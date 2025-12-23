<div class="container-fluid">


    {{-- Header --}}

    <div class="mb-3">

        <h4 class="py-3 mb-4">

            <span class="text-muted fw-light">گزارش روزانه /</span>

            <span class="text-success">روز {{ $yesterdayJalali }}</span>

        </h4>

    </div>


    {{-- Row: دانش‌آموزان بدون گزارش --}}

    {{-- Row: دانش‌آموزان بدون گزارش + Export Excel --}}

    <div class="row mb-4">

        <div class="col-lg-8">

            <div class="card shadow-sm">

                <div class="card-header d-flex justify-content-between align-items-center bg-warning bg-opacity-10">

                    <h5 class="mb-0">دانش‌آموزان بدون گزارش ({{ $yesterdayJalali }})</h5>

                    <span class="badge bg-warning">{{ count($studentsWithoutReports) }} نفر</span>

                </div>


                <div class="card-body">

                    @if(empty($studentsWithoutReports))

                        <div class="text-sm text-success">

                            ✅ تمامی دانش‌آموزان گزارش ارسال کرده‌اند.

                        </div>

                    @else

                        <div class="d-flex flex-wrap gap-2">

                            @foreach($studentsWithoutReports as $studentName)

                                <span class="badge bg-warning text-dark">{{ $studentName }}</span>

                            @endforeach

                        </div>

                    @endif

                </div>

            </div>

        </div>


        <div class="col-lg-4">

            <div class="card shadow-sm">

                <div class="card-header bg-success bg-opacity-10">

                    <h5 class="mb-0">خروجی اکسل</h5>

                </div>

                <div class="card-body">

                    <p class="small text-muted mb-3">

                        دانلود گزارش‌های روز {{ $yesterdayJalali }} به فرمت اکسل

                    </p>

                    <button wire:click="exportExcel" type="button"

                            wire:loading.attr="disabled"

                            class="btn btn-success w-100">

                        <span wire:loading.remove wire:target="exportExcel">

                            <i class="material-symbols-outlined me-1">download</i>

                            دانلود فایل اکسل

                        </span>

                        <span wire:loading wire:target="exportExcel">

                            <span class="spinner-border spinner-border-sm me-2"></span>

                            در حال آماده‌سازی...

                        </span>

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- Row: لیست گزارش‌ها --}}

    <div class="row">

        <div class="col-12">

            <div class="card shadow-sm">


                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <h5 class="mb-0">گزارش‌های در انتظار تایید</h5>


                    <div class="d-flex flex-wrap gap-2">

                        <button wire:click="bulkAction('approved')" type="button"

                                class="btn btn-success btn-sm">

                            <i class="material-symbols-outlined me-1">check_circle</i>

                            تایید گزارش‌ها

                        </button>

                        <button wire:click="bulkAction('rejected')" type="button"

                                class="btn btn-danger btn-sm">

                            <i class="material-symbols-outlined me-1">cancel</i>

                            رد گزارش‌ها

                        </button>

                    </div>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                            <tr>

                                <th>

                                    <div class="form-check">

                                        <input wire:model.live="selectAll" type="checkbox"

                                               class="form-check-input cursor-pointer">

                                    </div>

                                </th>

                                <th>#</th>

                                <th>دانش‌آموز</th>

                                <th>پارت‌های انجام شده</th>

                                <th>تست‌ها</th>

                                <th>ساعت گوشی</th>

                                <th>امتیاز</th>

                                <th>پارت جبرانی</th>

                                <th>نظر مشاور</th>

                                <th>وضعیت</th>

                                <th>عملیات</th>

                            </tr>

                            </thead>


                            <tbody>

                            @forelse($reports as $report)

                                <tr>

                                    {{-- Checkbox --}}

                                    <td>

                                        <div class="form-check">

                                            <input wire:model.live="selectedReports"

                                                   value="{{ $report->id }}" type="checkbox"

                                                   class="form-check-input cursor-pointer">

                                        </div>

                                    </td>


                                    {{-- ردیف --}}

                                    <td>{{ $loop->iteration + $reports->firstItem() - 1 }}</td>


                                    {{-- نام دانش‌آموز --}}

                                    <td>

                                            <span class="badge bg-primary">

                                                {{ $report->student->user->name ?? '---' }}

                                            </span>

                                    </td>


                                    {{-- پارت‌ها --}}

                                    <td>

                                        @php

                                            $completed = $report->completedParts()->count();

                                            $total = $report->parts()->where('is_compensatory', false)->count();

                                            $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

                                        @endphp

                                        <div class="d-flex align-items-center gap-2">

                                            <span class="fw-medium">{{ $completed }} / {{ $total }}</span>

                                            <div class="progress" style="width: 60px; height: 8px;">

                                                <div class="progress-bar bg-success"

                                                     style="width: {{ $percentage }}%"></div>

                                            </div>

                                            <small class="text-muted">{{ $percentage }}%</small>

                                        </div>

                                    </td>


                                    {{-- تست‌ها --}}

                                    <td>

                                        @php

                                            $testsDone = $report->parts->sum('tests_done') ?? 0;

                                            $testsRequired = $report->parts->sum(fn($p) => $p->programPart->test_count ?? 0);

                                        @endphp

                                        <span class="fw-medium">{{ $testsDone }} / {{ $testsRequired }}</span>

                                    </td>


                                    {{-- ساعت گوشی --}}

                                    <td>

                                            <span
                                                class="badge bg-{{ $report->phone_hours > 3 ? 'danger' : 'success' }}">

                                                {{ $report->phone_hours }} ساعت

                                            </span>

                                    </td>


                                    {{-- امتیاز --}}

                                    <td>

                                        <div class="d-flex flex-column">

                                            <span class="badge bg-secondary">{{ $report->rating }} / 5</span>

                                            <small class="text-muted">{{ $report->rating_label }}</small>

                                        </div>

                                    </td>


                                    {{-- پارت جبرانی --}}

                                    <td>

                                        @php

                                            $compensatory = $report->compensatoryParts()->count();

                                        @endphp

                                        @if($compensatory > 0)

                                            <span class="badge bg-info">{{ $compensatory }} پارت</span>

                                        @else

                                            <span class="text-muted">-</span>

                                        @endif

                                    </td>


                                    {{-- نظر مشاور --}}

                                    <td>

                                        <button type="button"

                                                wire:click="openCommentModal({{ $report->id }})"

                                                class="btn btn-sm btn-outline-primary">

                                            <i class="material-symbols-outlined">chat</i>

                                            مشاهده / ثبت

                                        </button>

                                        @if($report->comment && $report->comment->advisor_comment)

                                            <small class="text-muted d-block mt-1">

                                                {{ \Illuminate\Support\Str::limit($report->comment->advisor_comment, 20) }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- وضعیت --}}

                                    <td>

                                        <select wire:change="changeStatus({{ $report->id }}, $event.target.value)"

                                                class="form-select form-select-sm"

                                                wire:confirm="آیا مطمئن هستید؟">

                                            <option
                                                value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>

                                                در انتظار

                                            </option>

                                            <option
                                                value="approved" {{ $report->status == 'approved' ? 'selected' : '' }}>

                                                تایید شده

                                            </option>

                                            <option
                                                value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>

                                                رد شده

                                            </option>

                                        </select>

                                    </td>


                                    {{-- عملیات --}}

                                    <td>

                                        @can('delete_reports_for_academic_support')

                                            <button type="button"

                                                    wire:confirm="آیا مطمئن هستید؟"

                                                    wire:click="delete({{ $report->id }})"

                                                    class="btn btn-sm btn-outline-danger">

                                                <i class="material-symbols-outlined">delete</i>

                                            </button>

                                        @endcan

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="11" class="text-center py-4">

                                        <div>

                                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"

                                                       trigger="loop"

                                                       colors="primary:#121331,secondary:#08a88a"

                                                       style="width:75px;height:75px">

                                            </lord-icon>

                                            <h5 class="mt-2 mb-0">هیچ گزارشی یافت نشد</h5>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                <div class="card-footer">

                    {{ $reports->links('layouts.admin.pagination') }}

                </div>


            </div>

        </div>

    </div>


    {{-- Modal: نظر مشاور --}}

    @if($commentModalOpen)

        <div class="modal fade show d-block" tabindex="-1"

             style="background: rgba(0,0,0,.6);"

             wire:click.self="closeCommentModal">

            <div class="modal-dialog modal-lg modal-dialog-centered"

                 wire:keydown.escape="closeCommentModal">

                <div class="modal-content">


                    <div class="modal-header">

                        <div>

                            <h5 class="modal-title">نظر مشاور برای {{ $commentStudentName }}</h5>

                            <p class="small text-muted mb-0">

                                نظر شما برای دانش‌آموز ارسال می‌شود.

                            </p>

                        </div>

                        <button type="button" class="btn-close"

                                wire:click="closeCommentModal"></button>

                    </div>


                    <div class="modal-body">

                        @if($advisorCommentReadonly)

                            {{-- نمایش نظر ثبت شده --}}

                            <div class="mb-3">

                                <div class="fw-semibold mb-2">نظر ثبت شده مشاور</div>

                                <p class="p-3 rounded bg-primary bg-opacity-10 border border-primary">

                                    {{ $advisorCommentInput }}

                                </p>

                            </div>



                            @if($commentStudentReply)

                                <div class="mb-3">

                                    <div class="fw-semibold text-success mb-2">پاسخ دانش‌آموز</div>

                                    <p class="p-3 rounded bg-success bg-opacity-10 border border-success">

                                        {{ $commentStudentReply }}

                                    </p>

                                </div>

                            @endif

                        @else

                            {{-- فرم ثبت نظر --}}

                            <div class="mb-3">

                                <label class="form-label">متن نظر مشاور</label>

                                <textarea wire:model.defer="advisorCommentInput"

                                          class="form-control" rows="4"

                                          placeholder="نظر خود را وارد کنید..."></textarea>

                                @error('advisorCommentInput')

                                <div class="form-text text-danger">{{ $message }}</div>

                                @enderror

                            </div>

                        @endif

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary"

                                wire:click="closeCommentModal">

                            بستن

                        </button>


                        @if(!$advisorCommentReadonly)

                            <button type="button" wire:click="saveAdvisorComment"

                                    wire:loading.attr="disabled"

                                    class="btn btn-primary">

                                <span wire:loading.remove wire:target="saveAdvisorComment">

                                    ثبت نظر

                                </span>

                                <span wire:loading wire:target="saveAdvisorComment">

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
