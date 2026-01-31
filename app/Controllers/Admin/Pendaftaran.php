<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PendaftaranModel;
use App\Models\OrangTuaModel;
use App\Models\DokumenModel;
use App\Models\TahunAjaranModel;

class Pendaftaran extends BaseController
{
    protected $pendaftaranModel;
    protected $orangTuaModel;
    protected $dokumenModel;
    protected $tahunAjaranModel;

    public function __construct()
    {
        $this->pendaftaranModel = new PendaftaranModel();
        $this->orangTuaModel = new OrangTuaModel();
        $this->dokumenModel = new DokumenModel();
        $this->tahunAjaranModel = new TahunAjaranModel();
        helper(['auth', 'form']);
    }

    /**
     * List all registrations with filters
     */
    public function index()
    {
        $tahunAjaranId = $this->request->getGet('tahun_ajaran_id');
        $status = $this->request->getGet('status');
        $page = (int) $this->request->getGet('page') ?? 1;
        $perPage = 20;

        // Query builder
        $builder = $this->pendaftaranModel->getWithRelations();

        if ($tahunAjaranId) {
            $builder->where('pendaftaran.tahun_ajaran_id', $tahunAjaranId);
        }

        if ($status) {
            $builder->where('pendaftaran.status_pendaftaran', $status);
        }

        $total = $builder->countAllResults(false);
        $data['pendaftaran'] = $builder->paginate($perPage);
        $data['pager'] = $this->pendaftaranModel->pager;
        $data['total'] = $total;
        $data['tahunAjaran'] = $this->tahunAjaranModel->findAll();
        $data['statuses'] = [
            'draft' => 'Draft',
            'pending' => 'Pending',
            'pembayaran_verified' => 'Pembayaran Verified',
            'diverifikasi' => 'Diverifikasi',
            'diterima' => 'Diterima',
            'ditolak' => 'Ditolak'
        ];
        $data['title'] = 'Daftar Pendaftaran';
        $data['currentFilter'] = [
            'tahun_ajaran_id' => $tahunAjaranId,
            'status' => $status
        ];

        return view('admin/pendaftaran/index', $data);
    }

    /**
     * View registration detail
     */
    public function view($pendaftaranId)
    {
        $pendaftaran = $this->pendaftaranModel->getWithRelations($pendaftaranId);

        if (!$pendaftaran) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $data['pendaftaran'] = $pendaftaran;
        $data['orang_tua'] = $this->orangTuaModel->getByPendaftaran($pendaftaranId);
        $data['dokumen'] = $this->dokumenModel->getByPendaftaran($pendaftaranId);
        $data['dokumen_stats'] = $this->dokumenModel->getStats($pendaftaranId);
        $data['title'] = 'Detail Pendaftaran - ' . $pendaftaran->nomor_pendaftaran;

        return view('admin/pendaftaran/view', $data);
    }

    /**
     * Update registration status
     */
    public function updateStatus($pendaftaranId)
    {
        $newStatus = $this->request->getPost('status');
        $keterangan = $this->request->getPost('keterangan');

        if (!$newStatus) {
            return $this->response->setJSON(['success' => false, 'message' => 'Status harus diisi'])->setStatusCode(422);
        }

        $data = [
            'status_pendaftaran' => $newStatus
        ];

        if ($keterangan) {
            $data['keterangan'] = $keterangan;
        }

        if ($this->pendaftaranModel->update($pendaftaranId, $data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Status berhasil diupdate']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengupdate status'])->setStatusCode(500);
    }

    /**
     * Reject registration
     */
    public function reject($pendaftaranId)
    {
        $pendaftaran = $this->pendaftaranModel->find($pendaftaranId);

        if (!$pendaftaran) {
            return redirect()->back()->with('error', 'Pendaftaran tidak ditemukan');
        }

        $keterangan = $this->request->getPost('keterangan') ?? 'Pendaftaran ditolak oleh admin';

        $this->pendaftaranModel->update($pendaftaranId, [
            'status_pendaftaran' => 'ditolak',
            'keterangan' => $keterangan
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil ditolak');
    }

    /**
     * Accept registration
     */
    public function accept($pendaftaranId)
    {
        $pendaftaran = $this->pendaftaranModel->find($pendaftaranId);

        if (!$pendaftaran) {
            return redirect()->back()->with('error', 'Pendaftaran tidak ditemukan');
        }

        // Check if all documents are uploaded
        $dokumenStats = $this->dokumenModel->getStats($pendaftaranId);
        
        if (!$dokumenStats['all_uploaded']) {
            return redirect()->back()->with('error', 'Semua dokumen belum diupload');
        }

        $this->pendaftaranModel->update($pendaftaranId, [
            'status_pendaftaran' => 'diterima'
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil diterima');
    }

    /**
     * Export to Excel
     */
    public function export()
    {
        $tahunAjaranId = $this->request->getGet('tahun_ajaran_id');
        $format = $this->request->getGet('format') ?? 'excel';

        $builder = $this->pendaftaranModel->getWithRelations();

        if ($tahunAjaranId) {
            $builder->where('pendaftaran.tahun_ajaran_id', $tahunAjaranId);
        }

        $data = $builder->findAll();

        if ($format == 'excel') {
            return $this->exportExcel($data);
        } else {
            return $this->exportPdf($data);
        }
    }

    private function exportExcel($data)
    {
        // Placeholder - will implement with PHPExcel
        return redirect()->back()->with('info', 'Export Excel sedang dikembangkan');
    }

    private function exportPdf($data)
    {
        // Placeholder - will implement with TCPDF
        return redirect()->back()->with('info', 'Export PDF sedang dikembangkan');
    }
}
