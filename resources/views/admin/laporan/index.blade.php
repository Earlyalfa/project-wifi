@extends('layouts.admin')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')
{{-- ===================== CARD FILTER ===================== --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
    <form action="{{ route('admin.laporan') }}" method="GET" class="space-y-4">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                <i data-lucide="filter" class="w-4 h-4 text-violet-600"></i>
            </div>
            <h3 class="font-semibold text-slate-800">Filter Laporan</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Periode Date Range --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ $dari }}"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ $sampai }}"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
            </div>

            {{-- Jenis Laporan --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Laporan</label>
                <select name="jenis_laporan"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                    <option value="semua" {{ $jenis_laporan === 'semua' ? 'selected' : '' }}>Semua Transaksi</option>
                    <option value="pembayaran" {{ $jenis_laporan === 'pembayaran' ? 'selected' : '' }}>Pembayaran</option>
                    <option value="tunggakan" {{ $jenis_laporan === 'tunggakan' ? 'selected' : '' }}>Tunggakan</option>
                    <option value="lunas" {{ $jenis_laporan === 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>

            {{-- Status Pembayaran --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Status Pembayaran</label>
                <select name="status_pembayaran"
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                    <option value="semua" {{ $status_pembayaran === 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="lunas" {{ $status_pembayaran === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="belum_bayar" {{ $status_pembayaran === 'belum_bayar' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="dibatalkan" {{ $status_pembayaran === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex flex-wrap items-center gap-3 pt-2">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition-colors">
                <i data-lucide="eye" class="w-4 h-4"></i>
                Tampilkan
            </button>
            <a href="{{ route('admin.laporan') }}"
               class="inline-flex items-center gap-2 border border-slate-200 text-slate-600 font-semibold px-5 py-2.5 rounded-lg text-sm hover:bg-slate-50 transition-colors">
                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                Reset
            </a>
            <div class="flex-1"></div>
            <a href="{{ route('admin.laporan.export-pdf', request()->query()) }}"
               class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition-colors">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                Export PDF
            </a>
            <a href="{{ route('admin.laporan.export-excel', request()->query()) }}"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition-colors">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                Export Excel
            </a>
        </div>
    </form>
</div>

{{-- ===================== KARTU STATISTIK ===================== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
            <i data-lucide="trending-up" class="w-5 h-5 text-emerald-600"></i>
        </div>
        <p class="text-xs text-slate-400 font-medium">Total Pendapatan</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center mb-3">
            <i data-lucide="wallet" class="w-5 h-5 text-amber-600"></i>
        </div>
        <p class="text-xs text-slate-400 font-medium">Total Pembayaran Tunai</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($totalTunai, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center mb-3">
            <i data-lucide="smartphone" class="w-5 h-5 text-sky-600"></i>
        </div>
        <p class="text-xs text-slate-400 font-medium">Total Pembayaran QRIS</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($totalQris, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center mb-3">
            <i data-lucide="message-square-warning" class="w-5 h-5 text-rose-600"></i>
        </div>
        <p class="text-xs text-slate-400 font-medium">Total Pengaduan</p>
        <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalPengaduan }}</p>
    </div>
</div>

{{-- ===================== TABEL LAPORAN ===================== --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                <i data-lucide="file-text" class="w-4 h-4 text-violet-600"></i>
            </div>
            <h3 class="font-semibold text-slate-800">Data Pembayaran</h3>
        </div>
        <span class="text-xs text-slate-400 font-medium">Menampilkan {{ $pembayaranList->count() }} transaksi</span>
    </div>

    <div class="overflow-x-auto -mx-5">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-slate-100">
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase w-12">No</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Tanggal</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">ID Transaksi</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Nama Pelanggan</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Paket WiFi</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Metode</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Nominal</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Pegawai</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($pembayaranList as $index => $bayar)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-3 text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $bayar->created_at->format('d/m/Y') }}</td>
                        <td class="px-5 py-3">
                            <span class="font-mono text-xs font-semibold text-violet-600 bg-violet-50 px-2 py-1 rounded-lg">
                                #{{ str_pad($bayar->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $bayar->pelanggan->nama ?? '-' }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $bayar->pelanggan->paket ?? '-' }}</td>
                        <td class="px-5 py-3">
                            @if ($bayar->metode_pembayaran === 'tunai')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                    <i data-lucide="wallet" class="w-3 h-3"></i>
                                    Tunai
                                </span>
                            @elseif ($bayar->metode_pembayaran === 'qris')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-100 text-sky-700">
                                    <i data-lucide="smartphone" class="w-3 h-3"></i>
                                    QRIS
                                </span>
                            @elseif ($bayar->metode_pembayaran === 'transfer')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                    <i data-lucide="building" class="w-3 h-3"></i>
                                    Transfer
                                </span>
                            @else
                                <span class="text-xs text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 font-semibold text-slate-800">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                        <td class="px-5 py-3">
                            @if ($bayar->status === 'lunas')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Lunas
                                </span>
                            @elseif ($bayar->status === 'belum_bayar')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Belum Dibayar
                                </span>
                            @elseif ($bayar->status === 'dibatalkan')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Dibatalkan
                                </span>
                            @else
                                <span class="text-xs text-slate-400">{{ $bayar->status }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-slate-600">{{ $bayar->catatan ?? '-' }}</td>
                        <td class="px-5 py-3 text-center">
                            <a href="{{ route('admin.users.show', $bayar->pelanggan_id ?? 0) }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold text-violet-600 hover:text-violet-800 hover:underline">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center text-slate-400">
                                <i data-lucide="file-x" class="w-10 h-10 mb-2"></i>
                                <p class="text-sm font-medium">Tidak ada data laporan</p>
                                <p class="text-xs mt-1">Coba ubah periode atau filter lainnya.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
