@extends('layouts.pegawai')

@section('title', 'Tambah Pelanggan')
@section('page-title', 'Tambah Pelanggan')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-slate-400 mb-5">
        <a href="{{ route('pegawai.pelanggan.index') }}" class="hover:text-violet-600 transition-colors">Data Pelanggan</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-slate-700 font-medium">Tambah Baru</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                <i data-lucide="user-plus" class="w-5 h-5 text-violet-600"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Tambah Pelanggan Baru</h2>
                <p class="text-sm text-slate-400">Lengkapi data pelanggan untuk menambahkan ke sistem</p>
            </div>

        <form action="{{ route('pegawai.pelanggan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                {{-- Informasi Dasar --}}
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                        <i data-lucide="info" class="w-4 h-4 text-violet-500"></i>
                        Informasi Dasar
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama') }}" required
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400/30 focus:border-violet-400 transition-all @error('nama') border-rose-300 @enderror"
                                   placeholder="Masukkan nama lengkap">
                            @error('nama') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400/30 focus:border-violet-400 transition-all @error('email') border-rose-300 @enderror"
                                   placeholder="contoh@email.com">
                            @error('email') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">No. HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400/30 focus:border-violet-400 transition-all @error('no_hp') border-rose-300 @enderror"
                                   placeholder="Contoh: 08123456789">
                            @error('no_hp') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-violet-500"></i>
                        Alamat
                    </h3>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1.5">Alamat Lengkap <span class="text-rose-500">*</span></label>
                        <textarea name="alamat" rows="3" required
                                  class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400/30 focus:border-violet-400 transition-all @error('alamat') border-rose-300 @enderror"
                                  placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                        @error('alamat') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                {{-- Paket --}}
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                        <i data-lucide="wifi" class="w-4 h-4 text-violet-500"></i>
                        Paket WiFi
                    </h3>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1.5">Pilih Paket <span class="text-rose-500">*</span></label>
                        <select name="paket" required
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400/30 focus:border-violet-400 transition-all @error('paket') border-rose-300 @enderror">
                            <option value="">Pilih Paket</option>
                            @foreach ($paketList as $p)
                                <option value="{{ $p->nama }}" {{ old('paket') === $p->nama ? 'selected' : '' }}>
                                    {{ $p->nama }} - Rp {{ number_format($p->harga, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('paket') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center justify-between pt-4 mt-6 border-t border-slate-100">
                <a href="{{ route('pegawai.pelanggan.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition-all">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-violet-600 to-violet-700 text-white text-sm font-semibold hover:from-violet-700 hover:to-violet-800 transition-all shadow-sm">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
