<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Verifikasi Pembayaran</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="ri-bank-card-line me-2"></i>Verifikasi Pembayaran</h4>
    </div>

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status_bayar" class="form-select">
                        <option value="">Pilih Status</option>
                        <option value="pending" <?= $filters['status_bayar'] === 'pending' ? 'selected' : '' ?>>Pending (Menunggu)</option>
                        <option value="verified" <?= $filters['status_bayar'] === 'verified' ? 'selected' : '' ?>>Verified (Terverifikasi)</option>
                        <option value="rejected" <?= $filters['status_bayar'] === 'rejected' ? 'selected' : '' ?>>Rejected (Ditolak)</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" class="form-select">
                        <option value="">Semua Tahun Ajaran</option>
                        <?php foreach ($tahun_ajaran as $ta): ?>
                            <option value="<?= $ta->id ?>" <?= $filters['tahun_ajaran_id'] === (string)$ta->id ? 'selected' : '' ?>>
                                <?= $ta->nama_tahun ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Nomor pendaftaran / Nama" value="<?= $filters['search'] ?>">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-search-line me-2"></i>Cari
                    </button>
                    <a href="<?= base_url('admin/pembayaran') ?>" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nomor Pendaftaran</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal Upload</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pembayaran)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="ri-inbox-line me-2" style="font-size: 2rem;"></i>
                                    <p>Tidak ada data pembayaran</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pembayaran as $index => $item): ?>
                            <tr>
                                <td><?= ($page - 1) * 20 + $index + 1 ?></td>
                                <td><strong><?= $item->nomor_pendaftaran ?></strong></td>
                                <td><?= $item->nama_lengkap ?></td>
                                <td><?= $item->email ?></td>
                                <td>
                                    <strong>Rp <?= number_format($item->jumlah, 0, ',', '.') ?></strong>
                                </td>
                                <td>
                                    <?php if ($item->status_bayar === 'pending'): ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php elseif ($item->status_bayar === 'verified'): ?>
                                        <span class="badge bg-success">Verified</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d-m-Y', strtotime($item->created_at)) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/pembayaran/verify/' . $item->id) ?>" class="btn btn-sm btn-info">
                                        <i class="ri-eye-line"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if (!empty($pageLinks)): ?>
        <div class="mt-4">
            <?= $pageLinks ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>