<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\OrderCancelRequested;
use App\Notifications\ReturnRequestNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

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
            ->with([
                'details.productDetail.product',
                'details.review'    // gọi đến OrderDetail::review()
            ])->firstOrFail();
        $order = Order::with('details.review')->findOrFail($id);

        $canReview = $order->status === 'Đã Giao';
        return view('user.orderdetail', compact('order', 'canReview'));
    }



    public function cancel(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->status = 'Xác Nhận Hủy';
        $order->save();

        // Gửi thông báo cho tất cả admin
        $recipients = User::whereIn('role', ['admin', 'staff'])->get();

        Notification::send($recipients, new OrderCancelRequested($order));

        // ✅ (tuỳ chọn) log lại admin đã nhận
        foreach ($recipients as  $recipient) {
            \Log::info("Đã gửi thông báo hủy đơn #{$order->id} tới admin ID: { $recipient->id}");
        }

        return redirect()->back()->with('success', 'Đã gửi yêu cầu hủy đơn hàng');
    }
    public function refund(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Cập nhật trạng thái hoặc logic tùy theo yêu cầu
        $order->status = 'Yêu Cầu Trả Hàng';
        $order->save();

        // Gửi thông báo đến admin và staff
        $recipients = User::whereIn('role', ['admin', 'staff'])->get();
        Notification::send($recipients, new ReturnRequestNotification($order));

        return back()->with('success', 'Yêu cầu trả hàng đã được gửi tới quản trị viên.');
    }
}
//  $table->enum('status', ['Chờ Xác Nhận','Đã Xác Nhận','Chờ Lấy Hàng','Đã Lấy Hàng','Đang Giao','Đã Giao','Giao Thành Công','Xác Nhận Hủy','Đã Hủy'])->default('Chờ Xác Nhận');