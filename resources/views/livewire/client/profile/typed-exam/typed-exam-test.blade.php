<div x-data="{
    remainingSeconds: {{ $remainingSeconds }}, // فقط عدد ساده از سرور
    showTimer: @entangle('showTimer'),         // این یکی را می‌توانی entangle نگه داری
    timerInterval: null,
    showConfirmModal: false,
    showFiveMinuteWarning: false,
    fiveMinuteModalShown: false,

    init() {
        // اگر به هر دلیل مقدار ناعدد بود، صفرش کن
        if (isNaN(parseInt(this.remainingSeconds))) {
            this.remainingSeconds = 0;
        }
        this.startTimer();
    },

    startTimer() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
        }

        this.timerInterval = setInterval(() => {
            if (this.remainingSeconds > 0) {
                this.remainingSeconds--;

                // وقتی دقیقاً 5 دقیقه (300 ثانیه) مانده، یکبار اخطار بده
                if (this.remainingSeconds === 300 && !this.fiveMinuteModalShown) {
                    this.showFiveMinuteWarning = true;
                    this.fiveMinuteModalShown = true;
                }
            } else {
                clearInterval(this.timerInterval);
                $wire.timeExpired(); // Livewire → submitExam → redirect
            }
        }, 1000);
    },

    formatTime(seconds) {
        seconds = parseInt(seconds ?? 0, 10);
        if (isNaN(seconds) || seconds < 0) {
            seconds = 0;
        }

        const hrs  = Math.floor(seconds / 3600);
        const mins = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;

        return {
            hours:   hrs.toString().padStart(2, '0'),
            minutes: mins.toString().padStart(2, '0'),
            seconds: secs.toString().padStart(2, '0'),
        };
    }
}" x-init="init()" class="min-h-screen bg-background">



<div class="max-w-7xl mx-auto px-4 py-6 space-y-6">

        <!-- Top Info Card -->

        <div class="bg-secondary border border-border rounded-2xl p-4">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <!-- Right Side - Exam Info -->

                <div class="flex items-center gap-4">

                    <div class="flex-shrink-0 w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>

                        </svg>

                    </div>

                    <div style="margin-right: 10px">

                        <h1 class="font-bold text-xl text-foreground">{{ $exam->title }}</h1>

                        <p class="text-sm text-muted">{{ $totalQuestions }} سوال</p>

                    </div>

                </div>


            </div>
            <!-- Left Side - Timer and Controls -->

            <div class="flex flex-col sm:flex-row items-center justify-end gap-4">


                <!-- Timer Display -->
                <!-- Left Side - Timer and Controls -->
                <div class="mt-4 flex flex-col sm:flex-row items-center justify-end gap-4">

                    {{-- تاگل "مشاهده زمان" شبیه تصویر --}}
                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center gap-2 bg-background/80 border border-border rounded-2xl px-3 py-2">
                            <!-- سوئیچ -->
                            <button
                                type="button"
                                class="relative inline-flex items-center gap-2"
                                @click="showTimer = !showTimer"
                            >
                                <!-- آیکن زمان‌سنج -->
                                <span
                                    class="flex items-center justify-center w-7 h-7 rounded-xl bg-primary/10 text-primary"
                                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M12 8v4l2 2m-5-9h6M12 4a8 8 0 100 16 8 8 0 000-16z"/>
                    </svg>
                </span>

                                <!-- بدنه سوئیچ -->
                                <span class="relative inline-flex items-center">
                    <span class="w-11 h-6 rounded-full bg-gray-300 dark:bg-gray-700"></span>
                    <span
                        class="absolute w-5 h-5 rounded-full bg-white shadow-md transform transition-transform duration-200"
                        :class="showTimer ? 'translate-x-[18px]' : 'translate-x-[2px]'"
                    ></span>
                </span>
                            </button>

                            <span class="text-xs sm:text-sm text-muted">
                مشاهده زمان
            </span>
                        </div>
                    </div>

                    {{-- باکس‌های ساعت / دقیقه / ثانیه --}}
                    <div class="flex items-center justify-end gap-2 transition-all duration-200"
                         :class="showTimer ? 'timer-blur-glass' : ''">

                        <!-- ثانیه -->
                        <div
                            class="flex flex-col items-center bg-background border border-border rounded-xl px-3 py-2 min-w-[50px]">
                            <span class="font-bold text-lg"
                                  :class="remainingSeconds < 60 ? 'text-red-500' : 'text-foreground'"
                                  x-text="formatTime(remainingSeconds).seconds"></span>
                            <span class="text-[10px] text-muted">ثانیه</span>
                        </div>

                        <span class="text-xl font-bold text-muted">:</span>

                        <!-- دقیقه -->
                        <div
                            class="flex flex-col items-center bg-background border border-border rounded-xl px-3 py-2 min-w-[50px]">
                            <span class="font-bold text-lg text-foreground"
                                  x-text="formatTime(remainingSeconds).minutes"></span>
                            <span class="text-[10px] text-muted">دقیقه</span>
                        </div>

                        <span class="text-xl font-bold text-muted">:</span>

                        <!-- ساعت -->
                        <div
                            class="flex flex-col items-center bg-background border border-border rounded-xl px-3 py-2 min-w-[50px]">
                            <span class="font-bold text-lg text-foreground"
                                  x-text="formatTime(remainingSeconds).hours"></span>
                            <span class="text-[10px] text-muted">ساعت</span>
                        </div>
                    </div>

                </div>


            </div>

        </div>


        <br>
        <!-- Filter and View Toggle -->

        <div class="bg-secondary border border-border rounded-2xl p-4">

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">

                <!-- Filter Select -->

                <div class="flex items-center gap-3 w-full sm:w-auto">

                    <label class="text-sm text-muted whitespace-nowrap">فیلتر سوالات:</label>

                    <select wire:model.live="questionFilter"

                            class="flex-1 sm:flex-none bg-background border border-border rounded-xl px-4 py-2 text-sm text-foreground focus:ring-2 focus:ring-primary/20 focus:border-primary">

                        <option value="all">تمامی سوالات</option>

                        <option value="unanswered">سوالات بدون پاسخ</option>

                        <option value="minus">سوالات با علامت منها</option>

                        <option value="circle">سوالات با علامت دایره</option>

                        <option value="close">سوالات با علامت ضربدر</option>

                    </select>

                </div>


                <!-- View Mode Toggle -->

                <div class="flex items-center gap-2 bg-background border border-border rounded-xl p-1">

                    <button wire:click="setViewMode('questions')"

                            class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ $viewMode === 'questions' ? 'bg-primary text-primary-foreground' : 'text-muted hover:text-foreground' }}">

                        سوالات

                    </button>

                    <button wire:click="setViewMode('answersheet')"

                            class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ $viewMode === 'answersheet' ? 'bg-primary text-primary-foreground' : 'text-muted hover:text-foreground' }}">

                        پاسخنامه

                    </button>

                </div>

            </div>

        </div>


        <br>
        <div class="grid lg:grid-cols-12 gap-6">

            <!-- Question Grid -->


            <!-- Main Content Area -->

            <div class="lg:col-span-8 xl:col-span-9 order-1 lg:order-2">

                @if($viewMode === 'questions')
                    @if(count($questionsForView))
                        @foreach($questionsForView as $qItem)
                            @php
                                $question = $qItem['question'];
                                $options  = $qItem['options'];
                                $selected = $qItem['selected'];
                                $mark     = $qItem['mark'];
                                $index    = $qItem['index'];
                            @endphp

                            <div class="bg-secondary border border-border rounded-2xl overflow-hidden mb-5">
                                <!-- Question Header -->
                                <div class="relative bg-gradient-to-r from-primary/20 to-primary/5 p-4">
                                    <div class="absolute top-0 left-0 right-0 h-1 bg-primary"></div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                            <span
                                class="inline-flex items-center justify-center w-10 h-10 bg-primary text-primary-foreground rounded-full font-bold">
                                {{ $index + 1 }}
                            </span>
                                            <span class="text-sm text-muted">از {{ $totalQuestions }} سوال</span>
                                        </div>

                                        <!-- Question Marks -->
                                        <div class="flex items-center gap-2">
                                            <button wire:click="setQuestionMark({{ $question->id }}, 'close')"
                                                    class="w-8 h-8 rounded-full flex items-center justify-center transition-colors
                                           {{ $mark === 'close' ? 'bg-red-500 text-white' : 'bg-background border border-border text-muted hover:border-red-500 hover:text-red-500' }}">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>

                                            <button wire:click="setQuestionMark({{ $question->id }}, 'minus')"
                                                    class="w-8 h-8 rounded-full flex items-center justify-center transition-colors
                                           {{ $mark === 'minus' ? 'bg-orange-500 text-white' : 'bg-background border border-border text-muted hover:border-yellow-500 hover:text-yellow-500' }}">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2" d="M20 12H4"/>
                                                </svg>
                                            </button>

                                            <button wire:click="setQuestionMark({{ $question->id }}, 'circle')"
                                                    class="w-8 h-8 rounded-full flex items-center justify-center transition-colors
                                           {{ $mark === 'circle' ? 'bg-blue-500 text-white' : 'bg-background border border-border text-muted hover:border-blue-500 hover:text-blue-500' }}">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <circle cx="12" cy="12" r="8" stroke-width="2"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Question Body -->
                                <div class="p-6">
                                    <div class="prose text-white prose-sm dark:prose-invert max-w-none mb-5"
                                         dir="{{ $question->direction ?? 'rtl' }}">
                                        {!! $question->content?->body !!}
                                    </div>

                                    <!-- Options -->
                                    <div class="space-y-3">
                                        @foreach($options as $optIndex => $option)
                                            @php
                                                $isSelected  = $selected === $option->option_number;
                                                $optionLabel = ['الف', 'ب', 'ج', 'د'][$optIndex] ?? ($optIndex + 1);
                                            @endphp

                                            <button
                                                wire:click="selectAnswer({{ $question->id }}, {{ $option->option_number }})"
                                                class="w-full text-right p-4 rounded-xl border-2 transition-all
                                       {{ $isSelected ? 'border-primary bg-primary/10' : 'border-border bg-background hover:border-primary/50 hover:bg-primary/5' }}"
                                            >
                                                <div class="flex items-start gap-4">
                                    <span class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm
                                                 {{ $isSelected ? 'bg-primary text-primary-foreground' : 'bg-secondary text-foreground' }}">
                                        {{ $optionLabel }}
                                    </span>

                                                    <div class="flex-1 prose prose-sm dark:prose-invert text-foreground"
                                                         style="margin-right: 20px">
                                                        {!! $option->content !!}
                                                    </div>

                                                    @if($isSelected)
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             class="w-6 h-6 text-primary flex-shrink-0" fill="none"
                                                             viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="bg-secondary border border-border rounded-2xl p-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-muted mb-4"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-muted">سوالی با این فیلتر یافت نشد</p>
                        </div>
                    @endif
                @else
                    <!-- Answer Sheet View -->
                    <div class="bg-secondary border border-border rounded-2xl p-6" dir="rtl">
                        <h3 class="font-bold text-xl text-foreground mb-5">پاسخنامه</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                            @foreach($questionGrid as $item)
                                @php
                                    $unanswered = !$item['is_answered'];
                                    $mark = $item['mark'] ?? null;

                                    $markClass = match($mark) {
                                        'minus' => 'bg-amber-500/15 text-amber-300 border border-amber-500/40',
                                        'circle' => 'bg-sky-500/15 text-sky-300 border border-sky-500/40',
                                        'close' => 'bg-red-500/15 text-red-300 border border-red-500/40',
                                        default => '',
                                    };

                                    $markSymbol = match($mark) {
                                        'minus' => '−',
                                        'circle' => '○',
                                        'close' => '×',
                                        default => '',
                                    };
                                @endphp

                                    <!-- هر ردیف پاسخنامه: شماره سؤال + ۴ گزینه + ستون علامت -->
                                <button
                                    type="button"
                                    wire:click="goToQuestion({{ $item['index'] }}); setViewMode('questions')"
                                    class="group flex items-center gap-3 bg-background/60 border rounded-2xl px-3 py-2 text-xs sm:text-sm
                           transition-all
                           {{ $unanswered ? 'border-red-500/40' : 'border-border' }}
                           {{ $item['is_current'] ? 'ring-2 ring-primary/60 border-primary' : '' }}
                           hover:bg-background hover:border-primary/40"
                                >
                                    <!-- شماره سؤال (دایره) -->
                                    <div class="flex items-center gap-2 min-w-[3.2rem]">
                        <span
                            class="flex items-center justify-center w-8 h-8 rounded-full border border-border
                                   bg-secondary text-foreground text-xs font-bold"
                        >
                            {{ $item['index'] + 1 }}
                        </span>

                                        @if($mark)
                                            <span
                                                class="flex items-center justify-center w-7 h-7 rounded-full text-base font-bold {{ $markClass }}"
                                                title="علامت سؤال"
                                            >
                                {{ $markSymbol }}
                            </span>
                                        @endif
                                    </div>

                                    <!-- گزینه‌ها به صورت دایره‌ای مثل پاسخنامه تستی -->
                                    <div class="flex-1 flex items-center justify-between gap-1">
                                        @for($i = 1; $i <= 4; $i++)
                                            @php
                                                $isSelected = $item['selected_option'] === $i;
                                            @endphp

                                            <div
                                                class="flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-full border
                                       text-[11px] sm:text-xs font-semibold
                                       {{ $isSelected
                                            ? 'bg-primary border-primary text-primary-foreground shadow-md shadow-primary/30'
                                            : 'border-border text-muted group-hover:border-primary/40 group-hover:text-foreground' }}"
                                            >

                                            </div>
                                            <span
                                                class="relative inline-flex items-center
                                                justify-center  font-bold text-xs

                                                 {{ $isSelected
                                            ? 'rounded-full h-5 w-5 bg-primary text-primary-foreground'
                                            : 'border-border text-muted group-hover:border-primary/40 group-hover:text-foreground' }}">{{ $i }}</span>
                                        @endfor
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif


            </div>


        </div>

        <br>
        <!-- Submit Button -->

        <button
            type="button"
            x-on:click="showConfirmModal = true"
            class="inline-flex items-center  gap-2 px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl font-semibold text-sm transition-colors"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            ثبت نهایی
        </button>

        <!-- Confirm Submit Modal -->
        <div
            x-show="showConfirmModal"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center "
            x-transition.opacity
        >
            <div class="absolute inset-0 bg-black/60" @click="showConfirmModal = false"></div>

            <div
                class="relative bg-secondary border border-border rounded-2xl max-w-md w-full mx-4 p-6 shadow-xl"
                x-transition.scale
            >
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-lg text-primary">ثبت نهایی آزمون</h2>
                    <button type="button" class="text-muted hover:text-foreground" @click="showConfirmModal = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <p class="text-sm text-muted mb-4 leading-relaxed">
                    آیا از ثبت نهایی آزمون اطمینان دارید؟
                    <br>
                    پس از ثبت نهایی، امکان تغییر پاسخ‌ها وجود نخواهد داشت.
                </p>
                <br>
                <hr>
                <br>
                <div class="mb-6 text-sm leading-relaxed">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-white">تعداد کل سوالات:</span>
                        <span class="font-bold text-foreground">{{ $totalQuestions }}</span>
                    </div>

                    <div class="flex items-center justify-between mb-2">
                        <span class="text-white">سؤالات پاسخ داده شده:</span>
                        <span class="font-bold text-emerald-400">{{ $answeredCount }}</span>
                    </div>

                    @php
                        $unanswered = max($totalQuestions - $answeredCount, 0);
                    @endphp

                    <div class="flex items-center justify-between">
                        <span class="text-white">سؤالات بدون پاسخ:</span>
                        <span class="font-bold {{ $unanswered > 0 ? 'text-red-400' : 'text-emerald-400' }}">
                    {{ $unanswered }}
                </span>
                    </div>
                </div>
                <br>
                <div class="flex items-center justify-end gap-3">
                    <button
                        type="button"
                        class="px-4 py-2 rounded-xl border border-border bg-background text-sm text-foreground hover:bg-secondary transition-colors"
                        @click="showConfirmModal = false"
                    >
                        انصراف
                    </button>

                    <button
                        type="button"
                        class="px-4 py-2 rounded-xl bg-green-500 hover:bg-green-600 text-white text-sm font-semibold transition-colors"
                        @click="$wire.submitExam(); showConfirmModal = false"
                    >
                        بله، ثبت نهایی
                    </button>
                </div>
            </div>
        </div>

        <!-- Five-Minute Warning Modal -->
        <div
            x-show="showFiveMinuteWarning"
            x-cloak
            class="fixed inset-0 z-40 flex items-center justify-center"
            x-transition.opacity
        >
            <div class="absolute inset-0 bg-black/60" @click="showFiveMinuteWarning = false"></div>

            <div
                class="relative bg-secondary border border-border rounded-2xl max-w-md w-full mx-4 p-6 shadow-xl"
                x-transition.scale
            >
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-lg text-amber-400">۵ دقیقه‌ی پایانی</h2>
                    <button type="button" class="text-muted hover:text-foreground"
                            @click="showFiveMinuteWarning = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <p class="text-sm text-muted mb-6 leading-relaxed">
                    فقط ۵ دقیقه تا پایان آزمون باقی مانده است.
                    <br>
                    لطفاً پاسخ تمام سؤالات خود را بررسی و ثبت کنید.
                </p>

                <div class="flex items-center justify-end">
                    <button
                        type="button"
                        class="px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold transition-colors"
                        @click="showFiveMinuteWarning = false"
                    >
                        متوجه شدم
                    </button>
                </div>
            </div>
        </div>

    </div>


    @push('link')

        <style>

            [x-cloak] {
                display: none !important;
            }

            /* ----- تایمر شیشه‌ای و تار ----- */
            .timer-blur-glass {
                filter: blur(6px); /* تار کردن خود باکس‌ها */
                -webkit-backdrop-filter: blur(8px);
                backdrop-filter: blur(8px); /* اثر شیشه‌ای روی پس‌زمینه (اگر مرورگر پشتیبانی کند) */
                background-color: rgba(15, 23, 42, 0.35); /* یک بک‌گراند نیمه‌شفاف (متناسب با تم خودت تنظیم کن) */
                border-radius: 1rem;
                pointer-events: none; /* کلیک‌ناپذیر */
                user-select: none; /* انتخاب‌ناپذیر */
                transition: all 0.2s ease-in-out;
            }

            .prose img {
                max-width: 100%;
                height: auto;
                border-radius: 0.5rem;
            }

            .prose table {
                width: 100%;
                border-collapse: collapse;
            }

            .prose table th,
            .prose table td {
                border: 1px solid #e5e7eb;
                padding: 0.5rem;
            }
        </style>

    @endpush

</div>
