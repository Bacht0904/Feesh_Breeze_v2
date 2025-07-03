<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Rules\MatchOldPassword;
use App\Models\User;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Hash;

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
    // public function changPasswordStore(Request $request)
    // {
    //     $request->validate([
    //         'current_password' => ['required', new MatchOldPassword],
    //         'new_password' => ['required'],
    //         'new_confirm_password' => ['same:new_password'],
    //     ]);

    //     User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);

    //     return redirect()->route('admin')->with('success', 'thay đổi mật khẩu thành công');
    // }

    public function show($Slug)
    {
        $product = Product::with(['category', 'brand', 'product_details'])
            ->where('slug', $Slug)
            ->firstOrFail();
        $products = Product::whereHas('product_details', fn($q) => $q->where('quantity', '>', 0))
            ->with([
                'product_details' => fn($q) => $q->where('quantity', '>', 0),
                'reviews'
            ])
            ->get();
        if (!$product) {
            abort(404, view('errors.product-not-found'));
        }
        return view('user.product', compact('product'));
    }


    public function products()
    {
        $products = Product::with(['category', 'brand'])->get();

        $products = Product::with(['product_details'])->orderBy('id', 'asc')->paginate(10);
        return view('admin.products', compact('products'));
    }

    public function add_product()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-add', compact('categories', 'brands'));
    }
    public function showAddProductForm()
    {
        return view('admin.add_product'); // hoặc tên view của bạn
    }


    public function product_store(Request $request)
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
        $order = Order::find($id);
        $orderItems = OrderDetail ::where(  'order_id', $order->id)->orderBy('created_at','desc')->paginate(12);
        return view('admin.order-detail', compact('order','orderItems'));
    }

    public function order_tracking()
    {
        return view('admin.order-tracking');
    }

    public function update_order_status(Request $request)
    {
        $order = Order::find($request->id);
        if (!$order) {
        return back()->withErrors(['error' => 'Không tìm thấy đơn hàng.']);
    }
        $order->status = $request->status;
        // if($request->status == 'Đã Giao')
        // {
        //     $order->delivered_date = Carbon::now();
        // }
        // else if($request->status == 'Đã Hủy')
        // {
        //     $order->canceled_date = Carbon::now();
        // }
        $order->save();
        return back()->with('status','Đã cập nhật trạng thái đơn hàng thành công');
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

    public function delete_slide($id)
    {
        $slide = Slide::find($id);

        // Xóa ảnh nếu có
        if ($slide->image && File::exists(public_path($slide->image))) {
            File::delete(public_path($slide->image));
        }

        $slide->delete();

        return redirect()->route('admin.sliders')->with('status', 'Slide đã được xóa thành công!');
    }

    public function users()
    {
        $users = User::orderBy('id', 'asc')->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function add_user()
    {
        return view('admin.user-add');
    }

    public function edit_user($id)
    {
        $user = Auth::user();

    public function user_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->role = 'staff';         // Gán mặc định
        $user->status = 'active';      // Gán mặc định

        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $uploadFolder = 'uploads/users/';
            $savePath = public_path($uploadFolder);

            if (!file_exists($savePath)) {
                mkdir($savePath, 0777, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $fullPath = $savePath . '/' . $filename;

            $manager = new ImageManager(new Driver());
            $manager->read($image->getRealPath())
                ->resize(800, 400)
                ->save($fullPath);

            $user->image = $uploadFolder . $filename;
        }

        $user->save();

        return redirect()->route('admin.users')->with('status', 'Người dùng đã được thêm thành công!');
    }


    public function update_user(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'phone' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->role = $request->role;
        $user->status = $request->status;

        // Gán mặc định
        $user->role = 'staff';
        $user->status = 'active';

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $uploadFolder = 'uploads/users/';
            $savePath = public_path($uploadFolder);

            if (!file_exists($savePath)) {
                mkdir($savePath, 0777, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $fullPath = $savePath . '/' . $filename;

            $manager = new ImageManager(new Driver());
            $manager->read($image->getRealPath())
                ->resize(800, 400)
                ->save($fullPath);

            $user->image = $uploadFolder . $filename;
        }

        $user->save();

        return redirect()->route('admin.users')->with('status', 'Người dùng đã được cập nhật thành công!');
    }


    public function delete_user($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect()->route('admin.users')->with('status', 'Người dùng đã được xóa thành công!');
        } else {
            return redirect()->route('admin.users')->with('error', 'Người dùng không tồn tại!');
        }
    }

    public function coupons()
    {
        $coupons = Coupon::orderBy('id', 'asc')->paginate(10);
        return view('admin.coupons', compact('coupons'));
    }

    public function add_coupon()
    {

        return view('admin.coupon-add');
    }

    public function coupon_store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
        ]);
        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->save();

        return redirect()->route('admin.coupons')->with('status', 'Coupon đã được thêm thành công!');
    }

    public function edit_coupon($id)
    {
        $coupon = Coupon::find($id);
        return view('admin.coupon-edit', compact('coupon'));
    }

    public function update_coupon(Request $request, $id)
    {

        $request->validate([
            'code' => 'string|required', //'string| unique:coupons,code,' //. $request->id,//['required',Rule::unique('coupons','code')->ignore($id)],
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $coupon = Coupon::find($id);
        $data = $request->all();
        $status = $coupon->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', 'Cập nhật mã thành công');
        } else {
            request()->session()->flash('error', 'Vui lòng thử lại !!!');
        }

        // $coupon->code = $request->code;
        // $coupon->type = $request->type;
        // $coupon->value = $request->value;
        // $coupon->status = $request->status;
        // $coupon->save();

        return redirect()->route('admin.coupons'); //->with('status', 'Coupon đã được cập nhật thành công!');
    }

    public function delete_coupon($id)
    {
        // $coupon = Coupon::find($id);
        // $coupon->delete();

        $coupon = Coupon::findOrFail($id);
        if ($coupon) {
            $status = $coupon->delete();
            if ($status) {
                request()->session()->flash('success', 'Xóa mã thành công');
            } else {
                request()->session()->flash('error', 'Lỗi, vui lòng thử lại!!');
            }
            return redirect()->route('admin.coupons');
        } else {
            request()->session()->flash('error', 'Không tìm thấy mã giảm giá');
            return redirect()->back();
        }
    }

    public function settings()
    {
        return view('admin.settings');
    }
}
