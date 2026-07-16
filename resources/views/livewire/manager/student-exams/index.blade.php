<div class="container-fluid py-4">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="mb-1">امتحانات دانش‌آموزان</h4>
                <p class="text-muted mb-0">تنظیم بازه فعال‌سازی، نوع ثبت و ظرفیت مطالعه هر پایه و رشته را از اینجا مدیریت کنید.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-soft-info" wire:click="openSampleModal">
                    <i class="ri-upload-2-line me-1"></i>
                    آپلود نمونه سوالات
                </button>
                <button class="btn btn-primary" wire:click="openModal">
                    <i class="ri-add-line me-1"></i>
                    تنظیم جدید
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>پایه / رشته</th>
                        <th>نوبت</th>
                        <th>نوع ثبت</th>
                        <th>بازه فعال‌سازی</th>
                        <th>بازه امتحانات</th>
                        <th>سقف روزانه</th>
                        <th>جزئیات</th>
                        <th>نمونه سوالات</th>
                        <th>وضعیت</th>
                        <th class="text-end">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($settings as $setting)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $setting->grade_label }}</div>
                                <small class="text-muted">{{ $setting->field_label }}</small>
                            </td>
                            <td>{{ $setting->term_type_label }}</td>
                            <td>{{ $setting->input_mode_label }}</td>
                            <td>
                                {{ jdate($setting->activation_starts_at)->format('Y/m/d') }}
                                <span class="text-muted">تا</span>
                                {{ jdate($setting->activation_ends_at)->format('Y/m/d') }}
                            </td>
                            <td>
                                @if($setting->exam_starts_at && $setting->exam_ends_at)
                                    {{ jdate($setting->exam_starts_at)->format('Y/m/d') }}
                                    <span class="text-muted">تا</span>
                                    {{ jdate($setting->exam_ends_at)->format('Y/m/d') }}
                                @else
                                    <span class="text-muted">دانش‌آموز وارد می‌کند</span>
                                @endif
                            </td>
                            <td>{{ $setting->max_daily_study_hours }} ساعت</td>
                            <td>
                                @if($setting->input_mode === \App\Models\ExamPlanningSetting::INPUT_MANAGER)
                                    <a href="{{ route('manager.student-exams.detail', $setting->id) }}"
                                       class="btn btn-sm btn-soft-info">
                                        {{ $setting->days_count }} روز ثبت‌شده
                                    </a>
                                @else
                                    <span class="badge bg-light text-dark">توسط دانش‌آموز</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-soft-secondary"
                                        wire:click="openSampleModal({{ $setting->id }})">
                                    {{ $setting->sample_questions_count }} فایل
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-sm {{ $setting->is_active ? 'btn-success' : 'btn-outline-secondary' }}"
                                        wire:click="toggleActive({{ $setting->id }})">
                                    {{ $setting->is_active ? 'فعال' : 'غیرفعال' }}
                                </button>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <button class="btn btn-sm btn-soft-primary" wire:click="edit({{ $setting->id }})">
                                        ویرایش
                                    </button>
                                    <button class="btn btn-sm btn-soft-danger" wire:click="delete({{ $setting->id }})"
                                            onclick="return confirm('این تنظیم حذف شود؟')">
                                        حذف
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">هنوز تنظیم امتحانی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{ $settings->links() }}
        </div>
    </div>

    @if($showSampleModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(15, 23, 42, 0.55);">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title">آپلود نمونه سوالات تشریحی</h5>
                            @if($sampleSelectedSetting)
                                <div class="text-muted small">{{ $sampleSelectedSetting->grade_label }} - {{ $sampleSelectedSetting->field_label }}</div>
                            @endif
                        </div>
                        <button type="button" class="btn-close" wire:click="closeSampleModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">تنظیم امتحان</label>
                                <select class="form-select" wire:model.live="sampleSettingId">
                                    <option value="">انتخاب کنید</option>
                                    @foreach($sampleSettings as $setting)
                                        <option value="{{ $setting->id }}">
                                            {{ $setting->grade_label }} - {{ $setting->field_label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sampleSettingId') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">درس‌های قابل انتخاب</label>
                                <div class="rounded border p-2 bg-light d-flex flex-wrap gap-2 min-h-100">
                                    @forelse($sampleSubjects as $subject)
                                        <span class="badge bg-secondary-subtle text-dark">
                                            {{ $subject['name'] }} ({{ $subject['type'] === 'general' ? 'عمومی' : 'تخصصی' }})
                                        </span>
                                    @empty
                                        <span class="text-muted small">ابتدا یک تنظیم امتحان را انتخاب کنید.</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="text-muted small">برای هر فایل یک ردیف بسازید. هر فایل فقط PDF و تا 20 مگابایت.</div>
                            <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addSampleRow">
                                <i class="ri-add-line me-1"></i>
                                ردیف جدید
                            </button>
                        </div>

                        @error('sampleRows') <div class="alert alert-danger py-2">{{ $message }}</div> @enderror

                        <div class="d-grid gap-3">
                            @foreach($sampleRows as $index => $row)
                                <div wire:key="sample-row-{{ $index }}" class="border rounded-3 p-3 bg-body-tertiary">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="fw-semibold">فایل {{ $index + 1 }}</div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeSampleRow({{ $index }})" @disabled(count($sampleRows) === 1)>
                                            حذف
                                        </button>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-lg-4">
                                            <label class="form-label">عنوان</label>
                                            <input type="text" class="form-control" wire:model="sampleRows.{{ $index }}.title" placeholder="نمونه: فیزیک دوازدهم فصل ۱">
                                            @error('sampleRows.' . $index . '.title') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="form-label">درس</label>
                                            <select class="form-select" wire:model="sampleRows.{{ $index }}.cc_subject_id">
                                                <option value="">انتخاب کنید</option>
                                                @foreach($sampleSubjects as $subject)
                                                    <option value="{{ $subject['id'] }}">
                                                        {{ $subject['name'] }} ({{ $subject['type'] === 'general' ? 'عمومی' : 'تخصصی' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('sampleRows.' . $index . '.cc_subject_id') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="col-lg-2">
                                            <label class="form-label">زمان (دقیقه)</label>
                                            <input type="number" min="1" max="720" class="form-control" wire:model="sampleRows.{{ $index }}.duration_minutes" dir="ltr">
                                            @error('sampleRows.' . $index . '.duration_minutes') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="col-lg-2">
                                            <label class="form-label">فایل PDF</label>
                                            <input type="file" class="form-control" wire:model="sampleRows.{{ $index }}.file" accept="application/pdf">
                                            @error('sampleRows.' . $index . '.file') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" wire:model="sampleRows.{{ $index }}.is_main" id="sample-main-{{ $index }}">
                                                <label class="form-check-label" for="sample-main-{{ $index }}">
                                                    این فایل به عنوان اصلی ذخیره شود
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" wire:click="closeSampleModal">انصراف</button>
                        <button class="btn btn-primary" wire:click="saveSampleQuestions">
                            ذخیره نمونه سوالات
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(15, 23, 42, 0.55);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editingId ? 'ویرایش تنظیم امتحان' : 'ایجاد تنظیم امتحان' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">پایه</label>
                                <select class="form-select" wire:model.live="grade">
                                    @foreach($gradeOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">رشته</label>
                                <select class="form-select" wire:model="field" @if($grade === 9) disabled @endif>
                                    <option value="">بدون رشته</option>
                                    @foreach($fieldOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('field') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">نوبت</label>
                                <select class="form-select" wire:model="term_type">
                                    <option value="first">نوبت اول</option>
                                    <option value="second">نوبت دوم</option>
                                    <option value="final">نهایی / عمومی</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">شروع فعال‌سازی</label>
                                <input type="text" class="form-control" wire:model="activation_starts_at" placeholder="1405/02/01">
                                @error('activation_starts_at') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">پایان فعال‌سازی</label>
                                <input type="text" class="form-control" wire:model="activation_ends_at" placeholder="1405/02/20">
                                @error('activation_ends_at') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">نوع ثبت برنامه امتحانات</label>
                                <select class="form-select" wire:model.live="input_mode">
                                    <option value="student">توسط دانش‌آموز</option>
                                    <option value="manager">توسط مدیر</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">حداکثر ساعت مطالعه روزانه</label>
                                <input type="number" min="4" max="16" class="form-control" wire:model="max_daily_study_hours">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">شروع بازه امتحانات</label>
                                <input type="text" class="form-control" wire:model="exam_starts_at" placeholder="1405/03/10"
                                       @if($input_mode === \App\Models\ExamPlanningSetting::INPUT_STUDENT) disabled @endif>
                                @error('exam_starts_at') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">پایان بازه امتحانات</label>
                                <input type="text" class="form-control" wire:model="exam_ends_at" placeholder="1405/03/29"
                                       @if($input_mode === \App\Models\ExamPlanningSetting::INPUT_STUDENT) disabled @endif>
                                @error('exam_ends_at') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" wire:model="is_active" id="examSettingActive">
                                    <label class="form-check-label" for="examSettingActive">تنظیم فعال باشد</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" wire:click="closeModal">انصراف</button>
                        <button class="btn btn-primary" wire:click="save">
                            {{ $editingId ? 'ذخیره تغییرات' : 'ثبت تنظیم' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
