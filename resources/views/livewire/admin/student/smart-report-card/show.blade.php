<div>
    @push('link')
        <style>
            .src-month-card {
                border-radius: 1rem;
                border: 1px solid var(--bs-border-color);
                background: var(--bs-card-bg, #fff);
                transition: transform .15s ease, box-shadow .15s ease;
                position: relative;
                overflow: hidden;
            }
            .src-month-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 .5rem 1.2rem rgba(0,0,0,.08);
            }
            .src-month-card.is-active {
                border-color: #16a34a;
                box-shadow: 0 0 0 1px #16a34a inset;
            }
            .src-month-card.is-active::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(22,163,74,.06), transparent 55%);
                pointer-events: none;
            }
            .src-pill {
                display: inline-flex;
                align-items: center;
                gap: .35rem;
                padding: .25rem .7rem;
                border-radius: 999px;
                font-size: .75rem;
                font-weight: 600;
            }
            .src-pill-active { background: rgba(22,163,74,.12); color:#15803d; }
            .src-pill-inactive { background: rgba(100,116,139,.12); color:#475569; }
            [data-bs-theme="dark"] .src-month-card { background: rgba(255,255,255,.03); border-color: rgba(255,255,255,.08); }
            [data-bs-theme="dark"] .src-pill-active { background: rgba(22,163,74,.18); color:#86efac; }
            [data-bs-theme="dark"] .src-pill-inactive { background: rgba(148,163,184,.16); color:#cbd5e1; }
            .src-year-btn { min-width: 4.5rem; }
        </style>
    @endpush

    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard.index') }}">
                        <i class="fi fi-rr-home"></i>
                        صفحه اصلی
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.student.smartReportCard.index') }}">کارنامه هوشمند</a>
                </li>
                <li aria-current="page" class="breadcrumb-item active">
                    {{ $studentName }}
                </li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">کارنامه هوشمند <span class="text-primary">{{ $studentName }}</span></h4>
                    <p class="text-muted small mb-0">
                        ماه‌های مورد نظر را برای نمایش به دانش‌آموز در این سال فعال کنید. دیتای کارنامه از تاریخ شروع تا پایان همان ماه شمسی خوانده می‌شود.
                    </p>
                </div>
                <a href="{{ route('admin.student.smartReportCard.index') }}" class="btn btn-outline-secondary">
                    <i class="fi fi-rr-arrow-right ms-1"></i> بازگشت
                </a>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                <span class="fw-semibold ms-2">سال:</span>
                @for($y = $minYear; $y <= $maxYear; $y++)
                    <button type="button"
                            wire:click="changeYear({{ $y }})"
                            class="btn btn-sm src-year-btn {{ $selectedYear === $y ? 'btn-primary' : 'btn-outline-primary' }}">
                        {{ $y }}
                    </button>
                @endfor
            </div>

            <div class="row g-3">
                @foreach($months as $month)
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                        <div class="src-month-card p-3 h-100 {{ $month['is_active'] ? 'is-active' : '' }}">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div>
                                    <div class="fw-bold fs-5">{{ $month['name'] }}</div>
                                    <div class="text-muted small">{{ $month['days'] }} روزه</div>
                                </div>
                                <span class="src-pill {{ $month['is_active'] ? 'src-pill-active' : 'src-pill-inactive' }}">
                                    <span class="rounded-circle" style="width:.45rem;height:.45rem;background:currentColor;display:inline-block;"></span>
                                    {{ $month['is_active'] ? 'فعال' : 'غیرفعال' }}
                                </span>
                            </div>

                            <div class="small text-muted mb-3">
                                <div><i class="fi fi-rr-calendar ms-1"></i> از {{ $month['jalali_start'] }}</div>
                                <div><i class="fi fi-rr-calendar-check ms-1"></i> تا {{ $month['jalali_end'] }}</div>
                            </div>

                            @if($month['activated_at'])
                                <div class="small text-muted mb-2">
                                    آخرین تغییر: {{ jdate($month['activated_at'])->format('Y/m/d H:i') }}
                                </div>
                            @endif

                            <button type="button"
                                    wire:click="toggleMonth({{ $month['number'] }})"
                                    wire:loading.attr="disabled"
                                    wire:target="toggleMonth({{ $month['number'] }})"
                                    class="btn btn-sm w-100 {{ $month['is_active'] ? 'btn-outline-danger' : 'btn-success' }}">
                                <span wire:loading.remove wire:target="toggleMonth({{ $month['number'] }})">
                                    {{ $month['is_active'] ? 'غیرفعال‌سازی' : 'فعال‌سازی کارنامه' }}
                                </span>
                                <span wire:loading wire:target="toggleMonth({{ $month['number'] }})">
                                    در حال انجام...
                                </span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
