<div>
    <div class="container-fluid">

        <!-- page title -->

        <div class="row">

            <div class="col-12">

                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">

                    <h4 class="mb-sm-0">دیدگاه محصولات</h4>

                    <div class="page-title-right">

                        <ol class="breadcrumb m-0">

                            <li class="breadcrumb-item"><a href="{{ route('manager.dashboard.crm') }}">پنل مدیریت</a>
                            </li>

                            <li class="breadcrumb-item active">دیدگاه محصولات</li>

                        </ol>

                    </div>

                </div>

            </div>

        </div>


        <!-- Info Text -->

        <div class="row mb-3">

            <div class="col-12">

                <div class="alert alert-info text-center mb-0">

                    تمام کاربران ثبت‌نام شده می‌توانند برای محصولات دیدگاه ثبت کنند. دیدگاه‌ها پس از تایید شما نمایش
                    داده می‌شوند.


                </div>

            </div>

        </div>


        <!-- Flash Message -->

        @if(session()->has('message'))

            <div class="row mb-3">

                <div class="col-12">

                    <div class="alert alert-success alert-dismissible fade show" role="alert">

                        {{ session('message') }}

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    </div>

                </div>

            </div>

        @endif



        <!-- Stats Cards -->

        <div class="row">

            <div class="col-xxl-3 col-sm-6">

                <div class="card card-animate">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <p class="fw-medium text-muted mb-0">کل دیدگاه‌ها</p>

                                <h2 class="mt-4 ff-secondary fw-semibold">

                                    <span class="counter-value">{{ $totalComments }}</span>

                                </h2>

                            </div>

                            <div>

                                <div class="avatar-sm flex-shrink-0">

                                <span class="avatar-title bg-info-subtle text-info rounded-circle fs-4">

                                    <i class="ri-chat-3-line"></i>

                                </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-xxl-3 col-sm-6">

                <div class="card card-animate">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <p class="fw-medium text-muted mb-0">منتشر شده</p>

                                <h2 class="mt-4 ff-secondary fw-semibold">

                                    <span class="counter-value">{{ $publishedComments }}</span>

                                </h2>

                            </div>

                            <div>

                                <div class="avatar-sm flex-shrink-0">

                                <span class="avatar-title bg-success-subtle text-success rounded-circle fs-4">

                                    <i class="ri-check-double-line"></i>

                                </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-xxl-3 col-sm-6">

                <div class="card card-animate">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <p class="fw-medium text-muted mb-0">در انتظار تایید</p>

                                <h2 class="mt-4 ff-secondary fw-semibold">

                                    <span class="counter-value">{{ $pendingComments }}</span>

                                </h2>

                            </div>

                            <div>

                                <div class="avatar-sm flex-shrink-0">

                                <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-4">

                                    <i class="ri-time-line"></i>

                                </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-xxl-3 col-sm-6">

                <div class="card card-animate">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <p class="fw-medium text-muted mb-0">گزارش شده</p>

                                <h2 class="mt-4 ff-secondary fw-semibold">

                                    <span class="counter-value">{{ $reportedComments }}</span>

                                </h2>

                            </div>

                            <div>

                                <div class="avatar-sm flex-shrink-0">

                                <span class="avatar-title bg-danger-subtle text-danger rounded-circle fs-4">

                                    <i class="ri-flag-line"></i>

                                </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Comments List -->

        <div class="row">

            <div class="col-lg-12">

                <div class="card">

                    <div class="card-header border-bottom">

                        <div class="d-flex flex-wrap align-items-center gap-3">

                            <div class="d-flex align-items-center gap-2">

                                <i class="ri-filter-3-line"></i>

                                <span class="fw-medium">مرتب سازی بر اساس :</span>

                            </div>

                            <div class="btn-group" role="group">

                                <button type="button" wire:click="setStatus('all')"

                                        class="btn btn-sm {{ $statusFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">

                                    همه

                                </button>

                                <button type="button" wire:click="setStatus('published')"

                                        class="btn btn-sm {{ $statusFilter === 'published' ? 'btn-success' : 'btn-outline-secondary' }}">

                                    منتشر شده

                                </button>

                                <button type="button" wire:click="setStatus('pending')"

                                        class="btn btn-sm {{ $statusFilter === 'pending' ? 'btn-warning' : 'btn-outline-secondary' }}">

                                    در انتظار تایید

                                </button>

                                <button type="button" wire:click="setStatus('reported')"

                                        class="btn btn-sm {{ $statusFilter === 'reported' ? 'btn-danger' : 'btn-outline-secondary' }}">

                                    گزارش شده

                                </button>

                            </div>

                        </div>

                    </div>


                    <div class="card-body border-bottom">

                        <div class="row">

                            <div class="col-md-6">

                                <div class="search-box">

                                    <input wire:model.live.debounce.350ms="search" type="text"

                                           class="form-control search bg-light border-light"

                                           placeholder="جستجو در دیدگاه‌ها، نام کاربر یا محصول...">

                                    <i class="ri-search-line search-icon"></i>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        @forelse($comments as $comment)

                            <div class="card mb-3 border shadow-sm">

                                <div class="card-header bg-light">

                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                                        <div class="d-flex align-items-center gap-3">

                                            <!-- User Info -->

                                            <div class="d-flex align-items-center gap-2">

                                                <span class="text-muted">کاربر:</span>

                                                <a href="{{ route('manager.user.detail', $comment->user_id) }}"

                                                   class="fw-semibold text-primary" target="_blank">

                                                    {{ $comment->user->name }}

                                                    <i class="ri-external-link-line"></i>

                                                </a>

                                            </div>


                                            <span class="text-muted">|</span>


                                            <!-- Likes -->

                                            <div class="d-flex align-items-center gap-1">

                                                <span class="text-muted">پسندیدن:</span>

                                                <span class="badge bg-primary">{{ $comment->likes_count }}</span>

                                            </div>


                                            <span class="text-muted">|</span>


                                            <!-- Date -->

                                            <div class="d-flex align-items-center gap-1">

                                                <span class="text-muted">تاریخ:</span>

                                                <span
                                                    class="fw-medium">{{ jalali($comment->created_at)->format('%d %B %Y') }}</span>

                                            </div>


                                            <span class="text-muted">|</span>


                                            <!-- Product -->

                                            <div class="d-flex align-items-center gap-1">

                                                <span class="text-muted">محصول:</span>

                                                <a href="{{ route('client.product', ['p_code' => $comment->product->p_code]) }}"

                                                   class="fw-semibold text-info" target="_blank">

                                                    {{ Str::limit($comment->product->name, 30) }}

                                                    <i class="ri-external-link-line"></i>

                                                </a>

                                            </div>

                                        </div>


                                        <!-- Status Badge -->

                                        <div>

                                            @if($comment->status === 'published')

                                                <span class="badge bg-success-subtle text-success">منتشر شده</span>

                                            @elseif($comment->status === 'pending')

                                                <span
                                                    class="badge bg-warning-subtle text-warning">در انتظار تایید</span>

                                            @else

                                                <span class="badge bg-danger-subtle text-danger">گزارش شده</span>

                                            @endif

                                        </div>

                                    </div>

                                </div>


                                <div class="card-body">

                                    <!-- Comment Text -->

                                    <div class="mb-3">

                                        <h6 class="text-muted mb-2">

                                            <i class="ri-chat-quote-line me-1"></i>

                                            متن دیدگاه:

                                        </h6>

                                        <p class="mb-0 p-3 bg-light rounded">{{ $comment->comment }}</p>

                                    </div>


                                    <!-- Admin Reply -->

                                    @if($comment->reply)

                                        <div class="border-top pt-3">

                                            <div class="d-flex justify-content-between align-items-start">

                                                <div class="flex-grow-1">

                                                    <div class="d-flex align-items-center gap-2 mb-2">

                                                        <div class="avatar-xs">

                                                        <span
                                                            class="avatar-title bg-primary-subtle text-primary rounded-circle">

                                                            <i class="ri-admin-line"></i>

                                                        </span>

                                                        </div>

                                                        <div>

                                                            <h6 class="mb-0">{{ $comment->reply->admin->name ?? 'مدیر' }}</h6>

                                                            <small
                                                                class="text-muted">{{ $comment->reply->admin->getRoleNames()->first() ?? 'مدیر سیستم' }}</small>

                                                        </div>

                                                    </div>

                                                    <p class="mb-0 p-3 bg-primary-subtle rounded text-primary">

                                                        {{ $comment->reply->reply }}

                                                    </p>

                                                </div>

                                                <button type="button" wire:click="deleteReply({{ $comment->id }})"

                                                        class="btn btn-sm btn-outline-danger ms-2"

                                                        onclick="return confirm('آیا از حذف پاسخ اطمینان دارید؟')">

                                                    <i class="ri-close-line"></i>

                                                </button>

                                            </div>

                                        </div>

                                    @endif

                                </div>


                                <div class="card-footer bg-light">

                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                                        <div class="dropdown">

                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"

                                                    data-bs-toggle="dropdown" aria-expanded="false">

                                                <i class="ri-menu-line me-1"></i>

                                                منوی عملیات

                                            </button>

                                            <ul class="dropdown-menu">

                                                <li>

                                                    <button class="dropdown-item"
                                                            wire:click="openReplyModal({{ $comment->id }})">

                                                        <i class="ri-reply-line me-2"></i>

                                                        {{ $comment->reply ? 'ویرایش پاسخ' : 'ثبت پاسخ' }}

                                                    </button>

                                                </li>

                                                <li>

                                                    <button class="dropdown-item"
                                                            wire:click="openEditModal({{ $comment->id }})">

                                                        <i class="ri-edit-line me-2"></i>

                                                        ویرایش

                                                    </button>

                                                </li>

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                                <li>

                                                    <button class="dropdown-item text-danger"
                                                            wire:click="openDeleteModal({{ $comment->id }})">

                                                        <i class="ri-delete-bin-line me-2"></i>

                                                        حذف

                                                    </button>

                                                </li>

                                            </ul>

                                        </div>


                                        <div class="d-flex gap-2">

                                            <!-- Report Button -->

                                            @if($comment->status !== 'reported')

                                                <button type="button" wire:click="reportComment({{ $comment->id }})"

                                                        class="btn btn-sm btn-outline-danger"

                                                        onclick="return confirm('آیا مطمئن هستید؟ این دیدگاه گزارش شده و دیگر نمایش داده نخواهد شد.')">

                                                    <i class="ri-flag-line me-1"></i>

                                                    گزارش تخلف

                                                </button>

                                            @endif



                                            <!-- Publish/Unpublish Button -->

                                            @if($comment->status === 'pending')

                                                <button type="button" wire:click="approveComment({{ $comment->id }})"

                                                        class="btn btn-sm btn-success">

                                                    <i class="ri-check-line me-1"></i>

                                                    انتشار

                                                </button>

                                            @elseif($comment->status === 'published')

                                                <button type="button" wire:click="unpublishComment({{ $comment->id }})"

                                                        class="btn btn-sm btn-warning">

                                                    <i class="ri-close-line me-1"></i>

                                                    عدم انتشار

                                                </button>

                                            @elseif($comment->status === 'reported')

                                                <button type="button" wire:click="approveComment({{ $comment->id }})"

                                                        class="btn btn-sm btn-success">

                                                    <i class="ri-check-line me-1"></i>

                                                    بازگردانی و انتشار

                                                </button>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="text-center py-5">

                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"

                                           colors="primary:#121331,secondary:#08a88a"
                                           style="width:75px;height:75px"></lord-icon>

                                <h5 class="mt-2">دیدگاهی یافت نشد</h5>

                                <p class="text-muted">هیچ دیدگاهی با فیلترهای انتخابی شما وجود ندارد.</p>

                            </div>

                        @endforelse



                        <!-- Pagination -->

                        @if($comments->hasPages())

                            <div class="d-flex justify-content-end mt-4">

                                {{ $comments->links('layouts.manager.pagination') }}

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        <!-- Reply Modal -->

        @if($showReplyModal)

            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">

                <div class="modal-dialog modal-dialog-centered">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title">

                                <i class="ri-reply-line me-2"></i>

                                {{ $replyText ? 'ویرایش پاسخ' : 'ثبت پاسخ' }}

                            </h5>

                            <button type="button" class="btn-close" wire:click="closeModals"></button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <label class="form-label">متن پاسخ</label>

                                <textarea wire:model="replyText"
                                          class="form-control @error('replyText') is-invalid @enderror"

                                          rows="5" placeholder="متن پاسخ خود را وارد کنید..."></textarea>

                                @error('replyText')

                                <div class="invalid-feedback">{{ $message }}</div>

                                @enderror

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" wire:click="closeModals">انصراف</button>

                            <button type="button" class="btn btn-primary" wire:click="saveReply">

                                <i class="ri-save-line me-1"></i>

                                ذخیره پاسخ

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        @endif



        <!-- Edit Modal -->

        @if($showEditModal)

            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">

                <div class="modal-dialog modal-dialog-centered">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title">

                                <i class="ri-edit-line me-2"></i>

                                ویرایش دیدگاه

                            </h5>

                            <button type="button" class="btn-close" wire:click="closeModals"></button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <label class="form-label">متن دیدگاه</label>

                                <textarea wire:model="editComment"
                                          class="form-control @error('editComment') is-invalid @enderror"

                                          rows="5"></textarea>

                                @error('editComment')

                                <div class="invalid-feedback">{{ $message }}</div>

                                @enderror

                            </div>

                            <div class="mb-3">

                                <label class="form-label">تعداد پسندیدن</label>

                                <input type="number" wire:model="editLikes"
                                       class="form-control @error('editLikes') is-invalid @enderror"

                                       min="0">

                                @error('editLikes')

                                <div class="invalid-feedback">{{ $message }}</div>

                                @enderror

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" wire:click="closeModals">انصراف</button>

                            <button type="button" class="btn btn-primary" wire:click="saveEdit">

                                <i class="ri-save-line me-1"></i>

                                ذخیره تغییرات

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        @endif



        <!-- Delete Modal -->

        @if($showDeleteModal)

            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">

                <div class="modal-dialog modal-dialog-centered">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title text-danger">

                                <i class="ri-delete-bin-line me-2"></i>

                                حذف دیدگاه

                            </h5>

                            <button type="button" class="btn-close" wire:click="closeModals"></button>

                        </div>

                        <div class="modal-body text-center">

                            <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"

                                       colors="primary:#405189,secondary:#f06548"
                                       style="width:90px;height:90px"></lord-icon>

                            <h5 class="mt-3">آیا از حذف این دیدگاه اطمینان دارید؟</h5>

                            <p class="text-muted">با حذف این دیدگاه، تمامی اطلاعات مرتبط شامل پاسخ‌ها و لایک‌ها نیز حذف
                                خواهند شد.</p>

                        </div>

                        <div class="modal-footer justify-content-center">

                            <button type="button" class="btn btn-secondary" wire:click="closeModals">انصراف</button>

                            <button type="button" class="btn btn-danger" wire:click="deleteComment">

                                <i class="ri-delete-bin-line me-1"></i>

                                بله، حذف شود

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        @endif

    </div>
</div>
