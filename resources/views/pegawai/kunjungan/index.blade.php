@extends('layouts.pegawai')

@section('title', 'Riwayat Kunjungan')
@section('page-title', 'Riwayat Kunjungan')

@section('content')
    {{-- Card Ringkasan Statistik --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center mb-3">
                <i data-lucide="clipboard-list" class="w-5 h-5 text-violet-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Total Kunjungan</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalSemua }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                <i data-lucide="calendar-check" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Kunjungan Hari Ini</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalHariIni }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Tagihan Dibayar</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalTagihanDibayar }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center mb-3">
                <i data-lucide="x-circle" class="w-5 h-5 text-rose-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Belum Bayar</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalBelumBayar }}</p>
        </div>
    </div>

    {{-- Filter & Tabel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        {{-- Filter --}}
        <form method="GET" class="flex flex-col lg:flex-row lg:items-end gap-3 mb-5">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-slate-500 mb-1">Cari</label>
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nama pelanggan, kode, catatan..."
                           class="w-full rounded-lg border border-slate-200 pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Status</label>
                <select name="status"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                    <option value="">Semua Status</option>
                    <option value="tagihan_dibayar" {{ request('status') === 'tagihan_dibayar' ? 'selected' : '' }}>Tagihan Dibayar</option>
                    <option value="belum_bayar" {{ request('status') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                       class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                       class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="bg-violet-600 hover:bg-violet-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition-colors">
                    <i data-lucide="filter" class="w-4 h-4 inline-block mr-1"></i>
                    Filter
                </button>
                <a href="{{ route('pegawai.kunjungan.index') }}"
                   class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold px-4 py-2 rounded-lg text-sm transition-colors">
                    <i data-lucide="refresh-ccw" class="w-4 h-4 inline-block mr-1"></i>
                    Reset
                </a>
            </div>
        </form>

        {{-- Info filter aktif --}}
        @if (request('search') || request('status') || request('tanggal_awal') || request('tanggal_akhir'))
            <div class="flex flex-wrap items-center gap-2 mb-4 px-4 py-2 bg-violet-50 rounded-lg text-xs text-violet-700">
                <i data-lucide="info" class="w-3.5 h-3.5"></i>
                <span>Filter aktif:</span>
                @if (request('search')) <span class="bg-violet-100 px-2 py-0.5 rounded-full">Cari: "{{ request('search') }}"</span> @endif
                @if (request('status')) <span class="bg-violet-100 px-2 py-0.5 rounded-full">Status: {{ str_replace('_', ' ', request('status')) }}</span> @endif
                @if (request('tanggal_awal')) <span class="bg-violet-100 px-2 py-0.5 rounded-full">Dari: {{ request('tanggal_awal') }}</span> @endif
                @if (request('tanggal_akhir')) <span class="bg-violet-100 px-2 py-0.5 rounded-full">Sampai: {{ request('tanggal_akhir') }}</span> @endif
            </div>
        @endif

        {{-- Tabel --}}
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-slate-100 bg-slate-50/50">
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">#</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Waktu Kunjungan</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Pelanggan</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Pegawai</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Catatan</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($kunjungans as $k)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 text-slate-400 text-xs">{{ $loop->iteration }}</td>
                            <td class="px-5 py-3">
                                <p class="font-medium text-slate-800">{{ $k->waktu_kunjungan->format('d M Y') }}</p>
                                <p class="text-xs text-slate-400">{{ $k->waktu_kunjungan->format('H:i') }} WIB</p>
                            </td>
                            <td class="px-5 py-3">
                                <p class="font-medium text-slate-800">{{ $k->pelanggan->nama }}</p>
                                <p class="text-xs text-slate-400">{{ $k->pelanggan->kode }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $k->pegawai->name }}</td>
                            <td class="px-5 py-3">
                                @if ($k->status === 'tagihan_dibayar')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Tagihan Dibayar
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Belum Bayar
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-slate-500 max-w-[200px] truncate">
                                {{ $k->catatan ?? '-' }}
                            </td>
                            <td class="px-5 py-3">
                                <a href="{{ route('pegawai.kunjungan.show', $k) }}"
                                   class="p-1.5 rounded-lg text-slate-400 hover:text-violet-600 hover:bg-violet-50 transition-colors inline-block"
                                   title="Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="map-pin" class="w-10 h-10 text-slate-300"></i>
                                    <p class="text-sm text-slate-400">Belum ada riwayat kunjungan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $kunjungans->links() }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Auto-submit filter berguna untuk select & date
    document.querySelectorAll('select[name="status"], input[name="tanggal_awal"], input[name="tanggal_akhir"]').forEach(el => {
        el.addEventListener('change', function() { this.closest('form').submit(); });
    });
</script>
@endpush

