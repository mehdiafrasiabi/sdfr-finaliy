<div>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="card-title mb-1">
                                {{ $isEditMode ? 'ویرایش آزمون' : 'ایجاد آزمون جدید' }}
                            </h4>
                            <span class="badge bg-primary-subtle text-primary">
                                <i class="ti ti-clipboard-list"></i>
                                شما تاکنون {{ $myExamsCount }} آزمون ساخته‌اید
                            </span>
                        </div>
                        <a href="{{ route('admin.typed-exams.my-exams') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-right me-1"></i>
                            بازگشت به لیست
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Wizard Steps -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="wizard-steps d-flex justify-content-center">
                            @foreach([1 => 'تنظیمات کلی', 2 => 'انتخاب سوالات', 3 => 'ترتیب سوالات'] as $step => $label)
                                <div class="wizard-step text-center px-4 {{ $currentStep === $step ? 'active' : '' }} {{ $currentStep > $step ? 'completed' : '' }}"
                                     wire:click="goToStep({{ $step }})"
                                     style="cursor: pointer;">
                                    <div class="step-number rounded-circle d-inline-flex align-items-center justify-content-center
                                        {{ $currentStep === $step ? 'bg-primary text-white' : ($currentStep > $step ? 'bg-success text-white' : 'bg-light') }}"
                                         style="width: 40px; height: 40px;">
                                        @if($currentStep > $step)
                                            <i class="ti ti-check"></i>
                                        @else
                                            {{ $step }}
                                        @endif
                                    </div>
                                    <div class="step-label mt-2 {{ $currentStep === $step ? 'fw-bold text-primary' : 'text-muted' }}">
                                        {{ $label }}
                                    </div>
                                </div>
                                @if($step < 3)
                                    <div class="step-connector flex-grow-1 align-self-start mt-4"
                                         style="height: 2px; background: {{ $currentStep > $step ? 'var(--bs-success)' : 'var(--bs-gray-300)' }};"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Step 1: General Settings -->
        @if($currentStep === 1)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">تنظیمات کلی آزمون</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Title -->
                                <div class="col-md-6">
                                    <label class="form-label">عنوان آزمون <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="title" class="form-control" placeholder="عنوان آزمون را وارد کنید">
                                    @error('title')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Academic Year (Dynamic) -->
                                <div class="col-md-3">
                                    <label class="form-label">دوره زمانی <span class="text-danger">*</span></label>
                                    <select wire:model="academic_year" class="form-select">
                                        <option value="">انتخاب کنید...</option>
                                        @foreach($examPeriods as $period)
                                            <option value="{{ $period->value }}">{{ $period->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('academic_year')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Difficulty -->
                                <div class="col-md-3">
                                    <label class="form-label">درجه سختی آزمون <span class="text-danger">*</span></label>
                                    <select wire:model="difficulty" class="form-select">
                                        @foreach($examDifficulties as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Random Selection Toggle -->
                                <div class="col-md-12">
                                    <label class="form-label">انتخاب تصادفی سوالات</label>
                                    <div class="form-check form-switch">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               wire:click="toggleRandomSelection"
                                            {{ $is_random_selection ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ $is_random_selection ? 'فعال' : 'غیرفعال' }}
                                        </label>
                                    </div>
                                    <small class="text-muted">با فعال کردن این گزینه می‌توانید از بانک سوالات به صورت تصادفی سوال انتخاب کنید</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" wire:click="nextStep">
                                مرحله بعد
                                <i class="ti ti-arrow-left ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- Step 2: Question Selection -->
        @if($currentStep === 2)
            <div class="row">
                <div class="col-12">
                    <!-- Selected Questions Summary -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-primary fs-6">
                                        {{ count($selectedQuestions) }} سوال انتخاب شده
                                    </span>
                                </div>
                                @if($is_random_selection)
                                    <button type="button" class="btn btn-info" wire:click="openRandomModal">
                                        <i class="ti ti-wand me-1"></i>
                                        انتخاب تصادفی سوالات
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Hierarchical Filters -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="ti ti-filter me-1"></i> فیلترها</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Education Level -->
                                <div class="col-md-2">
                                    <label class="form-label">دوره تحصیلی</label>
                                    <select wire:model.live="filterEducationLevel" class="form-select">
                                        <option value="">همه</option>
                                        @foreach($educationLevels as $level)
                                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Grade -->
                                <div class="col-md-2">
                                    <label class="form-label">پایه</label>
                                    <select wire:model.live="filterGrade" class="form-select" {{ empty($grades) ? 'disabled' : '' }}>
                                        <option value="">همه</option>
                                        @foreach($grades as $grade)
                                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Field (for grades >= 10) -->
                                @if($showFieldFilter)
                                    <div class="col-md-2">
                                        <label class="form-label">رشته</label>
                                        <select wire:model.live="filterField" class="form-select">
                                            <option value="">همه</option>
                                            @foreach($fields as $field)
                                                <option value="{{ $field->id }}">{{ $field->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <!-- Subject -->
                                <div class="col-md-2">
                                    <label class="form-label">درس</label>
                                    <select wire:model.live="filterSubject" class="form-select" {{ empty($subjects) ? 'disabled' : '' }}>
                                        <option value="">همه</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Chapter -->
                                <div class="col-md-2">
                                    <label class="form-label">فصل</label>
                                    <select wire:model.live="filterChapter" class="form-select" {{ empty($chapters) ? 'disabled' : '' }}>
                                        <option value="">همه</option>
                                        @foreach($chapters as $chapter)
                                            <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Topic -->
                                <div class="col-md-2">
                                    <label class="form-label">مبحث</label>
                                    <select wire:model.live="filterTopic" class="form-select" {{ empty($topics) ? 'disabled' : '' }}>
                                        <option value="">همه</option>
                                        @foreach($topics as $topic)
                                            <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Difficulty -->
                                <div class="col-md-2">
                                    <label class="form-label">سطح سختی</label>
                                    <select wire:model.live="filterDifficulty" class="form-select">
                                        <option value="">همه</option>
                                        @foreach($questionDifficulties as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Code -->
                                <div class="col-md-2">
                                    <label class="form-label">کد سوال</label>
                                    <input type="text" wire:model.live.debounce.300ms="filterCode" class="form-control" placeholder="کد...">
                                </div>
                                <!-- Sort Order -->
                                <div class="col-md-2">
                                    <label class="form-label">ترتیب</label>
                                    <select wire:model.live="sortOrder" class="form-select">
                                        <option value="desc">جدید به قدیم</option>
                                        <option value="asc">قدیم به جدید</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button wire:click="clearFilters" class="btn btn-outline-secondary w-100">
                                        <i class="ti ti-x"></i>
                                        حذف فیلتر
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('selectedQuestions')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <!-- Questions List -->
                    <div class="card">
                        <div class="card-body">
                            @if($questions && $questions->isNotEmpty())
                                @foreach($questions as $question)
                                    @php $usageCount = $questionUsageCounts[$question->id] ?? 0; @endphp
                                    <div class="question-item p-3 mb-3 border rounded {{ $this->isQuestionSelected($question->id) ? 'border-success bg-success-subtle' : '' }}">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="d-flex gap-2 mb-2 flex-wrap">
                                                    <span class="badge bg-primary">{{ $question->code }}</span>
                                                    @if($question->topic)
                                                        <span class="badge bg-secondary">{{ $question->topic->name }}</span>
                                                    @elseif($question->chapter)
                                                        <span class="badge bg-secondary">{{ $question->chapter->name }}</span>
                                                        <span class="badge bg-primary">جامع</span>
                                                    @endif
                                                    <span class="badge bg-info">{{ $questionDifficulties[$question->difficulty] ?? $question->difficulty }}</span>
                                                    @if($question->correct_option_number)
                                                        <span class="badge bg-success">گزینه {{ $question->correct_option_number }}</span>
                                                    @endif
                                                    @if($usageCount > 0)
                                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle"
                                                              title="این سوال قبلاً در آزمون‌های شما استفاده شده">
                                                            <i class="ti ti-refresh"></i>
                                                            {{ $usageCount }} بار در آزمون‌های شما تکرار شده
                                                        </span>
                                                    @endif
                                                </div>
                                                <!-- Question Image Preview -->
                                                @if($question->content && $question->content->question_image_url)
                                                    <div class="question-preview mt-2">
                                                        <img src="{{ $question->content->question_image_url }}"
                                                             alt="تصویر سوال"
                                                             class="img-fluid rounded"
                                                             style="max-height: 100px; object-fit: contain;">
                                                    </div>
                                                @else
                                                    <div class="text-muted small">
                                                        <i class="ti ti-photo-off me-1"></i>
                                                        تصویر سوال موجود نیست
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ms-3">
                                                @if($this->isQuestionSelected($question->id))
                                                    <button wire:click="removeQuestion({{ $question->id }})"
                                                            class="btn btn-outline-danger btn-sm">
                                                        <i class="ti ti-minus"></i>
                                                        حذف
                                                    </button>
                                                @else
                                                    <button wire:click="addQuestion({{ $question->id }})"
                                                            class="btn btn-outline-success btn-sm">
                                                        <i class="ti ti-plus"></i>
                                                        اضافه کردن
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $questions->links('layouts.admin.pagination') }}
                                </div>
                            @else
                                <div class="text-center py-5 text-muted">
                                    <i class="ti ti-file-unknown" style="font-size: 3rem;"></i>
                                    <p class="mt-2">سوالی یافت نشد</p>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" wire:click="prevStep">
                                <i class="ti ti-arrow-right me-1"></i>
                                مرحله قبل
                            </button>
                            <button type="button" class="btn btn-primary" wire:click="nextStep">
                                مرحله بعد
                                <i class="ti ti-arrow-left ms-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- Step 3: Question Order -->
        @if($currentStep === 3)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">ترتیب نهایی سوالات</h5>
                            <p class="text-muted small mb-0">
                                سوالات را با کشیدن دستگیره یا با دکمه‌های بالا/پایین جابه‌جا کنید
                            </p>
                        </div>
                        <div class="card-body">
                            <div wire:ignore.self id="sortable-questions-admin">
                                @foreach($orderedQuestions as $index => $question)
                                    <div wire:key="wizard-question-{{ $question['id'] }}"
                                        class="sortable-item p-3 mb-2 border rounded bg-light d-flex align-items-center flex-wrap gap-3"
                                        data-id="{{ $question['id'] }}">
                                        <div class="drag-handle cursor-move d-none d-sm-block">
                                            <i class="ti ti-grip-vertical text-muted fs-4"></i>
                                        </div>
                                        <div class="order-number badge bg-primary">{{ $index + 1 }}</div>

                                        @if($question['image'] ?? null)
                                            <img src="{{ $question['image'] }}" alt="تصویر سوال {{ $question['code'] }}"
                                                 class="rounded border flex-shrink-0"
                                                 style="width: 56px; height: 56px; object-fit: cover;">
                                        @else
                                            <div class="rounded border bg-secondary-subtle d-flex align-items-center justify-content-center flex-shrink-0 text-muted"
                                                 style="width: 56px; height: 56px;">
                                                <i class="ti ti-photo-off"></i>
                                            </div>
                                        @endif

                                        <div class="flex-grow-1" style="min-width: 140px;">
                                            <span class="badge bg-secondary">{{ $question['code'] }}</span>
                                            <span class="text-muted ms-2">{{ $question['topic'] ?? '-' }}</span>
                                        </div>

                                        <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                            <button type="button" wire:click="moveQuestionUp({{ $question['id'] }})"
                                                    class="btn btn-outline-secondary btn-sm"
                                                    title="جابه‌جایی به بالا"
                                                    {{ $loop->first ? 'disabled' : '' }}>
                                                <i class="ti ti-chevron-up"></i>
                                            </button>
                                            <button type="button" wire:click="moveQuestionDown({{ $question['id'] }})"
                                                    class="btn btn-outline-secondary btn-sm"
                                                    title="جابه‌جایی به پایین"
                                                    {{ $loop->last ? 'disabled' : '' }}>
                                                <i class="ti ti-chevron-down"></i>
                                            </button>
                                            <button wire:click="removeQuestion({{ $question['id'] }})" class="btn btn-outline-danger btn-sm">
                                                <i class="ti ti-trash"></i>
                                                حذف
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if(empty($orderedQuestions))
                                <div class="text-center py-5 text-muted">
                                    <p>سوالی انتخاب نشده است</p>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" wire:click="prevStep">
                                <i class="ti ti-arrow-right me-1"></i>
                                مرحله قبل
                            </button>
                            <button type="button" class="btn btn-success" wire:click="save" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="save">
                                    <i class="ti ti-device-floppy me-1"></i>
                                    ذخیره آزمون
                                </span>
                                <span wire:loading wire:target="save">
                                    <i class="ti ti-loader ti-spin me-1"></i>
                                    در حال ذخیره...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- Random Selection Modal -->
        @if($showRandomModal)
            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">انتخاب تصادفی سوالات</h5>
                            <button type="button" class="btn-close" wire:click="$set('showRandomModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <!-- Education Level -->
                                <div class="col-md-4">
                                    <label class="form-label">دوره تحصیلی</label>
                                    <select wire:model.live="randomEducationLevel" class="form-select">
                                        <option value="">همه</option>
                                        @foreach($educationLevels as $level)
                                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Grade -->
                                <div class="col-md-4">
                                    <label class="form-label">پایه</label>
                                    <select wire:model.live="randomGrade"
                                            class="form-select" {{ empty($randomGrades) ? 'disabled' : '' }}>
                                        <option value="">همه</option>
                                        @foreach($randomGrades as $grade)
                                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Field (for grades >= 10) -->
                                @if($showRandomFieldFilter)
                                    <div class="col-md-4">
                                        <label class="form-label">رشته</label>
                                        <select wire:model.live="randomField" class="form-select">
                                            <option value="">همه</option>
                                            @foreach($randomFields as $field)
                                                <option value="{{ $field->id }}">{{ $field->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <!-- Subject -->
                                <div class="col-md-4">
                                    <label class="form-label">درس</label>
                                    <select wire:model.live="randomSubject" class="form-select" {{ empty($randomSubjects) ? 'disabled' : '' }}>
                                        <option value="">همه</option>
                                        @foreach($randomSubjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Chapter -->
                                <div class="col-md-4">
                                    <label class="form-label">فصل</label>
                                    <select wire:model.live="randomChapter" class="form-select" {{ empty($randomChapters) ? 'disabled' : '' }}>
                                        <option value="">همه</option>
                                        @foreach($randomChapters as $chapter)
                                            <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Topic -->
                                <div class="col-md-4">
                                    <label class="form-label">مبحث</label>
                                    <select wire:model.live="randomTopic" class="form-select" {{ empty($randomTopics) ? 'disabled' : '' }}>
                                        <option value="">همه</option>
                                        @foreach($randomTopics as $topic)
                                            <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                                <!-- Difficulty -->
                                <div class="col-md-6">
                                    <label class="form-label">درجه سختی سوالات</label>
                                    <select wire:model="randomDifficulty" class="form-select">
                                        <option value="">همه</option>
                                        @foreach($questionDifficulties as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Count -->
                                <div class="col-md-6">
                                    <label class="form-label">تعداد سوالات</label>
                                    <input type="number" wire:model="randomCount" class="form-control" min="1" max="100">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" wire:click="clearRandomFilters">
                                <i class="ti ti-x me-1"></i>
                                پاک کردن فیلترها
                            </button>
                            <button type="button" class="btn btn-secondary" wire:click="$set('showRandomModal', false)">
                                انصراف
                            </button>
                            <button type="button" class="btn btn-primary" wire:click="selectRandomQuestions">
                                <i class="ti ti-wand me-1"></i>
                                انتخاب
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @push('link')
        <style>
            /* رفع باگ مهم UI: در بوت‌استرپ ۵.۳ خاصیت پیش‌فرض .card به‌صورت
               height: var(--bs-card-height, calc(100% - gutter)) تعریف شده که برای
               استفاده در گرید «row-cards» ساخته شده. وقتی چند card به‌صورت پشت‌سرهم
               (نه هرکدام در یک ستون جدا) داخل یک ستون قرار می‌گیرند - مثل مرحله ۲ این
               ویزارد (کارت خلاصه + کارت فیلترها + کارت لیست سوالات) - این درصد ارتفاع
               به‌اشتباه به یک مقدار پیکسلی بسیار بزرگ (مجموع ارتفاع واقعی هر سه کارت)
               resolve می‌شود و باعث می‌شود همه‌ی کارت‌ها به همان ارتفاع غول‌آسا کشیده
               شوند؛ نتیجه یک فضای خالی چند متری زیر هر بخش است. راه‌حل: ارتفاع کارت‌ها
               در این صفحه را به حالت طبیعی (بر اساس محتوا) برمی‌گردانیم. */
            .card {
                --bs-card-height: auto !important;
                height: auto !important;
            }
            /* هدر مراحل ویزارد: در صفحه‌های باریک (موبایل) باید جمع و جور بشه، نه اینکه از عرض صفحه بزنه بیرون */
            .wizard-steps {
                flex-wrap: wrap;
                row-gap: .75rem;
            }
            .wizard-step {
                min-width: 80px;
            }
            .wizard-step .step-label {
                font-size: .75rem;
            }
            @media (min-width: 576px) {
                .wizard-step {
                    min-width: 120px;
                }
                .wizard-step .step-label {
                    font-size: 1rem;
                }
            }
            .step-connector {
                min-width: 24px;
            }
            .sortable-item {
                transition: all 0.2s;
            }
            .sortable-item:hover {
                background-color: #e9ecef !important;
            }
            .drag-handle {
                cursor: move;
            }
            .sortable-ghost {
                opacity: 0.4;
            }
            .sortable-chosen {
                background-color: var(--bs-primary-bg-subtle) !important;
            }
            /* اگر هر بخشی (مثلاً جدول یا ردیف فیلترها) از عرض صفحه بزرگ‌تر شد، به‌جای بهم‌ریختن کل صفحه، خودش اسکرول افقی بگیرد */
            .container-fluid {
                overflow-x: hidden;
            }
        </style>
    @endpush
    @push('script')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            document.addEventListener('livewire:init', function () {
                initSortableAdminExamWizard();
            });
            document.addEventListener('livewire:navigated', function () {
                initSortableAdminExamWizard();
            });
            function initSortableAdminExamWizard() {
                const container = document.getElementById('sortable-questions-admin');
                if (container && !container.dataset.sortableInitialized) {
                    container.dataset.sortableInitialized = '1';
                    new Sortable(container, {
                        animation: 150,
                        handle: '.drag-handle',
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        onEnd: function () {
                            const items = [...container.querySelectorAll('.sortable-item')].map((el) => ({
                                id: parseInt(el.dataset.id, 10),
                            }));
                            @this.call('updateQuestionOrder', items);
                        }
                    });
                }
            }
        </script>
    @endpush
</div>
