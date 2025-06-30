<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Slide;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Rules\MatchOldPassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
//use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
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

        $amountM = implode(',', collect($monthlyDatas)->pluck('totalAmount')->toArray());
        $orderedAmountM = implode(',', collect($monthlyDatas)->pluck('totalOrderedAmount')->toArray());
        $confirmedAmountM = implode(',', collect($monthlyDatas)->pluck('totalConfirmedAmount')->toArray());
        $deliveredAmountM = implode(',', collect($monthlyDatas)->pluck('totalDeliveredAmount')->toArray());

        $totalAmount = collect($monthlyDatas)->sum('totalAmount');
        $totalOrderedAmount = collect($monthlyDatas)->sum('totalOrderedAmount');
        $totalConfirmedAmount = collect($monthlyDatas)->sum('totalConfirmedAmount');
        $totalDeliveredAmount = collect($monthlyDatas)->sum('totalDeliveredAmount');

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
            'totalDeliveredAmount'
        ));

    }

    public function changePassword()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập trước khi tiếp tục.');
        }
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
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'description' => 'required|string|max:10000',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.size' => 'required|string',
            'variants.*.color' => 'required|string',
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = $request->slug ?? Str::slug($request->name);

        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->description = $request->description;
        $product->save(); // cần trước khi tạo product_detail

        $manager = new ImageManager(new Driver());

        foreach ($request->variants as $variant) {
            if (!isset($variant['image']) || !$variant['image']->isValid()) {
                continue; // Bỏ qua nếu không có ảnh hợp lệ
            }

            $image = $variant['image'];
            $uploadFolder = 'uploads/products/';
            $savePath = public_path($uploadFolder);

            if (!file_exists($savePath)) {
                mkdir($savePath, 0777, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $fullPath = $savePath . '/' . $filename;

            // Resize ảnh và lưu
            $manager->read($image->getRealPath())
                ->resize(800, 800)
                ->save($fullPath);

            $product->product_details()->create([
                'image' => $uploadFolder . $filename,
                'price' => $variant['price'],
                'size' => $variant['size'],
                'color' => $variant['color'],
                'quantity' => $variant['quantity'],
            ]);
        }


        return redirect()->route('admin.products')->with('success', 'Đã thêm sản phẩm thành công!');
    }

    public function edit_product($id)
    {
        $product = Product::with(['product_details'])->findOrFail($id);
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $brands = Brand::select('id', 'name')->orderBy('name')->get();

        return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }

    public function update_product(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug,' . $request->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'description' => 'required|string|max:1024',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.size' => 'required|string',
            'variants.*.color' => 'required|string',
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $product = Product::findOrFail($request->id);
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->description = $request->description;
        $product->save();

        // Xóa các chi tiết sản phẩm cũ
        $product->product_details()->delete();

        $manager = new ImageManager(new Driver());

        foreach ($request->variants as $variant) {
            $image = $variant['image'];

            $uploadFolder = 'uploads/products/';
            $savePath = public_path($uploadFolder);

            if (!file_exists($savePath)) {
                mkdir($savePath, 0777, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $fullPath = $savePath . '/' . $filename;

            // Resize ảnh đúng cách với ImageManager
            $manager->read($image)->resize(800, 800)->save($fullPath);

            $product->product_details()->create([
                'image' => $uploadFolder . $filename,
                'price' => $variant['price'],
                'size' => $variant['size'],
                'color' => $variant['color'],
                'quantity' => $variant['quantity'],
            ]);
        }

        return redirect()->route('admin.products')->with('success', 'Đã cập nhật sản phẩm thành công!');
    }

    public function product_detail($id)
    {
        $product = Product::with(['product_details'])->findOrFail($id);
        return view('admin.product-detail', compact('product'));
    }

    public function delete_product($id)
    {
        $product = Product::findOrFail($id);
        // Xóa các chi tiết sản phẩm liên quan
        foreach ($product->product_details as $detail) {
            // Xóa ảnh nếu có
            if (File::exists(public_path($detail->image))) {
                File::delete(public_path($detail->image));
            }
        }
        // Xóa sản phẩm
        $product->delete();

        return redirect()->route('admin.products')->with('status', 'Sản phẩm đã được xóa thành công!');
    }

    public function product_search(Request $request)
    {
        $search = $request->input('name'); // Đổi 'search' => 'name' để khớp với input name trong form

        $products = Product::where('name', 'like', '%' . $search . '%')
            ->orWhere('slug', 'like', '%' . $search . '%')
            ->with(['category', 'brand'])
            ->paginate(10);

        return view('admin.products', compact('products', 'search'));
    }


    public function brands()
    {
        $brands = Brand::orderBy('id', 'asc')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function add_brand()
    {
        return view('admin.brand-add');
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:brands,slug',
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Thương hiệu đã được thêm thành công!');
    }

    public function edit_brand($id)
    {
        $brand = Brand::find($id);
        return view('admin.brand-edit', compact('brand'));
    }

    public function update_brand(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
        ]);

        $brand = Brand::find($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Thương hiệu đã được sửa thành công!');
    }



    public function delete_brand($id)
    {
        $brand = Brand::find($id);
        $brand->delete();

        return redirect()->route('admin.brands')->with('status', 'Thương hiệu đã được xóa thành công!');
    }

    public function brand_search(Request $request)
    {
        $search = $request->input('name'); // Đổi 'search' => 'name' để khớp với input name trong form

        $brands = Brand::where('name', 'like', '%' . $search . '%')
            ->orWhere('slug', 'like', '%' . $search . '%')
            ->paginate(10);

        return view('admin.brands', compact('brands', 'search'));
    }

    public function categories()
    {
        $categories = Category::orderBy('id', 'asc')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function add_category()
    {
        return view('admin.category-add');
    }

    public function category_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if (Category::where('slug', $category->slug)->exists()) {
            return back()->withErrors(['slug' => 'Slug đã tồn tại, vui lòng nhập lại!'])->withInput();
        }
        $category->save();

        return redirect()->route('admin.categories')->with('status', 'Loại sản phẩm đã được thêm thành công!');
    }

    public function edit_category($id)
    {
        $category = Category::find($id);
        return view('admin.category-edit', compact('category'));
    }

    public function update_category(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $request->id,
        ]);

        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if (Category::where('slug', $category->slug)->where('id', '!=', $category->id)->exists()) {
            return back()->withErrors(['slug' => 'Slug đã tồn tại, vui lòng nhập lại!'])->withInput();
        }
        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Loại sản phẩm đã được sửa thành công!');
    }

    public function delete_category($id)
    {
        $category = Category::find($id);
        $category->delete();

        return redirect()->route('admin.categories')->with('status', 'Loại sản phẩm đã được xóa thành công!');
    }

    public function category_search(Request $request)
    {
        $search = $request->input('name'); // Đổi 'search' => 'name' để khớp với input name trong form

        $categories = Category::where('name', 'like', '%' . $search . '%')
            ->orWhere('slug', 'like', '%' . $search . '%')
            ->paginate(10);

        return view('admin.categories', compact('categories', 'search'));
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
        $user = User::find($id);
        return view('admin.user-edit', compact('user'));
    }

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

        return redirect()->route('admin.coupons');//->with('status', 'Coupon đã được cập nhật thành công!');
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
