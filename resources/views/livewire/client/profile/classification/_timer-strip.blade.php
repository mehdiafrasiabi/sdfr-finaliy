@php
    $tc = $config['timerColor'];
    $borderCls = $tc === 'emerald' ? 'border-emerald-500/25' : 'border-amber-500/25';
    $bgCls     = $tc === 'emerald' ? 'bg-emerald-500/5'     : 'bg-amber-500/5';
    $sepCls    = $tc === 'emerald' ? 'text-emerald-500'     : 'text-amber-500';
    $numCls    = $tc === 'emerald' ? 'text-emerald-400'     : 'text-amber-400';
@endphp

<div class="rounded-xl border {{ $borderCls }} {{ $bgCls }} px-3 py-2">
    <p class="text-[10px] font-bold {{ $sepCls }} mb-1.5 text-center">⏳ {{ $config['timerLabel'] }}</p>
    <div class="flex items-center justify-center gap-1" dir="ltr">
        <div class="flex flex-col items-center min-w-[36px]">
            <span class="font-black text-lg {{ $numCls }} tabular-nums" x-text="seconds.toString().padStart(2,'0')">00</span>
            <span class="text-[9px] text-muted">ثانیه</span>
        </div>
        <span class="{{ $sepCls }} font-bold pb-3">:</span>
        <div class="flex flex-col items-center min-w-[36px]">
            <span class="font-black text-lg {{ $numCls }} tabular-nums" x-text="minutes.toString().padStart(2,'0')">00</span>
            <span class="text-[9px] text-muted">دقیقه</span>
        </div>
        <span class="{{ $sepCls }} font-bold pb-3">:</span>
        <div class="flex flex-col items-center min-w-[36px]">
            <span class="font-black text-lg {{ $numCls }} tabular-nums" x-text="hours.toString().padStart(2,'0')">00</span>
            <span class="text-[9px] text-muted">ساعت</span>
        </div>
        <span class="{{ $sepCls }} font-bold pb-3">:</span>
        <div class="flex flex-col items-center min-w-[36px]">
            <span class="font-black text-lg {{ $numCls }} tabular-nums" x-text="days.toString().padStart(2,'0')">00</span>
            <span class="text-[9px] text-muted">روز</span>
        </div>
    </div>
</div>
