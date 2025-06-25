<!-- @extends('layouts.app')

@section('content')
<div class="container">
  <h2>Chi tiết sản phẩm</h2>

  @php
    $firstDetail = $product->product_details->first();
  @endphp

  @if ($firstDetail && $firstDetail->image)
    <img src="{{ asset('/' . $firstDetail->image) }}" alt="{{ $product->name }}" class="img-fluid mb-3">
  @endif

  <h1>{{ $product->name }}</h1>
  <p>Danh mục: {{ $product->category->name ?? 'N/A' }}</p>

  @foreach ($product->product_details as $detail)
    <div class="mb-3">
      <p><strong>Size:</strong> {{ $detail->size }}</p>
      <p><strong>Giá:</strong> {{ number_format($detail->price, 0) }} VNĐ</p>
      <p><strong>Màu:</strong> {{ $detail->color }}</p>
    </div>
  @endforeach

  <p><strong>Mô tả:</strong> {{ $product->description }}</p>
  <p><strong>Trạng thái:</strong> {{ $product->status ? 'Còn hàng' : 'Hết hàng' }}</p>
  <p><strong>Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
</div>
@endsection -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h2 class="mb-4">Chi tiết sản phẩm</h2>

  @php $firstDetail = $product->product_details->first(); @endphp

  <div class="row">
    {{-- Ảnh sản phẩm --}}
    <div class="col-md-6">
      @if ($firstDetail && $firstDetail->image)
        <img src="{{ asset($firstDetail->image) }}" alt="{{ $product->name }}" class="img-fluid mb-3 rounded">
      @else
        <p class="text-muted">Không có ảnh hiển thị</p>
      @endif
    </div>

    {{-- Thông tin sản phẩm --}}
    <div class="col-md-6">
      <h1>{{ $product->name }}</h1>
      <p><strong>Danh mục:</strong> {{ $product->category->name ?? 'Chưa phân loại' }}</p>
      <p><strong>Trạng thái:</strong> {{ $product->status ? 'Còn hàng' : 'Hết hàng' }}</p>
      <p><strong>Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
      <p><strong>Mô tả:</strong> {{ $product->description }}</p>

      {{-- Chi tiết biến thể --}}
      <hr>
      @foreach ($product->product_details as $detail)
        <div class="border rounded p-3 mb-3">
          <p><strong>Size:</strong> {{ $detail->size }}</p>
          <p><strong>Giá:</strong> {{ number_format($detail->price, 0) }} VNĐ</p>
          <p><strong>Màu:</strong> {{ $detail->color }}</p>
          <p><strong>Chất liệu:</strong> {{ $detail->material ?? 'Chưa cập nhật' }}</p>
          <p><strong>Xuất xứ:</strong> {{ $detail->origin ?? 'Chưa cập nhật' }}</p>
          <p><strong>Số lượng tồn kho:</strong> {{ $detail->quantity ?? 'N/A' }}</p>
        </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
