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
