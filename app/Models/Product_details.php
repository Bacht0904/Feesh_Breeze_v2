<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\ProductImage;

class Product_details extends Model
{
    protected $fillable = [
        'product_id',
        'price',
        'size',
        'color',
        'quantity',
        'image',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    // Trong model ProductDetail
    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class,'product_detail_id');
    }

}
