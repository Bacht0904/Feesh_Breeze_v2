@extends('layouts.app')

@section('content')
<main class="pt-90">
  <section class="shop-checkout container">
    <h2 class="page-title mb-4">Giỏ hàng của bạn</h2>
    @if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert" id="alert-warning">
      {{ session('warning') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif


    @php
    $total = 0;
    $hasOutOfStock = false;
    @endphp

    @if ((Auth::check() && isset($items) && count($items) > 0) || (session('cart') && count(session('cart')) > 0))

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

              @if(Auth::check())
              @foreach ($items as $item)
              @php
              $subtotal = $item->price * $item->quantity;
              $total += $subtotal;
              $detail = \App\Models\Product_details::find($item->product_detail_id);
              $outOfStock = !$detail || $detail->quantity < $item->quantity;
                $hasOutOfStock = $outOfStock ? true : ($hasOutOfStock ?? false);
                $product = $detail?->product;
                @endphp

                <tr>
                  <td><img src="{{ asset($detail->image) }}" width="100" alt="{{ $product->name }}"></td>
                  <td>
                    <h5>{{ $product->name }}</h5>
                    @if($outOfStock)
                    <p class="text-danger">Hết hàng hoặc không đủ số lượng (Tồn: {{ $detail?->quantity ?? 0 }})</p>
                    @endif
                    <select name="product_detail_ids[{{ $item->id }}]" class="form-select form-select-sm">
                      @foreach($product->product_details as $variant)
                      <option value="{{ $variant->id }}" @selected($variant->id == $item->product_detail_id)>
                        {{ "Size {$variant->size} – Màu: {$variant->color}" }}
                      </option>
                      @endforeach
                    </select>
                  </td>
                  <td>
                    @if($outOfStock)
                    <span class="text-danger fw-bold">Hết hàng</span>
                    @else
                    {{ number_format($item['price'], 0) }} đ
                    @endif
                  </td>

                  <td>
                    <input type="number" name="quantities[{{ $item->id }}]" value="{{ $item->quantity }}" min="1"
                      class="form-control text-center w-75">
                  </td>
                  <td>
                    @if($outOfStock)
                    <span class="text-danger">–</span>
                    @else
                    {{ number_format($subtotal, 0) }} đ
                    @endif
                  </td>

                  <td>

                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" id="remove-form-{{ $item->id }}">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="text-danger">Xoá</button>
                    </form>

                  </td>

                </tr>
                @endforeach
                @else
                @foreach (session('cart', []) as $id => $item)
                @php
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
                $detail = \App\Models\Product_details::find($item['product_detail_id']);
                $outOfStock = !$detail || $detail->quantity < $item['quantity'];
                  $hasOutOfStock=$outOfStock ? true : ($hasOutOfStock ?? false);
                  $product=$detail?->product;
                  @endphp

                  <tr>
                    <td><img src="{{ asset($item['image']) }}" width="100" alt="{{ $item['product_name'] }}"></td>
                    <td>
                      <h5>{{ $item['product_name'] }}</h5>
                      @if($outOfStock)
                      <p class="text-danger">Hết hàng hoặc không đủ số lượng (Tồn: {{ $detail?->quantity ?? 0 }})</p>
                      @endif
                      <select name="product_detail_ids[{{ $id }}]" class="form-select form-select-sm">
                        @foreach($product?->product_details ?? [] as $variant)
                        <option value="{{ $variant->id }}" @selected($variant->id == $item['product_detail_id'])>
                          {{ "Size {$variant->size} – Màu: {$variant->color}" }}
                        </option>
                        @endforeach
                      </select>
                    </td>
                    <td>
                      @if($outOfStock)
                      <span class="text-danger fw-bold">Hết hàng</span>
                      @else
                      {{ number_format($item['price'], 0) }} đ
                      @endif
                    </td>

                    <td>
                      <input type="number" name="quantities[{{ $id }}]" value="{{ $item['quantity'] }}" min="1"
                        class="form-control text-center w-75">
                    </td>
                    <td>
                      @if($outOfStock)
                      <span class="text-danger">–</span>
                      @else
                      {{ number_format($subtotal, 0) }} đ
                      @endif
                    </td>

                    <td>

                      <form action="{{ route('cart.remove', $id) }}" method="POST" id="remove-form-{{ $id }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-danger">Xoá</button>
                      </form>

                    </td>

                  </tr>
                  @endforeach
                  @endif
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
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const alertWarning = document.getElementById('alert-warning');
    const alertSuccess = document.getElementById('alert-success');

    [alertWarning, alertSuccess].forEach(alert => {
      if (alert) {
        setTimeout(() => {
          alert.classList.remove('show');
          alert.classList.add('fade');
        }, 4000); // 4s tự động đóng
      }
    });
  });
</script>
@endpush