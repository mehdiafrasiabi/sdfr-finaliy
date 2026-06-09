
<div class="flex items-center gap-2 shrink-0">

    {{-- ── نوار نقطه‌ها ── (داخل دسترسی LTR تا محاسبه‌ی پرشدن ساده بماند) --}}
    <div dir="ltr" class="relative flex items-end justify-between select-none" style="width: 168px;">

        {{-- خط پایه --}}
        <div class="absolute left-3 right-3 bottom-[10.5px] h-[3px] rounded-full bg-border"></div>

        {{-- خط پرشده (از سمت D تا نقطه‌ی انتخاب‌شده) --}}
        <div class="absolute left-3 bottom-[10.5px] h-[3px] rounded-full transition-all duration-300 ease-out"
             :class="ratings['{{ $key }}'] ? barClass(ratings['{{ $key }}']) : ''"
             :style="ratings['{{ $key }}']
                 ? `width: calc((100% - 24px) * ${(ratings['{{ $key }}'] - 1) / 3})`
                 : 'width: 0px'"></div>

        @php
            $nodes = [
                ['v' => 1, 'letter' => 'D'],
                ['v' => 2, 'letter' => 'C'],
                ['v' => 3, 'letter' => 'B'],
                ['v' => 4, 'letter' => 'A'],
            ];
        @endphp
        @foreach($nodes as $n)
            <div class="relative z-10 flex flex-col items-center gap-1">
                {{-- حرف بالای نقطه --}}
                <span class="text-[11px] font-black leading-none transition-colors"
                      :class="ratings['{{ $key }}'] && ratings['{{ $key }}'] >= {{ $n['v'] }}
                          ? letterClass(ratings['{{ $key }}'])
                          : 'text-muted-foreground'">{{ $n['letter'] }}</span>

                {{-- نقطه --}}
                <button type="button"
                        @click="
    const btn = $event.currentTarget;
    btn.classList.add('dot-pop');
    setTimeout(() => btn && btn.classList.remove('dot-pop'), 350);
    setRating('{{ $key }}', '{{ $kind }}', {{ $id }}, {{ $n['v'] }});
"
                        class="w-6 h-6 rounded-full border-2 transition-all duration-200 active:scale-90 cursor-pointer"
                        :class="
                            ratings['{{ $key }}'] === {{ $n['v'] }}
                                ? dotClass(ratings['{{ $key }}'])
                                : (ratings['{{ $key }}'] && ratings['{{ $key }}'] > {{ $n['v'] }}
                                    ? 'bg-transparent ' + levelColorClass(ratings['{{ $key }}'], 'border')
                                    : 'bg-transparent border-border hover:border-foreground/40')
                        "
                        title="{{ $n['letter'] }}"></button>
            </div>
        @endforeach
    </div>

    {{-- ── دکمه حذف (ضربدر) ── --}}
    <template x-if="ratings['{{ $key }}']">
        <button type="button"
                @click="clearRating('{{ $key }}', '{{ $kind }}', {{ $id }})"
                class="w-6 h-6 flex items-center justify-center rounded-lg  text-red-400 hover:bg-red-500/20 hover:text-red-500 transition-colors shrink-0"
                title="حذف امتیاز">
            <svg class="w-3.5 h-3.5 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </template>
</div>
