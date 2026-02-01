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

                min-height: 100vh;
                position: sticky;
                top: 0;

            }

            .step-indicator {

                display: flex;

                align-items: center;

                justify-content: center;

                margin-bottom: 2rem;

                direction: rtl;

            }


            .step-circle {

                width: 40px;

                height: 40px;

                border-radius: 50%;

                display: flex;

                align-items: center;

                justify-content: center;

                font-weight: bold;

                font-size: 14px;

                transition: all 0.3s ease;

            }


            .step-circle.active {

                background: hsl(var(--primary));

                color: white;

            }


            .step-circle.completed {

                background: hsl(var(--primary));

                color: white;

            }


            .step-circle.inactive {

                background: hsl(var(--secondary));

                color: hsl(var(--muted));

            }


            .step-line {

                width: 60px;

                height: 3px;

                margin: 0 8px;

                transition: all 0.3s ease;

            }


            .step-line.active {

                background: hsl(var(--primary));

            }


            .step-line.inactive {

                background: white;

            }


            .input-icon {

                left: 12px;
                bottom: 6px;

            }


            .password-toggle {

                left: 12px;
                bottom: 6px;

            }


            .requirement-item {

                display: flex;

                align-items: center;

                gap: 8px;

                font-size: 12px;

                margin-bottom: 4px;

            }


            .requirement-circle {

                width: 8px;

                height: 8px;

                border-radius: 50%;

                transition: background-color 0.3s ease;

            }


            .requirement-circle.valid {

                background-color: #10b981;

            }


            .requirement-circle.invalid {

                background-color: #ef4444;

            }


            .shadow-soft {

                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);

            }


            .dark .shadow-soft-dark {

                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);

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


    <!-- Registration form section -->

    <div class="lg:w-1/2 flex items-center justify-center p-6">

        <div class="w-full max-w-md">

            <div class="bg-gradient-to-b from-secondary to-background space-y-5 px-5 pb-5 rounded-3xl shadow-soft dark:shadow-soft-dark  border border-border"
            >

                <div class="bg-background rounded-b-3xl space-y-2 p-5 " style="    text-align: center;">
                    <a href="https://sdfr.me" class="inline-flex items-center gap-2 text-primary">

                        <img src="/client/assets/images/theme/intro/header.png" style="width: 100px;">

                    </a>
                </div>
                <!-- Step indicator -->

                <div class="step-indicator">

                    <div
                        class="step-circle {{ $currentStep >= 3 ? 'completed' : ($currentStep >= 1 ? 'active' : 'inactive') }}">

                        @if($currentStep > 1)

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                 fill="currentColor">

                                <path fill-rule="evenodd"
                                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                      clip-rule="evenodd"/>

                            </svg>

                        @else

                            1

                        @endif

                    </div>

                    <div class="step-line {{ $currentStep >= 2 ? 'active' : 'inactive' }}"></div>

                    <div
                        class="step-circle {{ $currentStep >= 3 ? 'completed' : ($currentStep >= 2 ? 'active' : 'inactive') }}">

                        @if($currentStep > 2)

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                 fill="currentColor">

                                <path fill-rule="evenodd"
                                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                      clip-rule="evenodd"/>

                            </svg>

                        @else

                            2

                        @endif

                    </div>

                    <div class="step-line {{ $currentStep >= 3 ? 'active' : 'inactive' }}"></div>

                    <div class="step-circle {{ $currentStep >= 3 ? 'active' : 'inactive' }}">3</div>

                </div>


                <!-- Step 1 - Personal Information -->

                @if($currentStep === 1)

                    <div class="step">

                        <div class="text-center mb-5">

                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1">
                                    <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                    <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                </div>
                                <div class="font-black text-foreground">ثبت نام</div>
                            </div>



                        </div>


                        <form wire:submit.prevent="goToStep2" class="space-y-5">

                            <!-- Name -->

                            <div>

                                <label for="name" class="block text-sm font-medium text-foreground mb-2">نام و نام
                                    خانوادگی</label>

                                <div class="relative">

                                    <input type="text" id="name"

                                           wire:model="name"

                                           class="w-full ps-4 pe-10 py-3 border border-border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-secondary text-foreground placeholder:text-muted @error('name') border-red-500 @enderror"

                                           placeholder="نام و نام خانوادگی خود را وارد کنید">

                                    <div class="absolute input-icon top-1/2 transform -translate-y-1/2 text-muted">

                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>

                                        </svg>

                                    </div>

                                </div>

                                @error('name')

                                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>

                                @enderror

                            </div>


                            <!-- Mobile -->

                            <div>

                                <label for="mobile" class="block text-sm font-medium text-foreground mb-2">شماره
                                    موبایل</label>

                                <div class="relative">

                                    <input type="tel" id="mobile"

                                           wire:model.live="mobile"
                                           dir="rtl"

                                           maxlength="11"

                                           class="w-full  py-3 border border-border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-secondary text-foreground placeholder:text-muted @error('mobile') border-red-500 @enderror"

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


                            <!-- Continue button -->

                            <div class="pt-4">

                                <button type="submit"

                                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all disabled:opacity-50"

                                        wire:loading.attr="disabled"

                                        wire:target="goToStep2">

                                    <span wire:loading.remove wire:target="goToStep2">مرحله بعد</span>

                                    <span wire:loading wire:target="goToStep2" class="flex items-center gap-2">

                                    <span class="loading-spinner"></span>

                                    در حال پردازش...

                                </span>

                                    <svg wire:loading.remove wire:target="goToStep2" xmlns="http://www.w3.org/2000/svg"
                                         fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                         class="size-4 ms-2">

                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15.75 19.5 8.25 12l7.5-7.5"/>

                                    </svg>

                                </button>

                            </div>

                        </form>

                    </div>

                @endif



                <!-- Step 2 - Account Information -->

                @if($currentStep === 2)

                    <div class="step">

                        <div class="flex items-center gap-3 mb-5">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-primary">اطلاعات کاربری </div>
                        </div>


                        <form wire:submit.prevent="goToStep3" class="space-y-5">

                            <!-- Email -->

                            <div>

                                <label for="email" class="block text-sm font-medium text-foreground mb-2">ایمیل
                                    (اختیاری)</label>

                                <div class="relative">

                                    <input type="email" id="email"

                                           wire:model="email"

                                           class="w-full ps-4 pe-10 py-3 border border-border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-secondary text-foreground placeholder:text-muted @error('email') border-red-500 @enderror"

                                           placeholder="example@example.com">

                                    <div class="absolute input-icon top-1/2 transform -translate-y-1/2 text-muted">

                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>

                                        </svg>

                                    </div>

                                </div>

                                @error('email')

                                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>

                                @enderror

                            </div>


                            <!-- Password -->

                            <div x-data="{ showPassword: false }">

                                <label for="password" class="block text-sm font-medium text-foreground mb-2">رمز
                                    عبور</label>

                                <div class="relative">

                                    <input :type="showPassword ? 'text' : 'password'" id="password"

                                           wire:model.live="password"

                                           class="w-full ps-10 pe-4 py-3 border border-border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-secondary text-foreground placeholder:text-muted @error('password') border-red-500 @enderror"

                                           placeholder="رمز عبور قوی انتخاب کنید">

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

                                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>

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


                            <!-- Step 2 buttons -->

                            <div class="pt-4 flex gap-3">

                                <button type="button" wire:click="goToPreviousStep"

                                        class="w-1/2 flex justify-center items-center py-3 px-4 border border-border rounded-xl shadow-sm text-sm font-medium text-foreground bg-secondary hover:bg-secondary/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ms-2" viewBox="0 0 20 20"
                                         fill="currentColor">

                                        <path fill-rule="evenodd"
                                              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                              clip-rule="evenodd"/>

                                    </svg>

                                    مرحله قبل

                                </button>

                                <button type="submit"

                                        class="w-1/2 flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all disabled:opacity-50"

                                        wire:loading.attr="disabled"

                                        wire:target="goToStep3">

                                    <span wire:loading.remove wire:target="goToStep3">مرحله بعد</span>

                                    <span wire:loading wire:target="goToStep3" class="flex items-center gap-2">

                                    <span class="loading-spinner"></span>

                                    در حال پردازش...

                                </span>

                                    <svg wire:loading.remove wire:target="goToStep3" xmlns="http://www.w3.org/2000/svg"
                                         class="h-5 w-5 me-2" viewBox="0 0 20 20" fill="currentColor">

                                        <path fill-rule="evenodd"
                                              d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                              clip-rule="evenodd"/>

                                    </svg>

                                </button>

                            </div>

                        </form>

                    </div>

                @endif



                <!-- Step 3 - Final Verification -->

                @if($currentStep === 3)

                    <div class="step" x-data="countdownTimer()" x-init="startCountdown(90)">

                        <div class="flex items-center gap-3 mb-5">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-primary">تایید نهایی</div>
                        </div>


                        <!-- User Info Summary -->

                        <div class="bg-secondary rounded-xl p-4 mb-6">

                            <div class="grid grid-cols-1 gap-4 ">

                                <div class="mb-3">

                                    <p class="text-sm text-muted">نام و نام خانوادگی:</p>

                                    <p class="font-medium text-foreground">{{ $name }}</p>

                                </div>

                                <div class="col-span-1 mb-3">

                                    <p class="text-sm text-muted">ایمیل:</p>

                                    <p class="font-medium text-foreground">{{ $email ?: 'وارد نشده' }}</p>

                                </div>

                                <div class="mb-3">

                                    <p class="text-sm text-muted">شماره موبایل:</p>

                                    <p class="font-medium text-foreground" dir="ltr">{{ $mobile }}</p>

                                </div>



                            </div>

                        </div>


                        <form wire:submit.prevent="verifyAndRegister" class="space-y-5">

                            <!-- OTP Code -->

                            <div>

                                <label for="userInputCode" class="block text-sm font-medium text-foreground mb-2 mt-2">کد
                                    تایید</label>

                                <div class="relative">

                                    <input type="tel" id="userInputCode"

                                           wire:model.live="userInputCode"

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

                                @if($codeErrorMessage)

                                    <p class="mt-2 text-xs text-red-500">{{ $codeErrorMessage }}</p>

                                @endif

                                @if($sendSmsError)

                                    <p class="mt-2 text-xs text-red-500">{{ $sendSmsError }}</p>

                                @endif

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


                            <!-- Terms -->

                            <div class="flex items-start">

                                <div class="flex items-center h-5">

                                    <input id="terms" type="checkbox"

                                           wire:model="acceptTerms"

                                           class="form-radio !ring-0 !ring-offset-0 bg-border border-0">

                                </div>

                                <div class="ms-3 text-sm" style="margin-right: 10px;">

                                    <label for="terms" class="font-medium text-foreground cursor-pointer">

                                        با <a href="{{ route('client.terms') }}" class="text-primary hover:underline">قوانین
                                            و مقررات</a> سایت موافقم

                                    </label>

                                </div>

                            </div>

                            @error('acceptTerms')

                            <p class="text-xs text-red-500">{{ $message }}</p>

                            @enderror



                            <!-- Step 3 buttons -->

                            <div class="pt-4 flex gap-3">

                                <button type="button" wire:click="goToPreviousStep"

                                        class="w-1/2 flex justify-center items-center py-3 px-4 border border-border rounded-xl shadow-sm text-sm font-medium text-foreground bg-secondary hover:bg-secondary/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ms-2" viewBox="0 0 20 20"
                                         fill="currentColor">

                                        <path fill-rule="evenodd"
                                              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                              clip-rule="evenodd"/>

                                    </svg>

                                    مرحله قبل

                                </button>

                                <button type="submit"

                                        class="w-1/2 flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-primary-foreground bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all disabled:opacity-50"

                                        wire:loading.attr="disabled"

                                        wire:target="verifyAndRegister">

                                    <span wire:loading.remove wire:target="verifyAndRegister">ثبت نام</span>

                                    <span wire:loading wire:target="verifyAndRegister" class="flex items-center gap-2">

                                    <span class="loading-spinner"></span>

                                    در حال ثبت نام...

                                </span>

                                    <svg wire:loading.remove wire:target="verifyAndRegister"
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



                <!-- Login link -->

                <div class="mt-6 text-center">

                    <p class="text-sm text-muted">

                        قبلاً حساب کاربری دارید؟

                        <a href="{{ route('client.auth.login') }}" class="font-medium text-primary hover:underline">وارد
                            شوید</a>

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

                    // The countdown will be started by Alpine.js x-init

                });

            });

        </script>

    @endpush

</div>
