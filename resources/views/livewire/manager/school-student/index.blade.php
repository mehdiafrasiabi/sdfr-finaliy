<div>
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h4 class="card-title mb-0 flex-grow-1">
                دانش‌آموزان مدرسهٔ «{{ $school->name }}»
            </h4>
            <a href="{{ route('manager.schools.index') }}" class="btn btn-sm btn-soft-secondary">
                <i class="ri-arrow-right-line"></i> بازگشت به لیست مدارس
            </a>
        </div>
    </div>

    {{-- فرم افزودن/ویرایش دانش‌آموز --}}
    <div class="card mt-3">
        <div class="card-header d-flex align-items-center">
            <h4 class="card-title mb-0 flex-grow-1">
                {{ $studentId ? 'ویرایش دانش‌آموز' : 'افزودن دانش‌آموز جدید' }}
            </h4>
            @if ($studentId)
                <button type="button" wire:click="resetForm" class="btn btn-sm btn-soft-secondary">انصراف از ویرایش</button>
            @endif
        </div>
        <div class="card-body">
            <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">مشاور تحصیلی</label>
                        <select wire:model="form_advisor_id" class="form-select">
                            <option value="">--</option>
                            @foreach($advisors as $adv)
                                <option value="{{ $adv->id }}">{{ $adv->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">اگر مدرسه فقط یک مشاور داشت، خودکار انتخاب می‌شود.</small>
                        @error('form_advisor_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">نام و نام خانوادگی <sup class="text-danger">*</sup></label>
                        <input type="text" name="name" wire:model="name" class="form-control">
                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">کدملی <sup class="text-danger">*</sup></label>
                        <input type="text" name="national_code" wire:model="national_code" class="form-control" maxlength="10">
                        @error('national_code') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">تلفن دانش‌آموز <sup class="text-danger">*</sup></label>
                        <input type="text" name="mobile" wire:model="mobile" class="form-control" placeholder="09xxxxxxxxx">
                        @error('mobile') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">پایه تحصیلی <sup class="text-danger">*</sup></label>
                        <select name="grade" wire:model.live="grade" class="form-select">
                            <option value="">--</option>
                            <option value="9">نهم</option>
                            <option value="10">دهم</option>
                            <option value="11">یازدهم</option>
                            <option value="12">دوازدهم</option>
                        </select>
                        @error('grade') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            رشته تحصیلی @if($grade !== '9')<sup class="text-danger">*</sup>@endif
                        </label>
                        <select name="field" wire:model="field" class="form-select" @if($grade === '9') disabled @endif>
                            <option value="">--</option>
                            <option value="math">ریاضی</option>
                            <option value="experimental">تجربی</option>
                            <option value="human">انسانی</option>
                        </select>
                        @if($grade === '9')
                            <small class="text-muted">برای پایه نهم رشته انتخاب نمی‌شود.</small>
                        @endif
                        @error('field') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">تلفن پدر</label>
                        <input type="text" name="father_mobile" wire:model="father_mobile" class="form-control" placeholder="09xxxxxxxxx">
                        @error('father_mobile') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">تلفن مادر</label>
                        <input type="text" name="mother_mobile" wire:model="mother_mobile" class="form-control" placeholder="09xxxxxxxxx">
                        @error('mother_mobile') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">پیگیر آموزشی <sup class="text-danger">*</sup></label>
                        <select name="educational_pursuer" wire:model="educational_pursuer" class="form-select">
                            <option value="">--</option>
                            <option value="father">پدر</option>
                            <option value="mother">مادر</option>
                        </select>
                        @error('educational_pursuer') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <span wire:loading.remove>{{ $studentId ? 'ذخیره تغییرات' : 'افزودن دانش‌آموز' }}</span>
                        <span wire:loading>در حال ارسال...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ایمپورت اکسل --}}
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">افزودن گروهی از طریق اکسل</h5>
        </div>
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <button type="button" class="btn btn-soft-info" wire:click="downloadSample">
                    <i class="ri-download-line"></i> دانلود نمونه فایل اکسل
                </button>
                <small class="text-muted ms-3">فایل را با ستون‌های نمونه پر کنید و آپلود نمایید.</small>
            </div>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">فایل اکسل (xlsx/xls حداکثر 5MB)</label>
                    <input type="file" wire:model="excel_file" class="form-control" accept=".xlsx,.xls">
                    @error('excel_file') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 d-flex align-items-end mb-3">
                    <button type="button" wire:click="previewImport" class="btn btn-primary w-100" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="previewImport,excel_file">پیش‌نمایش</span>
                        <span wire:loading wire:target="previewImport,excel_file">در حال بارگذاری...</span>
                    </button>
                </div>
            </div>

            @if($advisors->count() === 1)
                <div class="alert alert-info">
                    این مدرسه فقط یک مشاور دارد ({{ $advisors->first()->name }})؛
                    به‌صورت خودکار به همهٔ دانش‌آموزان وارد شده اختصاص داده می‌شود.
                </div>
            @elseif($advisors->count() === 0)
                <div class="alert alert-warning">
                    برای این مدرسه هنوز مشاوری انتخاب نشده. دانش‌آموزان بدون مشاور ثبت می‌شوند
                    و باید بعداً به‌صورت دستی به هر دانش‌آموز مشاور اختصاص دهید.
                </div>
            @else
                <div class="alert alert-warning">
                    این مدرسه {{ $advisors->count() }} مشاور دارد؛ دانش‌آموزان وارد شده بدون مشاور ثبت می‌شوند
                    و باید برای هرکدام به‌صورت دستی مشاور انتخاب کنید.
                </div>
            @endif

            @if($importPreviewReady)
                <hr>
                <h6>پیش‌نمایش ایمپورت</h6>
                <div class="row">
                    <div class="col-md-6">
                        <strong class="text-success">ردیف‌های معتبر: {{ count($importValidRows) }}</strong>
                    </div>
                    <div class="col-md-6">
                        <strong class="text-danger">ردیف‌های نامعتبر: {{ count($importInvalidRows) }}</strong>
                    </div>
                </div>

                @if(count($importInvalidRows))
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered table-sm">
                            <thead>
                            <tr>
                                <th>شماره ردیف</th>
                                <th>نام</th>
                                <th>کدملی</th>
                                <th>موبایل</th>
                                <th>خطاها</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($importInvalidRows as $row)
                                <tr>
                                    <td>{{ $row['row'] }}</td>
                                    <td>{{ $row['data']['name'] ?? '-' }}</td>
                                    <td>{{ $row['data']['national_code'] ?? '-' }}</td>
                                    <td>{{ $row['data']['mobile'] ?? '-' }}</td>
                                    <td>
                                        <ul class="mb-0 small text-danger">
                                            @foreach($row['errors'] as $e)
                                                <li>{{ $e }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="d-flex gap-2 mt-3">
                    <button type="button" wire:click="confirmImport" class="btn btn-success"
                            @if(empty($importValidRows)) disabled @endif>
                        ذخیره {{ count($importValidRows) }} ردیف معتبر
                    </button>
                    <button type="button" wire:click="cancelImport" class="btn btn-soft-secondary">
                        انصراف
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- لیست دانش‌آموزان --}}
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">لیست دانش‌آموزان مدرسه</h5>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <input wire:model.live.debounce.500ms="search" type="text" class="form-control" placeholder="جستجو بر اساس نام، کدملی یا موبایل">
                </div>
            </div>

            <div class="table-responsive table-card">
                <table class="table align-middle table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>نام</th>
                        <th>کدملی</th>
                        <th>موبایل</th>
                        <th>پایه</th>
                        <th>رشته</th>
                        <th>پیگیر</th>
                        <th>مشاور</th>
                        <th>اقدام</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($students as $st)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $st->user?->name ?? '---' }}</td>
                            <td>{{ $st->national_code ?? '---' }}</td>
                            <td>{{ $st->user?->mobile ?? '---' }}</td>
                            <td>{{ $st->grade ?? '---' }}</td>
                            <td>{{ $st->field ?? '---' }}</td>
                            <td>
                                @if($st->educational_pursuer === 'father') پدر ({{ $st->father_mobile }})
                                @elseif($st->educational_pursuer === 'mother') مادر ({{ $st->mother_mobile }})
                                @else --- @endif
                            </td>
                            <td>{{ $st->advisor?->name ?? '— نامشخص —' }}</td>
                            <td>
                                <button wire:click="edit({{ $st->id }})" class="btn btn-sm btn-soft-success">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <button wire:click="delete({{ $st->id }})"
                                        wire:confirm="آیا از حذف این دانش‌آموز اطمینان دارید؟"
                                        class="btn btn-sm btn-soft-danger">
                                    <i class="ri-delete-bin-6-line"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">هیچ دانش‌آموزی یافت نشد.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $students->links('layouts.manager.pagination') }}
            </div>
        </div>
    </div>
</div>
