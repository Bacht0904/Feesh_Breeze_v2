<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Danh sách đơn hàng của user
    public function index()
    {
        $orders = Order::where('id_user', Auth::id())
            ->with('details')
            ->withCount('details')
            // Đếm số lượng sản phẩm
            ->orderByDesc('order_date')

            ->withCount('details')

            ->paginate(10);

        return view('user.order', compact('orders'));
    }

    // Chi tiết đơn hàng
    public function show($id)
    {
        $order = Order::where('id', $id)
            ->where('id_user', Auth::id())
            ->with(['details.productDetail.product', 'details.review']) // 👈 THÊM chỗ này
            ->firstOrFail();



        $canReview = $order->status === 'Chờ Xác Nhận';
        return view('user.orderdetail', compact('order', 'canReview'));
    }
}
//  $table->enum('status', ['Chờ Xác Nhận','Đã Xác Nhận','Chờ Lấy Hàng','Đã Lấy Hàng','Đang Giao','Đã Giao','Giao Thành Công','Xác Nhận Hủy','Đã Hủy'])->default('Chờ Xác Nhận');       