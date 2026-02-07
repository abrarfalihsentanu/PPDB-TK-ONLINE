<!doctype html>
<html lang="id" class="layout-menu-fixed layout-compact" data-assets-path="<?= base_url('assets/') ?>">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <title><?= $title ?? 'PPDB TK Online' ?> - PPDB TK</title>

    <meta name="description" content="Sistem Penerimaan Peserta Didik Baru TK Online" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/favicon/favicon.ico') ?>" />

    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/node-waves/node-waves.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/vendor/css/core.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/demo.css') ?>" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/vendor/libs/apex-charts/apex-charts.css') ?>" />

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <!-- Helpers -->
    <script src="<?= base_url('assets/vendor/js/helpers.js') ?>"></script>
    <script src="<?= base_url('assets/js/config.js') ?>"></script>

    <!-- Custom CSS -->
    <style>
        .icon-base {
            font-size: 1.25rem;
        }

        .icon-lg {
            font-size: 1.5rem;
        }

        .icon-md {
            font-size: 1.125rem;
        }

        /* Flash Message Animation */
        .flash-message {
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-overlay.show {
            display: flex;
        }
    </style>

    <?= $this->renderSection('css') ?>
</head>

<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Sidebar -->
            <?= $this->include('layouts/sidebar') ?>

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <?= $this->include('layouts/navbar') ?>

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <!-- Flash Messages -->
                        <?php if (session()->getFlashdata('message')): ?>
                            <?php $message = session()->getFlashdata('message'); ?>
                            <div class="alert alert-<?= $message['type'] ?> alert-dismissible flash-message" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="ri-<?= $message['type'] == 'success' ? 'checkbox-circle' : ($message['type'] == 'danger' ? 'error-warning' : 'information') ?>-line me-2 fs-4"></i>
                                    <div>
                                        <?= $message['text'] ?>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Main Content -->
                        <?= $this->renderSection('content') ?>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <?= $this->include('layouts/footer') ?>

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="<?= base_url('assets/vendor/libs/jquery/jquery.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/libs/popper/popper.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/js/bootstrap.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/libs/node-waves/node-waves.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/js/menu.js') ?>"></script>

    <!-- Vendors JS -->
    <script src="<?= base_url('assets/vendor/libs/apex-charts/apexcharts.js') ?>"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Main JS -->
    <script src="<?= base_url('assets/js/main.js') ?>"></script>

    <!-- Global Helper Functions -->
    <script>
        // Define base URL
        const baseUrl = '<?= base_url() ?>';

        // Show loading overlay
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('show');
        }

        // Hide loading overlay
        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('show');
        }

        // Format Rupiah
        function formatRupiah(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
        }

        // Parse Rupiah to number
        function parseRupiah(rupiah) {
            return parseInt(rupiah.replace(/[^0-9]/g, '')) || 0;
        }

        // Confirm Dialog
        function confirmDialog(message, callback) {
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#696cff',
                cancelButtonColor: '#8592a3'
            }).then((result) => {
                if (result.isConfirmed && callback) {
                    callback();
                }
            });
        }

        // Success Alert
        function successAlert(message) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: message,
                confirmButtonColor: '#696cff'
            });
        }

        // Error Alert
        function errorAlert(message) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
                confirmButtonColor: '#696cff'
            });
        }

        // Auto dismiss flash messages after 5 seconds
        setTimeout(function() {
            $('.flash-message').fadeOut('slow');
        }, 5000);

        // Initialize Select2
        $(document).ready(function() {
            if ($.fn.select2) {
                $('.select2').select2({
                    theme: 'bootstrap-5'
                });
            }
        });
    </script>

    <?= $this->renderSection('js') ?>
</body>

</html>