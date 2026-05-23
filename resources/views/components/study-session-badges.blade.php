@props([
    'isEarlyFinish' => false,
    'extraSeconds' => 0,
    'style' => 'tailwind',
])
@php
    $extraMin = (int) round(((int)$extraSeconds) / 60);
    $hasAny = (bool)$isEarlyFinish || $extraMin > 0;
@endphp
@if($hasAny)
    @if($style === 'bootstrap')
        <span class="d-inline-flex flex-wrap align-items-center" style="gap:.25rem;">
            @if($isEarlyFinish)
                <span class="badge rounded-pill text-bg-success" style="font-size:10px;">زودتر تمام شد</span>
            @endif
            @if($extraMin > 0)
                <span class="badge rounded-pill" style="background:#ede9fe;color:#7c3aed;font-size:10px;">
                    +{{ $extraMin }} دقیقه اضافه بر مشاور
                </span>
            @endif
        </span>
    @else
        <span class="inline-flex flex-wrap items-center" style="gap:.25rem;">
            @if($isEarlyFinish)
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full"
                      style="background:#0f2a1a;color:#4ade80;">زودتر تمام شد</span>
            @endif
            @if($extraMin > 0)
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full"
                      style="background:#2d1b69;color:#c4b5fd;">+{{ $extraMin }} دقیقه اضافه بر مشاور</span>
            @endif
        </span>
    @endif
@endif
