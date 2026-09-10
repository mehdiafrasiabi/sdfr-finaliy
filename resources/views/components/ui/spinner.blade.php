@props([
    'size' => 'md', // xs | sm | md | lg
])
{{--
    اسپینر مشترک و سبک: فقط یک span با انیمیشن CSS خالص (بدون جاوااسکریپت/Alpine)،
    پس هیچ لگ یا سنگینی اضافه نمی‌کنه. از کلاس animate-spin خودِ تیلوند استفاده می‌کنه
    (همون چیزی که قبلاً جای دیگه‌ای هم توی پروژه استفاده شده) و رنگش از currentColor
    میاد - یعنی با گذاشتن یک کلاس text-* (یا رنگ پیش‌فرض متنِ والدش) خودش رو با هر
    دکمه/بخشی که توش قرار می‌گیره هماهنگ می‌کنه، بدون نیاز به پاس دادن رنگ جداگانه.

    قبل از این کامپوننت، همین دقیقاً همین استایل (border + border-right-color:transparent)
    به‌صورت تکراری و کپی‌شده توی style چند صفحه (گزارش، برنامه‌ی هفتگی، ادیت پروفایل)
    نوشته شده بود؛ این‌جا یک‌بار نوشته شده تا همه جا از همینِ یکی استفاده بشه.

    نکته‌ی مهم: تگ‌های نمونه‌ی استفاده عمداً این‌جا نوشته نشده‌اند - کامپایلر Blade
    تگ‌های x-... را حتی داخل همین کامنت هم پردازش می‌کند و باعث رندر بازگشتی/بی‌نهایتِ
    خودِ این کامپوننت و ارور Allowed memory size exhausted می‌شود. برای استفاده به
    x-ui.select-menu-content.blade.php یا دیگر کامپوننت‌های پوشه‌ی ui نگاه کنید:
    پراپ size یکی از xs, sm, md (پیش‌فرض), lg را می‌پذیرد و کلاس رنگ (مثل text-primary)
    از طریق attributes پاس داده می‌شود.
--}}
@php
    $sizeClass = match ($size) {
        'xs' => 'h-3.5 w-3.5',
        'sm' => 'h-4 w-4',
        'lg' => 'h-8 w-8',
        default => 'h-5 w-5',
    };
    $borderWidth = $size === 'lg' ? '3px' : '2.5px';
@endphp
<span
    {{ $attributes->class(['inline-block shrink-0 rounded-full animate-spin align-[-0.15em]', $sizeClass]) }}
    style="border: {{ $borderWidth }} solid currentColor; border-right-color: transparent;"
    role="status"
    aria-label="در حال بارگذاری"
></span>
