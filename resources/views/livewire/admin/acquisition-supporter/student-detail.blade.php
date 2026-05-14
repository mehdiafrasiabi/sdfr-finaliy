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
