@extends('layouts.pegawai')

@section('title', 'Data Pelanggan')
@section('page-title', 'Data Pelanggan')

@section('content')
    {{-- Statistik Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center mb-3">
                <i data-lucide="users" class="w-5 h-5 text-violet-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Total Pelanggan</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalPelanggan }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Aktif</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalAktif }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center mb-3">
                <i data-lucide="x-circle" class="w-5 h-5 text-rose-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Nonaktif</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalNonaktif }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center mb-3">
                <i data-lucide="message-square-warning" class="w-5 h-5 text-amber-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Gangguan Aktif</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalGangguan }}</p>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        {{-- Header dengan filter --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <form method="GET" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama / kode / alamat..."
                           class="w-full sm:w-64 rounded-lg border border-slate-200 pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                </div>
                <div class="relative">
                    <i data-lucide="filter" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <select name="paket" onchange="this.form.submit()"
                            class="w-full sm:w-44 rounded-lg border border-slate-200 pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400 appearance-none cursor-pointer">
                        <option value="">Semua Paket</option>
                        @foreach ($paketList as $p)
                            <option value="{{ $p }}" {{ request('paket') === $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                @if (request('search') || request('paket'))
                    <a href="{{ route('pegawai.pelanggan.index') }}"
                       class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 text-slate-500 text-sm hover:bg-slate-50 transition-colors">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        Reset
                    </a>
                @endif
</form>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto -mx-5">
            @if ($pelanggans->count() > 0)
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-slate-100">
<th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Pelanggan</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Alamat</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Paket</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Jatuh Tempo</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Pembayaran</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($pelanggans as $p)
                            @php
                                $tagihanTerbaru = $p->pembayarans->first();
                                $gangguan = $gangguanStatuses->get($p->id);
                                $inisial = strtoupper(substr($p->nama, 0, 1));
                                $gradients = ['from-violet-400 to-violet-600', 'from-emerald-400 to-emerald-600', 'from-blue-400 to-blue-600', 'from-rose-400 to-rose-600', 'from-amber-400 to-amber-600', 'from-cyan-400 to-cyan-600', 'from-purple-400 to-purple-600', 'from-pink-400 to-pink-600'];
                                $g = $gradients[crc32($p->id) % count($gradients)];
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $g }} flex items-center justify-center shadow-sm shrink-0">
                                            <span class="text-sm font-bold text-white">{{ $inisial }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800">{{ $p->nama }}</p>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-[11px] font-mono font-semibold text-violet-600">{{ $p->kode }}</span>
                                                <span class="text-xs text-slate-400">{{ $p->no_hp ?? 'Tidak ada no. HP' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-slate-600 max-w-[160px] truncate">{{ $p->alamat ?? '-' }}</td>
                                <td class="px-5 py-3">
                                    @if ($p->paket)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-violet-50 text-violet-700 text-xs font-semibold">
                                            <i data-lucide="wifi" class="w-3 h-3"></i>
                                            {{ $p->paket }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    @if ($p->tagihan_jatuh_tempo)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 whitespace-nowrap">
                                            <i data-lucide="calendar-clock" class="w-3 h-3"></i>
                                            Tanggal {{ (int) $p->tagihan_jatuh_tempo }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    @if ($p->status === 'aktif')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    @if ($tagihanTerbaru && $tagihanTerbaru->status === 'belum_bayar')
                                        <div class="flex flex-col gap-0.5">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-600 w-fit">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Belum Bayar
                                            </span>
                                            <span class="text-[11px] text-rose-400 font-medium">Rp {{ number_format($tagihanTerbaru->jumlah, 0, ',', '.') }}</span>
                                        </div>
                                    @elseif ($tagihanTerbaru)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Lunas
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('pegawai.pelanggan.show', $p) }}"
                                           class="p-1.5 rounded-lg text-slate-400 hover:text-violet-600 hover:bg-violet-50 transition-colors"
                                           title="Detail">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('pegawai.pelanggan.edit', $p) }}"
                                           class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                                           title="Edit">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                        <i data-lucide="users" class="w-8 h-8 text-slate-300"></i>
                    </div>
                    <h3 class="text-base font-semibold text-slate-700 mb-1">Belum Ada Data Pelanggan</h3>
<p class="text-sm text-slate-400 text-center max-w-sm">Belum ada data pelanggan yang tersedia.</p>
                </div>
            @endif
        </div>

        {{-- Footer dengan pagination --}}
        @if ($pelanggans->hasPages())
            <div class="mt-4 pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <p class="text-xs text-slate-400">
                        Menampilkan {{ $pelanggans->firstItem() }}-{{ $pelanggans->lastItem() }} dari {{ $pelanggans->total() }} pelanggan
                    </p>
                    <div class="flex items-center gap-1">
                        {{ $pelanggans->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
