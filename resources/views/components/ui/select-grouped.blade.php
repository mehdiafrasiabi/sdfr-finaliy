@props([
    'groups'              => [],
    'groupLabelKey'       => 'grade_label',
    'groupItemsKey'       => 'subjects',
    'valueKey'            => 'id',
    'labelKey'            => 'name',
    'placeholder'         => 'انتخاب کنید...',
    'searchable'          => false,
    'searchPlaceholder'   => 'جستجو...',
    'disabled'            => false,
    'name'                => null,
    'id'                  => null,
])

@php
    $wireModel   = $attributes->whereStartsWith('wire:model')->first();
    $componentId = $id ?? 'x-select-grouped-' . uniqid();

    $flatOptions = [];
    $groupedJson = [];
    foreach ($groups as $group) {
        $items      = is_array($group) ? ($group[$groupItemsKey] ?? []) : [];
        $groupLabel = is_array($group) ? ($group[$groupLabelKey] ?? '') : '';
        $mappedItems = [];
        foreach ($items as $item) {
            $val = is_array($item) ? ($item[$valueKey] ?? '') : '';
            $lbl = is_array($item) ? ($item[$labelKey] ?? '') : '';
            $mappedItems[] = ['value' => $val, 'label' => $lbl];
            $flatOptions[] = ['value' => $val, 'label' => $lbl];
        }
        if (count($mappedItems) > 0) {
            $groupedJson[] = ['group' => $groupLabel, 'items' => $mappedItems];
        }
    }
@endphp

<div
    x-data="{
        open: false,
        search: '',
        selected: null,
        selectedLabel: '',
        groups: {{ json_encode($groupedJson, JSON_UNESCAPED_UNICODE) }},
        flat: {{ json_encode($flatOptions, JSON_UNESCAPED_UNICODE) }},
        placeholder: @js($placeholder),
        disabled: @js($disabled),

        get filteredGroups() {
            if (!this.search) return this.groups;
            const q = this.search.toLowerCase();
            return this.groups.map(g => ({
                group: g.group,
                items: g.items.filter(o => String(o.label).toLowerCase().includes(q))
            })).filter(g => g.items.length > 0);
        },

        selectOption(opt) {
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
                const found = this.flat.find(o => String(o.value) === String(val));
                if (found) { this.selected = found.value; this.selectedLabel = found.label; }
                else        { this.selected = null; this.selectedLabel = ''; }
            }
        }
    }"
    x-init="
        syncFromValue('');
        @if($wireModel)
        $watch('$wire.{{ $wireModel }}', val => syncFromValue(val));
        @endif
    "
    @keydown.escape.window="open = false"
    class="relative w-full"
    dir="rtl"
    id="{{ $componentId }}"
>
    <input type="hidden" x-ref="hiddenInput" name="{{ $name }}" :value="selected ?? ''">

    {{-- Trigger --}}
    <button
        type="button"
        @click.stop="if (!disabled) open = !open"
        :disabled="disabled"
        :class="{
            'border-blue-500 ring-2 ring-blue-500/20 dark:ring-blue-500/30': open,
            'opacity-50 cursor-not-allowed': disabled,
            'cursor-pointer hover:border-blue-400 dark:hover:border-blue-500': !disabled,
        }"
        class="w-full flex items-center justify-between gap-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2.5 text-sm text-right shadow-sm outline-none transition-all duration-150"
    >
        <span
            :class="selectedLabel ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500'"
            class="flex-1 truncate text-right text-sm"
            x-text="selectedLabel || placeholder"
        ></span>

        <span class="flex items-center gap-1 shrink-0">
            <span
                x-show="selected !== null && selected !== ''"
                @mousedown.stop.prevent="clearSelection()"
                class="flex items-center justify-center w-4 h-4 rounded-full text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 transition"
            >
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </span>
            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
        class="absolute z-50 mt-1 w-full rounded-xl overflow-hidden border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 shadow-xl shadow-black/10 dark:shadow-black/50"
    >
        @if($searchable)
            <div class="p-2 border-b border-gray-200 dark:border-gray-600">
                <div class="relative">
                <span class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400 dark:text-gray-500">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </span>
                    <input type="text" x-model="search" x-ref="searchInput"
                           x-init="$watch('open', v => v && $nextTick(() => $refs.searchInput?.focus()))"
                           placeholder="{{ $searchPlaceholder }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 pr-8 pl-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition"
                           @keydown.escape.stop="open = false"
                    >
                </div>
            </div>
        @endif

        <div class="max-h-64 overflow-y-auto py-1">

            <button type="button"
                    @mousedown.prevent="clearSelection(); open = false"
                    class="w-full text-right px-3 py-2 text-sm text-gray-400 dark:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
            >{{ $placeholder }}</button>

            <template x-for="group in filteredGroups" :key="group.group">
                <div>
                    <div
                        class="px-3 py-1.5 mt-1 text-[10px] font-bold tracking-wider text-gray-400 dark:text-gray-500 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900"
                        x-text="group.group"
                    ></div>

                    <template x-for="opt in group.items" :key="opt.value">
                        <button
                            type="button"
                            @mousedown.prevent="selectOption(opt)"
                            :class="{
                                'bg-blue-600 text-white': String(selected) === String(opt.value),
                                'text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700': String(selected) !== String(opt.value),
                            }"
                            class="w-full text-right px-3 py-2.5 text-sm flex items-center justify-between gap-2 transition-colors"
                        >
                            <span x-text="opt.label" class="truncate"></span>
                            <svg x-show="String(selected) === String(opt.value)" class="w-4 h-4 shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                    </template>
                </div>
            </template>

            <div x-show="filteredGroups.length === 0" class="px-3 py-4 text-center text-xs text-gray-400 dark:text-gray-500">
                نتیجه‌ای یافت نشد
            </div>
        </div>
    </div>
</div>
