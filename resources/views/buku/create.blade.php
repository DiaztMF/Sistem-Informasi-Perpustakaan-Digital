@extends('layouts.app')

@section('title', 'Tambah Buku Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Breadcrumb & Back -->
    <div class="mb-6 flex items-center justify-between">
        <nav class="flex items-center gap-2 text-xs font-medium text-slate-500">
            <a href="{{ route('buku.index') }}" class="hover:text-brand-600 transition-colors">Data Buku</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-slate-800 font-bold">Tambah Buku Baru</span>
        </nav>

        <a href="{{ route('buku.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-2xs transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Formulir Tambah Buku</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Lengkapi informasi koleksi buku baru untuk disimpan ke database perpustakaan.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('buku.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Kode Buku -->
                <div>
                    <label for="kode_buku" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kode Buku <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="kode_buku" 
                        name="kode_buku" 
                        value="{{ old('kode_buku') }}" 
                        placeholder="Contoh: BK-007" 
                        required
                        class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('kode_buku') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all font-mono"
                    >
                    @error('kode_buku')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @else
                        <p class="mt-1 text-2xs text-slate-400">Kode unik identifikasi buku di rak perpustakaan.</p>
                    @enderror
                </div>

                <!-- Stok -->
                <div>
                    <label for="stok" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Jumlah Stok <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="stok" 
                        name="stok" 
                        value="{{ old('stok', 1) }}" 
                        min="0" 
                        required
                        class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('stok') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"
                    >
                    @error('stok')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @else
                        <p class="mt-1 text-2xs text-slate-400">Jumlah eksemplar fisik buku yang tersedia.</p>
                    @enderror
                </div>
            </div>

            <!-- Judul Buku -->
            <div>
                <label for="judul" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Judul Buku <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="judul" 
                    name="judul" 
                    value="{{ old('judul') }}" 
                    placeholder="Masukkan judul buku lengkap..." 
                    required
                    class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('judul') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all font-medium text-slate-900"
                >
                @error('judul')
                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Pengarang -->
                <div>
                    <label for="pengarang" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nama Pengarang / Penulis <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="pengarang" 
                        name="pengarang" 
                        value="{{ old('pengarang') }}" 
                        placeholder="Contoh: Andrea Hirata" 
                        required
                        class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('pengarang') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"
                    >
                    @error('pengarang')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Penerbit -->
                <div>
                    <label for="penerbit" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nama Penerbit <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="penerbit" 
                        name="penerbit" 
                        value="{{ old('penerbit') }}" 
                        placeholder="Contoh: Kompas Gramedia" 
                        required
                        class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('penerbit') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"
                    >
                    @error('penerbit')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tahun Terbit -->
            <div class="sm:w-1/2">
                <label for="tahun_terbit" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Tahun Terbit <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="number" 
                    id="tahun_terbit" 
                    name="tahun_terbit" 
                    value="{{ old('tahun_terbit', date('Y')) }}" 
                    min="1900" 
                    max="{{ date('Y') + 1 }}" 
                    required
                    class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('tahun_terbit') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all"
                >
                @error('tahun_terbit')
                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('buku.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/25 transition-all flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Simpan Buku</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
