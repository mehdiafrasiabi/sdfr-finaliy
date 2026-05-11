<div class="max-w-7xl space-y-8 px-4 mx-auto">
    {{-- Update Notification Overlay --}}
    <livewire:client.profile.update-notification />

    <div>
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <!-- Sidebar -->
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-6">



                    <!-- Section Title -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground text-lg">پیشخوان</div>
                    </div>

                    {{-- بنر هفته آزمایشی: فقط برای کاربران غیر دانش‌آموز یا دانش‌آموزان آزمایشی --}}
                    @php
                        $trialWeek = \App\Models\TrialWeek::where('user_id', $user->id)->latest()->first();
                        $isTrialStudent = $student && $student->is_trial;
                        $showTrialBanner = !$student || $isTrialStudent;
                    @endphp

                    @if($showTrialBanner && !$trialWeek)
                    {{-- دکمه شروع آزمایشی --}}
                    <div class="relative overflow-hidden bg-gradient-to-l from-emerald-500/10 via-teal-500/5 to-transparent border border-emerald-500/30 rounded-2xl p-6">
                        <div class="absolute inset-0 bg-gradient-to-l from-emerald-500/5 to-transparent pointer-events-none"></div>
                        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-4">
                            <div>
                                <h3 class="font-black text-foreground text-lg mb-1 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    یک هفته آزمایشی رایگان
                                </h3>
                                <p class="text-sm text-muted leading-relaxed max-w-lg">
                                    با شروع دوره آزمایشی، طبقه‌بندی دروس، برنامه مطالعاتی شخصی و پشتیبان اختصاصی دریافت کنید.
                                </p>
                                <div class="flex items-center gap-4 mt-3">
                                    <div class="flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        طبقه‌بندی دروس
                                    </div>
                                    <div class="flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        برنامه شخصی‌سازی شده
                                    </div>
                                    <div class="flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        پشتیبان اختصاصی
                                    </div>
                                </div>
                            </div>
                            <livewire:client.profile.trial-week.start />
                        </div>
                    </div>
                    @elseif($trialWeek)
                    {{-- نمایش وضعیت آزمایشی --}}
                    <a wire:navigate href="{{ route('client.profile.trial.guide') }}"
                       class="flex items-center justify-between p-5 bg-gradient-to-l from-emerald-500/10 to-teal-500/5 border border-emerald-500/30 rounded-2xl hover:-translate-y-0.5 hover:shadow-md transition-all duration-200 group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-foreground">هفته آزمایشی — {{ $trialWeek->statusLabel }}</div>
                                <div class="text-xs text-muted mt-0.5">
                                    @if($trialWeek->isExpired())
                                        <span class="text-red-500">دوره آزمایشی منقضی شده است</span>
                                    @else
                                        {{ $trialWeek->daysRemaining }} روز باقی‌مانده • برای مشاهده راهنمای مراحل کلیک کنید
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            @php
                                $stepPercent = ($trialWeek->step / 4) * 100;
                            @endphp
                            <div class="hidden md:flex items-center gap-2">
                                <div class="w-24 h-2 bg-border rounded-full overflow-hidden">
                                    <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $stepPercent }}%"></div>
                                </div>
                                <span class="text-xs text-muted">{{ (int)$stepPercent }}%</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-muted group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </div>
                    </a>
                    @endif

                    <!-- Notification Alert -->
                    @if($student && !$isTrialStudent && $unreadNotificationsCount > 0)
                        <a wire:navigate
                           href="{{ route('client.profile.notification') }}"
                           class="flex items-center justify-between p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/40 rounded-2xl hover:-translate-y-0.5 hover:shadow-md transition-all duration-200 group">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 bg-emerald-100 dark:bg-emerald-800/40 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-emerald-600 dark:text-emerald-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-bold text-sm text-foreground flex items-center gap-2">
                                        مشاهده پیام‌ها
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 group-hover:-translate-x-1 transition-transform">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                                        </svg>
                                    </div>
                                    <div class="text-xs text-muted mt-0.5">
                                        شما <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $unreadNotificationsCount }}</span> پیام خوانده نشده دارید
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endif
                    <!-- Support & Advisor Cards -->
                    <div class="grid md:grid-cols-2 grid-cols-1 gap-5">

                        <!-- پشتیبان من -->
                        <div class="relative overflow-hidden bg-secondary border border-primary/20 rounded-2xl p-6 text-center hover:-translate-y-1 hover:shadow-lg hover:shadow-primary/10 transition-all duration-300">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-primary/10 pointer-events-none"></div>
                            <div class="relative z-10">
                                <h3 class="font-bold text-base text-foreground mb-4 flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-primary">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    پشتیبان من
                                </h3>
                                @if($supporterStudent && $supporterStudent->picture)
                                    <img src="{{ asset('adminsFile/' . $supporterStudent->id . '/' . $supporterStudent->picture) }}"
                                         alt="{{ $supporterStudent->name }}"
                                         class="w-16 h-16 rounded-full object-cover mx-auto mb-3 ring-2 ring-primary/30 shadow-lg">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center mx-auto mb-3 shadow-lg shadow-primary/30">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-primary-foreground">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                        </svg>
                                    </div>
                                @endif
                                @if($supporterStudent)
                                    <span class="font-bold text-base text-foreground">{{ $supporterStudent->name }}</span>
                                @else
                                    <span class="text-sm text-muted">تعیین نشده است</span>
                                @endif
                            </div>
                        </div>

                        <!-- مشاور من -->
                        <div class="relative overflow-hidden bg-secondary border border-primary/20 rounded-2xl p-6 text-center hover:-translate-y-1 hover:shadow-lg hover:shadow-primary/10 transition-all duration-300">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-primary/10 pointer-events-none"></div>
                            <div class="relative z-10">
                                <h3 class="font-bold text-base text-foreground mb-4 flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-primary">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                                    </svg>
                                    مشاور من
                                </h3>
                                @if($advisorStudent && $advisorStudent->picture)
                                    <img src="{{ asset('adminsFile/' . $advisorStudent->id . '/' . $advisorStudent->picture) }}"
                                         alt="{{ $advisorStudent->name }}"
                                         class="w-16 h-16 rounded-full object-cover mx-auto mb-3 ring-2 ring-primary/30 shadow-lg">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center mx-auto mb-3 shadow-lg shadow-primary/30">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-8 h-8 text-primary-foreground">
                                            <path fill-rule="evenodd" d="M9.664 1.319a.75.75 0 01.672 0 41.059 41.059 0 018.198 5.424.75.75 0 01-.254 1.285 31.372 31.372 0 00-7.86 3.83.75.75 0 01-.84 0 31.508 31.508 0 00-2.08-1.287V9.394c0-.244.116-.463.302-.592a35.504 35.504 0 013.305-2.033.75.75 0 00-.714-1.319 37 37 0 00-3.446 2.12A2.216 2.216 0 006 9.393v.38a31.293 31.293 0 00-4.28-1.746.75.75 0 01-.254-1.285 41.059 41.059 0 018.198-5.424zM6 11.459a29.848 29.848 0 00-2.455-1.158 41.029 41.029 0 00-.39 3.114.75.75 0 00.419.74c.528.256 1.046.53 1.554.82-.21.324-.455.63-.739.914a.75.75 0 101.06 1.06c.37-.369.69-.77.96-1.193a26.61 26.61 0 013.095 2.348.75.75 0 00.992 0 26.547 26.547 0 015.93-3.95.75.75 0 00.42-.739 41.053 41.053 0 00-.39-3.114 29.925 29.925 0 00-5.199 2.801 2.25 2.25 0 01-2.514 0c-.41-.275-.826-.541-1.25-.797a6.985 6.985 0 01-1.084 3.45 26.503 26.503 0 00-1.281-.78A5.487 5.487 0 006 12v-.54z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                @endif
                                @if($advisorStudent)
                                    <span class="font-bold text-base text-foreground">{{ $advisorStudent->name }}</span>
                                @else
                                    <span class="text-sm text-muted">تعیین نشده است</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- TO DO Section -->
                    <div class="bg-secondary border border-border rounded-2xl p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-primary">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-lg text-foreground">TO DO</h3>
                        </div>

                        <div class="grid sm:grid-cols-2 grid-cols-1 gap-5">

                            <!-- ساعت مطالعه -->
                            <div class="bg-background border border-border rounded-xl p-5 hover:-translate-y-0.5 hover:shadow-md transition-all duration-200">
                                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-primary">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                </div>
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-bold text-foreground">ساعت مطالعه</h4>
                                    <span class="font-bold text-lg text-primary">{{ $studyHoursProgress['total_hours'] }} ساعت</span>
                                </div>
                                <div class="space-y-3">
                                    <div class="w-full h-3 bg-border rounded-full overflow-hidden">
                                        <div class="h-full flex">
                                            <div class="h-full bg-primary rounded-full transition-all duration-700" style="width: {{ $studyHoursProgress['percentage'] }}%"></div>
                                            @if($studyHoursProgress['extra_percentage'] > 0)
                                                <div class="h-full bg-emerald-500 rounded-r-full transition-all duration-700" style="width: {{ min($studyHoursProgress['extra_percentage'], 50) }}%"></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-muted">{{ $studyHoursProgress['completed_hours'] }} از {{ $studyHoursProgress['total_hours'] }} ساعت</span>
                                        <span class="font-bold text-primary">{{ round($studyHoursProgress['percentage']) }}%</span>
                                    </div>
                                    @if($studyHoursProgress['extra_hours'] > 0)
                                        <div class="flex items-center gap-2 text-xs font-semibold px-3 py-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" />
                                            </svg>
                                            {{ $studyHoursProgress['extra_hours'] }} ساعت مطالعه اضافی! عالی هستی 🎉
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- ارسال گزارش -->
                            <div class="bg-background border border-border rounded-xl p-5 hover:-translate-y-0.5 hover:shadow-md transition-all duration-200">
                                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-primary">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-bold text-foreground">ارسال گزارش</h4>
                                    <span class="font-bold text-lg text-primary">{{ $reportProgress['submitted_days'] }}/{{ $reportProgress['total_days'] }}</span>
                                </div>
                                <div class="space-y-3">
                                    <div class="w-full h-3 bg-border rounded-full overflow-hidden">
                                        <div class="h-full bg-primary rounded-full transition-all duration-700" style="width: {{ $reportProgress['percentage'] }}%"></div>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-muted">{{ $reportProgress['submitted_days'] }} روز ثبت شده</span>
                                        <span class="font-bold text-primary">{{ round($reportProgress['percentage']) }}%</span>
                                    </div>
                                    <div class="grid grid-cols-7 gap-1.5 mt-1">
                                        @for($i = 0; $i < 7; $i++)
                                            <div class="w-full aspect-square rounded-full flex items-center justify-center text-xs font-bold
                                                {{ $i < $reportProgress['submitted_days']
                                                    ? 'bg-primary text-primary-foreground shadow-sm shadow-primary/30'
                                                    : 'bg-border text-muted/50 border-2 border-dashed border-border' }}">
                                                {{ $i + 1 }}
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Today's Program -->
                    <div class="bg-secondary border border-border rounded-2xl p-6">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-primary">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-lg text-foreground">برنامه امروز من</h3>
                        </div>

                        @if(count($todayProgram) > 0)
                            <div class="space-y-3 max-h-96 overflow-y-auto pl-1">
                                @foreach($todayProgram as $index => $part)
                                    <div class="flex items-start justify-between gap-4 bg-background border border-border rounded-xl p-4 hover:border-primary/30 hover:bg-primary/5 hover:-translate-x-1 transition-all duration-200">
                                        <div class="flex items-start gap-3 flex-1">
                                            <div class="flex items-center justify-center w-8 h-8 bg-primary/10 rounded-lg flex-shrink-0 font-bold text-sm text-primary">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="flex-1 space-y-2">
                                                <h4 class="font-bold text-foreground text-sm">{{ $part->lesson->name ?? 'درس' }}</h4>
                                                <div class="flex flex-wrap items-center gap-2">
                                                    @if($part->ccSubject)
                                                        <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-lg bg-primary/10 text-primary border border-primary/20">{{ $part->ccSubject->name }}</span>
                                                    @endif
                                                    @if($part->ccChapter)
                                                        <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-lg bg-primary/10 text-primary border border-primary/20">{{ $part->ccChapter->name }}</span>
                                                    @endif
                                                    <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-lg bg-primary/10 text-primary border border-primary/20">{{ $part->part_type_label }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end space-y-1 flex-shrink-0">
                                            <div class="flex items-center gap-1 text-sm font-bold text-foreground">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-primary">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $part->duration_hours }} ساعت
                                            </div>
                                            @if($part->test_count)
                                                <span class="text-xs text-muted">{{ $part->test_count }} تست</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-12 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20 h-20 text-muted/30 mb-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <p class="font-semibold text-muted">برنامه‌ای برای امروز تعریف نشده است</p>
                                <p class="text-sm text-muted/70 mt-1">منتظر برنامه جدید از مشاور خود باشید</p>
                            </div>
                        @endif
                    </div>

                    <!-- Class Schedule Section -->
                    @if($classSchedule)
                        <div class="bg-secondary border border-border rounded-2xl p-6">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-primary">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-lg text-foreground">برنامه کلاسی من</h3>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-xs rounded-full font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    نهایی شده
                                </span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                    <tr class="border-b border-border">
                                        <th class="text-right py-2.5 px-3 font-bold text-foreground" style="min-width: 80px;">روز</th>
                                        @foreach(['اول','دوم','سوم','چهارم','پنجم'] as $bellLabel)
                                            <th class="text-center py-2.5 px-3 font-bold text-foreground">زنگ {{ $bellLabel }}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @for($d = 0; $d < 7; $d++)
                                        <tr class="border-b border-border/50 {{ in_array($d, [5, 6]) ? 'opacity-50' : '' }}">
                                            <td class="py-2.5 px-3 font-bold text-foreground">
                                                {{ \App\Models\ClassSchedule::getDayName($d) }}
                                            </td>
                                            @for($p = 1; $p <= 5; $p++)
                                                @php
                                                    $cPart = $classSchedule->parts->where('day_of_week', $d)->where('part_order', $p)->first();
                                                @endphp
                                                <td class="text-center py-2.5 px-3">
                                                    @if($cPart)
                                                        <span class="inline-block px-2 py-1 rounded-lg text-xs font-semibold bg-primary/10 text-primary border border-primary/20">
                                                                {{ $cPart->lesson_name }}
                                                            </span>
                                                    @else
                                                        <span class="text-muted/40">-</span>
                                                    @endif
                                                </td>
                                            @endfor
                                        </tr>
                                    @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif



                </div>
            </div>
        </div>
    </div>
</div>
