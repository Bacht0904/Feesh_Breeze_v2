@extends('layouts.app')

@section('content')
<main class="pt-90 pb-5 bg-review">
  <div class="mb-4 pb-4"></div>
  <section class="container">
    <h2 class="review-title text-center mb-5">
      Đánh giá cho: <span class="text-primary">{{ $product->name }}</span>
    </h2>

    {{-- Tổng quan --}}
    <div class="review-summary text-center mb-5">
      @php $avg = round($product->reviews->avg('rating'), 1); @endphp
      <div class="d-inline-flex align-items-center fs-3 gap-1 mb-2">
        @for ($i = 1; $i <= 5; $i++)
          <i class="fa{{ $i <= $avg ? ' fa-star star-filled' : ' fa-star-o star-empty' }}"></i>
          @endfor
          <span class="ms-2 fw-bold">{{ $avg }}/5</span>
      </div>
      <div class="text-muted">{{ $product->reviews->count() }} đánh giá đã được ghi nhận</div>
    </div>

    {{-- Danh sách đánh giá --}}
    @if($reviews->count())
    @foreach($reviews as $review)
    <!-- <div class="card review-card border-0 shadow-sm mb-4">
      <div class="card-body">
        <div class="d-flex align-items-start gap-3 mb-3">
          {{-- Avatar + tên + thời gian --}}
          <img src="{{ asset($review->user->avatar ?? 'images/default-avatar.png') }}"
            class="avatar rounded-circle"
            alt="avatar">

          <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <strong class="text-dark">{{ $review->user->name ?? 'Khách hàng' }}</strong><br>
                <small class="text-muted fst-italic">{{ $review->created_at->format('d/m/Y H:i') }}</small>
              </div>
              {{-- Hiển thị sao --}}
              <div class="rating-stars d-inline-flex align-items-center gap-1">
                @for ($i = 1; $i <= 5; $i++)
                  <i class="fa fa-star{{ $i <= $review->rating ? ' text-shopee' : ' text-muted' }}"></i>
                  @endfor
              </div>

            </div>

            {{-- Nội dung bình luận --}}
            <p class="mb-1 mt-2 text-secondary" style="white-space: pre-line;">{{ $review->comment }}</p>
          </div>
        </div>
      </div>
    </div> -->
    <div class="card review-card border-0 shadow-sm mb-3">
      <div class="card-body">
        <div class="d-flex align-items-start gap-3 mb-3">
          {{-- Avatar --}}
          <img src="{{ asset($review->user->avatar ?? 'images/default-avatar.png') }}"
            class="avatar rounded-circle"
            alt="{{ $review->user->name }}">

          {{-- Nội dung đánh giá --}}
          <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2 mb-1">
              <strong class="text-dark">{{ $review->user->name ?? 'Khách hàng' }}</strong>
              <div class="rating-stars d-flex align-items-center">
                @for ($i = 1; $i <= 5; $i++)
                  <i class="fa fa-star {{ $i <= $review->rating ? 'text-shopee' : 'text-muted' }}"></i>
                  @endfor
              </div>
            </div>
            <small class="text-muted fst-italic">{{ $review->created_at->format('d/m/Y H:i') }}</small>

            {{-- Nội dung nhận xét --}}
            <p class="mt-2 mb-0 text-secondary" style="white-space: pre-line;">{{ $review->comment }}</p>
          </div>
        </div>
      </div>
    </div>


    @endforeach
    @else
    <div class="text-center text-muted mb-5">
      <i class="fa fa-comment-slash fa-2x mb-3"></i>
      <p>Chưa có đánh giá nào cho sản phẩm này.</p>
    </div>
    @endif

    {{-- Nút quay lại --}}
    <div class="text-center mt-4">
      <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-secondary">
        ← Quay về sản phẩm
      </a>
    </div>
  </section>
</main>
@endsection
@push('styles')
<style>
  .bg-review {
    background-color: #f8f9fa;
  }

  .review-title {
    font-size: 2rem;
    font-weight: 700;
  }

  .review-summary .fa-star,
  .rating .fa-star,
  .rating .fa-star-o {
    font-size: 1.3rem;
  }

  .star-filled {
    color: #FFA000;
  }

  .star-empty {
    color: #ccc;
  }

  .review-card {
    background-color: #fff;
    border-radius: 8px;
  }

  .review-card .card-body {
    padding: 1.25rem;
    line-height: 1.6;
  }

  @media (max-width: 576px) {
    .review-title {
      font-size: 1.5rem;
    }

    .review-summary .fa-star,
    .rating .fa-star,
    .rating .fa-star-o {
      font-size: 1.1rem;
    }
  }
</style>
@endpush
@push('styles')
<style>
  .text-shopee {
    color: #FFB600;
    /* Vàng Shopee */
  }

  .rating-stars i {
    font-size: 1.1rem;
    line-height: 1;
    margin-right: 2px;
  }

  /* Khi muốn sao gần sát nhau */
  .rating-stars {
    gap: 0.25rem;
  }
</style>
@endpush

@push('styles')
<style>
  .rounded-circle {
    object-fit: cover;
  }

  .star-filled {
    color: #FFA000;
  }

  .star-empty {
    color: #ccc;
  }
</style>
@endpush
@push('styles')
<style>
  .avatar {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50%;
    border: 1px solid #ddd;
  }

  @media (max-width: 576px) {
    .avatar {
      width: 32px;
      height: 32px;
    }
  }
</style>
@endpush