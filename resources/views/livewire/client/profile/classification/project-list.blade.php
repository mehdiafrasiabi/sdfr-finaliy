<div>

    <div class="container mx-auto px-4 max-w-6xl pb-10" dir="rtl">

        @push('link')

            <style>

                @font-face {

                    font-family: 'Digital';

                    src: url('/client/assets/fonts/digital-7.ttf') format('truetype');

                }


                [x-cloak] {
                    display: none !important;
                }


                .card-soft-shadow {

                    box-shadow: 0 18px 45px rgba(15, 23, 42, 0.06),
                    0 8px 20px rgba(15, 23, 42, 0.03);

                }


                .digital-clock {

                    font-family: 'Digital', 'Courier New', monospace;

                    font-size: 24px;

                    letter-spacing: 2px;

                    color: #ff9c00;

                    text-shadow: 0 0 8px rgba(255, 150, 0, 0.7);

                }


                .timer-box {

                    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);

                    border-radius: 12px;

                    padding: 12px 16px;

                    display: flex;

                    align-items: center;

                    justify-content: center;

                    gap: 8px;

                }


                .timer-unit {

                    display: flex;

                    flex-direction: column;

                    align-items: center;

                    min-width: 50px;

                }


                .timer-value {

                    font-family: 'Digital', 'Courier New', monospace;

                    font-size: 28px;

                    color: #00ff88;

                    text-shadow: 0 0 10px rgba(0, 255, 136, 0.5);

                    line-height: 1;

                }


                .timer-label {

                    font-size: 10px;

                    color: rgba(255, 255, 255, 0.6);

                    margin-top: 4px;

                }


                .timer-separator {

                    font-family: 'Digital', 'Courier New', monospace;

                    font-size: 28px;

                    color: #00ff88;

                    opacity: 0.7;

                }


                .upcoming-timer .timer-value {

                    color: #fbbf24;

                    text-shadow: 0 0 10px rgba(251, 191, 36, 0.5);

                }

            </style>

        @endpush




            <div class="mb-10 text-center">

            <h1 class="text-2xl md:text-3xl font-extrabold text-foreground mb-3">

                <span class="bg-gradient-to-r from-blue-500 via-sky-500 to-purple-600 bg-clip-text text-transparent">

                    طبقه‌بندی دروس

                </span>

            </h1>

            <p class="text-sm md:text-base text-muted">

                میزان تسلط خود را در هر مبحث مشخص کنید

            </p>

        </div>


        {{-- Active Projects --}}

        @if($activeProjects->count() > 0)

            <div class="mb-8">

                <h2 class="text-lg font-bold text-foreground mb-4 flex items-center gap-2">

                    <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>

                    پروژه‌های فعال

                </h2>

                <div class="grid gap-5 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">

                    @foreach($activeProjects as $project)

                        <article

                            x-data="projectTimer('{{ $project->end_at->toIso8601String() }}', 'active')"

                            class="group relative flex flex-col h-full rounded-2xl border border-border/70

                                   bg-card/90 dark:bg-card/80 overflow-hidden

                                   shadow-sm hover:shadow-xl hover:border-green-500/60

                                   transition-all duration-300 hover:-translate-y-1 card-soft-shadow"

                        >

                            {{-- Status Badge --}}

                            <div class="absolute top-3 left-3 sm:top-4 sm:left-4 z-10">

                                @if(isset($submissions[$project->id]) && $submissions[$project->id])

                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-green-500/25

                                                 bg-green-500/10 px-3 py-1 text-[11px] font-medium text-green-500 backdrop-blur-sm">

                                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20">

                                            <path fill-rule="evenodd"
                                                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                  clip-rule="evenodd"/>

                                        </svg>

                                        ارسال شده

                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-green-500/25

                                                 bg-green-500/10 px-3 py-1 text-[11px] font-medium text-green-500 backdrop-blur-sm">

                                        <span class="h-2 w-2 animate-pulse rounded-full bg-green-400"></span>

                                        فعال

                                    </span>

                                @endif

                            </div>


                            {{-- Gradient Overlay --}}

                            <div class="pointer-events-none absolute inset-0

                                        bg-gradient-to-br from-green-500/5 via-transparent to-emerald-500/10

                                        opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>


                            {{-- Content --}}

                            <div class="relative flex h-full flex-col p-4 sm:p-5 md:p-6">

                                <h3 class="mb-2 text-base md:text-lg font-semibold text-foreground line-clamp-2">

                                    {{ $project->name }}

                                </h3>


                                @if($project->description)

                                    <p class="mb-4 text-xs md:text-sm leading-relaxed text-muted line-clamp-2">

                                        {{ $project->description }}

                                    </p>

                                @endif



                                {{-- Date Info --}}

                                <div class="mb-4 flex flex-wrap gap-2.5 text-[11px] md:text-xs text-muted">

                                    <div
                                        class="flex items-center gap-1.5 rounded-lg bg-muted/80 dark:bg-muted/40 px-2.5 py-1.5">

                                        <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"

                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>

                                        </svg>

                                        <span class="text-[11px] md:text-xs text-foreground/90">

                                            شروع: {{ \Morilog\Jalali\Jalalian::fromCarbon($project->start_at)->format('Y/m/d') }}

                                        </span>

                                    </div>

                                    <div
                                        class="flex items-center gap-1.5 rounded-lg bg-muted/80 dark:bg-muted/40 px-2.5 py-1.5">

                                        <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"

                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>

                                        </svg>

                                        <span class="text-[11px] md:text-xs text-foreground/90">

                                            پایان: {{ \Morilog\Jalali\Jalalian::fromCarbon($project->end_at)->format('Y/m/d') }}

                                        </span>

                                    </div>

                                </div>


                                {{-- Realtime Timer --}}

                                <div class="mb-4">

                                    <p class="text-xs text-muted mb-2 text-center">زمان باقی‌مانده تا پایان</p>

                                    <div class="timer-box">

                                        <div class="timer-unit">

                                            <span class="timer-value"
                                                  x-text="days.toString().padStart(2, '0')">00</span>

                                            <span class="timer-label">روز</span>

                                        </div>

                                        <span class="timer-separator">:</span>

                                        <div class="timer-unit">

                                            <span class="timer-value"
                                                  x-text="hours.toString().padStart(2, '0')">00</span>

                                            <span class="timer-label">ساعت</span>

                                        </div>

                                        <span class="timer-separator">:</span>

                                        <div class="timer-unit">

                                            <span class="timer-value"
                                                  x-text="minutes.toString().padStart(2, '0')">00</span>

                                            <span class="timer-label">دقیقه</span>

                                        </div>

                                        <span class="timer-separator">:</span>

                                        <div class="timer-unit">

                                            <span class="timer-value"
                                                  x-text="seconds.toString().padStart(2, '0')">00</span>

                                            <span class="timer-label">ثانیه</span>

                                        </div>

                                    </div>

                                </div>


                                {{-- Spacer --}}

                                <div class="flex-1"></div>


                                {{-- Action Button --}}

                                <button

                                    type="button"

                                    wire:click="selectProject({{ $project->id }})"

                                    class="mt-3 w-full rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-300

                                           focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500/70

                                           focus-visible:ring-offset-2 focus-visible:ring-offset-background

                                           @if(isset($submissions[$project->id]) && $submissions[$project->id])

                                               bg-emerald-500/10 text-emerald-500 border border-emerald-500/30 hover:bg-emerald-500/15

                                           @else

                                               bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/25

                                               hover:from-green-600 hover:to-emerald-700

                                           @endif"

                                >

                                    <span class="flex items-center justify-center gap-2">

                                        @if(isset($submissions[$project->id]) && $submissions[$project->id])

                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"

                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"

                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>

                                            </svg>

                                            <span>مشاهده و ویرایش</span>

                                        @else

                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"

                                                      d="M13 7l5 5m0 0-5 5m5-5H6"/>

                                            </svg>

                                            <span>بزن بریم!</span>

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

            <div class="mb-8">

                <h2 class="text-lg font-bold text-foreground mb-4 flex items-center gap-2">

                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>

                    در انتظار شروع

                </h2>

                <div class="grid gap-5 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">

                    @foreach($upcomingProjects as $project)

                        <article

                            x-data="projectTimer('{{ $project->start_at->toIso8601String() }}', 'upcoming')"

                            class="group relative flex flex-col h-full rounded-2xl border border-border/70

                                   bg-card/90 dark:bg-card/80 overflow-hidden

                                   shadow-sm hover:shadow-xl hover:border-amber-500/60

                                   transition-all duration-300 hover:-translate-y-1 card-soft-shadow"

                        >

                            {{-- Status Badge --}}

                            <div class="absolute top-3 left-3 sm:top-4 sm:left-4 z-10">

                                <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-500/25

                                             bg-amber-500/10 px-3 py-1 text-[11px] font-medium text-amber-500 backdrop-blur-sm">

                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"

                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>

                                    </svg>

                                    در انتظار

                                </span>

                            </div>


                            {{-- Gradient Overlay --}}

                            <div class="pointer-events-none absolute inset-0

                                        bg-gradient-to-br from-amber-500/5 via-transparent to-orange-500/10

                                        opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>


                            {{-- Content --}}

                            <div class="relative flex h-full flex-col p-4 sm:p-5 md:p-6">

                                <h3 class="mb-2 text-base md:text-lg font-semibold text-foreground line-clamp-2">

                                    {{ $project->name }}

                                </h3>


                                @if($project->description)

                                    <p class="mb-4 text-xs md:text-sm leading-relaxed text-muted line-clamp-2">

                                        {{ $project->description }}

                                    </p>

                                @endif



                                {{-- Date Info --}}

                                <div class="mb-4 flex flex-wrap gap-2.5 text-[11px] md:text-xs text-muted">

                                    <div
                                        class="flex items-center gap-1.5 rounded-lg bg-muted/80 dark:bg-muted/40 px-2.5 py-1.5">

                                        <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"

                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>

                                        </svg>

                                        <span class="text-[11px] md:text-xs text-foreground/90">

                                            شروع: {{ \Morilog\Jalali\Jalalian::fromCarbon($project->start_at)->format('Y/m/d H:i') }}

                                        </span>

                                    </div>

                                </div>


                                {{-- Countdown to Start --}}

                                <div class="mb-4">

                                    <p class="text-xs text-muted mb-2 text-center">زمان باقی‌مانده تا شروع</p>

                                    <div class="timer-box upcoming-timer">

                                        <div class="timer-unit">

                                            <span class="timer-value"
                                                  x-text="days.toString().padStart(2, '0')">00</span>

                                            <span class="timer-label">روز</span>

                                        </div>

                                        <span class="timer-separator" style="color: #fbbf24;">:</span>

                                        <div class="timer-unit">

                                            <span class="timer-value"
                                                  x-text="hours.toString().padStart(2, '0')">00</span>

                                            <span class="timer-label">ساعت</span>

                                        </div>

                                        <span class="timer-separator" style="color: #fbbf24;">:</span>

                                        <div class="timer-unit">

                                            <span class="timer-value"
                                                  x-text="minutes.toString().padStart(2, '0')">00</span>

                                            <span class="timer-label">دقیقه</span>

                                        </div>

                                        <span class="timer-separator" style="color: #fbbf24;">:</span>

                                        <div class="timer-unit">

                                            <span class="timer-value"
                                                  x-text="seconds.toString().padStart(2, '0')">00</span>

                                            <span class="timer-label">ثانیه</span>

                                        </div>

                                    </div>

                                </div>


                                {{-- Spacer --}}

                                <div class="flex-1"></div>


                                {{-- Disabled Button --}}

                                <button

                                    type="button"

                                    disabled

                                    class="mt-3 w-full rounded-xl px-4 py-2.5 text-sm font-medium

                                           bg-gray-500/20 text-gray-400 border border-gray-500/30

                                           cursor-not-allowed"

                                >

                                    <span class="flex items-center justify-center gap-2">

                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"

                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>

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



        {{-- Ended/Inactive Projects --}}

        @if($endedProjects->count() > 0)

            <div class="mb-8">

                <h2 class="text-lg font-bold text-foreground mb-4 flex items-center gap-2">

                    <span class="h-2 w-2 rounded-full bg-red-500"></span>

                    پروژه‌های غیرفعال / تمام شده

                </h2>

                <div class="grid gap-5 sm:gap-6 md:grid-cols-2 lg:grid-cols-3">

                    @foreach($endedProjects as $project)

                        <article

                            class="group relative flex flex-col h-full rounded-2xl border border-border/70

                                   bg-card/90 dark:bg-card/80 overflow-hidden

                                   shadow-sm card-soft-shadow opacity-60"

                        >

                            {{-- Status Badge --}}

                            <div class="absolute top-3 left-3 sm:top-4 sm:left-4 z-10">

                                <span class="inline-flex items-center gap-1.5 rounded-full border border-red-500/25

                                             bg-red-500/10 px-3 py-1 text-[11px] font-medium text-red-500 backdrop-blur-sm">

                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"

                                              d="M6 18L18 6M6 6l12 12"/>

                                    </svg>

                                    غیرفعال

                                </span>

                            </div>


                            {{-- Content --}}

                            <div class="relative flex h-full flex-col p-4 sm:p-5 md:p-6">

                                <h3 class="mb-2 text-base md:text-lg font-semibold text-foreground line-clamp-2">

                                    {{ $project->name }}

                                </h3>


                                @if($project->description)

                                    <p class="mb-4 text-xs md:text-sm leading-relaxed text-muted line-clamp-2">

                                        {{ $project->description }}

                                    </p>

                                @endif



                                {{-- Date Info --}}

                                <div class="mb-4 flex flex-wrap gap-2.5 text-[11px] md:text-xs text-muted">

                                    <div
                                        class="flex items-center gap-1.5 rounded-lg bg-muted/80 dark:bg-muted/40 px-2.5 py-1.5">

                                        <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"

                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>

                                        </svg>

                                        <span class="text-[11px] md:text-xs text-foreground/90">

                                            پایان: {{ \Morilog\Jalali\Jalalian::fromCarbon($project->end_at)->format('Y/m/d') }}

                                        </span>

                                    </div>

                                </div>


                                {{-- Spacer --}}

                                <div class="flex-1"></div>


                                {{-- Disabled Button --}}

                                <button

                                    type="button"

                                    disabled

                                    class="mt-3 w-full rounded-xl px-4 py-2.5 text-sm font-medium

                                           bg-red-500/10 text-red-400 border border-red-500/30

                                           cursor-not-allowed"

                                >

                                    <span class="flex items-center justify-center gap-2">

                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"

                                                  d="M6 18L18 6M6 6l12 12"/>

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

            <div class="py-16 text-center">

                <div
                    class="mb-6 inline-flex h-20 w-20 items-center justify-center rounded-2xl bg-muted/80 dark:bg-muted/40">

                    <svg class="h-10 w-10 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"

                              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>

                    </svg>

                </div>

                <h3 class="mb-1.5 text-lg font-semibold text-foreground">پروژه‌ای در دسترس نیست</h3>

                <p class="text-sm text-muted">در حال حاضر پروژه طبقه‌بندی فعالی وجود ندارد.</p>

            </div>

        @endif



        {{-- Grade Selection Modal --}}

        @if($showGradeModal && $selectedProject)

            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" x-data x-cloak>

                {{-- Backdrop --}}

                <div class="absolute inset-0 bg-black/60 backdrop-blur-md" wire:click="closeModal"></div>


                {{-- Modal --}}

                <div class="relative z-10 w-full max-w-md sm:max-w-lg overflow-hidden rounded-2xl sm:rounded-3xl

                            border border-border/70 bg-card/95 dark:bg-slate-900/95 shadow-2xl

                            animate-in fade-in-0 zoom-in-95 duration-200">

                    {{-- Header --}}

                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-5 sm:px-6 py-4 text-right">

                        <div class="flex items-center justify-between gap-3">

                            <div class="flex flex-col">

                                <h3 class="text-base sm:text-lg font-bold text-white">انتخاب پایه تحصیلی</h3>

                                <p class="mt-0.5 line-clamp-1 text-xs sm:text-sm text-white/80">{{ $selectedProject->name }}</p>

                            </div>

                            <button type="button" wire:click="closeModal"

                                    class="inline-flex items-center justify-center rounded-full p-1.5 text-white/80

                                           hover:bg-white/10 hover:text-white transition-colors

                                           focus-visible:outline-none focus-visible:ring-2

                                           focus-visible:ring-white/70 focus-visible:ring-offset-2

                                           focus-visible:ring-offset-transparent">

                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"/>

                                </svg>

                            </button>

                        </div>

                    </div>


                    {{-- Content --}}

                    <div class="max-h-[70vh] overflow-y-auto p-5 sm:p-6">

                        @if(count($availableGrades) > 0)

                            <p class="mb-4 text-xs sm:text-sm text-muted">

                                پایه‌ای که می‌خواهید مباحث آن را طبقه‌بندی کنید انتخاب کنید:

                            </p>

                            <div class="grid gap-3">

                                @foreach($availableGrades as $grade)

                                    <button type="button" wire:click="startClassification({{ $grade['grade'] }})"

                                            class="group relative flex items-center justify-between rounded-2xl

                                                   border border-border/70 bg-card/90 dark:bg-slate-900/80

                                                   p-3.5 sm:p-4 text-right transition-all duration-200

                                                   hover:border-blue-500/60 hover:bg-blue-500/[0.04]">

                                        <div class="flex items-center gap-3.5 sm:gap-4">

                                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl

                                                        bg-gradient-to-br from-blue-500/20 to-purple-500/25 sm:h-12 sm:w-12">

                                                <span
                                                    class="text-base sm:text-lg font-bold text-blue-500">{{ $grade['grade'] }}</span>

                                            </div>

                                            <div class="space-y-1">

                                                <div class="text-sm sm:text-base font-medium text-foreground">

                                                    پایه {{ $grade['name'] }}

                                                </div>

                                                <div class="flex flex-wrap items-center gap-1.5">

                                                    <span class="rounded-full border px-2 py-0.5 text-[11px]

                                                        {{ $grade['type'] === 'progress'

                                                            ? 'border-green-500/25 bg-green-500/15 text-green-400'

                                                            : 'border-amber-500/25 bg-amber-500/15 text-amber-400' }}">

                                                        {{ $grade['type_name'] }}

                                                    </span>

                                                    @if($grade['has_general'])

                                                        <span
                                                            class="rounded-full border border-blue-500/25 bg-blue-500/15 px-2 py-0.5 text-[11px] text-blue-400">

                                                            + عمومی

                                                        </span>

                                                    @endif

                                                </div>

                                            </div>

                                        </div>

                                        <svg
                                            class="h-4 w-4 sm:h-5 sm:w-5 text-muted transition-colors group-hover:text-blue-500"

                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 5l7 7-7 7"/>

                                        </svg>

                                    </button>

                                @endforeach

                            </div>

                        @else

                            <div class="py-8 text-center">

                                <div
                                    class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-500/15">

                                    <svg class="h-8 w-8 text-amber-500" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"

                                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>

                                    </svg>

                                </div>

                                <p class="text-sm text-muted">پایه‌ای برای شما در این پروژه تعریف نشده است.</p>

                                <p class="mt-1 text-xs sm:text-sm text-muted">لطفا با پشتیبانی تماس بگیرید.</p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        @endif



        {{-- Loading Overlay --}}

        <div wire:loading.flex
             class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 backdrop-blur-sm">

            <div
                class="flex items-center gap-3 rounded-2xl border border-border/70 bg-card/95 px-5 py-4 sm:px-6 sm:py-5 shadow-xl dark:bg-slate-900/95">

                <div
                    class="h-7 w-7 sm:h-8 sm:w-8 animate-spin rounded-full border-4 border-blue-500/80 border-t-transparent"></div>

                <span class="text-sm sm:text-base font-medium text-foreground">در حال بارگذاری...</span>

            </div>

        </div>

    </div>


    @push('script')

        <script>

            function projectTimer(targetDate, type) {

                return {

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
                            if (this.interval) {
                                clearInterval(this.interval);
                            }
                            // Refresh page when timer ends
                            if (type === 'upcoming' || type === 'active') {
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            }
                            return;
                        }
                        this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                    },
                    destroy() {
                        if (this.interval) {
                            clearInterval(this.interval);
                        }
                    }
                }
            }
        </script>
    @endpush
</div>
