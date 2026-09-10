@extends('layouts.app')

@section('title', 'Transaksi Peminjaman Buku')

@section('content')
<div>
    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Sirkulasi & Peminjaman Buku</h1>
            <p class="text-sm text-slate-500 mt-1">Pencatatan peminjaman buku oleh siswa dan proses pengembalian buku.</p>
        </div>
        <div>
            <a href="{{ route('peminjaman.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/25 transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Pinjamkan Buku Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter Status Tabs -->
    <div class="bg-white p-3 rounded-2xl border border-slate-200 shadow-xs mb-6 flex flex-wrap items-center justify-between gap-3">
        <div class="inline-flex rounded-xl bg-slate-100 p-1">
            <a href="{{ route('peminjaman.index') }}" class="px-3.5 py-1.5 text-xs rounded-lg transition-all {{ empty($status) ? 'bg-white text-slate-900 font-bold shadow-2xs' : 'text-slate-500 hover:text-slate-900' }}">
                Semua Transaksi
            </a>
            <a href="{{ route('peminjaman.index', ['status' => 'dipinjam']) }}" class="px-3.5 py-1.5 text-xs rounded-lg transition-all {{ $status === 'dipinjam' ? 'bg-white text-amber-700 font-bold shadow-2xs' : 'text-slate-500 hover:text-slate-900' }}">
                Sedang Dipinjam
            </a>
            <a href="{{ route('peminjaman.index', ['status' => 'kembali']) }}" class="px-3.5 py-1.5 text-xs rounded-lg transition-all {{ $status === 'kembali' ? 'bg-white text-emerald-700 font-bold shadow-2xs' : 'text-slate-500 hover:text-slate-900' }}">
                Sudah Dikembalikan
            </a>
        </div>
        <div class="text-xs font-semibold text-slate-500 px-2">
            Total: {{ $peminjaman->total() }} catatan
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-xs font-bold text-slate-700 uppercase tracking-wider">
                    <tr>
                        <th scope="col" class="px-6 py-4 w-16 text-center">No</th>
                        <th scope="col" class="px-6 py-4">Buku yang Dipinjam</th>
                        <th scope="col" class="px-6 py-4">Peminjam (Siswa)</th>
                        <th scope="col" class="px-6 py-4">Tanggal Pinjam</th>
                        <th scope="col" class="px-6 py-4">Tanggal Kembali</th>
                        <th scope="col" class="px-6 py-4 text-center">Status</th>
                        <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($peminjaman as $index => $item)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4 text-center font-medium text-slate-400 text-xs">
                                {{ $peminjaman->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 text-base leading-snug">
                                    {{ $item->buku?->judul ?? 'Buku Telah Dihapus' }}
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5 font-mono">
                                    {{ $item->buku?->kode_buku }} &bull; {{ $item->buku?->pengarang }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800">
                                    {{ $item->anggota?->nama ?? 'Anggota Tidak Dikenal' }}
                                </div>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    NIS: {{ $item->anggota?->nis }} &bull; {{ $item->anggota?->kelas }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700 font-medium">
                                {{ $item->tanggal_pinjam instanceof \Carbon\CarbonInterface || $item->tanggal_pinjam instanceof \DateTimeInterface ? $item->tanggal_pinjam->translatedFormat('d M Y') : (\Illuminate\Support\Carbon::tryParse($item->tanggal_pinjam)?->translatedFormat('d M Y') ?? $item->tanggal_pinjam) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700">
                                @if($item->tanggal_kembali)
                                    <span class="text-emerald-700 font-medium">{{ $item->tanggal_kembali instanceof \Carbon\CarbonInterface || $item->tanggal_kembali instanceof \DateTimeInterface ? $item->tanggal_kembali->translatedFormat('d M Y') : (\Illuminate\Support\Carbon::tryParse($item->tanggal_kembali)?->translatedFormat('d M Y') ?? $item->tanggal_kembali) }}</span>
                                @else
                                    <span class="text-slate-400 italic text-xs">Belum Dikembalikan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                @if($item->status === 'dipinjam')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Dipinjam
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Kembali
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    @if($item->status === 'dipinjam')
                                        <form action="{{ route('peminjaman.kembalikan', $item->id) }}" method="POST"
                                              data-confirm="Proses pengembalian buku '{{ addslashes($item->buku?->judul ?? 'buku ini') }}'? Tanggal pengembalian akan dicatat hari ini dan stok buku fisik bertambah 1."
                                              data-confirm-title="Konfirmasi Pengembalian Buku"
                                              data-confirm-variant="primary"
                                              data-confirm-btn="Ya, Kembalikan Buku"
                                              class="inline-block">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-emerald-200 bg-emerald-50 text-xs font-semibold text-emerald-700 hover:bg-emerald-600 hover:text-white transition-colors cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Kembalikan
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('peminjaman.destroy', $item->id) }}" method="POST"
                                          data-confirm="Apakah Anda yakin ingin menghapus catatan riwayat transaksi peminjaman ini?"
                                          data-confirm-title="Hapus Riwayat Peminjaman"
                                          data-confirm-variant="danger"
                                          data-confirm-btn="Ya, Hapus Transaksi"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center p-1.5 rounded-lg border border-slate-200 text-xs text-rose-500 hover:bg-rose-50 hover:border-rose-200 transition-colors cursor-pointer" title="Hapus catatan">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                Belum ada riwayat transaksi peminjaman buku.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($peminjaman->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $peminjaman->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
