@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-cyan-100 flex items-center justify-center">
                <i data-lucide="settings" class="w-4 h-4 text-cyan-600"></i>
            </div>
            <h3 class="font-semibold text-slate-800">Pengaturan Aplikasi</h3>
        </div>

        <div class="space-y-6">
            <div class="p-4 bg-slate-50 rounded-xl">
                <p class="font-medium text-slate-800 mb-1">Informasi</p>
                <p class="text-sm text-slate-400">Halaman pengaturan masih dalam pengembangan. Fitur yang akan datang:</p>
                <ul class="mt-2 space-y-1 text-sm text-slate-500">
                    <li>• Pengaturan profil admin</li>
                    <li>• Konfigurasi paket WiFi</li>
                    <li>• Pengaturan notifikasi global</li>
                    <li>• Backup & restore data</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

