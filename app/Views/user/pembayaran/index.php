<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="<?= base_url('user/dashboard') ?>"><i class="ri-home-line"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item active"><i class="ri-money-dollar-circle-line"></i> Pembayaran</li>
    </ol>
</nav>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1">
            <i class="ri-money-dollar-circle-line me-2"></i> Pembayaran Pendaftaran
        </h4>
        <p class="text-muted mb-0">Kelola pembayaran pendaftaran Anda</p>
    </div>
</div>

<!-- Alert Messages -->
<?php if (session()->getFlashdata('message')): ?>
    <?php $message = session()->getFlashdata('message'); ?>
    <div class="alert alert-<?= $message['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
        <i class="ri-<?= $message['type'] === 'success' ? 'check-line' : 'error-warning-line' ?> me-2"></i>
        <?= $message['text'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (!$tahun_ajaran): ?>
    <!-- No Active Year -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="avatar avatar-xl mb-3">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-calendar-close-line ri-3x"></i>
                        </span>
                    </div>
                    <h5 class="mb-2">Belum Ada Tahun Ajaran Aktif</h5>
                    <p class="text-muted">Silakan tunggu pengumuman dari pihak sekolah untuk pendaftaran tahun ajaran baru.</p>
                </div>
            </div>
        </div>
    </div>

<?php elseif (!$pendaftaran): ?>
    <!-- No Registration -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="avatar avatar-xl mb-3">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri-file-text-line ri-3x"></i>
                        </span>
                    </div>
                    <h5 class="mb-2">Anda Belum Mendaftar</h5>
                    <p class="text-muted mb-4">Untuk melakukan pembayaran, silakan lakukan pendaftaran terlebih dahulu.</p>
                    <a href="<?= base_url('user/pendaftaran/create') ?>" class="btn btn-primary">
                        <i class="ri-edit-line me-2"></i> Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Biaya Pendaftaran Card -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-label-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Biaya Pendaftaran</h6>
                            <h4 class="mb-0"><?= formatRupiah($tahun_ajaran->biaya_pendaftaran) ?></h4>
                        </div>
                        <div>
                            <i class="ri-money-dollar-circle-line ri-3x" style="opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-label-<?= $pembayaran && $pembayaran[0]->status_bayar === 'verified' ? 'success' : 'warning' ?>">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1">Status Pembayaran</h6>
                            <h4 class="mb-0">
                                <?php if (!$pembayaran): ?>
                                    Belum Dibayar
                                <?php else: ?>
                                    <?php
                                    $latestPayment = $pembayaran[0];
                                    echo match ($latestPayment->status_bayar) {
                                        'pending' => 'Menunggu Verifikasi',
                                        'verified' => 'Terverifikasi',
                                        'rejected' => 'Ditolak',
                                        default => 'Unknown'
                                    };
                                    ?>
                                <?php endif; ?>
                            </h4>
                        </div>
                        <div>
                            <i class="ri-<?= $pembayaran && $pembayaran[0]->status_bayar === 'verified' ? 'check-double-line' : 'time-line' ?> ri-3x" style="opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Bukti Pembayaran -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title mb-0">
                        <i class="ri-upload-cloud-line me-2"></i> Upload Bukti Pembayaran
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($pembayaran && $pembayaran[0]->status_bayar === 'verified'): ?>
                        <div class="alert alert-success mb-0">
                            <i class="ri-check-line me-2"></i>
                            <strong>Pembayaran telah terverifikasi!</strong> Terima kasih telah melakukan pembayaran.
                        </div>
                    <?php else: ?>
                        <form action="<?= base_url('user/pembayaran/upload/' . $pendaftaran->id) ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label class="form-label" for="bukti_bayar">Bukti Pembayaran (JPG, PNG, PDF - Max 5MB)</label>
                                <input type="file" id="bukti_bayar" name="bukti_bayar" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                                <small class="text-muted">Upload bukti transfer atau kwitansi pembayaran Anda</small>
                            </div>

                            <div class="mb-0">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-upload-cloud-line me-2"></i> Upload Bukti
                                </button>
                                <a href="<?= base_url('user/pembayaran') ?>" class="btn btn-secondary">
                                    <i class="ri-close-line me-2"></i> Batal
                                </a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pembayaran -->
    <?php if ($pembayaran): ?>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title mb-0">
                            <i class="ri-history-line me-2"></i> Riwayat Pembayaran
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Bukti Pembayaran</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pembayaran as $p): ?>
                                    <tr>
                                        <td>
                                            <small><?= date('d/m/Y H:i', strtotime($p->created_at)) ?></small>
                                        </td>
                                        <td>
                                            <strong><?= formatRupiah($p->jumlah) ?></strong>
                                        </td>
                                        <td>
                                            <?php if ($p->bukti_bayar): ?>
                                                <a href="<?= base_url('files/preview/pembayaran/' . $p->id) ?>" class="btn btn-sm btn-info" target="_blank">
                                                    <i class="ri-eye-line"></i> Lihat
                                                </a>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Belum Upload</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $statusBadge = match ($p->status_bayar) {
                                                'pending' => 'warning',
                                                'verified' => 'success',
                                                'rejected' => 'danger',
                                                default => 'secondary'
                                            };
                                            ?>
                                            <span class="badge bg-label-<?= $statusBadge ?>">
                                                <i class="ri-<?= $p->status_bayar === 'verified' ? 'check-double-line' : ($p->status_bayar === 'rejected' ? 'close-line' : 'time-line') ?> me-1"></i>
                                                <?= ucfirst($p->status_bayar) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($p->keterangan): ?>
                                                <small class="text-danger"><?= esc($p->keterangan) ?></small>
                                            <?php else: ?>
                                                <small class="text-muted">-</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($p->status_bayar === 'rejected'): ?>
                                                <a href="<?= base_url('user/pembayaran/upload/' . $pendaftaran->id) ?>" class="btn btn-sm btn-primary">
                                                    <i class="ri-refresh-line"></i> Upload Ulang
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Upload Pembayaran Button -->
    <?php if (!$pembayaran || $pembayaran[0]->status_bayar !== 'verified'): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="text-center">
                    <a href="<?= base_url('user/pembayaran/upload/' . $pendaftaran->id) ?>" class="btn btn-lg btn-primary">
                        <i class="ri-upload-cloud-line me-2"></i> Upload Bukti Pembayaran
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

<?php endif; ?>

<?= $this->endSection() ?>