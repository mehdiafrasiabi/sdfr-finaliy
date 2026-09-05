<div class="min-h-screen bg-background">
    <div class="max-w-7xl mx-auto px-4 py-6 space-y-6">
        <!-- Header -->
        <div class="bg-secondary border border-border rounded-2xl p-4 mb-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4 mb-2">
                    <div class="flex-shrink-0 w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div style="margin-right: 10px">
                        <h1 class="font-bold text-xl text-foreground">نتیجه آزمون</h1>
                        <p class="text-sm text-muted">{{ $exam->title }}</p>
                    </div>
                </div>
                <a href="{{ route('client.profile.typed-exam.list') }}"
                   class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-background border border-border rounded-xl text-foreground hover:bg-secondary transition-colors text-sm font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    بازگشت به لیست
                </a>
            </div>
        </div>
        <!-- View Mode Toggle -->
        <div
            class="flex items-center justify-center gap-2 bg-secondary border border-border rounded-xl p-1 max-w-md mx-auto mb-5">
            <button wire:click="setViewMode('report')"
                    class="flex-1 px-6 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ $viewMode === 'report' ? 'bg-primary text-primary-foreground' : 'text-muted hover:text-foreground' }}">
                کارنامه
            </button>
            @if($canViewAnswerKey)
                <button wire:click="setViewMode('answersheet')"
                        class="flex-1 px-6 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ $viewMode === 'answersheet' ? 'bg-primary text-primary-foreground' : 'text-muted hover:text-foreground' }}">
                    پاسخنامه
                </button>
            @endif
        </div>
        @if($attempt->canUploadAnalysis())
            <div class="mb-5 rounded-2xl border border-yellow-500/30 bg-yellow-500/10 p-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="space-y-1">
                        <p class="font-bold text-yellow-700 dark:text-yellow-300">همین حالا تحلیل آزمون خود را قرار دهید.</p>
                        <p class="text-sm text-yellow-700/90 dark:text-yellow-200/90">بعد از انتخاب تصویر، فایل‌ها به WebP تبدیل می‌شوند و مستقیم در همین بخش ثبت خواهند شد.</p>
                    </div>
                    <a href="#analysis-upload-box"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-yellow-500 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-yellow-600">
                        بزن آپلود
                    </a>
                </div>
            </div>
        @endif
        @if($viewMode === 'report')
            <!-- Report Card View -->
            @if($canViewResult)
                <!-- Exam Info -->
                <div class="bg-secondary border border-border rounded-2xl p-6 mb-5">
                    <h2 class="font-bold text-lg text-foreground mb-4 flex items-center gap-2 mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        اطلاعات آزمون
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-background border border-border rounded-xl p-4 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-auto text-green-500 mb-2"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs text-muted block">زمان شروع</span>
                            <span class="font-bold text-foreground text-sm">{{ $stats['started_at'] ? verta($stats['started_at'])->format('H:i - Y/m/d') : '-' }}</span>
                        </div>
                        <div class="bg-background border border-border rounded-xl p-4 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-auto text-red-500 mb-2"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>

                            </svg>
                            <span class="text-xs text-muted block">زمان پایان</span>
                            <span
                                class="font-bold text-foreground text-sm">{{ $stats['submitted_at'] ? verta($stats['submitted_at'])->format('H:i - Y/m/d') : '-' }}</span>
                        </div>
                        <div class="bg-background border border-border rounded-xl p-4 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-auto text-blue-500 mb-2"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>

                            </svg>
                            <span class="text-xs text-muted block">مدت آزمون</span>
                            <span dir="ltr" class="inline-block font-bold text-foreground text-sm">{{ $stats['duration'] }}</span>
                        </div>
                        <div class="bg-background border border-border rounded-xl p-4 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-auto  mb-2" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" style="color: #ca00ca">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>

                            </svg>
                            <span class="text-xs text-muted block">تعداد سوالات</span>
                            <span class="font-bold text-foreground text-sm">{{ $stats['total'] }} سوال</span>
                        </div>
                    </div>
                </div>
                <!-- Stats Summary -->
                <div class="grid md:grid-cols-2 gap-6 mb-5 mb-5">
                    <!-- Answer Stats -->
                    <div class="bg-secondary border border-border rounded-2xl p-6 mb-5">
                        <h2 class="font-bold text-lg text-foreground mb-4 flex items-center gap-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            وضعیت پاسخ‌ها
                        </h2>
                        <div class="space-y-4 mb">
                            <div
                                class="flex items-center justify-between p-4 bg-green-500/10 border border-green-500/20 rounded-xl mb-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-foreground">پاسخ صحیح</span>
                                </div>
                                <span class="font-bold text-2xl text-green-500">{{ $stats['correct'] }}</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-4 bg-red-500/10 border border-red-500/20 rounded-xl mb-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-foreground">پاسخ غلط</span>
                                </div>
                                <span class="font-bold text-2xl text-red-500">{{ $stats['wrong'] }}</span>
                            </div>
                            <div
                                class="flex items-center justify-between p-4 bg-gray-500/10 border border-gray-500/20 rounded-xl mb-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-500 rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M20 12H4"/>
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-foreground">بدون پاسخ</span>
                                </div>
                                <span class="font-bold text-2xl text-gray-500">{{ $stats['unanswered'] }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Donut Chart -->
                    <div class="bg-secondary border border-border rounded-2xl p-6 mb-5">
                        <h2 class="font-bold text-lg text-foreground mb-4 flex items-center gap-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                            </svg>
                            نمودار عملکرد
                        </h2>
                        <div class="flex flex-col items-center">
                            <div class="relative w-48 h-48" x-data="{
                                correct: {{ $stats['correct'] }},
                                wrong: {{ $stats['wrong'] }},
                                unanswered: {{ $stats['unanswered'] }},
                                score: {{ $stats['score'] ?? 0 }}
                            }">
                                <canvas id="donutChart" class="w-full h-full"></canvas>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-3xl font-bold text-foreground">{{ number_format($stats['score'] ?? 0, 1) }}%</span>
                                    <span class="text-xs text-muted">درصد کل</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-center gap-6 mt-4 mt-5">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="relative inline-flex items-center justify-center rounded-full h-5 w-5 bg-green-500 font-bold text-xs"></div>
                                    <span class="text-xs text-muted">صحیح</span>
                                </div>
                                <div class="flex items-center gap-2" style="margin-right: 10px">
                                    <div
                                        class="relative inline-flex items-center justify-center rounded-full h-5 w-5 bg-red-500 font-bold text-xs"></div>
                                    <span class="text-xs text-muted">غلط</span>
                                </div>
                                <div class="flex items-center gap-2" style="margin-right: 10px">
                                    <div
                                        class="relative inline-flex items-center justify-center rounded-full h-5 w-5 bg-gray-400 font-bold text-xs"></div>
                                    <span class="text-xs text-muted">بدون پاسخ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-secondary border border-border rounded-2xl p-6 mb-5">
                    <h2 class="font-bold text-lg text-foreground mb-4 flex items-center gap-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                        </svg>
                        نمره منفی
                    </h2>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-2xl border border-green-500/20 bg-green-500/10 p-4">
                            <span class="block text-xs text-muted mb-2">اگر آزمون بدون نمره منفی باشد</span>
                            <span class="block text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ number_format($stats['score'] ?? 0, 1) }}%
                            </span>
                            <p class="mt-2 text-sm text-muted">
                                {{ $stats['correct'] }} پاسخ صحیح از {{ $stats['total'] }} سوال
                            </p>
                        </div>
                        <div class="rounded-2xl border border-red-500/20 bg-red-500/10 p-4">
                            <span class="block text-xs text-muted mb-2">اگر آزمون با نمره منفی باشد</span>
                            <span class="block text-2xl font-bold text-red-600 dark:text-red-400">
                                {{ number_format($stats['negative_score'] ?? 0, 1) }}%
                            </span>
                            <p class="mt-2 text-sm text-muted">
                                {{ $stats['wrong'] }} غلط ثبت شده و {{ $stats['negative_penalty_count'] }} پاسخ صحیح از امتیاز شما کم می‌شود.
                            </p>
                            <p class="mt-1 text-sm text-muted">
                                نتیجه نهایی با نمره منفی: {{ $stats['negative_correct'] }} پاسخ صحیح موثر از {{ $stats['total'] }} سوال
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 rounded-xl border border-border bg-background/60 p-4 text-sm text-muted">
                        هر ۳ پاسخ غلط، ۱ پاسخ صحیح را از امتیاز کم می‌کند. سوالات بدون پاسخ، نمره منفی ندارند.
                    </div>
                </div>
                <!-- System Analysis -->
                <div class="bg-secondary border border-border rounded-2xl p-6 mb-5">
                    <h2 class="font-bold text-lg text-foreground mb-4 flex items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        تحلیل
                    </h2>
                    <div class="p-4 bg-primary/5 border border-primary/20 rounded-xl">
                        <p class="text-muted font-bold text-lg  leading-relaxed">{{ $systemAnalysis }}</p>
                    </div>
                </div>

                <!-- Analysis Upload Section -->
                <div id="analysis-upload-box" class="bg-secondary border border-border rounded-2xl p-6 mb-5">
                    <h2 class="font-bold text-lg text-foreground mb-4 flex items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>

                        آپلود تحلیل
                    </h2>

                    <!-- Analysis Status -->
                    @if($attempt->analysis_status)
                        <div class="mb-4 p-4 rounded-xl
                            {{ $attempt->analysis_status === 'pending' ? 'bg-yellow-500/10 border border-yellow-500/20' : '' }}
                            {{ $attempt->analysis_status === 'approved' ? 'bg-green-500/10 border border-green-500/20' : '' }}
                            {{ $attempt->analysis_status === 'rejected' ? 'bg-red-500/10 border border-red-500/20' : '' }}">
                            <div class="flex items-center gap-3 ">
                                @if($attempt->analysis_status === 'pending')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-500" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-semibold text-yellow-500 dark:text-yellow-400">در انتظار تایید</span>
                                @elseif($attempt->analysis_status === 'approved')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span
                                        class="font-semibold text-green-500 dark:text-green-400">تحلیل تایید شده</span>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-semibold text-red-600 dark:text-red-400">تحلیل رد شده - امکان آپلود مجدد</span>
                                @endif
                            </div>
                        </div>
                    @endif
                    <br>
                    <!-- Existing Uploads -->
                    @if($attempt->analysisUploads->count() > 0)
                        <div class="mb-4">
                            <br>
                            <h4 class="text-sm font-semibold text-foreground mb-3">تصاویر آپلود شده:</h4>
                            <br>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach($attempt->analysisUploads as $upload)
                                    <div class="relative group">
                                        <img src="{{ $upload->url }}" alt="وجود ندارد"
                                             class="w-full h-32 object-cover rounded-xl border border-border">
                                        @if($attempt->canUploadAnalysis())
                                            <button wire:click="deleteAnalysisFile({{ $upload->id }})"
                                                    wire:confirm="آیا از حذف این تصویر اطمینان دارید؟"
                                                    class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- Upload Form -->
                    @if($attempt->canUploadAnalysis())
                        @if(session()->has('success'))
                            <div
                                class="mb-4 p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-600 dark:text-green-400">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="space-y-4">
                            <div class="relative border-2 border-dashed border-border rounded-xl p-8 text-center hover:border-primary/50 transition-colors">
                                <div wire:loading.flex wire:target="analysisFiles,uploadAnalysis"
                                     class="absolute inset-0 z-10 hidden items-center justify-center rounded-xl bg-background/80 backdrop-blur-sm">
                                    <div class="flex flex-col items-center gap-3 rounded-2xl border border-border bg-secondary px-5 py-4 shadow-xl">
                                        <span class="h-8 w-8 rounded-full border-2 border-primary/20 border-t-primary animate-spin"></span>
                                        <span class="text-sm font-semibold text-foreground" wire:loading.remove wire:target="analysisFiles">در حال آپلود تصویر و تبدیل به webp...</span>
                                        <span class="text-sm font-semibold text-foreground" wire:loading wire:target="analysisFiles">در حال آماده‌سازی تصویر...</span>
                                    </div>
                                </div>
                                <input type="file" wire:model="analysisFiles" multiple accept="image/*" class="hidden"
                                       id="analysisUpload">
                                <label for="analysisUpload" class="cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-muted mb-4"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-foreground font-semibold mb-1">برای آپلود تصاویر تحلیل کلیک کنید</p>
                                    <p class="text-sm text-muted">حداکثر حجم هر فایل: ۲۰ مگابایت | فرمت‌های مجاز:
                                        تصویر</p>
                                </label>
                            </div>
                            @error('analysisFiles.*')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                            @error('analysisFiles')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                            <!-- Preview -->
                            @if(count($uploadedPreviews) > 0)
                                <br>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    @foreach($uploadedPreviews as $index => $preview)
                                        <div class="relative group">
                                            <img src="{{ $preview }}" alt="Preview"
                                                 class="w-full h-25 object-cover rounded-xl border border-border">
                                            <button wire:click="removePreview({{ $index }})"
                                                    class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <br>
                                <button wire:click="uploadAnalysis"
                                        wire:loading.attr="disabled"
                                        class="w-full py-3 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold transition-colors flex items-center justify-center gap-2">
                                    <svg wire:loading wire:target="uploadAnalysis" class="animate-spin w-5 h-5"
                                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>

                                    </svg>
                                    <span wire:loading.remove wire:target="uploadAnalysis">آپلود تحلیل</span>
                                    <span wire:loading wire:target="uploadAnalysis">در حال آپلود...</span>
                                </button>
                            @endif
                        </div>
                    @elseif($attempt->analysis_status === 'approved')
                        <p class="text-muted text-center py-4">تحلیل شما تایید شده است.</p>
                    @elseif($attempt->analysis_status === 'pending')
                        <p class="text-muted text-center py-4">تحلیل شما در حال بررسی است.</p>
                    @endif
                </div>
            @else
                <div class="bg-secondary border border-border rounded-2xl p-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-yellow-500 mb-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="font-bold text-xl text-foreground mb-2">کارنامه در دسترس نیست</h3>
                    <p class="text-muted">کارنامه پس از پایان زمان آزمون قابل مشاهده خواهد بود.</p>
                </div>
            @endif
        @else
            <!-- Answer Sheet View -->
            @if($canViewAnswerKey && $questionsWithAnswers)
                <!-- Filter -->
                <div class="flex items-center justify-end gap-4 mt-5 mb-5">
                    <label class="text-sm text-muted">فیلتر:</label>
                    <div class="w-full max-w-[240px]">
                        <x-ui.select wire:model.live="answerFilter"
                                     :options="[
                                        ['value' => 'all', 'label' => 'همه سوالات'],
                                        ['value' => 'correct', 'label' => 'سوالات صحیح'],
                                        ['value' => 'wrong', 'label' => 'سوالات غلط'],
                                        ['value' => 'unanswered', 'label' => 'سوالات بدون پاسخ'],
                                     ]"
                                     value-key="value"
                                     label-key="label"
                                     placeholder="همه سوالات"/>
                    </div>
                </div>
                <!-- Questions List -->
                @if($questionsWithAnswers->count() > 0)
                    <div class="space-y-6">
                        @foreach($questionsWithAnswers as $index => $qa)
                        @php
                            $question = $qa['question'];
                            $selectedOption = $qa['selected_option'];
                            $selectedPosition = $qa['selected_position'];
                            $isCorrect = $qa['is_correct'];
                            $correctOptionNum = $qa['correct_option_number'];
                            $correctPosition = $qa['correct_position'];
                            $displayOptions = $qa['ordered_options']->isNotEmpty()
                                ? $qa['ordered_options']
                                : collect([1, 2, 3, 4])->map(fn ($num) => (object) ['option_number' => $num, 'is_correct' => (int) $correctOptionNum === $num, 'content' => null]);
                        @endphp
                        <div class="bg-secondary border-2 rounded-2xl overflow-hidden mb-5
                                    {{ $isCorrect === true ? 'border-green-500/50' : '' }}
                                    {{ $isCorrect === false ? 'border-red-500/50' : '' }}
                                    {{ $isCorrect === null ? 'border-gray-500/50' : '' }}">
                            <!-- Question Header -->
                            <div class="relative p-4
                                        {{ $isCorrect === true ? 'bg-green-500/10' : '' }}
                                        {{ $isCorrect === false ? 'bg-red-500/10' : '' }}
                                        {{ $isCorrect === null ? 'bg-gray-500/10' : '' }}">
                                <div class="absolute top-0 left-0 right-0 h-1
                                            {{ $isCorrect === true ? 'bg-green-500' : '' }}
                                            {{ $isCorrect === false ? 'bg-red-500' : '' }}
                                            {{ $isCorrect === null ? 'bg-gray-500' : '' }}"></div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full font-bold
                                                     {{ $isCorrect === true ? 'bg-green-500 text-white' : '' }}
                                                     {{ $isCorrect === false ? 'bg-red-500 text-white' : '' }}
                                                     {{ $isCorrect === null ? 'bg-gray-500 text-white' : '' }}">
                                            {{ $loop->iteration }}
                                        </span>
                                        @if($question->subject)
                                            <span class="text-sm text-muted">{{ $question->subject->name }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($isCorrect === true)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-500 text-white text-xs rounded-full font-semibold">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor"><path stroke-linecap="round"
                                                                                 stroke-linejoin="round"
                                                                                 stroke-width="2"
                                                                                 d="M5 13l4 4L19 7"/></svg>

                                                صحیح
                                            </span>
                                        @elseif($isCorrect === false)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-500 text-white text-xs rounded-full font-semibold">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor"><path stroke-linecap="round"
                                                                                 stroke-linejoin="round"
                                                                                 stroke-width="2"
                                                                                 d="M6 18L18 6M6 6l12 12"/></svg>
                                                غلط
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 bg-gray-500 text-white text-xs rounded-full font-semibold">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor"><path stroke-linecap="round"
                                                                                 stroke-linejoin="round"
                                                                                 stroke-width="2" d="M20 12H4"/></svg>
                                                بدون پاسخ
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Question Body -->
                            <div class="p-6 mb-5">
                                <div class="mb-5 grid gap-3 sm:grid-cols-2">
                                    <div class="rounded-xl border border-primary/20 bg-primary/5 p-3 text-sm">
                                        <span class="block text-xs text-muted mb-1">کلید شما</span>
                                        <span class="font-bold text-foreground">
                                            {{ $selectedPosition !== null ? 'گزینه ' . $selectedPosition : 'وجود ندارد' }}
                                        </span>
                                    </div>
                                    <div class="rounded-xl border border-green-500/20 bg-green-500/10 p-3 text-sm">
                                        <span class="block text-xs text-muted mb-1">کلید درست</span>
                                        <span class="font-bold text-green-600 dark:text-green-400">
                                            {{ $correctPosition !== null ? 'گزینه ' . $correctPosition : 'وجود ندارد' }}
                                        </span>
                                    </div>
                                </div>
                                <!-- Question Image or Text -->
                                @if($question->content?->question_image_url)
                                    <div class="question-image mb-5">
                                        <img
                                            src="{{ $question->content->question_image_url }}"
                                            alt="تصویر سوال {{ $loop->iteration }}"
                                            class="exam-question-img rounded-lg shadow-lg"
                                            loading="lazy">
                                    </div>
                                @else
                                    <div class="prose text-white prose-sm dark:prose-invert max-w-none mb-5" dir="rtl">
                                        {!! $question->content?->body !!}
                                    </div>
                                @endif
                                <!-- Options -->
                                @if($question->content?->question_image_url)
                                    <!-- Image-based question: show option numbers with status -->
                                    <div class="flex flex-wrap items-center gap-3 justify-center mt-6">
                                        @foreach($displayOptions as $optIndex => $option)
                                            @php
                                                $isSelected = $selectedOption === $option->option_number;
                                                $isCorrectOpt = (int) $correctOptionNum === (int) $option->option_number;
                                                $optionLabel = ['۱', '۲', '۳', '۴'][$optIndex] ?? ($optIndex + 1);
                                            @endphp
                                            <div class="flex flex-col items-center gap-1">
                                                <div class="w-14 h-14 rounded-full border-2 flex items-center justify-center font-bold text-xl
                                                            {{ $isCorrectOpt ? 'border-green-500 bg-green-500 text-white' : '' }}
                                                            {{ $isSelected && !$isCorrectOpt ? 'border-red-500 bg-red-500 text-white' : '' }}
                                                            {{ !$isCorrectOpt && !$isSelected ? 'border-border bg-background text-foreground' : '' }}">
                                                    {{ $optionLabel }}
                                                </div>
                                                <div class="flex items-center gap-1 text-xs">
                                                    @if($isSelected)
                                                        <span class="px-2 py-0.5 bg-primary/20 text-primary rounded-full font-semibold">انتخاب شما</span>
                                                    @endif
                                                    @if($isCorrectOpt)
                                                        <svg class="w-4 h-4 text-green-500" fill="none"
                                                             viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <!-- Text-based options (legacy support) -->
                                    <div class="space-y-2">
                                        @foreach($displayOptions as $optIndex => $option)
                                            @php
                                                $isSelected = $selectedOption === $option->option_number;
                                                $isCorrectOpt = (int) $correctOptionNum === (int) $option->option_number;
                                                $optionLabel = ['الف', 'ب', 'ج', 'د'][$optIndex] ?? ($optIndex + 1);
                                            @endphp
                                            <div class="flex items-start gap-3 p-3 rounded-xl
                                                        {{ $isCorrectOpt ? 'bg-green-500/10 border border-green-500' : '' }}
                                                        {{ $isSelected && !$isCorrectOpt ? 'bg-red-500/10 border border-red-500/30' : '' }}
                                                        {{ !$isCorrectOpt && !$isSelected ? 'bg-background border border-border' : '' }}">
                                                <span class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm
                                                             {{ $isCorrectOpt ? 'bg-green-500 text-white' : '' }}
                                                             {{ $isSelected && !$isCorrectOpt ? 'bg-red-500 text-white' : '' }}
                                                             {{ !$isCorrectOpt && !$isSelected ? 'bg-secondary text-foreground' : '' }}">
                                                    {{ $optionLabel }}
                                                </span>
                                                <div class="flex-1 prose prose-sm dark:prose-invert text-muted mb-2">
                                                    @if($option->content)
                                                        {!! $option->content !!}
                                                    @else
                                                        <span class="text-sm text-muted">گزینه {{ $option->option_number }}</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    @if($isSelected)
                                                        <span class="text-xs px-2 py-1 bg-primary/20 text-primary rounded-full font-semibold">انتخاب شما</span>
                                                    @endif
                                                    @if($isCorrectOpt)
                                                        <svg class="w-5 h-5 text-green-500" fill="none"
                                                             viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Explanation -->
                                @if($question->content?->explanation_image_url)
                                    <div class="mt-6 p-4 bg-blue-500/10 border border-blue-500/20 rounded-xl">
                                        <h4 class="font-bold text-foreground mb-3 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500"
                                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                            پاسخ تشریحی
                                        </h4>
                                        <img
                                            src="{{ $question->content->explanation_image_url }}"
                                            alt="پاسخ تشریحی سوال {{ $loop->iteration }}"
                                            class="exam-question-img rounded-lg mt-2"
                                            loading="lazy">
                                    </div>
                                @elseif($question->content?->explanation)
                                    <div class="mt-6 p-4 bg-blue-500/10 border border-blue-500/20 rounded-xl">
                                        <h4 class="font-bold text-foreground mb-2 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>

                                            </svg>
                                            پاسخ تشریحی
                                        </h4>
                                        <div class="prose prose-sm dark:prose-invert text-primary">
                                            {!! $question->content->explanation !!}
                                        </div>
                                    </div>
                                @else
                                    <div
                                        class="mt-6 p-4 bg-gray-500/10 border border-gray-500/20 rounded-xl text-center">
                                        <p class="text-muted text-sm">برای این سوال توضیح تشریحی ثبت نشده است.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-secondary border border-border rounded-2xl p-12 text-center">
                        <p class="text-muted text-sm">وجود ندارد</p>
                    </div>
                @endif
            @else
                <div class="bg-secondary border border-border rounded-2xl p-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-yellow-500 mb-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="font-bold text-xl text-foreground mb-2">پاسخنامه در دسترس نیست</h3>
                    <p class="text-muted">پاسخنامه پس از پایان زمان آزمون قابل مشاهده خواهد بود.</p>
                </div>
            @endif
        @endif
    </div>


    @script
    <script>
        // نکته: Chart.js همین الان توسط layouts.client.link به‌صورت سراسری (و همگام) لود شده
        // (همون که در سایدبار/سایر بخش‌ها هم استفاده می‌شود)، پس نباید دوباره از CDN لودش کنیم؛
        // لود مجدد یک نسخه‌ی دیگر از این کتابخانه، window.Chart را با نسخه‌ی متفاوتی جایگزین
        // می‌کند و می‌تواند نمودارهای دیگر صفحه (مثلاً در سایدبار) را خراب کند.
        // با این‌حال چون این بلوک ممکن است زودتر از اجرای اسکریپت لایه اجرا شود، کوتاه صبر می‌کنیم.
        (function initTypedExamResultDonutChart() {
            if (typeof Chart === 'undefined') {
                setTimeout(initTypedExamResultDonutChart, 100);
                return;
            }

            const ctx = document.getElementById('donutChart');

            if (!ctx) {
                return;
            }

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['صحیح', 'غلط', 'بدون پاسخ'],
                    datasets: [{
                        data: [{{ $stats['correct'] }}, {{ $stats['wrong'] }}, {{ $stats['unanswered'] }}],
                        backgroundColor: ['#22c55e', '#ef4444', '#9ca3af'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        })();
    </script>
    @endscript

    @assets
    <style>
        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
        }

        /* تصویر سوال — عرض کامل، ارتفاع متناسب، وضوح بالا در همه دستگاه‌ها */
        .exam-question-img {
            display: block;
            width: 100%;
            height: auto;
            max-width: 100%;
            object-fit: contain;
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
    </style>
    @endassets

</div>
