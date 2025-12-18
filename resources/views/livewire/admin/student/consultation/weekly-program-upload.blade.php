<div>
    <div class="container-xxl flex-grow-1 container-p-y">
        @push('link')
            <style>
                [x-cloak] {
                    display: none !important;
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
            <div class="card mb-4">
                <h5 class="card-header">پیش‌جلسه‌های ثبت شده توسط دانش‌آموز</h5>
                <div class="card-datatable table-responsive pt-0">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>عنوان جلسه</th>
                            <th>امتحانات</th>
                            <th>پرسش و پاسخ</th>
                            <th>تکالیف</th>
                            <th>وضعیت</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($preSessions as $preSession)
                            <tr>
                                <td>{{ $preSession->title }}</td>
                                <td>{{ $preSession->exams->count() }} مورد</td>
                                <td>{{ $preSession->qas->count() }} مورد</td>
                                <td>{{ $preSession->assignments->count() }} مورد</td>
                                <td>
                                    <span class="badge rounded-pill text-xs
                                        {{ $preSession->status === 'completed'
                                            ? 'bg-label-success'
                                            : 'bg-label-warning' }}">
                                        {{ $preSession->status_label }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- تنظیمات برنامه هفتگی --}}
        <div class="card mb-4">
            <h5 class="card-header">تاریخ شروع برنامه</h5>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">تاریخ شروع هفته</label>
                        <input type="date"
                               wire:model.live="start_date"
                               class="form-control">
                        <small class="text-muted">روز و تاریخ به صورت خودکار محاسبه می‌شود</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- جدول برنامه هفتگی --}}
        <div class="card mb-4">
            <h5 class="card-header">برنامه هفتگی</h5>
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
            <div class="card mb-4">
                <h5 class="card-header d-flex align-items-center gap-2">
                    <i class="material-symbols-outlined text-primary">analytics</i>
                    <span>خلاصه اطلاعات جزئی برنامه</span>
                </h5>

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

        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">

            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

                <div class="modal-content">


                    <div class="modal-header">

                        <h5 class="modal-title">

                            {{ $editingPartId ? 'ویرایش پارت' : 'افزودن پارت جدید' }}

                        </h5>

                        <button type="button" class="btn-close" wire:click="closePartModal"></button>

                    </div>


                    <div class="modal-body">


                        {{-- انتخاب دوره تحصیلی --}}

                        <div class="row g-3 mb-3">

                            <div class="col-md-6">

                                <label class="form-label">دوره تحصیلی <span class="text-danger">*</span></label>

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

                                <label class="form-label">پایه تحصیلی <span class="text-danger">*</span></label>

                                <select

                                    wire:model.live="partForm.cc_grade_id"

                                    class="form-select @error('partForm.cc_grade_id') is-invalid @enderror"

                                    {{ empty($grades) ? 'disabled' : '' }}

                                >

                                    <option
                                        value="">{{ empty($grades) ? 'ابتدا دوره را انتخاب کنید' : 'انتخاب کنید' }}</option>

                                    @foreach($grades as $grade)

                                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>

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
                                <label class="form-label">درس <span class="text-danger">*</span></label>
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
                                <label class="form-label">فصل</label>
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
                                <label class="form-label">مبحث</label>
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
                            <label class="form-label">توضیحات پارت</label>
                            <textarea
                                wire:model="partForm.description"
                                rows="2"
                                class="form-control"
                                placeholder="مسیر انتخاب شده یا توضیحات دلخواه"
                            ></textarea>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">مدت زمان (دقیقه) <span class="text-danger">*</span></label>
                                <input type="number"
                                       min="1"
                                       wire:model="partForm.duration_minutes"
                                       class="form-control @error('partForm.duration_minutes') is-invalid @enderror">
                                @error('partForm.duration_minutes')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">تعداد تست (اختیاری)</label>
                                <input type="number"
                                       min="0"
                                       wire:model="partForm.test_count"
                                       class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">نوع پارت <span class="text-danger">*</span></label>
                                <select wire:model="partForm.part_type" class="form-select">
                                    <option value="descriptive">تشریحی</option>
                                    <option value="test">تستی</option>
                                    <option value="video">ویدئو</option>
                                </select>
                            </div>
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


</div>
