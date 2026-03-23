@extends('layouts.adminNav')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Kelola Jadwal Pelajaran</h3>
            <p class="text-sm text-gray-500 mt-1">Upload dan kelola jadwal pelajaran (Gambar, PDF, Excel)</p>
        </div>
        <button onclick="document.getElementById('tambahModal').classList.remove('hidden')"
                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Jadwal
        </button>
    </div>

    {{-- ALERT SUCCESS --}}
    @if (session('success'))
        <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ALERT ERROR --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-4">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- EMPTY STATE --}}
    @if ($jadwals->isEmpty())
        <div class="text-center py-20">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h5 class="text-gray-400 font-medium">Belum ada jadwal</h5>
            <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Jadwal" untuk mulai upload.</p>
        </div>

    @else

    {{-- GRID JADWAL --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($jadwals as $jadwal)
        @php
            $ext  = strtolower(pathinfo($jadwal->gambar, PATHINFO_EXTENSION));
            $tipe = match($ext) {
                'pdf'         => 'pdf',
                'xlsx', 'xls' => 'excel',
                default       => 'gambar',
            };
            $fileUrl = asset('storage/' . $jadwal->gambar);
            $badge = match($tipe) {
                'pdf'   => ['bg' => 'bg-red-100',   'text' => 'text-red-600',   'label' => 'PDF'],
                'excel' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Excel'],
                default => ['bg' => 'bg-blue-100',  'text' => 'text-blue-700',  'label' => 'Gambar'],
            };
        @endphp

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">

            {{-- Preview area --}}
            @if($tipe === 'gambar')
                <a href="{{ $fileUrl }}" target="_blank" class="block overflow-hidden">
                    <img src="{{ $fileUrl }}"
                         class="w-full h-52 object-cover hover:scale-105 transition-transform duration-300"
                         alt="{{ $jadwal->judul }}">
                </a>

            @elseif($tipe === 'pdf')
                <a href="{{ $fileUrl }}" target="_blank"
                   class="flex flex-col items-center justify-center h-52 bg-red-50 hover:bg-red-100 transition-colors gap-3">
                    <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-semibold text-red-600">File PDF</p>
                        <p class="text-xs text-red-400 mt-0.5">Klik untuk buka di tab baru</p>
                    </div>
                </a>

            @elseif($tipe === 'excel')
                <div class="flex flex-col items-center justify-center h-52 bg-green-50 gap-3">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18M10 3v18M3 3h18v18H3z"/>
                        </svg>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-semibold text-green-700">File Excel</p>
                        <a href="{{ $fileUrl }}" download
                           class="inline-flex items-center gap-1.5 mt-1 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Unduh
                        </a>
                    </div>
                </div>
            @endif

            {{-- Judul + badge tipe --}}
            <div class="px-4 pt-3 pb-1 flex items-center gap-2">
                <h6 class="font-semibold text-gray-800 text-sm truncate flex-1">{{ $jadwal->judul }}</h6>
                <span class="flex-shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-md {{ $badge['bg'] }} {{ $badge['text'] }}">
                    {{ $badge['label'] }}
                </span>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2 p-3 mt-auto">
                <button onclick="document.getElementById('editModal{{ $jadwal->id }}').classList.remove('hidden')"
                        class="flex-1 flex items-center justify-center gap-1 bg-yellow-400 hover:bg-yellow-500 text-white text-sm font-medium py-2 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </button>
                <form action="{{ route('admin.jadwal.delete', $jadwal->id) }}" method="POST" class="flex-1"
                      onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-1 bg-red-500 hover:bg-red-600 text-white text-sm font-medium py-2 rounded-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        {{-- MODAL EDIT --}}
        <div id="editModal{{ $jadwal->id }}"
             class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
                <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="flex items-center justify-between px-6 py-4 border-b">
                        <h5 class="font-semibold text-gray-800">Edit Jadwal</h5>
                        <button type="button"
                                onclick="document.getElementById('editModal{{ $jadwal->id }}').classList.add('hidden')"
                                class="text-gray-400 hover:text-gray-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="px-6 py-4 space-y-4">

                        {{-- Judul --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Judul <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="judul" value="{{ $jadwal->judul }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Contoh: Jadwal Semester Ganjil 2024" required>
                        </div>

                        {{-- File saat ini --}}
                        <div>
                            <p class="text-xs text-gray-500 mb-2">File saat ini:
                                <span class="font-semibold {{ $badge['text'] }}">{{ $badge['label'] }}</span>
                            </p>
                            @if($tipe === 'gambar')
                                <img src="{{ $fileUrl }}"
                                     class="w-full max-h-40 object-contain rounded-lg border border-gray-200">
                            @elseif($tipe === 'pdf')
                                <div class="flex items-center gap-3 bg-red-50 border border-red-100 rounded-lg px-4 py-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-sm text-gray-600 truncate flex-1">{{ basename($jadwal->gambar) }}</span>
                                    <a href="{{ $fileUrl }}" target="_blank"
                                       class="text-xs text-red-500 font-semibold hover:underline flex-shrink-0">Buka</a>
                                </div>
                            @elseif($tipe === 'excel')
                                <div class="flex items-center gap-3 bg-green-50 border border-green-100 rounded-lg px-4 py-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18M10 3v18M3 3h18v18H3z"/>
                                    </svg>
                                    <span class="text-sm text-gray-600 truncate flex-1">{{ basename($jadwal->gambar) }}</span>
                                    <a href="{{ $fileUrl }}" download
                                       class="text-xs text-green-600 font-semibold hover:underline flex-shrink-0">Unduh</a>
                                </div>
                            @endif
                        </div>

                        {{-- Ganti File --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Ganti File <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <input type="file" name="gambar" id="editFileInput{{ $jadwal->id }}"
                                   onchange="handleEditPreview(this, {{ $jadwal->id }})"
                                   class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-600 file:font-medium hover:file:bg-blue-100 border border-gray-200 rounded-lg cursor-pointer"
                                   accept="image/jpg,image/jpeg,image/png,image/webp,application/pdf,.xlsx,.xls">
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WEBP, PDF, XLSX, XLS. Maks 10MB.</p>

                            {{-- Preview baru --}}
                            <div id="editPreview{{ $jadwal->id }}" class="hidden mt-3">
                                <p class="text-xs text-gray-500 mb-1">Preview file baru:</p>
                                <img id="editPreviewImg{{ $jadwal->id }}" src="#"
                                     class="w-full max-h-40 object-contain rounded-lg border border-gray-200 hidden">
                                <div id="editPreviewFile{{ $jadwal->id }}"
                                     class="hidden items-center gap-3 bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <span id="editPreviewFileName{{ $jadwal->id }}" class="text-sm text-gray-600 truncate"></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="flex gap-2 px-6 py-4 border-t">
                        <button type="button"
                                onclick="document.getElementById('editModal{{ $jadwal->id }}').classList.add('hidden')"
                                class="flex-1 py-2 rounded-lg border border-gray-300 text-gray-600 text-sm font-medium hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @endforeach
    </div>
    @endif

</div>

{{-- MODAL TAMBAH --}}
<div id="tambahModal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
        <form action="{{ route('admin.jadwal.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h5 class="font-semibold text-gray-800">Upload Jadwal Baru</h5>
                <button type="button"
                        onclick="document.getElementById('tambahModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="px-6 py-4 space-y-4">

                {{-- Judul --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Contoh: Jadwal Semester Ganjil 2024" required>
                </div>

                {{-- Upload File --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        File Jadwal <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="gambar" id="tambahFileInput"
                           onchange="handleTambahPreview(this)"
                           class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-600 file:font-medium hover:file:bg-blue-100 border border-gray-200 rounded-lg cursor-pointer"
                           accept="image/jpg,image/jpeg,image/png,image/webp,application/pdf,.xlsx,.xls"
                           required>
                    <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WEBP, PDF, XLSX, XLS. Maks 10MB.</p>
                </div>

                {{-- Preview --}}
                <div id="tambahPreview" class="hidden">
                    <p class="text-xs text-gray-500 mb-1">Preview:</p>
                    {{-- Gambar --}}
                    <img id="tambahPreviewImg" src="#"
                         class="w-full max-h-48 object-contain rounded-lg border border-gray-200 hidden">
                    {{-- PDF / Excel --}}
                    <div id="tambahPreviewFile"
                         class="hidden items-center gap-3 bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span id="tambahPreviewFileName" class="text-sm text-gray-600 truncate flex-1"></span>
                        <span id="tambahPreviewFileBadge"
                              class="flex-shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-md"></span>
                    </div>
                </div>

            </div>

            <div class="flex gap-2 px-6 py-4 border-t">
                <button type="button"
                        onclick="document.getElementById('tambahModal').classList.add('hidden')"
                        class="flex-1 py-2 rounded-lg border border-gray-300 text-gray-600 text-sm font-medium hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition">
                    Upload & Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // ── Deteksi tipe file dari ekstensi ──
    function getFileTipe(file) {
        const ext = file.name.split('.').pop().toLowerCase();
        if (['xlsx', 'xls'].includes(ext)) return 'excel';
        if (ext === 'pdf') return 'pdf';
        return 'gambar';
    }

    // ── Preview modal Tambah ──
    function handleTambahPreview(input) {
        const file = input.files[0];
        if (!file) return;

        const tipe = getFileTipe(file);
        const previewWrap = document.getElementById('tambahPreview');
        const previewImg  = document.getElementById('tambahPreviewImg');
        const previewFile = document.getElementById('tambahPreviewFile');
        const previewName = document.getElementById('tambahPreviewFileName');
        const previewBadge = document.getElementById('tambahPreviewFileBadge');

        previewWrap.classList.remove('hidden');

        if (tipe === 'gambar') {
            const reader = new FileReader();
            reader.onload = ev => { previewImg.src = ev.target.result; };
            reader.readAsDataURL(file);
            previewImg.classList.remove('hidden');
            previewFile.classList.add('hidden');
            previewFile.classList.remove('flex');
        } else {
            previewImg.classList.add('hidden');
            previewName.textContent = file.name;

            if (tipe === 'pdf') {
                previewBadge.textContent = 'PDF';
                previewBadge.className = 'flex-shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-md bg-red-100 text-red-600';
            } else {
                previewBadge.textContent = 'Excel';
                previewBadge.className = 'flex-shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-md bg-green-100 text-green-700';
            }

            previewFile.classList.remove('hidden');
            previewFile.classList.add('flex');
        }
    }

    // ── Preview modal Edit ──
    function handleEditPreview(input, id) {
        const file = input.files[0];
        if (!file) return;

        const tipe = getFileTipe(file);
        const previewWrap = document.getElementById('editPreview' + id);
        const previewImg  = document.getElementById('editPreviewImg' + id);
        const previewFile = document.getElementById('editPreviewFile' + id);
        const previewName = document.getElementById('editPreviewFileName' + id);

        previewWrap.classList.remove('hidden');

        if (tipe === 'gambar') {
            const reader = new FileReader();
            reader.onload = ev => { previewImg.src = ev.target.result; };
            reader.readAsDataURL(file);
            previewImg.classList.remove('hidden');
            previewFile.classList.add('hidden');
            previewFile.classList.remove('flex');
        } else {
            previewImg.classList.add('hidden');
            previewName.textContent = file.name;
            previewFile.classList.remove('hidden');
            previewFile.classList.add('flex');
        }
    }

    // ── Tutup modal klik backdrop ──
    document.querySelectorAll('[id^="editModal"], #tambahModal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) this.classList.add('hidden');
        });
    });
</script>
@endpush

@endsection