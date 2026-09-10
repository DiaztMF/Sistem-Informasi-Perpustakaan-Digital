@extends('layouts.app')

@section('title', 'Katalog Data Buku')

@section('content')
<div x-data="{ 
    search: '', 
    statusFilter: 'all',
    matches(item) {
        const matchesSearch = !this.search || 
            item.judul.toLowerCase().includes(this.search.toLowerCase()) ||
            item.kode.toLowerCase().includes(this.search.toLowerCase()) ||
            item.pengarang.toLowerCase().includes(this.search.toLowerCase()) ||
            item.penerbit.toLowerCase().includes(this.search.toLowerCase());
        
        const matchesStatus = this.statusFilter === 'all' || 
            (this.statusFilter === 'tersedia' && item.stok > 0) ||
            (this.statusFilter === 'habis' && item.stok <= 0);

        return matchesSearch && matchesStatus;
    }
}">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Katalog & Koleksi Buku</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data inventaris buku perpustakaan dan pantau status ketersediaannya.</p>
        </div>
        <div>
            <a href="{{ route('buku.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/25 transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Tambah Buku Baru</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Judul -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Judul</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-0.5">{{ $buku->count() }}</h3>
            </div>
        </div>

        <!-- Total Stok Eksemplar -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Stok</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-0.5">{{ $buku->sum('stok') }}</h3>
            </div>
        </div>

        <!-- Buku Tersedia -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Buku Siap Pinjam</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-0.5">{{ $buku->where('stok', '>', 0)->count() }}</h3>
            </div>
        </div>

        <!-- Stok Habis -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok Kosong</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-0.5">{{ $buku->where('stok', '<=', 0)->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs mb-6">
        <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
            <!-- Search Input -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="Cari berdasarkan judul, pengarang, kode buku, atau penerbit..." 
                    class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all text-slate-800 placeholder-slate-400"
                >
            </div>

            <!-- Filter Status -->
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-500 shrink-0">Filter:</span>
                <div class="inline-flex rounded-xl bg-slate-100 p-1">
                    <button 
                        type="button" 
                        @click="statusFilter = 'all'" 
                        :class="statusFilter === 'all' ? 'bg-white text-slate-800 shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-800'"
                        class="px-3 py-1 text-xs rounded-lg transition-all"
                    >
                        Semua
                    </button>
                    <button 
                        type="button" 
                        @click="statusFilter = 'tersedia'" 
                        :class="statusFilter === 'tersedia' ? 'bg-white text-emerald-700 shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-800'"
                        class="px-3 py-1 text-xs rounded-lg transition-all"
                    >
                        Tersedia
                    </button>
                    <button 
                        type="button" 
                        @click="statusFilter = 'habis'" 
                        :class="statusFilter === 'habis' ? 'bg-white text-rose-700 shadow-2xs font-bold' : 'text-slate-500 hover:text-slate-800'"
                        class="px-3 py-1 text-xs rounded-lg transition-all"
                    >
                        Habis
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-xs font-bold text-slate-700 uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4 w-16 text-center">No</th>
                        <th scope="col" class="px-6 py-4">Kode</th>
                        <th scope="col" class="px-6 py-4">Informasi Buku</th>
                        <th scope="col" class="px-6 py-4">Penerbit & Tahun</th>
                        <th scope="col" class="px-6 py-4 text-center">Stok</th>
                        <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($buku as $index => $item)
                        <tr 
                            x-show="matches({ 
                                judul: '{{ addslashes($item->judul) }}', 
                                kode: '{{ addslashes($item->kode_buku) }}', 
                                pengarang: '{{ addslashes($item->pengarang) }}', 
                                penerbit: '{{ addslashes($item->penerbit) }}', 
                                stok: {{ $item->stok }} 
                            })"
                            class="hover:bg-slate-50/60 transition-colors"
                        >
                            <td class="px-6 py-4 text-center font-medium text-slate-400 text-xs">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200/80 font-mono">
                                    {{ $item->kode_buku }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 text-base leading-snug">
                                    {{ $item->judul }}
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>{{ $item->pengarang }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-slate-800 font-medium">{{ $item->penerbit }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">Tahun {{ $item->tahun_terbit }}</div>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if($item->stok > 3)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $item->stok }} Tersedia
                                    </span>
                                @elseif($item->stok > 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        {{ $item->stok }} Menipis
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Stok Habis
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('buku.edit', $item->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:text-brand-700 hover:bg-brand-50 hover:border-brand-200 transition-colors cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>

                                    <!-- Delete Button Form -->
                                    <form action="{{ route('buku.destroy', $item->id) }}" method="POST"
                                          data-confirm="Apakah Anda yakin ingin menghapus buku '{{ addslashes($item->judul) }}'? Tindakan ini akan menghapus data buku secara permanen."
                                          data-confirm-title="Hapus Data Buku"
                                          data-confirm-variant="danger"
                                          data-confirm-btn="Ya, Hapus Buku"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-rose-600 hover:text-white hover:bg-rose-600 hover:border-rose-600 transition-colors cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-base font-bold text-slate-800">Belum Ada Data Buku</h4>
                                    <p class="text-xs text-slate-500 mt-1 max-w-sm">Katalog perpustakaan masih kosong. Silakan tambahkan koleksi buku pertama Anda.</p>
                                    <a href="{{ route('buku.create') }}" class="mt-4 px-4 py-2 bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold rounded-xl transition-colors">
                                        + Tambah Buku Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
