<div>
    @push('link')
        <style>
            /* ───────────── لایت مود (پیش‌فرض) ───────────── */
            :root {
                --matte-blue: #4A90E2;
                --matte-blue-hover: #357ABD;

                --bg-body: #F8FAFC;
                --bg-card: #ffffff;
                --bg-input: #F8FAFC;
                --bg-hover: #f1f5f9;

                --border-color: #E2E8F0;

                --text-main: #334155;
                --text-muted: #64748B;
                --text-inverse: #ffffff;

                --shadow-soft: 0 4px 24px rgba(0, 0, 0, 0.04);
                --shadow-hover: 0 8px 32px rgba(0, 0, 0, 0.08);

                /* رنگ‌های شبیه‌ساز کاغذ */
                --paper-bg: #ffffff;
                --paper-line: #e2e8f0;
                --paper-accent: #f8fafc;

                --radius-lg: 16px;
                --radius-md: 12px;
                --transition-smooth: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* ───────────── دارک مود (مود تیره و سینمایی) ───────────── */
            [data-theme="dark"],
            [data-bs-theme="dark"],
            .dark-mode {
                --matte-blue: #3b82f6;
                --matte-blue-hover: #60a5fa;

                --bg-body: #0b0f19; /* پس‌زمینه تیره و عمیق */
                --bg-card: #131a2b; /* کارت‌های سینمایی */
                --bg-input: #1a233a;
                --bg-hover: #1e293b;

                --border-color: rgba(255, 255, 255, 0.08);

                --text-main: #fdfbf7; /* رنگ متن کرم ملایم (Warm Cream) */
                --text-muted: #94a3b8;
                --text-inverse: #ffffff;

                --shadow-soft: 0 4px 24px rgba(0, 0, 0, 0.3);
                --shadow-hover: 0 12px 40px rgba(0, 0, 0, 0.5);

                /* رنگ‌های شبیه‌ساز کاغذ در دارک مود (جلوگیری از خیرگی چشم) */
                --paper-bg: #1e293b;
                --paper-line: #334155;
                --paper-accent: #0f172a;
            }

            /* ───────────── استایل‌های اختصاصی ───────────── */
            .exam-builder-wrapper {
                font-family: inherit;
                color: var(--text-main);
                background-color: var(--bg-body);
                padding: 1.5rem;
                border-radius: var(--radius-lg);
                transition: background-color 0.3s ease;
            }

            /* کارت‌ها */
            .modern-card {
                background-color: var(--bg-card);
                border: 1px solid var(--border-color);
                border-radius: var(--radius-lg);
                box-shadow: var(--shadow-soft);
                margin-bottom: 1.5rem;
                overflow: hidden;
                transition: var(--transition-smooth);
            }
            .modern-card:hover {
                box-shadow: var(--shadow-hover);
            }
            .modern-card-header {
                background-color: transparent;
                border-bottom: 1px solid var(--border-color);
                padding: 1.25rem 1.5rem;
                font-weight: 700;
                color: var(--matte-blue);
            }

            /* فرم‌ها */
            .modern-input, .modern-select {
                background-color: var(--bg-input);
                border: 1px solid var(--border-color);
                border-radius: var(--radius-md);
                padding: 0.6rem 1rem;
                color: var(--text-main);
                transition: var(--transition-smooth);
            }
            .modern-input:focus, .modern-select:focus {
                background-color: var(--bg-card);
                border-color: var(--matte-blue);
                box-shadow: 0 0 0 4px rgba(74, 144, 226, 0.15);
                color: var(--text-main);
                outline: none;
            }

            /* رفع مشکل رنگ آیتم‌های select در دارک مود */
            .modern-select option {
                background-color: var(--bg-card);
                color: var(--text-main);
            }

            .form-label {
                font-size: 0.875rem;
                font-weight: 600;
                color: var(--text-muted);
                margin-bottom: 0.5rem;
            }

            /* دکمه‌ها */
            .btn-matte-blue {
                background-color: var(--matte-blue);
                color: var(--text-inverse);
                border: none;
                border-radius: var(--radius-md);
                padding: 0.6rem 1.5rem;
                font-weight: 600;
                transition: var(--transition-smooth);
            }
            .btn-matte-blue:hover {
                background-color: var(--matte-blue-hover);
                color: var(--text-inverse);
                transform: translateY(-2px);
            }
            .btn-outline-soft {
                border: 1px solid var(--border-color);
                color: var(--text-main);
                background-color: transparent;
                border-radius: var(--radius-md);
                transition: var(--transition-smooth);
            }
            .btn-outline-soft:hover {
                background-color: var(--bg-hover);
                border-color: var(--text-muted);
                color: var(--text-main);
            }

            /* لیست نمرات (اسکرول نرم) */
            .score-list-container {
                max-height: 280px;
                overflow-y: auto;
                padding-right: 5px;
                background-color: var(--bg-input) !important;
                border-color: var(--border-color) !important;
            }
            .score-list-container::-webkit-scrollbar { width: 6px; }
            .score-list-container::-webkit-scrollbar-track { background: transparent; }
            .score-list-container::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 10px; }
            .score-list-container::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

            .score-input-wrapper {
                background-color: var(--bg-card);
                border: 1px solid var(--border-color);
                border-radius: var(--radius-md);
                overflow: hidden;
            }
            .score-input-wrapper .input-group-text {
                background-color: var(--bg-card);
                color: var(--text-muted);
                border: none;
                border-left: 1px solid var(--border-color); /* جداکننده راست‌چین */
            }
            .score-input-wrapper select {
                background-color: var(--bg-card);
                color: var(--text-main);
                border: none;
            }

            /* پاسخ‌برگ ساز (داینامیک برای لایت و دارک) */
            .answer-sheet-preview {
                background-color: var(--paper-bg);
                border: 1px solid var(--border-color);
                border-radius: var(--radius-md);
                box-shadow: var(--shadow-soft);
                overflow: hidden;
            }
            .answer-sheet-header {
                background-color: var(--matte-blue);
                color: #ffffff; /* هدر پاسخ‌برگ همیشه سفید بماند بهتر است */
                padding: 1.5rem;
                text-align: center;
            }
            .answer-row {
                border-bottom: 1px dashed var(--border-color);
                transition: var(--transition-smooth);
            }
            .answer-row:last-child { border-bottom: none; }
            .answer-row:hover { background-color: var(--bg-hover); }

            .answer-num-box {
                background-color: var(--paper-accent);
                color: var(--matte-blue);
                font-weight: 700;
                min-width: 50px;
                border-left: 1px solid var(--border-color);
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .answer-score-box {
                background-color: var(--paper-accent);
                min-width: 70px;
                border-right: 1px solid var(--border-color);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }
            .lines-bg {
                background-image: repeating-linear-gradient(to bottom, transparent, transparent 24px, var(--paper-line) 25px);
            }
            .height-controls {
                background-color: var(--bg-input);
                border-right: 1px solid var(--border-color);
            }
            .height-controls button {
                border-radius: 6px;
                color: var(--text-muted);
                background: transparent;
                transition: var(--transition-smooth);
            }
            .height-controls button:hover {
                background-color: var(--matte-blue);
                color: #ffffff;
            }

            /* فیکس کردن رنگ متن آلارم و آیکون‌ها در دارک مود */
            .text-primary-adaptive { color: var(--matte-blue) !important; }
        </style>
    @endpush
    <div class="exam-builder-wrapper" dir="rtl">
        <div class="container-fluid p-0">
            {{-- ───────────── هدر ───────────── --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 fw-bold">
                    <i class="ti ti-file-pencil text-primary-adaptive me-2"></i>
                    {{ $examId ? 'ویرایش آزمون تشریحی' : 'ساخت آزمون تشریحی' }}
                </h4>
                <a href="{{ route('admin.essay-exams.index') }}" class="btn btn-outline-soft btn-sm px-3 py-2">
                    <i class="ti ti-arrow-right ms-1"></i> بازگشت به لیست
                </a>
            </div>

            {{-- ───────────── هشدارها ───────────── --}}
            @if($errors->any())
                <div class="alert alert-danger modern-card mb-4" style="border-right: 4px solid #dc3545; border-radius: var(--radius-md); background: rgba(220, 53, 69, 0.1); color: #e74c3c;">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form wire:submit.prevent="save">
                <div class="row g-4">

                    {{-- ───────────── ستون سمت راست: فرم اطلاعات ───────────── --}}
                    <div class="col-xl-6 col-lg-6">
                        <!-- مرحله اول -->
                        <div class="modern-card">
                            <div class="modern-card-header d-flex align-items-center">
                                <span class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center ms-2" style="width: 24px; height: 24px; font-size: 12px; background-color: var(--matte-blue) !important;">۱</span>
                                اطلاعات پایه آزمون
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <label class="form-label">عنوان آزمون <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.defer="title" class="form-control modern-input" placeholder="مثال: آزمون میان‌ترم ریاضیات">
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">پایه</label>
                                        <select wire:model.live="cc_grade_id" class="form-select modern-select">
                                            <option value="">انتخاب کنید</option>
                                            @foreach($grades as $g)
                                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">رشته</label>
                                        <select wire:model.live="cc_field_id" class="form-select modern-select" @disabled(!$cc_grade_id)>
                                            <option value="">انتخاب کنید</option>
                                            @foreach($fields as $f)
                                                <option value="{{ $f->id }}">{{ $f->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">درس</label>
                                        <select wire:model.live="cc_subject_id" class="form-select modern-select" @disabled(!$cc_field_id)>
                                            <option value="">انتخاب کنید</option>
                                            @foreach($subjects as $s)
                                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">فصل</label>
                                        <select wire:model.live="cc_chapter_id" class="form-select modern-select" @disabled(!$cc_subject_id)>
                                            <option value="">انتخاب کنید</option>
                                            @foreach($chapters as $c)
                                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">مبحث <span class="text-danger">*</span></label>
                                        <select wire:model.defer="cc_topic_id" class="form-select modern-select" @disabled(!$cc_chapter_id)>
                                            <option value="">انتخاب کنید</option>
                                            @foreach($topics as $t)
                                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <hr class="my-4" style="border-color: var(--border-color);">

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">تعداد سوالات <span class="text-danger">*</span></label>
                                        <input type="number" min="1" max="100" wire:model.live.debounce.400ms="total_questions" class="form-control modern-input text-center" dir="ltr">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-primary-adaptive">نمره کل (محاسبه‌شده)</label>
                                        <div class="form-control modern-input text-center fw-bold text-primary-adaptive" style="background-color: var(--bg-hover);" readonly>
                                            {{ number_format($totalScorePreview, 2) }}
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label">بارم‌بندی سوالات (مضرب ۰.۲۵)</label>
                                    <div class="score-list-container border rounded p-3">
                                        <div class="row g-3">
                                            @foreach($questions as $idx => $q)
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="input-group input-group-sm score-input-wrapper shadow-sm">
                                                        <span class="input-group-text" style="width: 45px; justify-content: center;">س {{ $idx + 1 }}</span>
                                                        <select wire:model.live="questions.{{ $idx }}.score" class="form-select form-control" style="cursor: pointer; box-shadow: none;">
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
                        </div>

                        <!-- مرحله دوم: فایل‌ها -->
                        <div class="modern-card">
                            <div class="modern-card-header d-flex align-items-center">
                                <span class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center ms-2" style="width: 24px; height: 24px; font-size: 12px; background-color: var(--matte-blue) !important;">۲</span>
                                فایل‌های ضمیمه
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <label class="form-label">فایل PDF سوالات <span class="text-danger">*</span> <span class="text-muted fw-normal">(حداکثر 20MB)</span></label>
                                    <input type="file" wire:model="question_pdf" accept="application/pdf" class="form-control modern-input" id="question_pdf_input">

                                    @if($question_pdf_path && !$question_pdf)
                                        <div class="mt-2 text-success small d-flex align-items-center">
                                            <i class="ti ti-check me-1"></i> فایل قبلی با موفقیت آپلود شده است.
                                        </div>
                                    @endif
                                    <div wire:loading wire:target="question_pdf" class="text-primary-adaptive small mt-2 d-flex align-items-center">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span> در حال آپلود...
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label">فایل PDF پاسخنامه <span class="text-danger">*</span> <span class="text-muted fw-normal">(حداکثر 20MB)</span></label>
                                    <input type="file" wire:model="answer_pdf" accept="application/pdf" class="form-control modern-input">

                                    @if($answer_pdf_path && !$answer_pdf)
                                        <div class="mt-2 text-success small d-flex align-items-center">
                                            <i class="ti ti-check me-1"></i> فایل پاسخنامه قبلی آپلود شده است.
                                        </div>
                                    @endif
                                    <div wire:loading wire:target="answer_pdf" class="text-primary-adaptive small mt-2 d-flex align-items-center">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span> در حال آپلود...
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- دکمه‌های عملیات -->
                        <div class="d-flex justify-content-end gap-3 mt-4 mb-5 mb-lg-0">
                            <a href="{{ route('admin.essay-exams.index') }}" class="btn btn-outline-soft px-4 py-2">انصراف</a>
                            <button type="submit" class="btn btn-matte-blue px-5 py-2 shadow-sm d-flex align-items-center">
                                <span wire:loading.remove wire:target="save">ذخیره آزمون</span>
                                <span wire:loading wire:target="save" class="d-flex align-items-center">
                                <span class="spinner-border spinner-border-sm ms-2" role="status"></span> در حال پردازش...
                            </span>
                            </button>
                        </div>
                    </div>

                    {{-- ───────────── ستون سمت چپ: پیش‌نمایش زنده ───────────── --}}
                    <div class="col-xl-6 col-lg-6">
                        <div class="modern-card sticky-top" style="top: 20px; z-index: 10;">
                            <div class="modern-card-header d-flex justify-content-between align-items-center" style="background-color: var(--bg-hover);">
                                <div class="d-flex align-items-center text-main">
                                    <i class="ti ti-eye ms-2 text-muted fs-5"></i> پیش‌نمایش زنده پاسخ‌برگ
                                </div>
                                @if($examId)
                                    <a target="_blank" href="{{ route('admin.essay-exams.answer-sheet', ['examId' => $examId]) }}" class="btn btn-sm btn-outline-soft d-flex align-items-center rounded-pill px-3 text-primary-adaptive" style="border-color: var(--matte-blue);">
                                        <i class="ti ti-download ms-1"></i> دانلود PDF
                                    </a>
                                @endif
                            </div>
                            <div class="card-body p-4">

                                {{-- پیش‌نمایش PDF --}}
                                @if($previewPdfUrl)
                                    <div class="mb-4">
                                        <label class="form-label d-flex align-items-center"><i class="ti ti-file-text ms-1"></i> پیش‌نمایش سوالات (ذخیره‌شده)</label>
                                        <iframe src="{{ $previewPdfUrl }}" style="width:100%; height:300px; border:1px solid var(--border-color); border-radius:var(--radius-md); box-shadow:var(--shadow-soft);"></iframe>
                                    </div>
                                @endif
                                <div id="pdf-new-preview" class="mb-4" style="display:none;">
                                    <label class="form-label text-primary-adaptive d-flex align-items-center"><i class="ti ti-file-upload ms-1"></i> پیش‌نمایش فایل جدید (هنوز ذخیره نشده)</label>
                                    <iframe id="pdf-new-frame" style="width:100%; height:300px; border:1px solid var(--border-color); border-radius:var(--radius-md); box-shadow:var(--shadow-soft);"></iframe>
                                </div>

                                {{-- شبیه‌ساز پاسخ‌برگ SDFR --}}
                                <label class="form-label mb-3 d-flex align-items-center"><i class="ti ti-layout-board ms-1"></i> ساختار برگه پاسخ‌نامه</label>
                                <div class="answer-sheet-preview">
                                    <!-- هدر پاسخ‌برگ -->
                                    <div class="answer-sheet-header">
                                        <div class="fs-4 fw-bolder mb-1" style="letter-spacing: 2px;">SDFR</div>
                                        <div class="fs-6 opacity-75">پاسخ‌برگ آزمون: {{ $title ?: 'بدون عنوان' }}</div>
                                    </div>

                                    <!-- لیست سوالات در پاسخ‌برگ -->
                                    <div>
                                        @foreach($questions as $idx => $q)
                                            <div class="d-flex align-items-stretch answer-row">
                                                <div class="answer-num-box fs-5">
                                                    {{ $idx + 1 }}
                                                </div>
                                                <div class="flex-grow-1 lines-bg" style="min-height: {{ (int)($q['row_height'] ?? 110) }}px;">
                                                    <!-- فضای نوشتن پاسخ -->
                                                </div>
                                                <div class="answer-score-box">
                                                    <div style="font-size: 10px; color: var(--text-muted); margin-bottom: 2px;">نمره</div>
                                                    <div class="fw-bold fs-6 text-primary-adaptive">{{ $q['score'] ?? 0 }}</div>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center p-1 height-controls">
                                                    <button type="button" class="btn btn-sm py-1 px-2 border-0 mb-1" wire:click.prevent="setRowHeight({{ $idx }}, {{ (int)($q['row_height'] ?? 110) + 20 }})" title="افزایش فضای پاسخ">
                                                        <i class="ti ti-plus"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm py-1 px-2 border-0 mt-1" wire:click.prevent="setRowHeight({{ $idx }}, {{ (int)($q['row_height'] ?? 110) - 20 }})" title="کاهش فضای پاسخ">
                                                        <i class="ti ti-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
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
                    const pdfInput = document.getElementById('question_pdf_input');
                    if(pdfInput) {
                        pdfInput.addEventListener('change', function(e) {
                            const file = e.target.files[0];
                            if (!file) return;
                            const url = URL.createObjectURL(file);
                            const frame = document.getElementById('pdf-new-frame');
                            const previewWrapper = document.getElementById('pdf-new-preview');

                            frame.src = url;
                            // انیمیشن نرم برای نمایش پیش‌نمایش
                            previewWrapper.style.opacity = '0';
                            previewWrapper.style.display = 'block';
                            setTimeout(() => {
                                previewWrapper.style.transition = 'opacity 0.4s ease';
                                previewWrapper.style.opacity = '1';
                            }, 50);
                        });
                    }
                });
            </script>
        @endpush
    </div>
</div>
