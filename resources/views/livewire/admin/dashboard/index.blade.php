<div>
    @php
        function formatDuration($seconds) {
            if ($seconds < 60) {
                return $seconds . ' ثانیه';
            }
            $minutes = floor($seconds / 60);
            $hours = floor($minutes / 60);
            $minutes = $minutes % 60;
            if ($hours > 0) {
                return $hours . ' ساعت و ' . $minutes . ' دقیقه';
            }
            return $minutes . ' دقیقه';
        }
    @endphp
    @push('link')
        {{-- Iran Map --}}
        <style>
            .dashboard-stat-card .card-body {
                padding: 1rem;
            }

            .dashboard-stat-card .stat-title {
                color: #6c757d;
                font-size: 0.88rem;
                line-height: 1.8;
                min-height: 3rem;
                margin-bottom: 0.75rem;
            }

            .dashboard-stat-card .stat-value {
                text-align: left;
                direction: ltr;
                font-size: 1.45rem;
                font-weight: 700;
                line-height: 1.4;
                margin-bottom: 0.75rem;
            }

            .dashboard-stat-card .stat-meta {
                color: #6c757d;
                font-size: 0.78rem;
                min-height: 1.25rem;
                margin-bottom: 0.75rem;
            }

            .dashboard-stat-card .btn {
                width: 100%;
            }

            .iran-map-card {
                background:
                    radial-gradient(circle at top, rgba(99, 179, 237, 0.18), transparent 30%),
                    linear-gradient(180deg, #1f2431 0%, #161a24 100%);
                border: 0;
                overflow: hidden;
            }

            .iran-map-shell {
                color: #e8eefb;
            }

            .iran-map-summary {
                display: flex;
                justify-content: center;
                gap: 12px;
                flex-wrap: wrap;
                margin-bottom: 1rem;
            }

            .iran-map-pill {
                background: rgba(164, 208, 255, 0.12);
                border: 1px solid rgba(164, 208, 255, 0.18);
                color: #dcecff;
                border-radius: 999px;
                padding: 8px 14px;
                font-size: 0.85rem;
            }

            .iran-map-board {
                display: grid;
                grid-template-columns: minmax(0, 1fr) 280px;
                gap: 20px;
                align-items: center;
            }

            .iran-map-stage {
                position: relative;
                min-height: 560px;
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.06);
                border-radius: 24px;
                padding: 20px;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04);
            }

            #iran-map-container {
                width: 100%;
                max-width: 760px;
                margin: auto;
                position: relative;
            }

            #iran-map-container .iran-map-svg {
                width: 100%;
                height: auto;
                display: block;
            }

            #iran-map-container .iran-map-water {
                fill: #73b7e6;
            }

            #iran-map-container .iran-map-border {
                fill: rgba(255, 255, 255, 0.03);
                stroke: rgba(255, 255, 255, 0.2);
                stroke-width: 1.2px;
            }

            #iran-map-container .province {
                fill: #d7d9df;
                stroke: rgba(255, 255, 255, 0.92);
                stroke-width: 2px;
                transition: transform 0.2s ease, fill 0.2s ease, filter 0.2s ease, stroke 0.2s ease;
                transform-origin: center;
                transform-box: fill-box;
                cursor: pointer;
            }

            #iran-map-container .province:hover,
            #iran-map-container .province.is-active {
                transform: translateY(-2px);
                stroke: #ffffff;
                filter: drop-shadow(0 8px 16px rgba(28, 44, 79, 0.35));
            }

            .iran-map-sidepanel {
                background: rgba(255, 255, 255, 0.04);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 20px;
                padding: 18px;
                min-height: 320px;
            }

            .iran-map-sidepanel .eyebrow {
                color: #96a6c6;
                font-size: 0.8rem;
                margin-bottom: 6px;
            }

            .iran-map-sidepanel .province-name {
                font-size: 1.4rem;
                font-weight: 700;
                color: #ffffff;
                margin-bottom: 10px;
            }

            .iran-map-sidepanel .province-count {
                font-size: 2.3rem;
                line-height: 1;
                font-weight: 800;
                color: #8fd3ff;
                margin-bottom: 10px;
            }

            .iran-map-sidepanel .province-help {
                color: #9fb0cf;
                font-size: 0.92rem;
                line-height: 1.8;
            }

            .iran-map-toplist {
                margin-top: 18px;
                padding-top: 14px;
                border-top: 1px solid rgba(255, 255, 255, 0.08);
            }

            .iran-map-topitem {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                padding: 8px 0;
                color: #d9e6ff;
                font-size: 0.92rem;
            }

            .iran-map-topitem .badge {
                min-width: 42px;
            }

            .iran-map-footer {
                margin-top: 18px;
                text-align: center;
                color: #98abc9;
                font-size: 0.92rem;
            }

            @media (max-width: 991.98px) {
                .iran-map-board {
                    grid-template-columns: 1fr;
                }

                .iran-map-stage {
                    min-height: 0;
                }
            }
        </style>
    @endpush
    <div class="row mt-4 mb-3">
        <div class="col-12 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h4 class="mb-0">داشبورد تحلیلی مشاوره</h4>
                <small class="text-muted">آخرین بروزرسانی: {{ now()->format('Y/m/d H:i') }}</small>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge bg-light text-dark">
                    مبنای آمار گزارش و مطالعه: {{ $metricsDayLabel }} | {{ $metricsDateLabel }}
                </span>
                <div class="btn-group btn-group-sm" role="group" aria-label="metrics date toggle">
                    <button type="button"
                            wire:click="showPreviousDayMetrics"
                            class="btn {{ $usePreviousDayMetrics ? 'btn-primary' : 'btn-outline-primary' }}">
                        روز قبل
                    </button>
                    <button type="button"
                            wire:click="showCurrentDayMetrics"
                            class="btn {{ !$usePreviousDayMetrics ? 'btn-primary' : 'btn-outline-primary' }}">
                        بدون یک روز قبل
                    </button>
                </div>
            </div>
        </div>
    </div>

    @php
        $dashboardStats = [
            [
                'title' => 'کل دانش‌آموزان تحت پوشش',
                'value' => number_format($totalStudents),
                'valueClass' => 'text-primary',
                'meta' => 'صفحه اصلی دانش‌آموزان',
                'route' => route('admin.student.index'),
            ],
            [
                'title' => 'کل جلسات برگزار شده',
                'value' => number_format($totalCounselingSessions),
                'valueClass' => 'text-info',
                'meta' => 'صفحه اصلی جلسات مشاوره',
                'route' => route('admin.advising-sessions'),
            ],
            [
                'title' => 'گزارشات در انتظار تایید',
                'value' => number_format($pendingReportsCount),
                'valueClass' => 'text-warning',
                'meta' => 'بررسی و تایید گزارش‌ها',
                'route' => route('admin.student.reportDailyActivities.index'),
            ],
            [
                'title' => 'جلسات مشاوره امروز',
                'value' => number_format($todayCounselingSessions->count()),
                'valueClass' => 'text-info',
                'meta' => 'مشاهده لیست جلسات',
                'route' => route('admin.advising-sessions'),
            ],
            [
                'title' => 'پیام‌های خوانده نشده',
                'value' => number_format($unreadMessagesCount),
                'valueClass' => 'text-danger',
                'meta' => 'ورود به گفت‌وگوها',
                'route' => route('admin.consultant.chats'),
            ],
            [
                'title' => "گزارشات ارسال شده ({$metricsDayLabel})",
                'value' => number_format($reportsSentTodayCount),
                'valueClass' => 'text-success',
                'meta' => "مبنای نمایش: {$metricsDateLabel}",
                'route' => route('admin.reportStudentDay'),
            ],
            [
                'title' => "عدم ارسال گزارش ({$metricsDayLabel})",
                'value' => number_format($reportsNotSentTodayCount),
                'valueClass' => 'text-danger',
                'meta' => "دانش‌آموزان بدون گزارش در {$metricsDateLabel}",
                'route' => route('admin.reportStudentDay'),
            ],
            [
                'title' => "ساعت مطالعه دانش‌آموزان ({$metricsDayLabel})",
                'value' => formatDuration($totalStudyDurationToday),
                'valueClass' => 'text-dark',
                'meta' => "شروع نکرده‌اند: {$studentsNotStartedStudy->count()} نفر",
                'route' => route('admin.student.studySession.index'),
            ],
        ];
    @endphp

    <div class="row g-3 mb-4">
        @foreach($dashboardStats as $stat)
            <div class="col-xxl-3 col-xl-4 col-md-6">
                <div class="card card-animate h-100 dashboard-stat-card">
                    <div class="card-body d-flex flex-column">
                        <p class="stat-title">{{ $stat['title'] }}</p>
                        <div class="stat-value {{ $stat['valueClass'] }}">{{ $stat['value'] }}</div>
                        <div class="stat-meta">{{ $stat['meta'] }}</div>
                        <a href="{{ $stat['route'] }}" class="btn btn-sm btn-soft-primary mt-auto">ورود</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>



    {{-- Accordion for Details --}}
    <div class="accordion" id="dashboardAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingPending">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePending">
                    1) لیست کامل گزارش‌های در انتظار تایید ({{ $pendingReportsCount }})
                </button>
            </h2>
            <div id="collapsePending" class="accordion-collapse collapse" data-bs-parent="#dashboardAccordion">
                <div class="accordion-body table-responsive">
                    <table class="table table-sm table-striped align-middle">
                        <thead>
                        <tr>
                            <th>دانش‌آموز</th>
                            <th>استان</th>
                            <th>تعداد گزارش در انتظار</th>
                            <th>آخرین تاریخ گزارش</th>
                            <th>جزئیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($pendingReportsByStudent as $row)
                            <tr>
                                <td>{{ $row['student']?->user?->name ?? '-' }}</td>
                                <td>{{ $row['student']?->user?->personalInformation?->state?->name ?? '-' }}</td>
                                <td><span class="badge bg-warning text-dark">{{ $row['pending_count'] }}</span></td>
                                <td>
                                    @if($row['latest_report_date'])
                                        {{ jalali($row['latest_report_date'])->format('Y/m/d') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($row['student'])
                                        <a href="{{ route('admin.student.reportDailyActivities.detail', $row['student']->id) }}" class="btn btn-sm btn-soft-primary">
                                            بررسی و تایید
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">گزارش در انتظار تاییدی وجود ندارد.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingNoProgram">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNoProgram">
                    2) دانش‌آموزان بدون برنامه/جلسه یا نیازمند جلسه جدید ({{ $studentsWithoutProgramOrSession->count() }})
                </button>
            </h2>
            <div id="collapseNoProgram" class="accordion-collapse collapse" data-bs-parent="#dashboardAccordion">
                <div class="accordion-body table-responsive">
                    <table class="table table-sm table-striped align-middle">
                        <thead><tr><th>دانش‌آموز</th><th>استان</th><th>آخرین جلسه</th><th>توضیح</th><th>جزئیات</th></tr></thead>
                        <tbody>
                        @forelse($studentsWithoutProgramOrSession as $student)
                            @php
                                $latestSession = $student->advisingSessions->first();
                                $nextExpected = $latestSession?->activation_date ? \Carbon\Carbon::parse($latestSession->activation_date)->addDays(7) : null;
                            @endphp
                            <tr>
                                <td>{{ $student->user?->name ?? '-' }}</td>
                                <td>{{ $student->user?->personalInformation?->state?->name ?? '-' }}</td>
                                <td>
                                    @if($latestSession?->activation_date)
                                        {{jalali($latestSession->activation_date)->format('Y/m/d')}}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if(!$student->weeklyPrograms->first())
                                        <span class="badge bg-danger">بدون برنامه هفتگی</span>
                                    @elseif(!$latestSession)
                                        <span class="badge bg-danger">بدون جلسه مشاوره</span>
                                    @elseif($nextExpected && $nextExpected->lt(now()))
                                        <span class="badge bg-warning text-white">جلسه جدید باید تا   {{jalali($nextExpected)->format('Y/m/d')}} ثبت می‌شد</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.student.advising-sessions.create', $student->id) }}" class="btn btn-sm btn-soft-warning">
                                        ایجاد/پیگیری جلسه
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">موردی وجود ندارد.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTodaySessionsHeader">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTodaySessions">
                    3) لیست جلسات مشاوره امروز ({{ $todayCounselingSessions->count() }})
                </button>
            </h2>
            <div id="collapseTodaySessions" class="accordion-collapse collapse" data-bs-parent="#dashboardAccordion">
                <div class="accordion-body table-responsive">
                    <table class="table table-sm table-striped align-middle">
                        <thead><tr><th>دانش‌آموز</th><th>مشاور</th><th>استان</th><th>ساعت</th><th>وضعیت</th><th>جزئیات</th></tr></thead>
                        <tbody>
                        @forelse($todayCounselingSessions as $session)
                            <tr>
                                <td>{{ $session->student?->user?->name ?? '-' }}</td>
                                <td>{{ $session->advisor?->user?->name ?? '-' }}</td>
                                <td>{{ $session->student?->user?->personalInformation?->state?->name ?? '-' }}</td>
                                <td>{{ $session->session_time?->format('H:i') ?? '-' }}</td>
                                <td><span class="badge bg-secondary">{{ $session->status_label }}</span></td>
                                <td>
                                    <a href="{{ route('admin.student.weekly-program', ['student' => $session->student_id, 'session' => $session->id]) }}" class="btn btn-sm btn-soft-info">
                                        ورود به جزئیات
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">امروز جلسه‌ای ثبت نشده است.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwoDays">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwoDays">
                    4) دانش‌آموزان بدون ارسال گزارش در ۲ روز متوالی ({{ $studentsWithoutTwoDaysReport->count() }})
                </button>
            </h2>
            <div id="collapseTwoDays" class="accordion-collapse collapse" data-bs-parent="#dashboardAccordion">
                <div class="accordion-body table-responsive">
                    <table class="table table-sm table-striped align-middle">
                        <thead><tr><th>دانش‌آموز</th><th>استان</th><th>توضیح</th><th>جزئیات</th></tr></thead>
                        <tbody>
                        @forelse($studentsWithoutTwoDaysReport as $student)
                            <tr>
                                <td>{{ $student->user?->name ?? '-' }}</td>
                                <td>{{ $student->user?->personalInformation?->state?->name ?? '-' }}</td>
                                <td class="text-danger">در دو روز گذشته گزارشی ارسال نشده است.</td>
                                <td>
                                    <a href="{{ route('admin.student.reportDailyActivities.detail', $student->id) }}" class="btn btn-sm btn-soft-danger">
                                        بررسی گزارش
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">موردی وجود ندارد.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingLowCompletion">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLowCompletion">
                    5) دانش‌آموزان با کمتر از ۵۰٪ انجام برنامه امروز ({{ $lowCompletionRows->count() }})
                </button>
            </h2>
            <div id="collapseLowCompletion" class="accordion-collapse collapse" data-bs-parent="#dashboardAccordion">
                <div class="accordion-body">
                    @forelse($lowCompletionRows as $row)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>{{ $row['student']?->user?->name ?? '-' }}</strong>
                                <small class="text-muted">{{ $row['completed'] }} از {{ $row['planned'] }} پارت</small>
                            </div>
                            <div class="progress" role="progressbar" aria-valuenow="{{ $row['percentage'] }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-danger" style="width: {{ $row['percentage'] }}%">{{ $row['percentage'] }}%</div>
                            </div>
                            @if($row['student'])
                                <div class="mt-2 text-end">
                                    <a href="{{ route('admin.student.studySession.detail', $row['student']->id) }}" class="btn btn-sm btn-soft-primary">
                                        ورود به جزئیات
                                    </a>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-muted">هیچ دانش‌آموزی با انجام کمتر از ۵۰٪ برنامه امروز یافت نشد.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingNoStudyTodayHeader">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNoStudyToday">
                    6) دانش‌آموزانی که امروز هیچ ثبت مطالعه‌ای نداشته‌اند ({{ $studentsNotStartedStudy->count() }})
                </button>
            </h2>
            <div id="collapseNoStudyToday" class="accordion-collapse collapse" data-bs-parent="#dashboardAccordion">
                <div class="accordion-body table-responsive">
                    <table class="table table-sm table-striped align-middle">
                        <thead><tr><th>دانش‌آموز</th><th>استان</th><th>جزئیات</th></tr></thead>
                        <tbody>
                        @forelse($studentsNotStartedStudy as $student)
                            <tr>
                                <td>{{ $student->user?->name ?? '-' }}</td>
                                <td>{{ $student->user?->personalInformation?->state?->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.student.studySession.detail', $student->id) }}" class="btn btn-sm btn-soft-secondary">
                                        ورود به جزئیات
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted">همه دانش‌آموزان امروز حداقل یک مطالعه ثبت کرده‌اند.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <br>
    <div class="card iran-map-card mb-4">
        <div class="card-header bg-transparent border-0 pt-4 px-4">
            <h5 class="mb-1 text-white">پراکندگی دانش‌آموزان بر اساس استان</h5>
            <p class="mb-0 text-white-50">روی هر استان هاور کنید تا تعداد دقیق دانش‌آموزان همان استان نمایش داده شود.</p>
        </div>
        <div class="card-body iran-map-shell px-4 pb-4">
            @if($studentsPerState->isNotEmpty())
                <div class="iran-map-summary">
                    <div class="iran-map-pill">کل دانش‌آموزان: {{ number_format($totalStudents) }}</div>
                    <div class="iran-map-pill">استان‌های دارای دانش‌آموز: {{ number_format($studentsPerState->count()) }}</div>
                    <div class="iran-map-pill">بیشترین تمرکز: {{ $studentsPerState->first()->state_name ?? '—' }}</div>
                </div>

                <div class="iran-map-board">
                    <div class="iran-map-stage">
                        <div id="iran-map-container">
                            @include('livewire.admin.dashboard.partials.iran-map-svg')
                        </div>
                    </div>

                    <div class="iran-map-sidepanel">
                        <div class="eyebrow">وضعیت استان انتخابی</div>
                        <div class="province-name" id="iran-map-province-name">نقشه ایران</div>
                        <div class="province-count" id="iran-map-province-count">{{ number_format($totalStudents) }}</div>
                        <div class="province-help" id="iran-map-province-help">
                            ماوس را روی یکی از استان‌ها نگه دارید تا تعداد دقیق دانش‌آموزان آن استان را ببینید.
                        </div>

                        <div class="iran-map-toplist">
                            <div class="eyebrow">استان‌های برتر</div>
                            @foreach($studentsPerState->take(5) as $item)
                                <div class="iran-map-topitem">
                                    <span>{{ $item->state_name }}</span>
                                    <span class="badge bg-primary-subtle text-primary">{{ number_format($item->students_count) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="iran-map-footer">
                    هرچه رنگ استان پررنگ‌تر باشد، تعداد دانش‌آموزان آن استان بیشتر است.
                </div>
            @else
                <div class="alert alert-light text-center">داده‌ای برای نمایش پراکندگی دانش‌آموزان وجود ندارد.</div>
            @endif
        </div>
    </div>


    {{-- Exam and Classification Stats --}}
    <div class="row g-3 mb-4">
        {{-- Exam Stats --}}
        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">آمار آزمون‌ها</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h6>آزمون‌های تستی</h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    تعداد کل آزمون‌ها
                                    <span class="badge bg-primary">{{ number_format($examStats['totalTypedTestsCount']) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                   شرکت نکرده (در حال برگزاری)
                                    <span class="badge bg-warning text-dark">{{ number_format($examStats['unattendedTypedTestsCount']) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    بدون نتیجه (مهلت تمام شده)
                                    <span class="badge bg-danger">{{ number_format($examStats['overdueTypedTestsCount']) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    میانگین درصد دانش‌آموزان من
                                    <span class="badge bg-info">{{ $examStats['avgTypedTestScore'] }}%</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-6 mt-3 mt-lg-0">
                            <h6>آزمون‌های تشریحی</h6>
                             <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    تعداد کل آزمون‌ها
                                    <span class="badge bg-primary">{{ number_format($examStats['totalEssayTestsCount']) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                   شرکت نکرده (در حال برگزاری)
                                    <span class="badge bg-warning text-dark">{{ number_format($examStats['unattendedEssayTestsCount']) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    بدون نتیجه (مهلت تمام شده)
                                    <span class="badge bg-danger">{{ number_format($examStats['overdueEssayTestsCount']) }}</span>
                                </li>
                                 <li class="list-group-item d-flex justify-content-between align-items-center">
                                    میانگین نمره دانش‌آموزان من
                                    <span class="badge bg-info">{{ $examStats['avgEssayTestScore'] }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Classification Stats --}}
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">آمار طبقه‌بندی</h5></div>
                <div class="card-body">
                    @if($classificationStats['activeClassificationProject'])
                        <p class="text-muted">اطلاعات بر اساس طبقه‌بندی فعال:
                            <strong>{{$classificationStats['activeClassificationProject']->name}}</strong>
                            (تا تاریخ: {{ jalali($classificationStats['activeClassificationProject']->end_date)->format('Y/m/d') }})
                        </p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                تعداد انجام داده
                                <span class="badge bg-success">{{ number_format($classificationStats['classifiedStudentsCount']) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                تعداد انجام نداده
                                <span class="badge bg-danger">{{ number_format($classificationStats['unclassifiedStudentsCount']) }}</span>
                            </li>
                        </ul>
                    @else
                        <div class="alert alert-warning">در حال حاضر هیچ پروژه طبقه‌بندی فعالی (به جز هفته آزمایشی) وجود ندارد.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- Charts --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">میانگین انجام برنامه (۶ هفته اخیر)</h5></div>
                <div class="card-body">
                    <div id="weekly-completion-chart" style="min-height: 320px"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">توزیع انجام برنامه امروز</h5></div>
                <div class="card-body">
                    <div id="completion-distribution-chart" style="min-height: 320px"></div>
                </div>
            </div>
        </div>
    </div>


    @push('script')
        <script>
            function renderDashboardCharts() {
                // ... existing chart logic
            }
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.6/lottie.min.js"></script>
        <script>
            document.addEventListener('livewire:navigated', () => {
                initDashboard();
            });
            document.addEventListener('DOMContentLoaded', () => {
                initDashboard();
            });

            function initDashboard() {
                renderDashboardCharts();
                loadIranMap();
            }

            function renderDashboardCharts() {
                const weeklyData = @json($weeklyCompletionChart);
                const distributionData = @json($completionDistribution);

                const weeklyEl = document.querySelector('#weekly-completion-chart');
                const distEl = document.querySelector('#completion-distribution-chart');
                if (!weeklyEl || !distEl || typeof ApexCharts === 'undefined') {
                    return;
                }

                weeklyEl.innerHTML = '';
                distEl.innerHTML = '';

                new ApexCharts(weeklyEl, {
                    chart: { type: 'line', height: 320, toolbar: { show: false } },
                    stroke: { width: 3, curve: 'smooth' },
                    dataLabels: { enabled: false },
                    series: [{ name: 'میانگین درصد انجام', data: weeklyData.values }],
                    xaxis: { categories: weeklyData.labels },
                    yaxis: { max: 100, min: 0, labels: { formatter: val => `${Math.round(val)}%` } },
                    tooltip: { y: { formatter: val => `${val}%` } },
                    colors: ['#405189']
                }).render();

                new ApexCharts(distEl, {
                    chart: { type: 'bar', height: 320, toolbar: { show: false } },
                    plotOptions: { bar: { borderRadius: 6, horizontal: false } },
                    dataLabels: { enabled: true },
                    series: [{ name: 'تعداد دانش‌آموز', data: distributionData.values }],
                    xaxis: { categories: distributionData.labels },
                    colors: ['#0ab39c'],
                    tooltip: { y: { formatter: val => `${val} دانش‌آموز` } }
                }).render();
            }

            function loadIranMap() {
                const mapContainer = document.getElementById('iran-map-container');
                if(!mapContainer) return;
                const provinceNameEl = document.getElementById('iran-map-province-name');
                const provinceCountEl = document.getElementById('iran-map-province-count');
                const provinceHelpEl = document.getElementById('iran-map-province-help');

                const studentsPerState = @json($studentsPerState);
                const provincePaths = mapContainer.querySelectorAll('.province');
                if (!provincePaths.length) return;
                if (mapContainer.dataset.iranMapBound === '1') return;
                mapContainer.dataset.iranMapBound = '1';
                const tooltip = document.createElement('div');
                tooltip.style.position = 'absolute';
                tooltip.style.background = 'linear-gradient(180deg, rgba(18,27,43,0.96), rgba(25,36,57,0.96))';
                tooltip.style.color = '#fff';
                tooltip.style.padding = '8px 12px';
                tooltip.style.borderRadius = '12px';
                tooltip.style.border = '1px solid rgba(170, 214, 255, 0.25)';
                tooltip.style.boxShadow = '0 14px 30px rgba(0,0,0,0.28)';
                tooltip.style.fontSize = '13px';
                tooltip.style.pointerEvents = 'none';
                tooltip.style.display = 'none';
                tooltip.style.zIndex = '20';
                mapContainer.appendChild(tooltip);

                const provinceAliases = {
                    'آذربایجان شرقی': ['آذربایجان شرقی', 'آذربايجان شرقي'],
                    'آذربایجان غربی': ['آذربایجان غربی', 'آذربايجان غربي'],
                    'اردبیل': ['اردبیل', 'اردبيل'],
                    'اصفهان': ['اصفهان'],
                    'البرز': ['البرز'],
                    'ایلام': ['ایلام', 'ايلام'],
                    'بوشهر': ['بوشهر'],
                    'تهران': ['تهران'],
                    'چهارمحال و بختیاری': ['چهارمحال و بختیاری', 'چهارمحال و بختياري'],
                    'خراسان جنوبی': ['خراسان جنوبی', 'خراسان جنوبي'],
                    'خراسان رضوی': ['خراسان رضوی', 'خراسان رضوي'],
                    'خراسان شمالی': ['خراسان شمالی', 'خراسان شمالي'],
                    'خوزستان': ['خوزستان'],
                    'زنجان': ['زنجان'],
                    'سمنان': ['سمنان'],
                    'سیستان و بلوچستان': ['سیستان و بلوچستان', 'سيستان و بلوچستان'],
                    'فارس': ['فارس'],
                    'قزوین': ['قزوین', 'قزوين'],
                    'قم': ['قم'],
                    'کردستان': ['کردستان'],
                    'کرمان': ['کرمان'],
                    'کرمانشاه': ['کرمانشاه'],
                    'کهگیلویه و بویراحمد': ['کهگیلویه و بویراحمد', 'کهگيلويه و بويراحمد'],
                    'گلستان': ['گلستان'],
                    'گیلان': ['گیلان', 'گيلان'],
                    'لرستان': ['لرستان'],
                    'مازندران': ['مازندران'],
                    'مرکزی': ['مرکزی', 'مرکزي'],
                    'هرمزگان': ['هرمزگان'],
                    'همدان': ['همدان'],
                    'یزد': ['یزد'],
                };

                function normalizeProvinceName(name) {
                    return String(name || '')
                        .replace(/ك/g, 'ک')
                        .replace(/ي/g, 'ی')
                        .replace(/[‌\-]/g, ' ')
                        .replace(/\s+/g, ' ')
                        .trim();
                }

                const stateData = {};
                studentsPerState.forEach(item => {
                    stateData[normalizeProvinceName(item.state_name)] = Number(item.students_count || 0);
                });

                const normalizedAliases = {};
                Object.entries(provinceAliases).forEach(([canonicalName, aliases]) => {
                    aliases.forEach(alias => {
                        normalizedAliases[normalizeProvinceName(alias)] = canonicalName;
                    });
                    normalizedAliases[normalizeProvinceName(canonicalName)] = canonicalName;
                });

                function resolveProvinceCount(provinceName) {
                    const canonicalName = normalizedAliases[normalizeProvinceName(provinceName)] || normalizeProvinceName(provinceName);
                    return Number(stateData[canonicalName] || stateData[normalizeProvinceName(provinceName)] || 0);
                }

                const counts = Array.from(provincePaths).map(path => resolveProvinceCount(path.dataset.name));
                const maxCount = Math.max(...counts, 0);

                function getProvinceFill(count) {
                    if (!count) {
                        return '#d6d8de';
                    }

                    if (!maxCount) {
                        return '#6ea8fe';
                    }

                    const ratio = count / maxCount;

                    if (ratio >= 0.85) return '#164eab';
                    if (ratio >= 0.65) return '#2563c9';
                    if (ratio >= 0.45) return '#3d7be0';
                    if (ratio >= 0.25) return '#5b9aee';

                    return '#84b9f8';
                }

                function updateProvincePanel(provinceName, count) {
                    if (!provinceNameEl || !provinceCountEl || !provinceHelpEl) {
                        return;
                    }

                    provinceNameEl.textContent = provinceName;
                    provinceCountEl.textContent = count.toLocaleString('fa-IR');
                    provinceHelpEl.textContent = count > 0
                        ? `در حال حاضر ${count.toLocaleString('fa-IR')} دانش‌آموز از استان ${provinceName} در سیستم ثبت شده است.`
                        : `در حال حاضر دانش‌آموزی با استان ${provinceName} ثبت نشده است.`;
                }

                function resetProvincePanel() {
                    if (!provinceNameEl || !provinceCountEl || !provinceHelpEl) {
                        return;
                    }

                    provinceNameEl.textContent = 'نقشه ایران';
                    provinceCountEl.textContent = '{{ number_format($totalStudents) }}';
                    provinceHelpEl.textContent = 'ماوس را روی یکی از استان‌ها نگه دارید تا تعداد دقیق دانش‌آموزان آن استان را ببینید.';
                }

                provincePaths.forEach(path => {
                    const provinceName = path.dataset.name;
                    const count = resolveProvinceCount(provinceName);

                    path.dataset.count = count;
                    path.style.fill = getProvinceFill(count);

                    path.addEventListener('mouseenter', () => {
                        provincePaths.forEach(item => item.classList.remove('is-active'));
                        path.classList.add('is-active');
                        updateProvincePanel(provinceName, count);
                        tooltip.style.display = 'block';
                        tooltip.innerHTML = `
                            <div style="font-weight:700; margin-bottom:4px;">${provinceName}</div>
                            <div>تعداد دانش‌آموز: <strong>${count.toLocaleString('fa-IR')}</strong></div>
                        `;
                    });

                    path.addEventListener('mousemove', (event) => {
                        const rect = mapContainer.getBoundingClientRect();
                        tooltip.style.left = `${event.clientX - rect.left + 18}px`;
                        tooltip.style.top = `${event.clientY - rect.top - 10}px`;
                    });

                    path.addEventListener('mouseleave', () => {
                        path.classList.remove('is-active');
                        tooltip.style.display = 'none';
                        resetProvincePanel();
                    });
                });

                resetProvincePanel();
            }

        </script>
    @endpush
</div>
