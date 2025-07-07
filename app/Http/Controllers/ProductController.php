<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product_details;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function products()
    {
        $products = Product::with(['category', 'brand', 'product_details'])->orderBy('id', 'asc')->paginate(10);

        foreach ($products as $product) {
            $category = $product->category;
            $brand = $product->brand;

            // Tính tổng số lượng từ các biến thể
            $total_quantity = $product->product_details->sum('quantity');

            // Xác định trạng thái
            $newStatus = (
                $total_quantity === 0
            ) ? 'inactive' : 'active';

            // Chỉ update nếu có thay đổi
            if ($product->status !== $newStatus) {
                $product->status = $newStatus;
                $product->save();
            }
        }

        return view('admin.products', compact('products'));
    }


    public function add_product()
    {
        $dscategories = Category::select('id', 'name')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $brands = Brand::select('id', 'name')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.product-add', compact('dscategories', 'brands'));
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
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}0-9\s\-\%\.,()]+$/u'],
            'slug' => 'required|string|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'description' => 'required|string|max:10000',
            'isNew' => 'required|in:0,1',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.size' => 'required|string',
            'variants.*.color' => 'required|string',
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Kiểm tra trùng biến thể size + color
        $combinationCheck = [];
        foreach ($request->variants as $index => $variant) {
            $key = strtolower(trim($variant['size'])) . '-' . strtolower(trim($variant['color']));
            if (in_array($key, $combinationCheck)) {
                return back()->withErrors([
                    "variants.$index.size" => "Biến thể thứ " . ($index + 1) . " có kích thước + màu đã bị trùng.",
                ])->withInput();
            }
            $combinationCheck[] = $key;
        }

        $category = Category::findOrFail($request->category_id);
        $brand = Brand::findOrFail($request->brand_id);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = $request->slug ?? Str::slug($request->name);
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->description = $request->description;
        $product->isNew = $request->isNew;

        // Xác định trạng thái sản phẩm
        $total_quantity = collect($request->variants)->sum('quantity');
        $product->status = $total_quantity === 0 ? 'inactive' : 'active';

        $product->save();

        $manager = new ImageManager(new Driver());

        foreach ($request->variants as $variant) {
            if (!isset($variant['image']) || !$variant['image']->isValid()) {
                continue;
            }

            $image = $variant['image'];
            $uploadFolder = 'uploads/products/';
            $savePath = public_path($uploadFolder);

            if (!file_exists($savePath)) {
                mkdir($savePath, 0777, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $fullPath = $savePath . '/' . $filename;

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
        $categories = Category::select('id', 'name')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $brands = Brand::select('id', 'name')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }

    public function update_product(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}0-9\s\-\%\.,()]+$/u'],
            'slug' => 'required|string|unique:products,slug,' . $request->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'description' => 'required|string|max:1024',
            'isNew' => 'required|in:0,1',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.size' => 'required|string',
            'variants.*.color' => 'required|string',
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
            'deleted_keys' => 'nullable|array',
        ]);

        $category = Category::findOrFail($request->category_id);
        $product = Product::findOrFail($request->id);

        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->description = $request->description;
        $product->isNew = $request->isNew;

        $category = Category::findOrFail($request->category_id);
        $brand = Brand::findOrFail($request->brand_id);

        $total_quantity = 0;
        foreach ($request->variants as $variant) {
            $total_quantity += $variant['quantity'];
        }

        if ($total_quantity === 0)
            $product->status = 'inactive';
        else
            $product->status = 'active';

        $product->save();

        $existingDetails = $product->product_details->keyBy(function ($detail) {
            return $detail->size . '_' . $detail->color;
        });

        $manager = new ImageManager(new Driver());

        // ❌ Xoá các biến thể được chỉ định từ request
        if ($request->has('deleted_keys')) {
            foreach ($request->deleted_keys as $key) {
                if (isset($existingDetails[$key])) {
                    $existingDetails[$key]->delete();
                }
            }
        }

        foreach ($request->variants as $variant) {
            $key = $variant['size'] . '_' . $variant['color'];
            $imagePath = null;

            if (isset($variant['image']) && $variant['image']) {
                $image = $variant['image'];
                $uploadFolder = 'uploads/products/';
                $savePath = public_path($uploadFolder);

                if (!file_exists($savePath)) {
                    mkdir($savePath, 0777, true);
                }

                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $manager->read($image)->resize(800, 800)->save($savePath . '/' . $filename);
                $imagePath = $uploadFolder . $filename;
            }

            if (isset($existingDetails[$key])) {
                $old = $existingDetails[$key];

                $isSamePrice = $old->price == $variant['price'];
                $isSameQuantity = $old->quantity == $variant['quantity'];
                $isSameImage = !$imagePath || $old->image == $imagePath;

                if ($isSamePrice && $isSameQuantity && $isSameImage) {
                    continue;
                }
                Product_details::create([
                    'product_id' => $product->id,
                    'size' => $variant['size'],
                    'color' => $variant['color'],
                    'price' => $variant['price'],
                    'quantity' => $variant['quantity'],
                    'image' => $imagePath ?? $old->image,
                ]);
            } else {
                // Biến thể chưa tồn tại → thêm mới
                Product_details::create([
                    'product_id' => $product->id,
                    'size' => $variant['size'],
                    'color' => $variant['color'],
                    'price' => $variant['price'],
                    'quantity' => $variant['quantity'],
                    'image' => $imagePath ?? null,
                ]);
            }
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

        foreach ($product->product_details as $detail) {

            if (File::exists(public_path($detail->image))) {
                File::delete(public_path($detail->image));
            }
        }

        $product->delete();

        return redirect()->route('admin.products')->with('status', 'Sản phẩm đã được xóa thành công!');
    }

    public function product_search(Request $request)
    {
        $search = $request->input('name');

        $products = Product::where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('slug', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        })
            ->orWhereHas('category', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('brand', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('product_details', function ($query) use ($search) {
                $query->where('price', 'like', '%' . $search . '%');
            })
            ->with(['category', 'brand', 'product_details'])
            ->paginate(10);

        return view('admin.products', compact('products', 'search'));
    }


}
