@extends('layouts.app')

@section('content')
<main class="pt-90">
  <section class="product-single container">
    <div class="row">
      {{-- Phần ảnh slide --}}
      <div class="col-lg-7">
        <div class="swiper swiper-product-detail">
          <div class="swiper-wrapper">
            @foreach ($product->product_details as $detail)
            <div class="swiper-slide text-center">
              <img src="{{ asset($detail->image) }}" class="img-fluid rounded mb-3" alt="{{ $product->name }} - {{ $detail->size }}" style="max-width: 500px; height: auto;">

              <div>
                <p><strong>Size:</strong> {{ $detail->size }}</p>
                <p><strong>Giá:</strong> {{ number_format($detail->price, 0) }} VNĐ</p>
                <p><strong>Màu:</strong> {{ $detail->color }}</p>
                <p><strong>Số lượng:</strong> {{ $detail->quantity ?? 'N/A' }}</p>
              </div>
            </div>
            @endforeach
          </div>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
        </div>
      </div>

      {{-- Thông tin tổng quát sản phẩm --}}
      <div class="col-lg-5">
        <h1 class="product-title" style="max-width: 100%; white-space: nowrap; text-overflow: ellipsis;">
          {{-- Hiển thị tên sản phẩm --}}
          {{ $product->name }}
        </h1>
        <p><strong>Danh mục:</strong> {{ $product->category->name ?? 'Chưa phân loại' }}</p>
        <p><strong>Trạng thái:</strong> {{ $product->status ? 'Còn hàng' : 'Hết hàng' }}</p>
        <p><strong>Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Mô tả:</strong> {{ $product->description }}</p>

        {{-- Thêm vào giỏ --}}
        <form method="POST" action="{{ route('cart.addDetail') }}">
          @csrf

          {{-- Lựa chọn biến thể sản phẩm --}}
          <div class="mb-3">
            <label for="product_detail_id" class="form-label">Chọn size</label>
            <select name="product_detail_id" id="product_detail_id" class="form-select" required>
              @foreach ($product->product_details as $detail)
              <option value="{{ $detail->id }}">
                Size {{ $detail->size }} — {{ number_format($detail->price, 0) }} VNĐ — Màu: {{ $detail->color }}
              </option>
              @endforeach
            </select>
          </div>

          {{-- Số lượng --}}
          <div class="mb-3">
            <label for="quantity" class="form-label">Số lượng</label>
            <input type="number" name="quantity" value="1" min="1" class="form-control w-50" required>
          </div>

          <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
        </form>
        
      </div>
    </div>


  </section>
</main>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script>
  new Swiper('.swiper-product-detail', {
    loop: true,
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    }
  });
</script>
@endpush