<div class="max-w-3xl mx-auto px-4 py-6 sm:py-10" dir="rtl">

    <div class="mb-6">
        <a href="{{ route('client.parent.assessment.list', ['token' => $token]) }}" wire:navigate
           class="text-sm text-base-content/70 hover:text-primary inline-flex items-center gap-1 mb-3">
            ← بازگشت به لیست تست‌ها
        </a>
        <h1 class="text-xl sm:text-2xl font-bold">{{ $assessment->name_fa }}</h1>
    </div>

    <div class="bg-base-200 rounded-xl p-3 mb-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-medium">سوال {{ $currentIndex }} از {{ $totalActive }}</span>
            <span class="text-xs text-primary font-bold">{{ $totalActive > 0 ? round(($attempt->answered_count / $totalActive) * 100) : 0 }}%</span>
        </div>
        <div class="w-full bg-base-300 rounded-full h-2 overflow-hidden">
            <div class="bg-primary h-2 transition-all"
                 style="width: {{ $totalActive > 0 ? round(($attempt->answered_count / $totalActive) * 100) : 0 }}%"></div>
        </div>
    </div>

    @if (! $question)
        <div class="bg-base-200 rounded-2xl p-8 text-center">
            <p class="text-base-content/70">سوالی برای نمایش وجود ندارد.</p>
        </div>
    @else
        <div class="bg-base-100 border border-base-300 rounded-2xl p-5 sm:p-7" wire:key="q-{{ $question->id }}">
            <p class="text-base sm:text-lg leading-8 mb-6 font-medium">{{ $question->question_text_fa }}</p>

            @error('answer')
                <div class="alert alert-error mb-4 text-sm">{{ $message }}</div>
            @enderror

            @if ($question->type === \App\Models\AssessmentQuestion::TYPE_LIKERT5)
                <div class="space-y-2">
                    @foreach ($question->options as $opt)
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-base-300 cursor-pointer hover:bg-base-200 transition
                                      {{ $likertValue === (string)$opt->value ? 'border-primary bg-primary/5' : '' }}">
                            <input type="radio" wire:model.live="likertValue" value="{{ $opt->value }}" class="radio radio-primary" />
                            <span class="text-sm sm:text-base">{{ $opt->label_fa }}</span>
                        </label>
                    @endforeach
                </div>
            @else
                <div class="space-y-2">
                    @foreach ($question->options as $opt)
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-base-300 cursor-pointer hover:bg-base-200 transition
                                      {{ (int)$selectedOptionId === $opt->id ? 'border-primary bg-primary/5' : '' }}">
                            <input type="radio" wire:model.live="selectedOptionId" value="{{ $opt->id }}" class="radio radio-primary" />
                            <span class="text-sm sm:text-base">{{ $opt->label_fa }}</span>
                        </label>
                    @endforeach
                </div>
            @endif

            <div class="mt-7 flex flex-col-reverse sm:flex-row sm:justify-between gap-3">
                <a href="{{ route('client.parent.assessment.list', ['token' => $token]) }}" wire:navigate
                   class="btn btn-ghost btn-sm">بازگشت</a>
                <button type="button" wire:click="submitAnswer" wire:loading.attr="disabled" class="btn btn-primary">
                    <span wire:loading.remove wire:target="submitAnswer">
                        @if ($attempt->answered_count + 1 >= $totalActive)
                            ثبت پاسخ و تکمیل
                        @else
                            ثبت و سوال بعدی
                        @endif
                    </span>
                    <span wire:loading wire:target="submitAnswer">در حال ذخیره...</span>
                </button>
            </div>
        </div>
    @endif
</div>
