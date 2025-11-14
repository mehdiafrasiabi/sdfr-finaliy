<div>
    <form wire:submit.prevent="saveExam" class="card">
        <div class="card-header">
            <h4>ایجاد آزمون جدید</h4>
        </div>
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label">عنوان آزمون</label>
                    <input type="text" wire:model.live="title" id="title" class="form-control @error('title') is-invalid @enderror">
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="level" class="form-label">سطح آزمون</label>
                    <select wire:model="level" id="level" class="form-select @error('level') is-invalid @enderror">
                        <option value="easy">آسان</option>
                        <option value="medium">متوسط</option>
                        <option value="hard">سخت</option>
                        <option value="comprehensive">جامع</option>
                    </select>
                    @error('level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="pdf_file" class="form-label">فایل سوالات (PDF)</label>
                    <input type="file" wire:model="pdf_file" id="pdf_file" class="form-control @error('pdf_file') is-invalid @enderror">
                    @error('pdf_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if ($pdf_file)
                        <div class="mt-2">فایل سوالات انتخاب شد: {{ $pdf_file->getClientOriginalName() }}</div>
                    @endif
                </div>
                <div class="col-md-6 mb-3">
                    <label for="solution_pdf_file" class="form-label">فایل پاسخنامه تشریحی (اختیاری)</label>
                    <input type="file" wire:model="solution_pdf_file" id="solution_pdf_file" class="form-control @error('solution_pdf_file') is-invalid @enderror">
                    @error('solution_pdf_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if ($solution_pdf_file)
                        <div class="mt-2">فایل پاسخنامه انتخاب شد: {{ $solution_pdf_file->getClientOriginalName() }}</div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="duration_minutes" class="form-label">مدت آزمون (دقیقه)</label>
                    <input type="number" wire:model="duration_minutes" id="duration_minutes" class="form-control @error('duration_minutes') is-invalid @enderror" min="1">
                    @error('duration_minutes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="number_of_questions" class="form-label">تعداد سوالات</label>
                    <input type="number" wire:model.live="number_of_questions" id="number_of_questions" class="form-control @error('number_of_questions') is-invalid @enderror" min="1">
                    @error('number_of_questions') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>


            <hr>

            @if (count($examKeys) > 0)
                <div class="mb-3">
                    <h5 class="mt-4">کلید سوالات</h5>
                    <div class="row">
                        @foreach ($examKeys as $key)
                            <div class="col-md-3 mb-3">
                                <label for="key-{{ $key['question_number'] }}" class="form-label">سوال {{ $key['question_number'] }}</label>
                                <select wire:model="examKeys.{{ $key['question_number'] }}.correct_option" id="key-{{ $key['question_number'] }}" class="form-select @error('examKeys.' . $key['question_number'] . '.correct_option') is-invalid @enderror">
                                    <option value="">انتخاب کنید</option>
                                    <option value="1">گزینه ۱</option>
                                    <option value="2">گزینه ۲</option>
                                    <option value="3">گزینه ۳</option>
                                    <option value="4">گزینه ۴</option>
                                </select>
                                @error('examKeys.' . $key['question_number'] . '.correct_option') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer text-start">
            <a href="{{route('manager.exam.index')}}" class="btn btn-outline-danger">بازگشت</a>
            <button type="submit" class="btn btn-outline-primary">ایجاد آزمون</button>
        </div>
    </form>
</div>
