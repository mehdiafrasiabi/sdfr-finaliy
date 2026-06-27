<div class="px-4 py-6">
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('manager.dashboard.analytics') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">اقساط دانش‌آموزان</li>
            </ol>
        </nav>
    </div>

    {{-- فیلتر وضعیت --}}
    <div class="flex items-center gap-2 flex-wrap mb-4">
        @foreach (['all' => 'همه'] + \App\Livewire\Manager\Installment\Index::STATUS_LABELS as $key => $label)
            <button wire:click="setStatusFilter('{{ $key }}')"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                        {{ $statusFilter === $key
                            ? 'bg-indigo-600 text-white shadow'
                            : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- لیست طرح‌ها --}}
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800">
                <h4 class="m-0 text-slate-800 dark:text-slate-100 font-bold">طرح‌های اقساطی</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300">
                        <tr>
                            <th class="text-start px-4 py-3">دانش‌آموز</th>
                            <th class="text-start px-4 py-3">کل</th>
                            <th class="text-start px-4 py-3">اقساط</th>
                            <th class="text-start px-4 py-3">وضعیت</th>
                            <th class="text-start px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 dark:text-slate-200">
                        @forelse ($plans as $plan)
                            <tr class="border-t border-slate-100 dark:border-slate-800 {{ $selectedPlanId === $plan->id ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                                <td class="px-4 py-3 font-semibold">{{ $plan->user?->name ?? ('کاربر #' . $plan->user_id) }}</td>
                                <td class="px-4 py-3">{{ number_format($plan->total_amount) }}</td>
                                <td class="px-4 py-3">{{ $plan->paidCount() }}/{{ $plan->installment_count }}</td>
                                <td class="px-4 py-3">
                                    @switch($plan->status)
                                        @case('pending')
                                            <span class="px-2 py-0.5 rounded text-xs bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">در انتظار</span>
                                            @break
                                        @case('active')
                                            <span class="px-2 py-0.5 rounded text-xs bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">فعال</span>
                                            @break
                                        @case('completed')
                                            <span class="px-2 py-0.5 rounded text-xs bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300">تسویه</span>
                                            @break
                                        @default
                                            <span class="px-2 py-0.5 rounded text-xs bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300">{{ \App\Livewire\Manager\Installment\Index::STATUS_LABELS[$plan->status] ?? $plan->status }}</span>
                                    @endswitch
                                </td>
                                <td class="px-4 py-3">
                                    <button wire:click="selectPlan({{ $plan->id }})"
                                            class="px-3 py-1 rounded-md bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-semibold">
                                        مشاهده
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-slate-500 py-8">هیچ طرح اقساطی با این فیلتر یافت نشد.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $plans->links() }}</div>
        </div>

        {{-- جزئیات طرح انتخاب‌شده --}}
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800">
                <h4 class="m-0 text-slate-800 dark:text-slate-100 font-bold">جزئیات و ثبت دستی</h4>
            </div>
            <div class="p-5">
                @if (! $selectedPlan)
                    <p class="text-slate-500 dark:text-slate-400 text-sm">یک طرح را از لیست انتخاب کنید.</p>
                @else
                    <div class="mb-4 text-sm text-slate-700 dark:text-slate-200">
                        <div class="font-bold">{{ $selectedPlan->user?->name }}</div>
                        <div class="text-xs text-slate-500 mt-1">
                            کل: {{ number_format($selectedPlan->total_amount) }} ت — پیش‌پرداخت: {{ number_format($selectedPlan->initial_amount) }} ت —
                            هر قسط: {{ number_format($selectedPlan->monthly_amount) }} ت
                        </div>
                    </div>

                    @if ($selectedPlan->status === 'pending')
                        <div class="rounded-lg bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 p-3 mb-4 text-sm text-amber-700 dark:text-amber-300 flex items-center justify-between gap-2">
                            <span>پیش‌پرداخت این طرح هنوز ثبت نشده است.</span>
                            <button wire:click="openActivateModal({{ $selectedPlan->id }})"
                                    class="px-3 py-1 rounded-md bg-amber-500 hover:bg-amber-600 text-white text-xs whitespace-nowrap">
                                فعال‌سازی دستی
                            </button>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300">
                                <tr>
                                    <th class="text-start px-3 py-2">قسط</th>
                                    <th class="text-start px-3 py-2">سررسید</th>
                                    <th class="text-start px-3 py-2">مبلغ</th>
                                    <th class="text-start px-3 py-2">وضعیت</th>
                                    <th class="text-start px-3 py-2"></th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-700 dark:text-slate-200">
                                @foreach ($selectedPlan->installments as $inst)
                                    <tr class="border-t border-slate-100 dark:border-slate-800">
                                        <td class="px-3 py-2">{{ $inst->sequence }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap">{{ \Morilog\Jalali\Jalalian::fromCarbon($inst->due_date)->format('Y/m/d') }}</td>
                                        <td class="px-3 py-2">{{ number_format($inst->amount) }}</td>
                                        <td class="px-3 py-2">
                                            @if ($inst->status === 'paid')
                                                <span class="text-emerald-600 dark:text-emerald-400 font-semibold">پرداخت‌شده @if($inst->paid_manually)(دستی)@endif</span>
                                            @elseif ($inst->isOverdue())
                                                <span class="text-rose-600 dark:text-rose-400">سررسید گذشته</span>
                                            @else
                                                <span class="text-slate-500">پرداخت‌نشده</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2">
                                            @if ($inst->status !== 'paid')
                                                <button wire:click="openPayModal({{ $inst->id }})"
                                                        class="px-3 py-1 rounded-md bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">
                                                    ثبت پرداخت
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══ مودال ثبت دستی قسط ═══ --}}
    @if ($payInstallment)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 dark:bg-black/80 backdrop-blur-sm" wire:key="pay-modal">
            <div class="w-full max-w-md rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-xl">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                    <h5 class="m-0 font-bold text-slate-800 dark:text-slate-100">ثبت دستی پرداخت قسط</h5>
                    <button wire:click="closePayModal" class="text-slate-500 hover:text-slate-700 dark:text-slate-300">✕</button>
                </div>
                <div class="p-5 space-y-3">
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        قسط شمارهٔ <strong>{{ $payInstallment->sequence }}</strong> به مبلغ
                        <strong>{{ number_format($payInstallment->amount) }}</strong> تومان به‌صورت دستی پرداخت‌شده ثبت می‌شود.
                    </p>
                    <div>
                        <label class="block text-xs font-semibold mb-1 text-slate-600 dark:text-slate-300">یادداشت (اختیاری)</label>
                        <textarea wire:model="manualNote" rows="2" placeholder="مثلاً: واریز کارت‌به‌کارت — کد پیگیری ..."
                                  class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm"></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-slate-100 dark:border-slate-800">
                    <button wire:click="closePayModal" class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm">انصراف</button>
                    <button wire:click="confirmPay" wire:loading.attr="disabled"
                            class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold disabled:opacity-50">
                        ثبت پرداخت
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══ مودال فعال‌سازی طرح ═══ --}}
    @if ($activatePlan)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 dark:bg-black/80 backdrop-blur-sm" wire:key="activate-modal">
            <div class="w-full max-w-md rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-xl">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                    <h5 class="m-0 font-bold text-slate-800 dark:text-slate-100">فعال‌سازی دستی طرح اقساطی</h5>
                    <button wire:click="closeActivateModal" class="text-slate-500 hover:text-slate-700 dark:text-slate-300">✕</button>
                </div>
                <div class="p-5">
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        طرح اقساطیِ <strong>{{ $activatePlan->user?->name }}</strong> به‌عنوان «پیش‌پرداخت‌شده» فعال می‌شود و دسترسی دانش‌آموز
                        تا پایان خرداد باز خواهد شد. این کار برای پرداخت‌های خارج از درگاه است.
                    </p>
                </div>
                <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-slate-100 dark:border-slate-800">
                    <button wire:click="closeActivateModal" class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm">انصراف</button>
                    <button wire:click="confirmActivate" wire:loading.attr="disabled"
                            class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold disabled:opacity-50">
                        فعال‌سازی
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
