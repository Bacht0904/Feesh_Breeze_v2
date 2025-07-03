@extends('layouts.app')

@section('content')
<main class="pt-90">
  <section class="shop-checkout container">
    <h2 class="page-title mb-4">Danh sách yêu thích</h2>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
    @elseif (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
    @endif



    <ul class="list-group">
      @forelse($items as $detail)
      @php $product = $detail->product; @endphp
      <li class="list-group-item py-3 px-2 d-flex align-items-center justify-content-between flex-wrap flex-md-nowrap">
        {{-- Hình ảnh --}}
        <div class="me-3">
          <a href="{{ route('products.show', $product->slug) }}">
            <img src="{{ asset($detail->image) }}"
              alt="{{ $product->name }}"
              width="90" height="90"
              style="object-fit:cover; border-radius:6px;">
          </a>
        </div>

        {{-- Tên và chi tiết --}}
        <div class="flex-grow-1 me-3">
          <h6 class="mb-1">
            <a href="{{ route('products.show', $product->slug) }}"
              class="text-decoration-none text-dark"
              title="{{ $product->name }}">
              {{ \Illuminate\Support\Str::limit($product->name, 20) }}
            </a>
          </h6>
        </div>

        {{-- Hành động --}}
        <div class="d-flex align-items-stretch">
          {{-- Form thêm vào giỏ --}}
          <form action="{{ route('wishlist.moveToCart') }}" method="POST" class="d-flex align-items-stretch">
            @csrf
            <input type="hidden" name="original_product_detail_id" value="{{ $detail->id }}">

            {{-- Dropdown --}}
            <select name="product_detail_id"
              class="form-select form-select-sm"
              style="width: 280px; height: 38px; line-height: 1.5; padding: 0.25rem 0.5rem;"
              required>
              @foreach ($product->product_details as $variant)
              <option value="{{ $variant->id }}">
                {{ Str::limit("Size {$variant->size} – " . number_format($variant->price, 0) . " VNĐ – Màu: {$variant->color}", 50) }}
              </option>
              @endforeach
            </select>

            {{-- Button Thêm --}}
            <button type="submit"
              class="btn btn-sm btn-primary"
              style="height: 38px; border-radius: 0; border-left: 1px solid #ccc;">
              Thêm vào giỏ
            </button>
          </form>

          {{-- Form nút Xóa --}}
          <form action="{{ route('wishlist.remove', $detail->id) }}" method="POST" class="d-flex">
            @csrf @method('DELETE')
            <button type="submit"
              class="btn btn-sm text-white"
              style="background-color: #dc3545; height: 38px; border-radius: 0; margin-left: 2px;">
              Xóa
            </button>
          </form>
        </div>



      </li>
      @empty
      <li class="list-group-item text-center py-4">
        Bạn chưa có sản phẩm yêu thích nào.
      </li>
      @endforelse
    </ul>

  </section>
</main>
@endsection
@push('scripts')
<script>
    setTimeout(() => {
        const alert = document.getElementById('success-alert');
        if (alert) {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close(); // kích hoạt fade và tự tắt
        }
    }, 3000); // 3 giây
</script>
@endpush