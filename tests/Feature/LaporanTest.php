<?php

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;

test('guest is redirected from laporan to login', function () {
    $response = $this->get('/laporan');

    $response->assertRedirect(route('login'));
});

test('authenticated user can view laporan with correct stats', function () {
    $user = User::factory()->create();
    $bukuAktif = Buku::factory()->create(['judul' => 'Buku Pinjaman Aktif']);
    $bukuKembali = Buku::factory()->create(['judul' => 'Buku Sudah Kembali']);
    $anggota = Anggota::create([
        'nis' => 'NISLAP1',
        'nama' => 'Siswa Laporan',
        'kelas' => 'XII RPL 1',
    ]);

    Peminjaman::create([
        'buku_id' => $bukuAktif->id,
        'anggota_id' => $anggota->id,
        'tanggal_pinjam' => now()->subDays(4)->toDateString(),
        'status' => 'dipinjam',
    ]);
    Peminjaman::create([
        'buku_id' => $bukuKembali->id,
        'anggota_id' => $anggota->id,
        'tanggal_pinjam' => '2026-09-01',
        'tanggal_kembali' => '2026-09-05',
        'status' => 'kembali',
    ]);

    $response = $this->actingAs($user)->get('/laporan');

    $response->assertOk();
    $response->assertSee('Buku Pinjaman Aktif');
    $response->assertSee('Siswa Laporan');
    $response->assertDontSee('Buku Sudah Kembali');
    $response->assertViewHas('total_dipinjam', 1);
    $response->assertViewHas('total_peminjam_unik', 1);
    $response->assertViewHas('durasi_terlama_hari', 4);
    $response->assertViewHas('buku_terlama_judul', 'Buku Pinjaman Aktif');
});
