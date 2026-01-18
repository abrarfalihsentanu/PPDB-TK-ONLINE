<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('auth');

        // Cek apakah user sudah login
        if (!is_logged_in()) {
            set_message('warning', 'Anda harus login terlebih dahulu');
            return redirect()->to('/auth/login');
        }

        // Ambil role user dari session
        $userRole = session()->get('role');

        // Jika tidak ada argument (role yang diizinkan), skip
        if ($arguments === null) {
            return;
        }

        // Pastikan $arguments adalah array
        if (!is_array($arguments)) {
            $arguments = [$arguments];
        }

        // Cek apakah role user termasuk dalam role yang diizinkan
        if (!in_array($userRole, $arguments)) {
            // Log unauthorized access attempt
            log_message('warning', 'Unauthorized access attempt by user ID: ' . session()->get('user_id') . ' to ' . current_url());

            // Throw 404 error untuk keamanan (jangan kasih tau user bahwa halaman ada tapi tidak diizinkan)
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
