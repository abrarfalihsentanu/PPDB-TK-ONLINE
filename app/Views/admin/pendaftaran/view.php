<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h4>Detail Pendaftaran</h4>
<p class="text-muted">Detail lengkap pendaftaran.</p>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-body">
                <h5>Data Siswa</h5>
                <p><strong>Nomor:</strong> <?= esc($pendaftaran->nomor_pendaftaran) ?></p>
                <p><strong>Nama:</strong> <?= esc($pendaftaran->nama_lengkap) ?></p>
                <p><strong>NIK:</strong> <?= esc($pendaftaran->nik) ?></p>
                <p><strong>Alamat:</strong> <?= esc($pendaftaran->alamat) ?></p>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5>Data Orang Tua / Wali</h5>
                <?php if ($orang_tua): ?>
                    <p><strong>Ayah:</strong> <?= esc($orang_tua->nama_ayah) ?> (<?= esc($orang_tua->telepon_ayah) ?>)</p>
                    <p><strong>Ibu:</strong> <?= esc($orang_tua->nama_ibu) ?> (<?= esc($orang_tua->telepon_ibu) ?>)</p>
                <?php else: ?>
                    <p class="text-muted">Belum mengisi data orang tua.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5><i class="ri-file-line me-2"></i>Dokumen</h5>
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
                <?php else: ?>
                    <p class="text-muted"><i class="ri-information-line me-2"></i>Belum ada dokumen.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5>Informasi</h5>
                <p><strong>Tanggal dibuat:</strong> <?= esc($pendaftaran->created_at) ?></p>
                <p><strong>Status:</strong> <?= esc($pendaftaran->status_pendaftaran) ?></p>
                <a href="<?= base_url('admin/pendaftaran') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>