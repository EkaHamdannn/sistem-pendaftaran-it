<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Kelola Pelatihan</h2>
        <p class="text-muted mb-0">Daftar seluruh pelatihan dan jadwal yang tersedia di sistem.</p>
    </div>
    <a href="<?= base_url('/admin/pelatihan/tambah') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Tambah Pelatihan
    </a>
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

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 50px;">No</th>
                        <th>Judul Pelatihan</th>
                        <th>Instruktur</th>
                        <th>Tanggal & Waktu</th>
                        <th class="text-center">Sisa / Total Kuota</th>
                        <th class="text-center">Status</th>
                        <th class="text-end pe-3" style="width: 240px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pelatihan)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Belum ada data pelatihan. Klik tombol <strong>Tambah Pelatihan</strong> untuk membuat data baru.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($pelatihan as $item): ?>
                            <tr>
                                <td class="ps-3 fw-semibold"><?= $no++ ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?= esc($item['judul']) ?></div>
                                </td>
                                <td><?= esc($item['instruktur']) ?></td>
                                <td>
                                    <div><i class="bi bi-calendar3 me-1 text-muted"></i><?= date('d M Y', strtotime($item['tanggal'])) ?></div>
                                    <small class="text-muted"><i class="bi bi-clock me-1"></i><?= date('H.i', strtotime($item['jam_mulai'])) ?> – <?= date('H.i', strtotime($item['jam_selesai'])) ?> WIB</small>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold <?= ((int)$item['sisa_kuota'] > 0) ? 'text-success' : 'text-danger' ?>">
                                        <?= esc($item['sisa_kuota']) ?>
                                    </span>
                                    <span class="text-muted">/ <?= esc($item['kuota']) ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($item['status'] === 'Tersedia' && (int)$item['sisa_kuota'] > 0): ?>
                                        <span class="badge bg-success">Tersedia</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Penuh</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('/admin/peserta/' . $item['id']) ?>" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="Lihat Peserta">
                                            <i class="bi bi-people-fill me-1"></i>Peserta
                                        </a>
                                        <a href="<?= base_url('/admin/pelatihan/ubah/' . $item['id']) ?>" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Ubah Pelatihan">
                                            <i class="bi bi-pencil-square me-1"></i>Ubah
                                        </a>
                                        <form action="<?= base_url('/admin/pelatihan/hapus/' . $item['id']) ?>" 
                                              method="post" 
                                              class="d-inline"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelatihan: <?= esc($item['judul'], 'js') ?>?');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Pelatihan">
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
