@extends('layouts.app')

@section('content')

<main class="pt-90">
  <section class="product-single container">
    <div class="row align-items-start">

      {{-- Ảnh sản phẩm chính --}}
      <div class="col-lg-7">
        <div class="swiper swiper-product-detail">
          <div class="swiper-wrapper">
            @foreach ($product->product_details as $detail)
            @php
            $normColor = mb_strtolower(trim(preg_replace('/\s+/', ' ', $detail->color)));
            @endphp
            <div class="swiper-slide text-center" data-color="{{ $normColor }}">
              <img src="{{ asset($detail->image) }}" class="img-fluid rounded mb-3"
                alt="{{ $product->name }} - {{ $detail->size }}"
                style="max-width: 400px; height: auto;">
            </div>
            @endforeach

          </div>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
        </div>
      </div>
      {{-- Chi tiết bên phải --}}
      <div class="col-lg-5">
        <h2 class="fw-bold mb-3">{{ $product->name }}</h2>
        <p class="text-muted mb-1">{{ $product->category->name ?? 'Chưa phân loại' }}</p>
        <p><strong>Đánh giá:</strong>
          @if ($product->reviews->count() > 0)
          <span class="text-rating-custom fw-bold">
            {{ number_format($product->reviews->avg('rating'), 1) }}
            <i class="fa fa-star"></i>
          </span>

          <a href="{{ route('product.reviews', $product->id) }}" class="review-count">
            ({{ $product->reviews->count() }} đánh giá)
          </a>
          @else
          <span class="text-muted fst-italic">Chưa có đánh giá</span>
          @endif

        </p>
        <p class="fw-bold fs-4 text-danger">{{ number_format($product->product_details->first()->price, 0) }} VNĐ</p>
        <form action="{{ route('cart.addDetail') }}" method="POST">
          @csrf


          {{-- Chọn màu --}}
          <div class="mb-3">
            <label class="form-label">Chọn màu</label>
            <div class="d-flex flex-wrap gap-2">
              @foreach ($colors as $idx => $c)
              <label class="color-swatch" title="{{ $c['label'] }}">
                <input
                  type="radio"
                  name="color"
                  value="{{ $c['name'] }}"
                  class="d-none"
                  {{ $idx === 0 ? 'checked' : '' }}>
                <span class="swatch-circle" style="background-color: {{ $c['code'] }};"></span>
              </label>
              @endforeach
            </div>
          </div>

          {{-- Chọn size --}}
          <div class="mb-3">
            <label class="form-label">Chọn size</label>
            <div class="d-flex flex-wrap gap-2" id="size-container">
              @foreach ($product->product_details as $detail)
              @php
              $norm = mb_strtolower(trim(preg_replace('/\s+/', ' ', $detail->color)));
              $qty = $detail->quantity; // cột quantity trong DB
              $size = strtoupper($detail->size);
              @endphp

              <label
                class="size-option"
                data-color="{{ $norm }}"
                data-qty="{{ $qty }}"
                style="display: none">
                <input
                  type="radio"
                  name="product_detail_id"
                  value="{{ $detail->id }}"
                  class="btn-check d-none"
                  {{ $qty == 0 ? 'disabled' : '' }}>
                <span class="btn btn-outline-secondary btn-sm">
                  {{ $size }}
                  <small class="text-muted">({{ $qty }})</small>
                </span>
              </label>
              @endforeach
            </div>
          </div>



          {{-- Số lượng --}}
          {{-- Số lượng --}}
          <div class="mb-3">
            <label class="form-label">Số lượng</label>
            <input id="quantity-input"
              type="number"
              name="quantity"
              value="1"
              min="1"
              class="form-control w-50"
              required>
          </div>

          <button type="submit"
            id="add-to-cart-btn"
            class="btn btn-primary btn-lg w-100"
            disabled>
            🛒 Thêm vào giỏ hàng
          </button>

        </form>

        {{-- Mô tả sản phẩm --}}
        <a href="javascript:void(0)" id="toggle-description" class="text-decoration-underline d-inline-block mb-3">
          🔽 Xem mô tả
        </a>

        <div id="product-description" class="collapse">
          <hr class="mt-4">
          <h5 class="fw-bold">Mô tả</h5>
          <p>{{ $product->description }}</p>
        </div>

      </div>
    </div>

  </section>
</main>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
<style>
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

  .size-option {
    opacity: 0.7;
    transition: opacity 0.2s;
  }

  .size-option[style*="display: none"] {
    visibility: hidden;
    height: 0;
    margin: 0;
    padding: 0;
    opacity: 0;
  }
</style>
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
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const colorRadios = document.querySelectorAll('input[name="color"]');
    const sizeLabels = document.querySelectorAll('.size-option');
    const btnSubmit = document.querySelector('button[type="submit"]');
    const slides = document.querySelectorAll('.swiper-slide');
    const swiperInstance = document.querySelector('.swiper-product-detail')?.swiper;

    function updateByColor(selectedColor) {
      let any = false;

      sizeLabels.forEach(lbl => {
        const color = lbl.dataset.color;
        const qty = parseInt(lbl.dataset.qty, 10);
        const match = color === selectedColor && qty > 0;

        // show/hide size
        lbl.style.display = match ? 'inline-block' : 'none';

        // opacity + disable input
        lbl.classList.toggle('opacity-100', match);
        lbl.classList.toggle('opacity-50', !match);
        lbl.querySelector('input').disabled = !match;

        if (match) any = true;
      });

      // button
      btnSubmit.disabled = !any;
      btnSubmit.innerText = any ?
        '🛒 Thêm vào giỏ hàng' :
        '⛔ Không có size phù hợp';

      // Swiper slide tương ứng
      if (swiperInstance) {
        let found = false;
        slides.forEach((slide, idx) => {
          if ((slide.dataset.color || '').trim().toLowerCase() === selectedColor) {
            swiperInstance.slideTo(idx);
            found = true;
          }
        });
        if (!found) swiperInstance.slideTo(0);
      }
    }

    // gán event
    colorRadios.forEach(radio =>
      radio.addEventListener('change', () => {
        updateByColor(radio.value.trim().toLowerCase());
      })
    );

    // khởi tạo lần đầu
    const first = document.querySelector('input[name="color"]:checked');
    if (first) updateByColor(first.value.trim().toLowerCase());
  });
</script>

@endpush


@push('style')
<style>
  .color-option input:checked+.color-circle {
    box-shadow: 0 0 0 3px #000;
    border: 2px solid #fff;
  }

  .size-option.opacity-50 {
    opacity: 0.4;
    pointer-events: none;
  }

  .size-option.opacity-100 {
    opacity: 1;
  }
</style>
@endpush
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggle-description');
    const description = document.getElementById('product-description');

    toggleBtn.addEventListener('click', function() {
      const isOpen = description.classList.contains('show');

      description.classList.toggle('show');
      toggleBtn.innerHTML = isOpen ? '🔽 Xem mô tả' : '🔼 Thu gọn';
    });
  });
</script>
@endpush