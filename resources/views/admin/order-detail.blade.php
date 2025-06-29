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
                    <h5>Chi tiết đơn hàng </h5>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.orders') }}">Trở về</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Mã hóa đơn</th>
                        <td>{{ $order->id }}</td>
                        <th>Số điện thoại</th>
                        <td>{{ $order->phone }}</td>
                        <th>Ngày đặt</th>
                        <td>{{ $order->order_date }}</td>
                    </tr>

                    <tr> 
                        <th>Trạng thái đơn hàng</th>
                        <td colspan="5">
                            @if($order->status == 'Chờ Xác Nhận')
                                <span class="badge bg-success">Chờ Xác Nhận</span>
                            @elseif($order->status == 'Đã Xác Nhận')
                                <span class="badge bg-success">Đã Xác Nhận</span>
                            @elseif($order->status == 'Đang Giao')
                                <span class="badge bg-success">Đang Giao</span>
                            @elseif($order->status == 'Đã Nhận')
                                <span class="badge bg-success">Đã Nhận</span>
                            @else
                                <span class="badge bg-success">Đã Hủy</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <h5>Danh sách sản phẩm</h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">Giá</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">SKU</th>
                                <th class="text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderItems as $item)
                            <tr>
                                <td class="pname">
                                    <div class="image">
                                        <img src="1718066538.html" alt="{{ $item->product_name }}" class="image">
                                    </div>
                                    <div class="name">
                                        <a href="#" target="_blank" class="body-title-2">{{ $item->product_name }}</a>
                                    </div>
                                </td>
                                <td class="text-center">{{ $item->price }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-center">SHT01245</td>
                                <td class="text-center">
                                    <div class="list-icon-function view-icon">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
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

            <div class="wg-box mt-5">
                <h5>Địa chỉ giao hàng</h5>
                <div class="my-account__address-item col-md-6">
                    <div class="my-account__address-item__detail">
                        <p>{{ $order->name }}</p>
                        <p>{{ $order->address }}</p>
                        <br>
                        <p>Mobile : {{ $order->phone }}</p>
                    </div>
                </div>
            </div>

            <div class="wg-box mt-5">
                <h5>Giao dịch</h5>
                <table class="table table-striped table-bordered table-transaction">
                    <tbody>
                        <tr>
                            <th>Tổng tiền hàng</th>
                            <td>{{ $order->suptotal }}</td>
                            <th>Phí giao hàng</th>
                            <td>36.12</td>
                            <th>Giảm giá</th>
                            <td>0.00</td>
                        </tr>
                        <tr>
                            <th>Thành tiền</th>
                            <td>{{ $order->suptotal }}</td>
                            <th>Phương thức thanh toán</th>
                            <td>{{ $order->payment_method }}</td>
                            <th>Trạng thái đơn hàng</th>
                            <td colspan="5">
                                @if($order->status == 'Chờ Xác Nhận')
                                    <span class="badge bg-success">Chờ Xác Nhận</span>
                                @elseif($order->status == 'Đã Xác Nhận')
                                    <span class="badge bg-success">Đã Xác Nhận</span>
                                @elseif($order->status == 'Đang Giao')
                                    <span class="badge bg-success">Đang Giao</span>
                                @elseif($order->status == 'Đã Nhận')
                                    <span class="badge bg-success">Đã Nhận</span>
                                @else
                                    <span class="badge bg-success">Đã Hủy</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Ngày đặt hàng</th>
                            <td>{{ $order->order_date }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
