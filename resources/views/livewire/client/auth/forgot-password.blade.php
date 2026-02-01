<div class="min-h-screen flex flex-col lg:flex-row bg-background p-0">

    @push('link')
        <style>
            .auth-hero {
                /* بک‌گراند اصلی */
                background: linear-gradient(135deg, rgba(0,0,0,.25), rgba(0,0,0,.25)),
                url('/client/auth-illustration.webp') center / cover no-repeat;
            }

            /* برای اینکه روی موبایل هم تصویر “به صفحه بچسبه” و خوب دیده بشه */
            .auth-hero-inner {
                min-height: 210px; /* موبایل */
            }

            @media (min-width: 1024px) {
                .auth-hero-inner {
                    min-height: 100vh; /* دسکتاپ تمام قد */
                }
            }
        </style>

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



        <!-- Image section - MOBILE HORIZONTAL LAYOUT -->
        <div class="lg:w-1/2 auth-hero relative flex items-stretch justify-center overflow-hidden rounded-none rounded-3xl">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black/10 dark:bg-black/30"></div>

            <!-- محتوا: توی موبایل افقی (flex-row)، توی دسکتاپ عمودی (lg:flex-col) -->
            <div class="relative z-10 w-full auth-hero-inner
                flex flex-row items-center justify-center gap-4
                lg:flex-col
                text-center px-4 py-6 lg:px-6 lg:py-10">

                <!-- تصویر - توی موبایل کوچک‌تر -->
                <div class="relative flex items-center justify-center shrink-0">
                    <img
                        src="/client/authenticator1.png"
                        alt=""
                        class="max-w-[120px] sm:max-w-[140px] lg:max-w-[420px] h-auto drop-shadow-2xl"
                    />
                </div>

                <!-- متن - توی موبایل text-right -->
                <div class="lg:mt-6 max-w-md text-right lg:text-center">
                    <h2 class="font-black text-white text-lg sm:text-xl lg:text-4xl leading-relaxed">
                        کاربر محترم SDFR
                    </h2>

                    <p class="mt-2 lg:mt-3 text-white/90 text-xs sm:text-sm lg:text-base leading-6 lg:leading-7">
                        به دلیل اختلال در ارسال پیامک‌ها، بعد از ورود، شناسایی دو عاملی خود را از بخش امنیت فعال کنید.
                    </p>
                </div>
            </div>
        </div>

    <!-- Recovery form section -->

    <div class="lg:w-1/2 flex items-center justify-center p-6">

        <div class="w-full max-w-md">


            <div
                class="bg-gradient-to-b from-secondary to-background space-y-5 px-5 pb-5 rounded-3xl shadow-soft dark:shadow-soft-dark  border border-border">

                <div class="bg-background rounded-b-3xl space-y-2 p-5 " style="    text-align: center;">
                    <a href="https://sdfr.me" class="inline-flex items-center gap-2 text-primary">

                        <img src="/client/assets/images/theme/intro/header.png" style="width: 100px;">

                    </a>
                </div>

                <!-- Error message -->

                @if($errorMessage)

                    <div
                        class="mb-4 p-3 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-xl text-red-600 dark:text-red-400 text-sm">

                        {{ $errorMessage }}

                    </div>

                @endif



                <!-- Step 1: Request recovery code -->

                @if($step === 1)

                    <div>

                        <div class="flex items-center gap-3 mb-5">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">بازیابی رمز عبور</div>
                        </div>


                        <form wire:submit.prevent="sendCode" class="space-y-5">

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

                                <p class="mt-3 text-xs text-muted">کد بازیابی به این شماره ارسال خواهد شد</p>

                            </div>


                            <!-- Submit button -->

                            <div>

                                <button type="submit"

                                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all disabled:opacity-50"

                                        wire:loading.attr="disabled"

                                        wire:target="sendCode">

                                    <span wire:loading.remove wire:target="sendCode">ارسال کد بازیابی</span>

                                    <span wire:loading wire:target="sendCode" class="flex items-center gap-2">

                                    <span class="loading-spinner"></span>

                                    در حال ارسال...

                                </span>

                                    <svg wire:loading.remove wire:target="sendCode" xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5 ms-2" viewBox="0 0 20 20" fill="currentColor">

                                        <path
                                            d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>

                                    </svg>

                                </button>

                            </div>

                        </form>

                    </div>

                @endif



                <!-- Step 2: Verify code -->

                @if($step === 2)

                    <div x-data="countdownTimer()" x-init="startCountdown(90)">

                        <div class="flex items-center gap-3 mb-5">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">اعتبار سنجی</div>
                        </div>
                        <div class="text-center mb-8">

                            <p class="text-muted">کد ارسال شده به <span class="font-medium text-foreground"
                                                                        dir="ltr">{{ $mobile }}</span> را وارد کنید</p>

                            <button type="button" wire:click="backToMobileStep"
                                    class="text-xs text-primary hover:underline mt-1">تغییر شماره
                            </button>

                        </div>


                        <form wire:submit.prevent="verifyCode" class="space-y-5">

                            <!-- Code input -->

                            <div>

                                <label for="code" class="block text-sm font-medium text-foreground mb-2">کد
                                    تایید</label>

                                <div class="relative">

                                    <input type="tel" id="code"

                                           wire:model.live="code"

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

                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>

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

                                            wire:click="resendCode"

                                            @click="startCountdown(90)"

                                            class="text-primary hover:text-primary/80 font-medium text-sm transition-colors disabled:opacity-50"

                                            wire:loading.attr="disabled"

                                            wire:target="resendCode">

                                        <span wire:loading.remove wire:target="resendCode">ارسال مجدد کد</span>

                                        <span wire:loading wire:target="resendCode"
                                              class="flex items-center gap-2 justify-center">

                                        <span class="loading-spinner"></span>

                                        در حال ارسال...

                                    </span>

                                    </button>

                                </template>

                            </div>


                            <!-- Verify button -->

                            <div>

                                <button type="submit"

                                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all disabled:opacity-50"

                                        wire:loading.attr="disabled"

                                        wire:target="verifyCode">

                                    <span wire:loading.remove wire:target="verifyCode">تایید کد</span>

                                    <span wire:loading wire:target="verifyCode" class="flex items-center gap-2">

                                    <span class="loading-spinner"></span>

                                    در حال بررسی...

                                </span>

                                    <svg wire:loading.remove wire:target="verifyCode" xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5 ms-2" viewBox="0 0 20 20" fill="currentColor">

                                        <path fill-rule="evenodd"
                                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                              clip-rule="evenodd"/>

                                    </svg>

                                </button>

                            </div>

                        </form>

                    </div>

                @endif



                <!-- Step 3: Set new password -->

                @if($step === 3)

                    <div x-data="{ showPassword: false, showConfirmPassword: false }">

                        <div class="flex items-center gap-3 mb-5">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">وارد کردن رمزعبور جدید</div>
                        </div>

                        <form wire:submit.prevent="resetPassword" class="space-y-5">

                            <!-- New password -->

                            <div>

                                <label for="password" class="block text-sm font-medium text-foreground mb-2">رمز عبور
                                    جدید</label>

                                <div class="relative">

                                    <input :type="showPassword ? 'text' : 'password'" id="password"

                                           wire:model.live="password"

                                           class="w-full ps-10 pe-4 py-3 border border-border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-secondary text-foreground placeholder:text-muted @error('password') border-red-500 @enderror"

                                           placeholder="رمز عبور جدید">

                                    <button type="button" @click="showPassword = !showPassword"
                                            class="absolute password-toggle top-1/2 transform -translate-y-1/2 text-muted hover:text-foreground">

                                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>

                                        </svg>

                                        <svg x-show="showPassword" style="display: none;"
                                             xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>

                                        </svg>

                                    </button>

                                </div>

                                @error('password')

                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>

                                @enderror



                                <!-- Password Requirements -->

                                <div class="mt-3 p-3 bg-secondary rounded-lg">

                                    <p class="text-xs font-medium text-muted mb-2">رمز عبور باید شامل موارد زیر
                                        باشد:</p>

                                    <div class="requirement-item">

                                        <span
                                            class="requirement-circle {{ $passwordStrength['length'] ? 'valid' : 'invalid' }}"></span>

                                        <span class="text-foreground">حداقل ۸ کاراکتر</span>

                                    </div>

                                    <div class="requirement-item">

                                        <span
                                            class="requirement-circle {{ $passwordStrength['letter'] ? 'valid' : 'invalid' }}"></span>

                                        <span class="text-foreground">حداقل یک حرف انگلیسی (بزرگ یا کوچک)</span>

                                    </div>

                                    <div class="requirement-item">

                                        <span
                                            class="requirement-circle {{ $passwordStrength['number'] ? 'valid' : 'invalid' }}"></span>

                                        <span class="text-foreground">حداقل یک عدد</span>

                                    </div>

                                </div>

                            </div>


                            <!-- Confirm password -->

                            <div>

                                <label for="password_confirmation"
                                       class="block text-sm font-medium text-foreground mb-2">تکرار رمز عبور
                                    جدید</label>

                                <div class="relative">

                                    <input :type="showConfirmPassword ? 'text' : 'password'" id="password_confirmation"

                                           wire:model="passwordConfirmation"

                                           class="w-full ps-10 pe-4 py-3 border border-border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-secondary text-foreground placeholder:text-muted @error('password_confirmation') border-red-500 @enderror"

                                           placeholder="تکرار رمز عبور جدید">

                                    <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                                            class="absolute password-toggle top-1/2 transform -translate-y-1/2 text-muted hover:text-foreground">

                                        <svg x-show="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>

                                        </svg>

                                        <svg x-show="showConfirmPassword" style="display: none;"
                                             xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>

                                        </svg>

                                    </button>

                                </div>

                                @error('password_confirmation')

                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>

                                @enderror

                            </div>


                            <!-- Submit button -->

                            <div>

                                <button type="submit"

                                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all disabled:opacity-50"

                                        wire:loading.attr="disabled"

                                        wire:target="resetPassword">

                                    <span wire:loading.remove wire:target="resetPassword">ثبت رمز جدید</span>

                                    <span wire:loading wire:target="resetPassword" class="flex items-center gap-2">

                                    <span class="loading-spinner"></span>

                                    در حال ثبت...

                                </span>

                                    <svg wire:loading.remove wire:target="resetPassword"
                                         xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ms-2" viewBox="0 0 20 20"
                                         fill="currentColor">

                                        <path fill-rule="evenodd"
                                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                              clip-rule="evenodd"/>

                                    </svg>

                                </button>

                            </div>

                        </form>

                    </div>

                @endif



                <!-- Step 4: Success -->

                @if($step === 4)

                    <div class="text-center">

                        <div
                            class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900/30 mb-6">

                            <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>

                            </svg>

                        </div>

                        <h2 class="text-2xl font-bold text-foreground mb-2">رمز عبور با موفقیت تغییر کرد!</h2>

                        <p class="text-muted mb-6">اکنون می‌توانید با رمز عبور جدید وارد حساب کاربری خود شوید</p>


                        <button type="button"

                                wire:click="loginAndRedirect"

                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all disabled:opacity-50"

                                wire:loading.attr="disabled"

                                wire:target="loginAndRedirect">

                            <span wire:loading.remove wire:target="loginAndRedirect">ورود به حساب کاربری</span>

                            <span wire:loading wire:target="loginAndRedirect" class="flex items-center gap-2">

                            <span class="loading-spinner"></span>

                            در حال ورود...

                        </span>

                            <svg wire:loading.remove wire:target="loginAndRedirect" xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5 ms-2" viewBox="0 0 20 20" fill="currentColor">

                                <path fill-rule="evenodd"
                                      d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"
                                      clip-rule="evenodd"/>

                            </svg>

                        </button>

                    </div>

                @endif



                <!-- Return to login link -->

                @if($step !== 4)

                    <div class="mt-6 text-center">

                        <p class="text-sm text-muted">

                            <a href="{{ route('client.auth.login') }}"
                               class="font-medium text-primary hover:underline flex items-center justify-center">

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>

                                </svg>

                                بازگشت به صفحه ورود

                            </a>

                        </p>

                    </div>

                @endif

            </div>


            <!-- Terms footer -->

            <!-- Terms footer -->

            <div class="bg-secondary rounded-xl space-y-5 p-5 mt-3">
                <div class="font-medium text-xs text-center text-muted">
                    ورود شما به معنای پذیرش <a href="https://sdfr.me/terms"
                                               class="text-foreground transition-colors hover:text-primary hover:underline">شرایط</a>
                    و
                    <a href="https://sdfr.me/terms"
                       class="text-foreground transition-colors hover:text-primary hover:underline">قوانین
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
