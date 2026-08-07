@extends('layouts.pelanggan')

@section('title', 'Detail Pengaduan')
@section('page-title', 'Detail Pengaduan')

@section('content')
    <div class="max-w-3xl mx-auto">
        {{-- Card Detail --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                        <i data-lucide="file-text" class="w-4 h-4 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Detail Pengaduan</h3>
                </div>
                <a href="{{ route('pelanggan.pengaduan') }}"
                   class="text-xs font-medium text-violet-600 hover:underline inline-flex items-center gap-1">
                </a>
            </div>

            <div class="space-y-4">
                {{-- Status --}}
                <div>
                    <p class="text-xs text-slate-400 font-medium">Status</p>
                    @php
                        $badge = match($pengaduan->status) {
                            'Menunggu' => 'bg-amber-100 text-amber-700',
                            'Diproses' => 'bg-blue-100 text-blue-700',
                            'Selesai'  => 'bg-emerald-100 text-emerald-700',
                            default    => 'bg-slate-100 text-slate-500',
                        };
                        $dot = match($pengaduan->status) {
                            'Menunggu' => 'bg-amber-500',
                            'Diproses' => 'bg-blue-500',
                            'Selesai'  => 'bg-emerald-500',
                            default    => 'bg-slate-500',
                        };
                    @endphp
                    <p class="mt-1">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
                            {{ $pengaduan->status }}
                        </span>
                    </p>
                </div>

                {{-- Jenis Gangguan --}}
                <div>
                    <p class="text-xs text-slate-400 font-medium">Jenis Gangguan</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">{{ $pengaduan->jenis_gangguan }}</p>
                </div>

                {{-- Tanggal --}}
                <div>
                    <p class="text-xs text-slate-400 font-medium">Tanggal Pengaduan</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">{{ $pengaduan->created_at->format('d M Y H:i') }}</p>
                </div>

                {{-- Judul Keluhan --}}
                <div>
                    <p class="text-xs text-slate-400 font-medium">Judul Keluhan</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">{{ $pengaduan->judul_keluhan }}</p>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <p class="text-xs text-slate-400 font-medium">Deskripsi Keluhan</p>
                    <p class="text-sm text-slate-700 mt-1 whitespace-pre-wrap">{{ $pengaduan->deskripsi }}</p>
                </div>

                {{-- Keterangan (dari admin/pegawai) --}}
                @if ($pengaduan->keterangan)
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-start gap-2">
                        <i data-lucide="message-square" class="w-4 h-4 text-blue-600 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs font-semibold text-blue-700">Keterangan</p>
                            <p class="text-sm text-blue-800 mt-1">{{ $pengaduan->keterangan }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Foto --}}
                @if ($pengaduan->foto)
                <div>
                    <p class="text-xs text-slate-400 font-medium mb-2">Foto Pendukung</p>
                    <img src="{{ asset('storage/' . $pengaduan->foto) }}"
                         alt="Foto Pengaduan"
                         class="w-full max-w-md rounded-xl border border-slate-200">
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

