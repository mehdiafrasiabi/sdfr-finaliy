<div class="student-ui student-ui-auto-collapse">
    @include('livewire.admin.student._styles')
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

            /* اصلاح پس‌زمینه کارت فرم ذخیره شده */
            .saved-form-box {
                background-color: var(--dash-bg-card);
                border: 1px solid var(--dash-border);
            }

            /* کارتِ روزِ هفته در بخشِ زمان‌بندی */
            .day-student-row {
                background-color: var(--dash-bg-card);
                border: 1px solid var(--dash-border) !important;
            }

            /* بازخوردِ فوریِ فشردنِ دکمه‌ها */
            .btn:active {
                transform: scale(0.97);
            }
            .btn {
                transition: transform 0.1s ease-out, filter 0.15s ease, box-shadow 0.15s ease;
            }

            /* جعبه‌ی ساعت/دقیقه‌ی جلسه — فلکسِ سادهِ مستقل از input-group بوتسترپ */
            .time-input-group {
                direction: ltr;
                display: flex;
                align-items: center;
                flex-wrap: nowrap;
                gap: 0.5rem;
                max-width: 220px;
            }
            .time-input-group .form-control {
                flex: 0 0 80px;
                width: 80px;
            }
            .time-input-group .time-sep {
                font-weight: 700;
                font-size: 1.1rem;
                color: var(--dash-text-muted);
                flex: 0 0 auto;
            }

            /* ورودیِ تاریخِ شمسی */
            .jdp-date-input {
                cursor: pointer;
                background-color: var(--dash-input-bg) !important;
                color: var(--dash-text) !important;
                border-color: var(--dash-input-border) !important;
            }
            /* پاپ‌آپِ تقویمِ شمسی از یک لایه‌ی مستقل (خارج از مودال) رندر می‌شود؛
               باید بالاترِ بک‌دراپ و بدنه‌ی مودالِ بوتسترپ باشد وگرنه پشتِ آن پنهان می‌شود. */
            jdp-container {
                z-index: 99999 !important;
            }

            /* هماهنگ‌سازیِ select2 با تم — با important چون CSS خودِ select2 بعد از این استایل بارگذاری می‌شود */
            .select2-container--default .select2-selection--single {
                background-color: var(--dash-input-bg) !important;
                border: 1px solid var(--dash-input-border) !important;
                height: calc(1.5em + 0.75rem + 2px) !important;
                display: flex !important;
                align-items: center !important;
                border-radius: 0.375rem !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: var(--dash-text) !important;
                line-height: normal !important;
                padding-inline-start: 0.75rem !important;
                padding-inline-end: 1.5rem !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 100% !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow b {
                border-color: var(--dash-text-muted) transparent transparent transparent !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__placeholder {
                color: var(--dash-text-muted) !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__clear {
                color: var(--dash-text-muted) !important;
            }
            .select2-dropdown {
                background-color: var(--dash-input-bg) !important;
                border-color: var(--dash-input-border) !important;
                z-index: 99999 !important;
            }
            .select2-container--default .select2-results__option {
                background-color: var(--dash-input-bg) !important;
                color: var(--dash-text) !important;
            }
            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: #0d6efd !important;
                color: #fff !important;
            }
            .select2-container--default .select2-results__option[aria-selected="true"] {
                background-color: var(--dash-hover) !important;
                color: var(--dash-text) !important;
            }
            .select2-search--dropdown {
                background-color: var(--dash-input-bg) !important;
            }
            .select2-search--dropdown .select2-search__field {
                background-color: var(--dash-input-bg) !important;
                color: var(--dash-text) !important;
                border: 1px solid var(--dash-input-border) !important;
                border-radius: 0.375rem !important;
            }
            .select2-container--default .select2-results > .select2-results__options {
                max-height: 260px;
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
        <details class="su-data-drawer" open>
            <summary>خلاصه وضعیت مشاوره <span class="su-data-drawer__hint">دانش‌آموزان، جلسات و غیبت‌ها</span></summary>
            <div class="su-data-drawer__body">
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
            </div>
        </details>

        @php
            $renderStudentName = fn($st) => $this->studentDisplayName($st);
        @endphp

        {{-- ───────────── هدر بخش اصلی و جستجو ───────────── --}}
        <div class="d-flex justify-content-between align-items-end mb-3 flex-wrap gap-3">
            <div>
                <h4 class="mb-1 fw-bold">مدیریت جلسات</h4>
                <p class="small dash-text-muted mb-0">
                    جلسه‌ی مشاوره‌ی هر دانش‌آموز را به‌صورتِ دستی، برایِ روزِ برنامه‌ی هفتگی‌اش یا هر تاریخِ دیگری تعریف یا ویرایش کنید.
                </p>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button wire:click="openQuickSession" data-bs-toggle="modal" data-bs-target="#quickSessionModal" class="btn btn-success shadow-sm">
                    <i class="ri-flashlight-line ms-1"></i> تعریفِ جلسه
                </button>
                <div class="input-group shadow-sm" style="max-width:300px">
                    <span class="input-group-text border-end-0 dash-text-muted"><i class="ri-search-line"></i></span>
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control border-start-0 ps-0" placeholder="جستجوی دانش‌آموز...">
                </div>
            </div>
        </div>

        {{-- ───────────── جلسات امروز من ───────────── --}}
        <div class="modern-card mb-4">
            <div class="modern-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0 fw-bold"><i class="ri-time-line ms-1 text-primary"></i> جلسات امروز من</h5>
                <span class="badge bg-primary rounded-pill">{{ $todaySessions->count() }} جلسه</span>
            </div>
            <div class="card-body p-0">
                @forelse($todaySessions as $todaySession)
                    <div class="student-item p-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div>
                                <div class="fw-bold d-flex align-items-center gap-2">
                                    {{ $renderStudentName($todaySession->student) }}
                                    @if($todaySession->is_makeup)
                                        <span class="badge bg-warning text-dark rounded-pill">جبرانی</span>
                                    @endif
                                </div>
                                <div class="small dash-text-muted mt-1">
                                    {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($todaySession->activation_date))->format('Y/m/d') }}
                                    @if($todaySession->session_time)
                                        <span dir="ltr"> - {{ $todaySession->session_time->format('H:i') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-secondary-subtle dash-text-muted dash-border p-2" style="border: 1px solid">
                                    {{ $todaySession->result_status ? $todaySession->result_label : $todaySession->status_label }}
                                </span>
                                <button wire:click="openQuickSession({{ $todaySession->student_id }}, '{{ \Illuminate\Support\Carbon::parse($todaySession->activation_date)->toDateString() }}')" data-bs-toggle="modal" data-bs-target="#quickSessionModal" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="ri-edit-line ms-1"></i> ویرایش
                                </button>
                                <a href="{{ route('admin.student.advising-sessions.create', $todaySession->student?->user_id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">جزئیات</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center dash-text-muted py-4">
                        <i class="ri-calendar-check-line opacity-50 fs-1 d-block mb-2"></i>
                        امروز جلسه‌ای ثبت نشده است.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ───────────── غایبین این هفته ───────────── --}}
        @if ($absenteesThisWeek->isNotEmpty())
            <div class="modern-card mb-4" style="border-color: #dc3545 !important;">
                <div class="modern-card-header bg-danger-subtle">
                    <h5 class="mb-0 text-danger fw-bold"><i class="ri-user-unfollow-line ms-1"></i> غایبین این هفته ({{ $absenteesThisWeek->count() }})</h5>
                    <p class="small text-danger mb-0 mt-1 opacity-75">دانش‌آموزانی که برای جلسه این هفته آن‌ها غیبت دانش‌آموز ثبت شده است.</p>
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
                                        <button wire:click="createMakeupForAbsentSession({{ $session->id }})"
                                                class="btn btn-sm btn-outline-warning rounded-pill px-3 ms-2">
                                            تعیین جلسه جبرانی
                                        </button>
                                        <a href="{{ route('admin.student.advising-sessions.create', $session->student?->user_id) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">بررسی جلسه</a>
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
                    <p class="small dash-text-muted mb-0 mt-1">برای هر دانش‌آموز روزِ جلسه‌ی جبرانی را مشخص کنید؛ سپس ساعتِ آن را از طریقِ «تعریفِ جلسه» ثبت کنید.</p>
                </div>
                <div class="card-body p-3">
                    @if($pendingMakeups->contains(fn($mk) => $mk->created_at && $mk->created_at->lt(now()->subDays(7))))
                        <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
                            <i class="ri-error-warning-line fs-5"></i>
                            <div>برخی غیبت‌ها بیش از ۷ روز است تعیین تکلیف نشده‌اند. مسئولیت پیگیری و عواقب تأخیر با مشاور است.</div>
                        </div>
                    @endif
                    <div class="row g-3">
                        @foreach ($pendingMakeups as $mk)
                            @php $isOverdueMakeup = $mk->created_at && $mk->created_at->lt(now()->subDays(7)); @endphp
                            <div class="col-12 col-xl-6">
                                <div class="p-3 dash-border rounded-3 shadow-sm dash-hover-item {{ $isOverdueMakeup ? 'border-danger' : '' }}" style="border: 1px solid">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                        <div class="fw-bold d-flex align-items-center">
                                            <i class="ri-user-smile-line text-primary ms-2 fs-5"></i>
                                            {{ $mk->student?->user?->personalInformation?->name ?? $mk->student?->user?->name ?? 'دانش‌آموز' }}
                                        </div>
                                        @if($isOverdueMakeup)
                                            <span class="badge bg-danger">بیش از ۷ روز</span>
                                        @endif
                                    </div>
                                    <div class="d-flex align-items-center gap-2 w-100">
                                        <select wire:model="makeupDate.{{ $mk->id }}" class="form-select form-select-sm shadow-none">
                                            <option value="">انتخاب تاریخ جبرانی...</option>
                                            @foreach ($allowedMakeupDates as $dateValue => $dateLabel)
                                                <option value="{{ $dateValue }}">{{ $dateLabel }}</option>
                                            @endforeach
                                        </select>
                                        <button wire:click="assignMakeupDate({{ $mk->id }})" class="btn btn-warning btn-sm text-dark text-nowrap px-3 shadow-sm">تعیین تاریخ</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- ───────────── زمان‌بندیِ هفتگیِ جلسات (بر اساسِ جلسه‌ی تعریف‌شده در همان روز) ───────────── --}}
        <h5 class="fw-bold mb-3"><i class="ri-calendar-2-line ms-1 dash-text-muted"></i> زمان‌بندی هفتگی جلسات</h5>
        <div class="row g-4 mb-4">
            @foreach ($days as $d => $dayName)
                @php
                    $dayStudents = collect($grouped->get($d, collect()));
                    $dayDate = $dayDates[$d];
                    $dayDateLabel = \Morilog\Jalali\Jalalian::fromCarbon($dayDate)->format('Y/m/d');
                    $isToday = $d === $todayDow;
                @endphp
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="modern-card h-100 {{ $isToday ? '' : 'border-0' }}" @if($isToday) style="border-color:#0d6efd !important;" @endif>
                        <div class="modern-card-header d-flex justify-content-between align-items-center py-2 {{ $isToday ? 'bg-primary-subtle' : '' }}">
                            <div>
                                <h6 class="mb-0 fw-bold {{ $isToday ? 'text-primary' : '' }}">
                                    {{ $dayName }}
                                    @if($isToday)
                                        <span class="badge bg-primary rounded-pill ms-1">امروز</span>
                                    @endif
                                </h6>
                                <span class="small dash-text-muted" style="font-size:.75rem;">{{ $dayDateLabel }}</span>
                            </div>
                            <span class="badge bg-secondary rounded-pill px-2">{{ $dayStudents->count() }} نفر</span>
                        </div>
                        <div class="card-body p-2">
                            @forelse ($dayStudents as $st)
                                @php
                                    $activeMakeup = $activeMakeupsByStudent->get($st->id);
                                    $session = $weekSessions->get($st->id);
                                    $isAbsent = $session && $session->result_status === \App\Models\AdvisingSession::RESULT_STUDENT_ABSENT;
                                    $hasTime = $session && $session->session_time && ! $isAbsent;
                                @endphp
                                <div class="p-2 day-student-row dash-hover-item rounded mb-2">
                                    <div class="d-flex align-items-center justify-content-between mb-2 gap-2">
                                        <div>
                                            <div class="small fw-bold d-flex align-items-center gap-2" style="color: var(--dash-text);">
                                                <i class="ri-user-line dash-text-muted ms-1"></i> {{ $renderStudentName($st) }}
                                                @if($activeMakeup)
                                                    <span class="badge bg-warning text-dark rounded-pill">جبرانی</span>
                                                @endif
                                            </div>
                                            <div class="small dash-text-muted" dir="ltr">{{ $st->user?->mobile ?? '' }}</div>
                                        </div>
                                        <a href="{{ route('admin.student.advising-sessions.create', $st->user_id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-2" title="جزئیات و تاریخچه"><i class="ri-history-line"></i></a>
                                    </div>

                                    @if ($hasTime)
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                            <span class="badge {{ $session->finalized ? 'bg-success' : 'bg-secondary' }} rounded-pill" dir="ltr">
                                                <i class="ri-time-line ms-1"></i>{{ $session->session_time->format('H:i') }}
                                            </span>
                                            <button wire:click="openQuickSession({{ $st->id }}, '{{ $dayDate->toDateString() }}')" data-bs-toggle="modal" data-bs-target="#quickSessionModal" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                                <i class="ri-edit-line ms-1"></i> ویرایش
                                            </button>
                                        </div>
                                    @elseif ($isAbsent)
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                            <span class="badge bg-danger-subtle text-danger rounded-pill"><i class="ri-user-unfollow-line ms-1"></i> غیبت ثبت شد</span>
                                            <button wire:click="openQuickSession({{ $st->id }}, '{{ $dayDate->toDateString() }}')" data-bs-toggle="modal" data-bs-target="#quickSessionModal" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                                <i class="ri-edit-line ms-1"></i> تعریف مجدد
                                            </button>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                            <button wire:click="openQuickSession({{ $st->id }}, '{{ $dayDate->toDateString() }}')" data-bs-toggle="modal" data-bs-target="#quickSessionModal" class="btn btn-primary btn-sm rounded-pill px-3">
                                                <i class="ri-add-line ms-1"></i> تعریف جلسه
                                            </button>
                                            <div class="d-flex align-items-center gap-1">
                                                <select wire:model="rescheduleMakeupDate.{{ $st->id }}" class="form-select form-select-sm" style="max-width:130px;font-size:.75rem;" title="در صورتِ غیبت، تاریخ جبرانی">
                                                    <option value="">جبرانی...</option>
                                                    @foreach($allowedMakeupDates as $dateValue => $dateLabel)
                                                        <option value="{{ $dateValue }}">{{ $dateLabel }}</option>
                                                    @endforeach
                                                </select>
                                                <button wire:click="markSessionAbsent({{ $st->id }}, '{{ $dayDate->toDateString() }}')"
                                                        wire:confirm="غیبتِ این جلسه ثبت شود؟"
                                                        class="btn btn-outline-danger btn-sm" title="ثبتِ غیبت">
                                                    <i class="ri-user-unfollow-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
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

        {{-- ───────────── مودالِ تعریف/ویرایشِ جلسه ───────────── --}}
        <div class="modal fade" id="quickSessionModal" tabindex="-1" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold"><i class="ri-flashlight-line ms-1 text-success"></i> تعریف/ویرایشِ جلسه</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small dash-text-muted">جلسه به‌صورتِ دستی تعریف می‌شود؛ پس از ذخیره بلافاصله ثبتِ نهایی شده، پیش‌جلسه ساخته می‌شود و به دانش‌آموز اطلاع داده می‌شود.</p>

                        <div class="mb-3">
                            <label class="form-label small"><i class="ri-user-search-line ms-1 dash-text-muted"></i>دانش‌آموز</label>
                            <div wire:ignore
                                 x-data="{}"
                                 x-init="
                                    const el = $refs.quickStudentSelect;
                                    $(el).select2({
                                        width: '100%',
                                        dir: 'rtl',
                                        placeholder: 'جستجو و انتخابِ دانش‌آموز...',
                                        allowClear: true,
                                        dropdownParent: $(el).closest('.modal-content'),
                                    }).on('change', function () {
                                        $wire.set('quickStudentId', this.value ? parseInt(this.value) : null);
                                    });
                                    $wire.on('open-quick-session-modal', (e) => {
                                        $(el).val(e.studentId ?? '').trigger('change');
                                    });
                                 ">
                                <select x-ref="quickStudentSelect" class="form-select">
                                    <option value="">انتخاب کنید...</option>
                                    @foreach ($modalStudents as $st)
                                        <option value="{{ $st->id }}">{{ $renderStudentName($st) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('quickStudentId') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small"><i class="ri-calendar-line ms-1 dash-text-muted"></i>تاریخ جلسه (شمسی)</label>
                            <input type="text"
                                   class="form-control jdp-date-input"
                                   data-jdp
                                   data-jdp-gregorian-format="Y-m-d"
                                   autocomplete="off"
                                   readonly
                                   placeholder="انتخابِ تاریخ..."
                                   value="{{ $quickDate ? \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($quickDate))->format('Y/m/d') : '' }}"
                                   x-on:jdp:change="$wire.set('quickDate', $event.target.value)">
                            @error('quickDate') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small"><i class="ri-time-line ms-1 dash-text-muted"></i>زمان جلسه</label>
                            <div class="time-input-group">
                                <input type="number" min="0" max="23" class="form-control text-center" wire:model="quickTime.hour" placeholder="ساعت">
                                <span class="time-sep">:</span>
                                <input type="number" min="0" max="59" class="form-control text-center" wire:model="quickTime.minute" placeholder="دقیقه">
                            </div>
                            @error('quickTime.hour') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                            @error('quickTime.minute') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small"><i class="ri-link ms-1 dash-text-muted"></i>لینک جلسه آنلاین</label>
                            <input type="url" dir="ltr" wire:model="quickLink" class="form-control" placeholder="https://...">
                            @error('quickLink') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">انصراف</button>
                        <button wire:click="saveQuickSession" class="btn btn-success">
                            <i class="ri-save-line ms-1"></i> ثبت جلسه
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
