<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pelatihan yang Tersedia</h2>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (empty($pelatihan)): ?>
    <div class="card p-5 text-center text-muted">
        <div>
            <i class="bi bi-inbox" style="font-size: 3.5rem;"></i>
        </div>
        <h5 class="mt-3">Belum Ada Pelatihan</h5>
        <p class="mb-0">Tidak ada jadwal pelatihan yang tersedia saat ini.</p>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($pelatihan as $item): ?>
            <?php 
                $isTersedia = ($item['status'] === 'Tersedia' && (int)$item['sisa_kuota'] > 0); 
            ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title text-primary mb-0"><?= esc($item['judul']) ?></h5>
                        </div>

                        <div class="mb-3">
                            <?php if ($isTersedia): ?>
                                <span class="badge bg-success">TERSEDIA</span>
                            <?php else: ?>
                                <span class="badge bg-danger">PENUH</span>
                            <?php endif; ?>
                        </div>

                        <ul class="list-unstyled mb-0">
                            <li class="mb-2 text-secondary">
                                <i class="bi bi-person-fill text-muted me-2"></i>
                                <strong>Instruktur:</strong> <?= esc($item['instruktur']) ?>
                            </li>
                            <li class="mb-2 text-secondary">
                                <i class="bi bi-calendar3 text-muted me-2"></i>
                                <strong>Tanggal:</strong> <?= date('d F Y', strtotime($item['tanggal'])) ?>
                            </li>
                            <li class="mb-2 text-secondary">
                                <i class="bi bi-clock-fill text-muted me-2"></i>
                                <strong>Waktu:</strong>
                                <?= date('H.i', strtotime($item['jam_mulai'])) ?> – <?= date('H.i', strtotime($item['jam_selesai'])) ?> WIB
                            </li>
                            <li class="mb-2 text-secondary">
                                <i class="bi bi-people-fill text-muted me-2"></i>
                                <strong>Sisa Kuota:</strong> 
                                <span class="fw-bold <?= $isTersedia ? 'text-success' : 'text-danger' ?>">
                                    <?= esc($item['sisa_kuota']) ?>
                                </span> / <?= esc($item['kuota']) ?>
                            </li>
                        </ul>
                    </div>

                    <div class="card-footer bg-white border-0 pt-0 pb-3">
                        <?php if ($isTersedia): ?>
                            <a href="<?= base_url('daftar/' . $item['id']) ?>" class="btn btn-primary w-100">
                                Daftar Sekarang <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled>
                                Pendaftaran Ditutup
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
