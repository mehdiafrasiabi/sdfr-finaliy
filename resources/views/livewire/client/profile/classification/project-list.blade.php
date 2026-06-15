<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

            {{-- ═══ سایدبار ═══ --}}
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            {{-- ═══ محتوای اصلی ═══ --}}
            <div class="lg:col-span-9 md:col-span-8" dir="rtl">
                <div class="space-y-10">

                    {{-- سربرگ --}}
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">طبقه‌بندی دروس</div>
                    </div>

                    {{-- ══════════════════════════════════════
                         بخش طبقه‌بندی آزمایشی
                       ══════════════════════════════════════ --}}
                    @if($isTrialUser && $trialProject)
                        <section class="space-y-4">
                            <div class="flex items-center gap-2">
                                <span class="w-1 h-5 rounded-full bg-blue-500"></span>
                                <h2 class="font-bold text-foreground text-sm">طبقه‌بندی آزمایشی</h2>
                            </div>

                            @include('livewire.client.profile.classification._project-card', [
                                'project'   => $trialProject,
                                'mode'      => 'trial',
                                'submitted' => $trialSubmitted,
                                'wireAction'=> 'goTrial',
                            ])
                        </section>
                    @endif

                    {{-- ══════════════════════════════════════
                         پروژه‌های فعال
                       ══════════════════════════════════════ --}}
                    @if($activeProjects->count() > 0)
                        <section class="space-y-4">
                            <div class="flex items-center gap-2">
                                <span class="w-1 h-5 rounded-full bg-emerald-500"></span>
                                <h2 class="font-bold text-foreground text-sm">پروژه‌های فعال</h2>
                                <span class="text-xs font-bold text-emerald-500 bg-emerald-500/10 border border-emerald-500/30 px-2 py-0.5 rounded-full">
                                    {{ $activeProjects->count() }}
                                </span>
                            </div>

                            <div class="space-y-4">
                                @foreach($activeProjects as $project)
                                    @include('livewire.client.profile.classification._project-card', [
                                        'project'    => $project,
                                        'mode'       => 'active',
                                        'submitted'  => isset($submissions[$project->id]) && $submissions[$project->id],
                                        'wireAction' => "selectProject({$project->id})",
                                    ])
                                @endforeach
                            </div>
                        </section>
                    @endif

                    {{-- ══════════════════════════════════════
                         در انتظار شروع
                       ══════════════════════════════════════ --}}
                    @if($upcomingProjects->count() > 0)
                        <section class="space-y-4">
                            <div class="flex items-center gap-2">
                                <span class="w-1 h-5 rounded-full bg-amber-500"></span>
                                <h2 class="font-bold text-foreground text-sm">در انتظار شروع</h2>
                                <span class="text-xs font-bold text-amber-500 bg-amber-500/10 border border-amber-500/30 px-2 py-0.5 rounded-full">
                                    {{ $upcomingProjects->count() }}
                                </span>
                            </div>

                            <div class="space-y-4">
                                @foreach($upcomingProjects as $project)
                                    @include('livewire.client.profile.classification._project-card', [
                                        'project'    => $project,
                                        'mode'       => 'upcoming',
                                        'submitted'  => false,
                                        'wireAction' => null,
                                    ])
                                @endforeach
                            </div>
                        </section>
                    @endif

                    {{-- ══════════════════════════════════════
                         تمام شده / غیرفعال
                       ══════════════════════════════════════ --}}
                    @if($endedProjects->count() > 0)
                        <section class="space-y-4">
                            <div class="flex items-center gap-2">
                                <span class="w-1 h-5 rounded-full bg-foreground/30"></span>
                                <h2 class="font-bold text-foreground text-sm">تمام شده / غیرفعال</h2>
                                <span class="text-xs font-bold text-muted bg-secondary border border-border px-2 py-0.5 rounded-full">
                                    {{ $endedProjects->count() }}
                                </span>
                            </div>

                            <div class="space-y-4">
                                @foreach($endedProjects as $project)
                                    @include('livewire.client.profile.classification._project-card', [
                                        'project'    => $project,
                                        'mode'       => 'ended',
                                        'submitted'  => isset($submissions[$project->id]) && $submissions[$project->id],
                                        'wireAction' => null,
                                    ])
                                @endforeach
                            </div>
                        </section>
                    @endif

                    {{-- حالت خالی --}}
                    @if($activeProjects->count() === 0 && $upcomingProjects->count() === 0 && $endedProjects->count() === 0 && !($isTrialUser && $trialProject))
                        <div class="flex flex-col items-center justify-center py-20 space-y-5">
                            <img src="/client/svg/empty2.svg"
                                 class="w-full max-w-[370px] md:max-w-xs opacity-35 mb-4 md:mb-6"
                                 alt="پیامی وجود ندارد"/>
                            <div class="text-center space-y-1.5">
                                <h2 class="font-bold text-lg text-foreground">پروژه‌ای در دسترس نیست</h2>
                                <p class="text-sm text-muted">در حال حاضر پروژه طبقه‌بندی فعالی وجود ندارد.</p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- لودینگ --}}
    <div wire:loading.flex class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="flex items-center gap-3 rounded-2xl border border-border bg-secondary px-6 py-4 shadow-2xl">
            <div class="h-6 w-6 animate-spin rounded-full border-2 border-primary/30 border-t-primary"></div>
            <span class="text-sm font-semibold text-foreground">در حال بارگذاری...</span>
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
                    if (type === 'upcoming' || type === 'active') {
                        setTimeout(() => window.location.reload(), 1200);
                    }
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
