<div>
    @push('link')
        <style>
            /* ───────────── تعریف متغیرهای رنگی (لایت مود) ───────────── */
            :root {
                --dash-bg-card: #ffffff;
                --dash-border: rgba(0, 0, 0, 0.08);
                --dash-hover: #f8f9fa;
                --dash-text: #212529;
                --dash-text-muted: #6c757d;
                --dash-header-bg: #f8f9fa;
                --dash-shadow: 0 4px 12px rgba(0,0,0,0.03);
                --dash-shadow-hover: 0 10px 20px rgba(0,0,0,0.05);
                --dash-input-bg: #ffffff;
                --dash-input-border: #dee2e6;
            }

            /* ───────────── تعریف متغیرهای رنگی (دارک مود) ───────────── */
            /* اگر قالب شما از کلاس یا اِتریبیوت خاصی برای دارک مود استفاده می‌کند، آن را اینجا اضافه کنید */
            [data-theme="dark"],
            [data-bs-theme="dark"],
            .dark-mode {
                --dash-bg-card: #1e1e2d;         /* رنگ تیره حرفه‌ای برای کارت‌ها */
                --dash-border: rgba(255, 255, 255, 0.1);
                --dash-hover: #2b2b40;
                --dash-text: #e4e6ef;
                --dash-text-muted: #a1a5b7;
                --dash-header-bg: #151521;       /* پس‌زمینه هدر کارت‌ها */
                --dash-shadow: 0 4px 12px rgba(0,0,0,0.2);
                --dash-shadow-hover: 0 10px 20px rgba(0,0,0,0.3);
                --dash-input-bg: #151521;
                --dash-input-border: rgba(255, 255, 255, 0.15);
            }

            /* ───────────── استایل‌های سفارشی بر پایه متغیرها ───────────── */
            .dashboard-wrapper {
                font-family: inherit;
                color: var(--dash-text);
            }

            .dash-text-muted { color: var(--dash-text-muted) !important; }
            .dash-border { border-color: var(--dash-border) !important; }

            .stat-card {
                background-color: var(--dash-bg-card);
                border: 1px solid var(--dash-border);
                border-radius: 12px;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                box-shadow: var(--dash-shadow);
            }
            .stat-card:hover {
                transform: translateY(-3px);
                box-shadow: var(--dash-shadow-hover) !important;
            }

            .icon-box {
                width: 54px;
                height: 54px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 12px;
                font-size: 1.5rem;
            }

            .modern-card {
                background-color: var(--dash-bg-card);
                border: 1px solid var(--dash-border);
                border-radius: 12px;
                box-shadow: var(--dash-shadow);
                overflow: hidden;
            }
            .modern-card-header {
                background-color: var(--dash-header-bg);
                padding: 1rem 1.25rem;
                border-bottom: 1px solid var(--dash-border);
            }

            .student-item {
                border-bottom: 1px solid var(--dash-border);
                transition: background-color 0.2s ease;
            }
            .student-item:last-child {
                border-bottom: none;
            }
            .student-item:hover, .dash-hover-item:hover {
                background-color: var(--dash-hover);
            }

            /* فرم‌ها و اینپوت‌ها هماهنگ با دارک مود */
            .form-control, .form-select, .input-group-text {
                background-color: var(--dash-input-bg);
                border-color: var(--dash-input-border);
                color: var(--dash-text);
            }
            .form-control:focus, .form-select:focus {
                background-color: var(--dash-input-bg);
                color: var(--dash-text);
                border-color: #86b7fe;
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            }

            .time-input-group {
                direction: ltr;
                width: 120px;
            }

            /* جداول */
            .table-modern {
                color: var(--dash-text);
                border-color: var(--dash-border);
            }
            .table-modern th {
                font-weight: 600;
                background-color: var(--dash-header-bg);
                border-bottom: 2px solid var(--dash-border);
                color: var(--dash-text);
            }
            .table-modern td, .table-modern th {
                padding: 1rem;
                vertical-align: middle;
                border-color: var(--dash-border);
            }
            .table-modern tbody tr:hover {
                background-color: var(--dash-hover);
            }

            /* اصلاح رنگ پس‌زمینه حالت‌های Subtle در دارک مود (اختیاری برای زیبایی بیشتر) */
            [data-theme="dark"] .bg-primary-subtle, [data-bs-theme="dark"] .bg-primary-subtle { background-color: rgba(13, 110, 253, 0.15) !important; }
            [data-theme="dark"] .bg-success-subtle, [data-bs-theme="dark"] .bg-success-subtle { background-color: rgba(25, 135, 84, 0.15) !important; }
            [data-theme="dark"] .bg-warning-subtle, [data-bs-theme="dark"] .bg-warning-subtle { background-color: rgba(255, 193, 7, 0.15) !important; }
            [data-theme="dark"] .bg-danger-subtle,  [data-bs-theme="dark"] .bg-danger-subtle  { background-color: rgba(220, 53, 69, 0.15) !important; }

            /* اصلاح پس‌زمینه کارت فرم تماس ذخیره شده */
            .saved-form-box {
                background-color: var(--dash-bg-card);
                border: 1px solid var(--dash-border);
            }
        </style>
    @endpush


    <div class="dashboard-wrapper" dir="rtl">
        {{-- ───────────── هدر و نان‌ریزه ───────────── --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}" class="text-decoration-none dash-text-muted"><i class="ri-home-4-line ms-1"></i>صفحه اصلی</a></li>
                    <li class="breadcrumb-item active fw-bold" aria-current="page">جلسات مشاوره</li>
                </ol>
            </nav>
        </div>

        {{-- ───────────── داشبورد آمار ───────────── --}}
        <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-4 mb-4">
            <!-- کارت 1 -->
            <div class="col">
                <div class="stat-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-box bg-primary-subtle text-primary">
                            <i class="ri-group-line"></i>
                        </div>
                        <div class="text-start">
                            <p class="dash-text-muted small mb-1">کل دانش‌آموزان من</p>
                            <h4 class="mb-0 fw-bold">{{ $totalStudentsCount }} <span class="fs-6 dash-text-muted fw-normal">نفر</span></h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- کارت 2 -->
            <div class="col">
                <div class="stat-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-box bg-success-subtle text-success">
                            <i class="ri-calendar-check-line"></i>
                        </div>
                        <div class="text-start">
                            <p class="dash-text-muted small mb-1">جلسات برگزار شده</p>
                            <h4 class="mb-0 fw-bold">{{ $heldSessionsCount }} <span class="fs-6 dash-text-muted fw-normal">جلسه</span></h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- کارت 3 -->
            <div class="col">
                <div class="stat-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-box bg-warning-subtle text-warning">
                            <i class="ri-calendar-todo-line"></i>
                        </div>
                        <div class="text-start">
                            <p class="dash-text-muted small mb-1">کل جلسات جبرانی</p>
                            <h4 class="mb-0 fw-bold">{{ $totalMakeupSessionsCount }} <span class="fs-6 dash-text-muted fw-normal">جلسه</span></h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- کارت 4 -->
            <div class="col">
                <div class="stat-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="icon-box bg-danger-subtle text-danger">
                            <i class="ri-user-unfollow-line"></i>
                        </div>
                        <div class="text-start">
                            <p class="dash-text-muted small mb-1">غایبین این هفته</p>
                            <h4 class="mb-0 fw-bold">{{ $absenteesThisWeek->count() }} <span class="fs-6 dash-text-muted fw-normal">نفر</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $renderStudentName = fn($st) => $st->user?->personalInformation?->name
                ?? $st->user?->personalInformation?->name_full
                ?? $st->user?->name ?? 'دانش‌آموز';
        @endphp

        {{-- ───────────── هدر بخش اصلی و جستجو ───────────── --}}
        <div class="d-flex justify-content-between align-items-end mb-3 flex-wrap gap-3">
            <div>
                <h4 class="mb-1 fw-bold">مدیریت جلسات</h4>
                <p class="small dash-text-muted mb-0">
                    فقط برای <strong>فردا</strong> می‌توانید تماس بگیرید و ساعت جلسه را تعیین کنید.
                </p>
            </div>
            <div class="input-group shadow-sm" style="max-width:300px">
                <span class="input-group-text border-end-0 dash-text-muted"><i class="ri-search-line"></i></span>
                <input type="text" wire:model.live.debounce.400ms="search" class="form-control border-start-0 ps-0" placeholder="جستجوی دانش‌آموز...">
            </div>
        </div>

        {{-- ───────────── باکسِ فردا (قابلِ عملیات) ───────────── --}}
        <div class="modern-card border-primary mb-5" style="border-color: #0d6efd !important;">
            <div class="modern-card-header bg-primary-subtle d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0 text-primary fw-bold d-flex align-items-center">
                    <i class="ri-calendar-event-line fs-4 ms-2"></i>
                    فردا — {{ $days[$tomorrowDow] ?? '' }} ({{ $tomorrowTitle }})
                    <span class="badge bg-primary ms-3 rounded-pill">{{ $tomorrowStudents->count() }} دانش‌آموز</span>
                </h5>
                @if ($canFinalize)
                    <button wire:click="finalizeAll" wire:confirm="جلساتِ فردا ثبتِ نهایی شود؟ پس از آن به دانش‌آموزان اطلاع داده می‌شود." class="btn btn-success btn-sm shadow-sm">
                        <i class="ri-check-double-line ms-1"></i> ثبت نهاییِ جلساتِ فردا
                    </button>
                @endif
            </div>
            <div class="card-body p-0">
                @forelse ($tomorrowStudents as $st)
                    @php
                        $profile = $st->user?->profile;
                        $called  = $calledIds->has($st->id);
                        $session = $tomorrowSessions->get($st->id);
                        $isFinal = $session && $session->finalized;
                    @endphp
                    <div class="student-item p-3">
                        <div class="row align-items-center py-1">
                            <!-- اطلاعات دانش آموز -->
                            <div class="col-lg-4 col-md-12 d-flex align-items-center gap-3 mb-3 mb-lg-0">
                                @if ($profile && $profile->picture)
                                    <img src="{{ asset('user/img/' . $st->user->id . '/' . $profile->picture) }}" class="rounded-circle shadow-sm" width="50" height="50" style="object-fit:cover" alt="">
                                @else
                                    <div class="rounded-circle bg-secondary-subtle text-secondary fw-bold d-flex align-items-center justify-content-center shadow-sm" style="width:50px;height:50px;font-size:1.2rem;">
                                        {{ mb_substr($renderStudentName($st), 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-1 fw-bold d-flex align-items-center gap-2">
                                        {{ $renderStudentName($st) }}
                                        @if ($session && $session->is_makeup)
                                            <span class="badge bg-warning text-dark rounded-pill" style="font-size:0.7rem">جبرانی</span>
                                        @endif
                                    </h6>
                                    <div class="small dash-text-muted mb-1" dir="ltr" style="text-align: right;"><i class="ri-phone-line ms-1"></i>{{ $st->user?->mobile ?? 'بدون شماره' }}</div>
                                    <a href="{{ route('admin.student.advising-sessions.create', $st->user_id) }}" class="small text-decoration-none text-primary"><i class="ri-history-line ms-1"></i>جزئیات و تاریخچه</a>
                                </div>
                            </div>

                            <!-- عملیات و فرم -->
                            <div class="col-lg-8 col-md-12">
                                @if ($isFinal)
                                    <div class="d-flex justify-content-lg-end">
                                    <span class="badge bg-success py-2 px-3 fs-6 rounded-pill">
                                        <i class="ri-check-double-line ms-1"></i> ثبتِ نهایی — ساعت {{ $session->session_time?->format('H:i') }}
                                    </span>
                                    </div>
                                @elseif (! $called)
                                    <div class="d-flex align-items-center justify-content-lg-end flex-wrap gap-2">
                                        <span class="small dash-text-muted me-2"><i class="ri-information-line ms-1"></i>برای تعیین ساعت، ابتدا تماس بگیرید.</span>
                                        <button wire:click="openCall({{ $st->id }})" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                            <i class="ri-phone-fill ms-1"></i> ثبت تماس و هماهنگی
                                        </button>
                                    </div>
                                @else
                                    <div class="p-3 saved-form-box rounded-3 shadow-sm">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                                            <span class="badge bg-success-subtle text-success py-2 px-3 rounded-pill mb-2 mb-md-0"><i class="ri-phone-line ms-1"></i> تماس انجام شد</span>
                                            @if ($session)
                                                <span class="small text-success fw-bold"><i class="ri-checkbox-circle-fill ms-1"></i> ذخیره شده (در انتظار ثبت نهایی)</span>
                                            @endif
                                        </div>

                                        <div class="row g-3 align-items-end">
                                            <!-- ساعت و دقیقه -->
                                            <div class="col-12 col-md-auto">
                                                <label class="form-label small dash-text-muted mb-1">زمان جلسه</label>
                                                <div class="input-group input-group-sm time-input-group m-auto ms-md-0">
                                                    <input type="number" min="0" max="23" class="form-control text-center" wire:model="schedule.{{ $st->id }}.hour" placeholder="ساعت">
                                                    <span class="input-group-text dash-text-muted">:</span>
                                                    <input type="number" min="0" max="59" class="form-control text-center" wire:model="schedule.{{ $st->id }}.minute" placeholder="دقیقه">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md">
                                                <label class="form-label small dash-text-muted mb-1">لینک جلسه آنلاین (اختیاری)</label>
                                                <input type="url" class="form-control form-control-sm" dir="ltr" wire:model="schedule.{{ $st->id }}.link" placeholder="https://...">
                                            </div>
                                            <div class="col-12 col-md-auto text-end">
                                                <button wire:click="saveSchedule({{ $st->id }})" class="btn btn-primary btn-sm px-4 shadow-sm w-100 w-md-auto">
                                                    <i class="ri-save-line ms-1"></i> ذخیره
                                                </button>
                                            </div>
                                        </div>
                                        <!-- ارورها -->
                                        <div class="mt-2">
                                            @error("schedule.{$st->id}.hour") <span class="text-danger small d-block"><i class="ri-error-warning-line ms-1"></i>{{ $message }}</span> @enderror
                                            @error("schedule.{$st->id}.minute") <span class="text-danger small d-block"><i class="ri-error-warning-line ms-1"></i>{{ $message }}</span> @enderror
                                            @error("schedule.{$st->id}.link") <span class="text-danger small d-block"><i class="ri-error-warning-line ms-1"></i>{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center dash-text-muted py-5">
                        <i class="ri-calendar-event-line opacity-50" style="font-size: 4rem;"></i>
                        <p class="mt-3 mb-0 fs-5">برای فردا دانش‌آموزی در لیست ندارید.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ───────────── غایبین این هفته ───────────── --}}
        @if ($absenteesThisWeek->isNotEmpty())
            <div class="modern-card mb-4" style="border-color: #dc3545 !important;">
                <div class="modern-card-header bg-danger-subtle">
                    <h5 class="mb-0 text-danger fw-bold"><i class="ri-user-unfollow-line ms-1"></i> غایبین این هفته ({{ $absenteesThisWeek->count() }})</h5>
                    <p class="small text-danger mb-0 mt-1 opacity-75">دانش‌آموزانی که این هفته جلسه داشته‌اند اما وضعیت جلسه به "برگزار شده" تغییر نکرده است.</p>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern mb-0 align-middle">
                            <thead>
                            <tr>
                                <th>نام دانش‌آموز</th>
                                <th>تاریخ جلسه</th>
                                <th class="text-end">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($absenteesThisWeek as $session)
                                <tr>
                                    <td class="fw-bold">{{ $renderStudentName($session->student) }}</td>
                                    <td>
                                    <span class="badge bg-secondary-subtle dash-text-muted dash-border p-2" style="border: 1px solid">
                                        <i class="ri-calendar-line ms-1"></i> {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($session->activation_date))->format('l, d F Y') }}
                                    </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.student.advising-sessions.create', $session->student_id) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">بررسی جلسه</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- ───────────── جلسات جبرانیِ در انتظارِ تعیینِ روز ───────────── --}}
        @if ($pendingMakeups->isNotEmpty())
            <div class="modern-card mb-4" style="border-color: #ffc107 !important;">
                <div class="modern-card-header bg-warning-subtle">
                    <h5 class="mb-0 text-warning fw-bold" style="filter: brightness(0.8);"><i class="ri-calendar-todo-fill ms-1"></i> جلسات جبرانی در انتظار ({{ $pendingMakeups->count() }})</h5>
                    <p class="small dash-text-muted mb-0 mt-1">ناشی از مرخصیِ تاییدشده — برای هر دانش‌آموز روزِ جلسه‌ی جبرانی را مشخص کنید.</p>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3">
                        @foreach ($pendingMakeups as $mk)
                            <div class="col-12 col-xl-6">
                                <div class="d-flex flex-wrap align-items-center justify-content-between p-3 dash-border rounded-3 shadow-sm dash-hover-item" style="border: 1px solid">
                                    <div class="fw-bold mb-2 mb-md-0 d-flex align-items-center">
                                        <i class="ri-user-smile-line text-primary ms-2 fs-5"></i>
                                        {{ $mk->student?->user?->personalInformation?->name ?? $mk->student?->user?->name ?? 'دانش‌آموز' }}
                                    </div>
                                    <div class="d-flex align-items-center gap-2 w-100 w-md-auto">
                                        <select wire:model="makeupDay.{{ $mk->id }}" class="form-select form-select-sm shadow-none" style="min-width: 140px;">
                                            <option value="">انتخاب روز جبرانی...</option>
                                            @foreach ($days as $d => $name)
                                                <option value="{{ $d }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        <button wire:click="assignMakeupDay({{ $mk->id }})" class="btn btn-warning btn-sm text-dark text-nowrap px-3 shadow-sm">تعیین روز</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- ───────────── همه‌ی دانش‌آموزانِ من ───────────── --}}
        <div class="modern-card mb-5">
            <div class="modern-card-header">
                <h5 class="mb-0 fw-bold"><i class="ri-team-line ms-1 text-primary"></i> همه‌ی دانش‌آموزانِ من ({{ $allStudents->count() }})</h5>
                <p class="small dash-text-muted mb-0 mt-1">فهرست کامل دانش‌آموزانِ تحتِ مشاوره به‌همراه روزِ جلسه‌ی هفتگی.</p>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0 align-middle">
                        <thead>
                        <tr>
                            <th style="width:50px" class="text-center">#</th>
                            <th>نام دانش‌آموز</th>
                            <th>شماره موبایل</th>
                            <th>روز جلسه هفتگی</th>
                            <th class="text-end">عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($allStudents as $st)
                            <tr>
                                <td class="text-center dash-text-muted">{{ $loop->iteration }}</td>
                                <td class="fw-bold">{{ $renderStudentName($st) }}</td>
                                <td dir="ltr" class="text-end dash-text-muted">{{ $st->user?->mobile ?? '—' }}</td>
                                <td>
                                    @if ($st->session_day !== null)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle p-2 px-3 rounded-pill"><i class="ri-calendar-line ms-1"></i>{{ $days[$st->session_day] ?? '—' }}</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle p-2 px-3 rounded-pill"><i class="ri-error-warning-line ms-1"></i>تعیین‌نشده</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.student.advising-sessions.create', $st->user_id) }}" class="btn btn-sm btn-outline-secondary shadow-sm rounded-pill px-3">جزئیات / تاریخچه</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center dash-text-muted py-5">
                                    <i class="ri-user-search-line fs-1 opacity-50 d-block mb-2"></i>
                                    در حال حاضر دانش‌آموزی ندارید.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ───────────── باکس‌های سایرِ روزها (فقط نمایش) ───────────── --}}
        <h5 class="fw-bold mb-3"><i class="ri-calendar-2-line ms-1 dash-text-muted"></i> زمان‌بندی سایر روزهای هفته</h5>
        <div class="row g-4 mb-4">
            @foreach ($days as $d => $dayName)
                @if ($d === $tomorrowDow)
                    @continue
                @endif
                @php $dayStudents = collect($grouped->get($d, collect())); @endphp
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="modern-card h-100 border-0">
                        <div class="modern-card-header d-flex justify-content-between align-items-center py-2">
                            <h6 class="mb-0 fw-bold">{{ $dayName }}</h6>
                            <span class="badge bg-secondary rounded-pill px-2">{{ $dayStudents->count() }} نفر</span>
                        </div>
                        <div class="card-body p-2">
                            @forelse ($dayStudents as $st)
                                <div class="d-flex align-items-center justify-content-between p-2 dash-border dash-hover-item rounded" style="border-bottom: 1px solid">
                                    <a href="{{ route('admin.student.advising-sessions.create', $st->user_id) }}" class="small text-decoration-none fw-bold" style="color: var(--dash-text);">
                                        <i class="ri-user-line dash-text-muted ms-1"></i> {{ $renderStudentName($st) }}
                                    </a>
                                    <span class="small dash-text-muted" dir="ltr">{{ $st->user?->mobile ?? '' }}</span>
                                </div>
                            @empty
                                <div class="text-center dash-text-muted small py-4 opacity-50">
                                    <i class="ri-calendar-close-line fs-3 d-block mb-1"></i>
                                    بدون دانش‌آموز
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- دانش‌آموزانِ بدونِ روزِ مشخص (در انتظارِ تعیینِ روز توسط مدیر) --}}
        @if ($noDayStudents->isNotEmpty())
            <div class="modern-card mb-5 border-start border-warning border-4">
                <div class="modern-card-header pb-0 border-0 bg-transparent">
                    <h6 class="mb-0 text-warning fw-bold"><i class="ri-error-warning-fill ms-1"></i> بدونِ روزِ مشخص ({{ $noDayStudents->count() }})</h6>
                    <p class="small dash-text-muted mb-3 mt-1">برای این دانش‌آموزان هنوز روزِ ثابتِ هفتگی تعیین نشده است.</p>
                </div>
                <div class="card-body pt-0 p-3">
                    <div class="row g-2">
                        @foreach ($noDayStudents as $st)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="d-flex align-items-center justify-content-between p-2 dash-border rounded" style="border: 1px solid; background-color: var(--dash-header-bg);">
                                    <span class="small fw-bold"><i class="ri-user-line dash-text-muted ms-1"></i>{{ $renderStudentName($st) }}</span>
                                    <span class="badge dash-text-muted dash-border" dir="ltr" style="border: 1px solid; background: var(--dash-bg-card);">{{ $st->user?->mobile ?? '' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- ───────────── مودالِ تماس ───────────── --}}
        @include('livewire.admin.student.consultation.partials.call-modal')
    </div>
</div>
