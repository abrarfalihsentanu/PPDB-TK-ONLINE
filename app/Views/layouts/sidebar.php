<?php
helper('auth');
$role = get_user_role();
$currentUri = uri_string();
?>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <!-- Brand -->
    <div class="app-brand demo">
        <a href="<?= base_url('/') ?>" class="app-brand-link">
            <span class="app-brand-logo demo me-1">
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
            <span class="app-brand-text demo menu-text fw-semibold ms-2">PPDB TK</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
            <i class="menu-toggle-icon d-xl-inline-block align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1 ps">
        <!-- DASHBOARD -->
        <li class="menu-item <?= ($currentUri == '' || $currentUri == 'dashboard' || strpos($currentUri, 'dashboard') !== false) ? 'active' : '' ?>">
            <a href="<?= base_url('dashboard') ?>" class="menu-link">
                <i class="menu-icon ri-dashboard-3-line"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <?php if ($role == 'admin'): ?>
            <!-- ADMIN MENU -->
            <li class="menu-header mt-4">
                <span class="menu-header-text">MASTER DATA</span>
            </li>

            <!-- Tahun Ajaran -->
            <li class="menu-item <?= (strpos($currentUri, 'admin/tahun-ajaran') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('admin/tahun-ajaran') ?>" class="menu-link">
                    <i class="menu-icon ri-calendar-line"></i>
                    <div>Tahun Ajaran</div>
                </a>
            </li>

            <!-- Manajemen User -->
            <li class="menu-item <?= (strpos($currentUri, 'admin/users') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('admin/users') ?>" class="menu-link">
                    <i class="menu-icon ri-user-settings-line"></i>
                    <div>Manajemen User</div>
                </a>
            </li>

            <li class="menu-header mt-4">
                <span class="menu-header-text">PENDAFTARAN</span>
            </li>

            <!-- Data Pendaftaran -->
            <li class="menu-item <?= (strpos($currentUri, 'admin/pendaftaran') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('admin/pendaftaran') ?>" class="menu-link">
                    <i class="menu-icon ri-file-list-3-line"></i>
                    <div>Data Pendaftaran</div>
                </a>
            </li>

            <!-- Verifikasi Pembayaran -->
            <li class="menu-item <?= (strpos($currentUri, 'admin/pembayaran') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('admin/pembayaran') ?>" class="menu-link">
                    <i class="menu-icon ri-bank-card-line"></i>
                    <div>Verifikasi Pembayaran</div>
                </a>
            </li>

            <!-- Verifikasi Berkas -->
            <li class="menu-item <?= (strpos($currentUri, 'admin/verifikasi') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('admin/verifikasi') ?>" class="menu-link">
                    <i class="menu-icon ri-file-check-line"></i>
                    <div>Verifikasi Berkas</div>
                </a>
            </li>

            <!-- Penerimaan -->
            <li class="menu-item <?= (strpos($currentUri, 'admin/penerimaan') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('admin/penerimaan') ?>" class="menu-link">
                    <i class="menu-icon ri-checkbox-circle-line"></i>
                    <div>Penerimaan Siswa</div>
                </a>
            </li>

            <li class="menu-header mt-4">
                <span class="menu-header-text">LAPORAN</span>
            </li>

            <!-- Laporan -->
            <li class="menu-item <?= (strpos($currentUri, 'admin/laporan') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('admin/laporan') ?>" class="menu-link">
                    <i class="menu-icon ri-file-chart-line"></i>
                    <div>Laporan PPDB</div>
                </a>
            </li>

        <?php else: ?>
            <!-- ORANG TUA MENU -->
            <li class="menu-header mt-4">
                <span class="menu-header-text">PENDAFTARAN</span>
            </li>

            <!-- Pendaftaran Baru -->
            <li class="menu-item <?= (strpos($currentUri, 'user/pendaftaran/create') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('user/pendaftaran/create') ?>" class="menu-link">
                    <i class="menu-icon ri-add-circle-line"></i>
                    <div>Daftar Baru</div>
                </a>
            </li>

            <!-- Status Pendaftaran -->
            <li class="menu-item <?= (strpos($currentUri, 'user/dashboard') !== false || strpos($currentUri, 'user/pendaftaran/preview') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('user/dashboard') ?>" class="menu-link">
                    <i class="menu-icon ri-file-list-line"></i>
                    <div>Status Pendaftaran</div>
                </a>
            </li>

            <!-- Pembayaran -->
            <li class="menu-item <?= (strpos($currentUri, 'user/pembayaran') !== false) ? 'active' : '' ?>">
                <a href="<?= base_url('user/pembayaran') ?>" class="menu-link">
                    <i class="menu-icon ri-money-dollar-circle-line"></i>
                    <div>Pembayaran</div>
                </a>
            </li>

            <li class="menu-header mt-4">
                <span class="menu-header-text">INFORMASI</span>
            </li>

            <!-- Panduan -->
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link" data-bs-toggle="modal" data-bs-target="#panduanModal">
                    <i class="menu-icon ri-questionnaire-line"></i>
                    <div>Panduan Pendaftaran</div>
                </a>
            </li>
        <?php endif; ?>

        <!-- PROFIL & LOGOUT -->
        <li class="menu-header mt-4">
            <span class="menu-header-text">AKUN</span>
        </li>

        <li class="menu-item">
            <a href="<?= base_url('auth/logout') ?>" class="menu-link">
                <i class="menu-icon ri-logout-box-line"></i>
                <div>Logout</div>
            </a>
        </li>
    </ul>
</aside>

<!-- Modal Panduan (untuk Orang Tua) -->
<?php if ($role != 'admin'): ?>
    <div class="modal fade" id="panduanModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Panduan Pendaftaran PPDB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="accordion" id="panduanAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#step1">
                                    <i class="ri-number-1 me-2"></i> Langkah 1: Registrasi Akun
                                </button>
                            </h2>
                            <div id="step1" class="accordion-collapse collapse show" data-bs-parent="#panduanAccordion">
                                <div class="accordion-body">
                                    Buat akun dengan mengisi username, email, dan password yang valid.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step2">
                                    <i class="ri-number-2 me-2"></i> Langkah 2: Isi Formulir Pendaftaran
                                </button>
                            </h2>
                            <div id="step2" class="accordion-collapse collapse" data-bs-parent="#panduanAccordion">
                                <div class="accordion-body">
                                    Lengkapi data siswa, data orang tua, dan upload dokumen yang diperlukan (KK, Akta, Foto).
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step3">
                                    <i class="ri-number-3 me-2"></i> Langkah 3: Upload Bukti Pembayaran
                                </button>
                            </h2>
                            <div id="step3" class="accordion-collapse collapse" data-bs-parent="#panduanAccordion">
                                <div class="accordion-body">
                                    Lakukan pembayaran biaya pendaftaran dan upload bukti transfer.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#step4">
                                    <i class="ri-number-4 me-2"></i> Langkah 4: Menunggu Verifikasi
                                </button>
                            </h2>
                            <div id="step4" class="accordion-collapse collapse" data-bs-parent="#panduanAccordion">
                                <div class="accordion-body">
                                    Admin akan memverifikasi pembayaran dan berkas. Pantau status di dashboard Anda.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>