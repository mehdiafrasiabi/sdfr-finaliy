@if ($paginator->hasPages())
    <nav aria-label="پیمایش صفحه">
        <ul class="pagination">
            {{-- دکمه صفحه اول --}}
            @if ($paginator->onFirstPage())
                <li class="page-item first disabled">
                    <span class="page-link">
                        <i class="ti ti-chevrons-right ti-xs"></i>
                    </span>
                </li>
            @else
                <li class="page-item first">
                    <a class="page-link"
                       href="javascript:void(0);"
                       wire:click="gotoPage(1)">
                        <i class="ti ti-chevrons-right ti-xs"></i>
                    </a>
                </li>
            @endif

            {{-- دکمه قبلی --}}
            @if ($paginator->onFirstPage())
                <li class="page-item prev disabled">
                    <span class="page-link">
                        <i class="ti ti-chevron-right ti-xs"></i>
                    </span>
                </li>
            @else
                <li class="page-item prev">
                    <a class="page-link"
                       href="javascript:void(0);"
                       wire:click="previousPage"
                       rel="prev">
                        <i class="ti ti-chevron-right ti-xs"></i>
                    </a>
                </li>
            @endif

            {{-- شماره صفحات --}}
            @foreach ($elements as $element)
                {{-- سه نقطه "..." --}}
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                {{-- آرایه لینک‌ها --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link"
                                   href="javascript:void(0);"
                                   wire:click="gotoPage({{ $page }})">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- دکمه بعدی --}}
            @if ($paginator->hasMorePages())
                <li class="page-item next">
                    <a class="page-link"
                       href="javascript:void(0);"
                       wire:click="nextPage"
                       rel="next">
                        <i class="ti ti-chevron-left ti-xs"></i>
                    </a>
                </li>
            @else
                <li class="page-item next disabled">
                    <span class="page-link">
                        <i class="ti ti-chevron-left ti-xs"></i>
                    </span>
                </li>
            @endif

            {{-- دکمه صفحه آخر --}}
            @if ($paginator->hasMorePages())
                <li class="page-item last">
                    <a class="page-link"
                       href="javascript:void(0);"
                       wire:click="gotoPage({{ $paginator->lastPage() }})">
                        <i class="ti ti-chevrons-left ti-xs"></i>
                    </a>
                </li>
            @else
                <li class="page-item last disabled">
                    <span class="page-link">
                        <i class="ti ti-chevrons-left ti-xs"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
