<div>
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @error('contactType')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @enderror

        {{-- هدر --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1">{{ $trialWeek->user->name ?? '—' }}</h4>
                <p class="text-muted mb-0" dir="ltr">{{ $trialWeek->user->mobile ?? '—' }}</p>
            </div>
            <a href="{{ route('admin.acquisition-supporter.dashboard') }}" class="btn btn-soft-secondary btn-sm">
                <i class="ri-arrow-right-line me-1"></i>بازگشت
            </a>
        </div>

        <div class="row g-4">

            {{-- اطلاعات دانش‌آموز --}}
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="ri-user-line me-2 text-primary"></i>اطلاعات دانش‌آموز</h5>
                    </div>
                    <div class="card-body">
                        @php $info = $trialWeek->user?->personalInformation; @endphp
                        <ul class="list-unstyled mb-0 vstack gap-2">
                            <li class="d-flex justify-content-between">
                                <span class="text-muted">نام</span>
                                <span class="fw-semibold">{{ $info?->first_name ?? $trialWeek->user?->name ?? '—' }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted">نام خانوادگی</span>
                                <span class="fw-semibold">{{ $info?->last_name ?? '—' }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted">کد ملی</span>
                                <span class="fw-semibold" dir="ltr">{{ $info?->code_mell ?? '—' }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted">پایه</span>
                                <span class="fw-semibold">{{ $trialWeek->gradeLabel }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted">رشته</span>
                                <span class="fw-semibold">{{ $trialWeek->fieldLabel }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted">موبایل</span>
                                <span class="fw-semibold" dir="ltr">{{ $trialWeek->user?->mobile ?? '—' }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted">موبایل پدر</span>
                                <span class="fw-semibold" dir="ltr">{{ $trialWeek->father_mobile }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted">موبایل مادر</span>
                                <span class="fw-semibold" dir="ltr">{{ $trialWeek->mother_mobile }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted">وضعیت هفته آزمایشی</span>
                                <span class="badge badge-soft-info">{{ $trialWeek->statusLabel }}</span>
                            </li>
                            @if($trialWeek->expires_at)
                            <li class="d-flex justify-content-between">
                                <span class="text-muted">انقضا</span>
                                <span class="{{ $trialWeek->isExpired() ? 'text-danger' : 'text-success' }} fw-semibold">
                                    {{ $trialWeek->isExpired() ? 'منقضی' : $trialWeek->daysRemaining . ' روز مانده' }}
                                </span>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            {{-- مدیریت تماس‌ها --}}
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0"><i class="ri-phone-line me-2 text-success"></i>مدیریت تماس‌ها</h5>

                        {{-- دکمه‌های ثبت تماس --}}
                        <div class="d-flex gap-2 flex-wrap">
                            @php
                                $contacts   = $trialWeek->acquisitionContacts;
                                $hasInit    = $contacts->where('type','initial')->isNotEmpty();
                                $hasSec     = $contacts->where('type','secondary')->isNotEmpty();
                                $hasSupp    = $contacts->where('type','supplementary')->isNotEmpty();
                            @endphp

                            <button wire:click="openContactForm('initial')"
                                    class="btn btn-sm {{ $hasInit ? 'btn-soft-success' : 'btn-success' }}">
                                <i class="ri-phone-fill me-1"></i>تماس اولیه
                                @if($hasInit)<i class="ri-checkbox-circle-fill ms-1"></i>@endif
                            </button>

                            <button wire:click="openContactForm('secondary')"
                                    class="btn btn-sm {{ $hasSec ? 'btn-soft-primary' : 'btn-primary' }}"
                                    {{ !$hasInit ? 'disabled' : '' }}>
                                <i class="ri-phone-fill me-1"></i>تماس ثانویه
                                @if($hasSec)<i class="ri-checkbox-circle-fill ms-1"></i>@endif
                            </button>

                            <button wire:click="openContactForm('supplementary')"
                                    class="btn btn-sm {{ $hasSupp ? 'btn-soft-warning' : 'btn-warning' }}"
                                    {{ (!$hasInit || !$hasSec) ? 'disabled' : '' }}>
                                <i class="ri-phone-fill me-1"></i>تماس جانبی
                                @if($hasSupp)<i class="ri-checkbox-circle-fill ms-1"></i>@endif
                            </button>
                        </div>
                    </div>

                    <div class="card-body">

                        {{-- تاریخچه تماس‌ها --}}
                        @forelse($trialWeek->acquisitionContacts as $contact)
                        <div class="border rounded-3 p-3 mb-3 {{ $contact->answered ? 'border-success' : 'border-danger' }}">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <span class="badge me-2
                                        {{ $contact->type === 'initial' ? 'bg-success' :
                                           ($contact->type === 'secondary' ? 'bg-primary' : 'bg-warning text-dark') }}">
                                        {{ $contact->typeLabel }}
                                    </span>
                                    <span class="badge {{ $contact->answered ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                        {{ $contact->answered ? 'پاسخ داده شد' : 'پاسخ داده نشد' }}
                                    </span>
                                </div>
                                <small class="text-muted">{{ $contact->contacted_at->diffForHumans() }}</small>
                            </div>

                            @if($contact->notes)
                                <p class="mt-2 mb-0 fs-13 text-muted">{{ $contact->notes }}</p>
                            @endif

                            @if($contact->type === 'secondary' && $contact->prediction_percentage !== null)
                                <div class="mt-2">
                                    <span class="text-muted fs-12">درصد پیش‌بینی جذب: </span>
                                    <span class="fw-bold text-{{ $contact->prediction_percentage >= 60 ? 'success' : ($contact->prediction_percentage >= 30 ? 'warning' : 'danger') }}">
                                        {{ $contact->prediction_percentage }}٪
                                    </span>
                                    <div class="progress mt-1" style="height: 6px;">
                                        <div class="progress-bar bg-{{ $contact->prediction_percentage >= 60 ? 'success' : ($contact->prediction_percentage >= 30 ? 'warning' : 'danger') }}"
                                             style="width: {{ $contact->prediction_percentage }}%"></div>
                                    </div>
                                </div>
                            @endif

                            @if($contact->type === 'secondary' && $contact->attraction_plan)
                                <div class="mt-2 p-2 bg-light rounded">
                                    <strong class="fs-12 text-muted">پلن جذب:</strong>
                                    <p class="mb-0 fs-12 mt-1">{{ $contact->attraction_plan }}</p>
                                </div>
                            @endif
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="ri-phone-off-line display-6 d-block mb-2"></i>
                            هنوز هیچ تماسی ثبت نشده است.
                        </div>
                        @endforelse

                    </div>
                </div>
            </div>

        </div>

        {{-- ============================================================ --}}
        {{--  داده‌های دانش‌آموز: برنامه / گزارش‌ها / ساعت مطالعه          --}}
        {{-- ============================================================ --}}
        @php
            $fmtDur = function ($seconds) {
                $seconds = (int) $seconds;
                $h = intdiv($seconds, 3600);
                $m = intdiv($seconds % 3600, 60);
                if ($h > 0) return $h . ' ساعت' . ($m > 0 ? ' و ' . $m . ' دقیقه' : '');
                return $m . ' دقیقه';
            };
        @endphp

        <div class="row g-4 mt-1">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#tabProgram" role="tab">
                                    <i class="ri-calendar-todo-line me-1"></i>برنامهٔ هفتگی
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tabReports" role="tab">
                                    <i class="ri-file-list-3-line me-1"></i>گزارش‌های روزانه
                                    <span class="badge bg-primary-subtle text-primary ms-1">{{ $reports->count() }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tabStudy" role="tab">
                                    <i class="ri-time-line me-1"></i>ساعت مطالعه
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        @unless($hasStudent)
                            <div class="alert alert-warning mb-0">
                                <i class="ri-information-line me-1"></i>
                                این دانش‌آموز هنوز پروندهٔ تحصیلی (Student) فعالی ندارد؛ برنامه، گزارش و ساعت مطالعه‌ای ثبت نشده است.
                            </div>
                        @else
                        <div class="tab-content">

                            {{-- ---------------- برنامهٔ هفتگی ---------------- --}}
                            <div class="tab-pane fade show active" id="tabProgram" role="tabpanel">
                                @if(!$program)
                                    <div class="text-center text-muted py-4">
                                        <i class="ri-calendar-line display-6 d-block mb-2"></i>
                                        هنوز برنامه‌ای برای این دانش‌آموز ساخته نشده است.
                                    </div>
                                @else
                                    <div class="d-flex flex-wrap gap-3 mb-3">
                                        <span class="badge bg-info-subtle text-info">
                                            بازه: {{ jdate($program->start_date)->format('Y/m/d') }}
                                            تا {{ jdate($program->end_date)->format('Y/m/d') }}
                                        </span>
                                        <span class="badge {{ $program->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                            {{ $program->is_active ? 'فعال' : 'غیرفعال' }}
                                        </span>
                                        <span class="badge bg-primary-subtle text-primary">مجموع: {{ $program->total_hours }} ساعت</span>
                                        <span class="badge bg-warning-subtle text-warning">{{ $program->total_tests }} تست</span>
                                        @if($program->advisor)
                                            <span class="badge bg-light text-dark">مشاور: {{ $program->advisor->name }}</span>
                                        @endif
                                    </div>

                                    @foreach($program->getWeekDays() as $day)
                                        <div class="border rounded-3 mb-2">
                                            <div class="d-flex align-items-center justify-content-between px-3 py-2 bg-light rounded-top">
                                                <span class="fw-semibold">
                                                    {{ $day['name'] }}
                                                    <small class="text-muted ms-1">{{ $day['jalali_date'] }}</small>
                                                </span>
                                                <span>
                                                    @if($day['is_rest_day'])
                                                        <span class="badge bg-secondary">روز استراحت</span>
                                                    @else
                                                        <span class="badge bg-primary-subtle text-primary">{{ $day['total_hours'] }} ساعت</span>
                                                        <span class="badge bg-warning-subtle text-warning">{{ $day['total_tests'] }} تست</span>
                                                    @endif
                                                </span>
                                            </div>
                                            @if($day['parts']->isNotEmpty())
                                                <div class="table-responsive">
                                                    <table class="table table-sm mb-0 align-middle">
                                                        <tbody>
                                                        @foreach($day['parts'] as $part)
                                                            <tr>
                                                                <td style="width:40%">{{ $part->lesson_name ?? '—' }}</td>
                                                                <td><span class="badge bg-soft-secondary text-muted">{{ $part->part_type_label }}</span></td>
                                                                <td>{{ $part->lesson_type_label }}</td>
                                                                <td class="text-nowrap">{{ $part->duration_hours }} ساعت</td>
                                                                <td class="text-nowrap">{{ $part->test_count ?? 0 }} تست</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @elseif(!$day['is_rest_day'])
                                                <div class="px-3 py-2 text-muted fs-13">پارتی برای این روز ثبت نشده.</div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            {{-- ---------------- گزارش‌های روزانه ---------------- --}}
                            <div class="tab-pane fade" id="tabReports" role="tabpanel">
                                @forelse($reports as $report)
                                    <div class="border rounded-3 p-3 mb-3">
                                        <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
                                            <div>
                                                <span class="fw-semibold">{{ $report->day_name }}</span>
                                                <small class="text-muted ms-1">{{ jdate($report->report_date)->format('Y/m/d') }}</small>
                                                @if($report->is_compensatory)
                                                    <span class="badge bg-info-subtle text-info ms-1">جبرانی</span>
                                                @endif
                                            </div>
                                            <div class="d-flex gap-2">
                                                <span class="badge
                                                    {{ $report->status === 'approved' ? 'bg-success-subtle text-success' :
                                                       ($report->status === 'rejected' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning') }}">
                                                    {{ $report->status_label }}
                                                </span>
                                                <span class="badge bg-light text-dark">امتیاز: {{ $report->rating }}</span>
                                                <span class="badge bg-soft-primary text-primary">{{ $report->read_parts_count }}/{{ $report->total_parts }} پارت</span>
                                            </div>
                                        </div>
                                        @if($report->detail?->description)
                                            <p class="mt-2 mb-0 fs-13 text-muted">{{ $report->detail->description }}</p>
                                        @endif
                                        @if($report->feedback?->advisor_comment)
                                            <div class="mt-2 p-2 bg-light rounded">
                                                <strong class="fs-12 text-muted">نظر مشاور:</strong>
                                                <p class="mb-0 fs-12 mt-1">{{ $report->feedback->advisor_comment }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">
                                        <i class="ri-file-list-line display-6 d-block mb-2"></i>
                                        هنوز گزارشی توسط دانش‌آموز ثبت نشده است.
                                    </div>
                                @endforelse
                            </div>

                            {{-- ---------------- ساعت مطالعه ---------------- --}}
                            <div class="tab-pane fade" id="tabStudy" role="tabpanel">
                                <div class="alert alert-primary d-flex align-items-center justify-content-between">
                                    <span><i class="ri-time-line me-1"></i>مجموع ساعت مطالعهٔ تکمیل‌شده</span>
                                    <span class="fw-bold">{{ $fmtDur($totalStudySeconds) }}</span>
                                </div>

                                @if($studySessions->isEmpty())
                                    <div class="text-center text-muted py-4">
                                        <i class="ri-timer-line display-6 d-block mb-2"></i>
                                        هنوز جلسهٔ مطالعه‌ای ثبت نشده است.
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle">
                                            <thead>
                                                <tr>
                                                    <th>درس / پارت</th>
                                                    <th>تاریخ</th>
                                                    <th>مدت</th>
                                                    <th>وضعیت</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($studySessions as $session)
                                                <tr>
                                                    <td>{{ $session->programPart?->lesson_name ?? '—' }}</td>
                                                    <td class="text-nowrap">
                                                        {{ $session->started_at ? jdate($session->started_at)->format('Y/m/d H:i') : '—' }}
                                                    </td>
                                                    <td class="text-nowrap">{{ $fmtDur($session->duration_seconds) }}</td>
                                                    <td>
                                                        @if($session->is_completed)
                                                            <span class="badge bg-success-subtle text-success">تکمیل شده</span>
                                                        @else
                                                            <span class="badge bg-warning-subtle text-warning">ناتمام</span>
                                                        @endif
                                                        @if($session->is_cheating)
                                                            <span class="badge bg-danger-subtle text-danger ms-1">مشکوک</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                        </div>
                        @endunless
                    </div>
                </div>
            </div>
        </div>

        {{-- مودال ثبت تماس --}}
        @if($showContactForm)
        <div class="modal show d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            ثبت
                            @if($contactType === 'initial') تماس اولیه
                            @elseif($contactType === 'secondary') تماس ثانویه
                            @else تماس جانبی
                            @endif
                        </h5>
                        <button wire:click="closeContactForm" type="button" class="btn-close"></button>
                    </div>
                    <div class="modal-body">

                        {{-- پاسخ داده شد؟ --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">وضعیت تماس</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input wire:model="contactAnswered" class="form-check-input" type="radio"
                                           name="answered" id="answeredYes" value="1">
                                    <label class="form-check-label text-success" for="answeredYes">
                                        <i class="ri-check-line me-1"></i>پاسخ داده شد
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input wire:model="contactAnswered" class="form-check-input" type="radio"
                                           name="answered" id="answeredNo" value="0">
                                    <label class="form-check-label text-danger" for="answeredNo">
                                        <i class="ri-close-line me-1"></i>پاسخ داده نشد
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- یادداشت --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">یادداشت <small class="text-muted">(اختیاری)</small></label>
                            <textarea wire:model="contactNotes" class="form-control" rows="3"
                                      placeholder="خلاصه مکالمه یا نکات مهم..."></textarea>
                            @error('contactNotes') <div class="text-danger fs-12 mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- فیلدهای اختصاصی تماس ثانویه --}}
                        @if($contactType === 'secondary')
                        <div class="alert alert-info p-3 mb-3">
                            <strong>تماس ثانویه</strong> — پس از ۲ روز از تماس اولیه، با گزارش والدین
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                درصد پیش‌بینی جذب
                                <span class="badge bg-primary ms-1" id="pctBadge">{{ $predictionPct ?? 0 }}٪</span>
                            </label>
                            <input wire:model.live="predictionPct" type="range"
                                   class="form-range" min="0" max="100" step="5">
                            @error('predictionPct') <div class="text-danger fs-12 mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">پلن جذب <small class="text-muted">(اختیاری)</small></label>
                            <textarea wire:model="attractionPlan" class="form-control" rows="4"
                                      placeholder="برنامه پیشنهادی برای جذب این دانش‌آموز..."></textarea>
                        </div>
                        @endif

                        @if($contactType === 'supplementary')
                        <div class="alert alert-warning p-3">
                            <strong>تماس جانبی</strong> — پس از انجام تماس اولیه و ثانویه، برای حل مشکلات
                        </div>
                        @endif

                    </div>
                    <div class="modal-footer">
                        <button wire:click="closeContactForm" type="button" class="btn btn-light">انصراف</button>
                        <button wire:click="saveContact" type="button" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i>ثبت تماس
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
