<div>
    <div class="container mx-auto px-4 max-w-7xl pb-10" dir="rtl">

        {{-- Page Header --}}
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-1">
                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                <div class="w-2 h-2 bg-foreground rounded-full"></div>
            </div>
            <div class="font-black text-foreground">  طبقه‌بندی دروس</div>
        </div>

        {{-- Trial Classification Card --}}
        @if($isTrialUser && $trialProject)
            <div class="mb-10 mt-6">
                <h2 class="text-xl font-black text-foreground mb-5 flex items-center gap-2.5 relative pr-5
                           before:content-[''] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2
                           before:w-1 before:h-6 before:bg-gradient-to-b before:from-purple-500 before:to-transparent before:rounded-sm">
                    <span class="h-2.5 w-2.5 rounded-full bg-purple-500 animate-pulse"></span>
                    طبقه‌بندی آزمایشی
                </h2>
                <div class="rounded-2xl border-2 border-purple-500/40 bg-card/95 p-5 sm:p-6 shadow-lg">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <h3 class="text-lg font-bold text-foreground">{{ $trialProject->name }}</h3>
                        @if($trialSubmitted)
                            <span class="inline-flex items-center gap-1.5 rounded-xl border-2 border-green-500/30 bg-green-500/15 px-3 py-1.5 text-xs font-bold text-green-500">
                                ارسال شده
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-xl border-2 border-purple-500/30 bg-purple-500/15 px-3 py-1.5 text-xs font-bold text-purple-500">
                                فعال (آزمایشی)
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-muted-foreground leading-relaxed mb-4">
                        طبقه‌بندی ویژه‌ی دوره‌ی ۱ هفته آزمایشی — بدون محدودیت زمانی. پس از ثبت، به‌صورت خودکار تایید می‌شود.
                    </p>
                    <button type="button" wire:click="goTrial"
                            class="w-full rounded-xl px-5 py-3 text-sm font-bold transition-all duration-300 active:scale-[0.97]
                                   bg-gradient-to-br from-purple-500 to-fuchsia-600 text-white border-2 border-transparent
                                   hover:from-purple-600 hover:to-fuchsia-700
                                   shadow-[0_8px_20px_rgba(168,85,247,0.3)] hover:shadow-[0_12px_28px_rgba(168,85,247,0.4)]">
                        {{ $trialSubmitted ? 'مشاهده و ویرایش' : 'شروع طبقه‌بندی آزمایشی' }}
                    </button>
                </div>
            </div>
        @endif

        {{-- Active Projects --}}
        @if($activeProjects->count() > 0)
            <div class="mb-10">
                <h2 class="text-xl font-black text-foreground mb-5 flex items-center gap-2.5 relative pr-5
                           before:content-[''] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2
                           before:w-1 before:h-6 before:bg-gradient-to-b before:from-green-500 before:to-transparent before:rounded-sm">
                    <span class="h-2.5 w-2.5 rounded-full bg-green-500 animate-pulse"></span>
                    پروژه‌های فعال
                </h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 bg-background bg-secondary">
                    @foreach($activeProjects as $project)
                        <article
                            x-data="projectTimer('{{ $project->end_at->toIso8601String() }}', 'active')"
                            class="group relative flex flex-col h-full rounded-2xl border-2 border-border/70
                                   bg-card/95 shadow-lg backdrop-blur-sm overflow-hidden
                                   transition-all duration-300
                                   hover:-translate-y-2 hover:scale-[1.02]
                                   hover:shadow-[0_24px_48px_rgba(34,197,94,0.2),0_12px_24px_rgba(34,197,94,0.1)]
                                   hover:border-green-500/50
                                   [animation:slideInUp_0.6s_cubic-bezier(0.4,0,0.2,1)_backwards]"
                            style="animation-delay: {{ $loop->index * 0.05 + 0.05 }}s">

                            {{-- Hover Overlay --}}
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none
                                        bg-gradient-to-br from-green-500/8 to-emerald-500/8 z-0"></div>

                            {{-- Status Badge --}}
                            <div class="absolute top-4 left-4 z-10">
                                @if(isset($submissions[$project->id]) && $submissions[$project->id])
                                    <span class="inline-flex items-center gap-1.5 rounded-xl border-2 border-green-500/30
                                                 bg-green-500/15 px-3 py-1.5 text-xs font-bold text-green-500
                                                 backdrop-blur-sm transition-transform duration-300 group-hover:scale-105">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        ارسال شده
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-xl border-2 border-green-500/30
                                                 bg-green-500/15 px-3 py-1.5 text-xs font-bold text-green-500
                                                 backdrop-blur-sm transition-transform duration-300 group-hover:scale-105">
                                        <span class="h-2 w-2 rounded-full bg-green-400 animate-pulse"></span>
                                        فعال
                                    </span>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="relative flex h-full flex-col p-5 sm:p-6 z-10">
                                {{-- Title --}}
                                <div class="mb-3">
                                    <h3 class="text-lg md:text-xl font-bold text-foreground line-clamp-2 leading-tight mb-2">
                                        {{ $project->name }}
                                    </h3>
                                    @if($project->description)
                                        <div class="group/desc relative">
                                            <p class="text-sm leading-relaxed text-muted-foreground line-clamp-3 group-hover/desc:line-clamp-none transition-all duration-300">
                                                {{ $project->description }}
                                            </p>
                                            @if(strlen($project->description) > 100)
                                                <button type="button"
                                                        class="text-xs text-primary hover:text-primary/80 font-semibold mt-1 flex items-center gap-1 group-hover/desc:hidden"
                                                        onclick="this.parentElement.querySelector('p').classList.toggle('line-clamp-3')">
                                                    <span>نمایش بیشتر</span>
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                {{-- Date Info --}}
                                <div class="mb-4 flex flex-wrap gap-2 text-xs">
                                    <div class="flex items-center gap-1.5 rounded-xl bg-emerald-500/10 px-3 py-1.5 border border-emerald-500/20
                                                transition-all duration-300 group-hover:-translate-x-0.5">
                                        <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="font-semibold text-foreground">{{ \Morilog\Jalali\Jalalian::fromCarbon($project->start_at)->format('Y/m/d') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 rounded-xl bg-red-500/10 px-3 py-1.5 border border-red-500/20
                                                transition-all duration-300 group-hover:-translate-x-0.5">
                                        <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="font-semibold text-foreground">{{ \Morilog\Jalali\Jalalian::fromCarbon($project->end_at)->format('Y/m/d') }}</span>
                                    </div>
                                </div>

                                {{-- Timer (Active = Green) --}}
                                <div class="mb-4 rounded-2xl border-2 border-green-500/30 overflow-hidden">
                                    <div class="bg-green-500/10 px-3 py-2 text-center border-b border-green-500/20">
                                        <span class="text-[11px] font-bold uppercase tracking-wide text-green-500">⏰ زمان باقی‌مانده تا پایان</span>
                                    </div>
                                    <div class="bg-secondary px-5 py-4 flex items-center justify-center gap-2 direction-rtl" dir="rtl">
                                        <div class="flex flex-col items-center min-w-[50px] sm:min-w-[60px] gap-1.5">
                                            <span class="font-mono text-[28px] sm:text-[36px] font-bold tracking-[4px] leading-none text-green-500
                                                         [text-shadow:0_0_15px_rgba(34,197,94,0.6),0_0_30px_rgba(34,197,94,0.3)]"
                                                  x-text="seconds.toString().padStart(2, '0')">00</span>
                                            <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wide text-muted-foreground">ثانیه</span>
                                        </div>
                                        <span class="font-mono text-[28px] sm:text-[36px] text-green-500 opacity-70 [animation:blink_1.5s_ease-in-out_infinite] -mx-1">:</span>
                                        <div class="flex flex-col items-center min-w-[50px] sm:min-w-[60px] gap-1.5">
                                            <span class="font-mono text-[28px] sm:text-[36px] font-bold tracking-[4px] leading-none text-green-500
                                                         [text-shadow:0_0_15px_rgba(34,197,94,0.6),0_0_30px_rgba(34,197,94,0.3)]"
                                                  x-text="minutes.toString().padStart(2, '0')">00</span>
                                            <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wide text-muted-foreground">دقیقه</span>
                                        </div>
                                        <span class="font-mono text-[28px] sm:text-[36px] text-green-500 opacity-70 [animation:blink_1.5s_ease-in-out_infinite] -mx-1">:</span>
                                        <div class="flex flex-col items-center min-w-[50px] sm:min-w-[60px] gap-1.5">
                                            <span class="font-mono text-[28px] sm:text-[36px] font-bold tracking-[4px] leading-none text-green-500
                                                         [text-shadow:0_0_15px_rgba(34,197,94,0.6),0_0_30px_rgba(34,197,94,0.3)]"
                                                  x-text="hours.toString().padStart(2, '0')">00</span>
                                            <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wide text-muted-foreground">ساعت</span>
                                        </div>
                                        <span class="font-mono text-[28px] sm:text-[36px] text-green-500 opacity-70 [animation:blink_1.5s_ease-in-out_infinite] -mx-1">:</span>
                                        <span class="font-mono text-[28px] sm:text-[36px] text-green-500 opacity-70 [animation:blink_1.5s_ease-in-out_infinite] -mx-1">:</span>
                                        <div class="flex flex-col items-center min-w-[50px] sm:min-w-[60px] gap-1.5">
                                            <span class="font-mono text-[28px] sm:text-[36px] font-bold tracking-[4px] leading-none text-green-500
                                                         [text-shadow:0_0_15px_rgba(34,197,94,0.6),0_0_30px_rgba(34,197,94,0.3)]"
                                                  x-text="days.toString().padStart(2, '0')">00</span>
                                            <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wide text-muted-foreground">روز</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-1"></div>

                                {{-- Action Button --}}
                                <button
                                    type="button"
                                    wire:click="selectProject({{ $project->id }})"
                                    class="relative overflow-hidden mt-4 w-full rounded-xl px-5 py-3 text-sm font-bold
                                           transition-all duration-300 active:scale-[0.97]
                                           focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500/70 focus-visible:ring-offset-2 focus-visible:ring-offset-background
                                           before:content-[''] before:absolute before:top-1/2 before:left-1/2 before:w-0 before:h-0
                                           before:bg-white/20 before:rounded-full before:transition-all before:duration-500
                                           before:-translate-x-1/2 before:-translate-y-1/2 hover:before:w-[400px] hover:before:h-[400px]
                                           @if(isset($submissions[$project->id]) && $submissions[$project->id])
                                               bg-emerald-500/15 text-emerald-500 border-2 border-emerald-500/30 hover:bg-emerald-500/20 hover:border-emerald-500/50
                                           @else
                                               bg-gradient-to-br from-green-500 to-emerald-600 text-white border-2 border-transparent
                                               shadow-[0_8px_20px_rgba(34,197,94,0.3)] hover:shadow-[0_12px_28px_rgba(34,197,94,0.4)]
                                               hover:from-green-600 hover:to-emerald-700
                                           @endif">
                                    <span class="relative z-10 flex items-center justify-center gap-2">
                                        @if(isset($submissions[$project->id]) && $submissions[$project->id])
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>مشاهده و ویرایش</span>
                                        @else
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0-5 5m5-5H6"/>
                                            </svg>
                                            <span>شروع طبقه‌بندی</span>
                                        @endif
                                    </span>
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Upcoming Projects --}}
        @if($upcomingProjects->count() > 0)
            <div class="mb-10">
                <h2 class="text-xl font-black text-foreground mb-5 flex items-center gap-2.5 relative pr-5
                           before:content-[''] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2
                           before:w-1 before:h-6 before:bg-gradient-to-b before:from-amber-500 before:to-transparent before:rounded-sm">
                    <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                    در انتظار شروع
                </h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 bg-background bg-secondary">
                    @foreach($upcomingProjects as $project)
                        <article
                            x-data="projectTimer('{{ $project->start_at->toIso8601String() }}', 'upcoming')"
                            class="group relative flex flex-col h-full rounded-2xl border-2 border-border/70
                                   bg-card/95 shadow-lg backdrop-blur-sm overflow-hidden
                                   transition-all duration-300
                                   hover:-translate-y-1.5
                                   hover:shadow-[0_20px_40px_rgba(251,191,36,0.15),0_8px_16px_rgba(251,191,36,0.1)]
                                   hover:border-amber-500/50
                                   [animation:slideInUp_0.6s_cubic-bezier(0.4,0,0.2,1)_backwards]"
                            style="animation-delay: {{ $loop->index * 0.05 + 0.05 }}s">

                            {{-- Hover Overlay --}}
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none
                                        bg-gradient-to-br from-amber-500/8 to-yellow-500/8 z-0"></div>

                            {{-- Status Badge --}}
                            <div class="absolute top-4 left-4 z-10">
                                <span class="inline-flex items-center gap-1.5 rounded-xl border-2 border-amber-500/30
                                             bg-amber-500/15 px-3 py-1.5 text-xs font-bold text-amber-500
                                             backdrop-blur-sm transition-transform duration-300 group-hover:scale-105">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    در انتظار
                                </span>
                            </div>

                            {{-- Content --}}
                            <div class="relative flex h-full flex-col p-5 sm:p-6 z-10">
                                <div class="mb-3">
                                    <h3 class="text-lg md:text-xl font-bold text-foreground line-clamp-2 leading-tight mb-2">
                                        {{ $project->name }}
                                    </h3>
                                    @if($project->description)
                                        <div class="group/desc relative">
                                            <p class="text-sm leading-relaxed text-muted-foreground line-clamp-3 group-hover/desc:line-clamp-none transition-all duration-300">
                                                {{ $project->description }}
                                            </p>
                                            @if(strlen($project->description) > 100)
                                                <button type="button"
                                                        class="text-xs text-primary hover:text-primary/80 font-semibold mt-1 flex items-center gap-1 group-hover/desc:hidden"
                                                        onclick="this.parentElement.querySelector('p').classList.toggle('line-clamp-3')">
                                                    <span>نمایش بیشتر</span>
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-4 flex flex-wrap gap-2 text-xs">
                                    <div class="flex items-center gap-1.5 rounded-xl bg-amber-500/10 px-3 py-1.5 border border-amber-500/20
                                                transition-all duration-300 group-hover:-translate-x-0.5">
                                        <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="font-semibold text-foreground">{{ \Morilog\Jalali\Jalalian::fromCarbon($project->start_at)->format('Y/m/d H:i') }}</span>
                                    </div>
                                </div>

                                {{-- Timer (Upcoming = Amber) --}}
                                <div class="mb-4 rounded-2xl border-2 border-amber-500/30 overflow-hidden">
                                    <div class="bg-amber-500/10 px-3 py-2 text-center border-b border-amber-500/20">
                                        <span class="text-[11px] font-bold uppercase tracking-wide text-amber-500">⏰ زمان باقی‌مانده تا شروع</span>
                                    </div>
                                    <div class="bg-secondary px-5 py-4 flex items-center justify-center gap-2" dir="rtl">
                                        <div class="flex flex-col items-center min-w-[50px] sm:min-w-[60px] gap-1.5">
                                            <span class="font-mono text-[28px] sm:text-[36px] font-bold tracking-[4px] leading-none text-amber-400
                                                         [text-shadow:0_0_15px_rgba(251,191,36,0.6),0_0_30px_rgba(251,191,36,0.3)]"
                                                  x-text="days.toString().padStart(2, '0')">00</span>
                                            <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wide text-muted-foreground">روز</span>
                                        </div>
                                        <span class="font-mono text-[28px] sm:text-[36px] text-amber-400 opacity-70 [animation:blink_1.5s_ease-in-out_infinite] -mx-1">:</span>
                                        <div class="flex flex-col items-center min-w-[50px] sm:min-w-[60px] gap-1.5">
                                            <span class="font-mono text-[28px] sm:text-[36px] font-bold tracking-[4px] leading-none text-amber-400
                                                         [text-shadow:0_0_15px_rgba(251,191,36,0.6),0_0_30px_rgba(251,191,36,0.3)]"
                                                  x-text="hours.toString().padStart(2, '0')">00</span>
                                            <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wide text-muted-foreground">ساعت</span>
                                        </div>
                                        <span class="font-mono text-[28px] sm:text-[36px] text-amber-400 opacity-70 [animation:blink_1.5s_ease-in-out_infinite] -mx-1">:</span>
                                        <div class="flex flex-col items-center min-w-[50px] sm:min-w-[60px] gap-1.5">
                                            <span class="font-mono text-[28px] sm:text-[36px] font-bold tracking-[4px] leading-none text-amber-400
                                                         [text-shadow:0_0_15px_rgba(251,191,36,0.6),0_0_30px_rgba(251,191,36,0.3)]"
                                                  x-text="minutes.toString().padStart(2, '0')">00</span>
                                            <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wide text-muted-foreground">دقیقه</span>
                                        </div>
                                        <span class="font-mono text-[28px] sm:text-[36px] text-amber-400 opacity-70 [animation:blink_1.5s_ease-in-out_infinite] -mx-1">:</span>
                                        <div class="flex flex-col items-center min-w-[50px] sm:min-w-[60px] gap-1.5">
                                            <span class="font-mono text-[28px] sm:text-[36px] font-bold tracking-[4px] leading-none text-amber-400
                                                         [text-shadow:0_0_15px_rgba(251,191,36,0.6),0_0_30px_rgba(251,191,36,0.3)]"
                                                  x-text="seconds.toString().padStart(2, '0')">00</span>
                                            <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wide text-muted-foreground">ثانیه</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-1"></div>

                                <button type="button" disabled
                                        class="mt-4 w-full rounded-xl px-5 py-3 text-sm font-bold
                                               bg-gray-500/10 text-gray-400 border-2 border-gray-500/20 cursor-not-allowed">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        <span>هنوز شروع نشده</span>
                                    </span>
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Ended Projects --}}
        @if($endedProjects->count() > 0)
            <div class="mb-10">
                <h2 class="text-xl font-black text-foreground mb-5 flex items-center gap-2.5 relative pr-5
                           before:content-[''] before:absolute before:right-0 before:top-1/2 before:-translate-y-1/2
                           before:w-1 before:h-6 before:bg-gradient-to-b before:from-red-500 before:to-transparent before:rounded-sm">
                    <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                    تمام شده / غیرفعال
                </h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 bg-background bg-secondary">
                    @foreach($endedProjects as $project)
                        <article
                            class="group relative flex flex-col h-full rounded-2xl border-2 border-border/70
                                   bg-card/95 shadow-lg overflow-hidden opacity-65
                                   transition-all duration-300 hover:-translate-y-0.5 hover:opacity-80
                                   [animation:slideInUp_0.6s_cubic-bezier(0.4,0,0.2,1)_backwards]"
                            style="animation-delay: {{ $loop->index * 0.05 + 0.05 }}s">

                            {{-- Status Badge --}}
                            <div class="absolute top-4 left-4 z-10">
                                <span class="inline-flex items-center gap-1.5 rounded-xl border-2 border-red-500/30
                                             bg-red-500/15 px-3 py-1.5 text-xs font-bold text-red-500
                                             backdrop-blur-sm transition-transform duration-300 group-hover:scale-105">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    تمام شده
                                </span>
                            </div>

                            <div class="relative flex h-full flex-col p-5 sm:p-6 z-10">
                                <div class="mb-3">
                                    <h3 class="text-lg md:text-xl font-bold text-foreground line-clamp-2 leading-tight mb-2">
                                        {{ $project->name }}
                                    </h3>
                                    @if($project->description)
                                        <div class="group/desc relative">
                                            <p class="text-sm leading-relaxed text-muted-foreground line-clamp-3 group-hover/desc:line-clamp-none transition-all duration-300">
                                                {{ $project->description }}
                                            </p>
                                            @if(strlen($project->description) > 100)
                                                <button type="button"
                                                        class="text-xs text-primary hover:text-primary/80 font-semibold mt-1 flex items-center gap-1 group-hover/desc:hidden"
                                                        onclick="this.parentElement.querySelector('p').classList.toggle('line-clamp-3')">
                                                    <span>نمایش بیشتر</span>
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-4 flex flex-wrap gap-2 text-xs">
                                    <div class="flex items-center gap-1.5 rounded-xl bg-red-500/10 px-3 py-1.5 border border-red-500/20">
                                        <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="font-semibold text-foreground">{{ \Morilog\Jalali\Jalalian::fromCarbon($project->end_at)->format('Y/m/d') }}</span>
                                    </div>
                                </div>

                                <div class="flex-1"></div>

                                <button type="button" disabled
                                        class="mt-4 w-full rounded-xl px-5 py-3 text-sm font-bold
                                               bg-red-500/10 text-red-400 border-2 border-red-500/20 cursor-not-allowed">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        <span>این پروژه تمام شده</span>
                                    </span>
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Empty State --}}
        @if($activeProjects->count() === 0 && $upcomingProjects->count() === 0 && $endedProjects->count() === 0 && !($isTrialUser && $trialProject))

            <div class="flex flex-col items-center justify-center py-12 space-y-4">
                <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35" alt="empty"/>
                <div class="text-center space-y-2">
                    <h2 class="font-bold text-xl text-foreground">پروژه‌ای در دسترس نیست</h2>
                    <p class="text-muted text-sm">در حال حاضر پروژه طبقه‌بندی فعالی وجود ندارد.</p>
                </div>
            </div>
        @endif


        {{-- Loading Overlay --}}
        <div wire:loading.flex class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/70 backdrop-blur-md">
            <div class="flex items-center gap-3 rounded-2xl border-2 border-border/70 bg-card px-6 py-5 shadow-2xl backdrop-blur-xl">
                <div class="h-8 w-8 animate-spin rounded-full border-4 border-blue-500/80 border-t-transparent"></div>
                <span class="text-base font-semibold text-foreground">در حال بارگذاری...</span>
            </div>
        </div>

    </div>

    @script
    <script>
        Alpine.data('projectTimer', (targetDate, type) => ({
            days: 0,
            hours: 0,
            minutes: 0,
            seconds: 0,
            interval: null,

            init() {
                this.updateTimer();
                this.interval = setInterval(() => this.updateTimer(), 1000);
            },

            updateTimer() {
                const target = new Date(targetDate).getTime();
                const now = new Date().getTime();
                const diff = target - now;

                if (diff <= 0) {
                    this.days = 0;
                    this.hours = 0;
                    this.minutes = 0;
                    this.seconds = 0;
                    if (this.interval) clearInterval(this.interval);
                    if (type === 'upcoming' || type === 'active') {
                        setTimeout(() => window.location.reload(), 1000);
                    }
                    return;
                }

                this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
            },

            destroy() {
                if (this.interval) clearInterval(this.interval);
            }
        }));
    </script>
    @endscript


</div>
