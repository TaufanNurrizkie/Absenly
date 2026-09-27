<!-- PWA Install Prompt Banner & Persistent Floating Button (Android & iOS) -->

<!-- 1. Banner Utama -->
<div id="pwa-install-banner" class="fixed bottom-4 left-4 right-4 md:left-auto md:right-4 md:max-w-md bg-white border border-blue-200 rounded-2xl shadow-2xl p-4 z-50 hidden transition-all duration-300">
    <div class="flex items-start gap-3">
        <img src="{{ asset('img/icons/icon-96x96.png') }}" alt="Logo" class="w-12 h-12 rounded-xl object-contain shadow-sm border border-blue-100 p-1 flex-shrink-0 bg-blue-50">
        <div class="flex-1 min-w-0">
            <h4 class="text-sm font-bold text-gray-900 leading-tight">Install Aplikasi Presensi</h4>
            <p id="pwa-banner-desc" class="text-xs text-gray-600 mt-1 leading-relaxed">
                Pasang di HP Anda agar absen lebih cepat & mudah tanpa harus buka browser.
            </p>

            <!-- Android Install Action -->
            <div id="pwa-android-actions" class="mt-3 flex items-center gap-2">
                <button id="pwa-install-btn" type="button" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-semibold rounded-lg shadow transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Install Sekarang</span>
                </button>
                <button id="pwa-dismiss-btn" type="button" class="px-3 py-1.5 text-xs text-gray-500 hover:text-gray-700 font-medium">
                    Nanti Saja
                </button>
            </div>

            <!-- iOS Instructions Guide -->
            <div id="pwa-ios-actions" class="mt-2.5 p-2.5 bg-blue-50/80 border border-blue-100 rounded-xl text-xs text-blue-900 hidden">
                <p class="font-semibold flex items-center gap-1.5 text-blue-950">
                    <span>Cara pasang di iPhone / iPad:</span>
                </p>
                <ol class="list-decimal list-inside space-y-1.5 mt-1.5 text-[11px] text-gray-700 leading-normal">
                    <li>Ketuk tombol <strong>Bagikan (Share)</strong> <svg class="inline w-3.5 h-3.5 text-blue-600 align-text-bottom" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg> di Safari.</li>
                    <li>Gulir ke bawah & pilih <strong>Tambahkan ke Layar Utama</strong> (Add to Home Screen).</li>
                </ol>
                <div class="mt-2 text-right">
                    <button id="pwa-ios-dismiss-btn" type="button" class="text-[11px] font-semibold text-blue-600 hover:underline">
                        Tutup Panduan
                    </button>
                </div>
            </div>
        </div>

        <button id="pwa-close-btn" type="button" class="text-gray-400 hover:text-gray-600 text-lg leading-none -mt-1 p-1" title="Tutup">
            &times;
        </button>
    </div>
</div>

<!-- 2. Tombol Melayang Permanen (Floating Pill) jika banner ditutup / di-X -->
<button id="pwa-floating-btn" type="button" title="Install Aplikasi Presensi" class="fixed bottom-5 right-5 z-40 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 active:scale-95 text-white font-medium text-xs py-2 px-3.5 rounded-full shadow-xl flex items-center gap-2 transition-all duration-300 border-2 border-white/80 hidden">
    <svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
    </svg>
    <span class="font-semibold tracking-wide">Install App</span>
</button>

<script>
(function() {
    const banner = document.getElementById('pwa-install-banner');
    const floatingBtn = document.getElementById('pwa-floating-btn');
    const installBtn = document.getElementById('pwa-install-btn');
    const dismissBtn = document.getElementById('pwa-dismiss-btn');
    const closeBtn = document.getElementById('pwa-close-btn');
    const androidActions = document.getElementById('pwa-android-actions');
    const iosActions = document.getElementById('pwa-ios-actions');
    const iosDismissBtn = document.getElementById('pwa-ios-dismiss-btn');

    // Cek apakah sudah berjalan dalam mode PWA terinstall (Standalone)
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
    if (isStandalone) {
        return; // Jangan tampilkan apa pun jika aplikasi sudah terpasang
    }

    const isIos = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    let deferredPrompt = null;
    let isMinimized = localStorage.getItem('pwa_banner_minimized') === 'true';

    // Fungsi menampilkan kembali banner utama
    window.showPwaInstall = function() {
        if (banner) {
            banner.classList.remove('hidden');
        }
        if (floatingBtn) {
            floatingBtn.classList.add('hidden');
        }
        if (isIos && iosActions && androidActions) {
            androidActions.classList.add('hidden');
            iosActions.classList.remove('hidden');
        }
    };

    // Fungsi saat banner ditutup (X atau Nanti Saja)
    // Banner tertutup, tapi tombol melayang (Floating Button) tetap standby agar bisa dipencet kapan saja!
    function minimizeBanner() {
        if (banner) banner.classList.add('hidden');
        if (floatingBtn) floatingBtn.classList.remove('hidden');
        localStorage.setItem('pwa_banner_minimized', 'true');
        isMinimized = true;
    }

    if (dismissBtn) dismissBtn.addEventListener('click', minimizeBanner);
    if (closeBtn) closeBtn.addEventListener('click', minimizeBanner);
    if (iosDismissBtn) iosDismissBtn.addEventListener('click', minimizeBanner);

    // Klik tombol melayang untuk membuka kembali install/panduan
    if (floatingBtn) {
        floatingBtn.addEventListener('click', () => {
            if (isIos) {
                window.showPwaInstall();
            } else if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(({ outcome }) => {
                    if (outcome === 'accepted') {
                        if (floatingBtn) floatingBtn.classList.add('hidden');
                    }
                    deferredPrompt = null;
                });
            } else {
                window.showPwaInstall();
            }
        });
    }

    // Tombol atau link eksternal yang memiliki class "btn-trigger-pwa-install"
    document.querySelectorAll('.btn-trigger-pwa-install').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            if (floatingBtn) floatingBtn.click();
            else window.showPwaInstall();
        });
    });

    if (isIos) {
        if (isMinimized) {
            // Jika sebelumnya sudah diminimize, langsung tampilkan tombol melayang saja
            if (floatingBtn) floatingBtn.classList.remove('hidden');
        } else {
            // Tampilkan banner awal setelah 2.5 detik
            setTimeout(() => {
                if (banner && androidActions && iosActions) {
                    androidActions.classList.add('hidden');
                    iosActions.classList.remove('hidden');
                    banner.classList.remove('hidden');
                }
            }, 2500);
        }
    } else {
        // Pada Android / Chrome: Tangkap prompt instalasi PWA
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            if (isMinimized) {
                // Jika sebelumnya sudah pernah pencet X, langsung tampilkan tombol melayang saja
                if (floatingBtn) floatingBtn.classList.remove('hidden');
            } else {
                // Tampilkan banner awal
                if (banner) {
                    banner.classList.remove('hidden');
                }
            }
        });

        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) {
                    minimizeBanner();
                    return;
                }
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    console.log('[PWA] User accepted install');
                    if (banner) banner.classList.add('hidden');
                    if (floatingBtn) floatingBtn.classList.add('hidden');
                } else {
                    minimizeBanner();
                }
                deferredPrompt = null;
            });
        }

        window.addEventListener('appinstalled', () => {
            if (banner) banner.classList.add('hidden');
            if (floatingBtn) floatingBtn.classList.add('hidden');
            localStorage.removeItem('pwa_banner_minimized');
            console.log('[PWA] Aplikasi berhasil terinstall di perangkat');
        });
    }
})();
</script>
