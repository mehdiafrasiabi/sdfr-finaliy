<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-10">
                    <div class="space-y-5">

                        <!-- section:title -->
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">برنامه ها</div>
                        </div>

                        <!-- tabs container -->
                        <div class="space-y-5" x-data="{ activeTab: 'tabOne'}" wire:poll.visible>

                            <div>
                                <div x-show="activeTab === 'tabOne'">
                                    @if($weeklyPrograms->count() > 0)
                                        <div class="mt-6 space-y-4">
                                            @foreach($weeklyPrograms as $program)
                                                <div x-data="{ expanded: false }"
                                                     class="glass border border-border rounded-2xl overflow-hidden flex flex-col">

                                                    {{-- ═══════════════════════════════════
                                                         موبایل: تصویر بالا، اطلاعات وسط، دکمه‌ها پایین
                                                    ════════════════════════════════════ --}}
                                                    <div class="md:hidden">

                                                        {{-- تصویر بالا --}}
                                                        <div class="w-full h-36 flex items-center justify-center bg-gradient-to-b from-blue-100 to-blue-200 dark:from-blue-950 dark:to-blue-900">
                                                            <img src="/client/icons/plan.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">
                                                        </div>

                                                        {{-- اطلاعات --}}
                                                        <div class="p-4 space-y-3" dir="rtl">
                                                            <h3 class="font-bold text-foreground text-base">
                                                                برنامه هفته {{ jdate($program->start_date)->format('d %B') }}
                                                            </h3>

                                                            <p class="text-sm text-muted">
                                                                تا {{ jdate($program->end_date)->format('d %B Y') }}
                                                            </p>


                                                        </div>

                                                        {{-- دکمه‌های موبایل --}}
                                                        <div class="px-4 pb-4 space-y-2" dir="rtl">
                                                            <a wire:navigate href="{{ route('client.profile.consultation.weekly-program', $program->id) }}"
                                                               class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                                                مشاهده برنامه
                                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" strokeWidth="2" class="w-4 h-4"><path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                                            </a>

                                                            <button @click="expanded = !expanded"
                                                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                                <span>مشاهده جزئیات</span>
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                     class="w-4 h-4 transition-transform duration-200"
                                                                     :class="{ 'rotate-180': expanded }"
                                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    {{-- ═══════════════════════════════════
                                                         دسکتاپ: تصویر سمت چپ، اطلاعات + دکمه‌ها وسط‌چین عمودی
                                                    ════════════════════════════════════ --}}
                                                    <div class="hidden md:flex flex-row min-h-[130px]">

                                                        {{-- ستون تصویر --}}
                                                        <div class="flex-shrink-0 w-[120px] flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-[#1e3a5f] dark:to-[#1e40af]">
                                                            <img src="/client/icons/plan.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">
                                                        </div>

                                                        {{-- محتوا: items-center برای وسط‌چینی عمودی دکمه‌ها --}}
                                                        <div class="flex-1 p-4 flex items-center justify-between gap-4" dir="rtl">

                                                            {{-- راست: عنوان + تاریخ + بج‌ها --}}
                                                            <div class="space-y-2 flex-1 min-w-0">
                                                                <h3 class="font-bold text-foreground text-base">
                                                                    برنامه هفته {{ jdate($program->start_date)->format('d %B') }}
                                                                </h3>

                                                                <p class="text-sm text-muted">
                                                                    تا {{ jdate($program->end_date)->format('d %B Y') }}
                                                                </p>

                                                            </div>

                                                            {{-- چپ: دکمه‌ها --}}
                                                            <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                                                                <a wire:navigate href="{{ route('client.profile.consultation.weekly-program', $program->id) }}"
                                                                   class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                                    </svg>
                                                                </a>

                                                                <button @click="expanded = !expanded"
                                                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                         class="w-4 h-4 transition-transform duration-200"
                                                                         :class="{ 'rotate-180': expanded }"
                                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- ═══ جزئیات (مشترک موبایل و دسکتاپ) ═══ --}}
                                                    <div x-show="expanded"
                                                         x-cloak
                                                         x-transition:enter="transition ease-out duration-200"
                                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                                         x-transition:enter-end="opacity-100 translate-y-0"
                                                         x-transition:leave="transition ease-in duration-150"
                                                         x-transition:leave-start="opacity-100 translate-y-0"
                                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                                         class="border-border bg-background/50 p-4"
                                                         style="display: none;">
                                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                                <span class="text-xs text-muted">تاریخ شروع</span>
                                                                <span class="font-bold text-foreground text-sm mt-1">{{ jdate($program->start_date)->format('d %B') }}</span>
                                                            </div>

                                                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                                <span class="text-xs text-muted">تاریخ پایان</span>
                                                                <span class="font-bold text-foreground text-sm mt-1">{{ jdate($program->end_date)->format('d %B') }}</span>
                                                            </div>

                                                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                <span class="text-xs text-muted">ساعت کل برنامه</span>
                                                                <span class="font-bold text-foreground text-sm mt-1">{{ $program->total_hours }} ساعت</span>
                                                            </div>

                                                            <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-2 text-fuchsia-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                                </svg>
                                                                <span class="text-xs text-muted">تعداد تست</span>
                                                                <span class="font-bold text-foreground text-sm mt-1">{{ $program->total_tests }} تست</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <!-- حالت خالی -->
                                        <div class="flex flex-col items-center justify-center py-12 space-y-4">
                                            <img src="/client/svg/empty2.svg"
                                                 class="w-full max-w-[370px] md:max-w-xs opacity-35 mb-4 md:mb-6"
                                                 alt="پیامی وجود ندارد"/>
                                            <div class="text-center space-y-2">
                                                <h2 class="font-bold text-xl text-foreground">برنامه‌ای وجود ندارد!</h2>
                                                <p class="text-muted text-sm">هنوز برنامه‌ای برای شما ثبت نشده است.</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- پیجینیشن -->
                                    @if($weeklyPrograms->hasPages())
                                        <div class="mt-6 flex justify-center">
                                            <div class="inline-flex items-center gap-1 p-1 rounded-xl bg-slate-100 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/50">
                                                {{ $weeklyPrograms->links('layouts.client.pagination') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
