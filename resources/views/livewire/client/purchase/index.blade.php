<div class="max-w-3xl mx-auto px-4 py-8 text-slate-800 dark:text-slate-100">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold">خرید دوره</h1>
    </div>

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-200 px-4 py-3 border border-rose-200 dark:border-rose-800">
            {{ session('error') }}
        </div>
    @endif

    @if (! $price || ! $data)
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/40 p-6 text-center">
            <p class="text-slate-700 dark:text-slate-200 font-semibold">هنوز قیمتی برای پایهٔ شما تعریف نشده است.</p>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">لطفاً بعداً مراجعه کنید.</p>
        </div>
    @else
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-6 space-y-5">
            {{-- سرِ صفحه: پایه و ماه ورود --}}
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div>
                    <div class="text-sm text-slate-500 dark:text-slate-400 mb-1">پایه</div>
                    <div class="font-bold text-lg text-slate-900 dark:text-slate-100">{{ $price->grade_label }}</div>
                </div>
                <div class="text-end">
                    <div class="text-sm text-slate-500 dark:text-slate-400 mb-1">ماه ورود</div>
                    <div class="font-bold text-lg text-slate-900 dark:text-slate-100">
                        {{ $data['month_label'] }}
                        @if ($data['discount'] > 0)
                            <span class="ms-1 px-2 py-0.5 rounded text-xs bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300">تخفیف {{ $data['discount'] }}٪</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 p-3">
                    <div class="text-xs text-slate-500 dark:text-slate-400">نرخ مؤثر ماهانه</div>
                    <div class="font-bold mt-1">{{ number_format($data['effective_rate']) }} ت</div>
                </div>
                <div class="rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 p-3">
                    <div class="text-xs text-slate-500 dark:text-slate-400">ماه‌های باقی‌مانده تا خرداد</div>
                    <div class="font-bold mt-1">{{ $data['remaining_months'] }} ماه</div>
                </div>
            </div>

            <div class="rounded-lg bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800 p-3 text-sm text-indigo-700 dark:text-indigo-200">
                مدت دسترسی شما تا پایان خرداد (<strong>{{ $data['access_ends_label'] }}</strong>) خواهد بود. مبلغ بر اساس ماهِ ورود و تعداد ماه‌های باقی‌مانده محاسبه شده است.
            </div>

            {{-- دو حالت پرداخت --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- پرداخت کامل --}}
                <div class="rounded-2xl border-2 border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/20 p-5 flex flex-col">
                    <div class="text-sm font-bold text-emerald-700 dark:text-emerald-300 mb-1">پرداخت کامل</div>
                    <div class="text-2xl font-extrabold text-emerald-700 dark:text-emerald-200">
                        {{ number_format($data['full_with_coupon']) }}
                        <span class="text-sm font-normal">تومان</span>
                    </div>
                    @if ($couponDiscount > 0 && $data['full_with_coupon'] !== $data['total'])
                        <div class="text-xs text-slate-500 line-through mt-1">{{ number_format($data['total']) }} تومان</div>
                    @endif
                    <p class="text-xs text-emerald-700/80 dark:text-emerald-300/80 mt-2 flex-1">کل مبلغ دوره را یک‌جا پرداخت می‌کنید.</p>
                    <button wire:click="pay" wire:loading.attr="disabled"
                            class="mt-3 w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white font-bold shadow">
                        پرداخت کامل
                    </button>
                </div>

                {{-- پرداخت اقساطی --}}
                <div class="rounded-2xl border-2 border-indigo-200 dark:border-indigo-800 bg-indigo-50 dark:bg-indigo-900/20 p-5 flex flex-col">
                    <div class="text-sm font-bold text-indigo-700 dark:text-indigo-300 mb-1">پرداخت اقساطی</div>
                    @if ($data['installment_count'] > 0)
                        <div class="text-2xl font-extrabold text-indigo-700 dark:text-indigo-200">
                            {{ number_format($data['initial']) }}
                            <span class="text-sm font-normal">تومان پیش‌پرداخت</span>
                        </div>
                        <p class="text-xs text-indigo-700/80 dark:text-indigo-300/80 mt-2 flex-1">
                            سپس <strong>{{ $data['installment_count'] }}</strong> قسط ماهانهٔ
                            <strong>{{ number_format($data['monthly']) }}</strong> تومانی،
                            هر ماه در همین روز، تا پایان خرداد.
                        </p>
                        <button wire:click="payInstallment" wire:loading.attr="disabled"
                                class="mt-3 w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-bold shadow">
                            پرداخت پیش‌پرداخت و شروع اقساط
                        </button>
                    @else
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 flex-1">
                            چون فقط یک ماه تا پایان خرداد باقی مانده، امکان اقساط نیست. لطفاً پرداخت کامل را انتخاب کنید.
                        </p>
                    @endif
                </div>
            </div>

            {{-- کد تخفیف (فقط پرداخت کامل) --}}
            <div class="border-t border-slate-100 dark:border-slate-800 pt-4">
                <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">کد تخفیف <span class="text-xs text-slate-400">(روی پرداخت کامل)</span></label>
                @if ($couponDiscount > 0)
                    <div class="rounded-lg bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 p-3 text-sm text-emerald-700 dark:text-emerald-200">
                        ✔ {{ $couponNotice }}
                        <button wire:click="removeCoupon" class="float-end text-xs underline">حذف</button>
                    </div>
                @else
                    <div class="flex gap-2">
                        <input type="text" wire:model="couponCode" placeholder="کد تخفیف خود را وارد کنید"
                               class="flex-1 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm">
                        <button wire:click="applyCoupon"
                                class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-800 dark:bg-slate-200 dark:hover:bg-slate-100 dark:text-slate-800 text-white text-sm">
                            اعمال
                        </button>
                    </div>
                    @if ($couponError)
                        <div class="text-rose-600 dark:text-rose-400 text-sm mt-2">{{ $couponError }}</div>
                    @endif
                @endif
            </div>

            {{-- جدول مرجع ماه‌به‌ماه --}}
            <details class="border-t border-slate-100 dark:border-slate-800 pt-4">
                <summary class="cursor-pointer text-sm font-semibold text-slate-700 dark:text-slate-200">
                    جدول کامل قیمت بر اساس ماه ورود
                </summary>
                <div class="mt-3 overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300">
                            <tr>
                                <th class="px-3 py-2 text-start">ماه</th>
                                <th class="px-3 py-2 text-start">تخفیف</th>
                                <th class="px-3 py-2 text-start">کل پرداختی</th>
                                <th class="px-3 py-2 text-start">پیش‌پرداخت</th>
                                <th class="px-3 py-2 text-start">قسط هر ماه</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 dark:text-slate-200">
                            @foreach ($price->entryMonthsTable() as $m)
                                <tr class="border-t border-slate-100 dark:border-slate-800 {{ $m['is_current'] ? 'bg-emerald-50 dark:bg-emerald-900/20 font-semibold' : '' }}">
                                    <td class="px-3 py-2">{{ $m['label'] }} {{ $m['is_current'] ? '(جاری)' : '' }}</td>
                                    <td class="px-3 py-2">{{ $m['discount'] > 0 ? $m['discount'].'٪' : '—' }}</td>
                                    <td class="px-3 py-2">{{ number_format($m['total']) }}</td>
                                    <td class="px-3 py-2">{{ number_format($m['initial']) }}</td>
                                    <td class="px-3 py-2">{{ $m['installment_count'] > 0 ? number_format($m['installment']).' × '.$m['installment_count'] : '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </details>
        </div>
    @endif
</div>
