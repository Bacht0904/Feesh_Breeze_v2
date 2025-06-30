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
                    action="{{ route('admin.user.store') }}">
                    @csrf

                    <fieldset class="name">
                        <div class="body-title mb-10">Họ tên<span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" name="name" placeholder="Nhập tên người dùng"
                            value="{{ old('name') }}" required>
                        <div class="text-tiny">Không được nhập tên sản phẩm quá 100 ký tự.</div>
                    </fieldset>

                    <fieldset class="email">
                        <div class="body-title mb-10">Email<span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" name="email" placeholder="Nhập email" value="{{ old('email') }}"
                            required>
                    </fieldset>
                    <fieldset class="password">
                        <div class="body-title mb-10">Mật khẩu<span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="password" name="password" placeholder="Nhập mật khẩu"
                            value="{{ old('password') }}" required>
                    </fieldset>
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <fieldset class="password">
                        <div class="body-title mb-10">Xác nhận mật khẩu<span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu"
                            required>
                    </fieldset>
                    @error('password_confirmation')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <fieldset class="phone">
                        <div class="body-title mb-10">Số điện thoại <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" name="phone" placeholder="Nhập số điện thoại"
                            value="{{ old('phone') }}" required>
                    </fieldset>
                    <fieldset class="address">
                        <div class="body-title mb-10">Địa chỉ <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" name="address" placeholder="Nhập địa chỉ"
                            value="{{old('address')}}" required>
                    </fieldset>
                    <fieldset>
                        <div class="body-title">Tải hình ảnh lên <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">
                                        Drop your images here or select
                                        <span class="tf-color">click to browse</span>
                                    </span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
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
                        <button class="tf-button w-full" type="submit">Thêm người dùng</button>
                    </div>


                </form>
            </div>
        </div>
    </div>

@endsection