<div
    x-data="{
        answers: @entangle('answers').live,
        totalQ: {{ $totalQuestions }},
        get answeredCount() {
            return Object.values(this.answers).filter(v => {
                if (Array.isArray(v)) return v.length > 0;
                return v !== null && v !== '' && v !== undefined;
            }).length;
        },
        get pct() {
            return this.totalQ > 0 ? Math.min(100, Math.round((this.answeredCount / this.totalQ) * 100)) : 0;
        }
    }"
    class="min-h-screen bg-[#0a0a0f] text-white relative overflow-x-hidden"
    dir="rtl">

    {{-- ─── خطوط پس‌زمینه (grid) ─── --}}
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background-image: linear-gradient(to right, rgba(59,130,246,0.06) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(59,130,246,0.06) 1px, transparent 1px);
                background-size: 48px 48px;">
    </div>
    {{-- radial fade --}}
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(59,130,246,0.12) 0%, transparent 70%);">
    </div>
    {{-- blob پس‌زمینه --}}
    <div class="fixed top-0 right-0 w-[600px] h-[600px] rounded-full pointer-events-none z-0"
         style="background: radial-gradient(circle, rgba(59,130,246,0.08) 0%, transparent 70%); transform: translate(30%, -40%);"></div>
    <div class="fixed bottom-0 left-0 w-[500px] h-[500px] rounded-full pointer-events-none z-0"
         style="background: radial-gradient(circle, rgba(139,92,246,0.06) 0%, transparent 70%); transform: translate(-30%, 40%);"></div>

    <div class="relative z-10 max-w-3xl mx-auto px-4 py-8 pb-32">

        {{-- ─── هدر ─── --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                     style="background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.3);">
                    <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h1 class="font-black text-lg text-white leading-tight">تست‌های والدینی</h1>
                    <p class="text-xs text-white/40 mt-0.5">نظر شما برای طراحی برنامه‌ی فرزندتان ارزشمند است</p>
                </div>
            </div>
            {{-- progress badge --}}
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full"
                 style="background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.25);">
                <div class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></div>
                <span class="text-xs font-bold text-blue-300" x-text="`${pct}% تکمیل شده`"></span>
            </div>
        </div>

        {{-- ─── Progress bar ─── --}}
        <div class="mb-8 rounded-2xl p-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-xs text-white/50">پیشرفت پاسخ‌دهی</span>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold text-white" x-text="`${answeredCount} / {{ $totalQuestions }}`"></span>
                    <span class="text-xs font-black text-blue-400" x-text="`${pct}%`"></span>
                </div>
            </div>
            <div class="h-2.5 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.06);">
                <div class="h-full rounded-full transition-all duration-700"
                     style="background: linear-gradient(to left, #3b82f6, #8b5cf6);"
                     :style="`width: ${pct}%`"></div>
            </div>
        </div>

        @error('submit')
        <div class="mb-6 flex items-center gap-3 px-4 py-3 rounded-xl"
             style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3);">
            <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-7.4 12.82A1 1 0 003.75 18h16.5a1 1 0 00.86-1.32l-7.4-12.82a1 1 0 00-1.72 0z"/>
            </svg>
            <p class="text-sm text-red-300">{{ $message }}</p>
        </div>
        @enderror

        {{-- ─── تست‌ها ─── --}}
        @foreach($assessments as $ai => $assessment)
            {{-- عنوان تست --}}
            <div class="flex items-center gap-3 mb-5 {{ $ai > 0 ? 'mt-10' : '' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-black"
                     style="background: rgba(59,130,246,0.2); border: 1px solid rgba(59,130,246,0.3); color: #60a5fa;">
                    {{ $ai + 1 }}
                </div>
                <div>
                    <h2 class="font-black text-base text-white">{{ $assessment->name_fa }}</h2>
                    @if($assessment->description_fa)
                        <p class="text-xs text-white/40 mt-0.5">{{ $assessment->description_fa }}</p>
                    @endif
                </div>
                <div class="mr-auto flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold"
                     style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.4);">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    {{ $assessment->questions->count() }} سوال
                </div>
            </div>

            {{-- سوالات --}}
            <div class="space-y-4">
                @foreach($assessment->questions as $qi => $question)
                    @php $key = 'q_' . $question->id; @endphp

                    <div class="rounded-2xl overflow-hidden transition-all duration-300"
                         style="background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.06);"
                         :class="answers['{{ $key }}'] && (Array.isArray(answers['{{ $key }}']) ? answers['{{ $key }}'].length > 0 : answers['{{ $key }}'] !== '') ? 'ring-1 ring-blue-500/30' : ''">

                        {{-- هدر سوال --}}
                        <div class="px-5 py-4" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <div class="flex items-start gap-3">
                                {{-- شماره سوال --}}
                                <span class="flex-shrink-0 w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black mt-0.5 transition-all duration-300"
                                      :class="answers['{{ $key }}'] && (Array.isArray(answers['{{ $key }}']) ? answers['{{ $key }}'].length > 0 : answers['{{ $key }}'] !== '')
                                          ? 'bg-blue-500 text-white'
                                          : 'bg-white/5 text-white/30'">
                                    <template x-if="answers['{{ $key }}'] && (Array.isArray(answers['{{ $key }}']) ? answers['{{ $key }}'].length > 0 : answers['{{ $key }}'] !== '')">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </template>
                                    <template x-if="!answers['{{ $key }}'] || (Array.isArray(answers['{{ $key }}']) ? answers['{{ $key }}'].length === 0 : answers['{{ $key }}'] === '')">
                                        <span>{{ $qi + 1 }}</span>
                                    </template>
                                </span>
                                <p class="text-sm leading-7 text-white/85 font-medium flex-1">{{ $question->question_text_fa }}</p>
                            </div>
                        </div>

                        {{-- گزینه‌ها --}}
                        <div class="p-4 space-y-2">
                            @if($question->type === \App\Models\AssessmentQuestion::TYPE_LIKERT5)
                                {{-- Likert: ۵ دکمه افقی --}}
                                <div class="grid grid-cols-5 gap-2">
                                    @foreach($question->options as $opt)
                                        <label class="flex flex-col items-center gap-2 cursor-pointer group">
                                            <input type="radio"
                                                   wire:model.live="answers.{{ $key }}"
                                                   value="{{ $opt->value }}"
                                                   class="sr-only">
                                            <div class="w-full py-2.5 rounded-xl text-center text-xs font-bold transition-all duration-200 cursor-pointer select-none"
                                                 :class="answers['{{ $key }}'] == '{{ $opt->value }}'
                                                     ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/30 scale-105'
                                                     : 'text-white/50 hover:text-white/80 hover:scale-[1.02]'"
                                                 style="border: 1px solid rgba(255,255,255,0.08);"
                                                 :style="answers['{{ $key }}'] == '{{ $opt->value }}' ? 'border-color: transparent;' : ''"
                                                 @click="$wire.set('answers.{{ $key }}', '{{ $opt->value }}')">
                                                {{ $opt->label_fa }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                            @elseif($question->type === \App\Models\AssessmentQuestion::TYPE_VARK_MULTI)
                                {{-- Multi-select --}}
                                <p class="text-[10px] text-white/30 mb-2">می‌توانید چند گزینه انتخاب کنید</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach($question->options as $opt)
                                        <label class="flex items-center gap-3 px-4 py-3 rounded-xl cursor-pointer transition-all duration-200 select-none"
                                               :class="answers['{{ $key }}'] && answers['{{ $key }}'].includes({{ $opt->id }})
                                                   ? 'ring-1 ring-blue-500/50 text-white'
                                                   : 'text-white/60 hover:text-white/80'"
                                               style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07);"
                                               :style="answers['{{ $key }}'] && answers['{{ $key }}'].includes({{ $opt->id }}) ? 'background: rgba(59,130,246,0.12); border-color: rgba(59,130,246,0.3);' : ''"
                                               @click="
                                                   let arr = answers['{{ $key }}'] ? [...answers['{{ $key }}']] : [];
                                                   const idx = arr.indexOf({{ $opt->id }});
                                                   if (idx > -1) arr.splice(idx, 1); else arr.push({{ $opt->id }});
                                                   $wire.set('answers.{{ $key }}', arr);
                                               ">
                                            <div class="w-5 h-5 rounded-md flex items-center justify-center flex-shrink-0 transition-all duration-200"
                                                 :class="answers['{{ $key }}'] && answers['{{ $key }}'].includes({{ $opt->id }})
                                                     ? 'bg-blue-500'
                                                     : 'bg-white/5 border border-white/15'">
                                                <svg x-show="answers['{{ $key }}'] && answers['{{ $key }}'].includes({{ $opt->id }})"
                                                     class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium leading-snug">{{ $opt->label_fa }}</span>
                                        </label>
                                    @endforeach
                                </div>

                            @else
                                {{-- Single-select radio --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach($question->options as $opt)
                                        <label class="flex items-center gap-3 px-4 py-3 rounded-xl cursor-pointer transition-all duration-200 select-none"
                                               :class="answers['{{ $key }}'] == '{{ $opt->id }}'
                                                   ? 'ring-1 ring-blue-500/50 text-white'
                                                   : 'text-white/60 hover:text-white/80'"
                                               style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07);"
                                               :style="answers['{{ $key }}'] == '{{ $opt->id }}' ? 'background: rgba(59,130,246,0.12); border-color: rgba(59,130,246,0.3);' : ''"
                                               @click="$wire.set('answers.{{ $key }}', '{{ $opt->id }}')">
                                            <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-200"
                                                 :class="answers['{{ $key }}'] == '{{ $opt->id }}'
                                                     ? 'bg-blue-500'
                                                     : 'bg-white/5 border border-white/15'">
                                                <div x-show="answers['{{ $key }}'] == '{{ $opt->id }}'"
                                                     class="w-2 h-2 rounded-full bg-white"></div>
                                            </div>
                                            <span class="text-sm font-medium leading-snug">{{ $opt->label_fa }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        {{-- فاصله پایین برای نوار ثابت --}}
        <div class="h-24"></div>
    </div>

    {{-- ─── نوار ثبت نهایی ثابت ─── --}}
    <div class="fixed bottom-0 inset-x-0 z-50"
         style="background: rgba(10,10,15,0.9); backdrop-filter: blur(20px); border-top: 1px solid rgba(255,255,255,0.06);">
        <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            {{-- وضعیت --}}
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-all duration-300"
                     :class="pct >= 100 ? '' : (pct > 0 ? '' : '')"
                     :style="pct >= 100
                         ? 'background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3);'
                         : (pct > 0
                             ? 'background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.3);'
                             : 'background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08);')">
                    <template x-if="pct >= 100">
                        <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </template>
                    <template x-if="pct < 100 && pct > 0">
                        <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                        </svg>
                    </template>
                    <template x-if="pct === 0">
                        <svg class="w-5 h-5 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                        </svg>
                    </template>
                </div>
                <div>
                    <p class="text-[10px] text-white/40">وضعیت پاسخ‌دهی</p>
                    <p class="text-sm font-bold text-white" x-text="pct >= 100 ? 'همه سوالات پاسخ داده شد ✓' : (pct > 0 ? `${answeredCount} از {{ $totalQuestions }} سوال` : 'هنوز شروع نشده')"></p>
                </div>
            </div>

            {{-- دکمه ثبت --}}
            <button type="button"
                    wire:click="submitAll"
                    wire:loading.attr="disabled"
                    wire:target="submitAll"
                    :disabled="answeredCount === 0"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed"
                    :style="answeredCount > 0
                        ? 'background: linear-gradient(135deg, #3b82f6, #8b5cf6); color: white; box-shadow: 0 4px 20px rgba(59,130,246,0.35);'
                        : 'background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.4); border: 1px solid rgba(255,255,255,0.08);'">
                <span wire:loading.remove wire:target="submitAll" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ثبت نهایی
                </span>
                <span wire:loading wire:target="submitAll" class="inline-flex items-center gap-2">
                    <span class="inline-block w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
                    در حال ذخیره...
                </span>
            </button>
        </div>
    </div>

</div>
