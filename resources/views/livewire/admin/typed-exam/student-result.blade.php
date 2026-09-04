<div>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">نتیجه آزمون</h4>
                            <p class="text-muted mb-0">
                                {{ $exam->title }} -
                                {{ $attempt->student?->user?->name ?? 'نامشخص' }}
                            </p>
                        </div>
                        <a href="{{ route('admin.typed-exams.assignment', ['examId' => $examId]) }}"
                           class="btn btn-secondary">
                            <i class="ti ti-arrow-right me-1"></i>
                            بازگشت
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Stats Summary -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h6 class="text-muted">نمره کل</h6>
                        <div class="display-6 text-primary fw-bold">{{ $stats['score'] ?? 0 }}%</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center h-100 bg-success-subtle">
                    <div class="card-body">
                        <h6 class="text-success">صحیح</h6>
                        <div class="h3 text-success">{{ $stats['correct'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center h-100 bg-danger-subtle">
                    <div class="card-body">
                        <h6 class="text-danger">غلط</h6>
                        <div class="h3 text-danger">{{ $stats['wrong'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center h-100 bg-secondary-subtle">
                    <div class="card-body">
                        <h6 class="text-secondary">بدون پاسخ</h6>
                        <div class="h3 text-secondary">{{ $stats['unanswered'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h6 class="text-muted">زمان صرف شده</h6>
                        <div class="h3">{{ $stats['duration'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- پیام موفقیت عملیات ادمین --}}
        @if(session()->has('success'))
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-success mb-0">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        @endif

        {{-- تحلیل سیستم (اگر داری متغیر systemAnalysis یا فیلد مشابه روی attempt) --}}
        @if(!empty($attempt->system_analysis ?? null))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">تحلیل سیستم</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">
                                {{ $attempt->system_analysis }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- تحلیل دانش‌آموز و آپلودها برای ادمین --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">تحلیل دانش‌آموز (تصاویر آپلود شده)</h5>
                            <small class="text-muted d-block mt-1">
                                وضعیت تحلیل:
                                @if($attempt->analysis_status === 'pending')
                                    <span class="badge bg-warning text-dark">در انتظار بررسی</span>
                                @elseif($attempt->analysis_status === 'approved')
                                    <span class="badge bg-success">تایید شده</span>
                                @elseif($attempt->analysis_status === 'rejected')
                                    <span class="badge bg-danger">رد شده</span>
                                @else
                                    <span class="badge bg-secondary">نامشخص</span>
                                @endif
                            </small>
                        </div>

                        {{-- دکمه‌های تایید/رد فقط وقتی در وضعیت pending و دارای تصویر --}}
                        @if($attempt->analysis_status === 'pending' && $attempt->analysisUploads->count() > 0)
                            <div class="d-flex gap-2">
                                <button
                                    wire:click="approveAnalysis"
                                    wire:loading.attr="disabled"
                                    class="btn btn-success"
                                >
                            <span wire:loading.remove wire:target="approveAnalysis">
                                تایید تحلیل
                            </span>
                                    <span wire:loading wire:target="approveAnalysis">
                                در حال ثبت...
                            </span>
                                </button>

                                <button
                                    wire:click="rejectAnalysis"
                                    wire:loading.attr="disabled"
                                    class="btn btn-danger"
                                >
                            <span wire:loading.remove wire:target="rejectAnalysis">
                                رد تحلیل
                            </span>
                                    <span wire:loading wire:target="rejectAnalysis">
                                در حال ثبت...
                            </span>
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="card-body">
                        @if($attempt->analysisUploads->count() > 0)
                            <div class="row g-3">
                                @foreach($attempt->analysisUploads as $upload)
                                    <div class="col-6 col-md-3">
                                        <a href="{{ $upload->url }}" target="_blank">
                                            <img
                                                src="{{ $upload->url }}"
                                                alt="تصویر تحلیل"
                                                class="img-fluid rounded border"
                                            >
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">
                                تاکنون تصویری برای تحلیل توسط دانش‌آموز آپلود نشده است.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Questions with Answers -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">جزئیات پاسخ‌ها</h5>
                    </div>
                    <div class="card-body">
                        @foreach($questionsWithAnswers as $index => $qa)
                            @php
                                $question = $qa['question'];
                                $selectedOption = $qa['selected_option'];
                                $isCorrect = $qa['is_correct'];
                                $correctOptionNumber = $qa['correct_option_number'];
                                $optionNumbers = $qa['ordered_options']->isNotEmpty()
                                    ? $qa['ordered_options']->pluck('option_number')->map(fn ($number) => (int) $number)->values()
                                    : collect([1, 2, 3, 4]);
                            @endphp
                            <div
                                class="question-box border rounded p-4 mb-4 {{ $isCorrect === true ? 'border-success' : ($isCorrect === false ? 'border-danger' : 'border-secondary') }}">
                                <!-- Question Header -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-primary">سوال {{ $index + 1 }}</span>
                                        <span class="badge bg-secondary">{{ $question->code }}</span>
                                        <span class="badge bg-info">{{ $question->subject?->name }}</span>
                                    </div>
                                    <div>
                                        @if($isCorrect === true)
                                            <span class="badge bg-success"><i class="ti ti-check me-1"></i> صحیح</span>
                                        @elseif($isCorrect === false)
                                            <span class="badge bg-danger"><i class="ti ti-x me-1"></i> غلط</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="ti ti-minus me-1"></i> بدون پاسخ</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- Question Body (Image or Text) -->
                                <div class="question-body mb-3" dir="rtl">
                                    @if($question->content?->question_image_url)
                                        <img src="{{ $question->content->question_image_url }}"
                                             alt="تصویر سوال {{ $index + 1 }}"
                                             class="img-fluid rounded mb-3"
                                             style="max-width: 100%; max-height: 400px; object-fit: contain;">
                                    @elseif($question->content?->body)
                                        {!! $question->content->body !!}
                                    @else
                                        <div class="text-muted text-center py-4">
                                            <i class="ti ti-photo-off fs-1"></i>
                                            <p class="mt-2">تصویر سوال موجود نیست</p>
                                        </div>
                                    @endif
                                </div>
                                <hr>
                                <!-- Options (Simple numbered with correct/wrong indicators) -->
                                <div class="options-list">
                                    @foreach($optionNumbers as $positionIndex => $optNum)
                                        @php
                                            $position = $positionIndex + 1;
                                            $isSelected = (int) $selectedOption === (int) $optNum;
                                            $isCorrectOption = (int) $correctOptionNumber === (int) $optNum;
                                            $bgClass = '';
                                            if ($isCorrectOption) {
                                                $bgClass = 'bg-success-subtle border border-success';
                                            } elseif ($isSelected && !$isCorrectOption) {
                                                $bgClass = 'bg-danger-subtle border border-danger';
                                            }
                                        @endphp
                                        <div
                                            class="option-item d-flex align-items-center gap-2 mb-2 p-2 rounded {{ $bgClass }}">
                                            <span
                                                class="badge {{ $isCorrectOption ? 'bg-success' : ($isSelected ? 'bg-danger' : 'bg-secondary') }}"
                                                style="width: 30px;">
                                                {{ $position }}
                                            </span>
                                            <div class="flex-grow-1">گزینه {{ $position }}</div>
                                            @if($isSelected)
                                                <span class="badge bg-primary">انتخاب شده</span>
                                            @endif
                                            @if($isCorrectOption)
                                                <i class="ti ti-check text-success"></i>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <!-- Explanation (Image or Text) -->
                                @if($question->content?->explanation_image_url)
                                    <div class="explanation mt-3 p-3 bg-light rounded">
                                        <strong class="d-block mb-2"><i class="ti ti-book me-1"></i> توضیح:</strong>
                                        <img src="{{ $question->content->explanation_image_url }}"
                                             alt="توضیح سوال {{ $index + 1 }}"
                                             class="img-fluid rounded"
                                             style="max-width: 100%; max-height: 300px; object-fit: contain;">
                                    </div>
                                @elseif($question->content?->explanation)
                                    <div class="explanation mt-3 p-3 bg-light rounded">
                                        <strong class="d-block mb-2"><i class="ti ti-book me-1"></i> توضیح:</strong>
                                        {!! $question->content->explanation !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('link')
        <style>
            .question-body img, .option-item img, .explanation img {
                max-width: 100%;
                height: auto;
            }
        </style>
    @endpush
</div>
