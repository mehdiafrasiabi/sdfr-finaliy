<div class="row g-4">

    <!-- فرم ارسال اعلان -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0">
                    <i class="material-symbols-outlined align-middle ms-1">notifications</i>
                    ارسال اعلان به دانش‌آموز
                </h5>
            </div>

            <div class="card-body">
                <form wire:submit.prevent="send">

                    <!-- عنوان اعلان -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            عنوان اعلان:
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            wire:model="title"
                            class="form-control @error('title') is-invalid @enderror"
                            placeholder="عنوان پیام خود را وارد کنید"
                        >

                        @error('title')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- توضیحات -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            توضیحات:
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            rows="4"
                            wire:model="body"
                            class="form-control @error('body') is-invalid @enderror"
                            placeholder="متن پیام خود را وارد کنید..."
                        ></textarea>

                        @error('body')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ارسال به -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            ارسال به:
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            wire:model.live="sendType"
                            class="form-select"
                        >
                            <option value="all">همه دانش‌آموزان من</option>
                            <option value="single">انتخاب دانش‌آموز</option>
                        </select>
                    </div>

                    <!-- انتخاب دانش‌آموز (فقط در حالت تکی) -->
                    @if($sendType === 'single')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                انتخاب دانش‌آموز:
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                wire:model="studentId"
                                class="form-select @error('studentId') is-invalid @enderror"
                            >
                                <option value="">-- انتخاب کنید --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">
                                        {{ $student->user?->personalInformation?->name ?? $student->user?->name ?? 'نامشخص' }}
                                    </option>
                                @endforeach
                            </select>

                            @error('studentId')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <!-- نمایش لیست دانش‌آموزان -->
                        <div class="mb-3">
                            <label class="form-label text-body-secondary">
                                دانش‌آموزان شما ({{ count($students) }} نفر):
                            </label>

                            <div class="border rounded p-3" style="max-height:150px; overflow:auto;">
                                @forelse($students as $student)
                                    <div class="d-flex align-items-center gap-2 py-1 small">
                                        <i class="material-symbols-outlined text-success"
                                           style="font-size:16px;">person</i>
                                        <span>{{ $student->user?->personalInformation?->name ?? $student->user?->name ?? 'نامشخص' }}</span>
                                    </div>
                                @empty
                                    <div class="text-center text-body-secondary py-2">
                                        هیچ دانش‌آموزی یافت نشد
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    <!-- دکمه‌ها -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.student.index') }}" class="btn btn-outline-danger">
                            خروج
                        </a>

                        <button type="submit" class="btn btn-success flex-grow-1">
                            <span wire:loading.remove wire:target="send"
                                  class="d-flex align-items-center justify-content-center gap-2">
                                <i class="material-symbols-outlined">send</i>
                                ثبت و ارسال
                            </span>

                            <span wire:loading wire:target="send"
                                  class="d-flex align-items-center justify-content-center">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <!-- جدول اعلان‌ها -->
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                <h5 class="mb-0">
                    <i class="material-symbols-outlined align-middle ms-1">list</i>
                    لیست اعلان‌های ارسال شده
                </h5>

                <form class="position-relative" style="max-width:240px;">
                    <span class="position-absolute top-50 translate-middle-y ms-2 text-body-secondary">
                        <i class="material-symbols-outlined" style="font-size:20px;">search</i>
                    </span>

                    <input
                        type="text"
                        placeholder="جستجو..."
                        wire:model.live.debounce.350ms="search"
                        class="form-control form-control-sm ps-5"
                    >
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th class="text-nowrap">#</th>
                            <th class="text-nowrap">دانش‌آموز</th>
                            <th class="text-nowrap">عنوان</th>
                            <th class="text-nowrap">وضعیت</th>
                            <th class="text-nowrap">تاریخ ثبت</th>
                            <th class="text-nowrap">زمان خواندن</th>
                            <th class="text-nowrap">عملیات</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($notifications as $notif)
                            <tr>
                                <td class="text-nowrap">
                                    {{ $loop->iteration + $notifications->firstItem() - 1 }}
                                </td>

                                <td class="text-nowrap">
                                    @if($notif->target_type === 'all_students')
                                        <button
                                            wire:click="showRecipients({{ $notif->id }})"
                                            class="btn btn-sm btn-outline-primary rounded-pill d-inline-flex align-items-center gap-1"
                                            type="button"
                                        >
                                            <i class="material-symbols-outlined" style="font-size:16px;">group</i>
                                            همه دانش‌آموزان
                                        </button>
                                    @else
                                        {{ $notif->student?->user?->personalInformation?->name ?? 'نامشخص' }}
                                    @endif
                                </td>

                                <td>
                                    <p class="wrap-text fw-semibold mb-1">{{ $notif->title }}</p>
                                    <p class="wrap-text small text-body-secondary mb-0">{{ Str::limit($notif->body, 50) }}</p>
                                </td>

                                <td class="text-nowrap">
                                    @php $recipient = $notif->recipients->first(); @endphp

                                    @if($recipient && $recipient->is_read)
                                        <span class="badge text-bg-success d-inline-flex align-items-center gap-1">
                                            <i class="material-symbols-outlined"
                                               style="font-size:16px;">check_circle</i>
                                            خوانده شده
                                        </span>
                                    @else
                                        <span class="badge text-bg-warning d-inline-flex align-items-center gap-1">
                                            <i class="material-symbols-outlined" style="font-size:16px;">schedule</i>
                                            خوانده نشده
                                        </span>
                                    @endif
                                </td>

                                <td class="text-nowrap">
                                    <div>{{ jalali($notif->created_at)->format('%d %B %Y') }}</div>
                                    <div class="small text-body-secondary">{{ $notif->created_at->format('H:i') }}</div>
                                </td>

                                <td class="text-nowrap">
                                    @if($recipient && $recipient->is_read && $recipient->read_at)
                                        <div>{{ jalali($recipient->read_at)->format('%d %B %Y') }}</div>
                                        <div
                                            class="small text-body-secondary">{{ $recipient->read_at->format('H:i') }}</div>
                                    @else
                                        <span class="text-body-secondary">-</span>
                                    @endif
                                </td>

                                <td class="text-nowrap">
                                    <button
                                        wire:click="deleteNotification({{ $notif->id }})"
                                        wire:confirm="آیا از حذف این اعلان مطمئن هستید؟"
                                        class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1"
                                        type="button"
                                    >
                                        <i class="material-symbols-outlined" style="font-size:16px;">delete</i>
                                        حذف
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                               colors="primary:#121331,secondary:#08a88a"
                                               style="width:75px;height:75px"></lord-icon>
                                    <h5 class="mt-2 mb-0">هیچ اعلانی یافت نشد</h5>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-3">
                    {{ $notifications->links('layouts.admin.pagination') }}
                </div>
            </div>
        </div>
    </div>


    <!-- مودال نمایش گیرندگان -->
    @if($showRecipientsModal && $selectedNotification)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background: rgba(0,0,0,.5);"
             wire:click.self="closeRecipientsModal">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="material-symbols-outlined align-middle ms-1">group</i>
                            لیست گیرندگان - {{ $selectedNotification->title }}
                        </h5>
                        <button type="button" class="btn-close" aria-label="Close"
                                wire:click="closeRecipientsModal"></button>
                    </div>

                    <div class="modal-body">

                        <!-- آمار -->
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3 text-center bg-body-tertiary">
                                    <div class="fs-3 fw-bold">{{ $recipientsList->count() }}</div>
                                    <div class="small text-body-secondary">کل گیرندگان</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3 text-center bg-body-tertiary">
                                    <div class="fs-3 fw-bold text-success">
                                        {{ $recipientsList->filter(fn($n) => $n->recipients->first()?->is_read)->count() }}
                                    </div>
                                    <div class="small text-body-secondary">خوانده شده</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3 text-center bg-body-tertiary">
                                    <div class="fs-3 fw-bold text-warning">
                                        {{ $recipientsList->filter(fn($n) => !$n->recipients->first()?->is_read)->count() }}
                                    </div>
                                    <div class="small text-body-secondary">خوانده نشده</div>
                                </div>
                            </div>
                        </div>

                        <!-- لیست -->
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light position-sticky top-0">
                                <tr>
                                    <th class="text-nowrap">#</th>
                                    <th class="text-nowrap">نام دانش‌آموز</th>
                                    <th class="text-nowrap">وضعیت</th>
                                    <th class="text-nowrap">زمان خواندن</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($recipientsList as $notif)
                                    @php $recipient = $notif->recipients->first(); @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $notif->student?->user?->personalInformation?->name ?? 'نامشخص' }}</td>
                                        <td>
                                            @if($recipient && $recipient->is_read)
                                                <span
                                                    class="badge text-bg-success d-inline-flex align-items-center gap-1">
                                                    <i class="material-symbols-outlined"
                                                       style="font-size:14px;">check</i>
                                                    خوانده شده
                                                </span>
                                            @else
                                                <span
                                                    class="badge text-bg-secondary d-inline-flex align-items-center gap-1">
                                                    <i class="material-symbols-outlined" style="font-size:14px;">schedule</i>
                                                    خوانده نشده
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($recipient && $recipient->is_read && $recipient->read_at)
                                                {{ jalali($recipient->read_at)->format('%d %B %Y - H:i') }}
                                            @else
                                                <span class="text-body-secondary">-</span>
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
