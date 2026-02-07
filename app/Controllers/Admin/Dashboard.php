<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PendaftaranModel;
use App\Models\TahunAjaranModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    protected $pendaftaranModel;
    protected $tahunAjaranModel;
    protected $userModel;

    public function __construct()
    {
        $this->pendaftaranModel = new PendaftaranModel();
        $this->tahunAjaranModel = new TahunAjaranModel();
        $this->userModel = new UserModel();
        helper(['auth']);
    }

    public function index()
    {
        // Cek role
        if (!is_admin()) {
            return redirect()->to('/user/dashboard');
        }

        // Get active tahun ajaran
        $tahunAjaranAktif = $this->tahunAjaranModel->getActive();

        // Total Statistics
        $data['total_pendaftar'] = $this->pendaftaranModel->countAll();
        $data['total_users'] = $this->userModel->countByRole('orang_tua');

        if ($tahunAjaranAktif) {
            $data['tahun_ajaran_aktif'] = $tahunAjaranAktif;
            $data['total_pendaftar_aktif'] = $this->pendaftaranModel
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->countAllResults();

            // Get statistics by status
            $data['stats'] = $this->pendaftaranModel->getStatistics($tahunAjaranAktif->id);

            // Kuota
            $data['sisa_kuota'] = $this->tahunAjaranModel->getSisaKuota($tahunAjaranAktif->id);

            // Pendaftaran per bulan (untuk chart)
            $data['pendaftaran_per_bulan'] = $this->getPendaftaranPerBulan($tahunAjaranAktif->id);

            // Latest registrations
            $data['latest_pendaftaran'] = $this->pendaftaranModel
                ->getWithRelations()
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResult();
        } else {
            $data['tahun_ajaran_aktif'] = null;
            $data['total_pendaftar_aktif'] = 0;
            $data['stats'] = [
                'draft' => 0,
                'pending' => 0,
                'pembayaran_verified' => 0,
                'diverifikasi' => 0,
                'diterima' => 0,
                'ditolak' => 0
            ];
            $data['sisa_kuota'] = 0;
            $data['pendaftaran_per_bulan'] = [];
            $data['latest_pendaftaran'] = [];
        }

        $data['title'] = 'Dashboard Admin';

        return view('admin/dashboard', $data);
    }

    private function getPendaftaranPerBulan($tahunAjaranId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('pendaftaran');

        $result = $builder->select('MONTH(created_at) as bulan, COUNT(*) as total')
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->groupBy('MONTH(created_at)')
            ->orderBy('MONTH(created_at)', 'ASC')
            ->get()
            ->getResultArray();

        // Format untuk Chart.js
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $data = array_fill(0, 12, 0);

        foreach ($result as $row) {
            $data[$row['bulan'] - 1] = (int) $row['total'];
        }

        return [
            'labels' => $months,
            'data' => $data
        ];
    }
}
