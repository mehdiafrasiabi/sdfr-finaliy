<div dir="rtl" class="min-h-screen bg-background text-foreground">
    @push('link')
        <link rel="stylesheet" href="/client/assets/css/jalalidatepicker.min.css">
        <script src="/client/assets/js/jalalidatepicker.min.js" defer></script>
        <style>
            input[data-jdp] { direction: ltr; text-align: center; letter-spacing: 0.04em; }
            .jdp-container { font-family: inherit !important; z-index: 100 !important; }
        </style>
        <script>
            window.initExamPlanningJalaliDatepicker = window.initExamPlanningJalaliDatepicker || (() => {
                const start = () => {
                    if (typeof jalaliDatepicker === 'undefined') {
                        setTimeout(start, 200);
                        return;
                    }

                    jalaliDatepicker.startWatch({
                        minDate: 'attr',
                        maxDate: 'attr',
                        persianDigits: true,
                        showTodayBtn: false,
                        showEmptyBtn: true,
                        time: false,
                        autoHide: true,
                        zIndex: 100,
                    });
                };

                start();
            });

            document.addEventListener('DOMContentLoaded', window.initExamPlanningJalaliDatepicker);
            document.addEventListener('livewire:navigated', window.initExamPlanningJalaliDatepicker);
            window.addEventListener('exam-planning-scroll-top', () => {
                const container = document.getElementById('exam-planning-builder-top');
                if (container) {
                    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    return;
                }

                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        </script>
    @endpush

    <div id="exam-planning-builder-top" class="max-w-7xl mx-auto px-4 py-6 sm:px-5 sm:py-8">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex items-center gap-2 mb-2 sm:mb-0">
                <div class="w-9 h-9 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2Z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-black">ساخت برنامه امتحانی</h1>
                </div>
            </div>
            @if(!$programAlreadyBuilt && !$usingManagerCalendar && ($calendarReady || $editingCalendar))
                <button type="button"
                        wire:click="openResetCalendarModal"
                        wire:loading.attr="disabled" wire:target="openResetCalendarModal"
                        class="inline-flex w-full sm:w-auto items-center justify-center self-stretch sm:self-start rounded-2xl border border-red-500/20 bg-red-500/10 px-5 py-3 text-sm font-bold text-red-500 disabled:opacity-60">
                    <span wire:loading.remove wire:target="openResetCalendarModal">ریست و شروع از اول</span>
                    <span wire:loading wire:target="openResetCalendarModal" class="inline-block w-4 h-4 rounded-full border-2 border-red-500/30 border-t-red-500 animate-spin"></span>
                </button>
            @endif
        </div>

        @if($programAlreadyBuilt)
            <div class="rounded-3xl border border-emerald-500/20 bg-emerald-500/10 glass p-5 mb-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-500/15 text-emerald-600 flex items-center justify-center font-black">✓</div>
                        <div>
                            <h2 class="font-black text-lg text-emerald-700">برنامه امتحانی ساخته شده است</h2>
                            <p class="text-sm leading-7 text-muted-foreground">
                                برای جلوگیری از تداخل و بازسازی اشتباه، بعد از ساخت برنامه امکان ساخت یا ویرایش مجدد از این صفحه وجود ندارد.
                            </p>
                        </div>
                    </div>
                    @if($builtProgramRoute)
                        <a wire:navigate href="{{ $builtProgramRoute }}"
                           class="inline-flex  items-center justify-center rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-black text-white shadow-sm hover:bg-emerald-600">
                            مشاهده برنامه
                        </a>
                    @endif
                </div>
            </div>
        @elseif($needsCalendarInput)
            <div class="rounded-3xl border border-border bg-card glass p-5 mb-6">
                <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center font-black">1</div>
                        <div>
                            <h2 class="font-black text-lg">ثبت تقویم امتحانات</h2>
                            <p class="text-sm text-muted-foreground mt-2">بازه امتحاناتت را مشخص کن و روی هر روز، درس همان امتحان را اضافه کن. </p>
                        </div>
                    </div>
                </div>

                @if(empty($calendarDays))
                    <div class="grid md:grid-cols-3 gap-3 mb-4 mt-10">
                        <div>
                            <label class="block text-sm font-semibold mb-2">شروع امتحانات</label>
                            <input type="text" wire:model.blur="calendarStart" data-jdp inputmode="none" autocomplete="off" dir="ltr"
                                   data-jdp-min-date="{{ $calendarMinDate }}"
                                   data-jdp-max-date="{{ $calendarMaxDate }}"
                                   readonly
                                   class="w-full rounded-2xl border border-border bg-background px-4 py-3 text-sm cursor-pointer @error('calendarStart') border-red-500/70 @enderror"
                                   placeholder="1405/03/10">
                            @error('calendarStart')
                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">پایان امتحانات</label>
                            <input type="text" wire:model.blur="calendarEnd" data-jdp inputmode="none" autocomplete="off" dir="ltr"
                                   data-jdp-min-date="{{ $calendarMinDate }}"
                                   data-jdp-max-date="{{ $calendarMaxDate }}"
                                   readonly
                                   class="w-full rounded-2xl border border-border bg-background px-4 py-3 text-sm cursor-pointer @error('calendarEnd') border-red-500/70 @enderror"
                                   placeholder="1405/03/28">
                            @error('calendarEnd')
                                <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-end">
                            <button wire:click="saveCalendarRange" wire:loading.attr="disabled" wire:target="saveCalendarRange"
                                    class="w-full rounded-2xl bg-primary px-4 py-3 text-sm font-bold text-primary-foreground disabled:opacity-60 inline-flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="saveCalendarRange">ثبت بازه امتحانات</span>
                                <span wire:loading wire:target="saveCalendarRange" class="inline-block w-4 h-4 rounded-full border-2 border-white/40 border-t-white animate-spin"></span>
                            </button>
                        </div>
                    </div>
                @endif

                @if(!empty($calendarDays))
                    <div class="space-y-4">
                        <div class="flex flex-col gap-3 rounded-2xl border border-amber-500/15 bg-amber-500/10 glass px-4 py-3">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <div class="text-[20px] font-black">هفته {{ $currentCalendarWeek + 1 }} از {{ $calendarWeekCount }}</div>
                                    <div class="text-xs text-muted-foreground mt-1">روی هر روز بزن، درس همان امتحان را از داخل مودال انتخاب و ثبت کن.</div>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    @if($calendarWeekCount > 1)
                                        <button type="button"
                                                wire:click="goToPreviousCalendarWeek"
                                                @disabled($currentCalendarWeek === 0)
                                                class="inline-flex items-center justify-center rounded-2xl border border-border bg-secondary px-4 py-2 text-sm font-bold text-foreground disabled:opacity-40 disabled:cursor-not-allowed">
                                            هفته قبل
                                        </button>
                                        <button type="button"
                                                wire:click="goToNextCalendarWeek"
                                                @disabled($currentCalendarWeek >= $calendarWeekCount - 1)
                                                class="inline-flex items-center justify-center rounded-2xl border border-border bg-secondary px-4 py-2 text-sm font-bold text-foreground disabled:opacity-40 disabled:cursor-not-allowed">
                                            هفته بعد
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="pb-2">
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-7">
                                @foreach($visibleCalendarWeek as $day)
                                    @if($day)
                                        <div wire:key="calendar-day-{{ $day['selection_key'] }}" class="rounded-3xl border border-border bg-background glass p-4  flex flex-col">
                                            <div class="flex items-start justify-between gap-2 mb-4">
                                                <div>
                                                    <div class="text-xs font-bold text-muted-foreground">{{ $day['day_name'] }}</div>
                                                    <div class="mt-1 text-base font-black">{{ $day['jalali'] }}</div>
                                                </div>
                                                <span class="inline-flex h-3 w-3 rounded-full {{ $day['subjects']->isNotEmpty() ? 'bg-emerald-500' : 'bg-border' }}"></span>
                                            </div>

                                            @if($day['subjects']->isNotEmpty())
                                                <div class="mt-auto space-y-2">
                                                    @foreach($day['subjects'] as $examDay)
                                                        <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-3 py-3">
                                                            <div class="flex items-start justify-between gap-2">
                                                                <div>
                                                                    <div class="text-sm font-black leading-6">{{ $examDay->subject->name }}</div>
                                                                </div>
                                                                <button type="button"
                                                                        wire:click="removeExamDay({{ $examDay->id }})"
                                                                        wire:loading.attr="disabled" wire:target="removeExamDay({{ $examDay->id }})"
                                                                        class="inline-flex h-7 w-7 items-center justify-center text-red-500 transition hover:text-red-600 disabled:opacity-60">
                                                                    <svg wire:loading.remove wire:target="removeExamDay({{ $examDay->id }})" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                                        <path d="M18 6 6 18M6 6l12 12"/>
                                                                    </svg>
                                                                    <span wire:loading wire:target="removeExamDay({{ $examDay->id }})" class="inline-block w-3 h-3 rounded-full border-2 border-red-500/30 border-t-red-500 animate-spin"></span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <button type="button"
                                                        wire:click="openExamDayModal('{{ $day['date'] }}', '{{ $day['selection_key'] }}')"
                                                        wire:loading.attr="disabled" wire:target="openExamDayModal('{{ $day['date'] }}', '{{ $day['selection_key'] }}')"
                                                        class="mt-auto flex  w-full flex-col items-center justify-center rounded-2xl border border-dashed border-primary/25 bg-primary/5 px-3 py-4 text-center transition hover:bg-primary/10 disabled:opacity-70">
                                                    <span wire:loading.remove wire:target="openExamDayModal('{{ $day['date'] }}', '{{ $day['selection_key'] }}')" class="flex flex-col items-center justify-center">
                                                        <span class="text-sm font-black text-primary">انتخاب درس</span>
                                                    </span>
                                                    <span wire:loading wire:target="openExamDayModal('{{ $day['date'] }}', '{{ $day['selection_key'] }}')" class="inline-flex items-center gap-2 text-primary">
                                                        <span class="inline-block w-4 h-4 rounded-full border-2 border-current/30 border-t-current animate-spin"></span>
                                                    </span>
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class=" rounded-3xl border border-dashed border-border/70 bg-transparent"></div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        @if($activeExamRows->isNotEmpty())
                            @php($canFinalizeCalendar = $activeExamRows->count() >= 3)
                            <div class="p-4 sm:p-5">
                                @if(!$calendarFinalized && !$usingManagerCalendar)
                                    <div class="flex flex-col items-start gap-3 sm:items-end">
                                        @unless($canFinalizeCalendar)
                                            <p class="text-sm leading-7 text-amber-700">
                                                برای ثبت نهایی تقویم، حداقل باید 3 امتحان ثبت شده باشد.
                                            </p>
                                        @endunless
                                        <button type="button"
                                                wire:click="openFinalizeCalendarModal"
                                                wire:loading.attr="disabled" wire:target="openFinalizeCalendarModal"
                                                @disabled(!$canFinalizeCalendar)
                                                class="inline-flex items-center justify-center rounded-2xl bg-primary px-5 py-3 text-sm font-black text-primary-foreground shadow-sm hover:opacity-90 disabled:opacity-40 disabled:cursor-not-allowed">
                                            <span wire:loading.remove wire:target="openFinalizeCalendarModal">{{ $editingCalendar ? 'ذخیره' : 'ثبت نهایی تقویم امتحانات' }}</span>
                                            <span wire:loading wire:target="openFinalizeCalendarModal" class="inline-block w-4 h-4 rounded-full border-2 border-white/40 border-t-white animate-spin"></span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        @if(!$programAlreadyBuilt && $calendarReady)
            <div class="rounded-3xl border border-border bg-card glass p-5 mb-6">
                <div class="flex flex-col gap-3 mb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center font-black">2</div>
                        <div>
                            <h2 class="font-black text-lg">ساعت‌ مطالعه هر درس</h2>
                        </div>
                    </div>
                    @if($calendarFinalized && !$usingManagerCalendar)
                        <button type="button"
                                wire:click="reopenCalendarForEditing"
                                wire:loading.attr="disabled" wire:target="reopenCalendarForEditing"
                                class="inline-flex items-center justify-center rounded-2xl border border-amber-500/20 bg-amber-500/10 px-4 py-2.5 text-sm font-bold text-amber-600 disabled:opacity-60">
                            <span wire:loading.remove wire:target="reopenCalendarForEditing">بازگشت برای ویرایش امتحانات</span>
                            <span wire:loading wire:target="reopenCalendarForEditing" class="inline-block w-4 h-4 rounded-full border-2 border-amber-500/30 border-t-amber-500 animate-spin"></span>
                        </button>
                    @endif
                </div>

                @if($segmentWarnings->isNotEmpty())
                    <div class="rounded-2xl border border-red-500/20 bg-red-500/10 glass px-4 py-3 text-sm text-red-600 mb-4">
                        جمع ساعت‌های واردشده در یکی از بازه‌های امتحانی از ظرفیت مجاز بیشتر شده است. قبل از ساخت برنامه، مقدارها را متعادل کن.
                    </div>
                @endif

                @if(!empty($missingStudySubjects))
                    <div class="rounded-2xl border border-amber-500/20 bg-amber-500/10 glass px-4 py-3 text-sm text-amber-700 mb-4">
                        <div class="font-black">این درس‌ها هنوز ساعت مطالعه ندارند:</div>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($missingStudySubjects as $missingSubject)
                                <span class="inline-flex rounded-xl border border-amber-500/20 bg-background/60 px-3 py-1 text-xs font-bold text-amber-700">
                                    {{ $missingSubject }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif


                <div x-data="{ activeType: @entangle('activeType'), examsModal: false }"
                     x-effect="document.body.classList.toggle('overflow-hidden', examsModal)">
                    <div class="flex gap-2 mb-5">
                        <button type="button" @click="activeType = 'specialized'"
                                :class="activeType === 'specialized' ? 'bg-primary text-primary-foreground border-primary' : 'bg-secondary text-muted-foreground border-border'"
                                class="px-4 py-2 rounded-2xl border text-sm font-bold transition">
                            تخصصی
                        </button>
                        <button type="button" @click="activeType = 'general'"
                                :class="activeType === 'general' ? 'bg-primary text-primary-foreground border-primary' : 'bg-secondary text-muted-foreground border-border'"
                                class="px-4 py-2 rounded-2xl border text-sm font-bold transition">
                            عمومی
                        </button>
                    </div>

                    <div x-show="activeType === 'specialized'" x-cloak>
                        <?php $specializedSubjects = $curriculum['specialized_subjects'] ?? []; ?>
                        @if(count($specializedSubjects) > 0)
                            <div class="flex gap-2 overflow-x-auto pb-3 mb-4">
                                @foreach($specializedSubjects as $tabIndex => $tabSubject)
                                    <button type="button"
                                            wire:click="$set('activeSubject', {{ $tabIndex }})"
                                            class="whitespace-nowrap rounded-2xl border px-4 py-2 text-sm font-bold transition {{ $activeSubject === $tabIndex ? 'bg-primary text-primary-foreground border-primary' : 'bg-secondary text-muted-foreground border-border' }}">
                                        {{ $tabSubject['name'] }}
                                    </button>
                                @endforeach
                            </div>

                            @foreach($specializedSubjects as $subjectIndex => $subject)
                                <?php
                                    $subjectData = $subjectMeta[$subject['id']] ?? null;
                                    $scheduled = $subjectData['is_scheduled'] ?? false;
                                    $hasChapters = !empty($subject['chapters']);
                                    $subjectIsHidden = $activeSubject !== $subjectIndex;
                                    $priorityDisabled = !$scheduled || !$hasChapters;
                                    $incrementDisabled = !$scheduled || !($subjectData['can_add'] ?? false);
                                ?>
                                <div class="{{ $subjectIsHidden ? 'hidden' : '' }}">
                                    <div class="rounded-3xl border border-border border-blue-600/15 bg-blue-600/10 glass p-4 mb-4">
                                        <div class="flex items-start justify-between gap-3 mb-3 flex-wrap">
                                            <div>
                                                <h3 class="font-black text-lg mb-2">{{ $subject['name'] }}
                                                    <span class="text-primary" style="margin-right: 5px;">
                                                     ( {{$subjectData['exam_date_label'] }} )
                                                </span>
                                                </h3>
                                                @if($scheduled)
                                                    <p class="text-xs text-muted">
                                                         ظرفیت تا امتحان: {{ $subjectData['capacity_label'] }}
                                                    </p>
                                                @else
                                                    <p class="text-xs text-amber-600">برای این درس هنوز تاریخ امتحان ثبت نشده است.</p>
                                                @endif
                                            </div>
                                        </div>

                                        @if($scheduled)
                                            <div class="mb-4">
                                                <div class="flex items-center justify-end text-xs mb-2">
                                                    <span class="text-primary">{{ $subjectData['entered_label'] }}</span>
                                                </div>
                                                <div class="h-2 rounded-full bg-secondary overflow-hidden">
                                                    <div class="h-full rounded-full bg-primary transition-all" style="width: {{ $subjectData['progress'] }}%"></div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="rounded-2xl border border-border glass px-3 py-3">
                                            <button type="button"
                                                    wire:click="togglePriority({{ $subject['id'] }})"
                                                    {{ $priorityDisabled ? 'disabled' : '' }}
                                                    class="inline-flex w-full items-center justify-center rounded-xl border px-3 py-2.5 text-xs font-bold transition {{ ($prioritySubjects[$subject['id']] ?? false) ? 'border-red-500/30 bg-red-500/10 text-red-500' : 'border-border bg-secondary text-muted-foreground' }}">
                                                <span>اره،میترسم</span>
                                            </button>
                                            <p class="mt-3 text-xs leading-6 text-muted-foreground">
                                                اگر نگران این درس هستی و استرس شب امتحانش رو داری روی دکمه بزن
                                            </p>
                                        </div>
                                        <div class="space-y-2">
                                            @if(!empty($subject['chapters']))
                                                @foreach($subject['chapters'] as $chapter)
                                                    <?php
                                                        $key = 'chapter_' . $chapter['id'];
                                                        $minutes = $allocations[$key] ?? 0;
                                                    ?>
                                                    <div class="rounded-2xl border {{ $minutes <= 0 ? 'border-red-500/30 bg-red-500/5' : 'border-border' }} glass px-3 py-3 flex items-center justify-between gap-3">
                                                        <span class="text-sm">{{ $chapter['name'] }}</span>
                                                        <div class="flex flex-col items-end gap-1">
                                                            <div class="flex items-center gap-2">
                                                            <button type="button"
                                                                    wire:click="decrementAllocation('chapter', {{ $chapter['id'] }}, {{ $subject['id'] }})"
                                                                    wire:loading.attr="disabled" wire:target="decrementAllocation('chapter', {{ $chapter['id'] }}, {{ $subject['id'] }})"
                                                                    {{ (!$scheduled || $minutes <= 30) ? 'disabled' : '' }}
                                                                    class="w-9 h-9 rounded-xl border border-border bg-secondary text-lg font-bold disabled:opacity-40">-</button>
                                                            <div class="min-w-[92px] text-center rounded-xl bg-secondary px-3 py-2 text-sm font-bold">
                                                                {{ intdiv($minutes, 60) }}:{{ str_pad((string) ($minutes % 60), 2, '0', STR_PAD_LEFT) }}
                                                            </div>
                                                            <button type="button"
                                                                    wire:click="incrementAllocation('chapter', {{ $chapter['id'] }}, {{ $subject['id'] }})"
                                                                    wire:loading.attr="disabled" wire:target="incrementAllocation('chapter', {{ $chapter['id'] }}, {{ $subject['id'] }})"
                                                                    {{ $incrementDisabled ? 'disabled' : '' }}
                                                                    class="w-9 h-9 rounded-xl border border-border bg-secondary text-lg font-bold disabled:opacity-40">+</button>
                                                            </div>
                                                            @if($minutes <= 0)
                                                                <p class="text-xs font-semibold text-red-500 text-left">حداقل ۳۰ دقیقه ثبت کن.</p>
                                                            @endif
                                                            @error('allocations.' . $key)
                                                                <p class="text-xs font-semibold text-red-500 text-left">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="rounded-2xl border border-dashed border-border glass px-3 py-4 text-sm text-muted-foreground">
                                                    برای این درس فصل فعالی پیدا نشد.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="rounded-2xl border border-dashed border-border glass px-4 py-8 text-center text-sm text-muted-foreground">
                                درس تخصصی ثبت‌شده‌ای در تقویم امتحانات پیدا نشد.
                            </div>
                        @endif
                    </div>

                    <div x-show="activeType === 'general'" x-cloak>
                        <?php
                            $generalSubjects = $curriculum['general_subjects'] ?? [];
                            $generalActiveSubject = count($generalSubjects) > 0
                                ? min($activeSubject, count($generalSubjects) - 1)
                                : 0;
                        ?>
                        @if(count($generalSubjects) > 0)
                            <div class="flex gap-2 overflow-x-auto pb-3 mb-4">
                                @foreach($generalSubjects as $tabIndex => $tabSubject)
                                    <button type="button"
                                            wire:click="$set('activeSubject', {{ $tabIndex }})"
                                            class="whitespace-nowrap rounded-2xl border px-4 py-2 text-sm font-bold transition {{ $generalActiveSubject === $tabIndex ? 'bg-primary text-primary-foreground border-primary' : 'bg-secondary text-muted-foreground border-border' }}">
                                        {{ $tabSubject['name'] }}
                                    </button>
                                @endforeach
                            </div>

                            @foreach($generalSubjects as $subjectIndex => $subject)
                                <?php
                                    $subjectData = $subjectMeta[$subject['id']] ?? null;
                                    $scheduled = $subjectData['is_scheduled'] ?? false;
                                    $hasChapters = !empty($subject['chapters']);
                                    $subjectIsHidden = $generalActiveSubject !== $subjectIndex;
                                    $priorityDisabled = !$scheduled || !$hasChapters;
                                    $incrementDisabled = !$scheduled || !($subjectData['can_add'] ?? false);
                                    $generalMode = $generalStudyModes[$subject['id']] ?? null;
                                    $hasWholeAllocation = (int) ($allocations['subject_' . $subject['id']] ?? 0) > 0;
                                    $hasChapterAllocation = collect($subject['chapters'] ?? [])
                                        ->contains(fn (array $chapter) => (int) ($allocations['chapter_' . $chapter['id']] ?? 0) > 0);

                                    if (! $generalMode) {
                                        $generalMode = $hasWholeAllocation ? 'whole' : ($hasChapterAllocation ? 'chapter' : null);
                                    }
                                ?>
                                <div class="{{ $subjectIsHidden ? 'hidden' : '' }}">
                                    <div class="rounded-3xl border border-border border-blue-600/15 bg-blue-600/10 glass p-4 mb-4">
                                        <div class="flex items-start justify-between gap-3 mb-3 flex-wrap">
                                            <div>
                                                <h3 class="font-black text-lg mb-2">{{ $subject['name'] }}
                                                <span class="text-primary" style="margin-right: 5px">
                                                     ( {{ $subjectData['exam_date_label'] }} )
                                                </span>
                                                </h3>

                                                @if($scheduled)
                                                    <p class="text-xs text-muted">
                                                         ظرفیت تا امتحان: {{ $subjectData['capacity_label'] }}
                                                    </p>
                                                @else
                                                    <p class="text-xs text-amber-600">برای این درس هنوز تاریخ امتحان ثبت نشده است.</p>
                                                @endif
                                            </div>
                                        </div>

                                        @if($scheduled)
                                            <div class="mb-4">
                                                <div class="flex items-center justify-end text-xs mb-2">
                                                    <span class="text-primary">{{ $subjectData['entered_label'] }}</span>
                                                </div>
                                                <div class="h-2 rounded-full bg-secondary overflow-hidden">
                                                    <div class="h-full rounded-full bg-primary transition-all" style="width: {{ $subjectData['progress'] }}%"></div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="rounded-2xl border border-border glass px-3 py-3 mb-3">
                                            <button type="button"
                                                    wire:click="togglePriority({{ $subject['id'] }})"
                                                    {{ $priorityDisabled ? 'disabled' : '' }}
                                                    class="inline-flex w-full items-center justify-center rounded-xl border px-3 py-2.5 text-xs font-bold transition {{ ($prioritySubjects[$subject['id']] ?? false) ? 'border-red-500/30 bg-red-500/10 text-red-500' : 'border-border bg-secondary text-muted-foreground' }}">
                                                <span>اره،میترسم</span>
                                            </button>
                                            <p class="mt-3 text-xs leading-6 text-muted-foreground">
                                                اگر نگران این درس هستی و استرس شب امتحانش رو داری روی دکمه بزن
                                            </p>
                                        </div>

                                        <div class="rounded-2xl border border-border glass px-3 py-3 mb-3">
                                            <div class="grid grid-cols-2 gap-2">
                                                <button type="button"
                                                        wire:click="setGeneralStudyChapterMode({{ $subject['id'] }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="setGeneralStudyChapterMode({{ $subject['id'] }})"
                                                        {{ ($scheduled && $hasChapters) ? '' : 'disabled' }}
                                                        class="inline-flex items-center justify-center rounded-xl border px-3 py-2.5 text-xs font-bold transition {{ $generalMode === 'chapter' ? 'border-primary bg-primary/10 text-primary' : 'border-border bg-secondary text-muted-foreground' }}">
                                                    <span wire:loading.remove wire:target="setGeneralStudyChapterMode({{ $subject['id'] }})">فصل به فصل</span>
                                                    <span wire:loading wire:target="setGeneralStudyChapterMode({{ $subject['id'] }})" class="inline-block w-3.5 h-3.5 rounded-full border-2 border-current/30 border-t-current animate-spin"></span>
                                                </button>
                                                <button type="button"
                                                        wire:click="openGeneralWholeModal({{ $subject['id'] }})"
                                                        wire:loading.attr="disabled"
                                                        wire:target="openGeneralWholeModal({{ $subject['id'] }})"
                                                        {{ $scheduled ? '' : 'disabled' }}
                                                        class="inline-flex items-center justify-center rounded-xl border px-3 py-2.5 text-xs font-bold transition {{ $generalMode === 'whole' ? 'border-blue-500/30 bg-blue-500/10 text-blue-500' : 'border-border bg-secondary text-muted-foreground' }}">
                                                    <span wire:loading.remove wire:target="openGeneralWholeModal({{ $subject['id'] }})">کلی</span>
                                                    <span wire:loading wire:target="openGeneralWholeModal({{ $subject['id'] }})" class="inline-block w-3.5 h-3.5 rounded-full border-2 border-current/30 border-t-current animate-spin"></span>
                                                </button>
                                            </div>
                                            @if(!$generalMode)
                                                <p class="mt-3 text-xs text-muted-foreground">اول یکی از دو حالت را انتخاب کن.</p>
                                            @endif
                                        </div>

                                        @if($generalMode === 'whole')
                                            <?php
                                                $key = 'subject_' . $subject['id'];
                                                $minutes = $allocations[$key] ?? 0;
                                            ?>
                                            <div class="rounded-2xl border {{ $minutes <= 0 ? 'border-red-500/30 bg-red-500/5' : 'border-border' }} glass px-3 py-3 flex items-center justify-between gap-3">
                                                <span class="text-sm font-bold">کل درس</span>
                                                <div class="flex flex-col items-end gap-1">
                                                    <div class="flex items-center gap-2">
                                                        <button type="button"
                                                                wire:click="decrementAllocation('subject', {{ $subject['id'] }}, {{ $subject['id'] }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="decrementAllocation('subject', {{ $subject['id'] }}, {{ $subject['id'] }})"
                                                                {{ (!$scheduled || $minutes <= 0) ? 'disabled' : '' }}
                                                                class="w-9 h-9 rounded-xl border border-border bg-secondary text-lg font-bold disabled:opacity-40">-</button>
                                                        <div class="min-w-[92px] text-center rounded-xl bg-secondary px-3 py-2 text-sm font-bold">
                                                            {{ intdiv($minutes, 60) }}:{{ str_pad((string) ($minutes % 60), 2, '0', STR_PAD_LEFT) }}
                                                        </div>
                                                        <button type="button"
                                                                wire:click="incrementAllocation('subject', {{ $subject['id'] }}, {{ $subject['id'] }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="incrementAllocation('subject', {{ $subject['id'] }}, {{ $subject['id'] }})"
                                                                {{ (!$scheduled || $incrementDisabled) ? 'disabled' : '' }}
                                                                class="w-9 h-9 rounded-xl border border-border bg-secondary text-lg font-bold disabled:opacity-40">+</button>
                                                    </div>
                                                    @if($minutes <= 0)
                                                        <p class="text-xs font-semibold text-red-500 text-left">حداقل ۳۰ دقیقه ثبت کن.</p>
                                                    @endif
                                                    @error('allocations.' . $key)
                                                        <p class="text-xs font-semibold text-red-500 text-left">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        @elseif($generalMode === 'chapter')
                                            <div class="space-y-2">
                                                @forelse($subject['chapters'] as $chapter)
                                                    <?php
                                                        $key = 'chapter_' . $chapter['id'];
                                                        $minutes = $allocations[$key] ?? 0;
                                                    ?>
                                                    <div class="rounded-2xl border {{ $minutes <= 0 ? 'border-red-500/30 bg-red-500/5' : 'border-border' }} glass px-3 py-3 flex items-center justify-between gap-3">
                                                        <span class="text-sm">{{ $chapter['name'] }}</span>
                                                        <div class="flex flex-col items-end gap-1">
                                                            <div class="flex items-center gap-2">
                                                                <button type="button"
                                                                        wire:click="decrementAllocation('chapter', {{ $chapter['id'] }}, {{ $subject['id'] }})"
                                                                        wire:loading.attr="disabled"
                                                                        wire:target="decrementAllocation('chapter', {{ $chapter['id'] }}, {{ $subject['id'] }})"
                                                                        @if(!$scheduled || $minutes <= 30) disabled @endif
                                                                        class="w-9 h-9 rounded-xl border border-border bg-secondary text-lg font-bold disabled:opacity-40">-</button>
                                                                <div class="min-w-[92px] text-center rounded-xl bg-secondary px-3 py-2 text-sm font-bold">
                                                                    {{ intdiv($minutes, 60) }}:{{ str_pad((string) ($minutes % 60), 2, '0', STR_PAD_LEFT) }}
                                                                </div>
                                                                <button type="button"
                                                                        wire:click="incrementAllocation('chapter', {{ $chapter['id'] }}, {{ $subject['id'] }})"
                                                                        wire:loading.attr="disabled"
                                                                        wire:target="incrementAllocation('chapter', {{ $chapter['id'] }}, {{ $subject['id'] }})"
                                                                        {{ $incrementDisabled ? 'disabled' : '' }}
                                                                        class="w-9 h-9 rounded-xl border border-border bg-secondary text-lg font-bold disabled:opacity-40">+</button>
                                                            </div>
                                                            @if($minutes <= 0)
                                                                <p class="text-xs font-semibold text-red-500 text-left">حداقل ۳۰ دقیقه ثبت کن.</p>
                                                            @endif
                                                            @error('allocations.' . $key)
                                                                <p class="text-xs font-semibold text-red-500 text-left">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="rounded-2xl border border-dashed border-border glass px-3 py-4 text-sm text-muted-foreground">
                                                        برای این درس فصل فعالی پیدا نشد.
                                                    </div>
                                                @endforelse
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="rounded-2xl border border-dashed border-border glass px-4 py-8 text-center text-sm text-muted-foreground">
                                درس عمومی ثبت‌شده‌ای در تقویم امتحانات پیدا نشد.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-end gap-2">
                @error('buildProgram')
                    <p class="w-full rounded-2xl border border-red-500/20 bg-red-500/10 glass px-4 py-3 text-sm font-semibold text-red-500">
                        {{ $message }}
                    </p>
                @enderror
                <button wire:click="buildProgram"
                        wire:loading.attr="disabled" wire:target="buildProgram"
                        @disabled(!$canBuildProgram)
                        class="inline-flex w-full sm:w-auto items-center justify-center self-stretch sm:self-end rounded-2xl bg-emerald-500 px-5 py-3 text-sm font-black text-white shadow-sm hover:bg-emerald-600 disabled:opacity-40 disabled:cursor-not-allowed gap-2">
                    <span wire:loading.remove wire:target="buildProgram">ساخت برنامه</span>
                    <span wire:loading wire:target="buildProgram" class="inline-block w-4 h-4 rounded-full border-2 border-white/40 border-t-white animate-spin"></span>
                </button>
            </div>
        @endif
    </div>

    @if($showExamDayModal)
        <div x-data="{ examDayOpen: @entangle('showExamDayModal') }"
             x-effect="document.body.classList.toggle('overflow-hidden', examDayOpen)"
             x-show="examDayOpen" x-cloak
             class="fixed inset-0 z-[135] flex items-end justify-center overflow-hidden overscroll-none md:items-center md:p-4"
             @keydown.escape.window="$wire.closeExamDayModal()">

            <div class="absolute inset-0 bg-black/65 backdrop-blur-sm"
                 x-show="examDayOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="$wire.closeExamDayModal()"></div>

            <div class="relative z-10 flex max-h-[88svh] w-full flex-col overflow-hidden rounded-t-3xl border border-border bg-background glass shadow-2xl pb-[env(safe-area-inset-bottom,0px)] md:max-h-[80vh] md:max-w-2xl md:rounded-3xl md:pb-0"
                 x-show="examDayOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-full md:translate-y-4 md:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 md:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 md:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-full md:translate-y-4 md:scale-95"
                 dir="rtl">

                <div class="flex justify-center pt-3 pb-1 md:hidden">
                    <div class="w-12 h-1 rounded-full bg-foreground/20"></div>
                </div>

                <div class="p-5 border-b border-border">
                    <h3 class="font-black text-lg">انتخاب درس امتحان</h3>
                    <p class="mt-2 text-sm leading-7 text-muted-foreground">
                        @if($selectedExamDayDate)
                            {{ jdate($selectedExamDayDate)->format('l Y/m/d') }}
                        @endif
                        <span class="block mt-1">یکی از درس‌های در دسترس را انتخاب کن و بعد دکمه ثبت را بزن.</span>
                    </p>
                </div>

                <div class="flex-1 overflow-y-auto px-5 py-4 overscroll-contain">
                    @php($examDayModalOptions = $selectedExamDaySelectionKey ? ($subjectOptionsByDay[$selectedExamDaySelectionKey] ?? []) : [])

                    @if(count($examDayModalOptions) > 0)
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach($examDayModalOptions as $subject)
                                @php($isSelected = $selectedExamDaySubjectId === (int) $subject['id'])
                                <button type="button"
                                        wire:click="$set('selectedExamDaySubjectId', {{ $subject['id'] }})"
                                        class="rounded-2xl border px-4 py-4 text-right transition {{ $isSelected ? 'border-primary bg-primary/10 shadow-sm shadow-primary/10' : 'border-border bg-background hover:border-primary/30 hover:bg-primary/5' }}">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <div class="text-sm font-black">{{ $subject['name'] }}</div>
                                            <div class="mt-1 text-xs text-muted-foreground">{{ $subject['type'] === 'general' ? 'عمومی' : 'تخصصی' }}</div>
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-border glass px-4 py-6 text-center text-sm text-muted-foreground">
                            برای این روز، درس آزاد دیگری باقی نمانده است.
                        </div>
                    @endif

                    @error('selectedExamDaySubjectId')
                        <p class="mt-3 text-sm font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-3 border-t border-border p-4">
                    <button type="button"
                            wire:click="closeExamDayModal"
                            wire:loading.attr="disabled"
                            wire:target="closeExamDayModal,saveExamDayFromModal"
                            class="w-full rounded-2xl border border-border px-4 py-3 text-sm font-bold text-foreground hover:bg-secondary/70 disabled:opacity-60">
                        انصراف
                    </button>
                    <button type="button"
                            wire:click="saveExamDayFromModal"
                            wire:loading.attr="disabled"
                            wire:target="saveExamDayFromModal"
                            class="w-full rounded-2xl bg-primary px-4 py-3 text-sm font-black text-primary-foreground disabled:opacity-60 inline-flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="saveExamDayFromModal">ثبت درس</span>
                        <span wire:loading wire:target="saveExamDayFromModal" class="inline-block w-4 h-4 rounded-full border-2 border-white/40 border-t-white animate-spin"></span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showGeneralWholeModal)
        <div x-data="{ wholeOpen: @entangle('showGeneralWholeModal') }"
             x-effect="document.body.classList.toggle('overflow-hidden', wholeOpen)"
             x-show="wholeOpen" x-cloak
             class="fixed inset-0 z-[132] flex items-end justify-center overflow-hidden overscroll-none md:items-center md:p-4"
             @keydown.escape.window="$wire.closeGeneralWholeModal()">

            <div class="absolute inset-0 bg-black/65 backdrop-blur-sm"
                 x-show="wholeOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="$wire.closeGeneralWholeModal()"></div>

            <div class="relative z-10 flex max-h-[88svh] w-full flex-col overflow-hidden rounded-t-3xl border border-border bg-background glass shadow-2xl pb-[env(safe-area-inset-bottom,0px)] md:max-h-[80vh] md:max-w-md md:rounded-3xl md:pb-0"
                 x-show="wholeOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-full md:translate-y-4 md:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 md:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 md:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-full md:translate-y-4 md:scale-95"
                 dir="rtl">

                <div class="flex justify-center pt-3 pb-1 md:hidden">
                    <div class="w-12 h-1 rounded-full bg-foreground/20"></div>
                </div>

                @php($selectedGeneralWholeSubject = collect($curriculum['general_subjects'] ?? [])->firstWhere('id', $selectedGeneralWholeSubjectId))

                <div class="p-5 border-b border-border">
                    <h3 class="font-black text-lg">ساعت مطالعه کلی</h3>
                    <p class="mt-2 text-sm leading-7 text-muted-foreground">
                        {{ $selectedGeneralWholeSubject['name'] ?? 'درس انتخاب‌شده' }}
                    </p>
                </div>

                <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4 overscroll-contain">
                    <div class="rounded-2xl border border-border bg-secondary/70 glass px-4 py-4">
                        <div class="flex items-center justify-between gap-3">
                            <button type="button"
                                    wire:click="decrementGeneralWholeMinutes"
                                    wire:loading.attr="disabled"
                                    wire:target="decrementGeneralWholeMinutes"
                                    class="w-10 h-10 rounded-xl border border-border bg-background text-lg font-black disabled:opacity-40">-</button>
                            <div class="min-w-[120px] text-center rounded-xl bg-background px-4 py-3 text-base font-black">
                                {{ intdiv($selectedGeneralWholeMinutes, 60) }}:{{ str_pad((string) ($selectedGeneralWholeMinutes % 60), 2, '0', STR_PAD_LEFT) }}
                            </div>
                            <button type="button"
                                    wire:click="incrementGeneralWholeMinutes"
                                    wire:loading.attr="disabled"
                                    wire:target="incrementGeneralWholeMinutes"
                                    class="w-10 h-10 rounded-xl border border-border bg-background text-lg font-black disabled:opacity-40">+</button>
                        </div>
                    </div>

                    @error('selectedGeneralWholeMinutes')
                        <p class="text-sm font-semibold text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-3 border-t border-border p-4">
                    <button type="button"
                            wire:click="closeGeneralWholeModal"
                            wire:loading.attr="disabled"
                            wire:target="closeGeneralWholeModal,saveGeneralWholeMode"
                            class="w-full rounded-2xl border border-border px-4 py-3 text-sm font-bold text-foreground hover:bg-secondary/70 disabled:opacity-60">
                        بستن
                    </button>
                    <button type="button"
                            wire:click="saveGeneralWholeMode"
                            wire:loading.attr="disabled"
                            wire:target="saveGeneralWholeMode"
                            class="w-full rounded-2xl bg-primary px-4 py-3 text-sm font-black text-primary-foreground disabled:opacity-60 inline-flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="saveGeneralWholeMode">ثبت</span>
                        <span wire:loading wire:target="saveGeneralWholeMode" class="inline-block w-4 h-4 rounded-full border-2 border-white/40 border-t-white animate-spin"></span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showResetCalendarModal)
        <div x-data="{ resetOpen: @entangle('showResetCalendarModal') }"
             x-effect="document.body.classList.toggle('overflow-hidden', resetOpen)"
             x-show="resetOpen" x-cloak
             class="fixed inset-0 z-[140] flex items-end justify-center overflow-hidden overscroll-none md:items-center md:p-4"
             @keydown.escape.window="$wire.closeResetCalendarModal()">

            <div class="absolute inset-0 bg-black/65 backdrop-blur-sm"
                 x-show="resetOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="$wire.closeResetCalendarModal()"></div>

            <div class="relative z-10 flex max-h-[88svh] w-full flex-col overflow-hidden rounded-t-3xl border border-border bg-background glass shadow-2xl pb-[env(safe-area-inset-bottom,0px)] md:max-h-[80vh] md:max-w-md md:rounded-3xl md:pb-0"
                 x-show="resetOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-full md:translate-y-4 md:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 md:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 md:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-full md:translate-y-4 md:scale-95"
                 dir="rtl">

                <div class="flex justify-center pt-3 pb-1 md:hidden">
                    <div class="w-12 h-1 rounded-full bg-foreground/20"></div>
                </div>

                <div class="p-5 border-b border-border">
                    <h3 class="font-black text-lg">تأیید ریست</h3>
                    <p class="mt-2 text-sm leading-7 text-muted-foreground">
                        مطمئنی می‌خواهی همه اطلاعات این صفحه ریست شود و دوباره از اول شروع کنی؟
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 border-t border-border p-4">
                    <button type="button"
                            wire:click="closeResetCalendarModal"
                            wire:loading.attr="disabled"
                            wire:target="closeResetCalendarModal,resetCalendarBuilder"
                            class="w-full rounded-2xl border border-border px-4 py-3 text-sm font-bold text-foreground hover:bg-secondary/70 disabled:opacity-60">
                        خیر
                    </button>
                    <button type="button"
                            wire:click="resetCalendarBuilder"
                            wire:loading.attr="disabled"
                            wire:target="resetCalendarBuilder"
                            class="w-full rounded-2xl bg-red-500 px-4 py-3 text-sm font-black text-white disabled:opacity-60 inline-flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="resetCalendarBuilder">بله، ریست کن</span>
                        <span wire:loading wire:target="resetCalendarBuilder" class="inline-block w-4 h-4 rounded-full border-2 border-white/40 border-t-white animate-spin"></span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showFinalizeCalendarModal)
        <div x-data="{ finalizeOpen: @entangle('showFinalizeCalendarModal') }"
             x-effect="document.body.classList.toggle('overflow-hidden', finalizeOpen)"
             x-show="finalizeOpen" x-cloak
             class="fixed inset-0 z-[130] flex items-end justify-center overflow-hidden overscroll-none md:items-center md:p-4"
             @keydown.escape.window="$wire.closeFinalizeCalendarModal()">

            <div class="absolute inset-0 bg-black/65 backdrop-blur-sm"
                 x-show="finalizeOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="$wire.closeFinalizeCalendarModal()"></div>

            <div class="relative z-10 flex max-h-[88svh] w-full flex-col overflow-hidden rounded-t-3xl border border-border bg-background glass shadow-2xl pb-[env(safe-area-inset-bottom,0px)] md:max-h-[80vh] md:max-w-lg md:rounded-3xl md:pb-0"
                 x-show="finalizeOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-full md:translate-y-4 md:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 md:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 md:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-full md:translate-y-4 md:scale-95"
                 dir="rtl">

                <div class="flex justify-center pt-3 pb-1 md:hidden">
                    <div class="w-12 h-1 rounded-full bg-foreground/20"></div>
                </div>

                <div class="p-5 border-b border-border">
                    <h3 class="font-black text-lg">تایید نهایی تقویم امتحانات</h3>
                    <p class="mt-2 text-sm leading-7 text-muted-foreground">
                        این برنامه‌ای است که تا الان ثبت کرده‌ای. اگر آماده‌ای، ثبت نهایی بزن تا وارد مرحله ساعت‌دهی شوی.
                    </p>
                    <p class="mt-1 text-xs leading-6 text-muted-foreground">
                        روزهای خالیِ بعد از آخرین امتحان هم در همین مرحله خودکار حذف می‌شوند و دیگر در محاسبات نمی‌آیند.
                    </p>
                </div>

                <div class="flex-1 overflow-y-auto px-5 py-4 space-y-3 overscroll-contain">
                    @foreach($activeExamRows as $row)
                        <div class="rounded-2xl border border-border bg-secondary/70 glass px-4 py-3">
                            <div class="font-bold text-sm">{{ $row->subject->name }}</div>
                            <div class="mt-1 text-xs text-muted-foreground">{{ jdate($row->exam_date)->format('l Y/m/d') }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-2 gap-3 border-t border-border p-4">
                    <button wire:click="closeFinalizeCalendarModal"
                            wire:loading.attr="disabled"
                            wire:target="closeFinalizeCalendarModal,finalizeCalendar"
                            class="w-full rounded-2xl border border-border px-4 py-3 text-sm font-bold text-foreground hover:bg-secondary/70 disabled:opacity-60">
                        لغو
                    </button>
                    <button wire:click="finalizeCalendar"
                            wire:loading.attr="disabled"
                            wire:target="finalizeCalendar"
                            class="w-full rounded-2xl bg-primary px-4 py-3 text-sm font-black text-primary-foreground disabled:opacity-60 inline-flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="finalizeCalendar">ثبت نهایی</span>
                        <span wire:loading wire:target="finalizeCalendar" class="inline-block w-4 h-4 rounded-full border-2 border-white/40 border-t-white animate-spin"></span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
