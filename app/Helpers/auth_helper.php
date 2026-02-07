<?php

/**
 * Auth Helper
 * Helper functions untuk autentikasi
 */

if (!function_exists('is_logged_in')) {
    /**
     * Cek apakah user sudah login
     */
    function is_logged_in()
    {
        return session()->get('logged_in') === true;
    }
}

if (!function_exists('get_user_id')) {
    /**
     * Get user ID dari session
     */
    function get_user_id()
    {
        return session()->get('user_id');
    }
}

if (!function_exists('get_user_role')) {
    /**
     * Get user role dari session
     */
    function get_user_role()
    {
        return session()->get('role');
    }
}

if (!function_exists('get_user_email')) {
    /**
     * Get user email dari database berdasarkan user_id di session
     */
    function get_user_email()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return 'unknown';
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);

        return $user ? $user->email : 'unknown';
    }
}

if (!function_exists('is_admin')) {
    /**
     * Cek apakah user adalah admin
     */
    function is_admin()
    {
        return session()->get('role') === 'admin';
    }
}

if (!function_exists('is_orang_tua')) {
    /**
     * Cek apakah user adalah orang tua
     */
    function is_orang_tua()
    {
        return session()->get('role') === 'orang_tua';
    }
}

if (!function_exists('set_message')) {
    /**
     * Set flash message
     */
    function set_message($type, $message)
    {
        session()->setFlashdata('message', [
            'type' => $type,
            'text' => $message
        ]);
    }
}

if (!function_exists('get_message')) {
    /**
     * Get flash message
     */
    function get_message()
    {
        return session()->getFlashdata('message');
    }
}

if (!function_exists('hash_password')) {
    /**
     * Hash password
     */
    function hash_password($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}

if (!function_exists('verify_password')) {
    /**
     * Verify password
     */
    function verify_password($password, $hash)
    {
        return password_verify($password, $hash);
    }
}

if (!function_exists('generate_token')) {
    /**
     * Generate random token
     */
    function generate_token($length = 32)
    {
        return bin2hex(random_bytes($length));
    }
}
