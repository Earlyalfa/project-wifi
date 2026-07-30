@extends('layouts.pegawai')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                <i data-lucide="settings" class="w-4 h-4 text-violet-600"></i>
            </div>
            <h3 class="font-semibold text-slate-800">Pengaturan Notifikasi</h3>
        </div>

        <form action="{{ route('pegawai.settings.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                <div>
                    <p class="font-medium text-slate-800">Notifikasi Gangguan</p>
                    <p class="text-xs text-slate-400">Dapatkan notifikasi saat ada laporan gangguan baru</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="notif_gangguan" value="0">
                    <input type="checkbox" name="notif_gangguan" value="1"
                           class="sr-only peer"
                           {{ session('settings.notif_gangguan', true) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-violet-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                </label>
            </div>

            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                <div>
                    <p class="font-medium text-slate-800">Notifikasi Pembayaran</p>
                    <p class="text-xs text-slate-400">Dapatkan notifikasi saat ada konfirmasi pembayaran</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="notif_pembayaran" value="0">
                    <input type="checkbox" name="notif_pembayaran" value="1"
                           class="sr-only peer"
                           {{ session('settings.notif_pembayaran', true) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-violet-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                </label>
            </div>

            <button type="submit"
                    class="w-full bg-violet-600 hover:bg-violet-700 text-white font-semibold py-2.5 rounded-lg text-sm transition-colors mt-4">
                Simpan Pengaturan
            </button>
        </form>
    </div>
</div>
@endsection
