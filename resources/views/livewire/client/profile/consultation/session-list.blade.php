<div>
    @assets
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    @endassets

    <div class="max-w-7xl space-y-14 px-4 mx-auto" x-data="{ showPreSessionModal: @entangle('showPreSessionModal') }">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-10">
                    <div class="space-y-5">

                        <!-- section:title -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1">
                                    <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                    <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                </div>
                                <div class="font-black text-foreground">اتاق مشاوره</div>
                            </div>
                            <!-- دکمه افزودن برنامه کلاسی -->
                            @if($student && $student->advisor_id && $student->supporter_id)
                                <a wire:navigate href="{{ route('client.profile.consultation.class-schedule') }}"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors shadow-lg shadow-primary/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    افزودن برنامه کلاسی
                                </a>
                            @endif
                        </div>
                        <!-- end section:title -->

                        <!-- Guide Section -->
                        <div
                            dir="rtl"
                            x-data="collapseGuide('consultation-guide')"
                            x-init="init()"
                            class="rounded-2xl border border-border bg-primary overflow-hidden transition-all">

                            <!-- HEADER -->
                            <button
                                @click="toggle"
                                class="w-full flex items-center justify-between px-4 md:px-6 py-4 transition">

                                <!-- title -->
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-white dark:text-white"
                                         fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 14h-2v-2h2v2zm0-4h-2V6h2v6z"/>
                                    </svg>
                                    <span class="font-black text-white dark:text-white text-blue-300 md:text-lg">
                                        راهنمای شرکت در اتاق مشاوره
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
                                        <img src="/client/assets/images/blog/sdfr.jpg"
                                             class="w-full h-[200px] md:h-[180px] object-cover rounded-xl">
                                        <button
                                            type="button"
                                            id="57612318744"
                                            data-video-url="https://www.aparat.com/video/video/embed/videohash/utg98i1/vt/frame?titleShow=true&recom=self"
                                            allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"
                                            data-video-title="راهنمای اتاق مشاوزه"
                                            class="absolute inset-0 flex items-center justify-center">
                                            <span class="w-14 h-14 rounded-full bg-white/90 dark:bg-black/60
                                                   flex items-center justify-center shadow-lg transition">
                                                <svg class="w-7 h-7 text-blue-600 mr-1"
                                                     fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                            </span>
                                        </button>
                                    </div>

                                    <!-- TEXT -->
                                    <div class="flex-1 text-right text-sm md:text-base text-white dark:text-white leading-7 order-1 md:order-2">
                                        دانش‌آموز عزیز سلام، قبل از شرکت در جلسه مشاوره موارد زیر را با دقت مطالعه کنید:
                                        <br>• استفاده از آخرین نسخه مرورگر کروم الزامی است.
                                        <br>• قبل از شروع جلسه حتما "پیش جلسه" پرشود .
                                        <br>• در ساعت مقرر در جلسه حضور داشته باشید .
                                        <br>•پس برگزاری جلسه نهایت یک ساعت بعد برنامه شما بارگزاری میشود .
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Guide Section -->
                        <!-- فیلتر جلسات -->
                        <div class="flex flex-wrap gap-2" dir="rtl">
                            <button wire:click="$set('statusFilter', 'all')"
                                    class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors
                                           {{ $statusFilter === 'all'
                                               ? 'bg-primary text-primary-foreground shadow-md'
                                               : 'bg-secondary text-foreground border border-border hover:bg-secondary/80' }}">
                                همه جلسات
                            </button>
                            <button wire:click="$set('statusFilter', 'completed')"
                                    class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors
                                           {{ $statusFilter === 'completed'
                                               ? 'bg-emerald-500 text-white shadow-md'
                                               : 'bg-secondary text-foreground border border-border hover:bg-secondary/80' }}">
                                 برگزار شده
                            </button>
                            <button wire:click="$set('statusFilter', 'pending')"
                                    class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors
                                           {{ $statusFilter === 'pending'
                                               ? 'bg-slate-500 text-white shadow-md'
                                               : 'bg-secondary text-foreground border border-border hover:bg-secondary/80' }}">
                                در انتظار
                            </button>
                            <button wire:click="$set('statusFilter', 'cancelled')"
                                    class="px-4 py-2 rounded-xl text-sm font-semibold transition-colors
                                           {{ $statusFilter === 'cancelled'
                                               ? 'bg-red-500 text-white shadow-md'
                                               : 'bg-secondary text-foreground border border-border hover:bg-secondary/80' }}">
                                 لغو شده
                            </button>
                        </div>
                        <!-- لیست جلسات به صورت کارت -->
                        @if($sessions->isEmpty())
                            <div class="flex flex-col items-center justify-center space-y-12 py-16">
                                <div class="flex flex-col items-center justify-center space-y-12">
                                    <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35" alt="empty"/>
                                    <div class="text-center space-y-3">
                                        <h2 class="font-bold text-xl text-foreground">
                                            جلسه‌ای برای شما وجود ندارد.
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($sessions as $session)
                                    @php
                                        $isExpanded = in_array($session->id, $expandedSessions ?? []);
                                        $isLocked   = in_array($session->id, $lockedSessionIds ?? []);
                                    @endphp

                                    <div class="bg-secondary border border-border rounded-2xl overflow-hidden flex flex-col
                                                {{ $isLocked ? 'opacity-75' : '' }}">

                                        <!-- Main Box -->
                                        <div class="p-4 flex-1 flex flex-col gap-4">

                                            <!-- بالا: آیکن + اطلاعات جلسه -->
                                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                                <!-- راست: آیکن و عنوان و تاریخ -->
                                                <div class="flex items-center gap-4">
                                                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center
                                                                {{ $isLocked ? 'bg-gray-200 dark:bg-gray-700' : 'bg-purple-100 dark:bg-purple-900/30' }}">
                                                        @if($isLocked)
                                                            <!-- آیکن قفل -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                        @endif
                                                    </div>

                                                    <div class="flex-1" style="margin-right: 10px">
                                                        <h3 class="font-bold text-foreground text-lg flex items-center gap-2">
                                                            {{ $session->title }}
                                                            @if($isLocked)
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs rounded-full">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                                    </svg>
                                                                    قفل شده
                                                                </span>
                                                            @endif
                                                        </h3>

                                                        <p class="text-sm text-muted mt-1">
                                                            <span class="inline-flex items-center gap-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                                {{ jalali($session->activation_date)->format('%d %B %Y') }}
                                                                @if($session->session_time)
                                                                    - ساعت {{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}
                                                                @endif
                                                            </span>
                                                        </p>

                                                        {{-- وضعیت + محل برگزاری در یک ردیف --}}
                                                        <div class="mt-3 flex flex-wrap items-center gap-2">
                                                            <!-- وضعیت جلسه -->
                                                            @if($session->status === 'inactive')
                                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-slate-100 dark:bg-slate-900/30 text-slate-600 dark:text-slate-400 text-xs rounded-full">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                    </svg>
                                                                    در انتظار برگزاری
                                                                </span>
                                                            @elseif($session->status === 'active')
                                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-500 dark:text-green-400 text-xs rounded-full">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"/>
                                                                    </svg>
                                                                    در حال برگزاری
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-500 dark:text-emerald-400 text-xs rounded-full">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                    </svg>
                                                                    برگزار شده
                                                                </span>
                                                            @endif

                                                            <!-- محل برگزاری -->
                                                            @if($session->location_type === 'online')
                                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 text-xs rounded-full">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                                                    </svg>
                                                                    مجازی
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-xs rounded-full">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                    </svg>
                                                                    حضوری
                                                                </span>
                                                            @endif

                                                            <!-- وضعیت پیش‌جلسه -->
                                                            @if(!$isLocked && $session->preSession)
                                                                @if($session->preSession->status === 'completed')
                                                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-primary dark:text-blue-400 text-xs rounded-full">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                        </svg>
                                                                        پیش‌جلسه تکمیل شده
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-xs rounded-full">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                        </svg>
                                                                        پیش‌جلسه در انتظار
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <!-- پیام قفل بودن جلسه -->
                                                        @if($isLocked)
                                                            <div class="mt-3 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800/50 rounded-lg px-3 py-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                                </svg>
                                                                <span>
                                                                    این جلسه قفل است. پس از مشخص شدن نتیجه جلسه قبلی، این جلسه برای شما باز می‌شود.
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- پایین باکس: دکمه‌ها -->
                                            <div class="mt-2 pt-3 border-t border-border flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 md:gap-3">
                                                @if($isLocked)
                                                    {{-- جلسه قفل است: نمایش دکمه غیر فعال --}}
                                                    <button disabled
                                                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-500 rounded-xl font-semibold text-sm cursor-not-allowed opacity-60">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>                                                        </svg>
                                                        جلسه قفل است
                                                    </button>
                                                @else
                                                    {{-- جلسه باز است: نمایش دکمه‌های معمولی --}}
                                                    @if($session->canFillPreSession() && $session->preSession && $session->preSession->status !== 'completed')
                                                        <button wire:click="openPreSessionModal({{$session->id}})"
                                                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-semibold text-sm transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                            پر کردن پیش‌جلسه
                                                        </button>
                                                    @elseif($session->preSession)
                                                        <a wire:navigate wire:ignore href="{{ route('client.profile.consultation.pre-session', $session->id) }}"
                                                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-slate-500 hover:bg-slate-600 text-white rounded-xl font-semibold text-sm transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                            </svg>
                                                            مشاهده پیش‌جلسه
                                                        </a>
                                                    @endif


                                                    @if($session->skyroom_link && $session->is_active)
                                                        <a wire:ignore href="{{ $session->skyroom_link }}" target="_blank"
                                                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                            </svg>
                                                            ورود به جلسه
                                                        </a>
                                                    @endif
                                                @endif

                                                <!-- دکمه دراپ‌داون -->
                                                <button wire:click="toggleDetails({{ $session->id }})"
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
                                                <!-- توضیحات جلسه -->
                                                @if($session->description)
                                                    <div class="mb-4 p-3 bg-secondary rounded-xl">
                                                        <h4 class="font-semibold text-foreground text-sm mb-2">توضیحات:</h4>
                                                        <p class="text-sm text-muted leading-relaxed">{{ $session->description }}</p>
                                                    </div>
                                                @endif

                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                    <!-- تاریخ -->
                                                    <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        <span class="text-xs text-muted">تاریخ جلسه</span>
                                                        <span class="font-bold text-foreground text-sm mt-1">{{ jalali($session->activation_date)->format('%d %B %Y') }}</span>
                                                    </div>

                                                    <!-- ساعت -->
                                                    @if($session->session_time)
                                                        <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: orange">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            <span class="text-xs text-muted">ساعت برگزاری</span>
                                                            <span class="font-bold text-foreground text-sm mt-1">{{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}</span>
                                                        </div>
                                                    @endif

                                                    <!-- نوع جلسه -->
                                                    <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        <span class="text-xs text-muted">نوع جلسه</span>
                                                        <span class="font-bold text-foreground text-sm mt-1">{{ $session->location_type === 'online' ? 'مجازی' : 'حضوری' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            @if($sessions->hasPages())
                                <div class="mt-6">
                                    {{ $sessions->links('layouts.client.pagination') }}
                                </div>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>

        {{-- Modal تایید پیش‌جلسه --}}
        <div x-show="showPreSessionModal" x-cloak x-transition.opacity
             class="fixed inset-0 z-[100] overflow-y-auto ">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div x-show="showPreSessionModal"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative w-full max-w-md my-20 overflow-hidden transition-all  transform bg-secondary border border-border rounded-2xl shadow-2xl z-20">



                    <hr class="border-border">

                    <div class="p-6">
                        <div class="flex flex-col items-center justify-center space-y-5">
                            <div class="flex items-center justify-center w-20 h-20 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>

                            <h3 class="font-bold text-xl text-foreground">پر کردن پیش‌جلسه</h3>

                            @if($selectedSession)
                                <p class="text-center text-muted text-sm leading-relaxed">
                                    آیا می‌خواهید پیش‌جلسه <strong>{{ $selectedSession->title }}</strong> را پر کنید؟
                                </p>
                                <p class="text-center text-amber-600 dark:text-amber-400 text-xs bg-amber-50 dark:bg-amber-900/20 p-3 rounded-xl">
                                    ⚠️ توجه: پس از رسیدن به تاریخ جلسه، امکان ویرایش پیش‌جلسه وجود نخواهد داشت.
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-x-4 border-t border-border p-4">
                        <button type="button" wire:click="closePreSessionModal"
                                class="flex items-center justify-center gap-x-2 w-full bg-background border border-border rounded-xl text-foreground py-3 px-4 hover:bg-secondary transition-colors">
                            <span class="font-bold text-sm">لغو</span>
                        </button>
                        <button wire:click="confirmStartPreSession"
                                class="flex items-center justify-center gap-x-2 w-full bg-primary hover:bg-primary/90 border border-transparent rounded-xl text-primary-foreground py-3 px-4 transition-colors">
                            <span class="font-bold text-sm">بله، شروع می‌کنم</span>
                        </button>
                    </div>
                </div>

                <div x-show="showPreSessionModal" wire:click="closePreSessionModal" class="fixed inset-0 bg-secondary/80 cursor-pointer transition-all z-10"></div>
            </div>
        </div>
    </div>
</div>
