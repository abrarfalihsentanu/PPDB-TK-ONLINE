<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PembayaranModel;
use App\Models\PendaftaranModel;
use App\Models\TahunAjaranModel;

class Pembayaran extends BaseController
{
    protected $pembayaranModel;
    protected $pendaftaranModel;
    protected $tahunAjaranModel;
    protected $validation;

    public function __construct()
    {
        $this->pembayaranModel = new PembayaranModel();
        $this->pendaftaranModel = new PendaftaranModel();
        $this->tahunAjaranModel = new TahunAjaranModel();
        $this->validation = \Config\Services::validation();
        helper(['auth', 'form']);
    }

    /**
     * List semua pembayaran dengan filter
     */
    public function index()
    {
        // Cek role
        if (!is_admin()) {
            return redirect()->to('/user/dashboard');
        }

        // Get filters dari request
        $filters = [
            'status_bayar' => $this->request->getGet('status_bayar') ?? 'pending',
            'tahun_ajaran_id' => $this->request->getGet('tahun_ajaran_id') ?? '',
            'search' => $this->request->getGet('search') ?? ''
        ];

        // Get data with pagination
        $perPage = 20;
        $page = (int) ($this->request->getGet('page') ?? 1);
        $offset = ($page - 1) * $perPage;

        $query = $this->pembayaranModel->getAllWithRelations($filters);
        $total = $query->countAllResults(false);
        $pembayaran = $query->limit($perPage, $offset)->get()->getResult();

        // Get tahun ajaran untuk filter dropdown
        $tahunAjaran = $this->tahunAjaranModel->findAll();

        // Pagination
        $pager = \Config\Services::pager();
        $pageLinks = $pager->makeLinks($page, $perPage, $total);

        $data = [
            'title' => 'Verifikasi Pembayaran',
            'pembayaran' => $pembayaran,
            'tahun_ajaran' => $tahunAjaran,
            'filters' => $filters,
            'total' => $total,
            'page' => $page,
            'pageLinks' => $pageLinks
        ];

        return view('admin/pembayaran/index', $data);
    }

    /**
     * Detail dan form verifikasi pembayaran
     */
    public function verify($pembayaranId)
    {
        // Cek role
        if (!is_admin()) {
            return redirect()->to('/user/dashboard');
        }

        // Get pembayaran dengan relasi
        $pembayaran = $this->pembayaranModel->getWithRelations($pembayaranId);

        if (!$pembayaran) {
            return $this->response->setStatusCode(404)->setBody('Data pembayaran tidak ditemukan');
        }

        // Get pendaftaran detail
        $pendaftaran = $this->pendaftaranModel->find($pembayaran->pendaftaran_id);

        $data = [
            'title' => 'Verifikasi Pembayaran',
            'pembayaran' => $pembayaran,
            'pendaftaran' => $pendaftaran,
            'validation' => $this->validation
        ];

        return view('admin/pembayaran/verify', $data);
    }

    /**
     * Proses verifikasi pembayaran
     */
    public function processVerify($pembayaranId)
    {
        // Cek role
        if (!is_admin()) {
            return redirect()->to('/user/dashboard');
        }

        // Get pembayaran
        $pembayaran = $this->pembayaranModel->find($pembayaranId);

        if (!$pembayaran) {
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan');
        }

        // Validasi input
        $rules = [
            'status_bayar' => [
                'rules' => 'required|in_list[verified,rejected]',
                'errors' => [
                    'required' => 'Status harus dipilih',
                    'in_list' => 'Status tidak valid'
                ]
            ],
            'keterangan' => [
                'rules' => 'permit_empty',
                'errors' => []
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $status = $this->request->getPost('status_bayar');
        $keterangan = $this->request->getPost('keterangan');

        // Update pembayaran
        $updateData = [
            'status_bayar' => $status,
            'verified_by' => get_user_id(),
            'verified_at' => date('Y-m-d H:i:s'),
            'keterangan' => $keterangan ?: null
        ];

        if (!$this->pembayaranModel->update($pembayaranId, $updateData)) {
            return redirect()->back()->with('error', 'Gagal update pembayaran: ' . $this->pembayaranModel->errors());
        }

        // Jika verified, update status pendaftaran
        if ($status === 'verified') {
            $pendaftaran = $this->pendaftaranModel->find($pembayaran->pendaftaran_id);

            if ($pendaftaran) {
                $this->pendaftaranModel->update($pembayaran->pendaftaran_id, [
                    'status_pendaftaran' => 'pembayaran_verified'
                ]);
            }
        }

        // Log activity
        log_message('info', 'Admin ' . get_user_email() . ' melakukan verifikasi pembayaran ID: ' . $pembayaranId);

        set_message('success', 'Pembayaran berhasil diverifikasi');
        return redirect()->to('admin/pembayaran')->with('success', 'Pembayaran berhasil diverifikasi');
    }

    /**
     * Get statistics pembayaran (untuk dashboard)
     */
    public function getStats()
    {
        $tahunAjaranAktif = $this->tahunAjaranModel->getActive();

        if (!$tahunAjaranAktif) {
            return [
                'pending' => 0,
                'verified' => 0,
                'rejected' => 0,
                'total' => 0,
                'nominal_menunggu' => 0,
                'nominal_terverifikasi' => 0
            ];
        }

        // Count by status
        $pending = $this->pembayaranModel
            ->select('COUNT(*) as total', false)
            ->join('pendaftaran', 'pendaftaran.id = pembayaran.pendaftaran_id')
            ->where('pembayaran.status_bayar', 'pending')
            ->where('pendaftaran.tahun_ajaran_id', $tahunAjaranAktif->id)
            ->get()
            ->getRow();

        $verified = $this->pembayaranModel
            ->select('COUNT(*) as total', false)
            ->join('pendaftaran', 'pendaftaran.id = pembayaran.pendaftaran_id')
            ->where('pembayaran.status_bayar', 'verified')
            ->where('pendaftaran.tahun_ajaran_id', $tahunAjaranAktif->id)
            ->get()
            ->getRow();

        $rejected = $this->pembayaranModel
            ->select('COUNT(*) as total', false)
            ->join('pendaftaran', 'pendaftaran.id = pembayaran.pendaftaran_id')
            ->where('pembayaran.status_bayar', 'rejected')
            ->where('pendaftaran.tahun_ajaran_id', $tahunAjaranAktif->id)
            ->get()
            ->getRow();

        return [
            'pending' => (int) ($pending->total ?? 0),
            'verified' => (int) ($verified->total ?? 0),
            'rejected' => (int) ($rejected->total ?? 0),
            'total' => (int) ($pending->total ?? 0) + (int) ($verified->total ?? 0) + (int) ($rejected->total ?? 0)
        ];
    }
}
