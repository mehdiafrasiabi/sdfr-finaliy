<div>
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="card-title mb-0">پنل پشتیبان جذب سایت</h4>
                    <p class="text-muted fs-13 mt-1 mb-0">دانش‌آموزان تخصیص‌یافته به شما</p>
                </div>
                <span class="badge bg-warning-subtle text-warning fs-12">پشتیبان جذب سایت</span>
            </div>

            <div class="card-body">

                {{-- فیلترها --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <div class="search-box">
                            <input wire:model.live.debounce.400ms="search"
                                   type="text" class="form-control search"
                                   placeholder="جستجو بر اساس نام یا موبایل...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select wire:model.live="statusFilter" class="form-select">
                            <option value="all">همه وضعیت‌ها</option>
                            <option value="pending">در انتظار تخصیص</option>
                            <option value="supporter_assigned">تخصیص یافته</option>
                            <option value="classification_done">طبقه‌بندی تکمیل</option>
                            <option value="pre_session_done">پیش‌جلسه تکمیل</option>
                            <option value="program_built">برنامه ساخته شده</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check form-switch mt-2">
                            <input wire:model.live="inactiveOnly"
                                   class="form-check-input" type="checkbox"
                                   id="inactiveOnly" role="switch">
                            <label class="form-check-label text-danger fw-semibold" for="inactiveOnly">
                                <i class="ri-alarm-warning-line me-1"></i>فقط غیرفعال (۳+ روز)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>دانش‌آموز</th>
                                <th>پایه / رشته</th>
                                <th>شماره پدر / مادر</th>
                                <th>تماس‌ها</th>
                                <th>وضعیت</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($trials as $trial)
                            @php
                                $hasInitial       = $trial->acquisitionContacts->where('type','initial')->isNotEmpty();
                                $hasSecondary     = $trial->acquisitionContacts->where('type','secondary')->isNotEmpty();
                                $hasSupplementary = $trial->acquisitionContacts->where('type','supplementary')->isNotEmpty();
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration + $trials->firstItem() - 1 }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $trial->user->name ?? '—' }}</div>
                                    <div class="text-muted fs-12" dir="ltr">{{ $trial->user->mobile ?? '—' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary">{{ $trial->gradeLabel }}</span>
                                    @if($trial->field)
                                        <span class="badge bg-info-subtle text-info ms-1">{{ $trial->fieldLabel }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fs-12" dir="ltr">
                                        <i class="ri-phone-line text-muted me-1"></i>{{ $trial->father_mobile }}
                                    </div>
                                    <div class="fs-12" dir="ltr">
                                        <i class="ri-phone-line text-muted me-1"></i>{{ $trial->mother_mobile }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <span class="badge {{ $hasInitial ? 'bg-success' : 'bg-secondary-subtle text-secondary' }}"
                                              title="تماس اولیه">
                                            <i class="ri-phone-fill me-1"></i>اولیه
                                        </span>
                                        <span class="badge {{ $hasSecondary ? 'bg-primary' : 'bg-secondary-subtle text-secondary' }}"
                                              title="تماس ثانویه">
                                            <i class="ri-phone-fill me-1"></i>ثانویه
                                        </span>
                                        <span class="badge {{ $hasSupplementary ? 'bg-warning text-dark' : 'bg-secondary-subtle text-secondary' }}"
                                              title="تماس جانبی">
                                            <i class="ri-phone-fill me-1"></i>جانبی
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusBadge = match($trial->status) {
                                            'pending'              => 'badge-soft-secondary',
                                            'supporter_assigned'   => 'badge-soft-info',
                                            'classification_done'  => 'badge-soft-primary',
                                            'pre_session_done'     => 'badge-soft-warning',
                                            'program_built'        => 'badge-soft-success',
                                            default                => 'badge-soft-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusBadge }}">{{ $trial->statusLabel }}</span>
                                    @if($trial->isExpired())
                                        <span class="badge badge-soft-danger ms-1">منقضی</span>
                                    @elseif($trial->daysRemaining <= 2)
                                        <span class="badge badge-soft-warning ms-1">{{ $trial->daysRemaining }} روز مانده</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.acquisition-supporter.student', $trial->id) }}"
                                       class="btn btn-sm btn-soft-primary">
                                        <i class="ri-eye-line me-1"></i>جزئیات
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="ri-user-search-line display-6 d-block mb-2"></i>
                                    هیچ دانش‌آموزی یافت نشد.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $trials->links() }}

            </div>
        </div>
    </div>
</div>
