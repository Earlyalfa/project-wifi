@extends('layouts.pegawai')

@section('title', 'Pelanggan')
@section('page-title', 'Daftar Pelanggan')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-5">

    <form method="GET" class="mb-4">
        <div class="relative max-w-xs">
            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / kode pelanggan"
                   class="w-full rounded-lg border border-slate-200 pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
        </div>
    </form>

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-slate-400 border-b border-slate-100">
                <th class="py-2 font-medium">Kode</th>
                <th class="py-2 font-medium">Nama</th>
                <th class="py-2 font-medium">Paket</th>
                <th class="py-2 font-medium">Status</th>
                <th class="py-2 font-medium"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pelanggans as $p)
                <tr class="border-b border-slate-50">
                    <td class="py-2.5 text-slate-800 font-medium">{{ $p->kode }}</td>
                    <td class="py-2.5 text-slate-700">{{ $p->nama }}</td>
                    <td class="py-2.5 text-slate-500">{{ $p->paket }}</td>
                    <td class="py-2.5">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                            {{ $p->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    <td class="py-2.5 text-right">
                        <a href="{{ route('pegawai.pelanggan.show', $p) }}" class="text-emerald-600 font-medium hover:underline">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-6 text-center text-slate-400">Belum ada data pelanggan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $pelanggans->links() }}
    </div>
</div>
@endsection
