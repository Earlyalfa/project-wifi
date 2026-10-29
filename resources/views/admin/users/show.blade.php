@extends('layouts.admin')

@section('title', 'Detail Pengguna')
@section('page-title', 'Detail Pengguna')

@section('content')
<div class="max-w-4xl mx-auto">
    <nav class="flex items-center gap-2 text-sm text-slate-400 mb-5" aria-label="Breadcrumb">
        <a href="{{ route('admin.users') }}" class="hover:text-violet-700 transition-colors">Data Pengguna</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300"></i>
        <span class="font-medium text-slate-700">Detail {{ ucfirst($user->role) }}</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-5">
            @if ($user->foto_profil)
                <img src="{{ asset('storage/'.$user->foto_profil) }}" alt="Foto profil {{ $user->name }}" class="w-14 h-14 rounded-full object-cover shrink-0">
            @else
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center shrink-0 shadow-sm">
                    <i data-lucide="user" class="w-7 h-7 text-white"></i>
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <h2 class="font-bold text-lg text-slate-800">{{ $user->name }}</h2>
                <p class="text-sm text-slate-400">{{ $user->email }}</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'bg-violet-100 text-violet-700' : 'bg-emerald-100 text-emerald-700' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $user->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-600' }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center justify-center gap-2 bg-violet-600 hover:bg-violet-700 text-white font-semibold px-4 py-2.5 rounded-lg transition-colors text-sm shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
        </div>

        <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Informasi Akun &amp; Profil</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                <i data-lucide="mail" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                <div><p class="text-xs text-slate-400 font-medium">Email</p><p class="text-sm text-slate-800 font-medium">{{ $user->email }}</p></div>
            </div>
            <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                <i data-lucide="phone" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                <div><p class="text-xs text-slate-400 font-medium">No. HP</p><p class="text-sm text-slate-800 font-medium">{{ $user->no_hp ?: '-' }}</p></div>
            </div>
            <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3 md:col-span-2">
                <i data-lucide="map-pin" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                <div><p class="text-xs text-slate-400 font-medium">Alamat</p><p class="text-sm text-slate-800 font-medium">{{ $user->alamat ?: '-' }}</p></div>
            </div>
            <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                <i data-lucide="calendar" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                <div><p class="text-xs text-slate-400 font-medium">Tanggal Bergabung</p><p class="text-sm text-slate-800 font-medium">{{ $user->created_at?->format('d F Y') ?? '-' }}</p></div>
            </div>
            <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                <i data-lucide="shield-check" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                <div><p class="text-xs text-slate-400 font-medium">Status Akun</p><p class="text-sm text-slate-800 font-medium">{{ ucfirst($user->status) }}</p></div>
            </div>
        </div>
    </div>
</div>
@endsection

