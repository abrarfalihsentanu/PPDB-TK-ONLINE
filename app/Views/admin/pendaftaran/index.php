<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h4>Data Pendaftaran</h4>
<p class="text-muted">Daftar semua pendaftaran siswa.</p>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="tablePendaftaran">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Tahun Ajaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendaftaran as $i => $p): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= esc($p->nomor_pendaftaran) ?></td>
                            <td><?= esc($p->nama_lengkap) ?></td>
                            <td><?= esc($p->email) ?></td>
                            <td><?= esc($p->nama_tahun) ?></td>
                            <td><?= esc($p->status_pendaftaran) ?></td>
                            <td>
                                <a href="<?= base_url('admin/pendaftaran/view/' . $p->id) ?>" class="btn btn-sm btn-primary">Lihat</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>