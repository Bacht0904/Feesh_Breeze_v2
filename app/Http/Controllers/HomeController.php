<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Product_details;
use App\Models\User;

class HomeController extends Controller
{
    public function welcome()
    {
        $wishlistIds = session('wishlist') ? array_keys(session('wishlist')) : [];
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

        return view('welcome', compact('categories', 'products', 'featuredProducts', 'wishlistIds'));
    }

    public function index()
    {
        return $this->welcome(); // gọi lại hàm welcome để tránh lặp code
    }

    public function shop(Request $request)
    {
        $sort = $request->input('sort', 'default');
        $wishlistIds = session('wishlist') ? array_keys(session('wishlist')) : [];

        // Subquery: lấy mỗi product_id kèm min(price)
        $priceSub = DB::table('product_details')
            ->select('product_id', DB::raw('MIN(price) as min_price'))
            ->where('quantity', '>', 0)
            ->groupBy('product_id');

        // Query sản phẩm còn hàng
        $productQuery = Product::query()
            ->whereHas('product_details', fn($q) => $q->where('quantity', '>', 0))
            ->with([
                'lowestPricedDetail' => fn($q) => $q->where('quantity', '>', 0)
                    ->orderBy('price')
                    ->orderBy('size'),
                'reviews',
                'category',
                'brand',
            ]);
        $search = $request->input('search');

        if (!empty($search)) {
            $productQuery->where('name', 'like', '%' . $search . '%');
        }

        // Bộ lọc nâng cao
        if ($request->filled('category')) {
            $productQuery->whereHas('category', fn($q) =>
            $q->where('slug', $request->input('category')));
        }

        if ($request->filled('brand')) {
            $productQuery->whereHas('brand', fn($q) =>
            $q->where('slug', $request->input('brand')));
        }

        if ($request->filled('size')) {
            $productQuery->whereHas('product_details', fn($q) =>
            $q->where('size', $request->input('size')));
        }

        if ($request->filled('color')) {
            $productQuery->whereHas('product_details', fn($q) =>
            $q->whereRaw('LOWER(color) = ?', [strtolower($request->input('color'))]));
        }

        if ($request->filled('min_price')) {
            $productQuery->whereHas('product_details', fn($q) =>
            $q->where('price', '>=', $request->input('min_price')));
        }

        if ($request->filled('max_price')) {
            $productQuery->whereHas('product_details', fn($q) =>
            $q->where('price', '<=', $request->input('max_price')));
        }


        // Sắp xếp
        match ($sort) {
            'price_asc' => $productQuery
                ->joinSub($priceSub, 'pd', fn($join) =>
                $join->on('products.id', '=', 'pd.product_id'))
                ->orderBy('pd.min_price')
                ->select('products.*'),

            'price_desc' => $productQuery
                ->joinSub($priceSub, 'pd', fn($join) =>
                $join->on('products.id', '=', 'pd.product_id'))
                ->orderByDesc('pd.min_price')
                ->select('products.*'),

            'newest' => $productQuery->latest(),

            default => $productQuery->orderBy('products.id'),
        };

        $products = $productQuery->paginate(9)->withQueryString();

        $categories = Category::all();
        $brands = Brand::withCount('products')->get();
        $sizes = Product_details::select('size')->distinct()->pluck('size');
        $colors = Product_details::select('color')->distinct()->pluck('color')->map(fn($color) => [
            'name' => $color,
            'code' => config('colormap')[mb_strtolower(trim($color))] ?? '#cccccc',
        ]);

        return view('user.shop', compact(
            'products',
            'categories',
            'brands',
            'sizes',
            'colors',
            'sort',
            'wishlistIds'
        ));
    }

    public function quickSuggestions()
    {
        $categories = Category::select('name', 'slug')->limit(5)->get();
        $brands     = Brand::select('name', 'slug')->limit(5)->get();
        $products   = Product::with('lowestPricedDetail')
            ->latest()
            ->take(5)
            ->get();
        dd($products);
        return response()->json([
            'categories' => $categories,
            'brands'     => $brands,
            'products'   => $products,
        ]);
    }

    public function suggest(Request $request)
    {
        $keyword = $request->input('keyword');


        $sizes = ProductDetail::select('size')->distinct()->pluck('size');
        $colors = ProductDetail::select('color')->distinct()->pluck('color')->map(function ($color) {
            $hexMap = config('colormap');
            return [
                'name' => $color,
                'code' => $hexMap[mb_strtolower(trim($color))] ?? '#cccccc',
            ];
        });

        $products = Product::where('name', 'like', '%' . $keyword . '%')
            ->with('lowestPricedDetail')
            ->take(5)
            ->get();

        return response()->json($products);
    }


    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'like', '%' . $query . '%')
            ->orWhereHas('product_details', function ($q) use ($query) {
                $q->where('size', 'like', '%' . $query . '%')
                    ->orWhere('color', 'like', '%' . $query . '%');
            })
            ->with(['product_details', 'reviews'])
            ->paginate(9);

        return view('user.search_results', compact('products', 'query'));
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
