@extends('layouts.pelanggan')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Card: Informasi Profile --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-5">
        <form action="{{ route('pelanggan.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="flex flex-col items-center text-center mb-5">
                <div class="relative w-24 h-24 mb-3">
                    @if ($pelanggan && $pelanggan->foto_profil)
                        <div id="profile-fallback" class="hidden w-24 h-24 rounded-full bg-gradient-to-br from-violet-400 to-violet-600 items-center justify-center shadow-sm">
                            <span class="text-3xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <img src="{{ asset('storage/' . $pelanggan->foto_profil) }}" alt="Foto profil {{ $user->name }}" id="preview-foto" class="w-24 h-24 rounded-full object-cover border-2 border-violet-100 shadow-sm">
                    @else
                        <div id="profile-fallback" class="w-24 h-24 rounded-full bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-sm">
                            <span class="text-3xl font-bold text-white">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <img src="" alt="Preview foto profil" id="preview-foto" class="hidden w-24 h-24 rounded-full object-cover border-2 border-violet-100 shadow-sm">
                    @endif
                    <button type="button" onclick="document.getElementById('foto_profil').click()" title="Ubah foto profil" aria-label="Ubah foto profil" class="absolute -right-1 -bottom-1 w-9 h-9 rounded-full bg-violet-600 hover:bg-violet-700 border-2 border-white shadow-md flex items-center justify-center text-white transition-colors focus:outline-none focus:ring-2 focus:ring-violet-400">
                        <i data-lucide="camera" class="w-4 h-4"></i>
                    </button>
                    <input type="file" name="foto_profil" id="foto_profil" class="sr-only" accept="image/*" onchange="previewFoto(event)">
                </div>
                <h2 class="font-bold text-lg text-slate-800 mt-2">{{ $user->name }}</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Pelanggan</span>
                @error('foto_profil')
                    <p class="text-xs text-rose-500 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-slate-100 mb-5">

            <h3 class="font-semibold text-slate-800 text-sm">Ubah Profil</h3>

            {{-- Nama Lengkap --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                       required>
                @error('name')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                       required>
                @error('email')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nomor HP --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nomor HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $pelanggan->no_hp ?? '') }}"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                       placeholder="08xxxxxxxxxx">
                @error('no_hp')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Alamat --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                <textarea name="alamat" rows="2"
                          class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                          placeholder="Alamat lengkap">{{ old('alamat', $pelanggan->alamat ?? '') }}</textarea>
                @error('alamat')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-violet-600 hover:bg-violet-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- Card Ganti Kata Sandi --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <form action="{{ route('pelanggan.profile.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <h3 class="font-semibold text-slate-800 text-sm">Ubah Password</h3>

            {{-- Password Lama --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password Lama</label>
                <input type="password" name="current_password"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                       required>
                @error('current_password')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Baru --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                <input type="password" name="password"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                       required>
                @error('password')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password Baru --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                       required>
            </div>

            <button type="submit"
                    class="w-full bg-rose-600 hover:bg-rose-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                Ubah Password
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewFoto(event) {
        const input = event.target;
        const preview = document.getElementById('preview-foto');
        const fallback = document.getElementById('profile-fallback');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (fallback) fallback.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush

