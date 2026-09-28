@extends('layouts.adminNav')

@section('title', 'Absen Pulang')
@section('page-title', 'Absen Pulang')

@section('content')

    {{-- Header + Tanggal --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Absen Pulang Hari Ini</h1>
            <p class="text-xs text-slate-500 mt-0.5" id="tanggalHariIni"></p>
        </div>
        <div class="flex items-center gap-2 bg-slate-100 px-3 py-1.5 rounded-full">
            <span class="text-xs text-slate-500 font-medium">Auto-refresh</span>
            <span id="refreshDot" class="w-2 h-2 rounded-full bg-purple-500 inline-block animate-pulse"></span>
        </div>
    </div>

    {{-- Summary Badges --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Sudah Pulang -->
        <div class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center shrink-0 border border-purple-100">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="num text-2xl font-bold text-slate-800" id="countSudahPulang">—</p>
                <p class="text-xs text-slate-500 font-medium">Sudah Pulang</p>
            </div>
        </div>
        
        <!-- Izin Pulang Pending -->
        <div class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center shrink-0 border border-amber-100">
                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="num text-2xl font-bold text-slate-800" id="countIzinPulang">—</p>
                <p class="text-xs text-slate-500 font-medium">Izin Pulang Pending</p>
            </div>
        </div>
        
        <!-- Belum Pulang -->
        <div class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center shrink-0 border border-red-100">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="num text-2xl font-bold text-slate-800" id="countBelumPulang">—</p>
                <p class="text-xs text-slate-500 font-medium">Belum Pulang</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- SUDAH PULANG (BESAR DI KIRI) --}}
        <div class="xl:col-span-2 bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                <h2 class="text-sm font-semibold text-slate-700">Daftar Sudah Pulang</h2>
                <span class="ml-auto num text-xs font-bold text-white bg-purple-500 px-2.5 py-1 rounded-full shadow-sm" id="badgeSudahPulang">0</span>
            </div>
            <div id="sudahPulangList" class="p-4 space-y-3 max-h-[720px] overflow-y-auto"></div>
        </div>

        {{-- KOLOM KANAN (VERTIKAL) --}}
        <div class="flex flex-col gap-6">

            {{-- IZIN PULANG PENDING --}}
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span>
                    <h2 class="text-sm font-semibold text-slate-700">Izin Pulang Pending</h2>
                    <span class="ml-auto num text-xs font-bold text-white bg-amber-500 px-2.5 py-1 rounded-full shadow-sm" id="badgeIzinPulang">0</span>
                </div>
                <div id="izinPulangList" class="p-4 space-y-3 max-h-[340px] overflow-y-auto"></div>
            </div>

            {{-- BELUM PULANG --}}
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="flex items-center gap-3 px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                    <h2 class="text-sm font-semibold text-slate-700">Belum Pulang</h2>
                    <span class="ml-auto num text-xs font-bold text-white bg-red-500 px-2.5 py-1 rounded-full shadow-sm" id="badgeBelumPulang">0</span>
                </div>
                <div id="belumPulangList" class="p-4 space-y-3 max-h-[340px] overflow-y-auto"></div>
            </div>

        </div>
    </div>
    
    {{-- MODAL PREVIEW FOTO --}}
    <div id="fotoModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="relative bg-white rounded-2xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-100 flex flex-col">
            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 bg-slate-50">
                <p id="fotoModalTitle" class="text-sm font-semibold text-slate-800 truncate">Preview Foto Pulang</p>
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
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL PENGAJUAN IZIN/SAKIT --}}
    <div id="detailModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="relative bg-white rounded-2xl max-w-lg w-full shadow-2xl overflow-hidden border border-slate-100">
            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-amber-50 to-white">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Detail Pengajuan Izin Pulang
                </h3>
                <button onclick="closeDetailModal()" 
                    class="text-slate-400 hover:text-slate-700 transition p-1 rounded-lg hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Content --}}
            <div class="p-6 max-h-[60vh] overflow-y-auto bg-white">
                <div id="detailContent" class="space-y-4"></div>
            </div>

            {{-- Footer --}}
            <div id="detailFooter" class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                {{-- Tombol dirender via JS --}}
            </div>
        </div>
    </div>

    <input type="hidden" id="csrfToken" value="{{ csrf_token() }}">

    {{-- TOAST NOTIFIKASI --}}
    <div id="toastNotif" class="fixed bottom-6 right-6 z-[60] hidden">
        <div id="toastInner" class="flex items-center gap-3 px-5 py-3 rounded-xl shadow-xl text-sm font-medium text-white border border-white/10">
            <svg id="toastIcon" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
            <span id="toastMsg"></span>
        </div>
    </div>

@endsection

@push('scripts')
<script>
// ─── State ───────────────────────────────────────────────
let rawData = {};

// ─── Tanggal ─────────────────────────────────────────────
const tanggalEl = document.getElementById('tanggalHariIni');
if (tanggalEl) {
    tanggalEl.textContent = new Date().toLocaleDateString('id-ID', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
    });
}

// ─── Toast ────────────────────────────────────────────────
function showToast(msg, type = 'success') {
    const toast   = document.getElementById('toastNotif');
    const inner   = document.getElementById('toastInner');
    const iconEl  = document.getElementById('toastIcon');
    const msgEl   = document.getElementById('toastMsg');

    const configs = {
        success: {
            bg  : 'bg-green-600',
            icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        },
        error: {
            bg  : 'bg-red-600',
            icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        },
    };

    const cfg = configs[type] ?? configs.success;
    inner.className = `flex items-center gap-3 px-5 py-3 rounded-xl shadow-xl text-sm font-medium text-white border border-white/10 ${cfg.bg}`;
    iconEl.innerHTML = cfg.icon;
    msgEl.textContent = msg;

    toast.classList.remove('hidden');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => toast.classList.add('hidden'), 3000);
}

// ─── Load Data ───────────────────────────────────────────
async function loadData() {
    try {
        const res  = await fetch("{{ route('admin.absen-pulang.data') }}");
        const data = await res.json();
        rawData    = data;

        renderSudahPulang(data.sudah_pulang);
        renderIzinPulang(data.izin_pulang);
        renderBelumPulang(data.belum_pulang);

        document.getElementById('countSudahPulang').textContent = data.sudah_pulang.length;
        document.getElementById('countIzinPulang').textContent  = data.izin_pulang.length;
        document.getElementById('countBelumPulang').textContent = data.belum_pulang.length;

        document.getElementById('badgeSudahPulang').textContent = data.sudah_pulang.length;
        document.getElementById('badgeIzinPulang').textContent  = data.izin_pulang.length;
        document.getElementById('badgeBelumPulang').textContent = data.belum_pulang.length;

    } catch (e) {
        console.error('Gagal memuat data absen pulang:', e);
    }
}

// ─── Empty State ──────────────────────────────────────────
function emptyState(msg) {
    return `<div class="text-center py-8 text-slate-400 text-xs">
        <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
        <p>${msg}</p>
    </div>`;
}

// ─── Render Sudah Pulang ──────────────────────────────────
function renderSudahPulang(data) {
    const container = document.getElementById('sudahPulangList');
    if (!data.length) {
        container.innerHTML = emptyState('Belum ada yang pulang');
        return;
    }
    container.innerHTML = data.map(item => {
        const tipeBadge = item.tipe_pulang === 'sakit' 
            ? '<span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">Sakit</span>'
            : item.tipe_pulang === 'izin'
            ? '<span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Izin</span>'
            : '<span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Normal</span>';

        const fotoSrc = item.foto_pulang
            ? `/storage/${item.foto_pulang}`
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(item.user?.name ?? 'U')}&background=E9D5FF&color=7E22CE&size=80`;
        
        return `
        <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 transition-colors border border-slate-100 bg-white shadow-xs">
            <img src="${fotoSrc}"
                 onclick="openFotoModal('${fotoSrc}', '${encodeURIComponent(item.user?.name ?? 'Siswa')}')"
                 onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(item.user?.name ?? 'U')}&background=E9D5FF&color=7E22CE&size=80'"
                 class="w-12 h-12 rounded-full object-cover shrink-0 border-2 border-white shadow-sm cursor-pointer hover:scale-105 transition-transform ring-2 ring-purple-100">
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-slate-800 truncate">${item.user?.name ?? '-'}</p>
                <p class="text-xs text-slate-500">${item.user?.kelas ?? ''} ${item.user?.jurusan ?? ''}</p>
                <div class="flex items-center gap-2 mt-1 text-xs text-slate-400">
                    <span class="num">Masuk: ${item.waktu_masuk}</span>
                    <span class="text-slate-300">•</span>
                    <span class="num text-purple-600 font-medium">Pulang: ${item.waktu_pulang}</span>
                </div>
                <div class="mt-1">${tipeBadge}</div>
            </div>
        </div>
    `}).join('');
}

// ─── Render Izin Pulang Pending ───────────────────────────
function renderIzinPulang(data) {
    const container = document.getElementById('izinPulangList');
    if (!data.length) {
        container.innerHTML = emptyState('Tidak ada pengajuan pending');
        return;
    }
    container.innerHTML = data.map(item => {
        const tipeBadge = item.tipe_pulang === 'sakit' 
            ? '<span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">Sakit</span>'
            : '<span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Izin</span>';
        
        return `
        <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50/50 transition-colors border border-amber-100 bg-white shadow-xs">
            <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center shrink-0 text-sm font-bold text-amber-700 border border-amber-200">
                ${(item.user?.name ?? 'U').charAt(0).toUpperCase()}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-slate-800 truncate">${item.user?.name ?? '-'}</p>
                <p class="text-xs text-slate-500">${item.user?.kelas ?? ''} ${item.user?.jurusan ?? ''}</p>
                <p class="text-xs text-slate-400 num mt-1">Masuk: ${item.waktu_masuk}</p>
                <div class="mt-1">${tipeBadge}</div>
            </div>
            <button onclick='openDetailModal(${JSON.stringify(item).replace(/'/g, "&#39;")})'
                    class="shrink-0 px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition text-xs font-medium flex items-center gap-1 border border-amber-100">
                ACC
            </button>
        </div>
    `}).join('');
}

// ─── Render Belum Pulang ──────────────────────────────────
function renderBelumPulang(data) {
    const container = document.getElementById('belumPulangList');
    if (!data.length) {
        container.innerHTML = emptyState('Semua sudah pulang');
        return;
    }
    container.innerHTML = data.map(item => {
        const statusBadge = item.status_pulang === 'rejected'
            ? '<span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-red-100 text-red-600">Ditolak</span>'
            : '';
        
        return `
        <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-colors border border-slate-100 bg-white shadow-xs">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center shrink-0 text-sm font-bold text-red-600 border border-red-100">
                ${(item.user?.name ?? 'U').charAt(0).toUpperCase()}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-slate-800 truncate">${item.user?.name ?? '-'}</p>
                <p class="text-xs text-slate-500">${item.user?.kelas ?? ''} ${item.user?.jurusan ?? ''}</p>
                <p class="text-xs text-slate-400 num mt-1">Masuk: ${item.waktu_masuk}</p>
                ${statusBadge ? `<div class="mt-1">${statusBadge}</div>` : ''}
            </div>
        </div>
    `}).join('');
}

// ─── Open Detail Modal ────────────────────────────────────
function openDetailModal(item) {
    const modal   = document.getElementById('detailModal');
    const content = document.getElementById('detailContent');
    const footer  = document.getElementById('detailFooter');

    const tipeBadge = item.tipe_pulang === 'sakit' 
        ? '<span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">Sakit</span>'
        : '<span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">Izin</span>';

    content.innerHTML = `
        <div class="bg-gradient-to-br from-slate-50 to-white border border-slate-100 rounded-xl p-4">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center text-lg font-bold text-slate-700">
                    ${(item.user?.name ?? 'U').charAt(0).toUpperCase()}
                </div>
                <div>
                    <p class="font-bold text-slate-800">${item.user?.name ?? '-'}</p>
                    <p class="text-xs text-slate-500">${item.user?.kelas ?? '-'} ${item.user?.jurusan ?? '-'}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-white border border-slate-100 rounded-lg p-3">
                    <p class="text-[10px] text-slate-400 uppercase font-semibold mb-1">Waktu Masuk</p>
                    <p class="text-sm font-bold text-slate-700 num">${item.waktu_masuk}</p>
                </div>
                <div class="bg-white border border-slate-100 rounded-lg p-3">
                    <p class="text-[10px] text-slate-400 uppercase font-semibold mb-1">Tipe</p>
                    <div class="mt-1">${tipeBadge}</div>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-lg p-3">
                <p class="text-[10px] text-slate-400 uppercase font-semibold mb-2">Alasan</p>
                <p class="text-sm text-slate-700">${item.alasan || '-'}</p>
            </div>
        </div>
    `;

    footer.innerHTML = `
        <button onclick="closeDetailModal()" 
            class="px-4 py-2 text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition text-sm font-medium">
            Batal
        </button>
        <button onclick="handleApprovalPulang(${item.id}, 'rejected')" 
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-semibold shadow-sm">
            Tolak
        </button>
        <button onclick="handleApprovalPulang(${item.id}, 'approved')" 
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-semibold shadow-sm">
            Setujui
        </button>
    `;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    function openFotoModal(src, name = 'Siswa') {
        const titleEl = document.getElementById('fotoModalTitle');
        if (titleEl) titleEl.innerText = 'Foto Pulang: ' + decodeURIComponent(name);
        const img = document.getElementById('fotoModalImg');
        if (img) {
            img.onerror = function() {
                this.onerror = null;
                this.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=7E22CE&color=FFFFFF&size=400`;
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

// ─── Handle Approval ──────────────────────────────────────
async function handleApprovalPulang(id, status) {
    try {
        const res = await fetch(`/admin/kehadiran/${id}/approval-pulang`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.getElementById('csrfToken').value,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status })
        });

        const data = await res.json();

        if (res.ok) {
            showToast(data.message, 'success');
            closeDetailModal();
            loadData();
        } else {
            throw new Error(data.message || 'Gagal memperbarui status');
        }
    } catch (error) {
        showToast(error.message || 'Terjadi kesalahan', 'error');
    }
}

// ─── Auto Refresh ─────────────────────────────────────────
setInterval(() => loadData(), 10000); // Refresh setiap 10 detik

// ─── Initial Load ─────────────────────────────────────────
loadData();
</script>
@endpush
