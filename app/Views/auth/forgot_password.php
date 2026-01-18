<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Lupa Password' ?> - PPDB TK Online</title>

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

        .forgot-container {
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }

        .forgot-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .forgot-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .forgot-body {
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
    <div class="forgot-container">
        <div class="forgot-card">
            <div class="forgot-header">
                <i class="fas fa-key fa-3x mb-3"></i>
                <h3>Lupa Password?</h3>
                <p class="mb-0">Masukkan email Anda untuk reset password</p>
            </div>

            <div class="forgot-body">
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

                <form action="<?= base_url('auth/process-forgot-password') ?>" method="POST">
                    <?= csrf_field() ?>

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
                        <small class="text-muted">
                            Kami akan mengirimkan link reset password ke email ini
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-submit w-100">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Link Reset
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
</body>

</html>