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
        @if($activeProjects->count() === 0 && $upcomingProjects->count() === 0 && $endedProjects->count() === 0)

            <div class="flex flex-col items-center justify-center py-12 space-y-4">
                <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35" alt="empty"/>
                <div class="text-center space-y-2">
                    <h2 class="font-bold text-xl text-foreground">پروژه‌ای در دسترس نیست</h2>
                    <p class="text-muted text-sm">در حال حاضر پروژه طبقه‌بندی فعالی وجود ندارد.</p>
                </div>
            </div>
        @endif

        {{-- Grade Selection Modal --}}
        @if($showGradeModal && $selectedProject)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" x-data x-cloak>
                {{-- Backdrop --}}
                <div class="absolute inset-0 bg-black/70 backdrop-blur-lg [animation:fadeIn_0.3s_ease-out]"
                     wire:click="closeModal"></div>

                {{-- Modal --}}
                <div class="relative z-10 w-full max-w-md sm:max-w-lg overflow-hidden rounded-3xl
                            border-2 border-border/70 bg-card shadow-2xl backdrop-blur-xl
                            [animation:slideUp_0.4s_cubic-bezier(0.4,0,0.2,1)]">

                    {{-- Header --}}
                    <div class="bg-gradient-to-r from-blue-600 via-sky-500 to-purple-600 px-6 py-5">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex flex-col">
                                <h3 class="text-lg sm:text-xl font-black text-white">انتخاب پایه تحصیلی</h3>
                                <p class="mt-1 line-clamp-1 text-sm text-white/90">{{ $selectedProject->name }}</p>
                            </div>
                            <button type="button" wire:click="closeModal"
                                    class="inline-flex items-center justify-center rounded-xl p-2
                                           text-white/80 hover:bg-white/15 hover:text-white
                                           transition-all duration-200 focus-visible:outline-none
                                           focus-visible:ring-2 focus-visible:ring-white/70">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="max-h-[65vh] overflow-y-auto p-6">
                        @if(count($availableGrades) > 0)
                            <p class="mb-5 text-sm text-muted-foreground leading-relaxed">
                                پایه‌ای که می‌خواهید مباحث آن را طبقه‌بندی کنید انتخاب کنید:
                            </p>

                            <div class="grid gap-3.5">
                                @foreach($availableGrades as $grade)
                                    <button type="button"
                                            wire:click="startClassification({{ $grade['grade'] }})"
                                            class="group flex items-center justify-between rounded-2xl border-2 border-border/70
                                                   bg-card p-4 transition-all duration-300
                                                   hover:border-blue-500/60 hover:bg-blue-500/[0.06]
                                                   hover:-translate-x-1 hover:shadow-[0_8px_20px_rgba(59,130,246,0.15)]">

                                        <div class="flex items-center gap-4">
                                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl
                                                        bg-gradient-to-br from-blue-500/25 to-purple-500/30
                                                        border border-blue-500/20 transition-transform duration-300 group-hover:scale-110">
                                                <span class="text-xl font-black text-blue-500">{{ $grade['grade'] }}</span>
                                            </div>

                                            <div class="space-y-1.5 text-right">
                                                <div class="text-base font-bold text-foreground">پایه {{ $grade['name'] }}</div>
                                                <div class="flex flex-wrap items-center gap-1.5">
                                                    <span class="rounded-xl border px-2.5 py-1 text-xs font-semibold
                                                        {{ $grade['type'] === 'progress'
                                                            ? 'border-green-500/30 bg-green-500/15 text-green-500'
                                                            : 'border-amber-500/30 bg-amber-500/15 text-amber-500' }}">
                                                        {{ $grade['type_name'] }}
                                                    </span>
                                                    @if($grade['has_general'])
                                                        <span class="rounded-xl border border-blue-500/30 bg-blue-500/15 px-2.5 py-1 text-xs font-semibold text-blue-500">
                                                            + عمومی
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <svg class="h-6 w-6 text-muted-foreground transition-all duration-300 group-hover:text-blue-500 group-hover:translate-x-1"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                @endforeach
                            </div>

                        @else
                            <div class="py-12 text-center">
                                <div class="mb-5 inline-flex h-20 w-20 items-center justify-center rounded-3xl bg-amber-500/15 border-2 border-amber-500/20">
                                    <svg class="h-10 w-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <h4 class="mb-2 text-lg font-bold text-foreground">پایه‌ای تعریف نشده</h4>
                                <p class="text-sm text-muted-foreground">پایه‌ای برای شما در این پروژه تعریف نشده است.</p>
                                <p class="mt-1 text-xs text-muted-foreground">لطفاً با پشتیبانی تماس بگیرید.</p>
                            </div>
                        @endif
                    </div>
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
