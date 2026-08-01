@extends('layouts.admin')

@section('title', 'Detail Pengguna')
@section('page-title', 'Detail Pengguna')

@section('content')
<div class="max-w-4xl mx-auto">
    <a href="{{ route('admin.users') }}" class="flex items-center gap-2 text-slate-500 text-sm mb-4 hover:underline">
    </a>

    <div class="grid lg:grid-cols-3 gap-5">
        {{-- KOLOM KIRI: Info User & Pelanggan --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- CARD INFO USER --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-start gap-4 mb-5">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shrink-0 shadow-sm">
                        <i data-lucide="user" class="w-7 h-7 text-white"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="font-bold text-lg text-slate-800">{{ $user->name }}</h2>
                                <p class="text-sm text-slate-400">{{ $user->email }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $user->role === 'admin' ? 'bg-violet-100 text-violet-700' : ($user->role === 'pegawai' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600') }}">
                                    <span class="w-1.5 h-1.5 rounded-full
                                        {{ $user->role === 'admin' ? 'bg-violet-600' : ($user->role === 'pegawai' ? 'bg-emerald-500' : 'bg-slate-400') }}"></span>
                                    {{ ucfirst($user->role) }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $user->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-600' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->status === 'aktif' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ ucfirst($user->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="map-pin" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Alamat</p>
                            <p class="text-sm text-slate-800 font-medium">{{ $pelanggan->alamat ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="wifi" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Paket WiFi</p>
                            <p class="text-sm text-slate-800 font-medium">{{ $pelanggan->paket ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="phone" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">No. HP</p>
                            <p class="text-sm text-slate-800 font-medium">{{ $pelanggan->no_hp ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="calendar" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Tanggal Daftar</p>
                            <p class="text-sm text-slate-800 font-medium">{{ $user->created_at->format('d F Y') }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="check-circle" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Status Akun</p>
                            <p class="text-sm">
                                @if ($user->status === 'aktif')
                                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 font-semibold text-rose-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Nonaktif
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="tag" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Kode Pelanggan</p>
                            <p class="text-sm text-slate-800 font-medium">{{ $pelanggan->kode ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Foto & Aksi --}}
        <div class="space-y-4">
            {{-- Foto Rumah --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                        <i data-lucide="camera" class="w-4 h-4 text-violet-600"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Foto Rumah</h3>
                </div>
                @if ($pelanggan && $pelanggan->foto_rumah)
                    <img src="{{ asset('storage/'.$pelanggan->foto_rumah) }}"
                         class="w-full h-48 object-cover rounded-lg border border-slate-200 mb-2">
                @else
                    <div class="bg-slate-50 rounded-xl py-8 text-center">
                        <i data-lucide="image-off" class="w-10 h-10 text-slate-300 mx-auto mb-2"></i>
                        <p class="text-sm text-slate-400">Belum ada foto rumah</p>
                    </div>
                @endif
            </div>

            {{-- Tombol Aksi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 space-y-3">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="w-full flex items-center justify-center gap-2 bg-violet-600 hover:bg-violet-700 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                    Edit 
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

