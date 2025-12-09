<div>
    <div>
        <div class="card">
            <div class="card-header">
                <h4>لیست آزمون‌ها</h4>
            </div>
            <div class="card-body">
                @if (session()->has('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" wire:model.live="search" class="form-control" placeholder="جستجو بر اساس عنوان...">
                    </div>

                    <div class="col-md-6 text-end">
                        <a href="{{route('manager.exam.form')}}" class="btn btn-outline-primary text-end">اضافه کردن آزمون جدید</a>
                        <a href="{{route('manager.exam.questions')}}" class="btn btn-outline-secondary text-end">آپلود سوال جدید</a>

                        <select wire:model.live="level" class="form-select w-auto d-inline-block">
                            <option value="">همه سطوح</option>
                            <option value="easy">آسان</option>
                            <option value="medium">متوسط</option>
                            <option value="hard">سخت</option>
                            <option value="comprehensive">جامع</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>پایه</th>
                            <th>رشته</th>
                            <th>عنوان</th>
                            <th>سطح</th>
                            <th>زمان شروع</th>
                            <th>زمان پایان</th>
                            <th>وضعیت</th>
                            <th>تصادفی‌سازی</th>
                            <th>خروجی PDF</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($exams as $exam)
                            <tr>
                                <td>   {{ $loop->iteration }}</td>
                                <td>{{ $exam->grade === 'tenth' ? 'دهم' : ($exam->grade === 'eleventh' ? 'یازدهم' : 'دوازدهم') }}</td>
                                <td>
                                    @switch($exam->major)
                                        @case('math') ریاضی @break
                                        @case('experimental') تجربی @break
                                        @case('humanities') انسانی @break
                                    @endswitch
                                </td>
                                <td>{{ $exam->title }}</td>
                                <td>
                                    @if($exam->level == 'easy')
                                        <div>آسان</div>
                                    @elseif($exam->level == 'medium')
                                        <div>متوسط</div>
                                    @elseif($exam->level == 'hard')
                                        <div>سخت</div>
                                    @elseif($exam->level == 'comprehensive')
                                        <div>جامع</div>
                                    @endif
                                </td>
                                <td>{{ verta($exam->start_time)->format('Y/m/d H:i') }}</td>
                                <td>{{ verta($exam->end_time)->format('Y/m/d H:i') }}</td>
                                <td>
                                    @if ($exam->is_active)
                                        <span class="badge bg-success">فعال</span>
                                    @else
                                        <span class="badge bg-danger">غیرفعال</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($exam->shuffle_mode)
                                        @case('questions') فقط سوالات @break
                                        @case('options') فقط گزینه‌ها @break
                                        @case('both') سوالات و گزینه‌ها @break
                                        @default خیر
                                    @endswitch
                                </td>
                                <td>
                                    <a href="{{ asset($exam->pdf_path) }}" class="btn btn-sm btn-outline-secondary" target="_blank">دانلود سوالات</a>
                                </td>
                                <td>
                                    <button wire:click="deleteExam({{ $exam->id }})" wire:confirm="آیا از حذف این آزمون مطمئن هستید؟" class="btn btn-sm btn-danger">حذف</button>
                                </td>
                            </tr>
                        @empty
                            <tr class="noresult" style="display: block;">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                                               trigger="loop"
                                               colors="primary:#121331,secondary:#08a88a"
                                               style="width:75px;height:75px"></lord-icon>
                                    <h5 class="mt-2">متاسفم! هیچ نتیجه ای یافت نشد</h5>

                                </div>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="noresult" style="display: none">
                        <div class="text-center">
                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                       colors="primary:#121331,secondary:#08a88a"
                                       style="width:75px;height:75px"></lord-icon>
                            <h5 class="mt-2">متاسفم! هیچ نتیجه ای یافت نشد</h5>
                            <p class="text-muted mb-0">ما همه دپارتمان را جستجو کرده ایم، هیچ
                                دپارتمان برای
                                جستجوی شما پیدا نکردیم.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    {{$exams->links('layouts.manager.pagination')}}
                </div>
            </div>
        </div>
    </div>
</div>
