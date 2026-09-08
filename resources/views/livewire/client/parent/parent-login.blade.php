<div class="min-h-screen bg-[#0a0a0f] text-white relative overflow-hidden flex items-center justify-center px-4 py-10" dir="rtl"
     x-data="parentPortalLogin()" x-init="init()">
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background-image: linear-gradient(to right, rgba(59,130,246,0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(59,130,246,0.05) 1px, transparent 1px); background-size: 48px 48px;"></div>
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background: radial-gradient(ellipse 70% 50% at 50% 0%, rgba(59,130,246,0.1) 0%, transparent 70%);"></div>

    <div class="relative z-10 w-full max-w-md">
        <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
            <div class="h-1 w-full" style="background:linear-gradient(to left,#3b82f6,#8b5cf6,#ec4899);"></div>
            <div class="p-7 space-y-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.3);">
                        <svg class="w-7 h-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-white">پنل والدین</h1>
                        <p class="text-xs text-white/40 mt-1">مشاهده وضعیت تحصیلی فرزند شما</p>
                    </div>
                </div>

                @if($otpStep === 1)
                    <p class="text-sm text-white/60 leading-7">
                        برای ورود، <span class="text-blue-400 font-semibold">کد ملی دانش‌آموز</span> و
                        <span class="text-blue-400 font-semibold">شماره موبایل پدر یا مادر</span>
                        (همان شماره‌ای که هنگام ثبت‌نام اعلام شده) را وارد کنید. کد تایید برای همین شماره پیامک می‌شود.
                    </p>

                    <form wire:submit.prevent="login" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-white/70 mb-2">کد ملی دانش‌آموز</label>
                            <input type="text" wire:model="nationalCode" inputmode="numeric" maxlength="10" dir="ltr"
                                   placeholder="0012345678"
                                   class="w-full h-12 rounded-xl px-4 text-sm text-white placeholder-white/25 text-left outline-none focus:ring-2 focus:ring-blue-500/50 transition"
                                   style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);">
                            @error('nationalCode')
                            <p class="text-xs text-red-400 mt-2 leading-6">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-white/70 mb-2">شماره موبایل پدر یا مادر</label>
                            <input type="text" wire:model="mobile" inputmode="numeric" maxlength="11" dir="ltr"
                                   placeholder="09123456789"
                                   class="w-full h-12 rounded-xl px-4 text-sm text-white placeholder-white/25 text-left outline-none focus:ring-2 focus:ring-blue-500/50 transition"
                                   style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);">
                            @error('mobile')
                            <p class="text-xs text-red-400 mt-2 leading-6">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" wire:loading.attr="disabled" wire:target="login"
                                class="w-full h-12 rounded-xl font-bold text-sm text-white transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-60"
                                style="background:linear-gradient(135deg,#3b82f6,#8b5cf6);box-shadow:0 4px 20px rgba(59,130,246,0.3);">
                            <span wire:loading.remove wire:target="login">دریافت کد تایید</span>
                            <span wire:loading wire:target="login">در حال بررسی...</span>
                        </button>
                    </form>
                @else
                    <div class="rounded-xl p-3.5 text-center" style="background:rgba(59,130,246,0.08);border:1px solid rgba(59,130,246,0.2);">
                        <p class="text-xs text-white/70">
                            کد تایید به <span class="font-bold text-blue-400" dir="ltr">{{ $mobile }}</span> پیامک شد
                        </p>
                        <button type="button" wire:click="backToMobileStep" class="text-xs text-blue-400 hover:underline mt-2 font-semibold">تغییر اطلاعات</button>
                    </div>

                    <form wire:submit.prevent="verifyOtp" autocomplete="off" class="space-y-5" wire:key="parent-otp-step-2">
                        <div class="relative"
                             x-data="otpInput({ length: 6, hasServerError: @js($errors->has('otpCode')) })"
                             x-init="init()"
                             @otp-cleared.window="reset()"
                             @otp-error.window="triggerError()"
                             @otp-success.window="triggerSuccess()">

                            <input type="hidden" wire:model="otpCode" x-ref="hidden">

                            <label class="block text-xs font-bold text-white/70 mb-3 text-center">کد ۶ رقمی ارسال‌شده را وارد کنید</label>

                            <div class="flex items-center justify-center gap-2 sm:gap-2.5" dir="ltr"
                                 :class="{ 'shake': status === 'error' }">
                                <template x-for="(digit, index) in digits" :key="index">
                                    <input
                                        type="text"
                                        inputmode="numeric"
                                        autocomplete="one-time-code"
                                        maxlength="1"
                                        data-otp-slot
                                        :value="digits[index]"
                                        :aria-label="'رقم ' + (index + 1) + ' از ' + length"
                                        wire:loading.attr="disabled"
                                        wire:target="verifyOtp"
                                        @input="handleInput($event, index)"
                                        @keydown="handleKeydown($event, index)"
                                        @paste="handlePaste($event)"
                                        @focus="$event.target.select()"
                                        class="otp-slot rounded-xl text-xl sm:text-2xl font-bold font-mono text-white outline-none transition disabled:opacity-50"
                                        :class="{
                                            'is-filled': digit !== '' && status === 'idle',
                                            'is-error': status === 'error',
                                            'is-success': status === 'success',
                                            'is-pop': poppedIndex === index
                                        }"
                                    >
                                </template>
                            </div>

                            @error('otpCode')
                                <p class="text-xs text-red-400 mt-3 text-center leading-6">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-center">
                            <div x-show="countdown > 0" class="flex flex-col items-center gap-2">
                                <p class="text-xs text-white/40">ارسال مجدد تا</p>
                                <div class="w-14 h-14 rounded-full flex items-center justify-center font-black text-base"
                                     style="background:rgba(59,130,246,0.12);border:2px solid rgba(59,130,246,0.4);color:#60a5fa;">
                                    <span x-text="countdown"></span>
                                </div>
                            </div>
                            <button type="button" x-show="countdown === 0" x-cloak wire:click="resendOtp"
                                    wire:loading.attr="disabled" wire:target="resendOtp"
                                    class="text-blue-400 font-semibold hover:underline flex items-center gap-2 text-sm disabled:opacity-50 disabled:no-underline">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                ارسال مجدد کد
                            </button>
                        </div>

                        <button type="submit" wire:loading.attr="disabled" wire:target="verifyOtp"
                                class="w-full h-12 rounded-xl font-bold text-sm text-white transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-60"
                                style="background:linear-gradient(135deg,#3b82f6,#8b5cf6);box-shadow:0 4px 20px rgba(59,130,246,0.3);">
                            <span wire:loading.remove wire:target="verifyOtp">تایید و ورود به پنل والدین</span>
                            <span wire:loading wire:target="verifyOtp">در حال بررسی...</span>
                        </button>
                    </form>
                @endif

                <div class="flex items-center gap-3 px-4 py-3 rounded-xl" style="background:rgba(59,130,246,0.08);border:1px solid rgba(59,130,246,0.2);">
                    <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-white/55 leading-6">اطلاعات نمایش‌داده‌شده مطابق آخرین جلسه مشاوره برگزارشده برای فرزند شماست.</p>
                </div>
            </div>
        </div>
    </div>

    <style>
            .otp-slot {
                width: 2.75rem;
                height: 3.25rem;
                text-align: center;
                caret-color: #60a5fa;
                background: rgba(255,255,255,0.05);
                border: 1px solid rgba(255,255,255,0.12);
            }
            @media (min-width: 400px) {
                .otp-slot { width: 3rem; height: 3.5rem; }
            }
            .otp-slot:focus { border-color: rgba(59,130,246,0.6); box-shadow: 0 0 0 3px rgba(59,130,246,0.15); }
            .otp-slot.is-filled {
                background: rgba(59,130,246,0.1);
                border-color: rgba(59,130,246,0.5);
            }
            .otp-slot.is-error {
                border-color: #f87171 !important;
                color: #f87171;
                box-shadow: 0 0 0 3px rgba(248,113,113,0.15) !important;
            }
            .otp-slot.is-success {
                border-color: #34d399 !important;
                color: #34d399;
                box-shadow: 0 0 0 3px rgba(52,211,153,0.15) !important;
            }
            @keyframes otp-pop {
                0% { transform: scale(1); }
                40% { transform: scale(1.12); }
                100% { transform: scale(1); }
            }
            .otp-slot.is-pop { animation: otp-pop 0.18s ease-out; }

            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
            .shake { animation: shake 0.4s ease; }

            @media (prefers-reduced-motion: reduce) {
                .otp-slot.is-pop, .shake { animation: none; }
            }
    </style>

    @push('script')
        <script>
            function otpInput(config) {
                return {
                    length: config.length || 6,
                    digits: Array(config.length || 6).fill(''),
                    status: 'idle', // idle | error | success
                    hasServerError: config.hasServerError || false,
                    poppedIndex: -1,

                    get code() {
                        return this.digits.join('');
                    },

                    init() {
                        if (this.hasServerError) {
                            this.triggerError();
                        }
                        this.$nextTick(() => this.focusSlot(0));
                    },

                    toEnglishDigits(str) {
                        return str
                            .replace(/[۰-۹]/g, (d) => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
                            .replace(/[٠-٩]/g, (d) => '٠١٢٣٤٥٦٧٨٩'.indexOf(d));
                    },

                    sync() {
                        this.$refs.hidden.value = this.code;
                        this.$refs.hidden.dispatchEvent(new Event('input'));
                    },

                    pop(index) {
                        this.poppedIndex = index;
                        setTimeout(() => {
                            if (this.poppedIndex === index) this.poppedIndex = -1;
                        }, 180);
                    },

                    maybeSubmit() {
                        if (this.status !== 'idle') return;
                        if (this.digits.every((d) => d !== '')) {
                            this.$nextTick(() => this.$wire.call('verifyOtp'));
                        }
                    },

                    applySequence(seq, startIndex) {
                        const chars = seq.split('');
                        for (let i = 0; i < chars.length && (startIndex + i) < this.length; i++) {
                            this.digits[startIndex + i] = chars[i];
                        }
                        this.sync();
                        this.pop(Math.min(startIndex + chars.length - 1, this.length - 1));
                        const nextIndex = Math.min(startIndex + chars.length, this.length - 1);
                        this.focusSlot(nextIndex);
                        this.maybeSubmit();
                    },

                    handleInput(e, index) {
                        if (this.status === 'error') this.status = 'idle';

                        let val = this.toEnglishDigits(e.target.value).replace(/[^0-9]/g, '');

                        if (val.length > 1) {
                            e.target.value = this.digits[index] || '';
                            this.applySequence(val, index);
                            return;
                        }

                        this.digits[index] = val;
                        e.target.value = val;
                        this.sync();

                        if (val) {
                            this.pop(index);
                            if (index < this.length - 1) this.focusSlot(index + 1);
                        }

                        this.maybeSubmit();
                    },

                    handleKeydown(e, index) {
                        if (e.key === 'Backspace') {
                            e.preventDefault();
                            if (this.status === 'error') this.status = 'idle';
                            if (this.digits[index]) {
                                this.digits[index] = '';
                                this.sync();
                            } else if (index > 0) {
                                this.digits[index - 1] = '';
                                this.sync();
                                this.focusSlot(index - 1);
                            }
                        } else if (e.key === 'ArrowLeft') {
                            e.preventDefault();
                            if (index > 0) this.focusSlot(index - 1);
                        } else if (e.key === 'ArrowRight') {
                            e.preventDefault();
                            if (index < this.length - 1) this.focusSlot(index + 1);
                        } else if (e.key === 'Home') {
                            e.preventDefault();
                            this.focusSlot(0);
                        } else if (e.key === 'End') {
                            e.preventDefault();
                            this.focusSlot(this.length - 1);
                        }
                    },

                    handlePaste(e) {
                        e.preventDefault();
                        const pasted = (e.clipboardData || window.clipboardData).getData('text');
                        const cleaned = this.toEnglishDigits(pasted).replace(/[^0-9]/g, '').slice(0, this.length);
                        if (!cleaned) return;
                        if (this.status === 'error') this.status = 'idle';
                        this.digits = Array(this.length).fill('');
                        this.applySequence(cleaned, 0);
                    },

                    focusSlot(i) {
                        this.$nextTick(() => {
                            const el = this.$root.querySelectorAll('[data-otp-slot]')[i];
                            if (el) el.focus();
                        });
                    },

                    reset() {
                        this.digits = Array(this.length).fill('');
                        this.status = 'idle';
                        this.sync();
                        this.focusSlot(0);
                    },

                    triggerError() {
                        this.status = 'error';
                        setTimeout(() => {
                            this.digits = Array(this.length).fill('');
                            this.status = 'idle';
                            this.sync();
                            this.focusSlot(0);
                        }, 550);
                    },

                    triggerSuccess() {
                        this.status = 'success';
                    }
                }
            }

            function parentPortalLogin() {
                return {
                    countdown: 0,
                    timer: null,

                    init() {
                        this.countdown = this.$wire.countdown || 0;

                        if (this.countdown > 0) {
                            this.startCountdown(this.countdown);
                        }

                        Livewire.on('start-countdown', () => {
                            this.startCountdown(this.$wire.countdown || 0);
                        });
                    },

                    startCountdown(seconds) {
                        if (this.timer) clearInterval(this.timer);
                        this.countdown = seconds;
                        this.$wire.set('countdown', this.countdown, false);

                        if (this.countdown <= 0) {
                            return;
                        }

                        this.timer = setInterval(() => {
                            if (this.countdown > 0) {
                                this.countdown--;
                                this.$wire.set('countdown', this.countdown, false);
                            } else {
                                clearInterval(this.timer);
                                @this.call('countdownFinished');
                            }
                        }, 1000);
                    }
                }
            }
        </script>
    @endpush
</div>
