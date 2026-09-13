<div x-data="{ activeTab: @js($activeTab) }">
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
                        <x-ui.segmented-tabs
                            :items="[
                                'typed' => 'آزمون تستی',
                                'essay' => 'آزمون تشریحی',
                            ]"
                            :badges="[
                                'typed' => $this->typedPendingCount,
                                'essay' => $this->essayPendingCount,
                            ]"
                            :active="$activeTab"
                            @segmented-change="activeTab = $event.detail"
                        />

                        {{-- ✅ سوییچ تب‌ها دیگه رفت‌وبرگشت به سرور نداره: هر دو لیست (تستی/تشریحی) همین الان
                             هم توی هر رندر لود می‌شن (چون بج تعداد هر دو تب همیشه لازمه)، پس دیتا از قبل
                             آماده‌ست و فقط با Alpine نمایش/مخفی می‌شه - نه تاخیری، نه لودینگ ساختگی، و نه
                             ریس‌کاندیشنی که باعث می‌شد با کلیک سریع پشت‌سرهم، ایندیکیتور تب با محتوای
                             نمایش‌داده‌شده هماهنگ نباشه. --}}
                        <div x-show="activeTab === 'essay'" x-cloak>
                            @include('livewire.client.profile.typed-exam._essay-list', ['essayAssignments' => $essayAssignments])
                        </div>
                        <div x-show="activeTab === 'typed'" x-cloak>
                        @if($assignments->isEmpty())
                            <x-ui.empty-state title="آزمونی برای شما وجود ندارد!">
                                هنوز آزمونی برای شما ثبت نشده است.
                            </x-ui.empty-state>
                        @else
                            <div class="space-y-4">
                                @foreach($assignments as $assignment)
                                    @php
                                        $exam = $assignment->typedExam;
                                        [$statusKey, $statusLabel] = match ($assignment->computed_status) {
                                            'not_started' => ['not_started', null],
                                            'available'   => ['joinable', null],
                                            'expired'     => ['expired', null],
                                            'completed'   => ['paid', 'تکمیل شده'],
                                            default       => ['inactive', null],
                                        };
                                    @endphp

                                    <div wire:key="exam-card-{{ $assignment->id }}"
                                         x-data="{ expanded: false }"
                                         class="glass border border-border rounded-2xl overflow-hidden flex flex-col">

                                        {{-- ═══ موبایل ═══ --}}
                                        <div class="md:hidden">
                                            <x-ui.thumbnail class="w-full h-36">
                                                <img src="/client/icons/exam-test.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">
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

                                                    @if($assignment->computed_status === 'completed' && $assignment->latestAttempt?->score !== null)
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary text-xs rounded-full">
                                                            نمره: {{ number_format($assignment->latestAttempt->score, 1) }}%
                                                        </span>
                                                    @endif

                                                    @if($assignment->computed_status === 'completed' && empty($assignment->latestAttempt?->analysis_status))
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-warning text-warning-foreground text-xs rounded-full">
                                                            عدم آپلود تحلیل توسط دانش آموز
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="px-4 pb-4 space-y-2" dir="rtl">
                                                @if($assignment->can_start)
                                                    <x-ui.button type="button" wire:click="confirmEntry({{ $assignment->id }}, 'typed')"
                                                                 variant="primary" icon="chevron-left" block>
                                                        ورود به آزمون
                                                    </x-ui.button>
                                                @elseif($assignment->computed_status === 'completed')
                                                    <x-ui.button href="{{ route('client.profile.typed-exam.result', ['attemptId' => $assignment->latestAttempt->id]) }}"
                                                                 variant="success" icon="eye" block>
                                                        مشاهده نتیجه
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
                                                <img src="/client/icons/exam-test.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">
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

                                                        @if($assignment->computed_status === 'completed' && $assignment->latestAttempt?->score !== null)
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-primary/10 text-primary text-xs rounded-full">
                                                              %{{ number_format($assignment->latestAttempt->score, 1) }} درصد
                                                            </span>
                                                        @endif

                                                        @if($assignment->computed_status === 'completed' && empty($assignment->latestAttempt?->analysis_status))
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-warning text-warning-foreground text-xs rounded-full">
                                                                عدم آپلود تحلیل توسط دانش آموز
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                                                    @if($assignment->can_start)
                                                        <x-ui.button type="button" wire:click="confirmEntry({{ $assignment->id }}, 'typed')"
                                                                     variant="primary" icon="chevron-left">
                                                            ورود به آزمون
                                                        </x-ui.button>
                                                    @elseif($assignment->computed_status === 'completed')
                                                        <x-ui.button href="{{ route('client.profile.typed-exam.result', ['attemptId' => $assignment->latestAttempt->id]) }}"
                                                                     variant="success" icon="eye">
                                                            مشاهده نتیجه
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
                                                    <span class="text-xs text-muted">نام دفترچه</span>
                                                    <span class="font-bold text-foreground text-sm mt-1 text-center">{{ $exam->title }}</span>
                                                </div>
                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                    <x-ui.icon name="clock" class="w-6 h-6 mb-2 text-warning"/>
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
                                                    <x-ui.icon name="list-check" class="w-6 h-6 text-success mb-2"/>
                                                    <span class="text-xs text-muted">تعداد سوالات</span>
                                                    <span class="font-bold text-foreground text-sm mt-1">{{ $exam->questions_total }} سوال</span>
                                                </div>
                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                    <x-ui.icon name="square-pen" class="w-6 h-6 mb-2 text-info"/>
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
    </div>

    {{-- ═══════════════ مودال شروع آزمون — یکسان‌شده با الگوی مشترک مودال‌های پروژه ═══════════════ --}}
    @php
        $selectedAssignment = $confirmingExamType === 'essay'
            ? $essayAssignments->firstWhere('id', $confirmingExamId)
            : $assignments->firstWhere('id', $confirmingExamId);
        $selectedExam = $confirmingExamType === 'essay'
            ? $selectedAssignment?->essayExam
            : $selectedAssignment?->typedExam;
    @endphp
    <div
        x-data="{ confirmingId: @entangle('confirmingExamId') }"
        x-effect="confirmingId !== null ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
        x-cloak
    >
        <div
            x-show="confirmingId !== null"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[80] bg-black/60 backdrop-blur-sm"
            @click="$wire.closeModal()"
        ></div>

        <div
            x-show="confirmingId !== null"
            class="fixed inset-0 z-[81] flex items-end justify-center overscroll-contain sm:items-center sm:p-4"
            @click.self="$wire.closeModal()"
        >
            <div
                x-show="confirmingId !== null"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                class="relative w-full sm:max-w-md bg-background border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl flex flex-col pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
            >
                <div class="sm:hidden flex justify-center pt-3 pb-1 shrink-0">
                    <div class="w-10 h-1 rounded-full bg-border"></div>
                </div>
                <div class="p-6">
                    <div class="flex flex-col items-center justify-center space-y-5">
                        <div class="flex items-center justify-center w-20 h-20 bg-success/10 rounded-full">
                            <x-ui.icon name="clock" class="w-10 h-10 text-success"/>
                        </div>
                        <h3 class="font-bold text-xl text-foreground">{{ $selectedExam?->title ?? 'آزمون' }}</h3>
                        <p class="text-center text-muted text-sm leading-relaxed">
                            حواستون باشه از زمانی که دکمه شرکت در آزمون رو می‌زنید، زمان برای شما در نظر گرفته میشه!
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-x-4 border-t border-border p-4">
                    <x-ui.button type="button" wire:click="closeModal" variant="secondary-outline" icon="x" block>
                        لغو
                    </x-ui.button>
                    <x-ui.button type="button" wire:click="enterExam" wire:loading.attr="disabled" wire:target="enterExam"
                                 variant="primary" block>
                        <span wire:loading.remove wire:target="enterExam" class="inline-flex items-center gap-1.5">
                            شروع <x-ui.icon name="chevron-left" class="w-4 h-4"/>
                        </span>
                        <span wire:loading wire:target="enterExam">
                            <x-ui.spinner size="xs"/>
                        </span>
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>
</div>
