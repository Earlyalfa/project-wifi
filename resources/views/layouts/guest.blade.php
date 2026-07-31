<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WiFiPay - @yield('title', 'Selamat Datang')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4 md:p-6">

    <div class="w-full max-w-4xl min-h-[550px] bg-white rounded-[32px] shadow-2xl overflow-hidden flex flex-col md:flex-row">

        {{-- PANEL KIRI --}}
        <div
            class="w-full md:w-1/2 relative overflow-hidden
                   bg-gradient-to-br from-[#1a1a2e] via-[#2b2550] to-[#4a3ca3]
                   p-10 flex flex-col text-white
                   shadow-[12px_0_35px_rgba(0,0,0,0.15)]">

            {{-- Nama Aplikasi --}}
            <h1 class="text-3xl font-extrabold tracking-tight">
                WiFi<span class="text-violet-300">Pay</span>
            </h1>

            {{-- Judul --}}
            <div class="mt-10">
                <h2 class="text-4xl font-extrabold leading-tight">
                    Selamat <span class="text-violet-300">Datang!</span>
                </h2>

                <p class="mt-4 text-base text-violet-200/90 leading-7">
                    Sistem Management Pembayaran WiFi
                </p>
            </div>

            {{-- Indikator --}}
            <div class="flex items-center gap-2 mt-7">
                <div class="w-10 h-1 rounded-full bg-violet-300"></div>
                <div class="w-2 h-2 rounded-full bg-violet-300"></div>
                <div class="w-2 h-2 rounded-full bg-violet-300 opacity-60"></div>
            </div>

            {{-- Logo --}}
            <div class="flex-1 flex items-center justify-center relative">

                {{-- Glow --}}
                <div class="absolute w-64 h-64 rounded-full bg-violet-500/20 blur-3xl"></div>

                {{-- Lingkaran --}}
                <div class="absolute w-72 h-72 rounded-full border border-violet-300/15"></div>

                <img
                    src="{{ asset('images/logo-wifipay.png') }}"
                    alt="WiFiPay"
                    class="relative z-10 w-52 h-52 object-contain drop-shadow-2xl transition duration-300 hover:scale-105">

            </div>

            {{-- Efek transisi ke panel kanan --}}
            <div class="absolute top-0 right-0 h-full w-12 bg-gradient-to-r from-transparent to-white/10 pointer-events-none"></div>

        </div>

        {{-- PANEL KANAN --}}
        <div class="w-full md:w-1/2 bg-[#FCFCFE] p-8 flex flex-col justify-center relative">

            {{-- Bayangan halus --}}
            <div class="absolute left-0 top-0 h-full w-px bg-violet-100/40"></div>

            @yield('content')

        </div>

    </div>

</body>
</html>