<div dir="rtl" class="relative mx-auto max-w-[94rem] overflow-x-hidden px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
    @push('link')
        <link rel="stylesheet" href="/client/assets/css/jalalidatepicker.min.css">
        <script src="/client/assets/js/jalalidatepicker.min.js" defer></script>
        <style>
            input[data-jdp] { direction: ltr; text-align: center; letter-spacing: 0.04em; }
            .jdp-container { font-family: inherit !important; z-index: 100 !important; }

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
                background: rgb(245 158 11 / 0.18);
                color: rgb(146 64 14);
                border: 1px solid rgb(245 158 11 / 0.35);
                box-shadow: 0 18px 36px -24px rgb(245 158 11 / 0.65);
            }

            .purchase-button-warning:hover {
                background: rgb(245 158 11 / 0.24);
            }

            .purchase-button-soft {
                border: 1px solid hsl(var(--border) / 0.9);
                background: hsl(var(--secondary) / 0.8);
            }

            .purchase-button-trial {
                border: 1px solid hsl(160 84% 36% / 0.32);
                background: linear-gradient(135deg, hsl(160 84% 36% / 0.18), hsl(174 72% 42% / 0.1));
                color: hsl(160 84% 36%);
                box-shadow: 0 18px 36px -24px hsl(160 84% 36% / 0.55);
            }

            .purchase-button-trial:hover {
                background: linear-gradient(135deg, hsl(160 84% 36% / 0.24), hsl(174 72% 42% / 0.16));
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
            window.initPurchaseJalaliDatepicker = window.initPurchaseJalaliDatepicker || (() => {
                const start = () => {
                    if (typeof jalaliDatepicker === 'undefined') {
                        setTimeout(start, 200);
                        return;
                    }

                    jalaliDatepicker.startWatch({
                        persianDigits: true,
                        showTodayBtn: false,
                        showEmptyBtn: true,
                        time: false,
                        autoHide: true,
                        zIndex: 100,
                    });
                };

                start();
            });

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
                        if (target.tagName === 'BUTTON') {
                            target.click();
                        }
                    });
                });
            });

            document.addEventListener('DOMContentLoaded', window.initPurchaseJalaliDatepicker);
            document.addEventListener('livewire:navigated', window.initPurchaseJalaliDatepicker);
            document.addEventListener('DOMContentLoaded', window.initPurchaseStepEffects);
            document.addEventListener('livewire:navigated', window.initPurchaseStepEffects);
        </script>
    @endpush

    <div wire:loading.flex wire:target="nextStep,prevStep,startEditInfo,cancelEditInfo,saveInfo,saveInfoAndContinue,applyCoupon,removeCoupon,pay,payInstallment,openTrialConfirm,closeTrialConfirm,startTrialWeek"
         class="fixed inset-0 z-[90] hidden items-center justify-center bg-slate-950/55 backdrop-blur-sm">
        <div class="w-[min(92vw,24rem)] rounded-3xl border border-white/10 bg-slate-950/80 p-6 text-center text-white shadow-2xl">
            <div class="mx-auto mb-4 h-14 w-14 rounded-2xl border border-white/10 bg-white/5 p-3">
                <svg class="h-full w-full animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-90" fill="currentColor" d="M12 2a10 10 0 0 1 10 10h-4a6 6 0 0 0-6-6V2z"></path>
                </svg>
            </div>
            <p class="text-sm font-black">در حال پردازش مرحله جاری...</p>
            <p class="mt-2 text-xs leading-6 text-white/70">لطفاً چند لحظه صبر کنید تا اطلاعات شما بررسی و مرحله بعد آماده شود.</p>
        </div>
    </div>

    @if (session('error'))
        <div class="mb-5 rounded-2xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm font-bold text-red-300">
            {{ session('error') }}
        </div>
    @endif

    @if (! $price || ! $data)
        <div class="purchase-shell rounded-[2rem] p-6 sm:p-8">
            <div class="purchase-panel mx-auto max-w-2xl rounded-[1.75rem] p-8 text-center">
                <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl border border-border bg-secondary/50 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
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
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
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
                                            <div class="mt-3 rounded-2xl border border-red-500/25 bg-red-500/10 p-4 text-sm leading-7 text-red-300">
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

                                <div class="rounded-[1.5rem] border border-emerald-500/20 bg-emerald-500/8 p-5">
                                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                        <div class="max-w-2xl">
                                            <div class="text-sm font-black text-foreground">فعلاً می‌خواهی با ما یک هفته تستی کار کنی؟</div>
                                            <p class="mt-2 text-sm leading-7 text-muted">
                                                اگر هنوز برای خرید قطعی نیستی، می‌توانی به‌جای پرداخت، مسیر <span class="font-black text-foreground">یک هفته آزمایشی</span> را شروع کنی و بعد از ورود، ادامه خرید از همین صفحه دیگر در اولویت این flow نخواهد بود.
                                            </p>
                                        </div>
                                        <button wire:click="openTrialConfirm" wire:loading.attr="disabled" wire:target="openTrialConfirm"
                                                class="purchase-button-trial inline-flex w-full items-center justify-center gap-2 rounded-2xl px-6 py-3 text-sm font-black transition disabled:opacity-60 lg:w-auto">
                                            <span wire:loading.remove wire:target="openTrialConfirm">شروع یک هفته آزمایشی</span>
                                            <span wire:loading wire:target="openTrialConfirm" class="inline-flex items-center gap-2">
                                                <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                </svg>
                                                در حال آماده‌سازی
                                            </span>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex justify-end">
                                    <button wire:click="nextStep" wire:loading.attr="disabled" wire:target="nextStep"
                                            class="purchase-button inline-flex w-full items-center justify-center gap-2 rounded-2xl px-6 py-3 text-sm font-black transition disabled:opacity-60 sm:w-auto">
                                        <span wire:loading.remove wire:target="nextStep">ادامه و بررسی اطلاعات</span>
                                        <span wire:loading wire:target="nextStep" class="inline-flex items-center gap-2">
                                            <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
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
                                        <div class="rounded-2xl border border-amber-500/25 bg-amber-500/10 p-4 text-sm leading-7 text-amber-800">
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
                                            ['label' => 'محل تولد', 'value' => $infoPlaceOfBirth ?: '<span class="text-red-400 text-xs font-black">تکمیل این فیلد الزامی است</span>', 'field' => 'infoPlaceOfBirth'],
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
                                                    <button wire:click="editInfoField('{{ $row['field'] }}')" wire:loading.attr="disabled"
                                                            class="rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-[11px] font-black text-primary transition hover:bg-primary/15">
                                                        ویرایش
                                                    </button>
                                                </div>
                                                <div class="mt-3 text-sm font-black text-foreground">{!! $row['value'] !!}</div>
                                            </div>
                                        @endforeach
                                        <div class="purchase-review-card rounded-2xl p-4 lg:col-span-2">
                                            <div class="flex items-center justify-between gap-3">
                                                <div class="text-[11px] font-bold text-muted">آدرس</div>
                                                <button wire:click="editInfoField('infoAddress')" wire:loading.attr="disabled"
                                                        class="rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-[11px] font-black text-primary transition hover:bg-primary/15">
                                                    ویرایش
                                                </button>
                                            </div>
                                            <div class="mt-2 text-sm font-black text-foreground">{!! $infoAddress ?: '<span class="text-red-400 text-xs font-black">تکمیل این فیلد الزامی است</span>' !!}</div>
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
                                                @error($model)<div class="mt-1 text-xs font-bold text-red-400">{{ $message }}</div>@enderror
                                            </div>
                                        @endforeach

                                        <div class="space-y-2" data-purchase-field="infoBirthDate">
                                            <label class="block text-xs font-black text-foreground">تاریخ تولد</label>
                                            <input type="text" wire:model.blur="infoBirthDate" data-jdp data-jdp-max-date="today" inputmode="none" autocomplete="off" placeholder="۱۳۸۰/۰۱/۰۱" class="{{ $fieldClass }} cursor-pointer text-center" dir="ltr">
                                            @error('infoBirthDate')<div class="mt-1 text-xs font-bold text-red-400">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="purchase-select-surface space-y-2" data-purchase-field="infoGrade">
                                            <label class="block text-xs font-black text-foreground">پایه</label>
                                            <x-ui.select
                                                wire:model.live="infoGrade"
                                                :options="$gradeSelectOptions"
                                                placeholder="انتخاب پایه"
                                                name="infoGrade" />
                                            @error('infoGrade')<div class="mt-1 text-xs font-bold text-red-400">{{ $message }}</div>@enderror
                                        </div>

                                        @if($selectedGradeRequiresField)
                                            <div class="purchase-select-surface space-y-2" data-purchase-field="infoField">
                                                <label class="block text-xs font-black text-foreground">رشته</label>
                                                <x-ui.select
                                                    wire:model.live="infoField"
                                                    :options="$fieldSelectOptions"
                                                    placeholder="انتخاب رشته"
                                                    name="infoField" />
                                                @error('infoField')<div class="mt-1 text-xs font-bold text-red-400">{{ $message }}</div>@enderror
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
                                            @error('infoStateId')<div class="mt-1 text-xs font-bold text-red-400">{{ $message }}</div>@enderror
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
                                            @error('infoCityId')<div class="mt-1 text-xs font-bold text-red-400">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="space-y-2 lg:col-span-2" data-purchase-field="infoAddress">
                                            <label class="block text-xs font-black text-foreground">آدرس</label>
                                            <textarea wire:model="infoAddress" rows="3" class="purchase-field w-full !ring-0 rounded-xl border border-border bg-background px-4 py-3 text-sm leading-7 text-foreground outline-none transition-all focus:border-primary"></textarea>
                                            @error('infoAddress')<div class="mt-1 text-xs font-bold text-red-400">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-3 sm:flex-row">
                                        <button wire:click="saveInfo" wire:loading.attr="disabled" wire:target="saveInfo"
                                                class="purchase-button-soft inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-black text-foreground transition hover:border-primary/40 disabled:opacity-60 sm:w-auto">
                                            <span wire:loading.remove wire:target="saveInfo">ذخیره</span>
                                            <span wire:loading wire:target="saveInfo" class="inline-flex items-center gap-2">
                                                <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                </svg>
                                                در حال ذخیره
                                            </span>
                                        </button>
                                        <button wire:click="cancelEditInfo" wire:loading.attr="disabled" wire:target="cancelEditInfo"
                                                class="purchase-button-soft w-full rounded-2xl px-5 py-3 text-sm font-black text-foreground transition hover:border-primary/40 sm:w-auto">
                                            انصراف
                                        </button>
                                    </div>
                                @endif

                                <div class="flex flex-col gap-3 border-t border-border/70 pt-6 sm:flex-row sm:items-center sm:justify-between">
                                    <button wire:click="prevStep" wire:loading.attr="disabled" wire:target="prevStep"
                                            class="purchase-button-soft w-full rounded-2xl px-5 py-3 text-sm font-black text-foreground transition hover:border-primary/40 sm:w-auto">
                                        بازگشت
                                    </button>
                                    @if ($editingInfo)
                                        <button wire:click="saveInfoAndContinue" wire:loading.attr="disabled" wire:target="saveInfoAndContinue"
                                                class="purchase-button inline-flex w-full items-center justify-center gap-2 rounded-2xl px-6 py-3 text-sm font-black transition disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto">
                                            <span wire:loading.remove wire:target="saveInfoAndContinue">ذخیره و ادامه</span>
                                            <span wire:loading wire:target="saveInfoAndContinue" class="inline-flex items-center gap-2">
                                                <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                </svg>
                                                در حال ذخیره
                                            </span>
                                        </button>
                                    @elseif ($requiresInfoCompletion)
                                        <button wire:click="startEditInfo" wire:loading.attr="disabled" wire:target="startEditInfo"
                                                class="purchase-button-warning inline-flex w-full items-center justify-center gap-2 rounded-2xl px-6 py-3 text-sm font-black transition disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto">
                                            ویرایش اطلاعات
                                        </button>
                                    @else
                                        <button wire:click="nextStep" wire:loading.attr="disabled" wire:target="nextStep"
                                                class="purchase-button inline-flex w-full items-center justify-center gap-2 rounded-2xl px-6 py-3 text-sm font-black transition disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto">
                                            <span wire:loading.remove wire:target="nextStep">ادامه و ورود به پرداخت</span>
                                            <span wire:loading wire:target="nextStep" class="inline-flex items-center gap-2">
                                                <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                </svg>
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
                                            <button wire:click="removeCoupon" wire:loading.attr="disabled" wire:target="removeCoupon"
                                                    class="rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-2 text-xs font-black text-red-300 transition hover:bg-red-500/15">
                                                حذف کد
                                            </button>
                                        </div>
                                    @else
                                        <div class="mt-4 flex flex-col gap-3 sm:flex-row">
                                            <input type="text" wire:model="couponCode" placeholder="کد تخفیف را وارد کنید"
                                                   class="purchase-field min-w-0 flex-1 rounded-2xl px-4 py-3 text-sm text-foreground placeholder:text-muted/60">
                                            <button wire:click="applyCoupon" wire:loading.attr="disabled" wire:target="applyCoupon"
                                                    class="purchase-button inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-black transition disabled:opacity-60">
                                                <span wire:loading.remove wire:target="applyCoupon">اعمال کد</span>
                                                <span wire:loading wire:target="applyCoupon" class="inline-flex items-center gap-2">
                                                    <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                    </svg>
                                                    در حال بررسی
                                                </span>
                                            </button>
                                        </div>
                                        @if ($couponError)
                                            <div class="mt-2 text-xs font-black text-red-400">{{ $couponError }}</div>
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
                                        <button wire:click="pay" wire:loading.attr="disabled" wire:target="pay" @disabled(! $agreedToTerms)
                                                class="purchase-button mt-6 inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-black transition disabled:cursor-not-allowed disabled:opacity-50">
                                            <span wire:loading.remove wire:target="pay">پرداخت نقدی</span>
                                            <span wire:loading wire:target="pay" class="inline-flex items-center gap-2">
                                                <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                </svg>
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
                                            <button wire:click="payInstallment" wire:loading.attr="disabled" wire:target="payInstallment" @disabled(! $agreedToTerms)
                                                    class="purchase-button mt-6 inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-black transition disabled:cursor-not-allowed disabled:opacity-50">
                                                <span wire:loading.remove wire:target="payInstallment">شروع با پیش‌پرداخت</span>
                                                <span wire:loading wire:target="payInstallment" class="inline-flex items-center gap-2">
                                                    <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                    </svg>
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
                                    <button wire:click="prevStep" wire:loading.attr="disabled" wire:target="prevStep"
                                            class="purchase-button-soft w-full rounded-2xl px-5 py-3 text-sm font-black text-foreground transition hover:border-primary/40 sm:w-auto">
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
            <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm" wire:click="closeTrialConfirm"></div>

            <div class="purchase-panel relative z-10 w-full max-w-lg rounded-[2rem] p-6 sm:p-8">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-emerald-500/20 bg-emerald-500/10 text-emerald-500">
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

                <div class="mt-6 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm leading-7 text-foreground">
                    بعد از شروع، شما به صفحهٔ انتظار برای تخصیص پشتیبان هدایت می‌شوید و ادامهٔ مراحل هفتهٔ آزمایشی از همان‌جا انجام می‌شود.
                </div>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <button wire:click="startTrialWeek" wire:loading.attr="disabled" wire:target="startTrialWeek"
                            class="purchase-button-trial inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-black transition disabled:opacity-60">
                        <span wire:loading.remove wire:target="startTrialWeek">بزن بریم یک هفته آزمایشی</span>
                        <span wire:loading wire:target="startTrialWeek" class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            در حال شروع
                        </span>
                    </button>
                    <button wire:click="closeTrialConfirm" wire:loading.attr="disabled" wire:target="closeTrialConfirm,startTrialWeek"
                            class="purchase-button-soft w-full rounded-2xl px-5 py-3 text-sm font-black text-foreground transition hover:border-primary/40">
                        فعلاً نه
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
