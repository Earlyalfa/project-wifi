@extends('layouts.pegawai')

@section('title', 'Edit Pelanggan')
@section('page-title', 'Edit Pelanggan')

@section('content')
<div class="max-w-lg mx-auto">
    <a href="{{ route('pegawai.pelanggan.index') }}" class="flex items-center gap-2 text-slate-500 text-sm mb-4 hover:underline">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali
    </a>

    <div class="bg-white rounded-2xl shadow-sm p-5">
        <form action="{{ route('pegawai.pelanggan.update', $pelanggan) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Kode (readonly) --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kode Pelanggan</label>
                <input type="text" value="{{ $pelanggan->kode }}" readonly
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm bg-slate-50 text-slate-500">
            </div>

            {{-- Nama --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $pelanggan->nama) }}" required
                       placeholder="Nama lengkap pelanggan"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 @error('nama') border-rose-300 @enderror">
                @error('nama') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Paket --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Paket</label>
                <input type="text" name="paket" value="{{ old('paket', $pelanggan->paket) }}"
                       placeholder="Contoh: 20 Mbps"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 @error('paket') border-rose-300 @enderror">
                @error('paket') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Alamat --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat</label>
                <textarea name="alamat" rows="3"
                          placeholder="Alamat lengkap pelanggan"
                          class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 @error('alamat') border-rose-300 @enderror">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                @error('alamat') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- No HP --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">No. HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $pelanggan->no_hp) }}"
                       placeholder="Contoh: 08123456789"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 @error('no_hp') border-rose-300 @enderror">
                @error('no_hp') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Status --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Status <span class="text-rose-500">*</span></label>
                <select name="status" required
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                    <option value="aktif" {{ old('status', $pelanggan->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $pelanggan->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Foto Rumah --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Foto Rumah (opsional)</label>
                @if ($pelanggan->foto_rumah)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$pelanggan->foto_rumah) }}" class="w-32 h-24 object-cover rounded-lg border">
                        <p class="text-xs text-slate-400 mt-1">Foto saat ini. Upload foto baru untuk mengganti.</p>
                    </div>
                @endif
                <input type="file" name="foto_rumah" accept="image/jpg,image/jpeg,image/png"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 @error('foto_rumah') border-rose-300 @enderror">
                @error('foto_rumah') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3">
                <a href="{{ route('pegawai.pelanggan.index') }}"
                   class="flex-1 text-center border border-slate-200 text-slate-600 font-semibold py-2.5 rounded-lg text-sm hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-violet-600 hover:bg-violet-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

