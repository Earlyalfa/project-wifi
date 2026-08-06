@extends('layouts.pelanggan')

@section('title', 'Pembayaran')
@section('page-title', 'Pembayaran')

@section('content')
{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#4f46e5',
        confirmButtonText: 'OK'
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#e11d48',
        confirmButtonText: 'OK'
    });
</script>
@endif

    {{-- ===================== DETAIL TAGIHAN ===================== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                <i data-lucide="file-text" class="w-4 h-4 text-violet-600"></i>
            </div>
            <h3 class="font-semibold text-slate-800">Detail Tagihan</h3>
        </div>

        @if ($tagihan)
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-4">
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 font-medium">Nomor Tagihan</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">INV-{{ str_pad($tagihan->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 font-medium">Periode</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">{{ $tagihan->periode }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 font-medium">Nominal Tagihan</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</p>
                </div>
                <div class="bg-violet-50 rounded-xl p-4 border border-violet-100">
                    <p class="text-xs text-indigo-400 font-medium">Total yang Harus Dibayar</p>
                    <p class="text-lg font-bold text-violet-700 mt-1">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 font-medium">Jatuh Tempo</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">{{ $tagihan->jatuh_tempo ? \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d M Y') : 'Belum diatur' }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 font-medium">Status Pembayaran</p>
                    <p class="text-sm font-semibold mt-1">
                        @if ($tagihan->status === 'menunggu_penagihan')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-100 text-sky-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                Menunggu Penagihan
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                Belum Dibayar
                            </span>
                        @endif
                    </p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 font-medium">Keterangan</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">Pembayaran WiFi {{ $tagihan->periode }}</p>
                </div>
            </div>

            {{-- Catatan Pembayaran --}}
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-3">
                <div class="flex items-start gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-amber-600 mt-0.5 shrink-0"></i>
                    <div class="text-sm text-amber-800">
                        <span class="font-semibold">Catatan Pembayaran:</span><br>
                        - Lakukan pembayaran sebelum tanggal <strong>{{ $tagihan->jatuh_tempo ? \Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d M Y') : 'Belum diatur' }}</strong> untuk menghindari denda.<br>
                        - Total yang harus dibayarkan adalah <strong>Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</strong>.<br>
                        - Bayar sesuai nominal tagihan (tanpa kode unik). QRIS akan mengirim detail transaksi otomatis.
                    </div>
                </div>
            </div>

            {{-- Pembayaran Tunai ke Petugas (Otomatis Lunas) --}}
            @if ($tagihan->status === 'menunggu_penagihan')
                <div class="bg-sky-50 border border-sky-200 rounded-xl p-4 mt-3">
                    <div class="flex items-center gap-2 mb-1.5">
                        <i data-lucide="clock" class="w-5 h-5 text-sky-600"></i>
                        <h4 class="text-sm font-semibold text-sky-800">Menunggu Penagihan Petugas</h4>
                    </div>
                    <p class="text-xs text-sky-600/80">Anda telah memilih pembayaran tunai. Petugas akan datang ke rumah Anda untuk menerima pembayaran. Setelah petugas mencatat pembayaran, status tagihan otomatis menjadi <strong>LUNAS</strong> tanpa perlu konfirmasi apa pun dari Anda.</p>
                </div>
            @else
                <div class="bg-sky-50 border border-sky-200 rounded-xl p-4 mt-3">
                    <div class="flex items-center gap-2 mb-1.5">
                        <i data-lucide="hand-coins" class="w-5 h-5 text-sky-600"></i>
                        <h4 class="text-sm font-semibold text-sky-800">Pembayaran Tunai ke Petugas</h4>
                    </div>
                    <p class="text-xs text-sky-600/80">Tidak punya aplikasi pembayaran? Anda bisa membayar <strong>langsung tunai ke petugas</strong> yang datang ke rumah Anda. Setelah membayar, petugas akan mencatatnya dan tagihan Anda otomatis menjadi <strong>LUNAS</strong> — Anda <strong>tidak perlu konfirmasi atau menekan tombol apa pun</strong>.</p>
                </div>
            @endif
        @else
            <div class="text-center py-8 text-slate-400">
                <i data-lucide="check-circle" class="w-12 h-12 mx-auto mb-3 text-emerald-400"></i>
                <p class="text-sm font-medium">Tidak ada tagihan yang perlu dibayar</p>
                <p class="text-xs mt-1">Semua tagihan Anda sudah lunas.</p>
            </div>
        @endif
    </div>

    {{-- ===================== PEMBAYARAN QRIS + FORM KONFIRMASI ===================== --}}
    {{-- QRIS hanya untuk tagihan belum bayar; tagihan 'menunggu_penagihan' menunggu petugas --}}
    @if ($tagihan && $tagihan->status !== 'menunggu_penagihan')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Card QRIS --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <i data-lucide="scan-qr-code" class="w-4 h-4 text-emerald-600"></i>
                </div>
                <h3 class="font-semibold text-slate-800">Pembayaran QRIS</h3>
            </div>

            <div class="flex flex-col items-center justify-center py-4">
                {{-- QR Code --}}
                <div class="w-100 h-72 bg-white border-2 border-slate-200 rounded-2xl flex items-center justify-center mb-4 overflow-hidden">
                    @if ($qrisImage && file_exists(public_path('storage/' . $qrisImage)))
                        <img src="{{ asset('storage/' . $qrisImage) }}"
                             alt="QRIS {{ $qrisMerchant }}"
                             class="w-full h-full object-contain p-2">
                    @else
                        <div class="text-center text-slate-300">
                            <i data-lucide="qr-code" class="w-16 h-16 mx-auto mb-2"></i>
                            <p class="text-xs font-medium">QRIS Merchant</p>
                        </div>
                    @endif
                </div>

                <div class="text-center">
                    <div class="flex items-center justify-center gap-2 mb-1">
                        <div class="w-6 h-6 rounded bg-slate-100 flex items-center justify-center">
                            <i data-lucide="store" class="w-3.5 h-3.5 text-slate-600"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-800">{{ $qrisMerchant }}</p>
                    </div>
                    <p class="text-2xl font-bold text-violet-600 mt-2">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</p>
                </div>

                {{-- Cara Pembayaran --}}
                <div class="w-full mt-5 bg-slate-50 rounded-xl p-4">
                    <p class="text-xs font-semibold text-slate-700 mb-2">Cara Pembayaran QRIS:</p>
                    <ol class="text-xs text-slate-500 space-y-1.5 list-decimal list-inside">
                        <li>Buka aplikasi <strong>e-Wallet</strong> atau <strong>Mobile Banking</strong> Anda.</li>
                        <li>Pilih menu <strong>Scan QR / QRIS</strong>.</li>
                        <li>Scan <strong>QR Code</strong> di samping.</li>
                        <li>Periksa nominal <strong>Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</strong>.</li>
                        <li>Masukkan <strong>PIN</strong> dan konfirmasi pembayaran.</li>
                        <li>Simpan <strong>bukti pembayaran</strong> (screenshot).</li>
                        <li>Upload bukti pembayaran pada form di samping.</li>
                    </ol>
                </div>
            </div>
        </div>

        {{-- Card Form Konfirmasi Pembayaran --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center">
                    <i data-lucide="clipboard-check" class="w-4 h-4 text-sky-600"></i>
                </div>
                <h3 class="font-semibold text-slate-800">Konfirmasi Pembayaran</h3>
            </div>

            <form action="{{ route('pelanggan.pembayaran.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="tagihan_id" value="{{ $tagihan->id }}">
                <input type="hidden" name="metode_pembayaran" value="QRIS">

                {{-- Tanggal Pembayaran --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Pembayaran <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_bayar" value="{{ old('tanggal_bayar', date('Y-m-d')) }}"
                           class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                           required>
                    @error('tanggal_bayar')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Metode Pembayaran (readonly) --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Metode Pembayaran</label>
                    <div class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm bg-slate-50 text-slate-600 flex items-center gap-2">
                        <i data-lucide="scan-qr-code" class="w-4 h-4 text-emerald-500"></i>
                        QRIS
                    </div>
                </div>

                {{-- Upload Bukti Pembayaran --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Upload Bukti Pembayaran <span class="text-rose-500">*</span></label>
                    <div class="mt-1 flex items-center gap-4">
                        <div class="w-20 h-20 rounded-xl bg-slate-100 flex items-center justify-center overflow-hidden shrink-0 border border-dashed border-slate-300">
                            <i data-lucide="upload" class="w-6 h-6 text-slate-400" id="bukti-icon"></i>
                            <img src="" alt="Preview" class="w-full h-full object-cover hidden" id="bukti-preview">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="bukti_bayar" id="bukti_bayar"
                                   class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-600 hover:file:bg-violet-100 transition-colors"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   onchange="previewBukti(event)"
                                   required>
                            <p class="text-xs text-slate-400 mt-1">Format: JPG, JPEG, PNG, PDF. Maks: 2 MB</p>
                        </div>
                    </div>
                    @error('bukti_bayar')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan (Opsional) --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Catatan <span class="text-slate-400">(Opsional)</span></label>
                    <textarea name="catatan" rows="2" placeholder="Catatan tambahan..."
                              class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Checkbox Konfirmasi --}}
                <div class="bg-slate-50 rounded-xl p-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="konfirmasi" value="1"
                               class="mt-0.5 w-4 h-4 rounded border-slate-300 text-violet-600 focus:ring-indigo-500"
                               {{ old('konfirmasi') ? 'checked' : '' }}
                               required>
                        <span class="text-sm text-slate-600">
                            Saya telah melakukan pembayaran sesuai nominal <strong>Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</strong>
                        </span>
                    </label>
                    @error('konfirmasi')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Button --}}
                <button type="submit"
                        class="w-full bg-violet-600 hover:bg-violet-700 text-white font-semibold py-3 rounded-lg text-sm transition-colors flex items-center justify-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Kirim Konfirmasi
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- ===================== RIWAYAT PEMBAYARAN ===================== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center">
                    <i data-lucide="clock" class="w-4 h-4 text-sky-600"></i>
                </div>
                <h3 class="font-semibold text-slate-800">Riwayat Pembayaran</h3>
            </div>
        </div>
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-slate-100">
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Tanggal</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Periode</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Nominal</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Metode</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($riwayatPembayaran as $p)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 text-slate-600">{{ $p->tanggal_bayar ? \Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y') : ($p->dibayar_at ? \Carbon\Carbon::parse($p->dibayar_at)->format('d/m/Y') : '-') }}</td>
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $p->periode }}</td>
                            <td class="px-5 py-3 font-semibold text-slate-800">Rp {{ number_format($p->total_bayar ?? $p->jumlah, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $p->metode_pembayaran ?? 'QRIS' }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $statusBadge = match($p->status) {
                                        'lunas' => ['bg-emerald-100', 'text-emerald-600', 'bg-emerald-500', 'Lunas'],
                                        'menunggu_verifikasi' => ['bg-amber-100', 'text-amber-600', 'bg-amber-500', 'Menunggu Verifikasi'],
                                        'menunggu_penagihan' => ['bg-sky-100', 'text-sky-600', 'bg-sky-500', 'Menunggu Penagihan'],
                                        'ditolak' => ['bg-rose-100', 'text-rose-600', 'bg-rose-500', 'Ditolak'],
                                        default => ['bg-rose-100', 'text-rose-600', 'bg-rose-500', 'Belum Bayar'],
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusBadge[0] }} {{ $statusBadge[1] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusBadge[2] }}"></span>
                                    {{ $statusBadge[3] }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-slate-500 text-xs">
                                @if ($p->status === 'lunas')
                                    Pembayaran lunas
                                @elseif ($p->status === 'menunggu_verifikasi')
                                    Menunggu verifikasi admin
                                @elseif ($p->status === 'menunggu_penagihan')
                                    Menunggu penagihan petugas
                                @elseif ($p->status === 'ditolak')
                                    Pembayaran ditolak
                                @else
                                    Menunggu pembayaran
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada riwayat pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function previewBukti(event) {
        const input = event.target;
        const preview = document.getElementById('bukti-preview');
        const icon = document.getElementById('bukti-icon');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.type === 'application/pdf') {
                // PDF - show PDF icon
                preview.classList.add('hidden');
                if (icon) {
                    icon.innerHTML = '<i data-lucide="file-text" class="w-6 h-6 text-slate-400"></i>';
                    lucide.createIcons();
                }
            } else {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (icon) icon.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    }
</script>
@endpush

