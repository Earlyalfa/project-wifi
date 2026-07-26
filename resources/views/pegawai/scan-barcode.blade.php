@extends('layouts.pegawai')

@section('title', 'Scan Barcode')
@section('page-title', 'Scan QR / Barcode Pelanggan')

@section('content')
<div class="max-w-lg mx-auto">

    {{-- Form Input Manual Kode --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-5">
        <h3 class="font-semibold text-slate-800 mb-4">Cari Berdasarkan Kode Pelanggan</h3>

        <form action="{{ route('pegawai.scan-barcode.store') }}" method="POST">
            @csrf
            <div class="flex gap-3">
                <div class="flex-1">
                    <input type="text" name="kode" placeholder="Masukkan kode pelanggan (contoh: CS-1248)"
                           class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400"
                           value="{{ old('kode') }}">
                    @error('kode')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-5 py-2.5 rounded-lg text-sm transition-colors">
                    Cari
                </button>
            </div>
        </form>
    </div>

    {{-- Panduan --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 text-center">
        <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
            <i data-lucide="scan-qr-code" class="w-8 h-8 text-emerald-600"></i>
        </div>
        <h3 class="font-semibold text-slate-800 mb-2">Scan QR Code</h3>
        <p class="text-sm text-slate-400 mb-4">
            Gunakan kamera untuk memindai QR code pelanggan, atau masukkan kode pelanggan secara manual di atas.
        </p>

        {{-- Kamera Scanner --}}
        <div id="scanner-container" class="relative bg-slate-100 rounded-xl overflow-hidden mb-4" style="min-height: 250px;">
            <video id="scanner-video" class="w-full h-full object-cover" playsinline></video>
            <div id="scanner-loading" class="absolute inset-0 flex items-center justify-center bg-slate-100">
                <div class="text-center">
                    <div class="w-10 h-10 border-4 border-emerald-100 border-t-emerald-500 rounded-full animate-spin mx-auto mb-3"></div>
                    <p class="text-sm text-slate-400">Mengaktifkan kamera...</p>
                </div>
            </div>
        </div>

        <button type="button" id="btn-start-scan"
                class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors">
            Mulai Scan
        </button>
        <button type="button" id="btn-stop-scan" style="display:none;"
                class="bg-rose-500 hover:bg-rose-600 text-white font-semibold px-6 py-2.5 rounded-lg text-sm transition-colors">
            Hentikan Scan
        </button>

        <div id="scan-result" class="mt-4 hidden">
            <div class="bg-emerald-50 text-emerald-700 rounded-lg px-4 py-3 text-sm font-medium">
                Kode terdeteksi: <span id="scanned-code" class="font-bold"></span>
            </div>
            <form id="scan-form-auto" method="POST" action="{{ route('pegawai.scan-barcode.store') }}" class="mt-3">
                @csrf
                <input type="hidden" name="kode" id="hidden-kode">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors">
                    Lanjut ke Detail Pelanggan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Gunakan jsQR untuk membaca QR code dari video --}}
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('scanner-video');
    const loading = document.getElementById('scanner-loading');
    const btnStart = document.getElementById('btn-start-scan');
    const btnStop = document.getElementById('btn-stop-scan');
    const scanResult = document.getElementById('scan-result');
    const scannedCode = document.getElementById('scanned-code');
    const hiddenKode = document.getElementById('hidden-kode');

    let stream = null;
    let scanning = false;
    let animationId = null;

    function startCamera() {
        loading.style.display = 'flex';
        navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment', width: 640, height: 480 }
        }).then(function (s) {
            stream = s;
            video.srcObject = s;
            video.play();
            loading.style.display = 'none';
            scanning = true;
            btnStart.style.display = 'none';
            btnStop.style.display = 'inline-block';
            scanLoop();
        }).catch(function (err) {
            loading.innerHTML = '<p class="text-sm text-rose-500">Tidak dapat mengakses kamera: ' + err.message + '</p>';
        });
    }

    function stopCamera() {
        scanning = false;
        if (animationId) {
            cancelAnimationFrame(animationId);
            animationId = null;
        }
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        video.srcObject = null;
        btnStart.style.display = 'inline-block';
        btnStop.style.display = 'none';
        loading.style.display = 'none';
    }

    function scanLoop() {
        if (!scanning) return;

        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: 'dontInvert',
            });

            if (code && code.data) {
                // QR code terdeteksi
                scanning = false;
                stopCamera();
                scanResult.classList.remove('hidden');
                scannedCode.textContent = code.data;
                hiddenKode.value = code.data;
                return;
            }
        }

        animationId = requestAnimationFrame(scanLoop);
    }

    btnStart.addEventListener('click', startCamera);
    btnStop.addEventListener('click', stopCamera);
});
</script>
@endpush

