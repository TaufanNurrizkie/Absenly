@extends('layouts.adminNav')

@section('title', 'Kehadiran Hari Ini')
@section('page-title', 'Kehadiran Hari Ini')

@section('content')

    {{-- Header + Tanggal --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-lg font-bold text-gray-900">Kehadiran Hari Ini</h1>
            <p class="text-xs text-gray-400 mt-0.5" id="tanggalHariIni"></p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs text-gray-400">Auto-refresh tiap 5 detik</span>
            <span id="refreshDot" class="w-2 h-2 rounded-full bg-emerald-400 inline-block animate-pulse"></span>
        </div>
    </div>

    {{-- Summary Badges --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        <div class="bg-white border border-gray-200 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="num text-xl font-bold text-gray-900" id="countHadir">—</p>
                <p class="text-xs text-gray-500">Hadir</p>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-yellow-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="num text-xl font-bold text-gray-900" id="countIzin">—</p>
                <p class="text-xs text-gray-500">Izin</p>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <div>
                <p class="num text-xl font-bold text-gray-900" id="countSakit">—</p>
                <p class="text-xs text-gray-500">Sakit</p>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="num text-xl font-bold text-gray-900" id="countBelum">—</p>
                <p class="text-xs text-gray-500">Belum Absen</p>
            </div>
        </div>
    </div>

    {{-- 4 Kolom Kehadiran --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- HADIR --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100 bg-emerald-50">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <h2 class="text-sm font-semibold text-emerald-700">Hadir</h2>
                <span class="ml-auto num text-xs font-bold text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-full" id="badgeHadir">0</span>
            </div>
            <div id="hadirList" class="p-3 space-y-2 max-h-[480px] overflow-y-auto">
                {{-- skeleton --}}
                <div class="skeleton-item flex items-center gap-3 p-2 rounded-xl bg-gray-50 animate-pulse">
                    <div class="w-10 h-10 rounded-full bg-gray-200 shrink-0"></div>
                    <div class="flex-1 space-y-1.5">
                        <div class="h-3 bg-gray-200 rounded w-3/4"></div>
                        <div class="h-2.5 bg-gray-100 rounded w-1/2"></div>
                    </div>
                </div>
                <div class="skeleton-item flex items-center gap-3 p-2 rounded-xl bg-gray-50 animate-pulse">
                    <div class="w-10 h-10 rounded-full bg-gray-200 shrink-0"></div>
                    <div class="flex-1 space-y-1.5">
                        <div class="h-3 bg-gray-200 rounded w-2/3"></div>
                        <div class="h-2.5 bg-gray-100 rounded w-1/3"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- IZIN --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100 bg-yellow-50">
                <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                <h2 class="text-sm font-semibold text-yellow-700">Izin</h2>
                <span class="ml-auto num text-xs font-bold text-yellow-600 bg-yellow-100 px-2 py-0.5 rounded-full" id="badgeIzin">0</span>
            </div>
            <div id="izinList" class="p-3 space-y-2 max-h-[480px] overflow-y-auto">
                <div class="skeleton-item p-2 rounded-xl bg-gray-50 animate-pulse">
                    <div class="h-3 bg-gray-200 rounded w-3/4 mb-1.5"></div>
                    <div class="h-2.5 bg-gray-100 rounded w-1/2"></div>
                </div>
            </div>
        </div>

        {{-- SAKIT --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100 bg-blue-50">
                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                <h2 class="text-sm font-semibold text-blue-700">Sakit</h2>
                <span class="ml-auto num text-xs font-bold text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full" id="badgeSakit">0</span>
            </div>
            <div id="sakitList" class="p-3 space-y-2 max-h-[480px] overflow-y-auto">
                <div class="skeleton-item p-2 rounded-xl bg-gray-50 animate-pulse">
                    <div class="h-3 bg-gray-200 rounded w-2/3 mb-1.5"></div>
                    <div class="h-2.5 bg-gray-100 rounded w-1/3"></div>
                </div>
            </div>
        </div>

        {{-- BELUM ABSEN / ALFA --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100 bg-red-50">
                <span class="w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>
                <h2 class="text-sm font-semibold text-red-700">Belum Absen</h2>
                <span class="ml-auto num text-xs font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded-full" id="badgeBelum">0</span>
            </div>
            <div id="belumList" class="p-3 space-y-2 max-h-[480px] overflow-y-auto">
                <div class="skeleton-item p-2 rounded-xl bg-gray-50 animate-pulse">
                    <div class="h-3 bg-gray-200 rounded w-3/4 mb-1.5"></div>
                    <div class="h-2.5 bg-gray-100 rounded w-1/2"></div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    // Tanggal hari ini
    const tanggalEl = document.getElementById('tanggalHariIni');
    if (tanggalEl) {
        tanggalEl.textContent = new Date().toLocaleDateString('id-ID', {
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
        });
    }

    async function loadData() {
        try {
            const res = await fetch("{{ route('admin.kehadiran.data') }}");
            const data = await res.json();

            renderHadir(data.hadir);
            renderSimple('izinList', 'izin', data.izin);
            renderSimple('sakitList', 'sakit', data.sakit);
            renderBelum(data.belum);

            // Update summary counters
            document.getElementById('countHadir').textContent  = data.hadir.length;
            document.getElementById('countIzin').textContent   = data.izin.length;
            document.getElementById('countSakit').textContent  = data.sakit.length;
            document.getElementById('countBelum').textContent  = data.belum.length;

            document.getElementById('badgeHadir').textContent  = data.hadir.length;
            document.getElementById('badgeIzin').textContent   = data.izin.length;
            document.getElementById('badgeSakit').textContent  = data.sakit.length;
            document.getElementById('badgeBelum').textContent  = data.belum.length;

        } catch (e) {
            console.error('Gagal memuat data kehadiran:', e);
        }
    }

    function renderHadir(data) {
        const container = document.getElementById('hadirList');
        if (!data.length) {
            container.innerHTML = emptyState('Belum ada yang hadir');
            return;
        }
        container.innerHTML = data.map(item => `
            <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-gray-50 transition">
                <img src="/storage/${item.foto}"
                    onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(item.user?.name ?? 'U')}&background=EFF6FF&color=2563EB&size=80'"
                    class="w-10 h-10 rounded-full object-cover shrink-0 border border-gray-100">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">${item.user?.name ?? '-'}</p>
                    <p class="text-xs text-gray-400 num">${item.waktu ?? ''}</p>
                </div>
                <span class="ml-auto shrink-0 w-2 h-2 rounded-full bg-emerald-400"></span>
            </div>
        `).join('');
    }

    function renderSimple(containerId, type, data) {
        const container = document.getElementById(containerId);
        const colors = {
            izin:  { dot: 'bg-yellow-400', text: 'text-yellow-700', bg: 'bg-yellow-50' },
            sakit: { dot: 'bg-blue-400',   text: 'text-blue-700',   bg: 'bg-blue-50'   },
        };
        const c = colors[type] ?? { dot: 'bg-gray-300', text: 'text-gray-600', bg: 'bg-gray-50' };

        if (!data.length) {
            container.innerHTML = emptyState('Tidak ada data');
            return;
        }
        container.innerHTML = data.map(item => `
            <div class="flex items-center gap-2.5 p-2.5 rounded-xl hover:bg-gray-50 transition">
                <div class="w-8 h-8 rounded-full ${c.bg} flex items-center justify-center shrink-0
                    text-xs font-bold ${c.text}">
                    ${(item.user?.name ?? 'U').charAt(0).toUpperCase()}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">${item.user?.name ?? '-'}</p>
                    ${item.keterangan ? `<p class="text-xs text-gray-400 truncate">${item.keterangan}</p>` : ''}
                </div>
            </div>
        `).join('');
    }

    function renderBelum(data) {
        const container = document.getElementById('belumList');
        if (!data.length) {
            container.innerHTML = `
                <div class="flex flex-col items-center py-6 text-center">
                    <svg class="w-8 h-8 text-emerald-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-gray-400">Semua sudah absen!</p>
                </div>`;
            return;
        }
        container.innerHTML = data.map(user => `
            <div class="flex items-center gap-2.5 p-2.5 rounded-xl hover:bg-gray-50 transition">
                <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center shrink-0
                    text-xs font-bold text-red-500">
                    ${(user.name ?? 'U').charAt(0).toUpperCase()}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">${user.name ?? '-'}</p>
                    ${user.kelas ? `<p class="text-xs text-gray-400">${user.kelas}</p>` : ''}
                </div>
                <span class="ml-auto shrink-0 text-[10px] font-semibold text-red-400 bg-red-50 px-1.5 py-0.5 rounded">Alfa</span>
            </div>
        `).join('');
    }

    function emptyState(msg) {
        return `<p class="text-xs text-gray-400 text-center py-6">${msg}</p>`;
    }

    loadData();
    setInterval(loadData, 5000);
</script>
@endpush