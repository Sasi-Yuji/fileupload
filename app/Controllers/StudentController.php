<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\CertificateModel;
use App\Services\GoogleDriveService;
use App\Services\MessagingService;

class StudentController extends BaseController
{
    public function index()
    {
        $model = new StudentModel();

        $db = \Config\Database::connect();
        if (!$db->fieldExists('updated_at', 'students')) {
            $db->query("ALTER TABLE students ADD updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
            // Set existing records to current time so they aren't completely null initially
            $db->query("UPDATE students SET updated_at = CURRENT_TIMESTAMP WHERE updated_at IS NULL");
        }

        // Sorting by updated_at DESC (which will show the latest edited or added records first)
        $data['students'] = $model->orderBy('updated_at', 'DESC')->orderBy('id', 'DESC')->findAll();
        
        // Stats
        $data['total_students'] = count($data['students']);
        $data['pending_count'] = $model->where('status', 'pending')->countAllResults();
        $data['approved_count'] = $model->where('status', 'approved')->countAllResults();

        return view('student_list', $data);
    }

    public function create()
    {
        return view('student_form');
    }

    public function save()
    {
        $studentModel = new StudentModel();
        $certificateModel = new CertificateModel();

        // 🔹 Validation
        $rules = [
            'name' => 'required|max_length[50]',
            'email' => 'required|valid_email|max_length[100]',
            'phone' => 'required',
            'department' => 'required|max_length[100]',
            'profile_photo' => 'uploaded[profile_photo]|is_image[profile_photo]|max_size[profile_photo,2048]',
            'resume' => 'uploaded[resume]|ext_in[resume,pdf]|max_size[resume,2048]',
            'id_proof' => 'uploaded[id_proof]|is_image[id_proof]|max_size[id_proof,2048]',
            'signature_data' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 🔹 Aggregate Size Check for Certificates
        $certificates = $this->request->getFiles()['certificates'] ?? [];
        $totalCertSize = 0;
        foreach ($certificates as $file) {
            if ($file->isValid())
                $totalCertSize += $file->getSize();
        }
        if ($totalCertSize > 5 * 1024 * 1024) {
            return redirect()->back()->withInput()->with('errors', ['certificates' => 'Total certificates size must not exceed 5MB.']);
        }

        // 🔹 Get files after validation
        $profile = $this->request->getFile('profile_photo');
        $resume = $this->request->getFile('resume');
        $idProof = $this->request->getFile('id_proof');

        // 🔹 Upload profile
        $profileName = $profile->getRandomName();
        $profile->move('uploads/profile/', $profileName);

        // 🔹 Upload resume
        $resumeName = $resume->getRandomName();
        $resume->move('uploads/resume/', $resumeName);

        $idProofData = $this->request->getFile('id_proof');
        $idProofName = $idProofData->getRandomName();
        $idProofData->move('uploads/id_proof', $idProofName);

        // 🔹 Handle Base64 Signature
        $signatureData = $this->request->getPost('signature_data');
        $signatureName = $this->saveBase64Image($signatureData, 'uploads/signature/');

        // 🔹 Per-student disk quota check (10MB)
        $estimatedStudentSize = ($profile->getSize() + $resume->getSize() + $idProof->getSize());
        $sigSize = strlen(base64_decode(explode(',', $this->request->getPost('signature_data'))[1] ?? ''));
        $estimatedStudentSize += $sigSize + $totalCertSize;
        $quotaLimitBytes = 10 * 1024 * 1024; // 10 MB per student
        if ($estimatedStudentSize > $quotaLimitBytes) {
            return redirect()->back()->withInput()->with('errors', [
                'quota' => 'Total file size for this student exceeds the 10MB quota (' . round($estimatedStudentSize / 1048576, 2) . ' MB used).'
            ]);
        }

        // 🔹 Calculate Digital Signature Hash (Fingerprint)
        $fingerprintData = $this->request->getPost('name') . $this->request->getPost('email') . $profileName . time();
        $digitalHash = hash('sha256', $fingerprintData);

        $studentData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'department' => $this->request->getPost('department'),
            'profile_photo' => $profileName,
            'resume' => $resumeName,
            'id_proof' => $idProofName,
            'signature' => $signatureName,
            'digital_signature_hash' => $digitalHash,
        ];

        $studentModel->insert($studentData);
        $studentId = $studentModel->getInsertID();

        // 🔹 Audit Log
        $this->auditLog('student_create', $studentId, ['name' => $studentData['name'], 'hash' => $digitalHash]);

        // 🔹 Ecosystem Integration: Cloud Sync
        $googleDrive = new GoogleDriveService();
        $syncResult = $googleDrive->syncStudentRecord($studentId, $studentData['name']);

        // 🔹 Ecosystem Integration: Automated Messaging
        $messaging = new MessagingService();
        $msgResult = $messaging->sendRegistrationConfirmation($studentData['phone'], $studentData['name']);

        // 🔹 Audit Log (Ecosystem Events)
        $this->auditLog('ecosystem_sync', $studentId, ['drive' => $syncResult['status'], 'sms' => $msgResult['status']]);

        // 🔹 Upload multiple certificates
        foreach ($certificates as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $file->move('uploads/certificates/', $fileName);

                $certificateModel->insert([
                    'student_id' => $studentId,
                    'file_name' => $fileName
                ]);
            }
        }

        session()->setFlashdata('success', 'Student registered successfully!');
        return redirect()->to('/students');
    }

    public function view($id)
    {
        $studentModel = new StudentModel();
        $certificateModel = new CertificateModel();

        $data['student'] = $studentModel->find($id);
        if (!$data['student']) {
            return redirect()->to('/students');
        }

        $data['certificates'] = $certificateModel->where('student_id', $id)->findAll();

        return view('student_view', $data);
    }

    public function edit($id)
    {
        $studentModel = new StudentModel();

        $data['student'] = $studentModel->find($id);
        if (!$data['student']) {
            return redirect()->to('/students');
        }

        $certificateModel = new CertificateModel();
        $data['certificates'] = $certificateModel->where('student_id', $id)->findAll();

        return view('student_form', $data);
    }

    public function update($id)
    {
        $studentModel = new StudentModel();
        $certificateModel = new CertificateModel();

        $student = $studentModel->find($id);
        if (!$student) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Student not found']);
            }
            return redirect()->to('/students');
        }

        // 🔹 Validation (Partial for update)
        $rules = [
            'name' => 'required|max_length[50]',
            'email' => 'required|valid_email|max_length[100]',
            'department' => 'required|max_length[100]',
        ];

        // Conditional file rules
        $profile = $this->request->getFile('profile_photo');
        if ($profile && $profile->isValid()) {
            $rules['profile_photo'] = 'max_size[profile_photo,2048]|is_image[profile_photo]';
        }
        $resume = $this->request->getFile('resume');
        if ($resume && $resume->isValid()) {
            $rules['resume'] = 'max_size[resume,2048]|ext_in[resume,pdf]';
        }
        $idProof = $this->request->getFile('id_proof');
        if ($idProof && $idProof->isValid()) {
            $rules['id_proof'] = 'max_size[id_proof,2048]';
        }

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => implode(', ', $this->validator->getErrors())]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 🔹 Aggregate Certificate Size Check
        $files = $this->request->getFiles();
        if (isset($files['certificates'])) {
            $totalCertSize = 0;
            foreach ($files['certificates'] as $file) {
                if ($file->isValid())
                    $totalCertSize += $file->getSize();
            }
            if ($totalCertSize > 5 * 1024 * 1024) {
                $errMsg = 'Total certificates size must not exceed 5MB.';
                if ($this->request->isAJAX())
                    return $this->response->setJSON(['status' => 'error', 'message' => $errMsg]);
                return redirect()->back()->withInput()->with('errors', ['certificates' => $errMsg]);
            }
        }
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'department' => $this->request->getPost('department'),
        ];

        // Handle File Overwrites
        if ($profile && $profile->isValid()) {
            @unlink('uploads/profile/' . $student['profile_photo']);
            $newName = $profile->getRandomName();
            $profile->move('uploads/profile/', $newName);
            $data['profile_photo'] = $newName;
        }

        if ($resume && $resume->isValid()) {
            @unlink('uploads/resume/' . $student['resume']);
            $newName = $resume->getRandomName();
            $resume->move('uploads/resume/', $newName);
            $data['resume'] = $newName;
        }

        $idProof = $this->request->getFile('id_proof');
        if ($idProof && $idProof->isValid()) {
            if ($student['id_proof'] && file_exists('uploads/id_proof/' . $student['id_proof'])) {
                unlink('uploads/id_proof/' . $student['id_proof']);
            }
            $idProofName = $idProof->getRandomName();
            $idProof->move('uploads/id_proof', $idProofName);
            $data['id_proof'] = $idProofName;
        }

        $signatureData = $this->request->getPost('signature_data');
        if (!empty($signatureData)) {
            if ($student['signature'] && file_exists('uploads/signature/' . $student['signature'])) {
                unlink('uploads/signature/' . $student['signature']);
            }
            $signatureName = $this->saveBase64Image($signatureData, 'uploads/signature/');
            $data['signature'] = $signatureName;
        }

        $studentModel->update($id, $data);

        // Multiple Certificates
        $files = $this->request->getFiles();
        if (isset($files['certificates'])) {
            foreach ($files['certificates'] as $file) {
                if ($file->isValid()) {
                    $newName = $file->getRandomName();
                    $file->move('uploads/certificates/', $newName);
                    $certificateModel->insert([
                        'student_id' => $id,
                        'file_name' => $newName
                    ]);
                }
            }
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Record updated successfully!']);
        }

        session()->setFlashdata('success', 'Record updated successfully!');
        return redirect()->to('/students');
    }



    // ─── Storage Stats API ─────────────────────────────────────────────
    /**
     * Returns JSON with disk usage broken down by file type folder.
     * Used by the dashboard storage widget.
     */
    public function storageStats()
    {
        return $this->response->setJSON($this->getStorageStats());
    }

    /**
     * Calculates actual disk usage per upload sub-folder.
     * Returns bytes + human-readable size for each type and a grand total.
     */
    private function getStorageStats(): array
    {
        $basePath = FCPATH . 'uploads/';
        $folders  = [
            'Photos'       => 'profile',
            'Resumes'      => 'resume',
            'ID Proofs'    => 'id_proof',
            'Signatures'   => 'signature',
            'Certificates' => 'certificates',
        ];

        $stats = [];
        $totalBytes = 0;

        foreach ($folders as $label => $dir) {
            $path  = $basePath . $dir . '/';
            $files = is_dir($path) ? glob($path . '*') : [];
            $files = array_filter($files, 'is_file');
            $bytes = empty($files) ? 0 : array_sum(array_map('filesize', $files));
            $totalBytes += $bytes;
            $stats[$label] = [
                'bytes'      => $bytes,
                'human'      => $this->formatBytes($bytes),
                'file_count' => count($files),
            ];
        }

        // Quota config: warn at 80%, critical at 95%
        $quotaBytes = 500 * 1024 * 1024; // 500 MB soft limit
        $usedPercent = $quotaBytes > 0 ? round(($totalBytes / $quotaBytes) * 100, 1) : 0;

        return [
            'breakdown'    => $stats,
            'total_bytes'  => $totalBytes,
            'total_human'  => $this->formatBytes($totalBytes),
            'quota_bytes'  => $quotaBytes,
            'quota_human'  => $this->formatBytes($quotaBytes),
            'used_percent' => $usedPercent,
            'status'       => $usedPercent >= 95 ? 'critical' : ($usedPercent >= 80 ? 'warning' : 'ok'),
        ];
    }

    /** Human-readable file size formatter */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = (int) floor(log($bytes, 1024));
        return round($bytes / pow(1024, $i), $precision) . ' ' . $units[$i];
    }

    /**
     * Calculates total disk usage for a single student (all file types).
     * Used for per-student quota enforcement.
     */
    private function getStudentDiskUsage(array $student): int
    {
        $total = 0;
        $checks = [
            FCPATH . 'uploads/profile/'       => $student['profile_photo'] ?? '',
            FCPATH . 'uploads/resume/'        => $student['resume'] ?? '',
            FCPATH . 'uploads/id_proof/'      => $student['id_proof'] ?? '',
            FCPATH . 'uploads/signature/'     => $student['signature'] ?? '',
        ];
        foreach ($checks as $dir => $file) {
            if ($file && file_exists($dir . $file)) {
                $total += filesize($dir . $file);
            }
        }
        // Certificates
        $certModel = new CertificateModel();
        $certs = $certModel->where('student_id', $student['id'])->findAll();
        foreach ($certs as $cert) {
            $p = FCPATH . 'uploads/certificates/' . $cert['file_name'];
            if (file_exists($p)) $total += filesize($p);
        }
        return $total;
    }

    // ─── Filtered / Chunked Bulk Export ───────────────────────────────
    /**
     * Exports student documents as a ZIP, with optional filters:
     *   ?department=CS&status=approved&from=2025-01-01&to=2025-12-31
     *
     * Replaces the old all-at-once exportZip().
     */
    public function exportZip()
    {
        $studentModel    = new StudentModel();
        $certificateModel = new CertificateModel();

        // ── Read filters from GET ──
        $filterDept   = $this->request->getGet('department');
        $filterStatus = $this->request->getGet('status');
        $filterFrom   = $this->request->getGet('from');   // YYYY-MM-DD
        $filterTo     = $this->request->getGet('to');     // YYYY-MM-DD

        $query = $studentModel->orderBy('id', 'ASC');

        if (!empty($filterDept)) {
            $query->where('department', $filterDept);
        }
        if (!empty($filterStatus)) {
            $query->where('status', $filterStatus);
        }
        if (!empty($filterFrom)) {
            $query->where('created_at >=', $filterFrom . ' 00:00:00');
        }
        if (!empty($filterTo)) {
            $query->where('created_at <=', $filterTo . ' 23:59:59');
        }

        $students = $query->findAll();

        if (empty($students)) {
            return redirect()->back()->with('error', 'No students match the selected filters.');
        }

        // Enforce a safe chunk limit to prevent timeouts
        $chunkLimit = 200;
        if (count($students) > $chunkLimit) {
            $students = array_slice($students, 0, $chunkLimit);
        }

        // ── Build ZIP ──
        $zip     = new \ZipArchive();
        $zipName = 'export_' . date('Ymd_His');
        if ($filterDept)   $zipName .= '_' . preg_replace('/[^a-z0-9]/i', '', $filterDept);
        if ($filterStatus) $zipName .= '_' . $filterStatus;
        $zipName .= '.zip';

        // Ensure writable uploads dir exists
        $writableDir = WRITEPATH . 'uploads/';
        if (!is_dir($writableDir)) mkdir($writableDir, 0775, true);
        $zipPath = $writableDir . $zipName;

        if ($zip->open($zipPath, \ZipArchive::CREATE) !== true) {
            return redirect()->back()->with('error', 'Could not create ZIP archive.');
        }

        foreach ($students as $student) {
            $safeName = preg_replace('/[^a-z0-9_]/i', '_', $student['name']);
            $folder   = $safeName . '_ID' . $student['id'] . '/';

            $fileMap = [
                'Photos'       => [FCPATH . 'uploads/profile/',   $student['profile_photo']],
                'Resumes'      => [FCPATH . 'uploads/resume/',    $student['resume']],
                'ID_Proofs'    => [FCPATH . 'uploads/id_proof/',  $student['id_proof']],
                'Signatures'   => [FCPATH . 'uploads/signature/', $student['signature']],
            ];

            foreach ($fileMap as $category => [$dir, $file]) {
                if ($file && file_exists($dir . $file)) {
                    $zip->addFile($dir . $file, $folder . $category . '/' . $file);
                }
            }

            // Certificates sub-folder
            $certs = $certificateModel->where('student_id', $student['id'])->findAll();
            foreach ($certs as $cert) {
                $certPath = FCPATH . 'uploads/certificates/' . $cert['file_name'];
                if (file_exists($certPath)) {
                    $zip->addFile($certPath, $folder . 'Certificates/' . $cert['file_name']);
                }
            }
        }

        $zip->close();

        // Send and delete the temp file after download
        return $this->response
            ->setHeader('Content-Disposition', 'attachment; filename="' . $zipName . '"')
            ->download($zipPath, null);
    }

    public function updateStatus($id)
    {
        $studentModel = new StudentModel();
        $status = $this->request->getPost('status');
        
        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $studentModel->update($id, ['status' => $status]);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Status updated to ' . $status]);
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid status']);
    }

    public function delete($id)
    {
        $studentModel = new StudentModel();
        $certificateModel = new CertificateModel();

        $student = $studentModel->find($id);

        // 🔥 Delete files
        unlink('uploads/profile/' . $student['profile_photo']);
        unlink('uploads/resume/' . $student['resume']);
        unlink('uploads/id_proof/' . $student['id_proof']);

        // 🔥 Delete certificates
        $certs = $certificateModel->where('student_id', $id)->findAll();
        foreach ($certs as $cert) {
            unlink('uploads/certificates/' . $cert['file_name']);
        }

        $certificateModel->where('student_id', $id)->delete();
        $studentModel->delete($id);

        return redirect()->to('/students');
    }

    private function saveBase64Image($base64Data, $path)
    {
        if (empty($base64Data)) return null;

        $data = explode(',', $base64Data);
        if (count($data) < 2) return null;

        $img = base64_decode($data[1]);
        $fileName = uniqid() . '.png';
        
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        
        file_put_contents($path . $fileName, $img);
        return $fileName;
    }
}