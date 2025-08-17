@if ($paginator->hasPages())
    <nav class="flex justify-center mt-6" role="navigation" aria-label="Pagination Navigation">
        <ul class="inline-flex items-center space-x-1 rtl:space-x-reverse">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="inline-flex items-center px-3 py-1 text-sm text-gray-400 bg-gray-100 rounded-full cursor-not-allowed">
                        قبلی
                    </span>
                </li>
            @else
                <li>
                    <button wire:click="previousPage" rel="prev"
                            class="inline-flex items-center px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-full hover:bg-gray-100">
                        قبلی
                    </button>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- Dots --}}
                @if (is_string($element))
                    <li>
                        <span class="px-3 py-1 text-sm text-gray-400">{{ $element }}</span>
                    </li>
                @endif

                {{-- Page Numbers --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="inline-flex items-center px-3 py-1 text-sm font-semibold text-white bg-primary rounded-full shadow">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <button wire:click="gotoPage({{ $page }})"
                                        class="inline-flex items-center px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-full hover:bg-gray-100">
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
                            class="inline-flex items-center px-3 py-1 text-sm text-gray-700 bg-white border border-gray-300 rounded-full hover:bg-gray-100">
                        بعدی
                    </button>
                </li>
            @else
                <li>
                    <span class="inline-flex items-center px-3 py-1 text-sm text-gray-400 bg-gray-100 rounded-full cursor-not-allowed">
                        بعدی
                    </span>
                </li>
            @endif

        </ul>
    </nav>
@endif
