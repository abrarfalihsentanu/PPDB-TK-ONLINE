<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrangTuaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pendaftaran_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique'     => true,
            ],
            'nama_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'nik_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '16',
                'null'       => true,
            ],
            'pekerjaan_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'penghasilan_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'telepon_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'nama_ibu' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'nik_ibu' => [
                'type'       => 'VARCHAR',
                'constraint' => '16',
                'null'       => true,
            ],
            'pekerjaan_ibu' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'penghasilan_ibu' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'telepon_ibu' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'nama_wali' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'null'       => true,
            ],
            'nik_wali' => [
                'type'       => 'VARCHAR',
                'constraint' => '16',
                'null'       => true,
            ],
            'pekerjaan_wali' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'hubungan_wali' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'telepon_wali' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('pendaftaran_id', 'pendaftaran', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('orang_tua');
    }

    public function down()
    {
        $this->forge->dropTable('orang_tua');
    }
}
