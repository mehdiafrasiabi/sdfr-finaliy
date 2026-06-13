<div class="px-4 py-6">
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('manager.dashboard.analytics') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">قیمت پایه‌ها</li>
            </ol>
        </nav>
    </div>

    @if (session('success'))
        <div class="rounded-lg mb-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-200 border border-emerald-200 dark:border-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm">
        <div class="flex items-center justify-between p-5 border-b border-slate-100 dark:border-slate-800">
            <div>
                <h4 class="m-0 text-slate-800 dark:text-slate-100 font-bold">قیمت‌گذاری «ماه ورود و تخفیف»</h4>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    برای هر پایه «نرخ ماهانه»، «درصد پیش‌پرداخت» و «سال خدمت» را تعیین کنید.
                    سال خدمت از تیر تا پایان خرداد سال بعد است. پس از ذخیره، تخفیف هر ماه را در صفحهٔ جزئیات تنظیم کنید.
                </p>
            </div>
            <button wire:click="openCreate"
                    class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm whitespace-nowrap">
                افزودن قیمت پایه
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300">
                    <tr>
                        <th class="text-start px-4 py-3">پایه</th>
                        <th class="text-start px-4 py-3">نرخ ماهانه (تومان)</th>
                        <th class="text-start px-4 py-3">پیش‌پرداخت</th>
                        <th class="text-start px-4 py-3">سال خدمت</th>
                        <th class="text-start px-4 py-3">پایان دسترسی</th>
                        <th class="text-start px-4 py-3">وضعیت</th>
                        <th class="text-start px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 dark:text-slate-200">
                    @forelse ($prices as $price)
                        <tr class="border-t border-slate-100 dark:border-slate-800">
                            <td class="px-4 py-3 font-semibold">{{ $price->grade_label }}</td>
                            <td class="px-4 py-3">{{ number_format($price->monthly_rate) }}</td>
                            <td class="px-4 py-3">{{ (int) ($price->initial_percentage ?? 30) }}٪</td>
                            <td class="px-4 py-3">{{ $price->serviceYear() ? 'تیر ' . $price->serviceYear() : '—' }}</td>
                            <td class="px-4 py-3">{{ $price->end_at ? \Morilog\Jalali\Jalalian::fromCarbon($price->end_at)->format('Y/m/d') : '—' }}</td>
                            <td class="px-4 py-3">
                                @if($price->is_active)
                                    <span class="px-2 py-0.5 rounded text-xs bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">فعال</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-xs bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">غیرفعال</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 flex gap-2 flex-wrap">
                                <a href="{{ route('manager.grade-price.show', ['price' => $price->id]) }}"
                                   wire:navigate
                                   class="px-3 py-1 rounded-md bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-semibold">
                                    تخفیف ماه‌ها
                                </a>
                                <button wire:click="openEdit({{ $price->id }})"
                                        class="px-3 py-1 rounded-md bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs">
                                    ویرایش
                                </button>
                                <button wire:click="toggleActive({{ $price->id }})"
                                        class="px-3 py-1 rounded-md bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-xs">
                                    {{ $price->is_active ? 'غیرفعال‌کردن' : 'فعال‌کردن' }}
                                </button>
                                <button wire:click="delete({{ $price->id }})"
                                        wire:confirm="آیا از حذف مطمئن هستید؟"
                                        class="px-3 py-1 rounded-md bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300 text-xs">
                                    حذف
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-slate-500 dark:text-slate-400 py-8">
                                هنوز قیمتی برای هیچ پایه‌ای تعریف نشده است.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 dark:bg-black/80 backdrop-blur-sm">
            <div class="w-full max-w-md rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-xl">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                    <h5 class="m-0 font-bold text-slate-800 dark:text-slate-100">
                        {{ $editingId ? 'ویرایش قیمت پایه' : 'افزودن قیمت پایه' }}
                    </h5>
                    <button wire:click="closeForm" class="text-slate-500 hover:text-slate-700 dark:text-slate-300">✕</button>
                </div>

                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">پایه</label>
                        <select wire:model="grade"
                                class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm">
                            <option value="9">نهم</option>
                            <option value="10">دهم</option>
                            <option value="11">یازدهم</option>
                            <option value="12">دوازدهم</option>
                        </select>
                        @error('grade')<div class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">نرخ ماهانه (تومان)</label>
                        <input type="number" min="1" wire:model.live="monthlyRate"
                               class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm" dir="ltr">
                        @if($monthlyRate > 0)
                            <p class="text-xs text-slate-400 mt-1">{{ number_format($monthlyRate) }} تومان</p>
                        @endif
                        @error('monthlyRate')<div class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">پیش‌پرداخت (٪)</label>
                            <input type="number" min="0" max="100" wire:model="initialPercentage"
                                   class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm" dir="ltr">
                            @error('initialPercentage')<div class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">سال خدمت (تیر)</label>
                            <input type="number" min="1390" max="1450" wire:model="serviceYear" placeholder="1405"
                                   class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm" dir="ltr">
                            @error('serviceYear')<div class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <p class="text-xs text-slate-400">سال خدمت از تیر {{ $serviceYear ?: '—' }} تا پایان خرداد {{ $serviceYear ? $serviceYear + 1 : '—' }} خواهد بود.</p>

                    <label class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
                        <input type="checkbox" wire:model="isActive" class="form-check-input">
                        <span>فعال باشد</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-slate-100 dark:border-slate-800">
                    <button wire:click="closeForm"
                            class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm">
                        انصراف
                    </button>
                    <button wire:click="save" wire:loading.attr="disabled"
                            class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold disabled:opacity-50">
                        ذخیره
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
