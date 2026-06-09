<div>
    @push('link')
        <style>
            [x-cloak] { display: none !important; }

            /* ═══ GRID BACKGROUNDS ═══ */
            .grid-figma {
                background-image:
                    linear-gradient(to right, hsl(var(--border) / 0.4) 1px, transparent 1px),
                    linear-gradient(to bottom, hsl(var(--border) / 0.4) 1px, transparent 1px),
                    linear-gradient(to right, hsl(var(--border) / 0.2) 1px, transparent 1px),
                    linear-gradient(to bottom, hsl(var(--border) / 0.2) 1px, transparent 1px);
                background-size: 80px 80px, 80px 80px, 16px 16px, 16px 16px;
                -webkit-mask-image: radial-gradient(ellipse 100% 80% at 50% 30%, #000 30%, transparent 90%);
                mask-image: radial-gradient(ellipse 100% 80% at 50% 30%, #000 30%, transparent 90%);
            }

            /* ═══ GLASS ═══ */
            .glass-card {
                background: hsl(var(--background) / 0.6);
                backdrop-filter: blur(18px) saturate(140%);
                -webkit-backdrop-filter: blur(18px) saturate(140%);
                border: 1px solid hsl(var(--border) / 0.6);
            }
            .glass-input {
                background: hsl(var(--secondary) / 0.6);
                border: 1px solid hsl(var(--border));
                transition: all 0.2s ease;
                color: hsl(var(--foreground));
            }
            .glass-input:focus {
                background: hsl(var(--secondary));
                border-color: hsl(var(--primary));
                box-shadow: 0 0 0 3px hsl(var(--primary) / 0.15);
                outline: none;
            }
            .glass-input::placeholder { color: hsl(var(--muted) / 0.7); }

            /* ═══════════════════════════════════════════════════════════════
               ANIMATED BORDER — نور پررنگ و دقیقاً روی border
               ::before  = خط نوری واضح، روی لبه‌ی کارت (z بالاتر از محتوا)
               ::after   = هاله‌ی نرم محو پشت کارت
               ═══════════════════════════════════════════════════════════════ */
            .train-border {
                position: relative;
                border-radius: 1.5rem;
                --bw: 2px;       /* ضخامت خط نور */
                --speed: 3s;     /* سرعت چرخش */
            }
            /* خط نوری واضح روی لبه */
            .train-border::before {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: inherit;
                padding: var(--bw);
                background: conic-gradient(
                    from var(--angle, 0deg),
                    transparent 0deg,
                    transparent 200deg,
                    hsl(var(--primary) / 0.45) 270deg,
                    #3b82f6 318deg,
                    #93c5fd 340deg,
                    #ffffff 351deg,
                    #93c5fd 360deg
                );
                -webkit-mask:
                    linear-gradient(#000 0 0) content-box,
                    linear-gradient(#000 0 0);
                -webkit-mask-composite: xor;
                mask-composite: exclude;
                animation: rotate-border var(--speed) linear infinite;
                pointer-events: none;
                z-index: 3;
            }
            /* هاله‌ی محو (glow) که همراه خط می‌چرخد */
            .train-border::after {
                content: '';
                position: absolute;
                inset: -2px;
                border-radius: inherit;
                padding: calc(var(--bw) + 2px);
                background: conic-gradient(
                    from var(--angle, 0deg),
                    transparent 0deg,
                    transparent 250deg,
                    hsl(var(--primary) / 0.8) 320deg,
                    #60a5fa 345deg,
                    transparent 360deg
                );
                -webkit-mask:
                    linear-gradient(#000 0 0) content-box,
                    linear-gradient(#000 0 0);
                -webkit-mask-composite: xor;
                mask-composite: exclude;
                filter: blur(7px);
                opacity: 0.9;
                animation: rotate-border var(--speed) linear infinite;
                pointer-events: none;
                z-index: 0;
            }
            .train-border > * { position: relative; z-index: 1; }

            @property --angle {
                syntax: '<angle>';
                initial-value: 0deg;
                inherits: false;
            }
            @keyframes rotate-border {
                to { --angle: 360deg; }
            }
            /* Fallback برای مرورگرهای بدون @property */
            @supports not (background: conic-gradient(from 0deg, red, blue)) {
                .train-border::before,
                .train-border::after { display: none; }
            }

            /* ═══ PRESS BUTTONS ═══ */
            .btn-press {
                position: relative;
                transform: translateY(0);
                box-shadow:
                    0 4px 0 0 hsl(var(--primary) / 0.4),
                    0 6px 12px hsl(var(--primary) / 0.25);
                transition: transform 0.08s ease, box-shadow 0.08s ease;
                background: hsl(var(--primary));
                color: white;
                user-select: none;
            }
            .btn-press:hover:not(:disabled) {
                transform: translateY(-1px);
                box-shadow:
                    0 5px 0 0 hsl(var(--primary) / 0.4),
                    0 8px 16px hsl(var(--primary) / 0.35);
            }
            .btn-press:active:not(:disabled),
            .btn-press.pressed {
                transform: translateY(3px);
                box-shadow:
                    0 1px 0 0 hsl(var(--primary) / 0.4),
                    0 2px 4px hsl(var(--primary) / 0.2);
            }
            .btn-press:disabled { opacity: 0.6; cursor: not-allowed; }

            .btn-press-secondary {
                position: relative;
                transform: translateY(0);
                box-shadow:
                    0 3px 0 0 hsl(var(--border)),
                    0 4px 8px hsl(var(--foreground) / 0.05);
                transition: transform 0.08s ease, box-shadow 0.08s ease;
                background: hsl(var(--secondary));
                color: hsl(var(--foreground));
                border: 1px solid hsl(var(--border));
            }
            .btn-press-secondary:hover:not(:disabled) { transform: translateY(-1px); }
            .btn-press-secondary:active:not(:disabled) {
                transform: translateY(2px);
                box-shadow: 0 1px 0 0 hsl(var(--border)), 0 1px 2px hsl(var(--foreground) / 0.05);
            }

            /* ═══ ACCENT CARDS ═══ */
            .accent-emerald { --accent: 16 185 129; }
            .accent-sky     { --accent: 14 165 233; }
            .accent-card {
                background: linear-gradient(135deg, rgb(var(--accent) / 0.08), hsl(var(--secondary) / 0.6));
                border: 1px solid rgb(var(--accent) / 0.25);
            }
            .accent-icon {
                background: rgb(var(--accent) / 0.12);
                color: rgb(var(--accent));
                border: 1px solid rgb(var(--accent) / 0.25);
            }

            /* ═══ STEP FADE (بدون حرکت/پرش — فقط محو) ═══ */
            .step-fade { transition: opacity 0.22s ease; }

            /* ═══ FALLBACK ANIMATIONS (وقتی Lottie تنظیم نشده) ═══ */
            @keyframes pop-in {
                0%   { transform: scale(0.6); opacity: 0; }
                60%  { transform: scale(1.08); }
                100% { transform: scale(1); opacity: 1; }
            }
            @keyframes soft-float {
                0%, 100% { transform: translateY(0); }
                50%      { transform: translateY(-8px); }
            }
            .anim-pop   { animation: pop-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }
            .anim-float { animation: soft-float 3s ease-in-out infinite; }

            /* ═══ PROGRESS DOTS ═══ */
            .progress-dot {
                width: 8px; height: 8px; border-radius: 999px;
                background: hsl(var(--border));
                transition: all 0.3s ease;
            }
            .progress-dot.active { width: 28px; background: hsl(var(--primary)); }
            .progress-dot.completed { background: hsl(var(--primary) / 0.5); }

            /* ═══ TOOLTIP ═══ */
            .field-tip {
                position: absolute;
                bottom: calc(100% + 8px);
                right: 0;
                background: hsl(var(--foreground));
                color: hsl(var(--background));
                padding: 6px 10px;
                border-radius: 8px;
                font-size: 11px;
                white-space: nowrap;
                box-shadow: 0 8px 24px hsl(var(--foreground) / 0.15);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.2s ease;
                z-index: 10;
            }
            .field-tip::after {
                content: '';
                position: absolute;
                top: 100%;
                right: 14px;
                border: 5px solid transparent;
                border-top-color: hsl(var(--foreground));
            }
            .field-wrap:focus-within .field-tip { opacity: 1; }

            /* ═══ ERRORS / SCROLLBAR ═══ */
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
            .shake { animation: shake 0.4s ease; }

            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: hsl(var(--primary) / 0.3); border-radius: 999px; }
            ::-webkit-scrollbar-thumb:hover { background: hsl(var(--primary) / 0.5); }

            /* ═══ FLOATING ORBS ═══ */
            @keyframes float-orb {
                0%, 100% { transform: translate(0, 0); }
                50%      { transform: translate(20px, -25px); }
            }
            .float-orb { animation: float-orb 9s ease-in-out infinite; }

            /* ═══ MOBILE: full-screen background ═══ */
            .mobile-bg {
                min-height: 100dvh;
                background: hsl(var(--background));
            }

            @media (prefers-reduced-motion: reduce) {
                * { animation: none !important; transition: none !important; }
            }
        </style>
    @endpush

    {{-- ✅ Lottie player از CDN (به‌جای فایل لوکال) --}}
    <script src="https://cdn.jsdelivr.net/npm/@lottiefiles/lottie-player@2.0.12/dist/lottie-player.js" defer></script>

    {{-- Driver.js (onboarding tour) --}}
    <link rel="stylesheet" href="/client/animation/driver.css"/>
    <script src="/client/animation/driver.js.iife.js" defer></script>

    {{-- ⚠️ CRITICAL: register Alpine.data BEFORE the x-data div renders --}}
    @push('script')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('onboardingFlow', () => ({
                    busy: false,
                    countdownTimer: null,
                    touchStartX: 0,
                    touchEndX: 0,
                    tourShown: false,

                    init() {
                        this.startCountdownIfNeeded();

                        Livewire.on('start-countdown', () => this.startCountdownIfNeeded());
                        Livewire.on('step-validation-failed', () => { this.busy = false; });
                        Livewire.on('step-changed', () => {
                            this.endTransition();
                            this.$nextTick(() => this.maybeShowTour());
                        });

                        this.$nextTick(() => this.maybeShowTour());
                    },

                    // پایان حالت لودینگ بین مراحل (کمی تاخیر تا چشمک نزند)
                    endTransition() {
                        setTimeout(() => { this.busy = false; }, 280);
                    },

                    pressBtn(el) {
                        if (!el) return;
                        el.classList.add('pressed');
                        setTimeout(() => el.classList.remove('pressed'), 120);
                        if (navigator.vibrate) navigator.vibrate(10);
                    },

                    maybeShowTour() {
                        if (this.tourShown) return;
                        if (localStorage.getItem('sdfr_onboarding_tour_done')) return;
                        if (typeof window.driver === 'undefined') return;

                        const isDesktop = window.matchMedia('(min-width: 768px)').matches;
                        if (!isDesktop && this.$wire.currentStep !== 2) return;
                        if (isDesktop && this.$wire.currentStep > 4) return;
                        if (!document.querySelector('[data-tour="firstName"]')) return;

                        this.tourShown = true;
                        const driver = window.driver.js.driver;
                        const tour = driver({
                            showProgress: true,
                            allowClose: true,
                            nextBtnText: 'بعدی',
                            prevBtnText: 'قبلی',
                            doneBtnText: 'فهمیدم',
                            steps: [
                                {
                                    element: '[data-tour="firstName"]',
                                    popover: {
                                        title: 'اطلاعات اولیه',
                                        description: 'این اطلاعات روی کارنامه و گزارش‌ها درج می‌شه. حتماً فارسی و کامل وارد کنید.',
                                        side: isDesktop ? 'right' : 'bottom',
                                    }
                                },
                                {
                                    element: '[data-tour="codeMell"]',
                                    popover: {
                                        title: 'کد ملی',
                                        description: 'کد ملی برای احراز هویت در سامانه استفاده می‌شه.',
                                        side: 'bottom',
                                    }
                                },
                            ],
                            onDestroyed: () => {
                                localStorage.setItem('sdfr_onboarding_tour_done', '1');
                            }
                        });
                        setTimeout(() => tour.drive(), 500);
                    },

                    startCountdownIfNeeded() {
                        if (this.countdownTimer) clearInterval(this.countdownTimer);
                        if (this.$wire.countdown <= 0) return;
                        this.countdownTimer = setInterval(() => {
                            if (this.$wire.countdown > 0) {
                                this.$wire.set('countdown', this.$wire.countdown - 1, false);
                            } else {
                                clearInterval(this.countdownTimer);
                                this.$wire.countdownFinished();
                            }
                        }, 1000);
                    },

                    goNext() {
                        if (this.busy) return;
                        // مرحله ۱ → ۲ کاملاً کلاینت‌ساید
                        if (this.$wire.currentStep === 1) {
                            this.busy = true;
                            this.$wire.set('currentStep', 2).then(() => this.endTransition());
                            return;
                        }
                        this.busy = true;
                        // next() یا step-changed یا step-validation-failed را dispatch می‌کند
                        this.$wire.next();
                    },

                    goPrev() {
                        if (this.busy) return;
                        if (this.$wire.currentStep <= 1) return;
                        if (this.$wire.currentStep === 2) {
                            this.busy = true;
                            this.$wire.set('currentStep', 1).then(() => this.endTransition());
                            return;
                        }
                        this.busy = true;
                        this.$wire.previous().then(() => this.endTransition());
                    },

                    submitDesktopForm() {
                        if (this.busy) return;
                        this.busy = true;
                        this.$wire.submitAll();
                    },

                    handleTouchStart(e) {
                        this.touchStartX = e.changedTouches[0].screenX;
                    },
                    handleTouchEnd(e) {
                        this.touchEndX = e.changedTouches[0].screenX;
                        const diff = this.touchEndX - this.touchStartX;
                        if (Math.abs(diff) < 60) return;
                        if (['INPUT','TEXTAREA','SELECT','BUTTON'].includes(e.target.tagName)) return;
                        if (diff > 0 && this.$wire.currentStep > 1 && this.$wire.currentStep <= 4) {
                            this.goPrev();
                        }
                    },
                }));
            });
        </script>
    @endpush

    @php
        $gradeLabels = ['9'=>'نهم','10'=>'دهم','11'=>'یازدهم','12'=>'دوازدهم'];
        $fieldLabels = ['math'=>'ریاضی','experimental'=>'تجربی','human'=>'انسانی'];

        // ── options برای کامپوننت x-ui.select ──
        $gradeOptions = [];
        foreach ($gradeLabels as $v => $l) { $gradeOptions[] = ['id' => (string) $v, 'name' => $l]; }
        $fieldOptions = [];
        foreach ($fieldLabels as $v => $l) { $fieldOptions[] = ['id' => $v, 'name' => $l]; }
        $stateOptions = collect($states)->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values()->all();
        $cityOptions  = collect($cities)->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values()->all();

        $features = [
            ['t' => 'برنامه‌ی هفتگی اختصاصی', 'd' => 'مشاور متخصص برای شما برنامه می‌نویسد', 'icon' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
            ['t' => 'گزارش لحظه‌ای پیشرفت',   'd' => 'هر روز عملکرد خودتو رصد می‌کنید',     'icon' => '<line x1="3" y1="3" x2="3" y2="21"/><line x1="3" y1="21" x2="21" y2="21"/><polyline points="7 16 11 12 15 16 21 10"/>'],
            ['t' => 'تایمر هوشمند مطالعه',   'd' => 'ساعت مفید مطالعه‌ی هر درس را ثبت کنید', 'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
            ['t' => 'آزمون‌های آنلاین',       'd' => 'سنجش مرحله‌ای با بازخورد تخصصی',       'icon' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'],
            ['t' => 'اتاق مشاوره',           'd' => 'ارتباط مستقیم با مشاور تخصصی',         'icon' => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8z"/>'],
            ['t' => 'کارنامه و آزمون',       'd' => 'کارنامه‌ی ماهانه با نمودار پیشرفت',     'icon' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>'],
        ];

        // ════════════════════════════════════════════════════════════════════
        // 🎞️ انیمیشن‌ها
        // لینک‌ها را از https://lottiefiles.com/free-animations بردار:
        //   انیمیشن مورد نظر → دکمه‌ی share/embed → آدرس .lottie یا .json
        // و در سه متغیر زیر بگذار. اگر خالی بماند، یک آیکن متحرک ساده‌ی
        // جایگزین (بدون هیچ خطایی در کنسول) نمایش داده می‌شود.
        // ════════════════════════════════════════════════════════════════════
        $lottieWelcome = '';
        $lottieOtp     = '';
        $lottieSuccess = '';

        // آیکن‌های جایگزین (همیشه لود می‌شوند)
        $fbWelcome = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full text-primary anim-float"><path d="M22 10 12 5 2 10l10 5 10-5Z"/><path d="M6 12v5c0 1 2.7 2.4 6 2.4s6-1.4 6-2.4v-5"/><path d="M22 10v6"/></svg>';
        $fbOtp     = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full text-primary anim-float"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>';
        $fbSuccess = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="w-full h-full text-emerald-500 anim-pop"><circle cx="12" cy="12" r="10"/><path d="m8 12 3 3 5-6"/></svg>';
    @endphp

    {{-- ═══════════════════════════════════════════════════════════════════════════
         ROOT WRAPPER with Alpine
         ═══════════════════════════════════════════════════════════════════════════ --}}
    <div class="relative min-h-screen overflow-hidden bg-background text-foreground mobile-bg"
         dir="rtl"
         x-data="onboardingFlow()">

        {{-- Background layers --}}
        <div class="absolute inset-0 grid-figma pointer-events-none"></div>
        <div class="absolute top-20 -right-20 w-72 h-72 bg-primary/15 rounded-full blur-3xl float-orb pointer-events-none"></div>
        <div class="absolute bottom-20 -left-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl float-orb pointer-events-none" style="animation-delay: -3s"></div>


        {{-- ─────────────────────────────────────────────────────────
             📱 MOBILE (< md)
             ───────────────────────────────────────────────────────── --}}
        <div class="md:hidden relative z-10 min-h-[100dvh] flex flex-col">

            {{-- Header --}}
            <header class="px-4 pt-5 pb-3" x-show="$wire.currentStep < {{ $totalSteps }}">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                            </svg>
                        </div>
                        <span class="font-black text-sm tracking-wider">SDFR</span>
                    </div>

                    <div class="flex-1 flex items-center justify-center gap-2">
                        @for ($i = 1; $i <= $totalSteps; $i++)
                            <span class="progress-dot"
                                  :class="{
                                    'active': $wire.currentStep === {{ $i }},
                                    'completed': $wire.currentStep > {{ $i }}
                                  }"></span>
                        @endfor
                    </div>

                    <div class="text-xs font-mono text-muted">
                        <span x-text="$wire.currentStep"></span>/{{ $totalSteps }}
                    </div>
                </div>
            </header>

            @if ($generalError)
                <div class="mx-4 mb-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-500 px-4 py-3 text-sm">
                    {{ $generalError }}
                </div>
            @endif

            {{-- Steps --}}
            <main class="flex-1 flex items-stretch justify-center px-4 py-4"
                  @touchstart="handleTouchStart($event)"
                  @touchend="handleTouchEnd($event)">

                <div class="w-full relative">

                    {{-- ═══ LOADING VEIL بین مراحل (هیچ چیز پرش نمی‌کند) ═══ --}}
                    <div x-show="busy" x-cloak
                         x-transition:enter="step-fade"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="absolute inset-0 z-20 flex flex-col items-center justify-center gap-4 min-h-[60vh]">
                        <svg class="w-12 h-12 animate-spin text-primary" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="48" stroke-linecap="round" opacity="0.4"/>
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="14 60" stroke-linecap="round"/>
                        </svg>
                        <span class="text-sm text-muted">لطفاً صبر کنید…</span>
                    </div>

                    {{-- استپ‌ها فقط محو می‌شوند (بدون حرکت) --}}
                    <div class="step-fade" :class="busy ? 'opacity-0 pointer-events-none' : 'opacity-100'">

                        {{-- ═══ STEP 1 ═══ --}}
                        <section x-show="$wire.currentStep === 1">
                            <div class="train-border">
                                <div class="glass-card rounded-3xl p-6 text-center">
                                    <div class="inline-block w-36 h-36 mb-3">
                                        @if($lottieWelcome)
                                            <lottie-player src="{{ $lottieWelcome }}" background="transparent" speed="1" loop autoplay style="width:100%;height:100%"></lottie-player>
                                        @else
                                            {!! $fbWelcome !!}
                                        @endif
                                    </div>

                                    <h1 class="font-black text-2xl leading-tight mb-3">
                                        به <span class="text-primary">SDFR</span> خوش آمدید
                                    </h1>
                                    <p class="text-muted text-sm leading-7 mb-6">
                                        پلتفرم هوشمند پایش مطالعه و مشاوره‌ی تخصصی.
                                        در کمتر از ۲ دقیقه حساب‌تون ساخته می‌شه.
                                    </p>

                                    <div class="space-y-2 mb-6 text-right">
                                        @foreach(array_slice($features, 0, 4) as $f)
                                            <div class="flex items-center gap-3 p-3 rounded-xl bg-secondary/50 border border-border">
                                            <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-primary/10 text-primary border border-primary/20 shrink-0">
                                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    {!! $f['icon'] !!}
                                                </svg>
                                            </span>
                                                <div class="text-right">
                                                    <div class="font-bold text-sm">{{ $f['t'] }}</div>
                                                    <div class="text-[11px] text-muted leading-5">{{ $f['d'] }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 text-xs font-bold mb-5">
                                    <span class="relative flex w-1.5 h-1.5">
                                        <span class="absolute inline-flex w-full h-full bg-emerald-500 rounded-full opacity-75 animate-ping"></span>
                                        <span class="relative inline-flex w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    </span>
                                        هفته آزمایشی رایگان
                                    </div>

                                    <button type="button" @click="goNext()" @mousedown="pressBtn($el)"
                                            class="btn-press w-full h-14 rounded-2xl font-bold text-base flex items-center justify-center gap-2">
                                        بزن بریم
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </section>

                        {{-- ═══ STEP 2 ═══ --}}
                        <section x-show="$wire.currentStep === 2">
                            <div class="train-border">
                                <div class="glass-card rounded-3xl p-6">
                                    <div class="flex items-center gap-3 mb-6">
                                        <div class="w-11 h-11 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h2 class="font-black text-lg">اطلاعات شخصی</h2>
                                            <p class="text-[11px] text-muted">اسمتو فارسی وارد کن</p>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="field-wrap relative" data-tour="firstName">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">نام</label>
                                            <input wire:model.blur="firstName" type="text" placeholder="مثلاً علی" autocomplete="given-name"
                                                   class="glass-input w-full rounded-xl px-4 py-3 text-sm @error('firstName') border-rose-500/60 shake @enderror">
                                            <div class="field-tip">باید فارسی باشه</div>
                                            @error('firstName')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="field-wrap relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">نام خانوادگی</label>
                                            <input wire:model.blur="lastName" type="text" placeholder="مثلاً محمدی" autocomplete="family-name"
                                                   class="glass-input w-full rounded-xl px-4 py-3 text-sm @error('lastName') border-rose-500/60 shake @enderror">
                                            @error('lastName')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="field-wrap relative" data-tour="codeMell">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">کد ملی</label>
                                            <input wire:model.blur="codeMell" type="text" maxlength="10" placeholder="۱۰ رقم" inputmode="numeric" dir="ltr"
                                                   class="glass-input w-full rounded-xl px-4 py-3 text-sm font-mono tracking-wider @error('codeMell') border-rose-500/60 shake @enderror">
                                            <div class="field-tip">دقیقاً ۱۰ رقم</div>
                                            @error('codeMell')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- ═══ STEP 3 ═══ --}}
                        <section x-show="$wire.currentStep === 3">
                            <div class="train-border">
                                <div class="glass-card rounded-3xl p-6">
                                    <div class="flex items-center gap-3 mb-6">
                                        <div class="w-11 h-11 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h2 class="font-black text-lg">والدین و پایه</h2>
                                            <p class="text-[11px] text-muted">برای گزارش‌گیری و ارتباط</p>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="field-wrap relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شماره پدر</label>
                                            <input wire:model.blur="fatherMobile" type="tel" placeholder="09..." dir="ltr" inputmode="numeric"
                                                   class="glass-input w-full rounded-xl px-4 py-3 text-sm font-mono @error('fatherMobile') border-rose-500/60 shake @enderror">
                                            @error('fatherMobile')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="field-wrap relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شماره مادر</label>
                                            <input wire:model.blur="motherMobile" type="tel" placeholder="09..." dir="ltr" inputmode="numeric"
                                                   class="glass-input w-full rounded-xl px-4 py-3 text-sm font-mono @error('motherMobile') border-rose-500/60 shake @enderror">
                                            @error('motherMobile')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="field-wrap relative">
                                                <label class="block text-xs font-semibold mb-1.5 text-muted">پایه</label>
                                                <x-ui.select wire:model.live="grade" :options="$gradeOptions" placeholder="انتخاب پایه" />
                                            </div>
                                            @if($grade !== '9')
                                                <div class="field-wrap relative" wire:key="field-m-{{ $grade }}">
                                                    <label class="block text-xs font-semibold mb-1.5 text-muted">رشته</label>
                                                    <x-ui.select wire:model="field" :options="$fieldOptions" placeholder="انتخاب رشته" />
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- ═══ STEP 4 ═══ --}}
                        <section x-show="$wire.currentStep === 4">
                            <div class="train-border">
                                <div class="glass-card rounded-3xl p-6">
                                    <div class="flex items-center gap-3 mb-6">
                                        <div class="w-11 h-11 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary">
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h2 class="font-black text-lg">مکان و حساب</h2>
                                            <p class="text-[11px] text-muted">شماره برای ورود به سامانه</p>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="field-wrap relative">
                                                <label class="block text-xs font-semibold mb-1.5 text-muted">استان</label>
                                                <x-ui.select wire:model.live="stateId" :options="$stateOptions" :searchable="true" placeholder="انتخاب استان" search-placeholder="جستجوی استان..." />
                                                @error('stateId')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="field-wrap relative" wire:key="city-m-{{ $stateId }}">
                                                <label class="block text-xs font-semibold mb-1.5 text-muted">شهر</label>
                                                <x-ui.select wire:model="cityId" :options="$cityOptions" :searchable="true" :disabled="(int) $stateId === 0" placeholder="انتخاب شهر" search-placeholder="جستجوی شهر..." />
                                                @error('cityId')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <div class="field-wrap relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شماره موبایل (برای ورود)</label>
                                            <input wire:model.blur="mobile" type="tel" placeholder="09..." dir="ltr" inputmode="numeric"
                                                   class="glass-input w-full rounded-xl px-4 py-3 text-sm font-mono @error('mobile') border-rose-500/60 shake @enderror">
                                            <div class="field-tip">کد تأیید روی این شماره میاد</div>
                                            @error('mobile')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="field-wrap relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">رمز عبور</label>
                                            <input wire:model.live.debounce.300ms="password" type="password" dir="ltr"
                                                   autocomplete="new-password" data-lpignore="true" data-1p-ignore="true"
                                                   class="glass-input w-full rounded-xl px-4 py-3 text-sm @error('password') border-rose-500/60 shake @enderror">
                                            <div class="flex items-center gap-1.5 mt-2 flex-wrap">
                                                <span class="text-[10px] text-muted">قدرت:</span>
                                                <span class="text-[10px] px-1.5 py-0.5 rounded transition-colors"
                                                      :class="$wire.passwordStrength?.length ? 'bg-emerald-500/15 text-emerald-500' : 'bg-secondary text-muted'">۸+ کاراکتر</span>
                                                <span class="text-[10px] px-1.5 py-0.5 rounded transition-colors"
                                                      :class="$wire.passwordStrength?.letter ? 'bg-emerald-500/15 text-emerald-500' : 'bg-secondary text-muted'">حرف</span>
                                                <span class="text-[10px] px-1.5 py-0.5 rounded transition-colors"
                                                      :class="$wire.passwordStrength?.number ? 'bg-emerald-500/15 text-emerald-500' : 'bg-secondary text-muted'">عدد</span>
                                            </div>
                                            @error('password')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="field-wrap relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">تکرار رمز</label>
                                            <input wire:model.blur="passwordConf" type="password" dir="ltr"
                                                   autocomplete="new-password" data-lpignore="true" data-1p-ignore="true"
                                                   class="glass-input w-full rounded-xl px-4 py-3 text-sm @error('passwordConf') border-rose-500/60 shake @enderror">
                                            @error('passwordConf')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- ═══ STEP 5 — OTP ═══ --}}
                        <section x-show="$wire.currentStep === 5">
                            <div class="train-border">
                                <div class="glass-card rounded-3xl p-6 text-center">
                                    <div class="inline-block w-28 h-28 mb-2">
                                        @if($lottieOtp)
                                            <lottie-player src="{{ $lottieOtp }}" background="transparent" speed="1" loop autoplay style="width:100%;height:100%"></lottie-player>
                                        @else
                                            {!! $fbOtp !!}
                                        @endif
                                    </div>

                                    <h2 class="font-black text-xl mb-2">کد تأیید را وارد کنید</h2>
                                    <p class="text-sm text-muted mb-5 leading-7">
                                        کد ۶ رقمی به شماره‌ی
                                        <strong dir="ltr" class="text-primary">{{ $mobile }}</strong>
                                        ارسال شد.
                                    </p>

                                    <input wire:model="otpInput" type="text" maxlength="6" placeholder="------"
                                           inputmode="numeric" dir="ltr" x-init="$el.focus()"
                                           class="glass-input w-full text-center tracking-[0.6em] text-2xl font-mono rounded-2xl px-4 py-4 mb-3">

                                    @if($otpError)
                                        <div class="text-rose-500 text-xs mb-3">{{ $otpError }}</div>
                                    @endif

                                    <div class="flex items-center justify-between text-sm mb-2">
                                        @if($countdown > 0)
                                            <span class="text-muted text-xs">
                                            ارسال مجدد تا
                                            <span class="text-primary font-mono mx-1" x-text="$wire.countdown"></span>
                                            ثانیه
                                        </span>
                                        @else
                                            <button type="button" wire:click="resendOtp"
                                                    class="text-primary hover:underline text-xs font-bold">ارسال مجدد کد</button>
                                        @endif

                                        <button type="button" wire:click="verifyOtp" @mousedown="pressBtn($el)"
                                                wire:loading.attr="disabled" wire:target="verifyOtp"
                                                class="btn-press px-6 py-2.5 rounded-xl text-sm font-bold">
                                            <span wire:loading.remove wire:target="verifyOtp">تأیید کد</span>
                                            <span wire:loading wire:target="verifyOtp">در حال بررسی…</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- ═══ STEP 6 — Final ═══ --}}
                        <section x-show="$wire.currentStep === 6">
                            <div class="train-border">
                                <div class="glass-card rounded-3xl p-6">
                                    <div class="text-center mb-6">
                                        <div class="inline-block w-28 h-28 mb-2">
                                            @if($lottieSuccess)
                                                <lottie-player src="{{ $lottieSuccess }}" background="transparent" speed="1" autoplay style="width:100%;height:100%"></lottie-player>
                                            @else
                                                {!! $fbSuccess !!}
                                            @endif
                                        </div>
                                        <h2 class="font-black text-2xl mb-2">حساب شما ساخته شد</h2>
                                        <p class="text-sm text-muted">یکی از مسیرها را برای ادامه انتخاب کنید.</p>
                                    </div>

                                    <div class="space-y-3">
                                        <button type="button" wire:click="openTrialConfirm"
                                                class="accent-card accent-emerald w-full rounded-2xl p-5 text-right hover:-translate-y-0.5 transition-transform">
                                            <div class="inline-flex items-center gap-1.5 text-xs font-bold accent-icon accent-emerald rounded-full px-2.5 py-1 mb-3">
                                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                                رایگان
                                            </div>
                                            <h3 class="font-black text-lg mb-1">۱ هفته آزمایشی</h3>
                                            <p class="text-xs text-muted leading-6">تجربه‌ی کامل امکانات بدون پرداخت</p>
                                        </button>

                                        <button type="button" wire:click="goToPurchase"
                                                class="accent-card accent-sky w-full rounded-2xl p-5 text-right hover:-translate-y-0.5 transition-transform">
                                            <div class="inline-flex items-center gap-1.5 text-xs font-bold accent-icon accent-sky rounded-full px-2.5 py-1 mb-3">
                                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                                </svg>
                                                کامل
                                            </div>
                                            <h3 class="font-black text-lg mb-1">خرید دوره</h3>
                                            <p class="text-xs text-muted leading-6">دسترسی به همه‌ی امکانات</p>
                                        </button>
                                    </div>

                                    <div class="text-center mt-5">
                                        <button type="button" wire:click="declineTrial"
                                                class="text-xs text-muted hover:text-foreground transition-colors">
                                            فعلاً نه — بازگشت
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </div> {{-- /step-fade --}}
                </div>
            </main>

            {{-- Bottom nav --}}
            <footer class="sticky bottom-0 px-4 pb-4 pt-2 bg-gradient-to-t from-background via-background/95 to-transparent"
                    x-show="$wire.currentStep >= 2 && $wire.currentStep <= 4">
                <div class="flex items-center gap-3">
                    <button type="button" @click="goPrev()" @mousedown="pressBtn($el)"
                            x-show="$wire.currentStep > 1"
                            :disabled="busy"
                            class="btn-press-secondary h-12 px-5 rounded-xl text-sm font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                        قبلی
                    </button>
                    <div class="flex-1"></div>
                    <button type="button" @click="goNext()" @mousedown="pressBtn($el)"
                            :disabled="busy"
                            class="btn-press h-12 px-8 rounded-xl text-sm font-bold flex items-center gap-2">
                        <span x-show="!busy" class="flex items-center gap-2">
                            ادامه
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                            </svg>
                        </span>
                        <span x-show="busy" class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="32" stroke-linecap="round" opacity="0.5"/>
                            </svg>
                            صبر کنید…
                        </span>
                    </button>
                </div>
            </footer>
        </div>


        {{-- ─────────────────────────────────────────────────────────
             🖥️ DESKTOP (≥ md)
             ───────────────────────────────────────────────────────── --}}
        <div class="hidden md:block relative z-10 min-h-screen">

            <div class="max-w-7xl mx-auto px-8 pt-6">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <span class="font-black text-base tracking-wider">SDFR</span>
                </div>
            </div>

            @if ($generalError)
                <div class="max-w-7xl mx-auto px-8 mt-4">
                    <div class="rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-500 px-4 py-3 text-sm">
                        {{ $generalError }}
                    </div>
                </div>
            @endif

            {{-- Steps 5,6 centered --}}
            <div x-show="$wire.currentStep >= 5" class="max-w-2xl mx-auto px-8 py-12">
                <section x-show="$wire.currentStep === 5">
                    <div class="train-border">
                        <div class="glass-card rounded-3xl p-10 text-center">
                            <div class="inline-block w-32 h-32 mb-3">
                                @if($lottieOtp)
                                    <lottie-player src="{{ $lottieOtp }}" background="transparent" speed="1" loop autoplay style="width:100%;height:100%"></lottie-player>
                                @else
                                    {!! $fbOtp !!}
                                @endif
                            </div>
                            <h2 class="font-black text-2xl mb-2">کد تأیید را وارد کنید</h2>
                            <p class="text-sm text-muted mb-6 leading-7">
                                کد ۶ رقمی به <strong dir="ltr" class="text-primary">{{ $mobile }}</strong> ارسال شد.
                            </p>
                            <input wire:model="otpInput" type="text" maxlength="6" placeholder="------"
                                   inputmode="numeric" dir="ltr" x-init="$el.focus()"
                                   class="glass-input w-full text-center tracking-[0.7em] text-3xl font-mono rounded-2xl px-4 py-4 mb-3">

                            @if($otpError)
                                <div class="text-rose-500 text-xs mb-3">{{ $otpError }}</div>
                            @endif

                            <div class="flex items-center justify-between mt-5">
                                @if($countdown > 0)
                                    <span class="text-muted text-sm">
                                        ارسال مجدد تا
                                        <span class="text-primary font-mono mx-1" x-text="$wire.countdown"></span>
                                        ثانیه
                                    </span>
                                @else
                                    <button type="button" wire:click="resendOtp"
                                            class="text-primary hover:underline text-sm font-bold">ارسال مجدد کد</button>
                                @endif

                                <button type="button" wire:click="verifyOtp" @mousedown="pressBtn($el)"
                                        wire:loading.attr="disabled" wire:target="verifyOtp"
                                        class="btn-press px-8 py-3 rounded-xl font-bold">
                                    <span wire:loading.remove wire:target="verifyOtp">تأیید کد</span>
                                    <span wire:loading wire:target="verifyOtp">در حال بررسی…</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <section x-show="$wire.currentStep === 6">
                    <div class="train-border">
                        <div class="glass-card rounded-3xl p-10">
                            <div class="text-center mb-8">
                                <div class="inline-block w-32 h-32 mb-2">
                                    @if($lottieSuccess)
                                        <lottie-player src="{{ $lottieSuccess }}" background="transparent" speed="1" autoplay style="width:100%;height:100%"></lottie-player>
                                    @else
                                        {!! $fbSuccess !!}
                                    @endif
                                </div>
                                <h2 class="font-black text-3xl mb-2">حساب شما ساخته شد</h2>
                                <p class="text-muted">یکی از مسیرها را برای ادامه انتخاب کنید.</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <button type="button" wire:click="openTrialConfirm"
                                        class="accent-card accent-emerald rounded-2xl p-6 text-right hover:-translate-y-1 transition-transform">
                                    <div class="inline-flex items-center gap-1.5 text-xs font-bold accent-icon accent-emerald rounded-full px-2.5 py-1 mb-4">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> رایگان
                                    </div>
                                    <h3 class="font-black text-xl mb-1">۱ هفته آزمایشی</h3>
                                    <p class="text-sm text-muted leading-6">تجربه‌ی کامل امکانات بدون پرداخت</p>
                                </button>

                                <button type="button" wire:click="goToPurchase"
                                        class="accent-card accent-sky rounded-2xl p-6 text-right hover:-translate-y-1 transition-transform">
                                    <div class="inline-flex items-center gap-1.5 text-xs font-bold accent-icon accent-sky rounded-full px-2.5 py-1 mb-4">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                        </svg>
                                        کامل
                                    </div>
                                    <h3 class="font-black text-xl mb-1">خرید دوره</h3>
                                    <p class="text-sm text-muted leading-6">دسترسی به همه‌ی امکانات با قیمت پلکانی</p>
                                </button>
                            </div>

                            <div class="text-center mt-6">
                                <button type="button" wire:click="declineTrial"
                                        class="text-sm text-muted hover:text-foreground transition-colors">
                                    فعلاً نه — بازگشت به صفحه اصلی
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            {{-- Steps 1-4: Split layout --}}
            <div x-show="$wire.currentStep < 5" class="max-w-7xl mx-auto px-8 pt-8 pb-12">
                <div class="grid grid-cols-12 gap-8 items-start">

                    {{-- RIGHT: Intro --}}
                    <div class="col-span-5 sticky top-8 space-y-6">
                        <div class="inline-flex items-center gap-2 glass-card rounded-full px-3 py-1.5">
                            <span class="relative flex w-1.5 h-1.5">
                                <span class="absolute inline-flex w-full h-full bg-primary rounded-full opacity-75 animate-ping"></span>
                                <span class="relative inline-flex w-1.5 h-1.5 bg-primary rounded-full"></span>
                            </span>
                            <span class="font-semibold text-xs">هفته‌ی آزمایشی رایگان</span>
                        </div>

                        <h1 class="font-black text-4xl leading-tight">
                            به <span class="text-primary">SDFR</span> خوش آمدید
                        </h1>
                        <p class="text-muted leading-8 text-sm">
                            پلتفرم هوشمند پایش مطالعه و مشاوره‌ی تخصصی برای دانش‌آموزان جدی.
                            با تکمیل فرم روبه‌رو، حساب کاربری شما ساخته می‌شه و وارد یک هفته‌ی
                            آزمایشی رایگان می‌شید.
                        </p>

                        <div class="train-border">
                            <div class="relative glass-card rounded-3xl p-6">
                                <div class="w-full h-48">
                                    @if($lottieWelcome)
                                        <lottie-player src="{{ $lottieWelcome }}" background="transparent" speed="1" loop autoplay style="width:100%;height:100%"></lottie-player>
                                    @else
                                        {!! $fbWelcome !!}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            @foreach($features as $f)
                                <div class="flex items-start gap-2.5 p-3 rounded-xl bg-secondary/50 border border-border">
                                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary/10 text-primary border border-primary/20 shrink-0">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            {!! $f['icon'] !!}
                                        </svg>
                                    </span>
                                    <div>
                                        <div class="font-bold text-xs">{{ $f['t'] }}</div>
                                        <div class="text-[10px] text-muted leading-5 mt-0.5">{{ $f['d'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- LEFT: Full form --}}
                    <div class="col-span-7">
                        <div class="train-border">
                            <div class="glass-card rounded-3xl p-8 space-y-7">

                                <div class="flex items-center gap-3 pb-5 border-b border-border">
                                    <div class="w-11 h-11 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="font-black text-lg">فرم ثبت‌نام</h2>
                                        <p class="text-xs text-muted mt-0.5">اطلاعات زیر را تکمیل کنید تا حسابتون ساخته بشه</p>
                                    </div>
                                </div>

                                <fieldset class="space-y-4">
                                    <legend class="flex items-center gap-2 font-bold text-sm text-foreground mb-1">
                                        <span class="flex items-center justify-center w-5 h-5 rounded-md bg-primary/10 text-primary text-[10px] font-black border border-primary/20">۱</span>
                                        اطلاعات شخصی
                                    </legend>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="field-wrap relative" data-tour="firstName">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">نام</label>
                                            <input wire:model.blur="firstName" type="text" placeholder="مثلاً علی"
                                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm @error('firstName') border-rose-500/60 shake @enderror">
                                            <div class="field-tip">باید فارسی باشه</div>
                                            @error('firstName')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="field-wrap relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">نام خانوادگی</label>
                                            <input wire:model.blur="lastName" type="text" placeholder="مثلاً محمدی"
                                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm @error('lastName') border-rose-500/60 shake @enderror">
                                            @error('lastName')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="field-wrap relative" data-tour="codeMell">
                                        <label class="block text-xs font-semibold mb-1.5 text-muted">کد ملی</label>
                                        <input wire:model.blur="codeMell" type="text" maxlength="10" placeholder="۱۰ رقم" inputmode="numeric" dir="ltr"
                                               class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono tracking-wider @error('codeMell') border-rose-500/60 shake @enderror">
                                        <div class="field-tip">دقیقاً ۱۰ رقم</div>
                                        @error('codeMell')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                    </div>
                                </fieldset>

                                <fieldset class="space-y-4 pt-5 border-t border-border">
                                    <legend class="flex items-center gap-2 font-bold text-sm text-foreground mb-1">
                                        <span class="flex items-center justify-center w-5 h-5 rounded-md bg-primary/10 text-primary text-[10px] font-black border border-primary/20">۲</span>
                                        والدین و پایه‌ی تحصیلی
                                    </legend>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="field-wrap relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شماره پدر</label>
                                            <input wire:model.blur="fatherMobile" type="tel" placeholder="09..." dir="ltr" inputmode="numeric"
                                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono @error('fatherMobile') border-rose-500/60 shake @enderror">
                                            @error('fatherMobile')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="field-wrap relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شماره مادر</label>
                                            <input wire:model.blur="motherMobile" type="tel" placeholder="09..." dir="ltr" inputmode="numeric"
                                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono @error('motherMobile') border-rose-500/60 shake @enderror">
                                            @error('motherMobile')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="field-wrap relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">پایه</label>
                                            <x-ui.select wire:model.live="grade" :options="$gradeOptions" placeholder="انتخاب پایه" />
                                        </div>
                                        @if($grade !== '9')
                                            <div class="field-wrap relative" wire:key="field-d-{{ $grade }}">
                                                <label class="block text-xs font-semibold mb-1.5 text-muted">رشته</label>
                                                <x-ui.select wire:model="field" :options="$fieldOptions" placeholder="انتخاب رشته" />
                                            </div>
                                        @endif
                                    </div>
                                </fieldset>

                                <fieldset class="space-y-4 pt-5 border-t border-border">
                                    <legend class="flex items-center gap-2 font-bold text-sm text-foreground mb-1">
                                        <span class="flex items-center justify-center w-5 h-5 rounded-md bg-primary/10 text-primary text-[10px] font-black border border-primary/20">۳</span>
                                        مکان و رمز عبور
                                    </legend>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="field-wrap relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">استان</label>
                                            <x-ui.select wire:model.live="stateId" :options="$stateOptions" :searchable="true" placeholder="انتخاب استان" search-placeholder="جستجوی استان..." />
                                            @error('stateId')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="field-wrap relative" wire:key="city-d-{{ $stateId }}">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شهر</label>
                                            <x-ui.select wire:model="cityId" :options="$cityOptions" :searchable="true" :disabled="(int) $stateId === 0" placeholder="انتخاب شهر" search-placeholder="جستجوی شهر..." />
                                            @error('cityId')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-span-2 field-wrap relative" data-tour="mobile">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">شماره موبایل (برای ورود)</label>
                                            <input wire:model.blur="mobile" type="tel" placeholder="09..." dir="ltr" inputmode="numeric"
                                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono @error('mobile') border-rose-500/60 shake @enderror">
                                            <div class="field-tip">کد تأیید روی این شماره میاد</div>
                                            @error('mobile')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="field-wrap relative" data-tour="password">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">رمز عبور</label>
                                            <input wire:model.live.debounce.300ms="password" type="password" dir="ltr"
                                                   autocomplete="new-password" data-lpignore="true" data-1p-ignore="true"
                                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm @error('password') border-rose-500/60 shake @enderror">
                                            <div class="flex items-center gap-1.5 mt-2 flex-wrap">
                                                <span class="text-[10px] text-muted">قدرت:</span>
                                                <span class="text-[10px] px-1.5 py-0.5 rounded transition-colors"
                                                      :class="$wire.passwordStrength?.length ? 'bg-emerald-500/15 text-emerald-500' : 'bg-secondary text-muted'">۸+</span>
                                                <span class="text-[10px] px-1.5 py-0.5 rounded transition-colors"
                                                      :class="$wire.passwordStrength?.letter ? 'bg-emerald-500/15 text-emerald-500' : 'bg-secondary text-muted'">حرف</span>
                                                <span class="text-[10px] px-1.5 py-0.5 rounded transition-colors"
                                                      :class="$wire.passwordStrength?.number ? 'bg-emerald-500/15 text-emerald-500' : 'bg-secondary text-muted'">عدد</span>
                                            </div>
                                            @error('password')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="field-wrap relative">
                                            <label class="block text-xs font-semibold mb-1.5 text-muted">تکرار رمز</label>
                                            <input wire:model.blur="passwordConf" type="password" dir="ltr"
                                                   autocomplete="new-password" data-lpignore="true" data-1p-ignore="true"
                                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm @error('passwordConf') border-rose-500/60 shake @enderror">
                                            @error('passwordConf')<div class="text-xs text-rose-500 mt-1.5">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="flex items-center justify-between gap-3 pt-5 border-t border-border">
                                    <p class="text-[11px] text-muted leading-5 max-w-[50%]">
                                        با ارسال این فرم، یک کد تأیید روی شماره‌ی موبایلتون ارسال می‌شه.
                                    </p>
                                    <button type="button" @click="submitDesktopForm()" @mousedown="pressBtn($el)"
                                            :disabled="busy"
                                            class="btn-press h-12 px-8 rounded-xl font-bold text-sm flex items-center gap-2">
                                        <span x-show="!busy" class="flex items-center gap-2">
                                            ساخت حساب و دریافت کد
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                                            </svg>
                                        </span>
                                        <span x-show="busy" class="flex items-center gap-2">
                                            <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="32" stroke-linecap="round" opacity="0.5"/>
                                            </svg>
                                            صبر کنید…
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- ═══ Trial Confirm Modal ═══ --}}
        <div x-show="$wire.showTrialConfirm"
             x-transition.opacity
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-background/80 backdrop-blur-md"
                 wire:click="closeTrialConfirm"></div>

            <div class="relative w-full max-w-sm"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="train-border">
                    <div class="glass-card rounded-2xl p-6 text-center">
                        <div class="inline-block w-24 h-24 mb-3">
                            @if($lottieSuccess)
                                <lottie-player src="{{ $lottieSuccess }}" background="transparent" speed="1" autoplay style="width:100%;height:100%"></lottie-player>
                            @else
                                {!! $fbSuccess !!}
                            @endif
                        </div>

                        <h3 class="font-black text-lg mb-2">شروع هفته‌ی آزمایشی</h3>
                        <p class="text-sm text-muted mb-5 leading-6">
                            با شروع آزمایشی، یک پشتیبان جذب با شما تماس می‌گیرد و فرایند را آغاز می‌کند.
                        </p>
                        <div class="flex items-center gap-2">
                            {{-- ✅ مستقیماً wire:click → ریدایرکت قطعی انجام می‌شود --}}
                            <button type="button" wire:click="confirmTrial"
                                    wire:loading.attr="disabled" wire:target="confirmTrial"
                                    @mousedown="pressBtn($el)"
                                    class="btn-press flex-1 px-4 py-3 rounded-xl font-bold text-sm">
                                <span wire:loading.remove wire:target="confirmTrial">بله، شروع می‌کنم</span>
                                <span wire:loading wire:target="confirmTrial">در حال ارسال…</span>
                            </button>
                            <button type="button" wire:click="closeTrialConfirm"
                                    wire:loading.attr="disabled" wire:target="confirmTrial"
                                    @mousedown="pressBtn($el)"
                                    class="btn-press-secondary px-4 py-3 rounded-xl text-sm">
                                انصراف
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
