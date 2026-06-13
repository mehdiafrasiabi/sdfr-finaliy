<div class="px-4 py-6">
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('manager.dashboard.analytics') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ route('manager.grade-price.index') }}">قیمت پایه‌ها</a></li>
                <li class="breadcrumb-item active">{{ $price->grade_label }}</li>
            </ol>
        </nav>
    </div>

    @if (session('success'))
        <div class="rounded-lg mb-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-200 border border-emerald-200 dark:border-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- خلاصهٔ پلن --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-4">
            <div class="text-xs text-slate-500 dark:text-slate-400">پایه</div>
            <div class="text-slate-900 dark:text-slate-100 font-bold text-lg mt-1">{{ $price->grade_label }}</div>
        </div>
        <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-4">
            <div class="text-xs text-slate-500 dark:text-slate-400">نرخ ماهانه</div>
            <div class="text-slate-900 dark:text-slate-100 font-bold text-lg mt-1">{{ number_format($price->monthly_rate) }} ت</div>
        </div>
        <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-4">
            <div class="text-xs text-slate-500 dark:text-slate-400">پیش‌پرداخت</div>
            <div class="text-slate-900 dark:text-slate-100 font-bold text-lg mt-1">{{ (int) ($price->initial_percentage ?? 30) }}٪</div>
        </div>
        <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-4">
            <div class="text-xs text-slate-500 dark:text-slate-400">سال خدمت</div>
            <div class="text-slate-900 dark:text-slate-100 font-bold text-lg mt-1">تیر {{ $price->serviceYear() }} تا خرداد {{ $price->serviceYear() + 1 }}</div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm">
        <div class="flex items-center justify-between p-5 border-b border-slate-100 dark:border-slate-800">
            <div>
                <h4 class="m-0 text-slate-800 dark:text-slate-100 font-bold">تخفیف زودهنگام بر اساس ماه ورود</h4>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    فقط «درصد تخفیف» هر ماه را تغییر دهید؛ بقیهٔ ستون‌ها به‌صورت زنده محاسبه می‌شوند.
                    «کل پرداختی» = نرخ مؤثر × ماه‌های باقی‌مانده تا خرداد.
                </p>
            </div>
            <button wire:click="saveAll" wire:loading.attr="disabled"
                    class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold disabled:opacity-50 whitespace-nowrap">
                ذخیرهٔ همه
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300">
                    <tr>
                        <th class="text-start px-3 py-3">ماه ورود</th>
                        <th class="text-start px-3 py-3">تخفیف (٪)</th>
                        <th class="text-start px-3 py-3">نرخ مؤثر ماهانه</th>
                        <th class="text-start px-3 py-3">ماه‌های باقی‌مانده</th>
                        <th class="text-start px-3 py-3">کل پرداختی سال</th>
                        <th class="text-start px-3 py-3">پیش‌پرداخت</th>
                        <th class="text-start px-3 py-3">قسط هر ماه</th>
                        <th class="text-start px-3 py-3"></th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 dark:text-slate-200">
                    @foreach ($rows as $row)
                        <tr class="border-t border-slate-100 dark:border-slate-800">
                            <td class="px-3 py-2 font-semibold">{{ $row['label'] }}</td>
                            <td class="px-3 py-2">
                                <input type="number" min="0" max="100"
                                       wire:model.live="monthDiscounts.{{ $row['index'] }}"
                                       class="w-20 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-2 py-1 text-sm" dir="ltr">
                                @error('month_' . $row['index'])<div class="text-rose-600 text-xs mt-1">{{ $message }}</div>@enderror
                            </td>
                            <td class="px-3 py-2">{{ number_format($row['effective_rate']) }}</td>
                            <td class="px-3 py-2">{{ $row['remaining_months'] }}</td>
                            <td class="px-3 py-2 font-semibold text-indigo-700 dark:text-indigo-300">{{ number_format($row['total']) }}</td>
                            <td class="px-3 py-2">{{ number_format($row['initial']) }}</td>
                            <td class="px-3 py-2">
                                @if($row['installment_count'] > 0)
                                    {{ number_format($row['installment']) }}
                                    <span class="text-xs text-slate-400">× {{ $row['installment_count'] }}</span>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-3 py-2">
                                <button wire:click="saveMonthDiscount({{ $row['index'] }})"
                                        class="px-3 py-1 rounded-md bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-semibold">
                                    ذخیره
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
