<div>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>آپلود سوال جدید</h4>
            <a href="{{ route('manager.exam.index') }}" class="btn btn-outline-secondary">بازگشت به لیست آزمون‌ها</a>
        </div>
        <form wire:submit.prevent="save" class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">پایه</label>
                    <select class="form-select @error('grade') is-invalid @enderror" wire:model="grade">
                        <option value="tenth">دهم</option>
                        <option value="eleventh">یازدهم</option>
                        <option value="twelfth">دوازدهم</option>
                    </select>
                    @error('grade') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">رشته</label>
                    <select class="form-select @error('major') is-invalid @enderror" wire:model="major">
                        <option value="math">ریاضی</option>
                        <option value="experimental">تجربی</option>
                        <option value="humanities">انسانی</option>
                    </select>
                    @error('major') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">سطح سوال</label>
                    <select class="form-select @error('level') is-invalid @enderror" wire:model="level">
                        <option value="easy">آسان</option>
                        <option value="medium">متوسط</option>
                        <option value="hard">سخت</option>
                        <option value="special">ویژه</option>
                    </select>
                    @error('level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mt-3">
                <label class="form-label">صورت سوال</label>
                <textarea class="form-control @error('question') is-invalid @enderror" rows="4" wire:model="question" placeholder="متن سوال را اینجا بنویسید..."></textarea>
                <small class="text-muted">تمام فیلدهای متنی به صورت ویرایشگر ساده آماده شده‌اند تا به راحتی متن سوال یا راهنما را وارد کنید.</small>
                @error('question') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label class="form-label">گزینه ۱</label>
                    <textarea class="form-control" rows="2" wire:model="options.option_one"></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">گزینه ۲</label>
                    <textarea class="form-control" rows="2" wire:model="options.option_two"></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">گزینه ۳</label>
                    <textarea class="form-control" rows="2" wire:model="options.option_three"></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">گزینه ۴</label>
                    <textarea class="form-control" rows="2" wire:model="options.option_four"></textarea>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label class="form-label">گزینه صحیح</label>
                    <select class="form-select @error('correct_option') is-invalid @enderror" wire:model="correct_option">
                        <option value="">انتخاب کنید</option>
                        <option value="1">گزینه ۱</option>
                        <option value="2">گزینه ۲</option>
                        <option value="3">گزینه ۳</option>
                        <option value="4">گزینه ۴</option>
                    </select>
                    @error('correct_option') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">پاسخنامه / توضیح</label>
                    <textarea class="form-control" rows="3" wire:model="answer_explanation" placeholder="توضیح، راه حل یا پاسخ تشریحی"></textarea>
                </div>
            </div>

            <div class="text-start mt-3">
                <button class="btn btn-outline-primary" type="submit">ثبت سوال</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">بانک سوالات</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>پایه</th>
                    <th>رشته</th>
                    <th>سطح</th>
                    <th>سوال</th>
                    <th>گزینه صحیح</th>
                </tr>
                </thead>
                <tbody>
                @forelse($questions as $question)
                    <tr>
                        <td>{{ $questions->firstItem() + $loop->index }}</td>
                        <td>{{ $question->grade === 'tenth' ? 'دهم' : ($question->grade === 'eleventh' ? 'یازدهم' : 'دوازدهم') }}</td>
                        <td>
                            @switch($question->major)
                                @case('math') ریاضی @break
                                @case('experimental') تجربی @break
                                @case('humanities') انسانی @break
                            @endswitch
                        </td>
                        <td>
                            @switch($question->level)
                                @case('easy') آسان @break
                                @case('medium') متوسط @break
                                @case('hard') سخت @break
                                @case('special') ویژه @break
                            @endswitch
                        </td>
                        <td class="text-truncate" style="max-width: 260px;">{{ \Illuminate\Support\Str::limit($question->question, 80) }}</td>
                        <td>{{ $question->correct_option ? 'گزینه '.$question->correct_option : '---' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">سوالی ثبت نشده است.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="mt-2">
                {{ $questions->links('layouts.manager.pagination') }}
            </div>
        </div>
    </div>
</div>
