<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">داشبورد مدرسه: {{ $school?->name ?? '—' }}</h4>
        <span class="text-muted small">کد مدرسه: {{ $school?->code ?? '—' }}</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">دانش‌آموزان</div>
                <h3 class="mb-0 mt-1">{{ $stats['students'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">گزارش‌های ثبت‌شده</div>
                <h3 class="mb-0 mt-1">{{ $stats['reports'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">نمرات ثبت‌شده</div>
                <h3 class="mb-0 mt-1">{{ $stats['grades'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">تماس‌ها با اولیا</div>
                <h3 class="mb-0 mt-1">{{ $stats['contacts'] }}</h3>
            </div></div>
        </div>
    </div>

    <h5 class="mb-3">روند رشد و پسرفت در آزمون‌ها</h5>
    <div class="row g-3 mb-4">
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

    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.student.index') }}" class="btn btn-primary btn-sm">دانش‌آموزان مدرسه</a>
        <a href="{{ route('admin.school-manager.academic-status') }}" class="btn btn-outline-secondary btn-sm">وضعیت تحصیلی</a>
        <a href="{{ route('admin.school-manager.advising') }}" class="btn btn-outline-secondary btn-sm">آمار جلسات مشاوره</a>
        <a href="{{ route('admin.school-manager.grades') }}" class="btn btn-outline-secondary btn-sm">ثبت نمرات</a>
    </div>
</div>
