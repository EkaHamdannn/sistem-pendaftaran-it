<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary btn-sm mb-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pelatihan
        </a>

        <!-- Detail Singkat Pelatihan -->
        <div class="card mb-4 border-primary shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Informasi Pelatihan</h5>
            </div>
            <div class="card-body">
                <h4 class="text-primary mb-3"><?= esc($pelatihan['judul']) ?></h4>
                <div class="row g-2 text-secondary">
                    <div class="col-sm-6">
                        <i class="bi bi-person-fill text-muted me-2"></i>
                        <strong>Instruktur:</strong> <?= esc($pelatihan['instruktur']) ?>
                    </div>
                    <div class="col-sm-6">
                        <i class="bi bi-calendar3 text-muted me-2"></i>
                        <strong>Tanggal:</strong> <?= date('d F Y', strtotime($pelatihan['tanggal'])) ?>
                    </div>
                    <div class="col-sm-6">
                        <i class="bi bi-clock-fill text-muted me-2"></i>
                        <strong>Waktu:</strong> <?= date('H.i', strtotime($pelatihan['jam_mulai'])) ?> – <?= date('H.i', strtotime($pelatihan['jam_selesai'])) ?> WIB
                    </div>
                    <div class="col-sm-6">
                        <i class="bi bi-people-fill text-muted me-2"></i>
                        <strong>Sisa Kuota:</strong> 
                        <span class="badge bg-success fs-6"><?= esc($pelatihan['sisa_kuota']) ?> tempat</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Pendaftaran -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Formulir Pendaftaran Peserta</h5>
            </div>
            <div class="card-body p-4">

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php 
                    $errors = session()->getFlashdata('errors') ?? [];
                ?>

                <form action="<?= base_url('daftar/' . $pelatihan['id']) ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Nama Lengkap -->
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>"
                               id="nama"
                               name="nama"
                               placeholder="Contoh: Eka Hamdan"
                               value="<?= old('nama') ?>"
                               required>
                        <?php if (isset($errors['nama'])): ?>
                            <div class="invalid-feedback">
                                <?= esc($errors['nama']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Alamat Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email"
                               class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                               id="email"
                               name="email"
                               placeholder="Contoh: user@domain.com"
                               value="<?= old('email') ?>"
                               required>
                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback">
                                <?= esc($errors['email']) ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-text">Gunakan email aktif. Satu email hanya dapat mendaftar 1 kali pada pelatihan ini.</div>
                    </div>

                    <!-- Nomor HP -->
                    <div class="mb-4">
                        <label for="nomor_hp" class="form-label fw-semibold">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control <?= isset($errors['nomor_hp']) ? 'is-invalid' : '' ?>"
                               id="nomor_hp"
                               name="nomor_hp"
                               placeholder="Contoh: 08123456789"
                               value="<?= old('nomor_hp') ?>"
                               required>
                        <?php if (isset($errors['nomor_hp'])): ?>
                            <div class="invalid-feedback">
                                <?= esc($errors['nomor_hp']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-between pt-2">
                        <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary px-4">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-send-check me-1"></i> Daftar Sekarang
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
