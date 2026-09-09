@extends('layouts.app')

@section('title', 'Tambah Anggota Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <nav class="flex items-center gap-2 text-xs font-medium text-slate-500">
            <a href="{{ route('anggota.index') }}" class="hover:text-brand-600 transition-colors">Data Anggota</a>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-slate-800 font-bold">Tambah Anggota</span>
        </nav>
        <a href="{{ route('anggota.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3 py-1.5 rounded-lg">Kembali</a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-bold text-slate-900">Formulir Pendaftaran Siswa</h2>
            <p class="text-xs text-slate-500 mt-0.5">Daftarkan siswa baru sebagai anggota perpustakaan.</p>
        </div>

        <form action="{{ route('anggota.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            <div>
                <label for="nis" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">NIS (Nomor Induk Siswa) <span class="text-rose-500">*</span></label>
                <input type="text" id="nis" name="nis" value="{{ old('nis') }}" required placeholder="Contoh: NIS202405" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('nis') border-rose-400 @else border-slate-200 @enderror rounded-xl font-mono">
                @error('nis') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Muhammad Rizky" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('nama') border-rose-400 @else border-slate-200 @enderror rounded-xl">
                @error('nama') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="kelas" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kelas / Jurusan <span class="text-rose-500">*</span></label>
                <input type="text" id="kelas" name="kelas" value="{{ old('kelas') }}" required placeholder="Contoh: XII RPL 1" class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border @error('kelas') border-rose-400 @else border-slate-200 @enderror rounded-xl">
                @error('kelas') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('anggota.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl shadow-md cursor-pointer">Simpan Anggota</button>
            </div>
        </form>
    </div>
</div>
@endsection
