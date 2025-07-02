@extends('layouts.app')

@section('content')
<main class="pt-90">
  <section class="container">
    <h2 class="page-title mb-4">Đánh giá cho: {{ $product->name }}</h2>

    {{-- Tổng quan --}}
    <div class="mb-4">
      <strong>Trung bình:</strong>
      @php $avg = round($product->reviews->avg('rating'), 1); @endphp
      @for ($i = 1; $i <= 5; $i++)
        <i class="fa{{ $i <= $avg ? ' text-rating-custom' : ' fa-star-o text-muted' }}"></i>
        @endfor
        <span class="ms-2 text-secondary">{{ $avg }}/5 từ {{ $product->reviews->count() }} đánh giá</span>
    </div>

    {{-- Danh sách đánh giá --}}
    @if($reviews->count())
    @foreach($reviews as $review)
    <div class="border rounded p-3 mb-3">
      <div class="d-flex justify-content-between">
        <strong>{{ $review->user->name ?? 'Khách hàng' }}</strong>
        <span class="text-warning">
          @for($i = 1; $i <= 5; $i++)
            <i class="fa{{ $i <= $review->rating ? ' fa-star' : ' fa-star-o' }}"></i>
            @endfor
        </span>
      </div>
      <p class="mt-2">{{ $review->comment }}</p>
      <small class="text-muted">Đánh giá vào {{ $review->created_at->format('d/m/Y H:i') }}</small>
    </div>
    @endforeach
    @else
    <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
    @endif

    {{-- Quay lại sản phẩm --}}
    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-secondary mt-3">← Quay về sản phẩm</a>
  </section>
</main>
@endsection
@push('styles')
<style>
  .text-rating-custom {
    color: #ff9900;
    /* Cam rực rỡ hoặc chọn tông màu bạn thích */
    font-weight: bold;
    font-size: 1.1rem;
  }



  .review-count {
    color: #6c757d;
    /* xám nhẹ */
    margin-left: 5px;
  }

  .product-title {
    font-size: 1.75rem;
    font-weight: bold;
    margin-bottom: 1rem;
  }

  .swiper-product-detail img {
    max-width: 100%;
    height: auto;
  }

  .swiper-button-next,
  .swiper-button-prev {
    color: #000;
  }

  .swiper-button-next:hover,
  .swiper-button-prev:hover {
    color: #007bff;
  }

  .form-select,
  .form-control {
    width: 100%;
  }

  .form-select {
    max-width: 300px;
  }



  .form-check-label {
    cursor: pointer;
  }
</style>
@endpush