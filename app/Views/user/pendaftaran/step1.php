<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Progress Bar -->
            <div class="mb-4">
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        Step 1: Data Siswa
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Step 1: Data Siswa</h5>
                </div>
                <div class="card-body">
                    <form id="formDataSiswa">
                        <?= csrf_field() ?>
                        <input type="hidden" name="pendaftaran_id" value="<?= $pendaftaran->id ?>">

                        <!-- Nama Lengkap -->
                        <div class="form-group mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                   value="<?= $pendaftaran->nama_lengkap ?? '' ?>" required>
                            <div class="invalid-feedback" style="display: none;"></div>
                        </div>

                        <!-- NIK -->
                        <div class="form-group mb-3">
                            <label for="nik" class="form-label">NIK (16 digit) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nik" name="nik" 
                                   value="<?= $pendaftaran->nik ?? '' ?>" 
                                   pattern="\d{16}" maxlength="16" required>
                            <small class="form-text text-muted">Masukkan 16 digit NIK tanpa spasi</small>
                            <div class="invalid-feedback" style="display: none;"></div>
                        </div>

                        <!-- Tempat Lahir -->
                        <div class="form-group mb-3">
                            <label for="tempat_lahir" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" 
                                   value="<?= $pendaftaran->tempat_lahir ?? '' ?>" required>
                            <div class="invalid-feedback" style="display: none;"></div>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="form-group mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" 
                                   value="<?= $pendaftaran->tanggal_lahir ? date('Y-m-d', strtotime($pendaftaran->tanggal_lahir)) : '' ?>" required>
                            <small class="form-text text-muted">Usia harus 3-7 tahun</small>
                            <div class="invalid-feedback" style="display: none;"></div>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="form-group mb-3">
                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="jk_laki" name="jenis_kelamin" value="L" 
                                       <?= ($pendaftaran->jenis_kelamin == 'L') ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="jk_laki">Laki-laki</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="jk_perempuan" name="jenis_kelamin" value="P" 
                                       <?= ($pendaftaran->jenis_kelamin == 'P') ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="jk_perempuan">Perempuan</label>
                            </div>
                        </div>

                        <!-- Agama -->
                        <div class="form-group mb-3">
                            <label for="agama" class="form-label">Agama <span class="text-danger">*</span></label>
                            <select class="form-select" id="agama" name="agama" required>
                                <option value="">-- Pilih Agama --</option>
                                <option value="Islam" <?= ($pendaftaran->agama == 'Islam') ? 'selected' : '' ?>>Islam</option>
                                <option value="Kristen" <?= ($pendaftaran->agama == 'Kristen') ? 'selected' : '' ?>>Kristen</option>
                                <option value="Katolik" <?= ($pendaftaran->agama == 'Katolik') ? 'selected' : '' ?>>Katolik</option>
                                <option value="Hindu" <?= ($pendaftaran->agama == 'Hindu') ? 'selected' : '' ?>>Hindu</option>
                                <option value="Buddha" <?= ($pendaftaran->agama == 'Buddha') ? 'selected' : '' ?>>Buddha</option>
                                <option value="Konghucu" <?= ($pendaftaran->agama == 'Konghucu') ? 'selected' : '' ?>>Konghucu</option>
                            </select>
                            <div class="invalid-feedback" style="display: none;"></div>
                        </div>

                        <!-- Alamat -->
                        <div class="form-group mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= $pendaftaran->alamat ?? '' ?></textarea>
                            <div class="invalid-feedback" style="display: none;"></div>
                        </div>

                        <!-- RT/RW -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="rt" class="form-label">RT</label>
                                    <input type="text" class="form-control" id="rt" name="rt" value="<?= $pendaftaran->rt ?? '' ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="rw" class="form-label">RW</label>
                                    <input type="text" class="form-control" id="rw" name="rw" value="<?= $pendaftaran->rw ?? '' ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Kelurahan/Desa -->
                        <div class="form-group mb-3">
                            <label for="kelurahan" class="form-label">Kelurahan/Desa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kelurahan" name="kelurahan" 
                                   value="<?= $pendaftaran->kelurahan ?? '' ?>" required>
                            <div class="invalid-feedback" style="display: none;"></div>
                        </div>

                        <!-- Kecamatan -->
                        <div class="form-group mb-3">
                            <label for="kecamatan" class="form-label">Kecamatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kecamatan" name="kecamatan" 
                                   value="<?= $pendaftaran->kecamatan ?? '' ?>" required>
                            <div class="invalid-feedback" style="display: none;"></div>
                        </div>

                        <!-- Kota/Kabupaten -->
                        <div class="form-group mb-3">
                            <label for="kota_kabupaten" class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kota_kabupaten" name="kota_kabupaten" 
                                   value="<?= $pendaftaran->kota_kabupaten ?? '' ?>" required>
                            <div class="invalid-feedback" style="display: none;"></div>
                        </div>

                        <!-- Provinsi -->
                        <div class="form-group mb-3">
                            <label for="provinsi" class="form-label">Provinsi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="provinsi" name="provinsi" 
                                   value="<?= $pendaftaran->provinsi ?? '' ?>" required>
                            <div class="invalid-feedback" style="display: none;"></div>
                        </div>

                        <!-- Kode Pos -->
                        <div class="form-group mb-3">
                            <label for="kode_pos" class="form-label">Kode Pos</label>
                            <input type="text" class="form-control" id="kode_pos" name="kode_pos" 
                                   value="<?= $pendaftaran->kode_pos ?? '' ?>">
                        </div>

                        <!-- Buttons -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <a href="<?= base_url('user/dashboard') ?>" class="btn btn-secondary w-100">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100" id="btnSubmit">
                                    <i class="fas fa-arrow-right"></i> Lanjut ke Step 2
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
                <p class="mt-3">Menyimpan data...</p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('formDataSiswa').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

    try {
        loadingModal.show();

        const response = await fetch('<?= base_url('user/pendaftaran/store-data-siswa') ?>', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        loadingModal.hide();

        if (!response.ok) {
            // Show errors
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        const feedback = input.nextElementSibling;
                        if (feedback && feedback.classList.contains('invalid-feedback')) {
                            feedback.textContent = data.errors[field];
                            feedback.style.display = 'block';
                        }
                    }
                });
            } else {
                alert(data.message || 'Gagal menyimpan data');
            }
            return;
        }

        // Success - redirect to next step
        window.location.href = '<?= base_url() ?>' + data.next_step;

    } catch (error) {
        loadingModal.hide();
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
});

// Clear error messages on input
document.querySelectorAll('input, select, textarea').forEach(element => {
    element.addEventListener('change', function() {
        const feedback = this.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.style.display = 'none';
        }
    });
});
</script>

<?php $this->endSection(); ?>
