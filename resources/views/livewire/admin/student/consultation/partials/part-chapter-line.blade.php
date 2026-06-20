@php $pm = $part->part_mode ?? 'normal'; @endphp
<div class="small d-flex align-items-start gap-1 mb-1">
    <i class="material-symbols-outlined text-muted" style="font-size:13px;">menu_book</i>
    <span class="flex-fill">
        @if($pm === 'review')
            @php $rcs = collect($part->review_chapters ?? []); @endphp
            @if($rcs->count())
                @foreach($rcs as $rc)
                    <span class="badge bg-warning-subtle text-warning" style="font-size:10px;">{{ $rc['name'] }}</span>
                @endforeach
            @else
                <span class="text-muted">بدون فصل</span>
            @endif
        @elseif($pm === 'whole_book')
            <span class="text-success">کل کتاب</span>
        @elseif($part->ccChapter)
            {{ $part->ccChapter->name }}
        @else
            <span class="text-muted">بدون فصل</span>
        @endif
    </span>
</div>
