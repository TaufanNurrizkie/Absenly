<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SMK ICB Cinta Teknika') — SMK ICB Cinta Teknika</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icb_Logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <div class="w-8 h-8  flex items-center justify-center ">
                        <img src="{{ asset('img/icb_Logo.png') }}" alt="ICB Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="text-lg font-bold text-gray-900 tracking-tight">SMK ICB CT</span>
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

                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.kehadiran') }}"
                    class="nav-link {{ request()->routeIs('admin.kehadiran') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                    </svg>
                    Kehadiran Siswa
                </a>

                <a href="{{ route('admin.absen-pulang') }}"
                    class="nav-link {{ request()->routeIs('admin.absen-pulang') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                            clip-rule="evenodd" />
                    </svg>
                    Absen Pulang
                </a>

                {{-- <a href="javascript:void(0)" class="nav-link opacity-50 cursor-not-allowed">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                    Kehadiran Guru
                    <span class="ml-auto text-[9px] text-gray-300 font-medium">Soon</span>
                </a> --}}

                <div class="my-3 border-t border-gray-100"></div>
                <p class="px-3 text-[10px] font-semibold text-gray-400 uppercase tracking-widest mb-2">Pengaturan</p>

                <a href="{{ route('admin.settings.index') }}"
                    class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                    Pengaturan Absensi
                </a>

                <div class="my-3 border-t border-gray-100"></div>
                <p class="px-3 text-[10px] font-semibold text-gray-400 uppercase tracking-widest mb-2">Informasi</p>

                <a href="{{ route('admin.berita.index') }}"
                    class="nav-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" />
                        <path
                            d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" />
                    </svg>
                    Kelola Berita
                </a>

                <a href="{{ route('admin.jadwal.index') }}"
                    class="nav-link {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                            clip-rule="evenodd" />
                    </svg>
                    Kelola Jadwal
                </a>

                <div class="my-3 border-t border-gray-100"></div>
                <p class="px-3 text-[10px] font-semibold text-gray-400 uppercase tracking-widest mb-2">Manajemen User
                </p>

                <a href="{{ route('admin.users.siswa') }}"
                    class="nav-link {{ request()->routeIs('admin.users.siswa') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                    Data Siswa
                </a>

                <a href="{{ route('admin.users.guru') }}"
                    class="nav-link {{ request()->routeIs('admin.users.guru') ? 'active' : '' }}">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                    Data Guru
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

                        {{-- Header --}}
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

                        {{-- List --}}
                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-50" id="notifList">
                            @forelse($notifs as $notif)
                                @php
                                    $data = $notif->data;
                                    $isRead = $notif->read_at !== null;
                                @endphp
                                <div id="notif-{{ $notif->id }}"
                                    class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 transition-colors cursor-pointer
                                            {{ $isRead ? 'bg-white' : 'bg-blue-50/40' }}"
                                    onclick="markRead('{{ $notif->id }}')">

                                    {{-- Avatar --}}
                                    <div
                                        class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center
                                                flex-shrink-0 text-xs font-bold text-blue-600">
                                        {{ strtoupper(substr($data['siswa_nama'] ?? '?', 0, 1)) }}
                                    </div>

                                    {{-- Konten --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5 mb-0.5">
                                            <span class="text-xs font-bold text-slate-800 truncate">
                                                {{ $data['siswa_nama'] ?? '-' }}
                                            </span>
                                            <span
                                                class="flex-shrink-0 text-[10px] font-bold px-1.5 py-0.5
                                                         rounded-md bg-blue-50 text-blue-600">
                                                JAMKOS
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 truncate">
                                            {{ $data['kelas'] ?? '-' }} · {{ $data['jurusan'] ?? '-' }} melaporkan
                                            kelas kosong
                                        </p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">
                                            {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                        </p>
                                    </div>

                                    {{-- Dot unread --}}
                                    <div class="flex-shrink-0 pt-0.5">
                                        @if (!$isRead)
                                            <div id="dot-{{ $notif->id }}"
                                                class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                        @endif
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
            if (list.querySelectorAll('[id^="notif-"]').length === 0) {
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

        // ── Mark as read → langsung hapus dari DOM & DB ──
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
                if (!res.ok) return;

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

        // ── Hapus semua yang sudah dibaca ──
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

        // ── Update badge count ──
        function updateBadge(delta) {
            const badge = document.getElementById('notifBadge');
            if (!badge) return;
            const current = parseInt(badge.textContent) || 0;
            const next = current + delta;
            if (next <= 0) {
                badge.classList.add('hidden');
                badge.textContent = '';
            } else {
                badge.classList.remove('hidden');
                badge.textContent = next > 9 ? '9+' : next;
            }
        }
    </script>

    @stack('scripts')

</body>

</html>
