<div>
    @push('link')
        <style>
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .animate-fadeIn {
                animation: fadeIn 0.6s ease-out;
            }

            .stat-card {
                border-radius: 1.25rem;
                transition: all .25s ease;
            }

            .stat-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 0.75rem 2rem rgba(15, 23, 42, .15);
            }

            .total-card {
                border-radius: 1.5rem;
                overflow: hidden;
                position: relative;
                background: radial-gradient(circle at 0% 0%, #a855f7, #1f2937);
                color: #fff;
            }

            .total-card::before {
                content: '';
                position: absolute;
                inset: 0;
                background: radial-gradient(circle at 100% 0%, rgba(59, 130, 246, .4), transparent 55%);
                opacity: .9;
            }

            .total-card-inner {
                position: relative;
                z-index: 1;
            }

            .badge-soft {
                padding: .25rem .75rem;
                border-radius: 999px;
                font-size: .75rem;
                font-weight: 600;
            }

            .badge-soft-blue {
                background: rgba(59, 130, 246, .1);
                color: #1d4ed8;
            }

            .badge-soft-green {
                background: rgba(22, 163, 74, .1);
                color: #15803d;
            }

            .badge-soft-amber {
                background: rgba(245, 158, 11, .15);
                color: #b45309;
            }

            .badge-soft-violet {
                background: rgba(139, 92, 246, .1);
                color: #7c3aed;
            }

            .table-hover tbody tr:hover {
                background: linear-gradient(90deg, #eff6ff, #fdf2ff);
            }

            .pill-index {
                width: 2rem;
                height: 2rem;
                border-radius: 999px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: .7rem;
            }

            [dir="rtl"] .text-start {
                text-align: right !important;
            }

            [dir="rtl"] .text-end {
                text-align: left !important;
            }
        </style>
    @endpush

    <div dir="rtl" class="card">
        <div class="container-xxl">

            {{-- هدر و منو بالا --}}
            <div class="mb-4 animate-fadeIn">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <p class="small text-muted mb-1 d-flex align-items-center gap-2">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            گزارش مطالعه دانش‌آموز
                        </p>
                        <h1 class="fw-bold display-6 mb-0 text-dark">
                            زمان مطالعه
                            <span class="fw-bolder"
                                  style="background:linear-gradient(90deg,#2563eb,#7c3aed);-webkit-background-clip:text;color:transparent;">
                                {{ $studentName }}
                            </span>
                        </h1>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                         <span
                             class="d-inline-flex align-items-center px-3 py-2 bg-white border border-light rounded-3 shadow-sm small text-muted">
                            <svg width="16" height="16" class="ms-2 text-secondary" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ now()->format('Y/m/d H:i') }}
                        </span>

                        <a href="{{ route('admin.student.studySession.index') }}"
                           class="btn btn-outline-secondary d-inline-flex align-items-center">
                            <svg width="18" height="18" class="ms-2" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 19l-7-7m0 0 7-7m-7 7h18"/>
                            </svg>
                            بازگشت
                        </a>
                    </div>
                </div>
            </div>

            {{-- پیام‌های فلش --}}
            @if (session()->has('success'))
                <div class="alert alert-success border-end border-4 border-success shadow-sm d-flex align-items-center"
                     role="alert">
                    <svg width="18" height="18" class="ms-2 text-success" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="fw-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger border-end border-4 border-danger shadow-sm d-flex align-items-center"
                     role="alert">
                    <svg width="18" height="18" class="ms-2 text-danger" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="fw-medium">{{ session('error') }}</span>
                </div>
            @endif

            {{-- کارت‌های امروز / هفته / ماه --}}
            <div class="row g-3 mb-4">

                {{-- امروز --}}
                <div class="col-md-3">
                    <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                        <div class="card-body text-center py-3">
                            <div class="badge-soft badge-soft-blue mb-2 d-inline-block">پارت‌های تکمیل‌شده</div>
                            <div class="h3 fw-black text-primary mb-0">{{ $completedPartsCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                        <div class="card-body text-center py-3">
                            <div class="badge-soft badge-soft-violet mb-2 d-inline-block">جلسات جبرانی</div>
                            <div class="h3 fw-black mb-0" style="color:#7c3aed;">{{ $makeupCount }}</div>

                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                            <div class="card-body text-center py-3">
                                <div class="badge-soft badge-soft-amber mb-2 d-inline-block">میانگین بازخورد</div>
                                <div class="h3 fw-black text-warning mb-0">{{ $feedbackAvg }}/10</div>
                            </div>
                        </div>
                    </div>

                    {{-- این هفته --}}
                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                            <div class="card-body text-center py-3">
                                <div class="badge-soft badge-soft-green mb-2 d-inline-block">میانگین (دقیقه)</div>
                                <div class="h3 fw-black text-success mb-0">{{ $averageDuration ?? 0 }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- کارت‌های زمانی --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                            <div class="position-absolute top-0 start-0 rounded-circle"
                                 style="width:8rem;height:8rem;background:radial-gradient(circle,#3b82f6,#1d4ed8);opacity:.18;transform:translate(-30%,-30%);"></div>
                            <div class="card-body position-relative">
                                <div class="d-flex justify-content-between align-items-center mb-2"><span
                                        class="badge-soft badge-soft-blue">امروز</span></div>
                                <div class="h2 fw-black text-primary mb-0">{{ $studyTime['today'] }}</div>
                            </div>
                            <div class="progress rounded-0" style="height:4px;">
                                <div class="progress-bar bg-primary" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>

                    {{-- این ماه --}}
                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                            <div class="position-absolute top-0 start-0 rounded-circle"
                                 style="width:8rem;height:8rem;background:radial-gradient(circle,#22c55e,#15803d);opacity:.18;transform:translate(-30%,-30%);"></div>
                            <div class="card-body position-relative">
                                <div class="d-flex justify-content-between align-items-center mb-2"><span
                                        class="badge-soft badge-soft-green">این هفته</span></div>
                                <div class="h2 fw-black text-success mb-0">{{ $studyTime['week'] }}</div>
                            </div>
                            <div class="progress rounded-0" style="height:4px;">
                                <div class="progress-bar bg-success" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                            <div class="position-absolute top-0 start-0 rounded-circle"
                                 style="width:8rem;height:8rem;background:radial-gradient(circle,#f59e0b,#b45309);opacity:.18;transform:translate(-30%,-30%);"></div>

                            <div class="card-body position-relative">
                                <div class="d-flex justify-content-between align-items-center mb-2"><span
                                        class="badge-soft badge-soft-amber">این ماه</span></div>
                                <div class="h2 fw-black text-warning mb-0">{{ $studyTime['month'] }}</div>
                            </div>
                            <div class="progress rounded-0" style="height:4px;">
                                <div class="progress-bar bg-warning" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                            <div class="position-absolute top-0 start-0 rounded-circle"
                                 style="width:8rem;height:8rem;background:radial-gradient(circle,#8b5cf6,#6d28d9);opacity:.18;transform:translate(-30%,-30%);"></div>
                            <div class="card-body position-relative">
                                <div class="d-flex justify-content-between align-items-center mb-2"><span
                                        class="badge-soft badge-soft-violet">جبرانی</span></div>
                                <div class="h2 fw-black mb-0"
                                     style="color:#7c3aed;">{{ $studyTime['makeup'] ?? '00:00:00' }}</div>
                            </div>
                            <div class="progress rounded-0" style="height:4px;">
                                <div class="progress-bar" style="width: 100%;background:#8b5cf6;"></div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- کارت بزرگ کل زمان مطالعه --}}
                <div class="mb-4 total-card shadow-lg">
                    <div class="total-card-inner px-4 px-md-5 py-4 py-md-5">
                        <div class="row align-items-center g-4">
                            <div class="col-md-7 d-flex align-items-center gap-3 gap-md-4">
                                <div
                                    class="bg-white bg-opacity-10 rounded-4 p-3 p-md-4 d-flex align-items-center justify-content-center shadow">
                                    <svg width="48" height="48" class="text-white" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="small text-white-50 mb-2">کل زمان مطالعه (شامل جبرانی)</div>
                                    <div class="display-5 fw-black mb-1">{{ $studyTime['total'] }}</div>
                                    <div class="small text-white-50">{{ $regularCount }} جلسه عادی + {{ $makeupCount }}
                                        جلسه جبرانی
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div
                                            class="bg-white bg-opacity-10 rounded-3 p-3 text-center text-white shadow-sm">
                                            <div class="h3 fw-bold mb-1">{{ count($studySessions) }}</div>
                                            <div class="small text-white-50">کل جلسات</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div
                                            class="bg-white bg-opacity-10 rounded-3 p-3 text-center text-white shadow-sm">
                                            <div class="h3 fw-bold mb-1">{{ $averageDuration ?? 0 }}</div>
                                            <div class="small text-white-50">میانگین (دقیقه)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- فیلترها --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small mb-1">جستجو</label>
                                <input type="text" wire:model.live="search" placeholder="جستجو در یادداشت‌ها..."
                                       class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold small mb-1">از تاریخ</label>
                                <input type="text" wire:model.live="dateFrom" placeholder="1402/01/01"
                                       class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold small mb-1">تا تاریخ</label>
                                <input type="text" wire:model.live="dateTo" placeholder="1402/12/29"
                                       class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small mb-1">نوع جلسه</label>
                                <select wire:model.live="sessionType" class="form-select">
                                    <option value="all">همه جلسات</option>
                                    <option value="regular">فقط عادی</option>
                                    <option value="makeup">فقط جبرانی</option>
                                </select>
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button wire:click="resetFilters" type="button"
                                        class="btn w-100 btn-light border d-flex align-items-center justify-content-center gap-2 fw-semibold">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    پاک کردن
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- جدول جلسات مطالعه --}}
                {{-- جلسات جبرانی --}}
                @if($sessionType !== 'regular' && count($makeupSessions) > 0)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light border-0 px-4 py-3">
                            <div class="d-flex align-items-center gap-2">
                                <h2 class="h5 fw-bold mb-0">جلسات مطالعه جبرانی</h2>
                                <span class="badge bg-white border text-muted px-3 py-2">{{ count($makeupSessions) }} جلسه</span>
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-nowrap mb-0 text-start">
                                <thead class="table-light">
                                <tr class="small text-muted">
                                    <th class="px-3">#</th>
                                    <th class="px-3">مبحث</th>
                                    <th class="px-3">درس / فصل</th>
                                    <th class="px-3">مدت زمان</th>
                                    <th class="px-3">یادداشت</th>
                                    <th class="px-3">وضعیت</th>
                                    <th class="px-3">تاریخ ثبت</th>
                                    <th class="px-3">عملیات</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($makeupSessions as $idx => $mk)
                                    <tr>
                                        <td class="text-center">
                                            <span class="pill-index bg-gradient text-white shadow-sm fw-bold"
                                                  style="background:#8b5cf6;">{{ $idx + 1 }}</span>
                                        </td>
                                        <td class="fw-semibold">{{ $mk->ccTopic?->name ?? '-' }}</td>

                                        <td>
                                            <span class="small text-muted">
                                            {{ $mk->ccTopic?->chapter?->subject?->name ?? '' }}
                                                @if($mk->ccTopic?->chapter?->name)
                                                    / {{ $mk->ccTopic->chapter->name }}
                                                @endif
                                        </span>
                                        </td>

                                        <td>
                                       <span class="badge rounded-pill text-bg-primary px-3 py-2">
                                            {{ $this->formatDuration($mk->duration_seconds) }}
                                        </span>
                                        </td>
                                        <td class="small">{{ $mk->note ?? '-' }}</td>
                                        <td>
                                            @if($mk->status === 'pending')
                                                <span class="badge rounded-pill text-bg-warning px-3 py-2">در انتظار تایید</span>
                                            @elseif($mk->status === 'approved')
                                                <span
                                                    class="badge rounded-pill text-bg-success px-3 py-2">تایید شده</span>
                                            @else
                                                <span class="badge rounded-pill text-bg-danger px-3 py-2">رد شده</span>
                                            @endif
                                        </td>

                                        <td class="small text-muted">{{ $mk->created_at?->format('Y/m/d H:i') }}</td>
                                        <td>
                                            @if($mk->status === 'pending')
                                                <div class="d-flex gap-1">
                                                    <button wire:click="approveMakeup({{ $mk->id }})"
                                                            class="btn btn-sm btn-outline-success" title="تایید">
                                                        <svg width="16" height="16" fill="none" stroke="currentColor"
                                                             viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </button>
                                                    <button wire:click="rejectMakeup({{ $mk->id }})"
                                                            class="btn btn-sm btn-outline-danger" title="رد">
                                                        <svg width="16" height="16" fill="none" stroke="currentColor"
                                                             viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="small text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                {{-- جدول جلسات عادی --}}
                @if($sessionType !== 'makeup')
                    <div class="card shadow-sm">
                        <div
                            class="card-header bg-light border-0 px-4 py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                            <div>
                                <h2 class="h5 fw-bold mb-1">جزئیات جلسات مطالعه</h2>
                                <div class="small text-muted">تمام رکوردها با تاریخ، ساعت شروع و توضیحات کامل</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-white border text-muted d-inline-flex align-items-center px-3 py-2">
                                {{ count($studySessions) }} جلسه
                            </span>
                                <button wire:click="exportToExcel" type="button"
                                        class="btn btn-success d-inline-flex align-items-center gap-1"
                                        title="خروجی Excel">Excel
                                </button>
                                <button wire:click="exportToPdf" type="button"
                                        class="btn btn-danger d-inline-flex align-items-center gap-1" title="خروجی PDF">
                                    PDF
                                </button>
                            </div>
                        </div>
                        @if(count($studySessions) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle text-nowrap mb-0 text-start">
                                    <thead class="table-light">
                                    <tr class="small text-muted text-center text-md-start">
                                        <th class="px-3">#</th>
                                        <th class="px-3" wire:click="sortBy('started_at')" style="cursor:pointer;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>روز و تاریخ شروع</span>
                                                @if($sortBy === 'started_at')
                                                    <svg width="14" height="14"
                                                         class="{{ $sortDirection === 'asc' ? 'rotate-180' : '' }}"
                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </th>
                                        <th class="px-3">ساعت شروع</th>
                                        <th class="px-3">ساعت پایان</th>
                                        <th class="px-3" wire:click="sortBy('duration_seconds')"
                                            style="cursor:pointer;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span>مدت زمان</span>
                                                @if($sortBy === 'duration_seconds')
                                                    <svg width="14" height="14"
                                                         class="{{ $sortDirection === 'asc' ? 'rotate-180' : '' }}"
                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </th>
                                        <th class="px-3">زمان برنامه‌ریزی</th>
                                        <th class="px-3">عنوان / یادداشت</th>
                                        <th class="px-3">عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($studySessions as $index => $session)
                                        <tr>
                                            <td class="text-center">
                                                <span
                                                    class="pill-index bg-primary bg-gradient text-white shadow-sm fw-bold">{{ $index + 1 }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span
                                                        class="fw-semibold">{{ $session->started_at ? $session->started_at->translatedFormat('l') : '-' }}</span>
                                                    <span
                                                        class="text-muted small">{{ $session->started_at ? $session->started_at->translatedFormat('d F Y') : '-' }}</span>
                                                    <span class="text-muted"
                                                          style="font-size:.7rem;">جلسه #{{ $session->id }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="rounded-circle bg-success"
                                                          style="width:8px;height:8px;"></span>
                                                    <span
                                                        class="fw-semibold">{{ $session->started_at ? $session->started_at->format('H:i') : '-' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="rounded-circle bg-danger"
                                                          style="width:8px;height:8px;"></span>
                                                    <span
                                                        class="fw-semibold">{{ $session->ended_at ? $session->ended_at->format('H:i') : '-' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                            <span
                                                class="badge rounded-pill text-bg-primary d-inline-flex align-items-center gap-1 px-3 py-2">
                                                {{ $this->formatDuration($session->duration_seconds) }}
                                            </span>
                                            </td>
                                            <td>
                                            <span
                                                class="badge rounded-pill d-inline-flex align-items-center px-3 py-2 {{ $session->planned_seconds ? 'text-bg-warning' : 'bg-light text-muted border' }}">
                                                {{ $session->planned_seconds ? $this->formatDuration($session->planned_seconds) : 'ثبت نشده' }}
                                            </span>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 220px;">
                                                    @if($session->note)
                                                        <span class="small fw-semibold"
                                                              title="{{ $session->note }}">{{ $session->note }}</span>
                                                    @else
                                                        <span
                                                            class="small text-muted fst-italic">یادداشتی ثبت نشده</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-start">
                                                <button type="button" wire:click="deleteSession({{ $session->id }})"
                                                        wire:confirm="آیا از حذف این جلسه اطمینان دارید؟"
                                                        class="btn btn-sm btn-outline-danger d-inline-flex align-items-center"
                                                        title="حذف جلسه">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor"
                                                         viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="card-body text-center py-5">
                                <div
                                    class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-3"
                                    style="width:80px;height:80px;">
                                    <svg width="36" height="36" class="text-secondary" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="h6 fw-bold mb-1">هیچ جلسه‌ای یافت نشد</h3>
                                <p class="small text-muted mb-0">جلسه مطالعه‌ای برای این دانش‌آموز ثبت نشده است.</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>










