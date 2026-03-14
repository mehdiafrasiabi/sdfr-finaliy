<div>
    <div>
        <div>
            <div class="col-xxl-6">
                @if (session()->has('success'))
                    <div class="bg-green text-green p-2 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">مدیریت کد های هدیه</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            <span class="bg-danger text-white p-1 rounded">قبل از اقدام حتما مطالعه شود :</span>
                        <hr class="border-dashed">
                        1-کد هدیه شما باید بصورت کارکتر
                        <span class="text-danger">انگلیسی</span>
                        باشد.
                        <hr>
                        2-اگر نوع هدیه
                        <span class="text-success">برای همه</span>
                        انتخاب شود، همه کاربران می‌توانند از این کد استفاده کنند.
                        اگر
                        <span class="text-danger">یک نفر</span>
                        انتخاب شود، فقط کاربر انتخابی می‌تواند استفاده کند.
                        <hr>
                        3-میزان هدیه به
                        <span class="text-success">تومان</span>
                        وارد شود.
                        </p>
                        <hr class="border-dashed">
                        <div class="live-preview">
                            <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="code" class="form-label">کد هدیه:</label>
                                            <sup style="color: red">*</sup>
                                            <input type="text" wire:model="code" name="code" class="form-control"
                                                   placeholder="مثلا: GIFT2024" id="code">
                                            @error('code') <span
                                                class="text-danger text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="type" class="form-label">نوع هدیه:</label>
                                            <sup style="color: red">*</sup>
                                            <select name="type" wire:model.live="type" class="form-select mb-3">
                                                <option value="for_all">برای همه</option>
                                                <option value="for_one">یک نفر</option>
                                            </select>
                                            @error('type') <span
                                                class="text-danger text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    @if($type === 'for_one')
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="user_id" class="form-label">انتخاب کاربر:</label>
                                                <sup style="color: red">*</sup>
                                                <input type="text" wire:model.live.debounce.500ms="userSearch"
                                                       class="form-control mb-2"
                                                       placeholder="جستجوی نام یا موبایل کاربر...">
                                                <select name="user_id" wire:model="user_id" class="form-select">
                                                    <option value="">انتخاب کنید</option>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}
                                                            ({{ $user->mobile }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('user_id') <span
                                                    class="text-danger text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    @endif
                                    @if($type === 'for_all')
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="usage_limit" class="form-label">تعداد استفاده از کد:</label>
                                                <sup style="color: red">*</sup>
                                                <input type="number" wire:model="usage_limit" name="usage_limit"
                                                       class="form-control"
                                                       placeholder="55 (نفر)" min="1" id="usage_limit">
                                                @error('usage_limit') <span
                                                    class="text-danger text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="amount" class="form-label">میزان هدیه (تومان):</label>
                                            <sup style="color: red">*</sup>
                                            <input type="text" wire:model="amount" name="amount" class="form-control"
                                                   placeholder="مثلا: 50000" id="amount">
                                            @error('amount') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="expires_at" class="form-label">تاریخ انقضا:</label>
                                            <sup style="color: red">*</sup>
                                            <input type="date" wire:model="expires_at" name="expires_at"
                                                   class="form-control"
                                                   id="expires_at">
                                            @error('expires_at') <span
                                                class="text-danger text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">وضعیت کد:</label>
                                        <sup style="color: red">*</sup>
                                        <div class="mb-3">
                                            <div class="form-check form-check-success form-check-inline">
                                                <input class="form-check-input" type="radio" name="is_active"
                                                       id="is_active_true" wire:model="is_active" value="1">
                                                <label class="form-check-label" for="is_active_true">فعال</label>
                                            </div>
                                            <div class="form-check form-check-danger form-check-inline">
                                                <input class="form-check-input" type="radio" name="is_active"
                                                       id="is_active_false" wire:model="is_active" value="0">
                                                <label class="form-check-label" for="is_active_false">غیر فعال</label>
                                            </div>
                                            @error('is_active') <span
                                                class="text-danger text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success">
                                        <span wire:loading.remove wire:target="submit">
                                            {{ $giftcode_id ? 'ویرایش' : 'افزودن' }}
                                        </span>
                                            <span wire:loading wire:target="submit">
                                            <span class="spinner-border spinner-border-sm" role="status"></span>
                                        </span>
                                        </button>
                                        @if($giftcode_id)
                                            <button type="button" wire:click="$set('giftcode_id', null)"
                                                    class="btn btn-secondary">
                                                انصراف
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">لیست کد های هدیه</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4 mb-3">
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <input wire:model.live.debounce.500ms="search" type="text"
                                               class="form-control search" placeholder="جستجو بر اساس کد">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive table-card mb-4">
                            <table class="table nowrap dt-responsive align-middle table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>کد هدیه</th>
                                    <th>نوع هدیه</th>
                                    <th>کاربر</th>
                                    <th>تعداد استفاده</th>
                                    <th>میزان هدیه</th>
                                    <th>تاریخ انقضا</th>
                                    <th>وضعیت</th>
                                    <th>اقدام</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($giftCodes as $giftCode)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info fs-12">{{ $giftCode->code }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $giftCode->type === 'for_all' ? 'primary' : 'warning' }}-subtle text-{{ $giftCode->type === 'for_all' ? 'primary' : 'warning' }}">
                                                {{ $giftCode->type_text }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $giftCode->user ? $giftCode->user->name : '-' }}
                                        </td>
                                        <td>
                                            @if($giftCode->type === 'for_all')
                                                {{ $giftCode->usage_count }} / {{ $giftCode->usage_limit }}
                                            @else
                                                {{ $giftCode->usage_count > 0 ? 'استفاده شده' : 'استفاده نشده' }}
                                            @endif
                                        </td>
                                        <td>{{ number_format($giftCode->amount) }} تومان</td>
                                        <td>{{ jalali($giftCode->expires_at)->format('%d %B %Y') }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $giftCode->status_color }}-subtle text-{{ $giftCode->status_color }}">
                                                {{ $giftCode->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <button wire:click="edit({{ $giftCode->id }})"
                                                    class="btn btn-sm btn-soft-primary">
                                                <i class="ri-edit-line"></i>
                                            </button>
                                            <button wire:click="toggleStatus({{ $giftCode->id }})"
                                                    class="btn btn-sm btn-soft-{{ $giftCode->is_active ? 'warning' : 'success' }}">
                                                <i class="ri-{{ $giftCode->is_active ? 'pause' : 'play' }}-line"></i>
                                            </button>
                                            <button wire:confirm="آیا از حذف این کد هدیه اطمینان دارید؟"
                                                    wire:click="delete({{ $giftCode->id }})"
                                                    class="btn btn-sm btn-soft-danger">
                                                <i class="ri-delete-bin-6-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                                                       trigger="loop"
                                                       colors="primary:#121331,secondary:#08a88a"
                                                       style="width:75px;height:75px"></lord-icon>
                                            <h5 class="mt-2">هیچ کد هدیه‌ای یافت نشد</h5>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $giftCodes->links('layouts.manager.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
