@extends('layouts.admin')

@section('title', 'Paket WiFi')
@section('page-title', 'Paket WiFi')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-slate-800">Daftar Paket WiFi</h2>
        <a href="{{ route('admin.paket.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Paket
        </a>
    </div>

    <div class="overflow-x-auto -mx-5">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-slate-100 bg-slate-50/50">
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Paket</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Harga</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Deskripsi</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($pakets as $paket)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-3">
                            <span class="font-semibold text-slate-800">{{ $paket->nama }}</span>
                        </td>
                        <td class="px-5 py-3 font-semibold text-slate-800">
                            Rp {{ number_format($paket->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3 text-slate-500 max-w-[200px] truncate">
                            {{ $paket->deskripsi ?? '-' }}
                        </td>
                        <td class="px-5 py-3">
                            @if ($paket->status === 'aktif')
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
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.paket.edit', $paket) }}"
                                   class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                                   title="Edit">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.paket.destroy', $paket) }}" method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus paket {{ $paket->nama }}?')" class="inline">
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
                        <td colspan="5" class="px-5 py-10 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <i data-lucide="signal" class="w-10 h-10 text-slate-300"></i>
                                <p class="text-sm text-slate-400">Belum ada paket WiFi.</p>
                                <a href="{{ route('admin.paket.create') }}"
                                   class="text-sm font-semibold text-indigo-600 hover:underline mt-1">
                                    Tambah Paket Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pakets->links() }}
    </div>
</div>
@endsection

