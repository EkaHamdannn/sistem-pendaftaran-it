<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin — Pelatihan IT') ?></title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #212529;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            padding: 0.75rem 1rem;
            border-radius: 0;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }
        .sidebar .nav-link i { margin-right: 0.5rem; }
        .content-area { padding: 1.5rem; }
        .card { border: none; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
    </style>
</head>
<body>

    <!-- Topbar Admin -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('/admin') ?>">
                <i class="bi bi-gear-fill me-2"></i>Admin Panel
            </a>
            <div class="ms-auto d-flex align-items-center">
                <span class="text-light me-3">
                    <i class="bi bi-person-circle me-1"></i>
                    Halo, <?= esc(session()->get('admin_username') ?? 'Admin') ?>
                </span>
                <a href="<?= base_url('/logout') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 sidebar d-none d-md-block py-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= (url_is('admin') || url_is('admin/')) ? 'active' : '' ?>"
                           href="<?= base_url('/admin') ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (url_is('admin/pelatihan*') || url_is('admin/peserta*')) ? 'active' : '' ?>"
                           href="<?= base_url('/admin/pelatihan') ?>">
                            <i class="bi bi-journal-text"></i> Kelola Pelatihan
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Konten Utama -->
            <main class="col-md-10 content-area">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
