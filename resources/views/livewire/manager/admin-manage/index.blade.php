<div>
    <div>
        <div class="col-xxl-12">
            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible alert-additional fade show" role="alert">
                    <div class="alert-body">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <i class="ri-user-smile-line label-icon"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="alert-heading">ادمین شما با موفقیت ساخته شد!</h5>
                                <p class="mb-0">این رمز را در اختیار هیچکس به جز ادمین مربوطه ندهید!</p>
                            </div>
                        </div>
                    </div>
                    <div class="alert-content">
                        <p class="mb-0">{{ session()->get('message') }}</p>
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">
                        <i class="ri-admin-line me-2"></i>
                        {{ $isEditing ? 'ویرایش ادمین' : 'افزودن ادمین جدید' }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="live-preview">
                        <form
                            wire:submit="{{ $isEditing ? 'update(Object.fromEntries(new FormData($event.target)))' : 'submit(Object.fromEntries(new FormData($event.target)))' }}">
                            {{-- بخش اطلاعات شخصی --}}
                            <div class="card border mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="ri-user-3-line me-2"></i>اطلاعات شخصی</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">نام و نام خانوادگی <sup
                                                        class="text-danger">*</sup></label>
                                                <input type="text" wire:model="name" name="name" class="form-control"
                                                       placeholder="مثلا: مهدی آبان" id="name">
                                                @error('name') <span
                                                    class="text-danger text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">ایمیل <sup
                                                        class="text-danger">*</sup></label>
                                                <input type="email" wire:model="email" name="email" class="form-control"
                                                       placeholder="example@gmail.com" id="email">
                                                @error('email') <span
                                                    class="text-danger text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="mobile" class="form-label">تلفن همراه <sup
                                                        class="text-danger">*</sup></label>
                                                <input type="text" wire:model="mobile" name="mobile"
                                                       class="form-control" placeholder="09123456789" id="mobile">
                                                @error('mobile') <span class="text-danger text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="national_code" class="form-label">کد ملی <sup
                                                        class="text-danger">*</sup></label>
                                                <input type="text" wire:model="national_code" name="national_code"
                                                       class="form-control" id="national_code">
                                                @error('national_code') <span
                                                    class="text-danger text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="postal_code" class="form-label">کد پستی <sup
                                                        class="text-danger">*</sup></label>
                                                <input type="text" wire:model="postal_code" name="postal_code"
                                                       class="form-control" id="postal_code">
                                                @error('postal_code') <span
                                                    class="text-danger text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="address" class="form-label">آدرس محل سکونت <sup
                                                        class="text-danger">*</sup></label>
                                                <textarea wire:model="address" name="address" class="form-control"
                                                          id="address" rows="2"></textarea>
                                                @error('address') <span
                                                    class="text-danger text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3" x-data="{isUploading:false, progress:0, previewUrl: null}"
                                                 x-on:livewire-upload-start="isUploading=true"
                                                 x-on:livewire-upload-finish="isUploading=false"
                                                 x-on:livewire-upload-error="isUploading=false"
                                                 x-on:livewire-upload-progress="progress=$event.detail.progress">
                                                <label for="picture" class="form-label">عکس پروفایل (jpg، png، webp)</label>
                                                @if($isEditing && $editingAdminId)
                                                    @php
                                                        $editingAdmin = \App\Models\Admin::find($editingAdminId);
                                                    @endphp
                                                    @if($editingAdmin && $editingAdmin->picture)
                                                        <div class="mb-2">
                                                            <img src="{{ asset('adminsFile/' . $editingAdmin->id . '/' . $editingAdmin->picture) }}"
                                                                 alt="عکس فعلی"
                                                                 class="rounded-circle"
                                                                 style="width:64px;height:64px;object-fit:cover;border:2px solid #dee2e6;">
                                                            <small class="text-muted d-block mt-1">عکس فعلی – برای تغییر فایل جدید انتخاب کنید</small>
                                                        </div>
                                                    @endif
                                                @endif
                                                <input type="file" wire:model="picture" name="picture"
                                                       class="form-control" id="picture"
                                                       accept="image/jpeg,image/png,image/webp"
                                                       x-on:change="
                                                           const file = $event.target.files[0];
                                                           if(file){ previewUrl = URL.createObjectURL(file); }
                                                       ">
                                                <div x-show="previewUrl" class="mt-2">
                                                    <img x-bind:src="previewUrl" alt="پیش‌نمایش"
                                                         class="rounded-circle"
                                                         style="width:64px;height:64px;object-fit:cover;border:2px solid #dee2e6;">
                                                </div>
                                                <div x-show="isUploading" class="progress mt-2">
                                                    <div
                                                        class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                                        role="progressbar" x-bind:style="`width:${progress}%`"></div>
                                                </div>
                                                @error('picture') <span
                                                    class="text-danger text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- بخش آپلود فایل --}}
                            <div class="card border mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="ri-file-upload-line me-2"></i>مدارک</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3" x-data="{isUploading:false, progress:0}"
                                                 x-on:livewire-upload-start="isUploading=true"
                                                 x-on:livewire-upload-finish="isUploading=false"
                                                 x-on:livewire-upload-error="isUploading=false"
                                                 x-on:livewire-upload-progress="progress=$event.detail.progress">
                                                <label for="document" class="form-label">مدرک تحصیلی (pdf یا تصویر) <sup
                                                        class="text-danger">*</sup></label>
                                                <input type="file" wire:model="document" name="document"
                                                       class="form-control" id="document"
                                                       accept="application/pdf,image/*">
                                                <div x-show="isUploading" class="progress mt-2">
                                                    <div
                                                        class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                                        role="progressbar" x-bind:style="`width:${progress}%`"></div>
                                                </div>
                                                @error('document') <span
                                                    class="text-danger text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3" x-data="{isUploading:false, progress:0}"
                                                 x-on:livewire-upload-start="isUploading=true"
                                                 x-on:livewire-upload-finish="isUploading=false"
                                                 x-on:livewire-upload-error="isUploading=false"
                                                 x-on:livewire-upload-progress="progress=$event.detail.progress">
                                                <label for="contract" class="form-label">قرارداد طرفین (pdf) <sup
                                                        class="text-danger">*</sup></label>
                                                <input type="file" wire:model="contract" name="contract"
                                                       class="form-control" id="contract" accept="application/pdf">
                                                <div x-show="isUploading" class="progress mt-2">
                                                    <div
                                                        class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                                        role="progressbar" x-bind:style="`width:${progress}%`"></div>
                                                </div>
                                                @error('contract') <span
                                                    class="text-danger text-sm">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- بخش نقش‌ها --}}
                            <div class="card border mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="ri-shield-user-line me-2"></i>انتخاب نقش</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($rolesWithPersianName as $role)
                                            <div class="col-md-3 col-sm-4 col-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                           wire:model="selectedRoles"
                                                           value="{{ $role['id'] }}"
                                                           id="role_{{ $role['id'] }}">
                                                    <label class="form-check-label" for="role_{{ $role['id'] }}">
                                                        {{ $role['persian_name'] }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('selectedRoles') <span
                                        class="text-danger text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            {{-- بخش دسترسی‌ها --}}
                            <div class="card border mb-4">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0"><i class="ri-lock-unlock-line me-2"></i>مدیریت دسترسی‌ها</h6>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-success"
                                                wire:click="selectAllPermissions">
                                            <i class="ri-checkbox-multiple-line me-1"></i>انتخاب همه
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary"
                                                wire:click="deselectAllPermissions">
                                            <i class="ri-checkbox-multiple-blank-line me-1"></i>عدم انتخاب همه
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="accordion" id="permissionAccordion">
                                        @foreach($permissionGroups as $index => $group)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}"
                                                            type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapse_{{ $index }}">
                                                        <div class="d-flex align-items-center w-100">
                                                            <span class="flex-grow-1">
                                                                <i class="ri-folder-line me-2"></i>
                                                                {{ $group['group_name'] }}
                                                                <span
                                                                    class="badge bg-primary ms-2">{{ count($group['permissions']) }}</span>
                                                            </span>
                                                            <div class="form-check me-3"
                                                                 onclick="event.stopPropagation();">
                                                                <input class="form-check-input" type="checkbox"
                                                                       wire:click="toggleGroupPermissions({{ $index }})"
                                                                       @if($this->isGroupFullySelected($index)) checked
                                                                       @endif
                                                                       id="group_check_{{ $index }}">
                                                                <label class="form-check-label small"
                                                                       for="group_check_{{ $index }}">
                                                                    انتخاب همه
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse_{{ $index }}"
                                                     class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                                     data-bs-parent="#permissionAccordion">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            @foreach($group['permissions'] as $permission)
                                                                <div class="col-md-4 col-sm-6 mb-2">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox"
                                                                               wire:model="selectedPermissions"
                                                                               value="{{ $permission['id'] }}"
                                                                               id="perm_{{ $permission['id'] }}">
                                                                        <label class="form-check-label"
                                                                               for="perm_{{ $permission['id'] }}">
                                                                            {{ $permission['persian_name'] }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('selectedPermissions') <span
                                        class="text-danger text-sm d-block mt-2">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            {{-- دکمه‌های ثبت --}}
                            <div class="text-end">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <span wire:loading.remove>
                                        <i class="ri-save-line me-1"></i>
                                        {{ $isEditing ? 'بروزرسانی' : 'افزودن ادمین' }}
                                    </span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-1"></span>
                                        در حال پردازش...
                                    </span>
                                </button>
                                @if($isEditing)
                                    <button type="button" class="btn btn-secondary btn-lg me-2" wire:click="cancelEdit">
                                        <i class="ri-close-line me-1"></i>انصراف
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- لیست ادمین‌ها --}}
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="ri-team-line me-2"></i>لیست ادمین‌ها و پشتیبانان
                    </h5>
                    <div class="search-box" style="width: 300px;">
                        <input wire:model.live.debounce.500ms="search" type="text" class="form-control"
                               placeholder="جستجو بر اساس نام، ایمیل یا موبایل...">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">ردیف</th>
                                <th>نام و نام خانوادگی</th>
                                <th>اطلاعات تماس</th>
                                <th>نقش‌ها</th>
                                <th>تعداد دسترسی</th>
                                <th style="width: 200px;">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($admins as $admin)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2">
                                                @if($admin->picture)
                                                    <img src="{{ asset('adminsFile/' . $admin->id . '/' . $admin->picture) }}"
                                                         alt="{{ $admin->name }}"
                                                         class="rounded-circle"
                                                         style="width:32px;height:32px;object-fit:cover;">
                                                @else
                                                <span
                                                        class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                        {{ mb_substr($admin->name, 0, 1) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="fw-medium">{{ $admin->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div><i class="ri-mail-line me-1 text-muted"></i>{{ $admin->email }}</div>
                                        <div><i class="ri-phone-line me-1 text-muted"></i>{{ $admin->mobile }}</div>
                                    </td>
                                    <td>
                                        @foreach($admin->roles as $role)
                                            <span class="badge bg-info-subtle text-info mb-1">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $permCount = $admin->getAllPermissions()->count();
                                        @endphp
                                        <span class="badge bg-{{ $permCount > 0 ? 'success' : 'secondary' }}">
                                                {{ $permCount }} دسترسی
                                            </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button wire:click="edit({{ $admin->id }})"
                                                    class="btn btn-sm btn-soft-primary" title="ویرایش">
                                                <i class="ri-pencil-line"></i> ویرایش
                                            </button>
                                            <button wire:confirm="آیا از حذف این ادمین اطمینان دارید؟"
                                                    wire:click="delete({{ $admin->id }})"
                                                    class="btn btn-sm btn-soft-danger" title="حذف">
                                                <i class="ri-delete-bin-line"></i> حذف
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ri-user-unfollow-line" style="font-size: 3rem;"></i>
                                            <h5 class="mt-3">هیچ ادمینی یافت نشد</h5>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $admins->links('layouts.manager.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
