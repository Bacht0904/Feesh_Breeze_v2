<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;
use App\Models\Product_details as ProductDetail;

class Review extends Model
{
    protected $fillable = ['user_id', 'product_id', 'product_detail_id', 'rating', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Product detail relationship is optional
    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class, 'product_detail_id');
    }
    public function review()
    {
        return $this->hasOne(Review::class, 'product_detail_id', 'product_detail_id')
            ->where('user_id', auth()->id());
    }
}
