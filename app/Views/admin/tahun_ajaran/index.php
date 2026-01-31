<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">Data Tahun Ajaran</h4>
                <p class="text-muted mb-0">Kelola tahun ajaran dan kuota pendaftaran</p>
            </div>
            <a href="<?= base_url('admin/tahun-ajaran/create') ?>" class="btn btn-primary">
                <i class="ri-add-circle-line me-1"></i> Tambah Tahun Ajaran
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="tableTahunAjaran">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun Ajaran</th>
                        <th>Kuota</th>
                        <th>Biaya Pendaftaran</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tahun_ajaran as $index => $ta): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><strong><?= esc($ta->nama_tahun) ?></strong></td>
                            <td><?= esc($ta->kuota) ?> siswa</td>
                            <td><?= formatRupiah($ta->biaya_pendaftaran) ?></td>
                            <td>
                                <small class="text-muted">
                                    <?= date('d M Y', strtotime($ta->tanggal_buka)) ?> -
                                    <?= date('d M Y', strtotime($ta->tanggal_tutup)) ?>
                                </small>
                            </td>
                            <td>
                                <?php if ($ta->status == 'aktif'): ?>
                                    <span class="badge bg-success">
                                        <i class="ri-check-line me-1"></i> Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="ri-more-2-line"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <?php if ($ta->status == 'nonaktif'): ?>
                                            <li>
                                                <a class="dropdown-item" href="<?= base_url('admin/tahun-ajaran/activate/' . $ta->id) ?>" onclick="return confirm('Aktifkan tahun ajaran ini? Tahun ajaran aktif lainnya akan dinonaktifkan.')">
                                                    <i class="ri-check-double-line me-2"></i> Aktifkan
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <li>
                                            <a class="dropdown-item" href="<?= base_url('admin/tahun-ajaran/edit/' . $ta->id) ?>">
                                                <i class="ri-edit-line me-2"></i> Edit
                                            </a>
                                        </li>
                                        <?php if ($ta->status != 'aktif'): ?>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="<?= base_url('admin/tahun-ajaran/delete/' . $ta->id) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus tahun ajaran ini?')">
                                                    <i class="ri-delete-bin-line me-2"></i> Hapus
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    $(document).ready(function() {
        $('#tableTahunAjaran').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            }
        });
    });
</script>
<?= $this->endSection() ?>