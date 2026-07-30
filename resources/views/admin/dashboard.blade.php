@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
    {{-- ===================== CARD STATISTIK (6 Card) ===================== --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                <i data-lucide="users" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Total Pengguna</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalPengguna }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center mb-3">
                <i data-lucide="user-check" class="w-5 h-5 text-violet-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Pegawai Aktif</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $pegawaiAktif }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center mb-3">
                <i data-lucide="signal" class="w-5 h-5 text-sky-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Paket WiFi</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $paketWifi }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center mb-3">
                <i data-lucide="clipboard-list" class="w-5 h-5 text-rose-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Tagihan Belum Dibayar</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $tagihanBelumDibayar }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                <i data-lucide="trending-up" class="w-5 h-5 text-emerald-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Pendapatan Bulan Ini</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center mb-3">
                <i data-lucide="message-square-warning" class="w-5 h-5 text-amber-600"></i>
            </div>
            <p class="text-xs text-slate-400 font-medium">Pengaduan Aktif</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $pengaduanAktif }}</p>
        </div>
    </div>

    {{-- ===================== ROW 2 KOLOM ===================== --}}
    <div class="grid lg:grid-cols-2 gap-6 mb-6">

        {{-- === GRAFIK PENDAPATAN 6 BULAN === --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 text-emerald-600"></i>
                    </div>

                    <h3 class="font-semibold text-slate-800">Pendapatan Pembayaran 6 Bulan Terakhir</h3>
                </div>
            </div>
            <div style="position: relative; height: 250px;">
                <canvas id="chartPendapatan"></canvas>
            </div>
        </div>

        {{-- === PENGGUNA TERBARU === --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                        <i data-lucide="users" class="w-4 h-4 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Pengguna Terbaru</h3>
                </div>
                <a href="{{ route('admin.users') }}" class="text-xs text-blue-600 font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto -mx-5">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-slate-100">
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Nama</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Role</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($penggunaTerbaru as $user)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3">
                                    <p class="font-medium text-slate-800">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $user->email }}</p>
                                </td>
                                <td class="px-5 py-3">
                                    @if ($user->role === 'admin')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-violet-100 text-violet-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-violet-600"></span> Admin
                                        </span>
                                    @elseif ($user->role === 'pegawai')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Pegawai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Pelanggan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    @if ($user->status === 'aktif')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    @if ($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.role', $user) }}" method="POST" class="flex items-center gap-1">
                                        @csrf @method('PATCH')
                                        <select name="role" class="text-xs border border-slate-200 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-violet-400">
                                            <option value="pelanggan" {{ $user->role === 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                                            <option value="pegawai" {{ $user->role === 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <button type="submit" class="text-xs font-semibold text-violet-600 hover:text-violet-800 hover:underline">Simpan</button>
                                    </form>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-8 text-center text-slate-400">Belum ada pengguna.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===================== ROW 2 KOLOM (2 Tables) ===================== --}}
    <div class="grid lg:grid-cols-2 gap-6 mb-6">

        {{-- === PEMBAYARAN TERBARU === --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <i data-lucide="credit-card" class="w-4 h-4 text-emerald-600"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Pembayaran Terbaru</h3>
                </div>
            </div>
            <div class="overflow-x-auto -mx-5">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-slate-100">
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Nama</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Nominal</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($pembayaranTerbaru as $bayar)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3 font-medium text-slate-800">{{ $bayar->pelanggan->nama ?? '-' }}</td>
                                <td class="px-5 py-3 font-semibold text-slate-800">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                                <td class="px-5 py-3">
                                    @if ($bayar->status === 'lunas')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Belum Bayar
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-slate-500">{{ $bayar->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-8 text-center text-slate-400">Belum ada pembayaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- === PENGADUAN TERBARU === --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                        <i data-lucide="message-square-warning" class="w-4 h-4 text-amber-600"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Pengaduan Terbaru</h3>
                </div>
            </div>
            <div class="overflow-x-auto -mx-5">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b border-slate-100">
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Nama</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Keluhan</th>
                            <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($pengaduanTerbaru as $g)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3 font-medium text-slate-800">{{ $g->pelanggan->nama ?? '-' }}</td>
                                <td class="px-5 py-3 text-slate-600 max-w-[140px] truncate">{{ $g->keluhan }}</td>
                                <td class="px-5 py-3">
                                    @php
                                        $badgeColor = match($g->status) {
                                            'menunggu' => 'bg-amber-100 text-amber-700',
                                            'diproses' => 'bg-blue-100 text-blue-700',
                                            'selesai'  => 'bg-emerald-100 text-emerald-700',
                                            default    => 'bg-slate-100 text-slate-500',
                                        };
                                        $dotColor = match($g->status) {
                                            'menunggu' => 'bg-amber-500',
                                            'diproses' => 'bg-blue-500',
                                            'selesai'  => 'bg-emerald-500',
                                            default    => 'bg-slate-400',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                        {{ ucfirst($g->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-5 py-8 text-center text-slate-400">Tidak ada pengaduan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
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
            <a href="{{ route('admin.users') }}"
               class="flex flex-col items-center gap-2 p-5 rounded-xl border border-slate-100 hover:border-violet-200 hover:bg-violet-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center group-hover:bg-violet-200 transition-colors">
                    <i data-lucide="user-plus" class="w-6 h-6 text-violet-600"></i>
                </div>
                <span class="text-sm font-semibold text-slate-700 group-hover:text-violet-700">Tambah Pengguna</span>
            </a>
            <a href="#"
               class="flex flex-col items-center gap-2 p-5 rounded-xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                    <i data-lucide="signal" class="w-6 h-6 text-emerald-600"></i>
                </div>
                <span class="text-sm font-semibold text-slate-700 group-hover:text-emerald-700">Tambah Paket WiFi</span>
            </a>
            <a href="#"
               class="flex flex-col items-center gap-2 p-5 rounded-xl border border-slate-100 hover:border-amber-200 hover:bg-amber-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                    <i data-lucide="file-text" class="w-6 h-6 text-amber-600"></i>
                </div>
                <span class="text-sm font-semibold text-slate-700 group-hover:text-amber-700">Cetak Laporan</span>
            </a>
            <a href="#"
               class="flex flex-col items-center gap-2 p-5 rounded-xl border border-slate-100 hover:border-rose-200 hover:bg-rose-50/50 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center group-hover:bg-rose-200 transition-colors">
                    <i data-lucide="message-square-warning" class="w-6 h-6 text-rose-600"></i>
                </div>
                <span class="text-sm font-semibold text-slate-700 group-hover:text-rose-700">Lihat Semua Pengaduan</span>
            </a>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('chartPendapatan').getContext('2d');
    const bulanLabels = @json($grafikBulanan->pluck('bulan'));
    const bulanData = @json($grafikBulanan->pluck('total'));

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: bulanLabels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: bulanData,
                backgroundColor: 'rgba(16, 185, 129, 0.3)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 2,
                borderRadius: 6,
                barPercentage: 0.6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp' + (value / 1000) + 'k';
                        }
                    },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endpush
