<div class="max-w-3xl mx-auto px-4 py-8 text-slate-800 dark:text-slate-100">
    {{-- ─── نوار بالایی ─── --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold">خرید دوره</h1>

        @if (! $pendingTrial)
            <button wire:click="startTrial"
                    wire:loading.attr="disabled"
                    class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 disabled:opacity-50 text-white font-semibold shadow-md">
                شروع آزمایشی (۱ هفته)
            </button>
        @endif
    </div>

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-200 px-4 py-3 border border-rose-200 dark:border-rose-800">
            {{ session('error') }}
        </div>
    @endif

    @if ($pendingTrial)
        <div class="mb-6 rounded-2xl border border-amber-200 dark:border-amber-700 bg-amber-50 dark:bg-amber-900/30 p-5">
            <h3 class="font-bold text-amber-800 dark:text-amber-200 mb-1">شما هفتهٔ آزمایشی فعال دارید</h3>
            <p class="text-sm text-amber-700 dark:text-amber-300 mb-3">
                در انتظار تخصیص پشتیبان جذب هستید. می‌توانید آزمایشی را لغو کنید و مستقیماً دوره را خریداری نمایید.
            </p>
            <a href="{{ route('client.profile.waiting-for-supporter') }}"
               wire:navigate
               class="inline-block px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold">
                بازگشت به صفحهٔ انتظار
            </a>
        </div>
    @endif

    @if (! $price)
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/40 p-6 text-center">
            <p class="text-slate-700 dark:text-slate-200 font-semibold">هنوز قیمتی برای پایهٔ شما تعریف نشده است.</p>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">لطفاً بعداً مراجعه کنید.</p>
        </div>
    @else
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-6 space-y-5">
            <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
                <div class="text-sm text-slate-500 dark:text-slate-400 mb-1">پایه:</div>
                <div class="font-bold text-lg text-slate-900 dark:text-slate-100">{{ $price->grade_label }}</div>
            </div>

            {{-- قیمت‌ها --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800 p-4 text-center">
                    <div class="text-xs text-emerald-600 dark:text-emerald-300 mb-1">قیمت این ماه</div>
                    <div class="text-2xl font-extrabold text-emerald-700 dark:text-emerald-200">{{ number_format($effectiveThisMonth) }}</div>
                    <div class="text-xs text-emerald-600 dark:text-emerald-300 mt-1">تومان</div>
                </div>
                <div class="rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 p-4 text-center">
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">قیمت ماه بعد (پلکانی)</div>
                    <div class="text-2xl font-extrabold text-slate-700 dark:text-slate-200">{{ number_format($nextMonthPrice) }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">تومان</div>
                </div>
            </div>

            <div class="rounded-lg bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800 p-3 text-sm text-indigo-700 dark:text-indigo-200">
                <strong>توجه:</strong> قیمت دوره پلکانی است. هر ماهی که از شروع دوره می‌گذرد،
                <strong>{{ number_format($price->monthly_reduction) }}</strong> تومان از قیمت کم می‌شود. این یعنی اگر دیرتر بپیوندید، فقط بابت تعداد ماه‌هایی که از خدمات استفاده می‌کنید پرداخت می‌نمایید.
            </div>

            @if ($activeDailyDiscount)
                <div class="rounded-lg bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 p-3 text-sm text-rose-700 dark:text-rose-200">
                    <strong>تخفیف ویژهٔ امروز:</strong> {{ $activeDailyDiscount->discount_percentage }}٪
                    @if ($activeDailyDiscount->label) — {{ $activeDailyDiscount->label }} @endif
                </div>
            @endif

            {{-- جدول ماه‌به‌ماه --}}
            <details class="border-t border-slate-100 dark:border-slate-800 pt-4">
                <summary class="cursor-pointer text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                    مشاهده قیمت ماه‌به‌ماه
                </summary>
                <div class="mt-3 overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300">
                            <tr>
                                <th class="px-3 py-2 text-start">ماه</th>
                                <th class="px-3 py-2 text-start">قیمت ثابت</th>
                                <th class="px-3 py-2 text-start">قیمت نهایی</th>
                                <th class="px-3 py-2 text-start">تخفیف</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 dark:text-slate-200">
                            @foreach ($monthsTable as $m)
                                <tr class="border-t border-slate-100 dark:border-slate-800 {{ $m['is_current'] ? 'bg-emerald-50 dark:bg-emerald-900/20 font-semibold' : '' }}">
                                    <td class="px-3 py-2">{{ $m['jalali_label'] }} {{ $m['is_current'] ? '(جاری)' : '' }}</td>
                                    <td class="px-3 py-2">{{ number_format($m['stepped']) }}</td>
                                    <td class="px-3 py-2">{{ number_format($m['effective']) }}</td>
                                    <td class="px-3 py-2">{{ $m['has_discount'] ? $m['discount_pct'].'٪' : '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </details>

            {{-- کد تخفیف --}}
            <div class="border-t border-slate-100 dark:border-slate-800 pt-4">
                <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">کد تخفیف</label>

                @if ($couponDiscount > 0)
                    <div class="rounded-lg bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 p-3 text-sm text-emerald-700 dark:text-emerald-200 mb-3">
                        ✔ {{ $couponNotice }}
                        <button wire:click="removeCoupon" class="float-end text-xs underline">حذف</button>
                    </div>
                @else
                    <div class="flex gap-2">
                        <input type="text" wire:model="couponCode"
                               placeholder="کد تخفیف خود را وارد کنید"
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

            {{-- دکمه پرداخت --}}
            <div class="border-t border-slate-100 dark:border-slate-800 pt-5">
                <button wire:click="pay"
                        wire:loading.attr="disabled"
                        class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white font-bold shadow-md">
                    پرداخت
                    <span class="block text-xs font-normal mt-1">
                        مبلغ قابل پرداخت: {{ number_format($effectiveThisMonth) }} تومان
                    </span>
                </button>
            </div>
        </div>
    @endif
</div>
