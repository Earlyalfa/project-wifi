@extends('layouts.pelanggan')

@section('title', 'Dashboard Pelanggan')
@section('page-title', 'Dashboard')

@section('content')
    {{-- ===================== CARD RINGKASAN (4 Card) ===================== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Card 1: Paket WiFi --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center mb-3">
                <i data-lucide="signal" class="w-5 h-5 text-violet-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Paket WiFi</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $paket ?? '-' }}</p>
        </div>

        {{-- Card 2: Tagihan Bulan Ini --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center mb-3">
                <i data-lucide="credit-card" class="w-5 h-5 text-rose-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Tagihan Bulan Ini</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">
                @if ($tagihanBulanIni)
                    Rp {{ number_format($tagihanBulanIni, 0, ',', '.') }}
                @else
                    Rp 0
                @endif
            </p>
        </div>

        {{-- Card 3: Jatuh Tempo Pembayaran --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center mb-3">
                <i data-lucide="calendar-clock" class="w-5 h-5 text-amber-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Jatuh Tempo Pembayaran</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">
                {{ $jatuhTempo ? \Carbon\Carbon::parse($jatuhTempo)->format('d M Y') : '-' }}
            </p>
        </div>

        {{-- Card 4: Status Pengaduan (klik ke halaman pengaduan) --}}
        <a href="{{ route('pelanggan.pengaduan') }}" class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 block hover:border-emerald-300 hover:shadow-md transition-all duration-200">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                <i data-lucide="message-square-warning" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Status Pengaduan</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">
                {{ $statusPengaduan ?? '-' }}
            </p>
        </a>
    </div>

    {{-- ===================== INFORMASI AKUN ===================== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                <i data-lucide="info" class="w-4 h-4 text-violet-600"></i>
            </div>
            <h3 class="font-semibold text-slate-800">Informasi Akun</h3>
        </div>
        <div class="space-y-3">
            <div class="flex items-center gap-3 py-2 border-b border-slate-50 last:border-b-0">
                <span class="w-32 text-sm text-slate-400 font-medium shrink-0">Nama</span>
                <span class="text-sm font-semibold text-slate-800">{{ $namaPelanggan ?? auth()->user()->name }}</span>
            </div>
            <div class="flex items-center gap-3 py-2 border-b border-slate-50 last:border-b-0">
                <span class="w-32 text-sm text-slate-400 font-medium shrink-0">Nomor HP</span>
                <span class="text-sm font-semibold text-slate-800">{{ $noHp ?? '-' }}</span>
            </div>
            <div class="flex items-center gap-3 py-2 border-b border-slate-50 last:border-b-0">
                <span class="w-32 text-sm text-slate-400 font-medium shrink-0">Email</span>
                <span class="text-sm font-semibold text-slate-800">{{ $email ?? auth()->user()->email }}</span>
            </div>
            <div class="flex items-center gap-3 py-2 border-b border-slate-50 last:border-b-0">
                <span class="w-32 text-sm text-slate-400 font-medium shrink-0">Alamat</span>
                <span class="text-sm font-semibold text-slate-800">{{ $alamat ?? '-' }}</span>
            </div>
            <div class="flex items-center gap-3 py-2 border-b border-slate-50 last:border-b-0">
                <span class="w-32 text-sm text-slate-400 font-medium shrink-0">Paket WiFi</span>
                <span class="text-sm font-semibold text-slate-800">{{ $paket ?? '-' }}</span>
            </div>
            <div class="flex items-center gap-3 py-2 border-b border-slate-50 last:border-b-0">
                <span class="w-32 text-sm text-slate-400 font-medium shrink-0">Tanggal Aktif</span>
                <span class="text-sm font-semibold text-slate-800">{{ $tanggalAktif ? \Carbon\Carbon::parse($tanggalAktif)->format('d M Y') : '-' }}</span>
            </div>
            <div class="flex items-center gap-3 py-2 border-b border-slate-50 last:border-b-0">
                <span class="w-32 text-sm text-slate-400 font-medium shrink-0">Jatuh Tempo</span>
                <span class="text-sm font-semibold text-slate-800">{{ $jatuhTempo ? \Carbon\Carbon::parse($jatuhTempo)->format('d M Y') : '-' }}</span>
            </div>
            <div class="flex items-center gap-3 py-2">
                <span class="w-32 text-sm text-slate-400 font-medium shrink-0">Status Akun</span>
                <span class="text-sm font-semibold">
                    @if ($statusAkun === 'aktif')
                        <span class="inline-flex items-center gap-1 text-emerald-600">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-rose-600">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            Nonaktif
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </div>

    {{-- ===================== GRID 2 KOLOM: RIWAYAT PEMBAYARAN + PENGADUAN TERBARU ===================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- RIWAYAT PEMBAYARAN --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <i data-lucide="credit-card" class="w-4 h-4 text-emerald-600"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Riwayat Pembayaran</h3>
                </div>
                <a href="#" class="text-xs text-blue-600 font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto -mx-5">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-slate-100">
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Bulan</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Nominal</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Tanggal Bayar</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($riwayatPembayaran as $p)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3 font-medium text-slate-800">{{ $p->periode }}</td>
                                <td class="px-5 py-3 font-semibold text-slate-800">Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-slate-600">{{ $p->dibayar_at ? \Carbon\Carbon::parse($p->dibayar_at)->format('d M Y') : '-' }}</td>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-slate-400">Belum ada riwayat pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PENGADUAN TERBARU --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                        <i data-lucide="message-square-warning" class="w-4 h-4 text-amber-600"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Pengaduan Terbaru</h3>
                </div>
                <a href="{{ route('pelanggan.pengaduan') }}" class="text-xs text-blue-600 font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto -mx-5">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-slate-100">
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Tanggal</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Keluhan</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($pengaduanTerbaru as $g)
                            <tr class="hover:bg-slate-50 transition-colors cursor-pointer" onclick="window.location='{{ route('pelanggan.pengaduan') }}'">
                                <td class="px-5 py-3 text-slate-600">{{ \Carbon\Carbon::parse($g->tanggal_lapor)->format('d M Y') }}</td>
                                <td class="px-5 py-3 text-slate-600 max-w-[200px] truncate">{{ $g->keluhan }}</td>
                                <td class="px-5 py-3">
                                    @php
                                        $badgeColor = match($g->status) {
                                            'menunggu' => 'bg-amber-100 text-amber-700',
                                            'diproses' => 'bg-blue-100 text-blue-700',
                                            'selesai'  => 'bg-emerald-100 text-emerald-700',
                                            default    => 'bg-slate-100 text-slate-500',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                        <span class="w-1.5 h-1.5 rounded-full
                                            {{ $g->status === 'menunggu' ? 'bg-amber-500' : ($g->status === 'diproses' ? 'bg-blue-500' : 'bg-emerald-500') }}"></span>
                                        {{ ucfirst($g->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-8 text-center text-slate-400">Belum ada pengaduan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===================== QUICK ACTION ===================== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                <i data-lucide="zap" class="w-4 h-4 text-violet-600"></i>
            </div>
            <h3 class="font-semibold text-slate-800">Quick Action</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- Lihat Tagihan Saya --}}
            <a href="#"
               class="flex items-center gap-4 p-5 rounded-xl border border-slate-100 hover:border-violet-200 hover:bg-violet-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center group-hover:bg-violet-200 transition-colors shrink-0">
                    <i data-lucide="credit-card" class="w-6 h-6 text-violet-600"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-700 group-hover:text-violet-700">Lihat Tagihan Saya</p>
                    <p class="text-xs text-slate-400 mt-0.5">Cek dan bayar tagihan WiFi kamu</p>
                </div>
            </a>

            {{-- Buat Pengaduan --}}
            <a href="{{ route('pelanggan.pengaduan') }}"
               class="flex items-center gap-4 p-5 rounded-xl border border-slate-100 hover:border-amber-200 hover:bg-amber-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center group-hover:bg-amber-200 transition-colors shrink-0">
                    <i data-lucide="message-square-plus" class="w-6 h-6 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-700 group-hover:text-amber-700">Buat Pengaduan</p>
                    <p class="text-xs text-slate-400 mt-0.5">Laporkan gangguan WiFi kamu</p>
                </div>
            </a>
        </div>
    </div>
@endsection
