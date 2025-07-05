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
                @if(Session::has('status'))
                <p class="alert alert-success">{{Session::get('status')}}</p>
                @endif
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
                            @elseif($order->status == 'Đã Giao')
                                <span class="badge bg-success">Đã Giao</span>
                            @elseif($order->status == 'Giao Thành Công')
                                <span class="badge bg-success">Giao Thành Công</span>

                            @else
                            <span class="badge bg-danger">Đã Hủy</span>
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
                            @foreach ($order->details as $item)
                            <tr>
                                <td class="pname">
                                    <div class="image">

                                    
                                        <img src="{{ asset($item->image) }}"  class="image" style="width: 50px; height: 50px; object-fit: cover;">

                                    </div>
                                    <div class="name">
                                        <a href="#" target="_blank" class="body-title-2">{{ $item->product_name }}</a>
                                    </div>
                                </td>
                                <td class="text-center">{{number_format( $item->price,'0',',','.' )}} VND</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-center">SHT01245</td>
                                <td class="text-center">{{ $item->productDetail->product->status }} </td>
                                    {{-- <div class="list-icon-function view-icon">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </div> --}}
                                
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

            <div class="wg-box mt-1" ">
                <h5>Giao dịch</h5>
                <table class=" table table-striped table-bordered table-transaction">
                <tbody>
                    <tr>
                        <th>Tổng tiền hàng</th>
                        <td>{{number_format( $order->total,'0',',','.' )}} VND </td>
                        <th>Phí giao hàng</th>
                        <td>{{number_format($order->shipping_fee,'0',',','.')}} VND</td>
                        <th>Giảm giá</th>
                        <td>{{number_format( $order->coupon_discount, '0',',','.' )}}</td>
                        <th>Trạng thái đơn hàng</th>
                        <td colspan="5" style="text-align: center;">
                            @if($order->status == 'Chờ Xác Nhận')
                            <span class="badge bg-success">Chờ Xác Nhận</span>
                            @elseif($order->status == 'Đã Xác Nhận')
                            <span class="badge bg-success">Đã Xác Nhận</span>
                            @elseif($order->status == 'Đang Giao')
                            <span class="badge bg-success">Đang Giao</span>
                            @elseif($order->status == 'Đã Nhận')
                            <span class="badge bg-success">Đã Nhận</span>
                            @else
                            <span class="badge bg-danger">Đã Hủy</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Thành tiền</th>
                        <td>{{number_format( $order->suptotal,'0',',','.' )}} VND </td>
                        <th>Phương thức thanh toán</th>
                        <td>{{ $order->payment_method }}</td>
                        <th>Trạng thái thanh toán</th>
                        <td colspan="2" style="text-align: center;">
                            @if($order->payment_status == 'Chưa Thanh Toán')
                            <span class="badge bg-success">Chưa Thanh Toán</span>
                            @elseif($order->payment_status == 'Đã Thanh Toán')
                            <span class="badge bg-success">Đã Thanh Toán</span>

                            @endif
                        </td>
                        <th>Ngày đặt hàng</th>
                        <td>{{ $order->order_date }}</td>




                    </tr>
                </tbody>
                </table>
                </div>


                <div class="wg-box mt-5">
                        <h5>Cập nhật trạng thái đơn hàng </h5>
                        {{-- @if ($order->status != "Đã Hủy")
                            <form action="{{ route('admin.order.status.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $order ->id}}"> 
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="select">
                                            <select id="status" name="status">
                                                <option value="Đã Xác Nhận" {{ $order ->status == 'Đã Xác Nhận' ? "selected" :"" }}> Đã Xác Nhận</option> 
                                                <option value="Đang Giao" {{ $order ->status == 'Đang Giao' ? "selected" :"" }}> Đang Giao </option> 
                                                <option value="Đã Giao" {{ $order ->status == 'Đã Giao' ? "selected" :"" }}> Đã Giao </option> 
                                                <option value="Đã Hủy" {{ $order ->status == 'Đã Hủy' ? "selected" :"" }}> Đã Hủy</option> 
                                            </select>
                                        </div>
                                    </div> 
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary tf-button w208"> Thay Đổi Trạng Thái</button>
                                        </div>


                                </div>
                            </form>
                        @else
                            <p class="text-danger mt-3">Đơn hàng đã bị hủy. Không thể cập nhật trạng thái.</p>
                        @endif --}}
                            
                        <form id="orderStatusForm" action="{{ route('admin.order.status.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" value="{{ $order->id }}">
                            <input type="hidden" name="status" id="statusInput">


                            @switch($order->status)
                                @case('Chờ Xác Nhận')
                                    <button type="button" onclick="confirmStatusChange('Đã Xác Nhận')" class="btn btn-success tf-button w208 " >Xác Nhận Đơn</button>
                                    <button type="button" onclick="confirmStatusChange('Đã Hủy')" class="btn btn-lg btn-danger">Hủy Đơn</button>
                                    @break

                                @case('Đã Xác Nhận')
                                    <button type="button" onclick="confirmStatusChange('Đang Giao')" class="btn btn-primary tf-button w208">Bắt Đầu Giao</button>
                                    {{-- <button type="button" onclick="confirmStatusChange('Đã Hủy')" class="btn btn-danger">Hủy Đơn</button> --}}
                                    @break

                                @case('Đang Giao')
                                    <button type="button" onclick="confirmStatusChange('Đã Giao')" class="btn btn-info tf-button w208">Xác Nhận Giao Thành Công</button>
                                    {{-- <button type="button" onclick="confirmStatusChange('Đã Hủy')" class="btn btn-danger">Hủy Đơn</button> --}}
                                    @break

                                @case('Đã Giao')
                                @case('Đã Hủy')
                                    <div class="alert alert-secondary">Đơn hàng đang ở trạng thái {{ $order->status }}. Không thể thay đổi trạng thái.</div>
                                    @break
                            @endswitch
                        </form>

                    </table>

            </div>

        </div>
    </div>
</div>
@endsection