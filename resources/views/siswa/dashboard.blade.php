@extends('layouts.siswaNav')

@section('content')
    {{-- Hanya animasi custom yang tidak tersedia di Tailwind --}}
    <style>
        /* Ripple animation */
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
            0%   { transform: translate(-50%, -50%) scale(0); opacity: 0.6; }
            100% { transform: translate(-50%, -50%) scale(1); opacity: 0; }
        }

        /* Attendance card slide-up */
        .attendance-card {
            animation: slideUp 0.5s ease forwards;
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        /* Scanner line sweep */
        .sc-line {
            position: absolute;
            left: 8%; right: 8%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #3b82f6, transparent);
            animation: sc-scan 2.5s ease-in-out infinite;
            top: 0;
        }

        @keyframes sc-scan {
            0%   { top: 10%; opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { top: 90%; opacity: 0; }
        }

        /* Face badge dot pulse */
        .face-badge.detected .face-dot {
            animation: dot-pulse 1s infinite;
        }

        @keyframes dot-pulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.3; }
        }
    </style>

    <div class="min-h-screen bg-slate-50 pb-10">

        {{-- ── Header Section ── --}}
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-b-[2.5rem] p-6 pt-8 pb-20 relative overflow-hidden shadow-lg">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-indigo-400/20 rounded-full -ml-24 -mb-24 blur-xl"></div>

            <div class="relative z-10 max-w-lg mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        @php
                            $hour = now()->format('H');
                            if ($hour >= 5 && $hour < 11)       $greeting = 'Good Morning';
                            elseif ($hour >= 11 && $hour < 17)  $greeting = 'Good Afternoon';
                            elseif ($hour >= 17 && $hour < 21)  $greeting = 'Good Evening';
                            else                                 $greeting = 'Good Night';
                        @endphp
                        <p class="text-blue-100 text-sm font-medium tracking-wide">{{ $greeting }}</p>
                        <h1 class="text-2xl font-bold text-white tracking-tight">{{ $user->name }}</h1>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            <p class="text-xs text-blue-200 font-medium">{{ $user->kelas }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('siswa.profile') }}" class="relative block">
                            <img src="{{ asset('img/' . $user->foto) }}" alt="Profile"
                                class="w-12 h-12 rounded-full border-2 border-white/30 object-cover shadow-md hover:scale-105 transition-transform duration-300">
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-indigo-600 rounded-full"></span>
                        </a>
                    </div>
                </div>

                {{-- Streak Card --}}
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 flex items-center gap-4 w-full border border-white/20 shadow-xl">
                    <div class="bg-white/20 p-3 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-300" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 23c-3.866 0-7-3.134-7-7 0-2.658 1.833-5.398 4.138-8.066.476-.556 1.162-.934 1.862-.934.702 0 1.389.377 1.862.934C15.166 10.602 17 13.342 17 16c0 3.866-3.134 7-7 7zm0-14.5c-.04 0-.21.07-.36.24C9.54 11.03 8 13.33 8 16c0 2.206 1.794 4 4 4s4-1.794 4-4c0-2.67-1.54-4.97-3.64-7.26-.15-.17-.32-.24-.36-.24z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-blue-100 font-medium uppercase tracking-wider">Check-in Streak</p>
                        <p class="text-2xl font-bold text-white">{{ $user->absen_streak }} <span class="text-sm font-normal opacity-80">Days</span></p>
                    </div>
                    <div class="bg-white/20 px-3 py-1 rounded-full text-xs font-semibold text-white">Active</div>
                </div>
            </div>
        </div>

        {{-- ── Main Action Button ── --}}
        <div class="px-6 -mt-12 relative z-20 flex justify-center mb-8">
            <button onclick="startAbsensi()" class="relative group">
                <div class="absolute inset-0 bg-blue-400 rounded-full blur-xl opacity-50 group-hover:opacity-80 transition-opacity duration-300 animate-pulse"></div>
                <div class="relative ripple-btn w-40 h-40 bg-white rounded-full shadow-2xl flex flex-col items-center justify-center border-4 border-blue-50 hover:border-blue-200 transition-all duration-300 hover:scale-105 active:scale-95 overflow-hidden">
                    <div class="bg-blue-600 rounded-full p-4 mb-2 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-blue-900 font-bold text-sm tracking-wide">CHECK IN</span>
                    <span id="clock" class="text-xs text-slate-500 font-medium mt-1"></span>
                </div>
            </button>
        </div>

        {{-- ── Date Display ── --}}
        <div class="text-center mb-8 px-6">
            <div class="inline-flex items-center gap-2 bg-white px-5 py-2 rounded-full shadow-sm border border-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-sm font-semibold text-slate-700">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</span>
            </div>
        </div>

        {{-- ── Menu Grid ── --}}
        <div class="px-6 mb-8">
            <div class="grid grid-cols-4 gap-4">

                {{-- History --}}
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col items-center hover:shadow-md hover:border-blue-200 transition-all duration-300 cursor-pointer">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-600">History</p>
                </div>

                {{-- Permit --}}
                <div onclick="document.getElementById('izinModal').classList.remove('hidden')"
                    class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col items-center hover:shadow-md hover:border-orange-200 transition-all duration-300 cursor-pointer">
                    <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-600">Permit</p>
                </div>

                {{-- Sick --}}
                <div onclick="document.getElementById('sakitModal').classList.remove('hidden')"
                    class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col items-center hover:shadow-md hover:border-red-200 transition-all duration-300 cursor-pointer">
                    <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-600">Sick</p>
                </div>

                {{-- Logout --}}
                <div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full">
                            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex flex-col items-center hover:shadow-md hover:border-slate-200 transition-all duration-300">
                                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-slate-600">Logout</p>
                            </div>
                        </button>
                    </form>
                </div>

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

                        // Border & badge by keterangan
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

                        // Image source
                        if (in_array($keterangan, ['sakit', 'izin', 'alpha', 'alpa'])) {
                            $imgSrc = match ($keterangan) {
                                'sakit' => asset('default-sakit.png'),
                                'izin'  => asset('default-izin.png'),
                                default => asset('default-alpha.png'),
                            };
                        } else {
                            $imgSrc = $absen->foto ? asset('storage/' . $absen->foto) : asset('img/default-photo.png');
                        }

                        // Approval status
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

                    <div class="attendance-card bg-white rounded-2xl shadow-sm border border-slate-100 border-l-4 {{ $borderAccent }} p-5 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="flex items-center gap-4 sm:gap-6">

                            {{-- Photo --}}
                            <div class="flex-shrink-0">
                                <div class="w-20 h-20 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-inner">
                                    <img src="{{ $imgSrc }}" alt="Attendance Photo" class="w-full h-full object-cover">
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0 space-y-3">

                                {{-- Row 1: Date & Badge --}}
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                    <div>
                                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">
                                            {{ $tanggal->translatedFormat('l') }}
                                        </p>
                                        <h3 class="text-md font-bold text-slate-800">
                                            {{ $tanggal->translatedFormat('d M Y') }}
                                        </h3>
                                    </div>
                                    <span class="self-start sm:self-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $badgeClass }}">
                                        {{ $keterangan }}
                                    </span>
                                </div>

                                {{-- Row 2: Time & Location --}}
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-slate-600">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 rounded-md bg-slate-50 border border-slate-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <span class="font-semibold text-slate-700">{{ $waktu->format('H:i') }}</span>
                                        <span class="text-xs px-2 py-0.5 rounded-full {{ $isLate ? 'bg-red-50 text-red-500 font-medium' : 'bg-green-50 text-green-500 font-medium' }}">
                                            {{ $isLate ? 'Late' : 'On Time' }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 rounded-md bg-slate-50 border border-slate-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs text-slate-500 font-medium truncate max-w-[150px]" title="{{ $absen->latitude }}, {{ $absen->longitude }}">
                                            {{ round($absen->latitude, 4) }}, {{ round($absen->longitude, 4) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Row 3: Approval Status --}}
                                <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-xs text-slate-400">Status Approval:</span>
                                    <div class="flex items-center gap-2 font-bold text-xs uppercase tracking-wide {{ $statusClass }} px-3 py-1 rounded-full">
                                        @if ($status === 'approved')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        @elseif($status === 'rejected')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
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
                        <div class="bg-slate-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
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
         MODAL: Attendance (Check-in)
    ══════════════════════════════════════════ --}}
    <div id="absenModal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden px-4
               opacity-0 scale-95 transition-all duration-300">
        <div class="bg-white rounded-3xl p-6 w-full max-w-md relative shadow-2xl">

            {{-- Close --}}
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

            {{-- Camera --}}
            <div class="relative w-full aspect-[4/3] bg-[#0f172a] rounded-2xl overflow-hidden mb-[14px]">
                <video id="video"
                    class="w-full h-full object-cover [transform:scaleX(-1)] block"
                    autoplay playsinline></video>

                {{-- Scanner corners + line --}}
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[170px] h-[170px] pointer-events-none">
                    {{-- TL --}}
                    <div class="absolute top-0 left-0 w-7 h-7 border-[3px] border-blue-500 border-r-transparent border-b-transparent rounded-tl-md opacity-90"></div>
                    {{-- TR --}}
                    <div class="absolute top-0 right-0 w-7 h-7 border-[3px] border-blue-500 border-l-transparent border-b-transparent rounded-tr-md opacity-90"></div>
                    {{-- BL --}}
                    <div class="absolute bottom-0 left-0 w-7 h-7 border-[3px] border-blue-500 border-r-transparent border-t-transparent rounded-bl-md opacity-90"></div>
                    {{-- BR --}}
                    <div class="absolute bottom-0 right-0 w-7 h-7 border-[3px] border-blue-500 border-l-transparent border-t-transparent rounded-br-md opacity-90"></div>
                    {{-- Scan line --}}
                    <div class="sc-line"></div>
                </div>

                {{-- Face badge --}}
                <div id="faceBadge"
                    class="face-badge waiting absolute bottom-3 left-1/2 -translate-x-1/2 px-3.5 py-1.5 rounded-full text-[11px] font-bold tracking-[0.4px] flex items-center gap-1.5 backdrop-blur-[10px] whitespace-nowrap transition-all duration-300
                           bg-slate-700/85 text-slate-200 border border-slate-400/30">
                    <div class="face-dot w-[7px] h-[7px] rounded-full bg-current flex-shrink-0"></div>
                    <span id="faceStatus">Waiting...</span>
                </div>
            </div>

            {{-- Map --}}
            <div id="map" class="h-32 w-full rounded-xl overflow-hidden border border-slate-100"></div>

            <canvas id="canvas" class="hidden"></canvas>

            {{-- Buttons --}}
            <div class="flex flex-col gap-3 mt-6">
                <button id="submitBtn" onclick="captureAndSubmit()"
                    class="w-full bg-blue-600 text-white py-3.5 rounded-xl font-semibold hover:bg-blue-700 transition-all duration-300 shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 disabled:opacity-45 disabled:cursor-not-allowed"
                    disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
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

    {{-- ══════════════════════════════════════════
         MODAL: Permit (Izin)
    ══════════════════════════════════════════ --}}
    <div id="izinModal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center px-4">
        <div class="bg-white rounded-3xl w-full max-w-md p-6 shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-slate-800">Request Permission</h2>
                <button type="button" onclick="document.getElementById('izinModal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 bg-slate-100 rounded-full p-1.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('absensi.izin') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="tipe" value="izin">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Reason</label>
                    <textarea name="alasan" rows="4"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none transition-all duration-200 text-sm"
                        placeholder="Explain your reason here..." required></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('izinModal').classList.add('hidden')"
                        class="flex-1 px-6 py-3 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold transition-all duration-200 text-sm">Cancel</button>
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg shadow-blue-500/30 text-sm">Submit Request</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         MODAL: Sick Leave (Sakit)
    ══════════════════════════════════════════ --}}
    <div id="sakitModal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center px-4">
        <div class="bg-white rounded-3xl w-full max-w-md p-6 shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-slate-800">Sick Leave Form</h2>
                <button type="button" onclick="document.getElementById('sakitModal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 bg-slate-100 rounded-full p-1.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('absensi.izin') }}" enctype="multipart/form-data"
                onsubmit="return confirmIzinSakit()">
                @csrf
                <input type="hidden" name="tipe" value="sakit">
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Medical Certificate</label>
                    <div class="relative border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-blue-400 transition-colors">
                        <input type="file" name="surat" id="suratDokter" accept="image/*,application/pdf"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="text-xs text-slate-500">Click to upload or drag and drop</p>
                        <p class="text-xs text-slate-400">PNG, JPG, PDF up to 10MB</p>
                    </div>
                    <div id="preview" class="mt-3 hidden">
                        <img id="previewImage" class="w-full rounded-xl border border-slate-100" alt="Preview" />
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('sakitModal').classList.add('hidden')"
                        class="flex-1 px-6 py-3 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl font-semibold transition-all duration-200 text-sm">Cancel</button>
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-semibold transition-all duration-200 shadow-lg shadow-red-500/30 text-sm">Submit Sick Leave</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Scripts ── --}}
    <script src="https://unpkg.com/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
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
            const video     = document.getElementById('video');
            const badge     = document.getElementById('faceBadge');
            const statusText = document.getElementById('faceStatus');
            const submitBtn = document.getElementById('submitBtn');

            if (!video || !video.videoWidth) return;
            if (!faceReady) { statusText.textContent = 'Loading AI...'; return; }

            try {
                const detection = await faceapi.detectSingleFace(
                    video,
                    new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 })
                );

                if (detection) {
                    isFaceDetected = true;
                    badge.className = 'face-badge detected absolute bottom-3 left-1/2 -translate-x-1/2 px-3.5 py-1.5 rounded-full text-[11px] font-bold tracking-[0.4px] flex items-center gap-1.5 backdrop-blur-[10px] whitespace-nowrap transition-all duration-300 bg-green-500/15 text-green-400 border border-green-500/50';
                    statusText.textContent = '✓ Face Detected';
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                } else {
                    isFaceDetected = false;
                    badge.className = 'face-badge not-detected absolute bottom-3 left-1/2 -translate-x-1/2 px-3.5 py-1.5 rounded-full text-[11px] font-bold tracking-[0.4px] flex items-center gap-1.5 backdrop-blur-[10px] whitespace-nowrap transition-all duration-300 bg-red-500/15 text-red-400 border border-red-500/40';
                    statusText.textContent = '✗ No Face';
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.45';
                }
            } catch (error) {
                console.error('Detection error', error);
            }
        }

        loadFaceModel();

        function updateClock() {
            document.getElementById('clock').textContent =
                new Date().toLocaleTimeString('en-US', { hour12: false });
        }
        updateClock();
        setInterval(updateClock, 1000);

        const allowedLat    = -6.949648486282659;
        const allowedLng    = 107.685995;
        const allowedRadius = 10000;
        let map, marker, circle;

        function startAbsensi() {
            const modal     = document.getElementById('absenModal');
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
                    radius: allowedRadius, color: '#2563eb', fillOpacity: 0.1
                }).addTo(map);

                const video = document.getElementById('video');
                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: 640, height: 480 } })
                    .then(stream => {
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
                video.srcObject.getTracks().forEach(track => track.stop());
                video.srcObject = null;
            }

            isFaceDetected = false;
            const badge = document.getElementById('faceBadge');
            badge.className = 'face-badge waiting absolute bottom-3 left-1/2 -translate-x-1/2 px-3.5 py-1.5 rounded-full text-[11px] font-bold tracking-[0.4px] flex items-center gap-1.5 backdrop-blur-[10px] whitespace-nowrap transition-all duration-300 bg-slate-700/85 text-slate-200 border border-slate-400/30';
            document.getElementById('faceStatus').textContent = 'Waiting...';
        }

        async function captureAndSubmit() {
            const video  = document.getElementById('video');
            const canvas = document.getElementById('canvas');

            if (!isFaceDetected) {
                Swal.fire('Warning', 'Please position your face correctly.', 'warning');
                return;
            }

            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            const dataURL = canvas.toDataURL('image/png');
            const latlng  = marker.getLatLng();
            const distance = map.distance([latlng.lat, latlng.lng], [allowedLat, allowedLng]);

            if (distance > allowedRadius) {
                Swal.fire('Out of Range', `You are ${Math.round(distance)}m away from school.`, 'error');
                return;
            }

            Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            fetch('/siswa/absen', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify({ photo: dataURL, lat: latlng.lat, lng: latlng.lng })
            })
            .then(async res => {
                if (!res.ok) { const text = await res.text(); throw new Error(text); }
                return res.json();
            })
            .then(data => {
                Swal.fire('Success', data.message || 'Check-in successful!', 'success');
                closeModal();
                setTimeout(() => location.reload(), 1500);
            })
            .catch(error => {
                console.log(error);
                Swal.fire('Error', 'Failed to submit attendance.', 'error');
            });
        }

        document.getElementById('suratDokter').addEventListener('change', function(e) {
            const file    = e.target.files[0];
            const preview = document.getElementById('preview');
            const img     = document.getElementById('previewImage');
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (event) => { img.src = event.target.result; preview.classList.remove('hidden'); };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        });

        function confirmIzinSakit() {
            Swal.fire({
                title: 'Submit Sick Leave?',
                text: "Ensure the document is correct.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#2563eb'
            }).then((result) => {
                if (result.isConfirmed) document.querySelector('#sakitModal form').submit();
            });
            return false;
        }
    </script>

    @if (session('success'))
        <script>
            Swal.fire({ icon: 'success', title: 'Success', text: '{{ session('success') }}', timer: 2000, showConfirmButton: false });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({ icon: 'error', title: 'Oops', text: '{{ session('error') }}' });
        </script>
    @endif
@endsection