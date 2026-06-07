<div class="max-w-5xl mx-auto px-4 py-6 sm:py-10" dir="rtl">

    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-2">کارنامه‌ی من</h1>
        <p class="text-sm text-base-content/70 leading-7">
            خلاصه‌ای از پروفایل روان‌شناختی، برنامه‌ی هفتگی، و فعالیت‌های دوره‌ی شما.
        </p>
    </div>

    {{-- خلاصه‌ی بالای صفحه --}}
    <div class="bg-gradient-to-l from-primary/10 to-base-100 border border-primary/20 rounded-2xl p-5 sm:p-6 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <p class="text-xs text-base-content/60 mb-1">دانش‌آموز</p>
                <p class="font-bold">{{ auth()->user()->name }}</p>
                <p class="text-xs text-base-content/60 mt-1">{{ $trial->grade_label }} · {{ $trial->field_label }}</p>
            </div>
            @if (! empty($report['mbti']['type']))
                <div>
                    <p class="text-xs text-base-content/60 mb-1">تیپ شخصیتی</p>
                    <p class="font-bold text-primary">{{ $report['mbti']['type'] }}</p>
                    <p class="text-xs text-base-content/60 mt-1">{{ $report['mbti']['title'] }}</p>
                </div>
            @endif
            @if (! empty($report['vark']['profile']))
                <div>
                    <p class="text-xs text-base-content/60 mb-1">سبک یادگیری</p>
                    <p class="font-bold text-primary">{{ $report['vark']['profile'] }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- آکاردئون چهار بخش --}}
    <div class="space-y-3">

        {{-- 1) پروفایل کامل --}}
        <div class="collapse collapse-arrow bg-base-100 border border-base-300 rounded-2xl">
            <input type="checkbox" checked />
            <div class="collapse-title text-base font-bold">۱) پروفایل روان‌شناختی</div>
            <div class="collapse-content">
                @if (! empty($report['mbti']['type']))
                    <div class="mb-5">
                        <p class="text-xs text-base-content/60">شخصیت (MBTI)</p>
                        <h3 class="text-lg font-bold text-primary mt-1">
                            {{ $report['mbti']['type'] }} — {{ $report['mbti']['title'] }}
                        </h3>
                        <p class="text-sm leading-7 mt-2 text-base-content/80">{{ $report['mbti']['description'] }}</p>
                    </div>
                @endif

                @if (! empty($report['vark']['profile']))
                    <div class="mb-5">
                        <p class="text-xs text-base-content/60">سبک یادگیری (VARK)</p>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-2">
                            @foreach ($report['vark']['modalities'] as $m)
                                <div class="rounded-xl border p-2 text-center
                                            {{ $m['dominant'] ? 'border-primary bg-primary/5' : 'border-base-300' }}">
                                    <p class="font-bold {{ $m['dominant'] ? 'text-primary' : '' }}">{{ $m['letter'] }}</p>
                                    <p class="text-[10px]">{{ $m['title'] }}</p>
                                    <p class="text-[10px] text-base-content/60">{{ $m['percent'] }}%</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (! empty($report['mindset_sections']))
                    <div>
                        <p class="text-xs text-base-content/60 mb-2">ذهنیت تحصیلی</p>
                        @foreach ($report['mindset_sections'] as $section)
                            <div class="bg-base-200 rounded-xl p-3 mb-3">
                                <p class="text-sm font-bold mb-2">{{ $section['assessment']->name_fa }}</p>
                                @foreach ($section['interpretation']['facets'] ?? [] as $facet)
                                    @php
                                        $level = $facet['level'] ?? 'medium';
                                        $barColor = match ($level) {
                                            'low'    => 'bg-success',
                                            'high'   => 'bg-error',
                                            default  => 'bg-warning',
                                        };
                                    @endphp
                                    <div class="mb-2">
                                        <div class="flex items-center justify-between text-xs mb-1">
                                            <span>{{ $facet['label'] }}</span>
                                            <span>{{ $facet['percent'] }}%</span>
                                        </div>
                                        <div class="w-full bg-base-300 rounded-full h-1 overflow-hidden">
                                            <div class="{{ $barColor }} h-1" style="width: {{ $facet['percent'] }}%"></div>
                                        </div>
                                        @if (! empty($facet['text']) && $facet['text'] !== '—')
                                            <p class="text-xs text-base-content/70 leading-5 mt-1">{{ $facet['text'] }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- 2) برنامه‌ی هفتگی --}}
        <div class="collapse collapse-arrow bg-base-100 border border-base-300 rounded-2xl">
            <input type="checkbox" />
            <div class="collapse-title text-base font-bold">۲) برنامه‌ی هفتگی</div>
            <div class="collapse-content">
                @if (! $program)
                    <p class="text-sm text-base-content/70">برنامه‌ای ساخته نشده.</p>
                @else
                    <p class="text-sm text-base-content/70 mb-4 leading-7">
                        برنامه از تاریخ {{ optional($program->start_date)->format('Y/m/d') }}
                        تا {{ optional($program->end_date)->format('Y/m/d') }} —
                        مجموع {{ $program->total_hours }} ساعت در هفته.
                    </p>
                    <div class="space-y-2">
                        @foreach ($weekDays as $day)
                            <div class="bg-base-200 rounded-xl p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-bold">{{ $day['name'] }}</span>
                                    <span class="text-xs text-base-content/60">{{ $day['jalali_short'] }} · {{ $day['total_hours'] }} ساعت</span>
                                </div>
                                @if (count($day['parts']) === 0)
                                    <p class="text-xs text-base-content/50">پارتی برای این روز ثبت نشده.</p>
                                @else
                                    <ul class="text-xs space-y-1">
                                        @foreach ($day['parts'] as $part)
                                            <li>• {{ $part->lesson_name }} ({{ $part->duration_minutes }} دقیقه)</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- 3) چکیده‌ی فعالیت --}}
        <div class="collapse collapse-arrow bg-base-100 border border-base-300 rounded-2xl">
            <input type="checkbox" />
            <div class="collapse-title text-base font-bold">۳) فعالیت دوره</div>
            <div class="collapse-content">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <div class="bg-base-200 rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold text-primary">{{ $sessionsCount }}</p>
                        <p class="text-xs text-base-content/60 mt-1">جلسات مشاوره</p>
                    </div>
                    <div class="bg-base-200 rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold text-primary">{{ $examAttempts->count() }}</p>
                        <p class="text-xs text-base-content/60 mt-1">آخرین آزمون‌ها</p>
                    </div>
                    <div class="bg-base-200 rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold text-primary">{{ $trial->daily_study_hours ?? 0 }}</p>
                        <p class="text-xs text-base-content/60 mt-1">ساعت روزانه</p>
                    </div>
                </div>

                @if ($examAttempts->isNotEmpty())
                    <p class="text-xs font-bold mt-5 mb-2">آخرین آزمون‌های شما</p>
                    <ul class="text-sm space-y-1">
                        @foreach ($examAttempts as $a)
                            <li class="flex items-center justify-between text-xs">
                                <span>{{ optional($a->created_at)->format('Y/m/d') }}</span>
                                <span class="text-base-content/60">شناسه #{{ $a->id }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- 4) توصیه‌های شخصی --}}
        <div class="collapse collapse-arrow bg-base-100 border border-base-300 rounded-2xl">
            <input type="checkbox" />
            <div class="collapse-title text-base font-bold">۴) توصیه‌های مطالعه برای شما</div>
            <div class="collapse-content">
                @foreach ($report['study_tips'] as $tip)
                    <div class="bg-primary/5 border border-primary/20 rounded-xl p-3 mb-3">
                        <p class="text-xs font-bold text-primary mb-1">{{ $tip['source'] }}</p>
                        <p class="text-sm leading-7">{{ $tip['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
