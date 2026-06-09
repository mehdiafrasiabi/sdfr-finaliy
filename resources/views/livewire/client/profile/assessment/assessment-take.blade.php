@php
    // آخرین سوال؟  اولین سوال؟
    $isLast  = $totalActive > 0 && $currentIndex >= $totalActive;
    $isFirst = $currentIndex <= 1;

    // مقدار اولیه‌ی انتخاب برای Alpine (تک‌انتخابی = عدد، لیکرت = رشته)
    if (!is_null($selectedOptionId ?? null)) {
        $pickedJs = (int) $selectedOptionId;
    } elseif (!is_null($likertValue ?? null) && $likertValue !== '') {
        $pickedJs = "'" . $likertValue . "'";
    } else {
        $pickedJs = 'null';
    }
@endphp

<div
    x-data="{
        picked: {{ $pickedJs }},
        flash: false,
        transitioning: false,
        isLast: @js($isLast),

        // انتخاب گزینه در سوالات تک‌انتخابی/لیکرت → علامت می‌خورد و خودکار می‌رود بعدی
        choose(prop, val, last) {
            if (this.transitioning) return;
            this.picked = val;
            this.flash = true;
            // مقدار را محلی ست می‌کنیم (بدون درخواست اضافه) تا با submit بعدی ارسال شود
            this.$wire.set(prop, val, false);

            if (last) {
                // در سوال آخر فقط علامت می‌خورد؛ کاربر خودش «ثبت نهایی» را می‌زند
                setTimeout(() => { this.flash = false; }, 300);
                return;
            }
            this.advance('submitAnswer');
        },

        // ناوبری با افکت ورق‌خوردن (قبلی / بعدی / خودکار)
        async advance(method) {
            if (this.transitioning) return;
            this.transitioning = true;             // محو شدن سوال فعلی (ورق رفتن)
            await new Promise(r => setTimeout(r, 220));
            await this.$wire[method]();            // ذخیره/جابجایی سمت سرور
            this.flash = false;
            this.transitioning = false;            // سوال جدید با انیمیشن qTurn وارد می‌شود
        },
    }"
    class="min-h-screen bg-[#0a0a0f] text-white relative overflow-x-hidden"
    dir="rtl">

    <style>
        /* ═══ گزینه‌ها ═══ */
        .opt-row { background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07); transition:background .18s ease, border-color .18s ease, transform .12s ease; }
        .opt-row:hover { transform: scale(1.004); }
        .opt-row:active { transform: scale(.985); }
        .opt-row.opt-sel { background:rgba(59,130,246,0.18); border:1.5px solid rgba(59,130,246,0.5); }
        .opt-dot { background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.18); }
        .opt-row.opt-sel .opt-dot { background:#3b82f6; border-color:#3b82f6; }

        /* ═══ افکت ورق‌خوردن سوال ═══ */
        .q-stage { transition: opacity .2s ease; }
        .q-perspective { perspective: 1200px; }
        @keyframes qTurn {
            0%   { opacity:0; transform: translateX(-26px) rotateY(14deg) scale(.985); }
            100% { opacity:1; transform: none; }
        }
        .q-turn { animation: qTurn .34s cubic-bezier(.16, 1, .3, 1) both; transform-origin: right center; backface-visibility: hidden; }

        @media (prefers-reduced-motion: reduce) {
            .q-turn { animation: none !important; }
            .q-stage { transition: none !important; }
        }
    </style>

    <div class="fixed inset-0 pointer-events-none z-0" style="background-image:linear-gradient(to right,rgba(59,130,246,0.05) 1px,transparent 1px),linear-gradient(to bottom,rgba(59,130,246,0.05) 1px,transparent 1px);background-size:48px 48px;"></div>
    <div class="fixed inset-0 pointer-events-none z-0" style="background:radial-gradient(ellipse 70% 50% at 50% 0%,rgba(59,130,246,0.10) 0%,transparent 70%);"></div>

    <div class="relative z-10 max-w-2xl mx-auto px-4 py-8 pb-32">

        {{-- هدر (خروج به لیست + نام آزمون) --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('client.profile.assessment.list') }}" wire:navigate
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-white/50 hover:text-white transition-colors"
               style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                خروج
            </a>
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full" style="background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.2);">
                <div class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></div>
                <span class="text-xs font-bold text-blue-300">{{ $assessment->name_fa }}</span>
            </div>
        </div>

        {{-- progress --}}
        <div class="rounded-2xl p-4 mb-5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-white/40">سوال <span class="text-white font-bold">{{ $currentIndex }}</span> از <span class="text-white font-bold">{{ $totalActive }}</span></span>
                <span class="text-xs font-black text-blue-400">{{ $totalActive > 0 ? round(($currentIndex / $totalActive) * 100) : 0 }}%</span>
            </div>
            <div class="h-2 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06);">
                <div class="h-full rounded-full transition-all duration-500" style="background:linear-gradient(to left,#3b82f6,#0ea5e9);width:{{ $totalActive > 0 ? round(($currentIndex / $totalActive) * 100) : 0 }}%;"></div>
            </div>
        </div>

        @if(!$question)
            <div class="flex flex-col items-center py-16 text-center gap-3">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background:rgba(255,255,255,0.05);">
                    <svg class="w-7 h-7 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
                </div>
                <p class="text-sm text-white/40">سوالی برای نمایش وجود ندارد.</p>
            </div>
        @else
            {{-- صحنه‌ی سوال: محو‌شدن هنگام عبور + ورود سوال جدید با ورق‌خوردن --}}
            <div class="q-perspective">
                <div class="q-stage" :class="transitioning ? 'opacity-0' : 'opacity-100'">
                    <div class="q-turn rounded-2xl overflow-hidden"
                         style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.07);"
                         :style="flash ? 'border-color:rgba(59,130,246,0.5);background:rgba(59,130,246,0.06);' : ''"
                         wire:key="q-{{ $question->id }}">

                        <div class="px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05);">
                            <div class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black mt-0.5"
                                      style="background:rgba(59,130,246,0.2);border:1px solid rgba(59,130,246,0.35);color:#60a5fa;">{{ $currentIndex }}</span>
                                <p class="text-sm leading-7 text-white/85 font-medium flex-1">{{ $question->question_text_fa }}</p>
                            </div>
                        </div>

                        <div class="p-4 space-y-2">
                            @error('answer')
                            <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl mb-3" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);">
                                <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <span class="text-xs text-red-300">{{ $message }}</span>
                            </div>
                            @enderror

                            @if($question->type === \App\Models\AssessmentQuestion::TYPE_LIKERT5)
                                {{-- لیکرت: انتخاب → خودکار بعدی --}}
                                @foreach($question->options as $opt)
                                    <button type="button"
                                            @click="choose('likertValue', '{{ $opt->value }}', isLast)"
                                            class="opt-row w-full flex items-center gap-3 px-4 py-3.5 rounded-xl"
                                            :class="picked === '{{ $opt->value }}' ? 'opt-sel' : ''">
                                        <span class="opt-dot w-5 h-5 rounded-full flex-shrink-0 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" x-show="picked === '{{ $opt->value }}'" x-cloak fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        </span>
                                        <span class="text-sm font-medium text-right flex-1" :class="picked === '{{ $opt->value }}' ? 'text-white' : 'text-white/60'">{{ $opt->label_fa }}</span>
                                    </button>
                                @endforeach

                            @elseif($question->type === \App\Models\AssessmentQuestion::TYPE_VARK_MULTI)
                                {{-- چندانتخابی: بدون auto-advance؛ با دکمه‌ی بعدی جلو می‌رود --}}
                                <p class="text-[11px] text-white/30 mb-2 px-1">می‌توانید چند گزینه انتخاب کنید — سپس «بعدی» را بزنید</p>
                                @foreach($question->options as $opt)
                                    <label class="opt-row flex items-center gap-3 px-4 py-3.5 rounded-xl cursor-pointer {{ in_array($opt->id, $selectedOptionIds) ? 'opt-sel' : '' }}">
                                        <input type="checkbox" wire:model.live="selectedOptionIds" value="{{ $opt->id }}" class="sr-only">
                                        <span class="opt-dot w-5 h-5 rounded-md flex-shrink-0 flex items-center justify-center">
                                            @if(in_array($opt->id, $selectedOptionIds))
                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            @endif
                                        </span>
                                        <span class="text-sm font-medium text-right flex-1 {{ in_array($opt->id, $selectedOptionIds) ? 'text-white' : 'text-white/60' }}">{{ $opt->label_fa }}</span>
                                    </label>
                                @endforeach

                            @else
                                {{-- تک‌انتخابی: انتخاب → خودکار بعدی --}}
                                @foreach($question->options as $opt)
                                    <button type="button"
                                            @click="choose('selectedOptionId', {{ $opt->id }}, isLast)"
                                            class="opt-row w-full flex items-center gap-3 px-4 py-3.5 rounded-xl"
                                            :class="picked === {{ $opt->id }} ? 'opt-sel' : ''">
                                        <span class="opt-dot w-5 h-5 rounded-full flex-shrink-0 flex items-center justify-center">
                                            <span class="w-2 h-2 rounded-full bg-white" x-show="picked === {{ $opt->id }}" x-cloak></span>
                                        </span>
                                        <span class="text-sm font-medium text-right flex-1" :class="picked === {{ $opt->id }} ? 'text-white' : 'text-white/60'">{{ $opt->label_fa }}</span>
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- نوار پایین: فقط «قبلی» و «بعدی / ثبت نهایی» --}}
    @if($question)
        <div class="fixed bottom-0 inset-x-0 z-50" style="background:rgba(10,10,15,0.95);backdrop-filter:blur(24px);border-top:1px solid rgba(255,255,255,0.07);">
            <div class="max-w-2xl mx-auto px-4 py-3 flex items-center justify-between gap-3">

                {{-- قبلی --}}
                <button type="button"
                        @click="advance('goToPrevious')"
                        :disabled="transitioning"
                        @if($isFirst) disabled @endif
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl text-sm font-semibold text-white/60 transition-all duration-200 hover:text-white disabled:opacity-30 disabled:cursor-not-allowed"
                        style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    قبلی
                </button>

                {{-- بعدی / ثبت نهایی --}}
                <button type="button"
                        @click="advance('submitAnswer')"
                        :disabled="transitioning"
                        wire:loading.attr="disabled" wire:target="submitAnswer"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold text-white transition-all duration-200 hover:scale-[1.02] active:scale-[0.97] disabled:opacity-60"
                        style="background:linear-gradient(135deg,#3b82f6,#0ea5e9);box-shadow:0 4px 20px rgba(59,130,246,0.3);">
                    <span wire:loading.remove wire:target="submitAnswer" class="flex items-center gap-2">
                        @if($isLast)
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            ثبت نهایی
                        @else
                            بعدی
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                        @endif
                    </span>
                    <span wire:loading wire:target="submitAnswer" class="inline-flex items-center gap-2">
                        <span class="inline-block w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
                        ذخیره...
                    </span>
                </button>
            </div>
        </div>
    @endif
</div>
