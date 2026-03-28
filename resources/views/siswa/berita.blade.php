@extends('layouts.siswaNav')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    * { font-family: 'Plus Jakarta Sans', sans-serif; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .fade-in-up { animation: fadeInUp 0.4s cubic-bezier(.22,.68,0,1.2) both; }

    .line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    .line-clamp-3 { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }

    .news-card { transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease; }
    .news-card:hover { transform:translateY(-4px); box-shadow:0 16px 40px -12px rgba(59,130,246,.2); border-color:#bfdbfe; }
    .news-card .card-img { transition: transform .5s ease; }
    .news-card:hover .card-img { transform:scale(1.07); }

    /* MODAL */
    #newsModal {
        position:fixed; inset:0;
        background:rgba(0,0,0,.6);
        backdrop-filter:blur(4px);
        -webkit-backdrop-filter:blur(4px);
        z-index:9999;
        display:flex; align-items:center; justify-content:center;
        padding:1rem;
        visibility:hidden; opacity:0;
        transition:opacity .25s ease, visibility .25s ease;
    }
    #newsModal.active { visibility:visible; opacity:1; }
    #newsModalContent {
        transform:scale(.92);
        transition:transform .3s cubic-bezier(.22,.68,0,1.2);
    }
    #newsModal.active #newsModalContent { transform:scale(1); }

    .gradient-text {
        background:linear-gradient(135deg,#fff 0%,#bfdbfe 100%);
        -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;
    }
    .no-img-placeholder { background:linear-gradient(135deg,#f8fafc 0%,#e2e8f0 100%); }
    .modal-scroll::-webkit-scrollbar { width:4px }
    .modal-scroll::-webkit-scrollbar-track { background:#f1f5f9; border-radius:99px }
    .modal-scroll::-webkit-scrollbar-thumb { background:#bfdbfe; border-radius:99px }
</style>

{{-- ══════ HEADER ══════ --}}
<div class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 text-white rounded-b-[2.5rem] px-5 pt-6 pb-14 overflow-hidden shadow-xl shadow-blue-900/20">
    <div class="absolute -top-16 -right-16 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-12 -left-12 w-52 h-52 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none"></div>

    <button onclick="history.back()"
        class="relative z-10 mb-5 inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 active:scale-95 px-3 py-2 rounded-xl backdrop-blur-sm transition-all text-sm font-semibold">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </button>

    <div class="relative z-10 flex items-start gap-3 mb-6">
        <div class="bg-white/20 backdrop-blur-sm p-2.5 rounded-xl shrink-0">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-extrabold gradient-text tracking-tight leading-tight">Berita Sekolah</h1>
            <p class="text-sm text-blue-100/80 mt-0.5">Update informasi terbaru seputar sekolah</p>
        </div>
    </div>

    <div class="relative z-10">
        <div class="relative">
            <input id="searchInput" type="text" autocomplete="off" placeholder="Cari judul berita..."
                class="w-full bg-white/15 backdrop-blur-md border border-white/25 text-white placeholder-white/60 rounded-2xl pl-5 pr-14 py-3.5 focus:outline-none focus:ring-2 focus:ring-white/40 transition-all text-sm font-medium"/>
            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                <svg class="h-4 w-4 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- ══════ CONTENT ══════ --}}
<div class="px-4 -mt-6 relative z-20 pb-10">

    {{-- Data berita dari server (embed JSON untuk JS) --}}
    @php
        $beritaJson = $beritas->map(fn($b) => [
            'id'       => $b->id,
            'judul'    => $b->judul,
            'penulis'  => $b->penulis,
            'tanggal'  => $b->created_at->format('d M Y'),
            'gambar'   => $b->gambar ? asset('img/' . $b->gambar) : null,
            'preview'  => Str::limit(strip_tags($b->konten), 100),
            'konten'   => $b->konten,
        ])->values();
    @endphp

    <div id="berita-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">

        @forelse($beritas as $berita)
        <div class="news-card fade-in-up bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col"
             data-judul="{{ strtolower($berita->judul) }}">

            <div class="relative aspect-video overflow-hidden bg-slate-100">
                @if($berita->gambar)
                    <img src="{{ asset('img/' . $berita->gambar) }}" alt="{{ $berita->judul }}" class="card-img w-full h-full object-cover"/>
                @else
                    <div class="no-img-placeholder w-full h-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent pointer-events-none"></div>
                <div class="absolute top-3 left-3 bg-white/95 px-2.5 py-1 rounded-lg shadow-sm flex items-center gap-1.5">
                    <svg class="h-3 w-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wide">{{ $berita->created_at->format('d M Y') }}</span>
                </div>
            </div>

            <div class="p-4 flex flex-col flex-1 gap-1.5">
                <h2 class="text-sm font-bold text-slate-800 line-clamp-2 leading-snug">{{ $berita->judul }}</h2>
                <p class="text-[11px] text-slate-400">Oleh: <span class="font-semibold text-blue-500">{{ $berita->penulis }}</span></p>
                <p class="text-xs text-slate-500 line-clamp-3 flex-1 leading-relaxed mt-0.5">{{ Str::limit(strip_tags($berita->konten), 100) }}</p>

                <button
                    class="btn-detail mt-3 w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold py-2.5 rounded-xl transition-all active:scale-95 flex items-center justify-center gap-2 shadow-sm shadow-blue-200"
                    data-id="{{ $berita->id }}">
                    Baca Selengkapnya
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </button>
            </div>
        </div>

        @empty
        {{-- FIX: Empty state hanya muncul kalau benar-benar tidak ada data --}}
        <div class="col-span-full flex flex-col items-center justify-center py-16 bg-white rounded-2xl border border-slate-100">
            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-slate-600 font-bold text-sm">Belum ada berita tersedia</p>
        </div>
        @endforelse

    </div>

    {{-- FIX: Empty state saat hasil pencarian kosong --}}
    <div id="no-result" class="hidden flex-col items-center justify-center py-16 bg-white rounded-2xl border border-slate-100 mt-4">
        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-4">
            <svg class="h-8 w-8 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <p class="text-slate-600 font-bold text-sm">Berita tidak ditemukan</p>
        <p class="text-slate-400 text-xs mt-1">Coba kata kunci yang berbeda</p>
    </div>
</div>

{{-- ══════ MODAL ══════ --}}
<div id="newsModal">
    <div id="newsModalContent" class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">

        {{-- Gambar header modal --}}
        <div id="modalImageWrap" class="relative shrink-0">
            <img id="modalImage" src="" alt="" class="w-full h-52 object-cover bg-slate-100"/>
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent pointer-events-none"></div>
            <button onclick="closeModal()"
                class="absolute top-3 right-3 bg-white/90 hover:bg-white active:scale-95 p-2 rounded-full shadow-lg transition-all">
                <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Tombol close saat tidak ada gambar --}}
        <div id="modalCloseFallback" class="hidden shrink-0 flex justify-end px-5 pt-4">
            <button onclick="closeModal()"
                class="bg-slate-100 hover:bg-slate-200 active:scale-95 p-2 rounded-full transition-all">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-5 pb-6 pt-4 overflow-y-auto modal-scroll flex flex-col gap-3 flex-1">
            <h3 id="modalTitle" class="text-lg font-extrabold text-slate-800 leading-snug"></h3>
            <div class="flex flex-wrap items-center gap-2 text-[11px]">
                <span id="modalPenulis" class="bg-blue-50 text-blue-600 font-bold px-2.5 py-1 rounded-lg border border-blue-100"></span>
                <span id="modalDate" class="bg-slate-50 text-slate-500 font-semibold px-2.5 py-1 rounded-lg border border-slate-100"></span>
            </div>
            <hr class="border-slate-100"/>
            <div id="modalContent" class="text-sm text-slate-600 leading-relaxed space-y-3 pb-2"></div>
        </div>
    </div>
</div>

{{-- ══════ JAVASCRIPT ══════ --}}
<script>
    // Data berita dari server
    const beritaData = @json($beritaJson);

    // ── SEARCH ──────────────────────────────────────────────────────────────
    const searchInput  = document.getElementById('searchInput');
    const cards        = document.querySelectorAll('.news-card');
    const noResult     = document.getElementById('no-result');

    searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase().trim();
        let visibleCount = 0;

        cards.forEach(card => {
            const judul = card.dataset.judul || '';
            const match = judul.includes(keyword);
            card.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        // Tampilkan/sembunyikan empty state pencarian
        if (visibleCount === 0 && keyword !== '') {
            noResult.classList.remove('hidden');
            noResult.classList.add('flex');
        } else {
            noResult.classList.add('hidden');
            noResult.classList.remove('flex');
        }
    });

    // ── MODAL ───────────────────────────────────────────────────────────────
    const modal           = document.getElementById('newsModal');
    const modalImage      = document.getElementById('modalImage');
    const modalImageWrap  = document.getElementById('modalImageWrap');
    const modalCloseFB    = document.getElementById('modalCloseFallback');
    const modalTitle      = document.getElementById('modalTitle');
    const modalPenulis    = document.getElementById('modalPenulis');
    const modalDate       = document.getElementById('modalDate');
    const modalContent    = document.getElementById('modalContent');

    function openModal(id) {
        const item = beritaData.find(b => b.id == id);
        if (!item) return;

        modalTitle.textContent   = item.judul;
        modalPenulis.textContent = 'Oleh: ' + item.penulis;
        modalDate.textContent    = item.tanggal;
        modalContent.innerHTML   = item.konten; // render HTML dari konten

        if (item.gambar) {
            modalImage.src            = item.gambar;
            modalImage.alt            = item.judul;
            modalImageWrap.classList.remove('hidden');
            modalCloseFB.classList.add('hidden');
        } else {
            modalImageWrap.classList.add('hidden');
            modalCloseFB.classList.remove('hidden');
            modalCloseFB.classList.add('flex');
        }

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Tutup modal saat klik backdrop
    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });

    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });

    // Pasang event listener ke semua tombol "Baca Selengkapnya"
    document.querySelectorAll('.btn-detail').forEach(btn => {
        btn.addEventListener('click', function () {
            openModal(this.dataset.id);
        });
    });
</script>
@endsection