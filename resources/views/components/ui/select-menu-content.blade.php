{{-- محتوای داخلیِ منوی x-ui.select — در یک فایل جدا تا هم در حالت عادی و هم در حالت
     dropUp (که با x-teleport به body منتقل می‌شود) بدون تکرار کد استفاده شود. --}}
@if($searchable)
    <div class="p-2 border-b border-border">
        <div class="relative">
            <span class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-muted">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
            </span>
            <input type="text" x-model="search" x-ref="searchInput"
                   x-init="$watch('open', v => v && $nextTick(() => $refs.searchInput?.focus()))"
                   placeholder="{{ $searchPlaceholder }}"
                   class="w-full rounded-lg border border-border bg-background text-foreground placeholder:text-muted pr-8 pl-3 py-2 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition"
                   @keydown.escape.stop="open = false"
            >
        </div>
    </div>
@endif

<div class="max-h-56 overflow-y-auto py-1" role="listbox">

    <button type="button"
            @mousedown.prevent="clearSelection(); open = false"
            class="w-full text-right px-3 py-2 text-sm text-muted hover:bg-background transition-colors"
            role="option"
    >{{ $placeholder }}</button>

    <template x-for="opt in filtered" :key="opt.value">
        <button
            type="button"
            @mousedown.prevent="selectOption(opt)"
            :class="{
            'bg-primary text-primary-foreground': String(selected) === String(opt.value),
            'text-foreground hover:bg-background': String(selected) !== String(opt.value),
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

    <div x-show="filtered.length === 0" class="px-3 py-4 text-center text-xs text-muted">
        نتیجه‌ای یافت نشد
    </div>
</div>
