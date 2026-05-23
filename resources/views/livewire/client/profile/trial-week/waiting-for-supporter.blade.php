{{--
    ═══════════════════════════════════════════════════════════════════
    Waiting For Supporter — Redesign (Portal-grade)
    ───────────────────────────────────────────────────────────────────
    • باکس ۱: انتظار پشتیبان + SVG با انیمیشن قطره‌ی آب (ripple) + رفرش
    • وضعیت ساعت کاری → فقط یک خط ظریف (هستیم/نیستیم)، بدون باکس
    • باکس ۲: ویدیوی معرفی + پنل کناری هماهنگ
    • مودال ویدیو bottom-sheet موبایل
    • دکمه‌ی لغو ظریف، با حس press
    ═══════════════════════════════════════════════════════════════════
--}}

<div class="max-w-5xl mx-auto px-4 py-6 sm:py-10" dir="rtl"
     x-data="waitingPage()" x-init="init()">

    <style>
        [x-cloak] { display: none !important; }

        /* ════ دکمه‌ی فشاری ════ */
        .press {
            transition: transform 0.09s ease, box-shadow 0.09s ease, background-color 0.15s ease;
        }
        .press:active { transform: translateY(3px); }

        .btn-refresh { box-shadow: 0 4px 0 0 hsl(var(--primary) / 0.35); }
        .btn-refresh:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 5px 0 0 hsl(var(--primary) / 0.4); }
        .btn-refresh:active:not(:disabled) { transform: translateY(4px); box-shadow: 0 0 0 0 hsl(var(--primary) / 0.35); }
        .btn-refresh:disabled { opacity: 0.65; cursor: wait; }

        @keyframes spin-once { to { transform: rotate(360deg); } }
        .spin-active { animation: spin-once 0.8s cubic-bezier(0.4, 0, 0.2, 1); }


        /* ════ انیمیشن قطره‌ی آب (ripple) ════ */
        @keyframes droplet {
            0%   { transform: scale(0.4); opacity: 0; }
            15%  { opacity: 0.7; }
            100% { transform: scale(2.6); opacity: 0; }
        }
        .droplet {
            position: absolute;
            inset: 0;
            border-radius: 9999px;
            border: 2px solid hsl(var(--primary) / 0.5);
            animation: droplet 3.4s cubic-bezier(0, 0.5, 0.5, 1) infinite;
        }
        .droplet.d2 { animation-delay: 1.13s; }
        .droplet.d3 { animation-delay: 2.26s; }

        /* حلقه‌ی داخلی پر-رنگ‌تر */
        @keyframes droplet-inner {
            0%   { transform: scale(0.55); opacity: 0; }
            20%  { opacity: 0.9; }
            100% { transform: scale(1.7); opacity: 0; }
        }
        .droplet-inner {
            position: absolute;
            inset: 14%;
            border-radius: 9999px;
            border: 1.5px solid hsl(var(--primary) / 0.35);
            animation: droplet-inner 3.4s cubic-bezier(0, 0.5, 0.5, 1) infinite;
        }
        .droplet-inner.d2 { animation-delay: 1.7s; }

        /* ════ شناور شدن نرم SVG مرکزی ════ */
        @keyframes bob {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-5px); }
        }
        .bob { animation: bob 4s ease-in-out infinite; }

        /* ════ ورود مرحله‌ای ════ */
        @keyframes rise {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .rise { animation: rise 0.55s cubic-bezier(0.16, 1, 0.3, 1) backwards; }
        .rise.r1 { animation-delay: 0.05s; }
        .rise.r2 { animation-delay: 0.14s; }
        .rise.r3 { animation-delay: 0.23s; }

        /* ════ نوار پیشرفت نازک ـ جریان ════ */
        @keyframes flow {
            from { background-position: 0 0; }
            to   { background-position: 22px 0; }
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
        .press:active { transform: translateY(3px); }

        .btn-refresh { box-shadow: 0 4px 0 0 hsl(var(--primary) / 0.35); }
        .btn-refresh:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 5px 0 0 hsl(var(--primary) / 0.4); }
        .btn-refresh:active:not(:disabled) { transform: translateY(4px); box-shadow: 0 0 0 0 hsl(var(--primary) / 0.35); }
        .btn-refresh:disabled { opacity: 0.65; cursor: wait; }

        @keyframes spin-once { to { transform: rotate(360deg); } }
        .spin-active { animation: spin-once 0.8s cubic-bezier(0.4, 0, 0.2, 1); }

        /* نقطه‌ی زنده (ساعت کاری) */
        @keyframes live-ping {
            0%   { transform: scale(1); opacity: 0.7; }
            100% { transform: scale(2.6); opacity: 0; }
        }
        .live-dot { position: relative; }
        .live-dot::after {
            content: '';
            position: absolute; inset: 0;
            border-radius: 9999px;
            background: currentColor;
            animation: live-ping 2s ease-out infinite;
        }

        /* دکمه‌ی play پالس */
        @keyframes play-pulse {
            0%, 100% { box-shadow: 0 0 0 0 hsl(var(--primary) / 0.45); }
            50%      { box-shadow: 0 0 0 16px hsl(var(--primary) / 0); }
        }
        .play-pulse { animation: play-pulse 2.6s ease infinite; }

        /* ════ مودال ویدیو ════ */
        .v-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.72);
            backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
            z-index: 90;
        }
        .v-sheet {
            position: fixed; left: 0; right: 0; bottom: 0;
            background: hsl(var(--background));
            border-top: 1px solid hsl(var(--border));
            border-radius: 28px 28px 0 0;
            z-index: 100;
            max-height: 92dvh;
            display: flex; flex-direction: column;
            padding-bottom: env(safe-area-inset-bottom, 0);
            box-shadow: 0 -20px 60px rgba(0,0,0,0.35);
        }
        @media (min-width: 768px) {
            .v-sheet {
                left: 50%; top: 50%; bottom: auto; right: auto;
                transform: translate(-50%, -50%);
                width: 90%; max-width: 760px;
                border-radius: 24px;
                border: 1px solid hsl(var(--border));
                max-height: 88dvh;
            }
        }
        .v-handle {
            width: 44px; height: 5px;
            background: hsl(var(--muted-foreground) / 0.35);
            border-radius: 999px; margin: 10px auto 4px;
        }
        @media (min-width: 768px) { .v-handle { display: none; } }

        @keyframes ov-in { from { opacity: 0; } to { opacity: 1; } }
        @keyframes sheet-up { from { transform: translateY(100%); } to { transform: translateY(0); } }
        @keyframes sheet-pop {
            from { opacity: 0; transform: translate(-50%, -45%) scale(0.96); }
            to   { opacity: 1; transform: translate(-50%, -50%) scale(1); }
        }
        .v-overlay { animation: ov-in 0.25s ease forwards; }
        .v-sheet   { animation: sheet-up 0.34s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @media (min-width: 768px) {
            .v-sheet { animation: sheet-pop 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
    </style>

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
                <span class="text-emerald-600 dark:text-emerald-400 font-bold">پشتیبانی آنلاین است</span>
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

    {{-- ═══════════════ باکس ۲ — ویدیوی معرفی ═══════════════ --}}
    <div class="rise r3 mt-5 grid grid-cols-1 md:grid-cols-5 gap-0 rounded-3xl border border-border bg-secondary overflow-hidden mb-3">

        {{-- سمت راست: thumbnail ویدیو --}}
        <button type="button" x-on:click="openVideo()"
                class="group relative md:col-span-3 h-52 md:h-auto min-h-[200px] overflow-hidden bg-gradient-to-br from-primary/25 via-primary/10 to-primary/5">
            {{-- الگوی تزئینی --}}
            <svg class="absolute inset-0 w-full h-full opacity-[0.18]" viewBox="0 0 300 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="60" cy="50" r="34" stroke="currentColor" stroke-width="2.5" class="text-primary"/>
                <circle cx="240" cy="150" r="46" stroke="currentColor" stroke-width="2.5" class="text-primary"/>
                <path d="M0 130 Q 75 100 150 120 T 300 105" stroke="currentColor" stroke-width="2.5" class="text-primary" fill="none"/>
                <rect x="180" y="30" width="40" height="40" rx="8" stroke="currentColor" stroke-width="2.5" class="text-primary"/>
            </svg>
            {{-- دکمه play --}}
            <span class="absolute inset-0 flex items-center justify-center">
                <span class="play-pulse flex items-center justify-center w-16 h-16 rounded-full bg-primary text-primary-foreground transition-transform group-hover:scale-110">
                    <svg class="w-7 h-7 mr-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </span>
            </span>
            {{-- مدت ویدیو (اختیاری) --}}
            <span class="absolute bottom-3 left-3 px-2 py-1 rounded-md bg-black/60 text-white text-[11px] font-bold backdrop-blur">
                ۰۳:۲۵
            </span>
        </button>

        {{-- سمت چپ: متن کنار ویدیو --}}
        <div class="md:col-span-2 p-6 flex flex-col justify-center gap-3 border-t md:border-t-0 md:border-r border-border">
            <div class="inline-flex items-center gap-1.5 self-start px-2.5 py-1 rounded-full bg-primary/10 text-primary text-[11px] font-bold">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/>
                </svg>
                ویدیوی معرفی
            </div>
            <h3 class="font-black text-foreground text-lg leading-7">
                تا تخصیص پشتیبان،<br>پنل رو یاد بگیر
            </h3>
            <p class="text-xs text-muted leading-6">
                در این ویدیوی کوتاه خودمون رو معرفی می‌کنیم و کار با بخش‌های پنل —
                برنامه‌ی هفتگی، گزارش روزانه و تایمر مطالعه — رو بهت آموزش می‌دیم.
            </p>
            <button type="button" x-on:click="openVideo()"
                    class="press inline-flex items-center justify-center gap-2 self-start px-4 py-2.5 mt-1 rounded-xl bg-primary/10 text-primary text-xs font-bold hover:bg-primary/15">
                تماشای ویدیو
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- ═══════════════ باکس ۱ — انتظار پشتیبان ═══════════════ --}}
    <div class="rise r2 relative overflow-hidden rounded-3xl border border-border bg-secondary">
        {{-- پس‌زمینه‌ی گرادینت ملایم --}}
        <div class="absolute inset-0 bg-gradient-to-b from-primary/[0.04] to-transparent pointer-events-none"></div>
        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-72 h-72 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>

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
                <div class="bob absolute inset-[22%] rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center shadow-xl shadow-primary/25">
                    {{-- آیکون: ذره‌بین روی کاربر (در حال پیدا کردن پشتیبان) --}}
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
            <h1 class="font-black text-2xl text-foreground mb-2">در انتظار پشتیبان</h1>
            <p class="text-sm text-muted leading-7 max-w-md mb-6">
                ثبت‌نامت با موفقیت انجام شد. تیم ما در حال انتخاب یک پشتیبان مناسب برای توست —
                به‌محض تخصیص، به‌صورت خودکار به مرحله‌ی بعد می‌ری.
            </p>

            {{-- نوار پیشرفت نازک --}}
            <div class="w-full max-w-sm mb-6">
                <div class="flex items-center justify-between text-[11px] mb-2">
                    <span class="font-bold text-emerald-600 dark:text-emerald-400">ثبت‌نام</span>
                    <span class="font-bold text-primary">تخصیص پشتیبان</span>
                    <span class="font-medium text-muted">شروع</span>
                </div>
                <div class="relative h-1.5 rounded-full bg-background overflow-hidden">
                    {{-- بخش کامل‌شده --}}
                    <div class="absolute inset-y-0 right-0 w-1/3 bg-emerald-500 rounded-full"></div>
                    {{-- بخش در حال انجام (متحرک) --}}
                    <div class="absolute inset-y-0 flow-bar rounded-full" style="right: 33.33%; width: 33.33%;"></div>
                </div>
            </div>

            {{-- دکمه‌ی رفرش --}}
            <button type="button"
                    wire:click="checkStatus"
                    wire:loading.attr="disabled"
                    wire:target="checkStatus"
                    x-on:click="spinRefresh()"
                    class="press btn-refresh inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-primary text-primary-foreground text-sm font-bold">
                <svg class="w-4 h-4" :class="{ 'spin-active': refreshing }"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 0 0 4.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 0 1-15.357-2m15.357 2H15"/>
                </svg>
                <span wire:loading.remove wire:target="checkStatus">بررسی وضعیت</span>
                <span wire:loading wire:target="checkStatus">در حال بررسی…</span>
            </button>

            {{-- پیام «هنوز نشده» --}}
            <div x-show="showNotYet" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mt-3 inline-flex items-center gap-1.5 text-[11px] text-muted">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5v5l3 1.7"/>
                </svg>
                هنوز پشتیبانی تخصیص داده نشده — کمی بعد دوباره بررسی کن.
            </div>

            {{-- اطلاعات ثبت‌شده (chip های ظریف) --}}
            @if($trialWeek)
                <div class="flex items-center justify-center gap-2 flex-wrap mt-7 pt-6 border-t border-border w-full">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-background border border-border text-xs">
                        <svg class="w-3.5 h-3.5 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.42A12 12 0 0 1 12 21a12 12 0 0 1-6.16-10.42L12 14z"/>
                        </svg>
                        <span class="text-muted">پایه:</span>
                        <span class="font-bold text-foreground">{{ $trialWeek->gradeLabel }}</span>
                    </span>
                    @if($trialWeek->grade != 9)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-background border border-border text-xs">
                            <svg class="w-3.5 h-3.5 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2"/>
                            </svg>
                            <span class="text-muted">رشته:</span>
                            <span class="font-bold text-foreground">{{ $trialWeek->fieldLabel }}</span>
                        </span>
                    @endif
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/25 text-xs">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <span class="font-bold text-amber-600 dark:text-amber-400">{{ $trialWeek->statusLabel }}</span>
                    </span>
                </div>
            @endif
        </div>
    </div>




    {{-- پیام خطا --}}
    @if (session('error'))
        <div class="mt-5 rounded-xl bg-red-50 dark:bg-red-900/10 text-red-700 dark:text-red-300 px-4 py-3 border border-red-200 dark:border-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif


    {{-- ═══════════════ دکمه‌ی لغو (ظریف، press) ═══════════════ --}}
    <div class="text-center mt-6">
        <button type="button"
                wire:click="cancelTrial"
                wire:confirm="آیا از لغو هفتهٔ آزمایشی و بازگشت به صفحهٔ خرید مطمئن هستید؟"
                class="press inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-semibold text-muted hover:text-red-600 hover:bg-red-500/5 transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
            لغو هفته آزمایشی و بازگشت به خرید
        </button>
    </div>


    {{-- ════════════════ مودال ویدیو ════════════════ --}}
    <template x-if="videoOpen">
        <div x-cloak>
            <div class="v-overlay" x-on:click="closeVideo()"></div>
            <div class="v-sheet" x-on:click.stop>
                <div class="v-handle"></div>

                <div class="shrink-0 px-5 py-4 border-b border-border flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary border border-primary/20 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-black text-base text-foreground">معرفی و آموزش پنل</h3>
                            <p class="text-[11px] text-muted">با ما و امکانات پنل آشنا شو</p>
                        </div>
                    </div>
                    <button type="button" x-on:click="closeVideo()"
                            class="w-9 h-9 rounded-xl hover:bg-muted text-muted hover:text-foreground transition flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-4 sm:p-5">
                    <div class="relative w-full rounded-xl overflow-hidden bg-black border border-border" style="aspect-ratio: 16 / 9;">
                        {{-- ⚠️ آدرس ویدیو را جایگزین کن (یا <iframe> آپارات) --}}
                        <video class="w-full h-full" controls playsinline preload="metadata"
                               poster="/client/assets/images/theme/video-poster.jpg">
                            <source src="/client/assets/videos/panel-intro.mp4" type="video/mp4">
                            مرورگر شما از پخش ویدیو پشتیبانی نمی‌کند.
                        </video>
                    </div>

                    <div class="mt-4 flex items-start gap-2.5 p-3 rounded-xl bg-secondary border border-border">
                        <svg class="w-5 h-5 text-primary shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4M12 8h.01"/>
                        </svg>
                        <p class="text-xs text-muted leading-relaxed">
                            در این ویدیو با تیم ما آشنا می‌شی و یاد می‌گیری چطور از بخش‌های مختلف پنل —
                            برنامه‌ی هفتگی، گزارش روزانه، تایمر مطالعه و کارنامه — استفاده کنی.
                        </p>
                    </div>
                </div>

                <div class="shrink-0 p-4 border-t border-border">
                    <button type="button" x-on:click="closeVideo()"
                            class="press w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-secondary border border-border text-foreground text-sm font-bold hover:bg-muted/50 transition-colors">
                        متوجه شدم، بستن
                    </button>
                </div>
            </div>
        </div>
    </template>


    {{-- ─── Alpine ─── --}}
    <script>
        function waitingPage() {
            return {
                refreshing: false,
                showNotYet: false,
                videoOpen: false,

                init() {
                    Livewire.on('status-checked', (e) => {
                        const assigned = Array.isArray(e) ? e[0]?.assigned : e?.assigned;
                        if (!assigned) {
                            this.showNotYet = true;
                            setTimeout(() => { this.showNotYet = false; }, 6000);
                        }
                    });
                },

                spinRefresh() {
                    this.refreshing = false;
                    requestAnimationFrame(() => {
                        this.refreshing = true;
                        setTimeout(() => { this.refreshing = false; }, 800);
                    });
                    if (navigator.vibrate) navigator.vibrate(10);
                },

                openVideo() {
                    this.videoOpen = true;
                    document.body.style.overflow = 'hidden';
                    if (navigator.vibrate) navigator.vibrate(8);
                },

                closeVideo() {
                    const v = document.querySelector('.v-sheet video');
                    if (v) { try { v.pause(); } catch (e) {} }
                    this.videoOpen = false;
                    document.body.style.overflow = '';
                },
            };
        }
    </script>
</div>
