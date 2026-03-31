<?php

namespace App\Models;
use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name','email','phone','department',
        'profile_photo','resume','id_proof', 'signature', 'digital_signature_hash', 'status'
    ];
}