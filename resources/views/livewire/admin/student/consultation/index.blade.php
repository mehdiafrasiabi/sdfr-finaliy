<div>
    @php
        $weekDays = [
            6 => 'شنبه',
            0 => 'یکشنبه',
            1 => 'دوشنبه',
            2 => 'سه‌شنبه',
            3 => 'چهارشنبه',
            4 => 'پنج‌شنبه',
            5 => 'جمعه',
        ];
    @endphp
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{route('admin.dashboard.index')}}">
                        <i class="fi fi-rr-home"></i>
                        صفحه اصلی
                    </a>
                </li>
                <li aria-current="page" class="breadcrumb-item active">
                    اتاق مشاوره
                </li>
            </ol>
        </nav>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card overflow-hidden">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <h6 class="card-title mb-0">اتاق مشاوره</h6>
                        <span class="badge bg-secondary rounded-pill" title="تعداد کل دانش‌آموزان">
                            {{ $totalStudentCount }} دانش‌آموز
                        </span>
                    </div>
                    <button wire:click="openStudentSelectModal"
                            class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                        <i class="fi fi-rr-calendar-plus"></i>
                        تعریف جلسه خودکار
                    </button>
                </div>

                <div class="card-body p-0">
                    {{-- ===== بخش ۱: انتخاب روز (Day Picker) ===== --}}
                    <div class="border-bottom bg-light px-3 pt-3 pb-0">
                        <div class="d-flex align-items-center gap-1 mb-2">
                            <i class="fi fi-rr-calendar-days text-muted small"></i>
                            <span class="small text-muted fw-semibold">انتخاب روز هفته:</span>
                        </div>
                        <ul class="nav nav-tabs border-0 flex-nowrap" style="overflow-x:auto;">
                            @foreach($weekDays as $day => $name)
                                <li class="nav-item">
                                    <button wire:click="selectDay({{ $day }})"
                                            class="nav-link px-3 py-2 {{ $selectedDay == $day ? 'active fw-bold' : '' }}">
                                        {{ $name }}
                                        @if(($dayCounts[$day] ?? 0) > 0)
                                            <span class="badge rounded-pill ms-1"
                                                  style="font-size:0.68rem;"
                                                  class="{{ $selectedDay == $day ? 'bg-primary' : 'bg-secondary' }}">
                                                {{ $dayCounts[$day] }}
                                            </span>
                                        @endif
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>


                    {{-- ===== بخش ۲: نمایش دانش‌آموزان روز انتخابی ===== --}}
                    <div class="p-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 fw-semibold fs-6">
                                    <i class="fi fi-rr-calendar me-1"></i>
                                    روز {{ $weekDays[$selectedDay] ?? '' }}
                                </span>
                                <span class="text-muted small">
                                    {{ count($dayStudents) }} دانش‌آموز در این روز
                                </span>
                            </div>
                        </div>

                        @forelse($dayStudents as $student)
                            @php
                                $profile = $student->user->profile ?? null;
                                $info    = $student->user->personalInformation ?? null;
                            @endphp
                            <div class="card border mb-3 shadow-sm">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-start gap-3">

                                        {{-- آواتار --}}
                                        <div class="flex-shrink-0">
                                            @if($profile && $profile->picture)
                                                <img src="{{ asset('user/img/' . $student->user->id . '/' . $profile->picture) }}"
                                                     class="rounded-circle"
                                                     style="width:48px;height:48px;object-fit:cover;" />
                                            @elseif($profile && $profile->gender === 'female')
                                                <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center"
                                                     style="width:48px;height:48px;font-size:1.3rem;">
                                                    <i class="fi fi-rr-user"></i>
                                                </div>
                                            @else
                                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                                                     style="width:48px;height:48px;font-size:1.3rem;">
                                                    <i class="fi fi-rr-user"></i>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- اطلاعات دانش‌آموز --}}
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold text-dark">
                                                {{ $info->name ?? '-' }} {{ $info->name_full ?? '' }}
                                            </div>
                                            <div class="text-muted small d-flex gap-3 mt-1 flex-wrap">
                                                <span>
                                                    <i class="fi fi-rr-smartphone me-1"></i>
                                                    {{ $student->user->mobile ?? '-' }}
                                                </span>
                                                @if($info)
                                                    <span>
                                                        @if($info->grade == 12) دوازدهم
                                                        @elseif($info->grade == 11) یازدهم
                                                        @elseif($info->grade == 10) دهم
                                                        @endif
                                                        @if($info->field == 'math') - ریاضی
                                                        @elseif($info->field == 'experimental') - تجربی
                                                        @elseif($info->field == 'human') - انسانی
                                                        @endif
                                                    </span>
                                                @endif
                                            </div>
                                            {{-- لیست جلسات --}}
                                            <div class="mt-2 pt-2 border-top">
                                                <div class="small text-muted mb-2 fw-semibold">
                                                    <i class="fi fi-rr-list me-1"></i>
                                                    لیست جلسات:
                                                </div>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($student->advisingSessions as $session)
                                                        @php
                                                            $jalaliDate = \Morilog\Jalali\Jalalian::fromCarbon($session->activation_date)->format('Y/m/d');
                                                            $hasResult  = !empty($session->result_status);
                                                            $timeStr    = $session->session_time
                                                                            ? $session->session_time->format('H:i')
                                                                            : '';
                                                        @endphp
                                                        <span class="badge rounded-pill px-3 py-2 small d-flex align-items-center gap-1
                                                              {{ $hasResult ? 'bg-secondary' : 'bg-light text-dark border' }}"
                                                              style="{{ $hasResult ? 'text-decoration:line-through; opacity:0.65;' : '' }}"
                                                              title="{{ $hasResult ? $session->result_label : 'در انتظار برگزاری' }}">
                                                            <i class="fi fi-rr-calendar-day" style="font-size:0.7rem;"></i>
                                                            {{ $jalaliDate }}
                                                            @if($timeStr)
                                                                <span class="opacity-75 small">{{ $timeStr }}</span>
                                                            @endif
                                                            @if($session->location_type === 'in_person')
                                                                <i class="fi fi-rr-building" style="font-size:0.65rem;" title="حضوری"></i>
                                                            @else
                                                                <i class="fi fi-rr-wifi" style="font-size:0.65rem;" title="آنلاین"></i>
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Bottom Pagination -->
                                        {{-- دکمه عملیات --}}
                                        @if($student->payment && $student->payment->order && $student->payment->order->user)
                                            <div class="flex-shrink-0">
                                                <a href="{{ route('admin.student.advising-sessions.create', $student->payment->order->user->id) }}"
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fi fi-rr-plus me-1"></i>
                                                    جلسه
                                                </a>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5">
                                <i class="fi fi-rr-calendar-xmark" style="font-size:2.5rem; opacity:0.4;"></i>
                                <p class="mt-2 mb-1">هیچ دانش‌آموزی برای روز <strong>{{ $weekDays[$selectedDay] ?? '' }}</strong> ثبت نشده است.</p>
                                <button wire:click="openStudentSelectModal"
                                        class="btn btn-primary btn-sm mt-2">
                                    <i class="fi fi-rr-calendar-plus me-1"></i>
                                    تعریف جلسه برای این روز
                                </button>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ==================== Modal 1: انتخاب دانش‌آموزان ==================== --}}
    @if($showStudentSelectModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);" wire:ignore.self>
            <div class="modal-dialog modal-lg modal-dialog-scrollable" style="max-width:720px;">
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-bold">
                            <i class="fi fi-rr-users me-2"></i>
                            انتخاب دانش‌آموزان برای تعریف جلسه خودکار
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeStudentSelectModal"></button>
                    </div>

                    <div class="modal-body p-0">
                        {{-- نمایش روز انتخاب‌شده (جدا از بخش انتخاب دانش‌آموز) --}}
                        <div class="p-3 border-bottom bg-primary-subtle">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fi fi-rr-calendar text-primary fs-5"></i>
                                <div>
                                    <div class="fw-bold text-primary fs-6">
                                        روز جلسه: {{ $weekDays[$selectedDay] ?? '' }}
                                    </div>
                                    <div class="small text-muted">جلسات هر هفته در این روز برگزار می‌شوند</div>
                                </div>
                            </div>
                        </div>

                        {{-- جستجو --}}
                        <div class="p-3 border-bottom bg-light">
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i class="fi fi-rr-search"></i></span>
                                <input type="text"
                                       class="form-control"
                                       wire:model.live.debounce.300ms="studentSearch"
                                       placeholder="جستجو بر اساس نام، نام خانوادگی یا موبایل..."
                                       autofocus />
                            </div>
                            <div class="alert alert-info d-flex align-items-center gap-2 mb-0 py-2 small">
                                <i class="fi fi-rr-info"></i>
                                فقط دانش‌آموزانی نمایش داده می‌شوند که هنوز برای هیچ روزی جلسه‌ای ثبت نشده است.
                            </div>
                        </div>

                        <!-- Selected count badge -->
                        @if(count($selectedStudents) > 0)
                            <div class="p-2 px-3 bg-primary-subtle border-bottom">
                                <span class="badge bg-primary rounded-pill me-1">{{ count($selectedStudents) }}</span>
                                دانش‌آموز انتخاب شده
                            </div>
                        @endif

                        <!-- Student List -->
                        <div style="max-height: 420px; overflow-y: auto;">
                            @forelse($modalStudents as $mStudent)
                                @php
                                    $mProfile   = $mStudent->user->profile ?? null;
                                    $mInfo      = $mStudent->user->personalInformation ?? null;
                                    $isSelected = in_array($mStudent->id, $selectedStudents);
                                @endphp
                                <div wire:click="toggleStudentSelection({{ $mStudent->id }})"
                                     class="d-flex align-items-center p-3 border-bottom
                                        {{ $isSelected ? 'bg-primary-subtle' : '' }}"
                                     style="cursor:pointer; transition: background 0.15s;">

                                    <!-- Checkbox -->
                                    <div class="me-3">
                                        <div class="form-check mb-0">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   style="width:18px;height:18px;"
                                                   @checked($isSelected)
                                                   onclick="return false;" />
                                        </div>
                                    </div>

                                    <!-- Avatar -->
                                    <div class="me-3">
                                        @if($mProfile && $mProfile->picture)
                                            <img src="{{ asset('user/img/' . $mStudent->user->id . '/' . $mProfile->picture) }}"
                                                 class="rounded-circle"
                                                 style="width:42px;height:42px;object-fit:cover;" />
                                        @elseif($mProfile && $mProfile->gender === 'female')
                                            <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center"
                                                 style="width:42px;height:42px;font-size:1.2rem;">
                                                <i class="fi fi-rr-user"></i>
                                            </div>
                                        @else
                                            <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                                                 style="width:42px;height:42px;font-size:1.2rem;">
                                                <i class="fi fi-rr-user"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Info -->
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-dark">
                                            {{ $mInfo->name ?? '-' }} {{ $mInfo->name_full ?? '' }}
                                        </div>
                                        <div class="text-muted small d-flex gap-3 mt-1 flex-wrap">
                                        <span>
                                            <i class="fi fi-rr-smartphone me-1"></i>
                                            {{ $mStudent->user->mobile ?? '-' }}
                                        </span>
                                            @if($mInfo)
                                                <span>
                                                    پایه:
                                                    @if($mInfo->grade == 12) دوازدهم
                                                    @elseif($mInfo->grade == 11) یازدهم
                                                    @elseif($mInfo->grade == 10) دهم
                                                    @else - @endif
                                                </span>
                                                <span>
                                            رشته:
                                            @if($mInfo->field == 'math') ریاضی
                                                    @elseif($mInfo->field == 'experimental') تجربی
                                                    @elseif($mInfo->field == 'human') انسانی
                                                    @else -
                                                    @endif
                                        </span>
                                            @endif
                                        </div>

                                    </div>

                                    <!-- Selected checkmark -->
                                    @if($isSelected)
                                        <div class="text-primary ms-2">
                                            <i class="fi fi-rr-check-circle" style="font-size:1.3rem;"></i>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center text-muted py-5">
                                    <i class="fi fi-rr-user-slash" style="font-size:2rem;"></i>
                                    <p class="mt-2">
                                        @if($studentSearch)
                                            دانش‌آموزی با این مشخصات یافت نشد.
                                        @else
                                            همه دانش‌آموزان جلسه دارند یا دانش‌آموزی وجود ندارد.
                                        @endif
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="modal-footer border-top d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" wire:click="closeStudentSelectModal">
                            انصراف
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="proceedToSchedule">
                            <i class="fi fi-rr-arrow-left me-1"></i>
                            ادامه ({{ count($selectedStudents) }} نفر انتخاب شده)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ==================== Modal 2: تنظیم روز و ساعت (به ازای هر دانش‌آموز) ==================== --}}
    @if($showScheduleModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);" wire:ignore.self>
            <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width:960px;">
                <div class="modal-content">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-bold">
                            <i class="fi fi-rr-clock me-2"></i>
                            تنظیم ساعت و محل برگزاری جلسات
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeScheduleModal"></button>
                    </div>

                    <div class="modal-body" style="max-height:70vh; overflow-y:auto;">

                        <!-- اطلاعیه -->
                        <div class="alert alert-info d-flex align-items-start gap-2 mb-4" role="alert">
                            <i class="fi fi-rr-info mt-1"></i>
                            <div class="small">
                                برای هر دانش‌آموز انتخاب‌شده، <strong>۴ جلسه</strong> به‌صورت خودکار در روز
                                <strong>{{ $weekDays[$selectedDay] ?? '' }}</strong>
                                ثبت می‌شود. هر جلسه یک هفته پس از جلسه قبلی برگزار می‌شود.

                                ساعت، دقیقه و محل برگزاری را برای هر دانش‌آموز جداگانه تنظیم کنید.

                            </div>
                        </div>

                        <!-- دانش‌آموزان انتخابی -->
                        <!-- کارت هر دانش‌آموز -->
                        @foreach($modalStudents->whereIn('id', $selectedStudents) as $selStudent)
                            @php
                                $selInfo  = $selStudent->user->personalInformation ?? null;
                                $sid      = $selStudent->id;
                                $schedule = $studentSchedules[$sid] ?? [
                                    'day'           => '',
                                    'hour'          => '08',
                                    'minute'        => '00',
                                    'location_type' => 'online',
                                    'skyroom_link'  => '',
                                ];
                                $isOnline = ($schedule['location_type'] ?? 'online') === 'online';
                            @endphp
                            <div class="card border mb-3 shadow-sm">
                                <div class="card-header bg-primary-subtle d-flex align-items-center gap-2 py-2">
                                    <i class="fi fi-rr-user text-primary"></i>
                                    <strong class="text-primary">
                                        {{ $selInfo->name ?? '-' }} {{ $selInfo->name_full ?? '' }}
                                    </strong>
                                    @if($selInfo)
                                        <small class="text-muted ms-1">({{ $selStudent->user->mobile ?? '' }})</small>

                                    @endif
                                    {{-- نمایش روز (جدا از انتخاب) --}}
                                    <span class="badge bg-white text-primary border border-primary ms-auto px-2 py-1">
                                        <i class="fi fi-rr-calendar me-1"></i>
                                        روز {{ $weekDays[$selectedDay] ?? '' }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3 align-items-start">


                                        <!-- ساعت -->
                                        <div class="col-md-2 col-3">
                                            <label class="form-label fw-semibold small">
                                                ساعت <span class="text-danger">*</span>
                                            </label>
                                            <input type="tel"
                                                   class="form-control form-control-sm @error('studentSchedules.'.$sid.'.hour') is-invalid @enderror"
                                                   wire:model="studentSchedules.{{ $sid }}.hour"
                                                   min="0" max="23" placeholder="۸">
                                            @error('studentSchedules.'.$sid.'.hour')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- دقیقه -->
                                        <div class="col-md-2 col-3">
                                            <label class="form-label fw-semibold small">
                                                دقیقه <span class="text-danger">*</span>
                                            </label>
                                            <input type="tel"
                                                   class="form-control form-control-sm @error('studentSchedules.'.$sid.'.minute') is-invalid @enderror"
                                                   wire:model="studentSchedules.{{ $sid }}.minute"
                                                   min="0" max="59" placeholder="۰۰">
                                            @error('studentSchedules.'.$sid.'.minute')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                        <!-- محل برگزاری -->
                                        <!-- محل برگزاری -->
                                        <div class="col-md-3 col-6">
                                            <label class="form-label fw-semibold small">
                                                محل برگزاری <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select form-select-sm @error('studentSchedules.'.$sid.'.location_type') is-invalid @enderror"
                                                    wire:model.live="studentSchedules.{{ $sid }}.location_type">
                                                <option value="online">مجازی (آنلاین)</option>
                                                <option value="in_person">حضوری</option>
                                            </select>
                                            @error('studentSchedules.'.$sid.'.location_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- لینک آنلاین (فقط اگر مجازی انتخاب شده) -->
                                        @if($isOnline)
                                            <div class="col-md-10 col-12">
                                                <label class="form-label fw-semibold small">
                                                    لینک جلسه آنلاین <span class="text-danger">*</span>
                                                </label>
                                                <input type="url"
                                                       class="form-control form-control-sm @error('studentSchedules.'.$sid.'.skyroom_link') is-invalid @enderror"
                                                       wire:model="studentSchedules.{{ $sid }}.skyroom_link"
                                                       placeholder="https://..." />
                                                @error('studentSchedules.'.$sid.'.skyroom_link')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endif

                        <!-- لینک آنلاین -->
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="modal-footer border-top d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" wire:click="backToStudentSelect">
                            <i class="fi fi-rr-arrow-right me-1"></i>
                            بازگشت
                        </button>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary" wire:click="closeScheduleModal">
                                انصراف
                            </button>
                            <button type="button" class="btn btn-success" wire:click="createAutoSessions"
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="createAutoSessions">
                                    <i class="fi fi-rr-check me-1"></i>
                                    ثبت جلسات
                                </span>
                                <span wire:loading wire:target="createAutoSessions">
                                    <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                    در حال ثبت...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
