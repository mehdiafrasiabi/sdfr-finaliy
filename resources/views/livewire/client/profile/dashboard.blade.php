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
                           class="flex items-center justify-between p-4 rounded-2xl bg-green-950/40 border border-green-900/50">
                            <span class="font-bold text-sm text-green-400">{{ $unreadNotificationsCount }} پیام خوانده نشده</span>
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
                            @if($advisorStudent && $advisorStudent->picture)
                                <img src="{{ asset('adminsFile/' . $advisorStudent->id . '/' . $advisorStudent->picture) }}"
                                     alt="{{ $advisorStudent->name }}"
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
                                    {{ $advisorStudent ? $advisorStudent->name : 'تعیین نشده' }}
                                </div>
                                <div class="text-xs mt-1 text-blue-400">مشاور شما</div>
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
                                    $dayNum       = $currentDate->format('j');
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
                            {{-- موبایل: carousel با peek --}}
                            <div class="md:hidden">
                                <div class="program-carousel -mx-4 px-4">
                                    @foreach($todayProgram as $part)
                                        @php
                                            $minutes = $part->duration_minutes ?? round(($part->duration_hours ?? 0) * 60);
                                            $hours   = intdiv($minutes, 60);
                                            $mins    = $minutes % 60;
                                        @endphp
                                        <div class="program-card rounded-2xl bg-neutral-800/80 border border-neutral-700 p-4 flex flex-col items-end justify-center text-right min-h-[100px]">
                                            <div class="font-bold text-white text-[15px] leading-tight mb-2">
                                                {{ $part->lesson->name ?? 'درس' }}
                                            </div>
                                            <div class="flex items-baseline gap-2" style="direction:ltr;">
                                                @if($hours > 0)
                                                    <div class="flex items-baseline gap-1">
                                                        <span class="font-black text-white text-lg leading-none">{{ $hours }}</span>
                                                        <span class="text-[11px] text-neutral-500">ساعت</span>
                                                    </div>
                                                @endif
                                                @if($mins > 0)
                                                    <div class="flex items-baseline gap-1">
                                                        <span class="font-black text-white text-lg leading-none">{{ $mins }}</span>
                                                        <span class="text-[11px] text-neutral-500">دقیقه</span>
                                                    </div>
                                                @endif
                                                @if($hours == 0 && $mins == 0)
                                                    <span class="text-xs text-neutral-500">بدون زمان</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    {{-- spacer برای نمایش peek آخرین کارت --}}
                                    <div class="flex-shrink-0 w-4"></div>
                                </div>
                                {{-- نقطه‌های ناوبری (شمارنده) --}}
                                <div class="flex items-center justify-center gap-1.5 mt-3">
                                    @foreach($todayProgram as $idx => $p)
                                        <div class="w-1.5 h-1.5 rounded-full {{ $idx === 0 ? 'bg-blue-400' : 'bg-neutral-700' }}"></div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- دسکتاپ: scroll افقی بدون scrollbar با سایه فید --}}
                            <div class="hidden md:block relative">
                                {{-- سایه چپ (نشانه اسکرول) --}}
                                <div class="absolute left-0 top-0 bottom-0 w-12 bg-gradient-to-l from-transparent to-neutral-900/80 z-10 pointer-events-none rounded-l-xl"></div>
                                <div class="program-scroll-desktop">
                                    @foreach($todayProgram as $part)
                                        @php
                                            $minutes = $part->duration_minutes ?? round(($part->duration_hours ?? 0) * 60);
                                            $hours   = intdiv($minutes, 60);
                                            $mins    = $minutes % 60;
                                        @endphp
                                        <div class="flex-shrink-0 rounded-2xl bg-neutral-800/80 border border-neutral-700 p-4 flex flex-col items-end justify-center text-right"
                                             style="min-width:160px; min-height:100px;">
                                            <div class="font-bold text-white text-[15px] leading-tight mb-2">
                                                {{ $part->lesson->name ?? 'درس' }}
                                            </div>
                                            <div class="flex items-baseline gap-2" style="direction:ltr;">
                                                @if($hours > 0)
                                                    <div class="flex items-baseline gap-1">
                                                        <span class="font-black text-white text-lg leading-none">{{ $hours }}</span>
                                                        <span class="text-[11px] text-neutral-500">ساعت</span>
                                                    </div>
                                                @endif
                                                @if($mins > 0)
                                                    <div class="flex items-baseline gap-1">
                                                        <span class="font-black text-white text-lg leading-none">{{ $mins }}</span>
                                                        <span class="text-[11px] text-neutral-500">دقیقه</span>
                                                    </div>
                                                @endif
                                                @if($hours == 0 && $mins == 0)
                                                    <span class="text-xs text-neutral-500">بدون زمان</span>
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

                    {{-- ══════ 5) نمودار میانگین ساعت مطالعه ══════ --}}
                    <div class="rounded-2xl bg-neutral-900/80 border border-neutral-800 p-4">
                        <div class="flex items-center justify-start gap-2 mb-4">
                            <div class="w-7 h-7 rounded-lg bg-neutral-800 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#888" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75z"/>
                                </svg>
                            </div>
                            <span class="font-bold text-white text-[15px]">میانگین ساعت مطالعه</span>

                        </div>
                        <div class="h-[100px] relative">
                            <svg viewBox="0 0 300 80" class="w-full h-[80px]" preserveAspectRatio="none">
                                <defs>
                                    <linearGradient id="blueGrad2" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.25"/>
                                        <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                                <path d="M0,78 C25,72 45,58 75,52 C105,46 125,62 155,42 C185,22 215,32 248,18 C265,11 285,5 300,2 L300,80 L0,80 Z" fill="url(#blueGrad2)"/>
                                <path d="M0,78 C25,72 45,58 75,52 C105,46 125,62 155,42 C185,22 215,32 248,18 C265,11 285,5 300,2" fill="none" stroke="#3b82f6" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="0"   cy="78" r="2.5" fill="#3b82f6"/>
                                <circle cx="75"  cy="52" r="2.5" fill="#3b82f6"/>
                                <circle cx="155" cy="42" r="2.5" fill="#3b82f6"/>
                                <circle cx="248" cy="18" r="2.5" fill="#3b82f6"/>
                                <circle cx="300" cy="2"  r="2.5" fill="#3b82f6"/>
                            </svg>
                            @if($activeProgram)
                                <div class="flex justify-between mt-1">
                                    @php $sd2 = \Carbon\Carbon::parse($activeProgram->start_date); @endphp
                                    @for($i = 0; $i < 6; $i++)
                                        <span class="text-[11px] text-neutral-600">{{ $sd2->copy()->addDays($i)->day }}</span>
                                    @endfor
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ══════ 6) نمودار پیشرفت درصد آزمون ══════ --}}
                    <div class="rounded-2xl bg-neutral-900/80 border border-neutral-800 p-4 mb-20 md:mb-0">
                        <div class="flex items-center justify-start gap-2 mb-4">
                            <div class="w-7 h-7 rounded-lg bg-neutral-800 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#888" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/>
                                </svg>
                            </div>

                            <span class="font-bold text-white text-[15px]">پیشرفت درصد آزمون</span>
                        </div>
                        <div class="h-[90px]">
                            <svg viewBox="0 0 300 80" class="w-full h-[80px]" preserveAspectRatio="none">
                                <defs>
                                    <linearGradient id="yellowGrad2" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.3"/>
                                        <stop offset="100%" stop-color="#f59e0b" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                                <path d="M0,65 C35,60 55,72 95,55 C135,38 155,68 195,45 C225,27 258,42 300,18 L300,80 L0,80 Z" fill="url(#yellowGrad2)"/>
                                <path d="M0,65 C35,60 55,72 95,55 C135,38 155,68 195,45 C225,27 258,42 300,18" fill="none" stroke="#f59e0b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>

                </div>{{-- end grid --}}
            </div>{{-- end main --}}
        </div>{{-- end flex --}}
    </div>{{-- end container --}}
</div>
