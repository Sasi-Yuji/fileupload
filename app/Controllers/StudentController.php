<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\CertificateModel;

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

        $studentData = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'department' => $this->request->getPost('department'),
            'profile_photo' => $profileName,
            'resume' => $resumeName,
            'id_proof' => $idProofName,
            'signature' => $signatureName,
        ];

        $studentModel->insert($studentData);
        $studentId = $studentModel->insertID();

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