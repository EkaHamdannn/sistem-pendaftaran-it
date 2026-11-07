<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <a href="<?= base_url('/admin/pelatihan') ?>" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Kelola Pelatihan
    </a>

    <!-- Header Informasi Pelatihan -->
    <div class="card shadow-sm border-0 mb-4 bg-white">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h3 class="text-primary fw-bold mb-1"><?= esc($pelatihan['judul']) ?></h3>
                    <div class="text-secondary">
                        <span class="me-3"><i class="bi bi-person-fill text-muted me-1"></i>Instruktur: <strong><?= esc($pelatihan['instruktur']) ?></strong></span>
                        <span class="me-3"><i class="bi bi-calendar3 text-muted me-1"></i><?= date('d F Y', strtotime($pelatihan['tanggal'])) ?></span>
                        <span><i class="bi bi-clock text-muted me-1"></i><?= date('H.i', strtotime($pelatihan['jam_mulai'])) ?> – <?= date('H.i', strtotime($pelatihan['jam_selesai'])) ?> WIB</span>
                    </div>
                </div>

                <div class="mt-3 mt-md-0 text-md-end">
                    <div class="text-muted small">Status &amp; Ketersediaan Kuota</div>
                    <div class="d-flex align-items-center justify-content-md-end gap-2 mt-1">
                        <?php if ($pelatihan['status'] === 'Tersedia' && (int)$pelatihan['sisa_kuota'] > 0): ?>
                            <span class="badge bg-success fs-6">Tersedia</span>
                        <?php else: ?>
                            <span class="badge bg-danger fs-6">Penuh</span>
                        <?php endif; ?>
                        <span class="fs-5 fw-bold text-dark">
                            <?= esc($pelatihan['sisa_kuota']) ?> <span class="text-muted fs-6 fw-normal">/ <?= esc($pelatihan['kuota']) ?> tempat</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Statistik Peserta -->
            <hr class="my-3">
            <div class="d-flex gap-3">
                <span class="badge bg-success-subtle text-success border border-success px-3 py-2">
                    <i class="bi bi-check-circle me-1"></i> Aktif Terdaftar: <strong><?= esc($total_terdaftar) ?></strong>
                </span>
                <span class="badge bg-secondary-subtle text-secondary border border-secondary px-3 py-2">
                    <i class="bi bi-x-circle me-1"></i> Dibatalkan: <strong><?= esc($total_dibatalkan) ?></strong>
                </span>
            </div>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Tabel Daftar Peserta -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-people-fill text-primary me-2"></i>Daftar Peserta Pelatihan
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 50px;">No</th>
                        <th>No. Tiket</th>
                        <th>Nama Peserta</th>
                        <th>Email</th>
                        <th>Nomor HP</th>
                        <th>Waktu Mendaftar</th>
                        <th class="text-center">Status</th>
                        <th class="text-end pe-3" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($peserta)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                                Belum ada peserta yang mendaftar pada pelatihan ini.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($peserta as $p): ?>
                            <tr>
                                <td class="ps-3 fw-semibold"><?= $no++ ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        #<?= sprintf('%05d', $p['id']) ?>
                                    </span>
                                </td>
                                <td class="fw-bold"><?= esc($p['nama']) ?></td>
                                <td><?= esc($p['email']) ?></td>
                                <td><?= esc($p['nomor_hp']) ?></td>
                                <td class="small text-muted">
                                    <?= date('d/m/Y H:i', strtotime($p['created_at'])) ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($p['status'] === 'Terdaftar'): ?>
                                        <span class="badge bg-success">Terdaftar</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Dibatalkan</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="d-inline-flex gap-1 justify-content-end">
                                        <?php if ($p['status'] === 'Terdaftar'): ?>
                                            <form action="<?= base_url('/admin/peserta/batalkan/' . $p['id']) ?>" 
                                                  method="post" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran peserta <?= esc($p['nama'], 'js') ?>? Sisa kuota pelatihan akan bertambah 1.');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Batalkan Pendaftaran">
                                                    <i class="bi bi-x-circle me-1"></i>Batalkan
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <form action="<?= base_url('/admin/peserta/hapus/' . $p['id']) ?>" 
                                              method="post" 
                                              class="d-inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data peserta <?= esc($p['nama'], 'js') ?> secara permanen?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Data Peserta">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
