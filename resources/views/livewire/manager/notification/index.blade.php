<div>

    <div class="row">

        <div class="col-xxl-4 col-lg-5">

            <div class="card">

                <div class="card-header align-items-center d-flex">

                    <h4 class="card-title mb-0 flex-grow-1">

                        <i class="ri-notification-3-line me-2"></i>

                        ارسال اطلاع‌رسانی جدید

                    </h4>

                </div>


                <div class="card-body">

                    <form wire:submit="send">

                        <!-- عنوان پیام -->

                        <div class="mb-3">

                            <label for="title" class="form-label">

                                عنوان پیام

                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"

                                   wire:model="title"

                                   id="title"

                                   class="form-control @error('title') is-invalid @enderror"

                                   placeholder="مثال: اطلاعیه مهم آزمون">

                            @error('title')

                            <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>


                        <!-- توضیحات -->

                        <div class="mb-3">

                            <label for="body" class="form-label">

                                توضیحات

                                <span class="text-danger">*</span>

                            </label>

                            <textarea wire:model="body"

                                      id="body"

                                      rows="4"

                                      class="form-control @error('body') is-invalid @enderror"

                                      placeholder="متن پیام خود را وارد کنید..."></textarea>

                            @error('body')

                            <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>


                        <!-- دسته‌بندی پیام -->

                        <div class="mb-3">

                            <label for="category" class="form-label">

                                دسته‌بندی پیام

                                <span class="text-danger">*</span>

                            </label>

                            <select wire:model="category"

                                    id="category"

                                    class="form-select @error('category') is-invalid @enderror">

                                @foreach($categories as $key => $label)

                                    <option value="{{ $key }}">{{ $label }}</option>

                                @endforeach

                            </select>

                            @error('category')

                            <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>


                        <!-- ارسال به -->

                        <div class="mb-3">

                            <label for="targetType" class="form-label">

                                ارسال به

                                <span class="text-danger">*</span>

                            </label>

                            <select wire:model="targetType"

                                    id="targetType"

                                    class="form-select @error('targetType') is-invalid @enderror">

                                @foreach($targetTypes as $key => $label)

                                    <option value="{{ $key }}">{{ $label }}</option>

                                @endforeach

                            </select>

                            @error('targetType')

                            <div class="invalid-feedback">{{ $message }}</div>

                            @enderror

                        </div>


                        <!-- دکمه ارسال -->

                        <div class="d-flex gap-2">

                            <button type="submit" class="btn btn-success flex-grow-1">

                                <span wire:loading.remove wire:target="send">

                                    <i class="ri-send-plane-fill me-1"></i>

                                    ارسال پیام

                                </span>

                                <span wire:loading wire:target="send">

                                    <span class="spinner-border spinner-border-sm me-1" role="status"></span>

                                    در حال ارسال...

                                </span>

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        <div class="col-xxl-8 col-lg-7">

            <div class="card">

                <div class="card-header align-items-center d-flex">

                    <h4 class="card-title mb-0 flex-grow-1">

                        <i class="ri-list-check me-2"></i>

                        لیست پیام‌های ارسال شده

                    </h4>

                    <div class="flex-shrink-0">

                        <div class="search-box">

                            <input type="text"

                                   wire:model.live.debounce.500ms="search"

                                   class="form-control"

                                   placeholder="جستجو...">

                            <i class="ri-search-line search-icon"></i>

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-light">

                            <tr>

                                <th style="width: 50px;">#</th>

                                <th>عنوان</th>

                                <th>دسته‌بندی</th>

                                <th>ارسال به</th>

                                <th>تعداد گیرندگان</th>

                                <th>خوانده شده</th>

                                <th>تاریخ</th>

                                <th style="width: 120px;">عملیات</th>

                            </tr>

                            </thead>

                            <tbody>

                            @forelse($notifications as $notification)

                                <tr>

                                    <td>{{ $loop->iteration + $notifications->firstItem() - 1 }}</td>

                                    <td>

                                        <div class="d-flex align-items-center">

                                            <div>

                                                <h6 class="mb-0">{{ Str::limit($notification->title, 30) }}</h6>

                                                <small
                                                    class="text-muted">{{ Str::limit($notification->body, 40) }}</small>

                                            </div>

                                        </div>

                                    </td>

                                    <td>

                                        <span
                                            class="badge {{ $notification->category === 'special' ? 'bg-warning' : 'bg-info' }}">

                                            {{ $notification->category_label }}

                                        </span>

                                    </td>

                                    <td>

                                        <span class="badge bg-primary">

                                            {{ $notification->target_type_label }}

                                        </span>

                                    </td>

                                    <td>

                                        <button type="button"

                                                wire:click="showRecipients({{ $notification->id }})"

                                                class="btn btn-sm btn-soft-primary">

                                            <i class="ri-group-line me-1"></i>

                                            {{ $notification->recipients_count }} نفر

                                        </button>

                                    </td>

                                    <td>

                                        <div class="d-flex align-items-center gap-2">

                                            <div class="progress flex-grow-1" style="height: 8px; min-width: 60px;">

                                                @php

                                                    $percentage = $notification->recipients_count > 0

                                                        ? round(($notification->read_recipients_count / $notification->recipients_count) * 100)

                                                        : 0;

                                                @endphp

                                                <div class="progress-bar bg-success"
                                                     style="width: {{ $percentage }}%"></div>

                                            </div>

                                            <small class="text-muted">{{ $percentage }}%</small>

                                        </div>

                                    </td>

                                    <td>

                                        <small>{{ jalali($notification->created_at)->format('%d %B %Y') }}</small>

                                        <br>

                                        <small class="text-muted">{{ $notification->created_at->format('H:i') }}</small>

                                    </td>

                                    <td>

                                        <button wire:click="delete({{ $notification->id }})"

                                                wire:confirm="آیا از حذف این پیام مطمئن هستید؟"

                                                class="btn btn-sm btn-soft-danger">

                                            <i class="ri-delete-bin-line"></i>

                                        </button>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8" class="text-center py-4">

                                        <div class="text-center">

                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"

                                                       trigger="loop"

                                                       colors="primary:#121331,secondary:#08a88a"

                                                       style="width:75px;height:75px"></lord-icon>

                                            <h5 class="mt-2">هیچ پیامی یافت نشد</h5>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>


                    @if($notifications->hasPages())

                        <div class="d-flex justify-content-center mt-3">

                            {{ $notifications->links() }}

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    <!-- مودال نمایش گیرندگان -->

    @if($showRecipientsModal && $selectedNotification)

        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">

            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">

                            <i class="ri-group-line me-2"></i>

                            لیست گیرندگان - {{ $selectedNotification->title }}

                        </h5>

                        <button type="button" class="btn-close" wire:click="closeRecipientsModal"></button>

                    </div>

                    <div class="modal-body">

                        <div class="mb-3">

                            <div class="row text-center">

                                <div class="col-4">

                                    <div class="p-2 bg-light rounded">

                                        <h5 class="mb-1">{{ $recipientsList->count() }}</h5>

                                        <small class="text-muted">کل گیرندگان</small>

                                    </div>

                                </div>

                                <div class="col-4">

                                    <div class="p-2 bg-success-subtle rounded">

                                        <h5 class="mb-1 text-success">{{ $recipientsList->where('is_read', true)->count() }}</h5>

                                        <small class="text-muted">خوانده شده</small>

                                    </div>

                                </div>

                                <div class="col-4">

                                    <div class="p-2 bg-danger-subtle rounded">

                                        <h5 class="mb-1 text-danger">{{ $recipientsList->where('is_read', false)->count() }}</h5>

                                        <small class="text-muted">خوانده نشده</small>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="table-responsive" style="max-height: 400px;">

                            <table class="table table-sm table-bordered">

                                <thead class="table-light sticky-top">

                                <tr>

                                    <th>#</th>

                                    <th>نام کاربر</th>

                                    <th>وضعیت</th>

                                    <th>زمان خواندن</th>

                                </tr>

                                </thead>

                                <tbody>

                                @foreach($recipientsList as $recipient)

                                    <tr>

                                        <td>{{ $loop->iteration }}</td>

                                        <td>

                                            {{ $recipient->user?->personalInformation?->name ?? $recipient->user?->name ?? 'نامشخص' }}

                                        </td>

                                        <td>

                                            @if($recipient->is_read)

                                                <span class="badge bg-success">

                                                    <i class="ri-check-line me-1"></i>

                                                    خوانده شده

                                                </span>

                                            @else

                                                <span class="badge bg-secondary">

                                                    <i class="ri-time-line me-1"></i>

                                                    خوانده نشده

                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            @if($recipient->is_read && $recipient->read_at)

                                                {{ jalali($recipient->read_at)->format('%d %B %Y - H:i') }}

                                            @else

                                                <span class="text-muted">-</span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" wire:click="closeRecipientsModal">

                            بستن

                        </button>

                    </div>

                </div>

            </div>

        </div>

    @endif

</div>
