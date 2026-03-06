@extends('layouts.siswaNav')

@section('content')

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .fade-in-up { animation: fadeInUp 0.6s ease forwards; }
</style>

<!-- Header Section -->
<div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-b-[2.5rem] p-6 pt-8 pb-12 relative overflow-hidden shadow-lg">
    <!-- Decorative Background -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32 blur-2xl"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-indigo-500/20 rounded-full -ml-24 -mb-24 blur-xl"></div>

    <div class="relative z-10">
        <!-- Motivasi Harian -->
        <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl border border-white/20 mb-6 text-center" data-aos="fade-down">
            <p class="text-sm sm:text-base font-medium text-blue-50 leading-relaxed">
                “{{ $motivasi }}”
            </p>
        </div>

        <!-- Profil -->
        <div class="flex flex-col items-center text-center" data-aos="zoom-in">
            <div class="relative mb-4">
                <img src="{{ asset('img/' . Auth::user()->foto) }}" alt="Profile"
                    class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover shadow-xl ring-4 ring-white/30">
                <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-400 border-2 border-white rounded-full shadow-sm"></div>
            </div>
            <p class="text-xs text-blue-200 font-medium">Selamat datback,</p>
            <h2 class="text-xl font-bold tracking-tight">{{ Auth::user()->name }}</h2>
            <p class="text-sm text-blue-100 mt-1 bg-white/10 px-3 py-1 rounded-full">{{ Auth::user()->kelas }}</p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="px-4 -mt-6 relative z-20 space-y-6 pb-6">

    <!-- Top Stats: Streak & Chart -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        
        <!-- Streak Card -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow fade-in-up" style="animation-delay: 0.1s">
            <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-red-500 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-200 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 23c-3.866 0-7-3.134-7-7 0-2.658 1.833-5.398 4.138-8.066.476-.556 1.162-.934 1.862-.934.702 0 1.389.377 1.862.934C15.166 10.602 17 13.342 17 16c0 3.866-3.134 7-7 7zm0-14.5c-.04 0-.21.07-.36.24C9.54 11.03 8 13.33 8 16c0 2.206 1.794 4 4 4s4-1.794 4-4c0-2.67-1.54-4.97-3.64-7.26-.15-.17-.32-.24-.36-.24z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Streak Absen</p>
                <p class="text-2xl font-bold text-slate-800 mt-1">{{ Auth::user()->absen_streak }} <span class="text-sm font-normal text-slate-500">Hari</span></p>
            </div>
        </div>

        <!-- Pie Chart Card -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition-shadow fade-in-up" style="animation-delay: 0.2s">
            <h3 class="text-xs text-slate-500 font-medium uppercase tracking-wider mb-3 text-center">Ringkasan Kehadiran</h3>
            <div class="h-40 flex items-center justify-center">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Ringkasan Bulanan -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 fade-in-up" style="animation-delay: 0.3s">
        <h2 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Ringkasan Bulan Ini
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <!-- Hadir -->
            <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100 hover:border-emerald-200 transition-colors">
                <p class="text-2xl font-bold text-emerald-600">{{ $stat['hadir'] }}</p>
                <p class="text-xs text-slate-500 mt-1">Hadir</p>
            </div>
            <!-- Izin -->
            <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100 hover:border-amber-200 transition-colors">
                <p class="text-2xl font-bold text-amber-500">{{ $stat['izin'] }}</p>
                <p class="text-xs text-slate-500 mt-1">Izin</p>
            </div>
            <!-- Sakit -->
            <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100 hover:border-blue-200 transition-colors">
                <p class="text-2xl font-bold text-blue-500">{{ $stat['sakit'] }}</p>
                <p class="text-xs text-slate-500 mt-1">Sakit</p>
            </div>
            <!-- Alpa -->
            <div class="bg-slate-50 rounded-xl p-3 text-center border border-slate-100 hover:border-red-200 transition-colors">
                <p class="text-2xl font-bold text-red-500">{{ $stat['alpa'] }}</p>
                <p class="text-xs text-slate-500 mt-1">Alpa</p>
            </div>
        </div>
    </div>

    <!-- Jadwal Hari Ini -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden fade-in-up" style="animation-delay: 0.4s">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h2 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Jadwal Hari Ini
            </h2>
            <span class="text-xs text-slate-400 font-medium">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse ($jadwal as $item)
                <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-10 rounded-full bg-indigo-500"></div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">{{ $item->mapel }}</p>
                            <p class="text-xs text-slate-500">{{ $item->kelas }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-slate-700 tabular-nums">{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}</p>
                        <p class="text-xs text-slate-400">{{ $item->hari }}</p>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm text-slate-400 font-medium">Tidak ada jadwal hari ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Berita -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden fade-in-up" style="animation-delay: 0.5s">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h2 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                Berita Terbaru
            </h2>
            <a href="{{ route('siswa.berita') }}" class="text-xs text-blue-600 font-medium hover:underline">Lihat Semua</a>
        </div>

        <div class="p-4 space-y-4">
            @forelse ($berita as $item)
                <div class="flex gap-4 group cursor-pointer" onclick="window.location='{{ route('siswa.berita') }}'">
                    <img src="{{ asset('img/' . $item->gambar) }}" alt="Gambar Berita" class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover shadow-sm shrink-0 group-hover:scale-105 transition-transform">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-slate-800 text-sm line-clamp-1 group-hover:text-blue-600 transition-colors">{{ $item->judul }}</h3>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                            {{ Str::limit(strip_tags($item->konten), 80, '...') }}
                        </p>
                        <div class="flex items-center gap-2 mt-2 text-xs text-slate-400">
                            <span>{{ $item->penulis }}</span>
                            <span>•</span>
                            <span>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-sm text-slate-400">Belum ada berita terbaru.</div>
            @endforelse
        </div>
    </div>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('pieChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut', // Menggunakan doughnut agar lebih modern
            data: {
                labels: ['Hadir', 'Izin', 'Sakit', 'Alpa'],
                datasets: [{
                    data: [{{ $stat['hadir'] }}, {{ $stat['izin'] }}, {{ $stat['sakit'] }}, {{ $stat['alpa'] }}],
                    backgroundColor: [
                        'rgba(52, 211, 153, 0.9)', // Emerald
                        'rgba(251, 191, 36, 0.9)',  // Amber
                        'rgba(96, 165, 250, 0.9)',  // Blue
                        'rgba(248, 113, 113, 0.9)'  // Red
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%', // Membuat lubang di tengah
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#64748b',
                            font: { size: 11, family: 'Plus Jakarta Sans' },
                            usePointStyle: true,
                            boxWidth: 6
                        }
                    }
                }
            }
        });
    }
</script>

@endsection