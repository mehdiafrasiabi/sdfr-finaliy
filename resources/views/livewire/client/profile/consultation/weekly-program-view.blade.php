<div
    class="max-w-6xl mx-auto px-3 sm:px-4 py-4 sm:py-6"
    dir="rtl"
    x-data="{ tab: 'grid' }"
>
    {{-- هدر برنامه --}}
    <section class="mb-6 overflow-hidden rounded-2xl border border-border bg-secondary shadow-[0_4px_20px_rgba(15,23,42,0.06),0_2px_8px_rgba(15,23,42,0.04)]">
        <div class="bg-gradient-to-r from-blue-900 via-blue-600 to-blue-400 px-4 py-5 sm:px-6 sm:py-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                {{-- لوگو + عنوان --}}
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 ring-2 ring-white/40 shadow-md backdrop-blur-sm">
                        <img src="/client/assets/images/favicon.svg" class="w-10 h-10" alt="لوگو SDFR">
                    </div>

                    <div>
                        <h1 class="mb-1 text-lg sm:text-xl font-bold text-white">برنامه هفتگی تحصیلی</h1>
                        <p class="text-xs text-blue-100">به سبک SDFR</p>

                        <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1 text-[11px] sm:text-xs text-blue-100/90">
                            <span class="flex items-center gap-1">

                                <span>
                                    {{ jdate($program->start_date)->format('Y/m/d') }}
                                    تا
                                    {{ jdate($program->end_date)->format('Y/m/d') }}
                                </span>
                            </span>

                            <span class="flex items-center gap-1">
                                <i class="fas fa-user-tie text-emerald-200"></i>
                                <span>
                                    مشاور:
                                    <span class="font-semibold text-white">{{ $advisorName }}</span>
                                </span>
                            </span>

                            @if($supporterName && $supporterName !== '-')
                                <span class="flex items-center gap-1">
                                    <span>
                                        پشتیبان:
                                        <span class="font-semibold text-white">{{ $supporterName }}</span>
                                    </span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- سوییچ تب‌ها + دکمه برگشت --}}
                <div class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center">
                    <a wire:navigate="" href="{{ route('client.profile.consultation.sessions') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-500 px-4 py-2
                              text-xs font-medium text-white shadow-sm ring-1
                              hover:bg-red transition-all">
                        <i class="fas fa-arrow-right text-slate-500"></i>
                        <span>بازگشت به جلسات</span>
                    </a>
                    <div class="inline-flex items-center justify-between rounded-full bg-black/10 p-1 text-[11px] text-white/80 ring-1 ring-white/20 backdrop-blur-sm sm:text-xs">
                        <button
                            type="button"
                            @click="tab = 'grid'"
                            :class="tab === 'grid' ? 'bg-white/95 text-slate-900 shadow-sm' : 'bg-transparent text-white/75'"
                            class="rounded-full px-3 py-1.5 transition"
                        >
                            جدول هفتگی
                        </button>
                        <button
                            type="button"
                            @click="tab = 'list'"
                            :class="tab === 'list' ? 'bg-white/95 text-slate-900 shadow-sm' : 'bg-transparent text-white/75'"
                            class="rounded-full px-3 py-1.5 transition"
                        >
                            لیست تفصیلی
                        </button>
                    </div>


                </div>
            </div>
        </div>
    </section>

    {{-- آمار کلی --}}
    <section class="mb-6 grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-4">
        <div class="rounded-2xl bg-gradient-to-br from-sky-500 to-sky-600 p-4 text-white shadow-md sm:p-5 dark:from-sky-500 dark:to-sky-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] text-sky-100/90">ساعات مطالعه</p>
                    <p class="mt-1 text-2xl font-extrabold sm:text-3xl">{{ $stats['totalHours'] }}</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5">
                        <circle cx="10" cy="10" r="8" stroke-linecap="round" stroke-linejoin="round"></circle>
                        <path d="M10 5v5h4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-4 text-white shadow-md sm:p-5 dark:from-emerald-500 dark:to-emerald-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] text-emerald-100/90">تعداد تست</p>
                    <p class="mt-1 text-2xl font-extrabold sm:text-3xl">{{ $stats['totalTests'] }}</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-gradient-to-br from-violet-500 to-violet-600 p-4 text-white shadow-md sm:p-5 dark:from-violet-500 dark:to-violet-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] text-violet-100/90">تعداد پارت‌ها</p>
                    <p class="mt-1 text-2xl font-extrabold sm:text-3xl">{{ $stats['totalParts'] }}</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 p-4 text-white shadow-md sm:p-5 dark:from-amber-500 dark:to-amber-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] text-amber-100/90">برنامه‌ریزی</p>
                    <p class="mt-1 text-2xl font-extrabold sm:text-3xl">{{ $stats['totalPlans'] }}</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    {{-- جدول برنامه هفتگی --}}
    <section
        x-show="tab === 'grid'"
        x-cloak
        class="rounded-2xl border border-border bg-secondary shadow-sm"
    >
        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-xs sm:text-[13px]">
                <thead>
                <tr class="bg-muted/50 text-[11px] text-muted">
                    @foreach($weekDays as $day)
                        <th class="border-l border-border px-3 py-3 last:border-l-0 {{ $day['is_rest_day'] ? 'bg-emerald-50/80 dark:bg-emerald-900/20' : '' }}" style="background-color: #2b2b31;">
                            <div class="font-semibold {{ $day['is_rest_day'] ? 'text-emerald-700 dark:text-emerald-400' : 'text-foreground' }}" >
                                {{ $day['name'] }}
                                @if($day['is_rest_day'])
                                    <span class="block text-[9px] mt-1 px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-300 inline-block">استراحت</span>
                                @endif
                            </div>
                            <div class="mt-1 text-[11px] text-muted">
                                {{ $day['jalali_date'] }}
                            </div>
                        </th>
                    @endforeach
                </tr>
                </thead>

                <tbody>
                <tr>
                    @foreach($weekDays as $day)
                        <td class="min-w-[385px] border-l border-border px-2 py-3 align-top last:border-l-0
                                       {{ $day['is_rest_day'] ? 'bg-emerald-50/30 dark:bg-emerald-900/10' : '' }}">

                            @if($day['is_rest_day'])
                                    <div class="flex flex-col items-center justify-center py-6">
                                        <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 p-5 w-full text-center">
                                            <div class="w-12 h-12 mx-auto rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center mb-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-emerald-600 dark:text-emerald-400">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                                                </svg>
                                            </div>
                                            <div class="text-sm font-bold text-emerald-700 dark:text-emerald-400 mb-1">روز استراحت</div>
                                            <p class="text-[11px] text-emerald-600/80 dark:text-emerald-400/70">از امروز خود لذت ببرید</p>
                                        </div>
                                    </div>
                            @else
                                <div class="space-y-2">
                                    @forelse($day['parts'] as $part)
                                        @php
                                            $isExam = in_array($part->part_type, ['comprehensive_exam', 'exam_analysis']);
                                            $isComprehensive = $part->part_type === 'comprehensive_exam';
                                            $isAnalysis = $part->part_type === 'exam_analysis';
                                        @endphp

                                        {{-- کارت عادی --}}
                                        @if(!$isExam)
                                            <div class="rounded-xl border border-border p-2 text-[11px] leading-relaxed shadow-sm min-h-[130px]"
                                                 style="background-color: #2b2b31;">

                                                {{-- نام درس --}}
                                                <div class="truncate text-[12px] font-semibold text-foreground" title="{{ $part->lesson_name }}">
                                                    {{ $part->lesson_name }}
                                                </div>

                                                {{-- مدت + تعداد تست --}}
                                                <div class="mt-1 flex flex-wrap items-center gap-2 text-[11px] text-muted">
                <span class="flex items-center gap-1">
                    <i class="fas fa-clock text-[10px]"></i>
                    {{ $part->duration_minutes }} دقیقه
                </span>
                                                    @if($part->test_count)
                                                        <span class="flex items-center gap-1">
                        <i class="fas fa-tasks text-[10px]"></i>
                        {{ $part->test_count }}
                    </span>
                                                    @endif
                                                </div>

                                                {{-- توضیحات --}}
                                                @if($part->description)
                                                    <div class="mt-1 text-[11px] text-muted h-[32px] overflow-hidden" title="{{ $part->description }}">
                                                        {{ Str::limit($part->description, 300) }}
                                                    </div>
                                                @else
                                                    <div class="mt-1 h-[32px]"></div>
                                                @endif

                                                {{-- بج‌ها --}}
                                                <div class="mt-1 flex flex-wrap items-center gap-1">
                                                    @if($part->part_type === 'test')
                                                        <span class="rounded-full bg-sky-100 px-2 py-0.5 text-[10px] font-medium text-sky-700 dark:bg-sky-900/60 dark:text-sky-200">تستی</span>
                                                    @elseif($part->part_type === 'descriptive')
                                                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-medium text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-200">تشریحی</span>
                                                    @else
                                                        <span class="rounded-full bg-violet-100 px-2 py-0.5 text-[10px] font-medium text-violet-700 dark:bg-violet-900/60 dark:text-violet-200">ویدیویی</span>
                                                    @endif

                                                    <span class="rounded-full bg-muted px-2 py-0.5 text-[10px] text-muted">
                    پایه
                    @if($part->grade == 10) دهم
                                                        @elseif($part->grade == 11) یازدهم
                                                        @elseif($part->grade == 12) دوازدهم
                                                        @endif
                </span>

                                                    @if($part->source_type && $part->source_type !== 'normal')
                                                        <span class="rounded-full px-2 py-0.5 text-[10px] font-medium {{ $part->source_type_tw_class }}">{{ $part->source_type_label }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- کارت آزمون جامع --}}
                                        @elseif($isComprehensive)
                                            <div class="rounded-xl overflow-hidden border border-red-400 dark:border-red-700 shadow-md min-h-[130px]">
                                                {{-- هدر رنگی --}}
                                                <div class="bg-gradient-to-r from-red-600 to-red-500 px-3 py-2 flex items-center gap-2">
                                                    <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-white/20">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-white">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                                        </svg>
                                                    </div>
                                                    <span class="text-[11px] font-bold text-white tracking-wide">آزمون جامع</span>
                                                    <span class="mr-auto text-[10px] text-red-100 flex items-center gap-1">
                    <i class="fas fa-clock text-[9px]"></i>
                    {{ $part->duration_minutes }} دقیقه
                </span>
                                                </div>

                                                {{-- بدنه --}}
                                                <div class="bg-red-50 dark:bg-red-900/20 px-3 py-2">
                                                    <div class="truncate text-[12px] font-semibold text-red-800 dark:text-red-300" title="{{ $part->lesson_name }}">
                                                        {{ $part->lesson_name }}
                                                    </div>

                                                    @if($part->test_count)
                                                        <div class="mt-1 flex items-center gap-1 text-[11px] text-red-600 dark:text-red-400">
                                                            <i class="fas fa-tasks text-[10px]"></i>
                                                            <span>{{ $part->test_count }} تست</span>
                                                        </div>
                                                    @endif

                                                    @if($part->description)
                                                        <div class="mt-1 text-[11px] text-red-700/70 dark:text-red-400/70 line-clamp-2" title="{{ $part->description }}">
                                                            {{ Str::limit($part->description, 300) }}
                                                        </div>
                                                    @endif

                                                    <div class="mt-2 flex flex-wrap items-center gap-1">
                                                        <span class="rounded-full bg-red-200 dark:bg-red-800/60 px-2 py-0.5 text-[10px] font-medium text-red-700 dark:text-red-200">آزمون جامع</span>
                                                        <span class="rounded-full bg-red-100 dark:bg-red-900/40 px-2 py-0.5 text-[10px] text-red-600 dark:text-red-300">
                        پایه
                        @if($part->grade == 10) دهم
                                                            @elseif($part->grade == 11) یازدهم
                                                            @elseif($part->grade == 12) دوازدهم
                                                            @endif
                    </span>
                                                        @if($part->source_type && $part->source_type !== 'normal')
                                                            <span class="rounded-full px-2 py-0.5 text-[10px] font-medium {{ $part->source_type_tw_class }}">{{ $part->source_type_label }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- کارت تحلیل آزمون --}}
                                        @elseif($isAnalysis)
                                            <div class="rounded-xl overflow-hidden border border-orange-400 dark:border-orange-700 shadow-md min-h-[130px]">
                                                {{-- هدر رنگی --}}
                                                <div class="bg-gradient-to-r from-orange-500 to-amber-500 px-3 py-2 flex items-center gap-2">
                                                    <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-white/20">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-white">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                                                        </svg>
                                                    </div>
                                                    <span class="text-[11px] font-bold text-white tracking-wide">تحلیل آزمون</span>
                                                    <span class="mr-auto text-[10px] text-orange-100 flex items-center gap-1">
                    <i class="fas fa-clock text-[9px]"></i>
                    {{ $part->duration_minutes }} دقیقه
                </span>
                                                </div>

                                                {{-- بدنه --}}
                                                <div class="bg-orange-50 dark:bg-orange-900/20 px-3 py-2">
                                                    <div class="truncate text-[12px] font-semibold text-orange-800 dark:text-orange-300" title="{{ $part->lesson_name }}">
                                                        {{ $part->lesson_name }}
                                                    </div>

                                                    @if($part->test_count)
                                                        <div class="mt-1 flex items-center gap-1 text-[11px] text-orange-600 dark:text-orange-400">
                                                            <i class="fas fa-tasks text-[10px]"></i>
                                                            <span>{{ $part->test_count }} تست</span>
                                                        </div>
                                                    @endif

                                                    @if($part->description)
                                                        <div class="mt-1 text-[11px] text-orange-700/70 dark:text-orange-400/70 line-clamp-2" title="{{ $part->description }}">
                                                            {{ Str::limit($part->description, 300) }}
                                                        </div>
                                                    @endif

                                                    <div class="mt-2 flex flex-wrap items-center gap-1">
                                                        <span class="rounded-full bg-orange-200 dark:bg-orange-800/60 px-2 py-0.5 text-[10px] font-medium text-orange-700 dark:text-orange-200">تحلیل آزمون</span>
                                                        <span class="rounded-full bg-orange-100 dark:bg-orange-900/40 px-2 py-0.5 text-[10px] text-orange-600 dark:text-orange-300">
                        پایه
                        @if($part->grade == 10) دهم
                                                            @elseif($part->grade == 11) یازدهم
                                                            @elseif($part->grade == 12) دوازدهم
                                                            @endif
                    </span>
                                                        @if($part->source_type && $part->source_type !== 'normal')
                                                            <span class="rounded-full px-2 py-0.5 text-[10px] font-medium {{ $part->source_type_tw_class }}">{{ $part->source_type_label }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    @empty
                                        <div class="py-4 text-center text-[11px] text-muted">بدون برنامه</div>
                                    @endforelse
                                </div>

                                @if($day['parts']->count() > 0)
                                    <div class="mt-3 border-t border-dashed border-border pt-2 text-[11px] text-muted">
                                        <div class="flex justify-between">
                                            <span>ساعت:</span>
                                            <span class="font-medium text-foreground">{{ $day['total_hours'] }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>تست:</span>
                                            <span class="font-medium text-foreground">{{ $day['total_tests'] }}</span>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </td>
                    @endforeach
                </tr>
                </tbody>
            </table>
        </div>
    </section>

    {{-- لیست تفصیلی پارت‌ها --}}
    <section
        x-show="tab === 'list'"
        x-cloak
        class="mt-6 rounded-2xl border border-border bg-secondary p-4 shadow-sm sm:p-5"
    >
        <h3 class="mb-4 text-base font-semibold text-foreground">لیست تفصیلی پارت‌ها</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-xs sm:text-[13px]">
                <thead>
                <tr class="bg-muted/50 text-muted">
                    <th class="px-3 py-2 text-right">روز</th>
                    <th class="px-3 py-2 text-right">درس</th>
                    <th class="px-3 py-2 text-right">توضیحات</th>
                    <th class="px-3 py-2 text-center">مدت</th>
                    <th class="px-3 py-2 text-center">تست</th>
                    <th class="px-3 py-2 text-center">نوع پارت</th>
                    <th class="px-3 py-2 text-center">نوع درس</th>
                    <th class="px-3 py-2 text-center">پایه</th>
                    <th class="px-3 py-2 text-center">منبع</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-border">
                @php
                    $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
                @endphp

                @foreach($program->parts->sortBy(['day_of_week', 'part_order']) as $part)
                    @php
                        $partJalaliDate = jdate($part->part_date);
                        $partDayOfWeek = $partJalaliDate->getDayOfWeek();
                        $partDayName = $jalaliDayNames[$partDayOfWeek];
                    @endphp
                    <tr class="hover:bg-muted/30 transition-colors {{ in_array($part->part_type, ['comprehensive_exam', 'exam_analysis']) ? 'bg-red-50/50 dark:bg-red-900/10' : '' }}">

                    <td class="px-3 py-2 text-[11px] text-muted">
                            {{ $partDayName }}
                            <span class="block text-[10px] text-muted/70">{{ $partJalaliDate->format('m/d') }}</span>
                        </td>
                        <td class="px-3 py-2 text-[11px] font-medium {{ in_array($part->part_type, ['comprehensive_exam', 'exam_analysis']) ? 'text-red-700 dark:text-red-400' : 'text-foreground' }}">{{ $part->lesson_name }}@if($part->ccChapter)({{ $part->ccChapter->name }})@endif</td>
                        <td class="px-3 py-2 text-[11px] text-muted max-w-[200px]">
                            @if($part->ccTopic)
                                <span class="text-primary font-medium">{{ $part->ccTopic->name }}</span>
                                @if($part->description) - @endif
                            @endif
                            {{ Str::limit($part->description, 100) ?? '-' }}
                        </td>
                        <td class="px-3 py-2 text-center text-[11px] text-foreground">{{ $part->duration_minutes }} دقیقه</td>
                        <td class="px-3 py-2 text-center text-[11px] text-foreground">{{ $part->test_count ?? '-' }}</td>
                        <td class="px-3 py-2 text-center">
                            @if($part->part_type === 'test')
                                <span class="rounded-full bg-sky-100 px-2 py-1 text-[10px] font-medium text-sky-700 dark:bg-sky-900/60 dark:text-sky-200">تستی</span>
                            @elseif($part->part_type === 'descriptive')
                                <span class="rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-medium text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-200">تشریحی</span>
                            @elseif($part->part_type === 'comprehensive_exam')
                                <span class="rounded-full bg-red-100 px-2 py-1 text-[10px] font-medium text-red-700 dark:bg-red-900/60 dark:text-red-200">آزمون جامع</span>
                            @elseif($part->part_type === 'exam_analysis')
                                <span class="rounded-full bg-orange-100 px-2 py-1 text-[10px] font-medium text-orange-700 dark:bg-orange-900/60 dark:text-orange-200">تحلیل آزمون</span>

                            @else
                                <span class="rounded-full bg-violet-100 px-2 py-1 text-[10px] font-medium text-violet-700 dark:bg-violet-900/60 dark:text-violet-200">ویدیویی</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            @if($part->lesson_type === 'general')
                                <span class="rounded-full bg-amber-100 px-2 py-1 text-[10px] font-medium text-amber-700 dark:bg-amber-900/60 dark:text-amber-200">عمومی</span>
                            @else
                                <span class="rounded-full bg-pink-100 px-2 py-1 text-[10px] font-medium text-pink-700 dark:bg-pink-900/60 dark:text-pink-200">تخصصی</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center text-[11px] text-foreground">
                            @if($part->grade == 10) دهم
                            @elseif($part->grade == 11) یازدهم
                            @elseif($part->grade == 12) دوازدهم
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            @if($part->source_type && $part->source_type !== 'normal')
                                <span class="rounded-full px-2 py-1 text-[10px] font-medium {{ $part->source_type_tw_class }}">{{ $part->source_type_label }}</span>
                            @else
                                <span class="text-[10px] text-muted">عادی</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- نمودارها --}}
    <section class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">

        {{-- نوع پارت --}}
        <div class="rounded-2xl border border-border bg-secondary p-4 shadow-sm">
            <h3 class="mb-3 text-center text-sm font-semibold text-foreground">توزیع نوع پارت</h3>
            <div class="flex items-center justify-center">
                <div class="relative h-32 w-32 sm:h-36 sm:w-36">
                    @php
                        $total = $stats['testParts'] + $stats['descriptiveParts'] + $stats['videoParts'];
                        $testPercent = $total > 0 ? ($stats['testParts'] / $total) * 100 : 0;
                        $descPercent = $total > 0 ? ($stats['descriptiveParts'] / $total) * 100 : 0;
                        $videoPercent = $total > 0 ? ($stats['videoParts'] / $total) * 100 : 0;
                    @endphp
                    <svg viewBox="0 0 36 36" class="h-full w-full">
                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                        @if($total > 0)
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#3B82F6" stroke-width="3"
                                    stroke-dasharray="{{ $testPercent }} {{ 100 - $testPercent }}"
                                    stroke-dashoffset="25" class="origin-center -rotate-90 transform"/>
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#10B981" stroke-width="3"
                                    stroke-dasharray="{{ $descPercent }} {{ 100 - $descPercent }}"
                                    stroke-dashoffset="{{ 25 - $testPercent }}" class="origin-center -rotate-90 transform"/>
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#8B5CF6" stroke-width="3"
                                    stroke-dasharray="{{ $videoPercent }} {{ 100 - $videoPercent }}"
                                    stroke-dashoffset="{{ 25 - $testPercent - $descPercent }}" class="origin-center -rotate-90 transform"/>
                        @endif
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex flex-wrap justify-center gap-3 text-[11px] text-muted">
                <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-sky-500"></span>تستی ({{ $stats['testParts'] }})</span>
                <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>تشریحی ({{ $stats['descriptiveParts'] }})</span>
                <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-violet-500"></span>ویدیویی ({{ $stats['videoParts'] }})</span>
            </div>
        </div>

        {{-- نوع درس --}}
        <div class="rounded-2xl border border-border bg-secondary p-4 shadow-sm">
            <h3 class="mb-3 text-center text-sm font-semibold text-foreground">توزیع نوع درس</h3>
            <div class="flex items-center justify-center">
                <div class="relative h-32 w-32 sm:h-36 sm:w-36">
                    @php
                        $totalLesson = $stats['generalParts'] + $stats['specializedParts'];
                        $generalPercent = $totalLesson > 0 ? ($stats['generalParts'] / $totalLesson) * 100 : 0;
                        $specPercent = $totalLesson > 0 ? ($stats['specializedParts'] / $totalLesson) * 100 : 0;
                    @endphp
                    <svg viewBox="0 0 36 36" class="h-full w-full">
                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                        @if($totalLesson > 0)
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#F59E0B" stroke-width="3"
                                    stroke-dasharray="{{ $generalPercent }} {{ 100 - $generalPercent }}"
                                    stroke-dashoffset="25" class="origin-center -rotate-90 transform"/>
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#EC4899" stroke-width="3"
                                    stroke-dasharray="{{ $specPercent }} {{ 100 - $specPercent }}"
                                    stroke-dashoffset="{{ 25 - $generalPercent }}" class="origin-center -rotate-90 transform"/>
                        @endif
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex flex-wrap justify-center gap-3 text-[11px] text-muted">
                <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-amber-500"></span>عمومی ({{ $stats['generalParts'] }})</span>
                <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-pink-500"></span>تخصصی ({{ $stats['specializedParts'] }})</span>
            </div>
        </div>

        {{-- پایه تحصیلی --}}
        <div class="rounded-2xl border border-border bg-secondary p-4 shadow-sm">
            <h3 class="mb-3 text-center text-sm font-semibold text-foreground">توزیع پایه تحصیلی</h3>
            <div class="flex items-center justify-center">
                <div class="relative h-32 w-32 sm:h-36 sm:w-36">
                    @php
                        $totalGrade = $stats['grade10Parts'] + $stats['grade11Parts'] + $stats['grade12Parts'];
                        $g10Percent = $totalGrade > 0 ? ($stats['grade10Parts'] / $totalGrade) * 100 : 0;
                        $g11Percent = $totalGrade > 0 ? ($stats['grade11Parts'] / $totalGrade) * 100 : 0;
                        $g12Percent = $totalGrade > 0 ? ($stats['grade12Parts'] / $totalGrade) * 100 : 0;
                    @endphp
                    <svg viewBox="0 0 36 36" class="h-full w-full">
                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                        @if($totalGrade > 0)
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#06B6D4" stroke-width="3"
                                    stroke-dasharray="{{ $g10Percent }} {{ 100 - $g10Percent }}"
                                    stroke-dashoffset="25" class="origin-center -rotate-90 transform"/>
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#84CC16" stroke-width="3"
                                    stroke-dasharray="{{ $g11Percent }} {{ 100 - $g11Percent }}"
                                    stroke-dashoffset="{{ 25 - $g10Percent }}" class="origin-center -rotate-90 transform"/>
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#EF4444" stroke-width="3"
                                    stroke-dasharray="{{ $g12Percent }} {{ 100 - $g12Percent }}"
                                    stroke-dashoffset="{{ 25 - $g10Percent - $g11Percent }}" class="origin-center -rotate-90 transform"/>
                        @endif
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex flex-wrap justify-center gap-3 text-[11px] text-muted">
                <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-cyan-500"></span>دهم ({{ $stats['grade10Parts'] }})</span>
                <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-lime-500"></span>یازدهم ({{ $stats['grade11Parts'] }})</span>
                <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-rose-500"></span>دوازدهم ({{ $stats['grade12Parts'] }})</span>
            </div>
        </div>
        {{-- منبع پارت --}}

        <div class="rounded-2xl border border-border bg-secondary p-4 shadow-sm">
            <h3 class="mb-3 text-center text-sm font-semibold text-foreground">توزیع منبع پارت</h3>
            <div class="flex items-center justify-center">
                <div class="relative h-32 w-32 sm:h-36 sm:w-36">
                    <svg viewBox="0 0 36 36" class="h-full w-full">
                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                        @if(count($sourceTypeStats) > 0)
                            @php $srcOffset = 25; @endphp
                            @foreach($sourceTypeStats as $stat)
                                @if($stat['percent'] > 0)
                                    <circle cx="18" cy="18" r="15.9155" fill="none" stroke="{{ $stat['color'] }}" stroke-width="3"
                                            stroke-dasharray="{{ $stat['percent'] }} {{ 100 - $stat['percent'] }}"
                                            stroke-dashoffset="{{ $srcOffset }}" class="origin-center -rotate-90 transform"/>
                                    @php $srcOffset -= $stat['percent']; @endphp
                                @endif
                            @endforeach
                        @endif
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex flex-wrap justify-center gap-3 text-[11px] text-muted">
                @foreach($sourceTypeStats as $stat)
                    <span class="flex items-center gap-1">
                        <span class="h-2 w-2 rounded-full" style="background-color: {{ $stat['color'] }}"></span>
                        {{ $stat['label'] }} ({{ $stat['count'] }})
                    </span>
                @endforeach
            </div>
        </div>
    </section>
</div>
