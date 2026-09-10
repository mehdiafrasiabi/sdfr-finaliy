<div class="min-h-screen text-white" dir="rtl" style="font-family: inherit;"
     x-data="{
        showIntro: false,
        networkLooksSuspicious: false,
        noticeShown: false,
        timerId: null,
        slowTimerId: null,
        trialNoticeTimerId: null,
        storageKey: 'sdfr_dashboard_intro_seen_v1',
        trialNoticeEligible: @js($showTrialWeekPanelNotice),
        showTrialNotice: false,
        dashboardTourAuto: @js($startDashboardTour),

        init() {
            const shouldPlayIntro = this.isMobile() && localStorage.getItem(this.storageKey) !== '1';

            if (!shouldPlayIntro) {
                this.scheduleTrialNoticeAfterTourState();
                return;
            }

            localStorage.setItem(this.storageKey, '1');
            this.showIntro = true;
            document.body.style.overflow = 'hidden';
            this.slowTimerId = setTimeout(() => {
                this.networkLooksSuspicious = true;
            }, 2500);
            this.timerId = setTimeout(() => {
                this.finish();
            }, 7000);

            this.$nextTick(() => {
                if (this.$refs.introGif && this.$refs.introGif.complete) {
                    this.startIntroTimer();
                }
            });
        },

        isMobile() {
            return window.matchMedia('(max-width: 639px)').matches;
        },

        startIntroTimer() {
            if (!this.showIntro) return;

            if (this.timerId) {
                clearTimeout(this.timerId);
            }

            if (this.slowTimerId) {
                clearTimeout(this.slowTimerId);
                this.slowTimerId = null;
            }

            this.timerId = setTimeout(() => this.finish(), 5000);
        },

        retryConnection() {
            window.location.reload();
        },

        maybeShowVpnNotice() {
            if (this.noticeShown || !this.networkLooksSuspicious || !navigator.onLine) return;

            this.noticeShown = true;
            const payload = {
                type: 'info',
                message: 'برای تجربه کاربری بهتر، لطفاً اگر VPN روشن است آن را خاموش کنید.',
                duration: 10000
            };

            const fire = (attempt = 0) => {
                if (typeof window.toast === 'function') {
                    window.toast(payload.message, payload.type, payload.duration);
                    return;
                }

                window.dispatchEvent(new CustomEvent('show-toast', { detail: payload }));

                if (attempt < 4 && typeof window.toast !== 'function') {
                    setTimeout(() => fire(attempt + 1), 400);
                }
            };

            setTimeout(() => fire(), 250);
        },

        finish() {
            if (this.timerId) {
                clearTimeout(this.timerId);
                this.timerId = null;
            }

            if (this.slowTimerId) {
                clearTimeout(this.slowTimerId);
                this.slowTimerId = null;
            }

            this.showIntro = false;
            document.body.style.overflow = '';
            this.maybeShowVpnNotice();
            this.scheduleTrialNoticeAfterTourState();
        },

        skip() {
            this.finish();
        },

        destroy() {
            if (this.timerId) {
                clearTimeout(this.timerId);
            }
            if (this.slowTimerId) {
                clearTimeout(this.slowTimerId);
            }
            if (this.trialNoticeTimerId) {
                clearTimeout(this.trialNoticeTimerId);
            }
            document.body.style.overflow = '';
        },

        scheduleTrialNoticeAfterTourState() {
            if (!this.trialNoticeEligible) return;
            if (this.dashboardTourAuto) return;
            if (this.isMobile() && localStorage.getItem('dashboard_tour_done') !== '1') return;

            this.maybeOpenTrialNotice();
        },

        maybeOpenTrialNotice() {
            if (!this.trialNoticeEligible || this.showTrialNotice) return;

            if (this.showIntro) {
                setTimeout(() => this.maybeOpenTrialNotice(), 300);
                return;
            }

            if (this.trialNoticeTimerId) return;
            this.trialNoticeTimerId = setTimeout(() => {
                this.showTrialNotice = true;
                document.body.style.overflow = 'hidden';
                this.trialNoticeTimerId = null;
            }, 350);
        },

        closeTrialNotice() {
            this.showTrialNotice = false;
            this.trialNoticeEligible = false;
            document.body.style.overflow = '';
        },
     }"
     x-init="init()"
     @sdfr-page-tour-finished.window="if ($event.detail.storageKey === 'dashboard_tour_done') maybeOpenTrialNotice()">
    @assets
    <style>
        /* ════════ SDFR Dashboard — Cosmic Glass UI ════════ */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* كارت carousel موبایل */
        .program-carousel {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding-left: 16px;
        }

        .program-carousel::-webkit-scrollbar {
            display: none;
        }

        .program-carousel .program-card {
            flex-shrink: 0;
            scroll-snap-align: start;
            width: calc(75vw - 32px);
            max-width: 240px;
        }

        .program-scroll-desktop {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding-bottom: 4px;
        }

        .program-scroll-desktop::-webkit-scrollbar {
            display: none;
        }

        /* زاویه چرخان برای حاشیه‌ی کارت‌های زنده */
        @property --sdfr-ang {
            syntax: '<angle>';
            initial-value: 0deg;
            inherits: false;
        }

        /* ───── پس‌زمینه کیهانی ───── */
        .cosmic-bg {
            position: fixed;
            inset: 0;
            /* z-0 تا پشتِ پس‌زمینهٔ تیرهٔ لِی‌اوت (bg-background) پنهان نشود.
               محتوای داشبورد z-10 و هدر/نویگیشن z-50 هستند، پس روی این بک‌گراند می‌مانند. */
            z-index: 0;
            overflow: hidden;
            pointer-events: none;
            background: radial-gradient(1200px 600px at 80% -10%, rgba(56, 189, 248, .10), transparent 60%),
            radial-gradient(900px 500px at 8% 110%, rgba(16, 185, 129, .10), transparent 60%),
            linear-gradient(180deg, #070a12 0%, #0a0f1a 45%, #070a12 100%);
        }

        .cosmic-bg .twinkle {
            position: absolute;
            width: 2px;
            height: 2px;
            border-radius: 50%;
            background: #cbd5e1;
            opacity: .5;
            animation: tw 3s ease-in-out infinite;
        }

        @keyframes tw {
            0%, 100% {
                opacity: .15;
            }
            50% {
                opacity: .7;
            }
        }

        /* شهاب‌سنگ‌ها */
        .comet {
            position: absolute;
            transform-origin: center;
        }

        .comet .core {
            position: absolute;
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 0 8px 2px rgba(125, 211, 252, .9);
            opacity: 0;
            animation: comet-fly var(--dur, 8s) ease-in infinite;
            animation-delay: var(--delay, 0s);
        }

        .comet .core::before {
            content: '';
            position: absolute;
            top: 50%;
            right: 3px;
            width: 170px;
            height: 2px;
            transform: translateY(-50%);
            border-radius: 2px;
            background: linear-gradient(to left, rgba(125, 211, 252, .95), rgba(125, 211, 252, 0));
        }

        @keyframes comet-fly {
            0% {
                transform: translateX(0);
                opacity: 0;
            }
            3% {
                opacity: 1;
            }
            14% {
                transform: translateX(var(--dist, 1400px));
                opacity: 0;
            }
            100% {
                transform: translateX(var(--dist, 1400px));
                opacity: 0;
            }
        }

        /* سفینه */
        .spaceship {
            position: fixed;
            left: 16px;
            bottom: 20px;
            z-index: 0;
            width: 78px;
            pointer-events: none;
            animation: ship-float 7s ease-in-out infinite;
            filter: drop-shadow(0 8px 22px rgba(56, 189, 248, .35));
        }

        @keyframes ship-float {
            0% {
                transform: translate(0, 0) rotate(-4deg);
            }
            25% {
                transform: translate(16px, -12px) rotate(2deg);
            }
            50% {
                transform: translate(30px, -4px) rotate(-3deg);
            }
            75% {
                transform: translate(13px, -14px) rotate(3deg);
            }
            100% {
                transform: translate(0, 0) rotate(-4deg);
            }
        }

        .spaceship .flame {
            transform-box: fill-box;
            transform-origin: 50% 0%;
            animation: flame .18s ease-in-out infinite alternate;
        }

        @keyframes flame {
            from {
                transform: scaleY(.7) scaleX(1);
                opacity: .65;
            }
            to {
                transform: scaleY(1.3) scaleX(.85);
                opacity: 1;
            }
        }

        /* ───── کارت شیشه‌ای پایه ───── */
        .glass {
            position: relative;
            border-radius: 1rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, .07), rgba(255, 255, 255, .025));
            -webkit-backdrop-filter: blur(16px) saturate(140%);
            backdrop-filter: blur(16px) saturate(140%);
            border: 1px solid rgba(255, 255, 255, .09);
            box-shadow: 0 10px 34px rgba(0, 0, 0, .40), inset 0 1px 0 rgba(255, 255, 255, .07);
            transition: transform .35s cubic-bezier(.2, .8, .2, 1), box-shadow .35s, border-color .35s;
        }

        .glass:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 46px rgba(0, 0, 0, .5), inset 0 1px 0 rgba(255, 255, 255, .10);
        }

        /* کارت‌های «زنده» → حاشیه نوری چرخان + نقطه ضربان‌دار */
        .card-live::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: inherit;
            padding: 1.4px;
            pointer-events: none;
            background: conic-gradient(from var(--sdfr-ang),
            transparent 0deg, transparent 250deg,
            rgba(16, 185, 129, .9) 300deg, rgba(56, 189, 248, 1) 330deg, transparent 360deg);
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            mask-composite: exclude;
            animation: sdfr-spin 4.5s linear infinite;
        }

        @keyframes sdfr-spin {
            to {
                --sdfr-ang: 360deg;
            }
        }

        .live-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #34d399;
            box-shadow: 0 0 0 0 rgba(52, 211, 153, .6);
            animation: pulse-dot 1.8s ease-out infinite;
            flex-shrink: 0;
        }

        @keyframes pulse-dot {
            0% {
                box-shadow: 0 0 0 0 rgba(52, 211, 153, .55);
            }
            70% {
                box-shadow: 0 0 0 7px rgba(52, 211, 153, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(52, 211, 153, 0);
            }
        }

        /* کارت‌های «تحلیلی/داده» → بافت نقطه‌چین خنک، بدون چرخش */
        .card-data {
            background-image: radial-gradient(rgba(56, 189, 248, .10) 1px, transparent 1.4px),
            linear-gradient(135deg, rgba(56, 189, 248, .06), rgba(255, 255, 255, .02));
            background-size: 16px 16px, 100% 100%;
            border-color: rgba(56, 189, 248, .18);
        }

        .card-data::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            pointer-events: none;
            box-shadow: inset 0 0 0 1px rgba(56, 189, 248, .10);
        }

        /* چیپ آیکن شیشه‌ای */
        .icon-chip {
            width: 1.9rem;
            height: 1.9rem;
            border-radius: .6rem;
            background: rgba(255, 255, 255, .05);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, .10);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ورود مرحله‌ای کارت‌ها */
        .rise {
            opacity: 0;
            animation: rise .6s cubic-bezier(.2, .8, .2, 1) forwards;
        }

        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: none;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .comet .core, .spaceship, .card-live::before, .twinkle, .spaceship .flame {
                animation: none !important;
            }

            .rise {
                animation: none !important;
                opacity: 1;
            }
        }

        @media (min-width: 640px) {
            .sdfr-intro-overlay {
                display: none !important;
            }
        }
    </style>
    @endassets
    {{-- ════════ انیمیشن ورود (گیف) — یکبار در ورود ════════ --}}
    <div x-show="showIntro" x-cloak wire:ignore
         @keydown.escape.window="skip()"
         class="sdfr-intro-overlay fixed inset-0 z-[120] flex items-center justify-center bg-black sm:hidden"
         x-transition:leave="transition ease-in duration-500"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        {{-- گیف اصلی (فقط موبایل) --}}
        <img x-ref="introGif"
             src="/client/videos/sdfr-intro.webp"
             class="h-full w-full object-cover sm:hidden"
             x-on:load="startIntroTimer()"
             x-on:error="finish()"
             alt="SDFR Intro"/>
    </div>

    <div class="sdfr-dashboard-content">
        <div x-data="{ openAdvisorModal: false }">
            {{-- ════════ تور راهنمای داشبورد (اولین ورود + آیکون راهنما) ════════ --}}
            <x-client.page-tour storage-key="dashboard_tour_done"
                                :auto="$startDashboardTour"
                                :steps="[
        /* ── موبایل: اشاره به منوی هدر ── */
        ['el' => '[data-tour=m-menu]',           'title' => 'منوها اینجاست',     'text' => 'با زدن این آیکون در بالای صفحه، به همه‌ی منوها و بخش‌های حساب کاربری‌ات دسترسی داری.',                                                                  'forced' => true],
        /* ── دسکتاپ: آیتم‌های سایدبار یکی‌یکی ── */
        ['el' => '[data-tour=sb-dashboard]',     'title' => 'داشبورد',           'text' => 'نمای کلی وضعیت و خلاصه‌ی امروزت اینجاست؛ هر وقت خواستی به اینجا برگرد.',                                                                                 'forced' => true],
        ['el' => '[data-tour=sb-consultation]',  'title' => 'اتاق مشاوره',       'text' => 'گفتگو و جلسات با مشاورت را از اینجا دنبال کن.',                                                                                                            'forced' => true],
        ['el' => '[data-tour=sb-plan]',          'title' => 'برنامه‌های مطالعاتی','text' => 'برنامه‌ی درسی اختصاصی‌ات را اینجا می‌بینی و اجرا می‌کنی.',                                                                                                'forced' => true],
        ['el' => '[data-tour=sb-report]',        'title' => 'گزارش‌های درسی',     'text' => 'گزارش مطالعه‌ی روزانه‌ات را از این بخش ثبت و مرور کن.',                                                                                                     'forced' => true],
        ['el' => '[data-tour=sb-exam]',          'title' => 'آزمون‌ها',          'text' => 'آزمون‌های تستی و تشریحی‌ات را از اینجا شروع کن.',                                                                                                          'forced' => true],
        ['el' => '[data-tour=sb-smart-report]',  'title' => 'کارنامه هوشمند',     'text' => 'تحلیل و نمودار پیشرفت تحصیلی‌ات را اینجا می‌بینی.',                                                                                                        'forced' => true],
        ['el' => '[data-tour=sb-classification]','title' => 'طبقه‌بندی دروس',     'text' => 'سطح تسلطت روی هر درس را اینجا مشخص می‌کنی تا برنامه دقیق‌تر شود.',                                                                                          'forced' => true],
        /* ── موبایل: ناوبریِ پایین ── */
        ['el' => '[data-tour=nav-consultation]', 'title' => 'اتاق مشاوره',       'text' => 'از اینجا می‌تونی وارد اتاق مشاوره بشی و با مشاورت ارتباط بگیری.',                                                                                        'forced' => true],
        ['el' => '[data-tour=nav-plan]',         'title' => 'برنامه درسی',       'text' => 'برنامه مطالعه درسیت رو اینجا می‌بینی و اجرا می‌کنی.',                                                                                                       'forced' => true],
        ['el' => '[data-tour=nav-logo]',         'title' => 'داشبورد',           'text' => 'با لمس لوگو وسط، هر جا باشی سریع به داشبورد اصلی برمی‌گردی.',                                                                                               'forced' => true],
        ['el' => '[data-tour=nav-report]',       'title' => 'گزارش روزانه',      'text' => 'گزارش مطالعه امروزت رو از همین‌جا ثبت کن.',                                                                                                                  'forced' => true],
        ['el' => '[data-tour=nav-exam]',         'title' => 'آزمون',             'text' => 'آزمون‌های تستی و تشریحیت رو از این بخش شروع کن.',                                                                                                           'forced' => true],
        ['el' => '[data-tour=sudden-event]',     'title' => 'اتفاقات یهویی',    'text' => 'اگه یه اتفاق غیرمنتظره پیش اومد (مثل بیماری یا مسافرت)، از این دکمه ثبت کن تا برنامه‌ات تنظیم بشه.',                                                     'forced' => true],
        ['el' => '[data-tour=class-schedule]',   'title' => 'برنامه کلاسی مدرسه','text' => 'برنامه هفتگی کلاس‌های مدرسه‌ات رو از اینجا ببین تا با برنامه مطالعه‌ات هماهنگ کنی.',                                                                   'forced' => true],
    ]"
            />
            <div x-show="showTrialNotice" x-cloak wire:ignore.self
                 class="fixed inset-0 z-[110] flex items-end justify-center bg-black/70 px-4 py-4 backdrop-blur-sm sm:items-center"
                 x-transition.opacity>
                <?php $trialNoticeIsExamMode = (bool) ($dashboardPeriod['is_exam_program'] ?? false); ?>
                <div class="w-full max-w-lg overflow-hidden rounded-2xl border border-white/10 bg-[#101827]/95 shadow-2xl shadow-sky-950/40"
                     @click.stop
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-6 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                    <div class="relative p-5 sm:p-6">
                        <div class="absolute inset-x-0 top-0 h-1 bg-primary"></div>

                        <div class="mb-5 flex items-start gap-3 text-right">
                            <div class="mt-1 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-2xl bg-primary/15 text-primary ring-1 ring-primary/25">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2v20"/>
                                    <path d="m17 5-5-3-5 3"/>
                                    <path d="m17 19-5 3-5-3"/>
                                    <path d="M2 12h20"/>
                                    <path d="m5 7-3 5 3 5"/>
                                    <path d="m19 7 3 5-3 5"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-base font-black text-white sm:text-lg">
                                    {{ $trialNoticeIsExamMode ? 'پنل امتحانات شما فعال شد' : 'پنل هفته آزمایشی شما فعال شد' }}
                                </p>
                                <p class="mt-2 text-sm leading-7 text-neutral-300">
                                    @if($trialNoticeIsExamMode)
                                        این پنل برای مدیریت برنامه امتحاناتت فعال شده است. از اینجا می‌توانی برنامه‌ی
                                        امتحانی، گزارش‌ها و مسیر مطالعه‌ات تا پایان امتحانات را دنبال کنی.
                                    @else
                                        این پنل برای تجربه‌ی یک هفته آزمایشی ساخته شده است. برنامه، گزارش‌ها و امکاناتی که
                                        اینجا می‌بینی فقط نمونه‌ای کوچک از خدمات کامل SDFR هستند تا با مسیر کار آشنا شوی.
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white/[0.04] p-4 text-right">
                            <p class="text-xs font-bold text-primary">
                                {{ $trialNoticeIsExamMode ? 'در مسیر امتحانات چه می‌بینی؟' : 'در دوره کامل چه اتفاقی می‌افتد؟' }}
                            </p>
                            <p class="mt-2 text-xs leading-6 text-neutral-400">
                                @if($trialNoticeIsExamMode)
                                    برنامه‌ریزی امتحانی، پیگیری روند مطالعه، نمایش برنامه روزانه و گزارش‌گیری این بازه
                                    از همین‌جا در اختیار تو قرار می‌گیرد تا مسیر امتحاناتت را متمرکزتر جلو ببری.
                                @else
                                    بعد از پایان هفته آزمایشی، برنامه‌ریزی، پیگیری مشاور، تحلیل عملکرد و ابزارهای گزارش‌دهی
                                    به شکل کامل‌تر و اختصاصی‌تر در اختیار شما قرار می‌گیرد.
                                @endif
                            </p>
                        </div>

                        <button type="button"
                                wire:click="acknowledgeTrialWeekPanelNotice"
                                wire:loading.attr="disabled"
                                wire:target="acknowledgeTrialWeekPanelNotice"
                                @click="closeTrialNotice()"
                                class="mt-5 flex h-12 w-full items-center justify-center rounded-xl bg-primary px-4 text-sm font-black text-white shadow-lg shadow-primary/20 transition hover:brightness-110 disabled:cursor-wait disabled:opacity-70">
                            <span wire:loading.remove wire:target="acknowledgeTrialWeekPanelNotice">متوجه شدم</span>
                            <span wire:loading wire:target="acknowledgeTrialWeekPanelNotice">در حال ثبت...</span>
                        </button>
                    </div>
                </div>
            </div>

            @if(!empty($pendingExamFeedback))
                <div
                    x-data="{
                        init() { document.body.style.overflow = 'hidden'; },
                        destroy() { document.body.style.overflow = ''; }
                    }"
                    class="fixed inset-0 z-[130] flex items-end justify-center bg-black/75 px-4 py-4 backdrop-blur-sm sm:items-center"
                    role="dialog"
                    aria-modal="true"
                    wire:key="pending-exam-feedback-{{ $pendingExamFeedback['schedule_id'] }}-{{ $pendingExamFeedback['exam_date'] }}">
                    <form wire:submit.prevent="submitExamDayFeedback"
                          class="w-full max-w-lg overflow-hidden rounded-3xl border border-white/10 bg-[#101827] shadow-2xl shadow-red-950/30"
                          @click.stop>
                        <div class="relative p-5 sm:p-6">
                            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-l from-red-500 via-amber-400 to-emerald-400"></div>

                            <div class="mb-5 text-center">
                                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-3xl bg-emerald-500/10 text-emerald-300 ring-1 ring-emerald-500/20">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8m-4-4v4M7 4h10v5a5 5 0 0 1-10 0V4Zm0 1H4v2a3 3 0 0 0 3 3m10-5h3v2a3 3 0 0 1-3 3"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-black text-white">خسته نباشی!</h3>
                                <p class="mt-2 text-sm leading-7 text-neutral-300">
                                    آزمون امروزت
                                    @if(!empty($pendingExamFeedback['subjects']))
                                        برای <span class="font-bold text-white">{{ implode('، ', $pendingExamFeedback['subjects']) }}</span>
                                    @endif
                                    تموم شد. لطفاً وضعیتش رو ثبت کن تا ادامه بدیم.
                                </p>
                                <p class="mt-1 text-xs text-neutral-500">
                                    {{ $pendingExamFeedback['day_name'] }} {{ $pendingExamFeedback['jalali_date'] }}
                                </p>
                            </div>

                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-neutral-300">آزمون امروزت رو چیکار کردی؟</label>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($pendingExamFeedback['difficulty_options'] as $value => $label)
                                        <label class="group cursor-pointer">
                                            <input type="radio"
                                                   wire:model="examFeedbackDifficulty"
                                                   value="{{ $value }}"
                                                   class="peer sr-only">
                                            <span class="flex h-12 items-center justify-center rounded-2xl border border-white/10 bg-white/[0.04] px-3 text-sm font-bold text-neutral-300 transition peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white group-hover:bg-white/[0.07]">
                                                {{ $label }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('examFeedbackDifficulty')
                                <p class="text-xs font-bold text-red-400">{{ $message }}</p>
                                @enderror

                                <div>
                                    <label class="mb-1.5 block text-xs font-bold text-neutral-300">یادداشت</label>
                                    <textarea wire:model.defer="examFeedbackNote"
                                              rows="4"
                                              class="w-full resize-none rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-sm text-white outline-none transition placeholder:text-neutral-600 focus:border-primary"
                                              placeholder="اگر نکته‌ای از آزمون امروزت هست، اینجا بنویس..."></textarea>
                                    @error('examFeedbackNote')
                                    <p class="mt-1 text-xs font-bold text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="submitExamDayFeedback"
                                    class="mt-5 flex h-12 w-full items-center justify-center rounded-2xl bg-primary px-4 text-sm font-black text-white shadow-lg shadow-primary/20 transition hover:brightness-110 disabled:cursor-wait disabled:opacity-70">
                                <span wire:loading.remove wire:target="submitExamDayFeedback">ثبت و ادامه</span>
                                <span wire:loading wire:target="submitExamDayFeedback" class="inline-flex items-center gap-2">
                                    <x-ui.spinner size="sm" class="text-white" />
                                    در حال ثبت...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="cosmic-bg" wire:ignore aria-hidden="true">
                {{-- ستاره‌های ثابت چشمک‌زن --}}
                <span class="twinkle" style="top:12%;left:18%;animation-delay:0s"></span>
                <span class="twinkle" style="top:24%;left:72%;animation-delay:.6s"></span>
                <span class="twinkle" style="top:40%;left:35%;animation-delay:1.1s"></span>
                <span class="twinkle" style="top:8%;left:54%;animation-delay:1.7s"></span>
                <span class="twinkle" style="top:62%;left:82%;animation-delay:.3s"></span>
                <span class="twinkle" style="top:78%;left:22%;animation-delay:2.1s"></span>
                <span class="twinkle" style="top:55%;left:60%;animation-delay:1.4s"></span>
                <span class="twinkle" style="top:30%;left:90%;animation-delay:.9s"></span>
                <span class="twinkle" style="top:85%;left:48%;animation-delay:2.6s"></span>
                <span class="twinkle" style="top:48%;left:8%;animation-delay:1.9s"></span>

                {{-- شهاب‌سنگ‌های دنباله‌دار (از هر طرف، هر چند ثانیه یکبار رد می‌شن) --}}
                <span class="comet" style="top:6%;left:-8%;transform:rotate(20deg)"><span class="core"
                                                                                          style="--dur:9s;--delay:0s;--dist:1500px"></span></span>
                <span class="comet" style="top:-5%;left:62%;transform:rotate(150deg)"><span class="core"
                                                                                            style="--dur:11s;--delay:2.5s;--dist:1500px"></span></span>
                <span class="comet" style="top:70%;left:-10%;transform:rotate(-12deg)"><span class="core"
                                                                                             style="--dur:8s;--delay:4s;--dist:1500px"></span></span>
                <span class="comet" style="top:30%;left:82%;transform:rotate(200deg)"><span class="core"
                                                                                            style="--dur:12s;--delay:1.2s;--dist:1500px"></span></span>
                <span class="comet" style="top:85%;left:50%;transform:rotate(220deg)"><span class="core"
                                                                                            style="--dur:10s;--delay:5.5s;--dist:1500px"></span></span>
                <span class="comet" style="top:15%;left:30%;transform:rotate(35deg)"><span class="core"
                                                                                           style="--dur:13s;--delay:3.3s;--dist:1500px"></span></span>
                <span class="comet" style="top:50%;left:-12%;transform:rotate(8deg)"><span class="core"
                                                                                           style="--dur:9.5s;--delay:6.8s;--dist:1500px"></span></span>

                {{-- سفینه گوشه‌ی چپ پایین --}}
                <svg class="spaceship" viewBox="0 0 80 120" fill="none" xmlns="http://www.w3.org/2000/svg"
                     aria-hidden="true">
                    <defs>
                        <linearGradient id="shipBody" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#e2e8f0"/>
                            <stop offset=".5" stop-color="#94a3b8"/>
                            <stop offset="1" stop-color="#475569"/>
                        </linearGradient>
                        <linearGradient id="shipFlame" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0" stop-color="#a5f3fc"/>
                            <stop offset=".45" stop-color="#38bdf8"/>
                            <stop offset="1" stop-color="rgba(56,189,248,0)"/>
                        </linearGradient>
                        <radialGradient id="shipWin" cx=".5" cy=".4" r=".7">
                            <stop offset="0" stop-color="#a7f3d0"/>
                            <stop offset="1" stop-color="#10b981"/>
                        </radialGradient>
                    </defs>
                    {{-- شعله موتور --}}
                    <g class="flame">
                        <path d="M31 84 Q40 120 49 84 Q40 96 31 84 Z" fill="url(#shipFlame)"/>
                    </g>
                    {{-- بدنه --}}
                    <path d="M40 6 C54 20 58 44 54 70 L52 84 H28 L26 70 C22 44 26 20 40 6 Z" fill="url(#shipBody)"
                          stroke="#cbd5e1" stroke-width="1"/>
                    {{-- بال‌ها --}}
                    <path d="M26 64 L12 86 L28 80 Z" fill="#0ea5e9"/>
                    <path d="M54 64 L68 86 L52 80 Z" fill="#0ea5e9"/>
                    {{-- پنجره --}}
                    <circle cx="40" cy="38" r="9" fill="url(#shipWin)" stroke="#e2e8f0" stroke-width="1.5"/>
                    <circle cx="40" cy="34" r="2.5" fill="rgba(255,255,255,.55)"/>
                </svg>
            </div>

            <div class="max-w-7xl mx-auto px-4 relative z-10">
                <div class="flex gap-6 items-start">

                    {{-- ===== SIDEBAR (دسکتاپ) ===== --}}
                    <div class="hidden md:block flex-shrink-0 w-[260px] sticky top-6">
                        <livewire:client.profile.sidebar/>
                    </div>

                    {{-- ===== MAIN CONTENT ===== --}}
                    <div class="flex-1 min-w-0">

                        {{-- ─── Banners ─── --}}
                        <?php
                            $trialWeek = \App\Models\TrialWeek::where('user_id', $user->id)->latest()->first();
                            $isTrialStudent = $student && $student->is_trial;
                            $showTrialBanner = !$student || $isTrialStudent;
                        ?>
                        <div class="space-y-3 mb-5">
                            @if($showTrialBanner && !$trialWeek)
                                <div class="glass rise rounded-2xl p-4" style="animation-delay:0s">
                                    <div class="flex items-center justify-between gap-3">
                                        <livewire:client.profile.trial-week.start/>
                                        <div class="text-right">
                                            <div class="font-bold text-white text-sm">یک هفته آزمایشی رایگان</div>
                                            <div class="text-xs mt-1 text-green-400/80">برنامه شخصی • پشتیبان اختصاصی
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($showTrialBanner && $trialWeek)
                                <div data-tour="trial"
                                     class="glass rise flex items-center justify-between p-4 rounded-2xl"
                                     style="animation-delay:0s">
                                    <div class="text-right">
                                        <div class="font-semibold text-white text-sm">
                                            هفته آزمایشی -
                                            @if($trialWeek->isExpired())
                                                <span class="text-red-400">منقضی شده</span>
                                            @elseif($trialWeek->expires_at)
                                                {{ $trialWeek->daysRemaining }} روز باقی‌مانده
                                            @else
                                                با ساخت برنامه، ۸ روز دسترسی فعال می‌شود
                                            @endif</div>
                                        <div class="text-xs text-neutral-400">

                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <?php
                                            // درصد بر اساس روزهای باقی‌مانده محاسبه می‌شود (از ۱۰۰٪ شروع شده و کم می‌شود)
                                            if ($trialWeek->expires_at) {
                                                $tw_total = $trialWeek->program_built_at
                                                    ? max(1, (int) \Carbon\Carbon::parse($trialWeek->program_built_at)->startOfDay()
                                                            ->diffInDays(\Carbon\Carbon::parse($trialWeek->expires_at)->startOfDay()))
                                                    : 8;
                                                $tw_remaining = max(0, (int) $trialWeek->daysRemaining);

                                                // تغییر اصلی اینجاست: به جای روزهای سپری شده، روزهای باقی‌مانده را مبنای درصد قرار می‌دهیم
                                                $sp = $trialWeek->isExpired()
                                                    ? 0
                                                    : min(100, (int) round($tw_remaining / $tw_total * 100));
                                            } else {
                                                // اگر می‌خواهید درصدِ مراحلِ قبل از ساخت برنامه هم معکوس باشد (از ۱۰۰ به سمت ۰ بیاید):
                                                $sp = 100 - (($trialWeek->step / 4) * 100);

                                                // اما اگر ترجیح می‌دهید پر شدن مراحل اولیه (قبل از شروع ۸ روز) مثل قبل از ۰ تا ۱۰۰ بالا برود، خط زیر را فعال نگه دارید:
                                                // $sp = ($trialWeek->step / 4) * 100;
                                            }
                                        ?>
                                        <span class="text-xs text-green-400">{{ (int)$sp }}%</span>
                                        <div class="w-14 h-1 rounded-full overflow-hidden bg-green-900/50">
                                            <div class="h-full rounded-full bg-green-400"
                                                 style="width:{{ $sp }}%;"></div>
                                        </div>

                                    </div>

                                </div>
                            @endif

                            @if($student && !$isTrialStudent && $unreadNotificationsCount > 0)
                                <a wire:navigate href="{{ route('client.profile.notification') }}"
                                   class="glass card-live rise flex items-center justify-between p-4 rounded-2xl"
                                   style="animation-delay:.05s">
                                    <span class="font-bold text-sm text-yellow-400">{{ $unreadNotificationsCount }} پیام خوانده نشده</span>
                                    <span class="text-sm text-white">مشاهده پیام‌ها ←</span>
                                </a>
                            @endif
                        </div>

                        {{-- ════════════════════════════════════════════
                             گرید اصلی:
                               کارت‌های «زنده» (مشاور، گزارش، ساعت مطالعه، برنامه امروز)
                                 → glass + card-live (حاشیه نوری چرخان + نقطه ضربان‌دار)
                               کارت‌های «تحلیلی» (مطالعه روزانه، پیشرفت دروس)
                                 → glass + card-data (بافت نقطه‌چین خنک)
                        ════════════════════════════════════════════ --}}

                        @if(!empty($schoolInfo))
                            @if(!empty($schoolInfo['image']))
                                <div class="relative z-10 mb-4 rounded-2xl overflow-hidden glass">
                                    <img src="{{ $schoolInfo['image'] }}" alt="{{ $schoolInfo['name'] }}"
                                         class="w-full h-auto object-cover" style="max-height: 360px;">
                                </div>
                            @endif
                            <div class="relative z-10 mb-4 rounded-2xl glass p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 flex-shrink-0 text-primary" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M22 9 12 5 2 9l10 4 10-4v6"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M6 10.6V16a6 3 0 0 0 12 0v-5.4"/>
                                    </svg>
                                    <span class="font-bold text-sm">مدرسه: {{ $schoolInfo['name'] }}</span>
                                </div>
                                @if($schoolInfo['manager'] || $schoolInfo['phone'])
                                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-foreground/80 pr-7">
                                        @if($schoolInfo['manager'])
                                            <div>مدیر مدرسه: <span
                                                    class="font-semibold">{{ $schoolInfo['manager'] }}</span>
                                            </div>
                                        @endif
                                        @if($schoolInfo['phone'])
                                            <div>تلفن: <span class="font-semibold"
                                                             dir="ltr">{{ $schoolInfo['phone'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- ══════ 1) مشاور (زنده) ══════ --}}
                            <div class="glass rise md:col-span-2 p-4 flex items-center justify-between gap-4"
                                 data-tour="advisor" style="animation-delay:.1s;margin-bottom: 36px">
                                {{-- راست: عکس + نام --}}
                                <div class="flex items-center gap-3">
                                    @if($advisorStudent && !empty($advisorStudent['picture']))
                                        <img
                                            src="{{ asset('adminsFile/' . $advisorStudent['id'] . '/' . $advisorStudent['picture']) }}"
                                            alt="{{ $advisorStudent['name'] }}"
                                            class="w-12 h-12 rounded-full object-cover ring-2 ring-white/20 flex-shrink-0">
                                    @else
                                        <div
                                            class="w-12 h-12 rounded-full bg-white/5 ring-2 ring-white/15 flex items-center justify-center flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="#94a3b8" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="text-right">
                                        <div class="font-bold text-white text-base leading-tight">
                                            {{ $advisorStudent['name'] ?? 'تعیین نشده' }}
                                        </div>
                                        <div class="font-bold text-[11px] mt-1 text-sky-500">
                                            مشاور شما
                                        </div>

                                    </div>
                                </div>
                                {{-- چپ: نقطه زنده + دکمه فلش --}}
                                <div class="flex items-center gap-3 flex-shrink-0">
                                    <span class="live-dot" title="در دسترس"></span>
                                    <button @click="openAdvisorModal = true"
                                            class="w-9 h-9 rounded-full bg-white/5 ring-1 ring-white/10 flex items-center justify-center hover:bg-white/10 transition cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="2"
                                             stroke="#cbd5e1" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            @if(!empty($examPlanningCard))
                                <a wire:navigate href="{{ $examPlanningCard['route'] }}"
                                   class="glass card-live rise md:col-span-2 p-4 flex items-center justify-between gap-4"
                                   style="animation-delay:.12s;margin-top:-20px;margin-bottom: 24px">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2Z"/>
                                            </svg>
                                        </div>
                                        <div class="text-right">
                                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                                <div class="font-bold text-white text-base">{{ $examPlanningCard['title'] }}</div>
                                                <span class="rounded-full bg-white/5 px-2 py-0.5 text-[10px] font-bold text-amber-300">{{ $examPlanningCard['badge'] }}</span>
                                            </div>
                                            <div class="text-xs text-neutral-400 leading-6">{{ $examPlanningCard['description'] }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 text-primary font-bold text-sm flex-shrink-0">
                                        <span>{{ $examPlanningCard['cta'] }}</span>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </div>
                                </a>
                            @endif

                            @if(!empty($examCountdownCard))
                                <div
                                    x-data="{
                                        target: new Date(@js($examCountdownCard['ends_at'])).getTime(),
                                        now: Date.now(),
                                        timer: null,
                                        init() {
                                            this.tick();
                                            this.timer = setInterval(() => this.tick(), 1000);
                                        },
                                        tick() {
                                            this.now = Date.now();
                                            if (this.remaining <= 0) {
                                                clearInterval(this.timer);
                                                setTimeout(() => window.location.reload(), 1200);
                                            }
                                        },
                                        destroy() {
                                            if (this.timer) clearInterval(this.timer);
                                        },
                                        get remaining() {
                                            return Math.max(0, this.target - this.now);
                                        },
                                        pad(value) {
                                            return String(value).padStart(2, '0');
                                        },
                                        get hours() {
                                            return Math.floor(this.remaining / 3600000);
                                        },
                                        get minutes() {
                                            return Math.floor((this.remaining % 3600000) / 60000);
                                        },
                                        get seconds() {
                                            return Math.floor((this.remaining % 60000) / 1000);
                                        }
                                    }"
                                    class="glass card-live rise md:col-span-2 p-4 overflow-hidden"
                                    style="animation-delay:.13s;margin-top:-20px;margin-bottom: 24px">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                        <div class="flex items-start gap-3">
                                            <div class="w-12 h-12 rounded-2xl bg-red-500/10 text-red-300 flex items-center justify-center flex-shrink-0 ring-1 ring-red-500/20">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"/>
                                                </svg>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-black text-white text-base mb-1">زمان باقی‌مانده تا امتحان پیش‌رو</div>
                                                <div class="text-xs text-neutral-400 leading-6">
                                                    {{ $examCountdownCard['day_name'] }} {{ $examCountdownCard['jalali_date'] }}
                                                    @if(!empty($examCountdownCard['subjects']))
                                                        <span class="text-neutral-500">·</span>
                                                        {{ implode('، ', $examCountdownCard['subjects']) }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-3 gap-2 min-w-[220px]" dir="ltr">
                                            <div class="rounded-2xl bg-white/[0.04] ring-1 ring-white/10 px-3 py-2 text-center">
                                                <div class="font-black text-2xl text-white" x-text="pad(hours)"></div>
                                                <div class="text-[10px] text-neutral-400 font-bold">ساعت</div>
                                            </div>
                                            <div class="rounded-2xl bg-white/[0.04] ring-1 ring-white/10 px-3 py-2 text-center">
                                                <div class="font-black text-2xl text-white" x-text="pad(minutes)"></div>
                                                <div class="text-[10px] text-neutral-400 font-bold">دقیقه</div>
                                            </div>
                                            <div class="rounded-2xl bg-white/[0.04] ring-1 ring-white/10 px-3 py-2 text-center">
                                                <div class="font-black text-2xl text-red-300" x-text="pad(seconds)"></div>
                                                <div class="text-[10px] text-neutral-400 font-bold">ثانیه</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif


                            {{-- ══════ استوری‌های SDFR (بالای گزارش روزانه و ساعت مطالعه) ══════ --}}
                            <div class="md:col-span-2" style="margin-bottom: 12px">
                                <livewire:client.home.story.index/>
                            </div>

                            {{-- ══════ 2) ارسال گزارش (زنده) ══════ --}}
                            <div class="glass rise p-4" data-tour="report" style="animation-delay:.15s">
                                {{-- هدر --}}
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="live-dot"></span>
                                        <span class="font-bold text-white text-[15px]">وضعیت گزارش روزانه من</span>

                                    </div>
                                </div>
                                <p class="text-[11px] text-neutral-400 mb-4">{{ $dashboardPeriod['report_hint'] }}</p>

                                @if($reportProgress['has_program'])
                                    <?php
                                        $reportDays = $reportProgress['days'] ?? [];
                                        $dayCount = count($reportDays);
                                        $daysPerPage = max(1, (int) ($reportProgress['days_per_page'] ?? 8));
                                        $pageCount = max(1, (int) ceil(max($dayCount, 1) / $daysPerPage));
                                        $gridColumns = min(max($dayCount, 1), $daysPerPage);
                                    ?>

                                    <div
                                        x-data="{ page: 0, total: {{ $pageCount }}, perPage: {{ $daysPerPage }}, days: {{ $dayCount }} }"
                                        class="space-y-3">
                                        @if($reportProgress['show_day_pagination'])
                                            <div class="flex items-center justify-between gap-2 rounded-xl bg-white/[0.03] ring-1 ring-white/10 px-2.5 py-2">
                                                <button type="button"
                                                        @click="page = Math.max(page - 1, 0)"
                                                        :disabled="page === 0"
                                                        class="px-3 py-1.5 rounded-lg text-[11px] font-bold bg-white/5 text-neutral-300 ring-1 ring-white/10 transition disabled:opacity-35 disabled:cursor-not-allowed">
                                                    عقب
                                                </button>
                                                <div class="text-[11px] font-semibold text-neutral-400">
                                                    <span x-text="Math.min(page * perPage + 1, days)"></span>
                                                    تا
                                                    <span x-text="Math.min((page + 1) * perPage, days)"></span>
                                                    از
                                                    <span>{{ $dayCount }}</span>
                                                    روز
                                                </div>
                                                <button type="button"
                                                        @click="page = Math.min(page + 1, total - 1)"
                                                        :disabled="page >= total - 1"
                                                        class="px-3 py-1.5 rounded-lg text-[11px] font-bold bg-white/5 text-neutral-300 ring-1 ring-white/10 transition disabled:opacity-35 disabled:cursor-not-allowed">
                                                    جلو
                                                </button>
                                            </div>
                                        @endif

                                        <div class="grid items-center gap-1.5 sm:gap-2"
                                             style="grid-template-columns: repeat({{ $gridColumns }}, minmax(0, 1fr));">
                                            @foreach($reportDays as $idx => $day)
                                                <div
                                                    @if($reportProgress['show_day_pagination']) x-show="page === {{ intdiv($idx, $daysPerPage) }}" @endif
                                                    title="{{ $day['day_name'] }} {{ $day['jalali_day'] }}"
                                                    class="aspect-square rounded-full border-2 flex items-center justify-center font-bold text-[12px] transition {{ $day['status_class'] }}">
                                                    {{ $day['jalali_day'] }}
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-6 text-neutral-500 text-[13px]">هنوز برنامه‌ای برایت ثبت
                                        نشده است.
                                    </div>
                                @endif
                            </div>

                            {{-- ══════ 3) ساعت مطالعه (زنده) ══════ --}}
                            <div class="glass rise p-4" data-tour="study" style="animation-delay:.2s">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="live-dot"></span>
                                        <span class="font-bold text-white text-[15px]">ساعت مطالعه من</span>
                                    </div>
                                    <div class="text-xl font-black text-sky-400">
                                        {{ $studyHoursProgress['total_hours'] }}
                                    </div>
                                </div>
                                <p class="text-[11px] text-neutral-400 mb-4">{{ $dashboardPeriod['study_hint'] }}</p>

                                <div class="w-full rounded-full h-[6px] bg-white/10 mb-3 overflow-hidden">
                                    <div
                                        class="h-full rounded-full bg-gradient-to-l from-emerald-400 to-sky-500 transition-all duration-700"
                                        style="width: {{ $studyHoursProgress['percentage'] }}%;"></div>
                                </div>
                                <div class="flex items-center justify-between text-[13px]">
                                    <div class="text-neutral-400">{{ round($studyHoursProgress['percentage']) }}%</div>
                                    <div class="text-sky-400">{{ $studyHoursProgress['completed_hours'] }}
                                        از {{ $studyHoursProgress['total_hours'] }}</div>
                                </div>
                            </div>
<br>
                            <a wire:navigate href="{{ route('client.profile.sample-questions') }}"
                               class="glass card-live rise md:col-span-2 p-4 flex items-center justify-between gap-4"
                               style="animation-delay:.14s;margin-top:-20px;margin-bottom: 24px">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-300 flex items-center justify-center flex-shrink-0 ring-1 ring-sky-500/20">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 4.5h7.25L19.5 9.25V19.5a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2v-13a2 2 0 0 1 2-2Z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.5 4.75V9.5h4.75M8.5 13h7M8.5 16h5"/>
                                        </svg>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-black text-white text-base mb-1">نمونه سوالات امتحانی</div>
                                        <div class="text-xs text-neutral-400 leading-6">نمونه سوالات تشریحی را بر اساس کتاب، نوبت امتحانی و سال ببین و دانلود کن.</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-primary font-bold text-sm flex-shrink-0">
                                    <span>مشاهده نمونه سوالات</span>
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </div>
                            </a>
                            {{-- ══════ 3.5) اضافه بر سازمان (فقط در صورت وجود) ══════ --}}
                            @if($extraOrgProgress['has_extra'])
                                <div class="glass rise p-4" style="animation-delay:.22s">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="flex items-center gap-2">
                                            <span class="live-dot"></span>
                                            <span class="font-bold text-white text-[15px]">اضافه بر سازمان</span>
                                        </div>
                                        <div class="text-xl font-black text-emerald-400" style="direction:ltr;">
                                            {{ $extraOrgProgress['hours'] }}
                                        </div>
                                    </div>
                                    <p class="text-[11px] text-neutral-400 mb-3">{{ $dashboardPeriod['extra_hint'] }}</p>
                                    <div
                                        class="text-xs font-semibold px-3 py-2 rounded-xl inline-flex items-center gap-1.5 bg-emerald-500/10 text-emerald-300 ring-1 ring-emerald-500/20">
                                        ↑ {{ $extraOrgProgress['hours'] }} اضافه بر سازمان! عالی پیش می‌روی
                                    </div>
                                </div>
                            @endif

                            {{-- ══════ 4) برنامه امروز (زنده، full-width) ══════ --}}
                            <div class="glass card-live relative rounded-2xl rise md:col-span-2 p-4" data-tour="today"
                                 style="animation-delay:.25s">
                                <div class="flex items-center justify-between gap-2 mb-1">

                                    <div class="flex items-center gap-2">
                                        <span class="live-dot"></span>
                                        <span class="font-bold text-white text-[15px]">برنامه امروز من </span>
                                    </div>
                                    {{-- ★ data-tour اضافه شد --}}
                                    <button type="button"
                                            data-tour="sudden-event"
                                            @click="Livewire.dispatch('open-sudden-event')"
                                            class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-primary ring-1 ring-white/10 text-neutral-300 hover:bg-white/10 hover:text-white transition cursor-pointer">
                                        اتفاقات یهویی !!
                                    </button>
                                </div>
                                <p class="text-[11px] text-neutral-400 mb-4">درس‌ها و تکالیفی که امروز باید انجام
                                    بدهی</p>

                                @if(count($todayProgram) > 0)
                                    {{-- موبایل: carousel با peek و pagination فعال --}}
                                    <div class="md:hidden"
                                         x-data="{
                active: 0,
                total: {{ count($todayProgram) }},
                onScroll(e) {
                    const el = e.target;
                    const cards = el.querySelectorAll('.program-card');
                    if (!cards.length) return;
                    const center = el.scrollLeft + el.clientWidth / 2;
                    let closest = 0;
                    let minDist = Infinity;
                    cards.forEach((c, i) => {
                        const cardCenter = c.offsetLeft + c.offsetWidth / 2;
                        const dist = Math.abs(cardCenter - center);
                        if (dist < minDist) { minDist = dist; closest = i; }
                    });
                    this.active = closest;
                },
                goTo(i) {
                    const el = this.$refs.carousel;
                    const card = el.querySelectorAll('.program-card')[i];
                    if (card) {
                        card.scrollIntoView({ behavior: 'smooth', inline: 'start', block: 'nearest' });
                    }
                }
             }">
                                        <div class="program-carousel -mx-4 px-4"
                                             x-ref="carousel"
                                             @scroll.passive="onScroll($event)">
                                            @foreach($todayProgram as $part)
                                                <?php
                                                    $minutes = $part->duration_minutes ?? round(($part->duration_hours ?? 0) * 60);
                                                    $testsCount = $part->tests_count ?? $part->test_count ?? 0;
                                                ?>
                                                <div
                                                    class="program-card rounded-2xl bg-white/5 ring-1 ring-white/10 px-4 py-3 flex items-center justify-between gap-3 min-h-[72px]">
                                                    <div
                                                        class="font-bold text-white text-[15px] leading-tight text-right">
                                                        {{ $part->lesson_name ?? ($part->lesson->name ?? 'درس') }}
                                                    </div>
                                                    <div class="flex items-center gap-4" style="direction:ltr;">
                                                        @if($minutes > 0)
                                                            <div class="flex flex-col items-center leading-tight">
                                                        <span
                                                            class="font-black text-white text-base">{{ $minutes }}</span>
                                                                <span
                                                                    class="text-[10px] text-neutral-400 mt-0.5">دقیقه</span>
                                                            </div>
                                                        @endif
                                                        @if($testsCount > 0)
                                                            <div class="flex flex-col items-center leading-tight">
                                                        <span
                                                            class="font-black text-white text-base">{{ $testsCount }}</span>
                                                                <span
                                                                    class="text-[10px] text-neutral-400 mt-0.5">تست</span>
                                                            </div>
                                                        @endif
                                                        @if($minutes == 0 && $testsCount == 0)
                                                            <span class="text-xs text-neutral-500">—</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="flex-shrink-0 w-4"></div>
                                        </div>

                                        {{-- نقطه‌های ناوبری فعال --}}
                                        <div class="flex items-center justify-center gap-1.5 mt-3">
                                            @foreach($todayProgram as $idx => $p)
                                                <button type="button"
                                                        @click="goTo({{ $idx }})"
                                                        :class="active === {{ $idx }} ? 'bg-sky-400 w-4' : 'bg-white/20 w-1.5'"
                                                        class="h-1.5 rounded-full transition-all duration-300"></button>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- دسکتاپ: scroll افقی --}}
                                    <div class="hidden md:block relative">
                                        <div
                                            class="absolute left-0 top-0 bottom-0 w-12 bg-gradient-to-l from-transparent to-[#0a0f1a]/70 z-10 pointer-events-none rounded-l-xl"></div>
                                        <div class="program-scroll-desktop">
                                            @foreach($todayProgram as $part)
                                                <?php
                                                    $minutes = $part->duration_minutes ?? round(($part->duration_hours ?? 0) * 60);
                                                    $testsCount = $part->tests_count ?? $part->test_count ?? 0;
                                                ?>
                                                <div
                                                    class="flex-shrink-0 rounded-2xl bg-white/5 ring-1 ring-white/10 px-4 py-3 flex items-center justify-between gap-4"
                                                    style="min-width:220px; min-height:72px;">
                                                    <div
                                                        class="font-bold text-white text-[15px] leading-tight text-right">
                                                        {{ $part->lesson_name ?? ($part->lesson->name ?? 'درس') }}
                                                    </div>
                                                    <div class="flex items-center gap-4" style="direction:ltr;">
                                                        @if($minutes > 0)
                                                            <div class="flex flex-col items-center leading-tight">
                                                        <span
                                                            class="font-black text-white text-base">{{ $minutes }}</span>
                                                                <span
                                                                    class="text-[10px] text-neutral-400 mt-0.5">دقیقه</span>
                                                            </div>
                                                        @endif
                                                        @if($testsCount > 0)
                                                            <div class="flex flex-col items-center leading-tight">
                                                        <span
                                                            class="font-black text-white text-base">{{ $testsCount }}</span>
                                                                <span
                                                                    class="text-[10px] text-neutral-400 mt-0.5">تست</span>
                                                            </div>
                                                        @endif
                                                        @if($minutes == 0 && $testsCount == 0)
                                                            <span class="text-xs text-neutral-500">—</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-8 text-neutral-500 text-[13px]">برنامه‌ای برای امروز
                                        تعریف نشده
                                    </div>
                                @endif

                                {{-- ══════ تحلیل زندهٔ برنامهٔ امروز (۲-۳ خط) ══════ --}}
                                @if(!empty($todayAnalysis) && !empty($todayAnalysis['lines']))
                                    <div
                                        class="relative z-10 mt-4 rounded-xl bg-white/[0.03] ring-1 ring-white/10 px-4 py-3">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-sky-400" fill="none" viewBox="0 0 24 24"
                                                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                 stroke-linejoin="round">
                                                <path
                                                    d="M12 2a7 7 0 0 0-4 12.7V17a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2v-2.3A7 7 0 0 0 12 2zM9 21h6"/>
                                            </svg>
                                            <span class="text-[13px] font-bold text-white">تحلیل امروزِ تو</span>
                                        </div>
                                        <div class="space-y-1.5">
                                            @foreach($todayAnalysis['lines'] as $line)
                                                <p class="text-[12px] leading-6 text-neutral-300">{{ $line }}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- ══════ باکس امتحان / پرسش و پاسخ / تکلیف هفته ══════ --}}
                            @unless($isGraduateStudent || ($dashboardPeriod['is_exam_program'] ?? false))
                                <div class="glass rise md:col-span-2 p-4"
                                     style="animation-delay:.28s;margin-bottom: 36px">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-white text-[15px]">مدرسه من</span>
                                            <span
                                                class="text-[10px] px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-300 ring-1 ring-amber-500/20">{{ $dashboardPeriod['short_label'] }}</span>
                                        </div>
                                        {{-- ★ data-tour اضافه شد --}}
                                        <a wire:navigate
                                           href="{{ route('client.profile.consultation.class-schedule') }}"
                                           data-tour="class-schedule"
                                           class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-amber-500/15 ring-1 ring-amber-500/30 text-amber-300 hover:bg-amber-500/25 hover:text-amber-200 transition flex items-center gap-1.5">
                                            برنامه کلاسی مدرسه
                                        </a>
                                    </div>

                                    <?php
                                        $sourceLabels = [
                                            'exam'     => ['label' => 'امتحان',              'color' => 'text-red-400',    'bg' => 'bg-red-500/10',    'ring' => 'ring-red-500/25'],
                                            'class_qa' => ['label' => 'پرسش و پاسخ کلاسی', 'color' => 'text-sky-400',    'bg' => 'bg-sky-500/10',    'ring' => 'ring-sky-500/25'],
                                            'homework' => ['label' => 'تکلیف',               'color' => 'text-amber-400',  'bg' => 'bg-amber-500/10',  'ring' => 'ring-amber-500/25'],
                                        ];
                                        $weekDayNames = ['شنبه','یکشنبه','دوشنبه','سه‌شنبه','چهارشنبه','پنج‌شنبه','جمعه'];
                                    ?>

                                    @if(!empty($weeklySpecialParts))
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            @foreach($weeklySpecialParts as $sp)
                                                <?php
                                                    $src = $sp['source_type'] ?? 'exam';
                                                    $meta = $sourceLabels[$src] ?? $sourceLabels['exam'];
                                                    $lessonName = $sp['lesson_name'] ?? ($sp['cc_subject']['name'] ?? ($sp['lesson']['name'] ?? 'درس'));
                                                    $dayIndex = $sp['day_of_week'] ?? null;
                                                    $dayName = $sp['period_label'] ?? ($dayIndex !== null ? ($weekDayNames[$dayIndex] ?? '') : '');
                                                    $minutes = $sp['duration_minutes'] ?? 0;
                                                    $tests   = $sp['test_count'] ?? 0;
                                                    $isSummary = !empty($sp['is_summary']);
                                                    $partsCount = (int) ($sp['parts_count'] ?? 0);
                                                ?>
                                                <div
                                                    class="rounded-xl bg-white/5 ring-1 ring-white/10 px-4 py-3 flex items-center justify-between gap-3">
                                                    <div class="flex flex-col gap-1">
                                                <span
                                                    class="font-bold text-white text-[14px] leading-tight">{{ $lessonName }}
                                                  <span
                                                      class="text-[11px] font-semibold px-2 py-0.5 rounded-full  {{ $meta['color'] }} ring-1 {{ $meta['ring'] }}">
                                                {{ $meta['label'] }}
                                            </span>
                                                </span>

                                                    </div>
                                                    <div class="flex items-center gap-2 flex-shrink-0">
                                                        <div class="flex flex-col items-center leading-tight text-center">
                                                            @if($isSummary)
                                                                <span class="font-black text-lg {{ $meta['color'] }}">{{ $partsCount }}</span>
                                                                <span class="text-[10px] text-neutral-400 mt-0.5">پارت</span>
                                                            @else
                                                                <span class="font-black text-sm {{ $meta['color'] }}">{{ $dayName }}</span>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-8 text-neutral-500 text-[13px]">دیتایی وجود ندارد
                                        </div>
                                    @endif
                                </div>
                            @endunless

                            {{-- ══════ آمار کلی هفته (محاسبات) ══════ --}}
                            <?php
                                $fmtHm = function ($seconds) {
                                    $seconds = max(0, (int) $seconds);
                                    $h = intdiv($seconds, 3600);
                                    $m = intdiv($seconds % 3600, 60);
                                    if ($h > 0 && $m > 0) return $h . ' ساعت ' . $m . ' دقیقه';
                                    if ($h > 0) return $h . ' ساعت';
                                    return $m . ' دقیقه';
                                };
                                $wi = $weeklyInsights;
                                $dailyChartDays = count($wi['daily'] ?? []);
                                $dailyChartPerPage = 8;
                                $dailyChartPageCount = max(1, (int) ceil(max($dailyChartDays, 1) / $dailyChartPerPage));
                                $showDailyChartPagination = (bool) ($wi['is_exam_program'] ?? false) && $dailyChartDays > $dailyChartPerPage;
                            ?>

                            {{-- ══════ 5) نمودار مطالعه روزانه این هفته (تحلیلی) ══════ --}}
                            <div class="glass card-data rise p-4"
                                 data-tour="chart"
                                 style="animation-delay:.3s"
                                 x-data="{ page: 0, total: {{ $dailyChartPageCount }} }">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <div class="flex items-center gap-2">
                                        <div class="icon-chip">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="#7dd3fc" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75z"/>
                                            </svg>
                                        </div>
                                        <span class="font-bold text-white text-[15px]">{{ $dashboardPeriod['daily_chart_title'] }}</span>
                                        <span
                                            class="text-[10px] px-2 py-0.5 rounded-full bg-sky-500/10 text-sky-300 ring-1 ring-sky-500/20">تحلیل</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] text-neutral-400">
                                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-sm"
                                                                                   style="background:#3b82f6"></span> برنامه</span>
                                        <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-sm"
                                                                                            style="background:#10b981"></span> مطالعه</span>
                                    </div>
                                </div>
                                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <p class="text-[11px] text-neutral-400">{{ $dashboardPeriod['daily_chart_hint'] }}</p>
                                    @if($showDailyChartPagination)
                                        <div class="flex items-center justify-between gap-2 rounded-xl bg-white/5 p-1 ring-1 ring-white/10 sm:justify-end"
                                             dir="rtl">
                                            <button type="button"
                                                    class="rounded-lg px-3 py-1.5 text-[11px] font-bold text-white transition disabled:cursor-not-allowed disabled:opacity-40 hover:bg-white/10"
                                                    :disabled="page <= 0"
                                                    @click="page = Math.max(0, page - 1); window.__dashSetDailyChartPage?.(page)">
                                                قبلی
                                            </button>
                                            <span class="min-w-20 text-center text-[11px] font-semibold text-neutral-300">
                                                صفحه <span x-text="page + 1"></span> از {{ $dailyChartPageCount }}
                                            </span>
                                            <button type="button"
                                                    class="rounded-lg px-3 py-1.5 text-[11px] font-bold text-white transition disabled:cursor-not-allowed disabled:opacity-40 hover:bg-white/10"
                                                    :disabled="page >= total - 1"
                                                    @click="page = Math.min(total - 1, page + 1); window.__dashSetDailyChartPage?.(page)">
                                                بعدی
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                @if($wi['has_data'])
                                    <div class="relative" style="height:170px">
                                        <canvas id="dash-chart-daily"></canvas>
                                    </div>
                                @else
                                    <div class="text-center py-10 text-neutral-500 text-[13px]">داده‌ای برای نمایش
                                        نیست.
                                    </div>
                                @endif
                            </div>

                            {{-- ══════ 6) پیشرفت دروس این هفته (تحلیلی) ══════ --}}
                            <div class="glass card-data rise md:col-span-1 p-4" style="animation-delay:.35s">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <div class="flex items-center gap-2">
                                        <div class="icon-chip">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="#7dd3fc" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M3.75 3v11.25A2.25 2.25 0 006 16.5h12M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5"/>
                                            </svg>
                                        </div>
                                        <span class="font-bold text-white text-[15px]">{{ $dashboardPeriod['subject_progress_title'] }}</span>
                                    </div>
                                    <span
                                        class="text-[10px] px-2 py-0.5 rounded-full bg-sky-500/10 text-sky-300 ring-1 ring-sky-500/20">تحلیل</span>
                                </div>
                                <p class="text-[11px] text-neutral-400 mb-4">درصد پیشرفت تو در هر درس بر اساس پارت‌های
                                    انجام‌شده</p>

                                @if($wi['has_data'] && !empty($wi['subjects']))
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                                        @foreach($wi['subjects'] as $subj)
                                            <?php
                                                $pct = $subj['percent'];
                                                $barColor = $pct >= 100 ? 'bg-green-500' : ($pct >= 70 ? 'bg-emerald-500' : ($pct >= 40 ? 'bg-amber-500' : ($pct > 0 ? 'bg-orange-500' : 'bg-red-500')));
                                                $txtColor = $pct >= 70 ? 'text-emerald-400' : ($pct >= 40 ? 'text-amber-400' : ($pct > 0 ? 'text-orange-400' : 'text-red-400'));
                                            ?>
                                            <div>
                                                <div class="flex items-center justify-between mb-1.5">
                                            <span
                                                class="text-[13px] font-semibold text-white truncate">{{ $subj['name'] }}</span>
                                                    <span class="text-[11px] text-neutral-400 whitespace-nowrap">{{ $subj['parts_studied'] }}/{{ $subj['parts_total'] }} پارت · <span
                                                            class="{{ $txtColor }} font-bold">{{ $pct }}%</span></span>
                                                </div>
                                                <div class="w-full h-2 rounded-full bg-white/10 overflow-hidden">
                                                    <div
                                                        class="h-full {{ $barColor }} rounded-full transition-all duration-700"
                                                        style="width: {{ min(100, $pct) }}%"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-8 text-neutral-500 text-[13px]">درسی برای نمایش پیشرفت
                                        وجود
                                        ندارد.
                                    </div>
                                @endif
                            </div>
                        </div>{{-- end grid --}}
                    </div>{{-- end main --}}
                </div>{{-- end flex --}}
            </div>
            {{-- end container --}}
            {{-- ════════════════ MODAL: ADVISOR INFO ════════════════ --}}
            <div x-show="openAdvisorModal"
                 class="fixed inset-0 z-[80] flex items-end md:items-center justify-center p-0 md:p-4"
                 style="display: none;"
                 role="dialog"
                 aria-modal="true">

                {{-- پس‌زمینه تاریک و مات کننده پشت مودال --}}
                <div x-show="openAdvisorModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="openAdvisorModal = false"
                     class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>

                {{-- باکس اصلی مودال --}}
                <div x-show="openAdvisorModal"
                     {{-- انیمیشن ورود از پایین در موبایل و بزرگ شدن در دسکتاپ --}}
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="translate-y-full md:translate-y-0 md:scale-95 md:opacity-0"
                     x-transition:enter-end="translate-y-0 md:scale-100 md:opacity-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="translate-y-0 md:scale-100 md:opacity-100"
                     x-transition:leave-end="translate-y-full md:translate-y-0 md:scale-95 md:opacity-0"
                     class="glass w-full md:max-w-md rounded-t-3xl md:rounded-2xl p-6 relative z-10 overflow-hidden max-h-[85vh] md:max-h-none overflow-y-auto">

                    {{-- دستگیره بالای مودال مخصوص موبایل --}}
                    <div class="w-12 h-1 bg-white/20 rounded-full mx-auto mb-4 md:hidden"
                         @click="openAdvisorModal = false"></div>

                    {{-- هدر مودال و دکمه بستن در دسکتاپ --}}
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-base font-bold text-white flex items-center gap-2">
                            <span class="live-dot"></span>
                            اطلاعات مشاور متخصص
                        </h3>
                        <button @click="openAdvisorModal = false"
                                class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center transition cursor-pointer text-neutral-400 hover:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                 stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- محتوای درون مودال (اطلاعات مشاور) --}}
                    <div class="text-center md:text-right space-y-4">
                        <div class="flex flex-col md:flex-row items-center gap-4 border-b border-white/10 pb-4">
                            @if($advisorStudent && !empty($advisorStudent['picture']))
                                <img
                                    src="{{ asset('adminsFile/' . $advisorStudent['id'] . '/' . $advisorStudent['picture']) }}"
                                    alt="{{ $advisorStudent['name'] }}"
                                    class="w-20 h-20 rounded-full object-cover ring-4 ring-sky-500/30 flex-shrink-0">
                            @else
                                <div
                                    class="w-20 h-20 rounded-full bg-white/5 ring-4 ring-white/10 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5"
                                         stroke="#94a3b8" class="w-10 h-10">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="text-center md:text-right">
                                <h4 class="text-lg font-black text-white">{{ $advisorStudent['name'] ?? 'تعیین نشده' }}</h4>
                            </div>
                        </div>

                        {{-- فیلدهای جزئیات بیشتر --}}
                        <div class="space-y-3 text-sm text-neutral-300">
                            <div class="bg-white/5 p-3 rounded-xl flex justify-between items-center">
                                <span class="text-neutral-400">نام و نام خانوادگی:</span>
                                <span class="font-semibold text-white">{{ $advisorStudent['name'] ?? '—' }}</span>
                            </div>
                            <div class="bg-white/5 p-3 rounded-xl flex justify-between items-center">
                                <span class="text-neutral-400">تحصیلات:</span>
                                <span class="font-semibold text-white">{{ $advisorStudent['education'] ?? '—' }}</span>
                            </div>
                            <div class="bg-white/5 p-3 rounded-xl text-right leading-relaxed">
                                <span class="text-neutral-400 block mb-1 text-xs">خلاصه معرفی:</span>
                                <p class="text-sm leading-7">{{ $advisorStudent['bio'] ?? '—' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- دکمه اکشن پایین مودال --}}
                    <div class="mt-6">
                        <button @click="openAdvisorModal = false"
                                class="w-full py-3 rounded-xl bg-sky-500 hover:bg-sky-600 text-white font-bold text-sm transition shadow-lg shadow-sky-500/20 cursor-pointer">
                            متوجه شدم
                        </button>
                    </div>
                </div>
            </div>

            {{-- ════════════════ مودال اتفاقات یهویی ════════════════ --}}
            <livewire:client.profile.sudden-event-modal/>

            @script
            <script>
                (function () {
                    const palette = ['#3b82f6', '#10b981', '#f59e0b', '#0ea5e9', '#ec4899', '#14b8a6', '#f97316', '#06b6d4', '#22d3ee', '#84cc16'];
                    window.__dashChartData = {
                        daily: @json($weeklyInsights['daily'] ?? []),
                        partType: @json($weeklyInsights['part_type_distribution'] ?? (object)[]),
                        monthly: @json($monthlyInsights['weeks'] ?? []),
                    };
                    window.__dashCharts = window.__dashCharts || {};
                    window.__dashDailyChartPage = 0;
                    window.__dashDailyChartPerPage = 8;
                    window.__dashDailyChartPaginate = @json((bool) (($weeklyInsights['is_exam_program'] ?? false) && count($weeklyInsights['daily'] ?? []) > 8));

                    function destroy(id) {
                        if (window.__dashCharts[id]) {
                            try {
                                window.__dashCharts[id].destroy();
                            } catch (e) {
                            }
                            delete window.__dashCharts[id];
                        }
                    }

                    function gridOpts() {
                        return {
                            x: {ticks: {color: '#a3a3a3', font: {size: 10, family: 'inherit'}}, grid: {display: false}},
                            y: {
                                beginAtZero: true,
                                ticks: {color: '#a3a3a3', font: {size: 10}},
                                grid: {color: 'rgba(148,163,184,.12)'}
                            }
                        };
                    }

                    function initDaily() {
                        const el = document.getElementById('dash-chart-daily');
                        if (!el || typeof Chart === 'undefined') return;
                        destroy('daily');
                        const allDays = window.__dashChartData.daily || [];
                        const pageCount = Math.max(1, Math.ceil(allDays.length / window.__dashDailyChartPerPage));
                        window.__dashDailyChartPage = Math.min(Math.max(0, window.__dashDailyChartPage || 0), pageCount - 1);
                        const d = window.__dashDailyChartPaginate
                            ? allDays.slice(
                                window.__dashDailyChartPage * window.__dashDailyChartPerPage,
                                (window.__dashDailyChartPage + 1) * window.__dashDailyChartPerPage
                            )
                            : allDays;
                        if (!d.length) return;
                        window.__dashCharts['daily'] = new Chart(el, {
                            type: 'bar',
                            data: {
                                labels: d.map(x => x.chart_label || x.label),
                                datasets: [
                                    {
                                        label: 'برنامه',
                                        data: d.map(x => x.planned_hours),
                                        backgroundColor: '#3b82f6',
                                        borderRadius: 5,
                                        maxBarThickness: 18
                                    },
                                    {
                                        label: 'مطالعه',
                                        data: d.map(x => x.studied_hours),
                                        backgroundColor: '#10b981',
                                        borderRadius: 5,
                                        maxBarThickness: 18
                                    },
                                ]
                            },
                            options: {
                                responsive: true, maintainAspectRatio: false,
                                plugins: {
                                    legend: {display: false},
                                    tooltip: {callbacks: {label: (c) => c.dataset.label + ': ' + c.parsed.y + ' ساعت'}}
                                },
                                scales: gridOpts()
                            }
                        });
                    }

                    window.__dashSetDailyChartPage = function (page) {
                        window.__dashDailyChartPage = Math.max(0, Number(page) || 0);
                        initDaily();
                    };

                    function initPartType() {
                        const el = document.getElementById('dash-chart-parttype');
                        if (!el || typeof Chart === 'undefined') return;
                        destroy('parttype');
                        const obj = window.__dashChartData.partType || {};
                        const labels = Object.keys(obj);
                        const values = Object.values(obj);
                        if (!labels.length) return;
                        window.__dashCharts['parttype'] = new Chart(el, {
                            type: 'doughnut',
                            data: {
                                labels: labels,
                                datasets: [{
                                    data: values,
                                    backgroundColor: labels.map((_, i) => palette[i % palette.length]),
                                    borderWidth: 2,
                                    borderColor: '#0a0f1a'
                                }]
                            },
                            options: {
                                responsive: true, maintainAspectRatio: false, cutout: '60%',
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {color: '#d4d4d4', font: {size: 10}, boxWidth: 10, padding: 8}
                                    }
                                }
                            }
                        });
                    }

                    function initMonthly() {
                        const el = document.getElementById('dash-chart-monthly');
                        if (!el || typeof Chart === 'undefined') return;
                        destroy('monthly');
                        const d = window.__dashChartData.monthly || [];
                        if (!d.length) return;
                        window.__dashCharts['monthly'] = new Chart(el, {
                            type: 'bar',
                            data: {
                                labels: d.map(x => x.label + ' (' + x.date + ')'),
                                datasets: [
                                    {
                                        label: 'برنامه',
                                        data: d.map(x => x.planned_hours),
                                        backgroundColor: '#0ea5e9',
                                        borderRadius: 6,
                                        maxBarThickness: 34
                                    },
                                    {
                                        label: 'مطالعه',
                                        data: d.map(x => x.done_hours),
                                        backgroundColor: '#f59e0b',
                                        borderRadius: 6,
                                        maxBarThickness: 34
                                    },
                                ]
                            },
                            options: {
                                responsive: true, maintainAspectRatio: false,
                                plugins: {
                                    legend: {display: false},
                                    tooltip: {callbacks: {label: (c) => c.dataset.label + ': ' + c.parsed.y + ' ساعت'}}
                                },
                                scales: gridOpts()
                            }
                        });
                    }

                    function initAll() {
                        if (!document.getElementById('dash-chart-daily') &&
                            !document.getElementById('dash-chart-parttype') &&
                            !document.getElementById('dash-chart-monthly')) return;
                        initDaily();
                        initPartType();
                        initMonthly();
                    }

                    window.__dashInitCharts = initAll;

                    function boot() {
                        if (typeof Chart === 'undefined') {
                            setTimeout(boot, 60);
                            return;
                        }
                        initAll();
                        setTimeout(() => {
                            initAll();
                        }, 180);
                    }

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', boot);
                    } else {
                        boot();
                    }

                    document.addEventListener('livewire:initialized', () => {
                        requestAnimationFrame(() => requestAnimationFrame(() => window.__dashInitCharts && window.__dashInitCharts()));
                    });

                    document.addEventListener('livewire:navigated', () => {
                        requestAnimationFrame(() => requestAnimationFrame(() => window.__dashInitCharts && window.__dashInitCharts()));
                    });

                    document.addEventListener('livewire:update', () => {
                        requestAnimationFrame(() => window.__dashInitCharts && window.__dashInitCharts());
                    });
                })();
            </script>
            @endscript

        </div>
    </div>
</div>
