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
                            <form name="account_edit_form" action="{{ route('admin.user.store') }}" method="POST"
                                class="form-new-product form-style-1 needs-validation" novalidate="">

                                <fieldset class="name">
                                    <div class="body-title">Tên<span class="tf-color-1">*</span>
                                    </div>
                                    <input class="flex-grow" type="text" placeholder="Họ tên" name="name" tabindex="0"
                                        value="" aria-required="true" required="">
                                </fieldset>

                                <fieldset class="name">
                                    <div class="body-title">Số điện thoại<span class="tf-color-1">*</span></div>
                                    <input class="flex-grow" type="text" placeholder="Số điện thoại" name="mobile"
                                        tabindex="0" value="" aria-required="true" required="">
                                </fieldset>

                                <fieldset class="name">
                                    <div class="body-title">Email<span class="tf-color-1">*</span></div>
                                    <input class="flex-grow" type="text" placeholder="Địa chie Email" name="email"
                                        tabindex="0" value="" aria-required="true" required="">
                                </fieldset>

                                <div class="row">
                                    <div class="row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <a class="btn btn-link" href="{{ route('auth.password.change') }}">
                                                {{ __('Đổi mật khẩu?') }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
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