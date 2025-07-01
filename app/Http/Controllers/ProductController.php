<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class ProductController extends Controller
{
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

    public function product_store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
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
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
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
}
