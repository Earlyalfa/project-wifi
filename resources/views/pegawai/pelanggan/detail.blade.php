@extends('layouts.pegawai')

@section('title', 'Detail Pelanggan')
@section('page-title', 'Detail Pelanggan')

@section('content')
<div class="max-w-md mx-auto"
     x-data="{
        showFoto: false,
        fotoLoading: false,
        fotoUrl: null,
        fotoCaption: null,
        openFoto() {
            this.showFoto = true;
            this.fotoLoading = true;
            this.fotoUrl = null;
            fetch('{{ route('pegawai.pelanggan.foto', $pelanggan) }}')
                .then(res => res.json())
                .then(data => {
                    // beri sedikit delay biar transisi loading terasa natural
                    setTimeout(() => {
                        this.fotoUrl = data.url;
                        this.fotoCaption = data.diambil_saat;
                        this.fotoLoading = false;
                    }, 500);
                })
                .catch(() => { this.fotoLoading = false; });
        }
     }">

    <a href="{{ route('pegawai.pelanggan.index') }}" class="flex items-center gap-2 text-slate-500 text-sm mb-4 hover:underline">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Kembali
    </a>

    <div class="bg-white rounded-2xl shadow-sm p-5">

        {{-- HEADER --}}
        <div class="flex items-start gap-4 mb-5">
            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                <i data-lucide="user" class="w-6 h-6 text-emerald-600"></i>
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-slate-800">{{ $pelanggan->nama }}</h2>

                    @if ($tagihanTerbaru && $tagihanTerbaru->status === 'belum_bayar')
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-rose-50 text-rose-500">Belum Bayar</span>
                    @else
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600">Lunas</span>
                    @endif
                </div>
                <p class="text-xs text-slate-400 mt-0.5">ID Pelanggan : {{ $pelanggan->kode }}</p>
                <p class="text-xs text-slate-400">No. HP : {{ $pelanggan->no_hp }}</p>
            </div>
        </div>

        {{-- INFO --}}
        <div class="space-y-4 text-sm mb-5">
            <div class="flex gap-3">
                <i data-lucide="map-pin" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                <div>
                    <p class="text-slate-400 text-xs">Alamat</p>
                    <p class="text-slate-800 font-medium">{{ $pelanggan->alamat }}</p>
                </div>
            </div>

            <div class="flex gap-3">
                <i data-lucide="wifi" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                <div>
                    <p class="text-slate-400 text-xs">Paket</p>
                    <p class="text-slate-800 font-medium">{{ $pelanggan->paket }}</p>
                </div>
            </div>

            <div class="flex gap-3">
                <i data-lucide="receipt" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                <div>
                    <p class="text-slate-400 text-xs">Tagihan Bulan Ini</p>
                    @if ($tagihanTerbaru)
                        <p class="text-slate-800 font-medium">Rp {{ number_format($tagihanTerbaru->jumlah, 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-400">Jatuh tempo : {{ optional($tagihanTerbaru->jatuh_tempo)->format('d M Y') }}</p>
                    @else
                        <p class="text-slate-800 font-medium">-</p>
                    @endif
                </div>
            </div>

            <div class="flex gap-3">
                <i data-lucide="circle-dollar-sign" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                <div>
                    <p class="text-slate-400 text-xs">Status Pembayaran</p>
                    @if ($tagihanTerbaru && $tagihanTerbaru->status === 'belum_bayar')
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-rose-50 text-rose-500">Belum Bayar</span>
                    @else
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600">Lunas</span>
                    @endif
                </div>
            </div>

            <div class="flex gap-3">
                <i data-lucide="calendar" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                <div>
                    <p class="text-slate-400 text-xs">Tanggal Daftar</p>
                    <p class="text-slate-800 font-medium">{{ $pelanggan->created_at->format('d F Y') }}</p>
                </div>
            </div>
        </div>

        {{-- RIWAYAT PEMBAYARAN --}}
        <div class="bg-slate-50 rounded-xl p-4 mb-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-slate-800 text-sm">Riwayat Pembayaran</h3>
                <a href="#" class="text-xs text-emerald-600 font-medium hover:underline">Lihat Semua</a>
            </div>

            <div class="space-y-2.5">
                @forelse ($pelanggan->pembayarans->take(3) as $bayar)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">{{ optional($bayar->jatuh_tempo)->format('d M Y') ?? $bayar->periode }}</span>
                        <span class="text-slate-800 font-medium">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</span>
                        @if ($bayar->status === 'lunas')
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">Lunas</span>
                        @else
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-rose-100 text-rose-600">Belum Bayar</span>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-400">Belum ada riwayat pembayaran.</p>
                @endforelse
            </div>
        </div>

        {{-- AKSI --}}
        <div class="flex flex-col gap-3">
            <button type="button" @click="openFoto()"
                    class="w-full border border-emerald-500 text-emerald-600 font-semibold py-2.5 rounded-lg hover:bg-emerald-50 flex items-center justify-center gap-2">
                <i data-lucide="eye" class="w-4 h-4"></i>
                View Foto Rumah
            </button>

            <form action="{{ route('pegawai.pelanggan.konfirmasi-pembayaran', $pelanggan) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-lg">
                    Konfirmasi Pembayaran
                </button>
            </form>
        </div>
    </div>

    {{-- MODAL FOTO RUMAH (lazy-loaded) --}}
    <div x-show="showFoto" x-cloak
         class="fixed inset-0 bg-black/50 flex items-center justify-center p-6 z-50"
         @click.self="showFoto = false">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-slate-800">Foto Rumah</h3>
                <button type="button" @click="showFoto = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            {{-- STATE: LOADING --}}
            <div x-show="fotoLoading" class="flex flex-col items-center justify-center py-10 gap-3">
                <div class="w-10 h-10 border-4 border-emerald-100 border-t-emerald-500 rounded-full animate-spin"></div>
                <p class="text-sm text-slate-400">Memuat foto...</p>
            </div>

            {{-- STATE: FOTO SIAP --}}
            <div x-show="!fotoLoading && fotoUrl" x-cloak>
                <img :src="fotoUrl" class="w-full h-52 object-cover rounded-lg mb-3">
                <p class="text-xs text-slate-400 mb-4" x-text="fotoCaption"></p>
            </div>

            {{-- STATE: TIDAK ADA FOTO --}}
            <div x-show="!fotoLoading && !fotoUrl" x-cloak class="py-6 text-center text-sm text-slate-400">
                Belum ada foto rumah untuk pelanggan ini.
            </div>

            <button type="button" @click="showFoto = false"
                    class="w-full border border-emerald-500 text-emerald-600 font-semibold py-2.5 rounded-lg hover:bg-emerald-50 mt-2">
                Tutup
            </button>
        </div>
    </div>

</div>
@endsection
