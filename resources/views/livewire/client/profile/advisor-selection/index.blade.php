
<div>
    @php
        $avatarFallback = asset('/client/assets/images/soon/soon.png');
    @endphp

    @if ($showSlotModal || $showModal || $showConfirmModal)
        <style>
            html,
            body {
                overflow: hidden;
                overscroll-behavior: none;
            }
        </style>
    @endif

    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

            {{-- محتوای اصلی انتخاب مشاور --}}
            <div class="lg:col-span-12 md:col-span-12">
                <div class="space-y-8">

                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <h1 class="font-black text-xl text-foreground">انتخاب مشاور تحصیلی</h1>
                    </div>

                    <div class="glass rounded-2xl p-5 md:p-6 border border-border">
                        <p class="text-muted text-xs md:text-sm leading-6">
                            روز هفته و ساعتِ موردنظرت را انتخاب کن تا مشاورانی که در آن زمان فعال‌اند نمایش داده شوند.
                            می‌توانی خودت مشاور را انتخاب کنی یا گزینه‌ی «انتخاب تصادفی» را بزنی تا سیستم برایت انتخاب کند.
                        </p>
                    </div>

                    <div class="space-y-5" wire:poll.visible.30000ms>

                        {{-- مشاورِ تاییدشده --}}
                        @if ($approvedAdvisor)
                            <div class="glass rounded-2xl p-5 md:p-6 border border-emerald-500/20 shadow-sm relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-1.5 h-full bg-emerald-500 rounded-r-2xl"></div>
                                <h2 class="text-sm font-bold mb-4 text-emerald-600">مشاور شما</h2>
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                    <img src="{{ $approvedAdvisor->picture_url ?? $avatarFallback }}" alt="{{ $approvedAdvisor->name }}"
                                         class="w-16 h-16 rounded-full object-cover border-2 border-emerald-500/30">
                                    <div>
                                        <div class="font-bold text-base md:text-lg">{{ $approvedAdvisor->name }}</div>
                                        @if ($approvedAdvisor->field_of_study)
                                            <div class="text-muted text-xs mt-1">{{ $approvedAdvisor->field_of_study }}</div>
                                        @endif
                                        <div class="text-emerald-600 text-xs md:text-sm mt-3 inline-flex items-center gap-1.5 bg-emerald-500/10 px-3 py-1.5 rounded-full">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                            مشاورِ شما به‌صورت قطعی تخصیص داده شده است.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- انتخابِ معلق (در انتظار تایید مدیر) --}}
                        @elseif ($pending)
                            <div class="glass rounded-2xl p-5 md:p-6 border border-amber-500/20 shadow-sm relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-1.5 h-full bg-amber-500 rounded-r-2xl"></div>
                                <h2 class="text-sm font-bold mb-4 text-amber-600">در انتظار تایید مدیر آموزشی</h2>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $pending->advisor->picture_url ?? $avatarFallback }}" alt="{{ $pending->advisor->name }}"
                                             class="w-16 h-16 rounded-full object-cover border-2 border-amber-500/30">
                                        <div class="flex-1">
                                            <div class="font-bold text-base md:text-lg">{{ $pending->advisor->name }}</div>
                                            <div class="text-muted text-xs mt-1 space-y-1">
                                                <div>روز جلسه: <span class="font-semibold text-foreground">{{ $days[$pending->weekly_day] ?? '—' }}</span></div>
                                                @if ($pending->preferred_hour)
                                                    <div>ساعتِ موردنظر: <span class="font-semibold text-foreground">{{ substr($pending->preferred_hour, 0, 5) }}</span></div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col sm:items-end gap-3 w-full sm:w-auto">
                                        <div class="text-amber-600 text-[11px] md:text-xs bg-amber-500/10 px-3 py-2 rounded-xl text-justify max-w-[280px]">
                                            انتخابِ شما ثبت شده و منتظرِ تاییدِ مدیر آموزشی است. ساعتِ دقیقِ هر جلسه را مشاور یک روز قبل مشخص می‌کند.
                                        </div>
                                        <button wire:click="cancelSelection"
                                                wire:confirm="انتخابِ فعلی لغو شود؟"
                                                class="w-full sm:w-auto h-10 px-5 rounded-xl bg-red-500/10 text-red-600 text-xs font-bold hover:bg-red-500 hover:text-white transition-colors">
                                            لغو و انتخابِ مجدد
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- فرمِ انتخابِ مشاور --}}
                        @elseif ($student && $student->advisor_id === null)
                            <div class="glass rounded-2xl p-5 md:p-6 border border-border shadow-sm relative z-20">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-end">
                                    <div>
                                        <label class="block text-xs font-semibold text-muted mb-2">روز هفته</label>
                                        <x-ui.select
                                            wire:model="filterDay"
                                            placeholder="انتخاب روز..."
                                            :options="collect($days)->map(fn($name, $id) => ['id' => $id, 'name' => $name])->values()->all()"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-muted mb-2">ساعت</label>
                                         <x-ui.select
                                            wire:model="filterHour"
                                            placeholder="انتخاب ساعت..."
                                            :options="collect($hours)->map(fn($h) => ['id' => $h, 'name' => sprintf('%02d:00', $h)])->values()->all()"
                                        />
                                    </div>
                                    <div class="sm:col-span-2 lg:col-span-1">
                                        <button wire:click="openSlotModal"
                                                class="w-full h-11 rounded-xl bg-primary text-primary-foreground text-sm font-bold hover:opacity-90 hover:shadow-lg hover:shadow-primary/30 transition-all flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.85-5.15a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" /></svg>
                                            انتخاب روز و ساعت
                                        </button>
                                    </div>
                                </div>
                                @error('filterDay') <div class="text-red-500 text-xs mt-3 font-medium">{{ $message }}</div> @enderror
                            </div>

                            {{-- فهرستِ مشاوران --}}
                            <div class="mt-6">
                                @if ($advisors->isEmpty())
                                    <div class="glass rounded-2xl p-10 flex flex-col items-center justify-center border border-border border-dashed space-y-4">
                                        <img src="/client/svg/empty2.svg" class="w-full max-w-[200px] opacity-50" alt="بدون نتیجه">
                                        <p class="text-center text-muted text-sm font-medium">
                                            @if ($filterDay !== null && $filterDay !== '' && $filterHour !== null && $filterHour !== '')
                                                برای این روز و ساعت، مشاورِ آزادی یافت نشد. روز یا ساعتِ دیگری را امتحان کنید.
                                            @else
                                                در حال حاضر مشاوری برای نمایش وجود ندارد.
                                            @endif
                                        </p>
                                    </div>
                                @else
                                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-5">
                                        @foreach ($advisors as $advisor)
                                            @php
                                                $capacity = $advisor->student_capacity ?? $defaultCapacity;
                                                $taken = (int) ($advisor->advised_students_count ?? 0);
                                                $remaining = max(0, $capacity - $taken);
                                                $capacityPercent = $capacity > 0 ? min(100, round(($taken / $capacity) * 100)) : 100;
                                            @endphp
                                            <div class="glass rounded-2xl p-4 border border-border flex flex-col hover:shadow-md transition-all group">
                                                <div class="flex items-center gap-3">
                                                    <img src="{{ $advisor->picture_url ?? $avatarFallback }}" alt="{{ $advisor->name }}"
                                                         class="w-14 h-14 rounded-full object-cover border border-border shadow-sm group-hover:border-primary/50 transition-colors">
                                                    <div class="min-w-0 flex-1">
                                                        <div class="font-bold text-foreground truncate">{{ $advisor->name }}</div>
                                                        @if ($advisor->field_of_study)
                                                            <div class="text-primary text-xs truncate mt-0.5 font-medium">{{ $advisor->field_of_study }}</div>
                                                        @endif
                                                        @if ($advisor->education)
                                                            <div class="text-muted text-[11px] truncate mt-1">{{ $advisor->education }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex gap-2 mt-5">
                                                    <button wire:click="openModal({{ $advisor->id }})"
                                                            class="flex-1 h-10 rounded-xl bg-background border border-border text-xs font-bold text-muted hover:text-foreground hover:bg-secondary transition">
                                                      خلاصه معرفی
                                                    </button>
                                                    <button wire:click="openConfirmModal({{ $advisor->id }})"
                                                            class="flex-[2] h-10 rounded-xl bg-primary text-primary-foreground text-xs font-bold hover:opacity-90 transition">
                                                        انتخاب مشاور
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="glass rounded-2xl p-10 flex flex-col items-center justify-center border border-border space-y-4">
                                <svg class="w-12 h-12 text-muted opacity-50" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                                <p class="text-center text-muted font-medium text-sm">
                                    این بخش مخصوصِ دانش‌آموزانِ خریدکرده است.
                                </p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- مودال انتخاب روز و ساعت --}}
    @if ($showSlotModal)
        <div class="fixed inset-0 z-[105] flex items-end justify-center overscroll-contain sm:items-center sm:p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>
            <div class="relative w-full rounded-t-3xl border border-border bg-background p-5 shadow-2xl sm:max-w-md sm:rounded-2xl sm:p-6">
                <div class="mx-auto mb-4 h-1.5 w-14 rounded-full bg-border sm:hidden"></div>
                <div class="pl-10">
                    <div class="text-xs font-bold text-primary">شروع انتخاب مشاور</div>
                    <h2 class="mt-1 text-lg font-black text-foreground">روز و ساعت جلسه هفتگی را انتخاب کن</h2>
                    <p class="mt-2 text-xs leading-6 text-muted">بعد از انتخاب، فقط مشاورانی را می‌بینی که همان زمان ظرفیت و ساعت کاری فعال دارند.</p>
                </div>

                <div class="mt-5 grid gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-muted mb-2">روز هفته</label>
                        <x-ui.select
                            wire:model="filterDay"
                            placeholder="انتخاب روز..."
                            :drop-up="true"
                            :options="collect($days)->map(fn($name, $id) => ['id' => $id, 'name' => $name])->values()->all()"
                        />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-muted mb-2">ساعت</label>
                        <x-ui.select
                            wire:model="filterHour"
                            placeholder="انتخاب ساعت..."
                            :drop-up="true"
                            :options="collect($hours)->map(fn($h) => ['id' => $h, 'name' => sprintf('%02d:00', $h)])->values()->all()"
                        />
                    </div>
                </div>

                <div class="mt-5 rounded-2xl border border-border bg-secondary/35 p-2">
                    <button wire:click="applySlotSelection" wire:loading.attr="disabled" wire:target="applySlotSelection"
                            class="flex min-h-12 w-full items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-black text-primary-foreground shadow-lg shadow-primary/20 transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-60">
                        <span wire:loading.remove wire:target="applySlotSelection">نمایش مشاوران</span>
                        <span wire:loading wire:target="applySlotSelection">در حال بررسی...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- مودالِ جزئیاتِ مشاور (این بخش خارج از گرید اصلی رندر می‌شود تا تمام‌صفحه باز شود) --}}
    @if ($showModal && $modalAdvisor)
        @php
            $modalCapacity = $modalAdvisor->student_capacity ?? $defaultCapacity;
            $modalTaken = (int) ($modalAdvisor->advised_students_count ?? 0);
            $modalRemaining = max(0, $modalCapacity - $modalTaken);
            $modalCapacityPercent = $modalCapacity > 0 ? min(100, round(($modalTaken / $modalCapacity) * 100)) : 100;
        @endphp
        <div class="fixed inset-0 z-[100] flex items-end justify-center overscroll-contain sm:items-center sm:p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>
            <div class="relative glass w-full rounded-t-3xl border border-border bg-background/95 p-5 shadow-2xl sm:max-w-md sm:rounded-2xl sm:p-6">
                <div class="mx-auto mb-4 h-1.5 w-14 rounded-full bg-border sm:hidden"></div>
                <button wire:click="closeModal"
                        class="absolute top-4 left-4 p-1.5 bg-secondary rounded-full text-muted hover:text-foreground transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>

                <div class="flex flex-col items-center text-center mt-2">
                    <img src="{{ $modalAdvisor->picture_url ?? $avatarFallback }}" alt="{{ $modalAdvisor->name }}"
                         class="w-24 h-24 rounded-full object-cover border-4 border-background shadow-md mb-3">
                    <div class="text-lg font-bold text-foreground">{{ $modalAdvisor->name }}</div>
                    @if ($modalAdvisor->field_of_study)
                        <div class="text-primary text-sm font-medium mt-1">{{ $modalAdvisor->field_of_study }}</div>
                    @endif
                </div>

                <div class="mt-6 max-h-[56vh] space-y-4 overflow-y-auto overscroll-contain rounded-xl border border-border/50 bg-secondary/50 p-4 text-sm sm:max-h-[58vh]">

                    <div class="grid gap-3">
                        <div class="flex items-center justify-between gap-4 border-b border-border/50 pb-2">
                            <span class="text-muted text-xs">نام مشاور</span>
                            <span class="font-bold text-foreground">{{ $modalAdvisor->name }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-4 border-b border-border/50 pb-2">
                            <span class="text-muted text-xs">تحصیلات</span>
                            <span class="text-left font-bold text-foreground">{{ $modalAdvisor->education ?: '—' }}</span>
                        </div>
                        <div class="border-b border-border/50 pb-3">
                            <div class="text-muted text-xs mb-2">خلاصه معرفی</div>
                            <p class="leading-7 text-foreground/90 text-justify text-xs">{{ $modalAdvisor->bio ?: 'توضیحاتی برای این مشاور ثبت نشده است.' }}</p>
                        </div>
                    </div>

                    <div>
                        <div class="text-muted text-xs mb-3">ساعات کاری فعال</div>
                        @php
                            $activeSchedules = $modalAdvisor->workSchedules->where('is_active', true)->sortBy('day_of_week');
                        @endphp
                        @if ($activeSchedules->isEmpty())
                            <div class="text-center text-muted text-xs py-2">ساعت کاری ثبت نشده است.</div>
                        @else
                            <ul class="space-y-2">
                                @foreach ($activeSchedules as $ws)
                                    <li class="flex items-center justify-between bg-background border border-border rounded-lg px-3 py-2">
                                        <span class="font-bold text-xs">{{ $days[$ws->day_of_week] ?? '' }}</span>
                                        <span class="text-muted text-xs font-mono bg-secondary px-2 py-1 rounded" dir="ltr">
                                            {{ substr($ws->start_time, 0, 5) }} - {{ substr($ws->end_time, 0, 5) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                @if ($student && $student->advisor_id === null && !$pending)
                    <button wire:click="openConfirmModal({{ $modalAdvisor->id }})"
                            class="w-full h-12 mt-6 rounded-xl bg-primary text-primary-foreground text-sm font-bold hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        انتخاب این مشاور
                    </button>
                @endif
            </div>
        </div>
    @endif

    {{-- مودال تایید انتخاب مشاور --}}
    @if ($showConfirmModal && $confirmAdvisor)
        @php
            $confirmCapacity = $confirmAdvisor->student_capacity ?? $defaultCapacity;
            $confirmTaken = (int) ($confirmAdvisor->advised_students_count ?? 0);
            $confirmRemaining = max(0, $confirmCapacity - $confirmTaken);
        @endphp
        <div class="fixed inset-0 z-[110] flex items-end justify-center overscroll-contain sm:items-center sm:p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" wire:click="closeConfirmModal"></div>
            <div class="relative w-full rounded-t-3xl border border-border bg-background p-5 shadow-2xl sm:max-w-md sm:rounded-2xl sm:p-6">
                <div class="mx-auto mb-4 h-1.5 w-14 rounded-full bg-border sm:hidden"></div>
                <button wire:click="closeConfirmModal"
                        class="absolute left-4 top-4 rounded-full bg-secondary p-1.5 text-muted transition-colors hover:text-foreground">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>

                <div class="flex items-center gap-3 pl-10">
                    <img src="{{ $confirmAdvisor->picture_url ?? $avatarFallback }}" alt="{{ $confirmAdvisor->name }}"
                         class="h-16 w-16 rounded-2xl border border-border object-cover">
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-primary">تایید انتخاب مشاور</div>
                        <h3 class="mt-1 truncate text-lg font-black text-foreground">{{ $confirmAdvisor->name }}</h3>
                        @if ($confirmAdvisor->education)
                            <div class="mt-1 truncate text-xs font-semibold text-muted">{{ $confirmAdvisor->education }}</div>
                        @endif
                    </div>
                </div>

                <div class="mt-5 rounded-2xl border border-primary/15 bg-primary/10 p-4 text-sm leading-7 text-foreground">
                    شما مشاور <span class="font-black">{{ $confirmAdvisor->name }}</span> را انتخاب کرده‌اید و جلسات شما هر هفته در
                    <span class="font-black">{{ $days[(int) $filterDay] ?? '—' }}</span>
                    @if ($filterHour !== null && $filterHour !== '')
                        ساعت <span class="font-black">{{ sprintf('%02d:00', (int) $filterHour) }}</span>
                    @endif
                    برگزار خواهد شد. آیا تایید می‌کنید؟
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl border border-border bg-secondary/50 p-3 text-center">
                        <div class="text-[11px] text-muted">زمان انتخابی</div>
                        <div class="mt-1 text-sm font-black text-foreground">
                            {{ $days[(int) $filterDay] ?? '—' }}
                            @if ($filterHour !== null && $filterHour !== '')
                                {{ sprintf('%02d:00', (int) $filterHour) }}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <button wire:click="closeConfirmModal"
                            class="min-h-12 rounded-2xl border border-border bg-red-500 px-5 py-3 text-sm font-black text-foreground shadow-sm transition hover:border-primary/40 hover:bg-secondary">
                       لغو
                    </button>
                    <button wire:click="confirmAdvisorSelection" wire:loading.attr="disabled" wire:target="confirmAdvisorSelection"
                            class="min-h-12 rounded-2xl bg-primary px-5 py-3 text-sm font-black text-primary-foreground shadow-lg shadow-primary/20 transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-60">
                        <span wire:loading.remove wire:target="confirmAdvisorSelection">تایید به عنوان مشاور </span>
                        <span wire:loading wire:target="confirmAdvisorSelection">در حال ثبت...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
