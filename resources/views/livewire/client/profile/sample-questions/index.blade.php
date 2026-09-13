<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-6">

                    {{-- Section title --}}
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div>
                            <div class="font-black text-foreground">نمونه سوالات تشریحی</div>
                            @if($setting)
                                <div class="text-xs text-muted mt-1">{{ $setting->grade_label }} / {{ $setting->field_label }}</div>
                            @elseif($profileLabel)
                                <div class="text-xs text-muted mt-1">{{ $profileLabel }}</div>
                            @endif
                        </div>

                        <x-ui.button href="{{ route('client.profile.dashboard') }}" wire:navigate
                                     variant="primary" icon="chevron-left" pill class="ms-auto">
                            داشبورد
                        </x-ui.button>
                    </div>

                    {{-- Filters --}}
                    <div class="space-y-3">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="جستجو در نمونه سوالات..."
                                   class="form-input w-full h-10 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground pe-10 ps-4"/>
                            <x-ui.icon name="search" class="size-4 absolute top-3 end-3 text-muted"/>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <x-ui.select wire:model.live="subjectFilter"
                                         :options="$books"
                                         value-key="id" label-key="name" placeholder="همه کتاب‌ها"/>
                            <x-ui.select wire:model.live="periodMonthFilter"
                                         :options="$periodMonths"
                                         value-key="id" label-key="name" placeholder="همه زمان‌ها"/>
                            <x-ui.select wire:model.live="periodYearFilter"
                                         :options="$periodYears"
                                         value-key="id" label-key="name" placeholder="همه سال‌ها"/>
                        </div>
                    </div>

                    {{-- Sample questions list — same box pattern as tickets --}}
                    <div class="space-y-4">
                        @forelse($questions as $question)
                            @php
                                $subjectName = $question->subject->name ?? 'بدون کتاب';
                                $book = $books->firstWhere('id', (int) $question->cc_subject_id);
                                $bookType = $book['type'] ?? $question->subject?->type;
                                $typeLabel = $bookType === 'general'
                                    ? 'عمومی'
                                    : ($bookType === 'specialized' ? 'تخصصی' : 'کتاب');
                                $periodLabel = $question->exam_period_label;
                            @endphp

                            <div class="glass border border-border rounded-2xl overflow-hidden flex flex-col">

                                {{-- Mobile --}}
                                <div class="md:hidden">
                                    <x-ui.thumbnail class="relative w-full h-36">
                                        <img src="/client/icons/exam-description.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">

                                        @if($question->is_main)
                                            <x-ui.status-badge status="active" label="اصلی" class="absolute top-3 left-3 shadow-lg"/>
                                        @endif
                                    </x-ui.thumbnail>

                                    <div class="p-4 space-y-3" dir="rtl">
                                        <h3 class="font-bold text-foreground text-base break-words">{{ $question->title }}</h3>

                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-muted">
                                            <span class="font-semibold">{{ $subjectName }}</span>
                                            <span>{{ $typeLabel }}</span>
                                            <span>{{ $periodLabel }}</span>
                                            <span>زمان آزمون: {{ $question->duration_minutes }} دقیقه</span>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full bg-background border border-border text-muted">
                                                PDF
                                            </span>
                                            <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full bg-primary/10 text-primary">
                                                {{ $periodLabel }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="px-4 pb-4">
                                        <x-ui.button href="{{ $question->download_url }}" download variant="primary" icon="download" block>
                                            دانلود PDF
                                        </x-ui.button>
                                    </div>
                                </div>

                                {{-- Desktop --}}
                                <div class="hidden md:flex flex-row min-h-[130px]">
                                    {{-- دارک‌مودِ دسکتاپ عمداً همون هگزِ سفارشیِ #1e3a5f/#1e40af نگه داشته
                                         شده (نه x-ui.thumbnail)، طبق همون قرارِ قبلی درباره‌ی این گرادیان‌ها --}}
                                    <div class="relative flex-shrink-0 w-[120px] flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-[#1e3a5f] dark:to-[#1e40af]">
                                        <img src="/client/icons/exam-description.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">

                                        @if($question->is_main)
                                            <x-ui.status-badge status="active" label="اصلی" class="absolute top-2 left-2 shadow-lg"/>
                                        @endif
                                    </div>

                                    <div class="flex-1 p-4 flex items-center justify-between gap-4" dir="rtl">
                                        <div class="space-y-2 flex-1 min-w-0">
                                            <h3 class="font-bold text-foreground text-base truncate">{{ $question->title }}</h3>

                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-muted">
                                                <span class="font-semibold">{{ $subjectName }}</span>
                                                <span>{{ $typeLabel }}</span>
                                                <span>{{ $periodLabel }}</span>
                                                <span>زمان آزمون: {{ $question->duration_minutes }} دقیقه</span>
                                            </div>

                                            <div class="flex flex-wrap items-center gap-1.5">

                                                <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-0.5 rounded-full bg-background border border-border text-muted">
                                                    PDF
                                                </span>
                                                <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-0.5 rounded-full bg-primary/10 text-primary">
                                                    {{ $periodLabel }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                                            <x-ui.button href="{{ $question->download_url }}" download variant="primary" icon="download">
                                                دانلود
                                            </x-ui.button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <x-ui.empty-state title="نمونه سوالی برای نمایش وجود ندارد.">
                                اگر کتابی را انتخاب کرده‌اید، فیلتر کتاب را تغییر دهید.
                            </x-ui.empty-state>
                        @endforelse

                        @if($questions->isNotEmpty())
                            <div class="mt-2">
                                {{ $questions->links('components.ui.pagination') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
