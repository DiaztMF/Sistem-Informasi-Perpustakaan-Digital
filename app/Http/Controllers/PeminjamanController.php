<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan daftar transaksi peminjaman.
     */
    public function index(Request $request): View
    {
        $status = $request->string('status')->trim()->value();

        $peminjaman = Peminjaman::with(['buku', 'anggota'])
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('peminjaman.index', compact('peminjaman', 'status'));
    }

    /**
     * Menampilkan formulir peminjaman buku baru.
     */
    public function create(): View
    {
        $buku = Buku::where('stok', '>', 0)->orderBy('judul')->get();
        $anggota = Anggota::orderBy('nama')->get();

        return view('peminjaman.create', compact('buku', 'anggota'));
    }

    /**
     * Menyimpan transaksi peminjaman baru dan memotong stok buku.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'buku_id' => ['required', 'exists:buku,id'],
            'anggota_id' => ['required', 'exists:anggota,id'],
            'tanggal_pinjam' => ['required', 'date'],
        ]);

        $buku = Buku::findOrFail($validated['buku_id']);

        if ($buku->stok <= 0) {
            return back()->withInput()->with('error', 'Maaf, stok buku "'.$buku->judul.'" saat ini sedang habis.');
        }

        // Simpan peminjaman
        Peminjaman::create([
            'buku_id' => $validated['buku_id'],
            'anggota_id' => $validated['anggota_id'],
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_kembali' => null,
            'status' => 'dipinjam',
        ]);

        // Kurangi stok buku
        $buku->decrement('stok');

        return redirect()->route('peminjaman.index')
            ->with('success', 'Transaksi peminjaman buku "'.$buku->judul.'" berhasil dicatat.');
    }

    /**
     * Proses pengembalian buku pinjaman.
     */
    public function kembalikan(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status === 'kembali') {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Buku ini sudah tercatat dikembalikan sebelumnya.');
        }

        $peminjaman->update([
            'status' => 'kembali',
            'tanggal_kembali' => now()->toDateString(),
        ]);

        // Kembalikan stok buku
        if ($peminjaman->buku) {
            $peminjaman->buku->increment('stok');
        }

        return redirect()->route('peminjaman.index')
            ->with('success', 'Buku "'.$peminjaman->buku?->judul.'" berhasil dikembalikan ke perpustakaan.');
    }

    /**
     * Menghapus catatan transaksi peminjaman.
     */
    public function destroy(Peminjaman $peminjaman): RedirectResponse
    {
        // Jika masih dipinjam, kembalikan stok buku terlebih dahulu sebelum dihapus
        if ($peminjaman->status === 'dipinjam' && $peminjaman->buku) {
            $peminjaman->buku->increment('stok');
        }

        $peminjaman->delete();

        return redirect()->route('peminjaman.index')
            ->with('success', 'Data transaksi peminjaman berhasil dihapus.');
    }
}
