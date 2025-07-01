@extends('layouts.app')

@section('content')
<main class="pt-90">
  <section class="shop-main container d-flex pt-4 pt-xl-5">
    <div class="shop-sidebar side-sticky bg-body" id="shopFilter">
      <div class="aside-header d-flex d-lg-none align-items-center">
        <h3 class="text-uppercase fs-6 mb-0">Filter By</h3>
        <button class="btn-close-lg js-close-aside btn-close-aside ms-auto"></button>
      </div>

      <div class="pt-4 pt-lg-0"></div>

      <div class="accordion" id="categories-list">
        <div class="accordion-item mb-4 pb-3">
          <h5 class="accordion-header" id="accordion-heading-1">
            <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
              data-bs-target="#accordion-filter-1" aria-expanded="true" aria-controls="accordion-filter-1">
              Danh Mục
              <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                  <path
                    d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                </g>
              </svg>
            </button>
          </h5>
          <div id="accordion-filter-1" class="accordion-collapse collapse show border-0"
            aria-labelledby="accordion-heading-1" data-bs-parent="#categories-list">
            <div class="accordion-body px-0 pb-0 pt-3">
              <ul class="list list-inline mb-0">
                @foreach ($categories as $category)
                <li class="list-item">
                  <a href="{{ route('shop.category', $category->slug) }}" class="menu-link py-1">
                    {{ $category->name }}
                  </a>
                </li>
                @endforeach
              </ul>

            </div>
          </div>
        </div>
      </div>


      <div class="accordion" id="color-filters">
        <div class="accordion-item mb-4 pb-3">
          <h5 class="accordion-header" id="accordion-heading-1">
            <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
              data-bs-target="#accordion-filter-2" aria-expanded="true" aria-controls="accordion-filter-2">
              Màu
              <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                  <path
                    d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                </g>
              </svg>
            </button>
          </h5>

          <div id="accordion-filter-2" class="accordion-collapse collapse show border-0"
            aria-labelledby="accordion-heading-1" data-bs-parent="#color-filters">
            <div class="accordion-body px-0 pb-0">
              <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach ($colors as $color)
                @if (!empty($color['code']))
                <a href="#"
                  class="swatch-color js-filter position-relative rounded-circle border border-light shadow-sm"
                  style="background-color: {{ $color['code'] }}; width: 32px; height: 32px;"
                  title="{{ ucfirst($color['name']) }}">
                  <span class="visually-hidden">{{ ucfirst($color['name']) }}</span>
                </a>
                @endif
                @endforeach
              </div>


            </div>
          </div>
        </div>
      </div>


      <div class="accordion" id="size-filters">
        <div class="accordion-item mb-4 pb-3">
          <h5 class="accordion-header" id="accordion-heading-size">
            <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
              data-bs-target="#accordion-filter-size" aria-expanded="true" aria-controls="accordion-filter-size">
              Sizes
              <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                  <path
                    d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                </g>
              </svg>
            </button>
          </h5>
          <div id="accordion-filter-size" class="accordion-collapse collapse show border-0"
            aria-labelledby="accordion-heading-size" data-bs-parent="#size-filters">
            <div class="accordion-body px-0 pb-0">
              <div class="d-flex flex-wrap">
                @foreach ($sizes as $size)
                <a href="#"
                  class="swatch-size btn btn-sm btn-outline-light mb-3 me-3 js-filter">{{ strtoupper($size) }}</a>
                @endforeach
              </div>

            </div>
          </div>
        </div>
      </div>

      <div class="accordion" id="brand-filters">
        <div class="accordion-item mb-4 pb-3">
          <h5 class="accordion-header" id="heading-brand">
            <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse"
              data-bs-target="#filter-brand" aria-expanded="true" aria-controls="filter-brand">
              Thương hiệu
              <span class="badge bg-secondary ms-2">({{ count($brands) }})</span>
              <svg class="accordion-button__icon type2" viewBox="0 0 10 6">
                <use href="#icon_arrow" />
              </svg>
            </button>
          </h5>
          <div id="filter-brand" class="accordion-collapse collapse show border-0" aria-labelledby="heading-brand"
            data-bs-parent="#brand-filters">
            <div class="accordion-body px-0 pb-0">
              <div class="search-field__input-wrapper mb-3">
                <input type="text" class="form-control form-control-sm border-light border-2"
                  placeholder="Tìm thương hiệu...">
              </div>
              <ul class="multi-select__list list-unstyled">
                @foreach ($brands as $brand)
                <li class="multi-select__item js-search-select js-multi-select text-primary">
                  <span class="me-auto">{{ $brand->name }}</span>
                  <span class="text-secondary">{{ $brand->products_count ?? 0 }}</span>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>


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
            <input class="price-range-slider" type="text" name="price_range" value="" data-slider-min="10"
              data-slider-max="1000" data-slider-step="5" data-slider-value="[250,450]" data-currency="$" />
            <div class="price-range__info d-flex align-items-center mt-2">
              <div class="me-auto">
                <span class="text-secondary">Min Price: </span>
                <span class="price-range__min">$250</span>
              </div>
              <div>
                <span class="text-secondary">Max Price: </span>
                <span class="price-range__max">$450</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="shop-list flex-grow-1">
      <div class="swiper-container js-swiper-slider slideshow slideshow_small slideshow_split" data-settings='{
        "autoplay": {
          "delay": 5000
        },
        "slidesPerView": 1,
        "effect": "fade",
        "loop": true,
        "pagination": {
          "el": ".slideshow-pagination",
          "type": "bullets",
          "clickable": true
        }
        }'>
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <div class="slide-split h-100 d-block d-md-flex overflow-hidden">
              <div class="slide-split_text position-relative d-flex align-items-center"
                style="background-color: #f5e6e0;">
                <div class="slideshow-text container p-3 p-xl-5">
                  <h2
                    class="text-uppercase section-title fw-normal mb-3 animate animate_fade animate_btt animate_delay-2">
                    Women's <br /><strong>ACCESSORIES</strong></h2>
                  <p class="mb-0 animate animate_fade animate_btt animate_delay-5">Accessories are the best way to
                    update your look. Add a title edge with new styles and new colors, or go for timeless pieces.</h6>
                </div>
              </div>
              <div class="slide-split_media position-relative">
                <div class="slideshow-bg" style="background-color: #f5e6e0;">
                  <img loading="lazy" src="{{asset('assets/images/shop/shop_banner3.jpg')}}" width="630" height="450"
                    alt="Women's accessories" class="slideshow-bg__img object-fit-cover" />
                </div>
              </div>
            </div>
          </div>

          <div class="swiper-slide">
            <div class="slide-split h-100 d-block d-md-flex overflow-hidden">
              <div class="slide-split_text position-relative d-flex align-items-center"
                style="background-color: #f5e6e0;">
                <div class="slideshow-text container p-3 p-xl-5">
                  <h2
                    class="text-uppercase section-title fw-normal mb-3 animate animate_fade animate_btt animate_delay-2">
                    Women's <br /><strong>ACCESSORIES</strong></h2>
                  <p class="mb-0 animate animate_fade animate_btt animate_delay-5">Accessories are the best way to
                    update your look. Add a title edge with new styles and new colors, or go for timeless pieces.</h6>
                </div>
              </div>
              <div class="slide-split_media position-relative">
                <div class="slideshow-bg" style="background-color: #f5e6e0;">
                  <img loading="lazy" src="{{asset('assets/images/shop/shop_banner3.jpg')}}" width="630" height="450"
                    alt="Women's accessories" class="slideshow-bg__img object-fit-cover" />
                </div>
              </div>
            </div>
          </div>

          <div class="swiper-slide">
            <div class="slide-split h-100 d-block d-md-flex overflow-hidden">
              <div class="slide-split_text position-relative d-flex align-items-center"
                style="background-color: #f5e6e0;">
                <div class="slideshow-text container p-3 p-xl-5">
                  <h2
                    class="text-uppercase section-title fw-normal mb-3 animate animate_fade animate_btt animate_delay-2">
                    Women's <br /><strong>ACCESSORIES</strong></h2>
                  <p class="mb-0 animate animate_fade animate_btt animate_delay-5">Accessories are the best way to
                    update your look. Add a title edge with new styles and new colors, or go for timeless pieces.</h6>
                </div>
              </div>
              <div class="slide-split_media position-relative">
                <div class="slideshow-bg" style="background-color: #f5e6e0;">
                  <img loading="lazy" src="{{asset('assets/images/shop/shop_banner3.jpg')}}" width="630" height="450"
                    alt="Women's accessories" class="slideshow-bg__img object-fit-cover" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="container p-3 p-xl-5">
          <div class="slideshow-pagination d-flex align-items-center position-absolute bottom-0 mb-4 pb-xl-2"></div>

        </div>
      </div>

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

          <button class="btn btn-sm btn-outline-secondary js-toggle-view" data-view="grid"
            title="Xem dạng lưới">
            <svg width="16" height="16">
              <use href="#icon_grid" />
            </svg>
          </button>

          <button class="btn btn-sm btn-outline-secondary js-toggle-view ms-2" data-view="list"
            title="Xem dạng danh sách">
            <svg width="16" height="16">
              <use href="#icon_list" />
            </svg>
          </button>
        </div>
      </div>
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
              <form action="{{ route('cart.addDetail') }}" method="POST">
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
                ${{ number_format($firstDetail->price, 2) }}
              </span>

              @if($firstDetail->size)
              <div class="mt-1">
                <span class="badge bg-light text-dark border me-1">
                  {{ $firstDetail->size }}
                </span>
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
                    <svg class="review-star {{ $i <= $reviewAvg ? 'text-rating-custom' : 'text-muted' }}">
                    <use href="#icon_star" />
                    </svg>
                    @endfor

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
              <button type="button"
                class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist"
                data-id="{{ $firstDetail->id }}"
                title="Add To Wishlist">
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
<style>
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
@endpush