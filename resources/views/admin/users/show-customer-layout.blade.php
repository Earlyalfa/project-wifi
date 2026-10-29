@extends('layouts.admin')

@section('title', 'Detail Pelanggan')
@section('page-title', 'Detail Pelanggan')

@section('content')
<div class="max-w-5xl mx-auto" x-data="{ showFoto: false }">
    <nav class="flex items-center gap-2 text-sm text-slate-400 mb-6">
        <a href="{{ route('admin.users') }}" class="hover:text-[#7C3AED] transition-colors">Data Pengguna</a>
        <span class="text-slate-300">/</span> 
        <span class="text-slate-700 font-medium">Detail Pelanggan</span>
    </nav>

    <div class="bg-white rounded-2xl border border-slate-200/60 p-6 md:p-8 mb-6">
        <div class="flex flex-col md:flex-row md:items-center gap-5">
            <div class="w-14 h-14 md:w-16 md:h-16 rounded-full bg-[#7C3AED]/10 flex items-center justify-center shrink-0">
                <span class="text-xl md:text-2xl font-bold text-[#7C3AED]">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-xl md:text-2xl font-bold text-slate-800 truncate">{{ $pelanggan->nama ?? $user->name }}</h2>
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold {{ $user->status === 'aktif' ? 'text-emerald-600' : 'text-slate-400' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $user->status === 'aktif' ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                    <span class="text-sm font-mono font-semibold text-slate-500">{{ $pelanggan->kode ?? 'Kode pelanggan belum tersedia' }}</span>
                </div>
            </div>
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-[#7C3AED] hover:bg-[#6D28D9] text-white text-sm font-semibold rounded-xl transition-all gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/60 p-6 md:p-8">
            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-6">Informasi Pelanggan</h3>
            <div class="space-y-5">
                <div class="flex items-start gap-4"><span class="text-sm text-slate-500 w-24 shrink-0">Nama</span><span class="text-sm font-medium text-slate-800">: {{ $pelanggan->nama ?? $user->name }}</span></div>
                <div class="flex items-start gap-4"><span class="text-sm text-slate-500 w-24 shrink-0">Email</span><span class="text-sm font-medium text-slate-800 break-all">: {{ $pelanggan->email ?? $user->email }}</span></div>
                <div class="flex items-start gap-4"><span class="text-sm text-slate-500 w-24 shrink-0">No. HP</span><span class="text-sm font-medium text-slate-800">: {{ $pelanggan->no_hp ?? $user->no_hp ?: '-' }}</span></div>
                <div class="flex items-start gap-4"><span class="text-sm text-slate-500 w-24 shrink-0">Alamat</span><span class="text-sm font-medium text-slate-800">: {{ $pelanggan->alamat ?? $user->alamat ?: '-' }}</span></div>
                <div class="flex items-start gap-4"><span class="text-sm text-slate-500 w-24 shrink-0">Bergabung</span><span class="text-sm font-medium text-slate-800">: {{ $pelanggan?->created_at?->format('d F Y') ?? $user->created_at?->format('d F Y') ?? '-' }}</span></div>
                <div class="flex items-start gap-4"><span class="text-sm text-slate-500 w-24 shrink-0">Paket WiFi</span><span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">{{ $pelanggan->paket ?? '-' }}</span></div>
                <div class="flex items-start gap-4"><span class="text-sm text-slate-500 w-24 shrink-0">Jatuh Tempo</span><span class="text-sm font-medium text-slate-800">: {{ $pelanggan?->tagihan_jatuh_tempo ? 'Setiap tanggal ' . (int) $pelanggan->tagihan_jatuh_tempo : 'Belum diatur' }}</span></div>
                <div class="flex items-start gap-4">
                    <span class="text-sm text-slate-500 w-24 shrink-0">Status</span>
                    <span class="text-sm font-medium text-slate-800">:
                        @if ($user->status === 'aktif')
                            <span class="inline-flex items-center gap-1.5 text-emerald-600"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>Aktif</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-slate-400"><span class="w-2 h-2 rounded-full bg-slate-300"></span>Nonaktif</span>
                        @endif
                    </span>
                </div>
                <div class="flex items-start gap-4">
                    <span class="text-sm text-slate-500 w-24 shrink-0">Foto Rumah</span>
                    <span class="text-sm font-medium text-slate-800">:
                        <button type="button" @click="showFoto = true" class="inline-flex items-center gap-1.5 text-[#7C3AED] hover:text-[#6D28D9] font-semibold transition-colors">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                            Lihat Foto
                        </button>
                    </span>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-slate-200/60 p-6 md:p-8 text-center">
                <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-5 flex items-center gap-2 justify-center">
                    <i data-lucide="qr-code" class="w-4 h-4 text-[#7C3AED]"></i>QR Code
                </h3>
                @if ($qrCodeBase64)
                    <div class="inline-block p-3 rounded-xl border border-slate-100 mx-auto"><img src="{{ $qrCodeBase64 }}" alt="QR Code {{ $pelanggan->kode }}" id="admin-customer-qr" class="w-[150px] h-[150px]"></div>
                    <p class="text-xs text-slate-400 mt-2.5 font-mono font-bold text-[#7C3AED]">{{ $pelanggan->kode }}</p>
                    <div class="mt-5 flex gap-2 justify-center">
                        <button type="button" onclick="downloadAdminCustomerQr()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#7C3AED] hover:bg-[#6D28D9] text-white text-xs font-semibold rounded-xl transition-all"><i data-lucide="download" class="w-3.5 h-3.5"></i>Download</button>
                        <button type="button" onclick="printAdminCustomerQr()" class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl hover:bg-slate-50 transition-all"><i data-lucide="printer" class="w-3.5 h-3.5"></i>Cetak</button>
                    </div>
                @else
                    <p class="text-sm text-slate-400 py-8">QR Code belum tersedia.</p>
                @endif
            </div>
        </div>
    </div>

    <section class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden mb-6" x-data="{ showKonfirmasi: false, tagihanId: null, tagihanPeriode: null, tagihanJumlah: 0, nominalDiterima: 0, catatan: '', openKonfirmasi(id, periode, jumlah) { this.tagihanId = id; this.tagihanPeriode = periode; this.tagihanJumlah = jumlah; this.nominalDiterima = jumlah; this.catatan = ''; this.showKonfirmasi = true; } }">
        <div class="px-6 md:px-8 py-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-rose-100 flex items-center justify-center"><i data-lucide="credit-card" class="w-4 h-4 text-rose-600"></i></div>
                <h3 class="text-sm font-semibold text-slate-800">Tagihan Belum Dibayar</h3>
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-600">{{ $tagihanBelumBayar->count() }} tagihan</span>
            </div>
        </div>
        @if ($tagihanBelumBayar->count() > 0)
            <div class="divide-y divide-slate-50">
                @foreach ($tagihanBelumBayar as $tagihan)
                    <div class="px-6 md:px-8 py-4 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm font-semibold text-slate-800">{{ $tagihan->periode }}</p>
                                @if ($tagihan->status === 'menunggu_verifikasi')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Menunggu Verifikasi</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-600"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Belum Dibayar</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Jatuh tempo: {{ $tagihan->jatuh_tempo?->format('d M Y') ?? '-' }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <p class="text-sm font-bold text-slate-800">Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</p>
                            @if ($pelanggan)
                                <button type="button" @click="openKonfirmasi('{{ $tagihan->id }}', '{{ $tagihan->periode }}', {{ $tagihan->jumlah }})" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl transition-all shadow-sm active:scale-[0.98]"><i data-lucide="circle-check" class="w-3.5 h-3.5"></i>Konfirmasi Pembayaran Tunai</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-6 md:px-8 py-10 text-center">
                <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center mx-auto mb-3"><i data-lucide="circle-check" class="w-6 h-6 text-emerald-500"></i></div>
                <p class="text-sm font-semibold text-emerald-700">Tidak ada tagihan yang belum dibayar</p>
                <p class="text-xs text-slate-400 mt-1">Semua tagihan pelanggan ini sudah lunas.</p>
            </div>
        @endif

        @if ($pelanggan && $tagihanBelumBayar->isNotEmpty())
            <div x-show="showKonfirmasi" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center p-6 z-50" @click.self="showKonfirmasi = false">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="font-semibold text-slate-800">Konfirmasi Pembayaran Tunai</h3>
                        <button type="button" @click="showKonfirmasi = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors"><i data-lucide="x" class="w-5 h-5"></i></button>
                    </div>
                    <form action="{{ route('pegawai.pelanggan.konfirmasi-pembayaran', $pelanggan) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="tagihan_id" :value="tagihanId">
                        <div class="bg-slate-50 rounded-xl p-4 space-y-2 text-sm">
                            <div class="flex justify-between"><span class="text-slate-500">Pelanggan</span><span class="font-semibold text-slate-800">{{ $pelanggan->nama }}</span></div>
                            <div class="flex justify-between"><span class="text-slate-500">Periode</span><span class="font-semibold text-slate-800" x-text="tagihanPeriode"></span></div>
                            <div class="flex justify-between"><span class="text-slate-500">Tagihan</span><span class="font-bold text-slate-800" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(tagihanJumlah)"></span></div>
                        </div>
                        <div><label class="block text-xs font-medium text-slate-600 mb-1.5">Metode Pembayaran</label><div class="flex items-center gap-2.5 px-3.5 py-2.5 bg-emerald-50 rounded-lg border border-emerald-200"><i data-lucide="wallet" class="w-4 h-4 text-emerald-600"></i><span class="text-sm font-medium text-emerald-700">Tunai (Cash)</span></div></div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1.5">Nominal Diterima <span class="text-rose-500">*</span></label>
                            <div class="relative"><div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none"><span class="text-sm font-bold text-slate-400">Rp</span></div><input type="number" name="nominal_diterima" x-model.number="nominalDiterima" min="0" required class="w-full pl-12 pr-4 py-2.5 text-sm font-semibold text-slate-800 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition"></div>
                            <p class="text-xs text-slate-400 mt-1">Jumlah tagihan: <span class="font-semibold text-slate-600" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(tagihanJumlah)"></span></p>
                        </div>
                        <div><label class="block text-xs font-medium text-slate-600 mb-1.5">Catatan <span class="text-slate-400 font-normal">(opsional)</span></label><textarea name="catatan" rows="2" x-model="catatan" placeholder="Tambahkan catatan..." class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition"></textarea></div>
                        <div class="flex gap-2 pt-1">
                            <button type="button" @click="showKonfirmasi = false" class="flex-1 border border-slate-200 text-slate-600 text-sm font-semibold py-2.5 rounded-xl hover:bg-slate-50 transition-all">Batal</button>
                            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm"><i data-lucide="circle-check" class="w-4 h-4"></i>Konfirmasi Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </section>

    <section class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
        <div class="px-6 md:px-8 py-5 border-b border-slate-100"><h3 class="text-sm font-semibold text-slate-800">Riwayat Pembayaran</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b border-slate-50">
                    <th class="px-6 md:px-8 py-3.5 text-left font-medium text-slate-400 text-xs uppercase tracking-wider">Periode</th>
                    <th class="px-6 md:px-8 py-3.5 text-left font-medium text-slate-400 text-xs uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 md:px-8 py-3.5 text-left font-medium text-slate-400 text-xs uppercase tracking-wider">Status</th>
                    <th class="px-6 md:px-8 py-3.5 text-left font-medium text-slate-400 text-xs uppercase tracking-wider">Dibayar Pada</th>
                    <th class="px-6 md:px-8 py-3.5 text-left font-medium text-slate-400 text-xs uppercase tracking-wider">Bukti</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($pelanggan?->pembayarans ?? collect() as $bayar)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 md:px-8 py-4 text-sm font-medium text-slate-800">{{ $bayar->periode ?? '-' }}</td>
                            <td class="px-6 md:px-8 py-4 text-sm font-semibold text-slate-800">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                            <td class="px-6 md:px-8 py-4">
                                @if ($bayar->status === 'lunas')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Lunas</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-600"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>{{ ucfirst(str_replace('_', ' ', $bayar->status)) }}</span>
                                @endif
                            </td>
                            <td class="px-6 md:px-8 py-4 text-sm text-slate-500">{{ $bayar->dibayar_at?->format('d M Y') ?? '-' }}</td>
                            <td class="px-6 md:px-8 py-4"><span class="text-xs text-slate-400">{{ $bayar->bukti_bayar ? 'Tersedia' : '-' }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 md:px-8 py-12 text-center"><p class="text-sm text-slate-400">Belum ada riwayat pembayaran.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div x-show="showFoto" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center p-6 z-50" @click.self="showFoto = false">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-slate-800">Foto Rumah</h3>
                <button type="button" @click="showFoto = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors" aria-label="Tutup">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            @if ($pelanggan?->foto_rumah)
                <img src="{{ asset('storage/'.$pelanggan->foto_rumah) }}" alt="Foto rumah {{ $pelanggan->nama }}" class="w-full h-64 object-cover rounded-xl mb-3">
                <p class="text-xs text-slate-400 mb-4">Foto ini diambil saat pendaftaran pelanggan.</p>
            @else
                <div class="py-10 text-center">
                    <i data-lucide="image-off" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                    <p class="text-sm text-slate-400">Belum ada foto rumah untuk pelanggan ini.</p>
                </div>
            @endif
            <button type="button" @click="showFoto = false" class="w-full border border-slate-200 text-slate-600 font-semibold py-2.5 rounded-xl hover:bg-slate-50 transition-all mt-2">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function downloadAdminCustomerQr() {
    const image = document.getElementById('admin-customer-qr');
    if (!image) return;
    const canvas = document.createElement('canvas');
    canvas.width = 150;
    canvas.height = 150;
    canvas.getContext('2d').drawImage(image, 0, 0, 150, 150);
    const link = document.createElement('a');
    link.download = 'QR-{{ $pelanggan?->kode ?? 'pelanggan' }}.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}

function printAdminCustomerQr() {
    const image = document.getElementById('admin-customer-qr');
    if (!image) return;
    const printWindow = window.open('', '_blank');
    if (!printWindow) return;
    printWindow.document.write('<html><head><title>Cetak QR</title></head><body style="display:flex;justify-content:center;align-items:center;min-height:100vh"><img src="' + image.src + '" width="250" height="250"></body></html>');
    printWindow.document.close();
    printWindow.onload = () => printWindow.print();
}
</script>
@endpush