{{--
    x-ui.modal — مودال باکس عمومی، هماهنگ با الگوی رویدادمحورِ خودِ پروژه (شبیه
    close-selects در components/ui/select.blade.php)، به‌جای نیاز به یک متغیر
    Livewire/Alpine مشترک بین دکمه و مودال.

    باز/بسته کردن از هر جای صفحه (حتی خارج از این کامپوننت) با dispatch کردن
    ایونت‌های window زیر انجام می‌شود:

        $dispatch('open-modal', 'confirm-delete')   ← باز کردن مودالی با id="confirm-delete"
        $dispatch('close-modal', 'confirm-delete')  ← بستن همان مودال
        $dispatch('close-modal')                     ← بستن هر مودالِ بازی (بدون id خاص)

    نمونه‌ی استفاده (کامنت متنی، نه تگ x- واقعی):

        دکمه‌ی باز کردن: button @click="$dispatch('open-modal', 'confirm-delete')"

        x-ui.modal id="confirm-delete" max-width="sm"
            x-slot:title  → متن عنوان
            بدنه‌ی مودال...
            x-slot:footer → دکمه‌های انصراف/تایید

    Props:
      id        : شناسه‌ی یکتای مودال (برای هدف‌گیریِ open-modal/close-modal)
      maxWidth  : sm | md (پیش‌فرض) | lg | xl | 2xl
      closeable : بستن با کلیک روی بک‌دراپ/Esc/دکمه‌ی × (پیش‌فرض true)
--}}
@props([
    'id'        => 'modal-' . uniqid(),
    'maxWidth'  => 'md',
    'closeable' => true,
])

@once('sdfr-ui-kit-assets')
    @include('components.ui._kit-assets')
@endonce

@php
    $maxWidthClass = match ($maxWidth) {
        'sm'    => 'max-w-sm',
        'lg'    => 'max-w-lg',
        'xl'    => 'max-w-xl',
        '2xl'   => 'max-w-2xl',
        default => 'max-w-md',
    };
@endphp

<template x-teleport="body">
    <div
        x-data="{ show: false }"
        x-on:open-modal.window="if ($event.detail === '{{ $id }}') show = true"
        x-on:close-modal.window="if (!$event.detail || $event.detail === '{{ $id }}') show = false"
        @if($closeable) x-on:keydown.escape.window="show = false" @endif
        x-show="show"
        x-cloak
        x-effect="show ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
        class="fixed inset-0 z-[9999] overflow-y-auto"
        style="display:none"
        dir="rtl"
        role="dialog"
        aria-modal="true"
        id="{{ $id }}"
    >
        {{-- بک‌دراپ --}}
        <div
            x-show="show"
            x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm"
            @if($closeable) @click="show = false" @endif
        ></div>

        {{-- پنل — موبایل: از پایینِ صفحه به‌صورت کشویی (bottom-sheet) باز/بسته می‌شود؛
             دسکتاپ (sm به بالا): از وسطِ صفحه با scale باز/بسته می‌شود. --}}
        <div class="relative flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
            <div
                x-show="show"
                x-transition:enter="ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                @click.stop
                {{ $attributes->class([
                    'relative w-full rounded-t-3xl sm:rounded-2xl border border-border bg-background text-foreground shadow-2xl shadow-black/40 overflow-hidden',
                    $maxWidthClass,
                ]) }}
            >
                @isset($title)
                    <div class="flex items-center justify-between gap-3 px-5 py-4 border-b border-border">
                        <h3 class="text-sm font-bold text-foreground">{{ $title }}</h3>
                        @if($closeable)
                            <button
                                type="button" @click="show = false" data-elevated="false"
                                class="btn-press shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-full text-muted hover:bg-secondary hover:text-foreground transition-colors"
                                aria-label="بستن"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        @endif
                    </div>
                @endisset

                <div class="px-5 py-4 text-sm text-foreground max-h-[70vh] overflow-y-auto">
                    {{ $slot }}
                </div>

                @isset($footer)
                    <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-border bg-secondary/40">
                        {{ $footer }}
                    </div>
                @endisset
            </div>
        </div>
    </div>
</template>
