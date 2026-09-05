<div>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="card-title mb-1">نظارت بر آزمون‌های مشاوران</h4>
                            @if($selectedAdvisorId)
                                <p class="text-muted mb-0">
                                    آزمون‌های ساخته‌شده توسط: <strong>{{ $selectedAdvisorName }}</strong>
                                </p>
                            @else
                                <p class="text-muted mb-0">تعداد آزمون‌های ساخته‌شده توسط هر مشاور</p>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            @if($selectedAdvisorId)
                                <button type="button" class="btn btn-secondary" wire:click="backToOverview">
                                    <i class="ti ti-arrow-right me-1"></i>
                                    بازگشت به لیست مشاوران
                                </button>
                            @endif
                            <a href="{{ route('manager.typed-exams.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-list"></i>
                                همه آزمون‌ها
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(!$selectedAdvisorId)
            <!-- Advisor Search -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <input type="text" wire:model.live.debounce.300ms="advisorSearch" class="form-control"
                                   placeholder="جستجوی نام مشاور...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advisors List -->
            <div class="row">
                <div class="col-12">
                    @if($advisors->isEmpty())
                        <div class="card">
                            <div class="card-body text-center py-5 text-muted">
                                <i class="ti ti-users" style="font-size: 3rem;"></i>
                                <p class="mt-2">مشاوری یافت نشد</p>
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
                                            <th>مشاور</th>
                                            <th>موبایل</th>
                                            <th>تعداد آزمون ساخته‌شده</th>
                                            <th>تعداد منتشرشده</th>
                                            <th>عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($advisors as $index => $advisor)
                                            <tr>
                                                <td>{{ $advisors->firstItem() + $index }}</td>
                                                <td><strong>{{ $advisor->name }}</strong></td>
                                                <td>{{ $advisor->mobile }}</td>
                                                <td>
                                                    <span class="badge bg-primary fs-6">{{ $advisor->typed_exams_count }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success">{{ $advisor->published_exams_count }}</span>
                                                </td>
                                                <td>
                                                    @if($advisor->typed_exams_count > 0)
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                                wire:click="viewAdvisor({{ $advisor->id }})">
                                                            <i class="ti ti-eye"></i>
                                                            مشاهده آزمون‌ها
                                                        </button>
                                                    @else
                                                        <span class="text-muted small">هنوز آزمونی نساخته</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                {{ $advisors->links('layouts.manager.pagination') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Selected Advisor's Exams -->
            <div class="row">
                <div class="col-12">
                    @if($exams->isEmpty())
                        <div class="card">
                            <div class="card-body text-center py-5 text-muted">
                                <i class="ti ti-file-unknown" style="font-size: 3rem;"></i>
                                <p class="mt-2">این مشاور هنوز آزمونی نساخته است</p>
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
                                            <th>درجه سختی</th>
                                            <th>تعداد سوالات</th>
                                            <th>تعداد اختصاص‌ها</th>
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
                                                <td>
                                                    <span class="badge bg-{{ $exam->difficulty === 'easy' ? 'success' : ($exam->difficulty === 'medium' ? 'warning' : ($exam->difficulty === 'hard' ? 'danger' : 'primary')) }}">
                                                        {{ $difficulties[$exam->difficulty] ?? $exam->difficulty }}
                                                    </span>
                                                </td>
                                                <td>{{ $exam->questions_count }}</td>
                                                <td>{{ $exam->assignments_count }}</td>
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
                                                           title="مشاهده و بررسی سوالات">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        <button wire:click="togglePublish({{ $exam->id }})"
                                                                class="btn btn-sm btn-outline-{{ $exam->is_published ? 'warning' : 'success' }}"
                                                                title="{{ $exam->is_published ? 'عدم انتشار' : 'انتشار' }}">
                                                            <i class="ti ti-eye{{ $exam->is_published ? '-off' : '' }}"></i>
                                                        </button>
                                                        <button wire:click="deleteExam({{ $exam->id }})"
                                                                wire:confirm="آیا از حذف این آزمون اطمینان دارید؟"
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="حذف">
                                                            <i class="ti ti-trash"></i>
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
        @endif
    </div>
    @push('script')
        <script>
            Livewire.on('success', (message) => {
                Swal.fire({icon: 'success', title: 'موفق', text: message, confirmButtonText: 'باشه'});
            });
            Livewire.on('error', (message) => {
                Swal.fire({icon: 'error', title: 'خطا', text: message, confirmButtonText: 'باشه'});
            });
        </script>
    @endpush
</div>
