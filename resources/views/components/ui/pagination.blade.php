{{--
    x-ui.pagination — نسخه‌ی به‌روزشده‌ی layouts/client/pagination.blade.php با
    توکن‌های رنگی پروژه (به‌جای gray-100/gray-800 هاردکد) تا با toggle شدنِ
    dark/light خودش را وفق بدهد، به‌علاوه‌ی حس فشاریِ دکمه‌ها.

    استفاده — دقیقاً مثل paginator پیش‌فرض لاراول/Livewire:
        {{ $students->links('components.ui.pagination') }}
    یا مستقیم:
        x-ui.pagination :paginator="$students"

    Props:
      paginator : یک نمونه از LengthAwarePaginator/Paginator
      perWindow : تعداد شماره‌صفحه‌ی هم‌زمان قابل‌نمایش (پیش‌فرض ۵)
--}}
@props([
    'paginator',
    'perWindow' => 5,
])

@once('sdfr-ui-kit-assets')
    @include('components.ui._kit-assets')
@endonce

@if($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last    = $paginator->lastPage();
        $start   = (int) (floor(($current - 1) / $perWindow) * $perWindow) + 1;
        $end     = min($start + $perWindow - 1, $last);
    @endphp

    <nav class="flex justify-center mt-6" role="navigation" aria-label="ناوبری صفحات">
        <ul class="inline-flex flex-wrap items-center justify-center gap-1.5 sm:gap-2">

            {{-- قبلی --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="inline-flex items-center px-3 sm:px-4 py-1.5 text-xs sm:text-sm font-medium text-muted bg-secondary/60 border border-border rounded-full cursor-not-allowed">
                        قبلی
                    </span>
                </li>
            @else
                <li>
                    <button
                        wire:click="previousPage" rel="prev" data-elevated="false"
                        class="btn-press inline-flex items-center px-3 sm:px-4 py-1.5 text-xs sm:text-sm font-medium text-foreground bg-secondary border border-border rounded-full transition-colors hover:bg-border/60"
                    >
                        قبلی
                    </button>
                </li>
            @endif

            {{-- ... ابتدا --}}
            @if ($start > 1)
                <li class="hidden sm:block">
                    <span class="px-2 py-1.5 text-sm text-muted">...</span>
                </li>
            @endif

            {{-- شماره‌صفحه‌ها --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $current)
                    <li>
                        <span class="inline-flex items-center justify-center min-w-[2.25rem] px-3 sm:px-4 py-1.5 text-xs sm:text-sm font-bold text-white bg-primary rounded-full shadow-md shadow-primary/20">
                            {{ $page }}
                        </span>
                    </li>
                @else
                    <li>
                        <button
                            wire:click="gotoPage({{ $page }})" data-elevated="false"
                            class="btn-press inline-flex items-center justify-center min-w-[2.25rem] px-3 sm:px-4 py-1.5 text-xs sm:text-sm font-medium text-foreground bg-secondary border border-border rounded-full transition-colors hover:bg-border/60"
                        >
                            {{ $page }}
                        </button>
                    </li>
                @endif
            @endfor

            {{-- ... انتها --}}
            @if ($end < $last)
                <li class="hidden sm:block">
                    <span class="px-2 py-1.5 text-sm text-muted">...</span>
                </li>
            @endif

            {{-- بعدی --}}
            @if ($paginator->hasMorePages())
                <li>
                    <button
                        wire:click="nextPage" rel="next" data-elevated="false"
                        class="btn-press inline-flex items-center px-3 sm:px-4 py-1.5 text-xs sm:text-sm font-medium text-foreground bg-secondary border border-border rounded-full transition-colors hover:bg-border/60"
                    >
                        بعدی
                    </button>
                </li>
            @else
                <li>
                    <span class="inline-flex items-center px-3 sm:px-4 py-1.5 text-xs sm:text-sm font-medium text-muted bg-secondary/60 border border-border rounded-full cursor-not-allowed">
                        بعدی
                    </span>
                </li>
            @endif

        </ul>
    </nav>
@endif
