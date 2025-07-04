@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Banner</h3>
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
                        <a href="{{ route('admin.banners') }}">
                            <div class="text-tiny">Banner</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Thêm banner mới</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <form method="POST" class="form-new-product form-style-1" enctype="multipart/form-data"
                    action="{{ route('admin.banner.store') }}">
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

                    <fieldset class="brand">
                            <div class="body-title mb-10">Thương hiệu<span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select name="brand_id" required>
                                    <option disabled selected>Chọn thương hiệu</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>

                    <!-- Ghi chú -->
                    <fieldset class="name">
                        <div class="body-title">Ghi chú <span class="tf-color-1">*</span></div>
                        <textarea id="note" name="description" required placeholder="Nhập ghi chú..."
                            class="form-control"></textarea>
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

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#note').summernote({
                placeholder: "Nhập ghi chú ngắn...",
                tabsize: 2,
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endpush

@push('styles')
<style>
    /* Căn chiều ngang của Summernote bằng input tiêu đề */
    #note,
    .note-editor,
    .note-editor .note-editing-area,
    .note-editor .note-editable {
        width: 100% !important;
        box-sizing: border-box;
    }

    /* Font và padding vùng soạn thảo */
    .note-editor .note-editable {
        font-size: 14px;
        padding: 10px 12px;
    }

    /* 📏 Icon trong toolbar to 1.3 lần */
    .note-editor .note-toolbar .note-icon {
        font-size: 1.3rem !important;
    }

    /* Tuỳ chọn: tăng nhẹ kích thước nút cho cân đối */
    .note-editor .note-toolbar .note-btn,
    .note-editor .note-toolbar .btn {
        font-size: 1.1rem !important;
        padding: 7px 10px !important;
    }
</style>
@endpush
