// public/service-worker.js

// نسخه را بالا بردیم (v5 -> v6) تا کش قدیمیِ «مسموم» پاک شود.
// قبلا سرویس‌ورکر هر پاسخی (حتی صفحه‌ی 404/خطای HTML یا 504) را برای .js/.css کش می‌کرد؛
// برای همین swiper-bundle.min.js به‌جای JS، HTML برمی‌گرداند و خطای
// «Unexpected token '<'» و سپس «Swiper is not defined» ایجاد می‌شد.
const CACHE_NAME = 'sdfrApp-v6';
const STATIC_CACHE = CACHE_NAME + ':static';

// فقط فایل‌های واقعا استاتیک پیش‌کش می‌شوند (هیچ HTML داینامیکی اینجا نباشد)
const urlsToCache = [
    '/offline.html',
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
    '/client/sounds/Alarmclock.ogg',
    '/client/sounds/Funny.mp3',
    '/client/sounds/Modern.mp3',
    '/client/sounds/alarm.wav',
];

// فقط پاسخ سالم کش شود: همان origin، وضعیت 200، نوع basic (نه opaque/خطا)
function isCacheableResponse(res) {
    return res && res.ok && res.status === 200 && res.type === 'basic';
}

// نصب — پیش‌کش فایل‌های استاتیک (هرکدام جداگانه تا یک فایل ناموجود کل نصب را خراب نکند)
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE).then(async (cache) => {
            await Promise.all(
                urlsToCache.map((url) =>
                    cache.add(url).catch((err) => console.warn('Failed to cache:', url, err))
                )
            );
        }).then(() => self.skipWaiting())
    );
});

// فعال‌سازی — همه‌ی کش‌های نسخه‌های قبلی (از جمله کش مسموم) پاک می‌شوند
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.map((k) => (k !== STATIC_CACHE ? caches.delete(k) : null)))
        ).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const req = event.request;

    if (req.method !== 'GET') return;

    const url = new URL(req.url);
    if (url.origin !== self.location.origin) return;

    // مسیرهای داینامیک/حساس اصلا دست نمی‌خورند (network مستقیم مرورگر).
    const DYNAMIC_PREFIXES = [
        '/livewire',
        '/profile',
        '/admin',
        '/manager',
        '/school-manager',
        '/sign-in',
        '/signup',
        '/login',
        '/logout',
        '/cart',
        '/checkout',
    ];
    if (DYNAMIC_PREFIXES.some((p) => url.pathname === p || url.pathname.startsWith(p + '/'))) {
        return;
    }

    const STATIC_EXT = /\.(css|js|mjs|png|jpe?g|gif|svg|webp|ico|woff2?|ttf|otf|mp3|ogg|wav)$/i;
    const isStaticAsset =
        url.pathname.startsWith('/client/assets/') ||
        url.pathname.startsWith('/client/sounds/') ||
        url.pathname === '/manifest.json' ||
        STATIC_EXT.test(url.pathname);

    // فایل‌های استاتیک: Cache First، اما فقط پاسخ سالم ذخیره می‌شود
    if (isStaticAsset) {
        event.respondWith(
            caches.match(req).then((cached) => {
                if (cached) return cached;
                return fetch(req)
                    .then((res) => {
                        if (isCacheableResponse(res)) {
                            const copy = res.clone();
                            caches.open(STATIC_CACHE).then((cache) => cache.put(req, copy));
                        }
                        return res;
                    })
                    .catch(async () => (await caches.match(req)) || Response.error());
            })
        );
        return;
    }

    // صفحات HTML (ناوبری): Network First
    if (req.mode === 'navigate' || req.destination === 'document') {
        event.respondWith(
            fetch(req).catch(async () => {
                const offline = await caches.match('/offline.html');
                if (offline) return offline;
                const body = '<!doctype html><meta charset="utf-8"><h1 style="font-family:sans-serif;text-align:center;margin-top:3rem">No internet connection</h1>';
                return new Response(body, { status: 503, headers: { 'Content-Type': 'text/html; charset=utf-8' } });
            })
        );
        return;
    }

    // بقیه‌ی درخواست‌ها: مستقیم شبکه
});
