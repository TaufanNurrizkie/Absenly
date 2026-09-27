<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Absenly') — Absenly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="{{ asset('img/icb_Logo.png') }}">
    @include('partials.pwa-head')
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --sidebar-w: 256px;
            --blue-brand: #2563EB;
            --blue-light: #EFF6FF;
            --surface: #F8FAFC;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--surface);
            margin: 0;
        }

        #sidebar {
            width: var(--sidebar-w);
            background: #fff;
            border-right: 1px solid #E2E8F0;
            position: fixed;
            top: 0;
            left: 0;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            z-index: 40;
            transform: translateX(-100%);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
        }

        @media (min-width: 1024px) {
            #sidebar {
                transform: translateX(0);
                position: sticky;
                top: 0;
                height: 100vh;
                flex-shrink: 0;
            }

            #mobileMenuBtn {
                display: none;
            }

            #mobileOverlay {
                display: none !important;
            }
        }

        #sidebar.open {
            transform: translateX(0);
        }

        .app-shell {
            display: flex;
            min-height: 100dvh;
        }

        .main-content {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            background: rgba(248, 250, 252, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #E2E8F0;
            padding: 0 1.5rem;
            height: 60px;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.875rem;
            border-radius: 0.625rem;
            color: #64748B;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
            margin-bottom: 2px;
        }

        .nav-link:hover {
            background: #F1F5F9;
            color: #1E293B;
        }

        .nav-link.active {
            background: var(--blue-light);
            color: var(--blue-brand);
            font-weight: 600;
        }

        .nav-link.active svg {
            color: var(--blue-brand);
        }

        .stat-card {
            background: #fff;
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            border: 1px solid #E2E8F0;
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .stat-card:hover {
            box-shadow: 0 8px 24px -4px rgba(37, 99, 235, 0.10);
            transform: translateY(-2px);
        }

        .num {
            font-family: 'DM Mono', monospace;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            animation: fadeUp 0.4s ease both;
        }

        .delay-1 {
            animation-delay: 0.05s;
        }

        .delay-2 {
            animation-delay: 0.10s;
        }

        .delay-3 {
            animation-delay: 0.15s;
        }

        .delay-4 {
            animation-delay: 0.20s;
        }

        .delay-5 {
            animation-delay: 0.25s;
        }

        .delay-6 {
            animation-delay: 0.30s;
        }

        #sidebar::-webkit-scrollbar {
            width: 4px;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 2px;
        }

        .badge-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }
    </style>
</head>

<body>

    <!-- Mobile Menu Button -->
    <button id="mobileMenuBtn"
        class="lg:hidden fixed top-3 left-3 z-50 bg-white p-2 rounded-xl shadow-md border border-gray-200 hover:bg-gray-50 transition">
        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Overlay -->
    <div id="mobileOverlay" class="hidden fixed inset-0 bg-black/40 z-30 lg:hidden backdrop-blur-sm"></div>

    <div class="app-shell">

        <!-- ===== SIDEBAR ===== -->
        <aside id="sidebar">

            <!-- Brand -->
            <div class="px-5 py-5 border-b border-gray-100 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-gray-900 tracking-tight">Absenly</span>
                </div>
                <button id="closeSidebarBtn"
                    class="lg:hidden text-gray-400 hover:text-gray-700 p-1 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Nav -->
            <nav class="flex-1 p-3 pt-4">
                <p class="px-3 text-[10px] font-semibold text-gray-400 uppercase tracking-widest mb-2">Menu Utama</p>

                <a href="{{ route('guru.dashboard') }}"
                    class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                    </svg>
                    Dashboard
                </a>

                {{-- <a href="{{ route('guru.absen') }}"
                    class="nav-link {{ request()->routeIs('guru.absen') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                    </svg>
                    Absen
                </a> --}}

                <a href="{{ route('guru.rekap') }}"
                    class="nav-link {{ request()->routeIs('guru.rekap') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    Rekap Absensi Siswa
                </a>

                <div class="my-3 border-t border-gray-100"></div>
                <p class="px-3 text-[10px] font-semibold text-gray-400 uppercase tracking-widest mb-2">Manajemen User
                </p>

                <a href="{{ route('guru.siswa') }}"
                    class="nav-link {{ request()->routeIs('guru.siswa') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                    Data Siswa
                </a>
            </nav>

            <!-- User Profile Footer -->
            <div class="p-3 border-t border-gray-100 shrink-0">
                <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition">
                    <a href="{{ route('profile.edit') }}"
                        class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-xs font-bold shrink-0"
                        title="Profil">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </a>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-gray-800 truncate leading-tight">
                            {{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-danger-button type="submit" title="Keluar" class="!p-1.5 text-red-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </x-danger-button>
                    </form>
                </div>
            </div>

        </aside>

        <!-- ===== MAIN CONTENT ===== -->
        <div class="main-content">

            <!-- Topbar -->
            <header class="topbar">
                <div class="lg:hidden w-10 shrink-0"></div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-sm font-semibold text-gray-800 truncate">@yield('page-title', 'Dashboard')</h1>
                </div>

                {{-- ── Bell Notification ── --}}
                @php
                    $unread = auth()->user()->unreadNotifications->count();
                    $hasRead = auth()->user()->notifications()->whereNotNull('read_at')->exists();
                    $notifs = auth()->user()->notifications()->latest()->take(15)->get();
                @endphp

                <div class="relative" id="notifWrapper">

                    {{-- Tombol bell --}}
                    <button onclick="toggleNotif()" id="notifBtn"
                        class="relative p-2 text-slate-500 hover:text-slate-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if ($unread > 0)
                            <span id="notifBadge"
                                class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[10px]
                                         font-bold rounded-full flex items-center justify-center">
                                {{ $unread > 9 ? '9+' : $unread }}
                            </span>
                        @else
                            <span id="notifBadge"
                                class="hidden absolute top-1 right-1 w-4 h-4 bg-red-500 text-white
                                         text-[10px] font-bold rounded-full flex items-center justify-center">
                            </span>
                        @endif
                    </button>

                    {{-- Dropdown --}}
                    <div id="notifDropdown"
                        class="hidden absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl
                                border border-slate-100 z-50 overflow-hidden">

                        {{-- Header dropdown --}}
                        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
                            <span class="text-sm font-bold text-slate-800">Notifikasi</span>
                            <div class="flex items-center gap-3">
                                @if ($unread > 0)
                                    <form action="{{ route('notifikasi.readAll') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="text-xs text-blue-500 hover:underline font-medium">
                                            Baca semua
                                        </button>
                                    </form>
                                @endif
                                @if ($hasRead)
                                    <button onclick="deleteAllRead()"
                                        class="text-xs text-red-400 hover:text-red-500 hover:underline font-medium">
                                        Hapus dibaca
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- List notifikasi --}}
                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-50" id="notifList">
                            @forelse($notifs as $notif)
                                @php
                                    $data = $notif->data;
                                    $isRead = $notif->read_at !== null;
                                    $tipe = $data['tipe'] ?? 'izin';
                                    $badgeBg =
                                        $tipe === 'sakit' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600';
                                @endphp
                                <div id="notif-{{ $notif->id }}"
                                    class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50
                                            transition-colors {{ $isRead ? 'bg-white' : 'bg-blue-50/40' }}">

                                    {{-- Avatar --}}
                                    <div class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center
                                                flex-shrink-0 text-xs font-bold text-slate-600 cursor-pointer"
                                        onclick="markRead('{{ $notif->id }}')">
                                        {{ strtoupper(substr($data['siswa_nama'] ?? '?', 0, 1)) }}
                                    </div>

                                    {{-- Konten --}}
                                    <div class="flex-1 min-w-0 cursor-pointer"
                                        onclick="markRead('{{ $notif->id }}')">
                                        <div class="flex items-center gap-1.5 mb-0.5">
                                            <span class="text-xs font-bold text-slate-800 truncate">
                                                {{ $data['siswa_nama'] ?? '-' }}
                                            </span>
                                            <span
                                                class="flex-shrink-0 text-[10px] font-bold px-1.5 py-0.5
                                                         rounded-md {{ $badgeBg }}">
                                                {{ strtoupper($tipe) }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 truncate">{{ $data['alasan'] ?? '-' }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">
                                            {{ $data['kelas'] ?? '-' }} ·
                                            {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                        </p>
                                    </div>

                                    {{-- Kanan: dot + tombol hapus --}}
                                    <div class="flex flex-col items-center gap-1.5 flex-shrink-0 pt-0.5">
                                        @if (!$isRead)
                                            <div id="dot-{{ $notif->id }}"
                                                class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                        @endif
                                        <button onclick="deleteNotif('{{ $notif->id }}')"
                                            id="del-{{ $notif->id }}" title="Hapus notifikasi"
                                            class="{{ $isRead ? '' : 'hidden' }} text-slate-300
                                                       hover:text-red-400 transition-colors p-0.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div id="notifEmpty" class="flex flex-col items-center justify-center py-10 gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-200"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <p class="text-xs text-slate-400">Belum ada notifikasi</p>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>
                {{-- ── End Bell Notification ── --}}

            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 md:p-6 overflow-x-hidden">
                @yield('content')
            </main>

        </div>
    </div>

    <script>
        // ── Sidebar mobile ──
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');

        function openSidebar() {
            sidebar.classList.add('open');
            mobileOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            mobileOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        mobileMenuBtn.addEventListener('click', openSidebar);
        closeSidebarBtn.addEventListener('click', closeSidebar);
        mobileOverlay.addEventListener('click', closeSidebar);

        sidebar.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) closeSidebar();
            });
        });

        // ── Notifikasi dropdown ──
        const notifDropdown = document.getElementById('notifDropdown');

        function toggleNotif() {
            notifDropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('notifWrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                notifDropdown.classList.add('hidden');
            }
        });

        // ── Cek & tampilkan empty state jika list kosong ──
        function checkEmptyList() {
            const list = document.getElementById('notifList');
            if (!list) return;

            const items = list.querySelectorAll('[id^="notif-"]');
            if (items.length === 0) {
                const oldEmpty = document.getElementById('notifEmpty');
                if (oldEmpty) oldEmpty.remove();

                list.innerHTML = `
                    <div id="notifEmpty" class="flex flex-col items-center justify-center py-10 gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-200" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <p class="text-xs text-slate-400">Belum ada notifikasi</p>
                    </div>`;
            }
        }

        // ── Mark single notif as read ──
        // ── Mark single notif as read, then remove it ──
        function markRead(id) {
            const el = document.getElementById('notif-' + id);
            if (!el) return;

            fetch(`/notifikasi/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            }).then(res => {
                // Tetap hapus dari DOM meskipun sudah dibaca sebelumnya
                const dot = document.getElementById('dot-' + id);
                if (dot) updateBadge(-1);

                el.style.transition = 'opacity 0.2s, transform 0.2s';
                el.style.opacity = '0';
                el.style.transform = 'translateX(8px)';

                setTimeout(() => {
                    el.remove();
                    checkEmptyList();
                }, 200);
            });
        }

        // ── Hapus satu notifikasi ──
        function deleteNotif(id) {
            fetch(`/notifikasi/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            }).then(res => {
                if (!res.ok) return;

                const el = document.getElementById('notif-' + id);
                if (!el) return;

                el.style.transition = 'opacity 0.2s, transform 0.2s';
                el.style.opacity = '0';
                el.style.transform = 'translateX(8px)';

                setTimeout(() => {
                    el.remove();
                    checkEmptyList();
                }, 200);
            });
        }

        // ── Hapus semua notifikasi yang sudah dibaca ──
        function deleteAllRead() {
            fetch(`/notifikasi/read/all`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            }).then(res => {
                if (!res.ok) return;

                const list = document.getElementById('notifList');
                if (!list) return;

                // Hapus hanya item yang tidak punya dot (sudah dibaca)
                list.querySelectorAll('[id^="notif-"]').forEach(el => {
                    const dot = el.querySelector('[id^="dot-"]');
                    if (!dot) {
                        el.style.transition = 'opacity 0.15s, transform 0.15s';
                        el.style.opacity = '0';
                        el.style.transform = 'translateX(8px)';
                        setTimeout(() => el.remove(), 150);
                    }
                });

                setTimeout(() => checkEmptyList(), 250);
            });
        }

        // ── Helper update badge count ──
        function updateBadge(delta) {
            const badge = document.getElementById('notifBadge');
            if (!badge) return;

            const current = parseInt(badge.textContent) || 0;
            const next = current + delta;

            if (next <= 0) {
                badge.classList.add('hidden');
                badge.textContent = '0';
            } else {
                badge.classList.remove('hidden');
                badge.textContent = next > 9 ? '9+' : next;
            }
        }
    </script>

    @stack('scripts')

</body>

</html>
