@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item pagination-prev-nav disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <img data-v-80d9eb16="" src="/img/icons/arrow.svg" style="width: 6px;height: 15px" alt=""> <span class="page-link" aria-hidden="true">Previous</span>
                </li>
            @else
                <li class="page-item pagination-prev-nav">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"><img data-v-80d9eb16="" src="/img/icons/arrow.svg" style="width: 6px;height: 15px" alt=""> Previous</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item pagination-page-nav disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item pagination-page-nav active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item pagination-page-nav"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item pagination-next-nav">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">NEXT <img src="/img/icons/arrow.svg" style="width: 6px;height: 15px" alt=""></a>
                </li>
            @else
                <li class="page-item pagination-next-nav disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">NEXT <img src="/img/icons/arrow.svg" style="width: 6px;height: 15px" alt=""></span>
                </li>
            @endif
        </ul>
    </nav>
@endif
