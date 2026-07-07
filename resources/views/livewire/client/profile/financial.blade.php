<div class="max-w-7xl space-y-14 px-4 mx-auto">
    <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

        <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
            <livewire:client.profile.sidebar/>
        </div>

        <div class="lg:col-span-9 md:col-span-8">
            <div class="space-y-10">

                {{-- ═══════ خلاصهٔ اقساط (در صورت داشتن طرح اقساطی) ═══════ --}}
                @if ($plan)
                    @php
                        $count   = (int) $plan->installment_count;
                        $paidCnt = $plan->paidCount();
                    @endphp
                    <div class="space-y-5">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">اقساط من</div>
                        </div>

                        <div class="glass border border-border rounded-2xl p-5 space-y-4 shadow-sm">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <h3 class="font-bold text-foreground">طرح اقساطی — {{ $plan->gradePrice?->grade_label ?? ('پایه ' . $plan->grade) }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-bold border
                                    @if($plan->status === \App\Models\InstallmentPlan::STATUS_COMPLETED) bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-300 dark:border-emerald-800
                                    @elseif($plan->status === \App\Models\InstallmentPlan::STATUS_ACTIVE) bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/40 dark:text-blue-300 dark:border-blue-800
                                    @else bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/40 dark:text-amber-300 dark:border-amber-800 @endif">
                                    @switch($plan->status)
                                        @case(\App\Models\InstallmentPlan::STATUS_COMPLETED) تسویه‌شده @break
                                        @case(\App\Models\InstallmentPlan::STATUS_ACTIVE) فعال @break
                                        @default در انتظار پیش‌پرداخت
                                    @endswitch
                                </span>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
                                <div class="rounded-xl bg-secondary p-3 text-center border border-border/50">
                                    <div class="text-xs text-muted">اقساط پرداخت‌شده</div>
                                    <div class="font-bold text-foreground mt-1">{{ $paidCnt }} از {{ $count }}</div>
                                </div>
                                <div class="rounded-xl bg-secondary p-3 text-center border border-border/50">
                                    <div class="text-xs text-muted">مبلغ هر قسط</div>
                                    <div class="font-bold text-foreground mt-1">{{ number_format($plan->monthly_amount) }} ت</div>
                                </div>
                                <div class="col-span-2 sm:col-span-1 rounded-xl bg-secondary p-3 text-center border border-border/50">
                                    <div class="text-xs text-muted">سررسید بعدی</div>
                                    <div class="font-bold text-foreground mt-1" dir="ltr">
                                        {{ $current ? jalali($current->due_date)->format('%Y/%m/%d') : '—' }}
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('client.profile.installment') }}" wire:navigate
                               class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-bold text-sm transition-colors">
                                مشاهده اقساط
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endif

                <div class="space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">تاریخچه تراکنش‌ها</div>
                    </div>
                    <div class="space-y-4">
                        @if($payments->isEmpty())
                            <div class="flex flex-col items-center justify-center py-12 space-y-4">
                                <img src="/client/svg/empty2.svg"
                                     class="w-full max-w-[370px] md:max-w-xs opacity-35 mb-4 md:mb-6"
                                     alt="پیامی وجود ندارد"/>
                                <div class="text-center space-y-2">
                                    <h2 class="font-bold text-xl text-foreground">تراکنشی وجود ندارد!</h2>
                                    <p class="text-muted text-sm">تاکنون هیچ پرداختی برای شما ثبت نشده است.</p>
                                </div>
                            </div>
                        @else

                            @foreach($payments as $payment)
                                <div wire:key="payment-card-{{ $payment->id }}"
                                     x-data="{ expanded: false }"
                                     class="glass border border-border rounded-2xl overflow-hidden flex flex-col">

                                    {{-- ═══════════════════════════════════
                                         موبایل: تصویر بالا، اطلاعات وسط، دکمه‌ها پایین
                                    ════════════════════════════════════ --}}
                                    <div class="md:hidden">

                                        {{-- تصویر بالا (دقیقاً مشابه plan) --}}
                                        <div class="w-full h-36 flex items-center justify-center bg-gradient-to-b from-blue-100 to-blue-200 dark:from-blue-950 dark:to-blue-900">
                                            <img src="/client/icons/omormali.webp" class="w-[9rem] h-[9rem] object-contain drop-shadow-md" alt="">
                                        </div>

                                        {{-- اطلاعات --}}
                                        <div class="p-4 space-y-3" dir="rtl">
                                            <h3 class="font-bold text-foreground text-base truncate">
                                                {{ $this->getPaymentDescription($payment) }}
                                            </h3>

                                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                                @if($payment->status === 'completed')
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs font-bold rounded-full">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> پرداخت‌شده
                                                    </span>
                                                @elseif($payment->status === 'pending')
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-xs font-bold rounded-full">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span> در انتظار
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-bold rounded-full">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> لغو شده
                                                    </span>
                                                @endif

                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full">
                                                    {{ number_format($payment->amount) }} تومان
                                                </span>
                                            </div>
                                        </div>

                                        {{-- دکمه‌های موبایل --}}
                                        <div class="px-4 pb-4 space-y-2" dir="rtl">
                                            <button @click="expanded = !expanded"
                                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                <span>مشاهده جزئیات</span>
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="w-4 h-4 transition-transform duration-200"
                                                     :class="{ 'rotate-180': expanded }"
                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- ═══════════════════════════════════
                                         دسکتاپ: تصویر سمت چپ، اطلاعات + دکمه‌ها وسط‌چین عمودی
                                    ════════════════════════════════════ --}}
                                    <div class="hidden md:flex flex-row min-h-[130px]">

                                        {{-- ستون تصویر (دقیقاً مشابه plan) --}}
                                        <div class="flex-shrink-0 w-[120px] flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-[#1e3a5f] dark:to-[#1e40af]">
                                            <img src="/client/icons/omormali.webp" class="w-22 h-22 object-contain drop-shadow-md" alt="">
                                        </div>

                                        {{-- محتوا --}}
                                        <div class="flex-1 p-4 flex items-center justify-between gap-4" dir="rtl">

                                            {{-- راست: عنوان + تاریخ + بج‌ها --}}
                                            <div class="space-y-3 flex-1 min-w-0">
                                                <h3 class="font-bold text-foreground text-base truncate">
                                                    {{ $this->getPaymentDescription($payment) }}
                                                </h3>
                                                <div class="flex flex-wrap items-center gap-2">
                                                    @if($payment->status === 'completed')
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs font-bold rounded-full">
                                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> پرداخت‌شده
                                                        </span>
                                                    @elseif($payment->status === 'pending')
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-xs font-bold rounded-full">
                                                            <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span> در انتظار
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-bold rounded-full">
                                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> لغو شده
                                                        </span>
                                                    @endif

                                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full">
                                                        مبلغ: {{ number_format($payment->amount) }} تومان
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- چپ: دکمه‌ها --}}
                                            <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                                                <button @click="expanded = !expanded"
                                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="w-4 h-4 transition-transform duration-200"
                                                         :class="{ 'rotate-180': expanded }"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ═══ جزئیات (مشترک موبایل و دسکتاپ) ═══ --}}
                                    <div x-show="expanded"
                                         x-cloak
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                         class=" border-border bg-background/50 p-4"
                                         style="display: none;">

                                        <div class="mb-4 p-3 bg-secondary rounded-xl text-center border border-border/50">
                                            <span class="text-xs text-muted">کد رهگیری</span>
                                            <span class="block font-bold text-foreground text-sm mt-1 tracking-widest" dir="ltr">{{ $payment->order_number }}</span>
                                        </div>

                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-xs text-muted">تاریخ تراکنش</span>
                                                <span class="font-bold text-foreground text-sm mt-1">{{ jalali($payment->created_at)->format('%d %B %Y') }}</span>
                                            </div>

                                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-xs text-muted">مبلغ تراکنش</span>
                                                <span class="font-bold text-foreground text-sm mt-1">{{ number_format($payment->amount) }} تومان</span>
                                            </div>

                                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <span class="text-xs text-muted">شماره صورتحساب</span>
                                                <span class="font-bold text-foreground text-sm mt-1" dir="ltr">{{ $payment->refNumber ?? '—' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if($payments->hasPages())
                                <div class="mt-6 flex justify-center">
                                    <div class="inline-flex items-center gap-1 p-1 rounded-xl bg-slate-100 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/50">
                                        {{ $payments->links('layouts.client.pagination') }}
                                    </div>
                                </div>
                            @endif

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
