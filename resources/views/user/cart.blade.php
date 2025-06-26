@extends('layouts.app')

@section('content')
<main class="pt-90">
  <section class="shop-checkout container">
    <h2 class="page-title mb-4">Giỏ hàng của bạn</h2>

    @if (session('cart') && count(session('cart')) > 0)
    <form method="POST" action="{{ route('cart.update') }}">
      @csrf
      <div class="shopping-cart">
        <div class="cart-table__wrapper">
          <table class="cart-table">
            <thead>
              <tr>
                <th>Sản phẩm</th>
                <th>Chi tiết</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tạm tính</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @php $total = 0; @endphp
              @foreach (session('cart') as $id => $item)
              @php
              $subtotal = $item['price'] * $item['quantity'];
              $total += $subtotal;
              @endphp
              <tr>
                <td>
                  <div class="shopping-cart__product-item">
                    <img loading="lazy" src="{{ asset($item['image']) }}" width="100" alt="{{ $item['product_name'] }}">
                  </div>
                </td>
                <td>
                  <div class="shopping-cart__product-item__detail">
                    <h5>{{ $item['product_name'] }}</h5>
                    <ul class="shopping-cart__product-item__options">
                      <li>Màu: {{ $item['color'] }}</li>
                      <li>Size: {{ $item['size'] }}</li>
                    </ul>
                  </div>
                </td>
                <td>{{ number_format($item['price'], 0) }} đ</td>
                <td>
                  <input type="number" name="quantities[{{ $id }}]" value="{{ $item['quantity'] }}" min="1"
                    class="form-control text-center w-75">
                </td>
                <td>{{ number_format($subtotal, 0) }} đ</td>
                <td>
                  <a href="{{ route('cart.remove', $id) }}" class="remove-cart text-danger">Xoá</a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <div class="cart-table-footer text-end mt-3">
            <strong class="fs-5 text-dark">Tổng cộng: {{ number_format($total, 0) }} đ</strong>
          </div>
        </div>
      </div>
      <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-stretch gap-3 text-end">
        <button type="submit" class="btn btn-outline-secondary w-100 w-md-auto">
          Cập nhật giỏ hàng
        </button>
        <a href="{{ route('checkout') }}" class="btn btn-primary w-100 w-md-auto">
          Tiến hành thanh toán
        </a>
      </div>
    </form>
    @else
    <div class="alert alert-info">Giỏ hàng của bạn đang trống.</div>
    @endif
  </section>
</main>
@endsection