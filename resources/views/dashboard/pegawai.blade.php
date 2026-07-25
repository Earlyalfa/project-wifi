<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard Pegawai - WiFiPay</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen">
    <nav class="bg-emerald-600 text-white px-6 py-4 flex justify-between items-center">
        <span class="font-semibold">WiFiPay - Dashboard Pegawai</span>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="bg-white/20 hover:bg-white/30 px-4 py-1.5 rounded-full text-sm transition">Keluar</button>
        </form>
    </nav>

    <div class="max-w-5xl mx-auto p-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-2">Halo Pegawai, {{ $user->name }}</h1>
        <p class="text-slate-500 mb-6">Role akun kamu: <span class="font-semibold text-emerald-600">{{ $user->role }}</span></p>

        <div class="grid sm:grid-cols-2 gap-4">
            <a href="{{ route('pegawai.pembayaran') }}" class="bg-white rounded-xl p-5 shadow hover:shadow-md transition">
                <p class="font-semibold text-slate-800">Verifikasi Pembayaran</p>
                <p class="text-sm text-slate-400">Cek pembayaran pelanggan yang masuk</p>
            </a>
            <div class="bg-white rounded-xl p-5 shadow">
                <p class="font-semibold text-slate-800">Data Pelanggan</p>
                <p class="text-sm text-slate-400">Belum ada data</p>
            </div>
        </div>
    </div>
</body>
</html>
