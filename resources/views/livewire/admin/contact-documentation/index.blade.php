<div>
    <div class="row g-4">

        {{-- ── هدر صفحه ──────────────────────────────────────────────── --}}
        <div class="col-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <h4 class="mb-0 fw-bold">
                    <i class="material-symbols-outlined align-middle ms-1">phone_in_talk</i>
                    مستندات تماس
                </h4>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button"
                            class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1"
                            wire:click="downloadTemplate">
                        <i class="material-symbols-outlined" style="font-size:18px;">download</i>
                        دانلود قالب اکسل
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm d-inline-flex align-items-center gap-1"
                            wire:click="$set('showImportModal', true)">
                        <i class="material-symbols-outlined" style="font-size:18px;">upload_file</i>
                        ورود از اکسل
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm d-inline-flex align-items-center gap-1"
                            wire:click="exportExcel" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="exportExcel"
                          class="d-inline-flex align-items-center gap-1">
                        <i class="material-symbols-outlined" style="font-size:18px;">table_view</i>
                        خروجی Excel
                    </span>
                        <span wire:loading wire:target="exportExcel">
                        <span class="spinner-border spinner-border-sm"></span>
                    </span>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1"
                            wire:click="exportPdf" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="exportPdf"
                          class="d-inline-flex align-items-center gap-1">
                        <i class="material-symbols-outlined" style="font-size:18px;">picture_as_pdf</i>
                        خروجی PDF
                    </span>
                        <span wire:loading wire:target="exportPdf">
                        <span class="spinner-border spinner-border-sm"></span>
                    </span>
                    </button>
                    <button type="button" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1"
                            wire:click="openCreate">
                        <i class="material-symbols-outlined" style="font-size:18px;">add</i>
                        ثبت مستند جدید
                    </button>
                </div>
            </div>
        </div>

        {{-- ── فیلترها ─────────────────────────────────────────────────── --}}
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
                                       placeholder="جستجو در عنوان و توضیحات...">
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
                                    wire:click="$set('search',''); $set('filterStudent',''); $set('filterStatus','')">
                                پاک
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── جدول مستندات ────────────────────────────────────────────── --}}
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
                                <th class="text-nowrap">وضعیت تماس</th>
                                <th class="text-nowrap">تاریخ تماس</th>
                                <th class="text-nowrap">شخص پاسخگو</th>
                                <th class="text-nowrap">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($records as $rec)
                                <tr>
                                    <td class="text-nowrap">
                                        {{ $loop->iteration + $records->firstItem() - 1 }}
                                    </td>
                                    <td class="text-nowrap fw-semibold">
                                        {{ $rec->student?->user?->personalInformation?->name ?? $rec->student?->user?->name ?? 'نامشخص' }}
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $rec->title }}</div>
                                        @if($rec->description)
                                            <div
                                                class="small text-body-secondary">{{ Str::limit($rec->description, 60) }}</div>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        @if($rec->contact_status === 'successful')
                                            <span class="badge text-bg-success d-inline-flex align-items-center gap-1">
                                            <i class="material-symbols-outlined"
                                               style="font-size:14px;">check_circle</i>
                                            موفق
                                        </span>
                                        @else
                                            <span class="badge text-bg-danger d-inline-flex align-items-center gap-1">
                                            <i class="material-symbols-outlined" style="font-size:14px;">cancel</i>
                                            ناموفق
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-nowrap">
                                        {{ jalali($rec->contact_date)->format('%d %B %Y') }}
                                    </td>
                                    <td class="text-nowrap">
                                        @php
                                            $respondentIcons = [
                                                'father'  => 'man',
                                                'mother'  => 'woman',
                                                'student' => 'school',
                                                'other'   => 'person',
                                            ];
                                        @endphp
                                        <span class="d-inline-flex align-items-center gap-1">
                                        <i class="material-symbols-outlined" style="font-size:16px;">
                                            {{ $respondentIcons[$rec->respondent] ?? 'person' }}
                                        </i>
                                        {{ \App\Models\ContactDocumentation::RESPONDENT[$rec->respondent] ?? $rec->respondent }}
                                    </span>
                                    </td>
                                    <td class="text-nowrap">
                                        <div class="d-flex gap-1">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1"
                                                    wire:click="openEdit({{ $rec->id }})">
                                                <i class="material-symbols-outlined" style="font-size:15px;">edit</i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1"
                                                    wire:click="confirmDelete({{ $rec->id }})">
                                                <i class="material-symbols-outlined" style="font-size:15px;">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="material-symbols-outlined d-block mb-2"
                                           style="font-size:48px;color:#ccc;">phone_missed</i>
                                        <h5 class="text-body-secondary">هیچ مستند تماسی یافت نشد</h5>
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


        {{-- ══════════════════════════════════════════════════════════════
             مودال افزودن / ویرایش مستند تماس
        ══════════════════════════════════════════════════════════════ --}}
        @if($showFormModal)
            <div class="modal fade show d-block" tabindex="-1" role="dialog"
                 style="background:rgba(0,0,0,.5);" wire:click.self="closeFormModal">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title d-flex align-items-center gap-2">
                                <i class="material-symbols-outlined">{{ $editingId ? 'edit' : 'add_call' }}</i>
                                {{ $editingId ? 'ویرایش مستند تماس' : 'ثبت مستند تماس جدید' }}
                            </h5>
                            <button type="button" class="btn-close" wire:click="closeFormModal"></button>
                        </div>

                        <div class="modal-body">
                            <form wire:submit.prevent="save" id="contactDocForm">
                                <div class="row g-3">

                                    {{-- دانش‌آموز --}}
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">
                                            دانش‌آموز <span class="text-danger">*</span>
                                        </label>
                                        <select wire:model="student_id"
                                                class="form-select @error('student_id') is-invalid @enderror">
                                            <option value="">-- انتخاب دانش‌آموز --</option>
                                            @foreach($students as $st)
                                                <option value="{{ $st->id }}">
                                                    {{ $st->user?->personalInformation?->name ?? $st->user?->name ?? 'نامشخص' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('student_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- عنوان --}}
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">
                                            عنوان <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" wire:model="title"
                                               class="form-control @error('title') is-invalid @enderror"
                                               placeholder="عنوان تماس را وارد کنید">
                                        @error('title')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- توضیحات --}}
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">توضیحات</label>
                                        <textarea wire:model="description" rows="3"
                                                  class="form-control @error('description') is-invalid @enderror"
                                                  placeholder="توضیحات تماس..."></textarea>
                                        @error('description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- وضعیت تماس --}}
                                    <div class="col-12 col-md-4">
                                        <label class="form-label fw-semibold">
                                            وضعیت تماس <span class="text-danger">*</span>
                                        </label>
                                        <div class="d-flex gap-3 mt-1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                       wire:model="contact_status" value="successful"
                                                       id="statusSuccess">
                                                <label class="form-check-label text-success fw-semibold"
                                                       for="statusSuccess">
                                                    موفق
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                       wire:model="contact_status" value="unsuccessful"
                                                       id="statusFail">
                                                <label class="form-check-label text-danger fw-semibold"
                                                       for="statusFail">
                                                    ناموفق
                                                </label>
                                            </div>
                                        </div>
                                        @error('contact_status')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- تاریخ تماس --}}
                                    <div class="col-12 col-md-4">
                                        <label class="form-label fw-semibold">
                                            تاریخ تماس <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" wire:model="contact_date"
                                               class="form-control p-date-only @error('contact_date') is-invalid @enderror"
                                               placeholder="مثال: 1403/01/15"
                                               autocomplete="off">
                                        @error('contact_date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- شخص پاسخگو --}}
                                    <div class="col-12 col-md-4">
                                        <label class="form-label fw-semibold">
                                            شخص پاسخگو <span class="text-danger">*</span>
                                        </label>
                                        <select wire:model="respondent"
                                                class="form-select @error('respondent') is-invalid @enderror">
                                            <option value="father">پدر</option>
                                            <option value="mother">مادر</option>
                                            <option value="student">دانش‌آموز</option>
                                            <option value="other">سایر</option>
                                        </select>
                                        @error('respondent')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeFormModal">
                                انصراف
                            </button>
                            <button type="submit" form="contactDocForm"
                                    class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <span wire:loading.remove wire:target="save">
                                <i class="material-symbols-outlined" style="font-size:18px;">save</i>
                                {{ $editingId ? 'ذخیره تغییرات' : 'ثبت مستند' }}
                            </span>
                                <span wire:loading wire:target="save">
                                <span class="spinner-border spinner-border-sm"></span>
                                در حال ثبت...
                            </span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        @endif


        {{-- ══════════════════════════════════════════════════════════════
             مودال حذف
        ══════════════════════════════════════════════════════════════ --}}
        @if($showDeleteModal)
            <div class="modal fade show d-block" tabindex="-1" role="dialog"
                 style="background:rgba(0,0,0,.5);" wire:click.self="$set('showDeleteModal',false)">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title text-danger d-flex align-items-center gap-2">
                                <i class="material-symbols-outlined">warning</i>
                                تأیید حذف
                            </h5>
                            <button type="button" class="btn-close" wire:click="$set('showDeleteModal',false)"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                            <i class="material-symbols-outlined d-block mb-3 text-danger" style="font-size:56px;">delete_forever</i>
                            <p class="mb-0">آیا از حذف این مستند تماس مطمئن هستید؟<br>
                                <span class="text-body-secondary small">این عمل قابل بازگشت نیست.</span>
                            </p>
                        </div>
                        <div class="modal-footer border-0 justify-content-center gap-3">
                            <button type="button" class="btn btn-secondary px-4"
                                    wire:click="$set('showDeleteModal',false)">
                                انصراف
                            </button>
                            <button type="button" class="btn btn-danger px-4" wire:click="delete">
                                <span wire:loading.remove wire:target="delete">بله، حذف شود</span>
                                <span wire:loading wire:target="delete">
                                <span class="spinner-border spinner-border-sm"></span>
                            </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        {{-- ══════════════════════════════════════════════════════════════
             مودال ورود از اکسل
        ══════════════════════════════════════════════════════════════ --}}
        @if($showImportModal)
            <div class="modal fade show d-block" tabindex="-1" role="dialog"
                 style="background:rgba(0,0,0,.5);" wire:click.self="closeImportModal">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title d-flex align-items-center gap-2">
                                <i class="material-symbols-outlined">upload_file</i>
                                ورود مستندات از فایل اکسل
                            </h5>
                            <button type="button" class="btn-close" wire:click="closeImportModal"></button>
                        </div>

                        <div class="modal-body">

                            @if(!$importPreview)
                                {{-- ── مرحله ۱: آپلود فایل ── --}}
                                <div class="alert alert-info d-flex gap-2 align-items-start mb-4">
                                    <i class="material-symbols-outlined mt-1">info</i>
                                    <div>
                                        <strong>راهنمای پر کردن اکسل:</strong><br>
                                        ابتدا قالب اکسل را دانلود کنید، سپس طبق فرمت زیر پر کنید و آپلود نمایید.
                                        <ul class="mb-0 mt-2 small">
                                            <li>ستون A: <strong>نام و نام خانوادگی دانش‌آموز</strong> – دقیقاً مطابق
                                                سیستم
                                            </li>
                                            <li>ستون B: <strong>عنوان</strong> (اجباری)</li>
                                            <li>ستون C: <strong>توضیحات</strong> (اختیاری)</li>
                                            <li>ستون D: <strong>وضعیت تماس</strong> – فقط بنویسید: <code>موفق</code> یا
                                                <code>ناموفق</code></li>
                                            <li>ستون E: <strong>تاریخ تماس</strong> – فرمت شمسی مثل:
                                                <code>1403/01/15</code></li>
                                            <li>ستون F: <strong>شخص پاسخگو</strong> – فقط: <code>پدر</code> /
                                                <code>مادر</code> / <code>دانش‌آموز</code> / <code>سایر</code></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <button type="button" class="btn btn-outline-secondary btn-sm mb-3"
                                            wire:click="downloadTemplate">
                                        <i class="material-symbols-outlined align-middle" style="font-size:16px;">download</i>
                                        دانلود قالب اکسل آماده
                                    </button>
                                </div>

                                <div class="border rounded p-4 text-center bg-body-tertiary">
                                    <i class="material-symbols-outlined d-block mb-2"
                                       style="font-size:48px;color:#6c757d;">upload_file</i>
                                    <label class="form-label fw-semibold">انتخاب فایل اکسل (xlsx, xls, csv)</label>
                                    <input type="file" wire:model="importFile"
                                           class="form-control @error('importFile') is-invalid @enderror"
                                           accept=".xlsx,.xls,.csv">
                                    @error('importFile')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-primary"
                                                wire:click="uploadImport"
                                                wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="uploadImport"
                                              class="d-inline-flex align-items-center gap-1">
                                            <i class="material-symbols-outlined" style="font-size:18px;">preview</i>
                                            پیش‌نمایش داده‌ها
                                        </span>
                                            <span wire:loading wire:target="uploadImport">
                                            <span class="spinner-border spinner-border-sm"></span>
                                            در حال بررسی...
                                        </span>
                                        </button>
                                    </div>
                                </div>

                            @else
                                {{-- ── مرحله ۲: پیش‌نمایش نتایج ── --}}

                                {{-- آمار --}}
                                <div class="row g-3 mb-4">
                                    <div class="col-6 col-md-3">
                                        <div class="border rounded p-3 text-center bg-body-tertiary">
                                            <div
                                                class="fs-3 fw-bold">{{ count($importValid) + count($importInvalid) }}</div>
                                            <div class="small text-body-secondary">کل ردیف‌ها</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="border rounded p-3 text-center" style="background:#d1fae5;">
                                            <div class="fs-3 fw-bold text-success">{{ count($importValid) }}</div>
                                            <div class="small text-success">قابل ثبت</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="border rounded p-3 text-center" style="background:#fee2e2;">
                                            <div class="fs-3 fw-bold text-danger">{{ count($importInvalid) }}</div>
                                            <div class="small text-danger">دارای خطا</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 d-flex align-items-center">
                                        <button type="button" class="btn btn-outline-secondary btn-sm w-100"
                                                wire:click="$set('importPreview', false)">
                                            <i class="material-symbols-outlined align-middle" style="font-size:16px;">arrow_back</i>
                                            انتخاب فایل جدید
                                        </button>
                                    </div>
                                </div>

                                {{-- ردیف‌های معتبر --}}
                                @if(count($importValid) > 0)
                                    <h6 class="text-success d-flex align-items-center gap-1 mb-2">
                                        <i class="material-symbols-outlined" style="font-size:18px;">check_circle</i>
                                        ردیف‌های قابل ثبت ({{ count($importValid) }} مورد)
                                    </h6>
                                    <div class="table-responsive mb-4">
                                        <table class="table table-sm table-bordered align-middle">
                                            <thead class="table-success">
                                            <tr>
                                                <th>ردیف</th>
                                                <th>دانش‌آموز</th>
                                                <th>عنوان</th>
                                                <th>وضعیت</th>
                                                <th>تاریخ</th>
                                                <th>پاسخگو</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($importValid as $row)
                                                <tr>
                                                    <td>{{ $row['row'] }}</td>
                                                    <td class="fw-semibold">{{ $row['student_name'] }}</td>
                                                    <td>{{ $row['title'] }}</td>
                                                    <td>
                                                        @if($row['contact_status'] === 'موفق')
                                                            <span class="badge text-bg-success">موفق</span>
                                                        @else
                                                            <span class="badge text-bg-danger">ناموفق</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $row['contact_date'] }}</td>
                                                    <td>{{ $row['respondent'] }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                {{-- ردیف‌های خطادار --}}
                                @if(count($importInvalid) > 0)
                                    <h6 class="text-danger d-flex align-items-center gap-1 mb-2">
                                        <i class="material-symbols-outlined" style="font-size:18px;">error</i>
                                        ردیف‌های دارای خطا ({{ count($importInvalid) }} مورد) – ثبت نخواهند شد
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered align-middle">
                                            <thead class="table-danger">
                                            <tr>
                                                <th>ردیف</th>
                                                <th>دانش‌آموز</th>
                                                <th>عنوان</th>
                                                <th>دلیل رد</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($importInvalid as $row)
                                                <tr>
                                                    <td>{{ $row['row'] }}</td>
                                                    <td>{{ $row['student_name'] }}</td>
                                                    <td>{{ $row['title'] ?: '-' }}</td>
                                                    <td>
                                                        <ul class="mb-0 ps-3 small text-danger">
                                                            @foreach($row['errors'] as $err)
                                                                <li>{{ $err }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                            @endif
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeImportModal">
                                بستن
                            </button>
                            @if($importPreview && count($importValid) > 0)
                                <button type="button" class="btn btn-success d-inline-flex align-items-center gap-2"
                                        wire:click="confirmImport"
                                        wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="confirmImport"
                                      class="d-inline-flex align-items-center gap-1">
                                    <i class="material-symbols-outlined" style="font-size:18px;">save</i>
                                    ثبت {{ count($importValid) }} مستند در دیتابیس
                                </span>
                                    <span wire:loading wire:target="confirmImport">
                                    <span class="spinner-border spinner-border-sm"></span>
                                    در حال ثبت...
                                </span>
                                </button>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
