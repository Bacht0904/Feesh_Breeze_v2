@extends('layouts.app')

@section('content')
<main class="pt-90">
  <div class="mb-4 pb-4"></div>
  <section class="shop-main container d-flex pt-4 pt-xl-5">
    <div class="shop-sidebar side-sticky bg-body" id="shopFilter">
      <div class="aside-header d-flex d-lg-none align-items-center">
        <h3 class="text-uppercase fs-6 mb-0">Filter By</h3>
        <button class="btn-close-lg js-close-aside btn-close-aside ms-auto"></button>
      </div>

      <div class="pt-4 pt-lg-0"></div>
      <form method="GET" action="{{ route('shop') }}">
        {{-- DANH MỤC --}}
        <div class="accordion mb-3" id="categories-accordion">
          <div class="accordion-item">
            <h5 class="accordion-header">
              <button class="accordion-button fs-5 text-uppercase p-0 border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#filter-category">Danh Mục</button>
            </h5>
            <div id="filter-category" class="accordion-collapse collapse show">
              <div class="accordion-body px-0 pt-3">
                <select name="category" class="form-select">
                  <option value="">Tất cả</option>
                  @foreach ($categories as $cat)
                  <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                    {{ $cat->name }}
                  </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>

        {{-- MÀU --}}
        <div class="accordion mb-3" id="color-accordion">
          <div class="accordion-item">
            <h5 class="accordion-header">
              <button class="accordion-button fs-5 text-uppercase p-0 border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#filter-color">Màu</button>
            </h5>
            <div id="filter-color" class="accordion-collapse collapse show">
              <div class="accordion-body px-0 pt-3 d-flex flex-wrap gap-2">
                @foreach ($colors as $color)
                @if (!empty($color['code']))
                <label class="color-swatch" title="{{ ucfirst($color['name']) }}">
                  <input type="radio" name="color" value="{{ strtolower($color['name']) }}"
                    class="d-none"
                    {{ request('color') === strtolower($color['name']) ? 'checked' : '' }}>
                  <span class="swatch-circle"
                    style="background-color: {{ $color['code'] }}; width: 32px; height: 32px;"></span>
                </label>
                @endif
                @endforeach
              </div>
            </div>
          </div>
        </div>

        {{-- SIZE --}}
        <div class="accordion mb-3" id="size-accordion">
          <div class="accordion-item">
            <h5 class="accordion-header">
              <button class="accordion-button fs-5 text-uppercase p-0 border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#filter-size">Size</button>
            </h5>
            <div id="filter-size" class="accordion-collapse collapse show">
              <div class="accordion-body px-0 pt-3 d-flex flex-wrap gap-2">
                @foreach ($sizes as $size)
                <label>
                  <input type="radio" name="size" value="{{ strtoupper($size) }}"
                    class="btn-check" autocomplete="off"
                    {{ request('size') === strtoupper($size) ? 'checked' : '' }}>
                  <span class="btn btn-outline-secondary btn-sm">{{ strtoupper($size) }}</span>
                </label>
                @endforeach
              </div>
            </div>
          </div>
        </div>

        {{-- THƯƠNG HIỆU --}}
        <div class="accordion mb-3" id="brand-accordion">
          <div class="accordion-item">
            <h5 class="accordion-header">
              <button class="accordion-button fs-5 text-uppercase p-0 border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#filter-brand">Thương hiệu</button>
            </h5>
            <div id="filter-brand" class="accordion-collapse collapse show">
              <div class="accordion-body px-0 pt-3">
                <select name="brand" class="form-select">
                  <option value="">Tất cả thương hiệu</option>
                  @foreach ($brands as $brand)
                  <option value="{{ $brand->slug }}" {{ request('brand') == $brand->slug ? 'selected' : '' }}>
                    {{ $brand->name }}
                  </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>

        {{-- GIÁ --}}

        <div class="accordion" id="price-filters">
          <div class="accordion-item mb-4">
            <h5 class="accordion-header mb-2" id="accordion-heading-price">
              <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
                data-bs-target="#accordion-filter-price" aria-expanded="true" aria-controls="accordion-filter-price">
                Giá
                <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                  <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                    <path
                      d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                  </g>
                </svg>
              </button>
            </h5>
            <div id="accordion-filter-price" class="accordion-collapse collapse show border-0"
              aria-labelledby="accordion-heading-price" data-bs-parent="#price-filters">

              <!-- Thanh trượt giá (từ 10.000 đến 1.000.000 VNĐ) -->
              <input class="price-range-slider" type="text" name="price_range"
                data-slider-min="10000" data-slider-max="1000000"
                data-slider-step="10000" data-slider-value="[10000,500000]"
                data-currency="₫" />

              <div class="price-range__info d-flex align-items-center mt-2">
                <div class="me-auto">
                  <span class="text-secondary"></span>
                  <span class="price-range__min">10.000 ₫</span>
                </div>
                <div>
                  <span class="text-secondary"></span>
                  <span class="price-range__max">1.000.000 ₫</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- NÚT LỌC --}}
        <div class="d-flex gap-2 mt-3">
          <button type="submit" class="btn btn-dark w-100 d-flex align-items-center justify-content-center gap-1">
            <i class="bi bi-funnel-fill"></i>
            Áp dụng
          </button>
          <a href="{{ route('shop') }}" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-1">
            <i class="bi bi-x-lg"></i>
            Xóa
          </a>
        </div>


      </form>
    </div>

    <div class="shop-list flex-grow-1">
      {{-- resources/views/partials/slider-split.blade.php --}}
      <section class="swiper-container js-swiper-slider slideshow slideshow_small slideshow_split"
        data-settings='{
           "modules": ["Parallax","Pagination","EffectFade","Autoplay"],
           "autoplay": { "delay": 5000 },
           "slidesPerView": 1,
           "effect": "fade",
           "loop": true,
           "parallax": true,
           "pagination": {
             "el": ".slideshow-pagination",
             "type": "bullets",
             "clickable": true
           }
         }'>

        <div class="swiper-wrapper">
          @foreach($slides as $slide)
          <div class="swiper-slide">
            <div class="slide-split h-100 d-block d-md-flex overflow-hidden">

              {{-- Left panel: Text (layer 1) --}}
              <div class="slide-split_text d-flex align-items-center"
                data-swiper-parallax="-200"
                style="background-color: {{ $slide->bg_color ?? '#f5e6e0' }};">
                <div class="slideshow-text container p-3 p-xl-5 text-center text-md-start">
                  <h2 class="text-uppercase section-title fw-normal mb-3 animate animate_fade animate_btt animate_delay-2"
                    data-swiper-parallax="-400">
                    {!! nl2br(e($slide->title)) !!}
                  </h2>
                  <p class="mb-0 animate animate_fade animate_btt animate_delay-5"
                    data-swiper-parallax="-300">
                    {!! \Illuminate\Support\Str::markdown($slide->description) !!}

                  </p>
                </div>
              </div>

              {{-- Right panel: Image (layer 2) --}}
              <div class="slide-split_media position-relative"
                data-swiper-parallax="200">
                <div class="slideshow-bg"
                  style="background-color: {{ $slide->bg_color ?? '#f5e6e0' }};">
                  <img loading="lazy"
                    src="{{ asset($slide->image) }}"
                    alt="{{ $slide->title }}"
                    class="slideshow-bg__img object-fit-cover w-100 h-100" />
                </div>
              </div>

            </div>
          </div>
          @endforeach
        </div>

        {{-- Pagination bullets --}}
        <div class="container p-3 p-xl-5">
          <div class="slideshow-pagination d-flex justify-content-center
                position-absolute bottom-0 mb-4 pb-xl-2"></div>
        </div>
      </section>


      <div class="mb-3 pb-2 pb-xl-3"></div>

      <div class="d-flex justify-content-between mb-4 pb-md-2">
        <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
          <a href="{{ route('home') }} " class="menu-link menu-link_us-s text-uppercase fw-medium">Trang Chủ</a>

          <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
          <a href="{{ route('shop') }}" class="menu-link menu-link_us-s text-uppercase fw-medium">Sản Phẩm</a>
        </div>



      </div>
      <div class="shop-header d-flex justify-content-between align-items-center mb-3 mb-md-4">
        <h2 class="section-title text-uppercase mb-0">Tất cả sản phẩm</h2>
        <div class="shop-header__actions d-flex align-items-center">
          <div class="shop-header__sort me-3">
            <label for="sort-select" class="form-label visually-hidden">Sắp xếp theo</label>
            <select id="sort-select" class="form-select form-select-sm">
              <option value="default" {{ request('sort') === 'default' ? 'selected' : '' }}>Mặc định</option>
              <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
              <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
              <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
            </select>

          </div>
        </div>
      </div>
      @if ($products->count() === 0)
      <div class="empty-products text-center py-5">
        <p class="text-muted">Rất tiếc, không tìm thấy sản phẩm nào phù hợp với yêu cầu của bạn.</p>
        <a href="{{ route('shop') }}" class="btn btn-outline-secondary mt-3">Xem tất cả sản phẩm</a>
      </div>
      @else

      <div class="products-grid row row-cols-2 row-cols-md-3" id="products-grid">
        @foreach($products as $product)
        @php
        $firstDetail = $product->lowestPricedDetail;
        $productUrl = route('products.show', $product->slug);
        @endphp

        @if($firstDetail && $firstDetail->quantity > 0)
        <div class="col-6 col-md-4">
          <div class="product-card mb-3 mb-md-4 mb-xxl-5">

            {{-- Hình ảnh sản phẩm --}}
            <div class="pc__img-wrapper">
              <div class="swiper-container background-img js-swiper-slider" data-settings='{"resizeObserver": true}'>
                <div class="swiper-wrapper">
                  <div class="swiper-slide">
                    <a href="{{ $productUrl }}">
                      <img
                        src="{{ asset($firstDetail->image ?? 'images/default.jpg') }}"
                        alt="{{ $product->name }}"
                        class="pc__img"
                        width="330" height="400"
                        loading="lazy">
                    </a>
                  </div>
                </div>
              </div>

              {{-- Nút Thêm vào giỏ --}}
              <form class="js-add-to-cart" method="POST">
                @csrf
                <input type="hidden" name="product_detail_id" value="{{ $firstDetail->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="pc__atc btn anim_appear-bottom position-absolute border-0 text-uppercase fw-medium" data-aside="cartDrawer">
                  Thêm vào giỏ
                </button>
              </form>
            </div>

            {{-- Thông tin sản phẩm --}}
            <div class="pc__info position-relative">
              <p class="pc__category">{{ $product->category->name ?? 'N/A' }}</p>
              <h6 class="pc__title">
                <a href="{{ $productUrl }}">{{ Str::limit($product->name, 25) }}</a>
              </h6>

              <span class="money price">
                {{ number_format($firstDetail->price, 0, ',', '.') }} đ

              </span>

              @if($product->product_details->isNotEmpty())
              <div class="product-sizes text-muted small">
                Size:
                @foreach($product->product_details->unique('size') as $detail)
                <span class="badge bg-light border text-dark me-1">{{ $detail->size }}</span>
                @endforeach
              </div>
              @endif



              {{-- Đánh giá --}}
              <div class="product-card__review d-flex align-items-center mt-2">
                @php
                $reviewCount = $product->reviews->count();
                $reviewAvg = $reviewCount > 0 ? round($product->reviews->avg('rating')) : 0;
                @endphp

                <div class="reviews-group d-flex align-items-center">
                  @for ($i = 1; $i <= 5; $i++)
                    {{-- Hiển thị sao đánh giá --}}
                    <svg class="review-star {{ $color=$i <=$reviewAvg ? 'text-warning-custom' : 'text-muted'  }}" width="16" height="16">
                    <use href="#icon_star" />
                    </svg>
                    @endfor

                    {{-- Hiển thị điểm trung bình nếu có đánh giá --}}



                    @if($reviewCount > 0)
                    <span class="ms-2 small text-dark fw-semibold">
                      {{ number_format($product->reviews->avg('rating'), 1) }}/5
                    </span>
                    @endif

                </div>

                <span class="reviews-note text-secondary ms-2">
                  {{ $reviewCount > 0 ? $reviewCount . ' đánh giá' : '0 đánh giá' }}
                </span>
              </div>

              {{-- Wishlist --}}
              @php
              $isWished = in_array($firstDetail->id, $wishlistIds);
              @endphp
              <button type="button"
                class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist {{ $isWished ? 'active' : '' }}"
                data-id="{{ $firstDetail->id }}"
                title="{{ $isWished ? 'Đã yêu thích' : 'Thêm vào yêu thích' }}">
                <svg width="16" height="16">
                  <use href="#icon_heart" />
                </svg>
              </button>

            </div>

          </div>
        </div>
        @endif
        @endforeach
      </div>

      @endif
      {{-- Phân trang --}}
      @if ($products->hasPages())
      <nav class="shop-pages d-flex justify-content-center mt-4" aria-label="Phân trang sản phẩm">
        {{ $products->links('vendor.pagination.tailwind') }}
      </nav>
      @endif
    </div>
  </section>
</main>
@endsection
@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('added_to_cart'))
<script>
  Swal.fire({
    icon: 'success',
    title: '🎉 Đã thêm vào giỏ!',
    text: "{{ session('added_to_cart') }}",
    timer: 2000,
    showConfirmButton: false,
    toast: true,
    position: 'top-end',
    customClass: {
      popup: 'shadow rounded',
    }
  });
</script>

@endif
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // CSRF cho AJAX
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $(document).on('submit', 'form.js-add-to-cart', function(e) {
    e.preventDefault(); // Ngăn reload trang

    const form = $(this);
    const data = form.serialize(); // Lấy data POST

    $.ajax({
      url: "{{ route('cart.addDetail') }}",
      method: "POST",
      data: data,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }).done(res => {
      showToast('success', res.message || 'Đã thêm vào giỏ hàng!');

      // ✅ Gọi hàm cập nhật số lượng giỏ hàng + wishlist
      updateHeaderCounts();

    }).fail(err => {
      const msg = err.responseJSON?.message || 'Lỗi. Vui lòng thử lại.';
      showToast('danger', msg);
    });
  });

  // Xử lý click nút yêu thích
  $(document).on('click', '.js-add-wishlist', function() {
    const btn = $(this);
    const id = btn.data('id');

    $.post("{{ route('wishlist.add') }}", {
        product_detail_id: id
      })
      .done(res => {
        showToast('success', res.message || 'Đã thêm vào yêu thích!');
        btn.toggleClass('active');
        updateHeaderCounts();
      })
      .fail(err => {
        const msg = err.responseJSON?.message || 'Lỗi. Vui lòng thử lại.';
        showToast('danger', msg);
      });
  });

  // Hiển thị toast
  function showToast(type, message) {
    $('.toast').toast('hide'); // tránh toast trùng
    const toast = $(`
      <div class="toast align-items-center text-white bg-${type} border-0 position-fixed top-0 end-0 m-3 shadow"
           role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
        <div class="d-flex">
          <div class="toast-body">${message}</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Đóng"></button>
        </div>
      </div>`);
    $('body').append(toast);
    const bs = new bootstrap.Toast(toast[0]);
    bs.show();
    toast.on('hidden.bs.toast', () => toast.remove());
  }
</script>
<script>
  // Xử lý chuyển từ wishlist sang giỏ hàng
  $(document).on('submit', 'form.js-move-to-cart', function(e) {
    e.preventDefault();
    const form = $(this);
    const originalId = form.find('input[name="original_product_detail_id"]').val();

    $.post("{{ route('wishlist.moveToCart') }}", form.serialize())
      .done(res => {
        showToast('success', res.message || 'Đã chuyển sang giỏ hàng!');
        form.closest('.list-group-item').remove();
      })
      .fail(err => {
        const msg = err.responseJSON?.message || 'Lỗi. Vui lòng thử lại.';
        showToast('danger', msg);
      });
  });
</script>
<script>
  document.getElementById('sort-select').addEventListener('change', function() {
    const selectedSort = this.value;
    const url = new URL(window.location.href);
    url.searchParams.set('sort', selectedSort);
    url.searchParams.set('page', 1); // reset về trang đầu
    window.location.href = url.toString();
  });
</script>

<script>
  setTimeout(() => {
    const alert = document.getElementById('flash-alert');
    if (alert) {
      const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
      bsAlert.close();
    }
  }, 3000);
</script>
<script>
  // resources/js/slider-split.js
  import Swiper, {
    Parallax,
    Pagination,
    EffectFade,
    Autoplay
  } from 'swiper';

  new Swiper('.js-swiper-slider', {
    modules: [Parallax, Pagination, EffectFade, Autoplay],
    autoplay: {
      delay: 5000
    },
    effect: 'fade',
    loop: true,
    parallax: true,
    pagination: {
      el: '.slideshow-pagination',
      type: 'bullets',
      clickable: true
    }
  });
</script>
@endpush
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/shop.css') }}">
<style>
  .pc__btn-wl.active svg use {
    fill: #e74c3c;
    /* Màu đỏ trái tim */
  }

  .review-star {
    fill: currentColor;
    width: 1.2rem;
    height: 1.2rem;
  }

  .text-warning {
    color: rgb(236, 83, 22);
    /* màu cam bạn chọn */
  }

  .text-warning-custom {
    color: coral
  }

  .text-muted {
    color: #adb5bd;
    /* xám nhạt cho sao chưa đánh giá */
  }

  .color-swatch {
    display: inline-block;
    cursor: pointer;
  }

  .swatch-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 2px solid #ccc;
    display: inline-block;
    transition: all 0.2s;
  }

  .color-swatch input:checked+.swatch-circle {
    border: 2px solid #000;
    box-shadow: 0 0 0 2px #fff inset;
  }


  /* ✅ Wrapper giữ tỷ lệ khung hình (nếu cần) */
  .pc__img-wrapper {
    position: relative;
    width: 100%;
    padding-top: 121.2%;
    overflow: hidden;
  }

  .pc__img-wrapper img.pc__img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    z-index: 0;
  }

  .pc__img-wrapper .swiper-container {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
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

  .pc__btn-wl.active {
    color: #e74c3c;
    /* đỏ trái tim */
  }


  .form-check-label {
    cursor: pointer;
  }

  .btn {
    transition: all 0.2s ease;
  }

  .btn:hover {
    transform: scale(1.02);
  }

  /* Toàn slide full-height */
  .slide-split {
    height: 100vh;
  }

  /* Panel text 50% desktop, 100% mobile */
  .slide-split_text {
    width: 100%;
  }

  @media(min-width: 768px) {
    .slide-split_text {
      width: 50%;
    }

    .slide-split_media {
      width: 50%;
    }
  }

  /* Ảnh cover full-panel */
  .slideshow-bg__img {
    height: 100%;
    object-fit: cover;
    width: 100%;
  }

  /* Pagination bullets */
  .swiper-pagination-bullet {
    background: rgba(0, 0, 0, 0.3);
    width: 8px;
    height: 8px;
    margin: 0 4px;
  }

  .swiper-pagination-bullet-active {
    background: rgba(0, 0, 0, 0.8);
  }

  /* resources/css/slider-split.css */
  /* Slide full-height */
  .slide-split {
    height: 100vh;
    overflow: hidden;
  }

  /* Text panel: 100% mobile, 50% desktop */
  .slide-split_text {
    width: 100%;
  }

  .slide-split_media {
    width: 100%;
  }

  @media(min-width: 768px) {
    .slide-split_text {
      width: 50%;
    }

    .slide-split_media {
      width: 50%;
    }
  }

  /* Image cover panel */
  .slideshow-bg__img {
    height: 100%;
    object-fit: cover;
    width: 100%;
  }

  /* Pagination bullets */
  .swiper-pagination-bullet {
    background: rgba(0, 0, 0, 0.3);
    width: 8px;
    height: 8px;
    margin: 0 4px;
  }

  .swiper-pagination-bullet-active {
    background: rgba(0, 0, 0, 0.8);
  }
</style>
@endpush