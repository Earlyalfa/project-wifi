@extends('layouts.admin')

@section('title', 'Data Pengguna')
@section('page-title', 'Data Pengguna')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-slate-800">Daftar Pengguna</h2>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition-colors">
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
                                <select name="role" class="text-xs border border-slate-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-violet-400">
                                    <option value="pelanggan" {{ $user->role === 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                                    <option value="pegawai" {{ $user->role === 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <button type="submit" class="text-xs font-semibold text-violet-600 hover:text-violet-800 hover:underline">
                                    Simpan
                                </button>
                            </form>
                            @else
                                <span class="inline-flex items-center gap-1 text-violet-700 bg-violet-50 px-2 py-0.5 rounded-full text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-violet-600"></span> Admin
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
                                            class="p-1 rounded-lg text-slate-400 hover:text-violet-600 hover:bg-violet-50 transition-colors"
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
                                   class="p-1.5 rounded-lg text-slate-400 hover:text-violet-600 hover:bg-violet-50 transition-colors"
                                   title="Detail">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                                   title="Edit">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                @if ($user->id !== auth()->id())
                                    <form id="delete-user-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="delete-user-button p-1.5 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition-colors"
                                                data-form-id="delete-user-form-{{ $user->id }}"
                                                title="Hapus"
                                                aria-label="Hapus pengguna {{ $user->name }}">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-user-button').forEach((button) => {
        button.addEventListener('click', () => {
            Swal.fire({
                title: 'Hapus pengguna?',
                text: 'Apakah Anda yakin ingin menghapus pengguna ini? Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
                focusCancel: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(button.dataset.formId).submit();
                }
            });
        });
    });

    @if (session('status'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: @json(session('status')),
            confirmButtonText: 'OK',
            confirmButtonColor: '#4f46e5',
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Tidak dapat menghapus',
            text: @json(session('error')),
            confirmButtonText: 'OK',
            confirmButtonColor: '#e11d48',
        });
    @endif
</script>
@endpush
