<div>
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="card-title mb-0">آزمون‌های تشریحی (مبحثی)</h4>
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
