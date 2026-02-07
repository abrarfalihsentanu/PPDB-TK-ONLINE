<?php
helper('auth');
$userName = session()->get('username') ?? 'Guest';
$userEmail = session()->get('email') ?? '';
$userRole = get_user_role();
$roleLabel = $userRole == 'admin' ? 'Administrator' : 'Orang Tua';
?>

<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="ri-menu-line ri-lg"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search (Optional) -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
                <i class="ri-search-line ri-lg"></i>
                <input type="text" class="form-control border-0 shadow-none ps-2" placeholder="Cari..." style="width: 200px;" />
            </div>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Quick Links -->
            <?php if ($userRole == 'admin'): ?>
                <li class="nav-item me-2">
                    <a class="nav-link" href="<?= base_url('admin/pendaftaran') ?>" data-bs-toggle="tooltip" title="Data Pendaftaran">
                        <i class="ri-file-list-3-line ri-lg"></i>
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-item me-2">
                    <a class="nav-link" href="<?= base_url('user/dashboard') ?>" data-bs-toggle="tooltip" title="Status Pendaftaran">
                        <i class="ri-file-list-line ri-lg"></i>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Notifications (Optional - untuk development selanjutnya) -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="ri-notification-3-line ri-lg"></i>
                    <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end p-0">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h6 class="mb-0 me-auto">Notifikasi</h6>
                            <span class="badge rounded-pill bg-label-primary">3 Baru</span>
                        </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container">
                        <div class="text-center p-4">
                            <small class="text-muted">Belum ada notifikasi</small>
                        </div>
                    </li>
                </ul>
            </li>

            <!-- User Dropdown -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="<?= base_url('assets/img/avatars/1.png') ?>" alt="<?= $userName ?>" class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="<?= base_url('assets/img/avatars/1.png') ?>" alt="<?= $userName ?>" class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 small"><?= $userName ?></h6>
                                    <small class="text-muted"><?= $roleLabel ?></small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="<?= base_url('profile') ?>">
                            <i class="ri-user-3-line me-3 ri-lg"></i>
                            <span>Profil Saya</span>
                        </a>
                    </li>
                    <?php if ($userRole == 'admin'): ?>
                        <li>
                            <a class="dropdown-item" href="<?= base_url('settings') ?>">
                                <i class="ri-settings-3-line me-3 ri-lg"></i>
                                <span>Pengaturan</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>">
                            <i class="ri-logout-box-r-line me-3 ri-lg"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>