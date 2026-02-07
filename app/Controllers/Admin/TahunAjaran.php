<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TahunAjaranModel;

class TahunAjaran extends BaseController
{
    protected $tahunAjaranModel;
    protected $validation;

    public function __construct()
    {
        $this->tahunAjaranModel = new TahunAjaranModel();
        $this->validation = \Config\Services::validation();
        helper(['auth', 'form']);
    }

    public function index()
    {
        $data['tahun_ajaran'] = $this->tahunAjaranModel->orderBy('nama_tahun', 'DESC')->findAll();
        $data['title'] = 'Data Tahun Ajaran';

        return view('admin/tahun_ajaran/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Tambah Tahun Ajaran';
        $data['validation'] = $this->validation;

        return view('admin/tahun_ajaran/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama_tahun' => [
                'rules' => 'required|regex_match[/^\d{4}\/\d{4}$/]|is_unique[tahun_ajaran.nama_tahun]',
                'errors' => [
                    'required' => 'Nama tahun ajaran harus diisi',
                    'regex_match' => 'Format tahun ajaran harus YYYY/YYYY (contoh: 2025/2026)',
                    'is_unique' => 'Tahun ajaran sudah ada'
                ]
            ],
            'kuota' => [
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Kuota harus diisi',
                    'integer' => 'Kuota harus berupa angka',
                    'greater_than' => 'Kuota minimal 1'
                ]
            ],
            'biaya_pendaftaran' => [
                'rules' => 'required|numeric|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Biaya pendaftaran harus diisi',
                    'numeric' => 'Biaya harus berupa angka',
                    'greater_than_equal_to' => 'Biaya minimal 0'
                ]
            ],
            'tanggal_buka' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal buka harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ],
            'tanggal_tutup' => [
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal tutup harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_tahun' => $this->request->getPost('nama_tahun'),
            'kuota' => $this->request->getPost('kuota'),
            'biaya_pendaftaran' => $this->request->getPost('biaya_pendaftaran'),
            'tanggal_buka' => $this->request->getPost('tanggal_buka'),
            'tanggal_tutup' => $this->request->getPost('tanggal_tutup'),
            'status' => 'nonaktif'
        ];

        if ($this->tahunAjaranModel->insert($data)) {
            set_message('success', 'Tahun ajaran berhasil ditambahkan');
            return redirect()->to('admin/tahun-ajaran');
        } else {
            set_message('danger', 'Gagal menambahkan tahun ajaran');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $data['tahun_ajaran'] = $this->tahunAjaranModel->find($id);

        if (!$data['tahun_ajaran']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Tahun ajaran tidak ditemukan');
        }

        $data['title'] = 'Edit Tahun Ajaran';
        $data['validation'] = $this->validation;

        return view('admin/tahun_ajaran/edit', $data);
    }

    public function update($id)
    {
        $tahunAjaran = $this->tahunAjaranModel->find($id);

        if (!$tahunAjaran) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Tahun ajaran tidak ditemukan');
        }

        $rules = [
            'nama_tahun' => [
                'rules' => 'required|regex_match[/^\d{4}\/\d{4}$/]|is_unique[tahun_ajaran.nama_tahun,id,' . $id . ']',
                'errors' => [
                    'required' => 'Nama tahun ajaran harus diisi',
                    'regex_match' => 'Format tahun ajaran harus YYYY/YYYY',
                    'is_unique' => 'Tahun ajaran sudah ada'
                ]
            ],
            'kuota' => 'required|integer|greater_than[0]',
            'biaya_pendaftaran' => 'required|numeric|greater_than_equal_to[0]',
            'tanggal_buka' => 'required|valid_date',
            'tanggal_tutup' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_tahun' => $this->request->getPost('nama_tahun'),
            'kuota' => $this->request->getPost('kuota'),
            'biaya_pendaftaran' => $this->request->getPost('biaya_pendaftaran'),
            'tanggal_buka' => $this->request->getPost('tanggal_buka'),
            'tanggal_tutup' => $this->request->getPost('tanggal_tutup')
        ];

        if ($this->tahunAjaranModel->update($id, $data)) {
            set_message('success', 'Tahun ajaran berhasil diupdate');
            return redirect()->to('admin/tahun-ajaran');
        } else {
            set_message('danger', 'Gagal mengupdate tahun ajaran');
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        $tahunAjaran = $this->tahunAjaranModel->find($id);

        if (!$tahunAjaran) {
            set_message('danger', 'Tahun ajaran tidak ditemukan');
            return redirect()->to('admin/tahun-ajaran');
        }

        // Cek apakah tahun ajaran masih aktif
        if ($tahunAjaran->status == 'aktif') {
            set_message('warning', 'Tidak dapat menghapus tahun ajaran yang sedang aktif');
            return redirect()->to('admin/tahun-ajaran');
        }

        // Cek apakah ada pendaftaran
        $db = \Config\Database::connect();
        $pendaftaranCount = $db->table('pendaftaran')
            ->where('tahun_ajaran_id', $id)
            ->countAllResults();

        if ($pendaftaranCount > 0) {
            set_message('warning', 'Tidak dapat menghapus tahun ajaran yang sudah memiliki pendaftaran');
            return redirect()->to('admin/tahun-ajaran');
        }

        if ($this->tahunAjaranModel->delete($id)) {
            set_message('success', 'Tahun ajaran berhasil dihapus');
        } else {
            set_message('danger', 'Gagal menghapus tahun ajaran');
        }

        return redirect()->to('admin/tahun-ajaran');
    }

    public function activate($id)
    {
        $tahunAjaran = $this->tahunAjaranModel->find($id);

        if (!$tahunAjaran) {
            set_message('danger', 'Tahun ajaran tidak ditemukan');
            return redirect()->to('admin/tahun-ajaran');
        }

        if ($this->tahunAjaranModel->activate($id)) {
            set_message('success', 'Tahun ajaran ' . $tahunAjaran->nama_tahun . ' berhasil diaktifkan');
        } else {
            set_message('danger', 'Gagal mengaktifkan tahun ajaran');
        }

        return redirect()->to('admin/tahun-ajaran');
    }
}
