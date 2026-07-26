@extends('layouts.pegawai')

@section('title', 'Detail Kunjungan')
@section('page-title', 'Detail Kunjungan')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('pegawai.kunjungan.index') }}" class="flex items-center gap-2 text-slate-500 text-sm mb-4 hover:underline">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali ke Riwayat
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        {{-- Header --}}
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                    <i data-lucide="map-pin" class="w-6 h-6 text-indigo-600"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-slate-800">Detail Kunjungan</h2>
                    <p class="text-xs text-slate-400">ID Kunjungan #{{ $kunjungan->id }}</p>
                </div>
            </div>
            @if ($kunjungan->status === 'tagihan_dibayar')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Tagihan Dibayar
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-600 ring-1 ring-rose-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    Belum Bayar
                </span>
            @endif
        </div>

        {{-- Info Detail --}}
        <div class="space-y-4 text-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 font-semibold uppercase mb-2">Waktu Kunjungan</p>
                    <div class="flex items-center gap-2">
                        <i data-lucide="calendar" class="w-4 h-4 text-indigo-500"></i>
                        <span class="font-medium text-slate-800">{{ $kunjungan->waktu_kunjungan->format('d F Y H:i') }} WIB</span>
                    </div>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 font-semibold uppercase mb-2">Pegawai</p>
                    <div class="flex items-center gap-2">
                        <i data-lucide="user" class="w-4 h-4 text-indigo-500"></i>
                        <span class="font-medium text-slate-800">{{ $kunjungan->pegawai->name }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-xs text-slate-400 font-semibold uppercase mb-2">Pelanggan</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                        <i data-lucide="user" class="w-5 h-5 text-indigo-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">{{ $kunjungan->pelanggan->nama }}</p>
                        <p class="text-xs text-slate-400">{{ $kunjungan->pelanggan->kode }} • {{ $kunjungan->pelanggan->paket ?? '-' }}</p>
                        <p class="text-xs text-slate-400">{{ $kunjungan->pelanggan->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-xs text-slate-400 font-semibold uppercase mb-2">Status Kunjungan</p>
                @if ($kunjungan->status === 'tagihan_dibayar')
                    <div class="flex items-center gap-2 text-emerald-700">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
                        <span class="font-medium">Pelanggan melakukan pembayaran tagihan saat kunjungan.</span>
                    </div>
                @else
                    <div class="flex items-center gap-2 text-rose-700">
                        <i data-lucide="x-circle" class="w-5 h-5 text-rose-500"></i>
                        <span class="font-medium">Pelanggan belum membayar tagihan saat kunjungan.</span>
                    </div>
                @endif
            </div>

            @if ($kunjungan->catatan)
                <div class="bg-indigo-50 rounded-xl p-4">
                    <p class="text-xs text-indigo-400 font-semibold uppercase mb-2">Catatan</p>
                    <p class="text-sm text-indigo-800">{{ $kunjungan->catatan }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

