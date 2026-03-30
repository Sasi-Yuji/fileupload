<?php

namespace App\Models;
use CodeIgniter\Model;

class CertificateModel extends Model
{
    protected $table = 'certificates';
    protected $allowedFields = ['student_id','file_name'];
}