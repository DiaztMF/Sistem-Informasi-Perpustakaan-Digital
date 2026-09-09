<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->value();

        $anggota = Anggota::query()
            ->withCount(['peminjaman as pinjaman_aktif_count' => function ($query) {
                $query->where('status', 'dipinjam');
            }])
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('anggota.index', compact('anggota', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('anggota.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:20', 'unique:anggota,nis'],
            'nama' => ['required', 'string', 'max:100'],
            'kelas' => ['required', 'string', 'max:20'],
        ]);

        Anggota::create($validated);

        return redirect()->route('anggota.index')
            ->with('success', 'Anggota baru "'.$validated['nama'].'" berhasil didaftarkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Anggota $anggota): View
    {
        return view('anggota.edit', compact('anggota'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Anggota $anggota): RedirectResponse
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:20', Rule::unique('anggota', 'nis')->ignore($anggota->id)],
            'nama' => ['required', 'string', 'max:100'],
            'kelas' => ['required', 'string', 'max:20'],
        ]);

        $anggota->update($validated);

        return redirect()->route('anggota.index')
            ->with('success', 'Data anggota "'.$anggota->nama.'" berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anggota $anggota): RedirectResponse
    {
        $hasActiveLoans = $anggota->peminjaman()->where('status', 'dipinjam')->exists();

        if ($hasActiveLoans) {
            return redirect()->route('anggota.index')
                ->with('error', 'Anggota "'.$anggota->nama.'" tidak dapat dihapus karena masih meminjam buku.');
        }

        $nama = $anggota->nama;
        $anggota->delete();

        return redirect()->route('anggota.index')
            ->with('success', 'Data anggota "'.$nama.'" berhasil dihapus.');
    }
}
