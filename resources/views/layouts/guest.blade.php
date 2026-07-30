<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WiFiPay - @yield('title', 'Selamat Datang')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4 md:p-6">

    <div class="w-full max-w-4xl bg-white rounded-[32px] shadow-xl overflow-hidden flex flex-col md:flex-row min-h-[550px]">

{{-- PANEL KIRI: SELAMAT DATANG --}}
        <div class="w-full md:w-1/2 bg-gradient-to-b from-[#1a1a2e] via-[#2d2b4d] to-[#3d3a6c] p-8 flex flex-col text-white relative">
            
            <h1 class="text-lg font-bold tracking-wider mb-6 text-violet-300 opacity-90">WiFiPay</h1>
            
            <h2 class="text-2xl font-bold mb-1 text-white">Selamat Datang!</h2>
            <p class="text-sm text-violet-200/80 mb-4">Sistem Management Pembayaran WiFi</p>

            <div class="flex items-center gap-1.5 mb-8 opacity-60">
                <div class="w-8 h-1 bg-violet-400 rounded-full"></div>
                <div class="w-2 h-1 bg-violet-400/40 rounded-full"></div>
                <div class="w-2 h-1 bg-violet-400/40 rounded-full"></div>
            </div>

<div class="flex-1 flex items-center justify-center min-h-[220px]">
    <img src="{{ asset('images/logo-wifipay.png') }}"
         alt="Logo WiFiPay"
         class="w-72 h-72 object-contain drop-shadow-lg transition duration-500 hover:scale-105">
</div>

        </div>

        {{-- PANEL KANAN: KONTEN DINAMIS --}}
        <div class="w-full md:w-1/2 bg-slate-50 p-8 flex flex-col justify-center">
            @yield('content')
        </div>

    </div>

</body>
</html>