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
                        <div class="rounded-xl bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-200 px-4 py-3 border border-rose-200 dark:border-rose-800 text-sm">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-200 px-4 py-3 border border-emerald-200 dark:border-emerald-800 text-sm">
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

                                <div class="border-t border-border pt-4 grid sm:grid-cols-2 gap-3">
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
                                    <div @class([
                                            "glass border rounded-2xl p-4 flex items-center justify-between gap-4 flex-wrap transition-all",
                                            "border-blue-500/40 bg-blue-950/20 ring-2 ring-blue-500/20" => $groupPaymentMode && $isSelected,
                                            "border-border" => !$groupPaymentMode || !$isSelected,
                                        ])>
                                        <div class="flex items-start gap-3">
                                            @if ($groupPaymentMode && $tab === 'due')
                                                <input type="checkbox"
                                                       value="{{ $inst->id }}"
                                                       wire:model.live="selectedInstallments"
                                                       class="mt-1 w-5 h-5 rounded border-border bg-secondary text-primary focus:ring-primary focus:ring-offset-secondary cursor-pointer">
                                            @endif
                                            <div class="space-y-1">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="font-bold text-foreground">قسط {{ $inst->sequence }} از {{ $count }}</span>
                                                    @if ($inst->status === 'paid')
                                                        <span class="px-2 py-0.5 rounded-full text-xs bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">پرداخت‌شده</span>
                                                    @elseif ($isOverdue)
                                                        <span class="px-2 py-0.5 rounded-full text-xs bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">سررسید گذشته</span>
                                                    @elseif ($isCurrent)
                                                        <span class="px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">قسط جاری</span>
                                                    @else
                                                        <span class="px-2 py-0.5 rounded-full text-xs bg-gray-200 text-gray-500 dark:bg-gray-700 dark:text-gray-400">در نوبت</span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-muted flex items-center gap-4 flex-wrap">
                                                    <span>تاریخ سررسید: <span class="text-foreground font-semibold">{{ jalali($inst->due_date)->format('%Y/%m/%d') }}</span></span>
                                                    <span class="text-foreground font-bold">{{ number_format($inst->amount) }} تومان</span>
                                                </div>
                                                @if ($inst->status === 'paid' && $inst->paid_at)
                                                    <div class="text-xs text-emerald-600 dark:text-emerald-400">
                                                        پرداخت‌شده در {{ jalali($inst->paid_at)->format('%Y/%m/%d') }}
                                                        @if ($inst->paid_manually) (ثبت دستی) @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            @if ($inst->status === 'paid')
                                                <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 text-sm font-semibold">✔ تسویه</span>
                                            @elseif ($isCurrent && !$groupPaymentMode)
                                                <button wire:click="payInstallment({{ $inst->id }})" wire:loading.attr="disabled"
                                                        class="inline-flex items-center justify-center px-6 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-bold text-sm transition-colors disabled:opacity-60">
                                                    پرداخت
                                                </button>
                                            @elseif (!$isCurrent && !$groupPaymentMode)
                                                <button disabled title="برای پرداخت این قسط، از حالت پرداخت گروهی استفاده کنید یا قسط جاری را بپردازید"
                                                        class="inline-flex items-center justify-center px-6 py-2 bg-secondary text-muted rounded-xl font-semibold text-sm cursor-not-allowed opacity-70">
                                                    پرداخت
                                                </button>
                                            @endif
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
                    <div class="flex items-center justify-between p-3 rounded-lg bg-secondary border-t-2 border-primary text-base">
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

