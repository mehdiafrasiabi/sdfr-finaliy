@props([
    'isEarlyFinish' => false,
    'extraSeconds' => 0,
    'extraTargetSeconds' => 0,
    'isCheating' => false,
    'cheatStatus' => null,
    'cheatMinutes' => 0,
    'style' => 'tailwind',
])
@php
    $extraMin    = (int) round(((int)$extraSeconds) / 60);
    $extraTarget = (int) $extraTargetSeconds;
    $isExtraEarly = $extraSeconds > 0 && $extraTarget > 0 && $extraSeconds < $extraTarget;
    $hasAny = (bool)$isEarlyFinish || $extraMin > 0 || (bool)$isCheating;

    $cheatBgBootstrap = match ($cheatStatus) {
        'pending'  => 'background:#fff3cd;color:#856404;',
        'approved' => 'background:#cce5ff;color:#004085;',
        'rejected' => 'background:#f8d7da;color:#721c24;',
        default    => 'background:#f8d7da;color:#721c24;',
    };
    $cheatBgTailwind = match ($cheatStatus) {
        'pending'  => 'background:#3a2000;color:#fbbf24;',
        'approved' => 'background:#0d2a4a;color:#60a5fa;',
        'rejected' => 'background:#3a1a1a;color:#f87171;',
        default    => 'background:#3a1a1a;color:#f87171;',
    };
    $cheatLabel = match ($cheatStatus) {
        'pending'  => 'تقلب (در انتظار تایید)',
        'approved' => 'تقلب تایید شده',
        'rejected' => 'تقلب رد شده',
        default    => 'تقلب',
    };
@endphp
@if($hasAny)
    @if($style === 'bootstrap')
        <span class="d-inline-flex flex-wrap align-items-center" style="gap:.25rem;">
            @if($isEarlyFinish)
                <span class="badge rounded-pill text-bg-success" style="font-size:10px;">زودتر تمام شد</span>
            @endif
            @if($extraMin > 0)
                @if($isExtraEarly)
                    <span class="badge rounded-pill" style="background:#ddd6fe;color:#5b21b6;font-size:10px;">
                        زودتر در اضافه بر مشاور
                    </span>
                @endif
                <span class="badge rounded-pill" style="background:#ede9fe;color:#7c3aed;font-size:10px;">
                    +{{ $extraMin }} دقیقه اضافه بر مشاور
                </span>
            @endif
            @if($isCheating)
                <span class="badge rounded-pill" style="{{ $cheatBgBootstrap }}font-size:10px;">
                    ⚠ {{ $cheatLabel }}@if($cheatMinutes > 0) — {{ $cheatMinutes }} د تأخیر @endif
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
                @if($isExtraEarly)
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full"
                          style="background:#1a0a2e;color:#c4b5fd;">زودتر در اضافه بر مشاور</span>
                @endif
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full"
                      style="background:#2d1b69;color:#c4b5fd;">+{{ $extraMin }} دقیقه اضافه بر مشاور</span>
            @endif
            @if($isCheating)
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full"
                      style="{{ $cheatBgTailwind }}">⚠ {{ $cheatLabel }}@if($cheatMinutes > 0) — {{ $cheatMinutes }} د تأخیر @endif</span>
            @endif
        </span>
    @endif
@endif
