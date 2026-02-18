<div dir="rtl">

    @assets
    <style>
        @font-face {
            font-family: 'Digital';
            src: url('/client/assets/fonts/digital-7.ttf') format('truetype');
        }
        [x-cloak] { display: none !important; }
    </style>
    @endassets


    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8"
                 x-data="{
                    permissionModal: @entangle('showPermissionModal'),
                    finishModal: @entangle('showFinishModal'),
                    makeupFinishModal: @entangle('showMakeupFinishModal'),
                    makeupModal: @entangle('showMakeupModal'),
                    feedbackModal: @entangle('showFeedbackModal')
                 }">

                {{-- Header --}}
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-4 mb-6">
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-1 opacity-70">
                            <div class="w-1.5 h-1.5 bg-foreground/70 rounded-full"></div>
                            <div class="w-2.5 h-2.5 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground text-sm sm:text-base">
                            ثبت ساعت مطالعه بر اساس برنامه
                        </div>
                    </div>
                </div>

                <!-- Guide Section -->
                <div
                    dir="rtl"
                    x-data="collapseGuide('studySession-guide')"
                    x-init="init()"
                    class="rounded-2xl border border-border bg-primary overflow-hidden transition-all">

                    <button
                        @click="toggle"
                        class="w-full flex items-center justify-between px-4 md:px-6 py-4 transition">

                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 14h-2v-2h2v2zm0-4h-2V6h2v6z"/>
                            </svg>

                            <span class="font-black text-white text-blue-300 md:text-lg">
                                راهنمای ثبت ساعت مطالعه
                            </span>
                        </div>

                        <svg
                            class="w-5 h-5 text-white transition-transform duration-300"
                            :class="open && 'rotate-180'"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div
                        x-show="open"
                        x-cloak
                        x-transition:enter="transition ease-out duration-600"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1"
                        class="px-4 md:px-6 pb-6">

                        <div class="flex flex-col md:flex-row-reverse gap-6 items-center mt-2">
                            <div class="relative w-full md:w-[280px] shrink-0 order-2 md:order-1">
                                <img
                                    src="/client/assets/images/blog/sdfr.jpg"
                                    class="w-full h-[200px] md:h-[180px] object-cover rounded-xl"
                                    alt="راهنمای ثبت ساعت مطالعه">

                                <button
                                    type="button"
                                    id="57612318744"
                                    data-video-url="https://www.aparat.com/video/video/embed/videohash/utg98i1/vt/frame?titleShow=true&recom=self"
                                    allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"
                                    data-video-title="راهنمای ثبت ساعت مطالعه"
                                    class="absolute inset-0 flex items-center justify-center">
                                    <span
                                        class="w-14 h-14 rounded-full bg-white/90 dark:bg-black/60 flex items-center justify-center shadow-lg transition">
                                        <svg class="w-7 h-7 text-blue-600 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </span>
                                </button>
                            </div>

                            <div class="flex-1 text-right text-sm md:text-base text-white leading-7 order-1 md:order-2">
                                دانش‌آموز عزیز سلام، قبل از شروع مطالعه موارد زیر را با دقت بخوانید:
                                <br>• استفاده از آخرین نسخه مرورگر کروم الزامی است.
                                <br>• حتماً قبل از خروج ثبت نهایی انجام شود.
                                <br>• می‌توانید هر زمان که بخواهید ساعت مطالعه خارج از چارچوب برنامه ثبت کنید.
                                <br>• پس از پایان هر جلسه، حتماً بازخورد خود را ثبت کنید.
                            </div>
                        </div>
                    </div>
                </div>

                <br>

                @if($weeklyProgram)

                    {{-- تایمر یکپارچه (عادی + اضافه بر سازمان) --}}
                    <div
                        class="sticky top-3 z-40"
                        wire:key="timer-unified"
                        wire:poll.visible.1000ms="{{ $makeupTimerRunning ? 'tickMakeup' : 'tick' }}">

                        @if($makeupTimerRunning || $makeupPausedAtTs)
                            {{-- حالت اضافه بر سازمان (بنفش) --}}
                            <section
                                class="rounded-3xl p-4 sm:p-6 text-white relative overflow-hidden border border-white/15
                                       bg-gradient-to-br from-violet-500/95 to-purple-700/95
                                       shadow-[0_18px_40px_rgba(0,0,0,.14)] dark:shadow-[0_18px_40px_rgba(0,0,0,.40)]">

                                <div class="pointer-events-none absolute -top-20 -left-20 w-72 h-72 rounded-full bg-violet-400/20 blur-3xl"></div>
                                <div class="pointer-events-none absolute -bottom-24 -right-24 w-72 h-72 rounded-full bg-purple-400/20 blur-3xl"></div>

                                <div class="flex flex-col gap-4 relative">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                        <div class="space-y-1">
                                            <div class="text-sm text-violet-100 font-extrabold tracking-tight flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                                </svg>
                                                تایمر مطالعه جبرانی
                                            </div>

                                            <div class="text-[11px] text-violet-100/90 leading-5">
                                                <span class="{{ $makeupTimerRunning ? 'text-emerald-200' : 'text-orange-200' }} font-semibold">
                                                    {{ $makeupTimerRunning ? 'در حال اجرا' : 'متوقف' }}
                                                </span>
                                            </div>

                                            <div class="mt-1 inline-flex items-center gap-2 text-[10px] text-violet-100/80">
                                                <span
                                                    class="w-2 h-2 rounded-full bg-slate-300/55 shadow-[0_0_10px_rgba(148,163,184,.25)]
                                                           {{ $makeupTimerRunning ? 'bg-emerald-400 shadow-[0_0_14px_rgba(52,211,153,.45)]' : '' }}">
                                                </span>
                                                <span>{{ $makeupTimerRunning ? 'زنده' : 'متوقف' }}</span>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2 justify-end">
                                            @if($makeupTimerRunning)
                                                <button wire:click="pauseMakeup"
                                                        class="px-4 h-10 rounded-full bg-orange-500/90 hover:bg-orange-500
                                                               focus:outline-none focus:ring-2 focus:ring-orange-300/50
                                                               font-semibold text-xs transition">
                                                    توقف
                                                </button>
                                            @elseif($makeupPausedAtTs)
                                                <button wire:click="resumeMakeup"
                                                        class="px-4 h-10 rounded-full bg-emerald-500/90 hover:bg-emerald-500
                                                               focus:outline-none focus:ring-2 focus:ring-emerald-300/50
                                                               font-semibold text-xs transition">
                                                    ادامه
                                                </button>
                                            @endif

                                            <button wire:click="cancelMakeup"
                                                    class="px-4 h-10 rounded-full bg-white/10 hover:bg-white/15 border border-white/15
                                                           focus:outline-none focus:ring-2 focus:ring-white/20
                                                           font-semibold text-xs transition">
                                                لغو
                                            </button>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <div
                                            class="font-['Digital',ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,'Liberation_Mono','Courier_New',monospace]
                                                   text-[clamp(40px,6vw,56px)] tracking-[2px] leading-none
                                                   text-violet-200 [text-shadow:0_0_12px_rgba(196,181,253,.35)]">
                                            {{ $this->formatClock($makeupRemainingSeconds) }}
                                        </div>
                                        <div class="mt-1 text-[11px] text-violet-100/80">زمان باقی‌مانده</div>
                                    </div>

                                    <div class="space-y-2">
                                        <div class="w-full bg-violet-200/20 rounded-full h-3 overflow-hidden">
                                            <div class="h-full bg-violet-300 transition-all duration-300"
                                                 style="width: {{ $makeupTargetSeconds > 0 ? ($makeupLiveSeconds / $makeupTargetSeconds * 100) : 0 }}%">
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between text-[10px] text-violet-100/80">
                                            <span>مصرف‌شده: <span class="text-violet-100 font-semibold">{{ $this->formatClock($makeupLiveSeconds) }}</span></span>
                                            <span>کل: <span class="text-violet-100 font-semibold">{{ $this->formatClock($makeupTargetSeconds) }}</span></span>
                                        </div>
                                    </div>
                                </div>
                            </section>

                        @else
                            {{-- حالت عادی (طلایی/نارنجی) --}}
                            <section
                                class="rounded-3xl p-4 sm:p-6 text-white relative overflow-hidden border border-slate-300/20
                                       bg-gradient-to-br from-slate-900/95 to-slate-950/95
                                       dark:from-slate-950/90 dark:to-slate-900/90
                                       shadow-[0_18px_40px_rgba(0,0,0,.14)] dark:shadow-[0_18px_40px_rgba(0,0,0,.40)]">

                                <div class="pointer-events-none absolute -top-20 -left-20 w-72 h-72 rounded-full bg-amber-500/10 blur-3xl"></div>
                                <div class="pointer-events-none absolute -bottom-24 -right-24 w-72 h-72 rounded-full bg-emerald-500/10 blur-3xl"></div>

                                <div class="flex flex-col gap-4 relative">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                        <div class="space-y-1">
                                            <div class="text-sm text-slate-100 font-extrabold tracking-tight">تایمر مطالعه</div>
                                            <div class="text-[11px] text-slate-200/90 leading-5">
                                                @if($currentPartId)
                                                    <span class="text-white font-semibold">پارت:</span>
                                                    <span class="text-slate-100">{{ $programParts->firstWhere('id', $currentPartId)?->lesson_name ?? '—' }}</span>
                                                    <span class="mx-2 text-slate-400">&bull;</span>
                                                    <span class="{{ $isRunning ? 'text-emerald-200' : 'text-orange-200' }} font-semibold">
                                                        {{ $isRunning ? 'در حال اجرا' : 'متوقف' }}
                                                    </span>
                                                @else
                                                    هیچ پارت فعالی انتخاب نشده — از لیست پایین یک پارت را شروع کن.
                                                @endif
                                            </div>

                                            <div class="mt-1 inline-flex items-center gap-2 text-[10px] text-slate-200/80">
                                                <span
                                                    class="w-2 h-2 rounded-full bg-slate-300/55 shadow-[0_0_10px_rgba(148,163,184,.25)]
                                                           {{ ($currentPartId && $isRunning) ? 'bg-emerald-400 shadow-[0_0_14px_rgba(52,211,153,.45)]' : '' }}">
                                                </span>
                                                <span>{{ ($currentPartId && $isRunning) ? 'زنده' : 'آماده' }}</span>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2 justify-end">
                                            @if($currentPartId && $isRunning)
                                                <button wire:click="pausePart"
                                                        class="px-4 h-10 rounded-full bg-orange-500/90 hover:bg-orange-500
                                                               focus:outline-none focus:ring-2 focus:ring-orange-300/50
                                                               font-semibold text-xs transition inline-flex items-center gap-2">
                                                    توقف
                                                </button>
                                            @elseif($currentPartId && !$isRunning && $pausedAtTs)
                                                <button wire:click="resumePart"
                                                        class="px-4 h-10 rounded-full bg-emerald-500/90 hover:bg-emerald-500
                                                               focus:outline-none focus:ring-2 focus:ring-emerald-300/50
                                                               font-semibold text-xs transition inline-flex items-center gap-2">
                                                    ادامه
                                                </button>
                                            @endif

                                            @if($currentPartId)
                                                <button wire:click="cancelPart"
                                                        class="px-4 h-10 rounded-full bg-white/10 hover:bg-white/15 border border-white/15
                                                               focus:outline-none focus:ring-2 focus:ring-white/20
                                                               font-semibold text-xs transition inline-flex items-center gap-2">
                                                    لغو
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <div
                                            class="font-['Digital',ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,'Liberation_Mono','Courier_New',monospace]
                                                   text-[clamp(40px,6vw,56px)] tracking-[2px] leading-none
                                                   text-amber-500 [text-shadow:0_0_12px_rgba(245,158,11,.35)]">
                                            {{ $this->formatClock($currentPartId ? $remainingSeconds : 0) }}
                                        </div>
                                        <div class="mt-1 text-[11px] text-slate-200/80">
                                            {{ $currentPartId ? 'زمان باقی‌مانده' : 'برای شروع، روی دکمه شروع یکی از پارت‌ها بزنید' }}
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <div class="w-full bg-slate-700/30 rounded-full h-3 overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 transition-all duration-300"
                                                 style="width: {{ $currentPartId && $targetSeconds > 0 ? (($targetSeconds - $remainingSeconds) / $targetSeconds * 100) : 0 }}%">
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between text-[10px] text-slate-200/80">
                                            <span>مصرف‌شده: <span class="text-slate-100 font-semibold">{{ $currentPartId ? $this->formatClock($targetSeconds - $remainingSeconds) : '00:00:00' }}</span></span>
                                            <span>کل: <span class="text-slate-100 font-semibold">{{ $currentPartId ? $this->formatClock($targetSeconds) : '00:00:00' }}</span></span>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @endif
                    </div>

                    <div class="h-3 sm:h-4"></div>

                    {{-- دکمه ثبت ساعت مطالعه اضافه بر سازمان --}}
                    @if(!$makeupTimerRunning && !$makeupPausedAtTs && !$currentPartId)
                        <div class="mt-4">
                            <button wire:click="openMakeupModal"
                                    wire:loading.attr="disabled"
                                    class="w-full sm:w-auto px-6 h-12 rounded-2xl bg-gradient-to-l from-violet-600 to-indigo-600
                                           hover:from-violet-700 hover:to-indigo-700 text-white font-bold text-sm transition
                                           shadow-lg shadow-violet-500/25 inline-flex items-center justify-center gap-2
                                           disabled:opacity-60 disabled:cursor-not-allowed">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                     stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>

                                <span>ثبت ساعت مطالعه خارج از چارچوب برنامه</span>

                                <span wire:loading wire:target="openMakeupModal"
                                      class="inline-block w-3.5 h-3.5 rounded-full border-2 border-white/35 border-t-white animate-spin"></span>
                            </button>
                        </div>
                    @endif

                    {{-- ابزارها --}}
                    <section class="mt-5 bg-secondary rounded-3xl p-4 sm:p-5">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <div class="font-black text-foreground">برنامه مطالعاتی من</div>
                                <div class="text-xs text-muted mt-1">بر اساس آخرین جلسه مشاوره برگزار شده</div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center">
                                <div class="inline-flex rounded-full border border-border bg-secondary/70 backdrop-blur p-1 overflow-x-auto">
                                    <button wire:click="$set('dayFilter','all')"
                                            class="px-4 h-9 rounded-full text-xs font-semibold transition whitespace-nowrap
                                            {{ $dayFilter==='all' ? 'bg-primary text-white shadow' : 'text-muted hover:text-foreground' }}">
                                        همه
                                    </button>
                                    <button wire:click="$set('dayFilter','today')"
                                            class="px-4 h-9 rounded-full text-xs font-semibold transition whitespace-nowrap
                                            {{ $dayFilter==='today' ? 'bg-primary text-white shadow' : 'text-muted hover:text-foreground' }}">
                                        امروز
                                    </button>
                                    <button wire:click="$set('dayFilter','upcoming')"
                                            class="px-4 h-9 rounded-full text-xs font-semibold transition whitespace-nowrap
                                            {{ $dayFilter==='upcoming' ? 'bg-primary text-white shadow' : 'text-muted hover:text-foreground' }}">
                                        از امروز به بعد
                                    </button>
                                </div>

                                <button wire:click="toggleProgram"
                                        class="px-5 h-10 rounded-full bg-primary hover:bg-primary/90 text-white text-xs font-semibold transition w-full sm:w-auto">
                                    {{ $showProgram ? 'مخفی کردن برنامه' : 'نمایش برنامه' }}
                                </button>
                            </div>
                        </div>
                    </section>

                    {{-- لیست برنامه --}}
                    @if($showProgram)

                        @php $programDays = $this->getProgramDays();@endphp

                        <div class="mt-5 space-y-7">

                            @foreach($programDays as $day)

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
                                            <span class="w-2 h-2 rounded-full {{ $day['is_rest_day'] ? 'bg-emerald-500' : 'bg-primary' }}"></span>
                                            <span class="font-black text-sm {{ $day['is_rest_day'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-foreground' }}">
                                                {{ $day['name'] }}
                                            </span>
                                            <span class="text-xs text-muted">{{ $day['jalali_date'] }}</span>

                                            @if($day['is_rest_day'])
                                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold">
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
                                        <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-secondary p-8 text-center">
                                            <h4 class="font-bold text-emerald-700 dark:text-emerald-400 mb-1">روز استراحت</h4>
                                            <p class="text-sm text-emerald-600/80 dark:text-emerald-400/70">
                                                امروز نیازی به مطالعه نیست. استراحت کن و انرژی بگیر!
                                            </p>
                                        </div>

                                    @elseif($day['parts_count'] > 0)

                                        <div class="overflow-x-auto rounded-3xl border border-border bg-secondary shadow-sm">
                                            <table class="w-full min-w-[720px] text-xs sm:text-[13px]">
                                                <thead class="sticky top-0 z-10">
                                                <tr class="bg-secondary/90 dark:bg-secondary/70 backdrop-blur text-foreground/80">
                                                    <th class="px-3 py-3 text-right">درس</th>
                                                    <th class="px-3 py-3 text-center">مدت</th>
                                                    <th class="px-3 py-3 text-center">نوع</th>
                                                    <th class="px-3 py-3 text-left">عملیات</th>
                                                </tr>
                                                </thead>

                                                <tbody class="divide-y divide-border">
                                                @foreach($day['parts']->sortBy('part_order') as $part)
                                                    <tr class="hover:bg-secondary/60 transition">
                                                        <td class="px-3 py-3 text-foreground">
                                                            <div class="font-semibold">{{ $part->lesson_name }}</div>

                                                            @if($part->description)
                                                                <div class="text-[11px] text-muted mt-1">
                                                                    {{ $part->description }}
                                                                </div>
                                                            @endif
                                                        </td>

                                                        <td class="px-3 py-3 text-center text-foreground" dir="ltr">
                                                            {{ floor($part->duration_minutes / 60) }}:{{ str_pad($part->duration_minutes % 60, 2, '0', STR_PAD_LEFT) }}
                                                        </td>

                                                        <td class="px-3 py-3 text-center">
                                                            <span class="px-2 py-1 rounded-full text-[10px]
                                                                {{ $part->part_type === 'test' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : '' }}
                                                                {{ $part->part_type === 'descriptive' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' : '' }}
                                                                {{ $part->part_type === 'video' ? 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400' : '' }}">
                                                                {{ $part->part_type_label }}
                                                            </span>

                                                            <span class="ms-1 px-2 py-1 rounded-full text-[10px] bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200">
                                                                {{ $part->lesson_type_label }}
                                                            </span>
                                                        </td>

                                                        <td class="px-3 py-3 text-left">
                                                            @if($this->isPartCompleted($part->id))
                                                                <span class="text-emerald-600 dark:text-emerald-400 font-bold text-[11px]">✓ تکمیل شده</span>
                                                            @elseif($currentPartId == $part->id)
                                                                <span class="text-blue-600 dark:text-blue-400 font-bold text-[11px]">در حال مطالعه...</span>
                                                            @else
                                                                <button wire:click="startPart({{ $part->id }})"
                                                                        wire:loading.attr="disabled"
                                                                        wire:target="startPart({{ $part->id }})"
                                                                        class="px-4 h-9 rounded-full bg-primary hover:bg-primary/90 text-white text-[11px] font-semibold transition
                                                                               disabled:opacity-60 disabled:cursor-not-allowed inline-flex items-center gap-2"
                                                                    {{ $currentPartId || $makeupTimerRunning ? 'disabled' : '' }}>
                                                                    <span wire:loading.remove wire:target="startPart({{ $part->id }})">شروع</span>
                                                                    <span wire:loading wire:target="startPart({{ $part->id }})"
                                                                          class="inline-block w-3.5 h-3.5 rounded-full border-2 border-white/35 border-t-white animate-spin"></span>
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        {{-- جلسات جبرانی امروز --}}
                                        @if($day['date'] === now()->toDateString() && $this->todayMakeupSessions->isNotEmpty())
                                            <div class="mt-4 rounded-2xl p-4 bg-secondary border border-border">
                                                <div class="flex items-center gap-2 mb-3">
                                                    <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                                                    <h4 class="font-bold text-foreground text-sm">جلسات خارج از چارچوب برنامه امروز</h4>
                                                    <span class="px-2 py-0.5 rounded-full bg-violet-100 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 text-[10px] font-bold">
                                                        {{ $this->todayMakeupSessions->count() }}
                                                    </span>
                                                </div>

                                                <div class="space-y-2">
                                                    @foreach($this->todayMakeupSessions as $makeup)
                                                        <div class="rounded-xl border border-border bg-secondary/60 p-3 flex items-center gap-3">
                                                            <div class="flex-1">
                                                                <div class="font-semibold text-foreground text-sm">
                                                                    {{ $makeup->ccTopic?->name ?? '-' }}
                                                                </div>

                                                                <div class="flex items-center gap-2 text-[11px] mt-1">
                                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold
                                                                        {{ $makeup->part_type === 'test' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : '' }}
                                                                        {{ $makeup->part_type === 'descriptive' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400' : '' }}
                                                                        {{ $makeup->part_type === 'video' ? 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400' : '' }}">
                                                                        {{ $makeup->part_type_label }}
                                                                    </span>

                                                                    <span class="text-muted">
                                                                        {{ floor($makeup->duration_seconds / 3600) }}:{{ str_pad(floor(($makeup->duration_seconds % 3600) / 60), 2, '0', STR_PAD_LEFT) }}
                                                                    </span>

                                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold
                                                                        {{ $makeup->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' : '' }}
                                                                        {{ $makeup->status === 'approved' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : '' }}
                                                                        {{ $makeup->status === 'rejected' ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : '' }}">
                                                                        {{ $makeup->status_label }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                    @else
                                        <div class="rounded-2xl border border-border bg-secondary p-6 text-center">
                                            <p class="text-sm text-muted">برنامه‌ای برای این روز تنظیم نشده است.</p>
                                        </div>
                                    @endif

                                    <div class="border-t border-dashed border-slate-300/35 dark:border-slate-600/35"></div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                @else
                    <div class="bg-secondary border border-yellow-200 dark:border-yellow-800 rounded-xl p-6 text-center">
                        <h3 class="text-lg font-bold text-yellow-800 dark:text-yellow-300 mb-2">برنامه‌ای یافت نشد</h3>
                        <p class="text-sm text-yellow-700 dark:text-yellow-400">
                            هنوز جلسه مشاوره‌ای برگزار نشده یا برنامه‌ای برای شما تنظیم نشده است.
                            <br>لطفاً با مشاور خود تماس بگیرید.
                        </p>
                    </div>
                @endif


                {{-- مودال دسترسی --}}
                <div x-cloak x-show="permissionModal"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
                     x-transition>
                    <div class="w-full max-w-md mx-4 bg-secondary border border-border rounded-2xl shadow-2xl"
                         @click.away="permissionModal = false">

                        <div class="flex items-center justify-between px-6 py-4 border-b border-border">
                            <h3 class="text-base font-bold text-foreground">درخواست دسترسی</h3>
                            <button type="button" @click="permissionModal = false" class="text-muted hover:text-foreground transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="px-6 py-5 space-y-4">
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                                <h4 class="font-semibold text-sm text-blue-800 dark:text-blue-300 mb-2">چرا این دسترسی‌ها نیاز است؟</h4>
                                <ul class="text-xs text-blue-700 dark:text-blue-400 space-y-2 list-disc list-inside">
                                    <li>دسترسی به صدا برای پخش الارم هنگام پایان تایمر</li>
                                    <li>دسترسی به نوتیفیکیشن برای یادآوری‌های مطالعه</li>
                                    <li>این دسترسی‌ها فقط برای عملکرد بهتر تایمر استفاده می‌شوند</li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-border bg-secondary rounded-b-2xl">
                            <button type="button"
                                    class="px-6 h-11 rounded-full bg-primary text-white font-semibold hover:bg-primary/90 transition"
                                    wire:click="permissionUnderstood">
                                متوجه شدم
                            </button>
                        </div>
                    </div>
                </div>


                {{-- مودال پایان پارت عادی --}}
                <div x-cloak x-show="finishModal"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
                     x-transition>
                    <div class="w-full max-w-md mx-4 bg-secondary border-2 border-green-500 rounded-3xl shadow-2xl"
                         @click.away="finishModal = false">

                        <div class="px-6 py-8 text-center space-y-4">
                            <div class="flex justify-center">
                                <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center animate-bounce">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="3" stroke="white" class="w-10 h-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <h3 class="text-2xl font-black text-green-600 dark:text-green-400">آفرین!</h3>
                                <p class="text-lg font-bold text-green-700 dark:text-green-300">تایم مطالعه به پایان رسید</p>
                                <p class="text-sm text-green-600 dark:text-green-400">آیا می‌خواهید این پارت را ثبت کنید؟</p>
                            </div>

                            <div class="flex items-center justify-center gap-3 pt-4">
                                <button type="button" @click="finishModal = false" wire:click="closeFinishModal"
                                        class="px-6 h-11 rounded-full border-2 border-gray-300 dark:border-gray-600
                                               text-gray-700 dark:text-gray-300 font-semibold
                                               hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                    بستن
                                </button>

                                <button type="button" wire:click="savePart"
                                        class="px-8 h-11 rounded-full bg-green-500 hover:bg-green-600 text-white font-semibold transition
                                               transform hover:scale-105">
                                    ثبت پارت
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- مودال پایان جبرانی --}}
                <div x-cloak x-show="makeupFinishModal"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
                     x-transition>
                    <div class="w-full max-w-md mx-4 bg-secondary border-2 border-violet-500 rounded-3xl shadow-2xl"
                         @click.away="makeupFinishModal = false">

                        <div class="px-6 py-8 text-center space-y-4">
                            <div class="flex justify-center">
                                <div class="w-16 h-16 rounded-full bg-violet-500 flex items-center justify-center animate-bounce">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="3" stroke="white" class="w-10 h-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <h3 class="text-2xl font-black text-violet-600 dark:text-violet-400">عالی!</h3>
                                <p class="text-lg font-bold text-violet-700 dark:text-violet-300">تایمر مطالعه اضافه بر سازمان به پایان رسید</p>
                                <p class="text-sm text-violet-600 dark:text-violet-400">آیا می‌خواهید این جلسه را ثبت کنید؟</p>
                            </div>

                            <div class="flex items-center justify-center gap-3 pt-4">
                                <button type="button" @click="makeupFinishModal = false" wire:click="closeMakeupFinishModal"
                                        class="px-6 h-11 rounded-full border-2 border-gray-300 dark:border-gray-600
                                               text-gray-700 dark:text-gray-300 font-semibold
                                               hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                    بستن
                                </button>

                                <button type="button" wire:click="saveMakeupSession"
                                        class="px-8 h-11 rounded-full bg-violet-500 hover:bg-violet-600 text-white font-semibold transition
                                               transform hover:scale-105">
                                    ثبت جلسه
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- مودال بازخورد --}}
                <div x-cloak x-show="feedbackModal"
                     class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
                     x-transition>
                    <div class="w-full max-w-md mx-4 bg-secondary border border-border rounded-2xl shadow-2xl">

                        <div class="flex items-center justify-between px-6 py-4 border-b border-border">
                            <h3 class="text-base font-bold text-foreground">بازخورد جلسه مطالعه</h3>
                        </div>

                        <div class="px-6 py-5 space-y-5">

                            @if($pendingFeedbackPartName)
                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full
                                            {{ $pendingFeedbackType === 'part' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400' : 'bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400' }}">
                                            {{ $pendingFeedbackType === 'part' ? 'پارت برنامه' : 'اضافه بر سازمان' }}
                                        </span>
                                    </div>
                                    <div class="font-bold text-blue-800 dark:text-blue-300 text-sm leading-6" dir="rtl">
                                        {{ $pendingFeedbackPartName }}
                                    </div>
                                </div>
                            @endif

                            <div class="text-center">
                                <p class="text-sm text-muted mb-4">لطفاً کیفیت جلسه مطالعه خود را امتیاز دهید</p>

                                <div class="flex items-center justify-center gap-1.5 mb-2" dir="ltr">
                                    @for($i = 1; $i <= 10; $i++)
                                        <button type="button"
                                                wire:click="setFeedbackRating({{ $i }})"
                                                class="focus:outline-none transition-transform duration-150 hover:scale-125
                                                       {{ $feedbackRating >= $i ? 'text-amber-500' : 'text-gray-300 dark:text-gray-600' }}">
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
                                    <div class="text-xs font-bold mt-2
                                        {{ $feedbackRating >= 9 ? 'text-emerald-600 dark:text-emerald-400' : '' }}
                                        {{ $feedbackRating >= 7 && $feedbackRating < 9 ? 'text-blue-600 dark:text-blue-400' : '' }}
                                        {{ $feedbackRating >= 5 && $feedbackRating < 7 ? 'text-yellow-600 dark:text-yellow-400' : '' }}
                                        {{ $feedbackRating >= 3 && $feedbackRating < 5 ? 'text-orange-600 dark:text-orange-400' : '' }}
                                        {{ $feedbackRating < 3 ? 'text-red-600 dark:text-red-400' : '' }}">
                                        @if($feedbackRating >= 9)
                                            عالی
                                        @elseif($feedbackRating >= 7)
                                            خوب
                                        @elseif($feedbackRating >= 5)
                                            متوسط
                                        @elseif($feedbackRating >= 3)
                                            ضعیف
                                        @else
                                            خیلی ضعیف
                                        @endif
                                        ({{ $feedbackRating }}/10)
                                    </div>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-foreground mb-1.5">توضیحات (اختیاری)</label>
                                <textarea wire:model="feedbackComment"
                                          rows="3"
                                          class="w-full rounded-xl border border-border bg-secondary px-4 py-3 text-sm text-foreground
                                                 focus:outline-none focus:ring-2 focus:ring-primary/30 resize-none"
                                          placeholder="نظر یا پیشنهادی دارید؟"></textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-border bg-secondary rounded-b-2xl">
                            <button type="button" wire:click="submitFeedback" wire:loading.attr="disabled"
                                    class="px-6 h-11 rounded-full bg-primary text-white font-semibold hover:bg-primary/90 transition
                                           disabled:opacity-60 inline-flex items-center gap-2"
                                {{ $feedbackRating < 1 ? 'disabled' : '' }}>
                                <span wire:loading.remove wire:target="submitFeedback">ثبت بازخورد</span>
                                <span wire:loading wire:target="submitFeedback"
                                      class="inline-block w-3.5 h-3.5 rounded-full border-2 border-white/35 border-t-white animate-spin"></span>
                            </button>
                        </div>
                    </div>
                </div>


                {{-- مودال مطالعه جبرانی --}}
                <div x-cloak x-show="makeupModal"
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
                     x-transition>
                    <div class="w-full max-w-lg mx-4 bg-secondary border border-border rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto"
                         @click.away="makeupModal = false">

                        <div class="flex items-center justify-between px-6 py-4 border-b border-border sticky top-0 bg-secondary z-10 rounded-t-2xl">
                            <h3 class="text-base font-bold text-foreground">ثبت ساعت مطالعه خارج از چارچوب برنامه</h3>
                            <button type="button" wire:click="closeMakeupModal" class="text-muted hover:text-foreground transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="px-6 py-5 space-y-5">

                            {{-- جستجوی سریع --}}
                            <div>
                                <label class="block text-xs font-semibold text-foreground mb-1.5">جستجوی سریع مبحث</label>

                                <div class="relative">
                                    <input type="text" wire:model.live.debounce.300ms="makeupSearch"
                                           class="w-full rounded-xl border border-border bg-secondary px-4 py-3 text-sm text-foreground
                                                  focus:outline-none focus:ring-2 focus:ring-primary/30 pe-10"
                                           placeholder="نام مبحث را جستجو کنید...">
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-muted">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                                        </svg>
                                    </div>

                                    <span wire:loading wire:target="makeupSearch"
                                          class="absolute right-3 top-1/2 -translate-y-1/2">
                                        <span class="inline-block w-3.5 h-3.5 rounded-full border-2 border-primary/30 border-t-primary animate-spin"></span>
                                    </span>
                                </div>

                                {{-- نتایج جستجو --}}
                                <div wire:loading wire:target="makeupSearch" class="mt-2 text-center py-4">
                                    <span class="inline-block w-3.5 h-3.5 rounded-full border-2 border-primary/30 border-t-primary animate-spin"></span>
                                    <span class="text-xs text-muted mr-2">در حال جستجو...</span>
                                </div>

                                <div wire:loading.remove wire:target="makeupSearch">
                                    @if(mb_strlen($makeupSearch) >= 2 && $this->searchResults->isNotEmpty())
                                        <div class="mt-2 rounded-xl border border-border bg-secondary shadow-lg max-h-60 overflow-y-auto">
                                            @foreach($this->searchResults as $result)
                                                <button type="button"
                                                        wire:click="selectSearchTopic({{ $result->id }})"
                                                        class="w-full text-right px-4 py-3 text-sm hover:bg-secondary/60 transition
                                                               border-b border-border last:border-b-0">
                                                    <div class="font-semibold text-foreground">{{ $result->name }}</div>
                                                    <div class="text-[11px] text-muted leading-5">
                                                        {{ $result->chapter?->subject?->grade?->name ?? '' }}
                                                        &laquo; {{ $result->chapter?->subject?->name ?? '' }}
                                                        &laquo; {{ $result->chapter?->name ?? '' }}
                                                        &laquo; <span class="text-primary font-medium">{{ $result->name }}</span>
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    @elseif(mb_strlen($makeupSearch) >= 2 && $this->searchResults->isEmpty())
                                        <div class="mt-2 text-center py-3 text-xs text-muted">
                                            نتیجه‌ای یافت نشد.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="border-t border-border pt-4">
                                <p class="text-[11px] text-muted mb-3">یا از فیلترهای زیر استفاده کنید:</p>

                                <div class="space-y-3 mb-3">

                                    @if($this->fields->isNotEmpty())
                                        <div>
                                            <label class="block text-[11px] font-semibold text-foreground mb-1">رشته</label>
                                            <div class="w-full rounded-xl border border-border bg-secondary px-4 py-2.5 text-sm text-foreground">
                                                <div class="flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                         stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-primary">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
                                                    </svg>
                                                    <span class="font-semibold">{{ $this->fields->first()->name ?? 'نامشخص' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div>
                                        <label class="block text-[11px] font-semibold text-foreground mb-1">پایه تحصیلی</label>
                                        <select wire:model.live="makeupGradeId"
                                                class="w-full rounded-xl border border-border bg-secondary px-3 py-2.5 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary/30">
                                            <option value="">انتخاب پایه...</option>
                                            @foreach($this->grades as $grade)
                                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                            @endforeach
                                        </select>
                                        @if($this->grades->isEmpty())
                                            <p class="text-[10px] text-muted mt-1">هیچ پایه‌ای برای رشته شما یافت نشد.</p>
                                        @endif
                                    </div>
                                </div>

                                @if($makeupGradeId)
                                    <div class="mb-3" wire:loading.class="opacity-50" wire:target="makeupGradeId">
                                        <label class="block text-[11px] font-semibold text-foreground mb-1">درس</label>
                                        <select wire:model.live="makeupSubjectId"
                                                class="w-full rounded-xl border border-border bg-secondary px-3 py-2.5 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary/30">
                                            <option value="">انتخاب درس...</option>
                                            @foreach($this->subjects as $subject)
                                                <option value="{{ $subject->id }}">
                                                    {{ $subject->name }} {{ $subject->type === 'general' ? '(عمومی)' : '(تخصصی)' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if($makeupSubjectId)
                                    <div class="mb-3" wire:loading.class="opacity-50" wire:target="makeupSubjectId">
                                        <label class="block text-[11px] font-semibold text-foreground mb-1">فصل</label>
                                        <select wire:model.live="makeupChapterId"
                                                class="w-full rounded-xl border border-border bg-secondary px-3 py-2.5 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary/30">
                                            <option value="">انتخاب فصل...</option>
                                            @foreach($this->chapters as $chapter)
                                                <option value="{{ $chapter->id }}">{{ $chapter->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if($makeupChapterId)
                                    <div class="mb-3" wire:loading.class="opacity-50" wire:target="makeupChapterId">
                                        <label class="block text-[11px] font-semibold text-foreground mb-1">مبحث</label>
                                        <select wire:model.live="makeupTopicId"
                                                class="w-full rounded-xl border border-border bg-secondary px-3 py-2.5 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary/30">
                                            <option value="">انتخاب مبحث...</option>
                                            @foreach($this->topics as $topic)
                                                <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>

                            {{-- انتخاب نوع پارت --}}
                            <div class="border-t border-border pt-4">
                                <label class="block text-xs font-semibold text-foreground mb-2">نوع مطالعه</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <button type="button"
                                            wire:click="$set('makeupPartType', 'test')"
                                            class="px-4 py-3 rounded-xl border-2 text-sm font-semibold transition transform hover:-translate-y-0.5
                                            {{ $makeupPartType === 'test'
                                                ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400'
                                                : 'border-border bg-secondary text-muted hover:border-blue-300' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mx-auto mb-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
                                        </svg>
                                        تستی
                                    </button>

                                    <button type="button"
                                            wire:click="$set('makeupPartType', 'descriptive')"
                                            class="px-4 py-3 rounded-xl border-2 text-sm font-semibold transition transform hover:-translate-y-0.5
                                            {{ $makeupPartType === 'descriptive'
                                                ? 'border-purple-500 bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400'
                                                : 'border-border bg-secondary text-muted hover:border-purple-300' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mx-auto mb-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                                        </svg>
                                        تشریحی
                                    </button>

                                    <button type="button"
                                            wire:click="$set('makeupPartType', 'video')"
                                            class="px-4 py-3 rounded-xl border-2 text-sm font-semibold transition transform hover:-translate-y-0.5
                                            {{ $makeupPartType === 'video'
                                                ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400'
                                                : 'border-border bg-secondary text-muted hover:border-orange-300' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mx-auto mb-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z"/>
                                        </svg>
                                        ویدیویی
                                    </button>
                                </div>
                            </div>

                            <div class="border-t border-border pt-4">
                                <label class="block text-xs font-semibold text-foreground mb-2">مدت زمان مطالعه</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] text-muted mb-1">ساعت</label>
                                        <input type="number" min="0" max="12" wire:model="makeupDurationHours"
                                               class="w-full rounded-xl border border-border bg-secondary px-3 py-2.5 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary/30">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-muted mb-1">دقیقه</label>
                                        <input type="number" min="0" max="59" wire:model="makeupDurationMinutes"
                                               class="w-full rounded-xl border border-border bg-secondary px-3 py-2.5 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary/30">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-foreground mb-1.5">یادداشت (اختیاری)</label>
                                <textarea wire:model="makeupNote" rows="2"
                                          class="w-full rounded-xl border border-border bg-secondary px-4 py-3 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary/30 resize-none"
                                          placeholder="توضیحات..."></textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-border bg-secondary rounded-b-2xl sticky bottom-0">
                            <button type="button" wire:click="closeMakeupModal"
                                    class="px-5 h-10 rounded-full border border-border text-foreground font-semibold text-sm hover:bg-secondary/60 transition">
                                انصراف
                            </button>

                            <button type="button" wire:click="startMakeupTimer" wire:loading.attr="disabled"
                                    class="px-6 h-10 rounded-full bg-violet-600 hover:bg-violet-700 text-white font-semibold text-sm transition
                                           disabled:opacity-60 inline-flex items-center gap-2"
                                {{ !$makeupTopicId ? 'disabled' : '' }}>
                                <span wire:loading.remove wire:target="startMakeupTimer">شروع تایمر</span>
                                <span wire:loading wire:target="startMakeupTimer"
                                      class="inline-block w-3.5 h-3.5 rounded-full border-2 border-white/35 border-t-white animate-spin"></span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    @script
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

            @this.call('onPermissionsGranted');
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
            @this.call('onPermissionsGranted');
            }
        });
    </script>
    @endscript

</div>
