<!DOCTYPE html>
<html lang="fa" dir="rtl" class="dark">


<head>
    {{--    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>--}}

    @include('layouts.client.link')
    @include('layouts.client.pwa')
    <!-- برای iOS -->

    {!! SEO::generate() !!}


</head>

<body class="dark">

<!-- container -->


<div class="flex flex-col min-h-screen bg-background">

    {{--    <div id="loadingOverlay">--}}
    {{--        <img src="/client/loading.png" alt="SDFR Loading Logo" class="loading-logo rounded-xl">--}}
    {{--    </div>--}}



    <!-- Banners Section -->
    <div class="banners-wrapper transition-all duration-300 ease-out overflow-hidden"
         :class="{
             'max-h-0 opacity-0': bannersHidden,
             'max-h-40 opacity-100': !bannersHidden
         }">

        <!-- PWA Banner -->
        <div id="pwaBanner" dir="rtl" class="w-full relative z-20" x-show="!pwaBannerClosed">
            <div class="w-full border-b border-slate-200/60 dark:border-slate-700/60
               bg-gradient-to-l from-blue-700 via-blue-600 to-indigo-700
               dark:from-slate-900 dark:via-slate-900 dark:to-slate-800
               text-white">
                <div
                    class="max-w-6xl mx-auto px-4 py-3 md:py-4 flex flex-col md:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-3 text-center md:text-right">
                        <div
                            class="shrink-0 w-10 h-10 rounded-2xl bg-white/15 dark:bg-white/10 flex items-center justify-center shadow-inner">
                            <span class="text-xl">📱</span>
                        </div>
                        <div>
                            <p class="font-extrabold text-base sm:text-lg md:text-xl leading-snug">
                                همین حالا <span class="text-yellow-300">SDFR</span> رو روی موبایلت داشته باش
                            </p>
                            <p class="text-xs sm:text-sm text-white/80 dark:text-white/70 mt-0.5">
                                نصب سریع، دسترسی راحت، تجربه بهتر ✨
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3">
                        <button id="installApp" type="button"
                                class="group relative overflow-hidden rounded-full px-4 sm:px-5 py-2 text-sm font-bold
                                       bg-emerald-500 hover:bg-emerald-400 active:scale-[0.98]
                                       shadow-md shadow-emerald-500/30 transition">
                            <span class="relative z-10">نصب اپلیکیشن</span>
                        </button>
                        <button id="closeBanner" type="button"
                                class="rounded-full px-4 sm:px-5 py-2 text-sm font-bold
                                       bg-rose-500 hover:bg-rose-400 active:scale-[0.98]
                                       shadow-md shadow-rose-500/25 transition">
                            بستن
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ANDROID Modal -->
    <div id="pwaAndroidModal" class="hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        <div class="relative mx-auto w-[92%] max-w-lg mt-16 sm:mt-24 rounded-2xl overflow-hidden
              bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-2xl">
            <div class="p-5 sm:p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-lg sm:text-xl font-extrabold">نصب اپلیکیشن روی اندروید</p>
                        <p class="mt-2 text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-7">
                            1) روی دکمه <b>«فهمیدم»</b> بزن.<br>
                            2) پنجره‌ی نصب مرورگر باز میشه.<br>
                            3) گزینه <b>Install</b> یا <b>Add</b> رو بزن تا اپ نصب بشه ✅
                        </p>
                    </div>
                    <button type="button" data-close-modal="android"
                            class="shrink-0 rounded-xl px-3 py-1.5 text-sm font-bold
                       bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 transition">
                        بستن
                    </button>
                </div>

                <div class="mt-5 flex flex-col sm:flex-row gap-2 sm:gap-3 sm:justify-end">
                    <button type="button" data-understood="android"
                            class="rounded-xl px-4 py-2 font-bold bg-emerald-500 hover:bg-emerald-400 text-white transition">
                        فهمیدم ✅
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- IOS Modal -->
    <div id="pwaIOSModal" class="hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        <div class="relative mx-auto w-[92%] max-w-lg mt-16 sm:mt-24 rounded-2xl overflow-hidden
              bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-2xl">
            <div class="p-5 sm:p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-lg sm:text-xl font-extrabold">نصب روی iPhone / iPad</p>
                        <p class="mt-2 text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-7">
                            iOS نصب خودکار نداره. برای نصب:<br>
                            1) پایین Safari روی دکمه <b>Share</b> بزن (آیکن مربع با فلش بالا).<br>
                            2) گزینه <b>Add to Home Screen</b> رو انتخاب کن.<br>
                            3) روی <b>Add</b> بزن ✅
                        </p>
                    </div>
                    <button type="button" data-close-modal="ios"
                            class="shrink-0 rounded-xl px-3 py-1.5 text-sm font-bold
                       bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 transition">
                        بستن
                    </button>
                </div>

                <div class="mt-5 flex flex-col sm:flex-row gap-2 sm:gap-3 sm:justify-end">
                    <button type="button" data-close-modal="ios"
                            class="rounded-xl px-4 py-2 font-bold bg-emerald-500 hover:bg-emerald-400 text-white transition">
                        فهمیدم ✅
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Loading Overlay برای نصب -->
    <div id="pwaLoading"
         class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
        <div class=" rounded-lg p-8 text-center max-w-sm mx-4">
            <!-- Spinner -->

            <h3 class="text-xl font-bold text-green-500 mb-2">در حال نصب...</h3>
            <p class="text-foreground">لطفاً چند لحظه صبر کنید</p>
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                 class="justify-center"
                 viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" width="40px" height="40px"
                 style="shape-rendering: auto; display: block; background: transparent;">
                <g>
                    <path stroke="none" fill="#ffffff"
                          d="M19 50A31 31 0 0 0 81 50A31 34 0 0 1 19 50">
                        <animateTransform values="0 50 51.5;360 50 51.5" keyTimes="0;1"
                                          repeatCount="indefinite" dur="0.8130081300813008s"
                                          type="rotate" attributeName="transform"/>
                    </path>
                    <g/>
                </g>
            </svg>
        </div>
    </div>


    <livewire:client.layout.header/>

    <!-- end header -->

    <main class="flex-auto py-5">
        {{$slot}}
    </main>

    <!-- footer -->
    <livewire:client.layout.footer/>
    <!-- end footer -->

    <!-- Floating Support Button -->
    <livewire:client.layout.floating-support/>

    <!-- Mobile Bottom Navigation - Fixed at bottom -->

    <livewire:client.layout.mobile-bottom-nav/>

    <div id="video-modal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4">
        <div class="absolute inset-0" data-close-video></div>

        <div class="relative w-full max-w-4xl bg-background dark:bg-slate-900 rounded-2xl shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between p-4 md:p-5 border-b border-border">
                <h3 id="video-modal-title" class="text-lg font-bold text-foreground">ویدیو راهنما</h3>

                <button type="button" data-close-video
                        class="w-8 h-8 rounded-full hover:bg-secondary text-white flex items-center justify-center transition-colors">
                    ✕
                </button>
            </div>

            <div class="p-4 md:p-6">
                <div id="video-container" class="w-full aspect-video bg-black rounded-lg overflow-hidden"></div>
            </div>
        </div>
    </div>

</div>


@include('layouts.client.script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const loadingOverlay = document.getElementById('loadingOverlay');
        const mainContentContainer = document.getElementById('mainContentContainer');
        const logoImage = document.getElementById('logoImage');
        const comingSoonText = document.getElementById('comingSoonText');

        // Simulate content loading time
        setTimeout(() => {
            // Hide loading overlay
            loadingOverlay.classList.add('hidden');

            // Show main content container
            mainContentContainer.classList.add('visible');

            // Start the "Coming Soon" animation after the loading screen fades out
            setTimeout(() => {
                logoImage.style.opacity = 1;
                logoImage.style.transform = 'translateY(0)';

                setTimeout(() => {
                    comingSoonText.style.opacity = 1;
                    comingSoonText.style.transform = 'scale(1)';
                }, 1500); // Start "Coming Soon" animation after 1.5 seconds
            }, 1000); // Wait for loading overlay to fully fade out (1 second transition)

        }, 3000); // Simulate 3 seconds of loading time
    });

    function checkInternetSpeed() {
        const imageAddr = "/client/loading.png";
        let startTime, endTime;
        const downloadSize = 56119;
        const download = new Image();

        download.onload = function () {
            endTime = new Date().getTime();
            const duration = (endTime - startTime) / 1000;
            const bitsLoaded = downloadSize * 8;
            const speedBps = (bitsLoaded / duration).toFixed(2);
            const speedKbps = (speedBps / 1024).toFixed(2);

            let animationDuration = 3000; // پیش‌فرض 3 ثانیه
            if (speedKbps > 500) animationDuration = 1000; // اینترنت سریع
            else if (speedKbps > 100) animationDuration = 2000; // اینترنت متوسط

            const rocket = document.querySelector('.rocket');
            rocket.style.animationDuration = `${animationDuration}ms`;
            rocket.style.animationIterationCount = 1; // فقط یک بار حرکت
            rocket.addEventListener('animationend', () => {
                rocket.style.display = 'none'; // مخفی کردن موشک بعد از انیمیشن
            });
        };

        startTime = new Date().getTime();
        download.src = imageAddr + "?t=" + startTime;
    }

    window.onload = checkInternetSpeed;

    //remove wire:snapshot form tags in client

    let attrs = [
        'snapshot',
        'effects',
        // 'id'
    ];

    function snapKill() {
        document.querySelectorAll('div').forEach(function (element) {
            for (let i in attrs) {
                if (element.getAttribute(`wire:${attrs[i]}`) !== null) {
                    element.removeAttribute(`wire:${attrs[i]}`);
                }
            }
        });
    }

    window.addEventListener('load', (ev) => {
        snapKill();
    });
</script>

<script>
    function collapseGuide(key) {
        return {
            open: false,

            init() {
                const saved = localStorage.getItem(key);
                this.open = saved !== null ? saved === 'true' : true;
            },

            toggle() {
                this.open = !this.open;
                localStorage.setItem(key, this.open);
            }
        }
    }
</script>

<script>
    (function () {
        const modal = () => document.getElementById('video-modal');
        const container = () => document.getElementById('video-container');
        const titleEl = () => document.getElementById('video-modal-title');

        function openVideo({url, title}) {
            const m = modal();
            const c = container();
            if (!m || !c) return;

            c.innerHTML = '';

            // بهترین روش برای آپارات: iframe
            const iframe = document.createElement('iframe');
            iframe.src = url;
            iframe.setAttribute('allowfullscreen', 'true');
            iframe.setAttribute('allow', 'autoplay; encrypted-media');
            iframe.className = 'w-full h-full';
            iframe.style.border = '0';
            c.appendChild(iframe);

            if (titleEl() && title) titleEl().textContent = title;

            m.classList.remove('hidden');
            m.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeVideo() {
            const m = modal();
            const c = container();
            if (!m || !c) return;

            m.classList.add('hidden');
            m.classList.remove('flex');
            c.innerHTML = '';
            document.body.style.overflow = '';
        }

        // کلیک روی دکمه‌های باز کردن (حتی بعد از navigate)
        document.addEventListener('click', (e) => {
            const openBtn = e.target.closest('[data-video-url]');
            if (openBtn) {
                const url = openBtn.getAttribute('data-video-url');
                const title = openBtn.getAttribute('data-video-title') || 'ویدیو راهنما';
                if (url) openVideo({url, title});
                return;
            }

            // بستن مدال
            if (e.target.closest('[data-close-video]')) {
                closeVideo();
                return;
            }
        });

        // ESC برای بستن
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeVideo();
        });

        // Livewire v3: بعد از navigate اگر مدال باز مونده بود، ببند
        document.addEventListener('livewire:navigated', () => {
            closeVideo();
        });

        // برای استفاده در جاهای دیگه (اختیاری)
        window.openVideoModal = (url, title = 'ویدیو راهنما') => openVideo({url, title});
        window.closeVideoModal = closeVideo;
    })();
</script>
<script>
    const banner = document.getElementById('pwaBanner');
    const installBtn = document.getElementById('installApp');
    const closeBannerBtn = document.getElementById('closeBanner'); // ✅ id درست

    const androidModal = document.getElementById('pwaAndroidModal');
    const iosModal = document.getElementById('pwaIOSModal');

    if (!banner || !installBtn || !androidModal || !iosModal) {
        console.warn('PWA elements not found on this page.');
    } else {

        let deferredPrompt = null;

        const LS_KEY_DISMISS  = 'pwa_banner_dismiss_until';
        const LS_KEY_INSTALLED = 'pwa_installed';

        // ── helpers ──────────────────────────────────────────────────────────
        function isDismissedNow() {
            const until = Number(localStorage.getItem(LS_KEY_DISMISS) || 0);
            return Date.now() < until;
        }

        function dismissForDays(days) {
            const until = Date.now() + (days * 24 * 60 * 60 * 1000);
            localStorage.setItem(LS_KEY_DISMISS, String(until));
        }

        function setInstalled() {
            localStorage.setItem(LS_KEY_INSTALLED, 'true');
        }

        function isInstalledSaved() {
            return localStorage.getItem(LS_KEY_INSTALLED) === 'true';
        }

        // ── وضعیت نصب فعلی ───────────────────────────────────────────────────
        const isInStandaloneMode =
            window.matchMedia('(display-mode: standalone)').matches ||
            window.navigator.standalone ||
            document.referrer.startsWith('android-app://');

        const isIOS     = /iPhone|iPad|iPod/i.test(navigator.userAgent);
        const isAndroid = /Android/i.test(navigator.userAgent);

        function isMobile() {
            return window.matchMedia('(max-width: 1024px)').matches;
        }

        // ── modal helpers ─────────────────────────────────────────────────────
        function openModal(type) {
            if (type === 'android') androidModal.classList.remove('hidden');
            if (type === 'ios')     iosModal.classList.remove('hidden');
            document.documentElement.classList.add('overflow-hidden');
        }

        function closeModal(type) {
            if (type === 'android') androidModal.classList.add('hidden');
            if (type === 'ios')     iosModal.classList.add('hidden');
            document.documentElement.classList.remove('overflow-hidden');
        }

        // ── نصب واقعی ────────────────────────────────────────────────────────
        async function triggerInstallPrompt() {
            if (!deferredPrompt) {
                alert('متاسفانه مرورگر شما از نصب خودکار پشتیبانی نمی‌کند.');
                return;
            }
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            if (outcome === 'accepted') {
                setInstalled();
                hideBanner();
            }
            deferredPrompt = null;
        }

        // ── نمایش / مخفی بنر ─────────────────────────────────────────────────
        function hideBanner() {
            banner.classList.add('hidden');
        }

        function canShowBanner() {
            if (isInStandaloneMode) return false;   // قبلاً نصب شده (standalone)
            if (isInstalledSaved()) return false;   // localStorage نشان می‌دهد نصب شده
            if (isDismissedNow())   return false;   // کاربر "بستن" زده و هنوز ۷ روز نگذشته
            return true;
        }

        // ── بارگذاری اولیه ────────────────────────────────────────────────────
        window.addEventListener('load', () => {
            if (!canShowBanner()) return;
            setTimeout(() => banner.classList.remove('hidden'), 800);
        });

        // ── رویداد نصب مرورگر ────────────────────────────────────────────────
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (canShowBanner()) {
                banner.classList.remove('hidden');
            }
        });

        // ── دکمه بستن ✅ اکنون کار می‌کند ─────────────────────────────────────
        if (closeBannerBtn) {
            closeBannerBtn.addEventListener('click', () => {
                dismissForDays(7);   // ۷ روز نشان نده
                hideBanner();
            });
        }

        // ── دکمه نصب ─────────────────────────────────────────────────────────
        installBtn.addEventListener('click', async () => {
            if (isIOS)                  { openModal('ios');     return; }
            if (isAndroid && isMobile()) { openModal('android'); return; }
            await triggerInstallPrompt();
        });

        // ── بعد از نصب موفق ───────────────────────────────────────────────────
        window.addEventListener('appinstalled', () => {
            setInstalled();
            hideBanner();
            deferredPrompt = null;
        });

        // ── بستن مودال‌ها ─────────────────────────────────────────────────────
        document.querySelectorAll('[data-close-modal]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                closeModal(e.currentTarget.getAttribute('data-close-modal'));
            });
        });

        document.querySelectorAll('[data-understood="android"]').forEach(btn => {
            btn.addEventListener('click', async () => {
                closeModal('android');
                await triggerInstallPrompt();
            });
        });
    }</script>

</body>

</html>
