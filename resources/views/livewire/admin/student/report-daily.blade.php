<div class="container-fluid">


    <!-- Header -->

    <div class="mb-4">

        <h4 class="py-3 mb-0">

            <span class="text-muted fw-light">گزارش های روزانه /</span>

            <span class="text-primary">{{ $yesterdayDayName }} - {{ $yesterdayJalali }}</span>

        </h4>

        <p class="text-muted mb-0">گزارش های در انتظار تایید روز گذشته</p>

    </div>


    <!-- Stats Cards -->

    <div class="row mb-4">

        <div class="col-lg-4 col-md-6 mb-3">

            <div class="card shadow-sm h-100">

                <div class="card-body d-flex align-items-center gap-3">

                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">

                        <i class="material-symbols-outlined text-primary" style="font-size: 28px;">pending_actions</i>

                    </div>

                    <div>

                        <h6 class="text-muted mb-1">گزارش های در انتظار</h6>

                        <h3 class="mb-0 text-primary">{{ $reports->total() }}</h3>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-4 col-md-6 mb-3">

            <div class="card shadow-sm h-100">

                <div class="card-body d-flex align-items-center gap-3">

                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">

                        <i class="material-symbols-outlined text-warning" style="font-size: 28px;">person_off</i>

                    </div>

                    <div>

                        <h6 class="text-muted mb-1">دانش‌آموزان بدون گزارش</h6>

                        <h3 class="mb-0 text-warning">{{ count($studentsWithoutReports) }}</h3>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-4 col-md-6 mb-3">

            <div class="card shadow-sm h-100">

                <div class="card-body d-flex align-items-center gap-3">

                    <div class="rounded-circle bg-info bg-opacity-10 p-3">

                        <i class="material-symbols-outlined text-info" style="font-size: 28px;">check_circle</i>

                    </div>

                    <div>

                        <h6 class="text-muted mb-1">انتخاب شده</h6>

                        <h3 class="mb-0 text-info">{{ count($selectedReports) }}</h3>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Students Without Reports Alert -->

    @if(count($studentsWithoutReports) > 0)

        <div class="alert alert-warning d-flex align-items-start gap-3 mb-4" role="alert">

            <i class="material-symbols-outlined">warning</i>

            <div>

                <h6 class="alert-heading mb-1">دانش‌آموزان بدون گزارش</h6>

                <p class="mb-0 small">{{ implode('، ', $studentsWithoutReports) }}</p>

            </div>

        </div>

    @endif



    <!-- Bulk Actions -->

    @if(count($selectedReports) > 0)

        <div class="card mb-4 border-primary">

            <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">

                <div class="d-flex align-items-center gap-2">

                    <span class="badge bg-primary rounded-pill">{{ count($selectedReports) }}</span>

                    <span>گزارش انتخاب شده</span>

                </div>

                <div class="d-flex gap-2">

                    <button type="button"

                            wire:click="bulkAction('approved')"

                            wire:confirm="آیا از تایید گزارش‌های انتخاب شده اطمینان دارید؟"

                            class="btn btn-success d-inline-flex align-items-center gap-1">

                        <i class="material-symbols-outlined">check_circle</i>

                        تایید همه

                    </button>

                    <button type="button"

                            wire:click="bulkAction('rejected')"

                            wire:confirm="آیا از رد گزارش‌های انتخاب شده اطمینان دارید؟"

                            class="btn btn-danger d-inline-flex align-items-center gap-1">

                        <i class="material-symbols-outlined">cancel</i>

                        رد همه

                    </button>

                </div>

            </div>

        </div>

    @endif



    <!-- Reports Table -->

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">لیست گزارش‌های در انتظار</h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                    <tr>

                        <th scope="col" class="text-center" style="width: 50px;">

                            <input type="checkbox"

                                   wire:model.live="selectAll"

                                   class="form-check-input">

                        </th>

                        <th scope="col">#</th>

                        <th scope="col">دانش‌آموز</th>

                        <th scope="col">پارت خوانده</th>

                        <th scope="col">تست زده</th>

                        <th scope="col">گوشی (غیردرسی)</th>

                        <th scope="col">امتیاز</th>

                        <th scope="col">نوع</th>

                        <th scope="col">نظر مشاور</th>

                        <th scope="col">وضعیت</th>

                        <th scope="col">عملیات</th>

                    </tr>

                    </thead>


                    <tbody>

                    @forelse($reports as $report)

                        @php

                            $readParts = $report->reportParts->where('is_read', true)->count();

                            $totalParts = $report->reportParts->count();

                            $totalTests = $report->reportParts->sum(fn($p) => $p->programPart?->test_count ?? 0);

                            $doneTests = $report->reportParts->sum('tests_done');

                        @endphp

                        <tr wire:key="report-{{ $report->id }}">

                            <td class="text-center">

                                <input type="checkbox"

                                       wire:model.live="selectedReports"

                                       value="{{ $report->id }}"

                                       class="form-check-input">

                            </td>


                            <td>{{ $loop->iteration + $reports->firstItem() - 1 }}</td>


                            <td>

                                <div class="d-flex align-items-center gap-2">

                                    <div
                                        class="avatar avatar-sm bg-primary rounded-circle text-white d-flex align-items-center justify-content-center"
                                        style="width: 36px; height: 36px;">

                                        {{ mb_substr($report->student->user->name ?? '?', 0, 1) }}

                                    </div>

                                    <span class="fw-medium">{{ $report->student->user->name ?? '---' }}</span>

                                </div>

                            </td>


                            <td>

                                <span class="text-success fw-medium">{{ $readParts }}</span>

                                <span class="text-muted">/</span>

                                <span>{{ $totalParts }}</span>

                                @if($totalParts - $readParts > 0)

                                    <span class="badge bg-danger ms-1">{{ $totalParts - $readParts }} نخوانده</span>

                                @endif

                            </td>


                            <td>

                                <span class="text-success fw-medium">{{ $doneTests }}</span>

                                <span class="text-muted">/</span>

                                <span>{{ $totalTests }}</span>

                                @if($totalTests - $doneTests > 0)

                                    <span
                                        class="badge bg-warning text-dark ms-1">{{ $totalTests - $doneTests }} نزده</span>

                                @endif

                            </td>


                            <td>

                                <span class="fw-medium">{{ $report->phone_hours }} ساعت</span>

                            </td>


                            <td>

                                @php

                                    $ratingColors = [

                                        5 => 'success',

                                        4 => 'success',

                                        3 => 'info',

                                        2 => 'warning',

                                        1 => 'danger',

                                    ];

                                @endphp

                                <span class="badge bg-{{ $ratingColors[$report->rating] ?? 'secondary' }}">

                                        {{ \App\Models\DailyReport::RATINGS[$report->rating] ?? 'نامشخص' }}

                                    </span>

                            </td>


                            <td>

                                @if($report->is_compensatory)

                                    <span class="badge bg-warning text-dark">جبرانی</span>

                                @else

                                    <span class="badge bg-light text-dark">عادی</span>

                                @endif

                            </td>


                            <td>

                                <button type="button"

                                        wire:click="openCommentModal({{ $report->id }})"

                                        class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">

                                    <i class="material-symbols-outlined" style="font-size: 18px;">chat</i>

                                    @if($report->advisor_comment)

                                        مشاهده

                                    @else

                                        ثبت نظر

                                    @endif

                                </button>

                                @if($report->student_reply)

                                    <span class="badge bg-success ms-1">پاسخ دارد</span>

                                @endif

                            </td>


                            <td>

                                <select wire:change="changeStatus({{ $report->id }}, $event.target.value)"

                                        wire:confirm="آیا از تغییر وضعیت اطمینان دارید؟"

                                        class="form-select form-select-sm" style="min-width: 130px;">

                                    <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>در
                                        انتظار
                                    </option>

                                    <option value="approved" {{ $report->status == 'approved' ? 'selected' : '' }}>
                                        تایید
                                    </option>

                                    <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>رد
                                    </option>

                                </select>

                            </td>


                            <td>

                                <div class="d-flex gap-1">

                                    <button type="button"

                                            wire:click="openDetailModal({{ $report->id }})"

                                            class="btn btn-sm btn-outline-info d-inline-flex align-items-center"

                                            title="جزئیات">

                                        <i class="material-symbols-outlined" style="font-size: 18px;">visibility</i>

                                    </button>

                                    <button type="button"

                                            wire:click="delete({{ $report->id }})"

                                            wire:confirm="آیا از حذف گزارش اطمینان دارید؟"

                                            class="btn btn-sm btn-outline-danger d-inline-flex align-items-center"

                                            title="حذف">

                                        <i class="material-symbols-outlined" style="font-size: 18px;">delete</i>

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="11" class="text-center py-5">

                                <div>

                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"

                                               trigger="loop"

                                               colors="primary:#121331,secondary:#08a88a"

                                               style="width:75px;height:75px"></lord-icon>

                                    <h5 class="mt-3 mb-1">گزارشی در انتظار تایید نیست</h5>

                                    <p class="text-muted mb-0">تمام گزارش‌های روز گذشته بررسی شده‌اند.</p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($reports->hasPages())

            <div class="card-footer d-flex justify-content-center">

                {{ $reports->links('layouts.admin.pagination') }}

            </div>

        @endif

    </div>


    <!-- Comment Modal -->

    @if($commentModalOpen)

        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.6);"

             wire:click.self="closeCommentModal">

            <div class="modal-dialog modal-lg modal-dialog-centered" wire:keydown.escape="closeCommentModal">

                <div class="modal-content">

                    <div class="modal-header">

                        <div>

                            <h5 class="modal-title">نظر مشاور برای {{ $commentStudentName ?: 'دانش‌آموز' }}</h5>

                            <p class="small text-muted mb-0">برای هر گزارش تنها یک نظر از سوی مشاور و یک پاسخ از سوی
                                دانش‌آموز ثبت می‌شود.</p>

                        </div>

                        <button type="button" class="btn-close" wire:click="closeCommentModal"></button>

                    </div>


                    <div class="modal-body">

                        @if($advisorCommentReadonly)

                            <div class="mb-3">

                                <div class="fw-semibold mb-2">نظر ثبت شده مشاور</div>

                                <p class="p-3 rounded bg-primary text-white d-inline-block mb-0">{{ $advisorCommentInput }}</p>

                            </div>



                            @if($commentStudentReply)

                                <div class="mb-3">

                                    <div class="fw-semibold text-success mb-2">پاسخ دانش‌آموز</div>

                                    <p class="p-3 rounded text-white d-inline-block mb-0"
                                       style="background-color:#0b9c0b;">{{ $commentStudentReply }}</p>

                                </div>

                            @endif

                        @else

                            <div class="mb-3">

                                <label class="form-label">متن نظر مشاور</label>

                                <textarea wire:model.defer="advisorCommentInput"

                                          class="form-control"

                                          rows="4"

                                          placeholder="نظر خود را بنویسید..."></textarea>

                                @error('advisorCommentInput')

                                <div class="form-text text-danger mt-1">{{ $message }}</div>

                                @enderror

                            </div>

                        @endif

                    </div>


                    <div class="modal-footer d-flex justify-content-between">

                        <button type="button" class="btn btn-secondary" wire:click="closeCommentModal">بستن</button>


                        @if(!$advisorCommentReadonly)

                            <button type="button"

                                    wire:click="saveAdvisorComment"

                                    wire:loading.attr="disabled"

                                    class="btn btn-success d-flex align-items-center gap-2">

                                <span wire:loading.remove wire:target="saveAdvisorComment">ثبت نظر</span>

                                <span wire:loading wire:target="saveAdvisorComment"
                                      class="spinner-border spinner-border-sm"></span>

                            </button>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    @endif



    <!-- Detail Modal -->

    @if($detailModalOpen && !empty($selectedReportData))

        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.6);"

             wire:click.self="closeDetailModal">

            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"
                 wire:keydown.escape="closeDetailModal">

                <div class="modal-content">

                    <div class="modal-header bg-light">

                        <div>

                            <h5 class="modal-title d-flex align-items-center gap-2">

                                <i class="material-symbols-outlined text-primary">analytics</i>

                                جزئیات گزارش {{ $selectedReportData['student_name'] ?? '' }}

                            </h5>

                            <p class="small text-muted mb-0">

                                {{ $selectedReportData['day_name'] ?? '' }}
                                - {{ $selectedReportData['report_date'] ?? '' }}

                                @if($selectedReportData['is_compensatory'] ?? false)

                                    <span class="badge bg-warning text-dark ms-1">جبرانی</span>

                                @endif

                            </p>

                        </div>

                        <button type="button" class="btn-close" wire:click="closeDetailModal"></button>

                    </div>


                    <div class="modal-body">

                        <!-- Summary Stats -->

                        <div class="row g-3 mb-4">

                            <div class="col-md-3 col-6">

                                <div class="card bg-success bg-opacity-10 border-0 h-100">

                                    <div class="card-body text-center py-3">

                                        <h3 class="text-success mb-1">{{ $selectedReportData['read_parts'] ?? 0 }}</h3>

                                        <small class="text-muted">پارت خوانده</small>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-3 col-6">

                                <div class="card bg-danger bg-opacity-10 border-0 h-100">

                                    <div class="card-body text-center py-3">

                                        <h3 class="text-danger mb-1">{{ $selectedReportData['unread_parts'] ?? 0 }}</h3>

                                        <small class="text-muted">پارت نخوانده</small>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-3 col-6">

                                <div class="card bg-info bg-opacity-10 border-0 h-100">

                                    <div class="card-body text-center py-3">

                                        <h3 class="text-info mb-1">{{ $selectedReportData['done_tests'] ?? 0 }}
                                            /{{ $selectedReportData['total_tests'] ?? 0 }}</h3>

                                        <small class="text-muted">تست زده شده</small>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-3 col-6">

                                <div class="card bg-warning bg-opacity-10 border-0 h-100">

                                    <div class="card-body text-center py-3">

                                        <h3 class="text-warning mb-1">{{ $selectedReportData['phone_hours'] ?? 0 }}</h3>

                                        <small class="text-muted">ساعت گوشی</small>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- Rating & Description -->

                        <div class="row g-3 mb-4">

                            <div class="col-md-6">

                                <div class="card border h-100">

                                    <div class="card-body">

                                        <h6 class="card-title text-muted mb-2">امتیاز روز</h6>

                                        @php

                                            $rating = $selectedReportData['rating'] ?? 3;

                                            $ratingColors = [5 => 'success', 4 => 'success', 3 => 'info', 2 => 'warning', 1 => 'danger'];

                                        @endphp

                                        <span class="badge bg-{{ $ratingColors[$rating] ?? 'secondary' }} fs-6">

                                            {{ $selectedReportData['rating_label'] ?? 'نامشخص' }}

                                        </span>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="card border h-100">

                                    <div class="card-body">

                                        <h6 class="card-title text-muted mb-2">توضیحات</h6>

                                        <p class="mb-0">{{ $selectedReportData['description'] ?: 'بدون توضیحات' }}</p>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- Parts Details -->

                        <h6 class="mb-3 d-flex align-items-center gap-2">

                            <i class="material-symbols-outlined text-primary">list_alt</i>

                            جزئیات پارت‌ها

                        </h6>

                        <div class="row g-2">

                            @foreach($reportPartsDetails as $part)

                                <div class="col-md-6">

                                    <div
                                        class="card border {{ $part['is_read'] ? 'border-success' : 'border-danger' }} h-100">

                                        <div class="card-body py-3">

                                            <div class="d-flex align-items-start justify-content-between gap-2">

                                                <div class="flex-grow-1">

                                                    <h6 class="mb-1 fw-semibold">{{ $part['lesson_name'] }}</h6>

                                                    @if($part['subject_name'] || $part['topic_name'])

                                                        <small class="text-muted">

                                                            {{ $part['subject_name'] }}

                                                            @if($part['topic_name'])

                                                                - {{ $part['topic_name'] }}

                                                            @endif

                                                        </small>

                                                    @endif

                                                    <div class="d-flex flex-wrap gap-2 mt-2">

                                                        <span class="badge bg-light text-dark">{{ $part['duration_minutes'] }} دقیقه</span>

                                                        @if($part['test_count'] > 0)

                                                            <span
                                                                class="badge {{ $part['tests_done'] >= $part['test_count'] ? 'bg-success' : 'bg-warning text-dark' }}">

                                                                تست: {{ $part['tests_done'] }}/{{ $part['test_count'] }}

                                                            </span>

                                                        @endif

                                                        @if($part['is_compensatory'])

                                                            <span class="badge bg-info">جبرانی</span>

                                                        @endif

                                                    </div>

                                                </div>

                                                <div>

                                                    @if($part['is_read'])

                                                        <span class="badge bg-success rounded-pill p-2">

                                                            <i class="material-symbols-outlined"
                                                               style="font-size: 20px;">check</i>

                                                        </span>

                                                    @else

                                                        <span class="badge bg-danger rounded-pill p-2">

                                                            <i class="material-symbols-outlined"
                                                               style="font-size: 20px;">close</i>

                                                        </span>

                                                    @endif

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" wire:click="closeDetailModal">بستن</button>

                    </div>

                </div>

            </div>

        </div>

    @endif


</div>
