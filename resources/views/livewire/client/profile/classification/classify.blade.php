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
    }"
    dir="rtl"
    class="min-h-screen bg-background text-foreground"
>

    @push('styles')
        <style>
            @keyframes fadeSlideUp {
                from { opacity: 0; transform: translateY(10px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            @keyframes letterPop {
                0%   { transform: scale(1); }
                40%  { transform: scale(1.18); }
                100% { transform: scale(1); }
            }
            .letter-pop  { animation: letterPop 0.3s cubic-bezier(0.34,1.56,0.64,1); }
            .row-enter   { animation: fadeSlideUp 0.3s ease backwards; }
            .progress-fill { transition: width 0.6s cubic-bezier(0.4,0,0.2,1); }

            /* hide horizontal scrollbar but keep scrolling */
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            .no-scrollbar::-webkit-scrollbar { display: none; }
        </style>
    @endpush

    <div class="max-w-4xl mx-auto px-4 pt-5 pb-28">

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
            <a wire:navigate href="{{ route('client.profile.classification.projects') }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-border bg-secondary text-xs font-semibold text-muted-foreground hover:text-foreground hover:bg-card transition-all shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3"/>
                </svg>
                بازگشت
            </a>
        </div>

        {{-- ═══ راهنمای امتیاز ═══ --}}
        <div class="flex items-center gap-3 bg-secondary/60 border border-border rounded-2xl px-4 py-3 mb-5 flex-wrap">
            <span class="text-xs text-muted-foreground font-semibold">راهنمای امتیاز:</span>
            @php
                $grades = [
                    ['label'=>'A','color'=>'text-emerald-500','desc'=>'عالی'],
                    ['label'=>'B','color'=>'text-blue-500',   'desc'=>'خوب'],
                    ['label'=>'C','color'=>'text-amber-500',  'desc'=>'متوسط'],
                    ['label'=>'D','color'=>'text-red-500',    'desc'=>'ضعیف'],
                ];
            @endphp
            <div class="flex items-center gap-3 flex-wrap">
                @foreach($grades as $g)
                    <div class="flex items-center gap-1.5">
                        <span class="text-sm font-black {{ $g['color'] }}">{{ $g['label'] }}</span>
                        <span class="text-[10px] text-muted-foreground">{{ $g['desc'] }}</span>
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
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-primary/10 border border-primary/20 text-primary text-xs font-black shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                            </svg>
                            {{ ['10'=>'پایه دهم','11'=>'پایه یازدهم','12'=>'پایه دوازدهم'][$grade] ?? 'پایه '.$grade }}
                        </span>
                        <div class="flex gap-2 flex-wrap flex-1">
                            @foreach($tags as $tag)
                                <button wire:click="selectTag('{{ $tag['id'] }}')" type="button"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border text-sm font-semibold transition-all duration-200 active:scale-95
                                               {{ $activeTag === $tag['id']
                                                   ? ($tag['type'] === 'specialized'
                                                       ? 'bg-primary text-primary-foreground border-primary shadow-sm'
                                                       : 'bg-emerald-500 text-white border-emerald-500 shadow-sm')
                                                   : 'bg-secondary border-border text-muted-foreground hover:text-foreground hover:border-foreground/20' }}">
                                    @if($tag['type'] === 'specialized')
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
                                        </svg>
                                    @else
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                                        </svg>
                                    @endif
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
                {{-- تب دروس -- اسکرول افقی بدون نمایش اسکرول‌بار --}}
                <div class="flex gap-2 mb-4 overflow-x-auto no-scrollbar pb-1 -mx-4 px-4">
                    @foreach($subjects as $si => $subject)
                        @php
                            $ratedCount = collect($subject['chapters'])->filter(fn($c) => isset($this->ratings['chapter_'.$c['id']]))->count();
                            $totalCount = count($subject['chapters']);
                            $isComplete = $totalCount > 0 && $ratedCount === $totalCount;
                        @endphp
                        <button type="button" @click="activeSubject = {{ $si }}"
                                class="flex-shrink-0 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border text-sm font-semibold transition-all duration-200 whitespace-nowrap active:scale-95"
                                :class="activeSubject === {{ $si }}
                                    ? 'bg-primary text-primary-foreground border-primary shadow-sm'
                                    : 'bg-secondary border-border text-muted-foreground hover:text-foreground hover:border-foreground/20'">
                            {{ $subject['name'] }}
                            @if($isComplete)
                                <svg class="w-3.5 h-3.5 text-emerald-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            @elseif($ratedCount > 0)
                                <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-white/20 text-[9px] font-black flex-shrink-0">{{ $ratedCount }}</span>
                            @endif
                        </button>
                    @endforeach
                </div>

                {{-- لیست فصل‌ها --}}
                @foreach($subjects as $si => $subject)
                    <div x-show="activeSubject === {{ $si }}" x-cloak>
                        <div class="rounded-2xl border border-border overflow-hidden bg-card">
                            @forelse($subject['chapters'] as $ci => $chapter)
                                @php $key = 'chapter_' . $chapter['id']; @endphp
                                <div
                                    class="row-enter flex items-center justify-between gap-3 px-4 py-3.5 hover:bg-secondary/40 transition-colors
                                           {{ $ci < count($subject['chapters']) - 1 ? 'border-b border-border' : '' }}"
                                    style="animation-delay: {{ $ci * 0.025 }}s;"
                                >
                                    {{-- نام فصل --}}
                                    <div class="flex items-center gap-2.5 flex-1 min-w-0">
                                        <span class="text-[11px] text-muted-foreground w-5 text-center font-mono flex-shrink-0">{{ $ci + 1 }}</span>
                                        <span class="text-sm text-foreground leading-snug truncate">{{ $chapter['name'] }}</span>
                                    </div>

                                    {{-- دکمه‌های A B C D + حذف --}}
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        @php
                                            $letters = [
                                                4 => ['label' => 'A', 'active' => 'bg-emerald-500 text-white border-emerald-500', 'idle' => 'text-emerald-500 border-emerald-500/30 hover:bg-emerald-500/10'],
                                                3 => ['label' => 'B', 'active' => 'bg-blue-500 text-white border-blue-500',       'idle' => 'text-blue-500 border-blue-500/30 hover:bg-blue-500/10'],
                                                2 => ['label' => 'C', 'active' => 'bg-amber-500 text-white border-amber-500',     'idle' => 'text-amber-500 border-amber-500/30 hover:bg-amber-500/10'],
                                                1 => ['label' => 'D', 'active' => 'bg-red-500 text-white border-red-500',         'idle' => 'text-red-500 border-red-500/30 hover:bg-red-500/10'],
                                            ];
                                        @endphp
                                        @foreach($letters as $val => $l)
                                            <button type="button"
                                                    @click="
                                                        setRating('{{ $key }}', 'chapter', {{ $chapter['id'] }}, {{ $val }});
                                                        $event.currentTarget.classList.add('letter-pop');
                                                        setTimeout(() => $event.currentTarget.classList.remove('letter-pop'), 350);
                                                    "
                                                    class="w-8 h-8 inline-flex items-center justify-center rounded-lg border text-xs font-black transition-all active:scale-90"
                                                    :class="ratings['{{ $key }}'] === {{ $val }} ? '{{ $l['active'] }} shadow-sm' : 'bg-transparent {{ $l['idle'] }}'"
                                                    title="{{ $l['label'] }}">
                                                {{ $l['label'] }}
                                            </button>
                                        @endforeach

                                        {{-- دکمه حذف --}}
                                        <template x-if="ratings['{{ $key }}']">
                                            <button type="button"
                                                    @click="clearRating('{{ $key }}', 'chapter', {{ $chapter['id'] }})"
                                                    class="w-7 h-7 mr-1 flex items-center justify-center rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 hover:text-red-500 transition-colors"
                                                    title="حذف امتیاز">
                                                <svg class="w-3.5 h-3.5 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-sm text-muted-foreground py-10">فصلی برای این درس یافت نشد.</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach

            @else
                {{-- دروس عمومی --}}
                <div class="rounded-2xl border border-border overflow-hidden bg-card">
                    @foreach($subjects as $si => $subject)
                        @php $key = 'subject_' . $subject['id']; @endphp
                        <div
                            class="row-enter flex items-center justify-between gap-3 px-4 py-3.5 hover:bg-secondary/40 transition-colors
                                   {{ $si < count($subjects) - 1 ? 'border-b border-border' : '' }}"
                            style="animation-delay: {{ $si * 0.03 }}s;"
                        >
                            <div class="flex items-center gap-2.5 flex-1">
                                <span class="text-[11px] text-muted-foreground w-5 text-center font-mono flex-shrink-0">{{ $si + 1 }}</span>
                                <span class="text-sm font-medium text-foreground">{{ $subject['name'] }}</span>
                            </div>

                            <div class="flex items-center gap-1.5 shrink-0">
                                @php
                                    $letters = [
                                        4 => ['label' => 'A', 'active' => 'bg-emerald-500 text-white border-emerald-500', 'idle' => 'text-emerald-500 border-emerald-500/30 hover:bg-emerald-500/10'],
                                        3 => ['label' => 'B', 'active' => 'bg-blue-500 text-white border-blue-500',       'idle' => 'text-blue-500 border-blue-500/30 hover:bg-blue-500/10'],
                                        2 => ['label' => 'C', 'active' => 'bg-amber-500 text-white border-amber-500',     'idle' => 'text-amber-500 border-amber-500/30 hover:bg-amber-500/10'],
                                        1 => ['label' => 'D', 'active' => 'bg-red-500 text-white border-red-500',         'idle' => 'text-red-500 border-red-500/30 hover:bg-red-500/10'],
                                    ];
                                @endphp
                                @foreach($letters as $val => $l)
                                    <button type="button"
                                            @click="
                                                setRating('{{ $key }}', 'subject', {{ $subject['id'] }}, {{ $val }});
                                                $event.currentTarget.classList.add('letter-pop');
                                                setTimeout(() => $event.currentTarget.classList.remove('letter-pop'), 350);
                                            "
                                            class="w-8 h-8 inline-flex items-center justify-center rounded-lg border text-xs font-black transition-all active:scale-90"
                                            :class="ratings['{{ $key }}'] === {{ $val }} ? '{{ $l['active'] }} shadow-sm' : 'bg-transparent {{ $l['idle'] }}'"
                                            title="{{ $l['label'] }}">
                                        {{ $l['label'] }}
                                    </button>
                                @endforeach

                                <template x-if="ratings['{{ $key }}']">
                                    <button type="button"
                                            @click="clearRating('{{ $key }}', 'subject', {{ $subject['id'] }})"
                                            class="w-7 h-7 mr-1 flex items-center justify-center rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-colors"
                                            title="حذف امتیاز">
                                        <svg class="w-3.5 h-3.5 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </template>
                            </div>
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

    </div>

    {{-- ═══ نوار ثبت نهایی ═══ --}}
    <div class="fixed bottom-0 inset-x-0 z-40 bg-card/95 backdrop-blur-sm border-t border-border">
        <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                @php $pctBottom = $totalTopics > 0 ? min(100, round(($completedTopics / max($totalTopics,1)) * 100)) : 0; @endphp
                <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center
                    {{ $pctBottom >= 100 ? 'bg-emerald-500/15' : ($pctBottom > 0 ? 'bg-primary/10' : 'bg-secondary') }}">
                    @if($pctBottom >= 100)
                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    @elseif($pctBottom > 0)
                        <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                    @else
                        <svg class="w-5 h-5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                    @endif
                </div>
                <div>
                    <div class="text-xs text-muted-foreground">وضعیت ثبت</div>
                    <div class="text-sm font-bold text-foreground">
                        @if($pctBottom >= 100) کامل — آماده ثبت نهایی
                        @elseif($pctBottom > 0) {{ $completedTopics }} مورد از {{ $totalTopics }} ثبت شده ({{ $pctBottom }}%)
                        @else هنوز شروع نشده
                        @endif
                    </div>
                </div>
            </div>

            <button wire:click="openSubmitModal" type="button"
                    @if($completedTopics == 0) disabled @endif
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 active:scale-95
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
