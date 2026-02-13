<div>
    <div>
        <div class="max-w-7xl space-y-14 px-4 mx-auto">
            <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

                <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                    <livewire:client.profile.sidebar/>
                </div>

                <div class="lg:col-span-9 md:col-span-8">
                    <div class="space-y-6">

                        <!-- section:title -->
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">افزودن برنامه کلاسی</div>

                            <a wire:navigate href="{{ route('client.profile.consultation.sessions') }}"
                               class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-primary rounded-full text-primary-foreground transition-colors hover:bg-foreground hover:text-background px-6 ms-auto">
                                <span class="font-semibold text-xs"> بازگشت به اتاق مشاوره</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </a>

                        </div>

                        <!-- اطلاعات پایه و رشته -->
                        <div dir="rtl" class="rounded-2xl border border-border bg-primary p-4 md:p-6">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-white font-bold text-lg">برنامه کلاسی</h2>
                                        <p class="text-white/70 text-sm">پایه
                                            @if($student?->personal_info)
                                                {{ $student->personal_info->grade == '10' ? 'دهم' : ($student->personal_info->grade == '11' ? 'یازدهم' : 'دوازدهم') }}
                                                -
                                                {{ $student->personal_info->field == 'math' ? 'ریاضی' : ($student->personal_info->field == 'experimental' ? 'تجربی' : 'انسانی') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                @if($isFinalized)
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-500/20 text-green-100 rounded-full text-sm font-semibold border border-green-400/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    نهایی شده
                                </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-500/20 text-amber-100 rounded-full text-sm font-semibold border border-amber-400/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    در حال تکمیل
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- راهنما -->
                        <div dir="rtl" class="rounded-2xl border border-border bg-secondary p-4">
                            <div class="flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-sm text-muted leading-relaxed space-y-1">
                                    <p>- از شنبه تا چهارشنبه <strong class="text-foreground">حداقل 3 پارت</strong> باید ثبت شود.</p>
                                    <p>- پنجشنبه و جمعه اختیاری است.</p>
                                    <p>- هر روز حداکثر 5 پارت قابل ثبت است.</p>
                                    <p>- پارت‌ها باید به ترتیب پر شوند.</p>
                                    <p>- پس از ثبت نهایی، امکان تغییر وجود نخواهد داشت.</p>
                                </div>
                            </div>
                        </div>

                        <!-- جدول برنامه کلاسی -->
                        <div dir="rtl" class="space-y-4">
                            @foreach($days as $day)
                                <div class="rounded-2xl border border-border bg-secondary overflow-hidden">
                                    <!-- هدر روز -->
                                    <div class="flex items-center justify-between px-4 py-3 {{ $day['is_mandatory'] ? 'bg-primary/5' : 'bg-secondary' }} border-b border-border">
                                        <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full {{ $day['is_complete'] ? 'bg-green-100 dark:bg-green-900/30 text-green-600' : ($day['is_mandatory'] ? 'bg-primary/10 text-primary' : 'bg-secondary text-muted') }} font-bold text-sm">

                                               <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M8 3v2m8-2v2M4 8h16M6 5h12a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z"/>
                                                    <text x="12" y="17" text-anchor="middle" font-size="9.5" font-weight="800"
                                                          fill="currentColor" stroke="none"
                                                          font-family="ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial">{{ $day['day_of_week'] + 1 }}</text>
                                                </svg>
                                        </span>
                                            <div>
                                                <span class="font-bold text-foreground">{{ $day['name'] }}</span>
                                                @if($day['is_mandatory'])
                                                    <span class="text-xs text-red-500 mr-2">(اجباری)</span>
                                                @else
                                                    <span class="text-xs text-muted mr-2">(اختیاری)</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-muted">{{ $day['filled_count'] }} / {{ \App\Models\ClassSchedule::MAX_PARTS_PER_DAY }}</span>
                                            @if($day['is_complete'] && $day['is_mandatory'])
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- پارت‌ها -->
                                    <div class="p-4">
                                        <div class="-mx-4 px-4 overflow-x-auto md:overflow-x-visible">
                                            <div class="flex gap-3 md:grid md:grid-cols-5 md:gap-3 min-w-max md:min-w-0">

                                            @foreach($day['parts'] as $partInfo)
                                                    <div class="w-44 md:w-auto flex-shrink-0 md:flex-shrink">
                                                @if($partInfo['is_filled'])
                                                    <!-- پارت پر شده -->
                                                    <div class="relative group">
                                                        <button
                                                            @if(!$isFinalized)
                                                                wire:click="openPartModal({{ $day['day_of_week'] }}, {{ $partInfo['order'] }})"
                                                            @endif
                                                            class="w-full rounded-xl border-2 border-green-300 dark:border-green-700 bg-green-50 dark:bg-green-900/20 p-3 text-center transition-all {{ !$isFinalized ? 'hover:border-green-400 hover:shadow-md cursor-pointer' : '' }}">
                                                            <div class="text-xs text-muted mb-1">پارت {{ $partInfo['order'] }}</div>
                                                            <div class="font-bold text-sm text-foreground truncate">{{ $partInfo['part']->lesson_name }}</div>
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500 mx-auto mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        </button>
                                                        @if(!$isFinalized)
                                                            <button wire:click="deletePart({{ $day['day_of_week'] }}, {{ $partInfo['order'] }})"
                                                                    wire:confirm="آیا مطمئنید؟ پارت‌های بعدی هم حذف خواهند شد."
                                                                    class="absolute -top-2 -left-2 w-6 h-6 bg-red-500 text-white rounded-full items-center justify-center text-xs hidden group-hover:flex shadow-lg">
                                                                &times;
                                                            </button>
                                                        @endif
                                                    </div>
                                                @elseif($partInfo['is_unlocked'])
                                                    <!-- پارت باز (قابل پر شدن) -->
                                                    <button wire:click="openPartModal({{ $day['day_of_week'] }}, {{ $partInfo['order'] }})"
                                                            class="w-full rounded-xl border-2 border-dashed border-primary/40 bg-primary/5 p-3 text-center transition-all hover:border-primary hover:bg-primary/10 hover:shadow-md cursor-pointer">
                                                        <div class="text-xs text-muted mb-1">پارت {{ $partInfo['order'] }}</div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                        </svg>
                                                        <div class="text-xs text-primary mt-1 font-semibold">افزودن</div>
                                                    </button>
                                                @else
                                                    <!-- پارت قفل شده -->
                                                    <div class="w-full rounded-xl border border-border bg-background/50 p-3 text-center opacity-40">
                                                        <div class="text-xs text-muted mb-1">پارت {{ $partInfo['order'] }}</div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-muted mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                        </svg>
                                                        <div class="text-xs text-muted mt-1">قفل</div>
                                                    </div>
                                                @endif
                                                    </div>
                                            @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- دکمه ثبت نهایی -->
                        @if(!$isFinalized)
                            <div dir="rtl" class="flex items-center justify-between gap-4">
                                <a href="{{ route('client.profile.consultation.sessions') }}">

                                </a>

                                <button wire:click="finalizeSchedule"
                                        @if(!$canFinalize) disabled @endif
                                        class="inline-flex items-center gap-2 px-8 py-3 rounded-xl font-bold text-sm transition-colors
                                        {{ $canFinalize
                                            ? 'bg-green-500 hover:bg-green-600 text-white shadow-lg shadow-green-500/30'
                                            : 'bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    ثبت نهایی برنامه کلاسی
                                </button>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <!-- مودال انتخاب درس -->
        @if($showModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" x-transition.opacity>
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="relative w-full max-w-md my-20 overflow-hidden transition-all transform bg-background border border-border rounded-2xl shadow-2xl z-20">

                        <!-- هدر مودال -->
                        <div class="p-4 border-b border-border flex items-center justify-between">
                            <h3 class="font-bold text-foreground text-lg">
                                انتخاب درس - {{ \App\Models\ClassSchedule::getDayName($selectedDay) }} (پارت {{ $selectedPart }})
                            </h3>
                            <button wire:click="closeModal" class="text-muted hover:text-error transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- بدنه مودال -->
                        <div class="p-6">
                            @if(count($subjects) > 0)
                                <p class="text-sm text-muted mb-4">یک درس را انتخاب کنید:</p>
                                <div class="space-y-2 max-h-80 overflow-y-auto">
                                    @foreach($subjects as $subject)
                                        <button wire:click="$set('selectedSubjectId', {{ $subject->id }})"
                                                class="w-full text-right px-4 py-3 rounded-xl border transition-all
                                                {{ $selectedSubjectId == $subject->id
                                                    ? 'border-primary bg-primary/10 text-primary font-bold'
                                                    : 'border-border bg-secondary hover:border-primary/40 hover:bg-primary/5 text-foreground' }}">
                                            <div class="flex items-center justify-between">
                                                <span>{{ $subject->name }}</span>
                                                <span class="text-xs {{ $subject->type === 'general' ? 'text-blue-500' : 'text-orange-500' }}">
                                                {{ $subject->type === 'general' ? 'عمومی' : 'تخصصی' }}
                                            </span>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-muted mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <p class="text-muted">درسی یافت نشد. لطفاً اطلاعات شخصی (پایه و رشته) خود را تکمیل کنید.</p>
                                </div>
                            @endif
                        </div>

                        <!-- فوتر مودال -->
                        <div class="flex items-center gap-x-4 border-t border-border p-4">
                            <button wire:click="closeModal"
                                    class="flex items-center justify-center gap-x-2 w-full bg-background border border-border rounded-xl text-foreground py-3 px-4 hover:bg-secondary transition-colors">
                                <span class="font-bold text-sm">انصراف</span>
                            </button>
                            <button wire:click="savePart"
                                    @if(!$selectedSubjectId) disabled @endif
                                    class="flex items-center justify-center gap-x-2 w-full rounded-xl py-3 px-4 transition-colors
                                    {{ $selectedSubjectId
                                        ? 'bg-primary hover:bg-primary/90 text-primary-foreground'
                                        : 'bg-gray-300 dark:bg-gray-700 text-gray-500 cursor-not-allowed' }}">
                                <span class="font-bold text-sm">ذخیره</span>
                            </button>
                        </div>
                    </div>

                    <div wire:click="closeModal"
                         class="fixed inset-0 bg-secondary/80 cursor-pointer transition-all z-10"></div>
                </div>
            </div>
        @endif
    </div>

</div>
