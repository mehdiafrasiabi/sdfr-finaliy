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

                    {{--
                           ارسال به:
                           برای جلوگیری از loading غیرضروری، sendType با Alpine.js
                           به‌صورت client-side مدیریت می‌شود. wire:model (بدون .live)
                           مقدار را فقط هنگام submit فرم به Livewire می‌فرستد.
                       --}}
                    <div class="mb-3" x-data="{ localSendType: '{{ $sendType }}' }">
                        <label class="form-label fw-semibold">
                            ارسال به:
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            x-model="localSendType"
                            wire:model="sendType"
                            class="form-select"
                        >
                            <option value="all">همه دانش‌آموزان من</option>
                            <option value="single">انتخاب دانش‌آموز</option>
                        </select>
                        <!-- انتخاب دانش‌آموز (فقط در حالت تکی) - client-side toggle -->
                        <div x-show="localSendType === 'single'" x-cloak class="mt-3">
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

                        <!-- نمایش لیست دانش‌آموزان (در حالت همه) - client-side toggle -->
                        <div x-show="localSendType !== 'single'" x-cloak class="mt-3">
                            <label class="form-label text-body-secondary">
                                دانش‌آموزان شما ({{ count($students) }} نفر):
                            </label>

                            <div class="border rounded p-3" style="max-height:150px; overflow:auto;">
                                @forelse($students as $student)
                                    <div class="d-flex align-items-center gap-2 py-1 small">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                                        </svg>

                                        <span>{{ $student->user?->personalInformation?->name ?? $student->user?->name ?? 'نامشخص' }}</span>
                                    </div>
                                @empty
                                    <div class="text-center text-body-secondary py-2">
                                        هیچ دانش‌آموزی یافت نشد
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- دکمه‌ها -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.student.index') }}" class="btn btn-outline-danger">
                            خروج
                        </a>

                        <button type="submit" class="btn btn-success flex-grow-1">
                            <div wire:loading.remove wire:target="send"
                                  class="d-flex align-items-center justify-content-center gap-2">
                               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                   <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z"/>
                               </svg>
                                ثبت و ارسال
                            </div>

                            <div
                                  class="d-flex align-items-center justify-content-center">
                                <span wire:loading wire:target="send" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </div>
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
                                    @if($notif->target_type === 'all_students')
                                        @php
                                            $readCount  = $notif->recipients->where('is_read', true)->count();
                                            $totalCount = $notif->recipients->count();
                                        @endphp
                                        <span class="badge text-bg-info d-inline-flex align-items-center gap-1">
                                            <i class="material-symbols-outlined" style="font-size:14px;">group</i>
                                            {{ $readCount }}/{{ $totalCount }} خوانده
                                        </span>
                                    @else
                                        @php $recipient = $notif->recipients->first(); @endphp
                                        @if($recipient && $recipient->is_read)
                                            <span class="badge text-bg-success d-inline-flex align-items-center gap-1">
                                                <i class="material-symbols-outlined" style="font-size:16px;">check_circle</i>
                                                خوانده شده
                                            </span>
                                        @else
                                            <span class="badge text-bg-warning d-inline-flex align-items-center gap-1">
                                                <i class="material-symbols-outlined" style="font-size:16px;">schedule</i>
                                                خوانده نشده
                                            </span>
                                        @endif
                                    @endif
                                </td>

                                <td class="text-nowrap">
                                    <div>{{ jalali($notif->created_at)->format('%d %B %Y') }}</div>
                                    <div class="small text-body-secondary">{{ $notif->created_at->format('H:i') }}</div>
                                </td>

                                <td class="text-nowrap">
                                    @if($notif->target_type === 'all_students')
                                        <span class="text-body-secondary small">مشاهده در مودال</span>
                                    @else
                                        @php $recipient = $notif->recipients->first(); @endphp
                                        @if($recipient && $recipient->is_read && $recipient->read_at)
                                            <div>{{ jalali($recipient->read_at)->format('%d %B %Y') }}</div>
                                            <div class="small text-body-secondary">{{ $recipient->read_at->format('H:i') }}</div>
                                        @else
                                            <span class="text-body-secondary">-</span>
                                        @endif
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
                                    <div class="fs-3 fw-bold">{{ count($recipientsList) }}</div>
                                    <div class="small text-body-secondary">کل گیرندگان</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3 text-center bg-body-tertiary">
                                    <div class="fs-3 fw-bold text-success">
                                        {{ collect($recipientsList)->where('is_read', true)->count() }}
                                    </div>
                                    <div class="small text-body-secondary">خوانده شده</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3 text-center bg-body-tertiary">
                                    <div class="fs-3 fw-bold text-warning">
                                        {{ collect($recipientsList)->where('is_read', false)->count() }}
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
                                @foreach($recipientsList as $recipient)

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $recipient->user?->personalInformation?->name ?? $recipient->user?->name ?? 'نامشخص' }}</td>
                                        <td>
                                            @if($recipient->is_read)
                                                <span class="badge text-bg-success d-inline-flex align-items-center gap-1">
                                                    <i class="material-symbols-outlined" style="font-size:14px;">check</i>
                                                    خوانده شده
                                                </span>
                                            @else
                                                <span class="badge text-bg-secondary d-inline-flex align-items-center gap-1">

                                                    <i class="material-symbols-outlined" style="font-size:14px;">schedule</i>
                                                    خوانده نشده
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($recipient->is_read && $recipient->read_at)
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
