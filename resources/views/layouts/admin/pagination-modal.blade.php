@if ($paginator->hasPages())
    <nav aria-label="Page navigation example">
        <ul class="pagination pagination-rounded pagination-success" dir="rtl">

            {{-- Previous (double right) --}}
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                @if ($paginator->onFirstPage())
                    <span class="page-link" aria-label="Previous">
                        <i class="fi fi-rr-angle-double-right"></i>
                    </span>
                @else
                    <a
                        class="page-link"
                        href="javascript:void(0);"
                        aria-label="Previous"
                        wire:click="previousPage('{{ $pageName }}')"
                        rel="prev"
                    >
                        <i class="fi fi-rr-angle-double-right"></i>
                    </a>
                @endif
            </li>

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item">
                                <a class="page-link active" href="javascript:void(0);">
                                    {{ $page }}
                                </a>
                            </li>
                        @else
                            <li class="page-item">
                                <a
                                    class="page-link"
                                    href="javascript:void(0);"
                                    wire:click="gotoPage({{ $page }}, '{{ $pageName }}')"
                                >
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next (double left) --}}
            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                @if ($paginator->hasMorePages())
                    <a
                        class="page-link"
                        href="javascript:void(0);"
                        aria-label="Next"
                        wire:click="nextPage('{{ $pageName }}')"
                        rel="next"
                    >
                        <i class="fi fi-rr-angle-double-left"></i>
                    </a>
                @else
                    <span class="page-link" aria-label="Next">
                        <i class="fi fi-rr-angle-double-left"></i>
                    </span>
                @endif
            </li>

        </ul>
    </nav>
@endif
