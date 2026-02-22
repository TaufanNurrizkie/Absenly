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
            <span class="text-xs text-gray-400">Auto-refresh tiap 10 detik</span>
            <span id="refreshDot" class="w-2 h-2 rounded-full bg-emerald-400 inline-block animate-pulse"></span>
        </div>
    </div>

    {{-- Summary Badges --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        <div class="bg-white border border-gray-200 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="num text-xl font-bold text-gray-900" id="countBelum">—</p>
                <p class="text-xs text-gray-500">Belum Absen</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

        {{-- HADIR (BESAR DI KIRI) --}}
        <div class="xl:col-span-2 bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100 bg-emerald-50">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <h2 class="text-sm font-semibold text-emerald-700">Hadir</h2>
                <span class="ml-auto num text-xs font-bold text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-full"
                    id="badgeHadir">0</span>
            </div>
            <div id="hadirList" class="p-3 space-y-2 max-h-[720px] overflow-y-auto"></div>
        </div>

        {{-- KOLOM KANAN (VERTIKAL) --}}
        <div class="flex flex-col gap-4">

            {{-- IZIN --}}
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100 bg-yellow-50">
                    <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                    <h2 class="text-sm font-semibold text-yellow-700">Izin</h2>
                    <span class="ml-auto num text-xs font-bold text-yellow-600 bg-yellow-100 px-2 py-0.5 rounded-full"
                        id="badgeIzin">0</span>
                </div>
                <div id="izinList" class="p-3 space-y-2 max-h-[220px] overflow-y-auto"></div>
            </div>

            {{-- SAKIT --}}
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100 bg-blue-50">
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                    <h2 class="text-sm font-semibold text-blue-700">Sakit</h2>
                    <span class="ml-auto num text-xs font-bold text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full"
                        id="badgeSakit">0</span>
                </div>
                <div id="sakitList" class="p-3 space-y-2 max-h-[220px] overflow-y-auto"></div>
            </div>

            {{-- BELUM ABSEN --}}
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100 bg-red-50">
                    <span class="w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>
                    <h2 class="text-sm font-semibold text-red-700">Belum Absen</h2>
                    <span class="ml-auto num text-xs font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded-full"
                        id="badgeBelum">0</span>
                </div>
                <div id="belumList" class="p-3 space-y-2 max-h-[220px] overflow-y-auto"></div>
            </div>

        </div>
    </div>

    {{-- FILTER + EXPORT --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-4 mt-6">
        <div class="flex flex-col md:flex-row md:items-center gap-3">
            <div class="flex gap-3 flex-wrap">
                <select id="filterKelas"
                    class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua Kelas</option>
                </select>

                <select id="filterJurusan"
                    class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua Jurusan</option>
                </select>

                <select id="filterStatus"
                    class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Semua Status</option>
                    <option value="hadir">Hadir</option>
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                    <option value="alfa">Belum Absen</option>
                </select>
            </div>

            <button onclick="exportCSV()"
                class="ml-auto bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-emerald-700 transition">
                Export CSV Hari Ini
            </button>
        </div>
    </div>

    {{-- TABEL REKAP --}}
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden mt-4">
        <div class="px-4 py-3 border-b bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-700">Rekap Kehadiran Hari Ini</h2>
            <span class="text-xs text-gray-400" id="rekapInfo">—</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-4 py-2 text-left w-8">#</th>
                        <th class="px-4 py-2 text-left">Nama</th>
                        <th class="px-4 py-2 text-left">Kelas</th>
                        <th class="px-4 py-2 text-left">Jurusan</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Waktu</th>
                        <th class="px-4 py-2 text-left">Keterangan</th>
                    </tr>
                </thead>
                <tbody id="rekapTable" class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400 text-xs">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between gap-2">
            <span class="text-xs text-gray-400" id="paginasiInfo"></span>
            <div class="flex items-center gap-1" id="paginasiButtons"></div>
        </div>
    </div>

    {{-- MODAL PREVIEW FOTO --}}
    <div id="fotoModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
        <div class="relative bg-white rounded-2xl max-w-md w-full mx-4">
            <button onclick="closeFotoModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-xl">✕</button>
            <img id="fotoModalImg" src="" class="w-full rounded-xl object-cover">
        </div>
    </div>

    {{-- MODAL DETAIL --}}
    <div id="detailModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
        <div class="relative bg-white rounded-2xl p-6 max-w-md w-full mx-4">
            <button onclick="closeDetailModal()" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-xl">✕</button>
            <h3 class="text-lg font-bold mb-4">Detail Kehadiran</h3>
            <div id="detailContent" class="space-y-2 text-sm text-gray-600"></div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
// ─── State ───────────────────────────────────────────────
let allData     = [];   // semua record gabungan (hadir+izin+sakit+belum)
let rawData     = {};   // raw dari API
let currentPage = 1;
const PER_PAGE  = 10;

// ─── Tanggal ─────────────────────────────────────────────
const tanggalEl = document.getElementById('tanggalHariIni');
if (tanggalEl) {
    tanggalEl.textContent = new Date().toLocaleDateString('id-ID', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
    });
}

// ─── Load Data ───────────────────────────────────────────
async function loadData() {
    try {
        const res  = await fetch("{{ route('admin.kehadiran.data') }}");
        const data = await res.json();
        rawData    = data;

        renderHadir(data.hadir);
        renderSimple('izinList', 'izin', data.izin);
        renderSimple('sakitList', 'sakit', data.sakit);
        renderBelum(data.belum);

        // Summary counters
        document.getElementById('countHadir').textContent = data.hadir.length;
        document.getElementById('countIzin').textContent  = data.izin.length;
        document.getElementById('countSakit').textContent = data.sakit.length;
        document.getElementById('countBelum').textContent = data.belum.length;

        document.getElementById('badgeHadir').textContent = data.hadir.length;
        document.getElementById('badgeIzin').textContent  = data.izin.length;
        document.getElementById('badgeSakit').textContent = data.sakit.length;
        document.getElementById('badgeBelum').textContent = data.belum.length;

        // Gabungkan semua data untuk tabel
        buildAllData(data);
        populateFilters();
        currentPage = 1;
        renderRekap();

    } catch (e) {
        console.error('Gagal memuat data kehadiran:', e);
    }
}

// ─── Gabungkan semua record ───────────────────────────────
function buildAllData(data) {
    const hadir = (data.hadir ?? []).map(r => ({
        nama     : r.user?.name    ?? '-',
        kelas    : r.user?.kelas   ?? '-',
        jurusan  : r.user?.jurusan ?? '-',
        status   : 'hadir',
        waktu    : r.waktu         ?? '-',
        keterangan: r.keterangan   ?? '',
    }));

    const izin = (data.izin ?? []).map(r => ({
        nama     : r.user?.name    ?? '-',
        kelas    : r.user?.kelas   ?? '-',
        jurusan  : r.user?.jurusan ?? '-',
        status   : 'izin',
        waktu    : r.waktu         ?? '-',
        keterangan: r.keterangan   ?? '',
    }));

    const sakit = (data.sakit ?? []).map(r => ({
        nama     : r.user?.name    ?? '-',
        kelas    : r.user?.kelas   ?? '-',
        jurusan  : r.user?.jurusan ?? '-',
        status   : 'sakit',
        waktu    : r.waktu         ?? '-',
        keterangan: r.keterangan   ?? '',
    }));

    const belum = (data.belum ?? []).map(u => ({
        nama     : u.name    ?? '-',
        kelas    : u.kelas   ?? '-',
        jurusan  : u.jurusan ?? '-',
        status   : 'alfa',
        waktu    : '-',
        keterangan: '',
    }));

    allData = [...hadir, ...izin, ...sakit, ...belum];
}

// ─── Populate filter dropdowns ────────────────────────────
function populateFilters() {
    const kelasSet   = new Set(allData.map(r => r.kelas).filter(v => v && v !== '-'));
    const jurusanSet = new Set(allData.map(r => r.jurusan).filter(v => v && v !== '-'));

    const kelasEl   = document.getElementById('filterKelas');
    const jurusanEl = document.getElementById('filterJurusan');

    // Simpan nilai yang sedang dipilih
    const prevKelas   = kelasEl.value;
    const prevJurusan = jurusanEl.value;

    kelasEl.innerHTML   = '<option value="">Semua Kelas</option>'
        + [...kelasSet].sort().map(k => `<option value="${k}">${k}</option>`).join('');
    jurusanEl.innerHTML = '<option value="">Semua Jurusan</option>'
        + [...jurusanSet].sort().map(j => `<option value="${j}">${j}</option>`).join('');

    kelasEl.value   = prevKelas;
    jurusanEl.value = prevJurusan;
}

// ─── Filter aktif ─────────────────────────────────────────
function getFiltered() {
    const kelas   = document.getElementById('filterKelas').value;
    const jurusan = document.getElementById('filterJurusan').value;
    const status  = document.getElementById('filterStatus').value;

    return allData.filter(r => {
        return (!kelas   || r.kelas   === kelas)
            && (!jurusan || r.jurusan === jurusan)
            && (!status  || r.status  === status);
    });
}

// ─── Render tabel rekap dengan pagination ─────────────────
function renderRekap() {
    const filtered  = getFiltered();
    const total     = filtered.length;
    const totalPage = Math.ceil(total / PER_PAGE) || 1;

    if (currentPage > totalPage) currentPage = totalPage;

    const start  = (currentPage - 1) * PER_PAGE;
    const end    = Math.min(start + PER_PAGE, total);
    const sliced = filtered.slice(start, end);

    const tbody = document.getElementById('rekapTable');

    if (!sliced.length) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-4 py-8 text-center text-gray-400 text-xs">Tidak ada data</td></tr>`;
        document.getElementById('rekapInfo').textContent     = '0 data';
        document.getElementById('paginasiInfo').textContent  = '';
        document.getElementById('paginasiButtons').innerHTML = '';
        return;
    }

    const statusBadge = {
        hadir : '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Hadir</span>',
        izin  : '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Izin</span>',
        sakit : '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Sakit</span>',
        alfa  : '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-500">Alfa</span>',
    };

    tbody.innerHTML = sliced.map((r, i) => `
        <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-2.5 text-gray-400 text-xs">${start + i + 1}</td>
            <td class="px-4 py-2.5 font-medium text-gray-800">${r.nama}</td>
            <td class="px-4 py-2.5 text-gray-500">${r.kelas}</td>
            <td class="px-4 py-2.5 text-gray-500">${r.jurusan}</td>
            <td class="px-4 py-2.5">${statusBadge[r.status] ?? r.status}</td>
            <td class="px-4 py-2.5 text-gray-500 num">${r.waktu}</td>
            <td class="px-4 py-2.5 text-gray-400 text-xs">${r.keterangan || '-'}</td>
        </tr>
    `).join('');

    // Info
    document.getElementById('rekapInfo').textContent    = `${total} data`;
    document.getElementById('paginasiInfo').textContent = `Menampilkan ${start + 1}–${end} dari ${total} data`;

    // Pagination buttons
    renderPaginasi(totalPage);
}

function renderPaginasi(totalPage) {
    const container = document.getElementById('paginasiButtons');
    let html = '';

    // Tombol Prev
    html += `<button onclick="goPage(${currentPage - 1})"
        class="px-2 py-1 text-xs rounded-lg border border-gray-200 ${currentPage === 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-100'}"
        ${currentPage === 1 ? 'disabled' : ''}>‹</button>`;

    // Nomor halaman (dengan ellipsis kalau banyak)
    const pages = getPaginasiRange(currentPage, totalPage);
    pages.forEach(p => {
        if (p === '...') {
            html += `<span class="px-2 py-1 text-xs text-gray-400">…</span>`;
        } else {
            html += `<button onclick="goPage(${p})"
                class="px-2.5 py-1 text-xs rounded-lg border ${p === currentPage
                    ? 'bg-emerald-600 text-white border-emerald-600'
                    : 'border-gray-200 hover:bg-gray-100 text-gray-600'}">${p}</button>`;
        }
    });

    // Tombol Next
    html += `<button onclick="goPage(${currentPage + 1})"
        class="px-2 py-1 text-xs rounded-lg border border-gray-200 ${currentPage === totalPage ? 'opacity-40 cursor-not-allowed' : 'hover:bg-gray-100'}"
        ${currentPage === totalPage ? 'disabled' : ''}>›</button>`;

    container.innerHTML = html;
}

function getPaginasiRange(current, total) {
    if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);

    const pages = [];
    pages.push(1);

    if (current > 3)             pages.push('...');
    for (let i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) {
        pages.push(i);
    }
    if (current < total - 2)    pages.push('...');
    pages.push(total);

    return pages;
}

function goPage(page) {
    const filtered  = getFiltered();
    const totalPage = Math.ceil(filtered.length / PER_PAGE) || 1;
    if (page < 1 || page > totalPage) return;
    currentPage = page;
    renderRekap();
}

// ─── Event listeners filter ───────────────────────────────
document.getElementById('filterKelas').addEventListener('change',   () => { currentPage = 1; renderRekap(); });
document.getElementById('filterJurusan').addEventListener('change', () => { currentPage = 1; renderRekap(); });
document.getElementById('filterStatus').addEventListener('change',  () => { currentPage = 1; renderRekap(); });

// ─── Export CSV ───────────────────────────────────────────
function exportCSV() {
    const filtered = getFiltered();
    const header   = ['No', 'Nama', 'Kelas', 'Jurusan', 'Status', 'Waktu', 'Keterangan'];
    const rows     = filtered.map((r, i) => [
        i + 1, r.nama, r.kelas, r.jurusan, r.status, r.waktu, r.keterangan
    ]);

    const csv  = [header, ...rows].map(r => r.map(v => `"${String(v).replace(/"/g, '""')}"`).join(',')).join('\n');
    const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = `kehadiran_${new Date().toISOString().slice(0, 10)}.csv`;
    a.click();
    URL.revokeObjectURL(url);
}

// ─── Render Hadir ─────────────────────────────────────────
function renderHadir(data) {
    const container = document.getElementById('hadirList');
    if (!data.length) {
        container.innerHTML = emptyState('Belum ada yang hadir');
        return;
    }
    container.innerHTML = data.map(item => `
        <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-gray-50 transition">
            <img src="/storage/${item.foto}"
                 onclick="openFotoModal('/storage/${item.foto}')"
                 onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(item.user?.name ?? 'U')}&background=EFF6FF&color=2563EB&size=80'"
                 class="w-10 h-10 rounded-full object-cover shrink-0 border border-gray-100 cursor-pointer hover:scale-105 transition">
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">${item.user?.name ?? '-'}</p>
                <p class="text-xs text-gray-400">${item.user?.kelas ?? ''} ${item.user?.jurusan ?? ''}</p>
                <p class="text-xs text-gray-400 num">${item.waktu ?? ''}</p>
            </div>
            <button onclick='openDetailModal(${JSON.stringify(item)})'
                    class="ml-auto text-xs px-2 py-1 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition">
                Detail
            </button>
        </div>
    `).join('');
}

// ─── Render Izin / Sakit ──────────────────────────────────
function renderSimple(containerId, type, data) {
    const container = document.getElementById(containerId);
    const colors = {
        izin  : { dot: 'bg-yellow-400', text: 'text-yellow-700', bg: 'bg-yellow-50' },
        sakit : { dot: 'bg-blue-400',   text: 'text-blue-700',   bg: 'bg-blue-50'   },
    };
    const c = colors[type] ?? { dot: 'bg-gray-300', text: 'text-gray-600', bg: 'bg-gray-50' };

    if (!data.length) {
        container.innerHTML = emptyState('Tidak ada data');
        return;
    }
    container.innerHTML = data.map(item => `
        <div class="flex items-center gap-2.5 p-2.5 rounded-xl hover:bg-gray-50 transition">
            <div class="w-8 h-8 rounded-full ${c.bg} flex items-center justify-center shrink-0 text-xs font-bold ${c.text}">
                ${(item.user?.name ?? 'U').charAt(0).toUpperCase()}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-gray-800 truncate">${item.user?.name ?? '-'}</p>
                ${item.keterangan ? `<p class="text-xs text-gray-400 truncate">${item.keterangan}</p>` : ''}
            </div>
            <button onclick='openDetailModal(${JSON.stringify(item)})'
                    class="text-xs px-2 py-1 ${c.bg} ${c.text} rounded-lg hover:opacity-80 transition">
                Detail
            </button>
        </div>
    `).join('');
}

// ─── Render Belum Absen ───────────────────────────────────
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
            <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center shrink-0 text-xs font-bold text-red-500">
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

// ─── Modals ───────────────────────────────────────────────
function openFotoModal(src) {
    document.getElementById('fotoModalImg').src = src;
    document.getElementById('fotoModal').classList.remove('hidden');
    document.getElementById('fotoModal').classList.add('flex');
}
function closeFotoModal() {
    document.getElementById('fotoModal').classList.add('hidden');
    document.getElementById('fotoModal').classList.remove('flex');
}
function openDetailModal(item) {
    document.getElementById('detailContent').innerHTML = `
        <p><strong>Nama:</strong> ${item.user?.name ?? item.nama ?? '-'}</p>
        <p><strong>Kelas:</strong> ${item.user?.kelas ?? item.kelas ?? '-'}</p>
        <p><strong>Jurusan:</strong> ${item.user?.jurusan ?? item.jurusan ?? '-'}</p>
        <p><strong>Status:</strong> ${item.status ?? '-'}</p>
        <p><strong>Waktu:</strong> ${item.waktu ?? '-'}</p>
        ${item.keterangan ? `<p><strong>Keterangan:</strong> ${item.keterangan}</p>` : ''}
    `;
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailModal').classList.add('flex');
}
function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('detailModal').classList.remove('flex');
}

// ─── Init ─────────────────────────────────────────────────
loadData();
setInterval(loadData, 10000);
</script>
@endpush