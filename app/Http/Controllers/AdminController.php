<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\OrderDetail;
use App\Notifications\OrderStatusUpdated;
use App\Notifications\OrderDeliveredNotification;



class AdminController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 📆 Lấy tháng và năm từ request hoặc dùng mặc định
        $month = $request->input('month') ?? date('m');
        $year = $request->input('year') ?? date('Y');

        // 📦 Lấy đơn hàng theo tháng/năm đã chọn
        $orders = Order::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // 📊 Dữ liệu dashboard theo tháng/năm chọn
        $dashboardDatas = DB::select("
        SELECT sum(total) as totalAmount,
               sum(IF(status = 'Chờ Xác Nhận', total, 0)) as totalOrderedAmount,
               sum(IF(status = 'Đã Xác Nhận', total, 0)) as totalConfirmedAmount,
               sum(IF(status = 'Đã Giao', total, 0)) as totalDeliveredAmount,
               count(*) as total,
               sum(IF(status = 'Chờ Xác Nhận', 1, 0)) as totalOrdered,
               sum(IF(status = 'Đã Xác Nhận', 1, 0)) as totalConfirmed,
               sum(IF(status = 'Đã Giao', 1, 0)) as totalDelivered
        FROM orders
        WHERE month(created_at) = ? AND year(created_at) = ?
    ", [$month, $year]);

        // 📈 Dữ liệu doanh thu từng tháng trong năm đã chọn
        $monthlyDatas = DB::select("
        SELECT M.id as month_No,
               M.name as monthName,
               IFNULL(D.totalAmount, 0) as totalAmount,
               IFNULL(D.totalOrderedAmount, 0) as totalOrderedAmount,
               IFNULL(D.totalConfirmedAmount, 0) as totalConfirmedAmount,
               IFNULL(D.totalDeliveredAmount, 0) as totalDeliveredAmount
        FROM month_names M
        LEFT JOIN (
            SELECT MONTH(created_at) as monthNo,
                   SUM(total) as totalAmount,
                   SUM(IF(status = 'Chờ Xác Nhận', total, 0)) as totalOrderedAmount,
                   SUM(IF(status = 'Đã Xác Nhận', total, 0)) as totalConfirmedAmount,
                   SUM(IF(status = 'Đã Giao', total, 0)) as totalDeliveredAmount
            FROM orders
            WHERE YEAR(created_at) = ?
            GROUP BY MONTH(created_at)
        ) D ON D.monthNo = M.id
    ", [$year]);

        // 🎯 Tổng doanh thu cho biểu đồ
        $amountM = collect($monthlyDatas)->pluck('totalAmount')->map(fn($v) => round($v, 2))->values();
        $orderedAmountM = collect($monthlyDatas)->pluck('totalOrderedAmount')->map(fn($v) => round($v, 2))->values();
        $confirmedAmountM = collect($monthlyDatas)->pluck('totalConfirmedAmount')->map(fn($v) => round($v, 2))->values();
        $deliveredAmountM = collect($monthlyDatas)->pluck('totalDeliveredAmount')->map(fn($v) => round($v, 2))->values();

        // 📦 Doanh thu tổng cho tháng đã chọn (sửa từ dashboardDatas)
        $totalAmount = $dashboardDatas[0]->totalAmount ?? 0;
        $totalOrderedAmount = $dashboardDatas[0]->totalOrderedAmount ?? 0;
        $totalConfirmedAmount = $dashboardDatas[0]->totalConfirmedAmount ?? 0;
        $totalDeliveredAmount = $dashboardDatas[0]->totalDeliveredAmount ?? 0;

        $contactCount = Contact::count();

        return view('admin.index', compact(
            'orders',
            'dashboardDatas',
            'amountM',
            'orderedAmountM',
            'confirmedAmountM',
            'deliveredAmountM',
            'totalAmount',
            'totalOrderedAmount',
            'totalConfirmedAmount',
            'totalDeliveredAmount',
            'contactCount',
            'user'
        ));
    }


    public function changePassword()
    {
        return view('admin.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // So sánh mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => '⚠️ Mật khẩu hiện tại không đúng.']);
        }

        // Nếu đúng thì cập nhật mật khẩu mới
        $user->password = Hash::make($request->new_password);
        $user->save();
        return redirect()->route('admin.settings')->with('success', 'Đã đổi mật khẩu thành công!');
    }




    public function orders()
    {
        $orders = Order::orderBy('created_at', 'desc')->paginate(12);
        return view('admin.orders', compact('orders'));
    }

    public function order_detail($id)
    {
        $order = Order::with('details.productDetail.product')->find($id);

        if (!$order) {
            abort(404, 'Không tìm thấy đơn hàng');
        }

        $orderItems = OrderDetail::where('order_id', $order->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('admin.order-detail', compact('order', 'orderItems'));
    }


    public function order_tracking()
    {
        return view('admin.order-tracking');
    }


    public function markAsDelivered($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return back()->with('error', 'Không tìm thấy đơn hàng.');
        }

        if ($order->status !== 'Đang Giao') {
            return back()->with('error', 'Chỉ có thể xác nhận đơn hàng đang giao.');
        }

        $order->status = 'Đã Giao';
        $order->payment_status = 'Đã Thanh Toán';
        $order->updated_at = now();
        $order->save();
        // Gửi thông báo cho tất cả admin
        $admins = \App\Models\User::whereIn('role', ['admin', 'staff'])->get();

        foreach ($admins as $admin) {
            $admin->notify(new OrderDeliveredNotification($order));
        }

        return back()->with('status', 'Đơn hàng đã được đánh dấu là Giao Thành Công.');
    }

    public function updateStatus(Request $request)
    {
        $order = Order::with(['details.productDetail', 'user'])->find($request->id);

        if (!$order) {
            return back()->with('error', 'Không tìm thấy đơn hàng.');
        }

        if ($order->status === 'Đã Hủy') {
            return back()->with('error', 'Đơn hàng đã bị hủy, không thể thay đổi trạng thái.');
        }

        $newStatus = $request->status;
        $oldStatus = $order->status;

        if ($newStatus === $oldStatus) {
            return back()->with('status', 'Trạng thái không thay đổi.');
        }

        try {
            DB::transaction(function () use ($order, $newStatus, $oldStatus) {
                // ✅ Trừ tồn kho khi xác nhận đơn
                if ($oldStatus !== 'Đã Xác Nhận' && $newStatus === 'Đã Xác Nhận') {
                    foreach ($order->details as $item) {
                        $product = $item->productDetail;

                        if ($product->quantity < $item->quantity) {
                            throw new \Exception("Sản phẩm {$product->name} không đủ số lượng tồn kho.");
                        }

                        $product->quantity -= $item->quantity;
                        $product->save();
                    }
                }

                // ✅ Hoàn lại kho nếu hủy khi đã xác nhận
                if ($oldStatus === 'Đã Xác Nhận' && $newStatus === 'Đã Hủy') {
                    foreach ($order->details as $item) {
                        $product = $item->productDetail;
                        $product->quantity += $item->quantity;
                        $product->save();
                    }
                }

                $order->status = $newStatus;
                $order->updated_at = now();
                $order->save();
            });

            if ($order->user && !$order->user->isAdmin()) {
                $order->user->notify(new OrderStatusUpdated($order));
            }
            return back()->with('status', 'Đã cập nhật trạng thái đơn hàng thành công.');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage());
        }
    }






    public function settings()
    {
        // Kiểm tra nếu chưa đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập trước khi tiếp tục.');
        }

        $user = Auth::user(); // Lấy thông tin người dùng đã đăng nhập

        return view('admin.settings', ['user' => Auth::user()]);
    }



    public function setting(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'email' => 'required|email|min:8|unique:users,email,' . $user->id,
            'phone' => ['required', 'regex:/^0[0-9]{9}$/'],
            'address' => 'required|string|max:255',
            'avatar' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $uploadFolder = 'uploads/users/';
            $savePath = public_path($uploadFolder);

            if (!file_exists($savePath)) {
                mkdir($savePath, 0777, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
            $fullPath = $savePath . '/' . $filename;

            $manager = new ImageManager(new Driver());
            $manager->read($avatar->getRealPath())
                ->resize(800, 400)
                ->save($fullPath);

            $user->avatar = $uploadFolder . $filename;
        }

        $user->save();

        return redirect()->route('admin.users')->with('status', 'Thông tin người dùng đã được cập nhật!');
    }
}
