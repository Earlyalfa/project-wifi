@extends('layouts.pelanggan')

@section('title', 'Pengaduan Gangguan')
@section('page-title', 'Pengaduan Gangguan')

@section('content')
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Subtitle --}}
    <p class="text-sm text-slate-400 -mt-3 mb-6">Sampaikan keluhan gangguan internet Anda, kami akan segera menindaklanjutinya.</p>

    {{-- ===================== GRID: FORM + INFO ===================== --}}
    <div class="grid grid-cols-3 gap-6">
        {{-- KOLOM KIRI: FORM (2/3) --}}
        <div class="col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                {{-- Header --}}
                <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-amber-50/50 to-transparent flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-sm">
                            <i data-lucide="message-square-plus" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800">Form Pengaduan Gangguan</h3>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <form action="{{ route('pelanggan.pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Jenis Gangguan --}}
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis Gangguan <span class="text-rose-500">*</span></label>
                            <select name="jenis_gangguan"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-violet-600 outline-none transition-all @error('jenis_gangguan') border-rose-300 bg-rose-50 @enderror">
                                <option value="">-- Pilih Jenis Gangguan --</option>
                                <option value="Internet Putus" {{ old('jenis_gangguan') == 'Internet Putus' ? 'selected' : '' }}>Internet Putus</option>
                                <option value="Internet Lambat" {{ old('jenis_gangguan') == 'Internet Lambat' ? 'selected' : '' }}>Internet Lambat</option>
                                <option value="Tidak Bisa Terhubung" {{ old('jenis_gangguan') == 'Tidak Bisa Terhubung' ? 'selected' : '' }}>Tidak Bisa Terhubung</option>
                                <option value="Router Bermasalah" {{ old('jenis_gangguan') == 'Router Bermasalah' ? 'selected' : '' }}>Router Bermasalah</option>
                                <option value="Lainnya" {{ old('jenis_gangguan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('jenis_gangguan')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Judul Keluhan --}}
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Keluhan <span class="text-rose-500">*</span></label>
                            <input type="text" name="judul_keluhan" value="{{ old('judul_keluhan') }}"
                                   placeholder="Contoh: Internet sering putus"
                                   class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-violet-600 outline-none transition-all @error('judul_keluhan') border-rose-300 bg-rose-50 @enderror">
                            @error('judul_keluhan')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Deskripsi Keluhan --}}
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Keluhan <span class="text-rose-500">*</span></label>
                            <textarea name="deskripsi" rows="5"
                                      placeholder="Jelaskan keluhan Anda secara detail..."
                                      class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-violet-600 outline-none transition-all @error('deskripsi') border-rose-300 bg-rose-50 @enderror">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Upload Foto --}}
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Upload Foto (Opsional)</label>
                            <div class="relative">
                                <input type="file" name="foto" id="foto"
                                       accept="image/jpg,image/jpeg,image/png"
                                       class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 transition-all cursor-pointer">
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Format: JPG, JPEG, PNG. Maksimal 2 MB.</p>
                            @error('foto')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                            {{-- Preview Foto --}}
                            <div id="preview-foto" class="mt-3 hidden">
                                <img id="preview-img" src="#" alt="Preview" class="w-40 h-40 object-cover rounded-xl border border-slate-200">
                            </div>
                        </div>

                        {{-- Tombol Kirim --}}
                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-md shadow-violet-600/20">
                                <i data-lucide="send" class="w-4 h-4"></i>
                                Kirim Pengaduan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: INFORMASI PENTING (1/3) --}}
        <div class="col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-24">
                <div class="px-5 py-4 border-b border-slate-100 bg-gradient-to-r from-blue-50/50 to-transparent flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-sm">
                            <i data-lucide="info" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800">Informasi Penting</h3>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div class="space-y-4">
                        <div class="rounded-xl bg-gradient-to-br from-amber-50/80 to-white border border-amber-200/60 p-4 hover:shadow-md hover:border-amber-300 transition-all duration-300 group">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center shrink-0 shadow-sm shadow-amber-200/50 group-hover:scale-110 transition-transform duration-300">
                                    <i data-lucide="clock" class="w-5 h-5 text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Proses Penanganan</p>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Kami akan merespon pengaduan Anda <strong class="text-amber-600">1x24 jam</strong> pada jam kerja.</p>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-xl bg-gradient-to-br from-emerald-50/80 to-white border border-emerald-200/60 p-4 hover:shadow-md hover:border-emerald-300 transition-all duration-300 group">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-500 flex items-center justify-center shrink-0 shadow-sm shadow-emerald-200/50 group-hover:scale-110 transition-transform duration-300">
                                    <i data-lucide="camera" class="w-5 h-5 text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Lampiran Foto</p>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Lampirkan foto gangguan (jika ada) agar memudahkan kami dalam pengecekan.</p>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-xl bg-gradient-to-br from-violet-50/80 to-white border border-violet-200/60 p-4 hover:shadow-md hover:border-violet-300 transition-all duration-300 group">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center shrink-0 shadow-sm shadow-violet-200/50 group-hover:scale-110 transition-transform duration-300">
                                    <i data-lucide="message-circle" class="w-5 h-5 text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Status Pengaduan</p>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Pantau perkembangan pengaduan melalui status:
                                        <span class="inline-flex items-center gap-1 mt-1 flex-wrap">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Menunggu</span>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>Diproses</span>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Selesai</span>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-xl bg-gradient-to-br from-rose-50/80 to-white border border-rose-200/60 p-4 hover:shadow-md hover:border-rose-300 transition-all duration-300 group">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-400 to-rose-500 flex items-center justify-center shrink-0 shadow-sm shadow-rose-200/50 group-hover:scale-110 transition-transform duration-300">
                                    <i data-lucide="messages-square" class="w-5 h-5 text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">Masukan & Saran</p>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Masukan dari pelanggan membantu kami meningkatkan kualitas layanan internet ke depannya.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== RIWAYAT PENGADUAN (FULL LAYAR) ===================== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mt-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-sky-100 flex items-center justify-center">
                    <i data-lucide="history" class="w-4 h-4 text-sky-600"></i>
                </div>
                <h3 class="font-semibold text-slate-800">Riwayat Pengaduan</h3>
            </div>
        </div>
        <div class="overflow-x-auto -mx-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-slate-100">
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">No</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Tanggal</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Jenis Gangguan</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Judul Keluhan</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Deskripsi</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Foto</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Status</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Keterangan</th>
                        <th class="px-5 py-3 font-semibold text-slate-400 text-xs uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($pengaduans as $index => $p)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3 text-slate-600">{{ $index + 1 }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $p->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-3 text-slate-600">{{ $p->jenis_gangguan }}</td>
                            <td class="px-5 py-3 text-slate-600 max-w-[150px] truncate">{{ $p->judul_keluhan }}</td>
                            <td class="px-5 py-3 text-slate-600 max-w-[150px] truncate">{{ Str::limit($p->deskripsi, 40) }}</td>
                            <td class="px-5 py-3">
                                @if ($p->foto)
                                    <a href="{{ asset('storage/' . $p->foto) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-violet-600 hover:underline">
                                        <i data-lucide="image" class="w-3.5 h-3.5"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $badgeColor = match($p->status) {
                                        'Menunggu' => 'bg-amber-100 text-amber-700',
                                        'Diproses' => 'bg-blue-100 text-blue-700',
                                        'Selesai'  => 'bg-emerald-100 text-emerald-700',
                                        default    => 'bg-slate-100 text-slate-500',
                                    };
                                    $dotColor = match($p->status) {
                                        'Menunggu' => 'bg-amber-500',
                                        'Diproses' => 'bg-blue-500',
                                        'Selesai'  => 'bg-emerald-500',
                                        default    => 'bg-slate-500',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                    {{ $p->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-slate-500 text-xs max-w-[120px] truncate">{{ $p->keterangan ?? '-' }}</td>
                            <td class="px-5 py-3">
                                <a href="{{ route('pelanggan.pengaduan.show', $p) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-violet-100 text-violet-700 hover:bg-indigo-200 transition-all">
                                    <i data-lucide="eye" class="w-3 h-3"></i> 
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-8 text-center text-slate-400">Belum ada pengaduan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Preview Foto Script --}}
    <script>
        document.getElementById('foto')?.addEventListener('change', function(e) {
            const preview = document.getElementById('preview-foto');
            const img = document.getElementById('preview-img');
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        });
    </script>

    {{-- SweetAlert Notifikasi Sukses --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
            });
        </script>
    @endif
@endsection

