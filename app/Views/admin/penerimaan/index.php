<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Pengumuman Penerimaan</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0"><i class="ri-checkbox-circle-line me-2"></i>Pengumuman Penerimaan Siswa</h4>
            <?php if (!empty($tahun_ajaran_aktif)): ?>
                <small class="text-muted">Tahun Ajaran: <strong><?= $tahun_ajaran_aktif->nama_tahun ?></strong> | Sisa Kuota: <strong class="text-primary"><?= $sisaKuota ?></strong></small>
            <?php endif; ?>
        </div>
    </div>

    <!-- Stats Cards -->
    <?php if (!empty($stats)): ?>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted d-block mb-2">Menunggu Keputusan</small>
                                <h3 class="mb-0"><?= $stats['diverifikasi'] ?></h3>
                            </div>
                            <i class="ri-time-line text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted d-block mb-2">Diterima</small>
                                <h3 class="mb-0 text-success"><?= $stats['diterima'] ?></h3>
                            </div>
                            <i class="ri-check-circle-line text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted d-block mb-2">Ditolak</small>
                                <h3 class="mb-0 text-danger"><?= $stats['ditolak'] ?></h3>
                            </div>
                            <i class="ri-close-circle-line text-danger" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted d-block mb-2">Sisa Kuota</small>
                                <h3 class="mb-0 text-primary"><?= $sisaKuota ?></h3>
                            </div>
                            <i class="ri-bar-chart-box-line text-info" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status_pendaftaran" class="form-select">
                        <option value="all" <?= $filters['status_pendaftaran'] === 'all' ? 'selected' : '' ?>>Semua Status</option>
                        <option value="diverifikasi" <?= $filters['status_pendaftaran'] === 'diverifikasi' ? 'selected' : '' ?>>Menunggu Keputusan</option>
                        <option value="diterima" <?= $filters['status_pendaftaran'] === 'diterima' ? 'selected' : '' ?>>Diterima</option>
                        <option value="ditolak" <?= $filters['status_pendaftaran'] === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Nomor pendaftaran / Nama" value="<?= $filters['search'] ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-search-line me-2"></i>Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table dengan Checkbox -->
    <div class="card">
        <form method="POST" id="formBatchUpdate" action="<?= base_url('admin/penerimaan/process') ?>">
            <?= csrf_field() ?>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </th>
                            <th>No</th>
                            <th>Nomor Pendaftaran</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Status Saat Ini</th>
                            <th>Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pendaftaran)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="ri-inbox-line me-2" style="font-size: 2rem;"></i>
                                        <p>Tidak ada data pendaftaran</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pendaftaran as $index => $item): ?>
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" name="pendaftaran_ids[]" value="<?= $item->id ?>">
                                    </td>
                                    <td><?= ($page - 1) * 30 + $index + 1 ?></td>
                                    <td><strong><?= $item->nomor_pendaftaran ?></strong></td>
                                    <td><?= $item->nama_lengkap ?></td>
                                    <td><?= $item->email ?></td>
                                    <td>
                                        <?php if ($item->status_pendaftaran === 'diverifikasi'): ?>
                                            <span class="badge bg-warning">Menunggu</span>
                                        <?php elseif ($item->status_pendaftaran === 'diterima'): ?>
                                            <span class="badge bg-success">Diterima</span>
                                        <?php elseif ($item->status_pendaftaran === 'ditolak'): ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d-m-Y', strtotime($item->created_at)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Batch Action -->
            <?php if (!empty($pendaftaran)): ?>
                <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <strong id="selectedCount">0</strong> pendaftar dipilih
                    </small>
                    <div>
                        <select name="status" class="form-select d-inline-block w-auto me-2" id="batchStatus">
                            <option value="">Pilih Status</option>
                            <option value="diterima">✓ Diterima</option>
                            <option value="ditolak">✗ Ditolak</option>
                            <option value="cadangan">⏳ Cadangan</option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="ri-check-line me-2"></i>Terapkan Status
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Pagination -->
    <?php if (!empty($pageLinks)): ?>
        <div class="mt-4">
            <?= $pageLinks ?>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('input[name="pendaftaran_ids[]"]');
        const selectedCount = document.getElementById('selectedCount');

        function updateCount() {
            const checked = document.querySelectorAll('input[name="pendaftaran_ids[]"]:checked').length;
            selectedCount.textContent = checked;
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateCount();
            });
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateCount);
        });

        document.getElementById('formBatchUpdate').addEventListener('submit', function(e) {
            const checked = document.querySelectorAll('input[name="pendaftaran_ids[]"]:checked').length;
            const status = document.getElementById('batchStatus').value;

            if (checked === 0 || !status) {
                e.preventDefault();
                alert('Silakan pilih minimal 1 pendaftar dan status');
                return false;
            }
        });
    });
</script>
<?= $this->endSection() ?>