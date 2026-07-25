@extends('layouts.pegawai')

@section('title', 'Dashboard Pegawai')
@section('page-title', 'Dashboard')

@section('content')
    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                <i data-lucide="users" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Total Pelanggan</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalPelanggan }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                <i data-lucide="clipboard-list" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Belum Bayar</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $belumBayarCount }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                <i data-lucide="triangle-alert" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Gangguan Hari Ini</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $gangguanHariIni }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                <i data-lucide="credit-card" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Pembayaran Hari Ini</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $pembayaranHariIni }}</p>
        </div>
    </div>

    {{-- Layout 2 Kolom --}}
    <div class="grid lg:grid-cols-3 gap-4 mb-6">

        {{-- Kolom Kiri - Tabel Belum Bayar --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-emerald-600"></i>
                        </div>
                        <h3 class="font-semibold text-slate-800">Pelanggan Belum Bayar</h3>
                    </div>
                </div>
                <div class="overflow-x-auto -mx-5">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-slate-100">
                                <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Nama</th>
                                <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Kode</th>
                                <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Paket</th>
                                <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Jatuh Tempo</th>
                                <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($daftarBelumBayar as $tagihan)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-5 py-3 font-medium text-slate-800">{{ $tagihan->pelanggan->nama }}</td>
                                    <td class="px-5 py-3 text-slate-500">{{ $tagihan->pelanggan->kode }}</td>
                                    <td class="px-5 py-3 text-slate-600">{{ $tagihan->pelanggan->paket ?? '-' }}</td>
                                    <td class="px-5 py-3 text-slate-600">{{ $tagihan->jatuh_tempo ? $tagihan->jatuh_tempo->format('d M Y') : '-' }}</td>
                                    <td class="px-5 py-3 font-semibold text-emerald-600">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-slate-400">Semua tagihan sudah dibayar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="space-y-4">

            {{-- Aktivitas Terbaru --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <i data-lucide="activity" class="w-4 h-4 text-emerald-600"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Aktivitas Terbaru</h3>
                </div>
                <div class="space-y-3">
                    @forelse ($aktivitasTerbaru as $aktivitas)
                        <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-slate-50 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="{{ $aktivitas->icon }}" class="w-4 h-4 text-emerald-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 truncate">{{ $aktivitas->title }}</p>
                                <p class="text-xs text-slate-400 truncate">{{ $aktivitas->desc }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $aktivitas->time }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-4">Belum ada aktivitas terbaru.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- Kunjungan Terakhir --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                <i data-lucide="history" class="w-4 h-4 text-emerald-600"></i>
            </div>
            <h3 class="font-semibold text-slate-800">Kunjungan Terakhir</h3>
        </div>
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-slate-100">
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Waktu</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Pelanggan</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($scanTerakhir as $kunjungan)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 text-slate-500">{{ $kunjungan->waktu_kunjungan->format('d M Y H:i') }} WIB</td>
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $kunjungan->pelanggan->nama }}</td>
                            <td class="px-5 py-3">
                                @if ($kunjungan->status === 'tagihan_dibayar')
                                    <span class="inline-flex items-center gap-1 text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-8 text-center text-slate-400">Belum ada kunjungan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

