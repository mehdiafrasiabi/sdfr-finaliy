@props([
    'color' => '#7dd3fc',
    'count' => 16,
])

@php
    // الگوهای ثابت (deterministic) برای تنوعِ پایدار
    $tops   = [6, 14, 22, 9, 31, 18, 44, 12, 57, 27, 70, 20, 38, 8, 63, 49, 35, 16, 52, 25];
    $scales = [0.55, 1.0, 1.7, 0.8, 1.3, 0.6, 1.9, 0.9, 1.5, 0.7, 2.1, 1.1, 0.65, 1.4, 0.85, 1.6];
    $durs   = [5.5, 7.0, 4.2, 8.5, 6.0, 9.5, 4.8, 7.8, 5.2, 10.5, 6.6, 8.0, 5.8, 9.0, 7.4, 4.5];
    $delays = [0, 2.4, 4.9, 1.3, 6.2, 3.1, 7.7, 0.8, 5.5, 2.0, 8.6, 3.9, 6.9, 1.7, 4.3, 9.4];
@endphp

<div class="sdfr-lines" aria-hidden="true" style="--c: {{ $color }};">
    @for ($i = 0; $i < (int) $count; $i++)
        @php
            $top   = $tops[$i % count($tops)];
            $scale = $scales[$i % count($scales)];
            $dur   = $durs[$i % count($durs)];
            $delay = $delays[$i % count($delays)];
        @endphp
        <span class="comet"
              style="--top: {{ $top }}%; --scale: {{ $scale }}; --dur: {{ $dur }}s; --delay: {{ $delay }}s;">
            <span class="core"></span>
        </span>
    @endfor
</div>

@once
    @push('link')
        <style>
            .sdfr-lines {
                position: absolute; inset: 0; overflow: hidden; pointer-events: none;
                --dist: 122vw;            /* طولِ سفرِ کامِت روی صفحه */
            }

            .sdfr-lines .comet {
                position: absolute;
                top: var(--top);
                left: -12%;
                opacity: 0;
                will-change: transform, opacity;
                animation: sdfr-comet-fly var(--dur, 6s) var(--delay, 0s) infinite;
                /* آرام شروع می‌شود و یهو شتاب می‌گیرد (slow → sudden) */
                animation-timing-function: cubic-bezier(.5, 0, .85, .25);
            }

            /* سرِ کامِت */
            .sdfr-lines .comet .core {
                position: relative;
                display: block;
                width:  calc(3px * var(--scale, 1));
                height: calc(3px * var(--scale, 1));
                border-radius: 50%;
                background: var(--c, #7dd3fc);
                box-shadow: 0 0 calc(7px * var(--scale, 1)) calc(1.5px * var(--scale, 1)) var(--c, #7dd3fc);
            }

            /* دنباله — پشتِ سر (سمت چپ، چون کامِت به راست می‌رود) */
            .sdfr-lines .comet .core::before {
                content: '';
                position: absolute;
                top: 50%;
                right: 100%;
                transform: translateY(-50%);
                width:  calc(165px * var(--scale, 1));
                height: calc(1.6px * var(--scale, 1));
                border-radius: 999px;
                background: linear-gradient(to left, var(--c, #7dd3fc), transparent 92%);
                opacity: .9;
            }

            @keyframes sdfr-comet-fly {
                0% {
                    transform: translate3d(0, 0, 0) scaleX(.35) scaleY(.55);
                    opacity: 0;
                }
                6% { opacity: .5; }                 /* کوچک و کم‌رنگ ظاهر می‌شود */
                16% {
                    transform: translate3d(calc(var(--dist) * .12), 0, 0) scaleX(.6) scaleY(.8);
                    opacity: 1;                      /* هنوز آرام و جمع‌وجور */
                }
                100% {
                    transform: translate3d(var(--dist), 0, 0) scaleX(1.75) scaleY(1.08);
                    opacity: 0;                      /* یهو کشیده + کلفت رد می‌شود و محو می‌گردد */
                }
            }

            @media (prefers-reduced-motion: reduce) {
                .sdfr-lines .comet { animation: none; opacity: .25; }
                .sdfr-lines .comet .core::before { width: calc(110px * var(--scale, 1)); }
            }
        </style>
    @endpush
@endonce
