@extends('layouts.admin')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Transaksi Laporan')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-slate-400 mb-6">
        <a href="{{ route('admin.laporan') }}" class="hover:text-[#7C3AED] transition-colors">Laporan</a>
        <span class="text-slate-300">/</span>
        <span class="text-slate-700 font-medium">Detail Transaksi</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-violet-100 flex items-center justify-center">
                <i data-lucide="file-text" class="w-5 h-5 text-violet-600"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Detail Transaksi</h2>
                <p class="text-sm text-slate-400 font-mono">#{{ str_pad($pembayaran->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>
        <a href="{{ route('admin.laporan') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-violet-600 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Laporan
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- KOLOM KIRI: Info Pembayaran --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- CARD INFO PEMBAYARAN --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                        <i data-lucide="receipt" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Informasi Pembayaran</h3>
                        <p class="text-xs text-slate-400">Detail transaksi pembayaran WiFi</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <p class="text-xs text-slate-400 font-medium">Nomor Transaksi</p>
                        <p class="text-sm font-mono font-bold text-violet-600 mt-1">#{{ str_pad($pembayaran->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <p class="text-xs text-slate-400 font-medium">Periode</p>
                        <p class="text-sm font-semibold text-slate-800 mt-1">{{ $pembayaran->periode ?? '-' }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <p class="text-xs text-slate-400 font-medium">Tanggal Dibuat</p>
                        <p class="text-sm font-semibold text-slate-800 mt-1">{{ $pembayaran->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <p class="text-xs text-slate-400 font-medium">Jatuh Tempo</p>
                        <p class="text-sm font-semibold text-slate-800 mt-1">{{ $pembayaran->jatuh_tempo ? \Carbon\Carbon::parse($pembayaran->jatuh_tempo)->format('d M Y') : '-' }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <p class="text-xs text-slate-400 font-medium">Nominal Tagihan</p>
                        <p class="text-sm font-bold text-slate-800 mt-1">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-violet-50 rounded-xl p-3.5 border border-violet-100">
                        <p class="text-xs text-violet-500 font-medium">Total Dibayar</p>
                        <p class="text-lg font-bold text-violet-700 mt-1">Rp {{ number_format($pembayaran->total_bayar ?? $pembayaran->jumlah, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <p class="text-xs text-slate-400 font-medium">Metode Pembayaran</p>
                        <p class="text-sm mt-1">
                            @php
                                $metode = strtolower($pembayaran->metode_pembayaran ?? '');
                                $badgeMetode = match($metode) {
                                    'tunai' => 'bg-amber-100 text-amber-700',
                                    'qris' => 'bg-sky-100 text-sky-700',
                                    'transfer' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-slate-100 text-slate-600',
                                };
                                $labelMetode = match($metode) {
                                    'tunai' => 'Tunai',
                                    'qris' => 'QRIS',
                                    'transfer' => 'Transfer',
                                    default => ucfirst($pembayaran->metode_pembayaran ?? '-'),
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeMetode }}">
                                {{ $labelMetode }}
                            </span>
                        </p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <p class="text-xs text-slate-400 font-medium">Tanggal Bayar</p>
                        <p class="text-sm font-medium text-slate-800 mt-1">
                            {{ $pembayaran->tanggal_bayar ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y') : ($pembayaran->dibayar_at ? $pembayaran->dibayar_at->format('d M Y H:i') : '-') }}
                        </p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <p class="text-xs text-slate-400 font-medium">Status</p>
                        <p class="text-sm mt-1">
                            @php
                                $sBadge = match($pembayaran->status) {
                                    'lunas' => 'bg-emerald-100 text-emerald-700',
                                    'menunggu_verifikasi' => 'bg-amber-100 text-amber-700',
                                    'menunggu_penagihan' => 'bg-sky-100 text-sky-700',
                                    'ditolak' => 'bg-rose-100 text-rose-700',
                                    'belum_bayar' => 'bg-rose-100 text-rose-600',
                                    'dibatalkan' => 'bg-slate-100 text-slate-500',
                                    default => 'bg-slate-100 text-slate-600',
                                };
                                $sLabel = match($pembayaran->status) {
                                    'lunas' => 'Lunas',
                                    'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                    'menunggu_penagihan' => 'Menunggu Penagihan',
                                    'ditolak' => 'Ditolak',
                                    'belum_bayar' => 'Belum Dibayar',
                                    'dibatalkan' => 'Dibatalkan',
                                    default => ucfirst($pembayaran->status),
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $sBadge }}">
                                <span class="w-1.5 h-1.5 rounded-full"></span>
                                {{ $sLabel }}
                            </span>
                        </p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <p class="text-xs text-slate-400 font-medium">Diterima Oleh</p>
                        <p class="text-sm font-medium text-slate-800 mt-1">
                            @if ($pembayaran->diterima_oleh)
                                {{ $pembayaran->diterima_oleh }}
                                @if ($pembayaran->penerima)
                                    <span class="text-xs text-slate-400">({{ $pembayaran->penerima->name ?? $pembayaran->penerima->email ?? '' }})</span>
                                @endif
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>

                @if ($pembayaran->catatan)
                    <div class="mt-4 bg-amber-50 rounded-xl p-4">
                        <div class="flex items-start gap-2">
                            <i data-lucide="sticky-note" class="w-4 h-4 text-amber-600 mt-0.5 shrink-0"></i>
                            <div>
                                <p class="text-xs font-semibold text-amber-700 mb-1">Catatan</p>
                                <p class="text-sm text-amber-800">{{ $pembayaran->catatan }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- CARD INFO PELANGGAN --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <i data-lucide="user" class="w-5 h-5 text-emerald-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Informasi Pelanggan</h3>
                        <p class="text-xs text-slate-400">Data pelanggan terkait transaksi</p>
                    </div>
                </div>

                @if ($pembayaran->pelanggan)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-slate-50 rounded-xl p-3.5">
                            <p class="text-xs text-slate-400 font-medium">Nama</p>
                            <p class="text-sm font-semibold text-slate-800 mt-1">{{ $pembayaran->pelanggan->nama ?? '-' }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3.5">
                            <p class="text-xs text-slate-400 font-medium">Kode Pelanggan</p>
                            <p class="text-sm font-mono font-bold text-violet-600 mt-1">{{ $pembayaran->pelanggan->kode ?? '-' }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3.5">
                            <p class="text-xs text-slate-400 font-medium">Alamat</p>
                            <p class="text-sm text-slate-800 mt-1">{{ $pembayaran->pelanggan->alamat ?? '-' }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3.5">
                            <p class="text-xs text-slate-400 font-medium">No. HP</p>
                            <p class="text-sm text-slate-800 mt-1">{{ $pembayaran->pelanggan->no_hp ?? '-' }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3.5">
                            <p class="text-xs text-slate-400 font-medium">Paket WiFi</p>
                            <p class="text-sm text-slate-800 mt-1">{{ $pembayaran->pelanggan->paket ?? '-' }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3.5">
                            <p class="text-xs text-slate-400 font-medium">Status Pelanggan</p>
                            <p class="text-sm mt-1">
                                @if ($pembayaran->pelanggan->status === 'aktif')
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
                @else
                    <div class="text-center py-8 text-slate-400">
                        <i data-lucide="user-x" class="w-10 h-10 mx-auto mb-2"></i>
                        <p class="text-sm">Data pelanggan tidak ditemukan.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- KOLOM KANAN: Bukti Pembayaran --}}
        <div class="space-y-6">
            {{-- CARD BUKTI BAYAR --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center">
                        <i data-lucide="image" class="w-5 h-5 text-sky-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Bukti Pembayaran</h3>
                        <p class="text-xs text-slate-400">Bukti transaksi dari pelanggan</p>
                    </div>
                </div>

                @if ($pembayaran->bukti_bayar)
                    @php
                        $isImage = in_array(pathinfo($pembayaran->bukti_bayar, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']);
                    @endphp
                    <div class="bg-slate-50 rounded-xl overflow-hidden mb-3">
                        @if ($isImage)
                            <a href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" target="_blank">
                                <img src="{{ asset('storage/' . $pembayaran->bukti_bayar) }}"
                                     alt="Bukti Pembayaran"
                                     class="w-full h-48 object-cover hover:opacity-90 transition-opacity cursor-pointer">
                            </a>
                        @else
                            <div class="flex flex-col items-center justify-center py-10 text-slate-400">
                                <i data-lucide="file-text" class="w-10 h-10 mb-2"></i>
                                <p class="text-sm font-medium">File PDF</p>
                                <a href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" target="_blank"
                                   class="mt-2 text-sm text-violet-600 hover:underline font-medium">
                                    Lihat File
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="text-center">
                        <a href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" target="_blank"
                           class="inline-flex items-center gap-2 text-sm text-violet-600 hover:underline font-medium">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Download Bukti Bayar
                        </a>
                    </div>
                @else
                    <div class="text-center py-8 text-slate-400">
                        <i data-lucide="image-off" class="w-10 h-10 mx-auto mb-2"></i>
                        <p class="text-sm">Tidak ada bukti pembayaran</p>
                    </div>
                @endif
            </div>

            {{-- CARD INFO RINGKAS --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <i data-lucide="info" class="w-5 h-5 text-indigo-600"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Ringkasan</h3>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Nominal</span>
                        <span class="font-semibold text-slate-800">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Total Bayar</span>
                        <span class="font-semibold text-slate-800">Rp {{ number_format($pembayaran->total_bayar ?? $pembayaran->jumlah, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between border-t border-slate-100 pt-3">
                        <span class="text-slate-400">Kembalian</span>
                        <span class="font-semibold text-emerald-600">
                            Rp {{ number_format(max(0, ($pembayaran->total_bayar ?? $pembayaran->jumlah) - $pembayaran->jumlah), 0, ',', '.') }}
                        </span>
                    </div>
                    @if ($pembayaran->status === 'lunas')
                        <div class="bg-emerald-50 rounded-xl p-3 text-center mt-2">
                            <i data-lucide="check-circle" class="w-6 h-6 text-emerald-500 mx-auto mb-1"></i>
                            <p class="text-sm font-semibold text-emerald-700">Pembayaran Lunas</p>
                            @if ($pembayaran->dibayar_at)
                                <p class="text-xs text-emerald-500 mt-1">{{ $pembayaran->dibayar_at->format('d M Y H:i') }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

