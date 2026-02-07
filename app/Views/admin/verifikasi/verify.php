<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('admin/verifikasi') ?>">Verifikasi Berkas</a></li>
            <li class="breadcrumb-item active">Detail Verifikasi</li>
        </ol>
    </nav>

    <!-- Header & Tombol Back -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="ri-file-check-line me-2"></i>Verifikasi Berkas Pendaftaran</h4>
        <a href="<?= base_url('admin/verifikasi') ?>" class="btn btn-outline-secondary">
            <i class="ri-arrow-left-line me-2"></i>Kembali
        </a>
    </div>

    <!-- Data Pendaftaran Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Informasi Pendaftar</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Nomor Pendaftaran</label>
                    <p class="form-control-plaintext"><?= $pendaftaran->nomor_pendaftaran ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <p class="form-control-plaintext"><?= $pendaftaran->nama_lengkap ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Email</label>
                    <p class="form-control-plaintext"><?= $pendaftaran->email ?></p>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Tahun Ajaran</label>
                    <p class="form-control-plaintext"><?= $pendaftaran->nama_tahun ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Verifikasi Dokumen -->
    <form method="POST" action="<?= base_url('admin/verifikasi/process-dokumen/' . $pendaftaran->id) ?>">
        <?= csrf_field() ?>

        <?php foreach ($dokumen as $doc): ?>
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ri-file-line me-2"></i>
                            Dokumen:
                            <?php
                            $jenisDokumen = [
                                'kk' => 'Kartu Keluarga (KK)',
                                'akta' => 'Akta Kelahiran',
                                'foto' => 'Foto Siswa'
                            ];
                            echo $jenisDokumen[$doc->jenis_dokumen] ?? ucfirst($doc->jenis_dokumen);
                            ?>
                        </h5>
                        <span class="badge bg-secondary"><?= ucfirst($doc->status_verifikasi) ?></span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Preview Dokumen -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Preview Dokumen</label>
                            <div class="border rounded p-3 bg-light" style="max-height: 400px; overflow: auto;">
                                <?php
                                $fileExt = strtolower(pathinfo($doc->path_file, PATHINFO_EXTENSION));
                                if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                    <img src="<?= base_url('files/preview/dokumen/' . $doc->id) ?>" alt="Dokumen" class="img-fluid rounded" style="max-width: 100%;">
                                <?php else: ?>
                                    <div class="text-center text-muted">
                                        <i class="ri-file-pdf-line" style="font-size: 3rem;"></i>
                                        <p class="mt-2"><?= basename($doc->path_file) ?></p>
                                        <a href="<?= base_url('files/download/dokumen/' . $doc->id) ?>" class="btn btn-sm btn-primary">
                                            <i class="ri-download-line"></i> Download File
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Form Verifikasi -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Status Verifikasi</label>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dokumen_status[<?= $doc->id ?>]" id="status_approved_<?= $doc->id ?>" value="approved" <?= $doc->status_verifikasi === 'approved' ? 'checked' : '' ?> required>
                                    <label class="form-check-label" for="status_approved_<?= $doc->id ?>">
                                        <i class="ri-check-circle-line text-success"></i> Lolos (Approved)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dokumen_status[<?= $doc->id ?>]" id="status_rejected_<?= $doc->id ?>" value="rejected" <?= $doc->status_verifikasi === 'rejected' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="status_rejected_<?= $doc->id ?>">
                                        <i class="ri-close-circle-line text-danger"></i> Ditolak (Rejected)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dokumen_status[<?= $doc->id ?>]" id="status_pending_<?= $doc->id ?>" value="pending" <?= $doc->status_verifikasi === 'pending' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="status_pending_<?= $doc->id ?>">
                                        <i class="ri-time-line text-warning"></i> Pending (Review)
                                    </label>
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label">Keterangan (Jika ditolak)</label>
                                <textarea name="dokumen_keterangan[<?= $doc->id ?>]" class="form-control" rows="4" placeholder="Tulis alasan dokumen ditolak..."><?= $doc->keterangan ?? '' ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Summary Status Dokumen -->
        <div class="alert alert-info mb-4">
            <h6 class="alert-heading mb-3"><i class="ri-information-line me-2"></i>Summary Status Dokumen</h6>
            <ul class="mb-0">
                <li><strong>Total Dokumen:</strong> <?= $statusDokumen['total'] ?></li>
                <li><strong>Lolos (Approved):</strong> <?= $statusDokumen['approved'] ?></li>
                <li><strong>Ditolak (Rejected):</strong> <?= $statusDokumen['rejected'] ?></li>
                <li><strong>Pending (Review):</strong> <?= $statusDokumen['pending'] ?></li>
            </ul>
        </div>

        <!-- Submit Button -->
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="ri-check-line me-2"></i>Simpan Verifikasi
            </button>
            <a href="<?= base_url('admin/verifikasi') ?>" class="btn btn-outline-secondary">
                <i class="ri-close-line me-2"></i>Batal
            </a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>