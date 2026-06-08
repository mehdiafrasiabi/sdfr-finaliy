<div>
    <div class="card">
        <div class="card-header align-items-center d-flex flex-wrap gap-2">
            <h4 class="card-title mb-0 flex-grow-1">درخواست‌های همکاری مدارس</h4>
            <div class="search-box">
                <input type="text" wire:model.live.debounce.400ms="search" class="form-control"
                       placeholder="جستجو بر اساس نام، مدرسه یا تلفن...">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive table-card">
                <table class="table align-middle table-hover table-bordered mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>ردیف</th>
                        <th>نام و نام خانوادگی</th>
                        <th>نام مدرسه</th>
                        <th>شماره تلفن</th>
                        <th>تعداد دانش‌آموز</th>
                        <th>استان</th>
                        <th>شهر</th>
                        <th>پک</th>
                        <th>هزینه قابل پرداخت (تومان)</th>
                        <th>سود و تخفیف (تومان)</th>
                        <th>تاریخ ثبت</th>
                        <th>وضعیت</th>
                        <th>اقدام</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($requests as $request)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $request->full_name }}</td>
                            <td>{{ $request->school_name ?? '---' }}</td>
                            <td dir="ltr" class="text-end">{{ $request->mobile }}</td>
                            <td>{{ number_format($request->student_count) }}</td>
                            <td>{{ $request->state?->name ?? '---' }}</td>
                            <td>{{ $request->city?->name ?? '---' }}</td>
                            <td><span class="badge bg-primary-subtle text-primary">{{ $request->package ?? '---' }}</span></td>
                            <td>{{ number_format($request->payable_amount) }}</td>
                            <td class="text-success">{{ number_format($request->discount_amount) }}</td>
                            <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($request->created_at)->format('Y/m/d H:i') }}</td>
                            <td>
                                @if($request->is_reviewed)
                                    <span class="badge bg-success-subtle text-success">بررسی شده</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">در انتظار</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button wire:click="toggleReviewed({{ $request->id }})"
                                            class="btn btn-sm btn-soft-success" title="تغییر وضعیت بررسی">
                                        <i class="ri-check-double-line"></i>
                                    </button>
                                    <button wire:click="delete({{ $request->id }})"
                                            wire:confirm="آیا از حذف این درخواست اطمینان دارید؟"
                                            class="btn btn-sm btn-soft-danger" title="حذف">
                                        <i class="ri-delete-bin-6-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center text-muted">هیچ درخواست همکاری ثبت نشده است.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $requests->links('layouts.manager.pagination') }}
            </div>
        </div>
    </div>
</div>
