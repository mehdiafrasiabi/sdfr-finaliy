<div
    x-data
    class="min-h-screen bg-background text-foreground pb-28 sm:pb-32"
    dir="rtl"
>
    @push('link')
        <style>
            [x-cloak] {
                display: none !important;
            }

            .card-soft-shadow {
                box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08),
                0 8px 20px rgba(15, 23, 42, 0.04);
            }
        </style>
    @endpush

    <div class="container mx-auto px-4 max-w-7xl pt-4 sm:pt-6 pb-24">

        {{-- Header --}}
        <header class="mb-5 sm:mb-7 flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="flex items-center gap-3 flex-1">
                <div class="flex items-center gap-1">
                    <div class="w-1 h-1 bg-foreground/80 rounded-full"></div>
                    <div class="w-2 h-2 bg-foreground rounded-full"></div>
                </div>

                <div>
                    <h1 class="font-black text-lg sm:text-xl text-foreground">
                        {{ $project->name }}
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm text-muted">
                        مباحث را امتیازدهی کنید تا تصویر دقیقی از وضعیت خودتان داشته باشید.
                    </p>
                </div>
            </div>

            <div class="mt-2 sm:mt-0">
                <a
                    wire:navigate
                    href="{{ route('client.profile.classification.projects') }}"
                    class="inline-flex items-center justify-center gap-x-1.5 h-9 sm:h-10
                           rounded-full border border-border bg-background px-4 sm:px-6
                           text-[11px] sm:text-xs font-semibold text-muted
                           hover:text-foreground hover:bg-secondary/70
                           transition-colors"
                >
                    <span>بازگشت</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3"/>
                    </svg>
                </a>
            </div>
        </header>

        {{-- Tags & Progress --}}
        <section
            class="mb-5 rounded-2xl border border-border bg-card/90 px-4 py-4 sm:px-5 sm:py-5 card-soft-shadow"
        >
            <div class="flex flex-col gap-3">
                {{-- فیلتر تگ‌ها + دکمه مباحث من --}}
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-wrap gap-2">
                        @foreach($availableTags as $tag)
                            <button
                                wire:click="selectTag('{{ $tag['id'] }}')"
                                type="button"
                                class="rounded-full px-3 sm:px-4 py-1.5 text-[11px] sm:text-xs font-medium
                                       transition-all duration-200
                                       {{ $activeTag === $tag['id']
                                          ? 'bg-primary text-primary-foreground shadow-sm hover:opacity-90'
                                          : 'bg-muted text-foreground hover:bg-muted/80' }}"
                            >
                                {{ $tag['label'] }}
                            </button>
                        @endforeach

                        {{-- دکمه "مباحث من" --}}
                        <button
                            wire:click="showMyRatings"
                            type="button"
                            class="rounded-full px-3 sm:px-4 py-1.5 text-[11px] sm:text-xs font-medium
                                   transition-all duration-200 flex items-center gap-2
                                   {{ $showMyTopics
                                      ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/25'
                                      : 'bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500/15' }}"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <span>مشاهده مباحث من</span>
                        </button>
                    </div>

                    {{-- خلاصه پیشرفت --}}
                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-primary/5 px-3 sm:px-4 py-1.5
                               text-[11px] sm:text-xs text-foreground"
                    >
                        <span class="text-muted">پیشرفت:</span>
                        <span class="font-semibold">
                            {{ $completedTopics }} از {{ $totalTopics }} مبحث
                        </span>
                        @if($totalTopics > 0)
                            <span class="text-xs text-primary font-bold">
                                ({{ round(($completedTopics / $totalTopics) * 100) }}%)
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="mt-1 w-full h-2 rounded-full bg-muted overflow-hidden">
                    <div
                        class="h-full rounded-full bg-gradient-to-l from-blue-500 via-primary to-emerald-500
                               transition-all duration-500"
                        style="width: {{ $totalTopics > 0 ? ($completedTopics / $totalTopics) * 100 : 0 }}%"
                    ></div>
                </div>
            </div>
        </section>

        {{-- لیست مباحث / مباحث من --}}
        @if(!$showMyTopics)
            {{-- Subjects List --}}
            <section class="space-y-4 md:space-y-6">
                @forelse($subjects as $subject)
                    <div
                        x-data="{ open: true }"
                        class="rounded-2xl border border-border/70 bg-card/80 overflow-hidden card-soft-shadow"
                    >
                        {{-- Subject Header --}}
                        <button
                            type="button"
                            @click="open = !open"
                            class="flex w-full items-center justify-between px-4 py-3 sm:px-5 sm:py-4
                                   border-b border-border/70
                                   bg-gradient-to-l from-primary/10 via-blue-500/5 to-purple-500/10"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex h-9 w-9 items-center justify-center rounded-xl
                                           bg-primary/15 text-primary shadow-sm shadow-primary/25"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>

                                <div class="flex flex-col items-start gap-0.5">
                                    <span class="text-sm sm:text-base font-bold text-foreground">
                                        {{ $subject['name'] }}
                                    </span>
                                    <span class="text-[11px] text-muted">
                                        {{ count($subject['chapters']) }} فصل
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="hidden sm:inline text-[11px] text-muted">
                                    برای باز و بسته کردن کلیک کنید
                                </span>
                                <svg
                                    class="w-4 h-4 text-muted transition-transform duration-200"
                                    :class="{ 'rotate-180': open }"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        {{-- Chapters & Topics --}}
                        <div
                            x-show="open"
                            x-transition.opacity.duration.200ms
                            x-cloak
                            class="divide-y divide-border/70 bg-background/80"
                        >
                            @foreach($subject['chapters'] as $chapter)
                                <div class="p-4 sm:p-5 space-y-3 sm:space-y-4">
                                    {{-- Chapter Title --}}
                                    <div class="flex items-center justify-between gap-2">
                                        <h3
                                            class="inline-flex items-center gap-2 rounded-full bg-blue-100/70
                                                   px-3 py-1 text-xs sm:text-sm font-semibold
                                                   text-primary dark:bg-blue-900/30 dark:text-blue-400"
                                        >
                                            <span
                                                class="flex h-5 w-5 items-center justify-center rounded-full
                                                       bg-primary/15 text-primary"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3"
                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </span>
                                            <span>{{ $chapter['name'] }}</span>
                                        </h3>

                                        <span class="hidden md:inline-block text-[11px] text-muted">
                                            {{ count($chapter['topics']) }} مبحث
                                        </span>
                                    </div>

                                    {{-- Topics --}}
                                    <div class="space-y-2.5 sm:space-y-3">
                                        @foreach($chapter['topics'] as $topic)
                                            <div
                                                class="flex flex-col gap-3 rounded-xl border border-muted/50
                                                       bg-muted/40 px-3 py-3 sm:px-4 sm:py-3.5
                                                       transition-colors hover:bg-muted/70
                                                       md:flex-row md:items-center md:justify-between"
                                            >
                                                {{-- Topic name --}}
                                                <span
                                                    class="text-[13px] sm:text-sm font-medium text-foreground leading-relaxed"
                                                >
                                                    {{ $topic['name'] }}
                                                </span>

                                                {{-- Rating --}}
                                                <div class="flex items-center gap-3 sm:gap-4">
                                                    {{-- Label D → A+ --}}
                                                    <div
                                                        class="hidden sm:flex items-center gap-1 text-[11px] text-muted"
                                                        dir="ltr"
                                                    >
                                                        <span>D</span>
                                                        <span class="mx-1.5">→</span>
                                                        <span>A+</span>
                                                    </div>

                                                    {{-- Stars --}}
                                                    <div class="flex items-center gap-1" dir="ltr">
                                                        @for($i = 1; $i <= 8; $i++)
                                                            <button
                                                                wire:click="setRating({{ $topic['id'] }}, {{ $i }})"
                                                                type="button"
                                                                class="h-6 w-6 sm:h-7 sm:w-7 rounded-full
                                                                       transition-all duration-200 transform
                                                                       hover:scale-110
                                                                       {{ isset($ratings[$topic['id']]) && $ratings[$topic['id']] >= $i
                                                                          ? $this->getStarColor($i)
                                                                          : 'bg-muted text-muted hover:bg-muted/80' }}"
                                                                title="{{ $this->getRatingLabel($i) }}"
                                                            >
                                                                <svg class="h-full w-full p-1" fill="currentColor"
                                                                     viewBox="0 0 20 20">
                                                                    <path
                                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                                    />
                                                                </svg>
                                                            </button>
                                                        @endfor

                                                        {{-- Clear --}}
                                                        @if(isset($ratings[$topic['id']]))
                                                            <button
                                                                wire:click="clearRating({{ $topic['id'] }})"
                                                                type="button"
                                                                class="mr-1 h-4 w-4 sm:h-6 sm:w-6 rounded-full
                                                                       bg-red-500/15 text-red-500
                                                                       hover:bg-red-500/25
                                                                       transition-all duration-200"
                                                                title="پاک کردن"
                                                            >
                                                                <svg class="h-full w-full p-1.5" fill="none"
                                                                     stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                          stroke-linejoin="round"
                                                                          stroke-width="2"
                                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    </div>

                                                    {{-- Badge --}}
                                                    @if(isset($ratings[$topic['id']]))
                                                        <span
                                                            class="text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded-lg
                                                                   {{ $this->getRatingBadgeColor($ratings[$topic['id']]) }}"
                                                        >
                                                            {{ $this->getRatingLabel($ratings[$topic['id']]) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div
                        class="mt-6 rounded-2xl border border-border bg-card/90 py-12 text-center card-soft-shadow"
                    >
                        <div
                            class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-muted text-muted"
                        >
                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <p class="text-sm text-muted">
                            مبحثی وجود ندارد، دسته‌بندی دیگری را انتخاب کنید.
                        </p>
                    </div>
                @endforelse
            </section>
        @else
            {{-- "مباحث من" --}}
            <section
                class="rounded-2xl border border-border bg-card/90 overflow-hidden card-soft-shadow"
            >
                <div
                    class="bg-gradient-to-r from-emerald-500/10 to-green-500/10 px-4 py-3 sm:px-5 sm:py-4
                           border-b border-border/70"
                >
                    <h2 class="flex items-center gap-2 text-sm sm:text-base font-bold text-foreground">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        مباحث امتیازدهی شده من
                    </h2>
                </div>

                <div class="p-4 sm:p-5">
                    @if($myRatedTopics->count() > 0)
                        <div class="space-y-4 sm:space-y-5">
                            @foreach($myRatedTopics as $subjectName => $topics)
                                <div class="overflow-hidden rounded-xl border border-border bg-background/70">
                                    <div class="bg-muted/60 px-4 py-2 text-xs sm:text-sm font-medium text-foreground">
                                        {{ $subjectName }}
                                    </div>
                                    <div class="divide-y divide-border/80">
                                        @foreach($topics as $classification)
                                            <div
                                                class="flex flex-col gap-2 px-4 py-3 text-xs sm:text-sm
                                                       sm:flex-row sm:items-center sm:justify-between"
                                            >
                                                <div>
                                                    <span class="text-foreground">
                                                        {{ $classification->topic->name }}
                                                    </span>
                                                    <span class="mt-0.5 block text-[11px] text-muted">
                                                        {{ $classification->topic->chapter->name }}
                                                    </span>
                                                </div>

                                                <span
                                                    class="inline-flex items-center justify-center rounded-lg px-3 py-1
                                                           text-xs font-bold {{ $this->getRatingBadgeColor($classification->rating) }}"
                                                >
                                                    {{ $this->getRatingLabel($classification->rating) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-6 text-center">
                            <p class="text-sm text-muted">هنوز مبحثی امتیازدهی نشده است.</p>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        {{-- Fixed Submit Bar --}}
        <div
            class="fixed bottom-0 inset-x-0 z-40 border-t border-border
                   bg-secondary/90 backdrop-blur-lg"
        >
            <div class="container mx-auto max-w-7xl px-4 py-3">
                <div class="flex items-center justify-between gap-3 text-xs sm:text-sm">
                    <div>
                        <span class="text-muted">وضعیت:</span>
                        @if($completedTopics >= $totalTopics && $totalTopics > 0)
                            <span class="ml-1 font-medium text-emerald-500">
                                آماده ثبت ✓
                            </span>
                        @else
                            <span class="ml-1 font-medium text-amber-500">
                                {{ max($totalTopics - $completedTopics, 0) }} مبحث باقی‌مانده
                            </span>
                        @endif
                    </div>

                    <button
                        wire:click="openSubmitModal"
                        @if($completedTopics < $totalTopics || $totalTopics == 0) disabled @endif
                        class="inline-flex items-center gap-2 rounded-xl px-5 py-2 text-xs sm:text-sm font-medium
                               text-white transition-all duration-300
                               {{ $completedTopics >= $totalTopics && $totalTopics > 0
                                  ? 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 shadow-lg shadow-green-500/25'
                                  : 'bg-gray-600 cursor-not-allowed opacity-60' }}"
                    >
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>ثبت نهایی</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Submit Confirmation Modal --}}
        @if($showSubmitModal)
            <div
                class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-0"
                x-data="{ modalOpen: true }"
                x-cloak
            >
                {{-- Backdrop --}}
                <div
                    class="fixed inset-0 bg-black/50"
                    wire:click="$set('showSubmitModal', false)"
                ></div>

                {{-- Modal --}}
                <div
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200 transform"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative z-20 w-full max-w-md overflow-hidden rounded-2xl border border-border
                           bg-background shadow-2xl"
                >
                    {{-- Close button --}}
                    <div class="relative p-3">
                        <button
                            type="button"
                            wire:click="$set('showSubmitModal', false)"
                            class="absolute left-4 top-3 text-muted hover:text-error focus:outline-none"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <hr class="border-border">

                    {{-- Body --}}
                    <div class="p-6">
                        <div class="flex flex-col items-center justify-center space-y-5">
                            <div
                                class="flex h-20 w-20 items-center justify-center rounded-full
                                       bg-green-100 text-green-500 dark:bg-green-900/30"
                            >
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>

                            <h3 class="text-lg font-bold text-foreground">
                                تأیید ثبت نهایی
                            </h3>

                            <p class="text-center text-xs sm:text-sm text-muted leading-relaxed">
                                آیا از ثبت نهایی طبقه‌بندی خود اطمینان دارید؟
                            </p>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center gap-3 border-t border-border px-4 py-4">
                        <button
                            type="button"
                            wire:click="$set('showSubmitModal', false)"
                            class="flex w-full items-center justify-center gap-x-2 rounded-xl border border-border
                                   bg-background px-4 py-2.5 text-xs sm:text-sm font-bold text-foreground
                                   hover:bg-secondary transition-colors"
                        >
                            لغو
                        </button>

                        <button
                            type="button"
                            wire:click="submitClassification"
                            class="flex w-full items-center justify-center gap-x-2 rounded-xl border border-transparent
                                   bg-primary px-4 py-2.5 text-xs sm:text-sm font-bold text-primary-foreground
                                   hover:bg-primary/90 transition-colors"
                        >
                            <span wire:loading.remove wire:target="submitClassification">
                                ثبت نهایی
                            </span>
                            <span wire:loading wire:target="submitClassification">
                                در حال ثبت...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Loading Overlay --}}
        <div
            wire:loading.flex
            wire:target="selectTag,clearRating"
            class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/30 backdrop-blur-sm"
        >
            <div
                class="flex items-center gap-3 rounded-xl border border-border bg-card px-4 py-3 shadow-xl"
            >
                <div class="h-6 w-6 animate-spin rounded-full border-[3px] border-blue-500 border-t-transparent"></div>
                <span class="text-sm sm:text-base font-bold text-foreground">
                    در حال پردازش...
                </span>
            </div>
        </div>
    </div>

    @php
        // اگر این متدها داخل کامپوننت Livewire تعریف شده‌اند، این بلوک لازم نیست.
        // صرفاً برای استفاده مستقیم در ویو (بدون $this) نگه داشته شده است.

        $getStarColor = function($rating) {
            return match(true) {
                $rating >= 7 => 'bg-green-500 text-white',
                $rating >= 5 => 'bg-blue-500 text-white',
                $rating >= 3 => 'bg-amber-500 text-white',
                default      => 'bg-red-500 text-white',
            };
        };

        $getRatingLabel = function($rating) {
            $labels = [1 => 'D', 2 => 'D+', 3 => 'C', 4 => 'C+', 5 => 'B', 6 => 'B+', 7 => 'A', 8 => 'A+'];
            return $labels[$rating] ?? '';
        };

        $getRatingBadgeColor = function($rating) {
            return match(true) {
                $rating >= 7 => 'bg-green-500/20 text-green-500',
                $rating >= 5 => 'bg-blue-500/20 text-blue-500',
                $rating >= 3 => 'bg-amber-500/20 text-amber-500',
                default      => 'bg-red-500/20 text-red-500',
            };
        };
    @endphp
</div>
