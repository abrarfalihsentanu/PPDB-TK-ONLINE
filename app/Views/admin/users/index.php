<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Manajemen User</h4>
        <p class="text-muted mb-0">Kelola user orang tua yang terdaftar</p>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body">
        <form action="" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="active" <?= $status == 'active' ? 'selected' : '' ?>>Aktif</option>
                    <option value="inactive" <?= $status == 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="search" class="form-label">Cari</label>
                <input type="text" class="form-control" id="search" name="search" placeholder="Username atau email..." value="<?= esc($search) ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ri-search-line me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="tableUsers">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Total Pendaftaran</th>
                        <th>Tanggal Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $index => $user): ?>
                        <?php
                        // Count pendaftaran
                        $db = \Config\Database::connect();
                        $totalPendaftaran = $db->table('pendaftaran')
                            ->where('user_id', $user->id)
                            ->countAllResults();
                        ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            <?= strtoupper(substr($user->username, 0, 2)) ?>
                                        </span>
                                    </div>
                                    <strong><?= esc($user->username) ?></strong>
                                </div>
                            </td>
                            <td><?= esc($user->email) ?></td>
                            <td>
                                <?php if ($user->status == 'active'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-label-info"><?= $totalPendaftaran ?> pendaftaran</span>
                            </td>
                            <td>
                                <small class="text-muted"><?= timeAgo($user->created_at) ?></small>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="ri-more-2-line"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="<?= base_url('admin/users/view/' . $user->id) ?>">
                                                <i class="ri-eye-line me-2"></i> Detail
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="resetPassword(<?= $user->id ?>, '<?= esc($user->username) ?>')">
                                                <i class="ri-lock-password-line me-2"></i> Reset Password
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item <?= $user->status == 'active' ? 'text-warning' : 'text-success' ?>" href="<?= base_url('admin/users/toggle-status/' . $user->id) ?>" onclick="return confirm('Apakah Anda yakin ingin mengubah status user ini?')">
                                                <i class="ri-toggle-line me-2"></i>
                                                <?= $user->status == 'active' ? 'Nonaktifkan' : 'Aktifkan' ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
    $(document).ready(function() {
        $('#tableUsers').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            order: [
                [5, 'desc']
            ]
        });
    });

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