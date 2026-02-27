<div>
    @push('link')
        <link rel="stylesheet" href="/admin/assets/css/jalalidatepicker.min.css">

        <style>
            /* ---- Dark/Light tokens (works with Bootstrap) ---- */
            :root{
                --card-bg: var(--bs-body-bg);
                --card-border: var(--bs-border-color);
                --muted: rgba(var(--bs-body-color-rgb), .65);
                --table-striped-bg: rgba(var(--bs-body-color-rgb), .04);
                --soft: rgba(var(--bs-body-color-rgb), .06);
                --link: var(--bs-link-color);
            }

            [data-bs-theme="dark"]{
                --table-striped-bg: rgba(255,255,255,.05);
                --soft: rgba(255,255,255,.08);
            }

            /* Card polish */
            .card.sessions-card{
                background: var(--card-bg);
                border-color: var(--card-border);
            }
            .card.sessions-card .card-header{
                background: transparent;
                border-bottom-color: var(--card-border);
            }

            /* Table improvements */
            .table.sessions-table{
                margin-bottom: 0;
            }
            .table.sessions-table thead th{
                font-weight: 600;
                white-space: nowrap;
            }
            .table.sessions-table tbody td{
                vertical-align: middle;
            }
            .table.sessions-table.table-striped > tbody > tr:nth-of-type(odd) > *{
                background-color: var(--table-striped-bg);
            }

            /* Better badges for both themes (keeps your existing classes) */
            .badge.bg-label-primary,
            .badge.bg-label-success,
            .badge.bg-label-warning,
            .badge.bg-label-secondary{
                background-color: var(--soft) !important;
                border: 1px solid var(--card-border);
                color: var(--bs-body-color) !important;
            }

            /* Icon buttons */
            .btn.btn-icon{
                width: 34px;
                height: 34px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            .icon-svg{
                width: 18px;
                height: 18px;
                display: inline-block;
                vertical-align: -0.125em;
                fill: currentColor;
            }

            /* Muted text consistent */
            .text-muted{
                color: var(--muted) !important;
            }

            /* Small helper for online link */
            .session-link{
                color: var(--link);
            }

        </style>
    @endpush
    <div class="container-xxl flex-grow-1 container-p-y">
        {{-- عنوان صفحه --}}
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">اتاق مشاوره /</span>
            {{ $editingSessionId ? 'ویرایش' : 'ایجاد' }} جلسه مشاوره
        </h4>
        <div class="row g-4 mb-4">
            {{-- فرم ایجاد/ویرایش جلسه --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">{{ $editingSessionId ? 'ویرایش' : 'افزودن' }} جلسه
                                مشاوره {{ $editingSessionId ? '' : 'جدید' }}</h5>
                            <small class="text-muted">لطفاً اطلاعات جلسه را کامل کنید.</small>
                        </div>
                        <div>
                            <span class="badge bg-label-success rounded-pill">
                                {{ $student->user->name ?? '---' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="{{ $editingSessionId ? 'updateSession' : 'createSession' }}">
                            {{-- عنوان جلسه --}}
                            <div class="mb-3">
                                <label class="form-label">عنوان جلسه</label>
                                <input
                                    type="text"
                                    name="title"
                                    wire:model="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    placeholder="مثال: 041127"
                                >
                                @error('title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- توضیحات (خودکار: جلسه مشاوره فردی) --}}
                            <div class="mb-3">
                                <label class="form-label">توضیحات جلسه</label>
                                <div class="form-control bg-body-tertiary text-muted-2" style="min-height:38px;">جلسه مشاوره فردی</div>

                            </div>
                            {{-- تاریخ و ساعت --}}
                            {{-- تاریخ و ساعت برگزاری (ترکیبی با تقویم شمسی) --}}
                            <div class="mb-3">
                                <label class="form-label">تاریخ و ساعت برگزاری</label>
                                <div wire:ignore>
                                    <input
                                        type="text"
                                        id="jdp-datetime"
                                        data-jdp
                                        class="form-control @error('activation_date') is-invalid @enderror @error('session_time') is-invalid @enderror"
                                        placeholder="انتخاب تاریخ و ساعت شمسی"
                                        autocomplete="off"
                                        readonly
                                    >
                                    {{-- فیلدهای مخفی که مقادیر میلادی را برای Livewire نگه می‌دارند --}}
                                    <input type="hidden" id="activation_date_hidden" wire:model="activation_date">
                                    <input type="hidden" id="session_time_hidden" wire:model="session_time">
                                    @error('activation_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    @error('session_time')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            {{-- محل برگزاری --}}
                            <div class="mb-3">
                                <label class="form-label">محل برگزاری</label>
                                <select name="location_type" wire:model.live.debounce.350ms="location_type"
                                        class="form-select @error('location_type') is-invalid @enderror">
                                    <option value="online">آنلاین</option>
                                    <option value="in_person">حضوری</option>
                                </select>
                                @error('location_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- لینک جلسه آنلاین --}}
                            @if($location_type === 'online')
                                <div class="mb-3">
                                    <label class="form-label">لینک جلسه آنلاین</label>
                                    <input
                                        type="url"
                                        name="skyroom_link"
                                        wire:model="skyroom_link"
                                        class="form-control @error('skyroom_link') is-invalid @enderror"
                                        placeholder="https://..."
                                    >
                                    @error('skyroom_link')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                            {{-- دکمه‌ها --}}
                            <div class="d-flex flex-wrap gap-2 mt-4">
                                <a
                                    href="{{ route('admin.advising-sessions') }}"
                                    class="btn btn-label-danger waves-effect"
                                >
                                    بازگشت
                                </a>
                                @if($editingSessionId)
                                    <button
                                        type="button"
                                        wire:click="cancelEdit"
                                        class="btn btn-label-secondary waves-effect"
                                    >
                                        انصراف
                                    </button>
                                    <button
                                        type="submit"
                                        class="btn btn-success waves-effect waves-light"
                                    >
                                        <span wire:loading.remove>ویرایش جلسه</span>
                                        <span wire:loading>در حال ویرایش...</span>
                                    </button>
                                @else
                                    <button
                                        type="submit"
                                        class="btn btn-success waves-effect waves-light"
                                    >
                                        <span wire:loading.remove>ثبت جلسه</span>
                                        <span wire:loading>در حال ثبت...</span>
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- لیست جلسات --}}
        <div class="card sessions-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="mb-0">لیست جلسات مشاوره</h5>
                <span class="badge rounded-pill bg-label-secondary">
      <span class="d-inline-flex align-items-center gap-1">
        <!-- calendar icon -->
        <svg class="icon-svg" viewBox="0 0 24 24" aria-hidden="true">
          <path
              d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h1V3a1 1 0 0 1 1-1Zm13 8H4v9a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-9ZM5 6a1 1 0 0 0-1 1v1h16V7a1 1 0 0 0-1-1H5Z"/>
        </svg>
        <span class="small">جلسات</span>
      </span>
    </span>
            </div>

            <div class="card-datatable table-responsive pt-0">
                <table class="table table-striped sessions-table mb-0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>عنوان</th>
                        <th>تاریخ و ساعت</th>
                        <th>محل برگزاری</th>
                        <th>وضعیت</th>
                        <th>نتیجه جلسه</th>
                        <th>پیش‌جلسه</th>
                        <th class="text-nowrap">عملیات</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($sessions as $session)
                        @php
                            $sessionDateTime = \Carbon\Carbon::parse($session->activation_date)->setTimeFromTimeString($session->session_time ?? '00:00:00');
                            $canAccess = \Carbon\Carbon::now()->gte($sessionDateTime);
                        @endphp

                        <tr>
                            <td class="text-nowrap">{{ $loop->iteration + $sessions->firstItem() - 1 }}</td>

                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $session->title }}</span>
                                    @if($session->description)
                                        <small class="text-muted ">{{ Str::limit($session->description, 80) }}</small>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <span
                                        class="text-nowrap">{{ jalali($session->activation_date)->format('%d %B %Y') }}</span>
                                    @if($session->session_time)
                                        <small class="text-muted text-nowrap">
                                            {{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}
                                        </small>
                                    @endif
                                </div>
                            </td>

                            <td>
                                @if($session->location_type === 'online')
                                    <span class="badge bg-label-primary me-1">آنلاین</span>
                                    @if($session->skyroom_link)
                                        <a
                                            href="{{ $session->skyroom_link }}"
                                            target="_blank"
                                            class="d-block small text-decoration-underline mt-1 session-link"
                                        >
                                            لینک جلسه
                                        </a>
                                    @endif
                                @else
                                    <span class="badge bg-label-success">حضوری</span>
                                @endif
                            </td>

                            <td>
                                @if($session->status === 'inactive')
                                    <span class="badge bg-secondary">در انتظار</span>
                                @elseif($session->status === 'active')
                                    <span class="badge bg-warning">در حال برگزاری</span>
                                @else
                                    <span class="badge bg-success">برگزار شده</span>
                                @endif
                            </td>

                            <td>
                                @if($canAccess && ($session->status === 'completed' || $session->status === 'active'))
                                    @php $programComplete = $this->isProgramComplete($session->id); @endphp

                                    <select
                                        wire:change="updateResultStatus({{$session->id}},$event.target.value)"
                                        class="form-select form-select-sm"
                                    >
                                        <option value="">انتخاب کنید</option>
                                        <option value="held"
                                            {{ $session->result_status === 'held' ? 'selected' : '' }}
                                            {{ !$programComplete ? 'disabled' : '' }}>
                                            برگزار شد {{ !$programComplete ? '(برنامه تکمیل نشده)' : '' }}
                                        </option>
                                        <option
                                            value="advisor_absent" {{ $session->result_status === 'advisor_absent' ? 'selected' : '' }}>
                                            توسط مشاور برگزار نشد
                                        </option>
                                        <option
                                            value="student_absent" {{ $session->result_status === 'student_absent' ? 'selected' : '' }}>
                                            دانش‌آموز غیبت داشت
                                        </option>
                                    </select>
                                    @if(!$programComplete && !$session->result_status)
                                        <small class="text-warning d-block mt-1" style="font-size: 10px;">
                                            <i class="material-symbols-outlined" style="font-size: 12px; vertical-align: middle;">warning</i>
                                            برنامه تکمیل نشده
                                        </small>
                                    @endif
                                @else
                                    <span class="badge bg-label-secondary d-inline-flex align-items-center gap-1">
                                <svg class="text-black" viewBox="0 0 24 24" aria-hidden="true" style="width:18px;height:18px;">
                                            <path d="M17 10h-1V8a4 4 0 0 0-8 0v2H7a3 3 0 0 0-3 3v6a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-6a3 3 0 0 0-3-3Zm-7-2a2 2 0 1 1 4 0v2h-4V8Zm10 11a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-6a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v6Z"/>
                                        </svg>
                                        <span>قفل</span>
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($session->preSession)
                                    @if($session->preSession->status === 'completed')
                                        <span class="badge bg-success">ثبت شده</span>
                                    @else
                                        <span class="badge bg-warning">در انتظار</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    @if($canAccess)
                                        <a
                                            href="{{ route('admin.student.weekly-program', ['student' => $student->id, 'session' => $session->id]) }}"
                                            class="btn btn-sm btn-icon btn-text-primary rounded-pill waves-effect"
                                            title="برنامه هفتگی"
                                        >
                                            <!-- calendar_month icon -->
                                            <svg class="icon-svg" viewBox="0 0 24 24" aria-hidden="true">
                                                <path
                                                    d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h1V3a1 1 0 0 1 1-1Zm13 8H4v9a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-9ZM5 6a1 1 0 0 0-1 1v1h16V7a1 1 0 0 0-1-1H5Z"/>
                                            </svg>
                                        </a>
                                    @else
                                        <button
                                            disabled
                                            class="btn btn-sm btn-icon btn-text-secondary rounded-pill"
                                            title="تا زمان رسیدن به تاریخ جلسه قفل است"
                                        >
                                            <!-- lock icon -->
                                            <svg class="icon-svg" viewBox="0 0 24 24" aria-hidden="true">
                                                <path
                                                    d="M17 10h-1V8a4 4 0 0 0-8 0v2H7a3 3 0 0 0-3 3v6a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-6a3 3 0 0 0-3-3Zm-7-2a2 2 0 1 1 4 0v2h-4V8Zm10 11a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-6a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v6Z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    <button
                                        wire:click="editSession({{ $session->id }})"
                                        class="btn btn-sm btn-icon btn-text-warning rounded-pill waves-effect"
                                        title="ویرایش جلسه"
                                    >
                                        <!-- edit icon -->
                                        <svg class="icon-svg" viewBox="0 0 24 24" aria-hidden="true">
                                            <path
                                                d="M4 17.25V20h2.75L17.81 8.94l-2.75-2.75L4 17.25Zm16.71-10.04a1 1 0 0 0 0-1.41l-2.51-2.51a1 1 0 0 0-1.41 0l-1.38 1.38 3.92 3.92 1.38-1.38Z"/>
                                        </svg>
                                    </button>

                                    <button
                                        wire:confirm="آیا مطمئن هستید؟"
                                        wire:click="deleteSession({{ $session->id }})"
                                        class="btn btn-sm btn-icon btn-text-danger rounded-pill waves-effect"
                                        title="حذف جلسه"
                                    >
                                        <!-- delete icon -->
                                        <svg class="icon-svg" viewBox="0 0 24 24" aria-hidden="true">
                                            <path
                                                d="M6 7h12l-1 14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 7Zm3-5h6a2 2 0 0 1 2 2v1H7V4a2 2 0 0 1 2-2Zm-1 5h2v12H8V7Zm6 0h2v12h-2V7Z"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted d-flex flex-column align-items-center gap-2">
                                    <!-- event_busy icon -->
                                    <svg class="icon-svg" style="width:34px;height:34px" viewBox="0 0 24 24"
                                         aria-hidden="true">
                                        <path
                                            d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h1V3a1 1 0 0 1 1-1Zm13 8H4v9a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-9ZM5 6a1 1 0 0 0-1 1v1h16V7a1 1 0 0 0-1-1H5Z"/>
                                        <path
                                            d="M9.3 13.3a1 1 0 0 1 1.4 0L12 14.6l1.3-1.3a1 1 0 1 1 1.4 1.4L13.4 16l1.3 1.3a1 1 0 0 1-1.4 1.4L12 17.4l-1.3 1.3a1 1 0 0 1-1.4-1.4L10.6 16l-1.3-1.3a1 1 0 0 1 0-1.4Z"/>
                                    </svg>
                                    <div>هیچ جلسه‌ای ثبت نشده است</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- paginate --}}
            <div class="row p-3 g-2 align-items-center">
                <div class="col-sm-12 col-md-5"></div>
                <div class="col-sm-12 col-md-7 d-flex justify-content-md-end">
                    {{ $sessions->links('layouts.admin.pagination') }}
                </div>
            </div>
        </div>

    </div>

    @push('script')
        <script src="/admin/assets/js/jalalidatepicker.min.js"></script>
        <script>
            (function () {
                'use strict';

                // ===== تبدیل جلالی به میلادی =====
                function jalaliToGregorian(jy, jm, jd) {
                    jy = parseInt(jy);
                    jm = parseInt(jm);
                    jd = parseInt(jd);
                    var jy1 = jy - 979, jm1 = jm - 1, jd1 = jd - 1;
                    var jDayNo = 365 * jy1 + Math.floor(jy1 / 33) * 8 + Math.floor((jy1 % 33 + 3) / 4);
                    var months = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];
                    for (var i = 0; i < jm1; i++) jDayNo += months[i];
                    jDayNo += jd1;
                    var gDayNo = jDayNo + 79;
                    var gy = 1600 + 400 * Math.floor(gDayNo / 146097);
                    gDayNo = gDayNo % 146097;
                    var leap = true;
                    if (gDayNo >= 36525) {
                        gDayNo--;
                        gy += 100 * Math.floor(gDayNo / 36524);
                        gDayNo = gDayNo % 36524;
                        if (gDayNo >= 365) gDayNo++;
                        else leap = false;
                    }
                    gy += 4 * Math.floor(gDayNo / 1461);
                    gDayNo %= 1461;
                    if (gDayNo >= 366) {
                        leap = false;
                        gDayNo--;
                        gy += Math.floor(gDayNo / 365);
                        gDayNo = gDayNo % 365;
                    }
                    var gMonths = [31, leap ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                    var gm = 0;
                    for (var i = 0; gDayNo >= gMonths[i]; i++) gDayNo -= gMonths[i];
                    gm = i + 1;
                    var gd = gDayNo + 1;
                    return [gy, gm, gd];
                }

                // ===== به‌روزرسانی فیلدهای مخفی Livewire از مقدار تقویم =====
                function syncPickerToLivewire(pickerValue) {
                    if (!pickerValue) return;
                    var parts = pickerValue.split(' ');
                    var datePart = parts[0] || '';
                    var timePart = parts[1] || '00:00';

                    var dp = datePart.split('/');
                    if (dp.length !== 3) return;
                    var greg = jalaliToGregorian(dp[0], dp[1], dp[2]);
                    var gregorianDate = greg[0] + '-'
                        + String(greg[1]).padStart(2, '0') + '-'
                        + String(greg[2]).padStart(2, '0');

                    var dateHidden = document.getElementById('activation_date_hidden');
                    var timeHidden = document.getElementById('session_time_hidden');
                    if (!dateHidden || !timeHidden) return;

                    dateHidden.value = gregorianDate;
                    timeHidden.value = timePart;
                    dateHidden.dispatchEvent(new Event('input', {bubbles: true}));
                    timeHidden.dispatchEvent(new Event('input', {bubbles: true}));
                }

                // ===== راه‌اندازی تقویم =====
                function initPicker() {
                    if (typeof jalaliDatepicker === 'undefined') return;

                    jalaliDatepicker.startWatch({
                        time: true,
                        hasSecond: false,
                        hideAfterChange: true,
                        showTodayBtn: true,
                        showEmptyBtn: true,
                        selector: '#jdp-datetime',
                    });

                    var pickerInput = document.getElementById('jdp-datetime');
                    if (!pickerInput) return;

                    // شنیدن تغییر تقویم
                    pickerInput.addEventListener('jdp:change', function () {
                        syncPickerToLivewire(this.value);
                    });

                    // اگر در حالت ویرایش باشیم، فیلد از قبل مقدار دارد
                    if (pickerInput.value) {
                        syncPickerToLivewire(pickerInput.value);
                    }
                }

                // ===== رویداد: پر کردن تقویم هنگام ویرایش جلسه =====
                window.addEventListener('jdp-session-loaded', function (event) {
                    var detail = Array.isArray(event.detail) ? event.detail[0] : event.detail;
                    var pickerInput = document.getElementById('jdp-datetime');
                    if (!pickerInput) return;
                    var jalaliDate = detail.jalali_date || '';
                    var sessionTime = detail.session_time || '';
                    pickerInput.value = jalaliDate + (sessionTime ? ' ' + sessionTime : '');
                    // همگام‌سازی با Livewire
                    syncPickerToLivewire(pickerInput.value);
                });

                // ===== رویداد: پاک کردن تقویم پس از ثبت/انصراف =====
                window.addEventListener('jdp-session-cleared', function () {
                    var pickerInput = document.getElementById('jdp-datetime');
                    if (pickerInput) pickerInput.value = '';
                    var dateHidden = document.getElementById('activation_date_hidden');
                    var timeHidden = document.getElementById('session_time_hidden');
                    if (dateHidden) {
                        dateHidden.value = '';
                        dateHidden.dispatchEvent(new Event('input', {bubbles: true}));
                    }
                    if (timeHidden) {
                        timeHidden.value = '';
                        timeHidden.dispatchEvent(new Event('input', {bubbles: true}));
                    }
                });

                // ===== اجرا پس از لود صفحه =====
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initPicker);
                } else {
                    initPicker();
                }

                // ===== اجرا مجدد پس از به‌روزرسانی Livewire (برای حفظ تقویم) =====
                document.addEventListener('livewire:navigated', initPicker);
            })();
        </script>
    @endpush
</div>
