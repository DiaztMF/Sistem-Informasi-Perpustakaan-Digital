<?php

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;

test('authenticated user can view anggota list', function () {
    $user = User::factory()->create();
    $anggota = Anggota::create([
        'nis' => 'NIS999',
        'nama' => 'Santoso Test',
        'kelas' => 'XII RPL 1',
    ]);

    $response = $this->actingAs($user)->get('/anggota');

    $response->assertOk();
    $response->assertSee('Santoso Test');
    $response->assertSee('NIS999');
});

test('authenticated user can borrow a book and decrement stock', function () {
    $user = User::factory()->create();
    $buku = Buku::factory()->create(['stok' => 5]);
    $anggota = Anggota::create([
        'nis' => 'NIS888',
        'nama' => 'Budi Siswa',
        'kelas' => 'XI TKJ 2',
    ]);

    $response = $this->actingAs($user)->post('/peminjaman', [
        'buku_id' => $buku->id,
        'anggota_id' => $anggota->id,
        'tanggal_pinjam' => now()->toDateString(),
    ]);

    $response->assertRedirect(route('peminjaman.index'));

    expect($buku->fresh()->stok)->toBe(4);

    $this->assertDatabaseHas('peminjaman', [
        'buku_id' => $buku->id,
        'anggota_id' => $anggota->id,
        'status' => 'dipinjam',
    ]);
});

test('authenticated user can return a borrowed book and increment stock', function () {
    $user = User::factory()->create();
    $buku = Buku::factory()->create(['stok' => 2]);
    $anggota = Anggota::create([
        'nis' => 'NIS777',
        'nama' => 'Citra Lestari',
        'kelas' => 'X RPL 1',
    ]);

    $peminjaman = Peminjaman::create([
        'buku_id' => $buku->id,
        'anggota_id' => $anggota->id,
        'tanggal_pinjam' => now()->subDays(3)->toDateString(),
        'status' => 'dipinjam',
    ]);

    $response = $this->actingAs($user)->post("/peminjaman/{$peminjaman->id}/kembalikan");

    $response->assertRedirect(route('peminjaman.index'));

    expect($peminjaman->fresh()->stok ?? null)->toBeNull();
    expect($buku->fresh()->stok)->toBe(3);
    expect($peminjaman->fresh()->status)->toBe('kembali');
    expect($peminjaman->fresh()->tanggal_kembali)->not->toBeNull();
});

test('authenticated user can view peminjaman index and see records', function () {
    $user = User::factory()->create();
    $buku = Buku::factory()->create(['stok' => 5, 'judul' => 'Buku Uji Coba']);
    $anggota = Anggota::create([
        'nis' => 'NIS123',
        'nama' => 'Siswa Penguji',
        'kelas' => 'XII RPL 1',
    ]);

    Peminjaman::create([
        'buku_id' => $buku->id,
        'anggota_id' => $anggota->id,
        'tanggal_pinjam' => '2026-09-01',
        'tanggal_kembali' => '2026-09-05',
        'status' => 'kembali',
    ]);

    $response = $this->actingAs($user)->get('/peminjaman');

    $response->assertOk();
    $response->assertSee('Buku Uji Coba');
    $response->assertSee('Siswa Penguji');
});
