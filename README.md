# SiPerpus - Sistem Informasi Perpustakaan Digital

Aplikasi manajemen perpustakaan modern berbasis **Laravel 11**, **Laravel Breeze API**, dan **Tailwind CSS**. Dibangun berdasarkan silabus Praktikum Pemrograman Web (Pertemuan 1–8).

[![PHP](https://img.shields.io/badge/PHP-8.3%20%7C%208.4-777BB4?logo=php&logoColor=white)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11%20%2F%2013-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Pest Tests](https://img.shields.io/badge/Tests-21%20Passed%20(100%25)-brightgreen?logo=pest)](https://pestphp.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

---

## Daftar Isi

- [Apa itu SiPerpus?](#apa-itu-siperpus)
- [Mengapa Menggunakan Arsitektur Ini?](#mengapa-menggunakan-arsitektur-ini)
- [Fitur Utama](#fitur-utama)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Panduan Instalasi Langkah-demi-Langkah](#panduan-instalasi-langkah-demi-langkah)
- [Kredensial Akun Demo](#kredensial-akun-demo)
- [Struktur Basis Data](#struktur-basis-data)
- [Daftar Route & Endpoint](#daftar-route--endpoint)
- [Alur Kerja Sistem (Workflow)](#alur-kerja-sistem-workflow)
- [Menjalankan Pengujian Otomatis](#menjalankan-pengujian-otomatis)
- [Penyelesaian Masalah Umum (Troubleshooting)](#penyelesaian-masalah-umum-troubleshooting)
- [Panduan Pengembangan Lanjutan](#panduan-pengembangan-lanjutan)
- [Lisensi](#lisensi)

---

## Apa itu SiPerpus?

**SiPerpus (Sistem Informasi Perpustakaan)** adalah aplikasi web untuk mengelola katalog buku, pencatatan anggota siswa, serta sirkulasi peminjaman dan pengembalian buku di lingkungan sekolah atau kampus.

Aplikasi ini menyederhanakan tugas petugas perpustakaan:
- Stok buku otomatis berkurang ketika ada peminjaman baru.
- Stok buku otomatis bertambah kembali ketika buku dikembalikan.
- Petugas dapat melihat anggota yang masih memiliki pinjaman aktif sebelum menghapus data.
- Pencarian buku berlangsung instan di sisi klien dengan indikator visual ketersediaan stok.

---

## Mengapa Menggunakan Arsitektur Ini?

Dalam praktikum web atau proyek perkuliahan, aplikasi sering kali menghadapi dilema:
1. **Full API terpisah (Next.js/React + Laravel API)** sering membutuhkan dua server yang berjalan bersamaan, konfigurasi CORS rumit, dan rentan kendala di komputer penguji/dosen.
2. **Blade konvensional murni** sering kali terlihat kuno jika tidak diintegrasikan dengan sistem autentikasi modern.

**Solusi SiPerpus:**
- Memanfaatkan **Laravel Breeze API** dan **Laravel Sanctum** untuk sistem autentikasi berbasis cookie stateful dan token JSON.
- Menggunakan antarmuka **Blade + Tailwind CSS + Alpine.js** yang terintegrasi langsung di dalam Laravel. 
- Hasilnya: Tampilan cepat, modern, tidak memerlukan kompilasi Node terpisah saat didemokan, dan seluruh backend siap dikonsumsi baik via browser maupun API eksternal (Postman/Mobile).

---

## Fitur Utama

### 1. Autentikasi Petugas (Laravel Breeze API & Sanctum)
- Halaman Login & Registrasi modern dengan validasi feedback instan.
- Proteksi route berbasis session `auth`. Pengguna yang belum masuk akan dialihkan otomatis ke halaman login.
- Tombol **Gunakan Akun Demo** untuk mengisi formulir login dalam 1 klik.
- Tombol keluar (*Logout*) yang terhubung asinkron ke endpoint `POST /logout`.

### 2. Katalog & Inventaris Data Buku (CRUD Lengkap)
- Menampilkan seluruh koleksi buku dengan 4 kartu statistik: *Total Judul*, *Total Stok*, *Buku Siap Pinjam*, dan *Stok Kosong*.
- Pencarian instan (*live search*) multi-kolom berdasarkan judul, pengarang, penerbit, maupun kode buku.
- Filter ketersediaan buku (*Semua*, *Tersedia*, *Habis*).
- Form tambah buku baru dengan validasi keunikan kode buku.
- Form edit buku dengan pengisian otomatis nilai lama (*old values*).
- Tombol hapus yang dilengkapi dialog konfirmasi JavaScript.

### 3. Manajemen Anggota Siswa (CRUD)
- Pencatatan NIS (Nomor Induk Siswa), Nama Lengkap, dan Kelas/Jurusan.
- Indikator jumlah pinjaman aktif yang sedang dibawa oleh masing-masing siswa.
- Pencegahan penghapusan anggota yang masih memiliki pinjaman buku aktif.

### 4. Sirkulasi Peminjaman & Pengembalian Buku
- Form transaksi pinjam dengan dropdown siswa dan dropdown buku yang stoknya masih tersedia.
- **Pengurangan Stok Otomatis:** Saat transaksi pinjam disimpan, stok buku fisik berkurang 1.
- **Aksi Pengembalian 1-Klik:** Petugas cukup menekan tombol "Kembalikan", sistem akan mencatat tanggal pengembalian hari ini, mengubah status menjadi `kembali`, dan otomatis mengembalikan 1 stok fisik buku.
- Filter transaksi berdasarkan status (*Semua Transaksi*, *Sedang Dipinjam*, *Sudah Dikembalikan*).

---

## Teknologi yang Digunakan

| Komponen | Teknologi | Keterangan |
|---|---|---|
| **Backend Framework** | Laravel 11 / 13 | PHP 8.3 / PHP 8.4 |
| **Autentikasi** | Laravel Breeze API | Laravel Sanctum stateful cookies |
| **Basis Data** | MySQL / MariaDB | Melalui XAMPP |
| **Styling & UI** | Tailwind CSS CDN | Tipografi Google Font *Plus Jakarta Sans* |
| **Interaktivitas UI** | Alpine.js | Live search, filter status, mobile menu |
| **Test Runner** | Pest PHP 5 | 21 Feature & Unit tests |
| **Code Formatter** | Laravel Pint | Standar PSR-12 / Laravel |

---

## Persyaratan Sistem

Pastikan komputer Anda telah terinstal:
- **PHP** versi 8.2 atau lebih baru (`php -v`)
- **Composer** versi 2.x (`composer -v`)
- **XAMPP** (dengan service Apache dan MySQL aktif)
- Web Browser modern (Google Chrome, Microsoft Edge, Mozilla Firefox)

---

## Panduan Instalasi Langkah-demi-Langkah

Ikuti langkah-langkah berikut untuk menjalankan proyek dari awal di komputer lokal:

### 1. Buka Terminal dan Masuk ke Direktori Proyek
```bash
cd "d:\Project\PW Project\perpustakaan"
```

### 2. Nyalakan XAMPP dan Buat Database
1. Buka **XAMPP Control Panel**.
2. Klik tombol **Start** pada modul **Apache** dan **MySQL**.
3. Buka browser dan akses phpMyAdmin di: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
4. Buat basis data baru bernama: **`db_perpus`** (collation `utf8mb4_unicode_ci`).

### 3. Konfigurasi File Lingkungan (.env)
Pastikan file `.env` di root direktori proyek memiliki konfigurasi database yang sesuai:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_perpus
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Install Dependensi Composer
```bash
composer install
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi Database
Eksekusi file migrasi untuk membuat tabel `buku`, `anggota`, `peminjaman`, `users`, dan `sessions`:
```bash
php artisan migrate
```

Output yang diharapkan:
```text
INFO  Running migrations.
0001_01_01_000000_create_users_table ........................... DONE
0001_01_01_000001_create_cache_table ........................... DONE
0001_01_01_000002_create_jobs_table ............................ DONE
2026_09_09_072520_create_buku_table ............................ DONE
2026_09_09_072529_create_anggota_table ......................... DONE
2026_09_09_072535_create_peminjaman_table ...................... DONE
2026_09_09_083117_create_personal_access_tokens_table .......... DONE
```

### 7. Isi Data Awal (Seeding)
Isi database dengan akun admin demo, koleksi buku awal, data siswa, dan contoh transaksi peminjaman:
```bash
php artisan db:seed
```

Output yang diharapkan:
```text
INFO  Seeding database.
```

### 8. Jalankan Server Pengembangan Lokal
```bash
php artisan serve
```

Buka browser Anda dan akses:
👉 **[http://localhost:8000](http://localhost:8000)** atau **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## Kredensial Akun Demo

Gunakan akun petugas berikut untuk masuk ke dalam sistem:

| Peran | Email | Kata Sandi |
|---|---|---|
| **Petugas Perpustakaan** | `admin@perpustakaan.test` | `password` |

> 💡 **Tips Pengujian:** Pada halaman login, Anda dapat langsung mengklik tombol **"Gunakan Akun"** untuk mengisi email dan password secara otomatis.

---

## Struktur Basis Data

### 1. Tabel `buku`
| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `BIGINT UNSIGNED` | Primary Key (Auto Increment) |
| `kode_buku` | `VARCHAR(20)` | Unique, kode identitas buku (contoh: `BK-001`) |
| `judul` | `VARCHAR(150)` | Judul lengkap buku |
| `pengarang` | `VARCHAR(100)` | Penulis buku |
| `penerbit` | `VARCHAR(100)` | Nama penerbit |
| `tahun_terbit` | `YEAR` | Tahun publikasi buku |
| `stok` | `INT` | Sisa eksemplar fisik yang tersedia (default: 0) |
| `created_at`, `updated_at` | `TIMESTAMP` | Waktu pencatatan |

### 2. Tabel `anggota`
| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `BIGINT UNSIGNED` | Primary Key (Auto Increment) |
| `nis` | `VARCHAR(20)` | Unique, Nomor Induk Siswa |
| `nama` | `VARCHAR(100)` | Nama lengkap siswa |
| `kelas` | `VARCHAR(20)` | Tingkat dan kelas (contoh: `XII RPL 1`) |
| `created_at`, `updated_at` | `TIMESTAMP` | Waktu pendaftaran |

### 3. Tabel `peminjaman`
| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `BIGINT UNSIGNED` | Primary Key (Auto Increment) |
| `buku_id` | `BIGINT UNSIGNED` | Foreign Key ke tabel `buku.id` (Cascade) |
| `anggota_id` | `BIGINT UNSIGNED` | Foreign Key ke tabel `anggota.id` (Cascade) |
| `tanggal_pinjam` | `DATE` | Tanggal awal peminjaman |
| `tanggal_kembali` | `DATE` (Nullable) | Tanggal buku dikembalikan |
| `status` | `ENUM('dipinjam', 'kembali')` | Status pinjaman (default: `dipinjam`) |
| `created_at`, `updated_at` | `TIMESTAMP` | Waktu transaksi |

---

## Daftar Route & Endpoint

Aplikasi ini mendefinisikan rute web dan API yang bersih:

| Method | URI | Nama Route | Middleware | Deskripsi |
|---|---|---|---|---|
| `GET` | `/` | - | - | Pengalihan awal ke `buku.index` |
| `GET` | `/login` | `login` | `guest` | Tampilan formulir login petugas |
| `POST` | `/login` | `login` | `guest` | Autentikasi akun via Breeze API |
| `GET` | `/register` | `register.view` | `guest` | Tampilan registrasi petugas baru |
| `POST` | `/register` | `register` | `guest` | Proses pembuatan akun petugas baru |
| `POST` | `/logout` | `logout` | `auth` | Menghancurkan sesi aktif (Logout) |
| `GET` | `/buku` | `buku.index` | `auth` | Katalog & daftar semua buku |
| `GET` | `/buku/create` | `buku.create` | `auth` | Formulir tambah buku baru |
| `POST` | `/buku` | `buku.store` | `auth` | Menyimpan buku baru ke database |
| `GET` | `/buku/{buku}/edit` | `buku.edit` | `auth` | Formulir ubah data buku |
| `PUT` | `/buku/{buku}` | `buku.update` | `auth` | Menyimpan perubahan data buku |
| `DELETE` | `/buku/{buku}` | `buku.destroy` | `auth` | Menghapus data buku |
| `GET` | `/anggota` | `anggota.index` | `auth` | Daftar anggota perpustakaan |
| `GET` | `/anggota/create` | `anggota.create` | `auth` | Formulir tambah anggota |
| `POST` | `/anggota` | `anggota.store` | `auth` | Menyimpan data anggota baru |
| `GET` | `/anggota/{anggota}/edit`| `anggota.edit` | `auth` | Formulir edit anggota |
| `PUT` | `/anggota/{anggota}` | `anggota.update` | `auth` | Memperbarui data anggota |
| `DELETE` | `/anggota/{anggota}` | `anggota.destroy` | `auth` | Menghapus data anggota |
| `GET` | `/peminjaman` | `peminjaman.index` | `auth` | Daftar riwayat peminjaman buku |
| `GET` | `/peminjaman/create` | `peminjaman.create` | `auth` | Formulir transaksi peminjaman |
| `POST` | `/peminjaman` | `peminjaman.store` | `auth` | Catat pinjam & potong stok buku |
| `POST` | `/peminjaman/{id}/kembalikan` | `peminjaman.kembalikan` | `auth` | Pengembalian buku & tambah stok |
| `DELETE` | `/peminjaman/{id}` | `peminjaman.destroy` | `auth` | Menghapus riwayat transaksi |

---

## Alur Kerja Sistem (Workflow)

```
[Pengunjung Web]
       │
       ▼
  / (Root URI)
       │
       ▼
Sudah Login? ── Tidak ──► [Halaman /login] ── Auth Breeze API ──► Sesi Dibuat
       │                                                              │
       ▼ Ya                                                           ▼
[Katalog Buku (/buku)] ◄──────────────────────────────────────────────┘
  ├── Tambah Buku ──► Validasi Form ──► Simpan ke DB
  ├── Edit Buku ────► Form Nilai Lama ──► Update Data
  ├── Hapus Buku ───► Cek Pinjaman Aktif ──► Hapus / Batalkan
  │
  ├── [Data Anggota (/anggota)] ──► Kelola NIS & Kelas Siswa
  │
  └── [Peminjaman (/peminjaman)]
        ├── Form Pinjam ──► Pilih Siswa & Buku ──► Stok Berkurang 1
        └── Tombol Kembalikan ──────────────────► Stok Bertambah 1 & Status 'kembali'
```

---

## Menjalankan Pengujian Otomatis

Proyek ini telah diverifikasi menggunakan suite pengujian otomatis **Pest PHP** dengan total 21 pengujian fitur:

Jalankan perintah berikut di terminal:
```bash
vendor/bin/pest
```

Output pengujian:
```text
   PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Feature\ExampleTest
  ✓ example

   PASS  Tests\Feature\Auth\AuthenticationTest
  ✓ users can authenticate using the login screen
  ✓ users can not authenticate with invalid password
  ✓ users can logout

   PASS  Tests\Feature\Auth\EmailVerificationTest
  ✓ email verification screen can be rendered
  ✓ email can be verified
  ✓ email is not verified with invalid hash

   PASS  Tests\Feature\Auth\PasswordResetTest
  ✓ reset password link screen can be rendered
  ✓ reset password link can be requested
  ✓ reset password screen can be rendered
  ✓ password can be reset with valid token

   PASS  Tests\Feature\Auth\RegistrationTest
  ✓ new users can register

   PASS  Tests\Feature\BukuFrontendTest
  ✓ guest is redirected from / to /buku then to /login
  ✓ login and register pages render successfully with status 200
  ✓ authenticated user can view buku index and see books
  ✓ authenticated user can view create form
  ✓ authenticated user can view edit form
  ✓ authenticated user can store a new book
  ✓ authenticated user can update a book
  ✓ authenticated user can delete a book

   PASS  Tests\Feature\SirkulasiPerpustakaanTest
  ✓ authenticated user can view anggota list
  ✓ authenticated user can borrow a book and decrement stock
  ✓ authenticated user can return a borrowed book and increment stock

  Tests:    21 passed (62 assertions)
  Duration: 2.52s
```

---

## Penyelesaian Masalah Umum (Troubleshooting)

### 1. `Database file at path [db_perpus] does not exist (Connection: sqlite)`
- **Penyebab:** Server dev (`php artisan serve` / `composer run dev`) dinyalakan sebelum konfigurasi `.env` diubah ke MySQL, sehingga server PHP masih memegang state lama.
- **Solusi:** Hentikan dev server di terminal dengan `Ctrl + C`, jalankan `php artisan optimize:clear`, lalu jalankan kembali `php artisan serve`.

### 2. `SQLSTATE[HY000] [2002] Connection refused`
- **Penyebab:** Service MySQL di XAMPP belum menyala.
- **Solusi:** Buka XAMPP Control Panel dan pastikan modul MySQL dalam status **Running** (indikator hijau).

### 3. Halaman Tampil Tanpa Style (CSS Berantakan)
- **Penyebab:** Koneksi internet terputus saat memuat Tailwind CSS CDN.
- **Solusi:** Pastikan perangkat Anda terhubung ke internet saat membuka aplikasi di browser.

---

## Panduan Pengembangan Lanjutan

Untuk panduan arsitektur internal dan konvensi penulisan kode:
- **[AGENTS.md](./AGENTS.md)** — Panduan konvensi framework Laravel Boost, Pest testing, dan aturan ekosistem.
- **[CLAUDE.md](./CLAUDE.md)** — Panduan prasyarat lingkungan kerja pengembang.

---

## Lisensi

Proyek ini dirilis di bawah lisensi [MIT License](LICENSE). Bebas digunakan dan dikembangkan untuk keperluan edukasi dan pembelajaran.
