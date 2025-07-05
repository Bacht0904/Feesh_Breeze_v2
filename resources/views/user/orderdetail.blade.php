@extends('layouts.app')

@section('content')
<style>
    .pt-90 {
        padding-top: 90px !important;
    }

    .pr-6px {
        padding-right: 6px;
        text-transform: uppercase;
    }

    .my-account .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 40px;
        border-bottom: 1px solid;
        padding-bottom: 13px;
    }

    .my-account .wg-box {
        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        padding: 24px;
        flex-direction: column;
        gap: 24px;
        border-radius: 12px;
        background: var(--White);
        box-shadow: 0px 4px 24px 2px rgba(20, 25, 38, 0.05);
    }

    .bg-success {
        background-color: #40c710 !important;
    }

    .bg-danger {
        background-color: #f44032 !important;
    }

    .bg-warning {
        background-color: #f5d700 !important;
        color: #000;
    }

    .table-transaction>tbody>tr:nth-of-type(odd) {
        --bs-table-accent-bg: #fff !important;

    }

    .table-transaction th,
    .table-transaction td {
        padding: 0.625rem 1.5rem .25rem !important;
        color: #000 !important;
    }

    .table> :not(caption)>tr>th {
        padding: 0.625rem 1.5rem .25rem !important;
        background-color: #6a6e51 !important;
    }

    .table-bordered>:not(caption)>*>* {
        border-width: inherit;
        line-height: 32px;
        font-size: 14px;
        border: 1px solid #e1e1e1;
        vertical-align: middle;
    }

    .table-striped .image {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        flex-shrink: 0;
        border-radius: 10px;
        overflow: hidden;
    }

    .table-striped td:nth-child(1) {
        min-width: 250px;
        padding-bottom: 7px;
    }

    .pname {
        display: flex;
        gap: 13px;
    }

    .table-bordered> :not(caption)>tr>th,
    .table-bordered> :not(caption)>tr>td {
        border-width: 1px 1px;
        border-color: #6a6e51;
    }
</style>
<main class="pt-90" style="padding-top: 0px;">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title">Chi Tiết Đơn Hàng</h2>
        <div class="row">
            <div class="col-lg-2">
                <ul class="account-nav">
                    <li><a href="{{ route('wishlist') }}" class="menu-link menu-link_us-s">Yêu Thích</a></li>
                    <li><a href="{{ route('cart') }}" class="menu-link menu-link_us-s">Giỏ Hàng</a></li>
                    <li><a href="{{ route('orders.index') }}" class="menu-link menu-link_us-s">Đơn Hàng</a></li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    <a href="#" class="menu-link menu-link_us-s" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Đăng xuất
                    </a>

                </ul>
            </div>

            <div class="col-lg-10">
                {{-- Thông tin đơn hàng --}}
                <div class="wg-box mt-5 mb-5">
                    <div class="row">
                        <div class="col-6">
                            <h5>Thông tin đơn hàng</h5>
                        </div>
                        <div class="col-6 text-end">
                            <a class="btn btn-sm btn-danger" href="{{ route('orders.index') }}">← Quay lại</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-transaction">
                            <tbody>
                                <tr>
                                    <th>Mã đơn</th>
                                    <td>{{ $order->id }}</td>
                                    <th>Số điện thoại</th>
                                    <td>{{ $order->phone }}</td>

                                </tr>
                                <tr>
                                    <th>Ngày đặt hàng</th>
                                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</td>
                                    <th>Email</th>
                                    <td>{{ Auth::user()->email ?? '--' }}</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái đơn hàng</th>
                                    <td>
                                        <span class="badge bg-{{ $order->status == 'Đã Hủy' ? 'danger' : ($order->status == 'Chờ Xác Nhận' ? 'warning' : 'success') }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    @switch($order->status)
                                    @case('Chờ Xác Nhận')

                                    <th>Yêu cầu hủy hàng</th>
                                    <td colspan="5">
                                        <form action="{{ route('orders.refund', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-danger">Hủy đơn hàng</button>
                                        </form>
                                    </td>

                                    @break

                                    @case('Xác Nhận Hủy')

                                    <th>Trạng thái đơn hàng</th>
                                    <td colspan="5">
                                        <span class="badge bg-danger">Xác nhận hủy</span><br>
                                        <small>Đơn hàng đang chờ xác nhận hủy từ hệ thống.</small>
                                    </td>

                                    @break

                                    @case('Đã Hủy')

                                    <th>Trạng thái đơn hàng</th>
                                    <td colspan="5">
                                        <span class="badge bg-dark">Đã hủy</span><br>
                                        <small>Yêu cầu hủy hoặc trả hàng đã được xử lý.</small>
                                    </td>

                                    @break
                                    @default
                                    <th>Trạng thái đơn hàng</th>
                                    <td colspan="5">
                                        <span class="badge bg-light text-dark">Vui lòng chờ</span>
                                    </td>
                                    @endswitch
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Danh sách sản phẩm --}}
                <div class="wg-box wg-table table-all-user">
                    <h5 class="mb-3">Sản phẩm trong đơn</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-center">Đánh giá</th>
                                    <th class="text-center">Size, Màu</th>
                                    <th class="text-center">Trả hàng</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->details as $item)
                                @php
                                $productDetail = $item->productDetail;
                                $product = $productDetail->product ?? null;
                                $review = $item->review;
                                $canReview = !$review || ($review && $review->status == 0);
                                @endphp
                                <tr>
                                    <!-- Sản phẩm -->
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ asset($item->image) }}" style="width: 50px; height: 50px; object-fit: cover;">
                                            <a href="{{ route('products.show', ['slug' => $product->slug]) }}">
                                                {{ $product->name }}
                                            </a>
                                        </div>
                                    </td>

                                    <!-- Giá -->
                                    <td class="text-center">{{ number_format($item->price, 0, ',', '.') }}₫</td>

                                    <!-- Số lượng -->
                                    <td class="text-center">{{ $item->quantity }}</td>

                                    <!-- Đánh giá -->
                                    <td class="text-center">
                                        @php
                                        $review = $item->review ?? null;
                                        $canReview = !$review || $review->status == 0;
                                        @endphp

                                        @if ($review && $review->status == 1)
                                        <div class="d-flex justify-content-center gap-1 mb-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star {{ $i <= $review->rating ? 'text-warning-custom' : 'text-secondary' }}"></i>
                                                @endfor
                                        </div>
                                        <div class="text-muted small" style="max-width: 240px;">{!! $review->comment !!}</div>
                                        @else
                                        <span class="badge bg-warning">Chưa đánh giá</span>
                                        @endif


                                    </td>

                                    <!-- Size, Màu -->
                                    <td class="text-center">{{ $item->size ?? '--' }}{{ $item->color ? ', '.$item->color : '' }}</td>

                                    <!-- Trả hàng -->
                                    <td class="text-center">Không</td>

                                    <!-- Thao tác -->
                                    <td class="text-center">
                                        @php
                                        $review = $item->review ?? null;
                                        $canReview = !$review || $review->status == 0;
                                        @endphp
                                        @if ($review && $review->status == 1)
                                        <div class="d-flex flex-wrap gap-2">
                                            <button class="btn btn-warning btn-sm px-3 shadow-sm d-flex align-items-center"
                                                onclick="editReview(
                                            {{ $product->id }},
                                            '{{ $product->name }}',
                                            {{ $productDetail->id ?? 'null' }},
                                            {{ $review->rating }},
                                            `{!! $review->comment !!}`,
                                            {{ $review->id }})">
                                                <i class="fa fa-pen-to-square me-1"></i> Sửa
                                            </button>

                                            <form action="{{ route('review.destroy', $review->id) }}" method="POST"
                                                onsubmit="return confirm('Bạn có chắc muốn xoá đánh giá này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm px-3 shadow-sm d-flex align-items-center">
                                                    <i class="fa fa-xmark me-1"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                        <button class="btn btn-success radius"
                                            onclick="showReviewForm({{ $product->id }}, '{{ $product->name }}', {{ $productDetail->id ?? 'null' }})">
                                            Đánh giá
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Đánh giá sản phẩm --}}

                <!-- Review Section -->
                <div id="review-section" class="mt-4 d-none">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light fw-bold">
                            <span id="review-mode-label">Đánh giá sản phẩm:</span>
                            <span id="review-product-name" class="text-primary"></span>
                        </div>
                        <div class="card-body">
                            <form id="review-form" method="POST" action="{{ route('review.store') }}">
                                @csrf
                                <!-- Hidden fields -->
                                <input type="hidden" name="product_id" id="review-product-id">
                                <input type="hidden" name="product_detail_id" id="review-product-detail-id">

                                <!-- Star Rating -->
                                <div class="mb-3">
                                    <label class="form-label d-block">Đánh giá sao *</label>
                                    <div class="star-rating d-flex gap-1">
                                        @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star-{{ $i }}" name="rating" value="{{ $i }}">
                                        <label for="star-{{ $i }}" title="{{ $i }} sao">
                                            <i class="fa fa-star"></i>
                                        </label>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Review Content with Summernote -->
                                <div class="mb-3">
                                    <label for="review-comment" class="form-label">Nhận xét của bạn</label>
                                    <textarea name="comment" id="review-comment" class="form-control" required></textarea>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-success" id="review-submit-btn">Gửi đánh giá</button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="cancelReview()">Hủy</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Scripts: Summernote + Form Handler -->





                {{-- Địa chỉ giao hàng --}}
                <div class="wg-box mt-5">
                    <h5>Địa chỉ giao hàng</h5>
                    <div class="my-account__address-item col-md-6">
                        <div class="my-account__address-item__detail">
                            <p>{{ $order->name }}</p>
                            <p>{{ $order->address }}</p>
                            <p>SĐT: {{ $order->phone }}</p>
                            <p>Email: {{ $order->email ?? '--' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Thông tin thanh toán --}}
                <div class="wg-box mt-5">
                    <h5>Thanh toán</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-transaction">
                            <tbody>
                                <tr>
                                    <th>Tạm tính</th>
                                    <td>{{ number_format($order->suptotal, 0, ',', '.') }}₫</td>
                                    <th>Tiền vận chuyển</th>
                                    <td>{{ $order->shipping_fee == 0 ? 'Miễn phí' : number_format($order->shipping_fee, 0, ',', '.') . '₫' }}</td>
                                    <th>Giảm giá</th>
                                    <td>-{{ number_format($order->coupon_discount ?? 0, 0, ',', '.') }}₫</td>
                                </tr>
                                <tr>
                                    <th>Tổng thanh toán</th>
                                    <td>{{ number_format($order->total, 0, ',', '.') }}₫</td>
                                    <th>Phương thức</th>
                                    <td>{{ $order->payment_method }}</td>
                                    <th>Trạng thái</th>
                                    <td>
                                        <span class="badge bg-{{ $order->payment_status === 'Đã Thanh Toán' ? 'success' : 'warning' }}">
                                            {{ $order->payment_status }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- {{-- Nút hủy --}}
                @if ($order->status === 'Chờ Xác Nhận')
                <div class="wg-box mt-5 text-end">
                    <form action="#" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <button type="submit" class="btn btn-danger">Hủy đơn hàng</button>
                    </form>
                </div>
                @endif -->
            </div>

        </div>
    </section>
</main>
@endsection
@push('styles')
<style>
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        /* căn giữa theo trục ngang */
        align-items: center;
        /* căn giữa theo trục dọc */
        gap: 6px;
        margin-bottom: 10px;
    }

    .text-warning-custom {
        color:rgb(255, 147, 24) !important;
        /* Màu vàng rực rỡ */
    }


    .star-rating input[type="radio"]:not(:checked)~label {
        color: #ccc;
    }

    .star-rating input[type="radio"] {
        display: none;
    }

    .star-rating label {
        font-size: 30px;
        color: #ccc;
        cursor: pointer;
        transition: color 0.2s;
        margin: 0;
        /* tránh lệch do spacing */
    }

    .star-rating label:hover,
    .star-rating label:hover~label {
        color: #ffc107;
    }

    .star-rating input[type="radio"]:checked~label,
    .star-rating input[type="radio"]:checked~label~label {
        color: #ffc107;
    }
</style>
@endpush
@push('scripts')
<script>
    let currentReviewingProductId = null;
</script>
<!-- Summernote CSS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

<script>
    let currentFormContext = null;

    $(document).ready(function() {
        // Khởi tạo Summernote
        $('#review-comment').summernote({
            placeholder: 'Hãy chia sẻ nhận xét về sản phẩm...',
            height: 180,
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });

        // Đồng bộ nội dung Summernote vào textarea khi submit
        $('#review-form').on('submit', function() {
            const content = $('#review-comment').summernote('code');
            $('#review-comment').val(content);

            // Kiểm tra nếu nội dung rỗng (chỉ là <p><br></p>)
            const textOnly = $('<div>').html(content).text().trim();
            if (textOnly.length === 0) {
                alert('Nội dung không được để trống!');
                return false;
            }
        });
    });

    function toggleReviewForm({
        mode,
        productId,
        productName,
        productDetailId = '',
        rating = null,
        comment = '',
        reviewId = null
    }) {
        const form = document.getElementById("review-form");
        const section = document.getElementById("review-section");
        const currentKey = `${mode}-${reviewId ?? productId}`;

        // Ẩn form nếu đang mở đúng form đó
        if (currentFormContext === currentKey) {
            section.classList.add("d-none");
            currentFormContext = null;
            return;
        }

        currentFormContext = currentKey;
        section.classList.remove("d-none");

        // Cập nhật nội dung form
        document.getElementById("review-mode-label").textContent = mode === 'edit' ? "Chỉnh sửa đánh giá:" : "Đánh giá sản phẩm:";
        document.getElementById("review-product-name").innerText = productName;
        document.getElementById("review-product-id").value = productId;
        document.getElementById("review-product-detail-id").value = productDetailId;

        // Đặt lại đánh giá sao
        form.querySelectorAll("input[name='rating']").forEach(input => {
            input.checked = parseInt(input.value) === parseInt(rating);
        });

        // Thiết lập nội dung Summernote
        $('#review-comment').summernote('code', comment || '');

        // Cập nhật action và method
        form.action = mode === 'edit' ?
            `/review/${reviewId}` :
            `{{ route('review.store') }}`;

        form.querySelector("input[name='_method']")?.remove();
        if (mode === 'edit') {
            const methodInput = document.createElement("input");
            methodInput.type = "hidden";
            methodInput.name = "_method";
            methodInput.value = "PUT";
            form.appendChild(methodInput);
        }
    }

    function showReviewForm(productId, productName, productDetailId) {
        toggleReviewForm({
            mode: 'create',
            productId,
            productName,
            productDetailId
        });
    }

    function editReview(productId, productName, detailId, rating, comment, reviewId) {
        toggleReviewForm({
            mode: 'edit',
            productId,
            productName,
            productDetailId: detailId,
            rating,
            comment,
            reviewId
        });
    }

    function cancelReview() {
        document.getElementById("review-section").classList.add("d-none");
        currentFormContext = null;
    }
</script>

@endpush