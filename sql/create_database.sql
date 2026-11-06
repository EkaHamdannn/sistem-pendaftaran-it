-- ============================================================
-- SKRIP SQL BOOTSTRAP
-- Jalankan di MySQL CLI atau phpMyAdmin sebelum migrasi CI4.
-- Hanya membuat database; tabel akan dibuat oleh migrasi.
-- ============================================================

CREATE DATABASE IF NOT EXISTS `db_pelatihan_it`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- Verifikasi database berhasil dibuat
SHOW DATABASES LIKE 'db_pelatihan_it';
