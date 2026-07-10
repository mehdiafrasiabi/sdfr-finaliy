<!DOCTYPE html>
<html lang="fa" dir="rtl" class="dark">
<head>
    <meta name="color-scheme" content="dark">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-22E7M47QKY"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-22E7M47QKY');
    </script>
    <style>
        :root { color-scheme: dark; }
        /* ═══════ SDFR — Cosmic Light Lines (reusable) ═══════ */
        @property --sdfr-ang { syntax: '<angle>'; initial-value: 0deg; inherits: false; }

        /* ظرف خط‌های نوری — هرجا بذاری، روی همون والد پخش می‌شه */
        .sdfr-lines {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .sdfr-lines .comet { position: absolute; transform-origin: center; }

        .sdfr-lines .comet .core {
            position: absolute;
            width: 3px; height: 3px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 0 8px 2px rgba(125, 211, 252, .9);
            opacity: 0;
            animation: sdfr-comet-fly var(--dur, 8s) ease-in infinite;
            animation-delay: var(--delay, 0s);
        }

        .sdfr-lines .comet .core::before {
            content: '';
            position: absolute;
            top: 50%; right: 3px;
            width: 170px; height: 2px;
            transform: translateY(-50%);
            border-radius: 2px;
            background: linear-gradient(to left, rgba(125, 211, 252, .95), rgba(125, 211, 252, 0));
        }

        @keyframes sdfr-comet-fly {
            0%   { transform: translateX(0);                  opacity: 0; }
            3%   { opacity: 1; }
            14%  { transform: translateX(var(--dist, 1400px)); opacity: 0; }
            100% { transform: translateX(var(--dist, 1400px)); opacity: 0; }
        }

        @media (prefers-reduced-motion: reduce) {
            .sdfr-lines .comet .core { animation: none !important; }
        }
    </style>
    <script>
        function mobileMenuHandler() {
            return {
                offcanvasOpen: false,
                profileModalOpen: false,
                desktopProfileOpen: false,
                isScrolled: false,
                isMobile: false,
                pwaBannerClosed: false,
                bannersHidden: false,
                isClosingMenu: false,
                init() {
                    this.checkMobile();
                    this.checkScroll();

                    window.addEventListener('scroll', () => this.checkScroll(), {passive: true});

                    // Watch برای قفل اسکرول
                    this.$watch('offcanvasOpen', (value) => {
                        if (value) {
                            document.body.style.overflow = 'hidden';
                        } else {
                            document.body.style.overflow = '';
                        }
                    });

                    this.$watch('profileModalOpen', (value) => {
                        if (value) {
                            document.body.style.overflow = 'hidden';
                        } else {
                            document.body.style.overflow = '';
                        }
                    });

                    this.$watch('desktopProfileOpen', (value) => {
                        if (value) {
                            document.body.style.overflow = 'hidden';
                        } else {
                            document.body.style.overflow = '';
                        }
                    });
                },

                checkMobile() {
                    this.isMobile = window.innerWidth < 1024;
                },

                checkScroll() {
                    this.isScrolled = window.scrollY > 28;
                },

                handleResize() {
                    this.checkMobile();
                    if (!this.isMobile && this.offcanvasOpen) {
                        this.closeMenu();
                    }
                    if (!this.isMobile && this.profileModalOpen) {
                        this.profileModalOpen = false;
                    }
                    if (this.isMobile && this.desktopProfileOpen) {
                        this.desktopProfileOpen = false;
                    }
                },

                toggleMenu() {
                    if (this.offcanvasOpen) {
                        this.closeMenu();
                    } else {
                        this.openMenu();
                    }
                },

                openMenu() {
                    this.profileModalOpen = false;
                    this.desktopProfileOpen = false;

                    if (!this.isScrolled) {
                        this.bannersHidden = true;
                    }

                    this.offcanvasOpen = true;
                },

                openProfileModal() {
                    if (this.offcanvasOpen) {
                        this.closeMenu();
                    }

                    this.profileModalOpen = true;
                },

                closeMenu() {
                    if (this.isClosingMenu) return;
                    this.isClosingMenu = true;

                    this.offcanvasOpen = false;

                    if (!this.isScrolled) {
                        setTimeout(() => {
                            this.bannersHidden = false;
                            this.isClosingMenu = false;
                        }, 350);
                    } else {
                        this.isClosingMenu = false;
                    }
                },

                scrollToTop() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            }
        }
    </script>
    @include('layouts.client.link')
    @include('layouts.client.pwa')
    {!! SEO::generate() !!}
    <link rel="preload" href="/client/assets/images/theme/intro/header.png" as="image">
    {{-- favicon: use rel=icon (preload-as-image was unused and caused the console warning) --}}
    <link rel="icon" type="image/svg+xml" href="/client/assets/images/favicon.svg">
    <link rel="shortcut icon" href="/client/assets/images/favicon.svg">
</head>

<body>
<!-- container -->
@php
    $student = auth()->user()?->student;
    $needsAdvisorSelection = $student && $student->needsAdvisorSelection();
@endphp

<div class="flex flex-col min-h-screen bg-background">

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

    {{-- صفحه‌ی اصلی full-bleed است؛ روی بقیه صفحات پدینگ عمودی نگه داشته می‌شود --}}
    <main class="flex-auto @unless(request()->routeIs('client.home')) py-4 @endunless">
        {{$slot}}
        @if(request()->routeIs('client.profile.*'))
            <x-client.profile-reconnect-overlay />
            <x-cosmic-lines class="!fixed hidden dark:block" />
        @endif
    </main>

    <!-- footer -->
    @unless(request()->routeIs('client.profile.assessment.*') || $needsAdvisorSelection)
        <livewire:client.layout.footer/>
    @endunless
    <!-- end footer -->

    <!-- Mobile Bottom Navigation - Fixed at bottom -->
    {{-- تا وقتی برنامه‌ی هفته آزمایشی ساخته نشده، منوی پایین موبایل نمایش داده نمی‌شود --}}
    @php
        $bottomNavTrial = auth()->user()?->trialWeek;
    @endphp
    @if((!$bottomNavTrial || $bottomNavTrial->status === \App\Models\TrialWeek::STATUS_PROGRAM_BUILT) && !request()->routeIs('client.profile.advisor-chat') && !$needsAdvisorSelection)
        <livewire:client.layout.mobile-bottom-nav/>
    @endif

</div>

{{-- مودال نصب PWA (فقط موبایل اندروید/iOS) --}}
@include('layouts.client.pwa-install-modal')

@include('layouts.client.script')

{{-- Keyframe Animations --}}
<style>
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(40px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes blink {
        0%, 100% { opacity: 0.7; }
        50%       { opacity: 0.3; }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px) scale(0.95); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
</style>
<style>
    * { scrollbar-width: none !important; -ms-overflow-style: none !important; }
    *::-webkit-scrollbar { display: none !important; }
</style>
</body>

</html>
