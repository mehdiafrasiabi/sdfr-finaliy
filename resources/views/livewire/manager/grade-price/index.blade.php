<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h4 class="card-title mb-0">قیمت‌گذاری پایه‌های تحصیلی</h4>
                    <button wire:click="openCreate" class="btn btn-primary">
                        <i class="ri-add-line align-middle me-1"></i>
                        افزودن قیمت جدید
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap">
                            <thead class="table-light">
                            <tr>
                                <th>پایه تحصیلی</th>
                                <th>قیمت کل</th>
                                <th>تخفیف ثابت</th>
                                <th>تاریخ شروع</th>
                                <th>تاریخ پایان</th>
                                <th>عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($grades as $grade)
                                @php $pricing = $activePricings[$grade->id] ?? null; @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $grade->name }}</strong>
                                        @if($grade->educationLevel)
                                            <small class="text-muted d-block">{{ $grade->educationLevel->name }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pricing)
                                            {{ number_format($pricing->total_price) }} تومان
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pricing && $pricing->discount_amount > 0)
                                            {{ number_format($pricing->discount_amount) }} تومان
                                        @else
                                            <span class="text-muted">۰</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pricing)
                                            {{ jalali($pricing->starts_on)->format('%d %B %Y') }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pricing)
                                            {{ jalali($pricing->ends_on)->format('%d %B %Y') }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($pricing)
                                            <button wire:click="edit({{ $pricing->id }})" class="btn btn-sm btn-soft-primary">ویرایش</button>
                                            <button wire:click="deactivate({{ $pricing->id }})" wire:confirm="غیرفعال شود؟" class="btn btn-sm btn-soft-danger">غیرفعال‌سازی</button>
                                        @else
                                            <button wire:click="openCreate({{ $grade->id }})" class="btn btn-sm btn-soft-success">تنظیم قیمت</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($showForm)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5)">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form wire:submit.prevent="save">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'ویرایش قیمت' : 'افزودن قیمت جدید' }}</h5>
                            <button type="button" wire:click="close" class="btn-close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">پایه تحصیلی</label>
                                <select wire:model="cc_grade_id" class="form-select">
                                    <option value="">انتخاب پایه...</option>
                                    @foreach($grades as $g)
                                        <option value="{{ $g->id }}">{{ $g->name }} - {{ $g->educationLevel?->name }}</option>
                                    @endforeach
                                </select>
                                @error('cc_grade_id') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">قیمت کل دوره (تومان)</label>
                                <input wire:model="total_price" type="number" min="0" class="form-control">
                                @error('total_price') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">تخفیف ثابت پایه (تومان)</label>
                                <input wire:model="discount_amount" type="number" min="0" class="form-control">
                                @error('discount_amount') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">تاریخ شروع (میلادی)</label>
                                    <input wire:model="starts_on" type="date" class="form-control">
                                    @error('starts_on') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">تاریخ پایان (میلادی)</label>
                                    <input wire:model="ends_on" type="date" class="form-control">
                                    @error('ends_on') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="alert alert-info">
                                ماهانه بر اساس ماه شمسی محاسبه می‌شود: قیمت کل تقسیم بر تعداد ماه‌های شمسی دوره، و به ازای هر ماه گذشته از تاریخ شروع از مبلغ کسر می‌شود.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" wire:click="close" class="btn btn-light">انصراف</button>
                            <button type="submit" class="btn btn-primary">ذخیره</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
