<div>
    <div>
        <div class="max-w-7xl space-y-14 px-4 mx-auto">
            @php
                // (I) در حالتِ هفتهٔ آزمایشی سایدبار نمایش داده نمی‌شود.
                $u = auth()->user();
                $inTrialCs = $u && $u->trialWeek && ! $u->isSchoolStudent()
                    && ! ($u->student && $u->student->hasActivePaidAccess());
            @endphp
            <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
                @unless($inTrialCs)
                    <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                        <livewire:client.profile.sidebar/>
                    </div>
                @endunless

                <div class="{{ $inTrialCs ? 'col-span-1 md:col-span-12' : 'lg:col-span-9 md:col-span-8' }}">
                    <div class="space-y-6">

                        {{-- Section Title --}}
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">افزودن برنامه کلاسی</div>
                            <a wire:navigate href="{{ $backUrl ?: ($inTrialCs ? route('client.profile.trial.guide') : route('client.profile.dashboard')) }}"
                               class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-secondary border border-border rounded-full text-muted transition-colors hover:text-foreground px-6 ms-auto">
                                <span class="font-semibold text-xs">
                                    {{ $backUrl ? 'بازگشت به پیش‌جلسه' : ('بازگشت' . ($inTrialCs ? ' به راهنما' : '')) }}
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3"></path>
                                </svg>
                            </a>
                        </div>

                        {{-- اطلاعات پایه و رشته --}}
                        <div dir="rtl" class="rounded-2xl border border-border bg-primary p-4 md:p-6">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-white font-bold text-lg">برنامه کلاسی</h2>
                                        <p class="text-white/70 text-sm">پایه
                                            @if($student?->personal_info)
                                                {{ $student->personal_info->grade == '10' ? 'دهم' : ($student->personal_info->grade == '11' ? 'یازدهم' : 'دوازدهم') }}
(
                                                {{ $student->personal_info->field == 'math' ? 'ریاضی و فیزیک' : ($student->personal_info->field == 'experimental' ? 'علوم تجربی' : 'علوم انسانی') }}
                                                )
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                @if($isFinalized)
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-500 text-white rounded-full text-sm font-semibold border border-emerald-400 shadow-lg shadow-emerald-500/30">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5 13l4 4L19 7"/>
        </svg>
        نهایی شده
    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-500 text-white rounded-full text-sm font-semibold border border-amber-400 shadow-lg shadow-amber-500/30">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        در حال تکمیل
    </span>
                                @endif
                            </div>
                        </div>

                        <div dir="rtl" class="rounded-2xl border border-border bg-background p-4 md:p-5">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-foreground">وضعیت مدرسه</h3>
                                        @if($isGraduate)
                                            <span class="inline-flex items-center rounded-full bg-secondary px-2.5 py-1 text-[11px] font-bold text-muted">فارغ‌التحصیل</span>
                                        @elseif($this->attendsSchoolSwitchLocked)
                                            <span class="inline-flex items-center rounded-full bg-rose-500/10 px-2.5 py-1 text-[11px] font-bold text-rose-500">قفل شده</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-muted leading-6">اگر وضعیتت نسبت به زمان ثبت‌نام تغییر کرده، از همین‌جا آن را به‌روزرسانی کن.</p>
                                    @if(!$isGraduate)
                                        <p class="text-xs text-muted">
                                            {{ $this->attendsSchoolSwitchLocked
                                                ? 'سقف ۲ بار تغییر برای این بخش استفاده شده و دیگر قابل ویرایش نیست.'
                                                : 'فقط ۲ بار امکان تغییر داری. تعداد باقی‌مانده: ' . $this->remainingAttendsSchoolChanges . ' بار' }}
                                        </p>
                                    @else
                                        <p class="text-xs text-muted">برای دانش‌آموز فارغ‌التحصیل امکان تغییر این وضعیت وجود ندارد.</p>
                                    @endif
                                </div>

                                <label class="inline-flex items-center gap-3 {{ $this->attendsSchoolSwitchLocked ? 'cursor-not-allowed opacity-70' : 'cursor-pointer' }}">
                                    <span class="text-sm font-semibold text-foreground">
                                        {{ $attendsSchool ? 'به مدرسه می‌روم' : 'به مدرسه نمی‌روم' }}
                                    </span>
                                    <span class="relative inline-flex items-center">
                                        <input type="checkbox"
                                               class="peer sr-only"
                                               wire:model.live="attendsSchool"
                                               @disabled($this->attendsSchoolSwitchLocked)>
                                        <span class="block h-8 w-14 rounded-full bg-secondary transition peer-checked:bg-primary"></span>
                                        <span class="absolute right-1 h-6 w-6 rounded-full bg-white shadow transition peer-checked:right-7"></span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        @if($this->shouldShowScheduleEditor)
                        {{-- راهنما --}}
                        <div dir="rtl" class="rounded-2xl border border-border bg-secondary p-4">
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5 text-primary mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>

                                <div class="text-sm text-muted leading-relaxed space-y-1">
                                    <p>- تمام روزهای هفته اختیاری هستند و الزامی برای تکمیل شنبه تا چهارشنبه وجود
                                        ندارد.</p>
                                    <p>- برای ثبت نهایی کافی است <strong class="text-foreground">حداقل یک پارت</strong>
                                        ثبت کرده باشید.</p>
                                    <p>- هر روز حداکثر 5 پارت قابل ثبت است و پارت‌ها باید به ترتیب پر شوند.</p>
                                    <p>- با کلیک روی هر پارت ثبت‌شده می‌توانید آن را ویرایش کنید.</p>
                                    <p>- در هر زمان می‌توانید پارت‌ها را ویرایش کنید و دوباره ثبت نهایی بزنید.</p>
                                </div>
                            </div>
                        </div>

                        {{-- جدول برنامه کلاسی --}}
                        <div dir="rtl" class="space-y-4">
                            @foreach($days as $day)
                                <div class="rounded-2xl  overflow-hidden">

                                    {{-- هدر روز --}}
                                    <div
                                        class="flex items-center justify-between px-4 py-3 bg-secondary mb-3">
                                        <div class="flex items-center gap-3">

                                            <div>
                                                <span class="font-bold text-foreground">{{ $day['name'] }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="text-sm text-muted">{{ $day['filled_count'] }} / {{ \App\Models\ClassSchedule::MAX_PARTS_PER_DAY }}</span>

                                            {{-- دکمه حذف تمامی پارت‌های روز --}}
                                            @if($day['filled_count'] > 0)
                                                <button
                                                    wire:click="$dispatch('open-delete-day-modal', { day: {{ $day['day_of_week'] }}, name: '{{ $day['name'] }}' })"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold
                                                           bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400
                                                           border border-red-200 dark:border-red-800
                                                           hover:bg-red-100 dark:hover:bg-red-900/40
                                                           transition-all duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- پارت‌ها --}}
                                    <div class="p-4 glass">
                                        @php
                                            $filledParts = collect($day['parts'])->where('is_filled', true);
                                            $nextUnlocked = collect($day['parts'])->where('is_filled', false)->where('is_unlocked', true)->first();
                                        @endphp

                                        {{-- Desktop --}}
                                        <div class="hidden md:grid md:grid-cols-5 md:gap-3">
                                            @foreach($day['parts'] as $partInfo)
                                                <div>
                                                    @if($partInfo['is_filled'])
                                                        <div class="relative group">
                                                            <button
                                                                @click="$dispatch('open-part-modal', { day: {{ $day['day_of_week'] }}, part: {{ $partInfo['order'] }} })"
                                                                class="w-full rounded-xl border-2 border-green-300 dark:border-green-700 bg-green-50 dark:bg-green-900/20 p-3 text-center transition-all hover:border-green-400 hover:shadow-md cursor-pointer">
                                                                <div class="text-xs text-muted mb-1">
                                                                    پارت {{ $partInfo['order'] }}</div>
                                                                <div
                                                                    class="font-bold text-sm text-foreground truncate">{{ $partInfo['part']->lesson_name }}</div>

                                                            </button>
                                                            {{-- دکمه حذف تک پارت --}}
                                                            <button
                                                                wire:click="$dispatch('open-delete-part-modal', { day: {{ $day['day_of_week'] }}, part: {{ $partInfo['order'] }}, name: '{{ $partInfo['part']->lesson_name }}' })"
                                                                class="absolute -top-2 -left-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full items-center justify-center text-xs hidden group-hover:flex shadow-lg transition-colors">
                                                                &times;
                                                            </button>
                                                        </div>
                                                    @elseif($partInfo['is_unlocked'])
                                                        <button
                                                            @click="$dispatch('open-part-modal', { day: {{ $day['day_of_week'] }}, part: {{ $partInfo['order'] }} })"
                                                            class="w-full rounded-xl border-2 border-dashed border-primary/40 bg-primary/5 p-3 text-center transition-all hover:border-primary hover:bg-primary/10 hover:shadow-md cursor-pointer">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 class="w-6 h-6 text-primary mx-auto" fill="none"
                                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      stroke-width="2" d="M12 4v16m8-8H4"/>
                                                            </svg>
                                                            <div class="text-xs text-primary mt-1 font-semibold">
                                                                افزودن
                                                            </div>
                                                        </button>
                                                    @else
                                                        <div
                                                            class="w-full rounded-xl border border-border bg-secondary/50 p-3 text-center opacity-40">
                                                            <div class="text-xs text-muted mb-1">
                                                                پارت {{ $partInfo['order'] }}</div>
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 class="w-6 h-6 text-muted mx-auto" fill="none"
                                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- Mobile --}}
                                        <div
                                            class="flex md:hidden gap-3 overflow-x-auto [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden -mx-4 px-4 snap-x snap-mandatory">
                                            @if($nextUnlocked)
                                                <div class="w-28 flex-shrink-0 snap-start">
                                                    <button
                                                        @click="$dispatch('open-part-modal', { day: {{ $day['day_of_week'] }}, part: {{ $nextUnlocked['order'] }} })"
                                                        class="w-full h-full min-h-[88px] rounded-xl border-2 border-dashed border-primary/50 bg-primary/5 p-3 text-center transition-all active:bg-primary/10">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             class="w-7 h-7 text-primary mx-auto" fill="none"
                                                             viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2" d="M12 4v16m8-8H4"/>
                                                        </svg>
                                                        <div class="text-xs text-primary mt-1 font-bold">افزودن</div>
                                                    </button>
                                                </div>
                                            @endif

                                            @foreach($filledParts as $partInfo)
                                                <div class="w-32 flex-shrink-0 snap-start mt-2">
                                                    <div class="relative">
                                                        <button
                                                            @click="$dispatch('open-part-modal', { day: {{ $day['day_of_week'] }}, part: {{ $partInfo['order'] }} })"
                                                            class="w-full rounded-xl border-2 border-green-300 dark:border-green-700 bg-green-50 dark:bg-green-900/20 p-3 text-center transition-all active:border-green-400 cursor-pointer">
                                                            <div class="text-[10px] text-muted mb-0.5">
                                                                پارت {{ $partInfo['order'] }}</div>
                                                            <div
                                                                class="font-bold text-xs text-foreground truncate">{{ $partInfo['part']->lesson_name }}</div>
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 class="w-3.5 h-3.5 text-green-500 mx-auto mt-1"
                                                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        </button>
                                                        <button
                                                            wire:click="$dispatch('open-delete-part-modal', { day: {{ $day['day_of_week'] }}, part: {{ $partInfo['order'] }}, name: '{{ $partInfo['part']->lesson_name }}' })"
                                                            class="absolute -top-2 -left-2 w-5 h-5 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center text-[10px] shadow-lg transition-colors">
                                                            &times;
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if($filledParts->isEmpty() && ($isFinalized || !$nextUnlocked))
                                                    <div class="w-full rounded-xl border border-border bg-secondary/50 p-3 text-center opacity-40">
                                                        <div class="text-xs text-muted mb-1">
                                                            پارتی وجود ندارد !</div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-muted mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                        </svg>
                                                    </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- دکمه ثبت نهایی --}}
                        <div dir="rtl" class="flex items-center justify-end gap-4">
                            <button wire:click="openFinalizeModal"
                                    wire:loading.attr="disabled"
                                    wire:target="openFinalizeModal"
                                    @if(!$canFinalize) disabled @endif
                                    class="inline-flex  items-center gap-2 px-8 py-3  rounded-xl font-bold text-sm transition-colors
                                    {{ $canFinalize
                                        ? 'bg-primary hover:bg-blue-600 text-white shadow-lg shadow-green-500/30'
                                        : 'bg-secondary  text-muted cursor-not-allowed' }}" >
                                <span wire:loading wire:target="openFinalizeModal">
                                    <svg class="animate-spin w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                         fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                </span>
                                <span wire:loading.remove wire:target="openFinalizeModal">
                                       ثبت نهایی
                                </span>

                            </button>
                        </div>
                        @else
                            <div dir="rtl" class="rounded-2xl border border-sky-200/70 bg-sky-50 px-4 py-4 text-sm text-sky-800 dark:bg-sky-500/10 dark:text-sky-300 dark:border-sky-500/40">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"></path>
                                    </svg>
                                    <div class="space-y-2">
                                        <p class="font-bold">در حال حاضر وضعیت شما روی «مدرسه نمی‌روم» است.</p>
                                        <p class="leading-6">در این حالت نیازی به ثبت برنامه کلاسی مدرسه نداری. هر زمان دوباره مدرسه رفتی، همین سوییچ را روشن کن تا فرم برنامه کلاسی برایت فعال شود.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        {{-- ======================================================= --}}
        {{-- مودال تایید حذف تک پارت (Alpine.js) --}}
        {{-- ======================================================= --}}
        <div
            x-data="{
        show: false,
        day: null,
        part: null,
        name: '',
        open(e) {
            this.day  = e.detail.day;
            this.part = e.detail.part;
            this.name = e.detail.name;
            this.show = true;
            document.body.classList.add('overflow-hidden'); // جلوگیری از اسکرول
        },
        close() {
            this.show = false;
            document.body.classList.remove('overflow-hidden'); // بازگرداندن اسکرول
        }
    }"
            @open-delete-part-modal.window="open($event)"
            x-show="show"
            x-cloak
            class="fixed inset-0 z-[82] flex flex-col justify-end sm:items-center sm:justify-center"
            @keydown.escape.window="close()">

            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="close()"></div>

            <div
                class="relative z-10 w-full sm:max-w-sm bg-background dark:bg-zinc-900 rounded-t-3xl sm:rounded-2xl  sm:border border-border shadow-2xl flex flex-col pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-8"
                dir="rtl">

                <div class="sm:hidden flex justify-center pt-3 pb-1 shrink-0">
                    <div class="w-10 h-1 rounded-full bg-foreground/20"></div>
                </div>

                <div class="p-6 text-center">
                    <div
                        class="w-14 h-14 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-500" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-foreground text-lg mb-2">حذف پارت</h3>
                    <p class="text-sm text-muted">
                        آیا از حذف درس <span class="font-bold text-foreground" x-text="`«${name}»`"></span> مطمئنید؟
                    </p>
                </div>

                <div class="flex items-center gap-3  border-border px-5 pb-5">
                    <button @click="close()"
                            class="w-full rounded-xl border border-border py-2.5 px-4 text-sm font-semibold text-foreground
                                   bg-background dark:bg-zinc-900 hover:bg-muted/50 dark:hover:bg-zinc-800 transition-colors">
                        انصراف
                    </button>
                    <button
                        @click="$wire.deletePart(day, part); close();"
                        wire:loading.attr="disabled"
                        wire:target="deletePart"
                        class="w-full rounded-xl py-2.5 px-4 text-sm font-semibold text-white
                               bg-red-500 hover:bg-red-600 transition-colors
                               inline-flex items-center justify-center gap-2">
                        <span wire:loading wire:target="deletePart">
                            <svg class="animate-spin w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="deletePart">حذف</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ======================================================= --}}
        {{-- مودال تایید حذف تمام پارت‌های روز (Alpine.js) --}}
        {{-- ======================================================= --}}
        <div
            x-data="{
        show: false,
        day: null,
        name: '',
        open(e) {
            this.day  = e.detail.day;
            this.name = e.detail.name;
            this.show = true;
            document.body.classList.add('overflow-hidden'); // جلوگیری از اسکرول
        },
        close() {
            this.show = false;
            document.body.classList.remove('overflow-hidden'); // بازگرداندن اسکرول
        }
    }"
            @open-delete-day-modal.window="open($event)"
            x-show="show"
            x-cloak
            class="fixed inset-0 z-[78] flex flex-col justify-end sm:items-center sm:justify-center"
            @keydown.escape.window="close()">

            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="close()"></div>

            <div
                class="relative z-10 w-full sm:max-w-sm bg-background dark:bg-zinc-900 rounded-t-3xl sm:rounded-2xl  sm:border border-border shadow-2xl flex flex-col pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-8"
                dir="rtl">

                <div class="sm:hidden flex justify-center pt-3 pb-1 shrink-0">
                    <div class="w-10 h-1 rounded-full bg-foreground/20"></div>
                </div>

                <div class="p-6 text-center">
                    <div
                        class="w-14 h-14 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-500" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-foreground text-lg mb-2">حذف تمامی پارت‌ها</h3>
                    <p class="text-sm text-muted">
                        آیا از حذف <strong class="text-red-500">تمامی پارت‌های</strong> روز
                        <span class="font-bold text-foreground" x-text="`«${name}»`"></span>
                        مطمئنید؟
                    </p>
                    <p class="text-xs  mt-3 text-red-500">این عمل قابل بازگشت نیست.</p>

                </div>

                <div class="flex items-center gap-3  border-border px-5 pb-5">
                    <button @click="close()"
                            class="w-full rounded-xl border border-border py-2.5 px-4 text-sm font-semibold text-foreground
                                   bg-background dark:bg-zinc-900 hover:bg-muted/50 dark:hover:bg-zinc-800 transition-colors">
                        انصراف
                    </button>
                    <button
                        @click="$wire.deleteAllDayParts(day); close();"
                        wire:loading.attr="disabled"
                        wire:target="deleteAllDayParts"
                        class="w-full rounded-xl py-2.5 px-4 text-sm font-semibold text-white
                               bg-red-500 hover:bg-red-600 transition-colors
                               inline-flex items-center justify-center gap-2">
                        <span wire:loading wire:target="deleteAllDayParts">
                            <svg class="animate-spin w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="deleteAllDayParts">حذف همه</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- مودال ثبت نهایی (Alpine - instant open) --}}
        <div x-data="{ finalizeOpen: @entangle('showFinalizeModal') }"
             x-init="$watch('finalizeOpen', value => {
         if (value) {
             document.body.classList.add('overflow-hidden');
         } else {
             document.body.classList.remove('overflow-hidden');
         }
     })"
             x-show="finalizeOpen" x-cloak
             class="fixed inset-0 z-[72] flex flex-col justify-end sm:items-center sm:justify-center"
             @keydown.escape.window="finalizeOpen = false">

            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"
                 x-show="finalizeOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="$wire.closeFinalizeModal()"></div>

            <div
                class="relative z-10 w-full sm:max-w-md bg-background dark:bg-zinc-900 rounded-t-3xl sm:rounded-2xl  sm:border border-border shadow-2xl flex flex-col pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                x-show="finalizeOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-8"
                dir="rtl">

                <div class="sm:hidden flex justify-center pt-3 pb-1 shrink-0">
                    <div class="w-10 h-1 rounded-full bg-foreground/20"></div>
                </div>

                <div class="p-5 border-b border-border">
                    <h3 class="font-bold text-foreground text-lg">به‌روزرسانی برنامه</h3>
                </div>
                <div class="p-5 space-y-2 text-sm text-muted">
                    <p>آیا از ثبت نهایی برنامه کلاسی مطمئن هستید؟</p>
                    <p class="text-primary">در آینده هم می‌توانید ویرایش کنید و دوباره ثبت نهایی بزنید.</p>
                </div>
                <div class="flex items-center gap-x-3  border-border p-4 pb-safe">
                    <button wire:click="closeFinalizeModal"
                            wire:loading.attr="disabled"
                            wire:target="closeFinalizeModal"
                            class="w-full rounded-xl border border-border py-3 px-4 text-foreground
                                   hover:bg-muted/50 dark:hover:bg-zinc-800 transition-colors text-sm font-semibold">
                        انصراف
                    </button>
                    <button wire:click="finalizeSchedule"
                            wire:loading.attr="disabled"
                            wire:target="finalizeSchedule"
                            class="w-full rounded-xl bg-primary hover:bg-primary-600 text-white py-3 px-4
                                   transition-colors inline-flex items-center justify-center gap-2 text-sm font-semibold">
                        <span wire:loading wire:target="finalizeSchedule">
                            <svg class="animate-spin w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </span>
                        <span wire:loading.remove wire:target="finalizeSchedule">بله، ثبت نهایی شود</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- مودال انتخاب درس (Alpine instant-open + loading) --}}
        <div x-data="{
    partModalOpen: false,
    openPart(day, part) {
        this.partModalOpen = true;
        document.body.classList.add('overflow-hidden'); // جلوگیری از اسکرول
        $wire.openPartModal(day, part);
    },
    closePart() {
        this.partModalOpen = false;
        document.body.classList.remove('overflow-hidden'); // بازگرداندن اسکرول
        $wire.closeModal();
    }
}"
             @open-part-modal.window="openPart($event.detail.day, $event.detail.part)"
             @close-part-modal.window="closePart()"
             x-show="partModalOpen" x-cloak
             class="fixed inset-0 z-[70] flex flex-col justify-end sm:items-center sm:justify-center"
             @keydown.escape.window="closePart()">

            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"
                 x-show="partModalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closePart()"></div>

            <div
                class="relative z-10 w-full sm:max-w-md bg-background dark:bg-zinc-900 rounded-t-3xl sm:rounded-2xl  sm:border border-border shadow-2xl flex flex-col max-h-[90vh] pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                x-show="partModalOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-8">

                <div class="sm:hidden flex justify-center pt-3 pb-1 shrink-0">
                    <div class="w-10 h-1 rounded-full bg-foreground/20"></div>
                </div>

                <div class="shrink-0 p-4 border-b border-border flex items-center justify-between">
                    <h3 class="font-bold text-foreground text-base">انتخاب درس</h3>
                    <button @click="closePart()" class="text-muted hover:text-red-500 transition-colors p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-4">
                    {{-- Loading skeleton --}}
                    <div wire:loading wire:target="openPartModal" class="space-y-2">
                        @for($i = 0; $i < 5; $i++)
                            <div class="h-12 bg-muted/40 rounded-xl animate-pulse"></div>
                        @endfor
                    </div>

                    {{-- Content --}}
                    <div wire:loading.remove wire:target="openPartModal">
                        @if(count($subjects) > 0)
                            @php
                                $maxSelectable = \App\Models\ClassSchedule::MAX_PARTS_PER_DAY - ($selectedPart ?? 1) + 1;
                                $selectedCount = count($selectedSubjectIds);
                            @endphp

                            {{-- هدر: راهنما + شمارنده --}}
                            <div class="flex items-center justify-between mb-3 gap-2">
                                <p class="text-sm text-muted">یک یا چند درس انتخاب کنید:</p>
                                <span class="shrink-0 text-xs px-2.5 py-1 rounded-full font-bold
                {{ $selectedCount > 0 ? 'bg-primary/10 text-primary' : 'bg-secondary text-muted' }}">
                {{ $selectedCount }} از {{ $maxSelectable }}
            </span>
                            </div>

                            @if($selectedCount >= $maxSelectable)
                                <div class="mb-3 px-3 py-2 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 text-xs">
                                    به حداکثر تعداد قابل انتخاب رسیدید.
                                </div>
                            @endif

                            <div class="space-y-2">
                                @foreach($subjects as $subject)
                                    @php
                                        $isSelected = in_array($subject->id, $selectedSubjectIds);
                                        $orderIndex = $isSelected ? array_search($subject->id, $selectedSubjectIds) : null;
                                        $assignedPart = $isSelected ? ($selectedPart + $orderIndex) : null;
                                    @endphp

                                    <button wire:click="toggleSubject({{ $subject->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="toggleSubject"
                                            class="w-full text-right px-3 py-3 rounded-xl border transition-all
                        {{ $isSelected
                            ? 'border-primary bg-primary/10 text-primary font-bold'
                            : 'border-border bg-background dark:bg-zinc-900 hover:border-primary/40 hover:bg-primary/5 text-foreground' }}">
                                        <div class="flex items-center justify-between gap-3">
                                            {{-- چپ‌چین: تیک + نام --}}
                                            <div class="flex items-center gap-3 min-w-0 flex-1">
                            <span class="flex-shrink-0 w-5 h-5 rounded-md border-2 flex items-center justify-center transition-colors
                                   {{ $isSelected
                                       ? 'bg-primary border-primary'
                                       : 'border-border bg-background dark:bg-zinc-900' }}">
                                @if($isSelected)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </span>
                                                <span class="truncate text-sm">{{ $subject->name }}</span>
                                            </div>

                                            {{-- راست‌چین: شماره پارت اختصاص‌یافته + نوع --}}
                                            <div class="flex items-center gap-2 shrink-0">
                                                @if($isSelected)
                                                    <span class="text-[10px] px-1.5 py-0.5 rounded-md bg-primary text-white font-bold">
                                    پارت {{ $assignedPart }}
                                </span>
                                                @endif
                                                <span class="text-[11px] {{ $subject->type === 'general' ? 'text-blue-500' : 'text-orange-500' }}">
                                {{ $subject->type === 'general' ? 'عمومی' : 'تخصصی' }}
                            </span>
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-muted mx-auto mb-3"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <p class="text-muted text-sm">درسی یافت نشد. لطفاً اطلاعات شخصی (پایه و رشته) خود را تکمیل کنید.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="shrink-0 flex items-center gap-x-4 border-border p-4 pb-safe">
                    <button @click="closePart()"
                            class="flex items-center justify-center gap-x-2 w-full border border-border rounded-xl text-foreground py-3 px-4
                   hover:bg-muted/50 dark:hover:bg-zinc-800 transition-colors text-sm font-semibold">
                        انصراف
                    </button>
                    <button wire:click="savePart"
                            wire:loading.attr="disabled"
                            wire:target="savePart"
                            @if(empty($selectedSubjectIds)) disabled @endif
                            class="flex items-center justify-center gap-x-2 w-full rounded-xl py-3 px-4 transition-colors
            {{ !empty($selectedSubjectIds)
                ? 'bg-primary hover:bg-primary/90'
                : 'bg-muted cursor-not-allowed' }}">
        <span wire:loading wire:target="savePart">
            <svg class="animate-spin w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </span>
                        <span wire:loading.remove wire:target="savePart" class="font-bold text-sm text-white">
            ذخیره @if(count($selectedSubjectIds) > 1) ({{ count($selectedSubjectIds) }} پارت) @endif
        </span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
