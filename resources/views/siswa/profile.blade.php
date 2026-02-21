@extends('layouts.siswaNav')

@section('content')
<div class="p-6 max-w-5xl mx-auto">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-900 to-blue-600 text-white p-6 rounded-xl shadow-lg mb-6" data-aos="fade-down">
        <h1 class="text-3xl font-bold mb-1">👤 Profil Siswa</h1>
        <p class="opacity-90 text-sm">Lihat dan kelola informasi pribadi kamu di sini.</p>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-2xl shadow-xl p-6 md:flex gap-6 items-start border-2 border-purple-200 hover:shadow-2xl transition duration-300" data-aos="fade-up">
        
        <!-- Foto Profil -->
        <div class="flex-shrink-0 mb-4 md:mb-0">
            @if($user->foto)
                <img src="{{ asset('img/' . $user->foto) }}" alt="Foto Profil"
                     class="w-40 h-40 rounded-full object-cover border-4 border-blue-600 shadow-md hover:scale-105 transition duration-300">
            @else
                <div class="w-40 h-40 rounded-full bg-gradient-to-br from-gray-300 to-gray-100 flex items-center justify-center text-gray-500 text-3xl font-bold border-4 border-gray-300 shadow-md">
                    ?
                </div>
            @endif
        </div>

        <!-- Informasi Profil -->
        <div class="flex-1 space-y-4 text-gray-800">
            <div>
                <h2 class="text-2xl font-bold text-indigo-700 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.21 0 4.296.536 6.121 1.486M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $user->name }}
                </h2>
                <div class="mt-2 text-sm space-y-1">
                    <p class="flex items-center gap-1">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-4a3 3 0 016 0v4" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 20h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z" />
                        </svg>
                        NIS: {{ $user->nis }}
                    </p>
                    <p class="flex items-center gap-1">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12.083 12.083 0 0112 21.5 12.083 12.083 0 015.84 10.578L12 14z" />
                        </svg>
                        Kelas: {{ $user->kelas }}
                    </p>
                    <p class="flex items-center gap-1">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                        Jurusan: {{ $user->jurusan }}
                    </p>
                    <p class="flex items-center gap-1">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 01-8 0m8 0a4 4 0 00-8 0m8 0a4 4 0 01-8 0" />
                        </svg>
                        Email: {{ $user->email }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 text-sm">
                <div>
                    <p class="text-gray-500 font-semibold">👫 Jenis Kelamin</p>
                    <p class="text-gray-800">{{ ucfirst($user->jenis_kelamin) }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-semibold">📞 Nomor HP</p>
                    <p class="text-gray-800">{{ $user->nohp }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-500 font-semibold">🎂 Tanggal Lahir</p>
                    <p class="text-gray-800">{{ $user->tempat_lahir }}, {{ \Carbon\Carbon::parse($user->tanggal_lahir)->format('d M Y') }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-500 font-semibold">🏠 Alamat</p>
                    <p class="text-gray-800">{{ $user->alamat }}</p>
                </div>
            </div>

            <div class="mt-6" data-aos="zoom-in">
                <div id="openModalBtn" class="inline-block bg-gradient-to-r from-blue-900 to-blue-600 hover:from-indigo-700 hover:to-pink-600 text-white cursor-pointer px-6 py-2 rounded-full shadow-md hover:shadow-lg transition duration-300">
                    ✏️ Edit Profil
                </div>
            </div>
        </div>
    </div>
    
</div>

<!-- Modal Edit Profil -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-md transform transition-all duration-300 scale-95 opacity-0 modal-content relative">

        <h2 class="text-xl font-bold mb-4 text-indigo-700">Edit Profil</h2>

        <form action="{{ route('siswa.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- Karena route kamu pakai method PUT --}}


        <!-- Foto Profil -->
        <div class="mb-4 text-center">
            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>

            <!-- Preview Foto Lama -->
            <div class="flex justify-center mb-3">
                <img 
                    src="{{ asset('img/' . Auth::user()->foto) }}" 
                    alt="Foto Profil" 
                    class="w-24 h-24 rounded-full object-cover border-2 border-gray-300 shadow-sm"
                    id="fotoPreview">
            </div>

            <!-- Input File -->
            <input 
                type="file" 
                name="foto" 
                class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50"
                onchange="previewFoto(event)">
        </div>


            <!-- Nama -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" value="{{ Auth::user()->name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 " readonly>
            </div>

            <!-- No HP -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">No HP</label>
                <input type="text" name="nohp"value="{{ old('nohp', Auth::user()->nohp) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <!-- Jenis Kelamin -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                <input type="text" name="jenis_kelamin" value="{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100" readonly>
            </div>

            <!-- Alamat -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea name="alamat" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ Auth::user()->alamat }}</textarea>
            </div>

            <!-- Submit -->
            <div class="text-right">

                <button type="button" id="closeModalBtn" class="inline-block bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded mr-2">
                    Batal
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded">
                    Simpan Perubahan
                </button>

            </div>
        </form>
    </div>
</div>


<script>

        const modal = document.getElementById('editModal');
    const modalContent = modal.querySelector('.modal-content');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');

    openBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');

        // Mulai animasi muncul
        setTimeout(() => {
            modalContent.classList.remove('opacity-0', 'scale-95');
            modalContent.classList.add('opacity-100', 'scale-100');
        }, 10);
    });

    closeBtn.addEventListener('click', () => {
        // Mulai animasi keluar
        modalContent.classList.remove('opacity-100', 'scale-100');
        modalContent.classList.add('opacity-0', 'scale-95');

        // Sembunyikan setelah animasi selesai
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300); // sesuai durasi
    });
    function previewFoto(event) {
        const foto = event.target.files[0];
        const preview = document.getElementById('fotoPreview');

        if (foto) {
            preview.src = URL.createObjectURL(foto);
        }
    }
</script>


@endsection
