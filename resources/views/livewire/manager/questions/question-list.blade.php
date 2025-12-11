<div>

    <div class="container-fluid">

        <!-- Page Header -->

        <div class="row mb-4">

            <div class="col-12">

                <div class="card">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <h4 class="card-title mb-0">بانک سوالات</h4>

                        <a href="{{ route('manager.questions.form') }}" class="btn btn-primary">

                            <i class="ti ti-plus me-1"></i>

                            افزودن سوال جدید

                        </a>

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

                            <!-- Subject Filter -->

                            <div class="col-md-3">

                                <label class="form-label">درس</label>

                                <select wire:model.live="filterSubject" class="form-select">

                                    <option value="">همه دروس</option>

                                    @foreach($subjects as $subject)

                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>

                                    @endforeach

                                </select>

                            </div>


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

                                <input

                                    type="text"

                                    wire:model.live.debounce.300ms="filterCode"

                                    class="form-control"

                                    placeholder="جستجوی کد..."

                                    inputmode="numeric"

                                >

                            </div>


                            <!-- Keyword Filter -->

                            <div class="col-md-3">

                                <label class="form-label">کلید واژه</label>

                                <input

                                    type="text"

                                    wire:model.live.debounce.500ms="filterKeyword"

                                    class="form-control"

                                    placeholder="جستجو در متن سوال..."

                                >

                            </div>

                        </div>


                        @if($filterSubject || $filterDifficulty || $filterCode || $filterKeyword)

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

                            <div class="card-header bg-light d-flex justify-content-between align-items-center">

                                <div class="d-flex align-items-center gap-3">

                                    <span class="badge bg-primary">کد: {{ $question->code }}</span>

                                    <span class="badge bg-secondary">{{ $question->subject?->name }}</span>

                                    <span
                                        class="badge bg-{{ $question->difficulty === 'easy' ? 'success' : ($question->difficulty === 'medium' ? 'warning' : ($question->difficulty === 'hard' ? 'danger' : 'info')) }}">

                                        {{ $difficulties[$question->difficulty] ?? $question->difficulty }}

                                    </span>

                                </div>

                                <div class="d-flex gap-2">

                                    <a href="{{ route('manager.questions.form', ['code' => $question->code]) }}"

                                       class="btn btn-sm btn-outline-primary"

                                       title="ویرایش">

                                        <i class=" ri-pencil-line"></i>
                                        ویرایش
                                    </a>

                                    <button

                                        wire:click="deleteQuestion({{ $question->id }})"

                                        wire:confirm="آیا از حذف این سوال اطمینان دارید؟"

                                        class="btn btn-sm btn-outline-danger"

                                        title="حذف">


                                        <i class=" ri-delete-bin-6-line"></i>
                                        حذف
                                    </button>

                                </div>

                            </div>


                            <!-- Question Body -->

                            <div class="card-body">

                                <div class="question-body mb-3 {{ $question->direction }}"
                                     dir="{{ $question->direction }}">

                                    {!! $question->content?->body !!}

                                </div>


                                <hr>


                                <!-- Options -->

                                <div class="options-list">

                                    @foreach($question->options as $option)

                                        <div
                                            class="option-item d-flex align-items-start gap-2 mb-2 p-2 rounded {{ $option->is_correct ? 'bg-success-subtle border border-success' : 'bg-light' }}">

                                            <span
                                                class="badge {{ $option->is_correct ? 'bg-success' : 'bg-secondary' }}">

                                                {{ $option->option_number }}

                                            </span>

                                            <div class="option-content flex-grow-1">

                                                {!! $option->content !!}

                                            </div>

                                            @if($option->is_correct)

                                                <i class="ti ti-check text-success"></i>

                                            @endif

                                        </div>

                                    @endforeach

                                </div>

                            </div>


                            <!-- Accordion Footer (Correct Answer & Explanation) -->

                            <div class="card-footer bg-white p-0">

                                <button

                                    class="btn btn-link w-100 text-decoration-none text-start d-flex justify-content-between align-items-center px-3 py-2"

                                    wire:click="toggleExpand({{ $question->id }})"

                                    type="button"

                                >

                                    <span>

                                        <i class="ti ti-info-circle me-1"></i>

                                        مشاهده پاسخ و توضیحات

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

                                            @php

                                                $correctOption = $question->options->firstWhere('is_correct', true);

                                            @endphp

                                            <span class="badge bg-success ms-2">

                                                گزینه {{ $correctOption?->option_number ?? '-' }}

                                            </span>

                                        </div>


                                        @if($question->content?->explanation)

                                            <div class="explanation-content p-3 bg-light rounded">

                                                <strong class="d-block mb-2">

                                                    <i class="ti ti-book me-1"></i>

                                                    توضیح تشریحی:

                                                </strong>

                                                {!! $question->content->explanation !!}

                                            </div>

                                        @else

                                            <div class="text-muted">

                                                <i class="ti ti-info-circle me-1"></i>

                                                توضیحی برای این سوال ثبت نشده است.

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


    @push('link')

        <style>

            .question-card {

                transition: box-shadow 0.2s;

            }

            .question-card:hover {

                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);

            }

            .question-body img {

                max-width: 100%;

                height: auto;

            }

            .option-content img {

                max-width: 100%;

                max-height: 100px;

            }

            .option-item.bg-success-subtle {

                border-color: var(--bs-success) !important;

            }

        </style>

    @endpush


</div>
