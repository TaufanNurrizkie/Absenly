@extends('layouts.adminNav')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard Overview')

@section('content')

    @if(isset($hariLiburHariIni) && $hariLiburHariIni)
        <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-center gap-4 fade-up">
            <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-red-800">Hari Libur Nasional: {{ $hariLiburHariIni->nama }}</h3>
                <p class="text-xs text-red-600 mt-0.5">Seluruh kegiatan absensi dinonaktifkan hari ini.</p>
            </div>
        </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">

        <div class="stat-card fade-up d1 col-span-2 lg:col-span-1">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <sveg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
            </div>
            <p class="num text-2xl font-bold text-gray-900">{{ $jmlhsiswa }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total Siswa</p>
        </div>

        <div class="stat-card fade-up d2">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="num text-2xl font-bold text-gray-900">{{ $persenKehadiran }}%</p>
            <p class="text-xs text-gray-500 mt-0.5">Kehadiran Hari Ini</p>
        </div>

        <div class="stat-card fade-up d3">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="num text-2xl font-bold text-gray-900">{{ $jmlTerlambat }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Terlambat</p>
        </div>

        <div class="stat-card fade-up d4">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
            <p class="num text-2xl font-bold text-gray-900">{{ $jmlBelumAbsen }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Tidak Hadir</p>
        </div>

    </div>

    {{-- Chart + Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

        {{-- Chart --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-5 fade-up d5">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-5">
                <div>
                    <h2 class="text-sm font-semibold text-gray-800">Grafik Kehadiran 7 Hari Terakhir</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Jumlah siswa hadir per hari</p>
                </div>
            </div>
            <div class="chart-wrap" style="position:relative; height:240px;">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        {{-- Activity Feed --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5 fade-up d6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-sm font-semibold text-gray-800">Aktivitas Terbaru</h2>
                <span class="text-xs text-gray-400 num">{{ now()->format('H:i') }} WIB</span>
            </div>

            @if($aktivitas->isEmpty())
                <p class="text-xs text-gray-400 text-center py-8">Belum ada aktivitas hari ini</p>
            @else
                <div class="space-y-4">
                    @php
                        $warnaMap = [
                            'blue'   => 'bg-blue-500',
                            'orange' => 'bg-orange-400',
                            'red'    => 'bg-red-400',
                            'emerald'=> 'bg-emerald-500',
                            'yellow' => 'bg-yellow-400',
                            'purple' => 'bg-purple-500',
                            'gray'   => 'bg-gray-400',
                        ];
                    @endphp

                    @foreach($aktivitas as $item)
                        <div class="flex gap-3">
                            <div class="w-2 h-2 rounded-full {{ $warnaMap[$item['warna']] ?? 'bg-gray-400' }} shrink-0 mt-1.5"></div>
                            <div>
                                <p class="text-xs font-medium text-gray-800">{{ $item['pesan'] }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5 num">{{ $item['waktu'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    {{-- Class Summary Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 fade-up d7">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-800">Rekap Kelas Hari Ini</h2>
            <span class="text-xs text-gray-400">{{ $rekapKelas->count() }} kelas</span>
        </div>

        @if($rekapKelas->isEmpty())
            <p class="text-xs text-gray-400 text-center py-10">Belum ada data kelas</p>
        @else

            {{-- Mobile: cards --}}
            <div class="md:hidden divide-y divide-gray-100">
                @foreach($rekapKelas as $k)
                    @php
                        $warna = $k['persen'] >= 90 ? 'emerald' : ($k['persen'] >= 80 ? 'orange' : 'red');
                        $badgeClass = [
                            'emerald' => 'text-emerald-600 bg-emerald-50',
                            'orange'  => 'text-orange-500 bg-orange-50',
                            'red'     => 'text-red-500 bg-red-50',
                        ][$warna];
                    @endphp
                    <div class="px-5 py-3.5 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $k['kelas'] }}</p>
                            <p class="text-xs text-gray-400">{{ $k['hadir'] }} hadir dari {{ $k['total'] }} siswa</p>
                        </div>
                        <div class="text-right">
                            <p class="num text-sm font-semibold text-gray-800">{{ $k['hadir'] }} / {{ $k['total'] }}</p>
                            <span class="text-[11px] font-semibold {{ $badgeClass }} px-2 py-0.5 rounded-lg">{{ $k['persen'] }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Desktop: table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Kelas</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Siswa</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Hadir</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Terlambat</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Tidak Hadir</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">% Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($rekapKelas as $k)
                            @php
                                $warna = $k['persen'] >= 90 ? 'emerald' : ($k['persen'] >= 80 ? 'orange' : 'red');
                                $badgeClass = [
                                    'emerald' => 'text-emerald-700 bg-emerald-50',
                                    'orange'  => 'text-orange-600 bg-orange-50',
                                    'red'     => 'text-red-600 bg-red-50',
                                ][$warna];
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-3 font-semibold text-gray-800">{{ $k['kelas'] }}</td>
                                <td class="px-5 py-3 num text-gray-500">{{ $k['total'] }}</td>
                                <td class="px-5 py-3 num font-medium text-gray-800">{{ $k['hadir'] }}</td>
                                <td class="px-5 py-3 num text-orange-500">{{ $k['terlambat'] }}</td>
                                <td class="px-5 py-3 num text-red-500">{{ $k['tidak_hadir'] }}</td>
                                <td class="px-5 py-3">
                                    <span class="text-xs font-semibold {{ $badgeClass }} px-2 py-1 rounded-lg">
                                        {{ $k['persen'] }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @endif
    </div>

@endsection

@push('scripts')
<script>
    const ctx      = document.getElementById('attendanceChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 240);
    gradient.addColorStop(0, 'rgba(37,99,235,0.18)');
    gradient.addColorStop(1, 'rgba(37,99,235,0)');

    const labels = @json($grafikLabels);
    const data   = @json($grafikData);

    // Hitung min/max yang wajar untuk skala Y
    const maxVal  = Math.max(...data, 1);
    const minVal  = Math.max(0, Math.min(...data) - Math.ceil(maxVal * 0.15));
    const yMax    = maxVal + Math.ceil(maxVal * 0.1);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Kehadiran',
                data,
                borderColor: '#2563EB',
                backgroundColor: gradient,
                borderWidth: 2.5,
                tension: 0.45,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#2563EB',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1E293B',
                    titleColor: '#94A3B8',
                    bodyColor: '#F8FAFC',
                    padding: 12,
                    cornerRadius: 10,
                    displayColors: false,
                    callbacks: {
                        label: ctx => 'Siswa hadir: ' + ctx.parsed.y.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    min: minVal,
                    max: yMax,
                    ticks: {
                        stepSize: Math.ceil((yMax - minVal) / 4),
                        color: '#94A3B8',
                        font: { size: 11, family: 'DM Mono' }
                    },
                    grid: { color: '#F1F5F9', drawBorder: false }
                },
                x: {
                    ticks: { color: '#94A3B8', font: { size: 11 } },
                    grid: { display: false, drawBorder: false }
                }
            }
        }
    });
</script>
@endpush