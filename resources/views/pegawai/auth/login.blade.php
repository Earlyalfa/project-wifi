<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Pegawai - WiFiPay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl p-8">

        <div class="flex items-center gap-2 mb-1">
            <i data-lucide="wifi" class="w-5 h-5 text-emerald-600"></i>
            <span class="font-bold text-slate-800">WiFiPay</span>
        </div>
        <p class="text-xs text-slate-400 mb-6">Sistem Manajemen Pembayaran WiFi</p>

        <div class="flex flex-col items-center text-center mb-6">
            <div class="w-14 h-14 rounded-full bg-emerald-500 flex items-center justify-center mb-3">
                <i data-lucide="user" class="w-6 h-6 text-white"></i>
            </div>
            <h2 class="font-bold text-emerald-600">Login Pegawai</h2>
            <p class="text-xs text-slate-400 mt-1">Masuk untuk mengakses dashboard pegawai</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('pegawai.login.store') }}" method="POST" class="flex flex-col gap-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Username</label>
                <div class="relative">
                    <i data-lucide="user" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username"
                           class="w-full rounded-lg border border-slate-200 pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400"
                           required autofocus>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                <div class="relative">
                    <i data-lucide="lock" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="password" name="password" placeholder="Masukkan password"
                           class="w-full rounded-lg border border-slate-200 pl-9 pr-9 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400"
                           required>
                    <i data-lucide="eye" class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2"></i>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300">
                    Ingat saya
                </label>
                <a href="#" class="text-emerald-600 font-medium hover:underline">Lupa password?</a>
            </div>

            <button type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-lg transition">
                Login
            </button>

            <a href="{{ route('home') }}" class="text-center text-sm text-slate-500 hover:underline flex items-center justify-center gap-1">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali ke pilihan peran
            </a>
        </form>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
