{{--
    این مودال قبلاً دو بلاکِ کاملاً جدا برای دسکتاپ (hidden md:flex) و موبایل
    (bottom-sheet با ترنزیشنِ مستقل) داشت که محتوای‌شان هم عیناً کپی شده بود.
    الان طبقِ همون الگویِ استانداردِ یکسانِ همه‌ی مودال‌های پروژه بازسازی شده:
    یک پنلِ واحد که با breakpoint سایز sm از حالتِ bottom-sheet به حالتِ
    وسط‌چین/scale سوییچ می‌کند.
--}}
@once('sdfr-ui-kit-assets')
    @include('components.ui._kit-assets')
@endonce

<div
    x-data="{ open: @entangle('open') }"
    x-effect="open ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
    x-cloak
>
    {{-- ========== Backdrop ========== --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm"
        @click="$wire.close()"
        aria-hidden="true"
    ></div>

    {{-- ========== پنل واحد ========== --}}
    <div
        x-show="open"
        class="fixed inset-0 z-[101] flex items-end justify-center overscroll-contain sm:items-center sm:p-4"
        @click.self="$wire.close()"
    >
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
            class="relative w-full sm:max-w-2xl max-h-[90vh] overflow-y-auto bg-background border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl"
        >
            <div class="mx-auto mt-3 mb-1 h-1.5 w-14 rounded-full bg-border sm:hidden"></div>

            @include('livewire.client.profile._subject-detail-content')
        </div>
    </div>
</div>
