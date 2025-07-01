@extends('layouts.app')
@section('content')
<main>
    <section class="container pt-90 pb-5">
        <h1 class="page-title text-center mb-4">Chào mừng đến với Feesh Breeze</h1>
    </section>

    @if(session('success') || session('error'))
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-2 px-4 py-3 
              {{ session('success') ? 'alert-success' : 'alert-danger' }}"
        role="alert" id="flash-alert">

        {{-- Biểu tượng --}}
        <span class="fs-4">
            {!! session('success') ? '✅' : '❌' !!}
        </span>

        {{-- Nội dung --}}
        <div class="flex-grow-1">
            {{ session('success') ?? session('error') }}
        </div>

        {{-- Nút đóng --}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
    @endif


    <section class="swiper-container js-swiper-slider swiper-number-pagination slideshow" data-settings='{
        "autoplay": {
          "delay": 5000
        },
        "slidesPerView": 1,
        "effect": "fade",
        "loop": true
      }'>
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="overflow-hidden position-relative h-100">
                    <div class="slideshow-character position-absolute bottom-0 pos_right-center">
                        <img loading="lazy" src="assets/images/home/demo3/slideshow-character1.png" width="542" height="733"
                            alt="Woman Fashion 1"
                            class="slideshow-character__img animate animate_fade animate_btt animate_delay-9 w-auto h-auto" />
                        <div class="character_markup type2">
                            <p
                                class="text-uppercase font-sofia mark-grey-color animate animate_fade animate_btt animate_delay-10 mb-0">
                                Dresses</p>
                        </div>
                    </div>
                    <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
                        <h6 class="text_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
                            New Arrivals</h6>
                        <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">Night Spring</h2>
                        <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-5">Dresses</h2>
                        <a href="#"
                            class="btn-link btn-link_lg default-underline fw-medium animate animate_fade animate_btt animate_delay-7">Shop
                            Now</a>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="overflow-hidden position-relative h-100">
                    <div class="slideshow-character position-absolute bottom-0 pos_right-center">
                        <img loading="lazy" src="assets/images/slideshow-character1.png" width="400" height="733"
                            alt="Woman Fashion 1"
                            class="slideshow-character__img animate animate_fade animate_btt animate_delay-9 w-auto h-auto" />
                        <div class="character_markup">
                            <p class="text-uppercase font-sofia fw-bold animate animate_fade animate_rtl animate_delay-10">Summer
                            </p>
                        </div>
                    </div>
                    <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
                        <h6 class="text_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
                            New Arrivals</h6>
                        <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">Night Spring</h2>
                        <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-5">Dresses</h2>
                        <a href="#"
                            class="btn-link btn-link_lg default-underline fw-medium animate animate_fade animate_btt animate_delay-7">Shop
                            Now</a>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="overflow-hidden position-relative h-100">
                    <div class="slideshow-character position-absolute bottom-0 pos_right-center">
                        <img loading="lazy" src="assets/images/slideshow-character2.png" width="400" height="690"
                            alt="Woman Fashion 2"
                            class="slideshow-character__img animate animate_fade animate_rtl animate_delay-10 w-auto h-auto" />
                    </div>
                    <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
                        <h6 class="text_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
                            New Arrivals</h6>
                        <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">Night Spring</h2>
                        <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-5">Dresses</h2>
                        <a href="#"
                            class="btn-link btn-link_lg default-underline fw-medium animate animate_fade animate_btt animate_delay-7">Shop
                            Now</a>
                    </div>
                </div>
            </div>

        </div>
        <div class="container">
            <div
                class="slideshow-pagination slideshow-number-pagination d-flex align-items-center position-absolute bottom-0 mb-5">
            </div>
        </div>

    </section>
    <div class="container mw-1620 bg-white border-radius-10">
        <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>
        <section class="hot-deals container">
            <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">Hot Deals</h2>
            <div class="row">
                <div
                    class="col-md-6 col-lg-4 col-xl-20per d-flex align-items-center flex-column justify-content-center py-4 align-items-md-start">
                    <h2>Summer Sale</h2>
                    <h2 class="fw-bold">Up to 60% Off</h2>

                    <div class="position-relative d-flex align-items-center text-center pt-xxl-4 js-countdown mb-3"
                        data-date="18-3-2024" data-time="06:50">
                        <div class="day countdown-unit">
                            <span class="countdown-num d-block"></span>
                            <span class="countdown-word text-uppercase text-secondary">Days</span>
                        </div>

                        <div class="hour countdown-unit">
                            <span class="countdown-num d-block"></span>
                            <span class="countdown-word text-uppercase text-secondary">Hours</span>
                        </div>

                    </div>

                    <a href="#" class="btn-link default-underline text-uppercase fw-medium mt-3">View All</a>
                </div>
                <div class="col-md-6 col-lg-8 col-xl-80per">
                    <div class="position-relative">
                        <div class="swiper-container js-swiper-slider" data-settings='{
                  "autoplay": {
                    "delay": 5000
                  },
                  "slidesPerView": 4,
                  "slidesPerGroup": 4,
                  "effect": "none",
                  "loop": false,
                  "breakpoints": {
                    "320": {
                      "slidesPerView": 2,
                      "slidesPerGroup": 2,
                      "spaceBetween": 14
                    },
                    "768": {
                      "slidesPerView": 2,
                      "slidesPerGroup": 3,
                      "spaceBetween": 24
                    },
                    "992": {
                      "slidesPerView": 3,
                      "slidesPerGroup": 1,
                      "spaceBetween": 30,
                      "pagination": false
                    },
                    "1200": {
                      "slidesPerView": 4,
                      "slidesPerGroup": 1,
                      "spaceBetween": 30,
                      "pagination": false
                    }
                  }
                }'>
                            <div class="swiper-wrapper">
                                <div class="swiper-slide product-card product-card_style3">
                                    <div class="pc__img-wrapper">
                                        <a href="details.html">
                                            <img loading="lazy" src="{{asset('assets/images/home/demo3/product-0-1.jpg')}}" width="258" height="313"
                                                alt="Cropped Faux leather Jacket" class="pc__img">
                                            <img loading="lazy" src="{{asset('assets/images/home/demo3/product-0-2.jpg')}}" width="258" height="313"
                                                alt="Cropped Faux leather Jacket" class="pc__img pc__img-second">
                                        </a>
                                    </div>

                                    <div class="pc__info position-relative">
                                        <h6 class="pc__title"><a href="details.html">Cropped Faux Leather Jacket</a></h6>
                                        <div class="product-card__price d-flex">
                                            <span class="money price text-secondary">$29</span>
                                        </div>

                                        <div
                                            class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                                            <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                                                data-aside="cartDrawer" title="Add To Cart">Add To Cart</button>
                                            <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                                                data-bs-toggle="modal" data-bs-target="#quickView" title="Quick view">
                                                <span class="d-none d-xxl-block">Quick View</span>
                                                <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <use href="#icon_view" />
                                                    </svg></span>
                                            </button>
                                            <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="Add To Wishlist">
                                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <use href="#icon_heart" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide product-card product-card_style3">
                                    <div class="pc__img-wrapper">
                                        <a href="details.html">
                                            <img loading="lazy" src="{{asset('assets/images/home/demo3/product-3-1.jpg')}}" width="258" height="313"
                                                alt="Cropped Faux leather Jacket" class="pc__img">
                                            <img loading="lazy" src="{{asset('assets/images/home/demo3/product-3-2.jpg')}}" width="258" height="313"
                                                alt="Cropped Faux leather Jacket" class="pc__img pc__img-second">
                                        </a>
                                    </div>

                                    <div class="pc__info position-relative">
                                        <h6 class="pc__title"><a href="details.html">Cableknit Shawl</a></h6>
                                        <div class="product-card__price d-flex align-items-center">
                                            <span class="money price-old">$129</span>
                                            <span class="money price text-secondary">$99</span>
                                        </div>

                                        <div
                                            class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                                            <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-add-cart js-open-aside"
                                                data-aside="cartDrawer" title="Add To Cart">Add To Cart</button>
                                            <button class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                                                data-bs-toggle="modal" data-bs-target="#quickView" title="Quick view">
                                                <span class="d-none d-xxl-block">Quick View</span>
                                                <span class="d-block d-xxl-none"><svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <use href="#icon_view" />
                                                    </svg></span>
                                            </button>
                                            <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="Add To Wishlist">
                                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <use href="#icon_heart" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.swiper-wrapper -->
                        </div><!-- /.swiper-container js-swiper-slider -->
                    </div><!-- /.position-relative -->
                </div>
            </div>
        </section>
        <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>
        <section class="container py-5">
            <h2 class="text-center fw-bold mb-4">🌟 Sản phẩm nổi bật</h2>

            {{-- Swiper wrapper --}}
            <div class="swiper js-featured-swiper">
                <div class="swiper-wrapper">
                    @foreach($products as $product)
                    @php
                    $detail = $product->product_details->first();
                    $productUrl = route('products.show', $product->slug);

                    @endphp
                    @if($detail && $detail->quantity > 0)
                    <div class="swiper-slide">
                        <div class="card border-0 shadow-sm product-card h-100 text-center">
                            <a href="{{ $productUrl }}" class="d-block">
                                <img
                                    src="{{ asset($detail->image ?: 'images/placeholder.jpg') }}"
                                    alt="{{ $product->name }}"
                                    class="card-img-top img-cover">
                            </a>
                            <!-- <button
                                type="button"
                                class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2 js-add-wishlist"
                                title="Thêm vào wishlist"
                                data-product-id="{{ $detail->id }}">
                                <i class="fa fa-heart"></i>
                            </button> -->
                            <!-- <button class="btn btn-sm btn-outline-danger position-absolute top-0 end-0  js-add-wishlist"
                                title="Add To Wishlist">
                                <svg width="16" height="16">
                                    <use href="#icon_heart" />
                                </svg>
                            </button> -->
                            <button type="button"
                                class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 js-add-wishlist"
                                data-id="{{ $detail->id }}"
                                title="Thêm vào yêu thích">
                                <svg width="16" height="16">
                                    <use href="#icon_heart" />
                                </svg>
                            </button>


                            @if ($detail)
                            <form action="{{ route('cart.addDetail') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_detail_id" value="{{ $detail->id }}">
                                <input type="hidden" name="quantity" value="1">

                                <button type="submit"
                                    class="pc__atc btn anim_appear-bottom position-absolute border-0 text-uppercase fw-medium"
                                    data-aside="cartDrawer">
                                    Thêm vào giỏ
                                </button>
                            </form>
                            @endif
                            <div class="pc__info position-relative">

                                <h6 class="card-title mb-1">
                                    <a href="{{ $productUrl }}"
                                        class="text-dark text-decoration-none d-block"
                                        title="{{ $product->name }}">
                                        {{ Str::limit($product->name, 30) }}
                                    </a>
                                </h6>
                                @if($detail)
                                <span class="money price">${{ number_format($detail->price, 2) }}</span>
                                @else
                                <span class="text-muted">Chưa có giá</span>
                                @endif


                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>

                {{-- Navigation --}}
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

            </div>
        </section>
    </div>
</main>
@endsection
@push('scripts')
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.js-featured-swiper', {
            slidesPerView: 5,
            spaceBetween: 20,
            loop: false,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },
            breakpoints: {
                320: {
                    slidesPerView: 1
                },
                576: {
                    slidesPerView: 2
                },
                768: {
                    slidesPerView: 3
                },
                992: {
                    slidesPerView: 4
                },
                1200: {
                    slidesPerView: 5
                }
            }
        });
    });
</script>
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
    setTimeout(() => {
        const alert = document.getElementById('flash-alert');
        if (alert) {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }
    }, 3000);
</script>
@endpush