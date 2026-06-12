@if ($paginator->hasPages())
    <nav class="flex justify-center mt-6" role="navigation" aria-label="Pagination Navigation">
        <ul class="inline-flex items-center space-x-2 rtl:space-x-reverse">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="inline-flex items-center px-4 py-1.5 text-sm font-medium text-gray-400 bg-gray-100 rounded-full cursor-not-allowed dark:bg-gray-800 dark:text-gray-600">
                        قبلی
                    </span>
                </li>
            @else
                <li>
                    <button wire:click="previousPage" rel="prev"
                            class="inline-flex items-center px-4 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full transition-colors duration-200 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                        قبلی
                    </button>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- Dots --}}
                @if (is_string($element))
                    <li>
                        <span class="px-3 py-1.5 text-sm text-gray-400 dark:text-gray-500">{{ $element }}</span>
                    </li>
                @endif

                {{-- Page Numbers --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="inline-flex items-center px-4 py-1.5 text-sm font-bold text-white bg-primary rounded-full shadow-md shadow-primary/20 dark:shadow-none">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <button wire:click="gotoPage({{ $page }})"
                                        class="inline-flex items-center px-4 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full transition-colors duration-200 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                                    {{ $page }}
                                </button>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <button wire:click="nextPage" rel="next"
                            class="inline-flex items-center px-4 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full transition-colors duration-200 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                        بعدی
                    </button>
                </li>
            @else
                <li>
                    <span class="inline-flex items-center px-4 py-1.5 text-sm font-medium text-gray-400 bg-gray-100 rounded-full cursor-not-allowed dark:bg-gray-800 dark:text-gray-600">
                        بعدی
                    </span>
                </li>
            @endif

        </ul>
    </nav>
@endif
