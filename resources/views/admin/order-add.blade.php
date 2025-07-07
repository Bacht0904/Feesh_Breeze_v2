{{-- @extends('layouts.admin')

@section('content')
<h3>Tạo đơn hàng tại cửa hàng</h3>

<form action="{{ route('admin.order.store') }}" method="POST" id="orderForm">
    @csrf
    <div>
        <label>Họ tên khách</label>
        <input type="text" name="name" required>
    </div>
    <div>
        <label>SĐT khách</label>
        <input type="text" name="phone" required>
    </div>
    <div>
        <label>Địa chỉ</label>
        <input type="text" name="address" required>
    </div>

    <hr>

    <div>
        <label>Mã sản phẩm</label>
        <input type="text" id="productCode">
        <button type="button" onclick="addProduct()">Thêm sản phẩm</button>
    </div>

    <table id="productTable" border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Tên SP</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th>Xóa</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <input type="hidden" name="status" value="Đã Xác Nhận">
    <br>
    <button type="submit">Tạo đơn hàng</button>
</form>

<script>
    let items = [];

    function addProduct() {
        let code = document.getElementById('productCode').value;

        fetch(`/admin/products/find-by-code?code=${code}`)
            .then(res => res.json())
            .then(res => {
                if (!res.status) {
                    alert(res.message);
                    return;
                }

                let product = res.data;

                if (items.find(i => i.product_detail_id == product.id)) {
                    alert('Sản phẩm đã có trong danh sách');
                    return;
                }

                let row = `
                    <tr>
                        <td>${product.product.name} (${product.size}/${product.color})</td>
                        <td>${product.price}</td>
                        <td>
                            <input type="number" name="items[${items.length}][quantity]" value="1" min="1">
                            <input type="hidden" name="items[${items.length}][product_detail_id]" value="${product.id}">
                        </td>
                        <td class="total-cell">${product.price}</td>
                        <td><button type="button" onclick="this.closest('tr').remove()">Xóa</button></td>
                    </tr>
                `;

                document.querySelector("#productTable tbody").insertAdjacentHTML('beforeend', row);

                items.push({
                    product_detail_id: product.id
                });

                document.getElementById('productCode').value = '';
            });
    }
</script> 




@endsection --}}

@extends('layouts.admin')

@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Tạo đơn hàng tại cửa hàng</h3>
        </div>

        <div class="wg-box">
            <form action="{{ route('admin.order.store') }}" method="POST" id="orderForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block mb-1 text-xl font-bold text-gray-800">Họ tên khách</label>
                        <input type="text" name="name" class="form-input w-full" required>
                    </div>
                    <div>
                        <label class="block mb-1 font-semibold">SĐT khách</label>
                        <input type="text" name="phone" class="form-input w-full" required>
                    </div>
                    <div>
                        <label class="block mb-1 font-semibold">Địa chỉ</label>
                        <input type="text" name="address" class="form-input w-full" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Mã sản phẩm</label>
                    <div class="flex gap-2">
                        <input type="text" id="productCode" class="form-input w-full">
                        <button type="button" class="tf-button w208" onclick="addProduct()">+ Thêm sản phẩm</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="productTable">
                        <thead>
                            <tr>
                                <th>Tên SP</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <input type="hidden" name="status" value="Đã Xác Nhận">

                <div class="mt-5">
                    <button type="submit" class="tf-button w208 text-lg">Tạo đơn hàng</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let items = [];

    function addProduct() {
        let code = document.getElementById('productCode').value;

        fetch(`/admin/products/find-by-code?code=${code}`)
            .then(res => res.json())
            .then(res => {
                if (!res.status) {
                    toastr.error(res.message);
                    return;
                }

                let product = res.data;

                if (items.find(i => i.product_detail_id == product.id)) {
                    toastr.warning('Sản phẩm đã có trong danh sách');
                    return;
                }

                let index = items.length;

                let row = `
                    <tr>
                        <td>${product.product.name} (${product.size}/${product.color})</td>
                        <td>${product.price}</td>
                        <td>
                            <input type="number" name="items[${index}][quantity]" value="1" min="1" class="form-input w-20">
                            <input type="hidden" name="items[${index}][product_detail_id]" value="${product.id}">
                        </td>
                        <td class="total-cell">${product.price}</td>
                        <td><button type="button" class="tf-button text-sm bg-red-500 text-white" onclick="this.closest('tr').remove()">Xóa</button></td>
                    </tr>
                `;

                document.querySelector("#productTable tbody").insertAdjacentHTML('beforeend', row);

                items.push({ product_detail_id: product.id });

                document.getElementById('productCode').value = '';
            });
    }
</script>
@endsection
