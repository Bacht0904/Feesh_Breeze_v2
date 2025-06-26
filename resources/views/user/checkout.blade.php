@extends('layouts.app')

@section('content')
<main class="pt-90">
  <div class="mb-4 pb-4"></div>
  <section class="shop-checkout container">
    <h2 class="page-title">Vận chuyển và Thanh toán</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="checkout-steps mb-4">
      <a href="{{ route('cart') }}" class="checkout-steps__item active">
        <span class="checkout-steps__item-number">01</span>
        <span class="checkout-steps__item-title">
          <span>Giỏ Hàng</span><em>Sản phẩm</em>
        </span>
      </a>
      <a href="{{ route('checkout') }}" class="checkout-steps__item active">
        <span class="checkout-steps__item-number">02</span>
        <span class="checkout-steps__item-title">
          <span>Thanh toán</span><em>Thông tin</em>
        </span>
      </a>
      <span class="checkout-steps__item">
        <span class="checkout-steps__item-number">03</span>
        <span class="checkout-steps__item-title">
          <span>Xác nhận</span><em>Gửi đơn</em>
        </span>
      </span>
    </div>

    <div class="row">
      {{-- Form thông tin khách hàng --}}
      <div class="col-md-7">
        <form method="POST" action="{{ route('checkout.process') }}">
          @csrf

          <div class="form-floating mb-3">
            <input type="text" class="form-control" name="name" id="name" required>
            <label for="name">Họ tên *</label>
          </div>

          <div class="form-floating mb-3">
            <input type="text" class="form-control" name="phone" id="phone" required>
            <label for="phone">Số điện thoại *</label>
          </div>

          <div class="form-floating mb-4">
            <input type="text" class="form-control" name="address" id="address" required>
            <label for="address">Địa chỉ giao hàng *</label>
          </div>

          <div class="payment-methods mt-4">
            <h4 class="mb-3">Phương thức thanh toán</h4>
            @php
            $methods = [
            ['id' => 'cod', 'name' => 'Thanh toán khi nhận hàng', 'logo' => 'cash_logo.jpg'],
            ['id' => 'momo', 'name' => 'Ví MOMO', 'logo' => 'momo_logo.jpg'],
            ['id' => 'vnpay', 'name' => 'VNPAY', 'logo' => 'vnpay_logo.jpg'],
            ];
            @endphp

            @foreach($methods as $method)
            <div class="form-check mb-3 d-flex align-items-center">
              <input class="form-check-input me-3" type="radio" name="payment_method"
                id="{{ $method['id'] }}" value="{{ $method['id'] }}"
                {{ $loop->first ? 'checked' : '' }}>
              <label class="form-check-label d-flex align-items-center gap-3" for="{{ $method['id'] }}">
                <img src="{{ asset('images/payment_logo/' . $method['logo']) }}"
                  alt="{{ $method['name'] }}"
                  style="width: 48px; height: 32px; object-fit: contain; border-radius: 6px;">

                <span>{{ $method['name'] }}</span>
              </label>
            </div>
            @endforeach
          </div>
          <button type="submit" class="btn btn-primary">ĐẶT HÀNG</button>
        </form>
      </div>

      {{-- Bên phải: sơ lược giỏ hàng --}}
      <div class="col-md-5">
        <div class="card">
          <div class="card-header bg-light">
            <strong>🛒 Giỏ hàng của bạn</strong>
          </div>
          <ul class="list-group list-group-flush">
            @forelse ($cart ?? [] as $item)
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div>{{ $item['product_name'] }} ({{ $item['size'] ?? '-' }})</div>
                <small class="text-muted">{{ $item['quantity'] }} × ${{ number_format($item['price'], 2) }}</small>
              </div>
              <img src="{{ asset($item['image']) }}" alt="" width="50" class="rounded">
            </li>
            @empty
            <li class="list-group-item">Không có sản phẩm trong giỏ.</li>
            @endforelse
          </ul>
          <div class="card-footer text-end fw-bold">
            Tổng cộng: ${{ number_format(collect($cart ?? [])->sum(fn($i) => $i['price'] * $i['quantity']), 2) }}
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
  Swal.fire({
    icon: 'success',
    title: '🎉 Đặt hàng thành công!',
    text: "{{ session('success') }}",
    confirmButtonText: 'OK',
    customClass: {
      confirmButton: 'btn btn-primary px-4'
    },
    buttonsStyling: false
  });
</script>
@endif
@endpush

@push('styles')
<style>
  .form-check-label {
    cursor: pointer;
  }
</style>