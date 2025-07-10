<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Slide;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Product_details;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Banner;

class HomeController extends Controller
{

    public function welcome()
    {

        $banners = Banner::with('brand')
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get()->map(function ($banner) {
                if ($banner->brand) {
                    // Link tới /shop?brand=brand-slug
                    $banner->link = route('shop', [
                        'brand' => $banner->brand->slug
                    ]);
                } else {
                    $banner->link = route('shop.index');
                }
                $banner->cta_text = 'MUA NGAY';
                return $banner;
            });
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $topByOrderCount = DB::table('order_details')
            ->select('product_detail_id', DB::raw('COUNT(DISTINCT order_id) as order_count'))
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('product_detail_id')
            ->orderByDesc('order_count')
            ->limit(8)
            ->pluck('product_detail_id');

        $hotDeals = \App\Models\Product_details::with('product')
            ->whereIn('id', $topByOrderCount)
            ->get();

        // Lấy các wishlist ID từ session
        $wishlistIds = session('wishlist') ? array_keys(session('wishlist')) : [];

        // Danh sách sản phẩm có tồn kho
        // $products = \App\Models\Product::whereHas('product_details', fn($q) => $q->where('quantity', '>', 0))
        //     ->with([
        //         'product_details' => fn($q) => $q->where('quantity', '>', 0),
        //         'reviews'
        //     ])
        //     ->get();
        // $featuredProducts = \App\Models\Product::where('is_new', 1) // chỉ lấy sản phẩm mới
        //     ->with(['product_details' => fn($q) => $q->where('quantity', '>', 0), 'reviews'])
        //     ->latest()
        //     ->take(8)
        //     ->get();
        $products = \App\Models\Product::where('is_new', 1)
            ->whereHas('product_details', fn($q) => $q->where('quantity', '>', 0))
            ->with(['product_details' => fn($q) => $q->where('quantity', '>', 0), 'reviews'])
            ->latest()
            ->take(10)
            ->get();


        // Sản phẩm nổi bật (mới nhất)

        // Danh mục
        $categories = \App\Models\Category::all();

        return view('welcome', compact(
            'categories',
            'products',
            'wishlistIds',
            'hotDeals',
            'banners'
        ));
    }

    public function about()
    {
        $categories = \App\Models\Category::all();
        return view('user.about', compact(
            'categories',
        ));
    }


    public function index()
    {
        return $this->welcome(); // gọi lại hàm welcome để tránh lặp code
    }

    public function shop(Request $request)
    {
        $slides = Slide::active()
            ->orderByDesc('id')
            ->get();
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

        if ($request->filled('price_range')) {
            [$min, $max] = explode(',', str_replace(['[', ']'], '', $request->input('price_range')));

            $productQuery->whereHas('product_details', function ($q) use ($min, $max) {
                $q->whereBetween('price', [$min, $max]);
            });
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
            'best-sellers' => $productQuery
                ->withCount([
                    'order_details as sold_quantity' => fn($q) =>
                    $q->select(DB::raw('SUM(order_details.quantity)'))
                ])


                ->orderByDesc('sold_quantity'),


            default => $productQuery->orderBy('products.id'),
        };

        $products = $productQuery->paginate(9)->withQueryString();

        $categories = Category::all();
        $brands = Brand::withCount('products')->get();
        $sizes = Product_details::select('size')->distinct()->pluck('size');
        $colors = Product_details::pluck('color')
            ->unique()
            ->map(function ($color) {
                $key = mb_strtolower(preg_replace('/\s+/', ' ', trim($color)));
                $code = config('colormap')[$key] ?? '#cccccc';

                if ($code === '#cccccc') {
                    \Log::warning("⛔️ Thiếu map: [$color] → [$key]");
                }

                return [
                    'name' => $color,
                    'code' => $code,
                ];
            });



        return view('user.shop', compact(
            'products',
            'categories',
            'brands',
            'sizes',
            'colors',
            'sort',
            'wishlistIds',
            'slides'
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

    /**
     * Xử lý đăng ký người dùng mới
     */
    public function register(Request $request)
    {
        // Validate đầu vào với thông báo lỗi tiếng Việt
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|max:255|unique:users',
            'password'              => 'required|string|min:6|confirmed',
        ], [
            'name.required'         => 'Bạn chưa nhập họ và tên',
            'name.string'           => 'Họ và tên phải là chuỗi ký tự',
            'name.max'              => 'Họ và tên tối đa 255 ký tự',
            'email.required'        => 'Bạn chưa nhập email',
            'email.email'           => 'Định dạng email không hợp lệ',
            'email.unique'          => 'Email này đã được đăng ký',
            'password.required'     => 'Bạn chưa nhập mật khẩu',
            'password.min'          => 'Mật khẩu phải có tối thiểu 6 ký tự',
            'password.confirmed'    => 'Xác nhận mật khẩu không khớp',
        ]);

        // Tạo người dùng mới

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'user', // mặc định
            'status'   => 'active', // hoặc 'pending' nếu cần kích hoạt
            'avatar'   => 'default-avatar.png', // fallback ảnh mặc định
        ]);

        // Đăng nhập tự động
        Auth::login($user);

        // Chuyển hướng kèm thông báo thành công
        return redirect()->route('welcome')
            ->with('success', 'Chúc mừng bạn đã đăng ký thành công!');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
