<div class="container-fluid py-4">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h4 class="mb-1">نمونه سوالات: {{ $setting->grade_label }} / {{ $setting->field_label }}</h4>
                <p class="text-muted mb-0">
                    {{ $setting->term_type_label }}
                    <span class="mx-2">|</span>
                    هر کتاب فقط یک فایل اصلی فعال دارد.
                </p>
            </div>
            <a href="{{ route('manager.student-exams.index') }}" class="btn btn-light">
                بازگشت به لیست
            </a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">کل فایل‌ها</div>
                    <div class="fs-4 fw-bold">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">کتاب‌های این پایه/رشته</div>
                    <div class="fs-4 fw-bold">{{ $stats['books'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">فایل اصلی ثبت‌شده</div>
                    <div class="fs-4 fw-bold">{{ $stats['main'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">دارای ماه و سال</div>
                    <div class="fs-4 fw-bold">{{ $stats['categorized'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-0">{{ $editingId ? 'ویرایش نمونه سوال' : 'افزودن نمونه سوال' }}</h5>
                @if($editingId)
                    <div class="text-muted small mt-1">اگر فایل PDF جدید انتخاب نکنید، فایل قبلی حفظ می‌شود.</div>
                @endif
            </div>
            <div class="d-flex align-items-center gap-2">
                @if($editingId)
                    <button type="button" class="btn btn-sm btn-light" wire:click="cancelEdit">
                        انصراف از ویرایش
                    </button>
                @endif
                <span class="badge bg-soft-info text-info">PDF تا 20 مگابایت</span>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">کتاب</label>
                    <select class="form-select" wire:model="cc_subject_id">
                        <option value="">انتخاب کنید</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject['id'] }}">
                                {{ $subject['name'] }} ({{ $subject['type'] === 'general' ? 'عمومی' : 'تخصصی' }})
                            </option>
                        @endforeach
                    </select>
                    @error('cc_subject_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label">زمان امتحان</label>
                    <select class="form-select" wire:model.live="exam_period_month">
                        @foreach($monthOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('exam_period_month') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label">سال</label>
                    <select class="form-select" wire:model.live="exam_period_year">
                        @foreach($yearOptions as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                    @error('exam_period_year') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">عنوان</label>
                    <input type="text" class="form-control" wire:model="title" placeholder="امتحان نهایی خرداد 1404">
                    @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label">زمان آزمون (دقیقه)</label>
                    <input type="number" min="1" max="720" class="form-control" wire:model="duration_minutes" dir="ltr">
                    @error('duration_minutes') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">{{ $editingId ? 'فایل PDF جدید' : 'فایل PDF' }}</label>
                    <input type="file" class="form-control" wire:model="file" accept="application/pdf" wire:key="sample-file-{{ $editingId ?: 'new' }}">
                    @if($editingId)
                        <small class="text-muted d-block mt-1">برای حفظ فایل قبلی این بخش را خالی بگذارید.</small>
                    @endif
                    @error('file') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-lg-4 col-md-6 d-flex align-items-end">
                    <div class="form-check form-switch">
                        @php($mainSwitchId = 'sampleIsMain-' . ($editingId ?: 'new'))
                        <input class="form-check-input" type="checkbox" wire:model.live="is_main"
                               id="{{ $mainSwitchId }}" wire:key="sample-main-switch-{{ $editingId ?: 'new' }}">
                        <label class="form-check-label" for="{{ $mainSwitchId }}">
                            این فایل، تنها فایل اصلی این کتاب باشد
                        </label>
                    </div>
                </div>
                <div class="col-lg-4 d-flex align-items-end justify-content-lg-end">
                    <button type="button" class="btn btn-primary" wire:click="save" wire:loading.attr="disabled" wire:target="save,file">
                        <span wire:loading.remove wire:target="save,file">{{ $editingId ? 'ذخیره ویرایش' : 'ذخیره فایل' }}</span>
                        <span wire:loading wire:target="save,file">در حال ذخیره...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">فایل‌های قبلی</h5>
            <div style="min-width: 220px;">
                <select class="form-select form-select-sm" wire:model.live="subjectFilter">
                    <option value="">همه کتاب‌ها</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject['id'] }}">{{ $subject['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>کتاب</th>
                        <th>عنوان / دسته‌بندی</th>
                        <th>زمان</th>
                        <th>فایل اصلی</th>
                        <th>دانلود</th>
                        <th class="text-end">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($questions as $question)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $question->subject->name ?? 'بدون کتاب' }}</div>
                                <small class="text-muted">{{ $question->subject?->type === 'general' ? 'عمومی' : 'تخصصی' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $question->title }}</div>
                                <small class="text-muted">{{ $question->exam_period_label }}</small>
                            </td>
                            <td>{{ $question->duration_minutes }} دقیقه</td>
                            <td>
                                @if($question->is_main)
                                    <span class="badge bg-success">اصلی</span>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-success"
                                            wire:click="makeMain({{ $question->id }})">
                                        اصلی کردن
                                    </button>
                                @endif
                            </td>
                            <td>
                                <a href="{{ $question->download_url }}" class="btn btn-sm btn-soft-info" target="_blank">
                                    مشاهده PDF
                                </a>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-soft-primary"
                                            wire:click="editQuestion({{ $question->id }})">
                                        ویرایش
                                    </button>
                                    <button type="button" class="btn btn-sm btn-soft-danger"
                                            wire:click="deleteQuestion({{ $question->id }})"
                                            onclick="return confirm('این نمونه سوال حذف شود؟')">
                                        حذف
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">هنوز نمونه سوالی برای این تنظیم ثبت نشده است.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
