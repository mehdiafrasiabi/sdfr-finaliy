<div class="min-h-screen text-white" dir="rtl" style="font-family: inherit;" x-data="{ openAdvisorModal: false }">
    <livewire:client.profile.update-notification />


    {{-- ════════ تور راهنمای داشبورد (اولین ورود + آیکون راهنما) ════════ --}}
    <x-client.page-tour storage-key="dashboard_tour_done" :steps="[
        ['el' => '[data-tour=nav-consultation]', 'title' => 'اتاق مشاوره',       'text' => 'از اینجا می‌تونی وارد اتاق مشاوره بشی و با مشاورت ارتباط بگیری.',                                                                                        'forced' => true],
        ['el' => '[data-tour=nav-plan]',         'title' => 'برنامه درسی',       'text' => 'برنامه مطالعه درسیت رو اینجا می‌بینی و اجرا می‌کنی.',                                                                                                       'forced' => true],
        ['el' => '[data-tour=nav-logo]',         'title' => 'داشبورد',           'text' => 'با لمس لوگو وسط، هر جا باشی سریع به داشبورد اصلی برمی‌گردی.',                                                                                               'forced' => true],
        ['el' => '[data-tour=nav-report]',       'title' => 'گزارش روزانه',      'text' => 'گزارش مطالعه امروزت رو از همین‌جا ثبت کن.',                                                                                                                  'forced' => true],
        ['el' => '[data-tour=nav-exam]',         'title' => 'آزمون',             'text' => 'آزمون‌های تستی و تشریحیت رو از این بخش شروع کن.',                                                                                                           'forced' => true],
        ['el' => '[data-tour=sudden-event]',     'title' => 'اتفاقات یهویی',    'text' => 'اگه یه اتفاق غیرمنتظره پیش اومد (مثل بیماری یا امتحان یهویی)، از این دکمه ثبت کن تا برنامه‌ات تنظیم بشه.',                                                     'forced' => true],
        ['el' => '[data-tour=class-schedule]',   'title' => 'برنامه کلاسی مدرسه','text' => 'برنامه هفتگی مدرسه‌ات رو از اینجا وارد کن تا با برنامه مطالعه‌ات هماهنگ باشه.',                                                                   'forced' => true],
    ]"
    />

    {{-- ════════════════════════════════════════════════════════════
         پس‌زمینه کیهانی + سفینه (دکوراتیو، خارج از کنترل Livewire)
         wire:ignore → هرگز re-render نمی‌شه و انیمیشن‌ها قطع نمی‌شن
    ════════════════════════════════════════════════════════════ --}}
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
        <svg class="spaceship" viewBox="0 0 80 120" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
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
            z-index: -1;
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
            z-index: -1;
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
    </style>
    @endassets

    {{-- ════════ فرمت‌کنندهٔ زمان: ثانیه → «X ساعت و Y دقیقه» ════════
         در سراسر این ویو استفاده می‌شود تا به‌جای ساعت اعشاری (مثل ۳.۱)،
         زمان دقیق نمایش داده شود (مثل «۳ ساعت و ۶ دقیقه»).
    ════════════════════════════════════════════════════════════ --}}
    @php
        $fmtHm = function ($seconds) {
            $seconds = max(0, (int) round($seconds));
            $h = intdiv($seconds, 3600);
            $m = intdiv($seconds % 3600, 60);
            if ($h > 0 && $m > 0) return $h . ' ساعت و ' . $m . ' دقیقه';
            if ($h > 0)           return $h . ' ساعت';
            if ($m > 0)           return $m . ' دقیقه';
            return '۰ دقیقه';
        };
        // دقیقه → همان خروجی
        $fmtMin = fn ($minutes) => $fmtHm(((int) round($minutes)) * 60);
    @endphp

    <div class="max-w-7xl mx-auto px-4 py-6 relative z-10">
        <div class="flex gap-6 items-start">

            {{-- ===== SIDEBAR (دسکتاپ) ===== --}}
            <div class="hidden md:block flex-shrink-0 w-[260px] sticky top-6">
                <livewire:client.profile.sidebar/>
            </div>

            {{-- ===== MAIN CONTENT ===== --}}
            <div class="flex-1 min-w-0">

                {{-- ─── Banners ─── --}}
                @php
                    $trialWeek = \App\Models\TrialWeek::where('user_id', $user->id)->latest()->first();
                    $isTrialStudent = $student && $student->is_trial;
                    $showTrialBanner = !$student || $isTrialStudent;
                @endphp
                <div class="space-y-3 mb-5">
                    @if($showTrialBanner && !$trialWeek)
                        <div class="glass rise rounded-2xl p-4" style="animation-delay:0s">
                            <div class="flex items-center justify-between gap-3">
                                <livewire:client.profile.trial-week.start/>
                                <div class="text-right">
                                    <div class="font-bold text-white text-sm">یک هفته آزمایشی رایگان</div>
                                    <div class="text-xs mt-1 text-green-400/80">برنامه شخصی • پشتیبان اختصاصی</div>
                                </div>
                            </div>
                        </div>
                    @elseif($trialWeek)
                        <a wire:navigate href="{{ route('client.profile.trial.guide') }}" data-tour="trial"
                           class="glass rise flex items-center justify-between p-4 rounded-2xl"
                           style="animation-delay:0s">
                            <div class="flex items-center gap-2">
                                @php $sp = ($trialWeek->step / 4) * 100; @endphp
                                <div class="w-14 h-1 rounded-full overflow-hidden bg-green-900/50">
                                    <div class="h-full rounded-full bg-green-400" style="width:{{ $sp }}%;"></div>
                                </div>
                                <span class="text-xs text-green-400">{{ (int)$sp }}%</span>
                            </div>
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
                        </a>
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M22 9 12 5 2 9l10 4 10-4v6"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6 10.6V16a6 3 0 0 0 12 0v-5.4"/>
                            </svg>
                            <span class="font-bold text-sm">مدرسه: {{ $schoolInfo['name'] }}</span>
                        </div>
                        @if($schoolInfo['manager'] || $schoolInfo['phone'])
                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-foreground/80 pr-7">
                                @if($schoolInfo['manager'])
                                    <div>مدیر مدرسه: <span class="font-semibold">{{ $schoolInfo['manager'] }}</span>
                                    </div>
                                @endif
                                @if($schoolInfo['phone'])
                                    <div>تلفن: <span class="font-semibold" dir="ltr">{{ $schoolInfo['phone'] }}</span>
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
                                <span class="font-bold text-[11px] text-primary">
                                    مشاور شما:
                                </span>

                                <div class="font-bold text-white text-base leading-tight">

                                    {{ $advisorStudent['name'] ?? 'تعیین نشده' }}
                                </div>
                            </div>
                        </div>
                        {{-- چپ: نقطه زنده + دکمه فلش --}}
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <span class="live-dot" title="در دسترس"></span>
                            <button @click="openAdvisorModal = true"
                                    class="w-9 h-9 rounded-full bg-white/5 ring-1 ring-white/10 flex items-center justify-center hover:bg-white/10 transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                     stroke="#cbd5e1" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- ══════ 2) ارسال گزارش (زنده) ══════ --}}
                    <div class="glass rise p-4" data-tour="report" style="animation-delay:.15s">
                        {{-- هدر --}}
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="live-dot"></span>
                                <span class="font-bold text-white text-[15px]">وضعیت گزارش روزانه من</span>

                            </div>
                            <div class="text-2xl font-black text-sky-400 tracking-tight" style="direction:ltr;">
                                @if($reportProgress['has_program'])
                                    {{ $reportProgress['submitted_days'] }}/{{ $reportProgress['total_days'] }}
                                @else
                                    <span class="text-base text-neutral-500" style="direction:rtl;">وجود ندارد</span>
                                @endif
                            </div>
                        </div>
                        <p class="text-[11px] text-neutral-400 mb-4">تعداد روزهایی که این هفته گزارش روزانه ثبت
                            کرده‌ای</p>

                        @if($reportProgress['has_program'])
                            {{-- روزهای هفته (بر اساس بازهٔ جلسهٔ مشاوره) --}}
                            @php
                                $activeProgram = $this->getActiveWeeklyProgram();
                                $startDate = $reportProgress['start_date']
                                    ? \Carbon\Carbon::parse($reportProgress['start_date'])
                                    : \Carbon\Carbon::parse($activeProgram->start_date);
                                $endDate = $startDate->copy()->addDays(6);
                                $today = \Carbon\Carbon::today();

                                $submittedDates = \App\Models\DailyReport::where('student_id', $student->id)
                                    ->where('weekly_program_id', $activeProgram->id)
                                    ->where('is_compensatory', false)
                                    ->whereBetween('report_date', [$startDate, $endDate])
                                    ->pluck('report_date')
                                    ->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString())
                                    ->toArray();

                                // روزهای بدون پارت = روز استراحت
                                $restDayIndices = [];
                                for ($ri = 0; $ri < 7; $ri++) {
                                    $hasParts = $activeProgram->parts()->where('day_of_week', $ri)->exists();
                                    if (!$hasParts) $restDayIndices[] = $ri;
                                }
                            @endphp

                            <div class="flex items-center gap-1.5">
                                @for($i = 0; $i < 7; $i++)
                                    @php
                                        $currentDate  = $startDate->copy()->addDays($i);
                                        $dayNum       = jdate($currentDate)->format('j');
                                        $isToday      = $currentDate->isSameDay($today);
                                        $isSubmitted  = in_array($currentDate->toDateString(), $submittedDates);
                                        $isRestDay    = in_array($i, $restDayIndices);
                                        $isPast       = $currentDate->lt($today);

                                        if ($isRestDay) {
                                            $cls = 'bg-green-600/80 border-green-500 text-white';
                                        } elseif ($isSubmitted) {
                                            $cls = 'bg-sky-500 border-sky-400 text-white';
                                        } elseif ($isToday && !$isSubmitted) {
                                            $cls = 'bg-red-700/80 border-red-600 text-white';
                                        } elseif ($isPast && !$isSubmitted) {
                                            $cls = 'bg-red-950/60 border-red-800 text-red-300/80';
                                        } else {
                                            $cls = 'bg-white/5 border-white/10 text-neutral-500';
                                        }
                                    @endphp
                                    <div
                                        class="flex-1 aspect-square rounded-full border-2 flex items-center justify-center font-bold text-[12px] transition {{ $cls }}">
                                        {{ $dayNum }}
                                    </div>
                                @endfor
                            </div>
                        @else
                            <div class="text-center py-6 text-neutral-500 text-[13px]">هنوز برنامه‌ای برایت ثبت نشده است.</div>
                        @endif
                    </div>

                    {{-- ══════ 3) ساعت مطالعه (زنده) ══════ --}}
                    <div class="glass rise p-4" data-tour="study" style="animation-delay:.2s">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="live-dot"></span>
                                <span class="font-bold text-white text-[15px]">ساعت مطالعه من</span>
                            </div>
                            <div class="text-base font-black text-sky-400 whitespace-nowrap">
                                {{ $fmtMin($studyHoursProgress['total_minutes'] ?? 0) }}
                            </div>
                        </div>
                        <p class="text-[11px] text-neutral-400 mb-4">مجموع ساعت مطالعه‌ات نسبت به هدف این هفته</p>

                        <div class="w-full rounded-full h-[6px] bg-white/10 mb-3 overflow-hidden">
                            <div
                                class="h-full rounded-full bg-gradient-to-l from-emerald-400 to-sky-500 transition-all duration-700"
                                style="width: {{ $studyHoursProgress['percentage'] }}%;"></div>
                        </div>
                        <div class="flex items-center justify-between text-[13px]">
                            <div class="text-neutral-400">{{ round($studyHoursProgress['percentage']) }}%</div>
                            <div class="text-sky-400">{{ $fmtMin($studyHoursProgress['completed_minutes'] ?? 0) }}
                                از {{ $fmtMin($studyHoursProgress['total_minutes'] ?? 0) }}</div>
                        </div>
                    </div>

                    {{-- ══════ 3.5) اضافه بر سازمان (فقط در صورت وجود) ══════ --}}
                    @if($extraOrgProgress['has_extra'])
                        <div class="glass rise p-4" style="animation-delay:.22s">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <span class="live-dot"></span>
                                    <span class="font-bold text-white text-[15px]">اضافه بر سازمان</span>
                                </div>
                                <div class="text-base font-black text-emerald-400 whitespace-nowrap">
                                    {{ $fmtHm($extraOrgProgress['total_seconds'] ?? 0) }}
                                </div>
                            </div>
                            <p class="text-[11px] text-neutral-400 mb-3">میزان مطالعهٔ اضافه بر سازمان که این هفته ثبت کرده‌ای</p>
                            <div
                                class="text-xs font-semibold px-3 py-2 rounded-xl inline-flex items-center gap-1.5 bg-emerald-500/10 text-emerald-300 ring-1 ring-emerald-500/20">
                                ↑ {{ $fmtHm($extraOrgProgress['total_seconds'] ?? 0) }} اضافه بر سازمان! عالی پیش می‌روی
                            </div>
                        </div>
                    @endif

                    {{-- ══════ 4) برنامه امروز (زنده، full-width) ══════ --}}
                    <div class="glass rise md:col-span-2 p-4" data-tour="today" style="animation-delay:.25s">
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
                        <p class="text-[11px] text-neutral-400 mb-4">درس‌ها و تکالیفی که امروز باید انجام بدهی</p>

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
                                        @php
                                            $minutes = $part->duration_minutes ?? round(($part->duration_hours ?? 0) * 60);
                                            $testsCount = $part->tests_count ?? $part->test_count ?? 0;
                                        @endphp
                                        <div
                                            class="program-card rounded-2xl bg-white/5 ring-1 ring-white/10 px-4 py-3 flex items-center justify-between gap-3 min-h-[72px]">
                                            <div class="font-bold text-white text-[15px] leading-tight text-right">
                                                {{ $part->lesson_name ?? ($part->lesson->name ?? 'درس') }}
                                            </div>
                                            <div class="flex items-center gap-4" style="direction:ltr;">
                                                @if($minutes > 0)
                                                    <div class="flex flex-col items-center leading-tight">
                                                        <span
                                                            class="font-black text-white text-base">{{ $minutes }}</span>
                                                        <span class="text-[10px] text-neutral-400 mt-0.5">دقیقه</span>
                                                    </div>
                                                @endif
                                                @if($testsCount > 0)
                                                    <div class="flex flex-col items-center leading-tight">
                                                        <span
                                                            class="font-black text-white text-base">{{ $testsCount }}</span>
                                                        <span class="text-[10px] text-neutral-400 mt-0.5">تست</span>
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
                                        @php
                                            $minutes = $part->duration_minutes ?? round(($part->duration_hours ?? 0) * 60);
                                            $testsCount = $part->tests_count ?? $part->test_count ?? 0;
                                        @endphp
                                        <div
                                            class="flex-shrink-0 rounded-2xl bg-white/5 ring-1 ring-white/10 px-4 py-3 flex items-center justify-between gap-4"
                                            style="min-width:220px; min-height:72px;">
                                            <div class="font-bold text-white text-[15px] leading-tight text-right">
                                                {{ $part->lesson_name ?? ($part->lesson->name ?? 'درس') }}
                                            </div>
                                            <div class="flex items-center gap-4" style="direction:ltr;">
                                                @if($minutes > 0)
                                                    <div class="flex flex-col items-center leading-tight">
                                                        <span
                                                            class="font-black text-white text-base">{{ $minutes }}</span>
                                                        <span class="text-[10px] text-neutral-400 mt-0.5">دقیقه</span>
                                                    </div>
                                                @endif
                                                @if($testsCount > 0)
                                                    <div class="flex flex-col items-center leading-tight">
                                                        <span
                                                            class="font-black text-white text-base">{{ $testsCount }}</span>
                                                        <span class="text-[10px] text-neutral-400 mt-0.5">تست</span>
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
                            <div class="text-center py-8 text-neutral-500 text-[13px]">برنامه‌ای برای امروز تعریف نشده
                            </div>
                        @endif
                    </div>

                    {{-- ══════ باکس امتحان / پرسش و پاسخ / تکلیف هفته ══════ --}}
                    @unless($isGraduateStudent)
                        <div class="glass rise md:col-span-2 p-4" style="animation-delay:.28s;margin-bottom: 36px">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-white text-[15px]">مدرسه من</span>
                                    <span
                                        class="text-[10px] px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-300 ring-1 ring-amber-500/20">این هفته</span>
                                </div>
                                {{-- ★ data-tour اضافه شد --}}
                                <a wire:navigate href="{{ route('client.profile.consultation.class-schedule') }}"
                                   data-tour="class-schedule"
                                   class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-amber-500/15 ring-1 ring-amber-500/30 text-amber-300 hover:bg-amber-500/25 hover:text-amber-200 transition flex items-center gap-1.5">
                                    برنامه کلاسی مدرسه
                                </a>
                            </div>

                            @php
                                $sourceLabels = [
                                    'exam'     => ['label' => 'امتحان',              'color' => 'text-red-400',    'bg' => 'bg-red-500/10',    'ring' => 'ring-red-500/25'],
                                    'class_qa' => ['label' => 'پرسش و پاسخ کلاسی', 'color' => 'text-sky-400',    'bg' => 'bg-sky-500/10',    'ring' => 'ring-sky-500/25'],
                                    'homework' => ['label' => 'تکلیف',               'color' => 'text-amber-400',  'bg' => 'bg-amber-500/10',  'ring' => 'ring-amber-500/25'],
                                ];
                                $weekDayNames = ['شنبه','یکشنبه','دوشنبه','سه‌شنبه','چهارشنبه','پنج‌شنبه','جمعه'];
                            @endphp

                            @if(!empty($weeklySpecialParts))
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($weeklySpecialParts as $sp)
                                        @php
                                            $src = $sp['source_type'] ?? 'exam';
                                            $meta = $sourceLabels[$src] ?? $sourceLabels['exam'];
                                            $lessonName = $sp['lesson_name'] ?? ($sp['cc_subject']['name'] ?? ($sp['lesson']['name'] ?? 'درس'));
                                            $dayName = $weekDayNames[$sp['day_of_week']] ?? '';
                                            $minutes = $sp['duration_minutes'] ?? 0;
                                            $tests   = $sp['test_count'] ?? 0;
                                        @endphp
                                        <div
                                            class="rounded-xl bg-white/5 ring-1 ring-white/10 px-4 py-3 flex items-center justify-between gap-3">
                                            <div class="flex flex-col gap-1">
                                                <span
                                                    class="font-bold text-white text-[14px] leading-tight">{{ $lessonName }}

                                                </span>
                                                @if($dayName)
                                                    <span class="text-[11px] text-neutral-500">{{ $dayName }}</span>
                                                @endif

                                            </div>
                                            <div class="flex items-center gap-2 flex-shrink-0">

                                        <span
                                            class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $meta['bg'] }} {{ $meta['color'] }} ring-1 {{ $meta['ring'] }}">
                                                {{ $meta['label'] }}
                                            </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-neutral-500 text-[13px]">دیتایی وجود ندارد</div>
                            @endif
                        </div>
                    @endunless

                    {{-- ══════ آمار کلی هفته (محاسبات) ══════ --}}
                    @php
                        $wi = $weeklyInsights;
                    @endphp

                    {{-- ══════ 5) نمودار مطالعه روزانه این هفته (تحلیلی) ══════ --}}
                    <div class="glass card-data rise p-4" data-tour="chart" style="animation-delay:.3s">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <div class="flex items-center gap-2">
                                <div class="icon-chip">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="#7dd3fc" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75z"/>
                                    </svg>
                                </div>
                                <span class="font-bold text-white text-[15px]">مطالعه روزانه</span>
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
                        <p class="text-[11px] text-neutral-400 mb-4">ساعت برنامه‌ریزی‌شده در برابر ساعت واقعی مطالعه در
                            هر روز هفته</p>

                        @if($wi['has_data'])
                            <div class="relative" style="height:170px">
                                <canvas id="dash-chart-daily"></canvas>
                            </div>
                        @else
                            <div class="text-center py-10 text-neutral-500 text-[13px]">داده‌ای برای نمایش نیست.</div>
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
                                <span class="font-bold text-white text-[15px]">پیشرفت دروس این هفته</span>
                            </div>
                            <span
                                class="text-[10px] px-2 py-0.5 rounded-full bg-sky-500/10 text-sky-300 ring-1 ring-sky-500/20">تحلیل</span>
                        </div>
                        <p class="text-[11px] text-neutral-400 mb-4">درصد پیشرفت تو در هر درس بر اساس پارت‌های
                            انجام‌شده</p>

                        @if($wi['has_data'] && !empty($wi['subjects']))
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                                @foreach($wi['subjects'] as $subj)
                                    @php
                                        $pct = $subj['percent'];
                                        $barColor = $pct >= 100 ? 'bg-green-500' : ($pct >= 70 ? 'bg-emerald-500' : ($pct >= 40 ? 'bg-amber-500' : ($pct > 0 ? 'bg-orange-500' : 'bg-red-500')));
                                        $txtColor = $pct >= 70 ? 'text-emerald-400' : ($pct >= 40 ? 'text-amber-400' : ($pct > 0 ? 'text-orange-400' : 'text-red-400'));
                                    @endphp
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span
                                                class="text-[13px] font-semibold text-white truncate">{{ $subj['name'] }}</span>
                                            <span class="text-[11px] text-neutral-400 whitespace-nowrap">{{ $subj['parts_studied'] }}/{{ $subj['parts_total'] }} پارت · <span
                                                    class="{{ $txtColor }} font-bold">{{ $pct }}%</span></span>
                                        </div>
                                        <div class="w-full h-2 rounded-full bg-white/10 overflow-hidden">
                                            <div class="h-full {{ $barColor }} rounded-full transition-all duration-700"
                                                 style="width: {{ min(100, $pct) }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-neutral-500 text-[13px]">درسی برای نمایش پیشرفت وجود
                                ندارد.
                            </div>
                        @endif
                    </div>
                </div>{{-- end grid --}}
            </div>{{-- end main --}}
        </div>{{-- end flex --}}
    </div>{{-- end container --}}
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
                        <img src="{{ asset('adminsFile/' . $advisorStudent['id'] . '/' . $advisorStudent['picture']) }}"
                             alt="{{ $advisorStudent['name'] }}"
                             class="w-20 h-20 rounded-full object-cover ring-4 ring-sky-500/30 flex-shrink-0">
                    @else
                        <div
                            class="w-20 h-20 rounded-full bg-white/5 ring-4 ring-white/10 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
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

    {{-- ════════════════ CHART INITIALIZATION ════════════════ --}}
    @push('script')
        <script>
            (function () {
                // پالت بدون بنفش (هماهنگ با تم emerald/sky)
                const palette = ['#3b82f6', '#10b981', '#f59e0b', '#0ea5e9', '#ec4899', '#14b8a6', '#f97316', '#06b6d4', '#22d3ee', '#84cc16'];

                window.__dashChartData = {
                    daily: @json($weeklyInsights['daily'] ?? []),
                    partType: @json($weeklyInsights['part_type_distribution'] ?? (object)[]),
                    monthly: @json($monthlyInsights['weeks'] ?? []),
                };
                window.__dashCharts = window.__dashCharts || {};

                function destroy(id) {
                    if (window.__dashCharts[id]) {
                        try { window.__dashCharts[id].destroy(); } catch (e) {}
                        delete window.__dashCharts[id];
                    }
                }

                function gridOpts() {
                    return {
                        x: { ticks: { color: '#a3a3a3', font: { size: 10, family: 'inherit' } }, grid: { display: false } },
                        y: { beginAtZero: true, ticks: { color: '#a3a3a3', font: { size: 10 } }, grid: { color: 'rgba(148,163,184,.12)' } }
                    };
                }

                function initDaily() {
                    const el = document.getElementById('dash-chart-daily');
                    if (!el || typeof Chart === 'undefined') return;
                    destroy('daily');
                    const d = window.__dashChartData.daily || [];
                    if (!d.length) return;
                    window.__dashCharts['daily'] = new Chart(el, {
                        type: 'bar',
                        data: {
                            labels: d.map(x => x.label),
                            datasets: [
                                { label: 'برنامه', data: d.map(x => x.planned_hours), backgroundColor: '#3b82f6', borderRadius: 5, maxBarThickness: 18 },
                                { label: 'مطالعه', data: d.map(x => x.studied_hours), backgroundColor: '#10b981', borderRadius: 5, maxBarThickness: 18 },
                            ]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: { legend: { display: false }, tooltip: { callbacks: { label: (c) => c.dataset.label + ': ' + c.parsed.y + ' ساعت' } } },
                            scales: gridOpts()
                        }
                    });
                }

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
                        data: { labels: labels, datasets: [{ data: values, backgroundColor: labels.map((_, i) => palette[i % palette.length]), borderWidth: 2, borderColor: '#0a0f1a' }] },
                        options: {
                            responsive: true, maintainAspectRatio: false, cutout: '60%',
                            plugins: { legend: { position: 'bottom', labels: { color: '#d4d4d4', font: { size: 10 }, boxWidth: 10, padding: 8 } } }
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
                                { label: 'برنامه', data: d.map(x => x.planned_hours), backgroundColor: '#0ea5e9', borderRadius: 6, maxBarThickness: 34 },
                                { label: 'مطالعه', data: d.map(x => x.done_hours), backgroundColor: '#f59e0b', borderRadius: 6, maxBarThickness: 34 },
                            ]
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: { legend: { display: false }, tooltip: { callbacks: { label: (c) => c.dataset.label + ': ' + c.parsed.y + ' ساعت' } } },
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
                    if (typeof Chart === 'undefined') { setTimeout(boot, 60); return; }
                    initAll();
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', boot);
                } else {
                    boot();
                }

                document.addEventListener('livewire:navigated', () => {
                    requestAnimationFrame(() => requestAnimationFrame(() => window.__dashInitCharts && window.__dashInitCharts()));
                });

                document.addEventListener('livewire:update', () => {
                    requestAnimationFrame(() => window.__dashInitCharts && window.__dashInitCharts());
                });
            })();
        </script>
    @endpush
</div>
