<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Progress Bar -->
            <div class="mb-4">
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                        Step 3: Upload Dokumen
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Step 3: Upload Dokumen</h5>
                </div>
                <div class="card-body">
                    <!-- Upload Progress -->
                    <div class="alert alert-info" role="alert">
                        <h6 class="alert-heading">Dokumen yang diperlukan:</h6>
                        <ul class="mb-0">
                            <li>Kartu Keluarga (KK) - PDF/JPG/PNG, max 2MB</li>
                            <li>Akta Kelahiran - PDF/JPG/PNG, max 2MB</li>
                            <li>Foto Siswa - JPG/PNG, max 1MB (Ratio 3:4)</li>
                        </ul>
                    </div>

                    <!-- Dokumen Status -->
                    <div class="mb-4">
                        <h6 class="mb-3">Status Upload:</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card text-center <?= $dokumen_stats['kk'] ?? false ? 'border-success' : 'border-warning' ?>">
                                    <div class="card-body">
                                        <i class="fas fa-id-card fa-2x mb-2 <?= $dokumen_stats['kk'] ?? false ? 'text-success' : 'text-warning' ?>"></i>
                                        <h6>Kartu Keluarga</h6>
                                        <small><?= $dokumen_stats['kk'] ?? false ? '<span class="badge bg-success">Sudah Upload</span>' : '<span class="badge bg-warning">Belum Upload</span>' ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card text-center <?= $dokumen_stats['akta'] ?? false ? 'border-success' : 'border-warning' ?>">
                                    <div class="card-body">
                                        <i class="fas fa-file-pdf fa-2x mb-2 <?= $dokumen_stats['akta'] ?? false ? 'text-success' : 'text-warning' ?>"></i>
                                        <h6>Akta Kelahiran</h6>
                                        <small><?= $dokumen_stats['akta'] ?? false ? '<span class="badge bg-success">Sudah Upload</span>' : '<span class="badge bg-warning">Belum Upload</span>' ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card text-center <?= $dokumen_stats['foto'] ?? false ? 'border-success' : 'border-warning' ?>">
                                    <div class="card-body">
                                        <i class="fas fa-image fa-2x mb-2 <?= $dokumen_stats['foto'] ?? false ? 'text-success' : 'text-warning' ?>"></i>
                                        <h6>Foto Siswa</h6>
                                        <small><?= $dokumen_stats['foto'] ?? false ? '<span class="badge bg-success">Sudah Upload</span>' : '<span class="badge bg-warning">Belum Upload</span>' ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Forms -->
                    <div class="row">
                        <!-- KK Upload -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-id-card"></i> Kartu Keluarga</h6>
                                </div>
                                <div class="card-body">
                                    <form class="uploadForm" data-jenis="kk">
                                        <?= csrf_field() ?>
                                        <div class="mb-3">
                                            <input type="file" class="form-control fileInput" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
                                            <small class="text-muted">PDF/JPG/PNG, max 2MB</small>
                                        </div>
                                        <div class="progress d-none mb-3" style="height: 25px;">
                                            <div class="progress-bar progressBar" role="progressbar" style="width: 0%">0%</div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-upload"></i> Upload KK
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Akta Upload -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-file-pdf"></i> Akta Kelahiran</h6>
                                </div>
                                <div class="card-body">
                                    <form class="uploadForm" data-jenis="akta">
                                        <?= csrf_field() ?>
                                        <div class="mb-3">
                                            <input type="file" class="form-control fileInput" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
                                            <small class="text-muted">PDF/JPG/PNG, max 2MB</small>
                                        </div>
                                        <div class="progress d-none mb-3" style="height: 25px;">
                                            <div class="progress-bar progressBar" role="progressbar" style="width: 0%">0%</div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-upload"></i> Upload Akta
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Foto Upload -->
                        <div class="col-md-6 offset-md-3 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-image"></i> Foto Siswa</h6>
                                </div>
                                <div class="card-body">
                                    <form class="uploadForm" data-jenis="foto">
                                        <?= csrf_field() ?>
                                        <div class="mb-3">
                                            <input type="file" class="form-control fileInput" name="file" accept=".jpg,.jpeg,.png" required>
                                            <small class="text-muted">JPG/PNG, max 1MB, ratio 3:4</small>
                                        </div>
                                        <div id="previewFoto" class="mb-3 d-none text-center">
                                            <img id="previewImage" src="" alt="Preview" style="max-width: 150px; max-height: 200px;">
                                        </div>
                                        <div class="progress d-none mb-3" style="height: 25px;">
                                            <div class="progress-bar progressBar" role="progressbar" style="width: 0%">0%</div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-upload"></i> Upload Foto
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <a href="<?= base_url('user/pendaftaran/form/' . $pendaftaran->id . '/step/2') ?>" class="btn btn-secondary w-100">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary w-100" id="btnNext" disabled>
                                <i class="fas fa-arrow-right"></i> Lanjut ke Step 4
                            </button>
                        </div>
                    </div>
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
                <p class="mt-3" id="loadingText">Mengunggah dokumen...</p>
            </div>
        </div>
    </div>
</div>

<script>
const uploadedDocs = {
    kk: <?= isset($dokumen['kk']) ? 'true' : 'false' ?>,
    akta: <?= isset($dokumen['akta']) ? 'true' : 'false' ?>,
    foto: <?= isset($dokumen['foto']) ? 'true' : 'false' ?>
};

// Handle file preview for foto
document.querySelectorAll('.uploadForm[data-jenis="foto"] .fileInput').forEach(input => {
    input.addEventListener('change', function(e) {
        const file = this.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('previewImage').src = event.target.result;
                document.getElementById('previewFoto').classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });
});

// Handle form submission
document.querySelectorAll('.uploadForm').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const jenis = this.dataset.jenis;
        const fileInput = this.querySelector('.fileInput');
        const file = fileInput.files[0];
        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

        if (!file) {
            alert('Pilih file terlebih dahulu');
            return;
        }

        const formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        formData.append('jenis_dokumen', jenis);
        formData.append('file', file);

        try {
            loadingModal.show();

            const response = await fetch('<?= base_url('user/pendaftaran/upload-dokumen/' . $pendaftaran->id) ?>', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            loadingModal.hide();

            if (!response.ok) {
                alert(data.message || 'Gagal mengunggah file');
                return;
            }

            // Success
            uploadedDocs[jenis] = true;
            alert('File berhasil diupload');
            
            // Update UI
            location.reload();

        } catch (error) {
            loadingModal.hide();
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error.message);
        }
    });
});

// Enable/disable next button based on upload status
function checkAllDocuments() {
    const allUploaded = uploadedDocs.kk && uploadedDocs.akta && uploadedDocs.foto;
    document.getElementById('btnNext').disabled = !allUploaded;
}

document.getElementById('btnNext').addEventListener('click', function() {
    if (uploadedDocs.kk && uploadedDocs.akta && uploadedDocs.foto) {
        window.location.href = '<?= base_url('user/pendaftaran/form/' . $pendaftaran->id . '/step/4') ?>';
    } else {
        alert('Semua dokumen harus diupload terlebih dahulu');
    }
});

checkAllDocuments();
</script>

<?php $this->endSection(); ?>
