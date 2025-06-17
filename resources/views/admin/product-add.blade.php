@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Add Product</h3>
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
                        <a href="{{ route('admin.products') }}">
                            <div class="text-tiny">Sản phẩm</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Thêm sản phẩm</div>
                    </li>
                </ul>
            </div>
            <!-- form-add-product -->
            <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data"
                action="http://localhost:8000/admin/product/store">
                <input type="hidden" name="_token" value="8LNRTO4LPXHvbK2vgRcXqMeLgqtqNGjzWSNru7Xx" autocomplete="off">
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Tên sản phẩm<span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Nhập tên sản phẩm" name="name" tabindex="0" value=""
                            aria-required="true" required="">
                        <div class="text-tiny">Không được nhập tên sản phẩm quá 100 ký tự.</div>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title mb-10">Mã loại sản phẩm(Slug)<span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Nhập mã sản phẩm" name="slug" tabindex="0" value=""
                            aria-required="true" required="">
                        <div class="text-tiny">Không được nhập tên sản phẩm quá 100 ký tự.</div>
                    </fieldset>

                    <div class="gap22 cols">
                        <fieldset class="category">
                            <div class="body-title mb-10">Loại sản phẩm<span class="tf-color-1">*</span>
                            </div>
                            <div class="select">
                                <select class="" name="category_id">
                                    <option>Chọn loại sản phẩm</option>
                                    <option value="1">Loại sản phẩm 1</option>
                                    <option value="2">Loại sản phẩm 2</option>
                                    <option value="3">Loại sản phẩm 3</option>
                                    <option value="4">Loại sản phẩm 4</option>

                                </select>
                            </div>
                        </fieldset>
                        <fieldset class="brand">
                            <div class="body-title mb-10">Thương hiệu<span class="tf-color-1">*</span>
                            </div>
                            <div class="select">
                                <select class="" name="brand_id">
                                    <option>Chọn thương hiệu</option>
                                    <option value="1">Thương hiệu 1</option>
                                    <option value="2">Thương hiệu 2</option>
                                    <option value="3">Thương hiệu 3</option>
                                    <option value="4">Thương hiệu 4</option>

                                </select>
                            </div>
                        </fieldset>
                    </div>

                    <fieldset class="shortdescription">
                        <div class="body-title mb-10">Mô tả ngắn<span class="tf-color-1">*</span></div>
                        <textarea class="mb-10 ht-150" name="short_description" placeholder="Nhập mô tả" tabindex="0"
                            aria-required="true" required=""></textarea>
                        <div class="text-tiny">Không được nhập quá 100 ký tự</div>
                    </fieldset>

                    <fieldset class="description">
                        <div class="body-title mb-10">Chi tiết mô tả<span class="tf-color-1">*</span>
                        </div>
                        <textarea class="mb-10" name="description" placeholder="Nhập chi tiết mô tả" tabindex="0"
                            aria-required="true" required=""></textarea>
                    </fieldset>
                </div>
                <div class="wg-box">
                    <fieldset>
                        <div class="body-title">Tải lên hình ảnh<span class="tf-color-1">*</span>
                        </div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="display:none">
                                <img src="../../../localhost_8000/images/upload/upload-1.png" class="effect8" alt="">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Drop your images here or select <span class="tf-color">click to
                                            browse</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="body-title mb-10">Tải lên hình ảnh thư viện</div>
                        <div class="upload-image mb-16">
                            <!-- <div class="item">
                                    <img src="images/upload/upload-1.png" alt="">
                                </div>                                                 -->
                            <div id="galUpload" class="item up-load">
                                <label class="uploadfile" for="gFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="text-tiny">Drop your images here or select <span class="tf-color">click to
                                            browse</span></span>
                                    <input type="file" id="gFile" name="images[]" accept="image/*" multiple="">
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Giá thông thường<span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Nhập giá thông thường" name="regular_price"
                                tabindex="0" value="" aria-required="true" required="">
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title mb-10">Giá bán<span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Nhập giá bán" name="sale_price" tabindex="0"
                                value="" aria-required="true" required="">
                        </fieldset>
                    </div>


                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Mã sản phẩm<span class="tf-color-1">*</span>
                            </div>
                            <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU" tabindex="0" value=""
                                aria-required="true" required="">
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title mb-10">Số lượng<span class="tf-color-1">*</span>
                            </div>
                            <input class="mb-10" type="text" placeholder="Nhập số lượng" name="quantity" tabindex="0"
                                value="" aria-required="true" required="">
                        </fieldset>
                    </div>
                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">TThêm sản phẩm</button>
                    </div>
                </div>
            </form>
            <!-- /form-add-product -->
        </div>
        <!-- /main-content-wrap -->
    </div>
@endsection