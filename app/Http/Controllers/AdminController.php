<?php
namespace App\Http\Controllers;

use App\Http\Models\Brand;
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

    public function products()
    {
        return view('admin.products');
    }

    public function add_product()
    {
        return view('admin.product-add');
    }

    public function brands()
    {
        $brands = Brand::orderBy('id','DESC')->paginate(10);
        return view('admin.brands', compact('brands'));
    }

    public function add_brand()
    {
        return view('admin.brand-add');
    }

    public function brand_store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'slug'  => 'required|string|unique:brands,slug',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        // Lấy ảnh & đặt tên file
        $image = $request->file('image');
        $ext   = $image->getClientOriginalExtension();
        $fileName = Carbon::now()->format('YmdHis') . '.' . $ext;

        // Resize và lưu ảnh thumbnail
        $this->generateBrandThumbnailsImage($image, $fileName);

        $brand->image = $fileName;
        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Thương hiệu đã được thêm thành công!');
    }

    public function generateBrandThumbnailsImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/brands');

        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        Image::read($image->path())
            ->cover(124, 124, 'top')
            ->save($destinationPath . '/' . $imageName);
    }

    public function categories()
    {
        return view('admin.categories');
    }

    public function add_category()
    {
        return view('admin.category-add');
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
        return view('admin.coupons');
    }

    public function add_coupon()
    {
        return view('admin.coupon-add');
    }

        public function settings()
    {
        return view('admin.settings');
    }
}
