<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DokumenModel;
use App\Models\PembayaranModel;
use App\Models\PendaftaranModel;

/**
 * File Access Controller
 * Menghandle download dokumen dan pembayaran dengan permission check
 */
class FileAccess extends BaseController
{
    protected $dokumenModel;
    protected $pembayaranModel;
    protected $pendaftaranModel;

    public function __construct()
    {
        $this->dokumenModel = new DokumenModel();
        $this->pembayaranModel = new PembayaranModel();
        $this->pendaftaranModel = new PendaftaranModel();
        helper(['auth']);
    }

    /**
     * Download dokumen (KK, Akta, Foto)
     * Accessible by: Owner (Orang Tua) dan Admin
     */
    public function downloadDokumen($dokumenId)
    {
        // Validasi session
        if (!is_logged_in()) {
            return redirect()->to('auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Get dokumen
        $dokumen = $this->dokumenModel->find($dokumenId);

        if (!$dokumen) {
            return $this->response->setStatusCode(404)->setBody('File tidak ditemukan');
        }

        // Get pendaftaran untuk cek ownership
        $pendaftaran = $this->pendaftaranModel->find($dokumen->pendaftaran_id);

        if (!$pendaftaran) {
            return $this->response->setStatusCode(404)->setBody('Data pendaftaran tidak ditemukan');
        }

        // Cek permission:
        // - Admin bisa download semua dokumen
        // - Orang tua hanya bisa download dokumen miliknya sendiri
        $userId = get_user_id();
        $userRole = get_user_role();

        if ($userRole !== 'admin' && $pendaftaran->user_id !== $userId) {
            return $this->response->setStatusCode(403)->setBody('Akses ditolak');
        }

        // Validasi file path (prevent directory traversal)
        $filePath = FCPATH . $dokumen->path_file;

        // Ensure path is within allowed directory
        $realPath = realpath($filePath);
        $baseDir = realpath(FCPATH . 'uploads/dokumen/');

        if (!$realPath || strpos($realPath, $baseDir) !== 0 || !file_exists($realPath)) {
            return $this->response->setStatusCode(404)->setBody('File tidak ditemukan atau path tidak valid');
        }

        // Set header dan download file
        return $this->response->download($realPath, null);
    }

    /**
     * Download bukti pembayaran
     * Accessible by: Owner (Orang Tua) dan Admin
     */
    public function downloadBuktiBayar($pembayaranId)
    {
        // Validasi session
        if (!is_logged_in()) {
            return redirect()->to('auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Get pembayaran
        $pembayaran = $this->pembayaranModel->find($pembayaranId);

        if (!$pembayaran) {
            return $this->response->setStatusCode(404)->setBody('File tidak ditemukan');
        }

        // Get pendaftaran untuk cek ownership
        $pendaftaran = $this->pendaftaranModel->find($pembayaran->pendaftaran_id);

        if (!$pendaftaran) {
            return $this->response->setStatusCode(404)->setBody('Data pendaftaran tidak ditemukan');
        }

        // Cek permission
        $userId = get_user_id();
        $userRole = get_user_role();

        if ($userRole !== 'admin' && $pendaftaran->user_id !== $userId) {
            return $this->response->setStatusCode(403)->setBody('Akses ditolak');
        }

        // Validasi file path
        $filePath = FCPATH . $pembayaran->bukti_bayar;

        // Ensure path is within allowed directory
        $realPath = realpath($filePath);
        $baseDir = realpath(FCPATH . 'uploads/pembayaran/');

        if (!$realPath || strpos($realPath, $baseDir) !== 0 || !file_exists($realPath)) {
            return $this->response->setStatusCode(404)->setBody('File tidak ditemukan atau path tidak valid');
        }

        // Set header dan download file
        return $this->response->download($realPath, null);
    }

    /**
     * View/Preview dokumen (image)
     * Accessible by: Owner (Orang Tua) dan Admin
     */
    public function previewDokumen($dokumenId)
    {
        // Validasi session
        if (!is_logged_in()) {
            return redirect()->to('auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Get dokumen
        $dokumen = $this->dokumenModel->find($dokumenId);

        if (!$dokumen) {
            return $this->response->setStatusCode(404)->setBody('File tidak ditemukan');
        }

        // Get pendaftaran untuk cek ownership
        $pendaftaran = $this->pendaftaranModel->find($dokumen->pendaftaran_id);

        if (!$pendaftaran) {
            return $this->response->setStatusCode(404)->setBody('Data pendaftaran tidak ditemukan');
        }

        // Cek permission
        $userId = get_user_id();
        $userRole = get_user_role();

        if ($userRole !== 'admin' && $pendaftaran->user_id !== $userId) {
            return $this->response->setStatusCode(403)->setBody('Akses ditolak');
        }

        // Validasi file path
        $filePath = FCPATH . $dokumen->path_file;

        // Ensure path is within allowed directory
        $realPath = realpath($filePath);
        $baseDir = realpath(FCPATH . 'uploads/dokumen/');

        if (!$realPath || strpos($realPath, $baseDir) !== 0 || !file_exists($realPath)) {
            return $this->response->setStatusCode(404)->setBody('File tidak ditemukan atau path tidak valid');
        }

        // Get MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $realPath);
        finfo_close($finfo);

        // Set header dan tampilkan file
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($realPath) . '"')
            ->setBody(file_get_contents($realPath));
    }

    /**
     * Preview bukti pembayaran
     */
    public function previewBuktiBayar($pembayaranId)
    {
        // Validasi session
        if (!is_logged_in()) {
            return redirect()->to('auth/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Get pembayaran
        $pembayaran = $this->pembayaranModel->find($pembayaranId);

        if (!$pembayaran) {
            return $this->response->setStatusCode(404)->setBody('File tidak ditemukan');
        }

        // Get pendaftaran untuk cek ownership
        $pendaftaran = $this->pendaftaranModel->find($pembayaran->pendaftaran_id);

        if (!$pendaftaran) {
            return $this->response->setStatusCode(404)->setBody('Data pendaftaran tidak ditemukan');
        }

        // Cek permission
        $userId = get_user_id();
        $userRole = get_user_role();

        if ($userRole !== 'admin' && $pendaftaran->user_id !== $userId) {
            return $this->response->setStatusCode(403)->setBody('Akses ditolak');
        }

        // Validasi file path
        $filePath = FCPATH . $pembayaran->bukti_bayar;

        // Ensure path is within allowed directory
        $realPath = realpath($filePath);
        $baseDir = realpath(FCPATH . 'uploads/pembayaran/');

        if (!$realPath || strpos($realPath, $baseDir) !== 0 || !file_exists($realPath)) {
            return $this->response->setStatusCode(404)->setBody('File tidak ditemukan atau path tidak valid');
        }

        // Get MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $realPath);
        finfo_close($finfo);

        // Set header dan tampilkan file
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($realPath) . '"')
            ->setBody(file_get_contents($realPath));
    }
}
