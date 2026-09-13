{{--
    x-ui.status-badge — نشان وضعیت استاندارد برای همه‌ی حالت‌های رایج پروژه.

    استفاده (نمونه‌ها به‌صورت متن ساده، طبق قاعده‌ی کامنت‌نویسی سایر کامپوننت‌های
    این پوشه — کامپایلر Blade تگ x- را داخل کامنت هم پردازش می‌کند):

        x-ui.status-badge status="paid"
        x-ui.status-badge status="pending" label="در انتظار تایید مالی"   ← override متن

    status یکی از مقادیر زیر را می‌پذیرد:
        pending      در انتظار
        voided       لغو شده
        cancelled    کنسل شده
        paid         پرداخت شده
        not_started  هنوز شروع نشده
        joinable     قابل شرکت
        expired      منقضی شده
        completed    تکمیل شده
        active       فعال (سبز/success - مثلاً پروژه‌ی در حال اجرا)
        inactive     غیرفعال
        trial        آزمایشی (آبی/info با آیکون ستاره)

    طراحی عمداً فقط به رنگ متکی نیست (طبق اصل دسترس‌پذیری «رنگ به‌تنهایی معنا
    منتقل نکند»): هر وضعیت یک آیکون/نقطه‌ی متفاوت هم دارد، تا برای کاربرِ
    رنگ‌کوری هم قابل تشخیص باشد. رنگ‌ها از توکن‌های معنایی پروژه (primary,
    success, warning, error, info, muted, secondary, border) می‌آیند که در
    app.css برای هر دو حالت light/dark تعریف شده‌اند؛ پس با toggle شدن کلاس
    dark روی <html>، خودش سازگار می‌شود.
--}}
@props([
    'status' => 'inactive',
    'label'  => null,
])

@once('sdfr-ui-kit-assets')
    @include('components.ui._kit-assets')
@endonce

@php
    $map = [
        'pending' => [
            'label'   => 'در انتظار',
            'classes' => 'bg-warning/10 text-warning border-warning/30',
            'dot'     => 'bg-warning animate-pulse',
        ],
        'voided' => [
            'label'   => 'لغو شده',
            'classes' => 'bg-error/10 text-error border-error/30',
            'icon'    => 'ban',
        ],
        'cancelled' => [
            'label'   => 'کنسل شده',
            'classes' => 'bg-secondary text-muted border-border',
            'icon'    => 'x',
        ],
        'paid' => [
            'label'   => 'پرداخت شده',
            'classes' => 'bg-success/10 text-success border-success/30',
            'icon'    => 'check',
        ],
        'not_started' => [
            'label'   => 'هنوز شروع نشده',
            'classes' => 'bg-muted/10 text-muted border-muted/30',
            'icon'    => 'clock',
        ],
        'joinable' => [
            'label'   => 'قابل شرکت',
            'classes' => 'bg-info/10 text-info border-info/30',
            'dot'     => 'bg-info',
            'ring'    => true,
        ],
        'expired' => [
            'label'   => 'منقضی شده',
            'classes' => 'bg-error/10 text-error border-error/30',
            'icon'    => 'hourglass',
        ],
        'completed' => [
            'label'   => 'تکمیل شده',
            'classes' => 'bg-success text-success-foreground border-transparent',
            'icon'    => 'double-check',
        ],
        'active' => [
            'label'   => 'فعال',
            'classes' => 'bg-success/10 text-success border-success/30',
            'dot'     => 'bg-success animate-pulse',
        ],
        'trial' => [
            'label'   => 'آزمایشی',
            'classes' => 'bg-info/10 text-info border-info/30',
            'icon'    => 'star',
        ],
        'inactive' => [
            'label'   => 'غیرفعال',
            'classes' => 'bg-secondary text-muted border-border',
            'icon'    => 'dash',
        ],
    ];

    $cfg  = $map[$status] ?? $map['inactive'];
    $text = $label ?? $cfg['label'];
@endphp

<span
    {{ $attributes->class([
        'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold whitespace-nowrap w-fit',
        $cfg['classes'],
    ]) }}
>
    @if(isset($cfg['dot']))
        <span class="relative flex h-1.5 w-1.5 shrink-0">
            @if(!empty($cfg['ring']))
                <span class="absolute inline-flex h-full w-full animate-ping rounded-full {{ $cfg['dot'] }} opacity-60"></span>
            @endif
            <span class="relative inline-flex h-1.5 w-1.5 rounded-full {{ $cfg['dot'] }}"></span>
        </span>
    @elseif(isset($cfg['icon']))
        <span class="shrink-0 inline-flex">
            @switch($cfg['icon'])
                @case('check')
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    @break
                @case('double-check')
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12.5l4 4 8-9M10 16.5l1 1 9.5-10.5"/></svg>
                    @break
                @case('x')
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    @break
                @case('ban')
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M5.6 5.6l12.8 12.8"/></svg>
                    @break
                @case('clock')
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/></svg>
                    @break
                @case('hourglass')
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 3h12M6 21h12M7 3c0 4 3 5.5 5 6.5v5C10 15.5 7 17 7 21m10-18c0 4-3 5.5-5 6.5v5c2 1 5 2.5 5 6.5"/></svg>
                    @break
                @case('dash')
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" d="M5 12h14"/></svg>
                    @break
                @case('star')
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                    @break
            @endswitch
        </span>
    @endif

    {{ $text }}
</span>
