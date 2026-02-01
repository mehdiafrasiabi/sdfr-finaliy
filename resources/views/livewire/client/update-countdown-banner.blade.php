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
                <div class="flex flex-col lg:flex-row items-center justify-between gap-3">

                    <!-- متن + آیکن -->
                    <div class="flex items-center gap-3 text-center lg:text-right">
                        <div class="shrink-0 w-11 h-11 rounded-2xl bg-white/15 dark:bg-white/10
                                flex items-center justify-center shadow-inner">
                            <span class="text-2xl">⏳</span>
                        </div>

                        <div class="space-y-0.5">
                            <p class="font-extrabold text-base sm:text-lg md:text-xl leading-snug">
                                 آیا منتظر آپدیت SDFR هستی؟
                            </p>
                            <p class="text-xs sm:text-sm text-white/85 dark:text-white/70">
                               بزودی بزرگ ترین آپدیت مجموعه SDFR  با کلی امکانات خفن
                            </p>
                        </div>
                    </div>

                    <!-- تایمر -->
                    <div class="w-full lg:w-auto">
                        <div
                            class="w-full lg:w-auto rounded-2xl px-3 sm:px-4 py-2.5
                               bg-white/12 dark:bg-white/5
                               border border-white/15 dark:border-white/10
                               backdrop-blur-md shadow-lg shadow-black/10"
                        >
                            <!-- وقتی تموم شد -->
                            <template x-if="finished">
                                <div class="flex items-center justify-center gap-3">
                                    <div class="text-right">
                                        <div class="font-extrabold text-lg sm:text-xl">هورراااا 🎉</div>
                                        <div class="text-sm text-white/80">در حال آپدیت</div>
                                    </div>
                                </div>
                            </template>

                            <!-- در حال شمارش -->
                            <template x-if="!finished">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2 sm:gap-3 font-extrabold">
                                        <!-- روز -->
                                        <div class="min-w-[72px] sm:min-w-[88px] text-center">
                                            <div class="text-[11px] sm:text-xs font-bold text-white/75 mb-0.5">روز</div>
                                            <div class="text-lg sm:text-xl tabular-nums" x-text="fmt(days)"></div>
                                        </div>

                                        <div class="text-white/40 font-black">:</div>

                                        <!-- ساعت -->
                                        <div class="min-w-[62px] sm:min-w-[72px] text-center">
                                            <div class="text-[11px] sm:text-xs font-bold text-white/75 mb-0.5">ساعت
                                            </div>
                                            <div class="text-lg sm:text-xl tabular-nums" x-text="pad(hours)"></div>
                                        </div>

                                        <div class="text-white/40 font-black">:</div>

                                        <!-- دقیقه -->
                                        <div class="min-w-[62px] sm:min-w-[72px] text-center">
                                            <div class="text-[11px] sm:text-xs font-bold text-white/75 mb-0.5">دقیقه
                                            </div>
                                            <div class="text-lg sm:text-xl tabular-nums" x-text="pad(minutes)"></div>
                                        </div>

                                        <div class="text-white/40 font-black">:</div>

                                        <!-- ثانیه -->
                                        <div class="min-w-[62px] sm:min-w-[72px] text-center">
                                            <div class="text-[11px] sm:text-xs font-bold text-white/75 mb-0.5">ثانیه
                                            </div>
                                            <div class="text-lg sm:text-xl tabular-nums" x-text="pad(seconds)"></div>
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
