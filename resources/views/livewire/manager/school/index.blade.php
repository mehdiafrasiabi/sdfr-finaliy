<div>
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">
                {{ $schoolId ? 'ویرایش مدرسه' : 'افزودن مدرسه جدید' }}
            </h4>
            @if ($schoolId)
                <button type="button" wire:click="resetForm" class="btn btn-sm btn-soft-secondary">انصراف از ویرایش</button>
            @endif
        </div>
        <div class="card-body">
            <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">نام مدرسه <sup class="text-danger">*</sup></label>
                        <input type="text" name="name" wire:model="name" class="form-control" placeholder="مثلا: دبیرستان نمونه">
                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">کد مدرسه <sup class="text-danger">*</sup></label>
                        <input type="text" name="code" wire:model="code" class="form-control" placeholder="کد یکتای مدرسه">
                        @error('code') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">تلفن عمومی مدرسه <sup class="text-danger">*</sup></label>
                        <input type="text" name="public_phone" wire:model="public_phone" class="form-control" placeholder="مثلا 02112345678">
                        @error('public_phone') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">آدرس مدرسه <sup class="text-danger">*</sup></label>
                        <textarea name="address" wire:model="address" class="form-control" rows="2"></textarea>
                        @error('address') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <hr>
                <h5 class="mt-2 mb-3">اطلاعات مدیر مدرسه</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">نام و نام خانوادگی مدیر <sup class="text-danger">*</sup></label>
                        <input type="text" name="manager_name" wire:model="manager_name" class="form-control">
                        @error('manager_name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">تلفن تماس مدیر <sup class="text-danger">*</sup></label>
                        <input type="text" name="manager_phone" wire:model="manager_phone" class="form-control" placeholder="مثلا 09121234567">
                        @error('manager_phone') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <hr>
                <h5 class="mt-2 mb-3">اطلاعات معاون آموزشی مدرسه</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">نام و نام خانوادگی معاون <sup class="text-danger">*</sup></label>
                        <input type="text" name="deputy_name" wire:model="deputy_name" class="form-control">
                        @error('deputy_name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">تلفن تماس معاون <sup class="text-danger">*</sup></label>
                        <input type="text" name="deputy_phone" wire:model="deputy_phone" class="form-control" placeholder="مثلا 09121234567">
                        @error('deputy_phone') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <span wire:loading.remove>{{ $schoolId ? 'ذخیره تغییرات' : 'افزودن مدرسه' }}</span>
                        <span wire:loading>در حال ارسال...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">لیست مدارس</h5>
        </div>
        <div class="card-body">
            <div class="row g-4 mb-3">
                <div class="col-sm">
                    <div class="search-box">
                        <input wire:model.live.debounce.500ms="search" type="text"
                               class="form-control search" placeholder="جستجو بر اساس نام یا کد مدرسه">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
            </div>

            <div class="table-responsive table-card">
                <table class="table align-middle table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام مدرسه</th>
                        <th>کد</th>
                        <th>تلفن</th>
                        <th>مدیر</th>
                        <th>معاون</th>
                        <th>پشتیبان‌ها</th>
                        <th>دانش‌آموزان</th>
                        <th>اقدام</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($schools as $school)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $school->name }}</td>
                            <td>{{ $school->code }}</td>
                            <td>{{ $school->public_phone }}</td>
                            <td>{{ $school->manager?->name ?? '---' }}</td>
                            <td>{{ $school->deputy?->name ?? '---' }}</td>
                            <td>{{ $school->supporters_count }}</td>
                            <td>{{ $school->students_count }}</td>
                            <td>
                                <a href="{{ route('manager.schools.show', $school->id) }}"
                                   class="btn btn-sm btn-soft-info">
                                    <i class="ri-eye-line"></i> جزئیات / پشتیبان‌ها
                                </a>
                                <button wire:click="edit({{ $school->id }})"
                                        class="btn btn-sm btn-soft-success">
                                    <i class="ri-pencil-line"></i> ویرایش
                                </button>
                                <button wire:click="delete({{ $school->id }})"
                                        wire:confirm="آیا از حذف این مدرسه اطمینان دارید؟"
                                        class="btn btn-sm btn-soft-danger">
                                    <i class="ri-delete-bin-6-line"></i> حذف
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">هیچ مدرسه‌ای ثبت نشده است.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $schools->links('layouts.manager.pagination') }}
            </div>
        </div>
    </div>
</div>
