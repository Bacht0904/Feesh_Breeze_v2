@extends('layouts.app')

@section('content')
<style>
    .table> :not(caption)>tr>th {
        padding: 0.625rem 1.5rem !important;
        background-color: #6a6e51 !important;
        color: white;
    }

    .table> :not(caption)>tr>td {
        padding: 0.8rem 1rem !important;
    }

    .table-bordered> :not(caption)>tr>th,
    .table-bordered> :not(caption)>tr>td {
        border-width: 1px 1px;
        border-color: #6a6e51;
    }

    .bg-success {
        background-color: #40c710 !important;
    }

    .bg-danger {
        background-color: #f44032 !important;
    }

    .bg-warning {
        background-color: #f5d700 !important;
        color: #000;
    }
</style>

<main class="pt-90">
    <section class="my-account container">
        <h2 class="page-title mb-4">Đơn hàng</h2>
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-lg-2">
                <ul class="account-nav">
                    <!-- <li><a href="my-account.html" class="menu-link menu-link_us-s">Dashboard</a></li> -->

                    <li><a href="{{ route('wishlist') }}" class="menu-link menu-link_us-s">Yêu Thích</a></li>
                    <li><a href="{{ route('cart') }}" class="menu-link menu-link_us-s">Giỏ Hàng</a></li>
                    <li><a href="{{ route('orders.index') }}" class="menu-link menu-link_us-s">Đơn Hàng</a></li>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Đăng xuất
                    </a>

                </ul>
            </div>

            {{-- Bảng đơn hàng --}}
            <div class="col-lg-10">
                <div class="wg-table table-all-user">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Mã đơn</th>
                                    <th class="text-center">Người nhận</th>
                                    <th class="text-center">Số điện thoại</th>
                                    <th class="text-center">Tạm tính</th>
                                    <th class="text-center">Tổng tiền</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Ngày đặt</th>
                                    <th class="text-center">Sản phẩm</th>
                                    <th class="text-center">Ngày giao</th>
                                    <th class="text-center">Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)

                                <tr>
                                    <td class="text-center">{{ $order->id }}</td>
                                    <td class="text-center">{{ $order->name }}</td>
                                    <td class="text-center">{{ $order->phone }}</td>
                                    <td class="text-center">{{ number_format($order->suptotal, 0, ',', '.') }}₫</td>
                                    <td class="text-center">{{ number_format($order->total, 0, ',', '.') }}₫</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $order->status === 'Canceled' ? 'danger' : ($order->status === 'Ordered' ? 'warning' : 'success') }}">
                                            {{ $order->status === 'Canceled' ? 'Đã hủy' : ($order->status === 'Ordered' ? 'Chờ xác nhận' : 'Hoàn tất') }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">{{ $order->details->count() }}</td>
                                    <td class="text-center">{{ $order->delivered_at ? \Carbon\Carbon::parse($order->delivered_at)->format('d/m/Y') : '--' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('orders.details', $order->id) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-4">Không có đơn hàng nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Phân trang --}}
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </section>
</main>
@endsection