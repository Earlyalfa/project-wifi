@extends('layouts.pegawai')

@section('title', 'Detail Gangguan')
@section('page-title', 'Detail Pengaduan Gangguan')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('pegawai.gangguan.index') }}" class="flex items-center gap-2 text-slate-500 text-sm mb-4 hover:underline">
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        {{-- Header --}}
        <div class="flex items-start justify-between mb-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i data-lucide="message-square-warning" class="w-5 h-5 text-amber-600"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-slate-800">Pengaduan Gangguan</h2>
                    <p class="text-xs text-slate-400 font-mono">{{ $gangguan->no_tiket }}</p>
                </div>
            </div>
            @php
                $badge = match($gangguan->status) {
                    'menunggu' => 'bg-amber-100 text-amber-700 ring-amber-200',
                    'diproses' => 'bg-blue-100 text-blue-700 ring-blue-200',
                    'selesai'  => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
                    default    => 'bg-slate-100 text-slate-500 ring-slate-200',
                };
                $dot = match($gangguan->status) {
                    'menunggu' => 'bg-amber-500',
                    'diproses' => 'bg-blue-500',
                    'selesai'  => 'bg-emerald-500',
                    default    => 'bg-slate-400',
                };
            @endphp
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold ring-1 {{ $badge }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
                {{ ucfirst($gangguan->status) }}
            </span>
        </div>

        {{-- Info --}}
        <div class="space-y-4 text-sm mb-6">
            <div class="flex gap-3">
                <i data-lucide="user" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                <div>
                    <p class="text-slate-400 text-xs">Nama Pelanggan</p>
                    <p class="text-slate-800 font-medium">{{ $gangguan->pelanggan->nama }}</p>
                    <p class="text-xs text-slate-400">{{ $gangguan->pelanggan->kode }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <i data-lucide="map-pin" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                <div>
                    <p class="text-slate-400 text-xs">Alamat</p>
                    <p class="text-slate-800 font-medium">{{ $gangguan->pelanggan->alamat ?? '-' }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <i data-lucide="phone" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                <div>
                    <p class="text-slate-400 text-xs">No. HP</p>
                    <p class="text-slate-800 font-medium">{{ $gangguan->pelanggan->no_hp ?? '-' }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <i data-lucide="calendar" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                <div>
                    <p class="text-slate-400 text-xs">Tanggal Lapor</p>
                    <p class="text-slate-800 font-medium">{{ $gangguan->tanggal_lapor->format('d F Y H:i') }} WIB</p>
                </div>
            </div>
        </div>

        {{-- Keluhan --}}
        <div class="bg-slate-50 rounded-xl p-4 mb-6">
            <p class="text-xs text-slate-400 font-semibold uppercase mb-2">Keluhan</p>
            <p class="text-sm text-slate-700">{{ $gangguan->keluhan }}</p>
        </div>

        {{-- Aksi --}}
        @if ($gangguan->status !== 'selesai')
            <form action="{{ route('pegawai.gangguan.update-status', $gangguan) }}" method="POST">
                @csrf
                @method('PATCH')
                @if ($gangguan->status === 'menunggu')
                    <input type="hidden" name="status" value="diproses">
                    <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                        Proses Pengaduan
                    </button>
                @else
                    <input type="hidden" name="status" value="selesai">
                    <button type="submit"
                            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                        Tandai Selesai
                    </button>
                @endif
            </form>
        @else
            <div class="bg-emerald-50 text-emerald-700 rounded-xl px-4 py-3 text-sm font-medium text-center">
                <i data-lucide="check-circle" class="w-4 h-4 inline-block mr-1"></i>
                Pengaduan telah selesai ditangani.
            </div>
        @endif
    </div>
</div>
@endsection

