<div>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">مدیریت پروژه‌های طبقه‌بندی</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('manager.dashboard.crm') }}">داشبورد</a></li>
                            <li class="breadcrumb-item active">پروژه‌های طبقه‌بندی</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            {{ $editingId ? 'ویرایش پروژه' : 'افزودن پروژه جدید' }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form wire:submit="submit">
                            <div class="mb-3">
                                <label for="name" class="form-label">نام پروژه <span
                                        class="text-danger">*</span></label>
                                <input type="text" wire:model="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name" placeholder="مثال: طبقه‌بندی مهر ۱۴۰۴">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">توضیحات</label>
                                <textarea wire:model="description"
                                          class="form-control @error('description') is-invalid @enderror"
                                          id="description" rows="3" placeholder="توضیحات و راهنمای پروژه..."></textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">تاریخ شروع <span class="text-danger">*</span></label>
                                        <input type="text" wire:model="start_date"
                                               class="form-control @error('start_date') is-invalid @enderror"
                                               id="start_date" placeholder="1404/01/01" dir="ltr">
                                        @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_time" class="form-label">ساعت شروع <span
                                                class="text-danger">*</span></label>
                                        <input type="time" wire:model="start_time"
                                               class="form-control @error('start_time') is-invalid @enderror"
                                               id="start_time">
                                        @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">تاریخ پایان <span
                                                class="text-danger">*</span></label>
                                        <input type="text" wire:model="end_date"
                                               class="form-control @error('end_date') is-invalid @enderror"
                                               id="end_date" placeholder="1404/01/15" dir="ltr">
                                        @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_time" class="form-label">ساعت پایان <span
                                                class="text-danger">*</span></label>
                                        <input type="time" wire:model="end_time"
                                               class="form-control @error('end_time') is-invalid @enderror"
                                               id="end_time">
                                        @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- Grade Settings -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">تنظیمات پایه‌ها</label>
                                <div class="alert alert-info py-2 small mb-2">
                                    <i class="ri-information-line me-1"></i>
                                    تنظیم کنید که هر پایه به چه محتوایی دسترسی داشته باشد
                                </div>
                                <!-- Grade 12 Settings -->
                                <div class="card card-body bg-light mb-2">
                                    <h6 class="mb-2"><i class="ri-graduation-cap-line me-1"></i>دانش‌آموزان پایه
                                        دوازدهم:</h6>
                                    @foreach($gradeSettings[12] as $index => $setting)
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       wire:model="gradeSettings.12.{{ $index }}.enabled"
                                                       id="gs12_{{ $index }}">
                                            </div>
                                            <span
                                                class="badge bg-{{ $setting['type'] === 'progress' ? 'success' : 'warning' }}-subtle text-{{ $setting['type'] === 'progress' ? 'success' : 'warning' }}">
                                            {{ $setting['type'] === 'progress' ? 'پیشروی' : 'جمع‌بندی' }}
                                        </span>
                                            <span>پایه {{ $setting['target_grade'] == 12 ? 'دوازدهم' : ($setting['target_grade'] == 11 ? 'یازدهم' : 'دهم') }}</span>
                                            <div class="form-check form-check-inline ms-auto">
                                                <input class="form-check-input" type="checkbox"
                                                       wire:model="gradeSettings.12.{{ $index }}.has_general"
                                                       id="gs12_gen_{{ $index }}">
                                                <label class="form-check-label small" for="gs12_gen_{{ $index }}">عمومی</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <!-- Grade 11 Settings -->
                                <div class="card card-body bg-light mb-2">
                                    <h6 class="mb-2"><i class="ri-graduation-cap-line me-1"></i>دانش‌آموزان پایه یازدهم:</h6>
                                    @foreach($gradeSettings[11] as $index => $setting)
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       wire:model="gradeSettings.11.{{ $index }}.enabled"
                                                       id="gs11_{{ $index }}">
                                            </div>
                                            <span
                                                class="badge bg-{{ $setting['type'] === 'progress' ? 'success' : 'warning' }}-subtle text-{{ $setting['type'] === 'progress' ? 'success' : 'warning' }}">
                                            {{ $setting['type'] === 'progress' ? 'پیشروی' : 'جمع‌بندی' }}
                                        </span>
                                            <span>پایه {{ $setting['target_grade'] == 11 ? 'یازدهم' : 'دهم' }}</span>
                                            <div class="form-check form-check-inline ms-auto">
                                                <input class="form-check-input" type="checkbox"
                                                       wire:model="gradeSettings.11.{{ $index }}.has_general"
                                                       id="gs11_gen_{{ $index }}">
                                                <label class="form-check-label small" for="gs11_gen_{{ $index }}">عمومی</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <!-- Grade 10 Settings -->
                                <div class="card card-body bg-light">
                                    <h6 class="mb-2"><i class="ri-graduation-cap-line me-1"></i>دانش‌آموزان پایه دهم:</h6>
                                    @foreach($gradeSettings[10] as $index => $setting)
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       wire:model="gradeSettings.10.{{ $index }}.enabled"
                                                       id="gs10_{{ $index }}">
                                            </div>
                                            <span class="badge bg-success-subtle text-success">پیشروی</span>
                                            <span>پایه دهم</span>
                                            <div class="form-check form-check-inline ms-auto">
                                                <input class="form-check-input" type="checkbox"
                                                       wire:model="gradeSettings.10.{{ $index }}.has_general"
                                                       id="gs10_gen_{{ $index }}">
                                                <label class="form-check-label small"
                                                       for="gs10_gen_{{ $index }}">عمومی</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" wire:model="is_active"
                                           id="is_active">
                                    <label class="form-check-label" for="is_active">فعال</label>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                <span wire:loading.remove wire:target="submit">
                                    <i class="ri-save-line me-1"></i>
                                    {{ $editingId ? 'ویرایش' : 'ذخیره' }}
                                </span>
                                    <span wire:loading wire:target="submit">
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    در حال ذخیره...
                                </span>
                                </button>
                                @if($editingId)
                                    <button type="button" wire:click="resetForm" class="btn btn-secondary">
                                        <i class="ri-close-line me-1"></i>
                                        انصراف
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">لیست پروژه‌ها</h5>
                        <div class="search-box">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                   class="form-control search" placeholder="جستجو...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">ردیف</th>
                                    <th>نام پروژه</th>
                                    <th style="width: 140px;">شروع</th>
                                    <th style="width: 140px;">پایان</th>
                                    <th style="width: 100px;">وضعیت</th>
                                    <th style="width: 120px;">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($projects as $project)
                                    <tr>
                                        <td>{{ $loop->iteration + ($projects->currentPage() - 1) * $projects->perPage() }}</td>
                                        <td>
                                            <div class="fw-medium">{{ $project->name }}</div>
                                            @if($project->description)
                                                <small class="text-muted">{{ Str::limit($project->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td class="text-nowrap small">
                                            {{ \Morilog\Jalali\Jalalian::fromCarbon($project->start_at)->format('Y/m/d') }}
                                            <br>
                                            <span class="text-muted">{{ $project->start_at->format('H:i') }}</span>
                                        </td>
                                        <td class="text-nowrap small">
                                            {{ \Morilog\Jalali\Jalalian::fromCarbon($project->end_at)->format('Y/m/d') }}
                                            <br>
                                            <span class="text-muted">{{ $project->end_at->format('H:i') }}</span>
                                        </td>
                                        <td>
                                            @if($project->status === 'active')
                                                <span class="badge bg-success">در حال اجرا</span>
                                            @elseif($project->status === 'upcoming')
                                                <span class="badge bg-info">در انتظار</span>
                                            @else
                                                <span class="badge bg-secondary">پایان یافته</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <button wire:click="edit({{ $project->id }})"
                                                        class="btn btn-sm btn-soft-success" title="ویرایش">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <button wire:click="delete({{ $project->id }})"
                                                        wire:confirm="آیا از حذف این پروژه اطمینان دارید؟"
                                                        class="btn btn-sm btn-soft-danger" title="حذف">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ri-folder-open-line fs-1 d-block mb-2"></i>
                                                هیچ پروژه‌ای یافت نشد
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $projects->links('layouts.manager.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
