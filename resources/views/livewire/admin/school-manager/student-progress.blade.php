<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">پیشرفت دانش‌آموز: {{ $student->user?->name ?? '—' }}</h4>
        <a href="{{ route('admin.school-manager.academic-status') }}" class="btn btn-sm btn-outline-secondary">بازگشت</a>
    </div>

    <div class="card mb-4">
        <div class="card-body d-flex flex-wrap gap-4">
            <div>
                <div class="text-muted small">طبقه‌بندی فعلی</div>
                <h3 class="mb-0 mt-1">{{ $student->star ?? '—' }}</h3>
            </div>
            <div>
                <div class="text-muted small">پایه</div>
                <h5 class="mb-0 mt-1">{{ $student->grade ?? '—' }}</h5>
            </div>
            <div>
                <div class="text-muted small">رشته</div>
                <h5 class="mb-0 mt-1">{{ $student->field ?: '—' }}</h5>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            @include('livewire.admin.school-manager.partials.trend', [
                'title' => 'نمرات ماهانه‌ی مدرسه (از ۱۰۰)',
                'trend' => $schoolTrend,
                'delta' => $schoolDelta,
            ])
        </div>
        <div class="col-lg-6">
            @include('livewire.admin.school-manager.partials.trend', [
                'title' => 'آزمون‌های تایپی سیستم (درصد)',
                'trend' => $typedTrend,
                'delta' => $typedDelta,
            ])
        </div>
    </div>

    {{-- پیشرفت/پسرفت بر اساس طبقه‌بندی دروس (M5) --}}
    <div class="card mt-4">
        <div class="card-header"><strong>روند طبقه‌بندی دروس (A بهترین — D ضعیف‌ترین)</strong></div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                <tr>
                    <th>درس</th>
                    <th class="text-center">طبقه‌بندی قبلی</th>
                    <th class="text-center">طبقه‌بندی فعلی</th>
                    <th class="text-center">روند</th>
                </tr>
                </thead>
                <tbody>
                @forelse($subjectTrends as $row)
                    <tr>
                        <td>{{ $row['subject'] }}</td>
                        <td class="text-center">{{ \App\Support\ClassificationProgress::ratingLabel($row['previous']) }}</td>
                        <td class="text-center"><strong>{{ \App\Support\ClassificationProgress::ratingLabel($row['latest']) }}</strong></td>
                        <td class="text-center">
                            @if($row['delta'] === null)
                                <span class="text-muted">—</span>
                            @elseif($row['delta'] > 0)
                                <span class="badge bg-success">پیشرفت ▲ {{ $row['delta'] }}</span>
                            @elseif($row['delta'] < 0)
                                <span class="badge bg-danger">پسرفت ▼ {{ abs($row['delta']) }}</span>
                            @else
                                <span class="badge bg-secondary">بدون تغییر</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">داده‌ی طبقه‌بندی‌ای برای این دانش‌آموز ثبت نشده است.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
