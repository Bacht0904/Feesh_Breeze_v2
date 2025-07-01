@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Đổi mật khẩu</h3>
            </div>

            <div class="wg-box">
                <div class="col-lg-12">
                    <div class="page-content my-account__edit">
                        <div class="my-account__edit-form">
                            <form name="account_edit_form" action="{{ route('auth.password.update') }}" method="POST"
                                class="form-new-product form-style-1 needs-validation">
                                @csrf
                                <fieldset class="name">
                                    <div class="body-title">Mật khẩu hiện tại<span class="tf-color-1">*</span>
                                    </div>
                                    <input class="flex-grow" type="password" placeholder="Mật khẩu hiện tại"
                                        name="current_password" tabindex="0" value="" aria-required="true" required="">
                                </fieldset>

                                <fieldset class="name">
                                    <div class="body-title">Mật khẩu mới<span class="tf-color-1">*</span></div>
                                    <input class="flex-grow" type="password" placeholder="Mật khẩu mới" name="new_password"
                                        tabindex="0" value="" aria-required="true" required="">
                                </fieldset>

                                <fieldset class="name">
                                    <div class="body-title">Xác nhận mật khẩu mới<span class="tf-color-1">*</span></div>
                                    <input class="flex-grow" type="password" placeholder="Xác nhận mật khẩu mới"
                                        name="new_password_confirmation" tabindex="0" value="" aria-required="true"
                                        required="">
                                </fieldset>

                                <div class="row">
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