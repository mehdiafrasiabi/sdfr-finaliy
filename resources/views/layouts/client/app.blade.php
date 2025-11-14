<!DOCTYPE html>
<html lang="fa" dir="rtl" class="dark">


<head>
    @include('layouts.client.link')
    @include('layouts.client.pwa')
    <!-- برای iOS -->

    {!! SEO::generate() !!}

    <style>
        /* اطمینان از نمایش درست */
        #pwaBanner.hidden,
        #pwaLoading.hidden {
            display: none !important;
            background-color: #2866c8;
        }

        .blur-container {
            position: relative;
        }

        .blur-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px) brightness(0.7);
            border-radius: 16px;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .blur-container *:not(.blur-overlay, .blur-overlay *) {
            pointer-events: none;
            user-select: none;
        }
    </style>

</head>

<body class="dark">

<!-- container -->


<div class="flex flex-col min-h-screen bg-background">

    {{--    <div id="loadingOverlay">--}}
    {{--        <img src="/client/loading.png" alt="SDFR Loading Logo" class="loading-logo rounded-xl">--}}
    {{--    </div>--}}

    <div id="pwaBanner" style="background-color: #0a53be"
         class="hidden w-full flex-wrap md:flex-nowrap justify-center md:justify-between items-center md:py-4 py-2 gap-2 bg-green-700 z-20 relative">

        <!-- متن سمت راست -->
        <p class="font-black xs:text-2xl text-lg text-white text-center md:text-right">
            📱 همین حالا SDFR رو روی موبایلت داشته باش
        </p>

        <!-- دکمه‌ها سمت چپ -->
        <div class="flex items-center gap-3 mt-2 md:mt-0 justify-center">
            <button id="installApp" type="button"
                    class="bg-green-500 text-white font-semibold justify-center px-4 py-2 rounded-full text-sm hover:opacity-80 transition ">
                نصب اپلیکیشن
            </button>
            <button id="closeBanner"
                    class="bg-red-500 text-white font-semibold px-4 py-2 rounded-full text-sm hover:opacity-80 transition">
                نمیخوام
            </button>
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






    <header class="bg-background/80 backdrop-blur-xl border-b border-border sticky top-0 z-30"
            x-data="{ offcanvasOpen: false }">


        <!-- بنر نصب PWA -->


        <livewire:client.layout.header/>
    </header>


    <!-- end header -->

    <main class="flex-auto py-5">
        {{$slot}}
    </main>

    <!-- footer -->
    <livewire:client.layout.footer/>
    <!-- end footer -->
</div>
<script>
    const banner = document.getElementById('pwaBanner');
    const installBtn = document.getElementById('installApp');
    const closeBanner = document.getElementById('closeBanner');
    const loadingOverlay = document.getElementById('pwaLoading');
    let deferredPrompt = null;

    // ✅ تابع کوکی
    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${value};expires=${date.toUTCString()};path=/`;
    }

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }

    // 🔹 چک کردن حالت PWA
    const isInStandaloneMode =
        (window.matchMedia('(display-mode: standalone)').matches) ||
        (window.navigator.standalone) ||
        document.referrer.startsWith('android-app://');

    // 🔹 تشخیص iOS
    const isIOS = /iPhone|iPad|iPod/.test(navigator.userAgent);

    // 🔹 گوش دادن به beforeinstallprompt (فقط Android/Chrome)
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;

        // اگر کوکی "بستن" ست نشده باشه، نشون بده
        if (!isInStandaloneMode && !getCookie('pwa-dismissed') && !getCookie('pwa-installed')) {
            banner.classList.remove('hidden');
        }
    });

    // 🔹 دکمه نصب
    installBtn.addEventListener('click', async () => {
        if (isIOS) {
            // برای iOS راهنمایی نشون بده
            alert('📱 برای نصب:\n۱. روی دکمه Share بزن\n۲. "Add to Home Screen" رو انتخاب کن');
            return;
        }

        if (!deferredPrompt) {
            alert('متاسفانه مرورگر شما از نصب خودکار پشتیبانی نمیکنه');
            return;
        }

        // نمایش Loading
        loadingOverlay.classList.remove('hidden');

        try {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            console.log('✅ userChoice:', outcome);

            if (outcome === 'accepted') {
                setCookie('pwa-installed', 'true', 365);
                banner.classList.add('hidden');
            }
        } catch (error) {
            console.error('❌ Install failed:', error);
        } finally {
            loadingOverlay.classList.add('hidden');
            deferredPrompt = null;
        }
    });

    // 🔹 وقتی کاربر PWA رو نصب کرد
    window.addEventListener('appinstalled', () => {
        console.log('✅ PWA installed');
        setCookie('pwa-installed', 'true', 365);
        banner.classList.add('hidden');
        loadingOverlay.classList.add('hidden');
    });

    // 🔹 دکمه بستن (دیگه نشون نده)
    closeBanner.addEventListener('click', () => {
        setCookie('pwa-dismissed', 'true', 30); // 30 روز نشون نده
        banner.classList.add('hidden');
    });

    // 🔹 نمایش بنر در صفحه دانلود
    if (window.location.pathname === '/download') {
        // در صفحه دانلود همیشه نشون بده (حتی اگه قبلا بسته بشه)
        if (!isInStandaloneMode && !getCookie('pwa-installed')) {
            banner.classList.remove('hidden');
        }
    } else {
        // در بقیه صفحات فقط اگه کوکی dismissed نباشه
        window.addEventListener('load', () => {
            if (!isInStandaloneMode && !getCookie('pwa-dismissed') && !getCookie('pwa-installed')) {
                setTimeout(() => banner.classList.remove('hidden'), 2000);
            }
        });
    }

    // 🔹 ثبت Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/service-worker.js')
                .then(reg => console.log('✅ Service Worker registered:', reg))
                .catch(err => console.log('❌ Service Worker failed:', err));
        });
    }
</script>
@include('layouts.client.script')


</body>

</html>
