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
        <div class="w-full md:w-1/2 bg-gradient-to-b from-[#f0f3ff] via-[#e1e7ff] to-[#c7d2fe] p-8 flex flex-col text-slate-800 relative">
            
            <h1 class="text-lg font-bold tracking-wider mb-6 text-indigo-700 opacity-90">WiFiPay</h1>
            
            <h2 class="text-2xl font-bold mb-1 text-indigo-950">Selamat Datang!</h2>
            <p class="text-sm text-indigo-800/80 mb-4">Sistem Management Pembayaran WiFi</p>

            <div class="flex items-center gap-1.5 mb-8 opacity-60">
                <div class="w-8 h-1 bg-indigo-700 rounded-full"></div>
                <div class="w-2 h-1 bg-indigo-700/40 rounded-full"></div>
                <div class="w-2 h-1 bg-indigo-700/40 rounded-full"></div>
            </div>

            <div class="flex-1 flex items-center justify-center min-h-[220px]">
                <div class="w-44 h-44 bg-white/70 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg shadow-indigo-900/5 transition duration-500 transform hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#4f46e5" class="w-16 h-16 opacity-90">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M2.25 21h19.5M4.5 21V11.855M12 6.25a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25c0-4.142-3.358-7.5-7.5-7.5s-7.5 3.358-7.5 7.5" opacity="0.2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 8.25c0-2.485-2.015-4.5-4.5-4.5s-4.5 2.015-4.5 4.5" opacity="0.4" />
                    </svg>
                </div>
            </div>

        </div>

        {{-- PANEL KANAN: KONTEN DINAMIS --}}
        <div class="w-full md:w-1/2 bg-slate-50 p-8 flex flex-col justify-center">
            @yield('content')
        </div>

    </div>

</body>
</html>