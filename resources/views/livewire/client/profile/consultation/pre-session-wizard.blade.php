<div class="min-h-screen bg-background py-6 sm:py-10" dir="rtl">
    <div class="container mx-auto px-3 sm:px-4 max-w-4xl">

        {{-- HEADER --}}
        <div class="overflow-hidden rounded-2xl border bg-secondary border-border bg-card shadow-[0_4px_20px_rgba(0,0,0,0.06)] dark:shadow-[0_4px_20px_rgba(0,0,0,0.2)]">
            <div class="bg-gradient-to-br from-blue-700 via-blue-600 to-blue-400 px-5 py-6 sm:px-7 sm:py-7">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">
                            پیش‌جلسه مشاوره  ({{ $session->title }})
                        </h1>
                        <p class="text-sm text-blue-100">

                        </p>
                        <p class="mt-3 text-xs sm:text-sm text-blue-100/90">
                            تاریخ جلسه:
                            <span class="font-semibold">
                                {{ jalali($session->activation_date)->format('%d %B %Y') }}
                            </span>
                            @if($session->session_time)
                                <span class="mx-1 text-blue-200/80">•</span>
                                <span>ساعت {{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}</span>
                            @endif
                        </p>
                    </div>

                    <div class="flex flex-col items-stretch gap-2 sm:items-end">
                        <a wire:navigate href="{{ route('client.profile.consultation.sessions') }}"
                           class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-red-500 px-4 py-2 text-xs sm:text-sm font-medium
                            text-muted shadow-sm transition hover:bg-muted/80 hover:text-foreground focus:outline-none focus:ring-2
                            focus:ring-border focus:ring-offset-2 focus:ring-offset-background">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            بازگشت
                        </a>

                        <span class="inline-flex items-center gap-2 rounded-full bg-black/10 px-3 py-1 text-[11px] text-blue-100/90 ring-1 ring-blue-200/40">
                            <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                            <span>
                                مرحله فعلی:
                                <strong class="mr-1">{{ $stepTitles[$currentStep] ?? '' }}</strong>
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- WIZARD STEPS --}}
            <div class="border-b border-border bg-muted/50 px-3 py-3 sm:px-5 sm:py-4">
                <div class="flex items-center justify-between gap-1 sm:gap-2">
                    @foreach($stepTitles as $step => $title)
                        <div class="flex items-center {{ $step < $totalSteps ? 'flex-1' : '' }}">
                            {{-- Circle --}}
                            <button
                                wire:click="goToStep({{ $step }})"
                                class="relative flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-full text-xs font-bold transition-all
                                @if($currentStep === $step)
                                    bg-blue-600 text-white shadow-sm ring-2 ring-blue-300/80
                                @elseif($currentStep > $step)
                                    bg-emerald-500 text-white shadow-sm ring-1 ring-emerald-300/70
                                @else
                                    bg-muted text-muted ring-1 ring-border
                                @endif"
                            >
                                @if($currentStep > $step)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                @else
                                    {{ $step }}
                                @endif
                            </button>

                            {{-- Title --}}
                            <span class="mr-2 hidden text-[11px] sm:inline-block sm:text-xs
                                @if($currentStep === $step) font-medium text-blue-600
                                @elseif($currentStep > $step) text-emerald-600
                                @else text-muted
                                @endif">
                                {{ $title }}
                            </span>

                            {{-- Connector --}}
                            @if($step < $totalSteps)
                                <div class="mr-2 flex-1 min-w-[20px]">
                                    <div class="h-1 rounded-full bg-border overflow-hidden">
                                        <div class="h-1 rounded-full transition-all duration-300
                                            @if($currentStep > $step) bg-emerald-500 w-full
                                            @elseif($currentStep === $step) bg-blue-500 w-1/2
                                            @else w-0
                                            @endif">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- عنوان مرحله روی موبایل --}}
                <div class="mt-3 text-xs text-muted sm:hidden">
                    <span class="font-medium text-foreground">{{ $stepTitles[$currentStep] ?? '' }}</span>
                </div>
            </div>

            {{-- STEP CONTENT --}}
            <div class="bg-card p-4 sm:p-6 border-t border-border rounded-b-2xl">

                {{-- هشدار عدم امکان ویرایش --}}
                @if(!$canEdit)
                    <div class="mb-6 flex items-start gap-2 rounded-xl border border-amber-200/80 bg-amber-500/10 px-3 py-3 text-xs text-amber-700 dark:border-amber-500/40 dark:text-amber-300">
                        <span class="mt-0.5">
<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M12 9v4m0 4h.01M10.29 3.86l-7.4 12.82A1 1 0 003.75 18h16.5a1 1 0 00.86-1.32l-7.4-12.82a1 1 0 00-1.72 0z"/>
</svg>
                        </span>
                        <p>
                            زمان ویرایش پیش‌جلسه به پایان رسیده است.
                            <span class="font-medium">فقط می‌توانید اطلاعات ثبت شده را مشاهده کنید.</span>
                        </p>
                    </div>
                @endif

                {{-- ===== مرحله ۱: امتحانات ===== --}}
                @if($currentStep === 1)
                    <h3 class="mb-2 text-base sm:text-lg font-bold text-foreground">امتحانات</h3>
                    <p class="mb-5 text-xs sm:text-sm text-muted">
                        تمام امتحاناتی که در هفته پیش رو را دارید، ثبت کنید.
                    </p>

                    @if($canEdit)
                        <div class="mb-6 rounded-xl bg-muted/50 dark:bg-muted/30 px-3 py-4 sm:px-4 sm:py-5">
                            <div class="grid grid-cols-1 gap-3 sm:gap-4 md:grid-cols-2 mb-4">

                                {{-- درس --}}
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-foreground">درس</label>
                                    @if(count($availableSubjects) > 0)
                                        <select wire:model.live="examForm.cc_subject_id" class="w-full rounded-lg border border-border bg-background px-3 py-2.5 text-sm text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:bg-background dark:text-foreground">
                                            <option value="">انتخاب درس...</option>
                                            @foreach($availableSubjects as $subject)
                                                <option value="{{ $subject['id'] }}">{{ $subject['name'] }} ({{ $subject['type'] === 'general' ? 'عمومی' : 'تخصصی' }})</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" wire:model="examForm.subject" class="w-full rounded-lg border border-border bg-background px-3 py-2.5 text-sm text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="مثال: ریاضی">
                                    @endif
                                    @error('examForm.subject')<span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                                </div>

                                {{-- فصل --}}
                                <div>
                                    <label class="mb-1 block text-xs font-medium text-foreground">فصل</label>
                                    @if(count($availableChapters) > 0)
                                        <select wire:model="examForm.cc_chapter_id" class="w-full rounded-lg border border-border bg-background px-3 py-2.5 text-sm text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                            <option value="">انتخاب فصل...</option>
                                            @foreach($availableChapters as $chapter)
                                                <option value="{{ $chapter['id'] }}">{{ $chapter['name'] }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select disabled class="w-full rounded-lg border border-border bg-muted px-3 py-2.5 text-sm text-muted shadow-sm cursor-not-allowed">
                                            <option value="">ابتدا درس را انتخاب کنید</option>
                                        </select>
                                    @endif
                                </div>

                                {{-- تاریخ امتحان --}}
                                <div class="md:col-span-2">
                                    <label class="mb-1 block text-xs font-medium text-foreground">
                                        تاریخ امتحان
                                        @if($examForm['exam_date'])
                                            <span class="text-blue-600 font-bold mr-1">{{ $examForm['exam_date'] }}</span>
                                        @endif
                                    </label>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($availableDates as $dateItem)
                                            <button type="button"
                                                    wire:click="$set('examForm.exam_date', '{{ $dateItem['value'] }}')"
                                                    class="flex flex-col items-center justify-center min-w-[70px] px-2.5 py-2 rounded-xl border text-xs transition-all duration-150
                                                        {{ $examForm['exam_date'] === $dateItem['value']
                                                            ? 'border-blue-500 bg-blue-600 text-white shadow-md shadow-blue-500/30'
                                                            : 'border-border bg-background text-muted hover:border-blue-400 hover:bg-blue-500/10 hover:text-blue-600 dark:hover:bg-blue-500/15' }}">
                                                <span class="font-semibold text-[11px] mb-0.5">{{ $dateItem['day_name'] }}</span>
                                                <span class="text-[11px] opacity-80">{{ $dateItem['day'] }} {{ $dateItem['month_name'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                    @error('examForm.exam_date')<span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                                </div>

                                {{-- تعداد پارت --}}
                                <div x-data="{ count: $wire.entangle('examForm.part_count') }" x-init="if(!count || count < 1) count = 1">
                                    <label class="mb-1 block text-xs font-medium text-foreground">تعداد پارت (پیشنهادی جهت مطالعه امتحان فوق)</label>
                                    <div class="flex items-center gap-1">
                                        <button type="button" @click="count++" class="flex items-center justify-center w-10 h-10 rounded-lg border border-border bg-background text-foreground shadow-sm transition hover:bg-green-500/10 hover:border-green-500 hover:text-green-600 dark:hover:bg-green-500/15">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                        <input type="tel" min="1" x-model.number="count" @input="if(count < 1) count = 1" class="flex-1 rounded-lg border border-border bg-background px-3 py-2.5 text-sm text-center text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                        <button type="button" @click="if(count > 1) count--" class="flex items-center justify-center w-10 h-10 rounded-lg border border-border bg-background text-foreground shadow-sm transition hover:bg-red-500/10 hover:border-red-500 hover:text-red-600 dark:hover:bg-red-500/15">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- زمان هر پارت --}}
                                <div x-data="{
                                    totalMinutes: $wire.entangle('examForm.time_per_part'),
                                    hours: 0, minutes: 0,
                                    init() { let val = parseInt(this.totalMinutes)||0; this.hours=Math.floor(val/60); this.minutes=val%60; },
                                    update() { let h=Math.min(Math.max(parseInt(this.hours)||0,0),24); let m=Math.min(Math.max(parseInt(this.minutes)||0,0),59); this.hours=h; this.minutes=m; this.totalMinutes=(h*60)+m; }
                                }" x-init="init()">
                                    <label class="mb-1 block text-xs font-medium text-foreground">زمان هر پارت</label>
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 relative">
                                            <input type="tel" min="0" max="59" x-model.number="minutes" @input="update()" class="w-full rounded-lg border border-border bg-background px-3 py-2.5 pl-10 text-sm text-center text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="0">
                                            <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[10px] text-muted pointer-events-none">دقیقه</span>
                                        </div>
                                        <span class="text-lg font-bold text-muted">:</span>
                                        <div class="flex-1 relative">
                                            <input type="tel" min="0" max="24" x-model.number="hours" @input="update()" class="w-full rounded-lg border border-border bg-background px-3 py-2.5 pl-10 text-sm text-center text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="0">
                                            <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[10px] text-muted pointer-events-none">ساعت</span>
                                        </div>
                                    </div>
                                    <div class="mt-1 text-[10px] text-muted" x-show="totalMinutes > 0">
                                        مجموع: <span x-text="totalMinutes"></span> دقیقه
                                    </div>
                                </div>
                            </div>

                            <button wire:click="addExam" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-background">
                                افزودن امتحان
                            </button>
                        </div>
                    @endif

                    @if(count($exams) > 0)
                        <div class="space-y-2">
                            @foreach($exams as $exam)
                                <div class="flex items-center justify-between rounded-xl bg-blue-500/10 dark:bg-blue-500/15 px-3 py-2.5 text-xs sm:text-sm text-foreground">
                                    <div class="space-x-1 space-x-reverse">
                                        <span class="text-muted">امتحان ({{ jalali($exam['exam_date'])->format('Y/m/d') }}) :</span>
                                        <span class="font-medium">{{ $exam['subject'] }}
                                            <span class="text-muted">({{ $exam['part_count'] }} پارت - {{ $exam['time_per_part'] }} دقیقه)</span>
                                        </span>
                                    </div>
                                    @if($canEdit)
                                        <button wire:click="deleteExam({{ $exam['id'] }})" class="text-red-500 transition hover:text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M6 6l1 16h10l1-16"/><path d="M10 11v6"/><path d="M14 11v6"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="py-4 text-center text-xs text-muted">هیچ امتحانی ثبت نشده است.</p>
                    @endif
                @endif

                {{-- ===== مرحله ۲: پرسش و پاسخ ===== --}}
                @if($currentStep === 2)
                    <h3 class="mb-2 text-base sm:text-lg font-bold text-foreground">پرسش و پاسخ کلاسی</h3>
                    <p class="mb-5 text-xs sm:text-sm text-muted">پرسش و پاسخ‌های کلاسی هفته پیش رو را ثبت کنید.</p>

                    @if($canEdit)
                        <div class="mb-6 rounded-xl bg-muted/50 dark:bg-muted/30 px-3 py-4 sm:px-4 sm:py-5">
                            <div class="grid grid-cols-1 gap-3 sm:gap-4 md:grid-cols-2 mb-4">

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-foreground">درس</label>
                                    @if(count($availableSubjects) > 0)
                                        <select wire:model.live="qaForm.cc_subject_id" class="w-full rounded-lg border border-border bg-background px-3 py-2.5 text-sm text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                            <option value="">انتخاب درس...</option>
                                            @foreach($availableSubjects as $subject)
                                                <option value="{{ $subject['id'] }}">{{ $subject['name'] }} ({{ $subject['type'] === 'general' ? 'عمومی' : 'تخصصی' }})</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" wire:model="qaForm.subject" class="w-full rounded-lg border border-border bg-background px-3 py-2.5 text-sm text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="مثال: فیزیک">
                                    @endif
                                    @error('qaForm.subject')<span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-foreground">فصل</label>
                                    @if(count($availableChapters) > 0)
                                        <select wire:model="qaForm.cc_chapter_id" class="w-full rounded-lg border border-border bg-background px-3 py-2.5 text-sm text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                            <option value="">انتخاب فصل...</option>
                                            @foreach($availableChapters as $chapter)
                                                <option value="{{ $chapter['id'] }}">{{ $chapter['name'] }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select disabled class="w-full rounded-lg border border-border bg-muted px-3 py-2.5 text-sm text-muted shadow-sm cursor-not-allowed">
                                            <option value="">ابتدا درس را انتخاب کنید</option>
                                        </select>
                                    @endif
                                </div>

                                <div class="md:col-span-2">
                                    <label class="mb-1 block text-xs font-medium text-foreground">
                                        تاریخ
                                        @if($qaForm['qa_date'])
                                            <span class="text-blue-600 font-bold mr-1">{{ $qaForm['qa_date'] }}</span>
                                        @endif
                                    </label>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($availableDates as $dateItem)
                                            <button type="button"
                                                    wire:click="$set('qaForm.qa_date', '{{ $dateItem['value'] }}')"
                                                    class="flex flex-col items-center justify-center min-w-[70px] px-2.5 py-2 rounded-xl border text-xs transition-all duration-150
                                                        {{ $qaForm['qa_date'] === $dateItem['value']
                                                            ? 'border-blue-500 bg-blue-600 text-white shadow-md shadow-blue-500/30'
                                                            : 'border-border bg-background text-muted hover:border-blue-400 hover:bg-blue-500/10 hover:text-blue-600 dark:hover:bg-blue-500/15' }}">
                                                <span class="font-semibold text-[11px] mb-0.5">{{ $dateItem['day_name'] }}</span>
                                                <span class="text-[11px] opacity-80">{{ $dateItem['day'] }} {{ $dateItem['month_name'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                    @error('qaForm.qa_date')<span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                                </div>

                                <div x-data="{ count: $wire.entangle('qaForm.part_count') }" x-init="if(!count || count < 1) count = 1">
                                    <label class="mb-1 block text-xs font-medium text-foreground">تعداد پارت (پیشنهادی جهت آمادگی در فعالیت فوق)</label>
                                    <div class="flex items-center gap-1">
                                        <button type="button" @click="count++" class="flex items-center justify-center w-10 h-10 rounded-lg border border-border bg-background text-foreground shadow-sm transition hover:bg-green-500/10 hover:border-green-500 hover:text-green-600 dark:hover:bg-green-500/15">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                        <input type="tel" min="1" x-model.number="count" @input="if(count < 1) count = 1" class="flex-1 rounded-lg border border-border bg-background px-3 py-2.5 text-sm text-center text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                        <button type="button" @click="if(count > 1) count--" class="flex items-center justify-center w-10 h-10 rounded-lg border border-border bg-background text-foreground shadow-sm transition hover:bg-red-500/10 hover:border-red-500 hover:text-red-600 dark:hover:bg-red-500/15">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <div x-data="{
                                    totalMinutes: $wire.entangle('qaForm.time_per_part'),
                                    hours: 0, minutes: 0,
                                    init() { let val = parseInt(this.totalMinutes)||0; this.hours=Math.floor(val/60); this.minutes=val%60; },
                                    update() { let h=Math.min(Math.max(parseInt(this.hours)||0,0),24); let m=Math.min(Math.max(parseInt(this.minutes)||0,0),59); this.hours=h; this.minutes=m; this.totalMinutes=(h*60)+m; }
                                }" x-init="init()">
                                    <label class="mb-1 block text-xs font-medium text-foreground">زمان هر پارت</label>
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 relative">
                                            <input type="tel" min="0" max="59" x-model.number="minutes" @input="update()" class="w-full rounded-lg border border-border bg-background px-3 py-2.5 pl-10 text-sm text-center text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="0">
                                            <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[10px] text-muted pointer-events-none">دقیقه</span>
                                        </div>
                                        <span class="text-lg font-bold text-muted">:</span>
                                        <div class="flex-1 relative">
                                            <input type="tel" min="0" max="24" x-model.number="hours" @input="update()" class="w-full rounded-lg border border-border bg-background px-3 py-2.5 pl-10 text-sm text-center text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="0">
                                            <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[10px] text-muted pointer-events-none">ساعت</span>
                                        </div>
                                    </div>
                                    <div class="mt-1 text-[10px] text-muted" x-show="totalMinutes > 0">
                                        مجموع: <span x-text="totalMinutes"></span> دقیقه
                                    </div>
                                </div>
                            </div>

                            <button wire:click="addQa" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-background">
                                افزودن پرسش و پاسخ
                            </button>
                        </div>
                    @endif

                    @if(count($qas) > 0)
                        <div class="space-y-2">
                            @foreach($qas as $qa)
                                <div class="flex items-center justify-between rounded-xl bg-emerald-500/10 dark:bg-emerald-500/15 px-3 py-2.5 text-xs sm:text-sm text-foreground">
                                    <div class="space-x-1 space-x-reverse">
                                        <span class="text-muted">پرسش و پاسخ کلاسی ({{ jalali($qa['qa_date'])->format('Y/m/d') }}) :</span>
                                        <span class="font-medium">{{ $qa['subject'] }}
                                            <span class="text-muted">({{ $qa['part_count'] }} پارت - {{ $qa['time_per_part'] }} دقیقه)</span>
                                        </span>
                                    </div>
                                    @if($canEdit)
                                        <button wire:click="deleteQa({{ $qa['id'] }})" class="text-red-500 transition hover:text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M6 6l1 16h10l1-16"/><path d="M10 11v6"/><path d="M14 11v6"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="py-4 text-center text-xs text-muted">هیچ پرسش و پاسخی ثبت نشده است.</p>
                    @endif
                @endif

                {{-- ===== مرحله ۳: تکالیف ===== --}}
                @if($currentStep === 3)
                    <h3 class="mb-2 text-base sm:text-lg font-bold text-foreground">تکالیف</h3>
                    <p class="mb-5 text-xs sm:text-sm text-muted">تکالیف هفته پیش رو را ثبت کنید.</p>

                    @if($canEdit)
                        <div class="mb-6 rounded-xl bg-muted/50 dark:bg-muted/30 px-3 py-4 sm:px-4 sm:py-5">
                            <div class="grid grid-cols-1 gap-3 sm:gap-4 md:grid-cols-2 mb-4">

                                <div>
                                    <label class="mb-1 block text-xs font-medium text-foreground">درس</label>
                                    @if(count($availableSubjects) > 0)
                                        <select wire:model.live="assignmentForm.cc_subject_id" class="w-full rounded-lg border border-border bg-background px-3 py-2.5 text-sm text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                            <option value="">انتخاب درس...</option>
                                            @foreach($availableSubjects as $subject)
                                                <option value="{{ $subject['id'] }}">{{ $subject['name'] }} ({{ $subject['type'] === 'general' ? 'عمومی' : 'تخصصی' }})</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" wire:model="assignmentForm.subject" class="w-full rounded-lg border border-border bg-background px-3 py-2.5 text-sm text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="مثال: شیمی">
                                    @endif
                                    @error('assignmentForm.subject')<span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="mb-1 block text-xs font-medium text-foreground">
                                        تاریخ تحویل
                                        @if($assignmentForm['due_date'])
                                            <span class="text-blue-600 font-bold mr-1">{{ $assignmentForm['due_date'] }}</span>
                                        @endif
                                    </label>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($availableDates as $dateItem)
                                            <button type="button"
                                                    wire:click="$set('assignmentForm.due_date', '{{ $dateItem['value'] }}')"
                                                    class="flex flex-col items-center justify-center min-w-[70px] px-2.5 py-2 rounded-xl border text-xs transition-all duration-150
                                                        {{ $assignmentForm['due_date'] === $dateItem['value']
                                                            ? 'border-blue-500 bg-blue-600 text-white shadow-md shadow-blue-500/30'
                                                            : 'border-border bg-background text-muted hover:border-blue-400 hover:bg-blue-500/10 hover:text-blue-600 dark:hover:bg-blue-500/15' }}">
                                                <span class="font-semibold text-[11px] mb-0.5">{{ $dateItem['day_name'] }}</span>
                                                <span class="text-[11px] opacity-80">{{ $dateItem['day'] }} {{ $dateItem['month_name'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                    @error('assignmentForm.due_date')<span class="mt-1 block text-xs text-red-500">{{ $message }}</span>@enderror
                                </div>

                                <div x-data="{ count: $wire.entangle('assignmentForm.part_count') }" x-init="if(!count || count < 1) count = 1">
                                    <label class="mb-1 block text-xs font-medium text-foreground">تعداد پارت (پیشنهادی جهت مطالعه انجام تکالیف فوق)</label>
                                    <div class="flex items-center gap-1">
                                        <button type="button" @click="count++" class="flex items-center justify-center w-10 h-10 rounded-lg border border-border bg-background text-foreground shadow-sm transition hover:bg-green-500/10 hover:border-green-500 hover:text-green-600 dark:hover:bg-green-500/15">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                        <input type="tel" min="1" x-model.number="count" @input="if(count < 1) count = 1" class="flex-1 rounded-lg border border-border bg-background px-3 py-2.5 text-sm text-center text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                        <button type="button" @click="if(count > 1) count--" class="flex items-center justify-center w-10 h-10 rounded-lg border border-border bg-background text-foreground shadow-sm transition hover:bg-red-500/10 hover:border-red-500 hover:text-red-600 dark:hover:bg-red-500/15">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <div x-data="{
                                    totalMinutes: $wire.entangle('assignmentForm.time_per_part'),
                                    hours: 0, minutes: 0,
                                    init() { let val = parseInt(this.totalMinutes)||0; this.hours=Math.floor(val/60); this.minutes=val%60; },
                                    update() { let h=Math.min(Math.max(parseInt(this.hours)||0,0),24); let m=Math.min(Math.max(parseInt(this.minutes)||0,0),59); this.hours=h; this.minutes=m; this.totalMinutes=(h*60)+m; }
                                }" x-init="init()">
                                    <label class="mb-1 block text-xs font-medium text-foreground">زمان هر پارت</label>
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 relative">
                                            <input type="tel" min="0" max="59" x-model.number="minutes" @input="update()" class="w-full rounded-lg border border-border bg-background px-3 py-2.5 pl-10 text-sm text-center text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="0">
                                            <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[10px] text-muted pointer-events-none">دقیقه</span>
                                        </div>
                                        <span class="text-lg font-bold text-muted">:</span>
                                        <div class="flex-1 relative">
                                            <input type="tel" min="0" max="24" x-model.number="hours" @input="update()" class="w-full rounded-lg border border-border bg-background px-3 py-2.5 pl-10 text-sm text-center text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="0">
                                            <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[10px] text-muted pointer-events-none">ساعت</span>
                                        </div>
                                    </div>
                                    <div class="mt-1 text-[10px] text-muted" x-show="totalMinutes > 0">
                                        مجموع: <span x-text="totalMinutes"></span> دقیقه
                                    </div>
                                </div>
                            </div>

                            <button wire:click="addAssignment" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-background">
                                افزودن تکلیف
                            </button>
                        </div>
                    @endif

                    @if(count($assignments) > 0)
                        <div class="space-y-2">
                            @foreach($assignments as $assignment)
                                <div class="flex items-center justify-between rounded-xl bg-violet-500/10 dark:bg-violet-500/15 px-3 py-2.5 text-xs sm:text-sm text-foreground">
                                    <div class="space-x-1 space-x-reverse">
                                        <span class="text-muted">تکلیف ({{ jalali($assignment['due_date'])->format('Y/m/d') }}) :</span>
                                        <span class="font-medium">{{ $assignment['subject'] }}
                                            <span class="text-muted">({{ $assignment['part_count'] }} پارت - {{ $assignment['time_per_part'] }} دقیقه)</span>
                                        </span>
                                    </div>
                                    @if($canEdit)
                                        <button wire:click="deleteAssignment({{ $assignment['id'] }})" class="text-red-500 transition hover:text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M6 6l1 16h10l1-16"/><path d="M10 11v6"/><path d="M14 11v6"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="py-4 text-center text-xs text-muted">هیچ تکلیفی ثبت نشده است.</p>
                    @endif
                @endif

                {{-- ===== مرحله ۴: متفرقه ===== --}}
                @if($currentStep === 4)
                    <h3 class="mb-2 text-base sm:text-lg font-bold text-foreground">متفرقه</h3>
                    <p class="mb-5 text-xs sm:text-sm text-muted">
                        هر توضیح یا نکته دیگری که می‌خواهید به مشاور بگویید را اینجا بنویسید.
                    </p>

                    <div class="mb-4">
                        <textarea
                            wire:model="miscDescription"
                            rows="6"
                            class="w-full rounded-xl border border-border bg-background px-3 py-3 text-sm text-foreground shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 {{ !$canEdit ? 'opacity-60 cursor-not-allowed' : '' }}"
                            placeholder="توضیحات خود را اینجا بنویسید..."
                            {{ !$canEdit ? 'disabled' : '' }}
                        ></textarea>
                    </div>

                    @if($canEdit)
                        <button wire:click="saveMiscellaneous" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-background">
                            ذخیره توضیحات
                        </button>
                    @endif
                @endif

                {{-- ===== مرحله ۵: خلاصه ===== --}}
                @if($currentStep === 5)
                    <h3 class="mb-2 text-base sm:text-lg font-bold text-foreground">خلاصه پیش‌جلسه</h3>
                    <p class="mb-5 text-xs sm:text-sm text-muted">
                        تمام اطلاعاتی که ثبت کرده‌اید در بخش‌های زیر نمایش داده شده است.
                    </p>

                    <div class="space-y-4 sm:space-y-6">
                        <div class="rounded-xl border border-border bg-muted/30 dark:bg-muted/20 p-3 sm:p-4">
                            <h4 class="mb-3 text-xs sm:text-sm font-bold text-blue-600">امتحانات ({{ count($exams) }} مورد)</h4>
                            @forelse($exams as $exam)
                                <div class="border-b border-dashed border-border py-1 text-xs sm:text-sm last:border-b-0 text-foreground">
                                    {{ $exam['subject'] }} – {{ $exam['part_count'] }} پارت ({{ $exam['time_per_part'] }} دقیقه) – {{ jalali($exam['exam_date'])->format('Y/m/d') }}
                                </div>
                            @empty
                                <p class="text-xs text-muted">ثبت نشده</p>
                            @endforelse
                        </div>

                        <div class="rounded-xl border border-border bg-muted/30 dark:bg-muted/20 p-3 sm:p-4">
                            <h4 class="mb-3 text-xs sm:text-sm font-bold text-emerald-600">پرسش و پاسخ ({{ count($qas) }} مورد)</h4>
                            @forelse($qas as $qa)
                                <div class="border-b border-dashed border-border py-1 text-xs sm:text-sm last:border-b-0 text-foreground">
                                    {{ $qa['subject'] }} – {{ $qa['part_count'] }} پارت ({{ $qa['time_per_part'] }} دقیقه) – {{ jalali($qa['qa_date'])->format('Y/m/d') }}
                                </div>
                            @empty
                                <p class="text-xs text-muted">ثبت نشده</p>
                            @endforelse
                        </div>

                        <div class="rounded-xl border border-border bg-muted/30 dark:bg-muted/20 p-3 sm:p-4">
                            <h4 class="mb-3 text-xs sm:text-sm font-bold text-violet-600">تکالیف ({{ count($assignments) }} مورد)</h4>
                            @forelse($assignments as $assignment)
                                <div class="border-b border-dashed border-border py-1 text-xs sm:text-sm last:border-b-0 text-foreground">
                                    {{ $assignment['subject'] }} – {{ $assignment['part_count'] }} پارت ({{ $assignment['time_per_part'] }} دقیقه) – {{ jalali($assignment['due_date'])->format('Y/m/d') }}
                                </div>
                            @empty
                                <p class="text-xs text-muted">ثبت نشده</p>
                            @endforelse
                        </div>

                        <div class="rounded-xl border border-border bg-muted/30 dark:bg-muted/20 p-3 sm:p-4">
                            <h4 class="mb-3 text-xs sm:text-sm font-bold text-amber-600">متفرقه</h4>
                            @if($miscDescription)
                                <p class="text-xs sm:text-sm text-foreground">{{ $miscDescription }}</p>
                            @else
                                <p class="text-xs text-muted">ثبت نشده</p>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- دکمه‌های ناوبری --}}
                <div class="mt-8 border-t border-border pt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>

                        @if($currentStep > 1)
                            <button wire:click="prevStep" class="inline-flex items-center justify-center rounded-lg bg-muted px-5 py-2 text-xs sm:text-sm font-medium text-foreground shadow-sm transition hover:bg-muted/80 focus:outline-none focus:ring-2 focus:ring-border focus:ring-offset-2 focus:ring-offset-background">
                                مرحله قبل
                            </button>
                        @endif
                    </div>

                    <div class="flex justify-end">
                        @if($currentStep < $totalSteps)
                            <button wire:click="nextStep" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-background">
                                مرحله بعد
                            </button>
                        @else
                            @if($canEdit)
                                <button wire:click="finalSubmit" class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-5 py-2 text-xs sm:text-sm font-medium text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 focus:ring-offset-background">
                                    ثبت نهایی پیش‌جلسه
                                </button>
                            @else
                                <a href="{{ route('client.profile.consultation.sessions') }}" class="inline-flex items-center justify-center rounded-lg bg-muted px-5 py-2 text-xs sm:text-sm font-medium text-foreground shadow-sm transition hover:bg-muted/80 focus:outline-none focus:ring-2 focus:ring-border focus:ring-offset-2 focus:ring-offset-background">
                                    بازگشت به لیست جلسات
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
