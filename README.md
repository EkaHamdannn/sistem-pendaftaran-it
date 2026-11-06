# Sistem Pendaftaran Pelatihan IT

Aplikasi berbasis web untuk pendaftaran pelatihan teknologi informasi, manajemen kuota pelatihan secara realtime, serta pembatalan pendaftaran peserta. Dibuat menggunakan framework **CodeIgniter 4**, basis data **MySQL**, dan antarmuka **Bootstrap 5**.

Proyek ini disusun untuk memenuhi tugas **Ujian Tengah Semester (UTS) Praktik Mata Kuliah Testing dan Implementasi Sistem**, Program Studi Sistem Informasi, Universitas PGRI Madiun.

---

## Identitas Pengembang
- **Nama**: Eka Hamdan Rahmawan
- **NIM**: 2305102003
- **No. Presensi**: 01
- **Program Studi**: Sistem Informasi
- **Perguruan Tinggi**: Universitas PGRI Madiun

---

## Teknologi & Tools
| Komponen | Spesifikasi / Tools |
| :--- | :--- |
| **Bahasa Pemrograman** | PHP 8.2 |
| **Framework Web** | CodeIgniter 4 (Arsitektur MVC) |
| **Basis Data** | MySQL (XAMPP 8.2), dikelola dengan phpMyAdmin |
| **Antarmuka / CSS** | Bootstrap 5, Bootstrap Icons |
| **Development Server** | PHP Spark Server (`php spark serve`, port 8080) |
| **Editor / AI Assistant**| Google Antigravity |
| **Version Control** | Git & GitHub |

---

## Fitur Utama Aplikasi
1. **Pendaftaran Peserta**:
   - Memilih jadwal pelatihan yang masih memiliki kuota.
   - Validasi data input: nama (hanya huruf & spasi, min 3 karakter), email (format valid), dan nomor HP (angka 10–13 digit).
   - Pengurangan kuota pelatihan secara otomatis & atomik dalam transaksi database.
   - Penerbitan nomor tiket unik (format: `#00001`).
   - Pencegahan pendaftaran ganda (email yang sama pada pelatihan aktif yang sama).

2. **Pengelolaan Pelatihan (Admin)**:
   - CRUD data pelatihan (Nama, Kategori, Tanggal, Jam Mulai - Jam Selesai, Lokasi, Kuota).
   - Validasi tanggal tidak boleh masa lalu, jam selesai harus lebih besar dari jam mulai, dan kuota 1–100.
   - Proteksi integritas relasional database (*foreign key restrict*) saat pelatihan memiliki riwayat pendaftar.

3. **Pengelolaan Pendaftar & Pembatalan (Admin)**:
   - Melihat daftar peserta pelatihan terdaftar.
   - Fitur pembatalan pendaftaran (*soft cancellation*) dengan pengembalian kuota pelatihan secara otomatis (+1).

4. **Keamanan Sistem**:
   - Autentikasi Admin dilindungi hash kata sandi BCrypt.
   - *Filter session* untuk seluruh endpoint dashboard admin (`/admin/*`).
   - *CSRF Protection* aktif pada seluruh form pendaftaran dan modul admin.
   - *Rate Limiting / Throttler* pada form login (maksimal 5 percobaan gagal per 60 detik per alamat IP).
   - *Zero-Knowledge UI* (tombol masuk admin tidak diekspos pada navbar umum).

---

## Kredensial Akun Admin
Untuk mengakses panel admin, navigasikan browser ke URL login:
- **URL Login**: `http://localhost:8080/login`
- **Username**: `admin`
- **Password**: `Admin@123`

---

## Langkah Menjalankan Aplikasi

1. **Clone Repository & Nyalakan Layanan Database**
   ```bash
   git clone https://github.com/<username>/sistem-pendaftaran-it.git
   cd sistem-pendaftaran-it
   ```
   Pastikan service **Apache** dan **MySQL** pada control panel XAMPP sudah berjalan (*running*).

2. **Konfigurasi Environment**
   Salin berkas `env.example` menjadi `.env`:
   ```bash
   cp env.example .env
   # Atau pada Windows PowerShell:
   copy env.example .env
   ```
   Pastikan pengaturan database di dalam file `.env` sudah sesuai:
   ```ini
   database.default.hostname = localhost
   database.default.database = db_pelatihan_it
   database.default.username = root
   database.default.password =
   database.default.DBDriver = MySQLi
   database.default.port     = 3306
   ```

3. **Inisialisasi Database**
   Buat database baru bernama `db_pelatihan_it` melalui phpMyAdmin (`http://localhost/phpmyadmin`), lalu:
   - **Opsi A (CLI Spark Migration & Seeder)**:
     ```bash
     php spark migrate
     php spark db:seed AdminSeeder
     ```
   - **Opsi B (Impor SQL Manual)**:
     Impor berkas `sql/full_schema.sql` langsung melalui menu **Import** pada phpMyAdmin di database `db_pelatihan_it`.

4. **Jalankan Server Lokal**
   Jalankan server pengembangan bawaan CodeIgniter 4:
   ```bash
   php spark serve
   ```
   *(Catatan: Jika `php` belum terdaftar di Environment PATH Windows, gunakan path binary XAMPP: `C:\xampp\php\php.exe spark serve`)*

5. **Akses Aplikasi**
   - **Halaman Utama Peserta**: [http://localhost:8080](http://localhost:8080)
   - **Halaman Login Admin**: [http://localhost:8080/login](http://localhost:8080/login) (Gunakan kredensial pada bagian di atas).

---

## Struktur Direktori Utama
```text
sistem-pendaftaran-it/
├── app/
│   ├── Config/
│   │   ├── Filters.php          # Konfigurasi filter autentikasi & CSRF
│   │   └── Routes.php           # Pemetaan 16 rute aplikasi
│   ├── Controllers/
│   │   ├── AuthController.php   # Autentikasi admin & rate limiter (throttler)
│   │   ├── PelatihanController.php # Katalog pelatihan publik
│   │   ├── PendaftaranController.php # Form pendaftaran peserta & transaksi kuota
│   │   └── Admin/
│   │       ├── DashboardController.php # Ringkasan statistik admin
│   │       ├── PelatihanController.php # CRUD pelatihan & validasi kuota
│   │       └── PesertaController.php   # Daftar peserta & pembatalan kuota
│   ├── Database/
│   │   ├── Migrations/          # Skema migrasi 3 tabel (admin, pelatihan, pendaftaran)
│   │   └── Seeds/               # AdminSeeder dengan hash BCrypt
│   ├── Filters/
│   │   └── AuthFilter.php       # Proteksi hak akses session admin
│   ├── Models/
│   │   ├── AdminModel.php
│   │   ├── PelatihanModel.php
│   │   └── PendaftaranModel.php
│   └── Views/
│       ├── layouts/             # Template layout admin & publik (Bootstrap 5)
│       ├── public/              # View katalog, form daftar, tiket sukses
│       ├── auth/                # View login admin
│       └── admin/               # View dashboard, CRUD pelatihan, kelola peserta
├── public/                      # Entrypoint web server (index.php, CSS, assets)
├── sql/
│   ├── create_database.sql      # DDL pembuatan database
│   └── full_schema.sql          # DDL lengkap beserta seeder default
├── system/                      # Core engine CodeIgniter 4
├── env.example                  # Template konfigurasi environment
├── spark                        # CodeIgniter 4 CLI tool
└── README.md                    # Dokumentasi proyek
```
