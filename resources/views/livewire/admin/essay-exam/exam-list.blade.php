<div class="student-ui student-ui-auto-collapse">
    @include('livewire.admin.student._styles')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="card-title mb-0">آزمون‌های تشریحی</h4>
                        <div class="d-flex gap-2 flex-wrap">
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   class="form-control"
                                   placeholder="جستجوی عنوان..."
                                   style="width: 250px;">
                            <a href="{{ route('admin.essay-exams.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>
                                ساخت آزمون
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Dashboard Section --}}
        @if(!empty($dashboardData))
            <div class="row g-3 mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100">
                        <div class="card-header">آمار کلی</div>
                        <div class="card-body">
                            <p>کل آزمون‌های برگزار شده: <span class="fw-bold">{{ $dashboardData['totalExamsHeld'] ?? 0 }}</span></p>
                            <p class="mb-0">میانگین درصد دانش‌آموزان: <span class="fw-bold text-primary">{{ $dashboardData['averagePercent'] ?? 0 }}%</span></p>
                        </div>
                    </div>
                </div>
                 <div class="col-lg-3 col-md-6">
                    <div class="card h-100">
                        <div class="card-header">مشارکت دانش‌آموزان</div>
                        <div class="card-body">
                            <p>آزمون‌های انجام شده: <span class="fw-bold text-success">{{ $dashboardData['completedAssignments'] ?? 0 }}</span></p>
                            <p class="mb-0">در انتظار انجام: <span class="fw-bold text-warning">{{ $dashboardData['pendingAssignments'] ?? 0 }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100">
                        <div class="card-header text-success">دروس قوی (بر اساس آزمون مباحث)</div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @forelse($dashboardData['strengths'] as $subject)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $subject->subject_name }}
                                        <span class="badge bg-success">{{ round($subject->average_percent, 1) }}%</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-muted">موردی یافت نشد.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card h-100">
                        <div class="card-header text-danger">دروس ضعیف (بر اساس آزمون مباحث)</div>
                        <div class="card-body">
                           <ul class="list-group list-group-flush">
                                @forelse($dashboardData['weaknesses'] as $subject)
                                     <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $subject->subject_name }}
                                        <span class="badge bg-danger">{{ round($subject->average_percent, 1) }}%</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-muted">موردی یافت نشد.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                @if($exams->isEmpty())
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ti ti-file-unknown text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">هیچ آزمون تشریحی ثبت نشده است</h5>
                            <a href="{{ route('admin.essay-exams.create') }}" class="btn btn-primary mt-3">
                                ساخت اولین آزمون
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row">
                        @foreach($exams as $exam)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-start">
                                        <h5 class="card-title mb-0">{{ $exam->title }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            <span class="badge bg-info">{{ $exam->questions_count }} سوال</span>
                                            <span class="badge bg-primary">نمره کل: {{ number_format($exam->total_score, 2) }}</span>
                                            <span class="badge bg-secondary">{{ $exam->assignments_count }} اختصاص</span>
                                        </div>
                                        @if($exam->topic)
                                            <p class="text-muted small mb-0">
                                                <i class="ti ti-bookmark me-1"></i>
                                                {{ $exam->topic->name }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="card-footer d-flex gap-2 flex-wrap">
                                        <a href="{{ route('admin.essay-exams.edit', ['examId' => $exam->id]) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="ti ti-edit"></i> ویرایش
                                        </a>
                                        <a href="{{ route('admin.essay-exams.assign', ['examId' => $exam->id]) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="ti ti-users me-1"></i>
                                            اختصاص
                                        </a>
                                        <a href="{{ route('admin.essay-exams.assignments', ['examId' => $exam->id]) }}"
                                           class="btn btn-outline-info btn-sm">
                                            <i class="ti ti-list"></i> پاسخ‌ها
                                        </a>
                                        <a href="{{ route('admin.essay-exams.answer-sheet', ['examId' => $exam->id]) }}"
                                           target="_blank"
                                           class="btn btn-outline-success btn-sm">
                                            <i class="ti ti-file-download"></i> پاسخ‌برگ
                                        </a>
                                        <button type="button"
                                                wire:click="deleteExam({{ $exam->id }})"
                                                wire:confirm="آیا مطمئن هستید؟"
                                                class="btn btn-outline-danger btn-sm">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $exams->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
