<!-- PWA & Mobile Web App Meta -->
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#2563EB">
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="Presensi ICB">

<!-- Apple iOS Safari Meta -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="Presensi ICB">
<link rel="apple-touch-icon" href="{{ asset('img/icons/apple-touch-icon.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/icons/apple-touch-icon.png') }}">
<link rel="apple-touch-icon" sizes="192x192" href="{{ asset('img/icons/icon-192x192.png') }}">

<!-- Register Service Worker -->
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('{{ asset('sw.js') }}')
                .then(reg => {
                    console.log('[PWA] Service Worker aktif:', reg.scope);
                })
                .catch(err => {
                    console.warn('[PWA] Service Worker gagal:', err);
                });
        });
    }
</script>
