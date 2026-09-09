@extends('layouts.app')

@section('title', 'Pinjamkan Buku Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <nav class="flex items-center gap-2 text-xs font-medium text-slate-500">
            <a href="{{ route('peminjaman.index') }}" class="hover:text-brand-600 transition-colors">Peminjaman</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-slate-800 font-bold">Transaksi Pinjam</span>
        </nav>
        <a href="{{ route('peminjaman.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3 py-1.5 rounded-lg">Kembali</a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-900">Formulir Transaksi Peminjaman Buku</h2>
            <p class="text-xs text-slate-500 mt-0.5">Pilih anggota siswa dan buku yang akan dipinjam (stok buku akan otomatis berkurang 1).</p>
        </div>

        <form action="{{ route('peminjaman.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Pilih Anggota -->
            <div>
                <label for="anggota_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Peminjam (Siswa) <span class="text-rose-500">*</span></label>
                <select id="anggota_id" name="anggota_id" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('anggota_id') border-rose-400 @else border-slate-200 @enderror rounded-xl">
                    <option value="">-- Pilih Siswa / Anggota --</option>
                    @foreach($anggota as $siswa)
                        <option value="{{ $siswa->id }}" {{ old('anggota_id') == $siswa->id ? 'selected' : '' }}>
                            {{ $siswa->nama }} (NIS: {{ $siswa->nis }} - {{ $siswa->kelas }})
                        </option>
                    @endforeach
                </select>
                @error('anggota_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <!-- Pilih Buku -->
            <div>
                <label for="buku_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Buku yang Dipinjam <span class="text-rose-500">*</span></label>
                <select id="buku_id" name="buku_id" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('buku_id') border-rose-400 @else border-slate-200 @enderror rounded-xl">
                    <option value="">-- Pilih Buku yang Tersedia --</option>
                    @foreach($buku as $item)
                        <option value="{{ $item->id }}" {{ old('buku_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->judul }} [Kode: {{ $item->kode_buku }} | Sisa Stok: {{ $item->stok }}]
                        </option>
                    @endforeach
                </select>
                @error('buku_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <!-- Tanggal Pinjam -->
            <div>
                <label for="tanggal_pinjam" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Pinjam <span class="text-rose-500">*</span></label>
                <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('tanggal_pinjam') border-rose-400 @else border-slate-200 @enderror rounded-xl">
                @error('tanggal_pinjam') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('peminjaman.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-md cursor-pointer">Catat Peminjaman</button>
            </div>
        </form>
    </div>
</div>
@endsection
