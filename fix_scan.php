<?php
$file = __DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'pegawai' . DIRECTORY_SEPARATOR . 'scan-barcode.blade.php';
$content = file_get_contents($file);

// Fix 1: scanner-loading div - close properly
$from1 = <<<'HTML'
<div id="scanner-loading" class="absolute inset-0 flex items-center justify-center bg-slate-100">
                        <div class="text-center">
                            <div class="w-10 h-10 border-4 border-emerald-100 border-t-emerald-500 rounded-full animate-spin mx-auto mb-3"></div>
                            <p class="text-sm text-slate-400">Mengaktifkan kamera...</p>
                        </div>
                <div class="flex gap-3">
HTML;

$to1 = <<<'HTML'
<div id="scanner-loading" class="absolute inset-0 flex items-center justify-center bg-slate-100">
                        <div class="text-center">
                            <div class="w-10 h-10 border-4 border-emerald-100 border-t-emerald-500 rounded-full animate-spin mx-auto mb-3"></div>
                            <p class="text-sm text-slate-400">Mengaktifkan kamera...</p>
                        </div>
                </div>
                <div class="flex gap-3">
HTML;

$content = str_replace($from1, $to1, $content, $count1);

// Fix 2: Close scanner card before Upload Gambar QR
$from2 = <<<'HTML'
                    </button>
                </div>

            {{-- Upload Gambar QR --}}
HTML;

$to2 = <<<'HTML'
                    </button>
                </div>

            {{-- Upload Gambar QR --}}
HTML;

$content = str_replace($from2, $to2, $content, $count2);

// Fix 3: Close Input Manual Kode card and left column before right column
$from3 = <<<'HTML'
                    </button>
                </div>

        {{-- ================= KOLOM KANAN: HASIL SCAN ================= --}}
HTML;

$to3 = <<<'HTML'
                    </button>
                </div>
        </div>

        {{-- ================= KOLOM KANAN: HASIL SCAN ================= --}}
HTML;

$content = str_replace($from3, $to3, $content, $count3);

// Fix 4: Close data pelanggan grid div
$from4 = <<<'HTML'
                        </div>
                    <div id="foto-rumah-container" class="mt-3 hidden">
                        <img id="foto-rumah-img" src="" alt="Foto Rumah" class="w-full h-40 object-cover rounded-xl border border-slate-200">
                    </div>

                {{-- Tagihan --}}
HTML;

$to4 = <<<'HTML'
                        </div>
                    <div id="foto-rumah-container" class="mt-3 hidden">
                        <img id="foto-rumah-img" src="" alt="Foto Rumah" class="w-full h-40 object-cover rounded-xl border border-slate-200">
                    </div>

                {{-- Tagihan --}}
HTML;

$content = str_replace($from4, $to4, $content, $count4);

// Fix 5: Close tagihan grid
$from5 = <<<'HTML'
                            <p id="tagihan-keterangan" class="text-sm font-semibold text-slate-800 mt-0.5">-</p>
                            </div>

                        {{-- Form Konfirmasi Pembayaran Tunai --}}
HTML;

$to5 = <<<'HTML'
                            <p id="tagihan-keterangan" class="text-sm font-semibold text-slate-800 mt-0.5">-</p>
                            </div>

                        {{-- Form Konfirmasi Pembayaran Tunai --}}
HTML;

$content = str_replace($from5, $to5, $content, $count5);

// Fix 6: Fix form structure - close missing divs in Tunai section
$from6 = <<<'HTML'
                                    <div class="flex items-center gap-2 px-3 py-2.5 bg-slate-50 rounded-lg border border-slate-200">
                                        <i data-lucide="wallet" class="w-4 h-4 text-emerald-600"></i>
                                        <span class="text-sm font-medium text-slate-700">Tunai (Cash)</span>
                                    </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Nominal Diterima</label>
HTML;

$to6 = <<<'HTML'
                                    <div class="flex items-center gap-2 px-3 py-2.5 bg-slate-50 rounded-lg border border-slate-200">
                                        <i data-lucide="wallet" class="w-4 h-4 text-emerald-600"></i>
                                        <span class="text-sm font-medium text-slate-700">Tunai (Cash)</span>
                                    </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Nominal Diterima</label>
HTML;

$content = str_replace($from6, $to6, $content, $count6);

// Fix 7: Close nominal input div
$from7 = <<<'HTML'
                                        <input type="number" id="form-nominal" min="0"
                                               class="w-full rounded-lg border border-slate-200 pl-10 pr-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                                    </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Catatan <span class="text-slate-400">(Opsional)</span></label>
HTML;

$to7 = <<<'HTML'
                                        <input type="number" id="form-nominal" min="0"
                                               class="w-full rounded-lg border border-slate-200 pl-10 pr-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                                    </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Catatan <span class="text-slate-400">(Opsional)</span></label>
HTML;

$content = str_replace($from7, $to7, $content, $count7);

// Fix 8: Close tagihan card, riwayat card, hasil-data, right, main grid
$from8 = <<<'HTML'
                    </div>
    </div>
@endsection
HTML;

$to8 = <<<'HTML'
                    </div>
            </div>
    </div>
@endsection
HTML;

$content = str_replace($from8, $to8, $content, $count8);

$total = $count1 + $count2 + $count3 + $count4 + $count5 + $count6 + $count7 + $count8;
file_put_contents($file, $content);
echo "Done! Total fixes applied: $total\n";
</｜DSML｜parameter>
</｜DSML｜parameter>
</｜DSML｜parameter>
</｜DSML｜parameter>
</｜DSML｜parameter>
</｜DSML｜parameter>
</｜DSML｜parameter>
</｜DSML｜parameter>
</｜DSML｜parameter>
