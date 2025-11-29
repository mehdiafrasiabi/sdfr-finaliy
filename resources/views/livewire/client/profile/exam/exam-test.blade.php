@push('link')
    <style>
        /* ====== Glassmorphism Background ====== */
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

        /* ====== Question Card Animation ====== */
        .question-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 4px solid transparent;
        }

        .question-card.answered {
            border-left-color: #10b981;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(255, 255, 255, 0) 100%);
        }

        .dark .question-card.answered {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(20, 25, 40, 0) 100%);
        }

        .question-card.unanswered {
            border-left-color: #ef4444;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.05) 0%, rgba(255, 255, 255, 0) 100%);
        }

        .dark .question-card.unanswered {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.08) 0%, rgba(20, 25, 40, 0) 100%);
        }

        .question-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .dark .question-card:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
        }

        /* ====== Option Buttons ====== */
        .option-btn {
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
        }

        .option-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .option-btn:hover::before {
            opacity: 1;
        }

        .option-btn.selected {
            transform: scale(1);
            animation: optionSelect 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes optionSelect {
            0% {
                transform: scale(0.95);
                opacity: 0;
            }
            50% {
                transform: scale(1.08);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* ====== Timer Animation ====== */
        .timer-display {
            font-variant-numeric: tabular-nums;
            font-family: 'Courier New', monospace;
        }

        .timer-warning {
            animation: timerPulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes timerPulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.7;
                transform: scale(1.02);
            }
        }

        /* ====== Progress Elements ====== */
        .progress-item {
            transition: all 0.25s ease;
        }

        .progress-item:hover {
            transform: translateX(-4px);
        }

        .progress-badge {
            animation: badgePop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
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

        /* ====== Button Styles ====== */
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary:hover:not(:disabled) {
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5);
            transform: translateY(-2px);
        }

        .btn-primary:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.5);
            transform: translateY(-2px);
        }

        /* ====== Scrollbar Styling ====== */
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

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #1e40af 0%, #1e3a8a 100%);
        }

        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        /* ====== Layout ====== */
        .exam-container {
            direction: rtl;
        }

        .exam-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width: 1024px) {
            .exam-content {
                grid-template-columns: 1fr 360px;
            }
        }

        /* ====== Fade In Animation ====== */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.4s ease-out;
        }
    </style>
@endpush

<div class="exam-container min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-slate-950 dark:via-blue-950 dark:to-slate-950 py-8">
    <div class="max-w-7xl mx-auto px-4">

        <!-- ====== Menu Section ====== -->
        <div class="mb-8 fade-in">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex items-center gap-1">
                    <div class="w-1 h-1 bg-blue-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                </div>
                <h1 class="font-black text-3xl md:text-4xl text-transparent bg-clip-text bg-gradient-to-l from-blue-600 text-white">
                    {{ $exam->title }}
                </h1>
            </div>

            <!-- ====== Timer & Download Section ====== -->
            <div class="grid grid-cols-3 md:grid-cols-3 gap-4 spy">
                <!-- Timer Card -->
                <div x-data="{
                    initialTime: {{ $initialTimeInSeconds }},
                    startedAt: {{ $startedAtTimestamp }},
                    remainingSeconds: 0,
                    interval: null,

                    init() {
                        this.updateRemainingTime();
                        this.interval = setInterval(() => {
                            this.updateRemainingTime();
                            if (this.remainingSeconds <= 0) {
                                clearInterval(this.interval);
                                @this.call('submitExam');
                            }
                        }, 1000);
                    },

                    updateRemainingTime() {
                        const now = Math.floor(Date.now() / 1000);
                        const elapsedTime = now - this.startedAt;
                        this.remainingSeconds = Math.max(0, this.initialTime - elapsedTime);
                    },

                    formatTime(time) {
                        const hours = Math.floor(time / 3600);
                        const minutes = Math.floor((time % 3600) / 60);
                        const seconds = time % 60;
                        if (hours > 0) {
                            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                        }
                        return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                    },

                    isWarning() {
                        return this.remainingSeconds < 300;
                    },

                    isDanger() {
                        return this.remainingSeconds < 60;
                    }
                }" x-init="init()" class="glass-bg rounded-3xl p-6 border border-blue-200 dark:border-blue-900 col-span-1">
                    <div class="flex flex-col items-center justify-center gap-3 mb-2 space-y-5">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-primary dark:text-primary">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                            </svg>
                            <span class="text-sm font-semibold text-foreground">زمان باقی‌مانده</span>
                        </div>
                        <div class="timer-display font-black text-3xl md:text-4xl  bg-clip-text bg-gradient-to-l"
                             :class="isDanger() ? 'text-red-500 dark:text-red-500 timer-warning' : isWarning() ? 'text-yellow-500 dark:text-yellow-500' : 'text-green-500 dark:text-green-500'"
                             x-text="formatTime(remainingSeconds)"></div>
                        <p x-show="isDanger()" class="text-xs font-bold text-red-500">⚠️ زمان به پایان می‌رسد!</p>
                        <p x-show="isWarning() && !isDanger()" class="text-xs font-semibold text-yellow-500">⏰ توجه به زمان</p>
                    </div>
                </div>

                <!-- Download Card -->
                <a href="{{ asset($exam->pdf_path) }}" download
                   class="btn-success glass-bg rounded-3xl space-y-5 p-6 border border-green-200 dark:border-green-900 inline-flex flex-col items-center justify-center gap-3 text-white font-semibold hover:shadow-xl transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path d="M19 12v7H5v-7H3v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2z"></path>
                        <path d="M11 3L5.5 8.5l1.42 1.41L11 5.83V15h2V5.83l4.08 4.08L18.5 8.5 12 2z"></path>
                    </svg>
                    <span>دانلود سوالات</span>
                </a>

                <!-- Stats Card -->
                <div class="glass-bg rounded-3xl p-6 border border-blue-200 dark:border-blue-900 space-y-5">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center">
                            <p class="text-3xl font-black text-green-500 dark:text-green-500">{{ $this->answeredCount() }}</p>
                            <p class="text-xs text-muted mt-1 font-semibold">پاسخ داده</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-black text-red-500 dark:text-red-500">{{ $this->unansweredCount() }}</p>
                            <p class="text-xs text-muted mt-1 font-semibold">پاسخ‌نشده</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ====== Main Content ====== -->
        <div class="exam-content">

            <!-- ====== Questions Section ====== -->
            <div class="space-y-5">
                <h2 class="text-xl md:text-2xl font-bold text-foreground sticky top-0 bg-gradient-to-b from-slate-50 via-blue-50 to-transparent dark:from-slate-950 dark:via-blue-950 dark:to-transparent py-4 -mx-4 px-4 z-10 mb-6">
                    📝 <span class="text-primary">{{ $exam->number_of_questions }}</span> سوال برای پاسخ دادن
                </h2>

                <div class="space-y-5">
                    @for ($i = 1; $i <= $exam->number_of_questions; $i++)
                        <div class="question-card glass-bg rounded-2xl p-6 border border-slate-200 dark:border-slate-700 fade-in
                                    @if(isset($answers[$i]) && $answers[$i] !== null)
                                        answered
                                    @else
                                        unanswered
                                    @endif"
                             style="animation-delay: {{ ($i - 1) * 0.05 }}s">

                            <!-- Question Menu -->
                            <div class="flex items-start justify-between gap-4 mb-6">
                                <div class="flex items-start gap-4 flex-1">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 text-white font-bold text-sm flex-shrink-0 shadow-md">
                                        {{ $i }}
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-base md:text-lg text-foreground">سوال {{ $i }}</h3>
                                        <p class="text-xs text-muted mt-1">انتخاب یک گزینه</p>
                                    </div>
                                </div>
                                @if(isset($answers[$i]) && $answers[$i] !== null)
                                    <div class="progress-badge inline-flex items-center gap-1 rounded-full px-3 py-1 bg-gradient-to-r from-green-400 to-green-600 text-white text-xs font-bold shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 1 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.208Z" clip-rule="evenodd" />
                                        </svg>
                                        پاسخ داده شده
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1 rounded-full px-3 py-1 bg-gradient-to-r from-red-400 to-red-600 text-white text-xs font-bold shadow-md opacity-70">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                                        </svg>
                                        پاسخ‌نشده
                                    </div>
                                @endif
                            </div>

                            <!-- Options Grid -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @for ($option = 1; $option <= 4; $option++)
                                    <button type="button"
                                            wire:click="selectOption({{ $i }}, {{ $option }})"
                                            class="option-btn relative w-full h-14 rounded-xl font-bold text-lg transition-all duration-200 shadow-sm hover:shadow-md
                                                @if(isset($answers[$i]) && $answers[$i] == $option)
                                                    selected bg-gradient-to-br from-blue-400 to-blue-600 text-white border-2 border-blue-600 dark:from-blue-500 dark:to-blue-700
                                                @else
                                                    bg-white dark:bg-slate-800 text-foreground border-2 border-slate-200 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-600
                                                @endif">
                                        {{ $option }}
                                    </button>
                                @endfor
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Submit Button -->
                <button wire:click="submitExam"
                        wire:confirm="آیا از ثبت نهایی آزمون مطمئن هستید؟"
                        class="btn-primary w-full rounded-2xl py-4 text-white font-bold text-lg hover:shadow-xl transition-all flex items-center justify-center gap-2 mt-8">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.052-.143Z" clip-rule="evenodd" />
                    </svg>
                    <span>ثبت نهایی آزمون</span>
                </button>
            </div>

            <!-- ====== Navbar Progress ====== -->
            <div class="sticky top-24 h-fit">
                <div class="glass-bg rounded-2xl p-6 border border-blue-200 dark:border-blue-900 shadow-lg">
                    <h3 class="font-bold text-lg text-foreground mb-6 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-primary dark:text-primary">
                            <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0 1 12 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 0 1 3.498 1.307 4.491 4.491 0 0 1 1.307 3.497A4.49 4.49 0 0 1 21.75 12a4.5 4.5 0 0 1-1.549 3.397 4.491 4.491 0 0 1-1.307 3.497 4.491 4.491 0 0 1-3.497 1.307A4.49 4.49 0 0 1 12 21.75a4.5 4.5 0 0 1-3.397-1.549 4.49 4.49 0 0 1-3.498-1.307 4.491 4.491 0 0 1-1.307-3.497A4.49 4.49 0 0 1 2.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 0 1 1.307-3.498 4.49 4.49 0 0 1 3.497-1.307zm7.007 6.387a.75.75 0 1 0-1.06-1.06L9.039 9.039a.75.75 0 0 0 0 1.06l5.511 5.511a.75.75 0 1 0 1.06-1.06L10.1 9.426z" clip-rule="evenodd" />
                        </svg>
                        درصد تکمیل
                    </h3>

                    <!-- Progress Bar -->
                    <div class="mb-6">
                        <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full transition-all duration-500 ease-out"
                                 style="width: {{ ($this->answeredCount() / $exam->number_of_questions) * 100 }}%"></div>
                        </div>
                        <div class="flex items-center justify-between mt-3">
                            <p class="text-sm font-bold text-foreground">
                                <span class="text-blue-600 dark:text-blue-400">{{ $this->answeredCount() }}</span> / {{ $exam->number_of_questions }}
                            </p>
                            <p class="text-sm font-bold text-primary dark:text-primary">
                                {{ round(($this->answeredCount() / $exam->number_of_questions) * 100) }}%
                            </p>
                        </div>
                    </div>

                    <!-- Questions List -->
                    <div class="space-y-2 max-h-80 overflow-y-auto custom-scrollbar">
                        <p class="text-xs font-semibold text-muted mb-3">لیست سوالات:</p>
                        @for ($i = 1; $i <= $exam->number_of_questions; $i++)
                            <div class="progress-item rounded-lg p-3 flex items-center justify-between bg-slate-100 dark:bg-slate-800 hover:bg-slate-150 dark:hover:bg-slate-700">
                                <span class="text-sm font-semibold text-foreground">سوال {{ $i }}</span>
                                @if(isset($answers[$i]) && $answers[$i] !== null)
                                    <div class="progress-badge inline-flex items-center justify-center w-6 h-6 rounded-full bg-gradient-to-r from-green-400 to-green-600 text-green-500 text-xs font-bold shadow-md">
                                        {{ $answers[$i] }}
                                    </div>
                                @else
                                    <div class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-300 dark:bg-slate-600 text-rose-500 dark:text-rose-500 text-xs font-semibold">
                                        -
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>

                    <!-- Summary -->
                    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700 space-y-3">
                        <div class="rounded-lg p-3 bg-gradient-to-r from-green-50 to-green-100 dark:from-green-950 dark:to-green-900">
                            <p class="text-xs text-muted mb-1">✓ پاسخ داده شده</p>
                            <p class="text-2xl font-black text-green-500 dark:text-green-500">{{ $this->answeredCount() }}</p>
                        </div>
                        <div class="rounded-lg p-3 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-950 dark:to-red-900">
                            <p class="text-xs text-muted mb-1">✗ پاسخ‌نشده</p>
                            <p class="text-2xl font-black text-red-500 dark:text-red-500">{{ $this->unansweredCount() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
