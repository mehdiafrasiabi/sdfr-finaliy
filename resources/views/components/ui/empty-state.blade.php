{{--
    x-ui.empty-state — بلوکِ «چیزی برای نشون دادن نیست» (تصویرِ کم‌رنگ + عنوان +
    توضیح)، که عیناً چند بار در فایل‌های مختلف (و حتی دوبار در همون یک فایل)
    تکرار شده بود.

    مثال استفاده:
        x-ui.empty-state title="جلسه‌ای وجود ندارد!"
            هنوز جلسه‌ای برای شما ثبت نشده است.

    Props:
      image : مسیر svg/تصویرِ کم‌رنگ (پیش‌فرض همون empty2.svg که همه‌جای پروژه استفاده شده)
      title : عنوانِ بولد؛ اگه ندی، فقط توضیح (اسلات) نشون داده می‌شه
--}}
@props([
    'image' => '/client/svg/empty2.svg',
    'title' => null,
])
<div {{ $attributes->class(['flex flex-col items-center justify-center space-y-12 py-16']) }}>
    <img src="{{ $image }}"
         class="w-full max-w-[370px] md:max-w-xs opacity-35 mb-4 md:mb-6"
         alt="پیامی وجود ندارد"/>
    <div class="text-center space-y-3">
        @if($title)
            <h2 class="font-bold text-xl text-foreground">{{ $title }}</h2>
        @endif
        <p class="text-muted text-sm">{{ $slot }}</p>
    </div>
</div>
