<div>
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
                min-height: 120px;
                border-radius: 14px;
                border: 1px solid var(--ui-border);
                transition: transform .12s ease, box-shadow .12s ease, border-color .12s ease;
                overflow: visible;
            }

            /* Exam day parts should auto-size */
            .plan-box.border-danger,
            .plan-box.border-warning {
                min-height: 120px;
                height: auto;
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

            /* اسکرول افقی و عمودی */
            .weekly-scroll {
                overflow-x: auto;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                white-space: nowrap;
            }

            /* جدول عرض داینامیک بر اساس تعداد ستون */
            .weekly-table {
                min-width: 2200px;
                table-layout: fixed;
            }

            /* سلول‌ها باکس رو خرد نکنن */
            .weekly-table td,
            .weekly-table th {
                white-space: nowrap;
                vertical-align: top;
            }

            /* هر ستون پلن یک عرض مشخص داشته باشه */
            .weekly-table th:nth-child(n+7):nth-child(-n+30),
            .weekly-table td:nth-child(n+7):nth-child(-n+30) {
                width: 210px;
                min-width: 210px;
            }

            /* باکس داخل سلول کامل جا بگیره */
            .plan-box {
                width: 100%;
                min-width: 200px;
            }

            /* Cross-day drag-drop styles */
            .drag-over-day {
                background: rgba(37, 99, 235, .08) !important;
                outline: 2px dashed rgba(37, 99, 235, .4);
                outline-offset: -2px;
            }

            .drag-swap-target .plan-box {
                outline: 2px solid rgba(234, 88, 12, .6) !important;
                outline-offset: 2px;
                background: rgba(234, 88, 12, .05) !important;
            }

            /* Registered badge */
            .registered-badge {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 2px 8px;
                border-radius: 6px;
                font-size: 11px;
                font-weight: 600;
            }

            .registered-badge.registered {
                background: rgba(22, 163, 74, .1);
                color: #16a34a;
                border: 1px solid rgba(22, 163, 74, .2);
            }

            [data-bs-theme="dark"] .registered-badge.registered {
                background: rgba(22, 163, 74, .2);
                color: #4ade80;

            }

            /* Drag handle hover */
            .drag-handle:hover {
                color: var(--bs-primary) !important;
                cursor: grab;
            }

            .drag-handle:active {
                cursor: grabbing;
            }

            /* Sortable ghost/chosen states */
            .sortable-ghost-cell {
                opacity: 0.35;
                background: rgba(37, 99, 235, .06) !important;
                border: 2px dashed rgba(37, 99, 235, .4) !important;
            }

            .sortable-chosen-cell .plan-box {
                box-shadow: 0 8px 24px rgba(37, 99, 235, .25) !important;
                border-color: rgba(37, 99, 235, .5) !important;
                transform: scale(1.02);
            }

        </style>
    @endpush
    <div class="container-xxl flex-grow-1 container-p-y bg-body text-body" dir="rtl">

        {{-- D1: Modal پیش‌نمایش روزخوانی/پیش‌خوانی هفتگی --}}
        @if($showWeeklyReadingsPreview)
            <div class="modal fade show d-block" tabindex="-1"
                 style="background: rgba(2, 6, 23, 0.60); backdrop-filter: blur(4px);">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content shadow-lg border-0">
                        <div class="modal-header text-white"
                             style="background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%);">
                            <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                                <i class="material-symbols-outlined">auto_fix_high</i>
                                پیش‌نمایش روزخوانی و پیش‌خوانی هفتگی
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                    wire:click="closeWeeklyReadingsPreview"></button>
                        </div>

                        <div class="modal-body">
                            @if(count($weeklyReadingsPreview) > 0)
                                <div class="alert alert-info d-flex align-items-center gap-2 mb-3">
                                    <i class="material-symbols-outlined">info</i>
                                    <span class="small">
                                    @if(collect($weeklyReadingsPreview)->where('duration_minutes', 0)->count() > 0)
                                            <strong>توجه:</strong> برخی پارت‌ها تایم ۰ دارند. بعد از ثبت، تایم آنها را
                                            تنظیم کنید.
                                        @else
                                            تایم‌ها بر اساس آخرین جلسه مشاوره تنظیم شده‌اند.
                                        @endif
                                </span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead>
                                        <tr class="text-muted-2">
                                            <th>#</th>
                                            <th>نوع</th>
                                            <th>درس</th>
                                            <th class="text-center">روز</th>
                                            <th class="text-center">تاریخ</th>
                                            <th class="text-center">دقیقه</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($weeklyReadingsPreview as $idx => $item)
                                            <tr>
                                                <td class="text-center">{{ $idx + 1 }}</td>
                                                <td>
                                                    @if($item['type'] === 'daily')
                                                        <span
                                                            class="badge bg-primary-subtle text-primary">روزخوانی</span>
                                                    @else
                                                        <span class="badge bg-info-subtle text-info">پیش‌خوانی</span>
                                                    @endif
                                                </td>
                                                <td class="fw-semibold">{{ $item['subject'] }}</td>
                                                <td class="text-center"><span
                                                        class="badge bg-body-tertiary text-body border rounded-pill">{{ $item['day_name'] }}</span>
                                                </td>
                                                <td class="text-center">{{ $item['jalali_date'] }}</td>
                                                <td class="text-center">
                                                    <input type="number"
                                                           wire:model.lazy="weeklyReadingsPreview.{{ $idx }}.duration_minutes"
                                                           class="form-control form-control-sm text-center mx-auto {{ $item['duration_minutes'] == 0 ? 'border-warning' : '' }}"
                                                           min="0" max="120" style="width: 75px;">
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted-2 text-center">موردی برای نمایش وجود ندارد.</p>
                            @endif
                        </div>

                        <div class="modal-footer bg-body-tertiary">
                            <button type="button" class="btn btn-outline-secondary"
                                    wire:click="closeWeeklyReadingsPreview">
                                انصراف
                            </button>
                            @if(count($weeklyReadingsPreview) > 0)
                                <button type="button" class="btn btn-success" wire:click="applyWeeklyReadings">
                                <span wire:loading.remove wire:target="applyWeeklyReadings">
                                    <i class="material-symbols-outlined" style="font-size: 18px;">check</i>
                                    ثبت در برنامه
                                </span>
                                    <span wire:loading wire:target="applyWeeklyReadings">در حال ثبت...</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- D2: Modal انتخاب روز برای امتحان کلاسی --}}
        @if($showExamDaySelectModal)
            <div class="modal fade show d-block" tabindex="-1"
                 style="background: rgba(2, 6, 23, 0.60); backdrop-filter: blur(4px);">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow-lg border-0">
                        <div class="modal-header text-white"
                             style="background: linear-gradient(135deg, #d97706 0%, #f59e0b 50%, #fbbf24 100%);">
                            <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                                <i class="material-symbols-outlined">event</i>
                                انتخاب روز برای امتحان کلاسی
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                    wire:click="closeExamDaySelectModal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <p class="fw-semibold mb-1">درس: {{ $examDaySelectData['subject'] ?? '' }}</p>
                                <p class="small text-muted-2 mb-0">
                                    {{ $examDaySelectData['part_count'] ?? 0 }} پارت -
                                    {{ $examDaySelectData['time_per_part'] ?? 0 }} دقیقه هر پارت

                                    (تاریخ امتحان: {{ jalali($examDaySelectData['exam_date'])->format('%d %B %Y') }} )
                                </p>
                            </div>

                            <label class="form-label fw-semibold">روز مورد نظر را انتخاب کنید:</label>

                            <div class="d-flex align-items-center gap-2 mb-2 small">
                                <span class="d-inline-flex align-items-center gap-1"><span
                                        class="badge bg-success rounded-pill px-2">&nbsp;</span> قبل یا روز امتحان</span>
                                <span class="d-inline-flex align-items-center gap-1"><span
                                        class="badge bg-danger rounded-pill px-2">&nbsp;</span> بعد از امتحان</span>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                @if(isset($weekDays))
                                    @php $examDateCarbon = \Carbon\Carbon::parse($examDaySelectData['exam_date'] ?? null)->startOfDay(); @endphp
                                    @foreach($weekDays as $day)
                                        @if(!$day['is_rest_day'])
                                            @php
                                                $dayDateCarbon = $day['date']->copy()->startOfDay();
                                                $isBeforeOrOnExam = $dayDateCarbon->lte($examDateCarbon);
                                            @endphp
                                            <button type="button"
                                                    wire:click="$set('examDaySelectTarget', {{ $day['index'] }})"
                                                    class="btn btn-sm {{ $examDaySelectTarget === $day['index'] ? 'btn-primary' : ($isBeforeOrOnExam ? 'btn-outline-success' : 'btn-outline-danger') }}">
                                                {{ $day['name'] }} ({{ $day['jalali_date'] }})
                                            </button>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="modal-footer bg-body-tertiary">
                            <button type="button" class="btn btn-outline-secondary"
                                    wire:click="closeExamDaySelectModal">
                                انصراف
                            </button>
                            <button type="button" class="btn btn-warning text-white" wire:click="applyExamToDay"
                                {{ $examDaySelectTarget === null ? 'disabled' : '' }}>
                            <span wire:loading.remove wire:target="applyExamToDay">
                                <i class="material-symbols-outlined" style="font-size: 18px;">check</i>
                                ثبت در برنامه
                            </span>
                                <span wire:loading wire:target="applyExamToDay">در حال ثبت...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Modal آرشیو جلسه قبلی - برنامه درسی --}}
        @if($showPrevProgramModal)
            <div class="modal fade show d-block" tabindex="-1"
                 style="background: rgba(2, 6, 23, 0.65); backdrop-filter: blur(4px); z-index: 1080;">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content shadow-lg border-0">
                        <div class="modal-header text-white"
                             style="background: linear-gradient(135deg, #d97706 0%, #f59e0b 50%, #fbbf24 100%);">
                            <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                                <i class="material-symbols-outlined">history</i>
                                برنامه درسی جلسه قبلی
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                    wire:click="closePrevProgramModal"></button>
                        </div>

                        <div class="modal-body">
                            @if(count($prevSessionParts) > 0)
                                <div class="alert alert-warning d-flex align-items-center gap-2 mb-3 small">
                                    <i class="material-symbols-outlined">info</i>
                                    <span>برای کپی کردن پارت به برنامه جدید، روی دکمه «انتخاب برای کپی» کلیک کنید، سپس روز مقصد را انتخاب کرده و ثبت کنید.</span>
                                </div>

                                @if($copyingPrevPartId !== null)
                                    <div class="card mb-3 border-warning bg-warning bg-opacity-10">
                                        <div class="card-body py-2">
                                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                                <div class="fw-semibold text-warning d-flex align-items-center gap-1">
                                                    <i class="material-symbols-outlined">content_copy</i>
                                                    @php $copyingPart = collect($prevSessionParts)->firstWhere('id', $copyingPrevPartId); @endphp
                                                    در حال کپی: {{ $copyingPart['lesson_name'] ?? '' }}
                                                </div>
                                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                                    <label class="form-label mb-0 small fw-semibold">روز مقصد:</label>
                                                    <select wire:model.live="copyPrevPartTargetDay"
                                                            class="form-select form-select-sm" style="width:auto;">
                                                        <option value="">انتخاب روز</option>
                                                        @foreach($weekDays as $day)
                                                            @if(!$day['is_rest_day'])
                                                                <option value="{{ $day['index'] }}">{{ $day['name'] }}
                                                                    ({{ $day['jalali_date'] }})
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <button wire:click="copyPrevPartToProgram"
                                                            class="btn btn-sm btn-warning text-white"
                                                        {{ $copyPrevPartTargetDay === null ? 'disabled' : '' }}>
                                                        <span wire:loading.remove wire:target="copyPrevPartToProgram">
                                                            <i class="material-symbols-outlined"
                                                               style="font-size:16px;">check</i>
                                                            ثبت
                                                        </span>
                                                        <span wire:loading
                                                              wire:target="copyPrevPartToProgram">...</span>
                                                    </button>
                                                    <button wire:click="selectPrevPartForCopy({{ $copyingPrevPartId }})"
                                                            class="btn btn-sm btn-outline-secondary">
                                                        لغو
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-body-tertiary">
                                        <tr>
                                            <th class="text-center" style="width:40px;">#</th>
                                            <th>درس</th>
                                            <th class="text-center">روز</th>
                                            <th class="text-center">تایم</th>
                                            <th class="text-center">نوع</th>
                                            <th class="text-center">ستاره گزارش</th>
                                            <th class="text-center">میانگین</th>
                                            <th class="text-center">عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($prevSessionParts as $idx => $pPart)
                                            <tr class="{{ $copyingPrevPartId === $pPart['id'] ? 'table-warning' : '' }}">
                                                <td class="text-center text-muted-2">{{ $idx + 1 }}</td>
                                                <td>
                                                    <div class="fw-semibold">{{ $pPart['lesson_name'] }}</div>
                                                    @if($pPart['description'])
                                                        <div
                                                            class="small text-muted-2">{{ Str::limit($pPart['description'], 50) }}</div>
                                                    @endif
                                                    @if($pPart['source_type'] && $pPart['source_type'] !== 'normal')
                                                        <span
                                                            class="badge bg-{{ $pPart['source_type_color'] }}-subtle text-{{ $pPart['source_type_color'] }} small mt-1">{{ $pPart['source_type_label'] }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-body-tertiary text-body border rounded-pill small">{{ $pPart['day_name'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="small fw-semibold">{{ $pPart['duration_minutes'] }} دقیقه</span>
                                                    @if($pPart['test_count'])
                                                        <div class="small text-muted-2">{{ $pPart['test_count'] }}تست
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-body-tertiary text-body border small">{{ $pPart['part_type_label'] }}</span>
                                                    @if($pPart['grade_label'])
                                                        <div
                                                            class="small text-muted-2">{{ $pPart['grade_label'] }}</div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($pPart['report_rating'] !== null)
                                                        <div
                                                            class="d-flex align-items-center justify-content-center gap-1">
                                                            @for($s = 1; $s <= 10; $s++)
                                                                <i class="material-symbols-outlined"
                                                                   style="font-size:14px;color:{{ $s <= $pPart['report_rating'] ? '#f59e0b' : 'var(--bs-secondary-color)' }};">
                                                                    {{ $s <= $pPart['report_rating'] ? 'star' : 'star_border' }}
                                                                </i>
                                                            @endfor
                                                        </div>
                                                        <div class="small text-muted-2">{{ $pPart['report_rating'] }}
                                                            /10
                                                        </div>
                                                    @else
                                                        <span class="text-muted-2 small">ثبت نشده</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($pPart['avg_label'])
                                                        <span
                                                            class="badge bg-{{ $pPart['avg_color'] }}-subtle text-{{ $pPart['avg_color'] }} fw-bold px-2 py-1">
                                                            {{ $pPart['avg_label'] }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted-2 small">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button wire:click="selectPrevPartForCopy({{ $pPart['id'] }})"
                                                            class="btn btn-sm {{ $copyingPrevPartId === $pPart['id'] ? 'btn-warning text-white' : 'btn-outline-warning' }} d-inline-flex align-items-center gap-1">
                                                        <i class="material-symbols-outlined" style="font-size:15px;">
                                                            {{ $copyingPrevPartId === $pPart['id'] ? 'check_circle' : 'content_copy' }}
                                                        </i>
                                                        {{ $copyingPrevPartId === $pPart['id'] ? 'انتخاب شد' : 'کپی' }}
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="material-symbols-outlined text-muted-2" style="font-size:48px;">inbox</i>
                                    <p class="text-muted-2 mt-2">برنامه‌ای برای جلسه قبلی ثبت نشده است</p>
                                </div>
                            @endif
                        </div>

                        <div class="modal-footer bg-body-tertiary">
                            <button type="button" class="btn btn-outline-secondary"
                                    wire:click="closePrevProgramModal">
                                بستن
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

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
        <div class="card mb-4 ui-card" x-data="{
            iframeModal: false,
            iframeUrl: '',
            iframeTitle: '',
            iframeColor: '#2563eb',
            openIframe(url, title, color) {
                this.iframeUrl = url;
                this.iframeTitle = title;
                this.iframeColor = color || '#2563eb';
                this.iframeModal = true;
            }
        }">
            {{-- Iframe Modal --}}
            <template x-teleport="body">
                <div x-show="iframeModal" x-cloak
                     style="position:fixed;inset:0;z-index:1080;background:rgba(2,6,23,.65);backdrop-filter:blur(4px);"
                     @keydown.escape.window="iframeModal=false">
                    <div
                        style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;padding:16px;">
                        <div
                            style="width:100%;max-width:1200px;height:90vh;border-radius:18px;overflow:hidden;box-shadow:0 24px 60px rgba(0,0,0,.35);display:flex;flex-direction:column;background:var(--bs-body-bg);">
                            <div class="d-flex align-items-center justify-content-between px-4 py-3 text-white"
                                 :style="'background:'+iframeColor">
                                <h5 class="mb-0 d-flex align-items-center gap-2 fw-bold">
                                    <i class="material-symbols-outlined">open_in_new</i>
                                    <span x-text="iframeTitle"></span>
                                </h5>
                                <div class="d-flex align-items-center gap-2">
                                    <a :href="iframeUrl" target="_blank"
                                       class="btn btn-sm btn-light btn-outline-light opacity-75 d-flex align-items-center gap-1">
                                        <i class="material-symbols-outlined" style="font-size:16px;">open_in_new</i>
                                        باز کردن در تب جدید
                                    </a>
                                    <button @click="iframeModal=false" class="btn-close btn-close-white"></button>
                                </div>
                            </div>
                            <div style="flex:1;overflow:hidden;">
                                <iframe :src="iframeUrl" style="width:100%;height:100%;border:0;"
                                        loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
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
                        <a href="#"
                           @click.prevent="openIframe('{{ route('admin.student.studySession.detail', $student->id) }}', 'ساعت مطالعه', 'linear-gradient(135deg,#059669,#10b981)')"
                           class="quick-tile d-block p-3 text-center text-reset text-decoration-none h-100">
                            <i class="material-symbols-outlined d-block mb-2 text-success">schedule</i>
                            <span class="small d-block fw-semibold">ساعت مطالعه</span>
                            <span class="small text-muted-2">جلسات مطالعه</span>
                        </a>
                    </div>

                    <div class="col-6 col-md-2">
                        <a href="#"
                           @click.prevent="openIframe('{{ route('admin.student.reportDailyActivities.detail', $student->id) }}', 'گزارش فعالیت روزانه', 'linear-gradient(135deg,#0891b2,#06b6d4)')"
                           class="quick-tile d-block p-3 text-center text-reset text-decoration-none h-100">
                            <i class="material-symbols-outlined d-block mb-2 text-info">summarize</i>
                            <span class="small d-block fw-semibold">گزارش</span>
                            <span class="small text-muted-2">فعالیت روزانه</span>
                        </a>
                    </div>

                    <div class="col-6 col-md-2">
                        <a href="#"
                           @click.prevent="openIframe('{{ route('admin.typed-exams.index') }}', 'آزمون‌ها', 'linear-gradient(135deg,#d97706,#f59e0b)')"
                           class="quick-tile d-block p-3 text-center text-reset text-decoration-none h-100">
                            <i class="material-symbols-outlined d-block mb-2 text-warning">quiz</i>
                            <span class="small d-block fw-semibold">آزمون‌ها</span>
                            <span class="small text-muted-2">مرور آزمون</span>
                        </a>
                    </div>

                    <div class="col-6 col-md-2">
                        <a href="#" wire:click.prevent="openClassificationModal"
                           class="quick-tile d-block p-3 text-center text-reset text-decoration-none h-100">
                            <i class="material-symbols-outlined d-block mb-2 text-secondary">category</i>
                            <span class="small d-block fw-semibold">طبقه‌بندی</span>
                            <span class="small text-muted-2">دسته‌بندی موارد</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-2">
                        <a href="#" wire:click.prevent="openClassScheduleModal"
                           class="quick-tile d-block p-3 text-center text-reset text-decoration-none h-100">
                            <i class="material-symbols-outlined d-block mb-2 text-danger">menu_book</i>
                            <span class="small d-block fw-semibold">برنامه کلاسی</span>
                            <span class="small text-muted-2">مشاهده برنامه</span>
                        </a>
                    </div>
                </div>

                {{-- ارشیو جلسه قبلی --}}
                <hr class="my-3">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="material-symbols-outlined text-warning">history</i>
                    <h6 class="mb-0 fw-bold">ارشیو جلسه قبلی</h6>
                    <small class="text-muted-2">داده‌های جلسه برگزار شده قبلی</small>
                </div>
                <div class="row g-3">
                    <div class="col-6 col-md-4">
                        <a href="#" wire:click.prevent="openPrevProgramModal"
                           class="quick-tile d-block p-3 text-center text-reset text-decoration-none h-100"
                           style="border-color: rgba(234, 179, 8, .25);">
                            <i class="material-symbols-outlined d-block mb-2 text-warning">calendar_today</i>
                            <span class="small d-block fw-semibold">برنامه درسی</span>
                            <span class="small text-muted-2">جلسه قبلی</span>
                            <span wire:loading wire:target="openPrevProgramModal" class="d-block mt-1">
                                <span class="spinner-border spinner-border-sm text-warning"></span>
                            </span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="#"
                           @click.prevent="openIframe('{{ route('admin.student.reportDailyActivities.detail', $student->id) }}', 'گزارش فعالیت - جلسه قبلی', 'linear-gradient(135deg,#7c3aed,#a855f7)')"
                           class="quick-tile d-block p-3 text-center text-reset text-decoration-none h-100"
                           style="border-color: rgba(124, 58, 237, .25);">
                            <i class="material-symbols-outlined d-block mb-2 text-purple" style="color:#7c3aed;">summarize</i>
                            <span class="small d-block fw-semibold">گزارش</span>
                            <span class="small text-muted-2">جلسه قبلی</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="#"
                           @click.prevent="openIframe('{{ route('admin.student.studySession.detail', $student->id) }}', 'ساعت مطالعه - جلسه قبلی', 'linear-gradient(135deg,#059669,#10b981)')"
                           class="quick-tile d-block p-3 text-center text-reset text-decoration-none h-100"
                           style="border-color: rgba(5, 150, 105, .25);">
                            <i class="material-symbols-outlined d-block mb-2 text-success">schedule</i>
                            <span class="small d-block fw-semibold">ساعت مطالعه</span>
                            <span class="small text-muted-2">جلسه قبلی</span>
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
                                        <div class="weekly-scroll">
                                            <table class="table table-sm align-middle mb-0">
                                                <thead>
                                                <tr class="text-muted-2">
                                                    <th>درس</th>
                                                    <th>تعداد پارت</th>
                                                    <th>زمان هر پارت</th>

                                                    <th>تاریخ آزمون</th>
                                                    <th class="text-center">وضعیت</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($preSession->exams as $examIdx => $exam)
                                                    <tr>
                                                        <td class="fw-semibold">{{ $exam->subject }}</td>
                                                        <td>{{ $exam->part_count }} پارت</td>
                                                        <td>{{ $exam->time_per_part }} دقیقه</td>
                                                        <td>{{ jalali($exam->exam_date)->format('%d %B %Y') }}</td>
                                                        <td class="text-center">
                                                            @if($this->isExamRegistered($examIdx))
                                                                <span class="registered-badge registered">
                                                                    <i class="material-symbols-outlined"
                                                                       style="font-size: 14px;">check_circle</i>
                                                                    ثبت شده
                                                                </span>
                                                                <button wire:click="revertExamParts({{ $examIdx }})"
                                                                        wire:confirm="آیا از حذف پارت‌های این امتحان از برنامه اطمینان دارید؟"
                                                                        class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 mt-1">
                                                                    <i class="material-symbols-outlined"
                                                                       style="font-size: 14px;">undo</i>
                                                                    بازگرداندن
                                                                </button>
                                                            @else
                                                                <button wire:click="openExamDaySelect({{ $examIdx }})"
                                                                        class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                                                    <i class="material-symbols-outlined"
                                                                       style="font-size: 14px;">event</i>
                                                                    انتخاب روز
                                                                </button>
                                                            @endif
                                                        </td>
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
                                        <div class="weekly-scroll">
                                            <table class="table table-sm align-middle mb-0">
                                                <thead>
                                                <tr class="text-muted-2">
                                                    <th>درس</th>
                                                    <th>تعداد پارت</th>
                                                    <th>زمان هر پارت</th>
                                                    <th>تاریخ</th>
                                                    <th class="text-center">وضعیت</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($preSession->qas as $qaIdx => $qa)
                                                    <tr>
                                                        <td class="fw-semibold">{{ $qa->subject }}</td>
                                                        <td>{{ $qa->part_count }} پارت</td>
                                                        <td>{{ $qa->time_per_part }} دقیقه</td>
                                                        <td>{{ jalali($qa->qa_date)->format('%d %B %Y') }}</td>
                                                        <td class="text-center">
                                                            @if($this->isQaRegistered($qaIdx))
                                                                <span class="registered-badge registered">
                                                                    <i class="material-symbols-outlined"
                                                                       style="font-size: 14px;">check_circle</i>
                                                                    ثبت شده
                                                                </span>
                                                                <button wire:click="revertQaParts({{ $qaIdx }})"
                                                                        wire:confirm="آیا از حذف پارت‌های پرسش و پاسخ از برنامه اطمینان دارید؟"
                                                                        class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 mt-1">
                                                                    <i class="material-symbols-outlined"
                                                                       style="font-size: 14px;">undo</i>
                                                                    بازگرداندن
                                                                </button>
                                                            @else
                                                                <button wire:click="previewQaDistribution"
                                                                        class="btn btn-sm btn-outline-info d-inline-flex align-items-center gap-1">
                                                                    <i class="material-symbols-outlined"
                                                                       style="font-size: 14px;">auto_fix_high</i>
                                                                    ثبت در برنامه
                                                                </button>
                                                            @endif
                                                        </td>
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
                                        <div class="weekly-scroll">
                                            <table class="table table-sm align-middle mb-0">
                                                <thead>
                                                <tr class="text-muted-2">
                                                    <th>درس</th>
                                                    <th>تعداد پارت</th>
                                                    <th>زمان هر پارت</th>
                                                    <th>مهلت انجام</th>
                                                    <th class="text-center">وضعیت</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($preSession->assignments as $assignmentIdx => $assignment)
                                                    <tr>
                                                        <td class="fw-semibold">{{ $assignment->subject }}</td>
                                                        <td>{{ $assignment->part_count }} پارت</td>
                                                        <td>{{ $assignment->time_per_part }} دقیقه</td>
                                                        <td>{{ jalali($assignment->due_date)->format('%d %B %Y') }}</td>
                                                        <td class="text-center">
                                                            @if($this->isAssignmentRegistered($assignmentIdx))
                                                                <span class="registered-badge registered">
                                                                    <i class="material-symbols-outlined"
                                                                       style="font-size: 14px;">check_circle</i>
                                                                    ثبت شده
                                                                </span>
                                                                <button
                                                                    wire:click="revertAssignmentParts({{ $assignmentIdx }})"
                                                                    wire:confirm="آیا از حذف پارت‌های تکلیف از برنامه اطمینان دارید؟"
                                                                    class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 mt-1">
                                                                    <i class="material-symbols-outlined"
                                                                       style="font-size: 14px;">undo</i>
                                                                    بازگرداندن
                                                                </button>
                                                            @else
                                                                <button wire:click="previewHomeworkDistribution"
                                                                        class="btn btn-sm btn-outline-warning d-inline-flex align-items-center gap-1">
                                                                    <i class="material-symbols-outlined"
                                                                       style="font-size: 14px;">auto_fix_high</i>
                                                                    ثبت در برنامه
                                                                </button>
                                                            @endif
                                                        </td>
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
                            {{-- پارت در خواستی --}}
                            <div class="col-12">
                                <div class="ui-card p-3">
                                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color:#ea580c;">
                                        <i class="material-symbols-outlined">playlist_add</i>
                                        پارت در خواستی
                                        <span class="badge rounded-pill px-3 py-2" style="background:rgba(234,88,12,.12);color:#ea580c;">
                                            {{ $preSession->requestedParts->count() }}
                                        </span>
                                    </h6>

                                    @if($preSession->requestedParts->count() > 0)
                                        <div class="weekly-scroll">
                                            <table class="table table-sm align-middle mb-0">
                                                <thead>
                                                <tr class="text-muted-2">
                                                    <th>درس</th>
                                                    <th>تعداد پارت</th>
                                                    <th>زمان هر پارت</th>
                                                    <th>توضیحات</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($preSession->requestedParts as $rp)
                                                    <tr>
                                                        <td class="fw-semibold">{{ $rp->subject }}</td>
                                                        <td>{{ $rp->part_count }} پارت</td>
                                                        <td>{{ $rp->time_per_part }} دقیقه</td>
                                                        <td class="text-muted-2">{{ $rp->description ?? '—' }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted-2 small mb-0">هیچ پارت در خواستی ثبت نشده است</p>
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
            <div class="d-flex align-items-center justify-content-between mb-2 px-1 flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="material-symbols-outlined text-primary">view_week</i>
                    <h5 class="mb-0">برنامه هفتگی</h5>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <small class="text-muted-2">برای افزودن روی + کلیک کنید</small>
                    <button wire:click="togglePartSelectMode"
                            class="btn btn-sm {{ $partSelectMode ? 'btn-warning' : 'btn-outline-secondary' }} d-flex align-items-center gap-1">
                        <i class="material-symbols-outlined"
                           style="font-size:16px;">{{ $partSelectMode ? 'close' : 'check_box' }}</i>
                        {{ $partSelectMode ? 'خروج از انتخاب' : 'انتخاب و کپی پارت' }}
                    </button>
                </div>
            </div>
            {{-- Floating copy panel --}}
            @if($partSelectMode && count($selectedPartIds) > 0)
                <div class="card border-warning border-2 mb-3 mx-1">
                    <div class="card-body py-2 d-flex flex-wrap align-items-center gap-3">
                        <span class="fw-semibold text-warning d-flex align-items-center gap-1">
                            <i class="material-symbols-outlined" style="font-size:18px;">content_copy</i>
                            {{ count($selectedPartIds) }} پارت انتخاب شده
                        </span>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <label class="form-label mb-0 small fw-semibold">روز مقصد:</label>
                            <select wire:model.live="copyTargetDay" class="form-select form-select-sm"
                                    style="width:auto;">
                                <option value="">انتخاب روز</option>
                                @foreach($weekDays as $day)
                                    @if(!$day['is_rest_day'] && !$day['is_exam_day'])
                                        <option value="{{ $day['index'] }}">{{ $day['name'] }}({{ $day['jalali_date'] }}
                                            )
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <button wire:click="copySelectedParts"
                                    class="btn btn-sm btn-warning text-white"
                                {{ $copyTargetDay === null ? 'disabled' : '' }}>
                                <span wire:loading.remove wire:target="copySelectedParts">
                                    <i class="material-symbols-outlined" style="font-size:16px;">content_copy</i>
                                    کپی در روز انتخابی
                                </span>
                                <span wire:loading wire:target="copySelectedParts">در حال کپی...</span>
                            </button>
                            <button wire:click="clearPartSelection" class="btn btn-sm btn-outline-secondary">
                                لغو انتخاب
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="weekly-wrap">
                <div class="weekly-scroll">
                    <table class="table table-bordered table-hover align-middle weekly-table">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 90px;">روز</th>
                            <th class="text-center" style="width: 130px;">تاریخ</th>
                            <th class="text-center" style="width: 95px;">تست روز</th>
                            <th class="text-center" style="width: 90px;">استراحت</th>
                            <th class="text-center" style="width: 100px;">آزمون جامع</th>

                            <th class="text-center" style="width: 110px;">ساعت</th>
                            @php
                                $maxPartsInWeek = 10;
                                if(isset($weekDays)) {
                                    foreach($weekDays as $wd) {
                                        $c = count($wd['parts']);
     if($c >= $maxPartsInWeek) $maxPartsInWeek = $c + 2;
                                    }
                                }
                                $maxPartsInWeek = max($maxPartsInWeek, 10);
                            @endphp
                            @for($i = 1; $i <= $maxPartsInWeek; $i++)
                                <th class="text-center">پلن {{ $i }}</th>
                            @endfor

                        </tr>
                        </thead>

                        <tbody>
                        @foreach($weekDays as $day)
                            <tr class="{{ $day['is_rest_day'] ? 'table-success bg-success bg-opacity-10' : ($day['is_exam_day'] ? 'table-danger bg-danger bg-opacity-10' : '') }}"
                                data-day-index="{{ $day['index'] }}"
                                data-sortable-row="1">
                                {{-- روز --}}
                                <td class="text-center">
                                    <span
                                        class="badge {{ $day['is_rest_day'] ? 'bg-success' : ($day['is_exam_day'] ? 'bg-danger' : 'bg-primary') }} rounded-pill px-3 py-2 fw-bold">
                                        {{ $day['name'] }}
                                    </span>
                                </td>

                                {{-- تاریخ --}}
                                <td class="text-center">
                                    <div class="fw-semibold text-muted-2">{{ $day['jalali_date'] }}</div>
                                </td>

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
                                {{-- آزمون جامع --}}
                                <td class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               wire:click="toggleExamDay({{ $day['index'] }})"
                                               {{ $day['is_exam_day'] ? 'checked' : '' }}
                                               {{ $day['is_rest_day'] ? 'disabled' : '' }}
                                               style="cursor: pointer;">
                                    </div>
                                    @if($day['is_exam_day'])
                                        <small class="text-danger d-block mt-1 fw-semibold">آزمون جامع</small>
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
                                @for($i = 0; $i < $maxPartsInWeek; $i++)
                                    <td class="{{ (!$day['is_rest_day'] && !$day['is_exam_day'] && isset($day['parts'][$i])) ? 'plan-part-cell' : '' }}"
                                        data-part-id="{{ (!$day['is_rest_day'] && !$day['is_exam_day'] && isset($day['parts'][$i])) ? $day['parts'][$i]->id : '' }}">
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
                                        @elseif($day['is_exam_day'])
                                            {{-- Comprehensive exam day mode --}}
                                            @if(isset($day['parts'][$i]))
                                                @php $part = $day['parts'][$i]; @endphp
                                                <div
                                                    class="plan-box clickable p-3 {{ $part->part_type === 'exam_analysis' ? 'bg-warning bg-opacity-10 border-warning' : 'bg-danger bg-opacity-10 border-danger' }}"
                                                    wire:click="editExamPart({{ $part->id }})">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="fw-bold small">{{ $part->lesson_name }}</span>
                                                        <span
                                                            class="badge {{ $part->part_type === 'exam_analysis' ? 'bg-warning' : 'bg-danger' }} text-white text-xs">
                                                            {{ $part->part_type_label }}
                                                        </span>
                                                    </div>
                                                    @if($part->source_type && $part->source_type !== 'normal')
                                                        <div class="mb-1">
                                                            <span
                                                                class="badge bg-{{ $part->source_type_color }}-subtle text-{{ $part->source_type_color }} text-xs">
                                                                {{ $part->source_type_label }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    <p class="small mb-2 text-muted-2">{{ Str::limit($part->description, 55) }}</p>
                                                    <div class="d-flex flex-wrap gap-2 small text-muted-2">
                                                        <span class="d-flex align-items-center gap-1">
                                                            <i class="material-symbols-outlined"
                                                               style="font-size: 14px;">schedule</i>
                                                            {{ $part->duration_minutes }} دقیقه
                                                        </span>
                                                    </div>
                                                    <div class="mt-2">
                                                        <button class="btn btn-sm btn-outline-danger"
                                                                wire:click.stop="deleteExamPart({{ $part->id }})"
                                                                wire:confirm="آیا از حذف این آزمون اطمینان دارید؟">
                                                            <i class="material-symbols-outlined"
                                                               style="font-size: 14px;">delete</i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @elseif($i === count($day['parts']))
                                                <div
                                                    class="plan-box plan-empty d-flex align-items-center justify-content-center clickable"
                                                    wire:click="openExamPartModal({{ $day['index'] }})">
                                                    <div class="text-center">
                                                        <i class="material-symbols-outlined text-danger">add</i>
                                                        <div class="small text-danger mt-1">افزودن آزمون</div>
                                                    </div>
                                                </div>
                                            @else
                                                <div
                                                    class="plan-box plan-empty d-flex align-items-center justify-content-center"></div>
                                            @endif

                                        @elseif(isset($day['parts'][$i]))
                                            @php $part = $day['parts'][$i]; @endphp

                                            @if($partSelectMode)
                                                {{-- حالت انتخاب --}}
                                                @php $isSelected = in_array($part->id, $selectedPartIds); @endphp
                                                <div
                                                    class="plan-box p-3 {{ $part->color_class ?? '' }} {{ $isSelected ? 'border-warning border-2' : '' }}"
                                                    wire:click="togglePartSelection({{ $part->id }})"
                                                    style="cursor:pointer;position:relative;">
                                                    <div class="position-absolute top-0 end-0 p-1">
                                                        <i class="material-symbols-outlined {{ $isSelected ? 'text-warning' : 'text-muted-2' }}"
                                                           style="font-size:20px;">
                                                            {{ $isSelected ? 'check_box' : 'check_box_outline_blank' }}
                                                        </i>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-1 mb-1">
                                                        <span class="fw-bold small">{{ $part->lesson_name }}</span>
                                                    </div>
                                                    <div class="d-flex flex-wrap gap-2 small text-muted-2">
                                                        <span class="d-flex align-items-center gap-1">
                                                            <i class="material-symbols-outlined"
                                                               style="font-size:14px;">schedule</i>
                                                            {{ $part->duration_minutes }} دقیقه
                                                        </span>
                                                    </div>
                                                </div>
                                            @else
                                                {{-- رنگ‌بندی قبلی شما حفظ شده (color_class) --}}
                                                <div class="plan-box clickable p-3 {{ $part->color_class ?? '' }}"
                                                     wire:click="editPart({{ $part->id }})">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <div class="d-flex align-items-center gap-1">
                                                            <i class="material-symbols-outlined drag-handle text-muted-2"
                                                               style="font-size:16px;cursor:grab;user-select:none;"
                                                               title="برای جابه‌جایی بکشید"
                                                               wire:click.stop>drag_handle</i>
                                                            <span class="fw-bold small">{{ $part->lesson_name }}</span>
                                                        </div>
                                                        <span class="badge bg-body-tertiary text-body border text-xs">
                                                            {{ $part->part_type_label }} {{ $part->grade_label }}
                                                        </span>
                                                    </div>
                                                    @if($part->source_type && $part->source_type !== 'normal')
                                                        <div class="mb-1">
                                                            <span
                                                                class="badge bg-{{ $part->source_type_color }}-subtle text-{{ $part->source_type_color }} text-xs">
                                                                {{ $part->source_type_label }}
                                                            </span>
                                                        </div>
                                                    @endif
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
                                                    @endif
                                                </div>

                                                @elseif($i === count($day['parts']))
                                                    {{-- Part C: Add New Part button at the end of existing parts --}}
                                                    <div
                                                        class="plan-box plan-empty d-flex align-items-center justify-content-center clickable"
                                                        wire:click="openPartModal({{ $day['index'] }})">
                                                        <div class="text-center">
                                                            <i class="material-symbols-outlined text-primary">add_circle</i>
                                                            <div class="small text-primary mt-1">افزودن پارت</div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div
                                                        class="plan-box plan-empty d-flex align-items-center justify-content-center">
                                                    </div>
                                                @endif
                                    </td>
                                @endfor


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
                                <div
                                    class="list-group position-absolute w-100 mt-1 shadow-lg rounded-3 overflow-auto border"
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
                            <div class="col-md-4" wire:ignore.self id="field-select-wrapper"
                                 style="{{ !(count($fields) > 0 && $partForm['cc_grade_id']) ? 'display:none;' : '' }}">
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
                                    <option value="topic_exam">آزمون مبحثی</option>

                                </select>
                            </div>

                            <div class="col-md-4" x-data="{
                                    totalMinutes: $wire.entangle('partForm.duration_minutes'),
                                    hours: 0,
                                    minutes: 0,
                                    init() {
                                        let val = parseInt(this.totalMinutes) || 0;
                                        this.hours = Math.floor(val / 60);
                                        this.minutes = val % 60;
                                        this.$watch('totalMinutes', (v) => {
                                            let val = parseInt(v) || 0;
                                            this.hours = Math.floor(val / 60);
                                            this.minutes = val % 60;
                                        });
                                    },
                                    update() {
                                        let h = Math.min(Math.max(parseInt(this.hours) || 0, 0), 24);
                                        let m = Math.min(Math.max(parseInt(this.minutes) || 0, 0), 59);
                                        this.hours = h;
                                        this.minutes = m;
                                        this.totalMinutes = (h * 60) + m;
                                    }
                                }" x-init="init()">
                                <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined text-primary"
                                       style="font-size: 20px;">schedule</i>
                                    مدت زمان <span class="text-danger">*</span>
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-fill position-relative">
                                        <input type="number" min="0" max="24"
                                               x-model.number="hours"
                                               @input="update()"
                                               class="form-control text-center @error('partForm.duration_minutes') is-invalid @enderror"
                                               placeholder="0">
                                        <small class="position-absolute top-50 translate-middle-y text-muted"
                                               style="left:8px;font-size:10px;">ساعت</small>
                                    </div>
                                    <span class="fw-bold text-muted fs-5">:</span>
                                    <div class="flex-fill position-relative">
                                        <input type="number" min="0" max="59"
                                               x-model.number="minutes"
                                               @input="update()"
                                               class="form-control text-center"
                                               placeholder="0">
                                        <small class="position-absolute top-50 translate-middle-y text-muted"
                                               style="left:8px;font-size:10px;">دقیقه</small>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1" x-show="totalMinutes > 0">
                                    مجموع: <span x-text="totalMinutes"></span> دقیقه
                                </small>
                                @error('partForm.duration_minutes')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($partForm['part_type'] === 'test' || $partForm['part_type'] === 'topic_exam')
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
    {{-- Modal مشاهده برنامه کلاسی دانش‌آموز --}}
    @if($showClassScheduleModal && $classScheduleData['schedule'])
        <div class="modal fade show d-block" tabindex="-1"
             style="background: rgba(2, 6, 23, 0.60); backdrop-filter: blur(4px);">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header text-white"
                         style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 40%, #c084fc 100%);">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">menu_book</i>
                            برنامه کلاسی دانش‌آموز
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                                wire:click="closeClassScheduleModal"></button>
                    </div>

                    <div class="modal-body">
                        {{-- جدول برنامه کلاسی --}}
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead>
                                <tr>
                                    <th class="text-center"
                                        style="background: linear-gradient(90deg, #7c3aed, #a855f7); color: #fff; width: 110px;">
                                        روز
                                    </th>
                                    @for($p = 1; $p <= 5; $p++)
                                        <th class="text-center"
                                            style="background: linear-gradient(90deg, #7c3aed, #a855f7); color: #fff;">
                                            پارت {{ $p }}</th>
                                    @endfor
                                </tr>
                                </thead>
                                <tbody>
                                @for($d = 0; $d < 7; $d++)
                                    <tr>
                                        <td class="text-center">
                                            <span
                                                class="badge {{ in_array($d, [5, 6]) ? 'bg-secondary' : 'bg-primary' }} rounded-pill px-3 py-2 fw-bold">
                                                {{ \App\Models\ClassSchedule::getDayName($d) }}
                                            </span>
                                        </td>
                                        @for($p = 1; $p <= 5; $p++)
                                            @php
                                                $part = $classScheduleData['days'][$d]['parts']->where('part_order', $p)->first();
                                            @endphp
                                            <td class="text-center">
                                                @if($part)
                                                    <span
                                                        class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                                        {{ $part->lesson_name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted-2">-</span>
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endfor
                                </tbody>
                            </table>
                        </div>

                        {{-- دکمه‌های پیش‌خوانی و روزخوانی --}}
                        <hr>
                        <div class="mb-3">
                            <p class="small text-muted-2 mb-2 d-flex align-items-center gap-1">
                                <i class="material-symbols-outlined" style="font-size:16px;">auto_fix_high</i>
                                پیش‌خوانی و روزخوانی بر اساس برنامه کلاسی و تایم‌های جلسه قبلی محاسبه می‌شوند.
                            </p>
                            {{-- پیش‌نمایش با جدول قابل ویرایش --}}
                            @if(empty($weeklyReadingsPreview))
                                <div class="d-flex justify-content-center">
                                    <button wire:click="previewWeeklyReadings"
                                            class="btn btn-outline-secondary d-flex align-items-center gap-2">
                                        <i class="material-symbols-outlined" style="font-size:18px;">refresh</i>
                                        بارگذاری پیش‌نمایش
                                        <span wire:loading wire:target="previewWeeklyReadings">
                                            <span class="spinner-border spinner-border-sm"></span>
                                        </span>
                                    </button>
                                </div>
                            @else
                                {{-- جدول ویرایش تایم‌ها --}}
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h6 class="fw-bold mb-0 d-flex align-items-center gap-1">
                                        <i class="material-symbols-outlined text-success" style="font-size:18px;">edit_note</i>
                                        تایم‌های پیش‌خوانی و روزخوانی
                                    </h6>
                                    <button wire:click="previewWeeklyReadings"
                                            class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                                        <i class="material-symbols-outlined" style="font-size:15px;">refresh</i>
                                        بازنشانی
                                        <span wire:loading wire:target="previewWeeklyReadings"><span class="spinner-border spinner-border-sm"></span></span>
                                    </button>
                                </div>
                                <div class="alert alert-info small d-flex align-items-center gap-2 mb-2 py-2">
                                    <i class="material-symbols-outlined" style="font-size:16px;">info</i>
                                    تایم‌ها بر اساس آخرین جلسه بارگذاری شده‌اند. درس‌هایی که تایم نداشتند با ۰ نمایش داده می‌شوند. می‌توانید قبل از ثبت ویرایش کنید.
                                </div>
                                <div class="table-responsive mb-3" style="max-height:260px;overflow-y:auto;">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead class="sticky-top bg-body-tertiary">
                                        <tr class="text-muted-2">
                                            <th>نوع</th>
                                            <th>درس</th>
                                            <th class="text-center">روز</th>
                                            <th class="text-center">تاریخ</th>
                                            <th class="text-center" style="width:90px;">دقیقه</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($weeklyReadingsPreview as $idx => $item)
                                            <tr>
                                                <td>
                                                    @if($item['type'] === 'daily')
                                                        <span class="badge bg-primary-subtle text-primary">روزخوانی</span>
                                                    @else
                                                        <span class="badge bg-info-subtle text-info">پیش‌خوانی</span>
                                                    @endif
                                                </td>
                                                <td class="fw-semibold small">{{ $item['subject'] }}</td>
                                                <td class="text-center"><span class="badge bg-body-tertiary text-body border rounded-pill small">{{ $item['day_name'] }}</span></td>
                                                <td class="text-center small">{{ $item['jalali_date'] }}</td>
                                                <td class="text-center">
                                                    <input type="number"
                                                           wire:model.lazy="weeklyReadingsPreview.{{ $idx }}.duration_minutes"
                                                           class="form-control form-control-sm text-center mx-auto {{ $item['duration_minutes'] == 0 ? 'border-warning' : '' }}"
                                                           min="0" max="300" style="width:70px;">
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                    {{-- روزخوانی --}}
                                    @if($this->hasDailyReadings())
                                        <button wire:click="revertDailyReadings"
                                                wire:confirm="آیا از حذف تمام روزخوانی‌های برنامه اطمینان دارید؟"
                                                class="btn btn-outline-danger d-flex align-items-center gap-2">
                                            <span wire:loading.remove wire:target="revertDailyReadings">
                                                <i class="material-symbols-outlined" style="font-size:18px;">undo</i>
                                                بازگرداندن روزخوانی
                                            </span>
                                            <span wire:loading wire:target="revertDailyReadings">در حال حذف...</span>
                                        </button>
                                    @else
                                        <button wire:click="applyOnlyDailyReadings"
                                                class="btn btn-outline-primary d-flex align-items-center gap-2">
                                            <span wire:loading.remove wire:target="applyOnlyDailyReadings">
                                                <i class="material-symbols-outlined" style="font-size:18px;">today</i>
                                                افزودن روزخوانی
                                            </span>
                                            <span wire:loading wire:target="applyOnlyDailyReadings">در حال ثبت...</span>
                                        </button>
                                    @endif
                                    {{-- پیش‌خوانی --}}
                                    @if($this->hasPreReadings())
                                        <button wire:click="revertPreReadings"
                                                wire:confirm="آیا از حذف تمام پیش‌خوانی‌های برنامه اطمینان دارید؟"
                                                class="btn btn-outline-danger d-flex align-items-center gap-2">
                                            <span wire:loading.remove wire:target="revertPreReadings">
                                                <i class="material-symbols-outlined" style="font-size:18px;">undo</i>
                                                بازگرداندن پیش‌خوانی
                                            </span>
                                            <span wire:loading wire:target="revertPreReadings">در حال حذف...</span>
                                        </button>
                                    @else
                                        <button wire:click="applyOnlyPreReadings"
                                                class="btn btn-outline-info d-flex align-items-center gap-2">
                                            <span wire:loading.remove wire:target="applyOnlyPreReadings">
                                                <i class="material-symbols-outlined"
                                                   style="font-size:18px;">upcoming</i>
                                                افزودن پیش‌خوانی
                                            </span>
                                            <span wire:loading wire:target="applyOnlyPreReadings">در حال ثبت...</span>
                                        </button>
                                    @endif

                                    {{-- هر دو با هم --}}
                                    <button wire:click="applyWeeklyReadings"
                                            class="btn btn-outline-success d-flex align-items-center gap-2">
                                        <span wire:loading.remove wire:target="applyWeeklyReadings">
                                            <i class="material-symbols-outlined"
                                               style="font-size:18px;">auto_fix_high</i>
                                            افزودن هر دو
                                        </span>
                                        <span wire:loading wire:target="applyWeeklyReadings">در حال ثبت...</span>
                                    </button>
                                </div>

                                {{-- نمایش باکس‌های هر روز - پارت‌های ثبت شده --}}
                                @if($weeklyProgram)
                                    <hr>
                                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                                        <i class="material-symbols-outlined text-primary">view_week</i>
                                        روزخوانی و پیش‌خوانی ثبت‌شده در برنامه (به تفکیک روز)
                                    </h6>
                                    <div class="row g-2">
                                        @foreach($weekDays as $day)
                                            @if(!$day['is_rest_day'] && !$day['is_exam_day'])
                                                @php
                                                    $dayReadingParts = \App\Models\ProgramPart::where('weekly_program_id', $weeklyProgram->id)
                                                        ->where('day_of_week', $day['index'])
                                                        ->whereIn('source_type', ['daily_reading', 'pre_reading'])
                                                        ->orderBy('part_order')
                                                        ->get();
                                                @endphp
                                                <div class="col-md-6 col-lg-4 col-xl-3">
                                                    <div class="border rounded-3 p-2 h-100"
                                                         style="background: var(--bs-body-bg); border-color: var(--ui-border) !important;">
                                                        <div
                                                            class="fw-semibold small mb-2 d-flex align-items-center gap-1">
                                                            <span
                                                                class="badge bg-primary rounded-pill">{{ $day['name'] }}</span>
                                                            <span class="text-muted-2">{{ $day['jalali_date'] }}</span>
                                                        </div>
                                                        @if($dayReadingParts->isEmpty())
                                                            <p class="text-muted-2 small mb-0">ثبت نشده</p>
                                                        @else
                                                            @foreach($dayReadingParts as $rp)
                                                                <div
                                                                    class="d-flex align-items-center gap-1 mb-1 p-1 rounded-2"
                                                                    style="background: var(--bs-tertiary-bg);">
                                                                    @if($rp->source_type === 'daily_reading')
                                                                        <span
                                                                            class="badge bg-primary-subtle text-primary flex-shrink-0"
                                                                            style="font-size:10px;">روزخوانی</span>
                                                                    @else
                                                                        <span
                                                                            class="badge bg-info-subtle text-info flex-shrink-0"
                                                                            style="font-size:10px;">پیش‌خوانی</span>
                                                                    @endif
                                                                    <span
                                                                        class="small fw-semibold text-truncate flex-fill"
                                                                        title="{{ $rp->lesson_name }}">{{ $rp->lesson_name }}</span>
                                                                    <span class="small text-muted-2 flex-shrink-0">{{ $rp->duration_minutes }}دق</span>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer bg-body-tertiary">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeClassScheduleModal">
                            بستن
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal برنامه کلاسی وجود ندارد --}}
    @if($showNoScheduleModal)
        <div class="modal fade show d-block" tabindex="-1"
             style="background: rgba(2, 6, 23, 0.60); backdrop-filter: blur(4px);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">warning</i>
                            برنامه کلاسی
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                                wire:click="closeNoScheduleModal"></button>
                    </div>

                    <div class="modal-body text-center py-4">
                        <div class="mb-3">
                            <i class="material-symbols-outlined text-warning" style="font-size: 64px;">event_busy</i>
                        </div>
                        <h5 class="mb-3">برنامه کلاسی وجود ندارد</h5>
                        <p class="text-muted-2 mb-0">
                            دانش‌آموز هنوز برنامه کلاسی خود را آپلود نکرده است.
                        </p>
                    </div>

                    <div class="modal-footer justify-content-center gap-2 bg-body-tertiary">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeNoScheduleModal">
                            بستن
                        </button>
                        <button type="button" class="btn btn-warning text-white" wire:click="sendScheduleReminder">
                            <i class="material-symbols-outlined" style="font-size: 18px;">notifications_active</i>
                            اطلاع‌رسانی جهت ارسال برنامه کلاسی
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal ثبت روزخوانی / پیش‌خوانی --}}
    @if($showDailyReadingModal)
        <div class="modal fade show d-block" tabindex="-1"
             style="background: rgba(2, 6, 23, 0.60); backdrop-filter: blur(4px); z-index: 1060;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header text-white"
                         style="background: {{ $dailyReadingType === 'daily' ? 'linear-gradient(135deg, #1d4ed8, #2563eb)' : 'linear-gradient(135deg, #0891b2, #06b6d4)' }};">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">{{ $dailyReadingType === 'daily' ? 'today' : 'upcoming' }}</i>
                            {{ $editingDailyReadingPartId ? 'ویرایش' : 'ثبت' }}
                            {{ $dailyReadingType === 'daily' ? 'روزخوانی' : 'پیش‌خوانی' }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                                wire:click="closeDailyReadingModal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">درس</label>
                            <input type="text" class="form-control"
                                   value="{{ \App\Models\CcSubject::find($dailyReadingSubjectId)?->name ?? '' }}"
                                   disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">توضیحات</label>
                            <textarea wire:model="dailyReadingDescription" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="mb-3" x-data="{
                                totalMinutes: $wire.entangle('dailyReadingDuration'),
                                hours: 0,
                                minutes: 0,
                                init() {
                                    let val = parseInt(this.totalMinutes) || 0;
                                    this.hours = Math.floor(val / 60);
                                    this.minutes = val % 60;
                                    this.$watch('totalMinutes', (v) => {
                                        let val = parseInt(v) || 0;
                                        this.hours = Math.floor(val / 60);
                                        this.minutes = val % 60;
                                    });
                                },
                                update() {
                                    let h = Math.min(Math.max(parseInt(this.hours) || 0, 0), 24);
                                    let m = Math.min(Math.max(parseInt(this.minutes) || 0, 0), 59);
                                    this.hours = h;
                                    this.minutes = m;
                                    this.totalMinutes = (h * 60) + m;
                                }
                            }" x-init="init()">
                            <label class="form-label fw-semibold">مدت زمان</label>
                            <div class="d-flex align-items-center gap-2">
                                <div class="flex-fill position-relative">
                                    <input type="number" min="0" max="24"
                                           x-model.number="hours"
                                           @input="update()"
                                           class="form-control text-center"
                                           placeholder="0">
                                    <small class="position-absolute top-50 translate-middle-y text-muted"
                                           style="left:8px;font-size:10px;">ساعت</small>
                                </div>
                                <span class="fw-bold text-muted fs-5">:</span>
                                <div class="flex-fill position-relative">
                                    <input type="number" min="0" max="59"
                                           x-model.number="minutes"
                                           @input="update()"
                                           class="form-control text-center"
                                           placeholder="0">
                                    <small class="position-absolute top-50 translate-middle-y text-muted"
                                           style="left:8px;font-size:10px;">دقیقه</small>
                                </div>
                            </div>
                            <small class="text-muted-2 d-block mt-1">
                                <span x-show="totalMinutes > 0"> | مجموع: <span
                                        x-text="totalMinutes"></span> دقیقه</span>
                            </small>
                        </div>
                    </div>

                    <div class="modal-footer bg-body-tertiary">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeDailyReadingModal">
                            انصراف
                        </button>
                        <button type="button"
                                class="btn {{ $dailyReadingType === 'daily' ? 'btn-primary' : 'btn-info text-white' }}"
                                wire:click="saveDailyReading">
                            <span wire:loading.remove wire:target="saveDailyReading">ذخیره</span>
                            <span wire:loading wire:target="saveDailyReading">در حال ذخیره...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- Modal تایید آزمون جامع --}}
    @if($showExamDayConfirmModal)
        <div class="modal fade show d-block" tabindex="-1"
             style="background: rgba(2, 6, 23, 0.60); backdrop-filter: blur(4px);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">warning</i>
                            تایید آزمون جامع
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                                wire:click="closeExamDayConfirmModal"></button>
                    </div>

                    <div class="modal-body text-center py-4">
                        <div class="mb-3">
                            <i class="material-symbols-outlined text-danger"
                               style="font-size: 64px;">assignment</i>
                        </div>
                        <h5 class="mb-3">آیا مطمئن هستید؟</h5>
                        <p class="text-muted-2 mb-0">
                            این روز <strong class="text-danger">{{ $partsCountForExamDay }}</strong> پارت دارد.
                            <br>
                            با تایید، تمام پارت‌های این روز حذف شده و روز به عنوان
                            <span class="text-danger fw-bold">آزمون جامع</span> ثبت می‌شود.
                        </p>
                    </div>

                    <div class="modal-footer justify-content-center gap-2 bg-body-tertiary">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeExamDayConfirmModal">
                            انصراف
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="confirmExamDay">
                            <i class="material-symbols-outlined" style="font-size: 18px;">check</i>
                            تایید و ثبت آزمون جامع
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal افزودن/ویرایش آزمون جامع --}}
    @if($showExamPartModal)
        <div class="modal fade show d-block" tabindex="-1"
             style="background: rgba(2, 6, 23, 0.60); backdrop-filter: blur(4px);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header text-white"
                         style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 40%, #f87171 100%);">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">{{ $editingExamPartId ? 'edit' : 'add_circle' }}</i>
                            {{ $editingExamPartId ? 'ویرایش آزمون' : 'افزودن آزمون جامع' }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                                wire:click="closeExamPartModal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                <i class="material-symbols-outlined text-danger" style="font-size: 20px;">assignment</i>
                                نام آزمون <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   wire:model="examPartForm.exam_name"
                                   class="form-control @error('examPartForm.exam_name') is-invalid @enderror"
                                   placeholder="مثال: آزمون جامع ریاضی">
                            @error('examPartForm.exam_name')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" x-data="{
                                totalMinutes: $wire.entangle('examPartForm.duration_minutes'),
                                hours: 0,
                                minutes: 0,
                                init() {
                                    let val = parseInt(this.totalMinutes) || 0;
                                    this.hours = Math.floor(val / 60);
                                    this.minutes = val % 60;
                                    this.$watch('totalMinutes', (v) => {
                                        let val = parseInt(v) || 0;
                                        this.hours = Math.floor(val / 60);
                                        this.minutes = val % 60;
                                    });
                                },
                                update() {
                                    let h = Math.min(Math.max(parseInt(this.hours) || 0, 0), 24);
                                    let m = Math.min(Math.max(parseInt(this.minutes) || 0, 0), 59);
                                    this.hours = h;
                                    this.minutes = m;
                                    this.totalMinutes = (h * 60) + m;
                                }
                            }" x-init="init()">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                <i class="material-symbols-outlined text-primary" style="font-size: 20px;">schedule</i>
                                مدت زمان <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex align-items-center gap-2">
                                <div class="flex-fill position-relative">
                                    <input type="number" min="0" max="24"
                                           x-model.number="hours"
                                           @input="update()"
                                           class="form-control text-center @error('examPartForm.duration_minutes') is-invalid @enderror"
                                           placeholder="0">
                                    <small class="position-absolute top-50 translate-middle-y text-muted"
                                           style="left:8px;font-size:10px;">ساعت</small>
                                </div>
                                <span class="fw-bold text-muted fs-5">:</span>
                                <div class="flex-fill position-relative">
                                    <input type="number" min="0" max="59"
                                           x-model.number="minutes"
                                           @input="update()"
                                           class="form-control text-center"
                                           placeholder="0">
                                    <small class="position-absolute top-50 translate-middle-y text-muted"
                                           style="left:8px;font-size:10px;">دقیقه</small>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1" x-show="totalMinutes > 0">
                                مجموع: <span x-text="totalMinutes"></span> دقیقه
                            </small>
                            @error('examPartForm.duration_minutes')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                <i class="material-symbols-outlined text-secondary"
                                   style="font-size: 20px;">description</i>
                                توضیحات
                            </label>
                            <textarea wire:model="examPartForm.description"
                                      rows="3"
                                      class="form-control"
                                      placeholder="توضیحات آزمون..."></textarea>
                        </div>

                        <div class="alert alert-info small mb-0">
                            <i class="material-symbols-outlined" style="font-size: 16px;">info</i>
                            با ذخیره آزمون، یک پارت «تحلیل آزمون» نیز به صورت خودکار اضافه خواهد شد.
                        </div>
                    </div>

                    <div class="modal-footer bg-body-tertiary">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeExamPartModal">
                            انصراف
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="saveExamPart">
                            <span wire:loading.remove wire:target="saveExamPart">ذخیره</span>
                            <span wire:loading wire:target="saveExamPart">در حال ذخیره...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal توزیع تکالیف --}}
    @if($showDistributeHomeworkModal)
        <div class="modal fade show d-block" tabindex="-1"
             style="background: rgba(2, 6, 23, 0.60); backdrop-filter: blur(4px);">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header text-white"
                         style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 50%, #c084fc 100%);">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">assignment</i>
                            پیش‌نمایش توزیع تکالیف در برنامه
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                                wire:click="closeDistributionModal"></button>
                    </div>

                    <div class="modal-body">
                        @if(count($distributionPreview) > 0)
                            <div class="alert alert-info small">
                                <i class="material-symbols-outlined" style="font-size: 16px;">info</i>
                                پارت‌های زیر بر اساس قوانین توزیع تکالیف محاسبه شده‌اند. با کلیک روی «اعمال» در برنامه
                                ثبت می‌شوند.
                            </div>
                            <div class="weekly-scroll">
                                <table class="table table-sm table-bordered align-middle">
                                    <thead>
                                    <tr class="bg-body-tertiary">
                                        <th class="text-center">#</th>
                                        <th>درس</th>
                                        <th class="text-center">روز</th>
                                        <th class="text-center">تاریخ</th>
                                        <th class="text-center">مدت (دقیقه)</th>
                                        <th>توضیحات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($distributionPreview as $idx => $item)
                                        <tr>
                                            <td class="text-center">{{ $idx + 1 }}</td>
                                            <td class="fw-semibold">{{ $item['subject'] }}</td>
                                            <td class="text-center"><span
                                                    class="badge bg-primary rounded-pill">{{ $item['day_name'] }}</span>
                                            </td>
                                            <td class="text-center">{{ $item['jalali_date'] }}</td>
                                            <td class="text-center">{{ $item['duration_minutes'] }}</td>
                                            <td class="small">{{ $item['description'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted-2 text-center">موردی برای نمایش وجود ندارد.</p>
                        @endif
                    </div>

                    <div class="modal-footer bg-body-tertiary">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeDistributionModal">
                            انصراف
                        </button>
                        @if(count($distributionPreview) > 0)
                            <button type="button" class="btn btn-success" wire:click="applyDistribution">
                                <span wire:loading.remove wire:target="applyDistribution">
                                    <i class="material-symbols-outlined" style="font-size: 18px;">check</i>
                                    اعمال در برنامه
                                </span>
                                <span wire:loading wire:target="applyDistribution">در حال اعمال...</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal توزیع امتحانات --}}
    @if($showDistributeExamModal)
        <div class="modal fade show d-block" tabindex="-1"
             style="background: rgba(2, 6, 23, 0.60); backdrop-filter: blur(4px);">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header text-white"
                         style="background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 50%, #1d4ed8 100%);">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">school</i>
                            پیش‌نمایش توزیع امتحانات در برنامه
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                                wire:click="closeDistributionModal"></button>
                    </div>

                    <div class="modal-body">
                        @if(count($distributionPreview) > 0)
                            <div class="alert alert-info small">
                                <i class="material-symbols-outlined" style="font-size: 16px;">info</i>
                                پارت‌ها بر اساس تاریخ امتحان به‌صورت یکنواخت تا روز قبل امتحان توزیع شده‌اند.
                            </div>
                            <div class="weekly-scroll">
                                <table class="table table-sm table-bordered align-middle">
                                    <thead>
                                    <tr class="bg-body-tertiary">
                                        <th class="text-center">#</th>
                                        <th>درس</th>
                                        <th class="text-center">روز</th>
                                        <th class="text-center">تاریخ</th>
                                        <th class="text-center">مدت (دقیقه)</th>
                                        <th>توضیحات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($distributionPreview as $idx => $item)
                                        <tr>
                                            <td class="text-center">{{ $idx + 1 }}</td>
                                            <td class="fw-semibold">{{ $item['subject'] }}</td>
                                            <td class="text-center"><span
                                                    class="badge bg-info rounded-pill">{{ $item['day_name'] }}</span>
                                            </td>
                                            <td class="text-center">{{ $item['jalali_date'] }}</td>
                                            <td class="text-center">{{ $item['duration_minutes'] }}</td>
                                            <td class="small">{{ $item['description'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted-2 text-center">موردی برای نمایش وجود ندارد.</p>
                        @endif
                    </div>

                    <div class="modal-footer bg-body-tertiary">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeDistributionModal">
                            انصراف
                        </button>
                        @if(count($distributionPreview) > 0)
                            <button type="button" class="btn btn-success" wire:click="applyDistribution">
                                <span wire:loading.remove wire:target="applyDistribution">
                                    <i class="material-symbols-outlined" style="font-size: 18px;">check</i>
                                    اعمال در برنامه
                                </span>
                                <span wire:loading wire:target="applyDistribution">در حال اعمال...</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal توزیع پرسش و پاسخ --}}
    @if($showDistributeQaModal)
        <div class="modal fade show d-block" tabindex="-1"
             style="background: rgba(2, 6, 23, 0.60); backdrop-filter: blur(4px);">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header text-white"
                         style="background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%);">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">forum</i>
                            پیش‌نمایش توزیع پرسش و پاسخ در برنامه
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                                wire:click="closeDistributionModal"></button>
                    </div>

                    <div class="modal-body">
                        @if(count($distributionPreview) > 0)
                            <div class="alert alert-info small">
                                <i class="material-symbols-outlined" style="font-size: 16px;">info</i>
                                پارت‌ها یک روز قبل از تاریخ مشخص‌شده توزیع شده‌اند.
                            </div>
                            <div class="weekly-scroll">
                                <table class="table table-sm table-bordered align-middle">
                                    <thead>
                                    <tr class="bg-body-tertiary">
                                        <th class="text-center">#</th>
                                        <th>درس</th>
                                        <th class="text-center">روز</th>
                                        <th class="text-center">تاریخ</th>
                                        <th class="text-center">مدت (دقیقه)</th>
                                        <th>توضیحات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($distributionPreview as $idx => $item)
                                        <tr>
                                            <td class="text-center">{{ $idx + 1 }}</td>
                                            <td class="fw-semibold">{{ $item['subject'] }}</td>
                                            <td class="text-center"><span
                                                    class="badge bg-success rounded-pill">{{ $item['day_name'] }}</span>
                                            </td>
                                            <td class="text-center">{{ $item['jalali_date'] }}</td>
                                            <td class="text-center">{{ $item['duration_minutes'] }}</td>
                                            <td class="small">{{ $item['description'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted-2 text-center">موردی برای نمایش وجود ندارد.</p>
                        @endif
                    </div>

                    <div class="modal-footer bg-body-tertiary">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeDistributionModal">
                            انصراف
                        </button>
                        @if(count($distributionPreview) > 0)
                            <button type="button" class="btn btn-success" wire:click="applyDistribution">
                                <span wire:loading.remove wire:target="applyDistribution">
                                    <i class="material-symbols-outlined" style="font-size: 18px;">check</i>
                                    اعمال در برنامه
                                </span>
                                <span wire:loading wire:target="applyDistribution">در حال اعمال...</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- Modal طبقه‌بندی --}}
    @if($showClassificationModal)
        <div class="modal fade show d-block" tabindex="-1"
             style="background: rgba(2, 6, 23, 0.60); backdrop-filter: blur(4px); z-index: 1075;">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header text-white"
                         style="background: linear-gradient(135deg, #475569 0%, #64748b 50%, #94a3b8 100%);">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">category</i>
                            طبقه‌بندی مباحث
                            @if($classificationProjectName)
                                <span
                                    class="badge bg-white bg-opacity-25 fw-normal small">{{ $classificationProjectName }}</span>
                            @endif
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                                wire:click="closeClassificationModal"></button>
                    </div>

                    <div class="modal-body">
                        @if(count($classificationTopics) > 0)
                            <p class="small text-muted-2 mb-3 d-flex align-items-center gap-1">
                                <i class="material-symbols-outlined" style="font-size:16px;">info</i>
                                برای افزودن مبحث به برنامه روی «اضافه کردن» کلیک کنید و روز و زمان را انتخاب نمایید.
                            </p>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>درس</th>
                                        <th>فصل</th>
                                        <th>مبحث</th>
                                        <th class="text-center">رتبه</th>
                                        <th class="text-center">عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($classificationTopics as $idx => $item)
                                        <tr>
                                            <td class="text-muted-2">{{ $idx + 1 }}</td>
                                            <td class="fw-semibold">{{ $item['subject_name'] }}</td>
                                            <td class="small text-muted-2">{{ $item['chapter_name'] }}</td>
                                            <td class="fw-semibold">{{ $item['topic_name'] }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-{{ $item['rating_color'] }}-subtle text-{{ $item['rating_color'] }} fw-bold px-2">
                                                    {{ $item['rating_label'] }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex gap-1 justify-content-center">
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                            wire:click.stop="showClassificationInlineAdd({{ $item['topic_id'] }})">
                                                        <i class="material-symbols-outlined" style="font-size:14px;">add_circle</i>
                                                        اضافه کردن به برنامه
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        {{-- Inline add form --}}
                                        @if($showClassificationAddForm && $classificationSelectedTopicId === $item['topic_id'])
                                            <tr class="bg-body-tertiary">
                                                <td colspan="6">
                                                    <div class="p-3 rounded-3 border"
                                                         style="border-color: var(--ui-border) !important;">
                                                        <div class="row g-3 align-items-end">
                                                            <div class="col-md-4">
                                                                <label class="form-label small fw-semibold mb-1">انتخاب
                                                                    روز</label>
                                                                <select class="form-select form-select-sm"
                                                                        wire:model="classificationAddForm.day_index">
                                                                    <option value="">انتخاب کنید...</option>
                                                                    @foreach($weekDays as $wd)
                                                                        @if(!$wd['is_rest_day'])
                                                                            <option value="{{ $wd['index'] }}">
                                                                                {{ $wd['name'] }}
                                                                                ({{ $wd['jalali_date'] }})
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label
                                                                    class="form-label small fw-semibold mb-1">ساعت</label>
                                                                <input type="number" min="0" max="24"
                                                                       class="form-control form-control-sm text-center"
                                                                       wire:model="classificationAddForm.duration_hours"
                                                                       placeholder="0">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label
                                                                    class="form-label small fw-semibold mb-1">دقیقه</label>
                                                                <input type="number" min="0" max="59"
                                                                       class="form-control form-control-sm text-center"
                                                                       wire:model="classificationAddForm.duration_minutes"
                                                                       placeholder="0">
                                                            </div>
                                                            <div class="col-md-4 d-flex gap-2">
                                                                <button type="button"
                                                                        class="btn btn-sm btn-success flex-fill"
                                                                        wire:click="addClassificationToProgram">
                                                                    <span wire:loading.remove
                                                                          wire:target="addClassificationToProgram">
                                                                        <i class="material-symbols-outlined"
                                                                           style="font-size:14px;">check</i>
                                                                        ثبت نهایی
                                                                    </span>
                                                                    <span wire:loading
                                                                          wire:target="addClassificationToProgram">در حال ثبت...</span>
                                                                </button>
                                                                <button type="button"
                                                                        class="btn btn-sm btn-outline-secondary"
                                                                        wire:click="hideClassificationInlineAdd">
                                                                    <i class="material-symbols-outlined"
                                                                       style="font-size:14px;">close</i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="material-symbols-outlined text-muted-2" style="font-size:64px;">category</i>
                                <p class="text-muted-2 mt-3">
                                    @if($classificationProjectName)
                                        هیچ مبحثی برای این دانش‌آموز در پروژه «{{ $classificationProjectName }}» ثبت
                                        نشده است.
                                    @else
                                        پروژه طبقه‌بندی فعالی یافت نشد.
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer bg-body-tertiary">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeClassificationModal">
                            بستن
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal هشدار پارت با تایم صفر --}}
    @if($showZeroTimeWarningModal)
        <div class="modal fade show d-block" tabindex="-1"
             style="background: rgba(2, 6, 23, 0.60); backdrop-filter: blur(4px); z-index: 1080;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">warning</i>
                            هشدار — پارت‌های بدون تایم
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                                wire:click="closeZeroTimeWarningModal"></button>
                    </div>

                    <div class="modal-body text-center py-4">
                        <div class="mb-3">
                            <i class="material-symbols-outlined text-warning" style="font-size:64px;">schedule</i>
                        </div>
                        <h5 class="mb-3">برنامه دارای پارت‌های بدون تایم است</h5>
                        <p class="text-muted-2 mb-0">
                            <strong class="text-danger">{{ $zeroTimePartsCount }}</strong> پارت با مدت زمان ۰ دقیقه در
                            برنامه وجود دارد.
                            <br>
                            لطفاً ابتدا تایم پارت‌ها را تنظیم کنید یا برنامه را بدون تایید نهایی ذخیره کنید.
                        </p>
                    </div>

                    <div class="modal-footer justify-content-center gap-2 bg-body-tertiary">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeZeroTimeWarningModal">
                            بازگشت و ویرایش
                        </button>
                        <button type="button" class="btn btn-warning text-white" wire:click="forceFinalSave">
                            <i class="material-symbols-outlined" style="font-size:18px;">save</i>
                            ذخیره بدون تایید نهایی
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('script')
        <script src="/admin/assets/js/Sortable.min.js"></script>
        <script>
            // Cross-day drag-and-drop system
            let draggedPartId = null;
            let draggedFromDayIndex = null;

            function initSortableRows() {
                document.querySelectorAll('tr[data-sortable-row]').forEach(function (row) {
                    if (row._sortable) {
                        row._sortable.destroy();
                    }
                    row._sortable = Sortable.create(row, {
                        animation: 150,
                        handle: '.drag-handle',
                        draggable: '.plan-part-cell',
                        ghostClass: 'sortable-ghost-cell',
                        chosenClass: 'sortable-chosen-cell',
                        group: 'weeklyParts',
                        onStart: function (evt) {
                            var cell = evt.item;
                            draggedPartId = cell.getAttribute('data-part-id') ? parseInt(cell.getAttribute('data-part-id')) : null;
                            draggedFromDayIndex = parseInt(row.getAttribute('data-day-index'));
                        },
                        onEnd: function (evt) {
                            var targetRow = evt.to;
                            var targetDayIndex = parseInt(targetRow.getAttribute('data-day-index'));
                            var sourceDayIndex = draggedFromDayIndex;

                            if (sourceDayIndex === targetDayIndex) {
                                // Same day - reorder within day
                                var partIds = [];
                                targetRow.querySelectorAll('.plan-part-cell[data-part-id]').forEach(function (td) {
                                    var pid = td.getAttribute('data-part-id');
                                    if (pid) partIds.push(parseInt(pid));
                                });
                                if (partIds.length > 0) {
                                @this.call('reorderParts', partIds, targetDayIndex)
                                    ;
                                }
                            } else if (draggedPartId) {
                                // Cross-day move: check if dropped on another part (swap) or empty area (move)
                                var dropTargetCell = evt.related;
                                var targetPartId = dropTargetCell ? dropTargetCell.getAttribute('data-part-id') : null;

                                if (targetPartId && parseInt(targetPartId) !== draggedPartId) {
                                    // Swap parts
                                @this.call('swapParts', draggedPartId, parseInt(targetPartId))
                                    ;
                                } else {
                                    // Move to day
                                @this.call('movePartToDay', draggedPartId, targetDayIndex)
                                    ;
                                }
                            }

                            draggedPartId = null;
                            draggedFromDayIndex = null;
                        }
                    });
                });
                // Add drop zone visual feedback
                document.querySelectorAll('tr[data-sortable-row]').forEach(function (row) {
                    row.addEventListener('dragover', function () {
                        row.classList.add('drag-over-day');
                    });
                    row.addEventListener('dragleave', function () {
                        row.classList.remove('drag-over-day');
                    });
                    row.addEventListener('drop', function () {
                        row.classList.remove('drag-over-day');
                    });
                });
            }

            document.addEventListener('livewire:navigated', initSortableRows);
            document.addEventListener('livewire:updated', function () {
                setTimeout(initSortableRows, 100);
            });

            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(initSortableRows, 300);
            });
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
