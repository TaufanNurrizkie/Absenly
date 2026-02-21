@extends('layouts.siswaNav')

@section('content')

<style>
    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease forwards;
    }
</style>

    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-b-xl p-6 mb-6 shadow-md">
        <h1 class="text-2xl font-bold mb-2">📰 Berita & Informasi</h1>
        <p class="text-sm opacity-90 mb-4">Dapatkan informasi terbaru seputar kegiatan sekolahmu!</p>
      
        <!-- Search Input Group -->
        <form method="GET" action="{{ route('siswa.berita') }}" class="flex items-center gap-2">
          <input
            id="search"
            class="flex-1 bg-zinc-200 text-zinc-600 font-mono ring-1 ring-zinc-400 focus:ring-2 focus:ring-rose-400 outline-none duration-300 placeholder:text-zinc-600 placeholder:opacity-50 rounded-full px-4 py-2 shadow-md focus:shadow-lg focus:shadow-rose-400 dark:shadow-md dark:shadow-purple-500"
            autocomplete="off"
            placeholder="Search here..."
            name="search"
            type="text"
          />
          <button
            type="submit"
            class="bg-zinc-200 text-zinc-600 p-2 rounded-full ring-1 ring-zinc-400 hover:ring-2 hover:ring-rose-400 shadow-md hover:shadow-lg transition duration-300"
            aria-label="Search"
          >
            <img src="{{ asset('img/roket.png') }}" alt="Search Icon" class="w-5 h-5 ">
          </button>
        </form>
      </div>
      

      <div class="p-4">   

<!-- Berita Cards -->
<div id="berita-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($beritas as $berita)
        <div class="bg-white rounded-2xl shadow-md hover:shadow-indigo-300 hover:scale-[1.02] transition-all duration-300 overflow-hidden relative group fade-in-up">

            
            <!-- Gambar -->
            @if ($berita->gambar)
                <img src="{{ asset('img/' . $berita->gambar) }}"
                     alt="{{ $berita->judul }}"
                     class="w-full h-44 object-cover">
            @else
                <div class="w-full h-44 bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                    Tidak ada gambar
                </div>
            @endif

            <!-- Isi -->
            <div class="p-5">
                <h2 class="text-lg font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors duration-200">
                    {{ $berita->judul }}
                </h2>
                <h2 class="text-sm font-semibold text-gray-800">
                    {{ $berita->penulis }}
                </h2>
                <p class="text-sm text-gray-600 mt-1 line-clamp-3  leading-relaxed whitespace-normal break-words">
                    {{ Str::limit(strip_tags($berita->konten), 100) }}
                </p>
                <button
                onclick="showModal({{ $berita->id }})"
                class="relative flex items-center px-5 py-2.5 overflow-hidden font-medium transition-all bg-indigo-500 rounded-md group mt-4"
              >
                <span
                  class="absolute top-0 right-0 inline-block w-4 h-4 transition-all duration-500 ease-in-out bg-indigo-700 rounded group-hover:-mr-4 group-hover:-mt-4"
                >
                  <span
                    class="absolute top-0 right-0 w-5 h-5 rotate-45 translate-x-1/2 -translate-y-1/2 bg-white"
                  ></span>
                </span>
                <span
                  class="absolute bottom-0 rotate-180 left-0 inline-block w-4 h-4 transition-all duration-500 ease-in-out bg-indigo-700 rounded group-hover:-ml-4 group-hover:-mb-4"
                >
                  <span
                    class="absolute top-0 right-0 w-5 h-5 rotate-45 translate-x-1/2 -translate-y-1/2 bg-white"
                  ></span>
                </span>
                <span
                  class="absolute bottom-0 left-0 w-full h-full transition-all duration-500 ease-in-out delay-200 -translate-x-full bg-indigo-600 rounded-md group-hover:translate-x-0"
                ></span>
                <span
                  class="relative w-full text-center text-white transition-colors duration-200 ease-in-out group-hover:text-white"
                >
                  Baca Selengkapnya
                </span>
              </button>
              
            </div>

            <!-- Tanggal -->
            <div class="absolute top-3 left-3 bg-white/90 px-3 py-1 rounded-full text-xs text-gray-600 shadow flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3M16 7V3M3 11h18M5 19h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z"/>
                </svg>
                {{ $berita->created_at->format('d M Y') }}
            </div>
        </div>

        <!-- Modal Berita -->
        <div id="modal-{{ $berita->id }}" class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center transition-opacity duration-300 ease-in-out">
            <div class="bg-white p-6 rounded-xl w-11/12 max-w-xl relative max-h-[80vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300 ease-in-out" id="modal-content-{{ $berita->id }}">
        
                <h3 class="text-xl font-bold mb-2">{{ $berita->judul }}</h3>
                <p class="text-sm text-gray-700 mb-3">{{ $berita->created_at->format('d M Y') }}</p>
                <div class="text-sm text-gray-700 leading-relaxed whitespace-normal break-words">
                    {!! nl2br(e($berita->konten)) !!}
                </div>
                <button onclick="closeModal({{ $berita->id }})" class="absolute top-2 right-3 text-gray-500 text-2xl font-bold hover:text-red-500">&times;</button>
            </div>
        </div>

    @empty
        <p class="text-center text-gray-500 col-span-full">Belum ada berita tersedia.</p>
    @endforelse
</div>

</div>

<!-- Modal Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function showModal(id) {
    const modal = document.getElementById('modal-' + id);
    const content = document.getElementById('modal-content-' + id);
    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeModal(id) {
    const modal = document.getElementById('modal-' + id);
    const content = document.getElementById('modal-content-' + id);
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}


    $('#search').on('keyup', function () {
        let keyword = $(this).val();

        $.ajax({
            url: "{{ route('siswa.berita.search') }}", // route yang mengembalikan JSON
            method: "GET",
            data: { search: keyword },
            success: function (data) {
                let container = $('#berita-container');
                container.empty();

                if (data.length > 0) {
                    data.forEach(function (berita) {
                        let html = `
                            <div class="bg-white rounded-2xl shadow-md hover:shadow-indigo-300 hover:scale-[1.02] transition-all duration-300 overflow-hidden relative group">
                                ${berita.gambar ? `<img src="/img/${berita.gambar}" class="w-full h-44 object-cover">` : `<div class="w-full h-44 bg-gray-100 flex items-center justify-center text-gray-400 text-sm">Tidak ada gambar</div>`}
                                <div class="p-5">
                                    <h2 class="text-lg font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors duration-200">${berita.judul}</h2>
                                    <h2 class="text-sm font-semibold text-gray-800">${berita.penulis}</h2>
                                    <p class="text-sm text-gray-600 mt-1">${berita.konten.substring(0, 150)}...</p>
                                    <button onclick="showModal(${berita.id})" class="relative flex items-center px-5 py-2.5 overflow-hidden font-medium transition-all bg-indigo-500 rounded-md group mt-4">
                                        <span class="absolute top-0 right-0 inline-block w-4 h-4 bg-indigo-700 rounded group-hover:-mr-4 group-hover:-mt-4"><span class="absolute top-0 right-0 w-5 h-5 rotate-45 translate-x-1/2 -translate-y-1/2 bg-white"></span></span>
                                        <span class="absolute bottom-0 rotate-180 left-0 inline-block w-4 h-4 bg-indigo-700 rounded group-hover:-ml-4 group-hover:-mb-4"><span class="absolute top-0 right-0 w-5 h-5 rotate-45 translate-x-1/2 -translate-y-1/2 bg-white"></span></span>
                                        <span class="absolute bottom-0 left-0 w-full h-full -translate-x-full bg-indigo-600 rounded-md group-hover:translate-x-0"></span>
                                        <span class="relative w-full text-center text-white group-hover:text-white">Baca Selengkapnya</span>
                                    </button>
                                </div>
                                <div class="absolute top-3 left-3 bg-white/90 px-3 py-1 rounded-full text-xs text-gray-600 shadow flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3M16 7V3M3 11h18M5 19h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z"/>
                                    </svg>
                                    ${new Date(berita.created_at).toLocaleDateString('id-ID')}
                                </div>
                            </div>
                        `;
                        container.append(html);
                    });
                } else {
                    container.html(`<p class="text-center text-gray-500 col-span-full">Berita tidak ditemukan.</p>`);
                }
            }
        });
    });
</script>
@endsection
