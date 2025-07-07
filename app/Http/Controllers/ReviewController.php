<?php

namespace App\Http\Controllers;

use App\Models\Product_details;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;

class ReviewController extends Controller
{
    public function index(Product $product)
    {

        $productDetail = $product->product_details()->first();

        $reviews = $product->reviews()->where('status', 'active')->latest()->paginate(10);
        $reviewCount = $product->reviews()->count();
        $reviewAvg = $product->reviews()->avg('rating');

        return view('user.reviews', compact('product', 'reviews', 'productDetail', 'reviewCount', 'reviewAvg'));
    }


    public function store(Request $req)
    {

        $data = $req->validate([
            'order_id'          => 'required|exists:orders,id',
            'order_detail_id'   => 'required|exists:order_details,id',
            'product_id'        => 'required|exists:products,id',
            'product_detail_id' => 'nullable|exists:product_details,id',
            'rating'            => 'required|integer|min:1|max:5',
            'comment'           => 'nullable|string',
        ]);

        // xác nhận order thuộc user
        $order = Order::where('id', $data['order_id'])
            ->where('id_user', auth()->id())
            ->firstOrFail();

        // xác nhận detail đúng order
        $order->details()->findOrFail($data['order_detail_id']);


        try {
            $review = Review::create([
                'user_id'           => auth()->id(),
                'order_id'          => $data['order_id'],
                'order_detail_id'   => $data['order_detail_id'],
                'product_id'        => $data['product_id'],
                'product_detail_id' => $data['product_detail_id'],
                'rating'            => $data['rating'],
                'comment'           => $data['comment'],
                'status'            => 'active', // ✅ khớp enum
            ]);

        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return back()->with('success', '✅ Cảm ơn bạn đã đánh giá!');
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa đánh giá này.');
        }

        return back()->with('product.show', $review->product_id)
            ->with('review', $review);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);
        $review = Review::findOrFail($id);
        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa đánh giá này.');
        }
        $review->update([
            'rating'  => $request->rating,
            'comment' => $request->comment,
        ]);
        return redirect()->back()
            ->with('success', '✅ Đánh giá đã được cập nhật thành công!');
    }
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa đánh giá này.');
        }
        $review->status = 0; // Đánh dấu đánh giá là đã xóa (ẩn)
        $review->save();
        // Ẩn đánh giá thay vì xóa
        return redirect()->back()->with('success', '✅ Đánh giá đã được xóa thành công!');
    }
}
