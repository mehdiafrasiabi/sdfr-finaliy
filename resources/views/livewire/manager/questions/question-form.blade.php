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

            <!-- Basic Info Card -->

            <div class="row mb-4">

                <div class="col-12">

                    <div class="card">

                        <div class="card-header">

                            <h5 class="card-title mb-0">اطلاعات پایه سوال</h5>

                        </div>

                        <div class="card-body">

                            <div class="row">

                                <!-- Subject Select2 -->

                                <div class="col-md-4 mb-3">

                                    <label class="form-label">درس <span class="text-danger">*</span></label>

                                    <div>

                                        <select id="subject_id" name="subject_id" wire:model="subject_id" class="form-select"
                                                data-placeholder="انتخاب درس...">

                                            <option value="">انتخاب درس...</option>

                                            @foreach($subjects as $subject)

                                                <option
                                                    value="{{ $subject->id }}" {{ $subject_id == $subject->id ? 'selected' : '' }}>

                                                    {{ $subject->name }}

                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                    @error('subject_id')

                                    <div class="text-danger small mt-1">{{ $message }}</div>

                                    @enderror

                                </div>


                                <!-- Difficulty -->

                                <div class="col-md-4 mb-3">

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


                                <!-- Direction -->

                                <div class="col-md-4 mb-3">

                                    <label class="form-label">جهت نمایش <span class="text-danger">*</span></label>

                                    <select wire:model="direction" class="form-select">

                                        @foreach($directions as $value => $label)

                                            <option value="{{ $value }}">{{ $label }}</option>

                                        @endforeach

                                    </select>

                                    @error('direction')

                                    <div class="text-danger small mt-1">{{ $message }}</div>

                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Question Body Card -->

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">متن سوال <span class="text-danger">*</span></h5>
                        </div>
                        <div class="card-body">
                            <textarea
                                name="body"
                                class="form-control"
                                rows="5"
                                wire:model="body"
                            >
                                 {{ $body }}
                            </textarea>

                            @error('body')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>


            <!-- Options Card -->

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">گزینه‌ها <span class="text-danger">*</span></h5>
                        </div>
                        <div class="card-body">
                            @error('options')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            @foreach([1, 2, 3, 4] as $optionNum)
                                <div
                                    class="option-box mb-4 p-3 border rounded {{ $options[$optionNum]['is_correct'] ? 'border-success bg-success-subtle' : '' }}">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">گزینه {{ $optionNum }}</h6>
                                        <div class="form-check">
                                            <input
                                                type="radio"
                                                name="correctOption"
                                                wire:model="correctOption"
                                                id="correctOption{{ $optionNum }}"
                                                class="form-check-input"
                                                {{ $options[$optionNum]['is_correct'] ? 'checked' : '' }}
                                                wire:click="setCorrectOption({{ $optionNum }})"
                                            >
                                            <label class="form-check-label" for="correctOption{{ $optionNum }}">
                                                این گزینه صحیح است
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                            <textarea
                                name="option{{ $optionNum }}"
                                class="form-control"
                                rows="3"
                                wire:model.defer="options.{{ $optionNum }}.content"
                            ></textarea>

                                    </div>
                                    @error("options.{$optionNum}.content")
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


            <!-- Explanation Card -->

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card-body">
                        <textarea
                            name="explanation"
                            class="form-control"
                            rows="4"
                            wire:model="explanation"
                        ></textarea>
                    </div>

                </div>
            </div>


            <!-- Submit Button -->

            <div class="row">

                <div class="col-12">

                    <div class="card">

                        <div class="card-body d-flex justify-content-end gap-2">

                            <button type="button" class="btn btn-secondary" wire:click="$refresh">

                                <i class="ti ti-refresh me-1"></i>

                                بازنشانی

                            </button>

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





</div>
