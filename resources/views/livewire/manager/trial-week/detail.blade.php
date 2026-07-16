<div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- کارت اطلاعات کاربر --}}
        <div class="col-xxl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">اطلاعات کاربر</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-lg bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                            <i class="ri-user-line fs-2 text-primary"></i>
                        </div>
                        <h5 class="mb-1">{{ $trialWeek->user->name ?? '—' }}</h5>
                        <p class="text-muted mb-0">{{ $trialWeek->user->email ?? '—' }}</p>
                    </div>

                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="text-muted fw-medium">موبایل:</th>
                                <td>{{ $trialWeek->user->mobile ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-medium">پایه:</th>
                                <td><span class="badge bg-secondary">{{ $trialWeek->gradeLabel }}</span></td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-medium">رشته:</th>
                                <td>{{ $trialWeek->fieldLabel }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-medium">تلفن پدر:</th>
                                <td dir="ltr">{{ $trialWeek->father_mobile }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-medium">تلفن مادر:</th>
                                <td dir="ltr">{{ $trialWeek->mother_mobile }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-medium">تاریخ ثبت:</th>
                                <td>{{ $trialWeek->created_at->format('Y/m/d') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted fw-medium">انقضا:</th>
                                <td>
                                    @if($trialWeek->expires_at)
                                        <span class="{{ $trialWeek->isExpired() ? 'text-danger' : 'text-success' }}">
                                            {{ $trialWeek->expires_at->format('Y/m/d') }}
                                            @if(!$trialWeek->isExpired())
                                                ({{ $trialWeek->daysRemaining }} روز)
                                            @else
                                                (منقضی)
                                            @endif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- کارت وضعیت و مراحل --}}
        <div class="col-xxl-8">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">وضعیت فرایند آزمایشی</h5>
                    @php
                        $badgeClass = match($trialWeek->status) {
                            'pending' => 'badge-soft-warning',
                            'supporter_assigned' => 'badge-soft-info',
                            'classification_done' => 'badge-soft-primary',
                            'pre_session_done' => 'badge-soft-secondary',
                            'program_built' => 'badge-soft-success',
                            default => 'badge-soft-secondary',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }} fs-12">{{ $trialWeek->statusLabel }}</span>
                </div>
                <div class="card-body">

                    {{-- Timeline مراحل --}}
                    <div class="row g-3 mb-4">
                        @php
                            $steps = [
                                ['label' => 'ثبت درخواست', 'done' => true, 'date' => $trialWeek->created_at],
                                ['label' => 'تخصیص پشتیبان', 'done' => $trialWeek->step >= 1, 'date' => $trialWeek->supporter_assigned_at],
                                ['label' => 'طبقه‌بندی تکمیل', 'done' => $trialWeek->step >= 2, 'date' => $trialWeek->classification_locked_at],
                                ['label' => 'پیش‌جلسه تکمیل', 'done' => $trialWeek->step >= 3, 'date' => $trialWeek->pre_session_completed_at],
                                ['label' => 'برنامه ساخته شد', 'done' => $trialWeek->step >= 4, 'date' => $trialWeek->program_built_at],
                            ];
                        @endphp

                        @foreach($steps as $step)
                        <div class="col-md-4 col-6">
                            <div class="d-flex align-items-center gap-2 p-3 rounded-3 {{ $step['done'] ? 'bg-success bg-opacity-10 border border-success border-opacity-25' : 'bg-light border border-secondary border-opacity-25' }}">
                                <i class="{{ $step['done'] ? 'ri-checkbox-circle-fill text-success' : 'ri-checkbox-blank-circle-line text-muted' }} fs-18"></i>
                                <div>
                                    <div class="fw-semibold fs-12 {{ $step['done'] ? 'text-success' : 'text-muted' }}">{{ $step['label'] }}</div>
                                    @if($step['done'] && $step['date'])
                                        <div class="text-muted fs-11">{{ \Carbon\Carbon::parse($step['date'])->format('Y/m/d') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- تخصیص پشتیبان --}}
                    @if($trialWeek->status === 'pending')
                    <div class="alert alert-warning d-flex align-items-center gap-3 mb-4">
                        <i class="ri-time-line fs-20"></i>
                        <div>
                            <strong>در انتظار تخصیص پشتیبان</strong>
                            <p class="mb-0 mt-1 fs-12">برای شروع فرایند آزمایشی باید یک پشتیبان آزمایشی تخصیص دهید.</p>
                        </div>
                    </div>

                    <button wire:click="openAssignModal" class="btn btn-primary">
                        <i class="ri-user-add-line me-1"></i>
                        تخصیص پشتیبان آزمایشی
                    </button>
                    @elseif($trialWeek->acquisitionSupporter)
                    <div class="alert alert-success d-flex align-items-center gap-3">
                        <i class="ri-shield-check-line fs-20"></i>
                        <div>
                            <strong>پشتیبان تخصیص یافته: {{ $trialWeek->acquisitionSupporter->name }}</strong>
                            <p class="mb-0 mt-1 fs-12">{{ $trialWeek->acquisitionSupporter->mobile }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- اطلاعات جلسه آزمایشی --}}
                    @if($trialWeek->advisingSession)
                    <div class="mt-4 p-3 border border-info border-opacity-25 rounded-3 bg-info bg-opacity-10">
                        <h6 class="text-info mb-2"><i class="ri-calendar-check-line me-1"></i>جلسه آزمایشی</h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <small class="text-muted">عنوان:</small>
                                <div class="fw-semibold">{{ $trialWeek->advisingSession->title }}</div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">تاریخ:</small>
                                <div class="fw-semibold">{{ optional($trialWeek->advisingSession->activation_date)->format('Y/m/d') }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- مودال تخصیص پشتیبان --}}
    @if($showAssignModal)
    <div class="modal show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);"
         wire:keydown.escape="closeAssignModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-user-add-line me-2"></i>
                        تخصیص پشتیبان آزمایشی
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeAssignModal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">
                        پشتیبان آزمایشی برای <strong>{{ $trialWeek->user->name }}</strong> انتخاب کنید.
                        پس از انتخاب، یک جلسه آزمایشی به‌صورت خودکار ایجاد می‌شود.
                    </p>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">انتخاب پشتیبان</label>
                        <select wire:model="selectedSupporterId" class="form-select">
                            <option value="">-- انتخاب کنید --</option>
                            @foreach($supporters as $supporter)
                                <option value="{{ $supporter->id }}">
                                    {{ $supporter->name }} — {{ $supporter->mobile }}
                                </option>
                            @endforeach
                        </select>
                        @error('selectedSupporterId')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" wire:click="closeAssignModal">انصراف</button>
                    <button type="button"
                            class="btn btn-primary"
                            wire:click="assignSupporter"
                            wire:loading.attr="disabled">
                        <span wire:loading wire:target="assignSupporter"
                              class="spinner-border spinner-border-sm me-1"></span>
                        تخصیص پشتیبان
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
