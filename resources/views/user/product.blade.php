@extends('layouts.app')

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
@endsection
