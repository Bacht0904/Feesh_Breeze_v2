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
                        <div class="body-title">Ghi chú <span class="tf-color-1">*</span></div>
                        <textarea id="note" class="flex-grow form-control" name="description" required
                            placeholder="Nhập ghi chú...">{{ $slide->description }}</textarea>
                    </fieldset>



                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Sửa slide</button>
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