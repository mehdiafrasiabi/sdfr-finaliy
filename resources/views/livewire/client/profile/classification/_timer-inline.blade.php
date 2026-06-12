@php
    $tc = $config['timerColor'];
    $cls = $tc === 'emerald'
        ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-500'
        : 'bg-amber-500/10 border-amber-500/30 text-amber-500';
@endphp

<span class="inline-flex items-center gap-1.5 rounded-lg border px-2 py-0.5 text-[11px] font-bold {{ $cls }}">
    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span dir="ltr" class="tabular-nums">
        <span x-text="days">0</span>روز
        <span x-text="hours.toString().padStart(2,'0')">00</span>:<span x-text="minutes.toString().padStart(2,'0')">00</span>:<span x-text="seconds.toString().padStart(2,'0')">00</span>
    </span>
</span>
