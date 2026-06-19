<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">داشبورد جذب تلفنی</li>
            </ol>
        </nav>
    </div>

    {{-- خلاصهٔ کلی --}}
    <div class="row g-3 mb-2">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">کل شماره‌ها</div>
                    <h2 class="mb-0">{{ number_format($totalLeads) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">کل تماس‌های گرفته‌شده</div>
                    <h2 class="mb-0">{{ number_format($totalCalls) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <div class="text-muted small">تماس‌های موفق</div>
                    <h2 class="mb-0 text-success">{{ number_format($successfulTotal) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm border-start border-danger border-4">
                <div class="card-body">
                    <div class="text-muted small">تماس‌های ناموفق</div>
                    <h2 class="mb-0 text-danger">{{ number_format($unsuccessfulTotal) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- موفق --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success-subtle"><strong class="text-success">تماس‌های موفق</strong></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>تعداد ثبت‌نام</span>
                        <span class="badge bg-success">{{ number_format($successful['registered']) }}</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>تعداد پیگیری</span>
                        <span class="badge bg-primary">{{ number_format($successful['follow_up']) }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span>تعداد عدم تمایل</span>
                        <span class="badge bg-secondary">{{ number_format($successful['no_interest']) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ناموفق --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-danger-subtle"><strong class="text-danger">تماس‌های ناموفق</strong></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>خاموش</span>
                        <span class="badge bg-dark">{{ number_format($unsuccessful['off']) }}</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>عدم پاسخ</span>
                        <span class="badge bg-warning text-dark">{{ number_format($unsuccessful['no_answer']) }}</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <span>رد تماس</span>
                        <span class="badge bg-danger">{{ number_format($unsuccessful['rejected']) }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span>شماره اشتباه</span>
                        <span class="badge bg-secondary">{{ number_format($unsuccessful['wrong']) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- هدف‌گذاری --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header"><strong>هدف‌گذاری ثبت‌نام</strong></div>
                <div class="card-body">
                    @if ($teamGoal)
                        @php $pct = $teamGoal->target_count > 0 ? min(100, round($registeredTotal / $teamGoal->target_count * 100)) : 0; @endphp
                        <div class="mb-2 d-flex justify-content-between">
                            <strong>هدف تیمی</strong>
                            <span class="text-muted small">مهلت: {{ jalali($teamGoal->goal_date)->format('%d %B %Y') }}</span>
                        </div>
                        <div class="progress mb-1" style="height:22px">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $pct }}%">{{ $pct }}٪</div>
                        </div>
                        <div class="small text-muted mb-3">{{ number_format($registeredTotal) }} از {{ number_format($teamGoal->target_count) }} ثبت‌نام</div>
                    @else
                        <p class="text-muted small">هدف تیمی تعیین نشده است.</p>
                    @endif

                    @foreach ($consultantGoals as $goal)
                        @php $cpct = $goal->target_count > 0 ? min(100, round($goal->achieved / $goal->target_count * 100)) : 0; @endphp
                        <div class="mb-1 d-flex justify-content-between">
                            <span class="small">{{ $goal->admin?->name ?? '—' }}</span>
                            <span class="text-muted small">مهلت: {{ jalali($goal->goal_date)->format('%d %B %Y') }}</span>
                        </div>
                        <div class="progress mb-1" style="height:18px">
                            <div class="progress-bar" role="progressbar" style="width: {{ $cpct }}%">{{ $cpct }}٪</div>
                        </div>
                        <div class="small text-muted mb-2">{{ number_format($goal->achieved) }} از {{ number_format($goal->target_count) }}</div>
                    @endforeach

                    <a href="{{ route('admin.educational-manager.phone-acquisition.goals') }}" class="btn btn-sm btn-outline-primary mt-2">
                        مدیریت هدف‌گذاری
                    </a>
                </div>
            </div>
        </div>

        {{-- تماس گرفته‌نشده --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning-subtle">
                    <strong class="text-warning-emphasis">تماس گرفته‌نشده ({{ number_format($notCalledCount) }})</strong>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height:320px;overflow:auto">
                        <table class="table table-sm table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>نام</th>
                                    <th>موبایل</th>
                                    <th>مشاور</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($notCalled as $lead)
                                <tr>
                                    <td>{{ $lead->full_name ?: '—' }}</td>
                                    <td dir="ltr">{{ $lead->mobile }}</td>
                                    <td>{{ $lead->activeAssignment?->consultant?->name ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">همهٔ شماره‌های اختصاص‌یافته تماس گرفته شده‌اند.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
