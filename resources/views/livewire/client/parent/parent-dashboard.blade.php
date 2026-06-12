<div class="min-h-screen bg-[#0a0a0f] text-white relative overflow-hidden" dir="rtl">
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background-image: linear-gradient(to right, rgba(59,130,246,0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(59,130,246,0.05) 1px, transparent 1px); background-size: 48px 48px;"></div>
    <div class="fixed inset-0 pointer-events-none z-0"
         style="background: radial-gradient(ellipse 70% 50% at 50% 0%, rgba(59,130,246,0.1) 0%, transparent 70%);"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 py-8 space-y-6">

        {{-- هدر --}}
        <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
            <div class="h-1 w-full" style="background:linear-gradient(to left,#3b82f6,#8b5cf6,#ec4899);"></div>
            <div class="p-6 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0" style="background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.3);">
                        <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-black">{{ $this->parentRoleLabel }} گرامی، خوش آمدید</h1>
                        <p class="text-xs text-white/40 mt-1">
                            فرزند شما: <span class="text-blue-400 font-semibold">{{ $studentName }}</span>
                            @if($advisorName)
                                <span class="text-white/25 mx-1">|</span> مشاور: <span class="text-white/60">{{ $advisorName }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                <button wire:click="logout"
                        class="h-10 px-4 rounded-xl text-xs font-bold text-red-300 transition hover:scale-[1.03]"
                        style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);">
                    خروج از پنل
                </button>
            </div>
        </div>

        {{-- اطلاعات آخرین جلسه مشاوره --}}
        @if($sessionInfo)
            <div class="rounded-2xl p-5 flex flex-wrap items-center gap-x-8 gap-y-3" style="background:rgba(139,92,246,0.07);border:1px solid rgba(139,92,246,0.2);">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-xs text-white/50">آخرین جلسه مشاوره برگزارشده:</span>
                    <span class="text-sm font-bold text-violet-300">{{ $sessionInfo['date'] }}</span>
                    @if($sessionInfo['time'])
                        <span class="text-xs text-white/40">ساعت {{ $sessionInfo['time'] }}</span>
                    @endif
                </div>
                @if($sessionInfo['advisor'])
                    <div class="text-xs text-white/50">مشاور جلسه: <span class="text-white/80 font-semibold">{{ $sessionInfo['advisor'] }}</span></div>
                @endif
                <div class="text-xs text-white/50">نحوه برگزاری: <span class="text-white/80 font-semibold">{{ $sessionInfo['location'] }}</span></div>
                @if($data['week_range'])
                    <div class="text-xs text-white/50">بازه برنامه هفتگی: <span class="text-white/80 font-semibold">{{ $data['week_range'] }}</span></div>
                @endif
            </div>
        @else
            <div class="rounded-2xl p-5 text-center" style="background:rgba(245,158,11,0.07);border:1px solid rgba(245,158,11,0.2);">
                <p class="text-sm text-amber-300">هنوز جلسه مشاوره‌ای برای فرزند شما برگزار نشده است.</p>
            </div>
        @endif

        @if(!$data['has_program'])
            <div class="rounded-2xl p-10 text-center" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
                <p class="text-sm text-white/50 leading-7">برنامه هفتگی فعالی برای فرزند شما ثبت نشده است.<br>پس از برگزاری جلسه مشاوره و تنظیم برنامه، اطلاعات در این صفحه نمایش داده می‌شود.</p>
            </div>
        @else

            {{-- کارت‌های آمار هفته --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- ساعت مطالعه --}}
                <div class="rounded-2xl p-5 space-y-3" style="background:rgba(59,130,246,0.07);border:1px solid rgba(59,130,246,0.2);">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-white/60">ساعت مطالعه این هفته</span>
                        <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex items-end gap-1.5">
                        <span class="text-3xl font-black text-blue-300">{{ $data['study']['done_hours'] }}</span>
                        <span class="text-xs text-white/40 mb-1.5">از {{ $data['study']['planned_hours'] }} ساعت برنامه</span>
                    </div>
                    <div class="h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.08);">
                        <div class="h-full rounded-full" style="width:{{ $data['study']['percent'] }}%;background:linear-gradient(to left,#3b82f6,#60a5fa);"></div>
                    </div>
                    <p class="text-[11px] text-white/35">{{ $data['study']['percent'] }}٪ از برنامه انجام شده</p>
                </div>

                {{-- تعداد تست --}}
                <div class="rounded-2xl p-5 space-y-3" style="background:rgba(16,185,129,0.07);border:1px solid rgba(16,185,129,0.2);">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-white/60">تعداد تست این هفته</span>
                        <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex items-end gap-1.5">
                        <span class="text-3xl font-black text-emerald-300">{{ $data['tests']['done'] }}</span>
                        <span class="text-xs text-white/40 mb-1.5">از {{ $data['tests']['planned'] }} تست برنامه</span>
                    </div>
                    <div class="h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.08);">
                        <div class="h-full rounded-full" style="width:{{ $data['tests']['percent'] }}%;background:linear-gradient(to left,#10b981,#34d399);"></div>
                    </div>
                    <p class="text-[11px] text-white/35">{{ $data['tests']['percent'] }}٪ از تست‌های برنامه زده شده</p>
                </div>

                {{-- پارت تستی --}}
                <div class="rounded-2xl p-5 space-y-3" style="background:rgba(245,158,11,0.07);border:1px solid rgba(245,158,11,0.2);">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-white/60">پارت تستی این هفته</span>
                        <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
                    </div>
                    <div class="flex items-end gap-1.5">
                        <span class="text-3xl font-black text-amber-300">{{ $data['parts']['test'] }}</span>
                        <span class="text-xs text-white/40 mb-1.5">پارت از {{ $data['parts']['total'] }}</span>
                    </div>
                    <div class="h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.08);">
                        <div class="h-full rounded-full" style="width:{{ $data['parts']['test_percent'] }}%;background:linear-gradient(to left,#f59e0b,#fbbf24);"></div>
                    </div>
                    <p class="text-[11px] text-white/35">{{ $data['parts']['test_percent'] }}٪ از کل پارت‌های برنامه</p>
                </div>

                {{-- پارت تشریحی --}}
                <div class="rounded-2xl p-5 space-y-3" style="background:rgba(236,72,153,0.07);border:1px solid rgba(236,72,153,0.2);">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-white/60">پارت تشریحی این هفته</span>
                        <svg class="w-5 h-5 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    </div>
                    <div class="flex items-end gap-1.5">
                        <span class="text-3xl font-black text-pink-300">{{ $data['parts']['descriptive'] }}</span>
                        <span class="text-xs text-white/40 mb-1.5">پارت از {{ $data['parts']['total'] }}</span>
                    </div>
                    <div class="h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.08);">
                        <div class="h-full rounded-full" style="width:{{ $data['parts']['descriptive_percent'] }}%;background:linear-gradient(to left,#ec4899,#f472b6);"></div>
                    </div>
                    <p class="text-[11px] text-white/35">{{ $data['parts']['descriptive_percent'] }}٪ از کل پارت‌های برنامه</p>
                </div>
            </div>

            {{-- گزارش‌های ارسالی --}}
            <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
                <div class="p-5 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(59,130,246,0.15);">
                        <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 12h3.75m-3.75 3h3.75M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-black">گزارش‌های ارسالی این هفته</h2>
                        <p class="text-[11px] text-white/35 mt-0.5">{{ count($data['reports']) }} گزارش ثبت شده</p>
                    </div>
                </div>
                @if(count($data['reports']) === 0)
                    <p class="p-6 text-center text-xs text-white/35">در این هفته هنوز گزارشی ارسال نشده است.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead>
                            <tr class="text-white/40" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                                <th class="py-3 px-4 font-bold text-right">روز</th>
                                <th class="py-3 px-4 font-bold text-right">تاریخ</th>
                                <th class="py-3 px-4 font-bold text-right">پارت‌های مطالعه‌شده</th>
                                <th class="py-3 px-4 font-bold text-right">تست</th>
                                <th class="py-3 px-4 font-bold text-right">کار با موبایل</th>
                                <th class="py-3 px-4 font-bold text-right">کیفیت روز</th>
                                <th class="py-3 px-4 font-bold text-right">وضعیت</th>
                                <th class="py-3 px-4 font-bold text-right">نظر مشاور</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data['reports'] as $report)
                                <tr class="text-white/70" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                                    <td class="py-3 px-4 font-semibold">
                                        {{ $report['day'] }}
                                        @if($report['is_compensatory'])
                                            <span class="text-[10px] text-amber-300 mr-1">(جبرانی)</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">{{ $report['date'] }}</td>
                                    <td class="py-3 px-4">{{ $report['read_parts'] }} از {{ $report['total_parts'] }}</td>
                                    <td class="py-3 px-4">{{ $report['tests'] }}</td>
                                    <td class="py-3 px-4">{{ $report['phone_hours'] }} ساعت</td>
                                    <td class="py-3 px-4">{{ $report['rating_label'] }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 rounded-md text-[10px] font-bold
                                            @if($report['status'] === 'approved') text-emerald-300 @elseif($report['status'] === 'rejected') text-red-300 @else text-amber-300 @endif"
                                              style="background:rgba(255,255,255,0.06);">
                                            {{ $report['status_label'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-white/50 max-w-[200px]">{{ $report['advisor_comment'] ?: '—' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- امتحانات / پرسش و پاسخ / تکالیف --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                @foreach([
                    ['title' => 'امتحانات این هفته', 'items' => $data['exams'], 'color' => 'amber', 'rgb' => '245,158,11', 'empty' => 'امتحانی برای این هفته ثبت نشده است.'],
                    ['title' => 'پرسش و پاسخ این هفته', 'items' => $data['qa'], 'color' => 'cyan', 'rgb' => '6,182,212', 'empty' => 'پرسش و پاسخی برای این هفته ثبت نشده است.'],
                    ['title' => 'تکالیف این هفته', 'items' => $data['homework'], 'color' => 'red', 'rgb' => '239,68,68', 'empty' => 'تکلیفی برای این هفته ثبت نشده است.'],
                ] as $section)
                    <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.08);">
                        <div class="p-4 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.06);">
                            <h2 class="text-sm font-black">{{ $section['title'] }}</h2>
                            <span class="text-[10px] font-bold px-2 py-1 rounded-md text-{{ $section['color'] }}-300" style="background:rgba({{ $section['rgb'] }},0.12);">
                                {{ count($section['items']) }} مورد
                            </span>
                        </div>
                        @if(count($section['items']) === 0)
                            <p class="p-5 text-center text-xs text-white/35">{{ $section['empty'] }}</p>
                        @else
                            <div class="divide-y" style="--tw-divide-opacity:0;">
                                @foreach($section['items'] as $item)
                                    <div class="p-4 space-y-2" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs font-bold text-white/80">{{ $item['lesson'] }}</span>
                                            @if($item['is_done'])
                                                <span class="text-[10px] font-bold text-emerald-300 px-2 py-0.5 rounded-md flex-shrink-0" style="background:rgba(16,185,129,0.12);">انجام شد</span>
                                            @else
                                                <span class="text-[10px] font-bold text-white/35 px-2 py-0.5 rounded-md flex-shrink-0" style="background:rgba(255,255,255,0.06);">انجام نشده</span>
                                            @endif
                                        </div>
                                        @if($item['chapter'])
                                            <p class="text-[11px] text-white/40">{{ $item['chapter'] }}</p>
                                        @endif
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] text-white/40">
                                            <span>{{ $item['day'] }}@if($item['date']) — {{ $item['date'] }}@endif</span>
                                            <span>{{ $item['type_label'] }}</span>
                                            @if($item['duration_minutes'] > 0)
                                                <span>{{ $item['duration_minutes'] }} دقیقه</span>
                                            @endif
                                            @if($item['test_count'] > 0)
                                                <span>{{ $item['test_count'] }} تست</span>
                                            @endif
                                            @if($item['is_done'] && $item['studied_minutes'] > 0)
                                                <span class="text-emerald-300/70">{{ $item['studied_minutes'] }} دقیقه مطالعه شد</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

        @endif

        <p class="text-center text-[11px] text-white/25 pb-4">این اطلاعات بر اساس آخرین جلسه مشاوره برگزارشده و برنامه هفتگی تنظیم‌شده توسط مشاور نمایش داده می‌شود.</p>
    </div>
</div>
