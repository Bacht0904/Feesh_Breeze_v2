<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product_details as ProductDetail;

class Wishlist extends Model
{
    protected $fillable = ['user_id', 'product_detail_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function detail()
    {
        return $this->belongsTo(ProductDetail::class, 'product_detail_id');
    }
}
