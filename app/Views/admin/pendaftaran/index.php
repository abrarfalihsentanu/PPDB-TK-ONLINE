<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Daftar Pendaftaran</h4>
                <p class="text-muted mb-0">Kelola semua pendaftaran siswa</p>
            </div>
            <div>
                <a href="<?= base_url('admin/pendaftaran') ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-redo"></i> Refresh
                </a>
                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="get" class="row g-3">
                    <div class="col-md-5">
                        <label for="tahun_ajaran_id" class="form-label">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-select">
                            <option value="">-- Semua --</option>
                            <?php foreach ($tahunAjaran as $ta): ?>
                            <option value="<?= $ta->id ?>" <?= ($currentFilter['tahun_ajaran_id'] == $ta->id) ? 'selected' : '' ?>>
                                <?= $ta->nama_tahun ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">-- Semua --</option>
                            <?php foreach ($statuses as $key => $val): ?>
                            <option value="<?= $key ?>" <?= ($currentFilter['status'] == $key) ? 'selected' : '' ?>>
                                <?= $val ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Results Counter -->
<div class="row mb-3">
    <div class="col-12">
        <p class="text-muted">Total: <strong><?= $total ?></strong> pendaftaran</p>
    </div>
</div>

<!-- Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr class="table-light">
                            <th>#</th>
                            <th>Nomor Pendaftaran</th>
                            <th>Nama Siswa</th>
                            <th>Tahun Ajaran</th>
                            <th>Status</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pendaftaran)): ?>
                            <?php foreach ($pendaftaran as $index => $p): ?>
                            <tr>
                                <td><?= ($pager->getCurrentPage() - 1) * 20 + $index + 1 ?></td>
                                <td>
                                    <strong><?= $p->nomor_pendaftaran ?></strong>
                                </td>
                                <td><?= $p->nama_lengkap ?></td>
                                <td><?= $p->nama_tahun ?></td>
                                <td>
                                    <?php
                                    $statusBadge = [
                                        'draft' => 'secondary',
                                        'pending' => 'warning',
                                        'pembayaran_verified' => 'info',
                                        'diverifikasi' => 'primary',
                                        'diterima' => 'success',
                                        'ditolak' => 'danger'
                                    ];
                                    $badge = $statusBadge[$p->status_pendaftaran] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $badge ?>">
                                        <?= ucfirst(str_replace('_', ' ', $p->status_pendaftaran)) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($p->created_at)) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/pendaftaran/view/' . $p->id) ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted">Tidak ada data pendaftaran</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($pager): ?>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <p class="text-muted mb-0">
                Menampilkan <?= count($pendaftaran) ?> dari <?= $total ?> data
            </p>
            <nav>
                <?= $pager->links() ?>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="get" action="<?= base_url('admin/pendaftaran/export') ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="export_tahun" class="form-label">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" id="export_tahun" class="form-select">
                            <option value="">-- Semua --</option>
                            <?php foreach ($tahunAjaran as $ta): ?>
                            <option value="<?= $ta->id ?>"><?= $ta->nama_tahun ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="export_format" class="form-label">Format</label>
                        <select name="format" id="export_format" class="form-select">
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-download"></i> Download
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
