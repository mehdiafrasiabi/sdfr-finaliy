@if($essayAssignments->isEmpty())
    <div class="flex flex-col items-center justify-center space-y-12 py-16">
        <div class="flex flex-col items-center justify-center space-y-12">
            <img src="/client/empty/exam.png" class="w-full max-w-xs" alt="empty"/>
            <div class="text-center space-y-3">
                <h2 class="font-bold text-xl text-foreground">
                    آزمون تشریحی برای شما وجود ندارد!
                    <p class="text-muted text-sm">هنوز آزمون تشریحی برای شما ثبت نشده است.</p>
                </h2>
            </div>
        </div>
    </div>
@else
    <div class="space-y-4">
        @foreach($essayAssignments as $assignment)
            @php
                $exam = $assignment->essayExam;
                $isExpanded = in_array($assignment->id, $expandedExams);
            @endphp
            <div class="bg-secondary border border-border rounded-2xl overflow-hidden flex flex-col">
                <div class="p-4 flex-1 flex flex-col gap-4">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-500"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </div>
                            <div class="flex-1" style="margin-right: 10px">
                                <h3 class="font-bold text-foreground text-lg">{{ $exam->title }}</h3>
                                @if($assignment->time_range)
                                    <p class="text-sm text-muted mt-1">
                                        <span class="inline-flex items-center gap-1">
                                            {{ $assignment->time_range['start_date'] }} ساعت {{ $assignment->time_range['start_time'] }}
                                            تا
                                            {{ $assignment->time_range['end_date'] }} ساعت {{ $assignment->time_range['end_time'] }}
                                        </span>
                                    </p>
                                @endif
                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    @switch($assignment->computed_status)
                                        @case('not_started')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 text-xs rounded-full">هنوز شروع نشده</span>
                                            @break
                                        @case('available')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-500 text-xs rounded-full">قابل شرکت</span>
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
                            </div>
                        </div>
                    </div>

                    <!-- Answer-sheet preview (if exam is active) -->
                    @if($assignment->computed_status === 'available')
                        <div class="bg-background rounded-xl border border-border p-3">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <div class="text-sm text-muted">
                                    قبل از شروع می‌توانید پاسخ‌برگ را مشاهده/دانلود کنید.
                                </div>
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

                    <div class="mt-2 pt-3 border-t border-border flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 md:gap-3">
                        @if($assignment->can_start)
                            <button wire:click="confirmEntry({{ $assignment->id }})"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                ورود به آزمون
                            </button>
                        @elseif($assignment->computed_status === 'completed')
                            <a href="{{ route('client.profile.essay-exam.result', ['attemptId' => $assignment->latestAttempt->id]) }}"
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold text-sm transition-colors">
                                مشاهده نتیجه
                            </a>
                        @elseif($assignment->computed_status === 'submitted')
                            <button disabled class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-muted text-indigo-500 rounded-xl font-semibold text-sm cursor-not-allowed">
                                در انتظار تصحیح
                            </button>
                        @elseif($assignment->computed_status === 'not_started')
                            <button disabled class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-muted text-yellow-500 rounded-xl font-semibold text-sm cursor-not-allowed">
                                منتظر شروع
                            </button>
                        @else
                            <button disabled class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-muted text-red-500 rounded-xl font-semibold text-sm cursor-not-allowed">
                                غیرفعال
                            </button>
                        @endif

                        <button wire:click="toggleDetails({{ $assignment->id }})"
                                class="w-full sm:w-auto inline-flex items-center justify-between sm:justify-center gap-3 px-4 py-2.5 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                            <span class="md:hidden">مشاهده جزئیات</span>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5 transition-transform {{ $isExpanded ? 'rotate-180' : '' }}"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @if($isExpanded)
                    <div class="border-t border-border bg-background/50 p-4">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                <span class="text-xs text-muted">عنوان آزمون</span>
                                <span class="font-bold text-foreground text-sm mt-1">{{ $exam->title }}</span>
                            </div>
                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                <span class="text-xs text-muted">مدت زمان</span>
                                <span class="font-bold text-foreground text-sm mt-1">
                                    {{ $assignment->time?->duration_minutes ?? '—' }} دقیقه
                                </span>
                            </div>
                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                <span class="text-xs text-muted">تعداد سوالات</span>
                                <span class="font-bold text-foreground text-sm mt-1">{{ $exam->questions->count() }} سوال</span>
                            </div>
                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                <span class="text-xs text-muted">نمره کل</span>
                                <span class="font-bold text-foreground text-sm mt-1">{{ number_format($exam->total_score, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <br>
        @endforeach
    </div>
@endif
