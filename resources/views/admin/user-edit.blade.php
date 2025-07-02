@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Chỉnh sửa thông tin người dùng</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Trang chủ</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('admin.users') }}">
                            <div class="text-tiny">Người dùng</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Sửa thông tin người dùng</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <form class="flex items-center justify-between gap10 flex-wrap" method="POST" enctype="multipart/form-data"
                    action="{{ route('admin.user.update', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <fieldset class="name">
                        <div class="body-title mb-10">Họ tên<span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" name="name" placeholder="Nhập tên người dùng"
                            value="{{ $user->name }}" required>
                        <div class="text-tiny">Không được nhập tên sản phẩm quá 100 ký tự.</div>
                    </fieldset>

                    <fieldset class="email">
                        <div class="body-title mb-10">Email<span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" name="email" placeholder="Nhập email" value="{{ $user->email }}"
                            required>
                    </fieldset>
                    <fieldset class="user-role">
                        <div class="body-title mb-10">Vai trò người dùng <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="role" required>
                                <option disabled selected>Chọn vai trò</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                                <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Nhân viên</option>
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Người dùng</option>
                            </select>
                        </div>
                    </fieldset>


                    <fieldset class="user-status">
                        <div class="body-title mb-10">Trạng thái tài khoản <span class="tf-color-1">*</span></div>
                        <div class="select">
                            <select name="status" required>
                                <option disabled selected>Chọn trạng thái</option>
                                <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Không hoạt động
                                </option>
                            </select>
                        </div>
                    </fieldset>
                    <fieldset class="phone">
                        <div class="body-title mb-10">Số điện thoại <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" name="phone" placeholder="Nhập số điện thoại"
                            value="{{ $user->phone }}" required>
                    </fieldset>
                    <fieldset class="address">
                        <div class="body-title mb-10">Địa chỉ <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" name="address" placeholder="Nhập địa chỉ"
                            value="{{ $user->address ?? '' }}" required>
                    </fieldset>
                    <fieldset class="user-avatar">
                        <div class="body-title mb-10">Ảnh đại diện <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="{{ $user->avatar ? '' : 'display:none' }}">
                                <img src="{{ asset('uploads/avatar/' . $user->avatar) }}" class="effect8"
                                    alt="Avatar hiện tại">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="avatar">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">
                                        Chọn ảnh đại diện từ máy tính <span class="tf-color">hoặc kéo thả</span>
                                    </span>
                                    <input type="file" id="avatar" name="avatar" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Sửa thông tin</button>
                    </div>


                </form>
            </div>
        </div>
    </div>

@endsection