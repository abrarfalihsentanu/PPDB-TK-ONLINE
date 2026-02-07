<?php
helper('auth');
$role = get_user_role();
$user_id = get_user_id();
$currentUri = uri_string();

// Get user info
$userModel = new \App\Models\UserModel();
$user = $userModel->find($user_id);
?>

<!-- Navbar Horizontal -->
<nav class="layout-navbar navbar navbar-expand-xl align-items-center" id="layout-navbar">
    <div class="container-xxl">
        <!-- Brand -->
        <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-6">
            <a href="<?= base_url('user/dashboard') ?>" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
                    <span class="text-primary">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L2 7V10H22V7L12 2Z" fill="currentColor" />
                            <path d="M4 11V20H8V11H4Z" fill="currentColor" opacity="0.6" />
                            <path d="M10 11V20H14V11H10Z" fill="currentColor" opacity="0.8" />
                            <path d="M16 11V20H20V11H16Z" fill="currentColor" opacity="0.6" />
                            <path d="M2 21H22V22H2V21Z" fill="currentColor" />
                        </svg>
                    </span>
                </span>
                <span class="app-brand-text demo menu-text fw-semibold">PPDB TK</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
                <i class="ri-close-line icon-sm"></i>
            </a>
        </div>

        <!-- Menu Toggle untuk Mobile -->
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                <i class="ri-menu-line icon-md"></i>
            </a>
        </div>

        <!-- Navbar Right -->
        <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
            <!-- Search -->
            <li class="nav-item navbar-search-wrapper me-sm-2 me-xl-1 mb-50 d-none d-sm-inline-block">
                <a class="nav-item nav-link search-toggler px-0" href="javascript:void(0);">
                    <span class="d-inline-block text-body-secondary fw-normal" id="autocomplete"></span>
                </a>
            </li>

            <!-- Language & Theme Switcher -->
            <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                <!-- Theme Switcher -->
                <li class="nav-item dropdown me-sm-2 me-xl-0">
                    <a class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill" id="nav-theme" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <i class="ri-sun-line icon-22px theme-icon-active"></i>
                        <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
                        <li>
                            <button type="button" class="dropdown-item align-items-center active" data-bs-theme-value="light" aria-pressed="false">
                                <span><i class="ri-sun-line icon-md me-3" data-icon="sun-line"></i>Light</span>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark" aria-pressed="true">
                                <span><i class="ri-moon-clear-line icon-md me-3" data-icon="moon-clear-line"></i>Dark</span>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="system" aria-pressed="false">
                                <span><i class="ri-computer-line icon-md me-3" data-icon="computer-line"></i>System</span>
                            </button>
                        </li>
                    </ul>
                </li>

                <!-- Notifications -->
                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                    <a class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <span class="position-relative">
                            <i class="ri-notification-2-line icon-22px"></i>
                            <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-0">
                        <li class="dropdown-menu-header border-bottom">
                            <div class="dropdown-header d-flex align-items-center py-3">
                                <h6 class="mb-0 me-auto">Notifikasi</h6>
                            </div>
                        </li>
                        <li class="dropdown-menu-body">
                            <p class="text-center text-muted py-3">Tidak ada notifikasi baru</p>
                        </li>
                    </ul>
                </li>

                <!-- User Account -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <img src="<?= base_url('assets/img/avatars/default.png') ?>" alt="avatar" class="rounded-circle" />
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-3 py-2">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-2">
                                        <div class="avatar avatar-online">
                                            <img src="<?= base_url('assets/img/avatars/default.png') ?>" alt="avatar" class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 small"><?= $user->nama_lengkap ?? 'User' ?></h6>
                                        <small class="text-body-secondary"><?= $role === 'admin' ? 'Administrator' : 'Orang Tua' ?></small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= base_url('user/profile') ?>">
                                <i class="ri-user-3-line icon-22px me-2"></i>
                                <span class="align-middle">Profil Saya</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= base_url('auth/logout') ?>">
                                <i class="ri-logout-box-r-line ms-2 icon-16px me-2"></i>
                                <span class="align-middle">Logout</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- / Navbar -->

<!-- Horizontal Menu -->
<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu flex-grow-0">
    <div class="container-xxl d-flex h-100">
        <ul class="menu-inner">
            <!-- Dashboard -->
            <li class="menu-item <?= (strpos($currentUri, 'user/dashboard') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('user/dashboard') ?>" class="menu-link">
                    <i class="menu-icon ri-dashboard-3-line"></i>
                    <div>Dashboard</div>
                </a>
            </li>

            <!-- Pendaftaran -->
            <li class="menu-item <?= (strpos($currentUri, 'user/pendaftaran') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('user/pendaftaran') ?>" class="menu-link">
                    <i class="menu-icon ri-file-list-line"></i>
                    <div>Pendaftaran</div>
                </a>
            </li>

            <!-- Pembayaran -->
            <li class="menu-item <?= (strpos($currentUri, 'user/pembayaran') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('user/pembayaran') ?>" class="menu-link">
                    <i class="menu-icon ri-money-dollar-circle-line"></i>
                    <div>Pembayaran</div>
                </a>
            </li>
        </ul>
    </div>
</aside>

<!-- / Horizontal Menu -->