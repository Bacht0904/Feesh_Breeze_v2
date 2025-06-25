namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $slug = $request->input('slug');
        $quantity = $request->input('quant')[1] ?? 1;

        $product = Product::where('slug', $slug)->with('product_details')->firstOrFail();

        $cart = session()->get('cart', []);

        if (isset($cart[$slug])) {
            $cart[$slug]['quantity'] += $quantity;
        } else {
            $cart[$slug] = [
                'name' => $product->name,
                'price' => $product->product_details->first()->price ?? 0,
                'image' => $product->product_details->first()->image ?? '',
                'quantity' => $quantity
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
    }

    public function remove($slug)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$slug])) {
            unset($cart[$slug]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng.');
    }
}
