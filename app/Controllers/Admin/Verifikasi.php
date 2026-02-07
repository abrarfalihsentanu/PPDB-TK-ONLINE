<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DokumenModel;
use App\Models\PendaftaranModel;
use App\Models\OrangTuaModel;
use App\Models\TahunAjaranModel;

class Verifikasi extends BaseController
{
    protected $dokumenModel;
    protected $pendaftaranModel;
    protected $orangTuaModel;
    protected $tahunAjaranModel;
    protected $validation;

    public function __construct()
    {
        $this->dokumenModel = new DokumenModel();
        $this->pendaftaranModel = new PendaftaranModel();
        $this->orangTuaModel = new OrangTuaModel();
        $this->tahunAjaranModel = new TahunAjaranModel();
        $this->validation = \Config\Services::validation();
        helper(['auth', 'form']);
    }

    /**
     * List pendaftaran yang sudah pembayaran verified (siap verifikasi dokumen)
     */
    public function index()
    {
        // Cek role
        if (!is_admin()) {
            return redirect()->to('/user/dashboard');
        }

        // Get filters
        $filters = [
            'search' => $this->request->getGet('search') ?? '',
            'tahun_ajaran_id' => $this->request->getGet('tahun_ajaran_id') ?? ''
        ];

        // Get data with pagination
        $perPage = 20;
        $page = (int) ($this->request->getGet('page') ?? 1);
        $offset = ($page - 1) * $perPage;

        // Query pendaftaran yang pembayaran_verified
        $builder = $this->pendaftaranModel
            ->select('pendaftaran.*, users.email, users.username, tahun_ajaran.nama_tahun')
            ->join('users', 'users.id = pendaftaran.user_id', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = pendaftaran.tahun_ajaran_id', 'left')
            ->where('pendaftaran.status_pendaftaran', 'pembayaran_verified');

        // Apply filters
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $builder->groupStart()
                ->like('pendaftaran.nomor_pendaftaran', $search)
                ->orLike('pendaftaran.nama_lengkap', $search)
                ->orLike('users.email', $search)
                ->groupEnd();
        }

        if (!empty($filters['tahun_ajaran_id'])) {
            $builder->where('pendaftaran.tahun_ajaran_id', $filters['tahun_ajaran_id']);
        }

        // Order by
        $builder->orderBy('pendaftaran.created_at', 'ASC');

        $total = $builder->countAllResults(false);
        $pendaftaran = $builder->limit($perPage, $offset)->get()->getResult();

        // Get tahun ajaran untuk filter
        $tahunAjaran = $this->tahunAjaranModel->findAll();

        // Pagination
        $pager = \Config\Services::pager();
        $pageLinks = $pager->makeLinks($page, $perPage, $total);

        $data = [
            'title' => 'Verifikasi Berkas Pendaftaran',
            'pendaftaran' => $pendaftaran,
            'tahun_ajaran' => $tahunAjaran,
            'filters' => $filters,
            'total' => $total,
            'page' => $page,
            'pageLinks' => $pageLinks
        ];

        return view('admin/verifikasi/index', $data);
    }

    /**
     * Form verifikasi dokumen untuk 1 pendaftaran
     */
    public function verifyDokumen($pendaftaranId)
    {
        // Cek role
        if (!is_admin()) {
            return redirect()->to('/user/dashboard');
        }

        // Get pendaftaran dengan relasi
        $pendaftaran = $this->pendaftaranModel
            ->select('pendaftaran.*, users.email, users.username, tahun_ajaran.nama_tahun')
            ->join('users', 'users.id = pendaftaran.user_id', 'left')
            ->join('tahun_ajaran', 'tahun_ajaran.id = pendaftaran.tahun_ajaran_id', 'left')
            ->where('pendaftaran.id', $pendaftaranId)
            ->first();

        if (!$pendaftaran) {
            return $this->response->setStatusCode(404)->setBody('Data pendaftaran tidak ditemukan');
        }

        // Get dokumen
        $dokumen = $this->dokumenModel->getByPendaftaran($pendaftaranId);

        // Get orang tua
        $orangTua = $this->orangTuaModel->where('pendaftaran_id', $pendaftaranId)->first();

        // Get status dokumen
        $statusDokumen = $this->dokumenModel->getStatusByPendaftaran($pendaftaranId);

        $data = [
            'title' => 'Verifikasi Berkas Pendaftaran',
            'pendaftaran' => $pendaftaran,
            'dokumen' => $dokumen,
            'orangTua' => $orangTua,
            'statusDokumen' => $statusDokumen,
            'validation' => $this->validation
        ];

        return view('admin/verifikasi/verify', $data);
    }

    /**
     * Proses verifikasi dokumen
     */
    public function processDokumen($pendaftaranId)
    {
        // Cek role
        if (!is_admin()) {
            return redirect()->to('/user/dashboard');
        }

        // Get pendaftaran
        $pendaftaran = $this->pendaftaranModel->find($pendaftaranId);

        if (!$pendaftaran) {
            return redirect()->back()->with('error', 'Data pendaftaran tidak ditemukan');
        }

        // Get request data
        $dokumenStatus = $this->request->getPost('dokumen_status') ?? [];
        $dokumenKeterangan = $this->request->getPost('dokumen_keterangan') ?? [];

        // Validasi minimal ada data
        if (empty($dokumenStatus)) {
            return redirect()->back()->with('error', 'Silakan verifikasi minimal 1 dokumen');
        }

        // Update setiap dokumen
        $allApproved = true;
        $hasRejected = false;

        foreach ($dokumenStatus as $dokumenId => $status) {
            $keterangan = $dokumenKeterangan[$dokumenId] ?? '';

            // Validasi status
            if (!in_array($status, ['approved', 'rejected', 'pending'])) {
                continue;
            }

            // Update dokumen
            $this->dokumenModel->update($dokumenId, [
                'status_verifikasi' => $status,
                'keterangan' => !empty($keterangan) ? $keterangan : null
            ]);

            if ($status !== 'approved') {
                $allApproved = false;
            }

            if ($status === 'rejected') {
                $hasRejected = true;
            }
        }

        // Update status pendaftaran berdasarkan hasil verifikasi dokumen
        if ($allApproved) {
            // Semua dokumen approved
            $this->pendaftaranModel->update($pendaftaranId, [
                'status_pendaftaran' => 'diverifikasi'
            ]);
            $message = 'Berkas diverifikasi dan semua dokumen LOLOS';
        } elseif ($hasRejected) {
            // Ada dokumen yang ditolak
            $this->pendaftaranModel->update($pendaftaranId, [
                'status_pendaftaran' => 'dokumen_ditolak'
            ]);
            $message = 'Verifikasi selesai namun ada dokumen yang DITOLAK';
        } else {
            // Ada dokumen yang pending
            $message = 'Verifikasi selesai dengan status PENDING';
        }

        // Log activity
        log_message('info', 'Admin ' . get_user_email() . ' melakukan verifikasi dokumen pendaftaran ID: ' . $pendaftaranId);

        set_message('success', $message);
        return redirect()->to('admin/verifikasi')->with('success', $message);
    }

    /**
     * Get statistics verifikasi (untuk dashboard)
     */
    public function getStats()
    {
        $tahunAjaranAktif = $this->tahunAjaranModel->getActive();

        $pending = $this->pendaftaranModel
            ->where('status_pendaftaran', 'pembayaran_verified')
            ->countAllResults();

        $diverifikasi = $this->pendaftaranModel
            ->where('status_pendaftaran', 'diverifikasi')
            ->countAllResults();

        $ditolak = $this->pendaftaranModel
            ->where('status_pendaftaran', 'dokumen_ditolak')
            ->countAllResults();

        return [
            'pending' => $pending,
            'diverifikasi' => $diverifikasi,
            'ditolak' => $ditolak,
            'total' => $pending + $diverifikasi + $ditolak
        ];
    }
}
