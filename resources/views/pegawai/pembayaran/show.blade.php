@extends('layouts.pegawai')

@section('title', 'Detail Pembayaran')
@section('page-title', 'Detail Pembayaran')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('status'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('status') }}',
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

<div x-data="verifikasiHandler()">
    <a href="{{ route('pegawai.pembayaran.index') }}" class="flex items-center gap-2 text-slate-500 text-sm mb-4 hover:underline">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali ke Data Pembayaran
    </a>

    <div class="grid lg:grid-cols-3 gap-5">
        {{-- KOLOM KIRI: Detail Pembayaran --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- CARD INFO PEMBAYARAN --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                        <i data-lucide="receipt" class="w-5 h-5 text-violet-600"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-slate-800">Detail Pembayaran</h2>
                        <p class="text-sm text-slate-400 font-mono">INV-{{ str_pad($pembayaran->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="calendar" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Periode</p>
                            <p class="text-sm text-slate-800 font-medium">{{ $pembayaran->periode ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="calendar-check" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Jatuh Tempo</p>
                            <p class="text-sm text-slate-800 font-medium">{{ $pembayaran->jatuh_tempo ? \Carbon\Carbon::parse($pembayaran->jatuh_tempo)->format('d M Y') : '-' }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="coins" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Nominal Tagihan</p>
                            <p class="text-sm font-bold text-slate-800">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="bg-violet-50 rounded-xl p-3.5 border border-violet-100 flex items-start gap-3">
                        <i data-lucide="wallet" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-violet-500 font-medium">Total Dibayar</p>
                            <p class="text-lg font-bold text-violet-700">Rp {{ number_format($pembayaran->total_bayar ?? $pembayaran->jumlah, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="credit-card" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Metode Pembayaran</p>
                            <p class="text-sm font-medium text-slate-800">{{ $pembayaran->metode_pembayaran ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="clock" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Tanggal Bayar</p>
                            <p class="text-sm font-medium text-slate-800">{{ $pembayaran->tanggal_bayar ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y') : ($pembayaran->dibayar_at ? $pembayaran->dibayar_at->format('d M Y H:i') : '-') }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                        <i data-lucide="flag" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                        <div>
                            <p class="text-xs text-slate-400 font-medium">Status</p>
                            <p class="text-sm">
                                @php
                                    $sBadge = match($pembayaran->status) {
                                        'lunas' => 'bg-emerald-100 text-emerald-700',
                                        'menunggu_verifikasi' => 'bg-amber-100 text-amber-700',
                                        'ditolak' => 'bg-rose-100 text-rose-700',
                                        default => 'bg-rose-100 text-rose-600',
                                    };
                                    $sLabel = match($pembayaran->status) {
                                        'lunas' => 'Lunas',
                                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                        'ditolak' => 'Ditolak',
                                        default => 'Belum Bayar',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $sBadge }}">
                                    {{ $sLabel }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                @if ($pembayaran->catatan)
                    <div class="mt-4 bg-slate-50 rounded-xl p-4">
                        <div class="flex items-start gap-2">
                            <i data-lucide="file-text" class="w-4 h-4 text-slate-400 mt-0.5 shrink-0"></i>
                            <div>
                                <p class="text-xs font-semibold text-slate-600 mb-1">Catatan</p>
                                <p class="text-sm text-slate-700">{{ $pembayaran->catatan }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- CARD INFO PELANGGAN --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <i data-lucide="user" class="w-5 h-5 text-emerald-600"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Informasi Pelanggan</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <p class="text-xs text-slate-400 font-medium">Nama</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $pembayaran->pelanggan->nama ?? '-' }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <p class="text-xs text-slate-400 font-medium">Kode Pelanggan</p>
                        <p class="text-sm font-mono font-bold text-violet-600">{{ $pembayaran->pelanggan->kode ?? '-' }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <p class="text-xs text-slate-400 font-medium">Alamat</p>
                        <p class="text-sm text-slate-800">{{ $pembayaran->pelanggan->alamat ?? '-' }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <p class="text-xs text-slate-400 font-medium">No. HP</p>
                        <p class="text-sm text-slate-800">{{ $pembayaran->pelanggan->no_hp ?? '-' }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <p class="text-xs text-slate-400 font-medium">Paket WiFi</p>
                        <p class="text-sm text-slate-800">{{ $pembayaran->pelanggan->paket ?? '-' }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <p class="text-xs text-slate-400 font-medium">Status Pelanggan</p>
                        <p class="text-sm">
                            @if ($pembayaran->pelanggan && $pembayaran->pelanggan->status === 'aktif')
                                <span class="inline-flex items-center gap-1 font-semibold text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 font-semibold text-slate-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
                @if ($pembayaran->pelanggan)
                    <a href="{{ route('pegawai.pelanggan.show', $pembayaran->pelanggan) }}"
                       class="mt-4 inline-flex items-center gap-2 text-sm text-violet-600 hover:underline font-medium">
                        <i data-lucide="external-link" class="w-4 h-4"></i>
                        Lihat detail pelanggan
                    </a>
                @endif
            </div>
        </div>

        {{-- KOLOM KANAN: Bukti Bayar + Verifikasi --}}
        <div class="space-y-5">
            {{-- CARD BUKTI BAYAR --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center">
                        <i data-lucide="image" class="w-5 h-5 text-sky-600"></i>
                    </div>
                    <h3 class="font-semibold text-slate-800">Bukti Pembayaran</h3>
                </div>

                @if ($pembayaran->bukti_bayar)
                    @php
                        $isImage = in_array(pathinfo($pembayaran->bukti_bayar, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']);
                    @endphp
                    <div class="bg-slate-50 rounded-xl overflow-hidden mb-3">
                        @if ($isImage)
                            <a href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" target="_blank">
                                <img src="{{ asset('storage/' . $pembayaran->bukti_bayar) }}"
                                     alt="Bukti Pembayaran"
                                     class="w-full h-48 object-cover hover:opacity-90 transition-opacity cursor-pointer">
                            </a>
                        @else
                            <div class="flex flex-col items-center justify-center py-10 text-slate-400">
                                <i data-lucide="file-text" class="w-10 h-10 mb-2"></i>
                                <p class="text-sm font-medium">File PDF</p>
                                <a href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" target="_blank"
                                   class="mt-2 text-sm text-violet-600 hover:underline font-medium">
                                    Lihat File
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="text-center">
                        <a href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" target="_blank"
                           class="inline-flex items-center gap-2 text-sm text-violet-600 hover:underline font-medium">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Download Bukti Bayar
                        </a>
                    </div>
                @else
                    <div class="text-center py-8 text-slate-400">
                        <i data-lucide="image-off" class="w-10 h-10 mx-auto mb-2"></i>
                        <p class="text-sm">Tidak ada bukti pembayaran</p>
                    </div>
                @endif
            </div>

            {{-- CARD VERIFIKASI (hanya jika status menunggu_verifikasi) --}}
            @if ($pembayaran->status === 'menunggu_verifikasi')
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-5 h-5 text-amber-600"></i>
                        </div>
                        <h3 class="font-semibold text-slate-800">Verifikasi Pembayaran</h3>
                    </div>

                    <p class="text-sm text-slate-500 mb-4">
                        Pembayaran ini sedang menunggu verifikasi. Silakan periksa bukti bayar di samping dan lakukan verifikasi.
                    </p>

                    <form action="{{ route('pegawai.pembayaran.verifikasi', $pembayaran) }}" method="POST" x-ref="formVerifikasi">
                        @csrf
                        <input type="hidden" name="aksi" x-model="aksi">

                        <div class="mb-4">
                            <label class="block text-xs font-medium text-slate-600 mb-1.5">Catatan Verifikasi <span class="text-slate-400">(opsional)</span></label>
                            <textarea name="catatan_verifikasi" rows="3"
                                      placeholder="Tambahkan catatan untuk verifikasi..."
                                      class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">{{ old('catatan_verifikasi') }}</textarea>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="button" @click="konfirmasiSetujui()"
                                    class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                Setujui Pembayaran
                            </button>
                            <button type="button" @click="konfirmasiTolak()"
                                    class="w-full flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm">
                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                Tolak Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            @else
                {{-- Status info card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                            <i data-lucide="info" class="w-5 h-5 text-slate-500"></i>
                        </div>
                        <h3 class="font-semibold text-slate-800">Informasi</h3>
                    </div>
                    @if ($pembayaran->status === 'lunas')
                        <div class="bg-emerald-50 rounded-xl p-4 text-center">
                            <i data-lucide="check-circle" class="w-8 h-8 text-emerald-500 mx-auto mb-2"></i>
                            <p class="text-sm font-semibold text-emerald-700">Pembayaran Lunas</p>
                            <p class="text-xs text-emerald-500 mt-1">
                                {{ $pembayaran->dibayar_at ? 'Diverifikasi pada ' . $pembayaran->dibayar_at->format('d M Y H:i') : '' }}
                            </p>
                        </div>
                    @elseif ($pembayaran->status === 'ditolak')
                        <div class="bg-rose-50 rounded-xl p-4 text-center">
                            <i data-lucide="x-circle" class="w-8 h-8 text-rose-500 mx-auto mb-2"></i>
                            <p class="text-sm font-semibold text-rose-700">Pembayaran Ditolak</p>
                            @if ($pembayaran->catatan)
                                <p class="text-xs text-rose-500 mt-1">{{ $pembayaran->catatan }}</p>
                            @endif
                        </div>
                    @elseif ($pembayaran->status === 'belum_bayar')
                        <div class="bg-rose-50 rounded-xl p-4 text-center">
                            <i data-lucide="alert-circle" class="w-8 h-8 text-rose-500 mx-auto mb-2"></i>
                            <p class="text-sm font-semibold text-rose-700">Belum Dibayar</p>
                            <p class="text-xs text-rose-500 mt-1">Pelanggan belum melakukan pembayaran</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function verifikasiHandler() {
        return {
            aksi: '',
            konfirmasiSetujui() {
                this.aksi = 'setujui';
                Swal.fire({
                    title: 'Setujui Pembayaran?',
                    html: '<p class="text-sm text-slate-500">Pastikan bukti pembayaran sudah sesuai.</p>' +
                          '<p class="text-sm font-semibold text-emerald-600 mt-2">Status akan berubah menjadi <strong>LUNAS</strong></p>',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#e11d48',
                    confirmButtonText: 'Ya, Setujui',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$refs.formVerifikasi.submit();
                    }
                });
            },
            konfirmasiTolak() {
                this.aksi = 'tolak';
                Swal.fire({
                    title: 'Tolak Pembayaran?',
                    html: '<p class="text-sm text-slate-500">Pastikan Anda telah memeriksa bukti bayar.</p>' +
                          '<p class="text-sm font-semibold text-rose-600 mt-2">Status akan berubah menjadi <strong>DITOLAK</strong></p>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Tolak',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$refs.formVerifikasi.submit();
                    }
                });
            }
        };
    }
</script>
@endpush
