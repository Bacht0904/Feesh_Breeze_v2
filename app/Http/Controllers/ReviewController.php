<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Models\Product_details as ProductDetail;
use App\Models\Product;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        Review::create([
            'user_id'          => Auth::id(),
            'product_id'       => $request->product_id,
            'product_detail_id' => $request->product_detail_id,
            'rating'           => $request->rating,
            'comment'          => $request->comment,
        ]);

        return back()->with('success', '✅ Cảm ơn bạn đã đánh giá sản phẩm!');
    }
    public function edit($id)
    {
        $review = Review::findOrFail($id);
        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa đánh giá này.');
        }

        return view('user.edit_review', compact('review'));
    
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
        return redirect()->route('product.show', $review->product_id)
            ->with('success', '✅ Đánh giá đã được cập nhật thành công!');
    }
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa đánh giá này.');
        }
        $review->unset('status'); // Ẩn đánh giá thay vì xóa
        return redirect()->route('product.show', $review->product_id)
            ->with('success', '✅ Đánh giá đã được xóa thành công!');
    }
    public function productReviews($productId)
    {
        $product = Product::findOrFail($productId);
        $reviews = $product->reviews()->with('user')->get();

        return view('user.product_reviews', compact('product', 'reviews'));
    }   
    public function productDetailReviews($productDetailId)
    {
        $productDetail = ProductDetail::findOrFail($productDetailId);
        $reviews = $productDetail->reviews()->with('user')->get();

        return view('user.product_detail_reviews', compact('productDetail', 'reviews'));
    }
    public function userReviews()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with(['product', 'productDetail'])
            ->get();

        return view('user.user_reviews', compact('reviews'));
    }
    public function deleteUserReview($id)
    {
        $review = Review::findOrFail($id);
        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa đánh giá này.');
        }
        $review->delete();
        return redirect()->route('user.reviews')
            ->with('success', '✅ Đánh giá đã được xóa thành công!');
    }   
    public function editUserReview($id)
    {
        $review = Review::findOrFail($id);
        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa đánh giá này.');
        }
        return view('user.edit_user_review', compact('review'));
    }
    public function updateUserReview(Request $request, $id)
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
        return redirect()->route('user.reviews')
            ->with('success', '✅ Đánh giá đã được cập nhật thành công!');
    }   
    public function deleteUserReviewConfirm($id)
    {
        $review = Review::findOrFail($id);
        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa đánh giá này.');
        }
        return view('user.delete_user_review_confirm', compact('review'));
    }
    public function confirmDeleteUserReview(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa đánh giá này.');
        }
        $review->delete();
        return redirect()->route('user.reviews')
            ->with('success', '✅ Đánh giá đã được xóa thành công!');
    }
}   
    
