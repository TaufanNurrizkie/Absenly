@extends('layouts.adminNav')

@section('title', 'Scan QR Code')
@section('page-title', 'Scan QR Code Kehadiran')

@section('content')
    <div class="max-w-3xl mx-auto">

        {{-- CAMERA CARD --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-blue-100">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Arahkan QR Code ke Kamera</h3>
            </div>

            <div id="reader" class="rounded-xl overflow-hidden border-2 border-dashed border-blue-200 bg-blue-50/40">
            </div>

            <p class="text-sm text-slate-500 mt-4 text-center flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Pastikan QR Code berada di dalam kotak scanner.
            </p>
        </div>
    </div>

    {{-- HASIL SCAN MODAL --}}
    <div id="scan-modal-backdrop"
        class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm items-end md:items-center justify-center p-0 md:p-4">
        <div id="scan-modal"
            class="w-full md:max-w-md bg-white rounded-t-3xl md:rounded-2xl shadow-2xl border border-blue-100 max-h-[85vh] overflow-y-auto transform transition-transform duration-300 translate-y-full md:translate-y-0 md:scale-95 opacity-100">

            {{-- drag handle for mobile --}}
            <div class="md:hidden flex justify-center pt-3">
                <div class="w-10 h-1.5 rounded-full bg-slate-200"></div>
            </div>

            <div class="flex items-center justify-between px-5 pt-3 md:pt-5">
                <h3 class="text-lg font-bold text-slate-800">Hasil Scan</h3>
                <button onclick="closeModal()"
                    class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div id="scan-result" class="text-center p-6">
                <!-- content injected dynamically -->
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const resultContainer = document.getElementById('scan-result');
            const modalBackdrop = document.getElementById('scan-modal-backdrop');
            const modal = document.getElementById('scan-modal');
            let isProcessing = false;
            let html5QrcodeScanner = null;

            function openModal() {
                modalBackdrop.classList.remove('hidden');
                modalBackdrop.classList.add('flex');
                requestAnimationFrame(() => {
                    modal.classList.remove('translate-y-full', 'md:scale-95');
                    modal.classList.add('translate-y-0', 'md:scale-100');
                });
            }

            window.closeModal = function() {
                modal.classList.add('translate-y-full', 'md:scale-95');
                modal.classList.remove('translate-y-0', 'md:scale-100');
                setTimeout(() => {
                    modalBackdrop.classList.add('hidden');
                    modalBackdrop.classList.remove('flex');
                    resetScanner();
                }, 250);
            };

            // close on backdrop click (not when clicking modal itself)
            modalBackdrop.addEventListener('click', function(e) {
                if (e.target === modalBackdrop) closeModal();
            });

            function loadingHtml(text) {
                return `
                <div class="animate-pulse flex flex-col items-center py-6">
                    <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                    <p class="text-blue-600 font-bold">${text}</p>
                </div>`;
            }

            function onScanSuccess(decodedText, decodedResult) {
                if (isProcessing) return;
                isProcessing = true;

                if (html5QrcodeScanner) {
                    html5QrcodeScanner.pause();
                }

                resultContainer.innerHTML = loadingHtml('Mengambil data siswa...');
                openModal();

                fetch('{{ route('admin.scan.info') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            user_id: decodedText
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const user = data.user;
                            const photoHtml = user.foto ?
                                `<img src="${user.foto}" class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-blue-100 shadow-md mb-3">` :
                                `<div class="w-24 h-24 rounded-full bg-blue-100 mx-auto flex items-center justify-center mb-3">
                               <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                           </div>`;

                            resultContainer.innerHTML = `
                        <div class="w-full">
                            ${photoHtml}
                            <h4 class="text-slate-800 font-bold text-xl">${user.name}</h4>
                            <p class="text-slate-500 font-medium mb-1">${user.nis}</p>
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold mb-6">${user.kelas}</span>

                            <div class="flex flex-col gap-3 pb-2">
                                <button onclick="processAttendance('${decodedText}', 'hadir')" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-blue-500/30 transition-all">
                                    Approve Hadir
                                </button>
                                <button onclick="processAttendance('${decodedText}', 'pulang')" class="w-full bg-white hover:bg-blue-50 text-blue-700 font-bold py-3 px-4 rounded-xl border-2 border-blue-600 transition-all">
                                    Approve Pulang
                                </button>
                                <button onclick="closeModal()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3 px-4 rounded-xl transition-all">
                                    Batal
                                </button>
                            </div>
                        </div>
                    `;
                        } else {
                            showError(data.message);
                        }
                    })
                    .catch(error => {
                        showError("Terjadi kesalahan pada server saat mengambil data.");
                    });
            }

            window.processAttendance = function(userId, tipe) {
                resultContainer.innerHTML = loadingHtml(`Memproses absen ${tipe}...`);

                fetch('{{ route('admin.scan.process') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            user_id: userId,
                            tipe_absen: tipe
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            resultContainer.innerHTML = `
                        <div class="p-2 pb-4">
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                <svg class="w-12 h-12 mx-auto text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h4 class="text-blue-800 font-bold text-lg mb-1">${data.user}</h4>
                                <p class="text-blue-600 font-medium mb-1">${data.message}</p>
                            </div>
                        </div>
                    `;
                            setTimeout(() => {
                                closeModal();
                            }, 2000);
                        } else {
                            showError(data.message);
                        }
                    })
                    .catch(error => {
                        showError("Terjadi kesalahan pada server saat memproses absensi.");
                    });
            };

            window.showError = function(message) {
                resultContainer.innerHTML = `
                <div class="w-full pb-2">
                    <div class="p-4 bg-red-50 border border-red-200 rounded-xl mb-4">
                        <svg class="w-12 h-12 mx-auto text-red-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h4 class="text-red-800 font-bold text-lg mb-1">Gagal</h4>
                        <p class="text-red-600 text-sm">${message}</p>
                    </div>
                    <button onclick="closeModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition-all">
                        Kembali Scan
                    </button>
                </div>
            `;
            };

            window.resetScanner = function() {
                isProcessing = false;
                resultContainer.innerHTML = '';
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.resume();
                }
            };

            function onScanFailure(error) {
                // handle scan failure silently
            }

            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                },
                /* verbose= */
                false);
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });
    </script>
@endsection
