<div class="col-md-12">

    @push('link')
        <style>
            .wrap-text {
                word-wrap: break-word;
                white-space: normal;
                overflow-wrap: break-word;
                max-width: 300px; /* یا هر عرض دلخواه */
            }
        </style>
    @endpush

    {{-- ردیف اول: فیلتر و خروجی اکسل + دانش‌آموزان بدون گزارش --}}
    <div class="row g-4 mb-4">

        {{-- ستون ۱: فیلتر تاریخ و خروجی اکسل --}}
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">خروجی اکسل</h5>
                </div>

                <div class="card-body">
                    {{-- توضیح بازه بررسی --}}
                    <div class="mb-3">
                        بازه بررسی عدم ارسال گزارش به‌صورت خودکار از
                        <span class="font-semibold">۰۰:۰۰ تا ۲۳:۵۹</span>
                        همان روز محاسبه می‌شود.

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

                    {{-- دکمه خروجی اکسل --}}
                    <div class="mt-3 text-start text-md-end">
                        <button wire:click="exportExcel"
                                type="button"
                                wire:loading.attr="disabled"
                                class="btn btn-outline-primary d-inline-flex align-items-center">
                            <span class="position-relative">
                                <i class="material-symbols-outlined me-1 align-middle">
                                    download
                                </i>
                                <span wire:loading.remove>خروجی اکسل</span>
                                <span wire:loading>در حال آماده‌سازی...</span>
                            </span>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- ستون ۲: دانش‌آموزان بدون گزارش در بازه روزانه --}}
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">دانش‌آموزان بدون گزارش در بازه روزانه</h5>
                </div>

                <div class="card-body">
                    <div class="card border-0 bg-danger bg-opacity-10 p-3 mb-0">

                        <div class="small text-muted mb-2">
                            بازه بررسی:
                            {{ jalali($windowStart)->format('%d %B %Y | H:i') }}
                            تا
                            {{ jalali($windowEnd)->format('%d %B %Y | H:i') }}
                        </div>

                        @if(empty($studentsWithoutReports))
                            <div class="text-sm text-dark">
                                تمامی دانش‌آموزان در این بازه گزارش ارسال کرده‌اند.
                            </div>
                        @else
                            <div class="mt-2">
                                <div class="fw-semibold mb-2">
                                    اسامی دانش‌آموزان بدون گزارش:
                                </div>
                                <ul class="list-unstyled mb-0 inline-flex">
                                    @foreach($studentsWithoutReports as $studentName)
                                        <li class="mb-1">
                                            <span class="badge bg-warning">
                                                {{ $studentName }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- ردیف دوم: لیست گزارش‌های دانش‌آموز --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">

                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">لیست گزارش های دانش آموز</h5>

                    <div class="d-flex flex-wrap gap-2">
                        <button
                            wire:click="bulkAction('completed')"
                            type="button"
                            class="btn btn-outline-success btn-sm">
                            تایید گزارش
                        </button>
                        <button
                            wire:click="bulkAction('rejected')"
                            type="button"
                            class="btn btn-outline-danger btn-sm">
                            رد گزارش
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th scope="col">
                                    <div class="form-check">
                                        <input wire:model.live="selectAll"
                                               type="checkbox"
                                               class="form-check-input cursor-pointer">
                                    </div>
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">دانش آموز</th>
                                <th scope="col">توضیحات</th>
                                <th scope="col">فایل</th>
                                <th scope="col">نظر مشاور</th>
                                <th scope="col">تاریخ ثبت</th>
                                <th scope="col">پارت های موظفی</th>
                                <th scope="col">تست موظفی</th>
                                <th scope="col">تست انجام شده</th>
                                <th scope="col">درگیر با گوشی (درسی)</th>
                                <th scope="col">درگیر با گوشی (غیر درسی)</th>
                                <th scope="col">رضایت</th>
                                <th scope="col">عملیات</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($reports as $report)
                                <tr>
                                    {{-- چک‌باکس انتخاب --}}
                                    <td>
                                        <div class="form-check">
                                            <input
                                                wire:model.live="selectedReports"
                                                value="{{ $report->id }}"
                                                type="checkbox"
                                                class="form-check-input cursor-pointer">
                                        </div>
                                    </td>

                                    {{-- ردیف --}}
                                    <td>
                                        {{ $loop->iteration + $reports->firstItem() - 1 }}
                                    </td>

                                    {{-- نام دانش‌آموز --}}
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $report->student->user->name ?? '----' }}
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

                                            <span class="fw-medium d-block">
                                                {{ $firstPart }}
                                            </span>

                                            @if (!empty($restPart))
                                                <span x-show="!open"
                                                      @click="open = true"
                                                      class="text-primary small"
                                                      style="cursor:pointer;">
                                                    بیشتر...
                                                </span>

                                                <div x-show="open" class="mt-1">
                                                    <p class="wrap-text mb-1 small">
                                                        {{ $restPart }}
                                                    </p>
                                                    <span
                                                        @click="open = false"
                                                        class="text-danger small"
                                                        style="cursor:pointer;">
                                                        بستن
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
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

                                    {{-- نظر مشاور --}}
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <button
                                                type="button"
                                                wire:click="openCommentModal({{ $report->id }})"
                                                class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center">
                                                <i class="material-symbols-outlined me-1">chat</i>
                                                <span class="small">مشاهده / ثبت</span>
                                            </button>

                                            @if($report->advisor_comment)
                                                <div class="small text-muted">
                                                    {{ \Illuminate\Support\Str::limit($report->advisor_comment, 10) }}
                                                </div>
                                            @else
                                                <span class="small text-muted">
                                                    نظری ثبت نشده
                                                </span>
                                            @endif

                                            @if($report->student_reply)
                                                <span class="small text-success fw-semibold">
                                                    پاسخ دانش‌آموز ثبت شده
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- تاریخ ثبت --}}
                                    <td>
                                        {{ jalali($report->created_at)->format('%d %B %Y | H:i:s') }}
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

                                    {{-- رضایت --}}
                                    <td>
                                        <span class="badge bg-secondary">
                                            امتیاز: {{ $report->complacent ?? '--' }} / 10
                                        </span>
                                    </td>

                                    {{-- عملیات / حذف --}}
                                    <td>
                                        @can('delete_reports_for_academic_support')
                                            <button
                                                type="button"
                                                wire:confirm="آیا مطمئن هستید؟"
                                                wire:click="delete({{ $report->id }})"
                                                class="btn btn-sm btn-outline-danger d-inline-flex align-items-center">
                                                <i class="material-symbols-outlined me-1">delete</i>
                                                حذف
                                            </button>
                                        @else
                                            <span class="badge bg-danger d-inline-flex align-items-center">
                                                <i class="material-symbols-outlined me-1">delete</i>
                                                عدم دسترسی حذف!!
                                            </span>
                                        @endcan
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center py-4">
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

    {{-- مودال نظر مشاور --}}
    @if($commentModalOpen)
        <div class="modal fade show d-block"
             tabindex="-1"
             style="background: rgba(0,0,0,.6);"
             wire:click.self="closeCommentModal">
            <div class="modal-dialog modal-lg modal-dialog-centered"
                 wire:keydown.escape="closeCommentModal">
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
                        <button type="button"
                                class="btn-close"
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
                            <button type="button"
                                    wire:click="saveAdvisorComment"
                                    wire:loading.attr="disabled"
                                    class="btn btn-primary">
                                <span wire:loading.remove wire:target="saveAdvisorComment">
                                    ثبت نظر
                                </span>
                                <span wire:loading wire:target="saveAdvisorComment"
                                      class="d-inline-flex align-items-center">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    در حال ثبت...
                                </span>
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    @endif

</div>
