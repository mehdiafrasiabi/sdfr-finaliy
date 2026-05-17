<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">داشبورد</li>
            </ol>
        </nav>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">کل دانش‌آموزان من</div>
                    <h2 class="mb-0">{{ number_format($totalStudents) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">در انتظار تماس اولیه</div>
                    <h2 class="mb-0 text-warning">{{ number_format($awaitingPrimary) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">در انتظار تماس ثانویه</div>
                    <h2 class="mb-0 text-info">{{ number_format($awaitingSecondary) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">تماس‌های امروز</div>
                    <h2 class="mb-0 text-success">{{ number_format($callsToday) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">تماس‌های پاسخ‌داده‌شده</div>
                    <h2 class="mb-0">{{ number_format($callsAnsweredTotal) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">تماس‌های بدون پاسخ</div>
                    <h2 class="mb-0">{{ number_format($callsUnansweredTotal) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">نرخ پاسخ‌گویی</div>
                    <h2 class="mb-0">{{ $answeredRate }}٪</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">میانگین احتمال ثبت‌نام (تماس‌های ثانویه)</div>
                    <h2 class="mb-0">{{ $avgPrediction }}٪</h2>
                </div>
            </div>
        </div>
    </div>
</div>
