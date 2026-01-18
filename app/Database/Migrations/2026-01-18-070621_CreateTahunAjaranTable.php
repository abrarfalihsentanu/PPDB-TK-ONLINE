<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTahunAjaranTable extends Migration
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
            'nama_tahun' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'comment'    => 'Format: 2025/2026',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['aktif', 'nonaktif'],
                'default'    => 'nonaktif',
            ],
            'kuota' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'biaya_pendaftaran' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0,
            ],
            'tanggal_buka' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'tanggal_tutup' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('status');
        $this->forge->createTable('tahun_ajaran');
    }

    public function down()
    {
        $this->forge->dropTable('tahun_ajaran');
    }
}
