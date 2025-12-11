<div>
    <div>

        <div class="row">

            <div class="col-12">

                <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                    <h4 class="mb-sm-0">مدیریت فصل‌های {{ $subject->name }}</h4>

                    <div class="page-title-right">

                        <ol class="breadcrumb m-0">

                            <li class="breadcrumb-item"><a href="{{ route('manager.dashboard.crm') }}">داشبورد</a></li>

                            <li class="breadcrumb-item"><a
                                    href="{{ route('manager.classification.education-levels') }}">دوره‌ها</a></li>

                            <li class="breadcrumb-item"><a
                                    href="{{ route('manager.classification.grades', $subject->grade->education_level_id) }}">{{ $subject->grade->educationLevel->name }}</a>
                            </li>

                            <li class="breadcrumb-item"><a
                                    href="{{ route('manager.classification.subjects', ['educationLevel' => $subject->grade->education_level_id, 'grade' => $subject->grade->id]) }}">{{ $subject->grade->name }}</a>
                            </li>

                            <li class="breadcrumb-item active">فصل‌ها</li>

                        </ol>

                    </div>

                </div>

            </div>

        </div>


        <!-- Info Card -->

        <div class="row mb-3">

            <div class="col-12">

                <div class="card bg-primary-subtle">

                    <div class="card-body py-2">

                        <div class="d-flex align-items-center gap-3">

                            <i class="ri-book-2-line fs-4 text-primary"></i>

                            <div>

                                <span class="text-muted">درس:</span>

                                <strong>{{ $subject->name }}</strong>

                                <span
                                    class="badge bg-{{ $subject->type === 'general' ? 'info' : 'warning' }}-subtle text-{{ $subject->type === 'general' ? 'info' : 'warning' }} ms-2">

                                {{ $subject->type_name }}

                            </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="row">

            <div class="col-lg-4">

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">

                            {{ $editingId ? 'ویرایش فصل' : 'افزودن فصل' }}

                        </h5>

                    </div>

                    <div class="card-body">

                        <form wire:submit="submit">

                            <div class="mb-3">

                                <label for="name" class="form-label">نام فصل <span class="text-danger">*</span></label>

                                <input type="text" wire:model="name"
                                       class="form-control @error('name') is-invalid @enderror"

                                       id="name" placeholder="مثال: مثلثات">

                                @error('name')

                                <div class="invalid-feedback">{{ $message }}</div>

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

                        <h5 class="card-title mb-0">لیست فصل‌ها</h5>

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

                                    <th>نام فصل</th>

                                    <th style="width: 100px;">ترتیب</th>

                                    <th style="width: 100px;">وضعیت</th>

                                    <th style="width: 100px;">مباحث</th>

                                    <th style="width: 150px;">عملیات</th>

                                </tr>

                                </thead>

                                <tbody>

                                @forelse($chapters as $chapter)

                                    <tr>

                                        <td>{{ $loop->iteration + ($chapters->currentPage() - 1) * $chapters->perPage() }}</td>

                                        <td>

                                            <a href="{{ route('manager.classification.topics', $chapter->id) }}"

                                               class="text-primary fw-medium">

                                                {{ $chapter->name }}

                                            </a>

                                        </td>

                                        <td>{{ $chapter->order }}</td>

                                        <td>

                                            @if($chapter->is_active)

                                                <span class="badge bg-success-subtle text-success">فعال</span>

                                            @else

                                                <span class="badge bg-danger-subtle text-danger">غیرفعال</span>

                                            @endif

                                        </td>

                                        <td>

                                            <span class="badge bg-secondary-subtle text-secondary">

                                                {{ $chapter->topics_count }} مبحث

                                            </span>

                                        </td>

                                        <td>

                                            <div class="d-flex gap-1">

                                                <a href="{{ route('manager.classification.topics', $chapter->id) }}"

                                                   class="btn btn-sm btn-soft-info" title="مباحث">

                                                    <i class="ri-file-list-3-line"></i>

                                                </a>

                                                <button wire:click="edit({{ $chapter->id }})"

                                                        class="btn btn-sm btn-soft-success" title="ویرایش">

                                                    <i class="ri-pencil-line"></i>

                                                </button>

                                                <button wire:click="delete({{ $chapter->id }})"

                                                        wire:confirm="آیا از حذف این فصل اطمینان دارید؟"

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

                                                هیچ فصلی یافت نشد

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                                </tbody>

                            </table>

                        </div>


                        <div class="mt-3">

                            {{ $chapters->links('layouts.manager.pagination') }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
