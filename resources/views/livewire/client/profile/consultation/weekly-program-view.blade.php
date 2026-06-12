<div dir="rtl">

    @assets
    <style>
        @font-face { font-family: 'Digital'; src: url('/client/assets/fonts/digital-7.ttf') format('truetype'); }
        [x-cloak] { display: none !important; }

        .ss-scroll::-webkit-scrollbar { display: none; }
        .ss-scroll { -ms-overflow-style: none; scrollbar-width: none; }

        .part-card { transition: background .15s; }
        .part-card:hover { background: #232327; }

        .day-circle {
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px;
            transition: background .15s, color .15s, border-color .15s;
            cursor: pointer;
            flex-shrink: 0;
        }
        .day-indicator {
            height: 3px; width: 20px; border-radius: 9999px;
            margin-top: 4px;
            transition: background .15s;
        }
        .timer-ring { transform: rotate(-90deg); transform-origin: center; transition: stroke-dashoffset .4s linear; }
    </style>
    @endassets

    @php
        $timerActive = $currentPartId || $makeupTimerRunning || $makeupPausedAtTs;
        $isMakeupMode = $makeupTimerRunning || $makeupPausedAtTs;
        $activePart = $currentPartId ? collect($programParts)->firstWhere('id', $currentPartId) : null;
        $ringCircumference = 816.81; // 2 * pi * 130
        $appTz = config('app.timezone');
    @endphp

    <div class="max-w-6xl mx-auto px-3 sm:px-4 py-4 sm:py-6"
         x-data="{
             tab: @js($isActiveProgram ? 'study' : 'grid'),
             selectedDay: @js(\Carbon\Carbon::today()->toDateString()),
             expandedPart: null,
             togglePart(id) { this.expandedPart = this.expandedPart === id ? null : id; },
             permissionModal: @entangle('showPermissionModal'),
             finishModal:     @entangle('showFinishModal'),
             makeupFinishModal: @entangle('showMakeupFinishModal'),
             makeupModal:     @entangle('showMakeupModal'),
             feedbackModal:   @entangle('showFeedbackModal'),
         }">

        {{-- ================================================================ --}}
        {{-- ===== صفحه تمام‌صفحه تایمر (وقتی تایمر فعال است فقط همین صفحه) ===== --}}
        {{-- ================================================================ --}}
        @if($timerActive)
            <div class="fixed inset-0 z-[90] overflow-y-auto"
                 style="background:rgba(5,5,7,.94); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);"
                 x-data="{
                     wakeLock: null,
                     keepOn: false,
                     async toggleWake() {
                         try {
                             if (this.keepOn) {
                                 await this.wakeLock?.release();
                                 this.wakeLock = null;
                                 this.keepOn = false;
                             } else if ('wakeLock' in navigator) {
                                 this.wakeLock = await navigator.wakeLock.request('screen');
                                 this.keepOn = true;
                                 this.wakeLock.addEventListener('release', () => { this.keepOn = false; });
                             }
                         } catch(e) { console.warn(e); }
                     }
                 }">
                <div class="min-h-full flex flex-col items-center justify-center px-4 py-10 text-white">

                    {{-- دکمه آلارم بالا --}}
                    <div class="absolute top-4 left-4 flex items-center gap-2">
                        <button wire:click="$set('showAlarmModal', true)"
                                class="w-10 h-10 rounded-full flex items-center justify-center"
                                style="background:#1c1c1e; border:1px solid #2a2a2e;"
                                title="تنظیمات صدای آلارم">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="#aaa" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 9l10.5-3m0 6.553v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 11-2.12-1.767l1.32-.377V9.5l-7.5 2.25v5.57a2.25 2.25 0 01-1.632 2.163l-1.32.378a1.803 1.803 0 11-2.12-1.768l1.32-.377V7.5L19.5 4.5"/>
                            </svg>
                        </button>
                    </div>

                    {{-- عنوان پارت / جبرانی --}}
                    <div class="mb-6 text-center">
                        @if($isMakeupMode)
                            <span class="text-xs font-bold px-3 py-1 rounded-full" style="background:#2d1b69; color:#c4b5fd;">مطالعه اضافه بر سازمان</span>
                        @elseif($isInExtraPhase)
                            <span class="text-xs font-bold px-3 py-1 rounded-full" style="background:#2d1b69; color:#c4b5fd;">اضافه بر مشاور</span>
                            <div class="mt-2 font-bold text-white">{{ $activePart?->lesson_name ?? '—' }}</div>
                        @else
                            @if($activePart?->ccSubject)
                                <div class="text-xs mb-1" style="color:#666;">{{ $activePart->ccSubject->name }}</div>
                            @endif
                            <div class="font-black text-white text-lg">{{ $activePart?->lesson_name ?? '—' }}</div>
                            @if($activePart?->ccChapter)
                                <div class="text-xs mt-1" style="color:#4a9eff;">{{ $activePart->ccChapter->name }}</div>
                            @endif
                            @if($pendingExtraTargetSeconds !== null)
                                <span class="inline-block mt-2 text-[10px] px-2 py-0.5 rounded-full"
                                      style="background:#2d1b69; color:#c4b5fd;">
                                    + {{ $this->formatClock($pendingExtraTargetSeconds) }} اضافه بعد از پایان
                                </span>
                            @endif
                        @endif
                    </div>

                    {{-- دایره تایمر --}}
                    @php
                        if ($isMakeupMode) {
                            $ovRemaining = $makeupRemainingSeconds; $ovTarget = $makeupTargetSeconds;
                            $ovEndsAt = $makeupEndsAtTs; $ovRunning = $makeupTimerRunning;
                            $ovClockId = 'makeup-clock'; $ovRingId = 'makeup-ring'; $ovColor = '#a78bfa';
                        } elseif ($isInExtraPhase) {
                            $ovRemaining = $extraRemainingSeconds; $ovTarget = $extraTargetSeconds;
                            $ovEndsAt = $extraEndsAtTs; $ovRunning = $isRunning;
                            $ovClockId = 'extra-clock'; $ovRingId = 'extra-ring'; $ovColor = '#a78bfa';
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
                                    class="timer-ring"
                                    stroke-dasharray="{{ $ringCircumference }}"
                                    stroke-dashoffset="{{ $ovOffset }}"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6">
                            <div id="{{ $ovClockId }}" dir="ltr"
                                 style="font-family:'Digital',monospace; font-size:56px; color:#fff; letter-spacing:2px; line-height:1;">
                                {{ $this->formatClock($ovRemaining) }}
                            </div>
                            <div class="mt-3 text-sm" style="color:#9a9aa0;">
                                زمان کل
                                {{ $ovTotalH > 0 ? $ovTotalH . ' ساعت و ' : '' }}{{ $ovTotalM }} دقیقه{{ $ovTotalS > 0 ? ' و ' . $ovTotalS . ' ثانیه' : '' }}
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
                            <button type="button" @click="toggleWake()"
                                    class="mt-4 px-4 py-1.5 rounded-full text-xs flex items-center gap-1.5"
                                    :style="keepOn ? 'background:#1d3a6e;color:#4a9eff;border:1px solid #2563eb;' : 'background:#1c1c1e;color:#9a9aa0;border:1px solid #2a2a2e;'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                                </svg>
                                <span x-text="keepOn ? 'صفحه روشن می‌ماند' : 'روشن نگه‌داشتن صفحه'"></span>
                            </button>
                        </div>
                    </div>

                    {{-- وضعیت --}}
                    <div class="mt-4">
                        <span class="text-xs px-3 py-1 rounded-full font-semibold"
                              style="{{ $ovRunning ? 'background:#0f2a1a;color:#4ade80;' : 'background:#3a2000;color:#fbbf24;' }}">
                            {{ $ovRunning ? 'در حال اجرا' : 'متوقف' }}
                        </span>
                    </div>

                    {{-- دکمه‌های ۸۰٪ --}}
                    @if(!$isMakeupMode && $this->canShowEarlyOrMore)
                        <div class="grid grid-cols-2 gap-2 mt-6 w-full max-w-sm">
                            <button wire:click="openEarlyFinishConfirm"
                                    class="h-11 rounded-full text-xs font-bold"
                                    style="background:#0f2a1a; color:#4ade80; border:1px solid #1e5c35;">
                                ⚡ زودتر تمام کردم
                            </button>
                            <button wire:click="openStudyMoreModal"
                                    class="h-11 rounded-full text-xs font-bold"
                                    style="background:#2d1b69; color:#c4b5fd; border:1px solid #5b21b6;">
                                ➕ مطالعه بیشتر
                            </button>
                        </div>
                    @endif

                    {{-- دکمه‌های پایین: توقف کامل / مکث-ادامه --}}
                    <div class="flex items-center justify-center gap-14 mt-10">
                        <button wire:click="openCancelConfirm"
                                class="w-16 h-16 rounded-full flex items-center justify-center"
                                style="background:#1c1c1e; border:1px solid #2a2a2e;"
                                title="لغو تایمر">
                            <span class="block w-5 h-5 rounded" style="background:{{ $ovColor }};"></span>
                        </button>

                        @if($isMakeupMode)
                            @if($makeupTimerRunning)
                                <button wire:click="pauseMakeup"
                                        class="w-16 h-16 rounded-full flex items-center justify-center"
                                        style="background:#1c1c1e; border:1px solid #2a2a2e;" title="توقف">
                                    <span class="flex gap-1.5">
                                        <span class="block w-1.5 h-5 rounded" style="background:{{ $ovColor }};"></span>
                                        <span class="block w-1.5 h-5 rounded" style="background:{{ $ovColor }};"></span>
                                    </span>
                                </button>
                            @else
                                <button wire:click="resumeMakeup"
                                        class="w-16 h-16 rounded-full flex items-center justify-center"
                                        style="background:#1c1c1e; border:1px solid #2a2a2e;" title="ادامه">
                                    <svg viewBox="0 0 24 24" fill="{{ $ovColor }}" class="w-6 h-6" style="margin-right:-2px;">
                                        <path d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z"/>
                                    </svg>
                                </button>
                            @endif
                        @else
                            @if($isRunning)
                                <button wire:click="pausePart"
                                        class="w-16 h-16 rounded-full flex items-center justify-center"
                                        style="background:#1c1c1e; border:1px solid #2a2a2e;" title="توقف">
                                    <span class="flex gap-1.5">
                                        <span class="block w-1.5 h-5 rounded" style="background:{{ $ovColor }};"></span>
                                        <span class="block w-1.5 h-5 rounded" style="background:{{ $ovColor }};"></span>
                                    </span>
                                </button>
                            @elseif($pausedAtTs)
                                <button wire:click="resumePart"
                                        class="w-16 h-16 rounded-full flex items-center justify-center"
                                        style="background:#1c1c1e; border:1px solid #2a2a2e;" title="ادامه">
                                    <svg viewBox="0 0 24 24" fill="{{ $ovColor }}" class="w-6 h-6" style="margin-right:-2px;">
                                        <path d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z"/>
                                    </svg>
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- ==================== هدر برنامه ==================== --}}
        <section class="mb-6 overflow-hidden rounded-2xl border border-border glass shadow-[0_4px_20px_rgba(15,23,42,0.06),0_2px_8px_rgba(15,23,42,0.04)]">
            <div class="bg-gradient-to-r from-blue-900 via-blue-600 to-blue-400 px-4 py-5 sm:px-6 sm:py-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    {{-- لوگو + عنوان --}}
                    <div class="flex items-start gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 ring-2 ring-white/40 shadow-md backdrop-blur-sm">
                            <img src="/client/assets/images/favicon.svg" class="w-10 h-10" alt="لوگو SDFR">
                        </div>
                        <div>
                            <h1 class="mb-1 text-lg sm:text-xl font-bold text-white">برنامه هفتگی تحصیلی</h1>
                            <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-1 text-[11px] sm:text-xs text-blue-100/90">
                                <span class="flex items-center gap-1">
                                    <span>
                                        {{ jdate($program->start_date)->format('Y/m/d') }}
                                        تا
                                        {{ jdate($program->end_date)->format('Y/m/d') }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- سوییچ تب‌ها + دکمه برگشت --}}
                    <div class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center">
                        <a wire:navigate="" href="{{ route('client.profile.plan') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-500 px-4 py-2
                                  text-xs font-medium text-white shadow-sm ring-1
                                  hover:bg-red transition-all">
                            <i class="fas fa-arrow-right text-slate-200"></i>
                            <span>بازگشت به جلسات</span>
                        </a>
                        <div class="inline-flex items-center justify-between rounded-full bg-black p-1 text-[11px] text-white/80 ring-1 ring-white/20 backdrop-blur-sm sm:text-xs">
                            @if($isActiveProgram)
                                <button
                                    type="button"
                                    @click="tab = 'study'"
                                    :class="tab === 'study' ? 'bg-white/95 text-slate-900 shadow-sm' : 'bg-transparent text-white/75'"
                                    class="rounded-full px-3 py-1.5 transition"
                                >
                                    ثبت مطالعه
                                </button>
                            @endif
                            <button
                                type="button"
                                @click="tab = 'grid'"
                                :class="tab === 'grid' ? 'bg-white/95 text-slate-900 shadow-sm' : 'bg-transparent text-white/75'"
                                class="rounded-full px-3 py-1.5 transition"
                            >
                                جدول هفتگی
                            </button>
                            <button
                                type="button"
                                @click="tab = 'archive'"
                                :class="tab === 'archive' ? 'bg-white/95 text-slate-900 shadow-sm' : 'bg-transparent text-white/75'"
                                class="rounded-full px-3 py-1.5 transition"
                            >
                                آرشیو
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- آمار کلی --}}
        <section class="mb-6 grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-4">
            <div class="rounded-2xl bg-gradient-to-br from-sky-500 to-sky-600 p-4 text-white shadow-md sm:p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] text-sky-100/90">ساعات مطالعه</p>
                        <p class="mt-1 text-2xl font-extrabold sm:text-3xl">{{ $stats['totalHours'] }}</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5">
                            <circle cx="10" cy="10" r="8" stroke-linecap="round" stroke-linejoin="round"></circle>
                            <path d="M10 5v5h4" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 p-4 text-white shadow-md sm:p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] text-emerald-100/90">تعداد تست</p>
                        <p class="mt-1 text-2xl font-extrabold sm:text-3xl">{{ $stats['totalTests'] }}</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-violet-500 to-violet-600 p-4 text-white shadow-md sm:p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] text-violet-100/90">تعداد پارت‌ها</p>
                        <p class="mt-1 text-2xl font-extrabold sm:text-3xl">{{ $stats['totalParts'] }}</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        {{-- ==================== تب ثبت مطالعه ==================== --}}
        @if($isActiveProgram)
            <section x-show="tab === 'study'" x-cloak
                     class="rounded-2xl p-4 sm:p-5"
                     style="background:#0d0d0d; border:1px solid #1e1e1e;">

                <div class="flex items-center justify-between mb-5 px-1">
                    <div>
                        <div class="font-black text-white text-lg tracking-tight">ثبت ساعت مطالعه</div>
                        <div class="text-xs mt-0.5" style="color:#4a9eff;">بر اساس برنامه هفتگی</div>
                    </div>
                    <button wire:click="$set('showAlarmModal', true)"
                            class="w-9 h-9 rounded-full flex items-center justify-center"
                            style="background:#1c1c1c; border:1px solid #2a2a2a;"
                            title="تنظیمات صدای آلارم">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="#aaa" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 9l10.5-3m0 6.553v3.75a2.25 2.25 0 01-1.632 2.163l-1.32.377a1.803 1.803 0 11-2.12-1.767l1.32-.377V9.5l-7.5 2.25v5.57a2.25 2.25 0 01-1.632 2.163l-1.32.378a1.803 1.803 0 11-2.12-1.768l1.32-.377V7.5L19.5 4.5"/>
                        </svg>
                    </button>
                </div>

                {{-- دکمه مطالعه جبرانی --}}
                @if(!$timerActive)
                    <button wire:click="openMakeupModal"
                            class="w-full mb-4 h-12 rounded-2xl font-bold text-sm flex items-center justify-center gap-2"
                            style="background:linear-gradient(to left,#4f46e5,#7c3aed); color:#fff;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        ثبت ساعت مطالعه اضافه بر سازمان
                    </button>
                @endif

                {{-- دایره‌های روزها --}}
                <div class="flex items-center gap-2 mb-2 px-1">
                    <span class="text-xs font-semibold" style="color:#4a9eff;">
                        {{ jdate($program->start_date)->format('d') }} تا {{ jdate($program->end_date)->format('d F') }}
                    </span>
                    <span style="color:#2a2a2a; font-size:11px;">|</span>
                    <span class="text-xs" style="color:#666;">برنامه هفتگی</span>
                </div>

                <div class="flex items-center gap-2 mb-4 ss-scroll overflow-x-auto pb-1">
                    @foreach($weekDays as $day)
                        @php
                            $isToday = $day['date'] === \Carbon\Carbon::today()->toDateString();
                            $isPast  = $day['date'] < \Carbon\Carbon::today()->toDateString();
                            $allDone = $day['parts']->count() > 0 && $day['parts']->every(fn($p) => in_array($p->id, $completedParts));
                            $hasMiss = !$day['is_rest_day'] && $isPast && $day['parts']->filter(fn($p) => !in_array($p->id, $completedParts))->count() > 0;
                            $jalDay  = jdate(\Carbon\Carbon::parse($day['date']))->format('d');

                            if ($allDone) {
                                $defaultStyle = 'background:#0f2a1a;color:#4ade80;border:2px solid #1e5c35;';
                            } elseif ($hasMiss) {
                                $defaultStyle = 'background:#3a1a1a;color:#f87171;border:2px solid #7f1d1d;';
                            } elseif ($isToday) {
                                $defaultStyle = 'background:#1d3a6e;color:#4a9eff;border:2px solid #2563eb;';
                            } else {
                                $defaultStyle = 'background:#151515;color:#888;border:2px solid #222;';
                            }
                        @endphp
                        <div class="flex-shrink-0 flex flex-col items-center gap-0.5 cursor-pointer"
                             @click="selectedDay = '{{ $day['date'] }}'">
                            <div class="day-circle"
                                 :style="selectedDay === '{{ $day['date'] }}'
                                    ? 'background:#2563eb;color:#fff;border:2px solid #2563eb;'
                                    : '{{ $defaultStyle }}'">
                                {{ $jalDay }}
                            </div>
                            <div class="day-indicator"
                                 :style="selectedDay === '{{ $day['date'] }}' ? 'background:#2563eb;' : 'background:transparent;'">
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- کارت‌های پارت (دقیقاً مطابق UI) --}}
                <div class="space-y-1">
                    @foreach($weekDays as $day)
                        <div x-show="selectedDay === '{{ $day['date'] }}'" x-cloak>

                            @if($day['is_rest_day'])
                                <div class="rounded-2xl p-6 text-center"
                                     style="background:#161618; border:1px solid #1e1e1e;">
                                    <div class="text-2xl mb-2">🌿</div>
                                    <div class="font-bold mb-1" style="color:#4ade80;">روز استراحت</div>
                                    <div class="text-sm" style="color:#666;">امروز نیازی به مطالعه نیست</div>
                                </div>

                            @elseif($day['parts_count'] > 0)
                                <div class="space-y-3">
                                    @foreach($day['parts']->sortBy('part_order') as $part)
                                        @php
                                            $isDone   = in_array($part->id, $completedParts);
                                            $isActive = $currentPartId == $part->id;
                                            $isMissed = !$isDone && $day['date'] < \Carbon\Carbon::today()->toDateString();
                                        @endphp
                                        <div class="part-card rounded-3xl overflow-hidden"
                                             style="background:#1c1c1e; border:1px solid {{ $isActive ? '#2563eb' : 'transparent' }};">

                                            {{-- ردیف اصلی کارت --}}
                                            <div class="flex items-center justify-between px-5 py-5 cursor-pointer"
                                                 @click="togglePart({{ $part->id }})">

                                                {{-- راست: نام درس --}}
                                                <div class="text-right min-w-0">
                                                    <div class="font-black text-white truncate" style="font-size:22px;">{{ $part->lesson_name }}</div>
                                                </div>

                                                {{-- وسط: تست و دقیقه --}}
                                                <div class="flex items-center gap-6">
                                                    @if($part->test_count)
                                                        <div class="text-center">
                                                            <div class="font-bold text-white" style="font-size:15px; line-height:1.2;">{{ $part->test_count }}</div>
                                                            <div class="text-[11px]" style="color:#8a8a8e;">تست</div>
                                                        </div>
                                                    @endif
                                                    <div class="text-center">
                                                        <div class="font-bold text-white" style="font-size:15px; line-height:1.2;">{{ $part->duration_minutes }}</div>
                                                        <div class="text-[11px]" style="color:#8a8a8e;">دقیقه</div>
                                                    </div>

                                                    {{-- چپ: آیکون وضعیت --}}
                                                    <div class="w-9 h-9 flex items-center justify-center flex-shrink-0">
                                                        @if($isDone)
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                 viewBox="0 0 24 24" stroke-width="2.5"
                                                                 stroke="#22c55e" class="w-7 h-7">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                            </svg>
                                                        @elseif($isMissed)
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                 viewBox="0 0 24 24" stroke-width="2.5"
                                                                 stroke="#ef4444" class="w-7 h-7">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                        @elseif($isActive)
                                                            <div class="w-2.5 h-2.5 rounded-full animate-pulse"
                                                                 style="background:#4a9eff; box-shadow:0 0 8px #4a9eff;"></div>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                 viewBox="0 0 24 24" stroke-width="2.5"
                                                                 stroke="#3b82f6" class="w-6 h-6 transition-transform"
                                                                 :style="expandedPart === {{ $part->id }} ? 'transform:rotate(180deg)' : ''">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- جزئیات expand شده --}}
                                            <div x-show="expandedPart === {{ $part->id }}" x-collapse x-cloak>
                                                <div class="px-5 pb-5 space-y-3 text-center">

                                                    {{-- مسیر: درس >> فصل (بدون مبحث) --}}
                                                    <div class="flex items-center justify-center gap-1.5 flex-wrap text-sm"
                                                         style="color:#c8c8cc;" dir="rtl">
                                                        @if($part->ccSubject)
                                                            <span>{{ $part->ccSubject->name }}</span>
                                                        @endif
                                                        @if($part->ccSubject && $part->ccChapter)
                                                            <span style="color:#666;">«</span>
                                                        @endif
                                                        @if($part->ccChapter)
                                                            <span>{{ $part->ccChapter->name }}</span>
                                                        @endif
                                                    </div>

                                                    {{-- تگ‌ها --}}
                                                    <div class="flex items-center justify-center gap-2 flex-wrap">
                                                        <span class="px-3 py-1 rounded-full text-xs font-semibold"
                                                              style="{{ $part->part_type === 'test' ? 'background:#4c2a4a;color:#e2b8dd;' : ($part->part_type === 'descriptive' ? 'background:#2d1b69;color:#c4b5fd;' : 'background:#3a2a10;color:#fbbf24;') }}">
                                                            {{ $part->part_type_label }}
                                                        </span>
                                                        @if($part->grade)
                                                            <span class="px-3 py-1 rounded-full text-xs font-semibold"
                                                                  style="background:#1d3a6e; color:#93c5fd;">
                                                                @if($part->grade == 10) دهم
                                                                @elseif($part->grade == 11) یازدهم
                                                                @elseif($part->grade == 12) دوازدهم
                                                                @endif
                                                            </span>
                                                        @endif
                                                        <span class="px-3 py-1 rounded-full text-xs"
                                                              style="background:#26262a; color:#9a9aa0;">
                                                            {{ $part->lesson_type_label }}
                                                        </span>
                                                    </div>

                                                    @if($part->description)
                                                        <div class="text-xs leading-relaxed" style="color:#8a8a8e;">
                                                            {{ $part->description }}
                                                        </div>
                                                    @endif

                                                    {{-- دکمه شروع / وضعیت --}}
                                                    @if($isDone)
                                                        @php $meta = $completedPartsMeta[$part->id] ?? null; @endphp
                                                        <div class="text-xs font-bold py-2.5 rounded-full"
                                                             style="background:#0f2a1a; color:#4ade80;">
                                                            ✓ تکمیل شده
                                                            @if($meta && ($meta['is_early_finish'] ?? false)) (زودتر تمام شد) @endif
                                                            @if($meta && ($meta['extra_seconds'] ?? 0) > 0) + {{ $this->formatDuration($meta['extra_seconds']) }} اضافه @endif
                                                        </div>
                                                    @elseif($isActive)
                                                        <div class="text-xs font-bold py-2.5 rounded-full"
                                                             style="background:#0d2a4a; color:#4a9eff;">در حال مطالعه...</div>
                                                    @elseif($isMissed)
                                                        <div class="text-xs font-bold py-2.5 rounded-full"
                                                             style="background:#3a1a1a; color:#f87171;">ثبت نشده — می‌توانید جبرانی ثبت کنید</div>
                                                    @elseif(!$timerActive)
                                                        <button wire:click="startPart({{ $part->id }})"
                                                                wire:loading.attr="disabled"
                                                                wire:target="startPart({{ $part->id }})"
                                                                class="mx-auto block px-12 h-11 rounded-full font-bold text-sm relative"
                                                                style="background:#2563eb; color:#fff;">
                                                            <span wire:loading.remove wire:target="startPart({{ $part->id }})">شروع</span>
                                                            <span wire:loading wire:target="startPart({{ $part->id }})"
                                                                  class="flex items-center justify-center gap-2">
                                                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 12 0 12 0v4a8 8 0 00-8 8H4z"></path>
                                                                </svg>
                                                                صبر کنید...
                                                            </span>
                                                        </button>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            @else
                                <div class="rounded-2xl p-6 text-center"
                                     style="background:#161618; border:1px solid #1e1e1e;">
                                    <div class="text-sm" style="color:#666;">برنامه‌ای برای این روز تنظیم نشده</div>
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ==================== جدول هفتگی ==================== --}}
        <section
            x-show="tab === 'grid'"
            x-cloak
            class="rounded-2xl border border-border glass shadow-sm"
            x-data="{
                currentDayIndex: 0,
                totalDays: {{ count($weekDays) }},
                goNext() { if (this.currentDayIndex < this.totalDays - 1) this.currentDayIndex++; },
                goPrev() { if (this.currentDayIndex > 0) this.currentDayIndex--; }
            }"
        >
            {{-- ناوبری موبایل --}}
            <div class="flex md:hidden items-center gap-2 px-3 py-3 border-b border-border bg-muted/30 rounded-t-2xl">
                <button
                    type="button"
                    @click="goPrev()"
                    :disabled="currentDayIndex === 0"
                    :class="currentDayIndex === 0 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-primary/10 active:scale-95'"
                    class="flex items-center justify-center w-9 h-9 rounded-xl border border-border glass text-foreground transition-all shrink-0"
                    title="روز قبل"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                    </svg>
                </button>

                <div class="flex-1 relative">
                    <select
                        x-model.number="currentDayIndex"
                        class="w-full appearance-none rounded-xl border border-border glass text-foreground text-[12px] font-medium px-3 py-2 pr-3 pl-7 focus:outline-none focus:ring-2 focus:ring-primary/30 cursor-pointer"
                    >
                        @foreach($weekDays as $i => $day)
                            <option value="{{ $i }}">
                                {{ $day['name'] }} — {{ $day['jalali_date'] }}
                                @if($day['is_rest_day']) (استراحت) @endif
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute left-2 top-1/2 -translate-y-1/2 text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                        </svg>
                    </div>
                </div>

                <button
                    type="button"
                    @click="goNext()"
                    :disabled="currentDayIndex === totalDays - 1"
                    :class="currentDayIndex === totalDays - 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-primary/10 active:scale-95'"
                    class="flex items-center justify-center w-9 h-9 rounded-xl border border-border glass text-foreground transition-all shrink-0"
                    title="روز بعد"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                    </svg>
                </button>
            </div>

            {{-- نشانگر پیشرفت روز (موبایل) --}}
            <div class="flex md:hidden items-center justify-center gap-1.5 px-3 py-2 border-b border-border/50">
                @foreach($weekDays as $i => $day)
                    <button
                        type="button"
                        @click="currentDayIndex = {{ $i }}"
                        :class="currentDayIndex === {{ $i }}
                            ? '{{ $day['is_rest_day'] ? 'bg-emerald-500 w-4' : 'bg-blue-500 w-4' }}'
                            : 'bg-border w-1.5'"
                        class="h-1.5 rounded-full transition-all duration-300"
                    ></button>
                @endforeach
            </div>

            {{-- دسکتاپ: جدول کامل --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full min-w-[720px] text-xs sm:text-[13px]">
                    <thead>
                    <tr class="text-[11px] text-muted">
                        @foreach($weekDays as $day)
                            <th class="border-l border-border px-3 py-3 last:border-l-0 {{ $day['is_rest_day'] ? 'bg-emerald-50/80 dark:bg-emerald-900/20' : '' }}" style="background-color: #2b2b31;">
                                <div class="font-semibold {{ $day['is_rest_day'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-foreground' }}">
                                    {{ $day['name'] }}
                                    @if($day['is_rest_day'])
                                        <span class="block text-[9px] mt-1 px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-300 inline-block">استراحت</span>
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
                            <td class="min-w-[385px] border-l border-border px-2 py-3 align-top last:border-l-0
                                       {{ $day['is_rest_day'] ? 'bg-emerald-50/30 dark:bg-emerald-900/10' : '' }}">
                                @include('livewire.client.profile.consultation.partials.day-parts-content', ['day' => $day])
                            </td>
                        @endforeach
                    </tr>
                    </tbody>
                </table>
            </div>

            {{-- موبایل: نمایش تک روز --}}
            <div class="block md:hidden">
                @foreach($weekDays as $i => $day)
                    <div
                        x-show="currentDayIndex === {{ $i }}"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-x-2"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="px-3 py-3"
                    >
                        <div class="mb-3 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-1 h-6 rounded-full {{ $day['is_rest_day'] ? 'bg-emerald-500' : 'bg-blue-500' }}"></div>
                                <div>
                                    <span class="text-sm font-bold text-foreground">{{ $day['name'] }}</span>
                                    <span class="text-[11px] text-muted mr-2">{{ $day['jalali_date'] }}</span>
                                </div>
                                @if($day['is_rest_day'])
                                    <span class="rounded-full text-[10px] px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-300">استراحت</span>
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

        {{-- ==================== آرشیو ==================== --}}
        <section
            x-show="tab === 'archive'"
            x-cloak
            class="rounded-2xl border border-border glass p-4 shadow-sm sm:p-5"
        >
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-base font-semibold text-foreground">آرشیو مطالعه این هفته</h3>
                <span class="text-[11px] text-muted">{{ jdate($program->start_date)->format('Y/m/d') }} تا {{ jdate($program->end_date)->format('Y/m/d') }}</span>
            </div>

            {{-- خلاصه آرشیو --}}
            <div class="mb-5 grid grid-cols-2 gap-3 md:grid-cols-4">
                <div class="rounded-xl border border-border bg-emerald-50/50 dark:bg-emerald-900/10 p-3 text-center">
                    <div class="text-xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $archiveSummary['done'] }}</div>
                    <div class="text-[11px] text-muted mt-0.5">پارت ثبت‌شده از {{ $archiveSummary['total'] }}</div>
                </div>
                <div class="rounded-xl border border-border bg-red-50/50 dark:bg-red-900/10 p-3 text-center">
                    <div class="text-xl font-extrabold text-red-600 dark:text-red-400">{{ $archiveSummary['missed'] }}</div>
                    <div class="text-[11px] text-muted mt-0.5">پارت ثبت‌نشده (گذشته)</div>
                </div>
                <div class="rounded-xl border border-border bg-sky-50/50 dark:bg-sky-900/10 p-3 text-center">
                    <div class="text-xl font-extrabold text-sky-600 dark:text-sky-400">{{ $this->formatDuration($archiveSummary['studied_seconds']) }}</div>
                    <div class="text-[11px] text-muted mt-0.5">مجموع مطالعه ثبت‌شده</div>
                </div>
                <div class="rounded-xl border border-border bg-violet-50/50 dark:bg-violet-900/10 p-3 text-center">
                    <div class="text-xl font-extrabold text-violet-600 dark:text-violet-400">{{ $archiveSummary['makeup_count'] }}</div>
                    <div class="text-[11px] text-muted mt-0.5">جلسه جبرانی ({{ $this->formatDuration($archiveSummary['makeup_seconds']) }})</div>
                </div>
            </div>

            {{-- جدول پارت‌های برنامه --}}
            <div class="mb-2 flex items-center gap-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/40">
                    <i class="fas fa-list-check text-[12px] text-blue-600 dark:text-blue-400"></i>
                </div>
                <h4 class="text-sm font-semibold text-foreground">گزارش پارت‌های برنامه</h4>
            </div>
            <div class="overflow-x-auto mb-6">
                <table class="w-full text-xs sm:text-[13px]">
                    <thead>
                    <tr class="bg-muted/30 dark:bg-muted/20 text-muted text-[11px]">
                        <th class="px-3 py-2.5 text-right rounded-r-lg">روز</th>
                        <th class="px-3 py-2.5 text-right">درس</th>
                        <th class="px-3 py-2.5 text-right">فصل</th>
                        <th class="px-3 py-2.5 text-center">نوع</th>
                        <th class="px-3 py-2.5 text-center">مدت برنامه</th>
                        <th class="px-3 py-2.5 text-center">وضعیت</th>
                        <th class="px-3 py-2.5 text-center">شروع</th>
                        <th class="px-3 py-2.5 text-center">پایان</th>
                        <th class="px-3 py-2.5 text-center">مدت واقعی</th>
                        <th class="px-3 py-2.5 text-center rounded-l-lg">جزئیات</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                    @forelse($archiveParts as $row)
                        @php
                            $part = $row['part'];
                            $sps  = $row['session'];
                        @endphp
                        <tr class="hover:bg-muted/20 dark:hover:bg-muted/10 transition-colors {{ $row['status'] === 'missed' ? 'bg-red-50/30 dark:bg-red-900/10' : '' }}">
                            <td class="px-3 py-2.5 text-[11px] text-muted whitespace-nowrap">
                                {{ $row['day_name'] }}
                                <span class="block text-[10px] text-muted/70">{{ $row['jalali_date'] }}</span>
                            </td>
                            <td class="px-3 py-2.5 text-[12px] font-medium text-foreground">
                                {{ $part->lesson_name }}
                                @if($part->ccSubject && $part->ccSubject->name !== $part->lesson_name)
                                    <span class="block text-[10px] text-muted">{{ $part->ccSubject->name }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-2.5 text-[11px] text-muted">{{ $part->ccChapter?->name ?? '—' }}</td>
                            <td class="px-3 py-2.5 text-center">
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-medium
                                    {{ $part->part_type === 'test' ? 'bg-sky-100 dark:bg-sky-900/50 text-sky-700 dark:text-sky-300'
                                        : ($part->part_type === 'descriptive' ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300'
                                        : 'bg-violet-100 dark:bg-violet-900/50 text-violet-700 dark:text-violet-300') }}">
                                    {{ $part->part_type_label }}
                                </span>
                            </td>
                            <td class="px-3 py-2.5 text-center text-[11px]">{{ $part->duration_minutes }} د</td>
                            <td class="px-3 py-2.5 text-center">
                                @if($row['status'] === 'done')
                                    <span class="rounded-full bg-emerald-100 dark:bg-emerald-900/50 px-2 py-0.5 text-[10px] font-bold text-emerald-700 dark:text-emerald-300">✓ ثبت شده</span>
                                @elseif($row['status'] === 'missed')
                                    <span class="rounded-full bg-red-100 dark:bg-red-900/50 px-2 py-0.5 text-[10px] font-bold text-red-700 dark:text-red-300">✗ ثبت نشده</span>
                                @else
                                    <span class="rounded-full bg-muted/50 dark:bg-slate-700/60 px-2 py-0.5 text-[10px] text-muted">در انتظار</span>
                                @endif
                            </td>
                            <td class="px-3 py-2.5 text-center text-[11px] whitespace-nowrap" dir="ltr">
                                @if($sps?->started_at)
                                    {{ jdate($sps->started_at)->format('H:i') }}
                                    <span class="block text-[10px] text-muted/70">{{ jdate($sps->started_at)->format('m/d') }}</span>
                                @else — @endif
                            </td>
                            <td class="px-3 py-2.5 text-center text-[11px] whitespace-nowrap" dir="ltr">
                                @if($sps?->ended_at)
                                    {{ jdate($sps->ended_at)->format('H:i') }}
                                    <span class="block text-[10px] text-muted/70">{{ jdate($sps->ended_at)->format('m/d') }}</span>
                                @else — @endif
                            </td>
                            <td class="px-3 py-2.5 text-center text-[11px] whitespace-nowrap">
                                @if($sps)
                                    {{ $this->formatDuration((int)$sps->duration_seconds + (int)($sps->extra_seconds ?? 0)) }}
                                @else — @endif
                            </td>
                            <td class="px-3 py-2.5 text-center">
                                <div class="flex flex-wrap items-center justify-center gap-1">
                                    @if($sps?->is_early_finish)
                                        <span class="rounded-full bg-amber-100 dark:bg-amber-900/50 px-2 py-0.5 text-[10px] font-medium text-amber-700 dark:text-amber-300">زودتر تمام شد</span>
                                    @endif
                                    @if(($sps?->extra_seconds ?? 0) > 0)
                                        <span class="rounded-full bg-violet-100 dark:bg-violet-900/50 px-2 py-0.5 text-[10px] font-medium text-violet-700 dark:text-violet-300">+{{ $this->formatDuration($sps->extra_seconds) }} اضافه</span>
                                    @endif
                                    @if($sps?->feedback)
                                        <span class="rounded-full bg-sky-100 dark:bg-sky-900/50 px-2 py-0.5 text-[10px] font-medium text-sky-700 dark:text-sky-300">امتیاز {{ $sps->feedback->rating }}/10</span>
                                    @endif
                                    @if(!$sps && $row['status'] === 'pending')
                                        <span class="text-[10px] text-muted">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-3 py-8 text-center text-[12px] text-muted">پارتی برای این برنامه ثبت نشده است.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- گزارش جبرانی --}}
            <div class="mb-2 flex items-center gap-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900/40">
                    <i class="fas fa-plus text-[12px] text-violet-600 dark:text-violet-400"></i>
                </div>
                <h4 class="text-sm font-semibold text-foreground">گزارش مطالعه اضافه بر سازمان (جبرانی)</h4>
                <span class="rounded-full bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300 text-[10px] px-2 py-0.5">{{ $archiveMakeups->count() }} جلسه</span>
            </div>
            @if($archiveMakeups->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-xs sm:text-[13px]">
                        <thead>
                        <tr class="bg-violet-50/50 dark:bg-violet-900/10 text-muted text-[11px]">
                            <th class="px-3 py-2 text-right rounded-r-lg">تاریخ</th>
                            <th class="px-3 py-2 text-right">درس » فصل</th>
                            <th class="px-3 py-2 text-center">نوع</th>
                            <th class="px-3 py-2 text-center">مدت</th>
                            <th class="px-3 py-2 text-center">شروع</th>
                            <th class="px-3 py-2 text-center">پایان</th>
                            <th class="px-3 py-2 text-center">وضعیت تایید</th>
                            <th class="px-3 py-2 text-right rounded-l-lg">یادداشت</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                        @foreach($archiveMakeups as $makeup)
                            <tr class="hover:bg-violet-50/30 dark:hover:bg-violet-900/10 transition-colors">
                                <td class="px-3 py-2.5 text-[11px] text-muted whitespace-nowrap">{{ jdate($makeup->created_at)->format('Y/m/d') }}</td>
                                <td class="px-3 py-2.5 text-[12px] font-medium text-foreground">
                                    {{ collect([$makeup->ccTopic?->chapter?->subject?->name, $makeup->ccTopic?->chapter?->name])->filter()->implode(' » ') ?: '—' }}
                                </td>
                                <td class="px-3 py-2.5 text-center">
                                    <span class="rounded-full bg-muted/50 dark:bg-slate-700/60 px-2 py-0.5 text-[10px] text-muted">{{ $makeup->part_type_label }}</span>
                                </td>
                                <td class="px-3 py-2.5 text-center text-[11px] whitespace-nowrap">{{ $this->formatDuration($makeup->duration_seconds) }}</td>
                                <td class="px-3 py-2.5 text-center text-[11px] whitespace-nowrap" dir="ltr">
                                    {{ $makeup->started_at ? jdate($makeup->started_at)->format('H:i') : '—' }}
                                </td>
                                <td class="px-3 py-2.5 text-center text-[11px] whitespace-nowrap" dir="ltr">
                                    {{ $makeup->ended_at ? jdate($makeup->ended_at)->format('H:i') : '—' }}
                                </td>
                                <td class="px-3 py-2.5 text-center">
                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-medium
                                        {{ $makeup->status === 'approved' ? 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300'
                                            : ($makeup->status === 'rejected' ? 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300'
                                            : 'bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300') }}">
                                        {{ $makeup->status_label }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-[11px] text-muted max-w-[180px]">{{ Str::limit($makeup->note, 60) ?: '—' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-center rounded-xl border border-dashed border-border">
                    <p class="text-sm text-muted">هیچ جلسه جبرانی در این هفته ثبت نشده است.</p>
                </div>
            @endif
        </section>

        {{-- نمودارها --}}
        <section class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2" x-show="tab !== 'study'" x-cloak>
            {{-- نوع پارت --}}
            <div class="rounded-2xl border border-border glass p-4 shadow-sm dark:glass">
                <h3 class="mb-3 text-center text-sm font-semibold text-foreground">توزیع نوع پارت</h3>
                <div class="flex items-center justify-center">
                    <div class="relative h-32 w-32 sm:h-36 sm:w-36">
                        @php
                            $total = $stats['testParts'] + $stats['descriptiveParts'] + $stats['videoParts'];
                            $testPercent = $total > 0 ? ($stats['testParts'] / $total) * 100 : 0;
                            $descPercent = $total > 0 ? ($stats['descriptiveParts'] / $total) * 100 : 0;
                            $videoPercent = $total > 0 ? ($stats['videoParts'] / $total) * 100 : 0;
                        @endphp
                        <svg viewBox="0 0 36 36" class="h-full w-full">
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e5e7eb" stroke-width="3" class="dark:stroke-slate-700"/>
                            @if($total > 0)
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#3B82F6" stroke-width="3"
                                        stroke-dasharray="{{ $testPercent }} {{ 100 - $testPercent }}"
                                        stroke-dashoffset="25" class="origin-center -rotate-90 transform"/>
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#10B981" stroke-width="3"
                                        stroke-dasharray="{{ $descPercent }} {{ 100 - $descPercent }}"
                                        stroke-dashoffset="{{ 25 - $testPercent }}" class="origin-center -rotate-90 transform"/>
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#8B5CF6" stroke-width="3"
                                        stroke-dasharray="{{ $videoPercent }} {{ 100 - $videoPercent }}"
                                        stroke-dashoffset="{{ 25 - $testPercent - $descPercent }}" class="origin-center -rotate-90 transform"/>
                            @endif
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex flex-wrap justify-center gap-3 text-[11px] text-muted">
                    <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-sky-500"></span>تستی ({{ $stats['testParts'] }})</span>
                    <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>تشریحی ({{ $stats['descriptiveParts'] }})</span>
                    <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-violet-500"></span>ویدیویی ({{ $stats['videoParts'] }})</span>
                </div>
            </div>

            {{-- نوع درس --}}
            <div class="rounded-2xl border border-border glass p-4 shadow-sm">
                <h3 class="mb-3 text-center text-sm font-semibold text-foreground">توزیع نوع درس</h3>
                <div class="flex items-center justify-center">
                    <div class="relative h-32 w-32 sm:h-36 sm:w-36">
                        @php
                            $totalLesson = $stats['generalParts'] + $stats['specializedParts'];
                            $generalPercent = $totalLesson > 0 ? ($stats['generalParts'] / $totalLesson) * 100 : 0;
                            $specPercent = $totalLesson > 0 ? ($stats['specializedParts'] / $totalLesson) * 100 : 0;
                        @endphp
                        <svg viewBox="0 0 36 36" class="h-full w-full">
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e5e7eb" stroke-width="3" class="dark:stroke-slate-700"/>
                            @if($totalLesson > 0)
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#F59E0B" stroke-width="3"
                                        stroke-dasharray="{{ $generalPercent }} {{ 100 - $generalPercent }}"
                                        stroke-dashoffset="25" class="origin-center -rotate-90 transform"/>
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#EC4899" stroke-width="3"
                                        stroke-dasharray="{{ $specPercent }} {{ 100 - $specPercent }}"
                                        stroke-dashoffset="{{ 25 - $generalPercent }}" class="origin-center -rotate-90 transform"/>
                            @endif
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex flex-wrap justify-center gap-3 text-[11px] text-muted">
                    <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-amber-500"></span>عمومی ({{ $stats['generalParts'] }})</span>
                    <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-pink-500"></span>تخصصی ({{ $stats['specializedParts'] }})</span>
                </div>
            </div>

            {{-- پایه تحصیلی --}}
            <div class="rounded-2xl border border-border glass p-4 shadow-sm">
                <h3 class="mb-3 text-center text-sm font-semibold text-foreground">توزیع پایه تحصیلی</h3>
                <div class="flex items-center justify-center">
                    <div class="relative h-32 w-32 sm:h-36 sm:w-36">
                        @php
                            $totalGrade = $stats['grade10Parts'] + $stats['grade11Parts'] + $stats['grade12Parts'];
                            $g10Percent = $totalGrade > 0 ? ($stats['grade10Parts'] / $totalGrade) * 100 : 0;
                            $g11Percent = $totalGrade > 0 ? ($stats['grade11Parts'] / $totalGrade) * 100 : 0;
                            $g12Percent = $totalGrade > 0 ? ($stats['grade12Parts'] / $totalGrade) * 100 : 0;
                        @endphp
                        <svg viewBox="0 0 36 36" class="h-full w-full">
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e5e7eb" stroke-width="3" class="dark:stroke-slate-700"/>
                            @if($totalGrade > 0)
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#06B6D4" stroke-width="3"
                                        stroke-dasharray="{{ $g10Percent }} {{ 100 - $g10Percent }}"
                                        stroke-dashoffset="25" class="origin-center -rotate-90 transform"/>
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#84CC16" stroke-width="3"
                                        stroke-dasharray="{{ $g11Percent }} {{ 100 - $g11Percent }}"
                                        stroke-dashoffset="{{ 25 - $g10Percent }}" class="origin-center -rotate-90 transform"/>
                                <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#EF4444" stroke-width="3"
                                        stroke-dasharray="{{ $g12Percent }} {{ 100 - $g12Percent }}"
                                        stroke-dashoffset="{{ 25 - $g10Percent - $g11Percent }}" class="origin-center -rotate-90 transform"/>
                            @endif
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex flex-wrap justify-center gap-3 text-[11px] text-muted">
                    <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-cyan-500"></span>دهم ({{ $stats['grade10Parts'] }})</span>
                    <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-lime-500"></span>یازدهم ({{ $stats['grade11Parts'] }})</span>
                    <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-rose-500"></span>دوازدهم ({{ $stats['grade12Parts'] }})</span>
                </div>
            </div>

            {{-- منبع پارت --}}
            <div class="rounded-2xl border border-border glass p-4 shadow-sm">
                <h3 class="mb-3 text-center text-sm font-semibold text-foreground">توزیع منبع پارت</h3>
                <div class="flex items-center justify-center">
                    <div class="relative h-32 w-32 sm:h-36 sm:w-36">
                        <svg viewBox="0 0 36 36" class="h-full w-full">
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e5e7eb" stroke-width="3" class="dark:stroke-slate-700"/>
                            @if(count($sourceTypeStats) > 0)
                                @php $srcOffset = 25; @endphp
                                @foreach($sourceTypeStats as $stat)
                                    @if($stat['percent'] > 0)
                                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="{{ $stat['color'] }}" stroke-width="3"
                                                stroke-dasharray="{{ $stat['percent'] }} {{ 100 - $stat['percent'] }}"
                                                stroke-dashoffset="{{ $srcOffset }}" class="origin-center -rotate-90 transform"/>
                                        @php $srcOffset -= $stat['percent']; @endphp
                                    @endif
                                @endforeach
                            @endif
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex flex-wrap justify-center gap-3 text-[11px] text-muted">
                    @foreach($sourceTypeStats as $stat)
                        <span class="flex items-center gap-1">
                            <span class="h-2 w-2 rounded-full" style="background-color: {{ $stat['color'] }}"></span>
                            {{ $stat['label'] }} ({{ $stat['count'] }})
                        </span>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ==================== مودال‌ها ==================== --}}

        {{-- مودال دسترسی --}}
        <div x-cloak x-show="permissionModal"
             class="fixed inset-0 z-[150] flex flex-col justify-end sm:items-center sm:justify-center"
             @keydown.escape.window="permissionModal=false">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="permissionModal=false"></div>
            <div class="relative z-10 w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl border-t sm:border border-white/10 shadow-2xl"
                 style="background:#111;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="sm:hidden flex justify-center pt-3 pb-1">
                    <div class="w-10 h-1 rounded-full bg-white/20"></div>
                </div>
                <div class="flex items-center justify-between px-6 py-4"
                     style="border-bottom:1px solid #1e1e1e;">
                    <h3 class="font-bold text-white">درخواست دسترسی</h3>
                    <button @click="permissionModal=false">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="#666" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-5">
                    <div class="rounded-xl p-4 text-sm space-y-2"
                         style="background:#0d1a2e; border:1px solid #1e3a5f; color:#93c5fd;">
                        <div class="font-semibold mb-2" style="color:#60a5fa;">چرا این دسترسی‌ها نیاز است؟</div>
                        <div>• دسترسی به صدا برای پخش الارم هنگام پایان تایمر</div>
                        <div>• دسترسی به نوتیفیکیشن برای یادآوری‌های مطالعه</div>
                    </div>
                </div>
                <div class="flex justify-end px-6 py-4" style="border-top:1px solid #1e1e1e;">
                    <button wire:click="permissionUnderstood"
                            class="px-6 h-11 rounded-full font-semibold"
                            style="background:#2563eb; color:#fff;">متوجه شدم</button>
                </div>
            </div>
        </div>

        {{-- مودال پایان پارت --}}
        <div x-cloak x-show="finishModal"
             class="fixed inset-0 z-[130] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl"
                 style="background:#111; border:2px solid {{ $isInExtraPhase ? '#7c3aed' : '#16a34a' }};"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="sm:hidden flex justify-center pt-3 pb-1">
                    <div class="w-10 h-1 rounded-full bg-white/20"></div>
                </div>
                <div class="px-6 py-8 text-center space-y-4">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto animate-bounce"
                         style="background:{{ $isInExtraPhase ? '#7c3aed' : '#16a34a' }};">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="3" stroke="white" class="w-9 h-9">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black"
                        style="color:{{ $isInExtraPhase ? '#a78bfa' : '#4ade80' }};">
                        @if($isInExtraPhase) اضافه بر مشاور تمام شد
                        @elseif($pendingIsEarlyFinish) پایان زودهنگام
                        @else آفرین! 🎉
                        @endif
                    </h3>
                    <p class="font-bold text-white">
                        @if($isInExtraPhase) تایم مطالعه اضافه بر مشاور به پایان رسید
                        @else تایم مطالعه به پایان رسید
                        @endif
                    </p>
                    <div class="flex gap-3 justify-center pt-2">
                        <button wire:click="closeFinishModal"
                                class="px-6 h-11 rounded-full font-semibold text-sm"
                                style="background:#1c1c1c; color:#888; border:1px solid #333;">بستن</button>
                        <button wire:click="savePart"
                                class="px-8 h-11 rounded-full font-semibold text-sm"
                                style="background:{{ $isInExtraPhase ? '#7c3aed' : '#16a34a' }}; color:#fff;">
                            ثبت پارت
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- مودال تایید لغو --}}
        <div x-cloak x-show="$wire.showCancelConfirmModal"
             class="fixed inset-0 z-[135] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="closeCancelConfirm"></div>
            <div class="relative z-10 w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl"
                 style="background:#111; border:2px solid #ef4444;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="sm:hidden flex justify-center pt-3 pb-1">
                    <div class="w-10 h-1 rounded-full bg-white/20"></div>
                </div>
                <div class="px-6 py-8 text-center space-y-4">
                    <h3 class="text-xl font-black text-white">لغو تایمر؟</h3>
                    <p class="text-sm" style="color:#aaa;">
                        با لغو تایمر، زمان مطالعه فعلی ثبت نمی‌شود. آیا مطمئن هستید؟
                    </p>
                    <div class="flex gap-3 justify-center pt-2">
                        <button wire:click="closeCancelConfirm"
                                class="px-6 h-11 rounded-full font-semibold text-sm"
                                style="background:#1c1c1c; color:#888; border:1px solid #333;">ادامه مطالعه</button>
                        <button wire:click="{{ $isMakeupMode ? 'cancelMakeup' : 'cancelPart' }}"
                                class="px-8 h-11 rounded-full font-semibold text-sm"
                                style="background:#ef4444; color:#fff;">بله، لغو کن</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- مودال تایید «زودتر تمام کردم» --}}
        <div x-cloak x-show="$wire.showEarlyFinishConfirmModal"
             class="fixed inset-0 z-[135] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"
                 wire:click="closeEarlyFinishConfirm"></div>
            <div class="relative z-10 w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl"
                 style="background:#111; border:2px solid #16a34a;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="sm:hidden flex justify-center pt-3 pb-1">
                    <div class="w-10 h-1 rounded-full bg-white/20"></div>
                </div>
                <div class="px-6 py-8 text-center space-y-4">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto"
                         style="background:#0f2a1a;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             fill="#4ade80" class="w-9 h-9">
                            <path d="M11.983 1.907a.75.75 0 00-1.292-.657l-8.5 9.5A.75.75 0 002.75 12H6v6.5a.75.75 0 001.292.657l8.5-9.5A.75.75 0 0015.25 8H12V1.907z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-white">آیا مطمئنی؟</h3>
                    <p class="text-sm" style="color:#aaa;">
                        با تأیید، این پارت با مدت مطالعه فعلی به‌عنوان «زودتر تمام شد» ثبت می‌شود.
                    </p>
                    <p class="text-xs" style="color:#666;">
                        مدت ثبت شده:
                        <span class="text-white font-bold">{{ $this->formatClock($liveSeconds) }}</span>
                        از {{ $this->formatClock($targetSeconds) }}
                    </p>
                    <div class="flex gap-3 justify-center pt-2">
                        <button wire:click="closeEarlyFinishConfirm"
                                class="px-6 h-11 rounded-full font-semibold text-sm"
                                style="background:#1c1c1c; color:#888; border:1px solid #333;">انصراف</button>
                        <button wire:click="confirmEarlyFinish"
                                class="px-8 h-11 rounded-full font-semibold text-sm"
                                style="background:#16a34a; color:#fff;">بله، ثبت کن</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- مودال «مطالعه بیشتر» --}}
        <div x-cloak x-show="$wire.showStudyMoreModal"
             class="fixed inset-0 z-[135] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"
                 wire:click="closeStudyMoreModal"></div>
            <div class="relative z-10 w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl"
                 style="background:#111; border:2px solid #7c3aed;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="sm:hidden flex justify-center pt-3 pb-1">
                    <div class="w-10 h-1 rounded-full bg-white/20"></div>
                </div>
                <div class="flex items-center justify-between px-6 py-4"
                     style="border-bottom:1px solid #1e1e1e;">
                    <h3 class="font-bold text-white">مطالعه بیشتر (اضافه بر مشاور)</h3>
                    <button wire:click="closeStudyMoreModal">
                        <svg class="w-5 h-5" stroke="#666" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-5 space-y-5">
                    <p class="text-sm" style="color:#aaa;">
                        پس از پایان تایمر فعلی، یک تایمر اضافی به مدت زیر آغاز خواهد شد. حداکثر ۳ ساعت.
                    </p>
                    <div x-data="{
                            h: @entangle('studyMoreHours'),
                            m: @entangle('studyMoreMinutes'),
                            total() { return (parseInt(this.h)||0)*60 + (parseInt(this.m)||0); },
                         }" class="space-y-3" dir="ltr">
                        <div class="flex items-center justify-center gap-3">
                            <div class="flex flex-col items-center gap-1">
                                <button type="button"
                                        @click="if((parseInt(h)||0)<3){ h=(parseInt(h)||0)+1 }"
                                        class="w-9 h-9 rounded-xl flex items-center justify-center"
                                        style="background:#1c1c1c; border:1px solid #2a2a2a;">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                </button>
                                <input type="number" x-model.number="h" min="0" max="3"
                                       class="w-16 h-12 rounded-xl text-center font-bold text-lg"
                                       style="background:#1c1c1c; border:1px solid #2a2a2a; color:#fff;">
                                <button type="button"
                                        @click="if((parseInt(h)||0)>0){ h=(parseInt(h)||0)-1 }"
                                        class="w-9 h-9 rounded-xl flex items-center justify-center"
                                        style="background:#1c1c1c; border:1px solid #2a2a2a;">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <span class="text-xs" style="color:#555;">ساعت</span>
                            </div>
                            <div class="text-2xl font-black pb-6" style="color:#444;">:</div>
                            <div class="flex flex-col items-center gap-1">
                                <button type="button"
                                        @click="m=Math.min((parseInt(m)||0)+5,59)"
                                        class="w-9 h-9 rounded-xl flex items-center justify-center"
                                        style="background:#1c1c1c; border:1px solid #2a2a2a;">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                </button>
                                <input type="number" x-model.number="m" min="0" max="59"
                                       class="w-16 h-12 rounded-xl text-center font-bold text-lg"
                                       style="background:#1c1c1c; border:1px solid #2a2a2a; color:#fff;">
                                <button type="button"
                                        @click="m=Math.max((parseInt(m)||0)-5,0)"
                                        class="w-9 h-9 rounded-xl flex items-center justify-center"
                                        style="background:#1c1c1c; border:1px solid #2a2a2a;">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <span class="text-xs" style="color:#555;">دقیقه</span>
                            </div>
                        </div>
                        <div class="text-center text-xs" style="color:#666;">
                            <template x-if="total() > 180">
                                <span style="color:#f87171;">حداکثر ۳ ساعت — مقدار به ۳ ساعت محدود می‌شود.</span>
                            </template>
                            <template x-if="total() < 1">
                                <span style="color:#f87171;">حداقل ۱ دقیقه را انتخاب کنید.</span>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4"
                     style="border-top:1px solid #1e1e1e;">
                    <button wire:click="closeStudyMoreModal"
                            class="px-5 h-10 rounded-full font-semibold text-sm"
                            style="background:#1c1c1c; color:#888; border:1px solid #333;">انصراف</button>
                    <button wire:click="confirmStudyMore"
                            class="px-6 h-10 rounded-full font-semibold text-sm"
                            style="background:#7c3aed; color:#fff;">شروع پس از پایان</button>
                </div>
            </div>
        </div>

        {{-- مودال پایان جبرانی --}}
        <div x-cloak x-show="makeupFinishModal"
             class="fixed inset-0 z-[130] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl"
                 style="background:#111; border:2px solid #7c3aed;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="sm:hidden flex justify-center pt-3 pb-1">
                    <div class="w-10 h-1 rounded-full bg-white/20"></div>
                </div>
                <div class="px-6 py-8 text-center space-y-4">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto animate-bounce"
                         style="background:#7c3aed;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="3" stroke="white" class="w-9 h-9">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black" style="color:#a78bfa;">عالی! 🎉</h3>
                    <p class="font-bold text-white">تایمر جبرانی به پایان رسید</p>
                    <div class="flex gap-3 justify-center pt-2">
                        <button wire:click="closeMakeupFinishModal"
                                class="px-6 h-11 rounded-full font-semibold text-sm"
                                style="background:#1c1c1c; color:#888; border:1px solid #333;">بستن</button>
                        <button wire:click="saveMakeupSession"
                                class="px-8 h-11 rounded-full font-semibold text-sm"
                                style="background:#7c3aed; color:#fff;">ثبت جلسه</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- مودال بازخورد --}}
        <div x-cloak x-show="feedbackModal"
             class="fixed inset-0 z-[140] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto"
                 style="background:#111; border:1px solid #222;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="sm:hidden flex justify-center pt-3 pb-1">
                    <div class="w-10 h-1 rounded-full bg-white/20"></div>
                </div>
                <div class="flex items-center justify-between px-6 py-4"
                     style="border-bottom:1px solid #1e1e1e;">
                    <h3 class="font-bold text-white">بازخورد جلسه مطالعه</h3>
                </div>
                <div class="px-6 py-5 space-y-5">
                    @if($pendingFeedbackPartName)
                        <div class="rounded-xl p-3 text-sm"
                             style="background:#0d1a2e; border:1px solid #1e3a5f;">
                            <span class="text-xs font-bold px-2 py-0.5 rounded-full"
                                  style="{{ $pendingFeedbackType==='part' ? 'background:#3a2000;color:#fbbf24;' : 'background:#2d1b69;color:#c4b5fd;' }}">
                                {{ $pendingFeedbackType==='part' ? 'پارت برنامه' : 'اضافه بر سازمان' }}
                            </span>
                            <div class="font-bold text-white mt-2">{{ $pendingFeedbackPartName }}</div>
                        </div>
                    @endif
                    <div class="text-center">
                        <p class="text-sm mb-3" style="color:#666;">کیفیت جلسه را امتیاز دهید</p>
                        <div class="flex items-center justify-center gap-1" dir="ltr">
                            @for($i=1; $i<=10; $i++)
                                <button type="button"
                                        wire:click="setFeedbackRating({{ $i }})"
                                        class="transition-transform hover:scale-125"
                                        style="{{ $feedbackRating>=$i ? 'color:#f59e0b;' : 'color:#333;' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="currentColor" class="w-7 h-7">
                                        <path fill-rule="evenodd"
                                              d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        @if($feedbackRating > 0)
                            <div class="text-xs font-bold mt-2"
                                 style="{{ $feedbackRating>=9 ? 'color:#4ade80;' : ($feedbackRating>=7 ? 'color:#60a5fa;' : ($feedbackRating>=5 ? 'color:#fbbf24;' : 'color:#f87171;')) }}">
                                @if($feedbackRating>=9) عالی
                                @elseif($feedbackRating>=7) خوب
                                @elseif($feedbackRating>=5) متوسط
                                @else ضعیف
                                @endif
                                ({{ $feedbackRating }}/10)
                            </div>
                        @endif
                    </div>
                    <textarea wire:model="feedbackComment" rows="2"
                              class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none resize-none"
                              style="background:#1c1c1c; border:1px solid #2a2a2a; color:#fff;"
                              placeholder="نظر یا پیشنهاد..."></textarea>
                </div>
                <div class="flex justify-end px-6 py-4" style="border-top:1px solid #1e1e1e;">
                    <button wire:click="submitFeedback"
                            class="px-6 h-11 rounded-full font-semibold text-sm"
                            style="{{ $feedbackRating<1 ? 'background:#1c1c1c;color:#555;cursor:not-allowed;' : 'background:#2563eb;color:#fff;' }}"
                        {{ $feedbackRating<1 ? 'disabled' : '' }}>
                        ثبت بازخورد
                    </button>
                </div>
            </div>
        </div>

        {{-- مودال مطالعه جبرانی --}}
        <div x-cloak x-show="makeupModal"
             class="fixed inset-0 z-[145] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="makeupModal=false"></div>
            <div class="relative z-10 w-full sm:max-w-lg rounded-t-3xl sm:rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto"
                 style="background:#111; border:1px solid #222;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="sm:hidden flex justify-center pt-3 pb-1">
                    <div class="w-10 h-1 rounded-full bg-white/20"></div>
                </div>
                <div class="flex items-center justify-between px-6 py-4 sticky top-0"
                     style="background:#111; border-bottom:1px solid #1e1e1e; z-index:1;">
                    <h3 class="font-bold text-white text-sm">ثبت ساعت مطالعه اضافه بر سازمان</h3>
                    <button wire:click="closeMakeupModal">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="#666" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-5 space-y-5">

                    {{-- جستجو --}}
                    <div>
                        <label class="block text-xs font-semibold text-white mb-1.5">جستجوی سریع</label>
                        <div class="relative">
                            <input type="text"
                                   wire:model.live.debounce.300ms="makeupSearch"
                                   class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none"
                                   style="background:#1c1c1c; border:1px solid #2a2a2a; color:#fff;"
                                   placeholder="نام درس، فصل یا مبحث...">
                        </div>
                        @if(mb_strlen($makeupSearch) >= 2 && $this->searchResults->isNotEmpty())
                            <div class="mt-1 rounded-xl border max-h-52 overflow-y-auto"
                                 style="border-color:#222; background:#161616;">
                                @foreach($this->searchResults as $r)
                                    <button type="button"
                                            wire:click="selectSearchResult('{{ $r['type'] }}', {{ $r['id'] }})"
                                            class="w-full text-right px-4 py-3 text-sm flex items-start gap-2 hover:bg-white/5 transition"
                                            style="border-bottom:1px solid #1e1e1e;">
                                        <span class="text-xs font-bold px-1.5 py-0.5 rounded-md mt-0.5 flex-shrink-0"
                                              style="{{ $r['type']==='chapter' ? 'background:#0d1a2e;color:#60a5fa;' : 'background:#0f2a1a;color:#4ade80;' }}">
                                            {{ $r['type']==='chapter' ? 'فصل' : 'مبحث' }}
                                        </span>
                                        <div class="min-w-0">
                                            <div class="font-semibold text-white truncate">{{ $r['name'] }}</div>
                                            <div class="text-xs truncate" style="color:#555;">{{ $r['label'] }}</div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- فیلترها --}}
                    <div style="border-top:1px solid #1e1e1e; padding-top:1rem;" class="space-y-3">
                        @if($this->grades->isNotEmpty())
                            <div>
                                <label class="block text-xs font-semibold text-white mb-1">پایه تحصیلی</label>
                                <x-ui.select wire:model.live="makeupGradeId"
                                             wire:key="select-grade"
                                             :options="$this->grades->map(fn($g)=>['id'=>$g->id,'name'=>$g->name])->values()->toArray()"
                                             value-key="id" label-key="name" placeholder="انتخاب پایه..."/>
                            </div>
                        @endif
                        @if($makeupGradeId)
                            <div>
                                <label class="block text-xs font-semibold text-white mb-1">درس</label>
                                <x-ui.select wire:model.live="makeupSubjectId"
                                             wire:key="select-subject-{{ $makeupGradeId }}"
                                             :options="$this->subjects->map(fn($s)=>['id'=>$s->id,'name'=>$s->name])->values()->toArray()"
                                             value-key="id" label-key="name" placeholder="انتخاب درس..."/>
                            </div>
                        @endif
                        @if($makeupSubjectId)
                            <div>
                                <label class="block text-xs font-semibold text-white mb-1">فصل</label>
                                <x-ui.select wire:model.live="makeupChapterId"
                                             wire:key="select-chapter-{{ $makeupSubjectId }}"
                                             :options="$this->chapters->map(fn($c)=>['id'=>$c->id,'name'=>$c->name])->values()->toArray()"
                                             value-key="id" label-key="name" placeholder="انتخاب فصل..."/>
                            </div>
                        @endif
                        @if($makeupChapterId)
                            <div>
                                <label class="block text-xs font-semibold text-white mb-1">مبحث</label>
                                <x-ui.select wire:model.live="makeupTopicId"
                                             wire:key="select-topic-{{ $makeupChapterId }}"
                                             :options="$this->topics->map(fn($t)=>['id'=>$t->id,'name'=>$t->name])->values()->toArray()"
                                             value-key="id" label-key="name" placeholder="انتخاب مبحث..."/>
                            </div>
                        @endif
                    </div>

                    {{-- نوع مطالعه --}}
                    <div style="border-top:1px solid #1e1e1e; padding-top:1rem;">
                        <label class="block text-xs font-semibold text-white mb-2">نوع مطالعه</label>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach([['test','تستی','#1d3a6e','#93c5fd'],['descriptive','تشریحی','#2d1b69','#c4b5fd'],['video','ویدیویی','#1c1a0a','#fbbf24']] as [$val,$lbl,$bg,$clr])
                                <button type="button"
                                        wire:click="$set('makeupPartType','{{ $val }}')"
                                        class="py-3 rounded-xl font-semibold text-sm transition"
                                        style="{{ $makeupPartType===$val ? "background:$bg;color:$clr;border:2px solid $clr;" : 'background:#1c1c1c;color:#666;border:2px solid #222;' }}">
                                    {{ $lbl }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- مدت --}}
                    <div style="border-top:1px solid #1e1e1e; padding-top:1rem;">
                        <label class="block text-xs font-semibold text-white mb-2">مدت زمان</label>
                        <div x-data="{h:@entangle('makeupDurationHours'),m:@entangle('makeupDurationMinutes')}"
                             class="flex items-center gap-3" dir="ltr">
                            <div class="flex flex-col items-center gap-1">
                                <button type="button" @click="h=Math.min(h+1,24)"
                                        class="w-9 h-9 rounded-xl flex items-center justify-center"
                                        style="background:#1c1c1c; border:1px solid #2a2a2a;">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                </button>
                                <input type="number" x-model.number="h"
                                       class="w-14 h-12 rounded-xl text-center font-bold text-lg"
                                       style="background:#1c1c1c; border:1px solid #2a2a2a; color:#fff;">
                                <button type="button" @click="h=Math.max(h-1,0)"
                                        class="w-9 h-9 rounded-xl flex items-center justify-center"
                                        style="background:#1c1c1c; border:1px solid #2a2a2a;">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <span class="text-xs" style="color:#555;">ساعت</span>
                            </div>
                            <div class="text-2xl font-black pb-5" style="color:#444;">:</div>
                            <div class="flex flex-col items-center gap-1">
                                <button type="button" @click="m=Math.min(m+1,59)"
                                        class="w-9 h-9 rounded-xl flex items-center justify-center"
                                        style="background:#1c1c1c; border:1px solid #2a2a2a;">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                </button>
                                <input type="number" x-model.number="m"
                                       class="w-14 h-12 rounded-xl text-center font-bold text-lg"
                                       style="background:#1c1c1c; border:1px solid #2a2a2a; color:#fff;">
                                <button type="button" @click="m=Math.max(m-1,0)"
                                        class="w-9 h-9 rounded-xl flex items-center justify-center"
                                        style="background:#1c1c1c; border:1px solid #2a2a2a;">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <span class="text-xs" style="color:#555;">دقیقه</span>
                            </div>
                        </div>
                    </div>

                    {{-- یادداشت --}}
                    <textarea wire:model="makeupNote" rows="2"
                              class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none resize-none"
                              style="background:#1c1c1c; border:1px solid #2a2a2a; color:#fff;"
                              placeholder="یادداشت (اختیاری)..."></textarea>

                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 sticky bottom-0"
                     style="background:#111; border-top:1px solid #1e1e1e;">
                    <button wire:click="closeMakeupModal"
                            class="px-5 h-10 rounded-full font-semibold text-sm"
                            style="background:#1c1c1c; color:#888; border:1px solid #333;">انصراف</button>
                    <button wire:click="startMakeupTimer"
                            class="px-6 h-10 rounded-full font-semibold text-sm"
                            style="{{ !$makeupTopicId ? 'background:#1c1c1c;color:#555;cursor:not-allowed;' : 'background:#7c3aed;color:#fff;' }}"
                        {{ !$makeupTopicId ? 'disabled' : '' }}>
                        شروع تایمر
                    </button>
                </div>
            </div>
        </div>

        {{-- مودال انتخاب آلارم --}}
        <div x-cloak x-show="$wire.showAlarmModal"
             class="fixed inset-0 z-[200] flex flex-col justify-end sm:items-center sm:justify-center">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"
                 @click="$wire.showAlarmModal = false"></div>
            <div class="relative z-10 w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl"
                 style="background:#111; border:1px solid #222;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0">

                <div class="sm:hidden flex justify-center pt-3 pb-1">
                    <div class="w-10 h-1 rounded-full bg-white/20"></div>
                </div>

                <div class="flex items-center justify-between px-6 py-4"
                     style="border-bottom:1px solid #1e1e1e;">
                    <h3 class="font-bold text-white">انتخاب صدای آلارم</h3>
                    <button wire:click="$set('showAlarmModal', false)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="#666" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-4 space-y-2">
                    @php
                        $alarms = [
                            ['id' => 'Alarmclock', 'label' => 'زنگ کلاسیک', 'desc' => 'صدای زنگ سنتی'],
                            ['id' => 'Bells',      'label' => 'زنگ ملایم',  'desc' => 'صدای ملایم زنگوله'],
                            ['id' => 'Digital',    'label' => 'دیجیتال',    'desc' => 'صدای الکترونیکی'],
                            ['id' => 'Beep',       'label' => 'بیپ',        'desc' => 'صدای کوتاه بیپ'],
                        ];
                    @endphp

                    @foreach($alarms as $alarm)
                        <div class="flex items-center justify-between px-4 py-3 rounded-2xl cursor-pointer transition"
                             style="{{ $selectedAlarm === $alarm['id'] ? 'background:#0d1a2e; border:1px solid #2563eb;' : 'background:#1c1c1c; border:1px solid #222;' }}"
                             wire:click="setAlarm('{{ $alarm['id'] }}')">

                            <div class="flex items-center gap-3">
                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center"
                                     style="{{ $selectedAlarm === $alarm['id'] ? 'border-color:#2563eb; background:#2563eb;' : 'border-color:#444;' }}">
                                    @if($selectedAlarm === $alarm['id'])
                                        <div class="w-2 h-2 rounded-full bg-white"></div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-semibold text-white text-sm">{{ $alarm['label'] }}</div>
                                    <div class="text-xs" style="color:#555;">{{ $alarm['desc'] }}</div>
                                </div>
                            </div>

                            <button type="button"
                                    onclick="event.stopPropagation(); window.previewAlarm('{{ $alarm['id'] }}')"
                                    class="w-9 h-9 rounded-full flex items-center justify-center"
                                    style="background:#2563eb20; border:1px solid #2563eb40;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="2" stroke="#4a9eff" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z"/>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="px-6 py-4" style="border-top:1px solid #1e1e1e;">
                    <p class="text-xs text-center" style="color:#444;">
                        صداها در مرورگر کش می‌شوند — حتی بدون اینترنت پخش می‌شوند
                    </p>
                </div>
            </div>
        </div>

    </div>

    @script
    <script>
        const ALARMS = [
            { id: 'Alarmclock', label: 'زنگ کلاسیک', src: '/client/sounds/Alarmclock.ogg' },
            { id: 'Bells',      label: 'زنگ ملایم',   src: '/client/sounds/Bells.ogg' },
            { id: 'Digital',    label: 'دیجیتال',      src: '/client/sounds/Digital.ogg' },
            { id: 'Beep',       label: 'بیپ',          src: '/client/sounds/Beep.ogg' },
        ];

        const RING_CIRCUMFERENCE = 816.81;

        const audioCache = {};
        function preloadAlarms() {
            ALARMS.forEach(a => {
                const audio = new Audio(a.src);
                audio.preload = 'auto';
                audio.load();
                audioCache[a.id] = audio;
            });
        }

        function getSelectedAlarm() {
            return localStorage.getItem('selected_alarm') || 'Alarmclock';
        }

        function playAlarm() {
            const id = getSelectedAlarm();
            try {
                const src = (audioCache[id] || audioCache['Alarmclock']).src;
                const a = new Audio(src);
                a.volume = 1;
                a.play().catch(e => console.warn('alarm play failed:', e));

                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification('⏰ زمان مطالعه به پایان رسید!', {
                        body: 'پارت مطالعاتی شما با موفقیت تکمیل شد.',
                        icon: '/favicon.ico'
                    });
                }
            } catch(e) { console.warn(e); }
        }

        let clientTimerInterval = null;
        let lastSyncedEndsAt    = null;
        let lastMakeupEndsAt    = null;
        let lastExtraEndsAt     = null;
        let alarmFired          = false;
        let makeupAlarmFired    = false;
        let extraAlarmFired     = false;

        function startClientTimer() {
            if (clientTimerInterval) clearInterval(clientTimerInterval);
            alarmFired = false; makeupAlarmFired = false; extraAlarmFired = false;

            clientTimerInterval = setInterval(() => {
                const now = Math.floor(Date.now() / 1000);

                // تایمر اصلی (فاز ۱)
                const endsAt    = @this.endsAtTs;
                const isRunning = @this.isRunning;
                const isInExtra = @this.isInExtraPhase;

                if (!isInExtra && endsAt && isRunning) {
                    const rem = Math.max(endsAt - now, 0);
                    const tgt = @this.targetSeconds;
                    updateClockDOM('main-clock', rem);
                    updateRingDOM('main-ring', rem, tgt);
                    if (rem === 0 && !alarmFired) {
                        alarmFired = true;
                        playAlarm();
                    @this.call('syncTimers');
                    }
                }

                // فاز ۲ (اضافه بر مشاور)
                const extraEndsAt = @this.extraEndsAtTs;
                if (isInExtra && extraEndsAt && isRunning) {
                    const rem = Math.max(extraEndsAt - now, 0);
                    const tgt = @this.extraTargetSeconds;
                    updateClockDOM('extra-clock', rem);
                    updateRingDOM('extra-ring', rem, tgt);
                    if (rem === 0 && !extraAlarmFired) {
                        extraAlarmFired = true;
                        playAlarm();
                    @this.call('syncTimers');
                    }
                }

                // تایمر جبرانی
                const makeupEndsAt  = @this.makeupEndsAtTs;
                const makeupRunning = @this.makeupTimerRunning;
                if (makeupEndsAt && makeupRunning) {
                    const rem = Math.max(makeupEndsAt - now, 0);
                    const tgt = @this.makeupTargetSeconds;
                    updateClockDOM('makeup-clock', rem);
                    updateRingDOM('makeup-ring', rem, tgt);
                    if (rem === 0 && !makeupAlarmFired) {
                        makeupAlarmFired = true;
                        playAlarm();
                    @this.call('syncTimers');
                    }
                }

            }, 1000);
        }

        // هر ۵ ثانیه سرور sync
        setInterval(() => {
            if (@this.isRunning || @this.makeupTimerRunning) {
            @this.call('syncTimers');
            }
        }, 5000);

        function formatClock(s) {
            s = Math.max(0, s);
            const h   = Math.floor(s / 3600);
            const m   = Math.floor((s % 3600) / 60);
            const sec = s % 60;
            return `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(sec).padStart(2,'0')}`;
        }

        function updateClockDOM(id, seconds) {
            const el = document.getElementById(id);
            if (el) el.textContent = formatClock(seconds);
        }

        function updateRingDOM(id, remaining, target) {
            const el = document.getElementById(id);
            if (!el || !target) return;
            const ratio = Math.min(1, Math.max(0, remaining / target));
            el.style.strokeDashoffset = (RING_CIRCUMFERENCE * (1 - ratio)).toFixed(2);
        }

        window.addEventListener('alarm-selected', e => {
            localStorage.setItem('selected_alarm', e.detail.alarm);
        });

        window.previewAlarm = function(id) {
            const a = new Audio((audioCache[id] || audioCache['Alarmclock']).src);
            a.volume = 0.7;
            a.play().catch(e => console.warn(e));
        };

        window.addEventListener('livewire:initialized', () => {
            preloadAlarms();
            startClientTimer();
            if ('Notification' in window && Notification.permission === 'granted') {
            @this.call('onPermissionsGranted');
            }
        });

        Livewire.hook('morph.updated', () => {
            const endsAt      = @this.endsAtTs;
            const makeupEndsAt = @this.makeupEndsAtTs;
            const extraEndsAt = @this.extraEndsAtTs;
            if (endsAt !== lastSyncedEndsAt)       { alarmFired = false;       lastSyncedEndsAt = endsAt; }
            if (makeupEndsAt !== lastMakeupEndsAt) { makeupAlarmFired = false; lastMakeupEndsAt = makeupEndsAt; }
            if (extraEndsAt !== lastExtraEndsAt)   { extraAlarmFired = false;  lastExtraEndsAt = extraEndsAt; }
        });

        window.addEventListener('request-permissions', async () => {
            try {
                if ('Notification' in window && Notification.permission !== 'granted') {
                    await Notification.requestPermission();
                }
                const a = new Audio(ALARMS[0].src);
                a.volume = 0.01;
                await a.play();
                a.pause();
            } catch(e) { console.warn(e); } finally {
            @this.call('onPermissionsGranted');
            }
        });

        window.addEventListener('play-alarm', () => playAlarm());

        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
            @this.call('syncTimers');
                alarmFired = false; makeupAlarmFired = false; extraAlarmFired = false;
            }
        });

        window.addEventListener('focus', () => @this.call('syncTimers'));

        window.addEventListener('open-feedback-modal', () => {
            setTimeout(() => {
                document.dispatchEvent(new CustomEvent('livewire:update'));
            }, 50);
        });
    </script>
    @endscript

</div>
