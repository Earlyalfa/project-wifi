@extends('layouts.admin')

@section('title', 'Data Pengaduan Gangguan')
@section('page-title', 'Data Pengaduan Gangguan')

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

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center mb-3">
                <i data-lucide="message-square-warning" class="w-5 h-5 text-violet-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Total Pengaduan</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalSemua }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center mb-3">
                <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Menunggu</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ $totalMenunggu }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center mb-3">
                <i data-lucide="loader" class="w-5 h-5 text-blue-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Diproses</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $totalDiproses }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Selesai</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $totalSelesai }}</p>
        </div>
    </div>

    {{-- Tabel Pengaduan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        {{-- Header + Filter --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-4">
            <form method="GET" class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari judul / deskripsi / pelanggan..."
                           class="w-full sm:w-56 rounded-lg border border-slate-200 pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                </div>
                <select name="status" onchange="this.form.submit()"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                    <option value="">Semua Status</option>
                    <option value="Menunggu" {{ request('status') === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Diproses" {{ request('status') === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <select name="jenis" onchange="this.form.submit()"
                        class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                    <option value="">Semua Jenis</option>
                    @foreach ($jenisList as $j)
                        <option value="{{ $j }}" {{ request('jenis') === $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
            </form>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.pengaduan.index') }}"
                   class="text-xs text-violet-600 hover:underline font-medium">
                    Reset filter
                </a>
            </div>
        </div>

        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-slate-100 bg-slate-50/50">
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">No</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Tanggal</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Pelanggan</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Jenis</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Judul Keluhan</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Foto</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Keterangan</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($pengaduans as $index => $p)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 text-slate-600">{{ $pengaduans->firstItem() + $index }}</td>
                            <td class="px-5 py-3 text-slate-500 text-xs">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-3">
                                <p class="font-medium text-slate-800">{{ $p->user->name ?? '-' }}</p>
                                <p class="text-xs text-slate-400">{{ $p->user->email ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $p->jenis_gangguan }}</td>
                            <td class="px-5 py-3 text-slate-800 font-medium max-w-[150px] truncate">{{ $p->judul_keluhan }}</td>
                            <td class="px-5 py-3">
                                @if ($p->foto)
                                    <a href="{{ asset('storage/' . $p->foto) }}" target="_blank"
                                       class="inline-flex items-center gap-1 text-xs text-violet-600 hover:underline">
                                        <i data-lucide="image" class="w-3.5 h-3.5"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $badge = match($p->status) {
                                        'Menunggu' => 'bg-amber-100 text-amber-700',
                                        'Diproses' => 'bg-blue-100 text-blue-700',
                                        'Selesai'  => 'bg-emerald-100 text-emerald-700',
                                        default    => 'bg-slate-100 text-slate-500',
                                    };
                                    $dot = match($p->status) {
                                        'Menunggu' => 'bg-amber-500',
                                        'Diproses' => 'bg-blue-500',
                                        'Selesai'  => 'bg-emerald-500',
                                        default    => 'bg-slate-400',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-slate-500 text-xs max-w-[120px] truncate">{{ $p->keterangan ?? '-' }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('admin.pengaduan.show', $p) }}"
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
            {{ $pengaduans->links() }}
        </div>
    </div>
@endsection
