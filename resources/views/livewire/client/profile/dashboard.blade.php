<div style="background: #0d0d0d; min-height: 100vh; color: #fff; font-family: inherit;" dir="rtl">
    <livewire:client.profile.update-notification />

    @push('styles')
        <style>
            .scrollbar-hide::-webkit-scrollbar { display: none; }
            .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        </style>
    @endpush

    {{-- ستاره‌های پس‌زمینه --}}
    <canvas id="dashboard-stars"
            style="position:fixed; bottom:0; left:0; width:100%; height:45%;
                   pointer-events:none; z-index:0; opacity:0.55;">
    </canvas>

    {{-- LAYOUT: sidebar + main --}}
    <div class="max-w-7xl mx-auto px-4 py-6" style="position:relative; z-index:1;">
        <div class="flex gap-6 items-start">

            {{-- ===== SIDEBAR ===== --}}
            <div class="hidden md:block flex-shrink-0" style="width: 260px; position: sticky; top: 24px;">
                <livewire:client.profile.sidebar/>
            </div>

            {{-- ===== MAIN CONTENT ===== --}}
            <div class="flex-1 min-w-0 space-y-0">

                {{-- Trial/Notification Banners --}}
                @php
                    $trialWeek = \App\Models\TrialWeek::where('user_id', $user->id)->latest()->first();
                    $isTrialStudent = $student && $student->is_trial;
                    $showTrialBanner = !$student || $isTrialStudent;
                @endphp

                @if($showTrialBanner && !$trialWeek)
                    <div class="rounded-2xl p-4 mb-5" style="background: #0f2218; border: 1px solid #193326;">
                        <div class="flex items-center justify-between gap-3">
                            <livewire:client.profile.trial-week.start />
                            <div class="text-right">
                                <div class="font-bold text-white text-sm">یک هفته آزمایشی رایگان</div>
                                <div class="text-xs mt-1" style="color: #4ade80; opacity: 0.8;">برنامه شخصی • پشتیبان اختصاصی</div>
                            </div>
                        </div>
                    </div>
                @elseif($trialWeek)
                    <a wire:navigate href="{{ route('client.profile.trial.guide') }}"
                       class="flex items-center justify-between p-4 rounded-2xl mb-5"
                       style="background: #0f2218; border: 1px solid #193326;">
                        <div class="flex items-center gap-2">
                            @php $sp = ($trialWeek->step / 4) * 100; @endphp
                            <div class="w-14 h-1 rounded-full overflow-hidden" style="background: #1e3a2e;">
                                <div class="h-full rounded-full" style="width:{{ $sp }}%; background:#4ade80;"></div>
                            </div>
                            <span class="text-xs" style="color:#4ade80;">{{ (int)$sp }}%</span>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold text-white text-sm">هفته آزمایشی — {{ $trialWeek->statusLabel }}</div>
                            <div class="text-xs mt-0.5" style="color:#555;">
                                @if($trialWeek->isExpired())<span style="color:#f87171;">منقضی شده</span>
                                @else{{ $trialWeek->daysRemaining }} روز باقی‌مانده@endif
                            </div>
                        </div>
                    </a>
                @endif

                @if($student && !$isTrialStudent && $unreadNotificationsCount > 0)
                    <a wire:navigate href="{{ route('client.profile.notification') }}"
                       class="flex items-center justify-between p-4 rounded-2xl mb-5"
                       style="background: #0f2218; border: 1px solid #193326;">
                        <span class="font-bold text-sm" style="color:#4ade80;">{{ $unreadNotificationsCount }} پیام خوانده نشده</span>
                        <span class="text-sm text-white">مشاهده پیام‌ها ←</span>
                    </a>
                @endif

                {{-- ===== مشاور و پشتیبان ===== --}}
                <div class="grid grid-cols-2 gap-4 mb-1 py-5" style="border-bottom: 1px solid #1a1a1a;">

                    {{-- مشاور --}}
                    <div class="rounded-2xl p-5 flex flex-col items-center text-center gap-3"
                         style="background: #111; border: 1px solid #1e1e1e;">
                        @if($advisorStudent && $advisorStudent->picture)
                            <img src="{{ asset('adminsFile/' . $advisorStudent->id . '/' . $advisorStudent->picture) }}"
                                 alt="{{ $advisorStudent->name }}"
                                 class="w-14 h-14 rounded-full object-cover"
                                 style="border: 2px solid #252525;">
                        @else
                            <div class="w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0"
                                 style="background: #1c1c1c; border: 2px solid #252525;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#555" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5z"/>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <div class="font-bold text-white text-sm">{{ $advisorStudent ? $advisorStudent->name : 'تعیین نشده' }}</div>
                            <div class="text-xs mt-0.5" style="color: #4a9eff;">مشاور شما</div>
                        </div>
                    </div>

                    {{-- پشتیبان --}}
                    <div class="rounded-2xl p-5 flex flex-col items-center text-center gap-3"
                         style="background: #111; border: 1px solid #1e1e1e;">
                        @if($supporterStudent && $supporterStudent->picture)
                            <img src="{{ asset('adminsFile/' . $supporterStudent->id . '/' . $supporterStudent->picture) }}"
                                 alt="{{ $supporterStudent->name }}"
                                 class="w-14 h-14 rounded-full object-cover"
                                 style="border: 2px solid #252525;">
                        @else
                            <div class="w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0"
                                 style="background: #1c1c1c; border: 2px solid #252525;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#555" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        @endif
                        <div>
                            <div class="font-bold text-white text-sm">{{ $supporterStudent ? $supporterStudent->name : 'تعیین نشده' }}</div>
                            <div class="text-xs mt-0.5" style="color: #4a9eff;">پشتیبان شما</div>
                        </div>
                    </div>
                </div>

                {{-- ===== ارسال گزارش ===== --}}
                <div class="py-5" style="border-bottom: 1px solid #1a1a1a;">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-xl font-black" style="color: #4a9eff;">
                            {{ $reportProgress['submitted_days'] }}/{{ $reportProgress['total_days'] }}
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-white" style="font-size: 15px;">ارسال گزارش</span>
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: #1c1c1c;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#666" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    @php
                        $activeProgram = $this->getActiveWeeklyProgram();
                        $startDay = 21;
                        if($activeProgram) {
                            $startDay = \Carbon\Carbon::parse($activeProgram->start_date)->day;
                        }
                        $todayDay = \Carbon\Carbon::today()->day;
                    @endphp
                    <div class="flex items-center gap-2">
                        @for($i = 0; $i < 7; $i++)
                            @php
                                $dayNum = $startDay + $i;
                                $isSubmitted = $i < $reportProgress['submitted_days'];
                                $isToday = ($dayNum == $todayDay);
                            @endphp
                            <div class="flex-1 aspect-square rounded-full flex items-center justify-center font-bold"
                                 style="font-size: 13px;
                                 @if($isToday) background: #3a1a1a; border: 2px solid #8b2020; color: #fff;
                                 @elseif($isSubmitted) background: #0f2a1a; border: 2px solid #1e5c35; color: #4ade80;
                                 @else background: #151515; border: 2px solid #1e1e1e; color: #444;
                                 @endif">
                                {{ $dayNum }}
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- ===== ساعت مطالعه ===== --}}
                <div class="py-5" style="border-bottom: 1px solid #1a1a1a;">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-xl font-black" style="color: #4a9eff;">
                            {{ $studyHoursProgress['total_hours'] }} ساعت
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-white" style="font-size: 15px;">ساعت مطالعه</span>
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: #1c1c1c;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#666" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full rounded-full mb-3" style="height: 3px; background: #1c1c1c;">
                        <div class="h-full rounded-full" style="width: {{ $studyHoursProgress['percentage'] }}%; background: linear-gradient(to left, #7b5ea7, #4a9eff); transition: width 0.7s;"></div>
                    </div>
                    <div class="flex items-center justify-between" style="font-size: 13px;">
                        <div style="color: #4a9eff;">{{ $studyHoursProgress['completed_hours'] }} از {{ $studyHoursProgress['total_hours'] }}</div>
                        <div style="color: #444;">{{ round($studyHoursProgress['percentage']) }}%</div>
                    </div>
                    @if($studyHoursProgress['extra_hours'] > 0)
                        <div class="mt-3 text-xs font-semibold px-3 py-2 rounded-xl inline-flex items-center gap-1.5" style="background: #0f2a1a; color: #4ade80;">
                            ⬆ {{ $studyHoursProgress['extra_hours'] }} ساعت اضافی! عالی هستی 🎉
                        </div>
                    @endif
                </div>

                {{-- ===== برنامه امروز ===== --}}
                <div class="py-5" style="border-bottom: 1px solid #1a1a1a;">
                    <div class="flex items-center justify-end gap-2 mb-4">
                        <span class="font-bold text-white" style="font-size: 15px;">برنامه امروز</span>
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: #1c1c1c;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#666" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                            </svg>
                        </div>
                    </div>

                    @if(count($todayProgram) > 0)
                        <div class="hidden md:grid gap-3" style="grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));">
                            @foreach($todayProgram as $index => $part)
                                <div class="rounded-2xl p-4 flex flex-col justify-between"
                                     style="background: #141414; border: 1px solid #222; min-height: 110px;">
                                    <div class="font-bold text-white leading-tight" style="font-size: 14px;">{{ $part->lesson->name ?? 'درس' }}</div>
                                    @if($part->ccSubject)
                                        <div class="text-xs mt-1" style="color: #555;">{{ $part->ccSubject->name }}</div>
                                    @endif
                                    <div class="flex items-end justify-between mt-3 pt-3" style="border-top: 1px solid #222;">
                                        @if($part->test_count)
                                            <div>
                                                <div class="font-black text-white" style="font-size: 18px; line-height: 1;">{{ $part->test_count }}</div>
                                                <div class="text-xs" style="color: #555;">تست</div>
                                            </div>
                                        @else<div></div>@endif
                                        <div>
                                            <div class="font-black text-white" style="font-size: 18px; line-height: 1; direction: ltr;">{{ $part->duration_minutes ?? round($part->duration_hours * 60) }}</div>
                                            <div class="text-xs" style="color: #555;">دقیقه</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex md:hidden gap-3 overflow-x-auto pb-1 scrollbar-hide" style="margin: 0 -5px; padding: 0 5px;">
                            @foreach($todayProgram as $index => $part)
                                <div class="flex-shrink-0 rounded-2xl p-4 flex flex-col justify-between snap-start"
                                     style="background: #141414; border: 1px solid #222; min-width: 148px; min-height: 110px;">
                                    <div class="font-bold text-white leading-tight" style="font-size: 14px;">{{ $part->lesson->name ?? 'درس' }}</div>
                                    @if($part->ccSubject)
                                        <div class="text-xs mt-1" style="color: #555;">{{ $part->ccSubject->name }}</div>
                                    @endif
                                    <div class="flex items-end justify-between mt-3 pt-3" style="border-top: 1px solid #222;">
                                        @if($part->test_count)
                                            <div>
                                                <div class="font-black text-white" style="font-size: 18px; line-height: 1;">{{ $part->test_count }}</div>
                                                <div class="text-xs" style="color: #555;">تست</div>
                                            </div>
                                        @else<div></div>@endif
                                        <div>
                                            <div class="font-black text-white" style="font-size: 18px; line-height: 1; direction: ltr;">{{ $part->duration_minutes ?? round($part->duration_hours * 60) }}</div>
                                            <div class="text-xs" style="color: #555;">دقیقه</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8" style="color: #333; font-size: 13px;">برنامه‌ای برای امروز تعریف نشده</div>
                    @endif
                </div>

                {{-- ===== میانگین ساعت مطالعه ===== --}}
                <div class="py-5" style="border-bottom: 1px solid #1a1a1a;">
                    <div class="flex items-center justify-end gap-2 mb-4">
                        <span class="font-bold text-white" style="font-size: 15px;">میانگین ساعت مطالعه</span>
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: #1c1c1c;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#666" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                            </svg>
                        </div>
                    </div>
                    <div style="height: 110px; position: relative;">
                        <svg viewBox="0 0 300 90" class="w-full" style="height: 90px;" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="blueGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#4a9eff" stop-opacity="0.25"/>
                                    <stop offset="100%" stop-color="#4a9eff" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                            <path d="M0,78 C25,72 45,58 75,52 C105,46 125,62 155,42 C185,22 215,32 248,18 C265,11 285,5 300,2 L300,90 L0,90 Z" fill="url(#blueGrad)"/>
                            <path d="M0,78 C25,72 45,58 75,52 C105,46 125,62 155,42 C185,22 215,32 248,18 C265,11 285,5 300,2" fill="none" stroke="#4a9eff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="0"   cy="78" r="2.5" fill="#4a9eff"/>
                            <circle cx="75"  cy="52" r="2.5" fill="#4a9eff"/>
                            <circle cx="155" cy="42" r="2.5" fill="#4a9eff"/>
                            <circle cx="248" cy="18" r="2.5" fill="#4a9eff"/>
                            <circle cx="300" cy="2"  r="2.5" fill="#4a9eff"/>
                        </svg>
                        @if($activeProgram)
                            <div class="flex justify-between mt-1">
                                @php $sd = \Carbon\Carbon::parse($activeProgram->start_date); @endphp
                                @for($i = 0; $i < 6; $i++)
                                    <span style="font-size: 11px; color: #444;">{{ $sd->copy()->addDays($i)->day }}</span>
                                @endfor
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ===== پیشرفت درصد آزمون ===== --}}
                <div class="py-5" style="padding-bottom: 80px;">
                    <div class="flex items-center justify-end gap-2 mb-4">
                        <span class="font-bold text-white" style="font-size: 15px;">پیشرفت درصد آزمون</span>
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: #1c1c1c;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#666" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/>
                            </svg>
                        </div>
                    </div>
                    <div style="height: 90px;">
                        <svg viewBox="0 0 300 80" class="w-full" style="height: 80px;" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="yellowGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.3"/>
                                    <stop offset="100%" stop-color="#f59e0b" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                            <path d="M0,65 C35,60 55,72 95,55 C135,38 155,68 195,45 C225,27 258,42 300,18 L300,80 L0,80 Z" fill="url(#yellowGrad)"/>
                            <path d="M0,65 C35,60 55,72 95,55 C135,38 155,68 195,45 C225,27 258,42 300,18" fill="none" stroke="#f59e0b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

            </div>{{-- end main --}}
        </div>{{-- end flex --}}
    </div>{{-- end container --}}

    {{-- ===== انیمیشن ستاره‌های دنباله‌دار ===== --}}
    @push('script')
        <script>
            (function() {
                const canvas = document.getElementById('dashboard-stars');
                if (!canvas) return;
                const ctx = canvas.getContext('2d');

                function resize() {
                    canvas.width  = canvas.offsetWidth  * (window.devicePixelRatio || 1);
                    canvas.height = canvas.offsetHeight * (window.devicePixelRatio || 1);
                    ctx.scale(window.devicePixelRatio || 1, window.devicePixelRatio || 1);
                }
                resize();
                window.addEventListener('resize', resize);

                const W = () => canvas.offsetWidth;
                const H = () => canvas.offsetHeight;

                /* ستاره‌های ثابت */
                const BG_STARS = Array.from({length: 140}, () => ({
                    x: Math.random(),
                    y: Math.random(),
                    r: Math.random() * 1.1 + 0.2,
                    alpha: Math.random() * 0.5 + 0.1,
                    twinkle: Math.random() * Math.PI * 2,
                    speed: Math.random() * 0.018 + 0.004,
                }));

                /* کلاس ستاره دنباله‌دار */
                function Comet() { this.reset(true); }
                Comet.prototype.reset = function(init) {
                    var w = W(), h = H();
                    this.x  = init ? Math.random() * w : -100;
                    this.y  = init ? Math.random() * h * 0.7 : Math.random() * h * 0.5;
                    this.vx = 3.2 + Math.random() * 2.8;
                    this.vy = 1.0 + Math.random() * 1.8;
                    this.len = 80 + Math.random() * 70;
                    this.alpha = 0;
                    this.life = 0;
                    this.maxLife = 150 + Math.random() * 100;
                    this.r = 1.6 + Math.random() * 1.2;
                    this.col = Math.random() > 0.45 ? '150,200,255' : '210,225,255';
                };
                Comet.prototype.update = function() {
                    this.x += this.vx;
                    this.y += this.vy;
                    this.life++;
                    var fade = 30;
                    if (this.life < fade)             this.alpha = this.life / fade;
                    else if (this.life > this.maxLife - fade) this.alpha = Math.max(0, (this.maxLife - this.life) / fade);
                    else                              this.alpha = 1;
                    if (this.x > W() + 120 || this.y > H() + 60) this.reset(false);
                };
                Comet.prototype.draw = function() {
                    var angle = Math.atan2(this.vy, this.vx);
                    var tx = this.x - this.len * Math.cos(angle);
                    var ty = this.y - this.len * Math.sin(angle);
                    var grd = ctx.createLinearGradient(tx, ty, this.x, this.y);
                    grd.addColorStop(0,   'rgba(' + this.col + ',0)');
                    grd.addColorStop(0.5, 'rgba(' + this.col + ',' + (this.alpha * 0.25) + ')');
                    grd.addColorStop(1,   'rgba(' + this.col + ',' + this.alpha + ')');
                    ctx.save();
                    ctx.strokeStyle = grd;
                    ctx.lineWidth   = this.r;
                    ctx.lineCap     = 'round';
                    ctx.beginPath();
                    ctx.moveTo(tx, ty);
                    ctx.lineTo(this.x, this.y);
                    ctx.stroke();
                    /* هاله */
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.r * 2, 0, Math.PI * 2);
                    ctx.fillStyle = 'rgba(210,230,255,' + (this.alpha * 0.7) + ')';
                    ctx.fill();
                    /* نقطه مرکزی */
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.r * 0.7, 0, Math.PI * 2);
                    ctx.fillStyle = 'rgba(255,255,255,' + this.alpha + ')';
                    ctx.fill();
                    ctx.restore();
                };

                /* جرقه‌ها */
                function Spark(x, y) {
                    this.x = x; this.y = y;
                    this.vx = (Math.random() - 0.5) * 1.4;
                    this.vy = (Math.random() - 0.5) * 1.4 - 0.4;
                    this.life = 0;
                    this.maxLife = 20 + Math.random() * 18;
                    this.r = 0.6 + Math.random() * 1.0;
                }
                Spark.prototype.update = function() { this.x += this.vx; this.y += this.vy; this.vy += 0.05; this.life++; };
                Spark.prototype.draw  = function() {
                    var a = (1 - this.life / this.maxLife) * 0.7;
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
                    ctx.fillStyle = 'rgba(180,215,255,' + a + ')';
                    ctx.fill();
                };

                var comets = [new Comet(), new Comet()];
                var sparks  = [];
                var frame   = 0;
                var nextComet = 100;

                function loop() {
                    var w = W(), h = H();
                    ctx.clearRect(0, 0, w, h);
                    frame++;

                    /* ستاره‌های ثابت */
                    BG_STARS.forEach(function(s) {
                        s.twinkle += s.speed;
                        var a = s.alpha * (0.55 + 0.45 * Math.sin(s.twinkle));
                        ctx.beginPath();
                        ctx.arc(s.x * w, s.y * h, s.r, 0, Math.PI * 2);
                        ctx.fillStyle = 'rgba(200,220,255,' + a + ')';
                        ctx.fill();
                    });

                    /* ستاره‌های دنباله‌دار */
                    if (frame >= nextComet) {
                        comets.push(new Comet());
                        nextComet = frame + 80 + Math.random() * 140;
                    }
                    comets.forEach(function(c) {
                        c.update(); c.draw();
                        if (Math.random() < 0.35) sparks.push(new Spark(c.x, c.y));
                    });

                    /* جرقه‌ها */
                    sparks.forEach(function(s) { s.update(); s.draw(); });
                    sparks = sparks.filter(function(s) { return s.life < s.maxLife; });

                    requestAnimationFrame(loop);
                }
                loop();
            })();
        </script>
    @endpush

</div>
