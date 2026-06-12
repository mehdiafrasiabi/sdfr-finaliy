<div x-data="{
    remainingSeconds: {{ $remainingSeconds }},
    showTimer: false,
    currentQ: 0,
    pagedMode: false,
    timerInterval: null,
    showConfirmModal: false,
    showFiveMinuteWarning: false,
    fiveMinuteModalShown: false,

    init() {
        if (isNaN(parseInt(this.remainingSeconds))) {
            this.remainingSeconds = 0;
        }
        this.startTimer();
        // وقتی از پاسخنامه با $wire.goToQuestion میره، currentQ هم با Livewire آپدیت میشه
        window.addEventListener('exam-goto', (e) => {
            this.currentQ = e.detail.index;
        });
    },

    startTimer() {
        if (this.timerInterval) {
            clearInterval(this.timerInterval);
        }

        this.timerInterval = setInterval(() => {
            if (this.remainingSeconds > 0) {
                this.remainingSeconds--;

                if (this.remainingSeconds === 300 && !this.fiveMinuteModalShown) {
                    this.showFiveMinuteWarning = true;
                    this.fiveMinuteModalShown = true;
                }
            } else {
                clearInterval(this.timerInterval);
                $wire.timeExpired();
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
        <div class="sticky top-2 z-30 bg-secondary border border-border rounded-2xl p-4 shadow-lg shadow-black/5 backdrop-blur-sm">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div style="margin-right: 10px">
                        <h1 class="font-bold text-xl text-foreground">{{ $exam->title }}</h1>
                        <p class="text-sm text-muted">{{ $totalQuestions }} سوال</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-4">
                <div class="mt-4 flex flex-col sm:flex-row items-center justify-end gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 bg-background/80 border border-border rounded-2xl px-3 py-2">
                            <button type="button" class="relative inline-flex items-center gap-2" @click="showTimer = !showTimer">
                                <span class="flex items-center justify-center w-7 h-7 rounded-xl bg-primary/10 text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l2 2m-5-9h6M12 4a8 8 0 100 16 8 8 0 000-16z"/>
                                    </svg>
                                </span>

                                {{-- سوییچ بازنویسی‌شده --}}
                                <span dir="ltr"
                                      class="relative inline-flex items-center w-11 h-6 rounded-full transition-colors duration-200"
                                      :class="showTimer ? 'bg-primary' : 'bg-gray-300 dark:bg-gray-600'">
                                    <span class="absolute top-0.5 w-5 h-5 rounded-full bg-white shadow-md transition-transform duration-200"
                                          :class="showTimer ? 'translate-x-[22px]' : 'translate-x-[2px]'"></span>
                                </span>
                            </button>
                            <span class="text-xs sm:text-sm text-muted" x-text="showTimer ? 'عدم مشاهده زمان' : 'مشاهده زمان'">مشاهده زمان</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 transition-all duration-200" :class="showTimer ? 'timer-blur-glass' : ''">
                        <div class="flex flex-col items-center bg-background border border-border rounded-xl px-3 py-2 min-w-[50px]">
                            <span class="font-bold text-lg" :class="remainingSeconds < 60 ? 'text-red-500' : 'text-foreground'" x-text="formatTime(remainingSeconds).seconds"></span>
                            <span class="text-[10px] text-muted">ثانیه</span>
                        </div>
                        <span class="text-xl font-bold text-muted">:</span>
                        <div class="flex flex-col items-center bg-background border border-border rounded-xl px-3 py-2 min-w-[50px]">
                            <span class="font-bold text-lg text-foreground" x-text="formatTime(remainingSeconds).minutes"></span>
                            <span class="text-[10px] text-muted">دقیقه</span>
                        </div>
                        <span class="text-xl font-bold text-muted">:</span>
                        <div class="flex flex-col items-center bg-background border border-border rounded-xl px-3 py-2 min-w-[50px]">
                            <span class="font-bold text-lg text-foreground" x-text="formatTime(remainingSeconds).hours"></span>
                            <span class="text-[10px] text-muted">ساعت</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <!-- Filter and View Toggle -->
        <div class="bg-secondary border border-border rounded-2xl p-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4" dir="rtl">

                <!-- Filter Select -->
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <label class="text-sm text-muted whitespace-nowrap">فیلتر سوالات:</label>
                    <div class="flex-1 sm:flex-none sm:min-w-[220px]">
                        <x-ui.select wire:model.live="questionFilter"
                                     :options="[
                                         ['value' => 'all', 'label' => 'تمامی سوالات'],
                                         ['value' => 'unanswered', 'label' => 'سوالات بدون پاسخ'],
                                         ['value' => 'minus', 'label' => 'سوالات با علامت منها'],
                                         ['value' => 'circle', 'label' => 'سوالات با علامت دایره'],
                                         ['value' => 'close', 'label' => 'سوالات با علامت ضربدر'],
                                     ]"
                                     value-key="value" label-key="label" placeholder="انتخاب فیلتر..."/>
                    </div>
                </div>

                <!-- View Mode Toggle: pill style -->
                <div class="inline-flex items-center gap-1 p-1 bg-secondary/60 border border-border rounded-full">
                    <button type="button" wire:click="setViewMode('questions')"
                            wire:loading.attr="disabled"
                            wire:target="setViewMode,questionFilter"
                            class="relative inline-flex items-center gap-2 px-3 md:px-4 py-1.5 md:py-2 rounded-full text-xs md:text-sm font-medium transition-all disabled:opacity-60
                            {{ $viewMode === 'questions' ? 'bg-background text-primary shadow-sm' : 'text-foreground/70 hover:text-foreground' }}">
                        سوالات
                    </button>
                    <button type="button" wire:click="setViewMode('answersheet')"
                            wire:loading.attr="disabled"
                            wire:target="setViewMode,questionFilter"
                            class="relative inline-flex items-center gap-2 px-3 md:px-4 py-1.5 md:py-2 rounded-full text-xs md:text-sm font-medium transition-all disabled:opacity-60
                            {{ $viewMode === 'answersheet' ? 'bg-background text-primary shadow-sm' : 'text-foreground/70 hover:text-foreground' }}">
                        پاسخنامه
                    </button>
                </div>
            </div>
        </div>

        <br>

        <div class="relative">

            {{-- Loading overlay --}}
            <div wire:loading.flex
                 wire:target="setViewMode,questionFilter,goToQuestion"
                 class="absolute inset-0 z-20 hidden items-center justify-center bg-background/60 backdrop-blur-sm rounded-2xl">
                <div class="flex flex-col items-center gap-3 bg-secondary border border-border rounded-2xl px-6 py-4 shadow-xl">
                    <span class="w-8 h-8 rounded-full border-2 border-primary/30 border-t-primary animate-spin"></span>
                    <span class="text-xs text-muted">در حال بارگذاری...</span>
                </div>
            </div>

            @if($viewMode === 'questions')
                @if(count($questionsForView))
                    @php $totalShown = count($questionsForView); @endphp

                    {{-- توگل نمایش: همه سوالات / یک به یک --}}
                    <div class="max-w-5xl mx-auto mb-4 flex items-center justify-end gap-2" dir="rtl">
                        <span class="text-xs text-muted">نمایش:</span>
                        <div class="inline-flex items-center gap-1 p-1 bg-secondary/60 border border-border rounded-full">
                            <button type="button" @click="pagedMode = false"
                                    :class="!pagedMode ? 'bg-background text-primary shadow-sm' : 'text-foreground/70 hover:text-foreground'"
                                    class="px-3 py-1 rounded-full text-xs font-medium transition-all">
                                همه سوالات
                            </button>
                            <button type="button" @click="pagedMode = true"
                                    :class="pagedMode ? 'bg-background text-primary shadow-sm' : 'text-foreground/70 hover:text-foreground'"
                                    class="px-3 py-1 rounded-full text-xs font-medium transition-all">
                                یک به یک
                            </button>
                        </div>
                    </div>

                    {{-- نوار شماره سوالات (فقط در حالت یک‌به‌یک) --}}
                    <div x-show="pagedMode" x-cloak style="display: none;"
                         class="bg-secondary border border-border rounded-2xl p-3 mb-4 max-w-5xl mx-auto" dir="rtl">
                        <div class="flex items-center gap-2 overflow-x-auto pb-1">
                            @foreach($questionsForView as $i => $qItem)
                                @php $qMark = $qItem['mark']; @endphp
                                <button type="button"
                                        @click="currentQ = {{ $i }}"
                                        :class="currentQ === {{ $i }}
                                            ? 'bg-primary text-primary-foreground shadow-md shadow-primary/30'
                                            : '{{ $qMark === 'close' ? 'bg-red-500/10 text-red-500 border border-red-500/40' : ($qMark === 'minus' ? 'bg-amber-500/10 text-amber-500 border border-amber-500/40' : ($qMark === 'circle' ? 'bg-sky-500/10 text-sky-500 border border-sky-500/40' : 'bg-background border border-border text-foreground/70 hover:text-foreground')) }}'"
                                        class="flex-shrink-0 w-9 h-9 sm:w-10 sm:h-10 rounded-full font-bold text-xs sm:text-sm transition-all">
                                    {{ $i + 1 }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- سوالات: بسته به حالت همه یا یکی --}}
                    <div class="max-w-5xl mx-auto space-y-5">
                        @foreach($questionsForView as $i => $qItem)
                            @php
                                $question = $qItem['question'];
                                $options  = $qItem['options'];
                                $selected = $qItem['selected'];
                                $mark     = $qItem['mark'];
                                $index    = $qItem['index'];
                            @endphp

                            <div x-show="!pagedMode || currentQ === {{ $i }}" x-cloak
                                 class="bg-secondary border border-border rounded-2xl overflow-hidden">

                                {{-- هدر سوال --}}
                                <div class="relative bg-gradient-to-r from-primary/20 to-primary/5 p-3 sm:p-4">
                                    <div class="absolute top-0 left-0 right-0 h-1 bg-primary"></div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 bg-primary text-primary-foreground rounded-full font-bold text-sm sm:text-base">{{ $index + 1 }}</span>
                                            <span class="text-xs sm:text-sm text-muted">از {{ $totalQuestions }} سوال</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 sm:gap-2">
                                            <button wire:click="setQuestionMark({{ $question->id }}, 'close')"
                                                    class="w-8 h-8 rounded-full flex items-center justify-center transition-colors
                                                    {{ $mark === 'close' ? 'bg-red-500 text-white' : 'bg-background border border-border text-muted hover:border-red-500 hover:text-red-500' }}">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                            <button wire:click="setQuestionMark({{ $question->id }}, 'minus')"
                                                    class="w-8 h-8 rounded-full flex items-center justify-center transition-colors
                                                    {{ $mark === 'minus' ? 'bg-orange-500 text-white' : 'bg-background border border-border text-muted hover:border-yellow-500 hover:text-yellow-500' }}">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                </svg>
                                            </button>
                                            <button wire:click="setQuestionMark({{ $question->id }}, 'circle')"
                                                    class="w-8 h-8 rounded-full flex items-center justify-center transition-colors
                                                    {{ $mark === 'circle' ? 'bg-blue-500 text-white' : 'bg-background border border-border text-muted hover:border-blue-500 hover:text-blue-500' }}">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <circle cx="12" cy="12" r="8" stroke-width="2"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- بدنه سوال --}}
                                <div class="p-4 sm:p-5">
                                    @if($question->content?->question_image_url)
                                        <div class="mb-4 question-image-container">
                                            <img src="{{ $question->content->question_image_url }}" alt="تصویر سوال {{ $index + 1 }}" class="exam-question-img rounded-xl shadow-lg" loading="lazy">
                                        </div>
                                    @elseif($question->content?->body)
                                        <div class="prose text-white prose-sm dark:prose-invert max-w-none mb-4" dir="rtl">
                                            {!! $question->content?->body !!}
                                        </div>
                                    @else
                                        <div class="text-center py-8 text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <p>تصویر سوال موجود نیست</p>
                                        </div>
                                    @endif

                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 mt-4" dir="rtl">
                                        @foreach([1, 2, 3, 4] as $optNum)
                                            @php
                                                $isSelected = $selected === $optNum;
                                                $optionLabel = ['۱', '۲', '۳', '۴'][$optNum - 1];
                                            @endphp

                                            <button wire:click="selectAnswer({{ $question->id }}, {{ $optNum }})"
                                                    class="flex items-center justify-start gap-2 px-4 py-2.5 rounded-lg border transition-all
                                                    {{ $isSelected
                                                        ? 'border-primary/40 bg-primary/5 text-primary font-bold'
                                                        : 'border-border bg-background/50 text-foreground/80 hover:border-primary/30 hover:bg-background' }}">
                                                <span class="flex-shrink-0 w-4 h-4 rounded-[4px] transition-colors
                                                    {{ $isSelected ? 'bg-primary' : 'border border-border bg-background' }}"></span>
                                                <span class="text-sm">گزینه {{ $optionLabel }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- ناوبری قبلی/بعدی (فقط در حالت یک‌به‌یک) --}}
                    <div x-show="pagedMode" x-cloak
                         class="max-w-5xl mx-auto mt-4 flex items-center justify-between gap-3" dir="rtl">
                        <button type="button"
                                @click="currentQ = Math.max(0, currentQ - 1)"
                                :disabled="currentQ === 0"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            سوال قبلی
                        </button>

                        <span class="text-xs sm:text-sm text-muted font-semibold">
                            سوال <span class="text-foreground" x-text="currentQ + 1"></span> از {{ $totalShown }}
                        </span>

                        <button type="button"
                                @click="currentQ = Math.min({{ $totalShown - 1 }}, currentQ + 1)"
                                :disabled="currentQ === {{ $totalShown - 1 }}"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                            سوال بعدی
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                    </div>
                @else
                    <div class="bg-secondary border border-border rounded-2xl p-12 text-center max-w-5xl mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-muted mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-muted">سوالی با این فیلتر یافت نشد</p>
                    </div>
                @endif
            @else
                <!-- Answer Sheet View -->
                <div class="bg-secondary border border-border rounded-2xl p-4 sm:p-6" dir="rtl">
                    <h3 class="font-bold text-xl text-foreground mb-5 text-center">پاسخنامه</h3>

                    {{-- ═══ موبایل: لیست تک‌ستونه با کپسول آبی روشن (LTR) ═══ --}}
                    <div class="space-y-2 sm:hidden" dir="ltr">
                        @foreach($questionGrid as $item)
                            @php
                                $unanswered = !$item['is_answered'];
                                $mark = $item['mark'] ?? null;
                                $markBg = match($mark) {
                                    'minus'  => 'bg-amber-500',
                                    'circle' => 'bg-sky-500',
                                    'close'  => 'bg-red-500',
                                    default  => 'bg-transparent',
                                };
                            @endphp

                            <div class="w-full flex items-center gap-2 rounded-full pl-1.5 pr-2 py-1.5
                                        bg-sky-100/80 dark:bg-sky-950/30
                                        {{ $item['is_current'] ? 'ring-2 ring-primary' : '' }}">
                                {{-- شماره سوال (چپ - LTR) - کلیک = برو به سوال --}}
                                <button type="button"
                                        @click="currentQ = {{ $item['index'] }}; $wire.setViewMode('questions')"
                                        class="flex-shrink-0 w-7 text-center font-bold text-sm hover:text-primary transition-colors
                                               {{ $unanswered ? 'text-red-500' : 'text-foreground' }}">
                                    {{ $item['index'] + 1 }}
                                </button>

                                {{-- ۴ گزینه - کلیک = ثبت پاسخ مستقیم --}}
                                <div class="flex-1 flex items-center justify-between gap-1">
                                    @for($i = 1; $i <= 4; $i++)
                                        @php $isSelected = $item['selected_option'] === $i; @endphp
                                        <button type="button"
                                                wire:click="selectAnswer({{ $item['question_id'] }}, {{ $i }})"
                                                class="flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold transition-all
                                                       {{ $isSelected
                                                            ? 'bg-blue-500 text-white shadow-md shadow-blue-500/30 scale-105'
                                                            : 'border border-border bg-background/50 text-muted hover:border-primary/40 hover:text-foreground' }}">
                                            {{ $i }}
                                        </button>
                                    @endfor
                                </div>

                                {{-- نشانگر علامت (راست - LTR) - فقط نمایشی --}}
                                <span class="flex-shrink-0 w-3 h-7 rounded-md {{ $markBg }}"></span>
                            </div>
                        @endforeach
                    </div>

                    {{-- ═══ دسکتاپ: گرید چندستونه، ردیف‌های ساده با جداکننده عمودی (LTR) ═══ --}}
                    <div class="hidden sm:grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 sm:gap-x-6 sm:gap-y-1 divide-x divide-border/60" dir="ltr">
                        @foreach($questionGrid as $item)
                            @php
                                $unanswered = !$item['is_answered'];
                                $mark = $item['mark'] ?? null;
                                $markColor = match($mark) {
                                    'minus'  => 'text-amber-500',
                                    'circle' => 'text-sky-500',
                                    'close'  => 'text-red-500',
                                    default  => '',
                                };
                                $markSymbol = match($mark) {
                                    'minus'  => '−',
                                    'circle' => '○',
                                    'close'  => '×',
                                    default  => '',
                                };
                            @endphp

                            <div class="flex items-center gap-2 px-2 py-1.5 rounded-lg
                                        {{ $item['is_current'] ? 'bg-primary/5 ring-1 ring-primary/40' : '' }}">

                                {{-- شماره سوال (چپ - LTR) - کلیک = برو به سوال --}}
                                <button type="button"
                                        @click="currentQ = {{ $item['index'] }}; $wire.setViewMode('questions')"
                                        class="flex-shrink-0 w-8 text-center font-bold text-base hover:text-primary transition-colors
                                               {{ $unanswered ? 'text-red-500' : 'text-foreground' }}">
                                    {{ $item['index'] + 1 }}
                                </button>

                                {{-- ۴ گزینه - کلیک = ثبت پاسخ مستقیم --}}
                                <div class="flex-1 flex items-center justify-between gap-1.5">
                                    @for($i = 1; $i <= 4; $i++)
                                        @php $isSelected = $item['selected_option'] === $i; @endphp
                                        <button type="button"
                                                wire:click="selectAnswer({{ $item['question_id'] }}, {{ $i }})"
                                                class="flex items-center justify-center w-8 h-7 rounded-full text-xs font-bold border transition-all
                                                       {{ $isSelected
                                                            ? 'bg-blue-500 border-blue-500 text-white shadow-sm scale-105'
                                                            : 'border-border bg-background/30 text-muted hover:border-primary/40 hover:text-foreground' }}">
                                            {{ $i }}
                                        </button>
                                    @endfor
                                </div>

                                {{-- نشانگر علامت (راست - LTR) --}}
                                @if($mark)
                                    <span class="flex-shrink-0 w-5 text-center font-black text-base {{ $markColor }}" title="علامت سؤال">
                                        {{ $markSymbol }}
                                    </span>
                                @else
                                    <span class="flex-shrink-0 w-5"></span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <br>

        <div class="flex justify-end">
            <button type="button" x-on:click="showConfirmModal = true"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors shadow-md shadow-primary/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                ثبت نهایی
            </button>
        </div>

        <!-- Confirm Submit Modal (bottom sheet on mobile, centered on desktop) -->
        <div x-show="showConfirmModal" x-cloak
             class="fixed inset-0 z-50 flex flex-col justify-end sm:items-center sm:justify-center"
             x-transition.opacity>
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showConfirmModal = false"></div>

            <div class="relative w-full sm:max-w-md bg-secondary border-t sm:border border-border rounded-t-3xl sm:rounded-2xl flex flex-col pb-[env(safe-area-inset-bottom,0px)] sm:pb-0 shadow-xl"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-8">

                {{-- Handle bar (موبایل) --}}
                <div class="sm:hidden flex justify-center pt-3 pb-1 shrink-0">
                    <div class="w-10 h-1 rounded-full bg-foreground/20"></div>
                </div>

                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-bold text-lg text-primary">ثبت نهایی آزمون</h2>
                        <button type="button" class="text-muted hover:text-foreground" @click="showConfirmModal = false">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-muted mb-4 leading-relaxed">
                        آیا از ثبت نهایی آزمون اطمینان دارید؟<br>
                        پس از ثبت نهایی، امکان تغییر پاسخ‌ها وجود نخواهد داشت.
                    </p>
                    <hr class="my-4 border-border">
                    <div class="mb-2 text-sm leading-relaxed space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-foreground">تعداد کل سوالات:</span>
                            <span class="font-bold text-foreground">{{ $totalQuestions }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-foreground">سؤالات پاسخ داده شده:</span>
                            <span class="font-bold text-emerald-400">{{ $answeredCount }}</span>
                        </div>
                        @php $unanswered = max($totalQuestions - $answeredCount, 0); @endphp
                        <div class="flex items-center justify-between">
                            <span class="text-foreground">سؤالات بدون پاسخ:</span>
                            <span class="font-bold {{ $unanswered > 0 ? 'text-red-400' : 'text-emerald-400' }}">{{ $unanswered }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 border-t border-border p-4 pb-safe">
                    <button type="button"
                            class="flex-1 px-4 py-3 rounded-xl border border-border bg-background text-sm text-foreground hover:bg-secondary transition-colors font-semibold"
                            @click="showConfirmModal = false">
                        انصراف
                    </button>
                    <button type="button"
                            class="flex-1 px-4 py-3 rounded-xl bg-green-500 hover:bg-green-600 text-white text-sm font-semibold transition-colors"
                            @click="$wire.submitExam(); showConfirmModal = false">
                        بله، ثبت نهایی
                    </button>
                </div>
            </div>
        </div>

        <!-- Five-Minute Warning Modal (bottom sheet on mobile, centered on desktop) -->
        <div x-show="showFiveMinuteWarning" x-cloak
             class="fixed inset-0 z-40 flex flex-col justify-end sm:items-center sm:justify-center"
             x-transition.opacity>
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showFiveMinuteWarning = false"></div>

            <div class="relative w-full sm:max-w-md bg-secondary border-t sm:border border-border rounded-t-3xl sm:rounded-2xl flex flex-col pb-[env(safe-area-inset-bottom,0px)] sm:pb-0 shadow-xl"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-8">

                <div class="sm:hidden flex justify-center pt-3 pb-1 shrink-0">
                    <div class="w-10 h-1 rounded-full bg-foreground/20"></div>
                </div>

                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-bold text-lg text-amber-400">۵ دقیقه‌ی پایانی</h2>
                        <button type="button" class="text-muted hover:text-foreground" @click="showFiveMinuteWarning = false">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center justify-center mb-4">
                        <div class="flex items-center justify-center w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-muted leading-relaxed text-center">
                        فقط ۵ دقیقه تا پایان آزمون باقی مانده است.<br>
                        لطفاً پاسخ تمام سؤالات خود را بررسی و ثبت کنید.
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-border p-4 pb-safe">
                    <button type="button"
                            class="w-full px-4 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold transition-colors"
                            @click="showFiveMinuteWarning = false">
                        متوجه شدم
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('link')
        <style>
            [x-cloak] { display: none !important; }
            .timer-blur-glass {
                filter: blur(6px);
                -webkit-backdrop-filter: blur(8px);
                backdrop-filter: blur(8px);
                background-color: rgba(15, 23, 42, 0.35);
                border-radius: 1rem;
                pointer-events: none;
                user-select: none;
                transition: all 0.2s ease-in-out;
            }
            .prose img { max-width: 100%; height: auto; border-radius: 0.5rem; }
            .prose table { width: 100%; border-collapse: collapse; }
            .prose table th, .prose table td { border: 1px solid #e5e7eb; padding: 0.5rem; }
            .exam-question-img {
                display: block;
                width: 100%;
                height: auto;
                max-width: 100%;
                object-fit: contain;
                image-rendering: -webkit-optimize-contrast;
                image-rendering: crisp-edges;
            }
        </style>
    @endpush
</div>
