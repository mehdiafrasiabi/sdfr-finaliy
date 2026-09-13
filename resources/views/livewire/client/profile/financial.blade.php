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

                        // نگاشتِ وضعیتِ طرح اقساطی روی وضعیت‌های استانداردِ x-ui.status-badge
                        $planStatusKey = match ($plan->status) {
                            \App\Models\InstallmentPlan::STATUS_COMPLETED => 'completed',
                            \App\Models\InstallmentPlan::STATUS_ACTIVE    => 'active',
                            default                                       => 'pending',
                        };
                        $planStatusLabel = match ($plan->status) {
                            \App\Models\InstallmentPlan::STATUS_COMPLETED => 'تسویه‌شده',
                            \App\Models\InstallmentPlan::STATUS_ACTIVE    => null, // برچسب پیش‌فرض «فعال» همین است
                            default                                       => 'در انتظار پیش‌پرداخت',
                        };
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
                                <x-ui.status-badge :status="$planStatusKey" :label="$planStatusLabel"/>
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

                            <x-ui.button href="{{ route('client.profile.installment') }}" wire:navigate
                                         variant="primary" icon="chevron-left" block>
                                مشاهده اقساط
                            </x-ui.button>
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
                            <x-ui.empty-state title="تراکنشی وجود ندارد!">
                                تاکنون هیچ پرداختی برای شما ثبت نشده است.
                            </x-ui.empty-state>
                        @else

                            @foreach($payments as $payment)
                                @php
                                    // نگاشتِ وضعیتِ تراکنش روی وضعیت‌های استانداردِ x-ui.status-badge
                                    // (پرداخت‌شده=paid, در انتظار=pending, لغو شده=voided — هر سه دقیقاً
                                    // همون برچسبِ پیش‌فرضِ کامپوننت رو دارن، پس نیازی به override نیست)
                                    $paymentStatusKey = match ($payment->status) {
                                        'completed' => 'paid',
                                        'pending'   => 'pending',
                                        default     => 'voided',
                                    };
                                @endphp
                                <div wire:key="payment-card-{{ $payment->id }}"
                                     x-data="{ expanded: false }"
                                     class="glass border border-border rounded-2xl overflow-hidden flex flex-col">

                                    {{-- ═══════════════════════════════════
                                         موبایل: تصویر بالا، اطلاعات وسط، دکمه‌ها پایین
                                    ════════════════════════════════════ --}}
                                    <div class="md:hidden">

                                        {{-- تصویر بالا (دقیقاً مشابه plan) --}}
                                        <x-ui.thumbnail class="w-full h-36">
                                            <img src="/client/icons/omormali.webp" class="w-[9rem] h-[9rem] object-contain drop-shadow-md" alt="">
                                        </x-ui.thumbnail>

                                        {{-- اطلاعات --}}
                                        <div class="p-4 space-y-3" dir="rtl">
                                            <h3 class="font-bold text-foreground text-base truncate">
                                                {{ $this->getPaymentDescription($payment) }}
                                            </h3>

                                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                                <x-ui.status-badge :status="$paymentStatusKey"/>

                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full">
                                                    {{ number_format($payment->amount) }} تومان
                                                </span>
                                            </div>
                                        </div>

                                        {{-- دکمه‌های موبایل --}}
                                        <div class="px-4 pb-4 space-y-2" dir="rtl">
                                            {{-- دستی (نه x-ui.button) چون آیکونش باید با چرخش ۱۸۰ درجه بین
                                                 باز/بسته انیمیشن بگیره؛ پراپ icon ثابته و اجازه‌ی همچین
                                                 بایندینگی رو نمی‌ده --}}
                                            <button type="button" @click="expanded = !expanded" data-elevated="false"
                                                    class="btn-press w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                <span>مشاهده جزئیات</span>
                                                <x-ui.icon name="chevron-down" class="w-4 h-4 transition-transform duration-200"
                                                           x-bind:class="{ 'rotate-180': expanded }"/>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- ═══════════════════════════════════
                                         دسکتاپ: تصویر سمت چپ، اطلاعات + دکمه‌ها وسط‌چین عمودی
                                    ════════════════════════════════════ --}}
                                    <div class="hidden md:flex flex-row min-h-[130px]">

                                        {{-- ستون تصویر (دقیقاً مشابه plan) — دارک‌مودِ دسکتاپ عمداً همون
                                             هگزِ سفارشیِ #1e3a5f/#1e40af نگه داشته شده، نه x-ui.thumbnail،
                                             چون با نسخه‌ی موبایل (blue-950/900) کمی فرق دارد و طبق درخواستِ
                                             قبلی این گرادیان‌های آبی دست‌کاری نشدند. --}}
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
                                                    <x-ui.status-badge :status="$paymentStatusKey"/>

                                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full">
                                                        مبلغ: {{ number_format($payment->amount) }} تومان
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- چپ: دکمه‌ها --}}
                                            <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                                                <button type="button" @click="expanded = !expanded" data-elevated="false"
                                                        class="btn-press inline-flex items-center justify-center gap-2 px-4 py-2 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                    <x-ui.icon name="chevron-down" class="w-4 h-4 transition-transform duration-200"
                                                               x-bind:class="{ 'rotate-180': expanded }"/>
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
                                                <x-ui.icon name="calendar" class="w-6 h-6 text-primary mb-2"/>
                                                <span class="text-xs text-muted">تاریخ تراکنش</span>
                                                <span class="font-bold text-foreground text-sm mt-1">{{ jalali($payment->created_at)->format('%d %B %Y') }}</span>
                                            </div>

                                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl text-center">
                                                <x-ui.icon name="wallet" class="w-6 h-6 mb-2 text-warning"/>
                                                <span class="text-xs text-muted">مبلغ تراکنش</span>
                                                <span class="font-bold text-foreground text-sm mt-1">{{ number_format($payment->amount) }} تومان</span>
                                            </div>

                                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl text-center">
                                                <x-ui.icon name="receipt" class="w-6 h-6 text-success mb-2"/>
                                                <span class="text-xs text-muted">شماره صورتحساب</span>
                                                <span class="font-bold text-foreground text-sm mt-1" dir="ltr">{{ $payment->refNumber ?? '—' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if($payments->hasPages())
                                <div class="mt-6">
                                    {{ $payments->links('components.ui.pagination') }}
                                </div>
                            @endif

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
