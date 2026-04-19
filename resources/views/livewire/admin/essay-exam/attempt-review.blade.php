<div x-data="{ zoom: 1, answerModal: false }">
    @push('link')
        <style>[x-cloak] { display: none !important; }</style>

    @endpush
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @php
            $exam = $attempt->assignment->essayExam;
            $student = $attempt->assignment->student;
        @endphp

        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-1">تصحیح آزمون: {{ $exam->title }}</h4>
                    <div class="text-muted small">دانش‌آموز: {{ $student?->user?->name ?? '—' }}</div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm" @click="zoom = Math.max(0.5, zoom - 0.1)">
                        <i class="ti ti-zoom-out"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" @click="zoom = Math.min(3, zoom + 0.1)">
                        <i class="ti ti-zoom-in"></i>
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" @click="answerModal = true">
                        <i class="ti ti-file-text me-1"></i> نمایش پاسخ‌نامه
                    </button>
                    <a href="{{ route('admin.essay-exams.assignments', ['examId' => $exam->id]) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ti ti-arrow-right me-1"></i> بازگشت
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Center: Student uploads -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">برگه‌های دانش‌آموز ({{ $attempt->uploads->count() }} فایل)</h5></div>
                    <div class="card-body" style="max-height: 80vh; overflow:auto;">
                        @forelse($attempt->uploads as $upload)
                            <div class="mb-3 text-center">
                                <img src="{{ $upload->url }}"
                                     :style="`transform: scale(${zoom}); transform-origin: top center; transition: transform 0.2s;`"
                                     style="max-width: 100%; border:1px solid #ddd; border-radius:6px;">
                                <div class="text-muted small mt-1">صفحه {{ $loop->iteration }}</div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4">هیچ فایلی آپلود نشده.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right: Grading -->
            <div class="col-lg-4 mb-4">
                <div class="card sticky-top" style="top: 80px;">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">تصحیح سوالات</h5>
                        <span class="badge bg-primary">نمره کل: {{ number_format($exam->total_score, 2) }}</span>
                    </div>
                    <div class="card-body" style="max-height: 60vh; overflow-y:auto;">
                        <div class="row g-2">
                            @foreach($exam->questions as $q)
                                <div class="col-12">
                                    <label class="form-label small mb-1">سوال {{ $q->question_number }} (از {{ $q->score }})</label>
                                    <input type="number"
                                           step="0.25"
                                           min="0"
                                           max="{{ $q->score }}"
                                           wire:model.live.debounce.400ms="scores.{{ $q->id }}"
                                           class="form-control form-control-sm">
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label small">پیام مشاور (اختیاری)</label>
                            <textarea wire:model.defer="consultant_message" rows="3" class="form-control form-control-sm"></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" wire:click="saveDraft" class="btn btn-outline-primary btn-sm flex-grow-1">
                                ذخیره موقت
                            </button>
                            <button type="button"
                                    wire:click="finalize"
                                    wire:confirm="آیا تصحیح نهایی شد؟ به دانش‌آموز اعلان ارسال می‌شود."
                                    class="btn btn-success btn-sm flex-grow-1">
                                تصحیح کامل
                            </button>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        @php $sum = array_sum($scores); @endphp
                        <span class="fw-bold">نمره داده شده: {{ number_format($sum, 2) }} / {{ number_format($exam->total_score, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Answer PDF Modal -->
    <div x-show="answerModal"
         x-cloak
         x-transition.opacity
         style="display:none; position:fixed; inset:0; z-index:1055; background:rgba(0,0,0,0.6);"
         @keydown.escape.window="answerModal = false">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">پاسخ‌نامه آزمون</h5>
                    <button type="button" class="btn-close" @click="answerModal = false"></button>
                </div>
                <div class="modal-body p-0">
                    @if($exam->answer_pdf_path)
                        <iframe src="{{ $exam->answerPdfUrl() }}"
                                style="width:100%; height:80vh; border:0;"></iframe>
                    @else
                        <div class="p-5 text-center text-muted">پاسخ‌نامه‌ای موجود نیست.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

