@extends('layouts.admin')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('admin.users') }}" class="flex items-center gap-2 text-slate-500 text-sm mb-4 hover:underline">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali ke Data Pengguna
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                {{-- Nama Lengkap --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Nama lengkap pengguna"
                           class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('name') border-rose-300 @enderror">
                    @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           placeholder="contoh@email.com"
                           class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('email') border-rose-300 @enderror">
                    @error('email') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Password <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required
                           placeholder="Min. 8 karakter"
                           class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('password') border-rose-300 @enderror">
                    @error('password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- No HP --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                           placeholder="Contoh: 08123456789"
                           class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('no_hp') border-rose-300 @enderror">
                    @error('no_hp') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="2"
                              placeholder="Alamat lengkap"
                              class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('alamat') border-rose-300 @enderror">{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Paket WiFi --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Paket WiFi</label>
                    <select name="paket"
                            class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">-- Pilih Paket --</option>
                        @foreach ($paketList as $p)
                            <option value="{{ $p }}" {{ old('paket') === $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                        <option value="custom" {{ old('paket') === 'custom' ? 'selected' : '' }}>Lainnya (ketik manual)</option>
                    </select>
                    <input type="text" name="paket_custom" value="{{ old('paket_custom') }}"
                           placeholder="Atau ketik paket custom..."
                           class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm mt-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('paket') border-rose-300 @enderror">
                    @error('paket') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Upload Foto Rumah --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Upload Foto Rumah</label>
                    <input type="file" name="foto_rumah" accept="image/jpg,image/jpeg,image/png"
                           class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('foto_rumah') border-rose-300 @enderror">
                    @error('foto_rumah') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Role <span class="text-rose-500">*</span></label>
                    <select name="role" required
                            class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="pelanggan" {{ old('role') === 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                        <option value="pegawai" {{ old('role') === 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Status <span class="text-rose-500">*</span></label>
                    <select name="status" required
                            class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3">
                <a href="{{ route('admin.users') }}"
                   class="flex-1 text-center border border-slate-200 text-slate-600 font-semibold py-2.5 rounded-lg text-sm hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                    <i data-lucide="save" class="w-4 h-4 inline-block -mt-0.5"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const paketSelect = document.querySelector('select[name="paket"]');
    const paketCustom = document.querySelector('input[name="paket_custom"]');

    paketSelect.addEventListener('change', function () {
        if (this.value === 'custom') {
            paketCustom.style.display = 'block';
            paketCustom.focus();
        } else {
            paketCustom.style.display = 'none';
            paketCustom.value = '';
        }
    });

    // Trigger on load
    if (paketSelect.value === 'custom') {
        paketCustom.style.display = 'block';
    } else {
        paketCustom.style.display = 'none';
    }
});
</script>
@endpush

