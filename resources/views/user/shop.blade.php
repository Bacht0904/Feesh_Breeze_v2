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
                <a href="#" class="swatch-size btn btn-sm btn-outline-light mb-3 me-3 js-filter">{{ strtoupper($size) }}</a>
                @endforeach
              </div>

            </div>
          </div>
        </div>
      </div>

      <div class="accordion" id="brand-filters">
        <div class="accordion-item mb-4 pb-3">
          <h5 class="accordion-header" id="heading-brand">
            <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button"
              data-bs-toggle="collapse" data-bs-target="#filter-brand"
              aria-expanded="true" aria-controls="filter-brand">
              Thương hiệu
              <span class="badge bg-secondary ms-2">({{ count($brands) }})</span>
              <svg class="accordion-button__icon type2" viewBox="0 0 10 6">
                <use href="#icon_arrow" />
              </svg>
            </button>
          </h5>
          <div id="filter-brand" class="accordion-collapse collapse show border-0" aria-labelledby="heading-brand" data-bs-parent="#brand-filters">
            <div class="accordion-body px-0 pb-0">
              <div class="search-field__input-wrapper mb-3">
                <input type="text" class="form-control form-control-sm border-light border-2" placeholder="Tìm thương hiệu...">
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

      <!-- <div class="products-grid row row-cols-2 row-cols-md-3" id="products-grid">
        @foreach($products as $product)
        <div class="col-6 col-md-4">
          <div class="product-card mb-3 mb-md-4 mb-xxl-5">
            <div class="pc__img-wrapper">
              <div class="swiper-container background-img js-swiper-slider" data-settings='{"resizeObserver": true}'>
                <div class="swiper-wrapper">
                  <div class="swiper-slide">
                    <a href="{{ route('products.show', $product->id) }}">
                      <img loading="lazy" src="{{ asset('upload/product/' . $product->img) }}" width="330" height="400" alt="{{ $product->name }}" class="pc__img">
                    </a>
                  </div>
                  <div class="swiper-slide">
                    <a href="{{ route('products.show', $product->id) }}">
                      <img loading="lazy" src="{{ asset('assets/images/products/product_1-1.jpg') }}" width="330" height="400" alt="{{ $product->name }}" class="pc__img">
                    </a>
                  </div>
                </div>
                <span class="pc__img-prev"><svg width="7" height="11">
                    <use href="#icon_prev_sm" />
                  </svg></span>
                <span class="pc__img-next"><svg width="7" height="11">
                    <use href="#icon_next_sm" />
                  </svg></span>
              </div>
              <button class="pc__atc btn anim_appear-bottom position-absolute border-0 text-uppercase fw-medium js-add-cart js-open-aside" data-aside="cartDrawer">
                Thêm vào giỏ
              </button>
            </div>

            <div class="pc__info position-relative">
              <p class="pc__category">{{ $product->category->name ?? 'N/A' }}</p>
              <h6 class="pc__title"><a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a></h6>
              @if ($product->product_details->first())
              <span class="money price">
                ${{ number_format($product->product_details->first()->price, 2) }}
              </span>
              @else
              <span class="text-muted">Chưa có giá</span>
              @endif
              @foreach($product->product_details as $detail)
              <p>Size: {{ $detail->size }}</p>
              @endforeach

              <div class="product-card__review d-flex align-items-center">
                <div class="reviews-group d-flex">
                  @for($i = 0; $i < 5; $i++)
                    <svg class="review-star" viewBox="0 0 9 9">
                    <use href="#icon_star" /></svg>
                    @endfor
                </div>
                <span class="reviews-note text-lowercase text-secondary ms-1">8k+ reviews</span>
              </div>
              <button class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist" title="Add To Wishlist">
                <svg width="16" height="16">
                  <use href="#icon_heart" />
                </svg>
              </button>
            </div>
          </div>
        </div>
        @endforeach
      </div> -->
      <div class="products-grid row row-cols-2 row-cols-md-3" id="products-grid">
        @foreach($products as $product)
        @php
        $firstDetail = $product->product_details->first();
        $productUrl = route('products.show', $product->id);
        @endphp

        <div class="col-6 col-md-4">
          <div class="product-card mb-3 mb-md-4 mb-xxl-5">
            <div class="pc__img-wrapper">
              <div class="swiper-container background-img js-swiper-slider" data-settings='{"resizeObserver": true}'>
                <div class="swiper-wrapper">
                  <div class="swiper-slide">
                    @php
                    $productUrl = route('products.show', $product->slug); // nếu show theo slug
                    @endphp

                    <a href="{{ $productUrl }}">
                      <img loading="lazy" src="{{ asset('/' . $firstDetail->image) }}" width="330" height="400" alt="{{ $product->name }}" class="pc__img">
                    </a>

                  </div>
                  <!-- <div class="swiper-slide">
                    <a href="{{ $productUrl }}">
                      <img loading="lazy" src="{{ asset('assets/images/products/product_1-1.jpg') }}" width="330" height="400" alt="{{ $product->name }}" class="pc__img">
                    </a>
                  </div> -->
                </div>

              </div>

              @if ($firstDetail)
              <form action="{{ route('cart.addDetail') }}" method="POST">
                @csrf
                <input type="hidden" name="product_detail_id" value="{{ $firstDetail->id }}">
                <input type="hidden" name="quantity" value="1">

                <button type="submit" class="pc__atc btn anim_appear-bottom position-absolute border-0 text-uppercase fw-medium " data-aside="cartDrawer">
                  Thêm vào giỏ
                </button>
              </form>

              @endif

              <!-- <button class="pc__atc btn anim_appear-bottom position-absolute border-0 text-uppercase fw-medium js-add-cart js-open-aside" data-aside="cartDrawer">
                Thêm vào giỏ
              </button> -->


            </div>

            <div class="pc__info position-relative">
              <p class="pc__category">{{ $product->category->name ?? 'N/A' }}</p>
              <h6 class="pc__title"><a href="{{ $productUrl }}">{{ $product->name }}</a></h6>

              @if($firstDetail)
              <span class="money price">${{ number_format($firstDetail->price, 2) }}</span>
              @else
              <span class="text-muted">Chưa có giá</span>
              @endif
              @php
              $sizes = $product->product_details->pluck('size')->unique();
              @endphp

              <p class="mb-1">
                Size:
                {{ $sizes->implode(', ') }}
              </p>


              <div class="product-card__review d-flex align-items-center">
                <div class="reviews-group d-flex">
                  @for($i = 0; $i < 5; $i++)
                    <svg class="review-star" viewBox="0 0 9 9">
                    <use href="#icon_star" /></svg>
                    @endfor
                </div>
                <span class="reviews-note text-lowercase text-secondary ms-1">8k+ reviews</span>
              </div>

              <button class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist" title="Add To Wishlist">
                <svg width="16" height="16">
                  <use href="#icon_heart" />
                </svg>
              </button>
            </div>
          </div>
        </div>
        @endforeach
      </div>


      <nav class="shop-pages d-flex justify-content-between mt-3" aria-label="Page navigation">
        <a href="#" class="btn-link d-inline-flex align-items-center">
          <svg class="me-1" width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
            <use href="#icon_prev_sm" />
          </svg>
          <span class="fw-medium">PREV</span>
        </a>
        <ul class="pagination mb-0">
          <li class="page-item"><a class="btn-link px-1 mx-2 btn-link_active" href="#">1</a></li>
        </ul>
        <a href="#" class="btn-link d-inline-flex align-items-center">
          <span class="fw-medium me-1">NEXT</span>
          <svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
            <use href="#icon_next_sm" />
          </svg>
        </a>
      </nav>
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
@endpush
@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/shop.css') }}"> 