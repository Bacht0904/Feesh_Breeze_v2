@if ($paginator->hasPages())
<nav class="shop-pages d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 w-100" aria-label="Phân trang sản phẩm">

    <div class="d-flex justify-content-center w-100 gap-4 flex-wrap flex-md-nowrap">

        {{-- Nút TRƯỚC --}}
        @if ($paginator->onFirstPage())
            <span class="btn-link d-inline-flex align-items-center text-muted">
                <svg class="me-1" width="7" height="11" viewBox="0 0 7 11"><use href="#icon_prev_sm" /></svg>
                <span class="fw-medium">TRƯỚC</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn-link d-inline-flex align-items-center">
                <svg class="me-1" width="7" height="11" viewBox="0 0 7 11"><use href="#icon_prev_sm" /></svg>
                <span class="fw-medium">TRƯỚC</span>
            </a>
        @endif

        {{-- Số trang hiện tại --}}
        <ul class="pagination mb-0">
            <li class="page-item active">
                <a class="btn-link px-2 mx-1 btn-link_active">{{ $paginator->currentPage() }}</a>
            </li>
        </ul>

        {{-- Nút SAU --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn-link d-inline-flex align-items-center">
                <span class="fw-medium me-1">SAU</span>
                <svg width="7" height="11" viewBox="0 0 7 11"><use href="#icon_next_sm" /></svg>
            </a>
        @else
            <span class="btn-link d-inline-flex align-items-center text-muted">
                <span class="fw-medium me-1">SAU</span>
                <svg width="7" height="11" viewBox="0 0 7 11"><use href="#icon_next_sm" /></svg>
            </span>
        @endif

    </div>

</nav>
@endif
