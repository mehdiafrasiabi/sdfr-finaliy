<div>
    <div class="container-xxl flex-grow-1 container-p-y bg-body text-body" dir="rtl">

        @push('link')

            <style>
                [x-cloak] {
                    display: none !important;
                }

                /* ====== Theme tokens (minimal - bootstrap aware) ====== */
                :root {
                    --ui-bg: var(--bs-body-bg);
                    --ui-card: var(--bs-body-bg);
                    --ui-border: var(--bs-border-color);
                    --ui-muted: var(--bs-secondary-color);
                    --ui-shadow: 0 10px 25px rgba(0, 0, 0, .10);
                    --ui-shadow-sm: 0 6px 16px rgba(0, 0, 0, .08);
                    --ui-radius: 16px;
                    --ui-radius-sm: 12px;
                }

                .container-p-y {
                    background: var(--ui-bg);
                    border-radius: 18px;
                }

                /* ====== Cards / headers ====== */
                .ui-card {
                    background: var(--ui-card);
                    border: 1px solid var(--ui-border) !important;
                    border-radius: var(--ui-radius) !important;
                    box-shadow: var(--ui-shadow-sm);
                }

                .ui-card .card-header {
                    border-bottom: 1px solid var(--ui-border);
                    background: var(--bs-tertiary-bg);
                    backdrop-filter: blur(6px);
                    border-top-left-radius: var(--ui-radius);
                    border-top-right-radius: var(--ui-radius);
                }

                .ui-page-hero {
                    border-radius: 22px;
                    border: 1px solid rgba(255, 255, 255, .15);
                    box-shadow: var(--ui-shadow);
                    background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 50%, #1d4ed8 100%);
                    position: relative;
                    overflow: hidden;
                }

                .ui-page-hero:before {
                    content: "";
                    position: absolute;
                    inset: -40%;
                    background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, .18), transparent 55%);
                    transform: rotate(12deg);
                }

                .ui-page-hero > .card-body {
                    position: relative;
                }

                .ui-chip {
                    background: rgba(255, 255, 255, .12);
                    border: 1px solid rgba(255, 255, 255, .18);
                    border-radius: 14px;
                }

                /* ====== Quick access tiles ====== */
                .quick-tile {
                    background: var(--bs-body-bg);
                    border: 1px solid var(--ui-border);
                    border-radius: 16px;
                    transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
                    box-shadow: 0 4px 14px rgba(0, 0, 0, .08);
                }

                .quick-tile:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 12px 26px rgba(0, 0, 0, .12);
                    border-color: rgba(37, 99, 235, .25);
                }

                .quick-tile i {
                    font-size: 26px;
                }

                /* ====== Weekly table ====== */
                .weekly-wrap {
                    border-radius: var(--ui-radius);
                    overflow: hidden;
                    border: 1px solid var(--ui-border);
                    box-shadow: var(--ui-shadow-sm);
                    background: var(--bs-body-bg);
                }

                .weekly-table {
                    margin-bottom: 0;
                    min-width: 1100px;
                }

                .weekly-table thead th {
                    position: sticky;
                    top: 0;
                    z-index: 2;
                    background: linear-gradient(90deg, #1d4ed8, #2563eb);
                    color: #fff;
                    border-color: rgba(255, 255, 255, .16);
                    font-weight: 700;
                    white-space: nowrap;
                    padding-top: .85rem;
                    padding-bottom: .85rem;
                }

                .weekly-table tbody td {
                    vertical-align: top;
                    background: var(--bs-body-bg);
                }

                .weekly-table tbody tr:hover td {
                    background: rgba(2, 132, 199, .03);
                }

                /* plan cell boxes */
                .plan-box {
                    height: 104px;
                    border-radius: 14px;
                    border: 1px solid var(--ui-border);
                    transition: transform .12s ease, box-shadow .12s ease, border-color .12s ease;
                    background: var(--bs-body-bg);
                }

                .plan-box.clickable {
                    cursor: pointer;
                }

                .plan-box.clickable:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 10px 18px rgba(0, 0, 0, .12);
                    border-color: rgba(37, 99, 235, .22);
                }

                .plan-empty {
                    border-style: dashed;
                    border-color: color-mix(in srgb, var(--bs-secondary-color) 35%, transparent);
                    background: var(--bs-tertiary-bg);
                }

                .plan-rest {
                    border-color: rgba(22, 163, 74, .22);
                    background: rgba(22, 163, 74, .06);
                }

                /* badges */
                .badge.text-xs {
                    font-size: .72rem;
                }

                /* ====== Modal ====== */
                .modal.show .modal-dialog {
                    animation: modalSlideDown .22s ease-out;
                }

                @keyframes modalSlideDown {
                    from {
                        transform: translateY(-24px);
                        opacity: 0;
                    }
                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }

                .modal-content {
                    border-radius: 18px;
                    border: 1px solid var(--ui-border);
                    overflow: hidden;
                }

                .modal-header {
                    border-bottom: 1px solid rgba(255, 255, 255, .16);
                }

                /* ====== Select2 keep normal, just align with Bootstrap ====== */
                .select2-container {
                    width: 100% !important;
                }

                .select2-container .select2-selection--single {
                    height: calc(2.375rem + 2px);
                    border: 1px solid #ced4da;
                    border-radius: .5rem;
                    padding: .375rem .75rem;
                    display: flex;
                    align-items: center;
                }

                .select2-container--default .select2-selection--single .select2-selection__rendered {
                    line-height: 1.6;
                    padding: 0;
                }

                .select2-container--default .select2-selection--single .select2-selection__arrow {
                    height: calc(2.375rem + 2px);
                    right: .35rem;
                }

                .select2-dropdown {
                    border: 1px solid rgba(15, 23, 42, .12);
                    border-radius: 12px;
                    box-shadow: 0 16px 30px rgba(15, 23, 42, .12);
                    overflow: hidden;
                }

                .select2-search--dropdown .select2-search__field {
                    border-radius: 10px;
                    border: 1px solid rgba(15, 23, 42, .12);
                }

                /* small helpers */
                .text-muted-2 {
                    color: var(--ui-muted) !important;
                }
            </style>
        @endpush

        {{-- هدر صفحه --}}
        <div class="card mb-4 ui-page-hero border-0 text-white">
            <div class="card-body">
                <div class="row gy-3 align-items-center">
                    <div class="col-md-6 d-flex align-items-center gap-3">
                        <div class="bg-white bg-opacity-10 rounded-4 p-3 border border-white border-opacity-10">
                            <span class="fs-3 fw-bold text-white">SDFR</span>
                        </div>
                        <div>
                            <h1 class="h4 mb-1 fw-bold">برنامه درسی هفتگی</h1>
                            <small class="text-white-50">به سبک SDFR</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="ui-chip d-flex flex-wrap align-items-center justify-content-md-end gap-4 px-4 py-3">
                            <div class="text-center">
                                <div class="text-white-50 small mb-1">نام و نام خانوادگی</div>
                                <div class="fw-bold fs-6 text-white">{{ $student->user->name ?? '---' }}</div>
                            </div>

                            <div class="vr d-none d-md-block text-white opacity-25"></div>

                            <div class="text-center">
                                <div class="text-white-50 small mb-1">مشاور</div>
                                <div class="fw-bold text-white">{{ $advisorName }}</div>
                            </div>

                            <div class="vr d-none d-md-block text-white opacity-25"></div>

                            <div class="text-center">
                                <div class="text-white-50 small mb-1">پشتیبان</div>
                                <div class="fw-bold text-white">{{ $supporterName }}</div>
                            </div>

                            <div class="vr d-none d-md-block text-white opacity-25"></div>

                            <div class="text-center">
                                <div class="text-white-50 small mb-1">تاریخ ارائه برنامه</div>
                                <div class="fw-bold text-white">
                                    {{ $start_date ? jdate($start_date)->format('Y/m/d') : '---' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- دسترسی سریع --}}
        <div class="card mb-4 ui-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="material-symbols-outlined text-primary">bolt</i>
                    <h5 class="mb-0">دسترسی سریع</h5>
                </div>
                <small class="text-muted-2">میانبرهای پرکاربرد</small>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-md-2">
                        <a href="{{ route('admin.student.reportStudent.detail', $student->id) }}"
                           class="quick-tile d-block p-3 text-center text-reset text-decoration-none h-100">
                            <i class="material-symbols-outlined d-block mb-2 text-primary">assessment</i>
                            <span class="small d-block fw-semibold">کارنامه وضعیت</span>
                            <span class="small text-muted-2">گزارش کلی</span>
                        </a>
                    </div>

                    <div class="col-6 col-md-2">
                        <a href="{{ route('admin.student.studySession.detail', $student->id) }}"
                           class="quick-tile d-block p-3 text-center text-reset text-decoration-none h-100">
                            <i class="material-symbols-outlined d-block mb-2 text-success">schedule</i>
                            <span class="small d-block fw-semibold">ساعت مطالعه</span>
                            <span class="small text-muted-2">جلسات مطالعه</span>
                        </a>
                    </div>

                    <div class="col-6 col-md-2">
                        <a href="{{ route('admin.student.reportDailyActivities.detail', $student->id) }}"
                           class="quick-tile d-block p-3 text-center text-reset text-decoration-none h-100">
                            <i class="material-symbols-outlined d-block mb-2 text-info">summarize</i>
                            <span class="small d-block fw-semibold">گزارش</span>
                            <span class="small text-muted-2">فعالیت روزانه</span>
                        </a>
                    </div>

                    <div class="col-6 col-md-2">
                        <a href="#"
                           class="quick-tile d-block p-3 text-center text-reset text-decoration-none h-100">
                            <i class="material-symbols-outlined d-block mb-2 text-warning">quiz</i>
                            <span class="small d-block fw-semibold">آزمون‌ها</span>
                            <span class="small text-muted-2">مرور آزمون</span>
                        </a>
                    </div>

                    <div class="col-12 col-md-4">
                        <a href="#"
                           class="quick-tile d-block p-3 text-center text-reset text-decoration-none h-100">
                            <i class="material-symbols-outlined d-block mb-2 text-secondary">category</i>
                            <span class="small d-block fw-semibold">طبقه‌بندی</span>
                            <span class="small text-muted-2">دسته‌بندی موارد</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- پیش‌جلسه‌های ثبت شده --}}
        @if($preSessions->count() > 0)
            @foreach($preSessions as $preSession)
                <div class="card mb-4 ui-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">پیش‌جلسه: {{ $preSession->title }}</h5>
                            <small class="text-muted-2">اطلاعات ثبت شده توسط دانش‌آموز</small>
                        </div>
                        <span
                            class="badge rounded-pill {{ $preSession->status === 'completed' ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2">
                            {{ $preSession->status_label }}
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">

                            {{-- امتحانات --}}
                            <div class="col-lg-6">
                                <div class="ui-card p-3 h-100">
                                    <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">
                                        <i class="material-symbols-outlined">quiz</i>
                                        امتحانات
                                        <span class="badge bg-primary-subtle text-primary rounded-pill">
                                            {{ $preSession->exams->count() }}
                                        </span>
                                    </h6>

                                    @if($preSession->exams->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm align-middle mb-0">
                                                <thead>
                                                <tr class="text-muted-2">
                                                    <th>درس</th>
                                                    <th>تعداد پارت</th>
                                                    <th>تاریخ آزمون</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($preSession->exams as $exam)
                                                    <tr>
                                                        <td class="fw-semibold">{{ $exam->subject }}</td>
                                                        <td>{{ $exam->part_count }} پارت</td>
                                                        <td>{{ jalali($exam->exam_date)->format('%d %B %Y') }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted-2 small mb-0">هیچ امتحانی ثبت نشده است</p>
                                    @endif
                                </div>
                            </div>

                            {{-- پرسش و پاسخ کلاسی --}}
                            <div class="col-lg-6">
                                <div class="ui-card p-3 h-100">
                                    <h6 class="fw-bold text-info mb-3 d-flex align-items-center gap-2">
                                        <i class="material-symbols-outlined">forum</i>
                                        پرسش و پاسخ کلاسی
                                        <span class="badge bg-info-subtle text-info rounded-pill">
                                            {{ $preSession->qas->count() }}
                                        </span>
                                    </h6>

                                    @if($preSession->qas->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm align-middle mb-0">
                                                <thead>
                                                <tr class="text-muted-2">
                                                    <th>درس</th>
                                                    <th>تعداد پارت</th>
                                                    <th>زمان هر پارت</th>
                                                    <th>تاریخ</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($preSession->qas as $qa)
                                                    <tr>
                                                        <td class="fw-semibold">{{ $qa->subject }}</td>
                                                        <td>{{ $qa->part_count }} پارت</td>
                                                        <td>{{ $qa->time_per_part }} دقیقه</td>
                                                        <td>{{ jalali($qa->qa_date)->format('%d %B %Y') }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted-2 small mb-0">هیچ پرسش و پاسخ کلاسی ثبت نشده است</p>
                                    @endif
                                </div>
                            </div>

                            {{-- تکالیف --}}
                            <div class="col-lg-6">
                                <div class="ui-card p-3 h-100">
                                    <h6 class="fw-bold text-warning mb-3 d-flex align-items-center gap-2">
                                        <i class="material-symbols-outlined">assignment</i>
                                        تکالیف
                                        <span class="badge bg-warning-subtle text-warning rounded-pill">
                                            {{ $preSession->assignments->count() }}
                                        </span>
                                    </h6>

                                    @if($preSession->assignments->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm align-middle mb-0">
                                                <thead>
                                                <tr class="text-muted-2">
                                                    <th>درس</th>
                                                    <th>تعداد پارت</th>
                                                    <th>مهلت انجام</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($preSession->assignments as $assignment)
                                                    <tr>
                                                        <td class="fw-semibold">{{ $assignment->subject }}</td>
                                                        <td>{{ $assignment->part_count }} پارت</td>
                                                        <td>{{ jalali($assignment->due_date)->format('%d %B %Y') }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted-2 small mb-0">هیچ تکلیفی ثبت نشده است</p>
                                    @endif
                                </div>
                            </div>

                            {{-- متفرقه --}}
                            <div class="col-lg-6">
                                <div class="ui-card p-3 h-100">
                                    <h6 class="fw-bold text-success mb-3 d-flex align-items-center gap-2">
                                        <i class="material-symbols-outlined">notes</i>
                                        متفرقه
                                    </h6>

                                    @if($preSession->miscellaneous)
                                        <div class="bg-body-tertiary rounded-3 p-3 border"
                                             style="border-color: var(--ui-border) !important;">
                                            <p class="mb-0"
                                               style="white-space: pre-line;">{{ $preSession->miscellaneous->description }}</p>
                                        </div>
                                    @else
                                        <p class="text-muted-2 small mb-0">اطلاعات متفرقه ثبت نشده است</p>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        {{-- جدول برنامه هفتگی --}}
        <div class="card mb-4 border-0 bg-body">
            <div class="d-flex align-items-center justify-content-between mb-2 px-1">
                <div class="d-flex align-items-center gap-2">
                    <i class="material-symbols-outlined text-primary">view_week</i>
                    <h5 class="mb-0">برنامه هفتگی</h5>
                </div>
                <small class="text-muted-2">برای افزودن روی + کلیک کنید</small>
            </div>

            <div class="weekly-wrap">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle weekly-table">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 90px;">روز</th>
                            <th class="text-center" style="width: 130px;">تاریخ</th>
                            <th class="text-center" style="width: 90px;">استراحت</th>
                            <th class="text-center" style="width: 110px;">ساعت</th>
                            @for($i = 1; $i <= 10; $i++)
                                <th class="text-center">پلن {{ $i }}</th>
                            @endfor
                            <th class="text-center" style="width: 95px;">تست روز</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($weekDays as $day)
                            <tr class="{{ $day['is_rest_day'] ? 'table-success bg-success bg-opacity-10' : '' }}">

                                {{-- روز --}}
                                <td class="text-center">
                                    <span
                                        class="badge {{ $day['is_rest_day'] ? 'bg-success' : 'bg-primary' }} rounded-pill px-3 py-2 fw-bold">
                                        {{ $day['name'] }}
                                    </span>
                                </td>

                                {{-- تاریخ --}}
                                <td class="text-center">
                                    <div class="fw-semibold text-muted-2">{{ $day['jalali_date'] }}</div>
                                </td>

                                {{-- استراحت --}}
                                <td class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               wire:click="toggleRestDay({{ $day['index'] }})"
                                               {{ $day['is_rest_day'] ? 'checked' : '' }}
                                               style="cursor: pointer;">
                                    </div>
                                    @if($day['is_rest_day'])
                                        <small class="text-success d-block mt-1 fw-semibold">روز استراحت</small>
                                    @endif
                                </td>

                                {{-- ساعت کل --}}
                                <td class="text-center">
                                    @if($day['is_rest_day'])
                                        <span class="text-success fw-bold">-</span>
                                    @else
                                        <div class="fw-bold">{{ $day['total_hours'] }}</div>
                                        <small class="text-muted-2 d-block">ساعت</small>
                                    @endif
                                </td>

                                {{-- پلن‌ها --}}
                                @for($i = 0; $i < 10; $i++)
                                    <td>
                                        @if($day['is_rest_day'])
                                            <div
                                                class="plan-box plan-rest d-flex align-items-center justify-content-center">
                                                @if($i === 0)
                                                    <div class="text-center">
                                                        <i class="material-symbols-outlined text-success"
                                                           style="font-size: 34px;">self_improvement</i>
                                                        <div class="small text-success fw-semibold mt-1">استراحت</div>
                                                    </div>
                                                @endif
                                            </div>

                                        @elseif(isset($day['parts'][$i]))
                                            @php $part = $day['parts'][$i]; @endphp

                                            {{-- رنگ‌بندی قبلی شما حفظ شده (color_class) --}}
                                            <div class="plan-box clickable p-3 {{ $part->color_class }}"
                                                 wire:click="editPart({{ $part->id }})">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="fw-bold small">{{ $part->lesson_name }}</span>
                                                    <span class="badge bg-body-tertiary text-body border text-xs">
                                                        {{ $part->lesson_type_label }} {{ $part->grade_label }}
                                                    </span>
                                                </div>

                                                <p class="small mb-2 text-muted-2">
                                                    {{ Str::limit($part->description, 55) }}
                                                </p>

                                                <div class="d-flex flex-wrap gap-2 small text-muted-2">
                                                    <span class="d-flex align-items-center gap-1">
                                                        <i class="material-symbols-outlined" style="font-size: 14px;">schedule</i>
                                                        {{ $part->duration_minutes }} دقیقه
                                                    </span>

                                                    @if($part->test_count)
                                                        <span class="d-flex align-items-center gap-1">
                                                            <i class="material-symbols-outlined"
                                                               style="font-size: 14px;">quiz</i>
                                                            {{ $part->test_count }} تست
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                        @else
                                            <div
                                                class="plan-box plan-empty d-flex align-items-center justify-content-center clickable"
                                                wire:click="openPartModal({{ $day['index'] }})">
                                                <div class="text-center">
                                                    <i class="material-symbols-outlined text-muted-2">add</i>
                                                    <div class="small text-muted-2 mt-1">افزودن</div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                @endfor

                                {{-- تست روز --}}
                                <td class="text-center">
                                    @if($day['is_rest_day'])
                                        <span class="badge bg-success-subtle text-success fw-bold">-</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning fw-bold">
                                            {{ $day['total_tests'] }}
                                        </span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- آمار و نمودارها --}}
        @if($weeklyProgram && $weeklyProgram->parts->count() > 0)
            <div class="card mb-4 ui-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="material-symbols-outlined text-info">analytics</i>
                        <h5 class="mb-0">خلاصه اطلاعات جزئی برنامه</h5>
                    </div>
                    <small class="text-muted-2">آمار کلی هفته</small>
                </div>

                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="rounded-4 p-4 border bg-primary bg-opacity-10">
                                <p class="small text-muted-2 mb-1">جمع کل ساعت مطالعه</p>
                                <p class="fs-3 fw-bold text-primary mb-0">{{ $weeklyProgram->total_hours }}</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="rounded-4 p-4 border bg-warning bg-opacity-10">
                                <p class="small text-muted-2 mb-1">جمع کل تعداد تست</p>
                                <p class="fs-3 fw-bold text-warning mb-0">{{ $weeklyProgram->total_tests }}</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="rounded-4 p-4 border bg-success bg-opacity-10">
                                <p class="small text-muted-2 mb-1">تعداد کل پارت‌ها</p>
                                <p class="fs-3 fw-bold text-success mb-0">{{ $weeklyProgram->total_parts }}</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="rounded-4 p-4 border bg-secondary bg-opacity-10">
                                <p class="small text-muted-2 mb-1">تعداد پلن‌های درسی</p>
                                <p class="fs-3 fw-bold text-secondary mb-0">{{ $weeklyProgram->total_plans }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6 col-md-2">
                            <div class="rounded-4 p-3 text-center bg-body-tertiary border">
                                <div class="fs-3 fw-bold text-primary">{{ $weeklyProgram->test_parts_count }}</div>
                                <div class="small text-muted-2 mt-1">پارت تستی</div>
                            </div>
                        </div>

                        <div class="col-6 col-md-2">
                            <div class="rounded-4 p-3 text-center bg-body-tertiary border">
                                <div
                                    class="fs-3 fw-bold text-warning">{{ $weeklyProgram->descriptive_parts_count }}</div>
                                <div class="small text-muted-2 mt-1">پارت تشریحی</div>
                            </div>
                        </div>

                        <div class="col-6 col-md-2">
                            <div class="rounded-4 p-3 text-center bg-body-tertiary border">
                                <div class="fs-3 fw-bold text-secondary">{{ $weeklyProgram->video_parts_count }}</div>
                                <div class="small text-muted-2 mt-1">پارت ویدئو</div>
                            </div>
                        </div>

                        <div class="col-6 col-md-2">
                            <div class="rounded-4 p-3 text-center bg-body-tertiary border">
                                <div class="fs-3 fw-bold text-success">{{ $weeklyProgram->grade_10_parts_count }}</div>
                                <div class="small text-muted-2 mt-1">پارت دهم</div>
                            </div>
                        </div>

                        <div class="col-6 col-md-2">
                            <div class="rounded-4 p-3 text-center bg-body-tertiary border">
                                <div class="fs-3 fw-bold text-danger">{{ $weeklyProgram->grade_11_parts_count }}</div>
                                <div class="small text-muted-2 mt-1">پارت یازدهم</div>
                            </div>
                        </div>

                        <div class="col-6 col-md-2">
                            <div class="rounded-4 p-3 text-center bg-body-tertiary border">
                                <div class="fs-3 fw-bold text-info">{{ $weeklyProgram->grade_12_parts_count }}</div>
                                <div class="small text-muted-2 mt-1">پارت دوازدهم</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endif

        {{-- دکمه ذخیره / بازگشت --}}
        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('admin.advising-sessions') }}" class="btn btn-outline-secondary">
                بازگشت
            </a>

            <button wire:click="finalSave" class="btn btn-primary">
                <span wire:loading.remove>ذخیره برنامه</span>
                <span wire:loading>در حال ذخیره...</span>
            </button>
        </div>
    </div>

    {{-- Modal اضافه کردن / ویرایش پارت --}}
    @if($showPartModal)
        <div class="modal fade show d-block" tabindex="-1"
             style="background: rgba(2, 6, 23, 0.60); backdrop-filter: blur(4px);">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header text-white"
                         style="background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 40%, #0ea5e9 100%);">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">{{ $editingPartId ? 'edit' : 'add_circle' }}</i>
                            {{ $editingPartId ? 'ویرایش پارت' : 'افزودن پارت جدید' }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closePartModal"></button>
                    </div>

                    <div class="modal-body">
                        {{-- جستجوی سریع --}}
                        <div class="mb-3 position-relative">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                <i class="material-symbols-outlined text-primary" style="font-size: 20px;">search</i>
                                جستجوی سریع
                            </label>
                            <div class="position-relative">
                                <input type="text"
                                       wire:model.live.debounce.300ms="globalSearch"
                                       class="form-control pe-5"
                                       placeholder="نام درس، فصل یا مبحث را جستجو کنید..."
                                       autocomplete="off">
                                <div wire:loading wire:target="globalSearch"
                                     class="position-absolute top-50 translate-middle-y"
                                     style="left: 12px;">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                </div>
                            </div>
                            @if(count($globalSearchResults) > 0)
                                <div class="list-group position-absolute w-100 mt-1 shadow-lg rounded-3 overflow-auto border"
                                     style="z-index: 1060; max-height: 280px;">
                                    @foreach($globalSearchResults as $index => $result)
                                        <button type="button"
                                                wire:click="selectGlobalResult({{ $index }})"
                                                class="list-group-item list-group-item-action py-2 px-3 d-flex align-items-center gap-2">
                                            @if($result['type'] === 'topic')
                                                <span class="badge bg-success-subtle text-success small">مبحث</span>
                                            @elseif($result['type'] === 'chapter')
                                                <span class="badge bg-info-subtle text-info small">فصل</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning small">درس</span>
                                            @endif
                                            <span class="small text-truncate">{{ $result['label'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                            <small class="text-muted-2">با جستجو تمام فیلدها خودکار پر می‌شوند</small>
                        </div>

                        <hr class="mb-3 mt-1">

                        {{-- انتخاب دوره تحصیلی و پایه --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined text-primary"
                                       style="font-size: 20px;">school</i>
                                    دوره تحصیلی <span class="text-danger">*</span>
                                    <span wire:loading wire:target="partForm.education_level_id">
                                        <span class="spinner-border spinner-border-sm text-primary"></span>
                                    </span>
                                </label>
                                <div wire:ignore>
                                    <select id="education-level-select"
                                            class="form-select select2-modal @error('partForm.education_level_id') is-invalid @enderror">
                                        <option value="">انتخاب کنید</option>
                                        @foreach($educationLevels as $level)
                                            <option
                                                value="{{ $level->id }}" {{ $partForm['education_level_id'] == $level->id ? 'selected' : '' }}>
                                                {{ $level->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('partForm.education_level_id')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined text-success"
                                       style="font-size: 20px;">stairs</i>
                                    پایه تحصیلی <span class="text-danger">*</span>
                                    <span wire:loading wire:target="partForm.cc_grade_id">
                                        <span class="spinner-border spinner-border-sm text-success"></span>
                                    </span>
                                </label>
                                <div wire:ignore>
                                    <select id="grade-select"
                                            class="form-select select2-modal @error('partForm.cc_grade_id') is-invalid @enderror"
                                        {{ empty($grades) ? 'disabled' : '' }}>
                                        <option
                                            value="">{{ empty($grades) ? 'ابتدا دوره را انتخاب کنید' : 'انتخاب کنید' }}</option>
                                        @foreach($grades as $grade)
                                            <option
                                                value="{{ $grade->id }}" {{ $partForm['cc_grade_id'] == $grade->id ? 'selected' : '' }}>
                                                {{ $grade->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('partForm.cc_grade_id')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- رشته و درس --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-4" wire:ignore.self id="field-select-wrapper" style="{{ !(count($fields) > 0 && $partForm['cc_grade_id']) ? 'display:none;' : '' }}">
                                <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                    رشته
                                    <span wire:loading wire:target="partForm.cc_field_id">
                                        <span class="spinner-border spinner-border-sm text-secondary"></span>
                                    </span>
                                </label>
                                <div wire:ignore>
                                    <select id="field-select" class="form-select select2-modal">
                                        <option value="">بدون رشته</option>
                                        @foreach($fields as $field)
                                            <option
                                                value="{{ $field->id }}" {{ $partForm['cc_field_id'] == $field->id ? 'selected' : '' }}>
                                                {{ $field->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="text-muted-2">برای متوسطه اول خالی بگذارید</small>
                            </div>

                            <div class="{{ count($fields) > 0 && $partForm['cc_grade_id'] ? 'col-md-8' : 'col-12' }}">
                                <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined text-warning" style="font-size: 20px;">book</i>
                                    درس <span class="text-danger">*</span>
                                    <span wire:loading wire:target="partForm.cc_subject_id">
                                        <span class="spinner-border spinner-border-sm text-warning"></span>
                                    </span>
                                </label>
                                <div wire:ignore>
                                    <select id="subject-select"
                                            class="form-select select2-modal @error('partForm.cc_subject_id') is-invalid @enderror"
                                        {{ empty($subjects) ? 'disabled' : '' }}>
                                        <option
                                            value="">{{ empty($subjects) ? 'ابتدا پایه را انتخاب کنید' : 'انتخاب کنید' }}</option>
                                        @foreach($subjects as $subject)
                                            <option
                                                value="{{ $subject->id }}" {{ $partForm['cc_subject_id'] == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                                ({{ $subject->type === 'general' ? 'عمومی' : 'تخصصی' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('partForm.cc_subject_id')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- فصل و مبحث --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined text-info" style="font-size: 20px;">bookmark</i>
                                    فصل
                                    <span wire:loading wire:target="partForm.cc_chapter_id">
                                        <span class="spinner-border spinner-border-sm text-info"></span>
                                    </span>
                                </label>
                                <div wire:ignore>
                                    <select id="chapter-select"
                                            class="form-select select2-modal"
                                        {{ empty($chapters) ? 'disabled' : '' }}>
                                        <option
                                            value="">{{ empty($chapters) ? 'ابتدا درس را انتخاب کنید' : 'انتخاب کنید' }}</option>
                                        @foreach($chapters as $chapter)
                                            <option
                                                value="{{ $chapter->id }}" {{ $partForm['cc_chapter_id'] == $chapter->id ? 'selected' : '' }}>
                                                {{ $chapter->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined text-secondary"
                                       style="font-size: 20px;">topic</i>
                                    مبحث
                                    <span wire:loading wire:target="partForm.cc_topic_id">
                                        <span class="spinner-border spinner-border-sm text-secondary"></span>
                                    </span>
                                </label>
                                <div wire:ignore>
                                    <select id="topic-select"
                                            class="form-select select2-modal"
                                        {{ empty($topics) ? 'disabled' : '' }}>
                                        <option
                                            value="">{{ empty($topics) ? 'ابتدا فصل را انتخاب کنید' : 'انتخاب کنید' }}</option>
                                        @foreach($topics as $topic)
                                            <option
                                                value="{{ $topic->id }}" {{ $partForm['cc_topic_id'] == $topic->id ? 'selected' : '' }}>
                                                {{ $topic->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- توضیحات --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                <i class="material-symbols-outlined text-secondary"
                                   style="font-size: 20px;">description</i>
                                توضیحات پارت
                            </label>
                            <textarea wire:model="partForm.description"
                                      rows="2"
                                      class="form-control"
                                      placeholder="مسیر انتخاب شده یا توضیحات دلخواه"></textarea>
                        </div>

                        {{-- نوع پارت، مدت زمان، تعداد تست --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined text-danger"
                                       style="font-size: 20px;">category</i>
                                    نوع پارت <span class="text-danger">*</span>
                                </label>
                                <select wire:model.live="partForm.part_type" class="form-select">
                                    <option value="descriptive">تشریحی</option>
                                    <option value="test">تستی</option>
                                    <option value="video">ویدئو</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined text-primary"
                                       style="font-size: 20px;">schedule</i>
                                    مدت زمان (دقیقه) <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       min="1"
                                       wire:model="partForm.duration_minutes"
                                       class="form-control @error('partForm.duration_minutes') is-invalid @enderror">
                                @error('partForm.duration_minutes')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($partForm['part_type'] === 'test')
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                        <i class="material-symbols-outlined text-warning"
                                           style="font-size: 20px;">quiz</i>
                                        تعداد تست <span class="text-danger">*</span>
                                    </label>
                                    <input type="number"
                                           min="1"
                                           wire:model="partForm.test_count"
                                           class="form-control @error('partForm.test_count') is-invalid @enderror"
                                           placeholder="تعداد تست را وارد کنید">
                                    @error('partForm.test_count')
                                    <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer bg-body-tertiary">
                        @if($editingPartId)
                            <button type="button"
                                    class="btn btn-outline-danger"
                                    wire:click="deletePart({{ $editingPartId }})"
                                    wire:confirm="آیا از حذف این پارت اطمینان دارید؟">
                                حذف
                            </button>
                        @endif

                        <button type="button" class="btn btn-outline-secondary" wire:click="closePartModal">
                            انصراف
                        </button>

                        <button type="button" class="btn btn-primary" wire:click="savePart">
                            <span wire:loading.remove wire:target="savePart">ذخیره</span>
                            <span wire:loading wire:target="savePart">در حال ذخیره...</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

    {{-- Rest Day Confirmation Modal --}}
    @if($showRestDayConfirmModal)
        <div class="modal fade show d-block" tabindex="-1"
             style="background: rgba(2, 6, 23, 0.60); backdrop-filter: blur(4px);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">warning</i>
                            تایید روز استراحت
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                                wire:click="closeRestDayConfirmModal"></button>
                    </div>

                    <div class="modal-body text-center py-4">
                        <div class="mb-3">
                            <i class="material-symbols-outlined text-warning"
                               style="font-size: 64px;">self_improvement</i>
                        </div>
                        <h5 class="mb-3">آیا مطمئن هستید؟</h5>
                        <p class="text-muted-2 mb-0">
                            این روز <strong class="text-danger">{{ $partsCountForRestDay }}</strong> پارت دارد.
                            <br>
                            با تایید، تمام پارت‌های این روز حذف شده و روز به عنوان
                            <span class="text-success fw-bold">استراحت</span> ثبت می‌شود.
                        </p>
                    </div>

                    <div class="modal-footer justify-content-center gap-2 bg-body-tertiary">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeRestDayConfirmModal">
                            انصراف
                        </button>
                        <button type="button" class="btn btn-success" wire:click="confirmRestDay">
                            <i class="material-symbols-outlined" style="font-size: 18px;">check</i>
                            تایید و ثبت استراحت
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('script')
        <script>
            document.addEventListener('livewire:init', () => {
                const select2Config = {
                    dir: "rtl",
                    language: "fa",
                    allowClear: true,
                    width: '100%'
                };

                const selectMappings = {
                    'education-level-select': 'partForm.education_level_id',
                    'grade-select': 'partForm.cc_grade_id',
                    'field-select': 'partForm.cc_field_id',
                    'subject-select': 'partForm.cc_subject_id',
                    'chapter-select': 'partForm.cc_chapter_id',
                    'topic-select': 'partForm.cc_topic_id'
                };

                function destroySelect2(selectId) {
                    const $el = $('#' + selectId);
                    if ($el.length && $el.hasClass('select2-hidden-accessible')) {
                        $el.off('change').select2('destroy');
                    }
                }

                function initSingleSelect2(selectId) {
                    const $el = $('#' + selectId);
                    if (!$el.length) return;

                    const $modal = $el.closest('.modal-content');
                    if (!$modal.length) return;

                    destroySelect2(selectId);

                    $el.select2({
                        ...select2Config,
                        dropdownParent: $modal,
                        placeholder: $el.find('option:first').text()
                    });

                    $el.on('change', function () {
                        const val = $(this).val() || '';
                        const prop = selectMappings[selectId];
                        if (prop) {
                        @this.set(prop, val)
                            ;
                        }
                    });
                }

                function initAllSelect2() {
                    Object.keys(selectMappings).forEach(id => initSingleSelect2(id));
                }

                function destroyAllSelect2() {
                    Object.keys(selectMappings).forEach(id => destroySelect2(id));
                }

                function rebuildSelect2(selectId, options, selectedValue, disabled) {
                    const $el = $('#' + selectId);
                    if (!$el.length) return;

                    const $modal = $el.closest('.modal-content');
                    if (!$modal.length) return;

                    destroySelect2(selectId);

                    $el.empty();
                    options.forEach(opt => {
                        $el.append(new Option(opt.text, String(opt.value), false, String(opt.value) === String(selectedValue)));
                    });

                    $el.prop('disabled', !!disabled);

                    $el.select2({
                        ...select2Config,
                        dropdownParent: $modal,
                        placeholder: options.length > 0 ? options[0].text : ''
                    });

                    if (selectedValue) {
                        $el.val(String(selectedValue)).trigger('change.select2');
                    }

                    $el.on('change', function () {
                        const val = $(this).val() || '';
                        const prop = selectMappings[selectId];
                        if (prop) {
                        @this.set(prop, val)
                            ;
                        }
                    });
                }

                // Modal opened - initialize all selects
                Livewire.on('modal-opened', () => {
                    setTimeout(initAllSelect2, 250);
                });

                // Modal closed - destroy all selects
                Livewire.on('modal-closed', () => {
                    destroyAllSelect2();
                });

                // Cascading select update from PHP
                Livewire.on('select2-update', (params) => {
                    const data = Array.isArray(params) ? params[0] : params;
                    if (!data || !data.id) return;

                    setTimeout(() => {
                        rebuildSelect2(data.id, data.options || [], data.selected || '', data.disabled || false);
                    }, 50);
                });
            });
        </script>
    @endpush
</div>
