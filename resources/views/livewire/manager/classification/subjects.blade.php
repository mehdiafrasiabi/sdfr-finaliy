<div>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">مدیریت دروس {{ $grade->name }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('manager.dashboard.crm') }}">داشبورد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('manager.classification.education-levels') }}">دوره‌ها</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('manager.classification.grades', $educationLevel->id) }}">{{ $educationLevel->name }}</a></li>
                            <li class="breadcrumb-item active">دروس</li>
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
                            {{ $editingId ? 'ویرایش درس' : 'افزودن درس' }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form wire:submit="submit">
                            <div class="mb-3">
                                <label for="name" class="form-label">نام درس <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name" placeholder="مثال: حسابان 2">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="cc_field_id" class="form-label">رشته</label>
                                <select wire:model="cc_field_id"
                                        class="form-select @error('cc_field_id') is-invalid @enderror" id="cc_field_id">
                                    <option value="">همه رشته‌ها (عمومی)</option>
                                    @foreach($fields as $field)
                                        <option value="{{ $field->id }}">{{ $field->name }}</option>
                                    @endforeach
                                </select>
                                @error('cc_field_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">نوع درس <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" wire:model="type"
                                               value="specialized" id="type_specialized">
                                        <label class="form-check-label" for="type_specialized">
                                            تخصصی
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" wire:model="type"
                                               value="general" id="type_general">
                                        <label class="form-check-label" for="type_general">
                                            عمومی
                                        </label>
                                    </div>
                                </div>
                                @error('type')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
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
                        <h5 class="card-title mb-0">لیست دروس</h5>
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
                                    <th>نام درس</th>
                                    <th style="width: 120px;">رشته</th>
                                    <th style="width: 100px;">نوع</th>
                                    <th style="width: 100px;">ترتیب</th>
                                    <th style="width: 100px;">فصل‌ها</th>
                                    <th style="width: 150px;">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($subjects as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration + ($subjects->currentPage() - 1) * $subjects->perPage() }}</td>
                                        <td>
                                            <a href="{{ route('manager.classification.chapters', $subject->id) }}"
                                               class="text-primary fw-medium">
                                                {{ $subject->name }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($subject->field)
                                                <span
                                                    class="badge bg-primary-subtle text-primary">{{ $subject->field->name }}</span>
                                            @else
                                                <span
                                                    class="badge bg-secondary-subtle text-secondary">همه رشته‌ها</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($subject->type === 'general')
                                                <span class="badge bg-info-subtle text-info">عمومی</span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning">تخصصی</span>
                                            @endif
                                        </td>
                                        <td>{{ $subject->order }}</td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                {{ $subject->chapters_count ?? $subject->chapters->count() }} فصل
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('manager.classification.chapters', $subject->id) }}"
                                                   class="btn btn-sm btn-soft-info" title="فصل‌ها">
                                                    <i class="ri-folder-line"></i>
                                                </a>
                                                <button wire:click="edit({{ $subject->id }})"
                                                        class="btn btn-sm btn-soft-success" title="ویرایش">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <button wire:click="delete({{ $subject->id }})"
                                                        wire:confirm="آیا از حذف این درس اطمینان دارید؟"
                                                        class="btn btn-sm btn-soft-danger" title="حذف">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ri-book-open-line fs-1 d-block mb-2"></i>
                                                هیچ درسی یافت نشد
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $subjects->links('layouts.manager.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
