<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h4>Upload Dokumen</h4>
        <p class="text-muted">Upload KK, Akta Kelahiran, dan Foto Siswa.</p>

        <form method="POST" action="<?= base_url('user/pendaftaran/upload-dokumen/' . $pendaftaran_id) ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Kartu Keluarga (KK)</label>
                <input type="file" name="kk" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Akta Kelahiran</label>
                <input type="file" name="akta" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Foto Siswa</label>
                <input type="file" name="foto" class="form-control">
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= base_url('user/pendaftaran/preview/' . $pendaftaran_id) ?>" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Upload Dokumen</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>