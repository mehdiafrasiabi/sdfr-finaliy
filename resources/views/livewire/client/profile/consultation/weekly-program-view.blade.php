<div
    class="max-w-6xl mx-auto px-3 sm:px-4 py-4 sm:py-6 sdf-pattern-bg"
    dir="rtl"
    x-data="{ tab: 'grid' }"
>
    @push('link')
        <style>
            [x-cloak] {
                display: none !important;
            }

            /* پس‌زمینه‌ی لطیف شبیه دمو */
            .sdf-pattern-bg {
                background-color: #f8fafc;
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%233b82f6' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }

            /* گرادیان هدر شبیه SDFR دمو */
            .sdf-header-gradient {
                background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%);
            }

            /* سایه‌ی کارت نرم */
            .sdf-card-shadow {
                box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06),
                0 2px 8px rgba(15, 23, 42, 0.04);
            }

            /* سلول برنامه برای هوور */
            .sdf-schedule-cell {
                transition: all 0.18s ease;
            }

            .sdf-schedule-cell:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(15, 23, 42, 0.15);
            }

            /* برای دارک‌مود پس‌زمینه‌ی پترن کمی تیره‌تر شود */
            .dark .sdf-pattern-bg {
                background-color: #020617;
                background-image: none;
            }
        </style>
    @endpush

    {{-- هدر برنامه (استایل شبیه دمو) --}}
    <section
        class="mb-6 overflow-hidden rounded-2xl sdf-card-shadow border border-slate-100/80 bg-white/95
               dark:border-slate-800 dark:bg-slate-900/95"
    >
        <div class="sdf-header-gradient px-4 py-5 sm:px-6 sm:py-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                {{-- لوگو + عنوان --}}
                <div class="flex items-start gap-4">
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20
                               ring-2 ring-white/40 shadow-md backdrop-blur-sm"
                    >
                        <span class="text-xl font-extrabold tracking-[0.3em] text-white">
                           <img src="/client/assets/images/favicon.svg" class="w-10 h-10" alt=" لوگو SDFR" srcset="">
                        </span>
                    </div>

                    <div>
                        <h1 class="mb-1 text-lg sm:text-xl font-bold text-white">
                            برنامه هفتگی تحصیلی
                        </h1>
                        <p class="text-xs text-blue-100">به سبک SDFR</p>

                        <div
                            class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1 text-[11px] sm:text-xs text-blue-100/90"
                        >
                            <span class="flex items-center gap-1">
                                <i class="fas fa-calendar-alt text-sky-200"></i>
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
                                    <span class="font-semibold text-white">
                                  {{ $advisorName }}
                                    </span>
                                </span>
                            </span>
                            @if($supporterName && $supporterName !== '-')
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-headset text-violet-200"></i>
                                    <span>
                                        پشتیبان:
                                        <span class="font-semibold text-white">

                                            {{ $supporterName }}
                                        </span>
                                    </span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- سوییچ تب‌ها + دکمه برگشت --}}
                <div class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center">
                    <div
                        class="inline-flex items-center justify-between rounded-full bg-slate-950/10 p-1 text-[11px]
                               text-slate-50/80 ring-1 ring-white/20 backdrop-blur-sm
                               sm:text-xs"
                    >
                        <button
                            type="button"
                            @click="tab = 'grid'"
                            :class="tab === 'grid'
                                ? 'bg-white/95 text-slate-900 shadow-sm'
                                : 'bg-transparent text-slate-100/75'"
                            class="rounded-full px-3 py-1.5 transition"
                        >
                            جدول هفتگی
                        </button>
                        <button
                            type="button"
                            @click="tab = 'list'"
                            :class="tab === 'list'
                                ? 'bg-white/95 text-slate-900 shadow-sm'
                                : 'bg-transparent text-slate-100/75'"
                            class="rounded-full px-3 py-1.5 transition"
                        >
                            لیست تفصیلی
                        </button>
                    </div>

                    <a
                      wire:ignore=""  href="{{ route('client.profile.consultation.sessions') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/95 px-4 py-2
                               text-xs font-medium text-slate-900 shadow-sm ring-1 ring-white/50
                               hover:bg-slate-100
                               dark:bg-slate-900 dark:text-slate-50 dark:ring-slate-700 dark:hover:bg-slate-800"
                    >
                        <i class="fas fa-arrow-right text-slate-500 dark:text-slate-300"></i>
                        <span>بازگشت به جلسات</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- آمار کلی (استایل کارت‌های نرم شبیه دمو) --}}
    <section class="mb-6 grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-4">
        <div
            class="stat-card rounded-2xl bg-gradient-to-br from-sky-500 to-sky-600 p-4 text-white shadow-md
                   sm:p-5 dark:from-sky-400 dark:to-sky-500"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] text-sky-100/90">ساعات مطالعه</p>
                    <p class="mt-1 text-2xl font-extrabold sm:text-3xl">
                        {{ $stats['totalHours'] }}
                    </p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5">

                        <circle cx="10" cy="10" r="8" stroke-linecap="round" stroke-linejoin="round"></circle>

                        <path d="M10 5v5h4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div
            class="stat-card rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-4 text-white shadow-md
                   sm:p-5 dark:from-emerald-400 dark:to-emerald-500"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] text-emerald-100/90">تعداد تست</p>
                    <p class="mt-1 text-2xl font-extrabold sm:text-3xl">
                        {{ $stats['totalTests'] }}
                    </p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-foreground">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div
            class="stat-card rounded-2xl bg-gradient-to-br from-violet-500 to-violet-600 p-4 text-white shadow-md
                   sm:p-5 dark:from-violet-400 dark:to-violet-500"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] text-violet-100/90">تعداد پارت‌ها</p>
                    <p class="mt-1 text-2xl font-extrabold sm:text-3xl">
                        {{ $stats['totalParts'] }}
                    </p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" class="w-5 h-5"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div
            class="stat-card rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 p-4 text-white shadow-md
                   sm:p-5 dark:from-amber-400 dark:to-amber-500"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] text-amber-100/90">برنامه‌ریزی</p>
                    <p class="mt-1 text-2xl font-extrabold sm:text-3xl">
                        {{ $stats['totalPlans'] }}
                    </p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-foreground">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
    </section>



    {{-- جدول برنامه هفتگی (تب جدول) --}}
    <section
        x-show="tab === 'grid'"
        x-cloak
        class="rounded-2xl border border-slate-100/80 bg-white/95 shadow-sm backdrop-blur
               dark:border-slate-800 dark:bg-slate-900/95"
    >
        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-xs sm:text-[13px]">
                <thead>
                <tr class="bg-slate-50 text-[11px] text-slate-600 dark:bg-slate-800 dark:text-slate-200">
                    @foreach($weekDays as $day)
                        <th class="border-l border-slate-100 px-3 py-3 last:border-l-0 dark:border-slate-700 {{ $day['is_rest_day'] ? 'bg-emerald-50 dark:bg-emerald-900/20' : '' }}">

                            <div class="font-semibold {{ $day['is_rest_day'] ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-800 dark:text-slate-50' }}">
                                {{ $day['name'] }}
                                @if($day['is_rest_day'])
                                    <span class="block text-[9px] mt-1 px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-300 inline-block">استراحت</span>

                                @endif
                            </div>
                            <div class="mt-1 text-[11px] text-slate-500 dark:text-slate-300">
                                {{ $day['jalali_date'] }}
                            </div>
                        </th>
                    @endforeach
                </tr>
                </thead>

                <tbody>
                <tr>
                    @foreach($weekDays as $day)
                        <td
                            class="min-w-[110px] border-l border-slate-100 px-2 py-3 align-top last:border-l-0
                               dark:border-slate-700 {{ $day['is_rest_day'] ? 'bg-emerald-50/50 dark:bg-emerald-900/10' : '' }}"

                        >

                            @if($day['is_rest_day'])

                                <div class="text-center py-20">

                                    <div class="text-3xl mb-2">🌿</div>

                                    <div class="text-sm font-bold text-emerald-700 dark:text-emerald-400 mb-1">روز استراحت</div>

                                    <p class="text-[11px] text-emerald-600/80 dark:text-emerald-400/70">

                                        بدون برنامه مطالعاتی

                                    </p>

                                </div>

                            @else
                            <div class="space-y-2">
                                @forelse($day['parts'] as $part)
                                    <div
                                        class="sdf-schedule-cell rounded-xl border border-slate-100 bg-slate-50 p-2
                                               text-[11px] leading-relaxed shadow-sm
                                               {{ $part->color_class }}
                                               dark:border-slate-700 dark:bg-slate-800/80"
                                    >
                                        <div
                                            class="truncate text-[12px] font-semibold text-slate-800
                                                   dark:text-slate-50"
                                            title="{{ $part->lesson_name }}"
                                        >
                                            {{ $part->lesson_name }}
                                        </div>

                                        <div
                                            class="mt-1 flex flex-wrap items-center gap-2 text-[11px] text-slate-600
                                                   dark:text-slate-200"
                                        >
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

                                        @if($part->description)
                                            <div
                                                class="mt-1 truncate text-[11px] text-slate-500 dark:text-slate-300"
                                                title="{{ $part->description }}"
                                            >
                                                {{ Str::limit($part->description, 40) }}
                                            </div>
                                        @endif

                                        <div class="mt-1 flex flex-wrap items-center gap-1">
                                            @if($part->part_type === 'test')
                                                <span
                                                    class="rounded-full bg-sky-100 px-2 py-0.5 text-[10px] font-medium
                                                           text-sky-700 dark:bg-sky-900/60 dark:text-sky-200"
                                                >
                                                    تستی
                                                </span>
                                            @elseif($part->part_type === 'descriptive')
                                                <span
                                                    class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-medium
                                                           text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-200"
                                                >
                                                    تشریحی
                                                </span>
                                            @else
                                                <span
                                                    class="rounded-full bg-violet-100 px-2 py-0.5 text-[10px] font-medium
                                                           text-violet-700 dark:bg-violet-900/60 dark:text-violet-200"
                                                >
                                                    ویدیویی
                                                </span>
                                            @endif

                                            <span
                                                class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px]
                                                       text-slate-600 dark:bg-slate-800 dark:text-slate-200"
                                            >
                                                پایه
                                                @if($part->grade==10 )
                                                    دهم
                                                @elseif($part->grade==11 )
                                                    یازدهم
                                                @elseif($part->grade==12)
                                                    دوازدهم
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        class="py-4 text-center text-[11px] text-slate-400
                                               dark:text-slate-500"
                                    >
                                        بدون برنامه
                                    </div>
                                @endforelse
                            </div>

                            @if($day['parts']->count() > 0)
                                <div
                                    class="mt-3 border-t border-dashed border-slate-200 pt-2 text-[11px]
                                           text-slate-500 dark:border-slate-700 dark:text-slate-300"
                                >
                                    <div class="flex justify-between">
                                        <span>ساعت:</span>
                                        <span class="font-medium text-slate-700 dark:text-slate-100">
                                            {{ $day['total_hours'] }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>تست:</span>
                                        <span class="font-medium text-slate-700 dark:text-slate-100">
                                            {{ $day['total_tests'] }}
                                        </span>
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

    {{-- لیست تفصیلی پارت‌ها (تب لیست) --}}
    <section
        x-show="tab === 'list'"
        x-cloak
        class="mt-6 rounded-2xl border border-slate-100/80 bg-white/95 p-4 shadow-sm
               dark:border-slate-800 dark:bg-slate-900/95 sm:p-5"
    >
        <h3 class="mb-4 text-base font-semibold text-slate-900 dark:text-slate-50">
            لیست تفصیلی پارت‌ها
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-xs sm:text-[13px]">
                <thead>
                <tr class="bg-slate-50 text-slate-600 dark:bg-slate-800 dark:text-slate-200">
                    <th class="px-3 py-2 text-right">روز</th>
                    <th class="px-3 py-2 text-right">درس</th>
                    <th class="px-3 py-2 text-right">توضیحات</th>
                    <th class="px-3 py-2 text-center">مدت</th>
                    <th class="px-3 py-2 text-center">تست</th>
                    <th class="px-3 py-2 text-center">نوع پارت</th>
                    <th class="px-3 py-2 text-center">نوع درس</th>
                    <th class="px-3 py-2 text-center">پایه</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @php
                    $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

                @endphp

                @foreach($program->parts->sortBy(['day_of_week', 'part_order']) as $part)
                    @php
                        // محاسبه روز هفته واقعی از تاریخ part
                        $partJalaliDate = jdate($part->part_date);
                        $partDayOfWeek = $partJalaliDate->getDayOfWeek();
                        $partDayName = $jalaliDayNames[$partDayOfWeek];
                    @endphp
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/70">
                        <td class="px-3 py-2 text-[11px] text-slate-600 dark:text-slate-300">
                            {{ $partDayName }}
                            <span class="block text-[10px] text-slate-400">{{ $partJalaliDate->format('m/d') }}</span>
                        </td>
                        <td class="px-3 py-2 text-[11px] font-medium text-slate-800 dark:text-slate-100">
                            {{ $part->lesson_name }}
                        </td>
                        <td class="px-3 py-2 text-[11px] text-slate-600 dark:text-slate-300">
                            {{ $part->description ?? '-' }}
                        </td>
                        <td class="px-3 py-2 text-center text-[11px] text-slate-700 dark:text-slate-100">
                            {{ $part->duration_minutes }} دقیقه
                        </td>
                        <td class="px-3 py-2 text-center text-[11px] text-slate-700 dark:text-slate-100">
                            {{ $part->test_count ?? '-' }}
                        </td>
                        <td class="px-3 py-2 text-center">
                            @if($part->part_type === 'test')
                                <span
                                    class="rounded-full bg-sky-100 px-2 py-1 text-[10px] font-medium
                                           text-sky-700 dark:bg-sky-900/60 dark:text-sky-200"
                                >
                                    تستی
                                </span>
                            @elseif($part->part_type === 'descriptive')
                                <span
                                    class="rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-medium
                                           text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-200"
                                >
                                    تشریحی
                                </span>
                            @else
                                <span
                                    class="rounded-full bg-violet-100 px-2 py-1 text-[10px] font-medium
                                           text-violet-700 dark:bg-violet-900/60 dark:text-violet-200"
                                >
                                    ویدیویی
                                </span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            @if($part->lesson_type === 'general')
                                <span
                                    class="rounded-full bg-amber-100 px-2 py-1 text-[10px] font-medium
                                           text-amber-700 dark:bg-amber-900/60 dark:text-amber-200"
                                >
                                    عمومی
                                </span>
                            @else
                                <span
                                    class="rounded-full bg-pink-100 px-2 py-1 text-[10px] font-medium
                                           text-pink-700 dark:bg-pink-900/60 dark:text-pink-200"
                                >
                                    تخصصی
                                </span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center text-[11px] text-slate-700 dark:text-slate-100">
                          @if($part->grade==10 )
                              دهم
                            @elseif($part->grade==11 )
                              یازدهم
                            @elseif($part->grade==12)
                              دوازدهم
                          @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
        {{-- نمودارها (همان SVG ولی کارت‌استایل شده شبیه دمو) --}}
        <section class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            {{-- نوع پارت --}}
            <div
                class="rounded-2xl border border-slate-100/80 bg-white/95 p-4 shadow-sm
                   dark:border-slate-800 dark:bg-slate-900/95"
            >
                <h3 class="mb-3 text-center text-sm font-semibold text-slate-800 dark:text-slate-100">
                    توزیع نوع پارت
                </h3>

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
                                <circle
                                    cx="18" cy="18" r="15.9155"
                                    fill="none"
                                    stroke="#3B82F6"
                                    stroke-width="3"
                                    stroke-dasharray="{{ $testPercent }} {{ 100 - $testPercent }}"
                                    stroke-dashoffset="25"
                                    class="origin-center -rotate-90 transform"
                                />
                                <circle
                                    cx="18" cy="18" r="15.9155"
                                    fill="none"
                                    stroke="#10B981"
                                    stroke-width="3"
                                    stroke-dasharray="{{ $descPercent }} {{ 100 - $descPercent }}"
                                    stroke-dashoffset="{{ 25 - $testPercent }}"
                                    class="origin-center -rotate-90 transform"
                                />
                                <circle
                                    cx="18" cy="18" r="15.9155"
                                    fill="none"
                                    stroke="#8B5CF6"
                                    stroke-width="3"
                                    stroke-dasharray="{{ $videoPercent }} {{ 100 - $videoPercent }}"
                                    stroke-dashoffset="{{ 25 - $testPercent - $descPercent }}"
                                    class="origin-center -rotate-90 transform"
                                />
                            @endif
                        </svg>
                    </div>
                </div>

                <div class="mt-3 flex flex-wrap justify-center gap-3 text-[11px] text-slate-600 dark:text-slate-300">
                <span class="flex items-center gap-1">
                    <span class="h-2 w-2 rounded-full bg-sky-500"></span>
                    تستی ({{ $stats['testParts'] }})
                </span>
                    <span class="flex items-center gap-1">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    تشریحی ({{ $stats['descriptiveParts'] }})
                </span>
                    <span class="flex items-center gap-1">
                    <span class="h-2 w-2 rounded-full bg-violet-500"></span>
                    ویدیویی ({{ $stats['videoParts'] }})
                </span>
                </div>
            </div>

            {{-- نوع درس --}}
            <div
                class="rounded-2xl border border-slate-100/80 bg-white/95 p-4 shadow-sm
                   dark:border-slate-800 dark:bg-slate-900/95"
            >
                <h3 class="mb-3 text-center text-sm font-semibold text-slate-800 dark:text-slate-100">
                    توزیع نوع درس
                </h3>

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
                                <circle
                                    cx="18" cy="18" r="15.9155"
                                    fill="none"
                                    stroke="#F59E0B"
                                    stroke-width="3"
                                    stroke-dasharray="{{ $generalPercent }} {{ 100 - $generalPercent }}"
                                    stroke-dashoffset="25"
                                    class="origin-center -rotate-90 transform"
                                />
                                <circle
                                    cx="18" cy="18" r="15.9155"
                                    fill="none"
                                    stroke="#EC4899"
                                    stroke-width="3"
                                    stroke-dasharray="{{ $specPercent }} {{ 100 - $specPercent }}"
                                    stroke-dashoffset="{{ 25 - $generalPercent }}"
                                    class="origin-center -rotate-90 transform"
                                />
                            @endif
                        </svg>
                    </div>
                </div>

                <div class="mt-3 flex flex-wrap justify-center gap-3 text-[11px] text-slate-600 dark:text-slate-300">
                <span class="flex items-center gap-1">
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                    عمومی ({{ $stats['generalParts'] }})
                </span>
                    <span class="flex items-center gap-1">
                    <span class="h-2 w-2 rounded-full bg-pink-500"></span>
                    تخصصی ({{ $stats['specializedParts'] }})
                </span>
                </div>
            </div>

            {{-- پایه تحصیلی --}}
            <div
                class="rounded-2xl border border-slate-100/80 bg-white/95 p-4 shadow-sm
                   dark:border-slate-800 dark:bg-slate-900/95"
            >
                <h3 class="mb-3 text-center text-sm font-semibold text-slate-800 dark:text-slate-100">
                    توزیع پایه تحصیلی
                </h3>

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
                                <circle
                                    cx="18" cy="18" r="15.9155"
                                    fill="none"
                                    stroke="#06B6D4"
                                    stroke-width="3"
                                    stroke-dasharray="{{ $g10Percent }} {{ 100 - $g10Percent }}"
                                    stroke-dashoffset="25"
                                    class="origin-center -rotate-90 transform"
                                />
                                <circle
                                    cx="18" cy="18" r="15.9155"
                                    fill="none"
                                    stroke="#84CC16"
                                    stroke-width="3"
                                    stroke-dasharray="{{ $g11Percent }} {{ 100 - $g11Percent }}"
                                    stroke-dashoffset="{{ 25 - $g10Percent }}"
                                    class="origin-center -rotate-90 transform"
                                />
                                <circle
                                    cx="18" cy="18" r="15.9155"
                                    fill="none"
                                    stroke="#EF4444"
                                    stroke-width="3"
                                    stroke-dasharray="{{ $g12Percent }} {{ 100 - $g12Percent }}"
                                    stroke-dashoffset="{{ 25 - $g10Percent - $g11Percent }}"
                                    class="origin-center -rotate-90 transform"
                                />
                            @endif
                        </svg>
                    </div>
                </div>

                <div class="mt-3 flex flex-wrap justify-center gap-3 text-[11px] text-slate-600 dark:text-slate-300">
                <span class="flex items-center gap-1">
                    <span class="h-2 w-2 rounded-full bg-cyan-500"></span>
                    دهم ({{ $stats['grade10Parts'] }})
                </span>
                    <span class="flex items-center gap-1">
                    <span class="h-2 w-2 rounded-full bg-lime-500"></span>
                    یازدهم ({{ $stats['grade11Parts'] }})
                </span>
                    <span class="flex items-center gap-1">
                    <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                    دوازدهم ({{ $stats['grade12Parts'] }})
                </span>
                </div>
            </div>
        </section>
</div>
