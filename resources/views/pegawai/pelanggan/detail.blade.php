@extends('layouts.pegawai')

@section('title', 'Detail Pelanggan')
@section('page-title', 'Detail Pelanggan')

@section('content')
<div class="max-w-5xl mx-auto" x-data="{ showFoto: false, fotoLoading: false, fotoUrl: null, fotoCaption: null, openFoto() { this.showFoto = true; this.fotoLoading = true; this.fotoUrl = null; fetch('{{ route('pegawai.pelanggan.foto', $pelanggan) }}').then(res => res.json()).then(data => { setTimeout(() => { this.fotoUrl = data.url; this.fotoCaption = data.diambil_saat; this.fotoLoading = false; }, 500); }).catch(() => { this.fotoLoading = false; }); } }">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-slate-400 mb-6">
        <a href="{{ route('pegawai.pelanggan.index') }}" class="hover:text-[#7C3AED] transition-colors">Data Pelanggan</a>
        <span class="text-slate-300">/</span>
        <span class="text-slate-700 font-medium">Detail Pelanggan</span>
    </nav>

    {{-- CARD ATAS: Header Profil --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 p-6 md:p-8 mb-6">
        <div class="flex flex-col md:flex-row md:items-center gap-5">
            {{-- Avatar --}}
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-full bg-[#7C3AED]/10 flex items-center justify-center shrink-0">
                <span class="text-xl md:text-2xl font-bold text-[#7C3AED]">{{ strtoupper(substr($pelanggan->nama, 0, 1)) }}</span>
            </div>

            {{-- Info Nama & Status --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-xl md:text-2xl font-bold text-slate-800 truncate">{{ $pelanggan->nama }}</h2>
                    @if ($pelanggan->status === 'aktif')
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                            Nonaktif
                        </span>
                    @endif
                </div>
                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                    <span class="text-sm font-mono font-semibold text-slate-500">{{ $pelanggan->kode }}</span>
                </div>
            </div>

            {{-- Aksi --}}
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('pegawai.pelanggan.edit', $pelanggan) }}"
                   class="inline-flex items-center px-4 py-2 bg-[#7C3AED] hover:bg-[#6D28D9] text-white text-sm font-semibold rounded-xl transition-all gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </div>

    {{-- ROW 2 KOLOM: Informasi Pelanggan & QR Code --}}
    <div class="grid lg:grid-cols-3 gap-6 mb-6">
        {{-- KOLOM KIRI: Informasi Pelanggan --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/60 p-6 md:p-8">
            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-6">Informasi Pelanggan</h3>
            <div class="space-y-5">
                <div class="flex items-start gap-4">
                    <span class="text-sm text-slate-500 w-24 shrink-0">Nama</span>
                    <span class="text-sm font-medium text-slate-800">: {{ $pelanggan->nama ?? '-' }}</span>
                </div>
                <div class="flex items-start gap-4">
                    <span class="text-sm text-slate-500 w-24 shrink-0">No. HP</span>
                    <span class="text-sm font-medium text-slate-800">: {{ $pelanggan->no_hp ?? '-' }}</span>
                </div>
                <div class="flex items-start gap-4">
                    <span class="text-sm text-slate-500 w-24 shrink-0">Alamat</span>
                    <span class="text-sm font-medium text-slate-800">: {{ $pelanggan->alamat ?? '-' }}</span>
                </div>
                <div class="flex items-start gap-4">
                    <span class="text-sm text-slate-500 w-24 shrink-0">Bergabung</span>
                    <span class="text-sm font-medium text-slate-800">: {{ $pelanggan->created_at->format('d F Y') }}</span>
                </div>
                <div class="flex items-start gap-4">
                    <span class="text-sm text-slate-500 w-24 shrink-0">Paket WiFi</span>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">{{ $pelanggan->paket ?? '-' }}</span>
                </div>
                <div class="flex items-start gap-4">
                    <span class="text-sm text-slate-500 w-24 shrink-0">Status</span>
                    <span class="text-sm font-medium text-slate-800">:
                        @if ($pelanggan->status === 'aktif')
                            <span class="inline-flex items-center gap-1.5 text-emerald-600">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-slate-400">
                                <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                                Nonaktif
                            </span>
                        @endif
                    </span>
                </div>
                <div class="flex items-start gap-4">
                    <span class="text-sm text-slate-500 w-24 shrink-0">Foto Rumah</span>
                    <span class="text-sm font-medium text-slate-800">:
                        <button type="button" @click="openFoto()"
                                class="inline-flex items-center gap-1.5 text-[#7C3AED] hover:text-[#6D28D9] font-semibold transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat Foto
                        </button>
                    </span>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: QR Code --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 md:p-8 text-center">
            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-5 flex items-center gap-2 justify-center">
                <svg class="w-4 h-4 text-[#7C3AED]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                QR Code
            </h3>
            <div class="inline-block p-3 rounded-xl border border-slate-100 mx-auto">
                <canvas id="qr-detail-canvas"></canvas>
            </div>
            <p class="text-xs text-slate-400 mt-2.5 font-mono font-bold text-[#7C3AED]">{{ $pelanggan->kode }}</p>
            <div class="mt-5 flex gap-2 justify-center">
                <button type="button" onclick="downloadQRDetail()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#7C3AED] hover:bg-[#6D28D9] text-white text-xs font-semibold rounded-xl transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download
                </button>
                <button type="button" onclick="printQRDetail()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl hover:bg-slate-50 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak
                </button>
            </div>
        </div>
    </div>

    {{-- RIWAYAT PEMBAYARAN --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
        <div class="px-6 md:px-8 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-800">Riwayat Pembayaran</h3>
            <a href="#" class="text-xs font-medium text-[#7C3AED] hover:text-[#6D28D9] transition-colors">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-50">
                        <th class="px-6 md:px-8 py-3.5 text-left font-medium text-slate-400 text-xs uppercase tracking-wider">Periode</th>
                        <th class="px-6 md:px-8 py-3.5 text-left font-medium text-slate-400 text-xs uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 md:px-8 py-3.5 text-left font-medium text-slate-400 text-xs uppercase tracking-wider">Status</th>
                        <th class="px-6 md:px-8 py-3.5 text-left font-medium text-slate-400 text-xs uppercase tracking-wider">Dibayar Pada</th>
                        <th class="px-6 md:px-8 py-3.5 text-left font-medium text-slate-400 text-xs uppercase tracking-wider">Bukti</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($pelanggan->pembayarans as $bayar)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 md:px-8 py-4 text-sm font-medium text-slate-800">{{ $bayar->periode ?? '-' }}</td>
                            <td class="px-6 md:px-8 py-4 text-sm font-semibold text-slate-800">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                            <td class="px-6 md:px-8 py-4">
                                @if ($bayar->status === 'lunas')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 md:px-8 py-4 text-sm text-slate-500">{{ $bayar->dibayar_at ? $bayar->dibayar_at->format('d M Y') : '-' }}</td>
                            <td class="px-6 md:px-8 py-4">
                                <a href="#" class="p-1.5 rounded-lg text-slate-400 hover:text-[#7C3AED] hover:bg-[#7C3AED]/5 inline-block transition-colors" title="Lihat Bukti">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 md:px-8 py-12 text-center">
                                <p class="text-sm text-slate-400">Belum ada riwayat pembayaran.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL FOTO RUMAH --}}
    <div x-show="showFoto" x-cloak
         class="fixed inset-0 bg-black/50 flex items-center justify-center p-6 z-50"
         @click.self="showFoto = false">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-slate-800">Foto Rumah</h3>
                <button type="button" @click="showFoto = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- LOADING --}}
            <div x-show="fotoLoading" class="flex flex-col items-center justify-center py-10 gap-3">
                <div class="w-10 h-10 border-4 border-violet-100 border-t-[#7C3AED] rounded-full animate-spin"></div>
                <p class="text-sm text-slate-400">Memuat foto...</p>
            </div>

            {{-- FOTO SIAP --}}
            <div x-show="!fotoLoading && fotoUrl" x-cloak>
                <img :src="fotoUrl" class="w-full h-64 object-cover rounded-xl mb-3">
                <p class="text-xs text-slate-400 mb-4" x-text="fotoCaption"></p>
            </div>

            {{-- TIDAK ADA FOTO --}}
            <div x-show="!fotoLoading && !fotoUrl" x-cloak class="py-10 text-center">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-slate-400">Belum ada foto rumah untuk pelanggan ini.</p>
            </div>

            <button type="button" @click="showFoto = false"
                    class="w-full border border-slate-200 text-slate-600 font-semibold py-2.5 rounded-xl hover:bg-slate-50 transition-all mt-2">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('qr-detail-canvas');
    const kode = '{{ $pelanggan->kode }}';
    if (canvas && kode) {
        QRCode.toCanvas(canvas, kode, { 
            width: 150,
            height: 150,
            margin: 2,
            color: {
                dark: '#1a1a2e',
                light: '#ffffff'
            }
        }, function (error) {
            if (error) console.error(error);
        });
    }
});

function downloadQRDetail() {
    const canvas = document.getElementById('qr-detail-canvas');
    const kode = '{{ $pelanggan->kode }}';
    const link = document.createElement('a');
    link.download = 'QR-' + kode + '.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}

function printQRDetail() {
    const canvas = document.getElementById('qr-detail-canvas');
    const kode = '{{ $pelanggan->kode }}';
    const dataUrl = canvas.toDataURL('image/png');
    
    const win = window.open('', '_blank');
    win.document.write(`
        <html>
        <head>
            <title>Cetak QR - ${kode}</title>
            <style>
                body { display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; background: #fff; }
                .container { text-align: center; padding: 20px; }
                img { width: 250px; height: 250px; }
                h2 { margin: 15px 0 5px; color: #1a1a2e; }
                p { color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class="container">
                <img src="${dataUrl}" alt="QR ${kode}">
                <h2>WiFiPay</h2>
                <p>Kode Pelanggan: <strong>${kode}</strong></p>
            </div>
            <script>window.onload = function() { window.print(); } <\/script>
        </body>
        </html>
    `);
    win.document.close();
}
</script>
@endpush

