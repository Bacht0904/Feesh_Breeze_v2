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
            ->with(['details.productDetail']) // load cả chi tiết sản phẩm và thông tin sản phẩm gốc
            ->firstOrFail();
        $canReview = $order->status === 'Đã Giao' || $order->status === 'Hoàn Thành';
        return view('user.orderdetail', compact('order', 'canReview'));
    }
   
}
