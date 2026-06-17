{{-- ===================================================================
     Feature preview mock-ups (pure HTML/CSS, no external images)
     Usage: @include('livewire.client.home.feature-preview', ['type' => 'dashboard'])
     Types: dashboard | planning | consult | exam | report
=================================================================== --}}
@php $type = $type ?? 'dashboard'; @endphp

<div class="fp-root" dir="rtl">
    @switch($type)

        {{-- ============================ DASHBOARD ============================ --}}
        @case('dashboard')
            <div class="fp-topbar">
                <div class="flex items-center gap-2">
                    <span class="fp-dot" style="background:#ef4444"></span>
                    <span class="fp-dot" style="background:#f59e0b"></span>
                    <span class="fp-dot" style="background:#22c55e"></span>
                </div>
                <span class="fp-tab">داشبورد دانش‌آموز</span>
            </div>
            <div class="fp-body">
                <div class="grid grid-cols-3 gap-2 sm:gap-3">
                    <div class="fp-stat">
                        <span class="fp-stat-label">مطالعه‌ی امروز</span>
                        <span class="fp-stat-num">۴:۲۰<small>ساعت</small></span>
                        <span class="fp-stat-up">▲ ۱۲٪</span>
                    </div>
                    <div class="fp-stat">
                        <span class="fp-stat-label">آزمون‌های هفته</span>
                        <span class="fp-stat-num">۷<small>آزمون</small></span>
                        <span class="fp-stat-up">▲ ۳</span>
                    </div>
                    <div class="fp-stat">
                        <span class="fp-stat-label">میانگین درصد</span>
                        <span class="fp-stat-num">۷۸<small>٪</small></span>
                        <span class="fp-stat-up">▲ ۴٪</span>
                    </div>
                </div>
                <div class="fp-card mt-3" style="flex:1;display:flex;flex-direction:column;">
                    <div class="flex items-center justify-between mb-2">
                        <span class="fp-card-title">ساعت مطالعه‌ی هفته</span>
                        <span class="fp-badge">۲۸ ساعت</span>
                    </div>
                    <div class="fp-bars">
                        @foreach([44,70,54,92,80,104,62] as $h)
                            <span class="fp-bar" style="height: {{ $h }}px"></span>
                        @endforeach
                    </div>
                    <div class="fp-bars-x">
                        @foreach(['ش','ی','د','س','چ','پ','ج'] as $d)<span>{{ $d }}</span>@endforeach
                    </div>
                </div>
            </div>
            @break

        {{-- ============================ PLANNING ============================ --}}
        @case('planning')
            <div class="fp-topbar">
                <div class="flex items-center gap-2">
                    <span class="fp-dot" style="background:#ef4444"></span>
                    <span class="fp-dot" style="background:#f59e0b"></span>
                    <span class="fp-dot" style="background:#22c55e"></span>
                </div>
                <span class="fp-tab">برنامه‌ی هفتگی</span>
            </div>
            <div class="fp-body">
                <div class="flex items-center justify-between mb-2">
                    <span class="fp-card-title">هفته‌ی جاری</span>
                    <span class="fp-badge">۹۲٪ تکمیل</span>
                </div>
                <div class="fp-week">
                    @php
                        $plan = [
                            ['شنبه',   [['ریاضی','#3b82f6',2],['زیست','#10b981',1]]],
                            ['یک‌شنبه',[['فیزیک','#8b5cf6',1],['عربی','#f59e0b',1]]],
                            ['دوشنبه', [['شیمی','#06b6d4',2]]],
                            ['سه‌شنبه',[['ادبیات','#ec4899',1],['ریاضی','#3b82f6',1]]],
                            ['چهارشنبه',[['آزمون','#ef4444',1],['دینی','#22c55e',1]]],
                        ];
                    @endphp
                    @foreach($plan as [$day,$blocks])
                        <div class="fp-day">
                            <span class="fp-day-h">{{ $day }}</span>
                            @foreach($blocks as [$name,$c,$span])
                                <span class="fp-slot" style="background: {{ $c }}1f; color: {{ $c }}; border-color: {{ $c }}3a;">{{ $name }}</span>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
            @break

        {{-- ============================ CONSULT ============================ --}}
        @case('consult')
            <div class="fp-topbar">
                <div class="flex items-center gap-2">
                    <span class="fp-avatar">م</span>
                    <div class="flex flex-col">
                        <span class="fp-card-title leading-4">مشاور: استاد رضایی</span>
                        <span class="text-[9px] text-emerald-600 font-bold">● آنلاین</span>
                    </div>
                </div>
                <span class="fp-tab">اتاق مشاوره</span>
            </div>
            <div class="fp-body fp-chat">
                <div class="fp-msg fp-msg--in">سلام استاد، برای جمع‌بندی فیزیک وقت کم آوردم 😟</div>
                <div class="fp-msg fp-msg--out">سلام! نگران نباش. سه فصل آخر رو با تست‌های ترکیبی کار کن.</div>
                <div class="fp-msg fp-msg--out">یه برنامه‌ی ۱۰ روزه برات تنظیم کردم؛ توی داشبوردت گذاشتم.</div>
                <div class="fp-msg fp-msg--in">عالیه، ممنونم 🙏</div>
                <div class="fp-chat-input">
                    <span>پیامت را بنویس…</span>
                    <span class="fp-send">➤</span>
                </div>
            </div>
            @break

        {{-- ============================ EXAM ============================ --}}
        @case('exam')
            <div class="fp-topbar">
                <div class="flex items-center gap-2">
                    <span class="fp-dot" style="background:#ef4444"></span>
                    <span class="fp-dot" style="background:#f59e0b"></span>
                    <span class="fp-dot" style="background:#22c55e"></span>
                </div>
                <span class="fp-tab">آزمون آنلاین — ریاضی</span>
            </div>
            <div class="fp-body">
                <div class="flex items-center justify-between mb-2">
                    <span class="fp-badge">سؤال ۳ از ۲۰</span>
                    <span class="fp-timer">⏱ ۱۲:۴۵</span>
                </div>
                <div class="fp-q">حاصل عبارت مشتق تابع f(x)=۳x²+۲x در نقطه‌ی x=۱ کدام است؟</div>
                <div class="fp-options">
                    <span class="fp-opt">۶</span>
                    <span class="fp-opt fp-opt--on">۸ <i>✓</i></span>
                    <span class="fp-opt">۱۰</span>
                    <span class="fp-opt">۱۲</span>
                </div>
                <div class="fp-progress"><span style="width:15%"></span></div>
                <div class="flex items-center justify-between mt-2">
                    <span class="fp-skip">قبلی</span>
                    <span class="fp-next">سؤال بعدی</span>
                </div>
            </div>
            @break

        {{-- ============================ REPORT ============================ --}}
        @case('report')
            <div class="fp-topbar">
                <div class="flex items-center gap-2">
                    <span class="fp-dot" style="background:#ef4444"></span>
                    <span class="fp-dot" style="background:#f59e0b"></span>
                    <span class="fp-dot" style="background:#22c55e"></span>
                </div>
                <span class="fp-tab">کارنامه‌ی تحلیلی</span>
            </div>
            <div class="fp-body">
                <div class="flex items-stretch gap-3">
                    <div class="fp-donut">
                        <div class="fp-donut-ring"><span>۷۸٪</span></div>
                        <span class="fp-donut-cap">تراز کل</span>
                    </div>
                    <div class="flex-1 space-y-2">
                        @php
                            $subs = [['ریاضی','#3b82f6',82],['فیزیک','#8b5cf6',64],['شیمی','#06b6d4',75],['زیست','#10b981',88]];
                        @endphp
                        @foreach($subs as [$name,$c,$pct])
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="fp-sub-name">{{ $name }}</span>
                                    <span class="fp-sub-pct" style="color: {{ $c }}">{{ $pct }}٪</span>
                                </div>
                                <div class="fp-track"><span style="width: {{ $pct }}%; background: {{ $c }}"></span></div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="fp-note">نقطه‌ی قوت: زیست‌شناسی — پیشنهاد تمرکز: فیزیک (روند صعودی ▲)</div>
            </div>
            @break

    @endswitch
</div>
