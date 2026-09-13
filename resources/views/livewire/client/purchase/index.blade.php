@php
    $purchaseCurrentJalaliDate = \Morilog\Jalali\Jalalian::now();
    $purchaseCurrentJalaliYear = (int) $purchaseCurrentJalaliDate->format('Y');
    $purchaseCurrentJalaliMonth = (int) $purchaseCurrentJalaliDate->format('m');
    $purchaseCurrentJalaliDay = (int) $purchaseCurrentJalaliDate->format('d');
@endphp
<div dir="rtl"
     x-data="purchaseBirthDatePicker(@entangle('infoBirthDate'), {{ $purchaseCurrentJalaliYear }}, {{ $purchaseCurrentJalaliMonth }}, {{ $purchaseCurrentJalaliDay }})"
     class="relative mx-auto max-w-[94rem] overflow-x-hidden px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
    @push('link')
        <style>
            [x-cloak] { display: none !important; }

            .birth-date-trigger {
                direction: ltr;
                text-align: center;
                letter-spacing: 0.04em;
            }

            .birth-picker-grid {
                direction: ltr;
                display: grid;
                grid-template-columns: minmax(0, 1fr) minmax(0, 1.25fr) minmax(0, .8fr);
                gap: .35rem;
            }

            .birth-wheel {
                position: relative;
                height: 13.75rem;
                overflow-y: auto;
                overscroll-behavior: contain;
                scroll-snap-type: y mandatory;
                scrollbar-width: none;
                -webkit-overflow-scrolling: touch;
                mask-image: linear-gradient(to bottom, transparent, #000 24%, #000 76%, transparent);
                -webkit-mask-image: linear-gradient(to bottom, transparent, #000 24%, #000 76%, transparent);
            }

            .birth-wheel-shell {
                position: relative;
                min-width: 0;
            }

            .birth-wheel-shell::after {
                content: '';
                position: absolute;
                z-index: 0;
                top: 7rem;
                right: 0;
                left: 0;
                height: 2.75rem;
                border-radius: .8rem;
                background: hsl(var(--foreground) / .075);
                border-block: 1px solid hsl(var(--border) / .7);
                pointer-events: none;
            }

            .birth-wheel::-webkit-scrollbar { display: none; }

            .birth-wheel-spacer {
                height: 5.5rem;
                pointer-events: none;
            }

            .birth-wheel-item {
                position: relative;
                z-index: 1;
                display: flex;
                width: 100%;
                height: 2.75rem;
                align-items: center;
                justify-content: center;
                scroll-snap-align: center;
                scroll-snap-stop: always;
                border-radius: .8rem;
                color: hsl(var(--muted-foreground));
                font-size: .9rem;
                transition: color .15s ease, font-size .15s ease, font-weight .15s ease;
            }

            .birth-wheel-item.is-selected {
                color: hsl(var(--primary));
                font-size: 1.05rem;
                font-weight: 800;
            }

            .birth-picker-modal {
                box-shadow: 0 28px 80px -24px rgba(0, 0, 0, .55);
            }

            .purchase-shell {
                position: relative;
                overflow: hidden;
                border: 1px solid hsl(var(--border) / 0.8);
                background:
                    radial-gradient(circle at top right, hsl(var(--primary) / 0.16), transparent 28rem),
                    radial-gradient(circle at bottom left, hsl(var(--primary) / 0.08), transparent 24rem),
                    linear-gradient(180deg, hsl(var(--secondary) / 0.94), hsl(var(--secondary) / 0.82));
                box-shadow: 0 30px 80px -50px hsl(var(--foreground) / 0.45);
                backdrop-filter: blur(18px);
                -webkit-backdrop-filter: blur(18px);
            }

            .purchase-panel {
                position: relative;
                min-width: 0;
                border: 1px solid hsl(var(--border) / 0.65);
                background: linear-gradient(180deg, hsl(var(--background) / 0.96), hsl(var(--secondary) / 0.4));
                backdrop-filter: blur(14px);
                -webkit-backdrop-filter: blur(14px);
                box-shadow: inset 0 1px 0 hsl(var(--secondary) / 0.25);
            }

            .purchase-animated-border::before {
                content: "";
                position: absolute;
                inset: 0;
                padding: 1px;
                border-radius: inherit;
                pointer-events: none;
                background: conic-gradient(
                    from var(--purchase-angle),
                    hsl(var(--border) / 0.55),
                    hsl(var(--primary) / 0.85),
                    hsl(var(--border) / 0.5),
                    hsl(var(--primary) / 0.45),
                    hsl(var(--border) / 0.55)
                );
                mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
                -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
                mask-composite: exclude;
                -webkit-mask-composite: xor;
                animation: purchaseBorderSpin 7s linear infinite;
            }

            @property --purchase-angle {
                syntax: '<angle>';
                initial-value: 0deg;
                inherits: false;
            }

            @keyframes purchaseBorderSpin {
                to { --purchase-angle: 360deg; }
            }

            .purchase-stable-price {
                min-height: 3.5rem;
                font-variant-numeric: tabular-nums;
            }

            .purchase-button {
                background: hsl(var(--primary));
                color: hsl(var(--primary-foreground));
                box-shadow: 0 18px 36px -20px hsl(var(--primary) / 0.8);
            }

            .purchase-button:hover {
                filter: brightness(1.05);
            }

            .purchase-button-warning {
                background: hsl(var(--warning) / 0.18);
                color: hsl(var(--warning));
                border: 1px solid hsl(var(--warning) / 0.35);
                box-shadow: 0 18px 36px -24px hsl(var(--warning) / 0.65);
            }

            .purchase-button-warning:hover {
                background: hsl(var(--warning) / 0.24);
            }

            .purchase-button-soft {
                border: 1px solid hsl(var(--border) / 0.9);
                background: hsl(var(--secondary) / 0.8);
            }

            .purchase-button-trial {
                border: 1px solid hsl(var(--success) / 0.32);
                background: linear-gradient(135deg, hsl(var(--success) / 0.18), hsl(var(--success) / 0.1));
                color: hsl(var(--success));
                box-shadow: 0 18px 36px -24px hsl(var(--success) / 0.55);
            }

            .purchase-button-trial:hover {
                background: linear-gradient(135deg, hsl(var(--success) / 0.24), hsl(var(--success) / 0.16));
            }

            .purchase-field {
                border: 1px solid hsl(var(--border) / 0.85);
                background: hsl(var(--background));
                transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
            }

            .purchase-field:focus {
                border-color: hsl(var(--primary) / 0.85);
                box-shadow: 0 0 0 4px hsl(var(--primary) / 0.12);
                transform: translateY(-1px);
                outline: none;
            }

            .purchase-step-node {
                transition: transform .25s ease, border-color .25s ease, background-color .25s ease, box-shadow .25s ease;
            }

            .purchase-step-node.is-active {
                background: hsl(var(--primary));
                color: hsl(var(--primary-foreground));
                border-color: hsl(var(--primary));
                box-shadow: 0 18px 30px -18px hsl(var(--primary) / 0.75);
            }

            .purchase-step-node.is-done {
                background: hsl(var(--primary) / 0.14);
                color: hsl(var(--primary));
                border-color: hsl(var(--primary) / 0.45);
            }

            .purchase-step-line.is-done {
                background: linear-gradient(90deg, hsl(var(--primary) / 0.35), hsl(var(--primary) / 0.8));
            }

            .purchase-review-card {
                border: 1px solid hsl(var(--border) / 0.75);
                background: linear-gradient(180deg, hsl(var(--background) / 0.96), hsl(var(--secondary) / 0.18));
            }

            .purchase-surface-card {
                background: linear-gradient(180deg, hsl(var(--background) / 0.98), hsl(var(--secondary) / 0.26));
            }

            .purchase-amount {
                direction: ltr;
                unicode-bidi: plaintext;
                display: inline-block;
                max-width: 100%;
                overflow-wrap: anywhere;
                line-height: 1.15;
            }

            .purchase-select-surface > div > button {
                background: hsl(var(--background)) !important;
                color: hsl(var(--foreground)) !important;
                border-color: hsl(var(--border) / 0.85) !important;
            }

            .purchase-select-surface > div > button span,
            .purchase-select-surface > div > button svg {
                color: hsl(var(--foreground)) !important;
            }

            .purchase-step-stage {
                animation: purchaseStageEnter .42s cubic-bezier(.22, 1, .36, 1);
            }

            @keyframes purchaseStageEnter {
                from {
                    opacity: 0;
                    transform: translateY(22px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (prefers-reduced-motion: reduce) {
                .purchase-animated-border::before {
                    animation: none;
                }

                .purchase-step-stage {
                    animation: none;
                }
            }

            @media (max-width: 640px) {
                .purchase-shell {
                    border-radius: 1.25rem !important;
                }

                .purchase-panel {
                    border-radius: 1.125rem !important;
                }
            }
        </style>
        <script>
            window.purchaseBirthDatePicker = function (birthDateModel, currentYear, currentMonth, currentDay) {
                return {
                    birthDate: birthDateModel,
                    birthPickerOpen: false,
                    birthPickerTrigger: null,
                    previousBodyOverflow: '',
                    currentJalaliYear: Number(currentYear),
                    defaultBirthYear: 1385,
                    defaultBirthMonth: Number(currentMonth),
                    defaultBirthDay: Number(currentDay),
                    pickerYear: 1385,
                    pickerMonth: Number(currentMonth),
                    pickerDay: Number(currentDay),
                    birthMonths: [
                        {value: 1, label: 'فروردین'},
                        {value: 2, label: 'اردیبهشت'},
                        {value: 3, label: 'خرداد'},
                        {value: 4, label: 'تیر'},
                        {value: 5, label: 'مرداد'},
                        {value: 6, label: 'شهریور'},
                        {value: 7, label: 'مهر'},
                        {value: 8, label: 'آبان'},
                        {value: 9, label: 'آذر'},
                        {value: 10, label: 'دی'},
                        {value: 11, label: 'بهمن'},
                        {value: 12, label: 'اسفند'},
                    ],

                    get birthYears() {
                        return Array.from(
                            {length: Math.max(1, this.currentJalaliYear - 1380 + 1)},
                            (_, index) => 1380 + index
                        );
                    },

                    get birthDays() {
                        return Array.from(
                            {length: this.daysInJalaliMonth(this.pickerYear, this.pickerMonth)},
                            (_, index) => index + 1
                        );
                    },

                    destroy() {
                        this.unlockBirthPickerBody();
                    },

                    normalizeDigits(value) {
                        return String(value ?? '')
                            .replace(/[۰-۹]/g, digit => String('۰۱۲۳۴۵۶۷۸۹'.indexOf(digit)))
                            .replace(/[٠-٩]/g, digit => String('٠١٢٣٤٥٦٧٨٩'.indexOf(digit)));
                    },

                    toPersianDigits(value) {
                        return String(value).replace(/\d/g, digit => '۰۱۲۳۴۵۶۷۸۹'[Number(digit)]);
                    },

                    openBirthDatePicker() {
                        const normalized = this.normalizeDigits(this.birthDate);
                        const match = normalized.match(/^(\d{4})\/(\d{2})\/(\d{2})$/);
                        let hasValidSelectedDate = false;

                        if (match) {
                            const year = Number(match[1]);
                            const month = Number(match[2]);
                            const day = Number(match[3]);

                            if (year >= 1380 && year <= this.currentJalaliYear && month >= 1 && month <= 12) {
                                this.pickerYear = year;
                                this.pickerMonth = month;
                                this.pickerDay = Math.min(
                                    Math.max(day, 1),
                                    this.daysInJalaliMonth(year, month)
                                );
                                hasValidSelectedDate = true;
                            }
                        }

                        if (!hasValidSelectedDate) {
                            this.pickerYear = this.defaultBirthYear;
                            this.pickerMonth = this.defaultBirthMonth;
                            this.pickerDay = Math.min(
                                this.defaultBirthDay,
                                this.daysInJalaliMonth(this.defaultBirthYear, this.defaultBirthMonth)
                            );
                        }

                        this.birthPickerTrigger = document.activeElement;
                        this.previousBodyOverflow = document.body.style.overflow;
                        document.body.style.overflow = 'hidden';
                        this.birthPickerOpen = true;
                        this.$nextTick(() => requestAnimationFrame(() => this.scrollBirthWheels('auto')));
                    },

                    closeBirthDatePicker() {
                        if (!this.birthPickerOpen) return;
                        this.birthPickerOpen = false;
                        this.unlockBirthPickerBody();
                        setTimeout(() => this.birthPickerTrigger?.focus(), 260);
                    },

                    unlockBirthPickerBody() {
                        document.body.style.overflow = this.previousBodyOverflow;
                    },

                    confirmBirthDate() {
                        const maxDay = this.daysInJalaliMonth(this.pickerYear, this.pickerMonth);
                        this.pickerDay = Math.min(this.pickerDay, maxDay);
                        const formatted = [this.pickerYear, this.pickerMonth, this.pickerDay]
                            .map((part, index) => index === 0 ? String(part) : String(part).padStart(2, '0'))
                            .join('/');

                        this.birthDate = formatted;
                        this.$wire.setInfoBirthDate(formatted);
                        this.closeBirthDatePicker();
                    },

                    selectBirthPart(part, value) {
                        this.applyBirthPart(part, Number(value));
                        this.$nextTick(() => {
                            this.scrollBirthWheel(part, 'smooth');
                            if (part !== 'day') this.scrollBirthWheel('day', 'smooth');
                        });
                    },

                    syncBirthWheel(part, event) {
                        const values = this.birthPartValues(part);
                        if (!values.length) return;

                        const index = Math.max(0, Math.min(values.length - 1, Math.round(event.target.scrollTop / 44)));
                        this.applyBirthPart(part, values[index]);
                    },

                    applyBirthPart(part, value) {
                        if (part === 'year') this.pickerYear = value;
                        if (part === 'month') this.pickerMonth = value;
                        if (part === 'day') this.pickerDay = value;

                        const maxDay = this.daysInJalaliMonth(this.pickerYear, this.pickerMonth);
                        if (this.pickerDay > maxDay) {
                            this.pickerDay = maxDay;
                            this.$nextTick(() => this.scrollBirthWheel('day', 'smooth'));
                        }
                    },

                    birthPartValues(part) {
                        if (part === 'year') return this.birthYears;
                        if (part === 'month') return this.birthMonths.map(month => month.value);
                        return this.birthDays;
                    },

                    scrollBirthWheels(behavior = 'auto') {
                        this.scrollBirthWheel('year', behavior);
                        this.scrollBirthWheel('month', behavior);
                        this.scrollBirthWheel('day', behavior);
                    },

                    scrollBirthWheel(part, behavior = 'auto') {
                        const refNames = {
                            year: 'purchaseBirthYearWheel',
                            month: 'purchaseBirthMonthWheel',
                            day: 'purchaseBirthDayWheel',
                        };
                        const selected = {
                            year: this.pickerYear,
                            month: this.pickerMonth,
                            day: this.pickerDay,
                        };
                        const wheel = this.$refs[refNames[part]];
                        const index = this.birthPartValues(part).indexOf(selected[part]);

                        if (wheel && index >= 0) {
                            wheel.scrollTo({top: index * 44, behavior});
                        }
                    },

                    daysInJalaliMonth(year, month) {
                        if (month <= 6) return 31;
                        if (month <= 11) return 30;
                        return this.isJalaliLeapYear(year) ? 30 : 29;
                    },

                    isJalaliLeapYear(year) {
                        const breaks = [-61, 9, 38, 199, 426, 686, 756, 818, 1111, 1181,
                            1210, 1635, 2060, 2097, 2192, 2262, 2324, 2394, 2456, 3178];
                        let previous = breaks[0];
                        let jump = 0;

                        if (year < previous || year >= breaks[breaks.length - 1]) return false;

                        for (let index = 1; index < breaks.length; index += 1) {
                            const current = breaks[index];
                            jump = current - previous;
                            if (year < current) break;
                            previous = current;
                        }

                        let offset = year - previous;
                        if (jump - offset < 6) {
                            offset = offset - jump + Math.trunc((jump + 4) / 33) * 33;
                        }

                        let leap = ((offset + 1) % 33 - 1) % 4;
                        if (leap === -1) leap = 4;
                        return leap === 0;
                    },
                };
            };

            window.initPurchaseStepEffects = window.initPurchaseStepEffects || (() => {
                if (window.__purchaseStepEffectsBound) {
                    return;
                }

                window.__purchaseStepEffectsBound = true;
                window.addEventListener('purchase-step-changed', () => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });

                window.addEventListener('purchase-focus-field', event => {
                    const field = event?.detail?.field;
                    if (!field) {
                        return;
                    }

                    requestAnimationFrame(() => {
                        const wrapper = document.querySelector(`[data-purchase-field="${field}"]`);
                        const target = wrapper?.querySelector('button, input:not([type="hidden"]), textarea');

                        if (!target) {
                            return;
                        }

                        wrapper.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        target.focus({ preventScroll: true });
                        if (target.tagName === 'BUTTON' || field === 'infoBirthDate') {
                            target.click();
                        }
                    });
                });
            });

            document.addEventListener('DOMContentLoaded', window.initPurchaseStepEffects);
            document.addEventListener('livewire:navigated', window.initPurchaseStepEffects);
        </script>
    @endpush

    <div wire:loading.flex wire:target="nextStep,prevStep,startEditInfo,cancelEditInfo,saveInfo,saveInfoAndContinue,applyCoupon,removeCoupon,pay,payInstallment,openTrialConfirm,closeTrialConfirm,startTrialWeek"
         class="fixed inset-0 z-[90] hidden items-center justify-center bg-black/55 backdrop-blur-sm">
        <div class="w-[min(92vw,24rem)] rounded-3xl border border-white/10 bg-black/80 p-6 text-center text-white shadow-2xl">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-white/10 bg-white/5 p-3">
                <x-ui.spinner size="lg" class="text-white"/>
            </div>
            <p class="text-sm font-black">در حال پردازش مرحله جاری...</p>
            <p class="mt-2 text-xs leading-6 text-white/70">لطفاً چند لحظه صبر کنید تا اطلاعات شما بررسی و مرحله بعد آماده شود.</p>
        </div>
    </div>

    <div x-show="birthPickerOpen" x-cloak
         @keydown.escape.window="closeBirthDatePicker()"
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-250"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-end justify-center sm:items-center sm:p-4"
         role="dialog" aria-modal="true" aria-labelledby="purchase-birth-picker-title">
        <div x-show="birthPickerOpen"
             x-transition.opacity.duration.200ms
             class="absolute inset-0 bg-black/65 backdrop-blur-sm"
             @click="closeBirthDatePicker()"></div>

        <div x-show="birthPickerOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-4 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-250"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-4 sm:scale-95"
             class="birth-picker-modal relative w-full rounded-t-[2rem] border border-border bg-background px-4 pt-3 pb-[calc(env(safe-area-inset-bottom)+1rem)] sm:max-w-md sm:rounded-3xl sm:p-6">
            <div class="mx-auto mb-4 h-1.5 w-14 rounded-full bg-foreground/10 sm:hidden"></div>

            <div class="flex items-center justify-between gap-4 px-1">
                <div>
                    <h3 id="purchase-birth-picker-title" class="text-lg font-black text-foreground">انتخاب تاریخ تولد</h3>
                    <p class="mt-1 text-[11px] text-muted">برای انتخاب، هر ستون را بالا یا پایین بکشید.</p>
                </div>
                <button type="button" @click="closeBirthDatePicker()"
                        aria-label="بستن انتخابگر تاریخ"
                        data-elevated="false"
                        class="btn-press flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-secondary/70 text-muted transition-colors hover:text-foreground">
                    <x-ui.icon name="x" class="h-4 w-4"/>
                </button>
            </div>

            <div class="birth-picker-grid mt-5" aria-label="سال، ماه و روز تولد">
                <div class="birth-wheel-shell">
                    <div class="mb-2 text-center text-[11px] font-bold text-muted" dir="rtl">سال</div>
                    <div x-ref="purchaseBirthYearWheel" class="birth-wheel"
                         @scroll.passive.debounce.100ms="syncBirthWheel('year', $event)">
                        <div class="birth-wheel-spacer"></div>
                        <template x-for="year in birthYears" :key="year">
                            <button type="button" class="birth-wheel-item"
                                    :class="{ 'is-selected': pickerYear === year }"
                                    :aria-selected="pickerYear === year"
                                    @click="selectBirthPart('year', year)"
                                    x-text="toPersianDigits(year)"></button>
                        </template>
                        <div class="birth-wheel-spacer"></div>
                    </div>
                </div>

                <div class="birth-wheel-shell">
                    <div class="mb-2 text-center text-[11px] font-bold text-muted" dir="rtl">ماه</div>
                    <div x-ref="purchaseBirthMonthWheel" class="birth-wheel"
                         @scroll.passive.debounce.100ms="syncBirthWheel('month', $event)">
                        <div class="birth-wheel-spacer"></div>
                        <template x-for="month in birthMonths" :key="month.value">
                            <button type="button" class="birth-wheel-item" dir="rtl"
                                    :class="{ 'is-selected': pickerMonth === month.value }"
                                    :aria-selected="pickerMonth === month.value"
                                    @click="selectBirthPart('month', month.value)"
                                    x-text="month.label"></button>
                        </template>
                        <div class="birth-wheel-spacer"></div>
                    </div>
                </div>

                <div class="birth-wheel-shell">
                    <div class="mb-2 text-center text-[11px] font-bold text-muted" dir="rtl">روز</div>
                    <div x-ref="purchaseBirthDayWheel" class="birth-wheel"
                         @scroll.passive.debounce.100ms="syncBirthWheel('day', $event)">
                        <div class="birth-wheel-spacer"></div>
                        <template x-for="day in birthDays" :key="day">
                            <button type="button" class="birth-wheel-item"
                                    :class="{ 'is-selected': pickerDay === day }"
                                    :aria-selected="pickerDay === day"
                                    @click="selectBirthPart('day', day)"
                                    x-text="toPersianDigits(String(day).padStart(2, '0'))"></button>
                        </template>
                        <div class="birth-wheel-spacer"></div>
                    </div>
                </div>
            </div>

            <div class="mt-5 grid grid-cols-2 gap-3">
                <button type="button" @click="closeBirthDatePicker()" data-elevated="false"
                        class="btn-press h-11 rounded-xl border border-border bg-secondary/40 text-sm font-bold text-muted transition-colors hover:text-foreground">
                    انصراف
                </button>
                <button type="button" @click="confirmBirthDate()" data-elevated="true"
                        class="btn-press purchase-button h-11 rounded-xl text-sm font-black">
                    تأیید تاریخ
                </button>
            </div>
        </div>
    </div>

    @if (session('error'))
        <div class="mb-5 rounded-2xl border border-error/30 bg-error/10 px-4 py-3 text-sm font-bold text-error">
            {{ session('error') }}
        </div>
    @endif

    @if (! $price || ! $data)
        <div class="purchase-shell rounded-[2rem] p-6 sm:p-8">
            <div class="purchase-panel mx-auto max-w-2xl rounded-[1.75rem] p-8 text-center">
                <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl border border-border bg-secondary/50 text-primary">
                    <x-ui.icon name="info" class="h-8 w-8"/>
                </div>
                <h1 class="text-xl font-black text-foreground sm:text-2xl">فعلاً قیمت فعالی برای پایه شما ثبت نشده است.</h1>
                <p class="mt-3 text-sm leading-7 text-muted">به‌محض ثبت قیمت، همین صفحه امکان خرید را نمایش می‌دهد. اگر فکر می‌کنید این مورد غیرعادی است با پشتیبانی هماهنگ کنید.</p>
            </div>
        </div>
    @else
        @php
            $toFaDigits = static fn ($value) => strtr((string) $value, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']);
            $faMoney = static fn ($value) => $toFaDigits(number_format((int) $value));
            $steps = [
                1 => ['title' => 'مرور پلن', 'caption' => 'خدمات و قیمت'],
                2 => ['title' => 'اطلاعات شما', 'caption' => 'بازبینی و اصلاح'],
                3 => ['title' => 'نهایی‌سازی', 'caption' => 'پرداخت و تایید'],
            ];
        @endphp

        <div class="purchase-shell purchase-animated-border rounded-[2rem] p-3 sm:p-6 lg:p-8">
            <div class="pointer-events-none absolute inset-x-8 top-0 h-48 rounded-full bg-primary/10 blur-3xl"></div>

            <div class="relative z-10 space-y-6">
                <div class="grid gap-4 lg:grid-cols-[1fr_20rem] lg:items-start">


                    <div class="purchase-panel w-full rounded-[1.75rem] p-3 sm:p-5">
                        <div class="grid gap-4 sm:grid-cols-3">
                            @foreach ($steps as $n => $meta)
                                <div class="flex items-center gap-3 {{ $loop->last ? '' : 'sm:pl-2' }}">
                                    <div @class([
                                    'purchase-step-node flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border text-sm font-black',
                                    'is-active' => $step === $n,
                                    'is-done' => $step > $n,
                                    'border-border bg-secondary/50 text-muted' => $step < $n,
                                ])>
                                        @if ($step > $n)
                                            <x-ui.icon name="check" class="h-5 w-5"/>
                                        @else
                                            {{ $toFaDigits($n) }}
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm font-black text-foreground">{{ $meta['title'] }}</div>
                                        <div class="text-xs text-muted">{{ $meta['caption'] }}</div>
                                    </div>
                                    @unless ($loop->last)
                                        <div @class([
                                        'purchase-step-line hidden h-1 flex-1 rounded-full sm:block',
                                        'is-done' => $step > $n,
                                        'bg-border' => $step <= $n,
                                    ])></div>
                                    @endunless
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>


                <div class="mx-auto w-full max-w-[74rem]">
                    <section class="purchase-panel rounded-[1.75rem] p-4 sm:p-7 lg:p-8">
                        <div id="purchase-step-stage" wire:key="purchase-step-stage-{{ $step }}" class="purchase-step-stage">
                        @if ($step === 1)
                            <div class="space-y-6">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <h2 class="text-xl font-black text-foreground sm:text-2xl">مرور خدمات و مبلغ نهایی</h2>
                                        <p class="mt-2 text-sm leading-7 text-muted">جزئیات خرید را یک‌جا می‌بینید تا بدانید دقیقاً چه خدماتی دریافت می‌کنید و مبلغ بر چه اساسی محاسبه شده است.</p>
                                    </div>
                                    @if ($data['discount'] > 0)
                                        <div class="rounded-2xl border border-primary/20 bg-primary/10 px-4 py-3 text-right">
                                            <div class="text-[11px] font-bold text-primary">مزیت ثبت‌نام زودتر</div>
                                            <div class="mt-1 text-sm font-black text-foreground">{{ $toFaDigits($data['discount']) }}٪ تخفیف</div>
                                        </div>
                                    @endif
                                </div>

                                <div class="grid gap-4 lg:grid-cols-[1.1fr_.9fr]">
                                    <div class="purchase-animated-border purchase-surface-card relative rounded-[1.5rem] border border-border p-5">
                                        <div class="flex items-center gap-2 text-sm font-black text-foreground">
                                            <span class="h-2.5 w-2.5 rounded-full bg-primary"></span>
                                            خدماتی که در این خرید فعال می‌شود
                                        </div>
                                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                            @foreach ([
                                                'مشاور اختصاصی و جلسات منظم',
                                                'برنامه‌ریزی هفتگی و پیگیری مستمر',
                                                'آزمون، گزارش و تحلیل عملکرد',
                                                'پشتیبانی آموزشی تا پایان دوره',
                                            ] as $service)
                                                <div class="rounded-2xl border border-border bg-secondary/60 px-4 py-3 text-sm font-semibold text-foreground">
                                                    {{ $service }}
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-4 rounded-2xl border border-primary/15 bg-primary/10 p-4 text-sm leading-7 text-muted">
                                            مبلغ بر اساس <span class="font-black text-foreground">ماه ورود</span> محاسبه می‌شود. پایان دسترسی پنل برای این خرید روی <span class="font-black text-foreground">{{ $toFaDigits($data['access_ends_label']) }}</span> ثبت می‌شود و سررسید اقساط هم فقط تا ۲۰ اسفند چیده می‌شود.
                                        </div>
                                        @unless ($data['access_ends_is_khordad'])
                                            <div class="mt-3 rounded-2xl border border-error/25 bg-error/10 p-4 text-sm leading-7 text-error">
                                                تاریخ پایان دسترسی این پایه هنوز روی پایان خرداد تنظیم نشده است. قبل از پرداخت باید این مورد اصلاح شود.
                                            </div>
                                        @endunless
                                    </div>

                                    <div class="purchase-animated-border relative rounded-[1.5rem] border border-primary/20 bg-primary/10 p-5">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <div class="text-[11px] font-bold text-primary">پایه تحصیلی</div>
                                                <div class="mt-1 text-lg font-black text-foreground">{{ $price->grade_label }}</div>
                                            </div>
                                            <div class="text-left">
                                                <div class="text-[11px] font-bold text-primary">ماه ورود</div>
                                                <div class="mt-1 text-sm font-black text-foreground">{{ $data['month_label'] }}</div>
                                            </div>
                                        </div>

                                        <div class="mt-6 rounded-[1.35rem] border border-white/10 bg-background/80 p-5 text-center">
                                            @if ($data['savings'] > 0)
                                                <div class="text-sm text-muted line-through"><span class="purchase-amount">{{ $faMoney($data['original_total']) }}</span> تومان</div>
                                            @endif
                                            <div class="purchase-stable-price mt-2 text-[clamp(2rem,5vw,3.35rem)] font-black text-foreground"><span class="purchase-amount">{{ $faMoney($data['total']) }}</span></div>
                                            <div class="mt-1 text-sm font-bold text-primary">مبلغ خرید نهایی</div>
                                            @if ($data['savings'] > 0)
                                                <div class="mt-3 inline-flex rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-xs font-black text-primary">
                                                    <span class="purchase-amount">{{ $faMoney($data['savings']) }}</span> تومان تخفیف
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if (($data['installment_open'] ?? false) && $data['installment_count'] > 0)
                                    <div class="purchase-surface-card rounded-[1.5rem] border border-border p-5">
                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <div class="text-sm font-black text-foreground">امکان پرداخت اقساطی هم فعال است</div>
                                                <p class="mt-2 text-sm leading-7 text-muted">ابتدا پیش‌پرداخت را می‌پردازید و سپس مبلغ باقی‌مانده در {{ $toFaDigits($data['installment_count']) }} قسط ماهانه تا {{ $toFaDigits($data['installment_until_label']) }} تقسیم می‌شود.</p>
                                            </div>
                                            <div class="grid w-full gap-3 text-center sm:w-auto sm:min-w-[15rem] sm:grid-cols-2">
                                                <div class="rounded-2xl border border-primary/15 bg-background/80 p-3">
                                                    <div class="text-[11px] font-bold text-primary">پیش‌پرداخت</div>
                                                    <div class="mt-1 text-base font-black text-foreground"><span class="purchase-amount">{{ $faMoney($data['initial']) }}</span></div>
                                                </div>
                                                <div class="rounded-2xl border border-border bg-background/80 p-3">
                                                    <div class="text-[11px] text-muted">مبلغ هر قسط</div>
                                                    <div class="mt-1 text-base font-black text-foreground"><span class="purchase-amount">{{ $faMoney($data['monthly']) }}</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="rounded-[1.5rem] border border-success/20 bg-success/8 p-5">
                                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                        <div class="max-w-2xl">
                                            <div class="text-sm font-black text-foreground">فعلاً می‌خواهی با ما یک هفته تستی کار کنی؟</div>
                                            <p class="mt-2 text-sm leading-7 text-muted">
                                                اگر هنوز برای خرید قطعی نیستی، می‌توانی به‌جای پرداخت، مسیر <span class="font-black text-foreground">یک هفته آزمایشی</span> را شروع کنی و بعد از ورود، ادامه خرید از همین صفحه دیگر در اولویت این flow نخواهد بود.
                                            </p>
                                        </div>
                                        <button wire:click="openTrialConfirm" wire:loading.attr="disabled" wire:target="openTrialConfirm" data-elevated="true"
                                                class="btn-press purchase-button-trial inline-flex w-full items-center justify-center gap-2 rounded-2xl px-6 py-3 text-sm font-black transition disabled:opacity-60 lg:w-auto">
                                            <span wire:loading.remove wire:target="openTrialConfirm">شروع یک هفته آزمایشی</span>
                                            <span wire:loading wire:target="openTrialConfirm" class="inline-flex items-center gap-2">
                                                <x-ui.spinner size="sm"/>
                                                در حال آماده‌سازی
                                            </span>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button wire:click="nextStep" wire:loading.attr="disabled" wire:target="nextStep" data-elevated="true"
                                            class="btn-press purchase-button inline-flex w-full items-center justify-center gap-2 rounded-2xl px-6 py-3 text-sm font-black transition disabled:opacity-60 sm:w-auto">
                                        <span wire:loading.remove wire:target="nextStep">ادامه و بررسی اطلاعات</span>
                                        <span wire:loading wire:target="nextStep" class="inline-flex items-center gap-2">
                                            <x-ui.spinner size="sm"/>
                                            در حال بررسی
                                        </span>
                                    </button>
                                </div>
                            </div>
                        @endif

                        @if ($step === 2)
                            <div class="space-y-6">
                                <div class="flex flex-col gap-4">
                                    <div>
                                        <h2 class="text-xl font-black text-foreground sm:text-2xl">بازبینی اطلاعات کاربر</h2>
                                        <p class="mt-2 text-sm leading-7 text-muted">قبل از ورود به پرداخت، اطلاعات این بخش را یک‌بار دقیق چک کنید. برای ادامه، «آدرس»، «محل تولد»، «استان» و «شهر» باید کامل باشند.</p>
                                    </div>
                                    @if ($requiresInfoCompletion && ! $editingInfo)
                                        <div class="rounded-2xl border border-warning/25 bg-warning/10 p-4 text-sm leading-7 text-warning">
                                            برای ورود به پرداخت هنوز باید اطلاعات ضروری‌ات را تکمیل کنی. دکمه پایین در همین مرحله تو را وارد ویرایش می‌کند.
                                        </div>
                                    @endif
                                </div>

                                @if (! $editingInfo)
                                    @php
                                        $rows = [
                                            ['label' => 'نام', 'value' => $infoName, 'field' => 'infoName'],
                                            ['label' => 'نام و نام خانوادگی', 'value' => $infoNameFull ?: '—', 'field' => 'infoNameFull'],
                                            ['label' => 'نام پدر', 'value' => $infoFatherName, 'field' => 'infoFatherName'],
                                            ['label' => 'کد ملی', 'value' => $toFaDigits($infoCodeMell), 'field' => 'infoCodeMell'],
                                            ['label' => 'پایه', 'value' => $gradeOptions[$infoGrade] ?? $infoGrade, 'field' => 'infoGrade'],
                                            ['label' => 'رشته', 'value' => $selectedGradeRequiresField ? ($fieldOptions[$infoField] ?? $infoField) : 'بدون رشته', 'field' => 'infoField'],
                                            ['label' => 'تاریخ تولد', 'value' => $infoBirthDate ? $toFaDigits($infoBirthDate) : '—', 'field' => 'infoBirthDate'],
                                            ['label' => 'محل تولد', 'value' => $infoPlaceOfBirth ?: '<span class="text-error text-xs font-black">تکمیل این فیلد الزامی است</span>', 'field' => 'infoPlaceOfBirth'],
                                            ['label' => 'موبایل پدر', 'value' => $toFaDigits($infoFatherMobile), 'field' => 'infoFatherMobile'],
                                            ['label' => 'موبایل مادر', 'value' => $toFaDigits($infoMotherMobile), 'field' => 'infoMotherMobile'],
                                            ['label' => 'استان', 'value' => $pi?->state?->name ?? '—', 'field' => 'infoStateId'],
                                            ['label' => 'شهر', 'value' => $pi?->city?->name ?? '—', 'field' => 'infoCityId'],
                                        ];
                                    @endphp

                                    <div class="grid gap-4 lg:grid-cols-2">
                                        @foreach ($rows as $row)
                                            <div class="purchase-review-card rounded-2xl p-4">
                                                <div class="flex items-center justify-between gap-3">
                                                    <div class="text-[11px] font-bold text-muted">{{ $row['label'] }}</div>
                                                    <button wire:click="editInfoField('{{ $row['field'] }}')" wire:loading.attr="disabled" data-elevated="false"
                                                            class="btn-press rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-[11px] font-black text-primary transition hover:bg-primary/15">
                                                        ویرایش
                                                    </button>
                                                </div>
                                                <div class="mt-3 text-sm font-black text-foreground">{!! $row['value'] !!}</div>
                                            </div>
                                        @endforeach
                                        <div class="purchase-review-card rounded-2xl p-4 lg:col-span-2">
                                            <div class="flex items-center justify-between gap-3">
                                                <div class="text-[11px] font-bold text-muted">آدرس</div>
                                                <button wire:click="editInfoField('infoAddress')" wire:loading.attr="disabled" data-elevated="false"
                                                        class="btn-press rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-[11px] font-black text-primary transition hover:bg-primary/15">
                                                    ویرایش
                                                </button>
                                            </div>
                                            <div class="mt-2 text-sm font-black text-foreground">{!! $infoAddress ?: '<span class="text-error text-xs font-black">تکمیل این فیلد الزامی است</span>' !!}</div>
                                        </div>
                                    </div>
                                @else
                                    @php
                                        $fieldClass = 'purchase-field w-full h-12 !ring-0 rounded-xl border border-border bg-background px-4 text-sm text-foreground outline-none transition-all focus:border-primary';
                                        $textFields = [
                                            'infoName' => 'نام',
                                            'infoNameFull' => 'نام و نام خانوادگی',
                                            'infoFatherName' => 'نام پدر',
                                            'infoCodeMell' => 'کد ملی',
                                            'infoPlaceOfBirth' => 'محل تولد',
                                            'infoFatherMobile' => 'موبایل پدر',
                                            'infoMotherMobile' => 'موبایل مادر',
                                        ];
                                    @endphp

                                    <div class="grid gap-4 lg:grid-cols-2">
                                        @foreach ($textFields as $model => $label)
                                            <div class="space-y-2" data-purchase-field="{{ $model }}">
                                                <label class="block text-xs font-black text-foreground">{{ $label }}</label>
                                                <input type="text" wire:model="{{ $model }}" class="{{ $fieldClass }}">
                                                @error($model)<div class="mt-1 text-xs font-bold text-error">{{ $message }}</div>@enderror
                                            </div>
                                        @endforeach

                                        <div class="space-y-2" data-purchase-field="infoBirthDate">
                                            <label class="block text-xs font-black text-foreground">تاریخ تولد</label>
                                            <input type="text" readonly :value="birthDate"
                                                   @click="openBirthDatePicker()"
                                                   @keydown.enter.prevent="openBirthDatePicker()"
                                                   @keydown.space.prevent="openBirthDatePicker()"
                                                   inputmode="none" autocomplete="off" aria-haspopup="dialog"
                                                   placeholder="انتخاب تاریخ"
                                                   class="birth-date-trigger {{ $fieldClass }} cursor-pointer"
                                                   dir="ltr">
                                            @error('infoBirthDate')<div class="mt-1 text-xs font-bold text-error">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="purchase-select-surface space-y-2" data-purchase-field="infoGrade">
                                            <label class="block text-xs font-black text-foreground">پایه</label>
                                            <x-ui.select
                                                wire:model.live="infoGrade"
                                                :options="$gradeSelectOptions"
                                                placeholder="انتخاب پایه"
                                                name="infoGrade" />
                                            @error('infoGrade')<div class="mt-1 text-xs font-bold text-error">{{ $message }}</div>@enderror
                                        </div>

                                        @if($selectedGradeRequiresField)
                                            <div class="purchase-select-surface space-y-2" data-purchase-field="infoField">
                                                <label class="block text-xs font-black text-foreground">رشته</label>
                                                <x-ui.select
                                                    wire:model.live="infoField"
                                                    :options="$fieldSelectOptions"
                                                    placeholder="انتخاب رشته"
                                                    name="infoField" />
                                                @error('infoField')<div class="mt-1 text-xs font-bold text-error">{{ $message }}</div>@enderror
                                            </div>
                                        @else
                                            <div class="space-y-2" data-purchase-field="infoField">
                                                <label class="block text-xs font-black text-foreground">رشته</label>
                                                <div class="purchase-field flex h-12 items-center rounded-xl bg-background px-4 text-sm font-bold text-muted">
                                                    پایه نهم رشته ندارد
                                                </div>
                                            </div>
                                        @endif

                                        @if($profileSelectionChanged)
                                            <div class="rounded-2xl border border-primary/20 bg-primary/10 p-4 text-sm leading-7 text-muted lg:col-span-2">
                                                <span class="font-black text-primary">توجه:</span>
                                                اگر پایه‌ات را تغییر بدهی، ممکن است مبلغ نهایی خرید عوض شود. بعد از ذخیره، قیمت نقدی و اقساطی با اطلاعات جدید دوباره محاسبه می‌شود.
                                            </div>
                                        @endif

                                        <div class="purchase-select-surface space-y-2" data-purchase-field="infoStateId">
                                            <label class="block text-xs font-black text-foreground">استان</label>
                                            <x-ui.select
                                                wire:model.live="infoStateId"
                                                :options="$states->toArray()"
                                                placeholder="انتخاب استان"
                                                searchable
                                                search-placeholder="جستجوی استان"
                                                name="infoStateId" />
                                            @error('infoStateId')<div class="mt-1 text-xs font-bold text-error">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="purchase-select-surface space-y-2" wire:key="purchase-city-select-{{ $infoStateId ?: 'none' }}" data-purchase-field="infoCityId">
                                            <label class="block text-xs font-black text-foreground">شهر</label>
                                            <x-ui.select
                                                wire:model="infoCityId"
                                                :options="$cities->toArray()"
                                                :disabled="empty($infoStateId)"
                                                :placeholder="empty($infoStateId) ? 'ابتدا استان را انتخاب کنید' : 'انتخاب شهر'"
                                                searchable
                                                search-placeholder="جستجوی شهر"
                                                name="infoCityId" />
                                            @error('infoCityId')<div class="mt-1 text-xs font-bold text-error">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="space-y-2 lg:col-span-2" data-purchase-field="infoAddress">
                                            <label class="block text-xs font-black text-foreground">آدرس</label>
                                            <textarea wire:model="infoAddress" rows="3" class="purchase-field w-full !ring-0 rounded-xl border border-border bg-background px-4 py-3 text-sm leading-7 text-foreground outline-none transition-all focus:border-primary"></textarea>
                                            @error('infoAddress')<div class="mt-1 text-xs font-bold text-error">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-3 sm:flex-row">
                                        <button wire:click="saveInfo" wire:loading.attr="disabled" wire:target="saveInfo" data-elevated="false"
                                                class="btn-press purchase-button-soft inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-black text-foreground transition hover:border-primary/40 disabled:opacity-60 sm:w-auto">
                                            <span wire:loading.remove wire:target="saveInfo">ذخیره</span>
                                            <span wire:loading wire:target="saveInfo" class="inline-flex items-center gap-2">
                                                <x-ui.spinner size="sm"/>
                                                در حال ذخیره
                                            </span>
                                        </button>
                                        <button wire:click="cancelEditInfo" wire:loading.attr="disabled" wire:target="cancelEditInfo" data-elevated="false"
                                                class="btn-press purchase-button-soft w-full rounded-2xl px-5 py-3 text-sm font-black text-foreground transition hover:border-primary/40 sm:w-auto">
                                            انصراف
                                        </button>
                                    </div>
                                @endif

                                <div class="flex flex-col gap-3 border-t border-border/70 pt-6 sm:flex-row sm:items-center sm:justify-between">
                                    <button wire:click="prevStep" wire:loading.attr="disabled" wire:target="prevStep" data-elevated="false"
                                            class="btn-press purchase-button-soft w-full rounded-2xl px-5 py-3 text-sm font-black text-foreground transition hover:border-primary/40 sm:w-auto">
                                        بازگشت
                                    </button>
                                    @if ($editingInfo)
                                        <button wire:click="saveInfoAndContinue" wire:loading.attr="disabled" wire:target="saveInfoAndContinue" data-elevated="true"
                                                class="btn-press purchase-button inline-flex w-full items-center justify-center gap-2 rounded-2xl px-6 py-3 text-sm font-black transition disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto">
                                            <span wire:loading.remove wire:target="saveInfoAndContinue">ذخیره و ادامه</span>
                                            <span wire:loading wire:target="saveInfoAndContinue" class="inline-flex items-center gap-2">
                                                <x-ui.spinner size="sm"/>
                                                در حال ذخیره
                                            </span>
                                        </button>
                                    @elseif ($requiresInfoCompletion)
                                        <button wire:click="startEditInfo" wire:loading.attr="disabled" wire:target="startEditInfo" data-elevated="true"
                                                class="btn-press purchase-button-warning inline-flex w-full items-center justify-center gap-2 rounded-2xl px-6 py-3 text-sm font-black transition disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto">
                                            ویرایش اطلاعات
                                        </button>
                                    @else
                                        <button wire:click="nextStep" wire:loading.attr="disabled" wire:target="nextStep" data-elevated="true"
                                                class="btn-press purchase-button inline-flex w-full items-center justify-center gap-2 rounded-2xl px-6 py-3 text-sm font-black transition disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto">
                                            <span wire:loading.remove wire:target="nextStep">ادامه و ورود به پرداخت</span>
                                            <span wire:loading wire:target="nextStep" class="inline-flex items-center gap-2">
                                                <x-ui.spinner size="sm"/>
                                                در حال بررسی
                                            </span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if ($step === 3)
                            <div class="space-y-6">
                                <div>
                                    <h2 class="text-xl font-black text-foreground sm:text-2xl">تایید نهایی و پرداخت</h2>
                                    <p class="mt-2 text-sm leading-7 text-muted">قوانین را تایید کنید، در صورت نیاز کد تخفیف نقدی را اعمال کنید و سپس روش پرداخت مناسب را انتخاب کنید.</p>
                                </div>

                                <div class="rounded-[1.5rem] border border-primary/15 bg-primary/10 p-5">
                                    <label class="flex cursor-pointer items-start gap-3 text-sm leading-7 text-foreground">
                                        <input type="checkbox" wire:model.live="agreedToTerms" class="mt-1 h-5 w-5 rounded border-border bg-background text-primary focus:ring-primary">
                                        <span class="font-semibold">
                                            <a href="{{ route('client.terms') }}" target="_blank" class="font-black text-primary hover:underline">قوانین و شرایط</a>
                                            را خوانده‌ام و می‌پذیرم. همچنین می‌دانم در مدل اقساطی، پرداخت‌ها باید به‌ترتیب و در موعد مقرر انجام شوند.
                                        </span>
                                    </label>
                                    @unless ($agreedToTerms)
                                        <p class="mt-3 text-xs font-black text-primary">برای فعال شدن دکمه‌های پرداخت، ابتدا تایید قوانین لازم است.</p>
                                    @endunless
                                </div>

                                <div class="purchase-surface-card rounded-[1.5rem] border border-border p-5">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                                        <div>
                                            <div class="text-sm font-black text-foreground">کد تخفیف نقدی</div>
                                            <p class="mt-1 text-xs leading-6 text-muted">این تخفیف روی مبلغ کل شما اعمال می‌شود و هم پرداخت نقدی و هم اقساط را تحت تأثیر قرار می‌دهد.</p>
                                        </div>
                                        @if ($couponType !== '')
                                            <div class="rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-xs font-black text-primary">
                                                @if ($couponType === 'percentage')
                                                    {{ $toFaDigits($couponValue) }}٪ تخفیف فعال
                                                @else
                                                    {{ $faMoney($couponValue) }} تومان تخفیف فعال
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    @if ($couponType !== '' )
                                        <div class="mt-4 flex flex-col gap-3 rounded-2xl border border-primary/20 bg-primary/10 p-4 sm:flex-row sm:items-center sm:justify-between">
                                            <div class="text-sm font-black text-foreground">{{ $couponNotice }}</div>
                                            <button wire:click="removeCoupon" wire:loading.attr="disabled" wire:target="removeCoupon" data-elevated="false"
                                                    class="btn-press rounded-xl border border-error/20 bg-error/10 px-4 py-2 text-xs font-black text-error transition hover:bg-error/15">
                                                حذف کد
                                            </button>
                                        </div>
                                    @else
                                        <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                                            <input type="text" wire:model="couponCode" placeholder="کد تخفیف را وارد کنید"
                                                   class="purchase-field min-w-0 flex-1 rounded-2xl px-4 py-3 text-sm text-foreground placeholder:text-muted/60">
                                            <button wire:click="applyCoupon" wire:loading.attr="disabled" wire:target="applyCoupon" data-elevated="true"
                                                    class="btn-press purchase-button inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-black transition disabled:opacity-60">
                                                <span wire:loading.remove wire:target="applyCoupon">اعمال کد</span>
                                                <span wire:loading wire:target="applyCoupon" class="inline-flex items-center gap-2">
                                                    <x-ui.spinner size="sm"/>
                                                    در حال بررسی
                                                </span>
                                            </button>
                                        </div>
                                        @if ($couponError)
                                            <div class="mt-2 text-xs font-black text-error">{{ $couponError }}</div>
                                        @endif
                                    @endif
                                </div>

                                <div class="grid gap-4 xl:grid-cols-2">
                                    <div class="purchase-surface-card min-w-0 overflow-hidden rounded-[1.5rem] border border-border p-4 sm:p-5">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <p class="mt-2 text-sm leading-7 text-muted">کل مبلغ را یکجا پرداخت می‌کنید و دسترسی دوره بلافاصله فعال می‌شود.</p>
                                            </div>
                                        </div>
                                        <div class="mt-6 rounded-2xl border border-primary/15 bg-background/85 p-4 text-center">
                                            @if ($couponType !== ''  && $data['full_with_coupon'] !== $data['total'])
                                                <div class="text-sm text-muted line-through"><span class="purchase-amount">{{ $faMoney($data['total']) }}</span> تومان</div>
                                            @endif
                                            <div class="purchase-stable-price mt-2 text-[clamp(1.8rem,4vw,2.6rem)] font-black text-foreground"><span class="purchase-amount">{{ $faMoney($data['full_with_coupon']) }}</span></div>
                                            <div class="mt-1 text-sm font-bold text-primary">مبلغ قابل پرداخت</div>
                                        </div>
                                        <button wire:click="pay" wire:loading.attr="disabled" wire:target="pay" @disabled(! $agreedToTerms) data-elevated="true"
                                                class="btn-press purchase-button mt-6 inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-black transition disabled:cursor-not-allowed disabled:opacity-50">
                                            <span wire:loading.remove wire:target="pay">پرداخت نقدی</span>
                                            <span wire:loading wire:target="pay" class="inline-flex items-center gap-2">
                                                <x-ui.spinner size="sm"/>
                                                در حال اتصال به درگاه
                                            </span>
                                        </button>
                                    </div>

                                    <div class="purchase-surface-card min-w-0 overflow-hidden rounded-[1.5rem] border border-border p-4 sm:p-5">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <div class="text-sm font-black text-foreground">پرداخت اقساطی</div>
                                                <p class="mt-2 text-sm leading-7 text-muted">اگر بخواهید مبلغ را مرحله‌ای پرداخت کنید، این گزینه پیش‌پرداخت و اقساط بعدی را برایتان تنظیم می‌کند.</p>
                                            </div>
                                        </div>

                                        @if (($data['installment_open'] ?? false) && $data['installment_count'] > 0)
                                            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                                                <div class="min-w-0 rounded-2xl border border-primary/15 bg-background/85 p-4 text-center">
                                                    <div class="text-[11px] font-bold text-primary">پیش‌پرداخت</div>
                                                    <div class="purchase-stable-price mt-1 text-[clamp(1.4rem,4vw,2rem)] font-black text-foreground"><span class="purchase-amount">{{ $faMoney($data['initial']) }}</span></div>
                                                </div>
                                                <div class="min-w-0 rounded-2xl border border-border bg-background/85 p-4 text-center">
                                                    <div class="text-[11px] text-muted">هر قسط</div>
                                                    <div class="purchase-stable-price mt-1 text-[clamp(1.35rem,3.8vw,2rem)] font-black text-foreground"><span class="purchase-amount">{{ $faMoney($data['monthly']) }}</span></div>
                                                </div>
                                            </div>
                                            <p class="mt-4 text-sm leading-7 text-muted">
                                                بعد از پیش‌پرداخت، {{ $toFaDigits($data['installment_count']) }} قسط ماهانه برای شما ثبت می‌شود؛ آخرین سررسید قسط {{ $toFaDigits($data['installment_until_label']) }} است.
                                            </p>
                                            <button wire:click="payInstallment" wire:loading.attr="disabled" wire:target="payInstallment" @disabled(! $agreedToTerms) data-elevated="true"
                                                    class="btn-press purchase-button mt-6 inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-black transition disabled:cursor-not-allowed disabled:opacity-50">
                                                <span wire:loading.remove wire:target="payInstallment">شروع با پیش‌پرداخت</span>
                                                <span wire:loading wire:target="payInstallment" class="inline-flex items-center gap-2">
                                                    <x-ui.spinner size="sm"/>
                                                    در حال ساخت طرح
                                                </span>
                                            </button>
                                        @else
                                            <div class="mt-6 rounded-2xl border border-border bg-secondary/60 p-5 text-sm leading-7 text-muted">
                                                @if (! ($data['installment_open'] ?? false))
                                                    از ۱ فروردین تا ۳۱ خرداد پرداخت اقساطی فعال نیست. در این بازه فقط پرداخت نقدی در دسترس است.
                                                @else
                                                    در این مقطع، زمان کافی برای تقسیط تا ۲۰ اسفند باقی نمانده است و فقط پرداخت نقدی در دسترس است.
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex border-t border-border/70 pt-6">
                                    <button wire:click="prevStep" wire:loading.attr="disabled" wire:target="prevStep" data-elevated="false"
                                            class="btn-press purchase-button-soft w-full rounded-2xl px-5 py-3 text-sm font-black text-foreground transition hover:border-primary/40 sm:w-auto">
                                        بازگشت
                                    </button>
                                </div>
                            </div>
                        @endif
                        </div>
                    </section>
                </div>
            </div>
        </div>
    @endif

    @if ($showTrialConfirmModal)
        <div class="fixed inset-0 z-[110] flex items-center justify-center p-4" @keydown.escape.window="$wire.closeTrialConfirm()">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="closeTrialConfirm"></div>

            <div class="purchase-panel relative z-10 w-full max-w-lg rounded-[2rem] p-6 sm:p-8">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-success/20 bg-success/10 text-success">
                    {{-- استثنا: آیکون «رعد/شروع سریع» در دیکشنری موجود نیست --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>

                <div class="mt-5 text-center">
                    <h2 class="text-xl font-black text-foreground sm:text-2xl">شروع یک هفته آزمایشی</h2>
                    <p class="mt-3 text-sm leading-7 text-muted">
                        با انتخاب <span class="font-black text-foreground">یک هفته آزمایشی</span> امکان بازگشت به این تصمیم وجود ندارد و قرار است یک هفته تستی با ما کار کنید.
                        اگر آماده‌ای، همین حالا trial شما شروع می‌شود و وارد مسیر آزمایشی می‌شوی.
                    </p>
                </div>

                <div class="mt-6 rounded-2xl border border-success/20 bg-success/10 p-4 text-sm leading-7 text-foreground">
                    بعد از شروع، شما به صفحهٔ انتظار برای تخصیص پشتیبان هدایت می‌شوید و ادامهٔ مراحل هفتهٔ آزمایشی از همان‌جا انجام می‌شود.
                </div>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <button wire:click="startTrialWeek" wire:loading.attr="disabled" wire:target="startTrialWeek" data-elevated="true"
                            class="btn-press purchase-button-trial inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-black transition disabled:opacity-60">
                        <span wire:loading.remove wire:target="startTrialWeek">بزن بریم یک هفته آزمایشی</span>
                        <span wire:loading wire:target="startTrialWeek" class="inline-flex items-center gap-2">
                            <x-ui.spinner size="sm"/>
                            در حال شروع
                        </span>
                    </button>
                    <button wire:click="closeTrialConfirm" wire:loading.attr="disabled" wire:target="closeTrialConfirm,startTrialWeek" data-elevated="false"
                            class="btn-press purchase-button-soft w-full rounded-2xl px-5 py-3 text-sm font-black text-foreground transition hover:border-primary/40">
                        فعلاً نه
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
