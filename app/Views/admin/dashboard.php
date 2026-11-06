<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<h2 class="mb-4">Dashboard</h2>
<p class="text-muted mb-4">
    Selamat datang, <strong><?= esc($admin_username) ?></strong>. Berikut ringkasan data sistem.
</p>

<div class="row">
    <!-- Card: Total Pelatihan -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card border-start border-primary border-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-journal-text text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Total Pelatihan</h6>
                        <h2 class="mb-0"><?= esc($total_pelatihan) ?></h2>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0">
                <a href="<?= base_url('/admin/pelatihan') ?>" class="text-decoration-none">
                    Kelola Pelatihan <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Card: Total Peserta Aktif -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="bi bi-people-fill text-success" style="font-size: 2.5rem;"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="text-muted mb-1">Peserta Terdaftar</h6>
                        <h2 class="mb-0"><?= esc($total_peserta) ?></h2>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-0">
                <span class="text-muted">Peserta dengan status aktif</span>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
