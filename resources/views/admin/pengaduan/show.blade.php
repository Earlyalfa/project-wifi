@extends('layouts.admin')

@section('title', 'Detail Pengaduan')
@section('page-title', 'Detail Pengaduan Gangguan')

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

    <div x-data="pengaduanHandler()">
        <a href="{{ route('admin.pengaduan.index') }}" class="flex items-center gap-2 text-slate-500 text-sm mb-4 hover:underline">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Data Pengaduan
        </a>

        <div class="grid lg:grid-cols-3 gap-5">
            {{-- KOLOM KIRI: Detail Pengaduan --}}
            <div class="lg:col-span-2 space-y-5">
                {{-- CARD INFO PENGADUAN --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <div class="flex items-start justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                                <i data-lucide="message-square-warning" class="w-5 h-5 text-amber-600"></i>
                            </div>
                            <div>
                                <h2 class="font-bold text-lg text-slate-800">Detail Pengaduan</h2>
                                <p class="text-sm text-slate-400">ID: #{{ $pengaduan->id }}</p>
                            </div>
                        </div>
                        @php
                            $sBadge = match($pengaduan->status) {
                                'Menunggu' => 'bg-amber-100 text-amber-700',
                                'Diproses' => 'bg-blue-100 text-blue-700',
                                'Selesai'  => 'bg-emerald-100 text-emerald-700',
                                default    => 'bg-slate-100 text-slate-500',
                            };
                            $sDot = match($pengaduan->status) {
                                'Menunggu' => 'bg-amber-500',
                                'Diproses' => 'bg-blue-500',
                                'Selesai'  => 'bg-emerald-500',
                                default    => 'bg-slate-400',
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ $sBadge }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $sDot }}"></span>
                            {{ $pengaduan->status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                            <i data-lucide="tag" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                            <div>
                                <p class="text-xs text-slate-400 font-medium">Jenis Gangguan</p>
                                <p class="text-sm text-slate-800 font-medium">{{ $pengaduan->jenis_gangguan }}</p>
                            </div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3">
                            <i data-lucide="calendar" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                            <div>
                                <p class="text-xs text-slate-400 font-medium">Tanggal Pengaduan</p>
                                <p class="text-sm text-slate-800 font-medium">{{ $pengaduan->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3.5 flex items-start gap-3 md:col-span-2">
                            <i data-lucide="heading" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0"></i>
                            <div>
                                <p class="text-xs text-slate-400 font-medium">Judul Keluhan</p>
                                <p class="text-sm text-slate-800 font-medium">{{ $pengaduan->judul_keluhan }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 bg-slate-50 rounded-xl p-4">
                        <div class="flex items-start gap-2">
                            <i data-lucide="file-text" class="w-4 h-4 text-slate-400 mt-0.5 shrink-0"></i>
                            <div class="w-full">
                                <p class="text-xs font-semibold text-slate-600 mb-1">Deskripsi Keluhan</p>
                                <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $pengaduan->deskripsi }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($pengaduan->foto)
                    <div class="mt-4">
                        <p class="text-xs font-semibold text-slate-600 mb-2">Foto Pendukung</p>
                        <a href="{{ asset('storage/' . $pengaduan->foto) }}" target="_blank">
                            <img src="{{ asset('storage/' . $pengaduan->foto) }}"
                                 alt="Foto Pengaduan"
                                 class="w-full max-w-md rounded-xl border border-slate-200 hover:opacity-90 transition-opacity">
                        </a>
                        <p class="text-xs text-slate-400 mt-1">Klik untuk memperbesar</p>
                    </div>
                    @endif

                    {{-- Keterangan --}}
                    @if ($pengaduan->keterangan)
                    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start gap-2">
                            <i data-lucide="message-square" class="w-4 h-4 text-blue-600 mt-0.5 shrink-0"></i>
                            <div>
                                <p class="text-xs font-semibold text-blue-700">Keterangan Admin</p>
                                <p class="text-sm text-blue-800 mt-1">{{ $pengaduan->keterangan }}</p>
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
                            <p class="text-sm font-semibold text-slate-800">{{ $pengaduan->user->name ?? '-' }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3.5">
                            <p class="text-xs text-slate-400 font-medium">Email</p>
                            <p class="text-sm text-slate-800">{{ $pengaduan->user->email ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Update Status --}}
            <div class="space-y-5">
                {{-- CARD UPDATE STATUS --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-5 h-5 text-violet-600"></i>
                        </div>
                        <h3 class="font-semibold text-slate-800">Update Status</h3>
                    </div>

                    <form action="{{ route('admin.pengaduan.update-status', $pengaduan) }}" method="POST" x-ref="formStatus">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label class="block text-xs font-medium text-slate-600 mb-1.5">Status</label>
                            <select name="status" x-model="selectedStatus"
                                    class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">
                                <option value="Menunggu" {{ $pengaduan->status === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Diproses" {{ $pengaduan->status === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="Selesai" {{ $pengaduan->status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-medium text-slate-600 mb-1.5">Keterangan <span class="text-slate-400">(opsional)</span></label>
                            <textarea name="keterangan" rows="4"
                                      placeholder="Tambahkan keterangan untuk pelanggan..."
                                      class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400">{{ old('keterangan', $pengaduan->keterangan) }}</textarea>
                        </div>

                        <button type="button" @click="konfirmasiUpdate()"
                                class="w-full flex items-center justify-center gap-2 bg-violet-600 hover:bg-violet-700 text-white font-semibold py-2.5 rounded-lg transition-colors text-sm">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            Update Status
                        </button>
                    </form>
                </div>

                {{-- Timeline Status --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center">
                            <i data-lucide="clock" class="w-5 h-5 text-sky-600"></i>
                        </div>
                        <h3 class="font-semibold text-slate-800">Riwayat Status</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                                <div class="w-0.5 h-8 bg-slate-200"></div>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $pengaduan->status }}</p>
                                <p class="text-xs text-slate-400">{{ $pengaduan->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 rounded-full bg-violet-300"></div>
                                <div class="w-0.5 h-8 bg-slate-200"></div>
                            </div>
                            <div>
                                <p class="text-sm text-slate-600">Dibuat</p>
                                <p class="text-xs text-slate-400">{{ $pengaduan->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function pengaduanHandler() {
        return {
            selectedStatus: '{{ $pengaduan->status }}',
            konfirmasiUpdate() {
                const statusLabels = {
                    'Menunggu': { icon: 'clock', color: '#f59e0b', label: 'Menunggu' },
                    'Diproses': { icon: 'loader', color: '#3b82f6', label: 'Diproses' },
                    'Selesai': { icon: 'check-circle', color: '#10b981', label: 'Selesai' },
                };
                const s = statusLabels[this.selectedStatus] || statusLabels['Menunggu'];

                Swal.fire({
                    title: 'Ubah Status?',
                    html: `<p class="text-sm text-slate-500">Status pengaduan akan diubah menjadi:</p>
                           <p class="text-lg font-bold mt-2" style="color: ${s.color}">${s.label}</p>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: s.color,
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Update',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$refs.formStatus.submit();
                    }
                });
            }
        };
    }
</script>
@endpush
