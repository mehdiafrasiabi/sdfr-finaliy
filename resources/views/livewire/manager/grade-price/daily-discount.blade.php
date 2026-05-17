<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('manager.dashboard.analytics') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ route('manager.grade-price.index') }}">قیمت پایه‌ها</a></li>
                <li class="breadcrumb-item active">تخفیف‌های روزانه — {{ $price->grade_label }} / {{ $price->field_label }}</li>
            </ol>
        </nav>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-1">تخفیف‌های روز-خاص</h5>
                    <p class="small text-muted mb-0">
                        قیمت پلکانی ماه جاری: <strong>{{ number_format($price->current_stepped_price) }}</strong> تومان
                        | قیمت پلکانی ماه بعد: <strong>{{ number_format($price->next_month_stepped_price) }}</strong> تومان
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <button wire:click="openCreate" class="btn btn-primary">
                        <i class="ri-add-line me-1"></i> افزودن تخفیف
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>عنوان</th>
                            <th>درصد تخفیف</th>
                            <th>از تاریخ</th>
                            <th>تا تاریخ</th>
                            <th>وضعیت</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($discounts as $d)
                        <tr>
                            <td>{{ $d->id }}</td>
                            <td>{{ $d->label ?? '—' }}</td>
                            <td>{{ $d->discount_percentage }}٪</td>
                            <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($d->starts_on)->format('Y/m/d') }}</td>
                            <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($d->ends_on)->format('Y/m/d') }}</td>
                            <td>
                                @if($d->is_active)
                                    <span class="badge bg-success">فعال</span>
                                @else
                                    <span class="badge bg-secondary">غیرفعال</span>
                                @endif
                            </td>
                            <td class="d-flex gap-2">
                                <button wire:click="openEdit({{ $d->id }})" class="btn btn-sm btn-outline-primary">ویرایش</button>
                                <button wire:click="toggleActive({{ $d->id }})" class="btn btn-sm btn-outline-secondary">
                                    {{ $d->is_active ? 'غیرفعال' : 'فعال' }}
                                </button>
                                <button wire:click="delete({{ $d->id }})"
                                        wire:confirm="آیا مطمئن هستید؟"
                                        class="btn btn-sm btn-outline-danger">حذف</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">تخفیف فعالی وجود ندارد.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($showForm)
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.4)">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editingId ? 'ویرایش' : 'افزودن' }} تخفیف</h5>
                        <button type="button" class="btn-close" wire:click="closeForm"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">عنوان (اختیاری)</label>
                            <input type="text" wire:model="label" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">درصد تخفیف <span class="text-danger">*</span></label>
                            <input type="number" min="1" max="100" wire:model="discountPercentage" class="form-control">
                            @error('discountPercentage')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">از تاریخ <span class="text-danger">*</span></label>
                                <input type="date" wire:model="startsOn" class="form-control">
                                @error('startsOn')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">تا تاریخ <span class="text-danger">*</span></label>
                                <input type="date" wire:model="endsOn" class="form-control">
                                @error('endsOn')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" wire:model="isActive" class="form-check-input" id="da_active">
                            <label class="form-check-label" for="da_active">فعال</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="closeForm">انصراف</button>
                        <button class="btn btn-primary" wire:click="save">ذخیره</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
