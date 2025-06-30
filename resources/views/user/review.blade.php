@extends('layouts.app')

@section('content')
<main class="pt-90">
  <div class="mb-4 pb-4"></div>
  <section class="my-account container">
    <h2 class="page-title">Đánh giá sản phẩm</h2>
    <div class="row">
      <div class="col-lg-3">
        <ul class="account-nav">
          <!-- <li><a href="my-account.html" class="menu-link menu-link_us-s">Dashboard</a></li> -->

          <li><a href="{{ route('wishlist') }}" class="menu-link menu-link_us-s">Yêu Thích</a></li>
          <li><a href="{{ route('cart') }}" class="menu-link menu-link_us-s">Giỏ Hàng</a></li>
          <li><a href="{{ route('orders.index') }}" class="menu-link menu-link_us-s">Đơn Hàng</a></li>
          <li><a href="{{ route('user.address') }}" class="menu-link menu-link_us-s">Địa Chỉ</a></li>
          <li><a href="{{ route('user.review') }}" class="menu-link menu-link_us-s">Đánh Giá</a></li>


          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>

          <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Đăng xuất
          </a>

        </ul>
      </div>

      <div class="col-lg-9">
        <div class="page-content">
          {{-- Thông tin sản phẩm --}}
          <div class="d-flex align-items-center gap-3 mb-4">
            <img src="{{ asset($productDetail->image ?? 'images/placeholder.png') }}" width="80" alt="{{ $productDetail->product->name }}">
            <h5 class="mb-0">{{ $productDetail->product->name }}</h5>
          </div>

          {{-- Thêm đánh giá --}}
          <div class="card mb-5">
            <div class="card-header">
              <h5>Thêm đánh giá</h5>
            </div>
            <div class="card-body">
              <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $productDetail->product->id }}">
                <input type="hidden" name="product_detail_id" value="{{ $productDetail->id }}">

                <div class="row mb-4">
                  <label class="col-md-2 col-form-label text-md-end">Số sao:</label>
                  <div class="col-md-10 star-rating">
                    @for ($i = 1; $i <= 5; $i++)
                      <input type="radio" id="star-{{ $i }}" name="rating" value="{{ $i }}">
                      <label for="star-{{ $i }}"><i class="fa fa-star"></i></label>
                      @endfor
                  </div>
                </div>

                <div class="mb-4">
                  <label for="comment" class="form-label">Nhận xét của bạn:</label>
                  <textarea name="comment" class="form-control" rows="5" required>{{ old('comment') }}</textarea>
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-success">Gửi đánh giá</button>
                </div>
              </form>
            </div>
          </div>

          {{-- Quay lại --}}
          <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">← Quay về đơn hàng</a>
        </div>
      </div>
    </div>
  </section>
</main>
@endsection