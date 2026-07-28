@extends('layouts.admin')

@section('title', 'Data Pengguna')
@section('page-title', 'Data Pengguna')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-slate-800">Daftar Pengguna</h2>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Pengguna
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-slate-100">
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Nama</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Email</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Role</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                    <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse ($users as $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $user->name }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $user->email }}</td>
                        <td class="px-5 py-3">
                            @if ($user->id !== auth()->id())
                            <form action="{{ route('admin.users.role', $user) }}" method="POST" class="flex items-center gap-1">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="text-xs border border-slate-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                    <option value="pelanggan" {{ $user->role === 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                                    <option value="pegawai" {{ $user->role === 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <button type="submit" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                                    Simpan
                                </button>
                            </form>
                            @else
                                <span class="inline-flex items-center gap-1 text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Admin
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $user->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-600' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->status === 'aktif' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ ucfirst($user->status) }}
                                </span>
                                @if ($user->id !== auth()->id())
                                <form action="{{ route('admin.users.status', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $user->status === 'aktif' ? 'nonaktif' : 'aktif' }}">
                                    <button type="submit"
                                            class="p-1 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                            title="{{ $user->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i data-lucide="toggle-{{ $user->status === 'aktif' ? 'left' : 'right' }}" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="p-1.5 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors"
                                   title="Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                                   title="Edit">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
