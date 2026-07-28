@extends('layouts.admin')

@section('title', 'Edit Paket WiFi')
@section('page-title', 'Edit Paket WiFi')

@section('content')
<div class="max-w-lg mx-auto">
    <a href="{{ route('admin.paket.index') }}" class="flex items-center gap-2 text-slate-500 text-sm mb-4 hover:underline">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <form action="{{ route('admin.paket.update', $paket) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nama Paket --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Paket <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $paket->nama) }}" required
                       placeholder="Contoh: 20 Mbps"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('nama') border-rose-300 @enderror">
                @error('nama') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Harga --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Harga (Rp) <span class="text-rose-500">*</span></label>
                <input type="number" name="harga" value="{{ old('harga', $paket->harga) }}" required min="0"
                       placeholder="Contoh: 150000"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('harga') border-rose-300 @enderror">
                @error('harga') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                          placeholder="Deskripsi paket WiFi (opsional)"
                          class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('deskripsi') border-rose-300 @enderror">{{ old('deskripsi', $paket->deskripsi) }}</textarea>
                @error('deskripsi') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Status --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Status <span class="text-rose-500">*</span></label>
                <select name="status" required
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="aktif" {{ old('status', $paket->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $paket->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3">
                <a href="{{ route('admin.paket.index') }}"
                   class="flex-1 text-center border border-slate-200 text-slate-600 font-semibold py-2.5 rounded-lg text-sm hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                    <i data-lucide="save" class="w-4 h-4 inline-block -mt-0.5"></i> Perbarui
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

