<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Verifikasi Berkas</li>
        </ol>
    </nav>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1">
            <li class="breadcrumb-item">
                <a href="<?= base_url('admin/dashboard') ?>"><i class="ri-home-line"></i> Dashboard</a>
            </li>
            <li class="breadcrumb-item active"><i class="ri-file-check-line"></i> Verifikasi Berkas</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="ri-file-check-line me-2"></i>Verifikasi Berkas Pendaftaran</h4>
            <p class="text-muted mb-0">Kelola verifikasi dokumen pendaftaran calon siswa</p>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="ri-filter-line me-2"></i> Filter & Pencarian</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" class="form-select">
                        <option value="">- Semua Tahun Ajaran -</option>
                        <?php foreach ($tahun_ajaran as $ta): ?>
                            <option value="<?= $ta->id ?>" <?= $filters['tahun_ajaran_id'] === (string)$ta->id ? 'selected' : '' ?>>
                                <i class="ri-calendar-line"></i> <?= $ta->nama_tahun ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label">Pencarian</label>
                    <input type="text" name="search" class="form-control" placeholder="Cari nomor pendaftaran / nama..." value="<?= $filters['search'] ?>">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-search-line me-2"></i>Cari
                    </button>
                </div>

                <div class="col-12">
                    <a href="<?= base_url('admin/verifikasi') ?>" class="btn btn-outline-secondary">
                        <i class="ri-refresh-line me-2"></i> Reset Filter
                    </a>
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
                        <th width="5%">No</th>
                        <th width="15%"><i class="ri-file-text-line me-1"></i> Nomor Pendaftaran</th>
                        <th width="20%"><i class="ri-user-line me-1"></i> Nama Lengkap</th>
                        <th width="20%"><i class="ri-mail-line me-1"></i> Email</th>
                        <th width="15%"><i class="ri-calendar-line me-1"></i> Tahun Ajaran</th>
                        <th width="15%">Status</th>
                        <th width="15%">Tanggal Daftar</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pendaftaran)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="ri-inbox-line me-2" style="font-size: 2rem;"></i>
                                    <p>Tidak ada data pendaftaran yang menunggu verifikasi berkas</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pendaftaran as $index => $item): ?>
                            <tr>
                                <td><?= ($page - 1) * 20 + $index + 1 ?></td>
                                <td><strong><?= $item->nomor_pendaftaran ?></strong></td>
                                <td><?= $item->nama_lengkap ?></td>
                                <td><?= $item->email ?></td>
                                <td><?= $item->nama_tahun ?></td>
                                <td>
                                    <span class="badge bg-warning">
                                        Menunggu Verifikasi
                                    </span>
                                </td>
                                <td><?= date('d-m-Y', strtotime($item->created_at)) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/verifikasi/dokumen/' . $item->id) ?>" class="btn btn-sm btn-primary">
                                        <i class="ri-checkbox-circle-line me-1"></i> Verifikasi
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