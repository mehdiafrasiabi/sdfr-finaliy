@props([
    'hoursModel',
    'minutesModel',
    'maxHours'   => \App\Livewire\Client\Profile\SuddenEventModal::MAX_HOURS,
    'minuteStep' => \App\Livewire\Client\Profile\SuddenEventModal::MINUTE_STEP,
])

@php
    $hoursValues  = range(0, (int) $maxHours);
    $minuteValues = range(0, 59, (int) $minuteStep);
    $itemH  = 40;   // ارتفاعِ هر ردیف (px)
    $visible = 3;   // تعداد ردیف قابل‌مشاهده در هر ستون (فرد، تا ردیفِ وسط دقیق باشد)
    $boxH   = $itemH * $visible;
    $pad    = $itemH * intdiv($visible, 2);
@endphp

<div
    x-data="{
        itemH: {{ $itemH }},
        hoursValues: {{ \Illuminate\Support\Js::from($hoursValues) }},
        minutesValues: {{ \Illuminate\Support\Js::from($minuteValues) }},
        hours: @entangle($hoursModel).live,
        minutes: @entangle($minutesModel).live,
        _hTimer: null,
        _mTimer: null,

        idx(list, v) {
            const i = list.indexOf(parseInt(v));
            return i < 0 ? 0 : i;
        },
        initCol(ref, list, current) {
            this.$nextTick(() => {
                const el = this.$refs[ref];
                if (el) el.scrollTop = this.idx(list, current) * this.itemH;
            });
        },
        settle(ref, list, prop) {
            const el = this.$refs[ref];
            if (!el) return;
            let i = Math.round(el.scrollTop / this.itemH);
            i = Math.max(0, Math.min(list.length - 1, i));
            el.scrollTo({ top: i * this.itemH, behavior: 'smooth' });
            const v = list[i];
            if (this[prop] !== v) this[prop] = v;
        },
        onScroll(ref, list, prop, timerKey) {
            clearTimeout(this[timerKey]);
            this[timerKey] = setTimeout(() => this.settle(ref, list, prop), 130);
        },
        pick(ref, list, prop, v) {
            this[prop] = v;
            const el = this.$refs[ref];
            if (el) el.scrollTo({ top: this.idx(list, v) * this.itemH, behavior: 'smooth' });
        },
    }"
    x-init="initCol('hoursTrack', hoursValues, hours); initCol('minutesTrack', minutesValues, minutes);"
    class="relative select-none"
>
    {{-- نوار هایلایتِ ثابتِ وسط --}}
    <div class="pointer-events-none absolute inset-x-2 top-1/2 -translate-y-1/2 rounded-xl bg-primary/15 border border-primary/40" style="height:{{ $itemH }}px;"></div>
    {{-- محوشدگیِ بالا/پایین برای حسِ عمقِ چرخ --}}
    <div class="pointer-events-none absolute inset-x-0 top-0 h-4 bg-gradient-to-b from-background to-transparent z-10"></div>
    <div class="pointer-events-none absolute inset-x-0 bottom-0 h-4 bg-gradient-to-t from-background to-transparent z-10"></div>

    <div class="flex items-center justify-center gap-4" dir="ltr">
        {{-- ساعت --}}
        <div class="relative">
            <div
                x-ref="hoursTrack"
                @scroll="onScroll('hoursTrack', hoursValues, 'hours', '_hTimer')"
                class="wheel-track overflow-y-scroll scroll-smooth"
                style="height:{{ $boxH }}px; width:56px; scroll-snap-type:y mandatory; padding-block:{{ $pad }}px;"
            >
                <template x-for="v in hoursValues" :key="'h-' + v">
                    <div
                        @click="pick('hoursTrack', hoursValues, 'hours', v)"
                        class="flex items-center justify-center cursor-pointer font-black tabular-nums transition-all duration-150"
                        style="height:{{ $itemH }}px; scroll-snap-align:center;"
                        :class="hours === v ? 'text-foreground text-lg' : 'text-muted text-sm'"
                        x-text="v"
                    ></div>
                </template>
            </div>
            <span class="pointer-events-none absolute top-1/2 -translate-y-1/2 -left-7 text-[10px] text-muted font-bold">ساعت</span>
        </div>

        <span class="text-muted font-black text-base">:</span>

        {{-- دقیقه --}}
        <div class="relative">
            <div
                x-ref="minutesTrack"
                @scroll="onScroll('minutesTrack', minutesValues, 'minutes', '_mTimer')"
                class="wheel-track overflow-y-scroll scroll-smooth"
                style="height:{{ $boxH }}px; width:56px; scroll-snap-type:y mandatory; padding-block:{{ $pad }}px;"
            >
                <template x-for="v in minutesValues" :key="'m-' + v">
                    <div
                        @click="pick('minutesTrack', minutesValues, 'minutes', v)"
                        class="flex items-center justify-center cursor-pointer font-black tabular-nums transition-all duration-150"
                        style="height:{{ $itemH }}px; scroll-snap-align:center;"
                        :class="minutes === v ? 'text-foreground text-lg' : 'text-muted text-sm'"
                        x-text="v.toString().padStart(2, '0')"
                    ></div>
                </template>
            </div>
            <span class="pointer-events-none absolute top-1/2 -translate-y-1/2 -right-9 text-[10px] text-muted font-bold">دقیقه</span>
        </div>
    </div>
</div>

@once
    <style>
        /* مخفی‌کردنِ اسکرول‌بار ستون‌های چرخِ زمان، بدون نیاز به هیچ افزونهٔ Tailwind */
        .wheel-track { -ms-overflow-style: none; scrollbar-width: none; }
        .wheel-track::-webkit-scrollbar { display: none; }
    </style>
@endonce
