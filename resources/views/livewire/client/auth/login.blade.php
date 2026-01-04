<div class="min-h-screen flex flex-col lg:flex-row bg-background p-5">

    @push('link')

        <style>

            .auth-bg {

                background: linear-gradient(135deg, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.5)),
                url('/client/test.JPG') center/cover no-repeat;

            }


            .shadow-soft {

                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);

            }


            .dark .shadow-soft-dark {

                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);

            }


            .input-icon {

                left: 12px;
                bottom: 6px;

            }


            .password-toggle {

                left: 12px;
                bottom: 6px;

            }


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


            @keyframes spin {

                to {
                    transform: rotate(360deg);
                }

            }


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


            .fade-in {

                animation: fadeIn 0.3s ease-in-out;

            }


            @keyframes fadeIn {

                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }

            }

        </style>

    @endpush



    <!-- Image section -->

    <div class="lg:w-1/2 auth-bg relative hidden lg:flex items-center justify-center p-12">

        <div class="absolute inset-0 bg-black/30 dark:bg-black/50"></div>

        <div class="relative z-10 text-white text-center max-w-md">


            <h2 class="font-black sm:text-5xl text-3xl text-foreground mb-5">SDFR یکی از برترین مجموعه های کشور است !</h2>

{{--            <p class="text-lg mb-6">--}}
{{--            --}}
{{--            </p>--}}

        </div>

    </div>


    <!-- Login form section -->

    <div class="lg:w-1/2 flex items-center justify-center p-6">

        <div class="w-full max-w-md">

            <!-- Mobile logo -->

            <div class="flex items-center justify-center mb-8 lg:hidden">


            </div>


            <div class="bg-gradient-to-b from-secondary to-background space-y-5 px-5 pb-5 rounded-3xl shadow-soft dark:shadow-soft-dark  border border-border">

                <div class="bg-background rounded-b-3xl space-y-2 p-5 " style="    text-align: center;">
                    <a href="https://sdfr.me" class="inline-flex items-center gap-2 text-primary">

                        <img src="/client/assets/images/theme/intro/header.png" style="width: 100px;">

                    </a>
                </div>


                <!-- Login method selection tabs -->

                <div class="flex mb-6 rounded-xl overflow-hidden border border-border">

                    <button type="button"

                            wire:click="switchMethod('password')"

                            class="tab-button flex-1 py-3 px-4 text-sm font-medium {{ $loginMethod === 'password' ? 'active' : '' }}">

                        ورود با رمز عبور

                    </button>

                    <button type="button"

                            wire:click="switchMethod('otp')"

                            class="tab-button flex-1 py-3 px-4 text-sm font-medium {{ $loginMethod === 'otp' ? 'active' : '' }}">

                        ورود با پیامک

                    </button>

                </div>


                <!-- Error message -->

                @if($errorMessage)

                    <div
                        class="mb-4 p-3 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-xl text-red-600 dark:text-red-400 text-sm">

                        {{ $errorMessage }}

                    </div>

                @endif
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center gap-1">
                        <div class="w-1 h-1 bg-foreground rounded-full"></div>
                        <div class="w-2 h-2 bg-foreground rounded-full"></div>
                    </div>
                    <div class="font-black text-foreground">ورود به پرتال</div>
                </div>


                <!-- Password login form -->

                @if($loginMethod === 'password')

                    <form wire:submit.prevent="loginWithPassword" class="space-y-5" x-data="{ showPassword: false }">

                        <!-- Mobile -->

                        <div>

                            <label for="mobile" class="block text-sm font-medium text-foreground mb-2">شماره
                                موبایل</label>

                            <div class="relative">

                                <input type="tel" id="mobile"

                                       wire:model.live="mobile"

                                       maxlength="11"
                                       dir="rtl"

                                       class="w-full ps-4 pe-10 py-3 border border-border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-secondary text-foreground placeholder:text-muted @error('mobile') border-red-500 @enderror"

                                       placeholder="09123456789">

                                <div class="absolute input-icon top-1/2 transform -translate-y-1/2 text-muted">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>

                                    </svg>

                                </div>

                            </div>

                            @error('mobile')

                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>

                            @enderror

                        </div>


                        <!-- Password -->

                        <div>

                            <label for="password" class="block text-sm font-medium text-foreground mb-2">رمز
                                عبور</label>

                            <div class="relative">

                                <input :type="showPassword ? 'text' : 'password'" id="password"

                                       wire:model="password"

                                       class="w-full ps-10 pe-4 py-3 border border-border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-secondary text-foreground placeholder:text-muted @error('password') border-red-500 @enderror"

                                       placeholder="رمز عبور خود را وارد کنید">

                                <button type="button" @click="showPassword = !showPassword"
                                        class="absolute password-toggle top-1/2 transform -translate-y-1/2 text-muted hover:text-foreground">

                                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>

                                    </svg>

                                    <svg x-show="showPassword" style="display: none;" xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>

                                    </svg>

                                </button>

                            </div>

                            @error('password')

                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>

                            @enderror

                        </div>


                        <!-- Remember me & Forgot password -->

                        <div class="flex items-center justify-between">

                            <div class="flex items-center">

                                <input id="remember-me" type="checkbox"

                                       wire:model="rememberMe"

                                       class="w-4 h-4 form-radio !ring-0 !ring-offset-0 bg-border border-0">

                                <label for="remember-me" class="ms-2 block text-sm text-foreground cursor-pointer">مرا
                                    به خاطر بسپار</label>

                            </div>

                            <div class="text-sm">

                                <a href="{{ route('client.auth.forgotPassword') }}"
                                   class="font-medium text-primary hover:underline">فراموشی رمز عبور</a>

                            </div>

                        </div>


                        <!-- Login button -->

                        <div>

                            <button type="submit"

                                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all disabled:opacity-50"

                                    wire:loading.attr="disabled"

                                    wire:target="loginWithPassword">

                                <span wire:loading.remove wire:target="loginWithPassword">ورود به حساب</span>

                                <span wire:loading wire:target="loginWithPassword" class="flex items-center gap-2">

                                <span class="loading-spinner"></span>

                                در حال ورود...

                            </span>

                                <svg wire:loading.remove wire:target="loginWithPassword"
                                     xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ms-2" viewBox="0 0 20 20"
                                     fill="currentColor">

                                    <path fill-rule="evenodd"
                                          d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"
                                          clip-rule="evenodd"/>

                                </svg>

                            </button>

                        </div>

                    </form>

                @endif



                <!-- OTP login form -->

                @if($loginMethod === 'otp')

                    <div x-data="countdownTimer()" x-init="@if($otpStep === 2) startCountdown(90) @endif">

                        @if($otpStep === 1)

                            <!-- Step 1: Enter mobile -->

                            <form wire:submit.prevent="sendOtp" class="space-y-5">

                                <div>

                                    <label for="otpMobile" class="block text-sm font-medium text-foreground mb-2">شماره
                                        موبایل</label>

                                    <div class="relative">

                                        <input type="tel" id="otpMobile"

                                               wire:model.live="otpMobile"

                                               maxlength="11"

                                               class="w-full ps-4 pe-10 py-3 border border-border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-secondary text-foreground placeholder:text-muted @error('mobile') border-red-500 @enderror"
                                                dir="rtl"
                                               placeholder="09123456789">

                                        <div class="absolute input-icon top-1/2 transform -translate-y-1/2 text-muted">

                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>

                                            </svg>

                                        </div>

                                    </div>

                                    @error('mobile')

                                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>

                                    @enderror

                                    <p class="mt-3 text-xs text-muted">کد تایید به این شماره ارسال خواهد شد</p>

                                </div>


                                <div>

                                    <button type="submit"

                                            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all disabled:opacity-50"

                                            wire:loading.attr="disabled"

                                            wire:target="sendOtp">

                                        <span wire:loading.remove wire:target="sendOtp">دریافت کد تایید</span>

                                        <span wire:loading wire:target="sendOtp" class="flex items-center gap-2">

                                    <span class="loading-spinner"></span>

                                    در حال ارسال...

                                </span>

                                        <svg wire:loading.remove wire:target="sendOtp"
                                             xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ms-2" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>

                                        </svg>

                                    </button>

                                </div>

                            </form>

                        @else

                            <!-- Step 2: Enter OTP code -->

                            <form wire:submit.prevent="verifyOtp" class="space-y-5">

                                <div class="text-center mb-4">

                                    <p class="text-sm text-muted">کد تایید به شماره <span
                                            class="text-foreground font-medium" dir="ltr">{{ $otpMobile }}</span> ارسال
                                        شد</p>

                                    <button type="button" wire:click="backToMobileStep"
                                            class="text-xs text-primary hover:underline mt-1">تغییر شماره
                                    </button>

                                </div>


                                <div>

                                    <label for="otpCode" class="block text-sm font-medium text-foreground mb-2">کد
                                        تایید</label>

                                    <div class="relative">

                                        <input type="tel" id="otpCode"

                                               wire:model.live="otpCode"

                                               maxlength="6"

                                               class="w-full ps-4 pe-10 py-3 border border-border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-secondary text-foreground placeholder:text-muted text-center text-lg tracking-widest @error('code') border-red-500 @enderror"

                                               placeholder="------">

                                        <div class="absolute input-icon top-1/2 transform -translate-y-1/2 text-muted">

                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>

                                            </svg>

                                        </div>

                                    </div>

                                    @error('code')

                                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>

                                    @enderror

                                </div>


                                <!-- Countdown Timer -->

                                <div class="text-center">

                                    <template x-if="countdown > 0">

                                        <div class="flex flex-col items-center gap-2">

                                            <p class="text-sm text-muted">ارسال مجدد کد تا</p>

                                            <div class="countdown-circle">

                                                <span x-text="countdown"></span>

                                            </div>

                                            <p class="text-xs text-muted">ثانیه</p>

                                        </div>

                                    </template>

                                    <template x-if="countdown <= 0">

                                        <button type="button"

                                                wire:click="resendOtp"

                                                @click="startCountdown(90)"

                                                class="text-primary hover:text-primary/80 font-medium text-sm transition-colors disabled:opacity-50"

                                                wire:loading.attr="disabled"

                                                wire:target="resendOtp">

                                            <span wire:loading.remove wire:target="resendOtp">ارسال مجدد کد تایید</span>

                                            <span wire:loading wire:target="resendOtp"
                                                  class="flex items-center gap-2 justify-center">

                                        <span class="loading-spinner"></span>

                                        در حال ارسال...

                                    </span>

                                        </button>

                                    </template>

                                </div>


                                <div>

                                    <button type="submit"

                                            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all disabled:opacity-50"

                                            wire:loading.attr="disabled"

                                            wire:target="verifyOtp">

                                        <span wire:loading.remove wire:target="verifyOtp">تایید و ورود</span>

                                        <span wire:loading wire:target="verifyOtp" class="flex items-center gap-2">

                                    <span class="loading-spinner"></span>

                                    در حال بررسی...

                                </span>

                                        <svg wire:loading.remove wire:target="verifyOtp"
                                             xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ms-2" viewBox="0 0 20 20"
                                             fill="currentColor">

                                            <path fill-rule="evenodd"
                                                  d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"
                                                  clip-rule="evenodd"/>

                                        </svg>

                                    </button>

                                </div>

                            </form>

                        @endif

                    </div>

                @endif



                <!-- Registration link -->

                <div class="mt-6 text-center">

                    <p class="text-sm text-muted">

                        حساب کاربری ندارید؟

                        <a href="{{ route('client.auth.signup') }}" class="font-medium text-primary hover:underline">ثبت
                            نام کنید</a>

                    </p>

                </div>

            </div>


            <!-- Terms footer -->

            <div class="bg-secondary rounded-xl space-y-5 p-5 mt-3">
                <div class="font-medium text-xs text-center text-muted">
                    ورود شما به معنای پذیرش <a href="https://sdfr.me/terms" class="text-foreground transition-colors hover:text-primary hover:underline">شرایط</a>
                    و
                    <a href="https://sdfr.me/terms" class="text-foreground transition-colors hover:text-primary hover:underline">قوانین
                        حریم خصوصی</a> است.
                </div>
            </div>

        </div>

    </div>


    @push('script')

        <script>

            function countdownTimer() {

                return {

                    countdown: 0,

                    timer: null,


                    startCountdown(seconds) {

                        // Clear any existing timer

                        if (this.timer) {

                            clearInterval(this.timer);

                        }


                        this.countdown = seconds;


                        this.timer = setInterval(() => {

                            if (this.countdown > 0) {

                                this.countdown--;

                            } else {

                                clearInterval(this.timer);

                            }

                        }, 1000);

                    }

                }

            }


            // Listen for Livewire events to start countdown

            document.addEventListener('livewire:init', () => {

                Livewire.on('start-countdown', (event) => {

                    // The countdown will be started by Alpine.js

                });

            });

        </script>

    @endpush

</div>
