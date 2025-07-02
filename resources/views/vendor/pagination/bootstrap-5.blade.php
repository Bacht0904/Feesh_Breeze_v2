@if ($paginator
->hasPages())
<nav class="shop-pages d-flex flex-column flex-md-row justify-content-between align-items-center mt-4" aria-label="Phân trang sản phẩm">
   

    {{-- Phân trang chính --}}
    <ul class="pagination align-items-center mb-0">

        {{-- Nút TRƯỚC --}}
        @if ($paginator
        ->onFirstPage())
        <li class="page-item disabled">
            <span class="page-link d-inline-flex align-items-center">
                <svg class="me-1" width="7" height="11">
                    <use href="#icon_prev_sm" />
                </svg>
                TRƯỚC
            </span>
        </li>
        @else
        <li class="page-item">
            <a class="page-link d-inline-flex align-items-center" href="{{ $paginator
->previousPageUrl() }}" rel="prev">
                <svg class="me-1" width="7" height="11">
                    <use href="#icon_prev_sm" />
                </svg>
                TRƯỚC
            </a>
        </li>
        @endif

        {{-- Số trang --}}
        @foreach ($elements as $element)
        @if (is_string($element))
        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
        @endif

        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator
        ->currentPage())
        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
        @else
        <li class="page-item">
            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
        </li>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Nút SAU --}}
        @if ($paginator
        ->hasMorePages())
        <li class="page-item">
            <a class="page-link d-inline-flex align-items-center" href="{{ $paginator
->nextPageUrl() }}" rel="next">
                SAU
                <svg class="ms-1" width="7" height="11">
                    <use href="#icon_next_sm" />
                </svg>
            </a>
        </li>
        @else
        <li class="page-item disabled">
            <span class="page-link d-inline-flex align-items-center">
                SAU
                <svg class="ms-1" width="7" height="11">
                    <use href="#icon_next_sm" />
                </svg>
            </span>
        </li>
        @endif

    </ul>
</nav>
@endif