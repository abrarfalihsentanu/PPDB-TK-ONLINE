<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Dashboard</h4>
                <p class="text-muted mb-0">Selamat datang di sistem PPDB TK Online</p>
            </div>
            <?php if ($tahun_ajaran_aktif): ?>
                <div class="badge bg-label-primary fs-6 px-3 py-2">
                    <i class="ri-calendar-line me-1"></i>
                    Tahun Ajaran <?= esc($tahun_ajaran_aktif->nama_tahun) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (!$tahun_ajaran_aktif): ?>
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
    <!-- Belum Daftar -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="avatar avatar-xl mb-3">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-user-add-line ri-3x"></i>
                        </span>
                    </div>
                    <h5 class="mb-2">Anda Belum Mendaftar</h5>
                    <p class="text-muted mb-4">Daftarkan putra/putri Anda untuk tahun ajaran <?= esc($tahun_ajaran_aktif->nama_tahun) ?></p>
                    <a href="<?= base_url('user/pendaftaran/create') ?>" class="btn btn-primary">
                        <i class="ri-add-circle-line me-2"></i> Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Status Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h5 class="mb-1">Status Pendaftaran</h5>
                            <p class="text-muted mb-0">Nomor: <strong><?= esc($pendaftaran->nomor_pendaftaran) ?></strong></p>
                        </div>
                        <div>
                            <?php
                            $statusBadge = [
                                'draft' => ['bg' => 'secondary', 'text' => 'Draft'],
                                'pending' => ['bg' => 'warning', 'text' => 'Menunggu Verifikasi'],
                                'pembayaran_verified' => ['bg' => 'info', 'text' => 'Pembayaran Terverifikasi'],
                                'dokumen_ditolak' => ['bg' => 'danger', 'text' => 'Dokumen Ditolak'],
                                'diverifikasi' => ['bg' => 'primary', 'text' => 'Diverifikasi'],
                                'diterima' => ['bg' => 'success', 'text' => 'DITERIMA'],
                                'ditolak' => ['bg' => 'danger', 'text' => 'Ditolak']
                            ];
                            $badge = $statusBadge[$pendaftaran->status_pendaftaran] ?? ['bg' => 'secondary', 'text' => 'Unknown'];
                            ?>
                            <span class="badge bg-<?= $badge['bg'] ?> fs-5 px-3 py-2">
                                <?= $badge['text'] ?>
                            </span>
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="ri-user-line me-2 text-primary"></i>
                                <div>
                                    <small class="text-muted d-block">Nama Siswa</small>
                                    <strong><?= esc($pendaftaran->nama_lengkap) ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="ri-calendar-line me-2 text-primary"></i>
                                <div>
                                    <small class="text-muted d-block">Tanggal Daftar</small>
                                    <strong><?= date('d/m/Y', strtotime($pendaftaran->created_at)) ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="ri-money-dollar-circle-line me-2 text-primary"></i>
                                <div>
                                    <small class="text-muted d-block">Biaya Pendaftaran</small>
                                    <strong><?= formatRupiah($tahun_ajaran_aktif->biaya_pendaftaran) ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline Progress -->
    <?php if ($timeline): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Timeline Proses Pendaftaran</h5>
                    </div>
                    <div class="card-body">
                        <ul class="timeline mb-0">
                            <?php foreach ($timeline as $item): ?>
                                <li class="timeline-item timeline-item-transparent">
                                    <span class="timeline-point timeline-point-<?= $item['color'] ?>"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header mb-1">
                                            <h6 class="mb-0">
                                                <i class="<?= $item['icon'] ?> me-1"></i>
                                                <?= $item['title'] ?>
                                            </h6>
                                            <?php if ($item['date']): ?>
                                                <small class="text-muted"><?= $item['date'] ?></small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <?php if ($item['status'] == 'completed'): ?>
                                                <span class="badge bg-<?= $item['color'] ?>">
                                                    <i class="ri-check-line"></i> Selesai
                                                </span>
                                            <?php elseif ($item['status'] == 'process'): ?>
                                                <span class="badge bg-info">
                                                    <i class="ri-loader-4-line"></i> Proses
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-label-secondary">
                                                    <i class="ri-time-line"></i> Menunggu
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Aksi Cepat</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <!-- Edit Button (jika status masih draft/pending) -->
                        <?php if (in_array($pendaftaran->status_pendaftaran, ['draft', 'pending'])): ?>
                            <a href="<?= base_url('user/pendaftaran/edit/' . $pendaftaran->id) ?>" class="btn btn-label-primary">
                                <i class="ri-edit-line me-1"></i> Edit Pendaftaran
                            </a>
                        <?php endif; ?>

                        <!-- Upload Pembayaran (jika status pending dan belum ada pembayaran verified) -->
                        <?php if ($pendaftaran->status_pendaftaran == 'pending' && (!$pembayaran || $pembayaran->status_bayar != 'verified')): ?>
                            <a href="<?= base_url('user/pembayaran') ?>" class="btn btn-label-success">
                                <i class="ri-upload-2-line me-1"></i> Upload Bukti Bayar
                            </a>
                        <?php endif; ?>

                        <!-- Preview Button -->
                        <a href="<?= base_url('user/pendaftaran/preview/' . $pendaftaran->id) ?>" class="btn btn-label-info">
                            <i class="ri-eye-line me-1"></i> Preview Data
                        </a>

                        <!-- Cetak Bukti (jika sudah submit) -->
                        <?php if (!in_array($pendaftaran->status_pendaftaran, ['draft'])): ?>
                            <a href="<?= base_url('user/cetak/bukti-pendaftaran/' . $pendaftaran->id) ?>" class="btn btn-label-secondary" target="_blank">
                                <i class="ri-printer-line me-1"></i> Cetak Bukti
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Keterangan Khusus -->
                    <?php if ($pendaftaran->status_pendaftaran == 'dokumen_ditolak' && $pendaftaran->keterangan): ?>
                        <div class="alert alert-danger mt-3 mb-0">
                            <h6 class="alert-heading mb-1">
                                <i class="ri-error-warning-line me-1"></i> Catatan dari Admin
                            </h6>
                            <p class="mb-0"><?= esc($pendaftaran->keterangan) ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($pendaftaran->status_pendaftaran == 'diterima'): ?>
                        <div class="alert alert-success mt-3 mb-0">
                            <h6 class="alert-heading mb-1">
                                <i class="ri-checkbox-circle-line me-1"></i> Selamat!
                            </h6>
                            <p class="mb-0">Pendaftaran Anda telah DITERIMA. Silakan tunggu informasi lebih lanjut dari pihak sekolah.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>