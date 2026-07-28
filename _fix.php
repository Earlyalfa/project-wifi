<?php
$file = 'c:/xampp/htdocs/wifi/resources/views/layouts/pelanggan.blade.php';
// Write a complete, clean HTML file
$content = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WiFiPay Pelanggan - @yield(\'title\', \'Dashboard\')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        * { font-family: \'Poppins\', sans-serif; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex" x-data="{ sidebarOpen: false }">

    <template x-teleport="body">
        <div x-show="sidebarOpen" x-cloak x-on:click="sidebarOpen = false" class="fixed inset-0 bg-black/40 z-20 md:hidden"></div>
    </template>

    <aside class="fixed md:sticky inset-y-0 left-0 z-30 w-64 bg-indigo-950 text-indigo-50 flex flex-col shrink-0 min-h-screen transition-all duration-300 ease-in-out -translate-x-full md:translate-x-0"
           x-bind:class="sidebarOpen ? \'translate-x-0\' : \'-translate-x-full\'"
           x-cloak>
        <div class="px-5 py-6 flex items-center gap-3 border-b border-indigo-900/50">
            <div class="w-9 h-9 rounded-xl bg-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <i data-lucide="wifi" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <span class="font-bold text-lg tracking-tight">WiFiPay</span>
                <p class="text-[10px] text-indigo-400/60 font-medium -mt-0.5">Pelanggan</p>
            </div>
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto sidebar-scroll">
            @php
                $menu = [
                    [\'route\' => \'pelanggan.dashboard\', \'icon\' => \'layout-dashboard\', \'label\' => \'Dashboard\'],
                    [\'route\' => \'pelanggan.pembayaran\', \'icon\' => \'wallet\', \'label\' => \'Pembayaran\'],
                    [\'route\' => null, \'icon\' => \'message-square-warning\', \'label\' => \'Pengaduan Gangguan\'],
                    [\'route\' => null, \'icon\' => \'user\', \'label\' => \'Profil\'],
                ];
            @endphp
            <p class="px-3 text-[10px] tracking-widest text-indigo-400/50 font-semibold uppercase pt-2 pb-2">Menu</p>
            @foreach ($menu as $item)
                @php
                    $active = $item[\'route\'] && request()->routeIs($item[\'route\'].\'*\');
                @endphp
                <a href="{{ $item[\'route\'] ? route($item[\'route\']) : \'#\' }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200
                          {{ $active ? \'bg-indigo-600 text-white font-semibold shadow-md shadow-indigo-600/20\' : \'text-indigo-100/70 hover:bg-indigo-900/60 hover:text-indigo-100\' }}">
                    <i data-lucide="{{ $item[\'icon\'] }}" class="w-4 h-4 shrink-0"></i>
                    <span>{{ $item[\'label\'] }}</span>
                    @if ($active)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></span>
                    @endif
                </a>
            @endforeach
        </nav>
        <div class="p-3 border-t border-indigo-900/50">
            <form action="{{ route(\'pelanggan.logout\') }}" method="POST">
                @csrf
                <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-indigo-100/60 hover:bg-rose-500/10 hover:text-rose-400 transition-all duration-200">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-h-screen w-full max-w-full overflow-x-hidden">
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-200/60 px-4 md:px-6 py-3 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <button x-on:click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-500">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
                <div class="relative hidden sm:block">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" placeholder="Cari sesuatu..." class="w-56 rounded-lg border border-slate-200 pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-slate-50">
                </div>
            <div class="flex items-center gap-3 md:gap-5">
                <div class="relative" x-data="{ open: false, unread: 0 }" @click.outside="open = false">
                    <button @click="open = !open" class="relative p-2 rounded-xl hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-all">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span x-show="unread > 0" x-text="unread" class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] flex items-center justify-center text-[10px] font-bold text-white bg-rose-500 rounded-full ring-2 ring-white"></span>
                    </button>
                    <div x-show="open" x-cloak class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 z-50">
                        <div class="px-4 py-3 border-b border-slate-100">
                            <h3 class="font-semibold text-slate-800 text-sm">Notifikasi</h3>
                        </div>
                        <div class="px-4 py-8 text-center text-slate-400 text-sm">
                            <i data-lucide="bell-off" class="w-8 h-8 mx-auto mb-2"></i>
                            <p>Tidak ada notifikasi</p>
                        </div>
                </div>
                <div class="flex items-center gap-2 md:gap-3 p-1.5 rounded-xl">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-sm">
                        <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-semibold text-slate-800 leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-400 font-medium">Pelanggan</p>
                    </div>
            </div>
        </header>
        <main class="flex-1 p-4 md:p-6 overflow-x-hidden">
            @if (session(\'status\'))
                <div class="mb-4 text-sm text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-3 flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 text-indigo-500 shrink-0"></i>
                    {{ session(\'status\') }}
                </div>
            @endif
            @yield(\'content\')
        </main>
    </div>
    <script>
        lucide.createIcons();
    </script>
    @stack(\'scripts\')
</body>
</html>';

file_put_contents($file, $content);
echo "Written: " . filesize($file) . " bytes\n";
