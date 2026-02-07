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
                <h5>Dokumen</h5>
                <?php if (!empty($dokumen)): ?>
                    <ul>
                        <?php foreach ($dokumen as $d): ?>
                            <li><?= esc($d->jenis_dokumen) ?> - <a href="<?= base_url($d->path_file) ?>" target="_blank">Lihat</a> (<?= esc($d->status_verifikasi) ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted">Belum ada dokumen.</p>
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