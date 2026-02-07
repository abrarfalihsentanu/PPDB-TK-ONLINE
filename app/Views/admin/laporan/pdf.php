<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan PPDB</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0;
            font-size: 12px;
        }

        .filter-info {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 11px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .table th {
            background-color: #366092;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        .table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }

        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 4px;
        }

        .summary p {
            margin: 5px 0;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h2>LAPORAN PENDAFTARAN PPDB</h2>
        <p>Sekolah/Taman Kanak-Kanak</p>
        <p>Tanggal Cetak: <?= $tanggal_cetak ?? date('d-m-Y H:i:s') ?></p>
    </div>

    <!-- Filter Info -->
    <div class="filter-info">
        <strong>Filter Data:</strong>
        <?php if (!empty($filters['tahun_ajaran_id']) && !empty($tahun_ajaran)): ?>
            Tahun Ajaran: <?= $tahun_ajaran->nama_tahun ?? '-' ?> |
        <?php endif; ?>
        <?php if (!empty($filters['status_pendaftaran'])): ?>
            Status: <?= ucfirst(str_replace('_', ' ', $filters['status_pendaftaran'])) ?> |
        <?php endif; ?>
        <?php if (!empty($filters['jenis_kelamin'])): ?>
            Jenis Kelamin: <?= $filters['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?> |
        <?php endif; ?>
        <?php if (!empty($filters['agama'])): ?>
            Agama: <?= $filters['agama'] ?> |
        <?php endif; ?>
        <?php if (!empty($filters['date_from']) || !empty($filters['date_to'])): ?>
            Periode: <?= !empty($filters['date_from']) ? date('d-m-Y', strtotime($filters['date_from'])) : '-' ?> s/d <?= !empty($filters['date_to']) ? date('d-m-Y', strtotime($filters['date_to'])) : '-' ?>
        <?php endif; ?>
    </div>

    <!-- Data Table -->
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Pendaftaran</th>
                <th>Nama Lengkap</th>
                <th>NIK</th>
                <th>Tgl Lahir</th>
                <th>L/P</th>
                <th>Agama</th>
                <th>Email</th>
                <th>Status</th>
                <th>Tgl Daftar</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pendaftaran)): ?>
                <tr>
                    <td colspan="10" style="text-align: center; padding: 20px;">Tidak ada data</td>
                </tr>
            <?php else: ?>
                <?php foreach ($pendaftaran as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $item->nomor_pendaftaran ?></td>
                        <td><?= $item->nama_lengkap ?></td>
                        <td><?= $item->nik ?></td>
                        <td><?= date('d-m-Y', strtotime($item->tanggal_lahir)) ?></td>
                        <td><?= $item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                        <td><?= $item->agama ?></td>
                        <td><?= $item->email ?? '-' ?></td>
                        <td><?= ucfirst(str_replace('_', ' ', $item->status_pendaftaran)) ?></td>
                        <td><?= date('d-m-Y', strtotime($item->created_at)) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Summary -->
    <div class="summary">
        <p><strong>Total Pendaftar: <?= count($pendaftaran) ?> orang</strong></p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem PPDB</p>
    </div>
</body>

</html>