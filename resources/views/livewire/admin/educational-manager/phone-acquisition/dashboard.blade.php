<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">داشبورد مدیر آموزشی</li>
            </ol>
        </nav>
    </div>

    <div class="alert alert-light border shadow-sm mb-3">
        آمارهای آموزشی این صفحه بر اساس <strong>دانش‌آموزان غیرآزمایشی</strong> محاسبه شده‌اند.
        بخش تماس و جذب تلفنی روی کل داده‌های جذب تلفنی نمایش داده می‌شود.
    </div>

    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">تعداد کل دانش‌آموزان</div>
                    <h2 class="mb-0">{{ number_format($studentStats['total']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">مشاوران تحصیلی</div>
                    <h2 class="mb-0">{{ number_format($advisorStats['regular']) }}</h2>
                    <div class="small text-muted mt-1">
                        جذب تلفنی: {{ number_format($advisorStats['phoneAcquisition']) }}
                        | جذب یک‌هفته آزمایشی: {{ number_format($advisorStats['trialAcquisition']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">جلسات برگزارشده</div>
                    <h2 class="mb-0">{{ number_format($sessionStats['heldRegularCount']) }}</h2>
                    <div class="small text-muted mt-1">جلسات جبرانی برگزارشده: {{ number_format($sessionStats['heldMakeupCount']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger">
                <div class="card-body">
                    <div class="text-muted small">جلسات برگزارنشده بیشتر از ۷ روز</div>
                    <h2 class="mb-0 text-danger">{{ number_format($sessionStats['overdueCount']) }}</h2>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">میانگین ارسال گزارش در هفته</div>
                    <h2 class="mb-0">{{ $reportStats['sentAverageDays'] }} روز</h2>
                    <div class="small text-muted mt-1">{{ $reportStats['sentAveragePercent'] }}٪ از ۷ روز</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">میانگین عدم ارسال گزارش</div>
                    <h2 class="mb-0">{{ $reportStats['notSentAverageDays'] }} روز</h2>
                    <div class="small text-muted mt-1">{{ $reportStats['notSentAveragePercent'] }}٪ از ۷ روز</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-warning">
                <div class="card-body">
                    <div class="text-muted small">گزارشات بدون تعیین تکلیف</div>
                    <h2 class="mb-0 text-warning-emphasis">{{ number_format($reportStats['pendingReportsCount']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">میانگین مطالعه روزانه</div>
                    <h2 class="mb-0">{{ $studyStats['averagePerDayLabel'] }}</h2>
                    <div class="small text-muted mt-1">میانگین ۷ روز اخیر</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">تست درنظر گرفته‌شده</div>
                    <h2 class="mb-0">{{ number_format($testStats['planned']) }}</h2>
                    <div class="small text-muted mt-1">
                        انجام‌شده: {{ number_format($testStats['completed']) }}
                        | انجام‌نشده: {{ number_format($testStats['notCompleted']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">کل دقایق تماس</div>
                    <h2 class="mb-0">{{ number_format($phoneStats['totalTalkMinutes']) }} دقیقه</h2>
                    <div class="small text-muted mt-1">
                        پاسخ: {{ number_format($phoneStats['answeredCount']) }}
                        | عدم پاسخ: {{ number_format($phoneStats['unansweredCount']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 border-start border-4 border-secondary">
                <div class="card-body">
                    <div class="text-muted small">شماره‌های ۰ تماس</div>
                    <h2 class="mb-0">{{ number_format($phoneStats['notCalledCount']) }}</h2>
                    <div class="small text-muted mt-1">لیدهایی که هنوز هیچ تماس ثبت‌شده‌ای ندارند</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">طبقه‌بندی فعلی</div>
                    <h2 class="mb-0">{{ number_format($classificationStats['classifiedCount']) }}</h2>
                    <div class="small text-muted mt-1">
                        انجام نداده‌اند: {{ number_format($classificationStats['unclassifiedCount']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">میانگین سطح طبقه‌بندی</div>
                    <h2 class="mb-0">{{ $classificationStats['averageLevelLabel'] }}</h2>
                    <div class="small text-muted mt-1">
                        A: {{ number_format($classificationStats['studentLevelCounts']['A']) }}
                        | B: {{ number_format($classificationStats['studentLevelCounts']['B']) }}
                        | C: {{ number_format($classificationStats['studentLevelCounts']['C']) }}
                        | D: {{ number_format($classificationStats['studentLevelCounts']['D']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">آزمون‌های تستی</div>
                    <h2 class="mb-0">{{ number_format($examStats['typedTotal']) }}</h2>
                    <div class="small text-muted mt-1">اختصاص/برگزاری ثبت‌شده برای دانش‌آموزان</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">آزمون‌های تشریحی</div>
                    <h2 class="mb-0">{{ number_format($examStats['essayTotal']) }}</h2>
                    <div class="small text-muted mt-1">اختصاص/برگزاری ثبت‌شده برای دانش‌آموزان</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">آزمون‌های انجام‌شده</div>
                    <h2 class="mb-0">{{ number_format($examStats['doneTotal']) }}</h2>
                    <div class="small text-muted mt-1">انجام‌نشده: {{ number_format($examStats['notDoneTotal']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">تصحیح آزمون تشریحی</div>
                    <h2 class="mb-0">{{ number_format($examStats['essayGraded']) }}</h2>
                    <div class="small text-muted mt-1">ارسال‌شده و تصحیح‌نشده: {{ number_format($examStats['essaySubmittedUngraded']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header"><strong>جلسات برگزارنشده بیشتر از ۷ روز</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 360px; overflow: auto;">
                        <table class="table table-sm table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>دانش‌آموز</th>
                                    <th>مشاور</th>
                                    <th>تاریخ</th>
                                    <th>وضعیت</th>
                                    <th>نوع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sessionStats['overdueRows'] as $row)
                                    <tr>
                                        <td>{{ $row['student'] }}</td>
                                        <td>{{ $row['advisor'] }}</td>
                                        <td>{{ $row['date'] ? jalali($row['date'])->format('%Y/%m/%d') : '—' }}</td>
                                        <td>{{ $row['result'] }}</td>
                                        <td>
                                            @if ($row['is_makeup'])
                                                <span class="badge bg-warning text-dark">جبرانی</span>
                                            @else
                                                <span class="badge bg-light text-dark border">عادی</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">جلسه‌ی معوقِ برگزارنشده‌ای پیدا نشد.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header"><strong>گزارشات بدون تعیین تکلیف به تفکیک مشاور</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 360px; overflow: auto;">
                        <table class="table table-sm table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>مشاور</th>
                                    <th>تعداد گزارش</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reportStats['pendingByAdvisor'] as $row)
                                    <tr>
                                        <td>{{ $row['advisor'] }}</td>
                                        <td><span class="badge bg-warning text-dark">{{ number_format($row['count']) }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-3">هیچ گزارشِ درانتظاری وجود ندارد.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header"><strong>طبقه‌بندی فعلی و بعدی</strong></div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="text-muted small">پروژه فعلی/آخرین پروژه</span>
                        <div class="fw-bold">{{ $classificationStats['project']?->name ?? 'پروژه‌ای یافت نشد' }}</div>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted small">تاریخ بعدی طبقه‌بندی</span>
                        <div class="fw-bold">
                            @if ($classificationStats['nextProject'])
                                {{ jalali($classificationStats['nextProject']->start_at)->format('%d %B %Y') }}
                            @else
                                —
                            @endif
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-success fs-6">A: {{ number_format($classificationStats['studentLevelCounts']['A']) }}</span>
                        <span class="badge bg-info fs-6">B: {{ number_format($classificationStats['studentLevelCounts']['B']) }}</span>
                        <span class="badge bg-warning text-dark fs-6">C: {{ number_format($classificationStats['studentLevelCounts']['C']) }}</span>
                        <span class="badge bg-danger fs-6">D: {{ number_format($classificationStats['studentLevelCounts']['D']) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header"><strong>آمار کلی جذب تلفنی</strong></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-muted small">کل شماره‌ها</div>
                            <div class="fw-bold fs-4">{{ number_format($phoneStats['totalLeads']) }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">کل تماس‌ها</div>
                            <div class="fw-bold fs-4">{{ number_format($phoneStats['totalCalls']) }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">ثبت‌نام</div>
                            <div class="fw-bold text-success fs-5">{{ number_format($phoneStats['successful']['registered']) }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">پیگیری</div>
                            <div class="fw-bold text-primary fs-5">{{ number_format($phoneStats['successful']['follow_up']) }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">عدم پاسخ</div>
                            <div class="fw-bold text-warning-emphasis fs-5">{{ number_format($phoneStats['unsuccessful']['no_answer']) }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">شماره اشتباه</div>
                            <div class="fw-bold text-secondary fs-5">{{ number_format($phoneStats['unsuccessful']['wrong']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header"><strong>نقاط قوت میانگین دانش‌آموزان</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>درس</th>
                                    <th>میانگین</th>
                                    <th>A</th>
                                    <th>B</th>
                                    <th>C</th>
                                    <th>D</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($classificationStats['strongSubjects'] as $row)
                                    <tr>
                                        <td>{{ $row['subject'] }}</td>
                                        <td><span class="badge bg-success">{{ $row['avg_label'] }} ({{ $row['avg'] }})</span></td>
                                        <td>{{ number_format($row['A']) }}</td>
                                        <td>{{ number_format($row['B']) }}</td>
                                        <td>{{ number_format($row['C']) }}</td>
                                        <td>{{ number_format($row['D']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">داده‌ای برای نمایش وجود ندارد.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header"><strong>نقاط ضعف میانگین دانش‌آموزان</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>درس</th>
                                    <th>میانگین</th>
                                    <th>A</th>
                                    <th>B</th>
                                    <th>C</th>
                                    <th>D</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($classificationStats['weakSubjects'] as $row)
                                    <tr>
                                        <td>{{ $row['subject'] }}</td>
                                        <td><span class="badge bg-danger">{{ $row['avg_label'] }} ({{ $row['avg'] }})</span></td>
                                        <td>{{ number_format($row['A']) }}</td>
                                        <td>{{ number_format($row['B']) }}</td>
                                        <td>{{ number_format($row['C']) }}</td>
                                        <td>{{ number_format($row['D']) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">داده‌ای برای نمایش وجود ندارد.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($teamGoal || $consultantGoals->isNotEmpty())
        <div class="row g-3 mb-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header"><strong>هدف‌گذاری ثبت‌نام جذب تلفنی</strong></div>
                    <div class="card-body">
                        @if ($teamGoal)
                            @php $pct = $teamGoal->target_count > 0 ? min(100, round($registeredTotal / $teamGoal->target_count * 100)) : 0; @endphp
                            <div class="mb-2 d-flex justify-content-between">
                                <strong>هدف تیمی</strong>
                                <span class="text-muted small">مهلت: {{ jalali($teamGoal->goal_date)->format('%d %B %Y') }}</span>
                            </div>
                            <div class="progress mb-1" style="height: 22px">
                                <div class="progress-bar bg-success" style="width: {{ $pct }}%">{{ $pct }}٪</div>
                            </div>
                            <div class="small text-muted mb-3">{{ number_format($registeredTotal) }} از {{ number_format($teamGoal->target_count) }} ثبت‌نام</div>
                        @endif

                        <div class="row g-3">
                            @foreach ($consultantGoals as $goal)
                                @php $cpct = $goal->target_count > 0 ? min(100, round($goal->achieved / $goal->target_count * 100)) : 0; @endphp
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <div class="mb-1 d-flex justify-content-between">
                                            <span class="small fw-bold">{{ $goal->admin?->name ?? '—' }}</span>
                                            <span class="text-muted small">{{ jalali($goal->goal_date)->format('%d %B %Y') }}</span>
                                        </div>
                                        <div class="progress mb-1" style="height: 18px">
                                            <div class="progress-bar" style="width: {{ $cpct }}%">{{ $cpct }}٪</div>
                                        </div>
                                        <div class="small text-muted">{{ number_format($goal->achieved) }} از {{ number_format($goal->target_count) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <a href="{{ route('admin.educational-manager.phone-acquisition.goals') }}" class="btn btn-sm btn-outline-primary mt-3">
                            مدیریت هدف‌گذاری
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning-subtle">
                    <strong class="text-warning-emphasis">شماره‌های ۰ تماس ({{ number_format($phoneStats['notCalledCount']) }})</strong>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 340px; overflow: auto;">
                        <table class="table table-sm table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>نام</th>
                                    <th>موبایل</th>
                                    <th>مشاور جذب تلفنی</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($phoneStats['notCalled'] as $lead)
                                    <tr>
                                        <td>{{ $lead->full_name ?: '—' }}</td>
                                        <td dir="ltr">{{ $lead->mobile }}</td>
                                        <td>{{ $lead->activeAssignment?->consultant?->name ?? '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">همه‌ی شماره‌های اختصاص‌یافته حداقل یک تماس داشته‌اند.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
