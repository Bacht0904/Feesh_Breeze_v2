<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

  

    public function brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);
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
            'image' => 'mimes:png,jpg,jpeg|max:2048'
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

    public function categories()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
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
    public function orders()
    {
        return view('admin.orders');
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
        return view('admin.sliders');
    }

    public function add_slide()
    {
        return view('admin.slide-add');
    }

    public function users()
    {
        return view('admin.users');
    }

    public function coupons()
    {
        $coupons = Coupon::orderBy('id', 'DESC')->paginate(10);
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
    
    public function update_coupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code,' .$request->id,
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $coupon = Coupon::find($request->id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->status = $request->status;
        $coupon->save();

        return redirect()->route('admin.coupons')->with('status', 'Coupon đã được cập nhật thành công!');
    }

    public function delete_coupon($id)
    {
        $coupon = Coupon::find($id);
        $coupon->delete();

        return redirect()->route('admin.coupons')->with('status', 'Coupon đã được xóa thành công!');
    }

    public function settings()
    {
        return view('admin.settings');
    }
}
