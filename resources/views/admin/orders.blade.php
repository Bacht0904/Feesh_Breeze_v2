@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Danh sách hóa đơn</h3>
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
                        <div class="text-tiny">Hóa đơn</div>
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
                    <a class="tf-button style-1 w208" href="{{ route('admin.order.add') }}"><i class="icon-plus"></i>Thêm
                        mới</a>
                </div>
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:70px">#</th>
                                    <th class="text-center">Tên khách hàng</th>
                                    <th class="text-center">Số điện thoại</th>
                                    <th class="text-center">Số lượng sản phẩm</th>
                                    <th class="text-center">Tổng tiền</th>
                                    <th class="text-center">Phương Thức Thanh Toán</th>
                                    <th class="text-center">Trạng Thái Thanh Toán</th>
                                    <th class="text-center">Ngày đặt</th>
                                    <th class="text-center">Địa Chỉ</th>                                   
                                    <th class="text-center">Trạng thái đơn hàng</th>

                                    <th class="text-center">Xem chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order )
                                                                   
                                <tr>
                                    <td class="text-center">{{$order ->id}}</td>
                                    <td class="text-center">{{$order ->name}}</td>
                                    <td class="text-center">{{$order ->phone}}</td>
                                    <td class="text-center">{{$order ->order_items}}</td>
                                    <td class="text-center">{{number_format($order ->suptotal,0,',','.')}} VND</td>
                                    <td class="text-center">{{$order ->payment_method}}</td>
                                    <td class="text-center">{{$order ->payment_status}}</td>
                                    <td class="text-center">{{$order ->order_date}}</td>
                                    <td class="text-center">{{$order ->address}}</td>
                                    <td class="text-center">{{$order ->status}}</td>
                                   
                                    
                                    
                                    <td class="text-center">
                                        <a href="{{ route('admin.order.detail',['id'=>$order->id]) }}">
                                            <div class="list-icon-function view-icon">
                                                <div class="item eye">
                                                    <i class="icon-eye"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

                </div>
            </div>
        </div>
    </div>
@endsection