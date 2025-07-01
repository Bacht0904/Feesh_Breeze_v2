<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Models\Slide;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class SlideController extends Controller
{
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
            'title' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
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
            'title' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
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
}
