@extends('layouts.pegawai')

@section('title', 'Data Pelanggan')
@section('page-title', 'Data Pelanggan')

@section('content')
    {{-- Card Ringkasan --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center mb-3">
                <i data-lucide="users" class="w-5 h-5 text-indigo-600"></i>
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
        {{-- Header + Filter --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-4">
            <form method="GET" class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama / kode / alamat..."
                           class="w-full sm:w-56 rounded-lg border border-slate-200 pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <select name="paket" onchange="this.form.submit()"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">Semua Paket</option>
                    @foreach ($paketList as $p)
                        <option value="{{ $p }}" {{ request('paket') === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto -mx-5">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-slate-100 bg-slate-50/50">
                                <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Kode</th>
                                <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Nama</th>
                                <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Alamat</th>
                                <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Paket</th>
                                <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status Pembayaran</th>
                                <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status Gangguan</th>
                                <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($pelanggans as $p)
                                @php
                                    $tagihanTerbaru = $p->pembayarans->first();
                                    $gangguan = $gangguanStatuses->get($p->id);
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-5 py-3 font-mono font-semibold text-indigo-600">{{ $p->kode }}</td>
                                    <td class="px-5 py-3">
                                        <p class="font-medium text-slate-800">{{ $p->nama }}</p>
                                        <p class="text-xs text-slate-400">{{ $p->no_hp ?? '-' }}</p>
                                    </td>
                                    <td class="px-5 py-3 text-slate-600 max-w-[150px] truncate">{{ $p->alamat ?? '-' }}</td>
                                    <td class="px-5 py-3">
                                        <span class="font-medium text-slate-700">{{ $p->paket ?? '-' }}</span>
                                    </td>
                            <td class="px-5 py-3">
                                @if ($tagihanTerbaru && $tagihanTerbaru->status === 'belum_bayar')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Belum Bayar
                                    </span>
                                @elseif ($tagihanTerbaru)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-400">
                                        -
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @if ($gangguan)
                                    @php
                                        $gBadge = match($gangguan->status) {
                                            'menunggu' => 'bg-amber-100 text-amber-700',
                                            'diproses' => 'bg-blue-100 text-blue-700',
                                            'selesai'  => 'bg-emerald-100 text-emerald-700',
                                            default    => 'bg-slate-100 text-slate-400',
                                        };
                                        $gDot = match($gangguan->status) {
                                            'menunggu' => 'bg-amber-500',
                                            'diproses' => 'bg-blue-500',
                                            'selesai'  => 'bg-emerald-500',
                                            default    => 'bg-slate-300',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $gBadge }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $gDot }}"></span>
                                        {{ ucfirst($gangguan->status) }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('pegawai.pelanggan.show', $p) }}"
                                       class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                       title="Detail">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('pegawai.pelanggan.edit', $p) }}"
                                       class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                                       title="Edit">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('pegawai.pelanggan.destroy', $p) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus pelanggan {{ $p->nama }}?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                                                title="Hapus">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i data-lucide="users" class="w-10 h-10 text-slate-300"></i>
                                    <p class="text-sm text-slate-400">Belum ada data pelanggan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pelanggans->links() }}
        </div>
    </div>
@endsection

