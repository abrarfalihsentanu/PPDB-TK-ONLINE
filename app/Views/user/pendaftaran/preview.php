<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h4>Preview Pendaftaran</h4>
        <p class="text-muted">Periksa data sebelum submit pendaftaran.</p>

        <div class="card mb-3">
            <div class="card-body">
                <h5>Data Siswa</h5>
                <p><strong>Nama:</strong> <?= esc($pendaftaran->nama_lengkap) ?></p>
                <p><strong>NIK:</strong> <?= esc($pendaftaran->nik) ?></p>
                <p><strong>Tanggal Lahir:</strong> <?= esc($pendaftaran->tanggal_lahir) ?></p>
                <p><strong>Alamat:</strong> <?= esc($pendaftaran->alamat) ?></p>
                <a href="<?= base_url('user/pendaftaran/edit/' . $pendaftaran->id) ?>" class="btn btn-outline-primary btn-sm">Edit Data Siswa</a>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5>Data Orang Tua / Wali</h5>
                <?php if ($orang_tua): ?>
                    <p><strong>Ayah:</strong> <?= esc($orang_tua->nama_ayah) ?> (<?= esc($orang_tua->telepon_ayah) ?>)</p>
                    <p><strong>Ibu:</strong> <?= esc($orang_tua->nama_ibu) ?> (<?= esc($orang_tua->telepon_ibu) ?>)</p>
                    <a href="<?= base_url('user/pendaftaran/orangtua/' . $pendaftaran->id) ?>" class="btn btn-outline-primary btn-sm">Edit Data Orang Tua</a>
                <?php else: ?>
                    <p class="text-muted">Belum mengisi data orang tua.</p>
                    <a href="<?= base_url('user/pendaftaran/orangtua/' . $pendaftaran->id) ?>" class="btn btn-primary btn-sm">Isi Data Orang Tua</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5><i class="ri-file-line me-2"></i> Dokumen</h5>
                <?php if (!empty($dokumen)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($dokumen as $d): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="mb-1">
                                            <i class="ri-file-text-line me-2"></i>
                                            <strong><?php
                                                    $jenisDok = [
                                                        'kk' => 'Kartu Keluarga',
                                                        'akta' => 'Akta Kelahiran',
                                                        'foto' => 'Foto Siswa'
                                                    ];
                                                    echo $jenisDok[$d->jenis_dokumen] ?? ucfirst($d->jenis_dokumen);
                                                    ?></strong>
                                        </p>
                                        <small class="text-muted">
                                            <?php
                                            $badge = match ($d->status_verifikasi) {
                                                'pending' => ['bg' => 'warning', 'text' => 'Menunggu Verifikasi'],
                                                'approved' => ['bg' => 'success', 'text' => 'Disetujui'],
                                                'rejected' => ['bg' => 'danger', 'text' => 'Ditolak'],
                                                default => ['bg' => 'secondary', 'text' => ucfirst($d->status_verifikasi)]
                                            };
                                            ?>
                                            <span class="badge bg-<?= $badge['bg'] ?>"><?= $badge['text'] ?></span>
                                        </small>
                                    </div>
                                    <div>
                                        <a href="<?= base_url('files/preview/dokumen/' . $d->id) ?>" class="btn btn-sm btn-info" target="_blank">
                                            <i class="ri-eye-line"></i> Lihat
                                        </a>
                                        <a href="<?= base_url('files/download/dokumen/' . $d->id) ?>" class="btn btn-sm btn-secondary">
                                            <i class="ri-download-line"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= base_url('user/pendaftaran/upload/' . $pendaftaran->id) ?>" class="btn btn-outline-primary btn-sm mt-3">
                        <i class="ri-edit-line me-1"></i> Upload/Ubah Dokumen
                    </a>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <i class="ri-information-line me-2"></i>
                        <strong>Belum meng-upload dokumen.</strong>
                        <p class="mb-3 mt-2">Silakan upload dokumen pendukung untuk melanjutkan pendaftaran.</p>
                        <a href="<?= base_url('user/pendaftaran/upload/' . $pendaftaran->id) ?>" class="btn btn-sm btn-primary">
                            <i class="ri-upload-cloud-line me-1"></i> Upload Dokumen
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="<?= base_url('user/dashboard') ?>" class="btn btn-secondary">Kembali ke Dashboard</a>
            <form method="POST" action="<?= base_url('user/pendaftaran/submit/' . $pendaftaran->id) ?>">
                <button type="submit" class="btn btn-success">Submit Pendaftaran</button>
            </form>
        </div>

    </div>
</div>
<?= $this->endSection() ?>