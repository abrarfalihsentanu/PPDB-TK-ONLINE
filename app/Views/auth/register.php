<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Register' ?> - PPDB TK Online</title>

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
            padding: 20px 0;
        }

        .register-container {
            max-width: 500px;
            width: 100%;
            padding: 20px;
        }

        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .register-body {
            padding: 40px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            transition: transform 0.2s;
        }

        .btn-register:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
        }

        .input-group-text {
            background: white;
            border-right: none;
        }

        .form-control {
            border-left: none;
        }

        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            transition: all 0.3s;
        }

        .strength-weak {
            background: #dc3545;
            width: 33%;
        }

        .strength-medium {
            background: #ffc107;
            width: 66%;
        }

        .strength-strong {
            background: #28a745;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <i class="fas fa-user-plus fa-3x mb-3"></i>
                <h3>Daftar Akun Baru</h3>
                <p class="mb-0">PPDB TK Online</p>
            </div>

            <div class="register-body">
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

                <form action="<?= base_url('auth/attempt-register') ?>" method="POST" id="registerForm">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input
                                type="text"
                                class="form-control"
                                id="username"
                                name="username"
                                placeholder="Username (huruf dan angka)"
                                value="<?= old('username') ?>"
                                pattern="[a-zA-Z0-9]+"
                                required>
                        </div>
                        <small class="text-muted">Minimal 3 karakter, hanya huruf dan angka</small>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="email@example.com"
                                value="<?= old('email') ?>"
                                required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
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
                                required
                                oninput="checkPasswordStrength()">
                            <span class="input-group-text" style="cursor: pointer;" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="toggleIcon1"></i>
                            </span>
                        </div>
                        <div class="password-strength" id="passwordStrength"></div>
                        <small class="text-muted" id="strengthText"></small>
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

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" required>
                        <label class="form-check-label" for="terms">
                            Saya menyetujui <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Syarat & Ketentuan</a>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-register w-100">
                        <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                    </button>
                </form>

                <hr class="my-4">

                <div class="text-center">
                    <p class="mb-0">
                        Sudah punya akun?
                        <a href="<?= base_url('auth/login') ?>" class="fw-bold text-decoration-none">
                            Login di sini
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-4 text-white">
            <p>&copy; <?= date('Y') ?> PPDB TK Online. All rights reserved.</p>
        </div>
    </div>

    <!-- Modal Syarat & Ketentuan -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Syarat & Ketentuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>1. Ketentuan Umum</h6>
                    <p>Dengan mendaftar, Anda menyetujui untuk memberikan informasi yang benar dan akurat.</p>

                    <h6>2. Privasi Data</h6>
                    <p>Data yang Anda berikan akan dijaga kerahasiaannya dan hanya digunakan untuk keperluan pendaftaran.</p>

                    <h6>3. Tanggung Jawab Pengguna</h6>
                    <p>Anda bertanggung jawab menjaga kerahasiaan akun dan password Anda.</p>

                    <h6>4. Perubahan Ketentuan</h6>
                    <p>Pihak sekolah berhak mengubah syarat dan ketentuan sewaktu-waktu.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password visibility
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

        // Check password strength
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('strengthText');

            if (password.length === 0) {
                strengthBar.className = 'password-strength';
                strengthText.textContent = '';
                return;
            }

            let strength = 0;

            // Check length
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;

            // Check for numbers
            if (/\d/.test(password)) strength++;

            // Check for lowercase and uppercase
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;

            // Check for special characters
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            if (strength <= 2) {
                strengthBar.className = 'password-strength strength-weak';
                strengthText.textContent = 'Password lemah';
                strengthText.style.color = '#dc3545';
            } else if (strength <= 4) {
                strengthBar.className = 'password-strength strength-medium';
                strengthText.textContent = 'Password sedang';
                strengthText.style.color = '#ffc107';
            } else {
                strengthBar.className = 'password-strength strength-strong';
                strengthText.textContent = 'Password kuat';
                strengthText.style.color = '#28a745';
            }
        }

        // Check password match
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

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const username = document.getElementById('username').value;

            if (username.length < 3) {
                e.preventDefault();
                alert('Username minimal 3 karakter!');
                return;
            }

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

            if (!document.getElementById('terms').checked) {
                e.preventDefault();
                alert('Anda harus menyetujui syarat dan ketentuan!');
                return;
            }
        });
    </script>
</body>

</html>