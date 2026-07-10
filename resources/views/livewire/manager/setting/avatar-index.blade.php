<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">تنظیمات آواتار</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">تنظیمات</a></li>
                        <li class="breadcrumb-item active">آواتارها</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $editingId ? 'ویرایش آواتار' : 'افزودن آواتار جدید' }}</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save" class="row g-3">
                        <div class="col-12">
                            <label class="form-label">عنوان</label>
                            <input type="text" class="form-control" wire:model.defer="title" placeholder="مثال: ستاره پسر 4">
                            @error('title')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">جنسیت</label>
                            <select class="form-select" wire:model.defer="gender">
                                <option value="male">پسر</option>
                                <option value="female">دختر</option>
                            </select>
                            @error('gender')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">ترتیب نمایش</label>
                            <input type="number" min="0" class="form-control" wire:model.defer="sort_order">
                            @error('sort_order')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="avatar-active" wire:model.defer="is_active">
                                <label class="form-check-label" for="avatar-active">فعال باشد</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">تصویر آواتار</label>
                            <input type="file" class="form-control" wire:key="avatar-image-{{ $uploadIteration }}" wire:model="image" accept=".jpg,.jpeg,.png,.webp">
                            @error('image')<span class="text-danger small">{{ $message }}</span>@enderror
                            <div wire:loading wire:target="image" class="small text-muted mt-2">در حال بارگذاری تصویر...</div>
                        </div>

                        <div class="col-12">
                            <div class="border rounded-3 p-3 text-center bg-light-subtle">
                                @if($image)
                                    <img src="{{ $image->temporaryUrl() }}" alt="preview" class="img-fluid rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                                @elseif($editingId)
                                    @php
                                        $editingAvatar = $avatars->firstWhere('id', $editingId);
                                    @endphp
                                    @if($editingAvatar)
                                        <img src="{{ asset(ltrim($editingAvatar->image_path, '/')) }}" alt="{{ $editingAvatar->title }}" class="img-fluid rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                                    @endif
                                @else
                                    <div class="text-muted small">بعد از انتخاب فایل، پیش‌نمایش اینجا نمایش داده می‌شود.</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end">
                            @if($editingId)
                                <button type="button" class="btn btn-light" wire:click="resetForm">انصراف</button>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading.remove wire:target="save,image">{{ $editingId ? 'ذخیره تغییرات' : 'ثبت آواتار' }}</span>
                                <span wire:loading wire:target="save,image">در حال ذخیره...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">لیست آواتارها</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                            <tr>
                                <th>تصویر</th>
                                <th>عنوان</th>
                                <th>جنسیت</th>
                                <th>ترتیب</th>
                                <th>وضعیت</th>
                                <th class="text-end">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($avatars as $avatar)
                                <tr wire:key="avatar-row-{{ $avatar->id }}">
                                    <td>
                                        <img src="{{ asset(ltrim($avatar->image_path, '/')) }}" alt="{{ $avatar->title }}" class="rounded-circle" style="width: 56px; height: 56px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $avatar->title }}</div>
                                        <div class="text-muted small" dir="ltr">{{ $avatar->image_path }}</div>
                                    </td>
                                    <td>{{ $avatar->gender === 'female' ? 'دختر' : 'پسر' }}</td>
                                    <td>{{ $avatar->sort_order }}</td>
                                    <td>
                                        <span class="badge {{ $avatar->is_active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                            {{ $avatar->is_active ? 'فعال' : 'غیرفعال' }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-soft-primary" wire:click="edit({{ $avatar->id }})">ویرایش</button>
                                            <button type="button" class="btn btn-sm btn-soft-danger" wire:click="delete({{ $avatar->id }})" wire:confirm="از حذف این آواتار مطمئن هستی؟">حذف</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">هنوز آواتاری ثبت نشده است.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
