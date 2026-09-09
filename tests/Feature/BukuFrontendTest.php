<?php

use App\Models\Buku;
use App\Models\User;

test('guest is redirected from / to /buku then to /login', function () {
    $response = $this->get('/');
    $response->assertRedirect(route('buku.index'));

    $response2 = $this->get('/buku');
    $response2->assertRedirect(route('login'));
});

test('login and register pages render successfully with status 200', function () {
    $response = $this->get('/login');
    $response->assertOk();
    $response->assertSee('SiPerpus Digital');
    $response->assertSee('Selamat Datang Kembali');

    $registerResponse = $this->get('/register');
    $registerResponse->assertOk();
    $registerResponse->assertSee('Daftar Petugas Baru');
});

test('authenticated user can view buku index and see books', function () {
    $user = User::factory()->create();
    $buku = Buku::factory()->create([
        'kode_buku' => 'BK-TEST-1',
        'judul' => 'Buku Belajar Laravel',
        'pengarang' => 'Penulis Hebat',
        'penerbit' => 'Penerbit Utama',
        'tahun_terbit' => 2024,
        'stok' => 10,
    ]);

    $response = $this->actingAs($user)->get('/buku');

    $response->assertOk();
    $response->assertSee('Katalog');
    $response->assertSee('BK-TEST-1');
    $response->assertSee('Buku Belajar Laravel');
    $response->assertSee('Penulis Hebat');
});

test('authenticated user can view create form', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/buku/create');

    $response->assertOk();
    $response->assertSee('Formulir Tambah Buku');
});

test('authenticated user can view edit form', function () {
    $user = User::factory()->create();
    $buku = Buku::factory()->create([
        'kode_buku' => 'BK-EDIT-1',
        'judul' => 'Judul Awal',
        'pengarang' => 'Pengarang Awal',
        'penerbit' => 'Penerbit Awal',
        'tahun_terbit' => 2023,
        'stok' => 5,
    ]);

    $response = $this->actingAs($user)->get("/buku/{$buku->id}/edit");

    $response->assertOk();
    $response->assertSee('Ubah Data Buku');
    $response->assertSee('BK-EDIT-1');
    $response->assertSee('Judul Awal');
});

test('authenticated user can store a new book', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/buku', [
        'kode_buku' => 'BK-NEW-99',
        'judul' => 'Buku Baru Sukses Disimpan',
        'pengarang' => 'Pengarang Baru',
        'penerbit' => 'Penerbit Baru',
        'tahun_terbit' => 2024,
        'stok' => 7,
    ]);

    $response->assertRedirect(route('buku.index'));
    $this->assertDatabaseHas('buku', [
        'kode_buku' => 'BK-NEW-99',
        'judul' => 'Buku Baru Sukses Disimpan',
    ]);
});

test('authenticated user can update a book', function () {
    $user = User::factory()->create();
    $buku = Buku::factory()->create([
        'kode_buku' => 'BK-UPDATE-1',
        'judul' => 'Judul Sebelum Update',
        'pengarang' => 'Pengarang',
        'penerbit' => 'Penerbit',
        'tahun_terbit' => 2022,
        'stok' => 3,
    ]);

    $response = $this->actingAs($user)->put("/buku/{$buku->id}", [
        'judul' => 'Judul Sesudah Update',
        'pengarang' => 'Pengarang Baru',
        'penerbit' => 'Penerbit Baru',
        'tahun_terbit' => 2023,
        'stok' => 8,
    ]);

    $response->assertRedirect(route('buku.index'));
    $this->assertDatabaseHas('buku', [
        'id' => $buku->id,
        'judul' => 'Judul Sesudah Update',
    ]);
});

test('authenticated user can delete a book', function () {
    $user = User::factory()->create();
    $buku = Buku::factory()->create([
        'kode_buku' => 'BK-DEL-1',
        'judul' => 'Buku Yang Akan Dihapus',
        'pengarang' => 'Pengarang',
        'penerbit' => 'Penerbit',
        'tahun_terbit' => 2021,
        'stok' => 2,
    ]);

    $response = $this->actingAs($user)->delete("/buku/{$buku->id}");

    $response->assertRedirect(route('buku.index'));
    $this->assertDatabaseMissing('buku', [
        'id' => $buku->id,
    ]);
});
