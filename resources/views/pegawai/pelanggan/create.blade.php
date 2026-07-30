@extends('layouts.pegawai')

@section('title', 'Tambah Pelanggan')
@section('page-title', 'Tambah Pelanggan')

@section('content')
<div class="max-w-5xl mx-auto">
    <a href="{{ route('pegawai.pelanggan.index') }}" class="flex items-center gap-2 text-slate-500 text-sm mb-4 hover:underline">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

        {{-- FORM --}}
        <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm p-5">
        <form action="{{ route('pegawai.pelanggan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Kode (readonly, auto) --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kode Pelanggan</label>
                <input type="text" id="kode-pelanggan" value="{{ $kode }}" readonly
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm bg-slate-50 text-slate-500">
            </div>

            {{-- Nama --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                       placeholder="Nama lengkap pelanggan"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 @error('nama') border-rose-300 @enderror">
                @error('nama') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Paket --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Paket</label>
                <input type="text" name="paket" value="{{ old('paket') }}"
                       placeholder="Contoh: 20 Mbps"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 @error('paket') border-rose-300 @enderror">
                @error('paket') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Alamat --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat</label>
                <textarea name="alamat" rows="3"
                          placeholder="Alamat lengkap pelanggan"
                          class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 @error('alamat') border-rose-300 @enderror">{{ old('alamat') }}</textarea>
                @error('alamat') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- No HP --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">No. HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                       placeholder="Contoh: 08123456789"
                       class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 @error('no_hp') border-rose-300 @enderror">
                @error('no_hp') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Status --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Status <span class="text-rose-500">*</span></label>
                <select name="status" required
                        class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
                    <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Foto Rumah --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Foto Rumah (opsional)</label>
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
                        class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>

        {{-- QR CODE PANEL --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                    <i data-lucide="qr-code" class="w-4 h-4 text-violet-600"></i>
                </div>
                <h3 class="font-semibold text-slate-800">QR Code Pelanggan</h3>
            </div>

            <div id="qr-container" class="bg-slate-50 rounded-xl p-6 text-center">
                <div id="qr-loading" class="py-8">
                    <div class="w-10 h-10 border-[3px] border-violet-100 border-t-violet-500 rounded-full animate-spin mx-auto mb-3"></div>
                    <p class="text-sm text-slate-400">Memuat QR Code...</p>
                </div>
                <div id="qr-display" class="hidden">
                    <div class="inline-block bg-white p-4 rounded-xl shadow-sm border border-slate-200">
                        <canvas id="qr-canvas" class="mx-auto"></canvas>
                    </div>
                    <p class="text-xs text-slate-400 mt-3">
                        Kode: <span id="qr-kode-display" class="font-mono font-bold text-violet-600">{{ $kode }}</span>
                    </p>
                    <p class="text-xs text-slate-400 mt-1">Scan QR ini untuk mengakses data pelanggan</p>
                    <div class="mt-4 flex gap-2 justify-center">
                        <button type="button" onclick="downloadQR()"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white text-xs font-semibold rounded-lg transition-colors">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                            Download QR
                        </button>
                        <button type="button" onclick="printQR()"
                                class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                            Cetak
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-4 p-3 bg-amber-50 rounded-xl border border-amber-200">
                <div class="flex items-start gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-amber-600 mt-0.5 shrink-0"></i>
                    <p class="text-xs text-amber-700">
                        QR Code ini otomatis tergenerate dari Kode Pelanggan. 
                        Gunakan QR ini untuk memudahkan scanning data pelanggan oleh pegawai.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    generateQR();
});

function generateQR() {
    const kode = document.getElementById('kode-pelanggan').value;
    if (!kode) return;

    const canvas = document.getElementById('qr-canvas');
    const container = document.getElementById('qr-display');
    const loading = document.getElementById('qr-loading');
    const kodeDisplay = document.getElementById('qr-kode-display');

    kodeDisplay.textContent = kode;

    // Generate QR using canvas
    QRCode.toCanvas(canvas, kode, { 
        width: 180,
        height: 180,
        margin: 2,
        color: {
            dark: '#1a1a2e',
            light: '#ffffff'
        }
    }, function (error) {
        loading.classList.add('hidden');
        container.classList.remove('hidden');
        if (error) console.error(error);
        if (window.lucide) lucide.createIcons();
    });
}

function downloadQR() {
    const canvas = document.getElementById('qr-canvas');
    const kode = document.getElementById('kode-pelanggan').value;
    const link = document.createElement('a');
    link.download = 'QR-' + kode + '.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}

function printQR() {
    const canvas = document.getElementById('qr-canvas');
    const kode = document.getElementById('kode-pelanggan').value;
    const dataUrl = canvas.toDataURL('image/png');
    
    const win = window.open('', '_blank');
    win.document.write(`
        <html>
        <head>
            <title>Cetak QR - ${kode}</title>
            <style>
                body { display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; background: #fff; }
                .container { text-align: center; padding: 20px; }
                img { width: 250px; height: 250px; }
                h2 { margin: 15px 0 5px; color: #1a1a2e; }
                p { color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class="container">
                <img src="${dataUrl}" alt="QR ${kode}">
                <h2>WiFiPay</h2>
                <p>Kode Pelanggan: <strong>${kode}</strong></p>
            </div>
            <script>window.onload = function() { window.print(); } <\/script>
        </body>
        </html>
    `);
    win.document.close();
}
</script>
@endpush

