<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-indigo-50 dark:from-slate-950 dark:via-slate-900 dark:to-slate-900 py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white mb-2">خرید دوره سالانه</h1>
            <p class="text-slate-600 dark:text-slate-300">قیمت به‌صورت ماهانه پلکانی محاسبه می‌شود.</p>
        </div>

        <div class="rounded-3xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm p-6 md:p-8">
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">پایه تحصیلی</label>
                <select wire:model.live="selectedGradeId"
                        class="w-full rounded-2xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white px-4 py-3">
                    <option value="">پایه خود را انتخاب کنید...</option>
                    @foreach($grades as $g)
                        <option value="{{ $g->id }}">{{ $g->name }} - {{ $g->educationLevel?->name }}</option>
                    @endforeach
                </select>
            </div>

            @if($selectedGradeId && ! $pricing)
                <div class="rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 p-4 text-amber-800 dark:text-amber-200 text-sm">
                    برای این پایه هنوز قیمتی تعریف نشده است. لطفاً بعداً مراجعه کنید.
                </div>
            @endif

            @if($pricing && $breakdown)
                <div class="space-y-3 mb-6">
                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-900/50 p-5 border border-slate-200 dark:border-slate-700">
                        <h3 class="font-bold text-slate-900 dark:text-white mb-3">جزئیات قیمت</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between text-slate-700 dark:text-slate-300">
                                <span>قیمت پایه از تاریخ {{ jalali($pricing->starts_on)->format('%d %B %Y') }}</span>
                                <span class="font-semibold">{{ number_format($breakdown['base_price']) }} تومان</span>
                            </div>
                            <div class="flex justify-between text-slate-700 dark:text-slate-300">
                                <span>مدت دوره</span>
                                <span>{{ $breakdown['months_count'] }} ماه</span>
                            </div>
                            <div class="flex justify-between text-slate-700 dark:text-slate-300">
                                <span>قیمت هر ماه</span>
                                <span>{{ number_format($breakdown['monthly_step']) }} تومان</span>
                            </div>

                            @if($breakdown['elapsed_months'] > 0)
                                <div class="flex justify-between text-emerald-700 dark:text-emerald-400 pt-2 border-t border-slate-200 dark:border-slate-700">
                                    <span>کسر شده برای {{ $breakdown['elapsed_months'] }} ماه گذشته</span>
                                    <span class="font-semibold">- {{ number_format($breakdown['stepped_discount']) }} تومان</span>
                                </div>
                            @endif

                            @if($breakdown['grade_discount'] > 0)
                                <div class="flex justify-between text-emerald-700 dark:text-emerald-400">
                                    <span>تخفیف ثابت پایه</span>
                                    <span class="font-semibold">- {{ number_format($breakdown['grade_discount']) }} تومان</span>
                                </div>
                            @endif

                            @if($couponDiscount > 0)
                                <div class="flex justify-between text-emerald-700 dark:text-emerald-400">
                                    <span>کد تخفیف ({{ $appliedCoupon['code'] ?? '' }})</span>
                                    <span class="font-semibold">- {{ number_format($couponDiscount) }} تومان</span>
                                </div>
                            @endif

                            <div class="flex justify-between pt-3 border-t border-slate-200 dark:border-slate-700">
                                <span class="font-bold text-slate-900 dark:text-white">مبلغ نهایی</span>
                                <span class="font-extrabold text-lg text-indigo-600 dark:text-indigo-400">{{ number_format($finalAmount) }} تومان</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-4 text-blue-900 dark:text-blue-200 text-xs leading-6">
                        از ماه بعد، {{ number_format($breakdown['monthly_step']) }} تومان دیگر از قیمت کسر خواهد شد. هر ماه شمسی که از تاریخ شروع دوره می‌گذرد، مبلغ معادل یک ماه از مبلغ نهایی کم می‌شود.
                    </div>

                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 p-4">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">کد تخفیف (اختیاری)</label>
                        <div class="flex gap-2">
                            <input wire:model="couponCode" type="text"
                                   class="flex-1 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white px-3 py-2.5"
                                   placeholder="کد تخفیف خود را وارد کنید">
                            @if($appliedCoupon)
                                <button wire:click="removeCoupon" type="button" class="px-4 rounded-xl bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 font-semibold">حذف</button>
                            @else
                                <button wire:click="applyCoupon" type="button" class="px-4 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-semibold">اعمال</button>
                            @endif
                        </div>
                    </div>
                </div>

                <button wire:click="pay" wire:loading.attr="disabled"
                        class="w-full px-6 py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-60 text-white font-bold text-lg transition-colors">
                    <span wire:loading.remove wire:target="pay">پرداخت و ثبت‌نام</span>
                    <span wire:loading wire:target="pay">در حال انتقال به درگاه...</span>
                </button>
            @endif
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('client.welcome') }}" class="text-sm text-slate-600 dark:text-slate-400 hover:underline">بازگشت</a>
        </div>
    </div>
</div>
