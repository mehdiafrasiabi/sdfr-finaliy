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
        getLabel(r) {
            return {1:'D',2:'C',3:'B',4:'A'}[r] ?? '';
        },
        badgeClass(r) {
            if (r >= 4) return 'bg-green-500/15 text-green-600 dark:text-green-400 ring-1 ring-green-500/30';
            if (r >= 3) return 'bg-blue-500/15 text-blue-600 dark:text-blue-400 ring-1 ring-blue-500/30';
            if (r >= 2) return 'bg-amber-500/15 text-amber-600 dark:text-amber-400 ring-1 ring-amber-500/30';
            return 'bg-red-500/15 text-red-600 dark:text-red-400 ring-1 ring-red-500/30';
        }
    }"
    dir="rtl"
    class="min-h-screen bg-background text-foreground"
>

    <div class="max-w-4xl mx-auto px-4 pt-5 pb-28">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-3 mb-6">
            <div>
                <h1 class="text-lg font-black text-foreground mb-1">{{ $project->name }}</h1>
                <p class="text-sm text-muted leading-relaxed">دروس را امتیازدهی کنید تا تصویر دقیقی از وضعیت خود داشته باشید.</p>
            </div>
            <a wire:navigate href="{{ route('client.profile.classification.projects') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-border bg-secondary text-sm font-semibold text-muted hover:text-foreground hover:bg-card transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3"/>
                </svg>
                بازگشت
            </a>
        </div>

        {{-- Progress --}}
        <div class="bg-card border border-border rounded-xl px-4 py-3.5 mb-5">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-xs font-medium text-muted">پیشرفت کلی</span>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold text-foreground">{{ $completedTopics }} / {{ $totalTopics }}</span>
                    @if($totalTopics > 0)
                        <span class="text-xs font-bold text-primary">{{ min(100, round(($completedTopics / max($totalTopics,1)) * 100)) }}%</span>
                    @endif
                </div>
            </div>
            <div class="h-1.5 rounded-full bg-secondary overflow-hidden">
                <div class="h-full rounded-full bg-primary transition-all duration-500"
                     style="width: {{ $totalTopics > 0 ? min(100, round(($completedTopics / max($totalTopics,1)) * 100)) : 0 }}%"></div>
            </div>
        </div>

        {{-- Filter tabs --}}
        <div class="flex flex-wrap gap-2 mb-5">
            @foreach($availableTags as $tag)
                <button wire:click="selectTag('{{ $tag['id'] }}')" type="button"
                        class="px-3.5 py-1.5 rounded-lg border text-sm font-semibold transition-colors
                               {{ $activeTag === $tag['id']
                                   ? 'bg-primary/10 text-primary border-primary/40 font-bold'
                                   : 'bg-secondary border-border text-muted hover:text-foreground' }}">{{ $tag['label'] }}</button>
            @endforeach

            <button wire:click="showMyRatings" type="button"
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg border text-sm font-semibold transition-colors
                           {{ $showMyTopics
                               ? 'bg-green-500/10 text-green-600 dark:text-green-400 border-green-500/30 font-bold'
                               : 'bg-secondary border-border text-muted hover:text-foreground' }}">
                موارد امتیازدهی‌شده من
            </button>
        </div>

        @if(!$showMyTopics)

            @php
                $activeType = $activeTag ? explode('_', $activeTag)[1] ?? 'specialized' : 'specialized';
            @endphp

            @if(!empty($subjects))

                @if($activeType === 'specialized')
                    {{-- Specialized: tabs per subject, each shows chapters --}}
                    <div class="border-b border-border mb-6 overflow-x-auto">
                        <div class="flex min-w-max">
                            @foreach($subjects as $si => $subject)
                                <button type="button" @click="activeSubject = {{ $si }}"
                                        class="px-4 py-2.5 text-sm font-semibold border-b-2 -mb-px transition-colors whitespace-nowrap"
                                        :class="activeSubject === {{ $si }}
                                            ? 'border-primary text-primary'
                                            : 'border-transparent text-muted hover:text-foreground'">
                                    {{ $subject['name'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @foreach($subjects as $si => $subject)
                        <div x-show="activeSubject === {{ $si }}" x-cloak>
                            <div class="bg-secondary border border-border rounded-xl overflow-hidden mb-1">
                                @forelse($subject['chapters'] as $ci => $chapter)
                                    @php $key = 'chapter_' . $chapter['id']; @endphp
                                    <div class="flex items-center justify-between gap-3 px-4 py-3 transition-colors
                                                {{ $ci < count($subject['chapters']) - 1 ? 'border-b border-border' : '' }}"
                                         :class="ratings['{{ $key }}'] ? 'bg-green-500/5' : 'hover:bg-secondary/60'">
                                        <span class="text-sm text-foreground flex-1 leading-snug">
                                            <span class="text-xs text-muted ml-1">فصل {{ $ci + 1 }}</span>
                                            {{ $chapter['name'] }}
                                        </span>

                                        <div class="flex items-center gap-2 shrink-0">
                                            <div class="flex items-center gap-0.5" dir="ltr">
                                                @for($i = 1; $i <= 4; $i++)
                                                    <button type="button"
                                                            @click="setRating('{{ $key }}', 'chapter', {{ $chapter['id'] }}, {{ $i }})"
                                                            class="w-6 h-6 flex items-center justify-center rounded transition-transform hover:scale-110 active:scale-90"
                                                            :class="ratings['{{ $key }}'] >= {{ $i }} ? 'text-amber-400' : 'text-border hover:text-amber-300'"
                                                            title="{{ ['','D','C','B','A'][$i] }}">
                                                        <svg class="w-4 h-4 pointer-events-none" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    </button>
                                                @endfor
                                            </div>

                                            <template x-if="ratings['{{ $key }}']">
                                                <span class="text-xs font-bold px-2 py-0.5 rounded-md min-w-[28px] text-center"
                                                      :class="badgeClass(ratings['{{ $key }}'])"
                                                      x-text="getLabel(ratings['{{ $key }}'])"></span>
                                            </template>

                                            <template x-if="ratings['{{ $key }}']">
                                                <button type="button"
                                                        @click="clearRating('{{ $key }}', 'chapter', {{ $chapter['id'] }})"
                                                        class="w-7 h-7 flex items-center justify-center rounded bg-red-500/10 text-red-500 hover:bg-red-500/20 transition-colors"
                                                        title="ریست امتیاز">
                                                    <svg class="w-4 h-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                                                    </svg>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-sm text-muted py-8">فصلی برای این درس یافت نشد.</p>
                                @endforelse
                            </div>
                        </div>
                    @endforeach

                @else
                    {{-- General: list of subjects, each rated as a whole --}}
                    <div class="mb-3">
                        <p class="text-sm text-muted">به‌طور کلی دروس عمومی خود را چگونه طبقه‌بندی می‌کنید؟</p>
                    </div>
                    <div class="bg-secondary border border-border rounded-xl overflow-hidden mb-1">
                        @foreach($subjects as $si => $subject)
                            @php $key = 'subject_' . $subject['id']; @endphp
                            <div class="flex items-center justify-between gap-3 px-4 py-3 transition-colors
                                        {{ $si < count($subjects) - 1 ? 'border-b border-border' : '' }}"
                                 :class="ratings['{{ $key }}'] ? 'bg-green-500/5' : 'hover:bg-secondary/60'">
                                <span class="text-sm text-foreground flex-1 leading-snug font-medium">{{ $subject['name'] }}</span>

                                <div class="flex items-center gap-2 shrink-0">
                                    <div class="flex items-center gap-0.5" dir="ltr">
                                        @for($i = 1; $i <= 4; $i++)
                                            <button type="button"
                                                    @click="setRating('{{ $key }}', 'subject', {{ $subject['id'] }}, {{ $i }})"
                                                    class="w-6 h-6 flex items-center justify-center rounded transition-transform hover:scale-110 active:scale-90"
                                                    :class="ratings['{{ $key }}'] >= {{ $i }} ? 'text-amber-400' : 'text-border hover:text-amber-300'"
                                                    title="{{ ['','D','C','B','A'][$i] }}">
                                                <svg class="w-4 h-4 pointer-events-none" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        @endfor
                                    </div>

                                    <template x-if="ratings['{{ $key }}']">
                                        <span class="text-xs font-bold px-2 py-0.5 rounded-md min-w-[28px] text-center"
                                              :class="badgeClass(ratings['{{ $key }}'])"
                                              x-text="getLabel(ratings['{{ $key }}'])"></span>
                                    </template>

                                    <template x-if="ratings['{{ $key }}']">
                                        <button type="button"
                                                @click="clearRating('{{ $key }}', 'subject', {{ $subject['id'] }})"
                                                class="w-7 h-7 flex items-center justify-center rounded bg-red-500/10 text-red-500 hover:bg-red-500/20 transition-colors"
                                                title="ریست امتیاز">
                                            <svg class="w-4 h-4 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            @else
                <p class="text-center text-sm text-muted py-16">مورد قابل نمایش یافت نشد.</p>
            @endif

        @else

            {{-- My ratings --}}
            <div class="border border-green-500/30 rounded-xl overflow-hidden bg-card">
                <div class="flex items-center gap-2 px-4 py-3 bg-green-500/8 border-b border-green-500/20">
                    <span class="text-sm font-bold text-green-600 dark:text-green-400">موارد امتیازدهی‌شده ({{ $completedTopics }})</span>
                </div>

                @if($myRated->count() > 0)
                    @foreach($myRated as $subjectName => $items)
                        <div class="border-b border-border last:border-b-0">
                            <div class="px-4 py-2 bg-secondary border-b border-border text-xs font-bold text-muted">
                                {{ $subjectName }}
                            </div>
                            @foreach($items as $item)
                                <div class="flex items-center justify-between gap-3 px-4 py-3 border-b border-border last:border-b-0 hover:bg-secondary/50 transition-colors">
                                    <div>
                                        <div class="text-sm font-medium text-foreground">{{ $item->itemName }}</div>
                                        <div class="text-xs text-muted mt-0.5">{{ $item->context }}</div>
                                    </div>
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-md shrink-0
                                        {{ $item->rating >= 4
                                            ? 'bg-green-500/15 text-green-600 dark:text-green-400 ring-1 ring-green-500/30'
                                            : ($item->rating >= 3
                                                ? 'bg-blue-500/15 text-blue-600 dark:text-blue-400 ring-1 ring-blue-500/30'
                                                : ($item->rating >= 2
                                                    ? 'bg-amber-500/15 text-amber-600 dark:text-amber-400 ring-1 ring-amber-500/30'
                                                    : 'bg-red-500/15 text-red-600 dark:text-red-400 ring-1 ring-red-500/30')) }}">
                                        {{ $this->getRatingLabel($item->rating) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-12 text-sm text-muted">هنوز امتیازی ثبت نشده است.</div>
                @endif
            </div>

        @endif

    </div>

    {{-- Submit bar --}}
    <div class="fixed bottom-0 inset-x-0 z-40 bg-card/95 backdrop-blur border-t border-border">
        <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <div class="w-2 h-2 rounded-full shrink-0
                    {{ $completedTopics >= $totalTopics && $totalTopics > 0
                        ? 'bg-green-500'
                        : ($completedTopics > 0 ? 'bg-primary' : 'bg-border') }}"></div>
                <div>
                    <div class="text-xs text-muted">وضعیت</div>
                    <div class="text-sm font-bold text-foreground">
                        @if($completedTopics >= $totalTopics && $totalTopics > 0)
                            کامل — آماده ثبت
                        @elseif($completedTopics > 0)
                            {{ $completedTopics }} مورد ثبت شده
                        @else
                            هنوز شروع نشده
                        @endif
                    </div>
                </div>
            </div>

            <button wire:click="openSubmitModal" type="button" @if($completedTopics == 0) disabled @endif
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-bold transition-colors
                           {{ $completedTopics > 0
                               ? 'bg-green-500/15 text-green-600 dark:text-green-400 border border-green-500/30 hover:bg-green-500/25'
                               : 'bg-secondary text-muted border border-border opacity-50 cursor-not-allowed' }}">
                ثبت نهایی
            </button>
        </div>
    </div>

    {{-- Submit modal --}}
    @if($showSubmitModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/50 backdrop-blur-sm"
             wire:click.self="$set('showSubmitModal', false)">
            <div class="w-full max-w-sm bg-card border border-border rounded-2xl p-6">
                <h3 class="text-base font-black text-foreground text-center mb-2">تأیید ثبت نهایی</h3>
                <p class="text-sm text-muted text-center leading-relaxed mb-5">
                    شما {{ $completedTopics }} مورد از {{ $totalTopics }} مورد را امتیازدهی کرده‌اید.
                    @if($completedTopics < $totalTopics)
                        <span class="block mt-1 text-amber-500 font-semibold">توجه: همه موارد امتیازدهی نشده‌اند.</span>
                    @endif
                </p>
                <div class="flex gap-2.5">
                    <button type="button" wire:click="$set('showSubmitModal', false)"
                            class="flex-1 py-2.5 rounded-lg border border-border bg-secondary text-sm font-semibold text-foreground hover:bg-card transition-colors">
                        انصراف
                    </button>
                    <button type="button" wire:click="submitClassification"
                            class="flex-1 py-2.5 rounded-lg border border-green-500/30 bg-green-500/15 text-sm font-bold text-green-600 dark:text-green-400 hover:bg-green-500/25 transition-colors">
                        ثبت نهایی
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
