// service-worker.js - در مسیر public قرار بده

const CACHE_NAME = 'sdfrApp-v2';
const urlsToCache = [
    '/',
    '/manifest.json',
    '/client/load.png',
    '/client/load.svg',
    '/client/loading.png',
    '/client/assets/images/avatars/s.webp',
    '/client/assets/images/avatars/d.webp',
    '/client/assets/images/avatars/f.webp',
    '/client/assets/images/avatars/r.webp',
    '/client/assets/images/avatars/01.jpeg',
    '/client/assets/logoPwa/logoMobile.png',
    '/client/assets/logoPwa/logoMobile2.png',
    '/client/assets/logoPwa/logoMobile3.png',
    '/client/assets/logoPwa/logoMobile4.png',
    '/client/assets/logoPwa/logoMobile5.png',
    '/client/assets/css/dependencies/plyr.min.css',
    '/client/assets/css/dependencies/swiper-bundle.min.css',
    '/client/assets/css/apexcharts.css',
    '/client/assets/css/app.css',
    '/client/assets/css/custom-pagination.css',
    '/client/assets/css/custom-pagination2.css',
    '/client/assets/css/fonts.css',
    // اگر فایل CSS یا JS مهم داری اینجا اضافه کن
    '/client/assets/js/app.js',
    '/client/assets/js/dependencies/plyr.min.js',
    '/client/assets/js/dependencies/swiper-bundle.min.js',
    '/client/assets/js/story-player/story-player.js',
    '/client/assets/js/story-player/styles.css',

];

// نصب Service Worker
self.addEventListener('install', (event) => {
    console.log('✅ Service Worker installing...');
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('📦 Caching files');
                return cache.addAll(urlsToCache);
            })
            .then(() => self.skipWaiting()) // فوری فعال بشه
    );
});

// فعال‌سازی
self.addEventListener('activate', (event) => {
    console.log('✅ Service Worker activated');
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        console.log('🗑️ Deleting old cache:', cache);
                        return caches.delete(cache);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// استراتژی Cache First (برای فایل‌های استاتیک)
self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request)
            .then((response) => {
                // اگر در Cache بود برگردون، وگرنه از شبکه بگیر
                return response || fetch(event.request);
            })
            .catch(() => {
                // اگر آفلاین بود و صفحه HTML بود، صفحه آفلاین نشون بده
                if (event.request.destination === 'document') {
                    return caches.match('/');
                }
            })
    );
});
