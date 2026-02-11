<div>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                                <h5 class="fw-bold mb-1">گزارش‌های روزانه</h5>
                                <p class="text-muted mb-0 small">
                                    {{ $reportDateDayName }} - {{ $reportDateJalali }}
                                    <span class="badge bg-info ms-2">بازه گزارش: ۰۰:۰۰ تا ۰۶:۰۰ صبح روز بعد</span>
                                </p>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button"
                                        wire:click="showAllReportsConfirmation"
                                        class="btn btn-outline-primary btn-sm">
                                    <svg class="me-1" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M2.5 8a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-3a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0 6a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                                    </svg>
                                    تمام گزارشات بطور کلی
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4 g-3">
            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-primary fw-bold fs-3">{{ $reports->total() }}</div>
                        <div class="text-muted small">در انتظار تایید</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-danger fw-bold fs-3">{{ count($studentsWithoutReports) }}</div>
                        <div class="text-muted small">بدون گزارش</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-success fw-bold fs-3">{{ count($studentsOnRestDay) }}</div>
                        <div class="text-muted small">روز استراحت</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="text-info fw-bold fs-3">{{ count($selectedReports) }}</div>
                        <div class="text-muted small">انتخاب شده</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts: Students without reports & Rest day -->
        <div class="row mb-4 g-3">
            @if(count($studentsWithoutReports) > 0)
                <div class="col-md-6">
                    <div class="alert alert-danger border-0 shadow-sm mb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" class="me-2">
                                    <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                                    <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
                                </svg>
                                <strong>دانش‌آموزان بدون گزارش</strong>
                                <span class="badge bg-danger ms-2">{{ count($studentsWithoutReports) }} نفر</span>
                            </div>
                            <button type="button"
                                    wire:click="openStudentInfoModal('without')"
                                    class="btn btn-sm btn-danger">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                </svg>
                                گرفتن اطلاعات
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if(count($studentsOnRestDay) > 0)
                <div class="col-md-6">
                    <div class="alert alert-success border-0 shadow-sm mb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" class="me-2">
                                    <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
                                </svg>
                                <strong>دانش‌آموزان با روز استراحت</strong>
                                <span class="badge bg-success ms-2">{{ count($studentsOnRestDay) }} نفر</span>
                            </div>
                            <button type="button"
                                    wire:click="openStudentInfoModal('rest')"
                                    class="btn btn-sm btn-success">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                </svg>
                                گرفتن اطلاعات
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Bulk Actions -->
        @if(count($selectedReports) > 0)
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card border-primary border-0 shadow-sm">
                        <div class="card-body py-2">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <span class="text-primary fw-bold small">
                                    {{ count($selectedReports) }} گزارش انتخاب شده
                                </span>
                                <div class="d-flex gap-2">
                                    <button type="button"
                                            wire:click="bulkAction('approved')"
                                            wire:confirm="آیا از تایید گروهی مطمئن هستید؟"
                                            class="btn btn-success btn-sm">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                                        </svg>
                                        تایید همه
                                    </button>
                                    <button type="button"
                                            wire:click="bulkAction('rejected')"
                                            wire:confirm="آیا از رد گروهی مطمئن هستید؟"
                                            class="btn btn-danger btn-sm">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                        </svg>
                                        رد همه
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Reports Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        @if($reports->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 40px;">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   wire:model.live="selectAll">
                                        </th>
                                        <th class="text-nowrap">نام دانش‌آموز</th>
                                        <th class="text-nowrap text-center">پارت</th>
                                        <th class="text-nowrap text-center">تست</th>
                                        <th class="text-nowrap text-center">گوشی</th>
                                        <th class="text-nowrap text-center">امتیاز</th>
                                        <th class="text-nowrap text-center">نوع</th>
                                        <th class="text-nowrap text-center">ساعت ثبت</th>
                                        <th class="text-nowrap text-center">نظر</th>
                                        <th class="text-nowrap text-center">وضعیت</th>
                                        <th class="text-nowrap text-center">عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reports as $report)
                                        <tr wire:key="report-row-{{ $report->id }}">
                                            <td class="text-center">
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       value="{{ $report->id }}"
                                                       wire:model.live="selectedReports">
                                            </td>
                                            <td>
                                                <span class="fw-medium">{{ $report->student->user->personalInformation->name ?? $report->student->user->name ?? '-' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-success fw-bold">{{ $report->read_parts_count }}</span>
                                                <span class="text-muted">/</span>
                                                <span>{{ $report->total_parts }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold">{{ $report->total_tests }}</span>
                                            </td>
                                            <td class="text-center">
                                                {{ $report->phone_hours }}h
                                            </td>
                                            <td class="text-center">
                                                @php $ratingVal = $report->rating; @endphp
                                                <span class="badge rounded-pill
                                                    {{ $ratingVal >= 3 ? 'bg-success' : '' }}
                                                    {{ $ratingVal == 2 ? 'bg-primary' : '' }}
                                                    {{ $ratingVal <= 1 ? 'bg-danger' : '' }}">
                                                    {{ $this->getRatingLabel($ratingVal) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($report->is_compensatory)
                                                    <span class="badge bg-warning text-dark">جبرانی</span>
                                                @else
                                                    <span class="badge bg-light text-dark">عادی</span>
                                                @endif
                                            </td>
                                            <td class="text-center small text-muted">
                                                {{ $report->created_at?->format('H:i') }}
                                            </td>
                                            <td class="text-center">
                                                <button type="button"
                                                        wire:click="openCommentModal({{ $report->id }})"
                                                        class="btn btn-sm {{ $report->advisor_comment ? 'btn-success' : 'btn-outline-secondary' }}">
                                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                        @if($report->advisor_comment)
                                                            <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                                            <path d="M5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                                        @else
                                                            <path d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z"/>
                                                            <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                                        @endif
                                                    </svg>
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <select wire:change="changeStatus({{ $report->id }}, $event.target.value)"
                                                        class="form-select form-select-sm text-center"
                                                        style="min-width: 110px;">
                                                    <option value="pending" {{ $report->status === 'pending' ? 'selected' : '' }}>
                                                        در انتظار
                                                    </option>
                                                    <option value="approved" {{ $report->status === 'approved' ? 'selected' : '' }}>
                                                        تایید
                                                    </option>
                                                    <option value="rejected" {{ $report->status === 'rejected' ? 'selected' : '' }}>
                                                        رد
                                                    </option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button"
                                                            wire:click="openDetailModal({{ $report->id }})"
                                                            class="btn btn-outline-primary"
                                                            title="جزئیات">
                                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                        </svg>
                                                    </button>
                                                    <button type="button"
                                                            wire:click="delete({{ $report->id }})"
                                                            wire:confirm="آیا از حذف مطمئن هستید؟"
                                                            class="btn btn-outline-danger"
                                                            title="حذف">
                                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer bg-transparent py-3">
                                {{ $reports->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16" class="text-muted">
                                    <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
                                </svg>
                                <p class="text-muted mt-3">گزارشی در انتظار تایید وجود ندارد.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Comment Modal - ✅ با Select تایید/رد -->
    @if($commentModalOpen)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);"
             wire:click.self="closeCommentModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">
                            نظر مشاور
                            @if($commentStudentName)
                                <small class="text-muted fw-normal"> - {{ $commentStudentName }}</small>
                            @endif
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeCommentModal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- ✅ Select وضعیت - همیشه نمایش داده می‌شود --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small">وضعیت گزارش:</label>
                            <select wire:model="commentStatusInput"
                                    class="form-select @error('commentStatusInput') is-invalid @enderror"
                                {{ $advisorCommentReadonly ? 'disabled' : '' }}>
                                <option value="pending">در انتظار</option>
                                <option value="approved">تایید</option>
                                <option value="rejected">رد</option>
                            </select>
                            @error('commentStatusInput')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($advisorCommentReadonly)
                            <div class="mb-3">
                                <label class="form-label fw-bold small">نظر شما</label>
                                <div class="bg-light rounded-3 p-3 small">
                                    {{ $advisorCommentInput }}
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="form-label fw-bold small">نظر خود را بنویسید (اختیاری)</label>
                                <textarea wire:model="advisorCommentInput"
                                          rows="4"
                                          class="form-control @error('advisorCommentInput') is-invalid @enderror"
                                          placeholder="نظر خود را وارد کنید..."></textarea>
                                @error('advisorCommentInput')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        @if($commentStudentReply)
                            <div>
                                <label class="form-label fw-bold small text-success">پاسخ دانش‌آموز</label>
                                <div class="bg-success bg-opacity-10 rounded-3 p-3 small">
                                    {{ $commentStudentReply }}
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeCommentModal">بستن</button>
                        @if(!$advisorCommentReadonly)
                            <button type="button"
                                    class="btn btn-primary"
                                    wire:click="saveAdvisorComment"
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="saveAdvisorComment">ثبت نظر و وضعیت</span>
                                <span wire:loading wire:target="saveAdvisorComment">
                                    <span class="spinner-border spinner-border-sm me-1"></span> در حال ثبت...
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Detail Modal - ✅ اصلاح شده -->
    @if($detailModalOpen && !empty($selectedReportData))
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);"
             wire:click.self="closeDetailModal">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" class="me-2">
                                <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                            </svg>
                            جزئیات گزارش روزانه
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeDetailModal"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Student Info Card -->
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="text-primary">
                                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                            </svg>
                                            <div>
                                                <small class="text-muted d-block">دانش‌آموز</small>
                                                <strong class="text-dark">{{ $selectedReportData['student_name'] }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="text-info">
                                                <path d="M8.5 5.5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9V5.5z"/>
                                                <path d="M6.5 0a.5.5 0 0 0 0 1H7v1.07a7.001 7.001 0 0 0-3.273 12.474l-.602.602a.5.5 0 0 0 .707.708l.746-.746A6.97 6.97 0 0 0 8 16a6.97 6.97 0 0 0 3.422-.892l.746.746a.5.5 0 0 0 .707-.708l-.601-.602A7.001 7.001 0 0 0 9 2.07V1h.5a.5.5 0 0 0 0-1h-3zm1.038 3.018a6.093 6.093 0 0 1 .924 0 6 6 0 1 1-.924 0zM0 3.5c0 .753.333 1.429.86 1.887A8.035 8.035 0 0 1 4.387 1.86 2.5 2.5 0 0 0 0 3.5zM13.5 1c-.753 0-1.429.333-1.887.86a8.035 8.035 0 0 1 3.527 3.527A2.5 2.5 0 0 0 13.5 1z"/>
                                            </svg>
                                            <div>
                                                <small class="text-muted d-block">پایه و رشته</small>
                                                <strong class="text-dark">پایه {{ $selectedReportData['student_grade'] ?? '-' }} - {{ $selectedReportData['student_field'] ?? '-' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="text-success">
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                            <div>
                                                <small class="text-muted d-block">تاریخ</small>
                                                <strong class="text-dark">{{ $selectedReportData['report_date'] }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="text-warning">
                                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                                            </svg>
                                            <div>
                                                <small class="text-muted d-block">زمان ثبت</small>
                                                <strong class="text-dark">{{ $selectedReportData['created_at'] ?? '-' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Report Summary Statistics -->
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-lg-2">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body text-center py-3">
                                        @php
                                            $studySessionCount = collect($reportPartsDetails)->where('has_study_session', true)->count();
                                        @endphp
                                        <div class="{{ $studySessionCount === ($selectedReportData['total_parts'] ?? 0) ? 'text-success' : 'text-warning' }} fw-bold fs-4">
                                            {{ $studySessionCount }}/{{ $selectedReportData['total_parts'] ?? 0 }}
                                        </div>
                                        <small class="text-muted">ثبت ساعت مطالعه</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body text-center py-3">
                                        <div class="text-success fw-bold fs-4">{{ $selectedReportData['read_parts'] ?? 0 }}/{{ $selectedReportData['total_parts'] ?? 0 }}</div>
                                        <small class="text-muted">پارت خوانده شده</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body text-center py-3">
                                        <div class="text-primary fw-bold fs-4">{{ $selectedReportData['done_tests'] ?? 0 }}/{{ $selectedReportData['total_tests'] ?? 0 }}</div>
                                        <small class="text-muted">تست زده شده</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body text-center">
                                        <div class="text-info fw-bold fs-4">{{ $selectedReportData['phone_hours'] }} ساعت</div>
                                        <small class="text-muted">استفاده از گوشی</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-3">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body text-center">
                                        @php $detailRating = $selectedReportData['rating'] ?? 0; @endphp
                                        <div class="mb-1">
                                        <span class="badge rounded-pill fs-6
                                            {{ $detailRating >= 3 ? 'bg-success' : '' }}
                                            {{ $detailRating == 2 ? 'bg-primary' : '' }}
                                            {{ $detailRating <= 1 ? 'bg-danger' : '' }}">
                                            {{ $selectedReportData['rating_label'] }}
                                        </span>
                                        </div>
                                        <small class="text-muted">امتیاز کلی</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body text-center py-3">
                                        @if($selectedReportData['is_compensatory'] ?? false)
                                            <span class="badge bg-warning text-dark fs-6">جبرانی</span>
                                        @else
                                            <span class="badge bg-success fs-6">عادی</span>
                                        @endif
                                        <div><small class="text-muted">نوع گزارش</small></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($selectedReportData['is_compensatory'] ?? false)
                            <div class="alert alert-warning border-0 shadow-sm mb-4">
                                <div class="d-flex align-items-center gap-2">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                                    </svg>
                                    <strong>این گزارش جبرانی است.</strong>
                                </div>
                            </div>
                        @endif

                        <!-- Parts List -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom">
                                <h6 class="mb-0 fw-bold">
                                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="me-2 text-primary">
                                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                                    </svg>
                                    پارت‌های مطالعاتی
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                        <tr>
                                            <th class="text-nowrap">ردیف</th>
                                            <th class="text-nowrap">نام درس</th>
                                            <th class="text-nowrap">موضوع/فصل</th>
                                            <th class="text-center text-nowrap">نوع پارت</th>
                                            <th class="text-center text-nowrap">مدت برنامه</th>
                                            <th class="text-center text-nowrap">ثبت ساعت مطالعه</th>
                                            <th class="text-center text-nowrap">وضعیت گزارش</th>
                                            <th class="text-center text-nowrap">تست</th>
                                            <th class="text-center text-nowrap">امتیاز</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($reportPartsDetails as $index => $part)
                                            <tr class="{{ $part['is_read'] ? 'table-success' : '' }}">
                                                <td class="fw-medium">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="fw-semibold text-dark">{{ $part['lesson_name'] }}</div>
                                                    @if($part['subject_name'])
                                                        <small class="text-muted">{{ $part['subject_name'] }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($part['chapter_name'])
                                                        <span class="badge bg-info bg-opacity-10 text-info">{{ $part['chapter_name'] }}</span>
                                                    @endif
                                                    @if($part['topic_name'])
                                                        <br><small class="text-muted">{{ $part['topic_name'] }}</small>
                                                    @endif
                                                    @if(!$part['chapter_name'] && !$part['topic_name'])
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @php $pType = $part['part_type'] ?? ''; @endphp
                                                    <span class="badge
                                                        {{ $pType === 'test' ? 'bg-primary bg-opacity-10 text-primary' : '' }}
                                                        {{ $pType === 'descriptive' ? 'bg-purple bg-opacity-10 text-purple' : '' }}
                                                        {{ $pType === 'video' ? 'bg-warning bg-opacity-10 text-warning' : '' }}
                                                        {{ !in_array($pType, ['test','descriptive','video']) ? 'bg-secondary bg-opacity-10 text-secondary' : '' }}">
                                                        {{ $part['part_type_label'] ?? '-' }}
                                                    </span>
                                                    @if($part['lesson_type_label'] ?? false)
                                                        <br><small class="text-muted">{{ $part['lesson_type_label'] }}</small>
                                                    @endif
                                                    @if($part['is_compensatory'] ?? false)
                                                        <br><span class="badge bg-warning text-dark mt-1" style="font-size: 10px;">جبرانی</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $part['duration_minutes'] }} دقیقه</span>
                                                </td>
                                                <td class="text-center">
                                                    @if($part['has_study_session'] ?? false)
                                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" class="text-success">
                                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                        </svg>
                                                        <div>
                                                            @php
                                                                $studySecs = $part['study_duration_seconds'] ?? 0;
                                                                $studyH = floor($studySecs / 3600);
                                                                $studyM = floor(($studySecs % 3600) / 60);
                                                            @endphp
                                                            <small class="text-success fw-medium d-block">
                                                                {{ $studyH }}:{{ str_pad($studyM, 2, '0', STR_PAD_LEFT) }} ثبت شده
                                                            </small>
                                                            @if($part['study_started_at'] && $part['study_ended_at'])
                                                                <small class="text-muted" style="font-size: 10px;">
                                                                    {{ $part['study_started_at'] }} - {{ $part['study_ended_at'] }}
                                                                </small>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" class="text-danger">
                                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                                        </svg>
                                                        <div><small class="text-danger fw-medium">ثبت نشده</small></div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($part['is_read'])
                                                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16" class="text-success">
                                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                        </svg>
                                                        <div><small class="text-success fw-medium">خوانده شده</small></div>
                                                    @else
                                                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16" class="text-danger">
                                                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                                        </svg>
                                                        <div><small class="text-danger fw-medium">خوانده نشده</small></div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($part['test_count'] > 0)
                                                        <span class="fw-bold {{ $part['tests_done'] > 0 ? 'text-success' : 'text-muted' }}">{{ $part['tests_done'] }}</span>
                                                        <span class="text-muted">/{{ $part['test_count'] }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($part['part_rating'])
                                                        <div class="d-flex justify-content-center gap-0">
                                                            @for($s = 1; $s <= 4; $s++)
                                                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"
                                                                     class="{{ $s <= $part['part_rating'] ? 'text-warning' : 'text-muted' }}">
                                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                                </svg>
                                                            @endfor
                                                        </div>
                                                        <small class="text-muted">{{ $part['part_rating'] }}/4</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-muted py-5">
                                                    <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16" class="mb-3 opacity-50">
                                                        <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
                                                    </svg>
                                                    <p class="mb-0">پارتی برای این روز تعریف نشده است</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        @if($selectedReportData['description'] ?? null)
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-bottom">
                                    <h6 class="mb-0 fw-bold">
                                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="me-2 text-info">
                                            <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z"/>
                                            <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                                        </svg>
                                        توضیحات دانش‌آموز
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0 text-muted">{{ $selectedReportData['description'] }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Advisor Comment & Student Reply -->
                        @if($selectedReportData['advisor_comment'] ?? null)
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-header bg-primary bg-opacity-10 border-bottom border-primary border-opacity-25">
                                    <h6 class="mb-0 fw-bold text-primary">
                                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="me-2">
                                            <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                        </svg>
                                        نظر مشاور
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $selectedReportData['advisor_comment'] }}</p>
                                </div>
                            </div>
                        @endif

                        @if($selectedReportData['student_reply'] ?? null)
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-header bg-success bg-opacity-10 border-bottom border-success border-opacity-25">
                                    <h6 class="mb-0 fw-bold text-success">
                                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16" class="me-2">
                                            <path d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z"/>
                                        </svg>
                                        پاسخ دانش‌آموز
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $selectedReportData['student_reply'] }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="text-center mt-4">
                        <span class="badge rounded-pill px-4 py-2 fs-6
                            {{ $selectedReportData['status'] === 'approved' ? 'bg-success' : '' }}
                            {{ $selectedReportData['status'] === 'rejected' ? 'bg-danger' : '' }}
                            {{ $selectedReportData['status'] === 'pending' ? 'bg-warning text-dark' : '' }}">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                <path d="M14.778.085A.5.5 0 0 1 15 .5V8a.5.5 0 0 1-.314.464L14.5 8l.186.464-.003.001-.006.003-.023.009a12.435 12.435 0 0 1-.397.15c-.264.095-.631.223-1.047.35-.816.252-1.879.523-2.71.523-.847 0-1.548-.28-2.158-.525l-.028-.01C7.68 8.71 7.14 8.5 6.5 8.5c-.7 0-1.638.23-2.437.477A19.626 19.626 0 0 0 3 9.342V15.5a.5.5 0 0 1-1 0V.5a.5.5 0 0 1 1 0v.282c.226-.079.496-.17.79-.26C4.606.272 5.67 0 6.5 0c.84 0 1.524.277 2.121.519l.043.018C9.286.788 9.828 1 10.5 1c.7 0 1.638-.23 2.437-.477a19.587 19.587 0 0 0 1.349-.476l.019-.007.004-.002h.001M14 1.221c-.22.078-.48.167-.766.255-.81.252-1.872.523-2.734.523-.886 0-1.592-.286-2.203-.534l-.008-.003C7.662 1.21 7.139 1 6.5 1c-.669 0-1.606.229-2.415.478A21.294 21.294 0 0 0 3 1.845v6.433c.22-.078.48-.167.766-.255C4.576 7.77 5.638 7.5 6.5 7.5c.847 0 1.548.28 2.158.525l.028.01C9.32 8.29 9.86 8.5 10.5 8.5c.668 0 1.606-.229 2.415-.478A21.317 21.317 0 0 0 14 7.655V1.222z"/>
                            </svg>
                            وضعیت: {{ match($selectedReportData['status'] ?? '') {
                                'pending' => 'در انتظار بررسی',
                                'approved' => 'تایید شده',
                                'rejected' => 'رد شده',
                                default => 'نامشخص'
                            } }}
                        </span>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" wire:click="closeDetailModal">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                            </svg>
                            بستن
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Student Info Modal -->
    @if($studentInfoModalOpen)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);"
             wire:click.self="closeStudentInfoModal">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">{{ $studentInfoModalTitle }}</h5>
                        <button type="button" class="btn-close" wire:click="closeStudentInfoModal"></button>
                    </div>
                    <div class="modal-body">
                        @if(count($studentInfoList) > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-hover align-middle mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th class="text-nowrap">ردیف</th>
                                        <th class="text-nowrap">نام و نام خانوادگی</th>
                                        <th class="text-nowrap text-center">پایه + رشته</th>
                                        <th class="text-nowrap text-center">شماره موبایل</th>
                                        <th class="text-nowrap text-center">شماره پدر</th>
                                        <th class="text-nowrap text-center">شماره مادر</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($studentInfoList as $index => $student)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="fw-medium">{{ $student['name'] }}</td>
                                            <td class="text-center">
                                                {{ $student['grade'] !== '-' ? 'پایه ' . $student['grade'] : '-' }}
                                                @if($student['field'] !== '-')
                                                    - {{ $student['field'] }}
                                                @endif
                                            </td>
                                            <td class="text-center" dir="ltr">{{ $student['mobile'] }}</td>
                                            <td class="text-center" dir="ltr">{{ $student['father_mobile'] }}</td>
                                            <td class="text-center" dir="ltr">{{ $student['mother_mobile'] }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <svg width="48" height="48" fill="currentColor" viewBox="0 0 16 16" class="mb-2">
                                    <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
                                </svg>
                                <p class="mb-0">موردی یافت نشد.</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeStudentInfoModal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- All Unconfirmed Reports Modal -->
    @if($allReportsModalOpen)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);"
             wire:click.self="closeAllReportsModal">
            <div class="modal-dialog {{ $allReportsConfirmed ? 'modal-xl' : 'modal-dialog-centered' }} modal-dialog-scrollable">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">
                            @if($allReportsConfirmed)
                                تمام گزارشات تایید نشده
                            @else
                                هشدار
                            @endif
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeAllReportsModal"></button>
                    </div>
                    <div class="modal-body">
                        @if(!$allReportsConfirmed)
                            <!-- Confirmation Step -->
                            <div class="text-center py-4">
                                <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16" class="text-warning mb-3">
                                    <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                                    <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
                                </svg>
                                <h5 class="mt-3 fw-bold">توجه!</h5>
                                <p class="text-muted">
                                    با تایید این عملیات، تمام گزارش‌های تایید نشده دانش‌آموزان شما بارگذاری خواهد شد.
                                    <br>
                                    این عملیات ممکن است کمی زمان‌بر باشد.
                                </p>
                            </div>
                        @else
                            <!-- Reports List -->
                            @if(count($allUnconfirmedReports) > 0)
                                <div class="mb-3 small text-muted">
                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                    </svg>
                                    {{ count($allUnconfirmedReports) }} گزارش تایید نشده یافت شد.
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-hover align-middle mb-0">
                                        <thead class="table-light">
                                        <tr>
                                            <th class="text-nowrap">ردیف</th>
                                            <th class="text-nowrap">دانش‌آموز</th>
                                            <th class="text-nowrap text-center">تاریخ</th>
                                            <th class="text-nowrap text-center">روز</th>
                                            <th class="text-nowrap text-center">پارت</th>
                                            <th class="text-nowrap text-center">تست</th>
                                            <th class="text-nowrap text-center">گوشی</th>
                                            <th class="text-nowrap text-center">امتیاز</th>
                                            <th class="text-nowrap text-center">نوع</th>
                                            <th class="text-nowrap text-center">زمان ثبت</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($allUnconfirmedReports as $index => $rpt)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="fw-medium">{{ $rpt['student_name'] }}</td>
                                                <td class="text-center">{{ $rpt['report_date'] }}</td>
                                                <td class="text-center">{{ $rpt['day_name'] }}</td>
                                                <td class="text-center">
                                                    <span class="text-success">{{ $rpt['read_parts'] }}</span>/<span>{{ $rpt['total_parts'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fw-bold">{{ $rpt['done_tests'] }}</span>/<span>{{ $rpt['total_tests'] }}</span>
                                                </td>
                                                <td class="text-center">{{ $rpt['phone_hours'] }}h</td>
                                                <td class="text-center">
                                                    <span class="badge rounded-pill
                                                        {{ $rpt['rating'] >= 3 ? 'bg-success' : '' }}
                                                        {{ $rpt['rating'] == 2 ? 'bg-primary' : '' }}
                                                        {{ $rpt['rating'] <= 1 ? 'bg-danger' : '' }}">
                                                        {{ $rpt['rating_label'] }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if($rpt['is_compensatory'])
                                                        <span class="badge bg-warning text-dark">جبرانی</span>
                                                    @else
                                                        <span class="badge bg-light text-dark">عادی</span>
                                                    @endif
                                                </td>
                                                <td class="text-center small text-muted">{{ $rpt['created_at'] }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4 text-muted">
                                    <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16" class="text-success mb-2">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                    <p>تمام گزارش‌ها تایید شده‌اند. هیچ گزارش تایید نشده‌ای وجود ندارد.</p>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeAllReportsModal">بستن</button>
                        @if(!$allReportsConfirmed)
                            <button type="button"
                                    class="btn btn-primary"
                                    wire:click="confirmLoadAllReports"
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="confirmLoadAllReports">بله، نمایش بده</span>
                                <span wire:loading wire:target="confirmLoadAllReports">
                                    <span class="spinner-border spinner-border-sm me-1"></span> در حال بارگذاری...
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
