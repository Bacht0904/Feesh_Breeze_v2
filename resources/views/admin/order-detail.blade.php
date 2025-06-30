@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Thông tin hóa đơn</h3>
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
                        <div class="text-tiny">Chi tiết đơn hàng</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <h5>Danh sách sản phẩm</h5>
                    </div>
                    <a class="tf-button style-1 w208" href="orders.html">Trở về</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">Giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">SKU</th>
                                <th class="text-center">Loại</th>
                                <th class="text-center">Thương hiệu</th>
                                <th class="text-center">Trạng thái phản hồi</th>
                                <th class="text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>

                                <td class="pname">
                                    <div class="image">
                                        <img src="1718066538.html" alt="" class="image">
                                    </div>
                                    <div class="name">
                                        <a href="#" target="_blank" class="body-title-2">Product1</a>
                                    </div>
                                </td>
                                <td class="text-center">$71.00</td>
                                <td class="text-center">1</td>
                                <td class="text-center">SHT01245</td>
                                <td class="text-center">Category1</td>
                                <td class="text-center">Brand1</td>
                                <td class="text-center">No</td>
                                <td class="text-center">
                                    <div class="list-icon-function view-icon">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>

                                <td class="pname">
                                    <div class="image">
                                        <img src="1718066673.html" alt="" class="image">
                                    </div>
                                    <div class="name">
                                        <a href="#" target="_blank" class="body-title-2">Product2</a>
                                    </div>
                                </td>
                                <td class="text-center">$101.00</td>
                                <td class="text-center">1</td>
                                <td class="text-center">SHT99890</td>
                                <td class="text-center">Category2</td>
                                <td class="text-center">Brand1</td>
                                <td class="text-center">No</td>
                                <td class="text-center">
                                    <div class="list-icon-function view-icon">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

                </div>
            </div>

            <div class="wg-box mt-5">
                <h5>Địa chỉ giao hàng</h5>
                <div class="my-account__address-item col-md-6">
                    <div class="my-account__address-item__detail">
                        <p>Tp.HCM</p>
                        <p>Quận 1</p>
                        <p>P. Bến Nghé</p>
                        <p>Huỳnh Thúc Kháng</p>
                        <br>
                        <p>Mobile : 1234567891</p>
                    </div>
                </div>
            </div>

            <div class="wg-box mt-5">
                <h5>Gao dịch</h5>
                <table class="table table-striped table-bordered table-transaction">
                    <tbody>
                        <tr>
                            <th>Tổng tiền hàng</th>
                            <td>172.00</td>
                            <th>Phí giao hàng</th>
                            <td>36.12</td>
                            <th>Giảm giá</th>
                            <td>0.00</td>
                        </tr>
                        <tr>
                            <th>Thành tiền</th>
                            <td>208.12</td>
                            <th>Phương thức thanh toán</th>
                            <td>Tiền mặt</td>
                            <th>Trạng thái đơn hàng</th>
                            <td>Đang giao</td>
                        </tr>
                        <tr>
                            <th>Ngày đặt hàng</th>
                            <td>2024-07-11 00:54:14</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection