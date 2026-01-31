<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Dashboard Admin</h4>
                <p class="text-muted mb-0">Selamat datang di sistem PPDB TK Online</p>
            </div>
            <?php if ($tahun_ajaran_aktif): ?>
                <div class="badge bg-label-primary fs-6 px-3 py-2">
                    <i class="ri-calendar-line me-1"></i>
                    Tahun Ajaran <?= esc($tahun_ajaran_aktif->nama_tahun) ?>
                </div>
            <?php else: ?>
                <div class="badge bg-label-warning fs-6 px-3 py-2">
                    <i class="ri-alert-line me-1"></i>
                    Belum Ada Tahun Ajaran Aktif
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <!-- Total Pendaftar -->
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-muted d-block mb-1">Total Pendaftar</span>
                        <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2"><?= number_format($total_pendaftar_aktif) ?></h4>
                        </div>
                        <small class="text-muted">Tahun Ajaran Aktif</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-user-add-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kuota -->
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-muted d-block mb-1">Sisa Kuota</span>
                        <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2"><?= $sisa_kuota ?></h4>
                            <?php if ($tahun_ajaran_aktif): ?>
                                <span class="text-muted">/ <?= $tahun_ajaran_aktif->kuota ?></span>
                            <?php endif; ?>
                        </div>
                        <small class="text-success">
                            <?php if ($tahun_ajaran_aktif && $tahun_ajaran_aktif->kuota > 0): ?>
                                <?= number_format(($sisa_kuota / $tahun_ajaran_aktif->kuota) * 100, 1) ?>% tersisa
                            <?php else: ?>
                                0% tersisa
                            <?php endif; ?>
                        </small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri-group-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Diterima -->
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-muted d-block mb-1">Diterima</span>
                        <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2"><?= $stats['diterima'] ?></h4>
                        </div>
                        <small class="text-success">Siswa diterima</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-checkbox-circle-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending -->
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span class="text-muted d-block mb-1">Menunggu Verifikasi</span>
                        <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2"><?= $stats['pending'] + $stats['pembayaran_verified'] ?></h4>
                        </div>
                        <small class="text-warning">Perlu diproses</small>
                    </div>
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-time-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts & Status -->
<div class="row g-4 mb-4">
    <!-- Chart Pendaftaran -->
    <div class="col-12 col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Grafik Pendaftaran</h5>
                <small class="text-muted">Tahun Ajaran <?= $tahun_ajaran_aktif ? esc($tahun_ajaran_aktif->nama_tahun) : '-' ?></small>
            </div>
            <div class="card-body">
                <canvas id="chartPendaftaran" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Status Breakdown -->
    <div class="col-12 col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Status Pendaftaran</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="ri-draft-line"></i>
                                    </span>
                                </div>
                                <span>Draft</span>
                            </div>
                            <span class="badge bg-label-secondary"><?= $stats['draft'] ?></span>
                        </div>
                    </li>
                    <li class="mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class="ri-time-line"></i>
                                    </span>
                                </div>
                                <span>Pending</span>
                            </div>
                            <span class="badge bg-label-warning"><?= $stats['pending'] ?></span>
                        </div>
                    </li>
                    <li class="mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-label-info">
                                        <i class="ri-bank-card-line"></i>
                                    </span>
                                </div>
                                <span>Bayar Verified</span>
                            </div>
                            <span class="badge bg-label-info"><?= $stats['pembayaran_verified'] ?></span>
                        </div>
                    </li>
                    <li class="mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="ri-file-check-line"></i>
                                    </span>
                                </div>
                                <span>Diverifikasi</span>
                            </div>
                            <span class="badge bg-label-primary"><?= $stats['diverifikasi'] ?></span>
                        </div>
                    </li>
                    <li class="mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-label-success">
                                        <i class="ri-checkbox-circle-line"></i>
                                    </span>
                                </div>
                                <span>Diterima</span>
                            </div>
                            <span class="badge bg-label-success"><?= $stats['diterima'] ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-label-danger">
                                        <i class="ri-close-circle-line"></i>
                                    </span>
                                </div>
                                <span>Ditolak</span>
                            </div>
                            <span class="badge bg-label-danger"><?= $stats['ditolak'] ?></span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Latest Registrations -->
<?php if (!empty($latest_pendaftaran)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Pendaftaran Terbaru</h5>
                    <a href="<?= base_url('admin/pendaftaran') ?>" class="btn btn-sm btn-label-primary">
                        Lihat Semua <i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No. Pendaftaran</th>
                                <th>Nama Siswa</th>
                                <th>Email Orang Tua</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($latest_pendaftaran as $p): ?>
                                <tr>
                                    <td><strong><?= esc($p->nomor_pendaftaran) ?></strong></td>
                                    <td><?= esc($p->nama_lengkap) ?></td>
                                    <td><?= esc($p->email) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($p->created_at)) ?></td>
                                    <td>
                                        <?php
                                        $statusBadge = [
                                            'draft' => 'secondary',
                                            'pending' => 'warning',
                                            'pembayaran_verified' => 'info',
                                            'diverifikasi' => 'primary',
                                            'diterima' => 'success',
                                            'ditolak' => 'danger'
                                        ];
                                        $badge = $statusBadge[$p->status_pendaftaran] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-label-<?= $badge ?>">
                                            <?= ucfirst($p->status_pendaftaran) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('admin/pendaftaran/view/' . $p->id) ?>" class="btn btn-sm btn-icon btn-label-primary">
                                            <i class="ri-eye-line"></i>
                                        </a>
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

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Chart Pendaftaran
    const ctx = document.getElementById('chartPendaftaran');
    const chartData = <?= json_encode($pendaftaran_per_bulan) ?>;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Jumlah Pendaftar',
                data: chartData.data,
                borderColor: '#696cff',
                backgroundColor: 'rgba(105, 108, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>