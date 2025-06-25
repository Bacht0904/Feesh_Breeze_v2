@extends('layouts.app')

@section('content')
<main class="pt-90">
  <div class="mb-md-1 pb-md-3"></div>
  <section class="product-single container">
    <div class="row">
      <div class="col-lg-7">
        <div class="product-single__media" data-media-type="vertical-thumbnail">
          <div class="product-single__image">
            <div class="swiper-container">
              <div class="swiper-wrapper">
                <div class="swiper-slide product-single__image-item">
                  <img loading="lazy" class="h-auto" src="{{ asset('assets/images/products/product_0.jpg')}}" width="674"
                    height="674" alt="" />
                  <a data-fancybox="gallery" href="../images/products/product_0.html" data-bs-toggle="tooltip"
                    data-bs-placement="left" title="Zoom">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_zoom" />
                    </svg>
                  </a>
                </div>
                <div class="swiper-slide product-single__image-item">
                  <img loading="lazy" class="h-auto" src="{{ asset('assets/images/products/product_0-1.jpg')}}" width="674"
                    height="674" alt="" />
                  <a data-fancybox="gallery" href="../images/products/product_0-1.html" data-bs-toggle="tooltip"
                    data-bs-placement="left" title="Zoom">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_zoom" />
                    </svg>
                  </a>
                </div>
                <div class="swiper-slide product-single__image-item">
                  <img loading="lazy" class="h-auto" src="{{ asset('assets/images/products/product_0-2.jpg')}}" width="674"
                    height="674" alt="" />
                  <a data-fancybox="gallery" href="../images/products/product_0-2.html" data-bs-toggle="tooltip"
                    data-bs-placement="left" title="Zoom">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_zoom" />
                    </svg>
                  </a>
                </div>
                <div class="swiper-slide product-single__image-item">
                  <img loading="lazy" class="h-auto" src="{{ asset('assets/images/products/product_0-3.jpg')}}" width="674"
                    height="674" alt="" />
                  <a data-fancybox="gallery" href="../images/products/product_0-3.html" data-bs-toggle="tooltip"
                    data-bs-placement="left" title="Zoom">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_zoom" />
                    </svg>
                  </a>
                </div>
              </div>
              <div class="swiper-button-prev"><svg width="7" height="11" viewBox="0 0 7 11"
                  xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_prev_sm" />
                </svg></div>
              <div class="swiper-button-next"><svg width="7" height="11" viewBox="0 0 7 11"
                  xmlns="http://www.w3.org/2000/svg">
                  <use href="#icon_next_sm" />
                </svg></div>
            </div>
          </div>
          <div class="product-single__thumbnail">
            <div class="swiper-container">
              <div class="swiper-wrapper">
                <div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto"
                    src="{{ asset('assets/images/products/product_0.jpg')}}" width="104" height="104" alt="" /></div>
                <div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto"
                    src="{{ asset('assets/images/products/product_0-1.jpg')}}" width="104" height="104" alt="" /></div>
                <div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto"
                    src="{{ asset('assets/images/products/product_0-2.jpg')}}" width="104" height="104" alt="" /></div>
                <div class="swiper-slide product-single__image-item"><img loading="lazy" class="h-auto"
                    src="{{ asset('assets/images/products/product_0-3.jpg')}}" width="104" height="104" alt="" /></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="d-flex justify-content-between mb-4 pb-md-2">
          <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
            <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">Home</a>
            <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
            <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">The Shop</a>
          </div><!-- /.breadcrumb -->

          <div
            class="product-single__prev-next d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
            <a href="#" class="text-uppercase fw-medium"><svg width="10" height="10" viewBox="0 0 25 25"
                xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_prev_md" />
              </svg><span class="menu-link menu-link_us-s">Prev</span></a>
            <a href="#" class="text-uppercase fw-medium"><span class="menu-link menu-link_us-s">Next</span><svg
                width="10" height="10" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                <use href="#icon_next_md" />
              </svg></a>
          </div><!-- /.shop-acs -->
        </div>
        <h1 class="product-single__name">{{ $product->name }}</h1>
        <div class="product-single__rating">
          <div class="reviews-group d-flex">
            <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
              <use href="#icon_star" />
            </svg>
            <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
              <use href="#icon_star" />
            </svg>
            <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
              <use href="#icon_star" />
            </svg>
            <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
              <use href="#icon_star" />
            </svg>
            <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
              <use href="#icon_star" />
            </svg>
          </div>
          <span class="reviews-note text-lowercase text-secondary ms-1">8k+ reviews</span>
        </div>
        <div class="product-single__price">
          <span class="current-price">$449</span>
        </div>
    
        <form name="addtocart-form" method="post">
          <div class="product-single__addtocart">
            <div class="qty-control position-relative">
              <input type="number" name="quantity" value="1" min="1" class="qty-control__number text-center">
              <div class="qty-control__reduce">-</div>
              <div class="qty-control__increase">+</div>
            </div><!-- .qty-control -->
            <button type="submit" class="btn btn-primary btn-addtocart js-open-aside" data-aside="cartDrawer">Add to
              Cart</button>
          </div>
        </form>
        <div class="product-single__addtolinks">
          <a href="#" class="menu-link menu-link_us-s add-to-wishlist"><svg width="16" height="16" viewBox="0 0 20 20"
              fill="none" xmlns="http://www.w3.org/2000/svg">
              <use href="#icon_heart" />
            </svg><span>Add to Wishlist</span></a>
         
          <script src="js/details-disclosure.html" defer="defer"></script>
          <script src="js/share.html" defer="defer"></script>
        </div>
        <div class="product-single__meta-info">
          <div class="meta-item">
            <label>SKU:</label>
            <span>N/A</span>
          </div>
          <div class="meta-item">
            <p>Danh mục: {{ $product->category->name ?? 'N/A' }}</p>
          </div>

        </div>
      </div>
    </div>
    <div class="product-single__details-tab">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="tab-description-tab" data-bs-toggle="tab"
            data-bs-target="#tab-description" type="button" role="tab" aria-controls="tab-description"
            aria-selected="true">Mô tả</button>  
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-description" role="tabpanel"
          aria-labelledby="tab-description-tab">
          <div class="product-single__description">
            <p><strong>Mô tả:</strong> {{ $product->description }}</p>
          </div>
        </div>
        <div class="tab-pane fade" id="tab-additional-info" role="tabpanel" aria-labelledby="tab-additional-info-tab">
          <div class="product-single__addtional-info">
            @foreach ($product->product_details as $detail)
            <div class="item">
              <div class="mb-3">
                <label class="h6">Size</label>
                <span>{{ $detail->size }}</span>
              </div>
              <div class="mb-3">
                <label class="h6">Giá:</label>
                <span>{{ number_format($detail->price, 0) }} VNĐ</span>
              </div>
              <div class="mb-3">
                <label class="h6">Màu:</label>
                <span>{{ $detail->color }}</span>
              </div>
              <div class="mb-3">
                <label class="h6">Số lượng:</label>
                <span>{{ $detail->quantity }}</span>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
  </section>
</main>

@endsection