
<div>
    @php
        $avatarFallback = asset('/client/assets/images/soon/soon.png');
    @endphp

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
                            <div class="glass rounded-2xl p-5 md:p-6 border border-success/20 shadow-sm relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-1.5 h-full bg-success rounded-r-2xl"></div>
                                <h2 class="text-sm font-bold mb-4 text-success">مشاور شما</h2>
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                    <img src="{{ $approvedAdvisor->picture_url ?? $avatarFallback }}" alt="{{ $approvedAdvisor->name }}"
                                         class="w-16 h-16 rounded-full object-cover border-2 border-success/30">
                                    <div>
                                        <div class="font-bold text-base md:text-lg">{{ $approvedAdvisor->name }}</div>
                                        @if ($approvedAdvisor->field_of_study)
                                            <div class="text-muted text-xs mt-1">{{ $approvedAdvisor->field_of_study }}</div>
                                        @endif
                                        <div class="text-success text-xs md:text-sm mt-3 inline-flex items-center gap-1.5 bg-success/10 px-3 py-1.5 rounded-full">
                                            <x-ui.icon name="check" class="w-4 h-4"/>
                                            مشاورِ شما به‌صورت قطعی تخصیص داده شده است.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- انتخابِ معلق (در انتظار تایید مدیر) --}}
                        @elseif ($pending)
                            <div class="glass rounded-2xl p-5 md:p-6 border border-warning/20 shadow-sm relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-1.5 h-full bg-warning rounded-r-2xl"></div>
                                <h2 class="text-sm font-bold mb-4 text-warning">در انتظار تایید مدیر آموزشی</h2>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $pending->advisor->picture_url ?? $avatarFallback }}" alt="{{ $pending->advisor->name }}"
                                             class="w-16 h-16 rounded-full object-cover border-2 border-warning/30">
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
                                        <div class="text-warning text-[11px] md:text-xs bg-warning/10 px-3 py-2 rounded-xl text-justify max-w-[280px]">
                                            انتخابِ شما ثبت شده و منتظرِ تاییدِ مدیر آموزشی است. ساعتِ دقیقِ هر جلسه را مشاور یک روز قبل مشخص می‌کند.
                                        </div>
                                        <x-ui.button type="button" wire:click="cancelSelection" wire:confirm="انتخابِ فعلی لغو شود؟"
                                                     variant="error-soft" icon="x" class="w-full sm:w-auto">
                                            لغو و انتخابِ مجدد
                                        </x-ui.button>
                                    </div>
                                </div>
                            </div>

                            {{-- فرمِ انتخابِ مشاور --}}
                        @elseif ($student && $student->advisor_id === null)
                            <div class="glass rounded-2xl p-5 md:p-6 border border-border shadow-sm relative z-20 space-y-4">
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
                                        <x-ui.button type="button" wire:click="openSlotModal" variant="primary" icon="search" size="lg" block>
                                            انتخاب روز و ساعت
                                        </x-ui.button>
                                    </div>
                                </div>
                                @error('filterDay') <div class="text-error text-xs font-medium">{{ $message }}</div> @enderror

                                {{-- انتخاب تصادفی: قابلیتی که در بک‌اندِ کامپوننت (selectRandom) کامل و آماده بود
                                     ولی هیچ دکمه‌ای در ویو نداشت، با اینکه همین متنِ بالای صفحه به کاربر وعده‌اش را
                                     می‌داد. طبق تاییدِ کاربر همین‌جا اضافه شد. --}}
                                <div class="flex items-center gap-3 pt-1">
                                    <div class="h-px flex-1 bg-border"></div>
                                    <span class="text-[11px] text-muted shrink-0">یا</span>
                                    <div class="h-px flex-1 bg-border"></div>
                                </div>
                                <x-ui.button type="button" wire:click="selectRandom" wire:loading.attr="disabled" wire:target="selectRandom"
                                             wire:confirm="با این کار سیستم به‌طور خودکار یک مشاورِ آزاد برای شما انتخاب و ثبت می‌کند. ادامه می‌دهید؟"
                                             variant="secondary-outline" block>
                                    <span wire:loading.remove wire:target="selectRandom" class="inline-flex items-center gap-1.5">
                                        انتخاب تصادفی مشاور <x-ui.icon name="sparkles" class="w-4 h-4"/>
                                    </span>
                                    <span wire:loading wire:target="selectRandom">
                                        <x-ui.spinner size="xs"/>
                                    </span>
                                </x-ui.button>
                            </div>

                            {{-- فهرستِ مشاوران --}}
                            <div class="mt-6">
                                @if ($advisors->isEmpty())
                                    <div class="glass rounded-2xl p-10 border border-border border-dashed">
                                        <x-ui.empty-state>
                                            @if ($filterDay !== null && $filterDay !== '' && $filterHour !== null && $filterHour !== '')
                                                برای این روز و ساعت، مشاورِ آزادی یافت نشد. روز یا ساعتِ دیگری را امتحان کنید.
                                            @else
                                                در حال حاضر مشاوری برای نمایش وجود ندارد.
                                            @endif
                                        </x-ui.empty-state>
                                    </div>
                                @else
                                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-5">
                                        @foreach ($advisors as $advisor)
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
                                                    <x-ui.button type="button" wire:click="openModal({{ $advisor->id }})"
                                                                 variant="secondary-outline" icon="eye" size="sm" class="flex-1">
                                                        خلاصه معرفی
                                                    </x-ui.button>
                                                    <x-ui.button type="button" wire:click="openConfirmModal({{ $advisor->id }})"
                                                                 variant="primary" icon="check" size="sm" class="flex-[2]">
                                                        انتخاب مشاور
                                                    </x-ui.button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="glass rounded-2xl p-10 border border-border">
                                <x-ui.empty-state>این بخش مخصوصِ دانش‌آموزانِ خریدکرده است.</x-ui.empty-state>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- مودال انتخاب روز و ساعت --}}
    <div
        x-data="{ open: @entangle('showSlotModal') }"
        x-effect="open ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
        x-cloak
    >
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[105] bg-black/60 backdrop-blur-sm"
            @click="$wire.set('showSlotModal', false)"
        ></div>

        <div x-show="open" class="fixed inset-0 z-[106] flex items-end justify-center overscroll-contain sm:items-center sm:p-4">
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                class="relative w-full rounded-t-3xl border border-border bg-background p-5 shadow-2xl sm:max-w-md sm:rounded-2xl sm:p-6"
                @click.self="$wire.set('showSlotModal', false)"
            >
                <div class="mx-auto mb-4 h-1.5 w-14 rounded-full bg-border sm:hidden"></div>
                <button type="button" @click="$wire.set('showSlotModal', false)" data-elevated="false"
                        class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors">
                    <x-ui.icon name="x" class="w-4 h-4"/>
                </button>

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
                    <x-ui.button type="button" wire:click="applySlotSelection" wire:loading.attr="disabled" wire:target="applySlotSelection"
                                 variant="primary" size="lg" block>
                        <span wire:loading.remove wire:target="applySlotSelection" class="inline-flex items-center gap-1.5">
                            نمایش مشاوران <x-ui.icon name="search" class="w-4 h-4"/>
                        </span>
                        <span wire:loading wire:target="applySlotSelection">
                            <x-ui.spinner size="xs"/>
                        </span>
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>

    {{-- مودالِ جزئیاتِ مشاور --}}
    <div
        x-data="{ open: @entangle('showModal') }"
        x-effect="open ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
        x-cloak
    >
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm"
            @click="$wire.closeModal()"
        ></div>

        @if ($modalAdvisor)
            <div x-show="open" class="fixed inset-0 z-[101] flex items-end justify-center overscroll-contain sm:items-center sm:p-4">
                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                    class="relative glass w-full rounded-t-3xl border border-border bg-background/95 p-5 shadow-2xl sm:max-w-md sm:rounded-2xl sm:p-6"
                    @click.self="$wire.closeModal()"
                >
                    <div class="mx-auto mb-4 h-1.5 w-14 rounded-full bg-border sm:hidden"></div>
                    <button type="button" @click="$wire.closeModal()" data-elevated="false"
                            class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors">
                        <x-ui.icon name="x" class="w-4 h-4"/>
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
                        <x-ui.button type="button" wire:click="openConfirmModal({{ $modalAdvisor->id }})"
                                     variant="primary" icon="check" size="lg" block class="mt-6">
                            انتخاب این مشاور
                        </x-ui.button>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- مودال تایید انتخاب مشاور --}}
    <div
        x-data="{ open: @entangle('showConfirmModal') }"
        x-effect="open ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
        x-cloak
    >
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[110] bg-black/60 backdrop-blur-sm"
            @click="$wire.closeConfirmModal()"
        ></div>

        @if ($confirmAdvisor)
            <div x-show="open" class="fixed inset-0 z-[111] flex items-end justify-center overscroll-contain sm:items-center sm:p-4">
                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                    class="relative w-full rounded-t-3xl border border-border bg-background p-5 shadow-2xl sm:max-w-md sm:rounded-2xl sm:p-6"
                    @click.self="$wire.closeConfirmModal()"
                >
                    <div class="mx-auto mb-4 h-1.5 w-14 rounded-full bg-border sm:hidden"></div>
                    <button type="button" @click="$wire.closeConfirmModal()" data-elevated="false"
                            class="btn-press absolute left-4 top-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors">
                        <x-ui.icon name="x" class="w-4 h-4"/>
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
                        <x-ui.button type="button" wire:click="closeConfirmModal" variant="secondary-outline" icon="x">
                            لغو
                        </x-ui.button>
                        <x-ui.button type="button" wire:click="confirmAdvisorSelection" wire:loading.attr="disabled" wire:target="confirmAdvisorSelection"
                                     variant="primary">
                            <span wire:loading.remove wire:target="confirmAdvisorSelection" class="inline-flex items-center gap-1.5">
                                تایید به عنوان مشاور <x-ui.icon name="check" class="w-4 h-4"/>
                            </span>
                            <span wire:loading wire:target="confirmAdvisorSelection">
                                <x-ui.spinner size="xs"/>
                            </span>
                        </x-ui.button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
