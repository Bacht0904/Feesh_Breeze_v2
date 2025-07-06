<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    public function Banners()
    {
        $banners = Banner::orderBy('id', 'asc')->paginate(10);
        return view('admin.Banners', compact('banners'));
    }

    public function add_banner()
    {
        $brands = Brand::orderBy('name')->get();
        return view('admin.banner-add', compact('brands'));
    }

    public function banner_store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255', 'regex:/^[\p{L}0-9\s\/\-\!,]+$/u'],
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'brand_id' => 'required|exists:brands,id',
            // 'description' => 'required|string|max:1024',
        ]);

        $brand = Brand::findOrFail($request->brand_id);

        $banner = new Banner();
        $banner->title = $request->title;
        // $banner->description = $request->description;
        $banner->brand_id = $request->brand_id;

        if ($brand->status === 'inactive')
            $banner->status = 'inactive';
        else
            $banner->status = 'active';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $uploadFolder = 'uploads/Banners/';
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

            $banner->image = $uploadFolder . $filename;
        }

        $banner->save();

        return redirect()->route('admin.banners')->with('status', 'Banner đã được thêm thành công!');
    }

    public function edit_banner($id)
    {
        $banner = Banner::findOrFail($id);
        $brands = Brand::orderBy('name')->get(); // hoặc dùng Brand::all();

        return view('admin.banner-edit', compact('banner', 'brands'));
    }

    public function update_banner(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255', 'regex:/^[\p{L}0-9\s\/\-\!,]+$/u'],
            'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
            // 'description' => 'required|string|max:1024',
            'brand_id' => 'required|exists:brands,id',
        ]);


        $banner = Banner::find($request->id);
        $banner->title = $request->title;
        // $banner->description = $request->description;
        $banner->brand_id = $request->brand_id;

        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($banner->image && File::exists(public_path($banner->image))) {
                File::delete(public_path($banner->image));
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

            $banner->image = $uploadFolder . $filename;
        }

        $banner->save();

        return redirect()->route('admin.banners')->with('status', 'Banner đã được cập nhật thành công!');
    }

    public function toggle_banner_status($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->status = $banner->status === 'active' ? 'inactive' : 'active';
        $banner->save();

        return redirect()->route('admin.banners')->with('status', 'Trạng thái đã được cập nhật!');
    }

    public function delete_banner($id)
    {
        $banner = Banner::find($id);

        // Xóa ảnh nếu có
        if ($banner->image && File::exists(public_path($banner->image))) {
            File::delete(public_path($banner->image));
        }

        $banner->delete();

        return redirect()->route('admin.banners')->with('status', 'Banner đã được xóa thành công!');
    }
}
