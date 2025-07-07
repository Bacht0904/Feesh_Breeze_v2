@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Chi tiết sản phẩm</h3>
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
                        <a href="{{route('admin.products')}}">
                            <div class="text-tiny">Sản phẩm</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Chi tiết sản phẩm</div>
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
                </div>
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Hình ảnh</th>
                                    <th>Size</th>
                                    <th>Màu sắc</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->product_details as $detail)
                                    <tr>
                                        <td>{{ $detail->id }}</td>
                                        <td class="name">
                                            <a href="#" class="body-title-2">{{ $product->name }}</a>
                                        </td>
                                        <td>
                                            @if($detail->image)
                                                <img src="{{ asset($detail->image) }}" alt="Ảnh biến thể {{ $detail->id }}"
                                                    style="max-width: 80px; height: auto;">
                                            @else
                                                <span>Không có hình ảnh</span>
                                            @endif
                                        </td>
                                        <td>{{ $detail->size }}</td>
                                        <td>{{ $detail->color }}</td>
                                        <td>{{ number_format( $detail->price,'0',',','.' ) . ' VNĐ' }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="divider"></div>
                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                        {{-- {{ $product->links('pagination::bootstrap-5') }} --}}
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