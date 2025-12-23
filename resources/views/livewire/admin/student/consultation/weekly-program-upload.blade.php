<div>
    <div class="container-xxl flex-grow-1 container-p-y">
        @push('link')
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>

            <style>

                [x-cloak] {
                    display: none !important;
                }

                /* Select2 RTL Support */
                .select2-container {
                    width: 100% !important;
                }

                .select2-container--default .select2-selection--single {
                    height: 38px;
                    border: 1px solid #d9dee3;
                    border-radius: 0.375rem;
                }

                .select2-container--default .select2-selection--single .select2-selection__rendered {
                    line-height: 36px;
                    padding-right: 12px;
                    padding-left: 30px;
                }

                .select2-container--default .select2-selection--single .select2-selection__arrow {
                    height: 36px;
                    left: 1px;
                    right: auto;
                }

                /* Gradients */
                .bg-gradient-primary {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                }

                .bg-gradient-info {
                    background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
                }

                /* Modal Animation */
                .modal.show .modal-dialog {
                    animation: modalSlideDown 0.3s ease-out;
                }

                @keyframes modalSlideDown {
                    from {
                        transform: translateY(-50px);
                        opacity: 0;
                    }
                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }
            </style>
        @endpush
        {{-- هدر صفحه --}}
        <div class="card mb-4 border-0 text-white shadow-lg"
             style="background: linear-gradient(90deg, #1e88e5, #1565c0);">
            <div class="card-body">
                <div class="row gy-3 align-items-center">
                    <div class="col-md-6 d-flex align-items-center gap-3">
                        <div class="bg-white bg-opacity-25 rounded-3 p-3">
                            <span class="fs-3 fw-bold text-primary">SDFR</span>
                        </div>
                        <div>
                            <h1 class="h4 mb-1 fw-bold">برنامه درسی هفتگی</h1>
                            <small class="text-white">به سبک SDFR</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div
                            class="d-flex flex-wrap align-items-center justify-content-md-end gap-4 bg-white bg-opacity-10 rounded-3 px-4 py-3">
                            <div class="text-center">
                                <div class="text-light small mb-1">نام و نام خانوادگی</div>
                                <div class="fw-bold fs-6 text-black">{{ $student->user->name ?? '---' }}</div>
                            </div>

                            <div class="vr d-none d-md-block text-white opacity-50"></div>

                            <div class="text-center">
                                <div class="text-light small mb-1">مشاور</div>

                                <div class="fw-bold text-black">

                                    {{ $advisorName }}
                                </div>
                            </div>
                            <div class="vr d-none d-md-block text-white opacity-50"></div>
                            <div class="text-center">
                                <div class="text-light small mb-1">پشتیبان</div>
                                <div class="fw-bold text-black">
                                    {{ $supporterName }}
                                </div>
                            </div>

                            <div class="vr d-none d-md-block text-white opacity-50"></div>

                            <div class="text-center">
                                <div class="text-light small mb-1">تاریخ ارائه برنامه</div>
                                <div class="fw-bold text-black">
                                    {{ $start_date ? jdate($start_date)->format('Y/m/d') : '---' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- دسترسی سریع --}}
        <div class="card mb-4">
            <h5 class="card-header">دسترسی سریع</h5>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-md-2">
                        <a href="{{ route('admin.student.reportStudent.detail', $student->id) }}"
                           class="d-block border rounded-3 p-3 text-center text-reset text-decoration-none h-100">
                            <i class="material-symbols-outlined d-block mb-1 text-primary">assessment</i>
                            <span class="small d-block fw-medium text-muted">کارنامه وضعیت</span>
                        </a>
                    </div>

                    <div class="col-6 col-md-2">
                        <a href="{{ route('admin.student.studySession.detail', $student->id) }}"
                           class="d-block border rounded-3 p-3 text-center text-reset text-decoration-none h-100">
                            <i class="material-symbols-outlined d-block mb-1 text-success">schedule</i>
                            <span class="small d-block fw-medium text-muted">ساعت مطالعه</span>
                        </a>
                    </div>

                    <div class="col-6 col-md-2">
                        <a href="{{ route('admin.student.reportDailyActivities.detail', $student->id) }}"
                           class="d-block border rounded-3 p-3 text-center text-reset text-decoration-none h-100">
                            <i class="material-symbols-outlined d-block mb-1 text-info">summarize</i>
                            <span class="small d-block fw-medium text-muted">گزارش</span>
                        </a>
                    </div>

                    <div class="col-6 col-md-2">
                        <a href="#"
                           class="d-block border rounded-3 p-3 text-center text-reset text-decoration-none h-100">
                            <i class="material-symbols-outlined d-block mb-1 text-warning">quiz</i>
                            <span class="small d-block fw-medium text-muted">آزمون‌ها</span>
                        </a>
                    </div>

                    <div class="col-12 col-md-4">
                        <a href="#"
                           class="d-block border rounded-3 p-3 text-center text-reset text-decoration-none h-100">
                            <i class="material-symbols-outlined d-block mb-1 text-teal">category</i>
                            <span class="small d-block fw-medium text-muted">طبقه‌بندی</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- پیش‌جلسه‌های ثبت شده --}}
        @if($preSessions->count() > 0)
            @foreach($preSessions as $preSession)

                <div class="card mb-4">

                    <div class="card-header d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="mb-1">پیش‌جلسه: {{ $preSession->title }}</h5>

                            <small class="text-muted">اطلاعات ثبت شده توسط دانش‌آموز</small>

                        </div>

                        <span class="badge rounded-pill

                            {{ $preSession->status === 'completed'

                                ? 'bg-label-success'

                                : 'bg-label-warning' }}">

                            {{ $preSession->status_label }}

                        </span>

                    </div>


                    <div class="card-body">

                        <div class="row g-4">

                            {{-- امتحانات --}}

                            <div class="col-lg-6">

                                <div class="border rounded-3 p-3 h-100">

                                    <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">

                                        <i class="material-symbols-outlined">quiz</i>

                                        امتحانات

                                        <span
                                            class="badge bg-label-primary rounded-pill">{{ $preSession->exams->count() }}</span>

                                    </h6>

                                    @if($preSession->exams->count() > 0)

                                        <div class="table-responsive">

                                            <table class="table table-sm mb-0">

                                                <thead>

                                                <tr>

                                                    <th>درس</th>

                                                    <th>تعداد پارت</th>

                                                    <th>تاریخ آزمون</th>

                                                </tr>

                                                </thead>

                                                <tbody>

                                                @foreach($preSession->exams as $exam)

                                                    <tr>

                                                        <td class="fw-medium">{{ $exam->subject }}</td>

                                                        <td>{{ $exam->part_count }} پارت</td>

                                                        <td>{{ jalali($exam->exam_date)->format('%d %B %Y') }}</td>

                                                    </tr>

                                                @endforeach

                                                </tbody>

                                            </table>

                                        </div>

                                    @else

                                        <p class="text-muted small mb-0">هیچ امتحانی ثبت نشده است</p>

                                    @endif

                                </div>

                            </div>


                            {{-- پرسش و پاسخ کلاسی --}}

                            <div class="col-lg-6">

                                <div class="border rounded-3 p-3 h-100">

                                    <h6 class="fw-bold text-info mb-3 d-flex align-items-center gap-2">

                                        <i class="material-symbols-outlined">forum</i>

                                        پرسش و پاسخ کلاسی

                                        <span
                                            class="badge bg-label-info rounded-pill">{{ $preSession->qas->count() }}</span>

                                    </h6>

                                    @if($preSession->qas->count() > 0)

                                        <div class="table-responsive">

                                            <table class="table table-sm mb-0">

                                                <thead>

                                                <tr>

                                                    <th>درس</th>

                                                    <th>تعداد پارت</th>

                                                    <th>زمان هر پارت</th>

                                                    <th>تاریخ</th>

                                                </tr>

                                                </thead>

                                                <tbody>

                                                @foreach($preSession->qas as $qa)

                                                    <tr>

                                                        <td class="fw-medium">{{ $qa->subject }}</td>

                                                        <td>{{ $qa->part_count }} پارت</td>

                                                        <td>{{ $qa->time_per_part }} دقیقه</td>

                                                        <td>{{ jalali($qa->qa_date)->format('%d %B %Y') }}</td>

                                                    </tr>

                                                @endforeach

                                                </tbody>

                                            </table>

                                        </div>

                                    @else

                                        <p class="text-muted small mb-0">هیچ پرسش و پاسخ کلاسی ثبت نشده است</p>

                                    @endif

                                </div>

                            </div>


                            {{-- تکالیف --}}

                            <div class="col-lg-6">

                                <div class="border rounded-3 p-3 h-100">

                                    <h6 class="fw-bold text-warning mb-3 d-flex align-items-center gap-2">

                                        <i class="material-symbols-outlined">assignment</i>

                                        تکالیف

                                        <span
                                            class="badge bg-label-warning rounded-pill">{{ $preSession->assignments->count() }}</span>

                                    </h6>

                                    @if($preSession->assignments->count() > 0)

                                        <div class="table-responsive">

                                            <table class="table table-sm mb-0">

                                                <thead>

                                                <tr>

                                                    <th>درس</th>

                                                    <th>تعداد پارت</th>

                                                    <th>مهلت انجام</th>

                                                </tr>

                                                </thead>

                                                <tbody>

                                                @foreach($preSession->assignments as $assignment)

                                                    <tr>

                                                        <td class="fw-medium">{{ $assignment->subject }}</td>

                                                        <td>{{ $assignment->part_count }} پارت</td>

                                                        <td>{{ jalali($assignment->due_date)->format('%d %B %Y') }}</td>

                                                    </tr>

                                                @endforeach

                                                </tbody>

                                            </table>

                                        </div>

                                    @else

                                        <p class="text-muted small mb-0">هیچ تکلیفی ثبت نشده است</p>

                                    @endif

                                </div>

                            </div>


                            {{-- متفرقه --}}

                            <div class="col-lg-6">

                                <div class="border rounded-3 p-3 h-100">

                                    <h6 class="fw-bold text-success mb-3 d-flex align-items-center gap-2">

                                        <i class="material-symbols-outlined">notes</i>

                                        متفرقه

                                    </h6>

                                    @if($preSession->miscellaneous)

                                        <div class="bg-light rounded-3 p-3">

                                            <p class="mb-0"
                                               style="white-space: pre-line;">{{ $preSession->miscellaneous->description }}</p>

                                        </div>

                                    @else

                                        <p class="text-muted small mb-0">اطلاعات متفرقه ثبت نشده است</p>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach
        @endif

        {{-- تنظیمات برنامه هفتگی --}}
        <div class="card mb-5 border-0 shadow-sm">

            <div class="card-header bg-gradient-primary text-white d-flex align-items-center gap-2">

                <i class="material-symbols-outlined">calendar_month</i>

                <h5 class="mb-0">تاریخ شروع برنامه</h5>

            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-medium d-flex align-items-center gap-2">
                            <i class="material-symbols-outlined text-primary" style="font-size: 20px;">event</i>
                            تاریخ شروع هفته
                        </label>
                        <input type="date"
                               wire:model.live="start_date"
                               class="form-control">
                        <div class="form-text d-flex align-items-center gap-1 mt-2">
                            <i class="material-symbols-outlined text-info" style="font-size: 16px;">info</i>
                            <span>روز و تاریخ هر روز هفته به صورت خودکار محاسبه می‌شود</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- جدول برنامه هفتگی --}}
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-gradient-primary text-white d-flex align-items-center gap-2">
                <i class="material-symbols-outlined">view_week</i>
                <h5 class="mb-0">برنامه هفتگی</h5>
            </div>
            <div class="card-datatable table-responsive pt-0">
                <table class="table table-bordered align-middle mb-0" style="min-width: 1000px;">
                    <thead>
                    <tr style="background: linear-gradient(90deg, #42a5f5, #1e88e5); color: #fff;">
                        <th class="text-center fw-bold" style="width: 80px;">روز</th>
                        <th class="text-center fw-bold" style="width: 120px;">تاریخ</th>
                        <th class="text-center fw-bold" style="width: 110px;">ساعت</th>
                        @for($i = 1; $i <= 10; $i++)
                            <th class="text-center fw-bold">پلن {{ $i }}</th>
                        @endfor
                        <th class="text-center fw-bold" style="width: 90px;">تست روز</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($weekDays as $day)
                        <tr>
                            {{-- روز --}}
                            <td class="text-center">
                                <span class="badge bg-primary rounded-pill px-3 py-2 fw-bold">
                                    {{ $day['name'] }}
                                </span>
                            </td>

                            {{-- تاریخ --}}
                            <td class="text-center">
                                <span class="fw-medium text-muted">
                                    {{ $day['jalali_date'] }}
                                </span>
                            </td>

                            {{-- ساعت کل --}}
                            <td class="text-center">
                                <span class="fw-bold">
                                    {{ $day['total_hours'] }}
                                </span>
                                <small class="text-muted d-block">ساعت</small>
                            </td>

                            {{-- پلن‌ها --}}
                            @for($i = 0; $i < 10; $i++)
                                <td>
                                    @if(isset($day['parts'][$i]))
                                        @php $part = $day['parts'][$i]; @endphp
                                        <div
                                            class="rounded-3 p-3 border cursor-pointer {{ $part->color_class }}"
                                            wire:click="editPart({{ $part->id }})"
                                        >
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="fw-bold small">{{ $part->lesson_name }}</span>
                                                <span class="badge bg-white text-dark border border-light text-xs">
                                                    {{ $part->lesson_type_label }} {{ $part->grade_label }}
                                                </span>
                                            </div>

                                            <p class="small mb-2 text-muted">
                                                {{ Str::limit($part->description, 50) }}
                                            </p>

                                            <div class="d-flex flex-wrap gap-2 small text-muted">
                                                <span class="d-flex align-items-center gap-1">
                                                    <i class="material-symbols-outlined" style="font-size: 14px;">schedule</i>
                                                    {{ $part->duration_minutes }} دقیقه
                                                </span>

                                                @if($part->test_count)
                                                    <span class="d-flex align-items-center gap-1">
                                                        <i class="material-symbols-outlined" style="font-size: 14px;">quiz</i>
                                                        {{ $part->test_count }} تست
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="rounded-3 border border-dashed border-secondary border-opacity-25 d-flex align-items-center justify-content-center"
                                            style="height: 96px; cursor: pointer;"
                                            wire:click="openPartModal({{ $day['index'] }})"
                                        >
                                            <i class="material-symbols-outlined text-muted">add</i>
                                        </div>
                                    @endif
                                </td>
                            @endfor

                            {{-- تست روز --}}
                            <td class="text-center">
                                <span class="badge bg-label-warning fw-bold">
                                    {{ $day['total_tests'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- آمار و نمودارها --}}
        @if($weeklyProgram && $weeklyProgram->parts->count() > 0)
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-gradient-info text-white d-flex align-items-center gap-2">
                    <i class="material-symbols-outlined">analytics</i>
                    <h5 class="mb-0 text-white">خلاصه اطلاعات جزئی برنامه</h5>
                </div>

                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="rounded-3 p-4 border bg-primary bg-opacity-10">
                                <p class="small text-muted mb-1">جمع کل ساعت مطالعه</p>
                                <p class="fs-3 fw-bold text-primary mb-0">{{ $weeklyProgram->total_hours }}</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="rounded-3 p-4 border bg-warning bg-opacity-10">
                                <p class="small text-muted mb-1">جمع کل تعداد تست</p>
                                <p class="fs-3 fw-bold text-warning mb-0">{{ $weeklyProgram->total_tests }}</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="rounded-3 p-4 border bg-success bg-opacity-10">
                                <p class="small text-muted mb-1">تعداد کل پارت‌ها</p>
                                <p class="fs-3 fw-bold text-success mb-0">{{ $weeklyProgram->total_parts }}</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="rounded-3 p-4 border bg-purple bg-opacity-10">
                                <p class="small text-muted mb-1">تعداد پلن‌های درسی</p>
                                <p class="fs-3 fw-bold text-purple mb-0">{{ $weeklyProgram->total_plans }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6 col-md-2">
                            <div class="rounded-3 p-3 text-center bg-light">
                                <div class="fs-3 fw-bold text-primary">{{ $weeklyProgram->test_parts_count }}</div>
                                <div class="small text-muted mt-1">پارت تستی</div>
                            </div>
                        </div>

                        <div class="col-6 col-md-2">
                            <div class="rounded-3 p-3 text-center bg-light">
                                <div
                                    class="fs-3 fw-bold text-warning">{{ $weeklyProgram->descriptive_parts_count }}</div>
                                <div class="small text-muted mt-1">پارت تشریحی</div>
                            </div>
                        </div>

                        <div class="col-6 col-md-2">
                            <div class="rounded-3 p-3 text-center bg-light">
                                <div class="fs-3 fw-bold text-purple">{{ $weeklyProgram->video_parts_count }}</div>
                                <div class="small text-muted mt-1">پارت ویدئو</div>
                            </div>
                        </div>

                        <div class="col-6 col-md-2">
                            <div class="rounded-3 p-3 text-center bg-light">
                                <div class="fs-3 fw-bold text-teal">{{ $weeklyProgram->grade_10_parts_count }}</div>
                                <div class="small text-muted mt-1">پارت دهم</div>
                            </div>
                        </div>

                        <div class="col-6 col-md-2">
                            <div class="rounded-3 p-3 text-center bg-light">
                                <div class="fs-3 fw-bold text-danger">{{ $weeklyProgram->grade_11_parts_count }}</div>
                                <div class="small text-muted mt-1">پارت یازدهم</div>
                            </div>
                        </div>

                        <div class="col-6 col-md-2">
                            <div class="rounded-3 p-3 text-center bg-light">
                                <div class="fs-3 fw-bold text-indigo">{{ $weeklyProgram->grade_12_parts_count }}</div>
                                <div class="small text-muted mt-1">پارت دوازدهم</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- دکمه ذخیره / بازگشت --}}
        <div class="d-flex justify-content-end gap-2 mb-4">
            <a href="{{ route('admin.advising-sessions') }}"
               class="btn btn-label-secondary">
                بازگشت
            </a>

            <button wire:click="finalSave"
                    class="btn btn-primary">
                <span wire:loading.remove>ذخیره برنامه</span>
                <span wire:loading>در حال ذخیره...</span>
            </button>
        </div>
    </div>

    {{-- Modal اضافه کردن / ویرایش پارت --}}

    @if($showPartModal)
        <div class="modal fade show d-block" tabindex="-1"
             style="background: rgba(0,0,0,0.6); backdrop-filter: blur(3px);">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-gradient-primary text-white">
                        <h5 class="modal-title d-flex align-items-center gap-2">
                            <i class="material-symbols-outlined">{{ $editingPartId ? 'edit' : 'add_circle' }}</i>
                            {{ $editingPartId ? 'ویرایش پارت' : 'افزودن پارت جدید' }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closePartModal"></button>

                    </div>


                    <div class="modal-body">


                        {{-- انتخاب دوره تحصیلی --}}

                        <div class="row g-3 mb-3">

                            <div class="col-md-6">
                                <label class="form-label fw-medium d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined text-primary"
                                       style="font-size: 20px;">school</i>
                                    دوره تحصیلی
                                    <span class="text-danger">*</span>
                                </label>

                                <select

                                    wire:model.live="partForm.education_level_id"

                                    class="form-select @error('partForm.education_level_id') is-invalid @enderror"

                                >

                                    <option value="">انتخاب کنید</option>

                                    @foreach($educationLevels as $level)

                                        <option value="{{ $level->id }}">{{ $level->name }}</option>

                                    @endforeach

                                </select>

                                @error('partForm.education_level_id')

                                <div class="text-danger small">{{ $message }}</div>

                                @enderror

                            </div>


                            <div class="col-md-6">
                                <label class="form-label fw-medium d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined text-success"
                                       style="font-size: 20px;">stairs</i>
                                    پایه تحصیلی
                                    <span class="text-danger">*</span>
                                </label>

                                <select

                                    wire:model.live="partForm.cc_grade_id"

                                    class="form-select @error('partForm.cc_grade_id') is-invalid @enderror"

                                    {{ empty($grades) ? 'disabled' : '' }}

                                >

                                    <option
                                        value="">{{ empty($grades) ? 'ابتدا دوره را انتخاب کنید' : 'انتخاب کنید' }}</option>

                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->id }}">
                                            {{ $grade->name }}
                                            @if($grade->field)
                                                ({{ $grade->field->name }})
                                            @endif
                                        </option>
                                    @endforeach

                                </select>

                                @error('partForm.cc_grade_id')

                                <div class="text-danger small">{{ $message }}</div>

                                @enderror

                            </div>

                        </div>

                        {{-- رشته و درس --}}
                        <div class="row g-3 mb-3">
                            @if(count($fields) > 0 && $partForm['cc_grade_id'])
                                <div class="col-md-4">
                                    <label class="form-label">رشته</label>
                                    <select
                                        wire:model.live="partForm.cc_field_id"
                                        class="form-select"
                                    >
                                        <option value="">بدون رشته</option>
                                        @foreach($fields as $field)
                                            <option value="{{ $field->id }}">{{ $field->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">برای متوسطه اول خالی بگذارید</small>
                                </div>
                            @endif

                            <div class="{{ count($fields) > 0 && $partForm['cc_grade_id'] ? 'col-md-8' : 'col-12' }}">
                                <label class="form-label fw-medium d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined text-warning" style="font-size: 20px;">book</i>
                                    درس
                                    <span class="text-danger">*</span>
                                </label>
                                <select
                                    wire:model.live="partForm.cc_subject_id"
                                    class="form-select @error('partForm.cc_subject_id') is-invalid @enderror"
                                    {{ empty($subjects) ? 'disabled' : '' }}
                                >
                                    <option
                                        value="">{{ empty($subjects) ? 'ابتدا پایه را انتخاب کنید' : 'انتخاب کنید' }}</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">
                                            {{ $subject->name }} ({{ $subject->type === 'general' ? 'عمومی' : 'تخصصی' }}
                                            )
                                        </option>
                                    @endforeach
                                </select>
                                @error('partForm.cc_subject_id')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        {{-- فصل و مبحث --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined text-info" style="font-size: 20px;">bookmark</i>
                                    فصل
                                </label>
                                <select
                                    wire:model.live="partForm.cc_chapter_id"
                                    class="form-select"
                                    {{ empty($chapters) ? 'disabled' : '' }}
                                >
                                    <option
                                        value="">{{ empty($chapters) ? 'ابتدا درس را انتخاب کنید' : 'انتخاب کنید' }}</option>
                                    @foreach($chapters as $chapter)
                                        <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined text-purple" style="font-size: 20px;">topic</i>
                                    مبحث
                                </label>
                                <select
                                    wire:model.live="partForm.cc_topic_id"
                                    class="form-select"
                                    {{ empty($topics) ? 'disabled' : '' }}
                                >
                                    <option
                                        value="">{{ empty($topics) ? 'ابتدا فصل را انتخاب کنید' : 'انتخاب کنید' }}</option>
                                    @foreach($topics as $topic)
                                        <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- توضیحات --}}
                        <div class="mb-3">
                            <label class="form-label fw-medium d-flex align-items-center gap-2">
                                <i class="material-symbols-outlined text-secondary"
                                   style="font-size: 20px;">description</i>
                                توضیحات پارت
                            </label>
                            <textarea
                                wire:model="partForm.description"
                                rows="2"
                                class="form-control"
                                placeholder="مسیر انتخاب شده یا توضیحات دلخواه"
                            ></textarea>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined text-danger"
                                       style="font-size: 20px;">category</i>
                                    نوع پارت
                                    <span class="text-danger">*</span>
                                </label>
                                <select wire:model.live="partForm.part_type" class="form-select">
                                    <option value="descriptive">تشریحی</option>
                                    <option value="test">تستی</option>
                                    <option value="video">ویدئو</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined text-primary"
                                       style="font-size: 20px;">schedule</i>
                                    مدت زمان (دقیقه)
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       min="1"
                                       wire:model="partForm.duration_minutes"
                                       class="form-control @error('partForm.duration_minutes') is-invalid @enderror">
                                @error('partForm.duration_minutes')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            @if($partForm['part_type'] === 'test')
                                <div class="col-md-4">
                                    <label class="form-label fw-medium d-flex align-items-center gap-2">
                                        <i class="material-symbols-outlined text-warning"
                                           style="font-size: 20px;">quiz</i>
                                        تعداد تست
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number"
                                           min="1"
                                           wire:model="partForm.test_count"
                                           class="form-control @error('partForm.test_count') is-invalid @enderror"
                                           placeholder="تعداد تست را وارد کنید">
                                    @error('partForm.test_count')
                                    <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="modal-footer">
                        @if($editingPartId)
                            <button type="button"
                                    class="btn btn-danger"
                                    wire:click="deletePart({{ $editingPartId }})">
                                حذف
                            </button>
                        @endif

                        <button type="button"
                                class="btn btn-secondary"
                                wire:click="closePartModal">
                            انصراف
                        </button>

                        <button type="button"
                                class="btn btn-primary"
                                wire:click="savePart">
                            ذخیره
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

    @push('scripts')

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            function initSelect2() {
                // حذف Select2 های قبلی
                if ($('.modal select.select2-hidden-accessible').length) {
                    $('.modal select.select2-hidden-accessible').select2('destroy');
                }
                // Initialize Select2 برای تمام select های داخل مودال
                $('.modal select').each(function () {
                    if (!$(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2({
                            dir: "rtl",
                            language: "fa",
                            placeholder: $(this).find('option:first').text(),
                            allowClear: false,
                            dropdownParent: $('.modal-content'),
                            width: '100%'
                        });
                        // وقتی Select2 تغییر کرد، Livewire را آپدیت کن
                        $(this).on('select2:select', function (e) {
                            let wireModel = $(this).attr('wire:model.live') || $(this).attr('wire:model');
                            if (wireModel) {
                            @this.set(wireModel, $(this).val())
                                ;
                            }
                        });
                    }
                });
            }

            // وقتی مودال باز شد
            Livewire.on('modal-opened', () => {
                setTimeout(() => {
                    initSelect2();
                }, 100);
            });
            // بعد از آپدیت Livewire
            document.addEventListener('livewire:update', () => {
                setTimeout(() => {
                    if ($('.modal.show').length) {
                        initSelect2();
                    }
                }, 100);
            });
            // اولین بار که صفحه لود میشه
            $(document).ready(function () {
                // اگر مودال باز هست
                if ($('.modal.show').length) {
                    initSelect2();
                }
            });
        </script>
    @endpush
</div>
