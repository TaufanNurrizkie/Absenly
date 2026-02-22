@extends('layouts.adminNav')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard Overview')

@section('content')

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">

        <div class="stat-card fade-up d1 col-span-2 lg:col-span-1">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
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
                    <h2 class="text-sm font-semibold text-gray-800">Grafik Kehadiran Mingguan</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Jumlah siswa hadir per hari</p>
                </div>
                <div class="flex gap-1.5">
                    <button type="button" class="text-xs px-3 py-1.5 rounded-lg bg-blue-600 text-white font-medium">Mingguan</button>
                    <button type="button" class="text-xs px-3 py-1.5 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 transition">Bulanan</button>
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
                <span class="text-xs text-blue-600 cursor-pointer hover:underline">Lihat semua</span>
            </div>
            <div class="space-y-4">
                <div class="flex gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-500 shrink-0 mt-1.5"></div>
                    <div>
                        <p class="text-xs font-medium text-gray-800">Budi Santoso masuk kelas XII IPA 1</p>
                        <p class="text-[11px] text-gray-400 mt-0.5 num">07:12 WIB</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="w-2 h-2 rounded-full bg-orange-400 shrink-0 mt-1.5"></div>
                    <div>
                        <p class="text-xs font-medium text-gray-800">Ani Rahayu terlambat — XI IPS 2</p>
                        <p class="text-[11px] text-gray-400 mt-0.5 num">07:48 WIB</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="w-2 h-2 rounded-full bg-red-400 shrink-0 mt-1.5"></div>
                    <div>
                        <p class="text-xs font-medium text-gray-800">3 siswa X IPA 2 tidak hadir tanpa ket.</p>
                        <p class="text-[11px] text-gray-400 mt-0.5 num">08:00 WIB</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 shrink-0 mt-1.5"></div>
                    <div>
                        <p class="text-xs font-medium text-gray-800">Laporan XI IPA 1 dikirim ke orang tua</p>
                        <p class="text-[11px] text-gray-400 mt-0.5 num">08:30 WIB</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-500 shrink-0 mt-1.5"></div>
                    <div>
                        <p class="text-xs font-medium text-gray-800">Admin memperbarui data XII IPS 3</p>
                        <p class="text-[11px] text-gray-400 mt-0.5 num">09:05 WIB</p>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="w-2 h-2 rounded-full bg-purple-500 shrink-0 mt-1.5"></div>
                    <div>
                        <p class="text-xs font-medium text-gray-800">Guru baru ditambahkan ke sistem</p>
                        <p class="text-[11px] text-gray-400 mt-0.5 num">10:22 WIB</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Class Summary Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 fade-up d7">
        <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-800">Rekap Kelas Hari Ini</h2>
            <span class="text-xs text-blue-600 cursor-pointer hover:underline font-medium">Lihat detail →</span>
        </div>

        {{-- Mobile: cards --}}
        <div class="md:hidden divide-y divide-gray-100">
            <div class="px-5 py-3.5 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">XII IPA 1</p>
                    <p class="text-xs text-gray-400">Wali: Pak Hendra</p>
                </div>
                <div class="text-right">
                    <p class="num text-sm font-semibold text-gray-800">36 / 38</p>
                    <span class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg">94.7%</span>
                </div>
            </div>
            <div class="px-5 py-3.5 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">XI IPS 2</p>
                    <p class="text-xs text-gray-400">Wali: Bu Sari</p>
                </div>
                <div class="text-right">
                    <p class="num text-sm font-semibold text-gray-800">30 / 36</p>
                    <span class="text-[11px] font-semibold text-orange-500 bg-orange-50 px-2 py-0.5 rounded-lg">83.3%</span>
                </div>
            </div>
            <div class="px-5 py-3.5 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">X IPA 2</p>
                    <p class="text-xs text-gray-400">Wali: Pak Bimo</p>
                </div>
                <div class="text-right">
                    <p class="num text-sm font-semibold text-gray-800">33 / 40</p>
                    <span class="text-[11px] font-semibold text-red-500 bg-red-50 px-2 py-0.5 rounded-lg">82.5%</span>
                </div>
            </div>
        </div>

        {{-- Desktop: table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Kelas</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Wali Kelas</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Hadir</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Terlambat</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Tidak Hadir</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">% Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-semibold text-gray-800">XII IPA 1</td>
                        <td class="px-5 py-3 text-gray-600">Pak Hendra</td>
                        <td class="px-5 py-3 num font-medium text-gray-800">36</td>
                        <td class="px-5 py-3 num text-orange-500">2</td>
                        <td class="px-5 py-3 num text-red-500">0</td>
                        <td class="px-5 py-3"><span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-lg">94.7%</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-semibold text-gray-800">XI IPS 2</td>
                        <td class="px-5 py-3 text-gray-600">Bu Sari</td>
                        <td class="px-5 py-3 num font-medium text-gray-800">30</td>
                        <td class="px-5 py-3 num text-orange-500">4</td>
                        <td class="px-5 py-3 num text-red-500">2</td>
                        <td class="px-5 py-3"><span class="text-xs font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-lg">83.3%</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-semibold text-gray-800">X IPA 2</td>
                        <td class="px-5 py-3 text-gray-600">Pak Bimo</td>
                        <td class="px-5 py-3 num font-medium text-gray-800">33</td>
                        <td class="px-5 py-3 num text-orange-500">3</td>
                        <td class="px-5 py-3 num text-red-500">4</td>
                        <td class="px-5 py-3"><span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-lg">82.5%</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-semibold text-gray-800">XI IPA 3</td>
                        <td class="px-5 py-3 text-gray-600">Bu Dewi</td>
                        <td class="px-5 py-3 num font-medium text-gray-800">38</td>
                        <td class="px-5 py-3 num text-orange-500">1</td>
                        <td class="px-5 py-3 num text-red-500">0</td>
                        <td class="px-5 py-3"><span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-lg">97.4%</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-semibold text-gray-800">XII IPS 3</td>
                        <td class="px-5 py-3 text-gray-600">Pak Agus</td>
                        <td class="px-5 py-3 num font-medium text-gray-800">28</td>
                        <td class="px-5 py-3 num text-orange-500">5</td>
                        <td class="px-5 py-3 num text-red-500">3</td>
                        <td class="px-5 py-3"><span class="text-xs font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-lg">77.8%</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 240);
    gradient.addColorStop(0, 'rgba(37,99,235,0.18)');
    gradient.addColorStop(1, 'rgba(37,99,235,0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            datasets: [{
                label: 'Kehadiran',
                data: [920, 1050, 1100, 980, 1150, 1080],
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
                    min: 800,
                    max: 1200,
                    ticks: { stepSize: 100, color: '#94A3B8', font: { size: 11, family: 'DM Mono' } },
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