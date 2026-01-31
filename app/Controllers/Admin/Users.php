<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PendaftaranModel;

class Users extends BaseController
{
    protected $userModel;
    protected $pendaftaranModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->pendaftaranModel = new PendaftaranModel();
        helper(['auth', 'custom']);
    }

    public function index()
    {
        // Get filter parameters
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');

        $builder = $this->userModel->where('role', 'orang_tua');

        // Apply filters
        if ($status) {
            $builder->where('status', $status);
        }

        if ($search) {
            $builder->groupStart()
                ->like('username', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }

        $data['users'] = $builder->orderBy('created_at', 'DESC')->findAll();
        $data['status'] = $status;
        $data['search'] = $search;
        $data['title'] = 'Manajemen User';

        return view('admin/users/index', $data);
    }

    public function view($id)
    {
        $user = $this->userModel->find($id);

        if (!$user || $user->role != 'orang_tua') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan');
        }

        $data['user'] = $user;

        // Get all pendaftaran user
        $data['pendaftaran_list'] = $this->pendaftaranModel->getWithRelations()
            ->where('user_id', $id)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResult();

        $data['title'] = 'Detail User';

        return view('admin/users/view', $data);
    }

    public function toggleStatus($id)
    {
        $user = $this->userModel->find($id);

        if (!$user || $user->role != 'orang_tua') {
            set_message('danger', 'User tidak ditemukan');
            return redirect()->to('admin/users');
        }

        $newStatus = $user->status == 'active' ? 'inactive' : 'active';

        if ($this->userModel->update($id, ['status' => $newStatus])) {
            $message = $newStatus == 'active' ? 'User berhasil diaktifkan' : 'User berhasil dinonaktifkan';
            set_message('success', $message);
        } else {
            set_message('danger', 'Gagal mengubah status user');
        }

        return redirect()->to('admin/users');
    }

    public function resetPassword($id)
    {
        $user = $this->userModel->find($id);

        if (!$user || $user->role != 'orang_tua') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ]);
        }

        // Generate random password
        $newPassword = $this->generateRandomPassword();

        if ($this->userModel->update($id, ['password' => $newPassword])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Password berhasil direset',
                'password' => $newPassword
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal reset password'
            ]);
        }
    }

    private function generateRandomPassword($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $password;
    }
}
