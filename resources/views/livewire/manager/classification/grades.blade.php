<div>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">مدیریت پایه‌های {{ $educationLevel->name }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('manager.dashboard.crm') }}">داشبورد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('manager.classification.education-levels') }}">دوره‌های تحصیلی</a></li>
                            <li class="breadcrumb-item active">پایه‌ها</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            {{ $editingId ? 'ویرایش پایه' : 'افزودن پایه' }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form wire:submit="submit">
                            <div class="mb-3">
                                <label for="name" class="form-label">نام پایه <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name" placeholder="مثال: دوازدهم">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="grade_number" class="form-label">شماره پایه <span
                                        class="text-danger">*</span></label>
                                <select wire:model="grade_number"
                                        class="form-select @error('grade_number') is-invalid @enderror"
                                        id="grade_number">
                                    <option value="">انتخاب کنید...</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('grade_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">برای متوسطه دوم: ۱۰، ۱۱، ۱۲</small>
                            </div>
                            <div class="mb-3">
                                <label for="cc_field_id" class="form-label">رشته پایه</label>
                                <select wire:model="cc_field_id"
                                        class="form-select @error('cc_field_id') is-invalid @enderror"
                                        id="cc_field_id">
                                    <option value="">بدون رشته (متوسطه اول)</option>
                                    @foreach($fields as $field)
                                        <option value="{{ $field->id }}">{{ $field->name }}</option>
                                    @endforeach
                                </select>
                                @error('cc_field_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">برای متوسطه اول خالی بگذارید</small>
                            </div>
                            <div class="mb-3">
                                <label for="order" class="form-label">ترتیب نمایش</label>
                                <input type="number" wire:model="order"
                                       class="form-control @error('order') is-invalid @enderror"
                                       id="order" min="0" placeholder="0">
                                @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">لیست پایه‌ها</h5>
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
                                    <th style="width: 60px;">ردیف</th>
                                    <th>نام پایه</th>
                                    <th style="width: 100px;">شماره پایه</th>
                                    <th style="width: 120px;">رشته</th>
                                    <th style="width: 100px;">ترتیب</th>
                                    <th style="width: 100px;">وضعیت</th>
                                    <th style="width: 120px;">تعداد درس</th>
                                    <th style="width: 150px;">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($grades as $grade)
                                    <tr>
                                        <td>{{ $loop->iteration + ($grades->currentPage() - 1) * $grades->perPage() }}</td>
                                        <td>
                                            <a href="{{ route('manager.classification.subjects', ['educationLevel' => $educationLevel->id, 'grade' => $grade->id]) }}"
                                               class="text-primary fw-medium">
                                                {{ $grade->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">{{ $grade->grade_number }}</span>
                                        </td>
                                        <td>
                                            @if($grade->field)
                                                <span class="badge bg-warning-subtle text-warning">{{ $grade->field->name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $grade->order }}</td>
                                        <td>
                                            @if($grade->is_active)
                                                <span class="badge bg-success-subtle text-success">فعال</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">غیرفعال</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info">
                                                {{ $grade->subjects_count ?? $grade->subjects->count() }} درس
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('manager.classification.subjects', ['educationLevel' => $educationLevel->id, 'grade' => $grade->id]) }}"
                                                   class="btn btn-sm btn-soft-info" title="دروس">
                                                    <i class="ri-book-2-line"></i>
                                                </a>
                                                <button wire:click="edit({{ $grade->id }})"
                                                        class="btn btn-sm btn-soft-success" title="ویرایش">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <button wire:click="delete({{ $grade->id }})"
                                                        wire:confirm="آیا از حذف این پایه اطمینان دارید؟"
                                                        class="btn btn-sm btn-soft-danger" title="حذف">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ri-folder-open-line fs-1 d-block mb-2"></i>
                                                هیچ پایه‌ای یافت نشد
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $grades->links('layouts.manager.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
