<div
    x-data="{
        ratings: @entangle('ratings').live,
        activeSubject: 0,
        setRating(topicId, value) {
            this.ratings[topicId] = value;
            $wire.setRating(topicId, value);
        },
        clearRating(topicId) {
            delete this.ratings[topicId];
            $wire.clearRating(topicId);
        },
        getLabel(r) {
            return {1:'D',2:'D+',3:'C',4:'C+',5:'B',6:'B+',7:'A',8:'A+'}[r] ?? '';
        },
        badgeClass(r) {
            if (r >= 7) return 'bg-green-500/15 text-green-600 dark:text-green-400 ring-1 ring-green-500/30';
            if (r >= 5) return 'bg-blue-500/15 text-blue-600 dark:text-blue-400 ring-1 ring-blue-500/30';
            if (r >= 3) return 'bg-amber-500/15 text-amber-600 dark:text-amber-400 ring-1 ring-amber-500/30';
            return 'bg-red-500/15 text-red-600 dark:text-red-400 ring-1 ring-red-500/30';
        }
    }"
    dir="rtl"
    class="min-h-screen bg-background text-foreground"
>

    <div class="max-w-4xl mx-auto px-4 pt-5 pb-28">

        {{-- ── Header ─────────────────────────────────────────── --}}
        <div class="flex items-start justify-between gap-3 mb-6">
            <div>
                <h1 class="text-lg font-black text-foreground mb-1">{{ $project->name }}</h1>
                <p class="text-sm text-muted leading-relaxed">مباحث را امتیازدهی کنید تا تصویر دقیقی از وضعیت خود داشته باشید.</p>
            </div>
            <a
                wire:navigate
                href="{{ route('client.profile.classification.projects') }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-border
                       bg-secondary text-sm font-semibold text-muted hover:text-foreground
                       hover:bg-card transition-colors shrink-0"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3"/>
                </svg>
                بازگشت
            </a>
        </div>

        {{-- ── Progress ────────────────────────────────────────── --}}
        <div class="bg-card border border-border rounded-xl px-4 py-3.5 mb-5">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-xs font-medium text-muted">پیشرفت کلی</span>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold text-foreground">{{ $completedTopics }} / {{ $totalTopics }}</span>
                    @if($totalTopics > 0)
                        <span class="text-xs font-bold text-primary">{{ round(($completedTopics / $totalTopics) * 100) }}%</span>
                    @endif
                </div>
            </div>
            <div class="h-1.5 rounded-full bg-secondary overflow-hidden">
                <div
                    class="h-full rounded-full bg-primary transition-all duration-500"
                    style="width: {{ $totalTopics > 0 ? round(($completedTopics / $totalTopics) * 100) : 0 }}%"
                ></div>
            </div>
        </div>

        {{-- ── Filter Bar ──────────────────────────────────────── --}}
        <div class="flex flex-wrap gap-2 mb-5">
            @foreach($availableTags as $tag)
                <button
                    wire:click="selectTag('{{ $tag['id'] }}')"
                    type="button"
                    class="px-3.5 py-1.5 rounded-lg border text-sm  font-semibold transition-colors
                           {{ $activeTag === $tag['id']
                               ? 'bg-primary/10 text-primary border-primary/40 font-bold'
                               : 'bg-secondary border-border text-muted hover:text-foreground' }}"
                >{{ $tag['label'] }}</button>
            @endforeach

            <button
                wire:click="showMyRatings"
                type="button"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg border text-sm font-semibold transition-colors
                       {{ $showMyTopics
                           ? 'bg-green-500/10 text-green-600 dark:text-green-400 border-green-500/30 font-bold'
                           : 'bg-secondary border-border text-muted hover:text-foreground' }}"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                مباحث من
            </button>
        </div>

        {{-- ── Main ───────────────────────────────────────────── --}}
        @if(!$showMyTopics)

            @if(!empty($subjects))

                {{-- Subject Tabs --}}
                <div class="border-b border-border mb-6 overflow-x-auto">
                    <div class="flex min-w-max">
                        @foreach($subjects as $si => $subject)
                            <button
                                type="button"
                                @click="activeSubject = {{ $si }}"
                                class="px-4 py-2.5 text-sm font-semibold border-b-2 -mb-px transition-colors whitespace-nowrap"
                                :class="activeSubject === {{ $si }}
                                    ? 'border-primary text-primary'
                                    : 'border-transparent text-muted hover:text-foreground'"
                            >{{ $subject['name'] }}</button>
                        @endforeach
                    </div>
                </div>

                {{-- Tab Panels --}}
                @foreach($subjects as $si => $subject)
                    <div x-show="activeSubject === {{ $si }}" x-cloak>
                        @forelse($subject['chapters'] as $ci => $chapter)

                            {{-- Chapter Label --}}
                            <div class="flex items-center gap-2.5 {{ $ci > 0 ? 'mt-7' : '' }} mb-3">
                                <span class="flex items-center justify-center w-6 h-6 rounded-md bg-primary/10 text-primary text-xs font-bold shrink-0">
                                    {{ $ci + 1 }}
                                </span>
                                <span class="text-sm font-bold text-foreground">{{ $chapter['name'] }}</span>
                                <span class="text-xs text-muted mr-auto">{{ count($chapter['topics']) }} مبحث</span>
                            </div>

                            {{-- Topics Table --}}
                            <div class="bg-secondary border border-border rounded-xl overflow-hidden mb-1">
                                @foreach($chapter['topics'] as $ti => $topic)
                                    <div
                                        class="flex items-center justify-between gap-3 px-4 py-3 transition-colors
                                               {{ $ti < count($chapter['topics']) - 1 ? 'border-b border-border' : '' }}"
                                        :class="ratings[{{ $topic['id'] }}] ? 'bg-green-500/5' : 'hover:bg-secondary/60'"
                                    >
                                        <span class="text-sm text-foreground flex-1 leading-snug">{{ $topic['name'] }}</span>

                                        <div class="flex items-center gap-2 shrink-0">

                                            {{-- Stars (ltr so 1→8 is left→right) --}}
                                            <div class="flex items-center gap-0.5" dir="ltr">
                                                @for($i = 1; $i <= 8; $i++)
                                                    <button
                                                        type="button"
                                                        @click="setRating({{ $topic['id'] }}, {{ $i }})"
                                                        class="w-6 h-6 flex items-center justify-center rounded transition-transform hover:scale-110 active:scale-90"
                                                        :class="ratings[{{ $topic['id'] }}] >= {{ $i }}
                                                            ? 'text-amber-400'
                                                            : 'text-border hover:text-amber-300'"
                                                        title="{{ ['','D','D+','C','C+','B','B+','A','A+'][$i] }}"
                                                    >
                                                        <svg class="w-4 h-4 pointer-events-none" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    </button>
                                                @endfor
                                            </div>

                                            {{-- Rating badge --}}
                                            <template x-if="ratings[{{ $topic['id'] }}]">
                                                <span
                                                    class="text-xs font-bold px-2 py-0.5 rounded-md min-w-[32px] text-center"
                                                    :class="badgeClass(ratings[{{ $topic['id'] }}])"
                                                    x-text="getLabel(ratings[{{ $topic['id'] }}])"
                                                ></span>
                                            </template>

                                            {{-- Clear --}}
                                            <template x-if="ratings[{{ $topic['id'] }}]">
                                                <button
                                                    type="button"
                                                    @click="clearRating({{ $topic['id'] }})"
                                                    class="w-6 h-6 flex items-center justify-center rounded bg-red-500/10 text-red-500 hover:bg-red-500/20 transition-colors"
                                                >
                                                    <svg class="w-3.5 h-3.5 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </template>

                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @empty
                            <p class="text-center text-sm text-muted py-12">فصلی برای این درس یافت نشد.</p>
                        @endforelse
                    </div>
                @endforeach

            @else
                <p class="text-center text-sm text-muted py-16">مبحثی یافت نشد. یک دسته‌بندی دیگر انتخاب کنید.</p>
            @endif

        @else

            {{-- ── My Topics ────────────────────────────────── --}}
            <div class="border border-green-500/30 rounded-xl overflow-hidden bg-card">
                <div class="flex items-center gap-2 px-4 py-3 bg-green-500/8 border-b border-green-500/20">
                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span class="text-sm font-bold text-green-600 dark:text-green-400">مباحث امتیازدهی شده ({{ $completedTopics }} مبحث)</span>
                </div>

                @if($myRatedTopics->count() > 0)
                    @foreach($myRatedTopics as $subjectName => $topics)
                        <div class="border-b border-border last:border-b-0">
                            <div class="px-4 py-2 bg-secondary border-b border-border text-xs font-bold text-muted">
                                {{ $subjectName }}
                            </div>
                            @foreach($topics as $classification)
                                <div class="flex items-center justify-between gap-3 px-4 py-3 border-b border-border last:border-b-0 hover:bg-secondary/50 transition-colors">
                                    <div>
                                        <div class="text-sm font-medium text-foreground">{{ $classification->topic->name }}</div>
                                        <div class="text-xs text-muted mt-0.5">
                                            {{ $classification->topic->chapter->name }}
                                            @if($classification->topic->parent)
                                                &larr; {{ $classification->topic->parent->name }}
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-md shrink-0
                                        {{ $classification->rating >= 7
                                            ? 'bg-green-500/15 text-green-600 dark:text-green-400 ring-1 ring-green-500/30'
                                            : ($classification->rating >= 5
                                                ? 'bg-blue-500/15 text-blue-600 dark:text-blue-400 ring-1 ring-blue-500/30'
                                                : ($classification->rating >= 3
                                                    ? 'bg-amber-500/15 text-amber-600 dark:text-amber-400 ring-1 ring-amber-500/30'
                                                    : 'bg-red-500/15 text-red-600 dark:text-red-400 ring-1 ring-red-500/30')) }}">
                                        {{ $this->getRatingLabel($classification->rating) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-12 text-sm text-muted">هنوز مبحثی امتیازدهی نشده است.</div>
                @endif
            </div>

        @endif

    </div>

    {{-- ── Fixed Submit Bar ────────────────────────────────────── --}}
    <div class="fixed bottom-0 inset-x-0 z-40 bg-card/95 backdrop-blur border-t border-border">
        <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between gap-4">

            <div class="flex items-center gap-2.5">
                <div class="w-2 h-2 rounded-full shrink-0
                    {{ $completedTopics >= $totalTopics && $totalTopics > 0
                        ? 'bg-green-500'
                        : ($completedTopics > 0 ? 'bg-primary' : 'bg-border') }}">
                </div>
                <div>
                    <div class="text-xs text-muted">وضعیت</div>
                    <div class="text-sm font-bold text-foreground">
                        @if($completedTopics >= $totalTopics && $totalTopics > 0)
                            کامل — آماده ثبت
                        @elseif($completedTopics > 0)
                            {{ $completedTopics }} مبحث ثبت شده
                        @else
                            هنوز شروع نشده
                        @endif
                    </div>
                </div>
            </div>

            <button
                wire:click="openSubmitModal"
                type="button"
                @if($completedTopics == 0) disabled @endif
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-bold transition-colors
                       {{ $completedTopics > 0
                           ? 'bg-green-500/15 text-green-600 dark:text-green-400 border border-green-500/30 hover:bg-green-500/25'
                           : 'bg-secondary text-muted border border-border opacity-50 cursor-not-allowed' }}"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                ثبت نهایی
            </button>

        </div>
    </div>

    {{-- ── Modal ──────────────────────────────────────────────── --}}
    @if($showSubmitModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/50 backdrop-blur-sm"
            wire:click.self="$set('showSubmitModal', false)"
        >
            <div class="w-full max-w-sm bg-card border border-border rounded-2xl p-6">
                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-green-500/10 mx-auto mb-4">
                    <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <h3 class="text-base font-black text-foreground text-center mb-2">تأیید ثبت نهایی</h3>
                <p class="text-sm text-muted text-center leading-relaxed mb-5">
                    شما {{ $completedTopics }} مبحث از {{ $totalTopics }} مبحث را امتیازدهی کرده‌اید.
                    @if($completedTopics < $totalTopics)
                        <span class="block mt-1 text-amber-500 font-semibold">توجه: همه مباحث امتیازدهی نشده‌اند.</span>
                    @endif
                </p>

                <div class="flex gap-2.5">
                    <button
                        type="button"
                        wire:click="$set('showSubmitModal', false)"
                        class="flex-1 py-2.5 rounded-lg border border-border bg-secondary text-sm font-semibold text-foreground hover:bg-card transition-colors"
                    >انصراف</button>

                    <button
                        type="button"
                        wire:click="submitClassification"
                        class="flex-1 py-2.5 rounded-lg border border-green-500/30 bg-green-500/15 text-sm font-bold text-green-600 dark:text-green-400 hover:bg-green-500/25 transition-colors flex items-center justify-center gap-1.5"
                    >
                        <span wire:loading.remove wire:target="submitClassification">ثبت نهایی</span>
                        <span wire:loading wire:target="submitClassification" class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            در حال ثبت...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
