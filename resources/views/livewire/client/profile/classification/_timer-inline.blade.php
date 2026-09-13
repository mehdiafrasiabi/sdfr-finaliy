@php
    $tc = $config['timerColor'];
    $cls = $tc === 'success'
        ? 'bg-success/10 border-success/30 text-success'
        : 'bg-warning/10 border-warning/30 text-warning';
@endphp

<span class="inline-flex items-center gap-1.5 rounded-lg border px-2 py-0.5 text-[11px] font-bold {{ $cls }}">
    <x-ui.icon name="clock" class="w-3 h-3"/>
    <span dir="ltr" class="tabular-nums">
        <span x-text="days">0</span>:

        <span x-text="hours.toString().padStart(2,'0')">00</span>
        :<span x-text="minutes.toString().padStart(2,'0')">00</span>
        :<span x-text="seconds.toString().padStart(2,'0')">00</span>
    </span>

</span>
