<div dir="rtl" data-weekly-program-view>
    @assets
    <style>
        @font-face { font-family: 'Digital'; src: url('/client/assets/fonts/digital-7.ttf') format('truetype'); }
        [x-cloak] { display: none !important; }
        .ss-scroll::-webkit-scrollbar { display: none; }
        .ss-scroll { -ms-overflow-style: none; scrollbar-width: none; }
        .timer-ring { transform: rotate(-90deg); transform-origin: center; transition: stroke-dashoffset .4s linear; }
        .drop-ring { transition: stroke-dashoffset .6s ease; }
        {{-- .spinner-circle/.spinner-sm حذف شد؛ همه‌ی نشانگرهای بارگذاری این فایل به x-ui.spinner یکپارچه شدند. --}}

        /* ✅ هایلایت پارت مقصد هنگام ورود از دکمه «شروع و ثبت ساعت مطالعه» */
        .focus-part-highlight {
            animation: focusPartPulse 1.4s cubic-bezier(.22,1,.36,1) 2;
            position: relative;
        }
        @keyframes focusPartPulse {
            0%   { box-shadow: 0 0 0 0 rgba(245,158,11,.55); }
            40%  { box-shadow: 0 0 0 10px rgba(245,158,11,0); }
            100% { box-shadow: 0 0 0 0 rgba(245,158,11,0); }
        }
        @media (prefers-reduced-motion: reduce) {
            .focus-part-highlight { animation: focusPartFade 1.8s ease-in-out 1; }
        }
        @keyframes focusPartFade {
            0%, 100% { background-color: transparent; }
            50% { background-color: rgba(245,158,11,.14); }
        }
    </style>
    @endassets

    @php
        $timerActive  = $currentPartId || $makeupTimerRunning || $makeupPausedAtTs;
        $isMakeupMode = $makeupTimerRunning || $makeupPausedAtTs;
        $activePart   = $currentPartId ? collect($programParts)->firstWhere('id', $currentPartId) : null;
        $ringCircumference = 816.81;
        $appTz = config('app.timezone');
    @endphp

    @php
        $availableDayDates = collect($weekDays ?? [])->pluck('date')->filter()->values();
        $todayDate = \Carbon\Carbon::today()->toDateString();
        $initialSelectedDay = $availableDayDates->contains($todayDate)
            ? $todayDate
            : ($availableDayDates->first() ?? $todayDate);
        $dayPagerSize = 8;
        $showExamDayPager = ($isExamProgram ?? false) && $availableDayDates->count() > $dayPagerSize;
        $initialDaySearch = $availableDayDates->search($initialSelectedDay);
        $initialDayIndex = $initialDaySearch === false ? 0 : (int) $initialDaySearch;
        $initialDayPage = $showExamDayPager ? intdiv($initialDayIndex, $dayPagerSize) : 0;
        $totalDayPages = max(1, (int) ceil(max($availableDayDates->count(), 1) / $dayPagerSize));
        $jalaliDayLabels = collect($weekDays ?? [])->map(fn($day) => jdate(\Carbon\Carbon::parse($day['date']))->format('d F'));
    @endphp

    <div class="max-w-7xl mx-auto px-3 sm:px-4 "
         x-data="{
             jalaliDayLabels: @js($jalaliDayLabels->all()),
             currentPageDateRange() {
                if (!this.showExamDayPager || this.jalaliDayLabels.length === 0) {
                    return `{{ jdate($program->start_date)->format('d F') }} تا {{ jdate($program->end_date)->format('d F') }}`;
                }
                const startIndex = this.dayPage * this.dayPagerSize;
                const endIndex = Math.min(startIndex + this.dayPagerSize - 1, this.jalaliDayLabels.length - 1);
                const startDate = this.jalaliDayLabels[startIndex];
                const endDate = this.jalaliDayLabels[endIndex];
                if (startDate && endDate) {
                    if (startDate === endDate) return startDate;
                    return `${startDate} تا ${endDate}`;
                }
                return '...';
             },
             tab: @js($isActiveProgram ? 'study' : 'grid'),             selectedDay: @js($initialSelectedDay),
             availableDays: @js($availableDayDates->all()),
             dayPage: {{ $initialDayPage }},
             dayPagerSize: {{ $dayPagerSize }},
             totalDayPages: {{ $totalDayPages }},
             showExamDayPager: @js($showExamDayPager),
             openPartId: null,
             focusPartId: @js($focusPartId),
             focusDay: @js($focusDay),
             selectedAlarmState: @entangle('selectedAlarm'),
             previewingAlarmId: null,
             permissionModal:   @entangle('showPermissionModal'),
             finishModal:       @entangle('showFinishModal'),
             makeupFinishModal: @entangle('showMakeupFinishModal'),
             makeupModal:       @entangle('showMakeupModal'),
             feedbackModal:     @entangle('showFeedbackModal'),
             cancelModal:       @entangle('showCancelConfirmModal'),
             earlyModal:        @entangle('showEarlyFinishConfirmModal'),
             studyMoreModal:    @entangle('showStudyMoreModal'),
             alarmModal:        @entangle('showAlarmModal'),

             get anyModalOpen() {
                 return this.permissionModal || this.finishModal || this.makeupFinishModal
                     || this.makeupModal || this.feedbackModal || this.cancelModal
                     || this.earlyModal || this.studyMoreModal || this.alarmModal;
             },
             destroy() {
                 document.body.style.overflow = '';
             },
             ensureSelectedDay() {
                 if (!this.availableDays.includes(this.selectedDay)) {
                     this.selectedDay = this.availableDays[0] ?? this.selectedDay
                 }
                 if (this.showExamDayPager) {
                     const idx = this.availableDays.indexOf(this.selectedDay)
                     this.dayPage = idx >= 0 ? Math.floor(idx / this.dayPagerSize) : 0
                 }
             },
             dayIsVisible(index) {
                 return !this.showExamDayPager || Math.floor(index / this.dayPagerSize) === this.dayPage
             },
             goDayPage(delta) {
                 if (!this.showExamDayPager) return
                 this.dayPage = Math.min(Math.max(this.dayPage + delta, 0), this.totalDayPages - 1)
                 const nextDate = this.availableDays[this.dayPage * this.dayPagerSize]
                 if (nextDate) this.selectedDay = nextDate
             },
             togglePart(partId) {
                 this.openPartId = this.openPartId === partId ? null : partId
             },
             isPartOpen(partId) {
                 return this.openPartId === partId
             },
             focusPendingPart() {
                 if (!this.focusPartId) return
                 const targetId = this.focusPartId
                 if (this.focusDay && this.availableDays.includes(this.focusDay)) {
                     this.selectedDay = this.focusDay
                 }
                 this.ensureSelectedDay()
                 this.tab = 'study'
                 this.openPartId = targetId
                 this.$nextTick(() => {
                     setTimeout(() => {
                         const el = document.getElementById('program-part-' + targetId)
                         if (!el) return
                         el.scrollIntoView({ behavior: 'smooth', block: 'center' })
                         el.classList.add('focus-part-highlight')
                         setTimeout(() => el.classList.remove('focus-part-highlight'), 2800)
                     }, 200)
                 })
                 if (window.history?.replaceState) {
                     const url = new URL(window.location.href)
                     url.searchParams.delete('focus_part')
                     url.searchParams.delete('focus_day')
                     window.history.replaceState({}, '', url)
                 }
                 this.focusPartId = null
             },
         }"
         x-init="ensureSelectedDay(); focusPendingPart()"
         x-effect="document.body.style.overflow = anyModalOpen ? 'hidden' : ''"
         x-on:alarm-preview-state.window="previewingAlarmId = $event.detail?.id || null">

        {{-- ════════════════════════════════════════════════════════════
             صفحه تمام‌صفحه تایمر
           ════════════════════════════════════════════════════════════ --}}
        @if($timerActive)
            <div wire:key="study-timer-overlay-{{ $isMakeupMode ? 'makeup' : 'part' }}-{{ $currentPartId ?? $makeupChapterId ?? 'timer' }}"
                 class="fixed inset-0 z-[90] overflow-y-auto"
                 style="background:rgba(5,5,7,.94); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);"
                 x-data="{
                     wakeLock: null, keepOn: false, wantOn: false,
                     async acquire() {
                         if (!('wakeLock' in navigator)) return;
                         try {
                             this.wakeLock = await navigator.wakeLock.request('screen');
                             this.keepOn = true;
                             this.wakeLock.addEventListener('release', () => { this.keepOn = false; });
                         } catch(e) { console.warn(e); }
                     },
                     async toggleWake() {
                         if (this.wantOn) {
                             this.wantOn = false;
                             try { await this.wakeLock?.release(); } catch(e) {}
                             this.wakeLock = null; this.keepOn = false;
                         } else {
                             this.wantOn = true;
                             await this.acquire();
                         }
                     },
                     init() {
                         // وقتی صفحه قفل/مخفی می‌شود WakeLock آزاد می‌شود؛ موقع برگشت دوباره می‌گیریم
                         document.addEventListener('visibilitychange', () => {
                             if (this.wantOn && document.visibilityState === 'visible' && !this.keepOn) this.acquire();
                         });
                     }
                 }">
                <div class="min-h-full flex flex-col items-center justify-center px-4 py-10 text-white">

                    <div class="mb-6 text-center">
                        @if($isMakeupMode)
                            <span class="text-xs font-bold px-3 py-1 rounded-full bg-blue-500/20 text-blue-300">مطالعه اضافه بر سازمان</span>
                        @elseif($isInExtraPhase)
                            <span class="text-xs font-bold px-3 py-1 rounded-full bg-blue-500/20 text-blue-300">اضافه بر مشاور</span>
                            <div class="mt-2 font-bold text-white">{{ $activePart?->lesson_name ?? '—' }}</div>
                        @else
                            @if($activePart?->ccSubject)
                                <div class="text-xs mb-1 text-muted">{{ $activePart->ccSubject->name }}</div>
                            @endif
                            <div class="font-black text-white text-lg">{{ $activePart?->lesson_name ?? '—' }}</div>
                            @php $apm = $activePart?->part_mode ?? 'normal'; @endphp
                            @if($apm === 'review')
                                @php $arcs = collect($activePart->review_chapters ?? []); @endphp
                                @if($arcs->count())
                                    <div class="flex flex-wrap items-center justify-center gap-1 mt-1">
                                        @foreach($arcs as $rc)
                                            <span class="text-[10px] px-1.5 py-0.5 rounded-md bg-amber-500/20 text-amber-300">{{ $rc['name'] }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-xs mt-1 text-gray-400">بدون فصل</div>
                                @endif
                            @elseif($apm === 'whole_book')
                                <div class="text-xs mt-1 text-emerald-400">کل کتاب</div>
                            @elseif($activePart?->ccChapter)
                                <div class="text-xs mt-1 text-blue-400">{{ $activePart->ccChapter->name }}</div>
                            @else
                                <div class="text-xs mt-1 text-gray-400">بدون فصل</div>
                            @endif
                            @if($pendingExtraTargetSeconds !== null)
                                <span class="inline-block mt-2 text-[10px] px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-300">
                                    + {{ $this->formatClock($pendingExtraTargetSeconds) }} اضافه بعد از پایان
                                </span>
                            @endif
                        @endif
                    </div>

                    @php
                        if ($isMakeupMode) {
                            $ovRemaining = $makeupRemainingSeconds; $ovTarget = $makeupTargetSeconds;
                            $ovEndsAt = $makeupEndsAtTs; $ovRunning = $makeupTimerRunning;
                            $ovClockId = 'makeup-clock'; $ovRingId = 'makeup-ring'; $ovColor = '#0ea5e9';
                        } elseif ($isInExtraPhase) {
                            $ovRemaining = $extraRemainingSeconds; $ovTarget = $extraTargetSeconds;
                            $ovEndsAt = $extraEndsAtTs; $ovRunning = $isRunning;
                            $ovClockId = 'extra-clock'; $ovRingId = 'extra-ring'; $ovColor = '#0ea5e9';
                        } else {
                            $ovRemaining = $remainingSeconds; $ovTarget = $targetSeconds;
                            $ovEndsAt = $endsAtTs; $ovRunning = $isRunning;
                            $ovClockId = 'main-clock'; $ovRingId = 'main-ring'; $ovColor = '#2563eb';
                        }
                        $ovOffset = $ovTarget > 0 ? $ringCircumference * (1 - min(1, $ovRemaining / $ovTarget)) : 0;
                        $ovTotalH = intdiv((int)$ovTarget, 3600);
                        $ovTotalM = intdiv(((int)$ovTarget) % 3600, 60);
                        $ovTotalS = ((int)$ovTarget) % 60;
                    @endphp

                    <div class="relative" style="width:300px; height:300px;">
                        <svg viewBox="0 0 280 280" class="w-full h-full">
                            <circle cx="140" cy="140" r="130" fill="none" stroke="#1c1c1e" stroke-width="7"/>
                            <circle id="{{ $ovRingId }}" cx="140" cy="140" r="130" fill="none"
                                    stroke="{{ $ovColor }}" stroke-width="7" stroke-linecap="round"
                                    class="timer-ring" stroke-dasharray="{{ $ringCircumference }}"
                                    stroke-dashoffset="{{ $ovOffset }}"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6">
                            <div id="{{ $ovClockId }}" dir="ltr"
                                 style="font-family:'Digital',monospace; font-size:56px; color:#fff; letter-spacing:2px; line-height:1;">
                                {{ $this->formatClock($ovRemaining) }}
                            </div>
                            <div class="mt-3 text-sm text-gray-400">
                                زمان کل {{ $ovTotalH > 0 ? $ovTotalH . ' ساعت و ' : '' }}{{ $ovTotalM }} دقیقه{{ $ovTotalS > 0 ? ' و ' . $ovTotalS . ' ثانیه' : '' }}
                            </div>
                            @if($ovEndsAt)
                                <div class="mt-2 text-xs font-semibold" style="color:{{ $ovColor }};">
                                    @if($ovRunning)
                                        پایان در ساعت {{ jdate(\Carbon\Carbon::createFromTimestamp($ovEndsAt, $appTz))->format('H:i') }}
                                    @else
                                        متوقف شده — با ادامه، زمان پایان به‌روز می‌شود
                                    @endif
                                </div>
                            @endif
                            <button type="button" @click="toggleWake()" data-elevated="false"
                                    class="btn-press mt-4 px-4 py-1.5 rounded-full text-xs flex items-center gap-1.5 border"
                                    :class="wantOn ? 'bg-blue-500/20 text-blue-400 border-blue-500/40' : 'bg-white/5 text-gray-400 border-white/10'">
                                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                                </svg>
                                <span x-text="wantOn ? 'صفحه روشن می‌ماند' : 'روشن نگه‌داشتن صفحه'"></span>
                            </button>
                        </div>
                    </div>

                    <div class="mt-4">
                        <span class="text-xs px-3 py-1 rounded-full font-semibold"
                              :class="'{{ $ovRunning ? "bg-emerald-500/15 text-emerald-400" : "bg-amber-500/15 text-amber-400" }}'">
                            {{ $ovRunning ? 'در حال اجرا' : 'متوقف' }}
                        </span>
                    </div>

                    @if(!$isMakeupMode && $this->canShowEarlyOrMore)
                        <div class="grid grid-cols-2 gap-2 mt-6 w-full max-w-sm">
                            <button wire:click="openEarlyFinishConfirm" wire:loading.attr="disabled" wire:target="openEarlyFinishConfirm"
                                    class="btn-press h-11 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/40 disabled:opacity-50 inline-flex items-center justify-center gap-2" data-elevated="false">
                                <span wire:loading.remove wire:target="openEarlyFinishConfirm" class="inline-flex items-center gap-1.5">زودتر تمام کردم<x-ui.icon name="check" class="w-3.5 h-3.5"/></span>
                                <x-ui.spinner size="xs" wire:loading wire:target="openEarlyFinishConfirm"/>
                            </button>
                            <button wire:click="openStudyMoreModal" wire:loading.attr="disabled" wire:target="openStudyMoreModal"
                                    class="btn-press h-11 rounded-full text-xs font-bold bg-blue-500/15 text-blue-400 border border-blue-500/40 disabled:opacity-50 inline-flex items-center justify-center gap-2" data-elevated="false">
                                <span wire:loading.remove wire:target="openStudyMoreModal" class="inline-flex items-center gap-1.5">مطالعه بیشتر<x-ui.icon name="plus" class="w-3.5 h-3.5"/></span>
                                <x-ui.spinner size="xs" wire:loading wire:target="openStudyMoreModal"/>
                            </button>
                        </div>
                    @endif
                    <div class="flex items-center justify-center gap-14 mt-10">
                        <!-- دکمه لغو کامل -->
                        <button type="button" data-elevated="false"
                                wire:click.prevent="openCancelConfirm"
                                wire:loading.attr="disabled"
                                wire:target="openCancelConfirm"
                                class="btn-press w-16 h-16 rounded-full flex items-center justify-center bg-white/5 border border-white/10 disabled:opacity-50"
                                title="لغو">
                            <span class="block w-5 h-5 rounded" style="background:{{ $ovColor }};"></span>
                        </button>

                        @if($isMakeupMode)
                            <!-- ================= حالت تایمر جبرانی ================= -->
                            @if($makeupTimerRunning)
                                <!-- دکمه توقف جبرانی (سریع) -->
                                <button wire:click.prevent="pauseMakeup" data-elevated="false"
                                        wire:loading.attr="disabled"
                                        wire:target="pauseMakeup"
                                        class="btn-press w-16 h-16 rounded-full flex items-center justify-center bg-white/5 border border-white/10 disabled:opacity-50" title="توقف">
                <span wire:loading.remove wire:target="pauseMakeup" class="flex gap-1.5">
                    <span class="block w-1.5 h-5 rounded" style="background:{{ $ovColor }};"></span>
                    <span class="block w-1.5 h-5 rounded" style="background:{{ $ovColor }};"></span>
                </span>
                                    <span wire:loading wire:target="pauseMakeup" style="color:{{ $ovColor }};"><x-ui.spinner size="sm"/></span>
                                </button>
                            @else

                                <!-- دکمه ادامه جبرانی -->
                                <button type="button" data-elevated="false"
                                        wire:click.prevent="resumeMakeup"
                                        wire:loading.attr="disabled"
                                        wire:target="resumeMakeup"
                                        class="btn-press w-16 h-16 rounded-full flex items-center justify-center bg-white/5 border border-white/10 disabled:opacity-50" title="ادامه">
                                    <svg wire:loading.remove wire:target="resumeMakeup" viewBox="0 0 24 24" fill="{{ $ovColor }}" class="w-6 h-6" style="margin-right:-2px;">
                                        <path d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z"/>
                                    </svg>
                                    <span wire:loading wire:target="resumeMakeup" style="color:{{ $ovColor }};"><x-ui.spinner size="sm"/></span>
                                </button>
                            @endif
                        @else
                            <!-- ================= حالت تایمر برنامه‌ای (عادی) ================= -->
                            @if($isRunning)
                                <!-- دکمه توقف عادی (سریع) -->
                                <button type="button" data-elevated="false"
                                        wire:click.prevent="pausePart"
                                        wire:loading.attr="disabled"
                                        wire:target="pausePart"
                                        class="btn-press w-16 h-16 rounded-full flex items-center justify-center bg-white/5 border border-white/10 disabled:opacity-50" title="توقف">
                <span wire:loading.remove wire:target="pausePart" class="flex gap-1.5">
                    <span class="block w-1.5 h-5 rounded" style="background:{{ $ovColor }};"></span>
                    <span class="block w-1.5 h-5 rounded" style="background:{{ $ovColor }};"></span>
                </span>
                                    <span wire:loading wire:target="pausePart" style="color:{{ $ovColor }};"><x-ui.spinner size="sm"/></span>
                                </button>
                            @elseif($pausedAtTs)
                                <!-- دکمه ادامه عادی -->
                                <button type="button" data-elevated="false"
                                        wire:click.prevent="resumePart"
                                        wire:loading.attr="disabled"
                                        wire:target="resumePart"
                                        class="btn-press w-16 h-16 rounded-full flex items-center justify-center bg-white/5 border border-white/10 disabled:opacity-50" title="ادامه">
                                    <svg wire:loading.remove wire:target="resumePart" viewBox="0 0 24 24" fill="{{ $ovColor }}" class="w-6 h-6" style="margin-right:-2px;">
                                        <path d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z"/>
                                    </svg>
                                    <span wire:loading wire:target="resumePart" style="color:{{ $ovColor }};"><x-ui.spinner size="sm"/></span>
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8 space-y-6">

                <section class="overflow-hidden rounded-2xl border border-border glass">
                    <div class="bg-gradient-to-r from-info via-info to-info/70 px-4 py-5 sm:px-6 sm:py-6">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div class="flex items-center gap-4">
                                {{-- حالت موبایل: فقط تصویر هدر، بدون آیکون و بدون متن --}}
                                <div class="flex w-full items-center justify-center rounded-xl bg-white/20 py-2 ring-2 ring-white/40 shadow-md backdrop-blur-sm md:hidden">
                                    <img src="/client/assets/images/theme/intro/header.png"
                                         alt="برنامه مطالعاتی"
                                         class="h-14 w-auto object-contain">
                                </div>

                                {{-- حالت دسکتاپ: آیکون + عنوان، هم‌تراز و هم‌سطح با دکمه «بازگشت به جلسات» و تب‌ها --}}
                                <div class="hidden h-14 w-14 items-center justify-center rounded-2xl bg-white/20 ring-2 ring-white/40 shadow-md backdrop-blur-sm md:flex">
                                    <img src="/client/assets/images/favicon.svg" class="w-10 h-10" alt="لوگو">
                                </div>
                                <div class="hidden md:block">
                                    <h1 class="text-lg sm:text-xl font-bold text-white">برنامه مطالعاتی</h1>
                                </div>
                            </div>

                            <div class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center">

                                @php
                                    $programTabItems = [];
                                    if ($isActiveProgram) {
                                        $programTabItems['study'] = 'ثبت مطالعه';
                                    }
                                    $programTabItems['grid']    = 'جدول برنامه';
                                    $programTabItems['archive'] = 'آرشیو';
                                @endphp
                                <a wire:navigate href="{{ route('client.profile.plan') }}"
                                   class="inline-flex h-10 items-center  justify-center gap-2 rounded-xl bg-error px-4 py-2 text-xs font-medium text-error-foreground shadow-sm hover:bg-error/90 transition-all sm:h-auto">
                                    <i class="fas fa-arrow-right"></i><span>بازگشت به جلسات</span>
                                </a>


                            </div>
                        </div>
                    </div>
                </section>
                <x-ui.segmented-tabs
                    wire:ignore
                    class="w-full sm:w-auto"
                    :items="$programTabItems"
                    :active="$isActiveProgram ? 'study' : 'grid'"
                    indicator-class="bg-white/95 shadow-sm"
                    item-class="flex-1 justify-center px-9 py-1.5 text-[12px] sm:flex-none sm:text-xs font-medium"
                    @segmented-change="tab = $event.detail"
                />
                <section x-show="tab !== 'study'" x-cloak class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-3">
                    <div class="rounded-2xl bg-gradient-to-br from-info to-info/70 p-4 text-info-foreground shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[11px] text-info-foreground/80">ساعات مطالعه</p>
                                <p class="mt-1 text-2xl font-extrabold sm:text-3xl">{{ $stats['totalHours'] }}</p>
                            </div>
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20">
                                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5">
                                    <circle cx="10" cy="10" r="8" stroke-linecap="round"></circle>
                                    <path d="M10 5v5h4" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl bg-gradient-to-br from-success to-success/70 p-4 text-success-foreground shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[11px] text-success-foreground/80">تعداد تست</p>
                                <p class="mt-1 text-2xl font-extrabold sm:text-3xl">{{ $stats['totalTests'] }}</p>
                            </div>
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20">
                                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl bg-gradient-to-br from-info to-info/70 p-4 text-info-foreground shadow-md col-span-2 md:col-span-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[11px] text-info-foreground/80">تعداد پارت‌ها</p>
                                <p class="mt-1 text-2xl font-extrabold sm:text-3xl">{{ $stats['totalParts'] }}</p>
                            </div>
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20">
                                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ════════════════ تب ثبت مطالعه ════════════════ --}}
                @if($isActiveProgram)
                    <section x-show="tab === 'study'" x-cloak class="space-y-4">

                        @if(!$timerActive)
                            <button wire:click="openMakeupModal" wire:loading.attr="disabled" wire:target="openMakeupModal"
                                    class="btn-press w-full h-12 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 bg-gradient-to-l from-info/70 to-info text-info-foreground shadow-md shadow-info/20 disabled:opacity-60" data-elevated="true">
                                <span wire:loading.remove wire:target="openMakeupModal" class="flex items-center gap-2">
                                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                    </svg>
                               ثبت مطالعه مازاد بر برنامه مشاور
                                </span>
                                <x-ui.spinner size="sm" wire:loading wire:target="openMakeupModal"/>
                            </button>

                            <button type="button" @click="alarmModal = true" data-elevated="false"
                                    class="btn-press w-full h-11 rounded-2xl font-semibold text-sm flex items-center justify-center gap-2 bg-secondary border border-border text-foreground">
                                <x-ui.icon name="bell" class="w-4 h-4 text-info"/>
                                <span>انتخاب صدای آلارم قبل از شروع مطالعه</span>
                            </button>
                        @endif

                        <div class="glass border border-border rounded-2xl p-3">
                            <div class="flex items-center gap-2 mb-3 px-1">
                                @if($showExamDayPager)
                                    <span class="text-[18px] font-bold text-primary" x-text="currentPageDateRange()"></span>
                                    <span class="text-muted text-xs">|</span>
                                @endif

                            </div>
                            @if($showExamDayPager)
                                <div class="mb-3 flex items-center justify-between gap-2 rounded-xl bg-secondary/70 px-2.5 py-2">
                                    <button type="button"
                                            @click="goDayPage(-1)"
                                            :disabled="dayPage === 0" data-elevated="false"
                                            class="btn-press flex items-center justify-center w-9 h-9 rounded-xl border border-border glass text-foreground transition-all shrink-0 disabled:opacity-35 disabled:cursor-not-allowed">
                                        <x-ui.icon name="chevron-right" class="w-4 h-4"/>
                                    </button>
                                    <button type="button"
                                            @click="goDayPage(1)"
                                            :disabled="dayPage >= totalDayPages - 1" data-elevated="false"
                                            class="btn-press flex items-center justify-center w-9 h-9 rounded-xl border border-border glass text-foreground transition-all shrink-0 disabled:opacity-35 disabled:cursor-not-allowed">
                                        <x-ui.icon name="chevron-left" class="w-4 h-4"/>
                                    </button>
                                </div>
                            @endif
                            {{--
                                justify-center روی یک ردیف اسکرول‌شونده‌ی سرریز خطرناکه: وقتی محتوا از عرض کادر
                                بیشتر بشه، مرورگر با justify-content: center سرریز رو مساوی دو طرف پخش می‌کنه و
                                نصف چپش قابل‌اسکرول می‌مونه ولی نصف راستش (روز اول، چون rtl هستیم) از محدوده‌ی
                                قابل‌اسکرول بیرون می‌افته و همیشه نصفه دیده می‌شه - دقیقاً همون باگی که این‌جا بود.
                                justify-content: safe center دقیقاً برای همین ساخته شده: تا وقتی محتوا جا می‌شه
                                وسط‌چینش می‌کنه (مثل center)، ولی همین که سرریز بشه خودش به‌صورت خودکار می‌ره روی
                                حالت start (بدون بریدگی، کاملاً قابل‌اسکرول) - این‌طوری هم حالت کم‌تعداد روزها وسط
                                می‌مونه هم حالت پرتعداد روزها همه‌شون کامل و قابل‌اسکرول دیده می‌شن.
                            --}}
                            <div class="flex items-center gap-2 ss-scroll overflow-x-auto pb-1 [justify-content:safe_center]">
                                @foreach($weekDays as $dayIndex => $day)
                                    @php
                                        $isToday = $day['date'] === \Carbon\Carbon::today()->toDateString();
                                        $isPast  = $day['date'] < \Carbon\Carbon::today()->toDateString();
                                        $allDone = $day['parts']->count() > 0 && $day['parts']->every(fn($p) => in_array($p->id, $completedParts));
                                        $hasMiss = !$day['is_rest_day'] && $isPast && $day['parts']->filter(fn($p) => !in_array($p->id, $completedParts))->count() > 0;
                                        $jalDay  = jdate(\Carbon\Carbon::parse($day['date']))->format('d');

                                        if ($allDone)      $dayCls = 'bg-success/15 text-success border-success/40';
                                        elseif ($hasMiss)  $dayCls = 'bg-error/15 text-error border-error/40';
                                        elseif ($isToday)  $dayCls = 'bg-info/15 text-info border-info/40';
                                        else               $dayCls = 'bg-background text-muted border-border';
                                    @endphp
                                    <button type="button" @click="selectedDay = '{{ $day['date'] }}'" data-elevated="false"
                                            @if($showExamDayPager) x-show="dayIsVisible({{ $dayIndex }})" @endif
                                            class="btn-press flex-shrink-0 flex flex-col items-center gap-1">
                                        <span class="flex items-center justify-center w-10 h-10 md:w-12 md:h-12 lg:w-14 lg:h-14 rounded-full font-bold text-[15px] md:text-sm lg:text-base border-2 transition-all"
                                              :class="selectedDay === '{{ $day['date'] }}' ? 'bg-primary text-primary-foreground border-primary' : '{{ $dayCls }}'">
                                            {{ $jalDay }}
                                        </span>
                                        <span class="text-[10px] md:text-[11px] font-medium leading-none transition-colors"
                                              :class="selectedDay === '{{ $day['date'] }}' ? 'text-primary' : 'text-muted'">
                                            <span class="hidden md:inline">{{ $day['name'] }}</span>
                                            <span class="md:hidden">{{ mb_substr($day['name'], 0, 1) }}</span>
                                        </span>
                                        <span class="h-1 w-5 rounded-full transition-all"
                                              :class="selectedDay === '{{ $day['date'] }}' ? 'bg-primary' : 'bg-transparent'"></span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        @foreach($weekDays as $day)
                            <div x-show="selectedDay === '{{ $day['date'] }}'" x-cloak class="space-y-4">

                                @if($day['is_rest_day'])
                                    <div class="glass border border-success/20 rounded-2xl p-8 text-center">
                                        <div class="text-3xl mb-2">🌿</div>
                                        <div class="font-bold text-success mb-1">روز استراحت</div>
                                        <div class="text-sm text-muted">امروز نیازی به مطالعه نیست</div>
                                    </div>

                                @elseif($day['parts_count'] > 0)
                                    @php
                                        $plannedMin = $day['parts']->sum('duration_minutes');
                                        $doneMin    = $day['parts']->filter(fn($p) => in_array($p->id, $completedParts))->sum('duration_minutes');
                                        $donePartsCount = $day['parts']->filter(fn($p) => in_array($p->id, $completedParts))->count();
                                        $dayPercent = $plannedMin > 0 ? min(100, round(($doneMin / $plannedMin) * 100)) : 0;
                                        $dropCirc = 326.726;
                                        $dropOffset = $dropCirc * (1 - $dayPercent / 100);
                                    @endphp
                                    <div class="glass border border-border rounded-2xl p-4">
                                        <div class="flex items-center gap-4">
                                            <div class="relative flex-shrink-0" style="width:96px; height:96px;">
                                                <svg viewBox="0 0 120 120" class="w-full h-full -rotate-90">
                                                    <circle cx="60" cy="60" r="52" fill="none" class="stroke-border" stroke-width="9"/>
                                                    <circle cx="60" cy="60" r="52" fill="none" stroke="url(#dg{{ $day['index'] }})" stroke-width="9" stroke-linecap="round"
                                                            class="drop-ring" stroke-dasharray="{{ $dropCirc }}" stroke-dashoffset="{{ $dropOffset }}"/>
                                                    <defs>
                                                        <linearGradient id="dg{{ $day['index'] }}" x1="0%" y1="0%" x2="100%" y2="100%">
                                                            <stop offset="0%" stop-color="#3b82f6"/>
                                                            <stop offset="100%" stop-color="#0ea5e9"/>
                                                        </linearGradient>
                                                    </defs>
                                                </svg>
                                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                                    <span class="font-black text-lg text-foreground">{{ $dayPercent }}٪</span>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0 space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm font-bold text-foreground">پیشرفت امروز</span>
                                                    <span class="text-xs text-muted">{{ $donePartsCount }} از {{ $day['parts']->count() }} پارت</span>
                                                </div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div class="rounded-xl bg-background border border-border px-3 py-2 text-center">
                                                        <div class="font-bold text-success text-sm">{{ $this->formatDuration($doneMin * 60) }}</div>
                                                        <div class="text-[10px] text-muted">مطالعه‌شده</div>
                                                    </div>
                                                    <div class="rounded-xl bg-background border border-border px-3 py-2 text-center">
                                                        <div class="font-bold text-foreground text-sm">{{ $this->formatDuration($plannedMin * 60) }}</div>
                                                        <div class="text-[10px] text-muted">برنامه روز</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ✅ FIX: items-start جلوگیری از stretch شدن کارت بغلی --}}
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 items-center">
                                        @foreach($day['parts']->sortBy('part_order') as $part)
                                            @php
                                                $isDone   = in_array($part->id, $completedParts);
                                                $isActive = $currentPartId == $part->id;
                                                $isMissed = !$isDone && $day['date'] < \Carbon\Carbon::today()->toDateString();
                                                $typeStyle = $part->part_type === 'test' ? 'bg-info/10 text-info'
                                                    : ($part->part_type === 'descriptive' ? 'bg-success/10 text-success' : 'bg-warning/10 text-warning');
                                                $sampleQuestion = (
                                                    ($part->part_mode ?? 'normal') === \App\Models\ProgramPart::PART_MODE_WHOLE_BOOK
                                                    && $part->part_type === \App\Models\ProgramPart::PART_TYPE_DESCRIPTIVE
                                                )
                                                    ? ($mainSampleQuestionsBySubject[(int) $part->cc_subject_id] ?? null)
                                                    : null;
                                            @endphp

                                            <div wire:key="part-card-{{ $part->id }}"
                                                 id="program-part-{{ $part->id }}"
                                                 class="glass border rounded-2xl overflow-hidden transition-colors
                                                        {{ $isActive ? 'border-primary' : ($isDone ? 'border-success/30' : ($isMissed ? 'border-error/30' : 'border-border')) }}">

                                                <div class="flex items-center justify-between gap-3 p-4 cursor-pointer" @click="togglePart({{ $part->id }})">
                                                    <div class="min-w-0 flex-1">
                                                        <h4 class="font-bold text-foreground text-sm truncate">{{ $part->lesson_name }}</h4>
                                                        @php $pm = $part->part_mode ?? 'normal'; @endphp
                                                        @if($pm === 'review' && $part->ccSubject)
                                                            <p class="text-[11px] text-muted truncate mt-0.5">{{ $part->ccSubject->name }}</p>
                                                        @endif
                                                        @if($pm === 'review')
                                                            @php $rcs = collect($part->review_chapters ?? []); @endphp
                                                            @if($rcs->count())
                                                                <div class="flex flex-wrap gap-1 mt-1">
                                                                    @foreach($rcs as $rc)
                                                                        <span class="inline-block rounded-md bg-warning/10 text-warning px-1.5 py-0.5 text-[10px] font-semibold">{{ $rc['name'] }}</span>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <p class="text-[11px] text-muted mt-0.5">بدون فصل</p>
                                                            @endif
                                                        @elseif($pm === 'whole_book')
                                                            <p class="text-[11px] text-success mt-0.5">کل کتاب</p>
                                                        @elseif($part->ccChapter)
                                                            <p class="text-[11px] text-muted truncate mt-0.5">{{ $part->ccChapter->name }}</p>
                                                        @else
                                                            <p class="text-[11px] text-muted mt-0.5">بدون فصل</p>
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center gap-3 flex-shrink-0">
                                                        @if($part->test_count)
                                                            <div class="text-center">
                                                                <div class="font-bold text-foreground text-sm leading-none">{{ $part->test_count }}</div>
                                                                <div class="text-[10px] text-muted mt-0.5">تست</div>
                                                            </div>
                                                        @endif
                                                        <div class="text-center">
                                                            <div class="font-bold text-foreground text-sm leading-none">{{ $part->duration_minutes }}</div>
                                                            <div class="text-[10px] text-muted mt-0.5">دقیقه</div>
                                                        </div>
                                                        <div class="flex items-center justify-center w-8 h-8 flex-shrink-0">
                                                            @if($isDone)
                                                                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-success/15">
                                                                    <svg class="w-4 h-4 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                                    </svg>
                                                                </span>
                                                            @elseif($isMissed)
                                                                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-error/15">
                                                                    <svg class="w-4 h-4 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                                    </svg>
                                                                </span>
                                                            @elseif($isActive)
                                                                <span class="w-2.5 h-2.5 rounded-full bg-primary animate-pulse"></span>
                                                            @else
                                                                <svg class="w-5 h-5 text-muted transition-transform duration-200" :class="{ 'rotate-180': isPartOpen({{ $part->id }}) }"
                                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div x-show="isPartOpen({{ $part->id }})" x-collapse x-cloak>
                                                    <div class="px-4 pb-4 space-y-3 border-t border-border pt-3">
                                                        @php $pmd = $part->part_mode ?? 'normal'; @endphp
                                                        <div class="flex items-center justify-center gap-1.5 flex-wrap text-xs text-foreground/80">
                                                            @if($part->ccSubject)<span>{{ $part->ccSubject->name }}</span>@endif
                                                            @if($pmd === 'review')
                                                                @php $rcs = collect($part->review_chapters ?? []); @endphp
                                                                @if($rcs->count())
                                                                    <span class="text-muted">«</span>
                                                                    @foreach($rcs as $rc)
                                                                        <span class="rounded-md bg-warning/10 text-warning px-1.5 py-0.5 text-[11px] font-semibold">{{ $rc['name'] }}</span>
                                                                    @endforeach
                                                                @else
                                                                    <span class="text-muted">« بدون فصل</span>
                                                                @endif
                                                            @elseif($pmd === 'whole_book')
                                                                <span class="text-muted">«</span><span class="text-success">کل کتاب</span>
                                                            @elseif($part->ccChapter)
                                                                @if($part->ccSubject)<span class="text-muted">«</span>@endif
                                                                <span>{{ $part->ccChapter->name }}</span>
                                                            @else
                                                                <span class="text-muted">« بدون فصل</span>
                                                            @endif
                                                        </div>
                                                        <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                                            <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $typeStyle }}">{{ $part->part_type_label }}</span>
                                                            @if($part->grade)
                                                                <span class="rounded-full px-2.5 py-0.5 text-[11px] font-semibold bg-info/10 text-info">
                                                                    @if($part->grade == 10) دهم @elseif($part->grade == 11) یازدهم @elseif($part->grade == 12) دوازدهم @endif
                                                                </span>
                                                            @endif
                                                            <span class="rounded-full px-2.5 py-0.5 text-[11px] bg-secondary text-muted">{{ $part->lesson_type_label }}</span>
                                                        </div>
                                                        @if($part->description)
                                                            <p class="text-xs text-muted leading-relaxed text-center">{{ $part->description }}</p>
                                                        @endif
                                                        @if($sampleQuestion)
                                                            <div class="rounded-xl border border-success/20 bg-success/10 px-3 py-2 text-xs text-success text-center">
                                                                <span class="font-bold">نمونه سؤال اصلی:</span>
                                                                <span>{{ $sampleQuestion['title'] }}</span>
                                                                @if($sampleQuestion['duration_minutes'] > 0)
                                                                    <span class="mx-1 text-success/60">|</span>
                                                                    <span>{{ $sampleQuestion['duration_minutes'] }} دقیقه</span>
                                                                @endif
                                                            </div>
                                                        @endif

                                                        @if($isDone)
                                                            @php $meta = $completedPartsMeta[$part->id] ?? null; @endphp
                                                            <div class="text-xs font-bold py-2.5 rounded-xl bg-success/10 text-success text-center">
                                                                ✓ تکمیل شده
                                                                @if($meta && ($meta['is_early_finish'] ?? false)) (زودتر تمام شد) @endif
                                                                @if($meta && ($meta['extra_seconds'] ?? 0) > 0) + {{ $this->formatDuration($meta['extra_seconds']) }} اضافه @endif
                                                            </div>
                                                        @elseif($isActive)
                                                            <div class="text-xs font-bold py-2.5 rounded-xl bg-primary/10 text-primary text-center">در حال مطالعه...</div>
                                                        @elseif($isMissed)
                                                            @if($sampleQuestion)
                                                                <button type="button"
                                                                        @click="window.startSampleQuestionPart && window.startSampleQuestionPart({{ $part->id }}, @js($sampleQuestion['download_url']), @js($sampleQuestion['title']))"
                                                                        class="btn-press w-full h-11 rounded-xl font-bold text-sm bg-warning hover:bg-warning/90 text-warning-foreground transition-colors disabled:opacity-60 inline-flex items-center justify-center gap-2" data-elevated="true">
                                                                    دانلود و ثبت جبرانی بعد از ۳۰ ثانیه
                                                                </button>
                                                            @else
                                                                <button wire:click="startPart({{ $part->id }})"
                                                                        wire:loading.attr="disabled" wire:target="startPart({{ $part->id }})"
                                                                        class="btn-press w-full h-11 rounded-xl font-bold text-sm bg-warning hover:bg-warning/90 text-warning-foreground transition-colors disabled:opacity-60 inline-flex items-center justify-center gap-2" data-elevated="true">
        <span wire:loading.remove wire:target="startPart({{ $part->id }})" class="flex items-center gap-1.5">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0011.667 0l3.181-3.183m-4.991-2.696v-.001h4.992m-4.992 0L21 4.356"/>
            </svg>
            ثبت جبرانی
        </span>
                                                                    <x-ui.spinner size="sm" wire:loading wire:target="startPart({{ $part->id }})"/>
                                                                </button>
                                                            @endif
                                                            <p class="text-[10px] text-warning/80 text-center mt-1">این پارت در روزهای گذشته بوده — می‌توانی الان جبرانش کنی</p>
                                                        @elseif(!$timerActive)
                                                            @if($sampleQuestion)
                                                                <button type="button" data-elevated="true"
                                                                        @click="window.startSampleQuestionPart && window.startSampleQuestionPart({{ $part->id }}, @js($sampleQuestion['download_url']), @js($sampleQuestion['title']))"
                                                                        class="btn-press w-full h-11 rounded-xl font-bold text-sm bg-primary hover:bg-primary/90 text-primary-foreground transition-colors disabled:opacity-60 inline-flex items-center justify-center gap-2">
                                                                    دانلود و شروع بعد از ۳۰ ثانیه
                                                                </button>
                                                            @else
                                                                <button wire:click="startPart({{ $part->id }})" data-elevated="true"
                                                                        wire:loading.attr="disabled" wire:target="startPart({{ $part->id }})"
                                                                        class="btn-press w-full h-11 rounded-xl font-bold text-sm bg-primary hover:bg-primary/90 text-primary-foreground transition-colors disabled:opacity-60 inline-flex items-center justify-center gap-2">
                                                                    <span wire:loading.remove wire:target="startPart({{ $part->id }})" class="inline-flex items-center gap-1.5">شروع مطالعه<x-ui.icon name="arrow-left" class="w-4 h-4"/></span>
                                                                    <x-ui.spinner size="sm" wire:loading wire:target="startPart({{ $part->id }})"/>
                                                                </button>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                @else
                                    <div class="glass border border-border rounded-2xl p-8 text-center">
                                        <div class="text-sm text-muted">برنامه‌ای برای این روز تنظیم نشده</div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </section>
                @endif

                {{-- ════════════════ جدول برنامه ════════════════ --}}
                <section x-show="tab === 'grid'" x-cloak
                         class="rounded-2xl border border-border glass"
                         x-data="{
                            currentDayIndex: 0,
                            dayDropOpen: false,
                            totalDays: {{ count($weekDays) }},
                            dayLabels: @js(collect($weekDays)->map(fn($d) => $d['name'].' — '.$d['jalali_date'].($d['is_rest_day'] ? ' (استراحت)' : ''))->values()->all()),
                            goNext() { if (this.currentDayIndex < this.totalDays - 1) this.currentDayIndex++; },
                            goPrev() { if (this.currentDayIndex > 0) this.currentDayIndex--; }
                         }">

                    {{-- ناوبری موبایل با dropdown هم‌سبک x-ui.select --}}
                    <div class="flex md:hidden items-center gap-2 px-3 py-3 border-b border-border">
                        <button type="button" @click="goPrev()" :disabled="currentDayIndex === 0" data-elevated="false"
                                :class="currentDayIndex === 0 ? 'opacity-40' : 'hover:bg-primary/10 active:scale-95'"
                                class="btn-press flex items-center justify-center w-9 h-9 rounded-xl border border-border glass text-foreground transition-all shrink-0">
                            <x-ui.icon name="chevron-right" class="w-4 h-4"/>
                        </button>

                        {{-- ✅ FIX: drop‌down هم‌سبک x-ui.select بجای select native --}}
                        <div class="flex-1 relative" @click.outside="dayDropOpen = false" @keydown.escape="dayDropOpen = false">
                            <button type="button" @click.stop="dayDropOpen = !dayDropOpen" data-elevated="false"
                                    :class="dayDropOpen ? 'border-info ring-2 ring-info/20' : ''"
                                    class="btn-press w-full flex items-center justify-between gap-2 rounded-xl border border-border bg-secondary px-3 py-2 text-[12px] text-foreground transition-all">
                                <span class="flex-1 truncate text-right" x-text="dayLabels[currentDayIndex] || 'انتخاب روز'"></span>
                                <x-ui.icon name="chevron-down" class="w-3.5 h-3.5 text-muted transition-transform" x-bind:class="{ 'rotate-180': dayDropOpen }"/>
                            </button>

                            <div x-show="dayDropOpen" x-cloak
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute z-50 mt-1 w-full rounded-xl overflow-hidden border border-border bg-secondary shadow-xl shadow-black/10 max-h-56 overflow-y-auto">
                                @foreach($weekDays as $i => $day)
                                    <button type="button" data-elevated="false"
                                            @click="currentDayIndex = {{ $i }}; dayDropOpen = false"
                                            :class="currentDayIndex === {{ $i }} ? 'bg-info text-info-foreground' : 'text-foreground hover:bg-background'"
                                            class="btn-press w-full text-right px-3 py-2.5 text-sm flex items-center justify-between gap-2 transition-colors">
                                        <span class="truncate">{{ $day['name'] }} — {{ $day['jalali_date'] }} @if($day['is_rest_day'])<span class="text-success text-[10px]">(استراحت)</span>@endif</span>
                                        <x-ui.icon name="check" x-show="currentDayIndex === {{ $i }}" class="w-4 h-4 shrink-0"/>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <button type="button" @click="goNext()" :disabled="currentDayIndex === totalDays - 1" data-elevated="false"
                                :class="currentDayIndex === totalDays - 1 ? 'opacity-40' : 'hover:bg-primary/10 active:scale-95'"
                                class="btn-press flex items-center justify-center w-9 h-9 rounded-xl border border-border glass text-foreground transition-all shrink-0">
                            <x-ui.icon name="chevron-left" class="w-4 h-4"/>
                        </button>
                    </div>

                    <div class="flex md:hidden items-center justify-start gap-1.5 px-3 py-2 border-b border-border/50 overflow-x-auto ss-scroll">
                        @foreach($weekDays as $i => $day)
                            <button type="button" @click="currentDayIndex = {{ $i }}" data-elevated="false"
                                    :class="currentDayIndex === {{ $i }} ? '{{ $day['is_rest_day'] ? 'bg-success w-4' : 'bg-info w-4' }}' : 'bg-border w-1.5'"
                                    class="btn-press h-1.5 rounded-full transition-all duration-300"></button>
                        @endforeach
                    </div>

                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full min-w-[720px] text-xs">
                            <thead>
                            <tr class="text-[11px] text-muted bg-secondary">
                                @foreach($weekDays as $day)
                                    <th class="border-l border-border px-3 py-3 last:border-l-0 {{ $day['is_rest_day'] ? 'bg-success/5' : '' }}">
                                        <div class="font-semibold {{ $day['is_rest_day'] ? 'text-success' : 'text-foreground' }}">
                                            {{ $day['name'] }}
                                            @if($day['is_rest_day'])
                                                <span class="block text-[9px] mt-1 px-2 py-0.5 rounded-full bg-success/15 text-success inline-block">استراحت</span>
                                            @endif
                                        </div>
                                        <div class="mt-1 text-[11px] text-muted">{{ $day['jalali_date'] }}</div>
                                    </th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                @foreach($weekDays as $day)
                                    <td class="min-w-[300px] border-l border-border px-2 py-3 align-top last:border-l-0 {{ $day['is_rest_day'] ? 'bg-success/5' : '' }}">
                                        @include('livewire.client.profile.consultation.partials.day-parts-content', ['day' => $day])
                                    </td>
                                @endforeach
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="block md:hidden">
                        @foreach($weekDays as $i => $day)
                            <div x-show="currentDayIndex === {{ $i }}"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-x-2"
                                 x-transition:enter-end="opacity-100 translate-x-0"
                                 class="px-3 py-3">
                                <div class="mb-3 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1 h-6 rounded-full {{ $day['is_rest_day'] ? 'bg-success' : 'bg-info' }}"></div>
                                        <div>
                                            <span class="text-sm font-bold text-foreground">{{ $day['name'] }}</span>
                                            <span class="text-[11px] text-muted mr-2">{{ $day['jalali_date'] }}</span>
                                        </div>
                                        @if($day['is_rest_day'])
                                            <span class="rounded-full text-[10px] px-2 py-0.5 bg-success/15 text-success">استراحت</span>
                                        @endif
                                    </div>
                                    @if(!$day['is_rest_day'] && $day['parts']->count() > 0)
                                        <div class="text-[11px] text-muted text-left">
                                            <span class="font-medium text-foreground">{{ $day['total_hours'] }}</span> ساعت ·
                                            <span class="font-medium text-foreground">{{ $day['total_tests'] }}</span> تست
                                        </div>
                                    @endif
                                </div>
                                @include('livewire.client.profile.consultation.partials.day-parts-content', ['day' => $day])
                            </div>
                        @endforeach
                    </div>
                </section>

                <section x-show="tab === 'archive'" x-cloak class="rounded-2xl border border-border glass p-4 sm:p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-foreground">آرشیو مطالعه این برنامه</h3>
                        <span class="text-[11px] text-muted">{{ jdate($program->start_date)->format('Y/m/d') }} تا {{ jdate($program->end_date)->format('Y/m/d') }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                        <div class="rounded-xl border border-border bg-success/5 p-4 text-center">
                            <div class="text-2xl font-extrabold text-success">{{ $archiveSummary['done'] }}</div>
                            <div class="text-[11px] text-muted mt-1">پارت ثبت‌شده از {{ $archiveSummary['total'] }}</div>
                        </div>
                        <div class="rounded-xl border border-border bg-error/5 p-4 text-center">
                            <div class="text-2xl font-extrabold text-error">{{ $archiveSummary['missed'] }}</div>
                            <div class="text-[11px] text-muted mt-1">پارت ثبت‌نشده (گذشته)</div>
                        </div>
                        <div class="rounded-xl border border-border bg-info/5 p-4 text-center">
                            <div class="text-lg font-extrabold text-info">{{ $this->formatDuration($archiveSummary['studied_seconds']) }}</div>
                            <div class="text-[11px] text-muted mt-1">مجموع مطالعه ثبت‌شده</div>
                        </div>
                        <div class="rounded-xl border border-border bg-info/5 p-4 text-center">
                            <div class="text-2xl font-extrabold text-info">{{ $archiveSummary['makeup_count'] }}</div>
                            <div class="text-[11px] text-muted mt-1">مطالعه جبرانی ({{ $this->formatDuration($archiveSummary['makeup_seconds']) }})</div>
                        </div>
                    </div>
                </section>

            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════ مودال‌ها ════════════════════════════════════════════════════════════ --}}

        {{-- مودال دسترسی --}}
        <div x-cloak x-show="permissionModal" class="fixed inset-0 z-[150] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full sm:max-w-md glass border-t sm:border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-8">
                <div class="sm:hidden flex justify-center pt-3 pb-1"><div class="w-10 h-1 rounded-full bg-foreground/20"></div></div>
                <div class="flex items-center justify-between px-6 py-4 border-b border-border">
                    <h3 class="font-bold text-foreground">درخواست دسترسی</h3>
                    <button @click="permissionModal=false" class="btn-press text-muted hover:text-foreground" data-elevated="false">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="px-6 py-5">
                    <div class="rounded-xl p-4 text-sm space-y-2 bg-info/5 border border-info/20 text-info">
                        <div class="font-semibold mb-2 text-info">چرا این دسترسی‌ها نیاز است؟</div>
                        <div>• دسترسی به صدا برای پخش آلارم هنگام پایان تایمر</div>
                        <div>• دسترسی به نوتیفیکیشن برای یادآوری‌های مطالعه</div>
                    </div>
                </div>
                <div class="flex justify-end px-6 py-4 border-t border-border">
                    <button wire:click="permissionUnderstood" x-on:click="window.unlockStudyPermissions && window.unlockStudyPermissions()" data-elevated="true"
                            wire:loading.attr="disabled" wire:target="permissionUnderstood"
                            class="btn-press px-6 h-11 rounded-xl font-semibold bg-primary text-primary-foreground hover:bg-primary/90 disabled:opacity-60 inline-flex items-center justify-center gap-2 min-w-[120px]">
                        <span wire:loading.remove wire:target="permissionUnderstood">متوجه شدم</span>
                        <x-ui.spinner size="sm" wire:loading wire:target="permissionUnderstood"/>
                    </button>
                </div>
            </div>
        </div>

        {{-- مودال پایان پارت --}}
        <div x-cloak x-show="finishModal" class="fixed inset-0 z-[130] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full sm:max-w-md glass border-t-2 sm:border-2 rounded-t-3xl sm:rounded-2xl shadow-2xl pb-[env(safe-area-inset-bottom,0px)] sm:pb-0 {{ $isInExtraPhase ? 'border-info' : 'border-success' }}"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-8">
                <div class="sm:hidden flex justify-center pt-3 pb-1"><div class="w-10 h-1 rounded-full bg-foreground/20"></div></div>
                <div class="px-6 py-8 text-center space-y-4">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto animate-bounce {{ $isInExtraPhase ? 'bg-info' : 'bg-success' }}">
                        <x-ui.icon name="check" class="w-9 h-9 text-white"/>
                    </div>
                    <h3 class="text-2xl font-black {{ $isInExtraPhase ? 'text-info' : 'text-success' }}">
                        @if($isInExtraPhase) اضافه بر مشاور تمام شد @elseif($pendingIsEarlyFinish) پایان زودهنگام @else آفرین! 🎉 @endif
                    </h3>
                    <p class="font-bold text-foreground">
                        @if($isInExtraPhase) تایم مطالعه اضافه بر مشاور به پایان رسید @else تایم مطالعه به پایان رسید @endif
                    </p>
                    <div class="flex gap-3 justify-center pt-2">
                        <button wire:click="closeFinishModal" wire:loading.attr="disabled" wire:target="closeFinishModal" data-elevated="false"
                                class="btn-press px-6 h-11 rounded-xl font-semibold text-sm bg-secondary text-muted border border-border disabled:opacity-60 inline-flex items-center justify-center gap-2 min-w-[80px]">
                            <span wire:loading.remove wire:target="closeFinishModal">بستن</span>
                            <x-ui.spinner size="sm" wire:loading wire:target="closeFinishModal"/>
                        </button>
                        <button wire:click="savePart" wire:loading.attr="disabled" wire:target="savePart" data-elevated="true"
                                class="btn-press px-8 h-11 rounded-xl font-semibold text-sm {{ $isInExtraPhase ? 'bg-info text-info-foreground' : 'bg-success text-success-foreground' }} disabled:opacity-60 inline-flex items-center justify-center gap-2 min-w-[110px]">
                            <span wire:loading.remove wire:target="savePart">ثبت پارت</span>
                            <x-ui.spinner size="sm" wire:loading wire:target="savePart"/>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- مودال تایید لغو --}}
        <div x-cloak x-show="cancelModal" class="fixed inset-0 z-[135] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full sm:max-w-md glass border-t-2 sm:border-2 border-error rounded-t-3xl sm:rounded-2xl shadow-2xl pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-8">
                <div class="sm:hidden flex justify-center pt-3 pb-1"><div class="w-10 h-1 rounded-full bg-foreground/20"></div></div>
                <div class="px-6 py-8 text-center space-y-4">
                    <h3 class="text-xl font-black text-foreground">لغو تایمر؟</h3>
                    <p class="text-sm text-muted">با لغو تایمر، زمان مطالعه فعلی ثبت نمی‌شود. مطمئنی؟</p>
                    <div class="flex gap-3 justify-center pt-2">
                        <button @click="cancelModal=false" class="btn-press px-6 h-11 rounded-xl font-semibold text-sm bg-secondary text-muted border border-border" data-elevated="false">ادامه مطالعه</button>
                        @if($isMakeupMode)
                            <button type="button" data-elevated="true"
                                    wire:click.prevent="cancelMakeup"
                                    wire:loading.attr="disabled"
                                    wire:target="cancelMakeup"
                                    class="btn-press px-8 h-11 rounded-xl font-semibold text-sm bg-error text-error-foreground disabled:opacity-60 inline-flex items-center justify-center gap-2 min-w-[120px]">
                                <span wire:loading.remove wire:target="cancelMakeup">بله، لغو کن</span>
                                <x-ui.spinner size="sm" wire:loading wire:target="cancelMakeup"/>
                            </button>
                        @else
                            <button type="button" data-elevated="true"
                                    wire:click.prevent="cancelPart"
                                    wire:loading.attr="disabled"
                                    wire:target="cancelPart"
                                    class="btn-press px-8 h-11 rounded-xl font-semibold text-sm bg-error text-error-foreground disabled:opacity-60 inline-flex items-center justify-center gap-2 min-w-[120px]">
                                <span wire:loading.remove wire:target="cancelPart">بله، لغو کن</span>
                                <x-ui.spinner size="sm" wire:loading wire:target="cancelPart"/>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- مودال زودتر تمام کردم --}}
        <div x-cloak x-show="earlyModal" class="fixed inset-0 z-[135] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full sm:max-w-md glass border-t-2 sm:border-2 border-success rounded-t-3xl sm:rounded-2xl shadow-2xl pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-8">
                <div class="sm:hidden flex justify-center pt-3 pb-1"><div class="w-10 h-1 rounded-full bg-foreground/20"></div></div>
                <div class="px-6 py-8 text-center space-y-4">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto bg-success/15">
                        <svg viewBox="0 0 24 24" fill="#10b981" class="w-9 h-9"><path d="M11.983 1.907a.75.75 0 00-1.292-.657l-8.5 9.5A.75.75 0 002.75 12H6v6.5a.75.75 0 001.292.657l8.5-9.5A.75.75 0 0015.25 8H12V1.907z"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-foreground">مطمئنی؟</h3>
                    <p class="text-sm text-muted">با تأیید، این پارت با مدت فعلی به‌عنوان «زودتر تمام شد» ثبت می‌شود.</p>
                    <p class="text-xs text-muted">مدت ثبت‌شده: <span class="text-foreground font-bold">{{ $this->formatClock($liveSeconds) }}</span> از {{ $this->formatClock($targetSeconds) }}</p>
                    <div class="flex gap-3 justify-center pt-2">
                        <button @click="earlyModal=false" class="btn-press px-6 h-11 rounded-xl font-semibold text-sm bg-secondary text-muted border border-border" data-elevated="false">انصراف</button>
                        <button wire:click="confirmEarlyFinish" wire:loading.attr="disabled" wire:target="confirmEarlyFinish"
                                class="btn-press px-8 h-11 rounded-xl font-semibold text-sm bg-success text-success-foreground disabled:opacity-60 inline-flex items-center justify-center gap-2 min-w-[110px]" data-elevated="true">
                            <span wire:loading.remove wire:target="confirmEarlyFinish">بله، ثبت کن</span>
                            <x-ui.spinner size="sm" wire:loading wire:target="confirmEarlyFinish"/>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- مودال مطالعه بیشتر --}}
        <div x-cloak x-show="studyMoreModal" class="fixed inset-0 z-[135] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full sm:max-w-md glass border-t-2 sm:border-2 border-info rounded-t-3xl sm:rounded-2xl shadow-2xl pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-8">
                <div class="sm:hidden flex justify-center pt-3 pb-1"><div class="w-10 h-1 rounded-full bg-foreground/20"></div></div>
                <div class="flex items-center justify-between px-6 py-4 border-b border-border">
                    <h3 class="font-bold text-foreground">مطالعه بیشتر (اضافه بر مشاور)</h3>
                    <button @click="studyMoreModal=false" class="btn-press text-muted hover:text-foreground" data-elevated="false">
                        <svg class="w-5 h-5" stroke="currentColor" fill="none" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="px-6 py-5 space-y-5">
                    <p class="text-sm text-muted">پس از پایان تایمر فعلی، یک تایمر اضافی به مدت زیر آغاز می‌شود. حداکثر ۳ ساعت.</p>
                    {{--
                        قبلاً استپرِ +/- با اینپوتِ عددی بود (ظاهرش کمی زشت بود)؛
                        حالا از همان چرخِ ساعت/دقیقهٔ آیفونی‌ای که در
                        _sudden-event-content.blade.php ساخته شده استفاده می‌شود.
                        x-data بیرونی فقط برای پیامِ اعتبارسنجیِ زیرِ چرخ نگه داشته
                        شده (چون duration-wheel-picker خودش یک @entangle مستقل و
                        هم‌زمان با همین مقادیر دارد).
                    --}}
                    <div x-data="{ h: @entangle('studyMoreHours'), m: @entangle('studyMoreMinutes'), total() { return (parseInt(this.h)||0)*60 + (parseInt(this.m)||0); } }" class="space-y-3">
                        <x-ui.duration-wheel-picker
                            hours-model="studyMoreHours"
                            minutes-model="studyMoreMinutes"
                            :max-hours="3"
                            :minute-step="5"/>
                        <div class="text-center text-xs">
                            <template x-if="total() > 180"><span class="text-error">حداکثر ۳ ساعت — به ۳ ساعت محدود می‌شود.</span></template>
                            <template x-if="total() < 1"><span class="text-error">حداقل ۱ دقیقه را انتخاب کنید.</span></template>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-border">
                    <button @click="studyMoreModal=false" class="btn-press px-5 h-10 rounded-xl font-semibold text-sm bg-secondary text-muted border border-border" data-elevated="false">انصراف</button>
                    <button wire:click="confirmStudyMore" wire:loading.attr="disabled" wire:target="confirmStudyMore"
                            class="btn-press px-6 h-10 rounded-xl font-semibold text-sm bg-info text-info-foreground disabled:opacity-60 inline-flex items-center justify-center gap-2 min-w-[150px]" data-elevated="true">
                        <span wire:loading.remove wire:target="confirmStudyMore">شروع پس از پایان</span>
                        <x-ui.spinner size="sm" wire:loading wire:target="confirmStudyMore"/>
                    </button>
                </div>
            </div>
        </div>

        {{-- مودال پایان جبرانی --}}
        <div x-cloak x-show="makeupFinishModal" class="fixed inset-0 z-[130] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full sm:max-w-md glass border-t-2 sm:border-2 border-info rounded-t-3xl sm:rounded-2xl shadow-2xl pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-8">
                <div class="sm:hidden flex justify-center pt-3 pb-1"><div class="w-10 h-1 rounded-full bg-foreground/20"></div></div>
                <div class="px-6 py-8 text-center space-y-4">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto animate-bounce bg-info">
                        <x-ui.icon name="check" class="w-9 h-9 text-white"/>
                    </div>
                    <h3 class="text-2xl font-black text-info">عالی! 🎉</h3>
                    <p class="font-bold text-foreground">تایمر جبرانی به پایان رسید</p>
                    <div class="flex gap-3 justify-center pt-2">
                        <button wire:click="closeMakeupFinishModal" class="btn-press px-6 h-11 rounded-xl font-semibold text-sm bg-secondary text-muted border border-border" data-elevated="false">بستن</button>
                        <button wire:click="saveMakeupSession" wire:loading.attr="disabled" wire:target="saveMakeupSession"
                                class="btn-press px-8 h-11 rounded-xl font-semibold text-sm bg-info text-info-foreground disabled:opacity-60 inline-flex items-center justify-center gap-2 min-w-[120px]" data-elevated="true">
                            <span wire:loading.remove wire:target="saveMakeupSession">ثبت مطالعه</span>
                            <x-ui.spinner size="sm" wire:loading wire:target="saveMakeupSession"/>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- مودال بازخورد --}}
        <div x-cloak x-show="feedbackModal" class="fixed inset-0 z-[140] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full sm:max-w-md glass border-t sm:border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-8">
                <div class="sm:hidden flex justify-center pt-3 pb-1"><div class="w-10 h-1 rounded-full bg-foreground/20"></div></div>
                <div class="flex items-center justify-between px-6 py-4 border-b border-border">
                    <h3 class="font-bold text-foreground">بازخورد مطالعه</h3>
                </div>
                <div class="px-6 py-5 space-y-5">
                    @if($pendingFeedbackPartName)
                        <div class="rounded-xl p-3 text-sm bg-info/5 border border-info/20">
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $pendingFeedbackType==='part' ? 'bg-warning/15 text-warning' : 'bg-info/15 text-info' }}">
                                {{ $pendingFeedbackType==='part' ? 'پارت برنامه' : 'اضافه بر سازمان' }}
                            </span>
                            <div class="font-bold text-foreground mt-2">{{ $pendingFeedbackPartName }}</div>
                        </div>
                    @endif
                    <div class="text-center">
                        <p class="text-sm mb-3 text-muted">کیفیت مطالعه را امتیاز دهید</p>
                        <div class="flex items-center justify-center gap-1" dir="ltr">
                            @for($i=1; $i<=10; $i++)
                                <button type="button" wire:click="setFeedbackRating({{ $i }})" data-elevated="false" class="btn-press transition-transform hover:scale-125 {{ $feedbackRating>=$i ? 'text-warning' : 'text-border' }}">
                                    <svg viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd"/></svg>
                                </button>
                            @endfor
                        </div>
                        @if($feedbackRating > 0)
                            <div class="text-xs font-bold mt-2 {{ $feedbackRating>=9 ? 'text-success' : ($feedbackRating>=7 ? 'text-info' : ($feedbackRating>=5 ? 'text-warning' : 'text-error')) }}">
                                @if($feedbackRating>=9) عالی @elseif($feedbackRating>=7) خوب @elseif($feedbackRating>=5) متوسط @else ضعیف @endif ({{ $feedbackRating }}/10)
                            </div>
                        @endif
                    </div>
                    <textarea wire:model="feedbackComment" rows="2" class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none resize-none bg-secondary border border-border text-foreground" placeholder="نظر یا پیشنهاد..."></textarea>
                </div>
                <div class="flex justify-end px-6 py-4 border-t border-border">
                    <button wire:click="submitFeedback" wire:loading.attr="disabled" wire:target="submitFeedback" data-elevated="true"
                            class="btn-press px-6 h-11 rounded-xl font-semibold text-sm text-primary-foreground disabled:opacity-60 inline-flex items-center justify-center gap-2 min-w-[120px] {{ $feedbackRating<1 ? 'bg-secondary text-muted cursor-not-allowed' : 'bg-primary hover:bg-primary/90' }}"
                        {{ $feedbackRating<1 ? 'disabled' : '' }}>
                        <span wire:loading.remove wire:target="submitFeedback">ثبت بازخورد</span>
                        <x-ui.spinner size="sm" wire:loading wire:target="submitFeedback"/>
                    </button>
                </div>
            </div>
        </div>

        {{-- مودال جبرانی --}}
        <div x-cloak x-show="makeupModal" class="fixed inset-0 z-[145] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full sm:max-w-lg glass border-t sm:border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-8">
                <div class="sm:hidden flex justify-center pt-3 pb-1"><div class="w-10 h-1 rounded-full bg-foreground/20"></div></div>
                <div class="flex items-center justify-between px-6 py-4 sticky top-0 glass border-b border-border z-[1]">
                    <h3 class="font-bold text-foreground text-sm">ثبت مطالعه مازاد بر برنامه مشاور</h3>
                    <button wire:click="closeMakeupModal" class="btn-press text-muted hover:text-foreground" data-elevated="false">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                {{-- نشانگر بارگذاری هنگام واکشی اطلاعات آبشاری --}}
                <div wire:loading.flex wire:target="makeupSearch,makeupGradeId,makeupSubjectId,makeupChapterId,selectSearchResult"
                     class="items-center justify-center gap-2 py-2 bg-info/5 border-b border-border text-xs font-semibold text-info">
                    <x-ui.spinner size="xs"/>
                    در حال بارگذاری اطلاعات...
                </div>
                <div class="px-6 py-5 space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1.5">جستجوی سریع</label>
                        <input type="text" wire:model.live.debounce.300ms="makeupSearch"
                               class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none bg-secondary border border-border text-foreground" placeholder="نام درس یا فصل...">
                        @if(mb_strlen($makeupSearch) >= 2 && $this->searchResults->isNotEmpty())
                            <div class="mt-1 rounded-xl border border-border bg-secondary max-h-52 overflow-y-auto">
                                @foreach($this->searchResults as $r)
                                    <button type="button" wire:click="selectSearchResult('{{ $r['type'] }}', {{ $r['id'] }})" data-elevated="false"
                                            class="btn-press w-full text-right px-4 py-3 text-sm flex items-start gap-2 hover:bg-background transition border-b border-border last:border-0">
                                        <span class="text-xs font-bold px-1.5 py-0.5 rounded-md mt-0.5 flex-shrink-0 bg-info/10 text-info">
                                            فصل
                                        </span>
                                        <div class="min-w-0">
                                            <div class="font-semibold text-foreground truncate">{{ $r['name'] }}</div>
                                            <div class="text-xs text-muted truncate">{{ $r['label'] }}</div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="border-t border-border pt-4 space-y-3">
                        @if($this->grades->isNotEmpty())
                            <div>
                                <label class="block text-xs font-semibold text-foreground mb-1">پایه تحصیلی</label>
                                <x-ui.select wire:model.live="makeupGradeId" wire:key="select-grade"
                                             :options="$this->grades->map(fn($g)=>['id'=>$g->id,'name'=>$g->name])->values()->toArray()"
                                             value-key="id" label-key="name" placeholder="انتخاب پایه..."/>
                            </div>
                        @endif
                        @if($makeupGradeId)
                            <div>
                                <label class="block text-xs font-semibold text-foreground mb-1">درس</label>
                                <x-ui.select wire:model.live="makeupSubjectId" wire:key="select-subject-{{ $makeupGradeId }}"
                                             :options="$this->subjects->map(fn($s)=>['id'=>$s->id,'name'=>$s->name])->values()->toArray()"
                                             value-key="id" label-key="name" placeholder="انتخاب درس..."/>
                            </div>
                        @endif
                        @if($makeupSubjectId)
                            <div>
                                <label class="block text-xs font-semibold text-foreground mb-1">فصل</label>
                                <x-ui.select wire:model.live="makeupChapterId" wire:key="select-chapter-{{ $makeupSubjectId }}"
                                             :options="$this->chapters->map(fn($c)=>['id'=>$c->id,'name'=>$c->name])->values()->toArray()"
                                             value-key="id" label-key="name" placeholder="انتخاب فصل..."/>
                            </div>
                        @endif
                    </div>
                    <div class="border-t border-border pt-4">
                        <label class="block text-xs font-semibold text-foreground mb-2">نوع مطالعه</label>
                        <div class="grid grid-cols-3 gap-2">
                            {{--
                                ✅ FIX: قبلاً کلاس‌های Tailwind با interpolate کردن نام رنگ توی PHP
                                ساخته می‌شدن ("bg-$clr-500/15")؛ چون Tailwind فقط رشته‌های کامل و
                                ایستا رو موقع build اسکن می‌کنه، این کلاس‌ها هیچ‌وقت واقعاً تولید
                                نمی‌شدن و این دکمه‌ها در حالت انتخاب‌شده بی‌رنگ می‌موندن. حالا هر
                                حالت کلاس کامل و ایستای خودش رو داره.
                            --}}
                            @php
                                $makeupTypeTokens = [
                                    'test'        => 'bg-info/15 text-info border-info/50',
                                    'descriptive' => 'bg-success/15 text-success border-success/50',
                                    'video'       => 'bg-warning/15 text-warning border-warning/50',
                                ];
                            @endphp
                            @foreach([['test','تستی'],['descriptive','تشریحی'],['video','ویدیویی']] as [$val,$lbl])
                                <button type="button" wire:click="$set('makeupPartType','{{ $val }}')" data-elevated="false"
                                        class="btn-press py-3 rounded-xl font-semibold text-sm transition border-2 {{ $makeupPartType===$val ? $makeupTypeTokens[$val] : 'bg-secondary text-muted border-border' }}">
                                    {{ $lbl }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <div class="border-t border-border pt-4">
                        <label class="block text-xs font-semibold text-foreground mb-2">مدت زمان</label>
                        {{-- قبلاً استپرِ +/- با اینپوتِ عددی بود؛ حالا همان چرخِ ساعت/دقیقهٔ آیفونی --}}
                        <x-ui.duration-wheel-picker
                            hours-model="makeupDurationHours"
                            minutes-model="makeupDurationMinutes"
                            :max-hours="24"
                            :minute-step="1"/>
                    </div>
                    <textarea wire:model="makeupNote" rows="2" class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none resize-none bg-secondary border border-border text-foreground" placeholder="یادداشت (اختیاری)..."></textarea>
                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 sticky bottom-0 glass border-t border-border">
                    <button wire:click="closeMakeupModal" class="btn-press px-5 h-10 rounded-xl font-semibold text-sm bg-secondary text-muted border border-border" data-elevated="false">انصراف</button>
                    <button wire:click="startMakeupTimer" wire:loading.attr="disabled" wire:target="startMakeupTimer"
                            class="btn-press px-6 h-10 rounded-xl font-semibold text-sm disabled:opacity-60 inline-flex items-center justify-center gap-2 min-w-[120px] {{ !$makeupChapterId ? 'bg-secondary text-muted cursor-not-allowed' : 'bg-info text-info-foreground' }}" data-elevated="true"
                        {{ !$makeupChapterId ? 'disabled' : '' }}>
                        <span wire:loading.remove wire:target="startMakeupTimer">شروع تایمر</span>
                        <x-ui.spinner size="sm" wire:loading wire:target="startMakeupTimer"/>
                    </button>
                </div>
            </div>
        </div>

        {{-- مودال آلارم --}}
        <div x-cloak x-show="alarmModal" class="fixed inset-0 z-[200] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full sm:max-w-md glass border-t sm:border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-8">
                <div class="sm:hidden flex justify-center pt-3 pb-1"><div class="w-10 h-1 rounded-full bg-foreground/20"></div></div>
                <div class="flex items-center justify-between px-6 py-4 border-b border-border">
                    <h3 class="font-bold text-foreground">انتخاب صدای آلارم</h3>
                    <button @click="alarmModal=false; window.stopAlarmPreview && window.stopAlarmPreview()" class="btn-press text-muted hover:text-foreground" data-elevated="false">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="px-6 py-4 space-y-2">
                    @php $alarms = [['id'=>'Alarmclock','label'=>'زنگ کلاسیک','desc'=>'صدای زنگ سنتی'],['id'=>'Funny','label'=>'زنگ بامزه','desc'=>'آلارم شاد و بامزه'],['id'=>'Modern','label'=>'آلارم مدرن','desc'=>'صدای آلارم امروزی'],['id'=>'Loud','label'=>'آلارم بلند','desc'=>'صدای بلند و کشیده']]; @endphp
                    @foreach($alarms as $alarm)
                        <div wire:key="alarm-option-{{ $alarm['id'] }}"
                             wire:click="setAlarm('{{ $alarm['id'] }}')"
                             @click="selectedAlarmState = '{{ $alarm['id'] }}'"
                             class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-2xl cursor-pointer transition border text-right"
                             :class="selectedAlarmState === '{{ $alarm['id'] }}' ? 'bg-info/10 border-info/40' : 'bg-secondary border-border'">
                            <div class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                     :class="selectedAlarmState === '{{ $alarm['id'] }}' ? 'border-info bg-info' : 'border-muted'">
                                    <div x-show="selectedAlarmState === '{{ $alarm['id'] }}'" x-cloak class="w-2 h-2 rounded-full bg-white"></div>
                                </div>
                                <div>
                                    <div class="font-semibold text-foreground text-sm">{{ $alarm['label'] }}</div>
                                    <div class="text-xs text-muted">{{ $alarm['desc'] }}</div>
                                </div>
                            </div>
                            <span class="flex items-center gap-2 flex-shrink-0">
                                <span class="text-[11px] font-medium"
                                      :class="previewingAlarmId === '{{ $alarm['id'] }}' ? 'text-warning' : 'text-info'"
                                      x-text="previewingAlarmId === '{{ $alarm['id'] }}' ? 'توقف' : 'پخش'"></span>
                                {{-- استثنا: آیکون‌های play/pause در دیکشنری موجود نیستن --}}
                                <button type="button" data-elevated="false"
                                        @click.stop="window.toggleAlarmPreview && window.toggleAlarmPreview('{{ $alarm['id'] }}')"
                                        class="btn-press w-9 h-9 rounded-full flex items-center justify-center bg-info/10 border border-info/30 text-info">
                                    <svg x-show="previewingAlarmId !== '{{ $alarm['id'] }}'" x-cloak fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z"/>
                                    </svg>
                                    <svg x-show="previewingAlarmId === '{{ $alarm['id'] }}'" x-cloak fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4 text-warning">
                                        <path d="M7.5 5.25A1.125 1.125 0 006.375 6.375v11.25A1.125 1.125 0 007.5 18.75h1.5a1.125 1.125 0 001.125-1.125V6.375A1.125 1.125 0 009 5.25H7.5zm7.5 0a1.125 1.125 0 00-1.125 1.125v11.25A1.125 1.125 0 0015 18.75h1.5a1.125 1.125 0 001.125-1.125V6.375A1.125 1.125 0 0016.5 5.25H15z"/>
                                    </svg>
                                </button>
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 border-t border-border">
                    <p class="text-xs text-center text-muted">فقط آخرین صدای انتخاب‌شده در مرورگر ذخیره می‌شود و با انتخاب جدید، جای قبلی را می‌گیرد</p>
                </div>
            </div>
        </div>

    </div>
    @script
    <script>
        (() => {
            const CLEANUP_KEY = '__weeklyProgramTimerCleanup'
            if (typeof window[CLEANUP_KEY] === 'function') {
                window[CLEANUP_KEY]()
            }

            const ALARMS = [
                { id: 'Alarmclock', src: '/client/sounds/Alarmclock.ogg' },
                { id: 'Funny',      src: '/client/sounds/Funny.mp3' },
                { id: 'Modern',     src: '/client/sounds/Modern.mp3' },
                { id: 'Loud',       src: '/client/sounds/alarm.wav' },
            ]

            const RING_CIRCUMFERENCE = 816.81
            const FALLBACK_ALARM_SRC = '/client/sounds/Alarmclock.ogg'
            const SELECTED_ALARM_KEY = 'selected_alarm'
            const ROOT_SELECTOR = '[data-weekly-program-view]'
            const initialRoot = document.querySelector(`${ROOT_SELECTOR}[wire\\:id]`) || document.querySelector(ROOT_SELECTOR)
            const componentWire = (() => {
                try {
                    return typeof $wire !== 'undefined' ? $wire : null
                } catch (e) {
                    return null
                }
            })()
            const audioCache = {}
            const cleanups = []
            let previewAudio = null
            let previewAudioId = null
            let activeAlarmAudio = null

            let clientTimerInterval = null
            let syncInterval = null
            let componentAlive = true
            let sampleQuestionStartTimer = null

            let lastSyncedEndsAt = null
            let lastMakeupEndsAt = null
            let lastExtraEndsAt = null

            let alarmFired = false
            let makeupAlarmFired = false
            let extraAlarmFired = false

            function getTimerRoot() {
                if (initialRoot?.isConnected) return initialRoot

                return document.querySelector(`${ROOT_SELECTOR}[wire\\:id]`) || document.querySelector(ROOT_SELECTOR)
            }

            function getLiveWireProxy() {
                if (!componentAlive || !componentWire) return null

                const root = getTimerRoot()

                if (!root?.isConnected) {
                    componentAlive = false
                    return null
                }

                return componentWire
            }

            function addSafeListener(target, eventName, handler, options = undefined) {
                if (!target || typeof target.addEventListener !== 'function') return

                target.addEventListener(eventName, handler, options)

                cleanups.push(() => {
                    try {
                        target.removeEventListener(eventName, handler, options)
                    } catch (e) {
                        console.warn(e)
                    }
                })
            }

            function normalizeTs(value) {
                if (value === null || value === undefined || value === '') return null

                const numberValue = Number(value)

                return Number.isFinite(numberValue) ? numberValue : null
            }

            function getWireValue(key, fallback = null) {
                try {
                    const wire = getLiveWireProxy()

                    if (!wire) return fallback

                    return wire[key] ?? fallback
                } catch (e) {
                    return fallback
                }
            }

            function safeWireCall(method, ...args) {
                try {
                    const wire = getLiveWireProxy()

                    if (!wire) return

                    if (typeof wire.call === 'function') {
                        return wire.call(method, ...args)
                    }

                    if (typeof wire[method] === 'function') {
                        return wire[method](...args)
                    }
                } catch (e) {
                    console.warn(e)
                }
            }

            function safeWireCallDebounced(method, delay = 50, ...args) {
                setTimeout(() => {
                    safeWireCall(method, ...args)
                }, delay)
            }

            function getEventData(event) {
                return Array.isArray(event.detail) ? event.detail[0] : event.detail
            }

            function markAlreadyFinishedAlarms() {
                const now = Math.floor(Date.now() / 1000)

                lastSyncedEndsAt = normalizeTs(getWireValue('endsAtTs'))
                lastMakeupEndsAt = normalizeTs(getWireValue('makeupEndsAtTs'))
                lastExtraEndsAt = normalizeTs(getWireValue('extraEndsAtTs'))

                alarmFired = !!(lastSyncedEndsAt && now >= lastSyncedEndsAt)
                makeupAlarmFired = !!(lastMakeupEndsAt && now >= lastMakeupEndsAt)
                extraAlarmFired = !!(lastExtraEndsAt && now >= lastExtraEndsAt)
            }

            function refreshAlarmFlagsIfEndsChanged() {
                const now = Math.floor(Date.now() / 1000)

                const endsAt = normalizeTs(getWireValue('endsAtTs'))
                const makeupEndsAt = normalizeTs(getWireValue('makeupEndsAtTs'))
                const extraEndsAt = normalizeTs(getWireValue('extraEndsAtTs'))

                if (endsAt !== lastSyncedEndsAt) {
                    lastSyncedEndsAt = endsAt
                    alarmFired = !!(endsAt && now >= endsAt)
                }

                if (makeupEndsAt !== lastMakeupEndsAt) {
                    lastMakeupEndsAt = makeupEndsAt
                    makeupAlarmFired = !!(makeupEndsAt && now >= makeupEndsAt)
                }

                if (extraEndsAt !== lastExtraEndsAt) {
                    lastExtraEndsAt = extraEndsAt
                    extraAlarmFired = !!(extraEndsAt && now >= extraEndsAt)
                }
            }

            function preloadAlarms() {
                ALARMS.forEach((alarm) => {
                    try {
                        const audio = new Audio(alarm.src)

                        audio.preload = 'auto'

                        audio.addEventListener('error', () => {
                            audio.src = FALLBACK_ALARM_SRC
                            audio.load()
                        })

                        audio.load()
                        audioCache[alarm.id] = audio
                    } catch (e) {
                        console.warn(e)
                    }
                })
            }

            function emitAlarmPreviewState(id = null) {
                window.dispatchEvent(new CustomEvent('alarm-preview-state', {
                    detail: { id }
                }))
            }

            function stopAudioInstance(audio) {
                if (!audio) return

                try {
                    audio.pause()
                    audio.currentTime = 0
                } catch (e) {
                    console.warn(e)
                }
            }

            function stopActiveAlarmPlayback() {
                stopAudioInstance(activeAlarmAudio)
                activeAlarmAudio = null
            }

            function stopAlarmPreview() {
                stopAudioInstance(previewAudio)
                previewAudio = null
                previewAudioId = null
                emitAlarmPreviewState(null)

                return null
            }

            function buildAlarmAudio(id) {
                const entry = audioCache[id] || audioCache.Alarmclock
                const src = entry ? entry.src : FALLBACK_ALARM_SRC
                const audio = new Audio(src)

                audio.addEventListener('error', () => {
                    audio.src = FALLBACK_ALARM_SRC
                    audio.load()
                })

                return audio
            }

            function getSelectedAlarm() {
                const storedAlarm = localStorage.getItem(SELECTED_ALARM_KEY)
                return ALARMS.some((alarm) => alarm.id === storedAlarm) ? storedAlarm : 'Alarmclock'
            }

            function playAlarm() {
                const id = getSelectedAlarm()

                try {
                    stopAlarmPreview()
                    stopActiveAlarmPlayback()

                    const audio = buildAlarmAudio(id)
                    audio.volume = 1
                    activeAlarmAudio = audio

                    audio.play().catch(() => {
                        activeAlarmAudio = null
                        const fallbackAudio = new Audio(FALLBACK_ALARM_SRC)
                        fallbackAudio.volume = 1

                        fallbackAudio.play().catch((e) => {
                            console.warn('alarm play failed:', e)
                        })
                    })

                    if ('Notification' in window && Notification.permission === 'granted') {
                        new Notification('⏰ زمان مطالعه به پایان رسید!', {
                            body: 'پارت مطالعاتی شما تکمیل شد.',
                            icon: '/favicon.ico',
                        })
                    }
                } catch (e) {
                    console.warn(e)
                }
            }

            function formatClock(seconds) {
                seconds = Math.max(0, Number(seconds) || 0)

                const hours = Math.floor(seconds / 3600)
                const minutes = Math.floor((seconds % 3600) / 60)
                const secs = seconds % 60

                return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`
            }

            function updateClockDOM(id, seconds) {
                const element = document.getElementById(id)

                if (element) {
                    element.textContent = formatClock(seconds)
                }
            }

            function updateRingDOM(id, remaining, target) {
                const element = document.getElementById(id)

                if (!element || !target) return

                const ratio = Math.min(1, Math.max(0, remaining / target))

                element.style.strokeDashoffset = (RING_CIRCUMFERENCE * (1 - ratio)).toFixed(2)
            }

            function startClientTimer() {
                if (clientTimerInterval) {
                    clearInterval(clientTimerInterval)
                    clientTimerInterval = null
                }

                markAlreadyFinishedAlarms()

                clientTimerInterval = setInterval(() => {
                    if (!componentAlive || !getLiveWireProxy()) {
                        clearInterval(clientTimerInterval)
                        clientTimerInterval = null
                        return
                    }

                    refreshAlarmFlagsIfEndsChanged()

                    const now = Math.floor(Date.now() / 1000)

                    const endsAt = normalizeTs(getWireValue('endsAtTs'))
                    const isRunning = !!getWireValue('isRunning', false)
                    const isInExtra = !!getWireValue('isInExtraPhase', false)

                    if (!isInExtra && endsAt && isRunning) {
                        const remaining = Math.max(endsAt - now, 0)
                        const target = Number(getWireValue('targetSeconds', 0)) || 0

                        updateClockDOM('main-clock', remaining)
                        updateRingDOM('main-ring', remaining, target)

                        if (remaining === 0 && !alarmFired) {
                            alarmFired = true
                            playAlarm()
                            safeWireCall('syncTimers')
                        }
                    }

                    const extraEndsAt = normalizeTs(getWireValue('extraEndsAtTs'))

                    if (isInExtra && extraEndsAt && isRunning) {
                        const remaining = Math.max(extraEndsAt - now, 0)
                        const target = Number(getWireValue('extraTargetSeconds', 0)) || 0

                        updateClockDOM('extra-clock', remaining)
                        updateRingDOM('extra-ring', remaining, target)

                        if (remaining === 0 && !extraAlarmFired) {
                            extraAlarmFired = true
                            playAlarm()
                            safeWireCall('syncTimers')
                        }
                    }

                    const makeupEndsAt = normalizeTs(getWireValue('makeupEndsAtTs'))
                    const makeupRunning = !!getWireValue('makeupTimerRunning', false)

                    if (makeupEndsAt && makeupRunning) {
                        const remaining = Math.max(makeupEndsAt - now, 0)
                        const target = Number(getWireValue('makeupTargetSeconds', 0)) || 0

                        updateClockDOM('makeup-clock', remaining)
                        updateRingDOM('makeup-ring', remaining, target)

                        if (remaining === 0 && !makeupAlarmFired) {
                            makeupAlarmFired = true
                            playAlarm()
                            safeWireCall('syncTimers')
                        }
                    }
                }, 1000)
            }

            function startServerSync() {
                if (syncInterval) {
                    clearInterval(syncInterval)
                    syncInterval = null
                }

                syncInterval = setInterval(() => {
                    if (!componentAlive || !getLiveWireProxy()) {
                        clearInterval(syncInterval)
                        syncInterval = null
                        return
                    }

                    const isRunning = !!getWireValue('isRunning', false)
                    const makeupRunning = !!getWireValue('makeupTimerRunning', false)

                    if (componentAlive && (isRunning || makeupRunning)) {
                        safeWireCall('syncTimers')
                    }
                }, 5000)
            }

            /**
             * این تابع در Blade با کلیک کاربر صدا و Notification را unlock می‌کند.
             */
            window.unlockStudyPermissions = function () {
                try {
                    const audio = new Audio(FALLBACK_ALARM_SRC)
                    audio.volume = 0.01

                    audio.play()
                        .then(() => {
                            audio.pause()
                            audio.currentTime = 0
                        })
                        .catch(() => {})

                    if ('Notification' in window && Notification.permission === 'default') {
                        Notification.requestPermission().catch(() => {})
                    }
                } catch (e) {
                    console.warn(e)
                }
            }

            window.unlockStudyPermissions.__weeklyProgramTimer = true

            window.startSampleQuestionPart = function (partId, downloadUrl) {
                try {
                    if (sampleQuestionStartTimer) {
                        clearTimeout(sampleQuestionStartTimer)
                        sampleQuestionStartTimer = null
                    }

                    if (downloadUrl) {
                        const link = document.createElement('a')
                        link.href = downloadUrl
                        link.download = ''
                        link.target = '_blank'
                        link.rel = 'noopener'
                        document.body.appendChild(link)
                        link.click()
                        link.remove()
                    }

                    sampleQuestionStartTimer = setTimeout(() => {
                        sampleQuestionStartTimer = null
                        safeWireCall('startPart', partId)
                    }, 30000)
                } catch (e) {
                    console.warn(e)
                }
            }

            window.startSampleQuestionPart.__weeklyProgramTimer = true

            window.stopAlarmPreview = stopAlarmPreview
            window.stopAlarmPreview.__weeklyProgramTimer = true

            window.toggleAlarmPreview = function (id) {
                try {
                    if (previewAudioId === id && previewAudio && !previewAudio.paused) {
                        return stopAlarmPreview()
                    }

                    stopAlarmPreview()
                    stopActiveAlarmPlayback()

                    const audio = buildAlarmAudio(id)
                    audio.volume = 0.7
                    previewAudio = audio
                    previewAudioId = id
                    emitAlarmPreviewState(id)

                    audio.addEventListener('ended', () => {
                        if (previewAudio === audio) {
                            previewAudio = null
                            previewAudioId = null
                            emitAlarmPreviewState(null)
                        }
                    })

                    audio.play().catch((e) => {
                        stopAlarmPreview()
                        console.warn(e)
                    })

                    return id
                } catch (e) {
                    console.warn(e)
                    return stopAlarmPreview()
                }
            }

            window.toggleAlarmPreview.__weeklyProgramTimer = true

            addSafeListener(window, 'alarm-selected', (event) => {
                const data = getEventData(event)
                const alarm = data?.alarm || data

                if (alarm) {
                    localStorage.setItem(SELECTED_ALARM_KEY, alarm)
                }
            })

            addSafeListener(window, 'request-permissions', async () => {
                try {
                    if ('Notification' in window && Notification.permission !== 'granted') {
                        await Notification.requestPermission()
                    }

                    const audio = new Audio(ALARMS[0].src)
                    audio.volume = 0.01

                    await audio.play()

                    audio.pause()
                    audio.currentTime = 0
                } catch (e) {
                    console.warn(e)
                } finally {
                    safeWireCall('onPermissionsGranted')
                }
            })

            addSafeListener(window, 'play-alarm', () => {
                playAlarm()
            })

            addSafeListener(document, 'visibilitychange', () => {
                if (componentAlive && !document.hidden) {
                    safeWireCall('syncTimers')
                }
            })

            addSafeListener(window, 'focus', () => {
                if (componentAlive) {
                    safeWireCall('syncTimers')
                }
            })

            /**
             * موقع خروج با wire:navigate همه‌چیز را پاک می‌کنیم.
             */
            function cleanupWeeklyProgramTimer() {
                componentAlive = false

                if (clientTimerInterval) {
                    clearInterval(clientTimerInterval)
                    clientTimerInterval = null
                }

                if (syncInterval) {
                    clearInterval(syncInterval)
                    syncInterval = null
                }

                if (sampleQuestionStartTimer) {
                    clearTimeout(sampleQuestionStartTimer)
                    sampleQuestionStartTimer = null
                }

                while (cleanups.length) {
                    const cleanup = cleanups.pop()

                    try {
                        cleanup()
                    } catch (e) {
                        console.warn(e)
                    }
                }

                stopAlarmPreview()
                stopActiveAlarmPlayback()

                if (window.toggleAlarmPreview?.__weeklyProgramTimer) {
                    try {
                        delete window.toggleAlarmPreview
                    } catch (e) {
                        window.toggleAlarmPreview = undefined
                    }
                }

                if (window.unlockStudyPermissions?.__weeklyProgramTimer) {
                    try {
                        delete window.unlockStudyPermissions
                    } catch (e) {
                        window.unlockStudyPermissions = undefined
                    }
                }

                if (window.stopAlarmPreview?.__weeklyProgramTimer) {
                    try {
                        delete window.stopAlarmPreview
                    } catch (e) {
                        window.stopAlarmPreview = undefined
                    }
                }

                if (window[CLEANUP_KEY] === cleanupWeeklyProgramTimer) {
                    try {
                        delete window[CLEANUP_KEY]
                    } catch (e) {
                        window[CLEANUP_KEY] = undefined
                    }
                }
            }

            window[CLEANUP_KEY] = cleanupWeeklyProgramTimer

            const cleanupOnNavigate = () => {
                if (typeof window[CLEANUP_KEY] === 'function') {
                    window[CLEANUP_KEY]()
                }
            }

            addSafeListener(document, 'livewire:navigate', cleanupOnNavigate, { once: true })
            addSafeListener(document, 'livewire:navigating', cleanupOnNavigate, { once: true })

            preloadAlarms()
            const storedAlarm = getSelectedAlarm()
            if (storedAlarm !== getWireValue('selectedAlarm', 'Alarmclock')) {
                safeWireCallDebounced('setAlarm', 0, storedAlarm)
            }
            startClientTimer()
            startServerSync()

            if ('Notification' in window && Notification.permission === 'granted') {
                safeWireCallDebounced('onPermissionsGranted')
            }
        })()
    </script>
    @endscript

</div>
