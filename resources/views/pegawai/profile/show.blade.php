@extends('layouts.pegawai')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-5">
        <div class="flex items-center gap-4 mb-5">
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-sm">
                <span class="text-2xl font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
            <div>
                <h2 class="font-bold text-lg text-slate-800">{{ $user->name }}</h2>
                <p class="text-sm text-slate-400">{{ $user->email }}</p>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700 mt-1">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>

        <hr class="border-slate-100 mb-5">

        {{-- Form Update Profil --}}
        <form action="{{ route('pegawai.profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <h3 class="font-semibold text-slate-800 text-sm">Informasi Dasar</h3>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       required>
                @error('name')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       required>
                @error('email')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- Card Ganti Kata Sandi --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <form action="{{ route('pegawai.profile.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <h3 class="font-semibold text-slate-800 text-sm">Ganti Kata Sandi</h3>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Kata Sandi Saat Ini</label>
                <input type="password" name="current_password"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       required>
                @error('current_password')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Kata Sandi Baru</label>
                <input type="password" name="password"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       required>
                @error('password')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       required>
            </div>

            <button type="submit"
                    class="w-full bg-rose-600 hover:bg-rose-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                Ganti Kata Sandi
            </button>
        </form>
    </div>
</div>
@endsection
