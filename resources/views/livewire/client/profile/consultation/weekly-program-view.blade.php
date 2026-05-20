<div
    class="max-w-6xl mx-auto px-3 sm:px-4 py-4 sm:py-6"
    dir="rtl"
    x-data="{
        tab: 'grid',
        currentDayIndex: 0,
        totalDays: {{ count($weekDays) }},
        listSort: 'day',
        listLoading: false,

        get currentDay() {
            return this.currentDayIndex;
        },
        goNext() {
            if (this.currentDayIndex < this.totalDays - 1) {
                this.currentDayIndex++;
            }
        },
        goPrev() {
            if (this.currentDayIndex > 0) {
                this.currentDayIndex--;
            }
        },
        changeSort(val) {
            this.listLoading = true;
            this.listSort = val;
            setTimeout(() => { this.listLoading = false; }, 400);
        }
    }"
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
                        </div>
                    </div>
                </div>

                {{-- سوییچ تب‌ها + دکمه برگشت --}}
                <div class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center">
                    <a wire:navigate="" href="{{ route('client.profile.plan') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-500 px-4 py-2
                              text-xs font-medium text-white shadow-sm ring-1
                              hover:bg-red transition-all">
                        <i class="fas fa-arrow-right text-slate-200"></i>
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
        <div class="rounded-2xl bg-gradient-to-br from-sky-500 to-sky-600 p-4 text-white shadow-md sm:p-5">
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
        <div class="rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-4 text-white shadow-md sm:p-5">
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
        <div class="rounded-2xl bg-gradient-to-br from-violet-500 to-violet-600 p-4 text-white shadow-md sm:p-5">
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
        <div class="rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 p-4 text-white shadow-md sm:p-5">
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

    {{-- ==================== جدول هفتگی ==================== --}}
    <section
        x-show="tab === 'grid'"
        x-cloak
        class="rounded-2xl border border-border bg-secondary shadow-sm"
    >
        {{-- ناوبری موبایل (فقط روی موبایل نمایش داده می‌شه) --}}
        <div class="flex md:hidden items-center gap-2 px-3 py-3 border-b border-border bg-muted/30 rounded-t-2xl">
            {{-- دکمه روز قبل --}}
            <button
                type="button"
                @click="goPrev()"
                :disabled="currentDayIndex === 0"
                :class="currentDayIndex === 0 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-primary/10 active:scale-95'"
                class="flex items-center justify-center w-9 h-9 rounded-xl border border-border bg-secondary text-foreground transition-all shrink-0"
                title="روز قبل"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                </svg>
            </button>

            {{-- Select روز --}}
            <div class="flex-1 relative">
                <select
                    x-model.number="currentDayIndex"
                    class="w-full appearance-none rounded-xl border border-border bg-secondary text-foreground text-[12px] font-medium px-3 py-2 pr-3 pl-7 focus:outline-none focus:ring-2 focus:ring-primary/30 cursor-pointer"
                >
                    @foreach($weekDays as $i => $day)
                        <option value="{{ $i }}">
                            {{ $day['name'] }} — {{ $day['jalali_date'] }}
                            @if($day['is_rest_day']) (استراحت) @endif
                        </option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute left-2 top-1/2 -translate-y-1/2 text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </div>
            </div>

            {{-- دکمه روز بعد --}}
            <button
                type="button"
                @click="goNext()"
                :disabled="currentDayIndex === totalDays - 1"
                :class="currentDayIndex === totalDays - 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-primary/10 active:scale-95'"
                class="flex items-center justify-center w-9 h-9 rounded-xl border border-border bg-secondary text-foreground transition-all shrink-0"
                title="روز بعد"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </button>
        </div>

        {{-- نشانگر پیشرفت روز (موبایل) --}}
        <div class="flex md:hidden items-center justify-center gap-1.5 px-3 py-2 border-b border-border/50">
            @foreach($weekDays as $i => $day)
                <button
                    type="button"
                    @click="currentDayIndex = {{ $i }}"
                    :class="currentDayIndex === {{ $i }}
                        ? '{{ $day['is_rest_day'] ? 'bg-emerald-500 w-4' : 'bg-blue-500 w-4' }}'
                        : 'bg-border w-1.5'"
                    class="h-1.5 rounded-full transition-all duration-300"
                ></button>
            @endforeach
        </div>

        {{-- =================== دسکتاپ: جدول کامل =================== --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full min-w-[720px] text-xs sm:text-[13px]">
                <thead>
                <tr class="text-[11px] text-muted">
                    @foreach($weekDays as $day)
                        <th class="border-l border-border px-3 py-3 last:border-l-0 {{ $day['is_rest_day'] ? 'bg-emerald-50/80 dark:bg-emerald-900/20' : '' }}" style="background-color: #2b2b31;">
                            <div class="font-semibold {{ $day['is_rest_day'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-foreground' }}">
                                {{ $day['name'] }}
                                @if($day['is_rest_day'])
                                    <span class="block text-[9px] mt-1 px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-300 inline-block">استراحت</span>
                                @endif
                            </div>
                            <div class="mt-1 text-[11px] text-muted">{{ $day['jalali_date'] }}</div>
                        </th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                <tr>
                    @foreach($weekDays as $day)
                        <td class="min-w-[385px] border-l border-border px-2 py-3 align-top last:border-l-0
                                   {{ $day['is_rest_day'] ? 'bg-emerald-50/30 dark:bg-emerald-900/10' : '' }}">
                            @include('livewire.client.profile.consultation.partials.day-parts-content', ['day' => $day])
                        </td>
                    @endforeach
                </tr>
                </tbody>
            </table>
        </div>

        {{-- =================== موبایل: نمایش تک روز =================== --}}
        <div class="block md:hidden">
            @foreach($weekDays as $i => $day)
                <div
                    x-show="currentDayIndex === {{ $i }}"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="px-3 py-3"
                >
                    {{-- هدر روز در موبایل --}}
                    <div class="mb-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-6 rounded-full {{ $day['is_rest_day'] ? 'bg-emerald-500' : 'bg-blue-500' }}"></div>
                            <div>
                                <span class="text-sm font-bold text-foreground">{{ $day['name'] }}</span>
                                <span class="text-[11px] text-muted mr-2">{{ $day['jalali_date'] }}</span>
                            </div>
                            @if($day['is_rest_day'])
                                <span class="rounded-full text-[10px] px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300">استراحت</span>
                            @endif
                        </div>
                        @if(!$day['is_rest_day'] && $day['parts']->count() > 0)
                            <div class="text-[11px] text-muted text-left">
                                <span class="font-medium text-foreground">{{ $day['total_hours'] }}</span> ساعت ·
                                <span class="font-medium text-foreground">{{ $day['total_tests'] }}</span> تست
                            </div>
                        @endif
                    </div>

                    @include('livewire.client.profile.consultation.partials.day-parts-content', ['day' => $day])
                </div>
            @endforeach
        </div>
    </section>

    {{-- ==================== لیست تفصیلی ==================== --}}
    <section
        x-show="tab === 'list'"
        x-cloak
        class="mt-6 rounded-2xl border border-border bg-secondary p-4 shadow-sm sm:p-5"
    >
        {{-- هدر + فیلتر --}}
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h3 class="text-base font-semibold text-foreground">لیست تفصیلی پارت‌ها</h3>

            {{-- دکمه‌های مرتب‌سازی --}}
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-[11px] text-muted shrink-0">مرتب‌سازی:</span>
                <div class="inline-flex rounded-xl border border-border bg-muted/30 p-0.5 gap-0.5 flex-wrap">
                    <button
                        type="button"
                        @click="changeSort('day')"
                        :class="listSort === 'day'
                            ? 'bg-blue-500 text-white shadow-sm'
                            : 'text-muted hover:text-foreground hover:bg-muted/50'"
                        class="rounded-lg px-2.5 py-1.5 text-[11px] font-medium transition-all"
                    >
                        <i class="fas fa-calendar-alt ml-1"></i>
                        روزهای برنامه
                    </button>
                    <button
                        type="button"
                        @click="changeSort('reading')"
                        :class="listSort === 'reading'
                            ? 'bg-teal-500 text-white shadow-sm'
                            : 'text-muted hover:text-foreground hover:bg-muted/50'"
                        class="rounded-lg px-2.5 py-1.5 text-[11px] font-medium transition-all"
                    >
                        <i class="fas fa-book-open ml-1"></i>
                        پیش/روزخوانی
                    </button>
                    <button
                        type="button"
                        @click="changeSort('exam')"
                        :class="listSort === 'exam'
                            ? 'bg-red-500 text-white shadow-sm'
                            : 'text-muted hover:text-foreground hover:bg-muted/50'"
                        class="rounded-lg px-2.5 py-1.5 text-[11px] font-medium transition-all"
                    >
                        <i class="fas fa-file-alt ml-1"></i>
                        امتحانات
                    </button>
                </div>
            </div>
        </div>

        {{-- loading overlay --}}
        <div x-show="listLoading" class="flex items-center justify-center py-10">
            <div class="flex flex-col items-center gap-3">
                <div class="w-8 h-8 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                <span class="text-[12px] text-muted">در حال بارگذاری...</span>
            </div>
        </div>

        <div x-show="!listLoading" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

            {{-- =================== مرتب‌سازی براساس روز =================== --}}
            <div x-show="listSort === 'day'">
                @php
                    $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
                    $partsSortedByDay = $program->parts->sortBy(['day_of_week', 'part_order']);
                @endphp
                <div class="overflow-x-auto">
                    <table class="w-full text-xs sm:text-[13px]">
                        <thead>
                        <tr class="bg-muted/30 dark:bg-muted/20 text-muted text-[11px]">
                            <th class="px-3 py-2.5 text-right rounded-r-lg">روز</th>
                            <th class="px-3 py-2.5 text-right">درس</th>
                            <th class="px-3 py-2.5 text-right">توضیحات</th>
                            <th class="px-3 py-2.5 text-center">مدت</th>
                            <th class="px-3 py-2.5 text-center">تست</th>
                            <th class="px-3 py-2.5 text-center">نوع پارت</th>
                            <th class="px-3 py-2.5 text-center">نوع درس</th>
                            <th class="px-3 py-2.5 text-center">پایه</th>
                            <th class="px-3 py-2.5 text-center rounded-l-lg">منبع</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                        @foreach($partsSortedByDay as $part)
                            @php
                                $partJalaliDate = jdate($part->part_date);
                                $partDayOfWeek = $partJalaliDate->getDayOfWeek();
                                $partDayName = $jalaliDayNames[$partDayOfWeek];
                                $isExamRow = in_array($part->part_type, ['comprehensive_exam', 'exam_analysis']);
                            @endphp
                            <tr class="hover:bg-muted/20 dark:hover:bg-muted/10 transition-colors {{ $isExamRow ? 'bg-red-50/40 dark:bg-red-900/10' : '' }}">
                                <td class="px-3 py-2.5 text-[11px] text-muted">
                                    {{ $partDayName }}
                                    <span class="block text-[10px] text-muted/70">{{ $partJalaliDate->format('m/d') }}</span>
                                </td>
                                @include('livewire.client.profile.consultation.partials.list-part-cells', ['part' => $part, 'isExamRow' => $isExamRow])
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- =================== پیش‌خوانی و روزخوانی =================== --}}
            <div x-show="listSort === 'reading'">
                @php
                    $readingParts = $program->parts->whereIn('source_type', ['pre_reading', 'daily_reading'])->sortBy(['day_of_week', 'part_order']);
                @endphp
                @if($readingParts->count() > 0)
                    {{-- گروه‌بندی --}}
                    @foreach(['pre_reading' => ['label' => 'پیش‌خوانی', 'color' => 'teal', 'icon' => 'fa-book'], 'daily_reading' => ['label' => 'روزخوانی', 'color' => 'emerald', 'icon' => 'fa-sun']] as $type => $config)
                        @php $groupParts = $readingParts->where('source_type', $type); @endphp
                        @if($groupParts->count() > 0)
                            <div class="mb-5">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-{{ $config['color'] }}-100 dark:bg-{{ $config['color'] }}-900/40">
                                        <i class="fas {{ $config['icon'] }} text-[12px] text-{{ $config['color'] }}-600 dark:text-{{ $config['color'] }}-400"></i>
                                    </div>
                                    <h4 class="text-sm font-semibold text-foreground">{{ $config['label'] }}</h4>
                                    <span class="rounded-full bg-{{ $config['color'] }}-100 dark:bg-{{ $config['color'] }}-900/30 text-{{ $config['color'] }}-700 dark:text-{{ $config['color'] }}-300 text-[10px] px-2 py-0.5">{{ $groupParts->count() }} پارت</span>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-xs sm:text-[13px]">
                                        <thead>
                                        <tr class="bg-{{ $config['color'] }}-50/50 dark:bg-{{ $config['color'] }}-900/10 text-muted text-[11px]">
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
                                        <tbody class="divide-y divide-border">
                                        @foreach($groupParts as $part)
                                            @php
                                                $pJD = jdate($part->part_date);
                                                $pDOW = $pJD->getDayOfWeek();
                                                $pDayName = $jalaliDayNames[$pDOW];
                                            @endphp
                                            <tr class="hover:bg-{{ $config['color'] }}-50/30 dark:hover:bg-{{ $config['color'] }}-900/10 transition-colors">
                                                <td class="px-3 py-2.5 text-[11px] text-muted">
                                                    {{ $pDayName }}
                                                    <span class="block text-[10px] text-muted/70">{{ $pJD->format('m/d') }}</span>
                                                </td>
                                                @include('livewire.client.profile.consultation.partials.list-part-cells', ['part' => $part, 'isExamRow' => false])
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="w-12 h-12 rounded-xl bg-muted/50 flex items-center justify-center mb-3">
                            <i class="fas fa-book-open text-muted text-lg"></i>
                        </div>
                        <p class="text-sm text-muted">هیچ پارت پیش‌خوانی یا روزخوانی در این برنامه وجود ندارد.</p>
                    </div>
                @endif
            </div>

            {{-- =================== امتحانات =================== --}}
            <div x-show="listSort === 'exam'">
                @php
                    $examParts = $program->parts->whereIn('part_type', ['comprehensive_exam', 'exam_analysis'])->sortBy(['day_of_week', 'part_order']);
                    $regularExamParts = $program->parts->where('source_type', 'exam')->sortBy(['day_of_week', 'part_order']);
                @endphp

                @if($examParts->count() > 0 || $regularExamParts->count() > 0)
                    {{-- آزمون جامع و تحلیل --}}
                    @if($examParts->count() > 0)
                        <div class="mb-5">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/40">
                                    <i class="fas fa-star text-[12px] text-red-600 dark:text-red-400"></i>
                                </div>
                                <h4 class="text-sm font-semibold text-foreground">آزمون‌های جامع و تحلیل</h4>
                                <span class="rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 text-[10px] px-2 py-0.5">{{ $examParts->count() }} پارت</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs sm:text-[13px]">
                                    <thead>
                                    <tr class="bg-red-50/50 dark:bg-red-900/10 text-muted text-[11px]">
                                        <th class="px-3 py-2 text-right">روز</th>
                                        <th class="px-3 py-2 text-right">درس</th>
                                        <th class="px-3 py-2 text-right">توضیحات</th>
                                        <th class="px-3 py-2 text-center">مدت</th>
                                        <th class="px-3 py-2 text-center">تست</th>
                                        <th class="px-3 py-2 text-center">نوع</th>
                                        <th class="px-3 py-2 text-center">پایه</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-border">
                                    @foreach($examParts as $part)
                                        @php
                                            $pJD = jdate($part->part_date);
                                            $pDOW = $pJD->getDayOfWeek();
                                            $pDayName = $jalaliDayNames[$pDOW];
                                        @endphp
                                        <tr class="bg-red-50/30 dark:bg-red-900/10 hover:bg-red-50/60 dark:hover:bg-red-900/20 transition-colors">
                                            <td class="px-3 py-2.5 text-[11px] text-muted">
                                                {{ $pDayName }}
                                                <span class="block text-[10px]">{{ $pJD->format('m/d') }}</span>
                                            </td>
                                            <td class="px-3 py-2.5 text-[11px] font-medium text-red-700 dark:text-red-400">
                                                {{ $part->lesson_name }}
                                                @if($part->ccChapter)({{ $part->ccChapter->name }})@endif
                                            </td>
                                            <td class="px-3 py-2.5 text-[11px] text-muted max-w-[200px]">
                                                @if($part->ccTopic)<span class="text-primary font-medium">{{ $part->ccTopic->name }}</span>@if($part->description) - @endif@endif
                                                {{ Str::limit($part->description, 80) ?? '-' }}
                                            </td>
                                            <td class="px-3 py-2.5 text-center text-[11px]">{{ $part->duration_minutes }} د</td>
                                            <td class="px-3 py-2.5 text-center text-[11px]">{{ $part->test_count ?? '-' }}</td>
                                            <td class="px-3 py-2.5 text-center">
                                                @if($part->part_type === 'comprehensive_exam')
                                                    <span class="rounded-full bg-red-100 dark:bg-red-900/50 px-2 py-0.5 text-[10px] font-medium text-red-700 dark:text-red-300">آزمون جامع</span>
                                                @else
                                                    <span class="rounded-full bg-orange-100 dark:bg-orange-900/50 px-2 py-0.5 text-[10px] font-medium text-orange-700 dark:text-orange-300">تحلیل آزمون</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2.5 text-center text-[11px] text-foreground">
                                                @if($part->grade == 10) دهم @elseif($part->grade == 11) یازدهم @elseif($part->grade == 12) دوازدهم @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    {{-- پارت‌های نوع امتحان (source_type=exam) --}}
                    @if($regularExamParts->count() > 0)
                        <div class="mb-5">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-900/40">
                                    <i class="fas fa-file-alt text-[12px] text-amber-600 dark:text-amber-400"></i>
                                </div>
                                <h4 class="text-sm font-semibold text-foreground">امتحانات (پارت‌های امتحانی)</h4>
                                <span class="rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-[10px] px-2 py-0.5">{{ $regularExamParts->count() }} پارت</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs sm:text-[13px]">
                                    <thead>
                                    <tr class="bg-amber-50/50 dark:bg-amber-900/10 text-muted text-[11px]">
                                        <th class="px-3 py-2 text-right">روز</th>
                                        <th class="px-3 py-2 text-right">درس</th>
                                        <th class="px-3 py-2 text-right">توضیحات</th>
                                        <th class="px-3 py-2 text-center">مدت</th>
                                        <th class="px-3 py-2 text-center">تست</th>
                                        <th class="px-3 py-2 text-center">نوع پارت</th>
                                        <th class="px-3 py-2 text-center">پایه</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-border">
                                    @foreach($regularExamParts as $part)
                                        @php
                                            $pJD = jdate($part->part_date);
                                            $pDayName = $jalaliDayNames[$pJD->getDayOfWeek()];
                                        @endphp
                                        <tr class="hover:bg-amber-50/30 dark:hover:bg-amber-900/10 transition-colors">
                                            <td class="px-3 py-2.5 text-[11px] text-muted">
                                                {{ $pDayName }}
                                                <span class="block text-[10px]">{{ $pJD->format('m/d') }}</span>
                                            </td>
                                            <td class="px-3 py-2.5 text-[11px] font-medium text-foreground">{{ $part->lesson_name }}</td>
                                            <td class="px-3 py-2.5 text-[11px] text-muted max-w-[180px]">{{ Str::limit($part->description, 80) ?? '-' }}</td>
                                            <td class="px-3 py-2.5 text-center text-[11px]">{{ $part->duration_minutes }} د</td>
                                            <td class="px-3 py-2.5 text-center text-[11px]">{{ $part->test_count ?? '-' }}</td>
                                            <td class="px-3 py-2.5 text-center">
                                                @if($part->part_type === 'test')
                                                    <span class="rounded-full bg-sky-100 dark:bg-sky-900/50 px-2 py-0.5 text-[10px] font-medium text-sky-700 dark:text-sky-300">تستی</span>
                                                @elseif($part->part_type === 'descriptive')
                                                    <span class="rounded-full bg-emerald-100 dark:bg-emerald-900/50 px-2 py-0.5 text-[10px] font-medium text-emerald-700 dark:text-emerald-300">تشریحی</span>
                                                @else
                                                    <span class="rounded-full bg-violet-100 dark:bg-violet-900/50 px-2 py-0.5 text-[10px] font-medium text-violet-700 dark:text-violet-300">ویدیویی</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-2.5 text-center text-[11px] text-foreground">
                                                @if($part->grade == 10) دهم @elseif($part->grade == 11) یازدهم @elseif($part->grade == 12) دوازدهم @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="w-12 h-12 rounded-xl bg-muted/50 flex items-center justify-center mb-3">
                            <i class="fas fa-file-alt text-muted text-lg"></i>
                        </div>
                        <p class="text-sm text-muted">هیچ آزمون یا پارت امتحانی در این برنامه وجود ندارد.</p>
                    </div>
                @endif
            </div>

        </div>{{-- end !listLoading --}}
    </section>

    {{-- نمودارها --}}
    <section class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
        {{-- نوع پارت --}}
        <div class="rounded-2xl border border-border bg-secondary p-4 shadow-sm dark:bg-secondary">
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
                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e5e7eb" stroke-width="3" class="dark:stroke-slate-700"/>
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
                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e5e7eb" stroke-width="3" class="dark:stroke-slate-700"/>
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
                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e5e7eb" stroke-width="3" class="dark:stroke-slate-700"/>
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
                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e5e7eb" stroke-width="3" class="dark:stroke-slate-700"/>
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
