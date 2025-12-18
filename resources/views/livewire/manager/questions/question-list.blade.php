<div>

    <div class="container-fluid">

        <!-- Page Header -->

        <div class="row mb-4">

            <div class="col-12">

                <div class="card">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <h4 class="card-title mb-0">بانک سوالات</h4>

                        <div class="d-flex gap-2">

                            <button wire:click="openPdfModal" class="btn btn-outline-danger">

                                <i class="ti ti-file-type-pdf me-1"></i>

                                خروجی PDF

                            </button>

                            <a href="{{ route('manager.questions.form') }}" class="btn btn-primary">

                                <i class="ti ti-plus me-1"></i>

                                افزودن سوال جدید

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Filters Card -->

        <div class="row mb-4">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0">

                            <i class="ti ti-filter me-1"></i>

                            فیلترها

                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <!-- Education Level -->

                            <div class="col-md-4 col-lg-2">

                                <label class="form-label">دوره تحصیلی</label>

                                <select wire:model.live="filterEducationLevel" class="form-select">

                                    <option value="">همه</option>

                                    @foreach($educationLevels as $level)

                                        <option value="{{ $level->id }}">{{ $level->name }}</option>

                                    @endforeach

                                </select>

                            </div>


                            <!-- Grade -->

                            <div class="col-md-4 col-lg-2">

                                <label class="form-label">پایه</label>

                                <select wire:model.live="filterGrade"
                                        class="form-select" {{ empty($grades) ? 'disabled' : '' }}>

                                    <option value="">همه</option>

                                    @foreach($grades as $grade)

                                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>

                                    @endforeach

                                </select>

                            </div>


                            <!-- Field (conditional) -->

                            @if($showFieldFilter)

                                <div class="col-md-4 col-lg-2">

                                    <label class="form-label">رشته</label>

                                    <select wire:model.live="filterField"
                                            class="form-select" {{ empty($fields) ? 'disabled' : '' }}>

                                        <option value="">همه</option>

                                        @foreach($fields as $field)

                                            <option value="{{ $field->id }}">{{ $field->name }}</option>

                                        @endforeach

                                    </select>

                                </div>

                            @endif



                            <!-- Subject -->

                            <div class="col-md-4 col-lg-2">

                                <label class="form-label">درس</label>

                                <select wire:model.live="filterSubject"
                                        class="form-select" {{ empty($subjects) ? 'disabled' : '' }}>

                                    <option value="">همه</option>

                                    @foreach($subjects as $subject)

                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>

                                    @endforeach

                                </select>

                            </div>


                            <!-- Chapter -->

                            <div class="col-md-4 col-lg-2">

                                <label class="form-label">فصل</label>

                                <select wire:model.live="filterChapter"
                                        class="form-select" {{ empty($chapters) ? 'disabled' : '' }}>

                                    <option value="">همه</option>

                                    @foreach($chapters as $chapter)

                                        <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>

                                    @endforeach

                                </select>

                            </div>


                            <!-- Topic -->

                            <div class="col-md-4 col-lg-2">

                                <label class="form-label">مبحث</label>

                                <select wire:model.live="filterTopic"
                                        class="form-select" {{ empty($topics) ? 'disabled' : '' }}>

                                    <option value="">همه</option>

                                    @foreach($topics as $topic)

                                        <option value="{{ $topic->id }}">{{ $topic->name }}</option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        <div class="row g-3 mt-2">

                            <!-- Difficulty Filter -->

                            <div class="col-md-3">

                                <label class="form-label">میزان سختی</label>

                                <select wire:model.live="filterDifficulty" class="form-select">

                                    <option value="">همه سطوح</option>

                                    @foreach($difficulties as $value => $label)

                                        <option value="{{ $value }}">{{ $label }}</option>

                                    @endforeach

                                </select>

                            </div>


                            <!-- Code Filter -->

                            <div class="col-md-3">

                                <label class="form-label">کد سوال</label>

                                <input type="text"

                                       wire:model.live.debounce.300ms="filterCode"

                                       class="form-control"

                                       placeholder="جستجوی کد..."

                                       inputmode="numeric">

                            </div>

                        </div>


                        @if($filterEducationLevel || $filterGrade || $filterField || $filterSubject || $filterChapter || $filterTopic || $filterDifficulty || $filterCode)

                            <div class="mt-3">

                                <button wire:click="clearFilters" class="btn btn-outline-secondary btn-sm">

                                    <i class="ti ti-x me-1"></i>

                                    پاک کردن فیلترها

                                </button>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        <!-- Questions List -->

        <div class="row">

            <div class="col-12">

                @if($questions->isEmpty())

                    <div class="card">

                        <div class="card-body text-center py-5">

                            <i class="ti ti-file-unknown text-muted" style="font-size: 4rem;"></i>

                            <h5 class="mt-3 text-muted">سوالی یافت نشد</h5>

                            <p class="text-muted">با فیلترهای انتخاب شده سوالی وجود ندارد.</p>

                        </div>

                    </div>

                @else

                    @foreach($questions as $question)

                        <div class="card mb-3 question-card" wire:key="question-{{ $question->id }}">

                            <!-- Question Header -->

                            <div
                                class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">

                                <div class="d-flex align-items-center gap-2 flex-wrap">

                                    <span class="badge bg-primary">کد: {{ $question->code }}</span>

                                    @if($question->topic)

                                        <span
                                            class="badge bg-info">{{ $question->topic->chapter->subject->name ?? '-' }}</span>

                                        <span class="badge bg-secondary">{{ $question->topic->name ?? '-' }}</span>

                                    @endif

                                    <span
                                        class="badge bg-{{ $question->difficulty === 'easy' ? 'success' : ($question->difficulty === 'medium' ? 'warning' : ($question->difficulty === 'hard' ? 'danger' : 'info')) }}">

                                        {{ $difficulties[$question->difficulty] ?? $question->difficulty }}

                                    </span>

                                    <span class="badge bg-dark">پاسخ: گزینه {{ $question->correct_option }}</span>

                                </div>

                                <div class="d-flex gap-2">

                                    <a href="{{ route('manager.questions.form', ['code' => $question->code]) }}"

                                       class="btn btn-sm btn-outline-primary"

                                       title="ویرایش">

                                        <i class="ri-pencil-line"></i>

                                        ویرایش

                                    </a>

                                    <button wire:click="deleteQuestion({{ $question->id }})"

                                            wire:confirm="آیا از حذف این سوال اطمینان دارید؟"

                                            class="btn btn-sm btn-outline-danger"

                                            title="حذف">

                                        <i class="ri-delete-bin-6-line"></i>

                                        حذف

                                    </button>

                                </div>

                            </div>


                            <!-- Question Body -->

                            <div class="card-body">

                                @if($question->content && $question->content->hasQuestionImage())

                                    <div class="question-image-container text-center mb-3">

                                        <img src="{{ $question->content->question_image_url }}"

                                             alt="سوال {{ $question->code }}"

                                             class="img-fluid rounded shadow-sm question-image"

                                             style="max-height: 400px;">

                                    </div>

                                @else

                                    <div class="alert alert-warning">

                                        <i class="ti ti-alert-triangle me-1"></i>

                                        عکس سوال آپلود نشده است.

                                    </div>

                                @endif

                            </div>


                            <!-- Accordion Footer (Explanation) -->

                            <div class="card-footer bg-white p-0">

                                <button
                                    class="btn btn-link w-100 text-decoration-none text-start d-flex justify-content-between align-items-center px-3 py-2"

                                    wire:click="toggleExpand({{ $question->id }})"

                                    type="button">

                                    <span>

                                        <i class="ti ti-info-circle me-1"></i>

                                        مشاهده پاسخ تشریحی

                                    </span>

                                    <i class="ti ti-chevron-{{ in_array($question->id, $expandedQuestions) ? 'up' : 'down' }}"></i>

                                </button>


                                @if(in_array($question->id, $expandedQuestions))

                                    <div class="border-top p-3">

                                        <div class="mb-3">

                                            <strong class="text-success">

                                                <i class="ti ti-check me-1"></i>

                                                پاسخ صحیح:

                                            </strong>

                                            <span class="badge bg-success ms-2">

                                                گزینه {{ $question->correct_option }}

                                            </span>

                                        </div>


                                        @if($question->content && $question->content->hasExplanationImage())

                                            <div class="explanation-content p-3 bg-light rounded">

                                                <strong class="d-block mb-2">

                                                    <i class="ti ti-book me-1"></i>

                                                    پاسخ تشریحی:

                                                </strong>

                                                <img src="{{ $question->content->explanation_image_url }}"

                                                     alt="پاسخ تشریحی"

                                                     class="img-fluid rounded"

                                                     style="max-height: 400px;">

                                            </div>

                                        @else

                                            <div class="text-muted">

                                                <i class="ti ti-info-circle me-1"></i>

                                                پاسخ تشریحی برای این سوال ثبت نشده است.

                                            </div>

                                        @endif

                                    </div>

                                @endif

                            </div>

                        </div>

                    @endforeach



                    <!-- Pagination -->

                    <div class="d-flex justify-content-center mt-4">

                        {{ $questions->links('layouts.manager.pagination') }}

                    </div>

                @endif

            </div>

        </div>

    </div>


    <!-- PDF Export Modal -->

    @if($showPdfModal)

        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">

            <div class="modal-dialog modal-lg modal-dialog-centered">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">

                            <i class="ti ti-file-type-pdf text-danger me-2"></i>

                            خروجی PDF سوالات

                        </h5>

                        <button type="button" class="btn-close" wire:click="closePdfModal"></button>

                    </div>

                    <div class="modal-body">

                        <div class="row g-3">

                            <!-- Education Level -->

                            <div class="col-md-4">

                                <label class="form-label">دوره تحصیلی <span class="text-danger">*</span></label>

                                <select wire:model.live="pdfEducationLevel" class="form-select">

                                    <option value="">انتخاب کنید...</option>

                                    @foreach($educationLevels as $level)

                                        <option value="{{ $level->id }}">{{ $level->name }}</option>

                                    @endforeach

                                </select>

                            </div>


                            <!-- Grade -->

                            <div class="col-md-4">

                                <label class="form-label">پایه <span class="text-danger">*</span></label>

                                <select wire:model.live="pdfGrade"
                                        class="form-select" {{ empty($pdfGrades) ? 'disabled' : '' }}>

                                    <option value="">انتخاب کنید...</option>

                                    @foreach($pdfGrades as $grade)

                                        <option value="{{ $grade->id }}">{{ $grade->name }}</option>

                                    @endforeach

                                </select>

                            </div>


                            <!-- Field (conditional) -->

                            @if($showPdfFieldFilter)

                                <div class="col-md-4">

                                    <label class="form-label">رشته</label>

                                    <select wire:model.live="pdfField"
                                            class="form-select" {{ empty($pdfFields) ? 'disabled' : '' }}>

                                        <option value="">همه</option>

                                        @foreach($pdfFields as $field)

                                            <option value="{{ $field->id }}">{{ $field->name }}</option>

                                        @endforeach

                                    </select>

                                </div>

                            @endif



                            <!-- Subject -->

                            <div class="col-md-4">

                                <label class="form-label">درس <span class="text-danger">*</span></label>

                                <select wire:model.live="pdfSubject"
                                        class="form-select" {{ empty($pdfSubjects) ? 'disabled' : '' }}>

                                    <option value="">انتخاب کنید...</option>

                                    @foreach($pdfSubjects as $subject)

                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>

                                    @endforeach

                                </select>

                            </div>


                            <!-- Chapter -->

                            <div class="col-md-4">

                                <label class="form-label">فصل</label>

                                <select wire:model.live="pdfChapter"
                                        class="form-select" {{ empty($pdfChapters) ? 'disabled' : '' }}>

                                    <option value="">همه</option>

                                    @foreach($pdfChapters as $chapter)

                                        <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>

                                    @endforeach

                                </select>

                            </div>


                            <!-- Topic -->

                            <div class="col-md-4">

                                <label class="form-label">مبحث</label>

                                <select wire:model.live="pdfTopic"
                                        class="form-select" {{ empty($pdfTopics) ? 'disabled' : '' }}>

                                    <option value="">همه</option>

                                    @foreach($pdfTopics as $topic)

                                        <option value="{{ $topic->id }}">{{ $topic->name }}</option>

                                    @endforeach

                                </select>

                            </div>


                            <!-- Difficulty -->

                            <div class="col-md-4">

                                <label class="form-label">سطح سختی</label>

                                <select wire:model="pdfDifficulty" class="form-select">

                                    <option value="">همه</option>

                                    @foreach($difficulties as $value => $label)

                                        <option value="{{ $value }}">{{ $label }}</option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        <hr class="my-4">


                        <div class="row g-3">

                            <div class="col-12">

                                <div class="form-check">

                                    <input type="checkbox"

                                           wire:model="pdfExplanationAtEnd"

                                           class="form-check-input"

                                           id="pdfExplanationAtEnd">

                                    <label class="form-check-label" for="pdfExplanationAtEnd">

                                        پاسخنامه تشریحی در انتها

                                        <small class="text-muted d-block">اگر فعال باشد، پاسخنامه بعد از همه سوالات
                                            نمایش داده می‌شود</small>

                                    </label>

                                </div>

                            </div>

                            <div class="col-12">

                                <div class="form-check">

                                    <input type="checkbox"

                                           wire:model="pdfSeparateAnswer"

                                           class="form-check-input"

                                           id="pdfSeparateAnswer">

                                    <label class="form-check-label" for="pdfSeparateAnswer">

                                        پاسخنامه جدا

                                        <small class="text-muted d-block">سوالات و پاسخنامه در دو فایل جدا</small>

                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" wire:click="closePdfModal">

                            انصراف

                        </button>

                        <button type="button" class="btn btn-danger" wire:click="generatePdf">

                            <i class="ti ti-download me-1"></i>

                            دانلود PDF

                        </button>

                    </div>

                </div>

            </div>

        </div>

    @endif



    @push('link')

        <style>

            .question-card {

                transition: box-shadow 0.2s;

            }

            .question-card:hover {

                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);

            }

            .question-image {

                max-width: 100%;

                height: auto;

            }

            .dark .modal-content {

                background-color: #1e293b;

                color: #e2e8f0;

            }

            .dark .modal-header {

                border-bottom-color: #334155;

            }

            .dark .modal-footer {

                border-top-color: #334155;

            }

        </style>

    @endpush

</div>
