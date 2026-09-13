<div class="max-w-5xl mx-auto px-4 py-6 sm:py-10" dir="rtl">

    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-2 text-foreground">کارنامه‌ی من</h1>
        <p class="text-sm text-muted leading-7">
            خلاصه‌ای از پروفایل روان‌شناختی، برنامه‌ی هفتگی، و فعالیت‌های دوره‌ی شما.
        </p>
    </div>

    {{-- خلاصه‌ی بالای صفحه --}}
    <div class="bg-gradient-to-l from-primary/10 to-background border border-primary/20 rounded-2xl p-5 sm:p-6 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <p class="text-xs text-muted mb-1">دانش‌آموز</p>
                <p class="font-bold text-foreground">{{ auth()->user()->name }}</p>
                <p class="text-xs text-muted mt-1">{{ $trial->grade_label }} · {{ $trial->field_label }}</p>
            </div>
            @if (! empty($report['mbti']['type']))
                <div>
                    <p class="text-xs text-muted mb-1">تیپ شخصیتی</p>
                    <p class="font-bold text-primary">{{ $report['mbti']['type'] }}</p>
                    <p class="text-xs text-muted mt-1">{{ $report['mbti']['title'] }}</p>
                </div>
            @endif
            @if (! empty($report['vark']['profile']))
                <div>
                    <p class="text-xs text-muted mb-1">سبک یادگیری</p>
                    <p class="font-bold text-primary">{{ $report['vark']['profile'] }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- آکاردئون چهار بخش — قبلاً با کلاس‌های DaisyUI (collapse/base-content/...)
         نوشته شده بود که اصلاً توی این پروژه نصب نیست (فقط Tailwind خامِ خودِ پروژه‌ست)،
         یعنی این چهار بخش عملاً بی‌استایل بودن و آکاردئونشون هم کار نمی‌کرد؛ با
         الگوی استاندارد Alpine همین پروژه (btn-press + چرخشِ chevron-down) بازسازی شد --}}
    <div class="space-y-3" x-data="{ open1: true, open2: false, open3: false, open4: false }">

        {{-- 1) پروفایل کامل --}}
        <div class="border border-border rounded-2xl overflow-hidden bg-background">
            <button type="button" @click="open1 = !open1" data-elevated="false"
                    class="btn-press w-full flex items-center justify-between gap-2 px-4 py-3.5 text-base font-bold text-foreground hover:bg-secondary/50 transition-colors">
                <span>۱) پروفایل روان‌شناختی</span>
                <x-ui.icon name="chevron-down" class="w-4 h-4 text-muted transition-transform duration-200 flex-shrink-0" x-bind:class="{ 'rotate-180': open1 }"/>
            </button>
            <div x-show="open1" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="px-4 pb-4 border-t border-border pt-4">
                @if (! empty($report['mbti']['type']))
                    <div class="mb-5">
                        <p class="text-xs text-muted">شخصیت (MBTI)</p>
                        <h3 class="text-lg font-bold text-primary mt-1">
                            {{ $report['mbti']['type'] }} — {{ $report['mbti']['title'] }}
                        </h3>
                        <p class="text-sm leading-7 mt-2 text-muted">{{ $report['mbti']['description'] }}</p>
                    </div>
                @endif

                @if (! empty($report['vark']['profile']))
                    <div class="mb-5">
                        <p class="text-xs text-muted">سبک یادگیری (VARK)</p>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-2">
                            @foreach ($report['vark']['modalities'] as $m)
                                <div class="rounded-xl border p-2 text-center
                                            {{ $m['dominant'] ? 'border-primary bg-primary/5' : 'border-border' }}">
                                    <p class="font-bold {{ $m['dominant'] ? 'text-primary' : 'text-foreground' }}">{{ $m['letter'] }}</p>
                                    <p class="text-[10px] text-foreground">{{ $m['title'] }}</p>
                                    <p class="text-[10px] text-muted">{{ $m['percent'] }}%</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (! empty($report['mindset_sections']))
                    <div>
                        <p class="text-xs text-muted mb-2">ذهنیت تحصیلی</p>
                        @foreach ($report['mindset_sections'] as $section)
                            <div class="bg-secondary rounded-xl p-3 mb-3">
                                <p class="text-sm font-bold mb-2 text-foreground">{{ $section['assessment']->name_fa }}</p>
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
                                        <div class="flex items-center justify-between text-xs mb-1 text-foreground">
                                            <span>{{ $facet['label'] }}</span>
                                            <span>{{ $facet['percent'] }}%</span>
                                        </div>
                                        <div class="w-full bg-border rounded-full h-1 overflow-hidden">
                                            <div class="{{ $barColor }} h-1" style="width: {{ $facet['percent'] }}%"></div>
                                        </div>
                                        @if (! empty($facet['text']) && $facet['text'] !== '—')
                                            <p class="text-xs text-muted leading-5 mt-1">{{ $facet['text'] }}</p>
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
        <div class="border border-border rounded-2xl overflow-hidden bg-background">
            <button type="button" @click="open2 = !open2" data-elevated="false"
                    class="btn-press w-full flex items-center justify-between gap-2 px-4 py-3.5 text-base font-bold text-foreground hover:bg-secondary/50 transition-colors">
                <span>۲) برنامه‌ی هفتگی</span>
                <x-ui.icon name="chevron-down" class="w-4 h-4 text-muted transition-transform duration-200 flex-shrink-0" x-bind:class="{ 'rotate-180': open2 }"/>
            </button>
            <div x-show="open2" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="px-4 pb-4 border-t border-border pt-4">
                @if (! $program)
                    <p class="text-sm text-muted">برنامه‌ای ساخته نشده.</p>
                @else
                    <p class="text-sm text-muted mb-4 leading-7">
                        برنامه از تاریخ {{ optional($program->start_date)->format('Y/m/d') }}
                        تا {{ optional($program->end_date)->format('Y/m/d') }} —
                        مجموع {{ $program->total_hours }} ساعت در هفته.
                    </p>
                    <div class="space-y-2">
                        @foreach ($weekDays as $day)
                            <div class="bg-secondary rounded-xl p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-bold text-foreground">{{ $day['name'] }}</span>
                                    <span class="text-xs text-muted">{{ $day['jalali_short'] }} · {{ $day['total_hours'] }} ساعت</span>
                                </div>
                                @if (count($day['parts']) === 0)
                                    <p class="text-xs text-muted">پارتی برای این روز ثبت نشده.</p>
                                @else
                                    <ul class="text-xs space-y-1 text-foreground">
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
        <div class="border border-border rounded-2xl overflow-hidden bg-background">
            <button type="button" @click="open3 = !open3" data-elevated="false"
                    class="btn-press w-full flex items-center justify-between gap-2 px-4 py-3.5 text-base font-bold text-foreground hover:bg-secondary/50 transition-colors">
                <span>۳) فعالیت دوره</span>
                <x-ui.icon name="chevron-down" class="w-4 h-4 text-muted transition-transform duration-200 flex-shrink-0" x-bind:class="{ 'rotate-180': open3 }"/>
            </button>
            <div x-show="open3" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="px-4 pb-4 border-t border-border pt-4">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <div class="bg-secondary rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold text-primary">{{ $sessionsCount }}</p>
                        <p class="text-xs text-muted mt-1">جلسات مشاوره</p>
                    </div>
                    <div class="bg-secondary rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold text-primary">{{ $examAttempts->count() }}</p>
                        <p class="text-xs text-muted mt-1">آخرین آزمون‌ها</p>
                    </div>
                    <div class="bg-secondary rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold text-primary">{{ $trial->daily_study_hours ?? 0 }}</p>
                        <p class="text-xs text-muted mt-1">ساعت روزانه</p>
                    </div>
                </div>

                @if ($examAttempts->isNotEmpty())
                    <p class="text-xs font-bold mt-5 mb-2 text-foreground">آخرین آزمون‌های شما</p>
                    <ul class="text-sm space-y-1">
                        @foreach ($examAttempts as $a)
                            <li class="flex items-center justify-between text-xs text-foreground">
                                <span>{{ optional($a->created_at)->format('Y/m/d') }}</span>
                                <span class="text-muted">شناسه #{{ $a->id }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- 4) توصیه‌های شخصی --}}
        <div class="border border-border rounded-2xl overflow-hidden bg-background">
            <button type="button" @click="open4 = !open4" data-elevated="false"
                    class="btn-press w-full flex items-center justify-between gap-2 px-4 py-3.5 text-base font-bold text-foreground hover:bg-secondary/50 transition-colors">
                <span>۴) توصیه‌های مطالعه برای شما</span>
                <x-ui.icon name="chevron-down" class="w-4 h-4 text-muted transition-transform duration-200 flex-shrink-0" x-bind:class="{ 'rotate-180': open4 }"/>
            </button>
            <div x-show="open4" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="px-4 pb-4 border-t border-border pt-4">
                @foreach ($report['study_tips'] as $tip)
                    <div class="bg-primary/5 border border-primary/20 rounded-xl p-3 mb-3">
                        <p class="text-xs font-bold text-primary mb-1">{{ $tip['source'] }}</p>
                        <p class="text-sm leading-7 text-foreground">{{ $tip['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
