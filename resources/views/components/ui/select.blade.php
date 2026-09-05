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
        dropUp: {{ $dropUp ? 'true' : 'false' }},
        menuStyle: '',

        // موقعیت‌دهیِ واقعیِ منو نسبت به viewport (نه والدِ نزدیک)، تا وقتی dropUp فعاله
        // منو همیشه دقیقاً بالای دکمه باز بشه و هیچ‌وقت (مخصوصاً روی سافاری iOS، وقتی
        // یکی از والدها transform/animation داره) به اشتباه پایین باز نشه یا از صفحه بیرون نزنه.
        positionMenu() {
            if (!this.dropUp) return;
            this.$nextTick(() => {
                const btn  = this.$refs.trigger;
                const menu = this.$refs.menu;
                if (!btn || !menu) return;
                const rect   = btn.getBoundingClientRect();
                const gap    = 4;
                const menuH  = menu.offsetHeight;
                let top = rect.top - menuH - gap;
                if (top < 8) top = 8; // اگر بالای صفحه هم جا نشد، به لبه‌ی بالای viewport بچسبه
                this.menuStyle = `left:${rect.left}px; top:${top}px; width:${rect.width}px;`;
            });
        },

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
        $watch('open', (v) => { syncStackLayer(); if (v) positionMenu(); });
        syncStackLayer();
        window.addEventListener('close-selects', e => { if (e.detail.except !== '{{ $componentId }}') open = false; });
    "
        @keydown.escape.window="open = false"
        @resize.window="if (open) positionMenu()"
        @scroll.window="if (open) positionMenu()"
        :class="open ? 'z-[120]' : 'z-0'"
        class="relative w-full"
        dir="rtl"
        id="{{ $componentId }}"
    >
        <input type="hidden" x-ref="hiddenInput" name="{{ $name }}" :value="selected ?? ''">

        {{-- Trigger --}}
        <button
            type="button"
            x-ref="trigger"
            @click.stop="if (!disabled) { if (!open) window.dispatchEvent(new CustomEvent('close-selects', { detail: { except: '{{ $componentId }}' } })); open = !open; }"
            :disabled="disabled"
            :class="{
            'border-blue-500 ring-2 ring-blue-500/20 dark:ring-blue-500/30': open,
            'opacity-50 cursor-not-allowed': disabled,
            'cursor-pointer hover:border-primary/60': !disabled,
        }"
            class="w-full flex items-center justify-between gap-2 rounded-lg border border-border bg-secondary px-3 py-2.5 text-sm text-right shadow-sm outline-none transition-all duration-150"
            aria-haspopup="listbox"
            :aria-expanded="open"
        >
        <span
            :class="selectedLabel ? 'text-foreground' : 'text-muted'"
            class="flex-1 truncate text-right text-sm"
            x-text="selectedLabel || placeholder"
        ></span>

            <span class="flex items-center gap-1 shrink-0">
            <span
                x-show="selected !== null && selected !== ''"
                @mousedown.stop.prevent="clearSelection()"
                class="flex items-center justify-center w-4 h-4 rounded-full text-muted hover:text-red-500 transition"
            >
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </span>
            <svg class="w-4 h-4 text-muted transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </span>
        </button>

        {{-- Dropdown --}}
        @if($dropUp)
            {{-- با x-teleport به body منتقل می‌شود تا هیچ والدی (مثلاً مودال‌های bottom-sheet که
                 transform/animation دارند) روی containing-block و clipping آن اثر نگذارد؛ این دقیقاً
                 همان چیزی‌ست که باعث می‌شد در بعضی نسخه‌های سافاری iOS، با اینکه dropUp فعال بود،
                 منو گاهی پایین و بیرون از صفحه باز شود. موقعیت دقیق با positionMenu() (نسبت‌به‌viewport،
                 هماهنگ با getBoundingClientRect) محاسبه و از طریق menuStyle اعمال می‌شود. --}}
            <template x-teleport="body">
                <div
                    x-ref="menu"
                    x-show="open"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1 scale-[0.98]"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 -translate-y-1 scale-[0.98]"
                    @click.outside="open = false"
                    class="fixed z-[99999] w-full rounded-xl overflow-hidden border border-border bg-secondary shadow-xl shadow-black/40"
                    :style="menuStyle"
                    dir="rtl"
                    role="listbox"
                >
                    @include('components.ui.select-menu-content', ['searchable' => $searchable, 'searchPlaceholder' => $searchPlaceholder, 'placeholder' => $placeholder])
                </div>
            </template>
        @else
            <div
                x-ref="menu"
                x-show="open"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 -translate-y-1 scale-[0.98]"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 -translate-y-1 scale-[0.98]"
                @click.outside="open = false"
                class="absolute z-[99999] mt-1 w-full rounded-xl overflow-hidden border border-border bg-secondary shadow-xl shadow-black/40"
                style="min-width: 100%; z-index: 9999;"
                role="listbox"
            >
                @include('components.ui.select-menu-content', ['searchable' => $searchable, 'searchPlaceholder' => $searchPlaceholder, 'placeholder' => $placeholder])
            </div>
        @endif
    </div>
</div>
