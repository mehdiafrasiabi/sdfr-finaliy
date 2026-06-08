<div class="min-h-screen text-white" dir="rtl" style="font-family: inherit;">
    <livewire:client.profile.update-notification />

    @push('link')
        <style>
            .scrollbar-hide::-webkit-scrollbar { display: none; }
            .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

            /* كارت carousel موبایل */
            .program-carousel {
                display: flex;
                gap: 12px;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                padding-left: 16px; /* peek: تیکه‌ای از کارت بعدی */
            }
            .program-carousel::-webkit-scrollbar { display: none; }
            .program-carousel .program-card {
                flex-shrink: 0;
                scroll-snap-align: start;
                width: calc(75vw - 32px); /* عرض کارت + peek */
                max-width: 240px;
            }
            /* دسکتاپ: scroll افقی بدون scrollbar */
            .program-scroll-desktop {
                display: flex;
                gap: 12px;
                overflow-x: auto;
                scrollbar-width: none;
                -ms-overflow-style: none;
                padding-bottom: 4px;
            }
            .program-scroll-desktop::-webkit-scrollbar { display: none; }
        </style>
    @endpush

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
                        <div class="rounded-2xl p-4 bg-green-950/40 border border-green-900/50">
                            <div class="flex items-center justify-between gap-3">
                                <livewire:client.profile.trial-week.start />
                                <div class="text-right">
                                    <div class="font-bold text-white text-sm">یک هفته آزمایشی رایگان</div>
                                    <div class="text-xs mt-1 text-green-400/80">برنامه شخصی • پشتیبان اختصاصی</div>
                                </div>
                            </div>
                        </div>
                    @elseif($trialWeek)
                        <a wire:navigate href="{{ route('client.profile.trial.guide') }}"
                           class="flex items-center justify-between p-4 rounded-2xl bg-green-950/40 border border-green-900/50">
                            <div class="flex items-center gap-2">
                                @php $sp = ($trialWeek->step / 4) * 100; @endphp
                                <div class="w-14 h-1 rounded-full overflow-hidden bg-green-900/50">
                                    <div class="h-full rounded-full bg-green-400" style="width:{{ $sp }}%;"></div>
                                </div>
                                <span class="text-xs text-green-400">{{ (int)$sp }}%</span>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-white text-sm">هفته آزمایشی — {{ $trialWeek->statusLabel }}</div>
                                <div class="text-xs mt-0.5 text-neutral-500">
                                    @if($trialWeek->isExpired())<span class="text-red-400">منقضی شده</span>
                                    @else{{ $trialWeek->daysRemaining }} روز باقی‌مانده@endif
                                </div>
                            </div>
                        </a>
                    @endif

                    @if($student && !$isTrialStudent && $unreadNotificationsCount > 0)
                        <a wire:navigate href="{{ route('client.profile.notification') }}"
                           class="flex items-center justify-between p-4 rounded-2xl bg-yellow-950/40 border border-yellow-900/50">
                            <span class="font-bold text-sm text-yellow-400">{{ $unreadNotificationsCount }} پیام خوانده نشده</span>
                            <span class="text-sm text-white">مشاهده پیام‌ها ←</span>
                        </a>
                    @endif
                </div>

                {{-- ════════════════════════════════════════════
                     گرید اصلی:
                     موبایل → یک ستون
                     دسکتاپ → دو ستون (هر باکس یک سلول)
                     باکس‌های عریض (برنامه امروز، نمودارها) → col-span-2
                ════════════════════════════════════════════ --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- ══════ 1) مشاور ══════ --}}
                    <div class="md:col-span-2 rounded-2xl bg-neutral-900/80 border border-neutral-800 p-4 flex items-center justify-between gap-4">
                        {{-- راست: عکس + نام --}}
                        <div class="flex items-center gap-3">
                            @if($advisorStudent && !empty($advisorStudent['picture']))
                                <img src="{{ asset('adminsFile/' . $advisorStudent['id'] . '/' . $advisorStudent['picture']) }}"
                                     alt="{{ $advisorStudent['name'] }}"
                                     class="w-12 h-12 rounded-full object-cover ring-2 ring-neutral-700 flex-shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-full bg-neutral-800 ring-2 ring-neutral-700 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#666" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="text-right">
                                <div class="font-bold text-white text-base leading-tight">
                                    {{ $advisorStudent['name'] ?? 'تعیین نشده' }}
                                </div>
                                <div class="text-xs mt-1 text-blue-400">{{ $advisorStudent['label'] ?? 'مشاور شما' }}</div>
                            </div>
                        </div>
                        {{-- چپ: دکمه فلش --}}
                        <button class="w-9 h-9 rounded-full bg-neutral-800/60 flex items-center justify-center hover:bg-neutral-700/60 transition flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#888" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                            </svg>
                        </button>
                    </div>

                    {{-- ══════ 2) ارسال گزارش ══════ --}}
                    <div class="rounded-2xl bg-neutral-900/80 border border-neutral-800 p-4">
                        {{-- هدر --}}
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-neutral-800 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#888" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                    </svg>
                                </div>
                                <span class="font-bold text-white text-[15px]">ارسال گزارش</span>
                            </div>
                            <div class="text-2xl font-black text-blue-400 tracking-tight" style="direction:ltr;">
                                {{ $reportProgress['submitted_days'] }}/{{ $reportProgress['total_days'] }}
                            </div>

                        </div>

                        {{-- روزهای هفته --}}
                        @php
                            $activeProgram = $this->getActiveWeeklyProgram();
                            $startDate = $activeProgram
                                ? \Carbon\Carbon::parse($activeProgram->start_date)
                                : \Carbon\Carbon::today()->subDays(2);
                            $endDate = $activeProgram
                                ? \Carbon\Carbon::parse($activeProgram->end_date)
                                : $startDate->copy()->addDays(6);
                            $today = \Carbon\Carbon::today();

                            $submittedDates = [];
                            $restDayIndices = []; // ایندکس روزهای استراحت

                            if ($activeProgram && $student) {
                                $submittedDates = \App\Models\DailyReport::where('student_id', $student->id)
                                    ->where('weekly_program_id', $activeProgram->id)
                                    ->where('is_compensatory', false)
                                    ->whereBetween('report_date', [$startDate, $endDate])
                                    ->pluck('report_date')
                                    ->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString())
                                    ->toArray();

                                // روزهای بدون پارت = روز استراحت
                                for ($ri = 0; $ri < 7; $ri++) {
                                    $hasParts = $activeProgram->parts()->where('day_of_week', $ri)->exists();
                                    if (!$hasParts) $restDayIndices[] = $ri;
                                }
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
                                        // روز استراحت → سبز
                                        $cls = 'bg-green-600/80 border-green-500 text-white';
                                    } elseif ($isSubmitted) {
                                        // گزارش ارسال شده → primary (آبی)
                                        $cls = 'bg-primary border-primary text-white';
                                    } elseif ($isToday && !$isSubmitted) {
                                        // امروز ارسال نشده → danger قرمز پررنگ
                                        $cls = 'bg-red-700/80 border-red-600 text-white';
                                    } elseif ($isPast && !$isSubmitted) {
                                        // گذشته ارسال نشده → danger کم‌رنگ
                                        $cls = 'bg-red-950/60 border-red-800 text-red-300/80';
                                    } else {
                                        // آینده → خاکستری
                                        $cls = 'bg-neutral-900 border-neutral-700 text-neutral-500';
                                    }
                                @endphp
                                <div class="flex-1 aspect-square rounded-full border-2 flex items-center justify-center font-bold text-[12px] transition {{ $cls }}">
                                    {{ $dayNum }}
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- ══════ 3) ساعت مطالعه ══════ --}}
                    <div class="rounded-2xl bg-neutral-900/80 border border-neutral-800 p-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-neutral-800 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#888" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>

                                <span class="font-bold text-white text-[15px]">ساعت مطالعه</span>
                            </div>
                            <div class="text-xl font-black text-blue-400">
                                {{ $studyHoursProgress['total_hours'] }} ساعت
                            </div>

                        </div>
                        <div class="w-full rounded-full h-[3px] bg-neutral-800 mb-3">
                            <div class="h-full rounded-full bg-blue-500 transition-all duration-700"
                                 style="width: {{ $studyHoursProgress['percentage'] }}%;"></div>
                        </div>
                        <div class="flex items-center justify-between text-[13px]">
                            <div class="text-neutral-500">{{ round($studyHoursProgress['percentage']) }}%</div>
                            <div class="text-blue-400">{{ $studyHoursProgress['completed_hours'] }} از {{ $studyHoursProgress['total_hours'] }}</div>
                        </div>
                        @if($studyHoursProgress['extra_hours'] > 0)
                            <div class="mt-3 text-xs font-semibold px-3 py-2 rounded-xl inline-flex items-center gap-1.5 bg-green-950/50 text-green-400">
                                ⬆ {{ $studyHoursProgress['extra_hours'] }} ساعت اضافی! عالی هستی 🎉
                            </div>
                        @endif
                    </div>

                    {{-- ══════ 4) برنامه امروز (full-width) ══════ --}}
                    {{-- ══════ 4) برنامه امروز (full-width) ══════ --}}
                    <div class="md:col-span-2 rounded-2xl bg-neutral-900/80 border border-neutral-800 p-4">
                        <div class="flex items-center justify-start gap-2 mb-4">
                            <div class="w-7 h-7 rounded-lg bg-neutral-800 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#888" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                </svg>
                            </div>
                            <span class="font-bold text-white text-[15px]">برنامه امروز</span>
                        </div>

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
                        // در RTL، scrollLeft منفی یا برعکس میشه — از scrollIntoView استفاده می‌کنیم
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
                                        <div class="program-card rounded-2xl bg-neutral-800/80 border border-neutral-700 px-4 py-3 flex items-center justify-between gap-3 min-h-[72px]">
                                            {{-- راست: اسم درس (اول در DOM = راست در RTL) --}}
                                            <div class="font-bold text-white text-[15px] leading-tight text-right">
                                                {{ $part->lesson_name ?? ($part->lesson->name ?? 'درس') }}
                                            </div>
                                            {{-- چپ: آمار --}}
                                            <div class="flex items-center gap-4" style="direction:ltr;">
                                                @if($minutes > 0)
                                                    <div class="flex flex-col items-center leading-tight">
                                                        <span class="font-black text-white text-base">{{ $minutes }}</span>
                                                        <span class="text-[10px] text-neutral-500 mt-0.5">دقیقه</span>
                                                    </div>
                                                @endif
                                                @if($testsCount > 0)
                                                    <div class="flex flex-col items-center leading-tight">
                                                        <span class="font-black text-white text-base">{{ $testsCount }}</span>
                                                        <span class="text-[10px] text-neutral-500 mt-0.5">تست</span>
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
                                                :class="active === {{ $idx }} ? 'bg-blue-400 w-4' : 'bg-neutral-700 w-1.5'"
                                                class="h-1.5 rounded-full transition-all duration-300"></button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- دسکتاپ: scroll افقی --}}
                            <div class="hidden md:block relative">
                                <div class="absolute left-0 top-0 bottom-0 w-12 bg-gradient-to-l from-transparent to-neutral-900/80 z-10 pointer-events-none rounded-l-xl"></div>
                                <div class="program-scroll-desktop">
                                    @foreach($todayProgram as $part)
                                        @php
                                            $minutes = $part->duration_minutes ?? round(($part->duration_hours ?? 0) * 60);
                                            $testsCount = $part->tests_count ?? $part->test_count ?? 0;
                                        @endphp
                                        <div class="flex-shrink-0 rounded-2xl bg-neutral-800/80 border border-neutral-700 px-4 py-3 flex items-center justify-between gap-4"
                                             style="min-width:220px; min-height:72px;">
                                            {{-- راست: اسم درس --}}
                                            <div class="font-bold text-white text-[15px] leading-tight text-right">
                                                {{ $part->lesson_name ?? ($part->lesson->name ?? 'درس') }}
                                            </div>
                                            {{-- چپ: آمار --}}
                                            <div class="flex items-center gap-4" style="direction:ltr;">
                                                @if($minutes > 0)
                                                    <div class="flex flex-col items-center leading-tight">
                                                        <span class="font-black text-white text-base">{{ $minutes }}</span>
                                                        <span class="text-[10px] text-neutral-500 mt-0.5">دقیقه</span>
                                                    </div>
                                                @endif
                                                @if($testsCount > 0)
                                                    <div class="flex flex-col items-center leading-tight">
                                                        <span class="font-black text-white text-base">{{ $testsCount }}</span>
                                                        <span class="text-[10px] text-neutral-500 mt-0.5">تست</span>
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
                            <div class="text-center py-8 text-neutral-600 text-[13px]">برنامه‌ای برای امروز تعریف نشده</div>
                        @endif
                    </div>

                    {{-- ══════ 5) آمار کلی هفته (stat cards) ══════ --}}
                    @php
                        $fmtHm = function ($seconds) {
                            $seconds = max(0, (int) $seconds);
                            $h = intdiv($seconds, 3600);
                            $m = intdiv($seconds % 3600, 60);
                            if ($h > 0 && $m > 0) return $h . ' ساعت ' . $m . ' دقیقه';
                            if ($h > 0) return $h . ' ساعت';
                            return $m . ' دقیقه';
                        };
                        $wi = $weeklyInsights;
                    @endphp
                    <div class="md:col-span-2 rounded-2xl bg-neutral-900/80 border border-neutral-800 p-4">
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-neutral-800 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#888" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/>
                                    </svg>
                                </div>
                                <span class="font-bold text-white text-[15px]">خلاصه عملکرد این هفته</span>
                            </div>
                            @if($wi['has_data'])
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full
                                    {{ $wi['completion_percent'] >= 70 ? 'bg-green-950/60 text-green-400' : ($wi['completion_percent'] >= 40 ? 'bg-amber-950/60 text-amber-400' : 'bg-red-950/60 text-red-400') }}">
                                    {{ $wi['completion_percent'] }}% انجام برنامه
                                </span>
                            @endif
                        </div>

                        @if(!$wi['has_data'])
                            <div class="text-center py-8 text-neutral-600 text-[13px]">برنامه‌ای برای این هفته ثبت نشده است.</div>
                        @else
                            @php
                                $statTiles = [
                                    ['label' => 'پارت‌های برنامه', 'value' => $wi['parts_total'], 'sub' => $wi['parts_studied'] . ' انجام شده', 'color' => 'text-blue-400'],
                                    ['label' => 'ساعت مطالعه', 'value' => $fmtHm($wi['study_seconds']), 'sub' => 'از ' . round($wi['planned_minutes'] / 60, 1) . ' ساعت برنامه', 'color' => 'text-indigo-400'],
                                    ['label' => 'تست انجام شده', 'value' => $wi['tests_done'], 'sub' => 'برنامه: ' . $wi['tests_planned'], 'color' => 'text-violet-400'],
                                    ['label' => 'مطالعه باکیفیت', 'value' => $wi['quality']['عالی'] + $wi['quality']['با کیفیت'], 'sub' => $wi['quality']['بی‌کیفیت'] . ' بی‌کیفیت', 'color' => 'text-emerald-400'],
                                ];
                            @endphp
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                                @foreach($statTiles as $tile)
                                    <div class="rounded-xl bg-neutral-800/60 border border-neutral-700/60 p-3">
                                        <div class="text-[11px] text-neutral-500 mb-1">{{ $tile['label'] }}</div>
                                        <div class="text-lg font-black {{ $tile['color'] }} leading-tight">{{ $tile['value'] }}</div>
                                        <div class="text-[10px] text-neutral-500 mt-1">{{ $tile['sub'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- ══════ 6) نمودار مطالعه روزانه این هفته ══════ --}}
                    <div class="rounded-2xl bg-neutral-900/80 border border-neutral-800 p-4">
                        <div class="flex items-center justify-between gap-2 mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-neutral-800 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#888" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75z"/>
                                    </svg>
                                </div>
                                <span class="font-bold text-white text-[15px]">مطالعه روزانه</span>
                            </div>
                            <div class="flex items-center gap-2 text-[10px] text-neutral-400">
                                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-sm" style="background:#3b82f6"></span> برنامه</span>
                                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-sm" style="background:#10b981"></span> مطالعه</span>
                            </div>
                        </div>
                        @if($wi['has_data'])
                            <div class="relative" style="height:170px">
                                <canvas id="dash-chart-daily"></canvas>
                            </div>
                        @else
                            <div class="text-center py-10 text-neutral-600 text-[13px]">داده‌ای برای نمایش نیست.</div>
                        @endif
                    </div>

                    {{-- ══════ 7) نمودار توزیع نوع پارت ══════ --}}
                    <div class="rounded-2xl bg-neutral-900/80 border border-neutral-800 p-4">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-7 h-7 rounded-lg bg-neutral-800 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#888" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                                </svg>
                            </div>
                            <span class="font-bold text-white text-[15px]">توزیع نوع پارت</span>
                        </div>
                        @if($wi['has_data'] && !empty($wi['part_type_distribution']))
                            <div class="relative" style="height:170px">
                                <canvas id="dash-chart-parttype"></canvas>
                            </div>
                        @else
                            <div class="text-center py-10 text-neutral-600 text-[13px]">داده‌ای برای نمایش نیست.</div>
                        @endif
                    </div>

                    {{-- ══════ 8) پیشرفت دروس این هفته (progress bars) ══════ --}}
                    <div class="md:col-span-2 rounded-2xl bg-neutral-900/80 border border-neutral-800 p-4">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-7 h-7 rounded-lg bg-neutral-800 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#888" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h12M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5"/>
                                </svg>
                            </div>
                            <span class="font-bold text-white text-[15px]">پیشرفت دروس این هفته</span>
                        </div>
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
                                            <span class="text-[13px] font-semibold text-white truncate">{{ $subj['name'] }}</span>
                                            <span class="text-[11px] text-neutral-500 whitespace-nowrap">{{ $subj['parts_studied'] }}/{{ $subj['parts_total'] }} پارت · <span class="{{ $txtColor }} font-bold">{{ $pct }}%</span></span>
                                        </div>
                                        <div class="w-full h-2 rounded-full bg-neutral-800 overflow-hidden">
                                            <div class="h-full {{ $barColor }} rounded-full transition-all duration-700" style="width: {{ min(100, $pct) }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-neutral-600 text-[13px]">درسی برای نمایش پیشرفت وجود ندارد.</div>
                        @endif
                    </div>

                    {{-- ══════ 9) نمودار پیشرفت این ماه ══════ --}}
                    @php $mi = $monthlyInsights; @endphp
                    <div class="md:col-span-2 rounded-2xl bg-neutral-900/80 border border-neutral-800 p-4 mb-20 md:mb-0">
                        <div class="flex items-center justify-between flex-wrap gap-2 mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-neutral-800 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#888" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/>
                                    </svg>
                                </div>
                                <span class="font-bold text-white text-[15px]">پیشرفت این ماه</span>
                                @if($mi['month_label'])
                                    <span class="text-xs text-neutral-500">{{ $mi['month_label'] }}</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 text-[10px] text-neutral-400">
                                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-sm" style="background:#6366f1"></span> برنامه</span>
                                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-sm" style="background:#f59e0b"></span> مطالعه</span>
                            </div>
                        </div>

                        @if($mi['has_data'])
                            {{-- خلاصه ماه --}}
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                                <div class="rounded-xl bg-neutral-800/60 border border-neutral-700/60 p-3">
                                    <div class="text-[11px] text-neutral-500 mb-1">مجموع ساعت مطالعه</div>
                                    <div class="text-lg font-black text-amber-400" style="direction:ltr">{{ $mi['total_study_hours'] }}</div>
                                </div>
                                <div class="rounded-xl bg-neutral-800/60 border border-neutral-700/60 p-3">
                                    <div class="text-[11px] text-neutral-500 mb-1">انجام برنامه</div>
                                    <div class="text-lg font-black text-emerald-400">{{ $mi['completion_percent'] }}%</div>
                                </div>
                                <div class="rounded-xl bg-neutral-800/60 border border-neutral-700/60 p-3">
                                    <div class="text-[11px] text-neutral-500 mb-1">پارت‌های انجام‌شده</div>
                                    <div class="text-lg font-black text-blue-400">{{ $mi['total_parts_studied'] }}</div>
                                </div>
                                <div class="rounded-xl bg-neutral-800/60 border border-neutral-700/60 p-3">
                                    <div class="text-[11px] text-neutral-500 mb-1">گزارش‌های ارسالی</div>
                                    <div class="text-lg font-black text-green-400">{{ $mi['reports_sent'] }}</div>
                                </div>
                            </div>
                            <div class="relative" style="height:200px">
                                <canvas id="dash-chart-monthly"></canvas>
                            </div>
                        @else
                            <div class="text-center py-10 text-neutral-600 text-[13px]">هنوز برنامه‌ای در این ماه ثبت نشده است.</div>
                        @endif
                    </div>

                </div>{{-- end grid --}}
            </div>{{-- end main --}}
        </div>{{-- end flex --}}
    </div>{{-- end container --}}

    {{-- ════════════════ CHART INITIALIZATION ════════════════ --}}
    @push('script')
        <script>
            (function () {
                const palette = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316', '#06b6d4', '#a855f7', '#84cc16'];

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
                        data: { labels: labels, datasets: [{ data: values, backgroundColor: labels.map((_, i) => palette[i % palette.length]), borderWidth: 2, borderColor: '#171717' }] },
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
                                { label: 'برنامه', data: d.map(x => x.planned_hours), backgroundColor: '#6366f1', borderRadius: 6, maxBarThickness: 34 },
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

                // پس از به‌روزرسانی Livewire (مثلاً تغییر داده‌ها) چارت‌ها را بازسازی کن
                document.addEventListener('livewire:update', () => {
                    requestAnimationFrame(() => window.__dashInitCharts && window.__dashInitCharts());
                });
            })();
        </script>
    @endpush
</div>
