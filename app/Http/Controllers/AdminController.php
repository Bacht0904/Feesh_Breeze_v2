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
//use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function products()
    {
        $products = Product::with(['category', 'brand'])->get();

        $products = Product::with(['product_details'])->orderBy('created_at', 'desc')->paginate(10);
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
            'description' => 'required|string|max:1024',
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

            // ✅ Resize ảnh đúng cách với ImageManager
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
            'code' => 'required|string|unique:coupons,code,' . $request->id,
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
