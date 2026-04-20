<div class="container-fluid">

    {{-- ===== Header ===== --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h4 class="py-3 mb-0">
                <span class="text-muted fw-light">کارنامه هوشمند /</span>
                <span class="text-primary">{{ $studentName }}</span>
            </h4>
        </div>
    </div>

    {{-- ===== Date Range Filter ===== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0 d-flex align-items-center gap-2">
                <i class="material-symbols-outlined text-primary">calendar_month</i>
                انتخاب بازه زمانی کارنامه
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">تاریخ شروع</label>
                    <input type="text"
                           wire:model="filterStartDate"
                           class="form-control jalali-datepicker"
                           placeholder="مثال: ۱۴۰۴/۰۱/۰۱"
                           autocomplete="off">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">تاریخ پایان</label>
                    <input type="text"
                           wire:model="filterEndDate"
                           class="form-control jalali-datepicker"
                           placeholder="مثال: ۱۴۰۴/۰۱/۳۱"
                           autocomplete="off">
                </div>
                <div class="col-md-4">
                    <button wire:click="generateReport"
                            wire:loading.attr="disabled"
                            class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                        <span wire:loading wire:target="generateReport" class="spinner-border spinner-border-sm"></span>
                        <i wire:loading.remove wire:target="generateReport" class="material-symbols-outlined" style="font-size:20px;">assessment</i>
                        تولید کارنامه
                    </button>
                </div>
            </div>
            @if($reportError)
                <div class="alert alert-danger mt-3 mb-0 py-2">
                    <i class="material-symbols-outlined align-middle" style="font-size:18px;">error</i>
                    {{ $reportError }}
                </div>
            @endif
        </div>
    </div>

    @if($reportGenerated)

        {{-- ================================================================
             SECTION 1 — اطلاعات فردی دانش‌آموز
        ================================================================ --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex align-items-center gap-2" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;">
                <i class="material-symbols-outlined">person</i>
                <h6 class="mb-0">اطلاعات فردی دانش‌آموز</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4 col-6">
                        <div class="d-flex flex-column">
                            <small class="text-muted">نام و نام خانوادگی</small>
                            <strong>{{ $studentInfo['name'] }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="d-flex flex-column">
                            <small class="text-muted">نام پدر</small>
                            <strong>{{ $studentInfo['father_name'] }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="d-flex flex-column">
                            <small class="text-muted">کد ملی</small>
                            <strong>{{ $studentInfo['code_mell'] }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="d-flex flex-column">
                            <small class="text-muted">پایه تحصیلی</small>
                            <strong>{{ $studentInfo['grade'] }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="d-flex flex-column">
                            <small class="text-muted">رشته تحصیلی</small>
                            <strong>{{ $studentInfo['field'] }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="d-flex flex-column">
                            <small class="text-muted">تاریخ تولد</small>
                            <strong>{{ $studentInfo['birth_date'] }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="d-flex flex-column">
                            <small class="text-muted">شماره موبایل</small>
                            <strong>{{ $studentInfo['mobile'] }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="d-flex flex-column">
                            <small class="text-muted">استان / شهر</small>
                            <strong>{{ $studentInfo['state'] }} / {{ $studentInfo['city'] }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="d-flex flex-column">
                            <small class="text-muted">دوره / پکیج</small>
                            <strong>{{ $studentInfo['product'] }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="d-flex flex-column">
                            <small class="text-muted">مشاور</small>
                            <strong>{{ $studentInfo['advisor'] }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="d-flex flex-column">
                            <small class="text-muted">پشتیبان</small>
                            <strong>{{ $studentInfo['supporter'] }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="d-flex flex-column">
                            <small class="text-muted">بازه کارنامه</small>
                            <strong class="text-primary">{{ $filterStartDate }} تا {{ $filterEndDate }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================================================
             SECTION 2 — لیست جلسات مشاوره
        ================================================================ --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex align-items-center gap-2" style="background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;">
                <i class="material-symbols-outlined">groups</i>
                <h6 class="mb-0">جلسات مشاوره در این بازه
                    <span class="badge bg-white text-primary ms-2">{{ count($sessionsList) }}</span>
                </h6>
            </div>
            <div class="card-body p-0">
                @if(empty($sessionsList))
                    <div class="text-center py-4 text-muted">
                        <i class="material-symbols-outlined d-block mb-2" style="font-size:36px;">event_busy</i>
                        جلسه‌ای در این بازه ثبت نشده است.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>عنوان جلسه</th>
                                <th>تاریخ</th>
                                <th>روز</th>
                                <th>ساعت</th>
                                <th>محل برگزاری</th>
                                <th>وضعیت</th>
                                <th>نتیجه</th>
                                <th>مشاور</th>
                                <th>برنامه هفتگی</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sessionsList as $i => $session)
                                <tr>
                                    <td class="text-muted small">{{ $i + 1 }}</td>
                                    <td>
                                        <strong>{{ $session['title'] }}</strong>
                                        @if($session['description'])
                                            <br><small class="text-muted">{{ Str::limit($session['description'], 60) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $session['date'] }}</td>
                                    <td>{{ $session['day_name'] }}</td>
                                    <td>{{ $session['time'] }}</td>
                                    <td>{{ $session['location'] }}</td>
                                    <td>
                                        <span class="badge
                                            @if(str_contains($session['status'], 'برگزار شده')) bg-success
                                            @elseif(str_contains($session['status'], 'در حال')) bg-warning text-dark
                                            @else bg-secondary @endif">
                                            {{ $session['status'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge
                                            @if(str_contains($session['result'], 'برگزار شد')) bg-success
                                            @elseif(str_contains($session['result'], 'غیبت') || str_contains($session['result'], 'برگزار نشد')) bg-danger
                                            @else bg-secondary @endif">
                                            {{ $session['result'] }}
                                        </span>
                                    </td>
                                    <td>{{ $session['advisor'] }}</td>
                                    <td>
                                        @if($session['has_weekly_program'])
                                            <span class="badge bg-success">دارد</span>
                                        @else
                                            <span class="badge bg-secondary">ندارد</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- ================================================================
             SECTION 3 — آمار کلی مطالعه
        ================================================================ --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex align-items-center gap-2" style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;">
                <i class="material-symbols-outlined">bar_chart</i>
                <h6 class="mb-0">آمار کلی مطالعه</h6>
            </div>
            <div class="card-body">

                {{-- Row 1: Totals --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center py-4">
                                <div class="display-6 fw-bold text-primary mb-1">{{ number_format($studyStats['total_parts']) }}</div>
                                <div class="text-muted small">مجموع کل پارت‌ها</div>
                                <div class="text-muted" style="font-size:11px;">(برنامه‌ریزی‌شده)</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center py-4">
                                <div class="display-6 fw-bold text-warning mb-1">{{ number_format($studyStats['total_tests']) }}</div>
                                <div class="text-muted small">مجموع کل تست‌ها</div>
                                <div class="text-muted" style="font-size:11px;">(برنامه‌ریزی‌شده)</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center py-4">
                                <div class="display-6 fw-bold text-success mb-1 font-monospace">{{ $studyStats['planned_formatted'] }}</div>
                                <div class="text-muted small">مجموع کل ساعت مطالعه</div>
                                <div class="text-muted" style="font-size:11px;">(ساعت:دقیقه:ثانیه برنامه‌ریزی‌شده)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Row 2: Actuals + percentages --}}
                <h6 class="fw-semibold mb-3 text-muted">
                    <i class="material-symbols-outlined align-middle" style="font-size:18px;">check_circle</i>
                    میزان انجام‌شده
                </h6>
                <div class="row g-3">

                    {{-- Parts box --}}
                    <div class="col-md-4">
                        <div class="card border-primary border-2 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold">پارت‌ها</span>
                                    <span class="badge bg-primary fs-6">{{ $studyStats['parts_percent'] }}٪</span>
                                </div>
                                <div class="progress mb-2" style="height:10px;">
                                    <div class="progress-bar bg-primary"
                                         style="width:{{ $studyStats['parts_percent'] }}%"></div>
                                </div>
                                <div class="row text-center mt-2">
                                    <div class="col-6">
                                        <div class="fw-bold text-success">{{ number_format($studyStats['read_parts']) }}</div>
                                        <small class="text-muted">خوانده‌شده</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="fw-bold text-secondary">{{ number_format($studyStats['total_parts']) }}</div>
                                        <small class="text-muted">کل</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tests box --}}
                    <div class="col-md-4">
                        <div class="card border-warning border-2 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold">تست‌ها</span>
                                    <span class="badge bg-warning text-dark fs-6">{{ $studyStats['tests_percent'] }}٪</span>
                                </div>
                                <div class="progress mb-2" style="height:10px;">
                                    <div class="progress-bar bg-warning"
                                         style="width:{{ $studyStats['tests_percent'] }}%"></div>
                                </div>
                                <div class="row text-center mt-2">
                                    <div class="col-6">
                                        <div class="fw-bold text-success">{{ number_format($studyStats['done_tests']) }}</div>
                                        <small class="text-muted">زده‌شده</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="fw-bold text-secondary">{{ number_format($studyStats['total_tests']) }}</div>
                                        <small class="text-muted">کل</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Hours box --}}
                    <div class="col-md-4">
                        <div class="card border-success border-2 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold">ساعت مطالعه</span>
                                    <span class="badge bg-success fs-6">{{ $studyStats['hours_percent'] }}٪</span>
                                </div>
                                <div class="progress mb-2" style="height:10px;">
                                    <div class="progress-bar bg-success"
                                         style="width:{{ $studyStats['hours_percent'] }}%"></div>
                                </div>
                                <div class="row text-center mt-2">
                                    <div class="col-6">
                                        <div class="fw-bold text-success font-monospace small">{{ $studyStats['actual_formatted'] }}</div>
                                        <small class="text-muted">انجام‌شده</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="fw-bold text-secondary font-monospace small">{{ $studyStats['planned_formatted'] }}</div>
                                        <small class="text-muted">برنامه‌ریزی‌شده</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ================================================================
             SECTION 4 — گزارشات
        ================================================================ --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex align-items-center gap-2" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;">
                <i class="material-symbols-outlined">assignment</i>
                <h6 class="mb-0">گزارشات روزانه</h6>
            </div>
            <div class="card-body">

                {{-- Summary boxes --}}
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-2">
                        <div class="card text-center border-0 bg-primary bg-opacity-10">
                            <div class="card-body py-3">
                                <div class="fs-3 fw-bold text-primary">{{ $reportsData['total_sent'] }}</div>
                                <small class="text-muted">تعداد کل ارسالی</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="card text-center border-0 bg-danger bg-opacity-10">
                            <div class="card-body py-3">
                                <div class="fs-3 fw-bold text-danger">{{ $reportsData['total_not_sent'] }}</div>
                                <small class="text-muted">تعداد کل عدم ارسال</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="card text-center border-0 bg-success bg-opacity-10">
                            <div class="card-body py-3">
                                <div class="fs-3 fw-bold text-success">{{ $reportsData['total_approved'] }}</div>
                                <small class="text-muted">تایید شده</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="card text-center border-0 bg-danger bg-opacity-10">
                            <div class="card-body py-3">
                                <div class="fs-3 fw-bold text-danger">{{ $reportsData['total_rejected'] }}</div>
                                <small class="text-muted">رد شده</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="card text-center border-0 bg-secondary bg-opacity-10">
                            <div class="card-body py-3">
                                <div class="fs-3 fw-bold text-secondary">{{ $reportsData['total_pending'] }}</div>
                                <small class="text-muted">در انتظار</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="card text-center border-0 bg-info bg-opacity-10">
                            <div class="card-body py-3">
                                <div class="fs-3 fw-bold text-info">{{ $reportsData['total_compensatory'] }}</div>
                                <small class="text-muted">جبرانی</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Reports list --}}
                @if(empty($reportsData['list']))
                    <div class="text-center py-4 text-muted">
                        <i class="material-symbols-outlined d-block mb-2" style="font-size:36px;">inbox</i>
                        گزارشی در این بازه ثبت نشده است.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>تاریخ</th>
                                <th>روز</th>
                                <th>نوع</th>
                                <th>وضعیت</th>
                                <th>پارت خوانده‌شده</th>
                                <th>ساعت تلفنی</th>
                                <th>نظر مشاور</th>
                                <th>پاسخ دانش‌آموز</th>
                                <th>ثبت‌شده در</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reportsData['list'] as $i => $report)
                                <tr>
                                    <td class="text-muted small">{{ $i + 1 }}</td>
                                    <td>{{ $report['date'] }}</td>
                                    <td>{{ $report['day_name'] }}</td>
                                    <td>
                                        @if($report['is_compensatory'])
                                            <span class="badge bg-info">جبرانی</span>
                                        @else
                                            <span class="badge bg-secondary">عادی</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge
                                            @if($report['status'] === 'approved') bg-success
                                            @elseif($report['status'] === 'rejected') bg-danger
                                            @else bg-warning text-dark @endif">
                                            {{ $report['status_label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-success">{{ $report['parts_read'] }}</span>
                                        <span class="text-muted">/</span>
                                        <span class="text-muted">{{ $report['total_parts'] }}</span>
                                    </td>
                                    <td>
                                        @if($report['phone_hours'] > 0)
                                            <span class="badge bg-secondary">{{ $report['phone_hours'] }} ساعت</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($report['advisor_comment'])
                                            <span class="text-primary small"
                                                  title="{{ $report['advisor_comment'] }}"
                                                  data-bs-toggle="tooltip">
                                                {{ Str::limit($report['advisor_comment'], 30) }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($report['student_reply'])
                                            <span class="text-success small"
                                                  title="{{ $report['student_reply'] }}"
                                                  data-bs-toggle="tooltip">
                                                {{ Str::limit($report['student_reply'], 30) }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ $report['created_at'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- ================================================================
             SECTION 5 — ساعت مطالعه
        ================================================================ --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex align-items-center gap-2" style="background:linear-gradient(135deg,#ec4899,#db2777);color:#fff;">
                <i class="material-symbols-outlined">timer</i>
                <h6 class="mb-0">ساعت مطالعه</h6>
            </div>
            <div class="card-body">

                {{-- Summary row --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="card border-0 bg-light text-center">
                            <div class="card-body py-3">
                                <div class="fw-bold text-primary font-monospace fs-5">{{ $studyHoursData['normal_formatted'] }}</div>
                                <small class="text-muted">مطالعه عادی</small>
                                <br><small class="text-muted">({{ $studyHoursData['normal_count'] }} سشن)</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card border-0 bg-light text-center">
                            <div class="card-body py-3">
                                <div class="fw-bold text-purple font-monospace fs-5" style="color:#7c3aed;">{{ $studyHoursData['makeup_formatted'] }}</div>
                                <small class="text-muted">اضافه بر سازمان</small>
                                <br><small class="text-muted">({{ $studyHoursData['makeup_count'] }} سشن)</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card border-0 bg-success bg-opacity-10 text-center">
                            <div class="card-body py-3">
                                <div class="fw-bold text-success font-monospace fs-5">{{ $studyHoursData['total_formatted'] }}</div>
                                <small class="text-muted">مجموع کل مطالعه</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card border-0 bg-warning bg-opacity-10 text-center">
                            <div class="card-body py-3">
                                <div class="fw-bold fs-5">
                                    @if($studyHoursData['avg_feedback'] > 0)
                                        <span class="
                                            @if($studyHoursData['avg_feedback'] >= 9) text-success
                                            @elseif($studyHoursData['avg_feedback'] >= 7) text-primary
                                            @elseif($studyHoursData['avg_feedback'] >= 5) text-warning
                                            @else text-danger @endif">
                                            {{ $studyHoursData['avg_feedback'] }} / 10
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                                <small class="text-muted">میانگین بازخورد</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Average study minutes --}}
                <div class="alert alert-light border mb-4 d-flex align-items-center gap-3">
                    <i class="material-symbols-outlined text-primary" style="font-size:28px;">trending_up</i>
                    <div>
                        <span class="text-muted">میانگین مطالعه در هر سشن:</span>
                        <strong class="text-primary ms-2">{{ $studyHoursData['avg_study_minutes'] }} دقیقه</strong>
                    </div>
                </div>

                {{-- Normal sessions table --}}
                @if(!empty($studyHoursData['normal_list']))
                    <h6 class="fw-semibold mb-2 mt-3">
                        <i class="material-symbols-outlined align-middle text-primary" style="font-size:18px;">menu_book</i>
                        جلسات مطالعه عادی
                    </h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>تاریخ</th>
                                <th>درس / موضوع</th>
                                <th>مدت (ساعت:دقیقه:ثانیه)</th>
                                <th>بازخورد</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($studyHoursData['normal_list'] as $i => $s)
                                <tr>
                                    <td class="text-muted small">{{ $i + 1 }}</td>
                                    <td>{{ $s['date'] }}</td>
                                    <td>{{ $s['subject'] }}</td>
                                    <td class="font-monospace">{{ $s['duration_formatted'] }}</td>
                                    <td>
                                        @if($s['feedback_rating'] > 0)
                                            <span class="badge
                                                @if($s['feedback_rating'] >= 9) bg-success
                                                @elseif($s['feedback_rating'] >= 7) bg-primary
                                                @elseif($s['feedback_rating'] >= 5) bg-warning text-dark
                                                @else bg-danger @endif">
                                                {{ $s['feedback_label'] }} ({{ $s['feedback_rating'] }})
                                            </span>
                                        @else
                                            <span class="text-muted">ثبت نشده</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Makeup sessions table --}}
                @if(!empty($studyHoursData['makeup_list']))
                    <h6 class="fw-semibold mb-2">
                        <i class="material-symbols-outlined align-middle text-purple" style="font-size:18px;color:#7c3aed;">add_circle</i>
                        جلسات اضافه بر سازمان
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>تاریخ</th>
                                <th>موضوع</th>
                                <th>نوع</th>
                                <th>مدت (ساعت:دقیقه:ثانیه)</th>
                                <th>وضعیت</th>
                                <th>بازخورد</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($studyHoursData['makeup_list'] as $i => $s)
                                <tr>
                                    <td class="text-muted small">{{ $i + 1 }}</td>
                                    <td>{{ $s['date'] }}</td>
                                    <td>{{ $s['subject'] }}</td>
                                    <td>{{ $s['part_type'] }}</td>
                                    <td class="font-monospace">{{ $s['duration_formatted'] }}</td>
                                    <td>
                                        <span class="badge
                                            @if($s['status'] === 'تایید شده') bg-success
                                            @elseif($s['status'] === 'رد شده') bg-danger
                                            @else bg-warning text-dark @endif">
                                            {{ $s['status'] }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($s['feedback_rating'] > 0)
                                            <span class="badge
                                                @if($s['feedback_rating'] >= 9) bg-success
                                                @elseif($s['feedback_rating'] >= 7) bg-primary
                                                @elseif($s['feedback_rating'] >= 5) bg-warning text-dark
                                                @else bg-danger @endif">
                                                {{ $s['feedback_label'] }} ({{ $s['feedback_rating'] }})
                                            </span>
                                        @else
                                            <span class="text-muted">ثبت نشده</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if(empty($studyHoursData['normal_list']) && empty($studyHoursData['makeup_list']))
                    <div class="text-center py-4 text-muted">
                        <i class="material-symbols-outlined d-block mb-2" style="font-size:36px;">hourglass_empty</i>
                        جلسه مطالعه‌ای در این بازه ثبت نشده است.
                    </div>
                @endif

            </div>
        </div>

    @endif

    @push('script')
        <script>
            // Init Bootstrap tooltips
            document.addEventListener('livewire:navigated', function () {
                var tooltipEls = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipEls.forEach(function (el) { new bootstrap.Tooltip(el); });
            });
            document.addEventListener('DOMContentLoaded', function () {
                var tooltipEls = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipEls.forEach(function (el) { new bootstrap.Tooltip(el); });
            });
        </script>
    @endpush


</div>

