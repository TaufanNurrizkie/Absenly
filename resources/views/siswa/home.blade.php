@extends('layouts.siswaNav')
@section('content')


    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-900 to-blue-600 rounded-b-3xl" data-aos="fade-down" data-aos-duration="1000">   
      <!-- Motivasi Harian -->
        <div class="bg-gradient-to-r  p-4 sm:p-6 rounded-xl shadow text-center">
            <p class="italic text-base sm:text-lg font-semibold text-white">
                “{{ $motivasi }}”
            </p>
        </div>
    
        <!-- Profil -->
        <div class="flex flex-col items-center text-center" data-aos="zoom-in">
            <img src="{{ asset('img/' . Auth::user()->foto) }}" alt="Profile"
                class="w-24 sm:w-32 h-24 sm:h-32 rounded-full object-cover shadow mb-4 ring-4 ring-indigo-300">
            <p class="text-sm sm:text-base text-white">Selamat datang,</p>
            <h2 class="text-lg sm:text-xl font-bold text-white">{{ Auth::user()->name }}</h2>
            <p class="text-sm sm:text-base text-white">{{ Auth::user()->kelas }}</p>
        </div>
    </div>

<div class="  p-4 sm:p-6 space-y-6 sm:space-y-8">

    <!-- Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
        <!-- Streak -->
        <div class="bg-white rounded-xl shadow p-4 sm:p-6 flex flex-col items-center justify-center text-center transform transition-transform hover:scale-105">
            <div class="text-5xl sm:text-7xl animate-bounce">🔥</div>
            <p class="text-sm sm:text-base text-gray-600 mt-2">Streak Absen</p>
            <p class="text-lg sm:text-xl font-bold text-indigo-700">{{ Auth::user()->absen_streak }} Hari</p>
        </div>

        <!-- Pie Chart -->
        <div class="bg-white rounded-xl shadow p-4 sm:p-6 transform transition-transform hover:scale-105" >
            <h3 class="text-sm sm:text-base font-semibold text-center text-gray-700 mb-4">Ringkasan Kehadiran</h3>
            <div class="w-full overflow-x-auto">
                <canvas id="pieChart" class="mx-auto max-w-[250px] sm:max-w-[300px]"></canvas>
            </div>
        </div>
    </div>

    <!-- Ringkasan Bulanan -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-semibold mb-4 sm:mb-6 text-indigo-700">Ringkasan Bulan Ini</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center text-sm sm:text-base">
            <div>
                <p class="font-bold text-green-600 text-xl sm:text-2xl">{{ $stat['hadir'] }}</p>
                <p>Hadir</p>
            </div>
            <div>
                <p class="font-bold text-yellow-500 text-xl sm:text-2xl">{{ $stat['izin'] }}</p>
                <p>Izin</p>
            </div>
            <div>
                <p class="font-bold text-blue-500 text-xl sm:text-2xl">{{ $stat['sakit'] }}</p>
                <p>Sakit</p>
            </div>
            <div>
                <p class="font-bold text-red-500 text-xl sm:text-2xl">{{ $stat['alpa'] }}</p>
                <p>Alpa</p>
            </div>
        </div>
    </div>

    <!-- Jadwal Hari Ini -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-6" data-aos="fade-up" data-aos-delay="500">
        <h2 class="text-lg sm:text-xl font-semibold mb-4 text-indigo-700">📅 Jadwal Hari Ini</h2>
        <div class="overflow-x-auto">
            <table class="table-auto w-full text-sm sm:text-base text-left">
                <thead>
                    <tr class="bg-indigo-100 text-indigo-700">
                        <th class="px-4 py-2 sm:px-6 sm:py-3">Mata Pelajaran</th>
                        <th class="px-4 py-2 sm:px-6 sm:py-3">Hari</th>
                        <th class="px-4 py-2 sm:px-6 sm:py-3">Waktu</th>
                        <th class="px-4 py-2 sm:px-6 sm:py-3">Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwal as $item)
                        <tr class="border-b  transition-colors cursor-pointer">
                            <td class="px-4 py-2 sm:px-6 sm:py-3 hover:bg-indigo-100">{{ $item->mapel }}</td>
                            <td class="px-4 py-2 sm:px-6 sm:py-3 hover:bg-indigo-100">{{ $item->hari }}</td>
                            <td class="px-4 py-2 sm:px-6 sm:py-3 hover:bg-indigo-100">{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}</td>
                            <td class="px-4 py-2 sm:px-6 sm:py-3 hover:bg-indigo-100">{{ $item->kelas }}</td>
                        </tr>
                    @empty
                        <tr class="hover:bg-indigo-100 transition-colors">
                            <td colspan="4" class="px-4 py-2 sm:px-6 sm:py-3 text-center text-gray-500">Tidak ada jadwal tersedia hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Berita -->
    <div class="bg-white rounded-xl shadow p-4 sm:p-6" >
        <h2 class="text-lg sm:text-xl font-semibold mb-4 text-indigo-700">📰 Berita</h2>
        @forelse ($berita as $item)
            <div class="mb-4 flex flex-col sm:flex-row items-start gap-4 transform transition-transform hover:scale-105">
                <img src="{{ asset('img/' . $item->gambar) }}" alt="Gambar Berita" class="w-full sm:w-48 h-32 object-cover rounded-md shadow">
                <div class="flex-1">
                    <h3 class="font-bold text-lg sm:text-xl text-blue-800">{{ $item->judul }}</h3>
                    <p class="text-sm sm:text-base text-gray-600 mt-2 line-clamp-3">
                        {{ Str::limit(strip_tags($item->konten), 50, '...') }}
                    </p>
                    <a href="" class="text-indigo-600 hover:underline text-sm sm:text-base mt-2 block">Baca Selengkapnya</a>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">Penulis: {{ $item->penulis }}</p>
                    <p class="text-xs sm:text-sm text-gray-600">{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}</p>
                </div>
            </div>
        @empty
            <p class="text-sm sm:text-base text-gray-500">Belum ada berita terbaru.</p>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('pieChart');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Hadir', 'Izin','Sakit', 'Alpa'],
            datasets: [{
                data: [{{ $stat['hadir'] }}, {{ $stat['izin'] }}, {{ $stat['sakit'] }}, {{ $stat['alpa'] }}],
                backgroundColor: ['#34D399', '#FBBF24', '#60A5FA', '#F87171'],
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#4B5563'
                    }
                }
            }
        }
    });
</script>

@endsection
