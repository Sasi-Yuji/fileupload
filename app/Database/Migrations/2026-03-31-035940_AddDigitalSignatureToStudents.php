<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDigitalSignatureToStudents extends Migration
{
    public function up()
    {
        $this->forge->addColumn('students', [
            'digital_signature_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'null'       => true,
                'after'      => 'signature',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('students', 'digital_signature_hash');
    }
}
