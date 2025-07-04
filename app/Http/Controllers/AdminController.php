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

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::orderBy('created_at', 'desc')->get()->take(10);
        $dashboardDatas = DB::select("Select sum(total) as totalAmount,
                                            sum(if(status = 'Chờ Xác Nhận', total, 0)) as totalOrderedAmount,
                                            sum(if(status = 'Đã Xác Nhận', total, 0)) as totalConfirmedAmount,
                                            sum(if(status = 'Đã Giao', total, 0)) as totalDeliveredAmount,
                                            count(*) as total,
                                            sum(if(status = 'Chờ Xác Nhận', 1, 0)) as totalOrdered,
                                            sum(if(status = 'Đã Xác Nhận', 1, 0)) as totalConfirmed,
                                            sum(if(status = 'Đã Giao', 1, 0)) as totalDelivered
                                            from orders
                                            ");
        $monthlyDatas = DB::select("Select 
                                            M.id as month_No, 
                                            M.name as monthName, 
                                            Ifnull(D.totalAmount, 0) as totalAmount, 
                                            Ifnull(D.totalOrderedAmount, 0) as totalOrderedAmount, 
                                            Ifnull(D.totalConfirmedAmount, 0) as totalConfirmedAmount, 
                                            Ifnull(D.totalDeliveredAmount, 0) as totalDeliveredAmount 
                                            from month_names M 
                                            left join (
                                            Select 
                                                date_format(created_at, '%b') as monthName, 
                                                month(created_at) as monthNo, 
                                                sum(total) as totalAmount, 
                                                sum(if(status = 'Chờ Xác Nhận', total, 0)) as totalOrderedAmount, 
                                                sum(if(status = 'Đã Xác Nhận', total, 0)) as totalConfirmedAmount, 
                                                sum(if(status = 'Đã Giao', total, 0)) as totalDeliveredAmount 
                                            from orders 
                                            where year(created_at) = year(now()) 
                                            group by year(created_at), month(created_at), date_format(created_at, '%b') 
                                            order by month(created_at)
                                            ) D on D.monthNo = M.id");

        $amountM = collect($monthlyDatas)->pluck('totalAmount')->map(fn($v) => round($v, 2))->values();
        $orderedAmountM = collect($monthlyDatas)->pluck('totalOrderedAmount')->map(fn($v) => round($v, 2))->values();
        $confirmedAmountM = collect($monthlyDatas)->pluck('totalConfirmedAmount')->map(fn($v) => round($v, 2))->values();
        $deliveredAmountM = collect($monthlyDatas)->pluck('totalDeliveredAmount')->map(fn($v) => round($v, 2))->values();


        $totalAmount = collect($monthlyDatas)->sum('totalAmount');
        $totalOrderedAmount = collect($monthlyDatas)->sum('totalOrderedAmount');
        $totalConfirmedAmount = collect($monthlyDatas)->sum('totalConfirmedAmount');
        $totalDeliveredAmount = collect($monthlyDatas)->sum('totalDeliveredAmount');

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
        return view('auth.password.change');
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

    public function order_detail()
    {
        return view('admin.order-detail');
    }

    public function order_tracking()
    {
        return view('admin.order-tracking');
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

        $user->update($request->only('name', 'email', 'phone', 'avatar'));

        return redirect()->route('admin.users')->with('status', 'Thông tin người dùng đã được cập nhật!');
    }

}