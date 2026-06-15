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
</div>
