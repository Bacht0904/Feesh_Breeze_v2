<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use Auth;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'product_detail_id',
        'product_name',
        'size',
        'color',
        'image',
        'price',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, "order_id");
    }
    public function productDetail()
    {
        return $this->belongsTo(Product_details::class, 'product_detail_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'order_detail_id', 'id');
    }
}
