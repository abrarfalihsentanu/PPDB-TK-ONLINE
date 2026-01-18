<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TahunAjaranSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_tahun'        => '2025/2026',
                'status'            => 'aktif',
                'kuota'             => 60,
                'biaya_pendaftaran' => 500000,
                'tanggal_buka'      => '2025-01-01',
                'tanggal_tutup'     => '2025-06-30',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'nama_tahun'        => '2024/2025',
                'status'            => 'nonaktif',
                'kuota'             => 50,
                'biaya_pendaftaran' => 450000,
                'tanggal_buka'      => '2024-01-01',
                'tanggal_tutup'     => '2024-06-30',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert data
        foreach ($data as $tahun) {
            $this->db->table('tahun_ajaran')->insert($tahun);
        }

        echo "Tahun ajaran seeder berhasil dijalankan.\n";
        echo "Tahun ajaran aktif: 2025/2026\n";
        echo "Kuota: 60 siswa\n";
        echo "Biaya: Rp 500.000\n";
    }
}
