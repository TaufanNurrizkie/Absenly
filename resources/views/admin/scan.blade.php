@extends('layouts.adminNav')

@section('title', 'Scan QR Code')
@section('page-title', 'Scan QR Code Kehadiran')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Arahkan QR Code ke Kamera</h3>
        <div id="reader" width="600px" class="rounded-xl overflow-hidden shadow-inner"></div>
        <p class="text-sm text-slate-500 mt-4 text-center">Pastikan QR Code berada di dalam kotak scanner.</p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-center">
        <h3 class="text-lg font-bold text-slate-800 mb-4 text-center">Hasil Scan</h3>
        
        <div id="scan-result" class="text-center p-6 bg-slate-50 rounded-xl border border-slate-100">
            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <p class="text-slate-500 font-medium">Belum ada data yang discan.</p>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const resultContainer = document.getElementById('scan-result');
        let isProcessing = false;
        let html5QrcodeScanner = null;

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            isProcessing = true;
            
            if (html5QrcodeScanner) {
                html5QrcodeScanner.pause();
            }
            
            resultContainer.innerHTML = `<div class="animate-pulse flex flex-col items-center">
                <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                <p class="text-blue-600 font-bold">Mengambil data siswa...</p>
            </div>`;

            fetch('{{ route('admin.scan.info') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ user_id: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const user = data.user;
                    const photoHtml = user.foto 
                        ? `<img src="${user.foto}" class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-white shadow-md mb-3">`
                        : `<div class="w-24 h-24 rounded-full bg-slate-200 mx-auto flex items-center justify-center mb-3">
                               <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                           </div>`;

                    resultContainer.innerHTML = `
                        <div class="p-4">
                            ${photoHtml}
                            <h4 class="text-slate-800 font-bold text-xl">${user.name}</h4>
                            <p class="text-slate-500 font-medium mb-1">${user.nis}</p>
                            <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold mb-6">${user.kelas}</span>
                            
                            <div class="flex flex-col gap-3">
                                <button onclick="processAttendance('${decodedText}', 'hadir')" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-blue-500/30 transition-all">
                                    Approve Hadir
                                </button>
                                <button onclick="processAttendance('${decodedText}', 'pulang')" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-green-500/30 transition-all">
                                    Approve Pulang
                                </button>
                                <button onclick="resetScanner()" class="w-full bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-3 px-4 rounded-xl transition-all">
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
            resultContainer.innerHTML = `<div class="animate-pulse flex flex-col items-center">
                <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                <p class="text-blue-600 font-bold">Memproses absen ${tipe}...</p>
            </div>`;

            fetch('{{ route('admin.scan.process') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ user_id: userId, tipe_absen: tipe })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    resultContainer.innerHTML = `
                        <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                            <svg class="w-12 h-12 mx-auto text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h4 class="text-green-800 font-bold text-lg mb-1">${data.user}</h4>
                            <p class="text-green-600 font-medium mb-1">${data.message}</p>
                        </div>
                    `;
                    setTimeout(() => { resetScanner(); }, 3000);
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
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl mb-4">
                    <svg class="w-12 h-12 mx-auto text-red-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h4 class="text-red-800 font-bold text-lg mb-1">Gagal</h4>
                    <p class="text-red-600 text-sm">${message}</p>
                </div>
                <button onclick="resetScanner()" class="w-full bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-3 px-4 rounded-xl transition-all">
                    Kembali Scan
                </button>
            `;
        };

        window.resetScanner = function() {
            isProcessing = false;
            resultContainer.innerHTML = `
                <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <p class="text-slate-500 font-medium">Belum ada data yang discan.</p>
            `;
            if (html5QrcodeScanner) {
                html5QrcodeScanner.resume();
            }
        };

        function onScanFailure(error) {
            // handle scan failure silently
        }

        html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: {width: 250, height: 250} },
            /* verbose= */ false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    });
</script>
@endsection
