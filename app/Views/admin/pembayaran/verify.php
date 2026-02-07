<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('admin/pembayaran') ?>">Verifikasi Pembayaran</a></li>
            <li class="breadcrumb-item active">Verifikasi Detail</li>
        </ol>
    </nav>

    <!-- Header & Tombol Back -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="ri-bank-card-line me-2"></i>Verifikasi Pembayaran</h4>
        <a href="<?= base_url('admin/pembayaran') ?>" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-2"></i>Kembali
        </a>
    </div>

    <div class="row">
        <!-- Left: Data Pendaftaran -->
        <div class="col-lg-8">
            <!-- Data Pendaftaran Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pendaftar</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nomor Pendaftaran</label>
                            <p class="form-control-plaintext"><?= $pendaftaran->nomor_pendaftaran ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <p class="form-control-plaintext"><?= $pendaftaran->nama_lengkap ?></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">NIK</label>
                            <p class="form-control-plaintext"><?= $pendaftaran->nik ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Lahir</label>
                            <p class="form-control-plaintext"><?= date('d-m-Y', strtotime($pendaftaran->tanggal_lahir)) ?></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <p class="form-control-plaintext"><?= $pendaftaran->email ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">status Pendaftaran</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-primary">
                                    <?= ucfirst(str_replace('_', ' ', $pendaftaran->status_pendaftaran)) ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Pembayaran Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jumlah Pembayaran</label>
                            <p class="form-control-plaintext">
                                <strong>Rp <?= number_format($pembayaran->jumlah, 0, ',', '.') ?></strong>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Transfer</label>
                            <p class="form-control-plaintext">
                                <?= !empty($pembayaran->tanggal_bayar) ? date('d-m-Y', strtotime($pembayaran->tanggal_bayar)) : '-' ?>
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Preview Bukti Bayar</label>
                        <div class="border rounded p-3 bg-light" style="max-height: 400px; overflow: auto;">
                            <?php
                            $fileExt = strtolower(pathinfo($pembayaran->bukti_bayar, PATHINFO_EXTENSION));
                            if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                <img src="<?= base_url('files/preview/pembayaran/' . $pembayaran->id) ?>" alt="Bukti Bayar" class="img-fluid rounded" style="max-width: 100%;">
                            <?php else: ?>
                                <div class="text-center text-muted">
                                    <i class="ri-file-pdf-line" style="font-size: 3rem;"></i>
                                    <p class="mt-2"><?= basename($pembayaran->bukti_bayar) ?></p>
                                    <a href="<?= base_url('files/download/pembayaran/' . $pembayaran->id) ?>" class="btn btn-sm btn-primary">
                                        <i class="ri-download-line"></i> Download File
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Form Verifikasi -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white">Verifikasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= base_url('admin/pembayaran/process-verify/' . $pembayaran->id) ?>">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Verifikasi</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_bayar" id="statusApprove" value="verified" required>
                                <label class="form-check-label" for="statusApprove">
                                    <i class="ri-check-line text-success"></i> Setujui (Approved)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_bayar" id="statusReject" value="rejected">
                                <label class="form-check-label" for="statusReject">
                                    <i class="ri-close-line text-danger"></i> Tolak (Rejected)
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan (Opsional)</label>
                            <textarea name="keterangan" class="form-control" rows="4" placeholder="Tulis keterangan jika pembayaran ditolak..."></textarea>
                            <small class="text-muted">Keterangan akan dikirim ke pendaftar via email</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-check-line me-2"></i>Simpan Verifikasi
                            </button>
                        </div>
                    </form>

                    <!-- Info Box -->
                    <div class="alert alert-info mt-3 mb-0">
                        <small>
                            <strong>Catatan:</strong> Pastikan data pembayaran sesuai sebelum melakukan verifikasi.
                            Tidak boleh ada duplikasi atau pembayaran tidak lengkap.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>