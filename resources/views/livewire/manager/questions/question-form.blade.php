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

                                @if(!$isEditMode)

                                    <small class="text-muted me-2">(برای تمام سوالات اعمال می‌شود)</small>

                                @endif

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


            @if($isEditMode)

                {{-- ==================== حالت ویرایش: یک سوال ====================  --}}

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

                                        <label class="form-label">میزان سختی <span class="text-danger">*</span></label>

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

                                                    <input type="radio" name="correctOption"

                                                           wire:model="correctOption" value="{{ $optNum }}"

                                                           id="correctOption{{ $optNum }}" class="form-check-input">

                                                    <label class="form-check-label fw-bold"

                                                           for="correctOption{{ $optNum }}">گزینه {{ $optNum }}</label>

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


                <!-- Question Image Upload Card (Edit) -->

                <div class="row mb-4">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-header">

                                <h5 class="card-title mb-0">

                                    <i class="ti ti-photo me-1"></i>

                                    عکس سوال <span class="text-danger">*</span>

                                </h5>

                                <small class="text-muted">عرض ثابت ۱۰۸۰ پیکسل — حداکثر حجم: ۱۰ مگابایت</small>

                            </div>

                            <div class="card-body">

                                <div class="upload-area p-4 border-2 border-dashed rounded-3 text-center bg-light"

                                     x-data="{ isDragging: false }"

                                     x-on:dragover.prevent="isDragging = true"

                                     x-on:dragleave.prevent="isDragging = false"

                                     x-on:drop.prevent="isDragging = false"

                                     :class="{ 'border-primary bg-primary-subtle': isDragging }">

                                    @if($questionImage)

                                        <div class="position-relative d-inline-block">

                                            <img src="{{ $questionImage->temporaryUrl() }}" alt="پیش‌نمایش"

                                                 class="img-fluid rounded shadow-sm" style="max-height: 400px;">

                                            <button type="button" wire:click="removeQuestionImage"

                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">

                                                <i class="ti ti-x"></i>

                                            </button>

                                        </div>

                                    @elseif($existingQuestionImage)

                                        <div class="position-relative d-inline-block">

                                            <img src="{{ $existingQuestionImage }}" alt="عکس سوال"

                                                 class="img-fluid rounded shadow-sm" style="max-height: 400px;">

                                            <span class="badge bg-success position-absolute top-0 start-0 m-2">عکس فعلی</span>

                                        </div>

                                        <p class="text-muted mt-3 mb-2">برای تغییر عکس، فایل جدید انتخاب کنید</p>

                                    @else

                                        <div class="py-5">

                                            <i class="ti ti-cloud-upload text-muted" style="font-size: 4rem;"></i>

                                            <p class="text-muted mt-3 mb-2">عکس سوال را اینجا رها کنید یا کلیک کنید</p>

                                            <small class="text-muted">فرمت‌های مجاز: JPG, PNG, WebP</small>

                                        </div>

                                    @endif

                                    <input type="file" wire:model="questionImage" accept="image/*"

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


                <!-- Explanation Image Upload Card (Edit) -->

                <div class="row mb-4">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-header">

                                <h5 class="card-title mb-0">

                                    <i class="ti ti-photo me-1"></i>

                                    عکس پاسخ تشریحی (اختیاری)

                                </h5>

                                <small class="text-muted">عرض ثابت ۱۰۸۰ پیکسل — حداکثر حجم: ۱۰ مگابایت</small>

                            </div>

                            <div class="card-body">

                                <div class="upload-area p-4 border-2 border-dashed rounded-3 text-center bg-light"

                                     x-data="{ isDragging: false }"

                                     x-on:dragover.prevent="isDragging = true"

                                     x-on:dragleave.prevent="isDragging = false"

                                     x-on:drop.prevent="isDragging = false"

                                     :class="{ 'border-primary bg-primary-subtle': isDragging }">

                                    @if($explanationImage)

                                        <div class="position-relative d-inline-block">

                                            <img src="{{ $explanationImage->temporaryUrl() }}" alt="پیش‌نمایش"

                                                 class="img-fluid rounded shadow-sm" style="max-height: 400px;">

                                            <button type="button" wire:click="removeExplanationImage"

                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">

                                                <i class="ti ti-x"></i>

                                            </button>

                                        </div>

                                    @elseif($existingExplanationImage)

                                        <div class="position-relative d-inline-block">

                                            <img src="{{ $existingExplanationImage }}" alt="عکس پاسخ تشریحی"

                                                 class="img-fluid rounded shadow-sm" style="max-height: 400px;">

                                            <span class="badge bg-success position-absolute top-0 start-0 m-2">عکس فعلی</span>

                                        </div>

                                        <p class="text-muted mt-3 mb-2">برای تغییر عکس، فایل جدید انتخاب کنید</p>

                                    @else

                                        <div class="py-5">

                                            <i class="ti ti-cloud-upload text-muted" style="font-size: 4rem;"></i>

                                            <p class="text-muted mt-3 mb-2">عکس پاسخ تشریحی را اینجا رها کنید یا کلیک کنید</p>

                                            <small class="text-muted">فرمت‌های مجاز: JPG, PNG, WebP</small>

                                        </div>

                                    @endif

                                    <input type="file" wire:model="explanationImage" accept="image/*"

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

            @else

                {{-- ==================== حالت ایجاد: چندین سوال ====================  --}}

                <!-- Question Count Selector -->

                <div class="row mb-4">

                    <div class="col-12">

                        <div class="card border-primary">

                            <div class="card-header bg-primary bg-opacity-10">

                                <h5 class="card-title mb-0 text-primary">

                                    <i class="ti ti-list-numbers me-1"></i>

                                    تعداد سوالات برای آپلود

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="row align-items-center g-3">

                                    <div class="col-md-4">

                                        <label class="form-label fw-bold">چند سوال می‌خواهید آپلود کنید؟</label>

                                        <div class="input-group" style="max-width: 220px;">

                                            <button type="button" class="btn btn-outline-secondary"

                                                    wire:click="updatedQuestionCount({{ max(1, $questionCount - 1) }})">

                                                <i class="ti ti-minus"></i>

                                            </button>

                                            <input type="number"

                                                   wire:model.live="questionCount"

                                                   min="1" max="20"

                                                   class="form-control text-center fw-bold fs-5"

                                                   style="max-width: 80px;">

                                            <button type="button" class="btn btn-outline-secondary"

                                                    wire:click="updatedQuestionCount({{ min(20, $questionCount + 1) }})">

                                                <i class="ti ti-plus"></i>

                                            </button>

                                        </div>

                                        <small class="text-muted">حداکثر ۲۰ سوال در یک بار</small>

                                    </div>

                                    <div class="col-md-8">

                                        <div class="alert alert-info mb-0 py-2">

                                            <i class="ti ti-info-circle me-1"></i>

                                            تمام سوالات زیر در همان دسته‌بندی انتخاب‌شده بالا ذخیره می‌شوند.

                                            هر سوال می‌تواند سختی و گزینه صحیح جداگانه داشته باشد.

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- حلقه سوالات --}}

                @for ($i = 0; $i < $questionCount; $i++)

                    <div class="row mb-2">

                        <div class="col-12">

                            <div class="card border-secondary">

                                <div class="card-header d-flex align-items-center justify-content-between"

                                     style="background: rgba(var(--bs-secondary-rgb), 0.08)">

                                    <h5 class="card-title mb-0">

                                        <span class="badge bg-primary me-2">سوال {{ $i + 1 }}</span>

                                        از {{ $questionCount }}

                                    </h5>

                                    @if(isset($questionImages[$i]))

                                        <span class="badge bg-success">

                                            <i class="ti ti-check me-1"></i> عکس آپلود شده

                                        </span>

                                    @endif

                                </div>

                                <div class="card-body">

                                    <div class="row g-3 mb-4">

                                        <!-- Difficulty per question -->

                                        <div class="col-md-6">

                                            <label class="form-label">میزان سختی <span class="text-danger">*</span></label>

                                            <select wire:model="questionDifficulties.{{ $i }}" class="form-select">

                                                @foreach($difficulties as $value => $label)

                                                    <option value="{{ $value }}">{{ $label }}</option>

                                                @endforeach

                                            </select>

                                            @error("questionDifficulties.$i")

                                            <div class="text-danger small mt-1">{{ $message }}</div>

                                            @enderror

                                        </div>


                                        <!-- Correct Option per question -->

                                        <div class="col-md-6">

                                            <label class="form-label">گزینه صحیح <span class="text-danger">*</span></label>

                                            <div class="d-flex gap-3 mt-2 flex-wrap">

                                                @foreach([1, 2, 3, 4] as $optNum)

                                                    <div class="form-check form-check-inline">

                                                        <input type="radio"

                                                               name="questionCorrectOptions_{{ $i }}"

                                                               wire:model="questionCorrectOptions.{{ $i }}"

                                                               value="{{ $optNum }}"

                                                               id="qco_{{ $i }}_{{ $optNum }}"

                                                               class="form-check-input">

                                                        <label class="form-check-label fw-bold"

                                                               for="qco_{{ $i }}_{{ $optNum }}">

                                                            گزینه {{ $optNum }}

                                                        </label>

                                                    </div>

                                                @endforeach

                                            </div>

                                            @error("questionCorrectOptions.$i")

                                            <div class="text-danger small mt-1">{{ $message }}</div>

                                            @enderror

                                        </div>

                                    </div>


                                    <div class="row g-3">

                                        <!-- Question Image per question -->

                                        <div class="col-md-6">

                                            <label class="form-label fw-semibold">

                                                <i class="ti ti-photo me-1 text-primary"></i>

                                                عکس سوال {{ $i + 1 }}

                                                <span class="text-danger">*</span>

                                            </label>

                                            <small class="d-block text-muted mb-2">

                                                عرض ثابت ۱۰۸۰ پیکسل — حداکثر ۱۰ مگابایت — فرمت: JPG, PNG, WebP

                                            </small>

                                            <div class="upload-area p-3 border-2 border-dashed rounded-3 text-center bg-light"

                                                 x-data="{ isDragging: false }"

                                                 x-on:dragover.prevent="isDragging = true"

                                                 x-on:dragleave.prevent="isDragging = false"

                                                 x-on:drop.prevent="isDragging = false"

                                                 :class="{ 'border-primary bg-primary-subtle': isDragging }"

                                                 style="min-height: 160px;">

                                                @if(isset($questionImages[$i]) && $questionImages[$i])

                                                    <div class="position-relative d-inline-block">

                                                        <img src="{{ $questionImages[$i]->temporaryUrl() }}"

                                                             alt="پیش‌نمایش سوال {{ $i + 1 }}"

                                                             class="img-fluid rounded shadow-sm"

                                                             style="max-height: 260px;">

                                                        <button type="button"

                                                                wire:click="removeQuestionImageAt({{ $i }})"

                                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">

                                                            <i class="ti ti-x"></i>

                                                        </button>

                                                    </div>

                                                @else

                                                    <div class="py-4">

                                                        <i class="ti ti-cloud-upload text-muted" style="font-size: 3rem;"></i>

                                                        <p class="text-muted mt-2 mb-1 small">عکس سوال را رها کنید یا کلیک کنید</p>

                                                    </div>

                                                @endif

                                                <input type="file"

                                                       wire:model="questionImages.{{ $i }}"

                                                       accept="image/*"

                                                       class="position-absolute top-0 start-0 w-100 h-100 opacity-0"

                                                       style="cursor: pointer;">

                                            </div>

                                            <div wire:loading wire:target="questionImages.{{ $i }}"

                                                 class="text-center mt-2">

                                                <div class="spinner-border spinner-border-sm text-primary"

                                                     role="status"></div>

                                                <span class="ms-2 small">در حال آپلود...</span>

                                            </div>

                                            @error("questionImages.$i")

                                            <div class="text-danger small mt-1">{{ $message }}</div>

                                            @enderror

                                        </div>


                                        <!-- Explanation Image per question -->

                                        <div class="col-md-6">

                                            <label class="form-label fw-semibold">

                                                <i class="ti ti-photo me-1 text-info"></i>

                                                عکس پاسخ تشریحی {{ $i + 1 }}

                                                <span class="text-muted">(اختیاری)</span>

                                            </label>

                                            <small class="d-block text-muted mb-2">

                                                عرض ثابت ۱۰۸۰ پیکسل — حداکثر ۱۰ مگابایت — فرمت: JPG, PNG, WebP

                                            </small>

                                            <div class="upload-area p-3 border-2 border-dashed rounded-3 text-center bg-light"

                                                 x-data="{ isDragging: false }"

                                                 x-on:dragover.prevent="isDragging = true"

                                                 x-on:dragleave.prevent="isDragging = false"

                                                 x-on:drop.prevent="isDragging = false"

                                                 :class="{ 'border-primary bg-primary-subtle': isDragging }"

                                                 style="min-height: 160px;">

                                                @if(isset($explanationImages[$i]) && $explanationImages[$i])

                                                    <div class="position-relative d-inline-block">

                                                        <img src="{{ $explanationImages[$i]->temporaryUrl() }}"

                                                             alt="پیش‌نمایش پاسخ {{ $i + 1 }}"

                                                             class="img-fluid rounded shadow-sm"

                                                             style="max-height: 260px;">

                                                        <button type="button"

                                                                wire:click="removeExplanationImageAt({{ $i }})"

                                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2">

                                                            <i class="ti ti-x"></i>

                                                        </button>

                                                    </div>

                                                @else

                                                    <div class="py-4">

                                                        <i class="ti ti-cloud-upload text-muted" style="font-size: 3rem;"></i>

                                                        <p class="text-muted mt-2 mb-1 small">عکس پاسخ تشریحی را رها کنید یا کلیک کنید</p>

                                                    </div>

                                                @endif

                                                <input type="file"

                                                       wire:model="explanationImages.{{ $i }}"

                                                       accept="image/*"

                                                       class="position-absolute top-0 start-0 w-100 h-100 opacity-0"

                                                       style="cursor: pointer;">

                                            </div>

                                            <div wire:loading wire:target="explanationImages.{{ $i }}"

                                                 class="text-center mt-2">

                                                <div class="spinner-border spinner-border-sm text-primary"

                                                     role="status"></div>

                                                <span class="ms-2 small">در حال آپلود...</span>

                                            </div>

                                            @error("explanationImages.$i")

                                            <div class="text-danger small mt-1">{{ $message }}</div>

                                            @enderror

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @endfor

            @endif


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

                                    @if($isEditMode)

                                        ذخیره تغییرات

                                    @elseif($questionCount > 1)

                                        ذخیره {{ $questionCount }} سوال

                                    @else

                                        ذخیره سوال

                                    @endif

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

                transition: all 0.3s ease;

            }

            .upload-area:hover {

                border-color: var(--bs-primary) !important;

            }

            .border-dashed {

                border-style: dashed !important;

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


            Livewire.hook('morph.updated', () => {

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
