<div>
    @props([
    'options'             => [],
    'valueKey'            => 'id',
    'labelKey'            => 'name',
    'placeholder'         => 'انتخاب کنید...',
    'searchable'          => false,
    'searchPlaceholder'   => 'جستجو...',
    'disabled'            => false,
    'name'                => null,
    'id'                  => null,
    'dropUp'              => false,
])

    @php
        $wireModel   = $attributes->whereStartsWith('wire:model')->first();
        $componentId = $id ?? 'x-select-' . uniqid();

        $jsonOptions = collect($options)->map(fn($opt) => [
            'value' => is_array($opt) ? ($opt[$valueKey] ?? '') : '',
            'label' => is_array($opt) ? ($opt[$labelKey] ?? '') : '',
        ])->values()->toJson(JSON_UNESCAPED_UNICODE);

        $currentValue = '';
        if ($wireModel && isset($__livewire)) {
            try {
                $parts = explode('.', $wireModel);
                $val = $__livewire;
                foreach ($parts as $part) {
                    $val = is_array($val) ? ($val[$part] ?? null) : ($val->$part ?? null);
                }
                $currentValue = $val ?? '';
            } catch (\Throwable $e) {
                $currentValue = '';
            }
        }
    @endphp
    <div
        x-data="{
        open: false,
        search: '',
        selected: null,
        selectedLabel: '',
        options: {{ $jsonOptions }},
        placeholder: @js($placeholder),
        disabled: @js($disabled),
        initialValue: @js((string) $currentValue),

        get filtered() {
            if (!this.search) return this.options;
            const q = this.search.toLowerCase();
            return this.options.filter(o => String(o.label).toLowerCase().includes(q));
        },

        selectOption(opt) {
            if (!opt.value && opt.value !== 0) { this.clearSelection(); return; }
            this.selected      = opt.value;
            this.selectedLabel = opt.label;
            this.open          = false;
            this.search        = '';
            this.$refs.hiddenInput.value = opt.value;
            this.$refs.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
            @if($wireModel) $wire.set(@js($wireModel), opt.value); @endif
        },

        clearSelection() {
            this.selected      = null;
            this.selectedLabel = '';
            this.$refs.hiddenInput.value = '';
            this.$refs.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
            @if($wireModel) $wire.set(@js($wireModel), ''); @endif
        },

        syncFromValue(val) {
            if (val === '' || val === null || val === undefined) {
                this.selected = null; this.selectedLabel = '';
            } else {
                const found = this.options.find(o => String(o.value) === String(val));
                if (found) { this.selected = found.value; this.selectedLabel = found.label; }
                else        { this.selected = null; this.selectedLabel = ''; }
            }
        },

        syncStackLayer() {
            const stackParent = this.$el.closest('[data-select-stack]');

            if (!stackParent) {
                return;
            }

            if (this.open) {
                stackParent.dataset.selectOpen = 'true';
                stackParent.style.position = 'relative';
                stackParent.style.zIndex = '110';
            } else {
                stackParent.dataset.selectOpen = 'false';
                stackParent.style.zIndex = '';
            }
        }
    }"
        x-init="
        syncFromValue(initialValue);
        @if($wireModel)
        $watch('$wire.{{ $wireModel }}', val => syncFromValue(val));
        @endif
        $watch('open', () => syncStackLayer());
        syncStackLayer();
        window.addEventListener('close-selects', e => { if (e.detail.except !== '{{ $componentId }}') open = false; });
    "
        @keydown.escape.window="open = false"
        :class="open ? 'z-[120]' : 'z-0'"
        class="relative w-full"
        dir="rtl"
        id="{{ $componentId }}"
    >
        <input type="hidden" x-ref="hiddenInput" name="{{ $name }}" :value="selected ?? ''">

        {{-- Trigger --}}
        <button
            type="button"
            @click.stop="if (!disabled) { if (!open) window.dispatchEvent(new CustomEvent('close-selects', { detail: { except: '{{ $componentId }}' } })); open = !open; }"
            :disabled="disabled"
            :class="{
            'border-blue-500 ring-2 ring-blue-500/20 dark:ring-blue-500/30': open,
            'opacity-50 cursor-not-allowed': disabled,
            'cursor-pointer hover:border-blue-400 dark:hover:border-blue-500': !disabled,
        }"
            class="w-full flex items-center justify-between gap-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-secondary px-3 py-2.5 text-sm text-right shadow-sm outline-none transition-all duration-150"
            aria-haspopup="listbox"
            :aria-expanded="open"
        >
        <span
            :class="selectedLabel ? 'text-gray-900 dark:text-gray-100' : 'text-white'"
            class="flex-1 truncate text-right text-sm"
            x-text="selectedLabel || placeholder"
        ></span>

            <span class="flex items-center gap-1 shrink-0">
            <span
                x-show="selected !== null && selected !== ''"
                @mousedown.stop.prevent="clearSelection()"
                class="flex items-center justify-center w-4 h-4 rounded-full text-white hover:text-red-500 dark:hover:text-red-400 transition"
            >
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </span>
            <svg class="w-4 h-4 text-white transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </span>
        </button>

        {{-- Dropdown --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-1 scale-[0.98]"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 -translate-y-1 scale-[0.98]"
            @click.outside="open = false"
            class="absolute z-[99999] {{ $dropUp ? 'bottom-full mb-1' : 'mt-1' }} w-full rounded-xl overflow-hidden border border-gray-200 dark:border-gray-600 bg-secondary shadow-xl shadow-black/10 dark:shadow-black/50"
            style="min-width: 100%; z-index: 9999;"
            role="listbox"
        >
            @if($searchable)
                <div class="p-2 border-b border-gray-200 dark:border-gray-600">
                    <div class="relative">
                <span class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-white">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </span>
                        <input type="text" x-model="search" x-ref="searchInput"
                               x-init="$watch('open', v => v && $nextTick(() => $refs.searchInput?.focus()))"
                               placeholder="{{ $searchPlaceholder }}"
                               class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-background dark:bg-background text-white placeholder-gray-400 dark:placeholder-gray-500 pr-8 pl-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition"
                               @keydown.escape.stop="open = false"
                        >
                    </div>
                </div>
            @endif

            <div class="max-h-56 overflow-y-auto py-1" role="listbox">

                <button type="button"
                        @mousedown.prevent="clearSelection(); open = false"
                        class="w-full text-right px-3 py-2 text-sm text-white hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        role="option"
                >{{ $placeholder }}</button>

                <template x-for="opt in filtered" :key="opt.value">
                    <button
                        type="button"
                        @mousedown.prevent="selectOption(opt)"
                        :class="{
                        'bg-blue-600 text-white': String(selected) === String(opt.value),
                        'text-white dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700': String(selected) !== String(opt.value),
                    }"
                        class="w-full text-right px-3 py-2.5  text-sm flex items-center justify-between gap-2 transition-colors"
                        role="option"
                        :aria-selected="String(selected) === String(opt.value)"
                    >
                        <span x-text="opt.label" class="truncate"></span>
                        <svg x-show="String(selected) === String(opt.value)" class="w-4 h-4 shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </template>

                <div x-show="filtered.length === 0" class="px-3 py-4 text-center text-xs text-white">
                    نتیجه‌ای یافت نشد
                </div>
            </div>
        </div>
    </div>
</div>
