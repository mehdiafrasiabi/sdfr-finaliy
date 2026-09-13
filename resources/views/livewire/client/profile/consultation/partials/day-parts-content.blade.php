<div>
    {{-- محتوای پارت‌های یک روز — استفاده در جدول هفتگی (دسکتاپ و موبایل) --}}
    @if($day['is_rest_day'])
        <div class="glass border border-success/20 rounded-2xl p-5 text-center">
            <div class="text-2xl mb-2">🌿</div>
            <div class="font-bold text-success mb-1">روز استراحت</div>
            <div class="text-xs text-muted">امروز نیازی به مطالعه نیست</div>
        </div>
    @elseif($day['parts_count'] > 0)
        <div class="space-y-2">
            @foreach($day['parts']->sortBy('part_order') as $part)
                @php
                    $isDone   = in_array($part->id, $completedParts);
                    $isMissed = !$isDone && $day['date'] < \Carbon\Carbon::today()->toDateString();

                    $typeStyle = $part->part_type === 'test'
                        ? 'bg-info/10 text-info border-info/30'
                        : ($part->part_type === 'descriptive'
                            ? 'bg-success/10 text-success border-success/30'
                            : 'bg-warning/10 text-warning border-warning/30');
                @endphp

                <div class="glass border rounded-xl p-3 transition-colors
                        {{ $isDone ? 'border-success/30' : ($isMissed ? 'border-error/30' : 'border-border') }}">
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
                                            <span class="inline-block rounded-md bg-warning/10 text-warning px-1.5 py-0.5 text-[10px] font-semibold">{{ $rc['name'] }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-[11px] text-muted mt-0.5">بدون فصل</p>
                                @endif
                            @elseif($pm === 'whole_book')
                                <p class="text-[11px] text-success mt-0.5">کل کتاب</p>
                            @elseif($part->ccChapter)
                                <p class="text-[11px] text-muted truncate mt-0.5">{{ $part->ccChapter->name }}</p>
                            @else
                                <p class="text-[11px] text-muted mt-0.5">بدون فصل</p>
                            @endif
                        </div>

                        {{-- آیکن وضعیت --}}
                        <div class="flex-shrink-0">
                            @if($isDone)
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-success/15">
                                    <x-ui.icon name="check" class="w-4 h-4 text-success"/>
                                </span>
                            @elseif($isMissed)
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-error/15">
                                    <x-ui.icon name="x" class="w-4 h-4 text-error"/>
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
