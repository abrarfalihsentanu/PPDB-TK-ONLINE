<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PendaftaranModel;
use App\Models\TahunAjaranModel;

class Penerimaan extends BaseController
{
    protected $pendaftaranModel;
    protected $tahunAjaranModel;
    protected $validation;

    public function __construct()
    {
        $this->pendaftaranModel = new PendaftaranModel();
        $this->tahunAjaranModel = new TahunAjaranModel();
        $this->validation = \Config\Services::validation();
        helper(['auth', 'form']);
    }

    /**
     * List pendaftaran yang sudah diverifikasi (siap untuk pengumuman penerimaan)
     */
    public function index()
    {
        // Cek role
        if (!is_admin()) {
            return redirect()->to('/user/dashboard');
        }

        // Get tahun ajaran aktif
        $tahunAjaranAktif = $this->tahunAjaranModel->getActive();

        if (!$tahunAjaranAktif) {
            $data = [
                'title' => 'Pengumuman Penerimaan',
                'pendaftaran' => [],
                'tahun_ajaran' => [],
                'sisaKuota' => 0,
                'message' => 'Tidak ada tahun ajaran yang aktif'
            ];
            return view('admin/penerimaan/index', $data);
        }

        // Get filters
        $filters = [
            'status_pendaftaran' => $this->request->getGet('status_pendaftaran') ?? 'diverifikasi',
            'search' => $this->request->getGet('search') ?? '',
            'tahun_ajaran_id' => $tahunAjaranAktif->id
        ];

        // Get data with pagination
        $perPage = 30;
        $page = (int) ($this->request->getGet('page') ?? 1);
        $offset = ($page - 1) * $perPage;

        // Query
        $builder = $this->pendaftaranModel
            ->select('pendaftaran.*, users.email, users.username, tahun_ajaran.nama_tahun, tahun_ajaran.kuota')
            ->join('users', 'users.id = pendaftaran.user_id', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = pendaftaran.tahun_ajaran_id', 'left')
            ->where('pendaftaran.tahun_ajaran_id', $tahunAjaranAktif->id);

        // Filter by status
        if (!empty($filters['status_pendaftaran']) && $filters['status_pendaftaran'] !== 'all') {
            $builder->where('pendaftaran.status_pendaftaran', $filters['status_pendaftaran']);
        }

        // Search
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $builder->groupStart()
                ->like('pendaftaran.nomor_pendaftaran', $search)
                ->orLike('pendaftaran.nama_lengkap', $search)
                ->orLike('users.email', $search)
                ->groupEnd();
        }

        // Order by (yang diverifikasi duluan)
        $statusOrder = "FIELD(pendaftaran.status_pendaftaran, 'diverifikasi', 'diterima', 'ditolak')";
        $builder->orderBy($statusOrder)->orderBy('pendaftaran.created_at', 'ASC');

        $total = $builder->countAllResults(false);
        $pendaftaran = $builder->limit($perPage, $offset)->get()->getResult();

        // Get sisa kuota
        $sisaKuota = $this->pendaftaranModel->getSisaKuota($tahunAjaranAktif->id);

        // Get tahun ajaran untuk filter
        $tahunAjaran = $this->tahunAjaranModel->findAll();

        // Pagination
        $pager = \Config\Services::pager();
        $pageLinks = $pager->makeLinks($page, $perPage, $total);

        // Hitung statistik
        $stats = [
            'diverifikasi' => $this->pendaftaranModel
                ->where('status_pendaftaran', 'diverifikasi')
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->countAllResults(),
            'diterima' => $this->pendaftaranModel
                ->where('status_pendaftaran', 'diterima')
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->countAllResults(),
            'ditolak' => $this->pendaftaranModel
                ->where('status_pendaftaran', 'ditolak')
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->countAllResults(),
        ];

        $data = [
            'title' => 'Pengumuman Penerimaan Siswa',
            'pendaftaran' => $pendaftaran,
            'tahun_ajaran_aktif' => $tahunAjaranAktif,
            'tahun_ajaran' => $tahunAjaran,
            'filters' => $filters,
            'total' => $total,
            'page' => $page,
            'pageLinks' => $pageLinks,
            'sisaKuota' => $sisaKuota,
            'stats' => $stats
        ];

        return view('admin/penerimaan/index', $data);
    }

    /**
     * Proses update status penerimaan
     * Bisa batch update (multiple selections) atau single
     */
    public function processStatus()
    {
        // Cek role
        if (!is_admin()) {
            return redirect()->to('/user/dashboard');
        }

        // Get request data
        $pendaftaranIds = $this->request->getPost('pendaftaran_ids') ?? [];
        $status = $this->request->getPost('status') ?? '';
        $keterangan = $this->request->getPost('keterangan') ?? '';

        // Validasi input
        if (empty($pendaftaranIds) || empty($status)) {
            return redirect()->back()->with('error', 'Silakan pilih pendaftaran dan status');
        }

        if (!in_array($status, ['diterima', 'ditolak', 'cadangan'])) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        // Jika diterima, validasi kuota
        if ($status === 'diterima') {
            $tahunAjaranAktif = $this->tahunAjaranModel->getActive();
            if (!$tahunAjaranAktif) {
                return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif');
            }

            $sisaKuota = $this->pendaftaranModel->getSisaKuota($tahunAjaranAktif->id);
            $countNewAccepted = count($pendaftaranIds);

            if ($countNewAccepted > $sisaKuota) {
                return redirect()->back()->with('error', 'Kuota tidak mencukupi. Sisa kuota: ' . $sisaKuota . ' pendaftar, tapi Anda memilih ' . $countNewAccepted);
            }
        }

        // Update status untuk setiap pendaftaran
        $updated = 0;
        foreach ($pendaftaranIds as $id) {
            // Verify ownership
            $pendaftaran = $this->pendaftaranModel->find($id);
            if (!$pendaftaran) {
                continue;
            }

            // Update
            $updateData = [
                'status_pendaftaran' => $status,
                'keterangan' => !empty($keterangan) ? $keterangan : null
            ];

            if ($this->pendaftaranModel->update($id, $updateData)) {
                $updated++;
            }
        }

        // Log activity
        log_message('info', 'Admin ' . get_user_email() . ' melakukan update status penerimaan untuk ' . $updated . ' pendaftaran');

        set_message('success', 'Status penerimaan berhasil diupdate untuk ' . $updated . ' pendaftar');
        return redirect()->back()->with('success', 'Status penerimaan berhasil diupdate');
    }

    /**
     * Get statistics penerimaan
     */
    public function getStats()
    {
        $tahunAjaranAktif = $this->tahunAjaranModel->getActive();

        if (!$tahunAjaranAktif) {
            return [
                'diverifikasi' => 0,
                'diterima' => 0,
                'ditolak' => 0,
                'cadangan' => 0
            ];
        }

        return [
            'diverifikasi' => $this->pendaftaranModel
                ->where('status_pendaftaran', 'diverifikasi')
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->countAllResults(),
            'diterima' => $this->pendaftaranModel
                ->where('status_pendaftaran', 'diterima')
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->countAllResults(),
            'ditolak' => $this->pendaftaranModel
                ->where('status_pendaftaran', 'ditolak')
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->countAllResults(),
            'cadangan' => $this->pendaftaranModel
                ->where('status_pendaftaran', 'cadangan')
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->countAllResults(),
        ];
    }
}
