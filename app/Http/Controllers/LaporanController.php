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
            fn (Peminjaman $p) => (int) $p->tanggal_pinjam->diffInDays(now())
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
                ? (int) $terlama->tanggal_pinjam->diffInDays(now())
                : 0,
            'buku_terlama_judul' => $terlama?->buku?->judul,
        ]);
    }
}
