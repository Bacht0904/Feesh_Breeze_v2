@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Danh sách thương hiệu</h3>
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
                        <div class="text-tiny">Thương hiệu</div>
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
                    <a class="tf-button style-1 w208" href="{{ route('admin.brand.add') }}"><i class="icon-plus"></i>Thêm
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
                                    <th>STT</th>
                                    <th>Tên thương hiệu</th>
                                    <th>slug</th>
                                    <th>Sản phẩm</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brands as $brand)
                                    <tr>
                                        <td>{{ $brand->id }}</td>
                                        <td class="name">


                                            <a href="#" class="body-title-2">{{ $brand->name }}</a>

                                        </td>
                                        <td>{{ $brand->slug }}</td>
                                        <td><a href="#" target="_blank">0</a></td>
                                        <td>
                                            <div class="list-icon-function">
                                                <a href="{{ route('admin.brand.edit', ['id' => $brand->id]) }}">
                                                    <div class="item edit">
                                                        <i class="icon-edit-3"></i>
                                                    </div>
                                                </a>
                                                <form action="{{ route('admin.brand.delete', ['id' => $brand->id]) }}"
                                                    method="POST">

                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="item text-danger delete"
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
                        {{ $brands->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $function(document).ready(function () {
            // Xử lý sự kiện click cho các nút xóa
            $('.delete').on('click', function (event) {
                event.preventDefault();
                swal({
                    title: "Xác nhận xóa",
                    text: "Bạn có chắc chắn muốn xóa thương hiệu này?",
                    icon: "warning",
                    buttons: ["Hủy", "Xóa"],
                    confirmButtonColor: "#DD6B55",
                }).then((willDelete) => {
                    if (willDelete) {
                        // Nếu người dùng xác nhận xóa, gửi form
                        $(this).closest('form').submit();
                    } else {
                        // Nếu người dùng hủy, không làm gì cả
                        swal("Thương hiệu không bị xóa!");
                    }
                });

            });
        });
        // document.addEventListener('DOMContentLoaded', function() {
        //     const deleteForms = document.querySelectorAll('.delete');
        //     deleteForms.forEach(form => {
        //         form.addEventListener('click', function(event) {
        //             event.preventDefault();
        //             if (confirm('Bạn có chắc chắn muốn xóa thương hiệu này?')) {
        //                 this.closest('form').submit();
        //             }
        //         });
        //     });
        // });
    </script>
@endpush
