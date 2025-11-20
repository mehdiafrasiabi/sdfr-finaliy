@if ($paginator->hasPages())
    <p class="!mb-0 text-sm"></p>

    <ol class="mt-[10px] sm:mt-0 flex justify-center" dir="ltr">

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="inline-block mx-[1px]">
                <a href="javascript:void(0);"
                   class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md
                          border border-gray-100 dark:border-[#172036] opacity-50 cursor-not-allowed">
                    <span class="opacity-0">0</span>
                    <i class="material-symbols-outlined absolute left-0 right-0 top-1/2 -translate-y-1/2">
                        chevron_left
                    </i>
                </a>
            </li>
        @else
            <li class="inline-block mx-[1px]">
                <a href="javascript:void(0);"
                   wire:click="previousPage('{{ $pageName }}')"
                   rel="prev"
                   class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md
                          border border-gray-100 dark:border-[#172036] transition-all
                          hover:bg-primary-500 hover:text-white hover:border-primary-500">
                    <span class="opacity-0">0</span>
                    <i class="material-symbols-outlined absolute left-0 right-0 top-1/2 -translate-y-1/2">
                        chevron_left
                    </i>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)

            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="inline-block mx-[1px]">
                    <a href="javascript:void(0);"
                       class="w-[31px] h-[31px] block leading-[29px] text-center rounded-md
                              border border-gray-100 dark:border-[#172036] cursor-default">
                        {{ $element }}
                    </a>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="inline-block mx-[1px]">
                            <a href="javascript:void(0);"
                               class="w-[31px] h-[31px] block leading-[29px] text-center rounded-md
                                      border border-primary-500 bg-primary-500 text-white">
                                {{ $page }}
                            </a>
                        </li>
                    @else
                        <li class="inline-block mx-[1px]">
                            <a href="javascript:void(0);"
                               wire:click="gotoPage({{ $page }}, '{{ $pageName }}')"
                               class="w-[31px] h-[31px] block leading-[29px] text-center rounded-md
                                      border border-gray-100 dark:border-[#172036] transition-all
                                      hover:bg-primary-500 hover:text-white hover:border-primary-500">
                                {{ $page }}
                            </a>
                        </li>
                    @endif
                @endforeach
            @endif

        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="inline-block mx-[1px]">
                <a href="javascript:void(0);"
                   wire:click="nextPage('{{ $pageName }}')"
                   rel="next"
                   class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md
                          border border-gray-100 dark:border-[#172036] transition-all
                          hover:bg-primary-500 hover:text-white hover:border-primary-500">
                    <span class="opacity-0">0</span>
                    <i class="material-symbols-outlined absolute left-0 right-0 top-1/2 -translate-y-1/2">
                        chevron_right
                    </i>
                </a>
            </li>
        @else
            <li class="inline-block mx-[1px]">
                <a href="javascript:void(0);"
                   class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md
                          border border-gray-100 dark:border-[#172036] opacity-50 cursor-not-allowed">
                    <span class="opacity-0">0</span>
                    <i class="material-symbols-outlined absolute left-0 right-0 top-1/2 -translate-y-1/2">
                        chevron_right
                    </i>
                </a>
            </li>
        @endif

    </ol>
@endif
