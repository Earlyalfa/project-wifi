@extends('layouts.pegawai')

@section('title', 'Dashboard Pegawai')
@section('page-title', 'Dashboard Pegawai')

@section('content')

    {{-- KARTU STATISTIK --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-white rounded-xl p-4 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center mb-3">
                <i data-lucide="scan-line" class="w-4 h-4 text-indigo-600"></i>
            </div>
            <p class="text-xs text-slate-400">Scan Hari ini</p>
            <p class="text-2xl font-bold text-slate-800">{{ $scanHariIni }}</p>
            <p class="text-xs text-emerald-600 mt-1">+{{ $scanKemarin }} dari kemarin</p>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center mb-3">
                <i data-lucide="clipboard-list" class="w-4 h-4 text-amber-600"></i>
            </div>
            <p class="text-xs text-slate-400">Belum Bayar</p>
            <p class="text-2xl font-bold text-slate-800">{{ $belumBayarCount }}</p>
            <p class="text-xs text-slate-400 mt-1">Pelanggan</p>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center mb-3">
                <i data-lucide="credit-card" class="w-4 h-4 text-emerald-600"></i>
            </div>
            <p class="text-xs text-slate-400">Pembayaran</p>
            <p class="text-2xl font-bold text-slate-800">{{ $pembayaranHariIni }}</p>
            <p class="text-xs text-slate-400 mt-1">Hari ini</p>
        </div>

        <div class="bg-white rounded-xl p-4 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center mb-3">
                <i data-lucide="triangle-alert" class="w-4 h-4 text-rose-600"></i>
            </div>
            <p class="text-xs text-slate-400">Gangguan</p>
            <p class="text-2xl font-bold text-slate-800">{{ $gangguanMenunggu }}</p>
            <p class="text-xs text-slate-400 mt-1">Menunggu</p>
        </div>
    </div>

    {{-- SCAN TERAKHIR & DAFTAR BELUM BAYAR --}}
    <div class="grid md:grid-cols-2 gap-4 mb-6">

        <div class="bg-white rounded-xl p-5 shadow-sm">
            <h3 class="font-semibold text-slate-800 mb-4">Scan Terakhir</h3>
            <div class="space-y-3">
                @forelse ($scanTerakhir as $item)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
                                <i data-lucide="user" class="w-4 h-4 text-slate-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $item->pelanggan->kode }}</p>
                                <p class="text-xs text-slate-400">{{ $item->pelanggan->nama }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-slate-400">{{ $item->waktu_kunjungan->format('H:i') }} WIB</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">Belum ada scan hari ini.</p>
                @endforelse
            </div>
            <a href="{{ route('pegawai.pelanggan.index') }}" class="text-xs text-emerald-600 font-medium hover:underline mt-4 inline-block">Lihat semua</a>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm">
            <h3 class="font-semibold text-slate-800 mb-4">Daftar Belum Bayar</h3>
            <div class="space-y-3">
                @forelse ($daftarBelumBayar as $tagihan)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
                                <i data-lucide="user" class="w-4 h-4 text-slate-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $tagihan->pelanggan->nama }}</p>
                                <p class="text-xs text-slate-400">{{ $tagihan->pelanggan->kode }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-semibold text-slate-800">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">Tidak ada tagihan yang belum dibayar.</p>
                @endforelse
            </div>
            <a href="#" class="text-xs text-emerald-600 font-medium hover:underline mt-4 inline-block">Lihat semua</a>
        </div>
    </div>

    {{-- KUNJUNGAN TERAKHIR --}}
    <div class="bg-white rounded-xl p-5 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-4">Kunjungan Terakhir</h3>
        <div class="space-y-3">
            @forelse ($kunjunganTerakhir as $kunjungan)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-400 w-14">{{ $kunjungan->waktu_kunjungan->format('H:i') }}</span>
                    <span class="flex-1 font-medium text-slate-800">{{ $kunjungan->pelanggan->nama }}</span>
                    @if ($kunjungan->status === 'tagihan_dibayar')
                        <span class="text-emerald-600">Tagihan dibayar</span>
                    @else
                        <span class="text-rose-500 bg-rose-50 px-2 py-0.5 rounded-full text-xs font-semibold">Belum bayar</span>
                    @endif
                </div>
            @empty
                <p class="text-sm text-slate-400">Belum ada kunjungan tercatat.</p>
            @endforelse
        </div>
    </div>

@endsection
