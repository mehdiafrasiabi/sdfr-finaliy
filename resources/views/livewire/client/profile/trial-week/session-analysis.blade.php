<div class="max-w-7xl space-y-8 px-4 mx-auto">

    <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

        <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
            <livewire:client.profile.sidebar/>
        </div>

        <div class="lg:col-span-9 md:col-span-8 space-y-6">

            {{-- هدر --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1">
                    <div class="w-1 h-1 bg-foreground rounded-full"></div>
                    <div class="w-2 h-2 bg-foreground rounded-full"></div>
                </div>
                <div class="font-black text-foreground text-lg">تحلیل وضعیت مطالعاتی</div>
            </div>

            {{-- کارت خلاصه وضعیت --}}
            @if(!empty($analysis))
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/40 rounded-2xl p-5 text-center">
                    <div class="text-3xl font-black text-red-600 dark:text-red-400 mb-1">{{ $analysis['weak_count'] }}</div>
                    <div class="text-xs text-red-600/70 dark:text-red-400/70 font-semibold">مبحث ضعیف</div>
                    <div class="text-[10px] text-muted mt-1">نیاز به تمرکز بیشتر</div>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/40 rounded-2xl p-5 text-center">
                    <div class="text-3xl font-black text-amber-600 dark:text-amber-400 mb-1">{{ $analysis['medium_count'] }}</div>
                    <div class="text-xs text-amber-600/70 dark:text-amber-400/70 font-semibold">مبحث متوسط</div>
                    <div class="text-[10px] text-muted mt-1">قابل بهبود</div>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/40 rounded-2xl p-5 text-center">
                    <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mb-1">{{ $analysis['strong_count'] }}</div>
                    <div class="text-xs text-emerald-600/70 dark:text-emerald-400/70 font-semibold">مبحث قوی</div>
                    <div class="text-[10px] text-muted mt-1">پایه خوب</div>
                </div>
            </div>

            {{-- نمودار توزیع رتبه‌ها --}}
            <div class="bg-secondary border border-border rounded-2xl p-6">
                <h3 class="font-black text-foreground mb-5 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    توزیع سطح مباحث
                </h3>

                <div class="space-y-3">
                    @php
                        $ratingColors = [
                            'D'  => 'bg-red-500',    'D+' => 'bg-red-400',
                            'C'  => 'bg-orange-500', 'C+' => 'bg-orange-400',
                            'B'  => 'bg-amber-500',  'B+' => 'bg-amber-400',
                            'A'  => 'bg-emerald-500','A+' => 'bg-emerald-400',
                        ];
                        $total = max(1, $analysis['total']);
                    @endphp

                    @foreach($analysis['rating_counts'] as $label => $count)
                        @if($count > 0)
                        <div class="flex items-center gap-3">
                            <span class="w-6 text-xs font-black text-foreground text-right">{{ $label }}</span>
                            <div class="flex-1 bg-border rounded-full h-6 overflow-hidden">
                                <div class="{{ $ratingColors[$label] ?? 'bg-primary' }} h-full rounded-full flex items-center justify-end pr-2 transition-all duration-700"
                                     style="width: {{ max(5, ($count / $total) * 100) }}%">
                                    <span class="text-white text-[10px] font-bold">{{ $count }}</span>
                                </div>
                            </div>
                            <span class="w-8 text-xs text-muted text-left">{{ round(($count / $total) * 100) }}%</span>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- وضعیت هر درس --}}
            @if(!empty($analysis['subject_averages']))
            <div class="bg-secondary border border-border rounded-2xl p-6">
                <h3 class="font-black text-foreground mb-5 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    وضعیت هر درس
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($analysis['subject_averages'] as $subjectName => $data)
                    <div class="flex items-center justify-between p-4 bg-background border border-border rounded-xl">
                        <span class="font-semibold text-sm text-foreground">{{ $subjectName }}</span>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-muted">{{ $data['count'] }} مبحث</span>
                            <span class="px-3 py-1 rounded-full text-xs font-black
                                @if($data['average'] >= 6) bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400
                                @elseif($data['average'] >= 4) bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400
                                @else bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400
                                @endif">
                                {{ $data['label'] }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif


            @endif {{-- end if analysis --}}

            {{-- ═══════════ کارنامه تحلیلی پیش از ساخت برنامه ═══════════ --}}
            <div class="bg-secondary border border-border rounded-2xl p-6">
                <h3 class="font-black text-foreground mb-1 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
                    </svg>
                    کارنامه‌ی تحلیلی تو
                </h3>
                <p class="text-xs text-muted leading-6 mb-5">خلاصه‌ی همه‌ی کارهایی که انجام دادی — برنامه‌ات دقیقاً بر اساس همین اطلاعات ساخته می‌شود.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- طبقه‌بندی --}}
                    <div class="bg-background border border-border rounded-xl p-4">
                        <div class="text-xs font-bold text-foreground mb-2 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                            طبقه‌بندی دروس
                        </div>
                        @if(!empty($analysis) && $analysis['total'] > 0)
                            <div class="text-[11px] text-muted leading-6">
                                {{ $analysis['total'] }} مبحث ارزیابی شد:
                                <span class="text-red-500 font-bold">{{ $analysis['weak_count'] }} ضعیف</span> ·
                                <span class="text-amber-500 font-bold">{{ $analysis['medium_count'] }} متوسط</span> ·
                                <span class="text-emerald-500 font-bold">{{ $analysis['strong_count'] }} قوی</span>
                            </div>
                        @else
                            <div class="text-[11px] text-muted">داده‌ای ثبت نشده است.</div>
                        @endif
                    </div>

                    {{-- پیش‌جلسه --}}
                    <div class="bg-background border border-border rounded-xl p-4">
                        <div class="text-xs font-bold text-foreground mb-2 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                            پیش‌جلسه
                        </div>
                        <div class="text-[11px] text-muted leading-6">
                            @if($preSessionSummary['exams'] > 0) {{ $preSessionSummary['exams'] }} امتحان · @endif
                            @if($preSessionSummary['qas'] > 0) {{ $preSessionSummary['qas'] }} پرسش‌وپاسخ · @endif
                            @if($preSessionSummary['assignments'] > 0) {{ $preSessionSummary['assignments'] }} تکلیف · @endif
                            @if($preSessionSummary['requested'] > 0) {{ $preSessionSummary['requested'] }} پارت درخواستی @endif
                            @if($preSessionSummary['misc']) · توضیحات متفرقه ثبت شد @endif
                            @if($preSessionSummary['exams'] + $preSessionSummary['qas'] + $preSessionSummary['assignments'] + $preSessionSummary['requested'] === 0 && !$preSessionSummary['misc'])
                                موردی ثبت نشده است.
                            @endif
                        </div>
                    </div>

                    {{-- آزمون‌های شخصیت‌شناسی --}}
                    <div class="bg-background border border-border rounded-xl p-4">
                        <div class="text-xs font-bold text-foreground mb-2 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M6 21v-1a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v1"/></svg>
                            تست مایندست و شخصیت
                        </div>
                        <div class="text-[11px] text-muted leading-6">
                            @if($personality)
                                @if(!empty($personality['mbti']['type']))
                                    تیپ شخصیتی: <span class="font-black text-primary tracking-wider">{{ $personality['mbti']['type'] }}</span>
                                @endif
                                @if(!empty($personality['vark']['profile']))
                                    · سبک یادگیری: <span class="font-bold text-foreground">{{ $personality['vark']['profile'] }}</span>
                                @endif
                                @if(!empty($personality['custom']))
                                    · {{ count($personality['custom']) }} تست اختصاصی تحلیل شد
                                @endif
                            @else
                                تحلیلی در دسترس نیست.
                            @endif
                        </div>
                    </div>

                    {{-- برنامه کلاسی --}}
                    <div class="bg-background border border-border rounded-xl p-4">
                        <div class="text-xs font-bold text-foreground mb-2 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            برنامه کلاسی مدرسه
                        </div>
                        <div class="text-[11px] text-muted leading-6">
                            @if(!$this->needsSchedule)
                                نیازی نبود — {{ $trialWeek?->isGraduate() ? 'فارغ‌التحصیل هستی' : 'فعلاً مدرسه نمی‌روی' }}.
                            @elseif($this->classScheduleFinalized)
                                <span class="text-emerald-500 font-bold">ثبت و نهایی شد ✓</span>
                            @else
                                هنوز نهایی نشده است.
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- دکمه ساخت برنامه --}}
            @if($trialWeek && $trialWeek->status === \App\Models\TrialWeek::STATUS_PRE_SESSION_DONE)
            <div class="bg-gradient-to-l from-primary/10 to-primary/5 border border-primary/30 rounded-2xl p-8 text-center">
                <div class="text-5xl mb-4">📅</div>
                <h3 class="font-black text-foreground text-xl mb-3">آماده ساخت برنامه</h3>
                <p class="text-muted text-sm mb-6 leading-relaxed">
                    براساس تمام اطلاعات ثبت شده در سیستم مشاور شما برنامه اختصاصی برای شما آماده میکند!
                </p>
                <button wire:click="openHoursModal"
                        class="inline-flex items-center gap-3 px-8 py-4 bg-primary hover:bg-primary/90 text-primary-foreground rounded-2xl font-black text-base shadow-lg shadow-primary/30 hover:-translate-y-0.5 transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    ساخت برنامه
                </button>
            </div>
            @elseif($trialWeek && $trialWeek->status === \App\Models\TrialWeek::STATUS_PROGRAM_BUILT)
            <div class="bg-gradient-to-l from-emerald-500/10 to-teal-500/10 border border-emerald-500/30 rounded-2xl p-6 text-center">
                <div class="text-4xl mb-3">✅</div>
                <h3 class="font-black text-foreground text-lg mb-2">برنامه ساخته شده است</h3>
                <a wire:navigate href="{{ route('client.profile.consultation.sessions') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl font-bold mt-3 transition-colors">
                    مشاهده جلسات و برنامه
                </a>
            </div>
            @endif

        </div>
    </div>

    {{-- مودال ساعت مطالعه روزانه --}}
    @if($showHoursModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         @keydown.escape.window="$wire.closeHoursModal()">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeHoursModal"></div>
        <div class="relative z-10 w-full max-w-sm bg-background border border-border rounded-3xl shadow-2xl p-8 text-center"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="text-4xl mb-4">⏱️</div>
            <h2 class="text-xl font-black text-foreground mb-2">برنامه روزانه</h2>
            <p class="text-muted text-sm mb-6">در روز چند ساعت می‌توانید مطالعه کنید؟</p>

            <div class="flex items-center justify-center gap-4 mb-6">
                <button wire:click="$set('dailyStudyHours', {{ max(1, $dailyStudyHours - 1) }})"
                        class="w-10 h-10 rounded-full bg-secondary border border-border text-foreground font-black text-lg hover:bg-border transition-colors">
                    −
                </button>
                <div class="text-4xl font-black text-primary min-w-[60px]">
                    {{ $dailyStudyHours }}
                </div>
                <button wire:click="$set('dailyStudyHours', {{ min(14, $dailyStudyHours + 1) }})"
                        class="w-10 h-10 rounded-full bg-secondary border border-border text-foreground font-black text-lg hover:bg-border transition-colors">
                    +
                </button>
            </div>
            <p class="text-xs text-muted mb-6">ساعت در روز</p>

            @error('dailyStudyHours') <p class="text-sm text-red-500 mb-4">{{ $message }}</p> @enderror

            <div class="flex gap-3">
                <button wire:click="buildProgram"
                        wire:loading.attr="disabled"
                        class="flex-1 py-3 bg-primary hover:bg-primary/90 disabled:opacity-50 text-primary-foreground rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                    <svg wire:loading wire:target="buildProgram" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="buildProgram">بسازید!</span>
                    <span wire:loading wire:target="buildProgram">در حال ساخت...</span>
                </button>
                <button wire:click="closeHoursModal"
                        class="flex-1 py-3 bg-secondary hover:bg-border text-foreground rounded-xl font-bold transition-colors">
                    انصراف
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════ اورلی ساخت برنامه (۴۵ ثانیه) ═══════════ --}}
    @if($programJustBuilt)
    {{-- x-data به‌صورت اینلاین تا بعد از morph لایووایر هم اجرا شود --}}
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4"
         wire:key="build-overlay"
         x-data="{
            total: {{ \App\Livewire\Client\Profile\TrialWeek\SessionAnalysis::BUILD_OVERLAY_SECONDS }},
            remaining: {{ \App\Livewire\Client\Profile\TrialWeek\SessionAnalysis::BUILD_OVERLAY_SECONDS }},
            done: false,
            msgIndex: 0,
            messages: [
                'تحلیل طبقه‌بندی دروس و مباحث ضعیف و قوی…',
                'بررسی نیازمندی‌های پیش‌جلسه و پارت‌های درخواستی…',
                'لحاظ کردن تیپ شخصیتی و سبک یادگیری تو…',
                'چیدن پارت‌های مطالعه بر اساس اولویت دروس…',
                'بهینه‌سازی نهایی برنامه‌ی ۸ روزه‌ی تو…',
            ],
            get elapsed() { return this.total - this.remaining; },
            start() {
                const tick = setInterval(() => {
                    if (this.remaining <= 0) { clearInterval(tick); this.done = true; return; }
                    this.remaining--;
                    this.msgIndex = Math.min(this.messages.length - 1, Math.floor(this.elapsed / (this.total / this.messages.length)));
                }, 1000);
            },
         }"
         x-init="start()"
        <div class="absolute inset-0 bg-black/70 backdrop-blur-md"></div>

        <div class="relative z-10 w-full max-w-md bg-background border border-border rounded-3xl shadow-2xl p-8 text-center">

            {{-- فاز ۱: در حال ساخت --}}
            <template x-if="!done">
                <div>
                    <div class="relative mx-auto w-24 h-24 mb-6">
                        <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="44" fill="none" stroke="hsl(var(--border))" stroke-width="6"/>
                            <circle cx="50" cy="50" r="44" fill="none" stroke="hsl(var(--primary))" stroke-width="6"
                                    stroke-linecap="round" stroke-dasharray="276"
                                    :stroke-dashoffset="276 - (276 * elapsed / total)"
                                    style="transition: stroke-dashoffset 1s linear;"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center text-2xl">🛠️</div>
                    </div>

                    <h2 class="text-xl font-black text-foreground mb-2">در حال ساخت برنامه‌ی تو…</h2>
                    <p class="text-muted text-sm leading-7 mb-4" x-text="messages[msgIndex]"></p>
                    <div class="text-xs text-muted font-mono" x-text="remaining + ' ثانیه'"></div>
                </div>
            </template>

            {{-- فاز ۲: ساخته شد --}}
            <template x-if="done">
                <div>
                    <div class="mx-auto w-20 h-20 rounded-full bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center mb-5">
                        <svg class="w-10 h-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                    </div>
                    <h2 class="text-xl font-black text-foreground mb-2">برنامه‌ی شما ساخته شد 🎉</h2>
                    <p class="text-muted text-sm leading-7 mb-6">
                        هفته‌ی آزمایشی ۸ روزه‌ات از همین حالا شروع شد.
                        <br>
                        <span class="font-bold text-foreground">آماده‌ای شروع کنیم؟</span>
                    </p>
                    <button wire:click="goToDashboard"
                            wire:loading.attr="disabled" wire:target="goToDashboard"
                            class="w-full py-3.5 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-black text-base shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5">
                        بزن بریم! 🚀
                    </button>
                </div>
            </template>
        </div>
    </div>

    @endif

</div>
