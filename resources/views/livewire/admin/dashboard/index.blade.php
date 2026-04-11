<div>
    <div class="row mt-4 mb-3">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <h4 class="mb-0">داشبورد تحلیلی مشاوره</h4>
            <small class="text-muted">آخرین بروزرسانی: {{ now()->format('Y/m/d H:i') }}</small>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">تعداد کل دانش‌آموزان</p>
                    <h3 class="mb-0">{{ number_format($totalStudents) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">تعداد کل جلسات مشاوره</p>
                    <h3 class="mb-0">{{ number_format($totalCounselingSessions) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">گزارش‌های Pending</p>
                    <h3 class="mb-0 text-warning">{{ number_format($pendingReportsCount) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">جلسات مشاوره امروز</p>
                    <h3 class="mb-0 text-info">{{ number_format($todayCounselingSessions->count()) }}</h3>
                </div>
            </div>
        </div>
    </div>

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

    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">پراکندگی دانش‌آموزان بر اساس استان</h5></div>
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>استان</th>
                    <th>تعداد دانش‌آموز</th>
                </tr>
                </thead>
                <tbody>
                @forelse($studentsPerState as $row)
                    <tr>
                        <td>{{ $row->state_name }}</td>
                        <td>{{ number_format($row->students_count) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-center text-muted">داده‌ای ثبت نشده است.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

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
            <h2 class="accordion-header" id="headingTodaySessions">
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
            <h2 class="accordion-header" id="headingNoStudyToday">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNoStudyToday">
                    ۶) دانش‌آموزانی که امروز هیچ ثبت مطالعه‌ای نداشته‌اند ({{ $noStudyTodayRows->count() }})
                </button>
            </h2>
            <div id="collapseNoStudyToday" class="accordion-collapse collapse" data-bs-parent="#dashboardAccordion">
                <div class="accordion-body table-responsive">
                    <table class="table table-sm table-striped align-middle">
                        <thead><tr><th>دانش‌آموز</th><th>استان</th><th>پارت برنامه امروز</th><th>جزئیات</th></tr></thead>
                        <tbody>
                        @forelse($noStudyTodayRows as $row)
                            <tr>
                                <td>{{ $row['student']?->user?->name ?? '-' }}</td>
                                <td>{{ $row['student']?->user?->personalInformation?->state?->name ?? '-' }}</td>
                                <td>{{ $row['planned'] }}</td>
                                <td>
                                    @if($row['student'])
                                        <a href="{{ route('admin.student.studySession.detail', $row['student']->id) }}" class="btn btn-sm btn-soft-secondary">
                                            ورود به جزئیات
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">همه دانش‌آموزان امروز حداقل یک مطالعه ثبت کرده‌اند.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        document.addEventListener('livewire:navigated', renderDashboardCharts);
        document.addEventListener('DOMContentLoaded', renderDashboardCharts);

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
    </script>
    @endpush
</div>
