<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <!-- user:menus -->
                <livewire:client.profile.sidebar/>
                <!-- end user:menus -->
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
                        <!-- end section:title -->

                        <!-- Guide Section -->
                        <div
                            dir="rtl"
                            x-data="collapseGuide('plan-guide')"
                            x-init="init()"
                            class="rounded-2xl border border-border bg-primary  overflow-hidden transition-all">

                            <!-- HEADER -->
                            <button
                                @click="toggle"
                                class="w-full flex items-center justify-between px-4 md:px-6 py-4
                                 transition">

                                <!-- title -->
                                <div class="flex items-center gap-2">

                                    <svg class="w-5 h-5 text-white dark:text-white"
                                         fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 14h-2v-2h2v2zm0-4h-2V6h2v6z"/>
                                    </svg>

                                    <span class="font-black text-white dark:text-white text-blue-300 md:text-lg">
                راهنمای برنامه های مشاوره ای
            </span>
                                </div>

                                <!-- arrow -->
                                <svg
                                    class="w-5 h-5 text-white transition-transform duration-300"
                                    :class="open && 'rotate-180'"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- CONTENT -->
                            <div
                                x-show="open"
                                x-cloak
                                x-transition:enter="transition ease-out duration-600"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-1"
                                class="px-4 md:px-6 pb-6"
                            >


                                <div class="flex flex-col md:flex-row-reverse gap-6 items-center mt-2">

                                    <!-- TEXT -->
                                    <div
                                        class="flex-1 text-right text-sm md:text-base  text-white dark:text-white leading-7 order-1 md:order-2">

                                        شما در این بخش میتوانید برنامه های خود را بطور کلی مشاهده کنید

                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- End Guide Section -->

                        <!-- tabs container -->
                        <div class="space-y-5" x-data="{ activeTab: 'tabOne'}" wire:poll.visible>
                            <!-- tabs:list-container -->
                            <div class="relative overflow-x-auto">
                                <!-- tabs:list -->
                                <ul class="inline-flex gap-2 bg-secondary border border-border rounded-full p-1">
                                    <li>
                                        <button type="button"
                                                class="flex items-center gap-x-2 relative rounded-full py-2 px-4"
                                                x-bind:class="activeTab === 'tabOne' ? 'text-foreground bg-background' : 'text-muted'"
                                                x-on:click="activeTab = 'tabOne'">
                                            <span x-show="activeTab === 'tabOne'">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                     fill="currentColor" class="w-5 h-5">
                                                    <path
                                                        d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z"></path>
                                                    <path
                                                        d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z"></path>
                                                    <path
                                                        d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z"></path>
                                                </svg>
                                            </span>
                                            <span x-show="activeTab !== 'tabOne'">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                     viewBox="0 0 24 24" stroke-width="1.5"
                                                     stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5">
                                                    </path>
                                                </svg>
                                            </span>
                                            <span class="font-semibold text-sm">برنامه ها</span>
                                        </button>
                                    </li>
                                </ul>
                                <!-- end tabs:list -->
                            </div>
                            <!-- end tabs:list-container -->

                            <!-- tabs:contents -->
                            <div>
                                <!-- tabs:contents:tabOne -->
                                <div x-show="activeTab === 'tabOne'">
                                    @if($weeklyPrograms->count() > 0)
                                        <div class="mt-6">
                                            <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2">
                                                @foreach($weeklyPrograms as $program)
                                                    <a href="{{ route('client.profile.consultation.weekly-program', $program->id) }}"
                                                       class="group relative block overflow-hidden rounded-2xl
                                                              bg-gradient-to-br from-slate-50 to-slate-100
                                                              dark:from-slate-800/50 dark:to-slate-900/80
                                                              border border-slate-200/60 dark:border-slate-700/50
                                                              p-5 transition-all duration-300 ease-out
                                                              hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-slate-900/50
                                                              hover:-translate-y-1 hover:border-teal-500/30 dark:hover:border-teal-400/30">

                                                        <!-- گرادیان تزئینی بالای کارت -->
                                                        <div class="absolute top-0 right-0 left-0 h-1 bg-gradient-to-r
                                                                    from-teal-500 via-cyan-500 to-blue-500
                                                                    opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                                        <!-- آیکون تزئینی گوشه -->
                                                        <div class="absolute -top-6 -left-6 w-20 h-20
                                                                    bg-gradient-to-br from-teal-500/10 to-cyan-500/10
                                                                    dark:from-teal-500/5 dark:to-cyan-500/5
                                                                    rounded-full blur-2xl
                                                                    group-hover:scale-150 transition-transform duration-500"></div>

                                                        <div class="relative flex items-start justify-between gap-4">
                                                            <div class="flex-1 min-w-0">
                                                                <!-- هدر کارت -->
                                                                <div class="flex items-center gap-2.5 mb-3">
                                                                    <div class="flex-shrink-0 w-10 h-10 rounded-xl
                                                                                bg-gradient-to-br from-teal-500 to-cyan-600
                                                                                flex items-center justify-center shadow-lg shadow-teal-500/25">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                             viewBox="0 0 20 20"
                                                                             fill="currentColor"
                                                                             class="w-5 h-5 text-white">
                                                                            <path fill-rule="evenodd"
                                                                                  d="M5.75 2a.75.75 0 0 1 .75.75V4h7V2.75a.75.75 0 0 1 1.5 0V4h.25A2.75 2.75 0 0 1 18 6.75v8.5A2.75 2.75 0 0 1 15.25 18H4.75A2.75 2.75 0 0 1 2 15.25v-8.5A2.75 2.75 0 0 1 4.75 4H5V2.75A.75.75 0 0 1 5.75 2Zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75Z"
                                                                                  clip-rule="evenodd"/>
                                                                        </svg>
                                                                    </div>
                                                                    <div class="min-w-0">
                                                                        <h3 class="font-bold text-slate-800 dark:text-slate-100
                                                                                   text-base leading-tight truncate">
                                                                            برنامه
                                                                            هفته {{ jdate($program->start_date)->format('d %B') }}
                                                                        </h3>
                                                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                                                            تا {{ jdate($program->end_date)->format('d %B Y') }}
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                                <!-- آمار برنامه -->
                                                                <div class="flex flex-wrap gap-2 mt-4">
                                                                    <!-- ساعت -->
                                                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                                                                                bg-blue-50 dark:bg-blue-500/10
                                                                                text-blue-600 dark:text-blue-400
                                                                                border border-blue-100 dark:border-blue-500/20">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                             viewBox="0 0 16 16"
                                                                             fill="currentColor" class="w-3.5 h-3.5">
                                                                            <path fill-rule="evenodd"
                                                                                  d="M1 8a7 7 0 1 1 14 0A7 7 0 0 1 1 8Zm7.75-4.25a.75.75 0 0 0-1.5 0V8c0 .414.336.75.75.75h3.25a.75.75 0 0 0 0-1.5H8.75V3.75Z"
                                                                                  clip-rule="evenodd"/>
                                                                        </svg>
                                                                        <span class="font-semibold text-xs">{{ $program->total_hours }} ساعت</span>
                                                                    </div>

                                                                    <!-- پارت -->
                                                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                                                                                bg-emerald-50 dark:bg-emerald-500/10
                                                                                text-emerald-600 dark:text-emerald-400
                                                                                border border-emerald-100 dark:border-emerald-500/20">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                             viewBox="0 0 16 16"
                                                                             fill="currentColor" class="w-3.5 h-3.5">
                                                                            <path
                                                                                d="M8.5 4.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0ZM10.9 12.006c.11.542-.348.994-.9.994H2c-.553 0-1.01-.452-.902-.994a5.002 5.002 0 0 1 9.803 0ZM14.002 12h-1.59a2.556 2.556 0 0 0-.04-.29 6.476 6.476 0 0 0-1.167-2.603 3.002 3.002 0 0 1 3.633 1.911c.18.522-.283.982-.836.982ZM12 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                                                                        </svg>
                                                                        <span class="font-semibold text-xs">{{ $program->total_parts }} پارت</span>
                                                                    </div>

                                                                    <!-- تست -->
                                                                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg
                                                                                bg-violet-50 dark:bg-violet-500/10
                                                                                text-violet-600 dark:text-violet-400
                                                                                border border-violet-100 dark:border-violet-500/20">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                             viewBox="0 0 16 16"
                                                                             fill="currentColor" class="w-3.5 h-3.5">
                                                                            <path fill-rule="evenodd"
                                                                                  d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14Zm3.844-8.791a.75.75 0 0 0-1.188-.918l-3.7 4.79-1.649-1.833a.75.75 0 1 0-1.114 1.004l2.25 2.5a.75.75 0 0 0 1.15-.043l4.25-5.5Z"
                                                                                  clip-rule="evenodd"/>
                                                                        </svg>
                                                                        <span class="font-semibold text-xs">{{ $program->total_tests }} تست</span>
                                                                    </div>
                                                                </div>

                                                                <!-- اطلاعات جلسه -->
                                                                @if($program->advisingSession)
                                                                    <div
                                                                        class="mt-4 pt-3 border-t border-slate-200/60 dark:border-slate-700/50">
                                                                        <div
                                                                            class="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                 viewBox="0 0 16 16"
                                                                                 fill="currentColor"
                                                                                 class="w-3.5 h-3.5 text-amber-500">
                                                                                <path fill-rule="evenodd"
                                                                                      d="M8 1.75a.75.75 0 0 1 .692.462l1.41 3.393 3.664.293a.75.75 0 0 1 .428 1.317l-2.791 2.39.853 3.575a.75.75 0 0 1-1.12.814L8 12.177l-3.136 1.817a.75.75 0 0 1-1.12-.814l.852-3.574-2.79-2.39a.75.75 0 0 1 .427-1.318l3.663-.293 1.41-3.393A.75.75 0 0 1 8 1.75Z"
                                                                                      clip-rule="evenodd"/>
                                                                            </svg>
                                                                            <span
                                                                                class="text-xs font-medium truncate">{{ $program->advisingSession->title }}</span>
                                                                        </div>
                                                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 pr-5">
                                                                            {{ jalali($program->advisingSession->activation_date)->format('%d %B %Y') }}
                                                                        </p>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <!-- فلش -->
                                                            <div class="flex-shrink-0 w-8 h-8 rounded-full
                                                                        bg-slate-100 dark:bg-slate-700/50
                                                                        flex items-center justify-center
                                                                        group-hover:bg-teal-500 dark:group-hover:bg-teal-500
                                                                        transition-all duration-300">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                     viewBox="0 0 20 20"
                                                                     fill="currentColor"
                                                                     class="w-4 h-4 text-slate-400 dark:text-slate-500
                                                                            group-hover:text-white group-hover:-translate-x-0.5
                                                                            transition-all duration-300">
                                                                    <path fill-rule="evenodd"
                                                                          d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                                                          clip-rule="evenodd"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <!-- حالت خالی -->
                                        <div class="flex flex-col items-center justify-center py-16 px-4">
                                            <div class="w-20 h-20 rounded-2xl bg-slate-100 dark:bg-slate-800
                                                        flex items-center justify-center mb-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke-width="1.5" stroke="currentColor"
                                                     class="w-10 h-10 text-slate-400 dark:text-slate-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                                </svg>
                                            </div>
                                            <h4 class="font-bold text-slate-700 dark:text-slate-200 mb-1">برنامه‌ای وجود
                                                ندارد</h4>
                                            <p class="text-sm text-slate-500 dark:text-slate-400 text-center">
                                                هنوز برنامه‌ای برای شما ثبت نشده است
                                            </p>
                                        </div>
                                    @endif

                                    <!-- پیجینیشن -->
                                    @if($weeklyPrograms->hasPages())
                                        <div class="mt-6 flex justify-center">
                                            <div class="inline-flex items-center gap-1 p-1 rounded-xl
                                                        bg-slate-100 dark:bg-slate-800/50
                                                        border border-slate-200/60 dark:border-slate-700/50">
                                                {{ $weeklyPrograms->links('layouts.client.pagination') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <!-- end tabs:contents:tabOne -->
                            </div>
                            <!-- end tabs:contents -->
                        </div>
                        <!-- end tabs container -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
