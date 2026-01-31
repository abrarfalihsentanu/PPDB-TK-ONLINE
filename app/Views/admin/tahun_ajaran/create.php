<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/tahun-ajaran') ?>">Tahun Ajaran</a></li>
                <li class="breadcrumb-item active">Tambah Baru</li>
            </ol>
        </nav>
        <h4 class="mb-0">Tambah Tahun Ajaran</h4>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="<?= base_url('admin/tahun-ajaran/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="nama_tahun" class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_tahun" name="nama_tahun" placeholder="2025/2026" value="<?= old('nama_tahun') ?>" required>
                        <small class="text-muted">Format: YYYY/YYYY (contoh: 2025/2026)</small>
                        <?php if (isset($validation) && $validation->hasError('nama_tahun')): ?>
                            <div class="invalid-feedback d-block"><?= $validation->getError('nama_tahun') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kuota" class="form-label">Kuota Siswa <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="kuota" name="kuota" value="<?= old('kuota') ?>" min="1" required>
                                <?php if (isset($validation) && $validation->hasError('kuota')): ?>
                                    <div class="invalid-feedback d-block"><?= $validation->getError('kuota') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="biaya_pendaftaran" class="form-label">Biaya Pendaftaran <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" id="biaya_pendaftaran" name="biaya_pendaftaran" value="<?= old('biaya_pendaftaran') ?>" required>
                                </div>
                                <?php if (isset($validation) && $validation->hasError('biaya_pendaftaran')): ?>
                                    <div class="invalid-feedback d-block"><?= $validation->getError('biaya_pendaftaran') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_buka" class="form-label">Tanggal Buka <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal_buka" name="tanggal_buka" value="<?= old('tanggal_buka') ?>" required>
                                <?php if (isset($validation) && $validation->hasError('tanggal_buka')): ?>
                                    <div class="invalid-feedback d-block"><?= $validation->getError('tanggal_buka') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_tutup" class="form-label">Tanggal Tutup <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal_tutup" name="tanggal_tutup" value="<?= old('tanggal_tutup') ?>" required>
                                <?php if (isset($validation) && $validation->hasError('tanggal_tutup')): ?>
                                    <div class="invalid-feedback d-block"><?= $validation->getError('tanggal_tutup') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('admin/tahun-ajaran') ?>" class="btn btn-label-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card bg-primary-subtle">
            <div class="card-body">
                <h6 class="text-primary"><i class="ri-information-line me-1"></i> Informasi</h6>
                <ul class="small mb-0 ps-3">
                    <li class="mb-2">Tahun ajaran menggunakan format YYYY/YYYY</li>
                    <li class="mb-2">Hanya 1 tahun ajaran yang bisa aktif dalam satu waktu</li>
                    <li class="mb-2">Kuota akan berkurang otomatis setiap ada siswa diterima</li>
                    <li class="mb-2">Biaya pendaftaran akan digunakan untuk validasi pembayaran</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    // Format Rupiah input
    const biayaInput = document.getElementById('biaya_pendaftaran');
    biayaInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9]/g, '');
        e.target.value = new Intl.NumberFormat('id-ID').format(value);
    });

    // On submit, convert back to number
    document.querySelector('form').addEventListener('submit', function(e) {
        biayaInput.value = biayaInput.value.replace(/[^0-9]/g, '');
    });
</script>
<?= $this->endSection() ?>