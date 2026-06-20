<div>
    {{-- محتوای پارت‌های یک روز — استفاده در جدول هفتگی (دسکتاپ و موبایل) --}}
    @if($day['is_rest_day'])
        <div class="glass border border-emerald-500/20 rounded-2xl p-5 text-center">
            <div class="text-2xl mb-2">🌿</div>
            <div class="font-bold text-emerald-500 mb-1">روز استراحت</div>
            <div class="text-xs text-muted">امروز نیازی به مطالعه نیست</div>
        </div>
    @elseif($day['parts_count'] > 0)
        <div class="space-y-2">
            @foreach($day['parts']->sortBy('part_order') as $part)
                @php
                    $isDone   = in_array($part->id, $completedParts);
                    $isMissed = !$isDone && $day['date'] < \Carbon\Carbon::today()->toDateString();

                    $typeStyle = $part->part_type === 'test'
                        ? 'bg-sky-500/10 text-sky-500 border-sky-500/30'
                        : ($part->part_type === 'descriptive'
                            ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/30'
                            : 'bg-amber-500/10 text-amber-500 border-amber-500/30');
                @endphp

                <div class="glass border rounded-xl p-3 transition-colors
                        {{ $isDone ? 'border-emerald-500/30' : ($isMissed ? 'border-red-500/30' : 'border-border') }}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <h4 class="font-bold text-foreground text-sm truncate">{{ $part->lesson_name }}</h4>
                            @php $pm = $part->part_mode ?? 'normal'; @endphp
                            @if($pm === 'review' && $part->ccSubject)
                                <p class="text-[11px] text-muted truncate mt-0.5">{{ $part->ccSubject->name }}</p>
                            @endif
                            @if($pm === 'review')
                                @php $rcs = collect($part->review_chapters ?? []); @endphp
                                @if($rcs->count())
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($rcs as $rc)
                                            <span class="inline-block rounded-md bg-amber-500/10 text-amber-600 px-1.5 py-0.5 text-[10px] font-semibold">{{ $rc['name'] }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-[11px] text-muted mt-0.5">بدون فصل</p>
                                @endif
                            @elseif($pm === 'whole_book')
                                <p class="text-[11px] text-emerald-600 mt-0.5">کل کتاب</p>
                            @elseif($part->ccChapter)
                                <p class="text-[11px] text-muted truncate mt-0.5">{{ $part->ccChapter->name }}</p>
                            @else
                                <p class="text-[11px] text-muted mt-0.5">بدون فصل</p>
                            @endif
                        </div>

                        {{-- آیکن وضعیت --}}
                        <div class="flex-shrink-0">
                            @if($isDone)
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-emerald-500/15">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </span>
                            @elseif($isMissed)
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-red-500/15">
                                <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 flex-wrap mt-2">
                    <span class="inline-flex items-center gap-1 rounded-md border px-1.5 py-0.5 text-[10px] font-semibold {{ $typeStyle }}">
                        {{ $part->part_type_label }}
                    </span>
                        <span class="inline-flex items-center gap-1 rounded-md bg-background border border-border px-1.5 py-0.5 text-[10px] font-semibold text-foreground">
                        {{ $part->duration_minutes }} دقیقه
                    </span>
                        @if($part->test_count)
                            <span class="inline-flex items-center gap-1 rounded-md bg-background border border-border px-1.5 py-0.5 text-[10px] font-semibold text-foreground">
                            {{ $part->test_count }} تست
                        </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="glass border border-border rounded-2xl p-5 text-center">
            <div class="text-xs text-muted">برنامه‌ای برای این روز تنظیم نشده</div>
        </div>
    @endif
</div>
