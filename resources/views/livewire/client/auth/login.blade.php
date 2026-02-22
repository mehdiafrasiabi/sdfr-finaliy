<div class="min-h-screen flex flex-col lg:flex-row bg-background p-0" x-data="loginForm()">

    @push('link')
        <style>
            .auth-hero {
                background: linear-gradient(135deg, rgba(0,0,0,.25), rgba(0,0,0,.25)),
                url('/client/auth-illustration.webp') center / cover no-repeat;
            }
            .auth-hero-inner { min-height: 210px; }
            @media (min-width: 1024px) { .auth-hero-inner { min-height: 100vh; } }

            .shadow-soft {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }
            .dark .shadow-soft-dark {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
            }

            .input-icon { left: 12px; }
            .password-toggle { left: 12px; }

            .tab-button {
                transition: all 0.3s ease;
                background: transparent;
                color: hsl(var(--muted));
            }
            .tab-button.active {
                background: hsl(var(--primary));
                color: white;
            }
            .tab-button:not(.active):hover {
                background: hsl(var(--secondary));
            }

            .loading-spinner {
                border: 2px solid transparent;
                border-top-color: currentColor;
                border-radius: 50%;
                width: 16px;
                height: 16px;
                animation: spin 0.8s linear infinite;
            }
            @keyframes spin { to { transform: rotate(360deg); } }

            .countdown-circle {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: hsl(var(--secondary));
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                font-size: 18px;
                color: hsl(var(--primary));
                border: 3px solid hsl(var(--primary));
            }

            .form-input {
                transition: all 0.3s ease;
            }
            .form-input:focus {
                border-color: hsl(var(--primary));
                box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1);
            }
        </style>
    @endpush

    <!-- Hero Section -->
    <div class="lg:w-1/2 auth-hero relative flex items-stretch justify-center overflow-hidden rounded-3xl">
        <div class="absolute inset-0 bg-black/10 dark:bg-black/30"></div>
        <div class="relative z-10 w-full auth-hero-inner flex flex-row items-center justify-center gap-4 lg:flex-col text-center px-4 py-6 lg:px-6 lg:py-10">
            <div class="relative flex items-center justify-center shrink-0">
                <img src="/client/authenticator1.png" alt="" class="max-w-[120px] sm:max-w-[140px] lg:max-w-[420px] h-auto drop-shadow-2xl"/>
            </div>
            <div class="lg:mt-6 max-w-md text-right lg:text-center">
                <h2 class="font-black text-white text-lg sm:text-xl lg:text-4xl leading-relaxed">خوش آمدید</h2>
                <p class="mt-2 lg:mt-3 text-white/90 text-xs sm:text-sm lg:text-base leading-6 lg:leading-7">
                    برای ورود به حساب کاربری، لطفاً اطلاعات خود را وارد نمایید.
                </p>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="lg:w-1/2 flex items-center justify-center p-6">
        <div class="w-full max-w-md">
            <div class="bg-gradient-to-b from-secondary to-background space-y-5 px-5 pb-5 rounded-3xl shadow-soft dark:shadow-soft-dark border border-border">

                <!-- Header -->
                <div class="bg-background rounded-b-3xl space-y-2 p-5 text-center">
                    <a href="https://sdfr.me" class="inline-flex items-center gap-2 text-primary">
                        <img src="/client/assets/images/theme/intro/header.png" style="width: 100px;" alt="Logo">
                    </a>
                </div>

                <!-- Tabs -->
                <div class="flex mb-6 rounded-xl overflow-hidden border border-border">
                    <button type="button" wire:click="switchMethod('password')" class="tab-button flex-1 py-3 px-4 text-sm font-medium {{ $loginMethod === 'password' ? 'active' : '' }}">
                        ورود با رمز عبور
                    </button>
                    <button type="button" wire:click="switchMethod('otp')" class="tab-button flex-1 py-3 px-4 text-sm font-medium {{ $loginMethod === 'otp' ? 'active' : '' }}">
                        ورود با پیامک
                    </button>
                </div>

                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center gap-1">
                        <div class="w-1 h-1 bg-foreground rounded-full"></div>
                        <div class="w-2 h-2 bg-foreground rounded-full"></div>
                    </div>
                    <div class="font-black text-foreground">ورود به پرتال</div>
                </div>

                <!-- Password Login -->
                @if($loginMethod === 'password')
                    <form wire:submit.prevent="loginWithPassword" class="space-y-5">
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-foreground">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                شماره موبایل
                            </label>
                            <div class="relative">
                                <input
                                    wire:model.live="mobile"
                                    class="form-input w-full py-3 px-4 pl-12 rounded-xl border border-border bg-secondary text-foreground focus:outline-none @error('mobile') border-red-500 @enderror"
                                    type="tel"
                                    inputmode="numeric"
                                />
                                <div class="absolute input-icon top-1/2 transform -translate-y-1/2 text-muted">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('mobile') <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-foreground">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                رمز عبور
                            </label>
                            <div class="relative">
                                <input x-bind:type="showPassword ? 'text' : 'password'" wire:model.defer="password"
                                       class="form-input w-full py-3 px-4 pl-12 rounded-xl border border-border bg-secondary text-foreground focus:outline-none @error('password') border-red-500 @enderror"
                                       placeholder="********">
                                <button type="button" @click="showPassword = !showPassword" class="absolute password-toggle top-1/2 transform -translate-y-1/2 text-muted hover:text-foreground transition-colors">
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('password') <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="rememberMe" class="w-4 h-4 rounded border-border">
                                <span class="text-sm text-foreground">مرا به خاطر بسپار</span>
                            </label>
                            <a href="{{ route('client.auth.forgotPassword') }}" class="text-sm font-medium text-primary hover:underline">فراموشی رمز عبور</a>
                        </div>

                        <button type="submit" wire:loading.attr="disabled" wire:target="loginWithPassword"
                                class="w-full py-3 rounded-xl bg-primary text-primary-foreground font-medium hover:opacity-90 transition-all duration-300 flex items-center justify-center gap-2">
                            <span wire:loading.remove wire:target="loginWithPassword">
                                ورود به حساب
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                            </span>
                            <span wire:loading wire:target="loginWithPassword" class="inline-flex items-center gap-2">
                                <span class="loading-spinner"></span>
                                در حال ورود...
                            </span>
                        </button>
                    </form>
                @endif

                <!-- OTP Login -->
                @if($loginMethod === 'otp')
                    @if($otpStep === 1)
                        <form wire:submit.prevent="sendOtp" class="space-y-5">
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-sm font-medium text-foreground">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    شماره موبایل
                                </label>
                                <input type="tel" wire:model.live="otpMobile" maxlength="11" dir="ltr"
                                       class="form-input w-full py-3 px-4 rounded-xl border border-border bg-secondary text-foreground focus:outline-none @error('mobile') border-red-500 @enderror"
                                       placeholder="09123456789">
                                @error('mobile') <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p> @enderror
                                <p class="mt-2 text-xs text-muted">کد تایید به این شماره ارسال خواهد شد</p>
                            </div>

                            <button type="submit" wire:loading.attr="disabled" wire:target="sendOtp"
                                    class="w-full py-3 rounded-xl bg-primary text-primary-foreground font-medium hover:opacity-90 transition-all duration-300 flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="sendOtp">
                                    دریافت کد تایید
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                    </svg>
                                </span>
                                <span wire:loading wire:target="sendOtp" class="inline-flex items-center gap-2">
                                    <span class="loading-spinner"></span>
                                    در حال ارسال...
                                </span>
                            </button>
                        </form>
                    @else
                        <form wire:submit.prevent="verifyOtp" class="space-y-5">
                            <div class="bg-secondary rounded-xl p-4 text-sm text-foreground border border-border">
                                <p class="text-center">کد تایید به شماره <span class="font-bold" dir="ltr">{{ $otpMobile }}</span> ارسال شد</p>
                                <button type="button" wire:click="backToMobileStep" class="text-xs text-primary hover:underline mt-2 block mx-auto">تغییر شماره</button>
                            </div>

                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-sm font-medium text-foreground">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    کد تایید
                                </label>
                                <input type="tel" wire:model.live="otpCode" maxlength="6" dir="ltr"
                                       class="form-input w-full py-3 px-4 rounded-xl border border-border bg-secondary text-foreground focus:outline-none text-center text-2xl font-bold tracking-widest @error('code') border-red-500 @enderror"
                                       placeholder="• • • • • •">
                                @error('code') <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p> @enderror
                            </div>

                            <div class="text-center">
                                <div x-show="countdown > 0" class="flex flex-col items-center gap-2">
                                    <p class="text-sm text-muted">ارسال مجدد کد تا</p>
                                    <div class="countdown-circle">
                                        <span x-text="countdown"></span>
                                    </div>
                                </div>
                                <button type="button" x-show="countdown === 0" x-cloak wire:click="resendOtp" @click="startCountdown(90)"
                                        class="text-primary font-medium hover:underline flex items-center justify-center gap-2 mx-auto">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    ارسال مجدد کد
                                </button>
                            </div>

                            <button type="submit" wire:loading.attr="disabled" wire:target="verifyOtp"
                                    class="w-full py-3 rounded-xl bg-primary text-primary-foreground font-medium hover:opacity-90 transition-all duration-300 flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="verifyOtp">
                                    تایید و ورود
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </span>
                                <span wire:loading wire:target="verifyOtp" class="inline-flex items-center gap-2">
                                    <span class="loading-spinner"></span>
                                    در حال بررسی...
                                </span>
                            </button>
                        </form>
                    @endif
                @endif

                <div class="mt-6 text-center text-sm text-muted">
                    حساب کاربری ندارید؟
                    <a href="{{ route('client.auth.signup') }}" class="font-medium text-primary hover:underline">ثبت نام کنید</a>
                </div>
            </div>

            <div class="bg-secondary rounded-xl space-y-5 p-5 mt-3">
                <div class="font-medium text-xs text-center text-muted">
                    ورود شما به معنای پذیرش <a href="https://sdfr.me/terms" class="text-foreground hover:text-primary hover:underline">شرایط</a> و
                    <a href="https://sdfr.me/terms" class="text-foreground hover:text-primary hover:underline">قوانین حریم خصوصی</a> است.
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            function loginForm() {
                return {
                    showPassword: false,
                    countdown: 0,
                    timer: null,

                    init() {
                        Livewire.on('start-countdown', () => {
                            this.startCountdown(90);
                        });
                    },

                    startCountdown(seconds) {
                        if (this.timer) clearInterval(this.timer);
                        this.countdown = seconds;
                        this.timer = setInterval(() => {
                            if (this.countdown > 0) {
                                this.countdown--;
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
