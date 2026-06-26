<div
    x-data="{
        ratings: @entangle('ratings').live,
        activeSubject: 0,
        setRating(key, kind, id, value) {
            this.ratings[key] = value;
            $wire.setRating(id, value, kind);
        },
        clearRating(key, kind, id) {
            delete this.ratings[key];
            $wire.clearRating(id, kind);
        },

        /* ── رنگ‌بندی سطح‌ها (A=primary , B=green , C=amber , D=red) ── */
        levelColorClass(v, prop) {
            const map = {
                4: { bg: 'bg-primary',      border: 'border-primary',      text: 'text-primary'      },
                3: { bg: 'bg-emerald-500',  border: 'border-emerald-500',  text: 'text-emerald-500'  },
                2: { bg: 'bg-amber-500',    border: 'border-amber-500',    text: 'text-amber-500'    },
                1: { bg: 'bg-red-500',      border: 'border-red-500',      text: 'text-red-500'      },
            };
            return (map[v] && map[v][prop]) || '';
        },
        dotClass(sel)    { return this.levelColorClass(sel, 'bg') + ' ' + this.levelColorClass(sel, 'border'); },
        barClass(sel)    { return this.levelColorClass(sel, 'bg'); },
        letterClass(sel) { return this.levelColorClass(sel, 'text'); },
    }"
    dir="rtl"
    class="min-h-screen bg-background text-foreground"
>

    @push('link')
        <style>
            @keyframes fadeSlideUp {
                from { opacity: 0; transform: translateY(10px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            @keyframes dotPop {
                0%   { transform: scale(1); }
                40%  { transform: scale(1.35); }
                100% { transform: scale(1); }
            }
            .dot-pop     { animation: dotPop 0.3s cubic-bezier(0.34,1.56,0.64,1); }
            .row-enter   { animation: fadeSlideUp 0.3s ease backwards; }
            .progress-fill { transition: width 0.6s cubic-bezier(0.4,0,0.2,1); }

            /* hide horizontal scrollbar but keep scrolling */
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            .no-scrollbar::-webkit-scrollbar { display: none; }
        </style>
    @endpush

    <div class="max-w-4xl mx-auto px-4 pt-5 pb-28 lg:pb-8">

        {{-- ═══ Header ═══ --}}
        <div class="flex items-start justify-between gap-3 mb-6">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h1 class="text-lg font-black text-foreground">{{ $project->name }}</h1>
                </div>
                <p class="text-sm text-muted-foreground leading-relaxed pr-10">دروس را بر اساس آمادگی‌تان طبقه بندی کنید.</p>
            </div>
            @php
                // (E2) در حالتِ هفتهٔ آزمایشی، بازگشت به صفحهٔ راهنما (guide) — نه لیستِ پروژه‌ها.
                $u = auth()->user();
                $inTrial = $u && $u->trialWeek && ! $u->isSchoolStudent()
                    && ! ($u->student && $u->student->hasActivePaidAccess());
                $backRoute = $inTrial
                    ? route('client.profile.trial.guide')
                    : route('client.profile.classification.projects');
            @endphp
            <a wire:navigate href="{{ $backRoute }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-border bg-secondary text-xs font-semibold text-muted-foreground hover:text-foreground hover:bg-card transition-all shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3"/>
                </svg>
                بازگشت
            </a>
        </div>

        {{-- ═══ راهنما ═══ --}}
        <div dir="rtl" class="rounded-2xl border border-border bg-secondary p-4 mb-5">
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5 text-primary mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="text-sm text-muted leading-relaxed space-y-1">
                    <p>- در این صفحه دروس خود را بر اساس میزان <strong class="text-foreground">آمادگی</strong> طبقه‌بندی می‌کنی.</p>
                    <p>- ابتدا از بخش بالا <strong class="text-foreground">پایه</strong> و نوع درس (عمومی / تخصصی) را انتخاب کن.</p>
                    <p>- در دروس تخصصی، هر <strong class="text-foreground">فصل</strong> را جداگانه با یکی از سطح‌های <strong class="text-foreground">A تا D</strong> ارزیابی کن (A یعنی عالی و D یعنی ضعیف).</p>
                    <p>- دروس عمومی به‌صورت کلی و در یک ردیف طبقه‌بندی می‌شوند.</p>
                    <p>- برای پاک‌کردن یک امتیاز، روی همان نقطه‌ی انتخاب‌شده دوباره بزن.</p>
                    <p>- بعد از تکمیل همه‌ی دروس، طبقه‌بندی را ثبت نهایی کن.</p>
                </div>
            </div>
        </div>

        {{-- ═══ راهنمای امتیاز ═══ --}}
        <div class=" glass flex items-center gap-3 bg-secondary/60 border border-border rounded-2xl px-4 py-3 mb-5 flex-wrap">
            <span class="text-xs text-muted-foreground font-semibold">راهنمای امتیاز:</span>
            @php
                $grades = [
                    ['label'=>'A','color'=>'text-primary',    'desc'=>'عالی'],
                    ['label'=>'B','color'=>'text-emerald-500','desc'=>'خوب'],
                    ['label'=>'C','color'=>'text-amber-500',  'desc'=>'متوسط'],
                    ['label'=>'D','color'=>'text-red-500',    'desc'=>'ضعیف'],
                ];
            @endphp
            <div class="flex items-center gap-3 flex-wrap">
                @foreach($grades as $g)
                    <div class="flex items-center gap-1.5 border border-border rounded-2xl px-2 py-[3px]">
                        <span class="text-sm font-black {{ $g['color'] }}">{{ $g['label'] }}</span>
                        <span class="text-[10px] text-muted-foreground">{{ $g['desc'] }} </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ═══ فیلتر تب‌ها ═══ --}}
        <div class="mb-5">
            <div class="flex flex-col gap-2">
                @php $tagsByGrade = collect($availableTags)->groupBy('grade'); @endphp

                @foreach($tagsByGrade as $grade => $tags)
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-primary/10 border border-primary/20 text-white text-xs font-black shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                            </svg>
                            {{ ['10'=>'پایه دهم','11'=>'پایه یازدهم','12'=>'پایه دوازدهم'][$grade] ?? 'پایه '.$grade }}
                        </span>
                        <div class=" flex gap-2 flex-wrap flex-1">
                            @foreach($tags as $tag)
                                <button wire:click="selectTag('{{ $tag['id'] }}')" type="button"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border text-sm font-semibold transition-all duration-200 active:scale-95
                                               {{ $activeTag === $tag['id']
                                                   ? 'bg-primary text-primary-foreground border-primary shadow-sm'
                                                   : 'bg-secondary border-border text-muted-foreground hover:text-foreground hover:border-foreground/20' }}">
                                    {{ $tag['type'] === 'specialized' ? 'تخصصی' : 'عمومی' }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @php $activeType = $activeTag ? (explode('_', $activeTag)[1] ?? 'specialized') : 'specialized'; @endphp

        @if(!empty($subjects))

            @if($activeType === 'specialized')
                {{-- تب دروس -- استایل تب با خط زیرین --}}
                <div class="flex gap-1 border-b border-border mb-4 overflow-x-auto no-scrollbar -mx-4 px-4">
                    @foreach($subjects as $si => $subject)
                        @php
                            $ratedCount = collect($subject['chapters'])->filter(fn($c) => isset($this->ratings['chapter_'.$c['id']]))->count();
                            $totalCount = count($subject['chapters']);
                            $isComplete = $totalCount > 0 && $ratedCount === $totalCount;
                        @endphp
                        <button type="button" @click="activeSubject = {{ $si }}"
                                class="flex-shrink-0 inline-flex items-center gap-1.5 px-4 py-2 -mb-px text-sm font-semibold rounded-t-lg border-b-2 whitespace-nowrap transition-all duration-200 active:scale-95"
                                :class="activeSubject === {{ $si }}
                    ? 'bg-primary text-primary-foreground border-primary'
                    : 'bg-secondary border-transparent text-muted-foreground hover:text-foreground'">
                            {{ $subject['name'] }}
                            @if($isComplete)
                                <svg class="w-3.5 h-3.5 flex-shrink-0"
                                     :class="activeSubject === {{ $si }} ? 'text-white' : 'text-emerald-500'"
                                     fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            @elseif($ratedCount > 0)
                                <span class="inline-flex items-center justify-center w-5 h-5 text-[10px] font-black rounded-full bg-white/30">{{ $ratedCount }}</span>
                            @endif
                        </button>
                    @endforeach
                </div>

                {{-- لیست فصل‌ها --}}
                @foreach($subjects as $si => $subject)
                    <div x-show="activeSubject === {{ $si }}" x-cloak>
                        <div class="glass rounded-2xl border border-border overflow-hidden bg-card">
                            @forelse($subject['chapters'] as $ci => $chapter)
                                @php $key = 'chapter_' . $chapter['id']; @endphp
                                <div
                                    class="row-enter flex items-center justify-between gap-3 px-4 py-3.5 hover:bg-secondary/40 transition-colors
                                           {{ $ci < count($subject['chapters']) - 1 ? 'border-b border-border' : '' }}"
                                    style="animation-delay: {{ $ci * 0.025 }}s;"
                                >
                                    {{-- نام فصل --}}
                                    <div class="flex items-center gap-2.5 flex-1 min-w-0">
                                        <span class="text-sm text-foreground leading-snug truncate">{{ $chapter['name'] }}</span>
                                    </div>

                                    {{-- نقطه‌های متصل A B C D + حذف --}}
                                    @include('livewire.client.profile.classification.partials.rating-dots', [
                                        'key'  => $key,
                                        'kind' => 'chapter',
                                        'id'   => $chapter['id'],
                                    ])
                                </div>
                            @empty
                                <p class="text-center text-sm text-muted-foreground py-10">فصلی برای این درس یافت نشد.</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach

            @else
                {{-- ═══ دروس عمومی ═══ --}}

                {{-- باکس راهنمای دروس عمومی --}}
                <div class="glass flex items-start gap-3 bg-primary/5 border border-primary/15 rounded-2xl px-4 py-3 mb-4">
                    <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-foreground leading-relaxed font-medium">
                        دروس عمومی به‌صورت کلی طبقه‌بندی می‌شوند.
                    </p>
                </div>

                <div class="glass rounded-2xl border border-border overflow-hidden bg-card">
                    @foreach($subjects as $si => $subject)
                        @php $key = 'subject_' . $subject['id']; @endphp
                        <div
                            class="row-enter flex items-center justify-between gap-3 px-4 py-3.5 hover:bg-secondary/40 transition-colors
                                   {{ $si < count($subjects) - 1 ? 'border-b border-border' : '' }}"
                            style="animation-delay: {{ $si * 0.03 }}s;"
                        >
                            <div class="flex items-center gap-2.5 flex-1">
                                <span class="text-sm font-medium text-foreground">{{ $subject['name'] }}</span>
                            </div>

                            @include('livewire.client.profile.classification.partials.rating-dots', [
                                'key'  => $key,
                                'kind' => 'subject',
                                'id'   => $subject['id'],
                            ])
                        </div>
                    @endforeach
                </div>
            @endif

        @else
            <div class="flex flex-col items-center justify-center py-20 gap-4">
                <div class="w-16 h-16 rounded-2xl bg-secondary flex items-center justify-center">
                    <svg class="w-8 h-8 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                    </svg>
                </div>
                <p class="text-sm text-muted-foreground">موردی یافت نشد.</p>
            </div>
        @endif

        {{-- ═══ باکس ثبت نهایی (فقط دسکتاپ — پایین سمت چپ، زیر محتوا) ═══ --}}
        @php $pctDesk = $totalTopics > 0 ? min(100, round(($completedTopics / max($totalTopics,1)) * 100)) : 0; @endphp
        <div class="hidden lg:flex justify-end mt-8">
            <div class="glass w-full max-w-xs bg-card border border-border rounded-2xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-muted-foreground font-semibold">وضعیت طبقه‌بندی</span>
                    <span class="text-sm font-bold text-foreground tabular-nums">{{ $completedTopics }}/{{ $totalTopics }}</span>
                </div>
                <div class="h-2 w-full rounded-full bg-secondary overflow-hidden mb-4">
                    <div class="h-full rounded-full progress-fill {{ $pctDesk >= 100 ? 'bg-emerald-500' : 'bg-primary' }}"
                         style="width: {{ $pctDesk }}%"></div>
                </div>
                <button wire:click="openSubmitModal" type="button"
                        @if($completedTopics == 0) disabled @endif
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 active:scale-95
                               {{ $completedTopics > 0
                                   ? 'bg-emerald-500 text-white hover:bg-emerald-600 shadow-sm'
                                   : 'bg-secondary text-muted-foreground border border-border opacity-50 cursor-not-allowed' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    ثبت نهایی
                </button>
            </div>
        </div>

    </div>

    {{-- ═══ نوار ثبت نهایی (فقط موبایل/تبلت) ═══ --}}
    @php $pctBottom = $totalTopics > 0 ? min(100, round(($completedTopics / max($totalTopics,1)) * 100)) : 0; @endphp
    <div class="fixed bottom-0 inset-x-0 z-40 lg:hidden bg-card/95 backdrop-blur-sm border-t border-border">
        <div class="glass max-w-4xl mx-auto px-4 py-3 flex items-center gap-4">
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs text-muted-foreground font-semibold">وضعیت طبقه‌بندی</span>
                    <span class="text-xs font-bold text-foreground tabular-nums">{{ $completedTopics }}/{{ $totalTopics }}</span>
                </div>
                <div class="h-1.5 w-full rounded-full bg-secondary overflow-hidden">
                    <div class="h-full rounded-full progress-fill {{ $pctBottom >= 100 ? 'bg-emerald-500' : 'bg-primary' }}"
                         style="width: {{ $pctBottom }}%"></div>
                </div>
            </div>

            <button wire:click="openSubmitModal" type="button"
                    @if($completedTopics == 0) disabled @endif
                    class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 active:scale-95
                           {{ $completedTopics > 0
                               ? 'bg-emerald-500 text-white hover:bg-emerald-600 shadow-sm'
                               : 'bg-secondary text-muted-foreground border border-border opacity-50 cursor-not-allowed' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                ثبت نهایی
            </button>
        </div>
    </div>

    {{-- ═══ مودال تأیید ثبت ═══ --}}
    @if($showSubmitModal)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center px-4 bg-black/50 backdrop-blur-sm"
             wire:click.self="$set('showSubmitModal', false)">
            <div class="w-full max-w-sm bg-card border border-border rounded-2xl overflow-hidden shadow-2xl mb-4 sm:mb-0">
                <div class="flex items-center gap-3 px-5 py-4 border-b border-border">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-black text-foreground">تأیید ثبت نهایی</h3>
                </div>

                <div class="px-5 py-4">
                    <p class="text-sm text-muted-foreground leading-relaxed mb-1">
                        شما <span class="font-bold text-foreground">{{ $completedTopics }}</span> مورد از
                        <span class="font-bold text-foreground">{{ $totalTopics }}</span> مورد را امتیازدهی کرده‌اید.
                    </p>
                    @if($completedTopics < $totalTopics)
                        <div class="flex items-center gap-2 mt-3 px-3 py-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20">
                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-7.4 12.82A1 1 0 003.75 18h16.5a1 1 0 00.86-1.32l-7.4-12.82a1 1 0 00-1.72 0z"/></svg>
                            <span class="text-xs text-amber-600 dark:text-amber-400 font-semibold">{{ $totalTopics - $completedTopics }} مورد هنوز امتیازدهی نشده است.</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2 mt-3 px-3 py-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20">
                            <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold">همه موارد امتیازدهی شدند!</span>
                        </div>
                    @endif
                </div>

                <div class="flex gap-2.5 px-5 pb-5">
                    <button type="button" wire:click="$set('showSubmitModal', false)"
                            class="flex-1 py-2.5 rounded-xl border border-border bg-secondary text-sm font-semibold text-foreground hover:bg-card transition-colors">
                        انصراف
                    </button>
                    <button type="button" wire:click="submitClassification"
                            class="flex-1 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-sm font-bold text-white transition-colors flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="submitClassification">ثبت نهایی</span>
                        <span wire:loading wire:target="submitClassification" class="inline-block w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
