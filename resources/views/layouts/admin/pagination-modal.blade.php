@if ($paginator->hasPages())
    <nav aria-label="پیمایش صفحه">
        <ul class="pagination justify-content-center" dir="rtl">

            {{-- دکمه صفحه اول --}}
            <li class="page-item first {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a
                    href="javascript:void(0);"
                    class="page-link"
                    @unless($paginator->onFirstPage())
                        wire:click="gotoPage(1, '{{ $pageName }}')"
                    @endunless
                >
                    <i class="ti ti-chevrons-right ti-xs"></i>
                </a>
            </li>

            {{-- دکمه قبلی --}}
            <li class="page-item prev {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a
                    href="javascript:void(0);"
                    class="page-link"
                    @unless($paginator->onFirstPage())
                        wire:click="previousPage('{{ $pageName }}')"
                    rel="prev"
                    @endunless
                >
                    <i class="ti ti-chevron-right ti-xs"></i>
                </a>
            </li>

            {{-- صفحات میانی --}}
            @foreach ($elements as $element)

                {{-- سه‌نقطه‌ها --}}
                @if (is_string($element))
                    <li class="page-item disabled">
                        <a href="javascript:void(0);" class="page-link">
                            {{ $element }}
                        </a>
                    </li>
                @endif

                {{-- آرایه لینک‌ها --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <a href="javascript:void(0);" class="page-link">
                                    {{ $page }}
                                </a>
                            </li>
                        @else
                            <li class="page-item">
                                <a
                                    href="javascript:void(0);"
                                    class="page-link"
                                    wire:click="gotoPage({{ $page }}, '{{ $pageName }}')"
                                >
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif

            @endforeach

            {{-- دکمه بعدی --}}
            <li class="page-item next {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                <a
                    href="javascript:void(0);"
                    class="page-link"
                    @if ($paginator->hasMorePages())
                        wire:click="nextPage('{{ $pageName }}')"
                    rel="next"
                    @endif
                >
                    <i class="ti ti-chevron-left ti-xs"></i>
                </a>
            </li>

            {{-- دکمه صفحه آخر --}}
            <li class="page-item last {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                <a
                    href="javascript:void(0);"
                    class="page-link"
                    @if ($paginator->hasMorePages())
                        wire:click="gotoPage({{ $paginator->lastPage() }}, '{{ $pageName }}')"
                    @endif
                >
                    <i class="ti ti-chevrons-left ti-xs"></i>
                </a>
            </li>

        </ul>
    </nav>
@endif
