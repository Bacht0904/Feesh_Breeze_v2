<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
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
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
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
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'slug' => 'required|unique:categories,slug,' . $request->id,
        ]);

        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->status = $request->status;

        if (Category::where('slug', $category->slug)->where('id', '!=', $category->id)->exists()) {
            return back()->withErrors(['slug' => 'Slug đã tồn tại, vui lòng nhập lại!'])->withInput();
        }

        $category->save();

        // 🚀 Cập nhật lại trạng thái sản phẩm liên quan
        $products = Product::where('category_id', $category->id)->with(['brand', 'product_details'])->get();

        foreach ($products as $product) {
            $total_quantity = $product->product_details->sum('quantity');

            $newStatus = (
                $category->status === 'inactive' ||
                $product->brand->status === 'inactive' ||
                $total_quantity === 0
            ) ? 'inactive' : 'active';

            if ($product->status !== $newStatus) {
                $product->status = $newStatus;
                $product->save();
            }
        }

        return redirect()->route('admin.categories')->with('status', 'Loại sản phẩm đã được sửa thành công!');
    }

    public function delete_category($id)
    {
        $category = Category::find($id);
        $category->status = 'inactive';
        $category->save();

        // 📌 Ngừng hoạt động sản phẩm gắn với loại này
        Product::where('category_id', $category->id)->update(['status' => 'inactive']);

        return redirect()->route('admin.categories')->with('status', 'Loại sản phẩm đã được chuyển sang trạng thái không hoạt động!');
    }

    public function category_search(Request $request)
    {
        $search = $request->input('name');

        $categories = Category::where('name', 'like', '%' . $search . '%')
            ->orWhere('slug', 'like', '%' . $search . '%')
            ->paginate(10);

        return view('admin.categories', compact('categories', 'search'));
    }
}
