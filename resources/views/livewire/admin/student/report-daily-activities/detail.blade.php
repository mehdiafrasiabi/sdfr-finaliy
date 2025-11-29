<div class="container-fluid">

    {{-- ردیف اول: نام دانش‌آموز --}}
    <div class="mb-3">
        <h4  wire:ignore class="py-3 mb-4">
            <span class="text-muted fw-light">گزارش جامع /</span>
            <span class="text-success"> {{ $studentName }}</span>
        </h4>
    </div>

    {{-- ردیف دوم: کارت فیلتر و خروجی اکسل --}}
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">خروجی اکسل</h5>
                </div>

                <div class="card-body">

                    {{-- فیلتر وضعیت --}}
                    <div class="mb-3">
                        <label class="form-label">
                            وضعیت گزارش
                        </label>
                        <select
                            wire:model.live.debounce.500ms="status"
                            class="form-select">
                            <option selected>برای تغییر وضعیت انتخاب کنید</option>
                            <option value="all">همه وضعیت‌ها</option>
                            <option value="pending">در انتظار</option>
                            <option value="completed">تایید شده</option>
                            <option value="rejected">رد شده</option>
                        </select>
                    </div>

                    {{-- از تاریخ --}}
                    <div class="mb-3">
                        <label class="form-label">
                            از تاریخ (شمسی)
                        </label>
                        <input type="text"
                               name="startDate"
                               wire:model="startDate"
                               class="form-control"
                               placeholder="1404/05/24">
                        @error('startDate')
                        <div class="form-text text-danger mt-1">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    {{-- تا تاریخ --}}
                    <div class="mb-3">
                        <label class="form-label">
                            تا تاریخ (شمسی)
                        </label>
                        <input type="text"
                               name="endDate"
                               wire:model="endDate"
                               class="form-control"
                               placeholder="1404/06/24">
                        @error('endDate')
                        <div class="form-text text-danger mt-1">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mt-3 text-start text-md-end">
                        <button wire:click="exportExcel"
                                type="button"
                                wire:loading.attr="disabled"
                                class="btn btn-outline-primary d-inline-flex align-items-center">
                            <i class="material-symbols-outlined me-1">
                                download
                            </i>
                            <span wire:loading.remove>خروجی اکسل</span>
                            <span wire:loading>در حال آماده‌سازی...</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ردیف سوم: لیست گزارش‌های دانش‌آموز --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">لیست گزارش های دانش آموز</h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">دانش آموز</th>
                                <th scope="col">پارت های موظفی</th>
                                <th scope="col">تست موظفی</th>
                                <th scope="col">تست انجام شده</th>
                                <th scope="col">درگیر با گوشی (درسی)</th>
                                <th scope="col">درگیر با گوشی (غیر درسی)</th>
                                <th scope="col">توضیحات</th>
                                <th scope="col">رضایت</th>
                                <th scope="col">فایل</th>
                                <th scope="col">نظر مشاور</th>
                                <th scope="col">وضعیت</th>
                                <th scope="col">تاریخ ثبت درخواست</th>
                                <th scope="col">تاریخ تغییر وضعیت</th>
                                <th scope="col">عملیات</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($reports as $report)
                                <tr>

                                    {{-- شماره ردیف --}}
                                    <td>
                                        {{ $loop->iteration + $reports->firstItem() - 1 }}
                                    </td>

                                    {{-- نام دانش‌آموز --}}
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $report->student->user->name ?? '----' }}
                                        </span>
                                    </td>

                                    {{-- پارت‌های موظفی --}}
                                    <td>
                                        <span class="fw-medium">
                                            {{ $report->required_parts ?? '---' }}
                                        </span>
                                    </td>

                                    {{-- تست موظفی --}}
                                    <td>
                                        <span class="fw-medium">
                                            {{ $report->required_tests ?? '---' }}
                                        </span>
                                    </td>

                                    {{-- تست انجام شده --}}
                                    <td>
                                        <span class="fw-medium">
                                            {{ $report->done_tests ?? '---' }}
                                        </span>
                                    </td>

                                    {{-- درگیر با گوشی (درسی) --}}
                                    <td>
                                        <span class="fw-medium">
                                            {{ $report->phone_study_hours ?? '---' }}
                                        </span>
                                    </td>

                                    {{-- درگیر با گوشی (غیر درسی) --}}
                                    <td>
                                        <span class="fw-medium">
                                            {{ $report->phone_nonstudy_hours ?? '---' }}
                                        </span>
                                    </td>

                                    {{-- توضیحات با "بیشتر..." --}}
                                    <td>
                                        <div x-data="{ open: false }">
                                            @php
                                                $description = $report->description ?? '';
                                                $words = explode(' ', $description);
                                                $firstPart = implode(' ', array_slice($words, 0, 10));
                                                $restPart = implode(' ', array_slice($words, 10));
                                            @endphp

                                            <span class="fw-medium">
                                                {{ $firstPart }}
                                            </span>

                                            @if (!empty($restPart))
                                                <div>
                                                    <span x-show="!open"
                                                          @click="open = true"
                                                          class="text-primary"
                                                          style="cursor: pointer;">
                                                        بیشتر...
                                                    </span>
                                                    <div x-show="open" class="mt-2">
                                                        <p class="mb-1">
                                                            {{ $restPart }}
                                                        </p>
                                                        <span @click="open = false"
                                                              class="text-danger"
                                                              style="cursor: pointer;">
                                                            بستن
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- رضایت --}}
                                    <td>
                                        <span class="badge bg-secondary">
                                            امتیاز: {{ $report->complacent ?? '--' }} / 10
                                        </span>
                                    </td>

                                    {{-- فایل --}}
                                    <td>
                                        @if(isset($report->report_file))
                                            <a href="{{ asset('students/reportsDaily/'.$report->student_id).'/'.$report->report_file }}"
                                               target="_blank">
                                                مشاهده
                                            </a>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                فایلی وجود ندارد
                                            </span>
                                        @endif
                                    </td>

                                    {{-- نظر مشاور (دکمه + خلاصه) --}}
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <button
                                                type="button"
                                                wire:click="openCommentModal({{ $report->id }})"
                                                class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center">
                                                <i class="material-symbols-outlined me-1">chat</i>
                                                مشاهده / ثبت
                                            </button>

                                            @if($report->advisor_comment)
                                                <div class="small text-muted">
                                                    {{ \Illuminate\Support\Str::limit($report->advisor_comment, 10) }}
                                                </div>
                                            @else
                                                <span class="small text-muted">نظری ثبت نشده</span>
                                            @endif

                                            @if($report->student_reply)
                                                <span class="small text-success fw-semibold">
                                                    پاسخ دانش‌آموز ثبت شده
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- وضعیت --}}
                                    <td>
                                        @can('edit_reports_for_academic_support')
                                            <select
                                                wire:confirm="آیا از انتخاب خود برای تغییر وضعیت اطمینان دارید ؟"
                                                wire:change="changeStatus({{ $report->id }}, $event.target.value)"
                                                class="form-select form-select-sm">
                                                <option
                                                    value="pending" {{ $report->status=='pending' ? 'selected' :'' }}>
                                                    در انتظار تایید مشاور یا پشتیبان
                                                </option>
                                                <option
                                                    value="completed" {{ $report->status=='completed' ? 'selected' :'' }}>
                                                    تایید گزارش و درست بودن آن
                                                </option>
                                                <option
                                                    value="rejected" {{ $report->status=='rejected' ? 'selected' :'' }}>
                                                    رد گزارش
                                                </option>
                                            </select>
                                        @else
                                            @if($report->status=='pending')
                                                <span class="badge bg-primary">
                                                    در انتظار تایید مشاور یا پشتیبان
                                                </span>
                                            @elseif($report->status=='completed')
                                                <span class="badge bg-success">
                                                    توسط مشاور یا پشتیبان گزارش تایید شده است
                                                </span>
                                            @elseif($report->status=='rejected')
                                                <span class="badge bg-danger">
                                                    به صلاح دید مشاور یا پشتیبان رد شده است
                                                </span>
                                            @endif
                                        @endcan
                                    </td>

                                    {{-- تاریخ ثبت --}}
                                    <td>
                                        {{ jalali($report->created_at)->format('%d %B %Y | H:i:s') }}
                                    </td>

                                    {{-- تاریخ تغییر وضعیت --}}
                                    <td>
                                        {{ jalali($report->updated_at)->format('%d %B %Y | H:i:s') }}
                                    </td>

                                    {{-- عملیات حذف --}}
                                    <td>
                                        <button
                                            type="button"
                                            wire:confirm="آیا مطمئن هستید؟"
                                            wire:click="delete({{ $report->id }})"
                                            class="btn btn-sm btn-outline-danger d-inline-flex align-items-center">
                                            <i class="material-symbols-outlined me-1">delete</i>
                                            حذف
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="16" class="text-center py-4">
                                        <div>
                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                                                       trigger="loop"
                                                       colors="primary:#121331,secondary:#08a88a"
                                                       style="width:75px;height:75px"></lord-icon>
                                            <h5 class="mt-2 mb-0">
                                                متاسفیم! هیچ نتیجه‌ای یافت نشد
                                            </h5>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-center justify-content-md-between align-items-center">
                    {{ $reports->links('layouts.admin.pagination') }}
                </div>

            </div>
        </div>
    </div>

    {{-- مودال نظر مشاور (Bootstrap-style) --}}
    @if($commentModalOpen)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.6);"
             wire:click.self="closeCommentModal">
            <div class="modal-dialog modal-lg modal-dialog-centered" wire:keydown.escape="closeCommentModal">
                <div class="modal-content">

                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title">
                                نظر مشاور برای {{ $commentStudentName ?: 'دانش‌آموز' }}
                            </h5>
                            <p class="small text-muted mb-0">
                                برای هر گزارش تنها یک نظر از سوی مشاور و یک پاسخ از سوی دانش‌آموز ثبت می‌شود.
                            </p>
                        </div>
                        <button type="button" class="btn-close"
                                aria-label="Close"
                                wire:click="closeCommentModal"></button>
                    </div>

                    <div class="modal-body">
                        @if($advisorCommentReadonly)
                            <div class="mb-3">
                                <div class="fw-semibold mb-2">
                                    نظر ثبت شده مشاور
                                </div>
                                <p class="p-2 rounded bg-primary text-white d-inline-block">
                                    {{ $advisorCommentInput }}
                                </p>
                            </div>

                            @if($commentStudentReply)
                                <div class="mb-3">
                                    <div class="fw-semibold text-success mb-2">
                                        پاسخ دانش‌آموز
                                    </div>
                                    <p class="p-2 rounded text-white d-inline-block"
                                       style="background-color:#0b9c0b;">
                                        {{ $commentStudentReply }}
                                    </p>
                                </div>
                            @endif
                        @else
                            <div class="mb-3">
                                <label class="form-label">
                                    متن نظر مشاور
                                </label>
                                <textarea
                                    wire:model.defer="advisorCommentInput"
                                    class="form-control"
                                    rows="4"
                                    placeholder="بسیار عالی بود !"></textarea>
                                @error('advisorCommentInput')
                                <div class="form-text text-danger mt-1">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button"
                                class="btn btn-danger"
                                wire:click="closeCommentModal">
                            بستن
                        </button>

                        @if(!$advisorCommentReadonly)

                            <button
                                wire:click="saveAdvisorComment"
                                wire:loading.attr="disabled"
                                type="submit" class="btn btn-success d-flex align-items-center">

                                <div  class="d-flex align-items-center">
                                    <span  wire:loading.remove class="ms-1" wire:target="saveAdvisorComment">ثبت و ارسال</span>

                                    <span wire:target="saveAdvisorComment" wire:loading class="spinner-border spinner-border-sm ms-2" role="status"
                                          aria-hidden="true">

                                    </span>
                                </div>

                            </button>

                        @endif

                    </div>

                </div>
            </div>
        </div>
    @endif

</div>
