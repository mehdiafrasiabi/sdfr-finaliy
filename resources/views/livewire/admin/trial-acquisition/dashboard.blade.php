<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">داشبورد جذب آزمایشی</li>
            </ol>
        </nav>
    </div>

    {{-- خلاصه --}}
    <div class="row g-3 mb-2">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <div class="text-muted small">کل دانش‌آموزان من</div>
                <h2 class="mb-0">{{ number_format($total) }}</h2>
            </div></div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm border-start border-success border-4"><div class="card-body">
                <div class="text-muted small">تماس گرفته‌شده</div>
                <h2 class="mb-0 text-success">{{ number_format($calledStudents) }}</h2>
            </div></div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm border-start border-danger border-4"><div class="card-body">
                <div class="text-muted small">تماس گرفته‌نشده</div>
                <h2 class="mb-0 text-danger">{{ number_format($notCalled) }}</h2>
            </div></div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <div class="text-muted small">ثبت‌نام قطعی</div>
                <h2 class="mb-0 text-primary">{{ number_format($confirmed) }}</h2>
            </div></div>
        </div>
    </div>

    <div class="row g-3">
        {{-- پیشرفت مراحل --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header"><strong>پیشرفت مراحل تماس</strong></div>
                <div class="card-body">
                    @foreach (['day1' => 'روز اول (ثبت‌نام)', 'day3' => 'روز سوم', 'day7' => 'روز هفتم'] as $stage => $label)
                        @php $c = $stageProgress[$stage] ?? 0; $pct = $total > 0 ? round($c / $total * 100) : 0; @endphp
                        <div class="mb-1 d-flex justify-content-between small">
                            <span>{{ $label }}</span>
                            <span class="text-muted">{{ $c }} از {{ $total }}</span>
                        </div>
                        <div class="progress mb-3" style="height:16px">
                            <div class="progress-bar" style="width: {{ $pct }}%">{{ $pct }}٪</div>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-between mt-3 pt-2 border-top">
                        <span class="fw-bold">میانگین احتمال ثبت‌نام</span>
                        <span class="badge bg-info">{{ $avgProbability }}٪</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- یادآورها --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning-subtle"><strong class="text-warning-emphasis">یادآورها</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height:300px;overflow:auto">
                        <table class="table table-sm table-bordered mb-0 align-middle">
                            <thead><tr><th>دانش‌آموز</th><th>موبایل</th><th>زمان یادآور</th></tr></thead>
                            <tbody>
                            @forelse ($dueReminders as $t)
                                @php $due = $t->acq_reminder_at && $t->acq_reminder_at->lte($now); @endphp
                                <tr class="{{ $due ? 'table-danger' : '' }}">
                                    <td>{{ $t->user?->name ?? '—' }}</td>
                                    <td dir="ltr">{{ $t->user?->mobile ?? '—' }}</td>
                                    <td>{{ jalali($t->acq_reminder_at)->format('%d %B %Y، %H:%M') }}@if($due) <span class="badge bg-danger">سررسید</span>@endif</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">یادآوری ثبت نشده است.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- تماس‌های اضطراری --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-danger-subtle"><strong class="text-danger">تماس‌های اضطراری</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0 align-middle">
                            <thead><tr><th>دانش‌آموز</th><th>موبایل</th><th>علت</th><th>تاریخ</th></tr></thead>
                            <tbody>
                            @forelse ($emergencyCalls as $call)
                                <tr>
                                    <td>{{ $call->trialWeek?->user?->name ?? '—' }}</td>
                                    <td dir="ltr">{{ $call->trialWeek?->user?->mobile ?? '—' }}</td>
                                    <td>{{ $call->emergency_reason ?? '—' }}</td>
                                    <td>{{ jalali($call->called_at)->format('%d %B %Y، %H:%M') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">تماس اضطراری‌ای ثبت نشده است.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
