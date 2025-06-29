@extends('layouts.app')

@section('content')
<main class="pt-90">
    <section class="container my-account-orders">
        <h2 class="page-title mb-4">Lịch sử đơn hàng</h2>
        <div class="row">

            {{-- Sidebar --}}
            <div class="col-lg-3">
                <ul class="account-nav list-group">
                    @foreach([
                    ['url' => 'account-dashboard', 'label' => 'Dashboard'],
                    ['url' => 'account-orders', 'label' => 'Orders', 'active' => true],
                    ['url' => 'account-addresses', 'label' => 'Addresses'],
                    ['url' => 'account-details', 'label' => 'Account Details'],
                    ['url' => 'account-wishlists', 'label' => 'Wishlist']
                    ] as $item)
                    <li class="list-group-item {{ $item['active'] ?? false ? 'active' : '' }}">
                        <a href="{{ url($item['url']) }}" class="menu-link">{{ $item['label'] }}</a>
                    </li>
                    @endforeach
                    <li class="list-group-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="menu-link">Logout</a>
                        </form>
                    </li>
                </ul>
            </div>

            {{-- Order Table --}}
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header bg-light"><strong>🧾 Đơn hàng của bạn</strong></div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Khách hàng</th>
                                    <th>Điện thoại</th>
                                    <th>Tạm tính</th>
                                    <th>Thuế</th>
                                    <th>Tổng</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt</th>
                                    <th>SL SP</th>
                                    <th>Giao hàng</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders ?? [] as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $order->phone }}</td>
                                    <td>${{ number_format($order->subtotal, 2) }}</td>
                                    <td>${{ number_format($order->tax, 2) }}</td>
                                    <td>${{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'Canceled' ? 'danger' : ($order->status === 'Ordered' ? 'warning' : 'success') }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $order->items_count }}</td>
                                    <td>{{ $order->delivered_at ?? '--' }}</td>
                                    <td>
                                        <a href="{{ route('orders.details', $order->id) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted">Bạn chưa có đơn hàng nào.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination nếu có --}}
                <div class="mt-3">
                    {{ $orders->links() ?? '' }}
                </div>
            </div>

        </div>
    </section>
</main>
@endsection