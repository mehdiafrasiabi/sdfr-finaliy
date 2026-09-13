@php $pm = $part->part_mode ?? 'normal'; @endphp
@if($pm === 'whole_book')
    <span class="font-normal text-muted">(کل کتاب)</span>
@elseif($pm === 'review')
    <span class="font-normal text-muted">({{ collect($part->review_chapters ?? [])->pluck('name')->implode('، ') ?: 'پارت مروری' }})</span>
@elseif($part->ccChapter)
    <span class="font-normal text-muted">({{ $part->ccChapter->name }})</span>
@else
    <span class="font-normal text-muted">(بدون فصل)</span>
@endif
