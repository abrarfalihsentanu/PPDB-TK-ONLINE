<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\PendaftaranModel;
use App\Models\PembayaranModel;
use App\Models\TahunAjaranModel;

class Pembayaran extends BaseController
{
    protected $pendaftaranModel;
    protected $pembayaranModel;
    protected $tahunAjaranModel;
    protected $validation;

    public function __construct()
    {
        $this->pendaftaranModel = new PendaftaranModel();
        $this->pembayaranModel = new PembayaranModel();
        $this->tahunAjaranModel = new TahunAjaranModel();
        $this->validation = \Config\Services::validation();
        helper(['auth', 'form', 'rupiah']);
    }

    /**
     * List pembayaran user
     */
    public function index()
    {
        // Cek role
        if (!is_orang_tua()) {
            return redirect()->to('/admin/pembayaran');
        }

        $userId = get_user_id();
        $tahunAjaranAktif = $this->tahunAjaranModel->getActive();

        // Get pendaftaran user
        $pendaftaran = [];
        if ($tahunAjaranAktif) {
            $pendaftaran = $this->pendaftaranModel->where('user_id', $userId)
                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->first();
        }

        // Get pembayaran
        $pembayaranData = [];
        if ($pendaftaran) {
            $pembayaranData = $this->pembayaranModel->where('pendaftaran_id', $pendaftaran->id)
                ->orderBy('created_at', 'DESC')
                ->findAll();
        }

        $data = [
            'title' => 'Pembayaran Pendaftaran',
            'tahun_ajaran' => $tahunAjaranAktif,
            'pendaftaran' => $pendaftaran,
            'pembayaran' => $pembayaranData
        ];

        return view('user/pembayaran/index', $data);
    }

    /**
     * Upload bukti pembayaran
     */
    public function upload($pendaftaranId)
    {
        // Cek role
        if (!is_orang_tua()) {
            return redirect()->to('/admin/pembayaran');
        }

        // Get pendaftaran
        $pendaftaran = $this->pendaftaranModel->find($pendaftaranId);

        if (!$pendaftaran) {
            return redirect()->to('/user/pembayaran')->with('error', 'Data pendaftaran tidak ditemukan');
        }

        // Cek ownership
        if ($pendaftaran->user_id !== get_user_id()) {
            return redirect()->to('/user/pembayaran')->with('error', 'Akses ditolak');
        }

        // Get tahun ajaran
        $tahunAjaran = $this->tahunAjaranModel->find($pendaftaran->tahun_ajaran_id);

        // Get pembayaran existing
        $pembayaran = $this->pembayaranModel->where('pendaftaran_id', $pendaftaranId)->first();

        // Check if form submitted
        if ($this->request->getMethod() === 'post') {
            return $this->processUpload($pendaftaranId, $pendaftaran, $pembayaran);
        }

        $data = [
            'title' => 'Upload Bukti Pembayaran',
            'pendaftaran' => $pendaftaran,
            'tahun_ajaran' => $tahunAjaran,
            'pembayaran' => $pembayaran,
            'validation' => $this->validation
        ];

        return view('user/pembayaran/upload', $data);
    }

    /**
     * Process upload pembayaran
     */
    private function processUpload($pendaftaranId, $pendaftaran, $pembayaran)
    {
        // Validasi
        $rules = [
            'bukti_bayar' => [
                'rules' => 'uploaded[bukti_bayar]|mime_in[bukti_bayar,image/jpeg,image/png,application/pdf]|max_size[bukti_bayar,5120]',
                'errors' => [
                    'uploaded' => 'File bukti pembayaran harus diupload',
                    'mime_in' => 'File harus JPG, PNG, atau PDF',
                    'max_size' => 'Ukuran file maksimal 5 MB'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('bukti_bayar');

        if (!$file->isValid()) {
            return redirect()->back()->withInput()->with('error', 'File tidak valid');
        }

        // Create upload directory if not exists
        $uploadDir = FCPATH . 'uploads/pembayaran';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $fileName = $file->getRandomName();
        $filePath = 'uploads/pembayaran/' . $fileName;

        // Move file
        $file->move($uploadDir, $fileName);

        // Delete old file if exists
        if ($pembayaran && $pembayaran->bukti_bayar) {
            $oldFile = FCPATH . $pembayaran->bukti_bayar;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // Save to database
        if ($pembayaran) {
            // Update pembayaran
            $this->pembayaranModel->update($pembayaran->id, [
                'bukti_bayar' => $filePath,
                'status_bayar' => 'pending',
                'tanggal_bayar' => date('Y-m-d'),
                'keterangan' => null
            ]);

            // Update pendaftaran status
            $this->pendaftaranModel->update($pendaftaranId, [
                'status_pendaftaran' => 'pending'
            ]);

            set_message('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
        } else {
            // Create new pembayaran
            $this->pembayaranModel->insert([
                'pendaftaran_id' => $pendaftaranId,
                'jumlah' => $this->tahunAjaranModel->find($pendaftaran->tahun_ajaran_id)->biaya_pendaftaran,
                'bukti_bayar' => $filePath,
                'status_bayar' => 'pending',
                'tanggal_bayar' => date('Y-m-d'),
                'verified_by' => null,
                'verified_at' => null,
                'keterangan' => null
            ]);

            // Update pendaftaran status
            $this->pendaftaranModel->update($pendaftaranId, [
                'status_pendaftaran' => 'pending'
            ]);

            set_message('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
        }

        return redirect()->to('/user/pembayaran');
    }
}
