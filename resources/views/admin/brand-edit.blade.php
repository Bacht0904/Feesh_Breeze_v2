@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Thông tin thương hiệu</h3>
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
                        <a href="{{ route('admin.brands') }}">
                            <div class="text-tiny">Thương hiệu</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Chỉnh sửa thương hiệu</div>
                    </li>
                </ul>
            </div>
            <!-- new-category -->
            <div class="wg-box">
                <form method="POST"  class="form-new-product form-style-1" action="{{ route('admin.brand.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value={{ $brand->id }} />
                    <fieldset class="name">
                        <div class="body-title">Tên thương hiệu <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Nhập tên thương hiệu" name="name" tabindex="0" value="{{ $brand->name }}" aria-required="true" required="">
                    </fieldset>
                    @error('name') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                    <fieldset class="name">
                        <div class="body-title">Mã thương hiệu(slug) <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Nhập slug" name="slug" tabindex="0" value="{{ $brand->slug }}" aria-required="true" required="">
                    </fieldset>
                    @error('slug') <span class="alert alert-danger text-center">{{ $message }}</span> @enderror
                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $("#myFile").on("change",function(e) {
                const photoInp = $("#myFile");
                const [file] = this.files;
                if(file){
                    $("#imgpreview").attr('src',URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });

            $("input[name='name']").on("change",function() {
                $("input[name='slug']").val(StringToSlug($(this).val()));
            });
        });

       function StringToSlug(text) {
            const from = "áàảãạăắằẳẵặâấầẩẫậđéèẻẽẹêếềểễệíìỉĩịóòỏõọôốồổỗộơớờởỡợúùủũụưứừửữựýỳỷỹỵ";
            const to = "aaaaaaaaaaaaaaaaadeeeeeeeeeeeiiiiiooooooooooooooooouuuuuuuuuuuyyyyy";

            for (let i = 0; i < from.length; i++) {
                text = text.replace(new RegExp(from[i], "gi"), to[i]);
            }

            return text.toLowerCase()
                .replace(/[^\w\s-]/g, '') // loại bỏ ký tự đặc biệt
                .trim()
                .replace(/\s+/g, '-')     // thay khoảng trắng bằng dấu gạch ngang
                .replace(/-+/g, '-');     // loại bỏ dấu gạch ngang lặp
        }
    </script>
@endpush