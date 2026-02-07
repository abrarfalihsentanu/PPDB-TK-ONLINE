<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h4>Data Orang Tua / Wali</h4>
        <p class="text-muted">Isi data orang tua atau wali siswa.</p>

        <form method="POST" action="<?= base_url('user/pendaftaran/store-data-orangtua/' . $pendaftaran_id) ?>">
            <!-- DATA AYAH -->
            <h5 class="mb-3 text-primary">Data Ayah</h5>
            <div class="mb-3">
                <label class="form-label">Nama Ayah <span class="text-danger">*</span></label>
                <input type="text" name="nama_ayah" class="form-control" value="<?= old('nama_ayah', $orang_tua->nama_ayah ?? '') ?>" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIK Ayah</label>
                    <input type="text" name="nik_ayah" class="form-control" value="<?= old('nik_ayah', $orang_tua->nik_ayah ?? '') ?>" placeholder="16 digit">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pekerjaan Ayah <span class="text-danger">*</span></label>
                    <input type="text" name="pekerjaan_ayah" class="form-control" value="<?= old('pekerjaan_ayah', $orang_tua->pekerjaan_ayah ?? '') ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Penghasilan Ayah</label>
                    <select name="penghasilan_ayah" class="form-control">
                        <option value="">-- Pilih Penghasilan --</option>
                        <option value="< 500 ribu" <?= (old('penghasilan_ayah', $orang_tua->penghasilan_ayah ?? '') == '< 500 ribu') ? 'selected' : '' ?>>Kurang dari 500 ribu</option>
                        <option value="500 ribu - 1 juta" <?= (old('penghasilan_ayah', $orang_tua->penghasilan_ayah ?? '') == '500 ribu - 1 juta') ? 'selected' : '' ?>>500 ribu - 1 juta</option>
                        <option value="1 - 3 juta" <?= (old('penghasilan_ayah', $orang_tua->penghasilan_ayah ?? '') == '1 - 3 juta') ? 'selected' : '' ?>>1 - 3 juta</option>
                        <option value="3 - 5 juta" <?= (old('penghasilan_ayah', $orang_tua->penghasilan_ayah ?? '') == '3 - 5 juta') ? 'selected' : '' ?>>3 - 5 juta</option>
                        <option value="> 5 juta" <?= (old('penghasilan_ayah', $orang_tua->penghasilan_ayah ?? '') == '> 5 juta') ? 'selected' : '' ?>>Lebih dari 5 juta</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telepon Ayah <span class="text-danger">*</span></label>
                    <input type="text" name="telepon_ayah" class="form-control" value="<?= old('telepon_ayah', $orang_tua->telepon_ayah ?? '') ?>" placeholder="08..." required>
                </div>
            </div>

            <!-- DATA IBU -->
            <h5 class="mb-3 text-primary">Data Ibu</h5>
            <div class="mb-3">
                <label class="form-label">Nama Ibu <span class="text-danger">*</span></label>
                <input type="text" name="nama_ibu" class="form-control" value="<?= old('nama_ibu', $orang_tua->nama_ibu ?? '') ?>" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIK Ibu</label>
                    <input type="text" name="nik_ibu" class="form-control" value="<?= old('nik_ibu', $orang_tua->nik_ibu ?? '') ?>" placeholder="16 digit">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pekerjaan Ibu <span class="text-danger">*</span></label>
                    <input type="text" name="pekerjaan_ibu" class="form-control" value="<?= old('pekerjaan_ibu', $orang_tua->pekerjaan_ibu ?? '') ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Penghasilan Ibu</label>
                    <select name="penghasilan_ibu" class="form-control">
                        <option value="">-- Pilih Penghasilan --</option>
                        <option value="< 500 ribu" <?= (old('penghasilan_ibu', $orang_tua->penghasilan_ibu ?? '') == '< 500 ribu') ? 'selected' : '' ?>>Kurang dari 500 ribu</option>
                        <option value="500 ribu - 1 juta" <?= (old('penghasilan_ibu', $orang_tua->penghasilan_ibu ?? '') == '500 ribu - 1 juta') ? 'selected' : '' ?>>500 ribu - 1 juta</option>
                        <option value="1 - 3 juta" <?= (old('penghasilan_ibu', $orang_tua->penghasilan_ibu ?? '') == '1 - 3 juta') ? 'selected' : '' ?>>1 - 3 juta</option>
                        <option value="3 - 5 juta" <?= (old('penghasilan_ibu', $orang_tua->penghasilan_ibu ?? '') == '3 - 5 juta') ? 'selected' : '' ?>>3 - 5 juta</option>
                        <option value="> 5 juta" <?= (old('penghasilan_ibu', $orang_tua->penghasilan_ibu ?? '') == '> 5 juta') ? 'selected' : '' ?>>Lebih dari 5 juta</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telepon Ibu <span class="text-danger">*</span></label>
                    <input type="text" name="telepon_ibu" class="form-control" value="<?= old('telepon_ibu', $orang_tua->telepon_ibu ?? '') ?>" placeholder="08..." required>
                </div>
            </div>

            <!-- DATA WALI (OPSIONAL) -->
            <h5 class="mb-3 mt-4 text-primary">Data Wali <small class="text-muted">(Opsional)</small></h5>
            <div class="mb-3">
                <label class="form-label">Nama Wali</label>
                <input type="text" name="nama_wali" class="form-control" value="<?= old('nama_wali', $orang_tua->nama_wali ?? '') ?>">
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">NIK Wali</label>
                    <input type="text" name="nik_wali" class="form-control" value="<?= old('nik_wali', $orang_tua->nik_wali ?? '') ?>" placeholder="16 digit">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Pekerjaan Wali</label>
                    <input type="text" name="pekerjaan_wali" class="form-control" value="<?= old('pekerjaan_wali', $orang_tua->pekerjaan_wali ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Hubungan dengan Siswa</label>
                    <input type="text" name="hubungan_wali" class="form-control" value="<?= old('hubungan_wali', $orang_tua->hubungan_wali ?? '') ?>" placeholder="Nenek, Kakak, dll">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Telepon Wali</label>
                <input type="text" name="telepon_wali" class="form-control" value="<?= old('telepon_wali', $orang_tua->telepon_wali ?? '') ?>" placeholder="08...">
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= base_url('user/pendaftaran/preview/' . $pendaftaran_id) ?>" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Data Orang Tua</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>