<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username'   => 'admin',
                'email'      => 'admin@ppdb.test',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'status'     => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'demo_user',
                'email'      => 'user@ppdb.test',
                'password'   => password_hash('user123', PASSWORD_DEFAULT),
                'role'       => 'orang_tua',
                'status'     => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert data
        foreach ($data as $user) {
            $this->db->table('users')->insert($user);
        }

        // Output pesan
        echo "User seeder berhasil dijalankan.\n";
        echo "Admin: admin@ppdb.test / admin123\n";
        echo "User Demo: user@ppdb.test / user123\n";
    }
}
