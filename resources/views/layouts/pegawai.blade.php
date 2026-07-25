<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WiFiPay Pegawai - @yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        * { font-family: 'Poppins', sans-serif; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex" x-data="{ sidebarOpen: false, profileOpen: false }">

    {{-- SIDEBAR OVERLAY (MOBILE) --}}
    <template x-teleport="body">
        <div x-show="sidebarOpen" x-cloak x-on:click="sidebarOpen = false" class="fixed inset-0 bg-black/40 z-20 md:hidden"></div>
    </template>

    {{-- SIDEBAR --}}
    <aside class="fixed md:sticky inset-y-0 left-0 z-30 w-64 bg-indigo-950 text-indigo-50 flex flex-col shrink-0 min-h-screen transition-all duration-300 ease-in-out -translate-x-full md:translate-x-0"
           x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           x-cloak>
        
        {{-- Logo --}}
        <div class="px-5 py-6 flex items-center gap-3 border-b border-indigo-900/50">
            <div class="w-9 h-9 rounded-xl bg-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <i data-lucide="wifi" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <span class="font-bold text-lg tracking-tight">WiFiPay</span>
                <p class="text-[10px] text-indigo-400/60 font-medium -mt-0.5">Pegawai </p>
            </div>
        </div>

        {{-- Menu --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto sidebar-scroll">
            @php
                $menu = [
                    ['route' => 'pegawai.dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
                    ['route' => 'pegawai.scan-barcode', 'icon' => 'scan-qr-code', 'label' => 'Scan QR Code'],
                    ['route' => 'pegawai.pelanggan.index', 'icon' => 'users', 'label' => 'Data Pelanggan'],
                    ['route' => null, 'icon' => 'credit-card', 'label' => 'Pembayaran'],
                    ['route' => null, 'icon' => 'message-square-warning', 'label' => 'Pengaduan Gangguan'],
                    ['route' => null, 'icon' => 'map-pin', 'label' => 'Riwayat Kunjungan'],
                ];
            @endphp

            <p class="px-3 text-[10px] tracking-widest text-indigo-400/50 font-semibold uppercase pt-2 pb-2">Menu Utama</p>

            @foreach ($menu as $item)
                @php
                    $active = $item['route'] && request()->routeIs($item['route'].'*');
                @endphp
                <a href="{{ $item['route'] ? route($item['route']) : '#' }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200
                          {{ $active ? 'bg-indigo-600 text-white font-semibold shadow-md shadow-indigo-600/20' : 'text-indigo-100/70 hover:bg-indigo-900/60 hover:text-indigo-100' }}">
                    <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4 shrink-0"></i>
                    <span>{{ $item['label'] }}</span>
                    @if ($active)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
            @endforeach
        </nav>

        {{-- Logout --}}
        <div class="p-3 border-t border-indigo-900/50">
            <form action="{{ route('pegawai.logout') }}" method="POST">
                @csrf
                <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-indigo-100/60 hover:bg-rose-500/10 hover:text-rose-400 transition-all duration-200">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col min-h-screen w-full max-w-full overflow-x-hidden">

        {{-- NAVBAR --}}
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/60 px-4 md:px-6 py-3 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <button x-on:click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-500">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <h1 class="font-semibold text-slate-800 text-base md:text-lg">@yield('page-title', 'Dashboard Pegawai')</h1>
            </div>

            <div class="flex items-center gap-3 md:gap-5">

                {{-- Notifikasi --}}
                <button class="relative p-2 rounded-xl hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-all">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full ring-2 ring-white"></span>
                </button>

                {{-- Profile Dropdown --}}
                <div class="relative" x-data="{ open: false }" x-on:click.outside="open = false">
                    <button x-on:click="open = !open" class="flex items-center gap-2 md:gap-3 p-1.5 rounded-xl hover:bg-slate-100 transition-all">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-sm">
                            <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-sm font-semibold text-slate-800 leading-tight">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-slate-400 font-medium">Pegawai</p>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 hidden md:block" x-bind:class="open ? 'rotate-180' : ''"></i>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" x-cloak
                         class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50">
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400">{{ auth()->user()->email ?? 'pegawai@wifiPay.id' }}</p>
                        </div>
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
                            <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                            Profil Saya
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
                            <i data-lucide="settings" class="w-4 h-4 text-slate-400"></i>
                            Pengaturan
                        </a>
                        <div class="border-t border-slate-100 mt-1 pt-1">
                            <form action="{{ route('pegawai.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition-colors">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- CONTENT --}}
        <main class="flex-1 p-4 md:p-6 overflow-x-hidden">
            @if (session('status'))
                <div class="mb-4 text-sm text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-3 flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 text-indigo-500 shrink-0"></i>
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>

