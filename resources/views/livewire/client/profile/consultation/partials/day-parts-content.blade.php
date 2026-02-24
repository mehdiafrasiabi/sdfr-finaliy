{{-- partials/day-parts-content.blade.php --}}
@if($day['is_rest_day'])
    <div class="flex flex-col items-center justify-center py-6">
        <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 p-5 w-full text-center">
            <div class="w-12 h-12 mx-auto rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-emerald-600 dark:text-emerald-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                </svg>
            </div>
            <div class="text-sm font-bold text-emerald-700 dark:text-emerald-400 mb-1">روز استراحت</div>
            <p class="text-[11px] text-emerald-600/80 dark:text-emerald-400/70">از امروز خود لذت ببرید</p>
        </div>
    </div>
@else
    <div class="space-y-2">
        @forelse($day['parts'] as $part)
            @php
                $isExam = in_array($part->part_type, ['comprehensive_exam', 'exam_analysis']);
                $isComprehensive = $part->part_type === 'comprehensive_exam';
                $isAnalysis = $part->part_type === 'exam_analysis';
            @endphp

            {{-- کارت عادی --}}
            @if(!$isExam)
                <div class="rounded-xl border border-border dark:border-slate-700 p-2 text-[11px] leading-relaxed shadow-sm min-h-[130px] bg-secondary dark:bg-slate-800/60">
                    <div class="truncate text-[12px] font-semibold text-foreground" title="{{ $part->lesson_name }}">
                        {{ $part->lesson_name }}
                    </div>
                    <div class="mt-1 flex flex-wrap items-center gap-2 text-[11px] text-muted">
                        <span class="flex items-center gap-1">
                            <i class="fas fa-clock text-[10px]"></i>
                            {{ $part->duration_minutes }} دقیقه
                        </span>
                        @if($part->test_count)
                            <span class="flex items-center gap-1">
                                <i class="fas fa-tasks text-[10px]"></i>
                                {{ $part->test_count }} تست
                            </span>
                        @endif
                    </div>
                    @if($part->description)
                        <div class="mt-1 text-[11px] text-muted h-[32px] overflow-hidden" title="{{ $part->description }}">
                            {{ Str::limit($part->description, 300) }}
                        </div>
                    @else
                        <div class="mt-1 h-[32px]"></div>
                    @endif
                    @if($part->ccChapter || $part->ccTopic)
                        <div class="mt-1 flex flex-col gap-0.5 text-[10px] text-muted border-t border-border/50 dark:border-slate-700/50 pt-1">
                            @if($part->ccChapter)
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-bookmark text-[9px] text-violet-400"></i>
                                    <span class="font-medium text-violet-600 dark:text-violet-300">فصل:</span>
                                    <span class="truncate">{{ $part->ccChapter->name }}</span>
                                </span>
                            @endif
                            @if($part->ccTopic)
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-tag text-[9px] text-sky-400"></i>
                                    <span class="font-medium text-sky-600 dark:text-sky-300">مبحث:</span>
                                    <span class="truncate">{{ $part->ccTopic->name }}</span>
                                </span>
                            @endif
                        </div>
                    @endif
                    <div class="mt-1 flex flex-wrap items-center gap-1">
                        @if($part->part_type === 'test')
                            <span class="rounded-full bg-sky-100 dark:bg-sky-900/50 px-2 py-0.5 text-[10px] font-medium text-sky-700 dark:text-sky-200">تستی</span>
                        @elseif($part->part_type === 'descriptive')
                            <span class="rounded-full bg-emerald-100 dark:bg-emerald-900/50 px-2 py-0.5 text-[10px] font-medium text-emerald-700 dark:text-emerald-200">تشریحی</span>
                        @else
                            <span class="rounded-full bg-violet-100 dark:bg-violet-900/50 px-2 py-0.5 text-[10px] font-medium text-violet-700 dark:text-violet-200">ویدیویی</span>
                        @endif
                        <span class="rounded-full bg-muted/50 dark:bg-slate-700/60 px-2 py-0.5 text-[10px] text-muted">
                            پایه
                            @if($part->grade == 10) دهم
                            @elseif($part->grade == 11) یازدهم
                            @elseif($part->grade == 12) دوازدهم
                            @endif
                        </span>
                        @if($part->source_type && $part->source_type !== 'normal')
                            <span class="rounded-full px-2 py-0.5 text-[10px] font-medium {{ $part->source_type_tw_class }}">{{ $part->source_type_label }}</span>
                        @endif
                    </div>
                </div>

                {{-- کارت آزمون جامع --}}
            @elseif($isComprehensive)
                <div class="rounded-xl overflow-hidden border border-red-400 dark:border-red-700 shadow-md min-h-[130px]">
                    <div class="bg-gradient-to-r from-red-600 to-red-500 px-3 py-2 flex items-center gap-2">
                        <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                            </svg>
                        </div>
                        <span class="text-[11px] font-bold text-white tracking-wide">آزمون جامع</span>
                        <span class="mr-auto text-[10px] text-red-100 flex items-center gap-1">
                            <i class="fas fa-clock text-[9px]"></i>
                            {{ $part->duration_minutes }} دقیقه
                        </span>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/20 px-3 py-2">
                        <div class="truncate text-[12px] font-semibold text-red-800 dark:text-red-300" title="{{ $part->lesson_name }}">{{ $part->lesson_name }}</div>
                        @if($part->test_count)
                            <div class="mt-1 flex items-center gap-1 text-[11px] text-red-600 dark:text-red-400">
                                <i class="fas fa-tasks text-[10px]"></i>
                                <span>{{ $part->test_count }} تست</span>
                            </div>
                        @endif
                        @if($part->description)
                            <div class="mt-1 text-[11px] text-red-700/70 dark:text-red-400/70 line-clamp-2">{{ Str::limit($part->description, 300) }}</div>
                        @endif
                        @if($part->ccChapter || $part->ccTopic)
                            <div class="mt-1 flex flex-col gap-0.5 text-[10px] border-t border-red-200/50 dark:border-red-700/30 pt-1">
                                @if($part->ccChapter)
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-bookmark text-[9px] text-red-400"></i>
                                        <span class="font-medium text-red-600 dark:text-red-300">فصل:</span>
                                        <span class="truncate text-red-700/80 dark:text-red-300/80">{{ $part->ccChapter->name }}</span>
                                    </span>
                                @endif
                                @if($part->ccTopic)
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-tag text-[9px] text-red-400"></i>
                                        <span class="font-medium text-red-600 dark:text-red-300">مبحث:</span>
                                        <span class="truncate text-red-700/80 dark:text-red-300/80">{{ $part->ccTopic->name }}</span>
                                    </span>
                                @endif
                            </div>
                        @endif
                        <div class="mt-2 flex flex-wrap items-center gap-1">
                            <span class="rounded-full bg-red-200 dark:bg-red-800/60 px-2 py-0.5 text-[10px] font-medium text-red-700 dark:text-red-200">آزمون جامع</span>
                            <span class="rounded-full bg-red-100 dark:bg-red-900/40 px-2 py-0.5 text-[10px] text-red-600 dark:text-red-300">
                                پایه @if($part->grade == 10) دهم @elseif($part->grade == 11) یازدهم @elseif($part->grade == 12) دوازدهم @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- کارت تحلیل آزمون --}}
            @elseif($isAnalysis)
                <div class="rounded-xl overflow-hidden border border-orange-400 dark:border-orange-700 shadow-md min-h-[130px]">
                    <div class="bg-gradient-to-r from-orange-500 to-amber-500 px-3 py-2 flex items-center gap-2">
                        <div class="flex h-6 w-6 items-center justify-center rounded-lg bg-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                            </svg>
                        </div>
                        <span class="text-[11px] font-bold text-white tracking-wide">تحلیل آزمون</span>
                        <span class="mr-auto text-[10px] text-orange-100 flex items-center gap-1">
                            <i class="fas fa-clock text-[9px]"></i>
                            {{ $part->duration_minutes }} دقیقه
                        </span>
                    </div>
                    <div class="bg-orange-50 dark:bg-orange-900/20 px-3 py-2">
                        <div class="truncate text-[12px] font-semibold text-orange-800 dark:text-orange-300" title="{{ $part->lesson_name }}">{{ $part->lesson_name }}</div>
                        @if($part->test_count)
                            <div class="mt-1 flex items-center gap-1 text-[11px] text-orange-600 dark:text-orange-400">
                                <i class="fas fa-tasks text-[10px]"></i>
                                <span>{{ $part->test_count }} تست</span>
                            </div>
                        @endif
                        @if($part->description)
                            <div class="mt-1 text-[11px] text-orange-700/70 dark:text-orange-400/70 line-clamp-2">{{ Str::limit($part->description, 300) }}</div>
                        @endif
                        <div class="mt-2 flex flex-wrap items-center gap-1">
                            <span class="rounded-full bg-orange-200 dark:bg-orange-800/60 px-2 py-0.5 text-[10px] font-medium text-orange-700 dark:text-orange-200">تحلیل آزمون</span>
                            <span class="rounded-full bg-orange-100 dark:bg-orange-900/40 px-2 py-0.5 text-[10px] text-orange-600 dark:text-orange-300">
                                پایه @if($part->grade == 10) دهم @elseif($part->grade == 11) یازدهم @elseif($part->grade == 12) دوازدهم @endif
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="py-4 text-center text-[11px] text-muted">بدون برنامه</div>
        @endforelse
    </div>

    @if($day['parts']->count() > 0)
        <div class="mt-3 border-t border-dashed border-border dark:border-slate-700 pt-2 text-[11px] text-muted">
            <div class="flex justify-between">
                <span>ساعت:</span>
                <span class="font-medium text-foreground">{{ $day['total_hours'] }}</span>
            </div>
            <div class="flex justify-between">
                <span>تست:</span>
                <span class="font-medium text-foreground">{{ $day['total_tests'] }}</span>
            </div>
        </div>
    @endif
@endif
