@extends('layouts.pegawai')

@section('title', 'Pengaduan Gangguan')
@section('page-title', 'Pengaduan Gangguan')

@section('content')
    {{-- Card Ringkasan --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center mb-3">
                <i data-lucide="list" class="w-5 h-5 text-violet-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Total Pengaduan</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalSemua }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center mb-3">
                <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Menunggu</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalMenunggu }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center mb-3">
                <i data-lucide="loader" class="w-5 h-5 text-blue-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Diproses</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalDiproses }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Selesai</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalSelesai }}</p>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        {{-- Header + Filter --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <form method="GET" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari tiket / pelanggan / keluhan..."
                           class="w-full sm:w-64 rounded-lg border border-slate-200 pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                </div>
                <select name="status" onchange="this.form.submit()"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </form>
            <a href="{{ route('pegawai.gangguan.create') }}"
               class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition-colors shrink-0">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Pengaduan
            </a>
        </div>

        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-slate-100">
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">No Tiket</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Nama Pelanggan</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Alamat</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Keluhan</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Tanggal Lapor</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($gangguans as $g)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3">
                                <span class="font-mono font-semibold text-violet-600">{{ $g->no_tiket }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <p class="font-medium text-slate-800">{{ $g->pelanggan->nama }}</p>
                                <p class="text-xs text-slate-400">{{ $g->pelanggan->kode }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-600 max-w-[150px] truncate">{{ $g->pelanggan->alamat ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-600 max-w-[180px] truncate">{{ $g->keluhan }}</td>
                            <td class="px-5 py-3 text-slate-500">{{ $g->tanggal_lapor->format('d M Y H:i') }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $badge = match($g->status) {
                                        'menunggu' => 'bg-amber-100 text-amber-700 ring-amber-200',
                                        'diproses' => 'bg-blue-100 text-blue-700 ring-blue-200',
                                        'selesai'  => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
                                        default    => 'bg-slate-100 text-slate-500 ring-slate-200',
                                    };
                                    $dot = match($g->status) {
                                        'menunggu' => 'bg-amber-500',
                                        'diproses' => 'bg-blue-500',
                                        'selesai'  => 'bg-emerald-500',
                                        default    => 'bg-slate-400',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold ring-1 {{ $badge }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
                                    {{ ucfirst($g->status) }}
                                </span>
                            </td>
<td class="px-5 py-3">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <a href="{{ route('pegawai.gangguan.show', $g) }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-violet-50 text-violet-700 hover:bg-violet-100 transition-colors">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    </a>
                                    @if ($g->status === 'menunggu')
                                        <form action="{{ route('pegawai.gangguan.update-status', $g) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="diproses">
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                                <i data-lucide="play" class="w-3.5 h-3.5"></i>
                                                Proses
                                            </button>
                                        </form>
                                    @elseif ($g->status === 'diproses')
                                        <form action="{{ route('pegawai.gangguan.update-status', $g) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors">
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                Selesai
                                            </button>
                                        </form>
@endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="message-square-warning" class="w-10 h-10 text-slate-300"></i>
                                    <p class="text-sm text-slate-400">Belum ada pengaduan gangguan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $gangguans->links() }}
        </div>
    </div>
@endsection

