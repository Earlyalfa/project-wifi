@extends('layouts.pegawai')

@section('title', 'Tambah Pengaduan')
@section('page-title', 'Tambah Pengaduan Gangguan')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('pegawai.gangguan.index') }}" class="flex items-center gap-2 text-slate-500 text-sm mb-4 hover:underline">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                <i data-lucide="message-square-warning" class="w-4 h-4 text-amber-600"></i>
            </div>
            <div>
                <h2 class="font-semibold text-slate-800">Form Pengaduan Gangguan</h2>
                <p class="text-xs text-slate-400">No Tiket: <span class="font-mono font-semibold text-indigo-600">{{ $noTiket }}</span></p>
            </div>
        </div>

        <form action="{{ route('pegawai.gangguan.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Pelanggan</label>
                <select name="pelanggan_id" required
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach ($pelanggans as $p)
                        <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->kode }} — {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
                @error('pelanggan_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Keluhan</label>
                <textarea name="keluhan" rows="4" required
                          placeholder="Jelaskan keluhan yang dialami pelanggan..."
                          class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">{{ old('keluhan') }}</textarea>
                @error('keluhan') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors">
                    Simpan Pengaduan
                </button>
                <a href="{{ route('pegawai.gangguan.index') }}"
                   class="text-sm text-slate-500 hover:text-slate-700 font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

