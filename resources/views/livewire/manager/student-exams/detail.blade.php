<div class="container-fluid py-4">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h4 class="mb-1">جزئیات امتحانات: {{ $setting->grade_label }} / {{ $setting->field_label }}</h4>
                <p class="text-muted mb-0">
                    از {{ jdate($setting->exam_starts_at)->format('Y/m/d') }} تا {{ jdate($setting->exam_ends_at)->format('Y/m/d') }}
                    <span class="mx-2">|</span>
                    {{ $setting->term_type_label }}
                </p>
            </div>
            <a href="{{ route('manager.student-exams.index') }}" class="btn btn-light">
                بازگشت به لیست
            </a>
        </div>
    </div>

    @if($setting->input_mode !== \App\Models\ExamPlanningSetting::INPUT_MANAGER)
        <div class="alert alert-info">این تنظیم در حالت «توسط دانش‌آموز» است و جزئیات روزانه توسط خود دانش‌آموز ثبت می‌شود.</div>
    @endif

    <div class="card">
        <div class="card-body">
            @forelse($calendarDays as $weekIndex => $week)
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-soft-primary text-primary">هفته {{ $weekIndex + 1 }}</span>
                    </div>
                    <div class="row g-3">
                        @foreach($week as $day)
                            <div class="col-12 col-md-6 col-xl">
                                @if($day)
                                    <div class="border rounded-3 h-100 p-3 bg-light-subtle">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <div class="fw-semibold">{{ $day['day_name'] }}</div>
                                                <small class="text-muted">{{ $day['jalali'] }}</small>
                                            </div>
                                            <span class="badge bg-soft-secondary text-dark">{{ $day['day_number'] }}</span>
                                        </div>

                                        @if($day['subjects']->isEmpty())
                                            <div class="alert alert-warning py-2 px-3 small mb-3">اگر این روز خالی بماند، به‌صورت خودکار فرجه حساب می‌شود.</div>
                                        @else
                                            <div class="d-flex flex-column gap-2 mb-3">
                                                @foreach($day['subjects'] as $examDay)
                                                    <div class="d-flex justify-content-between align-items-center border rounded-2 bg-white px-2 py-2">
                                                        <div>
                                                            <div class="fw-semibold small">{{ $examDay->subject->name }}</div>
                                                            <small class="text-muted">{{ $examDay->subject->type === 'general' ? 'عمومی' : 'تخصصی' }}</small>
                                                        </div>
                                                        <button class="btn btn-sm btn-soft-danger"
                                                                wire:click="removeExamDay({{ $examDay->id }})">
                                                            حذف
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($setting->input_mode === \App\Models\ExamPlanningSetting::INPUT_MANAGER)
                                            <div class="d-flex flex-column gap-2">
                                                <select class="form-select form-select-sm" wire:model="daySubjectSelections.{{ $day['selection_key'] }}">
                                                    <option value="">انتخاب درس برای این روز</option>
                                                    @foreach($subjectOptions as $subject)
                                                        <option value="{{ $subject['id'] }}">
                                                            {{ $subject['name'] }} - {{ $subject['type'] === 'general' ? 'عمومی' : 'تخصصی' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button class="btn btn-sm btn-primary" wire:click="addExamToDay('{{ $day['date'] }}', '{{ $day['selection_key'] }}')">
                                                    افزودن درس
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="border rounded-3 h-100 p-3 bg-transparent"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">برای این تنظیم هنوز بازه امتحانات مشخص نشده است.</div>
            @endforelse
        </div>
    </div>
</div>
