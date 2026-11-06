<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow border-0 text-center p-4">
            <div class="card-body">
                <div class="mb-3">
                    <span class="badge bg-success p-3 rounded-circle">
                        <i class="bi bi-check-lg" style="font-size: 2.5rem;"></i>
                    </span>
                </div>

                <h3 class="text-success fw-bold mb-1">Pendaftaran Berhasil!</h3>
                <p class="text-muted mb-4">Terima kasih telah mendaftar. Data Anda telah berhasil dicatat oleh sistem.</p>

                <!-- Box Nomor Pendaftaran -->
                <div class="bg-light border rounded-3 p-3 mb-4">
                    <div class="text-secondary small text-uppercase fw-bold">Nomor Pendaftaran</div>
                    <div class="display-6 fw-bold text-primary">
                        #<?= sprintf('%05d', $pendaftaran['id']) ?>
                    </div>
                </div>

                <!-- Rincian Pendaftaran -->
                <div class="text-start border rounded-3 p-3 mb-4 bg-white">
                    <h6 class="fw-bold border-bottom pb-2 mb-3 text-secondary">
                        <i class="bi bi-card-checklist me-1"></i> Rincian Pendaftaran
                    </h6>
                    
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted">Nama Lengkap</div>
                        <div class="col-sm-8 fw-semibold"><?= esc($pendaftaran['nama']) ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted">Email</div>
                        <div class="col-sm-8"><?= esc($pendaftaran['email']) ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Nomor WhatsApp/HP</div>
                        <div class="col-sm-8"><?= esc($pendaftaran['nomor_hp']) ?></div>
                    </div>

                    <h6 class="fw-bold border-bottom pb-2 mb-3 text-secondary pt-2">
                        <i class="bi bi-mortarboard me-1"></i> Informasi Pelatihan
                    </h6>

                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted">Pelatihan</div>
                        <div class="col-sm-8 fw-semibold text-primary"><?= esc($pendaftaran['judul_pelatihan']) ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted">Instruktur</div>
                        <div class="col-sm-8"><?= esc($pendaftaran['instruktur']) ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 text-muted">Tanggal</div>
                        <div class="col-sm-8"><?= date('d F Y', strtotime($pendaftaran['tanggal'])) ?></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 text-muted">Waktu</div>
                        <div class="col-sm-8"><?= date('H.i', strtotime($pendaftaran['jam_mulai'])) ?> – <?= date('H.i', strtotime($pendaftaran['jam_selesai'])) ?> WIB</div>
                    </div>
                </div>

                <!-- Alert Catatan -->
                <div class="alert alert-warning text-start mb-4">
                    <div class="d-flex">
                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                        <div>
                            <strong>Penting:</strong> Simpan nomor pendaftaran ini (bisa difoto/screenshot) sebagai bukti kedatangan Anda. Mohon hadir 15 menit sebelum kegiatan dimulai.
                        </div>
                    </div>
                </div>

                <!-- Navigasi -->
                <div>
                    <a href="<?= base_url('/') ?>" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pelatihan
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
