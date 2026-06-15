{{-- نمایش روند ماهانه‌ی نمرات با میله و دلتای رشد/پسرفت --}}
{{-- ورودی‌ها: $title (string)، $trend (array)، $delta (?float) --}}
<div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-bold">{{ $title }}</h3>
        @if(!is_null($delta))
            @php $up = $delta >= 0; @endphp
            <span class="text-xs px-2 py-0.5 rounded text-white {{ $up ? 'bg-emerald-500' : 'bg-rose-500' }}">
                {{ $up ? 'رشد' : 'پسرفت' }} {{ $up ? '▲' : '▼' }} {{ abs($delta) }}
            </span>
        @endif
    </div>

    @if(empty($trend))
        <div class="text-center py-6 text-slate-400 text-sm">داده‌ای برای نمایش روند وجود ندارد.</div>
    @else
        @php $max = max(array_map(fn($t) => $t['avg'], $trend)) ?: 100; @endphp
        <div class="flex items-end gap-2 h-32">
            @foreach($trend as $t)
                <div class="flex-1 flex flex-col items-center justify-end h-full">
                    <span class="text-[10px] text-slate-500 mb-1">{{ $t['avg'] }}</span>
                    <div class="w-full rounded-t bg-blue-500/80"
                         style="height: {{ max(4, round(($t['avg'] / $max) * 100)) }}%"></div>
                    <span class="text-[10px] text-slate-400 mt-1">{{ $t['label'] }}</span>
                    @if(!is_null($t['delta']))
                        <span class="text-[10px] {{ $t['delta'] >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                            {{ $t['delta'] >= 0 ? '+' : '' }}{{ $t['delta'] }}
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
