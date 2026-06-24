<div class="max-w-2xl mx-auto px-4 py-6 sm:py-10" dir="rtl"
     x-data="consultantTimer()">

    @push('link')
        <style>
            [x-cloak] { display: none !important; }

            /* ════ انیمیشن قطره‌ی آب (ripple) ════ */
            @keyframes droplet {
                0%   { transform: scale(0.4); opacity: 0; }
                15%  { opacity: 0.7; }
                100% { transform: scale(2.6); opacity: 0; }
            }
            .droplet {
                position: absolute; inset: 0; border-radius: 9999px;
                border: 2px solid hsl(var(--primary) / 0.5);
                animation: droplet 3.4s cubic-bezier(0, 0.5, 0.5, 1) infinite;
            }
            .droplet.d2 { animation-delay: 1.13s; }
            .droplet.d3 { animation-delay: 2.26s; }

            /* ════ شناور شدن نرم ════ */
            @keyframes bob {
                0%, 100% { transform: translateY(0); }
                50%      { transform: translateY(-5px); }
            }
            .bob { animation: bob 4s ease-in-out infinite; }

            /* ════ ورود مرحله‌ای ════ */
            @keyframes rise {
                from { opacity: 0; transform: translateY(18px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            .rise { animation: rise 0.55s cubic-bezier(0.16, 1, 0.3, 1) backwards; }
            .rise.r1 { animation-delay: 0.05s; }
            .rise.r2 { animation-delay: 0.14s; }

            /* ════ ورود کارت مشاور ════ */
            @keyframes card-pop {
                0%   { opacity: 0; transform: translateY(16px) scale(0.94); }
                60%  { transform: translateY(-3px) scale(1.02); }
                100% { opacity: 1; transform: translateY(0) scale(1); }
            }
            .card-pop { animation: card-pop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }

            /* ════ حلقهٔ تایمر ════ */
            .timer-ring circle { transition: stroke-dashoffset 1s linear; }

            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after { animation: none !important; transition: none !important; }
            }
        </style>
    @endpush

    {{-- ═══════════ سرتیتر ═══════════ --}}
    <div class="rise r1 flex items-center gap-2.5 mb-5">
        <div class="flex items-center gap-1">
            <div class="w-1 h-1 bg-foreground rounded-full"></div>
            <div class="w-2 h-2 bg-foreground rounded-full"></div>
        </div>
        <span class="font-black text-foreground text-lg">هفته آزمایشی</span>
    </div>

    {{-- ═══════════ باکس اصلی ═══════════ --}}
    <div class="rise r2 relative overflow-hidden rounded-3xl border border-border bg-secondary">
        <div class="absolute inset-0 bg-gradient-to-b from-primary/[0.04] to-transparent pointer-events-none"></div>
        <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-72 h-72 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative px-6 py-10 sm:py-12 flex flex-col items-center text-center">

            {{-- ─── تایمر دایره‌ای + انیمیشن جستجو ─── --}}
            <div class="relative w-36 h-36 mb-7">
                <span class="droplet" x-show="!revealed"></span>
                <span class="droplet d2" x-show="!revealed"></span>
                <span class="droplet d3" x-show="!revealed"></span>

                {{-- حلقهٔ تایمر --}}
                <svg class="timer-ring absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="46" fill="none" stroke="hsl(var(--border))" stroke-width="4"/>
                    <circle cx="50" cy="50" r="46" fill="none" stroke="hsl(var(--primary))" stroke-width="4"
                            stroke-linecap="round"
                            stroke-dasharray="289"
                            :stroke-dashoffset="289 - (289 * elapsed / total)"/>
                </svg>

                <div class="bob absolute inset-[18%] rounded-full bg-gradient-to-br from-primary to-primary/70 flex items-center justify-center shadow-xl shadow-primary/25">
                    <template x-if="!revealed">
                        <svg class="w-10 h-10 text-primary-foreground" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="M21 21l-4.35-4.35"/>
                            <circle cx="11" cy="9" r="2.4"/>
                            <path d="M7.5 15.2a4 4 0 0 1 7 0"/>
                        </svg>
                    </template>
                    <template x-if="revealed">
                        <svg class="w-10 h-10 text-primary-foreground" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                    </template>
                </div>

                {{-- شمارنده --}}
                <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 px-2.5 py-0.5 rounded-full bg-background border border-border text-[11px] font-black text-primary font-mono"
                     x-text="remaining + 's'"></div>
            </div>

            {{-- عنوان و توضیح --}}
            <template x-if="!revealed">
                <div>
                    <h1 class="font-black text-2xl text-foreground mb-2">در حال انتخاب مشاور</h1>
                    <p class="text-sm text-muted leading-7 max-w-md mb-6">
                        <span class="font-bold text-primary">ثبت‌ درخواست با موفقیت انجام شد.</span>
                        <br>
                        سیستم هوشمند SDFR در حال آنالیز نتیجه‌ی آزمون‌هایت و انتخاب بهترین مشاور برای توست.
                    </p>
                </div>
            </template>
            <template x-if="revealed">
                <div>
                    <h1 class="font-black text-2xl text-foreground mb-2">مشاور تو انتخاب شد 🎉</h1>
                    <p class="text-sm text-muted leading-7 max-w-md mb-6">
                        این مشاور در طول هفته‌ی آزمایشی همراهت است و فرایند را قدم‌به‌قدم جلو می‌برد.
                    </p>
                </div>
            </template>

            {{-- ─── کارت مشاور (از ثانیهٔ ۱۲ به بعد) ─── --}}
            <div x-show="revealed" x-cloak class="card-pop w-full max-w-sm mb-6">
                <div class="rounded-2xl border border-primary/30 bg-background p-5 flex items-center gap-4 text-right">
                    {{-- عکس پروفایل --}}
                    @if($consultantAvatar)
                        <img src="{{ $consultantAvatar }}" alt="{{ $consultantName }}"
                             class="w-16 h-16 rounded-2xl object-cover border border-border shrink-0">
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0">
                            <svg class="w-8 h-8 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4"/><path d="M5 21v-1a7 7 0 0 1 14 0v1"/>
                            </svg>
                        </div>
                    @endif

                    <div class="min-w-0">
                        <div class="text-[10px] font-bold text-primary bg-primary/10 rounded-full px-2 py-0.5 inline-block mb-1.5">مشاور شما</div>
                        <div class="font-black text-foreground truncate">{{ $consultantName ?? 'مشاور SDFR' }}</div>
                        @if($consultantMobile)
                            <a href="tel:{{ $consultantMobile }}" dir="ltr"
                               class="inline-flex items-center gap-1.5 text-xs text-muted font-mono mt-1 hover:text-primary transition-colors">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                {{ $consultantMobile }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- نوار پیشرفت مراحل --}}
            <div class="w-full max-w-sm">
                <div class="flex items-center justify-between text-[11px] mb-2">
                    <span class="font-bold text-emerald-600 dark:text-emerald-400">آزمون‌ها</span>
                    <span class="font-bold text-primary">انتخاب مشاور</span>
                    <span class="font-medium text-muted">شروع</span>
                </div>
                <div class="relative h-1.5 rounded-full bg-background overflow-hidden">
                    <div class="absolute inset-y-0 right-0 w-1/3 bg-emerald-500 rounded-full"></div>
                    <div class="absolute inset-y-0 bg-primary/70 rounded-full transition-all duration-1000"
                         style="right: 33.33%;" :style="'right:33.33%;width:' + (33.33 * elapsed / total) + '%'"></div>
                </div>
            </div>

        </div>
    </div>

    {{-- پیام خطا --}}
    @if (session('error'))
        <div class="mt-5 rounded-xl bg-red-50 dark:bg-red-900/10 text-red-700 dark:text-red-300 px-4 py-3 border border-red-200 dark:border-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @push('script')
        <script>
            function consultantTimer() {
                return {
                    total: {{ \App\Livewire\Client\Profile\TrialWeek\WaitingForSupporter::TIMER_SECONDS }},
                    revealAt: {{ \App\Livewire\Client\Profile\TrialWeek\WaitingForSupporter::REVEAL_AT_SECOND }},
                    remaining: {{ \App\Livewire\Client\Profile\TrialWeek\WaitingForSupporter::TIMER_SECONDS }},
                    revealed: false,
                    finished: false,
                    get elapsed() { return this.total - this.remaining; },
                    // Alpine متد init داخل x-data را خودش یک بار صدا می‌زند.
                    init() {
                        const tick = setInterval(() => {
                            if (this.remaining <= 0) {
                                clearInterval(tick);
                                if (!this.finished) {
                                    this.finished = true;
                                    this.$wire.finish();
                                }
                                return;
                            }
                            this.remaining--;
                            // بعد از رد شدن از ثانیهٔ ۱۲، باکس مشاور نمایش داده می‌شود.
                            if (this.remaining < this.revealAt) {
                                this.revealed = true;
                            }
                        }, 1000);
                    },
                };
            }
        </script>
    @endpush
</div>
