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

            @endif {{-- end if analysis --}}

            {{-- ═══════════ کارنامه تحلیلی پیش از ساخت برنامه ═══════════ --}}
            <div class="glass border border-border rounded-2xl p-6">
                <h3 class="font-black text-foreground mb-1 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/>
                    </svg>
                    کارنامه‌ی تحلیلی تو
                </h3>
                <p class="text-xs text-muted leading-6 mb-5">خلاصه‌ی همه‌ی کارهایی که انجام دادی — برنامه‌ات دقیقاً بر اساس همین اطلاعات ساخته می‌شود.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                    {{-- آزمون‌های شخصیت‌شناسی --}}
                    <div class="bg-secondary border border-border rounded-xl p-4">
                        <div class="text-xs font-bold text-foreground mb-2 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M6 21v-1a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v1"/></svg>
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

                    {{-- برنامه کلاسی --}}
                    <div class="bg-secondary border border-border rounded-xl p-4">
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

                    {{-- پیش‌جلسه --}}
                    <div class="bg-secondary border border-border rounded-xl p-4">
                        <div class="text-xs font-bold text-foreground mb-2 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
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
                <button wire:click="openHoursModal"
                        class="inline-flex items-center gap-3 px-8 py-4 bg-primary hover:bg-primary/90 text-primary-foreground rounded-2xl font-black text-base shadow-lg shadow-primary/30 hover:-translate-y-0.5 transition-all duration-200">
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

    {{-- ════════════════ مودال انتخاب ساعت مطالعاتی ════════════════ --}}
    @if($showHoursModal)
        <div x-data x-init="document.body.style.overflow='hidden'"
             @keydown.escape.window="$wire.closeHoursModal()">

            <!-- لایه تاریک پس‌زمینه (Overlay) -->
            <div class="m-overlay" wire:click="closeHoursModal"></div>

            <!-- بدنه اصلی مودال (در موبایل از پایین باز می‌شود و در دسکتاپ وسط‌چین است) -->
            <div class="m-sheet" @click.stop>
                <!-- خط دستگیره بالای مودال مخصوص موبایل -->
                <div class="m-handle"></div>

                <div class="p-6 text-center overflow-y-auto">
                    <!-- آیکون مودال -->
                    <div class="pop-in inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-500/15 border border-blue-500/30 mb-4">
                        <svg class="w-8 h-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>

                    <h2 class="text-lg font-black text-foreground mb-2">تعیین ساعت مطالعه روزانه</h2>
                    <p class="text-sm text-muted leading-7 mb-5">
                        لطفاً میانگین ساعتی که می‌توانی در روز مطالعه کنی را مشخص کن تا برنامه‌ات بر اساس آن ساخته شود.
                    </p>

                    <!-- بخش انتخاب ساعت (به صورت Select یا اینپوت بسته به سلیقه خودتان) -->
                    <div class="mb-6 max-w-xs mx-auto">
                        <select wire:model="dailyStudyHours" class="w-full bg-secondary border border-border text-foreground rounded-xl px-4 py-3 font-bold text-center focus:outline-none focus:border-blue-500">
                            @for($i = 1; $i <= 14; $i++)
                                <option value="{{ $i }}">{{ $i }} ساعت در روز</option>
                            @endfor
                        </select>
                        @error('dailyStudyHours')<p class="text-xs text-red-500 mt-2">{{ $message }}</p>@enderror
                    </div>

                    <!-- دکمه‌های اکشن -->
                    <div class="flex gap-3">
                        <button type="button" wire:click="buildProgram"
                                wire:loading.attr="disabled" wire:target="buildProgram"
                                class="press btn-primary flex-1 inline-flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm">
                            <svg wire:loading wire:target="buildProgram" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.4 0 0 5.4 0 12h4z"/>
                            </svg>
                            <span wire:loading.remove wire:target="buildProgram">تأیید و ساخت برنامه</span>
                            <span wire:loading wire:target="buildProgram">در حال ایجاد…</span>
                        </button>

                        <button type="button" wire:click="closeHoursModal"
                                class="press btn-soft flex-1 py-3 rounded-xl font-bold text-sm">
                            انصراف
                        </button>
                    </div>
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
         x-init="start()">
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
