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
                                            <div class="grid gap-4 sm:grid-cols-1">
                                                @foreach($weeklyPrograms as $program)
                                                    @php
                                                        $isExpanded = in_array($program->id, $expandedPrograms ?? []);
                                                    @endphp

                                                    <div class="bg-secondary border border-border rounded-2xl overflow-hidden flex flex-col">

                                                        <!-- Main Box -->
                                                        <div class="p-4 flex-1 flex flex-col gap-4">

                                                            <!-- بالا: آیکن + اطلاعات برنامه -->
                                                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                                                <!-- راست: آیکن و عنوان و بازه زمانی -->
                                                                <div class="flex items-center gap-4">
                                                                    <div class="flex-shrink-0 w-12 h-12 bg-teal-100 dark:bg-teal-900/30 rounded-full flex items-center justify-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-teal-500" viewBox="0 0 20 20" fill="currentColor">
                                                                            <path fill-rule="evenodd" d="M5.75 2a.75.75 0 0 1 .75.75V4h7V2.75a.75.75 0 0 1 1.5 0V4h.25A2.75 2.75 0 0 1 18 6.75v8.5A2.75 2.75 0 0 1 15.25 18H4.75A2.75 2.75 0 0 1 2 15.25v-8.5A2.75 2.75 0 0 1 4.75 4H5V2.75A.75.75 0 0 1 5.75 2Zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75Z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                    </div>

                                                                    <div class="flex-1" style="margin-right: 10px">
                                                                        <h3 class="font-bold text-foreground text-lg">
                                                                            برنامه هفته {{ jdate($program->start_date)->format('d %B') }}
                                                                        </h3>

                                                                        <p class="text-sm text-muted mt-1">
                            <span class="inline-flex items-center gap-1">
                                تا {{ jdate($program->end_date)->format('d %B Y') }}
                            </span>
                                                                        </p>

                                                                        {{-- وضعیت + آمار در یک ردیف --}}
                                                                        <div class="mt-3 flex flex-wrap items-center gap-2">
                                                                            <!-- ساعت -->
                                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $program->total_hours }} ساعت
                            </span>

                                                                            <!-- پارت -->
                                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-xs rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                {{ $program->total_parts }} پارت
                            </span>

                                                                            <!-- تست -->
                                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-violet-100 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 text-xs rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $program->total_tests }} تست
                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- پایین باکس: دکمه‌ها (مشاهده برنامه + دراپ‌داون) -->
                                                            <div class="mt-2 pt-3 border-t border-border flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 md:gap-3">
                                                                <a wire:navigate href="{{ route('client.profile.consultation.weekly-program', $program->id) }}"
                                                                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                                    </svg>
                                                                    مشاهده برنامه
                                                                </a>

                                                                <!-- دکمه دراپ‌داون کنار بقیه دکمه‌ها -->
                                                                <button
                                                                    wire:click="toggleDetails({{ $program->id }})"
                                                                    class="w-full sm:w-auto inline-flex items-center justify-between sm:justify-center gap-3 px-4 py-2.5 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                                    <span class="md:hidden">مشاهده جزئیات</span>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                         class="w-5 h-5 transition-transform {{ $isExpanded ? 'rotate-180' : '' }}"
                                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <!-- Dropdown Details -->
                                                        @if($isExpanded)
                                                            <div class="border-t border-border bg-background/50 p-4">
                                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                                    <!-- تاریخ شروع -->
                                                                    <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                        </svg>
                                                                        <span class="text-xs text-muted">تاریخ شروع</span>
                                                                        <span class="font-bold text-foreground text-sm mt-1">{{ jdate($program->start_date)->format('d %B') }}</span>
                                                                    </div>

                                                                    <!-- تاریخ پایان -->
                                                                    <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: orange">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                        </svg>
                                                                        <span class="text-xs text-muted">تاریخ پایان</span>
                                                                        <span class="font-bold text-foreground text-sm mt-1">{{ jdate($program->end_date)->format('d %B') }}</span>
                                                                    </div>

                                                                    <!-- ساعت کل -->
                                                                    <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                        </svg>
                                                                        <span class="text-xs text-muted">ساعت کل</span>
                                                                        <span class="font-bold text-foreground text-sm mt-1">{{ $program->total_hours }} ساعت</span>
                                                                    </div>

                                                                    <!-- تعداد تست -->
                                                                    <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #bc1dbc">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                                        </svg>
                                                                        <span class="text-xs text-muted">تعداد تست</span>
                                                                        <span class="font-bold text-foreground text-sm mt-1">{{ $program->total_tests }} تست</span>
                                                                    </div>
                                                                </div>

                                                                <!-- اطلاعات جلسه مشاوره (اگر وجود داشته باشد) -->
                                                                @if($program->advisingSession)
                                                                    <div class="mt-4 pt-4 border-t border-border">
                                                                        <div class="flex items-center gap-3 p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-5 h-5 text-amber-500">
                                                                                <path fill-rule="evenodd" d="M8 1.75a.75.75 0 0 1 .692.462l1.41 3.393 3.664.293a.75.75 0 0 1 .428 1.317l-2.791 2.39.853 3.575a.75.75 0 0 1-1.12.814L8 12.177l-3.136 1.817a.75.75 0 0 1-1.12-.814l.852-3.574-2.79-2.39a.75.75 0 0 1 .427-1.318l3.663-.293 1.41-3.393A.75.75 0 0 1 8 1.75Z" clip-rule="evenodd"/>
                                                                            </svg>
                                                                            <div class="flex-1">
                                                                                <p class="font-semibold text-foreground text-sm">{{ $program->advisingSession->title }}</p>
                                                                                <p class="text-xs text-muted mt-0.5">{{ jalali($program->advisingSession->activation_date)->format('%d %B %Y') }}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <!-- حالت خالی -->
                                        <div class="flex flex-col items-center justify-center py-12 space-y-4">
                                            <img src="/client/empty/plan.png" class="w-full max-w-xs" alt="empty"/>
                                            <div class="text-center space-y-2">
                                                <h2 class="font-bold text-xl text-foreground">برنامه‌ای وجود
                                                    ندارد!</h2>
                                                <p class="text-muted text-sm">هنوز برنامه‌ای برای شما ثبت نشده است.</p>
                                            </div>
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
