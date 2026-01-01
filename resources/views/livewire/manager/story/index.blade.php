<div>

    <div class="row">

        <div class="col-12">

            <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                <h4 class="mb-sm-0">استوری ها</h4>

                <div class="page-title-right">

                    <ol class="breadcrumb m-0">

                        <li class="breadcrumb-item"><a href="{{ route('manager.dashboard.crm') }}">پنل مدیریت</a></li>

                        <li class="breadcrumb-item active">استوری ها</li>

                    </ol>

                </div>

            </div>

        </div>

    </div>


    <div class="row">

        <div class="col-lg-12">

            <div class="card">

                <div class="card-header d-flex align-items-center justify-content-between">

                    <div class="d-flex align-items-center gap-3">

                        <span>وضعیت نمایش استوری ها:</span>

                        <div class="form-check form-switch">

                            <input class="form-check-input" type="checkbox" role="switch"

                                   wire:click="toggleStoriesDisplay"

                                {{ $storiesDisplayEnabled ? 'checked' : '' }}>

                            <label class="form-check-label">{{ $storiesDisplayEnabled ? 'فعال' : 'غیرفعال' }}</label>

                        </div>

                    </div>

                    <a href="{{ route('manager.story.create') }}" class="btn btn-primary">

                        <i class="ri-add-line me-1"></i>

                        افزودن استوری

                    </a>

                </div>

                <div class="card-body">

                    <div class="row g-3 mb-4">

                        <div class="col-md-6">

                            <div class="d-flex align-items-center gap-2">

                                <i class="ri-filter-3-line"></i>

                                <span class="text-muted">مرتب سازی بر اساس:</span>

                                <div class="btn-group" role="group">

                                    <button type="button" wire:click="$set('filter', 'all')"

                                            class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">

                                        همه

                                    </button>

                                    <button type="button" wire:click="$set('filter', 'published')"

                                            class="btn btn-sm {{ $filter === 'published' ? 'btn-success' : 'btn-outline-success' }}">

                                        منتشر شده

                                    </button>

                                    <button type="button" wire:click="$set('filter', 'expired')"

                                            class="btn btn-sm {{ $filter === 'expired' ? 'btn-danger' : 'btn-outline-danger' }}">

                                        منقضی

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="table-responsive">

                        <table class="table table-hover table-bordered align-middle">

                            <thead class="table-light">

                            <tr>

                                <th style="width: 50px;"><i class="ri-settings-3-line"></i></th>

                                <th>عنوان</th>

                                <th>کاربر</th>

                                <th>وضعیت</th>

                                <th>تاریخ انقضا</th>

                                <th>تاریخ</th>

                            </tr>

                            </thead>

                            <tbody>

                            @forelse($stories as $story)

                                <tr>

                                    <td class="text-center">

                                        <div class="dropdown">

                                            <button class="btn btn-sm btn-light" type="button"
                                                    data-bs-toggle="dropdown">

                                                <i class="ri-more-2-fill"></i>

                                            </button>

                                            <ul class="dropdown-menu">

                                                <li>

                                                    <a class="dropdown-item"
                                                       href="{{ route('manager.story.edit', $story) }}">

                                                        <i class="ri-edit-line me-2"></i>ویرایش

                                                    </a>

                                                </li>

                                                <li>

                                                    <button class="dropdown-item"

                                                            wire:click="changeStatus({{ $story->id }})"

                                                            wire:confirm="آیا مطمئن هستید؟">

                                                        <i class="ri-toggle-line me-2"></i>

                                                        {{ $story->status ? 'غیرفعال کردن' : 'فعال کردن' }}

                                                    </button>

                                                </li>

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                                <li>

                                                    <button class="dropdown-item text-danger"

                                                            wire:click="delete({{ $story->id }})"

                                                            wire:confirm="آیا از حذف این استوری اطمینان دارید؟">

                                                        <i class="ri-delete-bin-line me-2"></i>حذف

                                                    </button>

                                                </li>

                                            </ul>

                                        </div>

                                    </td>

                                    <td>

                                        <div class="d-flex align-items-center gap-3">

                                            <div class="position-relative">

                                                <img src="/stories/thumbnail/{{ $story->thumbnail }}"

                                                     alt="{{ $story->title }}"

                                                     class="rounded-circle"

                                                     style="width: 50px; height: 50px; object-fit: cover; border: 3px solid {{ $story->status && !$story->is_expired ? '#198754' : '#dc3545' }};">

                                            </div>

                                            <span class="fw-medium">{{ $story->title }}</span>

                                        </div>

                                    </td>

                                    <td>{{ $story->user?->name ?? 'مدیر' }}</td>

                                    <td>

                                        @if($story->is_expired)

                                            <span class="badge bg-danger">منقضی شده</span>

                                        @elseif($story->status)

                                            <span class="badge bg-success">منتشر شده</span>

                                        @else

                                            <span class="badge bg-warning">غیرفعال</span>

                                        @endif

                                    </td>

                                    <td>

                                        @if($story->expires_at)

                                            {{ jalali($story->expires_at)->format('%d %B %Y') }}

                                        @else

                                            <span class="text-muted">-</span>

                                        @endif

                                    </td>

                                    <td>{{ jalali($story->created_at)->format('%d %B %Y') }}</td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="text-center py-5">

                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"

                                                   trigger="loop"

                                                   colors="primary:#121331,secondary:#08a88a"

                                                   style="width:75px;height:75px"></lord-icon>

                                        <h5 class="mt-2">هیچ استوری یافت نشد</h5>

                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{ $stories->links('layouts.manager.pagination') }}

                </div>

            </div>

        </div>

    </div>

</div>
