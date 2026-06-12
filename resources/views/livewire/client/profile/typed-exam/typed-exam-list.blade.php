<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>
            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-10">
                    <div class="space-y-5">

                        <!-- Header -->
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">آزمون‌ ها</div>
                        </div>

                        <!-- Tabs: pill style -->
                        <div class="flex justify-start" dir="rtl">
                            <div class="inline-flex items-center gap-1 p-1 bg-secondary/60 rounded-full border border-border">
                                <button type="button" wire:click="setTab('typed')"
                                        class="relative inline-flex items-center gap-2 px-3 md:px-4 py-1.5 md:py-2 rounded-full text-xs md:text-sm font-medium transition-all
                                        {{ $activeTab === 'typed' ? 'bg-secondary text-primary shadow-sm' : 'text-foreground/70 hover:text-foreground' }}">
                                    آزمون تستی
                                </button>
                                <button type="button" wire:click="setTab('essay')"
                                        class="relative inline-flex items-center gap-2 px-3 md:px-4 py-1.5 md:py-2 rounded-full text-xs md:text-sm font-medium transition-all
                                        {{ $activeTab === 'essay' ? 'bg-secondary text-primary shadow-sm' : 'text-foreground/70 hover:text-foreground' }}">
                                    آزمون تشریحی
                                    @if($essayAssignments->count() > 0)
                                        <span class="inline-flex items-center  justify-center min-w-[18px] h-4 px-1 text-[10px] font-bold rounded-full bg-red-500 text-white">
                                            {{ $essayAssignments->count() }}
                                        </span>
                                    @endif
                                </button>
                            </div>
                        </div>

                        @if($activeTab === 'essay')
                            @include('livewire.client.profile.typed-exam._essay-list', ['essayAssignments' => $essayAssignments])
                        @elseif($assignments->isEmpty())
                            <div class="flex flex-col items-center justify-center space-y-12 py-16">
                                <img src="/client/empty/exam.png" class="w-full max-w-xs" alt="empty"/>
                                <div class="text-center space-y-3">
                                    <h2 class="font-bold text-xl text-foreground">آزمونی برای شما وجود ندارد!</h2>
                                    <p class="text-muted text-sm">هنوز آزمونی برای شما ثبت نشده است.</p>
                                </div>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($assignments as $assignment)
                                    @php $exam = $assignment->typedExam; @endphp

                                    <div x-data="{ expanded: false }"
                                         class="glass border border-border rounded-2xl overflow-hidden flex flex-col">

                                        {{-- ═══ موبایل ═══ --}}
                                        <div class="md:hidden">
                                            <div class="w-full h-36 flex items-center justify-center bg-gradient-to-b from-blue-100 to-blue-200 dark:from-blue-950 dark:to-blue-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-blue-600 dark:text-blue-300 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>

                                            <div class="p-4 space-y-3" dir="rtl">
                                                <h3 class="font-bold text-foreground text-base">{{ $exam->title }}</h3>

                                                @if($assignment->time_range)
                                                    <p class="text-xs text-muted leading-6">
                                                        {{ $assignment->time_range['start_date'] }} ساعت {{ $assignment->time_range['start_time'] }}
                                                        تا
                                                        {{ $assignment->time_range['end_date'] }} ساعت {{ $assignment->time_range['end_time'] }}
                                                    </p>
                                                @endif

                                                <div class="flex flex-wrap items-center gap-2">
                                                    @switch($assignment->computed_status)
                                                        @case('not_started')
                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-xs rounded-full">هنوز شروع نشده</span>
                                                            @break
                                                        @case('available')
                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-500 dark:text-green-400 text-xs rounded-full">قابل شرکت</span>
                                                            @break
                                                        @case('expired')
                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-500 text-xs rounded-full">منقضی شده</span>
                                                            @break
                                                        @case('completed')
                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-primary dark:text-blue-400 text-xs rounded-full">تکمیل شده</span>
                                                            @break
                                                    @endswitch

                                                    @if($assignment->computed_status === 'completed' && $assignment->latestAttempt?->score !== null)
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary text-xs rounded-full">
                                                            نمره: {{ number_format($assignment->latestAttempt->score, 1) }}%
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="px-4 pb-4 space-y-2" dir="rtl">
                                                @if($assignment->can_start)
                                                    <button wire:click="confirmEntry({{ $assignment->id }})"
                                                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                                        ورود به آزمون
                                                    </button>
                                                @elseif($assignment->computed_status === 'completed')
                                                    <a href="{{ route('client.profile.typed-exam.result', ['attemptId' => $assignment->latestAttempt->id]) }}"
                                                       class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold text-sm transition-colors">
                                                        مشاهده نتیجه
                                                    </a>
                                                @elseif($assignment->computed_status === 'not_started')
                                                    <button disabled class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-muted text-yellow-500 rounded-xl font-semibold text-sm cursor-not-allowed">
                                                        منتظر شروع
                                                    </button>
                                                @else
                                                    <button disabled class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-muted text-red-500 rounded-xl font-semibold text-sm cursor-not-allowed">
                                                        غیرفعال
                                                    </button>
                                                @endif

                                                <button @click="expanded = !expanded"
                                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                    <span>مشاهده جزئیات</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="w-4 h-4 transition-transform duration-200"
                                                         :class="{ 'rotate-180': expanded }"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- ═══ دسکتاپ ═══ --}}
                                        <div class="hidden md:flex flex-row min-h-[130px]">
                                            <div class="flex-shrink-0 w-[120px] flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-[#1e3a5f] dark:to-[#1e40af]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-blue-600 dark:text-blue-200 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>

                                            <div class="flex-1 p-4 flex items-center justify-between gap-4" dir="rtl">
                                                <div class="space-y-2 flex-1 min-w-0">
                                                    <h3 class="font-bold text-foreground text-base">{{ $exam->title }}</h3>

                                                    @if($assignment->time_range)
                                                        <p class="text-xs text-muted leading-6">
                                                            {{ $assignment->time_range['start_date'] }} ساعت {{ $assignment->time_range['start_time'] }}
                                                            تا
                                                            {{ $assignment->time_range['end_date'] }} ساعت {{ $assignment->time_range['end_time'] }}
                                                        </p>
                                                    @endif

                                                    <div class="flex flex-wrap items-center gap-1.5">
                                                        @switch($assignment->computed_status)
                                                            @case('not_started')
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-xs rounded-full">هنوز شروع نشده</span>
                                                                @break
                                                            @case('available')
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-500 dark:text-green-400 text-xs rounded-full">قابل شرکت</span>
                                                                @break
                                                            @case('expired')
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-500 text-xs rounded-full">منقضی شده</span>
                                                                @break
                                                            @case('completed')
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-primary dark:text-blue-400 text-xs rounded-full">تکمیل شده</span>
                                                                @break
                                                        @endswitch

                                                        @if($assignment->computed_status === 'completed' && $assignment->latestAttempt?->score !== null)
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-primary/10 text-primary text-xs rounded-full">
                                                                نمره: {{ number_format($assignment->latestAttempt->score, 1) }}%
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                                                    @if($assignment->can_start)
                                                        <button wire:click="confirmEntry({{ $assignment->id }})"
                                                                class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                                            ورود به آزمون
                                                        </button>
                                                    @elseif($assignment->computed_status === 'completed')
                                                        <a href="{{ route('client.profile.typed-exam.result', ['attemptId' => $assignment->latestAttempt->id]) }}"
                                                           class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold text-sm transition-colors">
                                                            مشاهده نتیجه
                                                        </a>
                                                    @elseif($assignment->computed_status === 'not_started')
                                                        <button disabled class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-muted text-yellow-500 rounded-xl font-semibold text-sm cursor-not-allowed">
                                                            منتظر شروع
                                                        </button>
                                                    @else
                                                        <button disabled class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-muted text-red-500 rounded-xl font-semibold text-sm cursor-not-allowed">
                                                            غیرفعال
                                                        </button>
                                                    @endif

                                                    <button @click="expanded = !expanded"
                                                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             class="w-4 h-4 transition-transform duration-200"
                                                             :class="{ 'rotate-180': expanded }"
                                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ═══ جزئیات ═══ --}}
                                        <div x-show="expanded" x-cloak
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 -translate-y-1"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 -translate-y-1"
                                             class="border-border bg-background/50 p-4"
                                             style="display: none;">
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                    </svg>
                                                    <span class="text-xs text-muted">نام دفترچه</span>
                                                    <span class="font-bold text-foreground text-sm mt-1 text-center">{{ $exam->title }}</span>
                                                </div>
                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="text-xs text-muted">مدت زمان</span>
                                                    <span class="font-bold text-foreground text-sm mt-1">
                                                        @if($assignment->time?->duration_minutes)
                                                            {{ $assignment->time->duration_minutes }} دقیقه
                                                        @else
                                                            وجود ندارد
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="text-xs text-muted">تعداد سوالات</span>
                                                    <span class="font-bold text-foreground text-sm mt-1">{{ $exam->questions->count() }} سوال</span>
                                                </div>
                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-2 text-fuchsia-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                    </svg>
                                                    <span class="text-xs text-muted">نوع آزمون</span>
                                                    <span class="font-bold text-foreground text-sm mt-1">تستی</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Exam Modal -->
    @if($confirmingExamId)
        @php
            $selectedAssignment = $assignments->firstWhere('id', $confirmingExamId);
            $selectedExam = $selectedAssignment?->typedExam;
        @endphp
        <div class="fixed inset-0 z-[80] flex flex-col justify-end sm:items-center sm:justify-center" wire:keydown.escape.window="closeModal">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm cursor-pointer" wire:click="closeModal"></div>
            <div class="relative z-10 w-full sm:max-w-md bg-secondary sm:border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl flex flex-col pb-[env(safe-area-inset-bottom,0px)] sm:pb-0">
                <div class="sm:hidden flex justify-center pt-3 pb-1 shrink-0">
                    <div class="w-10 h-1 rounded-full bg-foreground/20"></div>
                </div>
                <div class="p-6">
                    <div class="flex flex-col items-center justify-center space-y-5">
                        <div class="flex items-center justify-center w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-green-500">
                                <path d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M12 9V12L13.5 13.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-xl text-foreground">{{ $selectedExam?->title ?? 'آزمون' }}</h3>
                        <p class="text-center text-muted text-sm leading-relaxed">
                            حواستون باشه از زمانی که دکمه شرکت در آزمون رو می‌زنید، زمان برای شما در نظر گرفته میشه!
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-x-4 border-border p-4">
                    <button type="button" wire:click="closeModal" class="flex items-center justify-center gap-x-2 w-full bg-background border border-border rounded-xl text-foreground py-3 px-4 hover:bg-secondary transition-colors">
                        <span class="font-bold text-sm">لغو</span>
                    </button>
                    <button wire:click="enterExam" class="flex items-center justify-center gap-x-2 w-full bg-primary hover:bg-primary/90 border border-transparent rounded-xl text-primary-foreground py-3 px-4 transition-colors">
                        <span class="font-bold text-sm">شروع</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
