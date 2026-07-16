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
                        <div class="font-black text-foreground">نمونه سوالات تشریحی</div>

                        <a wire:navigate href="{{ route('client.profile.dashboard') }}"
                           class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-primary rounded-full text-primary-foreground transition-colors hover:bg-foreground hover:text-background px-6 ms-auto">
                            <span class="font-semibold text-xs">داشبورد</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Filters --}}
                    <div class="space-y-3">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="جستجو در نمونه سوالات..."
                                   class="form-input w-full h-10 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground pe-10 ps-4"/>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-4 absolute top-3 end-3 text-muted">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                            </svg>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <x-ui.select wire:model.live="subjectFilter"
                                         :options="$books"
                                         value-key="id" label-key="name" placeholder="همه کتاب‌ها"/>
                        </div>
                    </div>

                    {{-- Sample questions list — same box pattern as tickets --}}
                    <div class="space-y-4">
                        @forelse($questions as $question)
                            @php
                                $subjectName = $question->subject->name ?? 'بدون کتاب';
                                $book = $books->firstWhere('id', (int) $question->cc_subject_id);
                                $typeLabel = ($book['type'] ?? null) === 'general' ? 'عمومی' : 'تخصصی';
                                $mainBg = $question->is_main ? 'bg-green-500/10' : 'bg-background';
                                $mainFg = $question->is_main ? 'text-green-500' : 'text-muted';
                                $mainDot = $question->is_main ? 'bg-green-500' : 'bg-muted';
                            @endphp

                            <div class="glass border border-border rounded-2xl overflow-hidden flex flex-col">

                                {{-- Mobile --}}
                                <div class="md:hidden">
                                    <div class="relative w-full h-36 flex items-center justify-center bg-gradient-to-b from-blue-100 to-blue-200 dark:from-blue-950 dark:to-blue-900">
                                        <img src="/client/icons/exam-description.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">

                                        @if($question->is_main)
                                            <span class="absolute top-3 left-3 inline-flex items-center justify-center h-[22px] px-2 bg-green-500 rounded-full text-white text-[11px] font-bold shadow-lg">
                                                اصلی
                                            </span>
                                        @endif
                                    </div>

                                    <div class="p-4 space-y-3" dir="rtl">
                                        <h3 class="font-bold text-foreground text-base break-words">{{ $question->title }}</h3>

                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-muted">
                                            <span class="font-semibold">{{ $subjectName }}</span>
                                            <span>{{ $typeLabel }}</span>
                                            <span>زمان آزمون: {{ $question->duration_minutes }} دقیقه</span>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full {{ $mainBg }} {{ $mainFg }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $mainDot }}"></span>
                                                {{ $question->is_main ? 'نمونه اصلی' : 'نمونه تمرینی' }}
                                            </span>
                                            <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full bg-background border border-border text-muted">
                                                PDF
                                            </span>
                                        </div>
                                    </div>

                                    <div class="px-4 pb-4">
                                        <a href="{{ $question->download_url }}" download
                                           class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                            دانلود PDF
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m7 10 5 5 5-5"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 21h14"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                                {{-- Desktop --}}
                                <div class="hidden md:flex flex-row min-h-[130px]">
                                    <div class="relative flex-shrink-0 w-[120px] flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-[#1e3a5f] dark:to-[#1e40af]">
                                        <img src="/client/icons/exam-description.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">

                                        @if($question->is_main)
                                            <span class="absolute top-2 left-2 inline-flex items-center justify-center h-5 px-2 bg-green-500 rounded-full text-white text-[10px] font-bold shadow-lg">
                                                اصلی
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex-1 p-4 flex items-center justify-between gap-4" dir="rtl">
                                        <div class="space-y-2 flex-1 min-w-0">
                                            <h3 class="font-bold text-foreground text-base truncate">{{ $question->title }}</h3>

                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-muted">
                                                <span class="font-semibold">{{ $subjectName }}</span>
                                                <span>{{ $typeLabel }}</span>
                                                <span>زمان آزمون: {{ $question->duration_minutes }} دقیقه</span>
                                            </div>

                                            <div class="flex flex-wrap items-center gap-1.5">
                                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $mainBg }} {{ $mainFg }}">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $mainDot }}"></span>
                                                    {{ $question->is_main ? 'نمونه اصلی' : 'نمونه تمرینی' }}
                                                </span>
                                                <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-0.5 rounded-full bg-background border border-border text-muted">
                                                    PDF
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                                            <a href="{{ $question->download_url }}" download
                                               class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                                دانلود
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m7 10 5 5 5-5"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 21h14"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center space-y-6 py-12">
                                <img src="/client/svg/empty2.svg"
                                     class="w-full max-w-[370px] md:max-w-xs opacity-35 mb-4 md:mb-6"
                                     alt="نمونه سوالی وجود ندارد"/>
                                <div class="text-center space-y-3">
                                    <h2 class="font-bold text-xl text-foreground">نمونه سوالی برای نمایش وجود ندارد.</h2>
                                    <p class="text-sm text-muted">اگر کتابی را انتخاب کرده‌اید، فیلتر کتاب را تغییر دهید.</p>
                                </div>
                            </div>
                        @endforelse

                        @if($questions->isNotEmpty())
                            <div class="p-3 text-xs text-muted">
                                {{ $questions->links('layouts.client.pagination') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
