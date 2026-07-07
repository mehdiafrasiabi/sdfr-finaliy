<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-8">

                    {{-- section:title --}}
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">اقساط من</div>
                    </div>

                    @if (session('error'))
                        <div class="rounded-xl bg-rose-50 dark:bg-rose-900 text-rose-700 dark:text-rose-200 px-4 py-3 border border-rose-200 dark:border-rose-800 text-sm">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="rounded-xl bg-emerald-50 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-200 px-4 py-3 border border-emerald-200 dark:border-emerald-800 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (! $plan)
                        {{-- بدون طرح اقساطی --}}
                        <div class="flex flex-col items-center justify-center space-y-8 py-16 glass border border-border rounded-2xl">
                            <img src="/client/svg/empty2.svg" class="w-full max-w-[300px] opacity-35" alt=""/>
                            <div class="text-center space-y-2">
                                <h2 class="font-bold text-xl text-foreground">طرح اقساطی ندارید</h2>
                                <p class="text-muted text-sm">شما خریدِ اقساطی فعالی ندارید.</p>
                            </div>
                        </div>
                    @else
                        @php
                            $count    = (int) $plan->installment_count;
                            $paidCnt  = $paidList->count();
                            $progress = $count > 0 ? round($paidCnt / $count * 100) : 0;
                        @endphp

                        {{-- ═══════ حالت پرداخت گروهی ═══════ --}}
                        @if($groupPaymentMode)
                            <div class="glass border-2 border-blue-500/30 rounded-2xl p-5 sm:p-6 space-y-4 relative transition-all">
                                <div class="absolute -top-3 right-5 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">حالت پرداخت گروهی</div>

                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <div>
                                        <h3 class="font-black text-foreground text-lg">انتخاب اقساط برای پرداخت</h3>
                                        <p class="text-xs text-muted mt-1">قسط‌های مورد نظر را به ترتیب انتخاب کنید (حداکثر ۵ قسط).</p>
                                    </div>
                                    <button wire:click="toggleGroupPaymentMode" class="btn btn-sm btn-outline-danger px-3 py-2">لغو</button>
                                </div>

                                @php
                                    $selectedAmount = $dueList->whereIn('id', $selectedInstallments)->sum('amount');
                                @endphp
                                <div class="sticky top-20 z-10">
                                    <div class="glass border border-border rounded-xl p-4 space-y-3 shadow-lg bg-background/80 backdrop-blur-sm">
                                         <div class="flex items-center justify-between text-sm">
                                            <span class="text-muted">تعداد اقساط انتخابی:</span>
                                            <span class="font-bold text-foreground">{{ count($selectedInstallments) }} قسط</span>
                                        </div>
                                        <div class="flex items-center justify-between text-base">
                                            <span class="text-muted">مبلغ کل:</span>
                                            <span class="font-extrabold text-primary">{{ number_format($selectedAmount) }} تومان</span>
                                        </div>
                                        <button wire:click="$set('showConfirmationModal', true)"
                                                wire:loading.attr="disabled"
                                                @if(count($selectedInstallments) == 0) disabled @endif
                                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-bold text-sm transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                            ادامه فرآیند پرداخت
                                        </button>
                                    </div>
                                </div>
                            </div>
                        {{-- ═══════ حالت عادی ═══════ --}}
                        @else
                             <div class="glass border border-border rounded-2xl p-5 sm:p-6 space-y-4">
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <h3 class="font-black text-foreground text-lg">طرح اقساطی — {{ $plan->gradePrice?->grade_label ?? ('پایه ' . $plan->grade) }}</h3>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if($plan->status === \App\Models\InstallmentPlan::STATUS_COMPLETED) bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300
                                        @elseif($plan->status === \App\Models\InstallmentPlan::STATUS_ACTIVE) bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300
                                        @else bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 @endif">
                                        @switch($plan->status)
                                            @case(\App\Models\InstallmentPlan::STATUS_COMPLETED) تسویه‌شده @break
                                            @case(\App\Models\InstallmentPlan::STATUS_ACTIVE) فعال @break
                                            @default در انتظار پیش‌پرداخت
                                        @endswitch
                                    </span>
                                </div>

                                <div class="divide-y divide-border text-sm">
                                   <div class="flex items-center justify-between py-2.5"><span class="text-muted">مبلغ کل خدمات</span> <span class="font-bold text-foreground">{{ number_format($plan->total_amount) }} تومان</span></div>
                                   <div class="flex items-center justify-between py-2.5"><span class="text-muted">مبلغ پرداخت اولیه</span> <span class="font-bold text-foreground">{{ number_format($plan->initial_amount) }} تومان</span></div>
                                   <div class="flex items-center justify-between py-2.5"><span class="text-muted">تعداد اقساط</span> <span class="font-bold text-foreground">{{ $plan->installment_count }} قسط</span></div>
                                   <div class="flex items-center justify-between py-2.5"><span class="text-muted">مبلغ هر قسط</span> <span class="font-bold text-foreground">{{ number_format($plan->monthly_amount) }} تومان</span></div>
                                   @if ($current) <div class="flex items-center justify-between py-2.5"><span class="text-muted">سر رسید قسط بعدی</span> <span class="font-bold text-foreground">{{ jalali($current->due_date)->format('%Y/%m/%d') }}</span></div> @endif
                                </div>

                                <div>
                                    <div class="h-2 w-full rounded-full bg-secondary overflow-hidden"><div class="h-full bg-primary rounded-full transition-all" style="width: {{ $progress }}%"></div></div>
                                    <div class="text-xs text-muted mt-1 text-end">{{ $progress }}٪ تکمیل شده</div>
                                </div>

                                <div class=" border-border pt-4 grid sm:grid-cols-2 gap-3">
                                    @if ($current)
                                        <button wire:click="payInstallment({{ $current->id }})" wire:loading.attr="disabled"
                                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-bold text-sm transition-colors disabled:opacity-60">
                                            پرداخت قسط جاری
                                        </button>
                                         <button wire:click="toggleGroupPaymentMode"
                                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-secondary hover:bg-zinc-700 text-foreground rounded-xl font-bold text-sm transition-colors">
                                            پرداخت گروهی
                                        </button>
                                    @elseif ($plan->status === \App\Models\InstallmentPlan::STATUS_COMPLETED)
                                        <div class="sm:col-span-2 text-center text-emerald-600 dark:text-emerald-400 text-sm font-semibold py-2">
                                            ✔ همهٔ اقساط تسویه شده است.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- ═══════ تب‌ها و لیست اقساط ═══════ --}}
                        <div class="inline-flex items-center gap-1 p-1 rounded-2xl bg-secondary">
                            <button wire:click="setTab('due')"
                                    @class([
                                        'px-4 py-2 rounded-xl text-sm font-semibold transition-colors inline-flex items-center gap-2',
                                        'bg-background text-foreground shadow' => $tab === 'due',
                                        'text-muted' => $tab !== 'due',
                                    ])>
                                قابل پرداخت
                                <span class="px-1.5 py-0.5 rounded-full text-xs bg-primary text-primary-foreground">{{ $dueList->count() }}</span>
                            </button>
                            <button wire:click="setTab('paid')"
                                    @class([
                                        'px-4 py-2 rounded-xl text-sm font-semibold transition-colors inline-flex items-center gap-2',
                                        'bg-background text-foreground shadow' => $tab === 'paid',
                                        'text-muted' => $tab !== 'paid',
                                    ])>
                                پرداخت‌شده
                                <span class="px-1.5 py-0.5 rounded-full text-xs bg-emerald-500 text-white">{{ $paidList->count() }}</span>
                            </button>
                        </div>

                        @php $list = $tab === 'paid' ? $paidList : $dueList; @endphp
                        @if ($list->isEmpty())
                            <div class="text-center text-muted text-sm py-10 glass border border-border rounded-2xl">
                                @if ($tab === 'paid') هنوز قسطی پرداخت نشده است. @else قسطِ قابل پرداختی وجود ندارد. @endif
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach ($list as $inst)
                                    @php
                                        $isCurrent = $current && $inst->id === $current->id;
                                        $isOverdue = $inst->status === 'pending' && $inst->isOverdue();
                                        $isSelected = in_array($inst->id, $selectedInstallments);
                                    @endphp

                                    <div wire:key="installment-card-{{ $inst->id }}"
                                         x-data="{ expanded: false }"
                                         @class([
                                            "glass border rounded-2xl overflow-hidden flex flex-col transition-all",
                                            "border-blue-500/40 bg-blue-950/20 ring-2 ring-blue-500/20" => $groupPaymentMode && $isSelected,
                                            "border-border" => !$groupPaymentMode || !$isSelected,
                                         ])
                                    >
                                        {{-- ═══════════════════════════════════
                                             موبایل: ساختار کپی‌شده از financial.blade.php
                                        ════════════════════════════════════ --}}
                                        <div class="md:hidden">
                                            <div class="w-full h-36 flex items-center justify-center bg-gradient-to-b from-blue-100 to-blue-200 dark:from-blue-950 dark:to-blue-900">
                                                <img src="{{ asset('client/icons/installment.webp') }}" class="w-[9rem] h-[9rem] object-contain drop-shadow-md" alt="Installment">
                                            </div>

                                            <div class="p-4 space-y-3" dir="rtl">
                                                @if ($groupPaymentMode && $tab === 'due')
                                                    <label class="flex items-center gap-4 cursor-pointer">
                                                        <input type="checkbox"
                                                               value="{{ $inst->id }}"
                                                               wire:model.live="selectedInstallments"
                                                               class="w-5 h-5 rounded border-border bg-secondary text-primary focus:ring-primary focus:ring-offset-secondary">
                                                        <span class="font-bold text-foreground">انتخاب قسط {{ $inst->sequence }}</span>
                                                    </label>
                                                @endif
                                                <h3 class="font-bold text-foreground text-base truncate">
                                                    قسط {{ $inst->sequence }} — سررسید: {{ jalali($inst->due_date)->format('%d %B %Y') }}
                                                </h3>
                                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                                    @if ($inst->status === 'paid')
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs font-bold rounded-full"><span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> پرداخت‌شده</span>
                                                    @elseif ($isOverdue)
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 text-xs font-bold rounded-full"><span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> سررسید گذشته</span>
                                                    @elseif ($isCurrent)
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold rounded-full"><span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span> قسط جاری</span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-gray-400 text-xs font-bold rounded-full"><span class="h-1.5 w-1.5 rounded-full bg-gray-500"></span> در نوبت</span>
                                                    @endif
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full">
                                                        {{ number_format($inst->amount) }} تومان
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="px-4 pb-4 space-y-2" dir="rtl">
                                                <button @click="expanded = !expanded" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                    <span>مشاهده جزئیات</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': expanded }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                </button>
                                                @if ($tab === 'due' && !$groupPaymentMode)
                                                     @if ($isCurrent)
                                                         <button wire:click="payInstallment({{ $inst->id }})" wire:loading.attr="disabled"
                                                                @class([
                                                                    'w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm transition-colors',
                                                                    'bg-rose-600 hover:bg-rose-700 text-white' => $isOverdue,
                                                                    'bg-primary hover:bg-primary/90 text-primary-foreground' => !$isOverdue,
                                                                ])>
                                                            پرداخت
                                                         </button>
                                                     @else
                                                         <button disabled class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm transition-colors bg-amber-500 text-white opacity-60 cursor-not-allowed">
                                                            پرداخت
                                                         </button>
                                                     @endif
                                                @endif
                                            </div>
                                        </div>

                                        {{-- ═══════════════════════════════════
                                             دسکتاپ: ساختار کپی‌شده از financial.blade.php
                                        ════════════════════════════════════ --}}
                                        <div class="hidden md:flex flex-row min-h-[130px]">
                                             @if ($groupPaymentMode && $tab === 'due')
                                                <div class="flex-shrink-0 w-[120px] flex items-center justify-center">
                                                     <input type="checkbox"
                                                           value="{{ $inst->id }}"
                                                           wire:model.live="selectedInstallments"
                                                           class="w-6 h-6 rounded-md border-border bg-secondary text-primary focus:ring-primary focus:ring-offset-secondary cursor-pointer">
                                                </div>
                                             @else
                                                <div class="flex-shrink-0 w-[120px] flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-[#1e3a5f] dark:to-[#1e40af]">
                                                    <img src="{{ asset('client/icons/installment.webp') }}" class="w-22 h-22 object-contain drop-shadow-md" alt="Installment">
                                                </div>
                                             @endif

                                            <div class="flex-1 p-4 flex items-center justify-between gap-4" dir="rtl">
                                                <div class="space-y-3 flex-1 min-w-0">
                                                    <h3 class="font-bold text-foreground text-base truncate">
                                                         قسط {{ $inst->sequence }} — سررسید: {{ jalali($inst->due_date)->format('%d %B %Y') }}
                                                    </h3>
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        @if ($inst->status === 'paid')
                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs font-bold rounded-full"><span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> پرداخت‌شده</span>
                                                        @elseif ($isOverdue)
                                                             <span class="inline-flex items-center gap-1 px-2 py-1 bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 text-xs font-bold rounded-full"><span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span> سررسید گذشته</span>
                                                        @elseif ($isCurrent)
                                                             <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-bold rounded-full"><span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span> قسط جاری</span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 dark:bg-gray-900/30 text-gray-600 dark:text-gray-400 text-xs font-bold rounded-full"><span class="h-1.5 w-1.5 rounded-full bg-gray-500"></span> در نوبت</span>
                                                        @endif
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full">
                                                            مبلغ: {{ number_format($inst->amount) }} تومان
                                                        </span>
                                                         @if ($inst->status === 'paid' && $inst->paid_at)
                                                            <div class="text-xs text-emerald-600 dark:text-emerald-400">
                                                                (پرداخت در {{ jalali($inst->paid_at)->format('Y/m/d') }})
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                                                     @if ($tab === 'due' && !$groupPaymentMode)
                                                        @if ($isCurrent)
                                                             <button wire:click="payInstallment({{ $inst->id }})" wire:loading.attr="disabled"
                                                                    @class([
                                                                        'inline-flex items-center justify-center gap-2 px-5 py-2 rounded-xl font-bold text-sm transition-colors',
                                                                        'bg-rose-600 hover:bg-rose-700 text-white' => $isOverdue,
                                                                        'bg-primary hover:bg-primary/90 text-primary-foreground' => !$isOverdue,
                                                                    ])>
                                                                پرداخت
                                                             </button>
                                                        @else
                                                             <button disabled class="inline-flex items-center justify-center gap-2 px-5 py-2 rounded-xl font-bold text-sm transition-colors bg-amber-500 text-white opacity-60 cursor-not-allowed">
                                                                پرداخت
                                                             </button>
                                                        @endif
                                                    @endif
                                                    <button @click="expanded = !expanded" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': expanded }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ═══ جزئیات (کپی‌شده از financial.blade.php) ═══ --}}
                                        <div x-show="expanded" x-cloak
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 -translate-y-1"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 -translate-y-1"
                                             class=" border-border bg-background/50 p-4" style="display: none;">

                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl text-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    <span class="text-xs text-muted">تاریخ سررسید</span>
                                                    <span class="font-bold text-foreground text-sm mt-1">{{ jalali($inst->due_date)->format('%d %B %Y') }}</span>
                                                </div>
                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl text-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-2" :class="$inst->status === 'paid' ? 'text-green-500' : 'text-orange-500'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    <span class="text-xs text-muted">وضعیت</span>
                                                    <span class="font-bold text-foreground text-sm mt-1">@if($inst->isPaid()) پرداخت شده @else در انتظار پرداخت @endif</span>
                                                </div>
                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl text-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    <span class="text-xs text-muted">صورتحساب</span>
                                                    <span class="font-bold text-foreground text-sm mt-1" dir="ltr">{{ $inst->payment->refNumber ?? '—' }}</span>
                                                </div>
                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl text-center">
                                                     <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-2 text-fuchsia-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                                                    <span class="text-xs text-muted">نوع پرداخت</span>
                                                    <span class="font-bold text-foreground text-sm mt-1">{{ $this->getInstallmentDescription($inst) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- مودال تایید پرداخت گروهی --}}
    @if ($showConfirmationModal)
        <div class="fixed inset-0 z-[100] flex items-end md:items-center justify-center p-0 md:p-4"
             aria-labelledby="modal-title" role="dialog" aria-modal="true"
        >
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" wire:click="$set('showConfirmationModal', false)"></div>

            <div class="relative glass bg-background/95 rounded-t-3xl md:rounded-2xl w-full max-w-md p-6 shadow-2xl border border-border">
                <div class="w-12 h-1 bg-white/20 rounded-full mx-auto mb-4 md:hidden" wire:click="$set('showConfirmationModal', false)"></div>

                <h3 class="text-lg font-bold text-foreground text-center" id="modal-title">تایید پرداخت گروهی</h3>
                <p class="text-sm text-muted text-center mt-2">شما در حال پرداخت همزمان چند قسط هستید.</p>

                <div class="mt-6 space-y-2 text-sm">
                    @php
                        $selectedToPay = $dueList->whereIn('id', $selectedInstallments);
                        $totalToPay = $selectedToPay->sum('amount');
                    @endphp
                    @foreach($selectedToPay as $item)
                        <div class="flex items-center justify-between p-2 rounded-lg bg-secondary">
                            <span>قسط {{ $item->sequence }} (سررسید: {{ jalali($item->due_date)->format('Y/m/d') }})</span>
                            <span class="font-semibold">{{ number_format($item->amount) }} تومان</span>
                        </div>
                    @endforeach
                    <div class="flex items-center justify-between p-3 rounded-lg bg-secondary border-primary text-base">
                        <span class="font-bold">جمع کل قابل پرداخت</span>
                        <span class="font-extrabold text-primary">{{ number_format($totalToPay) }} تومان</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button type="button" wire:click="$set('showConfirmationModal', false)"
                            class="px-6 py-3 bg-secondary rounded-xl text-sm font-semibold hover:bg-zinc-700 transition-colors">
                        انصراف
                    </button>
                    <button type="button" wire:click="paySelectedInstallments" wire:loading.attr="disabled"
                            class="px-6 py-3 bg-primary text-primary-foreground rounded-xl text-sm font-bold hover:opacity-90 transition-colors disabled:opacity-60">
                        تایید و پرداخت
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

