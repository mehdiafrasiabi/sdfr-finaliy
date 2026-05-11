<div class="max-w-7xl space-y-8 px-4 mx-auto">

    <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

        <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
            <livewire:client.profile.sidebar/>
        </div>

        <div class="lg:col-span-9 md:col-span-8 space-y-6">

            {{-- هدر --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1">
                    <div class="w-1 h-1 bg-foreground rounded-full"></div>
                    <div class="w-2 h-2 bg-foreground rounded-full"></div>
                </div>
                <div class="font-black text-foreground text-lg">تحلیل وضعیت مطالعاتی</div>
            </div>

            {{-- کارت خلاصه وضعیت --}}
            @if(!empty($analysis))
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/40 rounded-2xl p-5 text-center">
                    <div class="text-3xl font-black text-red-600 dark:text-red-400 mb-1">{{ $analysis['weak_count'] }}</div>
                    <div class="text-xs text-red-600/70 dark:text-red-400/70 font-semibold">مبحث ضعیف</div>
                    <div class="text-[10px] text-muted mt-1">نیاز به تمرکز بیشتر</div>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/40 rounded-2xl p-5 text-center">
                    <div class="text-3xl font-black text-amber-600 dark:text-amber-400 mb-1">{{ $analysis['medium_count'] }}</div>
                    <div class="text-xs text-amber-600/70 dark:text-amber-400/70 font-semibold">مبحث متوسط</div>
                    <div class="text-[10px] text-muted mt-1">قابل بهبود</div>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/40 rounded-2xl p-5 text-center">
                    <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mb-1">{{ $analysis['strong_count'] }}</div>
                    <div class="text-xs text-emerald-600/70 dark:text-emerald-400/70 font-semibold">مبحث قوی</div>
                    <div class="text-[10px] text-muted mt-1">پایه خوب</div>
                </div>
            </div>

            {{-- نمودار توزیع رتبه‌ها --}}
            <div class="bg-secondary border border-border rounded-2xl p-6">
                <h3 class="font-black text-foreground mb-5 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    توزیع سطح مباحث
                </h3>

                <div class="space-y-3">
                    @php
                        $ratingColors = [
                            'D'  => 'bg-red-500',    'D+' => 'bg-red-400',
                            'C'  => 'bg-orange-500', 'C+' => 'bg-orange-400',
                            'B'  => 'bg-amber-500',  'B+' => 'bg-amber-400',
                            'A'  => 'bg-emerald-500','A+' => 'bg-emerald-400',
                        ];
                        $total = max(1, $analysis['total']);
                    @endphp

                    @foreach($analysis['rating_counts'] as $label => $count)
                        @if($count > 0)
                        <div class="flex items-center gap-3">
                            <span class="w-6 text-xs font-black text-foreground text-right">{{ $label }}</span>
                            <div class="flex-1 bg-border rounded-full h-6 overflow-hidden">
                                <div class="{{ $ratingColors[$label] ?? 'bg-primary' }} h-full rounded-full flex items-center justify-end pr-2 transition-all duration-700"
                                     style="width: {{ max(5, ($count / $total) * 100) }}%">
                                    <span class="text-white text-[10px] font-bold">{{ $count }}</span>
                                </div>
                            </div>
                            <span class="w-8 text-xs text-muted text-left">{{ round(($count / $total) * 100) }}%</span>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- وضعیت هر درس --}}
            @if(!empty($analysis['subject_averages']))
            <div class="bg-secondary border border-border rounded-2xl p-6">
                <h3 class="font-black text-foreground mb-5 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    وضعیت هر درس
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($analysis['subject_averages'] as $subjectName => $data)
                    <div class="flex items-center justify-between p-4 bg-background border border-border rounded-xl">
                        <span class="font-semibold text-sm text-foreground">{{ $subjectName }}</span>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-muted">{{ $data['count'] }} مبحث</span>
                            <span class="px-3 py-1 rounded-full text-xs font-black
                                @if($data['average'] >= 6) bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400
                                @elseif($data['average'] >= 4) bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400
                                @else bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400
                                @endif">
                                {{ $data['label'] }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- اطلاعات پیش‌جلسه --}}
            @if($preSession)
            <div class="bg-secondary border border-border rounded-2xl p-6">
                <h3 class="font-black text-foreground mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    خلاصه پیش‌جلسه
                </h3>
                <div class="flex items-center gap-3 p-4 bg-background border border-border rounded-xl">
                    <div class="w-10 h-10 rounded-full {{ $preSession->status === 'completed' ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-amber-100 dark:bg-amber-900/30' }} flex items-center justify-center">
                        @if($preSession->status === 'completed')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <div class="font-bold text-sm text-foreground">{{ $preSession->title }}</div>
                        <div class="text-xs text-muted">{{ $preSession->statusLabel }}</div>
                    </div>
                </div>
            </div>
            @endif

            @endif {{-- end if analysis --}}

            {{-- دکمه ساخت برنامه --}}
            @if($trialWeek && $trialWeek->status === \App\Models\TrialWeek::STATUS_PRE_SESSION_DONE)
            <div class="bg-gradient-to-l from-primary/10 to-primary/5 border border-primary/30 rounded-2xl p-8 text-center">
                <div class="text-5xl mb-4">📅</div>
                <h3 class="font-black text-foreground text-xl mb-3">آماده ساخت برنامه</h3>
                <p class="text-muted text-sm mb-6 leading-relaxed">
                    بر اساس طبقه‌بندی و پیش‌جلسه شما، یک برنامه مطالعاتی ۷ روزه شخصی‌سازی شده ساخته می‌شود.
                </p>
                <button wire:click="openHoursModal"
                        class="inline-flex items-center gap-3 px-8 py-4 bg-primary hover:bg-primary/90 text-primary-foreground rounded-2xl font-black text-base shadow-lg shadow-primary/30 hover:-translate-y-0.5 transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    ساخت برنامه
                </button>
            </div>
            @elseif($trialWeek && $trialWeek->status === \App\Models\TrialWeek::STATUS_PROGRAM_BUILT)
            <div class="bg-gradient-to-l from-emerald-500/10 to-teal-500/10 border border-emerald-500/30 rounded-2xl p-6 text-center">
                <div class="text-4xl mb-3">✅</div>
                <h3 class="font-black text-foreground text-lg mb-2">برنامه ساخته شده است</h3>
                <a wire:navigate href="{{ route('client.profile.consultation.sessions') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-500 hover:bg-emerald-400 text-white rounded-xl font-bold mt-3 transition-colors">
                    مشاهده جلسات و برنامه
                </a>
            </div>
            @endif

        </div>
    </div>

    {{-- مودال ساعت مطالعه روزانه --}}
    @if($showHoursModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         @keydown.escape.window="$wire.closeHoursModal()">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeHoursModal"></div>
        <div class="relative z-10 w-full max-w-sm bg-background border border-border rounded-3xl shadow-2xl p-8 text-center"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="text-4xl mb-4">⏱️</div>
            <h2 class="text-xl font-black text-foreground mb-2">برنامه روزانه</h2>
            <p class="text-muted text-sm mb-6">در روز چند ساعت می‌توانید مطالعه کنید؟</p>

            <div class="flex items-center justify-center gap-4 mb-6">
                <button wire:click="$set('dailyStudyHours', {{ max(1, $dailyStudyHours - 1) }})"
                        class="w-10 h-10 rounded-full bg-secondary border border-border text-foreground font-black text-lg hover:bg-border transition-colors">
                    −
                </button>
                <div class="text-4xl font-black text-primary min-w-[60px]">
                    {{ $dailyStudyHours }}
                </div>
                <button wire:click="$set('dailyStudyHours', {{ min(14, $dailyStudyHours + 1) }})"
                        class="w-10 h-10 rounded-full bg-secondary border border-border text-foreground font-black text-lg hover:bg-border transition-colors">
                    +
                </button>
            </div>
            <p class="text-xs text-muted mb-6">ساعت در روز</p>

            @error('dailyStudyHours') <p class="text-sm text-red-500 mb-4">{{ $message }}</p> @enderror

            <div class="flex gap-3">
                <button wire:click="buildProgram"
                        wire:loading.attr="disabled"
                        class="flex-1 py-3 bg-primary hover:bg-primary/90 disabled:opacity-50 text-primary-foreground rounded-xl font-bold transition-colors flex items-center justify-center gap-2">
                    <svg wire:loading wire:target="buildProgram" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="buildProgram">بسازید!</span>
                    <span wire:loading wire:target="buildProgram">در حال ساخت...</span>
                </button>
                <button wire:click="closeHoursModal"
                        class="flex-1 py-3 bg-secondary hover:bg-border text-foreground rounded-xl font-bold transition-colors">
                    انصراف
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
