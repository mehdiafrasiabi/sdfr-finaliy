<div class="max-w-7xl space-y-8 px-4 mx-auto" wire:poll.30000ms="checkStatus">

    <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

        {{-- Sidebar --}}
        <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
            <livewire:client.profile.sidebar/>
        </div>

        {{-- محتوای اصلی --}}
        <div class="lg:col-span-9 md:col-span-8">
            <div class="space-y-6">

                {{-- عنوان --}}
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1">
                        <div class="w-1 h-1 bg-foreground rounded-full"></div>
                        <div class="w-2 h-2 bg-foreground rounded-full"></div>
                    </div>
                    <div class="font-black text-foreground text-lg">هفته آزمایشی</div>
                </div>

                {{-- کارت انتظار --}}
                <div class="bg-secondary border border-border rounded-2xl p-8 text-center space-y-6">

                    {{-- آیکون انیمیشن --}}
                    <div class="flex justify-center">
                        <div class="relative w-24 h-24">
                            {{-- دایره‌های پالسینگ --}}
                            <span class="absolute inset-0 rounded-full bg-amber-400/20 animate-ping"></span>
                            <span class="absolute inset-2 rounded-full bg-amber-400/30 animate-ping [animation-delay:0.3s]"></span>
                            <div class="relative flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- متن اصلی --}}
                    <div class="space-y-3">
                        <h2 class="text-2xl font-black text-foreground">در انتظار تخصیص پشتیبان</h2>
                        <p class="text-muted text-base max-w-md mx-auto leading-relaxed">
                            ثبت‌نام شما با موفقیت انجام شد. تیم ما در حال بررسی اطلاعات و تخصیص پشتیبان مناسب برای شما هستند.
                        </p>
                    </div>

                    {{-- مراحل انتظار --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                        <div class="flex flex-col items-center gap-2 p-4 rounded-xl bg-background border border-border">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-foreground">ثبت‌نام</span>
                            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">انجام شد</span>
                        </div>

                        <div class="flex flex-col items-center gap-2 p-4 rounded-xl bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800">
                            <div class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600 dark:text-amber-400 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-amber-700 dark:text-amber-300">تخصیص پشتیبان</span>
                            <span class="text-xs text-amber-600 dark:text-amber-400 font-medium">در انتظار</span>
                        </div>

                        <div class="flex flex-col items-center gap-2 p-4 rounded-xl bg-background border border-border opacity-50">
                            <div class="w-10 h-10 rounded-full bg-border flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-muted">شروع هفته آزمایشی</span>
                            <span class="text-xs text-muted font-medium">قفل شده</span>
                        </div>
                    </div>

                    {{-- اطلاعات ثبت‌شده --}}
                    @if($trialWeek)
                    <div class="bg-background border border-border rounded-xl p-4 text-right space-y-3">
                        <h3 class="font-bold text-foreground text-sm">اطلاعات ثبت‌شده شما</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <div class="space-y-1">
                                <span class="text-xs text-muted">پایه تحصیلی</span>
                                <p class="text-sm font-bold text-foreground">{{ $trialWeek->gradeLabel }}</p>
                            </div>
                            @if($trialWeek->grade != 9)
                            <div class="space-y-1">
                                <span class="text-xs text-muted">رشته</span>
                                <p class="text-sm font-bold text-foreground">{{ $trialWeek->fieldLabel }}</p>
                            </div>
                            @endif
                            <div class="space-y-1">
                                <span class="text-xs text-muted">وضعیت</span>
                                <p class="text-sm font-bold text-amber-600 dark:text-amber-400">{{ $trialWeek->statusLabel }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- توضیحات --}}
                    <div class="text-xs text-muted space-y-1">
                        <p>این صفحه هر ۳۰ ثانیه به‌روزرسانی می‌شود.</p>
                        <p>پس از تخصیص پشتیبان، به‌صورت خودکار به صفحه هفته آزمایشی منتقل خواهید شد.</p>
                    </div>

                </div>

                {{-- اطلاعیه تماس --}}
                <div class="bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-800 rounded-xl p-4 flex gap-3 items-start">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <p class="text-sm font-bold text-blue-800 dark:text-blue-200">پشتیبان به‌زودی با شما تماس می‌گیرد</p>
                        <p class="text-xs text-blue-600 dark:text-blue-400 leading-relaxed">
                            یک پشتیبان اختصاصی از تیم ما در اسرع وقت با شماره‌ای که ثبت کرده‌اید تماس خواهد گرفت و هفته آزمایشی شما را آغاز می‌کند.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
