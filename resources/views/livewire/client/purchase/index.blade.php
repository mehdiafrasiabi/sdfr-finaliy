<div dir="rtl" class="relative mx-auto max-w-6xl px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
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
                border: 1px solid hsl(var(--border) / 0.65);
                background: linear-gradient(180deg, hsl(var(--secondary) / 0.72), hsl(var(--secondary) / 0.5));
                backdrop-filter: blur(14px);
                -webkit-backdrop-filter: blur(14px);
                box-shadow: inset 0 1px 0 hsl(var(--secondary) / 0.25);
            }

            .purchase-button {
                background: hsl(var(--primary));
                color: hsl(var(--primary-foreground));
                box-shadow: 0 18px 36px -20px hsl(var(--primary) / 0.8);
            }

            .purchase-button:hover {
                filter: brightness(1.05);
            }

            .purchase-button-soft {
                border: 1px solid hsl(var(--border) / 0.9);
                background: hsl(var(--secondary) / 0.8);
            }

            .purchase-field {
                border: 1px solid hsl(var(--border) / 0.85);
                background: hsl(var(--secondary));
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

            document.addEventListener('DOMContentLoaded', window.initPurchaseJalaliDatepicker);
            document.addEventListener('livewire:navigated', window.initPurchaseJalaliDatepicker);
        </script>
    @endpush

    <div wire:loading.flex wire:target="nextStep,prevStep,startEditInfo,cancelEditInfo,saveInfo,applyCoupon,removeCoupon,pay,payInstallment"
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
            $steps = [
                1 => ['title' => 'مرور پلن', 'caption' => 'خدمات و قیمت'],
                2 => ['title' => 'اطلاعات شما', 'caption' => 'بازبینی و اصلاح'],
                3 => ['title' => 'نهایی‌سازی', 'caption' => 'پرداخت و تایید'],
            ];
        @endphp

        <div class="purchase-shell rounded-[2rem] p-3 sm:p-6 lg:p-8">
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
                                            {{ $n }}
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
                    <div class="purchase-panel w-full rounded-[1.5rem] p-4 sm:p-5">
                        <div class="text-[11px] font-bold text-muted">خلاصه پرداخت</div>
                        <div class="mt-2 text-2xl font-black text-foreground sm:text-3xl">
                            {{ number_format($data['full_with_coupon']) }}
                            <span class="text-sm font-bold text-muted">تومان</span>
                        </div>
                        <div class="mt-3 flex flex-wrap gap-2 text-[11px] font-semibold text-muted">
                            <span class="rounded-full border border-border bg-secondary/60 px-3 py-1">{{ $price->grade_label }}</span>
                            <span class="rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-primary">
                                از {{ $data['month_label'] }} تا {{ $data['access_ends_label'] }}</span>
                        </div>
                    </div>
                </div>


                <div class="grid gap-6 xl:grid-cols-[1.45fr_.85fr]">
                    <section class="purchase-panel rounded-[1.75rem] p-4 sm:p-7">
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
                                            <div class="mt-1 text-sm font-black text-foreground">{{ $data['discount'] }}٪ تخفیف</div>
                                        </div>
                                    @endif
                                </div>

                                <div class="grid gap-4 lg:grid-cols-[1.1fr_.9fr]">
                                    <div class="rounded-[1.5rem] border border-border bg-secondary/40 p-5">
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
                                            مبلغ بر اساس <span class="font-black text-foreground">ماه ورود</span> محاسبه می‌شود. اقساط فقط از تیر تا اسفند فعال است و سررسیدها تا پایان اسفند همان سال چیده می‌شوند.
                                        </div>
                                    </div>

                                    <div class="rounded-[1.5rem] border border-primary/15 bg-primary/10 p-5">
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

                                        <div class="mt-6 rounded-[1.35rem] border border-white/10 bg-secondary/60 p-5 text-center">
                                            @if ($data['savings'] > 0)
                                                <div class="text-sm text-muted line-through">{{ number_format($data['original_total']) }} تومان</div>
                                            @endif
                                            <div class="mt-2 text-4xl font-black text-foreground sm:text-5xl">{{ number_format($data['total']) }}</div>
                                            <div class="mt-1 text-sm font-bold text-primary">مبلغ خرید نهایی</div>
                                            @if ($data['savings'] > 0)
                                                <div class="mt-3 inline-flex rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-xs font-black text-primary">
                                                    {{ number_format($data['savings']) }} تومان صرفه‌جویی
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mt-4 grid grid-cols-2 gap-3">
                                            <div class="rounded-2xl border border-border bg-secondary/60 p-3 text-center">
                                                <div class="text-[11px] text-muted">ماه‌های تا اسفند</div>
                                                <div class="mt-1 text-base font-black text-foreground">{{ $data['remaining_months'] }} ماه</div>
                                            </div>
                                            <div class="rounded-2xl border border-border bg-secondary/60 p-3 text-center">
                                                <div class="text-[11px] text-muted">پایان دسترسی</div>
                                                <div class="mt-1 text-base font-black text-foreground">{{ $data['access_ends_label'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if (($data['installment_open'] ?? false) && $data['installment_count'] > 0)
                                    <div class="rounded-[1.5rem] border border-border bg-secondary/40 p-5">
                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <div class="text-sm font-black text-foreground">امکان پرداخت اقساطی هم فعال است</div>
                                                <p class="mt-2 text-sm leading-7 text-muted">ابتدا پیش‌پرداخت را می‌پردازید و سپس مبلغ باقی‌مانده در {{ $data['installment_count'] }} قسط ماهانه تا {{ $data['installment_until_label'] }} تقسیم می‌شود.</p>
                                            </div>
                                            <div class="grid w-full gap-3 text-center sm:w-auto sm:min-w-[15rem] sm:grid-cols-2">
                                                <div class="rounded-2xl border border-primary/15 bg-primary/10 p-3">
                                                    <div class="text-[11px] font-bold text-primary">پیش‌پرداخت</div>
                                                    <div class="mt-1 text-base font-black text-foreground">{{ number_format($data['initial']) }}</div>
                                                </div>
                                                <div class="rounded-2xl border border-border bg-secondary/60 p-3">
                                                    <div class="text-[11px] text-muted">مبلغ هر قسط</div>
                                                    <div class="mt-1 text-base font-black text-foreground">{{ number_format($data['monthly']) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

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
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <h2 class="text-xl font-black text-foreground sm:text-2xl">بازبینی اطلاعات کاربر</h2>
                                        <p class="mt-2 text-sm leading-7 text-muted">اگر موردی نیاز به اصلاح دارد، همین‌جا ویرایشش کنید. برای ورود به مرحله پرداخت، «آدرس» و «محل تولد» باید کامل باشند.</p>
                                    </div>
                                    @unless ($editingInfo)
                                        <button wire:click="startEditInfo" wire:loading.attr="disabled" wire:target="startEditInfo"
                                                class="purchase-button-soft inline-flex items-center justify-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-black text-foreground transition hover:border-primary/40 hover:text-primary">
                                            ویرایش اطلاعات
                                        </button>
                                    @endunless
                                </div>

                                @if (! $editingInfo)
                                    @php
                                        $rows = [
                                            'نام' => $infoName,
                                            'نام و نام خانوادگی' => $infoNameFull ?: '—',
                                            'نام پدر' => $infoFatherName,
                                            'کد ملی' => $infoCodeMell,
                                            'پایه' => $gradeOptions[$infoGrade] ?? $infoGrade,
                                            'رشته' => $fieldOptions[$infoField] ?? $infoField,
                                            'تاریخ تولد' => $infoBirthDate ?: '—',
                                            'محل تولد' => $infoPlaceOfBirth ?: '<span class="text-red-400 text-xs font-black">تکمیل این فیلد الزامی است</span>',
                                            'موبایل پدر' => $infoFatherMobile,
                                            'موبایل مادر' => $infoMotherMobile,
                                            'استان' => $pi?->state?->name ?? '—',
                                            'شهر' => $pi?->city?->name ?? '—',
                                        ];
                                    @endphp

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        @foreach ($rows as $label => $value)
                                            <div class="rounded-2xl border border-border bg-secondary/45 p-4">
                                                <div class="text-[11px] font-bold text-muted">{{ $label }}</div>
                                                <div class="mt-2 text-sm font-black text-foreground">{!! $value !!}</div>
                                            </div>
                                        @endforeach
                                        <div class="rounded-2xl border border-border bg-secondary/45 p-4 sm:col-span-2">
                                            <div class="text-[11px] font-bold text-muted">آدرس</div>
                                            <div class="mt-2 text-sm font-black text-foreground">{!! $infoAddress ?: '<span class="text-red-400 text-xs font-black">تکمیل این فیلد الزامی است</span>' !!}</div>
                                        </div>
                                    </div>
                                @else
                                    @php
                                        $fieldClass = 'purchase-field w-full h-12 !ring-0 rounded-xl border border-border bg-secondary px-4 text-sm text-foreground outline-none transition-all focus:border-primary';
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

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        @foreach ($textFields as $model => $label)
                                            <div class="space-y-2">
                                                <label class="block text-xs font-black text-foreground">{{ $label }}</label>
                                                <input type="text" wire:model="{{ $model }}" class="{{ $fieldClass }}">
                                                @error($model)<div class="mt-1 text-xs font-bold text-red-400">{{ $message }}</div>@enderror
                                            </div>
                                        @endforeach

                                        <div class="space-y-2">
                                            <label class="block text-xs font-black text-foreground">تاریخ تولد</label>
                                            <input type="text" wire:model.blur="infoBirthDate" data-jdp data-jdp-max-date="today" inputmode="none" autocomplete="off" placeholder="۱۳۸۰/۰۱/۰۱" class="{{ $fieldClass }} cursor-pointer text-center" dir="ltr">
                                            @error('infoBirthDate')<div class="mt-1 text-xs font-bold text-red-400">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label class="block text-xs font-black text-foreground">پایه</label>
                                            <x-ui.select
                                                wire:model="infoGrade"
                                                :options="$gradeSelectOptions"
                                                placeholder="انتخاب پایه"
                                                name="infoGrade" />
                                            @error('infoGrade')<div class="mt-1 text-xs font-bold text-red-400">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label class="block text-xs font-black text-foreground">رشته</label>
                                            <x-ui.select
                                                wire:model="infoField"
                                                :options="$fieldSelectOptions"
                                                placeholder="انتخاب رشته"
                                                name="infoField" />
                                            @error('infoField')<div class="mt-1 text-xs font-bold text-red-400">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="space-y-2">
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

                                        <div class="space-y-2" wire:key="purchase-city-select-{{ $infoStateId ?: 'none' }}">
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

                                        <div class="space-y-2 sm:col-span-2">
                                            <label class="block text-xs font-black text-foreground">آدرس</label>
                                            <textarea wire:model="infoAddress" rows="3" class="purchase-field w-full !ring-0 rounded-xl border border-border bg-secondary px-4 py-3 text-sm leading-7 text-foreground outline-none transition-all focus:border-primary"></textarea>
                                            @error('infoAddress')<div class="mt-1 text-xs font-bold text-red-400">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-3 sm:flex-row">
                                        <button wire:click="saveInfo" wire:loading.attr="disabled" wire:target="saveInfo"
                                                class="purchase-button inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-black transition disabled:opacity-60 sm:w-auto">
                                            <span wire:loading.remove wire:target="saveInfo">ذخیره تغییرات</span>
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
                                    <button wire:click="nextStep" wire:loading.attr="disabled" wire:target="nextStep" @disabled($editingInfo)
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
                                        <input type="checkbox" wire:model.live="agreedToTerms" class="mt-1 h-5 w-5 rounded border-border bg-secondary text-primary focus:ring-primary">
                                        <span class="font-semibold">
                                            <a href="{{ route('client.terms') }}" target="_blank" class="font-black text-primary hover:underline">قوانین و شرایط</a>
                                            را خوانده‌ام و می‌پذیرم. همچنین می‌دانم در مدل اقساطی، پرداخت‌ها باید به‌ترتیب و در موعد مقرر انجام شوند.
                                        </span>
                                    </label>
                                    @unless ($agreedToTerms)
                                        <p class="mt-3 text-xs font-black text-primary">برای فعال شدن دکمه‌های پرداخت، ابتدا تایید قوانین لازم است.</p>
                                    @endunless
                                </div>

                                <div class="rounded-[1.5rem] border border-border bg-secondary/40 p-5">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                                        <div>
                                            <div class="text-sm font-black text-foreground">کد تخفیف نقدی</div>
                                            <p class="mt-1 text-xs leading-6 text-muted">این بخش فقط روی پرداخت نقدی اثر می‌گذارد و روی مبلغ اقساط اعمال نمی‌شود.</p>
                                        </div>
                                        @if ($couponDiscount > 0)
                                            <div class="rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-xs font-black text-primary">
                                                {{ $couponDiscount }}٪ تخفیف فعال
                                            </div>
                                        @endif
                                    </div>

                                    @if ($couponDiscount > 0)
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

                                <div class="grid gap-4 lg:grid-cols-2">
                                    <div class="rounded-[1.5rem] border border-border bg-secondary/45 p-4 sm:p-5">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <div class="text-sm font-black text-foreground">پرداخت نقدی</div>
                                                <p class="mt-2 text-sm leading-7 text-muted">کل مبلغ را یکجا پرداخت می‌کنید و دسترسی دوره بلافاصله فعال می‌شود.</p>
                                            </div>
                                            <span class="rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-[11px] font-black text-primary">سریع‌ترین مسیر</span>
                                        </div>
                                        <div class="mt-6 rounded-2xl border border-primary/15 bg-primary/10 p-4 text-center">
                                            @if ($couponDiscount > 0 && $data['full_with_coupon'] !== $data['total'])
                                                <div class="text-sm text-muted line-through">{{ number_format($data['total']) }} تومان</div>
                                            @endif
                                            <div class="mt-2 text-2xl font-black text-foreground sm:text-3xl">{{ number_format($data['full_with_coupon']) }}</div>
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

                                    <div class="rounded-[1.5rem] border border-border bg-secondary/45 p-4 sm:p-5">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <div class="text-sm font-black text-foreground">پرداخت اقساطی</div>
                                                <p class="mt-2 text-sm leading-7 text-muted">اگر بخواهید مبلغ را مرحله‌ای پرداخت کنید، این گزینه پیش‌پرداخت و اقساط بعدی را برایتان تنظیم می‌کند.</p>
                                            </div>
                                            <span class="rounded-full border border-border bg-secondary/60 px-3 py-1 text-[11px] font-black text-muted">منعطف‌تر</span>
                                        </div>

                                        @if (($data['installment_open'] ?? false) && $data['installment_count'] > 0)
                                            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                                                <div class="rounded-2xl border border-primary/15 bg-primary/10 p-4 text-center">
                                                    <div class="text-[11px] font-bold text-primary">پیش‌پرداخت</div>
                                                    <div class="mt-1 text-2xl font-black text-foreground">{{ number_format($data['initial']) }}</div>
                                                </div>
                                                <div class="rounded-2xl border border-border bg-secondary/60 p-4 text-center">
                                                    <div class="text-[11px] text-muted">هر قسط</div>
                                                    <div class="mt-1 text-2xl font-black text-foreground">{{ number_format($data['monthly']) }}</div>
                                                </div>
                                            </div>
                                            <p class="mt-4 text-sm leading-7 text-muted">
                                                بعد از پیش‌پرداخت، {{ $data['installment_count'] }} قسط ماهانه برای شما ثبت می‌شود؛ آخرین سررسید قسط {{ $data['installment_until_label'] }} است.
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
                                                    در این مقطع، زمان کافی برای تقسیط تا پایان اسفند باقی نمانده است و فقط پرداخت نقدی در دسترس است.
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
                    </section>

                    <aside class="space-y-4">
                        <div class="purchase-panel rounded-[1.75rem] p-5">
                            <div class="text-sm font-black text-foreground">برآورد سریع</div>
                            <div class="mt-4 space-y-3">
                                <div class="flex items-center justify-between gap-4 rounded-2xl border border-border bg-secondary/45 px-4 py-3">
                                    <span class="text-xs font-bold text-muted">قیمت اصلی</span>
                                    <span class="text-sm font-black text-foreground">{{ number_format($data['original_total']) }}</span>
                                </div>
                                <div class="flex items-center justify-between gap-4 rounded-2xl border border-primary/15 bg-primary/10 px-4 py-3">
                                    <span class="text-xs font-bold text-primary">قیمت نهایی</span>
                                    <span class="text-sm font-black text-foreground">{{ number_format($data['total']) }}</span>
                                </div>
                                <div class="flex items-center justify-between gap-4 rounded-2xl border border-border bg-secondary/45 px-4 py-3">
                                    <span class="text-xs font-bold text-muted">پرداخت نقدی با کد</span>
                                    <span class="text-sm font-black text-foreground">{{ number_format($data['full_with_coupon']) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="purchase-panel rounded-[1.75rem] p-5">
                            <div class="text-sm font-black text-foreground">یادداشت‌های مهم</div>
                            <div class="mt-4 space-y-3 text-sm leading-7 text-muted">
                                <div class="rounded-2xl border border-border bg-secondary/45 px-4 py-3">خرید فقط زمانی نهایی می‌شود که پرداخت در درگاه با موفقیت تکمیل شود.</div>
                                <div class="rounded-2xl border border-border bg-secondary/45 px-4 py-3">اگر قبلاً پرداخت معلق یا طرح اقساطی نیمه‌کاره داشته باشید، همین صفحه تلاش می‌کند همان فرایند را ادامه دهد.</div>
                                <div class="rounded-2xl border border-border bg-secondary/45 px-4 py-3">سررسید اقساط فقط در بازه تیر تا اسفند همان سال ساخته می‌شود.</div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    @endif
</div>
