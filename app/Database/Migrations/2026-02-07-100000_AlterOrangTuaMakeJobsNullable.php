<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterOrangTuaMakeJobsNullable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('orang_tua', [
            'pekerjaan_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'pekerjaan_ibu' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('orang_tua', [
            'pekerjaan_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'pekerjaan_ibu' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
        ]);
    }
}
