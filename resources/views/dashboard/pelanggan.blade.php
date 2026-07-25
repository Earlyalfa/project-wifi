<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard Pelanggan - WiFiPay</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen">
    <nav class="bg-indigo-500 text-white px-6 py-4 flex justify-between items-center">
        <span class="font-semibold">WiFiPay - Dashboard Pelanggan</span>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="bg-white/20 hover:bg-white/30 px-4 py-1.5 rounded-full text-sm transition">Keluar</button>
        </form>
    </nav>

    <div class="max-w-5xl mx-auto p-6">
        <h1 class="text-2xl font-bold text-slate-800 mb-2">Halo, {{ $user->name }} 👋</h1>
        <p class="text-slate-500 mb-6">Selamat datang di dashboard pelanggan. Role akun kamu saat ini:
            <span class="font-semibold text-indigo-600">{{ $user->role }}</span>
        </p>

        <div class="grid sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl p-5 shadow">
                <p class="text-sm text-slate-400 mb-1">Status Pembayaran</p>
                <p class="text-lg font-bold text-slate-800">Belum ada data</p>
            </div>
            <div class="bg-white rounded-xl p-5 shadow">
                <p class="text-sm text-slate-400 mb-1">Paket WiFi</p>
                <p class="text-lg font-bold text-slate-800">Belum ada paket</p>
            </div>
            <div class="bg-white rounded-xl p-5 shadow">
                <p class="text-sm text-slate-400 mb-1">Riwayat Transaksi</p>
                <p class="text-lg font-bold text-slate-800">0 transaksi</p>
            </div>
        </div>
    </div>
</body>
</html>
