<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\PendaftaranModel;
use App\Models\OrangTuaModel;
use App\Models\DokumenModel;
use App\Models\TahunAjaranModel;
use CodeIgniter\Files\File;

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
     * Step 1: Create - Cek pendaftaran existing
     */
    public function create()
    {
        $userId = get_user_id();
        $tahunAjaranAktif = $this->tahunAjaranModel->getActive();

        // Cek apakah sudah ada pendaftaran untuk tahun ajaran aktif
        if ($tahunAjaranAktif) {
            $existingPendaftaran = $this->pendaftaranModel->getByUser($userId, $tahunAjaranAktif->id);
            
            if ($existingPendaftaran) {
                // Jika sudah ada, redirect ke edit
                return redirect()->to('user/pendaftaran/edit/' . $existingPendaftaran->id);
            }
        }

        // Buat pendaftaran baru dengan status draft
        $data = [
            'user_id' => $userId,
            'tahun_ajaran_id' => $tahunAjaranAktif ? $tahunAjaranAktif->id : null,
            'status_pendaftaran' => 'draft'
        ];

        $pendaftaranId = $this->pendaftaranModel->insert($data);

        return redirect()->to('user/pendaftaran/form/' . $pendaftaranId . '/step/1');
    }

    /**
     * Form display dengan step
     */
    public function form($pendaftaranId, $step = 1)
    {
        $userId = get_user_id();
        $pendaftaran = $this->pendaftaranModel->find($pendaftaranId);

        // Validasi kepemilikan
        if (!$pendaftaran || $pendaftaran->user_id != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Validasi status (hanya draft atau pending)
        if (!in_array($pendaftaran->status_pendaftaran, ['draft', 'pending'])) {
            return redirect()->to('user/dashboard')->with('error', 'Pendaftaran tidak dapat diedit');
        }

        $step = (int) $step;
        if ($step < 1 || $step > 4) {
            $step = 1;
        }

        $data['pendaftaran'] = $pendaftaran;
        $data['step'] = $step;
        $data['title'] = 'Form Pendaftaran - Step ' . $step;

        // Load data existing untuk edit
        if ($step == 1) {
            return view('user/pendaftaran/step1', $data);
        } elseif ($step == 2) {
            $data['orang_tua'] = $this->orangTuaModel->getByPendaftaran($pendaftaranId);
            return view('user/pendaftaran/step2', $data);
        } elseif ($step == 3) {
            $data['dokumen'] = $this->dokumenModel->getByPendaftaran($pendaftaranId);
            $data['dokumen_stats'] = $this->dokumenModel->getStats($pendaftaranId);
            return view('user/pendaftaran/step3', $data);
        } else {
            // Step 4: Preview & Submit
            $data['orang_tua'] = $this->orangTuaModel->getByPendaftaran($pendaftaranId);
            $data['dokumen'] = $this->dokumenModel->getByPendaftaran($pendaftaranId);
            $data['dokumen_stats'] = $this->dokumenModel->getStats($pendaftaranId);
            return view('user/pendaftaran/step4', $data);
        }
    }

    /**
     * Step 1: Store Data Siswa
     */
    public function storeDataSiswa()
    {
        $userId = get_user_id();
        $pendaftaranId = $this->request->getPost('pendaftaran_id');

        $pendaftaran = $this->pendaftaranModel->find($pendaftaranId);

        // Validasi kepemilikan
        if (!$pendaftaran || $pendaftaran->user_id != $userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak authorized'])->setStatusCode(403);
        }

        // Validasi umur (minimal 3 tahun, maksimal 7 tahun)
        $tanggalLahir = $this->request->getPost('tanggal_lahir');
        $tanggalLahirObj = \DateTime::createFromFormat('Y-m-d', $tanggalLahir);
        $hariIni = new \DateTime();
        $umur = $hariIni->diff($tanggalLahirObj)->y;

        if ($umur < 3 || $umur > 7) {
            return $this->response->setJSON(['success' => false, 'message' => 'Umur siswa harus antara 3-7 tahun'])->setStatusCode(422);
        }

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'nik' => $this->request->getPost('nik'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $tanggalLahir,
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'agama' => $this->request->getPost('agama'),
            'alamat' => $this->request->getPost('alamat'),
            'rt' => $this->request->getPost('rt'),
            'rw' => $this->request->getPost('rw'),
            'kelurahan' => $this->request->getPost('kelurahan'),
            'kecamatan' => $this->request->getPost('kecamatan'),
            'kota_kabupaten' => $this->request->getPost('kota_kabupaten'),
            'provinsi' => $this->request->getPost('provinsi'),
            'kode_pos' => $this->request->getPost('kode_pos'),
        ];

        if (!$this->pendaftaranModel->update($pendaftaranId, $data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan data',
                'errors' => $this->pendaftaranModel->errors()
            ])->setStatusCode(422);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data siswa berhasil disimpan',
            'next_step' => 'user/pendaftaran/form/' . $pendaftaranId . '/step/2'
        ]);
    }

    /**
     * Step 2: Store Data Orang Tua/Wali
     */
    public function storeDataOrangtua($pendaftaranId)
    {
        $userId = get_user_id();
        $pendaftaran = $this->pendaftaranModel->find($pendaftaranId);

        // Validasi kepemilikan
        if (!$pendaftaran || $pendaftaran->user_id != $userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak authorized'])->setStatusCode(403);
        }

        // Validasi NIK ayah dan ibu berbeda
        $nikAyah = $this->request->getPost('nik_ayah');
        $nikIbu = $this->request->getPost('nik_ibu');

        if ($nikAyah && $nikIbu && $nikAyah == $nikIbu) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'NIK ayah dan ibu harus berbeda'
            ])->setStatusCode(422);
        }

        $data = [
            'pendaftaran_id' => $pendaftaranId,
            'nama_ayah' => $this->request->getPost('nama_ayah'),
            'nik_ayah' => $nikAyah,
            'pekerjaan_ayah' => $this->request->getPost('pekerjaan_ayah'),
            'penghasilan_ayah' => $this->request->getPost('penghasilan_ayah'),
            'telepon_ayah' => $this->request->getPost('telepon_ayah'),
            'nama_ibu' => $this->request->getPost('nama_ibu'),
            'nik_ibu' => $nikIbu,
            'pekerjaan_ibu' => $this->request->getPost('pekerjaan_ibu'),
            'penghasilan_ibu' => $this->request->getPost('penghasilan_ibu'),
            'telepon_ibu' => $this->request->getPost('telepon_ibu'),
            'nama_wali' => $this->request->getPost('nama_wali'),
            'nik_wali' => $this->request->getPost('nik_wali'),
            'pekerjaan_wali' => $this->request->getPost('pekerjaan_wali'),
            'hubungan_wali' => $this->request->getPost('hubungan_wali'),
            'telepon_wali' => $this->request->getPost('telepon_wali'),
        ];

        // Delete existing orang tua data
        $this->orangTuaModel->deleteByPendaftaran($pendaftaranId);

        // Insert new orang tua data
        if (!$this->orangTuaModel->insert($data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan data orang tua',
                'errors' => $this->orangTuaModel->errors()
            ])->setStatusCode(422);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data orang tua berhasil disimpan',
            'next_step' => 'user/pendaftaran/form/' . $pendaftaranId . '/step/3'
        ]);
    }

    /**
     * Step 3: Upload Dokumen
     */
    public function uploadDokumen($pendaftaranId)
    {
        $userId = get_user_id();
        $pendaftaran = $this->pendaftaranModel->find($pendaftaranId);

        // Validasi kepemilikan
        if (!$pendaftaran || $pendaftaran->user_id != $userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak authorized'])->setStatusCode(403);
        }

        $jenisDokumen = $this->request->getPost('jenis_dokumen');
        $file = $this->request->getFile('file');

        // Validasi jenis dokumen
        $daftarJenisDokumen = ['kk', 'akta', 'foto'];
        if (!in_array($jenisDokumen, $daftarJenisDokumen)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Jenis dokumen tidak valid'])->setStatusCode(422);
        }

        // Validasi file
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'File tidak valid atau tidak diunggah'])->setStatusCode(422);
        }

        // Validasi ukuran file
        $maxSize = ($jenisDokumen == 'foto') ? 1024 * 1024 : 2 * 1024 * 1024; // 1MB for foto, 2MB for kk & akta
        if ($file->getSize() > $maxSize) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ukuran file terlalu besar. Maksimal ' . ($maxSize / 1024 / 1024) . 'MB'
            ])->setStatusCode(422);
        }

        // Validasi tipe file
        $allowedMimes = ($jenisDokumen == 'foto') 
            ? ['image/jpeg', 'image/png']
            : ['application/pdf', 'image/jpeg', 'image/png'];

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tipe file tidak diizinkan'
            ])->setStatusCode(422);
        }

        // Generate nama file
        $newName = $jenisDokumen . '_' . $pendaftaranId . '_' . time() . '.' . $file->getExtension();

        // Create upload directory if not exists
        $uploadPath = 'uploads/dokumen/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Move file
        if (!$file->move($uploadPath, $newName)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengunggah file'])->setStatusCode(500);
        }

        // Delete existing dokumen of same type
        $this->dokumenModel->deleteByJenis($pendaftaranId, $jenisDokumen);

        // Insert dokumen record
        $dokumenData = [
            'pendaftaran_id' => $pendaftaranId,
            'jenis_dokumen' => $jenisDokumen,
            'nama_file_asli' => $file->getClientName(),
            'nama_file' => $newName,
            'path_file' => $uploadPath . $newName,
            'ukuran_file' => $file->getSize(),
            'tipe_file' => $file->getMimeType(),
            'status_verifikasi' => 'pending',
        ];

        if (!$this->dokumenModel->insert($dokumenData)) {
            // Delete uploaded file if insert fails
            unlink($uploadPath . $newName);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan data dokumen',
                'errors' => $this->dokumenModel->errors()
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Dokumen berhasil diupload',
            'dokumen_id' => $this->dokumenModel->insertID()
        ]);
    }

    /**
     * Step 4: Preview & Submit
     */
    public function submit($pendaftaranId)
    {
        $userId = get_user_id();
        $pendaftaran = $this->pendaftaranModel->find($pendaftaranId);

        // Validasi kepemilikan
        if (!$pendaftaran || $pendaftaran->user_id != $userId) {
            return redirect()->to('user/dashboard')->with('error', 'Tidak authorized');
        }

        // Validasi data lengkap
        $orangTua = $this->orangTuaModel->getByPendaftaran($pendaftaranId);
        $dokumenStats = $this->dokumenModel->getStats($pendaftaranId);

        if (!$orangTua) {
            return redirect()->back()->with('error', 'Data orang tua belum lengkap');
        }

        if (!$dokumenStats['all_uploaded']) {
            return redirect()->back()->with('error', 'Semua dokumen belum diupload');
        }

        // Update status ke pending
        $this->pendaftaranModel->update($pendaftaranId, [
            'status_pendaftaran' => 'pending'
        ]);

        return redirect()->to('user/dashboard')->with('success', 'Pendaftaran berhasil disubmit. Menunggu verifikasi admin.');
    }

    /**
     * Edit Pendaftaran
     */
    public function edit($pendaftaranId)
    {
        $userId = get_user_id();
        $pendaftaran = $this->pendaftaranModel->find($pendaftaranId);

        // Validasi kepemilikan
        if (!$pendaftaran || $pendaftaran->user_id != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Cek status
        if (!in_array($pendaftaran->status_pendaftaran, ['draft', 'pending'])) {
            return redirect()->to('user/dashboard')->with('error', 'Pendaftaran tidak dapat diedit');
        }

        return redirect()->to('user/pendaftaran/form/' . $pendaftaranId . '/step/1');
    }

    /**
     * View Pendaftaran
     */
    public function view($pendaftaranId)
    {
        $userId = get_user_id();
        $pendaftaran = $this->pendaftaranModel->getWithRelations($pendaftaranId);

        // Validasi kepemilikan
        if (!$pendaftaran || $pendaftaran->user_id != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $data['pendaftaran'] = $pendaftaran;
        $data['orang_tua'] = $this->orangTuaModel->getByPendaftaran($pendaftaranId);
        $data['dokumen'] = $this->dokumenModel->getByPendaftaran($pendaftaranId);
        $data['title'] = 'Detail Pendaftaran';

        return view('user/pendaftaran/view', $data);
    }

    /**
     * Preview alias for view
     */
    public function preview($pendaftaranId)
    {
        return $this->view($pendaftaranId);
    }
}
