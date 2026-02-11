<div>
    @push('link')
        <style>
            @font-face {
                font-family: 'Digital';
                src: url('/client/assets/fonts/digital-7.ttf') format('truetype');
            }

            [x-cloak] {
                display: none !important;
            }

            /* ===== Card Styles ===== */
            .project-card {
                position: relative;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                backdrop-filter: blur(12px);
                overflow: hidden;
            }

            .project-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                opacity: 0;
                transition: opacity 0.4s ease;
                z-index: 0;
                pointer-events: none;
            }

            /* Active Project Card */
            .project-card.active-card::before {
                background: linear-gradient(135deg,
                rgba(34, 197, 94, 0.08) 0%,
                rgba(16, 185, 129, 0.08) 100%);
            }

            .project-card.active-card:hover::before {
                opacity: 1;
            }

            .project-card.active-card:hover {
                transform: translateY(-8px) scale(1.02);
                box-shadow: 0 24px 48px rgba(34, 197, 94, 0.2),
                0 12px 24px rgba(34, 197, 94, 0.1);
                border-color: rgba(34, 197, 94, 0.5);
            }

            /* Upcoming Project Card */
            .project-card.upcoming-card::before {
                background: linear-gradient(135deg,
                rgba(251, 191, 36, 0.08) 0%,
                rgba(245, 158, 11, 0.08) 100%);
            }

            .project-card.upcoming-card:hover::before {
                opacity: 1;
            }

            .project-card.upcoming-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 20px 40px rgba(251, 191, 36, 0.15),
                0 8px 16px rgba(251, 191, 36, 0.1);
                border-color: rgba(251, 191, 36, 0.5);
            }

            /* Ended Project Card */
            .project-card.ended-card {
                opacity: 0.65;
            }

            .project-card.ended-card:hover {
                transform: translateY(-2px);
                opacity: 0.8;
            }

            /* Card Content Layer */
            .project-card > * {
                position: relative;
                z-index: 1;
            }

            /* ===== Timer Styles ===== */
            .timer-box {
                border-radius: 16px;
                padding: 0;
                display: flex;
                flex-direction: column;
                gap: 0;
                overflow: hidden;
                border: 2px solid;
            }

            /* Timer for Active Projects - Green */
            .active-card .timer-box {
                border-color: rgba(34, 197, 94, 0.3);
            }

            /* Timer for Upcoming Projects - Amber */
            .upcoming-card .timer-box {
                border-color: rgba(251, 191, 36, 0.3);
            }

            .timer-header {
                background: rgba(var(--timer-bg), 0.1);
                padding: 8px 12px;
                text-align: center;
                border-bottom: 1px solid rgba(var(--timer-bg), 0.2);
            }

            .timer-header-text {
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: rgb(var(--timer-color));
            }

            .timer-content {
                background: var(--color-secondary);
                padding: 16px 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                direction: rtl;
            }

            .timer-unit {
                display: flex;
                flex-direction: column;
                align-items: center;
                min-width: 60px;
                gap: 6px;
            }

            .timer-value {
                font-family: 'Digital', 'Courier New', monospace;
                font-size: 36px;
                color: rgb(var(--timer-color));
                text-shadow: 0 0 15px rgba(var(--timer-color), 0.6),
                0 0 30px rgba(var(--timer-color), 0.3);
                line-height: 1;
                font-weight: bold;
                letter-spacing: 4px;
            }

            .timer-label {
                font-size: 10px;
                color: var(--color-muted);
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .timer-separator {
                font-family: 'Digital', 'Courier New', monospace;
                font-size: 36px;
                color: rgb(var(--timer-color));
                opacity: 0.7;
                animation: blink 1.5s ease-in-out infinite;
                margin: 0 -4px;
            }

            @keyframes blink {
                0%, 100% { opacity: 0.7; }
                50% { opacity: 0.3; }
            }

            /* Active Project Timer - Green */
            .active-card .timer-box {
                --timer-bg: 34, 197, 94;
                --timer-color: 34, 197, 94;
            }

            /* Upcoming Project Timer - Amber */
            .upcoming-card .timer-box {
                --timer-bg: 251, 191, 36;
                --timer-color: 251, 191, 36;
            }

            /* ===== Badge Styles ===== */
            .status-badge {
                transition: all 0.3s ease;
                backdrop-filter: blur(8px);
            }

            .project-card:hover .status-badge {
                transform: scale(1.05);
            }

            .pulse-dot {
                animation: pulse-dot 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            @keyframes pulse-dot {
                0%, 100% {
                    opacity: 1;
                    transform: scale(1);
                }
                50% {
                    opacity: 0.7;
                    transform: scale(1.3);
                }
            }

            /* ===== Date Badge Styles ===== */
            .date-badge {
                transition: all 0.3s ease;
            }

            .project-card:hover .date-badge {
                background: rgba(var(--badge-color), 0.15);
                transform: translateX(-2px);
            }

            /* ===== Button Styles ===== */
            .action-button {
                position: relative;
                overflow: hidden;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .action-button::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 0;
                height: 0;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                transform: translate(-50%, -50%);
                transition: width 0.6s ease, height 0.6s ease;
            }

            .action-button:hover::before {
                width: 400px;
                height: 400px;
            }

            .action-button:active {
                transform: scale(0.97);
            }

            /* Active Button Gradient */
            .btn-gradient-active {
                background: linear-gradient(135deg,
                rgb(34, 197, 94) 0%,
                rgb(16, 185, 129) 100%);
                box-shadow: 0 8px 20px rgba(34, 197, 94, 0.3);
            }

            .btn-gradient-active:hover {
                background: linear-gradient(135deg,
                rgb(22, 163, 74) 0%,
                rgb(5, 150, 105) 100%);
                box-shadow: 0 12px 28px rgba(34, 197, 94, 0.4);
            }

            /* ===== Section Headers ===== */
            .section-header {
                position: relative;
                padding-right: 20px;
            }

            .section-header::before {
                content: '';
                position: absolute;
                right: 0;
                top: 50%;
                transform: translateY(-50%);
                width: 4px;
                height: 24px;
                background: linear-gradient(to bottom, var(--header-color), transparent);
                border-radius: 2px;
            }

            /* ===== Animation Delays ===== */
            .project-card {
                animation: slideInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) backwards;
            }

            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(40px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .project-card:nth-child(1) { animation-delay: 0.05s; }
            .project-card:nth-child(2) { animation-delay: 0.10s; }
            .project-card:nth-child(3) { animation-delay: 0.15s; }
            .project-card:nth-child(4) { animation-delay: 0.20s; }
            .project-card:nth-child(5) { animation-delay: 0.25s; }
            .project-card:nth-child(6) { animation-delay: 0.30s; }

            /* ===== Modal Styles ===== */
            .modal-backdrop {
                animation: fadeIn 0.3s ease-out;
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            .modal-content {
                animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(30px) scale(0.95);
                }
                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .grade-option {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .grade-option:hover {
                transform: translateX(-4px);
                box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
            }

            /* ===== Responsive ===== */
            @media (max-width: 640px) {
                .timer-content {
                    padding: 12px 16px;
                    gap: 6px;
                }

                .timer-unit {
                    min-width: 50px;
                }

                .timer-value {
                    font-size: 28px;
                    letter-spacing: 2px;
                }

                .timer-separator {
                    font-size: 28px;
                    margin: 0 -6px;
                }

                .timer-label {
                    font-size: 9px;
                }

                .timer-header-text {
                    font-size: 10px;
                }
            }

            /* ===== Loading Spinner ===== */
            @keyframes spin {
                to { transform: rotate(360deg); }
            }

            .animate-spin {
                animation: spin 1s linear infinite;
            }
        </style>
    @endpush

    <div class="container mx-auto px-4 max-w-7xl pb-10" dir="rtl">

        <!-- Page Header -->
        <div class="mb-10 text-center">
            <h1 class="text-3xl md:text-4xl font-black text-foreground mb-3">
                <span class="bg-gradient-to-r from-blue-500 via-sky-500 to-purple-600 bg-clip-text text-transparent">
                    طبقه‌بندی دروس
                </span>
            </h1>
            <p class="text-sm md:text-base text-muted max-w-2xl mx-auto">
                میزان تسلط خود را در هر مبحث مشخص کنید و نقشه راه یادگیری شخصی خود را بسازید
            </p>
        </div>

        {{-- Active Projects --}}
        @if($activeProjects->count() > 0)
            <div class="mb-10">
                <h2 class="section-header text-xl font-black text-foreground mb-5 flex items-center gap-2.5"
                    style="--header-color: rgb(34, 197, 94)">
                    <span class="pulse-dot h-2.5 w-2.5 rounded-full bg-green-500"></span>
                    پروژه‌های فعال
                </h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($activeProjects as $project)
                        <article
                            x-data="projectTimer('{{ $project->end_at->toIso8601String() }}', 'active')"
                            class="project-card active-card group flex flex-col h-full rounded-2xl
                                   border-2 border-border/70 bg-card/95 dark:bg-card/90
                                   shadow-lg hover:shadow-2xl transition-all duration-400">

                            {{-- Status Badge --}}
                            <div class="absolute top-4 left-4 z-10">
                                @if(isset($submissions[$project->id]) && $submissions[$project->id])
                                    <span class="status-badge inline-flex items-center gap-1.5 rounded-xl
                                                 border-2 border-green-500/30 bg-green-500/15
                                                 px-3 py-1.5 text-xs font-bold text-green-500 backdrop-blur-sm">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        ارسال شده
                                    </span>
                                @else
                                    <span class="status-badge inline-flex items-center gap-1.5 rounded-xl
                                                 border-2 border-green-500/30 bg-green-500/15
                                                 px-3 py-1.5 text-xs font-bold text-green-500 backdrop-blur-sm">
                                        <span class="pulse-dot h-2 w-2 rounded-full bg-green-400"></span>
                                        فعال
                                    </span>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="relative flex h-full flex-col p-5 sm:p-6">
                                {{-- Title --}}
                                <div class="mb-3">
                                    <h3 class="text-lg md:text-xl font-bold text-foreground line-clamp-2 leading-tight mb-2">
                                        {{ $project->name }}
                                    </h3>
                                    @if($project->description)
                                        <div class="group/desc relative">
                                            <p class="text-sm leading-relaxed text-muted line-clamp-3 group-hover/desc:line-clamp-none transition-all duration-300">
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
                                    <div class="date-badge flex items-center gap-1.5 rounded-xl
                                                bg-emerald-500/10 dark:bg-emerald-500/15 px-3 py-1.5
                                                border border-emerald-500/20">
                                        <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="font-semibold text-foreground">
                                            {{ \Morilog\Jalali\Jalalian::fromCarbon($project->start_at)->format('Y/m/d') }}
                                        </span>
                                    </div>
                                    <div class="date-badge flex items-center gap-1.5 rounded-xl
                                                bg-red-500/10 dark:bg-red-500/15 px-3 py-1.5
                                                border border-red-500/20">
                                        <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="font-semibold text-foreground">
                                            {{ \Morilog\Jalali\Jalalian::fromCarbon($project->end_at)->format('Y/m/d') }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Timer --}}
                                <div class="mb-4">
                                    <div class="timer-box">
                                        <div class="timer-header">
                                            <span class="timer-header-text">⏰ زمان باقی‌مانده تا پایان</span>
                                        </div>
                                        <div class="timer-content">
                                            <div class="timer-unit">
                                                <span class="timer-value" x-text="days.toString().padStart(2, '0')">00</span>
                                                <span class="timer-label text-white">روز</span>
                                            </div>
                                            <span class="timer-separator">:</span>
                                            <div class="timer-unit">
                                                <span class="timer-value" x-text="hours.toString().padStart(2, '0')">00</span>
                                                <span class="timer-label text-white">ساعت</span>
                                            </div>
                                            <span class="timer-separator">:</span>
                                            <div class="timer-unit">
                                                <span class="timer-value" x-text="minutes.toString().padStart(2, '0')">00</span>
                                                <span class="timer-label text-white">دقیقه</span>
                                            </div>
                                            <span class="timer-separator">:</span>
                                            <div class="timer-unit">
                                                <span class="timer-value" x-text="seconds.toString().padStart(2, '0')">00</span>
                                                <span class="timer-label text-white">ثانیه</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Spacer --}}
                                <div class="flex-1"></div>

                                {{-- Action Button --}}
                                <button
                                    type="button"
                                    wire:click="selectProject({{ $project->id }})"
                                    class="action-button mt-4 w-full rounded-xl px-5 py-3 text-sm font-bold
                                           transition-all duration-300 focus-visible:outline-none
                                           focus-visible:ring-2 focus-visible:ring-green-500/70
                                           focus-visible:ring-offset-2 focus-visible:ring-offset-background
                                           @if(isset($submissions[$project->id]) && $submissions[$project->id])
                                               bg-emerald-500/15 text-emerald-500 border-2 border-emerald-500/30
                                               hover:bg-emerald-500/20 hover:border-emerald-500/50
                                           @else
                                               btn-gradient-active text-white border-2 border-transparent
                                           @endif">
                                    <span class="relative z-10 flex items-center justify-center gap-2">
                                        @if(isset($submissions[$project->id]) && $submissions[$project->id])
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>مشاهده و ویرایش</span>
                                        @else
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M13 7l5 5m0 0-5 5m5-5H6"/>
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
                <h2 class="section-header text-xl font-black text-foreground mb-5 flex items-center gap-2.5"
                    style="--header-color: rgb(251, 191, 36)">
                    <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                    در انتظار شروع
                </h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($upcomingProjects as $project)
                        <article
                            x-data="projectTimer('{{ $project->start_at->toIso8601String() }}', 'upcoming')"
                            class="project-card upcoming-card group flex flex-col h-full rounded-2xl
                                   border-2 border-border/70 bg-card/95 dark:bg-card/90
                                   shadow-lg hover:shadow-2xl transition-all duration-400">

                            {{-- Status Badge --}}
                            <div class="absolute top-4 left-4 z-10">
                                <span class="status-badge inline-flex items-center gap-1.5 rounded-xl
                                             border-2 border-amber-500/30 bg-amber-500/15
                                             px-3 py-1.5 text-xs font-bold text-amber-500 backdrop-blur-sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    در انتظار
                                </span>
                            </div>

                            {{-- Content --}}
                            <div class="relative flex h-full flex-col p-5 sm:p-6">
                                <div class="mb-3">
                                    <h3 class="text-lg md:text-xl font-bold text-foreground line-clamp-2 leading-tight mb-2">
                                        {{ $project->name }}
                                    </h3>
                                    @if($project->description)
                                        <div class="group/desc relative">
                                            <p class="text-sm leading-relaxed text-muted line-clamp-3 group-hover/desc:line-clamp-none transition-all duration-300">
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
                                    <div class="date-badge flex items-center gap-1.5 rounded-xl
                                                bg-amber-500/10 dark:bg-amber-500/15 px-3 py-1.5
                                                border border-amber-500/20">
                                        <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="font-semibold text-foreground">
                                            {{ \Morilog\Jalali\Jalalian::fromCarbon($project->start_at)->format('Y/m/d H:i') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="timer-box">
                                        <div class="timer-header">
                                            <span class="timer-header-text">⏰ زمان باقی‌مانده تا شروع</span>
                                        </div>
                                        <div class="timer-content">
                                            <div class="timer-unit">
                                                <span class="timer-value" x-text="days.toString().padStart(2, '0')">00</span>
                                                <span class="timer-label">روز</span>
                                            </div>
                                            <span class="timer-separator">:</span>
                                            <div class="timer-unit">
                                                <span class="timer-value" x-text="hours.toString().padStart(2, '0')">00</span>
                                                <span class="timer-label">ساعت</span>
                                            </div>
                                            <span class="timer-separator">:</span>
                                            <div class="timer-unit">
                                                <span class="timer-value" x-text="minutes.toString().padStart(2, '0')">00</span>
                                                <span class="timer-label">دقیقه</span>
                                            </div>
                                            <span class="timer-separator">:</span>
                                            <div class="timer-unit">
                                                <span class="timer-value" x-text="seconds.toString().padStart(2, '0')">00</span>
                                                <span class="timer-label">ثانیه</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-1"></div>

                                <button
                                    type="button"
                                    disabled
                                    class="mt-4 w-full rounded-xl px-5 py-3 text-sm font-bold
                                           bg-gray-500/10 text-gray-400 border-2 border-gray-500/20
                                           cursor-not-allowed">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
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
                <h2 class="section-header text-xl font-black text-foreground mb-5 flex items-center gap-2.5"
                    style="--header-color: rgb(239, 68, 68)">
                    <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                    تمام شده / غیرفعال
                </h2>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($endedProjects as $project)
                        <article class="project-card ended-card group flex flex-col h-full rounded-2xl
                                        border-2 border-border/70 bg-card/95 dark:bg-card/90
                                        shadow-lg transition-all duration-400">

                            <div class="absolute top-4 left-4 z-10">
                                <span class="status-badge inline-flex items-center gap-1.5 rounded-xl
                                             border-2 border-red-500/30 bg-red-500/15
                                             px-3 py-1.5 text-xs font-bold text-red-500 backdrop-blur-sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    تمام شده
                                </span>
                            </div>

                            <div class="relative flex h-full flex-col p-5 sm:p-6">
                                <div class="mb-3">
                                    <h3 class="text-lg md:text-xl font-bold text-foreground line-clamp-2 leading-tight mb-2">
                                        {{ $project->name }}
                                    </h3>
                                    @if($project->description)
                                        <div class="group/desc relative">
                                            <p class="text-sm leading-relaxed text-muted line-clamp-3 group-hover/desc:line-clamp-none transition-all duration-300">
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
                                    <div class="date-badge flex items-center gap-1.5 rounded-xl
                                                bg-red-500/10 dark:bg-red-500/15 px-3 py-1.5
                                                border border-red-500/20">
                                        <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="font-semibold text-foreground">
                                            {{ \Morilog\Jalali\Jalalian::fromCarbon($project->end_at)->format('Y/m/d') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex-1"></div>

                                <button
                                    type="button"
                                    disabled
                                    class="mt-4 w-full rounded-xl px-5 py-3 text-sm font-bold
                                           bg-red-500/10 text-red-400 border-2 border-red-500/20
                                           cursor-not-allowed">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="py-20 text-center">
                <div class="mb-6 inline-flex h-24 w-24 items-center justify-center rounded-3xl
                            bg-gradient-to-br from-blue-500/10 to-purple-500/10 border-2 border-border">
                    <svg class="h-12 w-12 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-bold text-foreground">پروژه‌ای در دسترس نیست</h3>
                <p class="text-sm text-muted">در حال حاضر پروژه طبقه‌بندی فعالی وجود ندارد.</p>
            </div>
        @endif

        {{-- Grade Selection Modal --}}
        @if($showGradeModal && $selectedProject)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" x-data x-cloak>
                {{-- Backdrop --}}
                <div class="modal-backdrop absolute inset-0 bg-black/70 backdrop-blur-lg"
                     wire:click="closeModal"></div>

                {{-- Modal --}}
                <div class="modal-content relative z-10 w-full max-w-md sm:max-w-lg
                            overflow-hidden rounded-3xl border-2 border-border/70
                            bg-card/98 dark:bg-slate-900/98 shadow-2xl backdrop-blur-xl">

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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="max-h-[65vh] overflow-y-auto p-6">
                        @if(count($availableGrades) > 0)
                            <p class="mb-5 text-sm text-muted leading-relaxed">
                                پایه‌ای که می‌خواهید مباحث آن را طبقه‌بندی کنید انتخاب کنید:
                            </p>

                            <div class="grid gap-3.5">
                                @foreach($availableGrades as $grade)
                                    <button type="button"
                                            wire:click="startClassification({{ $grade['grade'] }})"
                                            class="grade-option group flex items-center justify-between
                                                   rounded-2xl border-2 border-border/70
                                                   bg-card/90 dark:bg-slate-900/80 p-4
                                                   hover:border-blue-500/60 hover:bg-blue-500/[0.06]">

                                        <div class="flex items-center gap-4">
                                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl
                                                        bg-gradient-to-br from-blue-500/25 to-purple-500/30
                                                        border border-blue-500/20 group-hover:scale-110
                                                        transition-transform duration-300">
                                                <span class="text-xl font-black text-blue-500">
                                                    {{ $grade['grade'] }}
                                                </span>
                                            </div>

                                            <div class="space-y-1.5 text-right">
                                                <div class="text-base font-bold text-foreground">
                                                    پایه {{ $grade['name'] }}
                                                </div>
                                                <div class="flex flex-wrap items-center gap-1.5">
                                                    <span class="rounded-xl border px-2.5 py-1 text-xs font-semibold
                                                        {{ $grade['type'] === 'progress'
                                                            ? 'border-green-500/30 bg-green-500/15 text-green-500'
                                                            : 'border-amber-500/30 bg-amber-500/15 text-amber-500' }}">
                                                        {{ $grade['type_name'] }}
                                                    </span>
                                                    @if($grade['has_general'])
                                                        <span class="rounded-xl border border-blue-500/30
                                                                     bg-blue-500/15 px-2.5 py-1 text-xs
                                                                     font-semibold text-blue-500">
                                                            + عمومی
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <svg class="h-6 w-6 text-muted transition-all duration-300
                                                    group-hover:text-blue-500 group-hover:translate-x-1"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                @endforeach
                            </div>

                        @else
                            <div class="py-12 text-center">
                                <div class="mb-5 inline-flex h-20 w-20 items-center justify-center
                                            rounded-3xl bg-amber-500/15 border-2 border-amber-500/20">
                                    <svg class="h-10 w-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <h4 class="mb-2 text-lg font-bold text-foreground">پایه‌ای تعریف نشده</h4>
                                <p class="text-sm text-muted">پایه‌ای برای شما در این پروژه تعریف نشده است.</p>
                                <p class="mt-1 text-xs text-muted">لطفاً با پشتیبانی تماس بگیرید.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Loading Overlay --}}
        <div wire:loading.flex
             class="fixed inset-0 z-[60] hidden items-center justify-center
                    bg-black/70 backdrop-blur-md">
            <div class="flex items-center gap-3 rounded-2xl border-2 border-border/70
                        bg-card/98 px-6 py-5 shadow-2xl backdrop-blur-xl
                        dark:bg-slate-900/98">
                <div class="h-8 w-8 animate-spin rounded-full border-4
                            border-blue-500/80 border-t-transparent"></div>
                <span class="text-base font-semibold text-foreground">در حال بارگذاری...</span>
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
