<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_detail;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        foreach ($request->variants as $variant) {
            $image = $variant['image'];

            $uploadFolder = 'uploads/products/';
            $savePath = public_path($uploadFolder);

            // Tạo thư mục nếu chưa tồn tại
            if (!file_exists($savePath)) {
                mkdir($savePath, 0777, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $fullPath = $savePath . '/' . $filename;

            // Resize và lưu ảnh
            Image::make($image)->resize(800, 800)->save($fullPath);

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

        foreach ($request->variants as $variant) {
            $image = $variant['image'] ?? null;

            if ($image) {
                $uploadFolder = 'uploads/products/';
                $savePath = public_path($uploadFolder);

                // Tạo thư mục nếu chưa có
                if (!file_exists($savePath)) {
                    mkdir($savePath, 0777, true);
                }

                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $fullPath = $savePath . '/' . $filename;

                // Resize và lưu ảnh
                Image::make($image)->resize(800, 800)->save($fullPath);

                $product->product_details()->create([
                    'image' => $uploadFolder . $filename,
                    'price' => $variant['price'],
                    'size' => $variant['size'],
                    'color' => $variant['color'],
                    'quantity' => $variant['quantity'],
                ]);
            } else {
                // Nếu không có ảnh, chỉ lưu các thông tin khác
                $product->product_details()->create([
                    'price' => $variant['price'],
                    'size' => $variant['size'],
                    'color' => $variant['color'],
                    'quantity' => $variant['quantity'],
                ]);
            }
        }

        return redirect()->route('admin.products')->with('success', 'Đã cập nhật sản phẩm thành công!');
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
}
