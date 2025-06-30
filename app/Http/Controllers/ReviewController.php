<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Models\Product_details as ProductDetail;
use App\Models\Product;

class ReviewController extends Controller
{
    public function create($product_detail_id)
    {
        $productDetail = ProductDetail::with('product')->findOrFail($product_detail_id);
        return view('review', compact('productDetail'));
    }
    public function store(Request $request)
    {


        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'product_detail_id' => $request->product_detail_id ?? null,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);


        return back()->with('success', 'Đánh giá của bạn đã được gửi!');
    }
}
