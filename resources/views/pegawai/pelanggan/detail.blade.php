@extends('layouts.pegawai')

@section('title', 'Detail Pelanggan')
@section('page-title', 'Detail Pelanggan')

@section('content')
<div class="max-w-4xl mx-auto"
     x-data="{
        showFoto: false,
        fotoLoading: false,
        fotoUrl: null,
        fotoCaption: null,
        openFoto() {
            this.showFoto = true;
            this.fotoLoading = true;
            this.fotoUrl = null;
            fetch('{{ route('pegawai.pelanggan.foto', $pelanggan) }}')
                .then(res => res.json())
                .then(data => {
                    setTimeout(() => {
                        this.fotoUrl = data.url;
                        this.fotoCaption = data.diambil_saat;
                        this.fotoLoading = false;
                    }, 500);
                })
                .catch(() => { this.fotoLoading = false; });
        }
     }">

    <a href="{{ route('pegawai.pelanggan.index') }}" class="flex items-center gap-2 text-slate-500 text-sm mb-4 hover:underline">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali
    </a>

    <div class="grid lg:grid-cols-3 gap-5">
        {{-- KOLOM KIRI: Info Pelanggan --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- CARD INFO PELANGGAN --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-start gap-4 mb-5">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shrink-0 shadow-sm">
                        <i data-lucide="user" class="w-7 h-7 text-white"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="font-bold text-lg text-slate-800">{{ $pelanggan->nama }}</h2>
                                <p class="text-sm text-slate-400">{{ $pelanggan->kode }}</p>
                            </div>
                            @if ($tagihanTerbaru && $tagihanTerbaru->status === 'belum_bayar')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-600 ring-1 ring-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Belum Bayar
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-600 ring-1 ring-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Lunas
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="map-pin" class="w-4 h-4 text-indigo-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Alamat</p>
                            <p class="text-sm text-slate-800 font-medium">{{ $pelanggan->alamat ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="wifi" class="w-4 h-4 text-indigo-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Paket WiFi</p>
                            <p class="text-sm text-slate-800 font-medium">{{ $pelanggan->paket ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="phone" class="w-4 h-4 text-indigo-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">No. HP</p>
                            <p class="text-sm text-slate-800 font-medium">{{ $pelanggan->no_hp ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="calendar" class="w-4 h-4 text-indigo-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Tanggal Daftar</p>
                            <p class="text-sm text-slate-800 font-medium">{{ $pelanggan->created_at->format('d F Y') }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="circle-dollar-sign" class="w-4 h-4 text-indigo-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Status Pelanggan</p>
                            <p class="text-sm">
                                @if ($pelanggan->status === 'aktif')
                                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 font-semibold text-slate-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="receipt" class="w-4 h-4 text-indigo-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Tagihan Bulan Ini</p>
                            @if ($tagihanTerbaru)
                                <p class="text-sm text-slate-800 font-medium">Rp {{ number_format($tagihanTerbaru->jumlah, 0, ',', '.') }}</p>
                                <p class="text-xs text-slate-400">Jatuh tempo: {{ optional($tagihanTerbaru->jatuh_tempo)->format('d M Y') }}</p>
                            @else
                                <p class="text-sm text-slate-800 font-medium">-</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIWAYAT PEMBAYARAN --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                            <i data-lucide="credit-card" class="w-4 h-4 text-emerald-600"></i>
                        </div>
                        <h3 class="font-semibold text-slate-800">Riwayat Pembayaran</h3>
                    </div>
                </div>

                <div class="overflow-x-auto -mx-5">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-slate-100">
                                <th class="px-5 py-2.5 font-semibold text-slate-400 text-xs uppercase">Periode</th>
                                <th class="px-5 py-2.5 font-semibold text-slate-400 text-xs uppercase">Jatuh Tempo</th>
                                <th class="px-5 py-2.5 font-semibold text-slate-400 text-xs uppercase">Jumlah</th>
                                <th class="px-5 py-2.5 font-semibold text-slate-400 text-xs uppercase">Status</th>
                                <th class="px-5 py-2.5 font-semibold text-slate-400 text-xs uppercase">Dibayar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($pelanggan->pembayarans as $bayar)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-5 py-3 font-medium text-slate-800">{{ $bayar->periode ?? '-' }}</td>
                                    <td class="px-5 py-3 text-slate-500">{{ optional($bayar->jatuh_tempo)->format('d M Y') ?? '-' }}</td>
                                    <td class="px-5 py-3 font-semibold text-slate-800">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3">
                                        @if ($bayar->status === 'lunas')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                                Lunas
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-600">
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-slate-500">{{ $bayar->dibayar_at ? $bayar->dibayar_at->format('d M Y H:i') : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-6 text-center text-slate-400">Belum ada riwayat pembayaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Aksi --}}
        <div class="space-y-4">
            {{-- Status Pembayaran --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <i data-lucide="circle-dollar-sign" class="w-4 h-4 text-indigo-600"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Status Pembayaran</h3>
                </div>
                @if ($tagihanTerbaru && $tagihanTerbaru->status === 'belum_bayar')
                    <div class="bg-rose-50 rounded-xl p-4 text-center mb-3">
                        <i data-lucide="alert-circle" class="w-8 h-8 text-rose-500 mx-auto mb-2"></i>
                        <p class="text-sm font-semibold text-rose-700">Belum Bayar</p>
                        <p class="text-xs text-rose-500 mt-1">Tagihan Rp {{ number_format($tagihanTerbaru->jumlah, 0, ',', '.') }}</p>
                    </div>
                @else
                    <div class="bg-emerald-50 rounded-xl p-4 text-center mb-3">
                        <i data-lucide="check-circle" class="w-8 h-8 text-emerald-500 mx-auto mb-2"></i>
                        <p class="text-sm font-semibold text-emerald-700">Lunas</p>
                        <p class="text-xs text-emerald-500 mt-1">Tidak ada tagihan tertunggak</p>
                    </div>
                @endif
            </div>

            {{-- Tombol Aksi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 space-y-3">
                <h3 class="font-semibold text-slate-800 mb-2">Aksi</h3>

                <button type="button" @click="openFoto()"
                        class="w-full flex items-center justify-center gap-2 border border-indigo-500 text-indigo-600 font-semibold py-2.5 rounded-lg hover:bg-indigo-50 transition-colors text-sm">
                    <i data-lucide="camera" class="w-4 h-4"></i>
                    Lihat Foto Rumah
                </button>

                <a href="{{ route('pegawai.pelanggan.show', $pelanggan) . '?scan=1' }}"
                   class="w-full flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm"
                   onclick="event.preventDefault(); window.location.href='{{ route('pegawai.scan-barcode') }}?kode={{ $pelanggan->kode }}';">
                    <i data-lucide="scan-qr-code" class="w-4 h-4"></i>
                    Scan QR Code
                </a>

                @if ($tagihanTerbaru && $tagihanTerbaru->status === 'belum_bayar')
                    <form action="{{ route('pegawai.pelanggan.konfirmasi-pembayaran', $pelanggan) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm"
                                onclick="return confirm('Konfirmasi pembayaran untuk {{ $pelanggan->nama }}?')">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            Konfirmasi Pembayaran
                        </button>
                    </form>
                @endif

                <a href="{{ route('pegawai.pelanggan.edit', $pelanggan) }}"
                   class="w-full flex items-center justify-center gap-2 border border-slate-200 text-slate-600 font-semibold py-2.5 rounded-lg hover:bg-slate-50 transition-colors text-sm">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                    Edit Pelanggan
                </a>
            </div>
        </div>
    </div>

    {{-- MODAL FOTO RUMAH --}}
    <div x-show="showFoto" x-cloak
         class="fixed inset-0 bg-black/50 flex items-center justify-center p-6 z-50"
         @click.self="showFoto = false">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-slate-800">Foto Rumah</h3>
                <button type="button" @click="showFoto = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            {{-- LOADING --}}
            <div x-show="fotoLoading" class="flex flex-col items-center justify-center py-10 gap-3">
                <div class="w-10 h-10 border-4 border-emerald-100 border-t-emerald-500 rounded-full animate-spin"></div>
                <p class="text-sm text-slate-400">Memuat foto...</p>
            </div>

            {{-- FOTO SIAP --}}
            <div x-show="!fotoLoading && fotoUrl" x-cloak>
                <img :src="fotoUrl" class="w-full h-64 object-cover rounded-lg mb-3">
                <p class="text-xs text-slate-400 mb-4" x-text="fotoCaption"></p>
            </div>

            {{-- TIDAK ADA FOTO --}}
            <div x-show="!fotoLoading && !fotoUrl" x-cloak class="py-10 text-center">
                <i data-lucide="image-off" class="w-10 h-10 text-slate-300 mx-auto mb-3"></i>
                <p class="text-sm text-slate-400">Belum ada foto rumah untuk pelanggan ini.</p>
            </div>

            <button type="button" @click="showFoto = false"
                    class="w-full border border-emerald-500 text-emerald-600 font-semibold py-2.5 rounded-lg hover:bg-emerald-50 transition-colors mt-2">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

