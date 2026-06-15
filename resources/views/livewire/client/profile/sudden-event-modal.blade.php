<div
    x-data="{ open: @entangle('open') }"
    x-effect="document.body.classList.toggle('overflow-hidden', open)"
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
        class="fixed inset-0 z-[120] bg-black/60 backdrop-blur-sm"
        @click="$wire.close()"
        aria-hidden="true"
    ></div>

    {{-- ========== پنل واحد: موبایل از پایین (bottom-sheet) / دسکتاپ وسط ========== --}}
    <div
        x-show="open"
        class="fixed inset-0 z-[121] flex items-end justify-center md:items-center md:p-4"
        @click.self="$wire.close()"
    >
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-full md:translate-y-6 md:scale-95 opacity-0"
            x-transition:enter-end="translate-y-0 md:scale-100 opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-y-0 md:scale-100 opacity-100"
            x-transition:leave-end="translate-y-full md:translate-y-6 md:scale-95 opacity-0"
            class="relative w-full md:max-w-md max-h-[90vh] overflow-auto rounded-t-3xl md:rounded-2xl bg-[#0d121e]/95 backdrop-blur-2xl border border-white/10 shadow-2xl"
        >
            {{-- هندل کشیدن (فقط موبایل) --}}
            <div class="md:hidden flex justify-center pt-2.5 pb-0.5">
                <div class="w-12 h-1 rounded-full bg-white/20"></div>
            </div>

            @include('livewire.client.profile._sudden-event-content')
        </div>
    </div>
</div>
