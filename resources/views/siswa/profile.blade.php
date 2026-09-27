@extends('layouts.siswaNav')

@section('content')
<div class="p-4 md:p-6 max-w-4xl mx-auto">

    <!-- Header Section -->
    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white p-6 rounded-2xl shadow-lg mb-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20 blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-indigo-500/20 rounded-full -ml-12 -mb-12 blur-xl"></div>
        
        <div class="relative z-10 flex items-center gap-4">
            <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm border border-white/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight">My Profile</h1>
                <p class="opacity-80 text-sm mt-1">View and manage your personal information.</p>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Profile Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:flex gap-8 items-start hover:shadow-md transition-all duration-300">
        
        <!-- Foto Profil -->
        <div class="flex-shrink-0 flex flex-col items-center mb-6 md:mb-0">
            <div class="relative">
                @if($user->foto)
                    <img src="{{ asset('img/' . $user->foto) }}" alt="Foto Profil"
                         class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg ring-4 ring-slate-100">
                @else
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-slate-200 to-slate-100 flex items-center justify-center text-slate-400 border-4 border-white shadow-lg ring-4 ring-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                @endif
                <div class="absolute bottom-1 right-1 w-5 h-5 bg-green-500 border-2 border-white rounded-full"></div>
            </div>
            
            <div class="mt-4 text-center">
                <h2 class="text-xl font-bold text-slate-800">{{ $user->name }}</h2>
                <p class="text-sm text-slate-500 font-medium">{{ $user->nis }}</p>
            </div>
        </div>

        <!-- Informasi Profil -->
        <div class="flex-1 w-full">
            
            <!-- Basic Info -->
            <div class="bg-slate-50 rounded-xl p-4 mb-6 border border-slate-100">
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Academic Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 p-2 rounded-lg">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Class</p>
                            <p class="text-sm font-bold text-slate-700">{{ $user->kelas->nama ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-indigo-100 p-2 rounded-lg">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Major</p>
                            <p class="text-sm font-bold text-slate-700">{{ $user->jurusan->nama ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 text-sm mb-6">
                
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <div>
                        <p class="text-xs text-slate-500">Email</p>
                        <p class="text-slate-700 font-medium">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <div>
                        <p class="text-xs text-slate-500">Phone Number</p>
                        <p class="text-slate-700 font-medium">{{ $user->nohp ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <div>
                        <p class="text-xs text-slate-500">Gender</p>
                        <p class="text-slate-700 font-medium">{{ $user->jenis_kelamin == 'L' ? 'Male' : 'Female' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" />
                    </svg>
                    <div>
                        <p class="text-xs text-slate-500">Date of Birth</p>
                        <p class="text-slate-700 font-medium">{{ $user->tempat_lahir }}, {{ \Carbon\Carbon::parse($user->tanggal_lahir)->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="md:col-span-2 flex items-start gap-3">
                    <svg class="w-5 h-5 text-slate-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <div>
                        <p class="text-xs text-slate-500">Address</p>
                        <p class="text-slate-700 font-medium">{{ $user->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="border-t border-slate-100 pt-6 flex justify-between items-center gap-3">
                <div>
                    <button type="button" id="backBtn" onclick="window.history.back()" class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 px-4 py-2.5 rounded-xl font-semibold transition-all duration-300 active:scale-95 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </button>
                </div>

                <div class="flex items-center gap-3">
                    <button id="openPasswordModalBtn" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-semibold transition-all duration-300 active:scale-95 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Change Password
                    </button>
                    <button id="openModalBtn" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-lg shadow-blue-500/20 transition-all duration-300 active:scale-95 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit Profile
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Code Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col md:flex-row gap-6 items-center justify-between mt-6 hover:shadow-md transition-all duration-300">
        <div class="flex-1 text-center md:text-left">
            <h3 class="text-xl font-bold text-slate-800 mb-2">QR Code Attendance</h3>
            <p class="text-sm text-slate-500 mb-4">Show this QR code to the admin to quickly mark your attendance. You can download the image for offline use.</p>
            <a href="{{ route('siswa.qr.download') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-lg shadow-indigo-500/20 transition-all duration-300 active:scale-95 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download QR Code
            </a>
        </div>
        <div class="flex-shrink-0 bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
            {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate((string) $user->id) !!}
        </div>
    </div>

    <!-- Install PWA App Card -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-md p-5 text-white flex flex-col sm:flex-row items-center justify-between gap-4 mt-6">
        <div class="flex items-center gap-3.5 text-center sm:text-left">
            <div class="bg-white/20 p-2.5 rounded-xl flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-base leading-tight">Install Aplikasi Presensi di HP</h4>
                <p class="text-xs text-blue-100 mt-0.5">Pasang aplikasi agar bisa dibuka cepat langsung dari layar utama tanpa browser.</p>
            </div>
        </div>
        <button type="button" class="btn-trigger-pwa-install bg-white text-blue-700 hover:bg-blue-50 active:scale-95 px-4 py-2 rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2 flex-shrink-0">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            <span>Pasang Sekarang</span>
        </button>
    </div>
    
</div>

<!-- Modal Edit Profil -->
<div id="editModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex justify-center items-center z-50 hidden px-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md transform transition-all duration-300 scale-95 opacity-0 modal-content relative shadow-2xl max-h-[90vh] overflow-y-auto">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-slate-800">Edit Profile</h2>
            <button type="button" id="closeModalBtn" class="text-slate-400 hover:text-slate-600 bg-slate-100 rounded-full p-1.5 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('siswa.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Foto Profil -->
            <div class="mb-6 text-center">
                <label class="block text-sm font-semibold text-slate-700 mb-3">Profile Photo</label>
                
                <div class="flex justify-center mb-4">
                    <div class="relative">
                        <img src="{{ asset('img/' . Auth::user()->foto) }}" alt="Foto Profil" 
                             class="w-24 h-24 rounded-full object-cover border-2 border-slate-200 shadow-sm"
                             id="fotoPreview">
                        <label for="fileInput" class="absolute bottom-0 right-0 bg-blue-600 p-1.5 rounded-full cursor-pointer hover:bg-blue-700 transition-colors shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </label>
                        <input type="file" name="foto" id="fileInput" class="hidden" onchange="previewFoto(event)">
                    </div>
                </div>
                <p class="text-xs text-slate-400">Click the camera icon to change photo</p>
            </div>

            <!-- Nama -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-600 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ Auth::user()->name }}" 
                       class="block w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-500 cursor-not-allowed text-sm" readonly>
            </div>

            <!-- No HP -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-600 mb-1">Phone Number</label>
                <input type="text" name="nohp" value="{{ old('nohp', Auth::user()->nohp) }}" 
                       class="block w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm" placeholder="08xxxxxxxxxx">
            </div>

            <!-- Jenis Kelamin -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-600 mb-1">Gender</label>
                <input type="text" value="{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}" 
                       class="block w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-500 cursor-not-allowed text-sm" readonly>
            </div>

            <!-- Alamat -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-600 mb-1">Address</label>
                <textarea name="alamat" rows="3" 
                          class="block w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm resize-none"
                          placeholder="Enter your address">{{ Auth::user()->alamat }}</textarea>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-3">
                <button type="button" id="closeModalBtnFooter" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold px-4 py-2.5 rounded-xl transition-colors text-sm">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-lg shadow-blue-500/20 text-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Ganti Password -->
<div id="passwordModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex justify-center items-center z-50 hidden px-4">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md transform transition-all duration-300 scale-95 opacity-0 password-modal-content relative shadow-2xl">

        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="bg-amber-100 p-2 rounded-xl">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-800">Change Password</h2>
            </div>
            <button type="button" id="closePasswordModalBtn" class="text-slate-400 hover:text-slate-600 bg-slate-100 rounded-full p-1.5 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Validation errors for password form -->
        @if($errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->get('current_password') as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                    @foreach($errors->get('new_password') as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                    @foreach($errors->get('new_password_confirmation') as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('siswa.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Password Saat Ini -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-600 mb-1">Current Password</label>
                <div class="relative">
                    <input type="password" name="current_password" id="currentPassword"
                           class="block w-full border border-slate-200 rounded-xl px-4 py-2.5 pr-12 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all text-sm"
                           placeholder="Enter current password" required>
                    <button type="button" onclick="togglePassword('currentPassword', 'eyeCurrent')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                        <svg id="eyeCurrent" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Password Baru -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-600 mb-1">New Password</label>
                <div class="relative">
                    <input type="password" name="new_password" id="newPassword"
                           class="block w-full border border-slate-200 rounded-xl px-4 py-2.5 pr-12 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all text-sm"
                           placeholder="Minimum 8 characters" required minlength="8"
                           oninput="checkStrength(this.value)">
                    <button type="button" onclick="togglePassword('newPassword', 'eyeNew')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                        <svg id="eyeNew" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>

                <!-- Password Strength Indicator -->
                <div class="mt-2">
                    <div class="flex gap-1 mb-1">
                        <div id="bar1" class="h-1 flex-1 rounded-full bg-slate-200 transition-colors duration-300"></div>
                        <div id="bar2" class="h-1 flex-1 rounded-full bg-slate-200 transition-colors duration-300"></div>
                        <div id="bar3" class="h-1 flex-1 rounded-full bg-slate-200 transition-colors duration-300"></div>
                        <div id="bar4" class="h-1 flex-1 rounded-full bg-slate-200 transition-colors duration-300"></div>
                    </div>
                    <p id="strengthLabel" class="text-xs text-slate-400"></p>
                </div>
            </div>

            <!-- Konfirmasi Password Baru -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-600 mb-1">Confirm New Password</label>
                <div class="relative">
                    <input type="password" name="new_password_confirmation" id="confirmPassword"
                           class="block w-full border border-slate-200 rounded-xl px-4 py-2.5 pr-12 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all text-sm"
                           placeholder="Repeat new password" required
                           oninput="checkMatch()">
                    <button type="button" onclick="togglePassword('confirmPassword', 'eyeConfirm')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                        <svg id="eyeConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <p id="matchLabel" class="text-xs mt-1 hidden"></p>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-3">
                <button type="button" id="closePasswordModalBtnFooter" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold px-4 py-2.5 rounded-xl transition-colors text-sm">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-lg shadow-amber-500/20 text-sm">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ─── Edit Profile Modal ───────────────────────────────────────────────────
    const modal = document.getElementById('editModal');
    const modalContent = modal.querySelector('.modal-content');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const closeBtnFooter = document.getElementById('closeModalBtnFooter');

    function openModal() {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('opacity-0', 'scale-95');
            modalContent.classList.add('opacity-100', 'scale-100');
        }, 10);
    }

    function closeModal() {
        modalContent.classList.remove('opacity-100', 'scale-100');
        modalContent.classList.add('opacity-0', 'scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    openBtn.addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);
    closeBtnFooter.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

    function previewFoto(event) {
        const foto = event.target.files[0];
        const preview = document.getElementById('fotoPreview');
        if (foto) preview.src = URL.createObjectURL(foto);
    }

    // ─── Change Password Modal ────────────────────────────────────────────────
    const passwordModal = document.getElementById('passwordModal');
    const passwordModalContent = passwordModal.querySelector('.password-modal-content');
    const openPasswordBtn = document.getElementById('openPasswordModalBtn');
    const closePasswordBtn = document.getElementById('closePasswordModalBtn');
    const closePasswordBtnFooter = document.getElementById('closePasswordModalBtnFooter');

    function openPasswordModal() {
        passwordModal.classList.remove('hidden');
        setTimeout(() => {
            passwordModalContent.classList.remove('opacity-0', 'scale-95');
            passwordModalContent.classList.add('opacity-100', 'scale-100');
        }, 10);
    }

    function closePasswordModal() {
        passwordModalContent.classList.remove('opacity-100', 'scale-100');
        passwordModalContent.classList.add('opacity-0', 'scale-95');
        setTimeout(() => passwordModal.classList.add('hidden'), 300);
    }

    openPasswordBtn.addEventListener('click', openPasswordModal);
    closePasswordBtn.addEventListener('click', closePasswordModal);
    closePasswordBtnFooter.addEventListener('click', closePasswordModal);
    passwordModal.addEventListener('click', (e) => { if (e.target === passwordModal) closePasswordModal(); });

    // Auto-open password modal if there are password validation errors
    @if($errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation'))
        document.addEventListener('DOMContentLoaded', openPasswordModal);
    @endif

    // ─── Toggle Password Visibility ───────────────────────────────────────────
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.innerHTML = isHidden
            ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`
            : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
    }

    // ─── Password Strength Checker ────────────────────────────────────────────
    function checkStrength(value) {
        const bars = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3'), document.getElementById('bar4')];
        const label = document.getElementById('strengthLabel');
        
        let score = 0;
        if (value.length >= 8) score++;
        if (/[A-Z]/.test(value)) score++;
        if (/[0-9]/.test(value)) score++;
        if (/[^A-Za-z0-9]/.test(value)) score++;

        const colors = ['bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-500'];
        const labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
        const textColors = ['', 'text-red-500', 'text-orange-500', 'text-yellow-600', 'text-green-600'];

        bars.forEach((bar, i) => {
            bar.className = 'h-1 flex-1 rounded-full transition-colors duration-300 ' + (i < score ? colors[score - 1] : 'bg-slate-200');
        });

        label.textContent = value.length > 0 ? labels[score] : '';
        label.className = 'text-xs mt-0 ' + (value.length > 0 ? textColors[score] : 'text-slate-400');
    }

    // ─── Password Match Checker ───────────────────────────────────────────────
    function checkMatch() {
        const newPass = document.getElementById('newPassword').value;
        const confirmPass = document.getElementById('confirmPassword').value;
        const label = document.getElementById('matchLabel');

        if (confirmPass.length === 0) {
            label.classList.add('hidden');
            return;
        }

        label.classList.remove('hidden');
        if (newPass === confirmPass) {
            label.textContent = '✓ Passwords match';
            label.className = 'text-xs mt-1 text-green-600';
        } else {
            label.textContent = '✗ Passwords do not match';
            label.className = 'text-xs mt-1 text-red-500';
        }
    }
</script>
@endsection