<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Category;
use App\Models\Product;
use App\Models\Product_detail; // Nếu cần sử dụng Product_detail trong controller này

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function welcome()
    {
        $products = Product::with('product_details')->get(); // hoặc ->paginate()
        $categories = Category::all(); // hoặc ->where('status', 'active') nếu có
        return view('welcome', compact('categories', 'products'));
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }
    public function index()
    {
        if (Auth::check()) {
            $products = Product::with('product_details')->get(); // hoặc ->paginate()
            $categories = Category::all(); // hoặc ->where('status', 'active') nếu có
            return view('welcome', compact('categories', 'products'));
        } else {
            $products = Product::with('product_details')->get(); // hoặc ->paginate()
            $categories = Category::all(); // hoặc ->where('status', 'active') nếu có
            return view('welcome', compact('categories', 'products'));
        }
    }
    public function showProfile()
    {
        return view('auth.profile');
    }

    public function shop()
    {
        $products = Product::with('product_details')->get(); // hoặc ->paginate()
        $categories = Category::all(); // hoặc ->where('status', 'active') nếu có
        return view('user.shop', compact('categories', 'products'));
    }
    
    public function login(Request $request)
    {
        $data = $request->all();
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => 'active'])) {
            Session::put('user', $data['email']);
            request()->session()->flash('success', 'Đăng nhập thành công');
            return redirect()->route('home');
        } else {
            request()->session()->flash('error', 'Email hoặc mật khẩu không đúng!');
            return redirect()->back();
        }
    }

    public function logout()
    {
        Session::forget('user');
        Auth::logout();
        request()->session()->flash('success', 'Đăng xuất thành công');
        return back();
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('welcome');
    }
}
