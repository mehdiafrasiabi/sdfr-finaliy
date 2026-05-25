<div dir="rtl">

    @assets
    <style>
        @font-face { font-family: 'Digital'; src: url('/client/assets/fonts/digital-7.ttf') format('truetype'); }
        [x-cloak] { display: none !important; }

        /* scrollbar hide */
        .ss-scroll::-webkit-scrollbar { display: none; }
        .ss-scroll { -ms-overflow-style: none; scrollbar-width: none; }

        /* day pill */
        .day-pill { transition: background .18s, color .18s; cursor: pointer; }
        .day-pill.active { background: #2563eb !important; color: #fff !important; }
        .day-pill-wrap.active::after {
            content: '';
            display: block;
            height: 3px;
            border-radius: 9999px;
            background: #2563eb;
            margin-top: 4px;
        }

        /* part card */
        .part-card { transition: background .15s; }
        .part-card:hover { background: #1a1a1a; }

        /* stars canvas */
        #ss-stars {
            position: fixed; bottom: 0; left: 0;
            width: 100%; height: 45%;
            pointer-events: none; z-index: 0; opacity: 0.5;
        }
    </style>
    @endassets



    <div style="background:#0d0d0d; min-height:100vh; color:#fff; position:relative; z-index:1;">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex gap-6 items-start">

                {{-- SIDEBAR (دسکتاپ) --}}
                <div class="hidden md:block flex-shrink-0" style="width:260px; position:sticky; top:24px;">
                    <livewire:client.profile.sidebar/>
                </div>

                {{-- MAIN --}}
                <div class="flex-1 min-w-0"
                     x-data="{
                         permissionModal: @entangle('showPermissionModal'),
                         finishModal:     @entangle('showFinishModal'),
                         makeupFinishModal: @entangle('showMakeupFinishModal'),
                         makeupModal:     @entangle('showMakeupModal'),
                         feedbackModal:   @entangle('showFeedbackModal'),
                         expandedPart: null,
                         selectedDay: {{ $weeklyProgram ? 'null' : 'null' }},
                         togglePart(id) { this.expandedPart = this.expandedPart === id ? null : id; },
                     }">

                    {{-- ===== HEADER ===== --}}
                    <div class="flex items-center justify-between mb-5 px-1">
                        <div class="flex items-center gap-3">
                            {{-- نام برنامه --}}
                            <div>
                                <div class="font-black text-white text-lg tracking-tight">ثبت ساعت مطالعه</div>
                                @if($weeklyProgram)
                                    <div class="text-xs mt-0.5" style="color:#4a9eff;">بر اساس برنامه هفتگی</div>
                                @endif
                            </div>
                        </div>
                        {{-- دکمه نوتیف --}}
                        <div class="w-9 h-9 rounded-full flex items-center justify-center relative" style="background:#1c1c1c;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#aaa" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                            </svg>
                        </div>
                    </div>

                    {{-- ===== WEEK RANGE TABS ===== --}}
                    @if($weeklyProgram)
                        @php
                            $programDays = $this->getProgramDays();
                            $startJalali = \Morilog\Jalali\Jalalian::fromDateTime(\Carbon\Carbon::parse($weeklyProgram->start_date));
                            $endJalali   = \Morilog\Jalali\Jalalian::fromDateTime(\Carbon\Carbon::parse($weeklyProgram->end_date ?? \Carbon\Carbon::parse($weeklyProgram->start_date)->addDays(7)));
                        @endphp

                        {{-- بازه هفته --}}
                        <div class="flex items-center gap-3 mb-3 px-1 ss-scroll overflow-x-auto" style="white-space:nowrap;">
                        <span class="text-xs font-semibold" style="color:#4a9eff;">
                            {{ $startJalali->format('d') }} تا {{ $endJalali->format('d F') }}
                        </span>
                            <span class="text-xs" style="color:#444;">اردیبهشت</span>
                        </div>

                        {{-- دایره‌های روزها --}}
                        <div class="flex items-center gap-2 mb-5 ss-scroll overflow-x-auto pb-1 px-1"
                             x-data="{ selectedDay: @js(\Carbon\Carbon::today()->toDateString()) }">
                            @foreach($programDays as $day)
                                @php
                                    $isToday = $day['date'] === \Carbon\Carbon::today()->toDateString();
                                    $isPast  = $day['date'] < \Carbon\Carbon::today()->toDateString();
                                    $allDone = $day['parts']->count() > 0 && $day['parts']->every(fn($p) => in_array($p->id, $completedParts));
                                    $hasMiss = !$day['is_rest_day'] && $isPast && $day['parts']->filter(fn($p) => !in_array($p->id, $completedParts))->count() > 0;
                                    $jalaliDay = \Morilog\Jalali\Jalalian::fromDateTime(\Carbon\Carbon::parse($day['date']))->format('d');
                                @endphp
                                <div class="flex-shrink-0 flex flex-col items-center gap-1 day-pill-wrap"
                                     :class="selectedDay === '{{ $day['date'] }}' ? 'active' : ''"
                                     @click="selectedDay = '{{ $day['date'] }}'">
                                    <div class="day-pill w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm"
                                         :class="selectedDay === '{{ $day['date'] }}' ? 'active' : ''"
                                         style="
                                     @if($isToday && !$allDone) background:#1d3a6e; color:#4a9eff; border:2px solid #2563eb;
                                     @elseif($allDone) background:#0f2a1a; color:#4ade80; border:2px solid #1e5c35;
                                     @elseif($hasMiss) background:#3a1a1a; color:#f87171; border:2px solid #7f1d1d;
                                     @elseif($day['is_rest_day']) background:#151515; color:#555; border:2px solid #222;
                                     @else background:#151515; color:#666; border:2px solid #222;
                                     @endif">
                                        {{ $jalaliDay }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    @endif

                    {{-- ===== ACTIVE TIMER (sticky) ===== --}}
                    @if($weeklyProgram && ($currentPartId || $makeupTimerRunning || $makeupPausedAtTs))
                        <div class="sticky top-3 z-40 mb-4" wire:poll.1000ms="syncTimers">
                            @if($makeupTimerRunning || $makeupPausedAtTs)
                                {{-- جبرانی --}}
                                <div class="rounded-2xl p-4 text-white" style="background:#1a0a2e; border:1px solid #5b21b6;">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="text-xs font-bold" style="color:#a78bfa;">تایمر مطالعه جبرانی</div>
                                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                              style="{{ $makeupTimerRunning ? 'background:#0f2a1a;color:#4ade80;' : 'background:#3a2000;color:#fbbf24;' }}">
                                    {{ $makeupTimerRunning ? 'در حال اجرا' : 'متوقف' }}
                                </span>
                                    </div>
                                    <div class="text-center mb-3">
                                        <div style="font-family:'Digital',monospace; font-size:48px; color:#c4b5fd; letter-spacing:2px; line-height:1;">
                                            {{ $this->formatClock($makeupRemainingSeconds) }}
                                        </div>
                                    </div>
                                    <div class="w-full rounded-full mb-3" style="height:3px; background:#2d1b69;">
                                        <div class="h-full rounded-full" style="width:{{ $makeupTargetSeconds > 0 ? ($makeupLiveSeconds/$makeupTargetSeconds*100) : 0 }}%; background:#a78bfa; transition:width .3s;"></div>
                                    </div>
                                    <div class="flex gap-2 justify-end">
                                        @if($makeupTimerRunning)
                                            <button wire:click="pauseMakeup" class="px-4 h-9 rounded-full text-xs font-bold" style="background:#7c2d12; color:#fed7aa;">توقف</button>
                                        @elseif($makeupPausedAtTs)
                                            <button wire:click="resumeMakeup" class="px-4 h-9 rounded-full text-xs font-bold" style="background:#0f2a1a; color:#4ade80;">ادامه</button>
                                        @endif
                                        <button wire:click="cancelMakeup" class="px-4 h-9 rounded-full text-xs font-bold" style="background:#1c1c1c; color:#888; border:1px solid #333;">لغو</button>
                                    </div>
                                </div>
                            @elseif($currentPartId)
                                @php $activePart = $programParts->firstWhere('id', $currentPartId); @endphp
                                @if($isInExtraPhase)
                                    {{-- فاز ۲: اضافه بر مشاور --}}
                                    <div class="rounded-2xl p-4 text-white" style="background:#1a0a2e; border:1px solid #5b21b6;" wire:poll.1000ms="syncTimers">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="text-xs font-bold flex items-center gap-2" style="color:#c4b5fd;">
                                                <span class="px-2 py-0.5 rounded-full" style="background:#2d1b69;">اضافه بر مشاور</span>
                                                <span class="text-white">{{ $activePart?->lesson_name ?? '—' }}</span>
                                            </div>
                                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                                  style="{{ $isRunning ? 'background:#0f2a1a;color:#4ade80;' : 'background:#3a2000;color:#fbbf24;' }}">
                                                {{ $isRunning ? 'در حال اجرا' : 'متوقف' }}
                                            </span>
                                        </div>
                                        <div class="text-center mb-3">
                                            <div style="font-family:'Digital',monospace; font-size:48px; color:#a78bfa; letter-spacing:2px; line-height:1;">
                                                {{ $this->formatClock($extraRemainingSeconds) }}
                                            </div>
                                        </div>
                                        <div class="w-full rounded-full mb-3" style="height:3px; background:#2d1b69;">
                                            <div class="h-full rounded-full" style="width:{{ $extraTargetSeconds > 0 ? ($extraLiveSeconds/$extraTargetSeconds*100) : 0 }}%; background:linear-gradient(to left,#a78bfa,#7c3aed); transition:width .3s;"></div>
                                        </div>
                                        <div class="flex gap-2 justify-end">
                                            @if($isRunning)
                                                <button wire:click="pausePart" class="px-4 h-9 rounded-full text-xs font-bold" style="background:#7c2d12; color:#fed7aa;">توقف</button>
                                            @elseif($pausedAtTs)
                                                <button wire:click="resumePart" class="px-4 h-9 rounded-full text-xs font-bold" style="background:#0f2a1a; color:#4ade80;">ادامه</button>
                                            @endif
                                            <button wire:click="cancelPart" class="px-4 h-9 rounded-full text-xs font-bold" style="background:#1c1c1c; color:#888; border:1px solid #333;">لغو تایم اضافه</button>
                                        </div>

                                        @if($this->canShowEarlyFinish)
                                            <div class="mt-3">
                                                <button wire:click="openEarlyFinishConfirm"
                                                        class="w-full h-10 rounded-full text-xs font-bold"
                                                        style="background:#0f2a1a; color:#4ade80; border:1px solid #1e5c35;">
                                                    ⚡ زودتر تمام کردم
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    {{-- فاز ۱: تایمر عادی --}}
                                    <div class="rounded-2xl p-4 text-white" style="background:#0d1117; border:1px solid #2a2a2a;" wire:poll.1000ms="syncTimers">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="text-xs font-bold text-white flex items-center gap-2">
                                                <span>{{ $activePart?->lesson_name ?? '—' }}</span>
                                                @if($pendingExtraTargetSeconds !== null)
                                                    <span class="text-[10px] px-2 py-0.5 rounded-full" style="background:#2d1b69;color:#c4b5fd;">
                                                        + {{ $this->formatClock($pendingExtraTargetSeconds) }} اضافه پس از پایان
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                                  style="{{ $isRunning ? 'background:#0f2a1a;color:#4ade80;' : 'background:#3a2000;color:#fbbf24;' }}">
                                                {{ $isRunning ? 'در حال اجرا' : 'متوقف' }}
                                            </span>
                                        </div>
                                        <div class="text-center mb-3">
                                            <div style="font-family:'Digital',monospace; font-size:48px; color:#f59e0b; letter-spacing:2px; line-height:1;">
                                                {{ $this->formatClock($remainingSeconds) }}
                                            </div>
                                        </div>
                                        <div class="w-full rounded-full mb-3" style="height:3px; background:#1c1c1c;">
                                            <div class="h-full rounded-full" style="width:{{ $targetSeconds > 0 ? (($targetSeconds-$remainingSeconds)/$targetSeconds*100) : 0 }}%; background:linear-gradient(to left,#f59e0b,#ef4444); transition:width .3s;"></div>
                                        </div>
                                        <div class="flex gap-2 justify-end">
                                            @if($isRunning)
                                                <button wire:click="pausePart" class="px-4 h-9 rounded-full text-xs font-bold" style="background:#7c2d12; color:#fed7aa;">توقف</button>
                                            @elseif($pausedAtTs)
                                                <button wire:click="resumePart" class="px-4 h-9 rounded-full text-xs font-bold" style="background:#0f2a1a; color:#4ade80;">ادامه</button>
                                            @endif
                                            <button wire:click="cancelPart" class="px-4 h-9 rounded-full text-xs font-bold" style="background:#1c1c1c; color:#888; border:1px solid #333;">لغو</button>
                                        </div>

                                        {{-- دکمه‌های ۸۰٪ --}}
                                        @if($this->canShowEarlyFinish || $this->canShowStudyMore)
                                            <div class="grid {{ $this->canShowEarlyFinish && $this->canShowStudyMore ? 'grid-cols-2' : 'grid-cols-1' }} gap-2 mt-3">
                                                @if($this->canShowEarlyFinish)
                                                    <button wire:click="openEarlyFinishConfirm"
                                                            class="h-10 rounded-full text-xs font-bold"
                                                            style="background:#0f2a1a; color:#4ade80; border:1px solid #1e5c35;">
                                                        ⚡ زودتر تمام کردم
                                                    </button>
                                                @endif
                                                @if($this->canShowStudyMore)
                                                    <button wire:click="openStudyMoreModal"
                                                            class="h-10 rounded-full text-xs font-bold"
                                                            style="background:#2d1b69; color:#c4b5fd; border:1px solid #5b21b6;">
                                                        ➕ مطالعه بیشتر
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif

                    {{-- ===== PROGRAM PARTS LIST ===== --}}
                    @if($weeklyProgram)

                        {{-- دکمه مطالعه جبرانی --}}
                        @if(!$makeupTimerRunning && !$makeupPausedAtTs && !$currentPartId)
                            <button wire:click="openMakeupModal"
                                    class="w-full mb-4 h-12 rounded-2xl font-bold text-sm flex items-center justify-center gap-2"
                                    style="background:linear-gradient(to left,#4f46e5,#7c3aed); color:#fff;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                ثبت ساعت مطالعه اضافه بر سازمان
                            </button>
                        @endif

                        {{-- لیست روزها و کارت‌ها --}}
                        @php $programDays = $this->getProgramDays(); @endphp

                        <div x-data="{ selectedDay: @js(\Carbon\Carbon::today()->toDateString()) }" class="space-y-1">

                            {{-- دایره‌های روزها (نسخه با x-data) --}}
                            <div class="flex items-center gap-2 mb-4 ss-scroll overflow-x-auto pb-1">
                                @foreach($programDays as $day)
                                    @php
                                        $isToday = $day['date'] === \Carbon\Carbon::today()->toDateString();
                                        $allDone = $day['parts']->count() > 0 && $day['parts']->every(fn($p) => in_array($p->id, $completedParts));
                                        $hasMiss = !$day['is_rest_day'] && $day['date'] < \Carbon\Carbon::today()->toDateString() && $day['parts']->filter(fn($p) => !in_array($p->id, $completedParts))->count() > 0;
                                        $jalDay  = \Morilog\Jalali\Jalalian::fromDateTime(\Carbon\Carbon::parse($day['date']))->format('d');
                                    @endphp
                                    <div class="flex-shrink-0 flex flex-col items-center gap-1 cursor-pointer"
                                         @click="selectedDay = '{{ $day['date'] }}'">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all"
                                             :style="selectedDay === '{{ $day['date'] }}' ? 'background:#2563eb;color:#fff;border:2px solid #2563eb;' : '{{ $isToday ? 'background:#1d3a6e;color:#4a9eff;border:2px solid #2563eb;' : ($allDone ? 'background:#0f2a1a;color:#4ade80;border:2px solid #1e5c35;' : ($hasMiss ? 'background:#3a1a1a;color:#f87171;border:2px solid #7f1d1d;' : 'background:#151515;color:#555;border:2px solid #222;')) }}'">
                                            {{ $jalDay }}
                                        </div>
                                        {{-- خط آبی زیر روز انتخابی --}}
                                        <div class="h-0.5 w-6 rounded-full transition-all"
                                             :style="selectedDay === '{{ $day['date'] }}' ? 'background:#2563eb;' : 'background:transparent;'"></div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- کارت‌های پارت --}}
                            @foreach($programDays as $day)
                                <div x-show="selectedDay === '{{ $day['date'] }}'" x-cloak>

                                    @if($day['is_rest_day'])
                                        <div class="rounded-2xl p-6 text-center" style="background:#111; border:1px solid #1a1a1a;">
                                            <div class="font-bold mb-1" style="color:#4ade80;">روز استراحت 🌿</div>
                                            <div class="text-sm" style="color:#444;">امروز نیازی به مطالعه نیست</div>
                                        </div>

                                    @elseif($day['parts_count'] > 0)
                                        <div class="space-y-2">
                                            @foreach($day['parts']->sortBy('part_order') as $part)
                                                @php
                                                    $isDone    = in_array($part->id, $completedParts);
                                                    $isActive  = $currentPartId == $part->id;
                                                    $isMissed  = !$isDone && $day['date'] < \Carbon\Carbon::today()->toDateString();
                                                @endphp
                                                <div class="part-card rounded-2xl overflow-hidden"
                                                     style="background:#111; border:1px solid #1e1e1e;">

                                                    {{-- ردیف اصلی کارت --}}
                                                    <div class="flex items-center justify-between px-4 py-3 cursor-pointer"
                                                         @click="togglePart({{ $part->id }})">

                                                        {{-- سمت راست: آیکون وضعیت --}}
                                                        <div class="flex items-center gap-3">
                                                            {{-- آیکون --}}
                                                            <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0"
                                                                 style="{{ $isDone ? 'background:#0f2a1a;' : ($isMissed ? 'background:#3a1a1a;' : ($isActive ? 'background:#0d2a4a;' : 'background:#1c1c1c;')) }}">
                                                                @if($isDone)
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#4ade80" class="w-5 h-5">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                                    </svg>
                                                                @elseif($isMissed)
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#f87171" class="w-5 h-5">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                                    </svg>
                                                                @elseif($isActive)
                                                                    <div class="w-2 h-2 rounded-full" style="background:#4a9eff; box-shadow:0 0 8px #4a9eff;"></div>
                                                                @else
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#555" class="w-5 h-5">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                                                    </svg>
                                                                @endif
                                                            </div>

                                                            {{-- تست و دقیقه --}}
                                                            <div class="text-center">
                                                                @if($part->test_count)
                                                                    <div class="font-black text-white" style="font-size:15px; line-height:1;">{{ $part->test_count }}</div>
                                                                    <div class="text-xs" style="color:#555;">تست</div>
                                                                @endif
                                                            </div>
                                                            <div class="text-center">
                                                                <div class="font-black text-white" style="font-size:15px; line-height:1;">{{ $part->duration_minutes }}</div>
                                                                <div class="text-xs" style="color:#555;">دقیقه</div>
                                                            </div>
                                                        </div>

                                                        {{-- سمت چپ: نام درس + فلش --}}
                                                        <div class="flex items-center gap-2">
                                                            <div class="text-right">
                                                                <div class="font-bold text-white" style="font-size:15px;">{{ $part->lesson_name }}</div>
                                                                @if($part->ccSubject)
                                                                    <div class="text-xs" style="color:#555;">{{ $part->ccSubject->name }}</div>
                                                                @endif
                                                                @if($isDone)
                                                                    @php $meta = $completedPartsMeta[$part->id] ?? null; @endphp
                                                                    @if($meta && (
                                                                        ($meta['is_early_finish'] ?? false) ||
                                                                        ($meta['extra_seconds'] ?? 0) > 0 ||
                                                                        ($meta['is_cheating'] ?? false)
                                                                    ))
                                                                        <div class="mt-1">
                                                                            <x-study-session-badges
                                                                                :is-early-finish="(bool)($meta['is_early_finish'] ?? false)"
                                                                                :extra-seconds="(int)($meta['extra_seconds'] ?? 0)"
                                                                                :extra-target-seconds="(int)($meta['extra_target_seconds'] ?? 0)"
                                                                                :is-cheating="(bool)($meta['is_cheating'] ?? false)"
                                                                                :cheat-status="$meta['cheat_status'] ?? null"
                                                                                :cheat-minutes="(int)($meta['cheat_minutes'] ?? 0)" />
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0" style="background:#1c1c1c;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#555" class="w-4 h-4 transition-transform"
                                                                     :style="expandedPart === {{ $part->id }} ? 'transform:rotate(180deg)' : ''">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- جزئیات expand شده --}}
                                                    <div x-show="expandedPart === {{ $part->id }}"
                                                         x-collapse
                                                         x-cloak
                                                         style="border-top:1px solid #1e1e1e;">
                                                        <div class="px-4 py-3 space-y-3">

                                                            {{-- مسیر: درس >> فصل >> مبحث --}}
                                                            <div class="flex items-center gap-1 flex-wrap text-xs" style="color:#666;" dir="rtl">
                                                                @if($part->ccSubject)
                                                                    <span>{{ $part->ccSubject->name }}</span>
                                                                    <span style="color:#333;">«</span>
                                                                @endif
                                                                @if($part->ccChapter)
                                                                    <span>{{ $part->ccChapter->name }}</span>
                                                                    @if($part->ccTopic) <span style="color:#333;">«</span> @endif
                                                                @endif
                                                                @if($part->ccTopic)
                                                                    <span style="color:#4a9eff;">{{ $part->ccTopic->name }}</span>
                                                                @endif
                                                            </div>

                                                            {{-- تگ‌ها --}}
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold"
                                                          style="{{ $part->part_type === 'test' ? 'background:#1d3a6e;color:#93c5fd;' : ($part->part_type === 'descriptive' ? 'background:#2d1b69;color:#c4b5fd;' : 'background:#1c1c1c;color:#888;') }}">
                                                        {{ $part->part_type_label }}
                                                    </span>
                                                                <span class="px-2 py-0.5 rounded-full text-xs" style="background:#1c1c1c; color:#666;">
                                                        {{ $part->lesson_type_label }}
                                                    </span>
                                                            </div>

                                                            {{-- دکمه شروع --}}
                                                            @if($isDone)
                                                                <div class="text-xs font-bold py-2 text-center rounded-xl" style="background:#0f2a1a; color:#4ade80;">✓ تکمیل شده</div>
                                                            @elseif($isActive)
                                                                <div class="text-xs font-bold py-2 text-center rounded-xl" style="background:#0d2a4a; color:#4a9eff;">در حال مطالعه...</div>
                                                            @elseif(!$currentPartId && !$makeupTimerRunning)
                                                                <button wire:click="startPart({{ $part->id }})"
                                                                        class="w-full h-11 rounded-xl font-bold text-sm"
                                                                        style="background:#2563eb; color:#fff;">
                                                                    شروع
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    @else
                                        <div class="rounded-2xl p-6 text-center" style="background:#111; border:1px solid #1a1a1a;">
                                            <div class="text-sm" style="color:#444;">برنامه‌ای برای این روز تنظیم نشده</div>
                                        </div>
                                    @endif

                                </div>
                            @endforeach

                        </div>

                    @else
                        {{-- بدون برنامه --}}
                        <div class="flex flex-col items-center justify-center py-20 text-center">
                            <img src="/client/empty/studySession.png" class="w-48 mb-6 opacity-60" alt="empty"/>
                            <div class="font-bold text-white mb-1">برنامه مطالعاتی وجود ندارد</div>
                            <div class="text-sm" style="color:#444;">هنوز برنامه‌ای برای شما تنظیم نشده است</div>
                        </div>
                    @endif

                    {{-- ===== MODALS (همان مودال‌های قبلی ===== --}}

                    {{-- مودال دسترسی --}}
                    <div x-cloak x-show="permissionModal" class="fixed inset-0 z-[150] flex flex-col justify-end sm:items-center sm:justify-center" @keydown.escape.window="permissionModal=false">
                        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="permissionModal=false"></div>
                        <div class="relative z-10 w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl border-t sm:border border-white/10 shadow-2xl flex flex-col"
                             style="background:#111;"
                             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="sm:hidden flex justify-center pt-3 pb-1"><div class="w-10 h-1 rounded-full bg-white/20"></div></div>
                            <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid #1e1e1e;">
                                <h3 class="font-bold text-white">درخواست دسترسی</h3>
                                <button @click="permissionModal=false"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#666" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                            </div>
                            <div class="px-6 py-5">
                                <div class="rounded-xl p-4 text-sm space-y-2" style="background:#0d1a2e; border:1px solid #1e3a5f; color:#93c5fd;">
                                    <div class="font-semibold mb-2" style="color:#60a5fa;">چرا این دسترسی‌ها نیاز است؟</div>
                                    <div>• دسترسی به صدا برای پخش الارم هنگام پایان تایمر</div>
                                    <div>• دسترسی به نوتیفیکیشن برای یادآوری‌های مطالعه</div>
                                </div>
                            </div>
                            <div class="flex justify-end px-6 py-4" style="border-top:1px solid #1e1e1e;">
                                <button wire:click="permissionUnderstood" class="px-6 h-11 rounded-full font-semibold" style="background:#2563eb; color:#fff;">متوجه شدم</button>
                            </div>
                        </div>
                    </div>

                    {{-- مودال پایان پارت --}}
                    <div x-cloak x-show="finishModal" class="fixed inset-0 z-[75] flex flex-col justify-end sm:items-center sm:justify-center">
                        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="finishModal=false"></div>
                        <div class="relative z-10 w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl"
                             style="background:#111; border:2px solid {{ $isInExtraPhase ? '#7c3aed' : '#16a34a' }};"
                             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="sm:hidden flex justify-center pt-3 pb-1"><div class="w-10 h-1 rounded-full bg-white/20"></div></div>
                            <div class="px-6 py-8 text-center space-y-4">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto animate-bounce"
                                     style="background:{{ $isInExtraPhase ? '#7c3aed' : '#16a34a' }};">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white" class="w-9 h-9"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                </div>
                                <h3 class="text-2xl font-black" style="color:{{ $isInExtraPhase ? '#a78bfa' : '#4ade80' }};">
                                    @if($isInExtraPhase) اضافه بر مشاور تمام شد
                                    @elseif($pendingIsEarlyFinish) پایان زودهنگام
                                    @else آفرین!
                                    @endif
                                </h3>
                                <p class="font-bold text-white">
                                    @if($isInExtraPhase) تایم مطالعه اضافه بر مشاور به پایان رسید
                                    @else تایم مطالعه به پایان رسید
                                    @endif
                                </p>

                                @if($this->isCheatingNow)
                                    <div class="mt-4 text-right space-y-2 px-2">
                                        <div class="flex items-center gap-2 text-sm font-bold" style="color:#f59e0b;">
                                            <span>⚠️</span>
                                            <span>{{ $this->lateMinutes }} دقیقه دیرتر از موعد مجاز ثبت می‌کنید — این به‌عنوان تقلب علامت‌گذاری می‌شود.</span>
                                        </div>
                                        <label class="block text-sm font-bold text-white">علت طول کشیدن</label>
                                        <textarea wire:model.live="finishReason" rows="3"
                                                  placeholder="مثلاً: درگیر تمرین بودم، چند سوال سخت داشتم و..."
                                                  class="w-full rounded-xl p-3 text-sm"
                                                  style="background:#0d0d0d; border:1px solid #333; color:#fff;"></textarea>
                                        <div class="text-xs" style="color:#666;">
                                            بعد از ثبت، مشاور تایید/رد می‌کند. اگر رد شود، این پارت قابل ثبت مجدد نخواهد بود.
                                        </div>
                                    </div>
                                @endif

                                <div class="flex gap-3 justify-center pt-2">
                                    <button wire:click="closeFinishModal" class="px-6 h-11 rounded-full font-semibold text-sm" style="background:#1c1c1c; color:#888; border:1px solid #333;">بستن</button>
                                    <button wire:click="savePart"
                                            @if($this->isCheatingNow && mb_strlen(trim($finishReason)) < 5) disabled @endif
                                            class="px-8 h-11 rounded-full font-semibold text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                            style="background:{{ $isInExtraPhase ? '#7c3aed' : '#16a34a' }}; color:#fff;">
                                        ثبت پارت
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- مودال تایید «زودتر تمام کردم» --}}
                    <div x-cloak x-show="$wire.showEarlyFinishConfirmModal" class="fixed inset-0 z-[85] flex flex-col justify-end sm:items-center sm:justify-center">
                        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="closeEarlyFinishConfirm"></div>
                        <div class="relative z-10 w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl"
                             style="background:#111; border:2px solid #16a34a;"
                             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="sm:hidden flex justify-center pt-3 pb-1"><div class="w-10 h-1 rounded-full bg-white/20"></div></div>
                            <div class="px-6 py-8 text-center space-y-4">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto" style="background:#0f2a1a;">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#4ade80" class="w-9 h-9">
                                        <path d="M11.983 1.907a.75.75 0 00-1.292-.657l-8.5 9.5A.75.75 0 002.75 12H6v6.5a.75.75 0 001.292.657l8.5-9.5A.75.75 0 0015.25 8H12V1.907z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-black text-white">آیا مطمئنی؟</h3>
                                <p class="text-sm" style="color:#aaa;">
                                    با تأیید، این پارت با مدت مطالعه فعلی به‌عنوان «زودتر تمام شد» ثبت می‌شود.
                                </p>
                                <p class="text-xs" style="color:#666;">
                                    @if($isInExtraPhase)
                                        مدت ثبت شده در فاز اضافه بر مشاور: <span class="text-white font-bold">{{ $this->formatClock($extraLiveSeconds) }}</span> از {{ $this->formatClock($extraTargetSeconds) }}
                                    @else
                                        مدت ثبت شده: <span class="text-white font-bold">{{ $this->formatClock($liveSeconds) }}</span> از {{ $this->formatClock($targetSeconds) }}
                                    @endif
                                </p>
                                <div class="flex gap-3 justify-center pt-2">
                                    <button wire:click="closeEarlyFinishConfirm" class="px-6 h-11 rounded-full font-semibold text-sm" style="background:#1c1c1c; color:#888; border:1px solid #333;">انصراف</button>
                                    <button wire:click="confirmEarlyFinish" class="px-8 h-11 rounded-full font-semibold text-sm" style="background:#16a34a; color:#fff;">بله، ثبت کن</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- مودال «مطالعه بیشتر» --}}
                    <div x-cloak x-show="$wire.showStudyMoreModal" class="fixed inset-0 z-[85] flex flex-col justify-end sm:items-center sm:justify-center">
                        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="closeStudyMoreModal"></div>
                        <div class="relative z-10 w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl"
                             style="background:#111; border:2px solid #7c3aed;"
                             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="sm:hidden flex justify-center pt-3 pb-1"><div class="w-10 h-1 rounded-full bg-white/20"></div></div>
                            <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid #1e1e1e;">
                                <h3 class="font-bold text-white">مطالعه بیشتر (اضافه بر مشاور)</h3>
                                <button wire:click="closeStudyMoreModal">
                                    <svg class="w-5 h-5" stroke="#666" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
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
                                            <button type="button" @click="if((parseInt(h)||0)<3){ h=(parseInt(h)||0)+1 }"
                                                    class="w-9 h-9 rounded-xl flex items-center justify-center"
                                                    style="background:#1c1c1c; border:1px solid #2a2a2a;">
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                </svg>
                                            </button>
                                            <input type="number" x-model.number="h" min="0" max="3"
                                                   class="w-16 h-12 rounded-xl text-center font-bold text-lg"
                                                   style="background:#1c1c1c; border:1px solid #2a2a2a; color:#fff;">
                                            <button type="button" @click="if((parseInt(h)||0)>0){ h=(parseInt(h)||0)-1 }"
                                                    class="w-9 h-9 rounded-xl flex items-center justify-center"
                                                    style="background:#1c1c1c; border:1px solid #2a2a2a;">
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                            <span class="text-xs" style="color:#555;">ساعت</span>
                                        </div>
                                        <div class="text-2xl font-black pb-6" style="color:#444;">:</div>
                                        <div class="flex flex-col items-center gap-1">
                                            <button type="button" @click="m=Math.min((parseInt(m)||0)+5,59)"
                                                    class="w-9 h-9 rounded-xl flex items-center justify-center"
                                                    style="background:#1c1c1c; border:1px solid #2a2a2a;">
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                </svg>
                                            </button>
                                            <input type="number" x-model.number="m" min="0" max="59"
                                                   class="w-16 h-12 rounded-xl text-center font-bold text-lg"
                                                   style="background:#1c1c1c; border:1px solid #2a2a2a; color:#fff;">
                                            <button type="button" @click="m=Math.max((parseInt(m)||0)-5,0)"
                                                    class="w-9 h-9 rounded-xl flex items-center justify-center"
                                                    style="background:#1c1c1c; border:1px solid #2a2a2a;">
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
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
                            <div class="flex items-center justify-end gap-3 px-6 py-4" style="border-top:1px solid #1e1e1e;">
                                <button wire:click="closeStudyMoreModal" class="px-5 h-10 rounded-full font-semibold text-sm" style="background:#1c1c1c; color:#888; border:1px solid #333;">انصراف</button>
                                <button wire:click="confirmStudyMore" class="px-6 h-10 rounded-full font-semibold text-sm" style="background:#7c3aed; color:#fff;">شروع پس از پایان</button>
                            </div>
                        </div>
                    </div>

                    {{-- مودال پایان جبرانی --}}
                    <div x-cloak x-show="makeupFinishModal" class="fixed inset-0 z-[70] flex flex-col justify-end sm:items-center sm:justify-center">
                        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
                        <div class="relative z-10 w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl"
                             style="background:#111; border:2px solid #7c3aed;"
                             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="sm:hidden flex justify-center pt-3 pb-1"><div class="w-10 h-1 rounded-full bg-white/20"></div></div>
                            <div class="px-6 py-8 text-center space-y-4">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto animate-bounce" style="background:#7c3aed;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white" class="w-9 h-9"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                </div>
                                <h3 class="text-2xl font-black" style="color:#a78bfa;">عالی!</h3>
                                <p class="font-bold text-white">تایمر جبرانی به پایان رسید</p>
                                <div class="flex gap-3 justify-center pt-2">
                                    <button wire:click="closeMakeupFinishModal" class="px-6 h-11 rounded-full font-semibold text-sm" style="background:#1c1c1c; color:#888; border:1px solid #333;">بستن</button>
                                    <button wire:click="saveMakeupSession" class="px-8 h-11 rounded-full font-semibold text-sm" style="background:#7c3aed; color:#fff;">ثبت جلسه</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- مودال بازخورد --}}
                    <div x-cloak x-show="feedbackModal" class="fixed inset-0 z-[60] flex flex-col justify-end sm:items-center sm:justify-center">
                        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
                        <div class="relative z-10 w-full sm:max-w-md rounded-t-3xl sm:rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto"
                             style="background:#111; border:1px solid #222;"
                             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="sm:hidden flex justify-center pt-3 pb-1"><div class="w-10 h-1 rounded-full bg-white/20"></div></div>
                            <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid #1e1e1e;">
                                <h3 class="font-bold text-white">بازخورد جلسه مطالعه</h3>
                            </div>
                            <div class="px-6 py-5 space-y-5">
                                @if($pendingFeedbackPartName)
                                    <div class="rounded-xl p-3 text-sm" style="background:#0d1a2e; border:1px solid #1e3a5f;">
                                    <span class="text-xs font-bold px-2 py-0.5 rounded-full" style="{{ $pendingFeedbackType==='part' ? 'background:#3a2000;color:#fbbf24;' : 'background:#2d1b69;color:#c4b5fd;' }}">
                                        {{ $pendingFeedbackType==='part' ? 'پارت برنامه' : 'اضافه بر سازمان' }}
                                    </span>
                                        <div class="font-bold text-white mt-2">{{ $pendingFeedbackPartName }}</div>
                                    </div>
                                @endif
                                <div class="text-center">
                                    <p class="text-sm mb-3" style="color:#666;">کیفیت جلسه را امتیاز دهید</p>
                                    <div class="flex items-center justify-center gap-1" dir="ltr">
                                        @for($i=1;$i<=10;$i++)
                                            <button type="button" wire:click="setFeedbackRating({{ $i }})"
                                                    class="transition-transform hover:scale-125"
                                                    style="{{ $feedbackRating>=$i ? 'color:#f59e0b;' : 'color:#333;' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7">
                                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        @endfor
                                    </div>
                                    @if($feedbackRating>0)
                                        <div class="text-xs font-bold mt-2" style="{{ $feedbackRating>=9?'color:#4ade80;':($feedbackRating>=7?'color:#60a5fa;':($feedbackRating>=5?'color:#fbbf24;':'color:#f87171;')) }}">
                                            @if($feedbackRating>=9)عالی @elseif($feedbackRating>=7)خوب @elseif($feedbackRating>=5)متوسط @else ضعیف @endif
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
                    <div x-cloak x-show="makeupModal" class="fixed inset-0 z-[100] flex flex-col justify-end sm:items-center sm:justify-center">
                        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="makeupModal=false"></div>
                        <div class="relative z-10 w-full sm:max-w-lg rounded-t-3xl sm:rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto"
                             style="background:#111; border:1px solid #222;"
                             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="sm:hidden flex justify-center pt-3 pb-1"><div class="w-10 h-1 rounded-full bg-white/20"></div></div>
                            <div class="flex items-center justify-between px-6 py-4 sticky top-0" style="background:#111; border-bottom:1px solid #1e1e1e;">
                                <h3 class="font-bold text-white text-sm">ثبت ساعت مطالعه اضافه بر سازمان</h3>
                                <button wire:click="closeMakeupModal"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#666" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>
                            </div>
                            <div class="px-6 py-5 space-y-5">
                                {{-- جستجو --}}
                                <div>
                                    <label class="block text-xs font-semibold text-white mb-1.5">جستجوی سریع</label>
                                    <div class="relative">
                                        <input type="text" wire:model.live.debounce.300ms="makeupSearch"
                                               class="w-full rounded-xl px-4 py-3 text-sm focus:outline-none"
                                               style="background:#1c1c1c; border:1px solid #2a2a2a; color:#fff;"
                                               placeholder="نام درس، فصل یا مبحث...">
                                    </div>
                                    @if(mb_strlen($makeupSearch)>=2 && $this->searchResults->isNotEmpty())
                                        <div class="mt-1 rounded-xl border max-h-52 overflow-y-auto" style="border-color:#222; background:#161616;">
                                            @foreach($this->searchResults as $r)
                                                <button type="button" wire:click="selectSearchResult('{{ $r['type'] }}', {{ $r['id'] }})"
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
                                            <x-ui.select wire:model.live="makeupGradeId" wire:key="select-grade"
                                                         :options="$this->grades->map(fn($g)=>['id'=>$g->id,'name'=>$g->name])->values()->toArray()"
                                                         value-key="id" label-key="name" placeholder="انتخاب پایه..."/>
                                        </div>
                                    @endif
                                    @if($makeupGradeId)
                                        <div>
                                            <label class="block text-xs font-semibold text-white mb-1">درس</label>
                                            <x-ui.select wire:model.live="makeupSubjectId" wire:key="select-subject-{{ $makeupGradeId }}"
                                                         :options="$this->subjects->map(fn($s)=>['id'=>$s->id,'name'=>$s->name])->values()->toArray()"
                                                         value-key="id" label-key="name" placeholder="انتخاب درس..."/>
                                        </div>
                                    @endif
                                    @if($makeupSubjectId)
                                        <div>
                                            <label class="block text-xs font-semibold text-white mb-1">فصل</label>
                                            <x-ui.select wire:model.live="makeupChapterId" wire:key="select-chapter-{{ $makeupSubjectId }}"
                                                         :options="$this->chapters->map(fn($c)=>['id'=>$c->id,'name'=>$c->name])->values()->toArray()"
                                                         value-key="id" label-key="name" placeholder="انتخاب فصل..."/>
                                        </div>
                                    @endif
                                    @if($makeupChapterId)
                                        <div>
                                            <label class="block text-xs font-semibold text-white mb-1">مبحث</label>
                                            <x-ui.select wire:model.live="makeupTopicId" wire:key="select-topic-{{ $makeupChapterId }}"
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
                                            <button type="button" wire:click="$set('makeupPartType','{{ $val }}')"
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
                                    <div x-data="{h:@entangle('makeupDurationHours'),m:@entangle('makeupDurationMinutes')}" class="flex items-center gap-3" dir="ltr">
                                        <div class="flex flex-col items-center gap-1">
                                            <button type="button" @click="h=Math.min(h+1,24)" class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#1c1c1c; border:1px solid #2a2a2a;">
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                            </button>
                                            <input type="number" x-model.number="h" class="w-14 h-12 rounded-xl text-center font-bold text-lg" style="background:#1c1c1c; border:1px solid #2a2a2a; color:#fff;">
                                            <button type="button" @click="h=Math.max(h-1,0)" class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#1c1c1c; border:1px solid #2a2a2a;">
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </button>
                                            <span class="text-xs" style="color:#555;">ساعت</span>
                                        </div>
                                        <div class="text-2xl font-black pb-5" style="color:#444;">:</div>
                                        <div class="flex flex-col items-center gap-1">
                                            <button type="button" @click="m=Math.min(m+1,59)" class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#1c1c1c; border:1px solid #2a2a2a;">
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                            </button>
                                            <input type="number" x-model.number="m" class="w-14 h-12 rounded-xl text-center font-bold text-lg" style="background:#1c1c1c; border:1px solid #2a2a2a; color:#fff;">
                                            <button type="button" @click="m=Math.max(m-1,0)" class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#1c1c1c; border:1px solid #2a2a2a;">
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
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
                            <div class="flex items-center justify-end gap-3 px-6 py-4 sticky bottom-0" style="background:#111; border-top:1px solid #1e1e1e;">
                                <button wire:click="closeMakeupModal" class="px-5 h-10 rounded-full font-semibold text-sm" style="background:#1c1c1c; color:#888; border:1px solid #333;">انصراف</button>
                                <button wire:click="startMakeupTimer" class="px-6 h-10 rounded-full font-semibold text-sm"
                                        style="{{ !$makeupTopicId ? 'background:#1c1c1c;color:#555;cursor:not-allowed;' : 'background:#7c3aed;color:#fff;' }}"
                                    {{ !$makeupTopicId ? 'disabled' : '' }}>
                                    شروع تایمر
                                </button>
                            </div>
                        </div>
                    </div>

                </div>{{-- end main --}}
            </div>
        </div>
        {{-- ستاره‌های پس‌زمینه --}}
        <canvas id="ss-stars"></canvas>
    </div>

    @script
    <script>
        /* ===== ستاره‌های دنباله‌دار ===== */
        (function() {
            const canvas = document.getElementById('ss-stars');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            const dpr = window.devicePixelRatio || 1;
            function resize() {
                canvas.width  = canvas.offsetWidth  * dpr;
                canvas.height = canvas.offsetHeight * dpr;
                ctx.scale(dpr, dpr);
            }
            resize();
            window.addEventListener('resize', resize);
            const W = () => canvas.offsetWidth, H = () => canvas.offsetHeight;
            const BG = Array.from({length:130}, () => ({
                x: Math.random(), y: Math.random(),
                r: Math.random()*1.1+0.2, a: Math.random()*0.5+0.1,
                t: Math.random()*Math.PI*2, s: Math.random()*0.018+0.005
            }));
            function Comet() { this.reset(true); }
            Comet.prototype.reset = function(init) {
                const w=W(),h=H();
                this.x=init?Math.random()*w:-100;
                this.y=init?Math.random()*h*0.7:Math.random()*h*0.5;
                this.vx=3.2+Math.random()*2.8; this.vy=1+Math.random()*1.8;
                this.len=80+Math.random()*70; this.a=0; this.life=0;
                this.maxLife=150+Math.random()*100; this.r=1.6+Math.random()*1.2;
                this.col=Math.random()>.45?'150,200,255':'210,225,255';
            };
            Comet.prototype.update=function(){
                this.x+=this.vx; this.y+=this.vy; this.life++;
                var f=30;
                if(this.life<f) this.a=this.life/f;
                else if(this.life>this.maxLife-f) this.a=Math.max(0,(this.maxLife-this.life)/f);
                else this.a=1;
                if(this.x>W()+120||this.y>H()+60) this.reset(false);
            };
            Comet.prototype.draw=function(){
                var ang=Math.atan2(this.vy,this.vx);
                var tx=this.x-this.len*Math.cos(ang), ty=this.y-this.len*Math.sin(ang);
                var g=ctx.createLinearGradient(tx,ty,this.x,this.y);
                g.addColorStop(0,'rgba('+this.col+',0)');
                g.addColorStop(.5,'rgba('+this.col+','+(this.a*.25)+')');
                g.addColorStop(1,'rgba('+this.col+','+this.a+')');
                ctx.save(); ctx.strokeStyle=g; ctx.lineWidth=this.r; ctx.lineCap='round';
                ctx.beginPath(); ctx.moveTo(tx,ty); ctx.lineTo(this.x,this.y); ctx.stroke();
                ctx.beginPath(); ctx.arc(this.x,this.y,this.r*2,0,Math.PI*2);
                ctx.fillStyle='rgba(210,230,255,'+(this.a*.7)+')'; ctx.fill();
                ctx.beginPath(); ctx.arc(this.x,this.y,this.r*.7,0,Math.PI*2);
                ctx.fillStyle='rgba(255,255,255,'+this.a+')'; ctx.fill();
                ctx.restore();
            };
            function Spark(x,y){
                this.x=x;this.y=y;
                this.vx=(Math.random()-.5)*1.4; this.vy=(Math.random()-.5)*1.4-.4;
                this.life=0; this.maxLife=20+Math.random()*18; this.r=.6+Math.random();
            }
            Spark.prototype.update=function(){this.x+=this.vx;this.y+=this.vy;this.vy+=.05;this.life++;};
            Spark.prototype.draw=function(){
                var a=(1-this.life/this.maxLife)*.7;
                ctx.beginPath();ctx.arc(this.x,this.y,this.r,0,Math.PI*2);
                ctx.fillStyle='rgba(180,215,255,'+a+')';ctx.fill();
            };
            var comets=[new Comet(),new Comet()], sparks=[], frame=0, next=100;
            function loop(){
                var w=W(),h=H(); ctx.clearRect(0,0,w,h); frame++;
                BG.forEach(function(s){
                    s.t+=s.s; var a=s.a*(0.55+0.45*Math.sin(s.t));
                    ctx.beginPath();ctx.arc(s.x*w,s.y*h,s.r,0,Math.PI*2);
                    ctx.fillStyle='rgba(200,220,255,'+a+')';ctx.fill();
                });
                if(frame>=next){comets.push(new Comet());next=frame+80+Math.random()*140;}
                comets.forEach(function(c){
                    c.update();c.draw();
                    if(Math.random()<.35)sparks.push(new Spark(c.x,c.y));
                });
                sparks.forEach(function(s){s.update();s.draw();});
                sparks=sparks.filter(function(s){return s.life<s.maxLife;});
                requestAnimationFrame(loop);
            }
            loop();
        })();

        /* ===== Livewire events ===== */
        window.addEventListener('livewire:initialized',()=>{
            if('Notification' in window && Notification.permission==='granted') @this.call('onPermissionsGranted');
        });
        window.addEventListener('request-permissions', async ()=>{
            try {
                if('Notification' in window && Notification.permission!=='granted') await Notification.requestPermission();
                const a=new Audio('/client/sounds/Alarmclock.ogg'); a.volume=0.01; await a.play(); a.pause();
            } catch(e){ console.warn(e); } finally { @this.call('onPermissionsGranted'); }
        });
        window.addEventListener('play-alarm',()=>{
            try {
                const a=new Audio('/client/sounds/Alarmclock.ogg'); a.volume=1; a.play().catch(e=>console.warn(e));
                if('Notification' in window && Notification.permission==='granted')
                    new Notification('⏰ زمان مطالعه به پایان رسید!',{body:'پارت مطالعاتی شما با موفقیت تکمیل شد.',icon:'/favicon.ico'});
            } catch(e){ console.warn(e); }
        });
        document.addEventListener('visibilitychange',()=>{ if(!document.hidden) @this.call('syncTimers'); });
        window.addEventListener('focus',()=>{ @this.call('syncTimers'); });
    </script>
    @endscript

</div>
