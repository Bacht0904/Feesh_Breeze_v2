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

        $order = Order::with('details.productDetail.product')->find( $id );
        $orderItems = OrderDetail ::where(  'order_id', $order->id)->orderBy('created_at','desc')->paginate(12);
        return view('admin.order-detail', compact('order','orderItems'));

    }

    public function order_tracking()
    {
        return view('admin.order-tracking');
    }


    public function update_order_status(Request $request)
    {
        // $order = Order::find($request->id);
        // if (!$order) {
        // return back()->withErrors(['error' => 'Không tìm thấy đơn hàng.']);
        // }
        // $order->status = $request->status;
        // // if($request->status == 'Đã Giao')
        // // {
        // //     $order->delivered_date = Carbon::now();
        // // }
        // // else if($request->status == 'Đã Hủy')
        // // {
        // //     $order->canceled_date = Carbon::now();
        // // }
        // $order->save();
        // return back()->with('status','Đã cập nhật trạng thái đơn hàng thành công');
        $order = Order::with('details.productDetail') ->find($request->id);
        
        if(!$order) {
            return back()->with('error','Không tìm thấy đơn hàng.');
        }

        if($order->status ==="Đã Hủy") {
            return back()->with('error','Đơn hàng đã bị hủy bạn không thể thay đổi trạng thái');
        }

        $newStatus =$request->status;
        $previousStatus = $order->status;

        if($newStatus === $previousStatus) {
            return back() ->with('status', 'Trạng thái không thay đổi.');
        }

        DB::transaction(function () use ($order, $newStatus, $previousStatus)
        {

            // Nếu chuyển sang "Đã Xác Nhận" ->trừ tồn kho (chưa trừ lần nào)
            if($previousStatus !="Đã Xác Nhận" && $newStatus =="Đã Xác Nhận") {

                foreach ($order->details as $item) {
                    $productDetail = $item ->productDetail;
                    if($productDetail->quantity <$item->quantity) {
                        throw new \Exception("Sản Phẩm {$productDetail ->name} không đủ số lượng tồn kho." );
                    }
                    $productDetail->quantity -= $item->quantity;
                    $productDetail->save();

                }
            }
            if($previousStatus == "Đã Xác Nhận" && $newStatus == "Đã Hủy") {

                foreach ($order->details as $item) {
                    $productDetail = $item ->productDetail;
                    $productDetail->quantity += $item->quantity;
                    $productDetail->save();

                }
            
            }
            $order->status = $newStatus;
            $order->save();
        });
        return back()->with("status","Đã cập nhật trạng thái đơn hàng thành công");

    }


    public function sliders()
    {
        $slides = Slide::orderBy('id', 'asc')->paginate(10);
        return view('admin.sliders', compact('slides'));
    }

    public function add_slide()
    {
        return view('admin.slide-add');
    }

    public function slide_store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'required|string|max:1024',
            'link' => 'required|url',
        ]);

        $slide = new Slide();
        $slide->title = $request->title;
        $slide->description = $request->description;
        $slide->link = $request->link;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $uploadFolder = 'uploads/slides/';
            $savePath = public_path($uploadFolder);

            if (!file_exists($savePath)) {
                mkdir($savePath, 0777, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $fullPath = $savePath . '/' . $filename;

            // Resize ảnh và lưu
            $manager = new ImageManager(new Driver());
            $manager->read($image->getRealPath())
                ->resize(800, 400)
                ->save($fullPath);

            $slide->image = $uploadFolder . $filename;
        }

        $slide->save();

        return redirect()->route('admin.sliders')->with('status', 'Slide đã được thêm thành công!');
    }

    public function edit_slide($id)
    {
        $slide = Slide::find($id);
        return view('admin.slide-edit', compact('slide'));
    }

    public function update_slide(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'required|string|max:1024',
            'link' => 'required|url',
        ]);

        $slide = Slide::find($request->id);
        $slide->title = $request->title;
        $slide->description = $request->description;
        $slide->link = $request->link;

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($slide->image && File::exists(public_path($slide->image))) {
                File::delete(public_path($slide->image));
            }

            $image = $request->file('image');
            $uploadFolder = 'uploads/slides/';
            $savePath = public_path($uploadFolder);

            if (!file_exists($savePath)) {
                mkdir($savePath, 0777, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $fullPath = $savePath . '/' . $filename;

            // Resize ảnh và lưu
            $manager = new ImageManager(new Driver());
            $manager->read($image->getRealPath())
                ->resize(800, 400)
                ->save($fullPath);

            $slide->image = $uploadFolder . $filename;
        }

        $slide->save();

        return redirect()->route('admin.sliders')->with('status', 'Slide đã được cập nhật thành công!');
    }

    public function toggle_slide_status($id)
    {
        $slide = Slide::findOrFail($id);
        $slide->status = $slide->status === 'active' ? 'inactive' : 'active';
        $slide->save();

        return redirect()->route('admin.sliders')->with('status', 'Trạng thái đã được cập nhật!');
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
