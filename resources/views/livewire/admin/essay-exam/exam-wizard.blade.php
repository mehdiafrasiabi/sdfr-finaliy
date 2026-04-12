<div>
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ $examId ? 'ویرایش آزمون تشریحی' : 'ساخت آزمون تشریحی' }}</h4>
                <a href="{{ route('admin.essay-exams.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-right me-1"></i> بازگشت
                </a>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form wire:submit.prevent="save">
            <div class="row">
                <!-- Left: Form -->
                <div class="col-xl-6 col-lg-6 mb-4">
                    <div class="card mb-3">
                        <div class="card-header"><h5 class="mb-0">مرحله اول — اطلاعات آزمون</h5></div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">عنوان آزمون *</label>
                                <input type="text" wire:model.defer="title" class="form-control">
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">پایه</label>
                                    <select wire:model.live="cc_grade_id" class="form-select">
                                        <option value="">انتخاب کنید</option>
                                        @foreach($grades as $g)
                                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">رشته</label>
                                    <select wire:model.live="cc_field_id" class="form-select" @disabled(!$cc_grade_id)>
                                        <option value="">انتخاب کنید</option>
                                        @foreach($fields as $f)
                                            <option value="{{ $f->id }}">{{ $f->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">درس</label>
                                    <select wire:model.live="cc_subject_id" class="form-select" @disabled(!$cc_field_id)>
                                        <option value="">انتخاب کنید</option>
                                        @foreach($subjects as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">فصل</label>
                                    <select wire:model.live="cc_chapter_id" class="form-select" @disabled(!$cc_subject_id)>
                                        <option value="">انتخاب کنید</option>
                                        @foreach($chapters as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">مبحث *</label>
                                    <select wire:model.defer="cc_topic_id" class="form-select" @disabled(!$cc_chapter_id)>
                                        <option value="">انتخاب کنید</option>
                                        @foreach($topics as $t)
                                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">تعداد سوالات *</label>
                                    <input type="number" min="1" max="100"
                                           wire:model.live.debounce.400ms="total_questions"
                                           class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">نمره کل (محاسبه‌شده)</label>
                                    <input type="text" readonly value="{{ number_format($totalScorePreview, 2) }}" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">نمره هر سوال (مضرب 0.25)</label>
                                <div class="row g-2" style="max-height: 320px; overflow-y: auto;">
                                    @foreach($questions as $idx => $q)
                                        <div class="col-md-6">
                                            <div class="input-group input-group-sm mb-2">
                                                <span class="input-group-text">س{{ $idx + 1 }}</span>
                                                <select wire:model.live="questions.{{ $idx }}.score" class="form-select">
                                                    @foreach($allowedScores as $s)
                                                        <option value="{{ $s }}">{{ $s }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header"><h5 class="mb-0">فایل‌های آزمون</h5></div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">PDF سوالات * (حداکثر 20MB)</label>
                                <input type="file" wire:model="question_pdf" accept="application/pdf"
                                       class="form-control" id="question_pdf_input">
                                @if($question_pdf_path && !$question_pdf)
                                    <small class="text-muted d-block mt-1">فایل قبلی آپلود شده است.</small>
                                @endif
                                <div wire:loading wire:target="question_pdf" class="text-info small">در حال آپلود...</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">PDF پاسخنامه * (حداکثر 20MB)</label>
                                <input type="file" wire:model="answer_pdf" accept="application/pdf" class="form-control">
                                @if($answer_pdf_path && !$answer_pdf)
                                    <small class="text-muted d-block mt-1">فایل قبلی آپلود شده است.</small>
                                @endif
                                <div wire:loading wire:target="answer_pdf" class="text-info small">در حال آپلود...</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.essay-exams.index') }}" class="btn btn-outline-secondary">انصراف</a>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="save">ذخیره آزمون</span>
                            <span wire:loading wire:target="save">در حال ذخیره...</span>
                        </button>
                    </div>
                </div>

                <!-- Right: Live preview -->
                <div class="col-xl-6 col-lg-6 mb-4">
                    <div class="card sticky-top" style="top: 80px;">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">پیش‌نمایش پاسخ‌برگ</h5>
                            @if($examId)
                                <a target="_blank" href="{{ route('admin.essay-exams.answer-sheet', ['examId' => $examId]) }}"
                                   class="btn btn-outline-success btn-sm">
                                    <i class="ti ti-download me-1"></i> دانلود پاسخ‌برگ PDF
                                </a>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($previewPdfUrl)
                                <div class="mb-3">
                                    <label class="form-label small text-muted">PDF سوالات (ذخیره‌شده)</label>
                                    <iframe src="{{ $previewPdfUrl }}"
                                            style="width:100%; height:400px; border:1px solid var(--bs-border-color); border-radius:8px;"></iframe>
                                </div>
                            @endif
                                {{-- پیش‌نمایش فایل جدید انتخاب‌شده (قبل از ذخیره) --}}
                                <div id="pdf-new-preview" class="mb-3" style="display:none;">
                                    <label class="form-label small text-muted">PDF سوالات (انتخاب‌شده — هنوز ذخیره نشده)</label>
                                    <iframe id="pdf-new-frame"
                                            style="width:100%; height:400px; border:1px solid var(--bs-border-color); border-radius:8px;"></iframe>
                                </div>

                            <div class="border rounded p-3" style="background:#fff; color:#000;" dir="rtl">
                                <div class="text-center mb-3">
                                    <div style="font-weight:bold; font-size:18px;">SDFR</div>
                                    <div style="font-size:13px;">پاسخ‌برگ آزمون: {{ $title ?: '---' }}</div>
                                </div>
                                @foreach($questions as $idx => $q)
                                    <div class="d-flex align-items-stretch mb-2 border"
                                         style="border-color:#ccc !important;">
                                        <div class="px-2 py-1 text-center"
                                             style="min-width:50px; background:#f1f1f1; font-weight:bold; border-left:1px solid #ccc;">
                                            {{ $idx + 1 }}
                                        </div>
                                        <div class="flex-grow-1"
                                             style="min-height: {{ (int)($q['row_height'] ?? 110) }}px; background: repeating-linear-gradient(to bottom, transparent, transparent 24px, #ddd 25px);"></div>
                                        <div class="px-2 py-1 text-center"
                                             style="min-width:70px; background:#f1f1f1; border-right:1px solid #ccc;">
                                            <div style="font-size:11px; color:#666;">نمره</div>
                                            <div style="font-weight:bold;">{{ $q['score'] ?? 0 }}</div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <button type="button" class="btn btn-sm btn-light py-0 px-1"
                                                    wire:click.prevent="setRowHeight({{ $idx }}, {{ (int)($q['row_height'] ?? 110) + 20 }})"
                                                    title="افزایش ارتفاع">+</button>
                                            <button type="button" class="btn btn-sm btn-light py-0 px-1"
                                                    wire:click.prevent="setRowHeight({{ $idx }}, {{ (int)($q['row_height'] ?? 110) - 20 }})"
                                                    title="کاهش ارتفاع">−</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @push('script')
        <script>
            document.addEventListener('livewire:initialized', () => {
                document.getElementById('question_pdf_input')?.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    const url = URL.createObjectURL(file);
                    document.getElementById('pdf-new-frame').src = url;
                    document.getElementById('pdf-new-preview').style.display = 'block';
                });
            });
        </script>
    @endpush
</div>
