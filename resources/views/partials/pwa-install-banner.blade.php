<!-- PWA Install Prompt Banner (Android & iOS) -->
<div id="pwa-install-banner" class="fixed bottom-4 left-4 right-4 md:left-auto md:right-4 md:max-w-md bg-white border border-blue-200 rounded-2xl shadow-2xl p-4 z-50 hidden transition-all duration-300">
    <div class="flex items-start gap-3">
        <img src="{{ asset('img/icons/icon-96x96.png') }}" alt="Logo" class="w-12 h-12 rounded-xl object-contain shadow-sm border border-blue-100 p-1 flex-shrink-0 bg-blue-50">
        <div class="flex-1 min-w-0">
            <h4 class="text-sm font-bold text-gray-900 leading-tight">Install Aplikasi Presensi</h4>
            <p id="pwa-banner-desc" class="text-xs text-gray-600 mt-1 leading-relaxed">
                Pasang di HP Anda untuk akses lebih cepat & mudah tanpa buka browser.
            </p>

            <!-- Android Install Action -->
            <div id="pwa-android-actions" class="mt-3 flex items-center gap-2">
                <button id="pwa-install-btn" type="button" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-semibold rounded-lg shadow transition">
                    Install Sekarang
                </button>
                <button id="pwa-dismiss-btn" type="button" class="px-3 py-1.5 text-xs text-gray-500 hover:text-gray-700 font-medium">
                    Nanti Saja
                </button>
            </div>

            <!-- iOS Instructions Guide -->
            <div id="pwa-ios-actions" class="mt-2.5 p-2 bg-blue-50/70 border border-blue-100 rounded-lg text-xs text-blue-900 hidden">
                <p class="font-medium flex items-center gap-1.5">
                    <span>Cara pasang di iPhone:</span>
                </p>
                <ol class="list-decimal list-inside space-y-1 mt-1 text-[11px] text-gray-700">
                    <li>Ketuk ikon <strong>Bagikan (Share)</strong> <svg class="inline w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg> di bawah layar Safari.</li>
                    <li>Gulir & pilih <strong>Tambahkan ke Layar Utama</strong> (Add to Home Screen).</li>
                </ol>
                <div class="mt-2 text-right">
                    <button id="pwa-ios-dismiss-btn" type="button" class="text-[11px] font-semibold text-blue-600 hover:underline">
                        Mengerti
                    </button>
                </div>
            </div>
        </div>

        <button id="pwa-close-btn" type="button" class="text-gray-400 hover:text-gray-600 text-lg leading-none -mt-1 p-1" title="Tutup">
            &times;
        </button>
    </div>
</div>

<script>
(function() {
    const banner = document.getElementById('pwa-install-banner');
    const installBtn = document.getElementById('pwa-install-btn');
    const dismissBtn = document.getElementById('pwa-dismiss-btn');
    const closeBtn = document.getElementById('pwa-close-btn');
    const androidActions = document.getElementById('pwa-android-actions');
    const iosActions = document.getElementById('pwa-ios-actions');
    const iosDismissBtn = document.getElementById('pwa-ios-dismiss-btn');

    // Cek apakah sudah dalam mode standalone (sudah terinstall sebagai PWA)
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
    if (isStandalone) {
        return; // Jangan tampilkan jika sudah diinstall
    }

    // Cek apakah user pernah menutup banner dalam 7 hari terakhir
    const dismissedTime = localStorage.getItem('pwa_prompt_dismissed');
    if (dismissedTime && (Date.now() - parseInt(dismissedTime, 10)) < 7 * 24 * 60 * 60 * 1000) {
        return;
    }

    function dismissBanner() {
        if (banner) banner.classList.add('hidden');
        localStorage.setItem('pwa_prompt_dismissed', Date.now().toString());
    }

    if (dismissBtn) dismissBtn.addEventListener('click', dismissBanner);
    if (closeBtn) closeBtn.addEventListener('click', dismissBanner);
    if (iosDismissBtn) iosDismissBtn.addEventListener('click', dismissBanner);

    // Cek apakah perangkat adalah iOS (iPhone / iPad)
    const isIos = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

    if (isIos) {
        // Tampilkan instruksi khusus iOS setelah 3 detik
        setTimeout(() => {
            if (banner && androidActions && iosActions) {
                androidActions.classList.add('hidden');
                iosActions.classList.remove('hidden');
                banner.classList.remove('hidden');
            }
        }, 3000);
    } else {
        // Android / Chrome: Menangkap event beforeinstallprompt
        let deferredPrompt = null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (banner) {
                banner.classList.remove('hidden');
            }
        });

        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    console.log('[PWA] User accepted install');
                }
                deferredPrompt = null;
                dismissBanner();
            });
        }

        window.addEventListener('appinstalled', () => {
            if (banner) banner.classList.add('hidden');
            console.log('[PWA] Aplikasi berhasil diinstall');
        });
    }
})();
</script>
