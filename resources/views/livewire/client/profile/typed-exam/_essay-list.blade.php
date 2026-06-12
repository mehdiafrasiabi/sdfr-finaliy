@if($essayAssignments->isEmpty())
    <div class="flex flex-col items-center justify-center space-y-12 py-16">
        <img src="/client/empty/exam.png" class="w-full max-w-xs" alt="empty"/>
        <div class="text-center space-y-3">
            <h2 class="font-bold text-xl text-foreground">آزمون تشریحی برای شما وجود ندارد!</h2>
            <p class="text-muted text-sm">هنوز آزمون تشریحی برای شما ثبت نشده است.</p>
        </div>
    </div>
@else
    <div class="space-y-4">
        @foreach($essayAssignments as $assignment)
            @php $exam = $assignment->essayExam; @endphp

            <div x-data="{ expanded: false }"
                 class="glass border border-border rounded-2xl overflow-hidden flex flex-col">

                {{-- ═══ موبایل ═══ --}}
                <div class="md:hidden">
                    <div class="w-full h-36 flex items-center justify-center bg-gradient-to-b from-blue-100 to-blue-200 dark:from-blue-950 dark:to-blue-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-blue-600 dark:text-blue-300 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
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
                                @case('submitted')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-500 text-xs rounded-full">ارسال شده (در انتظار تصحیح)</span>
                                    @break
                                @case('completed')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-primary text-xs rounded-full">تصحیح شده</span>
                                    @break
                            @endswitch
                            @if($assignment->computed_status === 'completed' && $assignment->latestAttempt?->total_score !== null)
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary text-xs rounded-full">
                                    نمره: {{ number_format($assignment->latestAttempt->total_score, 2) }} / {{ number_format($exam->total_score, 2) }}
                                </span>
                            @endif
                        </div>

                        @if($assignment->computed_status === 'available')
                            <div class="bg-background rounded-xl border border-border p-3">
                                <div class="text-xs text-muted mb-2">قبل از شروع می‌توانید پاسخ‌برگ را مشاهده/دانلود کنید.</div>
                                <a href="{{ route('client.profile.essay-exam.answer-sheet', ['assignmentId' => $assignment->id]) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary/10 hover:bg-primary/20 text-primary rounded-lg text-xs font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                                    </svg>
                                    دانلود پاسخ‌برگ (PDF)
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="px-4 pb-4 space-y-2" dir="rtl">
                        @if($assignment->can_start)
                            <button wire:click="confirmEntry({{ $assignment->id }})"
                                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                ورود به آزمون
                            </button>
                        @elseif($assignment->computed_status === 'completed')
                            <a href="{{ route('client.profile.essay-exam.result', ['attemptId' => $assignment->latestAttempt->id]) }}"
                               class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold text-sm transition-colors">
                                مشاهده نتیجه
                            </a>
                        @elseif($assignment->computed_status === 'submitted')
                            <button disabled class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-muted text-indigo-500 rounded-xl font-semibold text-sm cursor-not-allowed">
                                در انتظار تصحیح
                            </button>
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
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
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
                                    @case('submitted')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-500 text-xs rounded-full">ارسال شده (در انتظار تصحیح)</span>
                                        @break
                                    @case('completed')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-primary text-xs rounded-full">تصحیح شده</span>
                                        @break
                                @endswitch
                                @if($assignment->computed_status === 'completed' && $assignment->latestAttempt?->total_score !== null)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-primary/10 text-primary text-xs rounded-full">
                                        نمره: {{ number_format($assignment->latestAttempt->total_score, 2) }} / {{ number_format($exam->total_score, 2) }}
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
                                <a href="{{ route('client.profile.essay-exam.result', ['attemptId' => $assignment->latestAttempt->id]) }}"
                                   class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold text-sm transition-colors">
                                    مشاهده نتیجه
                                </a>
                            @elseif($assignment->computed_status === 'submitted')
                                <button disabled class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-muted text-indigo-500 rounded-xl font-semibold text-sm cursor-not-allowed">
                                    در انتظار تصحیح
                                </button>
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

                {{-- ═══ نوار دانلود پاسخ‌برگ (دسکتاپ - فقط available) ═══ --}}
                @if($assignment->computed_status === 'available')
                    <div class="hidden md:block border-border bg-background/50 px-4 py-3">
                        <div class="flex items-center justify-between flex-wrap gap-2" dir="rtl">
                            <div class="text-xs text-muted">قبل از شروع می‌توانید پاسخ‌برگ را مشاهده/دانلود کنید.</div>
                            <a href="{{ route('client.profile.essay-exam.answer-sheet', ['assignmentId' => $assignment->id]) }}"
                               target="_blank"
                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary/10 hover:bg-primary/20 text-primary rounded-lg text-xs font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                                </svg>
                                دانلود پاسخ‌برگ (PDF)
                            </a>
                        </div>
                    </div>
                @endif

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
                            <span class="text-xs text-muted">عنوان آزمون</span>
                            <span class="font-bold text-foreground text-sm mt-1 text-center">{{ $exam->title }}</span>
                        </div>
                        <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-xs text-muted">مدت زمان</span>
                            <span class="font-bold text-foreground text-sm mt-1">{{ $assignment->time?->duration_minutes ?? '—' }} دقیقه</span>
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
                            <span class="text-xs text-muted">نمره کل</span>
                            <span class="font-bold text-foreground text-sm mt-1">{{ number_format($exam->total_score, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
