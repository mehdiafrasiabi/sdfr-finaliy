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

                        <h5 class="modal-title">اختصاص آزمون به دانش‌آموزان</h5>

                        <button type="button" class="btn-close" wire:click="closeAssignModal"></button>

                    </div>

                    <div class="modal-body">

                        <!-- Date/Time Settings -->

                        <div class="row g-3 mb-4">

                            <div class="col-md-3">

                                <label class="form-label">تاریخ شروع</label>

                                <input type="date" wire:model="startDate" class="form-control">

                                @error('startDate')
                                <div class="text-danger small">{{ $message }}</div> @enderror

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">تاریخ پایان</label>

                                <input type="date" wire:model="endDate" class="form-control">

                                @error('endDate')
                                <div class="text-danger small">{{ $message }}</div> @enderror

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">ساعت شروع</label>

                                <input type="time" wire:model="startTime" class="form-control">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">ساعت پایان</label>

                                <input type="time" wire:model="endTime" class="form-control">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">مدت آزمون (دقیقه)</label>

                                <input type="number" min="1" max="1440" wire:model="durationMinutes"
                                       class="form-control">

                                @error('durationMinutes')
                                <div class="text-danger small">{{ $message }}</div> @enderror

                            </div>

                        </div>


                        <!-- Visibility Settings -->

                        <div class="row g-3 mb-4">

                            <div class="col-12">

                                <h6 class="text-muted border-bottom pb-2">تنظیمات نمایش نتایج</h6>

                            </div>

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


                            <!-- Student Search -->

                            <div class="mb-3">

                                <input type="text"

                                       wire:model.live.debounce.300ms="studentSearch"

                                       class="form-control"

                                       placeholder="جستجوی دانش‌آموز...">

                            </div>


                            @error('selectedStudents')
                            <div class="alert alert-danger">{{ $message }}</div> @enderror



                            <!-- Students List -->

                            <div class="border rounded" style="max-height: 300px; overflow-y: auto;">
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

                            <button type="button" class="btn btn-danger" wire:click="closeAssignModal">انصراف</button>

                            <button type="button" class="btn btn-primary" wire:click="assignExam"
                                    wire:loading.attr="disabled">

                                <span wire:loading.remove wire:target="assignExam">ثبت اختصاص</span>

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

                                <h5 class="modal-title">ویرایش زمان‌بندی</h5>

                                <button type="button" class="btn-close" wire:click="closeEditModal"></button>

                            </div>

                            <div class="modal-body">

                                <div class="row g-3">

                                    <div class="col-md-6">

                                        <label class="form-label">تاریخ شروع</label>

                                        <input type="date" wire:model="editStartDate" class="form-control">

                                    </div>

                                    <div class="col-md-6">

                                        <label class="form-label">تاریخ پایان</label>

                                        <input type="date" wire:model="editEndDate" class="form-control">

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

                                            class="form-control"

                                        >

                                        @error('editDurationMinutes')

                                        <div class="text-danger small">{{ $message }}</div>

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

                                <div class="modal-footer">

                                    <button type="button" class="btn btn-secondary" wire:click="closeEditModal">انصراف
                                    </button>

                                    <button type="button" class="btn btn-primary" wire:click="updateAssignment">ذخیره
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                    @endif



                    @push('script')

                        <script>

                            Livewire.on('success', (message) => {

                                Swal.fire({icon: 'success', title: 'موفق', text: message, confirmButtonText: 'باشه'});

                            });

                            Livewire.on('error', (message) => {

                                Swal.fire({icon: 'error', title: 'خطا', text: message, confirmButtonText: 'باشه'});

                            });

                        </script>

                    @endpush

                </div>
