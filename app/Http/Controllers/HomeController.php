<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Product_details;
use App\Models\User;

class HomeController extends Controller
{
    public function welcome()
    {
        $products = Product::whereHas('product_details', fn($q) => $q->where('quantity', '>', 0))
            ->with([
                'product_details' => fn($q) => $q->where('quantity', '>', 0),
                'reviews' // 👈 thêm reviews để hiển thị số sao + số lượt đánh giá
            ])
            ->get();

        $featuredProducts = Product::with(['product_details', 'reviews']) // cũng eager load reviews
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::all(); // hoặc ->where('status', 'active')

        return view('welcome', compact('categories', 'products', 'featuredProducts'));
    }

    public function index()
    {
        return $this->welcome(); // gọi lại hàm welcome để tránh lặp code
    }

    public function shop()
    {
        $products = Product::whereHas('product_details', fn($q) => $q->where('quantity', '>', 0))
            ->with([
                'product_details' => fn($q) => $q->where('quantity', '>', 0),
                'reviews'
            ])
            ->get();

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

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function showProfile()
    {
        return view('auth.profile');
    }

    public function login(Request $request)
    {
        $data = $request->only(['email', 'password']);

        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => 'active'])) {
            Session::put('user', $data['email']);
            $request->session()->flash('success', 'Đăng nhập thành công');
            return redirect()->route('home');
        }

        $request->session()->flash('error', 'Email hoặc mật khẩu không đúng!');
        return back();
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('welcome');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
