<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TahunAjaranModel;

class TestController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $tahunAjaranModel = new TahunAjaranModel();

        $users = $userModel->findAll();
        $tahunAjaran = $tahunAjaranModel->getActive();

        echo "<h1>Test Database Connection</h1>";
        echo "<h2>Users:</h2>";
        echo "<pre>";
        print_r($users);
        echo "</pre>";

        echo "<h2>Tahun Ajaran Aktif:</h2>";
        echo "<pre>";
        print_r($tahunAjaran);
        echo "</pre>";
    }
}
