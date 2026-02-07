<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="<?= base_url('user/dashboard') ?>"><i class="ri-home-line"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('user/pembayaran') ?>"><i class="ri-money-dollar-circle-line"></i> Pembayaran</a>
        </li>
        <li class="breadcrumb-item active"><i class="ri-upload-cloud-line"></i> Upload Bukti</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1">
            <i class="ri-upload-cloud-line me-2"></i> Upload Bukti Pembayaran
        </h4>
        <p class="text-muted mb-0">Silakan upload bukti pembayaran Anda untuk menyelesaikan pendaftaran</p>
    </div>
</div>

<!-- Error Messages -->
<?php if (session()->has('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="ri-error-warning-line me-2"></i>
        <strong>Terjadi Kesalahan:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <!-- Upload Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-information-line me-2"></i> Informasi
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm bg-label-primary me-3">
                                <span class="avatar-initial rounded"><i class="ri-file-text-line"></i></span>
                            </div>
                            <div>
                                <small class="text-muted d-block">Nomor Pendaftaran</small>
                                <strong><?= esc($pendaftaran->nomor_pendaftaran) ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm bg-label-primary me-3">
                                <span class="avatar-initial rounded"><i class="ri-user-line"></i></span>
                            </div>
                            <div>
                                <small class="text-muted d-block">Nama Siswa</small>
                                <strong><?= esc($pendaftaran->nama_lengkap) ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm bg-label-primary me-3">
                                <span class="avatar-initial rounded"><i class="ri-money-dollar-circle-line"></i></span>
                            </div>
                            <div>
                                <small class="text-muted d-block">Biaya Pendaftaran</small>
                                <strong><?= formatRupiah($tahun_ajaran->biaya_pendaftaran) ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm bg-label-primary me-3">
                                <span class="avatar-initial rounded"><i class="ri-calendar-line"></i></span>
                            </div>
                            <div>
                                <small class="text-muted d-block">Tahun Ajaran</small>
                                <strong><?= esc($tahun_ajaran->nama_tahun) ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ri-upload-cloud-line me-2"></i> Upload Bukti Pembayaran
                </h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('user/pembayaran/upload/' . $pendaftaran->id) ?>" method="POST" enctype="multipart/form-data" id="uploadForm">
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Bukti Pembayaran</label>
                        <div class="form-control-plaintext">
                            <div class="upload-area border-2 border-dashed rounded-3 text-center py-5 cursor-pointer" id="uploadArea" style="transition: all 0.3s ease;">
                                <input type="file" id="bukti_bayar" name="bukti_bayar" class="d-none" accept=".jpg,.jpeg,.png,.pdf" required>
                                <div id="uploadContent">
                                    <i class="ri-upload-cloud-line" style="font-size: 3rem; color: #667eea; display: block; margin-bottom: 10px;"></i>
                                    <p class="mb-2"><strong>Klik atau drag & drop file</strong></p>
                                    <p class="text-muted mb-0"><small>JPG, PNG, atau PDF (Max 5MB)</small></p>
                                </div>
                                <div id="uploadedContent" class="d-none">
                                    <i class="ri-check-line" style="font-size: 3rem; color: #28a745; display: block; margin-bottom: 10px;"></i>
                                    <p class="mb-2"><strong id="fileName"></strong></p>
                                    <p class="text-muted mb-0"><small id="fileSize"></small></p>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="ri-information-line"></i>
                            Pastikan dokumen jelas dan mudah dibaca. Format harus JPG, PNG, atau PDF.
                        </small>
                    </div>

                    <!-- File Requirements -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading mb-2">
                            <i class="ri-lightbulb-line me-2"></i> Tips Upload:
                        </h6>
                        <ul class="mb-0 ps-3">
                            <li>Gunakan kamera atau scanner untuk hasil yang jelas</li>
                            <li>Pastikan semua detail terlihat dan terbaca</li>
                            <li>Hindari gambar yang blur atau terpotong</li>
                            <li>Format file harus JPG, PNG, atau PDF</li>
                            <li>Ukuran file tidak boleh lebih dari 5 MB</li>
                        </ul>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                            <i class="ri-upload-cloud-line me-2"></i> Upload Bukti
                        </button>
                        <a href="<?= base_url('user/pembayaran') ?>" class="btn btn-secondary">
                            <i class="ri-close-line me-2"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Box -->
        <div class="alert alert-warning mt-4" role="alert">
            <h6 class="alert-heading">
                <i class="ri-shield-warning-line me-2"></i> Keamanan Data
            </h6>
            <p class="mb-0">
                Data pribadi Anda dilindungi dengan enkripsi dan hanya dapat diakses oleh administrator yang berwenang.
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('bukti_bayar');
        const uploadContent = document.getElementById('uploadContent');
        const uploadedContent = document.getElementById('uploadedContent');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const submitBtn = document.getElementById('submitBtn');

        // Drag and drop
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#667eea';
            uploadArea.style.backgroundColor = '#f5f5ff';
        });

        uploadArea.addEventListener('dragleave', function() {
            uploadArea.style.borderColor = '#dee2e6';
            uploadArea.style.backgroundColor = 'transparent';
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#dee2e6';
            uploadArea.style.backgroundColor = 'transparent';

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelect();
            }
        });

        // Click to upload
        uploadArea.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', handleFileSelect);

        function handleFileSelect() {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const maxSize = 5 * 1024 * 1024; // 5MB

                if (file.size > maxSize) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    fileInput.value = '';
                    resetUploadUI();
                    return;
                }

                const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                if (!validTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan JPG, PNG, atau PDF.');
                    fileInput.value = '';
                    resetUploadUI();
                    return;
                }

                // Show uploaded content
                uploadContent.classList.add('d-none');
                uploadedContent.classList.remove('d-none');
                fileName.textContent = file.name;
                fileSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
                submitBtn.disabled = false;
            }
        }

        function resetUploadUI() {
            uploadContent.classList.remove('d-none');
            uploadedContent.classList.add('d-none');
            submitBtn.disabled = true;
        }
    });
</script>

<?= $this->endSection() ?>