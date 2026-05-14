<div class="max-w-3xl mx-auto px-4 py-8">
    {{-- ─────── نوار بالایی: دکمهٔ «شروع آزمایشی» ─────── --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold">خرید دوره</h1>

        @if (! $pendingTrial)
            <button wire:click="startTrial"
                    wire:loading.attr="disabled"
                    class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-semibold shadow-md">
                شروع آزمایشی (۱ هفته)
            </button>
        @endif
    </div>

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-50 text-red-700 px-4 py-3 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    @if ($pendingTrial)
        <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 p-5">
            <h3 class="font-bold text-amber-800 mb-1">شما هفتهٔ آزمایشی فعال دارید</h3>
            <p class="text-sm text-amber-700 mb-3">
                در انتظار تخصیص پشتیبان جذب هستید. می‌توانید آزمایشی را لغو کنید و
                مستقیماً دوره را خریداری نمایید.
            </p>
            <a href="{{ route('client.profile.waiting-for-supporter') }}"
               class="inline-block px-4 py-2 rounded-lg bg-amber-500 text-white text-sm font-semibold">
                بازگشت به صفحهٔ انتظار
            </a>
        </div>
    @endif

    @if (! $price)
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center">
            <p class="text-slate-700 font-semibold">هنوز قیمتی برای پایهٔ شما تعریف نشده است.</p>
            <p class="text-sm text-slate-500 mt-2">لطفاً بعداً مراجعه کنید.</p>
        </div>
    @else
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6 space-y-5">
            <div class="border-b border-slate-100 pb-4">
                <div class="text-sm text-slate-500 mb-1">پلن:</div>
                <div class="font-bold text-lg">
                    {{ $price->label ?? ($price->grade_label . ' / ' . $price->field_label) }}
                </div>
            </div>

            {{-- قیمت‌ها --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-xl bg-emerald-50 p-4 text-center">
                    <div class="text-xs text-emerald-600 mb-1">قیمت این ماه</div>
                    <div class="text-2xl font-extrabold text-emerald-700">
                        {{ number_format($effectiveThisMonth) }}
                    </div>
                    <div class="text-xs text-emerald-600 mt-1">تومان</div>
                </div>
                <div class="rounded-xl bg-slate-50 p-4 text-center">
                    <div class="text-xs text-slate-500 mb-1">قیمت ماه بعد (پلکانی)</div>
                    <div class="text-2xl font-extrabold text-slate-700">
                        {{ number_format($nextMonthPrice) }}
                    </div>
                    <div class="text-xs text-slate-500 mt-1">تومان</div>
                </div>
            </div>

            <div class="rounded-lg bg-blue-50 border border-blue-100 p-3 text-sm text-blue-700">
                <strong>توجه:</strong> قیمت به‌صورت پلکانی است؛ هر ماه که می‌گذرد،
                <strong>{{ number_format($price->monthly_reduction) }}</strong> تومان از قیمت کل کم می‌شود.
                ماه بعد قیمت برابر با
                <strong>{{ number_format($nextMonthPrice) }}</strong>
                تومان خواهد بود.
            </div>

            @if ($activeDailyDiscount)
                <div class="rounded-lg bg-rose-50 border border-rose-200 p-3 text-sm text-rose-700">
                    <strong>تخفیف ویژهٔ امروز:</strong>
                    {{ $activeDailyDiscount->discount_percentage }}٪
                    @if ($activeDailyDiscount->label) — {{ $activeDailyDiscount->label }} @endif
                </div>
            @endif

            {{-- کد تخفیف --}}
            <div class="border-t border-slate-100 pt-4">
                <label class="block text-sm font-semibold mb-2">کد تخفیف</label>

                @if ($couponDiscount > 0)
                    <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-3 text-sm text-emerald-700 mb-3">
                        ✔ {{ $couponNotice }}
                        <button wire:click="removeCoupon" class="float-end text-xs underline">حذف</button>
                    </div>
                @else
                    <div class="flex gap-2">
                        <input type="text" wire:model="couponCode"
                               placeholder="کد تخفیف خود را وارد کنید"
                               class="flex-1 border border-slate-200 rounded-lg px-4 py-2 text-sm">
                        <button wire:click="applyCoupon" class="px-4 py-2 rounded-lg bg-slate-700 text-white text-sm">
                            اعمال
                        </button>
                    </div>
                    @if ($couponError)
                        <div class="text-red-600 text-sm mt-2">{{ $couponError }}</div>
                    @endif
                @endif
            </div>

            {{-- دکمه پرداخت --}}
            <div class="border-t border-slate-100 pt-5">
                <button wire:click="pay"
                        wire:loading.attr="disabled"
                        class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-md">
                    پرداخت
                    <span class="block text-xs font-normal mt-1">
                        مبلغ قابل پرداخت: {{ number_format($effectiveThisMonth) }} تومان
                    </span>
                </button>
            </div>
        </div>
    @endif
</div>
