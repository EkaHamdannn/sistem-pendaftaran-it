-- ============================================================
-- SKRIP SQL LENGKAP (ALTERNATIF TANPA CI4 MIGRATION)
-- Jalankan skrip ini jika ingin membuat tabel secara langsung
-- di MySQL tanpa melalui spark migrate.
-- ============================================================

CREATE DATABASE IF NOT EXISTS `db_pelatihan_it`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `db_pelatihan_it`;

-- ============================================================
-- TABEL: admin
-- ============================================================
CREATE TABLE IF NOT EXISTS `admin` (
  `id`         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(50)      NOT NULL,
  `password`   VARCHAR(255)     NOT NULL,
  `created_at` TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  CONSTRAINT `uq_admin_username` UNIQUE (`username`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABEL: pelatihan
-- ============================================================
CREATE TABLE IF NOT EXISTS `pelatihan` (
  `id`          INT UNSIGNED                      NOT NULL AUTO_INCREMENT,
  `judul`       VARCHAR(150)                      NOT NULL,
  `instruktur`  VARCHAR(100)                      NOT NULL,
  `tanggal`     DATE                              NOT NULL,
  `jam_mulai`   TIME                              NOT NULL,
  `jam_selesai` TIME                              NOT NULL,
  `kuota`       TINYINT UNSIGNED                  NOT NULL,
  `sisa_kuota`  TINYINT UNSIGNED                  NOT NULL,
  `status`      ENUM('Tersedia','Penuh')          NOT NULL DEFAULT 'Tersedia',
  `created_at`  TIMESTAMP                         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP                         NOT NULL DEFAULT CURRENT_TIMESTAMP
                                                  ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  CONSTRAINT `chk_pelatihan_jam`
    CHECK (`jam_selesai` > `jam_mulai`),
  CONSTRAINT `chk_pelatihan_kuota`
    CHECK (`kuota` BETWEEN 1 AND 100),
  CONSTRAINT `chk_pelatihan_sisa_kuota`
    CHECK (`sisa_kuota` >= 0 AND `sisa_kuota` <= `kuota`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABEL: pendaftaran
-- ============================================================
CREATE TABLE IF NOT EXISTS `pendaftaran` (
  `id`           INT UNSIGNED                        NOT NULL AUTO_INCREMENT,
  `pelatihan_id` INT UNSIGNED                        NOT NULL,
  `nama`         VARCHAR(50)                         NOT NULL,
  `email`        VARCHAR(100)                        NOT NULL,
  `nomor_hp`     VARCHAR(13)                         NOT NULL,
  `status`       ENUM('Terdaftar','Dibatalkan')      NOT NULL DEFAULT 'Terdaftar',
  `created_at`   TIMESTAMP                           NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`   TIMESTAMP                           NOT NULL DEFAULT CURRENT_TIMESTAMP
                                                     ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),

  CONSTRAINT `fk_pendaftaran_pelatihan`
    FOREIGN KEY (`pelatihan_id`)
    REFERENCES `pelatihan`(`id`)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,

  INDEX `idx_pendaftaran_pelatihan` (`pelatihan_id`),
  INDEX `idx_pendaftaran_email`     (`email`),
  INDEX `idx_pendaftaran_status`    (`status`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEEDER: satu akun admin (password: Admin@123)
-- Hash di bawah dihasilkan dari password_hash('Admin@123', PASSWORD_BCRYPT)
-- ============================================================
INSERT INTO `admin` (`username`, `password`) VALUES (
  'admin',
  '$2y$10$jkcc4IypyslwNiyOygVsVe.0TFPijKYWxDuFONsAINN6mfk8eqR1a'
) ON DUPLICATE KEY UPDATE `password` = VALUES(`password`);
