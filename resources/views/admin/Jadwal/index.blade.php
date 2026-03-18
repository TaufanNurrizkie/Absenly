@extends('layouts.adminNav')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Kelola Jadwal Pelajaran</h3>
            <p class="text-sm text-gray-500 mt-1">Upload dan kelola gambar jadwal pelajaran</p>
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
            <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Jadwal" untuk mulai upload gambar jadwal.</p>
        </div>

    @else

    {{-- GRID JADWAL --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($jadwals as $jadwal)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">

            {{-- Gambar --}}
            <a href="{{ asset('storage/' . $jadwal->gambar) }}" target="_blank" class="block overflow-hidden">
                <img src="{{ asset('storage/' . $jadwal->gambar) }}"
                     class="w-full h-52 object-cover hover:scale-105 transition-transform duration-300"
                     alt="{{ $jadwal->judul }}">
            </a>

            {{-- Judul --}}
            <div class="px-4 pt-3 pb-1">
                <h6 class="font-semibold text-gray-800 text-sm truncate">{{ $jadwal->judul }}</h6>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2 p-3 mt-auto">

                {{-- GANTI --}}
                <button onclick="document.getElementById('editModal{{ $jadwal->id }}').classList.remove('hidden')"
                        class="flex-1 flex items-center justify-center gap-1 bg-yellow-400 hover:bg-yellow-500 text-white text-sm font-medium py-2 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </button>

                {{-- HAPUS --}}
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

                        {{-- Input Judul --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Judul <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="judul" value="{{ $jadwal->judul }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Contoh: Jadwal Semester Ganjil 2024" required>
                        </div>

                        {{-- Gambar saat ini --}}
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Gambar saat ini:</p>
                            <img src="{{ asset('storage/' . $jadwal->gambar) }}"
                                 class="w-full max-h-48 object-contain rounded-lg border border-gray-200">
                        </div>

                        {{-- Ganti Gambar (opsional) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Ganti Gambar <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <input type="file" name="gambar"
                                   class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-600 file:font-medium hover:file:bg-blue-100 border border-gray-200 rounded-lg cursor-pointer"
                                   accept="image/jpg,image/jpeg,image/png,image/webp">
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 5MB.</p>
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

                {{-- Input Judul --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Contoh: Jadwal Semester Ganjil 2024" required>
                </div>

                {{-- Upload Gambar --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Gambar Jadwal <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="gambar" id="previewInput"
                           class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-600 file:font-medium hover:file:bg-blue-100 border border-gray-200 rounded-lg cursor-pointer"
                           accept="image/jpg,image/jpeg,image/png,image/webp" required>
                    <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 5MB.</p>
                </div>

                {{-- Preview --}}
                <div id="previewWrapper" class="hidden">
                    <p class="text-xs text-gray-500 mb-1">Preview:</p>
                    <img id="previewImg" src="#"
                         class="w-full max-h-48 object-contain rounded-lg border border-gray-200">
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
    document.getElementById('previewInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (ev) {
            document.getElementById('previewImg').src = ev.target.result;
            document.getElementById('previewWrapper').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });

    document.querySelectorAll('[id^="editModal"], #tambahModal').forEach(modal => {
        modal.addEventListener('click', function (e) {
            if (e.target === this) this.classList.add('hidden');
        });
    });
</script>
@endpush

@endsection