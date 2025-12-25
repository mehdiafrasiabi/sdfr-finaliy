<div dir="rtl">

    @push('link')
        <style>
            @font-face {
                font-family: 'Digital';
                src: url('/client/assets/fonts/digital-7.ttf') format('truetype');
            }

            [x-cloak] {
                display: none !important;
            }

            .digital-clock {
                font-family: 'Digital', 'Courier New', monospace;
                font-size: 56px;
                letter-spacing: 2px;
                color: #ff9c00;
                text-shadow: 0 0 10px rgba(255, 156, 0, 0.45);
                line-height: 1;
            }

            .timer-shell {
                background: linear-gradient(135deg, #0b1220 0%, #111827 100%);
                border: 1px solid rgba(148, 163, 184, 0.18);
            }

            .dark .timer-shell {
                background: linear-gradient(135deg, #0b1020 0%, #0b1220 100%);
                border-color: rgba(148, 163, 184, 0.20);
            }

            .soft-surface {
                background: rgba(255, 255, 255, 0.92);
                border: 1px solid rgba(15, 23, 42, 0.08);
            }

            .dark .soft-surface {
                background: rgba(2, 6, 23, 0.6);
                border-color: rgba(148, 163, 184, 0.16);
            }

            .day-divider {
                border-top: 1px dashed rgba(148, 163, 184, 0.35);
            }

            .sticky-safe {
                position: sticky;
                top: 12px;
                z-index: 40;
            }

            .sticky-safe .timer-shell {
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            }

            .dark .sticky-safe .timer-shell {
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
            }

            .spinner {
                width: 14px;
                height: 14px;
                border: 2px solid rgba(255, 255, 255, 0.35);
                border-top-color: rgba(255, 255, 255, 0.95);
                border-radius: 999px;
                animation: spin 0.7s linear infinite;
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

            /* رنگ نوار پیشرفت (با کلاس) */
            .progress-ok {
                background: rgba(255, 255, 255, 0.70);
            }

            .progress-warn {
                background: rgba(251, 191, 36, 0.85);
            }

            /* amber */
            .progress-danger {
                background: rgba(248, 113, 113, 0.90);
            }

            /* red */

            /* جلوگیری از تغییر ارتفاع تایمر */
            .timer-shell {
                min-height: 230px; /* ثابت نگه داشتن ارتفاع (برای موبایل/دسکتاپ) */
            }

            /* روی موبایل اگر فونت بزرگه، کمی انعطاف */
            @media (max-width: 640px) {
                .timer-shell {
                    min-height: 250px;
                }

                .digital-clock {
                    font-size: 48px;
                }
            }

            /* وضعیت زنده بودن بدون تغییر ارتفاع */
            .live-dot {
                width: 8px;
                height: 8px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.55);
                box-shadow: 0 0 10px rgba(255, 255, 255, 0.25);
            }

            .live-dot.on {
                background: rgba(52, 211, 153, 0.9);
                box-shadow: 0 0 14px rgba(52, 211, 153, 0.45);
            }

        </style>
    @endpush

    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8"
                 x-data="{ permissionModal: @entangle('showPermissionModal'), finishModal: @entangle('showFinishModal') }">

                {{-- Header --}}
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex items-center gap-1">
                        <div class="w-1 h-1 bg-foreground rounded-full"></div>
                        <div class="w-2 h-2 bg-foreground rounded-full"></div>
                    </div>
                    <div class="font-black text-foreground">ثبت ساعت مطالعه بر اساس برنامه</div>

                    <a wire:navigate href="{{ route('client.profile.professionalTools.index') }}"
                       class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-background border border-border rounded-full text-muted transition-colors hover:text-foreground px-6 ms-auto">
                        <span class="font-semibold text-xs">بازگشت</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3"/>
                        </svg>
                    </a>
                </div>

                @if($weeklyProgram)

                    {{-- تایمر ثابت + sticky برای موبایل و دسکتاپ --}}
                    <div class="sticky-safe" wire:key="timer-fixed" wire:poll.visible.1000ms="tick">
                        <section class="timer-shell rounded-2xl p-5 sm:p-6 text-white">
                            @php
                                $progress = 0;
                                if ($currentPartId && $targetSeconds > 0) {
                                    $progress = (int) round((($targetSeconds - $remainingSeconds) / $targetSeconds) * 100);
                                    $progress = max(0, min(100, $progress));
                                }

                                $progressClass = 'progress-ok';
                                if ($progress >= 80) $progressClass = 'progress-danger';
                                elseif ($progress >= 55) $progressClass = 'progress-warn';

                                $currentPart = $currentPartId ? collect($programParts)->firstWhere('id', $currentPartId) : null;
                            @endphp

                            <div class="flex flex-col gap-4">

                                <div class="flex items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="text-sm text-slate-200 font-bold">
                                            تایمر مطالعه
                                        </div>

                                        <div class="text-[11px] text-slate-300 leading-5">
                                            @if($currentPartId)
                                                <span class="text-white font-semibold">پارت:</span>
                                                <span
                                                    class="text-slate-100">{{ $currentPart?->lesson_name ?? '—' }}</span>
                                                <span class="mx-2 text-slate-400">•</span>
                                                <span
                                                    class="{{ $isRunning ? 'text-emerald-200' : 'text-orange-200' }} font-semibold">
                                {{ $isRunning ? 'در حال اجرا' : 'متوقف' }}
                            </span>
                                            @else
                                                هیچ پارت فعالی انتخاب نشده — از لیست پایین یک پارت را شروع کن.
                                            @endif
                                        </div>

                                        {{-- وضعیت زنده بدون تغییر ارتفاع --}}
                                        <div class="mt-1 inline-flex items-center gap-2 text-[10px] text-slate-300">
                                            <span
                                                class="live-dot {{ ($currentPartId && $isRunning) ? 'on' : '' }}"></span>
                                            <span>{{ ($currentPartId && $isRunning) ? 'زنده' : 'آماده' }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if($currentPartId && $isRunning)
                                            <button wire:click="pausePart"
                                                    class="px-4 h-9 rounded-full bg-orange-500 hover:bg-orange-600 disabled:opacity-60 disabled:cursor-not-allowed font-semibold text-xs transition inline-flex items-center gap-2">
                                                <span>توقف</span>
                                            </button>
                                        @elseif($currentPartId && !$isRunning && $pausedAtTs)
                                            <button wire:click="resumePart"
                                                    class="px-4 h-9 rounded-full bg-emerald-500 hover:bg-emerald-600 disabled:opacity-60 disabled:cursor-not-allowed font-semibold text-xs transition inline-flex items-center gap-2">
                                                <span>ادامه</span>
                                            </button>
                                        @endif

                                        @if($currentPartId)
                                            <button wire:click="cancelPart"
                                                    class="px-4 h-9 rounded-full bg-white/10 hover:bg-white/15 border border-white/15 disabled:opacity-60 disabled:cursor-not-allowed font-semibold text-xs transition inline-flex items-center gap-2">
                                                <span>لغو</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-center">
                                    <div class="digital-clock">
                                        {{ $this->formatClock($currentPartId ? $remainingSeconds : 0) }}
                                    </div>
                                    <div class="mt-1 text-[11px] text-slate-300">
                                        {{ $currentPartId ? 'زمان باقی‌مانده' : 'برای شروع، روی دکمه شروع یکی از پارت‌ها بزنید' }}
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-[11px] text-slate-300">
                                        <span>پیشرفت</span>
                                        <span class="text-slate-200 font-semibold">
                        {{ $currentPartId ? $progress.'%' : '0%' }}
                    </span>
                                    </div>

                                    <div class="h-2 w-full rounded-full bg-white/10 overflow-hidden">
                                        <div class="h-2 rounded-full {{ $progressClass }} transition-all duration-300"
                                             style="width: {{ $currentPartId ? $progress : 0 }}%"></div>
                                    </div>

                                    <div class="flex items-center justify-between text-[10px] text-slate-300">
                    <span>
                        مصرف‌شده:
                        <span class="text-slate-100 font-semibold">
                            {{ $currentPartId ? $this->formatClock($targetSeconds - $remainingSeconds) : '00:00:00' }}
                        </span>
                    </span>
                                        <span>
                        کل:
                        <span class="text-slate-100 font-semibold">
                            {{ $currentPartId ? $this->formatClock($targetSeconds) : '00:00:00' }}
                        </span>
                    </span>
                                    </div>
                                </div>

                            </div>
                        </section>
                    </div>


                    {{-- فاصله برای اینکه محتوا زیر sticky نره --}}
                    <div class="h-3 sm:h-4"></div>


                    <div class="h-3 sm:h-4"></div>

                    {{-- ابزارها: نمایش/مخفی + فیلتر --}}
                    <section class="mt-5 soft-surface rounded-2xl p-4">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <div class="font-bold text-foreground">برنامه مطالعاتی من</div>
                                <div class="text-xs text-muted mt-1">
                                    بر اساس آخرین جلسه مشاوره برگزار شده
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center">
                                {{-- فیلتر --}}
                                <div class="inline-flex rounded-full border border-border bg-background p-1">
                                    <button wire:click="$set('dayFilter','all')"
                                            class="px-4 h-9 rounded-full text-xs font-semibold transition
                                            {{ $dayFilter==='all' ? 'bg-primary text-white' : 'text-muted hover:text-foreground' }}">
                                        همه
                                    </button>
                                    <button wire:click="$set('dayFilter','today')"
                                            class="px-4 h-9 rounded-full text-xs font-semibold transition
                                            {{ $dayFilter==='today' ? 'bg-primary text-white' : 'text-muted hover:text-foreground' }}">
                                        امروز
                                    </button>
                                    <button wire:click="$set('dayFilter','upcoming')"
                                            class="px-4 h-9 rounded-full text-xs font-semibold transition
                                            {{ $dayFilter==='upcoming' ? 'bg-primary text-white' : 'text-muted hover:text-foreground' }}">
                                        از امروز به بعد
                                    </button>
                                </div>

                                {{-- نمایش/مخفی --}}
                                <button wire:click="toggleProgram"
                                        class="px-5 h-10 rounded-full bg-primary hover:bg-primary/90 text-white text-xs font-semibold transition">
                                    {{ $showProgram ? 'مخفی کردن برنامه' : 'نمایش برنامه' }}
                                </button>
                            </div>
                        </div>
                    </section>

                    {{-- لیست برنامه --}}
                    @if($showProgram)

                        @php

                            $programDays = $this->getProgramDays();

                        @endphp



                        <div class="mt-5 space-y-7">

                            @foreach($programDays as $day)

                                {{-- فیلتر بر اساس dayFilter --}}

                                @php

                                    $showDay = true;

                                    if ($dayFilter === 'today') {

                                        $showDay = $day['date'] === now()->toDateString();

                                    } elseif ($dayFilter === 'upcoming') {

                                        $showDay = \Carbon\Carbon::parse($day['date'])->gte(now()->startOfDay());

                                    }

                                @endphp



                                @if($showDay)

                                    {{-- هدر روز --}}

                                    <div class="flex items-center justify-between">

                                        <div class="flex items-center gap-2">

                                            <span
                                                class="w-2 h-2 rounded-full {{ $day['is_rest_day'] ? 'bg-emerald-500' : 'bg-primary' }}"></span>

                                            <span
                                                class="font-black text-sm {{ $day['is_rest_day'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-foreground' }}">

                                                {{ $day['name'] }}

                                            </span>

                                            <span class="text-xs text-muted">{{ $day['jalali_date'] }}</span>

                                            @if($day['is_rest_day'])

                                                <span
                                                    class="px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold">

                                                    روز استراحت

                                                </span>

                                            @endif

                                        </div>


                                        <div class="text-[11px] text-muted">

                                            @if($day['is_rest_day'])

                                                <span class="text-emerald-600 dark:text-emerald-400">استراحت</span>

                                            @else

                                                {{ $day['parts_count'] }} پارت

                                            @endif

                                        </div>

                                    </div>



                                    @if($day['is_rest_day'])

                                        {{-- نمایش روز استراحت --}}

                                        <div
                                            class="rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50/50 dark:bg-emerald-900/10 p-8 text-center">

                                            <div class="text-4xl mb-3">🌿</div>

                                            <h4 class="font-bold text-emerald-700 dark:text-emerald-400 mb-1">روز
                                                استراحت</h4>

                                            <p class="text-sm text-emerald-600/80 dark:text-emerald-400/70">

                                                امروز نیازی به مطالعه نیست. استراحت کن و انرژی بگیر!

                                            </p>

                                        </div>

                                    @elseif($day['parts_count'] > 0)

                                        {{-- جدول ردیفی (مرتب، بدون کارت‌های شلوغ) --}}

                                        <div class="overflow-x-auto rounded-2xl border border-border bg-background">

                                            <table class="w-full text-xs sm:text-[13px]">

                                                <thead>

                                                <tr class="bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-200">

                                                    <th class="px-3 py-3 text-right">درس</th>

                                                    <th class="px-3 py-3 text-center">مدت</th>

                                                    <th class="px-3 py-3 text-center">نوع</th>

                                                    <th class="px-3 py-3 text-center">تست</th>

                                                    <th class="px-3 py-3 text-left">عملیات</th>

                                                </tr>

                                                </thead>


                                                <tbody class="divide-y divide-border">

                                                @foreach($day['parts']->sortBy('part_order') as $part)

                                                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40">

                                                        <td class="px-3 py-3 text-foreground">

                                                            <div class="font-semibold">

                                                                {{ $part->lesson_name }}

                                                            </div>

                                                            @if($part->description)

                                                                <div class="text-[11px] text-muted mt-1">

                                                                    {{ $part->description }}

                                                                </div>

                                                            @endif

                                                        </td>


                                                        <td class="px-3 py-3 text-center text-foreground">

                                                            {{ floor($part->duration_minutes / 60) }}

                                                            س {{ $part->duration_minutes % 60 }}د

                                                        </td>


                                                        <td class="px-3 py-3 text-center">

                                                            <span class="px-2 py-1 rounded-full text-[10px]

                                                                bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200">

                                                                {{ $part->part_type_label }}

                                                            </span>

                                                            <span class="ms-1 px-2 py-1 rounded-full text-[10px]

                                                                bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200">

                                                                {{ $part->lesson_type_label }}

                                                            </span>

                                                        </td>


                                                        <td class="px-3 py-3 text-center text-foreground">

                                                            {{ $part->test_count ?? '-' }}

                                                        </td>


                                                        <td class="px-3 py-3 text-left">

                                                            @if($this->isPartCompleted($part->id))

                                                                <span
                                                                    class="text-emerald-600 dark:text-emerald-400 font-bold text-[11px]">

                                                                    ✅ تکمیل شده

                                                                </span>

                                                            @elseif($currentPartId == $part->id)

                                                                <span
                                                                    class="text-blue-600 dark:text-blue-400 font-bold text-[11px]">

                                                                    🎯 در حال مطالعه...

                                                                </span>

                                                            @else

                                                                <button wire:click="startPart({{ $part->id }})"

                                                                        wire:loading.attr="disabled"

                                                                        wire:target="startPart({{ $part->id }})"

                                                                        class="px-4 h-9 rounded-xl bg-primary hover:bg-primary/90 text-white text-[11px] font-semibold transition disabled:opacity-60 disabled:cursor-not-allowed inline-flex items-center gap-2"

                                                                    {{ $currentPartId ? 'disabled' : '' }}>

                                                                    <span wire:loading.remove
                                                                          wire:target="startPart({{ $part->id }})">شروع</span>

                                                                    <span wire:loading
                                                                          wire:target="startPart({{ $part->id }})"
                                                                          class="spinner"></span>

                                                                </button>

                                                            @endif

                                                        </td>

                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div
                                            class="rounded-2xl border border-border bg-slate-50/50 dark:bg-slate-900/20 p-6 text-center">
                                            <p class="text-sm text-muted">برنامه‌ای برای این روز تنظیم نشده است.</p>
                                        </div>
                                    @endif
                                    <div class="day-divider"></div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                @else
                    {{-- پیام عدم وجود برنامه --}}
                    <div
                        class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-6 text-center">
                        <div class="text-yellow-600 dark:text-yellow-400 text-5xl mb-4">⚠️</div>
                        <h3 class="text-lg font-bold text-yellow-800 dark:text-yellow-300 mb-2">
                            برنامه‌ای یافت نشد
                        </h3>
                        <p class="text-sm text-yellow-700 dark:text-yellow-400">
                            هنوز جلسه مشاوره‌ای برگزار نشده یا برنامه‌ای برای شما تنظیم نشده است.
                            <br>
                            لطفاً با مشاور خود تماس بگیرید.
                        </p>
                    </div>
                @endif


                {{-- مودال دسترسی --}}
                <div x-cloak x-show="permissionModal"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
                     x-transition>
                    <div
                        class="w-full max-w-md mx-4 bg-background dark:bg-zinc-900 border border-border rounded-2xl shadow-2xl"
                        @click.away="permissionModal = false">
                        <div class="flex items-center justify-between px-6 py-4 border-b border-border">
                            <h3 class="text-base font-bold text-foreground">درخواست دسترسی</h3>
                            <button type="button" @click="permissionModal = false"
                                    class="text-muted hover:text-foreground transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="px-6 py-5 space-y-4">
                            <div
                                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                                <h4 class="font-semibold text-sm text-blue-800 dark:text-blue-300 mb-2">
                                    چرا این دسترسی‌ها نیاز است؟
                                </h4>
                                <ul class="text-xs text-blue-700 dark:text-blue-400 space-y-2 list-disc list-inside">
                                    <li>دسترسی به صدا برای پخش الارم هنگام پایان تایمر</li>
                                    <li>دسترسی به نوتیفیکیشن برای یادآوری‌های مطالعه</li>
                                    <li>این دسترسی‌ها فقط برای عملکرد بهتر تایمر استفاده می‌شوند</li>
                                </ul>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end gap-3 px-6 py-4 border-t border-border bg-secondary rounded-b-2xl">
                            <button type="button"
                                    class="px-6 h-11 rounded-full bg-primary text-white font-semibold hover:bg-primary/90 transition"
                                    wire:click="permissionUnderstood">
                                متوجه شدم
                            </button>
                        </div>
                    </div>
                </div>


                {{-- مودال پایان تایمر --}}
                <div x-cloak x-show="finishModal"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
                     x-transition>
                    <div
                        class="w-full max-w-md mx-4 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-2 border-green-500 rounded-3xl shadow-2xl">
                        <div class="px-6 py-8 text-center space-y-4">
                            <div class="flex justify-center">
                                <div
                                    class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center animate-bounce">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="3" stroke="white" class="w-10 h-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <h3 class="text-2xl font-black text-green-600 dark:text-green-400">🎉 آفرین! 🎉</h3>
                                <p class="text-lg font-bold text-green-700 dark:text-green-300">تایم مطالعه به پایان
                                    رسید</p>
                                <p class="text-sm text-green-600 dark:text-green-400">آیا می‌خواهید این پارت را ثبت
                                    کنید؟</p>
                            </div>

                            <div class="flex items-center justify-center gap-3 pt-4">
                                <button type="button" @click="finishModal = false" wire:click="closeFinishModal"
                                        class="px-6 h-11 rounded-full border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                    بستن
                                </button>
                                <button type="button" wire:click="savePart"
                                        class="px-8 h-11 rounded-full bg-green-500 hover:bg-green-600 text-white font-semibold transition transform hover:scale-105">
                                    ثبت پارت
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div> {{-- col --}}
        </div>
    </div>


    @push('script')
        <script>
            window.addEventListener('request-permissions', async () => {
                try {
                    if ('Notification' in window && Notification.permission !== 'granted') {
                        await Notification.requestPermission();
                    }

                    const testAudio = new Audio('/client/sounds/Alarmclock.ogg');
                    testAudio.volume = 0.01;
                    await testAudio.play();
                    testAudio.pause();

                @this.call('onPermissionsGranted')
                    ;
                } catch (error) {
                    console.warn('Permission request error:', error);
                }
            });

            window.addEventListener('play-alarm', () => {
                try {
                    const audio = new Audio('/client/sounds/Alarmclock.ogg');
                    audio.volume = 1;
                    audio.play().catch(e => console.warn("Sound play blocked:", e));

                    if ('Notification' in window && Notification.permission === 'granted') {
                        new Notification('⏰ زمان مطالعه به پایان رسید!', {
                            body: 'پارت مطالعاتی شما با موفقیت تکمیل شد.',
                            icon: '/favicon.ico',
                            badge: '/favicon.ico'
                        });
                    }
                } catch (error) {
                    console.warn('Alarm error:', error);
                }
            });

            window.addEventListener('livewire:initialized', () => {
                if ('Notification' in window && Notification.permission === 'granted') {
                @this.call('onPermissionsGranted')
                    ;
                }
            });
        </script>

    @endpush

</div>
