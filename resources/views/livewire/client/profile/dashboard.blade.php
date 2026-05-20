<div class="min-h-screen bg-[#0a0a0a] text-white" dir="rtl" style="font-family: inherit;">
    <livewire:client.profile.update-notification />

    @push('styles')
        <style>
            .scrollbar-hide::-webkit-scrollbar { display: none; }
            .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        </style>
    @endpush

    {{-- LAYOUT: sidebar + main --}}
    <div class="max-w-7xl mx-auto px-4 py-6 relative z-10">
        <div class="flex gap-6 items-start">

            {{-- ===== SIDEBAR ===== --}}
            <div class="hidden md:block flex-shrink-0 w-[260px] sticky top-6">
                <livewire:client.profile.sidebar/>
            </div>

            {{-- ===== MAIN CONTENT ===== --}}
            <div class="flex-1 min-w-0 space-y-5">

                {{-- Trial/Notification Banners --}}
                @php
                    $trialWeek = \App\Models\TrialWeek::where('user_id', $user->id)->latest()->first();
                    $isTrialStudent = $student && $student->is_trial;
                    $showTrialBanner = !$student || $isTrialStudent;
                @endphp

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

                {{-- ===== 1) باکس مشاور ===== --}}
                <div class="rounded-2xl bg-neutral-900/80 border border-neutral-800 p-4 flex items-center justify-between">
                    {{-- چپ: دکمه فلش --}}
                    <button class="w-9 h-9 rounded-full bg-neutral-800/60 flex items-center justify-center hover:bg-neutral-700/60 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#888" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                        </svg>
                    </button>

                    {{-- راست: نام و عکس --}}
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <div class="font-bold text-white text-base leading-tight">
                                {{ $advisorStudent ? $advisorStudent->name : 'تعیین نشده' }}
                            </div>
                            <div class="text-xs mt-1 text-blue-400">مشاور شما</div>
                        </div>
                        @if($advisorStudent && $advisorStudent->picture)
                            <img src="{{ asset('adminsFile/' . $advisorStudent->id . '/' . $advisorStudent->picture) }}"
                                 alt="{{ $advisorStudent->name }}"
                                 class="w-12 h-12 rounded-full object-cover ring-2 ring-neutral-700">
                        @else
                            <div class="w-12 h-12 rounded-full bg-neutral-800 ring-2 ring-neutral-700 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#666" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ===== 2) باکس ارسال گزارش ===== --}}
                <div class="py-2">
                    {{-- هدر --}}
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-2xl font-black text-blue-400 tracking-tight" style="direction: ltr;">
                            {{ $reportProgress['submitted_days'] }}/{{ $reportProgress['total_days'] }}
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-white text-[15px]">ارسال گزارش</span>
                            <div class="w-7 h-7 rounded-lg bg-neutral-800 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#888" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- محاسبه روزهای هفته --}}
                    @php
                        $activeProgram = $this->getActiveWeeklyProgram();
                        $startDate = $activeProgram
                            ? \Carbon\Carbon::parse($activeProgram->start_date)
                            : \Carbon\Carbon::today()->subDays(2);
                        $endDate = $activeProgram
                            ? \Carbon\Carbon::parse($activeProgram->end_date)
                            : $startDate->copy()->addDays(6);
                        $today = \Carbon\Carbon::today();

                        // روزهایی که گزارش ارسال شده (با کوئری گرفته میشه)
                        $submittedDates = [];
                        if($activeProgram && $student) {
                            $submittedDates = \App\Models\DailyReport::where('student_id', $student->id)
                                ->where('weekly_program_id', $activeProgram->id)
                                ->where('is_compensatory', false)
                                ->whereBetween('report_date', [$startDate, $endDate])
                                ->pluck('report_date')
                                ->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString())
                                ->toArray();
                        }
                    @endphp

                    {{-- 7 دایره --}}
                    <div class="flex items-center gap-2">
                        @for($i = 0; $i < 7; $i++)
                            @php
                                $currentDate = $startDate->copy()->addDays($i);
                                $dayNum = $currentDate->format('j'); // روز عددی
                                $isToday = $currentDate->isSameDay($today);
                                $isPast = $currentDate->lt($today);
                                $isSubmitted = in_array($currentDate->toDateString(), $submittedDates);

                                // منطق رنگ:
                                // امروز که هنوز ارسال نشده -> قرمز (danger) - مطابق تصویر
                                // روز ارسال شده -> آبی (primary)
                                // روز گذشته که ارسال نشده -> قرمز کم‌رنگ
                                // روز آینده -> خاکستری
                                if($isSubmitted) {
                                    $bgClass = 'bg-blue-600/90 border-blue-500 text-white';
                                } elseif($isToday) {
                                    $bgClass = 'bg-red-900/60 border-red-700 text-red-200';
                                } elseif($isPast) {
                                    $bgClass = 'bg-red-950/40 border-red-900/50 text-red-300/70';
                                } else {
                                    $bgClass = 'bg-neutral-900 border-neutral-800 text-neutral-600';
                                }
                            @endphp
                            <div class="flex-1 aspect-square rounded-full border-2 flex items-center justify-center font-bold text-[13px] transition {{ $bgClass }}">
                                {{ $dayNum }}
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- ===== 3) باکس ساعت مطالعه ===== --}}
                <div class="py-2">
                    {{-- هدر --}}
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-xl font-black text-blue-400">
                            {{ $studyHoursProgress['total_hours'] }} ساعت
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-white text-[15px]">ساعت مطالعه</span>
                            <div class="w-7 h-7 rounded-lg bg-neutral-800 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#888" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- پروگرس --}}
                    <div class="w-full rounded-full h-[3px] bg-neutral-800 mb-3">
                        <div class="h-full rounded-full bg-blue-500 transition-all duration-700"
                             style="width: {{ $studyHoursProgress['percentage'] }}%;"></div>
                    </div>

                    <div class="flex items-center justify-between text-[13px]">
                        <div class="text-blue-400">{{ $studyHoursProgress['completed_hours'] }} از {{ $studyHoursProgress['total_hours'] }}</div>
                        <div class="text-neutral-500">{{ round($studyHoursProgress['percentage']) }}%</div>
                    </div>

                    @if($studyHoursProgress['extra_hours'] > 0)
                        <div class="mt-3 text-xs font-semibold px-3 py-2 rounded-xl inline-flex items-center gap-1.5 bg-green-950/50 text-green-400">
                            ⬆ {{ $studyHoursProgress['extra_hours'] }} ساعت اضافی! عالی هستی 🎉
                        </div>
                    @endif
                </div>

                {{-- ===== 4) باکس برنامه امروز ===== --}}
                <div class="py-2">
                    {{-- هدر --}}
                    <div class="flex items-center justify-end gap-2 mb-4">
                        <span class="font-bold text-white text-[15px]">برنامه امروز</span>
                        <div class="w-7 h-7 rounded-lg bg-neutral-800 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#888" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                        </div>
                    </div>

                    @if(count($todayProgram) > 0)
                        {{-- اسلایدر افقی برای همه سایزها --}}
                        <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide -mx-1 px-1"
                             x-data
                             x-init="$el.scrollLeft = $el.scrollWidth">
                            @foreach($todayProgram as $part)
                                @php
                                    $minutes = $part->duration_minutes ?? round(($part->duration_hours ?? 0) * 60);
                                    $hours = intdiv($minutes, 60);
                                    $mins = $minutes % 60;
                                @endphp
                                <div class="flex-shrink-0 rounded-2xl bg-neutral-900 border border-neutral-800 p-4 flex flex-col items-center justify-center text-center"
                                     style="min-width: 140px; min-height: 100px;">
                                    {{-- نام درس --}}
                                    <div class="font-bold text-white text-[15px] leading-tight mb-2">
                                        {{ $part->lesson->name ?? 'درس' }}
                                    </div>
                                    {{-- زمان: ساعت و دقیقه --}}
                                    <div class="flex items-baseline gap-2 mt-1">
                                        @if($hours > 0)
                                            <div class="flex items-baseline gap-1">
                                                <span class="font-black text-white text-lg leading-none" style="direction: ltr;">{{ $hours }}</span>
                                                <span class="text-[11px] text-neutral-500">ساعت</span>
                                            </div>
                                        @endif
                                        @if($mins > 0)
                                            <div class="flex items-baseline gap-1">
                                                <span class="font-black text-white text-lg leading-none" style="direction: ltr;">{{ $mins }}</span>
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
                    @else
                        <div class="text-center py-8 text-neutral-600 text-[13px]">برنامه‌ای برای امروز تعریف نشده</div>
                    @endif
                </div>

                {{-- ===== نمودار میانگین ساعت مطالعه ===== --}}
                <div class="py-5 border-t border-neutral-900">
                    <div class="flex items-center justify-end gap-2 mb-4">
                        <span class="font-bold text-white text-[15px]">میانگین ساعت مطالعه</span>
                        <div class="w-7 h-7 rounded-lg bg-neutral-800 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#888" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="h-[110px] relative">
                        <svg viewBox="0 0 300 90" class="w-full h-[90px]" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="blueGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.25"/>
                                    <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                            <path d="M0,78 C25,72 45,58 75,52 C105,46 125,62 155,42 C185,22 215,32 248,18 C265,11 285,5 300,2 L300,90 L0,90 Z" fill="url(#blueGrad)"/>
                            <path d="M0,78 C25,72 45,58 75,52 C105,46 125,62 155,42 C185,22 215,32 248,18 C265,11 285,5 300,2" fill="none" stroke="#3b82f6" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="0" cy="78" r="2.5" fill="#3b82f6"/>
                            <circle cx="75" cy="52" r="2.5" fill="#3b82f6"/>
                            <circle cx="155" cy="42" r="2.5" fill="#3b82f6"/>
                            <circle cx="248" cy="18" r="2.5" fill="#3b82f6"/>
                            <circle cx="300" cy="2" r="2.5" fill="#3b82f6"/>
                        </svg>
                        @if($activeProgram)
                            <div class="flex justify-between mt-1">
                                @php $sd = \Carbon\Carbon::parse($activeProgram->start_date); @endphp
                                @for($i = 0; $i < 6; $i++)
                                    <span class="text-[11px] text-neutral-600">{{ $sd->copy()->addDays($i)->day }}</span>
                                @endfor
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ===== نمودار پیشرفت درصد آزمون ===== --}}
                <div class="py-5 border-t border-neutral-900 pb-20">
                    <div class="flex items-center justify-end gap-2 mb-4">
                        <span class="font-bold text-white text-[15px]">پیشرفت درصد آزمون</span>
                        <div class="w-7 h-7 rounded-lg bg-neutral-800 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#888" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/>
                            </svg>
                        </div>
                    </div>
                    <div class="h-[90px]">
                        <svg viewBox="0 0 300 80" class="w-full h-[80px]" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="yellowGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.3"/>
                                    <stop offset="100%" stop-color="#f59e0b" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                            <path d="M0,65 C35,60 55,72 95,55 C135,38 155,68 195,45 C225,27 258,42 300,18 L300,80 L0,80 Z" fill="url(#yellowGrad)"/>
                            <path d="M0,65 C35,60 55,72 95,55 C135,38 155,68 195,45 C225,27 258,42 300,18" fill="none" stroke="#f59e0b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

            </div>{{-- end main --}}
        </div>{{-- end flex --}}
    </div>{{-- end container --}}

</div>
