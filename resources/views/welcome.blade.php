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
            <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">BÁN CHẠY</h2>
            <div class="row">
                <div
                    class="col-md-6 col-lg-4 col-xl-20per d-flex align-items-center flex-column justify-content-center py-4 align-items-md-start">
                    <!-- <h2>July Sale</h2>
                    <h2 class="fw-bold">Up to 60% Off</h2> -->



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
                                @foreach($hotDeals as $detail)
                                @php
                                $product = $detail->product ?? null;
                                @endphp

                                @if($detail && $detail->quantity > 0)
                                <div class="swiper-slide">
                                    <div class="card border-0 shadow-sm product-card h-100 text-center">
                                        <a href="{{ route('products.show', $product->slug) }}" class="d-block">
                                            <img
                                                src="{{ asset($detail->image ?: 'images/placeholder.jpg') }}"
                                                alt="{{ $product->name }}"
                                                class="card-img-top img-cover">
                                        </a>
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
                                                <a href="{{ route('products.show', $product->slug) }}"
                                                    class="text-dark text-decoration-none d-block"
                                                    title="{{ $product->name }}">
                                                    {{ Str::limit($product->name, 20) }}
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
                            <!-- /.swiper-wrapper -->
                        </div><!-- /.swiper-container js-swiper-slider -->
                    </div><!-- /.position-relative -->
                </div>
            </div>
        </section>
        <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>
        <section class="container py-5">
            <h2 class="text-center fw-bold mb-4">Sản phẩm nổi bật</h2>

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
                                        {{ Str::limit($product->name, 20) }}
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