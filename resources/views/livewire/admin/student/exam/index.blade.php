<div>

    @push('link')
        <style>
            .wrap-text {
                word-wrap: break-word;
                white-space: normal;
                overflow-wrap: break-word;
                max-width: 500px; /* یا هر عرضی که می‌خوای */
            }
        </style>
    @endpush

    {{-- پیام‌های موفقیت / خطا --}}
    @if (session()->has('success'))
        <div class="alert alert-success d-flex justify-content-between align-items-center" role="alert" id="dismissingAlert">
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close close-btn" aria-label="Close"></button>
        </div>
        <br>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger d-flex justify-content-between align-items-center" role="alert" id="dismissingAlert">
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close close-btn" aria-label="Close"></button>
        </div>
        <br>
    @endif

    {{-- کارت لیست آزمون‌ها --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">آزمون ها</h5>

            <div class="d-flex flex-wrap gap-2 align-items-center">

                {{-- سرچ --}}
                <div class="position-relative">
                    <span class="position-absolute top-50 translate-middle-y start-0 ms-2 text-muted">
                        <i class="material-symbols-outlined" style="font-size: 20px">search</i>
                    </span>
                    <input type="text"
                           placeholder="جستجو....."
                           wire:model.live.debounce.350ms="search"
                           class="form-control form-control-sm ps-5"
                           style="min-width: 220px;">
                </div>

                {{-- فیلتر سطح --}}
                <div>
                    <select wire:model.live="levelFilter"
                            class="form-select form-select-sm">
                        <option value="all">همه سطوح</option>
                        @foreach($levelOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">عنوان</th>
                        <th scope="col">سطح</th>
                        <th scope="col">وضعیت</th>
                        <th scope="col">عملیات</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($exams as $exam)
                        <tr>
                            {{-- ردیف --}}
                            <td>
                                {{ $loop->iteration + $exams->firstItem() - 1 }}
                            </td>

                            {{-- عنوان --}}
                            <td>
                                <span class="fw-medium">{{ $exam->title }}</span>
                            </td>

                            {{-- سطح --}}
                            <td>
                                @if($exam->level == 'easy')
                                    <span class="badge rounded-pill text-white"
                                          style="background-color:#0a3622">
                                        آسان
                                    </span>
                                @elseif($exam->level == 'medium')
                                    <span class="badge rounded-pill text-white"
                                          style="background-color:orange">
                                        متوسط
                                    </span>
                                @elseif($exam->level == 'hard')
                                    <span class="badge rounded-pill text-white"
                                          style="background-color:red">
                                        سخت
                                    </span>
                                @elseif($exam->level == 'comprehensive')
                                    <span class="badge rounded-pill text-white"
                                          style="background-color:#014ab1">
                                        جامع
                                    </span>
                                @endif
                            </td>

                            {{-- وضعیت فعال/غیرفعال --}}
                            <td>
                                @if ($exam->is_active)
                                    <span class="badge rounded-pill bg-success-subtle text-success fw-semibold">
                                        فعال
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-danger-subtle text-danger fw-semibold">
                                        غیرفعال
                                    </span>
                                @endif
                            </td>

                            {{-- عملیات --}}
                            <td>
                                <div class="d-flex flex-wrap gap-2">

                                    @if (!$exam->is_active)
                                        <button
                                            type="button"
                                            wire:confirm="آیا از فعال‌سازی این آزمون مطمئن هستید؟"
                                            wire:click="activateExam({{ $exam->id }})"
                                            class="btn btn-success btn-sm">
                                            فعال‌سازی
                                        </button>
                                    @endif

                                    <button
                                        type="button"
                                        wire:click="openAssignStudentsModal({{ $exam->id }})"
                                        class="btn btn-secondary btn-sm">
                                        اختصاص دانش‌آموز
                                    </button>

                                    <a href="{{ route('admin.student.exam.studentResult',$exam->id) }}"
                                       class="btn btn-info btn-sm text-white">
                                        نتایج آزمون
                                    </a>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div>
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                               colors="primary:#121331,secondary:#08a88a"
                                               style="width:75px;height:75px"></lord-icon>
                                    <h5 class="mt-2 mb-0">متاسفیم! هیچ نتیجه ای یافت نشد</h5>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-center justify-content-md-between align-items-center">
            {{ $exams->links('layouts.admin.pagination') }}
        </div>
    </div>

    {{-- مودال اختصاص دانش‌آموز به آزمون --}}
    @if ($showStudentModal)
        <div class="modal fade show d-block"
             tabindex="-1"
             style="background: rgba(0,0,0,.6);"
             wire:click.self="closeAssignStudentsModal">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    {{-- هدر مودال --}}
                    <div class="modal-header">
                        <h5 class="modal-title mb-0">
                            اختصاص دانش‌آموز به آزمون
                            <span class="fw-bold">"{{ $selectedExam->title ?? '' }}"</span>
                        </h5>
                        <button type="button"
                                class="btn-close"
                                aria-label="Close"
                                wire:click="closeAssignStudentsModal"></button>
                    </div>

                    {{-- فرم داخل مودال --}}
                    <form wire:submit.prevent="assignStudents">
                        <div class="modal-body">

                            {{-- سرچ دانش‌آموز --}}
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">
                                    جستجوی دانش‌آموز (نام، کدملی یا شماره دانش‌آموز)
                                </label>
                                <input type="text"
                                       wire:model.live.debounce.350ms="studentSearch"
                                       class="form-control form-control-sm"
                                       placeholder="مثال: علی رضایی یا 1234567890">
                            </div>

                            {{-- جدول دانش‌آموزان --}}
                            <div class="table-responsive bg-light rounded p-2 mb-3" style="max-height: 300px; overflow-y: auto;">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="text-center">انتخاب</th>
                                        <th scope="col">دانش‌آموز</th>
                                        <th scope="col">شماره دانش‌آموز</th>
                                        <th scope="col" class="text-center">عملیات</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @forelse ($students as $student)
                                        <tr wire:key="student-{{ $student->id }}">
                                            {{-- Checkbox --}}
                                            <td class="text-center">
                                                <input type="checkbox"
                                                       wire:model="assignedStudents"
                                                       value="{{ $student->id }}"
                                                       class="form-check-input">
                                            </td>

                                            {{-- اطلاعات دانش‌آموز --}}
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <img src="/admin/assets/img/icons/brands/html-label.png"
                                                             class="rounded-circle"
                                                             width="40" height="40"
                                                             alt="avatar">
                                                    </div>
                                                    <div>
                                                        <span class="fw-medium d-block">
                                                            {{ optional($student->user?->personalInformation)->name ?? 'نامشخص' }}
                                                        </span>
                                                        <small class="text-muted d-block">
                                                            شماره موبایل:
                                                            {{ $student->user?->mobile ?? '---' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- شماره دانش‌آموز --}}
                                            <td>
                                                <span class="text-body">
                                                    {{ $student->id }}
                                                </span>
                                            </td>

                                            {{-- حذف اختصاص --}}
                                            <td class="text-center">
                                                @if (in_array($student->id, $assignedStudents))
                                                    <button type="button"
                                                            wire:click="removeAssignment({{ $student->id }})"
                                                            wire:confirm="آیا از حذف این دانش‌آموز مطمئن هستید؟"
                                                            class="btn btn-link text-danger p-0">
                                                        <i class="ri-delete-bin-line fs-5"></i>
                                                    </button>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">
                                                دانش‌آموزی مطابق جستجو یافت نشد.
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- صفحه‌بندی لیست دانش‌آموزان --}}
                            <div class="mt-3">
                                {{ $students->links('layouts.admin.pagination-modal', ['pageName' => 'studentsPage']) }}
                            </div>

                        </div>

                        {{-- دکمه‌های فوتر مودال --}}
                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button"
                                    wire:click="closeAssignStudentsModal"
                                    class="btn btn-outline-danger">
                                لغو
                            </button>

                            <button type="button"
                                    wire:click="assignStudents"
                                    wire:confirm="بعد از ثبت، آزمون برای دانش‌آموزان انتخاب شده فعال می‌شود. ادامه می‌دهید؟"
                                    class="btn btn-primary">
                                ثبت اختصاص
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endif

</div>
