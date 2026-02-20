// service-worker.js - در مسیر public قرار بده

const CACHE_NAME = 'sdfrApp-v3';
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
    // اگر فایل CSS یا JS مهم داری اینجا اضافه کن
    '/client/assets/js/app.js',
    '/client/assets/js/dependencies/plyr.min.js',
    '/client/assets/js/dependencies/swiper-bundle.min.js',
    '/client/assets/js/story-player/story-player.js',
    '/client/assets/js/story-player/styles.css',

];

// نصب Service Worker
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            // به جای addAll، هر فایل رو جداگانه اضافه کن
            const promises = urlsToCache.map(url =>
                cache.add(url).catch(err => {
                    console.warn('⚠️ Failed to cache:', url, err);
                })
            );
            return Promise.all(promises);
        }).then(() => self.skipWaiting())
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
                    return caches.match('/offline.html');
                }
            })
    );
});
