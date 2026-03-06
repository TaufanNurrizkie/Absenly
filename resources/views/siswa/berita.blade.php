@extends('layouts.siswaNav')

@section('content')

<style>
    /* Animation Keyframes */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .fade-in-up { animation: fadeInUp 0.5s ease forwards; }
    
    /* Stagger animation for cards */
    .news-card:nth-child(1) { animation-delay: 0.05s; }
    .news-card:nth-child(2) { animation-delay: 0.1s; }
    .news-card:nth-child(3) { animation-delay: 0.15s; }
    .news-card:nth-child(4) { animation-delay: 0.2s; }
    .news-card:nth-child(5) { animation-delay: 0.25s; }
    .news-card:nth-child(6) { animation-delay: 0.3s; }

    /* Line clamp for text truncation */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<!-- Header Section -->
<div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-b-[2.5rem] p-6 pt-8 pb-10 relative overflow-hidden shadow-lg">
    <!-- Decorative Shapes -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32 blur-2xl"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-indigo-500/20 rounded-full -ml-24 -mb-24 blur-xl"></div>

    <div class="relative z-10 max-w-lg mx-auto">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-white/20 p-2 rounded-xl backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight">Berita Sekolah</h1>
                <p class="text-sm text-blue-100 opacity-90">Update informasi terbaru seputar sekolah.</p>
            </div>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('siswa.berita') }}" class="relative">
            <input
                id="search"
                class="w-full bg-white/20 backdrop-blur-md border border-white/30 text-white placeholder-white/70 rounded-xl pl-4 pr-12 py-3 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all"
                autocomplete="off"
                placeholder="Cari judul berita..."
                name="search"
                type="text"
            />
            <button
                type="submit"
                class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/30 p-2 rounded-lg transition-colors"
                aria-label="Search"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>
</div>

<!-- News Content -->
<div class="px-4 -mt-4 relative z-20">
    <div id="berita-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($beritas as $berita)
            <!-- News Card -->
            <div class="news-card fade-in-up opacity-0 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-blue-100 transition-all duration-300 overflow-hidden flex flex-col group">
                
                <!-- Image Container -->
                <div class="relative aspect-video bg-slate-100 overflow-hidden">
                    @if ($berita->gambar)
                        <img src="{{ asset('img/' . $berita->gambar) }}"
                             alt="{{ $berita->judul }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-50">
                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif

                    <!-- Date Badge -->
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-lg shadow-sm border border-slate-100 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-[10px] font-bold text-slate-600 uppercase">{{ $berita->created_at->format('d M') }}</span>
                    </div>
                </div>

                <!-- Content Body -->
                <div class="p-5 flex flex-col flex-1">
                    <h2 class="text-base font-bold text-slate-800 line-clamp-2 mb-1 leading-snug">
                        {{ $berita->judul }}
                    </h2>
                    <p class="text-xs text-slate-400 mb-3">
                        Oleh: <span class="font-semibold text-slate-500">{{ $berita->penulis }}</span>
                    </p>
                    <p class="text-sm text-slate-500 line-clamp-2 flex-1 leading-relaxed">
                        {{ Str::limit(strip_tags($berita->konten), 80) }}
                    </p>

                    <button onclick="openDetailModal(`{{ addslashes($berita->judul) }}`, `{{ addslashes(nl2br(e($berita->konten))) }}`, '{{ $berita->created_at->format('d M Y') }}', '{{ $berita->gambar ? asset('img/' . $berita->gambar) : '' }}')"
                            class="mt-4 w-full bg-blue-50 hover:bg-blue-100 text-blue-600 font-semibold py-2.5 rounded-xl transition-colors duration-200 flex items-center justify-center gap-2 active:scale-95">
                        <span class="text-sm">Baca Selengkapnya</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-12 bg-white rounded-2xl border border-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-slate-400 font-medium text-sm">Belum ada berita tersedia.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Single Dynamic Modal -->
<div id="newsModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="newsModalContent">
        
        <!-- Modal Header -->
        <div class="relative">
            <img id="modalImage" src="" class="w-full h-48 object-cover bg-slate-100">
            <div class="absolute top-3 right-3">
                <button onclick="closeNewsModal()" class="bg-white/90 hover:bg-white p-1.5 rounded-full shadow-md transition-colors">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 max-h-[60vh] overflow-y-auto">
            <h3 id="modalTitle" class="text-xl font-bold text-slate-800 mb-2 pr-8"></h3>
            <p id="modalDate" class="text-xs text-slate-400 font-medium mb-4 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span></span>
            </p>
            <div id="modalContent" class="text-sm text-slate-600 leading-relaxed space-y-3"></div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // --- Modal Functions ---
    function openDetailModal(title, content, date, image) {
        const modal = document.getElementById('newsModal');
        const modalContent = document.getElementById('newsModalContent');
        
        // Set content
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalContent').innerHTML = content;
        document.getElementById('modalDate').querySelector('span').textContent = date;
        
        const imgEl = document.getElementById('modalImage');
        if (image) {
            imgEl.src = image;
            imgEl.classList.remove('hidden');
        } else {
            imgEl.classList.add('hidden');
        }

        // Show modal with animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeNewsModal() {
        const modal = document.getElementById('newsModal');
        const modalContent = document.getElementById('newsModalContent');
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Close modal on backdrop click
    document.getElementById('newsModal').addEventListener('click', function(e) {
        if (e.target === this) closeNewsModal();
    });

    // --- Live Search AJAX ---
    $('#search').on('keyup', function () {
        let keyword = $(this).val();

        $.ajax({
            url: "{{ route('siswa.berita.search') }}",
            method: "GET",
            data: { search: keyword },
            success: function (data) {
                let container = $('#berita-container');
                container.empty();

                if (data.length > 0) {
                    data.forEach(function (berita) {
                        // Create safe strings for JS
                        const safeTitle = berita.judul.replace(/`/g, "\\`");
                        const safeContent = berita.konten.substring(0, 150).replace(/`/g, "\\`");
                        const imgSrc = berita.gambar ? `/img/${berita.gambar}` : '';
                        
                        // Generate HTML (Matching the design)
                        let html = `
                        <div class="news-card bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-blue-100 transition-all duration-300 overflow-hidden flex flex-col group">
                            <div class="relative aspect-video bg-slate-100 overflow-hidden">
                                ${berita.gambar 
                                    ? `<img src="/img/${berita.gambar}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">` 
                                    : `<div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-50">
                                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>`}
                                
                                <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-lg shadow-sm border border-slate-100 flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-[10px] font-bold text-slate-600 uppercase">${new Date(berita.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })}</span>
                                </div>
                            </div>

                            <div class="p-5 flex flex-col flex-1">
                                <h2 class="text-base font-bold text-slate-800 line-clamp-2 mb-1 leading-snug">${berita.judul}</h2>
                                <p class="text-xs text-slate-400 mb-3">Oleh: <span class="font-semibold text-slate-500">${berita.penulis}</span></p>
                                <p class="text-sm text-slate-500 line-clamp-2 flex-1 leading-relaxed">${safeContent}...</p>
                                
                                <button onclick="openDetailModal(\`${safeTitle}\`, \`${berita.konten}\`, '${new Date(berita.created_at).toLocaleDateString('id-ID')}', '${imgSrc}')"
                                        class="mt-4 w-full bg-blue-50 hover:bg-blue-100 text-blue-600 font-semibold py-2.5 rounded-xl transition-colors duration-200 flex items-center justify-center gap-2 active:scale-95">
                                    <span class="text-sm">Baca Selengkapnya</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        `;
                        container.append(html);
                    });
                } else {
                    container.html(`
                        <div class="col-span-full flex flex-col items-center justify-center py-12 bg-white rounded-2xl border border-slate-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <p class="text-slate-400 font-medium text-sm">Berita tidak ditemukan.</p>
                        </div>
                    `);
                }
            }
        });
    });
</script>
@endpush