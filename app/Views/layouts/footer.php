<?php
$currentYear = date('Y');
$appVersion = '1.0.0';
?>

<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl">
        <div class="footer-container d-flex align-items-center justify-content-between py-3 flex-md-row flex-column">
            <!-- Copyright -->
            <div class="text-body mb-2 mb-md-0">
                <span class="text-muted">© <?= $currentYear ?></span>
                <a href="<?= base_url('/') ?>" class="footer-link fw-medium ms-1">PPDB TK Online</a>
            </div>

            <!-- Links & Version -->
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-label-primary rounded-pill">v<?= $appVersion ?></span>
                <a href="javascript:void(0);" class="footer-link" data-bs-toggle="modal" data-bs-target="#aboutModal">
                    <i class="ri-information-line me-1"></i> Tentang
                </a>
            </div>
        </div>
    </div>
</footer>

<!-- Modal About -->
<div class="modal fade" id="aboutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tentang Aplikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="avatar avatar-xl mb-3">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                            <i class="ri-building-4-line ri-2x"></i>
                        </span>
                    </div>
                    <h4 class="mb-1">Sistem PPDB TK Online</h4>
                    <p class="text-muted">Penerimaan Peserta Didik Baru</p>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Versi:</span>
                        <span class="fw-medium"><?= $appVersion ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Framework:</span>
                        <span class="fw-medium">CodeIgniter 4</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Template:</span>
                        <span class="fw-medium">Materio Bootstrap</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Tahun:</span>
                        <span class="fw-medium"><?= $currentYear ?></span>
                    </div>
                </div>

                <div class="alert alert-primary mb-0">
                    <div class="d-flex gap-2">
                        <i class="ri-information-line"></i>
                        <div>
                            <h6 class="alert-heading mb-1">Fitur Utama</h6>
                            <p class="mb-0 small">
                                Pendaftaran online, upload dokumen, pembayaran digital,
                                verifikasi otomatis, dan pelaporan lengkap untuk PPDB TK.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    .footer {
        box-shadow: 0 -2px 6px rgba(67, 89, 113, 0.1);
        margin-top: auto;
    }

    .footer-link {
        color: var(--bs-body-color);
        text-decoration: none;
        transition: all 0.2s ease-in-out;
    }

    .footer-link:hover {
        color: var(--bs-primary);
    }
</style>