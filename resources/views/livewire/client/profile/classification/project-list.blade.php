<div>
    <div class="max-w-7xl mx-auto px-4 pb-16" dir="rtl">

        @push('link')
            <style>
                @keyframes slideUp {
                    from { opacity: 0; transform: translateY(16px); }
                    to   { opacity: 1; transform: translateY(0); }
                }
                @keyframes blink {
                    0%, 100% { opacity: 1; }
                    50%       { opacity: 0.3; }
                }
                @keyframes pulseGlow {
                    0%, 100% { box-shadow: 0 0 0 0 currentColor; }
                    50%       { box-shadow: 0 0 0 4px transparent; }
                }
                .card-enter { animation: slideUp 0.4s cubic-bezier(0.16,1,0.3,1) backwards; }

                /* تایمر کامپکت */
                .timer-unit {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 2px;
                    min-width: 40px;
                }
                .timer-num {
                    font-size: 22px;
                    font-weight: 800;
                    line-height: 1;
                    font-variant-numeric: tabular-nums;
                    letter-spacing: 1px;
                }
                .timer-label {
                    font-size: 10px;
                    font-weight: 600;
                    opacity: 0.6;
                    letter-spacing: 0.5px;
                }
                .timer-sep {
                    font-size: 18px;
                    font-weight: 700;
                    opacity: 0.4;
                    margin: 0 -2px;
                    padding-bottom: 8px;
                }
            </style>
        @endpush

        {{-- سربرگ صفحه --}}
        <div class="flex items-center gap-3 mb-8 pt-2">
            <div class="flex items-center gap-1">
                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                <div class="w-2 h-2 bg-foreground rounded-full"></div>
            </div>
            <h1 class="font-black text-foreground text-xl">طبقه‌بندی دروس</h1>
        </div>

        {{-- ══════ طبقه‌بندی آزمایشی ══════ --}}
        @if($isTrialUser && $trialProject)
            <section class="mb-10">
                {{-- عنوان بخش --}}
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-1 h-6 rounded-full bg-purple-500 flex-shrink-0"></span>
                    <h2 class="font-black text-foreground text-base flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse inline-block"></span>
                        طبقه‌بندی آزمایشی
                    </h2>
                </div>

                <div class="card-enter rounded-2xl border border-purple-500/30 bg-card overflow-hidden"
                     style="animation-delay: .05s; box-shadow: 0 0 0 1px rgba(168,85,247,0.1), 0 8px 32px rgba(168,85,247,0.08);">
                    {{-- نوار رنگی بالا --}}
                    <div class="h-1 w-full bg-gradient-to-l from-fuchsia-500 to-purple-500"></div>

                    <div class="p-5 sm:p-6">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <h3 class="font-bold text-foreground text-lg leading-snug">{{ $trialProject->name }}</h3>
                            @if($trialSubmitted)
                                <span class="flex-shrink-0 inline-flex items-center gap-1.5 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-3 py-1 text-xs font-bold text-emerald-500">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    ارسال شده
                                </span>
                            @else
                                <span class="flex-shrink-0 inline-flex items-center gap-1.5 rounded-full border border-purple-500/30 bg-purple-500/10 px-3 py-1 text-xs font-bold text-purple-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-400 animate-pulse"></span>
                                    آزمایشی
                                </span>
                            @endif
                        </div>

                        <p class="text-sm text-muted-foreground leading-relaxed mb-5">
                            طبقه‌بندی ویژه دوره آزمایشی — بدون محدودیت زمانی. پس از ثبت به‌صورت خودکار تأیید می‌شود.
                        </p>

                        <button type="button" wire:click="goTrial"
                                class="w-full rounded-xl px-5 py-3 text-sm font-bold transition-all duration-200 active:scale-[0.98]
                                       bg-purple-500 hover:bg-purple-600 text-white">
                            {{ $trialSubmitted ? 'مشاهده و ویرایش' : 'شروع طبقه‌بندی آزمایشی' }}
                        </button>
                    </div>
                </div>
            </section>
        @endif

        {{-- ══════ پروژه‌های فعال ══════ --}}
        @if($activeProjects->count() > 0)
            <section class="mb-10">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-1 h-6 rounded-full bg-emerald-500 flex-shrink-0"></span>
                    <h2 class="font-black text-foreground text-base flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse inline-block"></span>
                        پروژه‌های فعال
                        <span class="text-xs font-bold text-emerald-500 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-full">{{ $activeProjects->count() }}</span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($activeProjects as $project)
                        <article
                            x-data="projectTimer('{{ $project->end_at->toIso8601String() }}', 'active')"
                            class="card-enter group relative flex flex-col rounded-2xl border border-border bg-card overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:border-emerald-500/40"
                            style="animation-delay: {{ $loop->index * 0.06 + 0.05 }}s; box-shadow: 0 2px 12px rgba(0,0,0,0.06);"
                        >
                            {{-- نوار رنگی بالا --}}
                            <div class="h-1 w-full bg-gradient-to-l from-green-400 to-emerald-500"></div>

                            <div class="flex flex-col flex-1 p-5">
                                {{-- بج وضعیت + عنوان --}}
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <h3 class="font-bold text-foreground text-base leading-snug flex-1">{{ $project->name }}</h3>
                                    @if(isset($submissions[$project->id]) && $submissions[$project->id])
                                        <span class="flex-shrink-0 inline-flex items-center gap-1 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-2.5 py-1 text-[11px] font-bold text-emerald-500">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            ارسال شده
                                        </span>
                                    @else
                                        <span class="flex-shrink-0 inline-flex items-center gap-1 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-2.5 py-1 text-[11px] font-bold text-emerald-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                            فعال
                                        </span>
                                    @endif
                                </div>

                                {{-- توضیحات --}}
                                @if($project->description)
                                    <p class="text-sm text-muted-foreground leading-relaxed mb-3 line-clamp-2">{{ $project->description }}</p>
                                @endif

                                {{-- تاریخ‌ها --}}
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <div class="inline-flex items-center gap-1.5 rounded-lg bg-secondary border border-border px-2.5 py-1.5 text-[11px] font-semibold text-foreground">
                                        <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ \Morilog\Jalali\Jalalian::fromCarbon($project->start_at)->format('Y/m/d') }}
                                    </div>
                                    <div class="inline-flex items-center gap-1.5 rounded-lg bg-secondary border border-border px-2.5 py-1.5 text-[11px] font-semibold text-foreground">
                                        <svg class="w-3.5 h-3.5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ \Morilog\Jalali\Jalalian::fromCarbon($project->end_at)->format('Y/m/d') }}
                                    </div>
                                </div>

                                {{-- تایمر کامپکت --}}
                                <div class="rounded-xl border border-emerald-500/25 bg-emerald-500/5 px-4 py-3 mb-4">
                                    <p class="text-[10px] font-bold text-emerald-500 mb-2 text-center tracking-wide">⏳ زمان باقی‌مانده تا پایان</p>
                                    <div class="flex items-center justify-center gap-0" dir="rtl">
                                        <div class="timer-unit">
                                            <span class="timer-num text-emerald-400" x-text="days.toString().padStart(2,'0')">00</span>
                                            <span class="timer-label">روز</span>
                                        </div>
                                        <span class="timer-sep text-emerald-500">:</span>
                                        <div class="timer-unit">
                                            <span class="timer-num text-emerald-400" x-text="hours.toString().padStart(2,'0')">00</span>
                                            <span class="timer-label">ساعت</span>
                                        </div>
                                        <span class="timer-sep text-emerald-500">:</span>
                                        <div class="timer-unit">
                                            <span class="timer-num text-emerald-400" x-text="minutes.toString().padStart(2,'0')">00</span>
                                            <span class="timer-label">دقیقه</span>
                                        </div>
                                        <span class="timer-sep text-emerald-500">:</span>
                                        <div class="timer-unit">
                                            <span class="timer-num text-emerald-400" x-text="seconds.toString().padStart(2,'0')" style="animation: blink 1s ease-in-out infinite;">00</span>
                                            <span class="timer-label">ثانیه</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-1"></div>

                                {{-- دکمه اقدام --}}
                                <button type="button" wire:click="selectProject({{ $project->id }})"
                                        class="w-full rounded-xl px-5 py-3 text-sm font-bold transition-all duration-200 active:scale-[0.98] flex items-center justify-center gap-2
                                        @if(isset($submissions[$project->id]) && $submissions[$project->id])
                                            border-2 border-emerald-500/40 bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500/15
                                        @else
                                            bg-emerald-500 hover:bg-emerald-600 text-white
                                        @endif">
                                    @if(isset($submissions[$project->id]) && $submissions[$project->id])
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        مشاهده و ویرایش
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0-5 5m5-5H6"/></svg>
                                        شروع طبقه‌بندی
                                    @endif
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ══════ در انتظار شروع ══════ --}}
        @if($upcomingProjects->count() > 0)
            <section class="mb-10">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-1 h-6 rounded-full bg-amber-500 flex-shrink-0"></span>
                    <h2 class="font-black text-foreground text-base flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span>
                        در انتظار شروع
                        <span class="text-xs font-bold text-amber-500 bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 rounded-full">{{ $upcomingProjects->count() }}</span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($upcomingProjects as $project)
                        <article
                            x-data="projectTimer('{{ $project->start_at->toIso8601String() }}', 'upcoming')"
                            class="card-enter group relative flex flex-col rounded-2xl border border-border bg-card overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:border-amber-500/40"
                            style="animation-delay: {{ $loop->index * 0.06 + 0.05 }}s; box-shadow: 0 2px 12px rgba(0,0,0,0.06);"
                        >
                            <div class="h-1 w-full bg-gradient-to-l from-yellow-400 to-amber-500"></div>

                            <div class="flex flex-col flex-1 p-5">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <h3 class="font-bold text-foreground text-base leading-snug flex-1">{{ $project->name }}</h3>
                                    <span class="flex-shrink-0 inline-flex items-center gap-1 rounded-full border border-amber-500/30 bg-amber-500/10 px-2.5 py-1 text-[11px] font-bold text-amber-500">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        در انتظار
                                    </span>
                                </div>

                                @if($project->description)
                                    <p class="text-sm text-muted-foreground leading-relaxed mb-3 line-clamp-2">{{ $project->description }}</p>
                                @endif

                                <div class="flex flex-wrap gap-2 mb-4">
                                    <div class="inline-flex items-center gap-1.5 rounded-lg bg-amber-500/10 border border-amber-500/20 px-2.5 py-1.5 text-[11px] font-semibold text-amber-600 dark:text-amber-400">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        شروع: {{ \Morilog\Jalali\Jalalian::fromCarbon($project->start_at)->format('Y/m/d - H:i') }}
                                    </div>
                                </div>

                                {{-- تایمر شمارش معکوس تا شروع --}}
                                <div class="rounded-xl border border-amber-500/25 bg-amber-500/5 px-4 py-3 mb-4">
                                    <p class="text-[10px] font-bold text-amber-500 mb-2 text-center tracking-wide">⏳ زمان باقی‌مانده تا شروع</p>
                                    <div class="flex items-center justify-center gap-0" dir="rtl">
                                        <div class="timer-unit">
                                            <span class="timer-num text-amber-400" x-text="days.toString().padStart(2,'0')">00</span>
                                            <span class="timer-label">روز</span>
                                        </div>
                                        <span class="timer-sep text-amber-500">:</span>
                                        <div class="timer-unit">
                                            <span class="timer-num text-amber-400" x-text="hours.toString().padStart(2,'0')">00</span>
                                            <span class="timer-label">ساعت</span>
                                        </div>
                                        <span class="timer-sep text-amber-500">:</span>
                                        <div class="timer-unit">
                                            <span class="timer-num text-amber-400" x-text="minutes.toString().padStart(2,'0')">00</span>
                                            <span class="timer-label">دقیقه</span>
                                        </div>
                                        <span class="timer-sep text-amber-500">:</span>
                                        <div class="timer-unit">
                                            <span class="timer-num text-amber-400" x-text="seconds.toString().padStart(2,'0')" style="animation: blink 1s ease-in-out infinite;">00</span>
                                            <span class="timer-label">ثانیه</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-1"></div>

                                <button type="button" disabled
                                        class="w-full rounded-xl px-5 py-3 text-sm font-bold flex items-center justify-center gap-2
                                               bg-secondary border border-border text-muted-foreground cursor-not-allowed opacity-60">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    هنوز شروع نشده
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ══════ تمام شده / غیرفعال ══════ --}}
        @if($endedProjects->count() > 0)
            <section class="mb-10">
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-1 h-6 rounded-full bg-muted flex-shrink-0"></span>
                    <h2 class="font-black text-foreground text-base flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-muted-foreground inline-block"></span>
                        تمام شده / غیرفعال
                        <span class="text-xs font-bold text-muted-foreground bg-secondary border border-border px-2 py-0.5 rounded-full">{{ $endedProjects->count() }}</span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($endedProjects as $project)
                        <article class="card-enter group relative flex flex-col rounded-2xl border border-border bg-card overflow-hidden opacity-60 hover:opacity-75 transition-opacity"
                                 style="animation-delay: {{ $loop->index * 0.06 + 0.05 }}s;">
                            <div class="h-1 w-full bg-border"></div>

                            <div class="flex flex-col flex-1 p-5">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <h3 class="font-bold text-foreground text-base leading-snug flex-1">{{ $project->name }}</h3>
                                    <span class="flex-shrink-0 inline-flex items-center gap-1 rounded-full border border-border bg-secondary px-2.5 py-1 text-[11px] font-bold text-muted-foreground">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        تمام شده
                                    </span>
                                </div>

                                @if($project->description)
                                    <p class="text-sm text-muted-foreground leading-relaxed mb-3 line-clamp-2">{{ $project->description }}</p>
                                @endif

                                <div class="flex flex-wrap gap-2 mb-4">
                                    <div class="inline-flex items-center gap-1.5 rounded-lg bg-secondary border border-border px-2.5 py-1.5 text-[11px] font-semibold text-muted-foreground">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        پایان: {{ \Morilog\Jalali\Jalalian::fromCarbon($project->end_at)->format('Y/m/d') }}
                                    </div>
                                    @if(isset($submissions[$project->id]) && $submissions[$project->id])
                                        <div class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1.5 text-[11px] font-semibold text-emerald-500">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            ارسال شده
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1"></div>

                                <button type="button" disabled
                                        class="w-full rounded-xl px-5 py-3 text-sm font-bold flex items-center justify-center gap-2
                                               bg-secondary border border-border text-muted-foreground cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    این پروژه تمام شده
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ══════ حالت خالی ══════ --}}
        @if($activeProjects->count() === 0 && $upcomingProjects->count() === 0 && $endedProjects->count() === 0 && !($isTrialUser && $trialProject))
            <div class="flex flex-col items-center justify-center py-20 space-y-5">
                <img src="/client/assets/images/theme/empty.svg" class="w-48 opacity-30" alt="خالی"/>
                <div class="text-center space-y-1.5">
                    <h2 class="font-bold text-lg text-foreground">پروژه‌ای در دسترس نیست</h2>
                    <p class="text-sm text-muted-foreground">در حال حاضر پروژه طبقه‌بندی فعالی وجود ندارد.</p>
                </div>
            </div>
        @endif

        {{-- لودینگ --}}
        <div wire:loading.flex class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="flex items-center gap-3 rounded-2xl border border-border bg-card px-6 py-4 shadow-2xl">
                <div class="h-6 w-6 animate-spin rounded-full border-3 border-primary/30 border-t-primary"></div>
                <span class="text-sm font-semibold text-foreground">در حال بارگذاری...</span>
            </div>
        </div>

    </div>

    @script
    <script>
        Alpine.data('projectTimer', (targetDate, type) => ({
            days: 0, hours: 0, minutes: 0, seconds: 0,
            interval: null,
            init() {
                this.updateTimer();
                this.interval = setInterval(() => this.updateTimer(), 1000);
            },
            updateTimer() {
                const diff = new Date(targetDate).getTime() - Date.now();
                if (diff <= 0) {
                    this.days = this.hours = this.minutes = this.seconds = 0;
                    clearInterval(this.interval);
                    if (type === 'upcoming' || type === 'active') setTimeout(() => window.location.reload(), 1200);
                    return;
                }
                this.days    = Math.floor(diff / 86400000);
                this.hours   = Math.floor((diff % 86400000) / 3600000);
                this.minutes = Math.floor((diff % 3600000) / 60000);
                this.seconds = Math.floor((diff % 60000) / 1000);
            },
            destroy() { clearInterval(this.interval); }
        }));
    </script>
    @endscript
</div>
