<div dir="rtl" class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-slate-900 dark:to-slate-800 px-4 py-8">
    <div class="max-w-xl w-full bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-l from-indigo-600 to-blue-600 px-6 py-5 text-white">
            <h1 class="text-2xl font-extrabold">پرداخت دوره</h1>
            <p class="text-white/80 text-sm mt-1">مبلغ بر اساس پایه و ماه فعلی محاسبه می‌شود</p>
        </div>

        <div class="p-6 md:p-10">
            @if (session('warning'))
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-xl p-4 mb-4">
                    {{ session('warning') }}
                </div>
            @endif

            @if ($error)
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4">{{ $error }}</div>
            @elseif ($priceInfo)
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between py-2 border-b">
                        <span>پایه</span>
                        <strong>{{ $priceInfo['grade_price']->grade }}</strong>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span>ماه فعلی از بسته</span>
                        <strong>ماه {{ $priceInfo['month_index'] + 1 }}</strong>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span>قیمت ماه</span>
                        <strong>{{ number_format($priceInfo['base_price']) }} تومان</strong>
                    </div>
                    @if ($priceInfo['discount_percent'] > 0)
                        <div class="flex justify-between py-2 border-b text-green-600">
                            <span>تخفیف</span>
                            <strong>{{ $priceInfo['discount_percent'] }}%</strong>
                        </div>
                    @endif
                    <div class="flex justify-between py-3 text-lg">
                        <span class="font-bold">مبلغ نهایی</span>
                        <strong class="text-indigo-600">{{ number_format($priceInfo['final_price']) }} تومان</strong>
                    </div>
                </div>

                <button wire:click="pay" wire:loading.attr="disabled"
                        class="w-full px-6 py-4 rounded-xl bg-gradient-to-l from-indigo-600 to-blue-600 text-white font-bold text-lg hover:shadow-lg transition-all disabled:opacity-50">
                    <span wire:loading.remove>پرداخت و رفتن به درگاه</span>
                    <span wire:loading>در حال انتقال…</span>
                </button>
            @else
                <div class="text-center py-8">
                    <p class="text-slate-500">قیمت برای پایه شما تعریف نشده است.</p>
                </div>
            @endif
        </div>
    </div>
</div>
