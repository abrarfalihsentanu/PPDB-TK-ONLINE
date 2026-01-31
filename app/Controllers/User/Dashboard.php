<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\PendaftaranModel;
use App\Models\TahunAjaranModel;

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

        $data['title'] = 'Dashboard';

        return view('user/dashboard', $data);
    }
}
