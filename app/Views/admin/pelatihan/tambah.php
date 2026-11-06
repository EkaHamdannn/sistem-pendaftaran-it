<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <a href="<?= base_url('/admin/pelatihan') ?>" class="btn btn-outline-secondary btn-sm mb-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Kelola Pelatihan
        </a>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-primary">
                    <i class="bi bi-plus-circle-dotted me-2"></i>Tambah Pelatihan Baru
                </h5>
            </div>
            <div class="card-body p-4">

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php $errors = session()->getFlashdata('errors') ?? []; ?>

                <form action="<?= base_url('/admin/pelatihan/tambah') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="judul" class="form-label fw-semibold">Judul Pelatihan <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control <?= isset($errors['judul']) ? 'is-invalid' : '' ?>"
                               id="judul"
                               name="judul"
                               placeholder="Contoh: Belajar RESTful API dengan CodeIgniter 4"
                               value="<?= old('judul') ?>"
                               required>
                        <?php if (isset($errors['judul'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['judul']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="instruktur" class="form-label fw-semibold">Nama Instruktur <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control <?= isset($errors['instruktur']) ? 'is-invalid' : '' ?>"
                               id="instruktur"
                               name="instruktur"
                               placeholder="Contoh: Eka Hamdan Rahmawan"
                               value="<?= old('instruktur') ?>"
                               required>
                        <?php if (isset($errors['instruktur'])): ?>
                            <div class="invalid-feedback"><?= esc($errors['instruktur']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal" class="form-label fw-semibold">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="date"
                                   class="form-control <?= isset($errors['tanggal']) ? 'is-invalid' : '' ?>"
                                   id="tanggal"
                                   name="tanggal"
                                   value="<?= old('tanggal') ?>"
                                   required>
                            <?php if (isset($errors['tanggal'])): ?>
                                <div class="invalid-feedback"><?= esc($errors['tanggal']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="kuota" class="form-label fw-semibold">Kapasitas Kuota <span class="text-danger">*</span></label>
                            <input type="number"
                                   class="form-control <?= isset($errors['kuota']) ? 'is-invalid' : '' ?>"
                                   id="kuota"
                                   name="kuota"
                                   min="1"
                                   max="100"
                                   placeholder="1 - 100"
                                   value="<?= old('kuota') ?>"
                                   required>
                            <?php if (isset($errors['kuota'])): ?>
                                <div class="invalid-feedback"><?= esc($errors['kuota']) ?></div>
                            <?php endif; ?>
                            <div class="form-text">Maksimal 100 peserta per pelatihan.</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="jam_mulai" class="form-label fw-semibold">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time"
                                   class="form-control <?= isset($errors['jam_mulai']) ? 'is-invalid' : '' ?>"
                                   id="jam_mulai"
                                   name="jam_mulai"
                                   value="<?= old('jam_mulai') ?>"
                                   required>
                            <?php if (isset($errors['jam_mulai'])): ?>
                                <div class="invalid-feedback"><?= esc($errors['jam_mulai']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="jam_selesai" class="form-label fw-semibold">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time"
                                   class="form-control <?= isset($errors['jam_selesai']) ? 'is-invalid' : '' ?>"
                                   id="jam_selesai"
                                   name="jam_selesai"
                                   value="<?= old('jam_selesai') ?>"
                                   required>
                            <?php if (isset($errors['jam_selesai'])): ?>
                                <div class="invalid-feedback"><?= esc($errors['jam_selesai']) ?></div>
                            <?php endif; ?>
                            <div class="form-text">Jam selesai harus lebih besar dari jam mulai.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pt-2 border-top">
                        <a href="<?= base_url('/admin/pelatihan') ?>" class="btn btn-outline-secondary px-4">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Pelatihan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
