@extends('layouts.pegawai')

@section('title', 'Scan QR ID Pelanggan')
@section('page-title', 'Scan QR Code Pelanggan')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="max-w-7xl mx-auto">

    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h3 class="text-lg font-semibold text-slate-800">Cari pelanggan untuk melihat data dan tagihan</h3>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-400 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>{{ now()->format('l, d F Y') }}</span>
        </div>
    </div>

    {{-- Search Bar --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-6">
        <div class="p-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" id="manual-kode"
                           placeholder="Masukkan kode pelanggan — contoh: CS-0001"
                           class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition placeholder:text-slate-400"
                           onkeydown="if(event.key==='Enter') cariPelanggan(this.value)">
                </div>
                <button type="button" onclick="cariPelanggan(document.getElementById('manual-kode').value)"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm hover:shadow-md active:scale-[0.98]">
                    Cari
                </button>
            </div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Left Column: Scanner (3/5 width on large) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Card: QR Scanner --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-emerald-100 flex items-center justify-center">
                            <i data-lucide="scan-qr-code" class="w-5 h-5 text-emerald-600"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Kamera Scanner</h3>
                            <p class="text-xs text-slate-400">Arahkan ke QR Code pelanggan</p>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div id="scanner-container" class="relative bg-slate-100 rounded-lg overflow-hidden mb-4" style="min-height:220px;">
                        <video id="scanner-video" class="w-full h-full object-cover" playsinline></video>
                        <div id="scanner-loading" class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-10 h-10 border-[3px] border-emerald-100 border-t-emerald-500 rounded-full animate-spin mx-auto mb-3"></div>
                                <p class="text-sm text-slate-400">Mengaktifkan kamera...</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" id="btn-start-scan"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition shadow-sm active:scale-[0.98]">
                            Mulai Scan
                        </button>
                        <button type="button" id="btn-stop-scan" style="display:none;"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium rounded-lg transition shadow-sm active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Hentikan
                        </button>
                    </div>
                </div>
            </div>

            {{-- Card: Upload QR --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Upload Gambar</h3>
                            <p class="text-xs text-slate-400">Dari galeri atau tangkapan layar</p>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div onclick="document.getElementById('upload-qr').click()"
                         class="border-2 border-dashed border-slate-200 rounded-lg p-8 text-center hover:border-blue-400 hover:bg-blue-50/30 transition cursor-pointer group">
                        <input type="file" id="upload-qr" accept="image/*" class="hidden" onchange="processUploadedQR(event)">
                        <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-100 transition">
                            <svg class="w-6 h-6 text-slate-300 group-hover:text-blue-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-slate-500 group-hover:text-blue-600 transition">Klik untuk upload gambar</p>
                        <p class="text-xs text-slate-400 mt-1">Format: JPG, PNG — Maks. 5MB</p>
                    </div>
                    <canvas id="upload-canvas" class="hidden"></canvas>
                </div>
            </div>

        </div>

        {{-- Right Column: Results (2/5 width on large) --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- Empty State --}}
            <div id="hasil-awal" class="bg-white rounded-xl shadow-sm border border-slate-200 p-10 text-center">
                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-5">
                    <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-700 mb-2">Belum Ada Data</h3>
                <p class="text-sm text-slate-400 max-w-md mx-auto leading-relaxed">
                    Gunakan kamera untuk memindai QR Code pelanggan, upload gambar QR, atau masukkan kode ID pelanggan pada kolom pencarian di atas.
                </p>
            </div>

            {{-- Loading State --}}
            <div id="loading-data" class="hidden bg-white rounded-xl shadow-sm border border-slate-200 p-10 text-center">
                <div class="w-12 h-12 border-[3px] border-indigo-100 border-t-indigo-500 rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-sm font-medium text-slate-500">Memuat data pelanggan...</p>
            </div>

            {{-- Error State --}}
            <div id="error-data" class="hidden bg-white rounded-xl shadow-sm border border-red-200 p-8 text-center">
                <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h4 class="text-base font-semibold text-slate-800 mb-1">Data Tidak Ditemukan</h4>
                <p id="error-message" class="text-sm text-slate-500"></p>
                <button onclick="resetScan()" class="mt-4 text-sm text-indigo-600 hover:text-indigo-700 font-medium underline underline-offset-2">
                    Coba lagi
                </button>
            </div>

            {{-- Result Data --}}
            <div id="hasil-data" class="hidden space-y-5">

                {{-- Card: Data Pelanggan --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-5 py-4 bg-slate-50 border-b border-slate-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <h3 class="text-sm font-semibold text-slate-800">Informasi Pelanggan</h3>
                            </div>
                            <span id="status-akun" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"></span>
                        </div>
                    </div>
                    <div class="divide-y divide-slate-50">
                        <div class="grid grid-cols-2 divide-x divide-slate-50">
                            <div class="px-5 py-3.5">
                                <p class="text-xs text-slate-400 mb-0.5">Nama</p>
                                <p id="data-nama" class="text-sm font-semibold text-slate-800">-</p>
                            </div>
                            <div class="px-5 py-3.5">
                                <p class="text-xs text-slate-400 mb-0.5">ID Pelanggan</p>
                                <p id="data-kode" class="text-sm font-mono font-bold text-indigo-600">-</p>
                            </div>
                        </div>
                        <div class="px-5 py-3.5">
                            <p class="text-xs text-slate-400 mb-0.5">Alamat</p>
                            <p id="data-alamat" class="text-sm font-semibold text-slate-800">-</p>
                        </div>
                        <div class="grid grid-cols-2 divide-x divide-slate-50">
                            <div class="px-5 py-3.5">
                                <p class="text-xs text-slate-400 mb-0.5">No. HP</p>
                                <p id="data-nohp" class="text-sm font-semibold text-slate-800">-</p>
                            </div>
                            <div class="px-5 py-3.5">
                                <p class="text-xs text-slate-400 mb-0.5">Paket WiFi</p>
                                <p id="data-paket" class="text-sm font-semibold text-slate-800">-</p>
                            </div>
                        </div>
                    </div>
                    <div id="foto-rumah-container" class="hidden px-5 pb-5">
                        <p class="text-xs text-slate-400 mb-2">Foto Rumah</p>
                        <img id="foto-rumah-img" src="" alt="Foto Rumah" class="w-full h-40 object-cover rounded-lg border border-slate-200">
                    </div>
                </div>

                {{-- Card: Tagihan --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-5 py-4 bg-slate-50 border-b border-slate-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                <h3 class="text-sm font-semibold text-slate-800">Tagihan</h3>
                            </div>
                            <span id="status-tagihan" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold"></span>
                        </div>
                    </div>
                    <div class="p-5">
                        <div id="tagihan-ada" class="hidden">
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-slate-50 rounded-lg p-3">
                                    <p class="text-xs text-slate-400 mb-0.5">Periode</p>
                                    <p id="tagihan-periode" class="text-sm font-semibold text-slate-800">-</p>
                                </div>
                                <div class="bg-slate-50 rounded-lg p-3">
                                    <p class="text-xs text-slate-400 mb-0.5">Jatuh Tempo</p>
                                    <p id="tagihan-jatuh-tempo" class="text-sm font-semibold text-slate-800">-</p>
                                </div>
                            </div>
                            <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-100 mb-4">
                                <p class="text-xs text-indigo-500 font-medium mb-1">Total Tagihan</p>
                                <p id="tagihan-nominal" class="text-xl font-bold text-indigo-700">-</p>
                            </div>

                            {{-- Payment Confirmation Form --}}
                            <div id="form-konfirmasi" class="border-t border-slate-100 pt-4 mt-4 hidden space-y-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <h4 class="text-sm font-semibold text-slate-700">Konfirmasi Pembayaran Tunai</h4>
                                </div>

                                <input type="hidden" id="form-pelanggan-id" value="">
                                <input type="hidden" id="form-tagihan-id" value="">
                                <input type="hidden" id="form-tagihan-jumlah" value="0">

                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Metode Pembayaran</label>
                                    <div class="flex items-center gap-2.5 px-3.5 py-2.5 bg-emerald-50 rounded-lg border border-emerald-200">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span class="text-sm font-medium text-emerald-700">Tunai (Cash)</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Nominal Diterima</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <span class="text-sm font-bold text-slate-400">Rp</span>
                                        </div>
                                        <input type="number" id="form-nominal" min="0"
                                               class="w-full pl-12 pr-4 py-2.5 text-sm font-semibold text-slate-800 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1.5">Catatan <span class="text-slate-400 font-normal">(opsional)</span></label>
                                    <textarea id="form-catatan" rows="2" placeholder="Tambahkan catatan..."
                                              class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition"></textarea>
                                </div>
                                <button type="button" onclick="konfirmasiPembayaran()"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition shadow-sm hover:shadow active:scale-[0.98]">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Konfirmasi Pembayaran
                                </button>
                            </div>
                        </div>
                        <div id="tagihan-tidak-ada" class="hidden text-center py-6">
                            <div class="w-14 h-14 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-emerald-700">Tidak ada tagihan tertunggak</p>
                            <p class="text-xs text-slate-400 mt-1">Semua pembayaran pelanggan ini sudah lunas.</p>
                        </div>
                    </div>
                </div>

                {{-- Card: Riwayat Pembayaran --}}
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-5 py-4 bg-slate-50 border-b border-slate-200">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-sm font-semibold text-slate-800">Riwayat Pembayaran</h3>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-100">
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Periode</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Nominal</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Tgl Bayar</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody id="riwayat-body" class="divide-y divide-slate-50"></tbody>
                        </table>
                        <p id="riwayat-kosong" class="hidden text-center py-8 text-sm text-slate-400">Belum ada riwayat pembayaran.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined' && lucide.createIcons) {
        lucide.createIcons();
    }
    initScanner();
});

// ===================== SCANNER =====================
let stream = null;
let scanning = false;
let animationId = null;
const video = document.getElementById('scanner-video');
const loading = document.getElementById('scanner-loading');
const btnStart = document.getElementById('btn-start-scan');
const btnStop = document.getElementById('btn-stop-scan');

function initScanner() {
    if (btnStart) btnStart.addEventListener('click', startCamera);
    if (btnStop) btnStop.addEventListener('click', stopCamera);
}

function startCamera() {
    if (loading) loading.style.display = 'flex';
    navigator.mediaDevices.getUserMedia({
        video: { facingMode: 'environment', width: 640, height: 480 }
    }).then(function (s) {
        stream = s;
        video.srcObject = s;
        video.play();
        if (loading) loading.style.display = 'none';
        scanning = true;
        if (btnStart) btnStart.style.display = 'none';
        if (btnStop) btnStop.style.display = 'inline-flex';
        scanLoop();
    }).catch(function (err) {
        if (loading) loading.innerHTML = '<p class="text-sm text-red-500">Kamera tidak tersedia: ' + err.message + '</p>';
    });
}

function stopCamera() {
    scanning = false;
    if (animationId) { cancelAnimationFrame(animationId); animationId = null; }
    if (stream) { stream.getTracks().forEach(function(t) { t.stop(); }); stream = null; }
    video.srcObject = null;
    if (btnStart) btnStart.style.display = 'inline-flex';
    if (btnStop) btnStop.style.display = 'none';
    if (loading) { loading.style.display = 'flex'; loading.innerHTML = '<div class="text-center"><div class="w-10 h-10 border-[3px] border-emerald-100 border-t-emerald-500 rounded-full animate-spin mx-auto mb-3"></div><p class="text-sm text-slate-400">Mengaktifkan kamera...</p></div>'; }
}

function scanLoop() {
    if (!scanning) return;
    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        var canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        var ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        var code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 'dontInvert' });
        if (code && code.data) {
            scanning = false;
            stopCamera();
            cariPelanggan(code.data);
            return;
        }
    }
    animationId = requestAnimationFrame(scanLoop);
}

// ===================== UPLOAD QR =====================
function processUploadedQR(event) {
    var file = event.target.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (e) {
        var img = new Image();
        img.onload = function () {
            var canvas = document.getElementById('upload-canvas');
            canvas.width = img.width;
            canvas.height = img.height;
            var ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);
            var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            var code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 'attemptBoth' });
            if (code && code.data) {
                cariPelanggan(code.data);
            } else {
                Swal.fire({ icon: 'warning', title: 'Gagal Membaca QR', text: 'Tidak dapat membaca QR Code dari gambar yang diupload.', confirmButtonColor: '#e11d48' });
            }
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

// ===================== RESET =====================
function resetScan() {
    document.getElementById('hasil-awal').classList.remove('hidden');
    document.getElementById('hasil-data').classList.add('hidden');
    document.getElementById('error-data').classList.add('hidden');
    document.getElementById('loading-data').classList.add('hidden');
    cacheData = null;
}

// ===================== AJAX SEARCH =====================
let cacheData = null;

function cariPelanggan(kode) {
    if (!kode || kode.trim() === '') return;

    var ha = document.getElementById('hasil-awal');
    var hd = document.getElementById('hasil-data');
    var ed = document.getElementById('error-data');
    var ld = document.getElementById('loading-data');

    if (ha) ha.classList.add('hidden');
    if (hd) hd.classList.add('hidden');
    if (ed) ed.classList.add('hidden');
    if (ld) ld.classList.remove('hidden');

    fetch('{{ route("pegawai.scan-barcode.cari") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ kode: kode.trim() }),
    })
    .then(function (res) {
        if (!res.ok) { return res.json().then(function (e) { throw e; }); }
        return res.json();
    })
    .then(function (data) {
        if (ld) ld.classList.add('hidden');
        if (data.success) {
            cacheData = data;
            tampilkanData(data);
        } else {
            tampilkanError(data.message || 'Data tidak ditemukan.');
        }
    })
    .catch(function (err) {
        if (ld) ld.classList.add('hidden');
        tampilkanError(err.message || 'Terjadi kesalahan saat menghubungi server.');
    });
}

// ===================== DISPLAY DATA =====================
function tampilkanData(data) {
    var p = data.pelanggan;
    var t = data.tagihan;

    var hd = document.getElementById('hasil-data');
    var ed = document.getElementById('error-data');

    hd.classList.remove('hidden');
    ed.classList.add('hidden');

    document.getElementById('data-nama').textContent = p.nama;
    document.getElementById('data-kode').textContent = p.kode;
    document.getElementById('data-alamat').textContent = p.alamat;
    document.getElementById('data-nohp').textContent = p.no_hp;
    document.getElementById('data-paket').textContent = p.paket;

    var sa = document.getElementById('status-akun');
    if (p.status === 'aktif') {
        sa.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700';
        sa.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> AKTIF';
    } else {
        sa.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600';
        sa.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> NONAKTIF';
    }

    var fc = document.getElementById('foto-rumah-container');
    if (p.foto_rumah) {
        document.getElementById('foto-rumah-img').src = p.foto_rumah;
        fc.classList.remove('hidden');
    } else {
        fc.classList.add('hidden');
    }

    var ta = document.getElementById('tagihan-ada');
    var tt = document.getElementById('tagihan-tidak-ada');
    var st = document.getElementById('status-tagihan');
    var fk = document.getElementById('form-konfirmasi');
    var ni = document.getElementById('form-nominal');

    if (t) {
        ta.classList.remove('hidden');
        tt.classList.add('hidden');
        document.getElementById('tagihan-periode').textContent = t.periode;
        document.getElementById('tagihan-jatuh-tempo').textContent = t.jatuh_tempo;
        document.getElementById('tagihan-nominal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(t.jumlah);
        st.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-' + t.status_color + '-50 text-' + t.status_color + '-700';
        st.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-' + t.status_color + '-500"></span> ' + t.status_label.toUpperCase();

        document.getElementById('form-pelanggan-id').value = data.pelanggan_id;
        document.getElementById('form-tagihan-id').value = t.id;
        document.getElementById('form-tagihan-jumlah').value = t.jumlah;
        ni.value = t.jumlah;

        if (t.status === 'belum_bayar' || t.status === 'menunggu_verifikasi') {
            fk.classList.remove('hidden');
        } else {
            fk.classList.add('hidden');
        }
    } else {
        ta.classList.add('hidden');
        tt.classList.remove('hidden');
        st.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700';
        st.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> LUNAS';
    }

    var rb = document.getElementById('riwayat-body');
    var rk = document.getElementById('riwayat-kosong');
    rb.innerHTML = '';
    if (data.riwayat_pembayaran && data.riwayat_pembayaran.length > 0) {
        rk.classList.add('hidden');
        data.riwayat_pembayaran.forEach(function (rp) {
            var color = rp.status === 'lunas' ? 'emerald' : (rp.status === 'menunggu_verifikasi' ? 'amber' : 'red');
            var tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-50 transition';
            tr.innerHTML = '<td class="px-5 py-3.5 font-medium text-slate-800">' + rp.periode + '</td><td class="px-5 py-3.5 font-semibold text-slate-800">Rp ' + new Intl.NumberFormat('id-ID').format(rp.total_bayar) + '</td><td class="px-5 py-3.5 text-slate-500">' + rp.tanggal_bayar + '</td><td class="px-5 py-3.5"><span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-' + color + '-50 text-' + color + '-700"><span class="w-1.5 h-1.5 rounded-full bg-' + color + '-500"></span> ' + rp.status_label.toUpperCase() + '</span></td>';
            rb.appendChild(tr);
        });
    } else {
        rk.classList.remove('hidden');
    }

    hd.scrollIntoView({ behavior: 'smooth', block: 'start' });
    if (window.lucide) lucide.createIcons();
}

function tampilkanError(message) {
    document.getElementById('hasil-data').classList.add('hidden');
    document.getElementById('loading-data').classList.add('hidden');
    document.getElementById('error-data').classList.remove('hidden');
    document.getElementById('error-message').textContent = message;
}

// ===================== CONFIRM PAYMENT =====================
function konfirmasiPembayaran() {
    var pelangganId = document.getElementById('form-pelanggan-id').value;
    var tagihanId = document.getElementById('form-tagihan-id').value;
    var nominal = document.getElementById('form-nominal').value;
    var catatan = document.getElementById('form-catatan').value;
    var jumlah = document.getElementById('form-tagihan-jumlah').value;

    if (!pelangganId || !tagihanId) {
        Swal.fire({ icon: 'error', title: 'Data Tidak Lengkap', text: 'Silakan scan ulang QR pelanggan terlebih dahulu.', confirmButtonColor: '#e11d48' });
        return;
    }
    if (!nominal || parseFloat(nominal) <= 0) {
        Swal.fire({ icon: 'warning', title: 'Nominal Tidak Valid', text: 'Masukkan nominal uang yang diterima.', confirmButtonColor: '#e11d48' });
        return;
    }
    if (parseFloat(nominal) < parseFloat(jumlah)) {
        Swal.fire({ icon: 'warning', title: 'Nominal Kurang', text: 'Uang yang diterima (Rp ' + new Intl.NumberFormat('id-ID').format(nominal) + ') kurang dari tagihan (Rp ' + new Intl.NumberFormat('id-ID').format(jumlah) + ').', confirmButtonColor: '#e11d48' });
        return;
    }

    var kembalian = parseFloat(nominal) - parseFloat(jumlah);

    Swal.fire({
        title: 'Konfirmasi Pembayaran',
        html: '<div class="text-left space-y-3">' +
              '<p class="text-sm text-slate-500">Pastikan data berikut sudah benar:</p>' +
              '<div class="bg-slate-50 rounded-lg p-4 space-y-2 text-sm">' +
              '<div class="flex justify-between"><span class="text-slate-500">Tagihan</span><span class="font-semibold">Rp ' + new Intl.NumberFormat('id-ID').format(jumlah) + '</span></div>' +
              '<div class="flex justify-between"><span class="text-slate-500">Dibayar</span><span class="font-semibold">Rp ' + new Intl.NumberFormat('id-ID').format(nominal) + '</span></div>' +
              (kembalian > 0 ? '<div class="flex justify-between pt-2 border-t border-slate-200"><span class="text-emerald-600 font-medium">Kembalian</span><span class="font-semibold text-emerald-600">Rp ' + new Intl.NumberFormat('id-ID').format(kembalian) + '</span></div>' : '') +
              '</div></div>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#e11d48',
        confirmButtonText: 'Ya, Konfirmasi',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        showLoaderOnConfirm: true,
        preConfirm: function () {
            return fetch('{{ route("pegawai.scan-barcode.konfirmasi") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ pelanggan_id: pelangganId, tagihan_id: tagihanId, nominal_diterima: nominal, catatan: catatan }),
            })
            .then(function (res) { if (!res.ok) { return res.json().then(function (e) { throw e; }); } return res.json(); })
            .then(function (data) { if (!data.success) { throw new Error(data.message || 'Gagal memproses.'); } return data; })
            .catch(function (err) { Swal.showValidationMessage(err.message || 'Terjadi kesalahan.'); });
        }
    }).then(function (result) {
        if (result.isConfirmed && result.value) {
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Berhasil',
                text: result.value.message || 'Status tagihan sekarang: LUNAS.',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'OK'
            }).then(function () {
                if (cacheData && cacheData.pelanggan && cacheData.pelanggan.kode) {
                    cariPelanggan(cacheData.pelanggan.kode);
                }
            });
        }
    });
}
</script>
@endpush
