@php $user = $user ?? Auth::user(); @endphp
@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Cài đặt</h3>
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
                        <div class="text-tiny">Cài đặt</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="col-lg-12">
                    <div class="page-content my-account__edit">
                        <div class="my-account__edit-form">
                            <form name="account_edit_form" action="{{ route('admin.setting', Auth::id()) }}" method="POST"
                                class="form-new-product form-style-1 needs-validation" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <fieldset class="name">
                                    <div class="body-title">Tên<span class="tf-color-1">*</span></div>
                                    <input class="flex-grow" type="text" placeholder="Họ tên" name="name" tabindex="0"
                                        value="{{ Auth::user()->name }}" aria-required="true" required>
                                </fieldset>

                                <fieldset class="phone">
                                    <div class="body-title">Số điện thoại<span class="tf-color-1">*</span></div>
                                    <input class="flex-grow" type="text" placeholder="Số điện thoại" name="phone"
                                        tabindex="0" value="{{ Auth::user()->phone }}" aria-required="true" required>
                                </fieldset>

                                <fieldset class="email">
                                    <div class="body-title">Email<span class="tf-color-1">*</span></div>
                                    <input class="flex-grow" type="email" placeholder="Địa chỉ Email" name="email"
                                        tabindex="0" value="{{ Auth::user()->email }}" aria-required="true" required>
                                </fieldset>

                                <fieldset class="address">
                                    <div class="body-title">Địa chỉ<span class="tf-color-1">*</span></div>
                                    <input class="flex-grow" type="text" placeholder="Địa chỉ" name="address" tabindex="0"
                                        value="{{ Auth::user()->address }}" aria-required="true" required>
                                </fieldset>

                                <fieldset>
                                    <div class="body-title">Tải hình ảnh lên <span class="tf-color-1">*</span>
                                    </div>
                                    <div class="upload-image flex-grow">
                                        <div class="item" id="imgpreview" style="display:none">
                                            <img src="upload-1.html" class="effect8" alt="">
                                        </div>
                                        <div id="upload-file" class="item up-load">
                                            <label class="uploadfile" for="myFile">
                                                <span class="icon">
                                                    <i class="icon-upload-cloud"></i>
                                                </span>
                                                <span class="body-text">Drop your images here or select <span
                                                        class="tf-color">click
                                                        to
                                                        browse</span></span>
                                                <input type="file" id="myFile" name="image" accept="image/*">
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>



                                <div class="row">
                                    <div class="row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <a class="btn btn-link" href="{{ route('admin.password.change') }}"
                                                style="font-size: 1.5em;">
                                                {{ __('Đổi mật khẩu?') }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <div class="my-3">
                                            <button type="submit" class="btn btn-primary tf-button w208">Lưu thay
                                                đổi</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection