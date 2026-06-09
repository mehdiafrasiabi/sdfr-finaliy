<div class="max-w-5xl mx-auto px-4 py-6 sm:py-10" dir="rtl"
     x-data="waitingPage()" x-init="init()">

    @push('link')
        <style>
            [x-cloak] {
                display: none !important;
            }

            /* ════ دکمه‌ی فشاری ════ */
            .press {
                transition: transform 0.09s ease, box-shadow 0.09s ease, background-color 0.15s ease;
            }

            .press:active {
                transform: translateY(3px);
            }

            .btn-refresh {
                box-shadow: 0 4px 0 0 hsl(var(--primary) / 0.35);
            }

            .btn-refresh:hover:not(:disabled) {
                transform: translateY(-1px);
                box-shadow: 0 5px 0 0 hsl(var(--primary) / 0.4);
            }

            .btn-refresh:active:not(:disabled) {
                transform: translateY(4px);
                box-shadow: 0 0 0 0 hsl(var(--primary) / 0.35);
            }

            .btn-refresh:disabled {
                opacity: 0.65;
                cursor: wait;
            }

            @keyframes spin-once {
                to {
                    transform: rotate(360deg);
                }
            }

            .spin-active {
                animation: spin-once 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            }


            /* ════ انیمیشن قطره‌ی آب (ripple) ════ */
            @keyframes droplet {
                0% {
                    transform: scale(0.4);
                    opacity: 0;
                }
                15% {
                    opacity: 0.7;
                }
                100% {
                    transform: scale(2.6);
                    opacity: 0;
                }
            }

            .droplet {
                position: absolute;
                inset: 0;
                border-radius: 9999px;
                border: 2px solid hsl(var(--primary) / 0.5);
                animation: droplet 3.4s cubic-bezier(0, 0.5, 0.5, 1) infinite;
            }

            .droplet.d2 {
                animation-delay: 1.13s;
            }

            .droplet.d3 {
                animation-delay: 2.26s;
            }

            /* حلقه‌ی داخلی پر-رنگ‌تر */
            @keyframes droplet-inner {
                0% {
                    transform: scale(0.55);
                    opacity: 0;
                }
                20% {
                    opacity: 0.9;
                }
                100% {
                    transform: scale(1.7);
                    opacity: 0;
                }
            }

            .droplet-inner {
                position: absolute;
                inset: 14%;
                border-radius: 9999px;
                border: 1.5px solid hsl(var(--primary) / 0.35);
                animation: droplet-inner 3.4s cubic-bezier(0, 0.5, 0.5, 1) infinite;
            }

            .droplet-inner.d2 {
                animation-delay: 1.7s;
            }

            /* ════ شناور شدن نرم SVG مرکزی ════ */
            @keyframes bob {
                0%, 100% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-5px);
                }
            }

            .bob {
                animation: bob 4s ease-in-out infinite;
            }

            /* ════ ورود مرحله‌ای ════ */
            @keyframes rise {
                from {
                    opacity: 0;
                    transform: translateY(18px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .rise {
                animation: rise 0.55s cubic-bezier(0.16, 1, 0.3, 1) backwards;
            }

            .rise.r1 {
                animation-delay: 0.05s;
            }

            .rise.r2 {
                animation-delay: 0.14s;
            }

            .rise.r3 {
                animation-delay: 0.23s;
            }

            /* ════ نوار پیشرفت نازک ـ جریان ════ */
            @keyframes flow {
                from {
                    background-position: 0 0;
                }
                to {
                    background-position: 22px 0;
                }
            }

            .flow-bar {
                background-image: repeating-linear-gradient(
                    -45deg,
                    hsl(var(--primary) / 0.55) 0 6px,
                    hsl(var(--primary) / 0.15) 6px 11px
                );
                background-size: 22px 22px;
                animation: flow 0.7s linear infinite;
            }

            /* ════ دکمه‌ی فشاری ════ */
            .press {
                transition: transform 0.09s ease, box-shadow 0.09s ease, background-color 0.15s ease;
            }

            .press:active {
                transform: translateY(3px);
            }

            .btn-refresh {
                box-shadow: 0 4px 0 0 hsl(var(--primary) / 0.35);
            }

            .btn-refresh:hover:not(:disabled) {
                transform: translateY(-1px);
                box-shadow: 0 5px 0 0 hsl(var(--primary) / 0.4);
            }

            .btn-refresh:active:not(:disabled) {
                transform: translateY(4px);
                box-shadow: 0 0 0 0 hsl(var(--primary) / 0.35);
            }

            .btn-refresh:disabled {
                opacity: 0.65;
                cursor: wait;
            }

            @keyframes spin-once {
                to {
                    transform: rotate(360deg);
                }
            }

            .spin-active {
                animation: spin-once 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* نقطه‌ی زنده (ساعت کاری) */
            @keyframes live-ping {
                0% {
                    transform: scale(1);
                    opacity: 0.7;
                }
                100% {
                    transform: scale(2.6);
                    opacity: 0;
                }
            }

            .live-dot {
                position: relative;
            }

            .live-dot::after {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: 9999px;
                background: currentColor;
                animation: live-ping 2s ease-out infinite;
            }

            /* دکمه‌ی play پالس */
            @keyframes play-pulse {
                0%, 100% {
                    box-shadow: 0 0 0 0 hsl(var(--primary) / 0.45);
                }
                50% {
                    box-shadow: 0 0 0 16px hsl(var(--primary) / 0);
                }
            }

            .play-pulse {
                animation: play-pulse 2.6s ease infinite;
            }

            /* ════ مودال ویدیو ════ */
            .v-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.72);
                backdrop-filter: blur(6px);
                -webkit-backdrop-filter: blur(6px);
                z-index: 90;
            }

            .v-sheet {
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                background: hsl(var(--background));
                border-top: 1px solid hsl(var(--border));
                border-radius: 28px 28px 0 0;
                z-index: 100;
                max-height: 92dvh;
                display: flex;
                flex-direction: column;
                padding-bottom: env(safe-area-inset-bottom, 0);
                box-shadow: 0 -20px 60px rgba(0, 0, 0, 0.35);
            }

            @media (min-width: 768px) {
                .v-sheet {
                    left: 50%;
                    top: 50%;
                    bottom: auto;
                    right: auto;
                    transform: translate(-50%, -50%);
                    width: 90%;
                    max-width: 760px;
                    border-radius: 24px;
                    border: 1px solid hsl(var(--border));
                    max-height: 88dvh;
                }
            }

            .v-handle {
                width: 44px;
                height: 5px;
                background: hsl(var(--muted-foreground) / 0.35);
                border-radius: 999px;
                margin: 10px auto 4px;
            }

            @media (min-width: 768px) {
                .v-handle {
                    display: none;
                }
            }

            @keyframes ov-in {
                from {
                    opacity: 0;
                }
                to {
                    opacity: 1;
                }
            }

            @keyframes sheet-up {
                from {
                    transform: translateY(100%);
                }
                to {
                    transform: translateY(0);
                }
            }

            @keyframes sheet-pop {
                from {
                    opacity: 0;
                    transform: translate(-50%, -45%) scale(0.96);
                }
                to {
                    opacity: 1;
                    transform: translate(-50%, -50%) scale(1);
                }
            }

            .v-overlay {
                animation: ov-in 0.25s ease forwards;
            }

            .v-sheet {
                animation: sheet-up 0.34s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }

            @media (min-width: 768px) {
                .v-sheet {
                    animation: sheet-pop 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                }
            }

            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after {
                    animation: none !important;
                    transition: none !important;
                }
            }
        </style>
    @endpush

    {{-- ═══════════ سرتیتر + وضعیت ساعت کاری (فقط یک خط ظریف) ═══════════ --}}
    <div class="rise r1 flex items-center justify-between gap-3 mb-5 flex-wrap">
        <div class="flex items-center gap-2.5">
            <div class="flex items-center gap-1">
                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                <div class="w-2 h-2 bg-foreground rounded-full"></div>
            </div>
            <span class="font-black text-foreground text-lg">هفته آزمایشی</span>
        </div>

        {{-- وضعیت ساعت کاری — فقط یک نشانگر ظریف --}}
        @if($isWithinWorkingHours)
            <div class="inline-flex items-center gap-2 text-xs">
                <span class="live-dot w-2 h-2 rounded-full bg-emerald-500 text-emerald-500"></span>
                <span class="text-emerald-600 dark:text-emerald-400 font-bold">مشاور ی آنلاین است</span>
                <span class="text-muted">·</span>
                <span class="text-muted">۹ صبح تا ۹ شب</span>
            </div>
        @else
            <div class="inline-flex items-center gap-2 text-xs">
                <span class="w-2 h-2 rounded-full bg-muted-foreground/40"></span>
                <span class="text-muted font-bold">خارج از ساعت کاری</span>
                <span class="text-muted">·</span>
                <span class="text-muted">رسیدگی {{ $nextActiveTime }}</span>
            </div>
        @endif
    </div>


    {{-- ═══════════════ باکس ۱ — انتظار مشاور  ═══════════════ --}}
    <div class="rise r2 relative overflow-hidden rounded-3xl border border-border bg-secondary">
        {{-- پس‌زمینه‌ی گرادینت ملایم --}}
        <div class="absolute inset-0 bg-gradient-to-b from-primary/[0.04] to-transparent pointer-events-none"></div>
        <div
            class="absolute -top-24 left-1/2 -translate-x-1/2 w-72 h-72 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative px-6 py-10 sm:py-12 flex flex-col items-center text-center">

            {{-- ─── SVG مرکزی با انیمیشن قطره‌ی آب ─── --}}
            <div class="relative w-36 h-36 mb-7">
                {{-- حلقه‌های قطره‌ی آب --}}
                <span class="droplet"></span>
                <span class="droplet d2"></span>
                <span class="droplet d3"></span>
                <span class="droplet-inner"></span>
                <span class="droplet-inner d2"></span>

                {{-- دایره‌ی مرکزی + SVG --}}
                <div
                    class="bob absolute inset-[22%] rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center shadow-xl shadow-primary/25">
                    {{-- آیکون: ذره‌بین روی کاربر (در حال پیدا کردن مشاور ) --}}
                    <svg class="w-10 h-10 text-primary-foreground" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="M21 21l-4.35-4.35"/>
                        <circle cx="11" cy="9" r="2.4"/>
                        <path d="M7.5 15.2a4 4 0 0 1 7 0"/>
                    </svg>
                </div>
            </div>

            {{-- عنوان و توضیح --}}
            <h1 class="font-black text-2xl text-foreground mb-2">در انتظار مشاور </h1>
            <p class="text-sm text-muted leading-7 max-w-md mb-6">
                <span class="font-bold text-primary">   ثبت‌نام شما با موفقیت انجام شد.</span>
                <br>
                سیستم هوشمند SDFR در حال آنالیز نتیجه تست شما و جستجوی بهترین مشاور اختصاصی برای شماست.   </p>

            {{-- نوار پیشرفت نازک --}}
            <div class="w-full max-w-sm mb-6">
                <div class="flex items-center justify-between text-[11px] mb-2">
                    <span class="font-bold text-emerald-600 dark:text-emerald-400">ثبت‌نام</span>
                    <span class="font-bold text-primary">تخصیص مشاور </span>
                    <span class="font-medium text-muted">شروع</span>
                </div>
                <div class="relative h-1.5 rounded-full bg-background overflow-hidden">
                    {{-- بخش کامل‌شده --}}
                    <div class="absolute inset-y-0 right-0 w-1/3 bg-emerald-500 rounded-full"></div>
                    {{-- بخش در حال انجام (متحرک) --}}
                    <div class="absolute inset-y-0 flow-bar rounded-full" style="right: 33.33%; width: 33.33%;"></div>
                </div>
            </div>

            {{-- پیام «هنوز نشده» --}}
            <div x-show="showNotYet" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mt-3 inline-flex items-center gap-1.5 text-[11px] text-muted">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5v5l3 1.7"/>
                </svg>
                هنوز مشاور ی تخصیص داده نشده — کمی بعد دوباره بررسی کن.
            </div>

            {{-- اطلاعات ثبت‌شده (chip های ظریف) --}}
            @if($trialWeek)
                <div class="flex items-center justify-center gap-2 flex-wrap  w-full">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-background border border-border text-xs">
                        <svg class="w-3.5 h-3.5 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 14l6.16-3.42A12 12 0 0 1 12 21a12 12 0 0 1-6.16-10.42L12 14z"/>
                        </svg>
                        <span class="text-muted">پایه:</span>
                        <span class="font-bold text-foreground">{{ $trialWeek->gradeLabel }}</span>
                    </span>
                    @if($trialWeek->grade != 9)
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-background border border-border text-xs">
                            <svg class="w-3.5 h-3.5 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2"/>
                            </svg>
                            <span class="text-muted">رشته:</span>
                            <span class="font-bold text-foreground">{{ $trialWeek->fieldLabel }}</span>
                        </span>
                    @endif
                </div>
            @endif

            {{-- ═══════════════ دکمه‌ی لغو (ظریف، press) ═══════════════ --}}
            <div class="text-center mt-6">
                <button type="button"
                        wire:click="cancelTrial"
                        wire:confirm="آیا از لغو هفتهٔ آزمایشی و بازگشت به صفحهٔ خرید مطمئن هستید؟"
                        class="press inline-flex items-center gap-1.5 px-4 py-2 dark:text-white rounded-lg text-xs font-semibold dark:bg-red-500 text-muted hover:text-red-600 hover:bg-red-500/5 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6L6 18M6 6l12 12"/>
                    </svg>
                    لغو هفته آزمایشی و بازگشت به خرید
                </button>
            </div>

        </div>
    </div>


    {{-- پیام خطا --}}
    @if (session('error'))
        <div
            class="mt-5 rounded-xl bg-red-50 dark:bg-red-900/10 text-red-700 dark:text-red-300 px-4 py-3 border border-red-200 dark:border-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif


</div>
