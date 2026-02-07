<form method="POST" action="<?= base_url('user/pendaftaran/store-data-siswa') ?>">
    <div class="mb-3">
        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= old('nama_lengkap') ?>" required>
    </div>
    <div class="mb-3">
        <label for="nik" class="form-label">NIK</label>
        <input type="text" class="form-control" id="nik" name="nik" value="<?= old('nik') ?>" required maxlength="16">
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?= old('tempat_lahir') ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= old('tanggal_lahir') ?>" required>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Jenis Kelamin</label>
        <div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_l" value="L" <?= old('jenis_kelamin') == 'L' ? 'checked' : '' ?> required>
                <label class="form-check-label" for="jk_l">Laki-laki</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_p" value="P" <?= old('jenis_kelamin') == 'P' ? 'checked' : '' ?> required>
                <label class="form-check-label" for="jk_p">Perempuan</label>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label for="agama" class="form-label">Agama</label>
        <select class="form-select" id="agama" name="agama" required>
            <option value="">-- Pilih Agama --</option>
            <?php $agamas = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']; ?>
            <?php foreach ($agamas as $a): ?>
                <option value="<?= $a ?>" <?= old('agama') == $a ? 'selected' : '' ?>><?= $a ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="alamat" class="form-label">Alamat Lengkap</label>
        <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= old('alamat') ?></textarea>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="kelurahan" class="form-label">Kelurahan/Desa</label>
            <input type="text" class="form-control" id="kelurahan" name="kelurahan" value="<?= old('kelurahan') ?>" required>
        </div>
        <div class="col-md-4 mb-3">
            <label for="kecamatan" class="form-label">Kecamatan</label>
            <input type="text" class="form-control" id="kecamatan" name="kecamatan" value="<?= old('kecamatan') ?>" required>
        </div>
        <div class="col-md-4 mb-3">
            <label for="kota_kabupaten" class="form-label">Kota / Kabupaten</label>
            <input type="text" class="form-control" id="kota_kabupaten" name="kota_kabupaten" value="<?= old('kota_kabupaten') ?>" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 mb-3">
            <label for="provinsi" class="form-label">Provinsi</label>
            <input type="text" class="form-control" id="provinsi" name="provinsi" value="<?= old('provinsi') ?>" required>
        </div>
        <div class="col-md-3 mb-3">
            <label for="kode_pos" class="form-label">Kode Pos</label>
            <input type="text" class="form-control" id="kode_pos" name="kode_pos" value="<?= old('kode_pos') ?>">
        </div>
        <div class="col-md-3 mb-3">
            <label for="rt" class="form-label">RT</label>
            <input type="text" class="form-control" id="rt" name="rt" value="<?= old('rt') ?>">
        </div>
        <div class="col-md-3 mb-3">
            <label for="rw" class="form-label">RW</label>
            <input type="text" class="form-control" id="rw" name="rw" value="<?= old('rw') ?>">
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Lanjutkan</button>
    </div>
</form>