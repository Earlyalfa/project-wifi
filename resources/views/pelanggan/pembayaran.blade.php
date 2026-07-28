@extends('layouts.pelanggan')

@section('title', 'Pembayaran')
@section('page-title', 'Pembayaran')

@section('content')
    {{-- ===================== DETAIL TAGIHAN ===================== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                <i data-lucide="file-text" class="w-4 h-4 text-indigo-600"></i>
            </div>
            <h3 class="font-semibold text-slate-800">Detail Tagihan</h3>
        </div>

        @if ($tagihan)
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <p class="text-xs text-slate-400 font-medium">Nomor Tagihan</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">INV-{{ str_pad($tagihan->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium">Bulan</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">{{ $tagihan->periode }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium">Nominal</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium">Kode Unik</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">{{ $kodeUnik }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium">Total Pembayaran</p>
                    <p class="text-base font-bold text-indigo-600 mt-1">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium">Jatuh Tempo</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">{{ $tagihan->jatuh_tempo ? \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d M Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium">Status</p>
                    <p class="text-sm font-semibold mt-1">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-600">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            Belum Dibayar
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-medium">Keterangan</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">Pembayaran WiFi {{ $tagihan->periode }}</p>
                </div>
            </div>

            {{-- Catatan Pembayaran --}}
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-3">
                <div class="flex items-start gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-amber-600 mt-0.5 shrink-0"></i>
                    <p class="text-sm text-amber-800">
                        <span class="font-semibold">Catatan Pembayaran:</span><br>
                        - Lakukan pembayaran sebelum tanggal jatuh tempo untuk menghindari denda.<br>
                        - Gunakan kode unik <strong>{{ $kodeUnik }}</strong> sebagai referensi pembayaran.<br>
                        - Total yang harus dibayarkan adalah <strong>Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</strong>.
                    </p>
                </div>
            </div>
        @else
            <div class="text-center py-8 text-slate-400">
                <i data-lucide="check-circle" class="w-12 h-12 mx-auto mb-3 text-emerald-400"></i>
                <p class="text-sm font-medium">Tidak ada tagihan yang perlu dibayar</p>
                <p class="text-xs mt-1">Semua tagihan Anda sudah lunas.</p>
            </div>
        @endif
    </div>

    {{-- ===================== PEMBAYARAN QRIS ===================== --}}
    @if ($tagihan)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                <i data-lucide="scan-qr-code" class="w-4 h-4 text-emerald-600"></i>
            </div>
            <h3 class="font-semibold text-slate-800">Pembayaran QRIS</h3>
        </div>

        <div class="flex flex-col items-center justify-center py-4">
            {{-- QRIS Placeholder --}}
            <div class="w-48 h-48 bg-white border-2 border-dashed border-slate-200 rounded-2xl flex flex-col items-center justify-center mb-4">
                <div class="text-center text-slate-300">
                    <i data-lucide="qr-code" class="w-16 h-16 mx-auto mb-2"></i>
                    <p class="text-xs font-medium">QRIS Merchant</p>
                </div>
            </div>

            <div class="text-center">
                <p class="text-sm font-semibold text-slate-800">Merchant WiFiPay</p>
                <p class="text-lg font-bold text-indigo-600 mt-1">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-400 mt-1">Scan QR di atas untuk melakukan pembayaran</p>
            </div>
        </div>
    </div>
    @endif

    {{-- ===================== RIWAYAT PEMBAYARAN ===================== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center">
                    <i data-lucide="clock" class="w-4 h-4 text-sky-600"></i>
                </div>
                <h3 class="font-semibold text-slate-800">Riwayat Pembayaran</h3>
            </div>
        </div>
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-slate-100">
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Tanggal</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Bulan</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Nominal</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Metode</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($riwayatPembayaran as $p)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 text-slate-600">{{ $p->dibayar_at ? \Carbon\Carbon::parse($p->dibayar_at)->format('d/m/Y') : '-' }}</td>
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $p->periode }}</td>
                            <td class="px-5 py-3 font-semibold text-slate-800">Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $p->metode_pembayaran ?? 'QRIS' }}</td>
                            <td class="px-5 py-3">
                                @if ($p->status === 'lunas')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-slate-500 text-xs">{{ $p->status === 'lunas' ? 'Pembayaran lunas' : 'Menunggu pembayaran' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada riwayat pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

