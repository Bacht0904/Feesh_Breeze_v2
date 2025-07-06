@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title"></h2>
            <div class="row">
                <div class="col-lg-3">
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
                <div class="col-lg-9">
                    <div class="page-content my-account__dashboard">
                        <section class="user-profile container">
                            <h2 class="page-title">Thông Tin Tài Khoản</h2>
                            <div class="profile-details">
                                <div class="profile-picture">
                                    <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar" class="rounded-circle" width="50">
                                </div>
                                <div class="profile-info">
                                    <h3>{{ Auth::user()->name }}</h3>
                                    <p>Email: {{ Auth::user()->email }}</p>
                                    <p>Số điện thoại: {{ Auth::user()->phone ?? 'Chưa có thông tin' }}</p>
                                    <p>Địa chỉ: {{ Auth::user()->address ?? 'Chưa có thông tin' }}</p>
                                    <p>Ngày gia nhập: {{ Auth::user()->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <!-- <div class="profile-actions">
                                <a href="#" class="btn btn-primary">Edit Profile</a>
                                <a href="#" class="btn btn-secondary">View Orders</a>
                            </div> -->
                        </section>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection