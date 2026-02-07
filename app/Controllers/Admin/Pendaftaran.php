<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PendaftaranModel;
use App\Models\OrangTuaModel;
use App\Models\DokumenModel;

class Pendaftaran extends BaseController
{
    protected $pendaftaranModel;
    protected $orangTuaModel;
    protected $dokumenModel;

    public function __construct()
    {
        $this->pendaftaranModel = new PendaftaranModel();
        $this->orangTuaModel = new OrangTuaModel();
        $this->dokumenModel = new DokumenModel();
        helper(['auth']);
    }

    public function index()
    {
        // Simple list with latest first
        $data['pendaftaran'] = $this->pendaftaranModel->getWithRelations()->orderBy('pendaftaran.created_at', 'DESC')->get()->getResult();
        $data['title'] = 'Data Pendaftaran';

        return view('admin/pendaftaran/index', $data);
    }

    public function view($id)
    {
        $pendaftaran = $this->pendaftaranModel->getWithRelations($id);

        if (!$pendaftaran) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pendaftaran tidak ditemukan');
        }

        $data['pendaftaran'] = $pendaftaran;
        $data['orang_tua'] = $this->orangTuaModel->where('pendaftaran_id', $id)->first();
        $data['dokumen'] = $this->dokumenModel->where('pendaftaran_id', $id)->findAll();
        $data['title'] = 'Detail Pendaftaran';

        return view('admin/pendaftaran/view', $data);
    }
}
