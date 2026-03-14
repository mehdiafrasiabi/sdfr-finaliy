<div>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">مدیریت مباحث {{ $chapter->name }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('manager.dashboard.crm') }}">داشبورد</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('manager.classification.education-levels') }}">دوره‌ها</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('manager.classification.chapters', $chapter->subject->id) }}">{{ $chapter->subject->name }}</a></li>
                            <li class="breadcrumb-item active">مباحث</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Info Card -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card bg-success-subtle">
                    <div class="card-body py-2">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div>
                                <i class="ri-book-2-line text-success me-1"></i>
                                <span class="text-muted">درس:</span>
                                <strong>{{ $chapter->subject->name }}</strong>
                            </div>
                            <div class="vr d-none d-md-block"></div>
                            <div>
                                <i class="ri-folder-line text-success me-1"></i>
                                <span class="text-muted">فصل:</span>
                                <strong>{{ $chapter->name }}</strong>
                            </div>
                            <div class="vr d-none d-md-block"></div>
                            <div>
                                <i class="ri-graduation-cap-line text-success me-1"></i>
                                <span class="text-muted">پایه:</span>
                                <strong>{{ $chapter->subject->grade->name }}</strong>
                            </div>
                            @if($parentTopic)
                                <div class="vr d-none d-md-block"></div>
                                <div>
                                    <i class="ri-node-tree text-success me-1"></i>
                                    <span class="text-muted">مبحث اصلی:</span>
                                    <strong>{{ $parentTopic->name }}</strong>
                                </div>
                            @endif
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
                            @if($editingId)
                                @if($managingSubtopicsFor)
                                    ویرایش زیرمبحث
                                @else
                                    ویرایش مبحث
                                @endif
                            @else
                                @if($managingSubtopicsFor)
                                    افزودن زیرمبحث
                                @else
                                    افزودن مبحث
                                @endif
                            @endif
                        </h5>
                    </div>
                    @if($managingSubtopicsFor)
                        <div class="row mb-3">
                            <div class="col-12">
                                <button wire:click="backToMainTopics" class="btn btn-secondary">
                                    <i class="ri-arrow-right-line me-1"></i>
                                    بازگشت به مباحث اصلی
                                </button>
                            </div>
                        </div>
                    @endif
                    <div class="card-body">
                        <form wire:submit="submit">
                            <div class="mb-3">
                                <label for="name" class="form-label">نام مبحث <span class="text-danger">*</span></label>
                                <input type="text" wire:model="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name" placeholder="مثال: تابع مثلثاتی">
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
                @if(!$managingSubtopicsFor)
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" wire:model="has_subtopics"
                                   id="has_subtopics">
                            <label class="form-check-label" for="has_subtopics">دارای زیرمبحث</label>
                        </div>
                        <small class="text-muted">اگر این مبحث شامل زیرمباحث است، این گزینه را فعال کنید</small>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            @if($managingSubtopicsFor)
                                لیست زیرمباحث
                            @else
                                لیست مباحث
                            @endif
                        </h5>
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
                                    <th>نام مبحث</th>
                                    <th style="width: 100px;">ترتیب</th>
                                    <th style="width: 100px;">وضعیت</th>
                                    <th style="width: 200px;">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($topics as $topic)
                                    <tr>
                                        <td>{{ $loop->iteration + ($topics->currentPage() - 1) * $topics->perPage() }}</td>
                                        <td class="fw-medium">
                                            {{ $topic->name }}
                                            @if($topic->has_subtopics && !$managingSubtopicsFor)
                                                <span class="badge bg-info-subtle text-info ms-2">
                                                    <i class="ri-node-tree"></i> دارای زیرمبحث
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $topic->order }}</td>
                                        <td>
                                            @if($topic->is_active)
                                                <span class="badge bg-success-subtle text-success">فعال</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">غیرفعال</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                @if($topic->has_subtopics && !$managingSubtopicsFor)
                                                    <button wire:click="manageSubtopics({{ $topic->id }})"
                                                            class="btn btn-sm btn-soft-info" title="مدیریت زیرمباحث">
                                                        <i class="ri-node-tree"></i>
                                                    </button>
                                                @endif
                                                <button wire:click="edit({{ $topic->id }})"
                                                        class="btn btn-sm btn-soft-success" title="ویرایش">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <button wire:click="delete({{ $topic->id }})"
                                                        wire:confirm="آیا از حذف این مبحث اطمینان دارید؟"
                                                        class="btn btn-sm btn-soft-danger" title="حذف">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ri-file-list-3-line fs-1 d-block mb-2"></i>
                                                @if($managingSubtopicsFor)
                                                    هیچ زیرمبحثی یافت نشد
                                                @elseهیچ مبحثی یافت نشد
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $topics->links('layouts.manager.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
