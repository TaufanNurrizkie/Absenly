@extends('layouts.adminNav')

@section('title', 'Kehadiran Hari Ini')
@section('page-title', 'Kehadiran Hari Ini')

@section('content')

    {{-- Header + Tanggal --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Kehadiran Hari Ini</h1>
            <p class="text-xs text-slate-500 mt-0.5" id="tanggalHariIni"></p>
        </div>
        <div class="flex items-center gap-2 bg-slate-100 px-3 py-1.5 rounded-full">
            <span class="text-xs text-slate-500 font-medium">Auto-refresh</span>
            <span id="refreshDot" class="w-2 h-2 rounded-full bg-blue-500 inline-block animate-pulse"></span>
        </div>
    </div>

    {{-- Summary Badges --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Hadir -->
        <div
            class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div
                class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0 border border-emerald-100">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="num text-2xl font-bold text-slate-800" id="countHadir">—</p>
                <p class="text-xs text-slate-500 font-medium">Hadir</p>
            </div>
        </div>
        <!-- Izin -->
        <div
            class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center shrink-0 border border-amber-100">
                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="num text-2xl font-bold text-slate-800" id="countIzin">—</p>
                <p class="text-xs text-slate-500 font-medium">Izin</p>
            </div>
        </div>
        <!-- Sakit -->
        <div
            class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0 border border-blue-100">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <div>
                <p class="num text-2xl font-bold text-slate-800" id="countSakit">—</p>
                <p class="text-xs text-slate-500 font-medium">Sakit</p>
            </div>
        </div>
        <!-- Belum Absen -->
        <div
            class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center shrink-0 border border-red-100">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="num text-2xl font-bold text-slate-800" id="countBelum">—</p>
                <p class="text-xs text-slate-500 font-medium">Belum Absen</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- HADIR (BESAR DI KIRI) --}}
        <div class="xl:col-span-2 bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <h2 class="text-sm font-semibold text-slate-700">Daftar Hadir</h2>
                <span class="ml-auto num text-xs font-bold text-white bg-emerald-500 px-2.5 py-1 rounded-full shadow-sm"
                    id="badgeHadir">0</span>
            </div>
            <div id="hadirList" class="p-4 space-y-3 max-h-[720px] overflow-y-auto"></div>
        </div>

        {{-- KOLOM KANAN (VERTIKAL) --}}
        <div class="flex flex-col gap-6">

            {{-- IZIN --}}
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                    <h2 class="text-sm font-semibold text-slate-700">Daftar Izin</h2>
                    <span class="ml-auto num text-xs font-bold text-white bg-amber-500 px-2.5 py-1 rounded-full shadow-sm"
                        id="badgeIzin">0</span>
                </div>
                <div id="izinList" class="p-4 space-y-3 max-h-[220px] overflow-y-auto"></div>
            </div>

            {{-- SAKIT --}}
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span>
                    <h2 class="text-sm font-semibold text-slate-700">Daftar Sakit</h2>
                    <span class="ml-auto num text-xs font-bold text-white bg-blue-500 px-2.5 py-1 rounded-full shadow-sm"
                        id="badgeSakit">0</span>
                </div>
                <div id="sakitList" class="p-4 space-y-3 max-h-[220px] overflow-y-auto"></div>
            </div>

            {{-- BELUM ABSEN --}}
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-400 animate-pulse"></span>
                    <h2 class="text-sm font-semibold text-slate-700">Belum Absen</h2>
                    <span class="ml-auto num text-xs font-bold text-white bg-red-500 px-2.5 py-1 rounded-full shadow-sm"
                        id="badgeBelum">0</span>
                </div>
                <div id="belumList" class="p-4 space-y-3 max-h-[220px] overflow-y-auto"></div>
            </div>

        </div>
    </div>

    {{-- FILTER + EXPORT --}}
    <div class="bg-white border border-slate-100 rounded-2xl p-5 mt-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex gap-3 flex-wrap flex-1">
                <select id="filterKelas"
                    class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 w-full md:w-auto">
                    <option value="">Semua Kelas</option>
                </select>

                <select id="filterJurusan"
                    class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 w-full md:w-auto">
                    <option value="">Semua Jurusan</option>
                </select>

                <select id="filterStatus"
                    class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 w-full md:w-auto">
                    <option value="">Semua Status</option>
                    <option value="hadir">Hadir</option>
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                    <option value="alfa">Belum Absen</option>
                </select>
            </div>

            <button onclick="exportExcel()" id="btnExportExcel"
                class="ml-auto bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-700 transition shadow-sm shadow-emerald-500/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export Excel
            </button>
        </div>
    </div>

    {{-- TABEL REKAP --}}
    <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden mt-6 shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h2 class="text-sm font-semibold text-slate-700">Rekap Kehadiran</h2>
            <span class="text-xs text-slate-400 font-medium bg-slate-100 px-2 py-1 rounded-md" id="rekapInfo">—</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold w-8">#</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold">Nama</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold">Kelas</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold">Jurusan</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold">Waktu</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold">Keterangan</th>
                    </tr>
                </thead>
                <tbody id="rekapTable" class="divide-y divide-slate-100">
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-slate-400 text-xs">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between gap-2 bg-slate-50/30">
            <span class="text-xs text-slate-500 font-medium" id="paginasiInfo"></span>
            <div class="flex items-center gap-1" id="paginasiButtons"></div>
        </div>
    </div>

    {{-- MODAL PREVIEW FOTO --}}
    <div id="fotoModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="relative bg-white rounded-2xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-100 flex flex-col">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 bg-slate-50">
                <p id="fotoModalTitle" class="text-sm font-semibold text-slate-800 truncate">Preview Foto</p>
                <button onclick="closeFotoModal()"
                    class="w-8 h-8 bg-white hover:bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:text-slate-800 shadow-sm transition border border-slate-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="relative bg-slate-900 flex items-center justify-center min-h-[260px] max-h-[80vh] overflow-hidden">
                <img id="fotoModalImg" src="" alt="Preview Foto" class="w-full object-contain max-h-[75vh]"
                     onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=Foto+Tidak+Tersedia&background=F1F5F9&color=64748B&size=400';">
                <p id="fotoModalSubtitle" class="absolute bottom-2 left-2 right-2 text-center text-xs text-white/90 bg-black/60 py-1.5 px-3 rounded-lg backdrop-blur-xs hidden"></p>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL --}}
    <div id="detailModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="relative bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-100">
            {{-- Header --}}
            <div
                class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Detail Kehadiran
                </h3>
                <button onclick="closeDetailModal()"
                    class="text-slate-400 hover:text-slate-700 transition p-1 rounded-lg hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Content --}}
            <div class="p-6 max-h-[70vh] overflow-y-auto bg-white">
                <div id="detailContent" class="space-y-4"></div>
            </div>

            {{-- Footer --}}
            <div id="detailFooter"
                class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                {{-- Tombol dirender via JS --}}
            </div>
        </div>
    </div>

    <input type="hidden" id="csrfToken" value="{{ csrf_token() }}">

    {{-- TOAST NOTIFIKASI --}}
    <div id="toastNotif" class="fixed bottom-6 right-6 z-[60] hidden">
        <div id="toastInner"
            class="flex items-center gap-3 px-5 py-3 rounded-xl shadow-xl text-sm font-medium text-white border border-white/10">
            <svg id="toastIcon" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor"
                viewBox="0 0 24 24"></svg>
            <span id="toastMsg"></span>
        </div>
    </div>

@endsection


<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.4.0/exceljs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
@push('scripts')
    <script>
        // ─── State ───────────────────────────────────────────────
        let allData = [];
        let rawData = {};
        let currentPage = 1;
        const PER_PAGE = 10;

        // ─── Tanggal ─────────────────────────────────────────────
        const tanggalEl = document.getElementById('tanggalHariIni');
        if (tanggalEl) {
            tanggalEl.textContent = new Date().toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        // ─── Toast ────────────────────────────────────────────────
        function showToast(msg, type = 'success') {
            const toast = document.getElementById('toastNotif');
            const inner = document.getElementById('toastInner');
            const iconEl = document.getElementById('toastIcon');
            const msgEl = document.getElementById('toastMsg');

            const configs = {
                success: {
                    bg: 'bg-blue-600',
                    icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                },
                error: {
                    bg: 'bg-red-600',
                    icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                },
            };

            const cfg = configs[type] ?? configs.success;
            inner.className =
                `flex items-center gap-3 px-5 py-3 rounded-xl shadow-xl text-sm font-medium text-white border border-white/10 ${cfg.bg}`;
            iconEl.innerHTML = cfg.icon;
            msgEl.textContent = msg;

            toast.classList.remove('hidden');
            clearTimeout(window._toastTimer);
            window._toastTimer = setTimeout(() => toast.classList.add('hidden'), 3000);
        }

        // ─── Load Data ───────────────────────────────────────────
        async function loadData() {
            try {
                const res = await fetch("{{ route('admin.kehadiran.data') }}");
                const data = await res.json();
                rawData = data;

                renderHadir(data.hadir);
                renderSimple('izinList', 'izin', data.izin);
                renderSimple('sakitList', 'sakit', data.sakit);
                renderBelum(data.belum);

                document.getElementById('countHadir').textContent = data.hadir.length;
                document.getElementById('countIzin').textContent = data.izin.length;
                document.getElementById('countSakit').textContent = data.sakit.length;
                document.getElementById('countBelum').textContent = data.belum.length;

                document.getElementById('badgeHadir').textContent = data.hadir.length;
                document.getElementById('badgeIzin').textContent = data.izin.length;
                document.getElementById('badgeSakit').textContent = data.sakit.length;
                document.getElementById('badgeBelum').textContent = data.belum.length;

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
                nama: r.user?.name ?? '-',
                kelas: r.user?.kelas?.nama ?? '-',
                jurusan: r.user?.jurusan?.nama ?? '-',
                status: 'hadir',
                waktu: r.waktu ?? '-',
                keterangan: r.keterangan ?? '',
            }));
            const izin = (data.izin ?? []).map(r => ({
                nama: r.user?.name ?? '-',
                kelas: r.user?.kelas?.nama ?? '-',
                jurusan: r.user?.jurusan?.nama ?? '-',
                status: 'izin',
                waktu: r.waktu ?? '-',
                keterangan: r.keterangan ?? '',
            }));
            const sakit = (data.sakit ?? []).map(r => ({
                nama: r.user?.name ?? '-',
                kelas: r.user?.kelas?.nama ?? '-',
                jurusan: r.user?.jurusan?.nama ?? '-',
                status: 'sakit',
                waktu: r.waktu ?? '-',
                keterangan: r.keterangan ?? '',
            }));
            const belum = (data.belum ?? []).map(u => ({
                nama: u.name ?? '-',
                kelas: u.kelas?.nama ?? '-',
                jurusan: u.jurusan?.nama ?? '-',
                status: 'alfa',
                waktu: '-',
                keterangan: '',
            }));

            allData = [...hadir, ...izin, ...sakit, ...belum];
        }

        // ─── Populate filter dropdowns ────────────────────────────
        function populateFilters() {
            const kelasSet = new Set(allData.map(r => r.kelas).filter(v => v && v !== '-'));
            const jurusanSet = new Set(allData.map(r => r.jurusan).filter(v => v && v !== '-'));

            const kelasEl = document.getElementById('filterKelas');
            const jurusanEl = document.getElementById('filterJurusan');

            const prevKelas = kelasEl.value;
            const prevJurusan = jurusanEl.value;

            kelasEl.innerHTML = '<option value="">Semua Kelas</option>' + [...kelasSet].sort().map(k =>
                `<option value="${k}">${k}</option>`).join('');
            jurusanEl.innerHTML = '<option value="">Semua Jurusan</option>' + [...jurusanSet].sort().map(j =>
                `<option value="${j}">${j}</option>`).join('');

            kelasEl.value = prevKelas;
            jurusanEl.value = prevJurusan;
        }

        // ─── Filter aktif ─────────────────────────────────────────
        function getFiltered() {
            const kelas = document.getElementById('filterKelas').value;
            const jurusan = document.getElementById('filterJurusan').value;
            const status = document.getElementById('filterStatus').value;

            return allData.filter(r => {
                return (!kelas || r.kelas === kelas) &&
                    (!jurusan || r.jurusan === jurusan) &&
                    (!status || r.status === status);
            });
        }

        // ─── Render tabel rekap ───────────────────────────────────
        function renderRekap() {
            const filtered = getFiltered();
            const total = filtered.length;
            const totalPage = Math.ceil(total / PER_PAGE) || 1;

            if (currentPage > totalPage) currentPage = totalPage;

            const start = (currentPage - 1) * PER_PAGE;
            const end = Math.min(start + PER_PAGE, total);
            const sliced = filtered.slice(start, end);

            const tbody = document.getElementById('rekapTable');

            if (!sliced.length) {
                tbody.innerHTML =
                    `<tr><td colspan="7" class="px-5 py-10 text-center text-slate-400 text-xs">Tidak ada data</td></tr>`;
                document.getElementById('rekapInfo').textContent = '0 data';
                document.getElementById('paginasiInfo').textContent = '';
                document.getElementById('paginasiButtons').innerHTML = '';
                return;
            }

            const statusBadge = {
                hadir: '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Hadir</span>',
                izin: '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">Izin</span>',
                sakit: '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20">Sakit</span>',
                alfa: '<span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-50 text-red-600 ring-1 ring-inset ring-red-600/20">Alfa</span>',
            };

            tbody.innerHTML = sliced.map((r, i) => `
        <tr class="hover:bg-slate-50 transition-colors">
            <td class="px-5 py-3 text-slate-400 text-xs font-medium">${start + i + 1}</td>
            <td class="px-5 py-3 font-medium text-slate-800">${r.nama}</td>
            <td class="px-5 py-3 text-slate-500">${r.kelas}</td>
            <td class="px-5 py-3 text-slate-500">${r.jurusan}</td>
            <td class="px-5 py-3">${statusBadge[r.status] ?? r.status}</td>
            <td class="px-5 py-3 text-slate-500 num tabular-nums">${r.waktu}</td>
            <td class="px-5 py-3 text-slate-400 text-xs">${r.keterangan || '-'}</td>
        </tr>
    `).join('');

            document.getElementById('rekapInfo').textContent = `${total} data`;
            document.getElementById('paginasiInfo').textContent = `Menampilkan ${start + 1}–${end} dari ${total} data`;

            renderPaginasi(totalPage);
        }

        function renderPaginasi(totalPage) {
            const container = document.getElementById('paginasiButtons');
            let html = '';

            html += `<button onclick="goPage(${currentPage - 1})"
        class="px-3 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-500 ${currentPage === 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-50 hover:border-slate-300'}"
        ${currentPage === 1 ? 'disabled' : ''}>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>`;

            const pages = getPaginasiRange(currentPage, totalPage);
            pages.forEach(p => {
                if (p === '...') {
                    html += `<span class="px-2 py-1 text-xs text-slate-400">…</span>`;
                } else {
                    html +=
                        `<button onclick="goPage(${p})"
                class="px-3 py-1.5 text-xs rounded-lg border transition-colors ${p === currentPage
                    ? 'bg-blue-600 text-white border-blue-600 font-semibold'
                    : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:border-slate-300'}">${p}</button>`;
                }
            });

            html += `<button onclick="goPage(${currentPage + 1})"
        class="px-3 py-1.5 text-xs rounded-lg border border-slate-200 bg-white text-slate-500 ${currentPage === totalPage ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-50 hover:border-slate-300'}"
        ${currentPage === totalPage ? 'disabled' : ''}>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>`;

            container.innerHTML = html;
        }

        function getPaginasiRange(current, total) {
            if (total <= 7) return Array.from({
                length: total
            }, (_, i) => i + 1);
            const pages = [1];
            if (current > 3) pages.push('...');
            for (let i = Math.max(2, current - 1); i <= Math.min(total - 1, current + 1); i++) pages.push(i);
            if (current < total - 2) pages.push('...');
            pages.push(total);
            return pages;
        }

        function goPage(page) {
            const filtered = getFiltered();
            const totalPage = Math.ceil(filtered.length / PER_PAGE) || 1;
            if (page < 1 || page > totalPage) return;
            currentPage = page;
            renderRekap();
        }

        document.getElementById('filterKelas').addEventListener('change', () => {
            currentPage = 1;
            renderRekap();
        });
        document.getElementById('filterJurusan').addEventListener('change', () => {
            currentPage = 1;
            renderRekap();
        });
        document.getElementById('filterStatus').addEventListener('change', () => {
            currentPage = 1;
            renderRekap();
        });

        // ─── Export Excel (siap saji) ─────────────────────────────
        async function exportExcel() {
            const btn = document.getElementById('btnExportExcel');
            const originalHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = 'Menyiapkan...';

            try {
                const filtered = getFiltered();
                const workbook = new ExcelJS.Workbook();
                workbook.creator = 'Absenly';
                workbook.created = new Date();

                const sheet = workbook.addWorksheet('Rekap Kehadiran', {
                    views: [{
                        state: 'frozen',
                        ySplit: 4
                    }],
                });

                // Kolom
                sheet.columns = [{
                        key: 'no',
                        width: 6
                    },
                    {
                        key: 'nama',
                        width: 28
                    },
                    {
                        key: 'kelas',
                        width: 12
                    },
                    {
                        key: 'jurusan',
                        width: 16
                    },
                    {
                        key: 'status',
                        width: 14
                    },
                    {
                        key: 'waktu',
                        width: 12
                    },
                    {
                        key: 'keterangan',
                        width: 32
                    },
                ];

                const tanggalStr = new Date().toLocaleDateString('id-ID', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });

                // ── Judul ──
                sheet.mergeCells('A1:G1');
                const titleCell = sheet.getCell('A1');
                titleCell.value = 'Rekap Kehadiran Siswa';
                titleCell.font = {
                    size: 16,
                    bold: true,
                    color: {
                        argb: 'FF1E293B'
                    }
                };
                titleCell.alignment = {
                    vertical: 'middle',
                    horizontal: 'left'
                };
                sheet.getRow(1).height = 26;

                // ── Subjudul (tanggal) ──
                sheet.mergeCells('A2:G2');
                const subCell = sheet.getCell('A2');
                subCell.value = tanggalStr;
                subCell.font = {
                    size: 11,
                    color: {
                        argb: 'FF64748B'
                    }
                };

                // ── Ringkasan ──
                sheet.mergeCells('A3:G3');
                const sumCell = sheet.getCell('A3');
                sumCell.value =
                    `Total: ${filtered.length} data  |  Hadir: ${rawData.hadir?.length ?? 0}  |  Izin: ${rawData.izin?.length ?? 0}  |  Sakit: ${rawData.sakit?.length ?? 0}  |  Belum Absen: ${rawData.belum?.length ?? 0}`;
                sumCell.font = {
                    size: 10,
                    italic: true,
                    color: {
                        argb: 'FF94A3B8'
                    }
                };
                sheet.addRow([]); // baris kosong pemisah (row 4)

                // ── Header tabel (row 5) ──
                const headerRow = sheet.addRow(['No', 'Nama', 'Kelas', 'Jurusan', 'Status', 'Waktu', 'Keterangan']);
                headerRow.eachCell(cell => {
                    cell.font = {
                        bold: true,
                        color: {
                            argb: 'FFFFFFFF'
                        }
                    };
                    cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: {
                            argb: 'FF2563EB'
                        }
                    };
                    cell.alignment = {
                        vertical: 'middle',
                        horizontal: 'center'
                    };
                    cell.border = {
                        top: {
                            style: 'thin',
                            color: {
                                argb: 'FFCBD5E1'
                            }
                        },
                        bottom: {
                            style: 'thin',
                            color: {
                                argb: 'FFCBD5E1'
                            }
                        },
                        left: {
                            style: 'thin',
                            color: {
                                argb: 'FFCBD5E1'
                            }
                        },
                        right: {
                            style: 'thin',
                            color: {
                                argb: 'FFCBD5E1'
                            }
                        },
                    };
                });
                headerRow.height = 22;

                // Warna per status (samain sama badge di web)
                const statusColors = {
                    hadir: {
                        fill: 'FFD1FAE5',
                        font: 'FF047857',
                        label: 'Hadir'
                    },
                    izin: {
                        fill: 'FFFEF3C7',
                        font: 'FFB45309',
                        label: 'Izin'
                    },
                    sakit: {
                        fill: 'FFDBEAFE',
                        font: 'FF1D4ED8',
                        label: 'Sakit'
                    },
                    alfa: {
                        fill: 'FFFEE2E2',
                        font: 'FFDC2626',
                        label: 'Belum Absen'
                    },
                };

                // ── Data rows ──
                filtered.forEach((r, i) => {
                    const row = sheet.addRow([
                        i + 1, r.nama, r.kelas, r.jurusan,
                        statusColors[r.status]?.label ?? r.status,
                        r.waktu, r.keterangan || '-',
                    ]);

                    row.eachCell((cell, colNumber) => {
                        cell.alignment = {
                            vertical: 'middle',
                            horizontal: colNumber === 1 ? 'center' : 'left'
                        };
                        cell.border = {
                            top: {
                                style: 'thin',
                                color: {
                                    argb: 'FFF1F5F9'
                                }
                            },
                            bottom: {
                                style: 'thin',
                                color: {
                                    argb: 'FFF1F5F9'
                                }
                            },
                            left: {
                                style: 'thin',
                                color: {
                                    argb: 'FFF1F5F9'
                                }
                            },
                            right: {
                                style: 'thin',
                                color: {
                                    argb: 'FFF1F5F9'
                                }
                            },
                        };
                    });

                    // Zebra stripe
                    if (i % 2 === 1) {
                        row.eachCell(cell => {
                            if (!cell.fill || cell.fill.fgColor?.argb === undefined) {
                                cell.fill = {
                                    type: 'pattern',
                                    pattern: 'solid',
                                    fgColor: {
                                        argb: 'FFF8FAFC'
                                    }
                                };
                            }
                        });
                    }

                    // Warnain cell status
                    const statusCell = row.getCell(5);
                    const sc = statusColors[r.status];
                    if (sc) {
                        statusCell.fill = {
                            type: 'pattern',
                            pattern: 'solid',
                            fgColor: {
                                argb: sc.fill
                            }
                        };
                        statusCell.font = {
                            bold: true,
                            color: {
                                argb: sc.font
                            }
                        };
                        statusCell.alignment = {
                            vertical: 'middle',
                            horizontal: 'center'
                        };
                    }
                });

                // ── Auto filter di header ──
                sheet.autoFilter = {
                    from: {
                        row: 5,
                        column: 1
                    },
                    to: {
                        row: 5,
                        column: 7
                    },
                };

                // ── Generate & download ──
                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                });
                saveAs(blob, `kehadiran_${new Date().toISOString().slice(0, 10)}.xlsx`);

                showToast('Excel berhasil diunduh');

            } catch (e) {
                console.error('Gagal export Excel:', e);
                showToast('Gagal membuat file Excel', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHTML;
            }
        }

        // ─── Render Hadir ─────────────────────────────────────────
        function renderHadir(data) {
            const container = document.getElementById('hadirList');
            if (!data.length) {
                container.innerHTML = emptyState('Belum ada yang hadir');
                return;
            }
            container.innerHTML = data.map(item => {
                const userName = item.user?.name ?? 'Siswa';
                const avatarFallback = `https://ui-avatars.com/api/?name=${encodeURIComponent(userName)}&background=E2E8F0&color=475569&size=160`;
                
                let thumbSrc = avatarFallback;
                let modalSrc = avatarFallback;
                let hasFoto = false;

                if (item.foto) {
                    const cleanPath = item.foto.startsWith('/') ? item.foto : `/${item.foto.startsWith('storage/') ? item.foto : 'storage/' + item.foto}`;
                    thumbSrc = cleanPath;
                    modalSrc = cleanPath;
                    hasFoto = true;
                } else if (item.user?.foto) {
                    const userFoto = item.user.foto.startsWith('/') ? item.user.foto : `/img/${item.user.foto}`;
                    thumbSrc = userFoto;
                    modalSrc = userFoto;
                }

                return `
        <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 transition-colors border border-slate-100 bg-white shadow-xs">
            <img src="${thumbSrc}"
                 onclick="openFotoModal('${modalSrc}', '${encodeURIComponent(userName)}', ${hasFoto})"
                 onerror="this.onerror=null; this.src='${avatarFallback}'"
                 class="w-12 h-12 rounded-full object-cover shrink-0 border-2 border-white shadow-sm cursor-pointer hover:scale-105 transition-transform ring-2 ring-slate-100">
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-slate-800 truncate">${userName}</p>
                <p class="text-xs text-slate-500">${item.user?.kelas?.nama ?? ''} ${item.user?.jurusan?.nama ?? ''}</p>
                <p class="text-xs text-slate-400 num mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ${item.waktu ?? ''}
                </p>
            </div>
            <button onclick='openDetailModal(${JSON.stringify(item).replace(/'/g, "&#39;")})'
                    class="shrink-0 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-xs font-medium flex items-center gap-1 border border-blue-100">
                Detail
            </button>
        </div>
        `;
            }).join('');
        }

        // ─── Render Izin / Sakit ──────────────────────────────────
        function renderSimple(containerId, type, data) {
            const container = document.getElementById(containerId);
            const colors = {
                izin: {
                    dot: 'bg-amber-400',
                    text: 'text-amber-700',
                    bg: 'bg-amber-50',
                    hover: 'hover:bg-amber-100',
                    border: 'border-amber-100',
                    approved: 'bg-emerald-100 text-emerald-700',
                    rejected: 'bg-red-100 text-red-600'
                },
                sakit: {
                    dot: 'bg-blue-400',
                    text: 'text-blue-700',
                    bg: 'bg-blue-50',
                    hover: 'hover:bg-blue-100',
                    border: 'border-blue-100',
                    approved: 'bg-emerald-100 text-emerald-700',
                    rejected: 'bg-red-100 text-red-600'
                },
            };
            const c = colors[type] ?? {
                dot: 'bg-slate-300',
                text: 'text-slate-600',
                bg: 'bg-slate-50',
                hover: 'hover:bg-slate-100',
                border: 'border-slate-100',
                approved: 'bg-emerald-100 text-emerald-700',
                rejected: 'bg-red-100 text-red-600'
            };

            if (!data.length) {
                container.innerHTML = emptyState('Tidak ada data');
                return;
            }

            container.innerHTML = data.map(item => {
                let approvalBadge = '';
                if (item.status === 'approved') {
                    approvalBadge =
                        `<span class="shrink-0 text-[10px] font-semibold px-2 py-0.5 rounded-full ${c.approved}">Disetujui</span>`;
                } else if (item.status === 'rejected') {
                    approvalBadge =
                        `<span class="shrink-0 text-[10px] font-semibold px-2 py-0.5 rounded-full ${c.rejected}">Ditolak</span>`;
                } else {
                    approvalBadge =
                        `<span class="shrink-0 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500">Menunggu</span>`;
                }

                return `
        <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors border border-slate-100 bg-white shadow-xs">
            <div class="w-10 h-10 rounded-full ${c.bg} flex items-center justify-center shrink-0 text-sm font-bold ${c.text} border ${c.border}">
                ${(item.user?.name ?? 'U').charAt(0).toUpperCase()}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-slate-800 truncate">${item.user?.name ?? '-'}</p>
                <p class="text-xs text-slate-500">${item.user?.kelas?.nama ?? ''} ${item.user?.jurusan?.nama ?? ''}</p>
                <div class="mt-1">${approvalBadge}</div>
            </div>
            <button onclick='openDetailModal(${JSON.stringify(item).replace(/'/g, "&#39;")})'
                    class="shrink-0 px-3 py-1.5 ${c.bg} ${c.text} rounded-lg ${c.hover} transition text-xs font-medium flex items-center gap-1 border ${c.border}">
                Detail
            </button>
        </div>
        `;
            }).join('');
        }

        // ─── Render Belum Absen ───────────────────────────────────
        function renderBelum(data) {
            const container = document.getElementById('belumList');
            if (!data.length) {
                container.innerHTML = `
            <div class="flex flex-col items-center py-8 text-center">
                <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center mb-3 ring-1 ring-emerald-100">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-xs text-slate-500 font-medium">Semua siswa sudah absen!</p>
            </div>`;
                return;
            }
            container.innerHTML = data.map(user => `
        <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors border border-slate-100 bg-white shadow-xs">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center shrink-0 text-sm font-bold text-red-500 border border-red-100">
                ${(user.name ?? 'U').charAt(0).toUpperCase()}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-slate-800 truncate">${user.name ?? '-'}</p>
                ${user.kelas?.nama ? `<p class="text-xs text-slate-500">${user.kelas.nama} ${user.jurusan?.nama ?? ''}</p>` : ''}
            </div>
            <span class="shrink-0 text-[10px] font-semibold text-red-500 bg-red-50 px-2 py-1 rounded-full border border-red-100">Belum Absen</span>
        </div>
    `).join('');
        }

        function emptyState(msg) {
            return `<p class="text-xs text-slate-400 text-center py-8">${msg}</p>`;
        }

        // ─── Modals ───────────────────────────────────────────────
        function openFotoModal(src, name = 'Siswa', hasFoto = true) {
            const title = decodeURIComponent(name);
            const titleEl = document.getElementById('fotoModalTitle');
            if (titleEl) titleEl.innerText = 'Foto: ' + title;

            const sub = document.getElementById('fotoModalSubtitle');
            if (sub) {
                if (!hasFoto) {
                    sub.innerText = 'Foto absensi tidak diambil (Hadir via QR Code / Manual Admin). Menampilkan foto profil.';
                    sub.classList.remove('hidden');
                } else {
                    sub.classList.add('hidden');
                }
            }

            const img = document.getElementById('fotoModalImg');
            if (img) {
                img.onerror = function() {
                    this.onerror = null;
                    this.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(title)}&background=3B82F6&color=FFFFFF&size=400`;
                };
                img.src = src;
            }

            document.getElementById('fotoModal').classList.remove('hidden');
            document.getElementById('fotoModal').classList.add('flex');
        }

        function closeFotoModal() {
            document.getElementById('fotoModal').classList.add('hidden');
            document.getElementById('fotoModal').classList.remove('flex');
        }

        // ─── Detail Modal ─────────────────────────────────────────
        function openDetailModal(item) {
            const statusConfig = {
                hadir: {
                    color: 'emerald',
                    icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>`,
                    label: 'Hadir'
                },
                izin: {
                    color: 'amber',
                    icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>`,
                    label: 'Izin'
                },
                sakit: {
                    color: 'blue',
                    icon: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>`,
                    label: 'Sakit'
                },
            };

            const type = item.jenis ?? item.keterangan ?? 'hadir';
            const config = statusConfig[type] || statusConfig.hadir;

            let approvalBadgeHTML = '';
            if (type === 'izin' || type === 'sakit') {
                if (item.status === 'approved') {
                    approvalBadgeHTML =
                        `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Disetujui</span>`;
                } else if (item.status === 'rejected') {
                    approvalBadgeHTML =
                        `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-100">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>Ditolak</span>`;
                } else {
                    approvalBadgeHTML =
                        `<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Menunggu Persetujuan</span>`;
                }
            }

            let contentHTML = `
        <div class="flex items-center gap-4 mb-5 p-4 bg-${config.color}-50 rounded-xl border border-${config.color}-100">
            <div class="w-12 h-12 bg-${config.color}-100 rounded-full flex items-center justify-center text-${config.color}-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">${config.icon}</svg>
            </div>
            <div class="flex-1">
                <p class="text-xs text-slate-500 font-medium">Status Kehadiran</p>
                <div class="flex items-center gap-2 flex-wrap mt-1">
                    <p class="text-lg font-bold text-${config.color}-700">${config.label}</p>
                    ${approvalBadgeHTML}
                </div>
            </div>
        </div>

        <div class="space-y-4">
            {{-- Nama --}}
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center shrink-0 border border-slate-50">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="flex-1 pt-1">
                    <p class="text-xs text-slate-400 mb-0.5">Nama Lengkap</p>
                    <p class="text-sm font-semibold text-slate-800">${item.user?.name ?? item.nama ?? '-'}</p>
                </div>
            </div>

            {{-- Kelas --}}
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center shrink-0 border border-slate-50">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="flex-1 pt-1">
                    <p class="text-xs text-slate-400 mb-0.5">Kelas & Jurusan</p>
                    <p class="text-sm font-semibold text-slate-800">${item.user?.kelas?.nama ?? item.kelas ?? '-'} ${item.user?.jurusan?.nama ?? item.jurusan ?? ''}</p>
                </div>
            </div>

            {{-- Waktu --}}
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center shrink-0 border border-slate-50">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 pt-1">
                    <p class="text-xs text-slate-400 mb-0.5">Waktu Absen</p>
                    <p class="text-sm font-semibold text-slate-800 num tabular-nums">${item.waktu ?? '-'}</p>
                </div>
            </div>
    `;

            // ── Foto kehadiran (hadir) ──
            const detailFoto = item.foto 
                ? (item.foto.startsWith('/') ? item.foto : `/${item.foto.startsWith('storage/') ? item.foto : 'storage/' + item.foto}`)
                : (item.user?.foto ? (item.user.foto.startsWith('/') ? item.user.foto : `/img/${item.user.foto}`) : null);
            const detailUserName = item.user?.name ?? item.nama ?? 'Siswa';
            const detailAvatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(detailUserName)}&background=E2E8F0&color=475569&size=200`;

            if (detailFoto) {
                contentHTML += `
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center shrink-0 border border-slate-50">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="flex-1 pt-1">
                    <p class="text-xs text-slate-400 mb-1.5">Foto Kehadiran</p>
                    <img src="${detailFoto}"
                         onclick="openFotoModal('${detailFoto}', '${encodeURIComponent(detailUserName)}', ${!!item.foto})"
                         onerror="this.onerror=null; this.src='${detailAvatar}'"
                         class="w-32 h-32 rounded-xl object-cover border border-slate-200 cursor-pointer hover:scale-105 transition-transform shadow-sm">
                </div>
            </div>
        `;
            }

            // ── Alasan / keterangan ──
            if (item.alasan || item.keterangan) {
                contentHTML += `
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center shrink-0 border border-slate-50">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                </div>
                <div class="flex-1 pt-1">
                    <p class="text-xs text-slate-400 mb-0.5">Keterangan / Alasan</p>
                    <p class="text-sm text-slate-700 bg-slate-50 p-3 rounded-lg border border-slate-100">${item.alasan ?? item.keterangan}</p>
                </div>
            </div>
        `;
            }

            // ── Lampiran Surat (izin / sakit) ──
            if ((type === 'izin' || type === 'sakit') && item.lampiran) {
                const isImage = /\.(jpg|jpeg|png|webp|gif)$/i.test(item.lampiran);
                const isPdf = /\.pdf$/i.test(item.lampiran);
                const lampiranUrl = item.lampiran.startsWith('/') ? item.lampiran : `/${item.lampiran.startsWith('storage/') ? item.lampiran : 'storage/' + item.lampiran}`;

                let lampiranContent = '';
                if (isImage) {
                    lampiranContent = `
                <img src="${lampiranUrl}"
                     onclick="openFotoModal('${lampiranUrl}', 'Lampiran ${encodeURIComponent(detailUserName)}', true)"
                     onerror="this.parentElement.innerHTML='<p class=\\'text-xs text-red-400\\'>Gagal memuat lampiran.</p>'"
                     class="w-full max-h-48 object-contain rounded-lg border border-slate-200 cursor-pointer hover:opacity-90 transition shadow-sm bg-white p-2">
                <a href="${lampiranUrl}" target="_blank"
                   class="inline-flex items-center gap-1 mt-2 text-xs text-blue-600 hover:underline font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Buka di tab baru
                </a>`;
                } else if (isPdf) {
                    lampiranContent = `
                <div class="flex items-center gap-3 p-3 bg-red-50 border border-red-100 rounded-lg">
                    <svg class="w-8 h-8 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-700 truncate">${item.lampiran.split('/').pop()}</p>
                        <p class="text-[10px] text-slate-400">Dokumen PDF</p>
                    </div>
                    <a href="${lampiranUrl}" target="_blank"
                       class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-medium transition flex items-center gap-1 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Buka
                    </a>
                </div>`;
                } else {
                    lampiranContent = `
                <a href="${lampiranUrl}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-medium transition border border-slate-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Unduh Lampiran
                </a>`;
                }

                contentHTML += `
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center shrink-0 border border-slate-50">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                </div>
                <div class="flex-1 pt-1">
                    <p class="text-xs text-slate-400 mb-1.5">Lampiran Surat</p>
                    ${lampiranContent}
                </div>
            </div>
        `;
            } else if (type === 'izin' || type === 'sakit') {
                contentHTML += `
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center shrink-0 border border-slate-50">
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                </div>
                <div class="flex-1 pt-1">
                    <p class="text-xs text-slate-400 mb-0.5">Lampiran Surat</p>
                    <p class="text-xs text-slate-400 italic">Tidak ada lampiran</p>
                </div>
            </div>
        `;
            }

            contentHTML += '</div>';

            document.getElementById('detailContent').innerHTML = contentHTML;

            // ── Footer: tombol approve/reject ──
            const footer = document.getElementById('detailFooter');
            if (type === 'izin' || type === 'sakit') {
                const isPending = !item.status || item.status === 'pending';

                footer.innerHTML = `
            <div class="flex items-center gap-3 w-full">
                <span class="text-[10px] text-slate-300 mr-auto font-mono">#${item.id ?? '-'}</span>
                ${isPending ? `
                                                            <button onclick="handleApproval(${item.id}, 'approved')"
                                                                class="px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                </svg>
                                                                Terima
                                                            </button>
                                                            <button onclick="handleApproval(${item.id}, 'rejected')"
                                                                class="px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-1.5 bg-white hover:bg-red-50 text-red-600 border border-red-200">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                                Tolak
                                                            </button>` : ''}
                <button onclick="closeDetailModal()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition">
                    Tutup
                </button>
            </div>
        `;
            } else {
                footer.innerHTML = `
            <button onclick="closeDetailModal()"
                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition">
                Tutup
            </button>
        `;
            }

            document.getElementById('detailModal').classList.remove('hidden');
            document.getElementById('detailModal').classList.add('flex');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
        }

        // ─── Approval: Terima / Tolak ─────────────────────────────
        async function handleApproval(id, action) {
            if (!id) {
                showToast('ID tidak ditemukan', 'error');
                return;
            }

            try {
                const res = await fetch(`{{ url('admin/kehadiran') }}/${id}/approval`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.getElementById('csrfToken')?.value ??
                            document.querySelector('meta[name="csrf-token"]')?.content ??
                            '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        status: action
                    }),
                });

                const json = await res.json();

                if (!res.ok) {
                    showToast(json.message ?? 'Gagal memperbarui status', 'error');
                    return;
                }

                showToast(
                    action === 'approved' ? 'Permohonan disetujui' : 'Permohonan ditolak',
                    action === 'approved' ? 'success' : 'error'
                );

                closeDetailModal();
                loadData();

            } catch (e) {
                console.error(e);
                showToast('Terjadi kesalahan jaringan', 'error');
            }
        }

        // ─── Init ─────────────────────────────────────────────────
        loadData();
        setInterval(loadData, 10000);
    </script>
@endpush
