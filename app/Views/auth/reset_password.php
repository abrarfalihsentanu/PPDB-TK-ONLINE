<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Reset Password' ?> - PPDB TK Online</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .reset-container {
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }

        .reset-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .reset-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .reset-body {
            padding: 40px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="reset-container">
        <div class="reset-card">
            <div class="reset-header">
                <i class="fas fa-lock fa-3x mb-3"></i>
                <h3>Reset Password</h3>
                <p class="mb-0">Masukkan password baru Anda</p>
            </div>

            <div class="reset-body">
                <?php if (session()->getFlashdata('message')): ?>
                    <?php $message = session()->getFlashdata('message'); ?>
                    <div class="alert alert-<?= $message['type'] ?> alert-dismissible fade show" role="alert">
                        <?= $message['text'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/process-reset-password') ?>" method="POST" id="resetForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="token" value="<?= esc($token) ?>">

                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                placeholder="Minimal 8 karakter"
                                required>
                            <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="toggleIcon1"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input
                                type="password"
                                class="form-control"
                                id="confirm_password"
                                name="confirm_password"
                                placeholder="Ketik ulang password"
                                required>
                            <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('confirm_password')">
                                <i class="fas fa-eye" id="toggleIcon2"></i>
                            </span>
                        </div>
                        <small class="text-muted" id="passwordMatch"></small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-submit w-100">
                        <i class="fas fa-check me-2"></i> Reset Password
                    </button>
                </form>

                <hr class="my-4">

                <div class="text-center">
                    <a href="<?= base_url('auth/login') ?>" class="text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const iconNum = fieldId === 'password' ? '1' : '2';
            const toggleIcon = document.getElementById('toggleIcon' + iconNum);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const matchText = document.getElementById('passwordMatch');

            if (confirmPassword.length === 0) {
                matchText.textContent = '';
                return;
            }

            if (password === confirmPassword) {
                matchText.textContent = '✓ Password cocok';
                matchText.style.color = '#28a745';
            } else {
                matchText.textContent = '✗ Password tidak cocok';
                matchText.style.color = '#dc3545';
            }
        });

        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password.length < 8) {
                e.preventDefault();
                alert('Password minimal 8 karakter!');
                return;
            }

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Konfirmasi password tidak cocok!');
                return;
            }
        });
    </script>
</body>

</html>