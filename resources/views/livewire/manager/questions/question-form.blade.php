<div>

    <div class="container-fluid">

        <!-- Page Header -->

        <div class="row mb-4">

            <div class="col-12">

                <div class="card">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <h4 class="card-title mb-0">

                            {{ $isEditMode ? 'ویرایش سوال' : 'ایجاد سوال جدید' }}

                            @if($questionCode)

                                <span class="badge bg-info ms-2">کد: {{ $questionCode }}</span>

                            @endif

                        </h4>

                        <a href="{{ route('manager.questions.index') }}" class="btn btn-secondary">

                            <i class="ti ti-arrow-right me-1"></i>

                            بازگشت به لیست

                        </a>

                    </div>

                </div>

            </div>

        </div>


        <form wire:submit.prevent="save">

            <!-- Hierarchical Filter Card -->

            <div class="row mb-4">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-filter me-1"></i>

                                انتخاب دسته‌بندی سوال

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                <!-- Education Level -->

                                <div class="col-md-4 col-lg-2">

                                    <label class="form-label">دوره تحصیلی <span class="text-danger">*</span></label>

                                    <select wire:model.live="educationLevelId" class="form-select select2-search">

                                        <option value="">انتخاب کنید...</option>

                                        @foreach($educationLevels as $level)

                                            <option value="{{ $level->id }}">{{ $level->name }}</option>

                                        @endforeach

                                    </select>

                                    @error('educationLevelId')

                                    <div class="text-danger small mt-1">{{ $message }}</div>

                                    @enderror

                                </div>


                                <!-- Grade -->

                                <div class="col-md-4 col-lg-2">

                                    <label class="form-label">پایه تحصیلی <span class="text-danger">*</span></label>

                                    <select wire:model.live="gradeId"
                                            class="form-select select2-search" {{ empty($grades) ? 'disabled' : '' }}>

                                        <option value="">انتخاب کنید...</option>

                                        @foreach($grades as $grade)

                                            <option value="{{ $grade->id }}">{{ $grade->name }}</option>

                                        @endforeach

                                    </select>

                                    @error('gradeId')

                                    <div class="text-danger small mt-1">{{ $message }}</div>

                                    @enderror

                                </div>


                                <!-- Field (only for grade >= 10) -->

                                @if($showFieldSelect)

                                    <div class="col-md-4 col-lg-2">

                                        <label class="form-label">رشته تحصیلی</label>

                                        <select wire:model.live="fieldId"
                                                class="form-select select2-search" {{ empty($fields) ? 'disabled' : '' }}>

                                            <option value="">انتخاب کنید...</option>

                                            @foreach($fields as $field)

                                                <option value="{{ $field->id }}">{{ $field->name }}</option>

                                            @endforeach

                                        </select>

                                        @error('fieldId')

                                        <div class="text-danger small mt-1">{{ $message }}</div>

                                        @enderror

                                    </div>

                                @endif



                                <!-- Subject -->

                                <div class="col-md-4 col-lg-2">

                                    <label class="form-label">درس <span class="text-danger">*</span></label>

                                    <select wire:model.live="subjectId"
                                            class="form-select select2-search" {{ empty($subjects) ? 'disabled' : '' }}>

                                        <option value="">انتخاب کنید...</option>

                                        @foreach($subjects as $subject)

                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>

                                        @endforeach

                                    </select>

                                    @error('subjectId')

                                    <div class="text-danger small mt-1">{{ $message }}</div>

                                    @enderror

                                </div>


                                <!-- Chapter -->

                                <div class="col-md-4 col-lg-2">

                                    <label class="form-label">فصل <span class="text-danger">*</span></label>

                                    <select wire:model.live="chapterId"
                                            class="form-select select2-search" {{ empty($chapters) ? 'disabled' : '' }}>

                                        <option value="">انتخاب کنید...</option>

                                        @foreach($chapters as $chapter)

                                            <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>

                                        @endforeach

                                    </select>

                                    @error('chapterId')

                                    <div class="text-danger small mt-1">{{ $message }}</div>

                                    @enderror

                                </div>


                                <!-- Topic -->

                                <div class="col-md-4 col-lg-2">

                                    <label class="form-label">مبحث <span class="text-danger">*</span></label>

                                    <select wire:model.live="topicId"
                                            class="form-select select2-search" {{ empty($topics) ? 'disabled' : '' }}>

                                        <option value="">انتخاب کنید...</option>

                                        @foreach($topics as $topic)

                                            <option value="{{ $topic->id }}">{{ $topic->name }}</option>

                                        @endforeach

                                    </select>

                                    @error('topicId')

                                    <div class="text-danger small mt-1">{{ $message }}</div>

                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Basic Info Card -->

            <div class="row mb-4">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">تنظیمات سوال</h5>

                        </div>

                        <div class="card-body">

                            <div class="row g-3">

                                <!-- Difficulty -->

                                <div class="col-md-6">

                                    <label class="form-label">میزان سختی سوال <span class="text-danger">*</span></label>

                                    <select wire:model="difficulty" class="form-select">

                                        @foreach($difficulties as $value => $label)

                                            <option value="{{ $value }}">{{ $label }}</option>

                                        @endforeach

                                    </select>

                                    @error('difficulty')

                                    <div class="text-danger small mt-1">{{ $message }}</div>

                                    @enderror

                                </div>


                                <!-- Correct Option -->

                                <div class="col-md-6">

                                    <label class="form-label">گزینه صحیح <span class="text-danger">*</span></label>

                                    <div class="d-flex gap-3 mt-2">

                                        @foreach([1, 2, 3, 4] as $optNum)

                                            <div class="form-check form-check-inline">

                                                <input

                                                    type="radio"

                                                    name="correctOption"

                                                    wire:model="correctOption"

                                                    value="{{ $optNum }}"

                                                    id="correctOption{{ $optNum }}"

                                                    class="form-check-input"

                                                >

                                                <label class="form-check-label fw-bold"
                                                       for="correctOption{{ $optNum }}">

                                                    گزینه {{ $optNum }}

                                                </label>

                                            </div>

                                        @endforeach

                                    </div>

                                    @error('correctOption')

                                    <div class="text-danger small mt-1">{{ $message }}</div>

                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Question Image Upload Card -->

            <div class="row mb-4">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-photo me-1"></i>

                                عکس سوال <span class="text-danger">*</span>

                            </h5>

                            <small class="text-muted">سایز پیشنهادی: 1200x800 پیکسل - حداکثر حجم: 5MB</small>

                        </div>

                        <div class="card-body">

                            <div
                                class="upload-area p-4 border-2 border-dashed rounded-3 text-center bg-light dark:bg-dark"

                                x-data="{ isDragging: false }"

                                x-on:dragover.prevent="isDragging = true"

                                x-on:dragleave.prevent="isDragging = false"

                                x-on:drop.prevent="isDragging = false"

                                :class="{ 'border-primary bg-primary-subtle': isDragging }">


                                @if($questionImage)

                                    <div class="position-relative d-inline-block">

                                        <img src="{{ $questionImage->temporaryUrl() }}"

                                             alt="پیش‌نمایش"

                                             class="img-fluid rounded shadow-sm"

                                             style="max-height: 400px;">

                                        <button type="button"

                                                wire:click="removeQuestionImage"

                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">

                                            <i class="ti ti-x"></i>

                                        </button>

                                    </div>

                                @elseif($existingQuestionImage)

                                    <div class="position-relative d-inline-block">

                                        <img src="{{ $existingQuestionImage }}"

                                             alt="عکس سوال"

                                             class="img-fluid rounded shadow-sm"

                                             style="max-height: 400px;">

                                        <span class="badge bg-success position-absolute top-0 start-0 m-2">

                                            عکس فعلی

                                        </span>

                                    </div>

                                    <p class="text-muted mt-3 mb-2">برای تغییر عکس، فایل جدید انتخاب کنید</p>

                                @else

                                    <div class="py-5">

                                        <i class="ti ti-cloud-upload text-muted" style="font-size: 4rem;"></i>

                                        <p class="text-muted mt-3 mb-2">عکس سوال را اینجا رها کنید یا کلیک کنید</p>

                                        <small class="text-muted">فرمت‌های مجاز: JPG, PNG, WebP</small>

                                    </div>

                                @endif


                                <input type="file"

                                       wire:model="questionImage"

                                       accept="image/*"

                                       class="position-absolute top-0 start-0 w-100 h-100 opacity-0"

                                       style="cursor: pointer;">

                            </div>


                            <div wire:loading wire:target="questionImage" class="text-center mt-3">

                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>

                                <span class="ms-2">در حال آپلود...</span>

                            </div>


                            @error('questionImage')

                            <div class="text-danger small mt-2">{{ $message }}</div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            <!-- Explanation Image Upload Card -->

            <div class="row mb-4">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">

                                <i class="ti ti-photo me-1"></i>

                                عکس پاسخ تشریحی (اختیاری)

                            </h5>

                            <small class="text-muted">سایز پیشنهادی: 1200x800 پیکسل - حداکثر حجم: 5MB</small>

                        </div>

                        <div class="card-body">

                            <div
                                class="upload-area p-4 border-2 border-dashed rounded-3 text-center bg-light dark:bg-dark"

                                x-data="{ isDragging: false }"

                                x-on:dragover.prevent="isDragging = true"

                                x-on:dragleave.prevent="isDragging = false"

                                x-on:drop.prevent="isDragging = false"

                                :class="{ 'border-primary bg-primary-subtle': isDragging }">


                                @if($explanationImage)

                                    <div class="position-relative d-inline-block">

                                        <img src="{{ $explanationImage->temporaryUrl() }}"

                                             alt="پیش‌نمایش"

                                             class="img-fluid rounded shadow-sm"

                                             style="max-height: 400px;">

                                        <button type="button"

                                                wire:click="removeExplanationImage"

                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">

                                            <i class="ti ti-x"></i>

                                        </button>

                                    </div>

                                @elseif($existingExplanationImage)

                                    <div class="position-relative d-inline-block">

                                        <img src="{{ $existingExplanationImage }}"

                                             alt="عکس پاسخ تشریحی"

                                             class="img-fluid rounded shadow-sm"

                                             style="max-height: 400px;">

                                        <span class="badge bg-success position-absolute top-0 start-0 m-2">

                                            عکس فعلی

                                        </span>

                                    </div>

                                    <p class="text-muted mt-3 mb-2">برای تغییر عکس، فایل جدید انتخاب کنید</p>

                                @else

                                    <div class="py-5">

                                        <i class="ti ti-cloud-upload text-muted" style="font-size: 4rem;"></i>

                                        <p class="text-muted mt-3 mb-2">عکس پاسخ تشریحی را اینجا رها کنید یا کلیک
                                            کنید</p>

                                        <small class="text-muted">فرمت‌های مجاز: JPG, PNG, WebP</small>

                                    </div>

                                @endif


                                <input type="file"

                                       wire:model="explanationImage"

                                       accept="image/*"

                                       class="position-absolute top-0 start-0 w-100 h-100 opacity-0"

                                       style="cursor: pointer;">

                            </div>


                            <div wire:loading wire:target="explanationImage" class="text-center mt-3">

                                <div class="spinner-border spinner-border-sm text-primary" role="status"></div>

                                <span class="ms-2">در حال آپلود...</span>

                            </div>


                            @error('explanationImage')

                            <div class="text-danger small mt-2">{{ $message }}</div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            <!-- Submit Button -->

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-body d-flex justify-content-end gap-2">

                            <a href="{{ route('manager.questions.index') }}" class="btn btn-secondary">

                                <i class="ti ti-x me-1"></i>

                                انصراف

                            </a>

                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">

                                <span wire:loading.remove wire:target="save">

                                    <i class="ti ti-device-floppy me-1"></i>

                                    {{ $isEditMode ? 'ذخیره تغییرات' : 'ذخیره سوال' }}

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

        </form>

    </div>


    @push('link')

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

        <style>

            .upload-area {

                position: relative;

                min-height: 200px;

                transition: all 0.3s ease;

            }

            .upload-area:hover {

                border-color: var(--bs-primary) !important;

            }

            .border-dashed {

                border-style: dashed !important;

            }

            .dark .upload-area {

                background-color: #1e293b !important;

            }

        </style>

    @endpush



    @push('script')

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>

            document.addEventListener('livewire:init', function () {

                initSelect2();

            });


            document.addEventListener('livewire:navigated', function () {

                initSelect2();

            });


            function initSelect2() {

                $('.select2-search').select2({

                    theme: 'bootstrap-5',

                    width: '100%',

                    allowClear: true,

                    dir: 'rtl',

                    language: {

                        noResults: function () {

                            return "نتیجه‌ای یافت نشد";

                        }

                    }

                });

            }

        </script>

    @endpush

</div>
