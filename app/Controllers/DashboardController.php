<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends BaseController
{
    public function index()
    {
        $studentModel = new \App\Models\StudentModel();
        $auditModel   = new \App\Models\AuditLogModel();
        $certModel    = new \App\Models\CertificateModel();

        // 📊 Stats Aggregation
        $data['total_students'] = $studentModel->countAllResults();
        $data['pending_apps']   = $studentModel->where('status', 'pending')->countAllResults();
        $data['approved_apps']  = $studentModel->where('status', 'approved')->countAllResults();
        $data['rejected_apps']  = $studentModel->where('status', 'rejected')->countAllResults();
        
        // 📉 Registration Trend (Last 7 Days - Ensuring 0s are filled)
        $db = \Config\Database::connect();
        $rawTrend = $db->query("SELECT DATE(created_at) as date, COUNT(*) as count FROM students WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY DATE(created_at) ORDER BY date ASC")->getResultArray();
        
        $trendDates = [];
        $trendCounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $trendDates[] = $date;
            
            $found = false;
            foreach ($rawTrend as $row) {
                if ($row['date'] == $date) {
                    $trendCounts[] = $row['count'];
                    $found = true;
                    break;
                }
            }
            if (!$found) $trendCounts[] = 0;
        }
        $data['trend_labels'] = $trendDates;
        $data['trend_values'] = $trendCounts;

        // 🏢 Department Distribution
        $deptQuery = $db->query("SELECT department, COUNT(*) as count FROM students GROUP BY department");
        $data['dept_data'] = $deptQuery->getResultArray();

        // 📑 Recent Activity (Audit Logs)
        $data['recent_logs'] = $auditModel->orderBy('created_at', 'DESC')->limit(10)->findAll();

        // 📂 Document Stats
        $data['total_certs'] = $certModel->countAllResults();

        return view('dashboard', $data);
    }
}
