{{-- ════════════════════════════════════════════════════════════════
     SDFR — PWA Install Bottom-Sheet (Android + iOS, موبایل فقط)
     • از پایین به بالا باز می‌شود (slide-up)
     • تا وقتی کاربر «نمیخوام» نزند، در هر بازدید نمایش داده می‌شود
     • «نمیخوام» = تا ۷ روز دیگر نمایش داده نمی‌شود
     • اندروید: نصب مستقیم با beforeinstallprompt
     • iOS: مودال راهنمای «افزودن به صفحه اصلی»
════════════════════════════════════════════════════════════════ --}}

<style>
    [x-cloak] { display: none !important; }

    /* ───── انیمیشن‌ها ───── */
    @keyframes sdfrPwaSheetUp {
        from { transform: translateY(110%); }
        to   { transform: translateY(0);    }
    }
    @keyframes sdfrPwaSheetDown {
        from { transform: translateY(0);    }
        to   { transform: translateY(110%); }
    }
    @keyframes sdfrPwaFade {
        from { opacity: 0; }
        to   { opacity: 1; }
    }
    @keyframes sdfrPwaIconPop {
        0%   { transform: scale(.6) rotate(-8deg); opacity: 0; }
        60%  { transform: scale(1.08) rotate(2deg); opacity: 1; }
        100% { transform: scale(1) rotate(0); }
    }
    @keyframes sdfrPwaPulseRing {
        0%   { transform: scale(.9);  opacity: .55; }
        70%  { transform: scale(1.5); opacity: 0;   }
        100% { transform: scale(1.5); opacity: 0;   }
    }
    @keyframes sdfrPwaArrow {
        0%,100% { transform: translateY(0);   opacity: .65; }
        50%     { transform: translateY(5px); opacity: 1;   }
    }
    @keyframes sdfrPwaShine {
        0%   { transform: translateX(-130%) skewX(-18deg); }
        60%,100% { transform: translateX(230%) skewX(-18deg); }
    }
    @keyframes sdfrPwaChip {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0);   }
    }

    .sdfr-pwa-sheet-enter  { animation: sdfrPwaSheetUp   .42s cubic-bezier(.22,1,.36,1) both; }
    .sdfr-pwa-sheet-leave  { animation: sdfrPwaSheetDown .30s cubic-bezier(.4,0,1,1)      both; }
    .sdfr-pwa-fade-enter   { animation: sdfrPwaFade .30s ease both; }

    .sdfr-pwa-icon-anim    { animation: sdfrPwaIconPop .6s .12s cubic-bezier(.34,1.56,.64,1) both; }
    .sdfr-pwa-ring         { animation: sdfrPwaPulseRing 2.4s ease-out infinite; }
    .sdfr-pwa-arrow        { animation: sdfrPwaArrow 1.4s ease-in-out infinite; }
    .sdfr-pwa-chip         { animation: sdfrPwaChip .4s ease both; }

    .sdfr-pwa-install-btn::after {
        content: '';
        position: absolute; top: 0; left: 0;
        width: 40%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.45), transparent);
        animation: sdfrPwaShine 3.2s ease-in-out infinite;
        pointer-events: none;
    }

    @media (prefers-reduced-motion: reduce) {
        .sdfr-pwa-sheet-enter, .sdfr-pwa-sheet-leave, .sdfr-pwa-icon-anim,
        .sdfr-pwa-ring, .sdfr-pwa-arrow, .sdfr-pwa-chip,
        .sdfr-pwa-install-btn::after { animation: none !important; }
    }
</style>

<div x-data="sdfrPwaInstall()" x-init="init()" x-cloak dir="rtl" class="lg:hidden">

    {{-- ══════════ بک‌دراپ ══════════ --}}
    <div x-show="open"
         class="fixed inset-0 z-[90] bg-black/60 backdrop-blur-sm sdfr-pwa-fade-enter"
         @click="dismissTemp()"></div>

    {{-- ══════════ Bottom Sheet اصلی نصب ══════════ --}}
    <div x-show="open"
         :class="closing ? 'sdfr-pwa-sheet-leave' : 'sdfr-pwa-sheet-enter'"
         class="fixed inset-x-0 bottom-0 z-[91] px-3 pb-[calc(env(safe-area-inset-bottom)+14px)]">

        <div class="relative mx-auto max-w-md overflow-hidden rounded-[26px] border border-white/10
                    bg-gradient-to-b from-[#101a33] via-[#0c1426] to-[#0a0f1f]
                    shadow-[0_-12px_60px_-10px_rgba(42,105,207,.55)]">

            {{-- درخشش پس‌زمینه --}}
            <div class="pointer-events-none absolute -top-24 left-1/2 -translate-x-1/2 h-56 w-56
                        rounded-full bg-[#2A69CF]/30 blur-3xl"></div>

            {{-- دستگیره بالا --}}
            <div class="relative flex justify-center pt-3">
                <span class="h-1.5 w-12 rounded-full bg-white/20"></span>
            </div>

            {{-- دکمه بستن (X) --}}
            <button @click="dismissTemp()"
                    aria-label="بستن"
                    class="absolute left-3 top-3 z-10 flex h-9 w-9 items-center justify-center
                           rounded-full bg-white/8 text-white/70 transition hover:bg-white/15 hover:text-white">
                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                    <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>

            <div class="relative px-5 pt-5 pb-5 text-center">

                {{-- آیکون اپ + حلقه پالس --}}
                <div class="relative mx-auto mb-4 h-[88px] w-[88px]">
                    <span class="sdfr-pwa-ring absolute inset-0 rounded-[24px] bg-[#2A69CF]/40"></span>
                    <span class="sdfr-pwa-ring absolute inset-0 rounded-[24px] bg-[#2A69CF]/30" style="animation-delay:.8s"></span>
                    <div class="sdfr-pwa-icon-anim relative flex h-[88px] w-[88px] items-center justify-center
                                rounded-[24px] bg-white/5 ring-1 ring-white/15 shadow-lg shadow-blue-900/40">
                        <img src="/client/assets/logoPwa/logo180.png" alt="SDFR"
                             class="h-[72px] w-[72px] rounded-[20px] object-cover">
                    </div>
                </div>

                <h3 class="text-lg font-extrabold text-white">نصب اپلیکیشن SDFR</h3>
                <p class="mx-auto mt-1.5 max-w-xs text-[13px] leading-6 text-white/60">
                    اپ را روی صفحه‌ی اصلی گوشی‌ات نصب کن و سریع‌تر، بدون مرورگر و حتی آفلاین واردش شو.
                </p>

                {{-- امتیاز --}}
                <div class="mt-3 flex items-center justify-center gap-1.5 text-amber-400">
                    <template x-for="i in 5" :key="i">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                            <path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.27 3.9a1 1 0 00.95.69h4.1c.97 0 1.37 1.24.59 1.81l-3.32 2.41a1 1 0 00-.36 1.12l1.27 3.9c.3.92-.75 1.69-1.54 1.12l-3.32-2.41a1 1 0 00-1.18 0l-3.32 2.41c-.78.57-1.83-.2-1.54-1.12l1.27-3.9a1 1 0 00-.36-1.12L2.9 9.33c-.78-.57-.38-1.81.59-1.81h4.1a1 1 0 00.95-.69l1.27-3.9z"/>
                        </svg>
                    </template>
                    <span class="mr-1 text-xs font-semibold text-white/55">۴٫۹ • رایگان</span>
                </div>

                {{-- مزیت‌ها (chips) --}}
                <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
                    <span class="sdfr-pwa-chip inline-flex items-center gap-1.5 rounded-full bg-white/5 px-3 py-1.5 text-[12px] font-medium text-white/80 ring-1 ring-white/10" style="animation-delay:.05s">
                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 text-emerald-400"><path d="M13 3L4 14h6l-1 7 9-11h-6l1-7z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                        اجرای سریع
                    </span>
                    <span class="sdfr-pwa-chip inline-flex items-center gap-1.5 rounded-full bg-white/5 px-3 py-1.5 text-[12px] font-medium text-white/80 ring-1 ring-white/10" style="animation-delay:.12s">
                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 text-sky-400"><path d="M12 3a9 9 0 100 18 9 9 0 000-18zM3.5 9h17M3.5 15h17M12 3c2.5 2.5 2.5 16 0 18M12 3c-2.5 2.5-2.5 16 0 18" stroke="currentColor" stroke-width="1.4"/></svg>
                        کار آفلاین
                    </span>
                    <span class="sdfr-pwa-chip inline-flex items-center gap-1.5 rounded-full bg-white/5 px-3 py-1.5 text-[12px] font-medium text-white/80 ring-1 ring-white/10" style="animation-delay:.19s">
                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 text-violet-400"><path d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        اعلان‌ها
                    </span>
                </div>

                {{-- دکمه نصب --}}
                <button @click="install()"
                        class="sdfr-pwa-install-btn relative mt-5 flex w-full items-center justify-center gap-2
                               overflow-hidden rounded-2xl bg-gradient-to-l from-[#2A69CF] to-[#4f8df0]
                               py-3.5 text-[15px] font-bold text-white shadow-lg shadow-blue-900/50
                               transition active:scale-[.98]">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                        <path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span x-text="installing ? 'در حال نصب…' : 'نصب اپلیکیشن'"></span>
                </button>

                {{-- دکمه نمیخوام --}}
                <button @click="dontWant()"
                        class="mt-2.5 w-full rounded-2xl py-3 text-[13px] font-medium text-white/45
                               transition hover:text-white/70">
                    نمی‌خوام
                </button>
            </div>
        </div>
    </div>

    {{-- ══════════ مودال راهنمای iOS ══════════ --}}
    <div x-show="iosOpen"
         class="fixed inset-0 z-[95] flex items-end sm:items-center justify-center px-3
                pb-[calc(env(safe-area-inset-bottom)+14px)] sm:pb-3 sdfr-pwa-fade-enter">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="iosOpen = false"></div>

        <div :class="open ? 'sdfr-pwa-sheet-enter' : ''"
             class="relative mx-auto w-full max-w-md overflow-hidden rounded-[26px] border border-white/10
                    bg-gradient-to-b from-[#101a33] via-[#0c1426] to-[#0a0f1f]
                    shadow-[0_-12px_60px_-10px_rgba(42,105,207,.55)]">

            <div class="flex justify-center pt-3"><span class="h-1.5 w-12 rounded-full bg-white/20"></span></div>

            <button @click="iosOpen = false" aria-label="بستن"
                    class="absolute left-3 top-3 flex h-9 w-9 items-center justify-center rounded-full
                           bg-white/8 text-white/70 transition hover:bg-white/15 hover:text-white">
                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>

            <div class="px-5 pb-6 pt-4 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-[20px] bg-white/5 ring-1 ring-white/15">
                    <img src="/client/assets/logoPwa/logo120.png" alt="SDFR" class="h-12 w-12 rounded-[16px]">
                </div>
                <h3 class="text-lg font-extrabold text-white">نصب روی آیفون / آیپد</h3>
                <p class="mx-auto mt-1.5 max-w-xs text-[13px] leading-6 text-white/60">
                    در مرورگر <span class="font-bold text-white/85">Safari</span> این چند مرحله را انجام بده:
                </p>

                <div class="mt-5 space-y-3 text-right">
                    {{-- مرحله ۱ --}}
                    <div class="flex items-center gap-3 rounded-2xl bg-white/5 p-3 ring-1 ring-white/10">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#2A69CF] text-sm font-bold text-white">۱</span>
                        <p class="flex-1 text-[13px] leading-6 text-white/80">
                            روی دکمه‌ی <span class="font-bold text-white">اشتراک‌گذاری</span> پایین صفحه بزن.
                        </p>
                        <span class="relative flex h-9 w-9 items-center justify-center rounded-xl bg-white/8">
                            <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 text-sky-400">
                                <path d="M12 3v12m0-12L8.5 6.5M12 3l3.5 3.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6 11v8a2 2 0 002 2h8a2 2 0 002-2v-8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </div>
                    {{-- مرحله ۲ --}}
                    <div class="flex items-center gap-3 rounded-2xl bg-white/5 p-3 ring-1 ring-white/10">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#2A69CF] text-sm font-bold text-white">۲</span>
                        <p class="flex-1 text-[13px] leading-6 text-white/80">
                            گزینه‌ی <span class="font-bold text-white">«افزودن به صفحه اصلی»</span> را انتخاب کن.
                        </p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/8">
                            <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 text-emerald-400">
                                <rect x="4" y="4" width="16" height="16" rx="4" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M12 8.5v7M8.5 12h7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </div>
                    {{-- مرحله ۳ --}}
                    <div class="flex items-center gap-3 rounded-2xl bg-white/5 p-3 ring-1 ring-white/10">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#2A69CF] text-sm font-bold text-white">۳</span>
                        <p class="flex-1 text-[13px] leading-6 text-white/80">
                            روی <span class="font-bold text-white">«افزودن»</span> بزن — تمام شد! 🎉
                        </p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/8">
                            <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 text-violet-400"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </div>
                </div>

                <button @click="iosOpen = false"
                        class="mt-5 w-full rounded-2xl bg-white/8 py-3 text-[14px] font-bold text-white transition hover:bg-white/15">
                    متوجه شدم
                </button>
            </div>
        </div>
    </div>
</div>

<script data-navigate-once>
    function sdfrPwaInstall() {
        return {
            open: false,
            iosOpen: false,
            closing: false,
            installing: false,
            isIOS: false,
            isAndroid: false,
            DISMISS_KEY: 'sdfr_pwa_dismissed_until',
            DISMISS_DAYS: 7,

            init() {
                const ua = navigator.userAgent || navigator.vendor || '';
                this.isIOS = /iphone|ipad|ipod/i.test(ua) && !window.MSStream;
                this.isAndroid = /android/i.test(ua);
                const isMobile = this.isIOS || this.isAndroid;

                // فقط موبایل اندروید/iOS
                if (!isMobile) return;

                // اگر قبلاً به‌صورت اپ نصب/باز شده باشد، نمایش نده
                if (this.isStandalone()) return;

                // اگر کاربر «نمیخوام» زده و هنوز در بازه‌ی ۷ روزه است
                if (this.isDismissed()) return;

                // گرفتن رویداد نصب اندروید (ممکن است قبل یا بعد از این اجرا شود)
                if (window.__sdfrDeferredPrompt === undefined) window.__sdfrDeferredPrompt = null;
                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    window.__sdfrDeferredPrompt = e;
                });

                // وقتی نصب شد، پنهان کن و دیگر نشان نده
                window.addEventListener('appinstalled', () => {
                    window.__sdfrDeferredPrompt = null;
                    this.open = false;
                    this.setDismissed(365);
                });

                // ورود نرم بعد از کمی مکث
                setTimeout(() => {
                    if (!this.isStandalone() && !this.isDismissed()) this.open = true;
                }, 1600);
            },

            isStandalone() {
                return window.matchMedia('(display-mode: standalone)').matches
                    || window.navigator.standalone === true;
            },

            isDismissed() {
                try {
                    const until = parseInt(localStorage.getItem(this.DISMISS_KEY) || '0', 10);
                    return until && Date.now() < until;
                } catch (e) { return false; }
            },

            setDismissed(days) {
                try {
                    localStorage.setItem(this.DISMISS_KEY, String(Date.now() + days * 86400000));
                } catch (e) {}
            },

            async install() {
                // iOS → مودال راهنما
                if (this.isIOS) { this.iosOpen = true; return; }

                const dp = window.__sdfrDeferredPrompt;
                if (dp) {
                    this.installing = true;
                    try {
                        dp.prompt();
                        const choice = await dp.userChoice;
                        window.__sdfrDeferredPrompt = null;
                        if (choice && choice.outcome === 'accepted') {
                            this.setDismissed(365);
                            this.hardClose();
                        } else {
                            this.installing = false;
                        }
                    } catch (e) {
                        this.installing = false;
                    }
                } else {
                    // مرورگر از نصب مستقیم پشتیبانی نمی‌کند → هدایت به صفحه‌ی نصب
                    window.location.href = '/app';
                }
            },

            dontWant() {
                this.setDismissed(this.DISMISS_DAYS);
                this.hardClose();
            },

            // بستن موقت (X یا کلیک روی پس‌زمینه) → دفعه‌ی بعد دوباره نمایش داده می‌شود
            dismissTemp() {
                this.hardClose();
            },

            hardClose() {
                this.closing = true;
                setTimeout(() => { this.open = false; this.closing = false; this.installing = false; }, 300);
            },
        }
    }
</script>
