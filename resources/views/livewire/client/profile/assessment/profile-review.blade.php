<div class="min-h-screen bg-[#0a0a0f] text-white relative overflow-x-hidden" dir="rtl">
    <div class="fixed inset-0 pointer-events-none z-0" style="background-image:linear-gradient(to right,rgba(59,130,246,0.05) 1px,transparent 1px),linear-gradient(to bottom,rgba(59,130,246,0.05) 1px,transparent 1px);background-size:48px 48px;"></div>
    <div class="fixed inset-0 pointer-events-none z-0" style="background:radial-gradient(ellipse 70% 50% at 50% 0%,rgba(59,130,246,0.10) 0%,transparent 70%);"></div>

    <div class="relative z-10 max-w-3xl mx-auto px-4 py-8 pb-32">

        {{-- هدر --}}
        <div class="mb-7">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.3);">
                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </div>
                <h1 class="text-xl font-black text-white">پروفایل روان‌شناختی شما</h1>
            </div>
            <p class="text-sm text-white/45 leading-7 mr-12">بر اساس پاسخ‌هایتان به آزمون‌ها، این پروفایل برای شما ساخته شد. لطفاً آن را مرور کنید.</p>
        </div>

        @if(session()->has('info'))
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-5" style="background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.25);">
                <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg>
                <p class="text-sm text-blue-300">{{ session('info') }}</p>
            </div>
        @endif

        {{-- ─── MBTI ─── --}}
        @if(!empty($summary['mbti']['type']))
            <div class="rounded-2xl overflow-hidden mb-4" style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.07);">
                <div class="h-0.5" style="background:linear-gradient(to left,#3b82f6,#8b5cf6);"></div>
                <div class="p-5">
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div>
                            <p class="text-[11px] text-white/35 mb-1.5">تیپ شخصیتی شما</p>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-3xl font-black" style="background:linear-gradient(to left,#3b82f6,#8b5cf6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">{{ $summary['mbti']['type'] }}</span>
                                <span class="text-base font-bold text-white/70">— {{ $summary['mbti']['title'] }}</span>
                            </div>
                        </div>
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0" style="background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.3);">
                            <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        </div>
                    </div>
                    <p class="text-sm text-white/60 leading-7 mb-3">{{ $summary['mbti']['description'] }}</p>
                    <div class="rounded-xl p-3.5" style="background:rgba(59,130,246,0.08);border:1px solid rgba(59,130,246,0.2);">
                        <p class="text-[11px] font-bold text-blue-400 mb-1">توصیه برای یادگیری</p>
                        <p class="text-sm text-white/65 leading-6">{{ $summary['mbti']['study_tip'] }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- ─── VARK ─── --}}
        @if(!empty($summary['vark']['profile']))
            <div class="rounded-2xl overflow-hidden mb-4" style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.07);">
                <div class="h-0.5" style="background:linear-gradient(to left,#8b5cf6,#ec4899);"></div>
                <div class="p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(139,92,246,0.15);border:1px solid rgba(139,92,246,0.3);">
                            <svg class="w-5 h-5 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] text-white/35">سبک یادگیری</p>
                            <h2 class="text-base font-black text-white">
                                {{ $summary['vark']['profile'] }}
                                @if($summary['vark']['is_multimodal'])
                                    <span class="text-xs text-white/40 font-normal"> (چندوجهی)</span>
                                @endif
                            </h2>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                        @foreach($summary['vark']['modalities'] as $m)
                            <div class="rounded-xl p-3 text-center transition-all duration-200"
                                 style="{{ $m['dominant'] ? 'background:rgba(139,92,246,0.15);border:1px solid rgba(139,92,246,0.35);' : 'background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);' }}">
                                <p class="text-2xl font-black mb-1" style="{{ $m['dominant'] ? 'color:#a78bfa;' : 'color:rgba(255,255,255,0.35);' }}">{{ $m['letter'] }}</p>
                                <p class="text-[11px] leading-4 {{ $m['dominant'] ? 'text-white/80' : 'text-white/35' }}">{{ $m['title'] }}</p>
                                <p class="text-[10px] mt-1 {{ $m['dominant'] ? 'text-violet-400 font-bold' : 'text-white/25' }}">{{ $m['percent'] }}%</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- ─── Mindset ─── --}}
        @if(!empty($summary['mindset_facets']))
            <div class="rounded-2xl overflow-hidden mb-4" style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.07);">
                <div class="h-0.5" style="background:linear-gradient(to left,#f59e0b,#ef4444);"></div>
                <div class="p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);">
                            <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                        </div>
                        <div>
                            <p class="text-[11px] text-white/35">ذهنیت تحصیلی</p>
                            <h2 class="text-base font-black text-white">نتایج مایندست</h2>
                        </div>
                    </div>
                    <div class="space-y-3.5">
                        @foreach($summary['mindset_facets'] as $facet)
                            @php
                                $level = $facet['level'] ?? 'medium';
                                $barColor = match($level) { 'low' => '#22c55e', 'high' => '#ef4444', default => '#f59e0b' };
                                $badgeBg  = match($level) { 'low' => 'rgba(34,197,94,0.12)', 'high' => 'rgba(239,68,68,0.12)', default => 'rgba(245,158,11,0.12)' };
                                $badgeBorder = match($level) { 'low' => 'rgba(34,197,94,0.3)', 'high' => 'rgba(239,68,68,0.3)', default => 'rgba(245,158,11,0.3)' };
                                $badgeColor  = match($level) { 'low' => '#4ade80', 'high' => '#f87171', default => '#fbbf24' };
                                $levelLabel  = match($level) { 'low' => 'پایین', 'high' => 'بالا', default => 'متوسط' };
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs font-semibold text-white/70">{{ $facet['label'] }}</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                                          style="background:{{ $badgeBg }};border:1px solid {{ $badgeBorder }};color:{{ $badgeColor }};">
                                        {{ $levelLabel }} · {{ $facet['percent'] }}%
                                    </span>
                                </div>
                                <div class="h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06);">
                                    <div class="h-full rounded-full transition-all duration-700"
                                         style="width:{{ $facet['percent'] }}%;background:{{ $barColor }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- نوار تأیید ثابت --}}
    <div class="fixed bottom-0 inset-x-0 z-50" style="background:rgba(10,10,15,0.96);backdrop-filter:blur(24px);border-top:1px solid rgba(255,255,255,0.07);">
        <div class="max-w-3xl mx-auto px-4 py-4">
            <p class="text-xs text-white/30 text-center mb-3">با تأیید این پروفایل، آماده‌ی ساخت برنامه‌ی هفتگی اختصاصی توسط پشتیبان خواهید شد.</p>
            <button type="button"
                    wire:click="acknowledge"
                    wire:loading.attr="disabled"
                    class="w-full h-12 rounded-xl font-bold text-sm text-white transition-all duration-200 hover:scale-[1.01] active:scale-[0.99] disabled:opacity-60"
                    style="background:linear-gradient(135deg,#3b82f6,#8b5cf6);box-shadow:0 4px 20px rgba(59,130,246,0.3);">
                <span wire:loading.remove wire:target="acknowledge">پروفایل من را تأیید می‌کنم و آماده‌ی شروع برنامه هستم</span>
                <span wire:loading wire:target="acknowledge" class="inline-flex items-center justify-center gap-2">
                    <span class="inline-block w-4 h-4 rounded-full border-2 border-white/30 border-t-white animate-spin"></span>
                    در حال ثبت...
                </span>
            </button>
        </div>
    </div>
</div>
