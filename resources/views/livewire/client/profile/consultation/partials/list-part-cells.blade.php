{{-- partials/list-part-cells.blade.php --}}
<td class="px-3 py-2.5 text-[11px] font-medium {{ $isExamRow ? 'text-red-700 dark:text-red-400' : 'text-foreground' }}">
    {{ $part->lesson_name }}
    @if($part->ccChapter)<span class="text-muted font-normal"> ({{ $part->ccChapter->name }})</span>@endif
</td>
<td class="px-3 py-2.5 text-[11px] text-muted max-w-[200px]">
    @if($part->ccTopic)
        <span class="text-primary font-medium">{{ $part->ccTopic->name }}</span>
        @if($part->description) - @endif
    @endif
    {{ Str::limit($part->description, 100) ?? '-' }}
</td>
<td class="px-3 py-2.5 text-center text-[11px] text-foreground whitespace-nowrap">{{ $part->duration_minutes }} دقیقه</td>
<td class="px-3 py-2.5 text-center text-[11px] text-foreground">{{ $part->test_count ?? '-' }}</td>
<td class="px-3 py-2.5 text-center">
    @if($part->part_type === 'test')
        <span class="rounded-full bg-sky-100 dark:bg-sky-900/50 px-2 py-0.5 text-[10px] font-medium text-sky-700 dark:text-sky-200">تستی</span>
    @elseif($part->part_type === 'descriptive')
        <span class="rounded-full bg-emerald-100 dark:bg-emerald-900/50 px-2 py-0.5 text-[10px] font-medium text-emerald-700 dark:text-emerald-200">تشریحی</span>
    @elseif($part->part_type === 'comprehensive_exam')
        <span class="rounded-full bg-red-100 dark:bg-red-900/50 px-2 py-0.5 text-[10px] font-medium text-red-700 dark:text-red-200">آزمون جامع</span>
    @elseif($part->part_type === 'exam_analysis')
        <span class="rounded-full bg-orange-100 dark:bg-orange-900/50 px-2 py-0.5 text-[10px] font-medium text-orange-700 dark:text-orange-200">تحلیل آزمون</span>
    @else
        <span class="rounded-full bg-violet-100 dark:bg-violet-900/50 px-2 py-0.5 text-[10px] font-medium text-violet-700 dark:text-violet-200">ویدیویی</span>
    @endif
</td>
<td class="px-3 py-2.5 text-center">
    @if($part->lesson_type === 'general')
        <span class="rounded-full bg-amber-100 dark:bg-amber-900/50 px-2 py-0.5 text-[10px] font-medium text-amber-700 dark:text-amber-200">عمومی</span>
    @else
        <span class="rounded-full bg-pink-100 dark:bg-pink-900/50 px-2 py-0.5 text-[10px] font-medium text-pink-700 dark:text-pink-200">تخصصی</span>
    @endif
</td>
<td class="px-3 py-2.5 text-center text-[11px] text-foreground">
    @if($part->grade == 10) دهم @elseif($part->grade == 11) یازدهم @elseif($part->grade == 12) دوازدهم @endif
</td>
<td class="px-3 py-2.5 text-center">
    @if($part->source_type && $part->source_type !== 'normal')
        <span class="rounded-full px-2 py-0.5 text-[10px] font-medium {{ $part->source_type_tw_class }}">{{ $part->source_type_label }}</span>
    @else
        <span class="text-[10px] text-muted">عادی</span>
    @endif
</td>
