{{--
    x-ui.button — دکمه‌ی استاندارد عملیاتی پروژه با «حس فشاری» (Tactile Press)

    مثال‌های استفاده (این کامنت‌ها عمداً به‌صورت متن ساده نوشته شده‌اند، نه تگ x-
    واقعی، چون کامپایلر Blade تگ‌های x-... را حتی داخل کامنت هم پردازش می‌کند):

    - دکمه‌ی اصلیِ «بعدی» شبیه نمونه‌ی خودِ پروژه:
        x-ui.button با @click="next()" x-bind:disabled="transitioning"
        <span x-text="index >= steps.length - 1 ? 'تمام 🎉' : 'بعدی'"></span>

      ⚠️ نکته‌ی مهم: وقتی می‌خوای یک attribute را به یک متغیرِ Alpine (نه یک
      مقدارِ PHP) بایند کنی، حتماً از فرمِ کاملِ x-bind:نام="..." استفاده کن، نه
      میان‌بُرِ :نام="...". چون روی تگ‌های کامپوننتِ Blade (<x-...>)، هر
      attributeای که با ":" شروع بشه، توسط خودِ Blade به‌عنوان یک عبارتِ PHP
      ارزیابی می‌شود (نه رشته‌ی خام برای Alpine) — یعنی :disabled="transitioning"
      باعث میشه Blade دنبال یک ثابت/متغیرِ PHP به اسم transitioning بگرده و
      خطای "Undefined constant" بده. برای پراپ‌های واقعیِ PHP (مثل
      :loading="$wire.saving" از سمتِ Livewire، یا :variant="$status") همون
      میان‌بر ":" کاملاً درست و لازم است؛ فقط وقتی سمتِ راست یک عبارتِ خالصِ
      Alpine (متغیر تعریف‌شده در x-data) است، از x-bind: کامل استفاده کن.

    - دکمه‌ی خطرناک با حالت loading و آیکون انتهایی:
        x-ui.button variant="error" icon="x" :loading="$wire.deleting" wire:click="delete"
            متن: حذف کن

    - وقتی خودِ دکمه یک لینک/ناوبری است (نه یک اکشن JS)، به‌جای <a> دستی از
      همین کامپوننت با پراپ href استفاده کن تا ظاهر/فشاری‌بودنش با بقیه‌ی
      دکمه‌ها یکی باشد؛ در این حالت یک تگ <a> رندر می‌شود، نه <button>:
        x-ui.button href="{{ route('...') }}" wire:navigate variant="secondary" icon="chevron-left"
            متن: مشاهده جزئیات

    Props:
      variant : primary | secondary | secondary-outline | outline | ghost | success | warning | error | info
                (هر کدوم از success/warning/error/info یک نسخه‌ی «-soft» هم دارن، مثلاً warning-soft)
                - secondary: پرِ رنگِ خاکستری/ثانویه (مثلاً برای «مشاهده‌ی چیزی که تمومه»)
                - secondary-outline: فقط حاشیه‌دار، برای دکمه‌های خنثی/ابزاری مثل لغو یا باز/بسته‌کردنِ جزئیات
      size    : sm | md (پیش‌فرض) | lg
      loading : bool — اسپینر (x-ui.spinner) نشون میده و غیرفعال میشه
      disabled: bool
      type    : button (پیش‌فرض) | submit | reset — فقط وقتی href نداری
      href    : اگه پر بشه، به‌جای <button> یک <a href="..."> رندر می‌شود
      block   : bool — عرض کامل
      pill    : bool — rounded-full به‌جای rounded-lg
      icon    : اسمِ یکی از آیکون‌های x-ui.icon (مثلاً 'check')، همیشه بعد از
                متنِ دکمه نمایش داده می‌شود (طبق قرارداد پروژه: آیکون انتهای متن)

      هر attribute دیگری (@click ،x-on: ،wire:click ،wire:navigate ،target،
      :disabled و ...) مستقیم روی تگِ نهایی (button یا a) پاس داده می‌شود.
--}}
@props([
    'variant'  => 'primary',
    'size'     => 'md',
    'loading'  => false,
    'disabled' => false,
    'type'     => 'button',
    'href'     => null,
    'block'    => false,
    'pill'     => false,
    'icon'     => null,
])

@once('sdfr-ui-kit-assets')
    @include('components.ui._kit-assets')
@endonce

@php
    $isDisabled = $disabled || $loading;
    $solid = in_array($variant, ['primary', 'success', 'warning', 'error', 'info'], true);
    $tag = $href ? 'a' : 'button';

    $sizeClasses = match ($size) {
        'sm'    => 'h-8 px-3 text-[11px] gap-1.5',
        'lg'    => 'h-11 px-6 text-sm gap-2',
        default => 'h-9 px-4 text-xs gap-1.5',
    };

    $spinnerSize = match ($size) {
        'sm'    => 'xs',
        'lg'    => 'sm',
        default => 'xs',
    };

    $iconSizeClass = match ($size) {
        'sm'    => 'w-3.5 h-3.5',
        'lg'    => 'w-5 h-5',
        default => 'w-4 h-4',
    };

    $variantClasses = match ($variant) {
        // برای اکشن‌های واقعیِ کم‌رنگ‌تر (مثلاً «مشاهده‌ی چیزی که قبلاً ثبت شده»): پرِ رنگِ ثانویه
        'secondary'         => 'bg-secondary text-foreground hover:bg-secondary/70',
        // برای دکمه‌های خنثی/ابزاری (لغو، باز/بسته‌کردنِ جزئیات و مثل آن): فقط حاشیه‌دار
        'secondary-outline' => 'bg-background border border-border text-foreground hover:bg-secondary',
        'outline'      => 'bg-transparent border border-primary text-primary hover:bg-primary hover:text-primary-foreground',
        'ghost'        => 'bg-transparent text-foreground hover:bg-secondary',
        'success'      => 'bg-success text-success-foreground hover:bg-white hover:text-black',
        'warning'      => 'bg-warning text-warning-foreground hover:bg-white hover:text-black',
        'error'        => 'bg-error text-error-foreground hover:bg-white hover:text-black',
        'info'         => 'bg-info text-info-foreground hover:bg-white hover:text-black',
        'info-soft'    => 'bg-info/10 text-info border border-info/30 hover:bg-info hover:text-info-foreground',
        'success-soft' => 'bg-success/10 text-success border border-success/30 hover:bg-success hover:text-success-foreground',
        'warning-soft' => 'bg-warning/10 text-warning border border-warning/30 hover:bg-warning hover:text-warning-foreground',
        'error-soft'   => 'bg-error/10 text-error border border-error/30 hover:bg-error hover:text-error-foreground',
        default        => 'bg-primary text-white hover:bg-white hover:text-black', // primary
    };

    $radiusClass = $pill ? 'rounded-full' : 'rounded-lg';
@endphp

<{{ $tag }}
    @if($tag === 'button')
        type="{{ $type }}"
        @if($isDisabled) disabled @endif
    @else
        href="{{ $href }}"
        @if($isDisabled) aria-disabled="true" tabindex="-1" @endif
    @endif
    data-elevated="{{ $solid ? 'true' : 'false' }}"
    {{ $attributes->class([
        'btn-press inline-flex items-center justify-center font-bold transition-colors',
        'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 focus-visible:ring-offset-2 focus-visible:ring-offset-background',
        'disabled:cursor-not-allowed',
        'disabled:opacity-50',
        $radiusClass,
        $sizeClasses,
        $variantClasses,
        'w-full' => $block,
        'pointer-events-none opacity-60' => $isDisabled && $tag === 'a',
    ]) }}
>
    <span class="{{ $loading ? 'opacity-90' : '' }}">{{ $slot }}</span>

    @if($loading)
        <x-ui.spinner size="{{ $spinnerSize }}" />
    @elseif($icon)
        <x-ui.icon :name="$icon" class="{{ $iconSizeClass }}" />
    @endif
</{{ $tag }}>
