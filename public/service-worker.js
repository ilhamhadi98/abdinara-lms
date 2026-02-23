const CACHE_NAME = 'abdinara-cat-v1';
const STATIC_ASSETS = [
    '/',
    '/manifest.json',
];

// -----------------------------------------------------------------------
// Install: cache static assets
// -----------------------------------------------------------------------
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_ASSETS))
    );
    self.skipWaiting();
});

// -----------------------------------------------------------------------
// Activate: clean up old caches
// -----------------------------------------------------------------------
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
        )
    );
    self.clients.claim();
});

// -----------------------------------------------------------------------
// Fetch: Network-first for API/dynamic, Cache-first for static assets
// -----------------------------------------------------------------------
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET and cross-origin
    if (request.method !== 'GET' || !url.origin.includes(self.location.origin)) return;

    // Livewire AJAX, API calls, Vite HMR → always network
    if (
        url.pathname.startsWith('/livewire/') ||
        url.pathname.startsWith('/api/') ||
        request.headers.get('X-Livewire')
    ) {
        event.respondWith(fetch(request));
        return;
    }

    // Static assets (JS, CSS, fonts, images) → Cache-first
    if (/\.(js|css|woff2?|ttf|png|jpg|svg|ico)(\?.*)?$/.test(url.pathname)) {
        event.respondWith(
            caches.match(request).then((cached) => {
                if (cached) return cached;
                return fetch(request).then((res) => {
                    const clone = res.clone();
                    caches.open(CACHE_NAME).then((c) => c.put(request, clone));
                    return res;
                });
            })
        );
        return;
    }

    // HTML pages → Network-first (fresh data), fallback cache
    event.respondWith(
        fetch(request)
            .then((res) => {
                const clone = res.clone();
                caches.open(CACHE_NAME).then((c) => c.put(request, clone));
                return res;
            })
            .catch(() => caches.match(request))
    );
});
