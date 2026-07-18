<div class="trial-monitor-page">
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.trial-acquisition.dashboard') }}">مشاوره جذب</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.trial-acquisition.monitor') }}">رصد هفته آزمایشی</a></li>
                <li class="breadcrumb-item active">امتحانات</li>
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
                        <button type="button" wire:click="selectTrial({{ $trial->id }})" class="list-group-item list-group-item-action {{ $active ? 'active' : '' }}">
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
                @endphp
                @if($monitorLocked)
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <div class="mb-3">
                                <span class="badge bg-secondary-subtle text-secondary border px-3 py-2">رصد قفل است</span>
                            </div>
                            <h4 class="mb-2">برای دیدن امتحانات، اول باید تماس را بگیری.</h4>
                            <p class="text-muted mb-0">تا وقتی تماس موفق ثبت نشده باشد، اطلاعات امتحانی این دانش‌آموز نمایش داده نمی‌شود.</p>
                        </div>
                    </div>
                @else
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
                                    <div class="text-muted small mt-1 d-flex flex-wrap gap-2">
                                        <span>پدر: <span dir="ltr">{{ $selectedTrial->father_mobile ?? '—' }}</span></span>
                                        <span>مادر: <span dir="ltr">{{ $selectedTrial->mother_mobile ?? '—' }}</span></span>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-2 trial-status-pills">
                                    @if($examProgramSummary)
                                        <span class="badge bg-warning text-dark px-3 py-2">برنامه امتحانی</span>
                                    @endif
                                    <span class="badge bg-{{ $selectedTrial->status_color }} px-3 py-2">{{ $selectedTrial->status_label }}</span>
                                    @if($selectedTrial->expires_at)
                                        <span class="badge bg-light text-dark border px-3 py-2">
                                            {{ $selectedTrial->isExpired() ? 'منقضی شده' : $selectedTrial->days_remaining . ' روز مانده تا پایان دسترسی' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($examProgramSummary)
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body d-flex flex-wrap gap-2 align-items-center">
                                <span class="badge bg-warning text-dark">برنامه امتحانی</span>
                                <span class="fw-semibold">{{ $examProgramSummary['title'] }}</span>
                                <span class="text-muted small">تاریخ‌ها: {{ $examProgramSummary['exam_range'] }}</span>
                                <span class="text-muted small">ثبت برنامه: {{ $examProgramSummary['program_built_at'] }}</span>
                                <span class="text-muted small">تعداد روزها: {{ $examProgramSummary['days_count'] }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="card mb-3 border rounded-4 shadow-sm trial-section-card">
                        <div class="card-header d-flex justify-content-between align-items-center rounded-top-4 trial-section-header">
                            <div>
                                <h5 class="mb-1">پیش‌جلسه‌های امتحانی</h5>
                                <small class="text-muted">فقط بخش امتحانات این دانش‌آموز</small>
                            </div>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.trial-acquisition.monitor', $selectedTrial->id) }}">
                                بازگشت به رصد
                            </a>
                        </div>
                        <div class="card-body">
                            @if($preSessions->count() > 0)
                                @foreach($preSessions as $preSession)
                                    <div class="border rounded-3 p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <h6 class="fw-bold text-primary mb-1 d-flex align-items-center gap-2 trial-mini-title">
                                                    <i class="material-symbols-outlined">quiz</i>
                                                    امتحانات
                                                    <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $preSession->exams->count() }}</span>
                                                </h6>
                                                <small class="text-muted">پیش‌جلسه: {{ $preSession->title }}</small>
                                            </div>
                                            <span class="badge rounded-pill px-3 py-2 {{ $preSession->status === 'completed' ? 'bg-success' : 'bg-warning text-white' }}">
                                                {{ $preSession->status_label }}
                                            </span>
                                        </div>

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
                                @endforeach
                            @else
                                <div class="text-center text-muted py-5">برای این دانش‌آموز هنوز پیش‌جلسه‌ی امتحانی ثبت نشده است.</div>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
