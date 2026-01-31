<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Detail Pendaftaran</h4>
                <p class="text-muted mb-0">Nomor: <strong><?= $pendaftaran->nomor_pendaftaran ?></strong></p>
            </div>
            <a href="<?= base_url('admin/pendaftaran') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<!-- Status Card -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-1"><?= $pendaftaran->nama_lengkap ?></h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-calendar"></i> 
                            <?= date('d/m/Y', strtotime($pendaftaran->tanggal_lahir)) ?>
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-id-card"></i> 
                            NIK: <?= $pendaftaran->nik ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <?php
                        $statusBadge = [
                            'draft' => 'secondary',
                            'pending' => 'warning',
                            'pembayaran_verified' => 'info',
                            'diverifikasi' => 'primary',
                            'diterima' => 'success',
                            'ditolak' => 'danger'
                        ];
                        $badge = $statusBadge[$pendaftaran->status_pendaftaran] ?? 'secondary';
                        ?>
                        <h3>
                            <span class="badge bg-<?= $badge ?> p-2">
                                <?= ucfirst(str_replace('_', ' ', $pendaftaran->status_pendaftaran)) ?>
                            </span>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Data Siswa -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-user-graduate"></i> Data Siswa</h6>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-5">Nama Lengkap:</dt>
                    <dd class="col-sm-7"><?= $pendaftaran->nama_lengkap ?></dd>

                    <dt class="col-sm-5">NIK:</dt>
                    <dd class="col-sm-7"><?= $pendaftaran->nik ?></dd>

                    <dt class="col-sm-5">Tempat Lahir:</dt>
                    <dd class="col-sm-7"><?= $pendaftaran->tempat_lahir ?></dd>

                    <dt class="col-sm-5">Tanggal Lahir:</dt>
                    <dd class="col-sm-7"><?= date('d/m/Y', strtotime($pendaftaran->tanggal_lahir)) ?></dd>

                    <dt class="col-sm-5">Jenis Kelamin:</dt>
                    <dd class="col-sm-7"><?= ($pendaftaran->jenis_kelamin == 'L') ? 'Laki-laki' : 'Perempuan' ?></dd>

                    <dt class="col-sm-5">Agama:</dt>
                    <dd class="col-sm-7"><?= $pendaftaran->agama ?></dd>
                </dl>
            </div>
        </div>
    </div>

    <!-- Data Lokasi -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-map-marker-alt"></i> Data Alamat</h6>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-5">Alamat:</dt>
                    <dd class="col-sm-7"><?= $pendaftaran->alamat ?></dd>

                    <dt class="col-sm-5">Kelurahan:</dt>
                    <dd class="col-sm-7"><?= $pendaftaran->kelurahan ?></dd>

                    <dt class="col-sm-5">Kecamatan:</dt>
                    <dd class="col-sm-7"><?= $pendaftaran->kecamatan ?></dd>

                    <dt class="col-sm-5">Kota/Kabupaten:</dt>
                    <dd class="col-sm-7"><?= $pendaftaran->kota_kabupaten ?></dd>

                    <dt class="col-sm-5">Provinsi:</dt>
                    <dd class="col-sm-7"><?= $pendaftaran->provinsi ?></dd>

                    <dt class="col-sm-5">Kode Pos:</dt>
                    <dd class="col-sm-7"><?= $pendaftaran->kode_pos ?? '-' ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<!-- Data Orang Tua -->
<?php if ($orang_tua): ?>
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-user-tie"></i> Data Ayah</h6>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-5">Nama:</dt>
                    <dd class="col-sm-7"><?= $orang_tua->nama_ayah ?></dd>

                    <dt class="col-sm-5">Pekerjaan:</dt>
                    <dd class="col-sm-7"><?= $orang_tua->pekerjaan_ayah ?></dd>

                    <dt class="col-sm-5">Penghasilan:</dt>
                    <dd class="col-sm-7"><?= $orang_tua->penghasilan_ayah ?></dd>

                    <dt class="col-sm-5">Telepon:</dt>
                    <dd class="col-sm-7"><?= $orang_tua->telepon_ayah ?></dd>

                    <?php if ($orang_tua->nik_ayah): ?>
                    <dt class="col-sm-5">NIK:</dt>
                    <dd class="col-sm-7"><?= $orang_tua->nik_ayah ?></dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-user"></i> Data Ibu</h6>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-5">Nama:</dt>
                    <dd class="col-sm-7"><?= $orang_tua->nama_ibu ?></dd>

                    <dt class="col-sm-5">Pekerjaan:</dt>
                    <dd class="col-sm-7"><?= $orang_tua->pekerjaan_ibu ?></dd>

                    <dt class="col-sm-5">Penghasilan:</dt>
                    <dd class="col-sm-7"><?= $orang_tua->penghasilan_ibu ?></dd>

                    <dt class="col-sm-5">Telepon:</dt>
                    <dd class="col-sm-7"><?= $orang_tua->telepon_ibu ?></dd>

                    <?php if ($orang_tua->nik_ibu): ?>
                    <dt class="col-sm-5">NIK:</dt>
                    <dd class="col-sm-7"><?= $orang_tua->nik_ibu ?></dd>
                    <?php endif; ?>
                </dl>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Dokumen -->
<?php if (!empty($dokumen)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-file"></i> Dokumen Pendaftaran</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($dokumen as $doc): ?>
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3 text-center">
                            <?php if (strpos($doc->tipe_file, 'image') !== false): ?>
                            <img src="<?= base_url($doc->path_file) ?>" alt="<?= $doc->jenis_dokumen ?>" 
                                 class="img-fluid" style="max-height: 200px; cursor: pointer;" 
                                 data-bs-toggle="modal" data-bs-target="#docModal<?= $doc->id ?>">
                            <?php else: ?>
                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                            <?php endif; ?>
                            <p class="mt-2 mb-1">
                                <strong><?= ucfirst(str_replace('_', ' ', $doc->jenis_dokumen)) ?></strong>
                            </p>
                            <p class="text-muted small mb-2">
                                <?= round($doc->ukuran_file / 1024, 2) ?> KB
                            </p>
                            <a href="<?= base_url($doc->path_file) ?>" download class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    </div>

                    <!-- Image Modal -->
                    <?php if (strpos($doc->tipe_file, 'image') !== false): ?>
                    <div class="modal fade" id="docModal<?= $doc->id ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title"><?= $doc->jenis_dokumen ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="<?= base_url($doc->path_file) ?>" alt="Dokumen" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Action Buttons -->
<?php if ($pendaftaran->status_pendaftaran == 'pending' || $pendaftaran->status_pendaftaran == 'pembayaran_verified'): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Aksi</h6>
            </div>
            <div class="card-body">
                <div class="btn-group" role="group">
                    <a href="<?= base_url('admin/pendaftaran/accept/' . $pendaftaran->id) ?>" 
                       class="btn btn-success" onclick="return confirm('Setujui pendaftaran ini?')">
                        <i class="fas fa-check"></i> Terima
                    </a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pendaftaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="<?= base_url('admin/pendaftaran/reject/' . $pendaftaran->id) ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan Penolakan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pendaftaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $this->endSection(); ?>
