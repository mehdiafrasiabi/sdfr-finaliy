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

            .badge-soft-orange {
                background: rgba(249, 115, 22, .1);
                color: #c2410c;
            }

            .badge-soft-purple {
                background: rgba(168, 85, 247, .1);
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

            .modal-overlay {
                backdrop-filter: blur(8px);
                background: rgba(0, 0, 0, 0.5);
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
                            {{ jdate(now())->format('Y/m/d H:i') }}
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

            {{-- کارت‌های آمار کلی --}}
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                        <div class="card-body text-center py-3">
                            <div class="badge-soft badge-soft-blue mb-2 d-inline-block">پارت‌های برنامه</div>
                            <div class="h3 fw-black text-primary mb-0">{{ $programPartsCount }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                        <div class="card-body text-center py-3">
                            <div class="badge-soft badge-soft-violet mb-2 d-inline-block">جلسات جبرانی</div>
                            <div class="h3 fw-black mb-0" style="color:#7c3aed;">{{ $makeupCount }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                        <div class="card-body text-center py-3">
                            <div class="badge-soft badge-soft-amber mb-2 d-inline-block">میانگین بازخورد</div>
                            <div class="h3 fw-black text-warning mb-0">{{ $feedbackAvg }}/10</div>
                        </div>
                    </div>
                </div>

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
                <div class="col-md-2">
                    <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                        <div class="position-absolute top-0 start-0 rounded-circle"
                             style="width:8rem;height:8rem;background:radial-gradient(circle,#3b82f6,#1d4ed8);opacity:.18;transform:translate(-30%,-30%);"></div>
                        <div class="card-body position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge-soft badge-soft-blue">امروز</span>
                            </div>
                            <div class="h2 fw-black text-primary mb-0">{{ $studyTime['today'] }}</div>
                        </div>
                        <div class="progress rounded-0" style="height:4px;">
                            <div class="progress-bar bg-primary" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                        <div class="position-absolute top-0 start-0 rounded-circle"
                             style="width:8rem;height:8rem;background:radial-gradient(circle,#22c55e,#15803d);opacity:.18;transform:translate(-30%,-30%);"></div>
                        <div class="card-body position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge-soft badge-soft-green">این هفته</span>
                            </div>
                            <div class="h2 fw-black text-success mb-0">{{ $studyTime['week'] }}</div>
                        </div>
                        <div class="progress rounded-0" style="height:4px;">
                            <div class="progress-bar bg-success" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                        <div class="position-absolute top-0 start-0 rounded-circle"
                             style="width:8rem;height:8rem;background:radial-gradient(circle,#f59e0b,#b45309);opacity:.18;transform:translate(-30%,-30%);"></div>
                        <div class="card-body position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge-soft badge-soft-amber">این ماه</span>
                            </div>
                            <div class="h2 fw-black text-warning mb-0">{{ $studyTime['month'] }}</div>
                        </div>
                        <div class="progress rounded-0" style="height:4px;">
                            <div class="progress-bar bg-warning" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                        <div class="position-absolute top-0 start-0 rounded-circle"
                             style="width:8rem;height:8rem;background:radial-gradient(circle,#8b5cf6,#6d28d9);opacity:.18;transform:translate(-30%,-30%);"></div>
                        <div class="card-body position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge-soft badge-soft-violet">جبرانی</span>
                            </div>
                            <div class="h2 fw-black mb-0"
                                 style="color:#7c3aed;">{{ $studyTime['makeup'] ?? '00:00:00' }}</div>
                        </div>
                        <div class="progress rounded-0" style="height:4px;">
                            <div class="progress-bar" style="width: 100%;background:#8b5cf6;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                        <div class="position-absolute top-0 start-0 rounded-circle"
                             style="width:8rem;height:8rem;background:radial-gradient(circle,#06b6d4,#0891b2);opacity:.18;transform:translate(-30%,-30%);"></div>
                        <div class="card-body position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge-soft badge-soft-blue">برنامه</span>
                            </div>
                            <div class="h2 fw-black text-info mb-0">{{ $studyTime['program'] ?? '00:00:00' }}</div>
                        </div>
                        <div class="progress rounded-0" style="height:4px;">
                            <div class="progress-bar bg-info" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card stat-card shadow-sm border-0 position-relative overflow-hidden">
                        <div class="position-absolute top-0 start-0 rounded-circle"
                             style="width:8rem;height:8rem;background:radial-gradient(circle,#ef4444,#dc2626);opacity:.18;transform:translate(-30%,-30%);"></div>
                        <div class="card-body position-relative">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge-soft"
                                      style="background: rgba(239, 68, 68, .1);color: #dc2626;">کل</span>
                            </div>
                            <div class="h2 fw-black text-danger mb-0">{{ $studyTime['total'] }}</div>
                        </div>
                        <div class="progress rounded-0" style="height:4px;">
                            <div class="progress-bar bg-danger" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- فیلترها --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold small mb-1">جستجو</label>
                            <input type="text" wire:model.live="search" placeholder="جستجو..."
                                   class="form-control form-control-sm">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold small mb-1">از تاریخ</label>
                            <input type="text" wire:model.live="dateFrom" placeholder="1402/01/01"
                                   class="form-control form-control-sm">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold small mb-1">تا تاریخ</label>
                            <input type="text" wire:model.live="dateTo" placeholder="1402/12/29"
                                   class="form-control form-control-sm">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold small mb-1">نوع جلسه</label>
                            <select wire:model.live="sessionType" class="form-select form-select-sm">
                                <option value="all">همه جلسات</option>
                                <option value="program">پارت‌های برنامه</option>
                                <option value="makeup">جبرانی</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold small mb-1">بازخورد</label>
                            <select wire:model.live="feedbackFilter" class="form-select form-select-sm">
                                <option value="all">همه</option>
                                <option value="has_feedback">دارای بازخورد</option>
                                <option value="no_feedback">بدون بازخورد</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold small mb-1">نوع پارت</label>
                            <select wire:model.live="partTypeFilter" class="form-select form-select-sm">
                                <option value="all">همه</option>
                                <option value="test">تستی</option>
                                <option value="descriptive">تشریحی</option>
                                <option value="video">ویدیویی</option>
                            </select>
                        </div>

                        <div class="col-md-10">
                            <label class="form-label fw-semibold small mb-1">جلسه مشاوره</label>
                            <select wire:model.live="advisingSessionFilter" class="form-select form-select-sm">
                                <option value="">همه جلسات مشاوره</option>
                                @foreach($advisingSessions as $session)
                                    <option value="{{ $session->id }}">
                                        {{ $session->title }}
                                        - {{ $session->activation_date ? jdate($session->activation_date)->format('Y/m/d') : '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button wire:click="resetFilters" type="button"
                                    class="btn w-100 btn-light border d-flex align-items-center justify-content-center gap-2 fw-semibold btn-sm">
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

            {{-- جدول جلسات جبرانی --}}
            @if($sessionType !== 'program' && count($makeupSessions) > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light border-0 px-4 py-3">
                        <div class="d-flex align-items-center gap-2">
                            <h2 class="h5 fw-bold mb-0">جلسات مطالعه جبرانی</h2>
                            <span
                                class="badge bg-white border text-muted px-3 py-2">{{ count($makeupSessions) }} جلسه</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle text-nowrap mb-0 text-start">
                            <thead class="table-light">
                            <tr class="small text-muted">
                                <th class="px-3">#</th>
                                <th class="px-3">مبحث</th>
                                <th class="px-3">درس / فصل</th>
                                <th class="px-3">نوع</th>
                                <th class="px-3">زمان شروع</th>
                                <th class="px-3">زمان پایان</th>
                                <th class="px-3">مدت زمان</th>
                                <th class="px-3">وضعیت</th>
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
                                        <span class="badge rounded-pill px-3 py-2
                                            {{ $mk->part_type === 'test' ? 'text-bg-primary' : '' }}
                                            {{ $mk->part_type === 'descriptive' ? 'text-bg-purple' : '' }}
                                            {{ $mk->part_type === 'video' ? 'text-bg-warning' : '' }}">
                                            {{ $mk->part_type_label }}
                                        </span>
                                    </td>

                                    <td class="small">{{ $mk->started_at ? jdate($mk->started_at)->format('Y/m/d H:i') : '-' }}</td>
                                    <td class="small">{{ $mk->ended_at ? jdate($mk->ended_at)->format('Y/m/d H:i') : '-' }}</td>

                                    <td>
                                        <span class="badge rounded-pill text-bg-info px-3 py-2">
                                            {{ $this->formatDuration($mk->duration_seconds) }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($mk->status === 'pending')
                                            <span
                                                class="badge rounded-pill text-bg-warning px-3 py-2">در انتظار تایید</span>
                                        @elseif($mk->status === 'approved')
                                            <span class="badge rounded-pill text-bg-success px-3 py-2">تایید شده</span>
                                        @else
                                            <span class="badge rounded-pill text-bg-danger px-3 py-2">رد شده</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex gap-1">
                                            <button wire:click="showDetail({{ $mk->id }}, 'makeup')"
                                                    class="btn btn-sm btn-outline-primary" title="جزئیات">
                                                <svg width="16" height="16" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>

                                            @if($mk->status === 'pending')
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
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- جدول جلسات برنامه --}}
            @if($sessionType !== 'makeup')
                <div class="card shadow-sm">
                    <div
                        class="card-header bg-light border-0 px-4 py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div>
                            <h2 class="h5 fw-bold mb-1">پارت‌های مطالعه برنامه</h2>
                            <div class="small text-muted">جلسات مطالعه بر اساس برنامه هفتگی</div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-white border text-muted d-inline-flex align-items-center px-3 py-2">
                                {{ count($studySessions) }} جلسه
                            </span>
                            <button wire:click="exportToExcel" type="button"
                                    class="btn btn-success btn-sm d-inline-flex align-items-center gap-1">
                                Excel
                            </button>
                            <button wire:click="exportToPdf" type="button"
                                    class="btn btn-danger btn-sm d-inline-flex align-items-center gap-1">
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
                                    <th class="px-3">درس / مبحث</th>
                                    <th class="px-3">نوع</th>
                                    <th class="px-3">جلسه مشاوره</th>
                                    <th class="px-3">تاریخ</th>
                                    <th class="px-3">زمان شروع</th>
                                    <th class="px-3">زمان پایان</th>
                                    <th class="px-3">مدت زمان</th>
                                    <th class="px-3">بازخورد</th>
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
                                                    class="fw-semibold">{{ $session->programPart?->lesson_name ?? '-' }}</span>
                                                @if($session->programPart?->ccSubject || $session->programPart?->ccTopic)
                                                    <span class="small text-muted">
                                                        {{ $session->programPart?->ccSubject?->name ?? '' }}
                                                        @if($session->programPart?->ccChapter)
                                                            / {{ $session->programPart->ccChapter->name }}
                                                        @endif
                                                        @if($session->programPart?->ccTopic)
                                                            / {{ $session->programPart->ccTopic->name }}
                                                        @endif
                                                    </span>
                                                @elseif($session->programPart?->description)
                                                    <span
                                                        class="small text-muted">{{ Str::limit($session->programPart->description, 60) }}</span>
                                                @endif
                                                @if($session->weeklyProgram && $session->programPart)
                                                    @php
                                                        $dayIndex = $session->programPart->day_of_week;
                                                        $programStart = \Carbon\Carbon::parse($session->weeklyProgram->start_date);
                                                        $partDate = $programStart->copy()->addDays($dayIndex);
                                                        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
                                                        $partDayName = $dayNames[jdate($partDate)->getDayOfWeek()] ?? '-';
                                                    @endphp
                                                    <span class="small text-primary">
                                                        روز {{ $dayIndex + 1 }} برنامه ({{ $partDayName }} {{ jdate($partDate)->format('m/d') }})
                                                    </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            <span class="badge rounded-pill px-2 py-1
                                                {{ $session->programPart?->part_type === 'test' ? 'text-bg-primary' : '' }}
                                                {{ $session->programPart?->part_type === 'descriptive' ? 'text-bg-purple' : '' }}
                                                {{ $session->programPart?->part_type === 'video' ? 'text-bg-warning' : '' }}">
                                                {{ $session->programPart?->part_type_label ?? '-' }}
                                            </span>
                                        </td>

                                        <td class="small text-muted">
                                            {{ $session->weeklyProgram?->advisingSession?->title ?? '-' }}
                                        </td>

                                        <td class="small">
                                            {{ $session->started_at ? jdate($session->started_at)->format('Y/m/d') : '-' }}

                                        </td>

                                        <td class="small">
                                            {{ $session->started_at ? $session->started_at->format('H:i') : '-' }}
                                        </td>

                                        <td class="small">
                                            {{ $session->ended_at ? $session->ended_at->format('H:i') : '-' }}
                                        </td>

                                        <td>
                                            <span class="badge rounded-pill text-bg-info px-3 py-2">
                                                {{ $this->formatDuration($session->duration_seconds) }}
                                            </span>
                                        </td>

                                        <td>
                                            @if($session->feedback)
                                                <span class="badge rounded-pill text-bg-success px-2 py-1">
                                                    ⭐ {{ $session->feedback->rating }}/10
                                                </span>
                                            @else
                                                <span class="badge rounded-pill text-bg-secondary px-2 py-1">
                                                    ندارد
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="d-flex gap-1">
                                                <button wire:click="showDetail({{ $session->id }}, 'program')"
                                                        class="btn btn-sm btn-outline-primary" title="جزئیات">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor"
                                                         viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>

                                                <button type="button" wire:click="deleteSession({{ $session->id }})"
                                                        wire:confirm="آیا از حذف این جلسه اطمینان دارید؟"
                                                        class="btn btn-sm btn-outline-danger" title="حذف">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor"
                                                         viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
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
                            <p class="small text-muted mb-0">جلسه مطالعه‌ای برای این فیلتر ثبت نشده است.</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- مودال جزئیات --}}
    @if($showDetailModal && $selectedSession)
        <div class="modal fade show d-block modal-overlay" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">
                            @if($selectedSessionType === 'makeup')
                                جزئیات جلسه جبرانی
                            @else
                                جزئیات جلسه مطالعه
                            @endif
                        </h5>
                        <button type="button" wire:click="closeDetailModal" class="btn-close"></button>
                    </div>

                    <div class="modal-body">
                        @if($selectedSessionType === 'makeup')
                            {{-- جزئیات جبرانی --}}
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">مبحث</label>
                                    <div class="fw-semibold">{{ $selectedSession->ccTopic?->name ?? '-' }}</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">درس</label>
                                    <div
                                        class="fw-semibold">{{ $selectedSession->ccTopic?->chapter?->subject?->name ?? '-' }}</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">فصل</label>
                                    <div
                                        class="fw-semibold">{{ $selectedSession->ccTopic?->chapter?->name ?? '-' }}</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">پایه</label>
                                    <div
                                        class="fw-semibold">{{ $selectedSession->ccTopic?->chapter?->subject?->grade?->name ?? '-' }}</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">نوع پارت</label>
                                    <div>
                                        <span class="badge rounded-pill px-3 py-2
                                            {{ $selectedSession->part_type === 'test' ? 'text-bg-primary' : '' }}
                                            {{ $selectedSession->part_type === 'descriptive' ? 'text-bg-purple' : '' }}
                                            {{ $selectedSession->part_type === 'video' ? 'text-bg-warning' : '' }}">
                                            {{ $selectedSession->part_type_label }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">زمان شروع</label>
                                    <div
                                        class="fw-semibold">{{ $selectedSession->started_at ? jdate($selectedSession->started_at)->format('Y/m/d H:i') : '-' }}</div>

                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">زمان پایان</label>
                                    <div
                                        class="fw-semibold">{{ $selectedSession->ended_at ? jdate($selectedSession->ended_at)->format('Y/m/d H:i') : '-' }}</div>

                                </div>

                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">مدت زمان</label>
                                    <div>
                                        <span class="badge rounded-pill text-bg-info px-3 py-2">
                                            {{ $this->formatDuration($selectedSession->duration_seconds) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">وضعیت</label>
                                    <div>
                                        @if($selectedSession->status === 'pending')
                                            <span
                                                class="badge rounded-pill text-bg-warning px-3 py-2">در انتظار تایید</span>
                                        @elseif($selectedSession->status === 'approved')
                                            <span class="badge rounded-pill text-bg-success px-3 py-2">تایید شده</span>
                                        @else
                                            <span class="badge rounded-pill text-bg-danger px-3 py-2">رد شده</span>
                                        @endif
                                    </div>
                                </div>

                                @if($selectedSession->note)
                                    <div class="col-12">
                                        <label class="small text-muted mb-1">یادداشت</label>
                                        <div class="p-3 bg-light rounded">{{ $selectedSession->note }}</div>
                                    </div>
                                @endif
                            </div>

                        @else
                            {{-- جزئیات پارت برنامه --}}
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">درس</label>
                                    <div
                                        class="fw-semibold">{{ $selectedSession->programPart?->lesson_name ?? '-' }}</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">جلسه مشاوره</label>
                                    <div
                                        class="fw-semibold">{{ $selectedSession->weeklyProgram?->advisingSession?->title ?? '-' }}</div>
                                </div>
                                @if($selectedSession->programPart?->ccSubject)
                                    <div class="col-md-4">
                                        <label class="small text-muted mb-1">مبحث درسی</label>
                                        <div class="fw-semibold">{{ $selectedSession->programPart->ccSubject->name }}</div>
                                    </div>
                                @endif

                                @if($selectedSession->programPart?->ccChapter)
                                    <div class="col-md-4">
                                        <label class="small text-muted mb-1">فصل</label>
                                        <div class="fw-semibold">{{ $selectedSession->programPart->ccChapter->name }}</div>
                                    </div>
                                @endif

                                @if($selectedSession->programPart?->ccTopic)
                                    <div class="col-md-4">
                                        <label class="small text-muted mb-1">سرفصل</label>
                                        <div class="fw-semibold">{{ $selectedSession->programPart->ccTopic->name }}</div>
                                    </div>
                                @endif

                                @if($selectedSession->weeklyProgram && $selectedSession->programPart)
                                    <div class="col-md-4">
                                        <label class="small text-muted mb-1">روز برنامه</label>
                                        @php
                                            $dayIdx = $selectedSession->programPart->day_of_week;
                                            $progStart = \Carbon\Carbon::parse($selectedSession->weeklyProgram->start_date);
                                            $partDt = $progStart->copy()->addDays($dayIdx);
                                            $dNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
                                            $pDayName = $dNames[jdate($partDt)->getDayOfWeek()] ?? '-';
                                        @endphp
                                        <div class="fw-semibold">
                                            <span class="badge rounded-pill text-bg-primary px-3 py-2">
                                                روز {{ $dayIdx + 1 }} ({{ $pDayName }} {{ jdate($partDt)->format('Y/m/d') }})
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">نوع پارت</label>
                                    <div>
                                        <span class="badge rounded-pill px-3 py-2
                                            {{ $selectedSession->programPart?->part_type === 'test' ? 'text-bg-primary' : '' }}
                                            {{ $selectedSession->programPart?->part_type === 'descriptive' ? 'text-bg-purple' : '' }}
                                            {{ $selectedSession->programPart?->part_type === 'video' ? 'text-bg-warning' : '' }}">
                                            {{ $selectedSession->programPart?->part_type_label ?? '-' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">نوع درس</label>
                                    <div>
                                        <span class="badge rounded-pill text-bg-secondary px-3 py-2">
                                            {{ $selectedSession->programPart?->lesson_type_label ?? '-' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">تعداد تست</label>
                                    <div
                                        class="fw-semibold">{{ $selectedSession->programPart?->test_count ?? '-' }}</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">تاریخ</label>
                                    <div
                                        class="fw-semibold">{{ $selectedSession->started_at ? jdate($selectedSession->started_at)->format('l، d F Y') : '-' }}</div>

                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">زمان شروع</label>
                                    <div
                                        class="fw-semibold">{{ $selectedSession->started_at ? $selectedSession->started_at->format('H:i') : '-' }}</div>
                                </div>

                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">زمان پایان</label>
                                    <div
                                        class="fw-semibold">{{ $selectedSession->ended_at ? $selectedSession->ended_at->format('H:i') : '-' }}</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">مدت زمان واقعی</label>
                                    <div>
                                        <span class="badge rounded-pill text-bg-info px-3 py-2">
                                            {{ $this->formatDuration($selectedSession->duration_seconds) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="small text-muted mb-1">مدت زمان برنامه‌ریزی شده</label>
                                    <div>
                                        <span class="badge rounded-pill text-bg-warning px-3 py-2">
                                            {{ $this->formatDuration($selectedSession->planned_seconds) }}
                                        </span>
                                    </div>
                                </div>

                                @if($selectedSession->programPart?->description)
                                    <div class="col-12">
                                        <label class="small text-muted mb-1">توضیحات پارت</label>
                                        <div
                                            class="p-3 bg-light rounded">{{ $selectedSession->programPart->description }}</div>
                                    </div>
                                @endif

                                @if($selectedSession->feedback)
                                    <div class="col-12">
                                        <div class="border-top pt-3 mt-2">
                                            <h6 class="fw-bold mb-3">بازخورد دانش‌آموز</h6>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="small text-muted mb-1">امتیاز</label>
                                                    <div>
                                                        <span class="badge rounded-pill text-bg-success px-3 py-2 fs-6">
                                                            ⭐ {{ $selectedSession->feedback->rating }}/10
                                                        </span>
                                                    </div>
                                                </div>

                                                @if($selectedSession->feedback->comment)
                                                    <div class="col-12">
                                                        <label class="small text-muted mb-1">نظر دانش‌آموز</label>
                                                        <div
                                                            class="p-3 bg-light rounded">{{ $selectedSession->feedback->comment }}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12">
                                        <div class="alert alert-warning mb-0">
                                            <small>بازخوردی برای این جلسه ثبت نشده است.</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" wire:click="closeDetailModal" class="btn btn-secondary">
                            بستن
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
