<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WiFiPay Admin - @yield('title', 'Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        * { font-family: 'Poppins', sans-serif; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex" x-data="{ sidebarOpen: false, notifOpen: false }">

    {{-- SIDEBAR OVERLAY MOBILE --}}
    <template x-teleport="body">
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/40 z-20 md:hidden"></div>
    </template>

    {{-- SIDEBAR --}}
    <aside class="fixed md:sticky inset-y-0 left-0 z-30 w-64 bg-indigo-950 text-indigo-50 flex flex-col shrink-0 min-h-screen transition-all duration-300 ease-in-out -translate-x-full md:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           x-cloak>

        {{-- Logo --}}
        <div class="px-5 py-6 flex items-center gap-3 border-b border-indigo-900/50">
            <div class="w-9 h-9 rounded-xl bg-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <i data-lucide="wifi" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <span class="font-bold text-lg tracking-tight">WiFiPay</span>
                <p class="text-[10px] text-indigo-400/60 font-medium -mt-0.5">Admin Panel</p>
            </div>
        </div>

        {{-- Menu --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto sidebar-scroll">
            @php
$menu = [
                    ['route' => 'admin.dashboard',   'icon' => 'layout-dashboard',     'label' => 'Dashboard'],
                    ['route' => 'admin.users',        'icon' => 'users',                'label' => 'Data Pengguna'],
                    ['route' => 'admin.paket.index', 'icon' => 'signal',               'label' => 'Paket WiFi'],
                    ['route' => null,                'icon' => 'credit-card',          'label' => 'Pembayaran'],
                    ['route' => null,                'icon' => 'message-square-warning', 'label' => 'Pengaduan Gangguan'],
                    ['route' => null,                'icon' => 'file-text',            'label' => 'Laporan'],
                    ['route' => 'admin.settings',    'icon' => 'settings',             'label' => 'Pengaturan'],
                ];
            @endphp

            <p class="px-3 text-[10px] tracking-widest text-indigo-400/50 font-semibold uppercase pt-2 pb-2">Menu Utama</p>

            @foreach ($menu as $item)
                @php $active = $item['route'] && request()->routeIs($item['route']); @endphp
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

        {{-- Admin Info --}}
        <div class="p-3 border-t border-indigo-900/50">
            <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shrink-0 shadow-sm">
                    <span class="text-xs font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-indigo-100 leading-tight truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-indigo-400/60 font-medium">Administrator</p>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST" class="mt-1">
                @csrf
                <button class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm text-indigo-100/60 hover:bg-rose-500/10 hover:text-rose-400 transition-all duration-200">
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
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-500">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                {{-- Search --}}
                <div class="relative hidden sm:block">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
<input type="text" placeholder="Cari sesuatu..."
                           class="w-56 rounded-lg border border-slate-200 pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-slate-50">
                </div>
            </div>

            <div class="flex items-center gap-3 md:gap-5">

                {{-- Notifikasi --}}
                <div class="relative" x-data="notifDropdown()" x-init="init()" @click.outside="notifOpen = false">
<button @click="toggle()" class="relative p-2 rounded-xl hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-all">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span x-show="unread > 0"
                              x-text="unread"
                              class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] flex items-center justify-center text-[10px] font-bold text-white bg-rose-500 rounded-full ring-2 ring-white"></span>
                    </button>

                    <div x-show="notifOpen" x-cloak
                         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 max-h-[500px] flex flex-col">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 shrink-0">
                            <h3 class="font-semibold text-slate-800 text-sm">Notifikasi</h3>
                            <button x-show="unread > 0" @click="markAllRead()"
                                    class="text-xs text-indigo-600 font-medium hover:underline">Tandai sudah dibaca</button>
                        </div>
                        <div class="overflow-y-auto flex-1 max-h-[350px]">
                            <template x-if="items.length === 0">
                                <div class="flex flex-col items-center py-10 text-slate-400">
                                    <i data-lucide="bell-off" class="w-8 h-8 mb-2"></i>
                                    <p class="text-sm">Tidak ada notifikasi</p>
                                </div>
                            </template>
                            <template x-for="n in items" :key="n.id">
                                <div @click="markRead(n)"
                                     :class="{'bg-indigo-50/50': !n.is_read}"
                                     class="flex items-start gap-3 px-4 py-3 border-b border-slate-50 cursor-pointer transition-colors hover:bg-slate-50">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 mt-0.5"
                                         :class="n.color === 'rose' ? 'bg-rose-100' : n.color === 'amber' ? 'bg-amber-100' : n.color === 'emerald' ? 'bg-emerald-100' : 'bg-indigo-100'">
                                        <i :data-lucide="n.icon" class="w-4 h-4"
                                           :class="n.color === 'rose' ? 'text-rose-600' : n.color === 'amber' ? 'text-amber-600' : n.color === 'emerald' ? 'text-emerald-600' : 'text-indigo-600'"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-slate-700" x-text="n.message"></p>
                                        <p class="text-xs text-slate-400 mt-0.5" x-text="n.time_ago"></p>
                                    </div>
                                    <span x-show="!n.is_read" class="w-2 h-2 rounded-full bg-indigo-500 shrink-0 mt-2"></span>
                                </div>
                            </template>
                        </div>
                        <div class="px-4 py-2.5 border-t border-slate-100 text-center shrink-0">
                            <a href="#" class="text-xs text-slate-400 hover:text-slate-600 font-medium">Lihat semua</a>
                        </div>
                    </div>
                </div>

                {{-- Profile --}}
                <div class="flex items-center gap-2 md:gap-3 p-1.5 rounded-xl">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-sm">
                        <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-semibold text-slate-800 leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-400 font-medium">Admin</p>
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

        function notifDropdown() {
            return {
                notifOpen: false,
                unread: 0,
                items: [],
                timer: null,

                init() {
                    this.fetch();
                    this.timer = setInterval(() => this.fetch(), 30000);
                },

                toggle() {
                    this.notifOpen = !this.notifOpen;
                    if (this.notifOpen) this.fetch();
                },

                fetch() {
                    fetch('/admin/notifications')
                        .then(r => r.json())
                        .then(d => {
                            this.unread = d.unread_count;
                            this.items = d.notifications;
                            this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
                        }).catch(() => {});
                },

                markRead(n) {
                    if (!n.is_read) {
                        fetch('/admin/notifications/' + n.id + '/read', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                            .then(() => {
                                n.is_read = true;
                                this.unread = Math.max(0, this.unread - 1);
                                this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
                            }).catch(() => {});
                    }
                    if (n.url) window.location.href = n.url;
                },

                markAllRead() {
                    fetch('/admin/notifications/read-all', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                        .then(() => {
                            this.items.forEach(i => i.is_read = true);
                            this.unread = 0;
                            this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
                        }).catch(() => {});
                },
            };
        }
    </script>
    @stack('scripts')
</body>
</html>

