<div>

    <div class="container-fluid">

        <!-- Page Header -->

        <div class="row mb-4">

            <div class="col-12">

                <div class="card">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <h4 class="card-title mb-0">لیست آزمون‌های تایپی</h4>

                        <a href="{{ route('manager.typed-exams.form') }}" class="btn btn-primary">

                            <i class="ti ti-plus me-1"></i>

                            ایجاد آزمون جدید

                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- Filters -->

        <div class="row mb-4">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">

                        <h5 class="card-title mb-0"><i class="ti ti-filter me-1"></i> فیلترها</h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">جستجوی عنوان</label>

                                <input type="text" wire:model.live.debounce.300ms="filterTitle" class="form-control"
                                       placeholder="عنوان آزمون...">

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">دوره زمانی</label>

                                <select wire:model.live="filterAcademicYear" class="form-select">

                                    <option value="">همه</option>

                                    @foreach($academicYears as $value => $label)

                                        <option value="{{ $value }}">{{ $label }}</option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">ترتیب</label>

                                <select wire:model.live="sortOrder" class="form-select">

                                    <option value="desc">جدیدترین</option>

                                    <option value="asc">قدیمی‌ترین</option>

                                </select>

                            </div>

                            <div class="col-md-2 d-flex align-items-end">

                                <button wire:click="clearFilters" class="btn btn-outline-secondary w-100">

                                    <i class="ti ti-x me-1"></i>

                                    پاک کردن

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Exams List -->

        <div class="row">

            <div class="col-12">

                @if($exams->isEmpty())

                    <div class="card">

                        <div class="card-body text-center py-5">

                            <i class="ti ti-file-unknown text-muted" style="font-size: 4rem;"></i>

                            <h5 class="mt-3 text-muted">آزمونی یافت نشد</h5>

                            <a href="{{ route('manager.typed-exams.form') }}" class="btn btn-primary mt-3">

                                <i class="ti ti-plus me-1"></i>

                                ایجاد اولین آزمون

                            </a>

                        </div>

                    </div>

                @else

                    <div class="card">

                        <div class="card-body p-0">

                            <div class="table-responsive">

                                <table class="table table-hover mb-0">

                                    <thead class="table-light">

                                    <tr>

                                        <th>#</th>

                                        <th>عنوان آزمون</th>

                                        <th>دوره زمانی</th>

                                        <th>درجه سختی</th>

                                        <th>تعداد سوالات</th>

                                        <th>وضعیت</th>

                                        <th>تاریخ ایجاد</th>

                                        <th>عملیات</th>

                                    </tr>

                                    </thead>

                                    <tbody>

                                    @foreach($exams as $index => $exam)

                                        <tr>

                                            <td>{{ $exams->firstItem() + $index }}</td>

                                            <td>

                                                <strong>{{ $exam->title }}</strong>

                                                @if($exam->is_random_selection)

                                                    <span class="badge bg-info-subtle text-info ms-1">تصادفی</span>

                                                @endif

                                            </td>

                                            <td>{{ $academicYears[$exam->academic_year] ?? $exam->academic_year }}</td>

                                            <td>

                                                    <span
                                                        class="badge bg-{{ $exam->difficulty === 'easy' ? 'success' : ($exam->difficulty === 'medium' ? 'warning' : ($exam->difficulty === 'hard' ? 'danger' : 'primary')) }}">

                                                        {{ $difficulties[$exam->difficulty] ?? $exam->difficulty }}

                                                    </span>

                                            </td>

                                            <td>{{ $exam->questions_count }}</td>

                                            <td>

                                                @if($exam->is_published)

                                                    <span class="badge bg-success">منتشر شده</span>

                                                @else

                                                    <span class="badge bg-secondary">پیش‌نویس</span>

                                                @endif

                                            </td>

                                            <td>{{ verta($exam->created_at)->format('Y/m/d') }}</td>

                                            <td>

                                                <div class="btn-group">

                                                    <a href="{{ route('manager.typed-exams.form', ['id' => $exam->id]) }}"

                                                       class="btn btn-sm btn-outline-primary"

                                                       title="ویرایش">

                                                        <i class=" ri-pencil-line"></i>
                                                                ویرایش
                                                    </a>

                                                    <button wire:click="togglePublish({{ $exam->id }})"

                                                            class="btn btn-sm btn-outline-{{ $exam->is_published ? 'warning' : 'success' }}"

                                                            title="{{ $exam->is_published ? 'عدم انتشار' : 'انتشار' }}">

                                                        <i class="ri-eye-{{ $exam->is_published ? 'off-line' : 'line' }}"></i>
                                                        {{ $exam->is_published ? 'عدم انتشار' : 'انتشار' }}
                                                    </button>

                                                    <button wire:click="deleteExam({{ $exam->id }})"

                                                            wire:confirm="آیا از حذف این آزمون اطمینان دارید؟"

                                                            class="btn btn-sm btn-outline-danger"

                                                            title="حذف">

                                                        <i class=" ri-delete-bin-6-line"></i>
                                                        حذف
                                                    </button>

                                                </div>

                                            </td>

                                        </tr>

                                    @endforeach

                                    </tbody>

                                </table>

                            </div>

                        </div>

                        <div class="card-footer">

                            {{ $exams->links('layouts.manager.pagination') }}

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>
