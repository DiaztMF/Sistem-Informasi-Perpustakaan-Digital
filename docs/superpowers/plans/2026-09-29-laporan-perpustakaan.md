# Laporan Pinjaman Aktif + Poles Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Ship halaman laporan pinjaman aktif (read-only, siap cetak) plus dua poles kecil, tanpa migrasi database.

**Architecture:** Satu controller baru khusus laporan (`LaporanController`, 1 method), satu view Blade meniru gaya Tailwind existing, dan dua edit view existing (badge durasi + penyatuan search ke server-side). Statistik laporan dihitung di controller; tidak ada perubahan skema.

**Tech Stack:** Laravel 13, Blade + Tailwind, Pest 5 (style `test()` closure seperti file test existing).

## Global Constraints

- Tanpa migrasi database — tidak ada kolom baru, tidak ada logika jatuh tempo/denda.
- Semua route baru di dalam grup `Route::middleware(['auth'])` di `routes/web.php`.
- Ikuti pola test existing: `User::factory()->create()`, `$this->actingAs($user)`, `Buku::factory()->create([...])`, `Anggota::create([...])`, `Peminjaman::create([...])`.
- Satu sumber kebenaran untuk search buku = server-side `?search=`; tidak ada filter client-side setengah data.
- Commit per task selesai dengan pesan `feat:`/`fix:` yang spesifik.

---

### Task 1: LaporanController + Route (TDD)

**Files:**
- Create: `app/Http/Controllers/LaporanController.php`
- Modify: `routes/web.php` (tambah import + 1 route di grup `auth`)
- Test: `tests/Feature/LaporanTest.php`

**Interfaces:**
- Consumes: model `Peminjaman` (relasi `buku`, `anggota`; cast `tanggal_pinjam` → Carbon), pola route grup `auth` existing.
- Produces: `GET /laporan` bernama `laporan.index` → view `laporan.index` dengan variabel `pinjaman` (Collection), `total_dipinjam` (int), `total_peminjam_unik` (int), `rata_rata_hari` (float 1 desimal), `durasi_terlama_hari` (int), `buku_terlama_judul` (?string). Dipakai Task 2.

- [ ] **Step 1: Write the failing test**

```php
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
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php artisan test --filter=LaporanTest`
Expected: FAIL with 404 (no route `/laporan` yet).

- [ ] **Step 3: Write minimal implementation**

`app/Http/Controllers/LaporanController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * Menampilkan laporan pinjaman buku yang sedang aktif.
     */
    public function index(): View
    {
        $pinjaman = Peminjaman::with(['buku', 'anggota'])
            ->where('status', 'dipinjam')
            ->latest()
            ->get();

        $durasi = $pinjaman->map(
            fn (Peminjaman $p) => $p->tanggal_pinjam->diffInDays(now())
        );

        $terlama = $pinjaman->sortBy(
            fn (Peminjaman $p) => $p->tanggal_pinjam
        )->first();

        return view('laporan.index', [
            'pinjaman' => $pinjaman,
            'total_dipinjam' => $pinjaman->count(),
            'total_peminjam_unik' => $pinjaman->unique('anggota_id')->count(),
            'rata_rata_hari' => round($durasi->avg() ?? 0, 1),
            'durasi_terlama_hari' => $terlama
                ? $terlama->tanggal_pinjam->diffInDays(now())
                : 0,
            'buku_terlama_judul' => $terlama?->buku?->judul,
        ]);
    }
}
```

In `routes/web.php`, add the import next to the other controller imports:

```php
use App\Http\Controllers\LaporanController;
```

And add the route as the first line inside the existing auth group (before `Route::resource('buku', ...)`):

```php
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
```

- [ ] **Step 4: Run test — expect FAIL on missing view**

Run: `php artisan test --filter=LaporanTest`
Expected: FAIL with "View [laporan.index] not found". That failure is correct and hands off to Task 2. Do not create the view in this task.

- [ ] **Step 5: Commit**

```bash
git add app/Http/Controllers/LaporanController.php routes/web.php tests/Feature/LaporanTest.php
git commit -m "feat: add laporan controller, route, and failing tests"
```

### Task 2: View Laporan + Link Navigasi

**Files:**
- Create: `resources/views/laporan/index.blade.php`
- Modify: `resources/views/layouts/app.blade.php` (2 nav blocks: desktop ~line 86-93, mobile ~line 138-140)
- Test: `tests/Feature/LaporanTest.php` (from Task 1, no new test file)

**Interfaces:**
- Consumes: variabel dari Task 1 (`pinjaman`, `total_dipinjam`, `total_peminjam_unik`, `rata_rata_hari`, `durasi_terlama_hari`, `buku_terlama_judul`); pola kartu statistik + tabel dari `buku/index.blade.php` dan `peminjaman/index.blade.php`.
- Produces: halaman `/laporan` hijau di test Task 1.

- [ ] **Step 1: Confirm the failing test**

Run: `php artisan test --filter=LaporanTest`
Expected: FAIL with "View [laporan.index] not found".

- [ ] **Step 2: Create the view**

`resources/views/laporan/index.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Laporan Pinjaman Aktif')

@section('content')
<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Laporan Pinjaman Aktif</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar buku yang sedang dipinjam beserta durasinya.</p>
        </div>
        <div class="print:hidden">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-blue-500/20 transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak Laporan</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sedang Dipinjam</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-0.5">{{ $total_dipinjam }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Peminjam Aktif</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-0.5">{{ $total_peminjam_unik }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Rata-rata (hari)</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-0.5">{{ $rata_rata_hari }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Terlama (hari)</p>
            <h3 class="text-2xl font-bold text-slate-900 mt-0.5">{{ $durasi_terlama_hari }}</h3>
            @if($buku_terlama_judul)
                <p class="text-xs text-slate-400 mt-0.5 truncate">{{ $buku_terlama_judul }}</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-xs font-bold text-slate-700 uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4 w-16 text-center">No</th>
                        <th scope="col" class="px-6 py-4">Buku yang Dipinjam</th>
                        <th scope="col" class="px-6 py-4">Peminjam (Siswa)</th>
                        <th scope="col" class="px-6 py-4">Tanggal Pinjam</th>
                        <th scope="col" class="px-6 py-4 text-center">Lama (hari)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pinjaman as $index => $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4 text-center font-medium text-slate-400 text-xs">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 text-base leading-snug">{{ $item->buku?->judul ?? 'Buku Telah Dihapus' }}</div>
                                <div class="text-xs text-slate-400 mt-0.5 font-mono">{{ $item->buku?->kode_buku }} &bull; {{ $item->buku?->pengarang }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800">{{ $item->anggota?->nama ?? 'Anggota Tidak Dikenal' }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">NIS: {{ $item->anggota?->nis }} &bull; {{ $item->anggota?->kelas }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700 font-medium">{{ $item->tanggal_pinjam->translatedFormat('d M Y') }}</td>
                            <td class="px-6 py-4 text-center whitespace-nowrap font-bold text-amber-700">{{ $item->tanggal_pinjam->diffInDays(now()) }} hari</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada buku yang sedang dipinjam.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
```

- [ ] **Step 3: Add desktop nav link**

In `resources/views/layouts/app.blade.php`, insert directly after the closing `</a>` of the Peminjaman Buku desktop link (line 93, before `</nav>` on line 94):

```blade
<a href="{{ route('laporan.index') }}" class="px-3 py-2 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('laporan.*') ? 'bg-brand-50 text-brand-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
    <div class="flex items-center gap-1.5">
        <svg class="w-4 h-4 {{ request()->routeIs('laporan.*') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Laporan
    </div>
</a>
```

- [ ] **Step 4: Add mobile nav link**

In the same file, insert directly after the Peminjaman Buku mobile link (lines 138-140, before `@auth` on line 141):

```blade
<a href="{{ route('laporan.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold {{ request()->routeIs('laporan.*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600' }}">
    Laporan
</a>
```

- [ ] **Step 5: Run test to verify it passes**

Run: `php artisan test --filter=LaporanTest`
Expected: PASS (2 tests: guest redirect + stats view).

- [ ] **Step 6: Commit**

```bash
git add resources/views/laporan/index.blade.php resources/views/layouts/app.blade.php
git commit -m "feat: add laporan view with stats, print, and nav links"
```

### Task 3: Badge Lama-Dipinjam di Tabel Peminjaman

**Files:**
- Modify: `resources/views/peminjaman/index.blade.php` (thead ~line 50, tanggal-kembali cell ~lines 81-87, `@empty` colspan line 139)
- Test: nonew test file — visual change only; guard via existing suite in Task 5.

**Interfaces:**
- Consumes: `$item->tanggal_pinjam` / `$item->tanggal_kembali` (Carbon via model casts).
- Produces: kolom "Lama" di tabel; tidak ada perubahan controller/route.

- [ ] **Step 1: Add the header cell**

After the `Tanggal Kembali` header cell (`<th scope="col" class="px-6 py-4">Tanggal Kembali</th>`), insert:

```blade
<th scope="col" class="px-6 py-4 text-center">Lama</th>
```

- [ ] **Step 2: Add the body cell**

After the closing `</td>` of the tanggal-kembali cell (line 87, before the status `<td>` on line 88), insert:

```blade
<td class="px-6 py-4 text-center whitespace-nowrap text-xs font-semibold text-slate-500">
    {{ $item->tanggal_pinjam->diffInDays($item->tanggal_kembali ?? now()) }} hari
</td>
```

- [ ] **Step 3: Fix the empty-state colspan**

Change `<td colspan="7"` to `<td colspan="8"` in the `@empty` row (line 139), since the table now has 8 columns.

- [ ] **Step 4: Verify visually + run suite**

Run: `php artisan test`
Expected: all existing tests PASS (no logic changed, Blade-only edit).

- [ ] **Step 5: Commit**

```bash
git add resources/views/peminjaman/index.blade.php
git commit -m "feat: show loan duration badge in peminjaman table"
```

### Task 4: Search Buku Satu Sumber Kebenaran (Server-Side)

**Files:**
- Modify: `app/Http/Controllers/BukuController.php` (`index` method, lines 13-29)
- Modify: `resources/views/buku/index.blade.php` (x-data block lines 6-22, search input lines 105-110, filter buttons lines 117-141, row `x-show` lines 162-171, stats lines 51/65/77/90, numbering line 173)
- Test: `tests/Feature/BukuSearchTest.php` (new file, 3 tests)

**Interfaces:**
- Consumes: query params `?search=` dan `?ketersediaan=all|tersedia|habis`; pola test existing.
- Produces: search + filter yang mencakup seluruh tabel DB (bukan 8 baris halaman aktif); statistik kartu yang benar dari query penuh.

- [ ] **Step 1: Write the failing tests**

```php
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
```

- [ ] **Step 2: Run tests to verify they fail**

Run: `php artisan test --filter=BukuSearchTest`
Expected: FAIL — `?search=` path returns both books (or 404 on missing view data), because the view ignores server results and stats count only the page.

- [ ] **Step 3: Update the controller**

Replace the `index` method in `app/Http/Controllers/BukuController.php` (lines 13-29) with:

```php
public function index(Request $request)
{
    $search = $request->string('search')->trim()->value();
    $ketersediaan = $request->string('ketersediaan')->trim()->value();
    if (! in_array($ketersediaan, ['tersedia', 'habis'], true)) {
        $ketersediaan = 'all';
    }

    $query = Buku::query()
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%")
                    ->orWhere('kode_buku', 'like', "%{$search}%")
                    ->orWhere('penerbit', 'like', "%{$search}%");
            });
        })
        ->when($ketersediaan === 'tersedia', fn ($query) => $query->where('stok', '>', 0))
        ->when($ketersediaan === 'habis', fn ($query) => $query->where('stok', '<=', 0));

    $buku = (clone $query)->latest()->paginate(8)->withQueryString();

    $stats = [
        'total_judul' => Buku::count(),
        'total_stok' => (int) Buku::sum('stok'),
        'tersedia' => Buku::where('stok', '>', 0)->count(),
        'habis' => Buku::where('stok', '<=', 0)->count(),
    ];

    return view('buku.index', compact('buku', 'stats', 'search', 'ketersediaan'));
}
```

Note: the `orWhere` group is wrapped in a closure so it never leaks outside the search scope; stats come from full-table queries, not the paginated collection.

- [ ] **Step 4: Rewire the view to server-side**

1. Replace the opening `<div x-data="{ ... }">` block (lines 6-22) with a plain `<div>` — delete the `search`, `statusFilter`, and `matches()` Alpine state entirely.
2. Replace the search `<input>` (lines 105-110): remove `x-model="search"`, wrap it in a GET form so Enter submits to the server:
```blade
<form action="{{ route('buku.index') }}" method="GET" class="relative flex-1">
    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </div>
    <input
        type="text"
        name="search"
        value="{{ $search }}"
        placeholder="Cari berdasarkan judul, pengarang, kode buku, atau penerbit..."
        class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-slate-800 placeholder-slate-400"
    >
    <input type="hidden" name="ketersediaan" value="{{ $ketersediaan }}">
</form>
```
3. Replace the three filter `<button type="button" @click=...>` elements (lines 117-141) with links that preserve the search term:
```blade
<a
    href="{{ route('buku.index', ['search' => $search, 'ketersediaan' => 'all']) }}"
    class="px-3 py-1 text-xs rounded-lg transition-all {{ $ketersediaan === 'all' ? 'bg-white text-slate-800 shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-800' }}"
>
    Semua
</a>
```
(same pattern for `tersedia` → emerald active classes, `habis` → rose active classes, keeping the original class strings from lines 120/128/136).
4. Delete the `x-show="matches({...})"` attribute from the `<tr>` (lines 162-171), leaving `<tr class="hover:bg-slate-50/60 transition-colors">`.
5. Replace the four stat values: `{{ $buku->count() }}` → `{{ $stats['total_judul'] }}`, `{{ $buku->sum('stok') }}` → `{{ $stats['total_stok'] }}`, `{{ $buku->where('stok', '>', 0)->count() }}` → `{{ $stats['tersedia'] }}`, `{{ $buku->where('stok', '<=', 0)->count() }}` → `{{ $stats['habis'] }}`.
6. Fix row numbering line 173: `{{ $index + 1 }}` → `{{ $buku->firstItem() + $index }}` so page 2 continues counting.

- [ ] **Step 5: Run tests to verify they pass**

Run: `php artisan test --filter=BukuSearchTest`
Expected: PASS (3 tests). Then run: `php artisan test`
Expected: all 27 tests PASS (22 pre-existing + 2 LaporanTest + 3 BukuSearchTest).

- [ ] **Step 6: Commit**

```bash
git add app/Http/Controllers/BukuController.php resources/views/buku/index.blade.php tests/Feature/BukuSearchTest.php
git commit -m "fix: unify book search to server-side with correct stats"
```

### Task 5: Final Verification (No Code Changes)

**Files:** none (verification only).

- [ ] **Step 1: Run the full suite**

Run: `php artisan test`
Expected: everything PASS (LaporanTest + BukuSearchTest + all pre-existing tests).

- [ ] **Step 2: Confirm the new route**

Run: `php artisan route:list --path=laporan`
Expected: one row, `GET|HEAD laporan → LaporanController@index`, named `laporan.index`, middleware includes `auth` (via `web` group listing).

- [ ] **Step 3: Manual browser check (local)**

Run: `php artisan serve`, visit `/laporan` as logged-in user: stats match DB, print button opens print dialog, nav highlights Laporan. Visit `/buku?search=` with a term spanning pages: results come from the whole catalog.
