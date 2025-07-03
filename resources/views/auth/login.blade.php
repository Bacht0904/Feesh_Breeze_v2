@extends('layouts.app')

@section('content')

<main class="pt-90">

    <div class="mb-4 pb-4"></div>
    <section class="login-register container">
        <ul class="nav nav-tabs mb-5" id="login_register" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link nav-link_underscore active" id="login-tab" data-bs-toggle="tab" href="#tab-item-login"
                    role="tab" aria-controls="tab-item-login" aria-selected="true">ĐĂNG NHẬP</a>
            </li>
        </ul>
        <div class="tab-content pt-2" id="login_register_tab_content">
            <div class="tab-pane fade show active" id="tab-item-login" role="tabpanel" aria-labelledby="login-tab">
                <div class="login-form">
                    <form method="POST" action="{{route('login')}}" name="login-form" class="needs-validation" novalidate="">
                        @csrf
                         <div class="container mt-3">
                                @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                                @endif

                                @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                                @endif
                            </div>
                        <div class="form-floating mb-3">
                            <input class="form-control form-control_gray " name="email" value="" required="" autocomplete="email"
                                autofocus="">
                            <label for="email">{{('Email *') }}</label>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="pb-3"></div>

                        <div class="form-floating mb-3">
                            <input id="password" type="password" class="form-control form-control_gray " name="password" required=""
                                autocomplete="current-password">
                            <label for="customerPasswodInput">Mật khẩu *</label>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-floating mb-3">
                            <button class="btn btn-primary w-100 text-uppercase" type="submit">Đăng Nhập</button>
                            @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Quên Mật Khẩu?') }}
                            </a>
                            @endif
                            <div class="customer-option mt-4 text-center">
                                <span class="text-secondary">Chưa có tài khoản?</span>
                                <a href="{{ route('register') }}" class="btn-text js-show-register">Tạo tài khoản</a> 
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
