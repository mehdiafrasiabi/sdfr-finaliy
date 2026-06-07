<div class="min-h-screen bg-[#0a0a0f] text-white relative overflow-x-hidden" dir="rtl">

    {{-- grid bg --}}
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background-image:linear-gradient(to right,rgba(59,130,246,0.05) 1px,transparent 1px),linear-gradient(to bottom,rgba(59,130,246,0.05) 1px,transparent 1px);background-size:48px 48px;"></div>
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background:radial-gradient(ellipse 70% 50% at 50% 0%,rgba(59,130,246,0.10) 0%,transparent 70%);"></div>
    <div class="fixed top-0 right-0 w-[500px] h-[500px] pointer-events-none z-0"
         style="background:radial-gradient(circle,rgba(139,92,246,0.07),transparent 70%);transform:translate(40%,-40%);"></div>

    <div class="relative z-10 max-w-3xl mx-auto px-4 py-8 pb-16">

        @if($expired)
            <div class="flex flex-col items-center justify-center py-20 text-center space-y-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);">
                    <svg class="w-8 h-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-7.4 12.82A1 1 0 003.75 18h16.5a1 1 0 00.86-1.32l-7.4-12.82a1 1 0 00-1.72 0z"/></svg>
                </div>
                <h2 class="text-xl font-black text-white">لینک منقضی شده است</h2>
                <p class="text-sm text-white/50 leading-7">برای دریافت لینک تازه با همکاران ما تماس بگیرید.</p>
            </div>
        @else

            {{-- هدر --}}
            <div class="flex items-center gap-3 mb-7">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.3);">
                    <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h1 class="font-black text-lg text-white">تست‌های والدینی</h1>
                    <p class="text-xs text-white/40 mt-0.5">{{ $invitation->parent_role_label }} گرامی، لطفاً تمام تست‌های زیر را تکمیل کنید</p>
                </div>
            </div>

            {{-- flash messages --}}
            @if(session()->has('info'))
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-5" style="background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.25);">
                    <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <p class="text-sm text-blue-300">{{ session('info') }}</p>
                </div>
            @endif
            @if(session()->has('success'))
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-5" style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.25);">
                    <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    <p class="text-sm text-emerald-300">{{ session('success') }}</p>
                </div>
            @endif

            {{-- progress کلی --}}
            <div class="rounded-2xl p-5 mb-6" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></div>
                        <span class="text-xs text-white/50">پیشرفت کلی</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-white">{{ $completedCount }} / {{ $totalCount }}</span>
                        <span class="text-xs font-black text-blue-400">{{ $totalCount > 0 ? round(($completedCount/$totalCount)*100) : 0 }}%</span>
                    </div>
                </div>
                <div class="h-2.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06);">
                    <div class="h-full rounded-full transition-all duration-700"
                         style="background:linear-gradient(to left,#3b82f6,#8b5cf6);width:{{ $totalCount > 0 ? round(($completedCount/$totalCount)*100) : 0 }}%;"></div>
                </div>
            </div>

            {{-- تکمیل شده --}}
            @if($invitation->isCompleted())
                <div class="flex flex-col items-center text-center rounded-2xl p-6 mb-6" style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mb-3" style="background:rgba(34,197,94,0.15);">
                        <svg class="w-7 h-7 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-black text-emerald-400 text-base mb-1">تمام تست‌ها تکمیل شد ✓</h3>
                    <p class="text-xs text-white/40 leading-6">از همکاری شما سپاسگزاریم. می‌توانید این صفحه را ببندید.</p>
                </div>
            @endif

            {{-- کارت‌های تست --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($items as $item)
                    @php
                        $isCompleted  = $item->status === 'completed';
                        $isInProgress = $item->status === 'in_progress';
                        $pct = $item->total > 0 ? round(($item->answered / $item->total) * 100) : 0;
                    @endphp
                    <div class="flex flex-col rounded-2xl overflow-hidden transition-all duration-300"
                         style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,{{ $isCompleted ? '0.1' : '0.06' }});"
                         @if($isCompleted) style="background:rgba(34,197,94,0.05);border:1px solid rgba(34,197,94,0.2);" @endif>

                        {{-- نوار رنگی بالا --}}
                        <div class="h-0.5 w-full"
                             style="background:{{ $isCompleted ? 'linear-gradient(to left,#22c55e,#16a34a)' : ($isInProgress ? 'linear-gradient(to left,#f59e0b,#d97706)' : 'linear-gradient(to left,rgba(255,255,255,0.1),rgba(255,255,255,0.05))') }};"></div>

                        <div class="flex flex-col flex-1 p-5">
                            {{-- عنوان + بج --}}
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <h3 class="font-bold text-white text-sm leading-snug flex-1">{{ $item->assessment->name_fa }}</h3>
                                <span class="flex-shrink-0 inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold"
                                      style="{{ $isCompleted
                                          ? 'background:rgba(34,197,94,0.15);color:#4ade80;border:1px solid rgba(34,197,94,0.3);'
                                          : ($isInProgress
                                              ? 'background:rgba(245,158,11,0.15);color:#fbbf24;border:1px solid rgba(245,158,11,0.3);'
                                              : 'background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.3);border:1px solid rgba(255,255,255,0.08);') }}">
                                    @if($isCompleted)
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        تکمیل‌شده
                                    @elseif($isInProgress)
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                        در حال انجام
                                    @else
                                        شروع نشده
                                    @endif
                                </span>
                            </div>

                            @if($item->assessment->description_fa)
                                <p class="text-xs text-white/40 leading-6 mb-3">{{ $item->assessment->description_fa }}</p>
                            @endif

                            {{-- progress --}}
                            <div class="mt-auto space-y-2">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-white/35">پیشرفت: {{ $item->answered }} از {{ $item->total }}</span>
                                    <span class="font-bold {{ $isCompleted ? 'text-emerald-400' : 'text-white/50' }}">{{ $pct }}%</span>
                                </div>
                                @if($item->total > 0)
                                    <div class="h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06);">
                                        <div class="h-full rounded-full transition-all duration-700"
                                             style="width:{{ $pct }}%;background:{{ $isCompleted ? '#22c55e' : ($isInProgress ? '#f59e0b' : '#3b82f6') }};"></div>
                                    </div>
                                @endif

                                {{-- دکمه --}}
                                <div class="pt-2">
                                    @if($isCompleted)
                                        <button disabled
                                                class="w-full h-10 rounded-xl text-xs font-bold flex items-center justify-center gap-2 cursor-not-allowed"
                                                style="background:rgba(34,197,94,0.1);color:#4ade80;border:1px solid rgba(34,197,94,0.2);">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            تکمیل‌شده
                                        </button>
                                    @elseif($isInProgress)
                                        <button wire:click="start('{{ $item->assessment->slug }}')"
                                                wire:loading.attr="disabled"
                                                class="w-full h-10 rounded-xl text-xs font-bold transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2"
                                                style="background:rgba(245,158,11,0.15);color:#fbbf24;border:1px solid rgba(245,158,11,0.3);">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0-5 5m5-5H6"/></svg>
                                            ادامه
                                        </button>
                                    @else
                                        <button wire:click="start('{{ $item->assessment->slug }}')"
                                                wire:loading.attr="disabled"
                                                class="w-full h-10 rounded-xl text-xs font-bold text-white transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2"
                                                style="background:linear-gradient(135deg,#3b82f6,#8b5cf6);box-shadow:0 4px 16px rgba(59,130,246,0.25);">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0-5 5m5-5H6"/></svg>
                                            شروع
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
