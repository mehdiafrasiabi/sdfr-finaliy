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
                            <x-ui.button href="{{ $backUrl ?: ($inTrialCs ? route('client.profile.trial.guide') : route('client.profile.dashboard')) }}"
                                         wire:navigate variant="secondary-outline" icon="chevron-right" pill
                                         class="ms-auto">
                                {{ $backUrl ? 'بازگشت به پیش‌جلسه' : ('بازگشت' . ($inTrialCs ? ' به راهنما' : '')) }}
                            </x-ui.button>
                        </div>

                        {{-- اطلاعات پایه و رشته --}}
                        <div dir="rtl" class="rounded-2xl border border-border bg-primary p-4 md:p-6">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center">
                                        <x-ui.icon name="book-open" class="w-6 h-6 text-white"/>
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
                                    <x-ui.status-badge status="paid" label="نهایی شده" class="!text-sm !px-4 !py-2"/>
                                @else
                                    <x-ui.status-badge status="pending" label="در حال تکمیل" class="!text-sm !px-4 !py-2"/>
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
                                            <span class="inline-flex items-center rounded-full bg-error/10 px-2.5 py-1 text-[11px] font-bold text-error">قفل شده</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-muted leading-6">اگر وضعیتت نسبت به زمان ثبت‌نام تغییر کرده، از همین‌جا آن را به‌روزرسانی کن.</p>
                                    @if(!$isGraduate)
                                        <p class="text-xs text-muted">
                                            {{ $this->attendsSchoolSwitchLocked
                                                ? 'سقف ' . $this->maxAttendsSchoolChanges . ' بار تغییر برای این بخش استفاده شده و دیگر قابل ویرایش نیست.'
                                                : 'فقط ' . $this->maxAttendsSchoolChanges . ' بار امکان تغییر داری. تعداد باقی‌مانده: ' . $this->remainingAttendsSchoolChanges . ' بار' }}
                                        </p>
                                    @else
                                        <p class="text-xs text-muted">برای دانش‌آموز فارغ‌التحصیل امکان تغییر این وضعیت وجود ندارد.</p>
                                    @endif
                                </div>

                                <button type="button"
                                        role="switch"
                                        aria-checked="{{ $attendsSchool ? 'true' : 'false' }}"
                                        wire:loading.attr="disabled"
                                        wire:target="changeAttendsSchool"
                                        @if(! $this->attendsSchoolSwitchLocked)
                                            @click="$dispatch('open-attends-school-modal', { next: {{ $attendsSchool ? 'false' : 'true' }} })"
                                        @endif
                                        @disabled($this->attendsSchoolSwitchLocked)
                                        class="inline-flex items-center gap-3 {{ $this->attendsSchoolSwitchLocked ? 'cursor-not-allowed opacity-70' : 'cursor-pointer' }}">
                                    <span class="text-sm font-semibold text-foreground">
                                        {{ $attendsSchool ? 'به مدرسه می‌روم' : 'به مدرسه نمی‌روم' }}
                                    </span>
                                    <span class="relative inline-flex items-center" wire:loading.class="opacity-50" wire:target="changeAttendsSchool">
                                        <span class="block h-8 w-14 rounded-full transition {{ $attendsSchool ? 'bg-primary' : 'bg-secondary' }}"></span>
                                        <span class="absolute h-6 w-6 rounded-full bg-white shadow transition {{ $attendsSchool ? 'right-7' : 'right-1' }}"></span>
                                    </span>
                                </button>
                            </div>
                        </div>

                        @if($this->shouldShowScheduleEditor)
                        {{-- راهنما --}}
                        <div dir="rtl" class="rounded-2xl border border-border bg-secondary p-4">
                            <div class="flex items-start gap-3">
                                <x-ui.icon name="info" class="w-5 h-5 text-primary mt-0.5 flex-shrink-0"/>

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
                                                <x-ui.button type="button" size="sm" variant="error-soft" icon="trash" pill
                                                             wire:click="$dispatch('open-delete-day-modal', { day: {{ $day['day_of_week'] }}, name: '{{ $day['name'] }}' })">
                                                    حذف همه
                                                </x-ui.button>
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
                                                                data-elevated="false"
                                                                class="btn-press w-full rounded-xl border-2 border-success/40 bg-success/10 p-3 text-center transition-all hover:border-success hover:shadow-md cursor-pointer">
                                                                <div class="text-xs text-muted mb-1">
                                                                    پارت {{ $partInfo['order'] }}</div>
                                                                <div
                                                                    class="font-bold text-sm text-foreground truncate">{{ $partInfo['part']->lesson_name }}</div>

                                                            </button>
                                                            {{-- دکمه حذف تک پارت --}}
                                                            <button
                                                                wire:click="$dispatch('open-delete-part-modal', { day: {{ $day['day_of_week'] }}, part: {{ $partInfo['order'] }}, name: '{{ $partInfo['part']->lesson_name }}' })"
                                                                data-elevated="true"
                                                                class="btn-press absolute -top-2 -left-2 w-6 h-6 bg-error hover:bg-error/90 text-white rounded-full items-center justify-center hidden group-hover:flex shadow-lg transition-colors">
                                                                <x-ui.icon name="x" class="w-3.5 h-3.5"/>
                                                            </button>
                                                        </div>
                                                    @elseif($partInfo['is_unlocked'])
                                                        <button
                                                            @click="$dispatch('open-part-modal', { day: {{ $day['day_of_week'] }}, part: {{ $partInfo['order'] }} })"
                                                            data-elevated="false"
                                                            class="btn-press w-full rounded-xl border-2 border-dashed border-primary/40 bg-primary/5 p-3 text-center transition-all hover:border-primary hover:bg-primary/10 hover:shadow-md cursor-pointer">
                                                            <x-ui.icon name="plus" class="w-6 h-6 text-primary mx-auto"/>
                                                            <div class="text-xs text-primary mt-1 font-semibold">
                                                                افزودن
                                                            </div>
                                                        </button>
                                                    @else
                                                        <div
                                                            class="w-full rounded-xl border border-border bg-secondary/50 p-3 text-center opacity-40">
                                                            <div class="text-xs text-muted mb-1">
                                                                پارت {{ $partInfo['order'] }}</div>
                                                            <x-ui.icon name="lock" class="w-6 h-6 text-muted mx-auto"/>
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
                                                        data-elevated="false"
                                                        class="btn-press w-full h-full min-h-[88px] rounded-xl border-2 border-dashed border-primary/50 bg-primary/5 p-3 text-center transition-all active:bg-primary/10">
                                                        <x-ui.icon name="plus" class="w-7 h-7 text-primary mx-auto"/>
                                                        <div class="text-xs text-primary mt-1 font-bold">افزودن</div>
                                                    </button>
                                                </div>
                                            @endif

                                            @foreach($filledParts as $partInfo)
                                                <div class="w-32 flex-shrink-0 snap-start mt-2">
                                                    <div class="relative">
                                                        <button
                                                            @click="$dispatch('open-part-modal', { day: {{ $day['day_of_week'] }}, part: {{ $partInfo['order'] }} })"
                                                            data-elevated="false"
                                                            class="btn-press w-full rounded-xl border-2 border-success/40 bg-success/10 p-3 text-center transition-all active:border-success cursor-pointer">
                                                            <div class="text-[10px] text-muted mb-0.5">
                                                                پارت {{ $partInfo['order'] }}</div>
                                                            <div
                                                                class="font-bold text-xs text-foreground truncate">{{ $partInfo['part']->lesson_name }}</div>
                                                            <x-ui.icon name="check" class="w-3.5 h-3.5 text-success mx-auto mt-1"/>
                                                        </button>
                                                        <button
                                                            wire:click="$dispatch('open-delete-part-modal', { day: {{ $day['day_of_week'] }}, part: {{ $partInfo['order'] }}, name: '{{ $partInfo['part']->lesson_name }}' })"
                                                            data-elevated="true"
                                                            class="btn-press absolute -top-2 -left-2 w-5 h-5 bg-error hover:bg-error/90 text-white rounded-full flex items-center justify-center shadow-lg transition-colors">
                                                            <x-ui.icon name="x" class="w-3 h-3"/>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if($filledParts->isEmpty() && ($isFinalized || !$nextUnlocked))
                                                    <div class="w-full rounded-xl border border-border bg-secondary/50 p-3 text-center opacity-40">
                                                        <div class="text-xs text-muted mb-1">
                                                            پارتی وجود ندارد !</div>
                                                        <x-ui.icon name="lock" class="w-6 h-6 text-muted mx-auto"/>
                                                    </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- دکمه ثبت نهایی --}}
                        <div dir="rtl" class="flex items-center justify-end gap-4">
                            <x-ui.button wire:click="openFinalizeModal"
                                         wire:loading.attr="disabled" wire:target="openFinalizeModal"
                                         :disabled="!$canFinalize"
                                         variant="{{ $canFinalize ? 'primary' : 'secondary' }}"
                                         icon="check" size="lg" pill>
                                ثبت نهایی
                            </x-ui.button>
                        </div>
                        @else
                            <div dir="rtl" class="rounded-2xl border border-info/30 bg-info/10 px-4 py-4 text-sm text-info">
                                <div class="flex items-start gap-3">
                                    <x-ui.icon name="info" class="w-5 h-5 mt-0.5 shrink-0"/>
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
        },
        close() {
            this.show = false;
        }
    }"
            @open-delete-part-modal.window="open($event)"
            x-effect="show ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
            @keydown.escape.window="close()">
            <div x-show="show" x-cloak>
                <div
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm"
                    @click="close()"
                ></div>

                <div
                    x-show="show"
                    class="fixed inset-0 z-[101] flex items-end justify-center overscroll-contain sm:items-center sm:p-4"
                    @click.self="close()"
                >
                    <div
                        x-show="show"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                        class="relative w-full sm:max-w-sm bg-background border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                        dir="rtl">

                        <div class="mx-auto mt-3 mb-1 h-1.5 w-14 rounded-full bg-border sm:hidden shrink-0"></div>

                        <button type="button" @click="close()" data-elevated="false"
                                class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors z-10">
                            <x-ui.icon name="x" class="w-4 h-4"/>
                        </button>

                        <div class="p-6 text-center">
                            <div class="w-14 h-14 bg-error/15 rounded-full flex items-center justify-center mx-auto mb-4">
                                <x-ui.icon name="trash" class="w-7 h-7 text-error"/>
                            </div>
                            <h3 class="font-bold text-foreground text-lg mb-2">حذف پارت</h3>
                            <p class="text-sm text-muted">
                                آیا از حذف درس <span class="font-bold text-foreground" x-text="`«${name}»`"></span> مطمئنید؟
                            </p>
                        </div>

                        <div class="flex items-center gap-3 border-t border-border px-5 py-5">
                            <x-ui.button type="button" variant="secondary-outline" icon="x" block @click="close()">
                                انصراف
                            </x-ui.button>
                            <x-ui.button type="button" variant="error" icon="trash" block
                                         wire:loading.attr="disabled" wire:target="deletePart"
                                         @click="$wire.deletePart(day, part); close();">
                                حذف
                            </x-ui.button>
                        </div>
                    </div>
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
        },
        close() {
            this.show = false;
        }
    }"
            @open-delete-day-modal.window="open($event)"
            x-effect="show ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
            @keydown.escape.window="close()">
            <div x-show="show" x-cloak>
                <div
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm"
                    @click="close()"
                ></div>

                <div
                    x-show="show"
                    class="fixed inset-0 z-[101] flex items-end justify-center overscroll-contain sm:items-center sm:p-4"
                    @click.self="close()"
                >
                    <div
                        x-show="show"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                        class="relative w-full sm:max-w-sm bg-background border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                        dir="rtl">

                        <div class="mx-auto mt-3 mb-1 h-1.5 w-14 rounded-full bg-border sm:hidden shrink-0"></div>

                        <button type="button" @click="close()" data-elevated="false"
                                class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors z-10">
                            <x-ui.icon name="x" class="w-4 h-4"/>
                        </button>

                        <div class="p-6 text-center">
                            <div class="w-14 h-14 bg-error/15 rounded-full flex items-center justify-center mx-auto mb-4">
                                <x-ui.icon name="triangle-alert" class="w-7 h-7 text-error"/>
                            </div>
                            <h3 class="font-bold text-foreground text-lg mb-2">حذف تمامی پارت‌ها</h3>
                            <p class="text-sm text-muted">
                                آیا از حذف <strong class="text-error">تمامی پارت‌های</strong> روز
                                <span class="font-bold text-foreground" x-text="`«${name}»`"></span>
                                مطمئنید؟
                            </p>
                            <p class="text-xs mt-3 text-error">این عمل قابل بازگشت نیست.</p>
                        </div>

                        <div class="flex items-center gap-3 border-t border-border px-5 py-5">
                            <x-ui.button type="button" variant="secondary-outline" icon="x" block @click="close()">
                                انصراف
                            </x-ui.button>
                            <x-ui.button type="button" variant="error" icon="trash" block
                                         wire:loading.attr="disabled" wire:target="deleteAllDayParts"
                                         @click="$wire.deleteAllDayParts(day); close();">
                                حذف همه
                            </x-ui.button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ======================================================= --}}
        {{-- مودال تایید تغییر وضعیت مدرسه (Alpine.js) --}}
        {{-- ======================================================= --}}
        <div
            x-data="{
        show: false,
        next: null,
        open(e) {
            this.next = e.detail.next;
            this.show = true;
        },
        close() {
            this.show = false;
        }
    }"
            @open-attends-school-modal.window="open($event)"
            x-effect="show ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
            @keydown.escape.window="close()">
            <div x-show="show" x-cloak>
                <div
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm"
                    @click="close()"
                ></div>

                <div
                    x-show="show"
                    class="fixed inset-0 z-[101] flex items-end justify-center overscroll-contain sm:items-center sm:p-4"
                    @click.self="close()"
                >
                    <div
                        x-show="show"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                        class="relative w-full sm:max-w-sm bg-background border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                        dir="rtl">

                        <div class="mx-auto mt-3 mb-1 h-1.5 w-14 rounded-full bg-border sm:hidden shrink-0"></div>

                        <button type="button" @click="close()" data-elevated="false"
                                class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors z-10">
                            <x-ui.icon name="x" class="w-4 h-4"/>
                        </button>

                        <div class="p-6 text-center">
                            <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <x-ui.icon name="info" class="w-7 h-7 text-primary"/>
                            </div>
                            <h3 class="font-bold text-foreground text-lg mb-2">تغییر وضعیت مدرسه</h3>
                            <p class="text-sm text-muted">
                                آیا مطمئن هستید می‌خواهید وضعیت را به
                                <span class="font-bold text-foreground"
                                      x-text="next ? '«به مدرسه می‌روم»' : '«به مدرسه نمی‌روم»'"></span>
                                تغییر دهید؟
                            </p>
                        </div>

                        <div class="flex items-center gap-3 border-t border-border px-5 py-5">
                            <x-ui.button type="button" variant="secondary-outline" icon="x" block @click="close()">
                                انصراف
                            </x-ui.button>
                            <x-ui.button type="button" variant="primary" icon="check" block
                                         wire:loading.attr="disabled" wire:target="changeAttendsSchool"
                                         @click="$wire.changeAttendsSchool(next); close();">
                                بله، تغییر بده
                            </x-ui.button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- مودال ثبت نهایی (Alpine - instant open) --}}
        <div x-data="{ finalizeOpen: @entangle('showFinalizeModal') }"
             x-effect="finalizeOpen ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
             @keydown.escape.window="finalizeOpen = false">
            <div x-show="finalizeOpen" x-cloak>
                <div
                    x-show="finalizeOpen"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm"
                    @click="$wire.closeFinalizeModal()"
                ></div>

                <div
                    x-show="finalizeOpen"
                    class="fixed inset-0 z-[101] flex items-end justify-center overscroll-contain sm:items-center sm:p-4"
                    @click.self="$wire.closeFinalizeModal()"
                >
                    <div
                        x-show="finalizeOpen"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                        class="relative w-full sm:max-w-md bg-background border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                        dir="rtl">

                        <div class="mx-auto mt-3 mb-1 h-1.5 w-14 rounded-full bg-border sm:hidden shrink-0"></div>

                        <button type="button" @click="$wire.closeFinalizeModal()" data-elevated="false"
                                class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors z-10">
                            <x-ui.icon name="x" class="w-4 h-4"/>
                        </button>

                        <div class="p-5 border-b border-border">
                            <h3 class="font-bold text-foreground text-lg">به‌روزرسانی برنامه</h3>
                        </div>
                        <div class="p-5 space-y-2 text-sm text-muted">
                            <p>آیا از ثبت نهایی برنامه کلاسی مطمئن هستید؟</p>
                            <p class="text-primary">در آینده هم می‌توانید ویرایش کنید و دوباره ثبت نهایی بزنید.</p>
                        </div>
                        <div class="flex items-center gap-x-3 border-t border-border p-4 pb-safe">
                            <x-ui.button wire:click="closeFinalizeModal"
                                         wire:loading.attr="disabled" wire:target="closeFinalizeModal"
                                         variant="secondary-outline" icon="x" block>
                                انصراف
                            </x-ui.button>
                            <x-ui.button wire:click="finalizeSchedule"
                                         wire:loading.attr="disabled" wire:target="finalizeSchedule"
                                         variant="primary" icon="check" block>
                                بله، ثبت نهایی شود
                            </x-ui.button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- مودال انتخاب درس (Alpine instant-open + loading) --}}
        <div x-data="{
    partModalOpen: false,
    partLoading: false,
    openPart(day, part) {
        this.partModalOpen = true;
        this.partLoading = true; // بلافاصله لودینگ نمایش داده شود تا محتوای قدیمی/خالی برای یک لحظه دیده نشود
        const startedAt = Date.now();
        $wire.openPartModal(day, part).then(() => {
            // حداقل زمان نمایش لودینگ، فقط برای حس روان‌تر — حتی اگر پاسخ فوری برسد
            const remaining = Math.max(0, 300 - (Date.now() - startedAt));
            setTimeout(() => { this.partLoading = false }, remaining);
        });
    },
    closePart() {
        this.partModalOpen = false;
        $wire.closeModal();
    }
}"
             @open-part-modal.window="openPart($event.detail.day, $event.detail.part)"
             @close-part-modal.window="closePart()"
             x-effect="partModalOpen ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
             @keydown.escape.window="closePart()">
            <div x-show="partModalOpen" x-cloak>
                <div
                    x-show="partModalOpen"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm"
                    @click="closePart()"
                ></div>

                <div
                    x-show="partModalOpen"
                    class="fixed inset-0 z-[101] flex items-end justify-center overscroll-contain sm:items-center sm:p-4"
                    @click.self="closePart()"
                >
                    <div
                        x-show="partModalOpen"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                        class="relative w-full sm:max-w-md bg-background border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl flex flex-col max-h-[90vh] pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                        dir="rtl">

                        <div class="mx-auto mt-3 mb-1 h-1.5 w-14 rounded-full bg-border sm:hidden shrink-0"></div>

                        <button type="button" @click="closePart()" data-elevated="false"
                                class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors z-10">
                            <x-ui.icon name="x" class="w-4 h-4"/>
                        </button>

                        <div class="shrink-0 p-4 border-b border-border">
                            <h3 class="font-bold text-foreground text-base">انتخاب درس</h3>
                        </div>

                        <div class="flex-1 overflow-y-auto p-4">
                            {{-- Loading skeleton — فقط برای حس روان‌تر، مستقل از سرعت واقعی پاسخ --}}
                            <div x-show="partLoading" x-cloak class="space-y-2">
                                @for($i = 0; $i < 5; $i++)
                                    <div class="h-12 bg-muted/40 rounded-xl animate-pulse"></div>
                                @endfor
                            </div>

                    {{-- Content --}}
                    <div x-show="!partLoading" x-cloak>
                        @if(count($subjects) > 0)
                            @if($isEditingFilledPart)
                                {{-- ویرایش یک پارتِ از قبل پرشده: فقط یک درس --}}
                                <p class="text-sm text-muted mb-3">درس این پارت را انتخاب کنید:</p>
                            @else
                                @php $selectedCount = count($selectedSubjectIds); @endphp
                                {{-- افزودن پارت خالی: چند درس هم‌زمان قابل انتخاب است --}}
                                <div class="flex items-center justify-between mb-3 gap-2">
                                    <p class="text-sm text-muted">یک یا چند درس انتخاب کنید:</p>
                                    <span class="shrink-0 text-xs px-2.5 py-1 rounded-full font-bold
                        {{ $selectedCount > 0 ? 'bg-primary/10 text-primary' : 'bg-secondary text-muted' }}">
                        {{ $selectedCount }} از {{ $this->maxSelectableSubjects }}
                    </span>
                                </div>

                                @if($selectedCount >= $this->maxSelectableSubjects)
                                    <div class="mb-3 px-3 py-2 rounded-lg bg-warning/10 text-warning text-xs">
                                        به حداکثر تعداد قابل انتخاب رسیدید.
                                    </div>
                                @endif
                            @endif

                            <div class="space-y-2">
                                @foreach($subjects as $subject)
                                    @php
                                        $isSelected = in_array($subject->id, $selectedSubjectIds);
                                        $orderIndex = $isSelected ? array_search($subject->id, $selectedSubjectIds) : null;
                                        $assignedPart = (! $isEditingFilledPart && $isSelected) ? ($selectedPart + $orderIndex) : null;
                                    @endphp

                                    <button wire:click="toggleSubject({{ $subject->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="toggleSubject"
                                            class="w-full text-right px-3 py-3 rounded-xl border transition-all
                        {{ $isSelected
                            ? 'border-primary bg-primary/10 text-primary font-bold'
                            : 'border-border bg-background hover:border-primary/40 hover:bg-primary/5 text-foreground' }}">
                                        <div class="flex items-center justify-between gap-3">
                                            {{-- چپ‌چین: تیک/رادیو + نام --}}
                                            <div class="flex items-center gap-3 min-w-0 flex-1">
                            <span class="flex-shrink-0 w-5 h-5 {{ $isEditingFilledPart ? 'rounded-full' : 'rounded-md' }} border-2 flex items-center justify-center transition-colors
                                   {{ $isSelected
                                       ? 'bg-primary border-primary'
                                       : 'border-border bg-background' }}">
                                @if($isSelected)
                                    @if($isEditingFilledPart)
                                        <span class="w-2 h-2 rounded-full bg-white"></span>
                                    @else
                                        <x-ui.icon name="check" class="w-3.5 h-3.5 text-white"/>
                                    @endif
                                @endif
                            </span>
                                                <span class="truncate text-sm">{{ $subject->name }}</span>
                                            </div>

                                            {{-- راست‌چین: شماره پارت اختصاص‌یافته (در حالت چندانتخابی) + نوع --}}
                                            <div class="flex items-center gap-2 shrink-0">
                                                @if($assignedPart)
                                                    <span class="text-[10px] px-1.5 py-0.5 rounded-md bg-primary text-white font-bold">
                                    پارت {{ $assignedPart }}
                                </span>
                                                @endif
                                                <span class="text-[11px] {{ $subject->type === 'general' ? 'text-info' : 'text-warning' }}">
                                {{ $subject->type === 'general' ? 'عمومی' : 'تخصصی' }}
                            </span>
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <x-ui.icon name="triangle-alert" class="w-12 h-12 text-muted mx-auto mb-3"/>
                                <p class="text-muted text-sm">درسی یافت نشد. لطفاً اطلاعات شخصی (پایه و رشته) خود را تکمیل کنید.</p>
                            </div>
                        @endif
                            </div>
                        </div>

                        <div class="shrink-0 flex items-center gap-x-4 border-t border-border p-4 pb-safe">
                            <x-ui.button type="button" variant="secondary-outline" icon="x" block @click="closePart()">
                                انصراف
                            </x-ui.button>
                            <x-ui.button wire:click="savePart"
                                         wire:loading.attr="disabled" wire:target="savePart"
                                         :disabled="empty($selectedSubjectIds)"
                                         variant="primary" icon="check" block>
                                ذخیره @if(!$isEditingFilledPart && count($selectedSubjectIds) > 1) ({{ count($selectedSubjectIds) }} پارت) @endif
                            </x-ui.button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
