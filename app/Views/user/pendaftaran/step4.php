<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Progress Bar -->
            <div class="mb-4">
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                        Step 4: Review & Submit
                    </div>
                </div>
            </div>

            <!-- Review Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Step 4: Review & Submit Pendaftaran</h5>
                </div>
                <div class="card-body">
                    <!-- Data Siswa -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-user-graduate"></i> Data Siswa</h6>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="fw-bold">Nomor Pendaftaran:</label>
                                <p><?= $pendaftaran->nomor_pendaftaran ?></p>
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
                                <label class="fw-bold">Tempat/Tanggal Lahir:</label>
                                <p><?= $pendaftaran->tempat_lahir ?>, <?= date('d-m-Y', strtotime($pendaftaran->tanggal_lahir)) ?></p>
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
                            <div class="col-md-12 mb-2">
                                <label class="fw-bold">Alamat Lengkap:</label>
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

                    <!-- Data Orang Tua -->
                    <?php if ($orang_tua): ?>
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-users"></i> Data Orang Tua/Wali</h6>
                        
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-muted">Ayah:</h6>
                            </div>
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

                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-muted">Ibu:</h6>
                            </div>
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

                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-muted">Wali:</h6>
                            </div>
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
                    <?php endif; ?>

                    <!-- Dokumen -->
                    <?php if (!empty($dokumen)): ?>
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-file"></i> Dokumen yang Diupload</h6>
                        <div class="row">
                            <?php foreach ($dokumen as $doc): ?>
                            <div class="col-md-4 mb-3">
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
                                        <img src="<?= base_url($doc->path_file) ?>" alt="Dokumen" class="img-fluid mb-2" style="max-height: 200px;">
                                        <?php else: ?>
                                        <div class="text-center mb-2">
                                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                        </div>
                                        <?php endif; ?>
                                        <small class="text-muted">
                                            <p class="mb-1">File: <?= $doc->nama_file_asli ?></p>
                                            <p class="mb-1">Ukuran: <?= round($doc->ukuran_file / 1024, 2) ?> KB</p>
                                            <p class="mb-0">Status: <span class="badge bg-warning">Pending Verifikasi</span></p>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Informasi Biaya -->
                    <div class="alert alert-info" role="alert">
                        <h6 class="alert-heading"><i class="fas fa-money-bill-wave"></i> Informasi Biaya Pendaftaran</h6>
                        <p class="mb-0">Biaya Pendaftaran: <strong>Rp <?= number_format($pendaftaran->biaya_pendaftaran ?? 0, 0, ',', '.') ?></strong></p>
                    </div>

                    <!-- Pernyataan -->
                    <form id="formSubmit">
                        <?= csrf_field() ?>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="pernyataan" name="pernyataan" required>
                            <label class="form-check-label" for="pernyataan">
                                Saya menyatakan bahwa semua data yang saya isi adalah benar dan dapat dipertanggungjawabkan secara hukum.
                            </label>
                        </div>

                        <!-- Buttons -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <a href="<?= base_url('user/pendaftaran/form/' . $pendaftaran->id . '/step/3') ?>" class="btn btn-secondary w-100">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success w-100" id="btnSubmit">
                                    <i class="fas fa-check"></i> Submit Pendaftaran
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Memproses pendaftaran...</p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('formSubmit').addEventListener('submit', async function(e) {
    e.preventDefault();

    if (!document.getElementById('pernyataan').checked) {
        alert('Anda harus menyetujui pernyataan');
        return;
    }

    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

    try {
        loadingModal.show();

        const response = await fetch('<?= base_url('user/pendaftaran/submit/' . $pendaftaran->id) ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        loadingModal.hide();

        if (response.ok) {
            alert('Pendaftaran berhasil disubmit!');
            window.location.href = '<?= base_url('user/dashboard') ?>';
        } else {
            const data = await response.text();
            alert(data || 'Gagal submit pendaftaran');
        }

    } catch (error) {
        loadingModal.hide();
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
});
</script>

<?php $this->endSection(); ?>
