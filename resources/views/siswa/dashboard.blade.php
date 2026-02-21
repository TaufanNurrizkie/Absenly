@extends('layouts.siswaNav')
@section('content')
    <style>
        /* Modal Animation */
        #absenModal {
            opacity: 0;
            transform: scale(0.95);
            transition: all 0.3s ease-in-out;
        }

        #absenModal.show {
            opacity: 1;
            transform: scale(1);
        }

        /* Video Aspect Ratio */
        .aspect-video {
            aspect-ratio: 16 / 9;
        }

        /* Menu Item Hover */
        .menu-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .menu-item:hover {
            transform: translateY(-4px);
        }

        /* Ripple Button Animation */
        .ripple-btn {
            position: relative;
            overflow: hidden;
        }

        .ripple-btn::after {
            content: '';
            position: absolute;
            width: 300%;
            height: 300%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(96, 165, 250, 0.3);
            border-radius: 50%;
            animation: ripple 2s infinite ease-out;
            z-index: 0;
        }

        .ripple-outer {
            position: relative;
            z-index: 1;
        }

        .ripple-outer::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.7);
            background: rgba(191, 219, 254, 0.4);
            border-radius: 9999px;
            animation: ripple-outer 2.5s infinite ease-out;
            z-index: -1;
        }

        @keyframes ripple-outer {
            0% {
                transform: translate(-50%, -50%) scale(0.7);
                opacity: 0.7;
            }

            100% {
                transform: translate(-50%, -50%) scale(2);
                opacity: 0;
            }
        }

        @keyframes ripple {
            0% {
                transform: translate(-50%, -50%) scale(0);
                opacity: 0.5;
            }

            100% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 0;
            }
        }

        /* Card Animation */
        .attendance-card {
            transform: translateY(20px);
            animation: slideUp 0.5s ease forwards;
        }

        @keyframes slideUp {
            to {
                transform: translateY(0);
            }
        }

        @keyframes modalFade {
            from {
                transform: scale(0.95);
            }

            to {
                transform: scale(1);
            }
        }

        .animate-modal {
            animation: modalFade 0.3s ease-out;
        }

        /* Gradient Background */
        .gradient-bg {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 50%, #93c5fd 100%);
        }

        /* Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Smooth Shadow */
        .soft-shadow {
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.1);
        }

        /* Time Badge */
        .time-badge {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
        }

        /* Face Detection Indicator */
        .face-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            z-index: 10;
        }

        .face-detected {
            background: rgba(34, 197, 94, 0.9);
        }

        .face-not-detected {
            background: rgba(239, 68, 68, 0.9);
        }
    </style>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50">

        <!-- Header Section -->
        <div class="gradient-bg rounded-b-[2rem] p-6 pt-4 pb-16 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/20 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/20 rounded-full -ml-24 -mb-24"></div>

            <div class="relative z-10">
                <!-- Top Bar -->
                <div class="flex items-center justify-between mb-8">
                    <div class="space-y-1">
                        @php
                            $hour = now()->format('H');
                            if ($hour >= 6 && $hour < 11) {
                                $greeting = 'Morning';
                                $emoji = '🌅';
                            } elseif ($hour >= 11 && $hour < 17) {
                                $greeting = 'Afternoon';
                                $emoji = '☀️';
                            } elseif ($hour >= 17 && $hour < 21) {
                                $greeting = 'Evening';
                                $emoji = '🌆';
                            } else {
                                $greeting = 'Night';
                                $emoji = '🌙';
                            }
                        @endphp
                        <h2 class="text-sm text-blue-800 font-medium">Good {{ $greeting }} {{ $emoji }}</h2>
                        <h1 class="text-2xl font-bold text-blue-900">{{ $user->name }}</h1>
                        <p class="text-sm text-blue-700 font-medium">{{ $user->kelas }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href=""
                            class="cursor-pointer p-2 bg-white/80 rounded-full hover:bg-white transition-all duration-300 soft-shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                class="text-blue-600">
                                <path fill="currentColor" fill-rule="evenodd"
                                    d="M13 3a1 1 0 1 0-2 0v.75h-.557A4.214 4.214 0 0 0 6.237 7.7l-.221 3.534a7.4 7.4 0 0 1-1.308 3.754a1.617 1.617 0 0 0 1.135 2.529l3.407.408V19a2.75 2.75 0 1 0 5.5 0v-1.075l3.407-.409a1.617 1.617 0 0 0 1.135-2.528a7.4 7.4 0 0 1-1.308-3.754l-.221-3.533a4.214 4.214 0 0 0-4.206-3.951H13zm-2.25 16a1.25 1.25 0 1 0 2.5 0v-.75h-2.5z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="{{ route('siswa.profile') }}" class="block">
                            <img src="{{ asset('img/' . $user->foto) }}" alt="Profile"
                                class="w-12 h-12 rounded-full border-3 border-white object-cover soft-shadow hover:scale-105 transition-transform duration-300">
                        </a>
                    </div>
                </div>

                <!-- Streak Counter -->
                <div class="flex items-center gap-2 bg-white/80 rounded-2xl px-4 py-3 w-fit soft-shadow mb-8">
                    <img src="{{ asset('img/fire-3352_256.gif') }}" alt="Streak" class="w-8 h-8" />
                    <div>
                        <p class="text-xs text-blue-600 font-medium">Streak</p>
                        <p class="text-xl font-bold text-blue-900">{{ $user->absen_streak }} days</p>
                    </div>
                </div>

                <!-- Attendance Button -->
                <div class="flex justify-center">
                    <div onclick="startAbsensi()"
                        class="ripple-outer relative w-44 h-44 rounded-full bg-white soft-shadow flex flex-col items-center justify-center text-center cursor-pointer hover:scale-105 transition-all duration-300">
                        <div class="relative z-10">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center mb-3 mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                    class="text-white">
                                    <path fill="currentColor"
                                        d="M9 1v2h6V1h2v2h4a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h4V1h2zm11 9H4v9h16v-9zm-4.964 1.136l1.414 1.414l-4.95 4.95l-3.536-3.536L9.38 12.55l2.121 2.122l3.536-3.536z" />
                                </svg>
                            </div>
                            <h1 class="text-xl font-bold text-blue-900">TAP TO</h1>
                            <p class="text-sm font-semibold text-blue-600">CHECK IN</p>
                        </div>
                    </div>
                </div>

                <!-- Date Time Display -->
                <div class="text-center mt-6 space-y-1">
                    <div class="inline-block bg-white/80 rounded-xl px-6 py-3 soft-shadow">
                        <h1 id="clock" class="text-2xl font-bold text-blue-900"></h1>
                        <span
                            class="block text-sm font-medium text-blue-600 mt-1">{{ \Carbon\Carbon::now()->format('D, d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Modal -->
        <div id="absenModal"
            class="fixed inset-0 bg-blue-900/60 backdrop-blur-sm flex items-center justify-center z-50 hidden px-4">
            <div class="bg-white rounded-3xl p-6 w-full max-w-md relative soft-shadow">
                <button onclick="closeModal()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-blue-600 transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h2 class="text-2xl font-bold text-center mb-6 text-blue-900">Check In</h2>

                <!-- Map -->
                <div id="map" class="h-52 w-full rounded-2xl mb-4 overflow-hidden soft-shadow"></div>

                <!-- Camera -->
                <div class="aspect-video w-full bg-gray-900 rounded-2xl overflow-hidden soft-shadow relative">
                    <video id="video" autoplay class="w-full h-full object-cover"></video>
                    <div id="faceIndicator" class="face-indicator face-not-detected">
                        <span id="faceStatus">😐 Detecting face...</span>
                    </div>
                </div>
                <canvas id="canvas" class="hidden"></canvas>

                <!-- Buttons -->
                <div class="flex flex-col gap-3 mt-6">
                    <button id="submitBtn" onclick="captureAndSubmit()"
                        class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3.5 rounded-xl font-semibold hover:from-blue-600 hover:to-blue-700 transition-all duration-300 soft-shadow disabled:opacity-50 disabled:cursor-not-allowed">
                        📸 Capture & Check In
                    </button>
                    <button onclick="closeModal()"
                        class="w-full bg-gray-100 text-gray-700 py-3.5 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-300">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- Menu Grid -->
        <div class="px-6 -mt-8 relative z-20">
            <div class="grid grid-cols-4 gap-4">
                <div class="menu-item">
                    <div class="bg-white rounded-2xl p-4 text-center soft-shadow">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-500 rounded-xl flex items-center justify-center text-2xl mx-auto mb-2">
                            📋
                        </div>
                        <p class="text-xs font-semibold text-gray-700">Attendance</p>
                    </div>
                </div>
                <div onclick="document.getElementById('izinModal').classList.remove('hidden')"
                    class="menu-item cursor-pointer">
                    <div class="bg-white rounded-2xl p-4 text-center soft-shadow">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl flex items-center justify-center text-2xl mx-auto mb-2">
                            🛑
                        </div>
                        <p class="text-xs font-semibold text-gray-700">Permission</p>
                    </div>
                </div>
                <div onclick="document.getElementById('sakitModal').classList.remove('hidden')"
                    class="menu-item cursor-pointer">
                    <div class="bg-white rounded-2xl p-4 text-center soft-shadow">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center text-2xl mx-auto mb-2">
                            🏥
                        </div>
                        <p class="text-xs font-semibold text-gray-700">Sick Leave</p>
                    </div>
                </div>
                <div class="menu-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full">
                            <div class="bg-white rounded-2xl p-4 text-center soft-shadow">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-500 rounded-xl flex items-center justify-center text-2xl mx-auto mb-2">
                                    🚪
                                </div>
                                <p class="text-xs font-semibold text-gray-700">Logout</p>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Izin -->
        <div id="izinModal"
            class="fixed inset-0 bg-blue-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center">
            <div class="bg-white rounded-3xl w-full max-w-md mx-4 p-6 soft-shadow">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-blue-900">Permission Form</h2>
                    <button type="button" onclick="document.getElementById('izinModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-blue-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('absensi.izin') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="tipe" value="izin">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Reason</label>
                        <textarea name="alasan"
                            class="w-full px-4 py-3 border-2 border-blue-100 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-transparent resize-none transition-all duration-200 placeholder-gray-400"
                            rows="4" placeholder="Enter your reason for permission..." required></textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('izinModal').classList.add('hidden')"
                            class="flex-1 px-6 py-3.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl font-semibold transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-3.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl font-semibold transition-all duration-200 soft-shadow">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Sakit -->
        <div id="sakitModal"
            class="fixed inset-0 bg-blue-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center">
            <div class="bg-white rounded-3xl w-full max-w-md mx-4 p-6 soft-shadow">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-blue-900">Sick Leave Form</h2>
                    <button type="button" onclick="document.getElementById('sakitModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-blue-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('absensi.izin') }}" enctype="multipart/form-data"
                    onsubmit="return confirmIzinSakit()">
                    @csrf
                    <input type="hidden" name="tipe" value="sakit">

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Medical Certificate</label>
                        <div class="relative">
                            <input type="file" name="surat" id="suratDokter" accept="image/*,application/pdf"
                                class="w-full px-4 py-3 border-2 border-blue-100 rounded-xl focus:ring-2 focus:ring-blue-400 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                required>
                        </div>
                        <div id="preview" class="mt-3 hidden">
                            <p class="text-sm text-gray-600 mb-2 font-medium">Preview:</p>
                            <img id="previewImage" class="w-full rounded-xl border-2 border-blue-100 soft-shadow"
                                alt="Preview" />
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="document.getElementById('sakitModal').classList.add('hidden')"
                            class="flex-1 px-6 py-3.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl font-semibold transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-3.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl font-semibold transition-all duration-200 soft-shadow">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Attendance History -->
        <div class="mt-8 px-6 pb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-xl text-blue-900">Recent Attendance</h3>
                <a href="#" class="text-sm text-blue-600 font-semibold hover:text-blue-700">View All →</a>
            </div>

            @forelse ($absensis as $absen)
                <div class="bg-white rounded-2xl soft-shadow p-5 mb-4 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <img src="{{ asset('storage/' . $absen->foto) }}" alt="Attendance Photo"
                            class="w-20 h-20 object-cover rounded-xl border-2 border-blue-100">

                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-blue-900 mb-3">
                                {{ \Carbon\Carbon::parse($absen->created_at)->translatedFormat('D, d M Y') }}
                            </h4>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Check In Time</p>
                                    @php
                                        $hour = \Carbon\Carbon::parse($absen->waktu)->hour;
                                        $bgColor = $hour >= 5 && $hour < 7 ? 'bg-green-50' : 'bg-red-50';
                                        $textColor = $hour >= 5 && $hour < 7 ? 'text-green-600' : 'text-red-600';
                                    @endphp
                                    <div
                                        class="flex items-center gap-1.5 {{ $bgColor }} {{ $textColor }} px-3 py-1.5 rounded-lg w-fit">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span
                                            class="text-sm font-bold">{{ \Carbon\Carbon::parse($absen->waktu)->format('H:i') }}</span>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Location</p>
                                    <div class="flex items-center gap-1.5 bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-xs font-semibold">{{ round($absen->latitude, 3) }},
                                            {{ round($absen->longitude, 3) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-sm text-gray-400 font-medium">No attendance records yet</p>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://unpkg.com/face-api.js@0.22.2/dist/face-api.min.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let faceReady = false;
        let faceDetectionInterval = null;
        let isFaceDetected = false;

        console.log('🚀 Face detection script loaded');
        console.log('📁 Models should be in: /models/');
        console.log('💡 Make sure you have tiny_face_detector model files in public/models/');


        // Load face detection model
        async function loadFaceModel() {
            try {
                console.log('🔄 Loading face detection model...');
                await faceapi.nets.tinyFaceDetector.loadFromUri('https://raw.githubusercontent.com/justadudewhohacks/face-api.js/master/weights');
                faceReady = true;
                console.log('✅ Face detection model loaded successfully');
            } catch (e) {
                console.error('❌ Failed to load face detection model:', e);
                faceReady = false;
            }
        }

        // Start face detection
        async function detectFace() {
            const video = document.getElementById('video');
            const indicator = document.getElementById('faceIndicator');
            const statusText = document.getElementById('faceStatus');
            const submitBtn = document.getElementById('submitBtn');

            if (!video || !video.videoWidth || !video.videoHeight) {
                console.log('⏳ Video not ready yet...');
                return;
            }

            if (!faceReady) {
                console.log('⏳ Face model not ready yet...');
                statusText.textContent = '⏳ Loading face detection...';
                return;
            }

            try {
                const detection = await faceapi.detectSingleFace(
                    video,
                    new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.4 })
                );

                if (detection) {
                    isFaceDetected = true;
                    indicator.className = 'face-indicator face-detected';
                    statusText.textContent = '✅ Face detected!';
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    console.log('✅ Face detected! Score:', detection.score);
                } else {
                    isFaceDetected = false;
                    indicator.className = 'face-indicator face-not-detected';
                    statusText.textContent = '❌ Please show your face';
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    console.log('❌ No face detected');
                }
            } catch (error) {
                console.error('Face detection error:', error);
                statusText.textContent = '⚠️ Detection error';
            }
        }

        // Initialize on page load
        loadFaceModel();

        // Clock update
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeString = `${hours}:${minutes}:${seconds}`;
            document.getElementById('clock').textContent = timeString;
        }

        updateClock();
        setInterval(updateClock, 1000);

        // Location settings
        const allowedLat = -6.949648486282659;
        const allowedLng = 107.685995;
        const allowedRadius = 100000;

        let map, marker, circle;

        function startAbsensi() {
            const modal = document.getElementById('absenModal');
            const submitBtn = document.getElementById('submitBtn');
            
            modal.classList.remove('hidden');
            setTimeout(() => modal.classList.add('show'), 10);
            
            // Disable submit button initially
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Initialize map
                map = L.map('map').setView([lat, lng], 17);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

                marker = L.marker([lat, lng]).addTo(map).bindPopup("Your Location").openPopup();
                circle = L.circle([allowedLat, allowedLng], {
                    radius: allowedRadius,
                    color: '#3b82f6',
                    fillOpacity: 0.1
                }).addTo(map);

                // Start camera
                const video = document.getElementById('video');
                navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'user', width: 640, height: 480 }
                }).then(stream => {
                    video.srcObject = stream;
                    console.log('📹 Camera started');
                    
                    // Wait for video to be ready, then start face detection
                    video.onloadedmetadata = () => {
                        video.play();
                        console.log('▶️ Video playing, size:', video.videoWidth, 'x', video.videoHeight);
                        
                        // Give a moment for video to stabilize
                        setTimeout(() => {
                            console.log('🔍 Starting face detection...');
                            // Start periodic face detection (check every 300ms for responsiveness)
                            faceDetectionInterval = setInterval(detectFace, 300);
                            
                            // Run first detection immediately
                            detectFace();
                        }, 500);
                    };
                }).catch(err => {
                    console.error('Camera error:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Camera Error',
                        text: 'Failed to access camera: ' + err.message,
                        confirmButtonColor: '#3b82f6'
                    });
                });

            }, function(error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Location Error',
                    text: 'Failed to get location: ' + error.message,
                    confirmButtonColor: '#3b82f6'
                });
            });
        }

        function closeModal() {
            const modal = document.getElementById('absenModal');
            modal.classList.remove('show');
            setTimeout(() => modal.classList.add('hidden'), 300);
            
            // Stop face detection
            if (faceDetectionInterval) {
                clearInterval(faceDetectionInterval);
                faceDetectionInterval = null;
            }
            
            // Clean up map
            if (map) map.remove();
            
            // Stop camera
            const video = document.getElementById('video');
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(track => track.stop());
            }
            
            // Reset face detection status
            isFaceDetected = false;
        }

        async function captureAndSubmit() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            
            // Validate video is ready
            if (!video.videoWidth || !video.videoHeight) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Camera Not Ready',
                    text: 'Please wait for the camera to initialize',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            // MANDATORY: Check if face is detected
            if (!isFaceDetected) {
                Swal.fire({
                    icon: 'error',
                    title: 'No Face Detected!',
                    text: 'Please position your face in front of the camera to check in.',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            // Capture image
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);

            const dataURL = canvas.toDataURL('image/png');
            const latlng = marker.getLatLng();
            const distance = map.distance([latlng.lat, latlng.lng], [allowedLat, allowedLng]);

            // Check distance
            if (distance > allowedRadius) {
                Swal.fire({
                    icon: 'error',
                    title: 'Out of Range',
                    text: `You are ${Math.round(distance)}m away. Must be within ${allowedRadius}m!`,
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            // Show loading
            Swal.fire({
                title: 'Processing...',
                text: 'Submitting your attendance',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit attendance
            fetch('/siswa/absen', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        photo: dataURL,
                        lat: latlng.lat,
                        lng: latlng.lng
                    })
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(err => {
                            throw new Error(err.message || 'Failed to check in');
                        });
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.status === 'error') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Already Checked In!',
                            text: data.message,
                            confirmButtonColor: '#3b82f6'
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'Attendance recorded successfully.',
                            confirmButtonColor: '#3b82f6'
                        });
                        closeModal();
                        setTimeout(() => location.reload(), 1500);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: error.message || 'An error occurred.',
                        confirmButtonColor: '#3b82f6'
                    });
                });
        }

        // File preview for sick leave
        document.getElementById('suratDokter').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview');
            const img = document.getElementById('previewImage');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    img.src = event.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                img.src = '';
                preview.classList.add('hidden');
            }
        });

        function confirmIzinSakit() {
            Swal.fire({
                title: 'Submit Sick Leave?',
                text: "Make sure the medical certificate is correct.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Submit',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('#sakitModal form').submit();
                }
            });
            return false;
        }
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false,
                confirmButtonColor: '#3b82f6'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: '{{ session('error') }}',
                showConfirmButton: true,
                confirmButtonColor: '#3b82f6'
            });
        </script>
    @endif
@endsection