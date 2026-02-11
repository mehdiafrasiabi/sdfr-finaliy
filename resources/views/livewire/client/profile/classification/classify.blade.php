<div
    x-data
    class="min-h-screen bg-background text-foreground pb-28 sm:pb-32"
    dir="rtl"
>
    @push('link')
        <style>
            [x-cloak] {
                display: none !important;
            }

            /* ===== Card Shadows ===== */
            .card-soft-shadow {
                box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08),
                0 10px 25px rgba(15, 23, 42, 0.04);
                transition: box-shadow 0.3s ease;
            }

            .card-soft-shadow:hover {
                box-shadow: 0 25px 60px rgba(15, 23, 42, 0.12),
                0 15px 30px rgba(15, 23, 42, 0.06);
            }

            /* ===== Subject Card Styles ===== */
            .subject-card {
                position: relative;
                overflow: hidden;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .subject-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg,
                rgba(59, 130, 246, 0.03) 0%,
                rgba(37, 99, 235, 0.03) 100%);
                opacity: 0;
                transition: opacity 0.3s ease;
                z-index: 0;
                pointer-events: none;
            }

            .subject-card:hover::before {
                opacity: 1;
            }

            /* ===== Topic Item Styles ===== */
            .topic-item {
                position: relative;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border: 2px solid transparent;
                overflow: visible;
            }

            .topic-item:hover {
                border-color: rgba(59, 130, 246, 0.3);
                background: rgba(59, 130, 246, 0.05);
                transform: translateX(-4px);
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
            }

            .topic-item.rated {
                border-color: rgba(251, 191, 36, 0.3);
                background: linear-gradient(135deg,
                rgba(251, 191, 36, 0.05) 0%,
                rgba(245, 158, 11, 0.05) 100%);
            }

            /* ===== Star Rating Styles ===== */
            .star-btn {
                position: relative;
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
                background: transparent;
                border: none;
            }

            .star-btn:hover {
                transform: scale(1.15);
            }

            .star-btn:active {
                transform: scale(0.95);
            }

            /* Empty Star - خالی */
            .star-empty {
                color: rgb(209, 213, 219);
            }

            .star-empty:hover {
                color: rgb(251, 191, 36);
            }

            /* Filled Star - پر شده طلایی */
            .star-filled {
                color: rgb(251, 191, 36);
                filter: drop-shadow(0 2px 4px rgba(251, 191, 36, 0.4));
            }

            /* Star Animation */
            @keyframes starPop {
                0% {
                    transform: scale(1);
                }
                50% {
                    transform: scale(1.25);
                }
                100% {
                    transform: scale(1);
                }
            }

            .star-filled {
                animation: starPop 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* ===== Tag/Filter Styles ===== */
            .filter-tag {
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .filter-tag::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 0;
                height: 0;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                transform: translate(-50%, -50%);
                transition: width 0.5s ease, height 0.5s ease;
            }

            .filter-tag:hover::before {
                width: 200px;
                height: 200px;
            }

            /* ===== Progress Bar Animation ===== */
            .progress-bar {
                position: relative;
                overflow: hidden;
            }

            .progress-bar::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent);
                animation: shimmer 2s infinite;
            }

            @keyframes shimmer {
                0% {
                    transform: translateX(-100%);
                }
                100% {
                    transform: translateX(100%);
                }
            }

            /* ===== Chapter Badge ===== */
            .chapter-badge {
                position: relative;
                overflow: hidden;
            }

            .chapter-badge::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent);
                transition: left 0.5s ease;
            }

            .chapter-badge:hover::before {
                left: 100%;
            }

            /* ===== Rating Badge Colors ===== */
            .badge-excellent {
                background: linear-gradient(135deg,
                rgba(34, 197, 94, 0.15) 0%,
                rgba(16, 185, 129, 0.15) 100%);
                color: rgb(34, 197, 94);
                border: 1px solid rgba(34, 197, 94, 0.3);
            }

            .badge-good {
                background: linear-gradient(135deg,
                rgba(59, 130, 246, 0.15) 0%,
                rgba(37, 99, 235, 0.15) 100%);
                color: rgb(59, 130, 246);
                border: 1px solid rgba(59, 130, 246, 0.3);
            }

            .badge-average {
                background: linear-gradient(135deg,
                rgba(251, 191, 36, 0.15) 0%,
                rgba(245, 158, 11, 0.15) 100%);
                color: rgb(251, 191, 36);
                border: 1px solid rgba(251, 191, 36, 0.3);
            }

            .badge-poor {
                background: linear-gradient(135deg,
                rgba(239, 68, 68, 0.15) 0%,
                rgba(220, 38, 38, 0.15) 100%);
                color: rgb(239, 68, 68);
                border: 1px solid rgba(239, 68, 68, 0.3);
            }

            /* ===== Subject Header Gradient ===== */
            .subject-header {
                background: linear-gradient(135deg,
                rgba(59, 130, 246, 0.08) 0%,
                rgba(37, 99, 235, 0.08) 50%,
                rgba(29, 78, 216, 0.08) 100%);
                position: relative;
                overflow: hidden;
            }

            .subject-header::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.1),
                transparent);
                transition: left 0.6s ease;
            }

            .subject-header:hover::before {
                left: 100%;
            }

            /* ===== Modal Animation ===== */
            @keyframes modalSlideUp {
                from {
                    opacity: 0;
                    transform: translateY(30px) scale(0.95);
                }
                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .modal-content {
                animation: modalSlideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* ===== Loading Spinner ===== */
            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

            .loading-spinner {
                animation: spin 1s linear infinite;
            }

            /* ===== Submit Button Pulse ===== */
            .submit-btn-active {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            @keyframes pulse {
                0%, 100% {
                    box-shadow: 0 8px 20px rgba(34, 197, 94, 0.3);
                }
                50% {
                    box-shadow: 0 12px 28px rgba(34, 197, 94, 0.5);
                }
            }

            /* ===== Dark Mode Adjustments ===== */
            @media (prefers-color-scheme: dark) {
                .subject-card::before {
                    background: linear-gradient(135deg,
                    rgba(59, 130, 246, 0.06) 0%,
                    rgba(37, 99, 235, 0.06) 100%);
                }

                .topic-item:hover {
                    background: rgba(59, 130, 246, 0.08);
                    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
                }

                .star-empty {
                    color: rgb(107, 114, 128);
                }

                .star-filled {
                    color: rgb(251, 191, 36);
                    filter: drop-shadow(0 2px 6px rgba(251, 191, 36, 0.5));
                }
            }

            /* ===== Responsive ===== */
            @media (max-width: 640px) {
                .star-btn svg {
                    width: 1.25rem;
                    height: 1.25rem;
                }

                .star-btn:hover {
                    transform: scale(1.1);
                }

                .topic-item {
                    overflow: visible;
                }

                /* فاصله بیشتر برای موبایل */
                .topic-item > div {
                    gap: 0.75rem;
                }
            }
        </style>
    @endpush

    <div class="container mx-auto px-4 max-w-7xl pt-4 sm:pt-6 pb-24">

        {{-- Header Section --}}
        <header class="mb-6 sm:mb-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                {{-- Title & Description --}}
                <div class="flex items-start gap-3 flex-1">
                    <div class="flex items-center gap-1 mt-1">
                        <div class="w-1 h-1 bg-primary/60 rounded-full animate-pulse"></div>
                        <div class="w-2 h-2 bg-primary rounded-full animate-pulse" style="animation-delay: 0.2s"></div>
                    </div>

                    <div class="flex-1">
                        <h1 class="font-black text-xl sm:text-2xl text-foreground mb-2">
                            {{ $project->name }}
                        </h1>
                        <p class="text-sm sm:text-base text-muted leading-relaxed">
                            مباحث را امتیازدهی کنید تا تصویر دقیقی از وضعیت خودتان داشته باشید.
                        </p>
                    </div>
                </div>

                {{-- Back Button --}}
                <div class="mt-2 sm:mt-0">
                    <a
                        wire:navigate
                        href="{{ route('client.profile.classification.projects') }}"
                        class="inline-flex items-center justify-center gap-2 h-10 sm:h-11
                               rounded-xl border-2 border-border bg-background px-5 sm:px-6
                               text-xs sm:text-sm font-bold text-muted
                               hover:text-foreground hover:bg-secondary hover:border-primary/30
                               transition-all duration-300"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="2"
                             stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3"/>
                        </svg>
                        <span>بازگشت</span>
                    </a>
                </div>
            </div>
        </header>

        {{-- Filter & Progress Section --}}
        <section class="mb-6 rounded-2xl border-2 border-border bg-card/95 p-5 sm:p-6 card-soft-shadow">
            <div class="space-y-5">
                {{-- Filters Row --}}
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    {{-- Tag Filters --}}
                    <div class="flex flex-wrap gap-2">
                        @foreach($availableTags as $tag)
                            <button
                                wire:click="selectTag('{{ $tag['id'] }}')"
                                type="button"
                                class="filter-tag relative rounded-xl px-4 sm:px-5 py-2 sm:py-2.5
                                       text-xs sm:text-sm font-bold
                                       transition-all duration-300
                                       {{ $activeTag === $tag['id']
                                          ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-500/30 scale-105'
                                          : 'bg-secondary text-foreground hover:bg-muted border border-border' }}"
                            >
                                <span class="relative z-10">{{ $tag['label'] }}</span>
                            </button>
                        @endforeach

                        {{-- My Topics Button --}}
                        <button
                            wire:click="showMyRatings"
                            type="button"
                            class="filter-tag relative rounded-xl px-4 sm:px-5 py-2 sm:py-2.5
                                   text-xs sm:text-sm font-bold flex items-center gap-2
                                   transition-all duration-300
                                   {{ $showMyTopics
                                      ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/30 scale-105'
                                      : 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500/20 border border-emerald-500/30' }}"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <span class="relative z-10">مباحث من</span>
                        </button>
                    </div>

                    {{-- Progress Summary --}}
                    <div class="inline-flex items-center gap-3 rounded-xl bg-primary/10 dark:bg-primary/15
                                px-4 sm:px-5 py-2 sm:py-2.5 border border-primary/20">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <div class="flex items-center gap-2 text-xs sm:text-sm">
                            <span class="text-muted font-medium">پیشرفت:</span>
                            <span class="font-black text-foreground">
                                {{ $completedTopics }} / {{ $totalTopics }}
                            </span>
                            @if($totalTopics > 0)
                                <span class="text-xs font-black text-primary">
                                    ({{ round(($completedTopics / $totalTopics) * 100) }}%)
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="relative">
                    <div class="h-3 rounded-full bg-muted/60 overflow-hidden">
                        <div
                            class="progress-bar h-full rounded-full bg-gradient-to-r
                                   from-blue-500 via-sky-500 to-blue-600
                                   transition-all duration-700 ease-out"
                            style="width: {{ $totalTopics > 0 ? ($completedTopics / $totalTopics) * 100 : 0 }}%"
                        ></div>
                    </div>
                    @if($totalTopics > 0)
                        <div class="absolute -top-1 right-0 left-0 flex justify-between text-[10px] text-muted font-medium">
                            <span>0%</span>
                            <span>50%</span>
                            <span>100%</span>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- Main Content --}}
        @if(!$showMyTopics)
            {{-- Subjects List --}}
            <section class="space-y-5 md:space-y-6">
                @forelse($subjects as $subjectIndex => $subject)
                    <div
                        x-data="{ open: true }"
                        class="subject-card rounded-2xl bg-card/95 overflow-hidden card-soft-shadow"
                        style="animation-delay: {{ $subjectIndex * 0.1 }}s"
                    >
                        {{-- Subject Header --}}
                        <button
                            type="button"
                            @click="open = !open"
                            class="subject-header flex w-full items-center justify-between px-5 py-4 sm:px-6 sm:py-5
                                   border-b border-border/30"
                        >
                            <div class="flex items-center gap-4">
                                {{-- Icon --}}
                                <div class="flex h-12 w-12 sm:h-14 sm:w-14 items-center justify-center rounded-2xl
                                           bg-gradient-to-br from-blue-500 to-blue-600 text-white
                                           shadow-lg shadow-blue-500/30">
                                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>

                                {{-- Title & Meta --}}
                                <div class="flex flex-col items-start gap-1">
                                    <span class="text-base sm:text-lg font-black text-foreground">
                                        {{ $subject['name'] }}
                                    </span>
                                    <div class="flex items-center gap-2 text-xs text-muted">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                            </svg>
                                            {{ count($subject['chapters']) }} فصل
                                        </span>
                                        <span class="text-muted/50">•</span>
                                        <span>
                                            {{ collect($subject['chapters'])->sum(fn($ch) => count($ch['topics'])) }} مبحث
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Expand Icon --}}
                            <div class="flex items-center gap-3">
                                <span class="hidden sm:inline text-xs text-muted font-medium">
                                    <span x-text="open ? 'بستن' : 'باز کردن'"></span>
                                </span>
                                <svg
                                    class="w-5 h-5 text-primary transition-transform duration-300"
                                    :class="{ 'rotate-180': open }"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                          d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        {{-- Chapters & Topics --}}
                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-cloak
                            class="divide-y divide-border/30 bg-background/50"
                        >
                            @foreach($subject['chapters'] as $chapterIndex => $chapter)
                                <div class="p-4 sm:p-6 space-y-4">
                                    {{-- Chapter Header --}}
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="chapter-badge inline-flex items-center gap-2.5 rounded-xl
                                                    bg-gradient-to-r from-blue-50 to-sky-50
                                                    dark:from-blue-900/20 dark:to-sky-900/20
                                                    px-4 py-2 border border-blue-200/50 dark:border-blue-800/50">
                                            <span class="flex h-6 w-6 items-center justify-center rounded-lg
                                                         bg-gradient-to-br from-blue-500 to-blue-600 text-white text-xs font-bold">
                                                {{ $chapterIndex + 1 }}
                                            </span>
                                            <span class="text-sm sm:text-base font-bold text-foreground">
                                                {{ $chapter['name'] }}
                                            </span>
                                        </div>

                                        <span class="hidden sm:inline-flex items-center gap-1.5 text-xs text-muted
                                                     bg-muted/30 px-3 py-1 rounded-lg">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ count($chapter['topics']) }} مبحث
                                        </span>
                                    </div>

                                    {{-- Topics --}}
                                    <div class="space-y-3">
                                        @foreach($chapter['topics'] as $topic)
                                            @if($topic['has_subtopics'] && isset($topic['children']) && count($topic['children']) > 0)
                                                {{-- Topic with Subtopics - Display Parent Name --}}
                                                <div class="mb-4">
                                                    <div class="flex items-center gap-2 mb-3 px-4 py-2 bg-info-subtle rounded-lg">
                                                        <svg class="w-5 h-5 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                                        </svg>
                                                        <span class="text-sm font-bold text-info">{{ $topic['name'] }}</span>
                                                    </div>
                                                    {{-- Display Subtopics --}}
                                                    <div class="space-y-3 pr-6">
                                                        @foreach($topic['children'] as $subtopic)
                                                            <div
                                                                class="topic-item rounded-xl px-4 py-4 sm:px-5 sm:py-4
                                                                       bg-secondary/50
                                                                       {{ isset($ratings[$subtopic['id']]) ? 'rated' : '' }}"
                                                            >
                                                                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                                                    {{-- Subtopic Name --}}
                                                                    <div class="flex-1 min-w-0">
                                                                        <span class="text-sm sm:text-base font-semibold text-foreground leading-relaxed block">
                                                                            {{ $subtopic['name'] }}
                                                                        </span>
                                                                    </div>

                                                                    {{-- Rating Controls for Subtopic --}}
                                                                    <div class="flex items-center gap-3 sm:gap-4 flex-shrink-0">
                                                                        {{-- Labels --}}
                                                                        <div class="hidden lg:flex items-center gap-2 text-xs text-muted font-medium" dir="ltr">
                                                                            <span class="px-2 py-1 rounded bg-red-500/10 text-red-500">D</span>
                                                                            <span class="text-muted/50">→</span>
                                                                            <span class="px-2 py-1 rounded bg-green-500/10 text-green-500">A+</span>
                                                                        </div>

                                                                        {{-- Stars --}}
                                                                        <div class="flex items-center gap-1 sm:gap-1.5" dir="ltr">
                                                                            @for($i = 1; $i <= 8; $i++)
                                                                                <button
                                                                                    wire:click="setRating({{ $subtopic['id'] }}, {{ $i }})"
                                                                                    type="button"
                                                                                    class="star-btn flex items-center justify-center
                                                                                           {{ isset($ratings[$subtopic['id']]) && $ratings[$subtopic['id']] >= $i
                                                                                              ? 'star-filled'
                                                                                              : 'star-empty' }}"
                                                                                    title="{{ $this->getRatingLabel($i) }}"
                                                                                >
                                                                                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="currentColor" viewBox="0 0 20 20">
                                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                                    </svg>
                                                                                </button>
                                                                            @endfor

                                                                            {{-- Clear Button --}}
                                                                            @if(isset($ratings[$subtopic['id']]))
                                                                                <button
                                                                                    wire:click="clearRating({{ $subtopic['id'] }})"
                                                                                    type="button"
                                                                                    class="mr-2 flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8
                                                                                           rounded-full bg-red-500/15 text-red-500 hover:bg-red-500/25
                                                                                           transition-all duration-200 hover:scale-110"
                                                                                    title="حذف امتیاز"
                                                                                >
                                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                                    </svg>
                                                                                </button>
                                                                            @endif
                                                                        </div>

                                                                        {{-- Rating Badge --}}
                                                                        @if(isset($ratings[$subtopic['id']]))
                                                                            <span class="text-xs sm:text-sm font-black px-3 py-1.5 rounded-xl
                                                                                         {{ $this->getRatingBadgeColor($ratings[$subtopic['id']]) }}">
                                                                                {{ $this->getRatingLabel($ratings[$subtopic['id']]) }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                {{-- Regular Topic without Subtopics --}}
                                                <div
                                                    class="topic-item rounded-xl px-4 py-4 sm:px-5 sm:py-4
                                                           bg-secondary/50
                                                           {{ isset($ratings[$topic['id']]) ? 'rated' : '' }}"
                                                >
                                                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                                        {{-- Topic Name --}}
                                                        <div class="flex-1 min-w-0">
                                                            <span class="text-sm sm:text-base font-semibold text-foreground leading-relaxed block">
                                                                {{ $topic['name'] }}
                                                            </span>
                                                        </div>

                                                        {{-- Rating Controls --}}
                                                        <div class="flex items-center gap-3 sm:gap-4 flex-shrink-0">
                                                        {{-- Labels --}}
                                                        <div class="hidden lg:flex items-center gap-2 text-xs text-muted font-medium" dir="ltr">
                                                            <span class="px-2 py-1 rounded bg-red-500/10 text-red-500">D</span>
                                                            <span class="text-muted/50">→</span>
                                                            <span class="px-2 py-1 rounded bg-green-500/10 text-green-500">A+</span>
                                                        </div>

                                                        {{-- Stars --}}
                                                        <div class="flex items-center gap-1 sm:gap-1.5" dir="ltr">
                                                            @for($i = 1; $i <= 8; $i++)
                                                                <button
                                                                    wire:click="setRating({{ $topic['id'] }}, {{ $i }})"
                                                                    type="button"
                                                                    class="star-btn flex items-center justify-center
                                                                           {{ isset($ratings[$topic['id']]) && $ratings[$topic['id']] >= $i
                                                                              ? 'star-filled'
                                                                              : 'star-empty' }}"
                                                                    title="{{ $this->getRatingLabel($i) }}"
                                                                >
                                                                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                    </svg>
                                                                </button>
                                                            @endfor

                                                            {{-- Clear Button --}}
                                                            @if(isset($ratings[$topic['id']]))
                                                                <button
                                                                    wire:click="clearRating({{ $topic['id'] }})"
                                                                    type="button"
                                                                    class="mr-2 flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8
                                                                           rounded-full bg-red-500/15 text-red-500 hover:bg-red-500/25
                                                                           transition-all duration-200 hover:scale-110"
                                                                    title="حذف امتیاز"
                                                                >
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                    </svg>
                                                                </button>
                                                            @endif
                                                        </div>

                                                        {{-- Rating Badge --}}
                                                        @if(isset($ratings[$topic['id']]))
                                                            <span class="text-xs sm:text-sm font-black px-3 py-1.5 rounded-xl
                                                                         {{ $this->getRatingBadgeColor($ratings[$topic['id']]) }}">
                                                                {{ $this->getRatingLabel($ratings[$topic['id']]) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    {{-- Empty State --}}
                    <div class="mt-8 rounded-2xl border-2 border-dashed border-border bg-card/90 py-16 text-center card-soft-shadow">
                        <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-2xl
                                    bg-gradient-to-br from-blue-500/10 to-sky-500/10 text-muted">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-lg font-bold text-foreground">مبحثی یافت نشد</h3>
                        <p class="text-sm text-muted">
                            لطفاً دسته‌بندی دیگری را انتخاب کنید.
                        </p>
                    </div>
                @endforelse
            </section>
        @else
            {{-- My Rated Topics --}}
            <section class="rounded-2xl border-2 border-emerald-500/30 bg-card/95 overflow-hidden card-soft-shadow">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-emerald-500/15 to-green-500/15 px-5 py-4 sm:px-6 sm:py-5
                            border-b-2 border-emerald-500/30">
                    <h2 class="flex items-center gap-3 text-base sm:text-lg font-black text-foreground">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl
                                    bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <span>مباحث امتیازدهی شده من</span>
                    </h2>
                </div>

                {{-- Content --}}
                <div class="p-5 sm:p-6">
                    @if($myRatedTopics->count() > 0)
                        <div class="space-y-5">
                            @foreach($myRatedTopics as $subjectName => $topics)
                                <div class="overflow-hidden rounded-xl border-2 border-border bg-background/70">
                                    {{-- Subject Name --}}
                                    <div class="bg-muted/60 px-4 py-3 text-sm sm:text-base font-bold text-foreground
                                                border-b-2 border-border">
                                        {{ $subjectName }}
                                    </div>

                                    {{-- Topics List --}}
                                    <div class="divide-y divide-border">
                                        @foreach($topics as $classification)
                                            <div class="flex flex-col gap-3 px-4 py-3.5 sm:flex-row sm:items-center sm:justify-between
                                                        hover:bg-muted/30 transition-colors">
                                                <div class="flex-1">
                                                    <span class="text-sm sm:text-base font-semibold text-foreground">
                                                        {{ $classification->topic->name }}
                                                    </span>
                                                    <span class="mt-1 block text-xs text-muted">
                                                        {{ $classification->topic->chapter->name }}
                                                        @if($classification->topic->parent)
                                                            <span class="text-info"> → {{ $classification->topic->parent->name }}</span>
                                                        @endif
                                                    </span>
                                                </div>

                                                <span class="inline-flex items-center justify-center rounded-xl px-4 py-2
                                                             text-xs sm:text-sm font-black {{ $this->getRatingBadgeColor($classification->rating) }}">
                                                    {{ $this->getRatingLabel($classification->rating) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-xl
                                        bg-muted/50 text-muted">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <p class="text-sm text-muted">هنوز مبحثی امتیازدهی نشده است.</p>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        {{-- Fixed Submit Bar --}}
        <div class="fixed bottom-0 inset-x-0 z-40 border-t-2 border-border
                    bg-secondary/95 backdrop-blur-xl shadow-2xl">
            <div class="container mx-auto max-w-7xl px-4 py-4">
                <div class="flex items-center justify-between gap-4">
                    {{-- Status --}}
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl
                             {{ $completedTopics >= $totalTopics && $totalTopics > 0 ? 'bg-green-500/20' : ($completedTopics > 0 ? 'bg-blue-500/20' : 'bg-amber-500/20') }}">
                            <svg class="w-5 h-5 {{ $completedTopics >= $totalTopics && $totalTopics > 0 ? 'text-green-500' : ($completedTopics > 0 ? 'text-blue-500' : 'text-amber-500') }}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($completedTopics >= $totalTopics && $totalTopics > 0)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @endif
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-muted font-medium">وضعیت</div>
                            @if($completedTopics >= $totalTopics && $totalTopics > 0)
                                <div class="text-sm font-black text-green-500">
                                    کامل - آماده ثبت ✓
                                </div>
                            @elseif($completedTopics > 0)
                                <div class="text-sm font-black text-blue-500">
                                    {{ $completedTopics }} مبحث ثبت شده
                                </div>
                            @else
                                <div class="text-sm font-black text-amber-500">
                                    شروع نشده
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button
                        wire:click="openSubmitModal"
                        @if($completedTopics == 0) disabled @endif
                        class="inline-flex items-center gap-2.5 rounded-xl px-6 sm:px-8 py-3 sm:py-3.5
                               text-sm sm:text-base font-black transition-all duration-300
                               {{ $completedTopics > 0
                                  ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white submit-btn-active hover:from-green-600 hover:to-emerald-700 hover:scale-105'
                                  : 'bg-gray-600/50 text-gray-400 cursor-not-allowed opacity-60' }}"
                    >
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>ثبت نهایی طبقه‌بندی</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Submit Confirmation Modal --}}
        @if($showSubmitModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-0" x-data x-cloak>
                {{-- Backdrop --}}
                <div class="fixed inset-0 bg-black/70 backdrop-blur-md"
                     wire:click="$set('showSubmitModal', false)"></div>

                {{-- Modal --}}
                <div class="modal-content relative z-20 w-full max-w-md overflow-hidden rounded-3xl
                            border-2 border-border bg-background shadow-2xl">

                    {{-- Close Button --}}
                    <button
                        type="button"
                        wire:click="$set('showSubmitModal', false)"
                        class="absolute left-4 top-4 z-10 text-muted hover:text-red-500
                               transition-colors focus:outline-none"
                    >
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    {{-- Body --}}
                    <div class="p-8 text-center">
                        <div class="mb-6 inline-flex h-24 w-24 items-center justify-center rounded-2xl
                                    bg-gradient-to-br from-green-500 to-emerald-600 text-white shadow-2xl shadow-green-500/30">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>

                        <h3 class="text-xl sm:text-2xl font-black text-foreground mb-3">
                            تأیید ثبت نهایی
                        </h3>

                        <p class="text-sm sm:text-base text-muted leading-relaxed mb-6">
                            آیا از ثبت طبقه‌بندی خود اطمینان دارید؟<br>
                            شما {{ $completedTopics }} مبحث را امتیازدهی کرده‌اید.
                            @if($completedTopics < $totalTopics)
                                <br><span class="text-amber-500 font-medium">توجه: همه مباحث امتیازدهی نشده‌اند، اما می‌توانید ثبت کنید.</span>
                            @endif
                        </p>

                        {{-- Actions --}}
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                wire:click="$set('showSubmitModal', false)"
                                class="flex-1 rounded-xl border-2 border-border bg-background px-5 py-3
                                       text-sm font-bold text-foreground hover:bg-secondary
                                       transition-all duration-300"
                            >
                                انصراف
                            </button>

                            <button
                                type="button"
                                wire:click="submitClassification"
                                class="flex-1 rounded-xl border-2 border-transparent
                                       bg-gradient-to-r from-green-500 to-emerald-600 px-5 py-3
                                       text-sm font-bold text-white hover:from-green-600 hover:to-emerald-700
                                       transition-all duration-300 shadow-lg shadow-green-500/30"
                            >
                                <span wire:loading.remove wire:target="submitClassification">
                                    ثبت نهایی
                                </span>
                                <span wire:loading wire:target="submitClassification" class="flex items-center justify-center gap-2">
                                    <svg class="loading-spinner h-4 w-4 border-2 border-white border-t-transparent rounded-full"></svg>
                                    در حال ثبت...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Loading Overlay --}}
        <div
            wire:loading.flex
            wire:target="selectTag,clearRating,setRating"
            class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 backdrop-blur-md"
        >
            <div class="flex items-center gap-4 rounded-2xl border-2 border-border bg-card px-6 py-5 shadow-2xl">
                <div class="loading-spinner h-8 w-8 rounded-full border-4 border-blue-500 border-t-transparent"></div>
                <span class="text-base font-bold text-foreground">
                    در حال پردازش...
                </span>
            </div>
        </div>
    </div>
</div>
