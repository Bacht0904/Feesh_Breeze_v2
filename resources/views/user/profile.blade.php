@extends('layouts.app')
@section('content')
<main class="pt-90">
    <section class="my-account container">

        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 mb-4">
                <ul class="account-nav list-unstyled">
                    <li><a href="{{ route('wishlist') }}" class="menu-link menu-link_us-s">Yêu Thích</a></li>
                    <li><a href="{{ route('cart') }}" class="menu-link menu-link_us-s">Giỏ Hàng</a></li>
                    <li><a href="{{ route('orders.index') }}" class="menu-link menu-link_us-s">Đơn Hàng</a></li>
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Đăng xuất
                            </a>
                        </form>
                    </li>
                </ul>
            </div>

            <!-- Main content -->
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="mb-4">Thông Tin Tài Khoản</h3>
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <div class="position-relative">
                                <img src="{{ Auth::user()->avatar && file_exists(public_path(Auth::user()->avatar))
        ? asset(Auth::user()->avatar)
        : asset('images/default-avatar.png') }}"
                                    alt="Avatar"
                                    class="rounded-circle border"
                                    style="width: 120px; height: 120px; object-fit: cover;">
                            </div>

                            <div>
                                <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                                <p class="mb-1 text-muted">Email: {{ Auth::user()->email }}</p>
                                <p class="mb-1 text-muted">Số điện thoại: {{ Auth::user()->phone ?? 'Chưa cập nhật' }}</p>
                                <p class="mb-1 text-muted">Địa chỉ: {{ Auth::user()->address ?? 'Chưa cập nhật' }}</p>
                                <p class="text-muted">Tham gia: {{ Auth::user()->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProfile">
                            Cập nhật thông tin
                        </button>
                        <button class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#modalAvatar">
                            Thay ảnh
                        </button>
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalChangePassword">
                            Đổi mật khẩu
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal: Cập nhật thông tin -->
    <!-- Modal: Cập nhật thông tin cá nhân -->
    <div class="modal fade" id="modalProfile" tabindex="-1" aria-labelledby="modalProfileLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-light border-0 rounded-top-3">
                        <h5 class="modal-title">Cập nhật thông tin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body px-4 py-3">
                        <div class="row g-3">
                            @foreach (['name' => 'Họ và tên', 'email' => 'Email', 'phone' => 'Số điện thoại', 'address' => 'Địa chỉ'] as $field => $label)
                            <div class="col-md-6 form-floating">
                                <input type="{{ $field === 'email' ? 'email' : 'text' }}" class="form-control" id="{{ $field }}" name="{{ $field }}" value="{{ Auth::user()->$field }}" required>
                                <label for="{{ $field }}">{{ $label }}</label>
                            </div>
                            @endforeach
                            <div class="col-12 form-floating">
                                <input type="password" class="form-control" id="current_password" name="current_password" required placeholder="••••••••">
                                <label for="current_password">Nhập mật khẩu hiện tại để xác nhận</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer px-4 py-3 border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Đổi mật khẩu -->
    <div class="modal fade" id="modalChangePassword" tabindex="-1" aria-labelledby="modalChangePasswordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-light border-0 rounded-top-3">
                        <h5 class="modal-title">Đổi mật khẩu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body px-4 py-3">
                        @foreach (['current_password' => 'Mật khẩu hiện tại', 'password' => 'Mật khẩu mới', 'password_confirmation' => 'Xác nhận mật khẩu'] as $name => $label)
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="{{ $name }}" name="{{ $name }}" required>
                            <label for="{{ $name }}">{{ $label }}</label>
                        </div>
                        @endforeach
                    </div>
                    <div class="modal-footer px-4 py-3 border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Thay ảnh đại diện -->
    <div class="modal fade" id="modalAvatar" tabindex="-1" aria-labelledby="modalAvatarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-light border-0 rounded-top-3">
                        <h5 class="modal-title">Thay đổi ảnh đại diện</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body px-4 py-4">
                        <div class="mb-3">
                            <label for="avatar" class="form-label">Chọn ảnh (JPEG, PNG, GIF, tối đa 2MB)</label>
                            <input type="file" name="avatar" accept="image/*" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer px-4 py-3 border-0">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>
@endsection