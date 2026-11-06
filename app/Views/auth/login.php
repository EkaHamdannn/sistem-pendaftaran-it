<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Pelatihan IT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-card {
            max-width: 420px;
            width: 100%;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="card shadow">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-mortarboard-fill text-primary" style="font-size: 2.5rem;"></i>
                    <h4 class="mt-2">Login Admin</h4>
                    <p class="text-muted">Sistem Pendaftaran Pelatihan IT</p>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/login') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text"
                                   class="form-control"
                                   id="username"
                                   name="username"
                                   value="<?= old('username') ?>"
                                   placeholder="Masukkan username"
                                   required
                                   autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password"
                                   class="form-control"
                                   id="password"
                                   name="password"
                                   placeholder="Masukkan password"
                                   required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="<?= base_url('/') ?>" class="text-muted text-decoration-none">
                        <i class="bi bi-arrow-left me-1"></i>Kembali ke halaman utama
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
