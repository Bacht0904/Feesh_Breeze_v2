<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Auth;

class UserController extends Controller
{
    public function users()
    {
        $users = User::orderBy('id', 'asc')->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function add_user()
    {

        return view('admin.user-add');
    }

    public function edit_user($id)
    {
        $user = User::find($id);
        return view('admin.user-edit', compact('user'));
    }

    public function user_store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'email' => 'required|email|min:8|unique:users,email,',
            'phone' => ['required', 'regex:/^0[0-9]{9}$/'],
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->role = 'staff';         // Gán mặc định
        $user->status = 'active';      // Gán mặc định

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $uploadFolder = 'uploads/users/';
            $savePath = public_path($uploadFolder);

            if (!file_exists($savePath)) {
                mkdir($savePath, 0777, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
            $fullPath = $savePath . '/' . $filename;

            $manager = new ImageManager(new Driver());
            $manager->read($avatar->getRealPath())
                ->resize(800, 400)
                ->save($fullPath);

            $user->avatar = $uploadFolder . $filename;
        }



        $user->save();

        return redirect()->route('admin.users')->with('status', 'Người dùng đã được thêm thành công!');
    }

    public function search_user(Request $request)
    {
        $search = $request->input('name');

        $users = User::where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%")
                ->orWhere('role', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%");
        })->paginate(10);

        return view('admin.users', compact('users', 'search'));
    }


    public function update_user(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'email' => 'required|email|min:8|unique:users,email,' . $request->id,
            'phone' => ['required', 'regex:/^0[0-9]{9}$/'],
            'address' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->role = $request->role;
        $user->status = $request->status;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $uploadFolder = 'uploads/users/';
            $savePath = public_path($uploadFolder);

            if (!file_exists($savePath)) {
                mkdir($savePath, 0777, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
            $fullPath = $savePath . '/' . $filename;

            $manager = new ImageManager(new Driver());
            $manager->read($avatar->getRealPath())
                ->resize(800, 400)
                ->save($fullPath);

            $user->avatar = $uploadFolder . $filename;
        }

        $user->save();

        return redirect()->route('admin.users')->with('status', 'Người dùng đã được cập nhật thành công!');
    }



    public function delete_user($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect()->route('admin.users')->with('status', 'Người dùng đã được xóa thành công!');
        } else {
            return redirect()->route('admin.users')->with('error', 'Người dùng không tồn tại!');
        }
    }

    public function contact()
    {
        return view('user.contact');
    }

    public function profile()
    {
        return view('user.profile');
    }
    public function index()
    {
        //
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|',
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $uploadFolder = 'uploads/users/';
            $savePath = public_path($uploadFolder);

            if (!file_exists($savePath)) {
                mkdir($savePath, 0755, true);
            }

            $filename = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
            $fullPath = $savePath . '/' . $filename;

            $manager = new ImageManager(new Driver());
            $manager->read($avatar->getRealPath())
                ->resize(800, 400)
                ->save($fullPath);

            // Optionally xoá avatar cũ
            if ($user->avatar && file_exists(public_path($user->avatar)) && $user->avatar !== 'images/default-avatar.png') {
                unlink(public_path($user->avatar));
            }

            
            $user->avatar = $uploadFolder . $filename;
            $user->save();
        }


        return back()->with('success', 'Cập nhật ảnh đại diện thành công!');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'address' => 'nullable|string',
            'phone'=>'min:10|max:10',
            'current_password' => 'required',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone'=>$request->phone,
        ]);

        return redirect()->route('profile')->with('success', 'Cập nhật thành công!');
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
