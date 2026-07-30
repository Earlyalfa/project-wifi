@extends('layouts.pelanggan')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Card: Informasi Profile --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-5">
        <div class="flex items-center gap-4 mb-5">
            {{-- Foto Profil --}}
            <div class="relative w-16 h-16 shrink-0">
                @if ($pelanggan && $pelanggan->foto_profil)
                    <img src="{{ asset('storage/' . $pelanggan->foto_profil) }}"
                         alt="Foto Profil"
                         class="w-16 h-16 rounded-full object-cover border-2 border-violet-100">
                @else
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shadow-sm">
                        <span class="text-2xl font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div>
                <h2 class="font-bold text-lg text-slate-800">{{ $user->name }}</h2>
                <p class="text-sm text-slate-400">{{ $user->email }}</p>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 mt-1">
                    Pelanggan
                </span>
            </div>
        </div>

        <hr class="border-slate-100 mb-5">

        {{-- Form Update Profil --}}
        <form action="{{ route('pelanggan.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PATCH')

            <h3 class="font-semibold text-slate-800 text-sm">Ubah Profil</h3>

            {{-- Foto Profil --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Foto Profil</label>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center overflow-hidden shrink-0">
                        @if ($pelanggan && $pelanggan->foto_profil)
                            <img src="{{ asset('storage/' . $pelanggan->foto_profil) }}"
                                 alt="Preview"
                                 class="w-full h-full object-cover"
                                 id="preview-foto">
                        @else
                            <i data-lucide="camera" class="w-5 h-5 text-slate-400" id="preview-icon"></i>
                            <img src="" alt="Preview" class="w-full h-full object-cover hidden" id="preview-foto">
                        @endif
                    </div>
                    <input type="file" name="foto_profil" id="foto_profil"
                           class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-600 hover:file:bg-violet-100 transition-colors"
                           accept="image/*"
                           onchange="previewFoto(event)">
                </div>
                @error('foto_profil')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

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
        const icon = document.getElementById('preview-icon');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (icon) icon.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush

