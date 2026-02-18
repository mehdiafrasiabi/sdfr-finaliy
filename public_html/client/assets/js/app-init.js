// ============================================================
// متغیرهای یک‌بار اجرا
// ============================================================
let _videoModalInitialized = false;
let _pwaBannerInitialized  = false;
let _deferredPrompt        = null;

// ============================================================
// initPage - هر بار بعد از navigate
// ============================================================
function initPage() {
    initVideoModal();
    initPWABanner();
}

// ============================================================
// Video Modal - فقط یه بار event اضافه میشه
// ============================================================
function initVideoModal() {
    if (_videoModalInitialized) return;
    _videoModalInitialized = true;

    function openVideo({ url, title }) {
        const modal     = document.getElementById('video-modal');
        const container = document.getElementById('video-container');
        const titleEl   = document.getElementById('video-modal-title');
        if (!modal) return;

        container.innerHTML = '';
        const iframe = document.createElement('iframe');
        iframe.src = url;
        iframe.setAttribute('allowfullscreen', 'true');
        iframe.className = 'w-full h-full';
        iframe.style.border = '0';
        container.appendChild(iframe);
        if (titleEl && title) titleEl.textContent = title;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeVideo() {
        const modal     = document.getElementById('video-modal');
        const container = document.getElementById('video-container');
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        container.innerHTML = '';
        document.body.style.overflow = '';
    }

    // Event delegation - یه بار روی document کافیه
    document.addEventListener('click', (e) => {
        const openBtn = e.target.closest('[data-video-url]');
        if (openBtn) {
            openVideo({
                url:   openBtn.getAttribute('data-video-url'),
                title: openBtn.getAttribute('data-video-title') || 'ویدیو راهنما'
            });
            return;
        }
        if (e.target.closest('[data-close-video]')) {
            closeVideo();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeVideo();
    });

    document.addEventListener('livewire:navigated', closeVideo);

    window.openVideoModal  = openVideo;
    window.closeVideoModal = closeVideo;
}

// ============================================================
// PWA Banner - فقط یه بار initialize
// ============================================================
function initPWABanner() {
    if (_pwaBannerInitialized) return;
    _pwaBannerInitialized = true;

    const banner         = document.getElementById('pwaBanner');
    const installBtn     = document.getElementById('installApp');
    const closeBannerBtn = document.getElementById('closeBanner');
    const androidModal   = document.getElementById('pwaAndroidModal');
    const iosModal       = document.getElementById('pwaIOSModal');

    if (!banner || !installBtn) return;

    const LS_KEY_DISMISS   = 'pwa_banner_dismiss_until';
    const LS_KEY_INSTALLED = 'pwa_installed';

    const isInStandalone = window.matchMedia('(display-mode: standalone)').matches
        || window.navigator.standalone
        || document.referrer.startsWith('android-app://');

    const isIOS     = /iPhone|iPad|iPod/i.test(navigator.userAgent);
    const isAndroid = /Android/i.test(navigator.userAgent);

    const isDismissed = () => Date.now() < Number(localStorage.getItem(LS_KEY_DISMISS) || 0);
    const isInstalled = () => localStorage.getItem(LS_KEY_INSTALLED) === 'true';
    const canShow     = () => !isInStandalone && !isInstalled() && !isDismissed();
    const isMobile    = () => window.matchMedia('(max-width: 1024px)').matches;

    const hideBanner  = () => banner.classList.add('hidden');
    const openModal   = (type) => {
        if (type === 'android') androidModal?.classList.remove('hidden');
        if (type === 'ios')     iosModal?.classList.remove('hidden');
    };
    const closeModal  = (type) => {
        if (type === 'android') androidModal?.classList.add('hidden');
        if (type === 'ios')     iosModal?.classList.add('hidden');
    };

    if (!canShow()) { hideBanner(); return; }
    setTimeout(() => banner.classList.remove('hidden'), 800);

    async function triggerInstall() {
        if (!_deferredPrompt) return;
        _deferredPrompt.prompt();
        const { outcome } = await _deferredPrompt.userChoice;
        if (outcome === 'accepted') {
            localStorage.setItem(LS_KEY_INSTALLED, 'true');
            hideBanner();
        }
        _deferredPrompt = null;
    }

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        _deferredPrompt = e;
        if (canShow()) banner.classList.remove('hidden');
    });

    window.addEventListener('appinstalled', () => {
        localStorage.setItem(LS_KEY_INSTALLED, 'true');
        hideBanner();
        _deferredPrompt = null;
    });

    closeBannerBtn?.addEventListener('click', () => {
        localStorage.setItem(LS_KEY_DISMISS, String(Date.now() + 7 * 864e5));
        hideBanner();
    });

    installBtn.addEventListener('click', async () => {
        if (isIOS)                   { openModal('ios');     return; }
        if (isAndroid && isMobile()) { openModal('android'); return; }
        await triggerInstall();
    });

    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', (e) => closeModal(e.currentTarget.getAttribute('data-close-modal')));
    });

    document.querySelectorAll('[data-understood="android"]').forEach(btn => {
        btn.addEventListener('click', async () => {
            closeModal('android');
            await triggerInstall();
        });
    });
}

// ============================================================
// Alpine collapseGuide
// ============================================================
document.addEventListener('alpine:init', () => {
    Alpine.data('collapseGuide', (key) => ({
        open: false,
        init() {
            const saved = localStorage.getItem(key);
            this.open = saved !== null ? saved === 'true' : true;
        },
        toggle() {
            this.open = !this.open;
            localStorage.setItem(key, this.open);
        }
    }));
});

// ============================================================
// اجرا
// ============================================================
document.addEventListener('DOMContentLoaded', initPage);
document.addEventListener('livewire:navigated', initPage);
