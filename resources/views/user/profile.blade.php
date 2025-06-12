@extends('layouts.app')
#@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title"></h2>
        <div class="row">
            <div class="col-lg-3">
                <ul class="account-nav">
                    <!-- <li><a href="my-account.html" class="menu-link menu-link_us-s">Dashboard</a></li> -->
                    <li><a href="account-orders.html" class="menu-link menu-link_us-s">Đơn Hàng</a></li>
                    <li><a href="account-address.html" class="menu-link menu-link_us-s">Địa Chỉ</a></li>
                    <li><a href="account-details.html" class="menu-link menu-link_us-s">Tài Khoản</a></li>
                    <li><a href="account-wishlist.html" class="menu-link menu-link_us-s">Yêu Thích</a></li>
                    <li><a href="{{route('logout')}}" class="menu-link menu-link_us-s">Đăng Xuất</a></li>
                </ul>
            </div>
            <div class="col-lg-9">
                <div class="page-content my-account__dashboard">
                    <section class="user-profile container">
                        <h2 class="page-title">Thông Tin Tài Khoản</h2>
                        <div class="profile-details">
                            <div class="profile-picture">
                                <img src="{{ asset('images/profile/' . Auth::user()->profile_picture) }}" alt="Profile Picture" />
                            </div>
                            <div class="profile-info">
                                <h3>{{ Auth::user()->name }}</h3>
                                <p>Email: {{ Auth::user()->email }}</p>
                                <p>Joined: {{ Auth::user()->created_at->format('d M Y') }}</p>
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