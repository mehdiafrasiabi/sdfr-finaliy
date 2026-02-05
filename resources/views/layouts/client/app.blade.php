<!DOCTYPE html>
<html lang="fa" dir="rtl" class="dark">


<head>
    {{--    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>--}}

    @include('layouts.client.link')
    @include('layouts.client.pwa')
    <!-- برای iOS -->

    {!! SEO::generate() !!}

    <style>
        [x-cloak] { display: none !important; }

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
            backdrop-filter: blur(5px) brightness(0.7);
            border-radius: 16px;
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .blur-container *:not(.blur-overlay, .blur-overlay *) {
            pointer-events: none;
            user-select: none;
        }


    </style>
    <style>
        /* احترام به کاربرانی که Reduce Motion فعال دارند */
        @media (prefers-reduced-motion: reduce) {
            .services-circle.is-bouncing,
            .services-circle.is-bouncing .services-icon {
                animation: none !important;
            }
        }

        /* انیمیشن پرش: تند بالا، نرم پایین */
        @keyframes servicesBounceUpDown {
            0%   { transform: translateY(0) scale(1); }
            18%  { transform: translateY(-40px) scale(1.06); } /* سریع میره بالا */
            55%  { transform: translateY(0) scale(1.00); }     /* آروم برمیگرده */
            70%  { transform: translateY(-8px) scale(1.02); }  /* یه بونس کوچیک */
            100% { transform: translateY(0) scale(1); }
        }

        /* چرخش فرفره‌ای عکس */
        @keyframes servicesSpin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* وقتی کلاس فعال شد */
        .services-circle.is-bouncing {
            animation: servicesBounceUpDown 1.2s cubic-bezier(.15,.95,.25,1) both;
            will-change: transform;
        }

        /* همزمان با پرش، عکس بچرخه */
        .services-circle.is-bouncing .services-icon {
            animation: servicesSpin 1.2s linear both;
            will-change: transform;
        }

    </style>
</head>

<body class="dark">

<!-- container -->


<div class="flex flex-col min-h-screen bg-background">

    {{--    <div id="loadingOverlay">--}}
    {{--        <img src="/client/loading.png" alt="SDFR Loading Logo" class="loading-logo rounded-xl">--}}
    {{--    </div>--}}




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


</body>

</html>
