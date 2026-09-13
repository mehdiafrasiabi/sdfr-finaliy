{{--
    x-ui.thumbnail — جعبه‌ی تصویر/آیکونِ کارت‌ها (مثلاً کارت‌های جلسه در
    session-list.blade.php)، که تویِ حالت موبایل و دسکتاپ عیناً تکرار می‌شد:
    یک باکس با گرادیانِ آبی (وقتی چیزی فعال/باز است) یا یک باکسِ خاکستریِ ساده
    با آیکونِ قفل (وقتی آیتم قفل است).

    طبق قرارداد پروژه، این کامپوننت فقط «جعبه + حالتِ قفل/باز» را مدیریت می‌کند؛
    خودِ تصویر/آیکونِ داخلش (وقتی قفل نیست) با اسلات پاس داده می‌شود، چون از
    فایلی به فایلِ دیگر فرق می‌کند:

        x-ui.thumbnail :locked="$isLocked" class="w-full h-36"
            <img src="/client/icons/counsolotion.webp" class="w-24 h-24 object-contain" alt="">

    Props:
      locked   : bool — اگه true باشه، به‌جای اسلات، آیکونِ قفل با پس‌زمینه‌ی خاکستری نشون داده می‌شه
      gradient : to-b (پیش‌فرض، عمودی) | to-br (مورب، برای ستونِ باریکِ دسکتاپ)
      size     : md (پیش‌فرض، برای موبایل) | sm (آیکونِ قفلِ کوچیک‌تر، برای دسکتاپ)

    اندازه/شکلِ خودِ جعبه (عرض، ارتفاع، flex-shrink) با کلاسِ معمولیِ Tailwind
    روی خودِ تگ پاس داده می‌شه (مثل مثال بالا)، چون این‌ها بسته به جای استفاده فرق می‌کنن.
--}}
@props([
    'locked'   => false,
    'gradient' => 'to-b',
    'size'     => 'md',
])
@php
    $gradientClass = $gradient === 'to-br' ? 'bg-gradient-to-br' : 'bg-gradient-to-b';
    $iconSizeClass = $size === 'sm' ? 'w-12 h-12' : 'w-14 h-14';
@endphp
<div {{ $attributes->class([
        'flex items-center justify-center flex-shrink-0',
        'bg-secondary' => $locked,
        "$gradientClass from-blue-100 to-blue-200 dark:from-blue-950 dark:to-blue-900" => ! $locked,
    ]) }}>
    @if($locked)
        <x-ui.icon name="lock" class="{{ $iconSizeClass }} text-muted"/>
    @else
        {{ $slot }}
    @endif
</div>
