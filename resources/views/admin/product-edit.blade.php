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
                    <li><a href="{{ route('admin.products') }}">
                            <div class="text-tiny">Sản phẩm</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Sửa sản phẩm</div>
                    </li>
                </ul>
            </div>

            <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data"
                action="{{ route('admin.product.update', $product->id) }}">
                @csrf
                @method('PUT')
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Tên sản phẩm<span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" name="name" placeholder="Nhập tên sản phẩm"
                            value="{{ $product->name }}" required>
                        <div class="text-tiny">Không được nhập tên sản phẩm quá 100 ký tự.</div>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title mb-10">Slug<span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" name="slug" placeholder="Nhập mã sản phẩm"
                            value="{{ $product->slug }}" required>
                    </fieldset>

                    <div class="gap22 cols">
                        <fieldset class="category">
                            <div class="body-title mb-10">Loại sản phẩm <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select name="category_id" required>
                                    <option disabled {{ !isset($product->category_id) ? 'selected' : '' }}>Chọn loại sản phẩm</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ (isset($product) && $product->category_id == $category->id) ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                            </option>
                                        @endforeach
                                </select>
                            </div>
                        </fieldset>


                        <fieldset class="brand">
                            <div class="body-title mb-10">Thương hiệu <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select name="brand_id" required>
                                    <option disabled {{ !isset($product->brand_id) ? 'selected' : '' }}>Chọn thương hiệu
                                    </option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ (isset($product) && $product->brand_id == $brand->id) ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>

                    </div>

                    <div class="gap22 cols">
                        <fieldset class="is-new">
                            <div class="body-title mb-10">Sản phẩm mới?<span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select name="isNew" required>
                                    <option disabled {{ !isset($product) ? 'selected' : '' }}>Chọn trạng thái</option>
                                    <option value="1" {{ isset($product) && $product->isNew == 1 ? 'selected' : '' }}>Có 🔥</option>
                                    <option value="0" {{ isset($product) && $product->isNew == 0 ? 'selected' : '' }}>Không</option>
                                </select>
                            </div>
                        </fieldset>
                    </div>

                </div>

                <div class="wg-box">

                    <fieldset class="shortdescription">
                        <div class="body-title mb-10">Mô tả<span class="tf-color-1">*</span></div>
                        <textarea class="mb-10 ht-150" name="description" placeholder="Nhập mô tả"
                            required>{{ $product->description }}</textarea>
                    </fieldset>
                    {{-- Form con – biến thể --}}
                    <div class="body-title mb-10">Biến thể sản phẩm</div>
                    <div id="variant-list">
                        @foreach($product->product_details as $index => $detail)
                            <div class="variant-item gap22 cols mb-16">
                                <fieldset class="name">
                                    <input type="text" name="variants[{{ $index }}][size]" value="{{ $detail->size }}"
                                        placeholder="Size" required>
                                </fieldset>
                                <fieldset class="name">
                                    <input type="text" name="variants[{{ $index }}][color]" value="{{ $detail->color }}"
                                        placeholder="Màu sắc" required>
                                </fieldset>
                                <fieldset class="name">
                                    <input type="number" name="variants[{{ $index }}][quantity]" value="{{ $detail->quantity }}"
                                        placeholder="Số lượng" required>
                                </fieldset>
                                <fieldset class="name">
                                    <input type="number" name="variants[{{ $index }}][price]" value="{{ $detail->price }}"
                                        placeholder="Giá bán" required>
                                </fieldset>
                                <fieldset class="name">
                                    <input type="file" name="variants[{{ $index }}][image]" accept="image/*">
                                    @if($detail->image)
                                        <div class="mt-1">
                                            <small>Ảnh hiện tại: <a href="{{ asset($detail->image) }}"
                                                    target="_blank">{{ basename($detail->image) }}</a></small>
                                        </div>
                                    @endif
                                </fieldset>
                                <button type="button" class="remove-variant tf-button small danger">Xoá</button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="add-variant" class="tf-button outline w-auto">+ Thêm biến thể</button>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="cols gap10">
                    <button class="tf-button w-full" type="submit">Sửa sản phẩm</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let variantIndex = 1;
        document.getElementById('add-variant').addEventListener('click', function () {
            const html = `
                            <div class="variant-item gap22 cols mb-16">
                                <fieldset class="name"><input type="text" name="variants[${variantIndex}][size]" placeholder="Size" required></fieldset>
                                <fieldset class="name"><input type="text" name="variants[${variantIndex}][color]" placeholder="Màu sắc" required></fieldset>
                                <fieldset class="name"><input type="number" name="variants[${variantIndex}][quantity]" placeholder="Số lượng" required></fieldset>
                                <fieldset class="name"><input type="number" name="variants[${variantIndex}][price]" placeholder="Giá bán" required></fieldset>
                                <fieldset class="name"><input type="file" name="variants[${variantIndex}][image]" accept="image/*" required></fieldset>
                                <button type="button" class="remove-variant tf-button small danger">Xoá</button>
                            </div>
                        `;
            document.getElementById('variant-list').insertAdjacentHTML('beforeend', html);
            variantIndex++;
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-variant')) {
                e.target.closest('.variant-item').remove();
            }
        });
    </script>
@endsection

@push('scripts')
    <script>
        $(function () {
            $("#myFile").on("change", function (e) {
                const photoInp = $("#myFile");
                const [file] = this.files;
                if (file) {
                    $("#imgpreview").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });

            $("input[name='name']").on("change", function () {
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