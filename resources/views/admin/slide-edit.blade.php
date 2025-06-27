@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Chỉnh sửa sản phẩm</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Trang chủ</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('admin.sliders') }}">
                            <div class="text-tiny">Slider</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Sửa Slider</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <form class="form-new-slide form-style-1" method="POST" enctype="multipart/form-data"
                    action="{{ route('admin.slide.update', $slide->id) }}">
                    @csrf
                    @method('PUT')
                    <fieldset class="name">
                        <div class="body-title">Tiêu đề <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Nhập tiêu đề" name="title" tabindex="0"
                            value="{{ $slide->title }}" aria-required="true" required="">
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
                                    <span class="body-text">Drop your images here or select <span class="tf-color">click
                                            to
                                            browse</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('image') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                    <fieldset class="name">
                        <div class="body-title">Ghi chú<span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Nhập ghi chú" name="description" tabindex="0"
                            value="{{ $slide->description }}" aria-required="true" required="">
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Link<span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Nhập link" name="link" tabindex="0"
                            value="{{ $slide->link }}" aria-required="true" required="">
                    </fieldset>


                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Sửa slide</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection