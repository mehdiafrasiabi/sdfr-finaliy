@if ($paginator->hasPages())
    <div class="paginating-container dataTables_paginate paging_simple_numbers" id="custom-livewire-pagination">
        <ul class="pagination">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="paginate_button page-item previous disabled">
                    <a href="javascript:void(0);" class="page-link">قبلی</a>
                </li>
            @else
                <li class="paginate_button page-item previous">
                    <a href="javascript:void(0);" wire:click="previousPage" rel="prev" class="page-link">قبلی</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="paginate_button page-item disabled">
                        <a href="javascript:void(0);" class="page-link">{{ $element }}</a>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="paginate_button page-item active">
                                <a href="javascript:void(0);" class="page-link">{{ $page }}</a>
                            </li>
                        @else
                            <li class="paginate_button page-item">
                                <a href="javascript:void(0);" wire:click="gotoPage({{ $page }})" class="page-link">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="paginate_button page-item next">
                    <a href="javascript:void(0);" wire:click="nextPage" rel="next" class="page-link">بعدی</a>
                </li>
            @else
                <li class="paginate_button page-item next disabled">
                    <a href="javascript:void(0);" class="page-link">بعدی</a>
                </li>
            @endif

        </ul>
    </div>
@endif
