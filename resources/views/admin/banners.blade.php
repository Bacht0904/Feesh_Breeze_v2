@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Banner</h3>
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
                        <div class="text-tiny">Banner</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search">
                            <fieldset class="name">
                                <input type="text" placeholder="Tìm kiếm..." class="" name="name" tabindex="2" value=""
                                    aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.banner.add') }}"><i class="icon-plus"></i>Thêm
                        mới</a>
                </div>
                <div class="wg-table table-all-user">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tiêu đề</th>
                                <th>Hình ảnh</th>
                                <th>Thương hiệu</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($banners as $banner)
                                <tr>
                                    <td>{{ $banner->id }}</td>
                                    <td class="body-title-2">{{ Str::limit($banner->title,30) }}</td>
                                    <td class="pname">
                                        <div class="image">
                                            <img src="{{ asset($banner->image) }}" width="100" height="100" alt="Ảnh slide" class="image">
                                        </div>
                                    </td>
                                    <td>{{ $banner->brand->name ?? 'Chưa có thương hiệu' }}</td>
                                    <td class="body-title-2">{{ Str::limit($banner->description,62) }}</td>
                                    <!-- <td>
                                                @if($banner->status == 'active')
                                                    <span class="badge bg-success">Hoạt động</span>
                                                @else
                                                    <span class="badge bg-secondary">Không hoạt động</span>
                                                @endif
                                            </td> -->
                                    <!-- Trong vòng lặp slide -->
                                    <td>
                                        <form action="{{ route('admin.banner.toggle', $banner->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="badge {{ $banner->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $banner->status == 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                                            </button>
                                        </form>
                                    </td>

                                    <td>
                                        <div class="list-icon-function">
                                            <form action="{{ route('admin.banner.edit', $banner->id) }}" method="GET"
                                                style="display: inline;">
                                                <button style="border: 1px solid transparent;" type="submit" class="item edit">
                                                    <i class="icon-edit-3"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.banner.delete', $banner->id) }}" method="POST">

                                                @csrf
                                                @method('DELETE')
                                                <button style="border: 1px solid transparent;" type="submit"
                                                    class="item text-danger delete"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
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

                </div>
            </div>
        </div>
    </div>
@endsection