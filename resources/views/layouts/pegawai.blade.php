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
                    ['route' => 'pegawai.gangguan.index', 'icon' => 'message-square-warning', 'label' => 'Pengaduan Gangguan'],
                    ['route' => 'pegawai.kunjungan.index', 'icon' => 'map-pin', 'label' => 'Riwayat Kunjungan'],
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
                <div class="relative" x-data="notificationDropdown()" x-init="init()" @click.outside="open = false">
                    <button @click="toggle()" class="relative p-2 rounded-xl hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-all">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span x-show="unreadCount > 0"
                              x-text="unreadCount"
                              class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] flex items-center justify-center text-[10px] font-bold text-white bg-rose-500 rounded-full ring-2 ring-white"></span>
                    </button>

                    {{-- Dropdown Notifikasi --}}
                    <div x-show="open" x-cloak
                         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 max-h-[500px] flex flex-col">
                        {{-- Header --}}
                        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 shrink-0">
                            <h3 class="font-semibold text-slate-800 text-sm">Notifikasi</h3>
                            <button x-show="unreadCount > 0" @click="markAllRead()"
                                    class="text-xs text-indigo-600 font-medium hover:underline">
                                Tandai sudah dibaca
                            </button>
                        </div>

                        {{-- Daftar Notifikasi --}}
                        <div class="overflow-y-auto flex-1 max-h-[350px]">
                            <template x-if="notifications.length === 0">
                                <div class="flex flex-col items-center justify-center py-10 text-slate-400">
                                    <i data-lucide="bell-off" class="w-8 h-8 mb-2"></i>
                                    <p class="text-sm">Tidak ada notifikasi</p>
                                </div>
                            </template>
                            <template x-for="notif in notifications" :key="notif.id">
                                <div @click="markRead(notif)"
                                     :class="{'bg-indigo-50/50': !notif.is_read, 'hover:bg-slate-50': true}"
                                     class="flex items-start gap-3 px-4 py-3 border-b border-slate-50 cursor-pointer transition-colors">
                                    {{-- Icon --}}
                                    <div :class="'w-8 h-8 rounded-full flex items-center justify-center shrink-0 mt-0.5 ' + (notif.color === 'rose' ? 'bg-rose-100' : notif.color === 'amber' ? 'bg-amber-100' : notif.color === 'emerald' ? 'bg-emerald-100' : 'bg-indigo-100')">
                                        <i :data-lucide="notif.icon"
                                           :class="'w-4 h-4 ' + (notif.color === 'rose' ? 'text-rose-600' : notif.color === 'amber' ? 'text-amber-600' : notif.color === 'emerald' ? 'text-emerald-600' : 'text-indigo-600')"></i>
                                    </div>
                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-slate-700" x-text="notif.message"></p>
                                        <p class="text-xs text-slate-400 mt-0.5" x-text="notif.time_ago"></p>
                                    </div>
                                    {{-- Unread dot --}}
                                    <span x-show="!notif.is_read" class="w-2 h-2 rounded-full bg-indigo-500 shrink-0 mt-2"></span>
                                </div>
                            </template>
                        </div>

                        {{-- Footer --}}
                        <div class="px-4 py-2.5 border-t border-slate-100 text-center shrink-0">
                            <a href="#" class="text-xs text-slate-400 hover:text-slate-600 font-medium">Lihat semua notifikasi</a>
                        </div>
                    </div>
                </div>

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
<a href="{{ route('pegawai.profile.show') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
                            <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                            Profil Saya
                        </a>
                        <a href="{{ route('pegawai.settings') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
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

        function notificationDropdown() {
            return {
                open: false,
                unreadCount: 0,
                notifications: [],
                pollingId: null,

                init() {
                    this.fetchNotifications();
                    // Auto-poll every 30 seconds
                    this.pollingId = setInterval(() => {
                        this.fetchNotifications();
                    }, 30000);
                },

                toggle() {
                    this.open = !this.open;
                    if (this.open) {
                        this.fetchNotifications();
                    }
                },

                fetchNotifications() {
                    fetch('/pegawai/notifications')
                        .then(res => res.json())
                        .then(data => {
                            this.unreadCount = data.unread_count;
                            this.notifications = data.notifications;
                            // Re-initialize Lucide icons for new DOM elements
                            this.$nextTick(() => {
                                if (window.lucide) lucide.createIcons();
                            });
                        })
                        .catch(() => {});
                },

                markRead(notif) {
                    if (!notif.is_read) {
                        fetch('/pegawai/notifications/' + notif.id + '/read', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                            .then(res => res.json())
                            .then(() => {
                                notif.is_read = true;
                                this.unreadCount = Math.max(0, this.unreadCount - 1);
                                // Re-render icons
                                this.$nextTick(() => {
                                    if (window.lucide) lucide.createIcons();
                                });
                            })
                            .catch(() => {});
                    }
                    // Navigate if there's a URL
                    if (notif.url) {
                        window.location.href = notif.url;
                    }
                },

                markAllRead() {
                    fetch('/pegawai/notifications/read-all', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                        .then(res => res.json())
                        .then(() => {
                            this.notifications.forEach(n => n.is_read = true);
                            this.unreadCount = 0;
                            this.$nextTick(() => {
                                if (window.lucide) lucide.createIcons();
                            });
                        })
                        .catch(() => {});
                }
            };
        }
    </script>
    @stack('scripts')
</body>
</html>

