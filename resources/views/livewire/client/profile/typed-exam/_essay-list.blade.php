@if($essayAssignments->isEmpty())
    <x-ui.empty-state title="آزمون تشریحی برای شما وجود ندارد!">
        هنوز آزمون تشریحی برای شما ثبت نشده است.
    </x-ui.empty-state>
@else
    <div class="space-y-4">
        @foreach($essayAssignments as $assignment)
            @php
                $exam = $assignment->essayExam;
                [$statusKey, $statusLabel] = match ($assignment->computed_status) {
                    'not_started' => ['not_started', null],
                    'available'   => ['joinable', null],
                    'expired'     => ['expired', null],
                    'submitted'   => ['pending', 'ارسال شده (در انتظار تصحیح)'],
                    'completed'   => ['paid', 'تصحیح شده'],
                    default       => ['inactive', null],
                };
            @endphp

            <div x-data="{ expanded: false }"
                 class="glass border border-border rounded-2xl overflow-hidden flex flex-col">

                {{-- ═══ موبایل ═══ --}}
                <div class="md:hidden">
                    <x-ui.thumbnail class="w-full h-36">
                        <img src="/client/icons/exam-description.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">
                    </x-ui.thumbnail>

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
                            <x-ui.status-badge :status="$statusKey" :label="$statusLabel"/>
                            @if($assignment->computed_status === 'completed' && $assignment->latestAttempt?->total_score !== null)
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary text-xs rounded-full">
                                    نمره: {{ number_format($assignment->latestAttempt->total_score, 2) }} / {{ number_format($exam->total_score, 2) }}
                                </span>
                            @endif
                        </div>

                        @if($assignment->computed_status === 'available')
                            <div class="bg-background rounded-xl border border-border p-3">
                                <div class="text-xs text-muted mb-2">قبل از شروع می‌توانید پاسخ‌برگ را مشاهده/دانلود کنید.</div>
                                <x-ui.button href="{{ route('client.profile.essay-exam.answer-sheet', ['assignmentId' => $assignment->id]) }}"
                                             target="_blank" variant="outline" icon="download" size="sm">
                                    دانلود پاسخ‌برگ (PDF)
                                </x-ui.button>
                            </div>
                        @endif
                    </div>

                    <div class="px-4 pb-4 space-y-2" dir="rtl">
                        @if($assignment->can_start)
                            <x-ui.button type="button" wire:click="confirmEntry({{ $assignment->id }}, 'essay')"
                                         variant="primary" icon="chevron-left" block>
                                ورود به آزمون
                            </x-ui.button>
                        @elseif($assignment->computed_status === 'completed')
                            <x-ui.button href="{{ route('client.profile.essay-exam.result', ['attemptId' => $assignment->latestAttempt->id]) }}"
                                         variant="success" icon="eye" block>
                                مشاهده نتیجه
                            </x-ui.button>
                        @elseif($assignment->computed_status === 'submitted')
                            <x-ui.button type="button" variant="warning-soft" icon="clock" disabled block>
                                در انتظار تصحیح
                            </x-ui.button>
                        @elseif($assignment->computed_status === 'not_started')
                            <x-ui.button type="button" variant="secondary" icon="clock" disabled block>
                                منتظر شروع
                            </x-ui.button>
                        @else
                            <x-ui.button type="button" variant="error-soft" icon="ban" disabled block>
                                غیرفعال
                            </x-ui.button>
                        @endif

                        <button type="button" @click="expanded = !expanded" data-elevated="false"
                                class="btn-press w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                            <span>مشاهده جزئیات</span>
                            <x-ui.icon name="chevron-down" class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': expanded }"/>
                        </button>
                    </div>
                </div>

                {{-- ═══ دسکتاپ ═══ --}}
                <div class="hidden md:flex flex-row min-h-[130px]">
                    {{-- دارک‌مودِ دسکتاپ عمداً همون هگزِ سفارشیِ #1e3a5f/#1e40af نگه داشته
                         شده (نه x-ui.thumbnail)، طبق همون قرارِ قبلی درباره‌ی این گرادیان‌ها --}}
                    <div class="flex-shrink-0 w-[120px] flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-[#1e3a5f] dark:to-[#1e40af]">
                        <img src="/client/icons/exam-description.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">
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
                                <x-ui.status-badge :status="$statusKey" :label="$statusLabel"/>
                                @if($assignment->computed_status === 'completed' && $assignment->latestAttempt?->total_score !== null)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-primary/10 text-primary text-xs rounded-full">
                                        نمره: {{ number_format($assignment->latestAttempt->total_score, 2) }} / {{ number_format($exam->total_score, 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                            @if($assignment->can_start)
                                <x-ui.button type="button" wire:click="confirmEntry({{ $assignment->id }}, 'essay')"
                                             variant="primary" icon="chevron-left">
                                    ورود به آزمون
                                </x-ui.button>
                            @elseif($assignment->computed_status === 'completed')
                                <x-ui.button href="{{ route('client.profile.essay-exam.result', ['attemptId' => $assignment->latestAttempt->id]) }}"
                                             variant="success" icon="eye">
                                    مشاهده نتیجه
                                </x-ui.button>
                            @elseif($assignment->computed_status === 'submitted')
                                <x-ui.button type="button" variant="warning-soft" icon="clock" disabled>
                                    در انتظار تصحیح
                                </x-ui.button>
                            @elseif($assignment->computed_status === 'not_started')
                                <x-ui.button type="button" variant="secondary" icon="clock" disabled>
                                    منتظر شروع
                                </x-ui.button>
                            @else
                                <x-ui.button type="button" variant="error-soft" icon="ban" disabled>
                                    غیرفعال
                                </x-ui.button>
                            @endif

                            <button type="button" @click="expanded = !expanded" data-elevated="false"
                                    class="btn-press inline-flex items-center justify-center gap-2 px-4 py-2 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                <x-ui.icon name="chevron-down" class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': expanded }"/>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ═══ نوار دانلود پاسخ‌برگ (دسکتاپ - فقط available) ═══ --}}
                @if($assignment->computed_status === 'available')
                    <div class="hidden md:block border-border bg-background/50 px-4 py-3">
                        <div class="flex items-center justify-between flex-wrap gap-2" dir="rtl">
                            <div class="text-xs text-muted">قبل از شروع می‌توانید پاسخ‌برگ را مشاهده/دانلود کنید.</div>
                            <x-ui.button href="{{ route('client.profile.essay-exam.answer-sheet', ['assignmentId' => $assignment->id]) }}"
                                         target="_blank" variant="outline" icon="download" size="sm">
                                دانلود پاسخ‌برگ (PDF)
                            </x-ui.button>
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
                            <x-ui.icon name="book-open" class="w-6 h-6 text-primary mb-2"/>
                            <span class="text-xs text-muted">عنوان آزمون</span>
                            <span class="font-bold text-foreground text-sm mt-1 text-center">{{ $exam->title }}</span>
                        </div>
                        <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                            <x-ui.icon name="clock" class="w-6 h-6 mb-2 text-warning"/>
                            <span class="text-xs text-muted">مدت زمان</span>
                            <span class="font-bold text-foreground text-sm mt-1">{{ $assignment->time?->duration_minutes ?? '—' }} دقیقه</span>
                        </div>
                        <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                            <x-ui.icon name="list-check" class="w-6 h-6 text-success mb-2"/>
                            <span class="text-xs text-muted">تعداد سوالات</span>
                            <span class="font-bold text-foreground text-sm mt-1">{{ $exam->questions_total }} سوال</span>
                        </div>
                        <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                            <x-ui.icon name="star" class="w-6 h-6 mb-2 text-info"/>
                            <span class="text-xs text-muted">نمره کل</span>
                            <span class="font-bold text-foreground text-sm mt-1">{{ number_format($exam->total_score, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
