<div>
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">دانش‌آموزان آزمایشی من</h4>
                <span class="badge bg-info">پشتیبان آزمایشی</span>
            </div>
            <div class="card-body">

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="search-box">
                            <input wire:model.live.debounce.400ms="search"
                                   type="text" class="form-control search"
                                   placeholder="جستجو بر اساس نام یا موبایل...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select wire:model.live="statusFilter" class="form-select">
                            <option value="all">همه وضعیت‌ها</option>
                            <option value="supporter_assigned">در انتظار طبقه‌بندی</option>
                            <option value="classification_done">طبقه‌بندی تکمیل — منتظر پیش‌جلسه</option>
                            <option value="pre_session_done">پیش‌جلسه تکمیل</option>
                            <option value="program_built">برنامه ساخته شده</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>دانش‌آموز</th>
                                <th>پایه / رشته</th>
                                <th>تلفن والدین</th>
                                <th>وضعیت</th>
                                <th>روزهای باقی‌مانده</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($trials as $trial)
                            <tr>
                                <td>{{ $loop->iteration + $trials->firstItem() - 1 }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $trial->user->name ?? '—' }}</div>
                                    <div class="text-muted fs-12">{{ $trial->user->mobile ?? '—' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $trial->gradeLabel }}</span>
                                    @if($trial->field)
                                        <span class="badge bg-info-subtle text-info ms-1">{{ $trial->fieldLabel }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fs-12" dir="ltr">پدر: {{ $trial->father_mobile }}</div>
                                    <div class="fs-12" dir="ltr">مادر: {{ $trial->mother_mobile }}</div>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match($trial->status) {
                                            'supporter_assigned' => 'badge-soft-info',
                                            'classification_done' => 'badge-soft-primary',
                                            'pre_session_done' => 'badge-soft-warning',
                                            'program_built' => 'badge-soft-success',
                                            default => 'badge-soft-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $trial->statusLabel }}</span>
                                </td>
                                <td>
                                    @if($trial->isExpired())
                                        <span class="text-danger fw-semibold">منقضی</span>
                                    @else
                                        <span class="text-success fw-semibold">{{ $trial->daysRemaining }} روز</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        {{-- تکمیل پیش‌جلسه --}}
                                        @if($trial->status === 'classification_done')
                                            <button wire:click="markPreSessionDone({{ $trial->id }})"
                                                    wire:loading.attr="disabled"
                                                    wire:confirm="آیا مطمئنید که پیش‌جلسه این دانش‌آموز تکمیل شده است؟"
                                                    class="btn btn-sm btn-warning">
                                                <i class="ri-checkbox-circle-line me-1"></i>
                                                تکمیل پیش‌جلسه
                                            </button>
                                        @endif

                                        {{-- مشاهده جلسه --}}
                                        @if($trial->advising_session_id)
                                            <a href="{{ route('admin.advising-sessions', optional($trial->student)->id) }}"
                                               class="btn btn-sm btn-info">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="ri-inbox-line fs-2 d-block mb-2"></i>
                                    هیچ دانش‌آموز آزمایشی یافت نشد
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
