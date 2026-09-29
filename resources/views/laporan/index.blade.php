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
                            <td class="px-6 py-4 text-center whitespace-nowrap font-bold text-amber-700">{{ (int) $item->tanggal_pinjam->diffInDays(now()) }} hari</td>
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
