<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sistem Pendaftaran Pelatihan IT') ?></title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: 700; }
        .card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
        footer { font-size: 0.875rem; color: #6c757d; }
    </style>
</head>
<body>

    <!-- Navbar Publik -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="bi bi-mortarboard-fill me-2"></i>Pelatihan IT
            </a>

            <!-- Tombol Admin disembunyikan dari publik (hanya tampil jika admin sudah login) -->
            <?php if (session()->get('admin_logged_in')): ?>
                <div class="ms-auto">
                    <a href="<?= base_url('/admin') ?>" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-speedometer2 me-1"></i>Panel Admin
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Konten Halaman -->
    <main class="container mb-5">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="text-center py-3">
        <p>&copy; <?= date('Y') ?> Sistem Pendaftaran Pelatihan IT &mdash; UTS Testing &amp; Implementasi Sistem</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
