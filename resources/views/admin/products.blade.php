@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Danh sách sản phẩm</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Trang chủ</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Sản phẩm</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search" method="GET" action="{{ route('admin.products.search') }}">
                            <fieldset class="name">
                                <input type="text" placeholder="Tìm kiếm..." class="" name="name" tabindex="2" value="{{ request('name') }}"
                                    aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.product.add') }}"><i class="icon-plus"></i>Thêm
                        mới</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th class="items-center">Tên sản phẩm</th>
                                <th>Slug</th>
                                <th>Giá</th>
                                <th>Loại sản phẩm</th>
                                <th>Thương hiệu</th>
                                <th>Mô tả</th>
                                <th>Hình ảnh</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td class="">
                                        <a href="#" class="body-title-2">{{ $product->name }}</a>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="">{{ $product->slug }}</div>
                                    </td>
                                    <td>
                                        @if($product->product_details->count())
                                            {{ number_format($product->product_details->min('price')) }}₫
                                        @else
                                            <span class="">Chưa có giá</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->category->name ?? 'Chưa có loại' }}</td>
                                    <td>{{ $product->brand->name ?? 'Chưa có thương hiệu' }}</td>
                                    <!-- <td style="max-width: 250px;">{{ Str::limit($product->description, 100) }}</td> -->
                                    <td
                                        style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <span
                                            title="{{ $product->description }}">{{ Str::limit($product->description, 100) }}</span>
                                    </td>

                                    <td>
                                        @if($product->product_details->count() && $product->product_details->first()->image)
                                            <img src="{{ asset($product->product_details->first()->image) }}"
                                                alt="{{ $product->name }}" style="max-width: 80px; height: auto;">
                                        @else
                                            <span>Không có hình ảnh</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->product_details->sum('quantity') > 0)
                                            <span class="badge bg-success">Còn hàng</span>
                                        @else
                                            <span class="badge bg-secondary">Hết hàng</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="list-icon-function">
                                            <a href="{{ route('admin.product.detail', $product->id) }}" target="_blank">
                                                <div class="item eye">
                                                    <i class="icon-eye"></i>
                                                </div>
                                            </a>
                                            <a href="{{ route('admin.product.edit', $product->id) }}">
                                                <div class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </div>
                                            </a>
                                            <form action="{{ route('admin.product.delete',$product->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="item text-danger delete"
                                                    style="background:none; border:none;">
                                                    <i class="icon-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

                    {{ $products->links('pagination::bootstrap-5') }}
                    <!-- {{ $products->appends(['name' => request('name')])->links() }} -->
                </div>
            </div>
        </div>
    </div>
@endsection