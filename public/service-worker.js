const CACHE_NAME = 'trace-pwa-v3';
const APP_SHELL = [
    './manifest.json',
    './Assets/Css/MobileApp.css',
    './Assets/Js/MobileApp.js',
    './Assets/Vendor/AOS/aos.css',
    './Assets/Vendor/AOS/aos.js',
    './Assets/Icons/AppIcon-192.png',
    './Assets/Icons/AppIcon-512.png',
    './Assets/Image/logo.png'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(APP_SHELL))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key)))
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') {
        return;
    }

    const requestUrl = new URL(event.request.url);

    // Always prefer fresh HTML/app pages so UI changes do not bounce back to old cached screens.
    if (event.request.mode === 'navigate' || event.request.destination === 'document') {
        event.respondWith(
            fetch(event.request)
                .then((networkResponse) => networkResponse)
                .catch(() => caches.match('./manifest.json').then(() => caches.match(event.request)).then((fallback) => fallback || Response.error()))
        );
        return;
    }

    const isStaticAsset =
        requestUrl.pathname.includes('/Assets/') ||
        requestUrl.pathname.endsWith('/manifest.json') ||
        requestUrl.pathname.endsWith('/service-worker.js');

    if (!isStaticAsset) {
        event.respondWith(fetch(event.request));
        return;
    }

    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            if (cachedResponse) {
                return cachedResponse;
            }

            return fetch(event.request)
                .then((networkResponse) => {
                    const clone = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
                    return networkResponse;
                })
                .catch(() => cachedResponse || Response.error());
        })
    );
});
