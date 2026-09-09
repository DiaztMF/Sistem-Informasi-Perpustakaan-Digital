<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin / Petugas Perpustakaan
        $admin = User::firstOrCreate(
            ['email' => 'admin@perpustakaan.test'],
            [
                'name' => 'Petugas Perpustakaan',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Data Buku Perpustakaan
        $bukuData = [
            [
                'kode_buku' => 'BK-001',
                'judul' => 'Pemrograman Web Modern dengan Laravel & Vue',
                'pengarang' => 'Rahmad Hidayat',
                'penerbit' => 'Informatika Bandung',
                'tahun_terbit' => 2024,
                'stok' => 8,
            ],
            [
                'kode_buku' => 'BK-002',
                'judul' => 'Laskar Pelangi',
                'pengarang' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'tahun_terbit' => 2005,
                'stok' => 5,
            ],
            [
                'kode_buku' => 'BK-003',
                'judul' => 'Bumi Manusia',
                'pengarang' => 'Pramoedya Ananta Toer',
                'penerbit' => 'Hasta Mitra',
                'tahun_terbit' => 1980,
                'stok' => 4,
            ],
            [
                'kode_buku' => 'BK-004',
                'judul' => 'Filosofi Teras: Panduan Stoikisme',
                'pengarang' => 'Henry Manampiring',
                'penerbit' => 'Kompas Gramedia',
                'tahun_terbit' => 2018,
                'stok' => 6,
            ],
            [
                'kode_buku' => 'BK-005',
                'judul' => 'Clean Architecture & Best Coding Practices',
                'pengarang' => 'Robert C. Martin',
                'penerbit' => 'Prentice Hall',
                'tahun_terbit' => 2017,
                'stok' => 3,
            ],
            [
                'kode_buku' => 'BK-006',
                'judul' => 'Basis Data Relasional & Perancangan Sistem',
                'pengarang' => 'Fathansyah',
                'penerbit' => 'Informatika',
                'tahun_terbit' => 2021,
                'stok' => 7,
            ],
        ];

        $bukuCreated = [];
        foreach ($bukuData as $item) {
            $bukuCreated[] = Buku::firstOrCreate(['kode_buku' => $item['kode_buku']], $item);
        }

        // 3. Data Anggota Siswa
        $anggotaData = [
            [
                'nis' => 'NIS202401',
                'nama' => 'Ahmad Fauzi',
                'kelas' => 'XII RPL 1',
            ],
            [
                'nis' => 'NIS202402',
                'nama' => 'Siti Nurhaliza',
                'kelas' => 'XII RPL 2',
            ],
            [
                'nis' => 'NIS202403',
                'nama' => 'Budi Santoso',
                'kelas' => 'XI TKJ 1',
            ],
            [
                'nis' => 'NIS202404',
                'nama' => 'Dewi Anggraini',
                'kelas' => 'X DKV 2',
            ],
        ];

        $anggotaCreated = [];
        foreach ($anggotaData as $item) {
            $anggotaCreated[] = Anggota::firstOrCreate(['nis' => $item['nis']], $item);
        }

        // 4. Data Peminjaman Awal (Contoh)
        if (Peminjaman::count() === 0 && count($bukuCreated) >= 3 && count($anggotaCreated) >= 3) {
            // Pinjaman 1: Masih dipinjam
            Peminjaman::create([
                'buku_id' => $bukuCreated[0]->id,
                'anggota_id' => $anggotaCreated[0]->id,
                'tanggal_pinjam' => now()->subDays(3)->toDateString(),
                'tanggal_kembali' => null,
                'status' => 'dipinjam',
            ]);

            // Pinjaman 2: Masih dipinjam
            Peminjaman::create([
                'buku_id' => $bukuCreated[3]->id,
                'anggota_id' => $anggotaCreated[1]->id,
                'tanggal_pinjam' => now()->subDays(5)->toDateString(),
                'tanggal_kembali' => null,
                'status' => 'dipinjam',
            ]);

            // Pinjaman 3: Sudah dikembalikan
            Peminjaman::create([
                'buku_id' => $bukuCreated[1]->id,
                'anggota_id' => $anggotaCreated[2]->id,
                'tanggal_pinjam' => now()->subDays(10)->toDateString(),
                'tanggal_kembali' => now()->subDays(2)->toDateString(),
                'status' => 'kembali',
            ]);
        }
    }
}
