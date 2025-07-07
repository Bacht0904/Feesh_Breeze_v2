@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Thông tin phiếu giảm giá</h3>
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
                        <a href="{{ route('admin.coupons') }}">
                            <div class="text-tiny">Phiếu giảm giá</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Thêm phiếu giảm giá</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <form class="form-new-product form-style-1" method="POST" action="{{ route('admin.coupon.store') }}">
                     @csrf
                    <fieldset class="name">
                        <div class="body-title">Mã giảm giá<span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Nhập mã giảm giá" name="code" tabindex="0"
                            value="" aria-required="true" required="">
                    </fieldset>
                    @error('code')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <fieldset class="category">
                        <div class="body-title">Loại giảm giá</div>
                        <div class="select flex-grow">
                            <select class="" name="type">
                                <option value="">Chọn loại giảm giá</option>
                                <option value="fixed">Số tiền(fixed)</option>
                                <option value="percent">Phần trăm(percent)</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('type')
                        <span class="text-danger">{{$message}}</span>
                    @enderror
                    <fieldset class="name">
                        <div class="body-title">Giá trị giảm<span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Nhập giá trị giảm" name="value" tabindex="0"
                            value="" aria-required="true" required="">
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Số lượng mã<span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Nhập số lượng" name="value" tabindex="0"
                            value="" aria-required="true" required="">
                    </fieldset>
                    <!-- <fieldset class="category">
                        <div class="body-title">Trạng thái</div>
                        <div class="select flex-grow">
                            <select class="" name="status">
                                <option value="">Chọn trạng thái</option>
                                <option value="active">Kích hoạt</option>
                                <option value="inactive">Ngưng kích hoạt</option>
                            </select>
                        </div>
                    </fieldset>
                    @error('status')
                        <span class="text-danger">{{$message}}</span>
                    @enderror -->

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection