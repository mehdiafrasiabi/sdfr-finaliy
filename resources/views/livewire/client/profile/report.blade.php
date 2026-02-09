<div>

    @push('link')

        <style>
            .part-box {
                transition: all 0.2s ease;
                cursor: pointer;
            }

            .part-box:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .star-btn {
                cursor: pointer;
                transition: all 0.15s ease;
            }

            .star-btn:active {
                transform: scale(0.95);
            }

            /* تضمین فاصله از bottom nav در موبایل */
            @media (max-width: 768px) {
                .modal-bottom-safe {
                    padding-bottom: calc(env(safe-area-inset-bottom) + 80px);
                }
            }
        </style>
    @endpush


    <div class="max-w-7xl space-y-6 px-4 mx-auto">

        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">

                <livewire:client.profile.sidebar/>

            </div>


            <div class="lg:col-span-9 md:col-span-8">

                <div class="space-y-6">

                    <!-- Section Title -->

                    <div class="flex items-center gap-3">

                        <div class="flex items-center gap-1">

                            <div class="w-1 h-1 bg-foreground rounded-full"></div>

                            <div class="w-2 h-2 bg-foreground rounded-full"></div>

                        </div>

                        <div class="font-black text-foreground">گزارش های روزانه</div>

                    </div>

                    <!-- Guide Section -->
                    <div
                        dir="rtl"
                        x-data="collapseGuide('report-guide')"
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
                راهنمای ثبت گزارش روزانه
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

                                <!-- IMAGE -->
                                <div class="relative w-full md:w-[280px] shrink-0 order-2 md:order-1">

                                    <img
                                        src="/client/assets/images/blog/sdfr.jpg"
                                        class="w-full h-[200px] md:h-[180px] object-cover rounded-xl"
                                    >

                                    <button
                                        type="button"
                                        id="57612318744"
                                        data-video-url="https://www.aparat.com/video/video/embed/videohash/utg98i1/vt/frame?titleShow=true&recom=self"
                                        allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"
                                        data-video-title="راهنمای گزارش"
                                        class="absolute inset-0 flex items-center justify-center"
                                    >
                                        <span
                                            class="w-14 h-14 rounded-full bg-white/90 dark:bg-black/60
                                               flex items-center justify-center shadow-lg transition">
                                            <svg class="w-7 h-7 text-blue-600 mr-1"
                                                 fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </span>
                                    </button>
                                </div>

                                <!-- TEXT -->
                                <div
                                    class="flex-1 text-right text-sm md:text-base  text-white dark:text-white leading-7 order-1 md:order-2">

                                    دانش‌آموز عزیز سلام، قبل از شرکت در آزمون موارد زیر را با دقت مطالعه کنید:

                                    <br>• استفاده از آخرین نسخه مرورگر کروم الزامی است.
                                    <br>• حتماً قبل از خروج ثبت نهایی انجام شود.
                                    <br>• پس از ورود به هر دفترچه امکان بازگشت وجود ندارد.
                                    <br>• دفترچه آزمایشی ممکن است در پایان نمایش داده شود.

                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- End Guide Section -->


                    <!-- Tabs -->

                    <div class="space-y-5" x-data="{ activeTab: 'submit' }">

                        <!-- Tab List -->

                        <div class="relative overflow-x-auto">

                            <ul class="inline-flex gap-2 bg-secondary border border-border rounded-full p-1">

                                <li>

                                    <button type="button"

                                            class="flex items-center gap-x-2 relative rounded-full py-2 px-4"

                                            :class="activeTab === 'submit' ? 'text-foreground bg-background' : 'text-muted'"

                                            @click="activeTab = 'submit'">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5">

                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M12 4.5v15m7.5-7.5h-15"/>

                                        </svg>

                                        <span class="font-semibold text-sm">ارسال گزارش</span>

                                    </button>

                                </li>

                                <li>

                                    <button type="button"

                                            class="flex items-center gap-x-2 relative rounded-full py-2 px-4"

                                            :class="activeTab === 'history' ? 'text-foreground bg-background' : 'text-muted'"

                                            @click="activeTab = 'history'">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5">

                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>

                                        </svg>

                                        <span class="font-semibold text-sm">گزارش های ارسال شده</span>

                                    </button>

                                </li>

                            </ul>

                        </div>


                        <!-- Tab Content: Submit Report -->

                        <div x-show="activeTab === 'submit'" class="space-y-6">

                            @if($currentSession && $currentProgram)
                                <!-- Week Days Grid -->

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">

                                    @foreach($weekDays as $dayIndex => $day)
                                        <div class="day-card bg-background border border-border rounded-2xl p-4

                                            {{ $day['is_submitted'] ? 'submitted border-green-500/50' : '' }}

                                            {{ $day['can_submit'] ? 'today border-blue-500' : '' }}

                                            {{ $day['is_locked'] ? 'locked' : '' }}

                                            {{ $day['is_rest_day'] ? 'border-emerald-500/50 bg-emerald-50/30 dark:bg-emerald-900/10' : '' }}"

                                             wire:key="day-{{ $dayIndex }}">


                                            <!-- Day Header -->

                                            <div class="flex items-center justify-between mb-3">

                                                <div>

                                                    <h4 class="font-bold {{ $day['is_rest_day'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-foreground' }}">{{ $day['name'] }}</h4>

                                                    <p class="text-xs text-muted">{{ $day['jalali_date'] }}</p>

                                                </div>

                                                @if($day['is_rest_day'])

                                                    <span class="text-emerald-500">

                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                             viewBox="0 0 24 24" stroke-width="1.5"
                                                             stroke="currentColor" class="w-6 h-6">

                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>

                                                        </svg>

                                                    </span>

                                                @elseif($day['is_submitted'])

                                                    <span class="text-green-500">

                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                             fill="currentColor" class="w-6 h-6">

                                                            <path fill-rule="evenodd"
                                                                  d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                                                                  clip-rule="evenodd"/>

                                                        </svg>

                                                    </span>

                                                @elseif($day['is_locked'])

                                                    <span class="text-red-500">

                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                             fill="currentColor" class="w-6 h-6">

                                                            <path fill-rule="evenodd"
                                                                  d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z"
                                                                  clip-rule="evenodd"/>

                                                        </svg>

                                                    </span>

                                                @elseif($day['can_submit'])

                                                    <span class="text-blue-500 animate-pulse">

                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                             fill="currentColor" class="w-6 h-6">

                                                            <path fill-rule="evenodd"
                                                                  d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z"
                                                                  clip-rule="evenodd"/>

                                                        </svg>

                                                    </span>

                                                @endif

                                            </div>


                                            @if($day['is_rest_day'])

                                                <!-- Rest Day Content -->

                                                <div class="text-center py-4">

                                                    <div class="text-2xl mb-2">🌿</div>

                                                    <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                                                        روز استراحت</p>

                                                    <p class="text-xs text-emerald-600/70 dark:text-emerald-400/70 mt-1">
                                                        نیازی به ارسال گزارش نیست</p>

                                                </div>

                                            @else

                                                <!-- Parts Info -->

                                                <div class="space-y-2 mb-4">

                                                    <div class="flex items-center justify-between text-sm">

                                                        <span class="text-muted">تعداد پارت:</span>

                                                        <span
                                                            class="font-medium text-foreground">{{ count($day['parts']) }}</span>

                                                    </div>

                                                    <div class="flex items-center justify-between text-sm">

                                                        <span class="text-muted">تعداد تست:</span>

                                                        <span
                                                            class="font-medium text-foreground">{{ $day['total_tests'] }}</span>

                                                    </div>

                                                </div>



                                                <!-- Action Button -->

                                                @if($day['can_submit'])

                                                    <button type="button"

                                                            wire:click="openReportModal({{ $dayIndex }})"

                                                            class="w-full bg-primary hover:bg-primary/90 text-white rounded-xl py-2.5 font-semibold text-sm transition-all">

                                                        ثبت گزارش

                                                    </button>

                                                @elseif($day['is_submitted'])

                                                    <div
                                                        class="text-center text-green-600 dark:text-green-400 text-sm font-medium py-2">

                                                        گزارش ثبت شده

                                                    </div>

                                                @elseif($day['is_locked'])

                                                    <div class="text-center text-red-500 text-sm font-medium py-2">

                                                        مهلت تمام شده

                                                    </div>

                                                @else

                                                    <div class="text-center text-muted text-sm py-2">

                                                        در انتظار

                                                    </div>

                                                @endif

                                            @endif

                                        </div>

                                    @endforeach

                                </div>



                                <!-- Compensatory Section -->

                                @if(count($missedParts) > 0)

                                    <div
                                        class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl p-5">

                                        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">

                                            <div>

                                                <h3 class="font-bold text-amber-800 dark:text-amber-200 flex items-center gap-2">

                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                         fill="currentColor" class="w-5 h-5">

                                                        <path fill-rule="evenodd"
                                                              d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z"
                                                              clip-rule="evenodd"/>

                                                    </svg>

                                                    پارت های جبرانی

                                                </h3>

                                                <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">

                                                    {{ count($missedParts) }} پارت از دست رفته دارید

                                                </p>

                                            </div>

                                            <button type="button"

                                                    wire:click="openCompensatoryModal"

                                                    class="bg-amber-500 hover:bg-amber-600 text-white rounded-xl px-4 py-2 font-semibold text-sm transition-all">

                                                ثبت پارت جبرانی

                                            </button>

                                        </div>

                                    </div>

                                @endif

                            @else

                                <!-- No Session -->

                                <div class="flex flex-col items-center justify-center py-12 space-y-4">

                                    <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35"
                                         alt="empty"/>

                                    <div class="text-center space-y-2">

                                        <h2 class="font-bold text-xl text-foreground">جلسه مشاوره برگزار شده‌ای وجود
                                            ندارد</h2>

                                        <p class="text-muted text-sm">پس از برگزاری جلسه مشاوره، برنامه هفتگی در اینجا
                                            نمایش داده می‌شود.</p>

                                    </div>

                                </div>

                            @endif

                        </div>


                        <!-- Tab Content: Report History -->

                        <div x-show="activeTab === 'history'" class="space-y-4">

                            @if($reports->isNotEmpty())

                                <div class="overflow-x-auto">

                                    <table class="w-full text-sm text-right">

                                        <thead class="text-xs text-muted uppercase bg-secondary border-b border-border">

                                        <tr>

                                            <th class="whitespace-nowrap p-4">ردیف</th>

                                            <th class="whitespace-nowrap p-4">تاریخ</th>

                                            <th class="whitespace-nowrap p-4">روز</th>

                                            <th class="whitespace-nowrap p-4">پارت خوانده</th>

                                            <th class="whitespace-nowrap p-4">تست زده</th>

                                            <th class="whitespace-nowrap p-4">گوشی (غیردرسی)</th>

                                            <th class="whitespace-nowrap p-4">امتیاز</th>

                                            <th class="whitespace-nowrap p-4">وضعیت</th>

                                            <th class="whitespace-nowrap p-4">نظر مشاور</th>

                                        </tr>

                                        </thead>

                                        <tbody>

                                        @foreach($reports as $report)

                                            <tr class="odd:bg-secondary even:bg-background whitespace-nowrap"
                                                wire:key="report-{{ $report->id }}">

                                                <td class="p-4 font-medium text-foreground">

                                                    {{ $loop->iteration + $reports->firstItem() - 1 }}

                                                </td>

                                                <td class="p-4 text-foreground">

                                                    {{ jdate($report->report_date)->format('Y/m/d') }}

                                                    @if($report->is_compensatory)

                                                        <span
                                                            class="inline-block bg-amber-500/20 text-amber-600 dark:text-amber-400 text-xs px-2 py-0.5 rounded-full mr-1">جبرانی</span>

                                                    @endif

                                                </td>

                                                <td class="p-4 text-foreground">{{ $report->day_name }}</td>

                                                <td class="p-4">

                                                    <span class="text-green-600">{{ $report->read_parts_count }}</span>

                                                    <span class="text-muted">/</span>

                                                    <span class="text-foreground">{{ $report->total_parts }}</span>

                                                </td>

                                                <td class="p-4 text-foreground">{{ $report->total_tests }}</td>

                                                <td class="p-4 text-foreground">{{ $report->phone_hours }} ساعت</td>

                                                <td class="p-4">

                                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium

                                                            {{ $report->rating >= 3 ? 'bg-green-500/20 text-green-600 dark:text-green-400' : '' }}

                                                            {{ $report->rating == 2 ? 'bg-blue-500/20 text-blue-600 dark:text-blue-400' : '' }}

                                                            {{ $report->rating <= 1 ? 'bg-red-500/20 text-red-600 dark:text-red-400' : '' }}">

                                                            {{ $this->getRatingLabel($report->rating) }}

                                                        </span>

                                                </td>

                                                <td class="p-4">

                                                    @if($report->status === 'approved')

                                                        <span
                                                            class="inline-flex items-center gap-1 text-green-600 dark:text-green-400">

                                                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>

                                                                تایید شده

                                                            </span>

                                                    @elseif($report->status === 'rejected')

                                                        <span class="inline-flex items-center gap-1 text-red-500">

                                                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>

                                                                رد شده

                                                            </span>

                                                    @else

                                                        <span class="inline-flex items-center gap-1 text-amber-500">

                                                                <span class="w-2 h-2 bg-amber-500 rounded-full"></span>

                                                                در انتظار

                                                            </span>

                                                    @endif

                                                </td>

                                                <td class="p-4">

                                                    @if($report->advisor_comment)

                                                        <button type="button"

                                                                wire:click="openReplyModal({{ $report->id }})"

                                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-primary text-white hover:bg-primary/80 transition-all text-xs">

                                                            مشاهده

                                                        </button>

                                                    @else

                                                        <span class="text-muted text-xs">منتظر نظر</span>

                                                    @endif

                                                </td>

                                            </tr>

                                        @endforeach

                                        </tbody>

                                    </table>

                                </div>

                                <div class="p-4">

                                    {{ $reports->links('layouts.client.pagination') }}

                                </div>

                            @else

                                <div class="flex flex-col items-center justify-center py-12 space-y-4">

                                    <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35"
                                         alt="empty"/>

                                    <div class="text-center space-y-2">

                                        <h2 class="font-bold text-xl text-foreground">گزارشی ثبت نشده است</h2>

                                        <p class="text-muted text-sm">گزارش های ارسال شده شما در اینجا نمایش داده
                                            می‌شود.</p>

                                    </div>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Report Modal -->

    <!-- Report Modal - IMPROVED VERSION -->
    @if($showReportModal && isset($weekDays[$selectedDayIndex]))
        @php $selectedDay = $weekDays[$selectedDayIndex]; @endphp

        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-0 sm:p-4"
            wire:click.self="closeReportModal">

            <div
                class="w-full sm:max-w-2xl max-h-[85vh] sm:max-h-[88vh] overflow-hidden bg-background border-t sm:border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl flex flex-col"
                style="margin-bottom: env(safe-area-inset-bottom, 80px);"
                wire:keydown.escape.window="closeReportModal">

                <!-- Modal Header - Fixed -->
                <div class="shrink-0 bg-background border-b border-border px-4 sm:px-6 py-3 sm:py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-foreground">ثبت گزارش روزانه</h3>
                            <p class="text-xs sm:text-sm text-muted">{{ $selectedDay['name'] }}
                                - {{ $selectedDay['jalali_short'] }}</p>
                        </div>
                        <button type="button"
                                wire:click="closeReportModal"
                                class="text-muted hover:text-foreground transition-all p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Scrollable Content -->
                <div class="flex-1 overflow-y-auto px-4 sm:px-6 py-4 sm:py-5 space-y-4 sm:space-y-5">

                    <!-- Parts Selection -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="font-semibold text-foreground text-sm sm:text-base">پارت‌های خوانده
                                شده:</label>
                            <span class="text-xs sm:text-sm text-muted">
                            <span class="font-medium text-green-600">{{ count($selectedParts) }}</span> / {{ count($selectedDay['parts']) }}
                        </span>
                        </div>

                        <div class="grid grid-cols-1 gap-2.5 sm:gap-3">
                            @foreach($selectedDay['parts'] as $part)ظ
                                <div wire:key="part-select-{{ $part->id }}"
                                     class="part-box relative bg-secondary rounded-xl border-2 transition-all duration-200 {{ in_array($part->id, $selectedParts) ? 'border-green-500 bg-green-50/50 dark:bg-green-900/10' : 'border-transparent' }}">

                                    <!-- Part Header - Clickable -->
                                    <div wire:click="togglePart({{ $part->id }})"
                                         class="flex items-start gap-2.5 sm:gap-3 p-3 sm:p-3.5 cursor-pointer">

                                        <div
                                            class="mt-0.5 shrink-0 {{ in_array($part->id, $selectedParts) ? 'text-green-500' : 'text-muted' }}">
                                            @if(in_array($part->id, $selectedParts))
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                     fill="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                                                    <path fill-rule="evenodd"
                                                          d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                                                          clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke-width="1.5" stroke="currentColor"
                                                     class="w-5 h-5 sm:w-6 sm:h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-foreground text-sm sm:text-base line-clamp-1">{{ $part->lesson_name }}</h4>

                                            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mt-1">
                                                @if($part->ccSubject)
                                                    <span
                                                        class="text-[10px] sm:text-xs text-muted bg-background px-1.5 py-0.5 rounded">{{ $part->ccSubject->name }}</span>
                                                @endif
                                                @if($part->ccTopic)
                                                    <span
                                                        class="text-[10px] sm:text-xs text-primary bg-primary/10 px-1.5 py-0.5 rounded">{{ $part->ccTopic->name }}</span>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-2 mt-1.5 text-xs text-muted">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $part->duration_minutes }} دقیقه
                                            </span>
                                                @if($part->test_count)
                                                    <span class="text-muted/50">•</span>
                                                    <span class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                         stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    {{ $part->test_count }} تست
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ✅ فقط اگر پارت انتخاب شده، ستاره‌ها و تست نمایش داده بشه --}}
                                    @if(in_array($part->id, $selectedParts))
                                        <div class="px-3 sm:px-3.5 pb-3 sm:pb-3.5 space-y-2.5 border-t border-border/50"
                                             wire:click.stop>

                                            <!-- Star Rating -->
                                            <div>
                                                <label class="text-xs text-muted block mb-1.5">امتیاز این پارت:</label>
                                                <div class="flex items-center gap-1">
                                                    @for($s = 1; $s <= 4; $s++)
                                                        <button type="button"
                                                                wire:click="setPartRating({{ $part->id }}, {{ $s }})"
                                                                class="star-btn transition-transform hover:scale-110 p-0.5">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                                 class="w-6 h-6 sm:w-7 sm:h-7 {{ ($partRatings[$part->id] ?? 0) >= $s ? 'text-amber-400 fill-amber-400' : 'text-gray-300 fill-none' }}"
                                                                 stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                                            </svg>
                                                        </button>
                                                    @endfor
                                                    @if(($partRatings[$part->id] ?? 0) > 0)
                                                        <span class="text-xs font-medium mr-1.5
                                                        {{ ($partRatings[$part->id] ?? 0) >= 3 ? 'text-green-600' : '' }}
                                                        {{ ($partRatings[$part->id] ?? 0) == 2 ? 'text-blue-600' : '' }}
                                                        {{ ($partRatings[$part->id] ?? 0) == 1 ? 'text-red-600' : '' }}">
                                                        {{ \App\Models\DailyReport::RATINGS[$partRatings[$part->id]] ?? '' }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Test Input --}}
                                            @if($part->test_count)
                                                <div>
                                                    <label class="text-xs text-muted block mb-1">تعداد تست زده
                                                        شده:</label>
                                                    <input type="number"
                                                           wire:model="testsDone.{{ $part->id }}"
                                                           min="0"
                                                           max="{{ $part->test_count }}"
                                                           class="w-full h-9 sm:h-10 rounded-lg border border-border bg-background text-foreground text-sm px-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                                           placeholder="از {{ $part->test_count }} تست">
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Phone Hours -->
                    <div class="space-y-2">
                        <label class="font-semibold text-foreground text-sm sm:text-base flex items-center gap-2">
                            <svg class="w-4 h-4 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            ساعت استفاده از گوشی (غیر درسی):
                        </label>
                        <input type="number"
                               wire:model="phoneHours"
                               min="0"
                               max="24"
                               class="w-full h-11 rounded-xl border border-border bg-secondary text-foreground px-4 text-sm sm:text-base focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                               placeholder="مثال: 3 ساعت">
                        @error('phoneHours')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label class="font-semibold text-foreground text-sm sm:text-base flex items-center gap-2">
                            <svg class="w-4 h-4 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            توضیحات (اختیاری):
                        </label>
                        <textarea wire:model="description"
                                  rows="3"
                                  class="w-full rounded-xl border border-border bg-secondary text-foreground px-4 py-3 text-sm sm:text-base resize-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                  placeholder="اگر توضیحی دارید اینجا بنویسید..."></textarea>
                        @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Computed Rating Display -->
                    @php $computedRating = $this->computedRating; @endphp
                    <div
                        class="bg-gradient-to-br from-primary/5 to-primary/10 border border-primary/20 rounded-xl p-3.5 sm:p-4">
                        <div class="flex items-center justify-between gap-3">
                        <span class="font-semibold text-foreground text-sm sm:text-base flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            امتیاز کلی روز:
                        </span>
                            @if($computedRating > 0)
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center gap-0.5">
                                        @for($s = 1; $s <= 4; $s++)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                 class="w-4 h-4 {{ $s <= $computedRating ? 'text-amber-400 fill-amber-400' : 'text-gray-300' }}"
                                                 stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs sm:text-sm font-bold px-2 py-1 rounded-lg
                                    {{ $computedRating >= 3 ? 'bg-green-500/20 text-green-600 dark:text-green-400' : '' }}
                                    {{ $computedRating == 2 ? 'bg-blue-500/20 text-blue-600 dark:text-blue-400' : '' }}
                                    {{ $computedRating == 1 ? 'bg-red-500/20 text-red-600 dark:text-red-400' : '' }}">
                                    {{ \App\Models\DailyReport::RATINGS[$computedRating] ?? '' }}
                                </span>
                                </div>
                            @else
                                <span class="text-muted text-xs sm:text-sm">ابتدا پارت‌ها را امتیازدهی کنید</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modal Footer - Fixed -->
                <div class="shrink-0 bg-secondary border-t border-border px-4 sm:px-6 py-3 sm:py-4">
                    <div class="flex items-center justify-end gap-2 sm:gap-3">
                        <button type="button"
                                wire:click="closeReportModal"
                                class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl border border-border text-foreground hover:bg-background transition-all text-sm sm:text-base font-medium">
                            انصراف
                        </button>
                        <button type="button"
                                wire:click="submitReport"
                                wire:loading.attr="disabled"
                                class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl bg-primary text-white hover:bg-primary/90 transition-all disabled:opacity-50 text-sm sm:text-base font-semibold">
                            <span wire:loading.remove wire:target="submitReport">ثبت گزارش</span>
                            <span wire:loading wire:target="submitReport" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            در حال ثبت...
                        </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif



    <!-- Compensatory Modal - Multi-Step -->

    @if($showCompensatoryModal)

        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"

             wire:click.self="closeCompensatoryModal">

            <div
                class="w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-background border border-border rounded-2xl shadow-2xl"

                wire:keydown.escape.window="closeCompensatoryModal">


                <!-- Modal Header -->

                <div class="sticky top-0 z-10 bg-background border-b border-border px-6 py-4">

                    <div class="flex items-center justify-between">

                        <div>

                            <h3 class="text-lg font-bold text-foreground">ثبت پارت جبرانی</h3>

                            <p class="text-sm text-muted">

                                @if($compensatoryStep === 1)

                                    مرحله ۱: پارت های خوانده شده را انتخاب کنید

                                @else

                                    مرحله ۲: جزئیات گزارش را وارد کنید

                                @endif

                            </p>

                        </div>

                        <button type="button" wire:click="closeCompensatoryModal"

                                class="text-muted hover:text-foreground transition-all">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-6 h-6">

                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>

                            </svg>

                        </button>

                    </div>


                    <!-- Step Indicator -->

                    <div class="flex items-center gap-2 mt-4">

                        <div
                            class="flex-1 h-2 rounded-full {{ $compensatoryStep >= 1 ? 'bg-amber-500' : 'bg-border' }}"></div>

                        <div
                            class="flex-1 h-2 rounded-full {{ $compensatoryStep >= 2 ? 'bg-amber-500' : 'bg-border' }}"></div>

                    </div>

                </div>


                <div class="px-6 py-5 space-y-4">

                    @if($compensatoryStep === 1)

                        <!-- Step 1: Select Parts -->

                        @foreach($missedParts as $index => $missed)
                            @php $compPartHasStudyHours = in_array($missed['part']->id, $completedStudyParts); @endphp
                            <div wire:click="toggleCompensatoryPart({{ $missed['part']->id }})"

                                 wire:key="comp-part-{{ $missed['part']->id }}"

                                 class="part-box p-4 bg-secondary rounded-xl
                                 {{ in_array($missed['part']->id, $selectedCompensatoryParts) ? 'selected' : '' }}
                                 {{ !$compPartHasStudyHours ? 'border-2 border-red-300 dark:border-red-800 opacity-70 cursor-not-allowed' : 'cursor-pointer' }}">

                                {{-- هشدار عدم ثبت ساعت مطالعه --}}
                                @if(!$compPartHasStudyHours)
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-red-500 shrink-0">
                                            <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-[10px] sm:text-xs text-red-600 dark:text-red-400 font-semibold">ساعت مطالعه ثبت نشده — ابتدا از بخش «ثبت ساعت مطالعه» اقدام کنید</span>
                                    </div>
                                @endif

                                <div class="flex items-start gap-3">

                                    <div
                                        class="part-icon mt-1 {{ in_array($missed['part']->id, $selectedCompensatoryParts) ? 'text-green-500' : (!$compPartHasStudyHours ? 'text-red-400' : 'text-muted') }}">
                                        @if(in_array($missed['part']->id, $selectedCompensatoryParts))

                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                 fill="currentColor" class="w-5 h-5">

                                                <path fill-rule="evenodd"
                                                      d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                                                      clip-rule="evenodd"/>

                                            </svg>
                                        @elseif(!$compPartHasStudyHours)

                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                 fill="currentColor" class="w-5 h-5">
                                                <path fill-rule="evenodd"
                                                      d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z"
                                                      clip-rule="evenodd"/>
                                            </svg>
                                        @else

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="w-5 h-5">

                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>

                                            </svg>

                                        @endif

                                    </div>

                                    <div class="flex-1 min-w-0">

                                        <div class="flex items-center gap-2 mb-1">

                                            <span
                                                class="text-xs bg-amber-500/20 text-amber-600 dark:text-amber-400 px-2 py-0.5 rounded-full">

                                                {{ $missed['day_name'] }} - {{ $missed['jalali_date'] }}

                                            </span>

                                        </div>

                                        <h4 class="font-medium {{ !$compPartHasStudyHours ? 'text-muted' : 'text-foreground' }} text-sm">{{ $missed['part']->lesson_name }}</h4>
                                        @if($missed['part']->ccSubject || $missed['part']->ccTopic)

                                            <p class="text-xs text-muted mt-1">

                                                {{ $missed['part']->ccSubject->name ?? '' }}

                                                @if($missed['part']->ccTopic)

                                                    - {{ $missed['part']->ccTopic->name }}

                                                @endif

                                            </p>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    @else

                        <!-- Step 2: Enter Details -->

                        <!-- Selected Parts Summary -->

                        <div
                            class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 mb-4">

                            <h4 class="font-semibold text-amber-800 dark:text-amber-200 mb-3">پارت‌های انتخاب شده:</h4>

                            <div class="space-y-4">

                                @foreach($missedParts as $missed)

                                    @if(in_array($missed['part']->id, $selectedCompensatoryParts))

                                        <div
                                            class="pb-3 border-b border-amber-200/50 dark:border-amber-800/50 last:border-0 last:pb-0">
                                            <div class="flex items-center justify-between text-sm mb-2">

                                                <span
                                                    class="text-foreground font-medium">{{ $missed['part']->lesson_name }}</span>

                                                @if($missed['part']->test_count)

                                                    <div class="flex items-center gap-2" wire:click.stop>

                                                        <label class="text-xs text-muted">تست زده:</label>

                                                        <input type="number"

                                                               wire:model="compensatoryTestsDone.{{ $missed['part']->id }}"

                                                               min="0"

                                                               max="{{ $missed['part']->test_count }}"

                                                               class="w-20 h-8 rounded-lg border border-border bg-background text-foreground text-sm px-2 text-center"

                                                               placeholder="{{ $missed['part']->test_count }}">

                                                    </div>

                                                @endif

                                            </div>

                                            <!-- Star Rating -->
                                            <div wire:click.stop>
                                                <label class="text-xs text-muted block mb-1.5">امتیاز:</label>
                                                <div class="flex items-center gap-1">
                                                    @for($s = 1; $s <= 4; $s++)
                                                        <button type="button"
                                                                wire:click="setCompensatoryPartRating({{ $missed['part']->id }}, {{ $s }})"
                                                                class="star-btn transition-transform hover:scale-110 p-0.5">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                                 class="w-6 h-6 sm:w-7 sm:h-7 {{ ($compensatoryPartRatings[$missed['part']->id] ?? 0) >= $s ? 'text-amber-400 fill-amber-400' : 'text-gray-300 fill-none' }}"
                                                                 stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                                            </svg>
                                                        </button>
                                                    @endfor
                                                    @if(($compensatoryPartRatings[$missed['part']->id] ?? 0) > 0)
                                                        <span class="text-xs font-medium mr-1.5
                {{ ($compensatoryPartRatings[$missed['part']->id] ?? 0) >= 3 ? 'text-green-600' : '' }}
                {{ ($compensatoryPartRatings[$missed['part']->id] ?? 0) == 2 ? 'text-blue-600' : '' }}
                {{ ($compensatoryPartRatings[$missed['part']->id] ?? 0) == 1 ? 'text-red-600' : '' }}">
                {{ \App\Models\DailyReport::RATINGS[$compensatoryPartRatings[$missed['part']->id]] ?? '' }}
            </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                            </div>

                        </div>



                        <!-- Phone Hours -->

                        <div class="space-y-2">

                            <label class="font-semibold text-foreground">ساعت استفاده از گوشی (غیر درسی):</label>

                            <input type="number"

                                   wire:model="compensatoryPhoneHours"

                                   min="0"

                                   max="24"

                                   class="w-full h-11 rounded-xl border border-border bg-secondary text-foreground px-4"

                                   placeholder="از 24 ساعت">

                            @error('compensatoryPhoneHours')

                            <p class="text-red-500 text-xs">{{ $message }}</p>

                            @enderror

                        </div>



                        <!-- Description -->

                        <div class="space-y-2">

                            <label class="font-semibold text-foreground">توضیحات (اختیاری):</label>

                            <textarea wire:model="compensatoryDescription"

                                      rows="3"

                                      class="w-full rounded-xl border border-border bg-secondary text-foreground px-4 py-3 resize-none"

                                      placeholder="توضیحات خود را بنویسید..."></textarea>

                            @error('compensatoryDescription')

                            <p class="text-red-500 text-xs">{{ $message }}</p>

                            @enderror

                        </div>

                    @endif

                </div>


                <!-- Modal Footer -->

                <div
                    class="sticky bottom-0 bg-secondary border-t border-border px-6 py-4 flex items-center justify-between">

                    <p class="text-sm text-muted">

                        انتخاب شده: <span
                            class="font-medium text-green-600">{{ count($selectedCompensatoryParts) }}</span>

                        از <span class="font-medium">{{ count($missedParts) }}</span> پارت

                    </p>

                    <div class="flex items-center gap-3">

                        @if($compensatoryStep === 1)

                            <button type="button"

                                    wire:click="closeCompensatoryModal"

                                    class="px-5 py-2.5 rounded-xl border border-border text-foreground hover:bg-background transition-all">

                                انصراف

                            </button>

                            <button type="button"

                                    wire:click="goToCompensatoryStep2"

                                    class="px-5 py-2.5 rounded-xl bg-amber-500 text-white hover:bg-amber-600 transition-all">

                                مرحله بعد

                            </button>

                        @else

                            <button type="button"

                                    wire:click="goToCompensatoryStep1"

                                    class="px-5 py-2.5 rounded-xl border border-border text-foreground hover:bg-background transition-all">

                                مرحله قبل

                            </button>

                            <button type="button"

                                    wire:click="submitCompensatory"

                                    wire:loading.attr="disabled"

                                    class="px-5 py-2.5 rounded-xl bg-amber-500 text-white hover:bg-amber-600 transition-all disabled:opacity-50">

                                <span wire:loading.remove wire:target="submitCompensatory">ثبت گزارش جبرانی</span>

                                <span wire:loading wire:target="submitCompensatory">در حال ثبت...</span>

                            </button>

                        @endif

                    </div>

                </div>

            </div>

        </div>
    @endif



    <!-- Report Modal - IMPROVED VERSION -->
    @if($showReportModal && isset($weekDays[$selectedDayIndex]))
        @php $selectedDay = $weekDays[$selectedDayIndex]; @endphp

        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-0 sm:p-4"
            wire:click.self="closeReportModal">

            <div
                class="w-full sm:max-w-2xl max-h-[85vh] sm:max-h-[88vh] overflow-hidden bg-background border-t sm:border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl flex flex-col"
                style="margin-bottom: env(safe-area-inset-bottom, 80px);"
                wire:keydown.escape.window="closeReportModal">

                <!-- Modal Header - Fixed -->
                <div class="shrink-0 bg-background border-b border-border px-4 sm:px-6 py-3 sm:py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-foreground">ثبت گزارش روزانه</h3>
                            <p class="text-xs sm:text-sm text-muted">{{ $selectedDay['name'] }}
                                - {{ $selectedDay['jalali_short'] }}</p>
                        </div>
                        <button type="button"
                                wire:click="closeReportModal"
                                class="text-muted hover:text-foreground transition-all p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Scrollable Content -->
                <div class="flex-1 overflow-y-auto px-4 sm:px-6 py-4 sm:py-5 space-y-4 sm:space-y-5">

                    <!-- Parts Selection -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="font-semibold text-foreground text-sm sm:text-base">پارت‌های خوانده
                                شده:</label>
                            <span class="text-xs sm:text-sm text-muted">
                            <span class="font-medium text-green-600">{{ count($selectedParts) }}</span> / {{ count($selectedDay['parts']) }}
                        </span>
                        </div>

                        <div class="grid grid-cols-1 gap-2.5 sm:gap-3">
                            @foreach($selectedDay['parts'] as $part)
                                @php $partHasStudyHours = in_array($part->id, $completedStudyParts); @endphp
                                <div wire:key="part-select-{{ $part->id }}"
                                     class="part-box relative bg-secondary rounded-xl border-2 transition-all duration-200
                                     {{ in_array($part->id, $selectedParts) ? 'border-green-500 bg-green-50/50 dark:bg-green-900/10' : '' }}
                                     {{ !$partHasStudyHours ? 'border-red-300 dark:border-red-800 opacity-70' : 'border-transparent' }}">

                                    {{-- نمایش هشدار عدم ثبت ساعت مطالعه --}}
                                    @if(!$partHasStudyHours)
                                        <div class="flex items-center gap-2 px-3 sm:px-3.5 pt-2.5 pb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-red-500 shrink-0">
                                                <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-[10px] sm:text-xs text-red-600 dark:text-red-400 font-semibold">ساعت مطالعه ثبت نشده — ابتدا از بخش «ثبت ساعت مطالعه» اقدام کنید</span>
                                        </div>
                                    @endif
                                    <!-- Part Header - Clickable -->
                                    <div wire:click="togglePart({{ $part->id }})"
                                         class="flex items-start gap-2.5 sm:gap-3 p-3 sm:p-3.5 {{ !$partHasStudyHours ? 'cursor-not-allowed' : 'cursor-pointer' }}">                                        <div
                                            class="mt-0.5 shrink-0 {{ in_array($part->id, $selectedParts) ? 'text-green-500' : (!$partHasStudyHours ? 'text-red-400' : 'text-muted') }}">
                                            @if(in_array($part->id, $selectedParts))
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                     fill="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                                                    <path fill-rule="evenodd"
                                                          d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                                                          clip-rule="evenodd"/>
                                                </svg>
                                            @elseif(!$partHasStudyHours)
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                     fill="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                                                    <path fill-rule="evenodd"
                                                          d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z"
                                                          clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                     stroke-width="1.5" stroke="currentColor"
                                                     class="w-5 h-5 sm:w-6 sm:h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold {{ !$partHasStudyHours ? 'text-muted' : 'text-foreground' }} text-sm sm:text-base line-clamp-1">{{ $part->lesson_name }}</h4>                                            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mt-1">
                                                @if($part->ccSubject)
                                                    <span
                                                        class="text-[10px] sm:text-xs text-muted bg-background px-1.5 py-0.5 rounded">{{ $part->ccSubject->name }}</span>
                                                @endif
                                                @if($part->ccTopic)
                                                    <span
                                                        class="text-[10px] sm:text-xs text-primary bg-primary/10 px-1.5 py-0.5 rounded">{{ $part->ccTopic->name }}</span>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-2 mt-1.5 text-xs text-muted">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $part->duration_minutes }} دقیقه
                                            </span>
                                                @if($part->test_count)
                                                    <span class="text-muted/50">•</span>
                                                    <span class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                         stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    {{ $part->test_count }} تست
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ✅ فقط اگر پارت انتخاب شده، ستاره‌ها و تست نمایش داده بشه --}}
                                    @if(in_array($part->id, $selectedParts))
                                        <div class="px-3 sm:px-3.5 pb-3 sm:pb-3.5 space-y-2.5 border-t border-border/50"
                                             wire:click.stop>

                                            <!-- Star Rating -->
                                            <div>
                                                <label class="text-xs text-muted block mb-1.5">امتیاز این پارت:</label>
                                                <div class="flex items-center gap-1">
                                                    @for($s = 1; $s <= 4; $s++)
                                                        <button type="button"
                                                                wire:click="setPartRating({{ $part->id }}, {{ $s }})"
                                                                class="star-btn transition-transform hover:scale-110 p-0.5">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                                 class="w-6 h-6 sm:w-7 sm:h-7 {{ ($partRatings[$part->id] ?? 0) >= $s ? 'text-amber-400 fill-amber-400' : 'text-gray-300 fill-none' }}"
                                                                 stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                                            </svg>
                                                        </button>
                                                    @endfor
                                                    @if(($partRatings[$part->id] ?? 0) > 0)
                                                        <span class="text-xs font-medium mr-1.5
                                                        {{ ($partRatings[$part->id] ?? 0) >= 3 ? 'text-green-600' : '' }}
                                                        {{ ($partRatings[$part->id] ?? 0) == 2 ? 'text-blue-600' : '' }}
                                                        {{ ($partRatings[$part->id] ?? 0) == 1 ? 'text-red-600' : '' }}">
                                                        {{ \App\Models\DailyReport::RATINGS[$partRatings[$part->id]] ?? '' }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Test Input --}}
                                            @if($part->test_count)
                                                <div>
                                                    <label class="text-xs text-muted block mb-1">تعداد تست زده
                                                        شده:</label>
                                                    <input type="number"
                                                           wire:model="testsDone.{{ $part->id }}"
                                                           min="0"
                                                           max="{{ $part->test_count }}"
                                                           class="w-full h-9 sm:h-10 rounded-lg border border-border bg-background text-foreground text-sm px-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                                           placeholder="از {{ $part->test_count }} تست">
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Phone Hours -->
                    <div class="space-y-2">
                        <label class="font-semibold text-foreground text-sm sm:text-base flex items-center gap-2">
                            <svg class="w-4 h-4 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            ساعت استفاده از گوشی (غیر درسی):
                        </label>
                        <input type="number"
                               wire:model="phoneHours"
                               min="0"
                               max="24"
                               class="w-full h-11 rounded-xl border border-border bg-secondary text-foreground px-4 text-sm sm:text-base focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                               placeholder="مثال: 3 ساعت">
                        @error('phoneHours')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label class="font-semibold text-foreground text-sm sm:text-base flex items-center gap-2">
                            <svg class="w-4 h-4 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            توضیحات (اختیاری):
                        </label>
                        <textarea wire:model="description"
                                  rows="3"
                                  class="w-full rounded-xl border border-border bg-secondary text-foreground px-4 py-3 text-sm sm:text-base resize-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                  placeholder="اگر توضیحی دارید اینجا بنویسید..."></textarea>
                        @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Computed Rating Display -->
                    @php $computedRating = $this->computedRating; @endphp
                    <div
                        class="bg-gradient-to-br from-primary/5 to-primary/10 border border-primary/20 rounded-xl p-3.5 sm:p-4">
                        <div class="flex items-center justify-between gap-3">
                        <span class="font-semibold text-foreground text-sm sm:text-base flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            امتیاز کلی روز:
                        </span>
                            @if($computedRating > 0)
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center gap-0.5">
                                        @for($s = 1; $s <= 4; $s++)
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                 class="w-4 h-4 {{ $s <= $computedRating ? 'text-amber-400 fill-amber-400' : 'text-gray-300' }}"
                                                 stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs sm:text-sm font-bold px-2 py-1 rounded-lg
                                    {{ $computedRating >= 3 ? 'bg-green-500/20 text-green-600 dark:text-green-400' : '' }}
                                    {{ $computedRating == 2 ? 'bg-blue-500/20 text-blue-600 dark:text-blue-400' : '' }}
                                    {{ $computedRating == 1 ? 'bg-red-500/20 text-red-600 dark:text-red-400' : '' }}">
                                    {{ \App\Models\DailyReport::RATINGS[$computedRating] ?? '' }}
                                </span>
                                </div>
                            @else
                                <span class="text-muted text-xs sm:text-sm">ابتدا پارت‌ها را امتیازدهی کنید</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modal Footer - Fixed -->
                <div class="shrink-0 bg-secondary border-t border-border px-4 sm:px-6 py-3 sm:py-4">
                    <div class="flex items-center justify-end gap-2 sm:gap-3">
                        <button type="button"
                                wire:click="closeReportModal"
                                class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl border border-border text-foreground hover:bg-background transition-all text-sm sm:text-base font-medium">
                            انصراف
                        </button>
                        <button type="button"
                                wire:click="submitReport"
                                wire:loading.attr="disabled"
                                class="px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl bg-primary text-white hover:bg-primary/90 transition-all disabled:opacity-50 text-sm sm:text-base font-semibold">
                            <span wire:loading.remove wire:target="submitReport">ثبت گزارش</span>
                            <span wire:loading wire:target="submitReport" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            در حال ثبت...
                        </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
