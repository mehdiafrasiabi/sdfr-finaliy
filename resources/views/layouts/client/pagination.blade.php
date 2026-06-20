@if ($paginator->hasPages())
    @php
        $perWindow = 5;
        $current   = $paginator->currentPage();
        $last      = $paginator->lastPage();
        $start     = (int) (floor(($current - 1) / $perWindow) * $perWindow) + 1;
        $end       = min($start + $perWindow - 1, $last);
    @endphp

    <nav class="flex justify-center mt-6" role="navigation" aria-label="Pagination Navigation">
        <ul class="inline-flex flex-wrap items-center justify-center gap-1.5 sm:gap-2">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="inline-flex items-center px-3 sm:px-4 py-1.5 text-xs sm:text-sm font-medium text-gray-400 bg-gray-100 rounded-full cursor-not-allowed dark:bg-gray-800 dark:text-gray-600">
                        قبلی
                    </span>
                </li>
            @else
                <li>
                    <button wire:click="previousPage" rel="prev"
                            class="inline-flex items-center px-3 sm:px-4 py-1.5 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full transition-colors duration-200 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                        قبلی
                    </button>
                </li>
            @endif

            {{-- اگر پنجره از ۱ شروع نشده، یک «...» در ابتدا --}}
            @if ($start > 1)
                <li class="hidden sm:block">
                    <span class="px-2 py-1.5 text-sm text-gray-400 dark:text-gray-500">...</span>
                </li>
            @endif

            {{-- Page Numbers (پنجره ۵تایی) --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $current)
                    <li>
                        <span class="inline-flex items-center justify-center min-w-[2.25rem] px-3 sm:px-4 py-1.5 text-xs sm:text-sm font-bold text-white bg-primary rounded-full shadow-md shadow-primary/20 dark:shadow-none">
                            {{ $page }}
                        </span>
                    </li>
                @else
                    <li>
                        <button wire:click="gotoPage({{ $page }})"
                                class="inline-flex items-center justify-center min-w-[2.25rem] px-3 sm:px-4 py-1.5 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full transition-colors duration-200 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                            {{ $page }}
                        </button>
                    </li>
                @endif
            @endfor

            {{-- اگر پنجره تا آخر نرسیده، یک «...» در انتها --}}
            @if ($end < $last)
                <li class="hidden sm:block">
                    <span class="px-2 py-1.5 text-sm text-gray-400 dark:text-gray-500">...</span>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <button wire:click="nextPage" rel="next"
                            class="inline-flex items-center px-3 sm:px-4 py-1.5 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full transition-colors duration-200 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                        بعدی
                    </button>
                </li>
            @else
                <li>
                    <span class="inline-flex items-center px-3 sm:px-4 py-1.5 text-xs sm:text-sm font-medium text-gray-400 bg-gray-100 rounded-full cursor-not-allowed dark:bg-gray-800 dark:text-gray-600">
                        بعدی
                    </span>
                </li>
            @endif

        </ul>
    </nav>
@endif
