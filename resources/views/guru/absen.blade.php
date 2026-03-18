@extends('layouts.guruNav')

@section('content')
{{-- Font Import --}}
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    /* Animasi Kustom */
    @keyframes scan-line {
        0% { top: 10%; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { top: 90%; opacity: 0; }
    }
    .animate-scan-line { animation: scan-line 2.5s ease-in-out infinite; }
    
    @keyframes pulse-ring {
        0% { transform: scale(0.9); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0; }
        100% { transform: scale(0.9); opacity: 0; }
    }
    .animate-pulse-ring { animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }

    .spinner {
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        width: 16px; height: 16px;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>

<div class="bg-slate-50 min-h-screen font-sans" style="font-family: 'DM Sans', sans-serif;">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- PAGE HEADER --}}
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    @php
                        $hour = now()->format('H');
                        $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 17 ? 'Selamat Siang' : 'Selamat Sore');
                    @endphp
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight" style="font-family: 'Syne', sans-serif;">
                        {{ $greeting }}, {{ $user->name ?? 'Guru' }}
                    </h1>
                    <p class="text-slate-500 text-sm mt-1">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="bg-blue-50 text-blue-600 text-xs font-bold px-4 py-2 rounded-full border border-blue-100 shadow-sm" id="ga-clock">
                        --:--:--
                    </span>
                    <a href="{{ route('profile.edit') ?? '#' }}" class="relative group">
                        <img src="{{ asset('img/' . ($user->foto ?? 'default.jpg')) }}" class="w-11 h-11 rounded-full border-2 border-white shadow-md hover:border-blue-300 transition object-cover" alt="Profile">
                        <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                    </a>
                </div>
            </div>
        </div>



        {{-- MAIN GRID: CHECKIN & HISTORY --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- LEFT: CHECKIN ACTION --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col items-center">
                    <h2 class="text-lg font-bold text-slate-800 mb-6 uppercase tracking-wide" style="font-family: 'Syne', sans-serif;">Absensi Harian</h2>
                    
                    <div class="relative flex items-center justify-center mb-6">
                        <!-- Pulse Background -->
                        <div class="absolute w-40 h-40 bg-blue-100 rounded-full animate-pulse-ring"></div>
                        
                        <button onclick="gaStartAbsen()" class="relative w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 shadow-xl flex flex-col items-center justify-center hover:scale-105 transform transition-transform duration-300 focus:outline-none ring-4 ring-white">
                            <svg class="w-8 h-8 text-white mb-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-white text-[10px] font-bold tracking-widest uppercase">Check In</span>
                        </button>
                    </div>

                    <p class="text-slate-400 text-xs text-center mb-6 px-4">
                        Pastikan wajah terlihat jelas dan lokasi Anda berada di dalam radius sekolah.
                    </p>

                    <div class="w-full grid grid-cols-2 gap-3">
                        <a href="#" class="flex items-center justify-center gap-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl p-3 text-xs font-semibold text-slate-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Riwayat
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="contents">
                            @csrf
                            <button type="submit" class="flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 border border-red-100 rounded-xl p-3 text-xs font-semibold text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- RIGHT: ACTIVITY & HISTORY --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 h-full flex flex-col">
                    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-800 uppercase tracking-wide" style="font-family: 'Syne', sans-serif;">Aktivitas Terkini</h3>
                        <a href="#" class="text-blue-600 text-xs font-semibold hover:underline">Lihat Semua</a>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto max-h-[400px] p-4">
                        @forelse ($absensis ?? [] as $absen)
                            @php
                                $h = \Carbon\Carbon::parse($absen->waktu)->hour;
                                $late = $h >= 7;
                            @endphp
                            <div class="flex items-center gap-4 p-3 hover:bg-slate-50 rounded-xl transition-colors mb-2 border border-transparent hover:border-slate-100">
                                <img src="{{ asset('storage/' . $absen->foto) }}" class="w-12 h-12 rounded-lg object-cover shadow-sm border border-slate-100" alt="Foto">
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-center mb-1">
                                        <p class="text-sm font-semibold text-slate-700">{{ \Carbon\Carbon::parse($absen->created_at)->translatedFormat('D, d M Y') }}</p>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $late ? 'bg-red-50 text-red-500' : 'bg-green-50 text-green-600' }}">
                                            {{ $late ? 'Terlambat' : 'Tepat Waktu' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 text-[11px] text-slate-400">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                            {{ \Carbon\Carbon::parse($absen->waktu)->format('H:i') }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                            {{ round($absen->latitude, 4) }}, {{ round($absen->longitude, 4) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="text-4xl mb-3 opacity-30">📋</div>
                                <p class="text-slate-400 text-sm font-medium">Belum ada riwayat absensi</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ABSENSI (CENTERED FOR DESKTOP) --}}
<div id="gaModal" class="fixed inset-0 z-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 p-4">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="gaCloseModal()"></div>

    <!-- Modal Content -->
    <div class="relative bg-white w-full max-w-xl rounded-2xl shadow-2xl transform scale-95 transition-transform duration-300" id="gaModalContent">
        
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-extrabold text-slate-800" style="font-family: 'Syne', sans-serif;">Konfirmasi Absensi</h2>
                <button onclick="gaCloseModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <p class="text-slate-400 text-xs mb-4">Posisikan wajah Anda dalam bingkai kamera</p>

            <!-- Camera Area -->
            <div class="relative w-full aspect-video bg-slate-900 rounded-xl overflow-hidden mb-4 border border-slate-200 shadow-inner">
                <video id="ga-video" autoplay playsinline class="w-full h-full object-cover transform scale-x-[-1]"></video>
                
                <!-- Scanner Overlay -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="relative w-40 h-40 border-2 border-blue-400/50 rounded-lg">
                        <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-blue-500 rounded-tl-lg"></div>
                        <div class="absolute top-0 right-0 w-6 h-6 border-t-2 border-r-2 border-blue-500 rounded-tr-lg"></div>
                        <div class="absolute bottom-0 left-0 w-6 h-6 border-b-2 border-l-2 border-blue-500 rounded-bl-lg"></div>
                        <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-blue-500 rounded-br-lg"></div>
                        <div class="absolute left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-blue-400 to-transparent animate-scan-line"></div>
                    </div>
                </div>

                <!-- Status Badge -->
                <div id="ga-face-badge" class="absolute top-3 left-1/2 -translate-x-1/2 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wide flex items-center gap-2 bg-slate-700/80 text-slate-200 backdrop-blur-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-current" id="ga-face-dot"></span>
                    <span id="ga-face-status">Menunggu...</span>
                </div>
            </div>

            <!-- Map Area -->
            <div id="ga-map" class="w-full h-32 rounded-xl overflow-hidden mb-5 border border-slate-200 shadow-sm relative z-0"></div>
            
            <canvas id="ga-canvas" class="hidden"></canvas>

            <!-- Buttons -->
            <button id="ga-submit-btn" onclick="gaCaptureSubmit()" disabled class="w-full py-3.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="3"/></svg>
                Ambil Foto & Absen
            </button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ── CLOCK ──
    function gaUpdateClock() {
        document.getElementById('ga-clock').textContent = new Date().toLocaleTimeString('id-ID', { hour12: false });
    }
    gaUpdateClock();
    setInterval(gaUpdateClock, 1000);

    // ── FACE API SETUP ──
    let gaFaceReady = false, gaFaceDetected = false, gaFaceInterval = null;

    (async () => {
        try {
            await faceapi.nets.tinyFaceDetector.loadFromUri('https://raw.githubusercontent.com/justadudewhohacks/face-api.js/master/weights');
            gaFaceReady = true;
        } catch(e) { console.error('Face model error', e); }
    })();

    async function gaDetectFace() {
        const video = document.getElementById('ga-video');
        const badge = document.getElementById('ga-face-badge');
        const statusTxt = document.getElementById('ga-face-status');
        const btn = document.getElementById('ga-submit-btn');

        if (!video || !video.videoWidth) return;
        if (!gaFaceReady) { statusTxt.textContent = 'Memuat Model...'; return; }

        try {
            const det = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.5 }));

            if (det) {
                badge.className = 'absolute top-3 left-1/2 -translate-x-1/2 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wide flex items-center gap-2 bg-green-100 text-green-700 border border-green-200 backdrop-blur-sm';
                statusTxt.textContent = 'Wajah Terdeteksi';
                gaFaceDetected = true;
                btn.disabled = false;
            } else {
                badge.className = 'absolute top-3 left-1/2 -translate-x-1/2 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wide flex items-center gap-2 bg-red-100 text-red-600 border border-red-200 backdrop-blur-sm';
                statusTxt.textContent = 'Wajah Tidak Terdeteksi';
                gaFaceDetected = false;
                btn.disabled = true;
            }
        } catch(e) { console.error(e); }
    }

    // ── MAP & MODAL LOGIC ──
    let gaMap, gaMarker;
    const gaSchoolLat = -6.949648486282659;
    const gaSchoolLng = 107.685995;
    const gaRadius = 10000;

    function gaStartAbsen() {
        const modal = document.getElementById('gaModal');
        const modalContent = document.getElementById('gaModalContent');
        
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100', 'pointer-events-auto');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');

        navigator.geolocation.getCurrentPosition(pos => {
            const lat = pos.coords.latitude, lng = pos.coords.longitude;

            if (!gaMap) {
                gaMap = L.map('ga-map').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {}).addTo(gaMap);
            } else {
                gaMap.setView([lat, lng], 15);
            }

            if (gaMarker) gaMarker.remove();
            gaMarker = L.marker([lat, lng]).addTo(gaMap).bindPopup('Lokasi Anda').openPopup();

            L.circle([gaSchoolLat, gaSchoolLng], {
                radius: gaRadius,
                color: '#3b82f6',
                fillOpacity: 0.1,
                weight: 1
            }).addTo(gaMap);

        }, err => {
            Swal.fire('Error', 'Akses lokasi ditolak: ' + err.message, 'error');
        });

        const video = document.getElementById('ga-video');
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: 640, height: 480 } })
            .then(stream => {
                video.srcObject = stream;
                video.onloadedmetadata = () => {
                    video.play();
                    setTimeout(() => {
                        gaFaceInterval = setInterval(gaDetectFace, 400);
                        gaDetectFace();
                    }, 600);
                };
            }).catch(err => Swal.fire('Error', 'Kamera tidak dapat diakses: ' + err.message, 'error'));
    }

    function gaCloseModal() {
        const modal = document.getElementById('gaModal');
        const modalContent = document.getElementById('gaModalContent');
        
        modal.classList.add('opacity-0', 'pointer-events-none');
        modal.classList.remove('opacity-100', 'pointer-events-auto');
        modalContent.classList.add('scale-95');
        modalContent.classList.remove('scale-100');

        if (gaFaceInterval) { clearInterval(gaFaceInterval); gaFaceInterval = null; }

        const video = document.getElementById('ga-video');
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(t => t.stop());
            video.srcObject = null;
        }
        gaFaceDetected = false;
        document.getElementById('ga-submit-btn').disabled = true;
        
        const badge = document.getElementById('ga-face-badge');
        badge.className = 'absolute top-3 left-1/2 -translate-x-1/2 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wide flex items-center gap-2 bg-slate-700/80 text-slate-200 backdrop-blur-sm';
        document.getElementById('ga-face-status').textContent = 'Menunggu...';
    }

    async function gaCaptureSubmit() {
        if (!gaFaceDetected) {
            Swal.fire('Peringatan', 'Posisikan wajah Anda dengan benar.', 'warning');
            return;
        }

        const video = document.getElementById('ga-video');
        const canvas = document.getElementById('ga-canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        const photo = canvas.toDataURL('image/jpeg', 0.85);

        if (!gaMarker) {
            Swal.fire('Error', 'Lokasi belum tersedia.', 'error');
            return;
        }

        const latlng = gaMarker.getLatLng();
        const dist = gaMap.distance([latlng.lat, latlng.lng], [gaSchoolLat, gaSchoolLng]);

        if (dist > gaRadius) {
            Swal.fire('Di Luar Jangkauan', `Anda berada ${Math.round(dist)}m dari sekolah.`, 'error');
            return;
        }

        const btn = document.getElementById('ga-submit-btn');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<div class="spinner"></div> Memproses...';
        btn.disabled = true;

        try {
            const res = await fetch("{{ route('guru.absen.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ photo, lat: latlng.lat, lng: latlng.lng })
            });

            const data = await res.json();

            if (data.status === 'success') {
                await Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: `<p>${data.message}</p><p class="mt-2 text-blue-500">🔥 Streak: <strong>${data.streak}</strong> hari</p>`,
                    timer: 2500,
                    showConfirmButton: false
                });
                gaCloseModal();
                location.reload();
            } else {
                Swal.fire('Gagal', data.message, 'error');
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }
        } catch(err) {
            console.error(err);
            Swal.fire('Error', 'Gagal mengirim absensi.', 'error');
            btn.innerHTML = originalHTML;
            btn.disabled = false;
        }
    }
</script>

@if(session('success'))
<script>
    Swal.fire({ icon: 'success', title: 'Berhasil', text: '{{ session('success') }}', timer: 2000, showConfirmButton: false });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({ icon: 'error', title: 'Oops', text: '{{ session('error') }}' });
</script>
@endif
@endsection
```