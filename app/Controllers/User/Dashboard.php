<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\PendaftaranModel;
use App\Models\TahunAjaranModel;
use App\Models\DokumenModel;
use App\Models\PembayaranModel;

class Dashboard extends BaseController
{
    protected $pendaftaranModel;
    protected $tahunAjaranModel;

    public function __construct()
    {
        $this->pendaftaranModel = new PendaftaranModel();
        $this->tahunAjaranModel = new TahunAjaranModel();
        helper(['auth']);
    }

    public function index()
    {
        $userId = get_user_id();
        $tahunAjaranAktif = $this->tahunAjaranModel->getActive();

        $data['tahun_ajaran_aktif'] = $tahunAjaranAktif;

        if ($tahunAjaranAktif) {
            // Get pendaftaran user untuk tahun ajaran aktif
            $data['pendaftaran'] = $this->pendaftaranModel->getByUser($userId, $tahunAjaranAktif->id);
        } else {
            $data['pendaftaran'] = null;
        }

        // Default timeline and pembayaran to avoid undefined variable errors in view
        $data['timeline'] = [];
        $data['pembayaran'] = null;

        // If there's a pendaftaran, gather dokumen and pembayaran to build a timeline
        if ($data['pendaftaran']) {
            $dokumenModel = new DokumenModel();
            $pembayaranModel = new PembayaranModel();

            $dokumenList = $dokumenModel->where('pendaftaran_id', $data['pendaftaran']->id)->findAll();
            $pembayaran = $pembayaranModel->where('pendaftaran_id', $data['pendaftaran']->id)->orderBy('id', 'DESC')->first();

            $data['dokumen'] = $dokumenList;
            $data['pembayaran'] = $pembayaran;

            $timeline = [];

            // Pendaftaran dibuat
            $timeline[] = [
                'title' => 'Pendaftaran dibuat',
                'icon'  => 'ri-user-line',
                'color' => 'primary',
                'date'  => $data['pendaftaran']->created_at ? date('d/m/Y', strtotime($data['pendaftaran']->created_at)) : null,
                'status' => 'completed'
            ];

            // Upload dokumen
            $dokumenCount = is_array($dokumenList) ? count($dokumenList) : 0;
            $dokumenStatus = 'pending';
            $dokumenColor = 'warning';
            if ($dokumenCount >= 3) {
                $dokumenStatus = 'completed';
                $dokumenColor = 'success';
            } elseif ($dokumenCount > 0) {
                $dokumenStatus = 'process';
                $dokumenColor = 'info';
            }
            $timeline[] = [
                'title' => 'Upload Dokumen',
                'icon'  => 'ri-file-list-3-line',
                'color' => $dokumenColor,
                'date'  => $dokumenCount > 0 ? ($dokumenList[0]->uploaded_at ?? null) : null,
                'status' => $dokumenStatus
            ];

            // Pembayaran
            if ($pembayaran) {
                $payStatus = $pembayaran->status_bayar == 'verified' ? 'completed' : 'process';
                $payColor = $pembayaran->status_bayar == 'verified' ? 'success' : 'info';
                $timeline[] = [
                    'title' => 'Pembayaran',
                    'icon'  => 'ri-bank-card-line',
                    'color' => $payColor,
                    'date'  => $pembayaran->tanggal_bayar ?? ($pembayaran->created_at ?? null),
                    'status' => $payStatus
                ];
            } else {
                $timeline[] = [
                    'title' => 'Pembayaran',
                    'icon'  => 'ri-bank-card-line',
                    'color' => 'secondary',
                    'date'  => null,
                    'status' => 'waiting'
                ];
            }

            // Verifikasi akhir
            $verifStatus = in_array($data['pendaftaran']->status_pendaftaran, ['diverifikasi', 'diterima']) ? 'completed' : ($data['pendaftaran']->status_pendaftaran == 'dokumen_ditolak' ? 'rejected' : 'waiting');
            $verifColor = $verifStatus == 'completed' ? 'primary' : ($verifStatus == 'rejected' ? 'danger' : 'secondary');
            $timeline[] = [
                'title' => 'Verifikasi Admin',
                'icon'  => 'ri-checkbox-circle-line',
                'color' => $verifColor,
                'date'  => null,
                'status' => $verifStatus
            ];

            $data['timeline'] = $timeline;
        }

        $data['title'] = 'Dashboard';

        return view('user/dashboard', $data);
    }
}
