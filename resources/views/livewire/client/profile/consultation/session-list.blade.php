<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto" x-data="{
        showPreSessionModal: @entangle('showPreSessionModal'),
        selectedTitle: '',
        openModal(title) {
            this.selectedTitle = title;
            this.showPreSessionModal = true;
        }
    }">
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
                            @if($student && $student->advisor_id)
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

                        <!-- لیست جلسات -->
                        @if($sessions->isEmpty())
                            <div class="flex flex-col items-center justify-center space-y-12 py-16">
                                <img src="/client/empty/consultation.png" class="w-full max-w-xs" alt="empty"/>
                                <div class="text-center space-y-3">
                                    <h2 class="font-bold text-xl text-foreground">
                                        جلسه‌ای وجود ندارد!
                                        <p class="text-muted text-sm">هنوز جلسه ای برای شما ثبت نشده است.</p>
                                    </h2>
                                </div>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($sessions as $session)
                                    @php
                                        $isLocked = in_array($session->id, $lockedSessionIds ?? []);
                                    @endphp

                                    <div
                                        x-data="{ expanded: false }"
                                        class="glass border border-border rounded-2xl overflow-hidden flex flex-col {{ $isLocked ? 'opacity-75' : '' }}"
                                    >

                                        {{-- ═══════════════════════════════════
                                             موبایل: تصویر بالا، اطلاعات وسط، دکمه‌ها پایین
                                        ════════════════════════════════════ --}}
                                        <div class="md:hidden">

                                            {{-- تصویر بالا --}}
                                            <div class="w-full h-36 flex items-center justify-center {{ $isLocked ? 'bg-gradient-to-b from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600' : 'bg-gradient-to-b from-blue-100 to-blue-200 dark:from-blue-950 dark:to-blue-900' }}">
                                                @if($isLocked)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                    </svg>
                                                @else
                                                    <img src="https://biomaze.ir/_nuxt/img/clipboard-blue.b61a561.webp" class="w-24 h-24 object-contain drop-shadow-md" alt="">
                                                @endif
                                            </div>

                                            {{-- اطلاعات --}}
                                            <div class="p-4 space-y-3" dir="rtl">
                                                <h3 class="font-bold text-foreground text-base flex items-center gap-2 flex-wrap">
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

                                                <p class="text-sm text-muted">
                                                    <span class="inline-flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        {{ jalali($session->activation_date)->format('%d %B %Y') }}
                                                        @if($session->session_time)
                                                            &nbsp;ساعت {{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}
                                                        @endif
                                                    </span>
                                                </p>

                                                <div class="flex flex-wrap items-center gap-2">
                                                    @if($session->status === 'inactive')
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-slate-100 dark:bg-slate-900/30 text-slate-600 dark:text-slate-400 text-xs rounded-full">در انتظار برگزاری</span>
                                                    @elseif($session->status === 'active')
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-500 dark:text-green-400 text-xs rounded-full">در حال برگزاری</span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-500 dark:text-emerald-400 text-xs rounded-full">برگزار شده</span>
                                                    @endif

                                                    @if($session->location_type === 'online')
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 text-xs rounded-full">مجازی</span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-xs rounded-full">حضوری</span>
                                                    @endif

                                                    @if(!$isLocked && $session->preSession)
                                                        @if($session->preSession->status === 'completed')
                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-primary dark:text-blue-400 text-xs rounded-full">پیش‌جلسه تکمیل شده</span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-xs rounded-full">پیش‌جلسه در انتظار</span>
                                                        @endif
                                                    @endif
                                                </div>

                                                @if($isLocked)
                                                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800/50 rounded-lg px-3 py-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                        </svg>
                                                        <span>این جلسه قفل است. پس از مشخص شدن نتیجه جلسه قبلی، این جلسه برای شما باز می‌شود.</span>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- دکمه‌های موبایل --}}
                                            <div class="px-4 pb-4 space-y-2" dir="rtl">
                                                @if($isLocked)
                                                    <button disabled class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-500 rounded-xl font-semibold text-sm cursor-not-allowed opacity-60">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                        </svg>
                                                        جلسه قفل است
                                                    </button>
                                                @else
                                                    @if($session->skyroom_link && $session->is_active)
                                                        <a wire:ignore href="{{ $session->skyroom_link }}" target="_blank"
                                                           class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                            </svg>
                                                            ورود به جلسه
                                                        </a>
                                                    @endif
                                                    @if($session->canFillPreSession() && $session->preSession && $session->preSession->status !== 'completed')
                                                        <button
                                                            @click="openModal('{{ addslashes($session->title) }}')"
                                                            wire:click="openPreSessionModal({{ $session->id }})"
                                                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-semibold text-sm transition-colors">
                                                            پر کردن پیش‌جلسه
                                                        </button>
                                                    @elseif($session->preSession)
                                                        <a wire:navigate wire:ignore href="{{ route('client.profile.consultation.pre-session', $session->id) }}"
                                                           class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-slate-500 hover:bg-slate-600 text-white rounded-xl font-semibold text-sm transition-colors">
                                                            مشاهده پیش‌جلسه
                                                        </a>
                                                    @endif


                                                @endif

                                                <button
                                                    @click="expanded = !expanded"
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
                                            <div class="flex-shrink-0 w-[120px] flex items-center justify-center bg-gradient-to-br {{ $isLocked ? 'from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600' : 'from-blue-100 to-blue-200 dark:from-[#1e3a5f] dark:to-[#1e40af]' }}">
                                                @if($isLocked)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                    </svg>
                                                @else
                                                    <img src="https://biomaze.ir/_nuxt/img/clipboard-blue.b61a561.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">
                                                @endif
                                            </div>

                                            {{-- محتوا: items-center برای وسط‌چین عمودی دکمه‌ها --}}
                                            <div class="flex-1 p-4 flex items-center justify-between gap-4" dir="rtl">

                                                {{-- راست: عنوان + تاریخ + بج‌ها --}}
                                                <div class="space-y-2 flex-1 min-w-0">
                                                    <h3 class="font-bold text-foreground text-base flex items-center gap-2 flex-wrap">
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

                                                    <p class="text-sm text-muted">
                                                        <span class="inline-flex items-center gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            {{ jalali($session->activation_date)->format('%d %B') }}
                                                            @if($session->session_time)
                                                                &nbsp;ساعت {{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}
                                                            @endif
                                                        </span>
                                                    </p>

                                                    <div class="flex flex-wrap items-center gap-1.5">
                                                        @if($session->status === 'inactive')
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-100 dark:bg-slate-900/30 text-slate-600 dark:text-slate-400 text-xs rounded-full">در انتظار برگزاری</span>
                                                        @elseif($session->status === 'active')
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-500 dark:text-green-400 text-xs rounded-full">در حال برگزاری</span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-500 dark:text-emerald-400 text-xs rounded-full">برگزار شده</span>
                                                        @endif

                                                        @if($session->location_type === 'online')
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 text-xs rounded-full">مجازی</span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-xs rounded-full">حضوری</span>
                                                        @endif

                                                        @if(!$isLocked && $session->preSession)
                                                            @if($session->preSession->status === 'completed')
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-primary dark:text-blue-400 text-xs rounded-full">پیش‌جلسه تکمیل شده</span>
                                                            @else
                                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-xs rounded-full">پیش‌جلسه در انتظار</span>
                                                            @endif
                                                        @endif
                                                    </div>

                                                    @if($isLocked)
                                                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800/50 rounded-lg px-3 py-2 mt-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                            </svg>
                                                            <span>این جلسه قفل است. پس از مشخص شدن نتیجه جلسه قبلی، این جلسه برای شما باز می‌شود.</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <button
                                                    @click="expanded = !expanded"
                                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="w-4 h-4 transition-transform duration-200"
                                                         :class="{ 'rotate-180': expanded }"
                                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </button>

                                                {{-- چپ: دکمه‌ها (وسط‌چین عمودی بخاطر items-center والد) --}}
                                                <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                                                    @if($isLocked)
                                                        <button disabled class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-gray-200 dark:bg-gray-700 text-gray-400 dark:text-gray-500 rounded-xl font-semibold text-sm cursor-not-allowed opacity-60">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                            </svg>
                                                            قفل است
                                                        </button>
                                                    @else
                                                        @if($session->canFillPreSession() && $session->preSession && $session->preSession->status !== 'completed')
                                                            <button
                                                                @click="openModal('{{ addslashes($session->title) }}')"
                                                                wire:click="openPreSessionModal({{ $session->id }})"
                                                                class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-semibold text-sm transition-colors">
                                                                پر کردن پیش‌جلسه
                                                            </button>
                                                        @elseif($session->preSession)
                                                            <a wire:navigate wire:ignore href="{{ route('client.profile.consultation.pre-session', $session->id) }}"
                                                               class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-secondary hover:bg-secondary text-white rounded-xl font-semibold text-sm transition-colors">
                                                                مشاهده پیش‌جلسه
                                                            </a>
                                                        @endif

                                                        @if($session->skyroom_link && $session->is_active)
                                                            <a wire:ignore href="{{ $session->skyroom_link }}" target="_blank"
                                                               class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                                </svg>
                                                                ورود به جلسه
                                                            </a>
                                                        @endif
                                                    @endif

                                                </div>
                                            </div>
                                        </div>

                                        {{-- ═══ جزئیات (مشترک موبایل و دسکتاپ) ═══ --}}
                                        <div
                                            x-show="expanded"
                                            x-cloak
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 -translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 -translate-y-1"
                                            class="border-border bg-background/50 p-4"
                                            style="display: none;"
                                        >
                                            @if($session->description)
                                                <div class="mb-4 p-3 bg-secondary rounded-xl" dir="rtl">
                                                    <h4 class="font-semibold text-foreground text-sm mb-2">توضیحات:</h4>
                                                    <p class="text-sm text-muted leading-relaxed">{{ $session->description }}</p>
                                                </div>
                                            @endif

                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="text-xs text-muted">تاریخ جلسه</span>
                                                    <span class="font-bold text-foreground text-sm mt-1">{{ jalali($session->activation_date)->format('%d %B %Y') }}</span>
                                                </div>

                                                @if($session->session_time)
                                                    <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span class="text-xs text-muted">ساعت برگزاری</span>
                                                        <span class="font-bold text-foreground text-sm mt-1">{{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}</span>
                                                    </div>
                                                @endif

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

                                    </div>
                                @endforeach
                            </div>

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

        {{-- Modal پیش‌جلسه --}}
        <div x-show="showPreSessionModal" x-cloak
             class="fixed inset-0 z-[100] flex flex-col justify-end sm:items-center sm:justify-center"
             @keydown.escape.window="showPreSessionModal = false"
             style="display: none;">

            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"
                 x-show="showPreSessionModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showPreSessionModal = false; $wire.closePreSessionModal()"></div>

            <div class="relative z-10 w-full sm:max-w-md bg-secondary rounded-t-3xl sm:rounded-2xl border-t sm:border border-border shadow-2xl flex flex-col pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                 x-show="showPreSessionModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-8">

                <div class="sm:hidden flex justify-center pt-3 pb-1 shrink-0">
                    <div class="w-10 h-1 rounded-full bg-foreground/20"></div>
                </div>

                <div class="p-6">
                    <div class="flex flex-col items-center justify-center space-y-5">
                        <div class="flex items-center justify-center w-20 h-20 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-xl text-foreground">پر کردن پیش‌جلسه</h3>
                        <p class="text-center text-muted text-sm leading-relaxed">
                            آیا می‌خواهید پیش‌جلسه <strong x-text="selectedTitle"></strong> را پر کنید؟
                        </p>
                        <p class="text-center text-amber-600 dark:text-amber-400 text-xs bg-amber-50 dark:bg-amber-900/20 p-3 rounded-xl">
                            ⚠️ توجه: پس از رسیدن به تاریخ جلسه، امکان ویرایش پیش‌جلسه وجود نخواهد داشت.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-x-4 border-border p-4 pb-safe">
                    <button type="button"
                            @click="showPreSessionModal = false"
                            wire:click="closePreSessionModal"
                            class="flex items-center justify-center gap-x-2 w-full bg-background border border-border rounded-xl text-foreground py-3 px-4 hover:bg-secondary transition-colors">
                        <span class="font-bold text-sm">لغو</span>
                    </button>
                    <button wire:click="confirmStartPreSession"
                            wire:loading.attr="disabled"
                            wire:target="confirmStartPreSession"
                            class="flex items-center justify-center gap-x-2 w-full bg-primary hover:bg-primary/90 border border-transparent rounded-xl text-primary-foreground py-3 px-4 transition-colors disabled:opacity-60">
                        <span wire:loading.remove wire:target="confirmStartPreSession" class="font-bold text-sm">بله، شروع می‌کنم</span>
                        <span wire:loading wire:target="confirmStartPreSession" class="inline-block w-4 h-4 rounded-full border-2 border-white/40 border-t-white animate-spin"></span>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
