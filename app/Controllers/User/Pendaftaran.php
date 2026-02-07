<?php

namespace App\Controllers\User;

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
        helper(['auth', 'form', 'file']);
    }

    public function create()
    {
        $userId = get_user_id();
        $tahunAjaranAktif = $this->tahunAjaranModel->getActive();

        if (!$tahunAjaranAktif) {
            set_message('warning', 'Belum ada tahun ajaran aktif');
            return redirect()->to('user/dashboard');
        }

        // Cek apakah user sudah punya pendaftaran untuk tahun ajaran aktif
        $existing = $this->pendaftaranModel->getByUser($userId, $tahunAjaranAktif->id);
        if ($existing) {
            return redirect()->to('user/dashboard');
        }

        $data['title'] = 'Daftar Baru';
        $data['tahun_ajaran'] = $tahunAjaranAktif;

        return view('user/pendaftaran/create', $data);
    }

    public function storeDataSiswa()
    {
        $userId = get_user_id();
        $tahunAjaranAktif = $this->tahunAjaranModel->getActive();

        if (!$tahunAjaranAktif) {
            return redirect()->back()->with('errors', 'Tidak ada tahun ajaran aktif');
        }

        $payload = [
            'user_id' => $userId,
            'tahun_ajaran_id' => $tahunAjaranAktif->id,
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'nik' => $this->request->getPost('nik'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
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
            'status_pendaftaran' => 'draft'
        ];

        if ($this->pendaftaranModel->insert($payload)) {
            $id = $this->pendaftaranModel->getInsertID();
            return redirect()->to('user/pendaftaran/preview/' . $id);
        }

        return redirect()->back()->withInput()->with('errors', $this->pendaftaranModel->errors());
    }

    public function storeDataOrangtua($id)
    {
        $userId = get_user_id();
        $pendaftaran = $this->pendaftaranModel->find($id);

        if (!$pendaftaran || $pendaftaran->user_id != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pendaftaran tidak ditemukan');
        }

        $payload = [
            'pendaftaran_id' => $id,
            'nama_ayah' => $this->request->getPost('nama_ayah'),
            'nik_ayah' => $this->request->getPost('nik_ayah'),
            'pekerjaan_ayah' => $this->request->getPost('pekerjaan_ayah'),
            'penghasilan_ayah' => $this->request->getPost('penghasilan_ayah'),
            'telepon_ayah' => $this->request->getPost('telepon_ayah'),
            'nama_ibu' => $this->request->getPost('nama_ibu'),
            'nik_ibu' => $this->request->getPost('nik_ibu'),
            'pekerjaan_ibu' => $this->request->getPost('pekerjaan_ibu'),
            'penghasilan_ibu' => $this->request->getPost('penghasilan_ibu'),
            'telepon_ibu' => $this->request->getPost('telepon_ibu'),
            'nama_wali' => $this->request->getPost('nama_wali'),
            'nik_wali' => $this->request->getPost('nik_wali'),
            'pekerjaan_wali' => $this->request->getPost('pekerjaan_wali'),
            'hubungan_wali' => $this->request->getPost('hubungan_wali'),
            'telepon_wali' => $this->request->getPost('telepon_wali'),
        ];

        // Upsert orang tua (unique by pendaftaran_id)
        $existing = $this->orangTuaModel->where('pendaftaran_id', $id)->first();
        if ($existing) {
            $this->orangTuaModel->update($existing->id, $payload);
        } else {
            $this->orangTuaModel->insert($payload);
        }

        return redirect()->to('user/pendaftaran/preview/' . $id);
    }

    /**
     * Menampilkan form data orang tua untuk pendaftaran
     */
    public function orangtua($id)
    {
        $userId = get_user_id();
        $pendaftaran = $this->pendaftaranModel->find($id);

        if (!$pendaftaran || $pendaftaran->user_id != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pendaftaran tidak ditemukan');
        }

        $data['pendaftaran_id'] = $id;
        $data['orang_tua'] = $this->orangTuaModel->where('pendaftaran_id', $id)->first();
        $data['title'] = 'Data Orang Tua';

        return view('user/pendaftaran/orangtua', $data);
    }

    public function uploadDokumen($id)
    {
        $userId = get_user_id();
        $pendaftaran = $this->pendaftaranModel->find($id);

        if (!$pendaftaran || $pendaftaran->user_id != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pendaftaran tidak ditemukan');
        }

        $files = [
            'kk' => $this->request->getFile('kk'),
            'akta' => $this->request->getFile('akta'),
            'foto' => $this->request->getFile('foto')
        ];

        $uploaded = 0;
        foreach ($files as $jenis => $file) {
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $ext = $file->getExtension();
                $newName = $jenis . '_' . $id . '_' . time() . '.' . $ext;
                $path = WRITEPATH . 'uploads/dokumen/';
                if (!is_dir($path)) {
                    mkdir($path, 0755, true);
                }
                $file->move($path, $newName);

                $this->dokumenModel->insert([
                    'pendaftaran_id' => $id,
                    'jenis_dokumen' => $jenis,
                    'nama_file' => $file->getName(),
                    'path_file' => 'writable/uploads/dokumen/' . $newName,
                    'status_verifikasi' => 'pending',
                    'uploaded_at' => date('Y-m-d H:i:s')
                ]);

                $uploaded++;
            }
        }

        if ($uploaded > 0) {
            set_message('success', "Berhasil meng-upload $uploaded file");
        } else {
            set_message('warning', 'Tidak ada file yang di-upload');
        }

        return redirect()->to('user/pendaftaran/preview/' . $id);
    }

    /**
     * Menampilkan form upload dokumen (GET)
     */
    public function upload($id)
    {
        $userId = get_user_id();
        $pendaftaran = $this->pendaftaranModel->find($id);

        if (!$pendaftaran || $pendaftaran->user_id != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pendaftaran tidak ditemukan');
        }

        $data['pendaftaran_id'] = $id;
        $data['title'] = 'Upload Dokumen';

        return view('user/pendaftaran/upload', $data);
    }

    public function preview($id)
    {
        $userId = get_user_id();
        $pendaftaran = $this->pendaftaranModel->getWithRelations($id);

        if (!$pendaftaran || $pendaftaran->user_id != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pendaftaran tidak ditemukan');
        }

        $data['pendaftaran'] = $pendaftaran;
        $data['orang_tua'] = $this->orangTuaModel->where('pendaftaran_id', $id)->first();
        $data['dokumen'] = $this->dokumenModel->where('pendaftaran_id', $id)->findAll();
        $data['title'] = 'Preview Pendaftaran';

        return view('user/pendaftaran/preview', $data);
    }

    public function submit($id)
    {
        $userId = get_user_id();
        $pendaftaran = $this->pendaftaranModel->find($id);

        if (!$pendaftaran || $pendaftaran->user_id != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pendaftaran tidak ditemukan');
        }

        // Validate completeness
        $orangTua = $this->orangTuaModel->where('pendaftaran_id', $id)->first();
        $dokumenCount = $this->dokumenModel->where('pendaftaran_id', $id)->countAllResults();

        if (!$orangTua || $dokumenCount < 3) {
            set_message('danger', 'Data belum lengkap. Pastikan mengisi data orang tua dan meng-upload semua dokumen.');
            return redirect()->to('user/pendaftaran/preview/' . $id);
        }

        $this->pendaftaranModel->update($id, ['status_pendaftaran' => 'pending']);
        set_message('success', 'Pendaftaran berhasil disubmit');
        return redirect()->to('user/dashboard');
    }

    public function edit($id)
    {
        $userId = get_user_id();
        $pendaftaran = $this->pendaftaranModel->find($id);

        if (!$pendaftaran || $pendaftaran->user_id != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pendaftaran tidak ditemukan');
        }

        if (!$this->pendaftaranModel->canEdit($id, $userId)) {
            set_message('warning', 'Pendaftaran tidak dapat diedit');
            return redirect()->to('user/dashboard');
        }

        $data['pendaftaran'] = $pendaftaran;
        $data['orang_tua'] = $this->orangTuaModel->where('pendaftaran_id', $id)->first();
        $data['title'] = 'Edit Pendaftaran';

        return view('user/pendaftaran/edit', $data);
    }

    public function update($id)
    {
        $userId = get_user_id();
        $pendaftaran = $this->pendaftaranModel->find($id);

        if (!$pendaftaran || $pendaftaran->user_id != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pendaftaran tidak ditemukan');
        }

        if (!$this->pendaftaranModel->canEdit($id, $userId)) {
            set_message('warning', 'Pendaftaran tidak dapat diedit');
            return redirect()->to('user/dashboard');
        }

        $payload = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'nik' => $this->request->getPost('nik'),
            'tempat_lahir' => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
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

        $this->pendaftaranModel->update($id, $payload);
        set_message('success', 'Pendaftaran berhasil diupdate');

        return redirect()->to('user/pendaftaran/preview/' . $id);
    }
}
