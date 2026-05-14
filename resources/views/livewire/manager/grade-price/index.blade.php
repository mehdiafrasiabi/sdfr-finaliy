<div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">قیمت‌گذاری بر اساس پایه تحصیلی</h4>
            <button wire:click="openCreate" class="btn btn-primary btn-sm">
                <i class="ri-add-line me-1"></i>ثبت قیمت جدید
            </button>
        </div>

        <div class="card-body">

            {{-- راهنما --}}
            <div class="alert alert-info p-3 mb-4">
                <strong>نحوه محاسبه:</strong>
                قسط ماهانه = (مبلغ کل × (۱ - تخفیف%)) ÷ تعداد ماه
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>پایه</th>
                            <th>رشته</th>
                            <th>نام پلن</th>
                            <th>مبلغ کل</th>
                            <th>تخفیف</th>
                            <th>مبلغ نهایی</th>
                            <th>ماه</th>
                            <th>قسط ماهانه</th>
                            <th>از تاریخ</th>
                            <th>تا تاریخ</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prices as $price)
                        <tr class="{{ !$price->is_active ? 'text-muted opacity-50' : '' }}">
                            <td><span class="badge bg-secondary-subtle text-secondary">{{ $price->gradeLabel }}</span></td>
                            <td>{{ $price->fieldLabel }}</td>
                            <td>{{ $price->label ?? '—' }}</td>
                            <td dir="ltr">{{ number_format($price->total_amount) }}</td>
                            <td>
                                @if($price->discount_percentage > 0)
                                    <span class="badge bg-warning-subtle text-warning">{{ $price->discount_percentage }}٪</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td dir="ltr" class="fw-bold text-success">
                                {{ number_format($price->finalAmount) }}
                            </td>
                            <td>{{ $price->months }} ماه</td>
                            <td dir="ltr" class="fw-bold text-primary">
                                {{ number_format($price->monthlyAmount) }}
                            </td>
                            <td>{{ $price->start_at->format('Y/m/d') }}</td>
                            <td>{{ $price->end_at?->format('Y/m/d') ?? 'تا اطلاع ثانوی' }}</td>
                            <td>
                                <div class="form-check form-switch mb-0">
                                    <input wire:click="toggleActive({{ $price->id }})"
                                           class="form-check-input" type="checkbox"
                                           {{ $price->is_active ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button wire:click="openEdit({{ $price->id }})" class="btn btn-sm btn-soft-primary">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button wire:click="delete({{ $price->id }})"
                                            wire:confirm="آیا مطمئن هستید؟"
                                            class="btn btn-sm btn-soft-danger">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted py-4">هیچ قیمتی ثبت نشده است.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- مودال فرم --}}
    @if($showForm)
    <div class="modal show d-block" tabindex="-1" style="background: rgba(0,0,0,.5);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $editingId ? 'ویرایش قیمت' : 'ثبت قیمت جدید' }}</h5>
                    <button wire:click="closeForm" type="button" class="btn-close"></button>
                </div>
                <div class="modal-body">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">پایه تحصیلی <span class="text-danger">*</span></label>
                            <select wire:model="grade" class="form-select">
                                <option value="9">نهم</option>
                                <option value="10">دهم</option>
                                <option value="11">یازدهم</option>
                                <option value="12">دوازدهم</option>
                            </select>
                            @error('grade') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">رشته</label>
                            <select wire:model="field" class="form-select">
                                <option value="">همه رشته‌ها</option>
                                <option value="math">ریاضی</option>
                                <option value="experimental">تجربی</option>
                                <option value="human">انسانی</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">نام پلن</label>
                            <input wire:model="label" type="text" class="form-control" placeholder="مثال: پلن پایه دهم ریاضی">
                            @error('label') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">مبلغ کل (تومان) <span class="text-danger">*</span></label>
                            <input wire:model.live="totalAmount" type="number" class="form-control" placeholder="مثال: 5000000">
                            @error('totalAmount') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">تخفیف (٪)</label>
                            <input wire:model.live="discountPct" type="number" class="form-control"
                                   min="0" max="100" placeholder="0">
                            @error('discountPct') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">تعداد ماه <span class="text-danger">*</span></label>
                            <input wire:model.live="months" type="number" class="form-control"
                                   min="1" max="36" placeholder="12">
                            @error('months') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                        </div>

                        {{-- پیش‌نمایش محاسبه --}}
                        @if($totalAmount > 0 && $months > 0)
                        <div class="col-12">
                            <div class="alert alert-success p-3">
                                <div class="row text-center">
                                    <div class="col">
                                        <div class="fs-12 text-muted">مبلغ کل</div>
                                        <div class="fw-bold" dir="ltr">{{ number_format($totalAmount) }} ت</div>
                                    </div>
                                    @if($discountPct > 0)
                                    <div class="col">
                                        <div class="fs-12 text-muted">تخفیف {{ $discountPct }}٪</div>
                                        <div class="fw-bold text-danger" dir="ltr">-{{ number_format((int)round($totalAmount * $discountPct / 100)) }} ت</div>
                                    </div>
                                    @endif
                                    <div class="col">
                                        <div class="fs-12 text-muted">مبلغ نهایی</div>
                                        <div class="fw-bold text-success" dir="ltr">{{ number_format((int)round($totalAmount * (1 - $discountPct / 100))) }} ت</div>
                                    </div>
                                    <div class="col">
                                        <div class="fs-12 text-muted">قسط ماهانه</div>
                                        <div class="fw-bold text-primary" dir="ltr">
                                            {{ number_format((int)round($totalAmount * (1 - $discountPct / 100) / max(1, $months))) }} ت
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="fs-12 text-muted">مدت</div>
                                        <div class="fw-bold">{{ $months }} ماه</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">تاریخ شروع <span class="text-danger">*</span></label>
                            <input wire:model="startAt" type="date" class="form-control">
                            @error('startAt') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">تاریخ پایان</label>
                            <input wire:model="endAt" type="date" class="form-control">
                            <small class="text-muted">خالی = تا اطلاع ثانوی</small>
                            @error('endAt') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">وضعیت</label>
                            <div class="form-check form-switch mt-2">
                                <input wire:model="isActive" class="form-check-input" type="checkbox"
                                       id="isActiveCheck">
                                <label class="form-check-label" for="isActiveCheck">
                                    {{ $isActive ? 'فعال' : 'غیرفعال' }}
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button wire:click="closeForm" type="button" class="btn btn-light">انصراف</button>
                    <button wire:click="save" type="button" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i>ذخیره
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
