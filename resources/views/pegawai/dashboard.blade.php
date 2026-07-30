@extends('layouts.pegawai')

@section('title', 'Dashboard Pegawai')
@section('page-title', 'Dashboard')

@section('content')
    {{-- ===================== CARD RINGKASAN (4 Card) ===================== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                <i data-lucide="users" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Total Pelanggan</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalPelanggan }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center mb-3">
                <i data-lucide="clipboard-list" class="w-5 h-5 text-rose-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Belum Bayar</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $belumBayarCount }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center mb-3">
                <i data-lucide="triangle-alert" class="w-5 h-5 text-amber-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Gangguan Baru</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $gangguanHariIni }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center mb-3">
                <i data-lucide="credit-card" class="w-5 h-5 text-sky-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Pembayaran Berhasil Hari Ini</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $pembayaranHariIni }}</p>
        </div>
    </div>

    {{-- ===================== ROW 2 KOLOM ===================== --}}
    <div class="grid lg:grid-cols-2 gap-6 mb-6">

        {{-- === SECTION 1: DAFTAR PELANGGAN BELUM BAYAR === --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center">
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Pelanggan Belum Bayar</h3>
                </div>
                <a href="{{ route('pegawai.pelanggan.index') }}" class="text-xs text-blue-600 font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto -mx-5">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-slate-100">
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Nama Pelanggan</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Paket WiFi</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Jatuh Tempo</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Tagihan</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($daftarBelumBayar as $tagihan)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3">
                                    <p class="font-medium text-slate-800">{{ $tagihan->pelanggan->nama }}</p>
                                    <p class="text-xs text-slate-400">{{ $tagihan->pelanggan->kode }}</p>
                                </td>
                                <td class="px-5 py-3 text-slate-600">{{ $tagihan->pelanggan->paket ?? '-' }}</td>
                                <td class="px-5 py-3 text-slate-600">{{ $tagihan->jatuh_tempo ? $tagihan->jatuh_tempo->format('d M Y') : '-' }}</td>
                                <td class="px-5 py-3 font-semibold text-slate-800">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Belum Bayar
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <a href="{{ route('pegawai.pelanggan.show', $tagihan->pelanggan) }}"
                                       class="inline-flex items-center gap-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                        <i data-lucide="scan-qr-code" class="w-3.5 h-3.5"></i>
                                        Scan QR
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-400">Semua tagihan sudah dibayar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- === SECTION 2: PENGADUAN GANGGUAN TERBARU === --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                        <i data-lucide="message-square-warning" class="w-4 h-4 text-amber-600"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Pengaduan Gangguan Terbaru</h3>
                </div>
                <a href="#" class="text-xs text-blue-600 font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto -mx-5">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-slate-100">
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Nama Pelanggan</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Keluhan</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Waktu Laporan</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($gangguanTerbaru as $g)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3">
                                    <p class="font-medium text-slate-800">{{ $g->pelanggan_nama }}</p>
                                    <p class="text-xs text-slate-400">{{ $g->pelanggan_kode }}</p>
                                </td>
                                <td class="px-5 py-3 text-slate-600 max-w-[160px] truncate">{{ $g->keluhan }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ $g->waktu_laporan->diffForHumans() }}</td>
                                <td class="px-5 py-3">
                                    @php
                                        $badgeColor = match($g->status) {
                                            'menunggu' => 'bg-amber-100 text-amber-700',
                                            'diproses' => 'bg-blue-100 text-blue-700',
                                            'selesai'  => 'bg-emerald-100 text-emerald-700',
                                            default    => 'bg-slate-100 text-slate-500',
                                        };
                                        $badgeLabel = ucfirst($g->status);
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                        <span class="w-1.5 h-1.5 rounded-full
                                            {{ $g->status === 'menunggu' ? 'bg-amber-500' : ($g->status === 'diproses' ? 'bg-blue-500' : 'bg-emerald-500') }}"></span>
                                        {{ $badgeLabel }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <a href="#"
                                       class="inline-flex items-center gap-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400">Tidak ada pengaduan gangguan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===================== ROW SEBELUM BAWAH ===================== --}}
    <div class="grid lg:grid-cols-2 gap-6 mb-6">

        {{-- === SECTION 3: AKTIVITAS HARI INI === --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                    <i data-lucide="activity" class="w-4 h-4 text-violet-600"></i>
                </div>
                <h3 class="font-semibold text-slate-800">Aktivitas Hari Ini</h3>
            </div>
            <div class="relative pl-6 border-l-2 border-slate-100 space-y-5">
                @forelse ($aktivitasHariIni as $aktivitas)
                    @php
                        $dotColor = match($aktivitas->color) {
                            'emerald' => 'bg-emerald-500',
                            'amber'   => 'bg-amber-500',
                            'blue'    => 'bg-blue-500',
                            'slate'   => 'bg-slate-300',
                            default   => 'bg-violet-600',
                        };
                        $iconColor = match($aktivitas->color) {
                            'emerald' => 'text-emerald-600',
                            'amber'   => 'text-amber-600',
                            'blue'    => 'text-blue-600',
                            'slate'   => 'text-slate-400',
                            default   => 'text-violet-600',
                        };
                    @endphp
                    <div class="relative">
                        <div class="absolute -left-[26px] top-1 w-4 h-4 rounded-full bg-white border-2 border-slate-100 flex items-center justify-center">
                            <div class="w-2 h-2 rounded-full {{ $dotColor }}"></div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center shrink-0">
                                <i data-lucide="{{ $aktivitas->icon }}" class="w-4 h-4 {{ $iconColor }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-slate-700">{!! $aktivitas->text !!}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $aktivitas->time }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 text-center py-4">Belum ada aktivitas hari ini.</p>
                @endforelse
            </div>
        </div>

        {{-- === SECTION 4: RINGKASAN HARI INI === --}}
        <div class="bg-gradient-to-br from-violet-700 to-violet-900 rounded-2xl shadow-sm p-5 text-white relative overflow-hidden">
            {{-- Ilustrasi --}}
            <div class="absolute right-0 top-0 w-40 h-40 opacity-10">
                <svg viewBox="0 0 200 200" fill="white" xmlns="http://www.w3.org/2000/svg">
                    <rect x="40" y="30" width="120" height="140" rx="8"/>
                    <rect x="55" y="50" width="90" height="8" rx="4"/>
                    <rect x="55" y="68" width="60" height="8" rx="4"/>
                    <rect x="55" y="86" width="80" height="8" rx="4"/>
                    <rect x="55" y="104" width="45" height="8" rx="4"/>
                    <rect x="55" y="130" width="90" height="28" rx="6"/>
                </svg>
            </div>
            <div class="absolute -bottom-6 -right-6 w-32 h-32 opacity-5">
                <svg viewBox="0 0 100 100" fill="white" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="40"/>
                    <circle cx="50" cy="38" r="12"/>
                    <ellipse cx="50" cy="70" rx="20" ry="16"/>
                </svg>
            </div>

            <div class="flex items-center gap-2 mb-5 relative">
                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center backdrop-blur-sm">
                    <i data-lucide="clipboard-check" class="w-4 h-4 text-white"></i>
                </div>
                <h3 class="font-semibold">Ringkasan Hari Ini</h3>
            </div>

            <div class="grid grid-cols-2 gap-3 relative">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3">
                    <p class="text-xs text-violet-200">Total Kunjungan</p>
                    <p class="text-xl font-bold mt-0.5">{{ $totalKunjungan }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3">
                    <p class="text-xs text-violet-200">Pelanggan Belum Bayar</p>
                    <p class="text-xl font-bold mt-0.5">{{ $belumBayarCount }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3">
                    <p class="text-xs text-violet-200">Pembayaran Berhasil</p>
                    <p class="text-xl font-bold mt-0.5">{{ $pembayaranHariIni }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3">
                    <p class="text-xs text-violet-200">Total Pembayaran</p>
                    <p class="text-xl font-bold mt-0.5">Rp {{ number_format($totalPembayaranNominal, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3">
                    <p class="text-xs text-violet-200">Gangguan Baru</p>
                    <p class="text-xl font-bold mt-0.5">{{ $gangguanHariIni }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3">
                    <p class="text-xs text-violet-200">Gangguan Diselesaikan</p>
                    <p class="text-xl font-bold mt-0.5">{{ $gangguanSelesai }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== QUICK ACTION ===================== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                <i data-lucide="zap" class="w-4 h-4 text-emerald-600"></i>
            </div>
            <h3 class="font-semibold text-slate-800">Quick Action</h3>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Scan QR Code --}}
            <a href="{{ route('pegawai.scan-barcode') }}"
               class="flex flex-col items-center gap-2 p-5 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                    <i data-lucide="scan-qr-code" class="w-6 h-6 text-blue-600"></i>
                </div>
                <span class="text-sm font-semibold text-slate-700 group-hover:text-blue-700">Scan QR Code</span>
            </a>

            {{-- Lihat Pelanggan --}}
            <a href="{{ route('pegawai.pelanggan.index') }}"
               class="flex flex-col items-center gap-2 p-5 rounded-xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                    <i data-lucide="users" class="w-6 h-6 text-emerald-600"></i>
                </div>
                <span class="text-sm font-semibold text-slate-700 group-hover:text-emerald-700">Lihat Pelanggan</span>
            </a>

            {{-- Lihat Gangguan --}}
            <a href="#"
               class="flex flex-col items-center gap-2 p-5 rounded-xl border border-slate-100 hover:border-amber-200 hover:bg-amber-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                    <i data-lucide="message-square-warning" class="w-6 h-6 text-amber-600"></i>
                </div>
                <span class="text-sm font-semibold text-slate-700 group-hover:text-amber-700">Lihat Gangguan</span>
            </a>

            {{-- Riwayat Kunjungan --}}
            <a href="#"
               class="flex flex-col items-center gap-2 p-5 rounded-xl border border-slate-100 hover:border-violet-200 hover:bg-violet-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center group-hover:bg-violet-200 transition-colors">
                    <i data-lucide="map-pin" class="w-6 h-6 text-violet-600"></i>
                </div>
                <span class="text-sm font-semibold text-slate-700 group-hover:text-violet-700">Riwayat Kunjungan</span>
            </a>
        </div>
    </div>
@endsection

