<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/users') ?>">Manajemen User</a></li>
                <li class="breadcrumb-item active">Detail User</li>
            </ol>
        </nav>
        <h4 class="mb-0">Detail User</h4>
    </div>
</div>

<div class="row">
    <!-- User Info Card -->
    <div class="col-lg-4 col-md-6">
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="avatar avatar-xl mb-3">
                    <span class="avatar-initial rounded-circle bg-label-primary">
                        <i class="ri-user-3-line ri-3x"></i>
                    </span>
                </div>
                <h5 class="mb-1"><?= esc($user->username) ?></h5>
                <p class="text-muted mb-2"><?= esc($user->email) ?></p>

                <!-- Status Badge -->
                <?php if ($user->status == 'active'): ?>
                    <span class="badge bg-success mb-3">
                        <i class="ri-check-line me-1"></i> Akun Aktif
                    </span>
                <?php else: ?>
                    <span class="badge bg-secondary mb-3">
                        <i class="ri-close-line me-1"></i> Akun Nonaktif
                    </span>
                <?php endif; ?>

                <div class="d-flex gap-2 justify-content-center mt-3">
                    <button class="btn btn-sm btn-label-primary" onclick="resetPassword(<?= $user->id ?>, '<?= esc($user->username) ?>')">
                        <i class="ri-lock-password-line me-1"></i> Reset Password
                    </button>
                    <a href="<?= base_url('admin/users/toggle-status/' . $user->id) ?>" class="btn btn-sm btn-label-<?= $user->status == 'active' ? 'warning' : 'success' ?>" onclick="return confirm('Apakah Anda yakin?')">
                        <i class="ri-toggle-line me-1"></i>
                        <?= $user->status == 'active' ? 'Nonaktifkan' : 'Aktifkan' ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Account Info -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-information-line me-1"></i> Informasi Akun</h6>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-6 text-muted small">ID User</dt>
                    <dd class="col-6 small"><?= $user->id ?></dd>

                    <dt class="col-6 text-muted small">Role</dt>
                    <dd class="col-6 small">
                        <span class="badge bg-label-info">Orang Tua</span>
                    </dd>

                    <dt class="col-6 text-muted small">Terdaftar</dt>
                    <dd class="col-6 small"><?= indonesianDate($user->created_at, true) ?></dd>

                    <dt class="col-6 text-muted small">Terakhir Update</dt>
                    <dd class="col-6 small"><?= timeAgo($user->updated_at) ?></dd>
                </dl>
            </div>
        </div>
    </div>

    <!-- Pendaftaran History -->
    <div class="col-lg-8 col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Riwayat Pendaftaran</h5>
                <span class="badge bg-label-primary"><?= count($pendaftaran_list) ?> Pendaftaran</span>
            </div>
            <div class="card-body">
                <?php if (empty($pendaftaran_list)): ?>
                    <div class="text-center py-5">
                        <div class="avatar avatar-lg mb-3">
                            <span class="avatar-initial rounded bg-label-secondary">
                                <i class="ri-file-list-line ri-2x"></i>
                            </span>
                        </div>
                        <p class="text-muted">Belum ada riwayat pendaftaran</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. Pendaftaran</th>
                                    <th>Nama Siswa</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pendaftaran_list as $p): ?>
                                    <tr>
                                        <td><strong><?= esc($p->nomor_pendaftaran) ?></strong></td>
                                        <td><?= esc($p->nama_lengkap) ?></td>
                                        <td><?= esc($p->nama_tahun) ?></td>
                                        <td>
                                            <span class="badge bg-<?= getStatusBadge($p->status_pendaftaran) ?>">
                                                <?= getStatusLabel($p->status_pendaftaran) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?= indonesianDate($p->created_at) ?></small>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('admin/pendaftaran/view/' . $p->id) ?>" class="btn btn-sm btn-icon btn-label-primary">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Activity Timeline (Optional - untuk pengembangan selanjutnya) -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="ri-time-line me-1"></i> Aktivitas Terakhir</h6>
            </div>
            <div class="card-body">
                <ul class="timeline mb-0">
                    <?php if (!empty($pendaftaran_list)): ?>
                        <?php foreach (array_slice($pendaftaran_list, 0, 5) as $p): ?>
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-<?= getStatusBadge($p->status_pendaftaran) ?>"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">
                                            <?php
                                            $actions = [
                                                'draft' => 'Membuat pendaftaran baru',
                                                'pending' => 'Submit formulir pendaftaran',
                                                'pembayaran_verified' => 'Pembayaran terverifikasi',
                                                'diverifikasi' => 'Berkas diverifikasi',
                                                'diterima' => 'Pendaftaran DITERIMA',
                                                'ditolak' => 'Pendaftaran ditolak'
                                            ];
                                            echo $actions[$p->status_pendaftaran] ?? 'Update status';
                                            ?>
                                        </h6>
                                        <small class="text-muted"><?= timeAgo($p->updated_at) ?></small>
                                    </div>
                                    <p class="mb-0 small">
                                        <span class="badge bg-label-secondary"><?= esc($p->nomor_pendaftaran) ?></span>
                                        - <?= esc($p->nama_lengkap) ?>
                                    </p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="text-center text-muted py-3">
                            <small>Belum ada aktivitas</small>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reset Password Result -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Password Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Password untuk user <strong id="resetUsername"></strong> telah direset.</p>
                <div class="alert alert-warning">
                    <h6 class="alert-heading mb-2">
                        <i class="ri-key-line me-1"></i> Password Baru:
                    </h6>
                    <div class="d-flex align-items-center">
                        <code id="newPassword" class="flex-grow-1 fs-5"></code>
                        <button class="btn btn-sm btn-icon btn-label-primary ms-2" onclick="copyPassword()" data-bs-toggle="tooltip" title="Copy">
                            <i class="ri-file-copy-line"></i>
                        </button>
                    </div>
                </div>
                <small class="text-danger">
                    <i class="ri-error-warning-line me-1"></i>
                    Simpan password ini dan berikan kepada user. Password tidak dapat ditampilkan lagi.
                </small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK, Saya Sudah Simpan</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
    function resetPassword(userId, username) {
        if (!confirm('Apakah Anda yakin ingin reset password untuk user ' + username + '?')) {
            return;
        }

        showLoading();

        $.ajax({
            url: '<?= base_url('admin/users/reset-password/') ?>' + userId,
            method: 'POST',
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                hideLoading();

                if (response.success) {
                    $('#resetUsername').text(username);
                    $('#newPassword').text(response.password);
                    $('#resetPasswordModal').modal('show');
                } else {
                    errorAlert(response.message);
                }
            },
            error: function() {
                hideLoading();
                errorAlert('Terjadi kesalahan saat reset password');
            }
        });
    }

    function copyPassword() {
        const password = document.getElementById('newPassword').textContent;
        navigator.clipboard.writeText(password).then(function() {
            successAlert('Password berhasil dicopy!');
        });
    }
</script>
<?= $this->endSection() ?>