<div>
    <div class="row g-4">
        <div class="col-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h4 class="mb-1 fw-bold">
                        <i class="material-symbols-outlined align-middle ms-1">phone_in_talk</i>
                        گزارش تماس‌ها
                    </h4>
                    <p class="text-body-secondary small mb-0">این صفحه فقط تماس‌های ثبت‌شده از فرایند جلسات مشاوره را نمایش می‌دهد.</p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row g-3">
                <div class="col-6 col-xl-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-body-secondary small">تماس موفق</div>
                                    <div class="fs-3 fw-bold text-success">{{ number_format($dashboard['successful']) }}</div>
                                </div>
                                <span class="badge bg-success-subtle text-success p-2">
                                    <i class="material-symbols-outlined">check_circle</i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-xl-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-body-secondary small">تماس ناموفق</div>
                                    <div class="fs-3 fw-bold text-danger">{{ number_format($dashboard['unsuccessful']) }}</div>
                                </div>
                                <span class="badge bg-danger-subtle text-danger p-2">
                                    <i class="material-symbols-outlined">phone_missed</i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-xl-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-body-secondary small">تماس اتمام حجت</div>
                                    <div class="fs-3 fw-bold text-warning">{{ number_format($dashboard['final_confirmation']) }}</div>
                                </div>
                                <span class="badge bg-warning-subtle text-warning p-2">
                                    <i class="material-symbols-outlined">verified_user</i>
                                </span>
                            </div>
                            @if($dashboard['makeup'] > 0)
                                <div class="small text-body-secondary mt-1">{{ number_format($dashboard['makeup']) }} تماس جبرانی</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-6 col-xl-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-body-secondary small">کل دقیقه تماس</div>
                                    <div class="fs-3 fw-bold text-primary">
                                        {{ number_format($dashboard['talk_minutes']) }}
                                        <span class="fs-6 fw-normal">دقیقه</span>
                                    </div>
                                </div>
                                <span class="badge bg-primary-subtle text-primary p-2">
                                    <i class="material-symbols-outlined">timer</i>
                                </span>
                            </div>
                            @if($dashboard['talk_seconds_remainder'] > 0)
                                <div class="small text-body-secondary mt-1">{{ $dashboard['talk_seconds_remainder'] }} ثانیه اضافه</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row g-3">
                <div class="col-12 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">برقراری تماس</h6>
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                <span class="text-body-secondary">کل تماس‌ها</span>
                                <strong>{{ number_format($dashboard['total']) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                <span class="text-body-secondary">برقرار شده</span>
                                <strong class="text-success">{{ number_format($dashboard['connected']) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-body-secondary">برقرار نشده</span>
                                <strong class="text-danger">{{ number_format($dashboard['not_connected']) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-8">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                <h6 class="fw-bold mb-0">ساعت‌های پاسخگویی دانش‌آموزان</h6>
                                <span class="badge bg-info-subtle text-info">میانگین پاسخ: {{ $dashboard['average_response_time'] }}</span>
                            </div>

                            @if($dashboard['top_response_hours']->isNotEmpty())
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @foreach($dashboard['top_response_hours'] as $row)
                                        <span class="badge bg-light text-dark border">
                                            {{ $row['label'] }} - {{ $row['count'] }} پاسخ
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="row g-2">
                                @foreach($dashboard['response_hours'] as $row)
                                    <div class="col-6 col-md-4 col-xl-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="small text-body-secondary" style="width:42px" dir="ltr">{{ $row['label'] }}</span>
                                            <div class="progress flex-grow-1" style="height:8px">
                                                <div class="progress-bar bg-info" style="width: {{ $row['percent'] }}%"></div>
                                            </div>
                                            <span class="small fw-semibold" style="width:24px">{{ $row['count'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body py-2">
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-md-5">
                            <div class="position-relative">
                                <span class="position-absolute top-50 translate-middle-y ms-2 text-body-secondary">
                                    <i class="material-symbols-outlined" style="font-size:20px;">search</i>
                                </span>
                                <input type="text" wire:model.live.debounce.350ms="search"
                                       class="form-control form-control-sm ps-5"
                                       placeholder="جستجو در دانش‌آموز، عنوان یا توضیحات...">
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <select wire:model.live="filterStudent" class="form-select form-select-sm">
                                <option value="">همه دانش‌آموزان</option>
                                @foreach($students as $st)
                                    <option value="{{ $st->id }}">
                                        {{ $st->user?->personalInformation?->name ?? $st->user?->name ?? 'نامشخص' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6 col-md-3">
                            <select wire:model.live="filterStatus" class="form-select form-select-sm">
                                <option value="">همه وضعیت‌ها</option>
                                <option value="successful">موفق</option>
                                <option value="unsuccessful">ناموفق</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-1">
                            <button type="button" class="btn btn-outline-secondary btn-sm w-100"
                                    wire:click="clearFilters">
                                پاک
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="text-nowrap">#</th>
                                <th class="text-nowrap">دانش‌آموز</th>
                                <th class="text-nowrap">عنوان</th>
                                <th class="text-nowrap">وضعیت</th>
                                <th class="text-nowrap">ارتباط</th>
                                <th class="text-nowrap">مدت</th>
                                <th class="text-nowrap">پاسخگو</th>
                                <th class="text-nowrap">تاریخ تماس</th>
                                <th class="text-nowrap">ساعت پاسخ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($records as $rec)
                                <tr>
                                    <td class="text-nowrap">{{ $loop->iteration + $records->firstItem() - 1 }}</td>
                                    <td class="text-nowrap fw-semibold">
                                        {{ $rec->student?->user?->personalInformation?->name ?? $rec->student?->user?->name ?? 'نامشخص' }}
                                    </td>
                                    <td>
                                        <div class="fw-semibold d-flex align-items-center gap-2">
                                            {{ $rec->title }}
                                            @if($rec->is_final_confirmation)
                                                <span class="badge bg-info-subtle text-info">اتمام حجت</span>
                                            @endif
                                            @if(str_contains((string) $rec->title, 'جبرانی'))
                                                <span class="badge bg-warning text-dark">جبرانی</span>
                                            @endif
                                        </div>
                                        @if($rec->description)
                                            <div class="small text-body-secondary">{{ Str::limit($rec->description, 80) }}</div>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        @if($rec->contact_status === 'successful')
                                            <span class="badge text-bg-success">موفق</span>
                                        @else
                                            <span class="badge text-bg-danger">ناموفق</span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        @if($rec->connected)
                                            <span class="badge bg-success-subtle text-success">برقرار شد</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">
                                                برقرار نشد
                                                @if($rec->fail_reason)
                                                    - {{ \App\Models\ContactDocumentation::FAIL_LABELS[$rec->fail_reason] ?? $rec->fail_reason }}
                                                @endif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap" dir="ltr">{{ $rec->talk_duration_label }}</td>
                                    <td class="text-nowrap">{{ $rec->respondents_label }}</td>
                                    <td class="text-nowrap">{{ jalali($rec->contact_date)->format('%d %B %Y') }}</td>
                                    <td class="text-nowrap" dir="ltr">
                                        {{ $rec->answered_at ? $rec->answered_at->format('H:i') : '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <i class="material-symbols-outlined d-block mb-2" style="font-size:48px;color:#ccc;">phone_missed</i>
                                        <h5 class="text-body-secondary">هیچ تماسی یافت نشد</h5>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $records->links('layouts.admin.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
