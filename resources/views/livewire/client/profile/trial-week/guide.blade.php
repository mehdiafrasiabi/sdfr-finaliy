<div class="max-w-5xl mx-auto px-4 py-6 sm:py-10" dir="rtl"
     x-data="guidePage()" x-init="init()">

    @push('link')
        <style>
            [x-cloak] { display: none !important; }

            /* ════ ورود مرحله‌ای ════ */
            @keyframes rise {
                from { opacity: 0; transform: translateY(20px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            .rise { animation: rise 0.5s cubic-bezier(0.16, 1, 0.3, 1) backwards; }
            .rise.r1 { animation-delay: 0.04s; }
            .rise.r2 { animation-delay: 0.10s; }
            .rise.r3 { animation-delay: 0.18s; }
            .rise.r4 { animation-delay: 0.26s; }
            .rise.r5 { animation-delay: 0.34s; }
            .rise.r6 { animation-delay: 0.42s; }

            /* ════ نوار پیشرفت ـ پر شدن ════ */
            @keyframes fill-bar { from { width: 0; } }
            .progress-fill { animation: fill-bar 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

            /* ════ شیمر روی نوار پیشرفت ════ */
            @keyframes shimmer {
                0%   { background-position: -200% 0; }
                100% { background-position: 200% 0; }
            }
            .progress-shimmer {
                background-image: linear-gradient(
                    90deg,
                    transparent 0%,
                    rgba(255,255,255,0.35) 50%,
                    transparent 100%
                );
                background-size: 200% 100%;
                animation: shimmer 2s linear infinite;
            }

            /* ════ pulse برای مرحله‌ی فعال ════ */
            @keyframes node-pulse {
                0%, 100% { box-shadow: 0 0 0 0 hsl(var(--primary) / 0.45); }
                50%      { box-shadow: 0 0 0 10px hsl(var(--primary) / 0); }
            }
            .node-active { animation: node-pulse 2.4s ease infinite; }

            /* نقطه‌ی زنده --}}*/
            @keyframes live-ping {
                0%   { transform: scale(1); opacity: 0.7; }
                100% { transform: scale(2.6); opacity: 0; }
            }
            .live-dot { position: relative; }
            .live-dot::after {
                content: '';
                position: absolute; inset: 0;
                border-radius: 9999px; background: currentColor;
                animation: live-ping 1.8s ease-out infinite;
            }

            /* ════ دکمه‌های press ════ */
            .press {
                transition: transform 0.09s ease, box-shadow 0.09s ease, background-color 0.15s ease;
            }
            .press:active:not(:disabled) { transform: translateY(3px); }

            .btn-primary {
                box-shadow: 0 4px 0 0 rgba(59, 130, 246, 0.4);
                background: #3b82f6;
                color: #fff;
            }
            .btn-primary:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 5px 0 0 rgba(59, 130, 246, 0.45); }
            .btn-primary:active:not(:disabled) { transform: translateY(4px); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
            .btn-primary:disabled { opacity: 0.6; }

            .btn-success {
                box-shadow: 0 4px 0 0 rgb(16 185 129 / 0.4);
                background: rgb(16 185 129);
                color: #fff;
            }
            .btn-success:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 5px 0 0 rgb(16 185 129 / 0.45); }
            .btn-success:active:not(:disabled) { transform: translateY(4px); box-shadow: 0 0 0 0 rgb(16 185 129 / 0.4); }

            .btn-soft {
                box-shadow: 0 3px 0 0 hsl(var(--border));
                background: hsl(var(--secondary));
                color: hsl(var(--foreground));
                border: 1px solid hsl(var(--border));
            }
            .btn-soft:hover:not(:disabled) { transform: translateY(-1px); }
            .btn-soft:active:not(:disabled) { transform: translateY(3px); box-shadow: 0 0 0 0 hsl(var(--border)); }

            /* ════ آیکون رفرش چرخان ════ */
            @keyframes spin-once { to { transform: rotate(360deg); } }
            .spin-active { animation: spin-once 0.8s cubic-bezier(0.4, 0, 0.2, 1); }

            /* ════ Confetti مرحله‌ی پایانی ════ */
            @keyframes pop-in {
                0%   { transform: scale(0) rotate(-20deg); opacity: 0; }
                60%  { transform: scale(1.2) rotate(8deg); }
                100% { transform: scale(1) rotate(0); opacity: 1; }
            }
            .pop-in { animation: pop-in 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }

            @keyframes confetti-fall {
                0%   { transform: translateY(-20px) rotate(0); opacity: 1; }
                100% { transform: translateY(60px) rotate(220deg); opacity: 0; }
            }
            .confetti {
                position: absolute;
                width: 8px; height: 8px;
                animation: confetti-fall 1.6s ease-in infinite;
            }

            /* ════ مودال ـ bottom sheet موبایل ════ */
            .m-overlay {
                position: fixed; inset: 0;
                background: rgba(0,0,0,0.65);
                backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
                z-index: 90;
            }
            .m-sheet {
                position: fixed; left: 0; right: 0; bottom: 0;
                background: hsl(var(--background));
                border-top: 1px solid hsl(var(--border));
                border-radius: 28px 28px 0 0;
                z-index: 100;
                max-height: 92dvh;
                display: flex; flex-direction: column;
                padding-bottom: env(safe-area-inset-bottom, 0);
                box-shadow: 0 -20px 60px rgba(0,0,0,0.3);
            }
            @media (min-width: 640px) {
                .m-sheet {
                    left: 50%; top: 50%; bottom: auto; right: auto;
                    transform: translate(-50%, -50%);
                    width: 90%; max-width: 460px;
                    border-radius: 24px;
                    border: 1px solid hsl(var(--border));
                    max-height: 88dvh;
                }
            }
            .m-handle {
                width: 44px; height: 5px;
                background: hsl(var(--muted-foreground) / 0.35);
                border-radius: 999px; margin: 10px auto 4px;
            }
            @media (min-width: 640px) { .m-handle { display: none; } }

            @keyframes ov-in { from { opacity: 0; } to { opacity: 1; } }
            @keyframes sheet-up { from { transform: translateY(100%); } to { transform: translateY(0); } }
            @keyframes sheet-pop {
                from { opacity: 0; transform: translate(-50%, -45%) scale(0.95); }
                to   { opacity: 1; transform: translate(-50%, -50%) scale(1); }
            }
            .m-overlay { animation: ov-in 0.25s ease forwards; }
            .m-sheet   { animation: sheet-up 0.34s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
            @media (min-width: 640px) {
                .m-sheet { animation: sheet-pop 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
            }

            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after { animation: none !important; transition: none !important; }
            }
        </style>
    @endpush

    {{-- ═══════════ سرتیتر + تایمر انقضا ═══════════ --}}
    <div class="rise r1 flex items-center justify-between gap-3 mb-5 flex-wrap">
        <div class="flex items-center gap-2.5">
            <div class="flex items-center gap-1">
                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                <div class="w-2 h-2 bg-foreground rounded-full"></div>
            </div>
            <span class="font-black text-foreground text-lg">هفته آزمایشی</span>
        </div>

        @if($trialWeek && $trialWeek->expires_at)
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold
                {{ $trialWeek->isExpired()
                    ? 'bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/25'
                    : 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/25' }}">
                @if($trialWeek->isExpired())
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M15 9l-6 6M9 9l6 6"/>
                    </svg>
                    منقضی شده
                @else
                    <span class="live-dot w-2 h-2 rounded-full bg-emerald-500 text-emerald-500"></span>
                    {{ $trialWeek->daysRemaining }} روز باقی‌مانده
                @endif
            </div>
        @elseif($trialWeek)
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/25">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5v5l3 1.7"/>
                </svg>
                ۸ روز دسترسی — از لحظه‌ی ساخت برنامه
            </div>
        @endif
    </div>


    @if($trialWeek)
        {{-- ═══════════ کارت پیشرفت کلی ═══════════ --}}
        @php
            $progressPct = ($trialWeek->step / 4) * 100;
            $stepTitles = [
                0 => 'در حال تخصیص مشاور تخصصی',
                1 => 'نوبت طبقه‌بندی دروس',
                2 => 'نوبت نیازمندی‌های برنامه',
                3 => 'نوبت ساخت برنامه',
                4 => 'هفته آزمایشی فعال شد',
            ];
        @endphp

        <div class="rise r2 relative overflow-hidden rounded-3xl border border-border glass p-6 mb-5" data-tour="progress">
            <div class="absolute -top-20 left-1/3 w-56 h-56 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative flex items-center justify-between mb-5">
                <div>
                    <div class="text-xs text-muted mb-1">وضعیت فعلی</div>
                    <div class="font-black text-foreground text-base">{{ $stepTitles[$trialWeek->step] ?? $trialWeek->statusLabel }}</div>
                </div>
                <div class="text-left">
                    <div class="font-black text-2xl text-blue-500" x-data="counter({{ (int)$progressPct }})" x-text="display + '٪'"></div>
                    <div class="text-[11px] text-muted">{{ $trialWeek->step }} از ۴ مرحله</div>
                </div>
            </div>

            {{-- نوار پیشرفت (تغییر به رنگ آبی اختصاصی) --}}
            <div class="relative h-2.5 rounded-full bg-background overflow-hidden">
                <div class="absolute inset-y-0 right-0 rounded-full bg-gradient-to-l from-sky-400 to-blue-600 progress-fill"
                     style="width: {{ $progressPct }}%">
                    <div class="absolute inset-0 progress-shimmer"></div>
                </div>
            </div>

            {{-- نقاط پیشرفت میانی (اصلاح پوزیشن برای قرارگیری دقیق در میان واژه‌ها) --}}
            <div class="relative flex items-center mt-4 px-2">
                <div class="absolute left-6 right-6 h-0.5 bg-border/40 z-0"></div>

                <div class="w-full flex justify-between relative z-10">
                    @php
                        $miniSteps = [
                            ['t' => 'ثبت‌نام',    'done' => $trialWeek->step >= 0],
                            ['t' => 'مشاور متخصص', 'done' => $trialWeek->step >= 1],
                            ['t' => 'طبقه‌بندی',  'done' => $trialWeek->step >= 2],
                            ['t' => 'نیازمندی‌ها', 'done' => $trialWeek->step >= 3],
                            ['t' => 'برنامه',     'done' => $trialWeek->step >= 4],
                        ];
                    @endphp
                    @foreach($miniSteps as $ms)
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-4 h-4 rounded-full border-2 transition-all flex items-center justify-center
                                {{ $ms['done'] ? 'bg-blue-500 border-blue-500 shadow-sm shadow-blue-500/50' : 'bg-background border-border' }}">
                                @if($ms['done'])
                                    <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                @endif
                            </div>
                            <span class="text-[11px] mt-1.5 {{ $ms['done'] ? 'text-blue-400 font-bold' : 'text-muted' }}">{{ $ms['t'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


        {{-- ═══════════════ تایم‌لاین عمودی مراحل ═══════════════ --}}
        <div class="relative">
            {{-- خط عمودی پس‌زمینه --}}
            <div class="absolute top-6 bottom-6 right-[13px] sm:right-[13px] w-0.5 bg-border"></div>
            {{-- خط پیشرفت عمودی به رنگ آبی --}}
            <div class="absolute top-6 right-[13px] sm:right-[13px] w-0.5 bg-gradient-to-b from-sky-400 to-blue-600 transition-all duration-700"
                 style="height: calc({{ min(100, ($trialWeek->step / 4) * 100) }}% - 1.5rem);"></div>

            <div class="space-y-4">

                {{-- ─────────── مرحله ۱: مشاور  ─────────── --}}
                @php $s1done = $trialWeek->step >= 1; $s1active = !$s1done; @endphp
                <div class="rise r3 relative flex gap-4" data-tour="step1">
                    {{-- نود تایم‌لاین --}}
                    <div class="relative z-10 shrink-0">
                        <div class="w-7 h-7 rounded-2xl flex items-center justify-center border-2
                            {{ $s1done ? 'bg-emerald-500 border-emerald-500'
                               : 'bg-amber-500 border-amber-500 node-active' }}">
                            @if($s1done)
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 6L9 17l-5-5"/>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            @endif
                        </div>
                    </div>

                    {{-- محتوای کارت --}}
                    <div class="flex-1 rounded-2xl border glass p-5
                        {{ $s1active ? 'border-blue-400/60 shadow-lg shadow-blue-500/5' : 'border-border' }}">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black text-muted bg-background rounded px-1.5 py-0.5">مرحله ۱</span>
                                    @if($s1done)
                                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 rounded-full px-2 py-0.5">انجام شد</span>
                                    @else
                                        <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 bg-amber-500/10 rounded-full px-2 py-0.5">در حال انجام</span>
                                    @endif
                                </div>
                                <h3 class="font-black text-foreground">مشاور متخصص شما</h3>
                            </div>
                        </div>

                        @if($s1done && $this->consultant)
                            {{-- کارت مشاور متخصص --}}
                            <div class="mt-3 rounded-xl border border-blue-500/25 bg-background p-4 flex items-center gap-3.5">
                                @if($this->consultant['avatar'])
                                    <img src="{{ $this->consultant['avatar'] }}" alt="{{ $this->consultant['name'] }}"
                                         class="w-14 h-14 rounded-xl object-cover border border-border shrink-0">
                                @else
                                    <div class="w-14 h-14 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center shrink-0">
                                        <svg class="w-7 h-7 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="8" r="4"/><path d="M5 21v-1a7 7 0 0 1 14 0v1"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <div class="font-black text-foreground truncate">{{ $this->consultant['name'] }}</div>
                                </div>
                            </div>
                        @elseif($s1done)
                            <p class="text-sm text-emerald-600 dark:text-emerald-400 font-semibold mt-2 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                فرایند متخصص شما آغاز شد — مشاور متخصص به‌زودی معرفی می‌شود.
                            </p>
                        @else
                            <p class="text-sm text-muted leading-7 mt-2">
                                درخواستت ثبت شد. سیستم در حال انتخاب مشاور متخصص مناسب برای توست.
                            </p>
                            <div class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-500/10">
                                <span class="live-dot w-1.5 h-1.5 rounded-full bg-amber-500 text-amber-500"></span>
                                <span class="text-xs font-bold text-amber-600 dark:text-amber-400">در انتظار تخصیص مشاور متخصص</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ─────────── مرحله ۲: طبقه‌بندی ─────────── --}}
                @php
                    $s2done = $trialWeek->step >= 2;
                    $s2active = $trialWeek->step === 1;
                    $s2locked = $trialWeek->step < 1;
                @endphp
                <div class="rise r4 relative flex gap-4 {{ $s2locked ? 'opacity-55' : '' }}" data-tour="step2">
                    <div class="relative z-10 shrink-0">
                        <div class="w-7 h-7 rounded-2xl flex items-center justify-center border-2
                            {{ $s2done ? 'bg-emerald-500 border-emerald-500'
                               : ($s2active ? 'bg-blue-500 border-blue-500 node-active' : 'bg-secondary border-border') }}">
                            @if($s2done)
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                            @else
                                <svg class="w-6 h-6 {{ $s2active ? 'text-white' : 'text-muted' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 rounded-2xl border glass p-5
                        {{ $s2active ? 'border-blue-500/60 shadow-lg shadow-blue-500/5' : 'border-border' }}">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black text-muted bg-background rounded px-1.5 py-0.5">مرحله ۲</span>
                                    @if($s2done)
                                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 rounded-full px-2 py-0.5">قفل شد</span>
                                    @elseif($s2active)
                                        <span class="text-[10px] font-bold text-blue-400 bg-blue-500/10 rounded-full px-2 py-0.5">اکنون</span>
                                    @endif
                                </div>
                                <h3 class="font-black text-foreground">طبقه‌بندی مباحث</h3>
                            </div>
                        </div>

                        @if(!$s2done)
                            <p class="text-sm text-muted leading-7 mt-2">
                                وضعیت خودتان را در هر درس مشخص کنید!
                                <br>
                                مشاور شما برای ارائه برنامه تخصصی و حرفه ای نیازمند آگاهی کلی از وضعیت تسلط شما در هر درس می باشد.
                            </p>

                            @if($s2active)
                                @if($activeProject)
                                    @if($this->classificationDone)
                                        <div class="mt-3 flex items-center gap-2 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/25">
                                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            <span class="text-xs font-bold text-emerald-700 dark:text-emerald-300">طبقه‌بندی تکمیل شده! حالا تأیید و قفلش کن.</span>
                                        </div>
                                    @endif

                                    {{-- تغییر چیدمان دکمه‌ها: دکمه رفتن به طبقه‌بندی در سمت چپ و تایید در سمت راست قرار گرفت --}}
                                    <div class="flex items-center justify-between gap-2 mt-3 flex-wrap w-full">
                                        <div>
                                            @if($this->classificationDone)
                                                <button type="button" wire:click="openLockConfirm"
                                                        class="press btn-success inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                                    </svg>
                                                    تأیید و قفل کردن
                                                </button>
                                            @endif
                                        </div>

                                        <a wire:navigate
                                           href="{{ route('client.profile.classification.classify', ['project' => $activeProject->id, 'grade' => ($trialWeek->grade >= 10 ? $trialWeek->grade : 10)]) }}"
                                           class="press btn-primary inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold mr-auto">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M15 3h6v6M10 14L21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                                            </svg>
                                            رفتن به طبقه‌بندی
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-3 inline-flex items-center gap-1.5 text-xs text-amber-600 dark:text-amber-400">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 8v4M12 16h.01"/></svg>
                                        پروژه‌ای برای طبقه‌بندی موجود نیست
                                    </div>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>

                {{-- ─────────── مرحله ۳: پیش‌جلسه ─────────── --}}
                @php
                    $s3done = $trialWeek->step >= 3;
                    $s3active = $trialWeek->step === 2;
                    $s3locked = $trialWeek->step < 2;
                @endphp
                <div class="rise r5 relative flex gap-4 {{ $s3locked ? 'opacity-55' : '' }}" data-tour="step3">
                    <div class="relative z-10 shrink-0">
                        <div class="w-7 h-7 rounded-2xl flex items-center justify-center border-2
                            {{ $s3done ? 'bg-emerald-500 border-emerald-500'
                               : ($s3active ? 'bg-blue-500 border-blue-500 node-active' : 'bg-secondary border-border') }}">
                            @if($s3done)
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                            @else
                                <svg class="w-6 h-6 {{ $s3active ? 'text-white' : 'text-muted' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 rounded-2xl border glass p-5
                        {{ $s3active ? 'border-blue-500/60 shadow-lg shadow-blue-500/5' : 'border-border' }}">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black text-muted bg-background rounded px-1.5 py-0.5">مرحله ۳</span>
                                    @if($s3done)
                                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 rounded-full px-2 py-0.5">تکمیل شد</span>
                                    @elseif($s3active)
                                        <span class="text-[10px] font-bold text-blue-400 bg-blue-500/10 rounded-full px-2 py-0.5">اکنون</span>
                                    @endif
                                </div>
                                <h3 class="font-black text-foreground">نیازمندی های برنامه </h3>
                            </div>
                        </div>
                        <p class="text-sm text-muted leading-7 mt-2"></p>

                        @if($s3active || $s3done)
                            <div class="mt-4 grid sm:grid-cols-2 gap-3">
                                {{-- پیش‌جلسه --}}
                                <div class="rounded-xl border border-border bg-background p-3 flex flex-col gap-2">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-xs font-bold text-foreground">مواردی که پیش از نوشتن برنامه مورد توجه قرار میگیره</span>
                                        @if($this->preSessionCompleted)
                                            <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 rounded-full px-2 py-0.5 inline-flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                                                نهایی شد
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 bg-amber-500/10 rounded-full px-2 py-0.5">در انتظار</span>
                                        @endif
                                    </div>
                                    @if($s3active && $trialWeek->advisingSession && !$this->preSessionCompleted)
                                        <a wire:navigate
                                           href="{{ route('client.profile.consultation.pre-session', $trialWeek->advising_session_id) }}"
                                           class="press btn-primary inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-xs font-bold w-full">
                                            ادامه
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M19 12H5M12 19l-7-7 7-7"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>

                                {{-- برنامه درسی --}}
                                <div class="rounded-xl border border-border bg-background p-3 flex flex-col gap-2">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-xs font-bold text-foreground">برنامه درسی مدرسه</span>
                                        @if(!$this->needsSchedule)
                                            <span class="text-[10px] font-bold text-sky-600 dark:text-sky-400 bg-sky-500/10 rounded-full px-2 py-0.5">لازم نیست</span>
                                        @elseif($this->classScheduleFinalized)
                                            <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 rounded-full px-2 py-0.5 inline-flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                                                نهایی شد
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 bg-amber-500/10 rounded-full px-2 py-0.5">در انتظار</span>
                                        @endif
                                    </div>
                                    @if(!$this->needsSchedule)
                                        <p class="text-[11px] text-muted leading-5">
                                            {{ $trialWeek->isGraduate() ? 'چون فارغ‌التحصیل هستی' : 'چون فعلاً مدرسه نمی‌روی' }}،
                                            نیازی به پر کردن برنامه کلاسی نداری .
                                        </p>
                                    @elseif($s3active && !$this->classScheduleFinalized)
                                        <a wire:navigate
                                           href="{{ route('client.profile.consultation.class-schedule') }}"
                                           class="press btn-primary inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-xs font-bold w-full">
                                            پر کردن برنامه درسی
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                                <line x1="16" y1="2" x2="16" y2="6"/>
                                                <line x1="8" y1="2" x2="8" y2="6"/>
                                                <line x1="3" y1="10" x2="21" y2="10"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ─────────── مرحله ۴: ساخت برنامه ─────────── --}}
                @php
                    $s4done = $trialWeek->step >= 4;
                    $s4active = $trialWeek->step === 3;
                    $s4locked = $trialWeek->step < 3;
                @endphp
                <div class="rise r6 relative flex gap-4 {{ $s4locked ? 'opacity-55' : '' }}" data-tour="step4">
                    <div class="relative z-10 shrink-0">
                        <div class="w-7 h-7 rounded-2xl flex items-center justify-center border-2
                            {{ $s4done ? 'bg-emerald-500 border-emerald-500'
                               : ($s4active ? 'bg-blue-500 border-blue-500 node-active' : 'bg-secondary border-border') }}">
                            @if($s4done)
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                            @else
                                <svg class="w-6 h-6 {{ $s4active ? 'text-white' : 'text-muted' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 rounded-2xl border glass p-5
                        {{ $s4active ? 'border-blue-500/60 shadow-lg shadow-blue-500/5' : 'border-border' }}">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black text-muted bg-background rounded px-1.5 py-0.5">مرحله ۴</span>
                                    @if($s4done)
                                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 rounded-full px-2 py-0.5">کامل شد</span>
                                    @elseif($s4active)
                                        <span class="text-[10px] font-bold text-blue-400 bg-blue-500/10 rounded-full px-2 py-0.5">اکنون</span>
                                    @endif
                                </div>
                                <h3 class="font-black text-foreground">ورود به جلسه و ساخت برنامه</h3>
                            </div>
                        </div>
                        <p class="text-sm text-muted leading-7 mt-2">
                            وضعیت تو تحلیل می‌شود و یک برنامه‌ی مطالعاتی شخصی برایت ساخته می‌شود.
                        </p>
                        @if($s4active)
                            <a wire:navigate href="{{ route('client.profile.trial.session-analysis') }}"
                               class="press btn-primary inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold">
                                ورود به جلسه
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>


        {{-- ═══════════ وضعیت شخصیتی (پیش از ساخت برنامه) ═══════════ --}}
        @php $personality = $this->personalitySummary; @endphp


        {{-- ═══════════ کارت پایانی: تبریک ═══════════ --}}
        @if($trialWeek->step >= 4)
            <div class="rise r6 relative overflow-hidden rounded-3xl border border-emerald-500/30 bg-gradient-to-br from-emerald-500/10 to-blue-500/5 p-7 mt-5 text-center">
                {{-- confetti --}}
                <div class="absolute inset-x-0 top-0 h-20 pointer-events-none overflow-hidden">
                    <span class="confetti rounded-sm bg-emerald-400" style="left:15%; animation-delay:0s;"></span>
                    <span class="confetti rounded-full bg-blue-500" style="left:32%; animation-delay:0.3s;"></span>
                    <span class="confetti rounded-sm bg-amber-400" style="left:50%; animation-delay:0.6s;"></span>
                    <span class="confetti rounded-full bg-emerald-400" style="left:68%; animation-delay:0.15s;"></span>
                    <span class="confetti rounded-sm bg-blue-500" style="left:85%; animation-delay:0.45s;"></span>
                </div>

                <div class="pop-in relative inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-500 mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 21h8M12 17v4M7 4h10v6a5 5 0 0 1-10 0V4zM7 4H4v2a3 3 0 0 0 3 3M17 4h3v2a3 3 0 0 1-3 3"/>
                    </svg>
                </div>
                <h3 class="relative font-black text-foreground text-xl mb-2">تبریک! هفته‌ی آزمایشی کامل شد</h3>
                <p class="relative text-sm text-muted leading-7 max-w-md mx-auto mb-5">
                    برنامه‌ی مطالعاتی‌ات آماده است. در طول
                    <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $trialWeek->daysRemaining }} روز</span>
                    باقی‌مانده از تمام امکانات استفاده کن.
                </p>
                <div class="relative flex justify-center gap-3 flex-wrap">
                    <a wire:navigate href="{{ route('client.profile.trial.report') }}"
                       class="press btn-primary inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
                        </svg>
                        مشاهده کارنامه
                    </a>
                    <a wire:navigate href="{{ route('client.profile.consultation.sessions') }}"
                       class="press btn-soft inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                        مشاهده جلسات
                    </a>
                    <a wire:navigate href="{{ route('client.profile.classification.projects') }}"
                       class="press btn-soft inline-flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-bold">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                        مشاهده طبقه‌بندی
                    </a>
                </div>
            </div>
        @endif
    @endif


    {{-- ════════════════ مودال تأیید قفل طبقه‌بندی ════════════════ --}}
    @if($showLockConfirm)
        <div x-data x-init="document.body.style.overflow='hidden'"
             @keydown.escape.window="$wire.closeLockConfirm()">
            <div class="m-overlay" wire:click="closeLockConfirm"></div>
            <div class="m-sheet" @click.stop>
                <div class="m-handle"></div>

                <div class="p-6 text-center overflow-y-auto">
                    <div class="pop-in inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-amber-500/15 border border-amber-500/30 mb-4">
                        <svg class="w-8 h-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>

                    <h2 class="text-lg font-black text-foreground mb-2">تأیید قفل طبقه‌بندی</h2>
                    <p class="text-sm text-muted leading-7 mb-5">
                        پس از تأیید، <strong class="text-foreground">امکان ویرایش طبقه‌بندی وجود نخواهد داشت.</strong>
                        از صحت اطلاعات وارد‌شده مطمئنی؟
                    </p>

                    @error('lock')<p class="text-sm text-red-500 mb-3">{{ $message }}</p>@enderror

                    <div class="flex gap-3">
                        <button type="button" wire:click="lockClassification"
                                wire:loading.attr="disabled" wire:target="lockClassification"
                                class="press btn-success flex-1 inline-flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm">
                            <svg wire:loading wire:target="lockClassification" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.4 0 0 5.4 0 12h4z"/>
                            </svg>
                            <span wire:loading.remove wire:target="lockClassification">بله، قفل کن</span>
                            <span wire:loading wire:target="lockClassification">در حال قفل…</span>
                        </button>
                        <button type="button" wire:click="closeLockConfirm"
                                class="press btn-soft flex-1 py-3 rounded-xl font-bold text-sm">
                            بازبینی می‌کنم
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif


    {{-- ════════════════ تور راهنمای صفحه ════════════════ --}}
    <x-client.page-tour storage-key="trial_guide_tour_done" :steps="[
        ['el' => '[data-tour=progress]', 'title' => 'نوار پیشرفت هفته آزمایشی', 'text' => 'از اینجا می‌بینی الان در کدام مرحله‌ای و چند درصد مسیر را رفته‌ای.'],
        ['el' => '[data-tour=step1]',    'title' => 'مشاور متخصص تو', 'text' => 'مشخصات مشاور متخصصت اینجاست؛ در طول هفته‌ی آزمایشی همراهت است و می‌توانی باهاش تماس بگیری.'],
        ['el' => '[data-tour=step2]',    'title' => 'طبقه‌بندی مباحث', 'text' => 'وضعیت تسلطت روی هر درس را مشخص می‌کنی تا برنامه دقیقاً بر اساس نقاط ضعف و قوتت ساخته شود.'],
        ['el' => '[data-tour=step3]',    'title' => 'نیازمندی‌های برنامه', 'text' => 'پیش‌جلسه (امتحان‌ها، پارت درخواستی و…) و در صورت نیاز برنامه کلاسی مدرسه را اینجا تکمیل می‌کنی.'],
        ['el' => '[data-tour=step4]',    'title' => 'ساخت برنامه', 'text' => 'بعد از تکمیل مراحل، وارد جلسه می‌شوی، کارنامه‌ی تحلیلی‌ات را می‌بینی و برنامه‌ی اختصاصی‌ات ساخته می‌شود.'],
    ]" />


    @push('script')
        <script>
            function guidePage() {
                return {
                    init() {},
                };
            }
            document.addEventListener('alpine:init', () => {
                if (Alpine.data && !Alpine.__guideCounter) {
                    Alpine.__guideCounter = true;
                    Alpine.data('counter', (target) => ({
                        display: 0,
                        init() {
                            const dur = 900, start = performance.now();
                            const tick = (now) => {
                                const t = Math.min(1, (now - start) / dur);
                                const eased = 1 - Math.pow(1 - t, 3);
                                this.display = Math.floor(target * eased);
                                if (t < 1) requestAnimationFrame(tick);
                                else this.display = target;
                            };
                            requestAnimationFrame(tick);
                        },
                    }));
                }
            });
        </script>
    @endpush
</div>
