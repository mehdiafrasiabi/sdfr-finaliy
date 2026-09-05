<div>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">اختصاص آزمون</h4>
                            <p class="text-muted mb-0">{{ $exam->title }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button wire:click="openAssignModal" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>
                                اختصاص جدید
                            </button>
                            <a href="{{ route('admin.typed-exams.index') }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-right me-1"></i>
                                بازگشت
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Assignments List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">لیست اختصاص‌ها</h5>
                    </div>
                    <div class="card-body p-0">
                        @if($assignments->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="ti ti-users" style="font-size: 3rem;"></i>
                                <p class="mt-2">هنوز اختصاصی ثبت نشده است</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th>دانش‌آموز</th>
                                        <th>تاریخ شروع</th>
                                        <th>تاریخ پایان</th>
                                        <th>ساعت</th>
                                        <th>مدت (دقیقه)</th>
                                        <th>وضعیت</th>
                                        <th>نمره</th>
                                        <th>عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($assignments as $assignment)
                                        <tr>
                                            <td>{{ $assignment->student?->user?->name ?? 'نامشخص' }}</td>
                                            <td>{{ $assignment->time ? verta($assignment->time->start_date)->format('Y/m/d') : '-' }}</td>
                                            <td>{{ $assignment->time ? verta($assignment->time->end_date)->format('Y/m/d') : '-' }}</td>
                                            <td>
                                                @if($assignment->time)
                                                    {{ $assignment->time->start_time }}
                                                    - {{ $assignment->time->end_time }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($assignment->time?->duration_minutes)
                                                    {{ $assignment->time->duration_minutes }} دقیقه
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                    <span
                                                        class="badge bg-{{ $assignment->status === 'completed' ? 'success' : ($assignment->status === 'started' ? 'warning' : ($assignment->status === 'expired' ? 'danger' : 'primary')) }}">
                                                        {{ $assignment->status_label }}
                                                    </span>
                                            </td>
                                            <td>
                                                @if($assignment->latestAttempt?->score !== null)
                                                    <span class="badge bg-primary">{{ $assignment->latestAttempt->score }}%</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button wire:click="openEditModal({{ $assignment->id }})"
                                                            class="btn btn-sm btn-outline-primary"
                                                            title="ویرایش زمان">
                                                        <i class="ti ti-clock-edit"></i>
                                                    </button>
                                                    @if($assignment->latestAttempt)
                                                        <a href="{{ route('admin.typed-exams.student-result', ['examId' => $exam->id, 'attemptId' => $assignment->latestAttempt->id]) }}"
                                                           class="btn btn-sm btn-outline-success"
                                                           title="مشاهده نتیجه">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                    @endif
                                                    <button wire:click="deleteAssignment({{ $assignment->id }})"
                                                            wire:confirm="آیا از حذف این اختصاص اطمینان دارید؟"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="حذف">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    @if($assignments->hasPages())
                        <div class="card-footer">
                            {{ $assignments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Assign Modal -->
    @if($showAssignModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ti ti-calendar-plus me-1"></i>
                            اختصاص آزمون به دانش‌آموزان
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeAssignModal"></button>
                    </div>
                    <div class="modal-body">

                        <!-- Date/Time Settings -->
                        <h6 class="text-muted border-bottom pb-2 mb-3">
                            <i class="ti ti-clock text-primary me-1"></i>
                            بازه زمانی و مدت آزمون
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <label class="form-label">تاریخ شروع</label>
                                <input type="text" wire:model.blur="startDate" data-jdp inputmode="none"
                                       autocomplete="off" dir="ltr" readonly
                                       class="form-control text-center @error('startDate') is-invalid @enderror"
                                       placeholder="1405/06/13">
                                @error('startDate')
                                <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">تاریخ پایان</label>
                                <input type="text" wire:model.blur="endDate" data-jdp inputmode="none"
                                       autocomplete="off" dir="ltr" readonly
                                       class="form-control text-center @error('endDate') is-invalid @enderror"
                                       placeholder="1405/06/20">
                                @error('endDate')
                                <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">ساعت شروع</label>
                                <input type="time" wire:model="startTime" class="form-control">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">ساعت پایان</label>
                                <input type="time" wire:model="endTime" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">مدت آزمون (دقیقه)</label>
                                <input type="number" min="1" max="1440" wire:model="durationMinutes"
                                       class="form-control @error('durationMinutes') is-invalid @enderror">
                                @error('durationMinutes')
                                <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Visibility Settings -->
                        <h6 class="text-muted border-bottom pb-2 mb-3">
                            <i class="ti ti-eye text-primary me-1"></i>
                            تنظیمات نمایش نتایج
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">زمان نمایش کارنامه</label>
                                <select wire:model="resultVisibility" class="form-select">
                                    @foreach($visibilityOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">زمان نمایش پاسخنامه</label>
                                <select wire:model="answerKeyVisibility" class="form-select">
                                    @foreach($visibilityOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Student Selection -->
                        <h6 class="text-muted border-bottom pb-2 mb-3">
                            <i class="ti ti-users text-primary me-1"></i>
                            انتخاب دانش‌آموزان
                        </h6>
                        @error('selectedStudents')
                        <div class="alert alert-danger py-2">{{ $message }}</div> @enderror

                        <div class="d-flex flex-column flex-sm-row gap-2 mb-2">
                            <input type="text"
                                   wire:model.live.debounce.300ms="studentSearch"
                                   class="form-control"
                                   placeholder="جستجوی دانش‌آموز (نام یا موبایل)...">
                            <div class="d-flex gap-2 flex-shrink-0">
                                <button type="button" class="btn btn-outline-primary btn-sm text-nowrap"
                                        wire:click="selectAllVisibleStudents">
                                    <i class="ti ti-checks"></i>
                                    انتخاب همه
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm text-nowrap"
                                        wire:click="clearSelectedStudents">
                                    لغو انتخاب‌ها
                                </button>
                            </div>
                        </div>

                        <div class="border rounded" style="max-height: 280px; overflow-y: auto;">
                            <div class="list-group list-group-flush">
                                @forelse($students as $student)
                                    @php
                                        $isAssigned = in_array($student->id, $assignedStudentIds ?? []);
                                        $isSelected = in_array($student->id, $selectedStudents);
                                    @endphp

                                    <label class="list-group-item d-flex align-items-center justify-content-between
                      {{ $isSelected ? 'bg-success-subtle' : '' }}
                      {{ $isAssigned ? 'opacity-75' : '' }}">
                                        <div class="d-flex align-items-center">
                                            <input type="checkbox"
                                                   class="form-check-input ms-2"
                                                   @if(!$isAssigned)
                                                       wire:click="toggleStudent({{ $student->id }})"
                                                @endif
                                                {{ $isSelected ? 'checked' : '' }}
                                                {{ $isAssigned ? 'disabled' : '' }}>

                                            <div class="me-2" style="margin-right: 10px">
                                                <div
                                                    class="fw-semibold text-white">{{ $student->user?->name ?? 'نامشخص' }}</div>
                                                <div
                                                    class="text-muted small">{{ $student->user?->mobile ?? '' }}</div>
                                            </div>
                                        </div>

                                        @if($isAssigned)
                                            <span class="badge bg-success text-white">اختصاص داده شده</span>
                                        @endif
                                    </label>
                                @empty
                                    <div class="text-center py-3 text-muted">
                                        دانش‌آموزی یافت نشد
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="mt-2 d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary">
                                {{ count($selectedStudents) }} دانش‌آموز انتخاب شده
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeAssignModal">انصراف</button>
                        <button type="button" class="btn btn-primary" wire:click="assignExam"
                                wire:loading.attr="disabled" wire:target="assignExam">
                            <span wire:loading.remove wire:target="assignExam">
                                <i class="ti ti-check"></i>
                                ثبت اختصاص
                            </span>
                            <span wire:loading wire:target="assignExam">در حال ثبت...</span>
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
                            <i class="ti ti-clock-edit me-1"></i>
                            ویرایش زمان‌بندی
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeEditModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">تاریخ شروع</label>
                                <input type="text" wire:model.blur="editStartDate" data-jdp inputmode="none"
                                       autocomplete="off" dir="ltr" readonly
                                       class="form-control text-center @error('editStartDate') is-invalid @enderror"
                                       placeholder="1405/06/13">
                                @error('editStartDate')
                                <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">تاریخ پایان</label>
                                <input type="text" wire:model.blur="editEndDate" data-jdp inputmode="none"
                                       autocomplete="off" dir="ltr" readonly
                                       class="form-control text-center @error('editEndDate') is-invalid @enderror"
                                       placeholder="1405/06/20">
                                @error('editEndDate')
                                <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ساعت شروع</label>
                                <input type="time" wire:model="editStartTime" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ساعت پایان</label>
                                <input type="time" wire:model="editEndTime" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">مدت آزمون (دقیقه)</label>
                                <input
                                    type="number"
                                    min="1"
                                    max="1440"
                                    wire:model="editDurationMinutes"
                                    name="editDurationMinutes"
                                    class="form-control @error('editDurationMinutes') is-invalid @enderror">
                                @error('editDurationMinutes')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mt-3">
                                <h6 class="text-muted border-bottom pb-2">تنظیمات نمایش نتایج</h6>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">زمان نمایش کارنامه</label>
                                <select wire:model="editResultVisibility" class="form-select">
                                    @foreach($visibilityOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">زمان نمایش پاسخنامه</label>
                                <select wire:model="editAnswerKeyVisibility" class="form-select">
                                    @foreach($visibilityOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeEditModal">انصراف
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="updateAssignment">
                            <i class="ti ti-check"></i>
                            ذخیره
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>

    @push('link')
        <style>
            input[data-jdp] { letter-spacing: 0.04em; cursor: pointer; }
        </style>
    @endpush

    @push('script')

        <script>

            (function bootTypedExamAssignmentJalaliDatepicker() {
                if (typeof jalaliDatepicker === 'undefined') {
                    setTimeout(bootTypedExamAssignmentJalaliDatepicker, 200);
                    return;
                }

                jalaliDatepicker.startWatch({
                    time: false,
                    autoHide: true,
                    changeMonth: true,
                    changeYear: true,
                    showTodayBtn: true,
                    todayBtnText: 'امروز',
                    zIndex: 2000,
                });
            })();

            Livewire.on('success', (message) => {

                Swal.fire({icon: 'success', title: 'موفق', text: message, confirmButtonText: 'باشه'});

            });

            Livewire.on('error', (message) => {

                Swal.fire({icon: 'error', title: 'خطا', text: message, confirmButtonText: 'باشه'});

            });

        </script>

    @endpush
