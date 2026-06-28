<div>
    @assets
    <style>
        /* حس فشار دادن دکمه */
        .press-btn {
            transition: transform 0.08s ease, box-shadow 0.08s ease;
            cursor: pointer;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }
        .press-btn:hover {
            background-color: #35353c !important;
        }
        .press-btn:active {
            transform: translateY(3px);
            box-shadow: 0 1px 0 #1a1a1f !important;
        }
        /* حذف اسپینر از input number */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] { -moz-appearance: textfield; }
    </style>
    @endassets
    <div dir="rtl">

        {{-- ==================== بنر بالای صفحه ==================== --}}
        <div class="bg-primary pb-40 sm:pb-44 pt-10 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="flex md:flex-row flex-col items-center justify-between gap-6 md:gap-8">

                    {{-- متن --}}
                    <div class="space-y-3 text-center md:text-right order-1 md:order-1">
                        <div class="flex items-center gap-2 justify-center md:justify-end">
                            <svg width="46" height="140" viewBox="0 0 46 140" fill="none"
                                 xmlns="http://www.w3.org/2000/svg"
                                 class="h-full sm:w-auto w-6 max-h-16 text-white/70">
                                <path d="M14.6961 131.927L27.9077 128.958C27.8003 130.209 27.3414 131.404 26.5918 132.383C25.8423 133.362 24.8374 134.079 23.7102 134.44C17.7 136.566 11.5905 138.334 5.41003 139.735C1.88814 140.487 0.107879 137.811 1.27564 134.189C3.22637 128.125 5.45891 122.147 7.58317 116.132C7.70288 115.782 7.81512 115.314 8.06083 115.141C8.87447 114.624 9.75059 114.213 10.6059 113.767C10.9329 114.546 11.652 115.377 11.5315 116.126C11.165 118.539 10.5156 120.9 9.97527 123.275C9.57055 124.691 9.52631 126.189 9.84717 127.615C11.2303 126.331 12.6663 125.106 13.9859 123.751C27.0005 110.485 35.6931 93.1013 38.7375 74.2506C41.782 55.3999 39.0112 36.1173 30.8478 19.3444C28.7161 14.869 26.0615 10.6917 23.6744 6.34201C22.8435 4.85252 22.0911 3.29367 21.2943 1.75769L22.0985 0.817938C23.0816 1.22994 24.3579 1.34076 25.003 2.08855C27.2882 4.63792 29.4005 7.35962 31.3246 10.2336C50.1428 39.6302 49.7137 80.106 30.0307 111.127C26.2796 117.05 21.5217 122.255 17.1923 127.759C16.305 128.921 15.2592 129.893 14.2875 130.947L14.6961 131.927Z" fill="currentColor"/>
                            </svg>
                            <div>
                                <h1 class="font-black text-white text-3xl sm:text-4xl leading-snug">
                                    {{ $setting?->title ?? 'درصدگیر آنلاین SDFR' }}
                                </h1>
                                <p class="text-white/80 text-sm sm:text-base mt-3">
                                    {{ $setting?->subtitle ?? 'محاسبه فوری درصد پاسخگویی به سوالات تستی' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- تصویر --}}
                    <div class="flex-shrink-0 flex justify-center order-2 md:order-2">
                        @if($setting?->image)
                            <img src="{{ asset('percent-calculator/' . $setting->image) }}"
                                 alt="{{ $setting->title }}"
                                 class="w-56 sm:w-72 md:w-80 object-contain drop-shadow-2xl">
                        @else
                            <svg width="280" height="240" viewBox="0 0 280 240" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-48 sm:w-64 drop-shadow-2xl">
                                <rect x="80" y="20" width="130" height="180" rx="16" fill="url(#calcGrad2)"/>
                                <rect x="95" y="38" width="100" height="40" rx="8" fill="#FFECB3"/>
                                <text x="188" y="65" text-anchor="end" fill="#333" font-size="18" font-weight="bold" font-family="monospace">34.45</text>
                                <rect x="95"  y="90"  width="22" height="18" rx="5" fill="#EF9A9A"/>
                                <rect x="123" y="90"  width="22" height="18" rx="5" fill="#EF9A9A"/>
                                <rect x="151" y="90"  width="22" height="18" rx="5" fill="#EF9A9A"/>
                                <rect x="179" y="90"  width="16" height="18" rx="5" fill="#FF7043"/>
                                <rect x="95"  y="114" width="22" height="18" rx="5" fill="#FFCDD2"/>
                                <rect x="123" y="114" width="22" height="18" rx="5" fill="#FFCDD2"/>
                                <rect x="151" y="114" width="22" height="18" rx="5" fill="#FFCDD2"/>
                                <rect x="179" y="114" width="16" height="18" rx="5" fill="#FF7043"/>
                                <rect x="95"  y="138" width="22" height="18" rx="5" fill="#FFCDD2"/>
                                <rect x="123" y="138" width="22" height="18" rx="5" fill="#FFCDD2"/>
                                <rect x="151" y="138" width="22" height="18" rx="5" fill="#FFCDD2"/>
                                <rect x="179" y="138" width="16" height="36" rx="5" fill="#FF7043"/>
                                <rect x="95"  y="162" width="50" height="18" rx="5" fill="#FFCDD2"/>
                                <rect x="151" y="162" width="22" height="18" rx="5" fill="#FFCDD2"/>
                                <circle cx="55" cy="70" r="20" fill="#FFCCBC"/>
                                <rect x="35" y="90" width="40" height="60" rx="8" fill="#1565C0"/>
                                <line x1="35" y1="105" x2="15" y2="125" stroke="#FFCCBC" stroke-width="8" stroke-linecap="round"/>
                                <line x1="75" y1="105" x2="82" y2="85"  stroke="#FFCCBC" stroke-width="8" stroke-linecap="round"/>
                                <rect x="37" y="148" width="14" height="38" rx="6" fill="#0D47A1"/>
                                <rect x="53" y="148" width="14" height="38" rx="6" fill="#0D47A1"/>
                                <text x="18"  y="68" fill="white" font-size="20" font-weight="bold" opacity="0.4">?</text>
                                <defs>
                                    <linearGradient id="calcGrad2" x1="80" y1="20" x2="210" y2="200" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#EF5350"/>
                                        <stop offset="100%" stop-color="#B71C1C"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- ==================== بدنه اصلی ==================== --}}
        <div class="max-w-7xl mx-auto px-4 -mt-36 sm:-mt-40 relative z-10"
             x-data="percentCalculator()">

            {{-- ==================== کارت فرم ==================== --}}
            <div class="bg-secondary rounded-2xl shadow-xl border border-border p-6 sm:p-8 mb-5">

                <p class="text-foreground/70 text-sm sm:text-base mb-6 text-right">
                    برای محاسبه‌ی درصد پاسخگویی، تعداد کل سوالات و تعداد پاسخ‌های درست و نادرست خود را در فرم زیر وارد کنید:
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6">

                    {{-- ===== کل سوالات ===== --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-foreground text-right">تعداد کل سوالات</label>
                        <div class="flex items-center gap-2">
                            {{-- دکمه − --}}
                            <button type="button"
                                    @click="total = Math.max(1, (parseInt(total)||1) - 1); calculate()"
                                    class="press-btn flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center text-xl font-bold text-foreground select-none"
                                    style="background-color:#2b2b31; box-shadow: 0 4px 0 #1a1a1f;">
                                −
                            </button>
                            {{-- اینپوت --}}
                            <input type="tel" min="1"
                                   x-model="total" @input="calculate"
                                   placeholder="۰"
                                   class="flex-1 text-center text-lg font-bold text-foreground focus:outline-none focus:ring-2 focus:ring-primary/40 h-11 rounded-xl border border-border min-w-0"
                                   style="background-color:#2b2b31">
                            {{-- دکمه + --}}
                            <button type="button"
                                    @click="total = Math.max(1, (parseInt(total)||0) + 1); calculate()"
                                    class="press-btn flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center text-xl font-bold text-foreground select-none"
                                    style="background-color:#2b2b31; box-shadow: 0 4px 0 #1a1a1f;">
                                +
                            </button>
                        </div>
                    </div>

                    {{-- ===== پاسخ درست ===== --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-foreground text-right">تعداد پاسخ درست</label>
                        <div class="flex items-center gap-2">
                            <button type="button"
                                    @click="correct = Math.max(0, (parseInt(correct)||0) - 1); calculate()"
                                    class="press-btn flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center text-xl font-bold text-foreground select-none"
                                    style="background-color:#2b2b31; box-shadow: 0 4px 0 #1a1a1f;">
                                −
                            </button>
                            <input type="tel" min="0"
                                   x-model="correct" @input="calculate"
                                   placeholder="۰"
                                   class="flex-1 text-center text-lg font-bold text-foreground focus:outline-none focus:ring-2 focus:ring-primary/40 h-11 rounded-xl border border-border min-w-0"
                                   style="background-color:#2b2b31">
                            <button type="button"
                                    @click="correct = Math.max(0, (parseInt(correct)||0) + 1); calculate()"
                                    class="press-btn flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center text-xl font-bold text-foreground select-none"
                                    style="background-color:#2b2b31; box-shadow: 0 4px 0 #1a1a1f;">
                                +
                            </button>
                        </div>
                    </div>

                    {{-- ===== پاسخ نادرست ===== --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-foreground text-right">تعداد پاسخ نادرست</label>
                        <div class="flex items-center gap-2">
                            <button type="button"
                                    @click="wrong = Math.max(0, (parseInt(wrong)||0) - 1); calculate()"
                                    class="press-btn flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center text-xl font-bold text-foreground select-none"
                                    style="background-color:#2b2b31; box-shadow: 0 4px 0 #1a1a1f;">
                                −
                            </button>
                            <input type="tel" min="0"
                                   x-model="wrong" @input="calculate"
                                   placeholder="۰"
                                   class="flex-1 text-center text-lg font-bold text-foreground focus:outline-none focus:ring-2 focus:ring-primary/40 h-11 rounded-xl border border-border min-w-0"
                                   style="background-color:#2b2b31">
                            <button type="button"
                                    @click="wrong = Math.max(0, (parseInt(wrong)||0) + 1); calculate()"
                                    class="press-btn flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center text-xl font-bold text-foreground select-none"
                                    style="background-color:#2b2b31; box-shadow: 0 4px 0 #1a1a1f;">
                                +
                            </button>
                        </div>
                    </div>

                </div>

                <div x-show="error" x-transition
                     class="mt-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-3 text-red-700 dark:text-red-300 text-sm text-center">
                    <span x-text="error"></span>
                </div>

            </div>

            {{-- ==================== کارت نتیجه ==================== --}}
            <div x-show="resultWithNeg !== null || resultWithout !== null"
                 x-transition:enter="transition ease-out duration-400"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">

                <div class="bg-secondary rounded-2xl shadow-xl border border-border p-6 sm:p-8 mb-5">

                    <p class="text-foreground/70 text-sm sm:text-base font-semibold mb-5 text-right">
                        درصد پاسخگویی شما به شرح زیر است:
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div class="rounded-2xl border-2 p-6 text-center transition-all duration-300"
                             :class="{
                            'border-green-400 bg-green-50 dark:bg-green-900/20': resultWithNeg !== null && resultWithNeg >= 60,
                            'border-yellow-400 bg-yellow-50 dark:bg-yellow-900/20': resultWithNeg !== null && resultWithNeg >= 30 && resultWithNeg < 60,
                            'border-red-400 bg-red-50 dark:bg-red-900/20': resultWithNeg !== null && resultWithNeg < 30,
                            'border-border': resultWithNeg === null
                         }">
                            <p class="text-foreground/50 text-xs mb-2">درصد (با نمره منفی)</p>
                            <p class="font-black text-4xl sm:text-5xl"
                               :class="{
                              'text-green-600 dark:text-green-400': resultWithNeg !== null && resultWithNeg >= 60,
                              'text-yellow-500 dark:text-yellow-400': resultWithNeg !== null && resultWithNeg >= 30 && resultWithNeg < 60,
                              'text-red-600 dark:text-red-400': resultWithNeg !== null && resultWithNeg < 30,
                              'text-foreground/30': resultWithNeg === null
                           }">
                                <span x-text="resultWithNeg !== null ? resultWithNeg.toFixed(2) + '%' : '---'"></span>
                            </p>
                            <p class="text-xs text-foreground/30 mt-3 font-mono">(درست×۳ − نادرست) ÷ (کل×۳) × ۱۰۰</p>
                        </div>

                        <div class="rounded-2xl border-2 p-6 text-center transition-all duration-300"
                             :class="{
                            'border-green-400 bg-green-50 dark:bg-green-900/20': resultWithout !== null && resultWithout >= 60,
                            'border-yellow-400 bg-yellow-50 dark:bg-yellow-900/20': resultWithout !== null && resultWithout >= 30 && resultWithout < 60,
                            'border-red-400 bg-red-50 dark:bg-red-900/20': resultWithout !== null && resultWithout < 30,
                            'border-border': resultWithout === null
                         }">
                            <p class="text-foreground/50 text-xs mb-2">درصد خام (بدون نمره منفی)</p>
                            <p class="font-black text-4xl sm:text-5xl"
                               :class="{
                              'text-green-600 dark:text-green-400': resultWithout !== null && resultWithout >= 60,
                              'text-yellow-500 dark:text-yellow-400': resultWithout !== null && resultWithout >= 30 && resultWithout < 60,
                              'text-red-600 dark:text-red-400': resultWithout !== null && resultWithout < 30,
                              'text-foreground/30': resultWithout === null
                           }">
                                <span x-text="resultWithout !== null ? resultWithout.toFixed(2) + '%' : '---'"></span>
                            </p>
                            <p class="text-xs text-foreground/30 mt-3 font-mono">درست ÷ کل × ۱۰۰</p>
                        </div>

                    </div>

                    <div class="grid grid-cols-3 gap-3 mt-5">
                        <div class="rounded-xl p-3 text-center" style="background-color:#2b2b31">
                            <div class="text-xl font-black text-primary" x-text="total || '—'"></div>
                            <div class="text-xs text-foreground/50 mt-1">کل سوالات</div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-3 text-center">
                            <div class="text-xl font-black text-green-600" x-text="correct || '—'"></div>
                            <div class="text-xs text-foreground/50 mt-1">پاسخ درست</div>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-3 text-center">
                            <div class="text-xl font-black text-red-500" x-text="wrong || '—'"></div>
                            <div class="text-xs text-foreground/50 mt-1">پاسخ نادرست</div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ==================== فرمول‌ها ==================== --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-secondary rounded-2xl shadow-sm border border-border p-5">
                    <h3 class="font-bold text-foreground mb-3 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-7 h-7 bg-green-100 dark:bg-green-900/30 rounded-full text-green-600 text-sm font-bold">✓</span>
                        بدون نمره منفی
                    </h3>
                    <div class="rounded-xl p-3 text-center font-mono text-sm text-foreground/80" style="background-color:#2b2b31">
                        درصد = (تعداد درست ÷ تعداد کل) × ۱۰۰
                    </div>
                </div>
                <div class="bg-secondary rounded-2xl shadow-sm border border-border p-5">
                    <h3 class="font-bold text-foreground mb-3 flex items-center gap-2">
                        <span class="inline-flex items-center justify-center w-7 h-7 bg-red-100 dark:bg-red-900/30 rounded-full text-red-600 text-sm font-bold">−</span>
                        با نمره منفی
                    </h3>
                    <div class="rounded-xl p-3 text-center font-mono text-sm text-foreground/80" style="background-color:#2b2b31">
                        درصد = ((درست × ۳ − نادرست) ÷ (کل × ۳)) × ۱۰۰
                    </div>
                </div>
            </div>

            @if($setting?->description)
                <div class="bg-secondary rounded-2xl shadow-sm border border-border p-6 sm:p-8 mb-6">
                    <div class="prose prose-lg dark:prose-invert max-w-none leading-relaxed ck-content">
                        {!! $setting->description !!}
                    </div>
                </div>
            @endif

        </div>

    </div>

    @script
    <script>
        function percentCalculator() {
            return {
                total: '',
                correct: '',
                wrong: '',
                resultWithNeg: null,
                resultWithout: null,
                error: '',

                calculate() {
                    this.error = '';
                    this.resultWithNeg = null;
                    this.resultWithout = null;

                    const total   = parseFloat(this.total);
                    const correct = parseFloat(this.correct);
                    const wrong   = parseFloat(this.wrong) || 0;

                    if (!this.total || isNaN(total) || total <= 0) return;
                    if (this.correct === '' || isNaN(correct) || correct < 0) return;

                    if (correct > total) {
                        this.error = 'تعداد پاسخ درست نمی‌تواند بیشتر از کل سوالات باشد.';
                        return;
                    }
                    if ((correct + wrong) > total) {
                        this.error = 'مجموع پاسخ درست و نادرست نمی‌تواند بیشتر از کل سوالات باشد.';
                        return;
                    }

                    this.resultWithout = (correct / total) * 100;
                    this.resultWithNeg = ((correct * 3 - wrong) / (total * 3)) * 100;
                }
            }
        }
    </script>
    @endscript
</div>
