@extends('layouts.admin')

@section('title', 'Data Pembayaran')
@section('page-title', 'Data Pembayaran')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('status'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('status') }}',
        confirmButtonColor: '#4f46e5',
        confirmButtonText: 'OK'
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#e11d48',
        confirmButtonText: 'OK'
    });
</script>
@endif

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                    <i data-lucide="credit-card" class="w-5 h-5 text-violet-600"></i>
                </div>
                <span class="text-xs font-medium text-slate-400">Total</span>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $totalPembayaran }}</p>
            <p class="text-xs text-slate-400 mt-1">Semua transaksi</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
                </div>
                <span class="text-xs font-medium text-slate-400">Pending</span>
            </div>
            <p class="text-2xl font-bold text-amber-600">{{ $totalMenunggu }}</p>
            <p class="text-xs text-slate-400 mt-1">Menunggu verifikasi</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <span class="text-xs font-medium text-slate-400">Lunas</span>
            </div>
            <p class="text-2xl font-bold text-emerald-600">{{ $totalLunas }}</p>
            <p class="text-xs text-slate-400 mt-1">Rp {{ number_format($totalNominalLunas, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-5 h-5 text-rose-600"></i>
                </div>
                <span class="text-xs font-medium text-slate-400">Ditolak</span>
            </div>
            <p class="text-2xl font-bold text-rose-600">{{ $totalDitolak }}</p>
            <p class="text-xs text-slate-400 mt-1">Verifikasi ditolak</p>
        </div>
    </div>

    {{-- Tabel Pembayaran --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        {{-- Header + Filter --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-4">
            <form method="GET" class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama / kode pelanggan..."
                           class="w-full sm:w-48 rounded-lg border border-slate-200 pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                </div>
                <select name="status" onchange="this.form.submit()"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                    <option value="">Semua Status</option>
                    <option value="belum_bayar" {{ request('status') === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="menunggu_verifikasi" {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <input type="month" name="periode" value="{{ request('periode') }}"
                       onchange="this.form.submit()"
                       class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
            </form>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.pembayaran.index') }}"
                   class="text-xs text-violet-600 hover:underline font-medium">
                    Reset filter
                </a>
            </div>
        </div>

        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-slate-100 bg-slate-50/50">
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Invoice</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Pelanggan</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Periode</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Jumlah</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Total Bayar</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Metode</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Tanggal Bayar</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($pembayarans as $p)
                        @php
                            $statusBadge = match($p->status) {
                                'lunas' => ['bg-emerald-100', 'text-emerald-700', 'bg-emerald-500', 'Lunas'],
                                'menunggu_verifikasi' => ['bg-amber-100', 'text-amber-700', 'bg-amber-500', 'Menunggu Verifikasi'],
                                'ditolak' => ['bg-rose-100', 'text-rose-700', 'bg-rose-500', 'Ditolak'],
                                default => ['bg-rose-100', 'text-rose-600', 'bg-rose-500', 'Belum Bayar'],
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 font-mono font-semibold text-violet-600 text-xs">
                                INV-{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-5 py-3">
                                <p class="font-medium text-slate-800">{{ $p->pelanggan->nama ?? '-' }}</p>
                                <p class="text-xs text-slate-400 font-mono">{{ $p->pelanggan->kode ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $p->periode ?? '-' }}</td>
                            <td class="px-5 py-3 font-semibold text-slate-800">
                                Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3 font-semibold text-slate-800">
                                @if ($p->total_bayar)
                                    Rp {{ number_format($p->total_bayar, 0, ',', '.') }}
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-slate-600 text-xs">{{ $p->metode_pembayaran ?? '-' }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusBadge[0] }} {{ $statusBadge[1] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusBadge[2] }}"></span>
                                    {{ $statusBadge[3] }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-slate-500 text-xs">
                                {{ $p->tanggal_bayar ? \Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y') : ($p->dibayar_at ? $p->dibayar_at->format('d/m/Y') : '-') }}
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('admin.pembayaran.show', $p) }}"
                                       class="p-1.5 rounded-lg text-slate-400 hover:text-violet-600 hover:bg-violet-50 transition-colors"
                                       title="Detail">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-10 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="credit-card" class="w-10 h-10 text-slate-300"></i>
                                    <p class="text-sm text-slate-400">Belum ada data pembayaran.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pembayarans->links() }}
        </div>
    </div>
@endsection
