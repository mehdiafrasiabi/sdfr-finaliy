<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>
            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-10">
                    <div class="space-y-5">
                        <!-- Header -->
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">آزمون‌ ها</div>
                        </div>
                        <!-- Guide Section -->
                        <div
                            dir="rtl"
                            x-data="collapseGuide('exam-guide')"
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
                                        راهنمای شرکت در آزمون
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
                                class="px-4 md:px-6 pb-6">
                                <div class="flex flex-col md:flex-row-reverse gap-6 items-center mt-2">
                                    <!-- IMAGE -->
                                    <div class="relative w-full md:w-[280px] shrink-0 order-2 md:order-1">
                                        <img
                                            src="/client/assets/images/blog/sdfr.jpg"
                                            class="w-full h-[200px] md:h-[180px] object-cover rounded-xl">
                                        <button
                                            type="button"
                                            id="57612318744"
                                            data-video-url="https://www.aparat.com/video/video/embed/videohash/utg98i1/vt/frame?titleShow=true&recom=self"
                                            allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"
                                            data-video-title="راهنمای شرکت در آزمون"
                                            class="absolute inset-0 flex items-center justify-center">
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

                        <!-- Tabs: Typed vs Essay -->
                        <div class="flex gap-2 border-b border-border mb-2">
                            <button type="button" wire:click="setTab('typed')"
                                    class="px-4 py-2 text-sm font-semibold rounded-t-lg transition
                                       {{ $activeTab === 'typed' ? 'bg-primary text-primary-foreground' : 'bg-secondary text-muted hover:text-foreground' }}">
                                آزمون تستی
                            </button>
                            <button type="button" wire:click="setTab('essay')"
                                    class="px-4 py-2 text-sm font-semibold rounded-t-lg transition
                                       {{ $activeTab === 'essay' ? 'bg-primary text-primary-foreground' : 'bg-secondary text-muted hover:text-foreground' }}">
                                آزمون تشریحی
                                @if($essayAssignments->count() > 0)
                                    <span class="inline-flex items-center justify-center w-5 h-5 ml-1 text-[10px] rounded-full bg-white/30">
                                        {{ $essayAssignments->count() }}
                                    </span>
                                @endif
                            </button>
                        </div>

                        @if($activeTab === 'essay')
                            @include('livewire.client.profile.typed-exam._essay-list', ['essayAssignments' => $essayAssignments])
                        @elseif($assignments->isEmpty())
                            <div class="flex flex-col items-center justify-center space-y-12 py-16">
                                <div class="flex flex-col items-center justify-center space-y-12">
                                    <img src="/client/empty/exam.png" class="w-full max-w-xs"
                                         alt="empty"/>
                                    <div class="text-center space-y-3">
                                        <h2 class="font-bold text-xl text-foreground">
                                            آزمونی برای شما وجود ندارد!
                                            <p class="text-muted text-sm">هنوز آزمونی برای شما ثبت نشده است.</p>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($assignments as $assignment)
                                    @php
                                        $exam = $assignment->typedExam;
                                        $isExpanded = in_array($assignment->id, $expandedExams);
                                    @endphp
                                    <div
                                        class="bg-secondary border border-border rounded-2xl overflow-hidden flex flex-col">

                                        <!-- Main Box -->
                                        <div class="p-4 flex-1 flex flex-col gap-4">

                                            <!-- بالا: آیکن + اطلاعات آزمون -->
                                            <div
                                                class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                                <!-- راست: آیکن و عنوان و بازه زمانی -->
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="flex-shrink-0 w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             class="w-6 h-6 text-blue-500" fill="none"
                                                             viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 " style="margin-right: 10px">
                                                        <h3 class="font-bold text-foreground text-lg">
                                                            {{ $exam->title }}
                                                        </h3>
                                                        @if($assignment->time_range)
                                                            <p class="text-sm text-muted mt-1">
                                                                <span class="inline-flex items-center gap-1">

                                                                    {{ $assignment->time_range['start_date'] }} ساعت {{ $assignment->time_range['start_time'] }}
                                                                    تا
                                                                    {{ $assignment->time_range['end_date'] }} ساعت {{ $assignment->time_range['end_time'] }}
                                                                </span>
                                                            </p>
                                                        @endif
                                                        {{-- وضعیت + نمره مرتب در یک ردیف --}}
                                                        <div class="mt-3 flex flex-wrap items-center gap-2">
                                                            @switch($assignment->computed_status)
                                                                @case('not_started')
                                                                    <span
                                                                        class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-xs rounded-full">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none"
                                                                             viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                        </svg>
                                                                        هنوز شروع نشده
                                                                    </span>
                                                                    @break

                                                                @case('available')
                                                                    <span
                                                                        class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-500 dark:text-green-400 text-xs rounded-full">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none"
                                                                             viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                        </svg>
                                                                        قابل شرکت
                                                                    </span>
                                                                    @break

                                                                @case('expired')
                                                                    <span
                                                                        class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-500 dark:text-red-500 text-xs rounded-full">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none"
                                                                             viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                  d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                        </svg>
                                                                        منقضی شده
                                                                    </span>
                                                                    @break
                                                                @case('completed')
                                                                    <span
                                                                        class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-primary dark:text-blue-400 text-xs rounded-full">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none"
                                                                                     viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                          d="M5 13l4 4L19 7"/>
                                                                                </svg>
                                                                                تکمیل شده
                                                                    </span>
                                                                    @break
                                                            @endswitch

                                                            @if($assignment->computed_status === 'completed' && $assignment->latestAttempt?->score !== null)
                                                                <span
                                                                    class="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary text-xs rounded-full">
                                                                         نمره: {{ number_format($assignment->latestAttempt->score, 1) }}%
                                                               </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- پایین باکس: دکمه‌ها (ورود/نتیجه/غیره + دراپ‌داون) -->
                                            <div
                                                class="mt-2 pt-3 border-t border-border flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 md:gap-3">
                                                @if($assignment->can_start)
                                                    <button
                                                        wire:click="confirmEntry({{ $assignment->id }})"
                                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                                        </svg>
                                                        ورود به آزمون
                                                    </button>
                                                @elseif($assignment->computed_status === 'completed')
                                                    <a
                                                        href="{{ route('client.profile.typed-exam.result', ['attemptId' => $assignment->latestAttempt->id]) }}"
                                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold text-sm transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                        </svg>
                                                        مشاهده نتیجه
                                                    </a>
                                                @elseif($assignment->computed_status === 'not_started')
                                                    <button disabled
                                                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-muted text-yellow-500 rounded-xl font-semibold text-sm cursor-not-allowed">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        منتظر شروع
                                                    </button>
                                                @else
                                                    <button disabled
                                                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-muted text-red-500 rounded-xl font-semibold text-sm cursor-not-allowed">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                        </svg>
                                                        غیرفعال
                                                    </button>
                                                @endif

                                                <!-- دکمه دراپ‌داون کنار بقیه دکمه‌ها -->
                                                <button
                                                    wire:click="toggleDetails({{ $assignment->id }})"
                                                    class="w-full sm:w-auto inline-flex items-center justify-between sm:justify-center gap-3 px-4 py-2.5 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                    <span class="md:hidden">مشاهده جزئیات</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="w-5 h-5 transition-transform {{ $isExpanded ? 'rotate-180' : '' }}"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Dropdown Details -->
                                        @if($isExpanded)
                                            <div class="border-t border-border bg-background/50 p-4">
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                    <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             class="w-6 h-6 text-primary mb-2" fill="none"
                                                             viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                        </svg>
                                                        <span class="text-xs text-muted">نام دفترچه</span>
                                                        <span
                                                            class="font-bold text-foreground text-sm mt-1">{{ $exam->title }}</span>
                                                    </div>
                                                    <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-2"
                                                             fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                             style="color: orange">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span class="text-xs text-muted">مدت زمان</span>
                                                        <span class="font-bold text-foreground text-sm mt-1">
                                                            @if($assignment->time?->duration_minutes)
                                                                {{ $assignment->time->duration_minutes }} دقیقه
                                                            @else
                                                                وجود ندارد
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             class="w-6 h-6 text-green-500 mb-2" fill="none"
                                                             viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span class="text-xs text-muted">تعداد سوالات</span>
                                                        <span class="font-bold text-foreground text-sm mt-1">{{ $exam->questions->count() }} سوال</span>
                                                    </div>

                                                    <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-2"
                                                             fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                             style="color: #bc1dbc">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                        </svg>
                                                        <span class="text-xs text-muted">نوع آزمون</span>
                                                        <span class="font-bold text-foreground text-sm mt-1">تستی</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <br>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Start Exam Modal -->
    @if($confirmingExamId)
        @php
            $selectedAssignment = $assignments->firstWhere('id', $confirmingExamId);
            $selectedExam = $selectedAssignment?->typedExam;
        @endphp
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ modalOpen: true }">
            <div class="flex items-center justify-center min-h-screen px-4">
                <!-- Modal Content -->
                <div x-show="modalOpen"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative w-full max-w-md my-20 overflow-hidden transition-all transform bg-secondary border border-border rounded-2xl shadow-2xl z-20">
                    <!-- Modal Body -->
                    <div class="p-6">
                        <div class="flex flex-col items-center justify-center space-y-5">
                            <!-- green Circle with Clock Icon -->
                            <div class="flex items-center justify-center w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                     xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-green-500">
                                    <path
                                        d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19Z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path d="M12 9V12L13.5 13.5" stroke="currentColor" stroke-width="2"
                                          stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path
                                        d="M16.51 17.35L16.16 21.18C16.1149 21.6787 15.8845 22.1423 15.5142 22.4792C15.1439 22.8162 14.6607 23.002 14.16 23H9.82998C9.32931 23.002 8.84609 22.8162 8.47578 22.4792C8.10548 22.1423 7.87504 21.6787 7.82998 21.18L7.47998 17.35M7.48998 6.65002L7.83998 2.82002C7.88489 2.32309 8.11391 1.8609 8.4821 1.52417C8.85028 1.18744 9.33103 1.00049 9.82998 1.00002H14.18C14.6807 0.997985 15.1639 1.18381 15.5342 1.52079C15.9045 1.85776 16.1349 2.32137 16.18 2.82002L16.53 6.65002"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <!-- Exam Title -->
                            <h3 class="font-bold text-xl text-foreground">{{ $selectedExam?->title ?? 'آزمون' }}</h3>
                            <!-- Warning Text -->
                            <p class="text-center text-muted text-sm leading-relaxed">
                                حواستون باشه از زمانی که دکمه شرکت در آزمون رو می‌زنید، زمان برای شما در نظر گرفته میشه!
                            </p>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="flex items-center gap-x-4 border-t border-border p-4">
                        <button type="button" wire:click="closeModal"
                                class="flex items-center justify-center gap-x-2 w-full bg-background border border-border rounded-xl text-foreground py-3 px-4 hover:bg-secondary transition-colors">
                            <span class="font-bold text-sm">لغو</span>
                        </button>
                        <button wire:click="enterExam"
                                class="flex items-center justify-center gap-x-2 w-full bg-primary hover:bg-primary/90 border border-transparent rounded-xl text-primary-foreground py-3 px-4 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-bold text-sm">شروع</span>
                        </button>
                    </div>
                </div>
                <!-- Backdrop -->
                <div x-show="modalOpen"
                     wire:click="closeModal"
                     class="fixed inset-0 bg-secondary/80 cursor-pointer transition-all z-10"></div>
            </div>
        </div>
    @endif
</div>
