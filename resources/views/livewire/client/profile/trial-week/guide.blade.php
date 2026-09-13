<div class="max-w-5xl mx-auto px-4 py-6 sm:py-10" dir="rtl">
    @php
        $hideForExamProgramTrialStudent = auth()->check()
            && app(\App\Services\ExamPlanningService::class)->shouldHideTrialExamProgramSections(auth()->user());
    @endphp

@assets
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

            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after { animation: none !important; transition: none !important; }
            }
        </style>
@endassets
    @if($examPlanningMode)
        <div class="rise r1 rounded-3xl border border-border glass p-6 mb-5">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-xl font-black text-primary mb-2">تجربه هوشمند برنامه امتحانی</h1>
                    <p class="text-sm text-muted leading-7 max-w-2xl">
                        تقویم امتحانت را تکمیل می‌کنی، ساعت مطالعه‌ی درس‌ها را می‌دهی و برنامه مخصوص امتحاناتت رو از SDFR تحویل میگیری.
                    </p>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-3 mt-5">
                <div class="rounded-2xl border border-border bg-background p-4">
                    <div class="text-[20px] font-bold text-foreground mb-1">گــــــــام ۱</div>
                    <div class="font-black text-primary mb-2">تقویم امتحان</div>
                </div>
                <div class="rounded-2xl border border-border bg-background p-4">
                    <div class="text-[20px] font-bold text-foreground mb-1">گــــــــام 2</div>
                    <div class="font-black text-primary mb-2">ساعت‌ مطالعه هر درس</div>
                </div>
                <div class="rounded-2xl border border-border bg-background p-4">
                    <div class="text-[20px] font-bold text-foreground mb-1">گــــــــام 3</div>
                    <div class="font-black text-primary mb-2">ساخت برنامه</div>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3 justify-center md:justify-end">
                <x-ui.button wire:click="goToExamPlanning" wire:loading.attr="disabled" wire:target="goToExamPlanning"
                             variant="primary" size="lg" icon="arrow-left" block class="md:w-auto">
                    <span wire:loading.remove wire:target="goToExamPlanning">ساخت برنامه</span>
                    <span wire:loading wire:target="goToExamPlanning" class="inline-flex items-center gap-2">
                        <x-ui.spinner size="sm"/>
                        در حال انتقال...
                    </span>
                </x-ui.button>
            </div>
        </div>
    @else
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
                    ? 'bg-error/10 text-error border border-error/25'
                    : 'bg-success/10 text-success border border-success/25' }}">
                @if($trialWeek->isExpired())
                    <x-ui.icon name="circle-x" class="w-3.5 h-3.5"/>
                    منقضی شده
                @else
                    <span class="live-dot w-2 h-2 rounded-full bg-success text-success"></span>
                    {{ $trialWeek->daysRemaining }} روز باقی‌مانده
                @endif
            </div>
        @elseif($trialWeek)
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-info/10 text-info border border-info/25">
                <x-ui.icon name="clock" class="w-3.5 h-3.5"/>
                ۸ روز دسترسی — از لحظه‌ی ساخت برنامه
            </div>
        @endif
    </div>


    @if($trialWeek)
        {{-- ═══════════ کارت پیشرفت کلی ═══════════ --}}
        @php
            // (E1) نوار تا «وسطِ» فاصله‌ی بینِ آخرین پله‌ی تکمیل‌شده و پله‌ی در‌حال‌انجام پر می‌شود
            // (روی نشانگرِ پله‌ی بعدی نمی‌رود). در پایان (همه تکمیل) = ۱۰۰٪.
            $progressPct = $trialWeek->step >= 4 ? 100 : (($trialWeek->step + 0.5) / 4) * 100;
            $stepTitles = [
                0 => 'در حال تخصیص مشاور تخصصی',
                1 => 'طبقه‌بندی دروس',
                2 => 'نیازمندی‌های برنامه',
                3 => 'ساخت برنامه',
                4 => 'هفته آزمایشی فعال شد',
            ];
        @endphp

        <div class="rise r2 relative overflow-hidden rounded-3xl border border-border glass p-6 mb-5" data-tour="progress">
            <div class="absolute -top-20 left-1/3 w-56 h-56 bg-info/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative flex items-center justify-between mb-5">
                <div>
                    <div class="text-xs text-muted mb-1">وضعیت فعلی</div>
                    <div class="font-black text-foreground text-base">{{ $stepTitles[$trialWeek->step] ?? $trialWeek->statusLabel }}</div>
                </div>
                <div class="text-left">
                    <div class="font-black text-2xl text-info"
                         x-data="{
                            display: 0,
                            init() {
                                const target = {{ (int) $progressPct }};
                                const duration = 900;
                                const start = performance.now();
                                const tick = (now) => {
                                    const t = Math.min(1, (now - start) / duration);
                                    const eased = 1 - Math.pow(1 - t, 3);
                                    this.display = Math.floor(target * eased);
                                    if (t < 1) {
                                        requestAnimationFrame(tick);
                                        return;
                                    }

                                    this.display = target;
                                };

                                requestAnimationFrame(tick);
                            }
                         }"
                         x-text="display + '٪'"></div>
                    <div class="text-[11px] text-muted">{{ $trialWeek->step }} از ۴ مرحله</div>
                </div>
            </div>

            {{-- نوار پیشرفت (تغییر به رنگ آبی اختصاصی) --}}
            <div class="relative h-2.5 rounded-full bg-background overflow-hidden">
                <div class="absolute inset-y-0 right-0 rounded-full bg-info progress-fill"
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
                                {{ $ms['done'] ? 'bg-info border-info shadow-sm shadow-info/50' : 'bg-background border-border' }}">
                                @if($ms['done'])
                                    <div class="w-1.5 h-1.5 rounded-full bg-info-foreground"></div>
                                @endif
                            </div>
                            <span class="text-[11px] mt-1.5 {{ $ms['done'] ? 'text-info font-bold' : 'text-muted' }}">{{ $ms['t'] }}</span>
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
            <div class="absolute top-6 right-[13px] sm:right-[13px] w-0.5 bg-info transition-all duration-700"
                 style="height: calc({{ min(100, $progressPct) }}% - 1.5rem);"></div>

            <div class="space-y-4">

                {{-- ─────────── مرحله ۱: مشاور  ─────────── --}}
                @php $s1done = $trialWeek->step >= 1; $s1active = !$s1done; @endphp
                <div class="rise r3 relative flex gap-4" data-tour="step1">
                    {{-- نود تایم‌لاین --}}
                    <div class="relative z-10 shrink-0">
                        <div class="w-7 h-7 rounded-2xl flex items-center justify-center border-2
                            {{ $s1done ? 'bg-success border-success'
                               : 'bg-warning border-warning node-active' }}">
                            @if($s1done)
                                <x-ui.icon name="check" class="w-6 h-6 text-success-foreground"/>
                            @else
                                {{-- آیکون «گروه/تیم» توی دیکشنری Keyline نیست، نزدیک‌ترین معادل (user) استفاده شد. --}}
                                <x-ui.icon name="user" class="w-6 h-6 text-warning-foreground"/>
                            @endif
                        </div>
                    </div>

                    {{-- محتوای کارت --}}
                    <div class="flex-1 rounded-2xl border glass p-5
                        {{ $s1active ? 'border-info/60 shadow-lg shadow-info/5' : 'border-border' }}">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black text-muted bg-background rounded px-1.5 py-0.5">مرحله ۱</span>
                                    @if($s1done)
                                        <span class="text-[10px] font-bold text-success bg-success/10 rounded-full px-2 py-0.5">انجام شد</span>
                                    @else
                                        <span class="text-[10px] font-bold text-warning bg-warning/10 rounded-full px-2 py-0.5">در حال انجام</span>
                                    @endif
                                </div>
                                <h3 class="font-black text-foreground">مشاور متخصص شما</h3>
                            </div>
                        </div>

                        @if($s1done && $this->consultant)
                            {{-- کارت مشاور متخصص --}}
                            <div class="mt-3 rounded-xl border border-info/25 bg-background p-4 flex items-center gap-3.5">
                                @if($this->consultant['avatar'])
                                    <img src="{{ $this->consultant['avatar'] }}" alt="{{ $this->consultant['name'] }}"
                                         class="w-14 h-14 rounded-xl object-cover border border-border shrink-0">
                                @else
                                    <div class="w-14 h-14 rounded-xl bg-success/10 border border-success/20 flex items-center justify-center shrink-0">
                                        <x-ui.icon name="user" class="w-7 h-7 text-success"/>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <div class="font-black text-foreground truncate">{{ $this->consultant['name'] }}</div>
                                </div>
                            </div>
                        @elseif($s1done)
                            <p class="text-sm text-success font-semibold mt-2 flex items-center gap-1.5">
                                <x-ui.icon name="check" class="w-4 h-4"/>
                                فرایند متخصص شما آغاز شد — مشاور متخصص به‌زودی معرفی می‌شود.
                            </p>
                        @else
                            <p class="text-sm text-muted leading-7 mt-2">
                                درخواستت ثبت شد. سیستم در حال انتخاب مشاور متخصص مناسب برای توست.
                            </p>
                            <div class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-warning/10">
                                <span class="live-dot w-1.5 h-1.5 rounded-full bg-warning text-warning"></span>
                                <span class="text-xs font-bold text-warning">در انتظار تخصیص مشاور متخصص</span>
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
                            {{ $s2done ? 'bg-success border-success'
                               : ($s2active ? 'bg-info border-info node-active' : 'bg-secondary border-border') }}">
                            @if($s2done)
                                <x-ui.icon name="check" class="w-6 h-6 text-success-foreground"/>
                            @else
                                <x-ui.icon name="layers" class="w-6 h-6 {{ $s2active ? 'text-info-foreground' : 'text-muted' }}"/>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 rounded-2xl border glass p-5
                        {{ $s2active ? 'border-info/60 shadow-lg shadow-info/5' : 'border-border' }}">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black text-muted bg-background rounded px-1.5 py-0.5">مرحله ۲</span>
                                    @if($s2done)
                                        <span class="text-[10px] font-bold text-success bg-success/10 rounded-full px-2 py-0.5">قفل شد</span>
                                    @elseif($s2active)
                                        <span class="text-[10px] font-bold text-info bg-info/10 rounded-full px-2 py-0.5">اکنون</span>
                                    @endif
                                </div>
                                <h3 class="font-black text-foreground">طبقه‌بندی دروس</h3>
                            </div>
                        </div>

                        @if(!$s2done)
                            <br>
                            <span class="font-medium"> وضعیت خودتان را در هر درس مشخص کنید!</span>
                            <p class="text-sm text-muted leading-7 mt-2">
                                مشاور شما برای ارائه برنامه تخصصی و حرفه ای نیازمند آگاهی کلی از وضعیت تسلط شما در هر درس می باشد.
                            </p>

                            @if($s2active)
                                @if($activeProject)
                                    @if($this->classificationDone)
                                        <div class="mt-3 flex items-center gap-2 p-3 rounded-xl bg-success/10 border border-success/25">
                                            <x-ui.icon name="check" class="w-4 h-4 text-success shrink-0"/>
                                            <span class="text-xs font-bold text-success">طبقه‌بندی تکمیل شده! حالا تأیید و قفلش کن.</span>
                                        </div>
                                    @endif

                                    {{-- تغییر چیدمان دکمه‌ها: دکمه رفتن به طبقه‌بندی در سمت چپ و تایید در سمت راست قرار گرفت --}}
                                    <div class="flex items-center justify-between gap-2 mt-3 flex-wrap w-full">
                                        <div>
                                            @if($this->classificationDone)
                                                <x-ui.button type="button" wire:click="openLockConfirm" variant="success" icon="lock" size="sm">تأیید و قفل کردن</x-ui.button>
                                            @endif
                                        </div>

                                        <x-ui.button href="{{ route('client.profile.classification.classify', ['project' => $activeProject->id, 'grade' => ($trialWeek->grade >= 10 ? $trialWeek->grade : 10)]) }}"
                                                     wire:navigate variant="primary" icon="arrow-left" size="lg" class="w-full sm:w-auto sm:mr-auto">شروع</x-ui.button>
                                    </div>
                                @else
                                    <div class="mt-3 inline-flex items-center gap-1.5 text-xs text-warning">
                                        <x-ui.icon name="triangle-alert" class="w-4 h-4"/>
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
                            {{ $s3done ? 'bg-success border-success'
                               : ($s3active ? 'bg-info border-info node-active' : 'bg-secondary border-border') }}">
                            @if($s3done)
                                <x-ui.icon name="check" class="w-6 h-6 text-success-foreground"/>
                            @else
                                <x-ui.icon name="list-check" class="w-6 h-6 {{ $s3active ? 'text-info-foreground' : 'text-muted' }}"/>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 rounded-2xl border glass p-5
                        {{ $s3active ? 'border-info/60 shadow-lg shadow-info/5' : 'border-border' }}">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black text-muted bg-background rounded px-1.5 py-0.5">مرحله ۳</span>
                                    @if($s3done)
                                        <span class="text-[10px] font-bold text-success bg-success/10 rounded-full px-2 py-0.5">تکمیل شد</span>
                                    @elseif($s3active)
                                        <span class="text-[10px] font-bold text-info bg-info/10 rounded-full px-2 py-0.5">اکنون</span>
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
                                            <span class="text-[10px] font-bold text-success bg-success/10 rounded-full px-2 py-0.5 inline-flex items-center gap-1">
                                                <x-ui.icon name="check" class="w-3 h-3"/>
                                                نهایی شد
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold text-warning bg-warning/10 rounded-full px-2 py-0.5">در انتظار</span>
                                        @endif
                                    </div>
                                    @if($s3active && $trialWeek->advisingSession && !$this->preSessionCompleted)
                                        <x-ui.button href="{{ route('client.profile.consultation.pre-session', $trialWeek->advising_session_id) }}"
                                                     wire:navigate variant="primary" icon="arrow-left" size="sm" block>ادامه</x-ui.button>
                                    @endif
                                </div>

                                {{-- برنامه درسی --}}
                                <div class="rounded-xl border border-border bg-background p-3 flex flex-col gap-2">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-xs font-bold text-foreground">برنامه درسی مدرسه</span>
                                        @if(!$this->needsSchedule)
                                            <span class="text-[10px] font-bold text-info bg-info/10 rounded-full px-2 py-0.5">لازم نیست</span>
                                        @elseif($this->classScheduleFinalized)
                                            <span class="text-[10px] font-bold text-success bg-success/10 rounded-full px-2 py-0.5 inline-flex items-center gap-1">
                                                <x-ui.icon name="check" class="w-3 h-3"/>
                                                نهایی شد
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold text-warning bg-warning/10 rounded-full px-2 py-0.5">در انتظار</span>
                                        @endif
                                    </div>
                                    @if(!$this->needsSchedule)
                                        <p class="text-[11px] text-muted leading-5">
                                            {{ $trialWeek->isGraduate() ? 'چون فارغ‌التحصیل هستی' : 'چون فعلاً مدرسه نمی‌روی' }}،
                                            نیازی به پر کردن برنامه کلاسی نداری .
                                        </p>
                                    @elseif($s3active && !$this->classScheduleFinalized)
                                        <x-ui.button href="{{ route('client.profile.consultation.class-schedule') }}"
                                                     wire:navigate variant="primary" icon="calendar" size="sm" block>پر کردن برنامه درسی</x-ui.button>
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
                            {{ $s4done ? 'bg-success border-success'
                               : ($s4active ? 'bg-info border-info node-active' : 'bg-secondary border-border') }}">
                            @if($s4done)
                                <x-ui.icon name="check" class="w-6 h-6 text-success-foreground"/>
                            @else
                                {{-- آیکون «رعد/جرقه» توی دیکشنری Keyline نیست، همون SVG قبلی نگه داشته شد. --}}
                                <svg class="w-6 h-6 {{ $s4active ? 'text-info-foreground' : 'text-muted' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 rounded-2xl border glass p-5
                        {{ $s4active ? 'border-info/60 shadow-lg shadow-info/5' : 'border-border' }}">
                        <div class="flex items-start justify-between gap-3 flex-wrap">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black text-muted bg-background rounded px-1.5 py-0.5">مرحله ۴</span>
                                    @if($s4done)
                                        <span class="text-[10px] font-bold text-success bg-success/10 rounded-full px-2 py-0.5">کامل شد</span>
                                    @elseif($s4active)
                                        <span class="text-[10px] font-bold text-info bg-info/10 rounded-full px-2 py-0.5">اکنون</span>
                                    @endif
                                </div>
                                <h3 class="font-black text-foreground">ساخت برنامه‌ی مطالعاتی</h3>
                            </div>
                        </div>
                        <p class="text-sm text-muted leading-7 mt-2">
                            فقط کافیست ساعت مطالعه‌ی روزانه‌ات را انتخاب کنی تا برنامه‌ی اختصاصی‌ات ساخته شود.
                        </p>
                        @if($s4active)
                            <x-ui.button type="button" wire:click="openHoursModal" variant="primary" icon="arrow-left" size="sm">ساخت برنامه‌ی من</x-ui.button>
                        @endif
                    </div>
                </div>

            </div>
        </div>


        {{-- ═══════════ وضعیت شخصیتی (پیش از ساخت برنامه) ═══════════ --}}
        @php $personality = $this->personalitySummary; @endphp


        {{-- ═══════════ کارت پایانی: تبریک ═══════════ --}}
        @if($trialWeek->step >= 4)
            <div class="rise r6 relative overflow-hidden rounded-3xl border border-success/30 bg-success/10 p-7 mt-5 text-center">
                {{-- confetti --}}
                <div class="absolute inset-x-0 top-0 h-20 pointer-events-none overflow-hidden">
                    <span class="confetti rounded-sm bg-success" style="left:15%; animation-delay:0s;"></span>
                    <span class="confetti rounded-full bg-info" style="left:32%; animation-delay:0.3s;"></span>
                    <span class="confetti rounded-sm bg-warning" style="left:50%; animation-delay:0.6s;"></span>
                    <span class="confetti rounded-full bg-success" style="left:68%; animation-delay:0.15s;"></span>
                    <span class="confetti rounded-sm bg-info" style="left:85%; animation-delay:0.45s;"></span>
                </div>

                {{-- آیکون «جام» توی دیکشنری Keyline نیست، همون SVG قبلی نگه داشته شد. --}}
                <div class="pop-in relative inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-success mb-4">
                    <svg class="w-8 h-8 text-success-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 21h8M12 17v4M7 4h10v6a5 5 0 0 1-10 0V4zM7 4H4v2a3 3 0 0 0 3 3M17 4h3v2a3 3 0 0 1-3 3"/>
                    </svg>
                </div>
                <h3 class="relative font-black text-foreground text-xl mb-2">تبریک! هفته‌ی آزمایشی کامل شد</h3>
                <p class="relative text-sm text-muted leading-7 max-w-md mx-auto mb-5">
                    برنامه‌ی مطالعاتی‌ات آماده است. در طول
                    <span class="font-bold text-success">{{ $trialWeek->daysRemaining }} روز</span>
                    باقی‌مانده از تمام امکانات استفاده کن.
                </p>
                @unless($hideForExamProgramTrialStudent)
                    <div class="relative flex justify-center gap-3 flex-wrap">
                        <x-ui.button href="{{ route('client.profile.trial.report') }}" wire:navigate variant="primary" icon="receipt">مشاهده کارنامه</x-ui.button>
                        <x-ui.button href="{{ route('client.profile.consultation.sessions') }}" wire:navigate variant="secondary-outline" icon="calendar">مشاهده جلسات</x-ui.button>
                        <x-ui.button href="{{ route('client.profile.classification.projects') }}" wire:navigate variant="secondary-outline" icon="layers">مشاهده طبقه‌بندی</x-ui.button>
                    </div>
                @endunless
            </div>
        @endif
    @endif


    {{-- ════════════════ مودال تأیید قفل طبقه‌بندی ════════════════ --}}
    <x-ui.modal id="trial-lock-confirm" max-width="sm">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-warning/15 border border-warning/30 mb-4">
                <x-ui.icon name="lock" class="w-8 h-8 text-warning"/>
            </div>

            <h2 class="text-lg font-black text-foreground mb-2">تأیید قفل طبقه‌بندی</h2>
            <p class="text-sm text-muted leading-7 mb-5">
                پس از تأیید، <strong class="text-foreground">امکان ویرایش طبقه‌بندی وجود نخواهد داشت.</strong>
                از صحت اطلاعات وارد‌شده مطمئنی؟
            </p>

            @error('lock')<p class="text-sm text-error mb-3">{{ $message }}</p>@enderror
        </div>

        <x-slot:footer>
            <x-ui.button type="button" wire:click="closeLockConfirm" variant="secondary-outline" icon="x" block>بازبینی می‌کنم</x-ui.button>
            <x-ui.button type="button" wire:click="lockClassification" wire:loading.attr="disabled" wire:target="lockClassification" variant="success" block>
                <span wire:loading.remove wire:target="lockClassification">بله، قفل کن</span>
                <span wire:loading wire:target="lockClassification" class="inline-flex items-center gap-2">
                    <x-ui.spinner size="xs"/>
                    در حال قفل…
                </span>
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    {{-- ════════════════ (F) مودال انتخاب ساعت مطالعه — داخلِ همین صفحه ════════════════ --}}
    <x-ui.modal id="trial-hours-modal-guide" max-width="sm">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-info/15 border border-info/30 mb-4">
                <x-ui.icon name="clock" class="w-8 h-8 text-info"/>
            </div>

            <h2 class="text-lg font-black text-foreground mb-2">تعیین ساعت مطالعه روزانه</h2>
            <p class="text-sm text-muted leading-7 mb-5">
                میانگین ساعتی که می‌توانی در روز مطالعه کنی را انتخاب کن تا برنامه‌ات بر اساس آن ساخته شود.
            </p>

            <div class="mb-6 max-w-xs mx-auto text-right" wire:key="hours-select">
                <x-ui.select
                    wire:model="dailyStudyHours"
                    :options="collect(range(2,12))->map(fn($i) => ['id' => $i, 'name' => $i . ' ساعت در روز'])->all()"
                    value-key="id" label-key="name" placeholder="انتخاب ساعت..." :drop-up="true" />
                @error('dailyStudyHours')<p class="text-xs text-error mt-2">{{ $message }}</p>@enderror
            </div>
        </div>

        <x-slot:footer>
            <x-ui.button type="button" wire:click="closeHoursModal" variant="secondary-outline" icon="x" block>انصراف</x-ui.button>
            <x-ui.button type="button" wire:click="buildProgram" wire:loading.attr="disabled" wire:target="buildProgram" variant="primary" block>
                <span wire:loading.remove wire:target="buildProgram">تأیید و ساخت برنامه</span>
                <span wire:loading wire:target="buildProgram" class="inline-flex items-center gap-2">
                    <x-ui.spinner size="xs"/>
                    در حال ایجاد…
                </span>
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>


    {{-- ════════════════ تور راهنمای صفحه ════════════════ --}}
    <x-client.page-tour storage-key="trial_guide_tour_done" :steps="[
        ['el' => '[data-tour=progress]', 'title' => 'نوار پیشرفت هفته آزمایشی', 'text' => 'از اینجا می‌بینی الان در کدام مرحله‌ای و چند درصد مسیر را رفته‌ای.'],
        ['el' => '[data-tour=step1]',    'title' => 'مشاور متخصص تو', 'text' => 'مشخصات مشاور متخصصت اینجاست؛ در طول هفته‌ی آزمایشی همراهت است و می‌توانی باهاش تماس بگیری.'],
        ['el' => '[data-tour=step2]',    'title' => 'طبقه‌بندی مباحث', 'text' => 'وضعیت تسلطت روی هر درس را مشخص می‌کنی تا برنامه دقیقاً بر اساس نقاط ضعف و قوتت ساخته شود.'],
        ['el' => '[data-tour=step3]',    'title' => 'نیازمندی‌های برنامه', 'text' => 'پیش‌جلسه (امتحان‌ها، پارت درخواستی و…) و در صورت نیاز برنامه کلاسی مدرسه را اینجا تکمیل می‌کنی.'],
        ['el' => '[data-tour=step4]',    'title' => 'ساخت برنامه', 'text' => 'بعد از تکمیل مراحل، فقط ساعت مطالعه‌ی روزانه‌ات را انتخاب می‌کنی و برنامه‌ی اختصاصی‌ات همین‌جا ساخته می‌شود.'],
    ]" />
    @endif
</div>
