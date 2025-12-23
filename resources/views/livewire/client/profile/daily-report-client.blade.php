<div class="w-full px-4 sm:px-6 lg:px-8 py-6">
    {{-- Header --}}
    <div class="mb-6">
        <h4 class="text-lg sm:text-xl font-semibold">
            <span class="text-slate-500 dark:text-slate-400">گزارش روزانه /</span>
            <span class="text-blue-600 dark:text-blue-400">برنامه هفتگی من</span>
        </h4>
    </div>

    @if($lastSession && $weeklyProgram)

        {{-- دکمه پارت جبرانی --}}
        @if(!empty($missedParts))
            <div class="mb-4">
                <div class="rounded-2xl border border-amber-200 dark:border-amber-900/40
                            bg-amber-50 dark:bg-amber-500/10 p-4
                            flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-2 text-amber-800 dark:text-amber-200 text-sm font-medium">
                        <i class="material-symbols-outlined">warning</i>
                        شما {{ count($missedParts) }} پارت ازدست رفته دارید که باید جبران کنید.
                    </div>

                    <button wire:click="openCompensatoryModal" type="button"
                            class="inline-flex items-center justify-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold
                                   bg-amber-500 hover:bg-amber-600 text-slate-950
                                   dark:bg-amber-400 dark:hover:bg-amber-500">
                        <i class="material-symbols-outlined text-[20px]">add_task</i>
                        ثبت پارت جبرانی
                    </button>
                </div>
            </div>
        @endif

        {{-- Row: روزهای هفته --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 mb-6">
            @foreach($weekDays as $day)
                @php
                    $variant = 'default';
                    if($day['is_today']) $variant = 'today';
                    if($day['has_report']) $variant = 'done';
                    if($day['is_locked']) $variant = 'locked';

                    $border = match($variant) {
                        'today' => 'border-blue-500 dark:border-blue-400',
                        'done' => 'border-emerald-400 dark:border-emerald-500',
                        'locked' => 'border-rose-400 dark:border-rose-500',
                        default => 'border-slate-200 dark:border-slate-800',
                    };

                    $headerBg = match($variant) {
                        'today' => 'bg-blue-600 text-white',
                        'done' => 'bg-emerald-600 text-white',
                        'locked' => 'bg-rose-600 text-white',
                        default => 'bg-slate-50 dark:bg-slate-800/60 text-slate-800 dark:text-slate-100',
                    };

                    $dateBadge = match($variant) {
                        'today','done','locked' => 'bg-white/90 text-slate-900',
                        default => 'bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800',
                    };
                @endphp

                <div class="rounded-2xl border {{ $border }} bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                    <div class="px-4 py-3 {{ $headerBg }}">
                        <div class="flex items-center justify-between gap-2">
                            <h6 class="text-sm font-semibold mb-0">{{ $day['name'] }}</h6>
                            <span
                                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $dateBadge }}">
                                {{ $day['jalali_day_month'] }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4">
                        <div class="mb-3">
                            <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">تعداد پارت‌ها:</div>
                            <div class="font-bold text-slate-900 dark:text-slate-100">{{ $day['parts_count'] }}پارت
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="text-xs text-slate-500 dark:text-slate-400 mb-1">تعداد تست‌ها:</div>
                            <div class="font-bold text-slate-900 dark:text-slate-100">{{ $day['total_tests'] }}تست
                            </div>
                        </div>

                        @if($day['can_submit'])
                            <button wire:click="openReportModal('{{ $day['date']->format('Y-m-d') }}')"
                                    type="button"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold
                                           bg-blue-600 hover:bg-blue-700 text-white
                                           dark:bg-blue-500 dark:hover:bg-blue-600">
                                <i class="material-symbols-outlined text-[20px]">send</i>
                                ثبت گزارش امروز
                            </button>
                        @elseif($day['has_report'])
                            <button type="button" disabled
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold
                                           bg-emerald-600 text-white opacity-70 cursor-not-allowed
                                           dark:bg-emerald-500">
                                <i class="material-symbols-outlined text-[20px]">check_circle</i>
                                گزارش ثبت شده
                            </button>
                        @elseif($day['is_locked'])
                            <button type="button" disabled
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold
                                           bg-rose-600 text-white opacity-70 cursor-not-allowed
                                           dark:bg-rose-500">
                                <i class="material-symbols-outlined text-[20px]">lock</i>
                                مهلت به پایان رسیده
                            </button>
                        @elseif($day['is_future'])
                            <button type="button" disabled
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold
                                           bg-slate-600 text-white opacity-70 cursor-not-allowed
                                           dark:bg-slate-700">
                                <i class="material-symbols-outlined text-[20px]">schedule</i>
                                آینده
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    @else
        {{-- پیام عدم وجود برنامه --}}
        <div class="rounded-2xl border border-blue-200 dark:border-blue-900/40 bg-blue-50 dark:bg-blue-500/10 p-4 mb-6">
            <div class="flex items-center gap-2 text-blue-800 dark:text-blue-200 text-sm font-medium">
                <i class="material-symbols-outlined">info</i>
                هنوز جلسه مشاوره برگزار شده‌ای ندارید یا برنامه هفتگی برای شما ثبت نشده است.
            </div>
        </div>
    @endif

    {{-- Row: گزارش‌های ارسال شده --}}
    <div
        class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
        <div class="px-4 sm:px-5 py-4 border-b border-slate-200 dark:border-slate-800">
            <h5 class="text-sm sm:text-base font-semibold text-slate-900 dark:text-slate-100 mb-0">
                گزارش‌های ارسال شده
            </h5>
        </div>

        {{-- ✅ Mobile Card View --}}
        <div class="md:hidden p-4 space-y-3">
            @forelse($reports as $report)
                @php
                    $statusText = $report->status === 'pending' ? 'در انتظار' : ($report->status === 'approved' ? 'تایید شده' : 'رد شده');
                    $statusPill = $report->status === 'pending'
                        ? 'bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-200'
                        : ($report->status === 'approved'
                            ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-200'
                            : 'bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-200');

                    $completed = $report->completedParts()->count();
                    $total = $report->parts()->where('is_compensatory', false)->count();

                    $testsDone = $report->parts->sum('tests_done') ?? 0;
                    $testsRequired = $report->parts->sum(fn($p) => $p->programPart->test_count ?? 0);

                    $phonePill = $report->phone_hours > 3
                        ? 'bg-rose-600 text-white dark:bg-rose-500'
                        : 'bg-emerald-600 text-white dark:bg-emerald-500';
                @endphp

                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="text-xs text-slate-500 dark:text-slate-400">تاریخ</div>
                            <div class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                                {{ jdate($report->report_date)->format('Y/m/d') }}
                            </div>
                        </div>

                        <span
                            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusPill }}">
                            {{ $statusText }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div
                            class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 p-3">
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mb-1">پارت‌های تکمیل‌شده</div>
                            <div class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                                {{ $completed }} / {{ $total }}
                            </div>
                        </div>

                        <div
                            class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 p-3">
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mb-1">تست‌ها</div>
                            <div class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                                {{ $testsDone }} / {{ $testsRequired }}
                            </div>
                        </div>

                        <div
                            class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 p-3">
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mb-1">ساعت گوشی</div>
                            <span
                                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $phonePill }}">
                                {{ $report->phone_hours }} ساعت
                            </span>
                        </div>

                        <div
                            class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 p-3">
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mb-1">امتیاز</div>
                            <div class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                                {{ $report->rating }} / 5
                                <div class="text-xs font-normal text-slate-500 dark:text-slate-400 mt-1">
                                    {{ $report->rating_label }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-xs text-slate-500 dark:text-slate-400">نظر مشاور</div>

                        @if($report->comment && $report->comment->advisor_comment)
                            <button wire:click="openReplyModal({{ $report->id }})"
                                    type="button"
                                    class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-xs font-semibold
                                           border border-slate-200 hover:bg-slate-50 text-blue-600
                                           dark:border-slate-800 dark:hover:bg-slate-800/40 dark:text-blue-400">
                                <i class="material-symbols-outlined text-[18px]">chat</i>
                                مشاهده نظر
                            </button>
                        @else
                            <span class="text-xs text-slate-500 dark:text-slate-400">-</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-500 dark:text-slate-400">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                               style="width:75px;height:75px"></lord-icon>
                    <h5 class="mt-2 mb-0 text-sm font-semibold">هنوز گزارشی ثبت نکرده‌اید</h5>
                </div>
            @endforelse
        </div>

        {{-- ✅ Desktop Table View --}}
        <div class="hidden md:block">
            <div class="overflow-x-auto">
                <table class="min-w-[900px] w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-800/60">
                    <tr class="text-right text-slate-600 dark:text-slate-300">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">تاریخ</th>
                        <th class="px-4 py-3">پارت‌های تکمیل شده</th>
                        <th class="px-4 py-3">تست‌ها</th>
                        <th class="px-4 py-3">ساعت گوشی</th>
                        <th class="px-4 py-3">امتیاز</th>
                        <th class="px-4 py-3">وضعیت</th>
                        <th class="px-4 py-3">نظر مشاور</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($reports as $report)
                        @php
                            $completed = $report->completedParts()->count();
                            $total = $report->parts()->where('is_compensatory', false)->count();

                            $testsDone = $report->parts->sum('tests_done') ?? 0;
                            $testsRequired = $report->parts->sum(fn($p) => $p->programPart->test_count ?? 0);

                            $statusText = $report->status === 'pending' ? 'در انتظار' : ($report->status === 'approved' ? 'تایید شده' : 'رد شده');
                            $statusPill = $report->status === 'pending'
                                ? 'bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-200'
                                : ($report->status === 'approved'
                                    ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-200'
                                    : 'bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-200');

                            $phonePill = $report->phone_hours > 3
                                ? 'bg-rose-600 text-white dark:bg-rose-500'
                                : 'bg-emerald-600 text-white dark:bg-emerald-500';
                        @endphp

                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 text-slate-800 dark:text-slate-100">
                            <td class="px-4 py-3 text-nowrap">
                                {{ $loop->iteration + $reports->firstItem() - 1 }}
                            </td>

                            <td class="px-4 py-3 text-nowrap text-slate-600 dark:text-slate-300">
                                {{ jdate($report->report_date)->format('Y/m/d') }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                             bg-blue-600 text-white dark:bg-blue-500">
                                    {{ $completed }} / {{ $total }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                             bg-sky-100 text-sky-800 dark:bg-sky-500/20 dark:text-sky-200">
                                    {{ $testsDone }} / {{ $testsRequired }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-nowrap">
                                <span
                                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $phonePill }}">
                                    {{ $report->phone_hours }} ساعت
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                                 bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-100 w-fit">
                                        {{ $report->rating }} / 5
                                    </span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        {{ $report->rating_label }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $statusPill }}">
                                    {{ $statusText }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                @if($report->comment && $report->comment->advisor_comment)
                                    <button wire:click="openReplyModal({{ $report->id }})"
                                            type="button"
                                            class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-xs font-semibold
                                                   border border-slate-200 hover:bg-slate-50 text-blue-600
                                                   dark:border-slate-800 dark:hover:bg-slate-800/40 dark:text-blue-400">
                                        <i class="material-symbols-outlined text-[18px]">chat</i>
                                        مشاهده نظر
                                    </button>
                                @else
                                    <span class="text-slate-500 dark:text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                           style="width:75px;height:75px"></lord-icon>
                                <h5 class="mt-2 mb-0 text-sm font-semibold">هنوز گزارشی ثبت نکرده‌اید</h5>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 sm:px-5 py-4 border-t border-slate-200 dark:border-slate-800">
                {{ $reports->links('layouts.client.pagination') }}
            </div>
        </div>
    </div>

    {{-- ===================== MODALS ===================== --}}

    {{-- Modal: ثبت گزارش روزانه --}}
    @if($reportModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:click.self="closeReportModal">
            <div class="absolute inset-0 bg-black/60"></div>

            <div class="relative w-full max-w-6xl rounded-2xl border border-slate-200 dark:border-slate-800
                        bg-white dark:bg-slate-900 shadow-xl
                        max-h-[85vh] overflow-hidden"
                 wire:keydown.escape="closeReportModal">

                <div
                    class="flex items-center justify-between gap-3 px-4 sm:px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h5 class="text-sm sm:text-base font-semibold text-slate-900 dark:text-slate-100">
                        ثبت گزارش روزانه
                    </h5>
                    <button type="button" wire:click="closeReportModal"
                            class="rounded-xl p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700
                                   dark:hover:bg-slate-800 dark:text-slate-400 dark:hover:text-slate-200">
                        ✕
                    </button>
                </div>

                <div class="px-4 sm:px-5 py-4 overflow-y-auto">
                    {{-- پارت‌ها --}}
                    <div class="mb-6">
                        <h6 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-3">
                            پارت‌های امروز (انتخاب کنید چه پارت‌هایی خواندید):
                        </h6>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                            @foreach($selectedDayParts as $part)
                                @php $isSelected = in_array($part['id'], $selectedParts); @endphp

                                <div
                                    wire:click="togglePart({{ $part['id'] }})"
                                    class="rounded-2xl border p-4 cursor-pointer select-none
                                           transition hover:-translate-y-0.5 hover:shadow-md
                                           {{ $isSelected
                                                ? 'border-emerald-300 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-500/10'
                                                : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900'
                                           }}"
                                >
                                    <div class="flex items-start justify-between gap-3 mb-2">
                                        <div class="font-semibold text-slate-900 dark:text-slate-100">
                                            {{ $part['lesson_name'] }}
                                        </div>

                                        @if($isSelected)
                                            <i class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">check_circle</i>
                                        @else
                                            <i class="material-symbols-outlined text-slate-400 dark:text-slate-500">radio_button_unchecked</i>
                                        @endif
                                    </div>

                                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-2">
                                        {{ $part['description'] ?? 'بدون توضیحات' }}
                                    </div>

                                    @if($part['test_count'] > 0)
                                        <div class="text-xs mb-2">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 font-semibold
                                                         bg-sky-100 text-sky-800 dark:bg-sky-500/20 dark:text-sky-200">
                                                {{ $part['test_count'] }} تست
                                            </span>
                                        </div>

                                        @if($isSelected)
                                            <div class="mt-2" wire:click.stop>
                                                <label
                                                    class="block text-xs font-medium text-slate-700 dark:text-slate-200 mb-2">
                                                    تعداد تست زده شده:
                                                </label>

                                                <input wire:model="partTests.{{ $part['id'] }}"
                                                       type="number" min="0" max="{{ $part['test_count'] }}"
                                                       class="w-full rounded-xl border border-slate-200 dark:border-slate-800
                                                              bg-white dark:bg-slate-950
                                                              text-slate-900 dark:text-slate-100
                                                              px-3 py-2 text-sm
                                                              focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500
                                                              dark:focus:ring-blue-400/20 dark:focus:border-blue-400">
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ساعت گوشی --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                            ساعت استفاده از گوشی (غیر درسی):
                        </label>
                        <input wire:model="phoneNonStudyHours" type="number" min="0" max="24"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-800
                                      bg-white dark:bg-slate-950
                                      text-slate-900 dark:text-slate-100
                                      px-3 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500
                                      dark:focus:ring-blue-400/20 dark:focus:border-blue-400">
                        @error('phoneNonStudyHours')
                        <div class="mt-2 text-xs text-rose-600 dark:text-rose-300">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- توضیحات --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                            توضیحات (اختیاری):
                        </label>
                        <textarea wire:model="description" rows="3"
                                  class="w-full rounded-2xl border border-slate-200 dark:border-slate-800
                                         bg-white dark:bg-slate-950
                                         text-slate-900 dark:text-slate-100
                                         px-3 py-2.5 text-sm
                                         focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500
                                         dark:focus:ring-blue-400/20 dark:focus:border-blue-400"></textarea>
                        @error('description')
                        <div class="mt-2 text-xs text-rose-600 dark:text-rose-300">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- امتیاز --}}
                    <div class="mb-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                            امتیاز خود را انتخاب کنید:
                        </label>

                        <div class="grid grid-cols-1 sm:grid-cols-5 gap-2">
                            <input wire:model="rating" type="radio" class="sr-only peer/r1" name="rating" id="rating1"
                                   value="1">
                            <label for="rating1"
                                   class="cursor-pointer rounded-xl px-3 py-2 text-sm font-semibold text-center
                                          border border-rose-200 text-rose-700 hover:bg-rose-50
                                          dark:border-rose-900/40 dark:text-rose-200 dark:hover:bg-rose-500/10
                                          peer-checked/r1:bg-rose-600 peer-checked/r1:text-white peer-checked/r1:border-rose-600">
                                نیاز به تلاش
                            </label>

                            <input wire:model="rating" type="radio" class="sr-only peer/r2" name="rating" id="rating2"
                                   value="2">
                            <label for="rating2"
                                   class="cursor-pointer rounded-xl px-3 py-2 text-sm font-semibold text-center
                                          border border-amber-200 text-amber-800 hover:bg-amber-50
                                          dark:border-amber-900/40 dark:text-amber-200 dark:hover:bg-amber-500/10
                                          peer-checked/r2:bg-amber-500 peer-checked/r2:text-slate-950 peer-checked/r2:border-amber-500">
                                قابل قبول
                            </label>

                            <input wire:model="rating" type="radio" class="sr-only peer/r3" name="rating" id="rating3"
                                   value="3">
                            <label for="rating3"
                                   class="cursor-pointer rounded-xl px-3 py-2 text-sm font-semibold text-center
                                          border border-sky-200 text-sky-800 hover:bg-sky-50
                                          dark:border-sky-900/40 dark:text-sky-200 dark:hover:bg-sky-500/10
                                          peer-checked/r3:bg-sky-600 peer-checked/r3:text-white peer-checked/r3:border-sky-600">
                                خوب
                            </label>

                            <input wire:model="rating" type="radio" class="sr-only peer/r4" name="rating" id="rating4"
                                   value="4">
                            <label for="rating4"
                                   class="cursor-pointer rounded-xl px-3 py-2 text-sm font-semibold text-center
                                          border border-blue-200 text-blue-700 hover:bg-blue-50
                                          dark:border-blue-900/40 dark:text-blue-200 dark:hover:bg-blue-500/10
                                          peer-checked/r4:bg-blue-600 peer-checked/r4:text-white peer-checked/r4:border-blue-600">
                                خیلی خوب
                            </label>

                            <input wire:model="rating" type="radio" class="sr-only peer/r5" name="rating" id="rating5"
                                   value="5">
                            <label for="rating5"
                                   class="cursor-pointer rounded-xl px-3 py-2 text-sm font-semibold text-center
                                          border border-emerald-200 text-emerald-700 hover:bg-emerald-50
                                          dark:border-emerald-900/40 dark:text-emerald-200 dark:hover:bg-emerald-500/10
                                          peer-checked/r5:bg-emerald-600 peer-checked/r5:text-white peer-checked/r5:border-emerald-600">
                                عالی
                            </label>
                        </div>

                        @error('rating')
                        <div class="mt-2 text-xs text-rose-600 dark:text-rose-300">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div
                    class="px-4 sm:px-5 py-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between gap-3">
                    <button type="button" wire:click="closeReportModal"
                            class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold
                                   border border-slate-200 hover:bg-slate-50 text-slate-700
                                   dark:border-slate-800 dark:hover:bg-slate-800/40 dark:text-slate-200">
                        انصراف
                    </button>

                    <button type="button" wire:click="submitReport" wire:loading.attr="disabled"
                            class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold
                                   bg-blue-600 hover:bg-blue-700 text-white
                                   disabled:opacity-60 disabled:cursor-not-allowed
                                   dark:bg-blue-500 dark:hover:bg-blue-600">
                        <span wire:loading.remove wire:target="submitReport" class="inline-flex items-center gap-2">
                            <i class="material-symbols-outlined text-[20px]">send</i>
                            ثبت گزارش
                        </span>
                        <span wire:loading wire:target="submitReport" class="inline-flex items-center gap-2">
                            <span
                                class="h-4 w-4 animate-spin rounded-full border-2 border-white/60 border-t-white"></span>
                            در حال ثبت...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal: پارت جبرانی --}}
    @if($compensatoryModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:click.self="closeCompensatoryModal">
            <div class="absolute inset-0 bg-black/60"></div>

            <div class="relative w-full max-w-6xl rounded-2xl border border-slate-200 dark:border-slate-800
                        bg-white dark:bg-slate-900 shadow-xl
                        max-h-[85vh] overflow-hidden"
                 wire:keydown.escape="closeCompensatoryModal">

                <div class="flex items-center justify-between gap-3 px-4 sm:px-5 py-4 border-b border-slate-200 dark:border-slate-800
                            bg-amber-50 dark:bg-amber-500/10">
                    <h5 class="text-sm sm:text-base font-semibold text-slate-900 dark:text-slate-100">
                        ثبت پارت‌های جبرانی
                    </h5>
                    <button type="button" wire:click="closeCompensatoryModal"
                            class="rounded-xl p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700
                                   dark:hover:bg-slate-800 dark:text-slate-400 dark:hover:text-slate-200">
                        ✕
                    </button>
                </div>

                <div class="px-4 sm:px-5 py-4 overflow-y-auto">
                    <div
                        class="rounded-2xl border border-amber-200 dark:border-amber-900/40 bg-amber-50 dark:bg-amber-500/10 p-4 mb-4">
                        <div class="flex items-center gap-2 text-amber-800 dark:text-amber-200 text-sm font-medium">
                            <i class="material-symbols-outlined">info</i>
                            پارت‌های زیر را در روز خودشان انجام نداده‌اید. اگر الان خوانده‌اید، انتخاب کنید.
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach($missedParts as $part)
                            @php $isSelected = in_array($part->id, $selectedCompensatoryParts); @endphp

                            <div wire:click="toggleCompensatoryPart({{ $part->id }})"
                                 class="rounded-2xl border p-4 cursor-pointer select-none
                                        transition hover:-translate-y-0.5 hover:shadow-md
                                        {{ $isSelected
                                            ? 'border-amber-300 dark:border-amber-800 bg-amber-50 dark:bg-amber-500/10'
                                            : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900'
                                        }}">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div class="font-semibold text-slate-900 dark:text-slate-100">
                                        {{ $part->lesson_name }}
                                    </div>

                                    @if($isSelected)
                                        <i class="material-symbols-outlined text-amber-600 dark:text-amber-300">check_circle</i>
                                    @else
                                        <i class="material-symbols-outlined text-slate-400 dark:text-slate-500">radio_button_unchecked</i>
                                    @endif
                                </div>

                                <div class="text-xs text-slate-500 dark:text-slate-400 mb-2">
                                    تاریخ: {{ jdate($part->part_date)->format('Y/m/d') }}
                                </div>

                                @if($part->test_count > 0)
                                    <div class="text-xs mb-2">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 font-semibold
                                                     bg-sky-100 text-sky-800 dark:bg-sky-500/20 dark:text-sky-200">
                                            {{ $part->test_count }} تست
                                        </span>
                                    </div>

                                    @if($isSelected)
                                        <div class="mt-2" wire:click.stop>
                                            <label
                                                class="block text-xs font-medium text-slate-700 dark:text-slate-200 mb-2">
                                                تعداد تست زده:
                                            </label>
                                            <input wire:model="compensatoryPartTests.{{ $part->id }}"
                                                   type="number" min="0" max="{{ $part->test_count }}"
                                                   class="w-full rounded-xl border border-slate-200 dark:border-slate-800
                                                          bg-white dark:bg-slate-950
                                                          text-slate-900 dark:text-slate-100
                                                          px-3 py-2 text-sm
                                                          focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500
                                                          dark:focus:ring-amber-400/20 dark:focus:border-amber-400">
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div
                    class="px-4 sm:px-5 py-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between gap-3">
                    <button type="button" wire:click="closeCompensatoryModal"
                            class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold
                                   border border-slate-200 hover:bg-slate-50 text-slate-700
                                   dark:border-slate-800 dark:hover:bg-slate-800/40 dark:text-slate-200">
                        انصراف
                    </button>

                    <button type="button" wire:click="submitCompensatory" wire:loading.attr="disabled"
                            class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold
                                   bg-amber-500 hover:bg-amber-600 text-slate-950
                                   disabled:opacity-60 disabled:cursor-not-allowed
                                   dark:bg-amber-400 dark:hover:bg-amber-500">
                        <span wire:loading.remove wire:target="submitCompensatory">ثبت پارت‌های جبرانی</span>
                        <span wire:loading wire:target="submitCompensatory" class="inline-flex items-center gap-2">
                            <span
                                class="h-4 w-4 animate-spin rounded-full border-2 border-slate-900/40 border-t-slate-900"></span>
                            در حال ثبت...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal: پاسخ به نظر مشاور --}}
    @if($replyModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:click.self="closeReplyModal">
            <div class="absolute inset-0 bg-black/60"></div>

            <div class="relative w-full max-w-3xl rounded-2xl border border-slate-200 dark:border-slate-800
                        bg-white dark:bg-slate-900 shadow-xl overflow-hidden"
                 wire:keydown.escape="closeReplyModal">

                <div
                    class="flex items-center justify-between gap-3 px-4 sm:px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                    <h5 class="text-sm sm:text-base font-semibold text-slate-900 dark:text-slate-100">
                        نظر مشاور
                    </h5>
                    <button type="button" wire:click="closeReplyModal"
                            class="rounded-xl p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700
                                   dark:hover:bg-slate-800 dark:text-slate-400 dark:hover:text-slate-200">
                        ✕
                    </button>
                </div>

                <div class="px-4 sm:px-5 py-4">
                    <div class="mb-4">
                        <div class="font-semibold text-slate-900 dark:text-slate-100 mb-2 text-sm">نظر مشاور:</div>
                        <p class="p-4 rounded-2xl border border-blue-200 dark:border-blue-900/40 bg-blue-50 dark:bg-blue-500/10 text-sm text-slate-800 dark:text-slate-100">
                            {{ $advisorCommentPreview }}
                        </p>
                    </div>

                    @if($studentReplyPreview)
                        <div class="mb-2">
                            <div class="font-semibold text-emerald-700 dark:text-emerald-300 mb-2 text-sm">پاسخ شما:
                            </div>
                            <p class="p-4 rounded-2xl border border-emerald-200 dark:border-emerald-900/40 bg-emerald-50 dark:bg-emerald-500/10 text-sm text-slate-800 dark:text-slate-100">
                                {{ $studentReplyPreview }}
                            </p>
                        </div>
                    @else
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-2">
                                پاسخ شما:
                            </label>
                            <textarea wire:model.defer="studentReplyInput" rows="4"
                                      placeholder="پاسخ خود را بنویسید..."
                                      class="w-full rounded-2xl border border-slate-200 dark:border-slate-800
                                             bg-white dark:bg-slate-950
                                             text-slate-900 dark:text-slate-100
                                             px-3 py-2.5 text-sm
                                             focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500
                                             dark:focus:ring-blue-400/20 dark:focus:border-blue-400"></textarea>
                            @error('studentReplyInput')
                            <div class="mt-2 text-xs text-rose-600 dark:text-rose-300">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                </div>

                <div
                    class="px-4 sm:px-5 py-4 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between gap-3">
                    <button type="button" wire:click="closeReplyModal"
                            class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold
                                   border border-slate-200 hover:bg-slate-50 text-slate-700
                                   dark:border-slate-800 dark:hover:bg-slate-800/40 dark:text-slate-200">
                        بستن
                    </button>

                    @if(!$studentReplyPreview)
                        <button type="button" wire:click="saveStudentReply" wire:loading.attr="disabled"
                                class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold
                                       bg-blue-600 hover:bg-blue-700 text-white
                                       disabled:opacity-60 disabled:cursor-not-allowed
                                       dark:bg-blue-500 dark:hover:bg-blue-600">
                            <span wire:loading.remove wire:target="saveStudentReply">ثبت پاسخ</span>
                            <span wire:loading wire:target="saveStudentReply" class="inline-flex items-center gap-2">
                                <span
                                    class="h-4 w-4 animate-spin rounded-full border-2 border-white/60 border-t-white"></span>
                                در حال ثبت...
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
