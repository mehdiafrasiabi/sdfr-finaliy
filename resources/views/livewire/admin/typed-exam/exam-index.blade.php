<div>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">آزمون‌های تایپی</h4>
                        <div class="d-flex gap-2">
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   class="form-control"
                                   placeholder="جستجوی عنوان آزمون..."
                                   style="width: 250px;">
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
                                        <span class="badge bg-success">{{ round($subject->average_score, 1) }}%</span>
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
                                        <span class="badge bg-danger">{{ round($subject->average_score, 1) }}%</span>
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

        <!-- Exams List -->
        <div class="row">
            <div class="col-12">
                @if($exams->isEmpty())
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ti ti-file-unknown text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">آزمونی یافت نشد</h5>
                        </div>
                    </div>
                @else
                    <div class="row">
                        @foreach($exams as $exam)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">{{ $exam->title }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            <span class="badge bg-{{ $exam->difficulty === 'easy' ? 'success' : ($exam->difficulty === 'medium' ? 'warning' : ($exam->difficulty === 'hard' ? 'danger' : 'primary')) }}">
                                                {{ $difficulties[$exam->difficulty] ?? $exam->difficulty }}
                                            </span>
                                            <span class="badge bg-info">{{ $exam->questions_count }} سوال</span>
                                            <span class="badge bg-secondary">{{ $exam->assignments_count }} اختصاص</span>
                                            <span class="badge bg-success">{{ $exam->completed_count }} تکمیل شده</span>
                                        </div>
                                        <p class="text-muted small mb-0">
                                            {{ $exam->academic_year }}
                                        </p>
                                    </div>
                                    <div class="card-footer d-flex gap-2">
                                        <a href="{{ route('admin.typed-exams.assignment', ['examId' => $exam->id]) }}"
                                           class="btn btn-primary btn-sm flex-grow-1">
                                            <i class="ti ti-users me-1"></i>
                                            اختصاص
                                        </a>
                                        <a href="{{ route('admin.typed-exams.stats', ['examId' => $exam->id]) }}"
                                           class="btn btn-outline-primary btn-sm flex-grow-1">
                                            <i class="ti ti-chart-bar me-1"></i>
                                            آمار
                                        </a>
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
