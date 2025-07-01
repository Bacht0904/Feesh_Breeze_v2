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
                    <!-- <li><a href="my-account.html" class="menu-link menu-link_us-s">Dashboard</a></li> -->

                    <li><a href="{{ route('wishlist') }}" class="menu-link menu-link_us-s">Yêu Thích</a></li>
                    <li><a href="{{ route('cart') }}" class="menu-link menu-link_us-s">Giỏ Hàng</a></li>
                    <li><a href="{{ route('orders.index') }}" class="menu-link menu-link_us-s">Đơn Hàng</a></li>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
                                    <td colspan="5">
                                        <span class="badge bg-{{ $order->status == 'Đã Hủy' ? 'danger' : ($order->status == 'Chờ Xác Nhận' ? 'warning' : 'success') }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
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
                                    <th class="text-center">Mã sản phẩm</th>
                                    <th class="text-center">Tùy chọn</th>
                                    <th class="text-center">Trả hàng</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->details as $item)


                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ asset($item->image) }}" class="image" style="width: 50px; height: 50px; object-fit: cover;">

                                            <a href="#" target="_blank" class="text-decoration-none fw-semibold">{{ $item->product_name }}</a>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">--</td>
                                    <td class="text-center">{{ $item->size ?? '--' }}{{ $item->color ? ', '.$item->color : '' }}</td>
                                    <td class="text-center">Không</td>
                                    <td class="text-center">

                                        @if($canReview)
                                        <form action="{{ route('review.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="product_detail_id" value="{{ $productDetail->id ?? '' }}">

                                            <div class="mb-2">
                                                <label>Đánh giá:</label>
                                                <select name="rating" class="form-select" required>
                                                    @for($i = 5; $i >= 1; $i--)
                                                    <option value="{{ $i }}">{{ $i }} sao</option>
                                                    @endfor
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <textarea name="comment" class="form-control" placeholder="Nhận xét của bạn (tuỳ chọn)"></textarea>
                                            </div>

                                            <button class="btn btn-primary">Gửi đánh giá</button>
                                        </form>
                                        @endif

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

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