@extends('layouts.siswaNav')

@section('content')
    <style>
        .ripple-btn::after {
            content: '';
            position: absolute;
            width: 300%;
            height: 300%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            animation: ripple 2.5s infinite ease-out;
            z-index: 0;
        }

        @keyframes ripple {
            0% {
                transform: translate(-50%, -50%) scale(0);
                opacity: 0.6;
            }

            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0;
            }
        }

        .attendance-card {
            animation: slideUp 0.5s ease forwards;
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .sc-line {
            position: absolute;
            left: 8%;
            right: 8%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #3b82f6, transparent);
            animation: sc-scan 2.5s ease-in-out infinite;
            top: 0;
        }

        @keyframes sc-scan {
            0% {
                top: 10%;
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                top: 90%;
                opacity: 0;
            }
        }

        .face-badge.detected .face-dot {
            animation: dot-pulse 1s infinite;
        }

        @keyframes dot-pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }
        }

        /* Notif dropdown animasi tanpa Alpine */
        #notifDropdown {
            display: none;
            transform-origin: top right;
        }

        #notifDropdown.open {
            display: block;
        }
    </style>

    @php
        $notifUnread = auth()->user()->unreadNotifications()->take(5)->get();
        $notifCount  = auth()->user()->unreadNotifications()->count();
    @endphp

    <div class="min-h-screen bg-slate-50 pb-10">

        {{-- ── Header ── --}}
        <div
            class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-b-[2.5rem] p-6 pt-8 pb-20 relative overflow-hidden shadow-lg">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-indigo-400/20 rounded-full -ml-24 -mb-24 blur-xl"></div>

            <div class="relative z-10 max-w-lg mx-auto">

                {{-- ── Top Row: Greeting + Avatar + Bell ── --}}
                <div class="flex items-start justify-between mb-6">
                    {{-- Greeting & Name --}}
                    <div>
                        @php
                            $hour = now()->format('H');
                            if ($hour >= 5 && $hour < 11) {
                                $greeting = 'Good Morning';
                            } elseif ($hour >= 11 && $hour < 17) {
                                $greeting = 'Good Afternoon';
                            } elseif ($hour >= 17 && $hour < 21) {
                                $greeting = 'Good Evening';
                            } else {
                                $greeting = 'Good Night';
                            }
                        @endphp
                        <p class="text-blue-100 text-sm font-medium tracking-wide">{{ $greeting }}</p>
                        <h1 class="text-2xl font-bold text-white tracking-tight">{{ $user->name }}</h1>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            <p class="text-xs text-blue-200 font-medium">{{ $user->kelas }}</p>
                        </div>
                    </div>

                    {{-- Avatar + Bell (pojok kanan atas) --}}
                    <div class="flex items-center gap-2 flex-shrink-0">

                        {{-- Bell Notifikasi --}}
                        <div class="relative">
                            <button onclick="toggleNotif()"
                                class="relative p-2 bg-white/10 rounded-xl border border-white/20 hover:bg-white/20 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if ($notifCount > 0)
                                    <span
                                        class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">
                                        {{ $notifCount > 9 ? '9+' : $notifCount }}
                                    </span>
                                @endif
                            </button>

                            {{-- Dropdown Notif --}}
                            <div id="notifDropdown"
                                class="absolute right-0 top-12 w-72 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50">
                                <div
                                    class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
                                    <h4 class="text-sm font-bold text-slate-800">Notifikasi Poin</h4>
                                    @if ($notifCount > 0)
                                        <a href="{{ route('siswa.notif.readAll') }}"
                                            class="text-xs text-blue-500 font-semibold hover:underline">
                                            Tandai semua dibaca
                                        </a>
                                    @endif
                                </div>

                                <div class="divide-y divide-slate-50 max-h-72 overflow-y-auto">
                                    @forelse($notifUnread as $notif)
                                        @php
                                            $d      = $notif->data;
                                            $change = $d['point_change'] ?? 0;
                                            $sign   = $change >= 0 ? '+' : '';
                                            $color  = $change > 0
                                                ? 'text-green-600'
                                                : ($change < 0 ? 'text-red-500' : 'text-slate-500');
                                        @endphp
                                        <div class="px-4 py-3 hover:bg-slate-50 transition cursor-default">
                                            <div class="flex items-start gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-xl {{ $change > 0 ? 'bg-green-50' : ($change < 0 ? 'bg-red-50' : 'bg-slate-100') }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                                    <span class="text-base">
                                                        {{ $change > 0 ? '🎉' : ($change < 0 ? '⚠️' : 'ℹ️') }}
                                                    </span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs font-bold text-slate-700">
                                                        {{ $d['title'] ?? 'Update Poin' }}
                                                    </p>
                                                    <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">
                                                        {{ $d['reason'] ?? '-' }}
                                                    </p>
                                                    <div class="flex items-center justify-between mt-1">
                                                        <span class="text-[10px] text-slate-400">
                                                            {{ $notif->created_at->diffForHumans() }}
                                                        </span>
                                                        <span class="text-xs font-bold {{ $color }}">
                                                            {{ $sign }}{{ $change }} poin
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="px-4 py-8 text-center">
                                            <p class="text-xs text-slate-400">Belum ada notifikasi poin.</p>
                                        </div>
                                    @endforelse
                                </div>

                            </div>
                        </div>

                        {{-- Avatar --}}
                        <a href="{{ route('siswa.profile') }}" class="relative block">
                            <img src="{{ asset('img/' . $user->foto) }}" alt="Profile"
                                class="w-11 h-11 rounded-full border-2 border-white/30 object-cover shadow-md hover:scale-105 transition-transform duration-300">
                            <span
                                class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-indigo-600 rounded-full"></span>
                        </a>
                    </div>
                </div>

                {{-- ── Streak + Poin Row ── --}}
                <div class="flex gap-3">
                    {{-- Streak Card --}}
                    <div
                        class="bg-white/10 backdrop-blur-md rounded-2xl p-4 flex items-center gap-3 flex-1 border border-white/20 shadow-xl">
                        <div class="bg-white/20 p-2.5 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-300"
                                viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 23c-3.866 0-7-3.134-7-7 0-2.658 1.833-5.398 4.138-8.066.476-.556 1.162-.934 1.862-.934.702 0 1.389.377 1.862.934C15.166 10.602 17 13.342 17 16c0 3.866-3.134 7-7 7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-blue-100 font-medium uppercase tracking-wider">Streak</p>
                            <p class="text-xl font-bold text-white leading-none">
                                {{ $user->absen_streak }}
                                <span class="text-xs font-normal opacity-80">Hari</span>
                            </p>
                        </div>
                    </div>

                    {{-- Poin Card --}}
                    <div
                        class="bg-white/10 backdrop-blur-md rounded-2xl p-4 flex items-center gap-3 flex-1 border border-white/20 shadow-xl">
                        <div class="bg-white/20 p-2.5 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-300"
                                viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] text-blue-100 font-medium uppercase tracking-wider">Poin</p>
                            <p class="text-xl font-bold text-white leading-none">
                                {{ number_format($user->Point) }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Main Action Button ── --}}
        <div class="px-6 -mt-12 relative z-20 flex justify-center mb-8">
            <button onclick="startAbsensi()" class="relative group">
                <div
                    class="absolute inset-0 bg-blue-400 rounded-full blur-xl opacity-50 group-hover:opacity-80 transition-opacity duration-300 animate-pulse">
                </div>
                <div
                    class="relative ripple-btn w-40 h-40 bg-white rounded-full shadow-2xl flex flex-col items-center justify-center border-4 border-blue-50 hover:border-blue-200 transition-all duration-300 hover:scale-105 active:scale-95 overflow-hidden">
                    <div class="bg-blue-600 rounded-full p-4 mb-2 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-blue-900 font-bold text-sm tracking-wide">CHECK IN</span>
                    <span id="clock" class="text-xs text-slate-500 font-medium mt-1"></span>
                </div>
            </button>
        </div>

        {{-- ── Date Display ── --}}
        <div class="text-center mb-8 px-6">
            <div
                class="inline-flex items-center gap-2 bg-white px-5 py-2 rounded-full shadow-sm border border-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span
                    class="text-sm font-semibold text-slate-700">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</span>
            </div>
        </div>

        {{-- ── Menu Grid ── --}}
        <div class="px-6 mb-8">
            <div class="grid grid-cols-4 gap-4">

                {{-- Jamkos --}}
                <div onclick="openJamkosModal()"
                    class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col items-center hover:shadow-md hover:border-blue-200 transition-all duration-300 cursor-pointer">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-600">Jamkos</p>
                </div>

                {{-- Permit --}}
                <div onclick="document.getElementById('izinModal').classList.remove('hidden')"
                    class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col items-center hover:shadow-md hover:border-orange-200 transition-all duration-300 cursor-pointer">
                    <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-600">Permit</p>
                </div>

                {{-- Sick --}}
                <div onclick="document.getElementById('sakitModal').classList.remove('hidden')"
                    class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col items-center hover:shadow-md hover:border-red-200 transition-all duration-300 cursor-pointer">
                    <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-600">Sick</p>
                </div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full">
                        <div
                            class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col items-center hover:shadow-md hover:border-slate-200 transition-all duration-300">
                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </div>
                            <p class="text-xs font-semibold text-slate-600">Logout</p>
                        </div>
                    </button>
                </form>

            </div>
        </div>

        {{-- ── Statistik Kehadiran ── --}}
        <div class="px-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg text-slate-800">Statistik Kehadiran</h3>
                <span class="text-xs text-blue-500 font-semibold">Bulan Ini</span>
            </div>

            <div class="grid grid-cols-4 gap-3 mb-4">
                @php
                    $statItems = [
                        [
                            'label' => 'Hadir',
                            'val'   => $statsKehadiran['hadir'],
                            'bg'    => 'bg-green-50',
                            'ic'    => 'text-green-500',
                            'path'  => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        ],
                        [
                            'label' => 'Izin',
                            'val'   => $statsKehadiran['izin'],
                            'bg'    => 'bg-amber-50',
                            'ic'    => 'text-amber-500',
                            'path'  => 'M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z',
                        ],
                        [
                            'label' => 'Sakit',
                            'val'   => $statsKehadiran['sakit'],
                            'bg'    => 'bg-red-50',
                            'ic'    => 'text-red-500',
                            'path'  => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                        ],
                        [
                            'label' => 'Alpha',
                            'val'   => $statsKehadiran['alpha'],
                            'bg'    => 'bg-slate-100',
                            'ic'    => 'text-slate-400',
                            'path'  => 'M6 18L18 6M6 6l12 12',
                        ],
                    ];
                    $total    = array_sum($statsKehadiran) ?: 1;
                    $hadirPct = round(($statsKehadiran['hadir'] / $total) * 100);
                @endphp

                @foreach ($statItems as $s)
                    <div
                        class="bg-white rounded-2xl p-3 shadow-sm border border-slate-100 flex flex-col items-center gap-1">
                        <div class="w-8 h-8 {{ $s['bg'] }} rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 {{ $s['ic'] }}" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['path'] }}" />
                            </svg>
                        </div>
                        <p class="text-lg font-bold text-slate-800 leading-none">{{ $s['val'] }}</p>
                        <p class="text-[10px] text-slate-500 font-medium">{{ $s['label'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Donut Chart --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <div class="flex items-center gap-5">
                    <div class="relative w-28 h-28 flex-shrink-0">
                        <canvas id="donutChart" width="112" height="112"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-xl font-bold text-slate-800">{{ $hadirPct }}%</span>
                            <span class="text-[10px] text-slate-400">Hadir</span>
                        </div>
                    </div>
                    <div class="flex-1 space-y-2.5">
                        @php
                            $legends = [
                                ['label' => 'Hadir', 'val' => $statsKehadiran['hadir'], 'color' => '#22c55e'],
                                ['label' => 'Izin',  'val' => $statsKehadiran['izin'],  'color' => '#f59e0b'],
                                ['label' => 'Sakit', 'val' => $statsKehadiran['sakit'], 'color' => '#ef4444'],
                                ['label' => 'Alpha', 'val' => $statsKehadiran['alpha'], 'color' => '#94a3b8'],
                            ];
                        @endphp
                        @foreach ($legends as $l)
                            @php $pct = round(($l['val'] / $total) * 100); @endphp
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-sm flex-shrink-0"
                                    style="background:{{ $l['color'] }}"></div>
                                <span class="text-xs text-slate-500 w-10">{{ $l['label'] }}</span>
                                <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full"
                                        style="width:{{ $pct }}%;background:{{ $l['color'] }}"></div>
                                </div>
                                <span
                                    class="text-xs font-semibold text-slate-700 w-5 text-right">{{ $l['val'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Berita Sekolah ── --}}
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4 px-6">
                <h3 class="font-bold text-lg text-slate-800">Berita Sekolah</h3>
                <a href="{{ route('siswa.berita') }}" class="text-xs text-blue-500 font-semibold">Lihat Semua</a>
            </div>

            <div class="flex gap-3 overflow-x-auto px-6 pb-2 scroll-smooth snap-x snap-mandatory"
                style="-webkit-overflow-scrolling:touch;scrollbar-width:none;">
                @forelse($beritaDashboard as $berita)
                    <div
                        class="flex-shrink-0 w-52 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden snap-start">
                        <div class="relative w-full h-28 bg-slate-100 overflow-hidden">
                            @if ($berita->gambar)
                                <img src="{{ asset('img/' . $berita->gambar) }}" alt="{{ $berita->judul }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-9 h-9 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div
                                class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-2 py-0.5 rounded-md text-[10px] font-bold text-blue-600">
                                {{ $berita->created_at->format('d M') }}
                            </div>
                        </div>
                        <div class="p-3">
                            <h4 class="text-xs font-bold text-slate-800 leading-snug mb-1"
                                style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                {{ $berita->judul }}
                            </h4>
                            <p class="text-[10px] text-slate-400 mb-2">{{ $berita->penulis }}</p>
                            <a href="{{ route('siswa.berita') }}"
                                class="block w-full text-center bg-blue-50 hover:bg-blue-100 text-blue-600 font-semibold text-[11px] py-1.5 rounded-lg transition-colors">
                                Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-slate-400 text-sm w-full">Belum ada berita.</div>
                @endforelse
            </div>
        </div>

        {{-- ── Jadwal Sekolah ── --}}
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4 px-6">
                <h3 class="font-bold text-lg text-slate-800">Jadwal Sekolah</h3>
                <span class="text-xs text-slate-400 font-medium">{{ $jadwals->count() }} jadwal</span>
            </div>

            @if ($jadwals->isEmpty())
                <div class="mx-6 text-center py-10 bg-white rounded-2xl border border-dashed border-slate-200">
                    <div class="bg-slate-50 rounded-full w-14 h-14 flex items-center justify-center mx-auto mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-slate-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-600">Belum Ada Jadwal</p>
                    <p class="text-xs text-slate-400 mt-1">Jadwal akan muncul di sini.</p>
                </div>
            @else
                <div class="flex gap-3 overflow-x-auto px-6 pb-2 snap-x snap-mandatory"
                    style="-webkit-overflow-scrolling:touch;scrollbar-width:none;">
                    @foreach ($jadwals as $jadwal)
                        @php
                            $ext     = strtolower(pathinfo($jadwal->gambar, PATHINFO_EXTENSION));
                            $tipe    = match ($ext) {
                                'pdf'        => 'pdf',
                                'xlsx','xls' => 'excel',
                                default      => 'gambar',
                            };
                            $fileUrl = asset('storage/' . $jadwal->gambar);
                            $badge   = match ($tipe) {
                                'pdf'   => ['bg' => 'bg-red-50',   'text' => 'text-red-500',   'label' => 'PDF'],
                                'excel' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'label' => 'Excel'],
                                default => ['bg' => 'bg-blue-50',  'text' => 'text-blue-600',  'label' => 'Gambar'],
                            };
                        @endphp

                        <div
                            class="flex-shrink-0 w-72 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden snap-start transition-all duration-300 hover:shadow-md hover:-translate-y-0.5">
                            <div class="flex items-center justify-between px-3 py-2.5 border-b border-slate-50">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div
                                        class="w-7 h-7 {{ $badge['bg'] }} rounded-lg flex items-center justify-center flex-shrink-0">
                                        @if ($tipe === 'pdf')
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-3.5 w-3.5 {{ $badge['text'] }}" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        @elseif ($tipe === 'excel')
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-3.5 w-3.5 {{ $badge['text'] }}" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 10h18M3 14h18M10 3v18M3 3h18v18H3z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-3.5 w-3.5 {{ $badge['text'] }}" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate">{{ $jadwal->judul }}</p>
                                        <p class="text-[10px] text-slate-400">
                                            {{ $jadwal->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                @if ($tipe === 'excel')
                                    <a href="{{ $fileUrl }}" download
                                        class="flex-shrink-0 ml-2 bg-green-50 hover:bg-green-100 text-green-600 p-1.5 rounded-lg border border-green-100 transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                @else
                                    <button
                                        onclick="openJadwalModal('{{ $fileUrl }}', '{{ addslashes($jadwal->judul) }}', '{{ $tipe }}')"
                                        class="flex-shrink-0 ml-2 bg-slate-50 hover:bg-blue-50 hover:text-blue-600 text-slate-400 p-1.5 rounded-lg border border-slate-100 hover:border-blue-100 transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            @if ($tipe === 'gambar')
                                <div class="relative bg-slate-50 cursor-zoom-in"
                                    onclick="openJadwalModal('{{ $fileUrl }}', '{{ addslashes($jadwal->judul) }}', 'gambar')">
                                    <img src="{{ $fileUrl }}" alt="{{ $jadwal->judul }}"
                                        class="w-full h-44 object-cover transition-transform duration-300 hover:scale-[1.02]"
                                        loading="lazy">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-200 flex items-end justify-center pb-3">
                                        <span
                                            class="bg-black/50 text-white text-[10px] font-medium px-3 py-1 rounded-full backdrop-blur-sm">Tap
                                            untuk perbesar</span>
                                    </div>
                                </div>
                            @elseif ($tipe === 'pdf')
                                <div class="relative bg-red-50 h-44 flex flex-col items-center justify-center gap-3 cursor-pointer"
                                    onclick="openJadwalModal('{{ $fileUrl }}', '{{ addslashes($jadwal->judul) }}', 'pdf')">
                                    <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-red-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs font-semibold text-red-600">File PDF</p>
                                        <p class="text-[10px] text-red-400 mt-0.5">Tap untuk buka</p>
                                    </div>
                                </div>
                            @elseif ($tipe === 'excel')
                                <div class="bg-green-50 h-44 flex flex-col items-center justify-center gap-3">
                                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 10h18M3 14h18M10 3v18M3 3h18v18H3z" />
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xs font-semibold text-green-700">File Excel</p>
                                        <p class="text-[10px] text-green-500 mt-0.5">Tidak bisa dipreview</p>
                                    </div>
                                    <a href="{{ $fileUrl }}" download
                                        class="flex items-center gap-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold px-4 py-2 rounded-xl transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Unduh Excel
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if ($jadwals->count() > 1)
                    <div class="flex justify-center gap-1.5 mt-3">
                        @foreach ($jadwals as $jadwal)
                            <div
                                class="h-1.5 rounded-full transition-all duration-300 {{ $loop->first ? 'w-3 bg-blue-500' : 'w-1.5 bg-slate-300' }}">
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

        {{-- ── Leaderboard Kelas ── --}}
        @php
            $leaderboard = \App\Models\User::where('usertype', 'siswa')
                ->where('kelas',   $user->kelas)
                ->where('jurusan', $user->jurusan)
                ->orderByDesc('Point')
                ->orderByDesc('absen_streak')
                ->limit(10)
                ->get(['id','name','foto','kelas','Point','absen_streak']);

            $myLbRank   = $leaderboard->search(fn($u) => $u->id === $user->id);
            $myLbRank   = $myLbRank !== false ? $myLbRank + 1 : null;
            $medalEmoji = ['🥇','🥈','🥉'];

            $podiumOrder = [
                $leaderboard->get(1),  // kiri  → rank 2
                $leaderboard->get(0),  // tengah → rank 1
                $leaderboard->get(2),  // kanan  → rank 3
            ];
            $podiumH    = ['h-20', 'h-28', 'h-16'];
            $podiumBg   = [
                'bg-gradient-to-t from-slate-300  to-slate-200',
                'bg-gradient-to-t from-amber-400  to-yellow-300',
                'bg-gradient-to-t from-amber-700  to-amber-500',
            ];
            $podiumRank = [2, 1, 3];
            $ringClass  = [
                'border-slate-300',
                'border-yellow-400 ring-2 ring-yellow-300/60',
                'border-amber-500',
            ];
        @endphp

        <div class="px-6 mb-8">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg text-slate-800">Leaderboard Kelas</h3>
                @if($myLbRank)
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                        Kamu #{{ $myLbRank }}
                    </span>
                @endif
            </div>

            {{-- Podium top-3 --}}
            @if($leaderboard->count() >= 1)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-4 pt-5 pb-0 mb-3 overflow-hidden">
                <div class="flex items-end justify-center gap-4">
                    @foreach($podiumOrder as $pi => $p)
                    <div class="flex flex-col items-center flex-1">
                        @if($p)
                            @php $isPodiumMe = $p->id === $user->id; @endphp
                            <div class="relative mb-1.5">
                                <img src="{{ asset('img/' . $p->foto) }}" alt="{{ $p->name }}"
                                     class="rounded-full object-cover border-[3px] shadow-md {{ $pi === 1 ? 'w-14 h-14' : 'w-12 h-12' }} {{ $ringClass[$pi] }}">
                                <span class="absolute -bottom-1 -right-0.5 text-sm leading-none">
                                    {{ $medalEmoji[$podiumRank[$pi] - 1] ?? '' }}
                                </span>
                            </div>
                            <p class="text-[11px] font-bold text-slate-700 truncate max-w-[72px] text-center leading-tight">
                                {{ $isPodiumMe ? 'Kamu' : \Str::words($p->name, 1, '') }}
                            </p>
                            <p class="text-[10px] font-semibold text-slate-500 mb-2">{{ number_format($p->Point) }}</p>
                        @else
                            <div class="w-12 h-12 rounded-full bg-slate-100 border-2 border-dashed border-slate-200 mb-1.5 flex items-center justify-center">
                                <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <p class="text-[10px] text-slate-300 mb-2">—</p>
                            <p class="text-[10px] text-slate-200 mb-2">0</p>
                        @endif
                        <div class="{{ $podiumH[$pi] }} {{ $podiumBg[$pi] }} w-full rounded-t-lg flex items-center justify-center">
                            <span class="text-white font-black text-sm opacity-70">#{{ $podiumRank[$pi] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Rank list --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm divide-y divide-slate-50 overflow-hidden">
                @forelse($leaderboard as $li => $luser)
                @php
                    $lrank   = $li + 1;
                    $isLbMe  = $luser->id === $user->id;
                @endphp
                <div class="flex items-center gap-3 px-4 py-3 transition-colors {{ $isLbMe ? 'bg-blue-50' : 'hover:bg-slate-50' }}">
                    {{-- rank / medal --}}
                    <div class="w-7 flex-shrink-0 text-center">
                        @if($lrank <= 3)
                            <span class="text-base leading-none">{{ $medalEmoji[$lrank - 1] }}</span>
                        @else
                            <span class="text-xs font-bold {{ $isLbMe ? 'text-blue-500' : 'text-slate-400' }}">#{{ $lrank }}</span>
                        @endif
                    </div>

                    {{-- avatar --}}
                    <img src="{{ asset('img/' . $luser->foto) }}" alt="{{ $luser->name }}"
                         class="w-9 h-9 rounded-full object-cover border-2 flex-shrink-0 {{ $isLbMe ? 'border-blue-400' : 'border-slate-100' }}">

                    {{-- name + streak --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate leading-tight">
                            {{ $luser->name }}
                            @if($isLbMe)
                                <span class="text-blue-500 text-[10px] font-bold ml-1">● kamu</span>
                            @endif
                        </p>
                        <p class="text-[10px] text-slate-400 font-medium">
                            🔥 {{ $luser->absen_streak ?? 0 }} hari streak
                        </p>
                    </div>

                    {{-- points --}}
                    <div class="flex-shrink-0 text-right">
                        <p class="text-sm font-black {{ $isLbMe ? 'text-blue-600' : 'text-slate-700' }}">
                            {{ number_format($luser->Point) }}
                        </p>
                        <p class="text-[10px] text-slate-400">pts</p>
                    </div>
                </div>
                @empty
                <div class="px-4 py-10 text-center">
                    <p class="text-2xl mb-2">🏆</p>
                    <p class="text-sm text-slate-500 font-medium">Belum ada data leaderboard.</p>
                    <p class="text-xs text-slate-400 mt-1">Mulai absen untuk masuk peringkat!</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- ── Recent Activity ── --}}
        <div class="px-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg text-slate-800">Recent Activity</h3>
            </div>

            <div class="space-y-4">
                @forelse ($absensis as $absen)
                    @php
                        $keterangan = strtolower($absen->keterangan ?? '');
                        $status     = strtolower($absen->status ?? 'pending');
                        $waktu      = \Carbon\Carbon::parse($absen->waktu);
                        $tanggal    = \Carbon\Carbon::parse($absen->created_at);
                        $isLate     = $waktu->hour >= 7;

                        $borderAccent = 'border-l-slate-200';
                        $badgeClass   = 'bg-slate-100 text-slate-700';
                        switch ($keterangan) {
                            case 'hadir':
                                $borderAccent = 'border-l-green-500';
                                $badgeClass   = 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20';
                                break;
                            case 'izin':
                                $borderAccent = 'border-l-amber-500';
                                $badgeClass   = 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20';
                                break;
                            case 'sakit':
                                $borderAccent = 'border-l-red-500';
                                $badgeClass   = 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20';
                                break;
                            case 'alpha':
                            case 'alpa':
                                $borderAccent = 'border-l-gray-500';
                                $badgeClass   = 'bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20';
                                break;
                        }

                        $imgSrc = in_array($keterangan, ['sakit', 'izin', 'alpha', 'alpa'])
                            ? match ($keterangan) {
                                'sakit' => asset('default-sakit.png'),
                                'izin'  => asset('default-izin.png'),
                                default => asset('default-alpha.png'),
                            }
                            : ($absen->foto
                                ? asset('storage/' . $absen->foto)
                                : asset('img/default-photo.png'));

                        switch ($status) {
                            case 'approved':
                                $statusClass = 'bg-green-50 text-green-600 ring-1 ring-inset ring-green-500/20';
                                $statusLabel = 'Approved';
                                break;
                            case 'rejected':
                                $statusClass = 'bg-red-50 text-red-600 ring-1 ring-inset ring-red-500/20';
                                $statusLabel = 'Rejected';
                                break;
                            default:
                                $statusClass = 'bg-amber-50 text-amber-600 ring-1 ring-inset ring-amber-500/20';
                                $statusLabel = 'Pending';
                                break;
                        }
                    @endphp

                    <div
                        class="attendance-card bg-white rounded-2xl shadow-sm border border-slate-100 border-l-4 {{ $borderAccent }} p-5 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="flex items-center gap-4 sm:gap-6">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-20 h-20 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-inner">
                                    <img src="{{ $imgSrc }}" alt="Attendance Photo"
                                        class="w-full h-full object-cover">
                                </div>
                            </div>
                            <div class="flex-1 min-w-0 space-y-3">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div>
                                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">
                                            {{ $tanggal->translatedFormat('l') }}</p>
                                        <h3 class="text-md font-bold text-slate-800">
                                            {{ $tanggal->translatedFormat('d M Y') }}</h3>
                                    </div>
                                    <span
                                        class="self-start sm:self-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $badgeClass }}">
                                        {{ $keterangan }}
                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-slate-600">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 rounded-md bg-slate-50 border border-slate-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <span class="font-semibold text-slate-700">{{ $waktu->format('H:i') }}</span>
                                        <span
                                            class="text-xs px-2 py-0.5 rounded-full {{ $isLate ? 'bg-red-50 text-red-500 font-medium' : 'bg-green-50 text-green-500 font-medium' }}">
                                            {{ $isLate ? 'Late' : 'On Time' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 rounded-md bg-slate-50 border border-slate-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs text-slate-500 font-medium truncate max-w-[150px]"
                                            title="{{ $absen->latitude }}, {{ $absen->longitude }}">
                                            {{ round($absen->latitude, 4) }}, {{ round($absen->longitude, 4) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-xs text-slate-400">Status Approval:</span>
                                    <div
                                        class="flex items-center gap-2 font-bold text-xs uppercase tracking-wide {{ $statusClass }} px-3 py-1 rounded-full">
                                        @if ($status === 'approved')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @elseif ($status === 'rejected')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                        {{ $statusLabel }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-200">
                        <div
                            class="bg-slate-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <h3 class="text-slate-700 font-semibold mb-1">Belum Ada Riwayat</h3>
                        <p class="text-sm text-slate-400">Riwayat absensi akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════
     MODALS
══════════════════════════════════════════ --}}

    {{-- Modal: Jamkos --}}
    <div id="jamkosModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden"
        onclick="closeJamkosModal(event)">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 flex flex-col gap-5"
            onclick="event.stopPropagation()">
            <div class="flex items-center justify-center">
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="text-center">
                <h2 class="text-base font-bold text-slate-800 mb-1">Laporkan Jam Kosong</h2>
                <p class="text-sm text-slate-500 leading-relaxed">Notifikasi akan dikirim ke admin bahwa kelas kamu
                    sedang tidak ada guru.</p>
            </div>
            <div class="bg-slate-50 rounded-xl px-4 py-3 flex flex-col gap-1.5 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-400 font-medium">Nama</span>
                    <span class="text-slate-700 font-semibold">{{ auth()->user()->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400 font-medium">Kelas</span>
                    <span class="text-slate-700 font-semibold">{{ auth()->user()->kelas ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400 font-medium">Jurusan</span>
                    <span class="text-slate-700 font-semibold">{{ auth()->user()->jurusan ?? '-' }}</span>
                </div>
            </div>
            <div class="flex gap-3">
                <button onclick="_closeJamkosModal()"
                    class="flex-1 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </button>
                <button onclick="kirimJamkos()" id="jamkosSubmitBtn"
                    class="flex-1 py-2.5 rounded-xl bg-blue-600 text-sm font-semibold text-white hover:bg-blue-700 transition flex items-center justify-center gap-2">
                    <span id="jamkosSubmitLabel">Kirim Notifikasi</span>
                    <svg id="jamkosSpinner" class="hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Modal: Check-in --}}
    <div id="absenModal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden px-4 opacity-0 scale-95 transition-all duration-300">
        <div class="bg-white rounded-3xl p-6 w-full max-w-md relative shadow-2xl">
            <button onclick="closeModal()"
                class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors duration-300 bg-slate-100 rounded-full p-1.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="text-center mb-5">
                <h2 class="text-xl font-bold text-slate-800">Confirm Check In</h2>
                <p class="text-sm text-slate-500">Position your face in the frame</p>
            </div>
            <div class="relative w-full aspect-[4/3] bg-[#0f172a] rounded-2xl overflow-hidden mb-[14px]">
                <video id="video" class="w-full h-full object-cover [transform:scaleX(-1)] block" autoplay
                    playsinline></video>
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[170px] h-[170px] pointer-events-none">
                    <div
                        class="absolute top-0 left-0 w-7 h-7 border-[3px] border-blue-500 border-r-transparent border-b-transparent rounded-tl-md opacity-90">
                    </div>
                    <div
                        class="absolute top-0 right-0 w-7 h-7 border-[3px] border-blue-500 border-l-transparent border-b-transparent rounded-tr-md opacity-90">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-7 h-7 border-[3px] border-blue-500 border-r-transparent border-t-transparent rounded-bl-md opacity-90">
                    </div>
                    <div
                        class="absolute bottom-0 right-0 w-7 h-7 border-[3px] border-blue-500 border-l-transparent border-t-transparent rounded-br-md opacity-90">
                    </div>
                    <div class="sc-line"></div>
                </div>
                <div id="faceBadge"
                    class="face-badge waiting absolute bottom-3 left-1/2 -translate-x-1/2 px-3.5 py-1.5 rounded-full text-[11px] font-bold tracking-[0.4px] flex items-center gap-1.5 backdrop-blur-[10px] whitespace-nowrap transition-all duration-300 bg-slate-700/85 text-slate-200 border border-slate-400/30">
                    <div class="face-dot w-[7px] h-[7px] rounded-full bg-current flex-shrink-0"></div>
                    <span id="faceStatus">Waiting...</span>
                </div>
            </div>
            <div id="map" class="h-32 w-full rounded-xl overflow-hidden border border-slate-100"></div>
            <canvas id="canvas" class="hidden"></canvas>
            <div class="flex flex-col gap-3 mt-6">
                <button id="submitBtn" onclick="captureAndSubmit()"
                    class="w-full bg-blue-600 text-white py-3.5 rounded-xl font-semibold hover:bg-blue-700 transition-all duration-300 shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 disabled:opacity-45 disabled:cursor-not-allowed"
                    disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Capture & Submit
                </button>
                <button onclick="closeModal()"
                    class="w-full bg-slate-100 text-slate-600 py-3 rounded-xl font-semibold hover:bg-slate-200 transition-all duration-300">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    {{-- Modal: Permit (Izin) --}}
    <div id="izinModal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center sm:px-4">
        <div class="bg-white rounded-t-3xl sm:rounded-3xl w-full sm:max-w-md shadow-2xl max-h-[90dvh] flex flex-col">
            <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-slate-100 flex-shrink-0">
                <h2 class="text-lg font-bold text-slate-800">Request Permission</h2>
                <button type="button" onclick="document.getElementById('izinModal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 bg-slate-100 rounded-full p-1.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="overflow-y-auto flex-1 px-6 py-4">
                <form id="izinForm" method="POST" action="{{ route('absensi.izin') }}"
                    enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="tipe" value="izin">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Reason</label>
                        <textarea name="alasan" rows="3"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none transition-all duration-200 text-sm"
                            placeholder="Explain your reason here..." required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Supporting Document <span class="text-slate-400 font-normal">(Optional)</span>
                        </label>
                        <label for="suratIzin"
                            class="relative flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl p-5 text-center hover:border-blue-400 hover:bg-blue-50/30 transition-all cursor-pointer">
                            <input type="file" name="surat" id="suratIzin" accept="image/*,application/pdf"
                                class="hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-slate-400 mb-1.5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p id="izinFileName" class="text-xs text-slate-500 font-medium">Click to upload or drag
                                and drop</p>
                            <p class="text-xs text-slate-400 mt-0.5">PNG, JPG, PDF up to 10MB</p>
                        </label>
                        <div id="previewIzinWrap" class="mt-3 hidden">
                            <div class="relative rounded-xl overflow-hidden border border-slate-100 bg-slate-50">
                                <img id="previewIzinImg" class="w-full max-h-40 object-contain" alt="Preview" />
                                <button type="button" onclick="clearIzinFile()"
                                    class="absolute top-2 right-2 bg-white/80 hover:bg-white text-slate-500 rounded-full p-1 shadow transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div id="previewIzinPdf"
                            class="mt-3 hidden items-center gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 flex-shrink-0"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span id="previewIzinPdfName"
                                class="text-xs text-slate-700 font-medium truncate flex-1"></span>
                            <button type="button" onclick="clearIzinFile()"
                                class="text-slate-400 hover:text-red-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex gap-3 px-6 py-4 border-t border-slate-100 flex-shrink-0">
                <button type="button" onclick="document.getElementById('izinModal').classList.add('hidden')"
                    class="flex-1 px-4 py-3 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold transition-all duration-200 text-sm">
                    Cancel
                </button>
                <button type="button" onclick="submitIzin()"
                    class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg shadow-blue-500/30 text-sm">
                    Submit Request
                </button>
            </div>
        </div>
    </div>

    {{-- Modal: Sick Leave (Sakit) --}}
    <div id="sakitModal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center sm:px-4">
        <div class="bg-white rounded-t-3xl sm:rounded-3xl w-full sm:max-w-md shadow-2xl max-h-[90dvh] flex flex-col">
            <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-slate-100 flex-shrink-0">
                <h2 class="text-lg font-bold text-slate-800">Sick Leave Form</h2>
                <button type="button" onclick="document.getElementById('sakitModal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 bg-slate-100 rounded-full p-1.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="overflow-y-auto flex-1 px-6 py-4">
                <form id="sakitForm" method="POST" action="{{ route('absensi.izin') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tipe" value="sakit">
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Symptoms /
                            Description</label>
                        <textarea name="alasan" rows="3"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-400 focus:border-transparent resize-none transition-all duration-200 text-sm"
                            placeholder="Describe your symptoms or condition..." required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Medical
                            Certificate</label>
                        <label for="suratDokter"
                            class="relative flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-xl p-5 text-center hover:border-red-400 hover:bg-red-50/30 transition-all cursor-pointer">
                            <input type="file" name="surat" id="suratDokter" accept="image/*,application/pdf"
                                class="hidden" required>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-slate-400 mb-1.5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p id="sakitFileName" class="text-xs text-slate-500 font-medium">Click to upload or drag
                                and drop</p>
                            <p class="text-xs text-slate-400 mt-0.5">PNG, JPG, PDF up to 10MB</p>
                        </label>
                        <div id="previewSakitWrap" class="mt-3 hidden">
                            <div class="relative rounded-xl overflow-hidden border border-slate-100 bg-slate-50">
                                <img id="previewSakitImg" class="w-full max-h-40 object-contain" alt="Preview" />
                                <button type="button" onclick="clearSakitFile()"
                                    class="absolute top-2 right-2 bg-white/80 hover:bg-white text-slate-500 rounded-full p-1 shadow transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div id="previewSakitPdf"
                            class="mt-3 hidden items-center gap-3 bg-red-50 border border-red-100 rounded-xl px-4 py-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 flex-shrink-0"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span id="previewSakitPdfName"
                                class="text-xs text-slate-700 font-medium truncate flex-1"></span>
                            <button type="button" onclick="clearSakitFile()"
                                class="text-slate-400 hover:text-red-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex gap-3 px-6 py-4 border-t border-slate-100 flex-shrink-0">
                <button type="button" onclick="document.getElementById('sakitModal').classList.add('hidden')"
                    class="flex-1 px-4 py-3 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold transition-all duration-200 text-sm">
                    Cancel
                </button>
                <button type="button" onclick="confirmIzinSakit()"
                    class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg shadow-red-500/30 text-sm">
                    Submit Sick Leave
                </button>
            </div>
        </div>
    </div>

    {{-- Modal: Jadwal (Gambar & PDF) --}}
    <div id="jadwalModal"
        class="fixed inset-0 bg-black/85 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4"
        onclick="closeJadwalModal()">
        <div class="relative w-full max-w-2xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-3">
                <h3 id="jadwalModalTitle" class="text-white font-bold text-base truncate pr-4"></h3>
                <button onclick="closeJadwalModal()"
                    class="flex-shrink-0 bg-white/10 hover:bg-white/20 text-white rounded-full p-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="jadwalModalGambar" class="hidden bg-white rounded-2xl overflow-hidden shadow-2xl">
                <img id="jadwalModalImg" src="" alt="Jadwal"
                    class="w-full h-auto max-h-[75vh] object-contain">
            </div>
            <div id="jadwalModalPdf" class="hidden bg-white rounded-2xl overflow-hidden shadow-2xl">
                <iframe id="jadwalModalIframe" src="" class="w-full rounded-2xl" style="height:75vh;"
                    frameborder="0"></iframe>
            </div>
            <a id="jadwalDownloadBtn" href="#" download
                class="mt-3 flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Unduh Jadwal
            </a>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════ --}}
    <script src="https://unpkg.com/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>

    <script>
        // ── Clock ──
        function updateClock() {
            document.getElementById('clock').textContent = new Date().toLocaleTimeString('en-US', {
                hour12: false
            });
        }
        updateClock();
        setInterval(updateClock, 1000);

        // ── Donut Chart ──
        new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [
                        {{ $statsKehadiran['hadir'] }},
                        {{ $statsKehadiran['izin'] }},
                        {{ $statsKehadiran['sakit'] }},
                        {{ $statsKehadiran['alpha'] }}
                    ],
                    backgroundColor: ['#22c55e', '#f59e0b', '#ef4444', '#94a3b8'],
                    borderWidth: 0,
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                },
                animation: { duration: 800 }
            }
        });

        // ── Notif Bell (vanilla JS, tanpa Alpine) ──
        const notifDropdown = document.getElementById('notifDropdown');

        function toggleNotif() {
            notifDropdown.classList.toggle('open');
        }

        // Tutup dropdown kalau klik di luar
        document.addEventListener('click', function(e) {
            const bell = e.target.closest('[onclick="toggleNotif()"]');
            const dropdown = e.target.closest('#notifDropdown');
            if (!bell && !dropdown) {
                notifDropdown.classList.remove('open');
            }
        });

        // ── Face API ──
        let faceReady = false;
        let faceDetectionInterval = null;
        let isFaceDetected = false;

        async function loadFaceModel() {
            try {
                await faceapi.nets.tinyFaceDetector.loadFromUri(
                    'https://raw.githubusercontent.com/justadudewhohacks/face-api.js/master/weights');
                faceReady = true;
            } catch (e) {
                console.error('Model load error', e);
            }
        }

        async function detectFace() {
            const video = document.getElementById('video');
            const badge = document.getElementById('faceBadge');
            const statusText = document.getElementById('faceStatus');
            const submitBtn = document.getElementById('submitBtn');

            if (!video || !video.videoWidth) return;
            if (!faceReady) {
                statusText.textContent = 'Loading AI...';
                return;
            }

            try {
                const detection = await faceapi.detectSingleFace(video,
                    new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 }));
                if (detection) {
                    isFaceDetected = true;
                    badge.className =
                        'face-badge detected absolute bottom-3 left-1/2 -translate-x-1/2 px-3.5 py-1.5 rounded-full text-[11px] font-bold tracking-[0.4px] flex items-center gap-1.5 backdrop-blur-[10px] whitespace-nowrap transition-all duration-300 bg-green-500/15 text-green-400 border border-green-500/50';
                    statusText.textContent = '✓ Face Detected';
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                } else {
                    isFaceDetected = false;
                    badge.className =
                        'face-badge not-detected absolute bottom-3 left-1/2 -translate-x-1/2 px-3.5 py-1.5 rounded-full text-[11px] font-bold tracking-[0.4px] flex items-center gap-1.5 backdrop-blur-[10px] whitespace-nowrap transition-all duration-300 bg-red-500/15 text-red-400 border border-red-500/40';
                    statusText.textContent = '✗ No Face';
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.45';
                }
            } catch (error) {
                console.error('Detection error', error);
            }
        }

        loadFaceModel();

        // ── Check-in Modal ──
        const allowedLat = -6.949648486282659;
        const allowedLng = 107.685995;
        const allowedRadius = 10000;
        let map, marker, circle;

        function startAbsensi() {
            const modal = document.getElementById('absenModal');
            const submitBtn = document.getElementById('submitBtn');

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0', 'scale-95');
                modal.classList.add('opacity-100', 'scale-100');
            }, 10);
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.45';

            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                map = L.map('map').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                marker = L.marker([lat, lng]).addTo(map).bindPopup("Your Location").openPopup();
                circle = L.circle([allowedLat, allowedLng], {
                    radius: allowedRadius,
                    color: '#2563eb',
                    fillOpacity: 0.1
                }).addTo(map);

                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: 640, height: 480 } })
                    .then(stream => {
                        const video = document.getElementById('video');
                        video.srcObject = stream;
                        video.onloadedmetadata = () => {
                            video.play();
                            setTimeout(() => {
                                faceDetectionInterval = setInterval(detectFace, 300);
                                detectFace();
                            }, 500);
                        };
                    }).catch(err => Swal.fire('Error', 'Could not access camera: ' + err.message, 'error'));
            }, function(error) {
                Swal.fire('Error', 'Location access denied: ' + error.message, 'error');
            });
        }

        function closeModal() {
            const modal = document.getElementById('absenModal');
            modal.classList.remove('opacity-100', 'scale-100');
            modal.classList.add('opacity-0', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);

            if (faceDetectionInterval) clearInterval(faceDetectionInterval);
            if (map) { map.remove(); map = null; }

            const video = document.getElementById('video');
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(t => t.stop());
                video.srcObject = null;
            }

            isFaceDetected = false;
            const badge = document.getElementById('faceBadge');
            badge.className =
                'face-badge waiting absolute bottom-3 left-1/2 -translate-x-1/2 px-3.5 py-1.5 rounded-full text-[11px] font-bold tracking-[0.4px] flex items-center gap-1.5 backdrop-blur-[10px] whitespace-nowrap transition-all duration-300 bg-slate-700/85 text-slate-200 border border-slate-400/30';
            document.getElementById('faceStatus').textContent = 'Waiting...';
        }

        async function captureAndSubmit() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');

            if (!isFaceDetected) {
                Swal.fire('Warning', 'Please position your face correctly.', 'warning');
                return;
            }

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            const dataURL = canvas.toDataURL('image/png');
            const latlng = marker.getLatLng();
            const distance = map.distance([latlng.lat, latlng.lng], [allowedLat, allowedLng]);

            if (distance > allowedRadius) {
                Swal.fire('Out of Range', `You are ${Math.round(distance)}m away from school.`, 'error');
                return;
            }

            Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            fetch('/siswa/absen', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ photo: dataURL, lat: latlng.lat, lng: latlng.lng })
                })
                .then(async res => {
                    if (!res.ok) throw new Error(await res.text());
                    return res.json();
                })
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Check-in Berhasil!',
                        html: `${data.message || 'Absensi tercatat.'}<br>
                               <span class="text-blue-600 font-bold">+${data.points_earned ?? 0} poin</span>
                               &nbsp;·&nbsp; Total: <strong>${data.points ?? 0} pts</strong>`,
                    });
                    closeModal();
                    setTimeout(() => location.reload(), 2000);
                })
                .catch(async err => {
                    let msg = 'Failed to submit attendance.';
                    try {
                        const parsed = JSON.parse(err.message);
                        msg = parsed.message || parsed.error || JSON.stringify(parsed);
                    } catch (e) {
                        msg = err.message || msg;
                    }
                    Swal.fire('Error', msg, 'error');
                    closeModal();
                });
        }

        // ── Jadwal Modal ──
        function openJadwalModal(src, title, tipe) {
            document.getElementById('jadwalModalTitle').textContent = title;
            document.getElementById('jadwalDownloadBtn').href = src;
            document.getElementById('jadwalModalGambar').classList.add('hidden');
            document.getElementById('jadwalModalPdf').classList.add('hidden');

            if (tipe === 'gambar') {
                document.getElementById('jadwalModalImg').src = src;
                document.getElementById('jadwalModalGambar').classList.remove('hidden');
            } else if (tipe === 'pdf') {
                document.getElementById('jadwalModalIframe').src = src;
                document.getElementById('jadwalModalPdf').classList.remove('hidden');
            }

            document.getElementById('jadwalModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeJadwalModal() {
            document.getElementById('jadwalModal').classList.add('hidden');
            document.getElementById('jadwalModalImg').src = '';
            document.getElementById('jadwalModalIframe').src = '';
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeJadwalModal(); });

        // ── Jamkos Modal ──
        function openJamkosModal() {
            const hour = new Date().getHours();
            if (hour >= 23) {
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak Bisa Lapor',
                    text: 'Laporan jam kosong hanya bisa dikirim sebelum pukul 15:00.',
                    confirmButtonColor: '#2563EB',
                    confirmButtonText: 'Mengerti',
                });
                return;
            }
            document.getElementById('jamkosModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeJamkosModal(e) {
            if (e && e.target !== document.getElementById('jamkosModal')) return;
            _closeJamkosModal();
        }

        function _closeJamkosModal() {
            document.getElementById('jamkosModal').classList.add('hidden');
            document.body.style.overflow = '';
            document.getElementById('jamkosSubmitBtn').disabled = false;
            document.getElementById('jamkosSubmitLabel').textContent = 'Kirim Notifikasi';
            document.getElementById('jamkosSpinner').classList.add('hidden');
        }

        function kirimJamkos() {
            const btn     = document.getElementById('jamkosSubmitBtn');
            const label   = document.getElementById('jamkosSubmitLabel');
            const spinner = document.getElementById('jamkosSpinner');

            btn.disabled = true;
            label.textContent = 'Mengirim...';
            spinner.classList.remove('hidden');

            fetch('{{ route('siswa.jamkos.kirim') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({})
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Server error ' + res.status);
                    return data;
                })
                .then(() => {
                    _closeJamkosModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Notifikasi Terkirim!',
                        text: 'Admin sudah diberitahu bahwa kelasmu sedang jam kosong.',
                        confirmButtonColor: '#2563EB',
                        confirmButtonText: 'Oke',
                    });
                })
                .catch(err => {
                    _closeJamkosModal();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: err.message || 'Terjadi kesalahan, coba lagi.',
                        confirmButtonColor: '#2563EB',
                    });
                });
        }

        // ── File Preview Helpers ──
        function handleFilePreview(input, ids) {
            const file = input.files[0];
            if (!file) return;
            document.getElementById(ids.label).textContent = file.name;
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = ev => {
                    document.getElementById(ids.img).src = ev.target.result;
                    document.getElementById(ids.wrap).classList.remove('hidden');
                    document.getElementById(ids.pdfWrap).classList.add('hidden');
                    document.getElementById(ids.pdfWrap).classList.remove('flex');
                };
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                document.getElementById(ids.wrap).classList.add('hidden');
                document.getElementById(ids.pdfName).textContent = file.name;
                document.getElementById(ids.pdfWrap).classList.remove('hidden');
                document.getElementById(ids.pdfWrap).classList.add('flex');
            }
        }

        document.getElementById('suratIzin').addEventListener('change', function(e) {
            handleFilePreview(e.target, {
                wrap: 'previewIzinWrap', img: 'previewIzinImg',
                pdfWrap: 'previewIzinPdf', pdfName: 'previewIzinPdfName', label: 'izinFileName'
            });
        });

        document.getElementById('suratDokter').addEventListener('change', function(e) {
            handleFilePreview(e.target, {
                wrap: 'previewSakitWrap', img: 'previewSakitImg',
                pdfWrap: 'previewSakitPdf', pdfName: 'previewSakitPdfName', label: 'sakitFileName'
            });
        });

        function clearIzinFile() {
            document.getElementById('suratIzin').value = '';
            document.getElementById('previewIzinWrap').classList.add('hidden');
            document.getElementById('previewIzinPdf').classList.add('hidden');
            document.getElementById('previewIzinPdf').classList.remove('flex');
            document.getElementById('izinFileName').textContent = 'Click to upload or drag and drop';
        }

        function clearSakitFile() {
            document.getElementById('suratDokter').value = '';
            document.getElementById('previewSakitWrap').classList.add('hidden');
            document.getElementById('previewSakitPdf').classList.add('hidden');
            document.getElementById('previewSakitPdf').classList.remove('flex');
            document.getElementById('sakitFileName').textContent = 'Click to upload or drag and drop';
        }

        // ── Submit Izin ──
        function submitIzin() {
            const form   = document.getElementById('izinForm');
            const alasan = form.querySelector('[name="alasan"]').value.trim();

            if (!alasan) {
                Swal.fire({ icon: 'warning', title: 'Oops!', text: 'Please fill in the reason.' });
                return;
            }

            Swal.fire({
                title: 'Submit Permission?',
                text: 'Make sure your reason is correct.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#2563eb'
            }).then(result => {
                if (!result.isConfirmed) return;
                Swal.fire({ title: 'Submitting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: new FormData(form)
                    })
                    .then(async res => {
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Failed.');
                        return data;
                    })
                    .then(data => {
                        document.getElementById('izinModal').classList.add('hidden');
                        form.reset();
                        clearIzinFile();
                        Swal.fire({
                            icon: 'success', title: 'Submitted!',
                            text: data.message || 'Permission request submitted successfully.',
                            timer: 2500, showConfirmButton: false
                        }).then(() => location.reload());
                    })
                    .catch(err => Swal.fire({ icon: 'error', title: 'Failed!', text: err.message || 'Something went wrong.' }));
            });
        }

        // ── Submit Sakit ──
        function confirmIzinSakit() {
            const form   = document.getElementById('sakitForm');
            const alasan = form.querySelector('[name="alasan"]').value.trim();
            const surat  = document.getElementById('suratDokter').files[0];

            if (!alasan) {
                Swal.fire({ icon: 'warning', title: 'Oops!', text: 'Please describe your symptoms.' });
                return;
            }
            if (!surat) {
                Swal.fire({ icon: 'warning', title: 'Oops!', text: 'Please upload a medical certificate.' });
                return;
            }

            Swal.fire({
                title: 'Submit Sick Leave?',
                text: 'Ensure the document is correct.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ef4444'
            }).then(result => {
                if (!result.isConfirmed) return;
                Swal.fire({ title: 'Submitting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: new FormData(form)
                    })
                    .then(async res => {
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Failed.');
                        return data;
                    })
                    .then(data => {
                        document.getElementById('sakitModal').classList.add('hidden');
                        form.reset();
                        clearSakitFile();
                        Swal.fire({
                            icon: 'success', title: 'Submitted!',
                            text: data.message || 'Sick leave submitted successfully.',
                            timer: 2500, showConfirmButton: false
                        }).then(() => location.reload());
                    })
                    .catch(err => Swal.fire({ icon: 'error', title: 'Failed!', text: err.message || 'Something went wrong.' }));
            });
        }
    </script>

@endsection