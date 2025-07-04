@extends('layouts.app')

@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
        <div class="checkout-steps">
            <div class="checkout-steps__item active">
                <span class="checkout-steps__item-number">01</span>
                <span class="checkout-steps__item-title">
                    <span>Giỏ hàng</span>
                    <em>Quản lý danh sách sản phẩm</em>
                </span>
            </div>
            <div class="checkout-steps__item active">
                <span class="checkout-steps__item-number">02</span>
                <span class="checkout-steps__item-title">
                    <span>Thanh toán & vận chuyển</span>
                    <em>Xác nhận thông tin đặt hàng</em>
                </span>
            </div>
            <div class="checkout-steps__item active">
                <span class="checkout-steps__item-number">03</span>
                <span class="checkout-steps__item-title">
                    <span>Xác nhận</span>
                    <em>Kiểm tra & gửi đơn</em>
                </span>
            </div>
        </div>

        <div class="order-complete">
            <div class="order-complete__message text-center">
                <svg class="success-icon" width="80" height="80" viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="40" cy="40" r="40" fill="#B9A16B" />
                    <path class="checkmark"
                        d="M52.9743 35.7612C52.9743 35.3426 52.8069 34.9241 52.5056 34.6228L50.2288 32.346C49.9275 32.0446 49.5089 31.8772 49.0904 31.8772C48.6719 31.8772 48.2533 32.0446 47.952 32.346L36.9699 43.3449L32.048 38.4062C31.7467 38.1049 31.3281 37.9375 30.9096 37.9375C30.4911 37.9375 30.0725 38.1049 29.7712 38.4062L27.4944 40.683C27.1931 40.9844 27.0257 41.4029 27.0257 41.8214C27.0257 42.24 27.1931 42.6585 27.4944 42.9598L33.5547 49.0201L35.8315 51.2969C36.1328 51.5982 36.5513 51.7656 36.9699 51.7656C37.3884 51.7656 37.8069 51.5982 38.1083 51.2969L40.385 49.0201L52.5056 36.8996C52.8069 36.5982 52.9743 36.1797 52.9743 35.7612Z"
                        fill="white" />
                </svg>
                <h3 class="mt-3">Đặt hàng thành công!</h3>
                <p>Cảm ơn bạn, đơn hàng của bạn đã được ghi nhận.</p>
            </div>



            <div class="order-info">
                <div class="order-info__item"><label>Mã đơn hàng</label> <span>{{ $order->id }}</span></div>
                <div class="order-info__item"><label>Ngày đặt</label> <span>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</span></div>

                <div class="order-info__item"><label>Tổng tiền</label> <span>{{ number_format($order->total, 0) }} đ</span></div>
                <div class="order-info__item"><label>Phương thức thanh toán</label> <span>{{ $order->payment_method }}</span></div>
            </div>
            <!-- <div class="order-info">
          <div class="order-info__item">
            <label>Order Number</label>
            <span>13119</span>
          </div>
          <div class="order-info__item">
            <label>Date</label>
            <span>27/10/2023</span>
          </div>
          <div class="order-info__item">
            <label>Total</label>
            <span>$81.40</span>
          </div>
          <div class="order-info__item">
            <label>Paymetn Method</label>
            <span>Direct Bank Transfer</span>
          </div> -->

            <!-- <div class="col-md-6">
                    <div class="order-info__item"><label>Khách hàng</label> <span>{{ $order->name }}</span></div>
                    <div class="order-info__item"><label>Số điện thoại</label> <span>{{ $order->phone }}</span></div>
                    <div class="order-info__item"><label>Địa chỉ</label> <span>{{ $order->address }}</span></div>
                    @if($order->email)
                    <div class="order-info__item"><label>Email</label> <span>{{ $order->email }}</span></div>
                    @endif
                </div> -->


            <div class="checkout__totals-wrapper mb-5">
                <div class="checkout__totals">
                    <h3>Chi tiết đơn hàng</h3>
                    <table class="checkout-cart-items">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderDetails as $item)
                            <tr>
                                <td>{{ $item->product_name }} x {{ $item->quantity }}</td>
                                <td>{{ number_format($item->price * $item->quantity, 0) }} đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <table class="checkout-totals">
                        <tbody>
                            <tr>
                                <th>Tạm tính</th>
                                <td>{{ number_format($order->suptotal, 0) }} đ</td>
                            </tr>
                            @if($order->coupon_discount > 0)
                            <tr>
                                <th>Giảm giá</th>
                                <td>-{{ number_format($order->coupon_discount, 0) }} đ</td>
                            </tr>
                            @endif
                            <tr>
                                <th>Phí vận chuyển</th>
                                <td>{{ number_format($order->shipping_fee, 0) }} đ</td>
                            </tr>
                            <tr>
                                <th>Tổng cộng</th>
                                <td><strong>{{ number_format($order->total, 0) }} đ</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</main>
@endsection
@push('slyte')
<style>
    .success-icon {
        transform: scale(0.5);
        opacity: 0;
        animation: popIn 0.6s ease-out forwards;
    }

    @keyframes popIn {
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .checkmark {
        transform-origin: center;
        animation: checkPulse 0.6s ease 0.3s forwards;
    }

    @keyframes checkPulse {
        0% {
            transform: scale(0.8);
            opacity: 0;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>
@endpush