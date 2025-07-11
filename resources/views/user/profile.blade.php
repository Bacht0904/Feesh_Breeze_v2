@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
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
    <div class="modal fade" id="modalProfile" tabindex="-1" aria-labelledby="modalProfileLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf


                    <div class="modal-header bg-light border-0 rounded-top-3">
                        <h5 class="modal-title">Cập nhật thông tin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>

                    <div class="modal-body px-4 py-3">
                        <div class="row g-3">
                            @foreach (['name' => 'Họ và tên', 'email' => 'Email', 'phone' => 'Số điện thoại'] as $field => $label)
                            <div class="col-md-6 form-floating">
                                <input type="{{ $field === 'email' ? 'email' : 'text' }}"
                                    class="form-control"
                                    id="{{ $field }}"
                                    name="{{ $field }}"
                                    value="{{ Auth::user()->$field }}"
                                    required>
                                <label for="{{ $field }}">{{ $label }}</label>
                            </div>
                            @endforeach

                            {{-- Địa chỉ cụ thể --}}
                            <div class="col-12 form-floating">
                                <input type="text"
                                    class="form-control"
                                    id="address_detail"
                                    placeholder="Số nhà, tên đường...">
                                <label for="address_detail">Địa chỉ cụ thể</label>
                            </div>

                            {{-- Ô ẩn lưu toàn bộ --}}
                            <input type="hidden" id="address" name="address" value="{{ Auth::user()->address }}">

                            {{-- Dropdown địa phương --}}
                            <div class="col-md-4">
                                <label class="form-label">Tỉnh/Thành phố</label>
                                <select class="form-select" id="city">
                                    <option value="">Chọn tỉnh thành</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Quận/Huyện</label>
                                <select class="form-select" id="district">
                                    <option value="">Chọn quận huyện</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Phường/Xã</label>
                                <select class="form-select" id="ward">
                                    <option value="">Chọn phường xã</option>
                                </select>
                            </div>

                            {{-- Xác thực mật khẩu --}}
                            <div class="col-12 form-floating">
                                <input type="password"
                                    class="form-control"
                                    id="current_password"
                                    name="current_password"
                                    placeholder="••••••••"
                                    required>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js">
    </script>
    <script>
        const citis = document.getElementById("city");
        const districts = document.getElementById("district");
        const wards = document.getElementById("ward");
        const address = document.getElementById("address");
        const addressDetail = document.getElementById("address_detail");

        axios.get("https://raw.githubusercontent.com/kenzouno1/DiaGioiHanhChinhVN/master/data.json").then(res => {
            const data = res.data;

            data.forEach(city => citis.add(new Option(city.Name, city.Id)));

            citis.onchange = function() {
                districts.length = 1;
                wards.length = 1;
                const selectedCity = data.find(c => c.Id === this.value);
                selectedCity?.Districts.forEach(d => {
                    districts.add(new Option(d.Name, d.Id));
                });
                updateAddress();
            };

            districts.onchange = function() {
                wards.length = 1;
                const city = data.find(c => c.Id === citis.value);
                const district = city?.Districts.find(d => d.Id === this.value);
                district?.Wards.forEach(w => {
                    wards.add(new Option(w.Name, w.Id));
                });
                updateAddress();
            };

            wards.onchange = updateAddress;
            addressDetail.oninput = updateAddress;

            function updateAddress() {
                const detail = addressDetail.value.trim();
                const ward = wards.options[wards.selectedIndex]?.text || '';
                const district = districts.options[districts.selectedIndex]?.text || '';
                const city = citis.options[citis.selectedIndex]?.text || '';

                const parts = [detail, ward, district, city].filter(Boolean);
                address.value = parts.join(', ');
            }
        });
    </script>


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