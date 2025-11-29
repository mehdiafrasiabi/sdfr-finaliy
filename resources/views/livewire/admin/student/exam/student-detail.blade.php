<div>

    @push('link')
        <style>
            /* ====== Glassmorphism & Animations ====== */
            .glass-bg {
                background: rgba(255, 255, 255, 0.75);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.3);
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            }

            .dark .glass-bg {
                background: rgba(20, 25, 40, 0.8);
                border: 1px solid rgba(255, 255, 255, 0.08);
                box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            }

            /* پس‌زمینه کلی صفحه نتیجه */
            .result-bg {
                background: radial-gradient(circle at top, #e0f2fe 0, #f8fafc 45%, #e5e7eb 100%);
            }

            .dark .result-bg {
                background: radial-gradient(circle at top, #0f172a 0, #020617 45%, #020617 100%);
            }

            /* Score Cards Animation */
            .score-card {
                animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
                transition: all 0.3s ease;
            }

            .score-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
            }

            .dark .score-card:hover {
                box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
            }

            /* Question Item Animation */
            .question-item {
                animation: fadeInLeft 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
                transition: all 0.25s ease;
            }

            .question-item:hover {
                transform: translateX(-4px);
            }

            /* Status Badges */
            .badge-correct {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                animation: badgePop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            .badge-incorrect {
                background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
                animation: badgePop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            .badge-unanswered {
                background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
                animation: badgePop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fadeInLeft {
                from {
                    opacity: 0;
                    transform: translateX(20px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes badgePop {
                0% {
                    transform: scale(0.5) rotate(-180deg);
                    opacity: 0;
                }
                100% {
                    transform: scale(1) rotate(0deg);
                    opacity: 1;
                }
            }

            @keyframes successPulse {
                0%, 100% {
                    transform: scale(1);
                }
                50% {
                    transform: scale(1.05);
                }
            }

            .success-checkmark {
                animation: successPulse 0.6s ease-out;
            }

            /* Upload Area */
            .upload-area {
                transition: all 0.3s ease;
                border: 2px dashed rgba(59, 130, 246, 0.3);
            }

            .upload-area:hover,
            .upload-area.dragover {
                border-color: rgba(59, 130, 246, 0.8);
                background: rgba(59, 130, 246, 0.05);
            }

            .dark .upload-area:hover,
            .dark .upload-area.dragover {
                background: rgba(59, 130, 246, 0.08);
            }

            /* Scrollbar */
            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: rgba(0, 0, 0, 0.05);
                border-radius: 10px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: linear-gradient(180deg, #3b82f6 0%, #1e40af 100%);
                border-radius: 10px;
            }

            .dark .custom-scrollbar::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.05);
            }

            /* Layout */
            .result-container {
                direction: rtl;
            }

            /* Question Status Indicator */
            .status-indicator {
                width: 3px;
                height: 100%;
            }

            .status-correct {
                background: linear-gradient(180deg, #10b981 0%, #059669 100%);
            }

            .status-incorrect {
                background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%);
            }

            .status-unanswered {
                background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
            }

            /* Answer Section */
            .answer-section {
                border-left: 4px solid transparent;
                padding: 1rem;
                border-radius: 0.5rem;
                transition: all 0.25s ease;
            }

            .answer-section.correct {
                border-left-color: #10b981;
                background: rgba(16, 185, 129, 0.05);
            }

            .dark .answer-section.correct {
                background: rgba(16, 185, 129, 0.08);
            }

            .answer-section.incorrect {
                border-left-color: #ef4444;
                background: rgba(239, 68, 68, 0.05);
            }

            .dark .answer-section.incorrect {
                background: rgba(239, 68, 68, 0.08);
            }

            .answer-section.unanswered {
                border-left-color: #f59e0b;
                background: rgba(245, 158, 11, 0.05);
            }

            .dark .answer-section.unanswered {
                background: rgba(245, 158, 11, 0.08);
            }

            .percentage-display {
                font-size: 4rem;
                font-weight: 900;
                background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .dark .percentage-display {
                background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .performance-message {
                background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
                border: 1px solid rgba(16, 185, 129, 0.3);
            }

            .dark .performance-message {
                background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.2) 100%);
                border: 1px solid rgba(16, 185, 129, 0.4);
            }

            .stats-box {
                transition: all 0.3s ease;
            }

            .stats-box:hover {
                transform: translateY(-5px);
            }
        </style>
    @endpush

    <div class="result-container card min-vh-100 py-4 py-md-5">
        <div class="container">

            {{-- هدر آزمون و درصد کلی --}}
            <div class="mb-4 mb-md-5">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="d-flex align-items-center gap-1">
                        <div style="width:4px;height:4px" class="bg-primary rounded-circle"></div>
                        <div style="width:8px;height:8px" class="bg-primary rounded-circle"></div>
                    </div>
                    <h1 class="fw-black fs-3 fs-md-2 text-primary mb-0">
                        آزمون: <span class="text-dark text-white">{{ $exam->title }}</span>
                    </h1>
                </div>

                <p class="fs-6 fw-semibold text-muted mb-4">
                    دانش‌آموز: <span class="text-dark">{{ $student->user->name }}</span>
                </p>

                <div class="text-center mb-4 mb-md-5">
                    <div class="percentage-display mb-2">
                        {{ $percentage }}%
                    </div>
                    <p class="fw-bold fs-4 text-dark text-white mb-0">
                        درصد کل آزمون
                    </p>
                </div>

                {{-- پیام عملکرد --}}
                @if($correctCount === $exam->number_of_questions && $unansweredCount === 0)
                    <div class="performance-message rounded-3 p-4 p-md-5 mb-4 text-center">
                        <div class="success-checkmark fs-1 mb-3">🎉</div>
                        <h2 class="fs-4 fw-black text-white mb-2">تبریک! عملکرد فوق‌العاده‌ای ثبت شده است.</h2>
                        <p class="mb-0 text-success">این دانش‌آموز به تمام سوالات پاسخ صحیح داده است.</p>
                    </div>
                @elseif($correctCount > $incorrectCount)
                    <div class="performance-message rounded-3 p-4 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="fs-1">👏</div>
                            <div>
                                <h3 class="fw-bold fs-5 text-success mb-1">عملکرد خوبی ثبت شده است!</h3>
                                <p class="mb-0 text-success">تعداد پاسخ‌های صحیح بیشتر از پاسخ‌های نادرست است.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- آمار صحیح / غلط / بی‌پاسخ --}}
            <div class="row g-3 g-md-4 mb-4">
                <div class="col-12 col-md-4">
                    <div class="stats-box glass-bg rounded-3 p-4 text-center border border-success-subtle">
                        <h3 class="fw-bold fs-6 text-success mb-2">پاسخ صحیح</h3>
                        <p class="fs-2 fw-black text-success mb-0">{{ $correctCount }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="stats-box glass-bg rounded-3 p-4 text-center border border-danger-subtle">
                        <h3 class="fw-bold fs-6 text-danger mb-2">پاسخ نادرست</h3>
                        <p class="fs-2 fw-black text-danger mb-0">{{ $incorrectCount }}</p>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="stats-box glass-bg rounded-3 p-4 text-center border border-warning-subtle">
                        <h3 class="fw-bold fs-6 text-warning mb-2">بی‌پاسخ</h3>
                        <p class="fs-2 fw-black text-warning mb-0">{{ $unansweredCount }}</p>
                    </div>
                </div>
            </div>

            {{-- لینک دفترچه و پاسخنامه --}}
            @if($examPdf || $solutionPdf)
                <div class="row g-3 g-md-4 mb-4">
                    @if($examPdf)
                        <div class="col-12 col-md-6">
                            <a href="{{ asset($examPdf) }}" download
                               class="glass-bg rounded-3 p-4 border border-primary-subtle d-flex align-items-center justify-content-between text-decoration-none text-dark">
                                <div>
                                    <h4 class="fw-bold fs-6 text-dark mb-1">📄 دفترچه آزمون</h4>
                                    <p class="mb-0 small text-secondary">مشاهده سوالات اصلی</p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                     class="fs-4 text-primary" style="width: 54px;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                            </a>
                        </div>
                    @endif
                    @if($solutionPdf)
                        <div class="col-12 col-md-6">
                            <a href="{{ asset($solutionPdf) }}" download
                               class="glass-bg rounded-3 p-4 border border-success-subtle d-flex align-items-center justify-content-between text-decoration-none text-dark">
                                <div>
                                    <h4 class="fw-bold fs-6 text-dark mb-1">✅ پاسخنامه تشریحی</h4>
                                    <p class="mb-0 small text-secondary">مشاهده پاسخ‌ها و توضیحات</p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" style="width: 54px;"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                     class="fs-4 text-success">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            {{-- بلوک آپلود/نمایش تحلیل آزمون --}}
            <div class="glass-bg rounded-3 border border-primary-subtle p-4 p-md-5 mb-3">
                <h3 class="fw-bold fs-5 text-dark mb-4 d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="currentColor" class="text-primary" style="width:24px;height:24px">
                        <path d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.69h13.5v-2.69a.75.75 0 0 1 1.5 0v2.69A2.25 2.25 0 0 1 15.75 21H3.75A2.25 2.25 0 0 1 1.5 18.75v-2.69a.75.75 0 0 1 .75-.75Z" />
                    </svg>
                    آپلود تحلیل آزمون
                </h3>

                @if($analysisStatus === 'sent' && !$uploadSuccess)
                    {{-- نمایش تحلیل‌های آپلود شده --}}
                    <div class="text-center py-4 py-md-5">
                        <div class="success-checkmark fs-1 mb-3">✅</div>
                        <h4 class="fw-bold fs-5 text-success mb-2">تحلیل آپلود شده است</h4>
                        <p class="text-muted mb-4">
                            تصاویر تحلیل دانش‌آموز در زیر قابل مشاهده است.
                        </p>

                        @if(!empty($analysisImagePath))
                            @php
                                $urls = collect($analysisImagePath)->map(function($img){
                                    if(is_array($img)) {
                                        $img = $img['path'] ?? array_values($img)[0] ?? '';
                                    }
                                    return Str::startsWith($img, ['http://','https://']) ? $img : asset($img);
                                })->values()->all();
                            @endphp

                            <div x-data="{ open: false, active: 0 }" class="mt-3">
                                <div class="row g-2">
                                    @foreach($urls as $idx => $img)
                                        <div class="col-6 col-md-3">
                                            <button type="button"
                                                    x-on:click="active = {{ $idx }}; open = true"
                                                    class="btn p-0 border border-secondary-subtle rounded-3 overflow-hidden w-100">
                                                <img src="{{ $img }}" alt="analysis-{{ $idx }}"
                                                     class="w-10" style="height:80px;object-fit:cover;">
                                            </button>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- لایت‌باکس --}}
                                <div x-show="open" x-on:keydown.escape.window="open = false" x-cloak
                                     class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-75"
                                     style="z-index: 1055;">
                                    <div class="w-100" style="max-width: 700px;">
                                        <div class="position-relative bg-white rounded-3 overflow-hidden">
                                            <button type="button"
                                                    x-on:click="open = false"
                                                    class="btn btn-light position-absolute top-0 end-0 m-3 rounded-circle shadow">
                                                ✕
                                            </button>

                                            <div class="bg-black d-flex align-items-center justify-content-center"
                                                 style="height:36vh;">
                                                <img :src="{{ json_encode($urls) }}[active]"
                                                     alt="analysis-large"
                                                     class="img-fluid"
                                                     style="max-height:36vh;object-fit:contain;">
                                            </div>

                                            <div class="d-flex align-items-center justify-content-between p-3 bg-light">
                                                <button type="button"
                                                        x-on:click="active = (active === 0 ? ({{ count($urls) }} - 1) : active - 1)"
                                                        class="btn btn-primary btn-sm">
                                                    ⬅ قبلی
                                                </button>
                                                <span class="small fw-semibold text-muted">
                                                    <span x-text="(active === null ? 0 : active + 1)"></span>
                                                    / {{ count($urls) }}
                                                </span>
                                                <button type="button"
                                                        x-on:click="active = (active === ({{ count($urls) }} - 1) ? 0 : active + 1)"
                                                        class="btn btn-primary btn-sm">
                                                    بعدی ➡
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endif
                    </div>

                @elseif($analysisStatus === 'expired')
                    {{-- مهلت تمام‌شده --}}
                    <div class="text-center py-4 rounded-3 bg-danger-subtle border border-danger-subtle">
                        <div class="fs-1 mb-2">⏰</div>
                        <h4 class="fw-bold fs-5 text-danger mb-2">مهلت تمام شده است</h4>
                        <p class="mb-0 text-muted">
                            پس از ۴۸ ساعت از تکمیل آزمون امکان آپلود تحلیل وجود ندارد.
                        </p>
                    </div>
                @else
                    {{-- فرم آپلود تحلیل --}}
                    <form wire:submit="uploadAnalysis">
                        <div class="mb-3">
                            <label for="analysis-upload"
                                   class="upload-area glass-bg rounded-3 p-4 text-center d-flex flex-column align-items-center justify-content-center"
                                   x-data="{ dragover: false }"
                                   x-on:dragover.prevent="dragover = true"
                                   x-on:dragleave="dragover = false"
                                   x-on:drop.prevent="dragover = false; $el.querySelector('input').click()">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                     fill="currentColor" class="text-primary mb-2" style="width:28px;height:28px">
                                    <path d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.69h13.5v-2.69a.75.75 0 0 1 1.5 0v2.69A2.25 2.25 0 0 1 15.75 21H3.75A2.25 2.25 0 0 1 1.5 18.75v-2.69a.75.75 0 0 1 .75-.75Z" />
                                </svg>
                                <p class="fw-bold text-dark mb-1">فایل را بکشید یا انتخاب کنید</p>
                                <p class="small text-muted mb-0">PNG, JPG, JPEG تا 5MB</p>
                                <input type="file" id="analysis-upload" multiple class="d-none"
                                       wire:model="analysisPhoto"
                                       accept="image/png,image/jpeg,image/jpg">
                            </label>
                            @error('analysisPhoto')
                            <p class="text-danger small fw-semibold mt-2">⚠️ {{ $message }}</p>
                            @enderror
                            @error('analysisPhoto.*')
                            <p class="text-danger small fw-semibold mt-2">⚠️ {{ $message }}</p>
                            @enderror
                        </div>

                        @if(!empty($analysisPhoto))
                            <div class="row g-2 mb-3">
                                @foreach($analysisPhoto as $photo)
                                    @if($photo instanceof \Livewire\TemporaryUploadedFile)
                                        <div class="col-6 col-md-3">
                                            <div class="position-relative rounded-3 overflow-hidden border border-primary-subtle">
                                                <img src="{{ $photo->temporaryUrl() }}" alt="preview"
                                                     class="w-100" style="height:80px;object-fit:cover;">
                                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-primary bg-opacity-25">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                         fill="currentColor" style="width:24px;height:24px" class="text-primary">
                                                        <path fill-rule="evenodd"
                                                              d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 1 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.208Z"
                                                              clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <button type="submit"
                                @disabled(empty($analysisPhoto))
                                class="btn btn-primary w-100 fw-bold d-flex align-items-center justify-content-center gap-2">
                            <span wire:loading.remove>
                                آپلود تحلیل
                            </span>
                            <span wire:loading class="d-flex align-items-center gap-2">
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                                در حال آپلود...
                            </span>
                        </button>
                    </form>
                @endif
            </div>

            {{-- نکته پایین صفحه --}}
            <div class="glass-bg rounded-3 p-3 border border-secondary-subtle mb-4">
                <p class="small text-muted mb-0">
                    <span class="fw-bold text-dark">💡 نکته:</span>
                    <span class="text-danger">تحلیل دانش‌آموزان را در بازه ۴۸ ساعته دریافت و بررسی کنید تا بازخورد دقیقی ارائه شود.</span>
                </p>
            </div>

            {{-- سوالات و پاسخ‌ها --}}
            <div class="glass-bg rounded-3 border border-secondary-subtle p-4 p-md-5">
                <h2 class="fs-4 fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                    📝 سوالات و پاسخ‌های آزمون
                </h2>

                <div class="row g-3">
                    @foreach($this->allQuestions() as $q)
                        <div class="col-12 col-lg-4">
                            <div class="question-item glass-bg rounded-3 border border-light overflow-hidden h-100">
                                <div class="d-flex h-100">
                                    <div class="status-indicator
                                        @if($q['status'] === 'correct')
                                            status-correct
                                        @elseif($q['status'] === 'incorrect')
                                            status-incorrect
                                        @else
                                            status-unanswered
                                        @endif">
                                    </div>

                                    <div class="flex-grow-1 p-3">
                                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                            <h3 class="fw-bold fs-6 text-dark mb-0">
                                                سوال {{ $q['number'] }}
                                            </h3>
                                            <div>
                                                @if($q['status'] === 'correct')
                                                    <span class="badge badge-correct border border-success-subtle">
                                                        پاسخ صحیح
                                                    </span>
                                                @elseif($q['status'] === 'incorrect')
                                                    <span class="badge badge-incorrect border-0">
                                                        پاسخ نادرست
                                                    </span>
                                                @else
                                                    <span class="badge badge-unanswered border-0">
                                                        بی‌پاسخ
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="answer-section
                                            @if($q['status'] === 'correct')
                                                correct
                                            @elseif($q['status'] === 'incorrect')
                                                incorrect
                                            @else
                                                unanswered
                                            @endif">

                                            <div class="mb-2">
                                                <p class="small fw-semibold text-muted mb-1">پاسخ دانش‌آموز:</p>
                                                <div class="fs-6 fw-bold
                                                    @if($q['status'] === 'correct')
                                                        text-success
                                                    @elseif($q['status'] === 'incorrect')
                                                        text-warning
                                                    @else
                                                        text-warning
                                                    @endif">
                                                    @if($q['student_answer'])
                                                        گزینه <span class="fw-black">{{ $q['student_answer'] }}</span>
                                                    @else
                                                        <span class="small">بدون پاسخ</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mb-0">
                                                <p class="small fw-semibold text-muted mb-1">پاسخ صحیح:</p>
                                                <div class="fs-6 fw-bold text-success">
                                                    گزینه <span class="fw-black">{{ $q['correct_option'] ?? 'نامشخص' }}</span>
                                                </div>
                                            </div>

                                            @if($q['description'])
                                                <div class="mt-3 pt-2 border-top border-light">
                                                    <p class="small fw-semibold text-muted mb-1">📚 توضیح:</p>
                                                    <p class="small text-dark mb-0">
                                                        {{ $q['description'] }}
                                                    </p>
                                                </div>
                                            @endif

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>
    </div>

</div>
