{{--
    x-ui.progress-bar — نوار پیشرفت (مخصوص درصد آپلود فایل، ولی برای هر پیشرفت
    درصدی دیگری هم قابل استفاده است).

    دو حالت استفاده دارد:

    ۱) استاتیک (مقدار از بک‌اند/Livewire می‌آید):
        x-ui.progress-bar :percent="$uploadPercent" label="در حال آپلود..." variant="info"

    ۲) زنده با Alpine (وقتی خودِ صفحه با x-data یک متغیرِ درصد دارد)، از طریق
       پراپ model اسم متغیرِ آلپاین را می‌دهید (بدون x- یا $) تا نوار به‌صورت
       reactive خودش را به‌روز کند، بدون نیاز به re-render سمت سرور:
        x-ui.progress-bar model="uploadPercent" label="در حال آپلود..." variant="info"

    Props:
      percent     : عدد ۰ تا ۱۰۰ (پیش‌فرض ۰) — فقط وقتی model ندهید استفاده می‌شود
      model       : نام متغیر Alpine در scope والد، برای بایند زنده‌ی درصد
      label       : متن بالای نوار (اختیاری)
      showPercent : نمایش عدد درصد در کنار label (پیش‌فرض true)
      variant     : primary (پیش‌فرض) | success | warning | error | info
      size        : sm | md (پیش‌فرض) | lg — ارتفاع نوار
      striped     : راه‌راهِ متحرک روی نوار (مناسب حالت «در حال آپلود»)
--}}
@props([
    'percent'     => 0,
    'model'       => null,
    'label'       => null,
    'showPercent' => true,
    'variant'     => 'primary',
    'size'        => 'md',
    'striped'     => false,
])

@once('sdfr-ui-kit-assets')
    @include('components.ui._kit-assets')
@endonce

@php
    $heightClass = match ($size) {
        'sm'    => 'h-1.5',
        'lg'    => 'h-4',
        default => 'h-2.5',
    };

    $fillClass = match ($variant) {
        'success' => 'bg-success',
        'warning' => 'bg-warning',
        'error'   => 'bg-error',
        'info'    => 'bg-info',
        default   => 'bg-primary',
    };

    $staticPercent = (int) max(0, min(100, $percent));

    // اسم متغیر Alpine اعتبارسنجی می‌شود تا فقط شامل حروف/عدد/underscore/نقطه باشد
    // (چون مستقیم داخل عبارت Alpine جای‌گذاری می‌شود، نه برای جلوگیری از XSS از
    // منبع خارجی — این پراپ همیشه توسط توسعه‌دهنده‌ی همین پروژه پاس داده می‌شود).
    $safeModel = $model && preg_match('/^[A-Za-z0-9_.$]+$/', $model) ? $model : null;
@endphp

<div {{ $attributes->class(['w-full']) }} @if($safeModel) x-data @endif dir="rtl">
    @if($label || $showPercent)
        <div class="flex items-center justify-between mb-1.5 text-[11px] font-bold text-muted">
            @if($label)
                <span>{{ $label }}</span>
            @else
                <span></span>
            @endif

            @if($showPercent)
                @if($safeModel)
                    <span class="text-foreground tabular-nums" x-text="Math.round(Math.max(0, Math.min(100, {{ $safeModel }}))) + '%'">0%</span>
                @else
                    <span class="text-foreground tabular-nums">{{ $staticPercent }}%</span>
                @endif
            @endif
        </div>
    @endif

    <div class="w-full {{ $heightClass }} rounded-full bg-secondary border border-border overflow-hidden">
        @if($safeModel)
            <div
                class="{{ $heightClass }} rounded-full {{ $fillClass }} transition-[width] duration-300 ease-out {{ $striped ? 'sdfr-progress-stripe' : '' }}"
                :style="'width:' + Math.max(0, Math.min(100, {{ $safeModel }})) + '%'"
            ></div>
        @else
            <div
                class="{{ $heightClass }} rounded-full {{ $fillClass }} transition-[width] duration-300 ease-out {{ $striped ? 'sdfr-progress-stripe' : '' }}"
                style="width: {{ $staticPercent }}%"
            ></div>
        @endif
    </div>
</div>
