<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Laporan Pendaftaran</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="ri-file-chart-line me-2"></i>Laporan Pendaftaran PPDB</h4>
    </div>

    <!-- Filter & Export Card -->
    <div class="card mb-4">
        <div class="card-header bg-primary">
            <h5 class="mb-0 text-white"><i class="ri-settings-3-line me-2"></i>Filter & Export Data</h5>
        </div>
        <div class="card-body">
            <form method="GET" id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" class="form-select">
                        <option value="">Pilih Tahun Ajaran</option>
                        <?php foreach ($tahun_ajaran as $ta): ?>
                            <option value="<?= $ta->id ?>">
                                <?= $ta->nama_tahun ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Status Pendaftaran</label>
                    <select name="status_pendaftaran" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="draft">Draft</option>
                        <option value="pending">Pending</option>
                        <option value="pembayaran_verified">Pembayaran Verified</option>
                        <option value="diverifikasi">Diverifikasi</option>
                        <option value="diterima">Diterima</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select">
                        <option value="">Semua Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Agama</label>
                    <select name="agama" class="form-select">
                        <option value="">Semua Agama</option>
                        <option value="Islam">Islam</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Buddha">Buddha</option>
                        <option value="Konghucu">Konghucu</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control" placeholder="Nomor pendaftaran / Nama / Email">
                </div>

                <div class="col-md-6" style="margin-top: 32px;">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="ri-search-line me-2"></i>Preview
                    </button>
                    <button type="button" class="btn btn-success me-2" onclick="exportPDF()">
                        <i class="ri-file-pdf-line me-2"></i>Export PDF
                    </button>
                    <button type="button" class="btn btn-info" onclick="exportExcel()">
                        <i class="ri-file-excel-line me-2"></i>Export Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Data -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Preview Data Laporan</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nomor Pendaftaran</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Kelamin</th>
                        <th>Agama</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <small class="text-muted">Pilih filter dan klik "Preview" untuk melihat data laporan</small>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function exportPDF() {
        const form = document.getElementById('filterForm');
        const params = new URLSearchParams(new FormData(form));
        window.location.href = '<?= base_url('admin/laporan/export-pdf?') ?>' + params.toString();
    }

    function exportExcel() {
        const form = document.getElementById('filterForm');
        const params = new URLSearchParams(new FormData(form));
        window.location.href = '<?= base_url('admin/laporan/export-excel?') ?>' + params.toString();
    }
</script>
<?= $this->endSection() ?>