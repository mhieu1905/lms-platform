@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Prev Button --}}
        @if ($paginator->onFirstPage())
            <span class="pagination-prev pagination__page-numbers disabled">
                <i class="iconify fs-18" data-icon="mingcute:left-line"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-prev pagination__page-numbers">
                <i class="iconify fs-18" data-icon="mingcute:left-line"></i>
            </a>
        @endif

        {{-- Number of pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="pagination__page-numbers disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pagination__page-numbers active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pagination__page-numbers">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next button --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-next pagination__page-numbers">
                <i class="iconify fs-18" data-icon="mingcute:right-line"></i>
            </a>
        @else
            <span class="pagination-next pagination__page-numbers disabled">
                <i class="iconify fs-18" data-icon="mingcute:right-line"></i>
            </span>
        @endif
    </div>
@endif
