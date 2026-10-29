@extends('layouts.admin')

@section('title', 'Edit Pelanggan')
@section('page-title', 'Edit Pelanggan')

@section('content')
@php
    $activePackageNames = $paketList->pluck('nama')->all();
    $currentPackageIsCustom = $pelanggan?->paket && !in_array($pelanggan->paket, $activePackageNames, true);
    $selectedPackage = old('paket', $currentPackageIsCustom ? 'custom' : ($pelanggan->paket ?? ''));
@endphp

<div class="max-w-3xl mx-auto">
    <nav class="flex items-center gap-2 text-sm text-slate-400 mb-6">
        <a href="{{ route('admin.users') }}" class="hover:text-[#7C3AED] transition-colors">Data Pengguna</a>
        <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7-7"/></svg>
        <a href="{{ route('admin.users.show', $user) }}" class="hover:text-[#7C3AED] transition-colors truncate max-w-[160px]">{{ $pelanggan->nama ?? $user->name }}</a>
        <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7-7"/></svg>
        <span class="text-slate-700 font-medium">Edit</span>
    </nav>

    <div class="bg-white rounded-[20px] shadow-lg shadow-slate-200/60 border border-slate-100 p-6 md:p-10">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-11 h-11 rounded-2xl bg-[#7C3AED]/10 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-[#7C3AED]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Edit Pelanggan</h2>
                <p class="text-sm text-slate-400 mt-0.5">Perbarui data pelanggan <span class="text-slate-600 font-medium">{{ $pelanggan->nama ?? $user->name }}</span></p>
            </div>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2" for="customer-name">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input id="customer-name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full h-11 rounded-xl border border-slate-200 bg-slate-50/50 px-4 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/20 focus:border-[#7C3AED] focus:bg-white transition-all @error('name') border-rose-300 bg-rose-50/30 @enderror"
                           placeholder="Masukkan nama lengkap">
                    @error('name') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2" for="customer-phone">No. HP</label>
                    <input id="customer-phone" type="text" name="no_hp" value="{{ old('no_hp', $pelanggan->no_hp ?? $user->no_hp) }}"
                           class="w-full h-11 rounded-xl border border-slate-200 bg-slate-50/50 px-4 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/20 focus:border-[#7C3AED] focus:bg-white transition-all @error('no_hp') border-rose-300 bg-rose-50/30 @enderror"
                           placeholder="Contoh: 08123456789">
                    @error('no_hp') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2" for="customer-address">Alamat Lengkap <span class="text-rose-500">*</span></label>
                <textarea id="customer-address" name="alamat" rows="4" required
                          class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/20 focus:border-[#7C3AED] focus:bg-white transition-all resize-none @error('alamat') border-rose-300 bg-rose-50/30 @enderror"
                          placeholder="Masukkan alamat lengkap">{{ old('alamat', $pelanggan->alamat ?? $user->alamat) }}</textarea>
                @error('alamat') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2" for="customer-package">Paket WiFi</label>
                    <select name="paket" id="customer-package"
                            class="w-full h-11 rounded-xl border border-slate-200 bg-slate-50/50 px-4 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/20 focus:border-[#7C3AED] focus:bg-white transition-all appearance-none cursor-pointer @error('paket') border-rose-300 bg-rose-50/30 @enderror"
                            style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%2394a3b8%22 stroke-width=%222%22><path stroke-linecap=%22round%22 stroke-linejoin=%22round%22 d=%22M19 9l-7 7-7-7%22/></svg>'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1rem;">
                        <option value="">Pilih Paket</option>
                        @foreach ($paketList as $paket)
                            <option value="{{ $paket->nama }}" {{ $selectedPackage === $paket->nama ? 'selected' : '' }}>{{ $paket->nama }} - Rp {{ number_format($paket->harga, 0, ',', '.') }}</option>
                        @endforeach
                        @if ($currentPackageIsCustom)
                            <option value="custom" {{ $selectedPackage === 'custom' ? 'selected' : '' }}>Paket khusus: {{ $pelanggan->paket }}</option>
                        @else
                            <option value="custom" {{ $selectedPackage === 'custom' ? 'selected' : '' }}>Lainnya (ketik manual)</option>
                        @endif
                    </select>
                    <input type="text" name="paket_custom" id="customer-package-custom" value="{{ old('paket_custom', $currentPackageIsCustom ? $pelanggan->paket : '') }}"
                           placeholder="Atau ketik paket custom..."
                           class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-2 text-sm mt-2 focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/20 focus:border-[#7C3AED] focus:bg-white transition-all @error('paket_custom') border-rose-300 bg-rose-50/30 @enderror"
                           style="display: none;">
                    @error('paket') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                    @error('paket_custom') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2" for="customer-status">Status</label>
                    <select id="customer-status" name="status"
                            class="w-full h-11 rounded-xl border border-slate-200 bg-slate-50/50 px-4 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/20 focus:border-[#7C3AED] focus:bg-white transition-all appearance-none cursor-pointer"
                            style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%2394a3b8%22 stroke-width=%222%22><path stroke-linecap=%22round%22 stroke-linejoin=%22round%22 d=%22M19 9l-7 7-7-7%22/></svg>'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1rem;">
                        <option value="aktif" {{ old('status', $user->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $user->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2" for="customer-due-day">Tanggal Jatuh Tempo Tagihan</label>
                    <select id="customer-due-day" name="tagihan_jatuh_tempo"
                            class="w-full h-11 rounded-xl border border-slate-200 bg-slate-50/50 px-4 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/20 focus:border-[#7C3AED] focus:bg-white transition-all appearance-none cursor-pointer @error('tagihan_jatuh_tempo') border-rose-300 bg-rose-50/30 @enderror"
                            style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 24 24%22 stroke=%22%2394a3b8%22 stroke-width=%222%22><path stroke-linecap=%22round%22 stroke-linejoin=%22round%22 d=%22M19 9l-7 7-7-7%22/></svg>'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1rem;">
                        <option value="">Pilih Tanggal</option>
                        @for ($day = 1; $day <= 31; $day++)
                            <option value="{{ $day }}" {{ old('tagihan_jatuh_tempo', $pelanggan->tagihan_jatuh_tempo ?? '') == $day ? 'selected' : '' }}>Setiap tanggal {{ $day }}</option>
                        @endfor
                    </select>
                    @error('tagihan_jatuh_tempo') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-600 mb-2">Foto Rumah</label>
                @if ($pelanggan?->foto_rumah)
                    <p class="text-xs text-slate-400 mt-2 mb-2">Foto sebelumnya: <a href="{{ asset('storage/'.$pelanggan->foto_rumah) }}" target="_blank" rel="noopener" class="text-[#7C3AED] hover:underline font-medium">Lihat Foto</a></p>
                @endif
                <input type="file" name="foto_rumah" accept="image/jpg,image/jpeg,image/png"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm text-slate-500 file:mr-4 file:border-0 file:bg-[#7C3AED]/10 file:text-[#7C3AED] file:text-xs file:font-semibold file:rounded-lg file:px-4 file:py-2 hover:file:bg-[#7C3AED]/15 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/20 focus:border-[#7C3AED] focus:bg-white transition-all @error('foto_rumah') border-rose-300 bg-rose-50/30 @enderror">
                @error('foto_rumah') <p class="text-xs text-rose-500 mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-8 mt-8">
                <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-slate-200 bg-white text-slate-600 text-sm font-semibold hover:bg-slate-50 hover:border-slate-300 transition-all w-full sm:w-auto">Batal</a>
                <button type="submit" class="inline-flex items-center justify-center px-7 py-3 rounded-xl bg-gradient-to-r from-[#7C3AED] to-[#6D28D9] text-white text-sm font-semibold hover:from-[#6D28D9] hover:to-[#5B21B6] transition-all shadow-md shadow-[#7C3AED]/25 w-full sm:w-auto">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const packageSelect = document.getElementById('customer-package');
    const customPackage = document.getElementById('customer-package-custom');
    if (!packageSelect || !customPackage) return;

    const updateCustomPackageVisibility = () => {
        customPackage.style.display = packageSelect.value === 'custom' ? 'block' : 'none';
    };

    packageSelect.addEventListener('change', updateCustomPackageVisibility);
    updateCustomPackageVisibility();
});
</script>
@endpush