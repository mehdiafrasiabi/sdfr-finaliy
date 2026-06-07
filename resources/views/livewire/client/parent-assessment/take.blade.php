<div
    x-data="{
        current: 0,
        answers: {},
        justPicked: null,
        submitting: false,

        questions: @js($questions->map(fn($q) => [
            'id'             => $q->id,
            'text'           => $q->question_text_fa,
            'type'           => $q->type,
            'assessmentName' => $q->_assessment_name,
            'options'        => $q->options->map(fn($o) => [
                'id'    => $o->id,
                'label' => $o->label_fa,
                'value' => (string)($o->value ?? $o->id),
            ])->values(),
        ])),

        get total()         { return this.questions.length; },
        get q()             { return this.questions[this.current] ?? null; },
        get answeredCount() { return Object.keys(this.answers).length; },
        get pct()           { return this.total > 0 ? Math.round((this.answeredCount / this.total) * 100) : 0; },
        get isLast()        { return this.current === this.total - 1; },

        init() {
            const pre = @js($preAnswers);
            Object.entries(pre).forEach(([qId, ans]) => {
                this.answers[parseInt(qId)] = ans;
            });
        },

        pick(qId, optId, optValue) {
            this.answers[qId] = { optionId: optId, value: optValue };
            this.justPicked = qId;
            setTimeout(() => {
                this.justPicked = null;
                if (this.current < this.total - 1) this.current++;
            }, 350);
        },

        toggleMulti(qId, optId) {
            let cur = this.answers[qId]?.multi ? [...this.answers[qId].multi] : [];
            const idx = cur.indexOf(optId);
            if (idx > -1) cur.splice(idx, 1); else cur.push(optId);
            this.answers[qId] = { multi: cur, optionId: cur[0] ?? null, value: null };
        },

        prev() { if (this.current > 0) this.current--; },
        next() { if (this.current < this.total - 1) this.current++; },

        isAnswered(qId) { return this.answers[qId] !== undefined && this.answers[qId] !== null; },
        pickedOption(qId) { return this.answers[qId]?.optionId ?? null; },
        hasMulti(qId, optId) { return this.answers[qId]?.multi?.includes(optId) ?? false; },

        async submitAll() {
            if (this.submitting) return;
            this.submitting = true;
            const payload = Object.entries(this.answers).map(([qId, ans]) => ({
                qId:      parseInt(qId),
                optionId: ans.optionId ?? null,
                value:    ans.value    ?? null,
                multi:    ans.multi    ?? null,
            }));
            await $wire.submitAll(payload);
            this.submitting = false;
        },
    }"
    x-init="init()"
    class="min-h-screen bg-[#0a0a0f] text-white relative overflow-x-hidden"
    dir="rtl">

    {{-- Grid background --}}
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background-image:linear-gradient(to right,rgba(59,130,246,0.05) 1px,transparent 1px),
                linear-gradient(to bottom,rgba(59,130,246,0.05) 1px,transparent 1px);
                background-size:48px 48px;"></div>
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background:radial-gradient(ellipse 70% 50% at 50% 0%,rgba(59,130,246,0.10) 0%,transparent 70%);"></div>

    <div class="relative z-10 max-w-2xl mx-auto px-4 py-8 pb-36">

        {{-- هدر --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('client.parent.assessment.list', ['token' => $token]) }}" wire:navigate
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-white/50 hover:text-white transition-colors"
               style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
                بازگشت
            </a>
            {{-- نام آزمون جاری --}}
            <div x-show="q" class="flex items-center gap-2 px-3 py-1.5 rounded-full max-w-[60%]"
                 style="background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.2);">
                <div class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse flex-shrink-0"></div>
                <span class="text-xs font-bold text-blue-300 truncate" x-text="q?.assessmentName ?? ''"></span>
            </div>
        </div>

        {{-- ─── Progress bar ─── --}}
        <div class="rounded-2xl p-4 mb-5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
            {{-- اعداد --}}
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-white/40">
                    سوال <span class="text-white font-bold" x-text="current + 1"></span>
                    از
                    <span class="text-white font-bold">{{ $totalCount }}</span>
                </span>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-white/35" x-text="`${answeredCount} پاسخ داده شده`"></span>
                    <span class="text-xs font-black text-blue-400" x-text="`${pct}%`"></span>
                </div>
            </div>

            {{-- نوار --}}
            <div class="h-2 rounded-full overflow-hidden mb-3" style="background:rgba(255,255,255,0.06);">
                <div class="h-full rounded-full transition-all duration-500"
                     style="background:linear-gradient(to left,#3b82f6,#8b5cf6);"
                     :style="`width:${pct}%`"></div>
            </div>

            {{-- نقطه‌های ناوبری (کل سوالات با شماره یکپارچه) --}}
            <div class="flex items-center gap-1 flex-wrap">
                <template x-for="(qq, i) in questions" :key="qq.id">
                    <button @click="current = i"
                            class="w-6 h-6 rounded-md text-[9px] font-bold transition-all duration-200 hover:scale-110"
                            :class="i === current ? 'scale-110' : ''"
                            :style="isAnswered(qq.id)
                                ? 'background:rgba(59,130,246,0.75);color:white;'
                                : (i === current
                                    ? 'background:rgba(255,255,255,0.18);color:white;border:1px solid rgba(255,255,255,0.35);'
                                    : 'background:rgba(255,255,255,0.04);color:rgba(255,255,255,0.2);border:1px solid rgba(255,255,255,0.06);')"
                            x-text="i + 1">
                    </button>
                </template>
            </div>
        </div>

        {{-- ─── کارت سوال ─── --}}
        <template x-if="q">
            <div>
                <div class="rounded-2xl overflow-hidden transition-all duration-250"
                     style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.07);"
                     :style="justPicked === q.id
                         ? 'border-color:rgba(59,130,246,0.55);background:rgba(59,130,246,0.07);transform:scale(0.995);'
                         : ''">

                    {{-- سربرگ سوال --}}
                    <div class="px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05);">
                        <div class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black mt-0.5 transition-all duration-300"
                                  :style="isAnswered(q.id)
                                      ? 'background:#3b82f6;color:white;'
                                      : 'background:rgba(59,130,246,0.2);border:1px solid rgba(59,130,246,0.35);color:#60a5fa;'"
                                  x-text="isAnswered(q.id) ? '✓' : (current + 1)">
                            </span>
                            <p class="text-sm leading-7 text-white/85 font-medium flex-1" x-text="q.text"></p>
                        </div>
                    </div>

                    {{-- گزینه‌ها (همه زیر هم، تک ستون) --}}
                    <div class="p-4 space-y-2">

                        {{-- ── Likert ── --}}
                        <template x-if="q.type === 'likert5'">
                            <div class="space-y-2">
                                <template x-for="opt in q.options" :key="opt.id">
                                    <button type="button"
                                            @click="pick(q.id, opt.id, opt.value)"
                                            class="w-full flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-200"
                                            :class="justPicked === q.id && pickedOption(q.id) === opt.id ? 'scale-[0.97]' : 'hover:scale-[1.005] active:scale-[0.98]'"
                                            :style="pickedOption(q.id) === opt.id
                                                ? 'background:rgba(59,130,246,0.18);border:1.5px solid rgba(59,130,246,0.5);'
                                                : 'background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);'">
                                        <div class="w-5 h-5 rounded-full flex-shrink-0 flex items-center justify-center transition-all duration-200"
                                             :style="pickedOption(q.id) === opt.id
                                                 ? 'background:#3b82f6;'
                                                 : 'background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.18);'">
                                            <template x-if="pickedOption(q.id) === opt.id">
                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </template>
                                        </div>
                                        <span class="text-sm font-medium flex-1 text-right"
                                              :class="pickedOption(q.id) === opt.id ? 'text-white' : 'text-white/60'"
                                              x-text="opt.label"></span>
                                    </button>
                                </template>
                            </div>
                        </template>

                        {{-- ── Single select ── --}}
                        <template x-if="q.type !== 'likert5' && q.type !== 'vark_multi'">
                            <div class="space-y-2">
                                <template x-for="opt in q.options" :key="opt.id">
                                    <button type="button"
                                            @click="pick(q.id, opt.id, opt.value)"
                                            class="w-full flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-200"
                                            :class="justPicked === q.id && pickedOption(q.id) === opt.id ? 'scale-[0.97]' : 'hover:scale-[1.005] active:scale-[0.98]'"
                                            :style="pickedOption(q.id) === opt.id
                                                ? 'background:rgba(59,130,246,0.18);border:1.5px solid rgba(59,130,246,0.5);'
                                                : 'background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);'">
                                        <div class="w-5 h-5 rounded-full flex-shrink-0 flex items-center justify-center transition-all duration-200"
                                             :style="pickedOption(q.id) === opt.id
                                                 ? 'background:#3b82f6;'
                                                 : 'background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.18);'">
                                            <template x-if="pickedOption(q.id) === opt.id">
                                                <div class="w-2 h-2 rounded-full bg-white"></div>
                                            </template>
                                        </div>
                                        <span class="text-sm font-medium flex-1 text-right"
                                              :class="pickedOption(q.id) === opt.id ? 'text-white' : 'text-white/60'"
                                              x-text="opt.label"></span>
                                    </button>
                                </template>
                            </div>
                        </template>

                        {{-- ── Multi select ── --}}
                        <template x-if="q.type === 'vark_multi'">
                            <div class="space-y-2">
                                <p class="text-[11px] text-white/30 mb-2 px-1">می‌توانید چند گزینه انتخاب کنید</p>
                                <template x-for="opt in q.options" :key="opt.id">
                                    <button type="button"
                                            @click="toggleMulti(q.id, opt.id)"
                                            class="w-full flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all duration-200 hover:scale-[1.005] active:scale-[0.98]"
                                            :style="hasMulti(q.id, opt.id)
                                                ? 'background:rgba(59,130,246,0.18);border:1.5px solid rgba(59,130,246,0.5);'
                                                : 'background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);'">
                                        <div class="w-5 h-5 rounded-md flex-shrink-0 flex items-center justify-center transition-all duration-200"
                                             :style="hasMulti(q.id, opt.id)
                                                 ? 'background:#3b82f6;'
                                                 : 'background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.18);'">
                                            <template x-if="hasMulti(q.id, opt.id)">
                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </template>
                                        </div>
                                        <span class="text-sm font-medium flex-1 text-right"
                                              :class="hasMulti(q.id, opt.id) ? 'text-white' : 'text-white/60'"
                                              x-text="opt.label"></span>
                                    </button>
                                </template>
                            </div>
                        </template>

                    </div>
                </div>

                {{-- راهنما زیر کارت --}}
                <p class="text-center text-[11px] mt-3 h-4 transition-all duration-300"
                   :class="isAnswered(q.id) ? 'text-emerald-500/60' : 'text-white/20'"
                   x-text="isAnswered(q.id) ? '✓ پاسخ ذخیره شد — می‌توانید ادامه دهید' : 'برای رفتن به سوال بعدی، یک گزینه انتخاب کنید'">
                </p>
            </div>
        </template>

    </div>

    {{-- ─── نوار پایین ثابت ─── --}}
    <div class="fixed bottom-0 inset-x-0 z-50"
         style="background:rgba(10,10,15,0.95);backdrop-filter:blur(24px);border-top:1px solid rgba(255,255,255,0.07);">
        <div class="max-w-2xl mx-auto px-4 py-3 flex items-center gap-3">

            {{-- قبلی --}}
            <button @click="prev"
                    :disabled="current === 0"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 disabled:opacity-25 disabled:cursor-not-allowed hover:scale-[1.02] active:scale-[0.97]"
                    style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.09);color:rgba(255,255,255,0.6);">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
                قبلی
            </button>

            {{-- وسط --}}
            <div class="flex-1 flex justify-center">

                {{-- بعدی --}}
                <button x-show="!isLast"
                        @click="next"
                        class="inline-flex items-center gap-1.5 px-6 py-2.5 rounded-xl text-sm font-bold text-white transition-all duration-200 hover:scale-[1.02] active:scale-[0.97]"
                        style="background:rgba(59,130,246,0.22);border:1px solid rgba(59,130,246,0.35);">
                    بعدی
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                    </svg>
                </button>

                {{-- ثبت نهایی (فقط آخرین سوال) --}}
                <button x-show="isLast"
                        x-cloak
                        @click="submitAll"
                        :disabled="submitting || answeredCount === 0"
                        class="inline-flex items-center gap-2 px-7 py-2.5 rounded-xl text-sm font-bold text-white transition-all duration-200 hover:scale-[1.02] active:scale-[0.97] disabled:opacity-40 disabled:cursor-not-allowed"
                        style="background:linear-gradient(135deg,#3b82f6,#8b5cf6);box-shadow:0 4px 20px rgba(59,130,246,0.35);">

                    <template x-if="!submitting">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            ثبت نهایی
                        </span>
                    </template>
                    <template x-if="submitting">
                        <span class="flex items-center gap-2">
                            <span class="inline-block w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
                            در حال ثبت...
                        </span>
                    </template>
                </button>
            </div>

            {{-- شمارنده کل --}}
            <div class="text-xs text-white/25 font-mono min-w-[40px] text-left"
                 x-text="`${current + 1} / {{ $totalCount }}`"></div>
        </div>
    </div>

</div>
