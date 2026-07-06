<div class="relative min-h-screen overflow-hidden bg-background text-foreground" dir="rtl"
     x-data="forgotPasswordForm()">


    @push('link')
        <style>
            .grid-figma {
                background-image: linear-gradient(to right, hsl(var(--border) / 0.4) 1px, transparent 1px),
                linear-gradient(to bottom, hsl(var(--border) / 0.4) 1px, transparent 1px),
                linear-gradient(to right, hsl(var(--border) / 0.2) 1px, transparent 1px),
                linear-gradient(to bottom, hsl(var(--border) / 0.2) 1px, transparent 1px);
                background-size: 80px 80px, 80px 80px, 16px 16px, 16px 16px;
                -webkit-mask-image: radial-gradient(ellipse 100% 80% at 50% 30%, #000 30%, transparent 90%);
                mask-image: radial-gradient(ellipse 100% 80% at 50% 30%, #000 30%, transparent 90%);
            }

            .glass-card {
                background: hsl(var(--background) / 0.6);
                backdrop-filter: blur(18px) saturate(140%);
                -webkit-backdrop-filter: blur(18px) saturate(140%);
                border: 1px solid hsl(var(--border) / 0.6);
            }

            .glass-input {
                background: hsl(var(--secondary) / 0.6);
                border: 1px solid hsl(var(--border));
                transition: all 0.2s ease;
                color: hsl(var(--foreground));
            }

            .glass-input:focus {
                background: hsl(var(--secondary));
                border-color: hsl(var(--primary));
                box-shadow: 0 0 0 3px hsl(var(--primary) / 0.15);
                outline: none;
            }

            .glass-input::placeholder {
                color: hsl(var(--muted) / 0.7);
            }

            .train-border {
                position: relative;
                border-radius: 1.5rem;
                --bw: 2px;
                --speed: 3s;
            }

            .train-border::before {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: inherit;
                padding: var(--bw);
                background: conic-gradient(from var(--angle, 0deg),
                transparent 0deg, transparent 200deg,
                hsl(var(--primary) / 0.45) 270deg, #3b82f6 318deg,
                #93c5fd 340deg, #ffffff 351deg, #93c5fd 360deg);
                -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
                -webkit-mask-composite: xor;
                mask-composite: exclude;
                animation: rotate-border var(--speed) linear infinite;
                pointer-events: none;
                z-index: 3;
            }

            .train-border > * {
                position: relative;
                z-index: 1;
            }

            @property --angle {
                syntax: '<angle>';
                initial-value: 0deg;
                inherits: false;
            }

            @keyframes rotate-border {
                to {
                    --angle: 360deg;
                }
            }

            @supports not (background: conic-gradient(from 0deg, red, blue)) {
                .train-border::before {
                    display: none;
                }
            }

            .btn-press {
                position: relative;
                transform: translateY(0);
                box-shadow: 0 4px 0 0 hsl(var(--primary) / 0.4), 0 6px 12px hsl(var(--primary) / 0.25);
                transition: transform 0.08s ease, box-shadow 0.08s ease;
                background: hsl(var(--primary));
                color: white;
                user-select: none;
            }

            .btn-press:hover:not(:disabled) {
                transform: translateY(-1px);
                box-shadow: 0 5px 0 0 hsl(var(--primary) / 0.4), 0 8px 16px hsl(var(--primary) / 0.35);
            }

            .btn-press:active:not(:disabled) {
                transform: translateY(3px);
                box-shadow: 0 1px 0 0 hsl(var(--primary) / 0.4), 0 2px 4px hsl(var(--primary) / 0.2);
            }

            .btn-press:disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }

            @keyframes float-orb {
                0%, 100% {
                    transform: translate(0, 0);
                }
                50% {
                    transform: translate(20px, -25px);
                }
            }

            .float-orb {
                animation: float-orb 9s ease-in-out infinite;
            }

            .password-wrapper {
                position: relative;
            }

            .password-wrapper input {
                padding-left: 2.5rem;
            }

            .eye-btn {
                position: absolute;
                left: 0.625rem;
                top: 50%;
                transform: translateY(-50%);
                color: hsl(var(--muted));
                background: none;
                border: none;
                padding: 4px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 4px;
                transition: color 0.15s ease;
            }

            .eye-btn:hover {
                color: hsl(var(--foreground));
            }

            .countdown-circle {
                width: 56px;
                height: 56px;
                border-radius: 50%;
                background: hsl(var(--secondary) / 0.6);
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 900;
                font-size: 16px;
                color: hsl(var(--primary));
                border: 2px solid hsl(var(--primary) / 0.4);
                font-variant-numeric: tabular-nums;
            }

            @keyframes shake {
                0%, 100% {
                    transform: translateX(0);
                }
                25% {
                    transform: translateX(-5px);
                }
                75% {
                    transform: translateX(5px);
                }
            }

            .shake {
                animation: shake 0.4s ease;
            }

            /* Stepper dots */
            .step-dot {
                width: 8px;
                height: 8px;
                border-radius: 999px;
                background: hsl(var(--border));
                transition: all 0.3s ease;
            }

            .step-dot.active {
                width: 28px;
                background: hsl(var(--primary));
            }

            .step-dot.completed {
                background: hsl(var(--primary) / 0.5);
            }

            @keyframes pop-in {
                0% {
                    transform: scale(0.6);
                    opacity: 0;
                }
                60% {
                    transform: scale(1.08);
                }
                100% {
                    transform: scale(1);
                    opacity: 1;
                }
            }

            .anim-pop {
                animation: pop-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
            }

            @media (prefers-reduced-motion: reduce) {
                * {
                    animation: none !important;
                    transition: none !important;
                }
            }
        </style>
    @endpush
    <div class="absolute inset-0 grid-figma pointer-events-none"></div>
    <div
        class="absolute top-20 -right-20 w-72 h-72 bg-primary/15 rounded-full blur-3xl float-orb pointer-events-none"></div>
    <div class="absolute bottom-20 -left-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl float-orb pointer-events-none"
         style="animation-delay: -3s"></div>

    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">

            {{-- Logo header --}}
            <div class="flex flex-col items-center mb-6">
                <a wire:navigate href="{{ route('client.home') }}" class="flex items-center gap-2 mb-3">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center">
                        <img src="/client/assets/images/favicon.svg" class="w-9 h-9 " alt="SDFR"/>
                    </div>
                </a>
                {{-- Step indicator --}}
                @if($step < 4)
                    <div class="flex items-center gap-2 mt-4">
                        <span class="text-xs text-muted font-mono mr-1">{{ $step }}/3</span>
                        <br>
                        @for($i = 1; $i <= 3; $i++)
                            <span
                                class="step-dot {{ $step === $i ? 'active' : ($step > $i ? 'completed' : '') }}"></span>
                        @endfor

                    </div>
                @endif
            </div>

            <div class="train-border">
                <div class="glass-card rounded-3xl p-6 space-y-5">

                    {{-- ═══ Step 1: Mobile ═══ --}}
                    @if($step === 1)
                        <form wire:submit.prevent="sendCode" autocomplete="on" class="space-y-4">
                            <div class="text-center">
                                <h2 class="font-bold text-base text-foreground mb-1">شماره موبایل خود را وارد کنید</h2>
                                <p class="text-xs text-muted">کد بازیابی به این شماره ارسال می‌شود</p>
                            </div>

                            <div class="relative">
                                <label class="block text-xs font-semibold mb-1.5 text-muted">شماره موبایل</label>
                                <input wire:model.live="mobile" type="tel" maxlength="11" dir="ltr"
                                       inputmode="numeric" autocomplete="username" placeholder="09..."
                                       class="glass-input w-full rounded-xl px-4 py-3 text-sm font-mono @error('mobile') border-rose-500/60 shake @enderror">
                                @error('mobile')
                                <div class="text-xs text-rose-500 mt-1.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>@enderror
                            </div>

                            <button type="submit" wire:loading.attr="disabled" wire:target="sendCode"
                                    class="btn-press w-full h-12 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="sendCode" class="flex items-center gap-2">
                                    ارسال کد بازیابی
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                </span>
                                <span wire:loading wire:target="sendCode" class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"
                                                stroke-dasharray="32" stroke-linecap="round" opacity="0.5"/>
                                    </svg>
                                </span>
                            </button>
                        </form>
                    @endif

                    {{-- ═══ Step 2: Verify Code ═══ --}}
                    @if($step === 2)
                        <form wire:submit.prevent="verifyCode" autocomplete="off" class="space-y-4">
                            <div class="rounded-xl p-3.5 bg-primary/5 border border-primary/20">
                                <p class="text-xs text-center text-foreground/80">
                                    کد ارسال‌شده به <span class="font-bold text-primary" dir="ltr">{{ $mobile }}</span>
                                    را وارد کنید
                                </p>
                                <button type="button" wire:click="backToMobileStep"
                                        class="text-xs text-primary hover:underline mt-2 block mx-auto font-semibold">
                                    تغییر شماره
                                </button>
                            </div>

                            <div class="relative">
                                <label class="block text-xs font-semibold mb-1.5 text-muted">کد تأیید</label>
                                <input type="tel" wire:model.live="code" maxlength="6" dir="ltr"
                                       autocomplete="one-time-code" inputmode="numeric" placeholder="------"
                                       class="glass-input w-full text-center tracking-[0.6em] text-2xl font-mono rounded-xl px-4 py-3.5 @error('code') border-rose-500/60 shake @enderror">
                                @error('code')
                                <div class="text-xs text-rose-500 mt-1.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>@enderror
                            </div>

                            <div class="flex items-center justify-center">
                                <div x-show="countdown > 0" class="flex flex-col items-center gap-2">
                                    <p class="text-xs text-muted">ارسال مجدد تا</p>
                                    <div class="countdown-circle"><span x-text="countdown"></span></div>
                                </div>
                                <button type="button" x-show="countdown === 0" x-cloak wire:click="resendCode"
                                        @click="startCountdown(90)"
                                        class="text-primary font-semibold hover:underline flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    ارسال مجدد کد
                                </button>
                            </div>

                            <button type="submit" wire:loading.attr="disabled" wire:target="verifyCode"
                                    class="btn-press w-full h-12 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="verifyCode" class="flex items-center gap-2">
                                    تأیید کد
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                <span wire:loading wire:target="verifyCode" class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"
                                                stroke-dasharray="32" stroke-linecap="round" opacity="0.5"/>
                                    </svg>
                                </span>
                            </button>
                        </form>
                    @endif

                    {{-- ═══ Step 3: New Password ═══ --}}
                    @if($step === 3)
                        <form wire:submit.prevent="resetPassword" autocomplete="off" class="space-y-4">
                            <div class="text-center">
                                <h2 class="font-bold text-base text-foreground mb-1">رمز عبور جدید</h2>
                                <p class="text-xs text-muted">یک رمز قوی برای حساب خود انتخاب کنید</p>
                            </div>

                            <div class="relative">
                                <label class="block text-xs font-semibold mb-1.5 text-muted">رمز عبور جدید</label>
                                <div class="password-wrapper">
                                    <input wire:model.live="password"
                                           :type="showPassword ? 'text' : 'password'"
                                           dir="ltr" autocomplete="new-password" placeholder="********"
                                           class="glass-input w-full rounded-xl px-4 py-3 text-sm @error('password') border-rose-500/60 shake @enderror">
                                    <button type="button" class="eye-btn" @click="showPassword = !showPassword"
                                            tabindex="-1">
                                        <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                             stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                            <path
                                                d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                            <line x1="1" y1="1" x2="23" y2="23"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                <div class="text-xs text-rose-500 mt-1.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>@enderror

                                {{-- Strength indicators --}}
                                <div class="mt-3 p-3 rounded-xl bg-secondary/40 border border-border space-y-2">
                                    <p class="text-[11px] font-semibold text-muted mb-1">رمز عبور باید شامل:</p>
                                    @foreach([
                                        ['key' => 'length', 'label' => 'حداقل ۸ کاراکتر'],
                                        ['key' => 'letter', 'label' => 'یک حرف انگلیسی'],
                                        ['key' => 'number', 'label' => 'یک عدد'],
                                    ] as $rule)
                                        @php $ok = $passwordStrength[$rule['key']] ?? false; @endphp
                                        <div
                                            class="flex items-center gap-2 text-xs transition-colors {{ $ok ? 'text-emerald-500' : 'text-muted' }}">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                @if($ok)
                                                    <path fill-rule="evenodd"
                                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                          clip-rule="evenodd"/>
                                                @else
                                                    <circle cx="10" cy="10" r="8" fill="none" stroke="currentColor"
                                                            stroke-width="1.5"/>
                                                @endif
                                            </svg>
                                            <span>{{ $rule['label'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="relative">
                                <label class="block text-xs font-semibold mb-1.5 text-muted">تکرار رمز عبور</label>
                                <div class="password-wrapper">
                                    <input wire:model.blur="passwordConfirmation"
                                           :type="showPasswordConfirmation ? 'text' : 'password'"
                                           dir="ltr" autocomplete="new-password" placeholder="********"
                                           class="glass-input w-full rounded-xl px-4 py-3 text-sm @error('passwordConfirmation') border-rose-500/60 shake @enderror">
                                    <button type="button" class="eye-btn"
                                            @click="showPasswordConfirmation = !showPasswordConfirmation" tabindex="-1">
                                        <svg x-show="!showPasswordConfirmation" class="w-4 h-4" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        <svg x-show="showPasswordConfirmation" x-cloak class="w-4 h-4" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                            <path
                                                d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                            <line x1="1" y1="1" x2="23" y2="23"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('passwordConfirmation')
                                <div class="text-xs text-rose-500 mt-1.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>@enderror
                            </div>

                            <button type="submit" wire:loading.attr="disabled" wire:target="resetPassword"
                                    class="btn-press w-full h-12 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="resetPassword" class="flex items-center gap-2">
                                    ثبت رمز جدید
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                <span wire:loading wire:target="resetPassword" class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"
                                                stroke-dasharray="32" stroke-linecap="round" opacity="0.5"/>
                                    </svg>
                                </span>
                            </button>
                        </form>
                    @endif

                    {{-- ═══ Step 4: Success ═══ --}}
                    @if($step === 4)
                        <div class="text-center space-y-4">
                            <div
                                class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-emerald-500/15 border border-emerald-500/30 anim-pop">
                                <svg class="h-8 w-8 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-black text-foreground">رمز عبور با موفقیت تغییر کرد</h2>
                            <p class="text-sm text-muted leading-7">اکنون می‌توانید با رمز عبور جدید وارد حساب کاربری
                                خود شوید.</p>

                            <button type="button" wire:click="loginAndRedirect" wire:loading.attr="disabled"
                                    wire:target="loginAndRedirect"
                                    class="btn-press w-full h-12 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="loginAndRedirect"
                                      class="flex items-center gap-2">
                                    ورود به حساب کاربری
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path
                                            d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                                </span>
                                <span wire:loading wire:target="loginAndRedirect"
                                      class="inline-flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"
                                                stroke-dasharray="32" stroke-linecap="round" opacity="0.5"/>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    @endif

                    @if($step !== 4)
                        <div class="pt-2 border-t border-border text-center">
                            <a wire:navigate href="{{ route('client.auth.login') }}"
                               class="font-semibold text-primary hover:underline inline-flex items-center justify-center gap-1.5 text-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                بازگشت به صفحه ورود
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4 text-center">
                <p class="text-[11px] text-muted leading-6">
                    ورود شما به معنای پذیرش
                    <a wire:navigate href="{{ route('client.terms') }}"
                       class="text-foreground hover:text-primary hover:underline font-semibold">شرایط</a>
                    و
                    <a wire:navigate href="{{ route('client.terms') }}"
                       class="text-foreground hover:text-primary hover:underline font-semibold">قوانین حریم خصوصی</a>
                    است.
                </p>
            </div>
        </div>
    </div>

    @push('script')
            <script>
                function forgotPasswordForm() {
                    return {
                        showPassword: false,
                        showPasswordConfirmation: false,
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
                                    @this.
                                    call('countdownFinished');
                                }
                            }, 1000);
                        }
                    }
                }
            </script>
    @endpush
</div>
