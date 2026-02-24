<!DOCTYPE html>
<html lang="fa" dir="rtl" class="dark">
<head>
    <meta name="color-scheme" content="dark">
    <style>
        :root { color-scheme: dark; }
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
                    this.isScrolled = window.scrollY > 50;
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
    <link rel="preload" href="/client/assets/images/favicon.svg" as="image">
</head>

<body class="dark">
<!-- container -->
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
<script src="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>

<script data-navigate-once>
    //remove wire:snapshot form tags in client

    let attrs = [
        'snapshot',
        'effects',
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
</body>

</html>
