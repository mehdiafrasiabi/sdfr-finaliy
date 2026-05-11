<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="card-title mb-0">هفته‌های آزمایشی</h4>
                    <span class="badge bg-info fs-12">
                        مدیریت درخواست‌های آزمایشی
                    </span>
                </div>
                <div class="card-body">

                    {{-- فیلترها --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="search-box">
                                <input wire:model.live.debounce.400ms="search"
                                       type="text"
                                       class="form-control search"
                                       placeholder="جستجو بر اساس نام یا موبایل...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select wire:model.live="statusFilter" class="form-select">
                                <option value="all">همه وضعیت‌ها</option>
                                <option value="pending">در انتظار تایید</option>
                                <option value="supporter_assigned">پشتیبان تخصیص یافته</option>
                                <option value="classification_done">طبقه‌بندی تکمیل</option>
                                <option value="pre_session_done">پیش‌جلسه تکمیل</option>
                                <option value="program_built">برنامه ساخته شده</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>کاربر</th>
                                    <th>پایه / رشته</th>
                                    <th>پشتیبان</th>
                                    <th>وضعیت</th>
                                    <th>انقضا</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trials as $trial)
                                <tr>
                                    <td>{{ $loop->iteration + $trials->firstItem() - 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-xs bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="ri-user-line text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $trial->user->name ?? '—' }}</div>
                                                <div class="text-muted fs-12">{{ $trial->user->mobile ?? '—' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary fs-11">{{ $trial->gradeLabel }}</span>
                                        @if($trial->field)
                                            <span class="badge bg-info-subtle text-info fs-11 ms-1">{{ $trial->fieldLabel }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($trial->supporter)
                                            <span class="text-success fw-semibold">{{ $trial->supporter->name }}</span>
                                        @else
                                            <span class="text-warning">
                                                <i class="ri-time-line me-1"></i>
                                                در انتظار
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($trial->status) {
                                                'pending' => 'badge-soft-warning',
                                                'supporter_assigned' => 'badge-soft-info',
                                                'classification_done' => 'badge-soft-primary',
                                                'pre_session_done' => 'badge-soft-secondary',
                                                'program_built' => 'badge-soft-success',
                                                default => 'badge-soft-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} fs-11">{{ $trial->statusLabel }}</span>
                                    </td>
                                    <td>
                                        @if($trial->expires_at)
                                            <span class="{{ $trial->isExpired() ? 'text-danger' : 'text-success' }} fs-12">
                                                @if($trial->isExpired())
                                                    <i class="ri-error-warning-line me-1"></i>منقضی
                                                @else
                                                    {{ $trial->daysRemaining }} روز مانده
                                                @endif
                                            </span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('manager.trial-week.detail', $trial->id) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="ri-eye-line me-1"></i>
                                            مشاهده
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="ri-inbox-line fs-2 d-block mb-2"></i>
                                        هیچ درخواست آزمایشی یافت نشد
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $trials->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
