<div>
    @php
        $fmtTime = function ($sec) {
            $sec = max(0, (int) $sec);
            $h = (int) floor($sec / 3600);
            $m = (int) floor(($sec % 3600) / 60);
            $s = $sec % 60;
            return sprintf('%02d:%02d:%02d', $h, $m, $s);
        };

        $studyLabel = $fmtTime($current['study_seconds']);
        $extraLabel = $fmtTime($current['extra_study_seconds']);

        $hasAnyData =
            $current['total_sessions'] > 0
            || $current['reports_sent'] > 0
            || $current['study_seconds'] > 0
            || $current['parts_total'] > 0;

        $chartData = [
            'partType' => [
                'labels' => array_keys($current['part_type_distribution']),
                'values' => array_values($current['part_type_distribution']),
            ],
            'lessonType' => [
                'labels' => array_keys($current['lesson_type_distribution']),
                'values' => array_values($current['lesson_type_distribution']),
            ],
            'grade' => [
                'labels' => array_keys($current['grade_distribution']),
                'values' => array_values($current['grade_distribution']),
            ],
            'quality' => [
                'labels' => array_keys($current['quality_distribution']),
                'values' => array_values($current['quality_distribution']),
            ],
        ];

        $compareLabels = [$previousLabel, 'این ماه'];
        $compareData = [
            'sessions' => [$previous['total_sessions'], $current['total_sessions']],
            'reports'  => [$previous['reports_sent'], $current['reports_sent']],
            'study'    => [round($previous['study_seconds'] / 3600, 2), round($current['study_seconds'] / 3600, 2)],
            'tests'    => [$previous['tests_done'], $current['tests_done']],
            'extra'    => [round($previous['extra_study_seconds'] / 3600, 2), round($current['extra_study_seconds'] / 3600, 2)],
        ];

        $sessionsChart = [
            'labels' => array_map(fn($s) => $s['short_label'], $currentSessions),
            'fullLabels' => array_map(fn($s) => $s['label'], $currentSessions),
            'planned' => array_map(fn($s) => $s['planned_hours'], $currentSessions),
            'done' => array_map(fn($s) => $s['done_hours'], $currentSessions),
            'extra' => array_map(fn($s) => $s['extra_hours'], $currentSessions),
        ];

        $compareSessionsLabels = [];
        $compareSessionsFullLabels = [];
        $compareSessionsPlanned = [];
        $compareSessionsDone = [];
        $compareSessionsExtra = [];
        foreach ($previousSessions as $i => $s) {
            $compareSessionsLabels[] = 'جلسه قبلی ' . ($i + 1);

            $compareSessionsFullLabels[] = $previousLabel . ' — ' . $s['label'];
            $compareSessionsPlanned[] = $s['planned_hours'];
            $compareSessionsDone[] = $s['done_hours'];
            $compareSessionsExtra[] = $s['extra_hours'];
        }
        foreach ($currentSessions as $i => $s) {
$compareSessionsLabels[] = 'جلسه فعلی ' . ($i + 1);
            $compareSessionsFullLabels[] = $currentLabel . ' — ' . $s['label'];
            $compareSessionsPlanned[] = $s['planned_hours'];
            $compareSessionsDone[] = $s['done_hours'];
            $compareSessionsExtra[] = $s['extra_hours'];
        }
        $compareSessionsChart = [
            'labels' => $compareSessionsLabels,
            'fullLabels' => $compareSessionsFullLabels,
            'planned' => $compareSessionsPlanned,
            'done' => $compareSessionsDone,
            'extra' => $compareSessionsExtra,
        ];

        $defaultGradeTab = end($availableGrades);
        reset($availableGrades);
        $gradeLabels = ['10' => 'دهم', '11' => 'یازدهم', '12' => 'دوازدهم'];

        $hasCompareData = $previous['total_sessions'] > 0
            || $previous['reports_sent'] > 0
            || $previous['study_seconds'] > 0
            || !empty($previousSessions);
    @endphp
    @php
        $compareItems = [];
        $metricsMap = [
//            'sessions' => 'جلسات',
            'reports'  => 'ارسال گزارش',
            'study'    => 'ساعت مطالعه',
            'tests'    => 'تعداد تست',
            'extra'    => 'خارج از چارچوب',
        ];
        foreach (['reports', 'study', 'tests', 'extra'] as $key) {
            $prev = $compareData[$key][0];
            $curr = $compareData[$key][1];
            $dir = $curr > $prev ? 'up' : ($curr < $prev ? 'down' : 'equal');
            $compareItems[] = [
                'key'   => $key,
                'label' => $metricsMap[$key],
                'prev'  => $prev,
                'curr'  => $curr,
                'dir'   => $dir,
                'unit'  => in_array($key, ['study', 'extra']) ? 'ساعت' : '',
            ];
        }
    @endphp
    @push('link')
        <style>
            [x-cloak] {
                display: none !important;
            }

            /* ════ Score ring ════ */
            @keyframes ring-draw {
                from {
                    stroke-dashoffset: 339.292;
                }
            }

            .score-ring-progress {
                animation: ring-draw 1.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }

            /* ════ Fade-up entrance for sections ════ */
            @keyframes section-up {
                from {
                    opacity: 0;
                    transform: translateY(16px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .section-up {
                animation: section-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) backwards;
            }

            .section-up:nth-child(1) {
                animation-delay: 0.04s;
            }

            .section-up:nth-child(2) {
                animation-delay: 0.08s;
            }

            .section-up:nth-child(3) {
                animation-delay: 0.12s;
            }

            .section-up:nth-child(4) {
                animation-delay: 0.16s;
            }

            .section-up:nth-child(5) {
                animation-delay: 0.20s;
            }

            .section-up:nth-child(6) {
                animation-delay: 0.24s;
            }

            .section-up:nth-child(7) {
                animation-delay: 0.28s;
            }

            .section-up:nth-child(8) {
                animation-delay: 0.32s;
            }

            .section-up:nth-child(9) {
                animation-delay: 0.36s;
            }

            /* ════ Stat card ════ */
            .stat-card {
                position: relative;
                overflow: hidden;
                transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1),
                border-color 0.2s ease;
            }

            .stat-card::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, var(--accent, transparent) 0%, transparent 55%);
                opacity: 0;
                transition: opacity 0.3s ease;
                pointer-events: none;
            }

            .stat-card:hover {
                transform: translateY(-3px);
                border-color: var(--accent-border, hsl(var(--border)));
            }

            .stat-card:hover::before {
                opacity: 0.1;
            }

            .stat-card .stat-icon {
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            .stat-card:hover .stat-icon {
                transform: scale(1.15) rotate(-6deg);
            }

            /* ════ Subject card ════ */
            .subject-card {
                position: relative;
                overflow: hidden;
                transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1),
                border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .subject-card:hover {
                transform: translateY(-3px);
                border-color: hsl(var(--primary) / 0.4);
                box-shadow: 0 8px 20px -8px hsl(var(--primary) / 0.2);
            }

            .subject-card::after {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 60%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.06), transparent);
                transition: left 0.7s ease;
                pointer-events: none;
            }

            .subject-card:hover::after {
                left: 160%;
            }

            /* ════ Progress bar fill ════ */
            @keyframes bar-fill {
                from {
                    width: 0 !important;
                }
            }

            .progress-fill {
                animation: bar-fill 1.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }

            /* ════ Compare button (minimal press) ════ */
            .compare-btn {
                transition: transform 0.12s ease, box-shadow 0.18s ease, background 0.2s ease;
            }

            .compare-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px -6px hsl(var(--primary) / 0.4);
            }

            .compare-btn:active {
                transform: translateY(1px) scale(0.98);
            }

            .compare-btn-arrow {
                transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            .compare-btn:hover .compare-btn-arrow {
                transform: translateY(3px);
            }

            /* ════ Compare reveal ════ */
            @keyframes compare-in {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .compare-content-in {
                animation: compare-in 0.5s cubic-bezier(0.16, 1, 0.3, 1) backwards;
            }

            /* ════ Floating SVG ════ */
            @keyframes float-up {
                0%, 100% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-7px);
                }
            }

            .float-up {
                animation: float-up 4s ease-in-out infinite;
            }

            /* ════ Animated trend line draw ════ */
            @keyframes draw-line {
                from {
                    stroke-dashoffset: 240;
                }
                to {
                    stroke-dashoffset: 0;
                }
            }

            .trend-line {
                stroke-dasharray: 240;
                animation: draw-line 2s ease forwards 0.3s;
            }

            /* ════ Pulse dot ════ */
            @keyframes soft-ping {
                0% {
                    transform: scale(1);
                    opacity: 0.5;
                }
                100% {
                    transform: scale(2.4);
                    opacity: 0;
                }
            }

            .ping-dot {
                position: relative;
            }

            .ping-dot::after {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: 50%;
                background: currentColor;
                animation: soft-ping 2s ease infinite;
            }

            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after {
                    animation: none !important;
                    transition: none !important;
                }
            }

            /* ════ Rotating border (train effect) ════ */
            @keyframes rotate-fast {
                0% {
                    transform: rotate(0deg);
                }
                100% {
                    transform: rotate(360deg);
                }
            }

            .analysis-train-ring {
                position: absolute;
                inset: -3px;
                border-radius: inherit;
                border: 2px dashed transparent;
                pointer-events: none;
                animation: rotate-fast 4s linear infinite;
            }

        </style>
    @endpush

    <div class="max-w-7xl space-y-10 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8 space-y-8">

                {{-- ═══════════════ Header ═══════════════ --}}
                <div
                    class="section-up relative overflow-hidden bg-gradient-to-br from-secondary via-secondary to-secondary/60 border border-border rounded-2xl p-5 sm:p-7">
                    <div
                        class="absolute -top-20 -left-10 w-64 h-64 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div
                        class="absolute -bottom-16 -right-10 w-56 h-56 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex items-center gap-1">
                                    <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                    <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                </div>
                                <span class="text-xs text-muted font-semibold">کارنامه هوشمند</span>
                            </div>
                            <h1 class="font-black text-2xl text-foreground">
                                {{ $card->month_name }}
                                <span class="text-muted font-bold text-base mr-1">{{ $card->jalali_year }}</span>
                            </h1>
                            <p class="text-xs text-muted mt-2">از {{ $card->jalali_start }}
                                تا {{ $card->jalali_end }}</p>
                        </div>

                        <a wire:navigate href="{{ route('client.profile.reportStudentStudy') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-background border border-border rounded-full text-sm text-muted hover:text-foreground hover:border-primary/40 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                 stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                            </svg>
                            بازگشت
                        </a>
                    </div>
                </div>

                @if(!$hasAnyData)
                    <div class="section-up glass border border-border rounded-2xl p-12 text-center space-y-4">
                        {{-- Empty state SVG --}}
                        <div class="float-up inline-block">
                            <svg width="150" height="120" viewBox="0 0 200 160" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <ellipse cx="100" cy="140" rx="70" ry="8" fill="currentColor" class="text-muted/10"/>
                                <rect x="55" y="50" width="90" height="70" rx="8" fill="currentColor"
                                      class="text-background" stroke="currentColor" stroke-width="2.5"/>
                                <line x1="55" y1="68" x2="145" y2="68" stroke="currentColor" stroke-width="2"
                                      class="text-border"/>
                                <circle cx="65" cy="59" r="2.5" fill="currentColor" class="text-muted/40"/>
                                <circle cx="73" cy="59" r="2.5" fill="currentColor" class="text-muted/40"/>
                                <line x1="68" y1="82" x2="110" y2="82" stroke="currentColor" stroke-width="3"
                                      stroke-linecap="round" class="text-muted/25"/>
                                <line x1="68" y1="94" x2="128" y2="94" stroke="currentColor" stroke-width="3"
                                      stroke-linecap="round" class="text-muted/25"/>
                                <line x1="68" y1="106" x2="98" y2="106" stroke="currentColor" stroke-width="3"
                                      stroke-linecap="round" class="text-muted/25"/>
                                <circle cx="135" cy="45" r="20" fill="currentColor" class="text-primary/10"
                                        stroke="currentColor" stroke-width="2.5"/>
                                <line x1="149" y1="59" x2="160" y2="70" stroke="currentColor" stroke-width="3"
                                      stroke-linecap="round" class="text-primary"/>
                            </svg>
                        </div>
                        <h2 class="font-bold text-xl text-foreground">داده‌ای برای نمایش در این ماه وجود ندارد.</h2>
                        <p class="text-sm text-muted">وقتی جلسات، گزارش‌ها یا ساعت مطالعه‌ای در این بازه ثبت شود، آمار
                            اینجا نمایش داده می‌شود.</p>
                    </div>
                @else

                    {{-- ════════════════ Analysis hero ════════════════ --}}
                    @php
                        $tierBg = match($analysis['color']){
                            'emerald' => 'from-emerald-500/15 to-emerald-500/5 border-emerald-500/30',
                            'blue'    => 'from-blue-500/15 to-blue-500/5 border-blue-500/30',
                            'amber'   => 'from-amber-500/15 to-amber-500/5 border-amber-500/30',
                            'orange'  => 'from-orange-500/15 to-orange-500/5 border-orange-500/30',
                            default   => 'from-red-500/15 to-red-500/5 border-red-500/30',
                        };
                        $tierText = match($analysis['color']){
                            'emerald' => 'text-emerald-600 dark:text-emerald-400',
                            'blue'    => 'text-blue-600 dark:text-blue-400',
                            'amber'   => 'text-amber-600 dark:text-amber-400',
                            'orange'  => 'text-orange-600 dark:text-orange-400',
                            default   => 'text-red-600 dark:text-red-400',
                        };
                        $ringColor = match($analysis['color']){
                            'emerald' => '#10b981',
                            'blue'    => '#3b82f6',
                            'amber'   => '#f59e0b',
                            'orange'  => '#f97316',
                            default   => '#ef4444',
                        };
                        $score = (int) $analysis['score'];
                        $circumference = 339.292;
                        $offset = $circumference - ($score / 100) * $circumference;
                    @endphp

                    <div
                        class="section-up bg-gradient-to-br {{ $tierBg }} border rounded-2xl p-5 sm:p-7 relative overflow-hidden">
                        {{-- حلقهٔ قطاری --}}
                        <div class="analysis-train-ring"
                             style="border-color: {{ $ringColor }}; opacity: 0.6;">
                        </div>

                        <div class="absolute -top-10 -left-10 w-48 h-48 rounded-full blur-3xl pointer-events-none"
                             style="background: {{ $ringColor }}25;"></div>

                        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="flex items-center gap-5 flex-1">
                                {{-- Score ring --}}
                                <div class="relative flex-shrink-0">
                                    <svg class="w-24 h-24 -rotate-90" viewBox="0 0 120 120">
                                        <circle cx="60" cy="60" r="54" fill="none" stroke="currentColor"
                                                stroke-width="8" class="text-border opacity-30"/>
                                        <circle cx="60" cy="60" r="54" fill="none" stroke="{{ $ringColor }}"
                                                stroke-width="8"
                                                stroke-linecap="round" stroke-dasharray="{{ $circumference }}"
                                                stroke-dashoffset="{{ $offset }}" class="score-ring-progress"/>
                                    </svg>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                                        <span class="text-2xl font-black {{ $tierText }} leading-none"
                                              x-data="counter({{ $score }})" x-text="display"></span>
                                        <span class="text-[10px] text-muted font-bold mt-0.5">از ۱۰۰</span>
                                    </div>
                                </div>

                                <div class="space-y-2 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xl text-muted font-bold">تحلیل SDFR</span>
                                        <span
                                            class="text-[10px] font-bold rounded-full px-2 py-0.5 {{ $tierText }} bg-background/60">
                                            {{ $analysis['tier'] }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-foreground/90 leading-7">{{ $analysis['message'] }}</p>
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-2 lg:grid-cols-4 gap-2 text-center text-[11px] md:min-w-[280px]">
                                <div class="bg-background/70 backdrop-blur rounded-xl p-2.5">
                                    <div class="text-muted text-[10px]">انجام برنامه</div>
                                    <div
                                        class="font-black text-foreground text-base mt-0.5">{{ $analysis['breakdown']['completion'] }}
                                        %
                                    </div>
                                </div>
                                <div class="bg-background/70 backdrop-blur rounded-xl p-2.5">
                                    <div class="text-muted text-[10px]">گزارش‌دهی</div>
                                    <div
                                        class="font-black text-foreground text-base mt-0.5">{{ $analysis['breakdown']['reports'] }}
                                        %
                                    </div>
                                </div>
                                <div class="bg-background/70 backdrop-blur rounded-xl p-2.5">
                                    <div class="text-muted text-[10px]">کیفیت</div>
                                    <div
                                        class="font-black text-foreground text-base mt-0.5">{{ $analysis['breakdown']['quality'] }}
                                        %
                                    </div>

                                </div>
                                <div class="bg-background/70 backdrop-blur rounded-xl p-2.5">
                                    <div class="text-muted text-[10px]">جریمه تقلب</div>
                                    <div
                                        class="font-black text-foreground text-base mt-0.5">{{ $analysis['breakdown']['cheat_penalty'] }}
                                        %
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>


                    {{-- ════════════════ Summary cards ════════════════ --}}
                    <div class="section-up grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3">
                        @php
                            $statCards = [
                                ['label' => 'جلسات برگزار شده', 'value' => $current['total_sessions'], 'isTime' => false, 'accent' => '#3b82f6', 'text' => 'text-foreground',
                                 'icon' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
                                ['label' => 'گزارش‌های ارسال شده', 'value' => $current['reports_sent'], 'isTime' => false, 'accent' => '#10b981', 'text' => 'text-green-600 dark:text-green-400',
                                 'icon' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'],
                                ['label' => 'گزارش‌های ارسال نشده', 'value' => $current['reports_missing'], 'isTime' => false, 'accent' => '#ef4444', 'text' => 'text-red-500',
                                 'icon' => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'],
                                ['label' => 'ساعت مطالعه', 'value' => $studyLabel, 'isTime' => true, 'accent' => '#6366f1', 'text' => 'text-foreground',
                                 'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
                                ['label' => 'تعداد تست', 'value' => $current['tests_done'], 'isTime' => false, 'accent' => '#8b5cf6', 'text' => 'text-foreground',
                                 'icon' => '<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>'],
                                ['label' => 'اضافه بر سازمان', 'value' => $extraLabel, 'isTime' => true, 'accent' => '#f59e0b', 'text' => 'text-amber-500',
                                 'icon' => '<polyline points="13 17 18 12 13 7"/><polyline points="6 17 11 12 6 7"/>'],
                            ];
                        @endphp

                        @foreach($statCards as $sc)
                            <div class="stat-card glass border border-border rounded-2xl p-4"
                                 style="--accent: {{ $sc['accent'] }}; --accent-border: {{ $sc['accent'] }}66;">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="text-[11px] text-muted">{{ $sc['label'] }}</div>
                                    <div class="stat-icon" style="color: {{ $sc['accent'] }}">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            {!! $sc['icon'] !!}
                                        </svg>
                                    </div>
                                </div>
                                @if($sc['isTime'])
                                    <div class="text-xl font-black {{ $sc['text'] }} tabular-nums"
                                         dir="ltr">{{ $sc['value'] }}</div>
                                @else
                                    <div class="text-2xl font-black {{ $sc['text'] }}">{{ $sc['value'] }}</div>
                                @endif
                                @if($sc['label'] === 'گزارش‌های ارسال شده' && $current['compensatory_reports'] > 0)
                                    <div class="text-[10px] text-muted mt-1">+ {{ $current['compensatory_reports'] }}
                                        جبرانی
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>


                    {{-- ════════════════ Donut charts ════════════════ --}}
                    <div class="section-up grid grid-cols-2 md:grid-cols-2 xl:grid-cols-4 gap-4">
                        <div class="glass border border-border rounded-2xl p-5">
                            <div class="font-bold text-foreground text-sm mb-3 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> توزیع نوع پارت
                            </div>
                            <div class="relative" style="height:220px">
                                <canvas id="src-chart-partType"></canvas>
                            </div>
                            @if(empty($chartData['partType']['labels']))
                                <div class="text-xs text-muted text-center mt-2">داده‌ای موجود نیست</div>
                            @endif
                        </div>
                        <div class="glass border border-border rounded-2xl p-5">
                            <div class="font-bold text-foreground text-sm mb-3 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> توزیع نوع درس
                            </div>
                            <div class="relative" style="height:220px">
                                <canvas id="src-chart-lessonType"></canvas>
                            </div>
                            @if(empty($chartData['lessonType']['labels']))
                                <div class="text-xs text-muted text-center mt-2">داده‌ای موجود نیست</div>
                            @endif
                        </div>
                        <div class="glass border border-border rounded-2xl p-5">
                            <div class="font-bold text-foreground text-sm mb-3 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-violet-500"></span> توزیع پایه تحصیلی
                            </div>
                            <div class="relative" style="height:220px">
                                <canvas id="src-chart-grade"></canvas>
                            </div>
                            @if(empty($chartData['grade']['labels']))
                                <div class="text-xs text-muted text-center mt-2">داده‌ای موجود نیست</div>
                            @endif
                        </div>
                        <div class="glass border border-border rounded-2xl p-5">
                            <div class="font-bold text-foreground text-sm mb-3 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> کیفیت مطالعه
                            </div>
                            <div class="relative" style="height:220px">
                                <canvas id="src-chart-quality"></canvas>
                            </div>
                            <div class="text-[10px] text-muted text-center mt-2">عالی (8+) · با کیفیت (5-7) · بی‌کیفیت (&lt;5)</div>
                        </div>
                    </div>


                    {{-- ════════════════ Sessions chart ════════════════ --}}
                    <div class="section-up glass border border-border rounded-2xl p-5">
                        <div class="flex items-center justify-between flex-wrap gap-2 mb-3">
                            <div>
                                <div class="font-bold text-foreground">ساعت مطالعه به تفکیک جلسات</div>
                                <div class="text-xs text-muted mt-1">برنامه مشاور، انجام شده و اضافه بر سازمان (ساعت)
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-[11px] flex-wrap">
                                <span class="inline-flex items-center gap-1 text-muted"><span
                                        class="w-2.5 h-2.5 rounded-sm" style="background:#3b82f6"></span> برنامه</span>
                                <span class="inline-flex items-center gap-1 text-muted"><span
                                        class="w-2.5 h-2.5 rounded-sm"
                                        style="background:#10b981"></span> انجام شده</span>
                                <span class="inline-flex items-center gap-1 text-muted"><span
                                        class="w-2.5 h-2.5 rounded-sm" style="background:#f59e0b"></span> اضافه بر سازمان</span>
                            </div>
                        </div>
                        @if(empty($currentSessions))
                            <div class="p-8 text-center text-sm text-muted">جلسه برگزار شده‌ای در این ماه نیست.</div>
                        @else
                            <div class="relative" style="height:300px">
                                <canvas id="src-chart-sessions"></canvas>
                            </div>
                            <div class="mt-3 text-[11px] text-muted space-y-1">
                                @foreach($currentSessions as $i => $s)
                                    <div>
                                        {{ $s['label'] }}
                                        <span class="opacity-75 mx-1">|</span>
                                        برنامه: <span dir="ltr">{{ $fmtTime($s['planned_seconds']) }}</span>
                                        — انجام: <span dir="ltr"
                                                       class="text-green-600 dark:text-green-400">{{ $fmtTime($s['done_seconds']) }}</span>
                                        — اضافه: <span dir="ltr"
                                                       class="text-amber-500">{{ $fmtTime($s['extra_seconds']) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>


                    {{-- ════════════════ Subject progress ════════════════ --}}
                    <div class="section-up glass border border-border rounded-2xl overflow-hidden"
                         x-data="{ activeGrade: '{{ $defaultGradeTab }}' }">
                        <div class="p-4 border-b border-border">
                            <div class="flex items-center justify-between flex-wrap gap-3">
                                <div>
                                    <div class="font-bold text-foreground">پیشرفت دروس</div>
                                    <div class="text-xs text-muted mt-1">میانگین درصد انجام برنامه برای هر کتاب — برای
                                        جزئیات روی هر کتاب کلیک کن
                                    </div>
                                </div>
                                <div class="inline-flex bg-background border border-border rounded-full p-1">
                                    @foreach($availableGrades as $g)
                                        <button type="button"
                                                x-on:click="activeGrade = '{{ $g }}'"
                                                x-bind:class="activeGrade === '{{ $g }}' ? 'bg-primary text-primary-foreground' : 'text-muted hover:text-foreground'"
                                                class="px-3 py-1 rounded-full text-xs font-semibold transition-colors">
                                            {{ $gradeLabels[$g] }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        @foreach($availableGrades as $g)
                            <div x-show="activeGrade === '{{ $g }}'" x-cloak class="p-4">
                                @php $subjects = $subjectsByGrade[$g] ?? []; @endphp
                                @if(empty($subjects))
                                    <div class="p-8 text-center text-sm text-muted">برای پایه {{ $gradeLabels[$g] }}
                                        کتابی در برنامه این ماه ثبت نشده است.
                                    </div>
                                @else
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach($subjects as $subj)
                                            @php
                                                $pct = $subj['percent'];
                                                $barColor = $pct >= 100 ? 'bg-green-500'
                                                    : ($pct >= 70 ? 'bg-emerald-500'
                                                    : ($pct >= 40 ? 'bg-amber-500'
                                                    : ($pct > 0 ? 'bg-orange-500' : 'bg-red-500')));
                                                $textColor = $pct >= 70 ? 'text-green-600 dark:text-green-400'
                                                    : ($pct >= 40 ? 'text-amber-500'
                                                    : ($pct > 0 ? 'text-orange-500' : 'text-red-500'));
                                            @endphp
                                            <div
                                                class="subject-card bg-background border border-border rounded-xl p-4 space-y-3">
                                                <div class="flex items-center justify-between gap-2">
                                                    <div
                                                        class="font-bold text-foreground text-sm truncate">{{ $subj['subject_name'] }}</div>
                                                    <span class="text-[10px] text-muted whitespace-nowrap">{{ $subj['parts_studied'] }}/{{ $subj['parts_total'] }} پارت</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <div
                                                        class="flex-1 h-2 glass border border-border rounded-full overflow-hidden">
                                                        <div class="h-full {{ $barColor }} rounded-full progress-fill"
                                                             style="width: {{ min(100, $pct) }}%"></div>
                                                    </div>
                                                    <span class="font-bold {{ $textColor }} text-xs whitespace-nowrap">{{ $pct }}%</span>
                                                </div>
                                                <div class="flex items-center gap-1 flex-wrap text-[10px]">
                                                    @if($subj['quality']['عالی'])
                                                        <span
                                                            class="px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 font-semibold">عالی: {{ $subj['quality']['عالی'] }}</span>
                                                    @endif
                                                    @if($subj['quality']['با کیفیت'])
                                                        <span
                                                            class="px-2 py-0.5 rounded-full bg-blue-500/15 text-blue-600 dark:text-blue-400 font-semibold">با کیفیت: {{ $subj['quality']['با کیفیت'] }}</span>
                                                    @endif
                                                    @if($subj['quality']['بی‌کیفیت'])
                                                        <span
                                                            class="px-2 py-0.5 rounded-full bg-red-500/15 text-red-600 dark:text-red-400 font-semibold">بی‌کیفیت: {{ $subj['quality']['بی‌کیفیت'] }}</span>
                                                    @endif
                                                </div>
                                                {{-- ✅ فقط dispatch می‌کند — کارنامه re-render نمی‌شود --}}
                                                <button type="button"
                                                        wire:click.prevent="requestSubjectDetail({{ $subj['subject_id'] }})"
                                                        class="w-full mt-1 inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-full text-xs font-semibold bg-border text-primary-foreground hover:opacity-90 transition-opacity">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                         class="w-3.5 h-3.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M3.75 4.5h16.5M3.75 12h16.5m-16.5 7.5h16.5"/>
                                                    </svg>
                                                    جزئیات فصل‌ها
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>


                    {{-- ════════════════ Escaped subjects ════════════════ --}}
                    <div class="section-up glass border border-border rounded-2xl p-5">
                        <div class="flex items-center gap-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-5 h-5 text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                            </svg>
                            <span class="font-bold text-foreground">دروس فراری در این ماه</span>
                        </div>
                        <p class="text-[11px] text-muted mb-3">دروسی که در طول ماه هیچ توجه‌ای به آنها نشده است.</p>
                        @if(empty($current['escaped_monthly']))
                            <p class="text-xs text-emerald-600 dark:text-emerald-400">آفرین — هیچ درس فراری نداری!</p>
                        @else
                            <div class="flex items-center gap-2 flex-wrap">
                                @foreach($current['escaped_monthly'] as $item)
                                    <span
                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-500/15 text-red-600 dark:text-red-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        {{ $item['subject_name'] }}
                                        <span class="opacity-75">({{ $item['total_parts'] }} پارت)</span>
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>


                    {{-- ════════════════ Cheat parts ════════════════ --}}
                    <div class="section-up glass border border-border rounded-2xl p-5">
                        <div class="flex items-center gap-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-5 h-5 text-rose-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                            </svg>
                            <span class="font-bold text-foreground">پارت‌های تقلب</span>
                            <span
                                class="text-[11px] font-bold rounded-full px-2 py-0.5 bg-rose-500/15 text-rose-600 dark:text-rose-400">{{ count($current['cheat_parts']) }}</span>
                        </div>
                        <p class="text-[11px] text-muted mb-3">پارت‌هایی که در گزارش روزانه به عنوان «خوانده شده» ثبت
                            کرده‌ای، اما زمان ثبت مطالعه با زمان اتمام پارت تداخل دارد.</p>
                        @if(empty($current['cheat_parts']))
                            <p class="text-xs text-emerald-600 dark:text-emerald-400">هیچ پارت تقلبی پیدا نشد.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs text-right">
                                    <thead class="text-muted bg-background border-b border-border">
                                    <tr>
                                        <th class="p-2.5 whitespace-nowrap">تاریخ</th>
                                        <th class="p-2.5 whitespace-nowrap">درس</th>
                                        <th class="p-2.5 whitespace-nowrap">فصل</th>
                                        <th class="p-2.5 whitespace-nowrap">نام پارت</th>
                                        <th class="p-2.5 whitespace-nowrap">دقایق برنامه</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($current['cheat_parts'] as $cp)
                                        <tr class="odd:bg-background even:glass border-b border-border/40">
                                            <td class="p-2.5 text-muted whitespace-nowrap">{{ $cp['report_date'] }}</td>
                                            <td class="p-2.5 font-semibold text-foreground whitespace-nowrap">{{ $cp['subject_name'] }}</td>
                                            <td class="p-2.5 text-muted whitespace-nowrap">{{ $cp['chapter_name'] ?? '-' }}</td>
                                            <td class="p-2.5 text-muted">{{ $cp['lesson_name'] }}</td>
                                            <td class="p-2.5 text-muted whitespace-nowrap">{{ $cp['planned_minutes'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>


                    {{-- ════════════════ COMPARE WITH PREVIOUS MONTH ════════════════ --}}
                    <div id="compare-section"
                         class="section-up glass border border-border rounded-2xl overflow-hidden"
                         x-data="{ open: false }">

                        {{-- CTA state --}}
                        <div x-show="!open" class="p-6 sm:p-8">
                            <div class="flex flex-col items-center text-center max-w-lg mx-auto">
                                {{-- SVG illustration --}}
                                <div class="float-up mb-5">
                                    <svg width="130" height="105" viewBox="0 0 200 160" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="20" cy="22" r="3" fill="currentColor" class="text-primary/30"/>
                                        <circle cx="182" cy="32" r="2" fill="currentColor" class="text-primary/20"/>
                                        <circle cx="172" cy="120" r="2.5" fill="currentColor" class="text-primary/25"/>
                                        <circle cx="28" cy="128" r="2" fill="currentColor" class="text-primary/20"/>

                                        <line x1="40" y1="130" x2="162" y2="130" stroke="currentColor"
                                              stroke-width="1.5"
                                              class="text-border" stroke-linecap="round" stroke-dasharray="3 4"/>

                                        {{-- Previous month bars (muted) --}}
                                        <rect x="55" y="78" width="15" height="52" rx="4" fill="currentColor"
                                              class="text-muted opacity-40"/>
                                        <rect x="74" y="62" width="15" height="68" rx="4" fill="currentColor"
                                              class="text-muted opacity-40"/>
                                        <rect x="93" y="88" width="15" height="42" rx="4" fill="currentColor"
                                              class="text-muted opacity-40"/>

                                        {{-- Current month bars (primary) --}}
                                        <rect x="115" y="56" width="15" height="74" rx="4" fill="currentColor"
                                              class="text-primary"/>
                                        <rect x="134" y="40" width="15" height="90" rx="4" fill="currentColor"
                                              class="text-primary"/>
                                        <rect x="153" y="68" width="15" height="62" rx="4" fill="currentColor"
                                              class="text-primary"/>

                                        {{-- Animated trend line --}}
                                        <path d="M50 100 Q 95 82 135 60 L 160 46" stroke="currentColor"
                                              stroke-width="2.5"
                                              class="text-primary trend-line" stroke-linecap="round"
                                              stroke-linejoin="round" fill="none"/>
                                        <path d="M154 39 L 162 46 L 154 53" stroke="currentColor" stroke-width="2.5"
                                              class="text-primary" stroke-linecap="round" stroke-linejoin="round"
                                              fill="none"/>

                                        {{-- Sparkle --}}
                                        <path d="M32 58 L32 70 M26 64 L38 64" stroke="currentColor" stroke-width="1.5"
                                              class="text-primary" stroke-linecap="round"/>
                                    </svg>
                                </div>

                                <h3 class="font-black text-foreground text-lg mb-2">
                                    عملکرد این ماه با ماه قبل چطوره؟
                                </h3>
                                <p class="text-sm text-muted leading-7 mb-5 max-w-md">
                                    ببین این ماه نسبت به <span
                                        class="font-bold text-foreground">{{ $previousLabel }}</span>
                                    در جلسات، گزارش‌ها، ساعت مطالعه و تست‌ها چقدر پیشرفت کردی.
                                </p>

                                @if($hasCompareData)
                                    <button type="button"
                                            x-on:click="open = true; $nextTick(() => $dispatch('compare-opened'))"
                                            class="compare-btn group inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-bold">
                                        <span>نمایش مقایسه</span>
                                        <svg class="w-4 h-4 compare-btn-arrow" viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="M12 5v14"/>
                                            <path d="m19 12-7 7-7-7"/>
                                        </svg>
                                    </button>
                                @else
                                    <div
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-muted/20 text-muted text-xs">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/>
                                            <line x1="12" y1="8" x2="12" y2="12"/>
                                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                                        </svg>
                                        داده‌ای از ماه قبل برای مقایسه موجود نیست
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Opened content --}}
                        <div x-show="open" x-cloak class="compare-content-in">
                            <div class="p-4 border-b border-border flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-primary">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                                    </svg>
                                    <span
                                        class="font-bold text-foreground text-sm">مقایسه با {{ $previousLabel }}</span>
                                </div>
                                <button type="button" x-on:click="open = false"
                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs text-muted hover:text-foreground hover:bg-background border border-border transition-colors">
                                    بستن
                                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                    </svg>
                                </button>
                            </div>

                            <div class="p-4 space-y-5">
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                                    @foreach($compareItems as $item)
                                        @php
                                            $dirIcon = match($item['dir']) {
                                                'up'    => '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5m0 0-7 7m7-7 7 7"/></svg>',
                                                'down'  => '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m0 0-7-7m7 7 7-7"/></svg>',
                                                default => '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/></svg>',
                                            };
                                            $dirText = match($item['dir']) {
                                                'up'    => 'پیشرفت',
                                                'down'  => 'پسرفت',
                                                default => 'بدون تغییر',
                                            };
                                            $badgeClasses = match($item['dir']) {
                                                'up'    => 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-400',
                                                'down'  => 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-red-500/15 text-red-600 dark:text-red-400',
                                                default => 'inline-flex items-center gap-1 text-muted',
                                            };
                                        @endphp
                                        <div
                                            class="bg-background border border-border rounded-xl p-3 flex flex-row md:flex-col items-center gap-3">
                                            {{-- بخش نمودار (در موبایل چپ | در دسکتاپ پایین) --}}
                                            <div class="order-2 md:order-2 w-1/2 md:w-full">
                                                <canvas id="src-cmp-{{ $item['key'] }}" height="160"></canvas>
                                            </div>
                                            {{-- عنوان + نشانگر پیشرفت (در موبایل راست | در دسکتاپ بالا) --}}
                                            <div class="order-1 md:order-1 w-1/2 md:w-full text-center md:text-center">
                                                <div
                                                    class="text-[18px] font-bold text-muted mb-1">{{ $item['label'] }}</div>
                                                <div class="flex items-center justify-center">
                                                        <span class="{{ $badgeClasses }}">
                                                            <span>{!! $dirIcon !!}</span>
                                                            <span class="text-xs font-semibold">{{ $dirText }}</span>
                                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="bg-background border border-border rounded-xl p-4">
                                    <div class="flex items-center justify-between flex-wrap gap-2 mb-3">
                                        <div>
                                            <div class="font-bold text-foreground text-sm">ساعت مطالعه جلسات (مقایسه ماه
                                                قبل و فعلی)
                                            </div>
                                            <div class="text-[11px] text-muted mt-1">برنامه، انجام شده و اضافه بر سازمان
                                                به تفکیک هر جلسه (ساعت)
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 text-[11px] flex-wrap">
                                            <span class="inline-flex items-center gap-1 text-muted"><span
                                                    class="w-2.5 h-2.5 rounded-sm" style="background:#3b82f6"></span> برنامه</span>
                                            <span class="inline-flex items-center gap-1 text-muted"><span
                                                    class="w-2.5 h-2.5 rounded-sm" style="background:#10b981"></span> انجام شده</span>
                                            <span class="inline-flex items-center gap-1 text-muted"><span
                                                    class="w-2.5 h-2.5 rounded-sm" style="background:#f59e0b"></span> اضافه بر سازمان</span>
                                        </div>
                                    </div>
                                    @if(empty($compareSessionsChart['labels']))
                                        <div class="p-6 text-center text-xs text-muted">جلسه‌ای برای مقایسه وجود
                                            ندارد.
                                        </div>
                                    @else
                                        <div class="relative" style="height:300px">
                                            <canvas id="src-cmp-sessions-bar"></canvas>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════
         مودال جزئیات درس — کامپوننت کاملاً جدا
         این کامپوننت مستقل render می‌شود و باز/بسته شدنش
         هیچ تأثیری روی کارنامه و چارت‌ها ندارد.
         ════════════════════════════════════════════════════════════════ --}}
    <livewire:client.profile.subject-detail-modal/>


    {{-- ════════════════════════════════════════════════════════════════
         CHART INITIALIZATION
         ════════════════════════════════════════════════════════════════ --}}
    @if($hasAnyData)
        @push('script')
            <script>
                (function () {
                    const palette = [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
                        '#ec4899', '#14b8a6', '#f97316', '#84cc16', '#06b6d4',
                        '#a855f7', '#22c55e', '#eab308', '#dc2626', '#0ea5e9'
                    ];
                    const qualityColors = {'عالی': '#10b981', 'با کیفیت': '#3b82f6', 'بی‌کیفیت': '#ef4444'};

                    function rootStyle(varName, fallback) {
                        const v = getComputedStyle(document.documentElement).getPropertyValue(varName).trim();
                        return v || fallback;
                    }

                    // داده‌ها روی window کش می‌شوند تا بعد از navigate هم در دسترس باشند
                    window.__srcChartData = {
                        main: @json($chartData),
                        sessions: @json($sessionsChart),
                        cmpLabels: @json($compareLabels),
                        cmpData: @json($compareData),
                        cmpSessions: @json($compareSessionsChart),
                    };
                    window.__srcCharts = window.__srcCharts || {};

                    function destroyChart(id) {
                        if (window.__srcCharts[id]) {
                            try {
                                window.__srcCharts[id].destroy();
                            } catch (e) {
                            }
                            delete window.__srcCharts[id];
                        }
                    }

                    function destroyAll() {
                        Object.keys(window.__srcCharts).forEach(destroyChart);
                    }

                    function donut(canvasId, labels, values, colorMap) {
                        destroyChart(canvasId);
                        const el = document.getElementById(canvasId);
                        if (!el || typeof Chart === 'undefined') return;
                        if (!labels.length || !values.length || values.every(v => !v)) {
                            const ctx = el.getContext('2d');
                            ctx && ctx.clearRect(0, 0, el.width, el.height);
                            return;
                        }
                        const legendColor = rootStyle('--foreground', '#ffffff') || '#ffffff';
                        const bg = colorMap
                            ? labels.map((lbl, i) => colorMap[lbl] || palette[i % palette.length])
                            : labels.map((_, i) => palette[i % palette.length]);
                        window.__srcCharts[canvasId] = new Chart(el, {
                            type: 'doughnut',
                            data: {
                                labels: labels,
                                datasets: [{
                                    data: values,
                                    backgroundColor: bg,
                                    borderWidth: 2,
                                    borderColor: rootStyle('--secondary', '#ffffff') || '#ffffff'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '62%',
                                animation: {animateRotate: true, duration: 700},
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            color: '#ffffff',
                                            font: {size: 11, family: 'inherit'},
                                            boxWidth: 10,
                                            padding: 8
                                        }
                                    }
                                }
                            }
                        });
                    }

                    function groupedBar(canvasId, labels, planned, done, extra, fullLabels, isComparison = false) {
                        destroyChart(canvasId);
                        const el = document.getElementById(canvasId);
                        if (!el || !labels.length || typeof Chart === 'undefined') return;
                        const legendColor = rootStyle('--foreground', '#ffffff') || '#ffffff';
                        const gridColor = 'rgba(148,163,184,.25)';

                        // رنگ‌بندی بر اساس پیشوند برچسب
                        const prevPlanned = '#64748b';   // خاکستری برای ماه قبل
                        const currPlanned = '#3b82f6';
                        const prevDone = '#4ade80';      // سبز کمرنگ برای ماه قبل
                        const currDone = '#10b981';
                        const prevExtra = '#fbbf24';     // زرد کمرنگ برای ماه قبل
                        const currExtra = '#f59e0b';

                        const plannedColors = labels.map(l => l.startsWith('جلسه قبلی') ? prevPlanned : currPlanned);
                        const doneColors = labels.map(l => l.startsWith('جلسه قبلی') ? prevDone : currDone);
                        const extraColors = labels.map(l => l.startsWith('جلسه قبلی') ? prevExtra : currExtra);

                        window.__srcCharts[canvasId] = new Chart(el, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [
                                    {
                                        label: 'برنامه',
                                        data: planned,
                                        backgroundColor: plannedColors,
                                        borderRadius: 6,
                                        maxBarThickness: 28
                                    },
                                    {
                                        label: 'انجام شده',
                                        data: done,
                                        backgroundColor: doneColors,
                                        borderRadius: 6,
                                        maxBarThickness: 28
                                    },
                                    {
                                        label: 'اضافه بر سازمان',
                                        data: extra,
                                        backgroundColor: extraColors,
                                        borderRadius: 6,
                                        maxBarThickness: 28
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                animation: {duration: 700},
                                plugins: {
                                    legend: {display: false},
                                    tooltip: {
                                        callbacks: {
                                            title: (items) => {
                                                if (!items.length) return '';
                                                const idx = items[0].dataIndex;
                                                return (fullLabels && fullLabels[idx]) || items[0].label;
                                            },
                                            label: (ctx) => {
                                                const total = Math.round(ctx.parsed.y * 3600);
                                                const hh = String(Math.floor(total / 3600)).padStart(2, '0');
                                                const mm = String(Math.floor((total % 3600) / 60)).padStart(2, '0');
                                                const ss = String(total % 60).padStart(2, '0');
                                                return `${ctx.dataset.label}: ${hh}:${mm}:${ss}`;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    x: {
                                        ticks: {color: '#ffffff', font: {size: 11, family: 'inherit'}},
                                        grid: {display: false}
                                    },
                                    y: {
                                        beginAtZero: true,
                                        ticks: {color: '#ffffff', font: {size: 11, family: 'inherit'}},
                                        grid: {color: '#ffffff'},
                                        title: {display: true, text: 'ساعت', color: '#ffffff', font: {size: 11}}
                                    }
                                }
                            }
                        });
                    }

                    function initMainCharts() {
                        const d = window.__srcChartData;
                        if (!d) return;
                        donut('src-chart-partType', d.main.partType.labels, d.main.partType.values);
                        donut('src-chart-lessonType', d.main.lessonType.labels, d.main.lessonType.values);
                        donut('src-chart-grade', d.main.grade.labels, d.main.grade.values);
                        donut('src-chart-quality', d.main.quality.labels, d.main.quality.values, qualityColors);
                        groupedBar('src-chart-sessions', d.sessions.labels, d.sessions.planned, d.sessions.done, d.sessions.extra, d.sessions.fullLabels);
                    }

                    function initCompareCharts() {
                        const d = window.__srcChartData;
                        if (!d) return;
                        donut('src-cmp-sessions', d.cmpLabels, d.cmpData.sessions);
                        donut('src-cmp-reports', d.cmpLabels, d.cmpData.reports);
                        donut('src-cmp-study', d.cmpLabels, d.cmpData.study);
                        donut('src-cmp-tests', d.cmpLabels, d.cmpData.tests);
                        donut('src-cmp-extra', d.cmpLabels, d.cmpData.extra);
                        if (d.cmpSessions.labels && d.cmpSessions.labels.length) {
                            groupedBar('src-cmp-sessions-bar', d.cmpSessions.labels, d.cmpSessions.planned, d.cmpSessions.done, d.cmpSessions.extra, d.cmpSessions.fullLabels, true);
                        }
                    }

                    function initAll() {
                        // فقط وقتی canvas اصلی در DOM هست (یعنی همین صفحه فعاله)
                        if (!document.getElementById('src-chart-partType')) return;
                        initMainCharts();
                        initCompareCharts();
                    }

                    window.__srcInitCharts = initAll;

                    // ════ رندر اولیه ════
                    function boot() {
                        if (typeof Chart === 'undefined') {
                            setTimeout(boot, 60);
                            return;
                        }
                        initAll();
                    }

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', boot);
                    } else {
                        boot();
                    }


                    document.addEventListener('livewire:navigated', () => {
                        // شمارنده‌های Alpine
                        if (window.Alpine) {
                            Alpine.initTree(document.body);
                        }
                        // چارت‌ها
                        requestAnimationFrame(() => requestAnimationFrame(window.__srcInitCharts));
                    });

                    // ════ اسکرول نرم هنگام باز شدن مقایسه ════
                    document.addEventListener('compare-opened', () => {
                        setTimeout(() => {
                            const el = document.getElementById('compare-section');
                            if (el) el.scrollIntoView({behavior: 'smooth', block: 'start'});
                            requestAnimationFrame(() => initCompareCharts());
                        }, 120);
                    });
                })();
            </script>
        @endpush
    @endif

    @push('script')
        <script>
            document.addEventListener('alpine:init', () => {
                // شمارنده‌ی انیمیشنی
                Alpine.data('counter', (target) => ({
                    display: 0,
                    init() {
                        const duration = 850;
                        const start = performance.now();
                        const tick = (now) => {
                            const t = Math.min(1, (now - start) / duration);
                            const eased = 1 - Math.pow(1 - t, 3); // easeOutCubic
                            this.display = Math.floor(target * eased);
                            if (t < 1) requestAnimationFrame(tick);
                            else this.display = target;
                        };
                        requestAnimationFrame(tick);
                    },
                }));
            });
        </script>
    @endpush
</div>
