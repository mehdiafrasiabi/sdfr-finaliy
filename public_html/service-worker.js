// public/service-worker.js

const CACHE_NAME = 'sdfrApp-v4'; // نسخه رو عوض کن تا کلاینت‌ها SW جدید بگیرن
const STATIC_CACHE = CACHE_NAME + ':static';

const urlsToCache = [
    '/',
    '/manifest.json',
    '/client/load.png',
    '/client/load.svg',
    '/client/loading.png',
    '/client/assets/images/avatars/01.jpeg',
    '/client/assets/logoPwa/logo.png',
    '/client/assets/logoPwa/logo16.png',
    '/client/assets/logoPwa/logo32.png',
    '/client/assets/logoPwa/logo48.png',
    '/client/assets/logoPwa/logo57.png',
    '/client/assets/css/dependencies/plyr.min.css',
    '/client/assets/css/dependencies/swiper-bundle.min.css',
    '/client/assets/css/apexcharts.css',
    '/client/assets/css/app.css',
    '/client/assets/css/custom-pagination.css',
    '/client/assets/css/custom-pagination2.css',
    '/client/assets/css/fonts.css',
    '/client/assets/js/app.js',
    '/client/assets/js/dependencies/plyr.min.js',
    '/client/assets/js/dependencies/swiper-bundle.min.js',
    '/client/assets/js/story-player/story-player.js',
    '/client/assets/js/story-player/styles.css',
];

// نصب
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE).then(async (cache) => {
            await Promise.all(
                urlsToCache.map((url) =>
                    cache.add(url).catch((err) => console.warn('⚠️ Failed to cache:', url, err))
                )
            );
        }).then(() => self.skipWaiting())
    );
});

// فعال‌سازی
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.map((k) => (k !== STATIC_CACHE ? caches.delete(k) : null)))
        ).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const req = event.request;
    const url = new URL(req.url);

    // فقط همون origin خودمون
    if (url.origin !== self.location.origin) return;

    // ✅ 1) هرچی غیر GET هست اصلاً دست نزن (POST/PUT/...)
    if (req.method !== 'GET') return;

    // ✅ 2) Livewire endpoints رو کامل exclude کن
    if (url.pathname.startsWith('/livewire')) return;

    // ✅ 3) مسیرهای حساس سشن/لاگین/لاگ‌اوت رو هم exclude کن (اینا رو طبق پروژه‌ت تنظیم کن)
    const bypassPrefixes = [
        '/logout',
        '/sign-in',
        '/admin',
        '/manager',
        '/profile', // چون start_url و scope شما اینجاست
    ];
    // اگر client شما زیر /profile هست و صفحاتش داینامیکه، بهتره HTML ها network-first باشن (پایین‌تر)
    // اینجا صرفاً مثال بود. می‌تونی این قسمت رو حذف کنی.

    // ✅ 4) برای فایل‌های استاتیک Cache First
    const isStaticAsset =
        url.pathname.startsWith('/client/assets/') ||
        url.pathname === '/manifest.json' ||
        url.pathname.endsWith('.css') ||
        url.pathname.endsWith('.js') ||
        url.pathname.endsWith('.png') ||
        url.pathname.endsWith('.jpg') ||
        url.pathname.endsWith('.jpeg') ||
        url.pathname.endsWith('.svg') ||
        url.pathname.endsWith('.woff') ||
        url.pathname.endsWith('.woff2');

    if (isStaticAsset) {
        event.respondWith(
            caches.match(req).then((cached) => {
                if (cached) return cached;
                return fetch(req).then((res) => {
                    const copy = res.clone();
                    caches.open(STATIC_CACHE).then((cache) => cache.put(req, copy));
                    return res;
                });
            })
        );
        return;
    }

    // ✅ 5) برای HTML / صفحات: Network First (تا session/CSRF/Livewire قاطی نکنه)
    if (req.mode === 'navigate' || req.destination === 'document') {
        event.respondWith(
            fetch(req)
                .then((res) => res)
                .catch(() => caches.match('/offline.html'))
        );
        return;
    }

    // ✅ fallback
    event.respondWith(fetch(req).catch(() => caches.match(req)));
});
