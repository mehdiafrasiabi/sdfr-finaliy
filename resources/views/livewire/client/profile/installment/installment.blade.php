<div class="max-w-7xl space-y-14 px-4 mx-auto">
    <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
        <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
            <livewire:client.profile.sidebar/>
        </div>

        <div class="lg:col-span-9 md:col-span-8">
            <div class="space-y-10">
                <div class="space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">اقساط شهریه</div>
                    </div>

                    @if (session('error'))
                        <div class="rounded-lg bg-red-500/10 text-red-500 px-4 py-3 text-sm">{{ session('error') }}</div>
                    @endif

                    @if (! $plan)
                        <div class="rounded-2xl border border-border bg-background p-8 text-center">
                            <p class="font-black text-foreground">شما طرح اقساطی فعالی ندارید.</p>
                            <a wire:navigate href="{{ route('client.purchase') }}"
                               class="inline-block mt-4 h-11 leading-[44px] rounded-full bg-primary text-white px-6">
                                خرید دوره
                            </a>
                        </div>
                    @else
                        {{-- خلاصهٔ طرح --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div class="rounded-xl border border-border bg-background p-4">
                                <div class="text-xs text-muted">کل مبلغ</div>
                                <div class="font-black text-foreground mt-1">{{ number_format($plan->total_amount) }} ت</div>
                            </div>
                            <div class="rounded-xl border border-border bg-background p-4">
                                <div class="text-xs text-muted">پیش‌پرداخت</div>
                                <div class="font-black text-foreground mt-1">{{ number_format($plan->initial_amount) }} ت</div>
                            </div>
                            <div class="rounded-xl border border-border bg-background p-4">
                                <div class="text-xs text-muted">اقساط پرداخت‌شده</div>
                                <div class="font-black text-foreground mt-1">{{ $plan->paidCount() }} از {{ $plan->installment_count }}</div>
                            </div>
                            <div class="rounded-xl border border-border bg-background p-4">
                                <div class="text-xs text-muted">پایان دسترسی</div>
                                <div class="font-black text-foreground mt-1">
                                    {{ $plan->access_ends_at ? \Morilog\Jalali\Jalalian::fromCarbon($plan->access_ends_at)->format('Y/m/d') : '—' }}
                                </div>
                            </div>
                        </div>

                        @if ($currentDue && $currentDue->isOverdue())
                            <div class="rounded-lg bg-red-500/10 text-red-500 px-4 py-3 text-sm font-bold">
                                قسطِ سررسیدشدهٔ پرداخت‌نشده دارید. تا تسویهٔ آن، دسترسی شما به پنل محدود است.
                            </div>
                        @endif

                        {{-- جدول اقساط --}}
                        <div class="relative overflow-x-auto rounded-2xl border border-border">
                            <table class="w-full text-sm text-right">
                                <thead class="text-xs text-muted uppercase bg-background border-b border-border">
                                <tr>
                                    <th class="whitespace-nowrap p-4">قسط</th>
                                    <th class="whitespace-nowrap p-4">سررسید</th>
                                    <th class="whitespace-nowrap p-4">مبلغ</th>
                                    <th class="whitespace-nowrap p-4">وضعیت</th>
                                    <th class="whitespace-nowrap p-4">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($installments as $inst)
                                    <tr class="odd:bg-secondary even:bg-background">
                                        <td class="p-4 font-black text-foreground">قسط {{ $inst->sequence }}</td>
                                        <td class="p-4 text-muted whitespace-nowrap">
                                            {{ \Morilog\Jalali\Jalalian::fromCarbon($inst->due_date)->format('Y/m/d') }}
                                        </td>
                                        <td class="p-4 font-black text-foreground">{{ number_format($inst->amount) }} ت</td>
                                        <td class="p-4">
                                            @if ($inst->isPaid())
                                                <span class="font-bold text-green-500">پرداخت‌شده @if($inst->paid_manually)<span class="text-xs text-muted">(دستی)</span>@endif</span>
                                            @elseif ($currentDue && $inst->id === $currentDue->id)
                                                <span class="font-bold text-amber-500">{{ $inst->isOverdue() ? 'سررسید گذشته' : 'در انتظار پرداخت' }}</span>
                                            @else
                                                <span class="text-muted">بعدی</span>
                                            @endif
                                        </td>
                                        <td class="p-4">
                                            @if (! $inst->isPaid() && $currentDue && $inst->id === $currentDue->id)
                                                <button wire:click="payCurrent" wire:loading.attr="disabled"
                                                        class="h-10 inline-flex items-center justify-center gap-2 rounded-full text-white px-5 bg-primary disabled:opacity-50">
                                                    پرداخت این قسط
                                                </button>
                                            @elseif (! $inst->isPaid())
                                                <span class="text-xs text-muted">ابتدا قسط‌های قبلی</span>
                                            @else
                                                <span class="text-xs text-muted">—</span>
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
    </div>
</div>
