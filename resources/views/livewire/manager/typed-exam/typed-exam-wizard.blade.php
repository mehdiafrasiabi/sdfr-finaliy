<div>

    <div class="container-fluid">

        <!-- Page Header -->

        <div class="row mb-4">

            <div class="col-12">

                <div class="card">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <h4 class="card-title mb-0">

                            {{ $isEditMode ? 'ویرایش آزمون' : 'ایجاد آزمون جدید' }}

                        </h4>

                        <a href="{{ route('manager.typed-exams.index') }}" class="btn btn-secondary">

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

                                <div
                                    class="wizard-step text-center px-4 {{ $currentStep === $step ? 'active' : '' }} {{ $currentStep > $step ? 'completed' : '' }}"

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

                                    <div
                                        class="step-label mt-2 {{ $currentStep === $step ? 'fw-bold text-primary' : 'text-muted' }}">

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

                                    <input type="text" wire:model="title" class="form-control"
                                           placeholder="عنوان آزمون را وارد کنید">

                                    @error('title')
                                    <div class="text-danger small mt-1">{{ $message }}</div> @enderror

                                </div>


                                <!-- Academic Year -->

                                <div class="col-md-3">

                                    <label class="form-label">دوره زمانی (سال تحصیلی) <span class="text-danger">*</span></label>

                                    <select wire:model="academic_year" class="form-select">

                                        @foreach($academicYears as $value => $label)

                                            <option value="{{ $value }}">{{ $label }}</option>

                                        @endforeach

                                    </select>

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

                                <div class="col-md-4">

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

                                </div>


                                <!-- Result Visibility -->

                                <div class="col-md-4">

                                    <label class="form-label">زمان نمایش کارنامه</label>

                                    <select wire:model="result_visibility" class="form-select">

                                        @foreach($resultVisibilities as $value => $label)

                                            <option value="{{ $value }}">{{ $label }}</option>

                                        @endforeach

                                    </select>

                                </div>


                                <!-- Answer Key Visibility -->

                                <div class="col-md-4">

                                    <label class="form-label">زمان نمایش پاسخ‌نامه</label>

                                    <select wire:model="answer_key_visibility" class="form-select">

                                        @foreach($resultVisibilities as $value => $label)

                                            <option value="{{ $value }}">{{ $label }}</option>

                                        @endforeach

                                    </select>

                                </div>


                                <!-- Randomization Type -->

                                <div class="col-md-6">

                                    <label class="form-label">ترتیب تصادفی سوالات/گزینه‌ها</label>

                                    <select wire:model="randomization_type" class="form-select">

                                        @foreach($randomizationTypes as $value => $label)

                                            <option value="{{ $value }}">{{ $label }}</option>

                                        @endforeach

                                    </select>

                                </div>


                                <!-- Description -->

                                <div class="col-12">

                                    <label class="form-label">توضیحات آزمون</label>

                                    <textarea wire:model="description" class="form-control" rows="3"
                                              placeholder="توضیحات اختیاری..."></textarea>

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

                                    <div class="alert alert-info mb-0 py-2">

                                        <i class="ti ti-info-circle me-1"></i>

                                        سوالات به صورت تصادفی انتخاب شده‌اند

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>


                    <!-- Filters -->

                    <div class="card mb-4">

                        <div class="card-header">

                            <h5 class="card-title mb-0"><i class="ti ti-filter me-1"></i> فیلترها</h5>

                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                <div class="col-md-2">

                                    <label class="form-label">درس</label>

                                    <select wire:model.live="filterSubject" class="form-select">

                                        <option value="">همه</option>

                                        @foreach($subjects as $subject)

                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>

                                        @endforeach

                                    </select>

                                </div>

                                <div class="col-md-2">

                                    <label class="form-label">سطح سختی</label>

                                    <select wire:model.live="filterDifficulty" class="form-select">

                                        <option value="">همه</option>

                                        @foreach($questionDifficulties as $value => $label)

                                            <option value="{{ $value }}">{{ $label }}</option>

                                        @endforeach

                                    </select>

                                </div>

                                <div class="col-md-2">

                                    <label class="form-label">کد سوال</label>

                                    <input type="text" wire:model.live.debounce.300ms="filterCode" class="form-control"
                                           placeholder="کد...">

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">کلید واژه</label>

                                    <input type="text" wire:model.live.debounce.500ms="filterKeyword"
                                           class="form-control" placeholder="جستجو...">

                                </div>

                                <div class="col-md-2">

                                    <label class="form-label">ترتیب</label>

                                    <select wire:model.live="sortOrder" class="form-select">

                                        <option value="desc">جدید به قدیم</option>

                                        <option value="asc">قدیم به جدید</option>

                                    </select>

                                </div>

                                <div class="col-md-1 d-flex align-items-end">

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

                                    <div
                                        class="question-item p-3 mb-3 border rounded {{ $this->isQuestionSelected($question->id) ? 'border-success bg-success-subtle' : '' }}">

                                        <div class="d-flex justify-content-between align-items-start">

                                            <div class="flex-grow-1">

                                                <div class="d-flex gap-2 mb-2">

                                                    <span class="badge bg-primary">{{ $question->code }}</span>

                                                    <span
                                                        class="badge bg-secondary">{{ $question->subject?->name }}</span>

                                                    <span
                                                        class="badge bg-info">{{ $questionDifficulties[$question->difficulty] ?? $question->difficulty }}</span>

                                                </div>

                                                <div class="question-preview text-muted small"
                                                     style="max-height: 60px; overflow: hidden;">

                                                    {{ Str::limit(strip_tags($question->content?->body), 150) }}

                                                </div>

                                            </div>

                                            <div>

                                                @if($this->isQuestionSelected($question->id))

                                                    <button wire:click="removeQuestion({{ $question->id }})"
                                                            class="btn btn-outline-danger btn-sm">

                                                        <i class="ri-subtract-line"></i>
                                                        حذف
                                                    </button>

                                                @else

                                                    <button wire:click="addQuestion({{ $question->id }})"
                                                            class="btn btn-outline-success btn-sm">

                                                        <i class="ri-add-line"></i>
                                                        اضافه کردن
                                                    </button>

                                                @endif

                                            </div>

                                        </div>

                                    </div>

                                @endforeach



                                <div class="d-flex justify-content-center mt-4">

                                    {{ $questions->links('layouts.manager.pagination') }}

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

                            <p class="text-muted small mb-0">سوالات را با کشیدن و رها کردن مرتب کنید</p>

                        </div>

                        <div class="card-body">

                            <div wire:ignore id="sortable-questions">

                                @foreach($orderedQuestions as $index => $question)

                                    <div
                                        class="sortable-item p-3 mb-2 border rounded bg-light d-flex align-items-center gap-3"

                                        data-id="{{ $question['id'] }}"

                                        data-code="{{ $question['code'] }}"

                                        data-subject="{{ $question['subject'] }}"

                                        data-difficulty="{{ $question['difficulty'] }}">

                                        <div class="drag-handle cursor-move">

                                            <i class="ti ti-grip-vertical text-muted fs-4"></i>

                                        </div>

                                        <div class="order-number badge bg-primary">{{ $index + 1 }}</div>

                                        <div class="flex-grow-1">

                                            <span class="badge bg-secondary">{{ $question['code'] }}</span>

                                            <span class="text-muted ms-2">{{ $question['subject'] }}</span>


                                        </div>

                                        <button wire:click="removeQuestion({{ $question['id'] }})"
                                                class="btn btn-outline-danger btn-sm">

                                            <i class=" ri-delete-bin-6-line"></i>
                                            حذف

                                        </button>

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

                            <button type="button" class="btn btn-success" wire:click="save"
                                    wire:loading.attr="disabled">

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

                <div class="modal-dialog modal-dialog-centered">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title">انتخاب تصادفی سوالات</h5>

                            <button type="button" class="btn-close"
                                    wire:click="$set('showRandomModal', false)"></button>

                        </div>

                        <div class="modal-body">

                            <div class="mb-3">

                                <label class="form-label">درجه سوالات</label>

                                <select wire:model="randomDifficulty" class="form-select">

                                    @foreach($questionDifficulties as $value => $label)

                                        <option value="{{ $value }}">{{ $label }}</option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">تعداد سوالات</label>

                                <input type="number" wire:model="randomCount" class="form-control" min="1" max="100">

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-secondary" wire:click="$set('showRandomModal', false)">
                                انصراف
                            </button>

                            <button type="button" class="btn btn-primary" wire:click="selectRandomQuestions">

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

            .wizard-step {
                min-width: 120px;
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

        </style>

    @endpush


    @push('link')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    @endpush

    @push('script')

        <script src="/manager/assets/js/pages/select2.init.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

        <script>

            document.addEventListener('livewire:init', function () {

                initSortable();

            });


            document.addEventListener('livewire:navigated', function () {

                initSortable();

            });


            function initSortable() {

                const container = document.getElementById('sortable-questions');

                if (container) {

                    new Sortable(container, {

                        animation: 150,

                        handle: '.drag-handle',

                        ghostClass: 'sortable-ghost',

                        chosenClass: 'sortable-chosen',

                        onEnd: function (evt) {

                            const items = [...container.querySelectorAll('.sortable-item')].map((el, index) => ({

                                id: parseInt(el.dataset.id),

                                code: el.dataset.code,

                                subject: el.dataset.subject,

                                difficulty: el.dataset.difficulty,

                            }));


                        @this.call('updateQuestionOrder', items)
                            ;


                            // Update visual order numbers

                            container.querySelectorAll('.order-number').forEach((el, index) => {

                                el.textContent = index + 1;

                            });

                        }

                    });

                }

            }


        </script>

    @endpush

</div>
