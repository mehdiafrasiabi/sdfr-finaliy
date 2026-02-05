<script src="/client/assets/js/dependencies/alpinejs.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const circle = document.querySelector(".services-circle");
        if (!circle) return;

        const run = () => {
            // اگر وسط انیمیشن بود، دوباره از اول شروع کن
            circle.classList.remove("is-bouncing");
            // ری‌فلو برای ریست شدن انیمیشن
            void circle.offsetWidth;
            circle.classList.add("is-bouncing");
        };

        // اجرای اولیه (اختیاری)
        run();

        // هر 60 ثانیه
        setInterval(run, 15000);

        // بعد از پایان انیمیشن کلاس پاک شود (تمیزتر)
        circle.addEventListener("animationend", (e) => {
            if (e.animationName === "servicesBounceUpDown") {
                circle.classList.remove("is-bouncing");
            }
        });
    });
</script>

<script>
    // ⭐ این تابع باید قبل از Alpine.js لود بشه
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

    // در app.js خط 9
    const darkModeToggle = document.getElementById('dark-mode-button');
    if (darkModeToggle) { // ⭐ چک کن که null نباشه
        darkModeToggle.checked = something;
    }
</script>
<script src="/client/assets/js/dependencies/swiper-bundle.min.js"></script>
<script src="/client/assets/js/dependencies/plyr.min.js"></script>
<script src="/client/assets/js/app.js"></script>
<script type="text/javascript" src="/client/assets/css/toast/toastify-js.js"></script>
<script src="/client/assets/js/story-player/story-player.js"></script>

<script>
    window.addEventListener('success', function (event) {
        Toastify({
            text: event.detail,
            duration: 3000,
            style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
            }
        }).showToast();
    });
    window.addEventListener('warning', function (event) {
        Toastify({
            text: event.detail,
            duration: 3000,
            style: {
                background: "linear-gradient(to right, #d61212, #ff0000)",
            }
        }).showToast();
    });
    window.addEventListener('add-to-cart', function () {
        Toastify({
            text: 'با موفقیت به سبد خرید شما اضافه شد ',
            duration: 4000,
            style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
            }
        }).showToast();
    });
</script>
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
            open: true,

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
    const banner = document.getElementById('pwaBanner');
    const installBtn = document.getElementById('installApp');
    const closeBanner = document.getElementById('closeBanner');

    const androidModal = document.getElementById('pwaAndroidModal');
    const iosModal = document.getElementById('pwaIOSModal');

    let deferredPrompt = null;

    // ---------- Helpers ----------
    const LS_KEY_DISMISS = 'pwa_banner_dismiss_until';
    const LS_KEY_INSTALLED = 'pwa_installed';

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

    const isInStandaloneMode =
        (window.matchMedia('(display-mode: standalone)').matches) ||
        (window.navigator.standalone) ||
        document.referrer.startsWith('android-app://');

    const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
    const isAndroid = /Android/i.test(navigator.userAgent);

    function isMobile() {
        return window.matchMedia('(max-width: 1024px)').matches;
    }

    function openModal(type) {
        if (type === 'android') androidModal.classList.remove('hidden');
        if (type === 'ios') iosModal.classList.remove('hidden');
        document.documentElement.classList.add('overflow-hidden');
    }

    function closeModal(type) {
        if (type === 'android') androidModal.classList.add('hidden');
        if (type === 'ios') iosModal.classList.add('hidden');
        document.documentElement.classList.remove('overflow-hidden');
    }

    async function triggerInstallPrompt() {
        if (!deferredPrompt) {
            // اگر beforeinstallprompt نیومده باشه
            alert('متاسفانه مرورگر شما از نصب خودکار پشتیبانی نمی‌کند.');
            return;
        }

        deferredPrompt.prompt();
        const {outcome} = await deferredPrompt.userChoice;

        if (outcome === 'accepted') {
            setInstalled();
            banner.classList.add('hidden');
        }

        deferredPrompt = null;
    }

    // ---------- Banner show logic ----------
    function canShowBanner() {
        if (isInStandaloneMode) return false;
        if (isInstalledSaved()) return false;
        if (isDismissedNow()) return false;
        return true;
    }

    // iOS: beforeinstallprompt نداریم، خودمون اگر mobile بود نمایش می‌دیم
    window.addEventListener('load', () => {
        if (!canShowBanner()) return;

        // برای iOS موبایل نمایش بنر اوکیه، برای دسکتاپ هم میشه نمایش داد ولی شما معمولاً نمی‌خوای
        // اینجا می‌ذاریم روی همه دستگاه‌ها نمایش بده اگر خواستی شرطش کن.
        setTimeout(() => banner.classList.remove('hidden'), 800);
    });

    // Android/Chrome: beforeinstallprompt
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;

        if (canShowBanner()) {
            banner.classList.remove('hidden');
        }
    });

    // ---------- Actions ----------
    closeBanner.addEventListener('click', () => {
        dismissForDays(7); // ✅ 7 روز مخفی
        banner.classList.add('hidden');
    });

    installBtn.addEventListener('click', async () => {
        // iOS: فقط مودال آموزشی
        if (isIOS) {
            openModal('ios');
            return;
        }

        // Android موبایل: اول مودال آموزشی، بعد prompt
        if (isAndroid && isMobile()) {
            openModal('android');
            return;
        }

        // Desktop یا Android tablet/desktop: مستقیم نصب (بدون مودال)
        await triggerInstallPrompt();
    });

    // وقتی کاربر PWA رو نصب کرد
    window.addEventListener('appinstalled', () => {
        setInstalled();
        banner.classList.add('hidden');
        deferredPrompt = null;
    });

    // ---------- Modal buttons ----------
    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const type = e.currentTarget.getAttribute('data-close-modal');
            closeModal(type);
        });
    });

    // Android: دکمه "فهمیدم" -> بعد install prompt
    document.querySelectorAll('[data-understood="android"]').forEach(btn => {
        btn.addEventListener('click', async () => {
            closeModal('android');
            await triggerInstallPrompt();
        });
    });

    // ---------- Optional: close modal by clicking backdrop ----------
    [androidModal, iosModal].forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) return; // (در این ساختار کلیک روی خود modal کمتر رخ می‌ده)
        });
    });
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

@stack('script')
@php

    $generalSettings = \App\Models\GeneralSetting::first();

@endphp

@if($generalSettings && $generalSettings->footer_scripts)

    {!! $generalSettings->footer_scripts !!}

@endif
