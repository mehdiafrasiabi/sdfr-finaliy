<div>
    <div dir="rtl" class="w-full relative z-20">
        <div
            class="w-full border-b border-slate-200/70 dark:border-slate-700/60
               bg-gradient-to-l from-indigo-700 via-blue-600 to-sky-600
               dark:from-slate-950 dark:via-slate-900 dark:to-slate-900
               text-white"
            x-data="updateCountdownBanner(@js($remainingSeconds), @js($finished))"
            x-init="init()"
        >
            <div class="max-w-6xl mx-auto px-4 py-3 sm:py-4">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-4">

                    <!-- متن + آیکن -->
                    <div class="flex items-center gap-3 text-center lg:text-right w-full lg:w-auto">
                        <div class="shrink-0 w-12 h-12 rounded-2xl bg-white/15 dark:bg-white/10
                                flex items-center justify-center shadow-lg">
                            <span class="text-3xl">⏳</span>
                        </div>

                        <div class="space-y-1">
                            <p class="font-extrabold text-base sm:text-lg md:text-xl leading-snug">
                                آیا منتظر آپدیت SDFR هستی؟
                            </p>
                            <p class="text-xs sm:text-sm text-white/90 dark:text-white/75">
                                بزودی بزرگ‌ترین آپدیت مجموعه SDFR با کلی امکانات خفن
                            </p>
                        </div>
                    </div>

                    <!-- تایمر -->
                    <div class="w-full lg:w-auto">
                        <div
                            class="rounded-2xl px-4 sm:px-5 py-3
                               bg-white/12 dark:bg-white/5
                               border border-white/20 dark:border-white/10
                               backdrop-blur-md shadow-xl shadow-black/15"
                        >
                            <!-- وقتی تموم شد -->
                            <template x-if="finished">
                                <div class="flex items-center justify-center gap-3 py-1">
                                    <div class="text-center">
                                        <div class="font-extrabold text-xl sm:text-2xl mb-1">🎉 هورراااا</div>
                                        <div class="text-sm text-white/85">در حال آپدیت</div>
                                    </div>
                                </div>
                            </template>

                            <!-- در حال شمارش -->
                            <template x-if="!finished">
                                <div class="flex items-center justify-center">
                                    <div class="flex items-center gap-2 sm:gap-3 font-extrabold">
                                        <!-- روز -->
                                        <div class="min-w-[70px] sm:min-w-[85px] text-center">
                                            <div class="text-[10px] sm:text-xs font-bold text-white/70 mb-1">روز</div>
                                            <div class="text-xl sm:text-2xl tabular-nums tracking-tight" x-text="fmt(days)"></div>
                                        </div>

                                        <div class="text-white/50 font-black text-lg">:</div>

                                        <!-- ساعت -->
                                        <div class="min-w-[60px] sm:min-w-[70px] text-center">
                                            <div class="text-[10px] sm:text-xs font-bold text-white/70 mb-1">ساعت</div>
                                            <div class="text-xl sm:text-2xl tabular-nums tracking-tight" x-text="pad(hours)"></div>
                                        </div>

                                        <div class="text-white/50 font-black text-lg">:</div>

                                        <!-- دقیقه -->
                                        <div class="min-w-[60px] sm:min-w-[70px] text-center">
                                            <div class="text-[10px] sm:text-xs font-bold text-white/70 mb-1">دقیقه</div>
                                            <div class="text-xl sm:text-2xl tabular-nums tracking-tight" x-text="pad(minutes)"></div>
                                        </div>

                                        <div class="text-white/50 font-black text-lg">:</div>

                                        <!-- ثانیه -->
                                        <div class="min-w-[60px] sm:min-w-[70px] text-center">
                                            <div class="text-[10px] sm:text-xs font-bold text-white/70 mb-1">ثانیه</div>
                                            <div class="text-xl sm:text-2xl tabular-nums tracking-tight" x-text="pad(seconds)"></div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function updateCountdownBanner(initialRemainingSeconds, initiallyFinished) {
            return {
                remaining: Number(initialRemainingSeconds ?? 0),
                finished: Boolean(initiallyFinished ?? false),

                days: 0, hours: 0, minutes: 0, seconds: 0,
                timer: null,

                init() {
                    this.tick();
                    if (!this.finished) {
                        this.timer = setInterval(() => this.step(), 1000);
                    }
                },

                step() {
                    if (this.remaining <= 0) {
                        this.finish();
                        return;
                    }
                    this.remaining -= 1;
                    this.tick();
                    if (this.remaining <= 0) this.finish();
                },

                finish() {
                    this.finished = true;
                    this.remaining = 0;
                    this.tick();
                    if (this.timer) clearInterval(this.timer);
                },

                tick() {
                    let r = Math.max(0, this.remaining);

                    this.days = Math.floor(r / 86400);
                    r = r % 86400;

                    this.hours = Math.floor(r / 3600);
                    r = r % 3600;

                    this.minutes = Math.floor(r / 60);
                    this.seconds = r % 60;
                },

                // نمایش عدد فارسی (fa-IR)
                fmt(n) {
                    try {
                        return new Intl.NumberFormat('fa-IR').format(n);
                    } catch {
                        return String(n);
                    }
                },

                pad(n) {
                    const s = String(n).padStart(2, '0');
                    // تبدیل 0-9 به فارسی برای UX بهتر
                    return s.replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]);
                }
            }
        }
    </script>
</div>
