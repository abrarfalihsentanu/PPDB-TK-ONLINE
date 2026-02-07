<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h4>Formulir Data Siswa</h4>
        <p class="text-muted">Isi data siswa untuk memulai pendaftaran.</p>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('errors') ?></div>
        <?php endif; ?>

        <?= $this->include('user/pendaftaran/_form_siswa') ?>
    </div>
</div>
<?= $this->endSection() ?>