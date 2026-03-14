<div class="min-h-screen flex flex-col lg:flex-row bg-background p-0" x-data="signupForm()">

    @push('link')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" rel="stylesheet">
        <style>
            .auth-hero {
                background: linear-gradient(135deg, rgba(0, 0, 0, .25), rgba(0, 0, 0, .25)),
                url('/client/auth-illustration.webp') center / cover no-repeat;
            }
            .auth-hero-inner { min-height: 210px; }
            @media (min-width: 1024px) { .auth-hero-inner { min-height: 100vh; } }

            .step-circle {
                width: 48px;
                height: 48px;
                transition: all 0.3s ease;
            }
            .step-circle.active {
                transform: scale(1.1);
                box-shadow: 0 0 20px rgba(var(--primary-rgb), 0.5);
            }
            .step-line {
                width: 64px;
                height: 3px;
                transition: all 0.3s ease;
            }
            .loading-spinner {
                border: 2px solid transparent;
                border-top-color: currentColor;
                border-radius: 50%;
                width: 16px;
                height: 16px;
                animation: spin .8s linear infinite;
            }
            @keyframes spin { to { transform: rotate(360deg); } }

            /* Select2 RTL Styling */
            .select2-container {
                width: 100% !important;
            }
            .select2-container--default .select2-selection--single {
                background-color: hsl(var(--secondary));
                border: 1px solid hsl(var(--border));
                border-radius: 0.75rem;
                height: 48px;
                padding: 0.75rem 1rem;
                direction: rtl;
                text-align: right;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: hsl(var(--foreground));
                line-height: 28px;
                padding-right: 0;
                padding-left: 28px;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 46px;
                right: auto;
                left: 1rem;
            }
            .select2-dropdown {
                background-color: hsl(var(--secondary));
                border: 1px solid hsl(var(--border));
                border-radius: 0.75rem;
                direction: rtl;
            }
            .select2-container--default .select2-search--dropdown .select2-search__field {
                background-color: hsl(var(--background));
                border: 1px solid hsl(var(--border));
                border-radius: 0.5rem;
                color: hsl(var(--foreground));
                padding: 0.5rem;
                direction: rtl;
            }
            .select2-container--default .select2-results__option {
                color: hsl(var(--foreground));
                padding: 0.75rem 1rem;
                text-align: right;
            }
            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: hsl(var(--primary));
                color: hsl(var(--primary-foreground));
            }

            /* Password Strength Indicator */
            .password-strength-item {
                transition: all 0.3s ease;
            }
            .password-strength-item.active {
                color: hsl(var(--primary));
                font-weight: 600;
            }

            /* Input Focus Effects */
            .form-input {
                transition: all 0.3s ease;
            }
            .form-input:focus {
                border-color: hsl(var(--primary));
                box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1);
            }

            /* Gender Radio Styling */
            .gender-option {
                transition: all 0.3s ease;
                cursor: pointer;
            }
            .gender-option:hover {
                border-color: hsl(var(--primary));
                background-color: hsl(var(--primary) / 0.05);
            }
            .gender-option input:checked + span {
                color: hsl(var(--primary));
                font-weight: 600;
            }

            /* Toast Custom Styles */
            .toastify {
                border-radius: 0.75rem;
                padding: 1rem 1.5rem;
                font-family: inherit;
            }
            .toastify.success {
                background: linear-gradient(135deg, #10b981, #059669);
            }
            .toastify.error {
                background: linear-gradient(135deg, #ef4444, #dc2626);
            }
        </style>
    @endpush

    <!-- Hero Section -->
    <div class="lg:w-1/2 auth-hero relative flex items-stretch justify-center overflow-hidden rounded-3xl">
        <div class="absolute inset-0 bg-black/10 dark:bg-black/30"></div>
        <div class="relative z-10 w-full auth-hero-inner flex flex-row items-center justify-center gap-4 lg:flex-col text-center px-4 py-6 lg:px-6 lg:py-10">
            <div class="relative flex items-center justify-center shrink-0">
                <img src="/client/step-01.webp" alt="" class="max-w-[120px] sm:max-w-[140px] lg:max-w-[420px] h-auto drop-shadow-2xl"/>
            </div>
            <div class="lg:mt-6 max-w-md text-right lg:text-center">
                <h2 class="font-black text-white text-lg sm:text-xl lg:text-4xl leading-relaxed">خوش آمدید</h2>
                <p class="mt-2 lg:mt-3 text-white/90 text-xs sm:text-sm lg:text-base leading-6 lg:leading-7">
                    برای عضویت در پلتفرم، لطفاً اطلاعات خود را با دقت وارد نمایید.
                </p>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="lg:w-1/2 flex items-center justify-center p-6">
        <div class="w-full max-w-md">
            <div class="bg-gradient-to-b from-secondary to-background space-y-6 px-6 pb-6 rounded-3xl shadow-soft dark:shadow-soft-dark border border-border">

                <!-- Header -->
                <div class="bg-background rounded-b-3xl p-5 text-center">
                    <a href="https://sdfr.me" class="inline-flex items-center gap-2 text-primary">
                        <img src="/client/assets/images/theme/intro/header.png" style="width: 100px;" alt="Logo">
                    </a>
                </div>

                <!-- Progress Steps -->
                <div class="flex items-center justify-center mb-8 direction-rtl" dir="rtl">
                    <div class="step-circle rounded-full flex items-center justify-center font-bold text-base {{ $currentStep >= 1 ? 'bg-primary text-primary-foreground active' : 'bg-secondary text-muted' }}">
                        @if($currentStep > 1)
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @else
                            1
                        @endif
                    </div>
                    <div class="step-line mx-2 {{ $currentStep >= 2 ? 'bg-primary' : 'bg-border' }}"></div>
                    <div class="step-circle rounded-full flex items-center justify-center font-bold text-base {{ $currentStep >= 2 ? 'bg-primary text-primary-foreground active' : 'bg-secondary text-muted' }}">
                        @if($currentStep > 2)
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        @else
                            2
                        @endif
                    </div>
                    <div class="step-line mx-2 {{ $currentStep >= 3 ? 'bg-primary' : 'bg-border' }}"></div>
                    <div class="step-circle rounded-full flex items-center justify-center font-bold text-base {{ $currentStep >= 3 ? 'bg-primary text-primary-foreground active' : 'bg-secondary text-muted' }}">3</div>
                </div>

                <!-- Step 1: Personal Info -->
                @if($currentStep === 1)
                    <form wire:submit.prevent="goToStep2" class="space-y-5">
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-foreground">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                نام
                            </label>
                            <input
                                type="text"
                                wire:model.defer="name"
                                class="form-input w-full py-3 px-4 rounded-xl border border-border bg-secondary text-foreground focus:outline-none @error('name') border-red-500 @enderror"
                                placeholder="نام خود را وارد کنید"
                            >
                            @error('name') <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-foreground">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                نام خانوادگی
                            </label>
                            <input
                                type="text"
                                wire:model.defer="fullName"
                                class="form-input w-full py-3 px-4 rounded-xl border border-border bg-secondary text-foreground focus:outline-none @error('fullName') border-red-500 @enderror"
                                placeholder="نام خانوادگی خود را وارد کنید"
                            >
                            @error('fullName') <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-sm font-medium text-foreground">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    استان
                                </label>
                                <select
                                    wire:model.live="stateId"
                                    class="state-select form-input w-full py-3 px-4 rounded-xl border border-border bg-secondary text-foreground focus:outline-none @error('stateId') border-red-500 @enderror"
                                    data-placeholder="استان خود را انتخاب کنید"
                                >
                                    <option value="">انتخاب استان</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                                @error('stateId') <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-sm font-medium text-foreground">
                                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    شهر
                                </label>
                                <select
                                    wire:model.defer="cityId"
                                    class="city-select form-input w-full py-3 px-4 rounded-xl border border-border bg-secondary text-foreground focus:outline-none @error('cityId') border-red-500 @enderror"
                                    @disabled(empty($stateId))
                                    data-placeholder="شهر خود را انتخاب کنید"
                                >
                                    <option value="">انتخاب شهر</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                @error('cityId') <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p> @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-foreground">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                جنسیت
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="gender-option flex items-center justify-center gap-2 py-3 px-4 rounded-xl border border-border bg-secondary text-foreground">
                                    <input type="radio" value="male" wire:model.defer="gender" class="sr-only">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        مرد
                                    </span>
                                </label>
                                <label class="gender-option flex items-center justify-center gap-2 py-3 px-4 rounded-xl border border-border bg-secondary text-foreground">
                                    <input type="radio" value="female" wire:model.defer="gender" class="sr-only">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        زن
                                    </span>
                                </label>
                            </div>
                            @error('gender') <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:target="goToStep2"
                            class="w-full py-3 rounded-xl bg-primary text-primary-foreground font-medium hover:opacity-90 transition-all duration-300 flex items-center justify-center gap-2"
                        >
                            <span wire:loading.remove wire:target="goToStep2">
                                مرحله بعد
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </span>
                            <span wire:loading wire:target="goToStep2" class="inline-flex items-center gap-2">
                                <span class="loading-spinner"></span>
                                در حال پردازش...
                            </span>
                        </button>
                    </form>
                @endif

                <!-- Step 2: Contact Info -->
                @if($currentStep === 2)
                    <form wire:submit.prevent="goToStep3" class="space-y-5">
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-foreground">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                شماره موبایل
                            </label>
                            <input
                                wire:model.live="mobile"
                                class="form-input w-full py-3 px-4 rounded-xl border border-border bg-secondary text-foreground focus:outline-none @error('mobile') border-red-500 @enderror"
                                maxlength="11"
                                dir="ltr"
                                type="tel"
                                inputmode="numeric"
                            />
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                ایمیل (اختیاری)
                            </label>
                            <input
                                type="email"
                                wire:model.defer="email"
                                class="form-input w-full py-3 px-4 rounded-xl border border-border bg-secondary text-foreground focus:outline-none @error('email') border-red-500 @enderror"
                                placeholder="example@mail.com"
                                dir="ltr"
                            >
                            @error('email') <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-foreground">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                تصویر پروفایل (اختیاری)
                            </label>
                            <div class="relative">
                                <input
                                    type="file"
                                    wire:model="picture"
                                    accept="image/*"
                                    class="form-input w-full py-2.5 px-4 rounded-xl border border-border bg-secondary text-foreground focus:outline-none @error('picture') border-red-500 @enderror file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary file:text-primary-foreground hover:file:opacity-90"
                                >
                                <div wire:loading wire:target="picture" class="absolute left-3 top-1/2 -translate-y-1/2">
                                    <span class="loading-spinner"></span>
                                </div>
                            </div>
                            @error('picture') <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <button
                                type="button"
                                wire:click="goToPreviousStep"
                                class="py-3 rounded-xl border border-border bg-secondary text-foreground font-medium hover:bg-primary/10 transition-all duration-300 flex items-center justify-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19l7-7-7-7"></path>
                                </svg>
                                مرحله قبل
                            </button>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="goToStep3"
                                class="py-3 rounded-xl bg-primary text-primary-foreground font-medium hover:opacity-90 transition-all duration-300 flex items-center justify-center gap-2"
                            >
                                <span wire:loading.remove wire:target="goToStep3">
                                    مرحله بعد
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </span>
                                <span wire:loading wire:target="goToStep3" class="inline-flex items-center gap-2">
                                    <span class="loading-spinner"></span>
                                    در حال پردازش...
                                </span>
                            </button>
                        </div>
                    </form>
                @endif

                <!-- Step 3: Password & OTP -->
                @if($currentStep === 3)
                    <form wire:submit.prevent="verifyAndRegister" class="space-y-5">
                        <div class="bg-secondary rounded-xl p-4 text-sm text-foreground space-y-2 border border-border">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="font-medium">{{ $name }} {{ $fullName }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span dir="ltr">{{ $mobile }}</span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-foreground">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                رمز عبور
                            </label>
                            <div class="relative">
                                <input
                                    type="{{ $showPassword ? 'text' : 'password' }}"
                                    wire:model.live="password"
                                    class="form-input w-full py-3 px-4 pl-12 rounded-xl border border-border bg-secondary text-foreground focus:outline-none @error('password') border-red-500 @enderror"
                                    placeholder="********"
                                >
                                <button
                                    type="button"
                                    wire:click="togglePasswordVisibility"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-muted hover:text-foreground transition-colors"
                                >
                                    @if($showPassword)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    @endif
                                </button>
                            </div>

                            <div class="flex flex-wrap gap-3 text-xs mt-2">
                                <span class="password-strength-item flex items-center gap-1 {{ $passwordStrength['length'] ? 'active' : 'text-muted' }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    حداقل ۸ کاراکتر
                                </span>
                                <span class="password-strength-item flex items-center gap-1 {{ $passwordStrength['letter'] ? 'active' : 'text-muted' }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    یک حرف
                                </span>
                                <span class="password-strength-item flex items-center gap-1 {{ $passwordStrength['number'] ? 'active' : 'text-muted' }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    یک عدد
                                </span>
                            </div>
                            @error('password') <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-foreground">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                تکرار رمز عبور
                            </label>
                            <div class="relative">
                                <input
                                    type="{{ $showPasswordConfirmation ? 'text' : 'password' }}"
                                    wire:model.blur="passwordConfirmation"
                                    class="form-input w-full py-3 px-4 pl-12 rounded-xl border border-border bg-secondary text-foreground focus:outline-none @error('passwordConfirmation') border-red-500 @enderror"
                                    placeholder="********"
                                >
                                <button
                                    type="button"
                                    wire:click="togglePasswordConfirmationVisibility"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-muted hover:text-foreground transition-colors"
                                >
                                    @if($showPasswordConfirmation)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    @endif
                                </button>
                            </div>
                            @error('passwordConfirmation') <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-foreground">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                کد تایید
                            </label>
                            <input
                                type="tel"
                                wire:model.live="userInputCode"
                                maxlength="6"
                                dir="ltr"
                                class="form-input w-full py-3 px-4 rounded-xl border border-border bg-secondary text-foreground focus:outline-none text-center text-2xl font-bold tracking-widest @error('code') border-red-500 @enderror"
                                placeholder="• • • • • •"
                            >
                            @error('code') <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p> @enderror
                            @if($codeErrorMessage)
                                <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $codeErrorMessage }}
                                </p>
                            @endif
                        </div>

                        <div class="text-sm text-center">
                            <span x-show="countdown > 0" class="text-muted">
                                ارسال مجدد کد تا <span x-text="countdown" class="font-bold text-primary"></span> ثانیه دیگر
                            </span>
                            <button
                                type="button"
                                x-show="countdown === 0"
                                wire:click="resendOtp"
                                class="text-primary font-medium hover:underline flex items-center justify-center gap-2 mx-auto"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                ارسال مجدد کد
                            </button>
                        </div>

                        <label class="flex items-start gap-3 text-sm text-foreground cursor-pointer">
                            <input type="checkbox" wire:model.defer="acceptTerms" class="mt-1 w-5 h-5 rounded border-border bg-secondary">
                            <span>شرایط و قوانین سایت را مطالعه کرده و می‌پذیرم.</span>
                        </label>
                        @error('acceptTerms') <p class="mt-1 text-xs text-red-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p> @enderror

                        <div class="grid grid-cols-2 gap-3">
                            <button
                                type="button"
                                wire:click="goToPreviousStep"
                                class="py-3 rounded-xl border border-border bg-secondary text-foreground font-medium hover:bg-primary/10 transition-all duration-300 flex items-center justify-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19l7-7-7-7"></path>
                                </svg>
                                مرحله قبل
                            </button>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="verifyAndRegister"
                                class="py-3 rounded-xl bg-primary text-primary-foreground font-medium hover:opacity-90 transition-all duration-300 flex items-center justify-center gap-2"
                            >
                                <span wire:loading.remove wire:target="verifyAndRegister" class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    ثبت نام
                                </span>
                                <span wire:loading wire:target="verifyAndRegister" class="inline-flex items-center gap-2">
                                    <span class="loading-spinner"></span>
                                    در حال ثبت نام...
                                </span>
                            </button>
                        </div>
                    </form>
                @endif

                <div class="mt-6 text-center text-sm text-muted">
                    قبلاً حساب کاربری دارید؟
                    <a href="{{ route('client.auth.login') }}" class="font-medium text-primary hover:underline">وارد شوید</a>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

        <script>
            function signupForm() {
                return {
                    countdown: 90,
                    timer: null,

                    init() {
                        // Select2 initialization
                        this.initSelect2();

                        // Livewire event listeners
                        Livewire.on('start-countdown', () => {
                            this.startCountdown();
                        });

                        Livewire.on('show-toast', (event) => {
                            this.showToast(event);
                        });
                    },

                    initSelect2() {
                        const self = this;

                        // State Select
                        $('.state-select').select2({
                            placeholder: 'استان خود را انتخاب کنید',
                            allowClear: true,
                            language: {
                                noResults: function() {
                                    return 'نتیجه‌ای یافت نشد';
                                }
                            }
                        }).on('change', function() {
                        @this.set('stateId', $(this).val());
                        });

                        // City Select
                        $('.city-select').select2({
                            placeholder: 'شهر خود را انتخاب کنید',
                            allowClear: true,
                            language: {
                                noResults: function() {
                                    return 'نتیجه‌ای یافت نشد';
                                }
                            }
                        }).on('change', function() {
                        @this.set('cityId', $(this).val());
                        });

                        // Re-initialize Select2 when cities update
                        Livewire.hook('message.processed', (message, component) => {
                            $('.city-select').select2({
                                placeholder: 'شهر خود را انتخاب کنید',
                                allowClear: true,
                                language: {
                                    noResults: function() {
                                        return 'نتیجه‌ای یافت نشد';
                                    }
                                }
                            });
                        });
                    },

                    startCountdown() {
                        if (this.timer) {
                            clearInterval(this.timer);
                        }

                        this.countdown = 90;

                        this.timer = setInterval(() => {
                            if (this.countdown > 0) {
                                this.countdown--;
                            } else {
                                clearInterval(this.timer);
                            @this.call('countdownFinished');
                            }
                        }, 1000);
                    },

                    showToast(event) {
                        const data = Array.isArray(event) ? event[0] : event;

                        Toastify({
                            text: data.message,
                            duration: 3000,
                            gravity: "top",
                            position: "center",
                            className: data.type,
                            stopOnFocus: true,
                        }).showToast();
                    }
                }
            }
        </script>
    @endpush

</div>
