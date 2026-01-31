<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Detail Pendaftaran</h4>
                <a href="<?= base_url('user/dashboard') ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <!-- Data Siswa -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-user-graduate"></i> Data Siswa</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">Nomor Pendaftaran:</label>
                            <p><?= $pendaftaran->nomor_pendaftaran ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">Tahun Ajaran:</label>
                            <p><?= $pendaftaran->nama_tahun ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">Nama Lengkap:</label>
                            <p><?= $pendaftaran->nama_lengkap ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">NIK:</label>
                            <p><?= $pendaftaran->nik ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">Tempat Lahir:</label>
                            <p><?= $pendaftaran->tempat_lahir ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">Tanggal Lahir:</label>
                            <p><?= date('d F Y', strtotime($pendaftaran->tanggal_lahir)) ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">Jenis Kelamin:</label>
                            <p><?= ($pendaftaran->jenis_kelamin == 'L') ? 'Laki-laki' : 'Perempuan' ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">Agama:</label>
                            <p><?= $pendaftaran->agama ?></p>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="fw-bold">Alamat:</label>
                            <p><?= $pendaftaran->alamat ?></p>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold">Lokasi Lengkap:</label>
                            <p>
                                <?php
                                $alamatLengkap = [];
                                if ($pendaftaran->rt) $alamatLengkap[] = 'RT ' . $pendaftaran->rt;
                                if ($pendaftaran->rw) $alamatLengkap[] = 'RW ' . $pendaftaran->rw;
                                if ($pendaftaran->kelurahan) $alamatLengkap[] = $pendaftaran->kelurahan;
                                if ($pendaftaran->kecamatan) $alamatLengkap[] = $pendaftaran->kecamatan;
                                if ($pendaftaran->kota_kabupaten) $alamatLengkap[] = $pendaftaran->kota_kabupaten;
                                if ($pendaftaran->provinsi) $alamatLengkap[] = $pendaftaran->provinsi;
                                if ($pendaftaran->kode_pos) $alamatLengkap[] = $pendaftaran->kode_pos;
                                echo implode(', ', $alamatLengkap);
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Orang Tua -->
            <?php if ($orang_tua): ?>
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-users"></i> Data Orang Tua</h6>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">Ayah:</h6>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">Nama:</label>
                            <p><?= $orang_tua->nama_ayah ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">Pekerjaan:</label>
                            <p><?= $orang_tua->pekerjaan_ayah ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">Penghasilan:</label>
                            <p><?= $orang_tua->penghasilan_ayah ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">No. Telepon:</label>
                            <p><?= $orang_tua->telepon_ayah ?></p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3">Ibu:</h6>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">Nama:</label>
                            <p><?= $orang_tua->nama_ibu ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">Pekerjaan:</label>
                            <p><?= $orang_tua->pekerjaan_ibu ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">Penghasilan:</label>
                            <p><?= $orang_tua->penghasilan_ibu ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">No. Telepon:</label>
                            <p><?= $orang_tua->telepon_ibu ?></p>
                        </div>
                    </div>

                    <?php if ($orang_tua->nama_wali): ?>
                    <hr>

                    <h6 class="mb-3">Wali:</h6>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">Nama:</label>
                            <p><?= $orang_tua->nama_wali ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">Hubungan:</label>
                            <p><?= ucfirst($orang_tua->hubungan_wali) ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">Pekerjaan:</label>
                            <p><?= $orang_tua->pekerjaan_wali ?></p>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="fw-bold">No. Telepon:</label>
                            <p><?= $orang_tua->telepon_wali ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Dokumen -->
            <?php if (!empty($dokumen)): ?>
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-file"></i> Dokumen</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($dokumen as $doc): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <?php
                                        $jenisDok = ['kk' => 'Kartu Keluarga', 'akta' => 'Akta Kelahiran', 'foto' => 'Foto Siswa'];
                                        echo $jenisDok[$doc->jenis_dokumen] ?? $doc->jenis_dokumen;
                                        ?>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <?php if (strpos($doc->tipe_file, 'image') !== false): ?>
                                    <img src="<?= base_url($doc->path_file) ?>" alt="Dokumen" class="img-fluid" style="max-height: 300px;">
                                    <?php else: ?>
                                    <div class="text-center">
                                        <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                    </div>
                                    <?php endif; ?>
                                    <small class="text-muted mt-2 d-block">
                                        <p class="mb-1">File: <?= $doc->nama_file_asli ?></p>
                                        <p class="mb-1">Ukuran: <?= round($doc->ukuran_file / 1024, 2) ?> KB</p>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
