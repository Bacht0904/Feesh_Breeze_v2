@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Thông tin loại sản phẩm</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{route('admin.index')}}">
                            <div class="text-tiny">Trang chủ</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('admin.categories') }}">
                            <div class="text-tiny">Loại sản phẩm</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Thêm loại sản phẩm</div>
                    </li>
                </ul>
            </div>
            <!-- new-category -->
            <div class="wg-box">
                <form method="POST" class="form-new-product form-style-1" action="{{ route('admin.category.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <fieldset class="name">
                        <div class="body-title">Tên loại sản phẩm <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Category name" name="name" tabindex="0"
                            value="{{ old('name') }}" aria-required="true" required="">
                    </fieldset>
                    @error('name') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                    <fieldset class="name">
                        <div class="body-title">Slug<span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Category Slug" name="slug" tabindex="0"
                            value="{{ old('slug') }}" aria-required="true" required="">
                    </fieldset>
                    @error('slug') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror

                    <div class="bot">
                        <div></div>
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
        $(function () {
            $("#myFile").on("change", function (e) {
                const [file] = this.files;
                if (file) {
                    $("#imgpreview").attr('src', URL.createObjectURL(file));
                    $("#imgpreviewBox").show();
                }
            });


            $("input[name='name']").on("change", function () {
                $("input[name='slug']").val(StringToSlug($(this).val()));
            });
        });

        function StringToSlug(Text) {
            return Text.toLowerCase()
                .replace(/[^\w ]+/g, "")
                .replace(/ +/g, "-");
        };
    </script>
@endpush