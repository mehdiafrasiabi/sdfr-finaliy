{{-- روند ماهانه‌ی نمرات با میله و دلتای رشد/پسرفت --}}
{{-- ورودی‌ها: $title (string)، $trend (array)، $delta (?float) --}}
<div class="card h-100">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">{{ $title }}</h6>
            @if(!is_null($delta))
                @php $up = $delta >= 0; @endphp
                <span class="badge bg-{{ $up ? 'success' : 'danger' }}">
                    {{ $up ? 'رشد ▲' : 'پسرفت ▼' }} {{ abs($delta) }}
                </span>
            @endif
        </div>

        @if(empty($trend))
            <div class="text-center text-muted py-4">داده‌ای برای نمایش روند وجود ندارد.</div>
        @else
            @php $max = max(array_map(fn($t) => $t['avg'], $trend)) ?: 100; @endphp
            <div class="d-flex align-items-end gap-2" style="height: 140px;">
                @foreach($trend as $t)
                    <div class="d-flex flex-column align-items-center justify-content-end flex-fill h-100">
                        <small class="text-muted" style="font-size: 10px;">{{ $t['avg'] }}</small>
                        <div class="w-100 rounded-top bg-primary"
                             style="height: {{ max(4, round(($t['avg'] / $max) * 100)) }}%; opacity: .8;"></div>
                        <small class="text-muted mt-1" style="font-size: 10px;">{{ $t['label'] }}</small>
                        @if(!is_null($t['delta']))
                            <small class="text-{{ $t['delta'] >= 0 ? 'success' : 'danger' }}" style="font-size: 10px;">
                                {{ $t['delta'] >= 0 ? '+' : '' }}{{ $t['delta'] }}
                            </small>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
