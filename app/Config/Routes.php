<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route - redirect ke login
$routes->get('/', 'Auth::login');

// ============================================
// AUTH ROUTES (Public - No Authentication)
// ============================================
$routes->group('auth', function ($routes) {
    // Login
    $routes->get('login', 'Auth::login');
    $routes->post('attempt-login', 'Auth::attemptLogin');

    // Register
    $routes->get('register', 'Auth::register');
    $routes->post('attempt-register', 'Auth::attemptRegister');

    // Forgot Password
    $routes->get('forgot-password', 'Auth::forgotPassword');
    $routes->post('process-forgot-password', 'Auth::processForgotPassword');

    // Reset Password
    $routes->get('reset-password/(:any)', 'Auth::resetPassword/$1');
    $routes->post('process-reset-password', 'Auth::processResetPassword');

    // Logout
    $routes->get('logout', 'Auth::logout');
});

// ============================================
// ADMIN ROUTES (Require Auth & Admin Role)
// ============================================
$routes->group('admin', ['filter' => ['auth', 'role:admin']], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Tahun Ajaran
    $routes->get('tahun-ajaran', 'Admin\TahunAjaran::index');
    $routes->get('tahun-ajaran/create', 'Admin\TahunAjaran::create');
    $routes->post('tahun-ajaran/store', 'Admin\TahunAjaran::store');
    $routes->get('tahun-ajaran/edit/(:num)', 'Admin\TahunAjaran::edit/$1');
    $routes->post('tahun-ajaran/update/(:num)', 'Admin\TahunAjaran::update/$1');
    $routes->get('tahun-ajaran/delete/(:num)', 'Admin\TahunAjaran::delete/$1');
    $routes->get('tahun-ajaran/activate/(:num)', 'Admin\TahunAjaran::activate/$1');

    // Users Management
    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/view/(:num)', 'Admin\Users::view/$1');
    $routes->post('users/toggle-status/(:num)', 'Admin\Users::toggleStatus/$1');
    $routes->post('users/reset-password/(:num)', 'Admin\Users::resetPassword/$1');

    // Pendaftaran (List & Detail)
    $routes->get('pendaftaran', 'Admin\Pendaftaran::index');
    $routes->get('pendaftaran/view/(:num)', 'Admin\Pendaftaran::view/$1');

    // Verifikasi Pembayaran
    $routes->get('pembayaran', 'Admin\Pembayaran::index');
    $routes->get('pembayaran/verify/(:num)', 'Admin\Pembayaran::verify/$1');
    $routes->post('pembayaran/process-verify/(:num)', 'Admin\Pembayaran::processVerify/$1');

    // Verifikasi Berkas
    $routes->get('verifikasi', 'Admin\Verifikasi::index');
    $routes->get('verifikasi/dokumen/(:num)', 'Admin\Verifikasi::verifyDokumen/$1');
    $routes->post('verifikasi/process-dokumen/(:num)', 'Admin\Verifikasi::processDokumen/$1');

    // Penerimaan
    $routes->get('penerimaan', 'Admin\Penerimaan::index');
    $routes->post('penerimaan/process', 'Admin\Penerimaan::processStatus');

    // Laporan
    $routes->get('laporan', 'Admin\Laporan::index');
    $routes->get('laporan/export-pdf', 'Admin\Laporan::exportPdf');
    $routes->get('laporan/export-excel', 'Admin\Laporan::exportExcel');
});

// ============================================
// USER ROUTES (Require Auth & Orang Tua Role)
// ============================================
$routes->group('user', ['filter' => ['auth', 'role:orang_tua']], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'User\Dashboard::index');

    // Pendaftaran
    $routes->get('pendaftaran/create', 'User\Pendaftaran::create');
    $routes->get('pendaftaran/form/(:num)/step/(:num)', 'User\Pendaftaran::form/$1/$2');
    $routes->post('pendaftaran/store-data-siswa', 'User\Pendaftaran::storeDataSiswa');
    $routes->post('pendaftaran/store-data-orangtua/(:num)', 'User\Pendaftaran::storeDataOrangtua/$1');
    $routes->post('pendaftaran/upload-dokumen/(:num)', 'User\Pendaftaran::uploadDokumen/$1');
    $routes->get('pendaftaran/preview/(:num)', 'User\Pendaftaran::preview/$1');
    $routes->post('pendaftaran/submit/(:num)', 'User\Pendaftaran::submit/$1');

    // Edit Pendaftaran
    $routes->get('pendaftaran/edit/(:num)', 'User\Pendaftaran::edit/$1');
    $routes->post('pendaftaran/update/(:num)', 'User\Pendaftaran::update/$1');
    $routes->get('pendaftaran/view/(:num)', 'User\Pendaftaran::view/$1');

    // Pembayaran
    $routes->get('pembayaran', 'User\Pembayaran::index');
    $routes->post('pembayaran/upload/(:num)', 'User\Pembayaran::upload/$1');

    // Cetak
    $routes->get('cetak/bukti-pendaftaran/(:num)', 'User\Cetak::buktiPendaftaran/$1');
});

// ============================================
// TEST ROUTE (Development Only)
// ============================================
$routes->get('test', 'TestController::index');
