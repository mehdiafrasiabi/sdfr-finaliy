<div>
    @props([
        'options'           => [],
        'valueKey'          => 'id',
        'labelKey'          => 'name',
        'placeholder'       => 'انتخاب کنید...',
        'searchable'        => false,
        'searchPlaceholder' => 'جستجو...',
        'disabled'          => false,
        'dropUp'            => false,
    ])

    @php
        $wireModel   = $attributes->whereStartsWith('wire:model')->first();
        $componentId = 'x-multiselect-' . uniqid();

        $jsonOptions = collect($options)->map(fn($opt) => [
            'value' => is_array($opt) ? ($opt[$valueKey] ?? '') : '',
            'label' => is_array($opt) ? ($opt[$labelKey] ?? '') : '',
        ])->values()->toJson(JSON_UNESCAPED_UNICODE);
    @endphp

    {{-- کامپوننت انتخاب چندگانه (multi-select) سفارشی؛ چون در پروژه select چند-انتخابیِ
         آماده‌ای وجود نداشت، این کامپوننت با همان زبان طراحیِ x-ui.select ساخته شده تا
         بشود چند گزینه (مثلاً چند فصل از یک درس) را هم‌زمان انتخاب و اضافه کرد. --}}
    <div
        x-data="{
            open: false,
            search: '',
            disabled: @js($disabled),
            options: {{ $jsonOptions }},
            placeholder: @js($placeholder),
            selected: @if($wireModel) @entangle($wireModel).live @else [] @endif,

            get filtered() {
                if (!this.search) return this.options;
                const q = this.search.toLowerCase();
                return this.options.filter(o => String(o.label).toLowerCase().includes(q));
            },
            isSelected(v) {
                return this.selected.some(x => String(x) === String(v));
            },
            toggle(v) {
                if (this.disabled) return;
                if (this.isSelected(v)) {
                    this.selected = this.selected.filter(x => String(x) !== String(v));
                } else {
                    this.selected = [...this.selected, v];
                }
            },
            get selectedLabels() {
                return this.options.filter(o => this.isSelected(o.value)).map(o => o.label);
            },
            get summary() {
                if (!this.selected.length) return this.placeholder;
                const labels = this.selectedLabels;
                return labels.length <= 2 ? labels.join('، ') : (labels.length + ' فصل انتخاب شده');
            },
        }"
        @keydown.escape.window="open = false"
        class="relative w-full"
        dir="rtl"
        id="{{ $componentId }}"
    >
        {{-- دکمه‌ی باز/بسته‌کردن --}}
        <button
            type="button"
            @click.stop="if (!disabled) { window.dispatchEvent(new CustomEvent('close-selects', { detail: { except: '{{ $componentId }}' } })); open = !open; }"
            :disabled="disabled"
            :class="{
                'border-sky-400 ring-2 ring-sky-400/20': open,
                'opacity-50 cursor-not-allowed': disabled,
                'cursor-pointer hover:border-sky-400/40': !disabled,
            }"
            class="w-full flex items-center justify-between gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2.5 text-sm text-right transition-all duration-150"
            aria-haspopup="listbox"
            :aria-expanded="open"
        >
            <span
                :class="selected.length ? 'text-white font-semibold' : 'text-neutral-500'"
                class="flex-1 truncate text-right text-[13px]"
                x-text="summary"
            ></span>
            <svg class="w-4 h-4 text-neutral-400 transition-transform duration-200 shrink-0" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        {{-- چیپ‌های انتخاب‌شده --}}
        <div class="flex flex-wrap gap-1.5 mt-2" x-show="selected.length > 0" x-cloak>
            <template x-for="label in selectedLabels" :key="label">
                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-sky-500/15 ring-1 ring-sky-400/30 text-[11px] text-sky-200" x-text="label"></span>
            </template>
        </div>

        {{-- منو --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-1 scale-[0.98]"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 -translate-y-1 scale-[0.98]"
            @click.outside="open = false"
            class="{{ $dropUp ? 'absolute z-[130] bottom-full mb-1' : 'absolute z-[130] mt-1' }} w-full rounded-xl overflow-hidden border border-white/10 bg-[#141a29] shadow-xl shadow-black/40"
            dir="rtl"
            role="listbox"
            x-cloak
        >
            @if($searchable)
                <div class="p-2 border-b border-white/10">
                    <input type="text" x-model="search" placeholder="{{ $searchPlaceholder }}"
                           class="w-full bg-white/5 border border-white/10 rounded-lg px-2.5 py-1.5 text-[12px] text-white placeholder-neutral-500 focus:outline-none focus:border-sky-400">
                </div>
            @endif

            <div class="max-h-56 overflow-y-auto py-1">
                <template x-for="opt in filtered" :key="opt.value">
                    <button type="button" @click="toggle(opt.value)"
                            class="w-full flex items-center gap-2.5 px-3 py-2.5 text-[13px] text-right hover:bg-white/5 transition"
                            :class="isSelected(opt.value) ? 'bg-sky-500/10' : ''">
                        <span class="w-4 h-4 rounded border flex items-center justify-center shrink-0"
                              :class="isSelected(opt.value) ? 'bg-sky-500 border-sky-400' : 'border-white/25'">
                            <svg x-show="isSelected(opt.value)" x-cloak class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <span :class="isSelected(opt.value) ? 'text-white font-semibold' : 'text-neutral-300'" x-text="opt.label"></span>
                    </button>
                </template>
                <template x-if="!filtered.length">
                    <div class="text-center text-neutral-500 text-[12px] py-4">موردی یافت نشد.</div>
                </template>
            </div>

            <div class="p-2 border-t border-white/10">
                <button type="button" @click="open = false"
                        class="w-full py-2 rounded-lg bg-sky-500 hover:bg-sky-400 text-white text-[12px] font-bold transition">تأیید</button>
            </div>
        </div>
    </div>
</div>
