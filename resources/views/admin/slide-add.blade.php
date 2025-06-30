@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Slide</h3>
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
                        <a href="slider.html">
                            <div class="text-tiny">Slider</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Thêm slide mới</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <form method="POST" class="form-new-product form-style-1" enctype="multipart/form-data"
                    action="{{ route('admin.slide.store') }}">
                    @csrf

                    <!-- Tiêu đề -->
                    <fieldset class="name">
                        <div class="body-title">Tiêu đề <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" name="title" placeholder="Nhập tiêu đề" required>
                    </fieldset>

                    <!-- Hình ảnh -->
                    <fieldset>
                        <div class="body-title">Tải hình ảnh lên <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            <div class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Drop your image here or <span class="tf-color">click to
                                            browse</span></span>
                                    <input type="file" id="myFile" name="image" accept=".jpg,.jpeg,.png" required>
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    <!-- Link -->
                    <fieldset class="name">
                        <div class="body-title">Link <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="url" name="link" placeholder="Nhập link (ví dụ: https://...)"
                            required>
                    </fieldset>

                    <!-- Ghi chú -->
                    <fieldset class="name">
                        <div class="body-title">Ghi chú <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" name="description" placeholder="Nhập ghi chú" required>
                    </fieldset>

                    <div class="bot">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <button class="tf-button w208" type="submit">Lưu</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection