@extends('layouts.app')

@section('content')
<main class="pt-90">
  <section class="shop-checkout container">
    <h2 class="page-title mb-4">Giỏ hàng của bạn</h2>

    @php
      $total = 0;
      $hasOutOfStock = false;
    @endphp

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
              @foreach (session('cart') as $id => $item)
              @php
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;

                $detail = \App\Models\Product_details::find($item['product_detail_id'] ?? null);
                $outOfStock = !$detail || $detail->quantity < $item['quantity'];
                if ($outOfStock) {
                    $hasOutOfStock = true;
                }

                $product = \App\Models\Product::with('product_details')->find($item['product_id'] ?? null);
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
                    @if ($outOfStock)
                      <p class="text-danger mb-1">Hết hàng hoặc không đủ số lượng (Tồn: {{ $detail?->quantity ?? 0 }})</p>
                    @endif
                    <select name="product_detail_ids[{{ $id }}]" class="form-select form-select-sm" required>
                      @foreach ($product?->product_details ?? [] as $variant)
                      <option value="{{ $variant->id }}"
                        @selected($variant->id == $item['product_detail_id'])>
                        {{ Str::limit("Size {$variant->size} – Màu: {$variant->color}", 50) }}
                      </option>
                      @endforeach
                    </select>
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

        <a href="{{ $hasOutOfStock ? '#' : route('checkout') }}"
          class="btn btn-primary w-100 w-md-auto {{ $hasOutOfStock ? 'disabled' : '' }}"
          onclick="{{ $hasOutOfStock ? 'return false;' : '' }}">
          Đặt hàng
        </a>
      </div>

      @if ($hasOutOfStock)
        <p class="text-danger text-end mt-2">
          Một số sản phẩm không còn đủ hàng. Vui lòng cập nhật lại để tiếp tục đặt hàng.
        </p>
      @endif

    </form>
    @else
    <div class="alert alert-info">Giỏ hàng của bạn đang trống.</div>
    @endif
  </section>
</main>
@endsection
