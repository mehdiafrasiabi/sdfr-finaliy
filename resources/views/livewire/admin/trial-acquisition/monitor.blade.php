<div class="trial-monitor-page">
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.trial-acquisition.dashboard') }}">مشاوره جذب</a></li>
                <li class="breadcrumb-item active">رصد هفته آزمایشی</li>
            </ol>
        </nav>
    </div>

    <div class="row g-3 trial-monitor-shell">
        <div class="col-xl-3">
            <div class="card border-0 shadow-sm h-100 trial-students-card">
                <div class="card-header bg-white">
                    <div class="fw-bold">دانش‌آموزان من</div>
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control form-control-sm mt-2" placeholder="جستجو نام یا موبایل">
                </div>
                <div class="list-group list-group-flush trial-students-list">
                    @forelse($trials as $trial)
                        @php
                            $name = $trial->user?->personalInformation?->name ?? $trial->user?->name ?? '—';
                            $active = (int) $selectedTrial?->id === (int) $trial->id;
                        @endphp
                        <button type="button"
                                wire:click="selectTrial({{ $trial->id }})"
                                class="list-group-item list-group-item-action {{ $active ? 'active' : '' }}">
                            <div class="d-flex justify-content-between gap-2 trial-student-row">
                                <span class="fw-semibold">{{ $name }}</span>
                                <span class="badge bg-{{ $trial->status_color }}">{{ $trial->status_label }}</span>
                            </div>
                            <div class="small {{ $active ? 'text-white-50' : 'text-muted' }}" dir="ltr">{{ $trial->user?->mobile ?? '—' }}</div>
                        </button>
                    @empty
                        <div class="text-center text-muted py-4">دانش‌آموزی برای رصد وجود ندارد.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-9">
            @if($selectedTrial)
                @php
                    $studentName = $selectedTrial->user?->personalInformation?->name ?? $selectedTrial->user?->name ?? '—';
                    $programParts = $weeklyProgram?->parts?->count() ?? 0;
                    $programMinutes = $weeklyProgram?->parts?->sum('duration_minutes') ?? 0;
                @endphp

                <div class="card border-0 shadow-sm mb-3 trial-student-summary">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 trial-student-summary-inner">
                            <div>
                                <h4 class="mb-1">{{ $studentName }}</h4>
                                <div class="text-muted small">
                                    {{ $selectedTrial->grade_label }} / {{ $selectedTrial->field_label }}
                                    <span class="mx-2">•</span>
                                    <span dir="ltr">{{ $selectedTrial->user?->mobile ?? '—' }}</span>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 trial-status-pills">
                                <span class="badge bg-{{ $selectedTrial->status_color }} px-3 py-2">{{ $selectedTrial->status_label }}</span>
                                @if($selectedTrial->expires_at)
                                    <span class="badge bg-light text-dark border px-3 py-2">
                                        {{ $selectedTrial->isExpired() ? 'منقضی شده' : $selectedTrial->days_remaining . ' روز مانده' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3 trial-stats-row">
                    <div class="col-6 col-lg-3">
                        <div class="card border-0 shadow-sm h-100"><div class="card-body">
                            <div class="text-muted small">پارت برنامه</div>
                            <h3 class="mb-0">{{ number_format($programParts) }}</h3>
                        </div></div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card border-0 shadow-sm h-100"><div class="card-body">
                            <div class="text-muted small">زمان کل برنامه</div>
                            <h3 class="mb-0">{{ $this->formatMinutes($programMinutes) }}</h3>
                        </div></div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card border-0 shadow-sm h-100"><div class="card-body">
                            <div class="text-muted small">گزارش‌های بدون ارسال</div>
                            <h3 class="mb-0 text-danger">{{ count($missingReports) }}</h3>
                        </div></div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="card border-0 shadow-sm h-100"><div class="card-body">
                            <div class="text-muted small">گزارش انتخاب‌شده</div>
                            <h3 class="mb-0 text-info">{{ count($selectedReports) }}</h3>
                        </div></div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3 trial-section-card">
                    <div class="card-header bg-white trial-section-header">
                        <div>
                            <strong>خلاصه عملکرد تا الان</strong>
                            <div class="small text-muted">بر اساس برنامه فعال، گزارش‌های ثبت‌شده و زمان‌های مطالعه ثبت‌شده</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 trial-live-stats">
                            <div class="col-6 col-lg">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="text-muted small">ساعت مطالعه تا الان</div>
                                    <div class="h5 mb-0 text-primary">{{ $monitorSummary['study_until_now'] }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-lg">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="text-muted small">ساعت مطالعه امروز</div>
                                    <div class="h5 mb-0 text-info">{{ $monitorSummary['study_today'] }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-lg">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="text-muted small">گزارش ارسال تا الان</div>
                                    <div class="h5 mb-0 text-success">{{ number_format($monitorSummary['sent_reports_until_now']) }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-lg">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="text-muted small">عدم گزارش تا الان</div>
                                    <div class="h5 mb-0 text-danger">{{ number_format($monitorSummary['missing_reports_until_now']) }}</div>
                                </div>
                            </div>
                            <div class="col-12 col-lg">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="text-muted small">تمام بخش‌ها</div>
                                    <div class="h5 mb-0 text-dark">{{ number_format($monitorSummary['done_parts']) }} / {{ number_format($monitorSummary['total_parts']) }}</div>
                                    <div class="small text-muted mt-1">انجام شده / کل</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PRE-SESSIONS --}}
                @if($preSessions->count() > 0)
                    @foreach($preSessions as $preSession)
                        <div class="card mb-3 border rounded-4 shadow-sm trial-section-card">
                            <div class="card-header d-flex justify-content-between align-items-center rounded-top-4 trial-section-header">
                                <div>
                                    <h5 class="mb-1">پیش‌جلسه: {{ $preSession->title }}</h5>
                                    <small class="text-muted">اطلاعات ثبت شده توسط دانش‌آموز</small>
                                </div>
                                <span class="badge rounded-pill px-3 py-2 {{ $preSession->status === 'completed' ? 'bg-success' : 'bg-warning text-white' }}">
                                    {{ $preSession->status_label }}
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-lg-6">
                                        <div class="border rounded-3 p-3 h-100">
                                            <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2 trial-mini-title">
                                                <i class="material-symbols-outlined">quiz</i>
                                                امتحانات
                                                <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $preSession->exams->count() }}</span>
                                            </h6>
                                            @if($preSession->exams->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-sm align-middle mb-0">
                                                        <thead class="table-light"><tr><th>درس</th><th>پارت</th><th>زمان</th><th>تاریخ</th></tr></thead>
                                                        <tbody>
                                                        @foreach($preSession->exams as $exam)
                                                            <tr>
                                                                <td class="fw-semibold small">{{ $exam->subject }}</td>
                                                                <td class="small">{{ $exam->part_count }}</td>
                                                                <td class="small">{{ $exam->time_per_part }} دقیقه</td>
                                                                <td class="small">{{ jalali($exam->exam_date)->format('%d %B') }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <p class="text-muted small mb-0">هیچ امتحانی ثبت نشده</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="border rounded-3 p-3 h-100">
                                            <h6 class="fw-bold text-info mb-3 d-flex align-items-center gap-2 trial-mini-title">
                                                <i class="material-symbols-outlined">forum</i>
                                                پرسش و پاسخ کلاسی
                                                <span class="badge bg-info-subtle text-info rounded-pill">{{ $preSession->qas->count() }}</span>
                                            </h6>
                                            @if($preSession->qas->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-sm align-middle mb-0">
                                                        <thead class="table-light"><tr><th>درس</th><th>پارت</th><th>زمان</th><th>تاریخ</th></tr></thead>
                                                        <tbody>
                                                        @foreach($preSession->qas as $qa)
                                                            <tr>
                                                                <td class="fw-semibold small">{{ $qa->subject }}</td>
                                                                <td class="small">{{ $qa->part_count }}</td>
                                                                <td class="small">{{ $qa->time_per_part }} دقیقه</td>
                                                                <td class="small">{{ jalali($qa->qa_date)->format('%d %B') }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <p class="text-muted small mb-0">هیچ پرسش و پاسخی ثبت نشده</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="border rounded-3 p-3 h-100">
                                            <h6 class="fw-bold text-warning mb-3 d-flex align-items-center gap-2 trial-mini-title">
                                                <i class="material-symbols-outlined">assignment</i>
                                                تکالیف
                                                <span class="badge bg-warning-subtle text-warning rounded-pill">{{ $preSession->assignments->count() }}</span>
                                            </h6>
                                            @if($preSession->assignments->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-sm align-middle mb-0">
                                                        <thead class="table-light"><tr><th>درس</th><th>پارت</th><th>زمان</th><th>مهلت</th></tr></thead>
                                                        <tbody>
                                                        @foreach($preSession->assignments as $assignment)
                                                            <tr>
                                                                <td class="fw-semibold small">{{ $assignment->subject }}</td>
                                                                <td class="small">{{ $assignment->part_count }}</td>
                                                                <td class="small">{{ $assignment->time_per_part }} دقیقه</td>
                                                                <td class="small">{{ jalali($assignment->due_date)->format('%d %B') }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <p class="text-muted small mb-0">هیچ تکلیفی ثبت نشده</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="border rounded-3 p-3 h-100">
                                            <h6 class="fw-bold text-success mb-3 d-flex align-items-center gap-2 trial-mini-title">
                                                <i class="material-symbols-outlined">notes</i> متفرقه
                                            </h6>
                                            @if($preSession->miscellaneous)
                                                <div class="bg-body-tertiary rounded-3 p-3 border">
                                                    <p class="mb-0 small" style="white-space:pre-line;">{{ $preSession->miscellaneous->description }}</p>
                                                </div>
                                            @else
                                                <p class="text-muted small mb-0">متفرقه‌ای ثبت نشده</p>
                                            @endif
                                        </div>
                                    </div>

                                    @if($preSession->requestedParts->count() > 0)
                                        <div class="col-12">
                                            <div class="border rounded-3 p-3">
                                                <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 trial-mini-title" style="color:#ea580c;">
                                                    <i class="material-symbols-outlined">playlist_add</i>
                                                    پارت درخواستی
                                                    <span class="badge rounded-pill px-2" style="background:rgba(234,88,12,.12);color:#ea580c;">{{ $preSession->requestedParts->count() }}</span>
                                                </h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm align-middle mb-0">
                                                        <thead class="table-light"><tr><th>درس</th><th>پارت</th><th>زمان</th><th>توضیحات</th></tr></thead>
                                                        <tbody>
                                                        @foreach($preSession->requestedParts as $rp)
                                                            <tr>
                                                                <td class="fw-semibold small">{{ $rp->subject }}</td>
                                                                <td class="small">{{ $rp->part_count }}</td>
                                                                <td class="small">{{ $rp->time_per_part }} دقیقه</td>
                                                                <td class="text-muted small">{{ $rp->description ?? '—' }}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <div class="card border-0 shadow-sm mb-3 trial-section-card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center gap-2 trial-section-header">
                        <div>
                            <strong>برنامه هفته آزمایشی</strong>
                            @if($weeklyProgram)
                                <div class="small text-muted">شروع {{ jalali($weeklyProgram->start_date)->format('%Y/%m/%d') }} تا {{ jalali($weeklyProgram->end_date)->format('%Y/%m/%d') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if($weeklyProgram)
                            <div class="row g-3">
                                @foreach($weekDays as $day)
                                    <div class="col-lg-6">
                                        <div class="border rounded-3 h-100 trial-day-card">
                                            <div class="p-3 border-bottom d-flex justify-content-between trial-day-head">
                                                <div>
                                                    <strong>{{ $day['name'] }}</strong>
                                                    <span class="text-muted small">{{ $day['jalali_date'] }}</span>
                                                </div>
                                                <div class="d-flex gap-1 trial-day-badges">
                                                    @if($day['is_rest_day'])<span class="badge bg-success">استراحت</span>@endif
                                                    @if($day['is_exam_day'])<span class="badge bg-warning text-dark">آزمون جامع</span>@endif
                                                    <span class="badge bg-{{ $day['status_color'] }}">{{ $day['status_label'] }}</span>
                                                    <span class="badge bg-light text-dark border">{{ $day['total_time_label'] }}</span>
                                                </div>
                                            </div>
                                            <div class="p-3">
                                                @if($day['can_view_details'])
                                                    <div class="mb-2">
                                                        @if($day['report_id'])
                                                            <button type="button" wire:click="openDetailModal({{ $day['report_id'] }})" class="btn btn-sm btn-outline-primary">
                                                                مشاهده جزئیات
                                                            </button>
                                                        @else
                                                            <button type="button" wire:click="openDayDetailModal({{ $day['index'] }})" class="btn btn-sm btn-outline-danger">
                                                                مشاهده جزئیات
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endif
                                                @forelse($day['parts'] as $part)
                                                    <div class="d-flex justify-content-between gap-2 py-2 border-bottom trial-day-part">
                                                        <div>
                                                            <div class="fw-semibold">{{ $part['lesson_name'] }}</div>
                                                            <div class="small text-muted">
                                                                {{ $part['chapter_name'] ?? $part['topic_name'] ?? '—' }}
                                                                <span class="mx-1">•</span>{{ $part['part_type_label'] }}
                                                                <span class="mx-1">•</span>{{ $part['source_type_label'] }}
                                                            </div>
                                                            <span class="badge bg-{{ $part['status_color'] }} mt-2">{{ $part['status_label'] }}</span>
                                                        </div>
                                                        <div class="text-nowrap small">
                                                            {{ $part['duration_label'] }}
                                                            @if($part['test_count']) / {{ $part['test_count'] }} تست @endif
                                                            @if($part['has_study_session'])
                                                                <div class="text-success fw-semibold mt-1">{{ $part['study_duration_label'] }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="text-muted small">پارتی برای این روز تعریف نشده است.</div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">هنوز برنامه‌ای برای این دانش‌آموز ساخته نشده است.</div>
                        @endif
                    </div>
                </div>

                <div class="card border-0 shadow-sm trial-section-card">
                    <div class="card-header bg-white">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 trial-report-head">
                            <div>
                                <strong>گزارش‌های روزانه</strong>
                                <div class="small text-muted">
                                    {{ $reportDateDayName }} - {{ $reportDateJalali }}
                                    <span class="badge bg-info ms-2 trial-window-badge">بازه گزارش: ۰۰:۰۰ تا ۰۶:۰۰ صبح روز بعد</span>
                                    @if($isViewingPast)<span class="badge bg-warning text-dark">تاریخ گذشته</span>@endif
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 trial-report-controls">
                                <select wire:model.live="reportStatus" class="form-select form-select-sm w-auto trial-report-status">
                                    <option value="pending">در انتظار</option>
                                    <option value="approved">تایید شده</option>
                                    <option value="rejected">رد شده</option>
                                    <option value="all">همه</option>
                                </select>
                                <button type="button" wire:click="goToPrevDay" class="btn btn-sm btn-outline-secondary" {{ !$canGoBack ? 'disabled' : '' }}>روز قبل</button>
                                <button type="button" wire:click="goToNextDay" class="btn btn-sm btn-outline-secondary" {{ !$canGoForward ? 'disabled' : '' }}>روز بعد</button>
                                @if($isViewingPast)<button type="button" wire:click="resetToToday" class="btn btn-sm btn-primary">امروز</button>@endif
                            </div>
                        </div>
                    </div>

                    @if(count($missingReports) > 0)
                        <div class="alert alert-danger border-0 rounded-0 mb-0 trial-missing-alert">
                            <strong>بدون گزارش:</strong>
                            @foreach($missingReports as $missing)
                                <span class="badge bg-danger-subtle text-danger border mx-1">{{ $missing['name'] }} - <span dir="ltr">{{ $missing['mobile'] }}</span></span>
                            @endforeach
                        </div>
                    @endif

                    @if(count($selectedReports) > 0)
                        <div class="card-body border-bottom py-2">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <span class="text-primary fw-bold small">{{ count($selectedReports) }} گزارش انتخاب شده</span>
                                <div class="d-flex gap-2 trial-bulk-actions">
                                    <button type="button" wire:click="bulkAction('approved')" wire:confirm="آیا از تایید گروهی مطمئن هستید؟" class="btn btn-success btn-sm">تایید</button>
                                    <button type="button" wire:click="bulkAction('rejected')" wire:confirm="آیا از رد گروهی مطمئن هستید؟" class="btn btn-danger btn-sm">رد</button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card-body p-0">
                        @if($reports->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 trial-report-table">
                                    <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width:40px"><input type="checkbox" class="form-check-input" wire:model.live="selectAll"></th>
                                        <th>دانش‌آموز</th>
                                        <th class="text-center">ساعت ثبت</th>
                                        <th class="text-center">پارت</th>
                                        <th class="text-center">تست</th>
                                        <th class="text-center">امتیاز</th>
                                        <th class="text-center">وضعیت</th>
                                        <th class="text-center">عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reports as $report)
                                        @php $ratingVal = (float) $report->calculated_rating; @endphp
                                        <tr wire:key="trial-monitor-report-{{ $report->id }}">
                                            <td class="text-center" data-label="انتخاب"><input type="checkbox" class="form-check-input" value="{{ $report->id }}" wire:model.live="selectedReports"></td>
                                            <td class="fw-medium" data-label="دانش‌آموز">{{ $report->student->user->profile?->full_name ?? $report->student->user->personalInformation?->name ?? $report->student->user->name ?? '-' }}</td>
                                            <td class="text-center small text-muted" data-label="ساعت ثبت">{{ $report->created_at ? jdate($report->created_at)->format('H:i') : '-' }}</td>
                                            <td class="text-center" data-label="پارت"><span class="text-success fw-bold">{{ $report->reportParts->where('is_read', true)->count() }}</span>/<span>{{ $report->reportParts->count() }}</span></td>
                                            <td class="text-center" data-label="تست">{{ $report->total_tests }}</td>
                                            <td class="text-center" data-label="امتیاز">
                                                @if($ratingVal > 0)
                                                    <span class="badge bg-primary rounded-pill">{{ $ratingVal }} / 10</span>
                                                    <div class="small text-muted">{{ $this->getRatingLabel($ratingVal) }}</div>
                                                @else
                                                    <span class="text-muted small">ثبت نشده</span>
                                                @endif
                                            </td>
                                            <td class="text-center" data-label="وضعیت"><span class="badge bg-{{ $this->getStatusColor($report->detail->status ?? 'pending') }}">{{ match($report->detail->status ?? 'pending') {'approved' => 'تایید', 'rejected' => 'رد', default => 'در انتظار'} }}</span></td>
                                            <td class="text-center" data-label="عملیات">
                                                <div class="btn-group btn-group-sm trial-row-actions">
                                                    <button type="button" wire:click="setReportStatus({{ $report->id }}, 'approved')" class="btn btn-outline-success" title="تایید"><i class="fi fi-rr-check"></i></button>
                                                    <button type="button" wire:click="setReportStatus({{ $report->id }}, 'rejected')" class="btn btn-outline-danger" title="رد"><i class="fi fi-rr-cross"></i></button>
                                                    <button type="button" wire:click="openCommentModal({{ $report->id }})" class="btn btn-outline-secondary" title="پیام مشاور"><i class="fi fi-rr-comment"></i></button>
                                                    <button type="button" wire:click="openDetailModal({{ $report->id }})" class="btn btn-outline-primary" title="جزئیات"><i class="fi fi-rr-eye"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer bg-transparent py-3">{{ $reports->links() }}</div>
                        @else
                            <div class="text-center text-muted py-5">گزارشی برای این وضعیت و تاریخ وجود ندارد.</div>
                        @endif
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm"><div class="card-body text-center text-muted py-5">دانش‌آموزی برای رصد انتخاب نشده است.</div></div>
            @endif
        </div>
    </div>

    @if($commentModalOpen)
        <div class="modal fade show d-block trial-modal-layer" tabindex="-1" style="background:rgba(0,0,0,.5)" wire:click.self="closeCommentModal">
            <div class="modal-dialog modal-dialog-centered trial-modal-dialog">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title">نظر مشاور <small class="text-muted">{{ $commentStudentName }}</small></h5>
                        <button type="button" class="btn-close" wire:click="closeCommentModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">وضعیت گزارش</label>
                            <select wire:model="commentStatusInput" class="form-select" {{ $advisorCommentReadonly ? 'disabled' : '' }}>
                                <option value="pending">در انتظار</option>
                                <option value="approved">تایید</option>
                                <option value="rejected">رد</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">پیام مشاور</label>
                            <textarea wire:model="advisorCommentInput" rows="4" class="form-control" {{ $advisorCommentReadonly ? 'readonly' : '' }}></textarea>
                            @error('advisorCommentInput')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        @if($commentStudentReply)
                            <div class="alert alert-success mb-0"><strong>پاسخ دانش‌آموز:</strong> {{ $commentStudentReply }}</div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeCommentModal">بستن</button>
                        @unless($advisorCommentReadonly)
                            <button type="button" class="btn btn-primary" wire:click="saveAdvisorComment">ثبت پیام</button>
                        @endunless
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($detailModalOpen)
        <div class="modal fade show d-block trial-modal-layer" tabindex="-1" style="background:rgba(0,0,0,.5)" wire:click.self="closeDetailModal">
            <div class="modal-dialog modal-xl modal-dialog-scrollable trial-modal-dialog trial-detail-dialog">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title">جزئیات گزارش - {{ $selectedReportData['student_name'] ?? '' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeDetailModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-3"><div class="border rounded-3 p-3"><div class="small text-muted">تاریخ</div><strong>{{ $selectedReportData['day_name'] ?? '' }} {{ $selectedReportData['report_date'] ?? '' }}</strong></div></div>
                            <div class="col-md-3"><div class="border rounded-3 p-3"><div class="small text-muted">پارت خوانده</div><strong>{{ $selectedReportData['read_parts'] ?? 0 }} / {{ $selectedReportData['total_parts'] ?? 0 }}</strong></div></div>
                            <div class="col-md-3"><div class="border rounded-3 p-3"><div class="small text-muted">تست</div><strong>{{ $selectedReportData['done_tests'] ?? 0 }} / {{ $selectedReportData['total_tests'] ?? 0 }}</strong></div></div>
                            <div class="col-md-3"><div class="border rounded-3 p-3"><div class="small text-muted">امتیاز</div><strong>{{ $selectedReportData['rating'] ?? 0 }} / 10 - {{ $selectedReportData['rating_label'] ?? '' }}</strong></div></div>
                        </div>

                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-bordered align-middle trial-detail-table">
                                <thead class="table-light"><tr><th>درس</th><th>فصل/مبحث</th><th class="text-center">انجام پارت</th><th class="text-center">نوع</th><th class="text-center">زمان</th><th class="text-center">مطالعه</th><th class="text-center">تست</th><th class="text-center">امتیاز</th></tr></thead>
                                <tbody>
                                @forelse($reportPartsDetails as $part)
                                    <tr>
                                        <td class="fw-semibold" data-label="درس">{{ $part['lesson_name'] }}</td>
                                        <td data-label="فصل/مبحث">{{ $part['chapter_name'] ?? $part['topic_name'] ?? '—' }}</td>
                                        <td class="text-center" data-label="انجام پارت"><span class="badge bg-{{ $part['status_color'] }}">{{ $part['status_label'] }}</span></td>
                                        <td class="text-center" data-label="نوع">{{ $part['part_type_label'] }}<div class="small text-muted">{{ $part['lesson_type_label'] }}</div></td>
                                        <td class="text-center" data-label="زمان">{{ $part['duration_label'] }}</td>
                                        <td class="text-center" data-label="مطالعه">
                                            @if($part['has_study_session'])
                                                <span class="text-success fw-bold">{{ $part['study_duration_label'] }}</span>
                                                <div class="small text-muted">{{ $part['study_started_at'] }} - {{ $part['study_ended_at'] }}</div>
                                                <x-study-session-badges
                                                    :is-early-finish="(bool)($part['is_early_finish'] ?? false)"
                                                    :extra-seconds="(int)($part['extra_seconds'] ?? 0)"
                                                    :extra-target-seconds="(int)($part['extra_target_seconds'] ?? 0)"
                                                    :is-cheating="(bool)($part['is_cheating'] ?? false)"
                                                    :cheat-status="$part['cheat_status'] ?? null"
                                                    :cheat-minutes="(int)($part['cheat_minutes'] ?? 0)"
                                                    style="bootstrap" />
                                                @if(!empty($part['cheat_reason']))<div class="small text-muted">علت تقلب: {{ \Illuminate\Support\Str::limit($part['cheat_reason'], 40) }}</div>@endif
                                            @else
                                                <span class="text-danger small">ثبت نشده</span>
                                            @endif
                                        </td>
                                        <td class="text-center" data-label="تست">{{ $part['tests_done'] }} / {{ $part['test_count'] }}</td>
                                        <td class="text-center" data-label="امتیاز">{{ $part['session_rating'] ?: '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center text-muted py-4">پارتی برای این گزارش یافت نشد.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(!empty($selectedReportData['makeup_sessions']))
                            <div class="border rounded-3 p-3 mb-3">
                                <h6 class="fw-bold">مطالعه اضافه بر سازمان</h6>
                                @foreach($selectedReportData['makeup_sessions'] as $ms)
                                    <span class="badge bg-light text-dark border me-1">{{ $ms['topic_name'] }} - {{ $ms['duration_minutes'] }} دقیقه - {{ $ms['ended_at'] }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6"><div class="border rounded-3 p-3 h-100"><h6>توضیحات دانش‌آموز</h6><p class="mb-0 text-muted">{{ $selectedReportData['description'] ?: 'وجود ندارد' }}</p></div></div>
                            <div class="col-md-6"><div class="border rounded-3 p-3 h-100"><h6>علت عدم انجام پارت</h6><p class="mb-0 text-muted">{{ $selectedReportData['missed_parts_reason'] ?: 'وجود ندارد' }}</p></div></div>
                            @if($selectedReportData['advisor_comment'] ?? null)<div class="col-md-6"><div class="alert alert-primary mb-0"><strong>نظر مشاور:</strong> {{ $selectedReportData['advisor_comment'] }}</div></div>@endif
                            @if($selectedReportData['student_reply'] ?? null)<div class="col-md-6"><div class="alert alert-success mb-0"><strong>پاسخ دانش‌آموز:</strong> {{ $selectedReportData['student_reply'] }}</div></div>@endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span class="badge bg-{{ $this->getStatusColor($selectedReportData['status'] ?? 'pending') }} me-auto">وضعیت: {{ match($selectedReportData['status'] ?? 'pending') {'approved' => 'تایید شده', 'rejected' => 'رد شده', default => 'در انتظار بررسی'} }}</span>
                        <button type="button" class="btn btn-secondary" wire:click="closeDetailModal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($dayDetailModalOpen)
        <div class="modal fade show d-block trial-modal-layer" tabindex="-1" style="background:rgba(0,0,0,.5)" wire:click.self="closeDayDetailModal">
            <div class="modal-dialog modal-xl modal-dialog-scrollable trial-modal-dialog trial-detail-dialog">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title">جزئیات روز - {{ $selectedDayData['day_name'] ?? '' }} {{ $selectedDayData['date'] ?? '' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeDayDetailModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-3"><div class="border rounded-3 p-3"><div class="small text-muted">دانش‌آموز</div><strong>{{ $selectedDayData['student_name'] ?? '' }}</strong></div></div>
                            <div class="col-md-3"><div class="border rounded-3 p-3"><div class="small text-muted">وضعیت گزارش</div><span class="badge bg-{{ $selectedDayData['status_color'] ?? 'secondary' }}">{{ $selectedDayData['status_label'] ?? '' }}</span></div></div>
                            <div class="col-md-3"><div class="border rounded-3 p-3"><div class="small text-muted">تعداد پارت</div><strong>{{ $selectedDayData['total_parts'] ?? 0 }}</strong></div></div>
                            <div class="col-md-3"><div class="border rounded-3 p-3"><div class="small text-muted">زمان برنامه</div><strong>{{ $selectedDayData['total_time'] ?? '0 دقیقه' }}</strong></div></div>
                        </div>

                        @if(($selectedDayData['is_rest_day'] ?? false) || ($selectedDayData['is_exam_day'] ?? false))
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @if($selectedDayData['is_rest_day'] ?? false)<span class="badge bg-success">روز استراحت</span>@endif
                                @if($selectedDayData['is_exam_day'] ?? false)<span class="badge bg-warning text-dark">آزمون جامع</span>@endif
                            </div>
                        @endif

                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-bordered align-middle trial-detail-table">
                                <thead class="table-light"><tr><th>درس</th><th>فصل/مبحث</th><th class="text-center">انجام پارت</th><th class="text-center">نوع</th><th class="text-center">زمان برنامه</th><th class="text-center">مطالعه ثبت‌شده</th><th class="text-center">تست</th></tr></thead>
                                <tbody>
                                @forelse($dayPartsDetails as $part)
                                    <tr>
                                        <td class="fw-semibold" data-label="درس">{{ $part['lesson_name'] }}</td>
                                        <td data-label="فصل/مبحث">{{ $part['chapter_name'] ?? $part['topic_name'] ?? '—' }}</td>
                                        <td class="text-center" data-label="انجام پارت"><span class="badge bg-{{ $part['status_color'] }}">{{ $part['status_label'] }}</span></td>
                                        <td class="text-center" data-label="نوع">{{ $part['part_type_label'] }}<div class="small text-muted">{{ $part['lesson_type_label'] }}</div></td>
                                        <td class="text-center" data-label="زمان برنامه">{{ $part['duration_label'] }}</td>
                                        <td class="text-center" data-label="مطالعه ثبت‌شده">
                                            @if($part['has_study_session'])
                                                <span class="text-success fw-bold">{{ $part['study_duration_label'] }}</span>
                                                <div class="small text-muted">{{ $part['study_started_at'] }} - {{ $part['study_ended_at'] }}</div>
                                            @else
                                                <span class="text-danger small">ثبت نشده</span>
                                            @endif
                                        </td>
                                        <td class="text-center" data-label="تست">{{ $part['tests_done'] }} / {{ $part['test_count'] }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">پارتی برای این روز تعریف نشده است.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-warning mb-0">
                            برای این روز گزارش روزانه ارسال نشده است؛ جزئیات بالا از برنامه و مطالعه‌های ثبت‌شده استخراج شده‌اند.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeDayDetailModal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        .trial-monitor-page {
            overflow-x: hidden;
        }

        .trial-monitor-page .card {
            --bs-card-height: auto;
            height: auto;
        }

        .trial-monitor-page .card-body {
            flex: 0 0 auto;
        }

        .trial-students-list {
            max-height: 760px;
            overflow: auto;
        }

        .trial-report-table th,
        .trial-report-table td,
        .trial-detail-table th,
        .trial-detail-table td {
            vertical-align: middle;
        }

        .trial-row-actions {
            white-space: nowrap;
        }

        .trial-day-badges,
        .trial-status-pills,
        .trial-report-controls,
        .trial-bulk-actions {
            min-width: 0;
        }

        .trial-mini-title .material-symbols-outlined {
            font-size: 20px;
            line-height: 1;
        }

        @media (max-width: 991.98px) {
            .trial-students-list {
                max-height: 300px;
            }

            .trial-monitor-shell {
                --bs-gutter-y: 1rem;
            }

            .trial-student-summary h4 {
                font-size: 1.15rem;
            }

            .trial-stats-row .card-body {
                padding: .85rem;
            }

            .trial-stats-row h3 {
                font-size: 1.35rem;
            }

            .trial-section-header,
            .trial-report-head,
            .trial-day-head,
            .trial-day-part {
                align-items: stretch !important;
                flex-direction: column;
            }

            .trial-section-header {
                gap: .65rem;
            }

            .trial-section-header .badge,
            .trial-day-badges .badge,
            .trial-status-pills .badge {
                width: fit-content;
                max-width: 100%;
                white-space: normal;
                text-align: right;
            }

            .trial-day-badges,
            .trial-report-controls {
                width: 100%;
            }

            .trial-report-controls > * {
                flex: 1 1 calc(50% - .5rem);
                min-width: 0;
            }

            .trial-report-controls .trial-report-status,
            .trial-report-controls .btn-primary {
                flex-basis: 100%;
                width: 100% !important;
            }

            .trial-window-badge {
                display: inline-flex;
                margin: .35rem 0 0 !important;
                white-space: normal;
                line-height: 1.7;
                text-align: right;
            }

            .trial-missing-alert {
                line-height: 2.1;
            }

            .trial-missing-alert .badge {
                display: inline-flex;
                max-width: 100%;
                white-space: normal;
                text-align: right;
            }

            .trial-bulk-actions,
            .trial-bulk-actions .btn {
                width: 100%;
            }

            .trial-report-table,
            .trial-report-table thead,
            .trial-report-table tbody,
            .trial-report-table tr,
            .trial-report-table td,
            .trial-detail-table,
            .trial-detail-table thead,
            .trial-detail-table tbody,
            .trial-detail-table tr,
            .trial-detail-table td {
                display: block;
                width: 100%;
            }

            .trial-report-table thead,
            .trial-detail-table thead {
                display: none;
            }

            .trial-report-table tbody,
            .trial-detail-table tbody {
                padding: .75rem;
            }

            .trial-report-table tr,
            .trial-detail-table tr {
                border: 1px solid var(--bs-border-color);
                border-radius: .75rem;
                margin-bottom: .75rem;
                overflow: hidden;
                background: var(--bs-body-bg);
                box-shadow: 0 .25rem .75rem rgba(15, 23, 42, .05);
            }

            .trial-report-table tr:last-child,
            .trial-detail-table tr:last-child {
                margin-bottom: 0;
            }

            .trial-report-table td,
            .trial-detail-table td {
                border: 0;
                border-bottom: 1px solid var(--bs-border-color);
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: .75rem;
                padding: .72rem .85rem;
                text-align: left !important;
            }

            .trial-detail-table td {
                align-items: flex-start;
            }

            .trial-report-table td::before,
            .trial-detail-table td::before {
                content: attr(data-label);
                color: var(--bs-secondary-color);
                font-size: .78rem;
                font-weight: 700;
                flex: 0 0 auto;
                text-align: right;
            }

            .trial-report-table td:last-child,
            .trial-detail-table td:last-child {
                border-bottom: 0;
            }

            .trial-row-actions {
                display: grid !important;
                grid-template-columns: repeat(4, minmax(42px, 1fr));
                gap: .35rem;
                width: 100%;
            }

            .trial-row-actions .btn {
                border-radius: .5rem !important;
            }

            .trial-modal-layer {
                display: flex !important;
                align-items: flex-end;
                padding: 0;
            }

            .trial-modal-dialog {
                width: 100%;
                max-width: 100%;
                margin: 0;
            }

            .trial-modal-dialog .modal-content {
                border-radius: 1rem 1rem 0 0;
                max-height: 88svh;
            }

            .trial-modal-dialog .modal-header,
            .trial-modal-dialog .modal-footer {
                padding: .85rem 1rem;
            }

            .trial-modal-dialog .modal-body {
                padding: 1rem;
                overflow-y: auto;
            }
        }

        @media (max-width: 575.98px) {
            .trial-monitor-page .app-page-head {
                margin-bottom: .75rem;
            }

            .trial-monitor-page .breadcrumb {
                row-gap: .25rem;
                font-size: .8rem;
            }

            .trial-students-card .card-header,
            .trial-section-card .card-header,
            .trial-section-card .card-body,
            .trial-student-summary .card-body {
                padding: .85rem;
            }

            .trial-student-row {
                align-items: flex-start;
                flex-direction: column;
            }

            .trial-student-row .badge {
                white-space: normal;
                text-align: right;
            }

            .trial-status-pills {
                width: 100%;
            }

            .trial-status-pills .badge {
                flex: 1 1 100%;
            }

            .trial-stats-row {
                --bs-gutter-x: .65rem;
                --bs-gutter-y: .65rem;
            }

            .trial-stats-row .text-muted.small {
                min-height: 2.35em;
                line-height: 1.55;
            }

            .trial-mini-title {
                flex-wrap: wrap;
                line-height: 1.8;
            }

            .trial-day-card .p-3 {
                padding: .85rem !important;
            }

            .trial-day-part {
                gap: .35rem !important;
            }

            .trial-day-part .text-nowrap {
                white-space: normal !important;
                color: var(--bs-primary);
                font-weight: 700;
            }

            .trial-report-table tbody,
            .trial-detail-table tbody {
                padding: .65rem;
            }

            .trial-report-table td,
            .trial-detail-table td {
                flex-direction: column;
                align-items: stretch;
                text-align: right !important;
            }

            .trial-report-table td::before,
            .trial-detail-table td::before {
                margin-bottom: .15rem;
            }

            .trial-row-actions {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .trial-monitor-page .table-responsive {
                border-radius: .75rem;
                overflow-x: visible;
            }

            .trial-monitor-page .table-responsive table:not(.trial-report-table):not(.trial-detail-table) {
                width: 100%;
                min-width: 0;
                table-layout: fixed;
                font-size: .78rem;
            }

            .trial-monitor-page .table-responsive table:not(.trial-report-table):not(.trial-detail-table) th,
            .trial-monitor-page .table-responsive table:not(.trial-report-table):not(.trial-detail-table) td {
                overflow-wrap: anywhere;
                padding: .45rem .35rem;
                white-space: normal;
            }

            .trial-monitor-page .modal-title {
                font-size: 1rem;
                line-height: 1.7;
            }
        }
    </style>
</div>
