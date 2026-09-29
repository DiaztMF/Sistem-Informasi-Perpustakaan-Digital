<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('buku.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_buku' => 'required|unique:buku',
            'judul' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);
        Buku::create($request->all());

        return redirect()->route('buku.index')
            ->with('success', 'Data buku berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Buku $buku)
    {
        return view('buku.edit', compact('buku'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buku $buku)
    {
        return view('buku.edit', compact('buku'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul' => 'required',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);
        $buku->update($request->all());

        return redirect()->route('buku.index')
            ->with('success', 'Data buku berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('buku.index')
            ->with('success', 'Data buku berhasil dihapus');
    }
}
