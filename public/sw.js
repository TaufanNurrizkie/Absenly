const CACHE_NAME = 'presensi-icb-v1';
const STATIC_ASSETS = [
    '/',
    '/manifest.json',
    '/img/icb_Logo.png',
    '/img/icons/icon-192x192.png',
    '/img/icons/icon-512x512.png',
    '/img/icons/apple-touch-icon.png'
];

// Install Event
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(STATIC_ASSETS).catch((err) => {
                console.warn('[SW] Cache addAll warning:', err);
            });
        }).then(() => self.skipWaiting())
    );
});

// Activate Event
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch Event
self.addEventListener('fetch', (event) => {
    // Only handle GET requests
    if (event.request.method !== 'GET') {
        return;
    }

    const url = new URL(event.request.url);

    // Bypass caching for admin / api / auth / storage routes that must always be fresh
    if (url.pathname.startsWith('/admin') ||
        url.pathname.startsWith('/guru') ||
        url.pathname.startsWith('/siswa/absen') ||
        url.pathname.startsWith('/deploy')) {
        return;
    }

    // For static assets (images, css, js, fonts), use Stale-While-Revalidate
    if (url.pathname.match(/\.(css|js|png|jpg|jpeg|svg|webp|woff2?|ico)$/)) {
        event.respondWith(
            caches.open(CACHE_NAME).then((cache) => {
                return cache.match(event.request).then((cachedResponse) => {
                    const fetchPromise = fetch(event.request).then((networkResponse) => {
                        if (networkResponse && networkResponse.status === 200) {
                            cache.put(event.request, networkResponse.clone());
                        }
                        return networkResponse;
                    }).catch(() => cachedResponse);

                    return cachedResponse || fetchPromise;
                });
            })
        );
        return;
    }

    // For HTML navigation requests: Network first with cache fallback
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => {
                return caches.match(event.request).then((response) => {
                    return response || caches.match('/');
                });
            })
        );
    }
});
