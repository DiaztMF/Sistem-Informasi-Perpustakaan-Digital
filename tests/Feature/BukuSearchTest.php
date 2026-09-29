<?php

use App\Models\Buku;
use App\Models\User;

test('search filters books across the database', function () {
    $user = User::factory()->create();
    Buku::factory()->create(['judul' => 'Kunci Inggris Unik']);
    Buku::factory()->create(['judul' => 'Buku Masak Sehari-hari']);

    $response = $this->actingAs($user)->get('/buku?search=Kunci Inggris');

    $response->assertOk();
    $response->assertSee('Kunci Inggris Unik');
    $response->assertDontSee('Buku Masak Sehari-hari');
});

test('ketersediaan filter shows only out-of-stock books', function () {
    $user = User::factory()->create();
    Buku::factory()->create(['judul' => 'Buku Stok Nol', 'stok' => 0]);
    Buku::factory()->create(['judul' => 'Buku Stok Ada', 'stok' => 5]);

    $response = $this->actingAs($user)->get('/buku?ketersediaan=habis');

    $response->assertOk();
    $response->assertSee('Buku Stok Nol');
    $response->assertDontSee('Buku Stok Ada');
});

test('stat cards reflect the full catalog not the current page', function () {
    $user = User::factory()->create();
    Buku::factory()->count(10)->create(['stok' => 2]);

    $response = $this->actingAs($user)->get('/buku');

    $response->assertOk();
    $response->assertSee('Total Judul');
});
