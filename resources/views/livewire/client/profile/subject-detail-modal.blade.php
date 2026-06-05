<div
    x-data="{ open: @entangle('open') }"
    x-effect="document.body.classList.toggle('overflow-hidden', open)"
    x-cloak
>
    {{-- ========== Backdrop مشترک ========== --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm"
        @click="$wire.close()"
        aria-hidden="true"
    ></div>

    {{-- ================== مودال دسکتاپ (نمایش در md به بالا) ================== --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="fixed inset-0 z-[100] hidden md:flex items-center justify-center p-4"
        @click.self="$wire.close()"
    >
        <div class="relative w-full max-w-2xl max-h-[90vh] overflow-auto rounded-2xl bg-background border border-border shadow-2xl">
            {{-- resources/views/livewire/client/profile/_modal-content.blade.php --}}
            <button @click="$wire.close()" class="absolute top-4 right-4 p-1.5 rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>

            <div class="p-6">
                {{-- عنوان --}}
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-foreground">{{ $subjectName }}</h2>
                        <p class="text-xs text-muted">پیشرفت فصل‌ها در این ماه</p>
                    </div>
                </div>

                @if(empty($subjectData))
                    <div class="py-10 text-center text-muted">
                        <svg class="mx-auto mb-3 w-16 h-16 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375"/>
                        </svg>
                        <p>داده‌ای برای این درس ثبت نشده است.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($subjectData as $chapter)
                            <div class="bg-secondary border border-border rounded-xl p-4 transition-all hover:border-primary/40">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-bold text-foreground">{{ $chapter['chapter_name'] }}</span>
                                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $chapter['percent'] >= 70 ? 'bg-emerald-500/15 text-emerald-600' : ($chapter['percent'] >= 40 ? 'bg-amber-500/15 text-amber-600' : 'bg-red-500/15 text-red-600') }}">
                            {{ $chapter['percent'] }}%
                        </span>
                                </div>
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="flex-1 h-2 bg-background border border-border rounded-full overflow-hidden">
                                        <div class="h-full rounded-full progress-fill {{ $chapter['percent'] >= 70 ? 'bg-emerald-500' : ($chapter['percent'] >= 40 ? 'bg-amber-500' : 'bg-red-500') }}"
                                             style="width: {{ min(100, $chapter['percent']) }}%"></div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 text-[11px] text-muted">
                                    <span>📚 {{ $chapter['parts_studied'] }}/{{ $chapter['parts_total'] }} پارت مطالعه‌شده</span>
                                    <span class="opacity-50">|</span>
                                    <span>⏱️ {{ $this->formatMinutes($chapter['studied_minutes']) }} از {{ $this->formatMinutes($chapter['planned_minutes']) }}</span>
                                </div>
                                <div class="flex items-center gap-1 mt-2 flex-wrap text-[10px]">
                                    @if($chapter['quality']['عالی'])
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-600 font-semibold">عالی: {{ $chapter['quality']['عالی'] }}</span>
                                    @endif
                                    @if($chapter['quality']['با کیفیت'])
                                        <span class="px-2 py-0.5 rounded-full bg-blue-500/15 text-blue-600 font-semibold">با کیفیت: {{ $chapter['quality']['با کیفیت'] }}</span>
                                    @endif
                                    @if($chapter['quality']['بی‌کیفیت'])
                                        <span class="px-2 py-0.5 rounded-full bg-red-500/15 text-red-600 font-semibold">بی‌کیفیت: {{ $chapter['quality']['بی‌کیفیت'] }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ================== مودال موبایل (Bottom Sheet) ================== --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        class="fixed inset-x-0 bottom-0 z-[101] md:hidden flex flex-col max-h-[90vh] rounded-t-2xl bg-background border-t border-x border-border shadow-2xl"
        @click.self="$wire.close()"
    >
        {{-- هندل کشیدن (اختیاری، برای حس بهتر) --}}
        <div class="flex justify-center pt-2 pb-1">
            <div class="w-10 h-1.5 rounded-full bg-border"></div>
        </div>
        <div class="overflow-auto flex-1">
            {{-- resources/views/livewire/client/profile/_modal-content.blade.php --}}
            <button @click="$wire.close()" class="absolute top-2 right-4 p-1.5 rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors mb-1">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>

            <div class="p-6">
                {{-- عنوان --}}
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-foreground">{{ $subjectName }}</h2>
                        <p class="text-xs text-muted">پیشرفت فصل‌ها در این ماه</p>
                    </div>
                </div>

                @if(empty($subjectData))
                    <div class="py-10 text-center text-muted">
                        <svg class="mx-auto mb-3 w-16 h-16 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375"/>
                        </svg>
                        <p>داده‌ای برای این درس ثبت نشده است.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($subjectData as $chapter)
                            <div class="bg-secondary border border-border rounded-xl p-4 transition-all hover:border-primary/40">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-bold text-foreground">{{ $chapter['chapter_name'] }}</span>
                                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $chapter['percent'] >= 70 ? 'bg-emerald-500/15 text-emerald-600' : ($chapter['percent'] >= 40 ? 'bg-amber-500/15 text-amber-600' : 'bg-red-500/15 text-red-600') }}">
                            {{ $chapter['percent'] }}%
                        </span>
                                </div>
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="flex-1 h-2 bg-background border border-border rounded-full overflow-hidden">
                                        <div class="h-full rounded-full progress-fill {{ $chapter['percent'] >= 70 ? 'bg-emerald-500' : ($chapter['percent'] >= 40 ? 'bg-amber-500' : 'bg-red-500') }}"
                                             style="width: {{ min(100, $chapter['percent']) }}%"></div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 text-[11px] text-muted">
                                    <span>📚 {{ $chapter['parts_studied'] }}/{{ $chapter['parts_total'] }} پارت مطالعه‌شده</span>
                                    <span class="opacity-50">|</span>
                                    <span>⏱️ {{ $this->formatMinutes($chapter['studied_minutes']) }} از {{ $this->formatMinutes($chapter['planned_minutes']) }}</span>
                                </div>
                                <div class="flex items-center gap-1 mt-2 flex-wrap text-[10px]">
                                    @if($chapter['quality']['عالی'])
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-600 font-semibold">عالی: {{ $chapter['quality']['عالی'] }}</span>
                                    @endif
                                    @if($chapter['quality']['با کیفیت'])
                                        <span class="px-2 py-0.5 rounded-full bg-blue-500/15 text-blue-600 font-semibold">با کیفیت: {{ $chapter['quality']['با کیفیت'] }}</span>
                                    @endif
                                    @if($chapter['quality']['بی‌کیفیت'])
                                        <span class="px-2 py-0.5 rounded-full bg-red-500/15 text-red-600 font-semibold">بی‌کیفیت: {{ $chapter['quality']['بی‌کیفیت'] }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
