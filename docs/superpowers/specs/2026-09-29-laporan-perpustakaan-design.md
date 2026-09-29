# Laporan Pinjaman Aktif + Poles — Design Spec

> Status: approved by user (2026-09-29). Next: writing-plans → implementation.

**Goal:** Tambah halaman laporan pinjaman aktif (read-only, siap cetak) dan poles dua fitur existing tanpa migrasi database.

**Constraints:** Tanpa kolom DB baru (tidak ada `tanggal_jatuh_tempo`, jadi tidak ada logika terlambat/denda). Ikuti gaya Tailwind + Blade existing. Semua route di dalam grup `auth`.

---

## 1. Laporan (baru)

- Route `GET /laporan` → `LaporanController@index`, nama `laporan.index`, dalam grup `Route::middleware(['auth'])` di `routes/web.php`.
- Link nav "Laporan" di sidebar setelah "Peminjaman".
- Query: `Peminjaman::with(['buku', 'anggota'])->where('status', 'dipinjam')->latest()->get()` — tanpa paginasi (laporan dibaca utuh dan dicetak).
- Statistik dihitung di controller: `total_dipinjam` (count), `total_peminjam_unik` (`unique('anggota_id')->count()`), `rata_rata_hari` (avg `diffInDays(tanggal_pinjam, now)`, 1 desimal), `durasi_terlama_hari` + judul bukunya (max).
- View `laporan/index.blade.php`: 4 kartu statistik + tabel (buku, peminjam + NIS/kelas, tgl pinjam, lama hari) + tombol Cetak (`window.print()`). Elemen non-tabel (nav, sidebar, tombol) diberi `print:hidden`.

## 2. Poles existing

1. **Badge lama-dipinjam** di `peminjaman/index.blade.php`: kolom kecil "X hari" via `diffInDays` — murni tampilan.
2. **Search buku satu sumber kebenaran**: hapus filter Alpine client-side (yang cuma mencakup 8 baris halaman aktif) di `buku/index.blade.php`, pertahankan server-side `?search=`. Menghilangkan bug UX hasil setengah data.

## 3. Testing

- Pest test baru `tests/Feature/LaporanTest.php`: guest redirect ke login; user auth melihat statistik benar (2 aktif + 1 kembali → tampil 2); hanya status `dipinjam` yang tampil.
- Verifikasi: `php artisan test` hijau, `php artisan route:list --path=laporan` menampilkan route.
