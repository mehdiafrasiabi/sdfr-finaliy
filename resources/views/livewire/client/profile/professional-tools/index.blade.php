<div>
    <div class="max-w-7xl space-y-6 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-6">

                    {{-- Section Title --}}
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">آچار فرانسه</div>
                    </div>

                    {{-- Guide Section --}}
                    <div
                        dir="rtl"
                        x-data="collapseGuide('report-guide')"
                        x-init="init()"
                        class="rounded-2xl border border-border bg-primary overflow-hidden transition-all">

                        <button @click="toggle" class="w-full flex items-center justify-between px-4 md:px-6 py-4 transition">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 14h-2v-2h2v2zm0-4h-2V6h2v6z"/>
                                </svg>
                                <span class="font-black text-white md:text-lg">راهنمای ابزارها</span>
                            </div>
                            <svg class="w-5 h-5 text-white transition-transform duration-300"
                                 :class="open && 'rotate-180'"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            x-cloak
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="px-4 md:px-6 pb-6">

                            <div class="flex flex-col md:flex-row-reverse gap-6 items-center mt-2">
                                <div class="relative w-full md:w-[280px] shrink-0 order-2 md:order-1">
                                    <img src="/client/assets/images/blog/sdfr.jpg"
                                         class="w-full h-[200px] md:h-[180px] object-cover rounded-xl">
                                    <button
                                        type="button"
                                        id="57612318744"
                                        data-video-url="https://www.aparat.com/video/video/embed/videohash/utg98i1/vt/frame?titleShow=true&recom=self"
                                        allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"
                                        data-video-title="راهنمای ابزارها"
                                        class="absolute inset-0 flex items-center justify-center">
                                        <span class="w-14 h-14 rounded-full bg-white/90 dark:bg-black/60 flex items-center justify-center shadow-lg transition hover:scale-110">
                                            <svg class="w-7 h-7 text-blue-600 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </span>
                                    </button>
                                </div>

                                <div class="flex-1 text-right text-sm md:text-base text-white leading-7 order-1 md:order-2">
                                    از ابزارهای حرفه‌ای برای بهبود عملکرد مطالعاتی خود استفاده کنید:
                                    <br>• پومودورو: مدیریت زمان مطالعه با تکنیک 25 دقیقه‌ای
                                    <br>• تست سرعتی: تمرین و افزایش سرعت پاسخگویی
                                    <br>• کلاسور تحلیل: تحلیل دقیق نقاط قوت و ضعف
                                    <br>• دفتر خلاصه‌ها: یادداشت و مرور مطالب مهم
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- End Guide --}}

                    {{-- Tools Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Card 1: Pomodoro (active) --}}
                        <div class="group relative overflow-hidden cursor-pointer
                                    bg-background bg-secondary border-2 border-blue-500/50 rounded-2xl p-5 sm:p-6
                                    shadow-[0_8px_24px_rgba(59,130,246,0.15)] dark:shadow-[0_8px_24px_rgba(59,130,246,0.1)]
                                    transition-all duration-300 ease-out
                                    hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.12)] dark:hover:shadow-[0_20px_40px_rgba(0,0,0,0.3)]
                                    animate-[slideInUp_0.6s_ease-out_0.1s_backwards]">
                            {{-- hover gradient overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-600/5 opacity-100 pointer-events-none rounded-2xl"></div>

                            <div class="relative z-10 flex items-start gap-4">
                                {{-- Number --}}
                                <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center font-black text-lg text-primary
                                            bg-gradient-to-br from-blue-500/15 to-purple-600/15
                                            transition-all duration-300 group-hover:from-blue-500/25 group-hover:to-purple-600/25 group-hover:scale-110">
                                    01
                                </div>

                                <div class="flex-1 min-w-0 space-y-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center
                                                    transition-all duration-300 group-hover:scale-110 group-hover:rotate-3">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-foreground text-base sm:text-lg">تایمر پومودورو</h3>
                                            <p class="text-xs text-muted">مدیریت زمان مطالعه</p>
                                        </div>
                                    </div>

                                    <p class="text-sm text-muted leading-relaxed">
                                        با تکنیک پومودورو 25 دقیقه تمرکز کامل داشته باشید و بعد از هر جلسه 5 دقیقه استراحت کنید.
                                    </p>

                                    <a wire:navigate href="{{ route('client.profile.professionalTools.pomodoro') }}"
                                       class="relative overflow-hidden inline-flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-2.5
                                              bg-primary hover:bg-primary/90 text-white rounded-xl font-semibold text-sm
                                              transition-all duration-300 active:scale-95">
                                        <span>شروع</span>

                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Card 2: Speed Test (disabled) --}}
                        <div class="group relative overflow-hidden cursor-not-allowed opacity-70
                                    bg-background bg-secondary border-2 border-border rounded-2xl p-5 sm:p-6
                                    transition-all duration-300 ease-out
                                    hover:-translate-y-0.5 hover:shadow-[0_4px_12px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_4px_12px_rgba(0,0,0,0.2)]
                                    animate-[slideInUp_0.6s_ease-out_0.2s_backwards]">
                            <div class="absolute inset-0 bg-gradient-to-br from-gray-500/5 to-gray-600/5 opacity-100 pointer-events-none rounded-2xl"></div>

                            <div class="relative z-10 flex items-start gap-4">
                                <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center font-black text-lg text-muted
                                            bg-gradient-to-br from-gray-500/10 to-gray-600/10
                                            transition-all duration-300 group-hover:scale-105">
                                    02
                                </div>

                                <div class="flex-1 min-w-0 space-y-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center
                                                    transition-all duration-300 group-hover:scale-105">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.38 8.57l-1.23 1.85a8 8 0 01-.22 7.58H5.07A8 8 0 0115.58 6.85l1.85-1.23A10 10 0 003.35 19a2 2 0 001.72 1h13.85a2 2 0 001.74-1 10 10 0 00-.27-10.44zm-9.79 6.84a2 2 0 002.83 0l5.66-8.49-8.49 5.66a2 2 0 000 2.83z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="font-bold text-foreground text-base sm:text-lg">تست سرعتی</h3>
                                                <span class="animate-pulse inline-flex items-center px-2 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-[10px] font-bold rounded-full">
                                                    بزودی
                                                </span>
                                            </div>
                                            <p class="text-xs text-muted">افزایش سرعت پاسخگویی</p>
                                        </div>
                                    </div>

                                    <p class="text-sm text-muted leading-relaxed">
                                        با تمرین‌های سرعتی مهارت پاسخگویی سریع و دقیق خود را در آزمون‌ها تقویت کنید.
                                    </p>

                                    <button disabled class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-2.5 bg-muted/20 text-muted rounded-xl font-semibold text-sm cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z"/>
                                        </svg>
                                        <span>در حال توسعه</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Card 3: Analysis Folder (disabled) --}}
                        <div class="group relative overflow-hidden cursor-not-allowed opacity-70
                                    bg-background bg-secondary border-2 border-border rounded-2xl p-5 sm:p-6
                                    transition-all duration-300 ease-out
                                    hover:-translate-y-0.5 hover:shadow-[0_4px_12px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_4px_12px_rgba(0,0,0,0.2)]
                                    animate-[slideInUp_0.6s_ease-out_0.3s_backwards]">
                            <div class="absolute inset-0 bg-gradient-to-br from-gray-500/5 to-gray-600/5 opacity-100 pointer-events-none rounded-2xl"></div>

                            <div class="relative z-10 flex items-start gap-4">
                                <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center font-black text-lg text-muted
                                            bg-gradient-to-br from-gray-500/10 to-gray-600/10
                                            transition-all duration-300 group-hover:scale-105">
                                    03
                                </div>

                                <div class="flex-1 min-w-0 space-y-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center
                                                    transition-all duration-300 group-hover:scale-105">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="font-bold text-foreground text-base sm:text-lg">کلاسور تحلیل</h3>
                                                <span class="animate-pulse inline-flex items-center px-2 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-[10px] font-bold rounded-full">
                                                    بزودی
                                                </span>
                                            </div>
                                            <p class="text-xs text-muted">تحلیل نقاط قوت و ضعف</p>
                                        </div>
                                    </div>

                                    <p class="text-sm text-muted leading-relaxed">
                                        تحلیل دقیق تست های زده شده خود را در این بخش ثبت می کنید تا در بازه های جمع بندی بهترین منبع مروری از نقاط قوت و ضعف تان داشته باشید.
                                    </p>

                                    <button disabled class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-2.5 bg-muted/20 text-muted rounded-xl font-semibold text-sm cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z"/>
                                        </svg>
                                        <span>در حال توسعه</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Card 4: Summary Notebook (disabled) --}}
                        <div class="group relative overflow-hidden cursor-not-allowed opacity-70
                                    bg-background bg-secondary border-2 border-border rounded-2xl p-5 sm:p-6
                                    transition-all duration-300 ease-out
                                    hover:-translate-y-0.5 hover:shadow-[0_4px_12px_rgba(0,0,0,0.08)] dark:hover:shadow-[0_4px_12px_rgba(0,0,0,0.2)]
                                    animate-[slideInUp_0.6s_ease-out_0.4s_backwards]">
                            <div class="absolute inset-0 bg-gradient-to-br from-gray-500/5 to-gray-600/5 opacity-100 pointer-events-none rounded-2xl"></div>

                            <div class="relative z-10 flex items-start gap-4">
                                <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center font-black text-lg text-muted
                                            bg-gradient-to-br from-gray-500/10 to-gray-600/10
                                            transition-all duration-300 group-hover:scale-105">
                                    04
                                </div>

                                <div class="flex-1 min-w-0 space-y-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center
                                                    transition-all duration-300 group-hover:scale-105">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="font-bold text-foreground text-base sm:text-lg">دفتر خلاصه‌ها</h3>
                                                <span class="animate-pulse inline-flex items-center px-2 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-[10px] font-bold rounded-full">
                                                    بزودی
                                                </span>
                                            </div>
                                            <p class="text-xs text-muted">یادداشت و مرور مطالب</p>
                                        </div>
                                    </div>

                                    <p class="text-sm text-muted leading-relaxed">
                                        نکات مهم و خلاصه‌های درسی خود را یادداشت کنید و هر زمان که خواستید مرور کنید.
                                    </p>

                                    <button disabled class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-2.5 bg-muted/20 text-muted rounded-xl font-semibold text-sm cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z"/>
                                        </svg>
                                        <span>در حال توسعه</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- End Tools Grid --}}

                </div>
            </div>
        </div>
    </div>
</div>
