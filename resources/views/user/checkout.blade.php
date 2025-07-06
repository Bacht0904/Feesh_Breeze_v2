@extends('layouts.app')

@section('content')
<main class="pt-90">
  <section class="shop-checkout container">
    <h2 class="page-title mb-4">Vận chuyển và Thanh toán</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tiến trình thanh toán --}}
    <!-- <div class="checkout-steps d-flex mb-5">
      @foreach ([
      ['text' => 'Giỏ Hàng', 'sub' => 'Sản phẩm'],
      ['text' => 'Thanh toán', 'sub' => 'Thông tin'],
      ['text' => 'Xác nhận', 'sub' => 'Gửi đơn']
      ] as $index => $step)
      <div class="checkout-steps__item {{ $index <= 1 ? 'active' : '' }}">
        <span class="checkout-steps__item-number">0{{ $index + 1 }}</span>
        <span class="checkout-steps__item-title">
          <span>{{ $step['text'] }}</span><em>{{ $step['sub'] }}</em>
        </span>
      </div>
      @endforeach
    </div> -->

    <div class="row">
      {{-- Form khách hàng --}}
      <div class="col-md-7">
        <form action="{{ route('checkout.process') }}" method="POST">
          @csrf

          @foreach ([
          ['name', 'Họ tên *', 'text', Auth::user()->name ?? ''],
          ['phone', 'Số điện thoại *', 'text', Auth::user()->phone ?? ''],
          ['address', 'Địa chỉ giao hàng *', 'text', Auth::user()->address ?? ''],

          ] as [$name, $label, $type, $value])
          <div class="form-floating mb-3">
            <input
              class="form-control"
              type="{{ $type }}"
              name="{{ $name }}"
              id="{{ $name }}"
              value="{{ old($name, $value) }}"
              required>


            <label for="{{ $name }}">{{ $label }}</label>
          </div>
          @endforeach
          <div class="voucher-section mt-4">
            <label for="coupon_code">Mã giảm giá</label>
            <div class="d-flex flex-column flex-md-row gap-2">


              <select id="coupon_select" class="form-select" onchange="onSelectCoupon(this.value)">
                <option value="">-- Chọn mã giảm giá --</option>
                @foreach ($availableCoupons as $coupon)
                <option value="{{ $coupon->code }}"
                  {{ session('coupon.code') === $coupon->code ? 'selected' : '' }}>
                  {{ $coupon->code }} ({{ $coupon->type === 'percent' ? $coupon->value.'%' : number_format($coupon->value).'đ' }})
                </option>
                @endforeach
              </select>


              {{-- Input nhập mã thủ công --}}
              <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="Hoặc nhập mã tại đây">

              {{-- Nút áp dụng --}}
              <button type="submit" formaction="{{ route('cart.applyCoupon') }}" class="btn btn-success">Áp dụng</button>
            </div>

            @if (session('voucher_message'))
            <p class="text-info mt-2">{{ session('voucher_message') }}</p>
            @endif
          </div>

          {{-- Script đồng bộ chọn và nhập --}}
          <script>
            function onSelectCoupon(code) {
              document.getElementById('coupon_code').value = code;
            }
          </script>

          <div class="form-floating mb-4">
            <textarea class="form-control" name="note" id="note" style="height: 100px" placeholder="Ghi chú đơn hàng (tuỳ chọn)"></textarea>
            <label for="note">Ghi chú đơn hàng</label>
          </div>

          {{-- Phương thức thanh toán --}}
          <h4 class="mb-3">Phương thức thanh toán</h4>
          @foreach([
          ['cod', 'Thanh toán khi nhận hàng', 'cash_logo.jpg'],
          ['momo', 'Ví MOMO', 'momo_logo.jpg'],

          ] as [$id, $name, $logo])
          <div class="form-check mb-2 d-flex align-items-center">
            <input class="form-check-input me-2" type="radio" name="payment_method" id="{{ $id }}" value="{{ $id }}" {{ $loop->first ? 'checked' : '' }}>
            <label class="form-check-label d-flex align-items-center gap-3" for="{{ $id }}">
              <img src="{{ asset('images/payment_logo/' . $logo) }}" alt="{{ $name }}" width="48" height="32" style="object-fit: contain; border-radius: 6px;">
              <span>{{ $name }}</span>
            </label>
          </div>
          @endforeach

          <button type="submit" class="btn btn-primary mt-4">ĐẶT HÀNG</button>
        </form>
      </div>

      {{-- Giỏ hàng --}}
      <div class="col-md-5">
        <div class="card">
          <div class="card-header bg-light"><strong>🛒 Giỏ hàng của bạn</strong></div>
          <ul class="list-group list-group-flush">
            @forelse ($cart ?? [] as $item)
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div>{{ $item['product_name'] }} ({{ $item['size'] ?? '-' }})</div>
                <small class="text-muted">{{ $item['quantity'] }} × ₫{{ number_format($item['price']) }}</small>
              </div>
              <img src="{{ asset($item['image']) }}" width="50" class="rounded" alt="">
            </li>
            @empty
            <li class="list-group-item">Không có sản phẩm trong giỏ.</li>
            @endforelse
          </ul>

          {{-- Tổng tiền --}}
          <div class="card-footer">
            <div class="d-flex justify-content-between">
              <span>Tạm tính:</span>
              <strong>₫{{ number_format($subtotal ?? 0) }}</strong>
            </div>



            @if (!empty($discount) && $discount > 0)
            <div class="d-flex justify-content-between">
              <span>Giảm giá:</span>
              <strong>-₫{{ number_format($discount) }}</strong>
            </div>
            @endif
            <div class="d-flex justify-content-between">
              <span>Phí vận chuyển:</span>
              <strong>{{ ($shipping ?? 0) === 0 ? 'Miễn phí' : '₫' . number_format($shipping ?? 0) }}</strong>
            </div>

            <hr>
            <div class="d-flex justify-content-between fw-bold fs-5">
              <span>Tổng cộng:</span>
              <span class="text-primary">₫{{ number_format($total ?? ($subtotal + $shipping - $discount)) }}</span>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
</main>
@endsection
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const selectedCoupon = "{{ session('coupon.code') }}";
    if (selectedCoupon) {
      document.getElementById('coupon_code').value = selectedCoupon;
    }
  });
</script>
@endpush

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

  .checkout-steps__item.active {
    font-weight: bold;
    color: var(--bs-primary);
  }

  .checkout-steps__item-number {
    background: #eee;
    padding: 4px 10px;
    border-radius: 20px;
    display: inline-block;
  }

  .checkout-steps__item-title em {
    display: block;
    font-size: 0.8rem;
    color: #999;
  }
</style>
@endpush