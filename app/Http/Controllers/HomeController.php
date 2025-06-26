<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
// Nếu cần sử dụng Brand trong controller này
// Nếu cần sử dụng Product_detail trong controller này
use App\Models\Product_details; // Nếu cần sử dụng ProductDetail trong controller này

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

        $featuredProducts = Product::latest()->take(8)->get();


        if (Auth::check()) {
            $products = Product::with('product_details')->get(); // hoặc ->paginate()
            $categories = Category::all(); // hoặc ->where('status', 'active') nếu có
            return view('welcome', compact('categories', 'products', 'featuredProducts'));
        } else {
            $products = Product::with('product_details')->get(); // hoặc ->paginate()
            $categories = Category::all(); // hoặc ->where('status', 'active') nếu có
            return view('welcome', compact('categories', 'products', 'featuredProducts'));
        }
    }
    public function showProfile()
    {
        return view('auth.profile');
    }

    public function shop()
    {
        $products = Product::with('product_details')->get();
        $categories = Category::all();
        $brands = Brand::withCount('products')->get();

        $sizes = Product_details::select('size')->distinct()->pluck('size');
        $colors = Product_details::select('color')->distinct()->pluck('color')->map(function ($color) {
            $hexMap = config('colormap');
            return [
                'name' => $color,
                'code' => $hexMap[mb_strtolower(trim($color))] ?? '#cccccc',
            ];
        });


        return view('user.shop', compact('products', 'categories', 'brands', 'colors', 'sizes'));
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
