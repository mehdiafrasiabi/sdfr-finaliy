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
    <div class="row mt-4 mb-3">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <h4 class="mb-0">داشبورد تحلیلی مشاوره</h4>
            <small class="text-muted">آخرین بروزرسانی: {{ now()->format('Y/m/d H:i') }}</small>
        </div>
    </div>

    {{-- First Row of Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">کل دانش‌آموزان تحت پوشش</p>
                    <h3 class="mb-0">{{ number_format($totalStudents) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">کل جلسات برگزار شده</p>
                    <h3 class="mb-0">{{ number_format($totalCounselingSessions) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100">
                <div class="card-body d-flex flex-column">
                    <p class="text-muted mb-2">گزارشات در انتظار تایید</p>
                    <h3 class="mb-1 text-warning">{{ number_format($pendingReportsCount) }}</h3>
                    <a href="{{ route('admin.student.reportDailyActivities.index') }}" class="btn btn-sm btn-soft-primary mt-auto">مشاهده صفحه گزارشات</a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100">
                <div class="card-body">
                     <p class="text-muted mb-2">جلسات مشاوره امروز</p>
                    <h3 class="mb-0 text-info">{{ number_format($todayCounselingSessions->count()) }}</h3>
                     <button type="button" class="btn btn-sm btn-soft-info mt-2" data-bs-toggle="collapse" data-bs-target="#collapseTodaySessions">
                        نمایش جزئیات
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Second Row of Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">پیام‌های خوانده نشده</p>
                    <h3 class="mb-0 text-danger">{{ number_format($unreadMessagesCount) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">گزارشات ارسال شده (امروز)</p>
                    <h3 class="mb-0 text-success">{{ number_format($reportsSentTodayCount) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">عدم ارسال گزارش (امروز)</p>
                    <h3 class="mb-0 text-danger">{{ number_format($reportsNotSentTodayCount) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">ساعت مطالعه دانش‌آموزان (امروز)</p>
                    <h3 class="mb-0">{{ formatDuration($totalStudyDurationToday) }}</h3>
                     <button type="button" class="btn btn-sm btn-soft-secondary mt-2" data-bs-toggle="collapse" data-bs-target="#collapseNoStudyToday">
                        افرادی که شروع نکرده‌اند ({{$studentsNotStartedStudy->count()}})
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Iran Map --}}
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">پراکندگی دانش‌آموزان بر اساس استان</h5></div>
        <div class="card-body text-center">
            @if($studentsPerState->isNotEmpty())
                <div id="iran-map-container" style="width: 100%; max-width: 700px; margin: auto; position: relative;">
                    {{-- SVG map will be injected here by script --}}
                </div>
                <p class="form-text mt-2">روی هر استان رنگی هاور کنید تا تعداد دانش آموزان را ببینید</p>
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


    {{-- Accordion for Details --}}
    <div class="accordion" id="dashboardAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingPending">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePending">
                    ۱) لیست کامل گزارش‌های Pending ({{ $pendingReportsCount }})
                </button>
            </h2>
            <div id="collapsePending" class="accordion-collapse collapse show" data-bs-parent="#dashboardAccordion">
                <div class="accordion-body table-responsive">
                    <table class="table table-sm table-striped align-middle">
                        <thead>
                        <tr>
                            <th>دانش‌آموز</th><th>استان</th><th>تعداد گزارش Pending</th><th>آخرین تاریخ گزارش</th><th>جزئیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($pendingReportsByStudent as $row)
                            <tr>
                                <td>{{ $row['student']?->user?->name ?? '-' }}</td>
                                <td>{{ $row['student']?->user?->personalInformation?->state?->name ?? '-' }}</td>
                                <td><span class="badge bg-warning">{{ $row['pending_count'] }}</span></td>
                                <td>
                                    @if($row['latest_report_date'])
                                        {{jalali($row['latest_report_date'])->format('Y/m/d')}}
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
                            <tr><td colspan="5" class="text-center text-muted">گزارش Pending وجود ندارد.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingNoProgram">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNoProgram">
                    ۲) دانش‌آموزان بدون برنامه/جلسه یا نیازمند جلسه جدید ({{ $studentsWithoutProgramOrSession->count() }})
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
                    ۳) لیست جلسات مشاوره امروز ({{ $todayCounselingSessions->count() }})
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
                    ۴) دانش‌آموزان بدون ارسال گزارش در ۲ روز متوالی ({{ $studentsWithoutTwoDaysReport->count() }})
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
                    ۵) دانش‌آموزان با کمتر از ۵۰٪ انجام برنامه امروز ({{ $lowCompletionRows->count() }})
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
                    ۶) دانش‌آموزانی که امروز هیچ ثبت مطالعه‌ای نداشته‌اند ({{ $studentsNotStartedStudy->count() }})
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

                // A simple SVG map of Iran with provinces. In a real project, this would be a separate file.
                const iranSvg = `
        <svg xmlns="http://www.w3.org/2000/svg" width="500" height="500" viewBox="0 0 1000 922">
            <style>.province { fill: #d4d4d4; stroke: #fff; stroke-width: 2px; transition: fill 0.3s; } .province.has-students { fill: #405189; } .sea { fill: #aadaff; }</style>
            <path class="sea" d="M371 0h133v33h-22v20h-91v-20h-20zm149 0h149v20h-20v13h-44v-13h-85zm165 0h109v20h-20v13h-30v-13h-59z"/>
            <path class="sea" d="M0 869h1000v53h-1000z"/>
            <text x="500" y="50" text-anchor="middle" font-size="24" fill="#003366">دریای خزر</text>
            <text x="500" y="900" text-anchor="middle" font-size="24" fill="#003366">خلیج فارس</text>
            <path id="آذربایجان-شرقی" class="province" d="M300 100 l50 -50 l100 0 l50 50 l-50 50 l-100 0z" data-name="آذربایجان شرقی"/>
            <path id="آذربایجان-غربی" class="province" d="M200 150 l50 -50 l100 0 l50 50 l-50 50 l-100 0z" data-name="آذربایجان غربی"/>
            <path id="اردبیل" class="province" d="M350 50 l50 -30 l100 0 l50 30 l-50 50 l-100 0z" data-name="اردبیل"/>
            <path id="اصفهان" class="province" d="M450 450 l100 -50 l100 50 l0 100 l-100 50 l-100 -50z" data-name="اصفهان"/>
            <path id="البرز" class="province" d="M400 300 l40 0 l10 40 l-50 0z" data-name="البرز"/>
            <path id="ایلام" class="province" d="M250 450 l50 -50 l50 0 l0 100 l-50 0 l-50 -50z" data-name="ایلام"/>
            <path id="بوشهر" class="province" d="M450 700 l50 -50 l100 0 l0 50 l-150 0z" data-name="بوشهر"/>
            <path id="تهران" class="province" d="M450 300 l50 0 l0 50 l-50 0z" data-name="تهران"/>
            <path id="چهارمحال-و-بختیاری" class="province" d="M380 550 l70 -40 l50 40 l-20 60 l-100 0z" data-name="چهارمحال و بختیاری"/>
            <path id="خراسان-جنوبی" class="province" d="M700 450 l100 0 l50 100 l-50 50 l-100 0 l-50 -100z" data-name="خراسان جنوبی"/>

<path id="خراسان-رضوی" class="province" d=" M 716.74 145.50 C 717.84 144.45 718.96 143.40 720.07 142.36 C 728.00 145.26 734.62 151.18 743.10 152.57 C 745.49 152.72 745.89 155.46 746.65 157.24 C 748.22 162.63 750.47 168.50 755.58 171.47 C 762.66 174.52 770.17 176.61 777.07 180.05 C 780.25 183.18 782.81 186.91 786.06 189.99 C 789.65 193.37 790.09 199.60 794.99 201.54 C 801.00 202.05 807.01 200.64 813.04 201.02 C 820.66 201.41 828.22 200.34 835.82 199.96 C 834.93 205.33 833.36 210.60 832.94 216.05 C 832.47 220.93 836.47 224.62 837.21 229.23 C 836.76 233.14 834.95 236.74 833.80 240.47 C 836.17 242.59 839.02 244.44 840.49 247.36 C 840.73 250.47 839.83 253.59 840.32 256.70 C 840.82 259.15 841.78 261.47 842.64 263.82 C 839.82 268.32 837.58 273.24 837.35 278.63 C 836.68 278.73 835.33 278.94 834.66 279.05 C 834.17 282.42 834.77 285.75 835.73 288.98 C 836.12 293.03 834.93 297.05 834.81 301.10 C 834.62 305.96 832.15 310.23 830.25 314.57 C 829.13 316.85 828.09 319.37 825.90 320.83 C 823.19 322.68 820.11 323.93 817.53 325.98 C 820.20 330.15 824.27 333.16 827.18 337.15 C 823.91 337.15 820.27 336.27 817.35 338.18 C 813.24 340.86 812.01 346.18 807.98 348.95 C 803.92 351.95 800.00 355.12 795.96 358.14 C 792.47 355.76 789.25 352.81 785.20 351.41 C 779.93 350.18 774.23 352.09 769.18 349.72 C 765.73 347.98 761.85 347.65 758.05 347.87 C 747.69 348.31 737.25 347.26 726.96 348.86 C 723.66 349.16 721.41 351.83 718.99 353.78 C 716.11 352.14 713.04 350.93 709.92 349.86 C 704.52 348.50 703.64 339.41 697.08 341.18 C 689.87 342.48 682.66 340.55 676.21 337.39 C 677.54 333.69 679.41 330.01 679.37 326.00 C 678.38 321.03 673.60 318.28 670.93 314.31 C 669.50 310.58 671.13 305.89 668.37 302.61 C 665.68 299.23 662.44 296.33 659.67 293.01 C 655.16 292.99 650.63 292.60 646.15 293.18 C 642.01 293.97 639.25 297.55 635.49 299.14 C 632.39 299.61 629.24 299.33 626.13 299.43 C 625.84 304.72 625.90 310.87 621.71 314.79 C 616.23 320.04 608.11 320.10 601.01 320.00 C 603.13 315.69 605.76 311.65 608.86 307.98 C 612.38 303.77 614.93 298.75 619.06 295.07 C 623.48 291.06 630.41 291.94 634.67 287.69 C 637.86 283.99 637.30 278.62 639.56 274.44 C 641.08 271.25 643.92 268.59 644.37 264.95 C 640.59 257.94 631.92 255.13 628.37 247.88 C 625.19 241.03 622.38 233.57 623.09 225.89 C 623.38 221.61 626.29 218.14 627.12 214.02 C 627.79 210.99 625.69 208.01 622.93 206.95 C 620.56 207.20 618.31 208.02 615.98 208.45 C 613.16 209.19 610.50 207.67 607.89 206.87 C 610.58 201.29 613.09 195.53 616.87 190.58 C 619.01 187.81 622.86 188.12 625.98 188.00 C 633.35 188.10 640.71 187.85 648.07 188.13 C 652.50 188.16 655.56 191.71 658.75 194.27 C 665.49 199.87 674.10 203.79 683.01 203.38 C 684.65 203.26 686.57 203.14 687.69 201.75 C 688.43 197.33 687.97 192.83 688.12 188.38 C 690.46 186.28 693.77 184.42 694.15 180.98 C 694.94 176.73 690.54 174.37 688.90 170.97 C 689.47 169.33 690.78 168.08 692.44 167.57 C 696.39 166.10 700.44 164.88 704.13 162.80 C 701.27 160.39 697.76 158.58 695.63 155.46 C 694.76 152.56 695.08 149.47 694.99 146.48 C 700.16 147.51 705.15 145.84 709.62 143.34 C 711.97 144.12 714.35 144.83 716.74 145.50 Z"></path>
            <path id="خراسان-شمالی" class="province" d="M700 200 l100 0 l50 100 l-150 0z" data-name="خراسان شمالی"/>
            <path id="خوزستان" class="province" d="M350 600 l100 0 l0 100 l-100 0z" data-name="خوزستان"/>
            <path id="زنجان" class="province" d="M350 200 l100 -50 l100 50 l0 50 l-200 0z" data-name="زنجان"/>
            <path id="سمنان" class="province" d="M550 300 l150 0 l0 100 l-150 0z" data-name="سمنان"/>
            <path id="سیستان-و-بلوچستان" class="province" d="M750 600 l150 0 l50 150 l-200 -50z" data-name="سیستان و بلوچستان"/>
            <path id="فارس" class="province" d="M500 600 l150 0 l50 100 l-200 -50z" data-name="فارس"/>
            <path id="قزوین" class="province" d="M400 250 l50 -50 l50 50 l-50 50z" data-name="قزوین"/>
            <path id="قم" class="province" d="M470 380 l40 0 l0 40 l-40 0z" data-name="قم"/>
            <path id="کردستان" class="province" d="M250 300 l100 0 l0 100 l-100 0z" data-name="کردستان"/>
            <path id="کرمان" class="province" d="M650 550 l100 50 l50 100 l-150 -50z" data-name="کرمان"/>
            <path id="کرمانشاه" class="province" d="M250 380 l100 0 l0 70 l-100 0z" data-name="کرمانشاه"/>
            <path id="کهگیلویه-و-بویراحمد" class="province" d="M430 600 l70 0 l0 70 l-70 0z" data-name="کهگیلویه و بویراحمد"/>
            <path id="گلستان" class="province" d="M600 150 l100 50 l-50 50 l-50 -100z" data-name="گلستان"/>
            <path id="گیلان" class="province" d="M450 100 l100 -50 l50 50 l-50 50z" data-name="گیلان"/>
            <path id="لرستان" class="province" d="M350 500 l80 0 l0 80 l-80 0z" data-name="لرستان"/>
            <path id="مازندران" class="province" d="M550 200 l100 0 l0 50 l-100 0z" data-name="مازندران"/>
            <path id="مرکزی" class="province" d="M400 400 l50 0 l0 80 l-50 0z" data-name="مرکزی"/>
            <path id="هرمزگان" class="province" d="M600 750 l150 0 l0 50 l-150 0z" data-name="هرمزگان"/>
            <path id="همدان" class="province" d="M350 350 l50 0 l0 50 l-50 0z" data-name="همدان"/>
            <path id="یزد" class="province" d="M600 450 l100 0 l0 100 l-100 0z" data-name="یزد"/>
        </svg>
        `;
                mapContainer.innerHTML = iranSvg;

                const studentsPerState = @json($studentsPerState);
                const provincePaths = mapContainer.querySelectorAll('.province');
                const tooltip = document.createElement('div');
                tooltip.style.position = 'absolute';
                tooltip.style.background = 'rgba(0,0,0,0.8)';
                tooltip.style.color = '#fff';
                tooltip.style.padding = '5px 10px';
                tooltip.style.borderRadius = '5px';
                tooltip.style.pointerEvents = 'none';
                tooltip.style.display = 'none';
                mapContainer.appendChild(tooltip);

                const stateData = {};
                studentsPerState.forEach(item => {
                    stateData[item.state_name] = item.students_count;
                });

                provincePaths.forEach(path => {
                    const provinceName = path.dataset.name;
                    if (stateData[provinceName]) {
                        path.classList.add('has-students');
                        path.dataset.count = stateData[provinceName];
                    }

                    path.addEventListener('mousemove', (e) => {
                        const count = path.dataset.count;
                        if(count) {
                            tooltip.style.display = 'block';
                            tooltip.style.left = `${e.offsetX + 15}px`;
                            tooltip.style.top = `${e.offsetY}px`;
                            tooltip.innerHTML = `${provinceName}: <strong>${count}</strong>`;
                        }
                    });

                    path.addEventListener('mouseleave', () => {
                        tooltip.style.display = 'none';
                    });
                });
            }

        </script>
    @endpush
</div>
