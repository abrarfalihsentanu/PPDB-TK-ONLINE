<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResetTokenToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'reset_token' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'password',
            ],
            'reset_token_expiry' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'reset_token',
            ],
            'remember_token' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'reset_token_expiry',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['reset_token', 'reset_token_expiry', 'remember_token']);
    }
}
