<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Progress Bar -->
            <div class="mb-4">
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                        Step 2: Data Orang Tua/Wali
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Step 2: Data Orang Tua/Wali</h5>
                </div>
                <div class="card-body">
                    <form id="formDataOrangTua">
                        <?= csrf_field() ?>
                        <input type="hidden" name="pendaftaran_id" value="<?= $pendaftaran->id ?>">

                        <!-- DATA AYAH -->
                        <h6 class="mb-3 border-bottom pb-2"><i class="fas fa-user-tie"></i> Data Ayah</h6>

                        <div class="form-group mb-3">
                            <label for="nama_ayah" class="form-label">Nama Ayah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_ayah" name="nama_ayah" 
                                   value="<?= $orang_tua->nama_ayah ?? '' ?>" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nik_ayah" class="form-label">NIK Ayah (16 digit)</label>
                            <input type="text" class="form-control" id="nik_ayah" name="nik_ayah" 
                                   value="<?= $orang_tua->nik_ayah ?? '' ?>" 
                                   pattern="\d{16}" maxlength="16">
                            <small class="form-text text-muted">Opsional, jika ada masukkan 16 digit</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="pekerjaan_ayah" class="form-label">Pekerjaan Ayah <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pekerjaan_ayah" name="pekerjaan_ayah" 
                                   value="<?= $orang_tua->pekerjaan_ayah ?? '' ?>" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="penghasilan_ayah" class="form-label">Penghasilan Ayah <span class="text-danger">*</span></label>
                            <select class="form-select" id="penghasilan_ayah" name="penghasilan_ayah" required>
                                <option value="">-- Pilih Penghasilan --</option>
                                <option value="< 1 juta" <?= ($orang_tua->penghasilan_ayah == '< 1 juta') ? 'selected' : '' ?>>Kurang dari 1 juta</option>
                                <option value="1-2 juta" <?= ($orang_tua->penghasilan_ayah == '1-2 juta') ? 'selected' : '' ?>>1-2 juta</option>
                                <option value="2-5 juta" <?= ($orang_tua->penghasilan_ayah == '2-5 juta') ? 'selected' : '' ?>>2-5 juta</option>
                                <option value="5-10 juta" <?= ($orang_tua->penghasilan_ayah == '5-10 juta') ? 'selected' : '' ?>>5-10 juta</option>
                                <option value="> 10 juta" <?= ($orang_tua->penghasilan_ayah == '> 10 juta') ? 'selected' : '' ?>>Lebih dari 10 juta</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="telepon_ayah" class="form-label">Nomor Telepon Ayah <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="telepon_ayah" name="telepon_ayah" 
                                   value="<?= $orang_tua->telepon_ayah ?? '' ?>" 
                                   pattern="08[0-9]{8,10}" placeholder="08xxxxxxxxx" required>
                            <small class="form-text text-muted">Format: 08xxx (10-12 digit)</small>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- DATA IBU -->
                        <h6 class="mb-3 border-bottom pb-2"><i class="fas fa-user"></i> Data Ibu</h6>

                        <div class="form-group mb-3">
                            <label for="nama_ibu" class="form-label">Nama Ibu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_ibu" name="nama_ibu" 
                                   value="<?= $orang_tua->nama_ibu ?? '' ?>" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nik_ibu" class="form-label">NIK Ibu (16 digit)</label>
                            <input type="text" class="form-control" id="nik_ibu" name="nik_ibu" 
                                   value="<?= $orang_tua->nik_ibu ?? '' ?>" 
                                   pattern="\d{16}" maxlength="16">
                            <small class="form-text text-muted">Opsional, jika ada masukkan 16 digit</small>
                        </div>

                        <div class="form-group mb-3">
                            <label for="pekerjaan_ibu" class="form-label">Pekerjaan Ibu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="pekerjaan_ibu" name="pekerjaan_ibu" 
                                   value="<?= $orang_tua->pekerjaan_ibu ?? '' ?>" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="penghasilan_ibu" class="form-label">Penghasilan Ibu <span class="text-danger">*</span></label>
                            <select class="form-select" id="penghasilan_ibu" name="penghasilan_ibu" required>
                                <option value="">-- Pilih Penghasilan --</option>
                                <option value="< 1 juta" <?= ($orang_tua->penghasilan_ibu == '< 1 juta') ? 'selected' : '' ?>>Kurang dari 1 juta</option>
                                <option value="1-2 juta" <?= ($orang_tua->penghasilan_ibu == '1-2 juta') ? 'selected' : '' ?>>1-2 juta</option>
                                <option value="2-5 juta" <?= ($orang_tua->penghasilan_ibu == '2-5 juta') ? 'selected' : '' ?>>2-5 juta</option>
                                <option value="5-10 juta" <?= ($orang_tua->penghasilan_ibu == '5-10 juta') ? 'selected' : '' ?>>5-10 juta</option>
                                <option value="> 10 juta" <?= ($orang_tua->penghasilan_ibu == '> 10 juta') ? 'selected' : '' ?>>Lebih dari 10 juta</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="telepon_ibu" class="form-label">Nomor Telepon Ibu <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="telepon_ibu" name="telepon_ibu" 
                                   value="<?= $orang_tua->telepon_ibu ?? '' ?>" 
                                   pattern="08[0-9]{8,10}" placeholder="08xxxxxxxxx" required>
                            <small class="form-text text-muted">Format: 08xxx (10-12 digit)</small>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- DATA WALI (OPSIONAL) -->
                        <h6 class="mb-3 border-bottom pb-2"><i class="fas fa-users"></i> Data Wali (Opsional)</h6>

                        <div class="form-group mb-3">
                            <label for="nama_wali" class="form-label">Nama Wali</label>
                            <input type="text" class="form-control" id="nama_wali" name="nama_wali" 
                                   value="<?= $orang_tua->nama_wali ?? '' ?>">
                        </div>

                        <div class="form-group mb-3">
                            <label for="hubungan_wali" class="form-label">Hubungan dengan Siswa</label>
                            <select class="form-select" id="hubungan_wali" name="hubungan_wali">
                                <option value="">-- Pilih Hubungan --</option>
                                <option value="kakek" <?= ($orang_tua->hubungan_wali == 'kakek') ? 'selected' : '' ?>>Kakek</option>
                                <option value="nenek" <?= ($orang_tua->hubungan_wali == 'nenek') ? 'selected' : '' ?>>Nenek</option>
                                <option value="paman" <?= ($orang_tua->hubungan_wali == 'paman') ? 'selected' : '' ?>>Paman</option>
                                <option value="tante" <?= ($orang_tua->hubungan_wali == 'tante') ? 'selected' : '' ?>>Tante</option>
                                <option value="keluarga lain" <?= ($orang_tua->hubungan_wali == 'keluarga lain') ? 'selected' : '' ?>>Keluarga Lain</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nik_wali" class="form-label">NIK Wali (16 digit)</label>
                            <input type="text" class="form-control" id="nik_wali" name="nik_wali" 
                                   value="<?= $orang_tua->nik_wali ?? '' ?>" 
                                   pattern="\d{16}" maxlength="16">
                        </div>

                        <div class="form-group mb-3">
                            <label for="pekerjaan_wali" class="form-label">Pekerjaan Wali</label>
                            <input type="text" class="form-control" id="pekerjaan_wali" name="pekerjaan_wali" 
                                   value="<?= $orang_tua->pekerjaan_wali ?? '' ?>">
                        </div>

                        <div class="form-group mb-3">
                            <label for="telepon_wali" class="form-label">Nomor Telepon Wali</label>
                            <input type="tel" class="form-control" id="telepon_wali" name="telepon_wali" 
                                   value="<?= $orang_tua->telepon_wali ?? '' ?>" 
                                   pattern="08[0-9]{8,10}" placeholder="08xxxxxxxxx">
                            <small class="form-text text-muted">Format: 08xxx (10-12 digit)</small>
                        </div>

                        <!-- Buttons -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <a href="<?= base_url('user/pendaftaran/form/' . $pendaftaran->id . '/step/1') ?>" class="btn btn-secondary w-100">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary w-100" id="btnSubmit">
                                    <i class="fas fa-arrow-right"></i> Lanjut ke Step 3
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
document.getElementById('formDataOrangTua').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

    try {
        loadingModal.show();

        const response = await fetch('<?= base_url('user/pendaftaran/store-data-orangtua/' . $pendaftaran->id) ?>', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        loadingModal.hide();

        if (!response.ok) {
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
