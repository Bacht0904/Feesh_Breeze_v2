@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Danh sách loại sản phẩm</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{route('admin.index')}}">
                            <div class="text-tiny">Trang chủ</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Loại sản phẩm</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <form class="form-search" method="GET" action="{{ route('admin.categories.search') }}">
                            <fieldset class="name">
                                <input type="text" placeholder="Tìm kiếm..." class="" name="name" tabindex="2"
                                    value="{{ request()->input('name') }}" aria-required="true" required="">
                            </fieldset>
                            <div class="button-submit">
                                <button class="" type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <a class="tf-button style-1 w208" href="{{ route('admin.category.add') }}"><i class="icon-plus"></i>Thêm
                        mới</a>
                </div>
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        @if(Session::has('status'))
                            <p class="alert alert-success">{{ Session::get('status') }}</p>
                        @endif
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên loại sản phẩm</th>
                                    <th>slug</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td class="name">


                                            <a href="#" class="body-title-2">{{ $category->name }}</a>

                                        </td>
                                        <td>{{ $category->slug }}</td>
                                        <td>
                                            {{ $category->status === 'active' ? 'Hoạt động' : 'Ngừng hoạt động' }}
                                        </td>

                                        <td>
                                            <div class="list-icon-function">
                                                <form action="{{ route('admin.category.edit', $category->id) }}" method="GET"
                                                    style="display: inline;">
                                                    <button style="border: 1px solid transparent;" type="submit"
                                                        class="item edit">
                                                        <i class="icon-edit-3"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.category.delete', $category->id) }}" method="POST">

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
                        {{ $categories->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
    </script>
@endpush