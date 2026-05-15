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

    {{-- ─── کارت اطلاعات پلن ─── --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-5 mb-5">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-sm">
            <div>
                <div class="text-slate-500 dark:text-slate-400">پایه</div>
                <div class="text-slate-900 dark:text-slate-100 font-bold text-lg mt-1">{{ $price->grade_label }}</div>
            </div>
            <div>
                <div class="text-slate-500 dark:text-slate-400">مبلغ کل</div>
                <div class="text-slate-900 dark:text-slate-100 font-bold text-lg mt-1">{{ number_format($price->total_amount) }} ت</div>
            </div>
            <div>
                <div class="text-slate-500 dark:text-slate-400">شروع</div>
                <div class="text-slate-900 dark:text-slate-100 font-bold text-lg mt-1">{{ \Morilog\Jalali\Jalalian::fromCarbon($price->start_at)->format('Y/m/d') }}</div>
            </div>
            <div>
                <div class="text-slate-500 dark:text-slate-400">پایان</div>
                <div class="text-slate-900 dark:text-slate-100 font-bold text-lg mt-1">{{ \Morilog\Jalali\Jalalian::fromCarbon($price->end_at)->format('Y/m/d') }}</div>
            </div>
            <div>
                <div class="text-slate-500 dark:text-slate-400">تعداد ماه</div>
                <div class="text-slate-900 dark:text-slate-100 font-bold text-lg mt-1">{{ $price->months_count }}</div>
            </div>
        </div>

        <div class="mt-4 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800 p-3 text-xs text-indigo-700 dark:text-indigo-200">
            قیمت پلکانی این پلن: هر ماه که می‌گذرد <strong>{{ number_format($price->monthly_reduction) }}</strong> تومان از قیمت کاسته می‌شود.
        </div>
    </div>

    {{-- ─── جدول ماه‌ها ─── --}}
    @if ($showDailyForm)
        <link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker@0.9.12/dist/jalalidatepicker.min.css">
        <script src="https://unpkg.com/@majidh1/jalalidatepicker@0.9.12/dist/jalalidatepicker.min.js"></script>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach ($rows as $row)
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-4">
                <div class="flex items-center justify-between mb-3">
                    <h5 class="m-0 font-bold text-slate-800 dark:text-slate-100">
                        ماه {{ $row['index'] + 1 }} — {{ $row['jalali_label'] }}
                    </h5>
                    <span class="text-xs text-slate-500 dark:text-slate-400">
                        {{ \Morilog\Jalali\Jalalian::fromCarbon($row['start'])->format('Y/m/d') }} تا
                        {{ \Morilog\Jalali\Jalalian::fromCarbon($row['end'])->format('Y/m/d') }}
                    </span>
                </div>

                <div class="rounded-lg bg-slate-50 dark:bg-slate-800/60 p-3 mb-3">
                    <div class="text-xs text-slate-500 dark:text-slate-400">قیمت ثابت پلکانی این ماه</div>
                    <div class="text-2xl font-bold text-slate-800 dark:text-slate-100 mt-1">
                        {{ number_format($row['stepped']) }}
                        <span class="text-sm font-normal text-slate-500 dark:text-slate-400">تومان</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="block text-xs font-semibold mb-1 text-slate-700 dark:text-slate-200">درصد تخفیف این ماه</label>
                    <div class="flex gap-2">
                        <input type="number" min="0" max="100"
                               wire:model="monthDiscounts.{{ $row['index'] }}"
                               class="flex-1 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-1.5 text-sm">
                        <button wire:click="saveMonthDiscount({{ $row['index'] }})"
                                class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold">
                            ذخیره
                        </button>
                    </div>
                    @error('month_'.$row['index'])<div class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800 pt-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-200">تخفیف‌های روز خاص</span>
                        <button wire:click="openDailyForm({{ $row['index'] }})"
                                class="text-xs px-2 py-1 rounded-md bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300">
                            + افزودن
                        </button>
                    </div>

                    @forelse ($row['dailies'] as $d)
                        <div class="flex items-center justify-between gap-2 rounded-md bg-slate-50 dark:bg-slate-800/60 px-2 py-1.5 mb-1 text-xs">
                            <div class="flex-1 truncate">
                                <span class="font-semibold text-slate-700 dark:text-slate-200">
                                    {{ \Morilog\Jalali\Jalalian::fromCarbon($d->starts_on)->format('Y/m/d') }}
                                </span>
                                — {{ $d->discount_percentage }}٪
                                @if ($d->label) <span class="text-slate-500">({{ $d->label }})</span> @endif
                                @if (!$d->is_active) <span class="text-slate-500">[غیرفعال]</span> @endif
                            </div>
                            <div class="flex gap-1">
                                <button wire:click="openEditDaily({{ $d->id }}, {{ $row['index'] }})"
                                        class="text-indigo-600 dark:text-indigo-400 text-xs">ویرایش</button>
                                <button wire:click="toggleDaily({{ $d->id }})"
                                        class="text-amber-600 dark:text-amber-400 text-xs">{{ $d->is_active ? 'خاموش' : 'روشن' }}</button>
                                <button wire:click="deleteDaily({{ $d->id }})"
                                        wire:confirm="آیا از حذف مطمئن هستید؟"
                                        class="text-rose-600 dark:text-rose-400 text-xs">حذف</button>
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-500 dark:text-slate-400 text-center py-1">هیچ روز خاصی تعریف نشده.</div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    {{-- ─── Modal تخفیف روز خاص ─── --}}
    @if ($showDailyForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 dark:bg-black/80 backdrop-blur-sm"
             x-data
             x-init="$nextTick(() => { if (window.jalaliDatepicker) { jalaliDatepicker.startWatch({ time: false, autoShow: true, showTodayBtn: true, showEmptyBtn: true, separatorChars: { date: '/' } }); } })">
            <div class="w-full max-w-md rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-xl">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                    <h5 class="m-0 font-bold text-slate-800 dark:text-slate-100">
                        {{ $editingDailyId ? 'ویرایش تخفیف روز خاص' : 'افزودن تخفیف روز خاص' }}
                    </h5>
                    <button wire:click="closeDailyForm" class="text-slate-500 hover:text-slate-700 dark:text-slate-300">✕</button>
                </div>

                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">عنوان (اختیاری)</label>
                        <input type="text" wire:model="dailyLabel" placeholder="مثلاً عید غدیر"
                               class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">درصد تخفیف</label>
                        <input type="number" min="1" max="100" wire:model="dailyPercentage"
                               class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm">
                        @error('dailyPercentage')<div class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">تاریخ روز خاص (شمسی)</label>
                        <input type="text" wire:model="dailyDateJ" data-jdp data-jdp-only-date placeholder="1405/08/13"
                               class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm" dir="ltr">
                        @error('dailyDateJ')<div class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                    <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
                        <input type="checkbox" wire:model="dailyActive">
                        <span>فعال</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-slate-100 dark:border-slate-800">
                    <button wire:click="closeDailyForm"
                            class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm">
                        انصراف
                    </button>
                    <button wire:click="saveDaily" wire:loading.attr="disabled"
                            class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold disabled:opacity-50">
                        ذخیره
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
