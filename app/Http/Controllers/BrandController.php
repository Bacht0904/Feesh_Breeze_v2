<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BrandController extends Controller
{
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
        $rules = [
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'slug' => 'required|unique:brands,slug,' . $request->id,
        ];

        $messages = [
            'name.regex' => 'Tên chỉ được chứa chữ cái và khoảng trắng, không có ký tự đặc biệt.',
            'slug.unique' => 'Slug này đã tồn tại. Vui lòng chọn tên khác.',
        ];

        $request->validate($rules, $messages);

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
        $rules = [
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'slug' => 'required|unique:brands,slug,' . $request->id,
        ];

        $messages = [
            'name.regex' => 'Tên chỉ được chứa chữ cái và khoảng trắng, không có ký tự đặc biệt.',
            'slug.unique' => 'Slug này đã tồn tại. Vui lòng chọn tên khác.',
        ];

        $request->validate($rules, $messages);

        $brand = Brand::find($request->id);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->status = $request->status;
        $brand->save();

        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Thương hiệu đã được sửa thành công!');
    }



    public function delete_brand($id)
    {
        $brand = Brand::find($id);

            $brand->status = 'inactive';
            $brand->save();
            // Product::where('category_id', $category->id)->update(['status' => 'inactive']);

            return redirect()->route('admin.brands')->with('status', 'Loại sản phẩm đã được chuyển sang trạng thái không hoạt động!');

    }

    public function brand_search(Request $request)
    {
        $search = $request->input('name'); // Đổi 'search' => 'name' để khớp với input name trong form

        $brands = Brand::where('name', 'like', '%' . $search . '%')
            ->orWhere('slug', 'like', '%' . $search . '%')
            ->paginate(10);

        return view('admin.brands', compact('brands', 'search'));
    }
}
