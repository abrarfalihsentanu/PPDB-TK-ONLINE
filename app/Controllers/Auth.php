<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $validation;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validation = \Config\Services::validation();
        helper(['auth', 'form']);
    }

    /**
     * Halaman Login
     */
    public function login()
    {
        // Jika sudah login, redirect ke dashboard
        if (is_logged_in()) {
            return $this->redirectToDashboard();
        }

        $data = [
            'title' => 'Login',
            'validation' => $this->validation
        ];

        return view('auth/login', $data);
    }

    /**
     * Process Login
     */
    public function attemptLogin()
    {
        // Validasi input
        $rules = [
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Email tidak valid'
                ]
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password harus diisi'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        // Cari user berdasarkan email
        $user = $this->userModel->getUserByEmail($email);

        if (!$user) {
            set_message('danger', 'Email tidak terdaftar');
            return redirect()->back()->withInput();
        }

        // Cek status user
        if ($user->status !== 'active') {
            set_message('danger', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
            return redirect()->back()->withInput();
        }

        // Verifikasi password
        if (!$this->userModel->verifyPassword($password, $user->password)) {
            set_message('danger', 'Password salah');
            return redirect()->back()->withInput();
        }

        // Buat session
        $this->setUserSession($user);

        // Set remember me cookie (optional)
        if ($remember) {
            $this->setRememberMe($user);
        }

        set_message('success', 'Login berhasil! Selamat datang, ' . $user->username);
        return $this->redirectToDashboard();
    }

    /**
     * Logout
     */
    public function logout()
    {
        // Destroy session
        session()->destroy();

        // Delete remember me cookie
        delete_cookie('remember_token');

        set_message('success', 'Anda telah berhasil logout');
        return redirect()->to('/auth/login');
    }

    /**
     * Halaman Register
     */
    public function register()
    {
        // Jika sudah login, redirect ke dashboard
        if (is_logged_in()) {
            return $this->redirectToDashboard();
        }

        $data = [
            'title' => 'Register',
            'validation' => $this->validation
        ];

        return view('auth/register', $data);
    }

    /**
     * Process Register
     */
    public function attemptRegister()
    {
        // Validasi input
        $rules = [
            'username' => [
                'rules' => 'required|min_length[3]|max_length[50]|is_unique[users.username]|alpha_numeric',
                'errors' => [
                    'required' => 'Username harus diisi',
                    'min_length' => 'Username minimal 3 karakter',
                    'max_length' => 'Username maksimal 50 karakter',
                    'is_unique' => 'Username sudah terdaftar',
                    'alpha_numeric' => 'Username hanya boleh huruf dan angka'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Email tidak valid',
                    'is_unique' => 'Email sudah terdaftar'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Password minimal 8 karakter'
                ]
            ],
            'confirm_password' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password harus diisi',
                    'matches' => 'Konfirmasi password tidak cocok'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan user baru
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'), // akan di-hash otomatis di model
            'role' => 'orang_tua',
            'status' => 'active'
        ];

        if ($this->userModel->insert($data)) {
            set_message('success', 'Registrasi berhasil! Silakan login.');
            return redirect()->to('/auth/login');
        } else {
            set_message('danger', 'Registrasi gagal. Silakan coba lagi.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Halaman Lupa Password
     */
    public function forgotPassword()
    {
        $data = [
            'title' => 'Lupa Password',
            'validation' => $this->validation
        ];

        return view('auth/forgot_password', $data);
    }

    /**
     * Process Forgot Password
     */
    public function processForgotPassword()
    {
        // Validasi email
        $rules = [
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Email tidak valid'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $user = $this->userModel->getUserByEmail($email);

        if (!$user) {
            set_message('danger', 'Email tidak terdaftar');
            return redirect()->back()->withInput();
        }

        // Generate reset token
        $token = generate_token();
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Simpan token ke database (bisa di tabel users atau tabel password_resets)
        $this->userModel->update($user->id, [
            'reset_token' => $token,
            'reset_token_expiry' => $expiry
        ]);

        // Simulasi: Tampilkan link reset (karena belum setup SMTP)
        $resetLink = base_url('auth/reset-password/' . $token);

        set_message('success', 'Link reset password telah digenerate. Link: <a href="' . $resetLink . '" target="_blank">' . $resetLink . '</a>');
        return redirect()->back();
    }

    /**
     * Halaman Reset Password
     */
    public function resetPassword($token = null)
    {
        if (!$token) {
            set_message('danger', 'Token tidak valid');
            return redirect()->to('/auth/forgot-password');
        }

        // Validasi token
        $user = $this->userModel->where('reset_token', $token)
            ->where('reset_token_expiry >=', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            set_message('danger', 'Token tidak valid atau sudah kadaluarsa');
            return redirect()->to('/auth/forgot-password');
        }

        $data = [
            'title' => 'Reset Password',
            'token' => $token,
            'validation' => $this->validation
        ];

        return view('auth/reset_password', $data);
    }

    /**
     * Process Reset Password
     */
    public function processResetPassword()
    {
        $token = $this->request->getPost('token');

        // Validasi token
        $user = $this->userModel->where('reset_token', $token)
            ->where('reset_token_expiry >=', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            set_message('danger', 'Token tidak valid atau sudah kadaluarsa');
            return redirect()->to('/auth/forgot-password');
        }

        // Validasi password baru
        $rules = [
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Password baru harus diisi',
                    'min_length' => 'Password minimal 8 karakter'
                ]
            ],
            'confirm_password' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password harus diisi',
                    'matches' => 'Konfirmasi password tidak cocok'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        // Update password dan hapus token
        $this->userModel->update($user->id, [
            'password' => $this->request->getPost('password'),
            'reset_token' => null,
            'reset_token_expiry' => null
        ]);

        set_message('success', 'Password berhasil direset. Silakan login dengan password baru.');
        return redirect()->to('/auth/login');
    }

    /**
     * Set user session
     */
    private function setUserSession($user)
    {
        $sessionData = [
            'user_id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'logged_in' => true
        ];

        session()->set($sessionData);
    }

    /**
     * Set remember me cookie
     */
    private function setRememberMe($user)
    {
        $token = generate_token();

        // Simpan token ke database
        $this->userModel->update($user->id, [
            'remember_token' => $token
        ]);

        // Set cookie (30 hari)
        set_cookie('remember_token', $token, 2592000);
    }

    /**
     * Redirect ke dashboard sesuai role
     */
    private function redirectToDashboard()
    {
        $role = session()->get('role');

        if ($role === 'admin') {
            return redirect()->to('/admin/dashboard');
        } else {
            return redirect()->to('/user/dashboard');
        }
    }
}
