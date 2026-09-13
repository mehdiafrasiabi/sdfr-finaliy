<div class="max-w-7xl space-y-8 px-4 mx-auto">
    @php
        $hideForExamProgramTrialStudent = auth()->check()
            && app(\App\Services\ExamPlanningService::class)->shouldHideTrialExamProgramSections(auth()->user());
    @endphp

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
                <div class="bg-error/10 border border-error/30 rounded-2xl p-5 text-center">
                    <div class="text-3xl font-black text-error mb-1">{{ $analysis['weak_count'] }}</div>
                    <div class="text-xs text-error/70 font-semibold">مبحث ضعیف</div>
                    <div class="text-[10px] text-muted mt-1">نیاز به تمرکز بیشتر</div>
                </div>
                <div class="bg-warning/10 border border-warning/30 rounded-2xl p-5 text-center">
                    <div class="text-3xl font-black text-warning mb-1">{{ $analysis['medium_count'] }}</div>
                    <div class="text-xs text-warning/70 font-semibold">مبحث متوسط</div>
                    <div class="text-[10px] text-muted mt-1">قابل بهبود</div>
                </div>
                <div class="bg-success/10 border border-success/30 rounded-2xl p-5 text-center">
                    <div class="text-3xl font-black text-success mb-1">{{ $analysis['strong_count'] }}</div>
                    <div class="text-xs text-success/70 font-semibold">مبحث قوی</div>
                    <div class="text-[10px] text-muted mt-1">پایه خوب</div>
                </div>
            </div>

            @endif {{-- end if analysis --}}

            {{-- ═══════════ کارنامه تحلیلی پیش از ساخت برنامه ═══════════ --}}
            <div class="glass border border-border rounded-2xl p-6">
                <h3 class="font-black text-foreground mb-1 flex items-center gap-2">
                    <x-ui.icon name="receipt" class="w-5 h-5 text-primary"/>
                    کارنامه‌ی تحلیلی تو
                </h3>
                <p class="text-xs text-muted leading-6 mb-5">خلاصه‌ی همه‌ی کارهایی که انجام دادی — برنامه‌ات دقیقاً بر اساس همین اطلاعات ساخته می‌شود.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                    {{-- آزمون‌های شخصیت‌شناسی --}}
                    <div class="bg-secondary border border-border rounded-xl p-4">
                        <div class="text-xs font-bold text-foreground mb-2 flex items-center gap-1.5">
                            <x-ui.icon name="user" class="w-4 h-4 text-primary"/>
                            Mindet Test
                        </div>
                        <div class="text-[11px] text-muted leading-6">
                            @if($personality)
                                @if(!empty($personality['mbti']['type']))
                                    تیپ شخصیتی: <span class="font-black text-primary tracking-wider">{{ $personality['mbti']['type'] }}</span>
                                @endif
                                @if(!empty($personality['vark']['profile']))
                                    · سبک یادگیری: <span class="font-bold text-foreground">{{ $personality['vark']['profile'] }}</span>
                                @endif
                                تست تحصیلی - روانشناسی مایندت
                            @else
                                تحلیلی در دسترس نیست.
                            @endif
                        </div>
                    </div>
                    {{-- طبقه‌بندی --}}
                    <div class="bg-secondary border border-border rounded-xl p-4">
                        <div class="text-xs font-bold text-foreground mb-2 flex items-center gap-1.5">
                            <x-ui.icon name="layers" class="w-4 h-4 text-primary"/>
                            طبقه‌بندی دروس
                        </div>
                        @if(!empty($analysis) && $analysis['total'] > 0)
                            <div class="text-[11px] text-muted leading-6">
                                {{ $analysis['total'] }} مبحث ارزیابی شد:
                                <span class="text-error font-bold">{{ $analysis['weak_count'] }} ضعیف</span> ·
                                <span class="text-warning font-bold">{{ $analysis['medium_count'] }} متوسط</span> ·
                                <span class="text-success font-bold">{{ $analysis['strong_count'] }} قوی</span>
                            </div>
                        @else
                            <div class="text-[11px] text-muted">داده‌ای ثبت نشده است.</div>
                        @endif
                    </div>

                    {{-- برنامه کلاسی --}}
                    <div class="bg-secondary border border-border rounded-xl p-4">
                        <div class="text-xs font-bold text-foreground mb-2 flex items-center gap-1.5">
                            <x-ui.icon name="calendar" class="w-4 h-4 text-primary"/>
                            برنامه کلاسی مدرسه
                        </div>
                        <div class="text-[11px] text-muted leading-6">
                            @if(!$this->needsSchedule)
                                نیازی نبود — {{ $trialWeek?->isGraduate() ? 'فارغ‌التحصیل هستی' : 'فعلاً مدرسه نمی‌روی' }}.
                            @elseif($this->classScheduleFinalized)
                                <span class="text-success font-bold">ثبت و نهایی شد ✓</span>
                            @else
                                هنوز نهایی نشده است.
                            @endif
                        </div>
                    </div>

                    {{-- پیش‌جلسه --}}
                    <div class="bg-secondary border border-border rounded-xl p-4">
                        <div class="text-xs font-bold text-foreground mb-2 flex items-center gap-1.5">
                            <x-ui.icon name="list-check" class="w-4 h-4 text-primary"/>
                            نیازمندی های جلسه
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



                </div>
            </div>

            {{-- دکمه ساخت برنامه --}}
            @if($trialWeek && $trialWeek->status === \App\Models\TrialWeek::STATUS_PRE_SESSION_DONE)
            <div class="glass  border border-primary rounded-2xl p-8 text-center">
                <div class="text-5xl mb-4">📅</div>
                <h3 class="font-black text-foreground text-xl mb-3">شروع یک هفته مطالعه هوشمند</h3>
                <p class="text-muted text-sm mb-6 leading-relaxed">
                    سیستم هوشمند SDFR برای هفته پیش روی شما آماده ساخت برنامه اختصاصی با نظارت مشاور متخصص می باشد
                </p>
                <x-ui.button wire:click="openHoursModal" variant="primary" size="lg" icon="calendar" pill>ساخت برنامه</x-ui.button>
            </div>
            @elseif($trialWeek && $trialWeek->status === \App\Models\TrialWeek::STATUS_PROGRAM_BUILT)
            <div class="bg-success/10 border border-success/30 rounded-2xl p-6 text-center">
                <div class="text-4xl mb-3">✅</div>
                <h3 class="font-black text-foreground text-lg mb-2">برنامه ساخته شده است</h3>
                @unless($hideForExamProgramTrialStudent)
                    <x-ui.button href="{{ route('client.profile.consultation.sessions') }}" wire:navigate variant="success" icon="chevron-left" pill class="mt-3">مشاهده جلسات و برنامه</x-ui.button>
                @endunless
            </div>
            @endif

        </div>
    </div>

    {{-- ════════════════ مودال انتخاب ساعت مطالعاتی ════════════════ --}}
    <x-ui.modal id="trial-hours-modal" max-width="sm">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-info/15 border border-info/30 mb-4">
                <x-ui.icon name="clock" class="w-8 h-8 text-info"/>
            </div>

            <h2 class="text-lg font-black text-foreground mb-2">تعیین ساعت مطالعه روزانه</h2>
            <p class="text-sm text-muted leading-7 mb-5">
                لطفاً میانگین ساعتی که می‌توانی در روز مطالعه کنی را مشخص کن تا برنامه‌ات بر اساس آن ساخته شود.
            </p>

            <div class="mb-6 max-w-xs mx-auto text-right">
                <x-ui.select
                    wire:model="dailyStudyHours"
                    :options="collect(range(1, 14))->map(fn($h) => ['id' => $h, 'name' => $h . ' ساعت در روز'])->all()"
                    placeholder="انتخاب ساعت..."
                />
                @error('dailyStudyHours')<p class="text-xs text-error mt-2">{{ $message }}</p>@enderror
            </div>
        </div>

        <x-slot:footer>
            <x-ui.button type="button" wire:click="closeHoursModal" variant="secondary-outline" icon="x" block>انصراف</x-ui.button>
            <x-ui.button type="button" wire:click="buildProgram" wire:loading.attr="disabled" wire:target="buildProgram" variant="primary" block>
                <span wire:loading.remove wire:target="buildProgram">تأیید و ساخت برنامه</span>
                <span wire:loading wire:target="buildProgram" class="inline-flex items-center gap-2">
                    <x-ui.spinner size="xs"/>
                    در حال ایجاد…
                </span>
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
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
         x-init="start(); window.SdfrModalScrollLock.lock()">
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
                    <div class="mx-auto w-20 h-20 rounded-full bg-success/15 border border-success/30 flex items-center justify-center mb-5">
                        <x-ui.icon name="check" class="w-10 h-10 text-success"/>
                    </div>
                    <h2 class="text-xl font-black text-foreground mb-2">برنامه‌ی شما ساخته شد 🎉</h2>
                    <p class="text-muted text-sm leading-7 mb-6">
                        هفته‌ی آزمایشی ۸ روزه‌ات از همین حالا شروع شد.
                        <br>
                        <span class="font-bold text-foreground">آماده‌ای شروع کنیم؟</span>
                    </p>
                    <x-ui.button wire:click="goToDashboard" wire:loading.attr="disabled" wire:target="goToDashboard" variant="primary" icon="arrow-left" size="lg" block>بزن بریم! 🚀</x-ui.button>
                </div>
            </template>
        </div>
    </div>

    @endif

</div>
